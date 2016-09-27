<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Auth login routes
Auth::routes();

//home routes
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/register', function(){
    return view('auth.register');
});
Route::group(['middleware' => 'web'], function (){
    Route::get('createField', function () {
        return view('createField');
    });
});

Route::get('/showField', function () {
    return view('showField');
});
Route::get('/fieldPhases/{fieldName}', 'FieldPhasesController@index');

Route::get('/demo', function () {

    $obj = new \App\Library\SRTMGeoTIFFReader(public_path('uploads') . DIRECTORY_SEPARATOR . 'user2' . DIRECTORY_SEPARATOR . 'imerominia');
    dd($obj->getAscentDescent());
    return 'demo';
});


