<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {			
			$table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
			$table->string('password'); 			
            $table->string('mobile_no')->nullable();
			$table->string('notification_channel')->default('sms');
			$table->string('user_type')->nullable();
			$table->string('ec_college')->nullable();
			$table->unsignedInteger('role_id')->nullable();			
            $table->tinyInteger('approved')->default(0);
			$table->tinyInteger('verified')->default(0);
            $table->tinyInteger('blocked')->default(0);
            $table->string('type')->nullable();
			$table->string('verification_code')->nullable();			
			$table->timestamp('email_verified_at')->nullable();
            $table->timestamp('sms_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
