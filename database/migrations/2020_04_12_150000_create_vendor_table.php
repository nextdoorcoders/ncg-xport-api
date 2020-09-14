<?php

use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
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
        Schema::create('vendor_currency', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('code');
            $table->string('symbol')->nullable();
            $table->boolean('symbol_before')->default(true);

            $table->timestamps();
        });

        Schema::create('vendor_currency_rate', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('location_id')->index();
            $table->uuid('from_currency_id')->index();
            $table->uuid('to_currency_id')->index();

            $table->string('source')->default(CurrencyRateModel::SOURCE_MINFIN);
            $table->string('type')->default(CurrencyRateModel::TYPE_EXCHANGE_RATE);

            $table->json('rate');

            $table->timestamps();
        });

        Schema::create('vendor_weather', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('location_id')->index();

            $table->timestamp('datetime_at')->index()->nullable();

            $table->decimal('temperature', 4, 1)->default(0)->index();
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
        Schema::dropIfExists('vendor_currency');
    }
}
