<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Http\Resources\Access\Permission as PermissionResource;
use App\Http\Resources\Access\PermissionCollection;
use App\Models\Access\Permission as PermissionModel;
use App\Models\Access\Role as RoleModel;
use App\Models\Account\User as UserModel;
use App\Services\Access\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    /**
     * PermissionController constructor.
     *
     * @param PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @return PermissionCollection
     */
    public function allPermissions()
    {
        $response = $this->permissionService->allPermissions();

        return new PermissionCollection($response);
    }

    /**
     * @param Request $request
     * @return PermissionResource
     */
    public function createPermission(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->permissionService->createPermission($user, $data);

        return new PermissionResource($response);
    }

    /**
     * @param PermissionModel $permission
     * @return PermissionResource
     */
    public function readPermission(PermissionModel $permission)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->permissionService->readPermission($permission, $user);

        return new PermissionResource($response);
    }

    /**
     * @param Request         $request
     * @param PermissionModel $permission
     * @return PermissionResource
     */
    public function updatePermission(Request $request, PermissionModel $permission)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->permissionService->updatePermission($permission, $user, $data);

        return new PermissionResource($response);
    }

    /**
     * @param PermissionModel $permission
     * @return Response
     * @throws \Exception
     */
    public function deletePermission(PermissionModel $permission)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->permissionService->deletePermission($permission, $user);

        return response()->noContent();
    }

    /**
     * @param PermissionModel $permission
     * @param RoleModel       $role
     * @return Response
     */
    public function assignRole(PermissionModel $permission, RoleModel $role)
    {
        $this->permissionService->assignRole($permission, $role);

        return response()->noContent();
    }

    /**
     * @param PermissionModel $permission
     * @param RoleModel       $role
     * @return Response
     */
    public function removeRole(PermissionModel $permission, RoleModel $role)
    {
        $this->permissionService->removeRole($permission, $role);

        return response()->noContent();
    }
}
