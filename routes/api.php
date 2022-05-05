<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/condo-list', function (){

    return DB::select('
                        SELECT * 
                        FROM newhomes 
                        ORDER BY priority
                        LIMIT 6
                     ');

});

Route::get('/condo-details/{condoName}', function ($condoName){

    $condoName = str_replace('+', ' ', $condoName);

    return DB::select("
                        SELECT * 
                        FROM newhomes 
                        WHERE name = '$condoName'
                     ");

});
