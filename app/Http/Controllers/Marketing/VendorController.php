<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\VendorCollection;
use App\Models\Marketing\Project;
use App\Services\Marketing\VendorService;

class VendorController extends Controller
{
    protected VendorService $vendorService;

    /**
     * VendorController constructor.
     *
     * @param VendorService $vendorService
     */
    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * @return VendorCollection
     */
    public function allVendors()
    {
        $response = $this->vendorService->allVendors();

        return new VendorCollection($response);
    }

    /**
     * @param Project $project
     * @return VendorCollection
     */
    public function freeProjectVendors(Project $project)
    {
        $response = $this->vendorService->freeProjectVendors($project);

        return new VendorCollection($response);
    }

    /**
     * @param Project $project
     * @return VendorCollection
     */
    public function busyProjectVendors(Project $project)
    {
        $response = $this->vendorService->busyProjectVendors($project);

        return new VendorCollection($response);
    }
}
