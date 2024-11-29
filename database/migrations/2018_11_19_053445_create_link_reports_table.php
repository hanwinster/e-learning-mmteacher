<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_reports', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('resource_id')->nullable();
			$table->unsignedInteger('user_id')->nullable();
			$table->string('report_type')->nullable();
			$table->mediumText('comment')->nullable();			
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
        Schema::dropIfExists('link_reports');
    }
}
