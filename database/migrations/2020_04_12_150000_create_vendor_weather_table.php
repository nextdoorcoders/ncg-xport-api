<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorWeatherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_weather', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('location_id')->index();

            $table->timestamp('datetime_at')->index()->nullable();

            $table->decimal('temp', 4, 1)->default(0)->index();
            $table->decimal('wind', 4, 1)->default(0)->index();
            $table->smallInteger('pressure', false, true)->index();
            $table->tinyInteger('humidity', false, true)->index();

            $table->smallInteger('clouds', false, true)->index();
            $table->smallInteger('rain', false, true)->index();
            $table->smallInteger('snow', false, true)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_weather');
    }
}
