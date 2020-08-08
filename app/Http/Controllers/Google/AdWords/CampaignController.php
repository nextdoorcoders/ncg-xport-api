<?php

namespace App\Http\Controllers\Google\AdWords;

use App\Http\Controllers\Controller;
use App\Models\Marketing\Company;
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
     * @param Company $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Company $campaign)
    {
        $response = $this->campaignService->index($campaign);

        return response()
            ->json($response);
    }
}
