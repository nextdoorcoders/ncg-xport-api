<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorphFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morph_files', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('fileable_type')->index()->nullable();
            $table->uuid('fileable_id')->index()->nullable();

            $table->string('field')->index();
            $table->string('name');
            $table->integer('size', false, true);
            $table->string('type');
            $table->string('disk_name');

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
        Schema::dropIfExists('morph_files');
    }
}
