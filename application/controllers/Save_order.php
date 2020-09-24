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
		$order_Details=$requestData['order_detail'];
		$length = count($order_Details);
		$i = 0;
		$response = array();

		$orderData = array(
			'taking_date' => $requestData['taking_date'],
			'delivery_date' => $requestData['delivery_date'],
			//'insert_date_time' => "",
			'order_code' => $requestData['order_code'],
			'trxid' => $requestData['trxid'],
			'taker_id' => $requestData['taker_id'],
			'order_for_client_id' => $requestData['order_for_client_id'],
			'plant_id'=>$order_Details[0]['plant'],
			'insert_time'=>$this->getInsertTime(),
			'isEditable'=>0,
			'isSubmitted'=>1
			);

		$order_index=$this->save_order_model->createdNewCustomerOrder($orderData);
		//echo $order_index;
		$orderData['id']=$order_index;

		if($order_index>0 && $order_index!=null){
		    
		    $total_amount=0;

			for ($i=0;$i<$length;$i++){
				
				$price=$this->order_model->getProductRate($order_Details[$i]['product_id']);//amount add
				$ordered_amount=$order_Details[$i]['quantityes']*$price[0]['p_wholesalePrice'];//amount add

				$data = array('txid' => $order_Details[$i]['txid'],
					'product_id' => $order_Details[$i]['product_id'],
					'quantityes' => $order_Details[$i]['quantityes'],
					'client_id' => $order_Details[$i]['client_id'],
					'taker_id' => $order_Details[$i]['taker_id'],
					'delevary_date' => $order_Details[$i]['delevary_date'],
					'plant' => $order_Details[$i]['plant'],
					'taking_date' => $order_Details[$i]['taking_date'],
					'order_type' => $order_Details[$i]['order_type'],
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
	private function getInsertTime(){
		$getDate= date("Y-m-d H:m:s");
		$getDate = strtotime($getDate);
		$getDate = strtotime("-6 h", $getDate);
		return $getDate=date("Y-m-d H:m:s", $getDate);
	}

}
