<?php

use App\Models\Geo\City as CityModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_cities', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('country_id')->index();
            $table->uuid('state_id')->index()->nullable();

            $table->string('type')->default(CityModel::TYPE_CITY)->index();

            $table->timestamps();
        });

        Schema::create('geo_cities_translate', function (Blueprint  $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('language_id')->index();
            $table->uuid('translatable_id')->index();

            $table->string('name');

            $table->timestamps();

//            $table->primary(['language_id', 'translatable_id'], 'language_translatable_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_cities');
    }
}
