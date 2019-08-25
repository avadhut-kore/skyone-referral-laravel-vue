<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use Exception;
use Auth;
use DB;
use Validator;
// use model here
use App\Models\Country as CountryModel;
use App\Models\ProjectSetting as ProjectSettingModel;
class CommonController extends Controller
{
	public $arrOutputData = [];
	/**
	 * get all the countries
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCountry() {
		try {
			$countries = CountryModel::get();
			$intCode = Response::HTTP_NOT_FOUND;
			$strMessage = trans('user.recordnotfound');
			if(count($countries) > 0 ){
				$this->arrOutputData = $countries->toArray();
		    	$intCode       		= Response::HTTP_OK;
		    	$strMessage  		= "OK";
		    }
            $strStatus     		= Response::$statusTexts[$intCode];
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
		} catch (Exception $e) {
            dd($e);
			$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus     		= Response::$statusTexts[$intCode];
            $strMessage 		= trans('admin.defaultexceptionmessage');
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
		}
	}

	/**
	 * Function for project settings values
	 * 
	 * @param $request : HTTP Request
	 * 
	 */ 
	public function getProjectSetting(Request $request){
		try {
			$arrWhere  = [['status','1']]; 
			$projectSetting = ProjectSettingModel::where($arrWhere)->first();
			$this->arrOutputData = $projectSetting;
		    $intCode       		= Response::HTTP_OK;
            $strStatus     		= Response::$statusTexts[$intCode];
            $strMessage  		= "OK";
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
		} catch (Exception $e) {
			$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus     		= Response::$statusTexts[$intCode];
            $strMessage 		= trans('admin.defaultexceptionmessage');
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
		}
	}

	 /**
     * Function to get the user details
     * 
     * @param $request : HTTP Request object
     *  
     */ 
    public function userExists(Request $request) {
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
            $arrSelect  = ['id','fullname','entry_time'];
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
     * get state by country 
     *
     * @return \Illuminate\Http\Response
     */
    public function getStateByCountry(Request $request) {
        $arrOutputData = [];
        try {
            $rules = array('country' => 'required');
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $message = messageCreator($validator->errors());
                return sendresponse($this->statuscode[403]['code'], $this->statuscode[403]['status'], $message, '');
            }
            $arrOutputData = CountryModel::join('tbl_state as state', 'tbl_country_new.id', '=', 'state.country_id')->select('state.name')->where('tbl_country_new.iso_code', $request->country)->get();

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
     * Function to check the user status
     * 
     * 
     */ 
    public function checkUserAccess(Request $request){
        try {
            $this->arrOutputData['type'] = Auth::user()->type;
            $this->arrOutputData['user_id'] = Auth::user()->user_id;
            $intCode            = Response::HTTP_OK;
            $strStatus          = Response::$statusTexts[$intCode];
            $strMessage         = "OK";
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
        } catch (Exception $e) {
            $intCode            = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strStatus          = Response::$statusTexts[$intCode];
            $strMessage         = trans('admin.defaultexceptionmessage');
            return sendResponse($intCode, $strStatus, $strMessage,$this->arrOutputData);
        }
    }
}