<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_currencies', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('code')->index();
            $table->string('symbol')->nullable();
            $table->boolean('symbol_before')->default(true);

            $table->timestamps();
        });

        Schema::create('vendor_currencies_rate', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_type_id')->index();
            $table->uuid('vendor_location_id')->index();
            $table->uuid('from_currency_id')->index();
            $table->uuid('to_currency_id')->index();

            $table->json('value');

            $table->timestamps();
        });

        Schema::create('vendor_keywords', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('code')->index();
            $table->string('keyword')->comment('External service key');

            $table->timestamps();
        });

        Schema::create('vendor_keywords_rate', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_type_id')->index();
            $table->uuid('vendor_location_id')->index();
            $table->uuid('keyword_id')->index();

            $table->string('value');

            $table->timestamps();
        });

        Schema::create('vendor_media_sync', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_type_id')->index();
            $table->uuid('vendor_location_id')->index()->nullable();

            $table->string('value');

            $table->timestamps();
        });

        Schema::create('vendor_monitors', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_type_id')->index();
            $table->uuid('vendor_location_id')->index()->nullable();

            $table->string('value');

            $table->timestamps();
        });

        Schema::create('vendor_weather', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_type_id')->index();
            $table->uuid('vendor_location_id')->index();

            $table->string('value');

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
        Schema::dropIfExists('vendor_monitors_rate');
        Schema::dropIfExists('vendor_monitors');
        Schema::dropIfExists('vendor_media_sync');
        Schema::dropIfExists('vendor_keywords_rate');
        Schema::dropIfExists('vendor_keywords');
        Schema::dropIfExists('vendor_currencies_rate');
        Schema::dropIfExists('vendor_currency');
    }
}
