<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Flash;
use Validator;
use DB;
use Crypt;
use Auth;
use Illuminate\Http\Response as Response;
// use model here
use App\User as UserModel;
// use trait here
use App\Traits\User;

class UserController extends Controller {
    use User;
    public $arrOutputData = [];
    /**
     * Show the all user listing
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllUser( Request $request)
    {
        $arrOutputData = [];
        try {
            $arrInput = $request->all();
            $intCode       = Response::HTTP_OK;
            $strMessage    = trans('user.recordfound');
            $strStatus    = Response::$statusTexts[$intCode];
            $arrOutputData = $this->getAllUsers($arrInput );
            return $arrOutputData;
        }catch (Exception $e) {

          
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
            $strStatus    = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
        //return view('admin.user.all-user', $arrOutputData);
    }


    /**
     * Function to return or perform the different types of action based on slug
     * 
     */ 
    public function userSwitchFunction(Request $request, $intId = NULL, $slug = NULL) {
        if( !empty($slug)) {
            switch ($slug) {
                case 'edit':    $arrWhere   = [['id', $intId]];
                                return $this->getUserData($request, $intId);
                                
                                break;
                case 'update':  return $this->updateUser($request); 
                                break;   
                default:
                                break;
            }
        }
        return redirect()->back();
    }

    /**
     * Function to get the user details
     *  
     */ 
    public function getUserPassword(Request $request) {
        $arrOutputData = [];
        try {
            $arrInput= $request->all();
            $arrWhere   = [];
            $arrRules = ['user_id' => 'required'];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            if(isset($arrInput['user_id']) && !empty($arrInput['user_id']))
                $arrWhere[]   = ['user_id', $arrInput['user_id']];
            $arrWhere[] = ['type','!=', 'Admin'];
            $arrOutputData = $this->getUserDetails($arrWhere);
            if(!empty($arrOutputData) ) {
                $intCode       = Response::HTTP_OK;
                $strMessage    = trans('user.recordfound');
                $strStatus     = Response::$statusTexts[$intCode];
            } else {
                $intCode       = Response::HTTP_BAD_REQUEST;
                $strMessage    = trans('user.recordnotfound');
                $strStatus     = Response::$statusTexts[$intCode];
            }
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
            $strStatus    = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }

    /**
     * Function to get the user details
     *  
     */ 
    public function getUserData(Request $request, $intId = NULL) {
        $arrOutputData = [];
        try {
            $arrWhere   = [];
            if(!empty($intId))
                $arrWhere   = [['id', $intId]];
            $arrOutputData = $this->getUserDetails($arrWhere);
            if(!empty($arrOutputData) ) {
                $intCode       = Response::HTTP_OK;
                $strMessage    = trans('user.recordfound');
                $strStatus     = Response::$statusTexts[$intCode];
            } else {
                $intCode       = Response::HTTP_BAD_REQUEST;
                $strMessage    = trans('user.recordnotfound');
                $strStatus     = Response::$statusTexts[$intCode];
            }
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
            $strStatus    = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }

     /**
     * Function to return the data for profile
     * 
     */ 
    public function profile() {
        $arrOutputData = [];
        try {
            $arrWhere   = [];
            $arrWhere   = [['user_id', Auth::user()->user_id]];
            $arrSelect  = ['user_id', 'email', 'status', 'id', 'mobile_no','first_name','last_name','type']; 
            $arrOutputData = $this->getUserDetails($arrWhere, $arrSelect);
            if(!empty($arrOutputData) ) {
                $intCode       = Response::HTTP_OK;
                $strMessage    = trans('user.recordfound');
                $strStatus     = Response::$statusTexts[$intCode];
            } else {
                $intCode       = Response::HTTP_NOT_FOUND;
                $strMessage    = trans('user.recordnotfound');
                $strStatus     = Response::$statusTexts[$intCode];
            }
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
            $strStatus    = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }
    /**
     * update user password with new password
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserPassword(Request $request) {
        $arrOutputData  = [];
        try {
            $arrInput = $request->all();
            $arrRules = array('id' => 'required','password' => 'required|min:8|max:50');
            $arrMessages = array(
                'password.regex' => 'Password min 8  character  '
            );
            // run the validation rules on the inputs form the form
            $validator = Validator::make($arrInput, $arrRules, $arrMessages);
            // if the validator fails, redirect back to the form
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            } else {
                $user = UserModel::where('id',$arrInput['id'])->first();
                if(empty($user)) {
                    $intCode    = Response::HTTP_NOT_FOUND;
                    $strMessage = trans('user.usernotexists');
                } else {
                    //$updatePass = User::where('id',$arrInput['id'])->update($arrUpdate);
                    $md5Password            = md5($arrInput['password']);
                    $user->password         = $md5Password;
                    $user->bcrypt_password  = bcrypt($arrInput['password']);
                    $user->encryptpass      = Crypt::encrypt($arrInput['password']);
                    $user->tr_passwd        = $md5Password;
                    $user->save();
                    $arrSendMail = [
                        'to_mail'  => $user->email,
                        'pagename' => 'emails.admin-emails.updateuserpassreply',
                        'msg'      => 'Password has been updated by Administrator. Please contact for any query',
                        'subject'  => 'Password update alert'
                    ];
                    //sendMail($arrSendMail,$arrSendMail['to_mail'],$arrSendMail['subject']);
                    $strMessage = "Password updated successfully";
                    $intCode = Response::HTTP_OK;   
                   
                }
            }
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus    = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        
    }

       /**
     * update 2fa status of users
     *
     * @return \Illuminate\Http\Response
     */
    public function update2faUserStatus(Request $request){
        $arrOutputData  = [];
        
        try {
            $arrInput = $request->all();
            $arrRules = array(
                'id'           => 'required',
            );        
            $validator = Validator::make($arrInput, $arrRules);        
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            $user = UserModel::where('id', $arrInput['id'])->first();
            if(empty($user)) {
                $intCode = Response::HTTP_NOT_FOUND;
                $strMessage = trans('user.usernotexists');
            } else {
                $status = ($user->google2fa_status == "enable") ? 'disable' : $user->google2fa_status;
                $user->google2fa_status = $status;
                $user->save();
                $intCode = Response::HTTP_OK;
                $strMessage = "Record updated successfully";
            }
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus    = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }


     /**
     * check user excited or not by passing parameter
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserExist(Request $request) {
        //dd('hi');
        $arrInput  =  $request->all();
        //validate the info, create rules for the inputs
        $rules = array(
            'user_id'    => 'required'
        );
        $validator = Validator::make($arrInput, $rules);
        if($validator->fails()) {
            $message = messageCreator($validator->errors());
            return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message,'');
        } else {
            //check wether user exist or not by user_id

            $checkUserExist = UserModel::where('status','Active')->where('user_id',$arrInput['user_id'])->first(); 
         // dd($checkUserExist);
            if(!empty($checkUserExist)) {               
                $arrObject['id']                = $checkUserExist->id;
                $arrObject['user_id']           = $checkUserExist->user_id;
                $arrObject['first_name']        = $checkUserExist->first_name;
                $arrObject['last_name']        = $checkUserExist->last_name;
                $arrObject['remember_token']    = $checkUserExist->remember_token;

                $intCode            = Response::HTTP_OK;
                $strStatus          = Response::$statusTexts[$intCode];
                $strMessage         = "OK";
                return sendResponse($intCode, $strStatus, $strMessage,$arrObject);
            } else {
                $intCode            = Response::HTTP_INTERNAL_SERVER_ERROR;
                $strStatus          = Response::$statusTexts[$intCode];
                $strMessage         = trans('admin.defaultexceptionmessage');
                return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
            }
        }
    }
    
}