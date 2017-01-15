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

Route::group(['middleware' => ['web']], function (){
    //Auth login routes
//Auth::routes();
//home routes
Route::get('/', 'HomeController@indexStandard');
Route::get('/home', 'HomeController@index');
Route::get('/home-standard', 'HomeController@indexStandard');


   Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
        Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
        Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

        // Registration Routes...
             Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
                 Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);
        
                 // Password Reset Routes...
                     Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
                         Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
                             Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
                                 Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);
    Route::get('/login', function () {    
        if (Auth::check()){
            return redirect('/home');
        }
        return view('auth.login');
    });
    Route::get('/register', function(){
        if(Auth::check()) {
            return redirect('/home');
        }
        return view('auth.register');
    });
    Route::get('createField', function () {
        return view('createField');
    });
    Route::get('standard/createField', function () {
        return view('standard/createField');
    });
});

// Pro field views
Route::get('/fieldPhases/{fieldName}', 'FieldPhasesController@index');
Route::get('/createFieldDate/{fieldName}', 'FieldPhasesController@create');
Route::get('/showField/{fieldName}/{fieldDate}', 'FieldPhasesController@show');
Route::get('/addProcess/{fieldName}/{fieldDate}','FieldPhasesController@addProcess');

// Standard field views
Route::get('/standard/fieldPhases/{fieldName}', 'StandardFieldPhasesController@index');
Route::get('/standard/createFieldDate/{fieldName}', 'StandardFieldPhasesController@create');
Route::get('/standard/showField/{fieldName}/{fieldDate}', 'StandardFieldPhasesController@show');

//Agriculturist
Route::get('/addFarmer', function () {
    return view('addFarmer');
});
Route::get('/agriculturistFields/{id}', 'AgricalturistViewController@userFields');
Route::get('/agiculturistFieldPhases/{id}/{fieldName}', 'AgricalturistViewController@userFieldsPhases');
Route::get('/agiculturistShowField/{id}/{fieldName}/{fieldDate}', 'AgricalturistViewController@showField');

//Standard fields
Route::get('/standard/agriculturistFieldPhases/{id}/{fieldName}','AgricalturistViewController@userStandardFieldsPhases');
Route::get('/standard/agriculturistShowField/{id}/{fieldName}/{fieldDate}', 'AgricalturistViewController@showStandardField');
