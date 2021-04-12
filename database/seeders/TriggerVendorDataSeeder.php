<?php

namespace Database\Seeders;

use App\Enums\Trigger\UptimeRobot\HttpAuthTypeEnum;
use App\Enums\Trigger\UptimeRobot\HttpMethodEnum;
use App\Enums\Trigger\UptimeRobot\KeywordTypeEnum;
use App\Enums\Trigger\UptimeRobot\PostContentTypeEnum;
use App\Enums\Trigger\UptimeRobot\PostTypeEnum;
use App\Enums\Trigger\UptimeRobot\SubtypeEnum;
use App\Enums\Trigger\UptimeRobot\TypeEnum;
use App\Models\Account\Language as LanguageModel;
use App\Models\Trigger\Vendor as VendorModel;
use App\Services\Vendor\Classes\Calendar;
use App\Services\Vendor\Classes\Currency;
use App\Services\Vendor\Classes\Keyword;
use App\Services\Vendor\Classes\MediaSync;
use App\Services\Vendor\Classes\UptimeRobot;
use App\Services\Vendor\Classes\Weather;
use Illuminate\Database\Seeder;

class TriggerVendorDataSeeder extends Seeder
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

        /** @var VendorModel $keyword */
        $keyword = VendorModel::query()
            ->create([
                'callback' => Keyword::class,
                'type'     => VendorModel::TYPE_KEYWORD,
                'source'   => 'google_trends',
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

        /** @var VendorModel $uptimeRobot */
        $uptimeRobot = VendorModel::query()
            ->create([
                'callback' => UptimeRobot::class,
                'type'     => VendorModel::TYPE_UPTIME_ROBOT,
                'source'   => 'uptime_robot',
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
                    'rate_type'        => 'average',
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
                    'rate_type'        => 'average',
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
                    'rate_type'        => 'average',
                    'rate_min'         => '5',
                    'rate_max'         => '10',
                ],
                'settings'           => [
                    'color' => '#1BC5BD',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/money.svg')),
                ],
            ]);

        $keyword->vendorsTypes()
            ->create([
                'type'               => 'checkRank',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Check rank',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Check keyword rank',
                ],
                'default_parameters' => [
                    'keyword'      => null,
                    'keyword_code' => null,
                    'min_rank'     => 0,
                    'max_rank'     => 100,
                    'days_ago'     => 1,
                ],
                'settings'           => [
                    'color' => '#9C27B0',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/google-trends.svg')),
                ],
            ]);

        $keyword->vendorsTypes()
            ->create([
                'type'               => 'compareRank',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Compare rank',
                ],
                'desc'               => [
                    LanguageModel::LANGUAGE_EN => 'Compare keywords rank',
                ],
                'default_parameters' => [
                    'keyword'                => null,
                    'reference_keyword'      => null,
                    'keyword_code'           => null,
                    'reference_keyword_code' => null,
                    'rate_type'              => 'greater',
                    'days_ago'               => 1,
                ],
                'settings'           => [
                    'color' => '#9C27B0',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/google-trends.svg')),
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

        $uptimeRobot->vendorsTypes()
            ->create([
                'type'               => 'http',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Check resource HTTP status',
                ],
                'default_parameters' => [
                    'monitor_id'       => null,
                    'alert_contact_id' => null,
                    'settings'         => [
                        'type'              => TypeEnum::http,
                        'friendly_name'     => 'HTTP',
                        'url'               => null,
                        'interval'          => 300,
                        'http_username'     => null,
                        'http_password'     => null,
                        'http_auth_type'    => HttpAuthTypeEnum::basic,
                        'http_method'       => HttpMethodEnum::get,
                        'post_type'         => PostTypeEnum::key_value,
                        'post_value'        => null,
                        'post_content_type' => PostContentTypeEnum::application_json,
                    ],
                ],
                'settings'           => [
                    'color' => '#8BC34A',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/uptime.svg')),
                ],
            ]);

        $uptimeRobot->vendorsTypes()
            ->create([
                'type'               => 'keyword',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Check resource KEYWORD status',
                ],
                'default_parameters' => [
                    'monitor_id'       => null,
                    'alert_contact_id' => null,
                    'settings'         => [
                        'type'              => TypeEnum::keyword,
                        'friendly_name'     => 'KEYWORD',
                        'url'               => null,
                        'interval'          => 300,
                        'http_username'     => null,
                        'http_password'     => null,
                        'http_auth_type'    => HttpAuthTypeEnum::basic,
                        'http_method'       => HttpMethodEnum::get,
                        'post_type'         => PostTypeEnum::key_value,
                        'post_value'        => null,
                        'post_content_type' => PostContentTypeEnum::application_json,
                        'keyword_type'      => KeywordTypeEnum::exists,
                        'keyword_value'     => null,
                    ],
                ],
                'settings'           => [
                    'color' => '#8BC34A',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/uptime.svg')),
                ],
            ]);

        $uptimeRobot->vendorsTypes()
            ->create([
                'type'               => 'ping',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Check resource PING status',
                ],
                'default_parameters' => [
                    'monitor_id'       => null,
                    'alert_contact_id' => null,
                    'settings'         => [
                        'type'          => TypeEnum::ping,
                        'friendly_name' => 'PING',
                        'url'           => null,
                        'interval'      => 300,
                    ],
                ],
                'settings'           => [
                    'color' => '#8BC34A',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/uptime.svg')),
                ],
            ]);

        $uptimeRobot->vendorsTypes()
            ->create([
                'type'               => 'port',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Check resource PORT status',
                ],
                'default_parameters' => [
                    'monitor_id'       => null,
                    'alert_contact_id' => null,
                    'settings'         => [
                        'type'          => TypeEnum::port,
                        'friendly_name' => 'PORT',
                        'url'           => null,
                        'interval'      => 300,
                        'sub_type'      => SubtypeEnum::http,
                        'port'          => null,
                    ],
                ],
                'settings'           => [
                    'color' => '#8BC34A',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/uptime.svg')),
                ],
            ]);

        $uptimeRobot->vendorsTypes()
            ->create([
                'type'               => 'heartBeat',
                'name'               => [
                    LanguageModel::LANGUAGE_EN => 'Check resource HEART BEAT status',
                ],
                'default_parameters' => [
                    'monitor_id'       => null,
                    'alert_contact_id' => null,
                    'settings'         => [
                        'type'          => TypeEnum::heartbeat,
                        'friendly_name' => 'HEART BEAT',
                        'url'           => null,
                        'interval'      => 300,
                    ],
                ],
                'settings'           => [
                    'color' => '#8BC34A',
                    'icon'  => file_get_contents(resource_path('images/vendor-icons/uptime.svg')),
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
