<?php

namespace App\Contracts;

interface MemberActionInterface {
	public function save(Array $payload);
	public function membershipSave(Array $payload);
	public function saveSales(Array $payload);
    public function membershipExpire();

}
