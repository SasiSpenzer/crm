<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class auto_boost_summary extends Model
{
    protected $guarded = [];
    protected $table = 'auto_boost_summary';
    
    public $timestamps = false;

    protected $primaryKey = "auto_boost_summary_id";
}
