<?php

namespace Database\Seeders;

use App\Models\Account\Language as LanguageModel;
use Illuminate\Database\Seeder;

class AccountLanguageDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = collect([
            [
                'name'       => 'English',
                'code'       => LanguageModel::LANGUAGE_EN,
                'priority'   => [LanguageModel::LANGUAGE_RU, LanguageModel::LANGUAGE_UK],
                'is_primary' => true,
            ],
            [
                'name'     => 'Русский язык',
                'code'     => LanguageModel::LANGUAGE_RU,
                'priority' => [LanguageModel::LANGUAGE_EN, LanguageModel::LANGUAGE_UK],
            ],
            [
                'name'     => 'Українська мова',
                'code'     => LanguageModel::LANGUAGE_UK,
                'priority' => [LanguageModel::LANGUAGE_RU, LanguageModel::LANGUAGE_EN],
            ],
        ]);

        $data->each(function ($language) {
            LanguageModel::query()
                ->create($language);
        });
    }
}
