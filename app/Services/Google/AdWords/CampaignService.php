<?php

namespace App\Services\Google\AdWords;

use EbashuOnHolidays\Google\Repositories\AdWords\CampaignRepository;

class CampaignService
{
    protected CampaignRepository $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    public function index()
    {
        return $this->campaignRepository->paginate();
    }
}
