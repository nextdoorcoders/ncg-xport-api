<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_workers', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('name')->nullable();
            $table->string('role')->index()->nullable();

            $table->string('email')->index()->unique();
            $table->string('password');

            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_workers');
    }
}
