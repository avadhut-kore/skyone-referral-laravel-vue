<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as Response;
// use model here
use App\Models\Otp as OtpModel;
use App\Models\ProjectSetting as ProjectSettingModel;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        ///dd('hii');
        $arrOutputData = [];
       // dd(Auth::check());
        if ((Auth::check()) && (Auth::user()->status == 'Active' && Auth::user()->type != 'Admin')) {
            // check fo google 2fa status if enable then only process the request else stick to OTP
            $arrSetHeaders  = apache_request_headers();
            if(Auth::user()->google2fa_status != 'enable') {
                /*$projectSetting = ProjectSettingModel::first();
                if(isset($arrSetHeaders['otpmode']) && $arrSetHeaders['otpmode'] == "FALSE")
                    return $next($request);
                if(!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
                    $arrOtpWhere  = [['ip_address', $_SERVER['REMOTE_ADDR']],['id', Auth::user()->id],['otp_for', '1']];
                    $otp =  OtpModel::select('otp_status')->where($arrOtpWhere)->orderby('otp_id', 'desc')->first();
                    if(( !empty($otp) && $otp->otp_status === 1) || ( isset($arrSetHeaders['master_pwd']) && $arrSetHeaders['master_pwd'] == "TRUE")){
                        return $next($request);
                    }
                    else {
                        $strMessage         = trans('user.verifyotp');
                        $intCode            = Response::HTTP_FORBIDDEN;
                        $strStatus          = Response::$statusTexts[$intCode];
                        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);    
                    }
                } else {*/
                    return $next($request);
                //}
            } else {
                return $next($request);
            }
        } else {
            $intCode            = Response::HTTP_FORBIDDEN;
            $strStatus          = $strMessage =  Response::$statusTexts[$intCode];
            if((Auth::check()) && ( Auth::user()->status == 'Inactive'))
                $strMessage = trans('user.userstatusinactive');
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }
}