<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\Location as LocationResource;
use App\Http\Resources\Geo\LocationCollection;
use App\Http\Resources\Geo\VendorTypeCollection;
use App\Models\Account\User as UserModel;
use App\Models\Geo\Location as LocationModel;
use App\Services\Geo\LocationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    /**
     * @return LocationCollection
     */
    public function allLocations()
    {
        $response = $this->locationService->allLocations();

        return new LocationCollection($response);
    }

    /**
     * @param Request $request
     * @return LocationResource
     */
    public function createLocation(Request $request)
    {
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
    public function readLocation(LocationModel $location)
    {
        $response = $this->locationService->readLocation($location);

        return new LocationResource($response);
    }

    /**
     * @param Request       $request
     * @param LocationModel $location
     * @return LocationResource
     */
    public function updateLocation(Request $request, LocationModel $location)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->locationService->updateLocation($location, $data);

        return new LocationResource($response);
    }

    /**
     * @param LocationModel $location
     * @return Response
     * @throws Exception
     */
    public function deleteLocation(LocationModel $location)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->locationService->deleteLocation($location);

        return response()->noContent();
    }

    /**
     * @param LocationModel|null $location
     * @return VendorTypeCollection
     */
    public function readVendors(LocationModel $location = null)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->locationService->readVendors($location, $user);

        return new VendorTypeCollection($response);
    }
}
