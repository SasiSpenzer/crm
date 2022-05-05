<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'pp_payments';
    protected $guarded = [];
    public $timestamps = false;
}
