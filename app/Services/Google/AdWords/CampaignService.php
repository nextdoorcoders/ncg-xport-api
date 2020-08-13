<?php

namespace App\Services\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Marketing\Account as AccountModel;
use App\Repositories\Google\AdWords\CampaignRepository;

class CampaignService
{
    protected CampaignRepository $campaignRepository;

    /**
     * CampaignService constructor.
     *
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param AccountModel $account
     * @return array
     * @throws MessageException
     */
    public function index(AccountModel $account)
    {
        $this->campaignRepository->setAccount($account);

        return $this->campaignRepository->paginate();
    }
}
