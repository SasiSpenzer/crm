<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Member;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function myTarget($data = false)
	{
		if ($data == false) {
			return view('dashboard.target_my');
		}else{
			//
		}
	}

	public function groupTarget()
	{
        date_default_timezone_set('Asia/Colombo');
	    $admin_level = intval(Auth::user()->admin_level);
	    if($admin_level > 2) {
            $yesterday = Carbon::yesterday();

            //calculation part can be found in inside the view
            /*$revenue = Member::select(['package_amount', 'am'])
                    ->where('type', 'Member')
                    ->where('expiry', '>', $yesterday)
                    ->where('payment_exp_date', '>', $yesterday)
                    ->get();*/
            /*$revenue = DB::table('google_payment_data')
                            ->select('google_payment_data.invoiced_amount AS package_amount','google_payment_data.am AS am')
                            ->get();*/

            //caluculation part can be found in inside the view
//            $addon_revenue = Member::leftjoin('admin_members_actions',
//                'admin_members_actions.uid', '=', 'admin_members.uid')
//                ->select([
//                    'admin_members.am as am',
//                    'admin_members_actions.qty as qty',
//                    'admin_members_actions.value as value'
//                ])
//                ->where('admin_members.type', 'Member')
//                ->where('admin_members.expiry', '>', $yesterday)
//                ->where('admin_members.payment_exp_date', '>', $yesterday)
//                ->get();

            //$targets = User::whereIn('admin_level', [1, 2])->get();
            //return view('dashboard.target_group', compact('targets', 'revenue', 'addon_revenue'));
            $month = date('Y-m');
            // BY Sasi Spenzer 2021-05-18 ** WFH
            $sql = "SELECT au.`id`, au.`name`, au.`target`, au.`mem_target`, gpd.`package_amount`
                    FROM `admin_users` AS au 
                    LEFT JOIN (
                         SELECT gpd.`am`,au.`am_status`,
                             IF(au.`am_status`= 1,SUM(gpd.`invoiced_amount`),SUM(gpd.`paid_amount`)) AS 'package_amount'
                      FROM `google_payment_data` AS gpd
                      LEFT JOIN  `admin_users`  AS au ON gpd.`am` = au.`username`
                      WHERE gpd.`due_date` LIKE '".$month."%'
                        GROUP BY gpd.`am`
                    ) AS gpd ON gpd.`am` = au.`username`
                    WHERE au.`admin_level` < 3";
            $output = DB::select($sql);
            //dd($output);
            return view('dashboard.target_group', compact('output'));
        } else {
	        $flash_message = "You haven't permission to access this page";
            return view('dashboard.index', compact('flash_message'));
        }
	}

	public function saveTarget(Request $request)
	{   $admin_level = intval(Auth::user()->admin_level);
        if($admin_level > 2) {
            User::where('id', $request->user_id)->update(['target' => $request->target]);
            return 'success';
        } else {
            return 'false';
        }
	}

	public function saveMemTarget(Request $request)
	{
        $admin_level = intval(Auth::user()->admin_level);
        if($admin_level > 2) {
            User::where('id', $request->user_id)->update(['mem_target' => $request->target]);
            return 'success';
        } else {
            return 'false';
        }
	}
}
