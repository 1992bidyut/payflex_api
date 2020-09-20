<?php

/**
 * This controller created by Shorif, 10/07/2019
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Save_order extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('save_order_model');
		$this->load->model('update_order_model');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('order_model');//amount add
	}
	protected $rest_format   = 'application/json';

	function _perform_library_auth( $email = '', $password = NULL)
	{			
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$password=sha1($password);

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
		$orderDetails=$requestData['order_details'];

		$length = count($requestData);
		$i = 0;
		$response = array();
		//commented for custom order code
		$orderData = array(
			'taking_date' => $requestData['taking_date'],
			'delivery_date' => $requestData['delevary_date'],
			//'insert_date_time' => "",
			'order_code' => $requestData['txid'],
			'taker_id' => $requestData['taker_id'],
			'order_for_client_id' => $requestData['client_id'],
			);

		$order_index=$this->save_order_model->createdNewCustomerOrder($orderData);
		//echo $order_index;
		$orderData['id']=$order_index;

		if($order_index>0 && $order_index!=null){
		    
		    $total_amount=0;

			for ($i=0;$i<$length;$i++){
				
				$price=$this->order_model->getProductRate($requestData[$i]['product_id']);//amount add
				$ordered_amount=$requestData[$i]['quantityes']*$price[0]['p_wholesalePrice'];//amount add

				$data = array('txid' => $requestData[$i]['txid'],
					'product_id' => $requestData[$i]['product_id'],
					'quantityes' => $requestData[$i]['quantityes'],
					'client_id' => $requestData[$i]['client_id'],
					'taker_id' => $requestData[$i]['taker_id'],
					'delevary_date' => $requestData[$i]['delevary_date'],
					'plant' => $requestData[$i]['plant'],
					'taking_date' => $requestData[$i]['taking_date'],
					'order_type' => $requestData[$i]['order_type'],
					'customer_order_id'=>$order_index,
					'ordered_amount'=>$ordered_amount//amount add
				);
				
				$total_amount=$total_amount+$ordered_amount;
				$res = $this->save_order_model->insertOrderTable($data);
			}
			
			//update customer order
			$orderData['total_costs']=$total_amount;
			$this->update_order_model->updateCustomerOrderTable($orderData,$order_index);
			

			if(!empty($res) ){
				$response['message'] = "Successfully saved data";
			} else {
				$response['message'] = "Failed to save data";
				$this->save_order_model->deletCustomerOrder($order_index);
			}
		} else {
			$response['message'] = "Internal Error!";
			$this->save_order_model->deletCustomerOrder($order_index);
		}

		$this->response(json_encode($response), 202);
	}

}
