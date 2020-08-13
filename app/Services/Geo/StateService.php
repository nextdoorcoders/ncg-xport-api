<?php

namespace App\Services\Geo;

use App\Models\Geo\Country as CountryModel;
use App\Models\Geo\State as StateModel;
use Illuminate\Database\Eloquent\Collection;

class StateService
{
    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return StateModel::query()
            ->get();
    }

    /**
     * @param CountryModel $country
     * @param array|object $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createState(CountryModel $country, $data)
    {
        return $country->states()->create($data);
    }
}
