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

        Schema::table('account_socials', function (Blueprint $table) {
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

        Schema::table('marketing_companies', function (Blueprint $table) {
            $table->foreign('social_account_id')
                ->references('id')
                ->on('account_socials')
                ->onDelete('cascade');
        });

        Schema::table('marketing_projects', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('marketing_companies')
                ->onDelete('cascade');
        });

        Schema::table('marketing_parameters', function (Blueprint $table) {
            $table->foreign('city_id')
                ->references('id')
                ->on('geo_cities')
                ->onDelete('cascade');

            $table->foreign('trigger_id')
                ->references('id')
                ->on('vendor_triggers')
                ->onDelete('cascade');
        });

        Schema::table('marketing_projects_has_parameters', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')
                ->on('marketing_projects')
                ->onDelete('cascade');

            $table->foreign('parameter_id')
                ->references('id')
                ->on('marketing_parameters')
                ->onDelete('cascade');
        });
    }
}
