<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('lecture_id');
            $table->text('topic', 128); 
            $table->text('meeting_id', 32)->nullable();         
            $table->text('start_time', 32)->nullable();
            $table->text('agenda', 256)->nullable();
            $table->unsignedInteger('host_video');
            $table->unsignedInteger('participant_video')->nullable();
            $table->unsignedInteger('duration');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('live_sessions');
    }
}
