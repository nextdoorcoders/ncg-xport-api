<?php

namespace App\Http\Controllers\Trigger;

use App\Exceptions\MessageException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trigger\Map as MapRequest;
use App\Http\Resources\Trigger\GroupCollection;
use App\Http\Resources\Trigger\Map as MapResource;
use App\Http\Resources\Trigger\MapCollection;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Trigger\MapService;
use Exception;
use Illuminate\Http\Request;
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
     * @param MapModel $map
     * @return MapResource
     */
    public function readMap(MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->mapService->readMap($map, $user);

        return new MapResource($response);
    }

    /**
     * @param MapRequest $request
     * @param MapModel   $map
     * @return MapResource
     * @throws Exception
     */
    public function updateMap(MapRequest $request, MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->mapService->updateMap($map, $user, $data);

        return new MapResource($response);
    }

    /**
     * @param MapModel $map
     * @return Response
     * @throws Exception
     */
    public function deleteMap(MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->mapService->deleteMap($map, $user);

        return response()->noContent();
    }

    /**
     * @param MapModel $map
     * @return MapResource
     */
    public function replicateMap(MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->mapService->replicateMap($map, $user);

        return new MapResource($response);
    }

    /**
     * @param MapModel $map
     * @return GroupCollection
     */
    public function readConditions(MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->mapService->readConditions($map, $user);

        return new GroupCollection($response);
    }

    /**
     * @param Request  $request
     * @param MapModel $map
     * @return GroupCollection
     * @throws MessageException
     */
    public function updateConditions(Request $request, MapModel $map)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->mapService->updateConditions($map, $user, $data);

        return new GroupCollection($response);
    }
}
