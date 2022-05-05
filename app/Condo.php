<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Condo extends Model
{
    protected $table = 'newhomes';

    protected $primaryKey = 'ID';

    public function city()
    {
        return $this->hasOne('App\City', 'city_id', 'city_no');
    }

}
