<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class TestController extends Controller
{
    public function index($test)
    {
        if ($test == 'test'){
            return Carbon::now()->subDay();
        }
    	elseif($test == 'email')
    		return $this->test_email();
    }

    public function test_email()
    {
    	$email = 'namal@lpw.lk';
    	$data = ['name' => 'Namal', 'email' => $email, 'comment' => 'Test'];
    	\Mail::send('emails.reminder', $data, function ($message) use ($email) {
    	
    	    $message->to($email, 'Namal');
    	
    	    $message->subject('Test');
    	});
    	return 'Success!';
    }
}
