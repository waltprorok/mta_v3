<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlatRateBillingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('billing_rates', function (Blueprint $table) {
            $table->boolean('flat_rate')->default(false)->after('active');
            $table->boolean('cancelled_twenty_four_hours')->default(false)->after('flat_rate');
            $table->boolean('cancelled_forty_eight_hours')->default(false)->after('cancelled_twenty_four_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('billing_rates', function (Blueprint $table) {
            $table->dropColumn(['flat_rate', 'cancelled_twenty_four_hours', 'cancelled_forty_eight_hours']);
        });
    }
}
