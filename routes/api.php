<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//User login/register
//Route::post('login','UserController@login');
//Route::post('signup','UserController@signup');
//Route::get('logout','UserController@logout');


// Create/Upload field
Route::post('uploadfield','FieldController@uploadfield');
Route::post('uploadfieldDate','FieldController@uploadfieldDate');
Route::post('deletefieldDate', 'FieldController@deletefieldDate');
Route::post('deletefield', 'FieldController@deletefield');

//Users
Route::get('users/{name}', 'UsersController@getUser');
Route::post('users/addFarmer', 'UsersController@addFarmerToList');