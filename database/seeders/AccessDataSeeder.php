<?php

namespace Database\Seeders;

use App\Models\Access\Permission as PermissionModel;
use App\Models\Access\Role as RoleModel;
use App\Models\Account\Language;
use App\Models\Account\User as UserModel;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class AccessDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesAndPermissions = collect([
            'user'       => collect([
                'view own maps',
                'create own maps',
                'read own maps',
                'update own maps',
                'delete own maps',

                'view own projects',
                'create own projects',
                'read own projects',
                'update own projects',
                'delete own projects',
            ]),
            'moderator'  => collect([
                'view all maps',

                'view all projects',
            ]),
            'admin'      => collect([
                'view all maps',
                'update all maps',

                'view all projects',
                'update all projects',
            ]),
            'supervisor' => collect(),
        ]);

        $rolesAndPermissions->each(function ($permissions, $roleName) {
            $permissions->map(function ($permissionName) {
                return PermissionModel::query()
                    ->create([
                        'name' => $permissionName,
                    ]);
            });

            /** @var RoleModel $role */
            $role = RoleModel::query()
                ->create([
                    'name' => $roleName,
                ]);

            $role->givePermissionTo($permissions);
        });

        $language = Language::query()
            ->where('code', Language::LANGUAGE_EN)
            ->first();

        $roles = RoleModel::query()
            ->get();

        $roles->each(function (RoleModel $role) use ($language) {
            /** @var UserModel $user */
            $user = UserModel::query()
                ->create([
                    'language_id' => $language->id,
                    'name'        => $role->name,
                    'email'       => sprintf('%s@gmail.com', $role->name),
                    'password'    => 'password',
                ]);

            $user->assignRole($role);
        });
    }
}
