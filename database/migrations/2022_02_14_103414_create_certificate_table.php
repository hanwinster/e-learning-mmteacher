<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->text('title', 128)->nullable(); 
            $table->text('description', 256)->nullable();         
            $table->text('certify_text', 256)->nullable();
            $table->text('completion_text', 256)->nullable();
            $table->text('certificate_date')->nullable();
            $table->string('signature_1')->nullable();
            $table->string('signature_2')->nullable();
            $table->string('background_image')->nullable();
            $table->string('logo_image')->nullable();
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
        Schema::dropIfExists('certificates');
    }
}
