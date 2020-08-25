<?php

namespace App\Services\Geo;

use App\Models\Geo\City as CityModel;
use App\Models\Geo\State as StateModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Exception;

class CityService
{
    protected WeatherService $weatherService;

    /**
     * CityService constructor.
     *
     * @param WeatherService $weatherService
     */
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return CityModel::query()
            ->get();
    }

    /**
     * @param StateModel $state
     * @param            $data
     * @return Model
     * @throws RequestException
     */
    public function createCity(StateModel $state, $data)
    {
        $weather = $this->weatherService->getWeatherByNames($data['name'], $state->country->alpha2);

        if (!is_null($weather)) {
            $data['owm_id'] = $weather['id'];

            $data['center'] = [
                'latitude'  => $weather['coord']['lat'],
                'longitude' => $weather['coord']['lon'],
            ];

            $data['name'] = $this->weatherService->getTranslationsById($weather['id']);
        }

        return $state->cities()
            ->create(array_merge($data, [
                'country_id' => $state->country_id,
            ]));
    }

    /**
     * Update weather information for each city where
     * open weather map id is not empty
     *
     * @throws Exception
     */
    public function updateWeatherInformation(): void
    {
        /** @var Collection $cities */
        $cities = CityModel::query()
            ->whereNotNull('owm_id')
            ->with('actualWeather')
            ->get();

        $cities->each(function ($city) {
            try {
                $this->weatherService->updateCityWeather($city);
            } catch (Exception $exception) {
                Log::error($exception);
            }
        });
    }
}
