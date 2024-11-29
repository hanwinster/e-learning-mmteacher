<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id')->comment('ID of Resource record');
            $table->unsignedInteger('user_id')->nullable()->comment('ID of User');
            $table->unsignedInteger('approved_by')->nullable()->comment('ID of User');
            $table->tinyInteger('approval_status')->unsigned()->default(0);
            $table->text('description')->nullable();
            $table->index(["resource_id"], 'fk_resource_id_idx');
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
        Schema::dropIfExists('requests');
    }
}
