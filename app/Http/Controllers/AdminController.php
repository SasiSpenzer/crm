<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Member;
use App\Advertisement;
use App\AdvertContacts;
use Datatables;
use App\Customer;
use App\Package;
use App\Payment; 
use Auth;
use Log;
use DB;
use Mockery\Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class AdminController extends Controller
{
	//delete phone numbers
    public function getCheckNumbers(){
    	return view('delete.check_numbers');
    }

    public function postCheckNumbers(Request $request){
    	$mob_number = $request->input('mob_number');
    	$contacts = AdvertContacts::with('customer')->where('mob_number', 'like', '%' .$mob_number. '%')->groupBy(['user_id', 'mob_number'])->get();
    	return view('delete.check_numbers', compact('contacts'));
    }

	//delete phone numbers
    public function getDeleteNumbers(){
    	return view('delete.delete_numbers');
    }

    public function postDeleteNumbers(Request $request){
    	$email = $request->input('user_email');
    	//$mobile = $request->input('user_number');
    	$customer = Customer::select(['UID', 'Uemail', 'firstname', 'surname'])->where('Uemail', $email)->first();
    	//if($mobile == '')
        $class='';
        if (!$customer) {
            $msg = "Email does not exist.";
            $class="alert-warning";
            return view('delete.delete_numbers', compact('msg', 'class'));
        }

    		$s_ad = AdvertContacts::select(['contact_id', 'ad_id', 'mob_number', 'mob_number_tag'])->where('user_id', $customer->UID)->groupBy('mob_number')->get();

    	/*else{
    		$s_ad = Advertisement::whereHas('adcontacts', function ($q) use ($mobile){
	    		$q->where('mob_number', $mobile);
	    	})->select(['heading', 'ad_id'])
	    	->where('UID', $customer->UID)->get();
    	}*/
    	return view('delete.delete_numbers', compact('s_ad', 'customer'));
    }

    public function ajaxDeleteNumbers($mid){
    	$contact = AdvertContacts::where('contact_id', $mid)->first();

        $is_pending_payment = Member::where('pending_amount', '>', 0)
                                    ->where('uid', $contact->user_id)->count();
        if($is_pending_payment) {
            return "Can't detele the number, This user has pending payments.";
        }else{
            Log::useFiles(storage_path().'/logs/delete_mobile_number.log');
            Log::info('Contact id ' . $mid . ' ('. $contact->mob_number .') was deleted by ' . auth()->user()->name . PHP_EOL);
            $contact->where('user_id','=', $contact->user_id)
            ->where('mob_number','=', $contact->mob_number)->delete();
            return 1;
        }

    	
    }

    // replace phone number
    public function getReplaceNumbers(){
    	return view('delete.replace_numbers');
    }

    public function postReplaceNumber(Request $request){
    	$email = $request->input('email');
    	$customer = Customer::select(['UID', 'Uemail', 'firstname', 'surname'])->where('Uemail', $email)->first();
        $class='';
        if (!$customer) {
            $msg = "Email does not exist.";
            $class="alert-warning";
            return view('delete.replace_numbers', compact('msg', 'class'));
        }
    	$contacts = AdvertContacts::where('user_id', $customer->UID)->groupBy('mob_number')->get();
        $contact='';
        foreach ($contacts as $contact)
        {

            $contact=$contact->ad_id;
        }
        if ($contact) {
            $msg='';
            return view('delete.replace_numbers', compact('contacts', 'customer', 'msg', 'class'));
        }else{
            $msg="This user does not have a contact number.";
            $class="alert-warning";
            return view('delete.replace_numbers', compact('msg', 'customer', 'class'));
        }
    	
    }

    public function ajaxReplaceNumber(Request $request){
    	$uid = $request->input('uid');
        $contact_id=$request->input('contact_id');
    	$old_number = $request->input('old_number');
    	$new_number = $request->input('new_number');
    	
    	$count_old = AdvertContacts::where('user_id', '!=', $uid)
    								->where('mob_number', $old_number)->count();

    	$count_new = AdvertContacts::where('user_id', '!=', $uid)
    								->where('mob_number', $new_number)->count();

                                    
    	/*if($count_old) {
    		return "Can't be replaced, the old number doesn't belong to this user.";
    	}elseif($count_new) {
    		return "Can't be replaced, the new number belongs to another user.";
    	}*/
        if(1){
    		/*AdvertContacts::where('mob_number', $old_number)
    							->update(['mob_number' => $new_number]);*/
    		Log::useFiles(storage_path().'/logs/replace_mobile_number.log');
    		Log::info($old_number . ' was replaced with '. $new_number .' by ' . auth()->user()->name . PHP_EOL);
            $last_query = AdvertContacts::where('user_id','=', $uid)
            ->where('mob_number','=', $old_number)
                    ->update(['mob_number' => $new_number]);
            /*$last_query = end($queries);*/
            /*print_r($last_query);*/
    		return 1;
    	}
    }

    // delete user account
    public function getDeleteUser(){

        $admin_level = Auth::user()->admin_level;
        if ($admin_level >= 3) {
            return view('delete.delete_users');
        } else {
            return view('dashboard.index');
        }
    }

    public function postDeleteUser(Request $request){
    	$email = $request->input('email');

    	$customer = Customer::select(['UID', 'Uemail', 'firstname', 'surname'])->where('Uemail', $email)->first();
    	return view('delete.delete_users', compact('customer'));
    }

    public function confirmDeleteUser(Request $request){
    	$uid = $request->input('uid');

    	$ads = Advertisement::where('UID', $uid)->count();
        $is_pending_payment = Member::where('pending_amount', '>', 0)
                                    ->where('uid', $uid)->count();

    	if($ads){
    		$msg = "Delete all ads of the customer before deleting the account.";
            $class="alert-warning";
    		return view('delete.delete_users', compact('msg', 'class'));
    	}
        else if($is_pending_payment) {
            $msg = "Can't detele the user account, This user has pending payments.";
            $class="alert-warning";
            return view('delete.delete_users', compact('msg', 'class'));
        }
        else{
    		$db_name = env('DB_DATABASE');
    		$db_archive = env('DB_ARCHIVE_DATABASE');
    		$customer = Customer::where('UID', $uid)->count();
    		if($customer == 0){
    			$msg = "There is no such account.";
    			return view('delete.delete_users', compact('msg'));
    		}
    		$now = date('Y-m-d h:i:s');
    		$by = auth()->user()->name;
    		$reason = $request->input('reason');
    		$sql = "INSERT INTO ".$db_archive.".users_archive SELECT *, '$now', '$by', '$reason' FROM ".$db_name.".users WHERE UID = $uid LIMIT 1";
    		DB::statement($sql);

    		// Delete From News Letter
            $customer = Customer::select(['Uemail'])->where('UID', $uid)->first();

            DB::table('email_subscriptions')->where('email', '=', $customer->Uemail)->delete();
            DB::table('user_email_queue')->where('user_email', '=',$customer->Uemail)->delete();
            DB::table('user_searched_filters')->where('uid', '=',$uid)->delete();
            DB::table('user_favourit_adverts')->where('uid', '=',$uid)->delete();
            DB::table('user_email_alerts')->where('uid', '=',$uid)->delete();


    		AdvertContacts::where('user_id', $uid)->delete();
    		Customer::where('UID', $uid)->delete();
    		Log::useFiles(storage_path().'/logs/delete_user_account.log');
    		Log::info('A user (UID: '. $uid .') was deleted by ' . $by . PHP_EOL);
    		$msg = "The account has been deleted successfully.";
            $class="alert-success";
    		return view('delete.delete_users', compact('msg', 'class'));
    	}
    }

    /*
     * For reset password view
     */
    public function resetPassword(){
        return view('reset.password_reset');
    }

    /**
     * For customer email data get
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|\Laravel\Lumen\Application
     */
    public function resetPasswordSubmit(Request $request){
        $email = $request->input('email');
        $customer = Customer::select(['UID', 'Uemail', 'firstname', 'surname'])->where('Uemail', $email)->first();
        //dd($customer);
        return view('reset.password_reset', compact('customer'));
    }

    /**
     * For customer password reset
     * @param Request $request
     * @return int|string
     */
    public function resetPasswordConfirm(Request $request){
        try{
            //get request email address
            $customer_email = $request->input('customer_email');
            $customer_data = DB::table('users')
                                ->where('Uemail', $customer_email)
                                ->select('UID', 'username', 'password', 'Uemail', 'firstname', 'surname', 'validated')
                                ->limit(1)
                                ->get();
            if(sizeof($customer_data) == 0){
                return "The Email address you entered is not valid or does not match any account. Please re-try entering the email address.";
            } else {
                //customer data
                $customer_id = intval($customer_data[0]->UID);
                $customer_username = $customer_data[0]->username;
                $customer_name = $customer_data[0]->firstname . ' ' . $customer_data[0]->surname;
                $customer_validated = $customer_data[0]->validated;
                
                if ($customer_username == '') {
                    $customer_username = $customer_email;
                }
                //generate new password
                /*$new_password = "";
                // define possible characters
                $possible = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                // set up a counter
                $i = 0;
                $length = 8;
                // add random characters to $password until $length is reached
                while ($i < $length) {
                    // pick a random character from the possible ones
                    $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
                    // we don't want this character if it's already in the password
                    if (!strstr($new_password, $char)) {
                        $new_password .= $char;
                        $i++;
                    }
                }*/
                
                $new_password = "";
                // define possible characters
                $possible = "123456789ABCDEFGHJKLMNPQRSTUVWXYZ"; 
                // set up a counter
                $i = 0;   
                $length = 8;
                // add random characters to $password until $length is reached
                while ($i < $length) { 
                  // pick a random character from the possible ones
                  $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
                  // we don't want this character if it's already in the password
                  if (!strstr($new_password, $char)) { 
                    $new_password .= $char;
                    $i++;
                  }
                }
                //check customer validation
                if ($customer_validated == 'N') {
                    $validated_text = "\n\nYour account is not active yet, therefore you cannot login. 
                                        We have sent an email with an activation link. 
                                        You will need to click on this link to activate your account before you can login. 
                                        If you haven't received this email, kindly contact us via contactus@lankapropertyweb.com";
                } else {
                    $validated_text = '';
                }

                //update customer password
                DB::table('users')
                    ->where('UID', $customer_id)
                    ->update(['password' => hash('sha256', $new_password),'is_sha256' => 1]);
                //send the mail for AM
                $AM_Email = Auth::user()->email ; // Logged User Email
                Mail::to($AM_Email)
                    ->cc('contactus@lankapropertyweb.com')
                    ->send(new SendMailable($customer_name, $customer_username, $new_password, $validated_text));
                return 1;
            }
        } catch ( Exception $e) {
            return "Something went wrong. Please, try again later!";
        }


    }
}
