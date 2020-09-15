<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Campaign as CampaignResource;
use App\Http\Resources\Marketing\CampaignCollection;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Marketing\Project as ProjectModel;
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
     * @param ProjectModel $project
     * @return CampaignCollection
     */
    public function allCampaigns(ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->campaignService->allCampaigns($project, $user);

        return new CampaignCollection($response);
    }

    /**
     * @param Request      $request
     * @param ProjectModel $project
     * @return CampaignResource
     */
    public function createCampaign(Request $request, ProjectModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->campaignService->createCampaign($project, $user, $data);

        return new CampaignResource($response);
    }

    /**
     * @param ProjectModel  $project
     * @param CampaignModel $campaign
     * @return CampaignResource
     */
    public function readCampaign(ProjectModel $project, CampaignModel $campaign)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->campaignService->readCampaign($project, $campaign, $user);

        return new CampaignResource($response);
    }

    /**
     * @param Request       $request
     * @param ProjectModel  $project
     * @param CampaignModel $campaign
     * @return CampaignResource
     */
    public function updateCampaign(Request $request, ProjectModel $project, CampaignModel $campaign)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->campaignService->updateCampaign($project, $campaign, $user, $data);

        return new CampaignResource($response);
    }

    /**
     * @param ProjectModel  $project
     * @param CampaignModel $campaign
     * @return Response
     * @throws Exception
     */
    public function deleteCampaign(ProjectModel $project, CampaignModel $campaign)
    {
        /** @var UserModel $user */
        $user = auth()->user();


        $this->campaignService->deleteCampaign($project, $campaign, $user);

        return response()->noContent();
    }
}
