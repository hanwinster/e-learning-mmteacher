<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {			
			$table->increments('id');
            $table->text('title');
            //$table->text('second_title')->nullable();
            $table->text('body')->nullable();
            $table->string('slug')->unique();
			$table->unsignedInteger('user_id')->nullable();
            //$table->boolean('show_title')->default(true);
            $table->boolean('published')->default(false);
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
        Schema::dropIfExists('pages');
    }
}
