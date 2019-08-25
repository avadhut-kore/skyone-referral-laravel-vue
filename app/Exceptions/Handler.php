<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response as Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\HttpException;
use League\OAuth2\Server\Exception\OAuthException ;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Auth;
use URL;
use Config;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {   
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) { 
       dd($exception);
        $arrOutputData = [];
        $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
        $strMessage    =  Response::$statusTexts[$intCode];
        $projectUrl = Config::get('constants.settings.domainpath');
        if(Auth::check()) {
            if($request->is('api*')){
                if ($exception instanceof NotFoundHttpException){
                    $intCode        = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $strMessage     = "Invalid route";
                }
            } else {
                return redirect($projectUrl);
            }
        }
        else{
            if($request->is('api*')){
                $intCode    = Response::HTTP_UNAUTHORIZED;
                $strMessage = Response::$statusTexts[$intCode];
            } else {
                return redirect($projectUrl);
            }
        }
        $arrResponse['code']     =  $intCode;
        $strStatus  = Response::$statusTexts[$intCode];
        $arrResponse['status']   =  $strStatus;
        $arrData    =(object)array();
        $arrResponse['data']     =  $arrData;
        return response()->json($arrResponse,$intCode);
    }
}