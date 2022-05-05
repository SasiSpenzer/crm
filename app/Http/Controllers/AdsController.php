<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Member;
use App\Advertisement;
use Datatables;
use App\Customer;
use App\Package;
use App\Payment; 
use Auth;
use DB;

class AdsController extends Controller
{
    public function viewToBeRemoved($user = false)
    {
    	if ($user == false) {
    		return view('ads.to-be-removed');
    	}else{
    		$user = Customer::where('UID', $user)->select(['UID', 'firstname', 'surname', 'Uemail'])->first();
    		return view('ads.to-be-removed-user', compact('user'));
    	}
    }

    // By Sasi Spenzer 2021-06-10 ** WFH
    public function checkLeads(Request $request){

        $userID = intval($request->input('uid'));
        $year = date("Y");
        $current_month = date('m');
        $lastmonth = $current_month-1;
        //$userID = 372;

        $sqlTotalViews = "SELECT SUM(total_views_section) AS total_views FROM  latest_stats_for_ads WHERE uid =".$userID;
        $userAdsViews = DB::select($sqlTotalViews);

        $sqlTotalLeads = "SELECT SUM(total_leads_section) AS total_leads FROM  latest_stats_for_ads WHERE uid =".$userID;
        $userAdsleads = DB::select($sqlTotalLeads);


        $returnArray = array();
        $returnArray['leads'] = $userAdsleads[0]->total_leads;
        $returnArray['views'] = $userAdsViews[0]->total_views;
//        $month_ago = date('Y-m-d', strtotime('-30 days'));
//        foreach ($userAds as $eachAd){
//            // Get leads in last 30 days
//            $sqlleadsGet = "SELECT COUNT(leads_month_data_id) AS leads FROM `leads_month_data` WHERE  leads_ad_city = '$eachAd->city' AND leads_ad_type = '" . $eachAd->type . "' AND leads_ad_property_type = '" . $eachAd->propty_type. "' AND `created_at` > '$month_ago'";
//            $allUserDataleads = DB::select($sqlleadsGet);
//
//            // Get views in last 30 days
//            $sqlviewsGet = "SELECT SUM(views_count) AS views FROM `views_month_data` WHERE  views_ad_city = '$eachAd->city' AND views_ad_type = '" . $eachAd->type . "' AND views_ad_property_type = '" . $eachAd->propty_type. "' AND `updated_at` > '$month_ago'";
//            $allUserDataViews = DB::select($sqlviewsGet);
//
//            // Adding Leads
//            if(!empty($allUserDataleads[0]->leads)){
//                $returnArray['leads'] += $allUserDataleads[0]->leads;
//            }
//            // Adding Views
//            if(!empty($allUserDataViews[0]->views)){
//                $returnArray['views'] += $allUserDataViews[0]->views;
//            }
//
//
//        }

        return $returnArray;


    }

    public function toBeRemoved($user = false)
    {
    	if ($user == false) {
    		//$today = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d');
            $today = date("Y-m-d");
            $fourteen_days_before = date('Y-m-d', strtotime($today . ' - 14 days'));
            if(isset($data['search']['value']) && $data['search']['value'] != null ) {
                $search_data = $data['search']['value'];
                $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1
                        WHERE (am.`type` != 'Member')  AND `payment` != 'Free' AND 
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'  
                        OR am.`am` LIKE '%".$search_data."%' OR am.`category` LIKE '%".$search_data."%' ) AND (a.`type` != 'Wanted') AND am.`payment_exp_date` < '".$today."' 
                        ORDER BY am.`payment_exp_date` DESC";
            } else {
                $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1 AND (a.`type` != 'Wanted') AND am.`payment_exp_date` < '".$today."' 
                        WHERE (am.`type` != 'Member') AND `payment` != 'Free'
                        ORDER BY am.`payment_exp_date` DESC";
            }
            $customer = DB::select($sql);

			return Datatables::of($customer)->make(true);

    	} else {
	    	$customer = Advertisement::leftjoin(
				'admin_members',
				'admin_members.uid',
				'=',
				'adverts.UID'
			)->select(array('adverts.ad_id AS ad_id',
				'adverts.propty_type AS propty_type',
				'adverts.service_type AS service_type',
				'adverts.heading AS heading',
				'adverts.is_active AS is_active',
				'adverts.type AS type',
				'adverts.submit_date AS submit_date',
				'admin_members.expiry AS expiry',
				'admin_members.payment_exp_date AS payment_exp_date',
				'admin_members.category AS category',
				'admin_members.am AS am',
			))->where('adverts.UID', $user)->where('is_active', 1);

			return Datatables::of($customer)->make(true);
    	}
    }


    public function limitExceed() {
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            /*$sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                    FROM `admin_members` AS am
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    LEFT JOIN `admin_member_packages` AS ap ON ap.`package_name` = am.`category`
                    INNER JOIN (
                            SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ad_count'
                            FROM `adverts` AS a
                            GROUP BY a.`UID`
                        ) AS a ON a.`UID` = am.`uid`
                    
                    WHERE (ap.`num_of_max_ads` < a.`ad_count` AND 
                    ap.`package_name` != 'Ultimate' AND ap.`package_name` != 'Business Ultimate' ) AND 
                    (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'  
                    OR am.`am` LIKE '%".$search_data."%' OR am.`category` LIKE '%".$search_data."%' )
                    ORDER BY am.`payment_exp_date` DESC";*/
            
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                    FROM `admin_members` AS am
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    LEFT JOIN `admin_member_packages` AS ap ON ap.`package_name` = am.`category`                  
                    WHERE ap.`num_of_max_ads` < u.`active_ads_count` AND 
                    ap.`package_name` != 'Ultimate' AND ap.`package_name` != 'Business Ultimate'
                    ORDER BY am.`payment_exp_date` DESC";
        } else {
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                    FROM `admin_members` AS am
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    LEFT JOIN `admin_member_packages` AS ap ON ap.`package_name` = am.`category`                  
                    WHERE ap.`num_of_max_ads` < u.`active_ads_count` AND 
                    ap.`package_name` != 'Ultimate' AND ap.`package_name` != 'Business Ultimate'
                    ORDER BY am.`payment_exp_date` DESC";
        }
        //dd($sql);exit();
        $customer = DB::select($sql);

        return Datatables::of($customer)->make(true);
    }

    public function nullADData() {
        $today = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d');
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1
                        WHERE am.`type` = '' OR (am.`type` IS NULL) OR (`payment_exp_date` IS NULL) AND  
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'  
                        OR am.`am` LIKE '%".$search_data."%' OR am.`category` LIKE '%".$search_data."%' )
                        ORDER BY am.`payment_exp_date` DESC";
        } else {
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1
                        WHERE am.`type` = '' OR (am.`type` IS NULL) OR (`payment_exp_date` IS NULL)
                        ORDER BY am.`payment_exp_date` DESC";
        }
        $customer = DB::select($sql);

        return Datatables::of($customer)->make(true);
    }

    public function appADData() {
        $today = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d');
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1 AND a.`source`= 'APP'
                        WHERE am.`category` = 'Single Ad'  AND (`users`.`firstname` LIKE '%".$search_data."%' OR 
                        `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'  
                        OR am.`am` LIKE '%".$search_data."%' OR am.`category` LIKE '%".$search_data."%' )
                        ORDER BY am.`payment_exp_date` DESC";
        } else {
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1 AND a.`source`= 'APP'
                        WHERE am.`category` = 'Single Ad' 
                        ORDER BY am.`payment_exp_date` DESC";
        }
        $customer = DB::select($sql);

        return Datatables::of($customer)->make(true);
    }

    public function expUpgradeADData() {
        $today = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d');
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1
                        INNER JOIN  ( 
                            SELECT a.`ad_id`
                            FROM `adverts` AS a 
                            LEFT JOIN `upgraded_ads` AS ua ON ua.`ad_id` = a.`ad_id`
                            WHERE  a.`priority` > 0 AND (ua.`ad_id` IS NULL OR ua.`ad_id` = '')
                        ) AS ua ON ua.`ad_id` = a.`ad_id` 
                        WHERE a.`is_active` = 1 AND (`users`.`firstname` LIKE '%".$search_data."%' OR 
                        `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%'  
                        OR am.`am` LIKE '%".$search_data."%' OR am.`category` LIKE '%".$search_data."%' )
                        ORDER BY am.`payment_exp_date` DESC";
        } else {
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`
                        FROM `admin_members` AS am
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` AND a.`is_active` = 1
                        INNER JOIN  ( 
                            SELECT a.`ad_id`
                            FROM `adverts` AS a 
                            LEFT JOIN `upgraded_ads` AS ua ON ua.`ad_id` = a.`ad_id`
                            WHERE  a.`priority` > 0 AND (ua.`ad_id` IS NULL OR ua.`ad_id` = '')
                        ) AS ua ON ua.`ad_id` = a.`ad_id` 
                        WHERE a.`is_active` = 1
                        ORDER BY am.`payment_exp_date` DESC";
        }
        $customer = DB::select($sql);

        return Datatables::of($customer)->make(true);
    }

    public function withoutPayment() {
        $today = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d');
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            /*$sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, u.`expiry`, u.`payment_exp_date`,u.`category`,u.`am`,p.id
                    FROM
                       (
                          SELECT u.`UID` AS 'UID', u.`firstname`, u.`surname`, u.`Uemail`, am.`expiry`, am.`category` , am.`payment_exp_date`, am.`am`
                          FROM admin_members am 
                          LEFT JOIN users u ON am.UID = u.UID 
                          WHERE ( am.category = 'Single Ad' OR am.category = 'Free' OR am.category = 'Free Trial' OR am.category IS NULL ) AND am.type = 'Member' AND am.am = 'Online'
                       ) u 
                       INNER JOIN ( 
                        SELECT a.UID AS 'UID', a.ad_id AS 'ad_id', a.type
                            FROM adverts a 
                            INNER JOIN cities c ON a.city = c.city_name 
                             WHERE a.posted_date > '2019-12-01' AND (c.region = 'Colombo All' OR c.region = 'Western' )
                          ) a ON u.UID = a.UID 
                       LEFT JOIN pp_payments p ON a.ad_id = p.ad_id 
                    WHERE p.id IS NULL
                    
                    UNION ALL
                    
                    SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, u.`expiry`, u.`payment_exp_date`,u.`category`,u.`am`,p.`id`
                    FROM (
                        SELECT u.`UID` AS 'UID', u.`firstname`, u.`surname`, u.`Uemail`, am.`expiry`, am.`category` , am.`payment_exp_date`, am.`am`
                        FROM `admin_members` am
                        LEFT JOIN `users` u ON am.`UID` = u.`UID`
                        WHERE ( am.`category` = 'Single Ad' OR am.`category` = 'Free' OR am.`category` IS NULL) AND am.`type` = 'Member' AND am.`am` = 'Online'
                    ) u 
                    JOIN (
                        SELECT `adverts`.`UID` AS 'UID',`adverts`.`ad_id` AS 'ad_id', `adverts`.`type`
                        FROM `adverts` 
                        WHERE `adverts`.`posted_date` > '2019-12-01' AND (`adverts`.`propty_type` = 'Apartment' OR `adverts`.`propty_type` = 'Commercial' OR `adverts`.`propty_type` = 'Bungalow' OR `adverts`.`propty_type` = 'Villa' OR `adverts`.`propty_type` = 'Other')
                    ) a ON u.`UID` = a.`UID`
                    LEFT JOIN `pp_payments` p ON a.`ad_id` = p.`ad_id`
                    WHERE p.`id` IS NULL  AND 
                    (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%'  OR u.`Uemail` LIKE '%".$search_data."%'  
                    OR u.`am` LIKE '%".$search_data."%' OR u.`category` LIKE '%".$search_data."%' )";*/
            
            $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`,am.id,a.propty_type,c.region FROM `admin_members` AS am 
INNER JOIN `users` AS u ON u.`UID` = am.`uid` 
INNER JOIN `adverts` AS a ON a.`UID` = am.`uid`
INNER JOIN cities c ON a.city = c.city_name
WHERE a.`is_active` = 1 AND a.propty_type != 'Room' AND a.propty_type !='Annexe' AND am.`category` = 'Single Ad' AND (a.`type` != 'Wanted') AND (a.`type` != 'agents') AND am.`type` = 'Member' AND (am.payment = 'Free' OR am.payment = 'Pending') AND ((a.propty_type = 'Bare Land' AND c.region = 'Central') OR (a.propty_type = 'Bare Land' AND c.region = 'North West') OR (a.propty_type = 'Bare Land' AND c.region = 'Western') OR (a.propty_type = 'Bare Land' AND c.region = 'Western')  OR (a.propty_type = 'Bare Land' AND c.region = 'Southern') OR (a.propty_type = 'Bare Land' AND c.region = 'Colombo All') OR a.propty_type = 'Apartment' OR a.propty_type = 'Bungalow' OR a.propty_type = 'Commercial' OR a.propty_type = 'Villa' OR (a.propty_type = 'House' AND c.region = 'Central') OR (a.propty_type = 'House' AND c.region = 'North West') OR (a.propty_type = 'House' AND c.region = 'Western') OR (a.propty_type = 'House' AND c.region = 'Southern') OR (a.propty_type = 'Land with house' AND c.region = 'Western') OR (a.propty_type = 'Land with house' AND c.region = 'Central')  OR (a.propty_type = 'Land with house' AND c.region = 'North West')  OR (a.propty_type = 'Land with house' AND c.region = 'North West') OR (a.type = 'land' AND c.region = 'Central') OR (a.type = 'land' AND c.region = 'Western') OR (a.type = 'land' AND c.region = 'Southern' AND a.propty_type != 'Land with house') OR (a.type = 'land' AND c.region = 'North West'))
ORDER BY `a`.`propty_type` ASC";
            
        } else {
                        $sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`,am.id,a.propty_type,c.region FROM `admin_members` AS am 
INNER JOIN `users` AS u ON u.`UID` = am.`uid` 
INNER JOIN `adverts` AS a ON a.`UID` = am.`uid`
INNER JOIN cities c ON a.city = c.city_name
WHERE a.`is_active` = 1 AND a.propty_type != 'Room' AND a.propty_type !='Annexe' AND am.`category` = 'Single Ad' AND (a.`type` != 'Wanted') AND (a.`type` != 'agents') AND am.`type` = 'Member' AND (am.payment = 'Free' OR am.payment = 'Pending') AND ((a.propty_type = 'Bare Land' AND c.region = 'Central') OR (a.propty_type = 'Bare Land' AND c.region = 'North West') OR (a.propty_type = 'Bare Land' AND c.region = 'Western') OR (a.propty_type = 'Bare Land' AND c.region = 'Western')  OR (a.propty_type = 'Bare Land' AND c.region = 'Southern') OR (a.propty_type = 'Bare Land' AND c.region = 'Colombo All') OR a.propty_type = 'Apartment' OR a.propty_type = 'Bungalow' OR a.propty_type = 'Commercial' OR a.propty_type = 'Villa' OR (a.propty_type = 'House' AND c.region = 'Central') OR (a.propty_type = 'House' AND c.region = 'North West') OR (a.propty_type = 'House' AND c.region = 'Western') OR (a.propty_type = 'House' AND c.region = 'Southern') OR (a.propty_type = 'Land with house' AND c.region = 'Western') OR (a.propty_type = 'Land with house' AND c.region = 'Central')  OR (a.propty_type = 'Land with house' AND c.region = 'North West')  OR (a.propty_type = 'Land with house' AND c.region = 'North West') OR (a.type = 'land' AND c.region = 'Central') OR (a.type = 'land' AND c.region = 'Western') OR (a.type = 'land' AND c.region = 'Southern' AND a.propty_type != 'Land with house') OR (a.type = 'land' AND c.region = 'North West'))
ORDER BY `a`.`propty_type` ASC";
            //$sql = "SELECT DISTINCT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`Uemail`, am.`expiry`, am.`payment_exp_date`,am.`category`,am.`am`,am.id FROM `admin_members` AS am INNER JOIN `users` AS u ON u.`UID` = am.`uid` INNER JOIN `adverts` AS a ON a.`UID` = am.`uid` WHERE a.`is_active` = 1 AND am.`category` = 'Single Ad' AND (a.`type` != 'Wanted') AND am.`type` != 'Member' AND am.`payment` != 'Free' ORDER BY am.`payment_exp_date` DESC";
        }
        $customer = DB::select($sql);

        return Datatables::of($customer)->make(true);
    }

    public function viewSummary()
    {
    	/*$package = Package::all();
    	$members = Member::all();*/
        $sql = "SELECT am.`category`, IF(COUNT(am.`id`) IS NULL, 0, IF(COUNT(am.`id`) != '', COUNT(am.`id`), 0))   AS 'member_count', 
                0 'invoiced_count', IF(eam.expired_count IS NULL, 0, eam.expired_count) AS 'expired_count'
                FROM `admin_members` AS am 
                LEFT JOIN (
                    SELECT am.`category`, IF(COUNT(am.`id`) IS NULL, 0, IF(COUNT(am.`id`) != '', COUNT(am.`id`), 0)) AS 'expired_count'
                    FROM `admin_members` AS am 
                    WHERE am.`type` != 'Member'
                    GROUP BY am.`category`
                ) eam ON eam.`category` = am.`category`
                WHERE am.`type` = 'Member'
                GROUP BY am.`category`";
        $customer['data'] = DB::select($sql);
    	return view('ads.summary', $customer);
    }

    public function activateCommercial()
	{
	    $admin_level = Auth::user()->admin_level;
		return view('ads.commercial_pending_payment',compact('admin_level'));
	}

	public function activeAds(){
        $admin_level = Auth::user()->admin_level;
        return view('ads.active_ads',compact('admin_level'));
    }

	public function postActivateCommercial()
	{
	    $admin_level = Auth::user()->admin_level;
	    if ($admin_level > 2) {
            if (request()->has('ad_id')) {
                $s_ad = Advertisement::leftjoin('users', 'adverts.UID', '=', 'users.UID')->where('ad_id', request('ad_id'))->first();
            } elseif (request()->has('s_ad_id')) {

                if (!Auth::check())
                    return redirect('/login');

                Payment::create(['IPN_ID' => '',
                    'ad_id' => request('s_ad_id'),
                    'by_name' => Auth::user()->username,
                    'user_id' => request('uid'),
                    'paid_date' => Carbon::parse(request('paid'))->format('Y-m-d'),
                    'exp_date' => Carbon::parse(request('expire'))->format('Y-m-d'),
                    'paid_amount' => request('amount'),
                    'payment_status' => "Completed",
                    'payer_status' => "verified",
                    'ad_status' => "active"
                ]);

                Advertisement::where('ad_id', request('s_ad_id'))->update(['blocked' => 'N', 'is_active' => 1]);

                session()->flash('msg', 'The Ad has been activated successfully!');
                $s_ad = null;

            } else {
                $s_ad = null;
            }

            if (!Auth::check())
                return redirect('/login');

            return view('ads.commercial_pending_payment', compact('s_ad'));
        } else {
            $s_ad = null;
            session()->flash("error_msg", "You haven't permission to active ads!");
            return view('ads.commercial_pending_payment', compact('s_ad'));
        }
	}


    /**
     * For member dashboard data store
     * @return false|string|void
     */
    public function memberDashboardStoreData()
    {
        $output_array = array();
        try {
            //truncate old data
            DB::table('member_dashboard_log_data')->truncate();

            date_default_timezone_set('Asia/Colombo');
            $plusseven = date("Y-m-d", mktime(12, 29, 59, date("m"), date("d") + 7, date("Y")));
            $minusseven = date("Y-m-d h:i:s", mktime(12, 29, 59, date("m"), date("d") - 7, date("Y")));
            $month_b_lastmonth = date("Y-m", mktime(12, 29, 59, date("m") - 2, date("d"), date("Y")));
            $last_month = date("Y-m", mktime(12, 29, 59, date("m") - 1, date("d"), date("Y")));
            $date = date("Y-m");
            $today = date("Y-m-d h:i:s");

            //For get basic data
            $sql = "SELECT u.`UID`, u.`Uemail`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', u.`ads_count`, 
                        ams.`category`, ams.`member_since`, ams.`expiry` AS 'membership_exp_date', ams.`payment_exp_date`, 
                        ams.`am` 
                    FROM `admin_members` AS ams
                    inner join `users` AS u on u.`uid` = ams.`UID`
                    WHERE  ams.`type` = 'Member'
                    ORDER BY ams.`expiry` DESC 
                    /*LIMIT 10*/"; // LIMIT 10

            $member_data = DB::select($sql);

            $output = array();
            foreach ($member_data AS $pro => $value) {
                $uid = intval($value->UID);
                $email = $value->Uemail;
                $sql_ads_count = "SELECT COUNT(a.`ad_id`) AS 'ads_count'
                                    FROM `adverts` AS a 
                                    WHERE a.`UID` = ".$uid." AND a.`is_active` = 1 
                                    GROUP BY a.`UID`";
                $result_ads_count = DB::select($sql_ads_count);
                $ads_count = isset($result_ads_count[0]) ? intval($result_ads_count[0]->ads_count) : 0;

                $output[$pro] = $value;
                //For get max page id
                $sql1 = "SELECT SQL_CACHE MAX(a.`current_page_id`) as 'max_page_id' 
                        FROM `adverts` AS a
                        WHERE a.`UID` = ".$uid."
                        ORDER BY a.`UID` DESC";
                $max_page_data = DB::select($sql1);
                $output[$pro]->max_page_id = isset($max_page_data[0]) ? intval($max_page_data[0]->max_page_id) : 0;

                //For get ads percentage with images
                $sql2 = "SELECT SQL_CACHE COUNT(a.`ad_id`) as 'pics_count' 
                        FROM `adverts` AS a
                        WHERE a.`UID` = ".$uid." AND a.`pic` ='Y'
                        ORDER BY a.`UID` DESC";
                $pics_count_data = DB::select($sql2);
                $ads_count_with_pics = isset($pics_count_data[0]) ? intval($pics_count_data[0]->pics_count) : 0;
                if ($ads_count > 0) {
                    $pic_percentage = ($ads_count_with_pics / $ads_count) * 100;
                } else {
                    $pic_percentage = 0;
                }
                $ads_percentage_round = number_format((float)$pic_percentage, 2, '.', '') . "%";
                $output[$pro]->pic_percentage = $ads_percentage_round;

                //For get upgrade ads count
                $sql3 = "SELECT COUNT(ua.`up_id`) AS 'upgraded_ads_count'
                        FROM `upgraded_ads` AS ua 
                        INNER JOIN `adverts` AS a ON a.`ad_id` = ua.`ad_id` 
                        WHERE a.`UID` = ".$uid." AND a.`priority` > 0
                        GROUP BY a.`UID`";

                $upgraded_ads_count_data = DB::select($sql3);
                $upgraded_ads_count = isset($result_upgrade_count[0]) ? intval($upgraded_ads_count_data[0]->upgraded_ads_count) : 0;
                $output[$pro]->ad_upgrade_count = $upgraded_ads_count;

                //For get ads boosts
                $sql4 = "SELECT SQL_CACHE ca.`avail_balance`, cau.`last_upgraded_by` 
                        FROM `credit_account` ca
                        left join `credit_account_upgrade` cau ON cau.`acc_id` = cau.`acc_id`
                        WHERE ca.`UID` = $uid 
                        ORDER BY ca.`UID` DESC 
                        LIMIT 1";
                $row_boosts_data = DB::select($sql4);
                $avail_balance = isset($row_boosts_data[0]) ? intval($row_boosts_data[0]->avail_balance) : 0;

                $sql5 = "SELECT SQL_CACHE COUNT(cpl.`log_id`) as 'boosts' 
                        FROM `credit_acc_payment_log` AS cpl
                        WHERE cpl.`UID` = $uid AND cpl.`txn_date` < '$today' AND cpl.`txn_date` > '$minusseven'";
                $row_boosts_per_week_data = DB::select($sql5);
                $boosts = isset($row_boosts_per_week_data[0]) ? intval($row_boosts_per_week_data[0]->boosts) : 0;
                $output[$pro]->boosts_left = $avail_balance . "(" . $boosts . ")";

                //For calculate view stats & leeds stats data
                /*$sql6 = "SELECT SQL_CACHE SUM(cs.`tel_contacts`) as 'tel', SUM(cs.`email_contacts`) as 'email', SUM(cs.`sms_contacts`) as 'sms'
                        FROM `contact_stats` AS cs
                        WHERE cs.`UID` = $uid 
                        ORDER BY cs.`UID`
                        LIMIT 1";*/
                $row_stats_data = $this->getLeadsDataCount($date);

                /*$tel_stats = isset($row_stats_data[0]) ? intval($row_stats_data[0]->tel) : 0;
                $email_stats = isset($row_stats_data[0]) ? intval($row_stats_data[0]->email) : 0;
                $sms_stats = isset($row_stats_data[0]) ? intval($row_stats_data[0]->sms) : 0;
                $total_stats = $tel_stats + $email_stats + $sms_stats;*/

                $total_stats = $row_stats_data['email_contacts'] + $row_stats_data['call_contacts'] + $row_stats_data['sms_contacts'] + $row_stats_data['whatsapp_contacts'];
                $output[$pro]->total_stats = $total_stats;
                //dd($row_stats_data, $total_stats);

                /*$sql7 = "SELECT SQL_CACHE SUM(ams.`total_views_per_month`) as 'l_views' ,SUM(ams.`total_leads_per_month`) as 'l_leads'
                        FROM `admin_members_stats` AS ams
                        WHERE ams.`UID` = $uid and ams.`date` = '" . $month_b_lastmonth . "'";
                $row_last_month_data = DB::select($sql7);
                $l_views = isset($row_last_month_data[0]) ? intval($row_last_month_data[0]->l_views) : 0;
                $l_leads = isset($row_last_month_data[0]) ? intval($row_last_month_data[0]->l_leads) : 0;*/
                $last_two_month_leads_data = $this->getLeadsDataCount($month_b_lastmonth);
                $l_leads = $last_two_month_leads_data['email_contacts'] + $last_two_month_leads_data['call_contacts'] +
                                $last_two_month_leads_data['sms_contacts'] + $last_two_month_leads_data['whatsapp_contacts'];

                /*$sql8 = "SELECT SQL_CACHE SUM(ams.`total_views_per_month`) as 't_views', SUM(ams.`total_leads_per_month`) as 't_leads'
                        FROM `admin_members_stats` AS ams 
                        WHERE ams.`UID` = $uid and ams.`date` = '" . $last_month . "'";
                $row_this_month_data = DB::select($sql8);
                $t_views = isset($row_last_month_data[0]) ? intval($row_this_month_data[0]->t_views) : 0;
                $t_leads = isset($row_last_month_data[0]) ? intval($row_this_month_data[0]->t_leads) : 0;*/
                $last_month_leads_data = $this->getLeadsDataCount($last_month);
                $t_leads = intval($last_month_leads_data['email_contacts']) + intval($last_month_leads_data['call_contacts']) +
                    intval($last_month_leads_data['sms_contacts']) + intval($last_month_leads_data['whatsapp_contacts']);

                $month = intval(date('m')) - 1;
                if($month > 0) {
                    $month = 12;
                    $year = intval(date('Y'));
                } else {
                    $year = intval(date('Y')) - 1;
                }
                $t_views = $this->getViewsDataCount($year, $month);

                $month = intval(date('m')) - 2;
                if($month > 0) {
                    $year = intval(date('Y'));
                } else if($month == -1) {
                    $month = 12;
                    $year = intval(date('Y') - 1);
                } else {
                    $month = 11;
                    $year = intval(date('Y')) - 1;
                }
                $l_views = $this->getViewsDataCount($year, $month);

                $diff_views = $t_views - $l_views;
                if ($l_views > 0) {
                    $views_percentage = ($diff_views / $l_views) * 100;
                } else {
                    $views_percentage = 0;
                }
                $views_percentage = number_format((float)$views_percentage, 0, '.', '');
                $output[$pro]->views_percentage = $views_percentage;
                $diff_leads = $t_leads - $l_leads;

                if ($l_views > 0) {
                    $leads_percentage = ($diff_leads / $l_leads) * 100;
                } else {
                    $leads_percentage = 0;
                }
                $leads_percentage = number_format((float)$leads_percentage, 0, '.', '');
                $output[$pro]->leads_percentage = $leads_percentage;

                //Get ads hits data
                /*$sql9 = "SELECT SQL_CACHE SUM(ah.`views_current`) as 'views'
                        FROM `ad_hits` AS ah
                        WHERE ah.`UID` = $uid 
                        ORDER BY ah.`UID` DESC";
                $row_ad_hits_data = DB::select($sql9);
                $ad_hits = isset($row_ad_hits_data[0]) ? intval($row_ad_hits_data[0]->views) : 0;*/
                $year = intval(date('Y'));
                $month = intval(date('m'));
                $ad_hits = $this->getViewsDataCount($year, $month);

                $output[$pro]->ad_hits = $ad_hits;
                if ($diff_views > 0) {
                    $output[$pro]->status_img = '<img src=\'images/up-arrow-1.png\'>';
                    $output[$pro]->status = 1;
                } elseif ($diff_views < 0) {
                    $output[$pro]->status = 2;
                    $output[$pro]->status_img = '<img src=\'images/down-arrow-1.png\'>';
                } else {
                    $output[$pro]->status = 0;
                    $output[$pro]->status_img = '<img src=\'images/trans-equal-1.png\'>';
                }

                if ($diff_leads > 0) {
                    $status_leads = "Increase";
                } elseif ($diff_leads < 0) {
                    $status_leads = "Decrease";
                } else {
                    $status_leads = "Equal";
                }

                if ($output[$pro]->membership_exp_date < $date) {
                    $output[$pro]->class = 'data-row-a';
                } else if ($output[$pro]->payment_exp_date < $date) {
                    $output[$pro]->class = 'data-row-b';
                } else if (($output[$pro]->membership_exp_date < $plusseven) || ($output[$pro]->payment_exp_date < $plusseven)) {
                    $output[$pro]->class = 'data-row-c';
                } else {
                    $output[$pro]->class = '';
                }
                if(isset($output[$pro]->ads_count) && $output[$pro]->ads_count != '' && $output[$pro]->ads_count != null) {
                    $output[$pro]->ads_count = intval($output[$pro]->ads_count);
                } else {
                    $output[$pro]->ads_count = 0;
                }
                DB::table('member_dashboard_log_data')->insert([
                    [
                        "UID" => $output[$pro]->UID,
                        "Uemail" => $output[$pro]->Uemail,
                        "username" => $output[$pro]->username,
                        "ads_count" => $output[$pro]->ads_count,
                        "category" => $output[$pro]->category,
                        "member_since" => $output[$pro]->member_since,
                        "membership_exp_date" => $output[$pro]->membership_exp_date,
                        "payment_exp_date" => $output[$pro]->payment_exp_date,
                        "am" => $output[$pro]->am,
                        "max_page_id" => intval($output[$pro]->max_page_id),
                        "pic_percentage" => $output[$pro]->pic_percentage,
                        "ad_upgrade_count" => intval($output[$pro]->ad_upgrade_count),
                        "boosts_left" => $output[$pro]->boosts_left,
                        "total_stats" => intval($output[$pro]->total_stats),
                        "views_percentage" => $output[$pro]->views_percentage,
                        "leads_percentage" => $output[$pro]->leads_percentage,
                        "ad_hits" => intval($output[$pro]->ad_hits),
                        "status" => intval($output[$pro]->status),
                        "status_img" => $output[$pro]->status_img,
                        "class" => $output[$pro]->class,
                        "created_at" => $today,
                        "updated_at" => $today,
                    ]
                ]);
            }
            $output_array["status"] = "Succeed";
            $output_array["description"] = "Membership dashboard log data updated successfully.";
        } catch( \Exception $e) {
            //dd($e);
            $output_array["status"] = "Failed";
            $output_array["description"] = $e;
        }
        return json_encode($output_array);
    }

    /**
     * For remove member expire am after 6 month
     * @return false|string|void
     */
    public function memberExpireRemoveAm() {
        try {
            date_default_timezone_set('Asia/Colombo');
            $before_six_month = date("Y-m-d", strtotime("-6 month"));

            DB::table('admin_members AS am')
                ->join('users AS u', 'u.UID', '=', 'am.uid')
                ->where( 'expiry', '<', $before_six_month)
                ->where('u.user_type', '=', 'O')
                ->update(['am.am' => null]);

            DB::table('admin_members AS am')
                ->join('users AS u', 'u.UID', '=', 'am.uid')
                ->where( 'expiry', '<', $before_six_month)
                ->where('u.user_type', '=', 'P')
                ->update(['am.am' => null]);

            $output_array["status"] = "Succeed";
            $output_array["description"] = "Membership expired data update successfully.";

        } catch( \Exception $e) {
            $output_array["status"] = "Failed";
            $output_array["description"] = $e;
        }
        return json_encode($output_array);
    }

    public function customerPayment($rand_string1,$customer_id,$rand_string2)
    {
        return view('member.customer_payment');
    }

    /**
     * Use offer type data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function offerType()
    {
        $admin_level = Auth::user()->admin_level;
        return view('ads.offer_type',compact('admin_level'));
    }

    public function offerTypeData()
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $admin_level = Auth::user()->admin_level;
            if ($admin_level > 2) {
                if (request()->has('ad_id')) {
                    $s_ad = Advertisement::leftjoin('users', 'adverts.UID', '=', 'users.UID')->where('ad_id', request('ad_id'))->first();
                    $sql = "SELECT DISTINCT a.`type`
                            FROM `adverts` AS a";
                    $ads_type = DB::select($sql);
                    $s_ad->ads_type = $ads_type;
                }
                elseif (request()->has('s_ad_id')) {
                    if (!Auth::check()) {
                        return redirect('/login');
                    }
                    $old_ads_type = request('old_type');
                    $ads_type = request('ads_type');
                    $owner_uid = intval(request('uid'));
                    $ad_id = intval(request('s_ad_id'));
                    $user_id = intval(Auth::user()->id);
                    $user_name = Auth::user()->name;
                    $date_time = date("Y-m-d H:i:s");
                    $change_description = "ads type change ".$old_ads_type." to ".$ads_type." (Ad id : ".$ad_id.") by ".$user_name;
                    if($old_ads_type != $ads_type) {
                        Advertisement::where('ad_id', $ad_id)->update(['type' => $ads_type]);
                        $sql1 = "INSERT INTO `ads_type_log` (`changer_user_id`,`changing_description`,`ads_owner_user_id`,`created_at`,`updated_at`)
                                VALUES(".$user_id.", '".$change_description."',".$owner_uid.", '".$date_time."', '".$date_time."')";
                        DB::statement($sql1);
                        session()->flash('msg', 'The Ad type changed successfully!');
                    } else {
                        session()->flash('msg', 'Same ads type selected!');
                    }
                    $s_ad = null;
                } else {
                    $s_ad = null;
                }
                if (!Auth::check()) {
                    return redirect('/login');
                }
                return view('ads.offer_type', compact('s_ad'));
            } else {
                $s_ad = null;
                session()->flash("error_msg", "You haven't permission to change ads type!");
                return view('ads.offer_type', compact('s_ad'));
            }
        } catch (\Exception $e) {
            $s_ad = null;
            session()->flash("error_msg", "Have error. Please try again later!");
            return view('ads.offer_type', compact('s_ad'));
        }
    }

    public function getLeadsDataCount($date) {
        $sql = "SELECT lt.`leads_type`, COUNT(ld.`leads_month_data_id`) AS 'leads_count'
                FROM `leads_month_data` AS ld 
                INNER JOIN `leads_type` AS lt ON lt.`leads_type_id` = ld.`leads_type_id` 
                WHERE ld.`created_at` LIKE '".$date."%'
                GROUP BY ld.`leads_type_id`";
        $row_stats_data = DB::select($sql);

        $output['email_contacts'] = 0;
        $output['call_contacts'] = 0;
        $output['sms_contacts'] = 0;
        $output['whatsapp_contacts'] = 0;
        foreach($row_stats_data AS $contacts_data){
            if($contacts_data->leads_type == 'Email'){
                $output['email_contacts'] = intval($contacts_data->leads_count);
            } else if($contacts_data->leads_type == 'Call'){
                $output['call_contacts'] = intval($contacts_data->leads_count);
            } else if($contacts_data->leads_type == 'SMS'){
                $output['sms_contacts'] = intval($contacts_data->leads_count);
            } else if($contacts_data->leads_type == 'Whats App'){
                $output['whatsapp_contacts'] = intval($contacts_data->leads_count);
            }
        }
        return $output;
    }

    public function getViewsDataCount($year, $month) {
        $sql = "SELECT vmd.`year`, vmd.`month`, SUM(vmd.`views_count`) AS 'ad_views_count'
                FROM `views_month_data` AS vmd
                WHERE vmd.`year` = ".$year." AND vmd.`month` = ". $month;
        $views_count_data = DB::select($sql);
        $views_count = 0;
        foreach($views_count_data AS $count_data){
            $views_count = intval($count_data->ad_views_count);
        }
        return $views_count;
    }
}
