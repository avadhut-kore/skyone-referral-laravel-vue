<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use Exception;
use Validator;
use Flash;
use Auth;
use Hash;
// use model here
use App\User as UserModel;
use App\Models\ProjectSetting as ProjectSettingModel;
// use trait here
use App\Traits\User;
use App\Http\Controllers\LoginController;


class UserController extends Controller
{
	use User;
   
    public $arrOutputData  = [];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->user = $u;
    }
    
    public function getUsers(){
        $users = $this->user->get();
        
        $users_data = [];
        foreach($users as $key => $user) {
            array_push($users_data,[
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'city' => $user->city,
                'mobile_no' => $user->mobile_no,
                'profession' => $user->profession
            ]);
        }

        if($users->count() == 0) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'Users not found',
                'data' => [],
            ],200);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'Users found',
            'data' => $users_data
        ],200);
    }

    public function register(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email|unique:users',
            'city' => 'required',
            'mobile_no' => 'required|numeric',
            'profession' => 'required',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'validation errors occured',
                'errors' => $validator->errors(),
                'data' => []
            ],200);
        }
        else {
            $this->user->first_name = $request->Input('first_name');
            $this->user->last_name = $request->Input('last_name');
            $this->user->email = $request->Input('email');
            $this->user->city = $request->Input('city');
            $this->user->mobile_no = $request->Input('mobile_no');
            $this->user->profession = $request->Input('profession');
            $this->user->password = Hash::make($request->Input('password'));
            
            if(!$this->user->save()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'msg' => 'Error occurred while registration..!please try again',
                    'data' => []
                ],200);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'msg' => 'User registered successfully',
                'data' => [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'email' => $this->user->email,
                    'city' => $this->user->city,
                    'mobile_no' => $this->user->mobile_no,
                    'profession' => $this->user->profession
                ]
            ],200);
        }   
    }

    public function edit($id) {
        $user = $this->user->where('id',$id)->first();

        if($user->count() == 0) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'User not found',
                'data' => [],
            ],200);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'User found',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'city' => $user->city,
                'mobile_no' => $user->mobile_no,
                'profession' => $user->profession
            ]
        ],200);
    }

    public function update(Request $request) {
        $id = $request->Input('id');
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email|unique:users,id,'.$id,
            'city' => 'required',
            'mobile_no' => 'required|numeric|unique:users,id,'.$id,
            'profession' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'validation errors occured',
                'errors' => $validator->errors(),
                'data' => []
            ],200);
        }
        else {
            
            $arr = [
                'first_name' => $request->Input('first_name'),
                'last_name' => $request->Input('last_name'),
                'email' => $request->Input('email'),
                'city' => $request->Input('city'),
                'mobile_no' => $request->Input('mobile_no'),
                'profession' => $request->Input('profession')
            ];

            if(!$this->user->where('id',$id)->update($arr)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'msg' => 'Error occurred while updating user..!please try again',
                    'data' => [],
                ],200);
            }

            $user = $this->user->where('id',$id)->first();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'msg' => 'User updated successfully',
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'city' => $user->city,
                    'mobile_no' => $user->mobile_no,
                    'profession' => $user->profession
                ]
            ],200);
        }
    }

    // Tempararly commented...will uncomment when it is required
    // public function destroy(Request $request) {
        
    //  $id = $request->Input('id');
        
    //  if(!$this->user->where('id',$id)->delete()) {
    //      return response()->json([
    //          'status' => 'error',
    //          'code' => 404,
    //          'msg' => 'Error occurred while deleting user..!please try again',
    //          'data' => [],
    //      ],200);
    //  }

    //  return response()->json([
    //      'status' => 'success',
    //      'code' => 200,
    //      'msg' => 'User deleted successfully'
    //  ],200);
    // }
    
    public function getUserDetails($id) {

        $user = $this->user->where('id',$id)->first();
        
        if($user->count() == 0) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'msg' => 'User not found',
                'data' => [],
            ],200);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'msg' => 'Data found',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'city' => $user->city,
                'mobile_no' => $user->mobile_no,
                'profession' => $user->profession
            ]
        ],200);
    }



    public function saveUser(Request $request) {
		try {
            $arrInput = $request->all();
           // check account reg time day
            //['account_no'=>$arrInput['account_no'], - removed account no @ 19-03-2019
            $arrValidationInput = UserModel::getValidationRulesMessages();
		    $validator = Validator::make($arrInput, $arrValidationInput['arrRules'],$arrValidationInput['arrMessage']);
		    // validation fails then return error
		    if ($validator->fails()) {
                return setValidationErrorMessage($validator);
		    }
            
            $arrOutput =  $this->userRegister($arrInput);
            return $arrOutput;
                
		} catch (PDOException $e) {
            dd($e);
            $intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus     		= Response::$statusTexts[$intCode];
            $strMessage 		= trans('user.error');
            return sendResponse($intCode, $strStatus, $strMessage,[]);
		}
        return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
	}


	/**
     * Function to update the user
     * 
     * @param $request : HTTP Request object
     */ 
    public function updateUser(Request $request) {
        $arrOutput = [];
        try {
            // check validation
            $arrInput  = $request->all();
            $intId     = Auth::user()->id;
            $arrValidationInput = UserModel::getValidationRulesMessages($intId);
            $arrRules = $arrValidationInput['arrRules'];
            unset($arrRules['password']);
            //unset($arrRules['pin_number']);

            dd($arrRules);
            $validator = Validator::make($arrInput, $arrRules,$arrValidationInput['arrMessage']);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            $arrSettingWhere  = [['status','=',1]];
            $registationPlan = ProjectSettingModel::where($arrSettingWhere)->pluck('registation_plan')->first();
            // if binary plan is on 
            if($registationPlan == 'binary' && ( isset($arrInput['position']) && $arrInput['position'] != 0 ) ){
            }else if($registationPlan == 'level'){  // if level plan on
                $arrOutput =  $this->levelPlan($arrInput);
                return $arrOutput;
            }
        } catch (Exception $e) {
            $intCode       = $e->getCode();
            $strMessage    = trans('user.error');
            $strStatus     = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,[]);
        }
    }

    /**
     * Function to get the user details
     * 
     * @param $request : HTTP Request object
     *  
     */ 
    public function userExists(Request $request) {
        //dd('sss');
        $arrOutputData = [];
        try {
            $arrInput = $request->all();
            $arrRules = ['user_id' => 'required'];
            $arrMessage = ['user_id.required' => 'User Id is required'];
            $validator = Validator::make($arrInput, $arrRules, $arrMessage);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            $arrWhere   = [];
            $arrWhere[] = ['user_id', $arrInput['user_id']];
           /* if(!isset($arrInput['admin']))
                $arrWhere[] = ['type', '!=' ,'Admin'];*/
            if(isset($arrInput['request_type']) && ($arrInput['request_type'] == 'register'))
                $arrWhere[] = ['status','Active'];
            $arrSelect  = ['id','fullname','mobile','country','entry_time'];
            $arrOutputData = $this->getUserDetails($arrWhere, $arrSelect);
            if(!empty($arrOutputData) ) {
                $intCode       = Response::HTTP_OK;
                $strMessage    = "User available";
            } else {
                $intCode       = Response::HTTP_NOT_FOUND;
                $strMessage    = "User not available";
            }
            $strStatus     = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        } catch (Exception $e) {
          
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
            $strStatus    = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }

    /**
     * Function to change the user password
     * 
     * @param $request : HTTP Request object
     * 
     */ 
    public function changePassword(Request $request){
        $arrOutputData = [];
        try {
            $arrInput = $request->all();
            $arrRules = [
                    'new_password' => 'required',
                    'current_pwd' => 'required',
                    'password_confirmation'=>'required',
                    //'otp' => 'required|min:6|max:6'
            ];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            $arrOutputData = $this->updateUserPassword($arrInput);
            return $arrOutputData;
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
            $arrWhere   = [['users.id', Auth::user()->id]];
            $arrSelect  = ['users.user_id', 'users.email', 'users.status', 'users.id', 'users.mobile_no','users.first_name','users.last_name','cn.country','cn.iso_code','users.btc_address','users.eth_address','users.type','users.bank_name','users.branch_name','users.holder_name','users.ifsc_code','users.account_no', 'users.state', 'users.city','users.paytm_no','users.tez_no','users.phonepe_no','users.mobikwik_no','users.flag']; 
            $arrOutputData = $this->getUserDetails($arrWhere, $arrSelect,1);
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
            $intCode        = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage     = trans('admin.defaultexceptionmessage');
            $strStatus      = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }

    /** 
     * change password send otp 
     * 
     * @param $request : HTTP Request object
     */ 
    public function sendChangePasswordOtp(Request $request) {
        $arrOutputData = [];
        $arrInput = $request->all();
        try{
            $arrMessage = array(
                'new_password.regex'     =>'Pasword contains first character letter, contains atleast 1 capital letter,combination of alphabets,numbers and special character i.e. ! @ # $ *',

            );
            $arrRules = array(
            'current_pwd'           => 'required',
            'new_password'          =>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/|min:6|max:30',
            'password_confirmation' => 'required|same:new_password'
            );

            $validator = Validator::make($arrInput, $arrRules,$arrMessage);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            $user = Auth::user();
            if(md5(trim($arrInput['current_pwd']))==$user->password)
            { 
                $intCode       = Response::HTTP_OK;
                $request->merge(['otp_for' => 'change password']);
                return $this->sendOtpToUser($request);
            }else{
                $strMessage = 'Current password not matched';
                $intCode    = Response::HTTP_BAD_REQUEST;
            }
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('user.error');
        }
        $strStatus     = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }


    /**
     * 
     * Function to send the OTP for change address & password
     * 
     * @param $request : HTTP Request Object
     * 
     */ 
    public function sendOtpToUser(Request $request)
    {
        $arrOutputData = [];
        $intCode       = Response::HTTP_OK;
        try {
            $user = Auth::user();
            if($user->google2fa_status=='enable'){ 
                $arrOutputData=array();
                $arrOutputData['remember_token']   = $user->remember_token;
                $arrOutputData['otpmode']   = 'FALSE';
                $arrOutputData['google2faauth']   = 'TRUE';
                $strMessage = 'Please enter your 2FA verification code';
            }else if($user->google2fa_status=='disable'){
                secureLogindata($user->user_id,$user->password,'Sent Otp on mail');
                $strOtpFor = (isset($arrInput['otp_for'])) ? $arrInput['otp_for'] : "";
                $sendopt= new LoginController;
                $sendopt->sendOtp($user, 'email',$strOtpFor);
                $strMessage    = trans('user.otpsent');
                $arrOutputData['otpmode']   = 'TRUE';
                $arrOutputData['google2faauth']   = 'FALSE';
            }
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }


    /**
     * Function to fetch the server information 
     * 
     * @param $request : HTTP Request
     * 
     */
    public function getServerInformation(Request $request) {
        $arrOutputData = [];
        try {
                $serverTime  = \Carbon\Carbon::now();
                $arrOutputData['server_time'] = $serverTime->toDateTimeString();                
                $currentTime = getTimeZoneByIP($_SERVER['REMOTE_ADDR']);
                $arrOutputData['current_time'] =$currentTime;
                $arrOutputData['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $intCode       = Response::HTTP_OK;
                $strMessage    = trans('user.recordfound');
                $strStatus     = Response::$statusTexts[$intCode];
            } catch (Exception $e) {
            $intCode        = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage     = trans('admin.defaultexceptionmessage');
        }
        $strStatus      = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }

    /**
     * Function to send the registration Otp
     * 
     * @param $request : HTTP Request Object
     * 
     */
    public function sendRegistrationOtp(Request $request){
        $arrOutputData  = [];
        try {
            $arrInput  = $request->all();
            $arrRules  = ['user_id' => 'required','country' => 'required','mobile' => 'required'];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            return $this->sendRegisterOtp($arrInput);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    } 

    /**
     * Function to verify the registration Otp
     * 
     * @param $request : HTTP Request Object
     * 
     */
    public function verifyRegistrationOtp(Request $request){
        $arrOutputData  = [];
        try {
            $arrInput  = $request->all();
            $arrRules  = ['user_id' => 'required','otp' => 'required|min:6|max:6','mobile' => 'required'];
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) {
                return setValidationErrorMessage($validator);
            }
            return $this->verifyRegisterOtp($arrInput);
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    } 
    
    /**
     * Function to update the user data
     * 
     * @param $request : HTTP Request
     * 
     */
    public function updateBankDetail(Request $request){
        $arrOutputData = [];
        try {
            $user = Auth::user();
            $arrInput = $request->all();
            //dd($arrInput);
            $arrKeys  = ['account_no','holder_name','bank_name','branch_name','ifsc_code','paytm_no','tez_no','phonepe_no','flag','city'];
            foreach($arrKeys as $key){
                if(array_key_exists($key, $arrInput))
                    $user->$key = $arrInput[$key];
            }
            $user->save(); 
            $intCode = Response::HTTP_OK;
            $strMessage = "Bank detail updated successfully";
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('admin.defaultexceptionmessage');
        }
        $strStatus  = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }

}