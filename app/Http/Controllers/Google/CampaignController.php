<?php

namespace App\Http\Controllers\Google;

use App\Http\Controllers\Controller;
use App\Http\Resources\Google\Campaign as CampaignResource;
use App\Models\Marketing\Account;
use App\Services\Google\AdWords\CampaignService;

class CampaignController extends Controller
{
    protected CampaignService $campaignService;

    /**
     * CampaignController constructor.
     *
     * @param CampaignService $campaignService
     */
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * @param Account $account
     * @return CampaignResource
     * @throws \App\Exceptions\MessageException
     */
    public function allCampaigns(Account $account)
    {
        $response = $this->campaignService
            ->allCampaigns($account);

        return new CampaignResource($response);
    }
}
