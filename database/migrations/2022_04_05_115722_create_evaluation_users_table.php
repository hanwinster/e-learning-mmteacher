<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('course_id'); 
            $table->unsignedInteger('user_id');
            $table->text('feedbacks');
            $table->decimal('overall_rating', 2,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_users');
    }
}
