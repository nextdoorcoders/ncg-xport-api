<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerMapsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trigger_maps_histories', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('map_id')->index();

            $table->boolean('is_enabled')->default(false);
            $table->json('imprint')->nullable();

            $table->timestamps();

            $table->foreign('map_id')
                ->references('id')
                ->on('trigger_maps')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trigger_maps_histories');
    }
}
