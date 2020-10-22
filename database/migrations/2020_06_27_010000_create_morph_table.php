<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorphTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morph_storages', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('fileable_type')->index()->nullable();
            $table->uuid('fileable_id')->index()->nullable();

            $table->string('name')->nullable();
            $table->string('desc')->nullable();
            $table->string('field')->index()->nullable();

            $table->string('file_name');
            $table->string('file_extension');
            $table->string('file_mime_type');
            $table->bigInteger('file_size', false, true);

            $table->timestamps();
        });

        Schema::create('morph_tokens', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('tokenable_type')->index();
            $table->uuid('tokenable_id')->index()->nullable();

            $table->string('agent');
            $table->string('ip');
            $table->text('abilities')->nullable();

            $table->string('token', 64)->unique();

            $table->timestamps();
            $table->timestamp('last_used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('morph_tokens');
        Schema::dropIfExists('morph_files');
    }
}
