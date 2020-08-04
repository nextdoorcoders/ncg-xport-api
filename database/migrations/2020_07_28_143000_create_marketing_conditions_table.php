<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_conditions', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('group_id')->index();
            $table->uuid('vendor_id')->index();

            $table->json('parameters');

            $table->unique(['group_id', 'vendor_id'], 'project_group_vendor_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_conditions');
    }
}
