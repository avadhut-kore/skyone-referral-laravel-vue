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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/* Route::any('pass',function(){
	$arrData = [];
	$str = "admin";
	$arrData['md5'] = md5($str);
	$arrData['bcrypt'] = bcrypt($str);
	$arrData['encryptpass'] = Crypt::encrypt($str);
	dd($arrData);
}); 
*/
// Some Routes are without authentication
Route::any('login','LoginController@login');
Route::post('register','User\UserController@saveUser');
Route::any('send-registration-otp','User\UserController@sendRegistrationOtp');
Route::any('verify-registration-otp','User\UserController@verifyRegistrationOtp');
//Route::post('forgot-password', 'ForgotPasswordController@sendResetPasswordLink'); // send link
Route::any('forgot-password', 'ForgotPasswordController@resetPasswordRandom'); // random pwd reset and send
Route::post('reset-password', 'ForgotPasswordController@resetPasswordByUser'); // pwd reset by user
Route::any('checkuserexist', 'User\UserController@userExists');
//Route::any('checkuserexist', 'CommonController@userExists');
Route::any('country','CommonController@getCountry');
Route::any('getStateByCountry', 'CommonController@getStateByCountry');
Route::any('user/country','CommonController@getCountry');
Route::any('admin/project-setting','CommonController@getProjectSetting');
Route::any('packages','CommonController@getPackage');


//common Routes without prefix
Route::middleware(['auth:api'])->group(function () {
	Route::any('logout','LoginController@logout');
	Route::get('/user', function () {
		return Auth::user();
	});
	/* check user otp */
	Route::post('checkotp','LoginController@checkOtp');
	Route::any('smsverification','LoginController@verifyMobile');
	Route::any('resend-otp','LoginController@resendOtp');
	Route::any('admin/verifyotp','LoginController@checkOtp');
	Route::any('server-information','User\UserController@getServerInformation');
	Route::any('check-user-access','CommonController@checkUserAccess');
	Route::any('user/packages','CommonController@getPackage');
});
Route::post('checkotpadminlogin','LoginController@checkOtpAdminLogin');
// define user route here only
Route::namespace('User')->middleware(['auth:api','UserMiddleware'])->prefix('/user/')->group(function () {
	
	Route::any('send-profile-otp','UserController@sendRegistrationOtp');
	Route::any('verify-profile-otp','UserController@verifyRegistrationOtp');
	
	Route::any('userdashboard','DashboardController@getUserDashboardDetails');
   
    /* User Routes */
    Route::any('mailverification','LoginController@verifyEmail');
	Route::post('2fa/enable','Google2FAController@enableTwoFactor');
	Route::any('change-password', 'UserController@changePassword');
	Route::any('change-password-otp', 'UserController@sendChangePasswordOtp');
	Route::any('profile', 'UserController@profile');
	Route::any('change-address-otp', 'UserController@sendChangeAddressOtp');
	Route::any('change-address', 'UserController@changeAddress');
	Route::any('2fa/validate', 'Google2FAController@postValidateToken');
	Route::post('update-user','UserController@updateUser');
	Route::any('2fa/validatelogintoken',  'Google2FAController@loginpostValidateToken');
	Route::any('2fa/enable', 'Google2FAController@enableTwoFactor');

	// Category Controller Routes
	Route::get('category-list','CategoryController@getCategories');
	Route::get('category/details/{id}','CategoryController@getCategoryDetails');
	Route::get('sub-category-list/{category_id}','CategoryController@getSubCategoriesByCategoryId');

	// Product Controller Routes
	Route::get('product-list','ProductController@getProducts');
	Route::get('product/details/{id}','ProductController@getProductDetails');

	// Referal Controller Routes
	Route::get('referal-list','ReferalController@getReferals');
	Route::get('referal/details/{id}','ReferalController@getReferalDetails');

	// Referal status Controller Routes
	Route::get('referal-status-list','ReferalStatusController@getReferalStatus');
	Route::get('referal-status/details/{id}','ReferalStatusController@getReferalStatusDetails');

	// Reward type Controller Routes
	Route::get('rewart-type-list','RewardTypeController@getRewardType');
	Route::get('rewart-type/details/{id}','RewardTypeController@getRewardTypeDetails');
	
});


// define admin route here only
Route::namespace('Admin')->middleware(['auth:api','AdminMiddleware'])->prefix('/admin/')->group(function () {

	Route::get('user-list','UserController@getUsers');
	Route::get('user/edit/{id}','UserController@edit');
	Route::post('user/update','UserController@update');
	Route::post('user/destroy','UserController@destroy');
	Route::get('user/details/{id}','UserController@getUserDetails');

	Route::any('users','UserController@getAllUser');
	Route::any('user-2fa-update', 'Google2FAController@update2faUserStatus');
    Route::any('getuserpassword', 'UserController@getUserPassword');
    Route::post('getuserprofile', 'UserController@getUserProfileDetails');
    Route::post('updateuserpassword', 'UserController@updateUserPassword');
    Route::any('viewprofile', 'UserController@userProfile');
    
	Route::post('update-user','UserController@updateUser');
    Route::any('navigations','NavigationsController@getNavigations');
    Route::any('checkuserexist', 'UserController@checkUserExist');

    // Category Controller Routes
	Route::any('category-list','CategoryController@getCategories');
	Route::post('category/store','CategoryController@store');
	Route::get('category/edit/{id}','CategoryController@edit');
	Route::post('category/update','CategoryController@update');
	Route::post('category/destroy','CategoryController@destroy');
	Route::get('category/details/{id}','CategoryController@getCategoryDetails');
	Route::get('sub-category-list/{category_id}','CategoryController@getSubCategoriesByCategoryId');

	// Product Controller Routes
	Route::get('product-list','ProductController@getProducts');
	Route::post('product/store','ProductController@store');
	Route::get('product/edit/{id}','ProductController@edit');
	Route::post('product/update','ProductController@update');
	Route::post('product/destroy','ProductController@destroy');
	Route::get('product/details/{id}','ProductController@getProductDetails');

	// Referal Controller Routes
	Route::get('referal-list','ReferalController@getReferals');
	Route::post('referal/store','ReferalController@store');
	Route::get('referal/edit/{id}','ReferalController@edit');
	Route::post('referal/update','ReferalController@update');
	Route::post('referal/destroy','ReferalController@destroy');
	Route::get('referal/details/{id}','ReferalController@getReferalDetails');

	// Referal status Controller Routes
	Route::get('referal-status-list','ReferalStatusController@getReferalStatus');
	Route::post('referal-status/store','ReferalStatusController@store');
	Route::get('referal-status/edit/{id}','ReferalStatusController@edit');
	Route::post('referal-status/update','ReferalStatusController@update');
	Route::post('referal-status/destroy','ReferalStatusController@destroy');
	Route::get('referal-status/details/{id}','ReferalStatusController@getReferalStatusDetails');

	// Reward type Controller Routes
	Route::get('rewart-type-list','RewardTypeController@getRewardType');
	Route::post('rewart-type/store','RewardTypeController@store');
	Route::get('rewart-type/edit/{id}','RewardTypeController@edit');
	Route::post('rewart-type/update','RewardTypeController@update');
	Route::post('rewart-type/destroy','RewardTypeController@destroy');
	Route::get('rewart-type/details/{id}','RewardTypeController@getRewardTypeDetails');

});