<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('account_id')->index();
            $table->uuid('project_id')->index()->nullable();

            $table->string('campaign_id')->index();
            $table->string('name');
            $table->string('desc')->nullable();

            $table->timestamp('date_start_at')->nullable();
            $table->timestamp('date_end_at')->nullable();

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
        Schema::dropIfExists('marketing_campaigns');
    }
}
