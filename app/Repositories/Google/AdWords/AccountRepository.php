<?php

namespace App\Repositories\Google\AdWords;

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\AdWords\v201809\mcm\ManagedCustomerService;

class AccountRepository extends AdWords
{
    const PAGE_LIMIT = 500;

    public function paginate()
    {
        /** @var AdWordsSession $session */
        $session = $this->sessionBuilder->build();

        $managedCustomerService = $this->services->get($session, ManagedCustomerService::class);

        // Create selector.
        $selector = new Selector();
        $selector->setFields(['CustomerId', 'Name']);
        $selector->setOrdering([new OrderBy('CustomerId', SortOrder::ASCENDING)]);
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

        // Maps from customer IDs to accounts and links.
        $customerIdsToAccounts = [];
        $customerIdsToChildLinks = [];
        $customerIdsToParentLinks = [];

        $totalNumEntries = 0;
        do {
            // Make the get request.
            $page = $managedCustomerService->get($selector);

            // Create links between manager and clients.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                if ($page->getLinks() !== null) {
                    foreach ($page->getLinks() as $link) {
                        // Cast the indexes to string to avoid the issue when 32-bit PHP
                        // automatically changes the IDs that are larger than the 32-bit max
                        // integer value to negative numbers.
                        $managerCustomerId = strval($link->getManagerCustomerId());
                        $customerIdsToChildLinks[$managerCustomerId][] = $link;
                        $clientCustomerId = strval($link->getClientCustomerId());
                        $customerIdsToParentLinks[$clientCustomerId] = $link;
                    }
                }
                foreach ($page->getEntries() as $account) {
                    $customerIdsToAccounts[strval($account->getCustomerId())] = $account;
                }
            }

            // Advance the paging index.
            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + self::PAGE_LIMIT
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return $customerIdsToAccounts;
    }
}
