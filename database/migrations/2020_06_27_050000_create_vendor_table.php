<?php

use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
use App\Models\Vendor\Weather as WeatherModel;
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

            $table->string('code');
            $table->string('symbol')->nullable();
            $table->boolean('symbol_before')->default(true);

            $table->timestamps();
        });

        Schema::create('vendor_currencies_rate', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_location_id')->index();
            $table->uuid('from_currency_id')->index();
            $table->uuid('to_currency_id')->index();

            $table->string('source')->default(CurrencyRateModel::SOURCE_MINFIN)->index();
            $table->string('type')->default(CurrencyRateModel::TYPE_EXCHANGE_RATE)->index();

            $table->json('value');

            $table->timestamps();
        });

        Schema::create('vendor_weather', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('vendor_location_id')->index();

            $table->string('source')->default(WeatherModel::SOURCE_OPEN_WEATHER_MAP)->index();
            $table->string('type')->default(WeatherModel::TYPE_TEMPERATURE)->index();

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
        Schema::dropIfExists('vendor_currencies_rate');
        Schema::dropIfExists('vendor_currency');
    }
}
