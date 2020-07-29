<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorphTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morph_tokens', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->string('tokenable_type')->index();
            $table->uuid('tokenable_id')->index()->nullable();

            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();

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
    }
}
