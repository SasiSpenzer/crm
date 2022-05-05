<?php

namespace App\Http\Controllers;

class InvoieController extends Controller {
	//
	public function index() {
		//dd(Datatables::of(User::query())->make(true));
		return view('invoice-system.insert_customer');
	}
}
