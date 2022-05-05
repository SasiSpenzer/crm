<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\Member;
use DB;

class VersionController extends Controller
{
	public function index($version)
	{
		if($version == 1)
			return $this->version_2018_1_5();
        if($version == 2)
            return $this->version_2018_2_26();
	}

    //to copy al call_date_time to payment_exp_date
    public function version_2018_2_26()
    {
        $all = Member::all(['id', 'payment_exp_date', 'call_date_time']);
        $string = '';

        foreach ($all as $key) {
            if($key->payment_exp_date == null && $key->call_date_time != null){
                $string .= $key->payment_exp_date . ', ';
                $key->update(['payment_exp_date' => $key->call_date_time]);
            }
        }

        return $string;
    }

    public function version_2018_1_5()
    {

        $result = DB::select("SELECT * FROM `admin_users` LIMIT 1");
        $msg = "";
        
        if (isset($result->admin_level)){
            DB::select("ALTER TABLE `admin_users` ADD `admin_level` TINYINT NOT NULL DEFAULT '2' AFTER `email`");
            $msg = "'admin_level' column was added successfully to 'admin_user' table <br/><br/>";
        }
        
        $result = DB::select("SELECT * FROM `admin_members_actions` LIMIT 1");
        if (isset($result->reminder)){
            DB::select("ALTER TABLE `admin_members_actions` ADD `reminder` DATE NULL AFTER `comments`");
            $msg = $msg . "'reminder' column was added successfully to 'admin_members_actions' table <br/><br/>";
        }

        $result = DB::select("SELECT * FROM `admin_members` LIMIT 1");
        if (isset($result->package_amount)){
            DB::select("ALTER TABLE `admin_members` ADD `package_amount` DECIMAL(10, 2) NOT NULL AFTER `category`");
            $msg = $msg . "'package_amount' column was added successfully to 'admin_members' table <br/><br/>";
        }

        $result = DB::select("SELECT * FROM `admin_members` LIMIT 1");
        if (isset($result->custom_amount)){
            DB::select("ALTER TABLE `admin_members` ADD `custom_amount` TINYINT(1) NOT NULL DEFAULT '0' AFTER `category`");
            $msg = $msg . "'custom_amount' column was added successfully to 'admin_members' table <br/><br/>";
        }

        $result = DB::select("SELECT * FROM `payment_log` LIMIT 1");
        if (isset($result->user_id)){
            DB::select("CREATE TABLE `payment_log` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `amount` DECIMAL(10, 2) NOT NULL , `assign_id` VARCHAR(50) NULL , `assign_type` INT NULL , `updated_at` TIMESTAMP NOT NULL , `created_at` TIMESTAMP NOT NULL , PRIMARY KEY (`id`))");
            $msg = $msg . "'payment_log' table was created successfully <br/><br/>";
        }

        $result = DB::select("SELECT * FROM `admin_users` LIMIT 1");
        if (isset($result->target)){
            DB::select("ALTER TABLE `admin_users` ADD `target` DECIMAL(10, 2) NOT NULL DEFAULT '2' AFTER `admin_level`");
            $msg = $msg . "'target' column was added successfully to 'admin_user' table <br/><br/>";
        }

        DB::select("ALTER TABLE `admin_users` CHANGE `password_md5` `password_md5` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        $msg = $msg . "'password_md5' column was updated for null values of 'admin_users' table <br/><br/>";

        // update previous bought ackages
        $packages = [];
        $pack = Package::all();
        foreach ($pack as $key) {
            $packages[$key->package_name]['Quarterly'] = $key->quarterly_payment;
            $packages[$key->package_name]['Annually'] = $key->annual_payment;
        }

        $members = Member::where('type', '<>', '')
                        ->where('payment', '<>', '')->get();
        
        foreach ($members as $key) {
            if (!isset($packages[$key->category][$key->payment]))
                continue;
            /*if($key->payment == 'Quartaly')
                continue; // because of spelling mistake
            if($key->category == 'TBC')
                continue; // TBC not defign in package_member_table
                //$key->category = 'Single Ad';*/
            $package_amount = $packages[$key->category][$key->payment];
            $key->update(['package_amount' => $package_amount]);
        }

        $msg = $msg . "All bought packages were updated successfully <br><br> Congratulation, Versioning was done!";

        return $msg;
    }
}
