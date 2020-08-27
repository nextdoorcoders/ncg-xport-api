<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeightKeyRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_users', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')
                ->on('geo_countries')
                ->onDelete('cascade');

            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');
        });

        Schema::table('account_contacts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
        });

        Schema::table('account_social_accounts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
        });

        Schema::table('geo_countries_translate', function (Blueprint $table) {
            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');

            $table->foreign('translatable_id')
                ->references('id')
                ->on('geo_countries')
                ->onDelete('cascade');
        });

        Schema::table('geo_states', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')
                ->on('geo_countries')
                ->onDelete('cascade');
        });

        Schema::table('geo_states_translate', function (Blueprint $table) {
            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');

            $table->foreign('translatable_id')
                ->references('id')
                ->on('geo_states')
                ->onDelete('cascade');
        });

        Schema::table('geo_cities', function (Blueprint $table) {
            $table->foreign('country_id')
                ->references('id')
                ->on('geo_countries')
                ->onDelete('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('geo_states')
                ->onDelete('cascade');
        });

        Schema::table('geo_cities_translate', function (Blueprint $table) {
            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');

            $table->foreign('translatable_id')
                ->references('id')
                ->on('geo_cities')
                ->onDelete('cascade');
        });

        Schema::table('vendor_weather', function (Blueprint $table) {
            $table->foreign('city_id')
                ->references('id')
                ->on('geo_cities')
                ->onDelete('cascade');
        });

        Schema::table('marketing_accounts', function (Blueprint $table) {
            $table->foreign('social_account_id')
                ->references('id')
                ->on('account_social_accounts')
                ->onDelete('cascade');
        });

        Schema::table('marketing_projects', function (Blueprint $table) {
            $table->foreign('owner_user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');

            $table->foreign('client_user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('geo_cities')
                ->onDelete('cascade');
        });

        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->foreign('account_id')
                ->references('id')
                ->on('marketing_accounts')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')
                ->on('marketing_projects')
                ->onDelete('cascade');
        });

        Schema::table('marketing_groups', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')
                ->on('marketing_projects')
                ->onDelete('cascade');
        });

        Schema::table('marketing_conditions', function (Blueprint $table) {
            $table->foreign('group_id')
                ->references('id')
                ->on('marketing_groups')
                ->onDelete('cascade');

            $table->foreign('vendor_location_id')
                ->references('id')
                ->on('marketing_vendors_location')
                ->onDelete('cascade');
        });

        Schema::table('marketing_vendors_translate', function (Blueprint $table) {
            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');

            $table->foreign('translatable_id')
                ->references('id')
                ->on('marketing_vendors')
                ->onDelete('cascade');
        });

        Schema::table('marketing_vendors_location', function (Blueprint $table) {
            $table->foreign('vendor_id')
                ->references('id')
                ->on('marketing_vendors')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('geo_cities')
                ->onDelete('cascade');
        });
    }
}
