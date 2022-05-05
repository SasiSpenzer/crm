<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'admin_member_packages';

    public function filterMembers($packages, $package)
    {
    	$filtered = $packages->filter(function ($item) use ($package) {
		    return $item->category == $package && $item->type == 'Member';
		});

		return $filtered->count();
    }

    public function filterInvoiced($packages, $package)
    {
    	$filtered = $packages->filter(function ($item) use ($package) {
		    return $item->category == $package && $item->type == 'Invoiced';
		});

		return $filtered->count();
    }

    public function filterExpired($packages, $package)
    {
    	$today = \Carbon\Carbon::now();
    	$filtered = $packages->filter(function ($item) use ($package, $today) {
		    return $item->category == $package && $item->type == 'Invoiced' && $item->expiry < $today;
		});

		return $filtered->count();
    }
}
