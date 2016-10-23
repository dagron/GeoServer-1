<?php

use Illuminate\Http\Request;
use App\Library\ImageProcessing\ImageProcessingController;

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
Route::post('addProcess', 'FieldController@addProcessedField');

// Markers
Route::post('createMarker', 'MarkerController@store');
Route::get('markers', 'MarkerController@index');
Route::get('markers/{id}', 'MarkerController@getMarker');
Route::delete('markers/{id}', 'MarkerController@deleteMarker');

//Polygons
Route::post('polygons', 'PolygonController@store');
Route::get('polygons/{id}', 'PolygonController@getPolygon');
//Users
Route::get('users/{name}', 'UsersController@getUser');
Route::post('users/addFarmer', 'UsersController@addFarmerToList');
Route::post('users/removeFarmer', 'UsersController@removeFarmerFromList');


Route::get('test' , function(){

    $con = new ImageProcessingController('~/4band1.tif','~/imgTests/');
          $con->process();
});

