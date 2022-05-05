<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;
use App\Payment;
use App\Customer;
use App\Member;
use App\PaymentLog;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function toBeExpire($time = null)
	{
		$date = date('Y-m-d');

		if($time == null){
			return view('payment.tobe_expire');
		}
		elseif($time == 'today'){

			$customer = Customer::where('payment_exp_date', $date);

		}elseif($time == 'one-week-before'){

			$from = Carbon::tomorrow();
			$to = Carbon::now()->addWeek();
			$customer = Customer::whereBetween('payment_exp_date', [$from, $to]);

		}elseif($time == 'one-month-before'){

			$from = Carbon::tomorrow();
			$to = Carbon::now()->addMonth();
			$customer = Customer::whereBetween('payment_exp_date', [$from, $to]);

		}

		$customer = $customer->join(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)->where('category', '!=','Free'
        )->select([
			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			'admin_members.updated_at AS last_updated_at',
			'users.UID AS UID',
			//'admin_members.payment_exp_date AS expiry',
			'admin_members.expiry AS expiry',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.category AS category',
			'admin_members.am AS am',

		]);

		return Datatables::of($customer)
				->make(true);
	}

	// By Sasi Spenzer 2020.01.03
    public function getRatesInEdit(Request $request){
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');

        $userpackage = trim($request->user_package);
        $payment_type = trim($request->payment_type);
        $duration = trim($request->duration);
        $columnName = "";

        // selecting column name by type
        if($payment_type == "Quarterly"){
            $columnName = "quarterly_payment";
        }else if($payment_type == "Annually"){
            $columnName = "annual_payment";
        }else if($payment_type == "Monthly"){
            $columnName = "monthly_payment";
        }else if(intval($payment_type) > 0){
            $columnName = "";
        }else {
            $columnName = "monthly_payment";
        }

        $today = date('Y-m-d');
        $duration_str = " + ".$duration . "month";
        $duration_date = date('Y-m-d', strtotime($today . $duration_str));
        $output['duration_date'] = $duration_date;
        if($columnName != '') {
            $rates = Package::where('package_name','=',$userpackage)->get();
            $output['ad_rates'] = isset($rates[0]) ? $rates[0]->$columnName : 0.00;
        } else {
            $add_rates_data = \DB::table('ad_rates')
                                ->select('ad_rates.rate')
                                ->where('ad_rates.id', '=', intval($payment_type))
                                ->get();
            $output['ad_rates'] = isset($add_rates_data[0])?$add_rates_data[0]->rate:0.00;
        }
        return $output;

    }



	public function expired($time = null)
	{
		$date = Carbon::now()->subDay();

		if($time == null){
			return view('payment.expired');
		}
		elseif ($time == 'all') {

			$customer = Customer::where('payment_exp_date', '<', $date)
									->where('payment_exp_date', '!=', '0000-00-00');

		}
		elseif($time == 'yesterday'){

			$customer = Customer::where('payment_exp_date', $date);

		}elseif($time == 'after-one-week'){

			$from = Carbon::now()->subWeek();
			$to = $date->subDay();
			$customer = Customer::whereBetween('payment_exp_date', [$from, $to]);

		}elseif($time == 'after-one-month'){

			$from = Carbon::now()->subMonth();
			$to = $date->subDay();
			$customer = Customer::whereBetween('payment_exp_date', [$from, $to]);

		}

		$customer = $customer->join(
			'admin_members',
			'users.UID',
			'=',
			'admin_members.uid'
		)->whereRaw('admin_members.category != "Free"'
		)->select([

			'users.ads_count AS ads_count',
			'users.firstname AS firstname',
			'users.surname AS surname',
			'users.Uemail AS Uemail',
			'admin_members.updated_at AS last_updated_at',
			'users.UID AS UID',
			'admin_members.payment_exp_date AS expiry',
			//'admin_members.expiry AS expiry',
			'admin_members.call_date_time AS call_date_time',
			'admin_members.category AS category',
			'admin_members.am AS am'
		]);

		return Datatables::of($customer)
				->make(true);
	}

    public function expiredHunter() {
        return view('payment.expired_hunter');
    }

    public function expiredHunterData() {
        $today = date('Y-m-d');
        $date_before_2_month = date('Y-m-d', strtotime($today. ' - 60 days'));

        if(isset($data['search']['value']) && $data['search']['value'] != null ) {
            $search_data = $data['search']['value'];
            $sql = "SELECT u.`ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', 
                        CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                        am.`uid` AS 'admin_uid',am.`updated_at` AS 'last_updated_at',am.`expiry` AS 'expiry',
                        am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                        am.`category` AS 'category',au.`name` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE am.`type` != 'Member' AND am.`payment_exp_date` < '" . $date_before_2_month . "' AND 
                        am.`category` != 'Single Ad' AND am.`category` != 'Free' AND 
                        am.payment_status != 5 AND am.payment_status != 6 AND  am.payment_status != 7 AND am.payment_status != 8 AND
                        (u.`firstname` LIKE  '%".$search_data."%' OR u.`surname` LIKE  '%".$search_data."%' OR 
                        u.`Uemail` LIKE  '%".$search_data."%' OR u.`category` LIKE  '%".$search_data."%') 
                    ORDER BY am.`payment_exp_date` DESC";
        } else {
            $sql = "SELECT u.`ads_count` AS 'ads_count',u.`firstname` AS 'firstname',u.`surname` AS 'surname', 
                        CONCAT(u.`firstname`, ' ',u.`surname`) AS 'full_name',u.`Uemail` AS 'Uemail',u.`UID` AS 'UID',
                        am.`uid` AS 'admin_uid',am.`updated_at` AS 'last_updated_at',am.`expiry` AS 'expiry',
                        am.`call_date_time` AS 'call_date_time', am.`payment_exp_date` AS 'payment_exp_date',
                        am.`category` AS 'category',au.`name` As 'am'
                    FROM `admin_members` AS am 
                    LEFT JOIN `admin_users` AS au On au.`username` = am.`am`
                    INNER JOIN `users` AS u ON u.`UID` = am.`uid`
                    WHERE am.`type` != 'Member' AND am.`payment_exp_date` < '" . $date_before_2_month . "' AND 
                    am.payment_status != 5 AND am.payment_status != 6 AND  am.payment_status != 7 AND am.payment_status != 8 AND
                        am.`category` != 'Single Ad' AND am.`category` != 'Free' 
                    ORDER BY am.`payment_exp_date` DESC";
        }
        $customer = DB::select($sql);
        return Datatables::of($customer)->make(true);
    }

	public function savePaymentExpire(Request $request)
	{
		$uid = $request->input('uid');
		$payment_exp = $request->input('payment_exp');
		$amount = $request->input('amount');

		$member = Member::select('id')->where('uid', $uid)->first();

		$member->update(['payment_exp_date' => $payment_exp]);

		PaymentLog::create(['user_id' => $uid, 'amount' => $amount, 'assign_id' => $member->id, 'assign_type' => 'Member']);

		return 'true';
	}

	public function addInvoiced() {
        $data['invoice_url'] = "http://docs.google.com/forms/d/e/1FAIpQLSdG0aiwxmeXSZW5WWVt5ggza6ObB9yBBbzxYDGXx3PehyD3LQ/viewform";
        return view('payment.invoice', $data);
    }

}
