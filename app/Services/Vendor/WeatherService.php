<?php

namespace App\Services\Vendor;

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\Location as LocationModel;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorLocation;
use App\Models\Trigger\VendorType as VendorTypeModel;
use App\Models\Vendor\Weather as WeatherModel;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    const SOURCE_OWM = 'open_weather_map';

    const VALUE_TEMPERATURE = 'temperature';
    const VALUE_WIND = 'wind';
    const VALUE_PRESSURE = 'pressure';
    const VALUE_HUMIDITY = 'humidity';
    const VALUE_CLOUDS = 'clouds';
    const VALUE_RAIN = 'rain';
    const VALUE_SNOW = 'snow';

    const VALUES = [
        self::VALUE_TEMPERATURE,
        self::VALUE_WIND,
        self::VALUE_PRESSURE,
        self::VALUE_HUMIDITY,
        self::VALUE_CLOUDS,
        self::VALUE_RAIN,
        self::VALUE_SNOW,
    ];

    protected string $url = 'http://api.openweathermap.org/data/2.5/weather';

    /**
     * @param int    $id
     * @param string $lang
     * @return array|mixed
     * @throws RequestException
     */
    public function findById(int $id, string $lang = LanguageModel::LANGUAGE_BY_DEFAULT)
    {
        return $this->request([
            'id'   => $id,
            'lang' => $lang,
        ]);
    }

    /**
     * @param string $name
     * @param string $lang
     * @return array|mixed
     * @throws RequestException
     */
    public function findByName(string $name, string $lang = LanguageModel::LANGUAGE_BY_DEFAULT)
    {
        return $this->request([
            'q'    => $name,
            'lang' => $lang,
        ]);
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws RequestException
     */
    public function request(array $data)
    {
        $response = Http::get($this->url, array_merge($data, [
            'APPID' => config('services.owm.app_id'),
            'units' => 'metric',
        ]));

        $response->throw();

        return $response->json();
    }

    /**
     * @param LocationModel $city
     * @return array|mixed|null
     */
    public function getWeatherByNames(LocationModel $city)
    {
        $weather = null;

        $country = $city->parents()
            ->where('type', LocationModel::TYPE_COUNTRY)
            ->first();

        foreach ($city->name as $lang => $value) {
            $value = explode('/', $value);

            try {
                $countryAlpha2 = $country->parameters['alpha2'] ?? null;

                $weather = $this->findByName(trim(trim(Arr::first($value)) . ',' . mb_strtolower($countryAlpha2), ','), $lang);

                // We only need the first cycle.
                // The loop will continue if an exception was thrown by API

                $city->parameters = [
                    'owm_id' => $weather['id'],
                ];

                $city->save();

                break;
            } catch (Exception  $exception) {
                // Do nothing
            }
        }

        return $weather;
    }

    /**
     * @param int $id
     * @return array
     * @throws RequestException
     */
    public function getTranslationsById(int $id)
    {
        $languages = LanguageModel::query()
            ->get();

        return $languages->mapWithKeys(function ($language) use ($id) {
            $weather = $this->findById($id, $language->code);

            return [
                $language->code => $weather['name'],
            ];
        })->toArray();
    }

    /**
     * @throws RequestException
     */
    public function updateWeather()
    {
        $locations = LocationModel::query()
            ->where('type', LocationModel::TYPE_CITY)
            ->get();

        $vendors = VendorTypeModel::query()
            ->whereHas('vendor', function ($vendor) {
                $vendor->where('type', Vendor::TYPE_WEATHER)
                    ->where('source', self::SOURCE_OWM);
            })
            ->get();

        $locations->each(function (LocationModel $location) use ($vendors) {
            try {
                if (!is_null($location->parameters) && array_key_exists('owm_id', $location->parameters)) {
                    $weather = $this->findById($location->parameters['owm_id']);
                } else {
                    $weather = $this->getWeatherByNames($location);
                }

                if ($weather !== null) {
                    $location->vendorsTypes()->sync($vendors);
                    $location->load('vendorsTypes');

                    foreach (self::VALUES as $valueType) {
                        $value = null;

                        switch ($valueType) {
                            case self::VALUE_TEMPERATURE:
                                $value = round($weather['main']['temp'] ?? null, 1);
                                break;
                            case self::VALUE_WIND:
                                $value = round($weather['wind']['speed'] ?? null, 1);
                                break;
                            case self::VALUE_PRESSURE:
                                $value = round($weather['main']['pressure'] ?? null);
                                break;
                            case self::VALUE_HUMIDITY:
                                $value = round($weather['main']['humidity'] ?? null);
                                break;
                            case self::VALUE_CLOUDS:
                                $value = round($weather['clouds']['all'] ?? null);
                                break;
                            case self::VALUE_RAIN:
                                $value = round($weather['rain']['1h'] ?? null);
                                break;
                            case self::VALUE_SNOW:
                                $value = round($weather['snow']['1h'] ?? null);
                                break;
                        }

                        if (!is_null($value)) {
                            /** @var Vendor $vendorType */
                            $vendorType = $location->vendorsTypes()
                                ->where('type', $valueType)
                                ->first();

                            /** @var VendorLocation $vendorLocation */
                            $vendorLocation = $vendorType->pivot;

                            WeatherModel::query()
                                ->create([
                                    'vendor_type_id'     => $vendorType->id,
                                    'vendor_location_id' => $vendorLocation->id,
                                    'value'              => $value,
                                ]);
                        }
                    }
                }
            } catch (Exception $exception) {
                throw $exception;
            }
        });
    }
}
