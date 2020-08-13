<?php

namespace App\Http\Controllers\Google\AdWords;

use App\Http\Controllers\Controller;
use App\Models\Marketing\Account;
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
     * @param Account $account
     * @return JsonResponse
     * @throws \App\Exceptions\MessageException
     */
    public function index(Account $account)
    {
        $response = $this->campaignService
            ->index($account);

        return response()
            ->json($response);
    }
}
