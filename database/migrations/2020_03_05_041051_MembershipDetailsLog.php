<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipDetailsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_details_log', function(Blueprint $table)
        {
            $table->bigIncrements('membership_details_log_id');
            $table->integer('changer_user_id');
            $table->text('changing_description')->nullable();
            $table->integer('approve_user_id')->nullable();
            $table->text('approve_description')->nullable();
            $table->dateTime('approve_at')->nullable();
            $table->tinyInteger('is_approve')->default(0);
            $table->tinyInteger('is_expire')->default(0);
            $table->integer('member_user_id');
            $table->string('old_membership_type')->nullable();
            $table->string('new_membership_type')->nullable();
            $table->string('old_membership_category')->nullable();
            $table->string('new_membership_category')->nullable();
            $table->double('old_package_amount',8, 2)->nullable();
            $table->double('new_package_amount', 8, 2)->nullable();
            $table->string('old_membership_payment')->nullable();
            $table->string('new_membership_payment')->nullable();
            $table->string('old_membership_duration')->nullable();
            $table->string('new_membership_duration')->nullable();
            $table->dateTime('old_membership_call_date')->nullable();
            $table->dateTime('new_membership_call_date')->nullable();
            $table->dateTime('old_membership_expiry_date')->nullable();
            $table->dateTime('new_membership_expiry_date')->nullable();
            $table->integer('old_membership_active_add_count')->nullable();
            $table->integer('new_membership_active_add_count')->nullable();
            $table->integer('old_membership_leads_count')->nullable();
            $table->integer('new_membership_leads_count')->nullable();
            $table->string('old_membership_am')->nullable();
            $table->string('new_membership_am')->nullable();
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
        Schema::drop('membership_details_log');
    }
}
