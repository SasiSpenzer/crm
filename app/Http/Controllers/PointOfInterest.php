<?php

namespace App\Http\Controllers;

use App\City;
use App\PointOfInterestImages;
use Illuminate\Http\Request;
use App\PointOfInterest as POI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Intervention\Image\ImageManagerStatic as Image;

class PointOfInterest extends Controller
{
    public function poiList()
    {
        return view('point-of-interest/point-of-interest-list');
    }

    public function editPoi($id = 0)
    {
        $data['cities'] =  City::select('*')->orderBy('city_name', 'ASC')->get();

//        POI::find($poiId)->poiImages; // get poi images

        if (!$id) {
            return view('point-of-interest/edit-point-of-interest-details')->with($data);
        }

        $data['poi'] =  POI::find($id);

        $data['poiImages'] = PointOfInterestImages::where('poi_id', $id)->get();

        return view('point-of-interest/edit-point-of-interest-details')->with($data);
    }

    public function submitPOIDetails(Request $request)
    {
       $data = [
         'title' => $request->title,
         'city_id' => $request->city_id,
         'description' => $request->description,
         'latitude' => $request->latitude,
         'longitude' => $request->longitude,
         'alt_tag' => str_replace(' ','-', strtolower($request->title))
       ];

        $poiId = isset($request->poi_id) ? $request->poi_id : false;

        $imageNames = [];

        if ($poiId)
        {
            if ($request->hasFile('poi_images'))
            {
                $imageNames = $this->imageUpload($poiId, $request);
            }

            DB::table('point_of_interest')->where('id', $poiId)->update($data);

        } else {

            DB::table('point_of_interest')->insert($data);

            $poiId = POI::orderBy('id', 'DESC')->first()->id;

            if ($request->hasFile('poi_images'))
            {
                $imageNames = $this->imageUpload($poiId, $request);
            }
        }

        if (is_array($imageNames))
        {
            foreach ($imageNames as $img)
            {
                $imgArray = [
                    'poi_id' => $poiId,
                    'image' => $img,
                ];

                DB::table('point_of_interest_images')->insert($imgArray);
            }
        }

       return Redirect::route('point.of.interest.list')->with('status', 'Point of Interest Updated Successfully!');
    }

    public function deletePointOfInterest($poiId)
    {
        POI::destroy($poiId);

        $status = 'Condo '.$poiId.' Deleted!';

        return Redirect::route('point.of.interest.list')->with('status', $status);
    }

    public function poiListJSON()
    {
        $poiList = POI::select('id', 'title')->get();

        return response()->json(['data' => $poiList]);
    }

    public function imageUpload($poiId, $request)
    {
        $imageNames = [];
        $images = $request->file('poi_images');

        $dir = __DIR__.'/../../../../../images/point-of-interest/'.$poiId;

        if (!is_dir($dir))
            mkdir($dir, 0777, true);

        foreach ($images as $img) {

            $filename = $img->getClientOriginalName();
            $filename = str_replace(' ', '-', $filename);
            // $extension = $img->getClientOriginalExtension();

            Image::make($img)->resize(600, 400)->save($dir.'/'.$filename, 40, 'jpg');

            array_push($imageNames, $filename);
        }

        return $imageNames;
    }

    public function deletePointOfInterestImage($imageId)
    {
        $poiImage = PointOfInterestImages::find($imageId);

        $dir = __DIR__.'/../../../../../images/point-of-interest/'.$poiImage->poi_id;

        $imagePathWithFileName = $dir .'/'. $poiImage->image;

        PointOfInterestImages::destroy($imageId);

        if (file_exists($imagePathWithFileName))
        {
            unlink($imagePathWithFileName);
            return Redirect::route('edit.point.of.interest', $poiImage->poi_id)->with('status', 'Image deleted successfully!');
        } else {
            return Redirect::route('edit.point.of.interest', $poiImage->poi_id)->with('status', 'File does not exist!');
        }

    }

}
