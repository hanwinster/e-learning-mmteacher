<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursePrivaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_privacies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id')->comment('ID of Course record');           
            $table->unsignedInteger('role_id')->nullable()->comment('ID of Role record');
            $table->string('user_type')->nullable()->comment('User type. See type column in users table. Options: Public, Teacher Educator Student Teacher');
            $table->index(["course_id"], 'fk_course_id_idx');
            $table->index(["user_type"], 'fk_user_type');
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
        Schema::dropIfExists('course_privacies');
    }
}
