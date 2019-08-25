<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use Exception;
use PDOException;
use DB;
use Validator;
use Config;
use URL;
use Crypt;
// use model here
use App\User as UserModel;
use App\Models\Resetpassword as ResetpasswordModel;
use App\Models\Activitynotification as ActivitynotificationModel;

class ForgotPasswordController extends Controller
{
	public $arrOutputData = [];
	/**
	 * get all navigation list by user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCountry() {
		try {
			$countries = CountryModel::get();
			if(count($countries) > 0 )
				$this->arrOutputData = $countries->toArray();
	
		    $intCode       		= Response::HTTP_OK;
            $strStatus     		= Response::$statusTexts[$intCode];
            $strMessage  		= "OK";
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
		} catch (Exception $e) {
			$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus     		= Response::$statusTexts[$intCode];
            $strMessage 		= trans('admin.defaultexceptionmessage');
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
		}
		
	}
	/**
	 * Function to send the reset passowrd link
	 * 
	 * @param $request : HTTP Request object
	 */ 
	public function sendResetPasswordLink(Request $request) {
		$intCode       		= Response::HTTP_NOT_FOUND;
        DB::beginTransaction();
        try {
			$arrInput 		= $request->all();
			$validator 		= Validator::make($arrInput, [
						        'user_id'	=> 'required',
						    ]);	
			//check for validation
	        if($validator->fails()){
	        	return setValidationErrorMessage($validator);	
	        }
	        $arrWhere = [['user_id', $arrInput['user_id']]];
	        $user = UserModel::select('email', 'id', 'user_id','mobile')->where($arrWhere)->first();
	        if(empty($user)){
	        	$strMessage = "User is not registered with this username";
	        } else {
	        	$userTokens = $user->tokens;
		        if(!empty($userTokens) && count($userTokens) > 0 ) {
		        	foreach($userTokens as $token) {
					    $token->revoke();   
					}
				}
	        	$arrResetPassword=array();
				$arrResetPassword['reset_password_token']=md5(uniqid(rand(), true));
				$arrResetPassword['id']=$user->id;
				$arrResetPassword['request_ip_address']=$request->ip();
				$insertresetDta=ResetpasswordModel::create($arrResetPassword);
				$actdata=array();     
				$actdata['id']=$user->id;
				$actdata['message']='Reset password link sent successfully to your registered email id';
				$actdata['status']=1;
				$actDta=ActivitynotificationModel::create($actdata);
				//$username=$user->email; 
				$reset_token=$arrResetPassword['reset_password_token']; 
				$url = URL::to('/');
				$resetLink = $url.'/user#/resetpassword?resettoken='.$reset_token;
				$arrEmailData  = [];
				$arrEmailData['email'] 		= $user->email;
				$arrEmailData['template']	= 'email.forgot-password';
				$arrEmailData['fullname']	= $user->fullname;
				$arrEmailData['subject']	= "RESET PASSWORD";
				$arrEmailData['reset_token']= $resetLink;
				$arrEmailData['user_id']	= $user->id;
				$strSmsMessage = "Your reset password link: ".$resetLink;
				$projectName = Config::get('constants.settings.projectname');
	            $msg = "Hello,\n".$strSmsMessage.".\nRegards,\n".$projectName;
	            sendMessage($user, $msg);

				$mail =sendEmail($arrEmailData);   
				$mail =true;
				$intCode       		= Response::HTTP_OK;
				$strMessage 		= 'Reset password link sent successfully to your registered mobile no.';
				if($mail){
					$intCode       		= Response::HTTP_OK;
					$strMessage 		= 'Reset password link sent successfully to your registered email id';
				}else{
					$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
					$strMessage 		= trans('admin.defaultexceptionmessage');
				}
	        }
	    }catch (PDOException $e) {
	    	DB::rollBack();
	    	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage 		= trans('admin.defaultexceptionmessage');
		}
		DB::commit();

		$strStatus     		= Response::$statusTexts[$intCode];
		return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData); 
	}
	
	/**
	 * Function to reset the passowrd
	 *  
	 * @param $requet : HTTP Request object
	 */ 
	public function resetPasswordRandom(Request $request) {

        $intCode = Response::HTTP_NOT_FOUND;
        $strStatus = Response::$statusTexts[$intCode];
        $strMessage = "Something went wrong";
        $arrOutputData = [];
        DB::beginTransaction();
        try {
            $arrInput = $request->all();
            $validator = Validator::make($arrInput, [
                        'user_id' => 'required',
            ]);
            //check for validation
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            $arrWhere = [['user_id', $arrInput['user_id']]];
            $user = UserModel::select('email', 'id', 'user_id','mobile')->where($arrWhere)->first();
            if (empty($user)) {
                $strMessage = "User is not registered with this user";
            } else {
                $six_digit_random_number = mt_rand(100000, 999999);
                $bcryptpass =  bcrypt($six_digit_random_number);
                $encryptpass = encrypt($six_digit_random_number);
				$password 	 = md5($six_digit_random_number);
                UserModel::where('id',$user->id)->update(['password'=>$password,'bcrypt_password'=>$bcryptpass,'encryptpass'=>$encryptpass]);
				
                // $arrEmailData = [];
                // $arrEmailData['email'] = $user->email;
                // $arrEmailData['template'] = 'email.password-reseted';
                // $arrEmailData['fullname'] = $user->fullname;
                // $arrEmailData['subject'] = "RESET PASSWORD";
                // $arrEmailData['reset_pass'] = $six_digit_random_number;
                // $arrEmailData['user_id'] = $user->id;
                // $mail = sendEmail($arrEmailData);
                $domainpath = Config::get('constants.settings.domainpath');
                $projectName = Config::get('constants.settings.projectname');
                $message = 'Your password reset successfully.\nYour new Password for User Id : '.$user->user_id.' is  '. $six_digit_random_number ;
                $message.=".\nRegards,\n".$projectName."\n".$domainpath;                
                sendSMS($user->mobile, $message);
                $mail = true;

                if ($mail) {
                    $intCode = Response::HTTP_OK;
                    $strMessage = 'Password Reset Successfully! Password sent to your registered mobile number';
                } else {
                    $intCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $strMessage = "Something went wrong";
                }
            }
        } catch (PDOException $e) {
            DB::rollBack();
            return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
        } catch (Exception $e) {
            $intCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage = "Something went wrong";
        }
        DB::commit();

        $strStatus = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage, $this->arrOutputData);
    }

	 /**
     * Function to reset the passowrd
     *  
     * @param $requet : HTTP Request object
     */
    public function resetPasswordByUser(Request $request) {
        $linkexpire = Config::get('constants.settings.linkexpire');
        $intCode = Response::HTTP_BAD_REQUEST;
        DB::beginTransaction();

        try {
            $arrInput = $request->all();
            $arrMesssage = array(
                'resettoken' => 'Reset token required',
            );
            $arrRules = [
                'resettoken' => 'required',
                'password' => 'required|confirmed|min:6|max:15'
            ];
            $validator = Validator::make($arrInput, $arrRules, $arrMesssage);

            //check for validation
            // if ($validator->fails()) {
            //     return setValidationErrorMessage($validator);
            // }
            $resetPassword = ResetpasswordModel::where([['reset_password_token', '=', $arrInput['resettoken']]])->first();
            // print_r($resetPassword);
            // exit();
            if (empty($resetPassword)) {
                $strMessage = "Invalid reset token";
            } else {
                if ($resetPassword->otp_status == 1) {
                    $strMessage = 'Link already used';
                } else {
                    $datetime = now();
                    $userId = $resetPassword->id;
                    $entry_time = $resetPassword->entry_time;
                    $current_time = now();
                    $hourdiff = round((strtotime($current_time) - strtotime($entry_time)) / 3600, 1);
                    $updateData = array();
                    $updateData['reset_password_token'] = $arrInput['resettoken'];
                    $updateData['otp_status'] = 1;
                    ResetpasswordModel::where('id', $userId)->update($updateData);

                    if (round($hourdiff) == $linkexpire && round($hourdiff) >= $linkexpire) {
                        $strMessage = 'Link Expired';
                    } else {
                        $arrUserWhere = [['id', $userId]];
                        $user = UserModel::where($arrUserWhere)->first();
                        if (empty($user)) {
                            $strMessage = "User not found";
                            $intCode = Response::HTTP_NOT_FOUND;
                        } else {
                            $user->password 	 	= md5($arrInput['password']);
							$user->bcrypt_password  = bcrypt($arrInput['password']);
							$user->encryptpass = Crypt::encrypt($arrInput['password']);
                            $user->save();
                            $resetPassword->request_ip_address = $_SERVER['REMOTE_ADDR'];
                            $resetPassword->out_time = $datetime;
                            $resetPassword->save();

                            $arrEmailData = [];
                            $arrEmailData['email'] = $user->email;
                            $arrEmailData['template'] = 'email.password-reseted';
                            $arrEmailData['fullname'] = $user->fullname;
                            $arrEmailData['subject'] = "Password reseted";
                            //$arrEmailData['reset_token']= $reset_token;
                            $arrEmailData['user_id'] = $user->id;
                            $mail = sendEmail($arrEmailData);
                            $actdata = array();
                            $actdata['id'] = $user->id;
                            $actdata['message'] = 'Password reset successfully';
                            $actdata['status'] = 1;
                            $actDta = ActivitynotificationModel::create($actdata);

                            $intCode = Response::HTTP_OK;
                            $strMessage = "Password reset successfully";
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            DB::rollBack();
            return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
        } catch (Exception $e) {

            $intCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage = "Something went wrong";
        }
        DB::commit();

        $strStatus = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage, $this->arrOutputData);
    }
}