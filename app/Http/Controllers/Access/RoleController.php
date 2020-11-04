<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use App\Http\Resources\Access\Role as RoleResource;
use App\Http\Resources\Access\RoleCollection;
use App\Models\Access\Permission as PermissionModel;
use App\Models\Access\Role as RoleModel;
use App\Models\Account\User as UserModel;
use App\Services\Access\RoleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    protected RoleService $roleService;

    /**
     * RoleController constructor.
     *
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @return RoleCollection
     */
    public function allRoles()
    {
        $response = $this->roleService->allRoles();

        return new RoleCollection($response);
    }

    /**
     * @param Request $request
     * @return RoleResource
     */
    public function createRole(Request $request)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->roleService->createRole($user, $data);

        return new RoleResource($response);
    }

    /**
     * @param RoleModel $role
     * @return RoleResource
     */
    public function readRole(RoleModel $role)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $response = $this->roleService->readRole($role, $user);

        return new RoleResource($response);
    }

    /**
     * @param Request   $request
     * @param RoleModel $role
     * @return RoleResource
     */
    public function updateRole(Request $request, RoleModel $role)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $data = $request->all();

        $response = $this->roleService->updateRole($role, $user, $data);

        return new RoleResource($response);
    }

    /**
     * @param RoleModel $role
     * @return Response
     * @throws Exception
     */
    public function deleteRole(RoleModel $role)
    {
        /** @var UserModel $user */
        $user = auth()->user();

        $this->roleService->deleteRole($role, $user);

        return response()->noContent();
    }

    /**
     * @param RoleModel       $role
     * @param PermissionModel $permission
     * @return Response
     */
    public function givePermissionTo(RoleModel $role, PermissionModel $permission)
    {
        $this->roleService->givePermissionTo($role, $permission);

        return response()->noContent();
    }

    /**
     * @param RoleModel       $role
     * @param PermissionModel $permission
     * @return Response
     */
    public function revokePermissionTo(RoleModel $role, PermissionModel $permission)
    {
        $this->roleService->revokePermissionTo($role, $permission);

        return response()->noContent();
    }
}
