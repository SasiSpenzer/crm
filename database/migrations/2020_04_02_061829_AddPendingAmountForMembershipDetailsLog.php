<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPendingAmountForMembershipDetailsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_details_log', function ($table) {
            $table->decimal('old_pending_amount', 8, 2)->after('new_membership_am')->default(0);
            $table->decimal('new_pending_amount', 8, 2)->after('old_pending_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_details_log', function ($table) {
            $table->dropColumn('old_pending_amount');
            $table->dropColumn('new_pending_amount');
        });
    }
}
