<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\VendorLog;

class ReportController extends Controller
{
    //
    /**
     */
    public function vendors()
    {
        $logs = VendorLog::paginate(20);
        return collect($logs);
        return $logs;
    }
}
