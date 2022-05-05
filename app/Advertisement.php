<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model {

	protected $table = 'adverts';

	public $timestamps = false;

	public function adcontacts(){
		return $this->hasMany('App\AdvertContacts', 'ad_id', 'ad_id');
	}

}
