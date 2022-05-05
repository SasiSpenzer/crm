<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Member extends Model {
	use Notifiable;

	protected $table = 'admin_members';
	protected $dates = ['member_since', 'expiry'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		        'package_amount', 'payment_exp_date', 'welcome_email'
		    ];
	

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	/* protected $hidden = [
		        'password', 'activation_key',
	*/
}
