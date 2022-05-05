<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPhoneRestrictionForMembershipDetailsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_details_log', function ($table) {
            $table->integer('is_old_phone_restriction')->after('new_pending_amount')->default(0);
            $table->integer('is_new_phone_restriction')->after('is_old_phone_restriction')->default(0);
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
            $table->dropColumn('is_old_phone_restriction');
            $table->dropColumn('is_new_phone_restriction');
        });
    }
}
