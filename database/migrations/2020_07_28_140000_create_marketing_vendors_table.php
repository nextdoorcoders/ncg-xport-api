<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_vendors', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('trigger_class');
            $table->json('parameters')->nullable();

            $table->string('type')->index()->nullable();

            $table->timestamps();
        });

        Schema::create('marketing_vendors_translate', function (Blueprint $table) {
            $table->uuid('language_id')->index();
            $table->uuid('translatable_id')->index();

            $table->string('name');
            $table->text('desc');

            $table->timestamps();

            $table->primary(['language_id', 'translatable_id'], 'language_translatable_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing_vendors');
    }
}
