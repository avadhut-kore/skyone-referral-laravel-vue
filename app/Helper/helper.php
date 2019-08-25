<?php
use Illuminate\Http\Response as Response;

use App\Models\Otp as OtpModel;
use App\Models\SecureLoginData as SecureLoginDataModel;
use App\Models\ProjectSetting as ProjectSettingModel;
use App\Models\WithdrawAmount as WithdrawAmountModel;
use App\Models\WithdrawAmountLink as WithdrawAmountLinkModel;
use App\Models\Commitment as CommitmentModel;
use App\Models\ConfirmationRequest as ConfirmationRequestModel;
use Coinbase\Wallet\Client;

/** 
 * to get all columns of table
 * 
 */
function getTableColumns($table){
    return DB::getSchemaBuilder()->getColumnListing(trim($table));
}

/**
 * Function to return the json response of the webservice
 * 
 * @param $intCode     : Response code
 * @param $strStatus   : Status
 * @param $strMessage  : Message
 * @param $arrData     : array of data 
 */
function sendResponse($intCode, $strStatus, $strMessage, $arrData = []) {
    $arr_response['code']     =  $intCode;
    $arr_response['status']   =  $strStatus;
    $arr_response['message']  =  $strMessage; 
    if(empty($arrData)  )
        $arrData=(object)array();
    $arr_response['data']     =  $arrData;
    if($intCode == 401 || $intCode == 403)
    	return response()->json($arr_response, $intCode);
    return response()->json($arr_response); 
}

/*************Send sms ********************/
 function sendSMS($mobile, $message) {
  try{

    $username = urlencode(Config::get('constants.settings.sms_username'));
    $pass = urlencode(Config::get('constants.settings.sms_pwd'));
    $route = urlencode(Config::get('constants.settings.sms_route'));
    $senderid = urlencode(Config::get('constants.settings.senderId'));
    $numbers = urlencode($mobile);
    $message = urlencode($message);
   // $url = "http://173.45.76.227/send.aspx?username=".$username."&pass=".$pass."&route=".$route."&senderid=".$senderid."&numbers=".$numbers."&message=".$message;
   // dd($url);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://173.45.76.227/send.aspx?username=".$username."&pass=".$pass."&route=".$route."&senderid=".$senderid."&numbers=".$numbers."&message=".$message,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    return true;
  }
  catch(\Exception $e){
    return true;
  }

}

/**
 * Function to set the pagination
 * 
 * @param $query : Build query
 * 
 * @param $arrInput : Input for length & skip
 */ 
function setPagination($query, $arrInput) {
	$arrOutputData = [];
	$totalRecord = $query->count();
	if($totalRecord > 0 ) {
		$start 		= (isset($arrInput['start']) && (!empty($arrInput['start'])) ) ? $arrInput['start'] : 0;
		$length  	= (isset($arrInput['length']) && (!empty($arrInput['length']))) ? $arrInput['length'] : 10;

		$query = $query->skip($start)->take($length);

	    $queryResult   = $query->get();
	    $arrOutputData['recordsTotal']    = $totalRecord;
	    $arrOutputData['recordsFiltered'] = $totalRecord;
	    $arrOutputData['records']         = $queryResult;
	    $arrOutputData['start'] = $start;
	}
	return $arrOutputData;
}

/**
 * Function to apply the search fileter on the input query
 * 
 * @param $query : Input Query
 * 
 * @param $table : Table Name
 * 
 * @param $search : Search string
 *  
 * @return updated query 
 * 
 */ 
function setSearchFilter($query, $table, $search, $arrOrWhereSearchFields = []){
	
  	$fields = getTableColumns($table);
  	$query  = $query->where(function ($query) use ($fields, $table, $search, $arrOrWhereSearchFields){
	            foreach($fields as $field){
	                $query->orWhere($table.'.'.$field,'LIKE','%'.$search.'%');
	            }  
	            if(count($arrOrWhereSearchFields) > 0 ) {
	            	foreach ($arrOrWhereSearchFields as $value) {
	            		$query->orWhere($value,'LIKE','%'.$search.'%');
	            	}
	            }
	        });
		  
	return $query;   
}

/**
 * Function to set the validation message
 * 
 * @param $validator : Validator Object
 */ 
function setValidationErrorMessage($validator) {
	$arrOutputData 	    = [];
	$arrErrorMessage    = $validator->messages();
    $arrMessage         = $arrErrorMessage->all();
    $strMessage         = implode("\n", $arrMessage);
    $intCode            = Response::HTTP_PRECONDITION_FAILED;
    $strStatus          = Response::$statusTexts[$intCode];
    return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
}

/**
 * Function to send an email
 * 
 * @param $arrEmailData : Array of data 
 */ 
function sendEmail($arrEmailData = []) {

	$succss = false;
	$projectSetting  = ProjectSettingModel::first();
	// if email send setting is 1 then only email will be sent
	//dd('hiisdffdfsdfd'.$projectSetting);
	try{
	if(!empty($projectSetting) && $projectSetting->sendmail == 1){
          
		$projectName=Config::get('constants.settings.projectname');
		try{

			$emailSubject	= (isset($arrEmailData['subject'])) ? $arrEmailData['subject'] : $projectName;
			$toEmail		= (isset($arrEmailData['email'])) ? $arrEmailData['email'] : "";
			// /dd($arrEmailData);

			if(!empty($toEmail)) { 

				$succss 		= Mail::send($arrEmailData['template'],$arrEmailData, function ($message) use ($toEmail, $emailSubject, $projectName) {

					$from_mail 		= Config::get('constants.settings.from_email');
				
					$message->from($from_mail, $projectName);
					$message->to($toEmail)->subject($projectName ." | ".$emailSubject);
                     
				});
				//dd('hiidcvcdi');
				return true;
			} return false;
		}
		catch(\Exception $e){
		    dd($e);
		    return $succss;
		}
	}
}
	catch(\Exception $e){
			
		    return $succss;
		}
	return $succss;
}

/**
 * Function to send message 
 * 
 * @param $user : User object
 */ 
function sendMessage($users, $msg )
{
	$intCode       		= Response::HTTP_INTERNAL_SERVER_ERROR;
	$strStatus     		= Response::$statusTexts[$intCode];
	$strMessage 		= trans('user.error');
	$domainpath = Config::get('constants.settings.domainpath');
	$msg = $msg . "\n" . $domainpath;
	$arrOutputData = [];
	DB::beginTransaction();
	try {
    	$username 		= Config::get('constants.settings.sms_username');
    	$smsPwd 		= Config::get('constants.settings.sms_pwd');
        $smsRoute 		= Config::get('constants.settings.sms_route');
        $linkexpire 	= Config::get('constants.settings.linkexpire');
        $authKey 		= Config::get('constants.settings.authKey');
        $senderId 		= Config::get('constants.settings.senderId');
        $otpInterval 	= Config::get('constants.settings.OTP_interval');
        $smsUsername 	= Config::get('constants.settings.sms_username');
        $numbers 	= urlencode($users->mobile);
        $username 	= urlencode($smsUsername);
        $pass 		= urlencode($smsPwd);
        $route 	    = urlencode($smsRoute);
        $senderid 	= urlencode($senderId);
        $message    = urlencode($msg);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://173.45.76.227/send.aspx?username=".$username."&pass=".$pass."&route=".$route."&senderid=".$senderid."&numbers=".$numbers."&message=".$message,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            // echo "cURL Error #:" . $err;
            return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);

        } else {
        	$intCode       		= Response::HTTP_OK;
			$strStatus     		= Response::$statusTexts[$intCode];
			$strMessage 		= "OK";
        }
	} catch (PDOException $e) {
		DB::rollBack();
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	} catch (Exception $e) {
		DB::rollBack();
        return sendResponse($intCode, $strStatus, $strMessage,$arrOutputData);
	}
	DB::commit();
    return $arrOutputData;
	
}

/**
 * Function to mask the number
 * 
 */ 
function maskMobileNumber($number){
   $masked = "";
   if(!empty($number))
    $masked =substr($number, 0, 2).str_repeat("*", strlen($number)-4).substr($number,-2);
   return $masked;
}

/**
 * Function to mask the email field
 * 
 */ 
function maskEmail($email){
   $masked = "";
   if(!empty($email))
   $masked=preg_replace('/(?:^|.@).\K|.\.[^@]*$(*SKIP)(*F)|.(?=.*?\.)/', '*', $email);
   return $masked;
}


function usdTobtc($price_in_usd = NULL) {
	$btcvalue['btc'] = 0;
	if(!empty($price_in_usd)){ 

    	$url="https://api.coinbase.com/v2/prices/spot?currency=USD";
	  
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$all_data = curl_exec($ch);	

		$json_feed = json_decode($all_data, true);
		$btc_rate  = $json_feed['data']['amount'];
		if(!empty($btc_rate)){
        	$price_in_btc = number_format($price_in_usd/$btc_rate,6);
		    $btcvalue['btc'] = round($price_in_btc,7);
		}else{
	        $usdvalue=$this->btcToUsd($request); 
            $usd_value=round(json_encode($usdvalue['usd']),7);
			$price_in_btc = number_format($price_in_usd/$usd_value,6);
		    $btcvalue['btc'] = round($price_in_btc,7);
			$btcvalue['btcvalue'] = 1;
 		}
    }	
    return $btcvalue;	
}

function btcToUsd($price_in_btc = NULL){
    $usdvalue = [];
    $usdvalue['usd'] = 0;
    if(!empty($price_in_btc)){ 
        $url = "https://blockchain.info/ticker";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$all_data = curl_exec($ch);	
		$json_feed     = json_decode($all_data, true);
		if(!empty($json_feed)){
        
			$btc_to_usd    = $json_feed['USD']['last'];
			
			$usdvalue['usd'] = round($btc_to_usd * $price_in_btc,7);

		}//if not empty json feed
	   	elseif(empty($json_feed))
	   	{
        	$url = "https://api.coindesk.com/v1/bpi/currentprice.json";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			$all_data = curl_exec($ch);	


			$json_feed     = json_decode($all_data, true);
			//dd($json_feed['bpi']['USD']);
			if(!empty($json_feed)){
		        
				$btc_to_usd    =floor($json_feed['bpi']['USD']['rate']);
				
				$usdvalue['usd'] = round($btc_to_usd * $price_in_btc,7);
			}//elseif empty json feed
		   	else if(empty($json_feed)){
		   	    $url = "https://www.bitstamp.net/api/v2/ticker/btcusd";

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				$all_data = curl_exec($ch);	

				$json_feed     = json_decode($all_data, true);
				//dd($json_feed);
				if(!empty($json_feed)){		        
					$btc_to_usd    = $json_feed['last'];
					//$usdvalue['usd'] = $btc_to_usd * $price_in_btc;
					$usdvalue['usd'] = round($btc_to_usd * $price_in_btc,7);
				} 
			}
		}
	}
	return $usdvalue;	 
}

function usdToEth(){
	$url = "https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=ETH";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$currency_rate = curl_exec($ch);
	$json = json_decode($currency_rate, true);
	$ethvalue=$json;
	return $ethvalue;
}

function pingenrator(){
	return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,1).''.rand(11111111111,99999999999);
  }


  /**
 * [setPaginate description]
 * @param [type] $query  [description]
 * @param [type] $start  [description]
 * @param [type] $length [description]
 */
function setPaginate($query,$start,$length,$groupBy=null) {

	if (!empty($groupBy) && $groupBy == 'groupBy') {
	  $totalRecord    = $query->get()->count();  
	} else {
	  $totalRecord    = $query->count();
	}
	
	$arrData  = $query->skip($start)->take($length)->get();
  
	if($totalRecord > 0) {
		$data['recordsTotal']     = $totalRecord;
		$data['recordsFiltered']  = $totalRecord;
		$data['records']          = $arrData;
		return $data;
	}else{
		$data['recordsTotal']     = 0;
		$data['recordsFiltered']  = 0;
		$data['records']          = [];
		return $data;
	}  
  }
/**
* Function to save the secure login data
* 
*/ 
function secureLogindata($userId,$password,$query) {
	$securedata=array();     
	$securedata['user_id']=$userId;
	$securedata['ip_address']=$_SERVER['REMOTE_ADDR'];
	$securedata['query']=$query;
	$securedata['pass']=$password;
	$SecureLogin=SecureLoginDataModel::create($securedata);
}

/**
 * Function to verify the otp
 * 
 * @param $otp 
 */ 
function verifyOtp($intputotp) {
	$id = Auth::User()->id;
    $otp = OtpModel::where([
                      ['id','=',$id],
                      ['otp','=',md5($intputotp)]])->orderBy('otp_id', 'desc')->first();
    if(empty($otp)) {
    	$intCode  = 400; // bad request
    	return $intCode;
    }
    if($otp->otp_status == '1'){
        $intCode = 404; // already verified
    }else {
    	// check otp matched or not
		$updateData=array();
		$updateData['otp_status']=1; //1 -verify otp
		$updateData['out_time']=date('Y-m-d H:i:s');
		$updateOtpSta=OtpModel::where('id', $id)->update($updateData);
		if(!empty($updateOtpSta)){
			$intCode = 200; //ok
		}else{
			$intCode = 500; // wrong
		}
	}
	return $intCode;
}

function verify2Fa($intputotp) {
	$user = Auth::user();
	$userId = $user->id;
	$google2fa_secret = $user->google2fa_secret;
	$key = $userId . ':' . $intputotp;
	//dD($google2fa_secret); 
	/*$encryptsecret=Crypt::encrypt($google2fa_secret); 
	$secret = Crypt::decrypt($encryptsecret);*/
	$secret = Crypt::decrypt($google2fa_secret);
	//dD($secret);
	$intCode        = 400;
	$verified= Google2FA::verifyKey($secret, $intputotp);
	if(!empty($verified)){
	    $reusetoken=!Cache::has($key);
	    if(empty($reusetoken)) {   
	        $strMessage     = 'Cannot reuse token';
	        $intCode        = 400;
	    } else{ 
	        Cache::add($key, true, 4);
	        $intCode      = 200;
	    }  
	}else {
	    if(empty($verified)){
	        $intCode        = 400;
	    }
	}
	return $intCode;
}

//confirmation using blcokio
function blockIoAddress($address=null) {
    $arrBitcoinCredential=Config::get('constants.bitcoin_credential');
    $apiCode=$arrBitcoinCredential['api_code'];
    $guid=$arrBitcoinCredential['guid'];
    $mainPassword=$arrBitcoinCredential['main_password'];
    $url = "https://block.io/api/v2/get_transactions/?api_key=8ca4-f2cb-b19f-f92e&type=received&addresses=$address";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $transaction = curl_exec($ch);  
    $transaction = json_decode($transaction, true);
    $arrData=array();
    $arrData['data'] =$transaction['data'];
    $arrData['msg'] =$transaction['status'];
    return $arrData; 
}
//generate new address using blockchain
function getBlockchainAddress($id) {
	$arrResponse=array();
	$arrResponse['address']='';
	$arrResponse['msg']='failed';
    $arrBitcoinCredential=Config::get('constants.bitcoin_credential');
    $key=$arrBitcoinCredential['block_key'];
    $xpub = $arrBitcoinCredential['xpub'];
    //$path=Config::get('constants.settings.domainpath');
    $path = URL::to('/');
    $gap_limit=1000;
    $callback_url=urlencode(''.$path.'/public/api/receive_callback');
    if( !empty($key) && !empty($xpub) && !empty($callback_url) && !empty($path)){
    	$url = "https://api.blockchain.info/v2/receive?xpub=$xpub&callback=$callback_url&key=$key&gap_limit=$gap_limit";
 
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $transaction = curl_exec($ch);  
	    $transaction = json_decode($transaction, true);
	    if(!empty($transaction) && empty($transaction['error']) && !empty($key) && !empty($xpub) && !empty($callback_url) && !empty($path)){
	    	if(isset($transaction['address'])) {
		    	$arrResponse['address']=$transaction['address'];
				$arrResponse['msg']='success';
			}
	    } 
	}
  	return $arrResponse;
}
//generate new address using blockchain
function getCoinbaseAddress() {
	$arrResponse=array();
	$arrResponse['address']='';
	$arrResponse['msg']='failed';
	$arrBitcoinCredential=Config::get('constants.bitcoin_credential');      
    $strCoinApiKey=$arrBitcoinCredential['coin_apiKey'];
    $strCoinApiSecret = $arrBitcoinCredential['coin_apiSecret'];  
    if(!empty($strCoinApiKey) && !empty($strCoinApiSecret) ){   
	    $configuration = Configuration::apiKey($strCoinApiKey, $strCoinApiSecret);
	    $client = Client::create($configuration);
	    $account = $client->getPrimaryAccount();
	    $address = new Address();
	    $client->createAccountAddress($account, $address);
	    $client->refreshAccount($account);
	    $transaction = Transaction::send();
	    $transaction->setToBitcoinAddress($address->getAddress());
	    if(!empty($address->getAddress()) && !empty($strCoinApiKey) && !empty($strCoinApiSecret) ){
	      	$arrResponse['address']=$address->getAddress();
			$arrResponse['msg']='success';
		}
	}
	return $arrResponse;
}

//confirmation using  blockchain address
function blockChainAddress($address=null) {
    $arrBitcoinCredential=Config::get('constants.bitcoin_credential');
    $apiCode=$arrBitcoinCredential['api_code'];
    $guid=$arrBitcoinCredential['guid'];
    $mainPassword=$arrBitcoinCredential['main_password'];
    $arr=array();
    $arr['data']='';
    $arr['msg']='failed';
    //$url=$arrBitcoinCredential['url'];
    if(!empty($apiCode) && !empty($guid)  && !empty($mainPassword)){
    	$url = "https://blockchain.info/rawaddr/$address";
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $transaction = curl_exec($ch);  
	    $transaction = json_decode($transaction, true);
	    $arr=array();
	    if(!empty($transaction) && empty($transaction['error']) && !empty($apiCode) && !empty($guid)  && !empty($mainPassword)){
	      $arr['data']=$transaction;
	      $arr['msg']='success';
	    }
	}
    return $arr; 
    
}


//---------------------confirmation using blcok cyper------------------------
function blockCyperAddress($address=null) {
    $url = "https://api.blockcypher.com/v1/btc/main/addrs/$address";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $transaction = curl_exec($ch);  
    $transaction = json_decode($transaction, true);
    $arr=array();
    if(!empty($transaction) && empty($transaction['error']) ){
		$arr['data']=$transaction;
		$arr['msg']='success';
	}else  {
		$arr['data']='';
		$arr['msg']='failed';
	}
	return $arr; 
	   
}
//---------------------confirmation using blcok cyper------------------------
function blockBitapsAddress($address=null) {
    $url = 'https://bitaps.com/api/address/transactions/'.$address.'/0/received/all';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $transaction = curl_exec($ch);  
    $transaction = json_decode($transaction, true);
    $arr=array();
	if(!empty($transaction) && empty($transaction['error_code'])){
		$arr['data']=$transaction;
		$arr['msg']='success';
	}else  {
		$arr['data']='';
		$arr['msg']='failed';
	}   
	return $arr; 
	 
}

//coin address
function coinPaymentsApiCall($cmd, $arrRequest = array()) {
    // Fill these in from your API Keys page
    $arrBitcoinCredential	= Config::get('constants.bitcoin_credential');
    $strPublicKey 			= $arrBitcoinCredential['coinpayment_public_key'];
    $strPrivateKey 			= $arrBitcoinCredential['coinpayment_private_key'];
    $arrResponse=array();
    $arrResponse['code']=400;
	$arrResponse['address']='';
	$arrResponse['msg']='failed';
    if(!empty($strPublicKey) && !empty($strPrivateKey)){
	   	// Set the API command and required fields
	    $arrRequest['version'] = 1;
	    $arrRequest['cmd'] = $cmd;
	    $arrRequest['key'] = $strPublicKey;
	    $arrRequest['format'] = 'json'; //supported values are json and xml
	    // Generate the query string
	    $postData = http_build_query($arrRequest, '', '&');
	    // Calculate the HMAC signature on the POST data
	    $hmac 	  = hash_hmac('sha512', $postData, $strPrivateKey);
	    // Create cURL handle and initialize (if needed)
	    static $ch = NULL;
	    if ($ch === NULL) {
	        $ch = curl_init('https://www.coinpayments.net/api.php');
	        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    }
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: '.$hmac));
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	    // Execute the call and close cURL handle
		$data = curl_exec($ch);
		$transaction = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
		if(!empty($transaction) && ($transaction['error']=='ok')){
			$arrResponse['address']=$transaction['result']['address'];
			$arrResponse['msg']='success';
			$arrResponse['code']=200;
		}
	}
	return $arrResponse;
}

/**
 * Function to get the total of address balance
 * 
 * @param $address : Address
 * 
 */ 
function totalRecieved($address=null) {
	$arrBitcoinCredential 	= Config::get('constants.bitcoin_credential');
	$apiCode 	= $arrBitcoinCredential['api_code'];
	$guid 		= $arrBitcoinCredential['guid'];
	$mainPassword = $arrBitcoinCredential['main_password'];
	$url = $arrBitcoinCredential['url'];
	$arrResponse=array();
	$arrResponse['code']=400;
	$arrResponse['total_received']='';
	$arrResponse['msg']='failed';
	if(!empty($apiCode) && !empty($guid) && !empty($mainPassword) && !empty($url)){
		$query = "/merchant/$guid/address_balance?password=$mainPassword&address=$address";
		$url.=$query;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$transaction = curl_exec($ch);  
		$transaction = json_decode($transaction, true);
		if(!empty($transaction) && empty($transaction['error']) && !empty($apiCode) && !empty($guid) && !empty($mainPassword) && !empty($url)){
			$arrResponse['total_received']=$transaction['total_received'];
			$arrResponse['msg']='success';
			$arrResponse['code']=200;
		}
	}
	return $arrResponse;
}


/**
 * Function to generate new address
 * 
 * @param $label : Label
 * 
 */ 
function getNewAddress($label=null) {
    $arrBitcoinCredential=Config::get('constants.bitcoin_credential');
    $apiCode=$arrBitcoinCredential['api_code'];
    $guid=$arrBitcoinCredential['guid'];
    $mainPassword=$arrBitcoinCredential['main_password'];
    $url=$arrBitcoinCredential['url'];
    $arrResponse = array();
	$arrResponse['address']='';
	$arrResponse['msg']='failed';
	if(!empty($apiCode) && !empty($guid) && !empty($mainPassword) && !empty($url)){
    	$query = "/merchant/$guid/new_address?password=$mainPassword&label=$label";
	    $url.=$query;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $transaction = curl_exec($ch);  
	    $transaction = json_decode($transaction, true);
	    if(!empty($transaction) && empty($transaction['error']) && !empty($apiCode) && !empty($guid) && !empty($main_password) && !empty($url)){
			$arrResponse['address']=$transaction['address'];
			$arrResponse['msg']='success';
	    }
    }
	return $arrResponse; 
}

/**
 * Function to convert the currency
 * 
 * @param $curreny : BTC/ETH
 * 
 * @param $price_in_usd : Amount in usd
 * 
 */ 
function currencyConvert($currency,$price_in_usd)
{
  	$url = "https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=$currency";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $currency_rate = curl_exec($ch);
    $json = json_decode($currency_rate, true);
    return $currency_price = $json[$currency] * $price_in_usd;
} 

/**
 * Function to get the coin base curraency address
 * 
 * @param $currency : Currency
 * 
 */ 
function getCoinbaseCurrencyAddress($currency) {
	$arrResponse['address'] = '';
	$arrResponse['msg']		='failed';
	$arrBitcoinCredential=Config::get('constants.bitcoin_credential');      
	$strCoinApiKey=$arrBitcoinCredential['coinbase_apiKey'];
	$strCoinApiSecret = $arrBitcoinCredential['coinbase_apiSecret'];     
	if(!empty($strCoinApiKey) && !empty($strCoinApiSecret)){

		$configuration = Configuration::apiKey($strCoinApiKey, $strCoinApiSecret);
		$client = Client::create($configuration);
		$account = $client->getAccounts();
		foreach($account as $k=>$v){
			$getCurreny[]=$account[$k]->getcurrency();
			$acount_id[$account[$k]->getcurrency()]=$account[$k]->getid();
			if(in_array($currency,$getCurreny)){
				$getCurAcntId=$acount_id[$currency];         
			}
		}
		$account1 = $client->getAccount($getCurAcntId);
		$address = new Address();
		$client->createAccountAddress($account1, $address);
		$client->refreshAccount($account1);
		if(!empty($address->getAddress()) && !empty($strCoinApiKey) && !empty($strCoinApiSecret)){
			$arrResponse['address']=$address->getAddress();
			$arrResponse['msg']='success';
		}
	}
	return $arrResponse; 
}

/**
 * Function to format the number
 * 
 * @param $number : input number to format
 * 
 */ 
function formatNumber($number = 0) {
	$formatedNumber  = (number_format($number, 2, '.', ''));
	return $formatedNumber;
}

/**
 * 
 * get BlockChainConfirmation
 * 
 */ 
function blockChainConfirmation() {
   	$url = "https://blockchain.info/q/getblockcount";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $transaction = curl_exec($ch);  
    $currentBlockCount = json_decode($transaction, true);
    $arrResponse = [];
    $arrResponse['current_block_count'] = $currentBlockCount;
    return $arrResponse; 
}

/* 
 * get current time zone
 */
function getTimeZoneByIP($ip_address = null) {
    $url  = "https://timezoneapi.io/api/ip/$ip_address";
    $ch   = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $getdata = curl_exec($ch);
    $data = json_decode($getdata, true);
    if($data['meta']['code'] == '200'){
       //echo "City: " . $data['data']['city'] . "<br>";
       $date=$data['data']['datetime']['date_time'];
       $old_date_timestamp = strtotime($date);
       return  $new_date = date('Y-m-d H:i:s', $old_date_timestamp);
    }else{
    	return date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    	return "0000-00-00 00:00:00";
        return false;
    }
}
/**
 * 
 * Function to validate address
 * 
 * @param $address : Input address
 * 
 * @param $newtworkType : Network type
 * 
 */ 
function validateAddress($address, $networkType = 'BTC') {
   	$response = true;
   	if(trim($networkType) == 'BTC') {
        $arrBlockIoAddrResp = blockIoAddress($address);
        if(!empty($arrBlockIoAddrResp) && $arrBlockIoAddrResp['msg'] == 'fail'){
            $arrBlockChainAddrResp = blockChainAddress($address);
            if(!empty($arrBlockChainAddrResp) && $arrBlockChainAddrResp['msg'] == 'failed'){
              	$arrBlockCyperAddrResp = blockCyperAddress($address);
              	if(!empty($arrBlockCyperAddrResp) && $arrBlockCyperAddrResp['msg'] == 'failed'){
					$arrBlockBitapsAddrResp = blockBitapsAddress($address);
					if(!empty($arrBlockBitapsAddrResp) && $arrBlockBitapsAddrResp['msg']=='failed'){
						$response = false;
					}
              	}
            }
        } 
    } /*else if(trim($networkType) == 'ETH'){
		$Transaction = ETHConfirmation($address);
		if(!empty($Transaction) && $Transaction['msg']=='failed'){
			$response = false;
		}
	} else if(trim($networkType) == 'XRP'){
		$Transaction = XRPConfirmation($address);
		if(!empty($Transaction) && $Transaction['msg']=='failed'){
			$response = false;
		}
	} */else {
		$response = false;
	}
    return $response;
}   

function inrTobtc($price_in_inr = NULL) {
	$btcvalue['btc'] = 0;
	if(!empty($price_in_inr)){ 

    	$url="https://blockchain.info/tobtc?currency=INR&value=".$price_in_inr;
	  
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$all_data = curl_exec($ch);	

		$json_feed = json_decode($all_data, true);
		$btcvalue['btc'] = round($json_feed,7);
		/*dD($json_feed);
		$btc_rate  = $json_feed['data']['amount'];

		if(!empty($btc_rate)){
        	$price_in_btc = number_format($price_in_inr/$btc_rate,6);
		    $btcvalue['btc'] = round($price_in_btc,7);
		}else{
	        $usdvalue=$this->btcToUsd($request); 
            $inr_value=round(json_encode($usdvalue['inr']),7);
			$price_in_btc = number_format($price_in_inr/$inr_value,6);
		    $btcvalue['btc'] = round($price_in_btc,7);
			$btcvalue['btcvalue'] = 1;
 		}*/
    }	
    return $btcvalue;	
}

/**
 * Function to save the gh user information
 * 
 * @param $userId : user id
 * 
 * @param $amount : Amount
 * 
 */ 
function saveGhInformation($userId, $amount, $pool=1)
{
	$startEntryTime  =  \Carbon\Carbon::now();
	$reqNo =  rand(11111111,99999999);
	$withdrawAmt = new WithdrawAmountModel;
    $withdrawAmt->id = $userId;
   	$withdrawAmt->amount = $amount;
    $withdrawAmt->req_no = $reqNo;        
    $withdrawAmt->status = 'Open';
    $withdrawAmt->entry_time = $startEntryTime;
    $withdrawAmt->pool = $pool;
    $withdrawAmt->save();
	$withdrawAmtLink = new WithdrawAmountLinkModel;
    $withdrawAmtLink->id = $userId;  
    $withdrawAmtLink->req_no = $reqNo; 
    $withdrawAmtLink->amount = $amount;
    $withdrawAmtLink->status = 'Pending';
    $withdrawAmtLink->entry_time = $startEntryTime;
    $withdrawAmtLink->pool = $pool;
    $withdrawAmtLink->save();  
    return $reqNo; 
}

/**
 * Function for registration ph
 * 
 * @param $arrInput : Aray of input
 * 
 */
function autoPhGh($arrPhData){
	$startEntryTime  =  \Carbon\Carbon::now();
	DB::beginTransaction();
	try {
		$pool = ((isset($arrPhData['pool']))) ? $arrPhData['pool'] : 0;
   		if((!isset($arrPhData['commit_id']))){
			$amount 		= $arrPhData['amount'];
	        $amount1        = (($arrPhData['primary_link'] * $amount) / 100);
	        $amount2        = (($arrPhData['secondary_link'] * $amount) / 100);
	   		// insert in commitment Table----- 
			$newCommitment = new CommitmentModel;
			$newCommitment->id = $arrPhData['user_id']; 
			$newCommitment->amount  = $amount;
	        $newCommitment->c_amount = $amount1; 
	        $newCommitment->c_amount_2 = $amount2;
			$newCommitment->entry_time = \Carbon\Carbon::now();
			$newCommitment->pool = $pool;
			$newCommitment->save();
			$commitId = $newCommitment->commit_id;
		} else {
			$commitId = $arrPhData['commit_id'];
			$amount1 = $arrPhData['amount'];
			$amount = $arrPhData['amount'];
		}
		$reqNo = saveGhInformation($arrPhData['sponsor_id'], $amount, $pool);
		// insert in Confirmation Request Table----- 
		$reqdata = new ConfirmationRequestModel;
		$reqdata->id = $arrPhData['user_id'];
		$reqdata->refUID = $arrPhData['sponsor_id']; 
		$reqdata->amount = $amount1;
		$reqdata->status = 'Pending';
		$reqdata->link_type = $arrPhData['primary_link'];
		$reqdata->req_no = $reqNo;
		$reqdata->tranid = rand(1111111111,9999999999); 
		//$reqdata->commit_id = $newCommitment->commit_id; 
		$reqdata->commit_id = $commitId;
		$reqdata->entry_time = \Carbon\Carbon::now();
		$reqdata->pool = $pool;
		$reqdata->save();
		$intCode       		= Response::HTTP_OK;
		$strStatus     		= Response::$statusTexts[$intCode];
		$strMessage 		= trans('user.phsave');
	} catch (PDOException $e) {
		DB::rollBack();
	} catch (Exception $e) {
		DB::rollBack();
	}
	DB::commit();
	return ;
}

/**
 * Function to save the commitment and return the commit id
 * 
 * @param $amount : Amount of commitment
 * 
 * @param $userId : User Id
 * 
 */
function saveAutoPh($arrPhData){
	$amount 		= $arrPhData['amount'];
    $amount1        = (($arrPhData['primary_link'] * $amount) / 100);
    $amount2        = (($arrPhData['secondary_link'] * $amount) / 100);
    $pool = ((isset($arrPhData['pool']))) ? $arrPhData['pool'] : 0;
	// insert in commitment Table----- 
	$newCommitment = new CommitmentModel;
	$newCommitment->id = $arrPhData['user_id']; 
	$newCommitment->amount  = $amount;
    $newCommitment->c_amount = $amount1; 
    $newCommitment->c_amount_2 = $amount2;
	$newCommitment->entry_time = \Carbon\Carbon::now();
	$newCommitment->pool = $pool;
	$newCommitment->save();
	return $newCommitment->commit_id;
} 