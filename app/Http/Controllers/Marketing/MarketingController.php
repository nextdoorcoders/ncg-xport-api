<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Campaigns\TreeCollection;
use App\Models\Account\User as UserModel;
use App\Services\Marketing\MarketingService;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    protected MarketingService $marketingService;

    /**
     * MarketingController constructor.
     *
     * @param MarketingService $marketingService
     */
    public function __construct(MarketingService $marketingService) {
        $this->marketingService = $marketingService;
    }

    /**
     * @param Request $request
     * @return TreeCollection
     */
    public function campaignsTree(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->marketingService->campaignsTree($user);

        return new TreeCollection($response);
    }
}
