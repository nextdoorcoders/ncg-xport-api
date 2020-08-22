<?php

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\City as CityModel;
use App\Models\Marketing\Vendor as VendorModel;
use App\Services\Marketing\Vendor\Classes\Clouds;
use App\Services\Marketing\Vendor\Classes\Humidity;
use App\Services\Marketing\Vendor\Classes\Pressure;
use App\Services\Marketing\Vendor\Classes\Rain;
use App\Services\Marketing\Vendor\Classes\Snow;
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
            ->whereNotNull('owm_id')
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

        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Clouds::class,
                'type'          => VendorModel::TYPE_CLOUDS,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Cloudiness',
                    LanguageModel::LANGUAGE_RU => 'Облачность',
                    LanguageModel::LANGUAGE_UK => 'Хмарність',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Cloudiness with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Облачность с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Хмарність з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);

        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Rain::class,
                'type'          => VendorModel::TYPE_RAIN,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Precipitation (rain)',
                    LanguageModel::LANGUAGE_RU => 'Осадки (дождь)',
                    LanguageModel::LANGUAGE_UK => 'Опади (дощ)',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Precipitation (rain) with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Осадки (дождь) с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Опади (дощ) з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);

        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Snow::class,
                'type'          => VendorModel::TYPE_SNOW,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Precipitation (snow)',
                    LanguageModel::LANGUAGE_RU => 'Осадки (снег)',
                    LanguageModel::LANGUAGE_UK => 'Опади (сніг)',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Precipitation (snow) with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Осадки (снег) с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Опади (сніг) з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);

        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Pressure::class,
                'type'          => VendorModel::TYPE_PRESSURE,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Atmosphere pressure',
                    LanguageModel::LANGUAGE_RU => 'Атмосферное давление',
                    LanguageModel::LANGUAGE_UK => 'Атмосферний тиск',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Atmosphere pressure with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Атмосферное давление с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Атмосферний тиск з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);

        $vendor = VendorModel::query()
            ->create([
                'trigger_class' => Humidity::class,
                'type'          => VendorModel::TYPE_HUMIDITY,
                'name'          => [
                    LanguageModel::LANGUAGE_EN => 'Air humidity',
                    LanguageModel::LANGUAGE_RU => 'Влажность воздуха',
                    LanguageModel::LANGUAGE_UK => 'Влажність повітря',
                ],
                'desc'          => [
                    LanguageModel::LANGUAGE_EN => 'Air humidity with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Влажность воздуха с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Влажність повітря з прив\'язкою до міста',
                ],
            ]);

        $vendor->cities()->sync($cities);
    }
}
