<?php

namespace App\Http\Controllers\Geo;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataResourceCollection;
use App\Services\Geo\TimezoneService;

class TimezoneController extends Controller
{
    protected TimezoneService $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    /**
     * @return DataResourceCollection
     */
    public function allTimezones()
    {
        $response = $this->timezoneService->allTimezones();

        return new DataResourceCollection($response);
    }
}
