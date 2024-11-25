<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->boolean('auto_schedule_new_active_students')->default(false);
            $table->enum('calendar', ['month', 'listWeek', 'agendaWeek', 'agendaDay'])->default('month');
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
        Schema::dropIfExists('teacher_settings');
    }
}
