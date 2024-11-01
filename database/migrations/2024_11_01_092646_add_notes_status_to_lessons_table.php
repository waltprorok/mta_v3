<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesStatusToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(CreateLessonsTable::TABLE_NAME, function (Blueprint $table) {
            $table->text('notes')->nullable()->after('interval');
            $table->enum('status', ['Scheduled', 'Re-Scheduled', 'Cancelled'])->default('Scheduled')->after('complete');
            $table->dateTime('status_updated_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(CreateLessonsTable::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(['notes', 'status']);
        });
    }
}
