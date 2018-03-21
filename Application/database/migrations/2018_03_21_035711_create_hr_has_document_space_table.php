<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrHasDocumentSpaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_has_document_space', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('document_space_id')->unsigned();

            $table->foreign('user_id')->references('user_id')->on('hr');
            $table->foreign('document_space_id')->references('id')->on('document_space');

            $table->primary(['user_id','document_space_id']);
            $table->index(['user_id','document_space_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_has_document_space');
    }
}
