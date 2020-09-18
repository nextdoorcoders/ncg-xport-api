<?php

use App\Models\Account\Contact as ContactModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_languages', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('name');
            $table->string('code')->index();

            $table->json('priority')->nullable();

            $table->boolean('is_primary')->default(false)->index();

            $table->timestamps();
        });

        Schema::create('account_users', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('location_id')->index()->nullable();
            $table->uuid('language_id')->index();

            $table->string('name')->nullable();
            $table->string('email')->index()->unique();

            $table->string('password');
            $table->string('password_reset_code')->nullable();

            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
        });

        Schema::create('account_contacts', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('user_id')->index();

            $table->string('type')->default(ContactModel::TYPE_PHONE);
            $table->text('value');

            $table->timestamps();
        });

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


        Schema::create('account_workers', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();

            $table->string('role')->index()->nullable();

            $table->string('name')->nullable();
            $table->string('email')->index()->unique();

            $table->string('password');
            $table->string('password_reset_code')->nullable();

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
        Schema::dropIfExists('account_social_accounts');
        Schema::dropIfExists('account_contacts');
        Schema::dropIfExists('account_users');
        Schema::dropIfExists('account_languages');
    }
}