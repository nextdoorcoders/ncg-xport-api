<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\VendorLocationCollection;
use App\Models\Marketing\Project;
use App\Services\Marketing\VendorLocationService;

class VendorLocationController extends Controller
{

    protected VendorLocationService $vendorLocationService;

    /**
     * VendorController constructor.
     *
     * @param VendorLocationService $vendorLocationService
     */
    public function __construct(VendorLocationService $vendorLocationService)
    {
        $this->vendorLocationService = $vendorLocationService;
    }

    /**
     * @param Project $project
     * @return VendorLocationCollection
     */
    public function freeProjectVendorsLocation(Project $project)
    {
        $response = $this->vendorLocationService->freeProjectVendorsLocation($project);

        return new VendorLocationCollection($response);
    }

    /**
     * @param Project $project
     * @return VendorLocationCollection
     */
    public function busyProjectVendorsLocation(Project $project)
    {
        $response = $this->vendorLocationService->busyProjectVendorsLocation($project);

        return new VendorLocationCollection($response);
    }
}
