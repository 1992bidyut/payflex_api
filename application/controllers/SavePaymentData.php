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
		$this->load->model('Update_order_model');
	}

	protected $rest_format   = 'application/json';

	function _perform_library_auth( $email = '', $password = NULL)
	{			
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');
		$password = sha1($password);

		$isValidUser = $this->login_model->getUser($email, $password);
		$response=array();
		if(empty($isValidUser)){
			$response['inserted_code']=null;
			$response['message']="Login error!";
			$response['isSuccessfull']=false;
			$response['trxid']=null;
			$this->response($response, 401);
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
		if ($data['reference_no']!="" && $data['reference_no']!="111"){
			if(!$this->payment_model->isPaymentReferenceExist($data['reference_no'])){
				$id = $this->payment_model->savePayment($data);
				$flag_data=array();
				$flag_data['payment_status']=1;
				$this->Update_order_model->updatePaymentFlag($flag_data,$data['order_code']);

				$response['trxid']=$data['trxid'];
				$response['inserted_code']=$id;
				$response['message']="Payment Saved!";
				$response['isSuccessfull']=true;
				$this->response(json_encode($response),202);
			}else{
				$response['trxid']=$data['trxid'];
				$response['inserted_code']=null;
				$response['message']="Duplicate payment reference!";
				$response['isSuccessfull']=false;
				$this->response(json_encode($response),202);
			}
		}else{
			if ($data['reference_no']!="111"){
				$id = $this->payment_model->savePayment($data);
				$flag_data=array();
				$flag_data['payment_status']=1;
				$this->Update_order_model->updatePaymentFlag($flag_data,$data['order_code']);
				$response['trxid']=$data['trxid'];
				$response['inserted_code']=$id;
				$response['message']="Payment Saved!";
				$response['isSuccessfull']=true;
				$this->response(json_encode($response),202);
			}else{
				if(!$this->payment_model->isAccountBalanceExist($data['order_code'],$data['reference_no'])){
					$id = $this->payment_model->savePayment($data);
					$flag_data=array();
					$flag_data['payment_status']=1;
					$this->Update_order_model->updatePaymentFlag($flag_data,$data['order_code']);
					$response['trxid']=$data['trxid'];
					$response['inserted_code']=$id;
					$response['message']="Account balance Saved!";
					$response['isSuccessfull']=true;
					$this->response(json_encode($response),202);
				}else{
					$response['trxid']=$data['trxid'];
					$response['inserted_code']=null;
					$response['message']="Duplicate Account balance reference!";
					$response['isSuccessfull']=false;
					$this->response(json_encode($response),202);
				}
			}
		}
	}

}
