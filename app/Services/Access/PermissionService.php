<?php

namespace App\Services\Access;

use App\Models\Access\Permission as PermissionModel;
use App\Models\Access\Role as RoleModel;
use App\Models\Account\User as UserModel;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allPermissions()
    {
        return PermissionModel::query()
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return PermissionModel
     */
    public function createPermission(UserModel $user, array $data)
    {
        /** @var PermissionModel $permission */
        $permission = PermissionModel::query()
            ->create($data);

        return $this->readPermission($permission, $user);
    }

    /**
     * @param PermissionModel $permission
     * @param UserModel       $user
     * @return PermissionModel
     */
    public function readPermission(PermissionModel $permission, UserModel $user)
    {
        return $permission->refresh();
    }

    /**
     * @param PermissionModel $permission
     * @param UserModel       $user
     * @param array           $data
     * @return PermissionModel
     */
    public function updatePermission(PermissionModel $permission, UserModel $user, array $data)
    {
        $permission->fill($data);
        $permission->save();

        return $this->readPermission($permission, $user);
    }

    /**
     * @param PermissionModel $permission
     * @param UserModel       $user
     * @throws Exception
     */
    public function deletePermission(PermissionModel $permission, UserModel $user)
    {
        try {
            DB::beginTransaction();

            $permission->delete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    /**
     * @param PermissionModel $permission
     * @param RoleModel       $role
     */
    public function assignRole(PermissionModel $permission, RoleModel $role)
    {
        $permission->assignRole($role);
    }

    /**
     * @param PermissionModel $permission
     * @param RoleModel       $role
     */
    public function removeRole(PermissionModel $permission, RoleModel $role)
    {
        $permission->removeRole($role);
    }
}
