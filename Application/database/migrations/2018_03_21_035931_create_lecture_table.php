<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->text('overview');
            $table->text('objectives')->nullable();
            $table->integer('faculty_id')->unsigned();

            $table->foreign('faculty_id')->references('user_id')->on('faculty');
            $table->timestamps();

            //misc
            $table->index('id');
            $table->index('faculty_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecture');
    }
}
