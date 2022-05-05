<?php

namespace App\Http\Controllers;

use App\City;
use App\CityMaps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CityController extends Controller
{
    private $colomboId = 8001;
    private $sriLankaId = 8002;

    public function editCityData($id)
    {
        if ($id == $this->colomboId || $id == $this->sriLankaId)
            return view('city.edit-city-details')->with('city', CityMaps::find($id));

        $data = [
          'city' => CityMaps::find($id),
          'cities' => City::find($id)
        ];

        return view('city.edit-city-details')->with($data);
    }

    public function saveCityData(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $cityDesc = str_replace('<div>', '<p>', str_replace('</div>', '</p>', $request->city_description));
        $mapsDesc = str_replace('<div>', '<p>', str_replace('</div>', '</p>', $request->map_description));

        $data = array(
            'sale_avg_house_price' => $request->sale_avg_house_price,
            'sale_avg_apartment_price' => $request->sale_avg_apartment_price,
            'sale_avg_land_price' => $request->sale_avg_land_price,
            'rent_avg_house_price' => $request->rent_avg_house_price,
            'rent_avg_apartment_price' => $request->rent_avg_apartment_price,
            'rent_avg_land_price' => $request->rent_avg_land_price,
            'num_bus_stops' => $request->num_bus_stops,
            'num_schools' => $request->num_schools,
            'num_hospitals' => $request->num_hospitals,
            'num_banks' => $request->num_banks,
            'num_restaurants' => $request->num_restaurants,
            'num_fuel_station' => $request->num_fuel_stations,
            'city_population' => $request->city_population,
            'city_description' => $cityDesc,
            'map_description' => $mapsDesc,
        );

        DB::table('city_maps')
            ->where('city_id', $request->id)
            ->update($data);

        DB::table('cities')->where('city_no', $request->id)->update(['city_radius' => $request->city_radius]);

        return Redirect::route('list.city')->with('status', 'City Updated Successfully!');
    }

    public function listCity()
    {
        $data = [
          'cities' => City::all()
        ];

        return view('city.city-list')->with($data);
    }

    public function cityListJson()
    {
       $cityList = City::select('city_no', 'city_name')->orderBy('city_no', 'asc')->get();

       $colombo = [
           'city_no' => $this->colomboId,
           'city_name' => 'Colombo',
       ];

       $sriLanka = [
           'city_no' => $this->sriLankaId,
           'city_name' => 'Sri Lanka',
       ];

        $cityList->push($colombo);
        $cityList->push($sriLanka);

       return response()->json(['data' => $cityList]);
    }

    /*public function convertDivToParagraph()
    {
        $cities = DB::table('city_maps')->whereRaw('city_description <> ""')->get();

        foreach ($cities as $city) {
            if (strpos($city->city_description, '<div>') !== true) {
                $cityNew = str_replace('<div>', '<p>', str_replace('</div>', '</p>', $city->city_description));
                DB::table('city_maps')->where('id', $city->id)->update(['city_description', $cityNew]);
            }

            return redirect()->route('list.city');

        }
    }*/

}
