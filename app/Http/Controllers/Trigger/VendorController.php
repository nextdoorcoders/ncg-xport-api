<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trigger\Vendor as VendorRequest;
use App\Http\Resources\Trigger\Vendor as VendorResource;
use App\Http\Resources\Trigger\VendorCollection;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Vendor as VendorModel;
use App\Services\Trigger\VendorService;
use Exception;
use Illuminate\Http\Response;

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
     * @param VendorRequest $request
     * @return VendorResource
     */
    public function createVendor(VendorRequest $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->vendorService->createVendor($user, $data);

        return new VendorResource($response);
    }

    /**
     * @param VendorModel $vendor
     * @return VendorResource
     */
    public function readVendor(VendorModel $vendor)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->vendorService->readVendor($vendor, $user);

        return new VendorResource($response);
    }

    /**
     * @param VendorRequest $request
     * @param VendorModel   $vendor
     * @return VendorResource
     */
    public function updateVendor(VendorRequest $request, VendorModel $vendor)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->vendorService->updateVendor($vendor, $user, $data);

        return new VendorResource($response);
    }

    /**
     * @param VendorModel $vendor
     * @return Response
     * @throws Exception
     */
    public function deleteVendor(VendorModel $vendor)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->vendorService->deleteVendor($vendor, $user);

        return response()->noContent();
    }
}
