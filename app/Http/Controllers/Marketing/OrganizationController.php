<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Resources\Marketing\Organization as OrganizationResource;
use App\Http\Resources\Marketing\OrganizationCollection;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Organization as OrganizationModel;
use App\Services\Marketing\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    protected OrganizationService $organizationService;

    /**
     * OrganizationController constructor.
     *
     * @param OrganizationService $organizationService
     */
    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * @return OrganizationCollection
     */
    public function allOrganizations()
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->organizationService->allOrganizations($user);

        return new OrganizationCollection($response);
    }

    /**
     * @param Request $request
     * @return OrganizationResource
     */
    public function createOrganization(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->organizationService->createOrganization($user, $data);

        return new OrganizationResource($response);
    }

    /**
     * @param OrganizationModel $Organization
     * @return OrganizationResource
     */
    public function readOrganization(OrganizationModel $Organization)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->organizationService->readOrganization($Organization, $user);

        return new OrganizationResource($response);
    }

    /**
     * @param Request       $request
     * @param OrganizationModel $Organization
     * @return OrganizationResource
     */
    public function updateOrganization(Request $request, OrganizationModel $Organization)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->organizationService->updateOrganization($Organization, $user, $data);

        return new OrganizationResource($response);
    }

    /**
     * @param OrganizationModel $Organization
     * @return Response
     * @throws \Exception
     */
    public function deleteOrganization(OrganizationModel $Organization)
    {
        /** @var UserModel $user */
        $user = auth()->user();


        $this->organizationService->deleteOrganization($Organization, $user);

        return response()->noContent();
    }
}
