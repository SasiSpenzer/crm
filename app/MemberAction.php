<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberAction extends Model {
	protected $table = 'admin_members_actions';

	public function admin()
	{
		return $this->belongsTo('App\User', 'by', 'username');
	}

	public function customer()
	{
		return $this->belongsTo('App\Customer', 'uid', 'UID');
	}
}
