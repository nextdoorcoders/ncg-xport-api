<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_groups', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('project_id')->index();

            $table->string('name')->nullable();
            $table->text('desc')->nullable();

            $table->boolean('is_trigger_enabled')->default(false);
            $table->timestamp('trigger_refreshed_at')->nullable();
            $table->timestamp('trigger_changed_at')->nullable();

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
        Schema::dropIfExists('marketing_groups');
    }
}
