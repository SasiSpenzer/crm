<?php

namespace App\Contracts;

interface MemberInterface {
	public function save(Array $payload);

	public function save_membership(Array $payload);

	public function save_membershipDetails(Array $payload);

	public function getCategoryWiseMemberCount();

    public function membershipDataApproval($approvalId);

}
