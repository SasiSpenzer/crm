<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsMembershipProfileLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_profile_log', function ($table) {
            $table->integer('is_expire')->after('new_member_image')->default(0);
            $table->text('changing_description')->after('is_expire')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_members', function ($table) {
            $table->dropColumn('is_expire');
            $table->dropColumn('changing_description');
        });
    }
}
