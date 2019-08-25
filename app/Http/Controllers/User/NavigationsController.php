<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response as Response;
use Exception;
use Auth;
use DB;

class NavigationsController extends Controller
{
	public $arrOutputData = [];
	/**
	 * get all navigation list by user id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getNavigations() {
		try {
			$loggedUserId = Auth::user()->id;
			$arrNavigationWhere = [['par.id',$loggedUserId],['pan.status','Active']];
		    $arrNavigations = DB::table('ps_admin_navigation as pan')
		                    ->join('ps_admin_rights as par','par.navigation_id','=','pan.navigation_id')
		                    ->join('ps_admin_navigation as pan1','pan1.navigation_id','=','pan.parent_id')
		                    ->select('pan.navigation_id','pan.parent_id','pan.menu','pan.path','pan.icon_class','pan.status','par.id','par.entry_time','pan1.menu as parent_menu','pan1.path as parent_path','pan1.icon_class as parent_icon_class','pan1.status as parent_status')
		                    ->where($arrNavigationWhere)
		                    ->get();

		    $arrFinalData = $filterData = [];
		    foreach ($arrNavigations as $value) {
		      $filterData[$value->parent_menu]['parentmenu']  = (object) ['parent_menu'=>$value->parent_menu,'parent_path'=>$value->parent_path,'parent_icon_class'=>$value->parent_icon_class,'parent_status'=>$value->parent_status];
		      $filterData[$value->parent_menu]['childmenu'][] = $value;
		    }
		    
		    foreach ($filterData as $value) {
		      $this->arrOutputData[] = (object)['parentmenu' => $value['parentmenu'], 'childmenu' => $value['childmenu']];
		    }
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
}