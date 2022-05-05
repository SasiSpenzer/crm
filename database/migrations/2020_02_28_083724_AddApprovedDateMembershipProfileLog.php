<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedDateMembershipProfileLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_profile_log', function ($table) {
            $table->dateTime('approved_date')->after('is_approve')->nullable();
            $table->text('approved_description')->after('approved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_profile_log', function ($table) {
            $table->dropColumn('approved_date');
            $table->dropColumn('approved_description');
        });
    }
}
