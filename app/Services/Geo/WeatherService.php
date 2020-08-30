<?php

namespace App\Services\Geo;

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\City as CityModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected string $url = 'http://api.openweathermap.org/data/2.5/weather';

    /**
     * @param int    $id
     * @param string $lang
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
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
     * @throws \Illuminate\Http\Client\RequestException
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
     * @throws \Illuminate\Http\Client\RequestException
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
     * @param array  $names
     * @param string $countryAlpha2
     * @return array|mixed|null
     */
    public function getWeatherByNames(array $names, string $countryAlpha2)
    {
        $weather = null;

        foreach ($names as $lang => $value) {
            $value = explode('/', $value);

            try {
                $weather = $this->findByName(trim(Arr::first($value)) . ',' . mb_strtolower($countryAlpha2), $lang);

                // We only need the first cycle.
                // The loop will continue if an exception was thrown by API
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
     * @throws \Illuminate\Http\Client\RequestException
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

    public function updateCityWeather(CityModel $city)
    {
        if (is_null($city->owm_id)) {
            return;
        }

        try {
            $weather = $this->findById($city->owm_id);

            $city->weathers()
                ->updateOrCreate([
                    'datetime_at' => Carbon::createFromTimestamp($weather['dt']) ?? null,
                ], [
                    'temp'     => round($weather['main']['temp'] ?? null, 1),
                    'wind'     => round($weather['wind']['speed'] ?? null, 1),
                    'pressure' => round($weather['main']['pressure'] ?? null),
                    'humidity' => round($weather['main']['humidity'] ?? null),
                    'clouds'   => round($weather['clouds']['all'] ?? null),
                    'rain'     => round($weather['rain']['1h'] ?? null),
                    'snow'     => round($weather['snow']['1h'] ?? null),
                ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
