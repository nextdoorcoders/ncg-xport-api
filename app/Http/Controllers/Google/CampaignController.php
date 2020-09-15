<?php

namespace App\Http\Controllers\Google;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Google\Campaign as GoogleCampaignResource;
use App\Models\Marketing\Project as ProjectModel;
use App\Services\Google\CampaignService;

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
     * @return GoogleCampaignResource
     * @throws MessageException
     */
    public function allGoogleCampaigns(ProjectModel $project)
    {
        $response = $this->campaignService
            ->allCampaigns($project);

        return new GoogleCampaignResource($response);
    }
}
