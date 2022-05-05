<?php

namespace App\Repositories;

use App\Contracts\CustomerInterface;
use App\Customer;
use App\MemberAction;
use Auth;
use Datatables;
use App\User;
use Illuminate\Support\Facades\DB;

class EloquentCustomerRepository implements CustomerInterface {

	/**
	 * Create a new repository instance.
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get member and user details by user ID
	 * @param  int $userId User ID
	 * @return json object
	 */
	function getByUID($userId) {
        $admin_level = Auth::user()->admin_level;
		$member = Customer::leftjoin(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)->leftjoin(
            'member_dashboard_log_data',
            'users.UID',
            '=',
            'member_dashboard_log_data.UID'
        )->leftjoin(
            'user_type',
            'user_type.user_type_id',
            '=',
            'admin_members.user_type_id'
        )->leftjoin(
            'membership_latest_data',
            'membership_latest_data.member_user_id',
            '=',
            'admin_members.uid'
        )->select(array(
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.UID AS UID',
			'users.Uemail AS Uemail',
			'users.logo_path AS logo_path',
			'users.ads_count AS active_ads_count',
			'admin_members.user_type_id AS user_type_id',
			'user_type.user_type AS user_type',
			'admin_members.type AS type',
			'admin_members.category AS category',
			'admin_members.duration AS duration',
			'admin_members.payment AS payment',
			'admin_members.payment_exp_date AS payment_exp_date',
			'admin_members.am AS am',
			'admin_members.source AS source',
			'admin_members.mobile_nos AS mobile_nos',
			'admin_members.company AS company',
			'admin_members.is_phone_restriction AS is_phone_restriction',
			'admin_members.is_active_auto_boost AS is_active_auto_boost',
			'admin_members.auto_boost_for_new_ads AS auto_boost_for_new_ads',
			//'admin_members.active_ads AS active_ads',
			//'admin_members.leads AS leads',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.remarks AS remarks',
			'admin_members.expiry AS expiry',
			'admin_members.custom_amount AS custom_amount',
			'admin_members.package_amount AS package_amount',
			'admin_members.payment_status',
            'users.linkin_id AS linkin_id',
            'users.logo_path AS logo_path',
            'admin_members.company AS company',
            'admin_members.latest_comment AS latest_comment',
            'admin_members.pending_amount AS pending_amount',
            'users.active_ads_count AS ads_count',
            'member_dashboard_log_data.member_since',
            'member_dashboard_log_data.max_page_id',
            'member_dashboard_log_data.pic_percentage',
            'member_dashboard_log_data.ad_upgrade_count',
            'member_dashboard_log_data.boosts_left',
            'member_dashboard_log_data.total_stats',
            'member_dashboard_log_data.views_percentage',
            'member_dashboard_log_data.leads_percentage',
            'member_dashboard_log_data.ad_hits',
            'member_dashboard_log_data.status',
            'member_dashboard_log_data.status_img',
            'member_dashboard_log_data.class',
            'membership_latest_data.membership_type AS payment_membership_type',
            'membership_latest_data.membership_duration AS payment_membership_duration',
            'membership_latest_data.payment_expire_data',
            'membership_latest_data.membership_expire_data',
            'membership_latest_data.membership_amount',
            'membership_latest_data.membership_pending_amount',
            'membership_latest_data.is_expire',
		))->where('users.UID', '=', $userId)->first();



		if (!empty($member)) {
			$member = $member->toArray();
		} else {
			$member = Customer::select(array(
				'users.firstname AS firstname',
				'users.surname AS surname',
				'users.UID AS UID'))->where('users.UID', '=', $userId)->first()->toArray();

		}

		//For get boosts data
        $boost_data = \DB::table('credit_account')
            ->select(array('credit_account.UID', 'credit_account.avail_balance', 'credit_account.last_updated'))
            ->groupBy('credit_account.UID')
            ->having('credit_account.UID', '=', $userId)
            ->orderBy('credit_account.last_updated', 'desc')
            ->limit(1)
            ->get()->toArray();
		if (isset($boost_data[0])) {
            $member['available_boosts'] = $boost_data[0]->avail_balance;
            $member['last_boost_date'] = $boost_data[0]->last_updated;
        } else {
            $member['available_boosts'] = 0;
            $member['last_boost_date'] = '-';
        }


		//For get membership status & membership remaining days count
        $day_remaining = 0;
		$status = 0;
        if(isset($member['payment_exp_date']) &&$member['payment_exp_date'] != '' && $member['payment_exp_date'] != null) {
            //default time zone set sri lanka colombo
            date_default_timezone_set('Asia/Colombo');
            $today = time();
            $payment_exp_date = strtotime($member['payment_exp_date']);
            $day_remaining = intval(($payment_exp_date - $today)/(60*60*24));
            if($day_remaining > 0){
                $status = 1;
            }

        }
        $member['status'] = $status;
        $member['membership_remaining'] = $day_remaining;

		$am = \App\User::select('username')->whereIn('admin_level', [1, 2])
						->get()->pluck('username')->toArray();
		//if (in_array(Auth::user()->name, \Config::get('membership.am'))) {
		if (in_array(Auth::user()->name, $am)) {
			$member['username'] = Auth::user()->name;
		} else {
			$member['username'] = "";
		}

		$leadsArr = \DB::table('contact_stats')
			->select(\DB::raw('contact_stats.UID,(SUM(ifnull(contact_stats.tel_contacts,0))+SUM(ifnull(contact_stats.email_contacts,0))) as leads'))
			->groupBy('contact_stats.UID')
			->having('contact_stats.UID', '=', $userId)
			->get()->toArray();

		if (!empty($leadsArr)) {
			foreach ($leadsArr as $leads) {
				$member['leads'] = $leads->leads;
			}
		} else {
			$member['leads'] = 0;
		}

		$memberActions = MemberAction::where('uid', '=', $userId)->orderBy('date_time', 'desc')->get();

		if (!empty($memberActions)) {
			$count = 0;
			foreach ($memberActions as $memberAction) {
				$member['memberAction']['action'][$count] = $memberAction->action;
				$member['memberAction']['qty'][$count] = $memberAction->qty;
				$member['memberAction']['value'][$count] = $memberAction->value;
				$member['memberAction']['comments'][$count] = $memberAction->comments;
				$member['memberAction']['date_time'][$count] = $memberAction->date_time;
				$member['memberAction']['by'][$count] = $memberAction->by;
				$member['memberAction']['reminder'][$count] = $memberAction->reminder;
				$count++;
			}
		}
		$member['adminLevel'] = $admin_level;

		//For get pending approval count
        $pending_approval = \DB::table('membership_details_log')
                                    ->select(\DB::raw('COUNT(membership_details_log.membership_details_log_id) AS pending_approval_count'))
                                    ->where('membership_details_log.member_user_id', '=', $userId)
                                    ->where('membership_details_log.is_expire', '=', 0)
                                    ->where('membership_details_log.is_approve', '=', 0)
                                    ->where('membership_details_log.is_only_generate_url', '=', 0)
                                    ->get()
                                    ->toArray();
		$member['pending_approval_count'] = isset($pending_approval[0])?intval($pending_approval[0]->pending_approval_count):0;

		return \Response::json($member);

	}

	/**
	 * Get customer and memberships datatable json
	 * @return JSON Datatable json object
	 */
	/*function listCustomerMemberships($searching) {
		$customer = Customer::leftjoin(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)
		//->leftjoin(
		//	'admin_members_actions',
		//	'users.UID',
		//	'=',
		//	'admin_members_actions.uid'
		->select(array(
			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			//'users.last_updated_at AS last_updated_at',
			'admin_members.updated_at AS last_updated_at',
			'users.UID AS UID',
			'admin_members.expiry AS expiry',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.category AS category',
			'admin_members.am AS am',
			//'admin_members_actions.date_time AS last_updated_at',

		));

		if(!$searching)
			$customer = $customer->where('ads_count', '>', 0);

		return Datatables::of($customer)
			->make(true);

	}*/

	function listCustomerMemberships($type,$searching, $search_data) {

        $year = intval(date('Y'));
	    $month = intval(date('m')) - 1;
	    $day = intval(date('d'));
        $today = date('Y-m-d');
        $timestamp_before_2_month = strtotime('-1 month');
        $date_before_2_month = date("Y-m-d",$timestamp_before_2_month);
	    $today = date('Y-m-d');
        $timestamp_before_2_weeks = strtotime('-2 weeks');
        $date_before_2_weeks= date("Y-m-d",$timestamp_before_2_weeks);
        $sql = '';
		if($type == 0 && (!$searching)) {
            $sql = "SELECT DISTINCT u.`active_ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                    am.`uid` AS 'admin_uid',DATE(am.`updated_at`) AS 'last_updated_at',am.`expiry` AS 'expiry',am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                    am.`category` AS 'category',am.`am` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `member_dashboard_log_data` AS mdl ON mdl.`UID` = am.`uid`
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am` 
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE (am.`type` = 'Member' OR am.`payment_exp_date` >= '" . $date_before_2_month . "') AND (am.`category` IS NOT NULL) AND am.`category` != '' AND 
                    am.`category` != 'Single Ad' AND am.`category` != 'Free' AND am.`category` != 'Trial'";
        }else if($type == 1) {
            $sql = "SELECT DISTINCT u.`active_ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                    am.`uid` AS 'admin_uid',am.`updated_at` AS 'last_updated_at',am.`expiry` AS 'expiry',am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                    am.`category` AS 'category',am.`am` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `member_dashboard_log_data` AS mdl ON mdl.`UID` = am.`uid`
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE am.`type` = 'Member' AND  am.`payment_exp_date` <= '" . $today . "' AND am.`payment_exp_date` >= '" . $date_before_2_weeks . "' ";
        }else if($type == 2) {
            $sql = "SELECT DISTINCT u.`active_ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                    am.`uid` AS 'admin_uid',am.`updated_at` AS 'last_updated_at',am.`expiry` AS 'expiry',am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                    am.`category` AS 'category',am.`am` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `member_dashboard_log_data` AS mdl ON mdl.`UID` = am.`uid`
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE am.`type` != 'Member' AND  am.`payment_exp_date` <= '" . $today . "' AND am.`payment_exp_date` >= '" . $date_before_2_month . "' AND am.`category` != 'Single Ad' AND am.`category` != 'Free'";
        }else if($type == 3) {
            $sql = "SELECT DISTINCT u.`active_ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                    am.`uid` AS 'admin_uid',am.`updated_at` AS 'last_updated_at',am.`expiry` AS 'expiry',am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                    am.`category` AS 'category',am.`am` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `member_dashboard_log_data` AS mdl ON mdl.`UID` = am.`uid`
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE am.`type` != 'Member' AND am.`payment_exp_date` < '" . $date_before_2_month . "' AND 
                    am.payment_status != 5 AND am.payment_status != 6 AND  am.payment_status != 7 AND am.payment_status != 8 AND
                    am.`category` != 'Single Ad' AND am.`category` != 'Free'";
        }else if($type == 4) {
            $sql = "SELECT DISTINCT u.`active_ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                    am.`uid` AS 'admin_uid',am.`updated_at` AS 'last_updated_at',am.`expiry` AS 'expiry',am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                    am.`category` AS 'category',am.`am` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `member_dashboard_log_data` AS mdl ON mdl.`UID` = am.`uid`
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE (am.`am` = 'Online' OR am.`am` = '' OR am.`am` = NULL ) AND am.`category` != 'Single Ad' AND am.`category` != '' AND (am.`category` IS NOT NULL) AND am.`category` != 'Free' AND am.`category` != 'Trial' ";
        }
        if($search_data != '' && $searching) {
            if($type == 0) {
                //$customer = $customer->where('users.Uemail' , 'LIKE', '%'.$search_data.'%');
                $sql = "SELECT DISTINCT u.`active_ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                        am.`uid` AS 'admin_uid',DATE(am.`updated_at`) AS 'last_updated_at',am.`expiry` AS 'expiry',am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                        am.`category` AS 'category',am.`am` As 'am'
                        FROM `admin_members` AS am 
                        LEFT JOIN `member_dashboard_log_data` AS mdl ON mdl.`UID` = am.`uid`
                        LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                        WHERE ((u.`Uemail` LIKE '%" . $search_data . "%' ) OR (u.`firstname` LIKE '%" . $search_data . "%' ) OR  (u.`surname` LIKE '%" . $search_data . "%' ) OR (am.`category` LIKE '%" . $search_data . "%' ) OR (am.`am` LIKE '%" . $search_data . "%' ) )";

            } else {
                //$customer = $customer->where('users.Uemail' , 'LIKE', '%'.$search_data.'%');
                $sql .= "AND ((u.`Uemail` LIKE '%" . $search_data . "%' ) OR (u.`firstname` LIKE '%" . $search_data . "%' ) OR  (u.`surname` LIKE '%" . $search_data . "%' ) OR (am.`category` LIKE '%" . $search_data . "%' ) OR (am.`am` LIKE '%" . $search_data . "%' ) )";

            }

        }
        $sql .= "ORDER BY am.`updated_at` DESC";
        $customer = DB::select($sql);
		/*$customer = $customer->select(array(
			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			'users.UID AS UID',
			'admin_members.uid AS admin_uid',
			'admin_members.updated_at AS last_updated_at',
			'admin_members.expiry AS expiry',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.category AS category',
			'admin_members.am AS am',
		));*/

		return Datatables::of($customer)->make(true);

	}

	function noneMemberships($data)
	{
        if(isset($data['search']['value'])){
        	$today = date('Y-m-d');
            $customer = Customer::leftjoin(
                'admin_members',
                'users.UID',
                '=',
                'admin_members.uid'
            )->leftjoin(
                'admin_users',
                'admin_users.username',
                '=',
                'admin_members.am'
            )->whereNull('membership'
            )->where('users.Uemail' , 'LIKE', '%'.$data['search']['value'].'%'
            )->orderBy('users.reg_date','desc'
            )->limit(25
            )->distinct();

            $customer = $customer->select(array(
                'users.active_ads_count AS ads_count',
                'users.firstname AS firstname',
                'users.surname AS surname',
                'users.Uemail AS Uemail',
                'users.reg_date As reg_date',
                'users.UID AS UID',
                'admin_users.name AS am',
                'admin_members.latest_comment',
                'users.last_updated_at',
                'admin_members.remarks'
            ));
        }else {
            $today = date('Y-m-d');
            $customer = Customer::leftjoin(
                'admin_members',
                'users.UID',
                '=',
                'admin_members.uid'
            )->whereRaw('ABS(TIMESTAMPDIFF(DAY, users.reg_date, ?)) > 2', $today
            )->whereRaw('ABS(TIMESTAMPDIFF(DAY, users.reg_date, ?)) < 30', $today
            )->whereNull('membership'
            )->orderBy('users.reg_date','desc'
            )->limit(25
            )->distinct();

            $customer = $customer->select(array(
                'users.active_ads_count AS ads_count',
                'users.firstname AS firstname',
                'users.surname AS surname',
                'users.Uemail AS Uemail',
                'users.reg_date As reg_date',
                'users.UID AS UID',
                'admin_members.am AS am',
                'admin_members.latest_comment',
                'users.last_updated_at',
                'admin_members.remarks'
            ));
        }
		return Datatables::of($customer)->make(true);

	}

	/**
	 * Auto-complete user/customer email
	 * @return Array
	 */
	function AutocompleteCustomerEmail($term) {
		$query = $term;

		$customers = Customer::where('Uemail', 'LIKE', '%' . $query . '%')->limit(25)->get();

		$data = array();

		foreach ($customers as $customer) {
			$data[] = array('value' => $customer->Uemail, 'id' => $customer->Uemail);
		}
		if (count($data)) {
			return $data;
		} else {
			return ['value' => 'No Result Found', 'id' => ''];
		}
	}

    /**
     * * Get list of ads by user/customer email
     * @param $email
     * @param $search_data
     * @return JSON Datatable json object
     */
	function listAdsByCustomer($email, $search_data) {
        /*$customer = Customer::join(
            'adverts',
            'users.UID',
            '=',
            'adverts.UID'
        )->select(array(
            'adverts.ad_id AS ad_id',
            'adverts.propty_type AS propty_type',
            'adverts.service_type AS service_type',
            'adverts.heading AS heading',
            'adverts.is_active AS is_active',
            'adverts.type AS type',
            'adverts.submit_date AS submit_date',

        )/*)->where('users.Uemail', 'LIKE', '%' . $email . '%')*/
           /* )->whereRaw('(adverts.is_active = 3 OR adverts.is_active = 1) AND users.Uemail LIKE "%?%"',[$email])
            ->orderBy('submit_date', 'desc'); */
        $thisYear = date("Y");
        $thisMonth = date("m");
        $lastMonth = date('m', strtotime('last month'));
        $month_ago = date('Y-m-d', strtotime('-30 days'));
        $sql = "SELECT `adverts`.`ad_id` AS 'ad_id',`adverts`.`propty_type` AS 'propty_type',`adverts`.`service_type` AS 'service_type',
                `adverts`.`agent_page_ref`,`adverts`.`city`,
                `adverts`.`heading` AS 'heading', `adverts`.`is_active` AS 'is_active', `adverts`.`type` AS 'type', 
                 `adverts`.`submit_date` AS 'submit_date',`adverts`.`posted_date` AS 'posted_date',`adverts`.`price`,`adverts`.`price_land_pp`,`adverts`.`price_sqft`,`adverts`.`price_monthly`,
                  `adverts`.`current_page_id`,`adverts`.`is_showcase`,`ad_upgrade_package`.`priority_name` AS 'priority', `latest_stats_for_ads`.`last_boost_count` AS boosted_count,
                    `latest_stats_for_ads`.`my_views` AS ad_views,`latest_stats_for_ads`.`total_views_section` AS last_month_total_market_views,
                    `latest_stats_for_ads`.`my_leads` AS ad_leads, `latest_stats_for_ads`.`total_leads_section` AS last_month_leads,
                    `latest_stats_for_ads`.`avg_price` AS price_comparison,
                     `latest_stats_for_ads`.`this_with_last_views` AS this_with_last_views,
                      `latest_stats_for_ads`.`old_total_views` AS old_total_views, 
                    (SELECT price_type FROM avg_property_stats WHERE CASE WHEN `adverts`.`type` = 'sales' THEN `sale_type` = 'Sale' 
                    WHEN `adverts`.`type` = 'land' THEN `sale_type` = 'Land'                                                                                     
                    WHEN `adverts`.`type` = 'rentals' THEN `sale_type` = 'Rental' END
                     AND city_name = `adverts`.`city` ORDER BY id DESC LIMIT 1) AS price_type,
                CASE
                                WHEN `adverts`.`was_active` = 1 AND  `admin_members`.`category` != 'Single Ad' AND `adverts`.`is_active` != 2  THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                FROM `adverts`
                INNER JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                LEFT JOIN `contact_stats` ON `contact_stats`.`AdID` = `adverts`.`ad_id`
                LEFT JOIN `leads_data` ON `leads_data`.`ad_id` = `adverts`.`ad_id`
                LEFT JOIN `latest_stats_for_ads` ON `latest_stats_for_ads`.`ad_id` = `adverts`.`ad_id`
                LEFT JOIN `admin_members` ON `adverts`.`UID` = `admin_members`.`uid`
                LEFT JOIN `leads_month_data` ON `leads_month_data`.`ad_id` = `adverts`.`ad_id`
                LEFT JOIN `ad_upgrade_package` ON `ad_upgrade_package`.`priority_id` = `adverts`.`priority`
                WHERE `users`.`Uemail` LIKE '%".$email."%' AND (`adverts`.`propty_type` LIKE '%".$search_data."%' OR 
                `adverts`.`ad_id` LIKE '%".$search_data."%' OR `adverts`.`heading` LIKE '%".$search_data."%') 
                GROUP BY `adverts`.`ad_id` ORDER BY `adverts`.`submit_date` DESC";

        $customer = DB::select($sql);

        return Datatables::of($customer)->make(true);

	}

    /**
     * Get user email & max ad count use by uid
     * @param $uid
     * @return mixed|string
     */
    public function getUserEmailByUID($uid)
    {
        $sql = "SELECT u.`Uemail`, IFNULL(ap.`num_of_max_ads`,0) AS 'num_of_max_ads'
                FROM `users` AS u
                LEFT JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                LEFT JOIN  `admin_member_packages` AS ap ON ap.`package_name` = am.`category`
                WHERE u.`UID` = " .$uid. " 
                ORDER BY u.`UID` DESC
                LIMIT 1";
        $user_data = DB::select($sql);
        return (isset($user_data[0])?$user_data[0]:'');
    }

    /**
     *  Get user max ad count use by uid
     * @param $email
     * @return string
     */
    public function getUserAdCountByEmail($email)
    {
        $sql = "SELECT u.`Uemail`, IFNULL(ap.`num_of_max_ads`,0) AS 'num_of_max_ads'
                FROM `users` AS u
                LEFT JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                LEFT JOIN  `admin_member_packages` AS ap ON ap.`package_name` = am.`category`
                WHERE u.`Uemail` = '" .$email. "' 
                ORDER BY u.`Uemail` DESC
                LIMIT 1";
        //dd($sql);
        $user_data = DB::select($sql);
        return (isset($user_data[0])?$user_data[0]:'');
    }

}
