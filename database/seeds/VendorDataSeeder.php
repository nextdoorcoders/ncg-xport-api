<?php

use App\Models\Account\Language as LanguageModel;
use App\Models\Trigger\Vendor as VendorModel;
use App\Services\Vendor\Classes\Weather\Clouds;
use App\Services\Vendor\Classes\Weather\Humidity;
use App\Services\Vendor\Classes\Weather\Pressure;
use App\Services\Vendor\Classes\Weather\Rain;
use App\Services\Vendor\Classes\Weather\Snow;
use App\Services\Vendor\Classes\Weather\Temperature;
use App\Services\Vendor\Classes\Weather\Wind;
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
        VendorModel::query()
            ->create([
                'trigger_class'      => Temperature::class,
                'type'               => VendorModel::TYPE_TEMPERATURE,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Air temperature (С°)',
                    LanguageModel::LANGUAGE_RU => 'Температура воздуха (С°)',
                    LanguageModel::LANGUAGE_UK => 'Температура повітря (С°)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Air temperature with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Температура воздуха с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Температура повітря з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '-15',
                    'max' => '45',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/temperature.svg')),
                ],
            ]);

        VendorModel::query()
            ->create([
                'trigger_class'      => Wind::class,
                'type'               => VendorModel::TYPE_WIND,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Wind speed (m/s)',
                    LanguageModel::LANGUAGE_RU => 'Скорость ветра (м/с)',
                    LanguageModel::LANGUAGE_UK => 'Швидкість вітру (м/с)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Wind speed with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Скорость ветра с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Швидкість вітру з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '0',
                    'max' => '10',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/wind.svg')),
                ],
            ]);

        VendorModel::query()
            ->create([
                'trigger_class'      => Clouds::class,
                'type'               => VendorModel::TYPE_CLOUDS,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Cloudiness (%)',
                    LanguageModel::LANGUAGE_RU => 'Облачность (%)',
                    LanguageModel::LANGUAGE_UK => 'Хмарність (%)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Cloudiness with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Облачность с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Хмарність з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '0',
                    'max' => '100',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/clouds.svg')),
                ],
            ]);

        VendorModel::query()
            ->create([
                'trigger_class'      => Rain::class,
                'type'               => VendorModel::TYPE_RAIN,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Rain precipitation (mm)',
                    LanguageModel::LANGUAGE_RU => 'Дождь осадки (мм)',
                    LanguageModel::LANGUAGE_UK => 'Дощ опади (мм)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Precipitation (rain) with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Осадки (дождь) с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Опади (дощ) з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '0',
                    'max' => '20',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/rain.svg')),
                ],
            ]);

        VendorModel::query()
            ->create([
                'trigger_class'      => Snow::class,
                'type'               => VendorModel::TYPE_SNOW,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Snow precipitation (mm)',
                    LanguageModel::LANGUAGE_RU => 'Снег осадки (мм)',
                    LanguageModel::LANGUAGE_UK => 'Сніг опади (мм)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Precipitation (snow) with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Осадки (снег) с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Опади (сніг) з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '0',
                    'max' => '20',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/snow.svg')),
                ],
            ]);

        VendorModel::query()
            ->create([
                'trigger_class'      => Pressure::class,
                'type'               => VendorModel::TYPE_PRESSURE,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Atmosphere pressure (hPa)',
                    LanguageModel::LANGUAGE_RU => 'Атмосферное давление (hPa)',
                    LanguageModel::LANGUAGE_UK => 'Атмосферний тиск (hPa)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Atmosphere pressure with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Атмосферное давление с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Атмосферний тиск з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '854',
                    'max' => '1086',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/pressure.svg')),
                ],
            ]);

        VendorModel::query()
            ->create([
                'trigger_class'      => Humidity::class,
                'type'               => VendorModel::TYPE_HUMIDITY,
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Air humidity (%)',
                    LanguageModel::LANGUAGE_RU => 'Влажность воздуха (%)',
                    LanguageModel::LANGUAGE_UK => 'Влажність повітря (%)',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Air humidity with reference to the city',
                    LanguageModel::LANGUAGE_RU => 'Влажность воздуха с привязкой к городу',
                    LanguageModel::LANGUAGE_UK => 'Влажність повітря з прив\'язкою до міста',
                ],
                'default_parameters' => [
                    'min' => '0',
                    'max' => '100',
                ],
                'settings'           => [
                    'color' => '#3699FF',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/humidity.svg')),
                ],
            ]);
    }
}
