<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trigger\Map as MapRequest;
use App\Http\Resources\Trigger\Map as MapResource;
use App\Http\Resources\Trigger\MapCollection;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Trigger\MapService;
use Exception;
use Illuminate\Http\Response;

class MapController extends Controller
{
    protected MapService $mapService;

    /**
     * MapController constructor.
     *
     * @param MapService $mapService
     */
    public function __construct(MapService $mapService)
    {
        $this->mapService = $mapService;
    }

    /**
     * @return MapCollection
     */
    public function allMaps()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->mapService->allMaps($user);

        return new MapCollection($response);
    }

    /**
     * @param MapRequest $request
     * @return MapResource
     */
    public function createMap(MapRequest $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->mapService->createMap($user, $data);

        return new MapResource($response);
    }

    /**
     * @param MapModel $project
     * @return MapResource
     */
    public function readMap(MapModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->mapService->readMap($project, $user);

        return new MapResource($response);
    }

    /**
     * @param MapRequest $request
     * @param MapModel   $project
     * @return MapResource
     */
    public function updateMap(MapRequest $request, MapModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->mapService->updateMap($project, $user, $data);

        return new MapResource($response);
    }

    /**
     * @param MapModel $project
     * @return Response
     * @throws Exception
     */
    public function deleteMap(MapModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->mapService->deleteMap($project, $user);

        return response()->noContent();
    }

    /**
     * @param MapModel $project
     * @return MapResource
     */
    public function replicateMap(MapModel $project)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->mapService->replicateMap($project, $user);

        return new MapResource($response);
    }
}
