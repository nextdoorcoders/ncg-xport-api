<?php

namespace App\Services\Access;

use App\Models\Access\Permission as PermissionModel;
use App\Models\Access\Role as RoleModel;
use App\Models\Account\User as UserModel;
use Exception;
use Illuminate\Support\Facades\DB;

class RoleService
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allRoles()
    {
        return RoleModel::query()
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return RoleModel
     */
    public function createRole(UserModel $user, array $data)
    {
        /** @var RoleModel $role */
        $role = RoleModel::query()
            ->create($data);

        return $this->readRole($role, $user);
    }

    /**
     * @param RoleModel $role
     * @param UserModel $user
     * @return RoleModel
     */
    public function readRole(RoleModel $role, UserModel $user)
    {
        return $role->refresh();
    }

    /**
     * @param RoleModel $role
     * @param UserModel $user
     * @param array     $data
     * @return RoleModel
     */
    public function updateRole(RoleModel $role, UserModel $user, array $data)
    {
        $role->fill($data);
        $role->save();

        return $this->readRole($role, $user);
    }

    /**
     * @param RoleModel $role
     * @param UserModel $user
     * @throws Exception
     */
    public function deleteRole(RoleModel $role, UserModel $user)
    {
        try {
            DB::beginTransaction();

            $role->delete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    /**
     * @param RoleModel       $role
     * @param PermissionModel $permission
     */
    public function givePermissionTo(RoleModel $role, PermissionModel $permission): void
    {
        $role->givePermissionTo($permission);
    }

    /**
     * @param RoleModel       $role
     * @param PermissionModel $permission
     */
    public function revokePermissionTo(RoleModel $role, PermissionModel $permission): void
    {
        $role->revokePermissionTo($permission);
    }
}
