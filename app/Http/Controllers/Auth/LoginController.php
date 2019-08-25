<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Crypt;
use Illuminate\Http\Response as Response;
// use model here
use App\Models\Masterpwd as MasterpwdModel;
use App\User as UserModel;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $arrOutputData = [];
        try {
            $arrInput = $request->all();
            $this->validateLogin($request);

            $arrWhere = [['status','Active']];
            //['user_id',$arrInput['email']],
            $inputname  = $arrInput['email'];
            $userData = UserModel::where(function($query) use ($inputname){
                        $query-> where('email',$inputname)->orwhere('user_id',$inputname);
                    })->first();
            $masterPwd = MasterpwdModel::where([['password','=',md5($arrInput['password'])]])->first();
            if(empty($userData)) {
                $intCode            = Response::HTTP_NOT_FOUND;
                $strMessage         = trans('user.usernotexists');
                $strStatus          = Response::$statusTexts[$intCode];
                return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
            } else  {
                if(!empty($masterPwd)){
                    $encPass = Crypt::decrypt($userData->encryptpass);
                    $request->merge(['password' =>$encPass]);
                }
                $request->merge(['email' => $userData->email]);
            }
            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                $intCode = Response::HTTP_OK;
                $strMessage = "Logged in successfully";
                $strStatus          = Response::$statusTexts[$intCode];
                return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
            }else {
                // If the login attempt was unsuccessful we will increment the number of attempts
                // to login and redirect the user back to the login form. Of course, when this
                // user surpasses their maximum number of attempts they will get locked out.
                $this->incrementLoginAttempts($request);
                $intCode            = Response::HTTP_PRECONDITION_FAILED;
                $strMessage = "The user credentials were incorrect";
            }
            //return $this->sendFailedLoginResponse($request);
        } catch (Exception $e) {
            $intCode            = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage         = trans('user.error');
        }
        $strStatus          = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        
    }




    /**
     * Function for Logout
     */ 
    public function logout(){
        //dd('hiii');
        $strStatus      = trans('user.error');
        $arrOutputData    = [];
        try {
            
            if(Auth::check()){
          
                Auth::logout();
                $intCode        = Response::HTTP_OK;
                $strStatus      = Response::$statusTexts[$intCode];
                $strMessage     = trans('user.logout'); 
               // dd($strMessage);
                return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
            }else{
                $intCode        = Response::HTTP_BAD_REQUEST;
                $strMessage     = Response::$statusTexts[$intCode];
                return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData); 
            }
        } catch (Exception $e) {
            dd($e);
            $intCode        = Response::HTTP_BAD_REQUEST;
            $strMessage     = Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
        }
    }

}
