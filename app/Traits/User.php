<?php
namespace App\Traits;
use Exception;
use PDOException;
use DB;
use Auth;
use Illuminate\Http\Response as Response;
use Hash;
use Crypt;
use Config;
// use model
use App\User as UserModel;
use App\Models\Dashboard as DashboardModel;
use App\Models\Otp as OtpModel;
use App\Models\ProjectSetting as ProjectSettingModel;

trait User {
    
    public $arrOutputData = [];
    /**	
     * Function to save the user according to level plan
     * 
     * @param $arrInput : Array of user input
     * 
     */ 
   	public function userRegister($arrInput){
   		$arrOutputData = [];
   		$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
		$strStatus     		= Response::$statusTexts[$intCode];
		$strMessage 		= trans('user.error');
		DB::beginTransaction();
   		try {
   			$randomToken   = md5(uniqid(rand(), true));
   			//dd($arrInput);
			// save new user
			$newUser 	= new UserModel;
			$newUser->user_id 		= $arrInput['email'];
			$newUser->first_name 	= $arrInput['first_name'];
            $newUser->last_name 	= $arrInput['last_name'];
            $newUser->email 		= $arrInput['email'];
            $newUser->city 			= $arrInput['city'];
            $newUser->mobile_no 	= $arrInput['mobile_no'];
            $newUser->profession 	= $arrInput['profession'];
			$newUser->password 	 	= md5($arrInput['password']);
			$newUser->bcrypt_password  = bcrypt($arrInput['password']);
			$newUser->encryptpass 	= Crypt::encrypt($arrInput['password']);
			$newUser->remember_token = $randomToken;
			$newUser->save();
			
			DB::commit();
			$intCode    = (!isset($arrInput['id']) || empty($arrInput['id'])) ? Response::HTTP_CREATED : Response::HTTP_OK;
        	$strStatus  = Response::$statusTexts[$intCode];
        	$strMessage = (!isset($arrInput['id']) || empty($arrInput['id'])) ? 'User registered successfully' : 'User updated successfully';
        	
   		}catch (PDOException $e) {
   			DB::rollBack();
   			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			DB::rollBack();
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		}
		$strStatus     		= Response::$statusTexts[$intCode];
		return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	}

	

	/**
	 * Function list the all the users 
	 * 
	 * @param $request : HTTP Request object
	 */ 
	public function getAllUsers($arrInput = []) {
		$arrOutputData = [];
		try {

		    $query = UserModel::select('tbl_users.id','tbl_users.user_id','tbl_users.fullname','tbl_users.email','tbl_users.entry_time','tbl_users.status','tbl_users.ref_user_id','tbl_users.type','tbl_users.mobile','tbl_users.google2fa_status','tbl_users.btc_address','tbl_users.position','tbl_users.l_c_count','tbl_users.r_c_count','tbl_users.l_bv','tbl_users.r_bv','tbl_users.virtual_parent_id','tbl_users.remember_token','tbl_users.google2fa_status','tbl_users.paytm_no','tbl_users.tez_no')
		          	->where('tbl_users.type','!=','Admin');

		    if(isset($arrInput['id'])){
			    //$query = $query->where('tbl_users.id',$arrInput['id']);
			    $query = $query->where('tbl_users.user_id',$arrInput['id']);
			}
			 if(isset($arrInput['sponser_id'])){
			    //$query = $query->where('tbl_users.id',$arrInput['id']);
			    $query = $query->where('tbl_users.user_id',$arrInput['sponser_id']);//->get();
			   // dd($query);
			}
			if(isset($arrInput['frm_date']) && isset($arrInput['to_date'])) {
			    $arrInput['frm_date'] = date('Y-m-d',strtotime($arrInput['frm_date']));
			    $arrInput['to_date'] = date('Y-m-d',strtotime($arrInput['to_date']));
			    $query = $query->whereBetween(DB::raw("DATE_FORMAT(tbl_users.entry_time,'%Y-%m-%d')"),[$arrInput['frm_date'], $arrInput['to_date']]);
			}
			if(isset($arrInput['status']) && !empty($arrInput['status']) ) {
			    $query = $query->where('tbl_users.status',$arrInput['status']);
			}
			if(isset($arrInput['search']['value']) && !empty($arrInput['search']['value']) ){
			    $search = $arrInput['search']['value'];
			    $arrOrWhereSearchFields = ['tbl_users.user_id']; //,'cn.country','cn.iso_code'
			    $query  = setSearchFilter($query, 'tbl_users', $search, $arrOrWhereSearchFields);
			}
			$query        	= $query->orderBy('tbl_users.id','desc');
			$totalRecord    = $query->count();
			$arrOutputData  = setPagination($query, $arrInput);
			$intCode       = (!isset($arrOutputData['recordsTotal'])) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;
            $strMessage    = (!isset($arrOutputData['recordsTotal'])) ? trans('user.recordnotfound') : trans('user.recordfound');
			$strStatus     = Response::$statusTexts[$intCode];
        } catch (Exception $e) {
        	$intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('user.error');
            $strStatus     = Response::$statusTexts[$intCode];
        }
        $strStatus     = Response::$statusTexts[$intCode];
    	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	}

	/**
	 *  Function to return the specific user information
	 * 
	 * @param $request 	: HTTP Request
	 * @param $arrWhere : array of conditions
	 */ 
	public function getUserDetails ($arrWhere = [], $arrSelect = [], $profileCall = NULL) {
		$arrOutputData = [];
		try {
			$user     = new UserModel; 
			if(count($arrSelect) > 0 ) 
				$user = $user->select($arrSelect);
			if(!empty($profileCall))
				$user = $user->leftjoin('tbl_users as tu2','tu2.id','=','tbl_users.ref_user_id')
                    ->leftjoin('country_new as cn','cn.iso_code','=','tbl_users.country');
			if(count($arrWhere) > 0 ) {
				$user = $user->where($arrWhere);
				$user = $user->orderBy('tbl_users.id','desc')->first();
				if(!empty($user)) {
					$serverTime  = \Carbon\Carbon::now();
					$user->server_time = $serverTime->toDateTimeString();
					$current_time = getTimeZoneByIP($_SERVER['REMOTE_ADDR']);
					$user->current_time =$current_time;
					$user->ip_address = $_SERVER['REMOTE_ADDR'];
					$user->joining_date =$user->entry_time;
					$arrOutputData = $user;
					
				}
			}
			return $arrOutputData;
		} catch (Exception $e) {
			return $arrOutputData;
		}
	}

	/**
	 * Function to update the password
	 * 
	 * @param $arrInput : Array of input
	 */ 
	public function updateUserPassword($arrInput) {
		$arrOutputData = [];
		$intCode       = Response::HTTP_BAD_REQUEST;
		$strStatus     = $strMessage  = Response::$statusTexts[$intCode];
		DB::beginTransaction();
		try {
			$user = Auth::user();
			if (Hash::check($arrInput['current_pwd'], $user->bcrypt_password)) {
				/*if($user->google2fa_status=='enable'){ 
                    $intResponse = verify2Fa($arrInput['otp']);
                }else 
                  	$intResponse   = verifyOtp($arrInput['otp']);*/
                $intResponse   =  200;
				if($intResponse == 200) {
					$user->password = md5($arrInput['new_password']);
					$user->bcrypt_password = bcrypt($arrInput['new_password']);
					$user->encryptpass = Crypt::encrypt($arrInput['new_password']);
					$user->save();
					$intCode       = Response::HTTP_OK;
		        	$strMessage    = "Password updated successfully";
		        	$strStatus     = Response::$statusTexts[$intCode];
		        } else {
		        	$intCode = $intResponse;
		        	$strMessage = ($intCode == 400) ? 'Bad Request' : trans('user.otpverified');
		        }
			} else{
				$intCode       = Response::HTTP_BAD_REQUEST;
	        	$strMessage    = "Old password does not match";
	        	$strStatus     = Response::$statusTexts[$intCode];
			}
		}catch (PDOException $e) {
   			DB::rollBack();
   			$intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
	        $strMessage    = trans('user.error');
	        $strStatus     = Response::$statusTexts[$intCode];
   		} catch (Exception $e) {
   			$intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
	        $strMessage    = trans('user.error');
	        $strStatus     = Response::$statusTexts[$intCode];
		}
		DB::commit();
		return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	}

	/**
	 * Function to send opt for registration
	 * 
	 * @param $arrInput : Array of input
	 * 
	 */
	public function sendRegisterOtp($arrInput){
		$arrOutputData = [];
		$intCode   	= Response::HTTP_INTERNAL_SERVER_ERROR;
        $strMessage = trans('user.error');
        $strStatus     = Response::$statusTexts[$intCode];
		DB::beginTransaction();
		try {
			$user = new UserModel;
			$user->mobile = $arrInput['mobile'];
			$random 	= rand(100000,999999); 
			//$random = 123456;
			$insertotp 					= array();
            $insertotp['mac_address']  	= $arrInput['user_id'];
            $insertotp['mobile_no']  	= $arrInput['mobile'];
            $insertotp['ip_address']	= trim($_SERVER['REMOTE_ADDR']);
            $insertotp['otp'] 			= md5($random);
            $insertotp['otp_status']	= 0;
            $insertotp['type'] 			= "mobile";
            $msg 		= $random.' is your verification code';
            sendMessage($user, $msg);
			$sendotp  = OtpModel::insert($insertotp); 
			$intCode  = Response::HTTP_OK;
			$strMessage = trans('user.otpsent') ;
		} catch (PDOException $e) {
			DB::rollBack();
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			DB::rollBack();
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		}
		DB::commit();
        $strStatus     = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	} 
	/**
	 * Function to send opt for registration
	 * 
	 * @param $arrInput : Array of input
	 * 
	 */
	public function verifyRegisterOtp($arrInput){
		$arrOutputData = [];
		try {
			$otp = $arrInput['otp'];
			$arrOtpWhere = [[[['mac_address','=',$arrInput['user_id']],['otp','=',md5($otp)]]]];
			$checotpstatus = OtpModel::where($arrOtpWhere)->orderBy('otp_id', 'desc')->first();
			// check otp status 1 - already used otp
			if(empty($checotpstatus)){
				$strMessage = 'Invalid otp';
				$intCode 	= Response::HTTP_BAD_REQUEST;
	        	$strStatus	= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}
			if($checotpstatus->otp_status == 1){
				$strMessage 	= trans('user.otpverified');
				$intCode 		= Response::HTTP_BAD_REQUEST;
	        	$strStatus		= Response::$statusTexts[$intCode];
	        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
			}
			$otpId = $checotpstatus->otp_id;
			$updateData=array();
			$updateData['otp_status']=1; //1 -verify otp
			$updateData['out_time']=date('Y-m-d H:i:s');
			$updateOtpSta =  OtpModel::where('otp_id', $otpId)->update($updateData);
			$intCode    = Response::HTTP_OK;
			$strStatus	= Response::$statusTexts[$intCode];
			$strMessage  	= "Otp Verified"; 
			return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		} catch (Exception $e) {
			$intCode 		= Response::HTTP_BAD_REQUEST;
    		$strStatus		= Response::$statusTexts[$intCode];
        	return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
		}
	}
}