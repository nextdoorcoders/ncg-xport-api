<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_social_accounts', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->uuid('user_id')->index();
            $table->string('provider_id')->index();
            $table->string('provider_name')->index();

            $table->string('email')->index()->nullable();

            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();

            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_social_accounts');
    }
}
