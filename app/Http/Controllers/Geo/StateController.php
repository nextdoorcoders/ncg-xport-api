<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\State as StateResource;
use App\Http\Resources\Geo\StateCollection;
use App\Models\Geo\Country as CountryModel;
use App\Services\Geo\StateService;
use Illuminate\Http\Request;

class StateController extends Controller
{
    protected StateService $stateService;

    /**
     * CountryController constructor.
     *
     * @param StateService $stateService
     */
    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
    }

    /**
     * @return StateCollection
     */
    public function index()
    {
        $response = $this->stateService->index();

        return new StateCollection($response);
    }

    /**
     * @param Request      $request
     * @param CountryModel $country
     * @return StateResource
     */
    public function create(Request $request, CountryModel $country)
    {
        $data = $request->all();

        $response = $this->stateService->createState($country, $data);

        return new StateResource($response);
    }
}
