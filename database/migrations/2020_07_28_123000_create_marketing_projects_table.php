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
            $table->uuid('campaign_id')->index();

            $table->string('name');

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

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
