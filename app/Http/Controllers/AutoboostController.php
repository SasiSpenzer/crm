<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Advertisement;
use App\autoboost;
use App\auto_boost_summary;
use App\Member;
use Illuminate\Http\Request;

use DB;

class AutoboostController extends Controller
{
 	
    public function index()
    {
        $autoboost = autoboost::groupBy('user_id')->select('user_id', DB::raw('count(*) as totalslots'))->get();
 		return view('auto-boost.index', compact('autoboost'));
    }

    public function create()
    {
        $slots = auto_boost_summary::all();
        return view('auto-boost.create', compact('slots'));
    }

    public function store(Request $request)
    {
        //$ads = Advertisement::where('UID', $request->uid)->where('is_active', 1);
        $ads = Advertisement::where('UID', $request->uid)->where('is_active', 1)->get();
        DB::table('admin_members')
                        ->where('uid', $request->uid)
                        ->update([
                            'is_active_auto_boost' => 'Y'
                        ]);
        $now = date('Y-m-d h:i:s');
        $weekdays = array(
          $request->monday1,
          $request->monday2,
          $request->tuesday1,
          $request->tuesday2,
          $request->wensday1,
          $request->wensday2,
          $request->thuesday1,
          $request->thuesday2,
          $request->friday1,
          $request->friday2,
          $request->sateday1,
          $request->sateday2,
          $request->sunday1,
          $request->sunday1
        );
        foreach($ads as $ad){
            foreach ($weekdays as $key => $days) {
                if($days){
                    $sql = "INSERT INTO `auto_boost`(`slot_id`, `ad_id`,  `ad_type`, `user_id`, `last_updated`, `status`) VALUES ($days,$ad->ad_id,'".$ad->type."',$request->uid,'".$now."',1)";
                    DB::statement($sql);
                }
            } 
        }
        

        $msg = "Successfully created!";

        return redirect('auto-boost')->with('info', $msg);
    }

    public function show(autoboost $autoboost)
    {
        //
    }

    public function edit(autoboost $autoboost)
    {
        
    	$slots = auto_boost_summary::all();
        return view('auto-boost.slots', compact('autoboost', 'slots'));

        return redirect('auto-boost');
    }

    public function update(Request $request, autoboost $autoboost)
    {
        

        $msg = "Successfully updated!";

        return redirect('auto-boost')->with('info', $msg);

    }

    public function destroy(autoboost $autoboost)
    {
        $autoboost->delete();
        $msg = "Successfully deleted!";

        return redirect('auto-boost')->with('info', $msg);
    }

    
}
