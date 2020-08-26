<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingVendorLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_vendors_location', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('city_id')->index();
            $table->uuid('vendor_id')->index();

            $table->unique(['city_id', 'vendor_id'], 'city_vendor_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_vendors_location');
    }
}
