<?php

namespace App\Http\Controllers;

use App\Contracts\AdvertisementInterface;
use Illuminate\Http\Request;
use App\Advertisement;
use Datatables;
use DB;

class AdvertisementController extends Controller {

	protected $ads;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(AdvertisementInterface $ads) {
		$this->middleware('auth');
		$this->ads = $ads;
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('advertisement.index');
	}

	/**
	 * Activate Ad
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function ActivateAd(Request $request) {
		return $this->ads->ActivateAd($request->input('ad_id'), $request->input('is_checked'));
	}
    public function AutoboostAd(Request $request) {
        return $this->ads->AutoboostAd($request->input('ad_id'), $request->input('is_checked'));
    }

	public function getSingleAd()
	{
		return view('ads.singlead_pending');
	}

	public function postSingleAd(Request $request)
	{
        $data = $request->input();
        $type = $data['type'] ;

        $today = date('Y-m-d');
        $before_day_time = strtotime('-1 day', strtotime($today));
        $before_day_time = date('Y-m-d',$before_day_time);

        $before_three_month = strtotime('-3 month', strtotime($today));
        $before_three_month = date('Y-m-d',$before_three_month);

        $before_14_days = strtotime('-14 days', strtotime($today));
        $before_14_days = date('Y-m-d',$before_14_days);

        $before_7_days = strtotime('-14 days', strtotime($today));
        $before_7_days = date('Y-m-d',$before_7_days);

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql =  "SELECT DISTINCT `adverts`.`UID` AS 'UID',
                            `adverts`.`ad_id` AS 'ad_id',
                            `adverts`.`propty_type` AS 'propty_type',
                            `adverts`.`type` AS 'type',
                            `adverts`.`submit_date` AS 'submit_date',
                             CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                            `admin_members`.`expiry` AS 'expiry',
                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                            `admin_members`.`am` AS 'am',
                            `admin_members`.`type` AS 'Admin_member_type',
                            `admin_members`.`package_amount` AS 'package_amount',
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            `admin_members`.`latest_comment`,
                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                            DATE_FORMAT(`admin_members`.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                            DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            `users`.`Uemail` AS 'Uemail',
                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                            CASE
                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
                                ELSE `adverts`.`price`
                            END AS 'ad_price',
                            CASE
                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bungalow' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Villa' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bare Land' THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
                                THEN 'yellow-class'
                                
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts` 
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                        LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID` ";

            if($type == 2){
                $sql .= "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";
            }

            $sql .= "LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                        WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."' AND `adverts`.`posted_date` > '".$before_three_month."'AND `admin_members`.`type` != 'Pending Payment' ";

            if($type == 2){
                $sql .= " AND (ama.created_at IS NULL OR ama.created_at > '".$before_7_days."') ";
            }
            if($type == 3){
                $sql .= " AND (SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) <  `admin_members`.`payment_exp_date`";
            }

            $sql .= " AND `admin_members`.`expiry` < '".$today."' AND `admin_members`.`expiry` >= '".$before_14_days."'
                        AND (`admin_members`.`type` != 'Member') AND `admin_members`.`category` = 'Single Ad' AND `admin_members`.`expiry` < '".$today."' AND
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `admin_members`.`am`  LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%') GROUP BY `adverts`.`UID`";
            $customer = DB::select($sql);
        } else {
            $sql =
                "SELECT DISTINCT `adverts`.`UID` AS 'UID',
                        `adverts`.`ad_id` AS 'ad_id',
                        `adverts`.`propty_type` AS 'propty_type',
                        `adverts`.`type` AS 'type',
                        `adverts`.`submit_date` AS 'submit_date',
                        `admin_members`.`type` AS 'Admin_member_type',
                         CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                        `admin_members`.`expiry` AS 'expiry',
                        `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                        `admin_members`.`am` AS 'am',
                        `admin_members`.`package_amount` AS 'package_amount',
                        `payment_status`.`payment_status` AS 'status',
                        `admin_members`.`duration`,
                        `admin_members`.`latest_comment`,
                        (SELECT `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                        DATE_FORMAT(`admin_members`.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                        DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                        `users`.`Uemail` AS 'Uemail',
                        CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                        CASE
                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
                                ELSE `adverts`.`price`
                            END AS 'ad_price',
                        CASE
                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bungalow' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Villa' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bare Land' THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
                                THEN 'yellow-class'
                                
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                FROM `adverts` 
                LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID` ";

            if($type == 2){
            $sql .= "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";
            }

            $sql .= "LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."'  AND `adverts`.`posted_date` > '".$before_three_month."' ";

            if($type == 2){
                $sql .= " AND (ama.created_at IS NULL OR ama.created_at > '".$before_7_days."') ";
            }
            if($type == 3){
                $sql .= " AND (SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) <  `admin_members`.`payment_exp_date`";
            }

            $sql .= " AND `admin_members`.`expiry` < '".$today."' AND `admin_members`.`expiry` >= '".$before_14_days."' AND `admin_members`.`type` != 'Pending Payment'
                AND  (`admin_members`.`type` != 'Member') AND  `admin_members`.`expiry` < '".$today."' AND `admin_members`.`category` = 'Single Ad' GROUP BY `adverts`.`UID`";
//            echo $sql; exit();
            $customer = DB::select($sql);
        }

		return Datatables::of($customer)->make(true);
	}

	public function getSingleAdAll()
	{
		return view('ads.singlead_pending_all');
	}

    // Old one
//    public function postSingleAdAll(Request $request)
//    {
//        $data = $request->input();
//        $today = date('Y-m-d');
//        $before_day_time = strtotime('-1 day', strtotime($today));
//        $before_day_time = date('Y-m-d',$before_day_time);
//        $before_three_month = strtotime('-3 month', strtotime($today));
//        $before_three_month = date('Y-m-d',$before_three_month);
//        $type = $data['type'] ;
//
//        $before_three_days = strtotime('-3 days', strtotime($today));
//        $before_two_days = strtotime('-2 days', strtotime($today));
//        $before_five_days = strtotime('-5 days', strtotime($today));
//        $before_12_days = strtotime('-12 days', strtotime($today));
//        $before_14_days = strtotime('-14 days', strtotime($today));
//        $before_23_days = strtotime('-23 days', strtotime($today));
//
//        $before_three_days = date('Y-m-d',$before_three_days);
//        $before_two_days = date('Y-m-d',$before_two_days);
//        $before_five_days = date('Y-m-d',$before_five_days);
//        $before_12_days = date('Y-m-d',$before_12_days);
//        $before_14_days = date('Y-m-d',$before_14_days);
//        $before_23_days = date('Y-m-d',$before_23_days);
//
//        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
//            $search_data = $data['search']['value'];
//            $sql =  "SELECT DISTINCT `adverts`.`UID` AS 'UID',
//                            `adverts`.`ad_id` AS 'ad_id',
//                            `adverts`.`propty_type` AS 'propty_type',
//                            `adverts`.`type` AS 'type',
//                            `adverts`.`submit_date` AS 'submit_date',
//                            CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
//                            `admin_members`.`expiry` AS 'expiry',
//                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
//                            `admin_members`.`am` AS 'am',
//                            `admin_members`.`package_amount` AS 'package_amount',
//                            `payment_status`.`payment_status` AS 'status',
//                            `admin_members`.`duration`,
//                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
//                            `admin_members`.`latest_comment`,";
//            if($type == '2' || $type == '3'){
//                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
//            }else {
//                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID`  ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
//            }
//
//            $sql .=  "                DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
//                            `users`.`Uemail` AS 'Uemail',
//                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
//                            CASE
//                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
//                                WHEN `adverts`.`type` = 'land' THEN `adverts`.`price_land_total`
//                                ELSE `adverts`.`price`
//                            END AS 'ad_price',
//                            CASE
//                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
//                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
//                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`price` > 50000000 THEN 'yellow-class'
//                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`type` = 'rentals' AND `adverts`.`price_monthly` > 200000
//                                THEN 'yellow-class'
//                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
//                                THEN 'yellow-class'
//                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price_land_total` > 40000000
//                                THEN 'yellow-class'
//                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
//                                THEN 'yellow-class'
//	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND
//	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
//                                ELSE ''
//                            END AS 'class'
//                        FROM `adverts`
//                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
//                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`
//                        LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`";
//            if($type == '2' || $type == '3'){
//                $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid` AND `by` <> 'System'";
//            }
//            $sql .=  "LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
//                        WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."' AND `adverts`.`posted_date` > '".$before_three_month."'
//                        AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad' AND
//                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'
//                        OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%' OR `admin_members`.`am` LIKE '%".$search_data."%') ";
//            if($type == 2){
//                $sql .= " AND ama.created_at IS NULL AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')";
//            }else if ($type == 3){
//                $sql .= " AND (SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) < '".$before_five_days."' AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')
//                            AND (ama.reminder < '".$today."' OR ama.reminder IS NULL) ";
//            }else if ($type == 4){
//                $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') = '".$before_12_days."' AND  `admin_members`.`am` != 'System' ";
//            }
//            else if($type == 5){
//                $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') > '".$before_23_days."' AND  `admin_members`.`am` = 'System' ";
//            }
//            $sql .= "GROUP BY `adverts`.`UID`";
//
//        } else {
//            $sql = "SELECT DISTINCT `adverts`.`UID` AS 'UID',
//                            `adverts`.`ad_id` AS 'ad_id',
//                            `adverts`.`propty_type` AS 'propty_type',
//                            `adverts`.`type` AS 'type',
//                            `adverts`.`submit_date` AS 'submit_date',
//                            CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
//                            `admin_members`.`expiry` AS 'expiry',
//                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
//                            `admin_members`.`am` AS 'am',
//                            `admin_members`.`package_amount` AS 'package_amount',";
//            if($type == '2' || $type == '3'){
//                //$sql .=  "(SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) AS maxD,";
//            }
//            $sql .=  "
//                            `payment_status`.`payment_status` AS 'status',
//                            `admin_members`.`duration`,
//                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
//                            DATE_FORMAT(`ama`.`date_time`,'%Y-%m-%d') AS 'last_updated_at',
//                            `admin_members`.`latest_comment`,";
//            if($type == '2' || $type == '3'){
//                //$sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
//            }else {
//                //$sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID`  ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
//            }
//            $sql .= "               DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
//                            `users`.`Uemail` AS 'Uemail',
//                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
//                            CASE
//                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
//                                WHEN `adverts`.`type` = 'land' THEN `adverts`.`price_land_total`
//                                ELSE `adverts`.`price`
//                            END AS 'ad_price',
//                            CASE
//                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
//                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
//                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`price` > 50000000 THEN 'yellow-class'
//                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`type` = 'rentals' AND `adverts`.`price_monthly` > 200000
//                                THEN 'yellow-class'
//                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
//                                THEN 'yellow-class'
//                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price_land_total` > 40000000
//                                THEN 'yellow-class'
//                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
//                                THEN 'yellow-class'
//	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND
//	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
//                                ELSE ''
//                            END AS 'class'
//                        FROM `adverts`
//                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`";
//            if($type == '2' || $type == '3'){
//                $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid` AND `by` <> 'System'";
//            } else {
//                $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";
//            }
//            $sql .=  "LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
//                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`
//                        LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
//                        WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."' AND `adverts`.`posted_date` > '".$before_three_month."'
//                        AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad' ";
//            if($type == 2){
//                $sql .= " AND ama.created_at IS NULL AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')";
//            }else if ($type == 3){
//                $sql .= " AND (SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) < '".$before_three_days."' AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')
//                            AND (ama.reminder < '".$today."' OR ama.reminder IS NULL) ";
//            }else if ($type == 4){
//                $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') = '".$before_12_days."' AND  `admin_members`.`am` != 'System' ";
//            } else if($type == 5){
//                $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') > '".$before_23_days."' AND  `admin_members`.`am` = 'System' ";
//            }
//            $sql .= "GROUP BY `adverts`.`UID`";
//
//        }
//        echo $sql ; exit();
//        $customer = DB::select($sql);
//        //$customer = '';
//        return Datatables::of($customer)->make(true);
//    }


    // New Function By Sasi
	public function postSingleAdAll(Request $request)
	{
        $data = $request->input();
        $today = date('Y-m-d');
        $before_day_time = strtotime('-1 day', strtotime($today));
        $before_day_time = date('Y-m-d',$before_day_time);
        $before_three_month = strtotime('-3 month', strtotime($today));
        $before_three_month = date('Y-m-d',$before_three_month);
        $type = $data['type'] ;

        $before_three_days = strtotime('-3 days', strtotime($today));
        $before_two_days = strtotime('-2 days', strtotime($today));
        $before_five_days = strtotime('-5 days', strtotime($today));
        $before_12_days = strtotime('-12 days', strtotime($today));
        $before_14_days = strtotime('-14 days', strtotime($today));
        $before_23_days = strtotime('-23 days', strtotime($today));

        $before_three_days = date('Y-m-d',$before_three_days);
        $before_two_days = date('Y-m-d',$before_two_days);
        $before_five_days = date('Y-m-d',$before_five_days);
        $before_12_days = date('Y-m-d',$before_12_days);
        $before_14_days = date('Y-m-d',$before_14_days);
        $before_23_days = date('Y-m-d',$before_23_days);

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql =  "SELECT DISTINCT `adverts`.`UID` AS 'UID',
                            `adverts`.`ad_id` AS 'ad_id',
                            `adverts`.`propty_type` AS 'propty_type',
                            `adverts`.`type` AS 'type',
                            `adverts`.`submit_date` AS 'submit_date',
                            CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                            `admin_members`.`expiry` AS 'expiry',
                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                            `admin_members`.`am` AS 'am',
                            `admin_members`.`package_amount` AS 'package_amount',
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                            `admin_members`.`latest_comment`,";
            if($type == '2' || $type == '3'){
                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
            }else {
                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID`  ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
            }

            $sql .=  "                DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            `users`.`Uemail` AS 'Uemail',
                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                            CASE
                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
                                WHEN `adverts`.`type` = 'land' THEN `adverts`.`price_land_total`
                                ELSE `adverts`.`price`
                            END AS 'ad_price',
                            CASE
                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`price` > 50000000 THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`type` = 'rentals' AND `adverts`.`price_monthly` > 200000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price_land_total` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
                                THEN 'yellow-class'
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts`
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`
                        LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`";

                $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";

            $sql .=  "LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status` ";
            $sql .=  "   WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."' AND `adverts`.`posted_date` > '".$before_three_month."' AND (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment')  AND
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'
                        OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%' OR `admin_members`.`am` LIKE '%".$search_data."%') ";

                        if($type == 2){
                            $sql .= " AND ama.created_at IS NULL AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')";
                        }else if ($type == 3){
                            $sql .= " AND (SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) < '".$before_three_days."' AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')
                            AND (ama.reminder < '".$today."' OR ama.reminder IS NULL) ";
                        }else if ($type == 4){
                            $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') = '".$before_12_days."' AND  `admin_members`.`am` != 'System' ";
                        }
                        else if($type == 5){
                            $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') > '".$before_23_days."' AND  `admin_members`.`am` = 'System' ";
                        }
                        $sql .= "GROUP BY `adverts`.`UID`";

        } else {
            $sql = "SELECT DISTINCT `adverts`.`UID` AS 'UID',
                            `adverts`.`ad_id` AS 'ad_id',
                            `adverts`.`propty_type` AS 'propty_type',
                            `adverts`.`type` AS 'type',
                            `adverts`.`submit_date` AS 'submit_date',
                            CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                            `admin_members`.`expiry` AS 'expiry',
                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                            `admin_members`.`am` AS 'am',
                            `admin_members`.`package_amount` AS 'package_amount',";
            if($type == '2' || $type == '3'){
                $sql .=  "(SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) AS maxD,";
            }
            $sql .=  "
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                            DATE_FORMAT(`ama`.`date_time`,'%Y-%m-%d') AS 'last_updated_at',
                            `admin_members`.`latest_comment`,";
            if($type == '2' || $type == '3'){
                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
            }else {
                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID`  ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";
            }
            $sql .= "               DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            `users`.`Uemail` AS 'Uemail',
                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                            CASE
                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
                                WHEN `adverts`.`type` = 'land' THEN `adverts`.`price_land_total`
                                ELSE `adverts`.`price`
                            END AS 'ad_price',
                            CASE
                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`price` > 50000000 THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'House' AND `adverts`.`type` = 'rentals' AND `adverts`.`price_monthly` > 200000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price_land_total` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
                                THEN 'yellow-class'
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts`
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`";
            if($type == '2' || $type == '3'){
                $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";
            } else {
                $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";
            }
              $sql .=  "LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`
                        LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status` ";

            $sql .=  " WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."' AND `adverts`.`posted_date` > '".$before_three_month."' AND (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment')  AND admin_members.`category` = 'Single Ad' ";

                        if($type == 2){
                            $sql .= " AND ama.created_at IS NULL AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')";
                        }else if ($type == 3){
                            $sql .= " AND (SELECT `created_at` FROM admin_members_actions WHERE uid = `adverts`.`UID` ORDER BY id DESC LIMIT 1) < '".$before_three_days."' AND (adverts.`posted_date` >= '".$before_14_days."' AND adverts.`posted_date` < '".$before_two_days."')
                            AND (ama.reminder < '".$today."' OR ama.reminder IS NULL) ";
                        }else if ($type == 4){
                            $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') = '".$before_12_days."' AND  `admin_members`.`am` != 'System' ";
                        } else if($type == 5){
                            $sql .= " AND DATE_FORMAT(adverts.`posted_date`,'%Y-%m-%d') > '".$before_23_days."' AND  `admin_members`.`am` = 'System' ";
                        }
                        $sql .= "GROUP BY `adverts`.`UID`";

        }
        //echo $sql ; exit();
        $customer = DB::select($sql);
        //$customer = '';
		return Datatables::of($customer)->make(true);
	}


    public function getActiveSingleAdsAll(Request $request)
    {
        $data = $request->input();
        $today = date('Y-m-d');
        $before_three_month = strtotime('-3 month', strtotime($today));
        $before_three_days = strtotime('-3 days', strtotime($today));
        $before_seven_days = strtotime('-7 days', strtotime($today));
        $before_12_days = strtotime('-12 days', strtotime($today));

        $before_three_month = date('Y-m-d',$before_three_month);
        $before_three_days = date('Y-m-d',$before_three_days);
        $before_seven_days = date('Y-m-d',$before_seven_days);
        $before_12_days = date('Y-m-d',$before_12_days);

        $type = intval($request->query()['type']);

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT DISTINCT a.`UID` AS 'UID',
                       a.`ad_id` AS 'ad_id',
                       a.`is_active`,
                       am.`type`,
                       `am`.`category`,
                       a.`propty_type` AS 'propty_type',
                       a.`type` AS 'type',
                       a.`submit_date` AS 'submit_date',
                       CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                       /*DATE_FORMAT(am.`expiry`,'%Y-%m-%d') AS 'expiry',*/
                       DATE_FORMAT(am.`expiry`,'%Y-%m-%d') AS 'expiry',
                       DATE_FORMAT(am.`payment_exp_date`,'%Y-%m-%d') AS 'payment_exp_date',
                       am.`am` AS 'am',
                       am.`package_amount` AS 'package_amount',
                       am.`payment_status` AS 'status',
                       am.`duration`,
                       a.`price`,
                       a.`price_monthly`,
                       CASE
                                WHEN `a`.`propty_type` = 'Apartment' THEN 'green-class'
                                WHEN `a`.`propty_type` = 'Commercial' THEN 'green-class'
                                WHEN `a`.`propty_type` = 'House' AND `a`.`price` > 50000000 THEN 'green-class'
                                WHEN `a`.`propty_type` = 'House' AND `a`.`type` = 'rentals' AND `a`.`price_monthly` > 200000
                                THEN 'green-class'
	                            WHEN `a`.`type` = 'land' AND `a`.`propty_type` != 'Bare Land' AND 
	                                `a`.`propty_type` != 'Land with house' THEN 'green-class'
                                ELSE ''
                            END AS 'class',
                        
                       am.`latest_comment`,
                        CASE
                                WHEN `a`.`type` = 'rentals' AND  `a`.`price_monthly` > 100000 THEN 'Yes'
                                WHEN `a`.`type` = 'land' AND  `a`.`price_land_total` > 4000000  THEN  'Yes'
                                WHEN `a`.`type` = 'sales' AND  `a`.`price` > 4000000  THEN  'Yes'
                                ELSE ''
                       END AS 'filter_ad_price',
                       DATE_FORMAT(am.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                       DATE_FORMAT(a.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                       u.`Uemail` AS 'Uemail',
                       CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username',
                       ad_upgrade_package.`priority_name` AS 'upgrade_type'
                    FROM `adverts` AS a
                    LEFT JOIN `ad_upgrade_package` ON ad_upgrade_package.priority_id = a.priority
                    LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = a.`UID`
                    INNER JOIN `admin_members` AS am ON am.`uid` = a.`UID`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid` ";
            if($type != 5) {
                $sql .= " WHERE a.`is_active` = 1 AND  `am`.`type` = 'Member' AND `am`.`category` = 'Single Ad' AND a.`type` <> 'agents' AND a.`type` <> 'wanted' AND
                        (u.`firstname` LIKE '%" . $search_data . "%' OR u.`surname` LIKE '%" . $search_data . "%'  OR u.`Uemail` LIKE '%" . $search_data . "%' OR am.`mobile_nos` LIKE '%" . $search_data . "%')  
                        AND (am.`category` = 'Free' OR am.`category` = 'Single Ad' OR am.`category` = '' OR am.`category` = NULL)";
            } else {
//                $sql .= " WHERE a.`is_active` = 1 AND  `am`.`type` = 'Member' AND `am`.`category` = 'Free' AND a.`type` <> 'agents' AND a.`type` <> 'wanted' AND
//                        (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%'  OR u.`Uemail` LIKE '%".$search_data."%' OR am.`mobile_nos` LIKE '%".$search_data."%')
//                        AND (am.`category` = 'Free' OR am.`category` = 'Single Ad' OR am.`category` = '' OR am.`category` = NULL)";
            }

            if($type == 2){

                $sql .= " AND (am.`am` = 'Online' OR am.`am` IS NULL )";
            }else if ($type == 3){
                $sql .= " AND am.`latest_comment` IS NULL ";
            }else if ($type == 4){
                $sql .= " AND DATE_FORMAT(am.`updated_at`,'%Y-%m-%d') < '".$before_three_days."' AND  am.`latest_comment` <> ''" ;
            }else if ($type == 5){
                $sql .= " WHERE
                am.category = 'Single Ad' AND
                 
                am.type = 'Member' AND
                (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%'  OR u.`Uemail` LIKE '%".$search_data."%' OR am.`mobile_nos` LIKE '%".$search_data."%')
                AND am.payment ='Free' AND
                a.is_active=1 AND
                ((a.type = 'sales' AND a.price >=40000000) OR
                (a.type = 'rentals' AND a.price >=150000) OR
                (a.type = 'land' AND a.price_land_total >=40000000))";
            }  else if ($type == 6){
                $sql .= " AND  a.`priority` > 0 AND (SELECT COUNT(`leads_month_data_id`) FROM `leads_month_data` WHERE ad_id = a.`ad_id`) < 5 
                AND (SELECT DATE_FORMAT(`created_at`,'%Y-%m-%d') FROM `upgraded_ads` WHERE ad_id = a.`ad_id` LIMIT 1) <= '".$before_seven_days."' ";
            }
            $sql .= "  ORDER BY am.`expiry` DESC";
        } else {
            $sql = "SELECT DISTINCT a.`UID` AS 'UID',
                       a.`ad_id` AS 'ad_id',
                       a.`is_active`,
                       `am`.`type`,
                       `am`.`category`,
                       a.`propty_type` AS 'propty_type',
                       a.`type` AS 'type',
                       a.`submit_date` AS 'submit_date',
                      CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                       /*DATE_FORMAT(am.`expiry`,'%Y-%m-%d') AS 'expiry',*/
                       DATE_FORMAT(am.`expiry`,'%Y-%m-%d') AS 'expiry',
                       DATE_FORMAT(am.`payment_exp_date`,'%Y-%m-%d') AS 'payment_exp_date',
                       am.`am` AS 'am',
                       am.`package_amount` AS 'package_amount',
                       am.`payment_status` AS 'status',
                       am.`duration`,
                       a.`price`, 
                       a.`price_monthly`,
                       CASE
                                WHEN `a`.`propty_type` = 'Apartment' THEN 'green-class'
                                WHEN `a`.`propty_type` = 'Commercial' THEN 'green-class'
                                WHEN `a`.`propty_type` = 'House' AND `a`.`price` > 50000000 THEN 'green-class'
                                WHEN `a`.`propty_type` = 'House' AND `a`.`type` = 'rentals' AND `a`.`price_monthly` > 200000
                                THEN 'green-class'
	                            WHEN `a`.`type` = 'land' AND `a`.`propty_type` != 'Bare Land' AND 
	                                `a`.`propty_type` != 'Land with house' THEN 'green-class'
                                ELSE ''
                            END AS 'class',
                       a.`price_monthly`, 
                       CASE
                                WHEN `a`.`type` = 'rentals' AND  `a`.`price_monthly` > 100000 THEN 'Yes'
                                WHEN `a`.`type` = 'land' AND  `a`.`price_land_total` > 4000000  THEN  'Yes'
                                WHEN `a`.`type` = 'sales' AND  `a`.`price` > 4000000  THEN  'Yes'
                                ELSE ''
                       END AS 'filter_ad_price',
                       am.`latest_comment`,
                       DATE_FORMAT(am.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                       DATE_FORMAT(a.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                       u.`Uemail` AS 'Uemail',
                       CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username',
                        ad_upgrade_package.`priority_name` AS 'upgrade_type'
                    FROM `adverts` AS a
                    LEFT JOIN `ad_upgrade_package` ON ad_upgrade_package.priority_id = a.priority          
                    LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = a.`UID`
                    INNER JOIN `admin_members` AS am ON am.`uid` = a.`UID`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid` ";
            if($type != 5) {
                $sql .= " WHERE a.`is_active` = 1 AND  `am`.`type` = 'Member' AND `am`.`category` = 'Single Ad' ";
            } else {
                //$sql .= " WHERE a.`is_active` = 1 AND  `am`.`type` = 'Member' AND `am`.`category` = 'Free' AND a.`type` <> 'agents' AND a.`type` <> 'wanted'";
            }

            if($type == 2){
                $sql .= " AND (am.`am` = 'Online' OR am.`am` IS NULL )";
            } else if ($type == 3){
                $sql .= " AND am.`latest_comment` IS NULL ";
            } else if ($type == 4){
                $sql .= " AND DATE_FORMAT(am.`updated_at`,'%Y-%m-%d') < '".$before_three_days."' AND  am.`latest_comment` <> ''" ;
            } else if ($type == 5){
                $sql .= " WHERE
                am.category = 'Single Ad' AND
                 
                am.type = 'Member' AND
                am.payment ='Free' AND
                a.is_active=1 AND
                ((a.type = 'sales' AND a.price >=40000000) OR
                (a.type = 'rentals' AND a.price >=150000) OR
                (a.type = 'land' AND a.price_land_total >=40000000))";
            } else if ($type == 6){
                $sql .= " AND  a.`priority` > 0 AND (SELECT COUNT(`leads_month_data_id`) FROM `leads_month_data` WHERE ad_id = a.`ad_id`) < 5 
                AND (SELECT DATE_FORMAT(`created_at`,'%Y-%m-%d') FROM `upgraded_ads` WHERE ad_id = a.`ad_id` LIMIT 1) <= '".$before_seven_days."' ";
            }
            $sql .= "ORDER BY am.`expiry` DESC";
        }
        //echo $sql; exit();
        $customer = DB::select($sql);
        for ($i = 0 ;count($customer) > $i ; $i++){
            $customer[$i]->status = config('datatables.payment_status.'.$customer[$i]->status);
            $customer[$i]->upgrade_type = config('datatables.ad_upgrade_package.'.$customer[$i]->upgrade_type);
        }
        return Datatables::of($customer)->make(true);
    }
	public function getSingleAdUpgrade()
    {
        return view('ads.single_ad_pending_upgrade');
    }
    public function getActiveAds(){
        return view('ads.active_ads');
    }

    public function postSingleAdUpgrade(Request $request)
    {
        $data = $request->input();
        $today = date('Y-m-d');

        $before_three_month = strtotime('-3 month', strtotime($today));
        $before_three_month = date('Y-m-d',$before_three_month);

        $before_14_days = strtotime('-14 days', strtotime($today));
        $before_14_days = date('Y-m-d',$before_14_days);

       // $type = intval($request->query()['type']);

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT DISTINCT a.`UID` AS 'UID',
                       a.`ad_id` AS 'ad_id',
                       a.`propty_type` AS 'propty_type',
                       a.`type` AS 'type',
                       a.`submit_date` AS 'submit_date',
                       CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                       /*DATE_FORMAT(am.`expiry`,'%Y-%m-%d') AS 'expiry',*/
                       DATE_FORMAT(pp.`exp_date`,'%Y-%m-%d') AS 'expiry',
                       DATE_FORMAT(am.`payment_exp_date`,'%Y-%m-%d') AS 'payment_exp_date',
                       am.`am` AS 'am',
                       am.`package_amount` AS 'package_amount',
                       am.`payment_status` AS 'status',
                       am.`duration`,
                       am.`latest_comment`,
                       (SELECT  `by` FROM `admin_members_actions` WHERE uid = UID AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                       DATE_FORMAT(am.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                       DATE_FORMAT(a.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                       u.`Uemail` AS 'Uemail',
                       CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username',
                       pp.`priority` AS 'upgrade_type'
                    FROM `adverts` AS a
                    INNER JOIN (SELECT p1.*
                       FROM `pp_payments` p1
                       WHERE p1.`exp_date` = ( SELECT MAX(p2.`exp_date`)
                    FROM `pp_payments` p2
                    WHERE p2.`ad_id` = p1.`ad_id`)) AS pp ON pp.`ad_id` = a.`ad_id`
                    INNER JOIN `admin_members` AS am ON am.`uid` = a.`UID`
                     LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = a.`UID`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE a.`priority` = 0 AND pp.`exp_date` < '".$today."' AND pp.`exp_date` >= '".$before_14_days."' AND u.`user_type` = 'O' AND
                        (u.`firstname` LIKE '%".$search_data."%' OR am.`am` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%'  OR u.`Uemail` LIKE '%".$search_data."%' OR am.`mobile_nos` LIKE '%".$search_data."%')  AND pp.`priority` > 3 
                        AND (am.`category` = 'Free' OR am.`category` = 'Single Ad' OR am.`category` = '' OR am.`category` = NULL)";
                    $sql .= "  ORDER BY pp.`exp_date` DESC";
        } else {
             $sql = "SELECT DISTINCT a.`UID` AS 'UID',
                       a.`ad_id` AS 'ad_id',
                       a.`propty_type` AS 'propty_type',
                       a.`type` AS 'type',
                       a.`submit_date` AS 'submit_date',
                       CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                       /*DATE_FORMAT(am.`expiry`,'%Y-%m-%d') AS 'expiry',*/
                       DATE_FORMAT(pp.`exp_date`,'%Y-%m-%d') AS 'expiry',
                       DATE_FORMAT(am.`payment_exp_date`,'%Y-%m-%d') AS 'payment_exp_date',
                       am.`am` AS 'am',
                       am.`package_amount` AS 'package_amount',
                       am.`payment_status` AS 'status',
                       am.`duration`,
                       am.`latest_comment`,
                       (SELECT  `by` FROM `admin_members_actions` WHERE uid = UID AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                       DATE_FORMAT(am.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                       DATE_FORMAT(a.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                       u.`Uemail` AS 'Uemail',
                       CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username',
                       pp.`priority` AS 'upgrade_type'
                    FROM `adverts` AS a
                    INNER JOIN (SELECT p1.*
                       FROM `pp_payments` p1
                       WHERE p1.`exp_date` = ( SELECT MAX(p2.`exp_date`)
                    FROM `pp_payments` p2
                    WHERE p2.`ad_id` = p1.`ad_id`)) AS pp ON pp.`ad_id` = a.`ad_id`
                    INNER JOIN `admin_members` AS am ON am.`uid` = a.`UID`
                    LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = a.`UID`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE a.`priority` = 0 AND pp.`exp_date` < '".$today."' AND pp.`exp_date` >= '".$before_14_days."' AND u.`user_type` = 'O' AND pp.`priority` > 3 
                    AND (am.`category` = 'Free' OR am.`category` = 'Single Ad' OR am.`category` = '' OR am.`category` = NULL)";
                    $sql .= "ORDER BY pp.`exp_date` DESC";
        }
        //echo $sql ; exit();
        $customer = DB::select($sql);
        for ($i = 0 ;count($customer) > $i ; $i++){
            $customer[$i]->status = config('datatables.payment_status.'.$customer[$i]->status);
            $customer[$i]->upgrade_type = config('datatables.ad_upgrade_package.'.$customer[$i]->upgrade_type);
        }
        return Datatables::of($customer)->make(true);
    }

    /**
     * For get user ads count
     * @param Request $request
     * @return false|string|void
     */
    public function getCustomerAdCount(Request $request) {
        $email_address = $request->input('email');
        $sql = "SELECT u.`UID`, COUNT(a.`ad_id`) AS 'use_ad_count', amp.`num_of_max_ads` AS 'max_ad_count'
                FROM `users` AS u 
                INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                INNER JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                LEFT JOIN `adverts` AS a ON a.`UID` = u.`UID` AND a.`is_active` = 1 
                WHERE u.`Uemail` = '".$email_address."'
                GROUP BY a.`UID`";
        $data = DB::select($sql);
        $output['uid'] = isset($data[0])?intval($data[0]->UID): 0;
        if(isset($data[0]) && $data[0]->max_ad_count == 'Unlimited') {
            $output['max_ad_count'] = -1;
        } elseif(isset($data[0]) && intval($data[0]->max_ad_count) > 0) {
            $output['max_ad_count'] = intval($data[0]->max_ad_count);
        } else {
            $output['max_ad_count'] = 0;
        }
        $output['use_ad_count'] = isset($data[0])?intval($data[0]->use_ad_count): 0;
        return $output;
    }

}
