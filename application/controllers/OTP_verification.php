<?php
/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class OTP_verification extends REST_Controller
{
	var $userName;
	var $userId;

	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('ApplicationModel');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('offer_model');
	}

	//

	protected $rest_format = 'application/json';


	function _perform_library_auth($email = '', $password = NULL)
	{
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$this->userPass = $password;
		$password = sha1($password);
		$this->userName = $email;

		$isValidUser = $this->login_model->getUser($email, $password);

		$this->userId = $isValidUser[0]['id'];

		if (empty($isValidUser)) {
			$response = array();

			$response['code'] = 202;
			$response['message'] = 'Username or Password error!';
			$response['data'] = null;
			$this->response(json_encode($response), 401);
			return false;
		} else {
			return true;
		}
	}

	function index_post()
	{
		if ($this->request->body) {
			$requestData = $this->request->body;
		} else {
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'), true);
		$client_id=$requestData['client_id'];
		$android_id=$requestData['android_id'];
		$otp=$requestData['otp'];
		$response=array();
		if ($this->login_model->getOPPValidation($client_id,$android_id,$otp)){
			$response['code']=202;
			$response['message']='Valid OTP!';
			$response['isValid']=true;
		}else{
			$response['code']=202;
			$response['message']='Wrong OPT! Please input Again!';
			$response['isValid']=false;
		}

		$this->response(json_encode($response), 202);
	}
}
