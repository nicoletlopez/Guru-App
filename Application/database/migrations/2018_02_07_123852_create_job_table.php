<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->string('type')->nullable();
            $table->double('ceiling_salary');
            $table->double('floor_salary');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('subject');

            //misc
            $table->softDeletes();
            $table->index('id');
            $table->index('title');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job');
    }
}
