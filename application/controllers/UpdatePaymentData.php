<?php

/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class UpdatePaymentData extends REST_Controller
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
		$response = array();

		$isValidUser = $this->login_model->getUser($email, $password);
		
		if(empty($isValidUser)){
			$response['message']="Username or Password error";
			$response['trxid']="";
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
		$data=$requestData;
		$response = array();

		if (($this->payment_model->getPaymentStatus($data))==0) {
			if($this->payment_model->updatePayment($data)){
				$response['message']="Successsful";
				$response['trxid']=$data['trxid'];
				$this->response(json_encode($response),202);
			}else{
				$response['message']="Not Successsful";
				$response['trxid']=$data['trxid'];
				$this->response(json_encode($response),202);
			}
		}else{
			$response['message']="This payment is locked by authority";
			$response['trxid']=$data['trxid'];
			$this->response(json_encode($response),202);
		}
	}

}