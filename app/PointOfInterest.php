<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointOfInterest extends Model
{
    protected $table = 'point_of_interest';

    public function city()
    {
        return $this->hasOne('App\City', 'city_id', 'city_no');
    }

}
