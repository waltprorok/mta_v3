<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('restrict');
            $table->integer('monday')->nullable();
            $table->tinyInteger('monday_active');
            $table->time('monday_open_time');
            $table->time('monday_close_time');
            $table->integer('tuesday')->nullable();
            $table->tinyInteger('tuesday_active');
            $table->time('tuesday_open_time');
            $table->time('tuesday_close_time');
            $table->integer('wednesday')->nullable();
            $table->tinyInteger('wednesday_active');
            $table->time('wednesday_open_time');
            $table->time('wednesday_close_time');
            $table->integer('thursday')->nullable();
            $table->tinyInteger('thursday_active');
            $table->time('thursday_open_time');
            $table->time('thursday_close_time');
            $table->integer('friday')->nullable();
            $table->tinyInteger('friday_active');
            $table->time('friday_open_time');
            $table->time('friday_close_time');
            $table->integer('saturday')->nullable();
            $table->tinyInteger('saturday_active');
            $table->time('saturday_open_time');
            $table->time('saturday_close_time');
            $table->integer('sunday')->nullable();
            $table->tinyInteger('sunday_active');
            $table->time('sunday_open_time');
            $table->time('sunday_close_time');
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
        Schema::dropIfExists('business_hours');
    }
}
