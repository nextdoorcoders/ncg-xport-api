<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trigger\HistoryCollection;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Trigger\HistoryService;

class HistoryController extends Controller
{
    private HistoryService $historyService;

    /**
     * HistoryController constructor.
     *
     * @param HistoryService $historyService
     */
    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * @param MapModel $map
     * @return HistoryCollection
     */
    public function allHistories(MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->historyService->allHistories($map, $user);

        return new HistoryCollection($response);
    }
}
