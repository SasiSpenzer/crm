<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
	protected $table = 'payment_log';
	protected $fillable = ['user_id', 'amount', 'assign_id', 'assign_type'];
}
