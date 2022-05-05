<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTypeForMembershipDetailsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_details_log', function ($table) {
            $table->integer('old_user_type_id')->after('member_user_id')->default(1);
            $table->integer('new_user_type_id')->after('old_user_type_id')->default(1);
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
            $table->dropColumn('old_user_type_id');
            $table->dropColumn('new_user_type_id');
        });
    }
}
