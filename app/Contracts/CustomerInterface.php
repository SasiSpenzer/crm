<?php

namespace App\Contracts;

interface CustomerInterface {

	/**
	 * Get member and user details by user ID
	 * @param  int $userId User ID
	 * @return json object
	 */
	public function getByUID($userId);

    /**
     * Get member email address by user ID
     * @param $uid
     * @return mixed
     */
    public function getUserEmailByUID($uid);

    /**
     *  Get user max ad count use by uid
     * @param $email
     * @return string
     */
    public function getUserAdCountByEmail($email);

	/**
	 * Get datatable json
	 * @return JSON Datatable json object
	 */
	public function listCustomerMemberships($type, $searching, $search_data);

    /**
     * Get Non members data
     * @param $data
     * @return mixed
     */
	public function noneMemberships($data);

	/**
	 * Auto-complete user/customer email
	 * @return Array
	 */
	public function AutocompleteCustomerEmail($term);

    /**
     * Get list of ads by user/customer email or search data
     * @param $email
     * @param $search_data
     * @return mixed
     */
	public function listAdsByCustomer($email, $search_data);

}
