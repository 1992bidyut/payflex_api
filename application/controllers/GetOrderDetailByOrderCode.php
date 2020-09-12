<?php

/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class GetOrderDetailByOrderCode extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('order_model');
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
		$client_id=$requestData['client_id'];
		$order_code=$requestData['order_code'];

		// $isValidUser = $this->login_model->getUser($username, $password);
		// $response = array();

		// if(!empty($isValidUser)){
		$response['code']=202;
		$response['message']='successful';
		$response['order_details']=$this->order_model->getOrderDetailsByOrderCode($client_id,$order_code);
		// }

		$this->response(json_encode($response), 202);
	}
//
}
