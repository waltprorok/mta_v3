<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('teacher_settings', function (Blueprint $table) {
            $table->string('calendar', 40)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('teacher_settings', function (Blueprint $table) {
            $table->enum('calendar', ['month', 'listWeek', 'agendaWeek', 'agendaDay'])->nullable()->default('month')->change();
        });
    }
};
