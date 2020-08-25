<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\VendorCollection;
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
}
