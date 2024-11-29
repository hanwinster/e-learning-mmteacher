<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_subject', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id')->comment('ID of Resource record');
            $table->unsignedInteger('subject_id')->nullable()->comment('ID of Role record');
            $table->index(["resource_id"], 'fk_resource_id_idx');
            $table->index(["subject_id"], 'fk_subject_id');
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
        Schema::dropIfExists('resource_subject');
    }
}
