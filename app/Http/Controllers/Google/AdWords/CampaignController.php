<?php

namespace App\Http\Controllers\Google\AdWords;

use App\Http\Controllers\Controller;
use App\Models\Marketing\Campaign;
use App\Services\Google\AdWords\CampaignService;
use Illuminate\Http\JsonResponse;

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
     * @param Campaign $campaign
     * @return JsonResponse
     */
    public function index(Campaign $campaign)
    {
        $response = $this->campaignService->index($campaign);

        return response()
            ->json($response);
    }
}
