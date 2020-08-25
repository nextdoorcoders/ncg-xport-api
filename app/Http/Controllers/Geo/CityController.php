<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\City as CityResource;
use App\Http\Resources\Geo\CityCollection;
use App\Models\Geo\State as StateModel;
use App\Services\Geo\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected CityService $cityService;

    /**
     * CountryController constructor.
     *
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * @param StateModel $state
     * @return CityCollection
     */
    public function allByState(StateModel $state)
    {
        $response = $this->cityService->allByState($state);

        return new CityCollection($response);
    }

    /**
     * @param Request    $request
     * @param StateModel $state
     * @return CityResource
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function create(Request $request, StateModel $state)
    {
        $data = $request->all();

        $response = $this->cityService->createCity($state, $data);

        return new CityResource($response);
    }
}
