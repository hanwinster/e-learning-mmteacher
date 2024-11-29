<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongAnswerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('long_answer_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('long_answer_id');
            $table->unsignedInteger('user_id');
            $table->text('submitted_answer');
            $table->text('comment')->nullable(); 
            $table->unsignedInteger('comment_by')->nullable();
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
        Schema::dropIfExists('long_answer_users');
    }
}
