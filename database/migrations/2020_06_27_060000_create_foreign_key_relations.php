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
                ->nullOnDelete();
        });

        Schema::table('account_contacts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
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

        Schema::table('vendor_currencies_rate', function (Blueprint $table) {
            $table->foreign('vendor_location_id')
                ->references('id')
                ->on('trigger_vendors_location')
                ->onDelete('cascade');

            $table->foreign('from_currency_id')
                ->references('id')
                ->on('vendor_currencies')
                ->onDelete('cascade');

            $table->foreign('to_currency_id')
                ->references('id')
                ->on('vendor_currencies')
                ->onDelete('cascade');
        });

        Schema::table('vendor_weather', function (Blueprint $table) {
            $table->foreign('vendor_location_id')
                ->references('id')
                ->on('trigger_vendors_location')
                ->onDelete('cascade');
        });

        Schema::table('trigger_maps', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')
                ->on('marketing_projects')
                ->nullOnDelete();
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

            $table->foreign('vendor_id')
                ->references('id')
                ->on('trigger_vendors')
                ->onDelete('cascade');

            $table->foreign('vendor_location_id')
                ->references('id')
                ->on('trigger_vendors_location')
                ->onDelete('cascade');
        });

        Schema::table('marketing_accounts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
        });

        Schema::table('marketing_organizations', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');

            $table->foreign('location_id')
                ->references('id')
                ->on('geo_locations')
                ->nullOnDelete();
        });

        Schema::table('marketing_projects', function (Blueprint $table) {
            $table->foreign('account_id')
                ->references('id')
                ->on('marketing_accounts')
                ->nullOnDelete();

            $table->foreign('organization_id')
                ->references('id')
                ->on('marketing_organizations')
                ->nullOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('account_users')
                ->onDelete('cascade');
        });

        Schema::table('marketing_campaigns', function (Blueprint $table) {
            $table->foreign('map_id')
                ->references('id')
                ->on('trigger_maps')
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
