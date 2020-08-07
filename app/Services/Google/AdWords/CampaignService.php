<?php

namespace App\Services\Google\AdWords;

use App\Repositories\Google\AdWords\CampaignRepository as CampaignRepository;

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
