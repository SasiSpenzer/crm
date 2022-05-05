<?php

namespace App\Http\Controllers;

use App\Contracts\CustomerInterface;
use Illuminate\Http\Request;
use App\Customer;
use App\MemberAction;
use App\Member;
use App\Advertisement;
use Auth;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;


class CustomerController extends Controller {

	protected $customer;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CustomerInterface $customer) {
		$this->middleware('auth');
		$this->customer = $customer;
	}

	/**
	 * Show the customer dashboard.
	 *
	 * @return view
	 */
	public function index() {
		return view('customer.index');
	}

	/**
	 * Get customer and memberships datatable json
	 * @return JSON Datatable json object
	 */
	function listUsers() {

		$today = date('Y-m-d');

		$customer = Customer::leftjoin(
			'admin_members',
			'admin_members.uid',
			'=',
			'users.UID'
		)->whereRaw('ABS(TIMESTAMPDIFF(DAY, users.reg_date, ?)) > 2', $today)
		//old comment
		/*->leftjoin(
			'admin_members_actions',
			'users.UID',
			'=',
			'admin_members_actions.uid'
		)*/->select(array(
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


        /*$customer = \DB::table('users_all_data')
            ->select(
                array(
                    'users_all_data.ads_count',
                    'users_all_data.firstname',
                    'users_all_data.surname',
                    'users_all_data.Uemail',
                    'users_all_data.last_updated_at',
                    'users_all_data.UID',
                    'users_all_data.expiry',
                    'users_all_data.call_date_time',
                    'users_all_data.category',
                    'users_all_data.am',
                )
            )->orderBy('users_all_data.last_updated_at', 'desc');*/
		return Datatables::of($customer)
			->make(true);

	}

	public function noneMembers()
	{
                $customer = Customer::leftjoin(
						'admin_members_actions',
						'users.UID',
						'=',
						'admin_members_actions.uid'
					)->whereNull('membership')->limit(100);
		$customer = $customer->select(array(
			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			'users.UID AS UID',
                        'users.reg_date AS reg_date',
			'admin_members_actions.updated_at AS last_updated_at',
			'admin_members_actions.comments AS am',
		));
        //return response()->json(['test' => 'test']);
		return Datatables::of($customer)->make(true);
	}

	public function deactivate(Request $request)
	{
		$user_id = $request->input('user_id');
		Member::where('uid', $user_id)->update([
					'type' => 'Inactive', 'category' => 'Free'
				]);
		Customer::where('UID', $user_id)->update([
					'membership' => null
				]);
		Advertisement::where('UID', $user_id)->update(['is_active' => 0]);
		return 'success';
	}

	/**
	 * Get customer and memberships datatable json
	 * @return JSON Datatable json object
	 */
	function listMembers() {
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT u.`ads_count` AS 'ads_count', u.`firstname` AS 'firstname', u.`surname` AS 'surname', 
                    CONCAT(u.`firstname`, ' ', u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail', am.`updated_at` AS 'last_updated_at',
                    am.`updated_at` AS 'last_updated_at',u.`UID` AS 'UID', am.`expiry` AS 'expiry', am.`call_date_time` AS 'call_date_time', 
                    am.`category` AS 'category', am.`am` AS 'am'
                    FROM `admin_members` AS am
                    LEFT JOIN `users` AS u ON am.`uid` = u.`UID`
                    WHERE am.category != 'Single Ad' AND am.type = 'Member' AND u.`ads_count` > 0 AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%'  OR u.`Uemail` LIKE '%".$search_data."%'  
                    OR u.`am` LIKE '%".$search_data."%' OR u.`category` LIKE '%".$search_data."%' )
                    ORDER BY am.`id` DESC";
                    //LIMIT 100";
        } else {
        	$date = 'Y-m-d H:i:s';
        	$date_start = date($date, strtotime(date('Y-m-d') . ' - 2 days'));
            $sql = "SELECT u.`ads_count` AS 'ads_count', u.`firstname` AS 'firstname', u.`surname` AS 'surname', 
                    CONCAT(u.`firstname`, ' ', u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail', am.`updated_at` AS 'last_updated_at',
                    am.`updated_at` AS 'last_updated_at',u.`UID` AS 'UID', am.`expiry` AS 'expiry', am.`call_date_time` AS 'call_date_time', 
                    am.`category` AS 'category', am.`am` AS 'am'
                    FROM `admin_members` AS am
                    LEFT JOIN `users` AS u ON am.`uid` = u.`UID`
                    WHERE am.category != 'Single Ad' AND am.type = 'Member' AND u.`ads_count` > 0 AND u.`reg_date` < '".$date_start."'
                    ORDER BY am.`id` DESC";
                    //LIMIT 100";
        }
        //echo $sql; exit();
        $customer = DB::select($sql);
        return Datatables::of($customer)->make(true);

	}

	/**
	 * Get customer and memberships datatable json
	 * @return JSON Datatable json object
	 */
	function listActiveMembers() {
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $today = date('Y-m-d');

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT u.`ads_count` AS 'ads_count', u.`firstname` AS 'firstname', u.`surname` AS 'surname', 
                    CONCAT(u.`firstname`, ' ', u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail', am.`updated_at` AS 'last_updated_at',
                    am.`updated_at` AS 'last_updated_at',u.`UID` AS 'UID', am.`expiry` AS 'expiry', am.`call_date_time` AS 'call_date_time', 
                    am.`category` AS 'category', am.`am` AS 'am'
                    FROM `admin_members` AS am
                    LEFT JOIN `users` AS u ON am.`uid` = u.`UID`
                    WHERE am.type = 'Member' AND am.category <> 'Single Ad' AND am.category <> 'Free' AND am.category NOT LIKE '%Business%' AND am.payment NOT LIKE 'Free%' AND (user_type_id = 1 OR user_type_id = 3) AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%'  OR u.`Uemail` LIKE '%".$search_data."%'  
                    OR u.`am` LIKE '%".$search_data."%' OR u.`category` LIKE '%".$search_data."%' ) AND am.`payment_exp_date` < '".$today."'
                    ORDER BY am.`id` DESC";
                    //LIMIT 100";
        } else {
        	$date = 'Y-m-d H:i:s';
        	$date_start = date($date, strtotime(date('Y-m-d') . ' - 2 days'));
            $sql = "SELECT u.`ads_count` AS 'ads_count', u.`firstname` AS 'firstname', u.`surname` AS 'surname', 
                    CONCAT(u.`firstname`, ' ', u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail', am.`updated_at` AS 'last_updated_at',
                    am.`updated_at` AS 'last_updated_at',u.`UID` AS 'UID', am.`expiry` AS 'expiry', am.`call_date_time` AS 'call_date_time', 
                    am.`category` AS 'category', am.`am` AS 'am'
                    FROM `admin_members` AS am
                    LEFT JOIN `users` AS u ON am.`uid` = u.`UID`
                    WHERE am.type = 'Member' AND am.category <> 'Single Ad' AND am.category <> 'Free' AND am.category NOT LIKE '%Business%' AND am.payment NOT LIKE 'Free%' AND (user_type_id = 1 OR user_type_id = 3)";
                    //LIMIT 100";
        }
        //echo $sql; exit();
        $customer = DB::select($sql);
        return Datatables::of($customer)->make(true);

	}

	/**
	 * Get customer and memberships datatable json
	 * @return JSON Datatable json object
	 */
	function listRevenue() {
		$customer = Customer::leftjoin(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)/*->leftjoin(
			'admin_members_actions',
			'users.UID',
			'=',
			'admin_members_actions.uid'
		)*/->select(array(
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

		return Datatables::of($customer)
			->make(true);

	}

	/**
	 * Data of To Be Expire
	 * @return JSON Datatable json object
	 */
	public function toBeExpire($time = null)
	{
		$date = Carbon::now();

		if($time == null){
			return view('member.tobe_expire');
		}
		elseif($time == 'today'){

			$customer = Customer::where('expiry', $date);

		}elseif($time == 'one-week-before'){

			$from = $date->addDay();
			$to = Carbon::now()->addWeek();
			$customer = Customer::whereBetween('expiry', [$from, $to]);

		}elseif($time == 'one-month-before'){

			$from = $date->addDay();
			$to = Carbon::now()->addMonth();
			$customer = Customer::whereBetween('expiry', [$from, $to]);

		}

		$customer = $customer->leftjoin(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)->leftjoin(
		    'admin_users',
            'admin_users.username',
            '=',
            'admin_members.am'
        )->select([
			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			'admin_members.updated_at AS last_updated_at',
			'users.UID AS UID',
			'admin_members.expiry AS expiry',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.category AS category',
			'admin_users.name AS am',

		]);

		return Datatables::of($customer)
				->make(true);
	}

	/**
	 * Data of To Be Expire
	 * @return JSON Datatable json object
	 */
	public function expired($time = null)
	{
		$date = Carbon::now()->subDay();

		if($time == null){
			return view('member.expired');
		}
		elseif($time == 'all'){

			$customer = Customer::where('expiry', '<', $date);

		}
		elseif($time == 'yesterday'){

			$customer = Customer::where('expiry', $date);

		}elseif($time == 'after-one-week'){

			$to = $date->subDay();
			$from = Carbon::now()->subWeek();
			$customer = Customer::whereBetween('expiry', [$from, $to]);

		}elseif($time == 'after-one-month'){

			$to = $date->subDay();
			$from = Carbon::now()->subMonth();
			$customer = Customer::whereBetween('expiry', [$from, $to]);

		}

		$customer = $customer->leftjoin(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)->select([

			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			'admin_members.updated_at AS last_updated_at',
			'users.UID AS UID',
			'admin_members.expiry AS expiry',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.category AS category',
			'admin_members.am AS am',

		]);

		return Datatables::of($customer)
				->make(true);
	}

	/**
	 * Return the view ads count and membership details for the customers.
	 *
	 * @return view
	 */
	public function customerMembershipsView() {
		return view('customer.list_customer_membership');
	}

	/**
	 * List the ads count and membership details for the customers.
	 */
	public function listCustomerMemberships(Request $request) {
	    $type = intval($request->query()['type']);
	    $search_data = "";
		if($request->query()['search']['value'] != null) {
            $search_data = $request->query()['search']['value'];
            $searching = true;
        }
        else {
            $searching = false;
        }
		return $this->customer->listCustomerMemberships($type, $searching, $search_data);
	}

	public function noneMembershipsView() {
		return view('customer.list_none_membership');
	}

	public function noneMemberships(Request $request) {
        $data = $request->input();
		return $this->customer->noneMemberships($data);
	}

	/**
	 * Get member and user details by User id.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getByUID(Request $request) {

		return $this->customer->getByUID($request->input('uid'));
	}

	public function getCustomerRegister(){
        $error = '';
        $email = $this->assignNewEmail(); // make new email
        return view('customer.register')->with(compact('error','email'));
    }
    public function setUploadForm(){
        $error_list = '';
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user['admin_level'] >= 3) {
            return view('customer.upload')->with(compact('error_list'));
        } else {
            return response(view('dashboard.403'),403);
        }

    }
    public function uploadCustomerData (Request  $request){
        // By Sasi Spenzer
        // Why I'm doing this without a Laravel Package ? - Requested
	    // Get file From Form
        $uploadFile = $request->file('customer_file');
        $filePath = $uploadFile->getRealPath();

        // Read The File
        $file = fopen($filePath,'r');

        // Get Headers
        $headers = fgetcsv($file);
        $headerArray = array();
        foreach ($headers as $key=>$value){

            // validate headers
            $header = strtolower($value);
            $header = preg_replace('/[^a-z]/','',$header);
            array_push($headerArray,$header);
        }
        $error_list = array();
        while($columns = fgetcsv($file)){

            if($columns[0] == ''){
                continue;
            }else {
                $data = array_combine($headerArray,$columns);
                $first_name = $data['firstname'];
                $last_name = $data['surname'];
                $email = $data['email'];

                if(empty($email)){
                    $email =  $this->assignNewEmail();
                }

                $telephone = $data['phone'];
                $source = $data['source'];
                $link = $data['link'];
                $agent = $data['type'];
                $am = strtolower($data['am']);
                $checktaken = $this->checkIfTaken($email);
                if(isset($checktaken[0]->UID)){
                    array_push($error_list,$email);
                  continue;
                }else {
                    // Making User Data Array
                    $agent = strtolower($agent);
                    switch ($agent) {
                        case 'owner':
                            $agent = 'O';
                            break;
                        case 'agent':
                            $agent = 'A';
                            break;
                        case 'developer':
                            $agent = 'D';
                            break;
                        case 'buyer':
                            $agent = 'B';
                            break;
                        default:
                            $agent = 'O';
                    }

                    $userDataArray = array();
                    $act_key = md5(uniqid(rand()));
                    $userDataArray['password'] = '';
                    $userDataArray['firstname'] = $first_name;
                    $userDataArray['username'] = $first_name."-CRM";
                    $userDataArray['surname'] = $last_name;
                    $userDataArray['Uemail'] =  $email;
                    $userDataArray['mobile_no'] =  $telephone;
                    $userDataArray['Ucountry'] =  'Sri Lanka';
                    $userDataArray['agent'] =  $agent;
                    $userDataArray['reg_date'] =  date("Y-m-d H:i:s");
                    $userDataArray['source'] =  'CRM';
                    $userDataArray['activation_key'] =  $act_key;
                    $userDataArray['validated'] =  'Y';
                    $userDataArray['is_sha256'] =  1;
                    $userDataArray['user_type'] =  $agent;
                    $userDataArray['subscribe'] =  'Yes';

                    if($agent == 'A'){
                        $user_type_id = 1;
                    } else if ($agent == 'D') {
                        $user_type_id = 5;
                    } else if ($agent == 'O') {
                        $user_type_id = 3;
                    } else {
                        $user_type_id = 3;
                    }


                    try{
                        $UID = $this->registerUserData($userDataArray);
                        $this->registerAdminMember($UID,$am,$source,$link,$telephone,$user_type_id);
                        $this->makeActivityRecorded($UID);
                        array_push($error_list,'List Imported Successfully!');
                    }catch (\Exception $exception){
                        return $exception->getMessage();
                    }


                }
            }
        }
        return view('customer.upload')->with(compact('error_list'));

    }
    public function getCustomerRegisterData(Request $request){

	   $first_name = $request->input('first_name');
	   $last_name = $request->input('last_name');
	   $email = $request->input('email');
	   $telephone = $request->input('telephone');
	   $source = $request->input('source');
	   $link = $request->input('link');
	   $agent = $request->input('agent');
	   $checktaken = $this->checkIfTaken($email);

	   if(isset($checktaken[0]->UID)){
	       $error = 'The email you entered already exists in our system.';
           return view('customer.register')->with(compact('error'));
       }else {
            // Making User Data Array
           // Making User Data Array
           $agent = strtolower($agent);
           switch ($agent) {
               case 'owner':
                   $agent = 'O';
                   break;
               case 'agent':
                   $agent = 'A';
                   break;
               case 'developer':
                   $agent = 'D';
                   break;
               case 'buyer':
                   $agent = 'B';
                   break;
               default:
                   $agent = 'O';
           }
           $userDataArray = array();
           $act_key = md5(uniqid(rand()));
           $userDataArray['password'] = '';
           $userDataArray['firstname'] = $first_name;
           $userDataArray['username'] = $first_name."-".$last_name."-CRM";
           $userDataArray['surname'] = $last_name;
           $userDataArray['Uemail'] =  $email;
           $userDataArray['Uemail'] =  $email;
           $userDataArray['mobile_no'] =  $telephone;
           $userDataArray['Ucountry'] =  'Sri Lanka';
           $userDataArray['agent'] =  $agent;
           $userDataArray['reg_date'] =  date("Y-m-d H:i:s");
           $userDataArray['source'] =  'CRM';
           $userDataArray['activation_key'] =  $act_key;
           $userDataArray['validated'] =  'Y';
           $userDataArray['is_sha256'] =  1;
           $userDataArray['user_type'] =  $agent;
           $userDataArray['subscribe'] =  'Yes';

           if($agent == 'A'){
               $user_type_id = 1;
           } else if ($agent == 'D') {
               $user_type_id = 5;
           } else if ($agent == 'O') {
               $user_type_id = 3;
           } else {
               $user_type_id = 3;
           }

           $username = Auth::user()->username;
           $UID = $this->registerUserData($userDataArray);
           $this->registerAdminMember($UID,$username,$source,$link,$telephone,$user_type_id);
           $this->makeActivityRecorded($UID);
           return redirect('/customer/register?success=1');
       }
    }

    public function checkIfTaken($email){
        try{
            $sql = "SELECT UID, Uemail FROM users WHERE Uemail = '" . $email . "';";
            $customer = DB::select($sql);
            return $customer;
        } catch (Exception $exception){
            return $exception->getMessage();
        }
    }

    public function assignNewEmail(){
        try{
            $sql = "SELECT UID FROM users ORDER BY UID DESC LIMIT 1";
            $user = DB::select($sql);
            $uid = $user[0]->UID;
            $newUID = intval($uid) + 1;
            $newEmail = 'noemail'.$newUID.'@lpw.lk';
            return $newEmail;
        } catch (Exception $exception){
            return $exception->getMessage();
        }
    }

    public function registerUserData($data){

	    $customerObj = new Customer();
        try{
	        $resutls = $customerObj->insertGetId($data);
	        $id = $customerObj->pluck('UID')->last();
            return $resutls;
        } catch (Exception $exception){
            return $exception->getMessage();
        }

    }
    public function makeActivityRecorded($UID){

	    $activityData = array();
        $today_time = date('Y-m-d H:i:s');

        $activityData['uid'] = $UID;
        $activityData['action'] = 'New Customer';
        $activityData['comments'] = 'Added New Customer Via CRM';
        $activityData['date_time'] = $today_time;
        $activityData['by'] = Auth::user()->username;
        $activityData['created_at'] = $today_time;
        $activityData['updated_at'] = $today_time;

	    $memberActionObj = new MemberAction();
	    $memberActionObj->insert($activityData);
    }

    public function registerAdminMember($UID,$username,$source,$link,$mobile_nos,$user_type_id){

        $today = date('Y-m-d');
        $today_time = date('Y-m-d H:i:s');
        $payment_exp_date = date('Y-m-d', strtotime("+30 day", strtotime($today_time)));

        $adminUserArray = array();
        $adminUserArray['uid'] = $UID;
        $adminUserArray['type'] = 'Inactive';
        $adminUserArray['category'] = 'Single Ad';
        $adminUserArray['package_amount'] = 0.00;
        $adminUserArray['duration'] = '1 month';
        $adminUserArray['payment'] = 'Pending';
        $adminUserArray['duration'] = '1 month';
        $adminUserArray['payment_exp_date'] = $payment_exp_date;
        $adminUserArray['am'] = $username;
        $adminUserArray['call_date_time'] = $payment_exp_date;
        $adminUserArray['member_since'] = $today;
        $adminUserArray['expiry'] = $payment_exp_date;
        $adminUserArray['user_type_id'] = $user_type_id;
        $adminUserArray['source'] = $source;
        $adminUserArray['mobile_nos'] = $mobile_nos;
        $adminUserArray['link'] = $link;
        $adminUserArray['is_phone_restriction'] = 1;
        $adminUserArray['created_at'] = $today_time;
        $adminUserArray['updated_at'] = $today_time;

        $adminMObj = new Member();
        $adminMObj->insert($adminUserArray);
    }

    /**
     * Return the view of ads list by customer.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function listAdsByCustomerView(Request $request) {
        $uid = intval($request->input('uid'));
        if($uid > 0) {
            $user_data = $this->customer->getUserEmailByUID($uid);
            $data["user_email"] = isset($user_data->Uemail)?$user_data->Uemail : "";
            if(isset($user_data->num_of_max_ads)) {
                if ($user_data->num_of_max_ads != 'Unlimited') {
                    $data["max_ad_count"] = intval($user_data->num_of_max_ads);
                } else {
                    $data["max_ad_count"] = -1;
                }
            } else {
                $data["max_ad_count"] = 0;
            }

        } else {
            $data["user_email"] = "";
            $data["max_ad_count"] = 0;
        }
		return view('customer.list_ads_by_customer',$data);
	}

	/**
	 * Auto-complete user/customer email
	 *
	 * @return Array
	 */
	public function AutocompleteCustomerEmail(Request $request) {
		//dd($request->input('term'));
		return $this->customer->AutocompleteCustomerEmail($request->input('term'));
	}

	/**
	 * List Ads by users/customer email.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listAdsByCustomer(Request $request) {
        $data = $request->input();
		return $this->customer->listAdsByCustomer($request->input('email'),$data['search']['value']);
	}


	/**
	 * List of customers.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listOfCustomer()
    {
        return view('customer.customer_list');
	}

    /**
     * Get Customer list data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function listOfCustomerData(Request $request)
    {
        $data = $request->input();
        $sql = "";
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            if(intval($data['search']['value']) > 0) {
                $sql = "SELECT `adverts`.`ad_id`, `users`.`UID`, CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username', 
                        `users`.`Uemail`, DATE_FORMAT(`users`.`reg_date`,'%Y-%m-%d') AS 'reg_date', `users`.`ads_count`, 
                        `admin_members`.`am`, `admin_members`.`mobile_nos`, `admin_members`.`type`
                    FROM `users` 
                    INNER JOIN `admin_members` ON `admin_members`.`uid` = `users`.`UID` 
                    INNER JOIN `adverts` ON `adverts`.`UID` = `users`.`UID`
                    WHERE `adverts`.`ad_id` = ".intval($data['search']['value']);
            } else {
                $sql =  "SELECT '-' AS 'ad_id', `users`.`UID`, CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username', 
                        `users`.`Uemail`, DATE_FORMAT(`users`.`reg_date`,'%Y-%m-%d') AS 'reg_date', `users`.`ads_count`,
                         `admin_members`.`am`, `admin_members`.`mobile_nos`, `admin_members`.`type`
                    FROM `users` 
                    INNER JOIN `admin_members` ON `admin_members`.`uid` = `users`.`UID` 
                    WHERE (`users`.`firstname` LIKE '%".$search_data."%' OR `users`.`surname` LIKE '%".$search_data."%'  OR `users`.`Uemail` LIKE '%".$search_data."%' OR `admin_members`.`mobile_nos` LIKE '%".$search_data."%')
                    LIMIT 25";
            }

        } else {
            $sql =  "
                    SELECT '-' AS 'ad_id', `users`.`UID`, CONCAT(`users`.`firstname`, ' ', `users`.`surname`) AS 'username', 
                        `users`.`Uemail`, DATE_FORMAT(`users`.`reg_date`,'%Y-%m-%d') AS 'reg_date', `users`.`ads_count`, 
                        `admin_members`.`am`, `admin_members`.`mobile_nos`, `admin_members`.`type`
                    FROM `users` 
                    INNER JOIN `admin_members` ON `admin_members`.`uid` = `users`.`UID` 
                    LIMIT 0
                    ";
        }
        $customer = DB::select($sql);
        //dd($customer);
        return Datatables::of($customer)->make(true);
    }

    /**
     * List of archive customers.
     *@return \Illuminate\Http\JsonResponse
     */
    public function listOfArchiveCustomer()
    {
        return view('customer.customer_archive_list');
    }

    /**
     * Get Customer list data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listOfArchiveCustomerData(Request $request)
    {
        $data = $request->input();
        $sql = "";
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT `ad_id`, `UID`, `email`, `tel`, DATE_FORMAT(`archived_date`,'%Y-%m-%d') AS 'archived_date', `heading`
                    FROM `adverts_archive`
                    WHERE ((`email` LIKE '%".$search_data."%') OR (`tel` LIKE '%".$search_data."%'))
                    ORDER BY `archived_date` DESC 
                    LIMIT 200";
        } else {
            $sql =  "SELECT `ad_id`, `UID`, `email`, `tel`, DATE_FORMAT(`archived_date`,'%Y-%m-%d') AS 'archived_date', `heading`
                    FROM `adverts_archive`
                    ORDER BY `archived_date` DESC 
                    LIMIT 0";
        }
        $customer = DB::connection('mysql2')->select($sql);
        return Datatables::of($customer)->make(true);
    }

    /**
     * View PAA agents list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paaAgents()
    {
        return view('customer.paa_agents_list');
    }

    /**
     * Get PAA agents search data
     * @param Request $request
     * @return mixed
     */
    public function paaAgentsSearchData(Request $request)
    {
        $start_date = $request->input("start_date");
        $end_date = $request->input("end_date");
        $revenue_price_query = "SELECT ap.`monthly_payment`
                                FROM `admin_member_packages` AS ap
                                WHERE `package_name` = 'Self Fast'
                                LIMIT 1";
        $revenue_data = DB::select($revenue_price_query);
        $revenue_price = isset($revenue_data[0]->monthly_payment)?$revenue_data[0]->monthly_payment:config('datatables.revenue_price');
        $sql = "";

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT u.`Uemail` AS 'email', u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count' , (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $start_date . "' AND a.`posted_date` <= '" . $end_date . "'
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (u.`user_type` = 'P' OR am.`user_type_id` = 2) AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%' OR am.`am` LIKE '%".$search_data."%') 
                        AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        } else {
            $sql = "SELECT u.`Uemail` AS 'email', u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count', (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $start_date . "' AND a.`posted_date` <= '" . $end_date . "'
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (u.`user_type` = 'P' OR am.`user_type_id` = 2) AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        }
        $agents = DB::select($sql);
        return Datatables::of($agents)->make(true);
    }

    /**
     * Get PAA agents data
     * @param Request $request
     * @return mixed
     */
    public function paaAgentsData(Request $request)
    {
        $data = $request->input();
        $revenue_price_query = "SELECT ap.`monthly_payment`
                                FROM `admin_member_packages` AS ap
                                WHERE `package_name` = 'Self Fast'
                                LIMIT 1";
        $revenue_data = DB::select($revenue_price_query);
        $revenue_price = isset($revenue_data[0]->monthly_payment)?$revenue_data[0]->monthly_payment:config('datatables.revenue_price');
        $sql = "";

        $today = date("Y-m-d");
        $seven_days_before = date('Y-m-d', strtotime($today . ' - 7 days'));
        $thirty_days_before = date('Y-m-d', strtotime($today . ' - 30 days'));
        $sixty_days_before = date('Y-m-d', strtotime($today . ' - 60 days'));

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            /*$sql = "SELECT u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count' , (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN (
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (u.`user_type` = 'P' OR am.`user_type_id` = 2) AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%' OR am.`am` LIKE '%".$search_data."%')
                        AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";*/
            $sql = "SELECT u.`Uemail` AS 'email', u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count' , (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $thirty_days_before . "'
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (u.`user_type` = 'P' OR am.`user_type_id` = 2) AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%' OR am.`am` LIKE '%".$search_data."%') 
                        AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        } else {
            /*$sql = "SELECT u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count', (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN (
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (u.`user_type` = 'P' OR am.`user_type_id` = 2) AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";*/
            $sql = "SELECT u.`Uemail` AS 'email', u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count', (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $thirty_days_before . "'
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (u.`user_type` = 'P' OR am.`user_type_id` = 2) AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        }
        $agents = DB::select($sql);
        foreach ($agents As $data => $value) {
            $uid = intval($value->uid);
            //Calculate today new ad count
            $sql_l1 = "SELECT COUNT(a.`ad_id`) 'ad_count'
                        FROM `adverts` AS a
                        WHERE a.`uid` = '" . $uid . "' AND a.`posted_date` >= '" . $sixty_days_before . "' AND 
                        a.`posted_date` < '" . $thirty_days_before . "' AND a.`is_active` = 1
                        GROUP BY a.`uid`";
            $last_month_ad_count = DB::select($sql_l1);
            $agents[$data]->last_month_ad_count = isset($today_ad_count[0])?$last_month_ad_count[0]->ad_count : 0;
            $agents[$data]->last_month_revenue = $agents[$data]->last_month_ad_count * $revenue_price;
            //Calculate last seven day new ad count
            $sql_l2 = "SELECT COUNT(a.`ad_id`) 'ad_count'
                        FROM `adverts` AS a
                        WHERE a.`uid` = '" . $uid . "' AND a.`posted_date` >= '" . $seven_days_before . "' AND a.`is_active` = 1
                        GROUP BY a.`uid`";
            $seven_day_ad_count = DB::select($sql_l2);
            $agents[$data]->seven_day_ad_count = isset($seven_day_ad_count[0])?$seven_day_ad_count[0]->ad_count : 0;
            $agents[$data]->seven_day_revenue = $agents[$data]->seven_day_ad_count * $revenue_price;
        }

        return Datatables::of($agents)->make(true);
    }

    /**
     * View PAA agent
     * @param $agentId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paaAgentView($agentId)
    {
        $arrAgent['id'] = $agentId;
        $sql = "SELECT CONCAT(u.`firstname`, ' ', u.`surname`) AS 'agent_name'
                FROM `users` AS u
                WHERE u.`UID` = ".$agentId ."
                LIMIT 1 ";
        $agent_data = DB::select($sql);
        $arrAgent['agent_name'] = isset($agent_data[0]->agent_name)?$agent_data[0]->agent_name:'';
        return view('customer.paa_agent_view', $arrAgent);
    }

    /**
     * Get PAA agent data
     * @param Request $request
     * @return mixed
     */
    public function paaAgentViewData(Request $request)
    {
        $data = $request->input();
        $agent_id = intval($request->input('agent_id'));
        date_default_timezone_set('Asia/Colombo');
        $today = date("Y-m-d");

        $sql = "";
        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT a.`ad_id`, a.`type`, TIMESTAMPDIFF(DAY, a.`posted_date`, '".$today."' ) AS 'days_count', 
                    a.`is_active`, IFNULL(aed.`ads_edit_count`, 0) AS 'ads_edit_count', 
                    IF(a.`last_edited` != '0000-00-00 00:00:00' , a.`last_edited`, '-') AS 'last_edited' 
                    FROM `adverts` AS a 
                    LEFT JOIN (
                        SELECT aed.`ad_id` , COUNT(aed.`ads_edit_data_id`) AS 'ads_edit_count'
                        FROM `ads_edit_data` AS aed 
                        WHERE aed.`is_enable` = 1  AND aed.`user_id` = ".$agent_id . "
                        GROUP BY aed.`ad_id`
                    ) AS aed ON aed.`ad_id` = a.`ad_id`
                    WHERE /*a.`is_active` = 1 AND*/ a.`UID` = " .$agent_id. " AND (a.`type` LIKE '%" .$search_data. "%')
                    ORDER BY TIMESTAMPDIFF(DAY, a.`posted_date`, '".$today."' ) ASC";
        } else{
            $sql = "SELECT a.`ad_id`, a.`type`, TIMESTAMPDIFF(DAY, a.`posted_date`, '".$today."') AS 'days_count', 
                    a.`is_active`, IFNULL(aed.`ads_edit_count`, 0) AS 'ads_edit_count', 
                    IF(a.`last_edited` != '0000-00-00 00:00:00' , a.`last_edited`, '-') AS 'last_edited'
                    FROM `adverts` AS a 
                    LEFT JOIN (
                        SELECT aed.`ad_id` , COUNT(aed.`ads_edit_data_id`) AS 'ads_edit_count'
                        FROM `ads_edit_data` AS aed 
                        WHERE aed.`is_enable` = 1 AND aed.`user_id` = ".$agent_id . "
                        GROUP BY aed.`ad_id`
                    ) AS aed ON aed.`ad_id` = a.`ad_id`
                    WHERE /*a.`is_active` = 1 AND*/ a.`UID` = ".$agent_id . "
                    ORDER BY TIMESTAMPDIFF(DAY, a.`posted_date`, '".$today."' ) ASC";
        }
        //dd($sql);
        $agents = DB::select($sql);
        $this_month = date('Y-m-01');
        $sql_achieve = "SELECT a.`ad_id`, a.`type`, IFNULL(TIMESTAMPDIFF(DAY, a.`posted_date`, '2020-09-09' ),'-') AS 'days_count', 
                        3 AS 'is_active', '-' AS 'ads_edit_count'
                        FROM `adverts_archive` AS a 
                        WHERE a.`archived_date` >= '".$this_month."' AND a.`UID` = ".$agent_id;
        $archieve_agents = DB::connection('mysql2')->select($sql_achieve);

        $output_data = array_merge($agents, $archieve_agents);

        return Datatables::of($output_data)->make(true);
    }

    public function adActivation(Request $request)
    {
        try {
            $ad_id = intval($request->input('ad_id'));
            $ad_action = intval($request->input('ad_action'));
            date_default_timezone_set('Asia/Colombo');
            $today = date("Y-m-d");

            DB::table('adverts')
                ->where('ad_id', $ad_id)
                ->update(['is_active' => $ad_action, 'last_edited' => $today]);

            $data['status'] = "Succeed";
            $data['description'] = "Ad update successfully";

        } catch (\Exception $e) {
            $data['status'] = "Failed";
            $data['description'] = $e;
        }
        return json_encode($data);
    }

    /*
     * Get customer activities
     * @param Request $request
     * @return mixed
     */
    public function getCustomerActivityData(Request $request)
    {
        $data = $request->input();
        $user_id = intval($data['uid']);
        $output = array();
        if($user_id > 0 ) {
            $sql = "SELECT *
                FROM (
                    SELECT au.`name` AS 'changer_name', ' ' AS 'payment_status',  'Profile Manage' AS 'action', ml.`changing_description` AS 'description', ml.`created_at` AS 'date_time'
                    FROM `membership_profile_log` AS ml 
                    LEFT JOIN `admin_users` AS au ON au.`id` =  ml.`changer_user_id`
                    WHERE ml.`member_user_id` = " . $user_id . " AND ml.`changing_description` != ''
                
                    UNION ALL
                
                    SELECT am.`by` AS 'changer_name' ,am.payment_status AS payment_status, am.`action` AS 'action', CONCAT('By ', am.`by`, ' : ', am.`comments`) AS 'description', am.`created_at` AS 'date_time'
                    FROM `admin_members_actions` AS am
                    LEFT JOIN `admin_users` AS au ON au.`username` =  am.`by`
                    WHERE am.`uid` = " . $user_id . " AND am.`comments` != '' 
                    
                    UNION ALL
                    /* By Sasi Spenzer 2021-09-17 B-day */
                    SELECT am.`am` AS 'changer_name', ' ' AS 'payment_status','Payment Completed' AS 'action', CONCAT('Online payment made by user for : Rs.', pp.`paid_amount`, ' ',`payment_details`,' IPN ID: ',pp.`IPN_ID`,'.')  AS 'description', pp.`payment_received` AS 'date_time'
                    FROM `pp_payments` AS pp 
                    INNER JOIN `admin_members` AS am ON am.`uid` =  pp.`user_id`
                    WHERE pp.`user_id` = " . $user_id . " AND pp.payment_details != 'For paid pending payments' AND 
                    pp.payment_details != 'Featured Ad' AND pp.gw_used != 'CRM'
                    
                    UNION ALL
                    /* By Sasi Spenzer 2021-09-17 B-day */
                    SELECT am.`am` AS 'changer_name', ' ' AS 'payment_status', 'Payment completed via payment link' AS 'action', CONCAT('Online payment made by user for : Rs.', pp.`paid_amount`, '  ',`payment_details`,' IPN ID: ',pp.`IPN_ID`,'.')  AS 'description', pp.`payment_received` AS 'date_time'
                    FROM `pp_payments` AS pp 
                    INNER JOIN `admin_members` AS am ON am.`uid` =  pp.`user_id`
                    WHERE pp.`user_id` = " . $user_id . " AND pp.payment_details = 'For paid pending payments'
                    
                
                    UNION ALL
                    
                    SELECT au.`name` AS 'changer_name', ' ' AS 'payment_status', 'Details Manage' AS 'action', md.`changed_description` AS 'description', md.`created_at` AS 'date_time'
                    FROM `membership_details_log` AS md
                    LEFT JOIN `admin_users` AS au ON au.`id` =  md.`changer_user_id`
                    WHERE md.`member_user_id` = " . $user_id . " AND md.`changing_description` != ''
                    
                    /*UNION ALL
                    
                    SELECT cau.`last_upgraded_by` AS 'changer_name', 'Boost Add' AS 'action', CONCAT(cau.`upgrade_amount`, ' boost added for part of membership ', cau.`last_upgraded_by`) AS 'description', cau.`last_upgrade` AS 'date_time'
                    FROM `credit_account` AS ca
                    INNER JOIN `credit_account_upgrade` AS cau ON cau.`acc_id` = ca.`acc_id`
                    WHERE ca.`UID` = " . $user_id . " AND cau.`credit_type` = 0
                    
                    UNION ALL 
                    
                    SELECT cau.`last_upgraded_by` AS 'changer_name', 'Boost Add' AS 'action', CONCAT(cau.`upgrade_amount`, ' additional boost added for ', cau.`last_upgraded_by`) AS 'description', cau.`last_upgrade` AS 'date_time'
                    FROM `credit_account` AS ca
                    INNER JOIN `credit_account_upgrade` AS cau ON cau.`acc_id` = ca.`acc_id`
                    WHERE ca.`UID` = " . $user_id . " AND cau.`credit_type` = 1*/
                     
                    UNION ALL
                    
                    SELECT au.`name` AS 'changer_name', ' ' AS 'payment_status', 'Details Approval' AS 'action', md.`approve_description` AS 'description', md.`approve_at` AS 'date_time'
                    FROM `membership_details_log` AS md 
                    LEFT JOIN `admin_users` AS au ON au.`id` =  md.`changer_user_id`
                    WHERE md.`member_user_id` = " . $user_id . " AND md.`approve_description` != ''  
                        AND md.`changing_description` NOT LIKE '%User added new free single ad%' 
                    
                    UNION ALL 
                    
                    SELECT au.`name` AS 'changer_name', ' ' AS 'payment_status', 'Ads_type Change' AS 'action', atl.`changing_description` AS 'description', atl.`created_at` AS 'date_time'
                    FROM `ads_type_log` AS atl 
                    LEFT JOIN `admin_users` AS au ON au.`id` = atl.`changer_user_id`
                    WHERE atl.`ads_owner_user_id` = " . $user_id . " AND atl.`changing_description` != '' 
                    
                ) AS p
                ORDER BY p.`date_time` DESC";

            $activity_data = DB::select($sql);
            $output['status'] = "Succeed";
            $output['data'] = $activity_data;
            $output['latest_data'] = isset($activity_data[0])?$activity_data[0]->action:'-';

        } else {
            $output['status'] = "Failed";
            $output['data'] = array();
        }
        return \Response::json($output);
    }

    /**
     * View m-PAA agents list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mpaaAgents()
    {
        return view('customer.mpaa_agents_list');
    }

    /**
     * Get m-PAA agents data
     * @param Request $request
     * @return mixed
     */
    public function mpaaAgentsData(Request $request)
    {
        date_default_timezone_set('Asia/Colombo');
        $data = $request->input();
        $revenue_price_query = "SELECT ap.`monthly_payment`
                                FROM `admin_member_packages` AS ap
                                WHERE `package_name` = 'Self Fast'
                                LIMIT 1";
        $revenue_data = DB::select($revenue_price_query);
        $revenue_price = isset($revenue_data[0]->monthly_payment)?$revenue_data[0]->monthly_payment:config('datatables.revenue_price');
        $sql = "";

        $today = date("Y-m-d");
        $seven_days_before = date('Y-m-d', strtotime($today . ' - 7 days'));
        $thirty_days_before = date('Y-m-d', strtotime($today . ' - 30 days'));
        $sixty_days_before = date('Y-m-d', strtotime($today . ' - 60 days'));

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count' , (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $thirty_days_before . "'
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (/*u.`user_type` = 'P' OR */am.`user_type_id` = 8) AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%' OR am.`am` LIKE '%".$search_data."%') 
                        AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        } else {
            $sql = "SELECT u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count', (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $thirty_days_before . "'
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (/*u.`user_type` = 'P' OR*/ am.`user_type_id` = 8) AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        }
        $agents = DB::select($sql);
        foreach ($agents As $data => $value) {
            $uid = intval($value->uid);
            //Calculate today new ad count
            $sql_l1 = "SELECT COUNT(a.`ad_id`) 'ad_count'
                        FROM `adverts` AS a
                        WHERE a.`uid` = '" . $uid . "' AND a.`posted_date` >= '" . $sixty_days_before . "' AND 
                        a.`posted_date` < '" . $thirty_days_before . "' AND a.`is_active` = 1
                        GROUP BY a.`uid`";
            $last_month_ad_count = DB::select($sql_l1);
            $agents[$data]->last_month_ad_count = isset($last_month_ad_count[0])?$last_month_ad_count[0]->ad_count : 0;
            $agents[$data]->last_month_revenue = $agents[$data]->last_month_ad_count * $revenue_price;
            //Calculate last seven day new ad count
            $sql_l2 = "SELECT COUNT(a.`ad_id`) 'ad_count'
                        FROM `adverts` AS a
                        WHERE a.`uid` = '" . $uid . "' AND a.`posted_date` >= '" . $seven_days_before . "' AND a.`is_active` = 1
                        GROUP BY a.`uid`";
            $seven_day_ad_count = DB::select($sql_l2);
            $agents[$data]->seven_day_ad_count = isset($seven_day_ad_count[0])?$seven_day_ad_count[0]->ad_count : 0;
            $agents[$data]->seven_day_revenue = $agents[$data]->seven_day_ad_count * $revenue_price;
        }
        //dd($agents);
        return Datatables::of($agents)->make(true);
    }

    /**
     * Get m-PAA agents data
     * @param Request $request
     * @return mixed
     */
    public function mpaaAgentsSearchData(Request $request)
    {
        date_default_timezone_set('Asia/Colombo');
        $data = $request->input();
        $start_date = $request->input("start_date");
        $end_date = $request->input("end_date");
        $revenue_price_query = "SELECT ap.`monthly_payment`
                                FROM `admin_member_packages` AS ap
                                WHERE `package_name` = 'Self Fast'
                                LIMIT 1";
        $revenue_data = DB::select($revenue_price_query);
        $revenue_price = isset($revenue_data[0]->monthly_payment)?$revenue_data[0]->monthly_payment:config('datatables.revenue_price');
        $sql = "";

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count' , (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $start_date . "'  AND a.`posted_date` <= '" . $end_date . "' 
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (/*u.`user_type` = 'P' OR */am.`user_type_id` = 8) AND (u.`firstname` LIKE '%".$search_data."%' OR u.`surname` LIKE '%".$search_data."%' OR am.`am` LIKE '%".$search_data."%') 
                        AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        } else {
            $sql = "SELECT u.`UID` AS 'uid', CONCAT(u.`firstname`, ' ', u.`surname`) AS 'username', IFNULL(a.`ads_count`,0) AS 'ads_count', (IFNULL(a.`ads_count`,0) * ".$revenue_price.") AS 'revenue', am.`am`
                    FROM `users` AS u 
                    INNER JOIN `admin_members` AS am ON am.`uid` = u.`UID`
                    LEFT JOIN ( 
                        SELECT a.`UID`, COUNT(a.`ad_id`) AS 'ads_count'
                        FROM `adverts` AS a
                        WHERE a.`is_active` = 1 AND a.`posted_date` >= '" . $start_date . "'  AND a.`posted_date` <= '" . $end_date . "' 
                        GROUP BY a.`UID` ) AS a ON a.`UID` = u.`UID`
                    WHERE (/*u.`user_type` = 'P' OR*/ am.`user_type_id` = 8) AND (am.`category` = 'Business Pro' OR am.`category` = 'Business Plus' OR am.`category` = 'Business Ultimate')
                    ORDER BY u.`ads_count` DESC, u.`firstname` DESC, u.`surname` DESC";
        }
        $agents = DB::select($sql);
        //dd($agents);
        return Datatables::of($agents)->make(true);
    }

    /**
     * View PAA agent
     * @param $agentId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mpaaAgentView($agentId)
    {
        $arrAgent['id'] = $agentId;
        $sql = "SELECT CONCAT(u.`firstname`, ' ', u.`surname`) AS 'agent_name'
                FROM `users` AS u
                WHERE u.`UID` = ".$agentId ."
                LIMIT 1 ";
        $agent_data = DB::select($sql);
        $arrAgent['agent_name'] = isset($agent_data[0]->agent_name)?$agent_data[0]->agent_name:'';
        return view('customer.mpaa_agent_view', $arrAgent);
    }


    public function userAgentAdCount(Request $request) {
        $user_email = $request->input('email');
        if($user_email != '' && $user_email != null) {
            $user_data = $this->customer->getUserAdCountByEmail($user_email);
            $data["user_email"] = isset($user_data->Uemail)?$user_data->Uemail:'';
            if(isset($user_data->num_of_max_ads)) {
                if ($user_data->num_of_max_ads != 'Unlimited') {
                    $data["max_ad_count"] = intval($user_data->num_of_max_ads);
                } else {
                    $data["max_ad_count"] = -1;
                }
            } else {
                $data["max_ad_count"] = 0;
            }
        } else {
            $data["max_ad_count"]  = 0;
        }
        //dd($max_ad_count);
        return $data;
    }

}
