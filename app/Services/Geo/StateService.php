<?php

namespace App\Services\Geo;

use App\Models\Geo\Country as CountryModel;
use App\Models\Geo\State as StateModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class StateService
{
    /**
     * @param CountryModel $country
     * @return Collection
     */
    public function allByCountry(CountryModel $country): Collection
    {
        return $country->states()
            ->get();
    }

    /**
     * @param CountryModel $country
     * @param array|object $data
     * @return Model
     */
    public function createState(CountryModel $country, $data)
    {
        return $country->states()
            ->create($data);
    }

    /**
     * @param StateModel $state
     * @return StateModel
     */
    public function readState(StateModel $state)
    {
        $state->load('country');

        return $state;
    }
}
