<?php

namespace App\Repositories;

use App\Contracts\MemberActionInterface;
use App\MemberAction;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EloquentMemberActionRepository implements MemberActionInterface {

	/**
	 * Create a new repository instance.
	 *
	 * @return void
	 */
	public function __construct() {}

	function save(Array $payload) {
		$memberAction = new MemberAction;

		$currentDateTime = Carbon::now();
		$memberAction->uid = $payload['memship_uid'];
		$memberAction->action = $payload['log_type'];
		$memberAction->comments = $payload['log_comments'];
		$memberAction->by = Auth::user()->username;
		$memberAction->date_time = $currentDateTime;
		$memberAction->created_at = $currentDateTime;
		$memberAction->updated_at = $currentDateTime;
		$memberAction->reminder = $payload['log_reminder'];

		$memberActionResult = $memberAction->save();

		if ($memberActionResult) {
			return $memberAction;
		} else {
			return null;
		}

	}

	function membershipSave(Array $payload) {
        //$memberAction = new MemberAction;
        $memberAction = [];
        //default time zone set sri lanka colombo
        date_default_timezone_set('Asia/Colombo');
        $currentDateTime = Carbon::now();
        $currentDate = Carbon::now()->format('Y-m-d');
        $uid = intval($payload['membership_uid']);
        $action = $payload['log_type'];
        $comments = $payload['log_comments'];
        $by = Auth::user()->username;
        $reminder = $payload['log_reminder'];
        $membership_status = $payload['payment_status'];
        $output = array();

        if($uid > 0) {
            if($comments != null && $comments != '' &&
                $action != null && $action != '') {
                DB::table('admin_members')
                    ->where('uid', intval($uid))
                    ->update([
                        'latest_comment' => $comments, 'updated_at' => $currentDateTime, 'latest_action'=> $action,
                            'payment_status' => $membership_status, 'latest_commented_at' => $currentDate
                        ]);

                switch ($membership_status) {
                    case 1:
                        $status = 'Follow-up';
                    break;

                    case 2:
                        $status = 'Will make payment';
                    break;

                    case 3:
                        $status = 'Paid';
                    break;
                    case 4:
                        $status = 'Not Interested - Advertising elsewhere';
                        break;
                    case 5:
                        $status = 'Inactive - Not in business';
                        break;
                    case 6:
                        $status = 'Inactive - Paused business';
                        break;
                    case 7:
                        $status = 'Inactive - No properties or sold';
                        break;
                    case 8:
                        $status = 'Follow-up - Pending Payments';
                        break;
                    case 9:
                        $status = 'Not Interested - Low leads';
                        break;

                }

                DB::table('admin_members_actions')->insert([
                        'uid' => $uid, 'action' => $action, 'comments' => $comments, 'reminder' => $reminder,
                        'payment_status' => $status,'date_time' => $currentDateTime, 'by' => $by, 'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime
                ]);
                $output['status'] = "Succeed";
                $output['description'] = "Member log data insert successfully";
            } else {
                $output['status'] = "Failed";
                $output['description'] = "Member data didn't fill correctly";
            }

        } else {
            $output['status'] = "Failed";
            $output['description'] = "Member data didn't pass correctly";
        }
        return $output;
    }
	function saveSales(Array $payload) {
		$memberAction = new MemberAction;

		$currentDateTime = Carbon::now();
		$memberAction->uid = $payload['memship_uid'];
		$memberAction->action = $payload['sales_type'];
		$memberAction->qty = $payload['sales_qty'];
		$memberAction->value = $payload['sales_value'];
		$memberAction->comments = $payload['sales_comments'];
		$memberAction->by = Auth::user()->name;
		$memberAction->date_time = $currentDateTime;
		$memberAction->created_at = $currentDateTime;
		$memberAction->updated_at = $currentDateTime;
		$memberActionResult = $memberAction->save();

		if ($memberActionResult) {
			return $memberAction;
		} else {
			return null;
		}
	}

    /**
     * For membership expire
     * @return mixed
     */
    public function membershipExpire()
    {
        //Get today
        $today = date('Y-m-d');
        //Get membership expired period
        $expired_period = config('membership.expirePeriod');

        // update admin member user type
        $data_admin = DB::table('admin_members')
            ->whereRaw('ABS(TIMESTAMPDIFF(DAY, admin_members.payment_exp_date, ?)) > ?', [$today, $expired_period])
            ->update(['admin_members.type' => 'Expired']);

        // update users membership
        $data_users = DB::table('users')
            ->join('admin_members', 'admin_members.uid', '=', 'users.UID')
            ->whereRaw('ABS(TIMESTAMPDIFF(DAY, admin_members.payment_exp_date, ?)) > ?', [$today, $expired_period])
            ->where('admin_members.type', '=', 'Expired')
            ->update(['users.membership' => 2]); // membership type 2 => Expired

        if(isset($data_admin) && isset($data_users)){
            $outputData = [
                'status' => 'succeed',
                'data' => 'Membership expired successfully'
            ];
        } else {
            $outputData = [
                'status' => 'failed',
                'data' => 'Membership expired fail'
            ];
        }
        return json_encode($outputData);

    }
}