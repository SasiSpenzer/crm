<?php

namespace App\Http\Controllers;

use App\Contracts\MemberActionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberActionController extends Controller {

	protected $memberAction;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MemberActionInterface $memberAction) {
		$this->middleware('auth');
		$this->memberAction = $memberAction;
	}

    /**
     * Process save request
     * @param Request $request
     * @return mixed
     */
	public function save(Request $request) {
        date_default_timezone_set('Asia/Colombo');
		$payload = [
			"memship_uid" => $request->input('memship_uid'),
			"log_comments" => $request->input('log_comments'),
			"log_type" => $request->input('log_type'),
			"log_reminder" => $request->input('log_reminder'),
		];

        if(intval($request->input('memship_uid')) > 0){
            DB::table('admin_members')
                ->where('uid', intval($request->input('memship_uid')))
                ->update(['latest_comment' => $request->input('log_comments'), 'updated_at' => date('Y-m-d H:i:s')]);
        }
		return $this->memberAction->save($payload);

	}

    /**
     * Process membership save
     */
	public function membershipSave(Request $request)
    {
        try {
            date_default_timezone_set('Asia/Colombo');
            $payload = [
                "membership_uid" => $request->input('membership_uid'),
                "log_comments" => $request->input('log_comments'),
                "log_type" => $request->input('log_type'),
                "log_reminder" => $request->input('log_reminder'),
                'payment_status' => $request->input('membership_status')
            ];
//            if (intval($request->input('membership_uid')) > 0) {
//
//            }
            $output = $this->memberAction->membershipSave($payload);
            return $output;
        } catch (\Exception $e){
            $output['status'] = "Failed";
            $output['description'] = "Log data insert failed";
            return $output;

        }
        //return $this->memberAction->save($payload);
    }

	public function saveSales(Request $request) {
		$payload = [
			"memship_uid" => $request->input('memship_uid'),
			"sales_type" => $request->input('sales_type'),
			"sales_qty" => $request->input('sales_qty'),
			"sales_value" => $request->input('sales_value'),
			"sales_comments" => $request->input('sales_comments'),

		];

		return $this->memberAction->saveSales($payload);

	}

}
