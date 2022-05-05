<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class autoboost extends Model
{
    protected $guarded = [];
    protected $table = 'auto_boost';

    public $timestamps = false;

    protected $primaryKey = "auto_boost_id";
}
