<?php

namespace App\Http\Controllers;

use App\Contracts\MemberActionInterface;
use Illuminate\Http\Request;
use App\MemberAction;
use Mail;

class CronjobController extends Controller
{
    protected $member;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MemberActionInterface $member) {
        $this->member = $member;
    }

    public function index($script)
    {
    	if($script == 'reminder')
    		return $this->send_email_for_reminder();
    }

    //this crone should run after 12am
    public function send_email_for_reminder()
    {
    	$date = date('Y-m-d');

    	$reminders = MemberAction::with('admin')->where('reminder', $date)->get();

    	foreach ($reminders as $key) {
    		$email = $key->admin->email;
            $name = $key->admin->name;

    		$customer = $key->customer; 
    		$data['name'] = $customer->firstname;
    		$data['email'] = $customer->Uemail;
    		$data['comment'] = $key->comments;

    		\Mail::send('emails.reminder', $data, function ($message) use ($email, $date, $name) {
    	
	    	    $message->to($email, $name);
	    	    $message->subject('Reminder: ' . $date);
	    	    
	    	});

    	}

        $msg = $reminders->count() . " reminder email(s) have been sent!";
        Mail::raw($msg, function ($message) use ($date) {
          $message->to('namaljayathunga@gmail.com', 'Namal')
                    ->subject('Reminder: ' . $date);
        });

        return $msg; //'Success!';

    }

    /**
     * For membership expire
     * @return mixed
     */
    public function membershipExpire()
    {
        $outData = $this->member->membershipExpire();
        return $outData;
    }
}
