<?php

namespace App\Repositories\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Marketing\Campaign as CampaignModel;
use Carbon\Carbon;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\v201809\cm\ApiException;
use Google\AdsApi\AdWords\v201809\cm\Campaign;
use Google\AdsApi\AdWords\v201809\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Illuminate\Support\Collection;

class CampaignRepository extends AdWords
{
    const PAGE_LIMIT = 500;

    /**
     * @return Collection
     */
    public function paginate()
    {
        /** @var AdWordsSession $session */
        $session = $this->sessionBuilder->build();

        /** @var CampaignService $campaignService */
        $campaignService = $this->services->get($session, CampaignService::class);

        $orderings = [
            new OrderBy('Name', SortOrder::ASCENDING),
        ];

        $campaigns = $this->select($campaignService, [], $orderings);

        return collect($campaigns);
    }

    /**
     * @param CampaignModel $campaignModel
     * @return mixed
     */
    public function find(CampaignModel $campaignModel)
    {
        /** @var AdWordsSession $session */
        $session = $this->sessionBuilder->build();

        /** @var CampaignService $campaignService */
        $campaignService = $this->services->get($session, CampaignService::class);

        $predicates = [
            new Predicate('Id', PredicateOperator::EQUALS, [
                $campaignModel->campaign_id,
            ]),
        ];

        $orderings = [
            new OrderBy('Name', SortOrder::ASCENDING),
        ];

        $campaigns = $this->select($campaignService, $predicates, $orderings);

        return collect($campaigns)->first();
    }

    public function update(CampaignModel $campaignModel, string $status)
    {
        /** @var AdWordsSession $session */
        $session = $this->sessionBuilder->build();

        $campaignService = $this->services->get($session, CampaignService::class);

        $operations = [];
        // Create a campaign with PAUSED status.
        $campaign = new Campaign();
        $campaign->setId($campaignModel->campaign_id);
        $campaign->setStatus($status);

        // Create a campaign operation and add it to the list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Update the campaign on the server.
        $result = $campaignService->mutate($operations);

        return collect($result->getValue())->first();
    }

    protected function select($campaignService, $predicates = [], $orderings = [])
    {
        // Create selector.
        $selector = new Selector();
        $selector->setFields(['Id', 'Name', 'Status', 'StartDate', 'EndDate']);
        $selector->setPredicates($predicates);
        $selector->setOrdering($orderings);
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

        $campaigns = [];

        $totalNumEntries = 0;
        do {
            // Make the get request.
            $page = $campaignService->get($selector);

            // Display results.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $campaign) {
                    $campaigns[] = (object)[
                        'id'         => $campaign->getId(),
                        'name'       => str_replace('_', ' ', $campaign->getName()),
                        'status'     => $campaign->getStatus(),
                        'start_date' => Carbon::createFromFormat('Y-m-d', preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1-$2-$3', $campaign->getStartDate()))->toDateString(),
                        'end_date'   => Carbon::createFromFormat('Y-m-d', preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1-$2-$3', $campaign->getEndDate()))->toDateString(),
                    ];
                }
            }

            // Advance the paging index.
            $selector->getPaging()->setStartIndex($selector->getPaging()->getStartIndex() + self::PAGE_LIMIT);
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return $campaigns;
    }

//    public function create()
//    {
//        $session = $this->sessionBuilder
//            ->withDeveloperToken(config('google.ADWORDS.developerToken'))
//            ->withClientCustomerId(config('google.ADWORDS.clientCustomerId'))
//            ->build();
//
//        $budgetService = $this->services->get($session, BudgetService::class);
//
//        // Create the shared budget (required).
//        $budget = new Budget();
//        $budget->setName('Interplanetary Cruise Budget #' . uniqid());
//        $money = new Money();
//        $money->setMicroAmount(50000000);
//        $budget->setAmount($money);
//        $budget->setDeliveryMethod(BudgetBudgetDeliveryMethod::STANDARD);
//
//        $operations = [];
//
//        // Create a budget operation.
//        $operation = new BudgetOperation();
//        $operation->setOperand($budget);
//        $operation->setOperator(Operator::ADD);
//        $operations[] = $operation;
//
//        // Create the budget on the server.
//        $result = $budgetService->mutate($operations);
//        $budget = $result->getValue()[0];
//
//        $campaignService = $this->services->get($session, CampaignService::class);
//
//        $operations = [];
//
//        // Create a campaign with required and optional settings.
//        $campaign = new Campaign();
//        $campaign->setName('Interplanetary Cruise #' . uniqid());
//        $campaign->setAdvertisingChannelType(AdvertisingChannelType::SEARCH);
//
//        // Set shared budget (required).
//        $campaign->setBudget(new Budget());
//        $campaign->getBudget()->setBudgetId($budget->getBudgetId());
//
//        // Set bidding strategy (required).
//        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
//        $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPC);
//
//        // You can optionally provide a bidding scheme in place of the type.
//        $biddingScheme = new ManualCpcBiddingScheme();
//        $biddingStrategyConfiguration->setBiddingScheme($biddingScheme);
//
//        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
//
//        // Set network targeting (optional).
//        $networkSetting = new NetworkSetting();
//        $networkSetting->setTargetGoogleSearch(true);
//        $networkSetting->setTargetSearchNetwork(true);
//        $networkSetting->setTargetContentNetwork(true);
//        $campaign->setNetworkSetting($networkSetting);
//
//        // Set additional settings (optional).
//        // Recommendation: Set the campaign to PAUSED when creating it to stop
//        // the ads from immediately serving. Set to ENABLED once you've added
//        // targeting and the ads are ready to serve.
//        $campaign->setStatus(CampaignStatus::PAUSED);
//        $campaign->setStartDate(date('Ymd', strtotime('+1 day')));
//        $campaign->setEndDate(date('Ymd', strtotime('+1 month')));
//
//        // Set frequency cap (optional).
//        $frequencyCap = new FrequencyCap();
//        $frequencyCap->setImpressions(5);
//        $frequencyCap->setTimeUnit(TimeUnit::DAY);
//        $frequencyCap->setLevel(Level::ADGROUP);
//        $campaign->setFrequencyCap($frequencyCap);
//
//        // Set advanced location targeting settings (optional).
//        $geoTargetTypeSetting = new GeoTargetTypeSetting();
//        $geoTargetTypeSetting->setPositiveGeoTargetType(GeoTargetTypeSettingPositiveGeoTargetType::DONT_CARE);
//        $geoTargetTypeSetting->setNegativeGeoTargetType(GeoTargetTypeSettingNegativeGeoTargetType::DONT_CARE);
//        $campaign->setSettings([$geoTargetTypeSetting]);
//
//        // Create a campaign operation and add it to the operations list.
//        $operation = new CampaignOperation();
//        $operation->setOperand($campaign);
//        $operation->setOperator(Operator::ADD);
//        $operations[] = $operation;
//
//        // Create a campaign with only required settings.
//        $campaign = new Campaign();
//        $campaign->setName('Interplanetary Cruise #' . uniqid());
//        $campaign->setAdvertisingChannelType(AdvertisingChannelType::DISPLAY);
//
//        // Set shared budget (required).
//        $campaign->setBudget(new Budget());
//        $campaign->getBudget()->setBudgetId($budget->getBudgetId());
//
//        // Set bidding strategy (required).
//        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
//        $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPC);
//        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
//
//        $campaign->setStatus(CampaignStatus::PAUSED);
//
//        // Create a campaign operation and add it to the operations list.
//        $operation = new CampaignOperation();
//        $operation->setOperand($campaign);
//        $operation->setOperator(Operator::ADD);
//        $operations[] = $operation;
//
//        // Create the campaigns on the server and print out some information for
//        // each created campaign.
//        $result = $campaignService->mutate($operations);
//        foreach ($result->getValue() as $campaign) {
//            printf("Campaign with name '%s' and ID %d was added.\n", $campaign->getName(), $campaign->getId());
//        }
//    }
//
//    public function delete($campaignId)
//    {
//        $session = $this->sessionBuilder
//            ->withDeveloperToken(config('google.ADWORDS.developerToken'))
//            ->withClientCustomerId(config('google.ADWORDS.clientCustomerId'))
//            ->build();
//
//        $campaignService = $this->services->get($session, CampaignService::class);
//
//        $operations = [];
//        // Create a campaign with REMOVED status.
//        $campaign = new Campaign();
//        $campaign->setId($campaignId);
//        $campaign->setStatus(CampaignStatus::REMOVED);
//
//        // Create a campaign operation and add it to the list.
//        $operation = new CampaignOperation();
//        $operation->setOperand($campaign);
//        $operation->setOperator(Operator::SET);
//        $operations[] = $operation;
//
//        // Remove the campaign on the server.
//        $result = $campaignService->mutate($operations);
//
//        $campaign = $result->getValue()[0];
//
//        printf("Campaign with ID %d was removed.\n", $campaign->getId());
//    }
}
