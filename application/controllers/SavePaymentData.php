<?php

/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class SavePaymentData extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('payment_model');
		$this->load->helper('url');
		$this->load->model('login_model');
	}

	protected $rest_format   = 'application/json';

	function _perform_library_auth( $email = '', $password = NULL)
	{			
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

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

	function index_post(){

		if( $this->request->body){
			$requestData = $this->request->body;
		}else{
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'),true);

		//echo $requestData;
		// $username = $this->input->get_request_header('username');
		// $password = $this->input->get_request_header('password');
		// $username=$requestData['username'];
		// $password=$requestData['password'];
		$data=$requestData;

		// $isValidUser = $this->login_model->getUser($username, $password);
		$response = array();

		// if(!empty($isValidUser)){
			$id = $this->payment_model->savePayment($data);
			$response['trxid']=$data['trxid'];
			$response['inserted_code']=$id;
			$this->response(json_encode($response),202);
		//}

		//$this->response(json_encode($response), 200);
	}

}