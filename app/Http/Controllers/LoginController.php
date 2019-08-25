<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use URL;
use Validator;
use Exception;
use GuzzleHttp\Client;
use Config;
use Auth;
use DB;
use Illuminate\Http\Response as Response;
use Google2FA;
use Crypt;

// use model here
use App\Models\ProjectSetting as ProjectSettingModel;
use App\Models\Otp as OtpModel;
use App\Models\Activitynotification as ActivitynotificationModel;
use App\Models\SecureLoginData as SecureLoginDataModel;
use App\Models\Masterpwd as MasterpwdModel;
use App\User as UserModel;
use App\Models\Dashboard as DashboardModel;

class LoginController extends Controller {
	/**
     * Function to generate the token.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $arrOutputData  = [];
        $strStatus 		= trans('user.error');
        $arrOutputData['mailverification'] = $arrOutputData['google2faauth'] = $arrOutputData['mailotp'] = $arrOutputData['mobileverification'] = $arrOutputData['otpmode'] = 'FALSE';
        try {
	        $arrInput 		= $request->all();
	        $baseUrl 		= URL::to('/'); 
			$validator 		= Validator::make($arrInput, [
						        'user_id'	=> 'required',
						    	'password' 	=> 'required'
						    ]);	
			// check for validation
	        if($validator->fails()){
	        	return setValidationErrorMessage($validator);	
	        }
	        $arrWhere 	= [];
	        $arrWhere[] = ['user_id',$arrInput['user_id']];
	        $arrWhere[] = ['status','Active'];
	        // check for the master password
	        if((isset($arrInput['admin'])) && ($arrInput['admin'] == "admin")){
	        	$arrWhere[] = ['type','Admin'];
	        } else {
	        	$arrWhere[] = ['type','!=','Admin'];
	        }
	        //dd($arrWhere);
			$userData =	UserModel::select('encryptpass')->where($arrWhere)->first();
			if(!(isset($arrInput['admin']))){
				$master_pwd = MasterpwdModel::where([['password','=',md5($arrInput['password'])]])->first();
				if(empty($userData)) {
					$intCode 	= Response::HTTP_UNAUTHORIZED;
		        	$strStatus	= Response::$statusTexts[$intCode];
		        	$strMessage = trans('user.usernotexists');
		        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
				} else  {
					// if master passport matched with input password then replace the password by user password
					if(!empty($master_pwd)){
						$arrInput['password'] = Crypt::decrypt($userData->encryptpass);
					}
					
				}
			}
			// create GuzzleHttp client
	        $http = new GuzzleHttp\Client;
	        //$response = $http->post('http://www.helpingcommunity.biz/helping-community/oauth/token', [
	        $response = $http->post($baseUrl.'/oauth/token', [
				'form_params' => [
				    'grant_type' 	=> 'password',
				    'client_id' 	=> Config::get('constants.server_client_id'),
				    'client_secret' => Config::get('constants.server_client_secret'),
				    /*'client_id' 	=> env('CLIENT_ID'),
				    'client_secret' => env('CLIENT_SECRETE'),*/
				    'username' 		=> $arrInput['user_id'],
				    'password' 		=> $arrInput['password'],
				    'scope' 		=> '',
				],
			]);
			$intCode 	= Response::HTTP_OK;
			$strMessage	= "The user credentials matched";
			$strStatus 	= Response::$statusTexts[$intCode];
			$passportResponse  	= json_decode((string) $response->getBody());
			$client = new GuzzleHttp\Client;
			// check for user data
			$userRequest = $client->request('GET', $baseUrl.'/api/user', [
			    'headers' => [
			        'Accept' => 'application/json',
			        'Authorization' => 'Bearer '.$passportResponse->access_token,
			    ],
			]);
			$user  	= json_decode((string) $userRequest->getBody());
			$strTok = $passportResponse->access_token;
			$arrOutputData['access_token'] = $strTok;
			//check for master password
			if(!empty($user)) {
				$arrOutputData['mobileverification']= 'FALSE';
				$arrOutputData['mailverification']  = 'FALSE';
				$arrOutputData['google2faauth']   	= 'FALSE';
				$arrOutputData['mailotp']   		= 'FALSE';
				$arrOutputData['otpmode']   		= 'FALSE';
				$arrOutputData['master_pwd']   		= 'FALSE';
				if(!empty($master_pwd)){
					$arrOutputData['user_id']   		=  $user->user_id;
					$arrOutputData['password']   		=  $arrInput['password'];
					$arrOutputData['master_pwd']   		= 'TRUE';
				} else {
					$projectSetting = ProjectSettingModel::first();
					if(!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
						// if google 2 fa is enable then dont issue OTP
						if($user->google2fa_status=='enable') {
							$arrOutputData['google2faauth']   		= 'TRUE';
						} else {
							// issue token
							$otpMode = ''; 
							if($user->type != 'Admin') {
								if(isset($arrInput['otp']) && $arrInput['otp'] == 'mail') {
									$otpMode =  'email';
								}
								if(isset($arrInput['otp']) && $arrInput['otp'] == 'mobile') {
									$otpMode =  'email';
								}
							} else {
								$otpMode = 'mobile';
							}
							if($otpMode != '') {
								$arrOutputData  = $this->sendOtp($user,$otpMode);
								$strMessage = trans('user.otpsent');
							}
						}
					}
				}
				$arrOutputData['access_token'] = $strTok;
			}
 			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			dd($e);
			$strMessage = "The user credentials were incorrect";
			$intCode 	= Response::HTTP_UNAUTHORIZED;
        	$strStatus	= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
        
    }
    /**
     * Function for Logout
     */ 
    public function logout(Request $request){
    	$strStatus 		= trans('user.error');
    	$arrOutputData    = [];
    	try {
    		$request->user()->token()->revoke();
    		$intCode 		= Response::HTTP_OK;
			$strStatus		= Response::$statusTexts[$intCode];
			$strMessage 	= trans('user.logout'); 
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
    		$intCode 		= Response::HTTP_BAD_REQUEST;
        	$strMessage		= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    	}
    }

    /**
     * Function to verify the otp
     * 
     */ 
	public function checkOtp(Request $request) {
		$strMessage    = trans('user.error');
    	$arrOutputData    = [];
    	try {
			$arrInput = $request->all();
			$otp 	=trim($arrInput['otp']);
			$user 	= Auth::user();
			$id 	= $user->id;
			/*$checotpstatus = OtpModel::where([
			['id','=',$id],*/
			
			/*['otp','=',md5($otp)]])->orderBy('otp_id', 'desc')->first();*/
			$arrOtpWhere = [[[['id','=',$id],['otp','=',md5($otp)]],['ip_address', $_SERVER['REMOTE_ADDR']]]];
			$checotpstatus = OtpModel::where($arrOtpWhere)->orderBy('otp_id', 'desc')->first();
			
			// check otp status 1 - already used otp
			if(empty($checotpstatus)){
				$strMessage = 'Invalid otp for token';
				$intCode 	= Response::HTTP_BAD_REQUEST;
	        	$strStatus	= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}
			if($checotpstatus->otp_status == 1){
				// otp already veriied
				$strMessage 	= trans('user.otpverified');
				$intCode 		= Response::HTTP_BAD_REQUEST;
	        	$strStatus		= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}

			// make otp verify
			secureLogindata($user->user_id,$user->password,'Login successfully');
			$updateData=array();
			$updateData['otp_status']=1; //1 -verify otp
			$updateData['out_time']=date('Y-m-d H:i:s');
			$updateOtpSta =  OtpModel::where('id', $id)->update($updateData);
			if(!empty($updateOtpSta)) {
				// ==============activity notification==========
				$date  = \Carbon\Carbon::now();
				$today = $date->toDateTimeString();
				$actdata = array();     
				$actdata['id'] = $id;
				$actdata['message'] = 'Login successfully with IP address ( '.$request->ip().' ) at time ('.$today.' ) ';
				$actdata['status']=1;
				$actDta=ActivitynotificationModel::create($actdata);

			} // end of else
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
			$strMessage  	= "Otp Verified.Login successfully"; 
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			//return ['response' => $e->getMessage()];
    		$intCode 		= Response::HTTP_BAD_REQUEST;
    		$strStatus		= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    	}
	}  


	/**
     * Function to verify the otp
     * 
     */ 
	public function checkOtpAdminLogin(Request $request) {
		$strMessage    = trans('user.error');
    	$arrOutputData    = [];
    	try {
			$arrInput = $request->all();
			$otp 	=trim($arrInput['otp']);
			//$user 	= Auth::user();

			$id 	= 1;
			/*$checotpstatus = OtpModel::where([
			['id','=',$id],*/
			
			/*['otp','=',md5($otp)]])->orderBy('otp_id', 'desc')->first();*/
			$arrOtpWhere = [[[['id','=',$id],['otp','=',md5($otp)]],['ip_address', $_SERVER['REMOTE_ADDR']]]];
			$checotpstatus = OtpModel::where($arrOtpWhere)->orderBy('otp_id', 'desc')->first();
			
			// check otp status 1 - already used otp
			if(empty($checotpstatus)){
				$strMessage = 'Invalid otp for token';
				$intCode 	= Response::HTTP_BAD_REQUEST;
	        	$strStatus	= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}
			if($checotpstatus->otp_status == 1){
				// otp already veriied
				$strMessage 	= trans('user.otpverified');
				$intCode 		= Response::HTTP_BAD_REQUEST;
	        	$strStatus		= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}

			// make otp verify
			//secureLogindata($user->user_id,$user->password,'Login successfully');
			$updateData=array();
			$updateData['otp_status']=1; //1 -verify otp
			$updateData['out_time']=date('Y-m-d H:i:s');
			$updateOtpSta =  OtpModel::where('id', $id)->update($updateData);
			if(!empty($updateOtpSta)) {
				// ==============activity notification==========
				$date  = \Carbon\Carbon::now();
				$today = $date->toDateTimeString();
				$actdata = array();     
				$actdata['id'] = $id;
				$actdata['message'] = 'Login successfully with IP address ( '.$request->ip().' ) at time ('.$today.' ) ';
				$actdata['status']=1;
				$actDta=ActivitynotificationModel::create($actdata);

			} // end of else
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
			$strMessage  	= "Otp Verified.Login successfully"; 
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			//return ['response' => $e->getMessage()];
    		$intCode 		= Response::HTTP_BAD_REQUEST;
    		$strStatus		= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    	}
	} 

	
	/**
	 * Function to verify the mobile no
	 * 
	 * @param $request : HTTP Request object
	 * 
	 */ 
	public function verifyMobile(Request $request)  { 
		$intCode 		= Response::HTTP_BAD_REQUEST;
        $strStatus 		= Response::$statusTexts[$intCode];
		try {
			$arrOutputData = [];
			$arrInput = $request->all();
			$user = Auth::user();
			$validator 		= Validator::make($arrInput, [
	            'otp'	 	=> 'required',
	        ]);
			## check for validation
	        if($validator->fails()){
	        	$strMessage	= trans('user.error');
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	        }
	        if($user->status == 'Active' || $user->mobileverify_status == '1'){
	        	$strMessage		= "Mobile already verified";
        		return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			} 

			// user in inactive then verify mobile

			$otp = $arrInput['otp']; 
			if($otp != $user->mobile_otp) { 
		    	secureLogindata($user->user_id,$user->password,'Invalid verification code',$user->mobile);
		        $strMessage = 'Invalid verification code';
		        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		    }        
            $arrOutputData['mobileverification']   	= 'FALSE';
			$arrOutputData['mailverification']   	= 'FALSE';
			$arrOutputData['google2faauth']  		= 'FALSE';
			$arrOutputData['mailotp']   			= 'FALSE';
			$user->status 	= 'Active';
			$user->mobileverify_status = '1';
			$user->save();
			secureLogindata($user->user_id,$user->password,'Mobile verified successfully');        
			$strMessage = "Mobile verified successfully";
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
		} catch (Exception $e) {
			$intCode 		= Response::HTTP_INTERNAL_SERVER_ERROR;
        	$strStatus 		= Response::$statusTexts[$intCode];
        	$strMessage		= trans('user.error');
    	}
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	} 

	/**
	 * Function to send the OTP
	 * 
	 * @param $user 	: User object
	 * 
	 * @param $otpMode 	: OTP Mode(mobile/mail)
	 */ 
	public function sendOtp($users, $otpMode) {
		$arrOutputData  = [];
		$arrOutputData['mailverification'] = $arrOutputData['mobileverification'] = 'FALSE';
		$arrOutputData['google2faauth'] = $arrOutputData['mailotp'] =  $arrOutputData['otpmode'] = 'FALSE';
		DB::beginTransaction();
		try {
			$otpInterval 	= Config::get('constants.settings.OTP_interval');

			$checotpstatus = OtpModel::where([['id','=',$users->id],])->orderBy('entry_time', 'desc')->first();
			if(!empty($checotpstatus)){
	            $entry_time=$checotpstatus->entry_time;
	            $out_time=$checotpstatus->out_time;
	            $checkmin=date('Y-m-d H:i:s',strtotime($otpInterval,strtotime($entry_time)));
	            $current_time=date('Y-m-d H:i:s');
	        }

	        if(false/*!empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1'*/){
	            $updateData=array();
	            $updateData['otp_status']=0;

	            $updateOtpSta 	= OtpModel::where('id', $users->id)->update($updateData);
	           	$intCode       	= Response::HTTP_BAD_REQUEST;
				$strStatus     	= Response::$statusTexts[$intCode];
				$strMessage 	= 'OTP already sent to your mobile no';
	           	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}else{
				$random 	= rand(100000,999999); 
				$insertotp 					= array();
	            $insertotp['id'] 			= $users->id;
	            $insertotp['ip_address']	= trim($_SERVER['REMOTE_ADDR']);
	            $insertotp['otp'] 			= md5($random);
	            $insertotp['otp_status']	= 0;
	            $insertotp['type'] 			= $otpMode;
	            if($otpMode == 'mobile') {
	            	$msg 		= $random.' is your verification code';
	            	//sendMessage($users, $msg);
	            }
	            else if($otpMode == 'mail' || $otpMode == 'email') {
	            	$arrEmailData['email'] 		= $users->email;
	            	$arrEmailData['subject'] 	= 'Otp';
	            	$arrEmailData['otp']		=$random;
	            	$arrEmailData['template']	= 'email.otp';
	            	$arrEmailData['fullname']	= $users->fullname;
	            	//dD($arrEmailData);
	            	sendEmail($arrEmailData);
	            } else if($otpMode == 'both') {
	            	// send email and message from here
	            	$msg 		= $random.' is your verification code';
	            	//sendMessage($users, $msg);
	            	$arrEmailData['email'] 		= $users->email;
	            	$arrEmailData['subject'] 	= 'Otp';
	            	$arrEmailData['otp']		= $random;
	            	$arrEmailData['template']	= 'email.otp';
	            	$arrEmailData['fullname']	= $users->fullname;
	            	sendEmail($arrEmailData);
	            }

				$sendotp  = OtpModel::create($insertotp); 
				$arrOutputData = array();
	            // $arrOutputData['id']   = $users->id;
	            $arrOutputData['mailverification']  	= 'FALSE';
	            $arrOutputData['google2faauth']   		= 'FALSE';
	            $arrOutputData['mailotp']   			=  'TRUE';
	            $arrOutputData['mobileverification']   	= 'FALSE';
	            //$arrOutputData['otpmode']   = ($otpMode == 'mobile') ? 'TRUE' :'FALSE';
	            $arrOutputData['otpmode']   			= $otpMode;
	            $arrOutputData['master_pwd']   			= 'FALSE';
	            // for now overrite with false;
	           // $arrOutputData['otpmode']   = 'FALSE';
	            $mask_mobile 	=	maskMobileNumber($users->mobile); 
	            $mask_email 	=	maskEmail($users->email); 
	            $arrOutputData['email'] = $mask_email;
	            $arrOutputData['mobile'] = $mask_mobile;
	            //$arrOutputData['otp']	=	$random;
			}
		}
		catch (PDOException $e) {
			DB::rollBack();
		} catch (Exception $e) {
			DB::rollBack();
		}
		DB::commit();
    	return $arrOutputData;
	}

	/**
	 * Function to verify the mobile no
	 * 
	 * @param $request : HTTP Request object
	 * 
	 */ 
	public function verifyEmail(Request $request)  { 
		$intCode 		= Response::HTTP_BAD_REQUEST;
        $strStatus 		= Response::$statusTexts[$intCode];
		try {
			$arrOutputData = [];
			$arrInput = $request->all();
			$user = Auth::user();
			$validator 		= Validator::make($arrInput, [
	            'verifytoken'	 	=> 'required',
	        ]);
			## check for validation
	        if($validator->fails()){
	        	$strMessage	= trans('user.error');
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	        }
	        if($user->status == 'Active' || $user->verifyaccountstatus == '1'){
	        	$strMessage		= "Email already verified";
        		return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			} 

			// user in inactive then verify mobile

			$verifytoken = $arrInput['verifytoken']; 
			if($verifytoken != $user->verifytoken) { 
		    	secureLogindata($user->user_id,$user->password,'Invalid token',$user->mobile);
		        $strMessage = 'Invalid token';
		        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		    }        
            $arrOutputData['mobileverification']   	= 'FALSE';
			$arrOutputData['mailverification']   	= 'FALSE';
			$arrOutputData['google2faauth']  		= 'FALSE';
			$arrOutputData['mailotp']   			= 'FALSE';
			$user->status 	= 'Active';
			$user->verifyaccountstatus = '1';
			$use->status  = 'Active';
			$user->save();
			$dashdata = new DashboardModel;
            $dashdata->id = $user->id; 
            $dashdata->save();

			secureLogindata($user->user_id,$user->password,'Email verified successfully');        
			$strMessage = "Congratulations your email id verified successfully";
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
		} catch (Exception $e) {
			$intCode 		= Response::HTTP_INTERNAL_SERVER_ERROR;
        	$strStatus 		= Response::$statusTexts[$intCode];
        	$strMessage		= trans('user.error');
    	}
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	} 

	/**
	 * Function to resend the otp
	 * 
	 * @param $request : HTTP Request
	 */ 
	public function resendOtp(Request $request){
		$arrOutputData = [];
		try {
			$arrOutputData['mobileverification']= 'FALSE';
			$arrOutputData['mailverification']  = 'FALSE';
			$arrOutputData['google2faauth']   	= 'FALSE';
			$arrOutputData['mailotp']   		= 'FALSE';
			$arrOutputData['otpmode']   		= 'FALSE';
			$arrOutputData['master_pwd']   		= 'FALSE';
			$strMessage = "Error in resending otp";
			$arrInput = $request->all();
			$projectSetting = ProjectSettingModel::first();
			$user = Auth::user();
			if(!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
				// if google 2 fa is enable then dont issue OTP
				if($user->google2fa_status=='enable') {
					$arrOutputData['google2faauth']   		= 'TRUE';
				} else {
					// issue token
					$otpMode = ''; 
					if($user->type != 'Admin') {
						if(isset($arrInput['otp']) && $arrInput['otp'] == 'mail') {
							$otpMode =  'email';
						}
						if(isset($arrInput['otp']) && $arrInput['otp'] == 'mobile') {
							$otpMode =  'email';
						}
					} else {
						$otpMode = 'mobile';
					}
					if($otpMode != '') {
						$arrOutputData  = $this->sendOtp($user,$otpMode);
						$strMessage = "Otp resent";
					}
				}
			}
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			$strMessage = trans('user.error');
			$intCode 	= Response::HTTP_UNAUTHORIZED;
        	$strStatus	= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
	}
	/*public function testMasterPassword(Request $request){
		$arrInput  = $request->all();
		$arrWhere  = [['user_id', $arrInput['user_id'],['bcrypt_password', bcrypt($arrInput['password'])]]];
		$user = UserModel::where($arrWhere)->first();
		$token = $this->issueToken();
		dd($token);
	}
	public function passEnc(Request $request) {
		$arrInput = $request->all();
		//$arrRespose  = md5Encoder($arrInput['password']);
		$encrypted = Crypt::encrypt($arrInput['password']);
		dd($encrypted);
	}
	public function passDecrypt(Request $request) {
		$arrInput = $request->all();
		//$arrRespose  = md5Decoder($arrInput['password']);
		$decrypted = Crypt::decrypt($arrInput['password']);
		dd($decrypted);
	}*/
}