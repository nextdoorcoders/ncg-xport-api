<?php

namespace App\Services\Geo;

use App\Models\Geo\Location as LocationModel;
use App\Services\Vendor\WeatherService;
use Exception;
use Illuminate\Database\Eloquent\Model;

class LocationService
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
     * @return mixed
     */
    public function allLocations()
    {
        return LocationModel::query()
            ->getAllRoot();
    }

    /**
     * @param array $data
     * @return LocationModel|null
     */
    public function createLocation(array $data)
    {
        /** @var LocationModel $location */
        $location = LocationModel::query()
            ->create($data);

        return $this->readLocation($location);
    }

    /**
     * @param LocationModel $location
     * @return LocationModel|null
     */
    public function readLocation(LocationModel $location)
    {
        return $location->fresh();
    }

    /**
     * @param LocationModel $location
     * @param array         $data
     * @return LocationModel|null
     */
    public function updateLocation(LocationModel $location, array $data)
    {
        $location->fill($data);
        $location->save();

        return $this->readLocation($location);
    }

    /**
     * @param LocationModel $location
     * @throws Exception
     */
    public function deleteLocation(LocationModel $location): void
    {
        try {
            $location->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /*
     * Trees
     */

    public function createTreeLocations($locations, $parent = null)
    {
        foreach ($locations as $location) {
            $this->createTreeLocation($location, $parent);
        }
    }

    public function createTreeLocation($location, $parent)
    {
        switch ($location['type']) {
            case LocationModel::TYPE_COUNTRY:
                $parent = $this->createCountry($location);
                break;
            case LocationModel::TYPE_STATE:
                $parent = $this->createState($parent, $location);
                break;
            case LocationModel::TYPE_CITY:
                $parent = $this->createCity($parent, $location);
                break;
        }

        if (array_key_exists('children', $location)) {
            $this->createTreeLocations($location['children'], $parent);
        }

        return $parent;
    }

    /**
     * @param $data
     * @return Model
     */
    public function createCountry($data)
    {
        unset($data['children']);

        return LocationModel::query()
            ->create($data);
    }

    /**
     * @param LocationModel $country
     * @param               $data
     * @return Model
     */
    public function createState(LocationModel $country, $data)
    {
        unset($data['children']);

        return $country->children()
            ->create($data);
    }

    /**
     * @param LocationModel $state
     * @param               $data
     * @return Model
     */
    public function createCity(LocationModel $state, $data)
    {
        unset($data['children']);

        return $state->children()
            ->create($data);
    }
}
