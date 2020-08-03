<?php

use App\Models\Account\Language as LanguageModel;
use Illuminate\Database\Seeder;

class LanguageDataSeeder extends Seeder
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
                'is_primary' => true,
            ],
            [
                'name' => 'Español',
                'code' => LanguageModel::LANGUAGE_ES,
            ],
            [
                'name' => 'Русский язык',
                'code' => LanguageModel::LANGUAGE_RU,
            ],
            [
                'name' => 'Português',
                'code' => LanguageModel::LANGUAGE_PT,
            ],
            [
                'name' => 'Français',
                'code' => LanguageModel::LANGUAGE_FR,
            ],
            [
                'name' => 'Deutsch',
                'code' => LanguageModel::LANGUAGE_DE,
            ],
            [
                'name' => 'Italiano',
                'code' => LanguageModel::LANGUAGE_IT,
            ],
            [
                'name' => 'Українська мова',
                'code' => LanguageModel::LANGUAGE_UK,
            ],
        ]);

        $data->each(function ($language) {
            LanguageModel::query()
                ->create($language);
        });
    }
}
