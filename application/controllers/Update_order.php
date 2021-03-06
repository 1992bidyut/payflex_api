<?php

/**
 * This controller created by Shorif, 11/07/2019
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Update_order extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('update_order_model');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('order_model');
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

		// var_dump($requestData);
		$requestData = json_decode(file_get_contents('php://input'),true);
		$length = count($requestData);
		$i = 0;
		$res = false;
		$response = array();
		
		    $total_amount=0;
		    $order_index=$this->update_order_model->getCustomerOrderId($requestData[0]['txid']);
		    
			for ($i=0;$i<$length;$i++){
			    
			    $price=$this->order_model->getProductRate($requestData[$i]['product_id']);//amount add
				$ordered_amount=$requestData[$i]['quantityes']*$price[0]['p_wholesalePrice'];//amount add
				
				$data = array(
					'product_id' => $requestData[$i]['product_id'],
					'quantityes' => $requestData[$i]['quantityes'],
					'client_id' => $requestData[$i]['client_id'],
					'taker_id' => $requestData[$i]['taker_id'],
					'delevary_date' => $requestData[$i]['delevary_date'],
					'plant' => $requestData[$i]['plant'],
					'taking_date' => $requestData[$i]['taking_date'],
					'order_type' => $requestData[$i]['order_type'],
					'ordered_amount'=>$ordered_amount//amount add
				);

				$txID = $requestData[$i]['txid'];
				$isValidTxid = $this->update_order_model->trxId($txID);

				if($isValidTxid == true) {
					// var_dump("data:  " ,$data);
					$res = $this->update_order_model->updateOrderTable($data, $txID);
				}
				else {
					$response['message'] = "Transaction id does not match";
				}
				$total_amount=$total_amount+$ordered_amount;
			}
			
			//update customer order
			$orderData['total_costs']=$total_amount;
			$this->update_order_model->updateCustomerOrderTable($orderData,$order_index);

			if(!empty($res)){
				$response['message'] = "Successfully updated data";
				$response['total_amount'] = $total_amount;
			} else {
				$response['message'] = "Failed to updated data";
			}
		

		$this->response(json_encode($response), 202);
	}
}
