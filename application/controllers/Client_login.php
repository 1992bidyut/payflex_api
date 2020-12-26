<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Client_login extends REST_Controller
{
	var $username;
	var $pass;

	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('login_model');
		$this->load->helper('url');
		$this->load->model('client_model');
	}

	protected $rest_format   = 'application/json';

	function _perform_library_auth( $email = '', $password = NULL)
	{
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$this->username=$email;
		$this->pass=$password;

		$password = sha1($password);

		$isValidUser = $this->login_model->getUser($email, $password);

		if(empty($isValidUser)){
			$resonseText = "errorLogin";
			$this->response($resonseText, 401);
			return false;
		}
		else{
			return true;
		}
	}

	public function index_post(){
		if( $this->request->body){
			$requestData = $this->request->body;
		}else{
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'),true);
		$android_id=$requestData['android_id'];
		$userData=$this->login_model->getUser($this->username,sha1($this->pass));
		$user_id=$userData[0]['id'];
		$clientData=$this->login_model->getClientUserInfo($user_id);
		$contact=$this->client_model->getClientContact($clientData[0]['client_id']);
		$image_name=$this->client_model->getProfilePicture($clientData[0]['client_id']);
		$client_id=$clientData[0]['client_id'];
		$allDevices=$this->login_model->getAllDeviceMap($client_id);
		$otp_code=rand(1000,9999);
//		echo $otp_code;

		if ($this->login_model->isDeviceMapExist($client_id,$android_id,1)){
			$clientData[0]['isNewDevice']=false;
			$clientData[0]['isValidDevice']=true;
		}else{
			if (count($allDevices)<3){
				if (!$this->login_model->isDeviceMapExist($client_id,$android_id,0)){
					$device_map['client_id']=$client_id;
					$device_map['android_id']=$android_id;
					$device_map['last_otp']=$otp_code;
					$this->login_model->insertDeviceMap($device_map);
					$clientData[0]['isNewDevice']=true;
					$clientData[0]['isValidDevice']=true;
				}else{
					$device_map['client_id']=$client_id;
					$device_map['android_id']=$android_id;
					$device_map['last_otp']=$otp_code;
					$this->login_model->updateDeviceMap($client_id,$android_id,$device_map);
					$clientData[0]['isNewDevice']=true;
					$clientData[0]['isValidDevice']=true;
				}

			}else{
				$clientData[0]['isNewDevice']=true;
				$clientData[0]['isValidDevice']=false;
			}
		}


//		$clientData[0]['image_url']='http://demo.onuserver.com/payFlex/asset/images/profileImg/'.$clientData[0]['client_id'].'/'.$image_name;
		$clientData[0]['image_url']='https://payflex.onukit.com/total/asset/images/profileImg/'.$clientData[0]['client_id'].'/'.$image_name;
		$clientData[0]['contacts']=$contact;
//		echo print_r($contact);
		foreach($contact as $cont){
			if ($cont['contact_type_id']==1){
				$this->sendOTP($cont['contact_value'],$otp_code);
			}
		}

		$this->response(json_encode($clientData[0]),202);
	}

	private function sendOTP($phoneNo,$otp){
		$sms_array = array();

		//create a json array of your sms
		$row_array['trxID'] =  $this->udate('YmdHisu');
		$row_array['trxTime'] = date('Y-m-d H:i:s');

		$mySMSArray [0]['smsID'] = $this->udate('YmdHisu');
		$mySMSArray [0]['smsSendTime'] = date('Y-m-d H:i:s');
		$mySMSArray [0]['mask'] = 'maskName';
		$mySMSArray [0]['mobileNo'] = $phoneNo;
		$mySMSArray [0]['smsBody'] = 'Please input this OTP '.$otp.' number into the PayFlex app';

		$row_array['smsDatumArray'] = $mySMSArray;
		$myJSonDatum = json_encode($row_array);

		//specifi the url
		$url="http://api.infobuzzer.net/v3.1/SendSMS/sendSmsInfoStore";

		if($ch = curl_init($url))
		{
			//Your valid username & Password ----------Please update those field
			$username = 'sms@lafargeholcim.com ';
			$password = 'holcim2233';

			curl_setopt( $ch , CURLOPT_HTTPAUTH , CURLAUTH_BASIC ) ;
			curl_setopt( $ch, CURLOPT_USERPWD , $username . ':' . $password ) ;
			curl_setopt( $ch , CURLOPT_CUSTOMREQUEST , 'POST' ) ;

			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
				'Content-Length: ' . strlen($myJSonDatum)));

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$myJSonDatum);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt( $ch, CURLOPT_TIMEOUT , 300 ) ;
			$response=curl_exec($ch);
			curl_close($ch);
//			echo('Response is: '.$response);
		}
		else
		{
//			echo("Sorry,the connection cannot be established");
		}
	}
	function udate($format, $utimestamp = null)
	{
		$m = explode(' ',microtime());
		list($totalSeconds, $extraMilliseconds) = array($m[1], (int)round($m[0]*1000,3));
		return date("YmdHis", $totalSeconds) . sprintf('%03d',$extraMilliseconds) ;
	}
}
