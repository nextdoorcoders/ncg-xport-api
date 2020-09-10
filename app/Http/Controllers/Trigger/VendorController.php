<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Http\Resources\Trigger\VendorCollection;
use App\Services\Trigger\VendorService;

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
