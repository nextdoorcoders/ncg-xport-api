<?php

namespace Database\Seeders;

use App\Models\Account\Language as LanguageModel;
use App\Models\Trigger\Vendor as VendorModel;
use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
use App\Services\Vendor\Classes\Calendar;
use App\Services\Vendor\Classes\Currency;
use App\Services\Vendor\Classes\MediaSync;
use App\Services\Vendor\Classes\Weather;
use Illuminate\Database\Seeder;

class TriggerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var VendorModel $calendar */
        $calendar = VendorModel::query()
            ->create([
                'callback' => Calendar::class,
                'type'     => VendorModel::TYPE_CALENDAR,
                'source'   => 'system',
                'settings' => null,
            ]);

        /** @var VendorModel $currency */
        $currency = VendorModel::query()
            ->create([
                'callback' => Currency::class,
                'type'     => VendorModel::TYPE_CURRENCY,
                'source'   => 'minfin',
                'settings' => null,
            ]);

        /** @var VendorModel $mediaSync */
        $mediaSync = VendorModel::query()
            ->create([
                'callback' => MediaSync::class,
                'type'     => VendorModel::TYPE_MEDIA_SYNC,
                'source'   => 'ncg',
                'settings' => null,
            ]);

        /** @var VendorModel $weather */
        $weather = VendorModel::query()
            ->create([
                'callback' => Weather::class,
                'type'     => VendorModel::TYPE_WEATHER,
                'source'   => 'open_weather_map',
                'settings' => null,
            ]);

        $calendar->vendorsTypes()
            ->create([
                'type'               => 'calendar',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Calendar',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Calendar (date, time and timezone)',
                ],
                'default_parameters' => [
                    'date_start_at' => null,
                    'date_end_at'   => null,
                    'time_start_at' => null,
                    'time_end_at'   => null,
                    'day_of_week'   => null,
                    'timezone'      => null,
                ],
                'settings'           => [
                    'color' => '#F64E60',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/time-schedule.svg')),
                ],
            ]);

        $currency->vendorsTypes()
            ->create([
                'type'               => 'exchange',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Exchange currency rate',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Exchange currency rate',
                ],
                'default_parameters' => [
                    'from_currency_id' => null,
                    'to_currency_id'   => null,
                    'rate_type'        => CurrencyRateModel::TYPE_OF_RATE_AVG,
                    'rate_min'         => '5',
                    'rate_max'         => '10',
                ],
                'settings'           => [
                    'color' => '#1BC5BD',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/money.svg')),
                ],
            ]);

        $currency->vendorsTypes()
            ->create([
                'type'               => 'interbank',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Interbank currency rate',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Interbank currency rate',
                ],
                'default_parameters' => [
                    'from_currency_id' => null,
                    'to_currency_id'   => null,
                    'rate_type'        => CurrencyRateModel::TYPE_OF_RATE_AVG,
                    'rate_min'         => '5',
                    'rate_max'         => '10',
                ],
                'settings'           => [
                    'color' => '#1BC5BD',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/money.svg')),
                ],
            ]);

        $currency->vendorsTypes()
            ->create([
                'type'               => 'national',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'National bank currency rate',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'National bank currency rate',
                ],
                'default_parameters' => [
                    'from_currency_id' => null,
                    'to_currency_id'   => null,
                    'rate_type'        => CurrencyRateModel::TYPE_OF_RATE_AVG,
                    'rate_min'         => '5',
                    'rate_max'         => '10',
                ],
                'settings'           => [
                    'color' => '#1BC5BD',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/money.svg')),
                ],
            ]);

        $mediaSync->vendorsTypes()
            ->create([
                'type'               => 'tv',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Inter',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'NCG TV sync',
                ],
                'default_parameters' => [
                ],
                'settings'           => [
                    'color' => '#FF9800',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/tv.svg')),
                ],
            ]);

        $mediaSync->vendorsTypes()
            ->create([
                'type'               => 'tv',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => '1+1',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'NCG TV sync',
                ],
                'default_parameters' => [
                ],
                'settings'           => [
                    'color' => '#FF9800',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/tv.svg')),
                ],
            ]);

        $mediaSync->vendorsTypes()
            ->create([
                'type'               => 'tv',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'ICTV',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'NCG TV sync',
                ],
                'default_parameters' => [
                ],
                'settings'           => [
                    'color' => '#FF9800',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/tv.svg')),
                ],
            ]);

        $weather->vendorsTypes()
            ->create([
                'type'               => 'temperature',
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

        $weather->vendorsTypes()
            ->create([
                'type'               => 'wind',
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

        $weather->vendorsTypes()
            ->create([
                'type'               => 'clouds',
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

        $weather->vendorsTypes()
            ->create([
                'type'               => 'rain',
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

        $weather->vendorsTypes()
            ->create([
                'type'               => 'snow',
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

        $weather->vendorsTypes()
            ->create([
                'type'               => 'pressure',
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

        $weather->vendorsTypes()
            ->create([
                'type'               => 'humidity',
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
