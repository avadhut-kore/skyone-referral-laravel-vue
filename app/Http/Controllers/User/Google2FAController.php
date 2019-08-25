<?php

namespace App\Http\Controllers\User;

use Crypt;
use Google2FA;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use URL;
use Config;
use Validator;
use Illuminate\Http\Response as Response;
// use model here
use App\User as UserModel;
use App\Models\Activitynotification as ActivitynotificationModel;
use PragmaRX\Google2FAPhp\Exceptions\InvalidCharactersException;


class Google2FAController extends Controller
{
    use ValidatesRequests;
    public $arrOutputData = [];
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {     
        $strMessage      = trans('user.error');
        //$domainpath=Config::get('constants.settings.domainpath');
        $domainpath = URL::to('/');
        try {
            $user  = Auth::user();
           //$user =  UserModel::find(829);
            $email = $user->email;

            if(empty($user->google2fa_secret)){
                //generate new secret
                $secret = $this->generateSecret(); 
                $google2fa_secret = Crypt::encrypt($secret);
                $user->google2fa_secret = $google2fa_secret;
                
                $user->save();
            } else{
                $secret=Crypt::decrypt(Auth::user()->google2fa_secret);
            }
            $qrcodestring= "otpauth://totp/".$domainpath.":".$email."?secret=".$secret."&issuer=".$domainpath."";
            $arrOutputData = array();
            $arrOutputData['secret']=$secret;
            $arrOutputData['qrcodestring']=$qrcodestring;
            $arrOutputData['google2fa_status']= Auth::user()->google2fa_status;
            $intCode    = Response::HTTP_OK;
            $strMessage = Response::$statusTexts[$intCode];
            $strStatus  = "Ok"; 
        } catch (Exception $e) {
            $intCode        = Response::HTTP_BAD_REQUEST;
            $strStatus      = Response::$statusTexts[$intCode];
        }
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData); 
        
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();
        //make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        return view('2fa/disableTwoFactor');
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {  
        try{

        $randomBytes = random_bytes(10);        
        return Base32::encode($randomBytes);


       }catch(Exception $e){
        //dd($e);
       }

    }

       /**
     *
     * @param  App\Http\Requests\ValidateSecretRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postValidateToken(Request $request)
    {   
        $strMessage     = trans('user.error');
        $intCode        = Response::HTTP_BAD_REQUEST;
        $strStatus      = Response::$statusTexts[$intCode];
        $arrOutputData  = [];        
        //$domainpath=Config::get('constants.settings.domainpath');
        $domainpath = URL::to('/');
        try {
            $arrInput = $request->all();
            $arrRules = array(
                'googleotp'         => 'bail|required|digits:6',
                'factor_status'     =>'required'
            );
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) 
                return setValidationErrorMessage($validator);

            $user = Auth::user();
            $userId = $user->id;
            $google2fa_secret = $user->google2fa_secret;
            $key = $userId . ':' . $request->input('googleotp'); 
            //$encryptsecret=Crypt::encrypt($google2fa_secret); 
            //dd('hii');
            $secret = Crypt::decrypt($google2fa_secret);
            $verified= Google2FA::verifyKey($secret, $arrInput['googleotp']);

            if(!empty($verified)){
                $reusetoken=!Cache::has($key);
                if(empty($reusetoken)) {   
                    $strMessage     = 'Cannot reuse token';
                } else{ 
                    Cache::add($key, true, 4);
                    //$checklogdin=Auth::loginUsingId($userId);
                    $user->factor_status = $arrInput['factor_status'];
                    $user->google2fa_status = $arrInput['factor_status'];
                    $user->save(); 
                    $intCode      = Response::HTTP_OK;
                    $strStatus    = Response::$statusTexts[$intCode];
                    $strMessage   = ($arrInput['factor_status']=='enable') ?  'Your 2FA Is Enabled Successfully Done' : 'Your 2FA Is Disabled Successfully Done';
                }  
            }else {
                if(empty($verified)){
                    $strMessage = 'Invalid otp';
                }
            }   
        } catch(InvalidCharactersException $e){
            
        } catch (Exception $e) {
            $intCode        = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus      = Response::$statusTexts[$intCode];
        }
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);           
    }

     /**
     *
     * @param  App\Http\Requests\ValidateSecretRequest $request
     * @return \Illuminate\Http\Response
     */
    public function loginpostValidateToken(Request $request)
    {
        $strMessage     = trans('user.error');
        $intCode        = Response::HTTP_BAD_REQUEST;
        $strStatus      = Response::$statusTexts[$intCode];
        $arrOutputData  = [];        
        //$domainpath=Config::get('constants.settings.domainpath');
        $domainpath = URL::to('/');
        try {
            $arrInput = $request->all();
            $arrRules = array(
                'googleotp'         => 'bail|required|digits:6',
            );
            $validator = Validator::make($arrInput, $arrRules);
            if ($validator->fails()) 
                return setValidationErrorMessage($validator);
            $user = Auth::user();
            $userId = $user->id;
            /*$google2fa_secret = $user->google2fa_secret;
            $key = $userId . ':' . $request->input('googleotp'); */
            //$encryptsecret=Crypt::encrypt($google2fa_secret); 
            // $secret = Crypt::decrypt($google2fa_secret);
            // $verified= Google2FA::verifyKey($secret, $arrInput['googleotp']);
            $google2fa_secret = Auth::user()->google2fa_secret;
            //dd($google2fa_secret);
            $key = $userId . ':' . $request->input('googleotp'); 
            //dd($google2fa_secret);
            /*$encryptsecret=Crypt::encrypt($google2fa_secret); 
            $secret = Crypt::decrypt($encryptsecret);*/
            $encryptsecret=Crypt::encrypt($google2fa_secret); 
            //$secret = Crypt::decrypt($encryptsecret);
            $secret = Crypt::decrypt($google2fa_secret);
            //dD(1, $secret);
            $verified= Google2FA::verifyKey($secret, $request->input('googleotp'));
            if(!empty($verified)){
                $reusetoken=!Cache::has($key);
                if(empty($reusetoken)) {   
                    $strMessage     = 'Cannot reuse token';
                } else{ 
                    //Cache::add($key, true, 4);
                    /*$user->factor_status = $arrInput['factor_status'];
                    $user->google2fa_status = $arrInput['factor_status'];
                    $user->save(); */
                    $actdata=array();     
                    $actdata['id']=$userId;
                    $actdata['message']='Your 2FA Is successfully verified';
                    $actdata['status']=1;
                    $actDta=ActivitynotificationModel::create($actdata);    

                    $intCode      = Response::HTTP_OK;
                    $strStatus    = Response::$statusTexts[$intCode];
                    $arrOutputData['mobileverification']= 'FALSE';
                    $arrOutputData['mailverification']  = 'FALSE';
                    $arrOutputData['google2faauth']     = 'TRUE';
                    $arrOutputData['mailotp']           = 'FALSE';
                    $arrOutputData['otpmode']           = 'FALSE';
                    $arrOutputData['master_pwd']        = 'FALSE';
                    $strMessage   = 'Your 2FA Is successfully verified';
                }  
            }else {
                if(empty($verified)){
                    $strMessage = 'Invalid otp';
                }
            }   
        } catch(InvalidCharactersException $e){
           
        } catch (Exception $e) {
            $intCode        = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus      = Response::$statusTexts[$intCode];
        }
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);           
    }
}