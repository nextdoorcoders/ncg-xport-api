<?php

namespace App\Http\Controllers\Google;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Google\Campaign as GoogleCampaignResource;
use App\Models\Trigger\Map as MapModel;
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
     * @param MapModel $map
     * @return GoogleCampaignResource
     * @throws MessageException
     */
    public function allGoogleCampaigns(MapModel $map)
    {
        $response = $this->campaignService
            ->allCampaigns($map);

        return new GoogleCampaignResource($response);
    }
}
