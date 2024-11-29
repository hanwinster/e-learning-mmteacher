<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseApprovalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_approval_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id')->comment('ID of Course record');
            $table->unsignedInteger('user_id')->nullable()->comment('ID of User');
            $table->unsignedInteger('approved_by')->nullable()->comment('ID of User');
            $table->tinyInteger('approval_status')->unsigned()->default(0);
            $table->text('description')->nullable();
            $table->index(["course_id"], 'fk_course_id_idx');
            $table->index(["user_id"], 'fk_user_idx');
            $table->index(["approved_by"], 'fk_approved_by_idx');
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
        Schema::dropIfExists('course_approval_requests');
    }
}
