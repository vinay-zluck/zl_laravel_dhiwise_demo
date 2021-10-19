<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
                $table->string('username')->nullable();
                $table->string('password')->nullable();
                $table->string('email')->nullable();
                $table->string('name')->nullable();
                $table->boolean('is_active')->nullable();
                $table->date('created_at')->nullable();
                $table->date('updated_at')->nullable();
                $table->integer('added_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->dateTime('login_reactive_time')->nullable();
                $table->integer('login_retry_limit')->default(0);
                $table->dateTime('reset_password_expire_time')->nullable();
                $table->string('reset_password_code')->nullable();
                $table->datetime('email_verified_at')->nullable();
            });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
