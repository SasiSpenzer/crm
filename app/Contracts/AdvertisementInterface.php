<?php

namespace App\Contracts;

interface AdvertisementInterface {

	/**
	 * Get list of ads by user/customer email
	 * @return JSON Datatable json object
	 */
	public function ActivateAd($adId, $isChecked);
}
