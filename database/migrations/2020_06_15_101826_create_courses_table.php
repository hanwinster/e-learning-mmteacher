<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('description')->default("");
            $table->text('objective')->nullable();
            $table->text('learning_outcome')->nullable();
            $table->string('cover_image');
            $table->string('url_link')->nullable();
            $table->boolean('is_display_video')->default(0);
            $table->string('video_link')->nullable();
            $table->unsignedInteger('downloadable_option');
            $table->text('course_categories');
            $table->unsignedInteger('course_level_id');
            $table->unsignedInteger('course_type_id');
            $table->tinyInteger('approval_status')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->boolean('is_requested')->default(0);
            $table->boolean('is_published')->default(0);
            $table->boolean('allow_edit')->default(0);
            $table->boolean('is_locked')->default(0);
            $table->boolean('allow_feedback')->default(0);
            $table->boolean('allow_discussion')->default(0);
            $table->boolean('is_auto_completion')->default(0);
            $table->unsignedInteger('acceptable_score_for_assignment')->default(65);
            $table->unsignedInteger('acceptable_score_for_assessment')->default(65);
            $table->unsignedInteger('item_affect_certification');
            $table->unsignedInteger('estimated_duration');
            $table->string('estimated_duration_unit');
            $table->unsignedInteger('view_count')->default(0);
            $table->string('lang')->default('both');
            $table->string('order_type')->default('default');
            $table->text('orders')->nullable();
            $table->text('collaborators')->nullable();
            $table->text('related_resources')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
