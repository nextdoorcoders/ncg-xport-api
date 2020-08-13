<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\Account as AccountModel;
use App\Models\Marketing\Campaign;
use App\Repositories\Google\AdWords\CampaignRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CampaignController extends Controller
{
    protected CampaignRepository $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param AccountModel $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountAllCampaigns(AccountModel $account)
    {
        $response = $account->campaigns()
            ->get();

        return response()->json($response);
    }

    public function accountAddCampaign(Request $request, AccountModel $account)
    {
        $data = $request->all();

        $campaign = $account->campaigns()
            ->create($data);

        return response()->json($campaign);
    }

    /**
     * @param Campaign $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCampaign(Campaign $campaign)
    {
        return response()->json($campaign);
    }

    /**
     * @param Request  $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCampaign(Request $request, Campaign $campaign)
    {
        $data = $request->all();

        $campaign->update($data);

        $this->campaignRepository->setAccount($campaign->account);
        $this->campaignRepository->update($campaign);

        return response()->json($campaign->fresh());
    }

    /**
     * @param Campaign $campaign
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response|object
     * @throws \Exception
     */
    public function deleteCampaign(Campaign $campaign)
    {
        $campaign->delete();

        return response()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
