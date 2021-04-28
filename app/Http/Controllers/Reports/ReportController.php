<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataResourceCollection;
use App\Models\VendorLog;
use App\Models\VendorState;

class ReportController extends Controller
{
    //
    /**
     * @return \App\Http\Resources\DataResourceCollection
     */
    public function vendors(): DataResourceCollection
    {
        $logs = VendorLog::query()->orderBy('created_at', 'desc')->get();
        return new DataResourceCollection($logs);
    }

    /**
     * @return \App\Http\Resources\DataResourceCollection
     */
    public function vendorsState(): DataResourceCollection
    {
        $states = VendorState::orderBy('updated_at', 'desc')->get();;
        return new DataResourceCollection($states );
    }
}
