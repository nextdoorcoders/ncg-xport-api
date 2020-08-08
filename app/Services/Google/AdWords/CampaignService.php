<?php

namespace App\Services\Google\AdWords;

use App\Models\Marketing\Company;
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
     * @param Company $campaign
     * @return array
     * @throws \App\Exceptions\MessageException
     */
    public function index(Company $campaign)
    {
        $this->campaignRepository->setCampaign($campaign);

        return $this->campaignRepository->paginate();
    }
}
