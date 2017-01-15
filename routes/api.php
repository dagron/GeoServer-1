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

Route::get('languages/{locale}', function($locale) {
    Session::set('applocale', $locale);//App::setLocale($locale);
    return json_encode(['locale' => App::getLocale()]); 
});

// Create/Upload field
Route::post('uploadfield','FieldController@uploadfield');
Route::post('uploadfieldDate','FieldController@uploadfieldDate');
Route::post('deletefieldDate', 'FieldController@deletefieldDate');
Route::post('deletefield', 'FieldController@deletefield');
Route::post('addProcess', 'FieldController@addProcessedField');

// Create/Upload 
Route::post('standard/uploadfield', 'StandardFieldController@uploadfield');
Route::post('standard/deletefield', 'StandardFieldController@deleteField');
Route::post('standard/uploadfieldDate', 'StandardFieldController@uploadfieldDate');
Route::post('standard/deletefieldDate', 'StandardFieldController@deletefieldDate');

// Markers
Route::post('createMarker', 'MarkerController@store');
Route::get('markers', 'MarkerController@index');
Route::get('markers/{id}', 'MarkerController@getMarker');
Route::delete('markers/{id}', 'MarkerController@deleteMarker');

//Polygons
Route::post('polygons', 'PolygonController@store');
Route::get('polygons/{id}', 'PolygonController@getPolygon');
Route::delete('polygons/{id}', 'PolygonController@deletePolygon');

//Users
Route::get('users/{name}', 'UsersController@getUser');
Route::post('users/addFarmer', 'UsersController@addFarmerToList');
Route::post('users/removeFarmer', 'UsersController@removeFarmerFromList');

//fields
Route::get('fields/{name}', 'FieldController@searchField');
Route::get('standard/fields/{name}', 'StandardFieldController@searchField');

//Comments
Route::post('standard/comments', 'CommentController@create');

Route::get('download/{user_id}/{field_name}/{date}', 'FieldController@download');

Route::get('test' , function(){

    $con = new ImageProcessingController('~/4band1.tif','~/imgTests/');
          $con->process();
});

