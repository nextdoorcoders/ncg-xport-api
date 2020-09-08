<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trigger_vendors', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('trigger_class');
            $table->string('type')->index()->nullable();

            $table->json('default_parameters')->nullable();
            $table->json('settings')->nullable();

            $table->timestamps();
        });

        Schema::create('trigger_vendors_translate', function (Blueprint $table) {
            $table->uuid('language_id')->index();
            $table->uuid('translatable_id')->index();

            $table->string('name');
            $table->text('desc');

            $table->timestamps();

            $table->primary(['language_id', 'translatable_id'], 'language_translatable_key');
        });

        Schema::create('trigger_vendors_location', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('location_id')->index();
            $table->uuid('vendor_id')->index();

            $table->unique(['location_id', 'vendor_id'], 'location_vendor_key');
        });

        Schema::create('trigger_maps', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('user_id')->index();

            $table->string('name');
            $table->text('desc')->nullable();

            $table->boolean('is_enabled')->default(false);
            $table->timestamp('refreshed_at')->nullable();
            $table->timestamp('changed_at')->nullable();

            $table->timestamps();
        });

        Schema::create('trigger_groups', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('map_id')->index();

            $table->string('name')->nullable();
            $table->text('desc')->nullable();

            $table->boolean('is_enabled')->default(false);
            $table->timestamp('refreshed_at')->nullable();
            $table->timestamp('changed_at')->nullable();

            $table->timestamps();
        });

        Schema::create('trigger_conditions', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('group_id')->index();

            $table->string('vendor_type')->index();
            $table->uuid('vendor_id')->index()->nullable();

            $table->json('parameters')->nullable();

            $table->boolean('is_enabled')->default(false);
            $table->timestamp('refreshed_at')->nullable();
            $table->timestamp('changed_at')->nullable();

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
        Schema::dropIfExists('trigger_conditions');
        Schema::dropIfExists('trigger_groups');
        Schema::dropIfExists('trigger_maps');
        Schema::dropIfExists('trigger_vendors_location');
        Schema::dropIfExists('trigger_vendors_translate');
        Schema::dropIfExists('trigger_vendors');
    }
}
