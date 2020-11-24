<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Middleware for owner
Route::group(['middleware' => ['assign.guard:owners','jwt.auth']],function ()
{
	Route::get('kost/listOwner', 'KostController@show');
    Route::post('kost/', 'KostController@create');
    Route::post('kost/{id}', 'KostController@update');
    Route::delete('kost/{id}', 'KostController@delete');
    Route::get('logout/owner', 'OwnerController@logout');
});

//Middleware for user
Route::group(['middleware' => ['assign.guard:users','jwt.auth']],function ()
{
    Route::get('availability/{id}', 'KostController@ask');
    Route::get('logout', 'UserController@logout');
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::post('register/owner', 'OwnerController@register');
Route::post('login/owner', 'OwnerController@login');

Route::get('kost', 'KostController@index');
Route::get('kost/{id}', 'KostController@detail');
Route::post('kost/search', 'KostController@search');
Route::get('kost/sorted/{order}', 'KostController@sorted');



