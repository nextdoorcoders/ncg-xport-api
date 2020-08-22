<?php

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\City as CityModel;
use App\Models\Marketing\Vendor as VendorModel;
use App\Services\Marketing\Vendor\Classes\Temperature;
use App\Services\Marketing\Vendor\Classes\Wind;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class VendorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Collection $cities */
        $cities = CityModel::query()
            ->get();

        /** @var VendorModel $vendor */
        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Temperature::class,
                'type'          => VendorModel::TYPE_TEMPERATURE,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Air temperature',
                    LanguageModel::LANGUAGE_RU => 'Температура воздуха',
                    LanguageModel::LANGUAGE_UK => 'Температура повітря',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Air temperature with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Температура воздуха с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Температура повітря з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);

        /** @var VendorModel $vendor */
        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Wind::class,
                'type'          => VendorModel::TYPE_WIND,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Wind speed',
                    LanguageModel::LANGUAGE_RU => 'Скорость ветра',
                    LanguageModel::LANGUAGE_UK => 'Швидкість вітру',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Wind speed with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Скорость ветра с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Швидкість вітру з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);
    }
}
