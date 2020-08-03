<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingProjectParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_projects_has_parameters', function (Blueprint $table) {
            $table->uuid('project_id')->index();
            $table->uuid('parameter_id')->index();

            $table->primary(['project_id', 'parameter_id'], 'project_parameter_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_projects_has_parameters');
    }
}
