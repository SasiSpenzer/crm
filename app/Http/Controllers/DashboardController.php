<?php

namespace App\Http\Controllers;

use App\Contracts\CustomerInterface;
use App\Contracts\DashboardInterface;
use Illuminate\Http\Request;
use App\Member;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
/*
admin_levels
0: nothing
1: admin
2: sales/banner
3: account
4: super admin
*/

class DashboardController extends Controller {

	protected $customer;
	protected $dashboard;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CustomerInterface $customer, DashboardInterface $dashboard) {
		$this->middleware('auth');
		$this->customer = $customer;
		$this->dashboard = $dashboard;
	}

	/**
	 * Show the customer dashboard.
	 *
	 * @return view
	 */
	public function index() {

        // By Sasi Spenzer 2021-07-27
        $currentDate = Carbon::now()->format('Y-m-d');
        $dateBefore30Days = Carbon::now()->subDays(30)->format('Y-m-d');
        DB::enableQueryLog();
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

        $sql4 = DB::table('admin_members_actions')
            ->join('admin_members', 'admin_members.uid', '=', 'admin_members_actions.uid')
            ->select('admin_members.am',DB::raw('COUNT(admin_members_actions.`id`) AS conv_count'))
            ->where('admin_members_actions.action', '=', 'Membership Activated')
            ->where('admin_members_actions.created_at', '<=', $currentDate)
            ->where('admin_members_actions.created_at', '>=', $dateBefore30Days)
            ->groupBy('admin_members.am')
            ->get()->toArray();

        $result_conv = $sql4;

        if ($result_conv) {

            foreach ($result_conv as $row) {
                $output_data_last["conv"][$row->am] = $row->conv_count;
                //$output_data["conv"]["total"] += intval($row["conv_count"]);
            }
        }


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
            $outArray[$each_source] = array();
            $outArray[$each_source]['Today'] = array();
            $outArray[$each_source]['Last_30_days'] = array();
            //$outArray[$each_source]['Total'] = 0;
            foreach ($getAms as $eachAm){
                //array_push($outArray[$each_source]['Today'],$eachAm->username);
                $outArray[$each_source]['Today'][$eachAm->username]['count'] = 0;
                $outArray[$each_source]['Today'][$eachAm->username]['convert'] = 0;
                $outArray[$each_source][$eachAm->username]['total'] = 0;
            }
            foreach ($getAms as $eachAm){
                //array_push($outArray[$each_source]['Last_30_days'],$eachAm->username);
                $outArray[$each_source]['Last_30_days'][$eachAm->username]['count'] = 0;
                $outArray[$each_source]['Last_30_days'][$eachAm->username]['convert'] = 0;
            }
        }
        //echo "<pre>"; print_r($output_data_today); exit();
        foreach ($updatedAccountsLast30Days as $each_record){

            $source = $each_record->source;
            $updated_at = $each_record->updated_at;
            $am = $each_record->am;
             //echo $updated_at.'-'.$currentDate.'<br/>';
            if($updated_at == $currentDate){
                //dd($am);
                if(isset($outArray[$source]['Today'][$am])){

                    $outArray[$source]['Today'][$am]['count'] += 1;
                    if(isset($output_data_today["conv"][$am])){
                        $outArray[$source]['Today'][$am]['convert'] = $output_data_today["conv"][$am];

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
                    if(isset($output_data_last["conv"][$am])){

                        $outArray[$source]['Last_30_days'][$am]['convert'] = $output_data_last["conv"][$am];
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
        //echo "<pre>"; print_r($outArray); exit();
		return view('dashboard.index')->with(['resultsTable'=>$outArray,'ams'=>$getAms]);;
	}

	public function users() {
		$user = Auth::user();
		if ($user['admin_level'] >= 3) {
			return view('dashboard.users');
		}else{
			return response(view('dashboard.403'),403);
		}
		
	}

	public function members() {
		$user = Auth::user();
		if ($user['admin_level'] >= 3) {
		return view('dashboard.members');
		}else{
			return response(view('dashboard.403'),403);
		}
	}

	public function revenue($am = false) {
		
		$yesterday  = Carbon::yesterday();
			$customer = Member::where('type', 'Member')
							->where('expiry', '>', $yesterday)
	    					->where('payment_exp_date', '<>', '0000-00-00')
	    					->where('payment_exp_date', '>', $yesterday)
							->join(
								'users',
								'users.UID',
								'=',
								'admin_members.uid'
							)->select([

								'users.ads_count AS ads_count',
								'users.firstname AS firstname',
								'users.surname AS surname',
								'users.Uemail AS Uemail',
								'users.UID AS UID',
								'admin_members.updated_at AS last_updated_at',
								'admin_members.expiry AS expiry',
								'admin_members.expiry AS expiry',
								'admin_members.payment_exp_date AS payment_expire',
								'admin_members.call_date_time AS call_date_time',
								'admin_members.category AS category',
								'admin_members.am AS am',

							]);

			if ($am != false) {
				$customer = $customer->where('am', $am);
			}

			return Datatables::of($customer)
					->make(true);
	}

	public function revenueMy($am)
	{
		if (Auth::user()->admin_level > 2 /*|| Auth::user()->admin_level == 4*/) {
			$am = false;
		}else{
			$am = Auth::user()->username;
		}
		return view('dashboard.revenue_my', compact('am'));
	}

	public function pendingPayment($data = false, $am = false) {
		$user = Auth::user();
		if ($user['admin_level'] >= 3) {
		if ($data == false) {
			return view('dashboard.revenue');
		}else{

			$yesterday  = Carbon::yesterday();
			$today  = Carbon::today();
			$customer = Member::where('type', 'Member')
							->where('expiry', '>', $yesterday)
    						->where('payment_exp_date', '<>', '0000-00-00')
    						->where('payment_exp_date', '<', $today)
    						->join(
								'users',
								'users.UID',
								'=',
								'admin_members.uid'
							)->select([
								'users.ads_count AS ads_count',
								'users.firstname AS firstname',
								'users.surname AS surname',
								'users.Uemail AS Uemail',
								'users.UID AS UID',
								'admin_members.updated_at AS last_updated_at',
								'admin_members.expiry AS expiry',
								'admin_members.payment_exp_date AS payment_expire',
								'admin_members.call_date_time AS call_date_time',
								'admin_members.category AS category',
								'admin_members.am AS am',

							]);

			if ($am != false) {
				$customer = $customer->where('am', $am);
			}
			
			return Datatables::of($customer)
					->make(true);
		}
	}else{
		return response(view('dashboard.403'),403);
	}
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
	public function listCustomerMemberships() {
		return $this->customer->listCustomerMemberships();
	}

	/**
	 * Get member and user details by User id.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getByUID(Request $request) {

		return $this->customer->getByUID($request->input('uid'));
	}

	/**
	 * Get widget details.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getDetails(Request $request) {

		return $this->dashboard->getDetails($request->input('uid'));
	}

    /**
     * View user dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function userDashboard()
    {
        return view('dashboard.user_dashboard');
    }

    /**
     * Get widget user details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails() {
        $user= Auth::user();
        $userId = $user->id;
        $username= $user->username;
        return $this->dashboard->getUserDetails($userId, $username);
    }

}
