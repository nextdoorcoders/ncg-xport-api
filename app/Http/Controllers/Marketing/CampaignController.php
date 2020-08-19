<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Campaign as CampaignResource;
use App\Http\Resources\Marketing\CampaignCollection;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Account as AccountModel;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Services\Marketing\CampaignService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * @param AccountModel $account
     * @return CampaignCollection
     */
    public function allCampaigns(AccountModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->campaignService->allCampaigns($account, $user);

        return new CampaignCollection($response);
    }

    /**
     * @param Request $request
     * @param AccountModel $account
     * @return CampaignResource
     */
    public function createCampaign(Request $request, AccountModel $account)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->campaignService->createCampaign($account, $user, $data);

        return new CampaignResource($response);
    }

    /**
     * @param CampaignModel $campaign
     * @return CampaignResource
     */
    public function readCampaign(AccountModel $account, CampaignModel $campaign)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->campaignService->readCampaign($account, $campaign, $user);

        return new CampaignResource($response);
    }

    /**
     * @param Request       $request
     * @param AccountModel  $account
     * @param CampaignModel $campaign
     * @return CampaignResource
     */
    public function updateCampaign(Request $request, AccountModel $account, CampaignModel $campaign)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->campaignService->updateCampaign($account, $campaign, $user, $data);

        return new CampaignResource($response);
    }

    /**
     * @param CampaignModel $campaign
     * @return Response
     * @throws Exception
     */
    public function deleteCampaign(AccountModel $account, CampaignModel $campaign)
    {
        /** @var UserModel $user */
        $user = auth()->user();


        $this->campaignService->deleteCampaign($account, $campaign, $user);

        return response()->noContent();
    }
}
