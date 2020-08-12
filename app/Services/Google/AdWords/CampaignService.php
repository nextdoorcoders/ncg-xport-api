<?php

namespace App\Services\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Marketing\Campaign;
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
     * @param Campaign $campaign
     * @return array
     * @throws MessageException
     */
    public function index(Campaign $campaign)
    {
        $this->campaignRepository->setCampaign($campaign);

        return $this->campaignRepository->paginate();
    }
}
