<?php

namespace Database\Seeders;

use App\Models\Access\Role as RoleModel;
use App\Models\Account\Language;
use App\Models\Account\Language as LanguageModel;
use App\Models\Account\User as UserModel;
use Illuminate\Database\Seeder;

class AccountUserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var LanguageModel $language */
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
                    'name'        => mb_strtoupper($role->name),
                    'email'       => sprintf('%s@gmail.com', $role->name),
                    'password'    => '123123123',
                ]);

            $user->assignRole($role);
        });
    }
}
