<?php

namespace App\Services\Marketing\Vendor;

interface VendorInterface
{
    public function run();

    public function current($cityId);
}
