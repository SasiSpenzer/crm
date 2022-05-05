<?php

namespace App\Contracts;

interface DashboardInterface {

	/**
	 * Get dashboard widget details
	 * @param  int $userId User ID
	 * @return json object
	 */
	public function getDetails($userId);

    /**
     * Get dashboard widget user details
     * @param $userId
     * @param $username
     * @return mixed
     */
	public function getUserDetails($userId, $username);

}
