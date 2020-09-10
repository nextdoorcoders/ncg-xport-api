<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\Location as LocationResource;
use App\Http\Resources\Geo\LocationCollection;
use App\Models\Account\User as UserModel;
use App\Models\Geo\Location as LocationModel;
use App\Services\Geo\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected LocationService $locationService;

    /**
     * CountryController constructor.
     *
     * @param LocationService $locationService
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function allLocations() {
        $response = $this->locationService->allLocations();

        return new LocationCollection($response);
    }

    public function createLocation(Request $request) {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->locationService->createLocation($data);

        return new LocationResource($response);
    }

    /**
     * @param LocationModel $location
     * @return LocationResource
     */
    public function readLocation(LocationModel $location) {
        $response = $this->locationService->readLocation($location);

        return new LocationResource($response);
    }

    public function updateLocation(Request $request, LocationModel $location) {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->locationService->updateLocation($location, $data);

        return new LocationResource($response);
    }

    public function deleteLocation(LocationModel $location) {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->locationService->deleteLocation($location);

        return response()->noContent();
    }
}
