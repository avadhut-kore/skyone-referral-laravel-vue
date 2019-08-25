<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use Exception;
use Auth;
use DB; 
use Config;
// use model here
use App\User as UserModel;

class DashboardController extends Controller {

    /**
     * Function to fetch the dashboard data
     * 
     */ 
    public function getUserDashboardDetails(Request $request) {
        //dd('hiii');
        $arrOutputData = [];
        try {
            $id = Auth::user()->id;
            $getDetails = Auth::user();
            if(empty($getDetails)) {
                $intCode            = Response::HTTP_BAD_REQUEST;
                $strStatus          = Response::$statusTexts[$intCode];
                $strMessage         = 'Dashboard data not found';
                return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
            }

            $arrOutputData['categories'] = ['Loan','Insurance','Hotel & Resort Booking','Real Estate','Jobs'];
            $arrOutputData['user_id']             = $getDetails->user_id;
            $arrOutputData['fullname']            = $getDetails->fullname;
            
            $intCode         = Response::HTTP_OK;
            $strMessage      = trans('user.recordfound');
        } catch (Exception $e) {
            $intCode            = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage         = trans('admin.defaultexceptionmessage');
        }
        $strStatus          = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }

}