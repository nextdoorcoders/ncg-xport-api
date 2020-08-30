<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\Country as CountryResource;
use App\Http\Resources\Geo\CountryCollection;
use App\Models\Geo\Country as CountryModel;
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
    public function allCountries()
    {
        $response = $this->countryService->allCountries();

        return new CountryCollection($response);
    }

    /**
     * @param Request $request
     * @return CountryResource
     */
    public function createCountry(Request $request)
    {
        $data = $request->all();

        $response = $this->countryService->createCountry($data);

        return new CountryResource($response);
    }

    /**
     * @param CountryModel $country
     * @return CountryResource
     */
    public function readCountry(CountryModel $country)
    {
        $response = $this->countryService->readCountry($country);

        return new CountryResource($response);
    }
}
