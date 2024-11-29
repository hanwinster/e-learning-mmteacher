<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {		
			$table->increments('id');
            $table->text('title');
            $table->text('second_title')->nullable();
            $table->unsignedInteger('category_id')->nullable();
			$table->text('body')->nullable();       
            $table->string('slug')->unique();                 
            $table->unsignedInteger('user_id')->nullable();
			$table->boolean('published')->default(false);
			$table->integer('weight')->default(0)->nullable();
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
        Schema::dropIfExists('articles');
    }
}
