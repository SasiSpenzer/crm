<?php

namespace App\Repositories;

use App\Advertisement;
use App\Autoboost;
use App\Contracts\AdvertisementInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EloquentAdvertisementRepository implements AdvertisementInterface {
	public function __construct() {
	}

	/**
	 * Activate ad
	 * @return array
	 */
	function ActivateAd($adId, $isChecked) {
	    $insertarray = array();
        $member_type_data = \DB::table('admin_members')
            ->join('adverts', 'adverts.UID', '=', 'admin_members.uid')
            ->select(array('admin_members.type AS type','adverts.UID','adverts.is_active','admin_members.category'))
            ->where('adverts.ad_id', '=', $adId)
            ->limit(1)
            ->get()
            ->toArray();
        $member_type = isset($member_type_data[0])?$member_type_data[0]->type:'';
        $UID = isset($member_type_data[0])?$member_type_data[0]->UID:'';
        $is_active = isset($member_type_data[0])?$member_type_data[0]->is_active:'';
        $category = isset($member_type_data[0])?$member_type_data[0]->category:'';
		if ($isChecked == "true") {

            if($member_type == 'Member') {
                $save = Advertisement::where('ad_id', '=', $adId)
                    ->update(['is_active' => 1]);
                $isActive = ($save) ? "1" : "";
            } else {
                $isActive = "-1";
            }
            if($category == 'Single Ad'){
                // Add Record to Actions BY Sasi Spenzer
                $insertarray['uid'] = $UID;
                $insertarray['action'] = 'Inactive ad made active';
                $insertarray['qty'] = '';
                $insertarray['value'] = '';
                $insertarray['ad_id'] = $adId;
                $insertarray['comments'] = 'Ad made active by '.Auth::user()->username;
                $insertarray['reminder'] = '';
                $insertarray['date_time'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                $insertarray['by'] = Auth::user()->username;
                $insertarray['created_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                $insertarray['updated_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');

                $add = \DB::table('admin_members_actions')->insert($insertarray);
            }


            //  From is_active=3 to is_active=1 Add Record to Actions BY Sasi Spenzer 2021-09-27 ** WFH
            if($category == 'Single Ad'){
                if($is_active == 3){
                    if($category == 'Single Ad'){ // Only Single Ads
                        $insertarray['uid'] = $UID;
                        $insertarray['action'] = 'Ad Activated';
                        $insertarray['qty'] = '';
                        $insertarray['value'] = '';
                        $insertarray['ad_id'] = $adId;
                        $insertarray['comments'] = 'Ad '.$adId.' activated through CRM';
                        $insertarray['reminder'] = '';
                        $insertarray['date_time'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                        $insertarray['by'] = Auth::user()->username;
                        $insertarray['created_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                        $insertarray['updated_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');

                        $add = \DB::table('admin_members_actions')->insert($insertarray);
                    }

                }
            }


		} else {
			$save = Advertisement::where('ad_id', '=', $adId)
				->update(['is_active' => 0]);
			$isActive = ($save) ? "0" : "";
            if($category == 'Single Ad') {
                // Add Record to Actions BY Sasi Spenzer
                $insertarray['uid'] = $UID;
                $insertarray['action'] = 'Ad made inactive';
                $insertarray['qty'] = '';
                $insertarray['value'] = '';
                $insertarray['ad_id'] = $adId;
                $insertarray['comments'] = 'Ad made inactive by ' . Auth::user()->username;
                $insertarray['reminder'] = '';
                $insertarray['date_time'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                $insertarray['by'] = Auth::user()->username;
                $insertarray['created_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');
                $insertarray['updated_at'] = Carbon::now()->timezone('Asia/Colombo')->format('Y-m-d H:i:s');

                $add = \DB::table('admin_members_actions')->insert($insertarray);
            }
		}

		return ['ad_id' => $adId, 'is_active' => $isActive];
	}
    function AutoboostAd($adId, $isChecked) {

        if ($isChecked == "true") {
            $save = Autoboost::where('ad_id', '=', $adId)
                    ->update(['status' => 1]);
            $isActive = ($save) ? "1" : "";

        } else {
            $save = Autoboost::where('ad_id', '=', $adId)
                ->update(['status' => 0]);
            $isActive = ($save) ? "0" : "";
            
        }

        return ['ad_id' => $adId, 'status' => $isActive];
    }

}