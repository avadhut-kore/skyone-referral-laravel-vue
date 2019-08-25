<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use Exception;
use Validator;
// use trait here
use App\Traits\Dashboard;

class DashboardController extends Controller {
    use Dashboard;
    public $arrOutputData = [];
    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboardData( Request $request)
    {
        $arrOutputData = [];
        try {
            $arrOutputData = $this->getAdminDashboardData($request );
            $intCode       = Response::HTTP_OK;
            $strMessage    = Response::$statusTexts[$intCode];
            $strResponse   = "OK"; 
            return sendResponse($intCode, $bool_success, $strMessage,$arrOutputData);
        }catch (Exception $e) {
            $intCode       = $e->getCode();
            $strMessage    = Response::$statusTexts[$intCode];
            $strResponse   = trans('user.error');
            return sendResponse($intCode, $strResponse, $strMessage,$arrOutputData);
        }
    }


    /**
     * Show the dashboard sumary
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardSummaryData( Request $request)
    {
        $arrOutputData = [];
        try {
            $arrInput       = $request->all();

            $arrOutputData = $this->getDashboardSummaryData($request );
            return $arrOutputData;
        }catch (Exception $e) {
            $intCode       = $e->getCode();
            $strMessage    = Response::$statusTexts[$intCode];
            $strResponse   = "Ok";
            return sendResponse($intCode, $strResponse, $strMessage,$arrOutputData);
        }
    }
}
