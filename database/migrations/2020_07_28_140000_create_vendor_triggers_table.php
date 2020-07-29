<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_triggers', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('sourceable_type');

            $table->string('name');
            $table->string('type')->default(\App\Models\Vendor\Trigger::TYPE_WEATHER);

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
        Schema::dropIfExists('vendor_triggers');
    }
}
