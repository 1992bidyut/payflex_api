<?php
/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class ApplicationSetupAndCheck extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('ApplicationModel');
		$this->load->helper('url');
		$this->load->model('login_model');
	}

	protected $rest_format   = 'application/json';
	var $userName;

	function _perform_library_auth( $email = '', $password = NULL)
	{
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$this->userPass=$password;
		$password = sha1($password);
		$this->userName=$email;

		$isValidUser = $this->login_model->getUser($email, $password);

		if(empty($isValidUser)){
			$response=array();

			$response['code']=202;
			$response['message']='Username or Password error!';
			$response['data']=null;
			$this->response(json_encode($response), 401);
			return false;
		}
		else{
			return true;
		}
	}

	function index_post(){

		if( $this->request->body){
			$requestData = $this->request->body;
		}else{
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'),true);
		$response=array();
		$system_param=array();
		if ($requestData['app_version']=='1v1'){
			$response['code']=202;
			$response['message']='Successfully!';
			$system_param['isUpdatedApp']=true;
			$system_param['isSystemUnderMaintenance']=false;
			$system_param['lastVersionOfApp']='1v1';
			$system_param['updatedAppLink']='https://drive.google.com/drive/folders/1w5TZJ-0NISdrIoNfUI3pCx6nfUtcDwZk?usp=sharing';
			$system_param['isMessageForUser']=false;
			$system_param['customWebViewURL']='https://payflex.onukit.com/total/';
		}else{
			$response['code']=202;
			$response['message']='Not Successfully!';
			$response['message']='Successfully!';
			$system_param['isUpdatedApp']=false;
			$system_param['isSystemUnderMaintenance']=false;
			$system_param['lastVersionOfApp']='1v1';
			$system_param['updatedAppLink']='https://drive.google.com/drive/folders/1w5TZJ-0NISdrIoNfUI3pCx6nfUtcDwZk?usp=sharing';
			$system_param['isMessageForUser']=true;
			$system_param['customWebViewURL']='';
		}
		$response['data']=$system_param;
		$this->response(json_encode($response), 202);
	}
}

?>
