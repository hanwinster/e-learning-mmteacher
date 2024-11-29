<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id')->comment('ID of Resource');
            $table->unsignedInteger('related_resource_id')->comment('ID of Related Resource');
            $table->index(["resource_id"], 'fk_resource_id_idx');
            $table->index(["related_resource_id"], 'fk_related_resource_id_idx');
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
        Schema::dropIfExists('related_resources');
    }
}
