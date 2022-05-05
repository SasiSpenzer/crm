<?php

namespace App\Repositories;

use App\Contracts\DashboardInterface;
use Carbon\Carbon;
use App\Member;
use App\User;
use App\MemberAction;
use Auth;
use Illuminate\Support\Facades\DB;

class EloquentDashboardRepository implements DashboardInterface
{

    /**
     * @var numOfMonths
     */
    private $numOfMonths;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getDetails($userId)
    {

        /*$users = \DB::table('users')
            ->select(\DB::raw('count(*) as user_count'))
            ->where('ads_count', '>', 0)
            ->get()->toArray();*/
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $today = date('Y-m-d');
        $this_month = date('Y-m');
        $before_two_weeks = date('Y-m-d', strtotime($today . ' - 14 days'));
        $yearbefore = date('Y-m-d', strtotime($today . ' - 365 days'));
        $sql = "SELECT COUNT(am.`id`) AS 'user_count'
                FROM `admin_members` AS am 
                WHERE am.`type` = 'Member' AND am.category != 'Single Ad' AND am.payment != 'Free'";
        $users = \DB::select($sql);

//        $active_members = \DB::table('admin_members')
//            ->select(\DB::raw('count(*) as active_member_count'))
//            ->where('type', '=', "Member")
//            ->where('category', '=', "Single Ad")
//            ->where('payment_exp_date', '>=', $before_two_weeks)
//            ->get()->toArray();

        $newSql = "SELECT COUNT(DISTINCT am.id) AS freeCount FROM admin_members AS am
                    LEFT JOIN adverts AS ad ON am.uid = ad.UID
                    WHERE am.type = 'Member' AND 
                    am.category = 'Single Ad' AND (am.user_type_id = 1 OR am.user_type_id = 3)";
        $active_members = \DB::select($newSql);

        $active_members_count = isset($active_members[0]) ? $this->numberFormat(intval($active_members[0]->freeCount)) : 0;

//        $members = \DB::table('admin_members')
//            ->select(\DB::raw('count(*) as member_count'))
//            ->leftjoin('users ','users .UID','=','am.UID')
//            ->where('type', '=', "Member")
//            ->whereNotIn('category', ["Free", "", "Single Ad"])
//            ->get()->toArray();

        $members = "SELECT COUNT(am.`id`) AS 'member_count'
                FROM `admin_members` AS am 
                LEFT JOIN users u ON u.UID=am.UID
                WHERE am.type = 'Member' AND am.category <> 'Single Ad' AND am.category <> 'Free' 
                AND am.category NOT LIKE '%Business%' AND am.payment NOT LIKE 'Free%' 
                AND (user_type_id = 1 OR user_type_id = 3)";
        $members = \DB::select($members);

        $currentDate = Carbon::now()->format('Y-m-d');
        $dateBeforeSevenDays = Carbon::now()->subDays(7)->format('Y-m-d');

        $accounts = \DB::table('admin_members')
            ->select(\DB::raw('count(*) as updated_accounts'))
            ->whereDate('updated_at', '=', $currentDate)
            ->get()->toArray();

        $newMembers = \DB::table('admin_members')
            ->select(\DB::raw('count(*) as new_members'))
            ->where('member_since', '=', $currentDate)
            ->where('type', '=', "Member")
            ->whereNotIn('category', ["Free", ""])
            ->get()->toArray();

        $updatedAccountsLastSevenDays = \DB::table('admin_members')
            ->select(\DB::raw('count(*) as updated_accounts_last7days'))
            ->whereDate('updated_at', '<=', $currentDate)
            ->whereDate('updated_at', '>=', $dateBeforeSevenDays)
            ->get()->toArray();

        $newMembersLastSevenDays = \DB::table('admin_members')
            ->select(\DB::raw('count(*) as new_members_last7days'))
            ->where('member_since', '<=', $currentDate)
            ->where('member_since', '>=', $dateBeforeSevenDays)
            ->where('type', '=', "Member")
            ->whereNotIn('category', ["Free", ""])
            ->get()->toArray();

        $yesterday = Carbon::yesterday();
        $revenue = Member::select(['package_amount', 'am', 'uid'])
            ->where('type', 'Member')
            ->where('expiry', '>', $yesterday)
            ->where('payment_exp_date', '>', $yesterday)
            ->get();

        $ams = User::whereIn('admin_level', [1, 2])->pluck('username');

        $ams_AMS = User::where('admin_level', '=', 1)->where('username', '!=', 'Online')->pluck('username');
        $ams_Hunters = User::where('admin_level', '=', 2)->pluck('username');

        $totalRevenue = $revenue->sum('package_amount');
        $totalRevenue_dis = $this->numberFormat($totalRevenue);

        //$member_action = MemberAction::all(); //to get boost values
        $member_action = Member::leftjoin('admin_members_actions',
            'admin_members_actions.uid', '=', 'admin_members.uid')
            ->select([
                'admin_members.am as am',
                'admin_members_actions.qty as qty',
                'admin_members_actions.value as value'
            ])
            ->where('admin_members.type', 'Member')
            ->where('admin_members.expiry', '>', $yesterday)
            ->where('admin_members.payment_exp_date', '>', $yesterday)
            ->get();

        /*$my_package_revenue = $revenue->where('am', Auth::user()->username)->sum('package_amount');
        $rev_uids = $revenue->where('am', Auth::user()->username)->pluck('uid')->unique();
        $services = $member_action->whereIn('uid', $rev_uids);
        $my_service_revenue = 0;
        foreach ($services as $key) {
            $my_service_revenue += $key->qty * $key->value;
        }
        $myRevenue = $my_package_revenue + $my_service_revenue;*/
        /*$myRevenue_query = DB::table('google_payment_data')
                        ->select(DB::raw('SUM(google_payment_data.invoiced_amount) as invoiced_amount'))
                        ->where('google_payment_data.am', Auth::user()->username)
                        ->where('google_payment_data.due_date', '>=', $this_month)
                        ->get();*/

        $am_status = intval(Auth::user()->am_status);

        if ($am_status == 2) {
            $sql = "SELECT gpd.`am`, SUM(gpd.`paid_amount`) AS 'invoiced_amount', SUM(gpd.`pending_amount`) AS 'pending_val'
                        FROM `google_payment_data` AS gpd 
                        WHERE gpd.`due_date` LIKE '" . $this_month . "%' AND gpd.`am` = '" . Auth::user()->username . "'";
        } else {
            $sql = "SELECT gpd.`am`, SUM(gpd.`invoiced_amount`) AS 'invoiced_amount', SUM(gpd.`pending_amount`) AS 'pending_val'
                        FROM `google_payment_data` AS gpd 
                        WHERE gpd.`due_date` LIKE '" . $this_month . "%' AND gpd.`am` = '" . Auth::user()->username . "'";
        }


        $myRevenue_query = DB::select($sql);
        $myRevenue = isset($myRevenue_query[0]) ? doubleval($myRevenue_query[0]->invoiced_amount) : 0;
        $myRevenue_dis = $this->numberFormat($myRevenue);

        //$group_package_revenue = $revenue->whereIn('am', $ams)->sum('package_amount');
        $data = $this_month . "%";

        // For AM's Group Revenue

        $groupRevenue_query_am = DB::table('google_payment_data')
            ->select(DB::raw('SUM(google_payment_data.invoiced_amount) as invoiced_total_amount'))
            ->where('google_payment_data.due_date', 'LIKE', $data)
            ->whereIn('am', $ams_AMS)
            ->get();
        $groupRevenue_AM = isset($groupRevenue_query_am[0]) ? $groupRevenue_query_am[0]->invoiced_total_amount : 0;

        // For Hunters

        $groupRevenue_query_hunters = DB::table('google_payment_data')
            ->select(DB::raw('SUM(google_payment_data.paid_amount) as paid_total_amount'))
            ->where('google_payment_data.due_date', 'LIKE', $data)
            ->whereIn('am', $ams_Hunters)
            ->get();

        $groupRevenue_Hunters = isset($groupRevenue_query_hunters[0]) ? $groupRevenue_query_hunters[0]->paid_total_amount : 0;

        // Get Total Revenue
        $groupRevenue = $groupRevenue_Hunters + $groupRevenue_AM;

        /*$rev_uids = $revenue->whereIn('am', $ams)->pluck('uid')->unique();
		$services = $member_action->whereIn('uid', $rev_uids);
		$group_service_revenue = 0;
		foreach ($services as $key) {
			$group_service_revenue += $key->qty * $key->value;
		}*/
        //$groupRevenue = $group_service_revenue + $group_package_revenue;
        $groupRevenue_dis = $this->numberFormat($groupRevenue);

        $targets = User::select(['username', 'target'])->whereIn('username', $ams)->get();
        $group_target = $targets->sum('target');
        $group_target_dis = $this->numberFormat($group_target);
        $my_target = @$targets->where('username', Auth::user()->username)->first()->target;
        $my_target_dis = $this->numberFormat($my_target);

        /**For get potential data*/
        $sql1 = "SELECT SUM(m.`membership_payment`) AS 'total_membership_payment'
                FROM (
                    SELECT am.`category`,  COUNT(am.`id`) AS 'membership_count', 
                    (COUNT(am.`id`) * amp.`monthly_payment`) AS 'membership_payment' 
                    FROM `admin_members` AS am 
                    INNER JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                    WHERE am.`category` != 'Free' 
                    AND am.`category` != 'Trail' AND am.created_at >= '" . $yearbefore . "'
                    AND am.`payment_status` != 5 AND am.`payment_status` != 6 AND am.`payment_status` != 7 AND am.`payment_status` != 8 
                    GROUP BY am.`category`
                ) AS m";
        $potential_count = DB::select($sql1);
        $potential_value = isset($potential_count[0]) ? $this->numberFormat($potential_count[0]->total_membership_payment) : 0.00;

        $widgets = [
            'total_users' => $users[0]->user_count, 'total_members' => $members[0]->member_count, 'active_members' => $active_members_count,
            'updated_accounts' => $accounts[0]->updated_accounts, 'new_members' => $newMembers[0]->new_members,
            'updated_accounts_last7days' => $updatedAccountsLastSevenDays[0]->updated_accounts_last7days,
            'new_members_last7days' => $newMembersLastSevenDays[0]->new_members_last7days,
            'total_revenue' => $totalRevenue, 'my_target' => $my_target, 'my_revenue' => $myRevenue,
            'my_revenue_dis' => $myRevenue_dis, 'group_target' => $group_target, 'group_revenue' => $groupRevenue,
            'group_revenue_dis' => $groupRevenue_dis, 'total_revenue_dis' => $totalRevenue_dis,
            'my_target_dis' => $my_target_dis, 'group_target_dis' => $group_target_dis, 'potential_value' => $potential_value
        ];

        return \Response::json($widgets);

    }

    /**
     * Get dashboard widget user details
     * @param $userId
     * @param $username
     * @return mixed|void
     */
    public function getUserDetails($userId, $username)
    {

        //For process & output variables
        $total_users_membership_count = 0;
        $total_users_membership_value = 0.00;
        $online_membership_count = 0;
        $online_membership_value = 0.00;
        $unassigned_membership_count = 0;
        $unassigned_membership_value = 0.00;
        $private_seller_membership_count = 0;
        $private_seller_membership_value = 0.00;

        $paid_member_count = 0;
        $free_member_count = 0;

        $total_invoiced_amount = 0.00;
        $total_paid_amount = 0.00;
        $am_total_paid_amount = 0.00;
        $am_total_invoiced_amount = 0.00;
        $am_total_pending_amount = 0.00;
        $am_data = array();
        $am_names = array();
        $today = date('Y-m-d');

        //Get login user admin level
        $admin_data = \DB::table('admin_users')
            ->select('admin_users.admin_level')
            ->where('admin_users.id', '=', $userId)
            ->get()->toArray();
        $admin_level = isset($admin_data[0]) ? intval($admin_data[0]->admin_level) : 0;

        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $this_month = date('Y-m');
        $today = date('Y-m-d');
        $before_three_months = date('Y-m-d', strtotime($today . ' - 90 days'));
        $before_two_months = date('Y-m-d', strtotime($today . ' - 60 days'));
        $before_one_year = date('Y-m-d', strtotime($today . ' - 365 days'));
        $before_six_months = date('Y-m-d', strtotime($today . ' - 180 days'));
        $before_two_weeks = date('Y-m-d', strtotime($today . ' - 14 days'));

        //Get am wise members data
        //Get all package data except online am packages
        $users_sql = "SELECT am.`am`, am.`category`, COUNT(am.`id`) AS 'user_count', 
                        (COUNT(am.`id`) * amp.`monthly_payment`) AS 'user_monthly_payment', 
                        am.`payment`, amp.`monthly_payment`, amp.`quarterly_payment`, amp.`annual_payment`, '1' AS 'department_id'
                        FROM `admin_members` AS am 
                        INNER JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                        WHERE ((am.`type` = 'Member') OR (am.`type` != 'Member' AND am.`payment_exp_date` > '" . $before_three_months . "' 
                        AND (am.`am` IS NULL OR am.`am` = '' )))  AND am.`am` != 'Online'
                        GROUP BY am.`am`, am.`category`";

        $users_data = \DB::select($users_sql);


        for ($row = 0; count($users_data) > $row; $row++) {
            //For total members process
            $total_users_membership_count += intval($users_data[$row]->user_count);
            $total_users_membership_value += floatval($users_data[$row]->user_monthly_payment);

            //For online members process
            /*if($users_data[$row]->am == 'Online'){
                $online_membership_count += intval($users_data[$row]->user_count);
                $online_membership_value += floatval($users_data[$row]->user_monthly_payment);
            }*/

            //For unassigned members process
            /*else */
            if ($users_data[$row]->am == '' || $users_data[$row]->am == null) {
                $unassigned_membership_count += intval($users_data[$row]->user_count);
                $unassigned_membership_value += floatval($users_data[$row]->user_monthly_payment);
            } //For am members process
            else {
                if ($admin_level > 2) {
                    if (in_array($users_data[$row]->am, $am_names)) {
                        $index = array_search($users_data[$row]->am, $am_names);
                        $am_data[$index]['am'] = ucfirst($users_data[$row]->am);
                        $am_data[$index]['am_user_count'] += intval($users_data[$row]->user_count);
                        $am_data[$index]['am_user_monthly_payment'] += floatval($users_data[$row]->user_monthly_payment);
                    } else {
                        $index = count($am_names);
                        $am_names[$index] = $users_data[$row]->am;
                        $am_data[$index]['am'] = ucfirst($users_data[$row]->am);
                        $am_data[$index]['am_department_id'] = intval($users_data[$row]->department_id);
                        $am_data[$index]['am_user_count'] = intval($users_data[$row]->user_count);
                        $am_data[$index]['am_user_monthly_payment'] = floatval($users_data[$row]->user_monthly_payment);
                    }
                } else if ($username == $users_data[$row]->am) {
                    $am_data[0]['am'] = ucfirst($users_data[$row]->am);
                    $am_data[0]['am_user_count'] = intval($users_data[$row]->user_count);
                    $am_data[0]['am_user_monthly_payment'] = floatval($users_data[$row]->user_monthly_payment);

                    $sql_total = "SELECT IFNULL(SUM(gpd.`paid_amount`), 0) AS 'total_paid_amount', 
                                    IFNULL(SUM(gpd.`invoiced_amount`), 0) AS 'total_invoiced_amount', 
                                    IFNULL(SUM(gpd.`pending_amount`), 0) AS 'total_pending_amount'
                                    FROM `google_payment_data` AS gpd 
                                    INNER JOIN `admin_members` AS am ON am.`uid` = gpd.`uid` 
                                    WHERE gpd.`due_date` LIKE '" . $this_month . "%' AND am.`am` = '" . $am_data[0]['am'] . "'";
                    $payment_data = \DB::select($sql_total);
                    $am_total_paid_amount = isset($payment_data[0]) ? $this->numberFormat(doubleval($payment_data[0]->total_paid_amount)) : 0.00;
                    $am_total_invoiced_amount = isset($payment_data[0]) ? $this->numberFormat(doubleval($payment_data[0]->total_invoiced_amount)) : 0.00;
                    $am_total_pending_amount = isset($payment_data[0]) ? $this->numberFormat(doubleval($payment_data[0]->total_pending_amount)) : 0.00;
                }
                //dd($am_data);
            }
        }

        $sql_online = "SELECT SUM(am.`user_count`) AS 'user_count', SUM(am.`user_monthly_payment`) AS 'user_monthly_payment'
                        FROM (
                            SELECT am.`am`, am.`category`, COUNT(am.`id`) AS 'user_count', 
                                (COUNT(am.`id`) * amp.`monthly_payment`) AS 'user_monthly_payment', 
                                am.`payment`, amp.`monthly_payment`, amp.`quarterly_payment`, amp.`annual_payment`, '1' AS 'department_id'
                            FROM `admin_members` AS am 
                            INNER JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                            WHERE ((am.`type` = 'Member') OR (am.`type` != 'Member' AND am.`payment_exp_date` > '" . $before_three_months . "' 
                                AND (am.`am` IS NULL OR am.`am` = '' )))  AND (am.`am` = 'Online' AND am.`category` != 'Free' AND am.`category` != 'Single Ad'
                                AND am.`category` != 'Free' AND am.`category` != '' AND am.`am` IS NOT NULL)
                            GROUP BY am.`am`, am.`category`
                        ) AS am";
        $users_online_data = \DB::select($sql_online);

        $online_membership_count = isset($users_online_data[0]) ? $users_online_data[0]->user_count : 0;
        $online_membership_value = isset($users_online_data[0]) ? $users_online_data[0]->user_monthly_payment : 0.00;

        $sql_total = "SELECT SUM(gpd.`paid_amount`) AS 'total_paid_amount', 
                        SUM(gpd.`invoiced_amount`) AS 'total_invoiced_amount', 
                        SUM(gpd.`pending_amount`) AS 'total_pending_amount'
                        FROM `google_payment_data` AS gpd 
                        INNER JOIN `admin_members` AS am ON am.`uid` = gpd.`uid` 
                        WHERE gpd.`due_date` LIKE '" . $this_month . "%' ";
        $payment_data = \DB::select($sql_total);
        $total_paid_amount = isset($payment_data[0]) ? $this->numberFormat(doubleval($payment_data[0]->total_paid_amount)) : 0.00;
        $total_invoiced_amount = isset($payment_data[0]) ? $this->numberFormat(doubleval($payment_data[0]->total_invoiced_amount)) : 0.00;
        $total_pending_amount = isset($payment_data[0]) ? $this->numberFormat(doubleval($payment_data[0]->total_pending_amount)) : 0.00;

        //For private seller data process
        $sql = "SELECT `admin_members`.`id` AS 'user_count' , `admin_members`.`payment_status`
                FROM `adverts` 
                INNER JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                WHERE `adverts`.`is_active` = 3 AND TIMESTAMPDIFF(DAY, `adverts`.`posted_date`, ?) < 90
                AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad'";
        $private_seller_data = \DB::select($sql, [$today]);
        for ($row2 = 0; count($private_seller_data) > $row2; $row2++) {
            //$private_seller_membership_count += intval($private_seller_data[$row2]->user_count);
            $private_seller_membership_count++;
            if (intval($private_seller_data[$row2]->payment_status) == 0) {
                $private_seller_membership_value += 1;
            }

        }

//        $paid_member_count = \DB::table('admin_members')
//            ->select(\DB::raw('count(*) as paidCount'))
//            ->where('type', '=', "Member")
//            ->where('category', '=', "Single Ad")
//            ->where('payment_exp_date', '>=', $before_two_weeks)
//            ->where('payment', '!=', 'Free')
//            ->get()->toArray();
//
//        $active_members_count_paid = isset($paid_member_count[0]) ? $this->numberFormat(intval($paid_member_count[0]->paidCount)) : 0;

        // Check Paid member Count By Sasi Spenzer 2021-10-07
        $paidMemberSql = "SELECT COUNT(*) AS paidCount FROM admin_members WHERE type LIKE 'member' AND category LIKE '%single%' AND payment NOT LIKE '%free%'";

        $paid_member_count = \DB::select($paidMemberSql);
        $active_members_count_paid = isset($paid_member_count[0]) ? $this->numberFormat(intval($paid_member_count[0]->paidCount)) : 0;


//        $free_member_count = \DB::table('admin_members')
//            ->select(\DB::raw('count(*) as freeCount'))
//            ->where('type', '=', "Member")
//            ->where('category', '=', "Single Ad")
//            ->where('payment_exp_date', '>=', $before_two_weeks)
//            ->where('payment', '=', 'Free')
//            ->get()->toArray();
//
//        $active_members_count_free = isset($free_member_count[0]) ? $this->numberFormat(intval($free_member_count[0]->freeCount)) : 0;

        // Check Free member Count By Sasi Spenzer 2021-10-07
        $freeMemberSql = "SELECT COUNT(*) AS freeCount FROM admin_members WHERE type LIKE 'member' AND category LIKE '%single%' AND payment LIKE '%free%'";

        $free_member_count = \DB::select($freeMemberSql);
        $active_members_count_free = isset($free_member_count[0]) ? $this->numberFormat(intval($free_member_count[0]->freeCount)) : 0;

        // Check Member Expire Count By Sasi Spenzer 2021-10-08 ** WFH
        $member_expire_count_newSql = "SELECT COUNT(am.`id`) AS member_expire_count_new FROM admin_members AS am
        WHERE am.type = 'Expired' AND am.category != 'Single Ad' AND am.payment != 'Free' AND payment_exp_date < '" . $today . "' AND payment_exp_date > '" . $before_two_months . "'";
        $member_expire_count_new = \DB::select($member_expire_count_newSql);

        // Check Member Expire +2 Count By Sasi Spenzer 2021-10-08 ** WFH
        $member_expire_count_plus_two_newSql = "SELECT COUNT(am.`id`) AS member_expire_count_plus_two_new FROM admin_members AS am
        WHERE am.type = 'Expired' AND am.category != 'Single Ad' AND am.payment != 'Free' AND payment_exp_date < '" . $before_two_months . "' AND payment_exp_date > '" . $before_one_year . "'";
        $member_expire_count_plus_two_new = \DB::select($member_expire_count_plus_two_newSql);

        // Check For Single Ad Expire Upto 6 months By Sasi Spenzer ** WFH
        $pvt_sellers_expire_count_new_newSql = "SELECT COUNT(am.`id`) AS pvt_sellers_expire_count_new FROM admin_members AS am
        WHERE am.type = 'Expired' AND am.category = 'Single Ad' AND am.payment != 'Free' AND payment_exp_date > '" . $before_six_months . "'";
        $pvt_sellers_expire_count_new = \DB::select($pvt_sellers_expire_count_new_newSql);

        //For am data number format set
        for ($row3 = 0; count($am_data) > $row3; $row3++) {
            $am_data[$row3]['am_user_monthly_payment'] = $this->numberFormat($am_data[$row3]['am_user_monthly_payment']);
        }
        if ($admin_level > 2) {
            $sql1 = "SELECT IF(am.`name` IS NOT NULL, am.`am`, IF(am.`name` != '',am.`am`, am.`am`)) AS 'am', SUM(gpd.`pending_amount`) AS 'pending_val', 
                        SUM(gpd.`invoiced_amount`) AS 'total_invoiced_amount', SUM(gpd.`paid_amount`) AS 'total_paid_amount',
                        IFNULL(am.`member_count`,0) AS 'member_count', IFNULL(am.`revnue_total`, 0) AS 'revnue_total' 
                        FROM `google_payment_data` AS gpd 
                        LEFT JOIN (
                            SELECT am.`name`, am.`am`, SUM(am.`member_count`) AS 'member_count', SUM(am.`revnue_total`) AS 'revnue_total'
                            FROM (
                                SELECT au.`name`, am.`am`, COUNT(am.`id`) AS 'member_count', am.`category`, (COUNT(am.`id`) * amp.`monthly_payment`) AS 'revnue_total'
                                FROM `admin_members` AS am 
                                LEFT JOIN `admin_users` AS au ON au.`username` = am.`am`
                                LEFT JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                                WHERE am.`type` = 'Member' AND am.`category` != 'Free' AND am.`category` IS NOT NULL
                                AND am.`am` != '' AND am.`am` IS NOT NULL 
                                GROUP BY am.`am`, am.`category`
                            ) AS am 
                            GROUP BY am.`am`
                        ) AS am ON am.`am` = gpd.`am` 
                WHERE gpd.`due_date` LIKE '" . $this_month . "%' AND am.`am` IS NOT NULL AND am.`am` != '' 
                GROUP BY gpd.`am` ";
            $total_pending_amount = $am_total_pending_amount;
        } else {
            $sql1 = "SELECT IF(am.`name` IS NOT NULL, am.`am`, IF(am.`name` != '',am.`am`, am.`am`)) AS 'am', SUM(gpd.`pending_amount`) AS 'pending_val', 
                        SUM(gpd.`invoiced_amount`) AS 'total_invoiced_amount', SUM(gpd.`paid_amount`) AS 'total_paid_amount',
                        IFNULL(am.`member_count`,0) AS 'member_count', IFNULL(am.`revnue_total`, 0) AS 'revnue_total' 
                        FROM `google_payment_data` AS gpd 
                        LEFT JOIN (
                            SELECT am.`name`, am.`am`, SUM(am.`member_count`) AS 'member_count', SUM(am.`revnue_total`) AS 'revnue_total'
                            FROM (
                                SELECT au.`name`, am.`am`, COUNT(am.`id`) AS 'member_count', am.`category`, (COUNT(am.`id`) * amp.`monthly_payment`) AS 'revnue_total'
                                FROM `admin_members` AS am 
                                LEFT JOIN `admin_users` AS au ON au.`username` = am.`am`
                                LEFT JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                                WHERE am.`type` = 'Member' AND am.`category` != 'Free' AND am.`category` IS NOT NULL 
                                AND am.`am` != '' AND am.`am` IS NOT NULL AND am.`am` = '" . $username . "' 
                                GROUP BY am.`am`, am.`category`
                            ) AS am 
                            GROUP BY am.`am`
                        ) AS am ON am.`am` = gpd.`am`
                    WHERE gpd.`due_date` LIKE '" . $this_month . "%' AND gpd.`am` = '" . $username . "' AND am.`am` IS NOT NULL AND am.`am` != '' 
                    GROUP BY gpd.`am` ";
            $total_pending_amount = $am_total_pending_amount;
        }


        // Check User Count By Account type

        // Get UserTypes
        $userDataArray = array();
        $sqlUserTypes = "SELECT user_type_id,user_type,display_name FROM `user_type` WHERE is_enable = 1";
        $db_user_types = \DB::select($sqlUserTypes);


        foreach ($db_user_types as $each_user_type) {
            $userTempArray = array();
            $user_type_id = $each_user_type->user_type_id;
            $user_type = $each_user_type->user_type;
            $user_type_display_name = $each_user_type->display_name;

            if ($user_type_id == 1) {
                $activeMemberSql = "SELECT COUNT(DISTINCT am.`id`) AS freeCount FROM admin_members AS am
WHERE am.type = 'Member' AND am.category <> 'Single Ad' AND am.category <> 'Free' AND am.category NOT LIKE '%Business%' AND user_type_id != 4";

                $expireMemberSql = "SELECT COUNT(DISTINCT am.`id`) AS exCount FROM admin_members AS am
                              LEFT JOIN adverts AS ad ON am.uid = ad.UID
                              WHERE am.type != 'Member' AND am.category <> 'Single Ad' AND am.category NOT LIKE '%Business%' AND user_type_id != 4 AND payment_exp_date < '" . $today . "'";
            } else if ($user_type_id == 3) {
                $activeMemberSql = "SELECT COUNT(DISTINCT am.id) AS freeCount FROM admin_members AS am
                                    LEFT JOIN adverts AS ad ON am.uid = ad.UID
                                    WHERE am.type = 'Member' AND am.category = 'Single Ad' AND (am.user_type_id = 1 OR am.user_type_id = 3)";

                $expireMemberSql = "SELECT COUNT(DISTINCT am.`id`) AS exCount FROM admin_members AS am
                              LEFT JOIN adverts AS ad ON am.uid = ad.UID
                              WHERE am.type != 'Member' AND  am.category = 'Single Ad' AND  (am.user_type_id = 1 OR am.user_type_id = 3) AND payment_exp_date < '" . $today . "'";
            } else {
                $activeMemberSql = "SELECT COUNT(DISTINCT am.`id`) AS freeCount FROM admin_members AS am
                              LEFT JOIN adverts AS ad ON am.uid = ad.UID
                              WHERE am.type = 'Member' AND am.user_type_id = '" . $user_type_id . "'";

                $expireMemberSql = "SELECT COUNT(DISTINCT am.`id`) AS exCount FROM admin_members AS am
                              LEFT JOIN adverts AS ad ON am.uid = ad.UID
                              WHERE am.type != 'Member' AND  am.user_type_id = '" . $user_type_id . "' AND payment_exp_date < '" . $today . "'";

            }
            $activeMemberSqlData = \DB::select($activeMemberSql);
            $expireMemberSqlData = \DB::select($expireMemberSql);

            $userTempArray['account_type'] = $user_type;
            $userTempArray['active_count'] = $activeMemberSqlData[0]->freeCount;
            $userTempArray['expired_count'] = $expireMemberSqlData[0]->exCount;
            array_push($userDataArray, $userTempArray);

        }


        $am_payment_data = array();
        $am_payment_sql = \DB::select($sql1);

        $countResults = count($am_payment_sql);
        for ($row4 = 0; count($am_payment_sql) > $row4; $row4++) {
            $am_payment_data[$row4]['am'] = $am_payment_sql[$row4]->am;

            // Check Full payment By Sasi Spenzer 2021-10-07 *** WFH **
            $full_total_for_am = $this->checkFullTotal($am_payment_sql[$row4]->am);
//            echo "<pre>";
//            print_r($full_total_for_am[0]->revnue_total);
//            exit();

            // Check for Expired Members Count By Sasi Spenzer 2021-10-07 *** WFH **
            $expiredTotal = $this->checkForExpire($am_payment_sql[$row4]->am);


            $am_payment_data[$row4]['pending_val'] = $this->numberFormatPaid(doubleval($am_payment_sql[$row4]->pending_val));
            $am_payment_data[$row4]['total_invoiced_amount'] = $this->numberFormatPaid(doubleval($am_payment_sql[$row4]->total_invoiced_amount));
            $am_payment_data[$row4]['total_paid_amount'] = $this->numberFormatPaid(doubleval($am_payment_sql[$row4]->total_paid_amount));
            $am_payment_data[$row4]['member_count'] = $this->numberFormatPaid(doubleval($am_payment_sql[$row4]->member_count));
            $am_payment_data[$row4]['revnue_total'] = $this->numberFormatPaid(doubleval($am_payment_sql[$row4]->revnue_total));

            //$am_payment_data[$row4]['full_revnue_total'] = $full_total_for_am[0]->revnue_total;
            //$am_payment_data[$row4]['expired_member_count'] = $expiredTotal[0]->member_count;



//            print_r($full_total_for_am[0]->revnue_total);
//            print_r($expiredTotal[0]->member_count);

            if(isset($full_total_for_am[0]->revnue_total)){
                $am_payment_data[$row4]['full_revnue_total'] = $this->numberFormatPaid(doubleval($full_total_for_am[0]->revnue_total));
            } else {
                $am_payment_data[$row4]['full_revnue_total'] = 0;
            }

            if(isset($expiredTotal[0]->member_count)){
                $am_payment_data[$row4]['expired_member_count'] = $expiredTotal[0]->member_count;
            } else {
                $am_payment_data[$row4]['expired_member_count'] = 0;
            }

        }

        // Add Am = System Data into main Array By Sasi Spenzer 2021-10-07 ** WFH
        $lastRaw = $countResults;
        $system_payment_details = $this->checkAllForSystem();


        $am_payment_data[$lastRaw]['am'] = 'System';
        $am_payment_data[$lastRaw]['pending_val'] = $this->numberFormatPaid(doubleval($system_payment_details[0]->pending_val));
        $am_payment_data[$lastRaw]['total_invoiced_amount'] = $this->numberFormatPaid(doubleval($system_payment_details[0]->total_invoiced_amount));
        $am_payment_data[$lastRaw]['total_paid_amount'] = $this->numberFormatPaid(doubleval($system_payment_details[0]->total_paid_amount));
        $am_payment_data[$lastRaw]['member_count'] = $this->numberFormatPaid(doubleval($system_payment_details[0]->member_count));
        $am_payment_data[$lastRaw]['revnue_total'] = $this->numberFormatPaid(doubleval($system_payment_details[0]->revnue_total));

        // Check Full payment By Sasi Spenzer 2021-10-07 *** WFH **
        $full_total_for_system = $this->checkFullTotal('System');

        // Check for Expired Members Count By Sasi Spenzer 2021-10-07 *** WFH **
        $expiredTotal_system = $this->checkForExpire('System');

        $am_payment_data[$lastRaw]['full_revnue_total'] = $this->numberFormatPaid(doubleval($full_total_for_system[0]->revnue_total));
        $am_payment_data[$lastRaw]['expired_member_count'] = $this->numberFormatPaid(doubleval($expiredTotal_system[0]->member_count));

//        echo "<pre>";
//        print_r($am_payment_data);
//        exit();

        $widgets = [
            'total_users_count' => $total_users_membership_count,
            'total_paid_amount' => $total_paid_amount,
            'total_invoiced_amount' => $total_invoiced_amount,
            'total_pending_amount' => $total_pending_amount,
            'total_users_value' => $this->numberFormat($total_users_membership_value),
            'private_seller_membership_count' => $private_seller_membership_count,
            'private_seller_membership_value' => $private_seller_membership_value,

            'paid_count_value' => $active_members_count_paid,// By Sasi Spenzer
            'free_count_value' => $active_members_count_free,// By Sasi Spenzer
            'member_expire_count_new' => $member_expire_count_new[0]->member_expire_count_new,// By Sasi Spenzer
            'member_expire_count_plus_two_new' => $member_expire_count_plus_two_new[0]->member_expire_count_plus_two_new,// By Sasi Spenzer
            'pvt_sellers_expire_count_new' => $pvt_sellers_expire_count_new[0]->pvt_sellers_expire_count_new,// By Sasi Spenzer

            'online_user_count' => $online_membership_count,
            'online_user_value' => $this->numberFormat($online_membership_value),
            'unassigned_user_count' => $unassigned_membership_count,
            'unassigned_user_value' => $this->numberFormat($unassigned_membership_value),
            'am_data' => $am_data,
            'user_id' => $userId,
            'admin_level' => $admin_level,
            'am_payment_data' => $am_payment_data,
            'member_account_type_data' => $userDataArray,
            'am_total_paid_amount' => $am_total_paid_amount,
            'am_total_invoiced_amount' => $am_total_invoiced_amount,
            'am_total_pending_amount' => $am_total_pending_amount,
        ];
        return \Response::json($widgets);
    }

    public function numberFormat($number)
    {
        $output = $number;
        if (intval($number / 1000000) > 0) {
            $output = number_format(floatval($number / 1000000), 2, '.', ',') . "M";
        } else if (intval($number / 1000) > 0) {
            $output = number_format(floatval($number / 1000), 2, '.', ',') . "K";
        }
        return $output;
    }

    public function checkFullTotal($amName)
    {
        $yearbefore = date('Y-m-d', strtotime('-1 year'));

        $sql = "SELECT (COUNT(am.`id`) * amp.`monthly_payment`) AS 'revnue_total'
                                FROM `admin_members` AS am 
                                LEFT JOIN `admin_users` AS au ON au.`username` = am.`am`
                                LEFT JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                                WHERE  am.`category` != 'Free' AND am.`category` IS NOT NULL AND  am.`category` != 'Single Ad' 
                                AND am.`am` != '' AND am.`am` IS NOT NULL AND am.`am` = '" . $amName . "'  AND am.created_at >= '" . $yearbefore . "'
                                AND am.`payment_status` != 5 AND am.`payment_status` != 6 AND am.`payment_status` != 7 AND am.`payment_status` != 8 
                                GROUP BY am.`am`";

        $fulltotal = \DB::select($sql);
        return $fulltotal;
    }

    public function checkForExpire($amName)
    {
        $today = date('Y-m-d');
        $sql = "SELECT  COUNT(am.`id`) AS 'member_count'
                                FROM `admin_members` AS am 
                                WHERE am.`type` = 'Expired' AND  am.`category` != 'Free' AND am.`category` IS NOT NULL AND  am.`category` != 'Single Ad' 
                                AND am.`am` != '' AND am.`am` IS NOT NULL AND am.`am` = '" . $amName . "'  AND am.payment_exp_date < '" . $today . "'
                                GROUP BY am.`am`";

        $expiredTotal = \DB::select($sql);
        return $expiredTotal;
    }

    public function checkAllForSystem()
    {
        $this_month = date('Y-m');
        $sql1 = "SELECT IF(am.`name` IS NOT NULL, am.`name`, IF(am.`name` != '',am.`name`, am.`am`)) AS 'am', SUM(gpd.`pending_amount`) AS 'pending_val', 
                        SUM(gpd.`invoiced_amount`) AS 'total_invoiced_amount', SUM(gpd.`paid_amount`) AS 'total_paid_amount',
                        IFNULL(am.`member_count`,0) AS 'member_count', IFNULL(am.`revnue_total`, 0) AS 'revnue_total' 
                        FROM `google_payment_data` AS gpd 
                        LEFT JOIN (
                            SELECT am.`name`, am.`am`, SUM(am.`member_count`) AS 'member_count', SUM(am.`revnue_total`) AS 'revnue_total'
                            FROM (
                                SELECT au.`name`, am.`am`, COUNT(am.`id`) AS 'member_count', am.`category`, (COUNT(am.`id`) * amp.`monthly_payment`) AS 'revnue_total'
                                FROM `admin_members` AS am 
                                LEFT JOIN `admin_users` AS au ON au.`username` = am.`am`
                                LEFT JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                                WHERE am.`type` = 'Member' AND am.`category` != 'Free' AND am.`category` IS NOT NULL
                                AND am.`am` != '' AND am.`am` IS NOT NULL 
                                GROUP BY am.`am`, am.`category`
                            ) AS am 
                            GROUP BY am.`am`
                        ) AS am ON am.`am` = 'System' 
                WHERE gpd.`due_date` LIKE '" . $this_month . "%' AND am.`am` IS NOT NULL AND am.`am` != '' 
                GROUP BY gpd.`am` ";
        $system_payment_sql = \DB::select($sql1);
        return $system_payment_sql;
    }

    public function numberFormatPaid($number)
    {
        $output = $number;
        if (intval($number / 1000000) > 0) {
            $output = number_format(floatval($number / 1000000), 0, '.', ',') . "M";
        } else if (intval($number / 1000) > 0) {
            $output = number_format(floatval($number / 1000), 0, '.', ',') . "K";
        }
        return $output;
    }
}