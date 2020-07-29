<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_states', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('country_id')->index();

            $table->string('name_en');
            $table->string('name_uk');
            $table->string('name_ru');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_states');
    }
}
