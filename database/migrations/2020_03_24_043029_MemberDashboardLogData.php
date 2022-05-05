<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MemberDashboardLogData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_dashboard_log_data', function(Blueprint $table)
        {
            $table->bigIncrements('member_dashboard_log_data_id');
            $table->integer('UID');
            $table->string('Uemail')->nullable();
            $table->string('username')->nullable();
            $table->integer('ads_count')->default(0);
            $table->string('category')->nullable();
            $table->date('member_since')->nullable();
            $table->date('membership_exp_date')->nullable();
            $table->date('payment_exp_date')->nullable();
            $table->string('am')->nullable();
            $table->integer('max_page_id')->default(0);
            $table->string('pic_percentage')->nullable();
            $table->integer('ad_upgrade_count')->default(0);
            $table->string('boosts_left')->nullable();
            $table->integer('total_stats')->default(0);
            $table->string('views_percentage')->nullable();
            $table->string('leads_percentage')->nullable();
            $table->integer('ad_hits')->default(0);
            $table->integer('status')->default(0);
            $table->string('status_img')->nullable();
            $table->string('class')->nullable();
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
        Schema::drop('member_dashboard_log_data');
    }
}
