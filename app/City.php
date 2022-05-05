<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $primaryKey = 'city_no';

    public function cityMaps()
    {
        return $this->hasOne('App\CityMaps', 'city_id', 'city_no');
    }
}
