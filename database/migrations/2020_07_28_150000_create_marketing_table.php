<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_organizations', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('user_id')->index();

            $table->timestamps();
        });

        Schema::create('marketing_projects', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('social_account_id')->index();
            $table->uuid('organization_id')->index()->nullable();
            $table->uuid('map_id')->index()->nullable();

            $table->string('name');
            $table->string('desc')->nullable();

            $table->json('parameters');
            $table->timestamp('date_start_at')->nullable();
            $table->timestamp('date_end_at')->nullable();

            $table->timestamps();
        });

        Schema::create('marketing_digital_campaigns', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('project_id')->index();

            $table->string('campaign_id')->index();
            $table->string('name');

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
        Schema::dropIfExists('marketing_digital_campaigns');
        Schema::dropIfExists('marketing_projects');
        Schema::dropIfExists('marketing_organizations');
    }
}
