<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataResourceCollection;
use App\Models\Vendor\Logs;
use App\Models\Vendor\States;

class ReportController extends Controller
{
    //
    /**
     * @return \App\Http\Resources\DataResourceCollection
     */
    public function vendors(): DataResourceCollection
    {
        $logs = Logs::query()->orderBy('created_at', 'desc')->get();
        return new DataResourceCollection($logs);
    }

    /**
     * @return \App\Http\Resources\DataResourceCollection
     */
    public function vendorsState(): DataResourceCollection
    {
        $states = States::orderBy('updated_at', 'desc')->get();;
        return new DataResourceCollection($states );
    }
}
