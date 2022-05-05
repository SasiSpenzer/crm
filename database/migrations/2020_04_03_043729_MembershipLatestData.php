<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipLatestData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_latest_data', function(Blueprint $table) {
            $table->bigIncrements('membership_latest_data_id');
            $table->integer('member_user_id')->nullable();
            $table->string('membership_type')->nullable();
            $table->string('membership_duration')->nullable();
            $table->date('membership_expire_data')->nullable();
            $table->date('payment_expire_data')->nullable();
            $table->decimal('membership_amount', 8, 2)->default(0);
            $table->decimal('membership_pending_payment', 8, 2)->default(0);
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
        Schema::drop('membership_latest_data');
    }
}
