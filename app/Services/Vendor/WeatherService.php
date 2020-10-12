<?php

namespace App\Services\Vendor;

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\Location as LocationModel;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorLocation;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class WeatherService
{
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
            ->with([
                'vendors' => function ($vendors) {
                    $vendors->where('vendor_type', Vendor::VENDOR_TYPE_WEATHER);
                },
            ])
            ->where('type', LocationModel::TYPE_CITY)
            ->get();

        $vendors = Vendor::query()
            ->where('vendor_type', Vendor::VENDOR_TYPE_WEATHER)
            ->get();

        $locations->each(function (LocationModel $location) use ($vendors) {
            try {
                if (!is_null($location->parameters) && array_key_exists('owm_id', $location->parameters)) {
                    $weather = $this->findById($location->parameters['owm_id']);
                } else {
                    $weather = $this->getWeatherByNames($location);
                }

                if ($weather !== null) {
                    $location->vendors()->sync($vendors);
                    $location->refresh();

                    foreach (Vendor::WEATHER_VALUES as $valueType) {
                        $value = null;

                        switch ($valueType) {
                            case Vendor::VALUE_TYPE_WEATHER_TEMPERATURE:
                                $value = round($weather['main']['temp'] ?? null, 1);
                                break;
                            case Vendor::VALUE_TYPE_WEATHER_WIND:
                                $value = round($weather['wind']['speed'] ?? null, 1);
                                break;
                            case Vendor::VALUE_TYPE_WEATHER_PRESSURE:
                                $value = round($weather['main']['pressure'] ?? null);
                                break;
                            case Vendor::VALUE_TYPE_WEATHER_HUMIDITY:
                                $value = round($weather['main']['humidity'] ?? null);
                                break;
                            case Vendor::VALUE_TYPE_WEATHER_CLOUDS:
                                $value = round($weather['clouds']['all'] ?? null);
                                break;
                            case Vendor::VALUE_TYPE_WEATHER_RAIN:
                                $value = round($weather['rain']['1h'] ?? null);
                                break;
                            case Vendor::VALUE_TYPE_WEATHER_SNOW:
                                $value = round($weather['snow']['1h'] ?? null);
                                break;
                        }

                        if (!is_null($value)) {
                            /** @var Vendor $vendor */
                            $vendor = $location->vendors
                                ->where('vendor_type', Vendor::VENDOR_TYPE_WEATHER)
                                ->where('value_type', $valueType)
                                ->first();

                            /** @var VendorLocation $vendorLocation */
                            $vendorLocation = $vendor->pivot;

                            $vendorLocation->weathers()->create([
                                'source' => 'open_weather_map',
                                'type'   => $valueType,
                                'value'  => $value,
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
