<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceFormatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_format', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('resource_id')->comment('ID of Resource record');
			$table->unsignedInteger('format_id')->nullable()->comment('ID of resource format record');
			$table->index(["resource_id"], 'fk_resource_id_idx');
            $table->index(["format_id"], 'fk_format_id');
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
        Schema::dropIfExists('resource_format');
    }
}
