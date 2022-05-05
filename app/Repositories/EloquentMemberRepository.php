<?php

namespace App\Repositories;

use App\Contracts\MemberInterface;
use App\Customer;
use App\Member;
use Carbon\Carbon;
use App\Package;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class EloquentMemberRepository implements MemberInterface {

	/**
	 * @var numOfMonths
	 */
	private $numOfMonths;
	private $memCategory;

	/**
	 * Create a new repository instance.
	 *
	 * @return void
	 */
	public function __construct() {}

	function save(Array $payload) {
		$member = Member::where('uid', $payload['uid'])->first();
		if (!empty($member) && !is_null($member)) {
			$memberResult = $this->store($member, $payload);
		} else {
			$isNewMember = true;
			$member = new Member;
			$memberResult = $this->store($member, $payload, $isNewMember);
		}
		return $memberResult;
	}

    /**
     * Save membership data
     * @param array $payload
     * @return mixed
     */
	public function save_membership(Array $payload)
    {
        //input data
        $membership_uid = intval($payload['membership_uid']);
        $membership_contact = $payload['membership_contact'] ;
        $old_membership_contact = $payload['old_membership_contact'] ;
        //$membership_email = $payload['membership_email'];
        $company_name = $payload['company_name'];
        $is_active_auto_boost = $payload['is_active_auto_boost'];
        $auto_boost_for_new_ads = $payload['auto_boost_for_new_ads'];
        $old_is_active_auto_boost = $payload['old_is_active_auto_boost'];
        $old_auto_boost_for_new_ads = $payload['old_auto_boost_for_new_ads'];
        $old_company_name = $payload['old_company_name'];
        $linkin_profile = $payload['linkin_profile'];
        $old_linkin_profile = $payload['old_linkin_profile'];
        //$latest_comment = $payload['latest_comment'];
        $user_image = $payload['user_image'];
        $old_user_image = $payload['old_user_image'];
        $is_change_user_profile = intval($payload['is_change_user_profile']);

        //login user data
        $login_user_data = \Auth::user();
        $changer_user_id = intval($login_user_data->id);
        $changer_user_name = $login_user_data->name;
        //Don't want check permission
        //$admin_level = intval($login_user_data->admin_level);
        $admin_level = 3;
        $change_description = $changer_user_name;
         //checking change data
        if($admin_level > 2){
            $change_description .= " updated following fields ";
        } else {
            $change_description .= " requested update following fields ";
        }
        if($old_company_name != $company_name) {
            $change_description .= "Company Name, ";
        }
        if($old_membership_contact != $membership_contact) {
            $change_description .= "Contact Number, ";
        }
        if($old_linkin_profile != $linkin_profile) {
            $change_description .= "LinkIn profile, ";
        }
        if($old_user_image != $user_image) {
            $change_description .= "User profile, ";
        }
        if($is_active_auto_boost != $old_is_active_auto_boost) {
            
            if($is_active_auto_boost == 'Y'){
            	$change_description .= "Auto Boost Activated, ";
            }else{
            	$change_description .= "Auto Boost Deactivated, ";
            }
        }
        if($auto_boost_for_new_ads != $old_auto_boost_for_new_ads) {
            if($auto_boost_for_new_ads == 'Y'){
            	$change_description .= "Auto Boost Activated, ";
            }else{
            	$change_description .= "Auto Boost Deactivated, ";
            }
        }
        $change_description = substr($change_description,0,-2).'.';

        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $today = date('Y-m-d H:i:s');

        try {
            $output['is_view_msg'] = 0;
            $membership_profile_log_id = 0;
            $output['admin_level'] = $admin_level;
            if($admin_level > 2) {
                if ($is_change_user_profile == 1) {
                    DB::table('admin_members')
                        ->where('uid', $membership_uid)
                        ->update([
                            'mobile_nos' => $membership_contact, //'latest_comment' => $latest_comment,
                            'is_active_auto_boost' => $is_active_auto_boost,
                            'auto_boost_for_new_ads' => $auto_boost_for_new_ads,
                            'company' => $company_name, 'updated_at' => $today
                        ]);
                    if ($user_image != '' && $user_image != null) {
                        DB::table('users')
                            ->where('UID', $membership_uid)
                            ->update([/*'Uemail' => $membership_email, */ 'logo_path' => $user_image, 'linkin_id' => $linkin_profile]);
                    } else {
                        DB::table('users')
                            ->where('UID', $membership_uid)
                            ->update([/*'Uemail' => $membership_email, */ 'linkin_id' => $linkin_profile]);
                    }
                    $membership_profile_log_id = DB::table('membership_profile_log')->insertGetId([
                        'changer_user_id' => $changer_user_id, 'approve_user_id' => $changer_user_id,
                        'member_user_id' => $membership_uid, 'is_approve' => 1, 'old_company_name' => $old_company_name,
                        'new_company_name' => $company_name, 'old_contact_number' => $old_membership_contact,
                        'new_contact_number' => $membership_contact, 'old_linkin_profile' => $old_linkin_profile,
                        'new_linkin_profile' => $linkin_profile, 'old_member_image' => $old_user_image,
                        'new_member_image' => $user_image, 'created_at' => $today, 'updated_at' => $today,
                        'changing_description' => $change_description
                    ]);
                    $output['is_view_msg'] = 1;
                }
                $output['description'] = "Member updated successfully";
            } else {
                if($is_change_user_profile == 1) {
                    $membership_profile_log_id = DB::table('membership_profile_log')->insertGetId([
                        'changer_user_id' => $changer_user_id, 'member_user_id' => $membership_uid,
                        'old_company_name' => $old_company_name, 'new_company_name' => $company_name,
                        'old_contact_number' => $old_membership_contact, 'new_contact_number' => $membership_contact,
                        'old_linkin_profile' => $old_linkin_profile, 'new_linkin_profile' => $linkin_profile,
                        'old_member_image' => $old_user_image, 'new_member_image' => $user_image,
                        'created_at' => $today, 'updated_at' => $today, 'changing_description' => $change_description
                    ]);
                    $output['description'] = "After high level member approved your changers will be add";
                    $output['is_view_msg'] = 1;
                } else {
                    $output['description'] = "No any data change";
                }

            }
            if($membership_profile_log_id > 0){
                DB::table('membership_profile_log')
                    ->where('membership_profile_log_id', '<' , $membership_profile_log_id)
                    ->where('member_user_id', '=', $membership_uid)
                    ->update(['is_expire' => 1]);
            }
            $output['status'] = 'Succeed';
            $output['member_img'] = $user_image;
        } catch(\Exception $e){
            $output['status'] = 'Failed';
            $output['is_view_msg'] = 1;
            $output['description'] = $e;
            $output['member_img'] = '';
        }
        return $output;

    }

	private function store(Member $member, Array $payload, $isNewMember = false) {
		$currentDateTime = Carbon::now();
		$today = Carbon::now();
		$monthAhead = date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'));

		if ($isNewMember) {
			$member->uid = $payload['uid'];
			$member->created_at = $currentDateTime;
		}
		if ($payload['type'] != "" && $payload['type'] != $member->type) {
			$member->updated_at = $currentDateTime;
		}
		if ($payload['category'] != "" && $payload['category'] != $member->category) {
			$member->updated_at = $currentDateTime;
		}

		if ($isNewMember) {
			$member->call_date_time = ($payload['call_date_time']) ? $payload['call_date_time'] : $today;
			$member->payment_exp_date = ($payload['payment_exp_date']) ? $payload['payment_exp_date'] : $today;

			$member->remarks = ($payload['remarks']) ? $payload['remarks'] : "";
		}

		//accout user or super admin
		if (Auth::user()->admin_level == 1 || Auth::user()->admin_level == 3 || Auth::user()->admin_level == 4) {
			$member->call_date_time = ($payload['call_date_time']) ? $payload['call_date_time'] : Null;
			$member->payment_exp_date = ($payload['call_date_time']) ? $payload['call_date_time'] : "0000-00-00";
		}

		if ($payload['category'] != "Free" && $payload['type'] == "Member" && $payload['category'] != $member->category) {
			$member->member_since = $currentDateTime;
			if (in_array($payload['duration'], \Config::get('membership.duration'))) {
				$this->numOfMonths = intval(array_search($payload['duration'], \Config::get('membership.duration')));
			}
			$member->expiry = ($payload['expiry']) ? $payload['expiry'] : $currentDateTime->addMonths($this->numOfMonths);
			//$member->expiry = $currentDateTime->addMonths($this->numOfMonths); //1
		} else {
			$member->expiry = ($payload['expiry']) ? $payload['expiry'] : $monthAhead;
		}

		if ($payload['category'] == 'Free' || $payload['category'] == 'Trial') {
			$member->call_date_time = NUll;
			$member->payment_exp_date = NULL;
			$member->expiry = NUll;
		}

		if ($payload['package_amount'] == '' || $payload['package_amount'] == '0.00') {
			$custom_amount = 0;
			
			$package_amount = '0.00';
			//check wether package was changed
			if ($member->category != $payload['category'] || $member->payment != $payload['payment'] || $member->package_amount != $payload['package_amount']) {
				if ($payload['payment'] == 'Quarterly') {
					$package_amount = Package::select('quarterly_payment')
												->where('package_name', $payload['category'])
												->first()->quarterly_payment;
				}elseif ($payload['payment'] == 'Annually') {
					$package_amount = Package::select('annual_payment')
												->where('package_name', $payload['category'])
												->first()->annual_payment;
				}
			}
		}else{
			$custom_amount = 1;
			$package_amount = $payload['package_amount'];
		}

		$member->type = ($payload['type']) ? $payload['type'] : "";
		$member->category = ($payload['category']) ? $payload['category'] : "";
		$member->duration = ($payload['duration']) ? $payload['duration'] : "";
		$member->payment = ($payload['payment']) ? $payload['payment'] : "";
		$member->am = ($payload['am']) ? $payload['am'] : "";
		$member->mobile_nos = ($payload['mobile_nos']) ? $payload['mobile_nos'] : "";
		$member->company = ($payload['company']) ? $payload['company'] : "";
		$member->active_ads = ($payload['active_ads']) ? $payload['active_ads'] : 0;
		$member->leads = ($payload['leads']) ? $payload['leads'] : 0;
		$member->package_amount = $package_amount;
		$member->custom_amount = $custom_amount;
		$member->payment_status = ($payload['memship_status']) ? $payload['memship_status'] : "";;

		$success = $member->save();

		if ($success) {
			if ($payload['type'] == "Member") {
				/*$categoies = Package::all()->pluck('package_name')->toArray();
				if (in_array($payload['category'], $categoies )) {
					$this->memCategory = intval(array_search($payload['category'], $categoies ));
				}*/
				$this->memCategory = Package::select('id')->where('package_name', $payload['category'])->first()->id;
				$save = Customer::where('UID', '=', $payload['uid'])
					->update(['membership' => $this->memCategory]);
			} else {
				$save = Customer::where('UID', '=', $payload['uid'])
					->update(['membership' => Null]);
			}

		}

		return $success;

	}

	public function getCategoryWiseMemberCount() {

		$members = \DB::table('admin_members')
			->select(\DB::raw('count(*) as member_count,category'))
			/*->whereIn('payment', ["Quarterly", "Annually"])
			->whereNotIn('category', ["Free", "", "Trial"])
			->whereNotIn('type', ["Member"])*/
			->groupBy('category')
			->get()->toArray();

		$membersQuarterly = \DB::table('admin_members')
			->select(\DB::raw('count(*) as member_count,category,payment'))
			->whereIn('payment', ["Quarterly"])
			->whereNotIn('category', ["Free", "", "Trial"])
			->whereNotIn('type', ["Member"])
			->groupBy('category', 'payment')
			->get()->toArray();

		$membersAnnually = \DB::table('admin_members')
			->select(\DB::raw('count(*) as member_count,category,payment'))
			->whereIn('payment', ["Annually"])
			->whereNotIn('category', ["Free", "", "Trial"])
			->whereNotIn('type', ["Member"])
			->groupBy('category', 'payment')
			->get()->toArray();

		$memberPackages = \DB::table('admin_member_packages')
			->select('package_name', 'quarterly_payment', 'annual_payment')
			->get()->toArray();

		$annualPaymentArr = [];
		$quarterlyPaymentArr = [];
		foreach ($memberPackages as $memberPackage) {
			$annualPaymentArr[$memberPackage->package_name] = $memberPackage->annual_payment;
			$quarterlyPaymentArr[$memberPackage->package_name] = $memberPackage->quarterly_payment;
		}

		$revenueArrQuarterly = [];

		foreach ($membersQuarterly as $memberQuarterly) {
			if ($memberQuarterly->category == 'TBC')
				continue;
			$revenueQuarterly = $memberQuarterly->member_count * $quarterlyPaymentArr[$memberQuarterly->category];
			$revenueArrQuarterly[$memberQuarterly->category] = $revenueQuarterly;

		}

		$revenueArrAnnually = [];
		foreach ($membersAnnually as $memberAnnually) {
			if (!isset($annualPaymentArr[$memberAnnually->category]))
                continue;
			$revenueAnnually = $memberAnnually->member_count * $annualPaymentArr[$memberAnnually->category];
			$revenueArrAnnually[$memberAnnually->category] = $revenueAnnually;

		}

		$resultArr['members'] = $members;
		$resultArr['revenueArrQuarterly'] = $revenueArrQuarterly;
		$resultArr['revenueArrAnnually'] = $revenueArrAnnually;
		return \Response::json($resultArr);

	}

    /**
     * Save membership details data
     * @param array $payload
     * @return mixed
     */
    public function save_membershipDetails(Array $payload)
    {
        try {
            //input data
            $membership_uid = intval($payload['membership_uid']);
            $old_user_type = intval($payload['old_user_type']);
            if(isset($payload['membership_status'])){
                $membership_status = intval($payload['membership_status']);
            } else {
                $membership_status = '';
            }

            $user_type = intval($payload['user_type']);
            $old_membership_type = $payload['old_membership_type'];
            $membership_type = $payload['membership_type'];
            $old_membership_category = $payload['old_membership_category'];
            $membership_category = $payload['membership_category'];
            $old_package_amount = floatval($payload['old_package_amount']);
            $package_amount = floatval($payload['package_amount']);
            $old_membership_payment = $payload['old_membership_payment'];
            $membership_payment = $payload['membership_payment'];
            $old_membership_duration = $payload['old_membership_duration'];
            $membership_duration = $payload['membership_duration'];
            $old_membership_call_date = $payload['old_membership_call_date'];
            $membership_call_date = $payload['membership_call_date'];
            $old_membership_expiry_date = $payload['old_membership_expiry_date'];
            $membership_expiry_date = $payload['membership_expiry_date'];
            $old_membership_active_add_count = $payload['old_membership_active_add_count'];
            $membership_active_add_count = $payload['membership_active_add_count'];
            $old_membership_leads_count = $payload['old_membership_leads_count'];
            $membership_leads_count = $payload['membership_leads_count'];
            $old_membership_am = $payload['old_membership_am'];
            $membership_am = $payload['membership_am'];
            $old_pending_amount= floatval($payload['old_pending_amount']);
            $pending_amount = floatval($payload['pending_amount']);
            $membership_comment = $payload['membership_comment'];
            $only_generate_url = intval($payload['only_generate_url']);
            $old_phone_restrictions = intval($payload['old_phone_restrictions']);
            $phone_restrictions = intval($payload['new_phone_restrictions']);

            //login user data
            $login_user_data = \Auth::user();
            $changer_user_id = intval($login_user_data->id);
            $changer_user_name = $login_user_data->name;
            $admin_level = intval($login_user_data->admin_level);

            //process data
            $approved_user_id = 0;
            $output = array();
            $is_approve = 0;
            $is_expire = 0;
            $change_description = $changer_user_name;

            //checking change data
            if($admin_level > 2){
                if($only_generate_url == 0){
                    $is_approve = 1;
                    $is_expire = 1;
                }

                $change_description .= " updated following fields ";
                $output['description'] = 'Membership details updated successfully.';
            } else {
                $change_description .= " requested update following fields ";
                $output['description'] = 'After high level member approved your changers will be add.';
            }
            $change_description_data = '</br>';
            $membership_users = DB::table('users')
                ->select(DB::raw('CONCAT(`firstname`, " ", `surname`) AS "member"'),'Uemail')
                ->where('UID', '=', $membership_uid)
                ->get();
            $member_name = isset($membership_users[0]->member)?$membership_users[0]->member:'';
            $member_email = isset($membership_users[0]->Uemail)?$membership_users[0]->Uemail:'';
            $mail_changers = array();
            $mail_body = $changer_user_name .  ", requested update following fields for ".$member_name."(".$member_email.") \n ";
            $pro = 0;
            if($old_user_type != $user_type) {

                $mail_changers[$pro]['type'] = "User Type";
                if(intval($old_user_type) == 1){
                    $old_user_type_name = "Property Agent";
                } else if(intval($old_user_type) == 2){
                    $old_user_type_name = "PAA Agent";
                } else if(intval($old_user_type) == 3){
                    $old_user_type_name = "Pvt Seller";
                } else if(intval($old_user_type) == 4){
                    $old_user_type_name = "Ideal Home";
                } else if(intval($old_user_type) == 5){
                    $old_user_type_name= "Developer";
                } else if(intval($old_user_type) == 6){
                    $old_user_type_name = "Internal";
                } else {
                    $old_user_type_name = "Other";
                }
                if(intval($user_type) == 1){
                    $new_user_type_name = "Property Agent";
                } else if(intval($user_type) == 2){
                    $new_user_type_name = "PAA Agent";
                } else if(intval($user_type) == 3){
                    $new_user_type_name = "Pvt Seller";
                } else if(intval($user_type) == 4){
                    $new_user_type_name = "Ideal Home";
                } else if(intval($user_type) == 5){
                    $new_user_type_name = "Developer";
                } else if(intval($user_type) == 6){
                    $new_user_type_name = "Internal";
                } else {
                    $new_user_type_name = "Other";
                }
                $change_description_data .= "User Type : (". $old_user_type_name . " => " . $new_user_type_name . "),</br>";
                $mail_changers[$pro]['old'] = $old_user_type_name;
                $mail_changers[$pro]['new'] = $new_user_type_name;
                $pro++;

            }
            if($old_membership_type != $membership_type) {
                $change_description_data .= "Membership Type : (". $old_membership_type . " => " . $membership_type . "), </br>";
                $mail_changers[$pro]['type'] = "Membership Type";
                $mail_changers[$pro]['old'] = $old_membership_type;
                $mail_changers[$pro]['new'] = $membership_type;
                $pro++;

                // When Pending payment to Member Change - Ad Activity By Sasi Spenzer 2021-09-23 - 24 ** WFH
                if(($old_membership_type == 'Pending Payment') AND ($membership_type == 'Member')){

                    // Checking User's Payment Method - Online , Link or Offline

                    // Check if it is Online
                    $date = Carbon::now()->subDays(1);
                    $ppResults = DB::table('pp_payments')
                        ->where('user_id', '=', $membership_uid)
                        ->where('paid_date', '>=', $date->toDateString())
                        ->get();

                    if(isset($ppResults[0])){
                        $method_of_payment = 'Online';
                    } else {
                        $method_of_payment = 'Offline';
                    }

                    if($membership_type == 'Member'){
                        // Add Record to Actions BY Sasi Spenzer
                        $insertarray['uid'] = $membership_uid;
                        $insertarray['action'] = 'Membership Activated';
                        $insertarray['qty'] = '';
                        $insertarray['value'] = '';
                        $insertarray['ad_id'] = '';
                        if($membership_category == 'Single Ad'){
                            $insertarray['comments'] = 'Payment made '.$method_of_payment.' by user for : Rs.'.$package_amount.'
                         For Single Ad';
                        } else {
                            $insertarray['comments'] = 'Payment made '.$method_of_payment.' by user for : Rs.'.$package_amount.'
                         For '.$membership_category.' Membership';
                        }

                        $insertarray['reminder'] = '';
                        $insertarray['date_time'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                        $insertarray['by'] = 'System';
                        $insertarray['created_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                        $insertarray['updated_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');

                        $add = \DB::table('admin_members_actions')->insert($insertarray);
                    }

                }


            }
            if($old_membership_category != $membership_category) {
                $change_description_data .= "Membership Category : (". $old_membership_category . " => " . $membership_category . "),</br>";
                $mail_changers[$pro]['type'] = "Membership Category";
                $mail_changers[$pro]['old'] = $old_membership_category;
                $mail_changers[$pro]['new'] = $membership_category;
                $pro++;
            }
            if($old_package_amount != $package_amount) {
                $change_description_data .= "Package Amount : (Rs.". number_format((float)$old_package_amount, 2, '.', '') . " => Rs." . number_format((float)$package_amount, 2, '.', '') . "),</br>";
                $mail_changers[$pro]['type'] = "Package Amount";
                $mail_changers[$pro]['old'] = $old_package_amount;
                $mail_changers[$pro]['new'] = $package_amount;
                $pro++;
            }
            if($old_membership_payment != $membership_payment) {
                if(intval($old_membership_payment) > 0){
                    $old_membership_payment_str = "membership.payment.".$old_membership_payment;
                    $old_membership_payment_view = config($old_membership_payment_str);

                } else {
                    $old_membership_payment_view = $old_membership_payment;
                }

                if(intval($membership_payment) > 0){
                    $membership_payment_str = "membership.payment.".$membership_payment;
                    $membership_payment_view = config($membership_payment_str);

                } else {
                    $old_membership_payment_view = $old_membership_payment;
                    $membership_payment_view = $membership_payment;
                }
                $change_description_data .= "Membership Payment : (". $old_membership_payment_view . " => " . $membership_payment_view . "),</br>";
                $mail_changers[$pro]['type'] = "Membership Payment";
                $mail_changers[$pro]['old'] = $old_membership_payment_view;
                $mail_changers[$pro]['new'] = $membership_payment_view;
                $pro++;
            }
            if($old_membership_duration != $membership_duration) {
                $change_description_data .= "Membership Duration : (". $old_membership_duration . " => " . $membership_duration . "),</br>";
                $mail_changers[$pro]['type'] = "Membership Duration";
                $mail_changers[$pro]['old'] = $old_membership_duration;
                $mail_changers[$pro]['new'] = $membership_duration;
                $pro++;
            }
            if($old_membership_call_date != $membership_call_date) {
                $change_description_data .= "Payment Expiry Date : (". $old_membership_call_date . " => " . $membership_call_date . "),</br>";
                $mail_changers[$pro]['type'] = "Payment Expiry Date";
                $mail_changers[$pro]['old'] = $old_membership_call_date;
                $mail_changers[$pro]['new'] = $membership_call_date;
                $pro++;
            }
            if($old_membership_expiry_date != $membership_expiry_date) {
                $change_description_data .= "Membership Expiry date : (". $old_membership_expiry_date . " => " . $membership_expiry_date . "),</br>";
                $mail_changers[$pro]['type'] = "Membership Expiry date";
                $mail_changers[$pro]['old'] = $old_membership_expiry_date;
                $mail_changers[$pro]['new'] = $membership_expiry_date;
                $pro++;

                    // When Chnage of Expiry Date By Sasi Spenzer 2021-09-23 ** WFH
                    // Add Record to Actions BY Sasi Spenzer
                    if($membership_type == 'Member'){
                        if($old_membership_type != 'Pending Payment'){

                            // Check only if period is more than 10 days
                            $date1_ts = strtotime($old_membership_expiry_date);
                            $date2_ts = strtotime($membership_expiry_date);
                            $diff = $date2_ts - $date1_ts;
                            $daysInAdvance =  round($diff / 86400);

                            if($daysInAdvance >= 10){
                                $insertarray['uid'] = $membership_uid;
                                $insertarray['action'] = 'Membership Renewed';
                                $insertarray['qty'] = '';
                                $insertarray['value'] = '';
                                $insertarray['ad_id'] = '';

                                $insertarray['comments'] = 'Offline payment made by user for : Rs.'.$package_amount.'';

                                $insertarray['reminder'] = '';
                                $insertarray['date_time'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                                $insertarray['by'] = 'System';
                                $insertarray['created_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                                $insertarray['updated_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');

                                $add = \DB::table('admin_members_actions')->insert($insertarray);
                            }

                        }


                        // Added Missing Log By Sasi Spenzer 2021-11-22 ** WFH

                        $detailsArray['changer_user_id'] =  $changer_user_id;
                        $detailsArray['changing_description'] =  'expire date Changed to  : '.$membership_expiry_date;
                        $detailsArray['changed_description'] =  'expire date Changed to  : '.$membership_expiry_date;;
                        $detailsArray['is_approve'] =  1;
                        $detailsArray['is_expire'] = 1;
                        $detailsArray['is_only_generate_url'] =  1;
                        $detailsArray['member_user_id'] =  $membership_uid;
                        $detailsArray['created_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                        $detailsArray['updated_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');

                        $add = \DB::table('membership_details_log')->insert($detailsArray);

                    }



            }
            if($old_membership_am != $membership_am) {
                $change_description_data .= "AM : (". $old_membership_am . " => " . $membership_am . "),</br>";
                $mail_changers[$pro]['type'] = "AM";
                $mail_changers[$pro]['old'] = $old_membership_am;
                $mail_changers[$pro]['new'] = $membership_am;
            }
            if($old_pending_amount != $pending_amount) {
                $change_description_data .= "Pending Amount : (". number_format((float)$old_pending_amount, 2, '.', '') . " => " . number_format((float)$pending_amount, 2, '.', '') . "),</br>";
                $mail_changers[$pro]['type'] = "Pending Amount";
                $mail_changers[$pro]['old'] = $old_pending_amount;
                $mail_changers[$pro]['new'] = $pending_amount;
            }
            if($old_phone_restrictions != $phone_restrictions && $admin_level > 2) {
                if(intval($old_phone_restrictions) == 1) {
                    $old_phone_restrictions_view = 'Yes';
                } else {
                    $old_phone_restrictions_view = 'No';
                }
                if(intval($phone_restrictions) == 1) {
                    $phone_restrictions_view = 'Yes';
                } else {
                    $phone_restrictions_view = 'No';
                }
                $change_description_data .= "Phone Restrictions : (". $old_phone_restrictions_view . " => " . $phone_restrictions_view . "),</br>";
                $mail_changers[$pro]['type'] = "Phone Restrictions";
                $mail_changers[$pro]['old'] = $old_phone_restrictions;
                $mail_changers[$pro]['new'] = $phone_restrictions;
            }

            $change_description_data = substr($change_description_data,0,-2).'.';
            $change_description .= $change_description_data;

            //default time zone set sri lanka colombo
            date_default_timezone_set('Asia/Colombo');
            $today = date('Y-m-d H:i:s');
            if($only_generate_url == 1) {
                $is_approve = 0;
            }
            //For add membership_details_log data
            $membership_details_log_id = DB::table('membership_details_log')->insertGetId([
                'changer_user_id' => $changer_user_id, 'approve_user_id' => $approved_user_id,
                'member_user_id' => $membership_uid, 'is_approve' => $is_approve,
                'new_user_type_id' => $user_type, 'old_user_type_id' => $old_user_type, 'old_membership_type' => $old_membership_type,
                'new_membership_type' => $membership_type, 'old_membership_category' => $old_membership_category,
                'new_membership_category' => $membership_category, 'old_package_amount' => $old_package_amount,
                'new_package_amount' => $package_amount, 'old_membership_payment' => $old_membership_payment,
                'new_membership_payment' => $membership_payment, 'old_membership_duration' => $old_membership_duration,
                'new_membership_duration' => $membership_duration, 'old_membership_call_date' => $old_membership_call_date,
                'new_membership_call_date' => $membership_call_date, 'old_membership_expiry_date' => $old_membership_expiry_date,
                'new_membership_expiry_date' => $membership_expiry_date, 'old_membership_active_add_count' => $old_membership_active_add_count,
                'new_membership_active_add_count' => $membership_active_add_count, 'old_membership_leads_count' => $old_membership_leads_count,
                'new_membership_leads_count' => $membership_leads_count, 'old_membership_am' => $old_membership_am, 'new_membership_am' => $membership_am,
                'old_pending_amount' => $old_pending_amount, 'new_pending_amount' => $pending_amount, 'is_only_generate_url' => $only_generate_url,
                'created_at' => $today, 'updated_at' => $today, 'changing_description' => ($mail_body. ' '.$change_description_data),
                'changed_description' => $change_description, 'is_old_phone_restriction' => $old_phone_restrictions, 'is_new_phone_restriction' => $phone_restrictions
            ]);

            //For add membership_latest_data
            $membership_latest_data_id = DB::table('membership_latest_data')->insertGetId([
                'member_user_id' => $membership_uid,
                'membership_type' => $membership_type,
                'membership_category' => $membership_category,
                'membership_duration' => $membership_duration,
                'membership_expire_data' => $membership_expiry_date,
                'payment_expire_data' => $membership_call_date,
                'membership_amount' => $package_amount,
                'membership_pending_amount' => $pending_amount,
                'is_expire' => $is_expire,
                'created_at' => $today,
                'updated_at' => $today
            ]);

            // Update payment_status status for All users By Sasi Spenzer 2021-09-17 ** WFH
            if(!empty($membership_status)){
                DB::table('admin_members')
                    ->where('uid', $membership_uid)
                    ->update([
                        'payment_status' => $membership_status
                    ]);
            }


            //For high level permission users
            if($admin_level > 2 && $only_generate_url == 0) {
                $membership_admin_member_data = DB::table('admin_members')
                    ->select('id AS admin_members_id')
                    ->where('uid', '=', $membership_uid)
                    ->get();
                if(isset($membership_admin_member_data[0]) && intval($membership_admin_member_data[0]->admin_members_id) > 0) {
                    DB::table('admin_members')
                        ->where('uid', $membership_uid)
                        ->update([
                            'user_type_id' => $user_type, 'type' => $membership_type, 'category' => $membership_category, 'package_amount' => $package_amount,
                            'payment' => $membership_payment, 'duration' => $membership_duration, 'payment_exp_date' => $membership_call_date,
                            'call_date_time' => $membership_call_date, 'expiry' => $membership_expiry_date, 'active_ads' => $membership_active_add_count,
                            'pending_amount' => $pending_amount, 'leads' => $membership_leads_count, 'am' => $membership_am, 'is_phone_restriction' => $phone_restrictions,
                            'created_at' => $today, 'updated_at' => $today
                        ]);
                } else {
                    DB::table('admin_members')
                        ->insert([
                            'uid' => $membership_uid, 'user_type_id' => $user_type, 'type' => $membership_type, 'category' => $membership_category, 'package_amount' => $package_amount,
                            'payment' => $membership_payment, 'duration' => $membership_duration, 'payment_exp_date' => $membership_call_date,
                            'call_date_time' => $membership_call_date, 'expiry' => $membership_expiry_date, 'active_ads' => intval($membership_active_add_count),
                            'pending_amount' => $pending_amount, 'leads' => intval($membership_leads_count), 'am' => $membership_am, 'is_phone_restriction' => $phone_restrictions,
                            'created_at' => $today, 'updated_at' => $today
                        ]);
                }

                $membership_category_data = DB::table('admin_member_packages')
                    ->select('id AS membership_category_id')
                    ->where('package_name', '=', $membership_category)
                    ->get();
                $membership_category_id = $membership_category_data[0]->membership_category_id;
                if($membership_category_id != 0) {
                    DB::table('users')
                        ->where('uid', $membership_uid)
                        ->update([
                            'membership' => $membership_category_id
                        ]);
                }

                $approved_user_id = $changer_user_id;
                $is_approve = 1;
            } else {
                if ($only_generate_url == 0) {
                    //For approval mail
                    $approval_person = "LankaPropertyWeb";
                    $brandName = "LankaPropertyWeb";
                    $brandTelephone = '0117 167 167';
                    //$brandEmail = 'info@lpw.lk';
                    $brandEmail = 'noreply@lankapropertyweb.com';
                    $mail_url = env('APP_URL','https://www.lankapropertyweb.com/su/LPW-Admin/public') .'/customer/details/approval/'.$membership_details_log_id;
                    $mail_url_name = "Please click approval above changers";

                    $approval_user_level =2;
                    $approval_users = DB::table('admin_users')
                        ->select('name', 'email')
                        //comment for testing purpose
                        ->where('admin_level', '>', $approval_user_level)
                        //->where('username', '=', 'chamika')
                        ->get();
                    $mail_body .= " (Not paid)";
                    //dd($mail_body);
                    foreach ($approval_users AS $approval_user){
                        //dd($approval_user->name);
                        $approval_username = $approval_user->name;
                        $approval_email = $approval_user->email;
                        Mail::send(['html' =>'member.approval_mail'],
                            ['approval_person' => $approval_person, 'email_body' => $mail_body, 'brandName' => $brandName,
                                'mail_changers' => $mail_changers, 'brandTelephone' => $brandTelephone, 'brandEmail' => $brandEmail,
                                'mail_url' => $mail_url, 'mail_url_name' => $mail_url_name, 'membership_comment' => $membership_comment
                            ],
                            function ($message) use ($approval_email, $approval_person, $brandEmail, $approval_username) {

                                $message->from($brandEmail, $approval_person);

                                $message->to($approval_email, $approval_username);
                                //$message->to('roshan@lpw.lk', $approval_username);

                                $message->subject("Membership changes approval");

                            });
                    }
                }
            }

            if($membership_details_log_id > 0 && $only_generate_url == 0){
                DB::table('membership_details_log')
                    ->where('membership_details_log_id', '<' , $membership_details_log_id)
                    ->where('member_user_id', '=', $membership_uid)
                    ->update(['is_expire' => 1]);
            }

            if($membership_latest_data_id > 0){
                DB::table('membership_latest_data')
                    ->where('membership_latest_data_id', '<', $membership_latest_data_id)
                    ->where('member_user_id', '=', $membership_uid)
                    ->delete();
            }
            $output['status'] = 'Succeed';
            $output['is_view_msg'] = 1;
            $output['admin_level'] = $admin_level;


        } catch (\Exception $e) {
            //dd($e);
            $output['status'] = 'Failed';
            $output['is_view_msg'] = 1;
            $output['description'] = "Membership details didn't change correctly.";
            $output['admin_level'] = 0;
        }
        return $output;
    }

    /**
     * Get membership approval data
     * @param $approvalId
     * @return mixed
     */
    public function membershipDataApproval($approvalId)
    {
        try {
            $output = array();
            //login user data
            $login_user_data = \Auth::user();
            $changer_user_id = intval($login_user_data->id);
            $changer_user_name = $login_user_data->name;
            $admin_level = intval($login_user_data->admin_level);
            if($admin_level > 2) {
                $membership_details_log_data = DB::table('membership_details_log')
                    ->select('is_approve', 'is_expire', 'new_membership_type', 'new_membership_category', 'new_package_amount',
                        'new_membership_payment', 'new_membership_duration', 'new_membership_call_date', 'new_membership_expiry_date',
                        'new_membership_active_add_count', 'new_membership_leads_count', 'new_membership_am', 'member_user_id',
                        'changing_description', 'new_pending_amount', 'is_new_phone_restriction')
                    ->where('membership_details_log_id', '=', $approvalId)
                    ->get();
                $is_approve = isset($membership_details_log_data[0]->is_approve) ? $membership_details_log_data[0]->is_approve : 2;
                $is_expire = isset($membership_details_log_data[0]->is_expire) ? $membership_details_log_data[0]->is_expire : 2;
                if (intval($is_approve) == 1 && intval($is_expire) < 2) {
                    $output['status'] = "error";
                    $output['msg'] = "All ready approved this request.";
                } else if (intval($is_expire) == 1) {
                    $output['status'] = "error";
                    $output['msg'] = "This request is expired.";
                } else if (intval($is_approve) == 0 && intval($is_expire) == 0) {
                    //default time zone set sri lanka colombo
                    date_default_timezone_set('Asia/Colombo');
                    $today = date('Y-m-d H:i:s');

                    $membership_uid = $membership_details_log_data[0]->member_user_id;
                    $membership_type = $membership_details_log_data[0]->new_membership_type;
                    if($membership_type == "Pending Payment") {
                        $membership_type = "Member";
                    }
                    $membership_category = $membership_details_log_data[0]->new_membership_category;
                    $package_amount = $membership_details_log_data[0]->new_package_amount;
                    $membership_payment = $membership_details_log_data[0]->new_membership_payment;
                    $membership_duration = $membership_details_log_data[0]->new_membership_duration;
                    $membership_call_date = $membership_details_log_data[0]->new_membership_call_date;
                    $membership_expiry_date = $membership_details_log_data[0]->new_membership_expiry_date;
                    $membership_active_add_count = $membership_details_log_data[0]->new_membership_active_add_count;
                    $membership_leads_count = $membership_details_log_data[0]->new_membership_leads_count;
                    $membership_am = $membership_details_log_data[0]->new_membership_am;
                    $pending_amount = $membership_details_log_data[0]->new_pending_amount;
                    $is_new_phone_restriction = intval($membership_details_log_data[0]->is_new_phone_restriction);

                    //For approval description
                    $changing_description = $membership_details_log_data[0]->changing_description;
                    $find_me   = 'update ';
                    $pos = strrpos($changing_description, $find_me);
                    $approve_description = $changer_user_name . ' approved changers in ' . substr($changing_description,($pos+7));

                    //For admin members update
                    $membership_admin_member_data = DB::table('admin_members')
                        ->select('id AS admin_members_id')
                        ->where('uid', '=', $membership_uid)
                        ->get();
                    if(isset($membership_admin_member_data[0]) && intval($membership_admin_member_data[0]->admin_members_id) > 0) {
                        DB::table('admin_members')
                            ->where('uid', $membership_uid)
                            ->update([
                                'type' => $membership_type, 'category' => $membership_category, 'package_amount' => $package_amount,
                                'payment' => $membership_payment, 'duration' => $membership_duration, 'payment_exp_date' => $membership_call_date,
                                'call_date_time' => $membership_call_date, 'expiry' => $membership_expiry_date, 'active_ads' => $membership_active_add_count,
                                'leads' => $membership_leads_count, 'pending_amount' => $pending_amount,
                                'am' => $membership_am, 'is_phone_restriction' => $is_new_phone_restriction, 'created_at' => $today, 'updated_at' => $today
                            ]);
                    } else {
                        DB::table('admin_members')
                            ->insert([
                                'uid' => $membership_uid, 'type' => $membership_type, 'category' => $membership_category, 'package_amount' => $package_amount,
                                'payment' => $membership_payment, 'duration' => $membership_duration, 'payment_exp_date' => $membership_call_date,
                                'call_date_time' => $membership_call_date, 'expiry' => $membership_expiry_date, 'active_ads' => intval($membership_active_add_count),
                                'leads' => intval($membership_leads_count), 'pending_amount' => $pending_amount,
                                'am' => $membership_am, 'is_phone_restriction' => $is_new_phone_restriction, 'created_at' => $today, 'updated_at' => $today
                            ]);
                    }

                    $membership_category_data = DB::table('admin_member_packages')
                        ->select('id AS membership_category_id')
                        ->where('package_name', '=', $membership_category)
                        ->get();
                    $membership_category_id = $membership_category_data[0]->membership_category_id;
                    if($membership_category_id != 0) {
                        DB::table('users')
                            ->where('uid', $membership_uid)
                            ->update([
                                'membership' => $membership_category_id
                            ]);
                    }

                    //For approval log update
                    DB::table('membership_details_log')
                        ->where('membership_details_log_id', '=', $approvalId)
                        ->update([
                            'approve_user_id' => $changer_user_id, 'updated_at' => $today,
                            'is_approve' => 1, 'approve_at' => $today, 'approve_description' => $approve_description
                        ]);
                    //For expire membership latest data
                    DB::table('membership_latest_data')
                        ->where('member_user_id', '=', $membership_uid)
                        ->update([
                            'is_expire' => 1,
                        ]);

                    $output['status'] = "success";
                    $output['msg'] = "Membership approved successfully.";
                } else {
                    $output['status'] = "error";
                    $output['msg'] = "Wrong request contact administrator.";
                }
            } else {
                $output['status'] = "error";
                $output['msg'] = "You haven't permission to approve request.";
            }
        } catch (\Exception $e) {
            $output['status'] = "error";
            $output['msg'] = $e;
        }
        return $output;
    }
}
