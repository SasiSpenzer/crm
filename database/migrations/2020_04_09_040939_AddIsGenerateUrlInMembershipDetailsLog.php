<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsGenerateUrlInMembershipDetailsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_details_log', function ($table) {
            $table->integer('is_only_generate_url')->after('is_expire')->default(0);
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
            $table->dropColumn('is_only_generate_url');
        });
    }
}
