<?php

namespace Database\Seeders;

use App\Models\Trigger\Vendor;
use App\Models\VendorState;
use Illuminate\Database\Seeder;

class VendorStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function run()
    {
        //
        $vendors = Vendor::all();
        foreach ($vendors as $vendor) {
            $class = new ($vendor->callback)();
            $classname = (new \ReflectionClass($class))->getShortName();
           VendorState::create([
               'name' => $classname,
           ]);
        }
    }
}
