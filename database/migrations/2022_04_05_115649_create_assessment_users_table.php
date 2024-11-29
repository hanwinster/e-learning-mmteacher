<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('assessment_question_answer_id');
            $table->unsignedInteger('course_id'); 
            $table->unsignedInteger('user_id');
            $table->text('answers');
            $table->decimal('score',2,2);
            $table->unsignedInteger('attempts');
            $table->text('status');
            $table->text('comment');
            $table->unsignedInteger('comment_by');
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
        Schema::dropIfExists('assessment_users');
    }
}
