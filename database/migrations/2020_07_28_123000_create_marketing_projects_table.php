<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_projects', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('owner_user_id')->index();
            $table->uuid('client_user_id')->index()->nullable();
            $table->uuid('city_id')->index();

            $table->string('name');
            $table->text('desc')->nullable();

            $table->boolean('is_trigger_enabled')->default(false);
            $table->timestamp('trigger_refreshed_at')->nullable();
            $table->timestamp('trigger_changed_at')->nullable();

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
        Schema::dropIfExists('marketing_projects');
    }
}
