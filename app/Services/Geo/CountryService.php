<?php

namespace App\Services\Geo;

use App\Models\Geo\Country as CountryModel;
use Illuminate\Database\Eloquent\Collection;

class CountryService
{
    /**
     * @return Collection
     */
    public function allCountries(): Collection
    {
        return CountryModel::query()
            ->get();
    }

    /**
     * @param array|object $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createCountry($data)
    {
        return CountryModel::query()
            ->create($data);
    }

    /**
     * @param CountryModel $country
     * @return CountryModel
     */
    public function readCountry(CountryModel $country)
    {
        return $country;
    }
}
