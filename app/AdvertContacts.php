<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertContacts extends Model {

	protected $table = 'adverts_contacts';

	public $timestamps = false;

	protected $primaryKey = "contact_id";

	public function customer(){
		return $this->belongsTo('App\Customer', 'user_id', 'UID');
	}
}
