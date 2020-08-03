<?php

namespace App\Http\Controllers\Google\AdWords;

use App\Http\Controllers\Controller;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $response = $this->campaignService->index();

        return response()
            ->json($response);
    }
}
