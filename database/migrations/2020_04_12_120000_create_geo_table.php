<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_locations', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('type')->default('country'); // TODO set const

            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('nested_left');
            $table->bigInteger('nested_right');
            $table->bigInteger('nested_depth');

            $table->timestamps();
        });

        Schema::create('geo_locations_translate', function (Blueprint  $table) {
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
        Schema::dropIfExists('geo_locations_translate');
        Schema::dropIfExists('geo_locations');
    }
}
