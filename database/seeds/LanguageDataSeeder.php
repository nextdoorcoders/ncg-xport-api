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
                'name' => 'Русский язык',
                'code' => LanguageModel::LANGUAGE_RU,
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
