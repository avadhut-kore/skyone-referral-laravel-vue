<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as Response;
// use model here
use App\Models\Otp as OtpModel;

class AdminMiddleware
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
        $arrOutputData = [];
        if ((Auth::check()) && (Auth::user()->status == 'Active' && Auth::user()->type == 'Admin')) {
            $arrSetHeaders  = apache_request_headers();
            //dd($otp->otp_status);
            if(Auth::user()->google2fa_status != 'enable') {
                $arrOtpWhere  = [['ip_address', $_SERVER['REMOTE_ADDR']],['id', Auth::user()->id]];
                //dd($arrOtpWhere);
                /*$otp =  OtpModel::select('otp_status')->where($arrOtpWhere)->orderby('otp_id', 'desc')->first();
                if(( !empty($otp) && $otp->otp_status === 1) ){
                    return $next($request);
                }
                else {
                    $strMessage         = trans('user.verifyotp');
                    $intCode            = Response::HTTP_FORBIDDEN;
                    $strStatus          = Response::$statusTexts[$intCode];
                    return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);    
                }*/
                return $next($request);
            } else {
                return $next($request);
            }
        } else {
            $intCode            = Response::HTTP_FORBIDDEN;
            $strStatus          = $strMessage = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    
        }
    }
}
