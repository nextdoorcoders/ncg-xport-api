<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\Geo\CountryCollection;
use App\Models\Geo\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function countries()
    {
        $response = Country::query()
            ->get();

        return new CountryCollection($response);
    }
}
