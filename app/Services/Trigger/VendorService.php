<?php

namespace App\Services\Trigger;

use App\Models\Account\User as UserModel;
use App\Models\Trigger\Vendor as VendorModel;
use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
use App\Models\Vendor\Weather as WeatherModel;
use App\Services\Vendor\Classes\Currency;
use App\Services\Vendor\Classes\Weather;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class VendorService
{
    /**
     * @return Collection
     */
    public function allVendors()
    {
        return VendorModel::query()
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return VendorModel|null
     */
    public function createVendor(UserModel $user, array $data)
    {
        /** @var VendorModel $vendor */
        $vendor = VendorModel::query()
            ->create($data);

        return $this->readVendor($vendor, $user);
    }

    /**
     * @param VendorModel $vendor
     * @param UserModel   $user
     * @return VendorModel|null
     */
    public function readVendor(VendorModel $vendor, UserModel $user)
    {
        return $vendor->fresh();
    }

    /**
     * @param VendorModel $vendor
     * @param UserModel   $user
     * @param array       $data
     * @return VendorModel|null
     */
    public function updateVendor(VendorModel $vendor, UserModel $user, array $data)
    {
        $vendor->fill($data);
        $vendor->save();

        return $this->readVendor($vendor, $user);
    }

    /**
     * @param VendorModel $vendor
     * @param UserModel   $user
     * @throws Exception
     */
    public function deleteVendor(VendorModel $vendor, UserModel $user)
    {
        try {
            $vendor->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateAllLocations()
    {
        /*
         * Weather
         */
        $triggers = VendorModel::query()
            ->where('callback', Weather::class)
            ->get();

        $locationIds = WeatherModel::query()
            ->get()
            ->pluck('vendor_location_id')
            ->unique();

        $triggers->each(function (VendorModel $vendor) use ($locationIds) {
            $vendor->locations()->sync($locationIds);
        });

        /*
         * Currency
         */
        $triggers = VendorModel::query()
            ->where('callback', Currency::class)
            ->get();

        $locationIds = CurrencyRateModel::query()
            ->get()
            ->pluck('vendor_location_id')
            ->unique();

        $triggers->each(function (VendorModel $vendor) use ($locationIds) {
            $vendor->locations()->sync($locationIds);
        });
    }
}
