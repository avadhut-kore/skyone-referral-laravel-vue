<?php

use App\Models\ProjectSetting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::group(['middleware' => ['auth']], function ()
{*/
   Route::any('user', function () {
    if(	Auth::check()) {
    	return view('home');
    }
    return view('home');
});
//});

// Some Routes are without authentication
//Route::any('login','LoginController@login');
//Route::any('register','User\UserController@saveUser');
Route::post('forgot-password', 'ForgotPasswordController@sendResetPasswordLink');
Route::post('reset-password', 'ForgotPasswordController@resetPassword');
Route::any('checkuserexist', 'User\UserController@userExists');
Route::any('country','CommonConroller@getCountry');


Route::any('skyoneadmin', function(){
	$setting=ProjectSetting::orderBy('id','desc')->first();
	if($setting->stop_status=='on'){

		return view('admin');
	}else{

          return view('stop');

	}
	

});
/*Route::get('/user', function () {
    if(Auth::check()) {
    	return view('home');
    }
    return view('login');
});*/
Route::any('/',function(){
	if(Auth::check()) {
		return redirect('/user');
	}
	return view('home');
});

Route::any('logout',function(){
	if(Auth::check()) {
		Auth::logout();
		return view('login');
	} else {
		return view('login');
	}
});