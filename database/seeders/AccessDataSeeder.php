<?php

namespace Database\Seeders;

use App\Models\Access\Permission as PermissionModel;
use App\Models\Access\Role as RoleModel;
use Illuminate\Database\Seeder;

class AccessDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var PermissionModel $permission */
        $permission = PermissionModel::query()
            ->create([
                'name' => 'create maps'
            ]);

        /** @var RoleModel $role */
        $role = RoleModel::query()
            ->create([
                'name' => 'User',
            ]);

        $role->givePermissionTo($permission);

        $role = RoleModel::query()
            ->create([
                'name' => 'Moderator',
            ]);

        $role = RoleModel::query()
            ->create([
                'name' => 'Admin',
            ]);

        $permission = PermissionModel::query()
            ->create([
                'name' => 'switch user'
            ]);

        $role = RoleModel::query()
            ->create([
                'name' => 'Supervisor',
            ]);

        $role->givePermissionTo($permission);
    }
}
