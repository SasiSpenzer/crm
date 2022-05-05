<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipProfileLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_profile_log', function(Blueprint $table)
        {
            $table->bigIncrements('membership_profile_log_id');
            $table->integer('changer_user_id');
            $table->integer('approve_user_id')->nullable();
            $table->tinyInteger('is_approve')->default(0);
            $table->integer('member_user_id');
            $table->string('old_company_name')->nullable();
            $table->string('new_company_name')->nullable();
            $table->string('old_contact_number')->nullable();
            $table->string('new_contact_number')->nullable();
            $table->string('old_linkin_profile')->nullable();
            $table->string('new_linkin_profile')->nullable();
            $table->string('old_member_image')->nullable();
            $table->string('new_member_image')->nullable();
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
        Schema::drop('membership_profile_log');
    }
}
