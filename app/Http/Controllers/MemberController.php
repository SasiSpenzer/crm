<?php

namespace App\Http\Controllers;

use App\Contracts\MemberInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Member;
use App\User;
use Datatables;
use DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class MemberController extends Controller {

	protected $member;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MemberInterface $member) {
		$this->middleware('auth');
		$this->member = $member;
	}

	/**
	 * Process save request.
	 *
	 * @return string
	 */
	public function save(Request $request) {
		$payload = [
			"uid" => $request->input('uid'),
			"type" => $request->input('type'),
			"category" => $request->input('category'),
			"duration" => $request->input('duration'),
			"payment" => $request->input('payment'),
			"am" => $request->input('am'),
			"mobile_nos" => $request->input('mobile_nos'),
			"company" => $request->input('company'),
			"active_ads" => $request->input('active_ads'),
			"leads" => $request->input('leads'),
			"call_date_time" => $request->input('call_date_time'),
			"payment_exp_date" => $request->input('call_date_time'),
			"remarks" => $request->input('remarks'),
			"expiry" => $request->input('expiry'),
			"package_amount" => $request->input('package_amount'),
			"custom_amount" => $request->input('custom_amount'),
			"memship_status" => $request->input('memship_status'),
		];

		if($request->input('am') != null){
			$am = User::select('am_status')->where('username', $request->input('am'))->first();
			if (isset($am->am_status) && ($am->am_status == 1 || $am->am_status == 2)){
				$member = Member::select(['id', 'am', 'welcome_email'])->where('uid', $request->input('uid'))->first();
				//to detect welcome email has been sent or not
				//dd($member->welcome_email);
                if (isset($member) ){
					if ($member->welcome_email == null)
						$member->update(['welcome_email' => 0]);
				}else{
					$payload['welcome_email'] = '0';
				}
			}
		}

		// $success = $this->member->save($payload);
		$success = ($this->member->save($payload)) ? "true" : "false";

		return $success;
	}

    /**
     * For membership profile data save
     * @param Request $request
     * @return mixed
     */
	public function membershipSave(Request $request)
    {
        try {
            //Input data get
            $membership_uid = $request->input('membership_uid');
            $membership_status = $request->input('membership_status');
            $member_contact = $request->input('membership_contact_num');
            $old_member_contact = $request->input('old_membership_contact_num');
            //$membership_email = $request->input('membership_email');
            $old_company_name = $request->input('old_company_name');
            $company_name = $request->input('company_name');
            $linkin_profile = $request->input('linkin_profile');
            $old_linkin_profile = $request->input('old_linkin_profile');
            $old_user_image = $request->input('old_user_image');
            $is_active_auto_boost = $request->input('is_active_auto_boost');
            $auto_boost_for_new_ads = $request->input('auto_boost_for_new_ads');
            $old_is_active_auto_boost = $request->input('old_is_active_auto_boost');
            $old_auto_boost_for_new_ads = $request->input('old_auto_boost_for_new_ads');
            $is_change_user_profile = 0;
            //$latest_comment = $request->input('latest_comment');
            $filename = '';
            $user_image_path = '';
            //For file upload
            if($request->hasfile('user_image'))
            {
                $file = $request->file('user_image');
                $extension = $file->getClientOriginalExtension();
                $filename = $membership_uid . '-' . time().'.'.$extension;
                $file->move('uploads/', $filename);
            }
            if($filename != ''){
                $user_image_path = url('/uploads/'.$filename);
            } else {
                $user_image_path = $old_user_image;
            }

            //For check user profile has changers
            if($member_contact != $old_member_contact || $old_company_name != $company_name || $linkin_profile != $old_linkin_profile ||
            $old_user_image != $user_image_path || $is_active_auto_boost != '' || $auto_boost_for_new_ads != '') {
                $is_change_user_profile = 1;
            }

            $payload = [
                            "membership_uid" => $membership_uid,
                            "membership_status" => $membership_status,
                            "membership_contact" => $member_contact,
                            //"membership_email" => $membership_email,
                            "company_name" => $company_name,
                            "linkin_profile" => $linkin_profile,
                            //"latest_comment" => $latest_comment,
                            "user_image" => $user_image_path,
                            "is_change_user_profile" => $is_change_user_profile,
                            "old_company_name" => $old_company_name,
                            "old_membership_contact" => $old_member_contact,
                            "old_linkin_profile" => $old_linkin_profile,
                            "old_user_image" => $old_user_image,
                            "is_active_auto_boost" => $is_active_auto_boost,
                            "auto_boost_for_new_ads" => $auto_boost_for_new_ads,
                            "old_is_active_auto_boost" => $old_is_active_auto_boost,
                            "old_auto_boost_for_new_ads" => $old_auto_boost_for_new_ads
                        ];


            //dd("test", $request->hasfile('user_image'));

            $output = $this->member->save_membership($payload);
        } catch(\Exception $e){
            $output['status'] = 'Failed';
            $output['is_view_msg'] = 1;
            $output['description'] = $e;
            $output['member_img'] = '';
        }

        return $output;
    }

	/**
	 * Get members count by category
	 * @return string
	 */
	public function getCategoryWiseMemberCount() {
		return $this->member->getCategoryWiseMemberCount();
	}

    /**
     * Membership details save
     * @param Request $request
     * @return false|string|void
     */
	public function membershipDetailsSave(Request $request)
    {
        //Input data get
        $membership_uid = $request->input('membership_uid');
        $old_user_type = $request->input('old_user_type');
        $user_type = $request->input('user_type');
        $old_membership_type = $request->input('old_membership_type');
        $membership_type = $request->input('membership_type');
        $old_membership_category = $request->input('old_membership_category');
        $membership_category = $request->input('membership_category');
        $old_package_amount = $request->input('old_package_amount');
        $package_amount = $request->input('package_amount');
        $old_membership_payment = $request->input('old_membership_payment');
        $membership_payment = $request->input('membership_payment');
        $old_membership_duration = $request->input('old_membership_duration');
        $membership_duration = $request->input('membership_duration');
        $old_membership_call_date = $request->input('old_membership_call_date');
        $membership_call_date = $request->input('membership_call_date');
        $old_membership_expiry_date = $request->input('old_membership_expiry_date');
        $membership_expiry_date = $request->input('membership_expiry_date');
        $old_membership_active_add_count = $request->input('old_membership_active_add_count');
        $membership_active_add_count = $request->input('membership_active_add_count');
        $old_membership_leads_count = $request->input('old_membership_leads_count');
        $membership_leads_count = $request->input('membership_leads_count');
        $old_membership_am = $request->input('old_membership_am');
        $membership_am = $request->input('membership_am');
        $old_pending_amount = $request->input('old_pending_amount');
        $pending_amount = $request->input('pending_amount');
        $membership_comment = $request->input('membership_comment');
        $old_phone_restrictions = $request->input('old_phone_restrictions');
        $phone_restrictions = $request->input('phone_restrictions');
        $only_generate_url = 0;

        if(($old_user_type != $user_type) || ($old_membership_type != $membership_type) || ($old_membership_category != $membership_category) ||
            ($old_package_amount != $package_amount) || ($old_membership_payment != $membership_payment) ||
            ($old_membership_duration != $membership_duration) || ($old_membership_call_date != $membership_call_date) ||
            ($old_membership_expiry_date != $membership_expiry_date) || ($old_membership_active_add_count != $membership_active_add_count) ||
            ($old_membership_leads_count != $membership_leads_count) || ($old_membership_am != $membership_am) || ($pending_amount != $old_pending_amount) /*||
            ($old_phone_restrictions != $phone_restrictions)*/
        ){
            $payload = [
                "membership_uid" => $membership_uid,
                "old_user_type" => $old_user_type,
                "user_type" => $user_type,
                "old_membership_type" => $old_membership_type,
                "membership_type" => $membership_type,
                "old_membership_category" => $old_membership_category,
                "membership_category" => $membership_category,
                "old_package_amount" => $old_package_amount,
                "package_amount" => $package_amount,
                "old_membership_payment" => $old_membership_payment,
                "membership_payment" => $membership_payment,
                "old_membership_duration" => $old_membership_duration,
                "membership_duration" => $membership_duration,
                "old_membership_call_date" => $old_membership_call_date,
                "membership_call_date" => $membership_call_date,
                "old_membership_expiry_date" => $old_membership_expiry_date,
                "membership_expiry_date" => $membership_expiry_date,
                "old_membership_active_add_count" => $old_membership_active_add_count,
                "membership_active_add_count" => $membership_active_add_count,
                "old_membership_leads_count" => $old_membership_leads_count,
                "membership_leads_count" => $membership_leads_count,
                "old_membership_am" => $old_membership_am,
                "membership_am" => $membership_am,
                "old_pending_amount" => $old_pending_amount,
                "pending_amount" => $pending_amount,
                "membership_comment" => $membership_comment,
                "only_generate_url" => $only_generate_url,
                "old_phone_restrictions" => $old_phone_restrictions,
                "new_phone_restrictions" => $phone_restrictions,
            ];
            $output = $this->member->save_membershipDetails($payload);
        } else {
            if(($old_phone_restrictions != $phone_restrictions)){
                //login user data
                $login_user_data = \Auth::user();
                $admin_level = intval($login_user_data->admin_level);
                if ($admin_level > 2){
                    $payload = [
                        "membership_uid" => $membership_uid,
                        "old_user_type" => $old_user_type,
                        "user_type" => $user_type,
                        "old_membership_type" => $old_membership_type,
                        "membership_type" => $membership_type,
                        "old_membership_category" => $old_membership_category,
                        "membership_category" => $membership_category,
                        "old_package_amount" => $old_package_amount,
                        "package_amount" => $package_amount,
                        "old_membership_payment" => $old_membership_payment,
                        "membership_payment" => $membership_payment,
                        "old_membership_duration" => $old_membership_duration,
                        "membership_duration" => $membership_duration,
                        "old_membership_call_date" => $old_membership_call_date,
                        "membership_call_date" => $membership_call_date,
                        "old_membership_expiry_date" => $old_membership_expiry_date,
                        "membership_expiry_date" => $membership_expiry_date,
                        "old_membership_active_add_count" => $old_membership_active_add_count,
                        "membership_active_add_count" => $membership_active_add_count,
                        "old_membership_leads_count" => $old_membership_leads_count,
                        "membership_leads_count" => $membership_leads_count,
                        "old_membership_am" => $old_membership_am,
                        "membership_am" => $membership_am,
                        "old_pending_amount" => $old_pending_amount,
                        "pending_amount" => $pending_amount,
                        "membership_comment" => $membership_comment,
                        "only_generate_url" => $only_generate_url,
                        "old_phone_restrictions" => $old_phone_restrictions,
                        "new_phone_restrictions" => $phone_restrictions,
                    ];
                    $output = $this->member->save_membershipDetails($payload);
                } else {
                    $output['status'] = 'Succeed';
                    $output['is_view_msg'] = 0;
                    $output['description'] = "No any data changers";
                    $output['admin_level'] = 0;
                }

            } else {
                $output['status'] = 'Succeed';
                $output['is_view_msg'] = 0;
                $output['description'] = "No any data changers";
                $output['admin_level'] = 0;
            }
        }
        return json_encode($output);

    }


    /**
     * Get customer approval details data
     * @param integer $approvalId
     * @param string $secret_key
     * @return mixed
     */
    public function membershipDataApproval($approvalId){

        if($approvalId > 0) {
            $output = $this->member->membershipDataApproval($approvalId);
//            $output['status'] = "success";
//            $output['msg'] = "Membership approval successfully.";
        } else {
            $output['status'] = "error";
            $output['msg'] = "Didn't pass data correctly.";
        }
        return redirect('/dashboard')->with($output['status'],$output['msg']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function memberDashboardView()
    {
        return view('member.member_dashboard');
    }

    public function memberDashboardData(Request $request)
    {
        $data = $request->input();
        $search_data = "";
        $membership = " ";
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $membership .= "WHERE (mdl.username LIKE '%" . $search_data . "%' OR mdl.category LIKE '%" . $search_data . "%' 
                            OR mdl.am LIKE '%" . $search_data . "%'  OR mdl.Uemail LIKE '%" . $search_data . "%' 
                            OR ut.`user_type` LIKE '%".$search_data."%') AND am.`category` != 'Single Ad' AND am.`category` != 'Free' AND am.`category` != ' ' AND am.`category` IS NOT NULL";
        } else {
            $membership .= "WHERE am.`category` != 'Single Ad' AND am.`category` != ' ' AND am.`category` != 'Free'  AND am.`category` IS NOT NULL";
        }

        /*$sql = 'SELECT mdl.`UID`, mdl.`Uemail`, mdl.`username`, mdl.`ads_count`, mdl.`category`, mdl.`member_since`, mdl.`membership_exp_date`, mdl.`payment_exp_date`, mdl.`am`, mdl.`max_page_id`, mdl.`pic_percentage`
                , mdl.`ad_upgrade_count`, mdl.`boosts_left`, mdl.`total_stats`, mdl.`views_percentage`, mdl.`leads_percentage`, mdl.`ad_hits`, mdl.`status`, mdl.`status_img`, mdl.`class`
                FROM `member_dashboard_log_data` AS mdl'
                .$membership;*/
        $sql = "SELECT DISTINCT mdl.`UID`, mdl.`Uemail`, mdl.`username`,mdl.`ads_count` AS 'ads_count', 
                am.`category`, am.`member_since`, am.`expiry` AS 'membership_exp_date', am.`payment_exp_date`, am.`am`, 
                mdl.`max_page_id` AS 'max_page_id', FORMAT(mdl.`pic_percentage`, 2) AS 'pic_percentage',  
                mdl.`ad_upgrade_count` AS 'ad_upgrade_count', mdl.`boosts_left` AS 'boosts_left', 
                mdl.`total_stats` AS 'total_stats', FORMAT(mdl.`views_percentage`,2) AS'views_percentage', 
                FORMAT(mdl.`leads_percentage`,2) AS 'leads_percentage', mdl.`ad_hits` AS 'ad_hits', 
                mdl.`status`, mdl.`leeds_status`, mdl.`status_img`, mdl.`leeds_status_img`, mdl.`class`, ut.`user_type` 
                FROM `member_dashboard_log_data` AS mdl
                LEFT JOIN `admin_members` AS am ON am.`uid` = mdl.`UID` 
                LEFT JOIN `user_type` AS ut ON ut.`user_type_id` = am.`user_type_id` "
                .$membership . " ORDER BY cast(mdl.`total_stats` as SIGNED) DESC";
        // Added Order
        //echo $sql; exit();
        $output = DB::select($sql);
        return Datatables::of($output)->make(true);
    }

    public function membershipPendingApprovalSave(Request $request)
    {
        try {
            //$data = $request->input();

            //Input data get
            $membership_uid = $request->input('membership_uid');
            $old_user_type = $request->input('old_user_type');
            $user_type = $request->input('user_type');
            $old_membership_type = $request->input('old_membership_type');
            $membership_type = $request->input('membership_type');
            $old_membership_category = $request->input('old_membership_category');
            $membership_category = $request->input('membership_category');
            $old_package_amount = $request->input('old_package_amount');
            $package_amount = $request->input('package_amount');
            $old_membership_payment = $request->input('old_membership_payment');
            $membership_payment = $request->input('membership_payment');
            $old_membership_duration = $request->input('old_membership_duration');
            $membership_duration = $request->input('membership_duration');
            $old_membership_call_date = $request->input('old_membership_call_date');
            $membership_call_date = $request->input('membership_call_date');
            $old_membership_expiry_date = $request->input('old_membership_expiry_date');
            $membership_expiry_date = $request->input('membership_expiry_date');
            $old_membership_active_add_count = $request->input('old_membership_active_add_count');
            $membership_active_add_count = $request->input('membership_active_add_count');
            $old_membership_leads_count = $request->input('old_membership_leads_count');
            $membership_leads_count = $request->input('membership_leads_count');
            $old_membership_am = $request->input('old_membership_am');
            $membership_am = $request->input('membership_am');
            $old_pending_amount = $request->input('old_pending_amount');
            $pending_amount = $request->input('pending_amount');
            $membership_comment = $request->input('membership_comment');
            $old_phone_restrictions = $request->input('old_phone_restrictions');
            $phone_restrictions= $request->input('phone_restrictions');
            $only_generate_url = 1;

            $payload = [
                "membership_uid" => $membership_uid,
                "old_user_type" => $old_user_type,
                "user_type" => $user_type,
                "old_membership_type" => $old_membership_type,
                "membership_type" => $membership_type,
                "old_membership_category" => $old_membership_category,
                "membership_category" => $membership_category,
                "old_package_amount" => $old_package_amount,
                "package_amount" => $package_amount,
                "old_membership_payment" => $old_membership_payment,
                "membership_payment" => $membership_payment,
                "old_membership_duration" => $old_membership_duration,
                "membership_duration" => $membership_duration,
                "old_membership_call_date" => $old_membership_call_date,
                "membership_call_date" => $membership_call_date,
                "old_membership_expiry_date" => $old_membership_expiry_date,
                "membership_expiry_date" => $membership_expiry_date,
                "old_membership_active_add_count" => $old_membership_active_add_count,
                "membership_active_add_count" => $membership_active_add_count,
                "old_membership_leads_count" => $old_membership_leads_count,
                "membership_leads_count" => $membership_leads_count,
                "old_membership_am" => $old_membership_am,
                "membership_am" => $membership_am,
                "old_pending_amount" => $old_pending_amount,
                "pending_amount" => $pending_amount,
                "membership_comment" => $membership_comment,
                "only_generate_url" => $only_generate_url,
                "old_phone_restrictions" => $old_phone_restrictions,
                "new_phone_restrictions" => $phone_restrictions,
            ];
            $output = $this->member->save_membershipDetails($payload);
        } catch (\Exception $e) {
            //dd($e);
            $output['status'] = 'Failed';
            $output['description'] = $e;

        }
        return json_encode($output);
    }

    /**
     * Get customer approval details data
     * @param integer $approvalId
     * @param string $secret_key
     * @return mixed
     */
    public function membershipPaymentApproval($approvalId){

        if($approvalId > 0) {
            $output = $this->member->membershipDataApproval($approvalId);
//            $output['status'] = "success";
//            $output['msg'] = "Membership approval successfully.";
        } else {
            $output['status'] = "error";
            $output['msg'] = "Didn't pass data correctly.";
        }
        return redirect('/dashboard')->with($output['status'],$output['msg']);
    }

    /**
     * For view agent activity data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function agentActivity(){
        $today = date("Y-m-d");
        $output['user_email'] = '';
        $output['header'] = date('Y', strtotime($today)) . " " .date('F', strtotime($today)) . " Agent Activity";
        $output['month_q1'] = date('F', strtotime($today));
        $output['month_q2'] = date('F', strtotime($today . ' - 1 month'));
        $output['month_q3'] = date('F', strtotime($today . ' - 2 month'));
        return view('member.agent_activity',$output);
    }

    /**
     * Get activity user data
     * @param Request $request
     * @return mixed
     */
    public function agentActivityData(Request $request){
        $data = $request->input('email');
        $membership = " ";
        if(isset($data) && $data != null ) {
            $sql = "SELECT u.`UID`, u.`firstname`, u.`surname`, u.`Uemail`, am.`category`, am.`am`, am.`expiry`, am.`payment_exp_date`,'-' AS 'test'
                    FROM `users` AS u
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    WHERE u.`Uemail` = '".$data."' 
                    LIMIT 1";
            $output = DB::select($sql);

            $uid = isset($output[0]) ? intval($output[0]->UID):0;
            if($uid > 0) {
                $today = date("Y-m-d");
                $date = intval(date('d', strtotime($today)));
                if($date < 30) {
                    $this_month = date('m', strtotime($today));
                    $month_q1 = date('Y-m', strtotime($today));
                    $date_year_q1 = date('Y', strtotime($today));
                    $date_month_q1 = date('m', strtotime($today));
                    $month_q2 = date('Y-m', strtotime($today . ' - 1 month'));
                    $date_year_q2 = date('Y', strtotime($today . ' - 1 month'));
                    $date_month_q2 = date('m', strtotime($today . ' - 1 month'));
                    $month_q3 = date('Y-m', strtotime($today . ' - 61 month'));
                    $date_year_q3 = date('Y', strtotime($today . ' - 61 month'));
                    $date_month_q3 = date('m', strtotime($today . ' - 61 month'));
                } else {
                    $this_month = date('m', strtotime($today));
                    $month_q1 = date('Y-m', strtotime($today));
                    $date_year_q1 = date('Y', strtotime($today));
                    $date_month_q1 = date('m', strtotime($today));
                    $month_q2 = date('Y-m', strtotime($today . ' - 1 month'));
                    $date_year_q2 = date('Y', strtotime($today . ' - 1 month'));
                    $date_month_q2 = date('m', strtotime($today . ' - 1 month'));
                    $month_q3 = date('Y-m', strtotime($today . ' - 2 month'));
                    $date_year_q3 = date('Y', strtotime($today . ' - 2 month'));
                    $date_month_q3 = date('m', strtotime($today . ' - 2 month'));
                }
                //For get this month post ad count
                $sql1 = "SELECT a.`UID`, COUNT(a.`ad_id`) AS 'post_count'
                            FROM `adverts` AS a
                            WHERE (a.`posted_date` LIKE '".$month_q1."%') AND a.`UID` = ".$uid." 
                            GROUP BY a.`UID`
                            ORDER BY a.`UID`";
                $output_post_data_1 = DB::select($sql1);
                $post_count_q1 = isset($output_post_data_1[0])?intval($output_post_data_1[0]->post_count):0;
                $output[0]->post_count_q1 = $post_count_q1;

                //For get this before month post ad count
                $sql2 = "SELECT a.`UID`, COUNT(a.`ad_id`) AS 'post_count'
                            FROM `adverts` AS a
                            WHERE (a.`posted_date` LIKE '".$month_q2."%') AND a.`UID` = ".$uid." 
                            GROUP BY a.`UID`
                            ORDER BY a.`UID`";
                $output_post_data_2 = DB::select($sql2);
                $post_count_q2 = isset($output_post_data_2[0])?intval($output_post_data_2[0]->post_count):0;
                $output[0]->post_count_q2 = $post_count_q2;

                //For get this before two month post ad count
                $sql3 = "SELECT a.`UID`, COUNT(a.`ad_id`) AS 'post_count'
                            FROM `adverts` AS a
                            WHERE (a.`posted_date` LIKE '".$month_q3."%') AND a.`UID` = ".$uid." 
                            GROUP BY a.`UID`
                            ORDER BY a.`UID`";
                $output_post_data_3 = DB::select($sql3);
                $post_count_q3 = isset($output_post_data_3[0])?intval($output_post_data_3[0]->post_count):0;
                $output[0]->post_count_q3 = $post_count_q3;

                //For get this this month edit ad count
                $sql4 = "SELECT aed.`user_id`, COUNT(aed.`ads_edit_data_id`) AS 'edit_count' 
                            FROM `ads_edit_data` AS aed 
                            WHERE (aed.`create_at` LIKE '".$month_q1."%') AND aed.`user_id` = ".$uid." 
                            GROUP BY aed.`user_id`
                            ORDER BY aed.`user_id`";
                $output_edit_data_1 = DB::select($sql4);
                $edit_count_q1 = isset($output_edit_data_1[0])?intval($output_edit_data_1[0]->edit_count):0;
                $output[0]->edit_count_q1 = $edit_count_q1;

                //For get this before month edit ad count
                $sql5 = "SELECT aed.`user_id`, COUNT(aed.`ads_edit_data_id`) AS 'edit_count' 
                            FROM `ads_edit_data` AS aed 
                            WHERE (aed.`create_at` LIKE '".$month_q2."%') AND aed.`user_id` = ".$uid." 
                            GROUP BY aed.`user_id`
                            ORDER BY aed.`user_id`";
                $output_edit_data_2 = DB::select($sql5);
                $edit_count_q2 = isset($output_edit_data_2[0])?intval($output_edit_data_2[0]->edit_count):0;
                $output[0]->edit_count_q2 = $edit_count_q2;

                //For get this before two month edit ad count
                $sql6 = "SELECT aed.`user_id`, COUNT(aed.`ads_edit_data_id`) AS 'edit_count' 
                            FROM `ads_edit_data` AS aed 
                            WHERE (aed.`create_at` LIKE '".$month_q3."%') AND aed.`user_id` = ".$uid." 
                            GROUP BY aed.`user_id`
                            ORDER BY aed.`user_id`";
                $output_edit_data_3 = DB::select($sql6);
                $edit_count_q3 = isset($output_edit_data_3[0])?intval($output_edit_data_3[0]->edit_count):0;
                $output[0]->edit_count_q3 = $edit_count_q3;

                //For get this month boost ad count
                $sql7 = "SELECT ca.`UID`, SUM(ca.`txn_credit_amount`) AS 'boost_count'
                            FROM `credit_acc_payment_log` AS ca
                            WHERE (ca.`txn_date` LIKE '".$month_q1."%') AND ca.`UID` = ".$uid." 
                            GROUP BY ca.`UID`
                            ORDER BY ca.`UID`";
                $output_boost_data_1 = DB::select($sql7);
                $boost_count_q1 = isset($output_boost_data_1[0])?intval($output_boost_data_1[0]->boost_count):0;
                $output[0]->boost_count_q1 = $boost_count_q1;

                //For get this before month boost ad count
                $sql8 = "SELECT ca.`UID`, SUM(ca.`txn_credit_amount`) AS 'boost_count'
                            FROM `credit_acc_payment_log` AS ca
                            WHERE (ca.`txn_date` LIKE '".$month_q2."%') AND ca.`UID` = ".$uid." 
                            GROUP BY ca.`UID`
                            ORDER BY ca.`UID`";
                $output_boost_data_2 = DB::select($sql8);
                $boost_count_q2 = isset($output_boost_data_2[0])?intval($output_boost_data_2[0]->boost_count):0;
                $output[0]->boost_count_q2 = $boost_count_q2;

                //For get this before two month boost ad count
                $sql9 = "SELECT ca.`UID`, SUM(ca.`txn_credit_amount`) AS 'boost_count'
                            FROM `credit_acc_payment_log` AS ca
                            WHERE (ca.`txn_date` LIKE '".$month_q3."%') AND ca.`UID` = ".$uid." 
                            GROUP BY ca.`UID`
                            ORDER BY ca.`UID`";
                $output_boost_data_3 = DB::select($sql9);
                $boost_count_q3 = isset($output_boost_data_3[0])?intval($output_boost_data_3[0]->boost_count):0;
                $output[0]->boost_count_q3 = $boost_count_q3;

                //For get this month paid ad count
                $sql10 = "SELECT a.`UID`, COUNT(p.`id`) AS 'paid_count'
                            FROM `pp_payments` AS p
                            INNER JOIN `adverts` AS a ON a.`ad_id` = p.`ad_id` 
                            WHERE (p.`IPN_ID` IS NOT NULL) AND p.`IPN_ID` != '' AND (p.`paid_date` LIKE '".$month_q1."%') AND a.`UID` = ".$uid." 
                            GROUP BY a.`UID`
                            ORDER BY a.`UID`";
                $output_paid_data_1 = DB::select($sql10);
                $paid_count_q1 = isset($output_paid_data_1[0])?intval($output_paid_data_1[0]->paid_count):0;
                $output[0]->paid_count_q1 = $paid_count_q1;

                //For get before month paid ad count
                $sql11 = "SELECT a.`UID`, COUNT(p.`id`) AS 'paid_count'
                            FROM `pp_payments` AS p
                            INNER JOIN `adverts` AS a ON a.`ad_id` = p.`ad_id` 
                            WHERE (p.`IPN_ID` IS NOT NULL) AND p.`IPN_ID` != '' AND (p.`paid_date` LIKE '".$month_q2."%') AND a.`UID` = ".$uid." 
                            GROUP BY a.`UID`
                            ORDER BY a.`UID`";
                $output_paid_data_2 = DB::select($sql11);
                $paid_count_q2 = isset($output_paid_data_2[0])?intval($output_paid_data_2[0]->paid_count):0;
                $output[0]->paid_count_q2 = $paid_count_q2;

                //For get before two month paid ad count
                $sql12 = "SELECT a.`UID`, COUNT(p.`id`) AS 'paid_count'
                            FROM `pp_payments` AS p
                            INNER JOIN `adverts` AS a ON a.`ad_id` = p.`ad_id` 
                            WHERE (p.`IPN_ID` IS NOT NULL) AND p.`IPN_ID` != '' AND (p.`paid_date` LIKE '".$month_q3."%') AND a.`UID` = ".$uid." 
                            GROUP BY a.`UID`
                            ORDER BY a.`UID`";
                $output_paid_data_3 = DB::select($sql12);
                $paid_count_q3 = isset($output_paid_data_3[0])?intval($output_paid_data_3[0]->paid_count):0;
                $output[0]->paid_count_q3 = $paid_count_q3;

                //For get before this month leads & views count
                $sql13 = "SELECT vmd.`user_id`, SUM(vmd.`views_month_data_id`) AS 'views_count'
                            FROM `views_month_data` AS vmd 
                            WHERE vmd.`user_id` = ".$uid." AND vmd.`year` = ".$date_year_q1." AND vmd.`month` = ".$date_month_q1." 
                            GROUP BY vmd.`user_id` 
                            ORDER BY vmd.`user_id`";
                $output_stat_data_1 = DB::select($sql13);
                $sql13_a = "SELECT lmd.`user_id`, COUNT(lmd.`leads_month_data_id`) AS 'leads_count' 
                            FROM `leads_month_data` AS lmd 
                            WHERE lmd.`user_id` = ".$uid." AND lmd.`created_at` LIKE '".$month_q1."%' 
                            GROUP BY lmd.`user_id` 
                            ORDER BY lmd.`user_id` ";
                $output_stat_data_1_a = DB::select($sql13_a);
                $views_count_q1 = isset($output_stat_data_1[0])?intval($output_stat_data_1[0]->views_count):0;
                $leads_count_q1 = isset($output_stat_data_1_a[0])?intval($output_stat_data_1_a[0]->leads_count):0;
                $output[0]->views_count_q1 = $views_count_q1;
                $output[0]->leads_count_q1 = $leads_count_q1;

                //For get before before month leads & views count
                $sql14 = "SELECT vmd.`user_id`, SUM(vmd.`views_month_data_id`) AS 'views_count'
                            FROM `views_month_data` AS vmd 
                            WHERE vmd.`user_id` = ".$uid." AND vmd.`year` = ".$date_year_q1." AND vmd.`month` = ".$date_month_q1." 
                            GROUP BY vmd.`user_id` 
                            ORDER BY vmd.`user_id`";
                $output_stat_data_2 = DB::select($sql14);
                $sql14_a = "SELECT lmd.`user_id`, COUNT(lmd.`leads_month_data_id`) AS 'leads_count' 
                            FROM `leads_month_data` AS lmd 
                            WHERE lmd.`user_id` = ".$uid." AND lmd.`created_at` LIKE '".$month_q1."%' 
                            GROUP BY lmd.`user_id` 
                            ORDER BY lmd.`user_id` ";
                $output_stat_data_2_a = DB::select($sql14_a);
                $views_count_q2 = isset($output_stat_data_2[0])?intval($output_stat_data_2[0]->views_count):0;
                $leads_count_q2 = isset($output_stat_data_2_a[0])?intval($output_stat_data_2_a[0]->leads_count):0;
                $output[0]->views_count_q2 = $views_count_q2;
                $output[0]->leads_count_q2 = $leads_count_q2;

                //For get before before two month leads & views count
                $sql15 = "SELECT vmd.`user_id`, SUM(vmd.`views_month_data_id`) AS 'views_count'
                            FROM `views_month_data` AS vmd 
                            WHERE vmd.`user_id` = ".$uid." AND vmd.`year` = ".$date_year_q1." AND vmd.`month` = ".$date_month_q1." 
                            GROUP BY vmd.`user_id` 
                            ORDER BY vmd.`user_id`";
                $output_stat_data_3 = DB::select($sql15);
                $sql15_a = "SELECT lmd.`user_id`, COUNT(lmd.`leads_month_data_id`) AS 'leads_count' 
                            FROM `leads_month_data` AS lmd 
                            WHERE lmd.`user_id` = ".$uid." AND lmd.`created_at` LIKE '".$month_q1."%' 
                            GROUP BY lmd.`user_id` 
                            ORDER BY lmd.`user_id` ";
                $output_stat_data_3_a = DB::select($sql15_a);
                $views_count_q3 = isset($output_stat_data_3[0])?intval($output_stat_data_3[0]->views_count):0;
                $leads_count_q3 = isset($output_stat_data_3_a[0])?intval($output_stat_data_3_a[0]->leads_count):0;
                $output[0]->views_count_q3 = $views_count_q3;
                $output[0]->leads_count_q3 = $leads_count_q3;

            }
            return Datatables::of($output)->make(true);
        } else {
            $sql = "SELECT u.`UID`, u.`firstname`, u.`surname`, u.`Uemail`, am.`category`, am.`am`, am.`expiry`, am.`payment_status`
                    FROM `users` AS u
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    WHERE u.`Uemail` = '%".$data."%' 
                    LIMIT 0";
            $output = DB::select($sql);
            return Datatables::of($output)->make(true);
        }
    }

    /**
     * Get user bulling data
     * @param Request $request
     * @return mixed
     */
    public function userBillingData(Request $request) {
        $uid = intval($request->input('billing_uid'));
        $sql = "SELECT gpd.`uid`, gpd.`invoice_number`, gpd.`invoiced_date`, FORMAT(gpd.`invoiced_amount`,2) AS 'invoiced_amount', gpd.`paid_date`, 
                FORMAT(gpd.`paid_amount`,2) AS 'paid_amount', FORMAT(gpd.`pending_amount`,2) AS 'pending_amount', `due_date`, `product_type`, `payment_method`,  
                (
                    CASE 
                        WHEN gpd.`invoiced_amount` > 0 AND gpd.`pending_amount` = 0 THEN 1
                        WHEN gpd.`invoiced_amount` > 0 AND gpd.`paid_amount` = 0 THEN 0
                        WHEN gpd.`invoiced_amount` > 0 AND gpd.`pending_amount` > 0 THEN 2
                        ELSE '3'
                    END
                ) AS 'status'
                FROM `google_payment_data` AS gpd 
                WHERE gpd.`uid` = " . $uid ;
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql .= " AND (gpd.`invoice_number` LIKE '%" . $search_data . "%')";
        }
        $output = DB::select($sql);
        return Datatables::of($output)->make(true);
    }

    /**
     * Get user bulling data
     * @param Request $request
     * @return mixed
     */
    public function userAddonsData(Request $request) {
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $today = date('Y-m-d');
        //$today = '2017-05-17';
        $thirty_days_before = date('Y-m-d', strtotime($today . ' - 30 days'));
        $uid = intval($request->input('addons_uid'));
        /*$sql = "SELECT d.`ad_data`, d.`created_date` AS 'created_at', d. `priority_name`
                FROM (
                    SELECT DISTINCT CONCAT(ua.`category`,' (Upgrade AD ID : ',a.`ad_id`, ')') AS 'ad_data', 
                        DATE(ua.`created_at`) AS 'created_date', aup.`priority_name`
                    FROM `upgraded_ads` AS ua 
                    INNER JOIN `adverts` AS a ON a.`ad_id` = ua.`ad_id`
                    INNER JOIN `ad_upgrade_package` AS aup ON aup.`priority_id` = ua.`priority` 
                    WHERE a.`UID` = " . $uid . " 
                    UNION ALL 
                    SELECT DISTINCT  
                    CASE
                        WHEN cau.`credit_type` = 0 THEN CONCAT(cau.`upgrade_amount`, ' boost (Package)')
                        ELSE CONCAT(cau.`upgrade_amount`, ' boost (Add-ons)')
                    END AS 'ad_data', DATE(cau.`last_upgrade`) AS 'created_date', 'Boost' AS 'priority_name'
                    FROM `credit_account` AS ca 
                    INNER JOIN `credit_account_upgrade` AS cau ON cau.`acc_id` = cau.`acc_id` 
                    WHERE ca.`UID` = " . $uid . " 
                ) AS d";*/
        $sql = "SELECT CONCAT(`upgrade_amount`, ' boost (Add-ons)') AS ad_data, last_upgrade AS created_date, last_upgraded_by AS priority_name,last_upgrade AS 'created_at' FROM `credit_account_upgrade` cau LEFT JOIN credit_account ca ON ca.acc_id = cau.acc_id WHERE ca.`UID` = " . $uid . " ORDER BY last_upgraded_by ASC ";
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql .= "WHERE ((d.`ad_data` LIKE '%" . $search_data . "%') OR 
                        (d.`priority_name` LIKE '%" . $search_data . "%') )";
        }
        //dd($sql);
        $output = DB::select($sql);
        return Datatables::of($output)->make(true);
    }

    public function myListTable() {
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $today = date('Y-m-d');
        $this_month = date('Y-m').'-01';
        $this_month_only = date('Y-m');
        $before_week = date('Y-m-d', strtotime($today . ' - 7 days'));
        $after_week = date('Y-m-d', strtotime($today . ' + 7 days'));
        $after_two_week = date('Y-m-d', strtotime($today . ' + 14 days'));
        $before_two_week = date('Y-m-d', strtotime($today . ' - 14 days'));

        $login_user_data = \Auth::user();
        $user_data = array();
        $user_data['full_name'] = $login_user_data->name;
        $user_data['am_name'] = $login_user_data->username;
        $user_data['am_status'] = intval($login_user_data->am_status);
        $user_data['admin_level'] = intval($login_user_data->admin_level);
        //For total members count get
        $sql = "SELECT am.`am`, IFNULL(COUNT(am.`id`),0) AS 'total_customer'
                FROM `admin_members` AS am 
                WHERE am.`am` = '".$user_data['am_name']."' AND am.`type` = 'Member'
                GROUP BY am.`am`";
        //For get rev & member target
        $sql1 = "SELECT SUM(au.`target`) AS 'target', SUM(au.`mem_target`) AS 'mem_target'
                FROM `admin_users` AS au " ;
        //For get current member count
        $sql2 = "SELECT COUNT(am.`id`) AS 'member_count'
                    FROM `admin_members` AS am 
                    WHERE am.`type` = 'Member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                    AND am.`category` != 'Trail'";

        if($user_data['admin_level'] < 3) {
            $sql1 .= "WHERE au.`username` = '".$user_data['am_name']."'";
            $sql2 .= " AND am.`am` = '".$user_data['am_name']."' 
                        GROUP BY am.`am`";
            $sql3 = "SELECT COUNT(cad.`conv_ad_data_id`) AS 'conv_mem_count'
                        FROM `admin_members` AS am
                        INNER JOIN `conv_ad_data` AS cad ON cad.`user_id` = am.`uid`
                        WHERE cad.`created_at` LIKE '".$this_month_only."%' AND am.`am` = '".$user_data['am_name']."' 
                        GROUP BY cad.`user_id`";
            $sql4 = "SELECT SUM(m.`membership_payment`) AS 'total_membership_payment'
                        FROM (
                            SELECT am.`category`,  COUNT(am.`id`) AS 'mebeship_count', 
                            (COUNT(am.`id`) * amp.`monthly_payment`) AS 'membership_payment' 
                            FROM `admin_members` AS am 
                            INNER JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                            WHERE am.`type` = 'Member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                            AND am.`category` != 'Trail' AND am.`am` = '".$user_data['am_name']."' 
                            GROUP BY am.`category`
                        ) AS m";

            $sql5 = "SELECT am.`am`, COUNT(ama.`id`) AS 'activity_count' 
                        FROM `admin_members_actions` AS ama 
                        INNER JOIN `admin_members` AS am ON am.`uid` = ama.`uid`
                        WHERE am.`am` = '".$user_data['am_name']."' AND ama.`created_at` >= '".$today."' 
                        GROUP BY am.`am` ";

            $sql6 = "SELECT am.`am`, COUNT(ama.`id`) AS 'activity_count' 
                        FROM `admin_members_actions` AS ama 
                        INNER JOIN `admin_members` AS am ON am.`uid` = ama.`uid`
                        WHERE am.`am` = '".$user_data['am_name']."' AND ama.`created_at` >= '".$before_week."' 
                        GROUP BY am.`am` ";

            $sql7 = "SELECT COUNT(am.`id`) AS 'expired_count' 
                        FROM `admin_members` AS am 
                        WHERE am.`type` != 'member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                        AND am.`category` != 'Trail' AND am.`am` = '".$user_data['am_name']."' ";

            $sql8 = "SELECT COUNT(am.`id`) AS 'lose_count'
                        FROM `admin_members` AS am 
                        WHERE am.`payment_exp_date` <= '".$after_two_week."' AND am.`payment_exp_date` >= '".$before_two_week."' 
                        AND am.`type` = 'Member' AND am.`am` = '".$user_data['am_name']."' AND am.`category` != 'Single Ad' 
                        AND am.`category` != 'Free' AND am.`category` != 'Trail'";

            $sql9 = "SELECT COUNT(am.`id`) AS 'graced_count' 
                        FROM `admin_members` AS am 
                        WHERE am.`type` = 'member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                        AND am.`category` != 'Trail' AND am.`am` = '".$user_data['am_name']."' AND 
                        am.`payment_exp_date` < '".$today."' AND  am.`payment_exp_date` >= '".$before_two_week."'";

            $sql10 = "SELECT COUNT(am.`id`) AS 'will_expire_count' 
                        FROM `admin_members` AS am 
                        WHERE am.`type` = 'member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                        AND am.`category` != 'Trail' AND am.`am` = '".$user_data['am_name']."' AND 
                        am.`payment_exp_date` > '".$today."' AND  am.`payment_exp_date` <= '".$after_week."'";

            $sql11 = "SELECT gpd.`am`, SUM(gpd.`invoiced_amount`) AS 'achieved_val', SUM(gpd.`pending_amount`) AS 'pending_val'
                        FROM `google_payment_data` AS gpd 
                        WHERE gpd.`due_date` LIKE '".$this_month_only."%' AND gpd.`am` = '".$user_data['am_name']."' 
                        GROUP BY gpd.`am`";
        } else {
            $sql3 = "SELECT COUNT(cad.`conv_ad_data_id`) AS 'conv_mem_count'
                        FROM `admin_members` AS am
                        INNER JOIN `conv_ad_data` AS cad ON cad.`user_id` = am.`uid`
                        WHERE cad.`created_at` LIKE '".$this_month_only."%' 
                        GROUP BY cad.`user_id`";

            $sql4 = "SELECT SUM(m.`membership_payment`) AS 'total_membership_payment'
                        FROM (
                            SELECT am.`category`,  COUNT(am.`id`) AS 'membership_count', 
                            (COUNT(am.`id`) * amp.`monthly_payment`) AS 'membership_payment' 
                            FROM `admin_members` AS am 
                            INNER JOIN `admin_member_packages` AS amp ON amp.`package_name` = am.`category`
                            WHERE am.`type` = 'Member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                            AND am.`category` != 'Trail' 
                            GROUP BY am.`category`
                        ) AS m";

            $sql5 = "SELECT am.`am`, COUNT(ama.`id`) AS 'activity_count' 
                        FROM `admin_members_actions` AS ama 
                        INNER JOIN `admin_members` AS am ON am.`uid` = ama.`uid` 
                        WHERE ama.`created_at` >= '".$today."' ";

            $sql6 = "SELECT am.`am`, COUNT(ama.`id`) AS 'activity_count' 
                        FROM `admin_members_actions` AS ama 
                        INNER JOIN `admin_members` AS am ON am.`uid` = ama.`uid` 
                        WHERE ama.`created_at` >= '".$before_week."' ";

            $sql7 = "SELECT COUNT(am.`id`) AS 'expired_count' 
                        FROM `admin_members` AS am 
                        WHERE am.`type` != 'member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                        AND am.`category` != 'Trail' ";

            $sql8 = "SELECT COUNT(am.`id`) AS 'lose_count'
                        FROM `admin_members` AS am 
                        WHERE am.`type` = 'Member' AND am.`payment_exp_date` <= '".$after_two_week."' 
                        AND am.`payment_exp_date` >= '".$before_two_week."' AND am.`category` != 'Single Ad' 
                        AND am.`category` != 'Free' AND am.`category` != 'Trail' ";

            $sql9 = "SELECT COUNT(am.`id`) AS 'graced_count' 
                        FROM `admin_members` AS am 
                        WHERE am.`type` = 'member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                        AND am.`category` != 'Trail'  AND 
                        am.`payment_exp_date` < '".$today."' AND  am.`payment_exp_date` >= '".$before_two_week."'";

            $sql10 = "SELECT COUNT(am.`id`) AS 'will_expire_count' 
                        FROM `admin_members` AS am 
                        WHERE am.`type` = 'member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                        AND am.`category` != 'Trail'  AND 
                        am.`payment_exp_date` > '".$today."' AND  am.`payment_exp_date` <= '".$after_week."'";

            $sql11 = "SELECT gpd.`am`, SUM(gpd.`invoiced_amount`) AS 'achieved_val', SUM(gpd.`pending_amount`) AS 'pending_val'
                        FROM `google_payment_data` AS gpd 
                        WHERE gpd.`due_date` LIKE '".$this_month_only."%'";

        }
        $total_customer_data = DB::select($sql);
        $user_data['total_customers'] = isset($total_customer_data[0])?$total_customer_data[0]->total_customer:0;
        $target_data = DB::select($sql1);
        $member_count = DB::select($sql2);
        $conv_count = DB::select($sql3);
        $potential_count = DB::select($sql4);
        $activity_count = DB::select($sql5);
        $last_week_activity_count = DB::select($sql6);
        $expired_count = DB::select($sql7);
        $likely_lose_count = DB::select($sql8);
        $graced_count = DB::select($sql9);
        $will_expire_count = DB::select($sql10);
        $achieved_value = DB::select($sql11);

        $user_data['mem_target'] = isset($target_data[0])?$this->numberFormat($target_data[0]->mem_target):0;
        $user_data['rev_target'] = isset($target_data[0])?$this->numberFormat($target_data[0]->target):0.00;
        $user_data['current_mem_count'] = isset($member_count[0])?$this->numberFormat($member_count[0]->member_count):0;
        $user_data['conv_mem_count'] = isset($conv_count[0])?$this->numberFormat($conv_count[0]->conv_mem_count):0;
        $user_data['potential_value'] = isset($potential_count[0])?
                                                $this->numberFormat($potential_count[0]->total_membership_payment):0.00;
        $user_data['activity_count'] = isset($activity_count[0])?$this->numberFormat($activity_count[0]->activity_count):0;
        $user_data['last_week_activity_count'] = isset($last_week_activity_count[0])?
                                                $this->numberFormat($last_week_activity_count[0]->activity_count):0;
        $user_data['expired_count'] = isset($expired_count[0])?$this->numberFormat($expired_count[0]->expired_count):0;
        $user_data['graced_count'] = isset($graced_count[0])?$this->numberFormat($graced_count[0]->graced_count):0;
        $user_data['will_expire_count'] = isset($will_expire_count[0])?$this->numberFormat($will_expire_count[0]->will_expire_count):0;
        $user_data['lose_count'] = isset($likely_lose_count[0])?$this->numberFormat($likely_lose_count[0]->lose_count):0;
        $user_data['likely_lose_percentage'] = isset($member_count[0]) && intval($member_count[0]->member_count) > 0?
            number_format((doubleval($likely_lose_count[0]->lose_count) / doubleval($member_count[0]->member_count)* 100), 0, '.',''):0;
        $user_data['achieved_value'] = isset($achieved_value[0])?$this->numberFormat($achieved_value[0]->achieved_val):0;
        $user_data['achieved_percentage'] =  '';
        //$user_data['achieved_percentage'] = isset($target_data[0]) && intval($target_data[0]->target) > 0?
        //    number_format((doubleval($achieved_value[0]->achieved_val) / doubleval($target_data[0]->target)* 100), 0, '.',''):0;


        /*$user_data['mem_potential'] = 'LKR 1.45M';
        $user_data['rev_achieved'] = 'LKR 650K';
        $user_data['today_updated'] = '20';
        $user_data['month_updated'] = '189';
        $user_data['deactivated_member'] = '15';
        $user_data['expired_customers'] = '5';
        $user_data['expired_week'] = '14';
        $user_data['lose_member'] = '12(8%)';*/
        return view('member.my_list_data',$user_data);
    }

    public function listHunters(){

        return view('member.hunter_list');
    }

    // By Sasi Spenzer 2021-10-27 ** WFH
    public function pendingPaymentSystem(){
        return view('member.pending_payment_system');
    }

    public function pvtsellersExp(){
        return view('member.pvt-sellers-exp');
    }

    public function pvtExpData(Request $request)
    {
        $data = $request->input();
        $login_user_data = \Auth::user();
        $nowAM = $login_user_data->username;
        $type = intval($request->query()['type']);
        $today = date('Y-m-d');
        $before_day_time = strtotime('-1 day', strtotime($today));
        $before_day_time = date('Y-m-d',$before_day_time);
        $before_three_month = strtotime('-3 month', strtotime($today));
        $before_three_month = date('Y-m-d',$before_three_month);

        $before_14_days = strtotime('-14 days', strtotime($today));
        $before_14_days = date('Y-m-d',$before_14_days);

        $before_5_days = strtotime('-5 days', strtotime($today));
        $before_5_days = date('Y-m-d',$before_5_days);

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql =  "SELECT DISTINCT `adverts`.`UID` AS 'UID',
                            `adverts`.`ad_id` AS 'ad_id',
                            `adverts`.`propty_type` AS 'propty_type',
                            `adverts`.`type` AS 'type',
                            `adverts`.`posted_date` AS 'posted_date',
                            `adverts`.`submit_date` AS 'submit_date',
                            CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                            `admin_members`.`expiry` AS 'expiry',
                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                            `admin_members`.`am` AS 'am',
                            `admin_members`.`package_amount` AS 'package_amount',
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            `admin_members`.`latest_comment`,
                            DATE_FORMAT(`admin_members`.`updated_at`,'%Y-%m-%d') AS 'last_updated_at',
                            DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
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
                                WHEN `adverts`.`type` = 'sales'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'land'  AND `adverts`.`price` > 40000000
                                THEN 'yellow-class'
                                WHEN `adverts`.`type` = 'rentals'  AND `adverts`.`price_monthly` > 150000
                                THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bare Land' THEN 'yellow-class'
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts` 
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`
                        LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`";
            if($type == 3){
                $sql .= " INNER JOIN `admin_members_actions` ON `admin_members_actions`.`uid` = `adverts`.`UID`";
            }
            $sql .= " LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."'";

            if($type == 2){
                $sql .= " AND (`admin_members`.`type` != 'Member') AND  `admin_members`.`latest_commented_at` < '".$before_14_days."' AND `admin_members`.`category` = 'Single Ad' AND `admin_members`.`expiry` > '".$before_14_days."' AND
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%')";
            }
            else if ($type == 3) {
                $sql .= " AND admin_members_actions.by = '" . $nowAM . "' AND (`admin_members`.`type` != 'Member') AND `admin_members`.`category` = 'Single Ad' AND `admin_members`.`expiry` > '".$before_14_days."' AND
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%')";
            }
            else if($type == 4) {
                $sql .= " AND (`admin_members`.`type` != 'Member') AND  `admin_members`.`latest_commented_at` < '".$before_5_days."' AND `admin_members`.`category` = 'Single Ad' AND `admin_members`.`expiry` > '".$before_14_days."' AND
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%')";
            } else {

                $sql .= "AND (`admin_members`.`type` != 'Member') AND `admin_members`.`category` = 'Single Ad' AND `admin_members`.`expiry` > '".$before_14_days."' AND
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%')";
            }
            if($type == 5){
                $sql .= " ORDER BY `adverts`.`ad_id` DESC ";
            }
            $sql .= " GROUP BY `users`.`UID`";


             $customer =  $sql;
        } else {
            $customer =
                "SELECT DISTINCT `adverts`.`UID` AS 'UID',
                        `adverts`.`ad_id` AS 'ad_id',
                        `adverts`.`propty_type` AS 'propty_type',
                        `adverts`.`type` AS 'type',
                        `adverts`.`posted_date` AS 'posted_date',
                        `adverts`.`submit_date` AS 'submit_date',
                        CONCAT(adverts_contacts.mob_number) AS 'mobile_no',
                        `admin_members`.`expiry` AS 'expiry',
                        `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                        `admin_members`.`am` AS 'am',
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
	                        WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                            ELSE ''
                        END AS 'class'
                FROM `adverts` 
                LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`
                LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID` ";

            if($type == 3){
                $customer .= " INNER JOIN `admin_members_actions` ON `admin_members_actions`.`uid` = `adverts`.`UID`";
            }
            $customer .= "LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                WHERE `adverts`.`is_active` = 3 AND `adverts`.`posted_date` < '".$today."'";

            if($type == 2){
                $customer .= " AND  (`admin_members`.`type` != 'Member') AND `admin_members`.`latest_commented_at` < '".$before_14_days."' AND  `admin_members`.`expiry` > '".$before_14_days."' AND `admin_members`.`category` = 'Single Ad'";
            }
            else if ($type == 3) {
                $customer .= " AND  admin_members_actions.by = '" . $nowAM . "'  AND  (`admin_members`.`type` != 'Member') AND  `admin_members`.`expiry` > '" . $before_14_days . "' AND `admin_members`.`category` = 'Single Ad'";
            }
            else if($type == 4) {
                $customer .= " AND  (`admin_members`.`type` != 'Member') AND `admin_members`.`latest_commented_at` < '".$before_5_days."' AND  `admin_members`.`expiry` > '" . $before_14_days . "' AND `admin_members`.`category` = 'Single Ad'";
            } else {

                $customer .= " AND  (`admin_members`.`type` != 'Member') AND  `admin_members`.`expiry` > '" . $before_14_days . "' AND `admin_members`.`category` = 'Single Ad'";
            }
            if($type == 5){
                $customer .= " ORDER BY `adverts`.`ad_id` DESC";
            }
            $customer .= " GROUP BY `users`.`UID`";
        }
        //echo $customer ; exit();
        $customer = DB::select($customer);
        return Datatables::of($customer)->make(true);
    }

    public function systemPendingData(Request $request)
    {
        $data = $request->input();
        $today = date('Y-m-d');

        $before_day_time = strtotime('-1 day', strtotime($today));
        $before_day_time = date('Y-m-d',$before_day_time);
        $before_three_month = strtotime('-3 month', strtotime($today));
        $before_three_month = date('Y-m-d',$before_three_month);
        $type = $data['type'] ;
        $login_user_data = \Auth::user();
        $nowAM = $login_user_data->username;

        $before_three_days = strtotime('-3 days', strtotime($today));
        $before_two_days = strtotime('-2 days', strtotime($today));
        $before_five_days = strtotime('-5 days', strtotime($today));
        $before_12_days = strtotime('-12 days', strtotime($today));
        $before_14_days = strtotime('-14 days', strtotime($today));
        $before_22_days = strtotime('-23 days', strtotime($today));
        $before_5_days = strtotime('-5 days', strtotime($today));

        $before_three_days = date('Y-m-d',$before_three_days);
        $before_two_days = date('Y-m-d',$before_two_days);
        $before_five_days = date('Y-m-d',$before_five_days);
        $before_12_days = date('Y-m-d',$before_12_days);
        $before_14_days = date('Y-m-d',$before_14_days);
        $before_22_days = date('Y-m-d',$before_22_days);
        $before_5_days = date('Y-m-d',$before_5_days);


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
                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                            `admin_members`.`duration`,
                            
                            `admin_members`.`latest_comment`,";

                $sql .=  "            (SELECT DATE_FORMAT(`admin_members_actions`.`date_time`,'%Y-%m-%d')  FROM admin_members_actions WHERE `admin_members_actions`.`uid` = `users`.`UID`  ORDER BY id DESC LIMIT 1) AS 'last_updated_at',";


            $sql .=  "                DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            `users`.`Uemail` AS 'Uemail',
                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                            CASE
                                WHEN `adverts`.`type` = 'rentals' THEN `adverts`.`price_monthly`
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
                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`";

            if($type == 3){
                $sql .= " INNER JOIN `admin_members_actions` ON `admin_members_actions`.`uid` = `adverts`.`UID`";
            }

            $sql .=  "LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                        WHERE `admin_members`.`am` = 'System' AND `adverts`.`is_active` = 3 AND DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') < '".$before_22_days."' ";

            if($type == 2){
                $sql .= " AND  `admin_members`.`latest_commented_at` < '".$before_14_days."'";
            }
            if($type == 3){
                $sql .= " AND admin_members_actions.by = '" . $nowAM . "'";
            }
            if($type == 4){
                $sql .= " AND  `admin_members`.`latest_commented_at` < '".$before_5_days."'";
            }



            $sql .=  " AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad' AND 
                        (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' 
                        OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%' OR `admin_members`.`am` LIKE '%".$search_data."%') ";
            if($type == 5){
                $sql .= " ORDER BY `adverts`.`ad_id` DESC";
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
                            `admin_members`.`package_amount` AS 'package_amount',
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            DATE_FORMAT(`ama`.`date_time`,'%Y-%m-%d') AS 'last_updated_at',
                            (SELECT  `by` FROM `admin_members_actions` WHERE uid = `adverts`.`UID` AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' ,
                            `admin_members`.`latest_comment`,";

            $sql .= "               DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
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
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts` 
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                        LEFT JOIN `adverts_contacts` ON `adverts_contacts`.`user_id` = `adverts`.`UID`";

            if($type == 3){
                $sql .= " INNER JOIN `admin_members_actions` ON `admin_members_actions`.`uid` = `adverts`.`UID`";
            }

            $sql .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";

            $sql .=  "LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                        LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                        WHERE `admin_members`.`am` = 'System' AND `adverts`.`is_active` = 3 AND DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') < '".$before_22_days."'";

            if($type == 2){
                $sql .= " AND  `admin_members`.`latest_commented_at` < '".$before_14_days."'";
            }
            if($type == 3){
                $sql .= " AND admin_members_actions.by = '" . $nowAM . "'";
            }
            if($type == 4){
                $sql .= " AND  `admin_members`.`latest_commented_at` < '".$before_5_days."'";
            }

            $sql .= "           AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad' ";
            if($type == 5){
                $sql .= " GROUP BY `adverts`.`UID` ORDER BY `adverts`.`ad_id` DESC";
            } else {
                $sql .= " GROUP BY `adverts`.`UID` ";
            }


        }
        //echo $sql ; exit();
        $customer = DB::select($sql);
        //$customer = '';
        return Datatables::of($customer)->make(true);
    }

    // By Sasi Spenzer 2021-10-27 - ** WFH

    public function huntersActivity(){

        // By Sasi Spenzer 2021-10-27
        $currentDate = Carbon::now()->format('Y-m-d');
        $dateBefore30Days = Carbon::now()->subDays(30)->format('Y-m-d');
        \Illuminate\Support\Facades\DB::enableQueryLog();
        $updatedAccountsLast30Days = \DB::table('admin_members')
            ->select('am','uid','source',\DB::raw('DATE(updated_at) AS updated_at'))
            ->whereDate('updated_at', '<=', $currentDate)
            ->whereDate('updated_at', '>=', $dateBefore30Days)
            ->where('source', '!=', '0')
            ->whereNotNull('source')
            ->get()->toArray();

        $getAms = DB::table('admin_users')
            ->select(DB::raw(' DISTINCT username'))
            ->where('am_status', '=', '2')
            ->get()->toArray();

        //dd(DB::getQueryLog());
//        $sql4 = \DB::select(\DB::raw("SELECT  cmd.`am`, COUNT(cmd.`conv_members_data`) AS 'conv_count'
//                                FROM `conv_members_data` AS cmd
//                                WHERE cmd.`am` != cmd.`old_am` AND cmd.`created_at` > '".$dateBefore30Days."'
//                                GROUP BY cmd.`am`"));




//        $sql5 = DB::table('conv_members_data')
//                ->select('conv_members_data.am',DB::raw('COUNT(conv_members_data.`conv_members_data`) AS conv_count'))
//                ->where('conv_members_data.am', '!=', 'conv_members_data.old_am')
//                ->where('conv_members_data.created_at', '>', $currentDate)
//                ->groupBy('conv_members_data.am')
//                ->get()->toArray();

        $sql5 = DB::table('admin_members_actions')
            ->join('admin_members', 'admin_members.uid', '=', 'admin_members_actions.uid')
            ->select('admin_members.am',DB::raw('COUNT(admin_members_actions.`id`) AS conv_count'))
            ->where('admin_members_actions.action', '=', 'Membership Activated')
            ->where('admin_members_actions.created_at', '>=', $dateBefore30Days)
            ->groupBy('admin_members.am')
            ->get()->toArray();
        //echo $sql5->toSql(); exit(); Changed 2021-10-11

        $result_conv_s = $sql5;
        //echo "<pre>";print_r($result_conv_s); exit();
        if ($result_conv_s) {
            foreach ($result_conv_s as $row) {
                $output_data_today["conv"][$row->am] = $row->conv_count;
                //$output_data["conv"]["total"] += intval($row["conv_count"]);
            }
        }

        $sourceList = array('Prospects','Inbound_call','Outbound_call','Chat_Email','Newspaper','Ikman_List','Other','fb','agents');
        $outArray = array();
        foreach ($sourceList as $each_source){

            $sql4 = DB::table('admin_members_actions')
                ->join('admin_members', 'admin_members.uid', '=', 'admin_members_actions.uid')
                ->select('admin_members.am',DB::raw('COUNT(admin_members_actions.`id`) AS conv_count'))
                ->where('admin_members_actions.action', '=', 'Membership Activated')
                ->where('admin_members_actions.created_at', '<=', $currentDate)
                ->where('admin_members_actions.created_at', '>=', $dateBefore30Days)
                ->where('admin_members.source', '=', $each_source)
                ->groupBy('admin_members.am')
                ->get()->toArray();

            $result_conv = $sql4;

            if ($result_conv) {

                foreach ($result_conv as $row) {
                    $output_data_last["conv"][$each_source][$row->am] = $row->conv_count;
                    //$output_data["conv"]["total"] += intval($row["conv_count"]);
                }
            }

            $sql5 = DB::table('admin_members_actions')
                ->join('admin_members', 'admin_members.uid', '=', 'admin_members_actions.uid')
                ->select('admin_members.am',DB::raw('COUNT(admin_members_actions.`id`) AS conv_count'))
                ->where('admin_members_actions.action', '=', 'Membership Activated')
                ->where('admin_members_actions.created_at', '>=', $currentDate)
                ->where('admin_members.source', '=', $each_source)
                ->groupBy('admin_members.am')
                ->get()->toArray();
            //echo $sql5->toSql(); exit(); Changed 2021-10-11

            $result_conv_s = $sql5;
            //echo "<pre>";print_r($result_conv_s); exit();
            if ($result_conv_s) {
                foreach ($result_conv_s as $row) {
                    $output_data_today["conv"][$each_source][$row->am] = $row->conv_count;
                    //$output_data["conv"]["total"] += intval($row["conv_count"]);
                }
            }

            $outArray['hunters_list'] = array();
            $outArray['hunters_list']['Today'] = array();
            $outArray['hunters_list']['Last_30_days'] = array();

            $outArray['pending_payment_system'] = array();
            $outArray['pending_payment_system']['Today'] = array();
            $outArray['pending_payment_system']['Last_30_days'] = array();

            $outArray['pvt_sellers_exp'] = array();
            $outArray['pvt_sellers_exp']['Today'] = array();
            $outArray['pvt_sellers_exp']['Last_30_days'] = array();

            $outArray['pending_payment_pvt'] = array();
            $outArray['pending_payment_pvt']['Today'] = array();
            $outArray['pending_payment_pvt']['Last_30_days'] = array();

            $outArray['pending_payment_exp'] = array();
            $outArray['pending_payment_exp']['Today'] = array();
            $outArray['pending_payment_exp']['Last_30_days'] = array();

            $outArray['members_exp'] = array();
            $outArray['members_exp']['Today'] = array();
            $outArray['members_exp']['Last_30_days'] = array();

            $outArray[$each_source] = array();
            $outArray[$each_source]['Today'] = array();
            $outArray[$each_source]['Last_30_days'] = array();
            //$outArray[$each_source]['Total'] = 0;
            foreach ($getAms as $eachAm){
                //array_push($outArray[$each_source]['Today'],$eachAm->username);
                $outArray['hunters_list']['Today'][$eachAm->username]['count'] = 0;
                $outArray['hunters_list']['Today'][$eachAm->username]['convert'] = 0;
                $outArray['hunters_list'][$eachAm->username]['total'] = 0;

                $outArray['pending_payment_system']['Today'][$eachAm->username]['count'] = 0;
                $outArray['pending_payment_system']['Today'][$eachAm->username]['convert'] = 0;
                $outArray['pending_payment_system'][$eachAm->username]['total'] = 0;

                $outArray['pvt_sellers_exp']['Today'][$eachAm->username]['count'] = 0;
                $outArray['pvt_sellers_exp']['Today'][$eachAm->username]['convert'] = 0;
                $outArray['pvt_sellers_exp'][$eachAm->username]['total'] = 0;

                $outArray['pending_payment_pvt']['Today'][$eachAm->username]['count'] = 0;
                $outArray['pending_payment_pvt']['Today'][$eachAm->username]['convert'] = 0;
                $outArray['pending_payment_pvt'][$eachAm->username]['total'] = 0;

                $outArray['pending_payment_exp']['Today'][$eachAm->username]['count'] = 0;
                $outArray['pending_payment_exp']['Today'][$eachAm->username]['convert'] = 0;
                $outArray['pending_payment_exp'][$eachAm->username]['total'] = 0;

                $outArray['members_exp']['Today'][$eachAm->username]['count'] = 0;
                $outArray['members_exp']['Today'][$eachAm->username]['convert'] = 0;
                $outArray['members_exp'][$eachAm->username]['total'] = 0;

                $outArray[$each_source]['Today'][$eachAm->username]['count'] = 0;
                $outArray[$each_source]['Today'][$eachAm->username]['convert'] = 0;
                $outArray[$each_source][$eachAm->username]['total'] = 0;


            }
            foreach ($getAms as $eachAm){
                $outArray['hunters_list']['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray['hunters_list']['Last_30_days'][$eachAm->username]['convert'] = 0;

                $outArray['pending_payment_system']['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray['pending_payment_system']['Last_30_days'][$eachAm->username]['convert'] = 0;

                $outArray['pvt_sellers_exp']['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray['pvt_sellers_exp']['Last_30_days'][$eachAm->username]['convert'] = 0;

                $outArray['pending_payment_pvt']['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray['pending_payment_pvt']['Last_30_days'][$eachAm->username]['convert'] = 0;

                $outArray['pending_payment_exp']['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray['pending_payment_exp']['Last_30_days'][$eachAm->username]['convert'] = 0;

                $outArray['members_exp']['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray['members_exp']['Last_30_days'][$eachAm->username]['convert'] = 0;

                //array_push($outArray[$each_source]['Last_30_days'],$eachAm->username);
                $outArray[$each_source]['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray[$each_source]['Last_30_days'][$eachAm->username]['convert'] = 0;


            }
        }

        foreach ($updatedAccountsLast30Days as $each_record){

            $source = $each_record->source;
            $updated_at = $each_record->updated_at;
            $am = $each_record->am;

            //echo $updated_at.'-'.$currentDate.'<br/>';
            if($updated_at == $currentDate){
                //dd($am);
                if(isset($outArray[$source]['Today'][$am])){

                    $outArray[$source]['Today'][$am]['count'] += 1;
                    if(isset($output_data_today["conv"][$source][$am])){
                        $outArray[$source]['Today'][$am]['convert'] = $output_data_today["conv"][$source][$am];

                        $outArray[$source][$am]['total'] += 1;
                    }else {
                        $outArray[$source]['Today'][$am]['convert'] = 0;
                    }
//                    if(!isset($outArray[$source]['Total'][$am])){
//                        $outArray[$source]['Total'][$am] = 0;
//                    }
//                    $outArray[$source]['Total'][$am] += 1;

                }
            }else {

                if(isset($outArray[$source]['Last_30_days'][$am])){
                    $outArray[$source]['Last_30_days'][$am]['count'] += 1;

                    if(isset($output_data_last["conv"][$source][$am])){

                        $outArray[$source]['Last_30_days'][$am]['convert'] = $output_data_last["conv"][$source][$am];
                        $outArray[$source][$am]['total']  += 1;


                    }else {
                        $outArray[$source]['Last_30_days'][$am]['convert'] = 0;
                    }
//                    if(!isset($outArray[$source]['Total'][$am])){
//                        $outArray[$source]['Total'][$am] = 0;
//                    }
//                    $outArray[$source]['Total'][$am] += 1;
                }
            }
        }
        $today = date('Y-m-d');
        $this_month = date('Y-m');

        $before_three_month = date('Y-m-d', strtotime($today . ' - 90 days'));

        foreach ($getAms as $eachAm){
            $am_name = $eachAm->username;

              $sql = "SELECT COUNT(u.`UID`) AS count
                FROM `admin_members` AS am 
                INNER JOIN `users` AS u ON u.`UID` = am.`uid` 
                LEFT JOIN `payment_status` AS ps ON ps.`payment_status_id` = am.`payment_status`
                LEFT JOIN `admin_users` AS au On au.`username` = am.`am` 
                LEFT JOIN (
                    SELECT lmd.`user_id`, IFNULL(COUNT(lmd.`leads_month_data_id`),0) AS 'leads_count' 
                    FROM `leads_month_data` AS lmd 
                    WHERE lmd.`created_at` LIKE '".$this_month."%' AND lmd.`ad_id` > 0
                    GROUP BY lmd.`user_id` 
                ) AS ld ON ld.`user_id` = am.`uid` 
                LEFT JOIN (
                    SELECT vmd.`user_id`, IFNULL(SUM(vmd.`views_count`),0) AS 'views_count' 
                    FROM `views_month_data` AS vmd 
                    WHERE vmd.`cretaed_at` LIKE '".$this_month."%' AND vmd.`ad_id` > 0
                    GROUP BY vmd.`user_id`  
                ) AS vmd ON vmd.`user_id` = am.`uid`
                WHERE am.`am` != 'Online' AND am.`am` != 'System' AND am.`payment_exp_date` >= '".$before_three_month."'";


            $sql .= " AND am.`am` = '".$am_name."'";



            $sql .= " ORDER BY u.`UID` DESC";

            $getCountToday = \DB::table('admin_members')
                ->select('am','uid','source',\DB::raw('DATE(updated_at) AS updated_at'),DB::raw('COUNT(admin_members.`uid`) AS count'))
                ->whereDate('updated_at', '=', $currentDate)
                ->where('am', '=', $eachAm->username)
                ->get()->toArray();

            $getCount30Days = \DB::table('admin_members')
                ->select('am','uid','source',\DB::raw('DATE(updated_at) AS updated_at'),DB::raw('COUNT(admin_members.`uid`) AS count'))
                ->whereDate('updated_at', '>=', $dateBefore30Days)
                ->where('am', '=', $eachAm->username)
                ->get()->toArray();




            $data =  DB::select($sql);

            $outArray['hunters_list']['Today'][$eachAm->username]['count'] = $getCountToday[0]->count;
            $outArray['hunters_list']['Last_30_days'][$eachAm->username]['count'] = $getCount30Days[0]->count;
            $outArray['hunters_list'][$am_name]['total'] = $data[0]->count;


            $before_day_time = strtotime('-1 day', strtotime($today));

            $before_three_month = strtotime('-3 month', strtotime($today));
            $before_three_month = date('Y-m-d',$before_three_month);

            $before_22_days = strtotime('-22 days', strtotime($today));
            $before_22_days = date('Y-m-d',$before_22_days);

            $before3months = date("Y-m-d",strtotime('-90 days'));
            $before1day = date("Y-m-d",strtotime('-1 days'));
            $before2months = date("Y-m-d",strtotime('-60 days'));
            $before1months = date("Y-m-d",strtotime('-30 days'));
            $before6months = date("Y-m-d",strtotime('-180 days'));
            $before12months = date("Y-m-d",strtotime('-365 days'));
            $before5days = date("Y-m-d",strtotime('-5 days'));
            $before2days = date("Y-m-d",strtotime('-2 days'));
            $date_before_14_days = date('Y-m-d', strtotime($today. ' - 14 days'));
            $date_before_05_days = date('Y-m-d', strtotime($today. ' - 5 days'));

            $panding_payment_system = "SELECT DISTINCT `adverts`.`UID` AS 'UID',COUNT(DISTINCT `adverts`.`UID`) AS Count,
                            `adverts`.`ad_id` AS 'ad_id',
                            `adverts`.`propty_type` AS 'propty_type',
                            `adverts`.`type` AS 'type',
                            `adverts`.`submit_date` AS 'submit_date',
                            `admin_members`.`mobile_nos` AS 'mobile_no',
                            `admin_members`.`expiry` AS 'expiry',
                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                            `admin_members`.`am` AS 'am',
                            `admin_members`.`package_amount` AS 'package_amount',
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            DATE_FORMAT(`ama`.`date_time`,'%Y-%m-%d') AS 'last_updated_at',
                            `admin_members`.`latest_comment`,";

            $panding_payment_system .= "               DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            `users`.`Uemail` AS 'Uemail',
                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                            CASE
                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bungalow' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Villa' THEN 'yellow-class'
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts` 
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                        LEFT JOIN `admin_members_actions` ON admin_members_actions.uid = `adverts`.`UID`
                        ";



            $panding_payment_system .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";

            $panding_payment_system .=  "LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                        LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                        WHERE `admin_members`.`am` = 'System' AND `adverts`.`is_active` = 3 AND `adverts`.`posted_date` > '".$before1months."'";



            $panding_payment_system .= "           AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad' ";
            $panding_payment_system .= " AND admin_members_actions.by = '" . $am_name . "' ";
            $panding_payment_system .= " GROUP BY `adverts`.`UID`";

            //echo $panding_payment_system;  exit();
            $panding_payment_system_data =  DB::select($panding_payment_system);
            //print_r($panding_payment_system_data); exit();

            $panding_payment_system_today = "SELECT DISTINCT `adverts`.`UID` AS 'UID',COUNT(DISTINCT `adverts`.`UID`) AS Count,
                            `adverts`.`ad_id` AS 'ad_id',
                            `adverts`.`propty_type` AS 'propty_type',
                            `adverts`.`type` AS 'type',
                            `adverts`.`submit_date` AS 'submit_date',
                            `admin_members`.`mobile_nos` AS 'mobile_no',
                            `admin_members`.`expiry` AS 'expiry',
                            `admin_members`.`payment_exp_date` AS 'payment_exp_date',
                            `admin_members`.`am` AS 'am',
                            `admin_members`.`package_amount` AS 'package_amount',
                            `payment_status`.`payment_status` AS 'status',
                            `admin_members`.`duration`,
                            DATE_FORMAT(`ama`.`date_time`,'%Y-%m-%d') AS 'last_updated_at',
                            `admin_members`.`latest_comment`,";

            $panding_payment_system_today .= "               DATE_FORMAT(`adverts`.`posted_date`,'%Y-%m-%d') AS 'posted_date',
                            `users`.`Uemail` AS 'Uemail',
                            CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username',
                            CASE
                                WHEN `adverts`.`propty_type` = 'Apartment' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Bungalow' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Commercial' THEN 'yellow-class'
                                WHEN `adverts`.`propty_type` = 'Villa' THEN 'yellow-class'
	                            WHEN `adverts`.`type` = 'land' AND `adverts`.`propty_type` != 'Bare Land' AND 
	                                `adverts`.`propty_type` != 'Land with house' THEN 'yellow-class'
                                ELSE ''
                            END AS 'class'
                        FROM `adverts` 
                        LEFT JOIN `admin_members` ON `admin_members`.`uid` = `adverts`.`UID`
                        LEFT JOIN `admin_members_actions` ON admin_members_actions.uid = `adverts`.`UID`
                        ";



            $panding_payment_system_today .=  "LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = admin_members.`uid`";

            $panding_payment_system_today .=  "LEFT JOIN `users` ON `users`.`UID` = `adverts`.`UID`
                        LEFT JOIN `payment_status` ON `payment_status`.`payment_status_id` = `admin_members`.`payment_status`
                        WHERE `admin_members`.`am` = 'System' AND `adverts`.`is_active` = 3 AND `adverts`.`posted_date` > '".$today."'";



            $panding_payment_system_today .= "           AND  (`admin_members`.`type` = 'Invoiced' OR `admin_members`.`type` = 'Pending Payment') AND `admin_members`.`category` = 'Single Ad' ";
            $panding_payment_system_today .= " AND admin_members_actions.by = '" . $am_name . "' ";
            $panding_payment_system_today .= " GROUP BY `adverts`.`UID`";

            $panding_payment_system_data_today =  DB::select($panding_payment_system_today);

            $outArray['pending_payment_system']['Today'][$eachAm->username]['count'] = $panding_payment_system_data_today[0]->count ?? 0;
            $outArray['pending_payment_system']['Last_30_days'][$eachAm->username]['count'] = $panding_payment_system_data[0]->count ?? 0;
            $outArray['pending_payment_system'][$am_name]['total'] = $panding_payment_system_data[0]->count ?? 0;



            // pvt sellers exp
            $pvt_sellers_exp_30 = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID`  
                         
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND ama.`created_at` >= '".$before1months."' 
                        LEFT JOIN `pp_payments` pp ON pp.user_id = ama.uid
                        WHERE a.`is_active` = 3  AND ama.`by` = '".$am_name."' 
                        AND  (am.`type` = 'Pending Payment' OR am.`type` = 'Expired') AND  am.`payment_exp_date` > '".$before3months."' AND am.`category` = 'Single Ad'
                        AND pp.payment_details LIKE '%For buy Single Ad%' AND pp.paid_date >= '$before6months'
                        GROUP BY `by`";

            $pvt_sellers_exp_data_results_30 =  DB::select($pvt_sellers_exp_30);

            $pvt_sellers_exp_today = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID`  
                         
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND ama.`created_at` >= '".$today."' 
                        LEFT JOIN `pp_payments` pp ON pp.user_id = ama.uid
                        WHERE a.`is_active` = 3  AND ama.`by` = '".$am_name."' 
                        AND  (am.`type` = 'Pending Payment' OR am.`type` = 'Expired') AND  am.`payment_exp_date` > '".$before3months."' AND am.`category` = 'Single Ad'
                        AND pp.payment_details LIKE '%For buy Single Ad%' AND pp.paid_date >= '$before6months'
                        GROUP BY `by`";

            $pvt_sellers_exp_data_results_today =  DB::select($pvt_sellers_exp_today);

            $pvt_sellers_exp_total = "SELECT COUNT(DISTINCT a.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` 
                        LEFT JOIN `pp_payments` pp ON pp.user_id = ama.uid
                        WHERE  am.`category` = 'Single Ad' AND (am.`type` = 'Pending Payment' OR am.`type` = 'Expired') 
                        AND a.is_active = 3 AND ama.`by` = '".$am_name."' AND ((am.latest_commented_at < CURDATE() - INTERVAL 5 DAY AND ama.`created_at` < CURDATE() - INTERVAL 5 DAY) OR ama.created_at IS NULL)  
                        AND (ama.reminder < CURDATE() OR ama.reminder IS NULL) 
                        AND  am.`payment_exp_date` > CURDATE() - INTERVAL 3 MONTH
                        AND pp.payment_details LIKE '%For buy Single Ad%' AND pp.paid_date >= CURDATE() - INTERVAL 6 MONTH";

            $pvt_sellers_exp_total_results =  DB::select($pvt_sellers_exp_total);


            $outArray['pvt_sellers_exp']['Today'][$eachAm->username]['count'] = $pvt_sellers_exp_data_results_today[0]->call_count ?? 0;
            $outArray['pvt_sellers_exp']['Last_30_days'][$eachAm->username]['count'] = $pvt_sellers_exp_data_results_30[0]->call_count ?? 0;
            $outArray['pvt_sellers_exp'][$am_name]['total'] = $pvt_sellers_exp_total_results[0]->call_count ?? 0;

            // pending_payment_pvt

            $pending_payment_pvt_today = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid`
                        WHERE am.`category` = 'Single Ad' AND am.`type` <> 'Member' AND a.is_active = 3
                        AND am.`am` = '".$am_name."' AND am.`assigned_date` >= '".$today."'
                        GROUP BY am.`am`";

            $pending_payment_pvt_results =  DB::select($pending_payment_pvt_today);

            $pending_payment_pvt_30 = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid`
                        WHERE am.`category` = 'Single Ad' AND am.`type` <> 'Member' AND a.is_active = 3
                        AND am.`am` = '".$am_name."' AND am.`assigned_date` >= '".$before1months."'
                        GROUP BY am.`am`";

            $pending_payment_pvt_results_30 =  DB::select($pending_payment_pvt_30);

            $pending_payment_pvt_total = "SELECT COUNT(DISTINCT a.UID) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND `by` <> 'System'
                        WHERE a.`is_active` = 3 AND  am.`type` != 'Member' AND am.`category` = 'Single Ad'  AND am.`am` = '".$am_name."' 
                        AND (ama.created_at < '".$date_before_05_days."' OR ama.created_at IS NULL) 
                        AND (a.`posted_date` >= '".$date_before_14_days."' AND a.`posted_date` < '".$before2days."') 
                         AND (ama.reminder < '".$today."' OR ama.reminder IS NULL)
                        ";
            $pending_payment_pvt_results_total =  DB::select($pending_payment_pvt_total);

            $outArray['pending_payment_pvt']['Today'][$eachAm->username]['count'] = $pending_payment_pvt_results[0]->call_count ?? 0;
            $outArray['pending_payment_pvt']['Last_30_days'][$eachAm->username]['count'] = $pending_payment_pvt_results_30[0]->call_count ?? 0;
            $outArray['pending_payment_pvt'][$am_name]['total'] = $pending_payment_pvt_results_total[0]->call_count ?? 0;

            // pending_payment_exp

            $pending_payment_exp_today = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID`
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND ama.`created_at` >= '".$today."'
                        WHERE  am.`category` = 'Single Ad'  AND am.`type` = 'Expired' AND a.is_active = 3
                        AND am.`am` = '".$am_name."' 
                        GROUP BY am.`am`";

            $pending_payment_exp_today_results =  DB::select($pending_payment_exp_today);

            $pending_payment_exp_30 = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID`
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND ama.`created_at` >= '".$before1months."'
                        WHERE  am.`category` = 'Single Ad'  AND am.`type` = 'Expired' AND a.is_active = 3
                        AND am.`am` = '".$am_name."' 
                        GROUP BY am.`am`";

            $pending_payment_exp_30_results =  DB::select($pending_payment_exp_30);

            $pending_payment_exp_total = "SELECT COUNT(DISTINCT a.UID) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND `by` <> 'System'
                        WHERE a.`is_active` = 3 AND  am.`type` != 'Member' AND am.`category` = 'Single Ad' 
                        AND (ama.created_at < '".$date_before_05_days."' OR ama.created_at IS NULL) 
                        AND (a.`posted_date` >= '".$date_before_14_days."' AND a.`posted_date` < '".$before2days."') 
                         AND (ama.reminder < '".$today."' OR ama.reminder IS NULL)
                        ";

            $pending_payment_exp_total_results =  DB::select($pending_payment_exp_total);

            $outArray['pending_payment_exp']['Today'][$eachAm->username]['count'] = $pending_payment_exp_today_results[0]->call_count ?? 0;
            $outArray['pending_payment_exp']['Last_30_days'][$eachAm->username]['count'] = $pending_payment_exp_30_results[0]->call_count ?? 0;
            $outArray['pending_payment_exp'][$am_name]['total'] = $pending_payment_exp_total_results[0]->call_count ?? 0;

            //members_exp
            $members_exp_today = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `admin_members` AS am 
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND ama.`created_at` >= '".$today."' 
                        WHERE am.`type` != 'Member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' AND ama.`by` = '".$am_name."'
                        AND payment_exp_date < '".$before2months."' 
                        GROUP BY `by`";

            $members_exp_today_results =  DB::select($members_exp_today);

            $members_exp_30 = "SELECT am.`am`, COUNT(ama.`uid`) AS 'call_count' 
                        FROM `admin_members` AS am 
                        INNER JOIN `users` AS u ON u.`UID` = am.`uid` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND ama.`created_at` >= '".$before1months."' 
                        WHERE am.`type` != 'Member' AND am.`category` != 'Single Ad' AND am.`category` != 'Free' AND ama.`by` = '".$am_name."'
                        AND payment_exp_date < '".$before2months."' 
                        GROUP BY `by`";

            $members_exp_30_results =  DB::select($members_exp_30);

            $members_exp_total = "SELECT COUNT(DISTINCT a.UID) AS 'call_count' 
                        FROM `adverts` AS a 
                        LEFT JOIN `admin_members` AS am ON am.`uid` = a.`UID` 
                        LEFT JOIN `admin_members_actions` AS ama ON ama.`uid` = am.`uid` AND `by` <> 'System'
                        WHERE a.`is_active` = 3 AND  am.`type` != 'Member' AND am.`category` = 'Single Ad' 
                        AND (ama.created_at < '".$date_before_05_days."' OR ama.created_at IS NULL) 
                        AND (a.`posted_date` >= '".$date_before_14_days."' AND a.`posted_date` < '".$before2days."') 
                         AND (ama.reminder < '".$today."' OR ama.reminder IS NULL)
                        ";

            $members_exp_total_results =  DB::select($members_exp_total);

            $outArray['members_exp']['Today'][$eachAm->username]['count'] = $members_exp_today_results[0]->call_count ?? 0;
            $outArray['members_exp']['Last_30_days'][$eachAm->username]['count'] = $members_exp_30_results[0]->call_count ?? 0;
            $outArray['members_exp'][$am_name]['total'] = $members_exp_total_results[0]->call_count ?? 0;
        }

//        echo "<pre>";
//        print_r($outArray);
//        exit();

        return view('member.hunters_activity')->with(['resultsTable'=>$outArray,'ams'=>$getAms]);

    }

    public function myListTableData(Request $request) {
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $today = date('Y-m-d');
        $this_month = date('Y-m');
        $before_two_week = date('Y-m-d', strtotime($today . ' - 14 days'));
        $after_two_week = date('Y-m-d', strtotime($today . ' + 14 days'));
        //$next_day = date('Y-m-d', strtotime($today . ' + 1 days'));
        $before_month = date('Y-m-d', strtotime($today . ' - 30 days'));
        $before_three_month = date('Y-m-d', strtotime($today . ' - 90 days'));
        //Login user auth data
        $login_user_data = \Auth::user();
        $admin_level  = intval($login_user_data->admin_level);
        $type = $request->input('type');
        $am_name = $login_user_data->username;
        //dd($today,$before_two_week,$after_two_week,$next_day,$before_month);
        $sql = "SELECT u.`UID`, CONCAT(u.`firstname`, ' ', u.`surname`) AS 'full_name', u.`Uemail` AS 'email_address', am.`category`, au.`name` As 'am', am.`created_at`,`payment_exp_date`,
                        ut.user_type,am.`expiry`,am.`source`, am.`latest_action`, am.`latest_comment`, am.`latest_commented_at`, am.`pending_amount`,
                        IF(`payment_exp_date` < '".$before_month."','Inactive',IF(`payment_exp_date` < '".$before_two_week."', 'Expired',
                        IF(`payment_exp_date` < '".$today."', 'Grace Period',IF(`payment_exp_date` < '".$after_two_week."', 'Expiring in 2 weeks','-')))) AS 'pay_status' ,
                        IF(`payment_exp_date` < '".$before_month."','expired',IF(`payment_exp_date` < '".$before_two_week."', 'month_expired',
                        IF(`payment_exp_date` < '".$today."', 'deactivated',IF(`payment_exp_date` < '".$after_two_week."', 'two_week','')))) AS 'payment_class',
                        IF(`payment_exp_date` < '".$before_month."','1',IF(`payment_exp_date` < '".$before_two_week."', '2',
                        IF(`payment_exp_date` < '".$today."', '3',IF(`payment_exp_date` < '".$after_two_week."', '4','5')))) AS 'priority' ,
                        
                        IF(`payment_exp_date` < '".$before_month."','expired',IF(`payment_exp_date` < '".$before_two_week."', 'month_expired',
                        IF(`payment_exp_date` < '".$today."', 'deactivated',IF(`payment_exp_date` < '".$after_two_week."', 'two_week','')))) AS 'payment_class',                    
                        IFNULL(vmd.`views_count`,0) AS 'views_count', IFNULL(ld.`leads_count`, 0) AS 'leads_count', u.`ads_count`, ps.`payment_status` ,
                         (SELECT  `by` FROM `admin_members_actions` WHERE uid = UID AND `by` != 'System' ORDER BY id DESC LIMIT 1) AS 'last_updated_by' 
                FROM `admin_members` AS am 
                INNER JOIN `users` AS u ON u.`UID` = am.`uid` 
                LEFT JOIN `payment_status` AS ps ON ps.`payment_status_id` = am.`payment_status`
                LEFT JOIN `admin_users` AS au On au.`username` = am.`am` 
                LEFT JOIN `user_type` AS ut On ut.`user_type_id` = am.`user_type_id` 
                LEFT JOIN (
                    SELECT lmd.`user_id`, IFNULL(COUNT(lmd.`leads_month_data_id`),0) AS 'leads_count' 
                    FROM `leads_month_data` AS lmd 
                    WHERE lmd.`created_at` LIKE '".$this_month."%' AND lmd.`ad_id` > 0
                    GROUP BY lmd.`user_id` 
                ) AS ld ON ld.`user_id` = am.`uid` 
                LEFT JOIN (
                    SELECT vmd.`user_id`, IFNULL(SUM(vmd.`views_count`),0) AS 'views_count' 
                    FROM `views_month_data` AS vmd 
                    WHERE vmd.`cretaed_at` LIKE '".$this_month."%' AND vmd.`ad_id` > 0
                    GROUP BY vmd.`user_id`  
                ) AS vmd ON vmd.`user_id` = am.`uid`
                WHERE am.`am` != 'Online' AND am.`am` != 'System' AND am.`payment_exp_date` >= '".$before_three_month."'";
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql .= " AND ((u.`firstname` LIKE '%" . $search_data . "%') OR  (u.`surname` LIKE '%" . $search_data . "%') 
                      (am.`category` LIKE '%" . $search_data . "%') OR  (am.`am` LIKE '%" . $search_data . "%') ) OR  (u.`Uemail` LIKE '%" . $search_data . "%') )";
        }
        if($admin_level < 3) {
            $sql .= " AND am.`am` = '".$am_name."'";
        }
        $filter_scope = '';
        if(isset($type)){
            switch ($type) {
                case 1:
                    $filter_scope = 'Prospects';
                    break;
                case 2:
                    $filter_scope = 'Inbound_call';
                    break;
                case 3:
                    $filter_scope = 'Outbound_call';
                    break;
                case 4:
                    $filter_scope = 'Chat_Email';
                    break;
                case 5:
                    $filter_scope = 'Newspaper';
                    break;
                case 6:
                    $filter_scope = 'Ikman_List';
                    break;
                case 7:
                    $filter_scope = 'other_website';
                    break;
                case 8:
                    $filter_scope = 'fb';
                    break;
                case 9:
                    $filter_scope = 'other';
                    break;
                case 10:
                    $filter_scope = 'agents';
                    break;
                default:
                    $filter_scope = '';
                    break;
            }
        }
        if($filter_scope != ''){
            $sql .= " AND am.`source` = '".$filter_scope."'";
        }
        $sql .= " ORDER BY u.`UID` DESC";
        //print_r($sql); exit();
        $output = DB::select($sql);
        return Datatables::of($output)->make(true);
    }

    public function numberFormat($number){
        $output = $number;
        if(intval($number / 1000000) > 0){
            $output = number_format(floatval($number / 1000000),2,'.',',')."M";
        } else if(intval($number / 1000) > 0){
            $output = number_format(floatval($number / 1000),2,'.',',')."K";
        }
        return $output;
    }

}