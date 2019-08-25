<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Exception;
use PDOException;
use DB;
// use model
use App\User as UserModel;
use App\Models\Dashboard as DashboardModel;

trait Dashboard {
    
public $arrOutputData = [];
/**	
* Function to save the user according to plan
* 
* @param $arrInput : Array of the input
* 
*/ 
    public function getAdminDashboardData(Request $request){
    $this->arrOutputData['intUserTotalCount'] 	= $this->arrOutputData['intUserActiveCount'] 	= $this->arrOutputData['intUserInactiveCount']= 0 ;
        try {
            $arrInput = $request->all();
            $user = new UserModel;	
            if(count($arrInput) > 0) {
                if(isset($arrInput['start_date']) && ( !empty($arrInput['start_date']))) {
                $user = $user->where('entry_time', '>=', $arrInput['start_date']);
                $this->arrOutputData['startDate'] = $arrInput['start_date'];
                }
                if(isset($arrInput['end_date']) && ( !empty($arrInput['end_date']))) {
                $user = $user->where('entry_time', '<=', $arrInput['end_date']);
                $this->arrOutputData['endDate'] = $arrInput['end_date'];
                }
            }

            // fetch the total count of the users registered
            $this->arrOutputData['intUserTotalCount'] = $user->count();
            // fetch the active user counts
            $arrActiveWhere = [['status','Active']];
            $this->arrOutputData['intUserActiveCount'] = $user->where($arrActiveWhere)->count();
            // fetch the active user counts
            $arrInactiveWhere = [['status','Inactive']];
            $this->arrOutputData['intUserInactiveCount'] = $user->where($arrInactiveWhere)->count();

        } catch (Exception $e) {
            return $this->arrOutputData;
        }
        return $this->arrOutputData;
	}

    /**
     * Function for the dashboard summary
     * 
     * @param $arrInput : Array of input
     * 
     */ 
    public function getDashboardSummaryData($arrInput){
        try {
            $arrOutputData['total_user'] = 0;
            $strMessage = trans('user.recordfound');
            $intCode    = Response::HTTP_OK;
        } catch (Exception $e) {
            $intCode       = Response::HTTP_INTERNAL_SERVER_ERROR;
            $strMessage    = trans('user.error');
        }
        $strStatus     = Response::$statusTexts[$intCode];
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
    }

    
}