<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\Country as CountryResource;
use App\Http\Resources\Geo\CountryCollection;
use App\Services\Geo\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected CountryService $countryService;

    /**
     * CountryController constructor.
     *
     * @param CountryService $countryService
     */
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * @return CountryCollection
     */
    public function index()
    {
        $response = $this->countryService->index();

        return new CountryCollection($response);
    }

    /**
     * @param Request $request
     * @return CountryResource
     */
    public function create(Request $request)
    {
        $data = $request->all();

        $response = $this->countryService->createCountry($data);

        return new CountryResource($response);
    }
}
