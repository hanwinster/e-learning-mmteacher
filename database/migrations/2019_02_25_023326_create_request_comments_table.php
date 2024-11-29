<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->comment('ID of User');
            $table->unsignedInteger('request_id')->nullable()->comment('ID of Resource Request');
            $table->text('description')->nullable();
            $table->index(["request_id"], 'fk_request_id_idx');
            $table->index(["user_id"], 'fk_user_idx');

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
        Schema::dropIfExists('request_comments');
    }
}
