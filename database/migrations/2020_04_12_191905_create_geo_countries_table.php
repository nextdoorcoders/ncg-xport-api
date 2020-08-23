<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_countries', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('alpha2')->index();
            $table->string('alpha3')->index();

            $table->string('phone_mask');

            $table->timestamps();
        });

        Schema::create('geo_countries_translate', function (Blueprint  $table) {
            $table->uuid('language_id')->index();
            $table->uuid('translatable_id')->index();

            $table->string('name');

            $table->timestamps();

            $table->primary(['language_id', 'translatable_id'], 'language_translatable_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_countries');
    }
}
