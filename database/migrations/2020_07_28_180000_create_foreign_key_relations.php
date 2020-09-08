<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_users', function (Blueprint $table) {
            $table->foreign('location_id')
                ->references('id')
                ->on('geo_locations')
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

        Schema::table('geo_locations', function (Blueprint $table) {
        });

        Schema::table('geo_locations_translate', function (Blueprint $table) {
            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');

            $table->foreign('translatable_id')
                ->references('id')
                ->on('geo_locations')
                ->onDelete('cascade');
        });

        Schema::table('vendor_weather', function (Blueprint $table) {
            $table->foreign('location_id')
                ->references('id')
                ->on('geo_locations')
                ->onDelete('cascade');
        });

        Schema::table('trigger_maps', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
        });

        Schema::table('trigger_groups', function (Blueprint $table) {
            $table->foreign('map_id')
                ->references('id')
                ->on('trigger_maps')
                ->onDelete('cascade');
        });

        Schema::table('trigger_conditions', function (Blueprint $table) {
            $table->foreign('group_id')
                ->references('id')
                ->on('trigger_groups')
                ->onDelete('cascade');
        });

        Schema::table('marketing_organizations', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
        });

        Schema::table('marketing_projects', function (Blueprint $table) {
            $table->foreign('social_account_id')
                ->references('id')
                ->on('account_social_accounts')
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('id')
                ->on('marketing_organizations')
                ->onDelete('cascade');

            $table->foreign('map_id')
                ->references('id')
                ->on('trigger_maps')
                ->onDelete('cascade');
        });

        Schema::table('marketing_digital_campaigns', function (Blueprint $table) {
            $table->foreign('project_id')
                ->references('id')
                ->on('marketing_projects')
                ->onDelete('cascade');
        });

        Schema::table('trigger_vendors_translate', function (Blueprint $table) {
            $table->foreign('language_id')
                ->references('id')
                ->on('account_languages')
                ->onDelete('cascade');

            $table->foreign('translatable_id')
                ->references('id')
                ->on('trigger_vendors')
                ->onDelete('cascade');
        });

        Schema::table('trigger_vendors_location', function (Blueprint $table) {
            $table->foreign('vendor_id')
                ->references('id')
                ->on('trigger_vendors')
                ->onDelete('cascade');

            $table->foreign('location_id')
                ->references('id')
                ->on('geo_locations')
                ->onDelete('cascade');
        });
    }
}
