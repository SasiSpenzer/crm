<?php

namespace App\Http\Controllers;

use App\City;
use App\Condo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManagerStatic as Image;


class CondoController extends Controller
{
    public function condoList()
    {
        return view('condo.condo-list');
    }

    public function jsonCityList()
    {
        $condoList = Condo::select('ID', 'name')->orderBy('ID', 'ASC')->get();

        return response()->json(['data' => $condoList]);
    }
    
    public function editCondoDetailsForm($condoId = null)
    {
        $data = [
            'condo' => Condo::find($condoId),
            'cityList' => City::orderBy('city_name')->get()
        ];

        return view('condo.edit-condo-details')->with($data);
    }

    public function saveCondoDetails(Request $request)
    {
        $data = [
            'name' => $request->condo_name,
            'city_id' => $request->city_id,
            'location' => $request->location,
            'type' => $request->type,
            'developer' => $request->developer,
            'floors' => $request->floors,
            'units' => $request->units,
            'completion_date' => $request->completion_date,
            'lat' => $request->latitude,
            'lon' => $request->longitude,
            'price_range' => $request->price_range,
            'tel' => $request->tel,
            'email' => $request->email,
            'website' => $request->website,
            'desc' => $request->condo_description,
        ];

        $condoId = isset($request->condo_id) ? $request->condo_id : false;

        if ($condoId)
        {
            if ($request->hasFile('condo_images'))
            {
                $data['picture'] = $this->imageUpload($condoId, $request);
            }

            DB::table('newhomes')->where('ID', $condoId)->update($data);

        } else {

            DB::table('newhomes')->insert($data);

            $condoId = Condo::orderBy('ID', 'DESC')->first()->ID;

            if ($request->hasFile('condo_images'))
            {
                $imageName = $this->imageUpload($condoId, $request);

                $condo = Condo::find($condoId);
                $condo->picture = $imageName;
                $condo->save();
            }

        }

        return Redirect::route('condo.list')->with('status', 'Condo Updated Successfully!');

    }

    public function imageUpload($condoId, $request)
    {
        $images = $request->file('condo_images');

        $dir = __DIR__.'/../../../../../images/condo/'.$condoId;

        if (!is_dir($dir))
            mkdir($dir, 0777, true);

        $i = 0;
        foreach ($images as $img) {

            $filename = $img->getClientOriginalName();
            $filename = str_replace(' ', '-', $filename);
            // $extension = $img->getClientOriginalExtension();

            Image::make($img)->resize(600, 400)->save($dir.'/'.$filename, 40);

            if ($i === 0)
                $imageName = $filename;

            $i++;
        }

        return $imageName;
    }

    public function deleteCondo($condoId)
    {
        Condo::destroy($condoId);

        $status = 'Condo '.$condoId.' Deleted!';

        return Redirect::route('condo.list')->with('status', $status);
    }

    public function moveOldCondoImagesToNewDirectory()
    {
        $condos = Condo::all();

        foreach ($condos as $condo)
        {
            $dir = __DIR__ . '/../../../../../images/condo/' . $condo->ID;

            if ($condo->picture)
            {
                $oldImagePath = __DIR__ . '/../../../../../images/condos/' . $condo->picture;

                if (!is_dir($dir))
                {
                    mkdir($dir, 0777, true);
                    chmod($dir,0777);
                }

                if (isset($oldImagePath) && file_exists($oldImagePath))
                {
                    copy($oldImagePath, $dir.'/'.$condo->picture);
                }
            }

        }

        return 'All images copied successfully!';

    }
}
