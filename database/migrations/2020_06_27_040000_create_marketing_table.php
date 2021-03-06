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
        Schema::create('marketing_accounts', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('user_id')->index();

            $table->string('provider_id')->index();
            $table->string('provider_name')->index();

            $table->string('email')->index()->nullable();

            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();

            $table->timestamps();
        });

        Schema::create('marketing_organizations', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('user_id')->index();
            $table->uuid('location_id')->index()->nullable();

            $table->string('name');
            $table->string('zip')->nullable();

            $table->string('email')->nullable();
            $table->json('phones')->nullable();
            $table->json('addresses')->nullable();

            $table->json('working_hours')->nullable();

            $table->json('social_networks')->nullable();

            $table->timestamps();
        });

        Schema::create('marketing_projects', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('account_id')->index()->nullable();
            $table->uuid('organization_id')->index()->nullable();
            $table->uuid('user_id')->index();

            $table->string('name');
            $table->string('desc')->nullable();

            $table->text('parameters');
            $table->boolean('is_enabled')->default(false)->index();
            $table->date('date_start_at')->index()->nullable();
            $table->date('date_end_at')->index()->nullable();

            $table->timestamps();
        });

        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('map_id')->index();

            $table->string('foreign_campaign_id')->index();
            $table->string('name');
            $table->boolean('is_rate_enabled')->after('is_enabled')->default(false)->index();
            $table->decimal('rate_min', 10, 2)->after('is_rate_enabled')->default(0)->nullable();
            $table->decimal('rate_max', 10, 2)->after('rate_min')->default(0)->nullable();

            $table->boolean('is_enabled')->default(false)->index();

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
        Schema::dropIfExists('marketing_projects');
        Schema::dropIfExists('marketing_organizations');
        Schema::dropIfExists('marketing_accounts');
    }
}
