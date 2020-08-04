<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_companies', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('social_account_id')->index();

            $table->string('name');
            $table->text('desc')->nullable();

            $table->text('parameters');

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
        Schema::dropIfExists('marketing_companies');
    }
}
