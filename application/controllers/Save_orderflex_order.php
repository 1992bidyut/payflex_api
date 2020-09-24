<?php

/**
 * This controller created by Shorif, 10/07/2019
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Save_orderflex_order extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('save_order_model');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('order_model');//amount add
		$this->load->model('update_order_model');
		$this->load->model('Client_model');
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
		$order_detail=$requestData['order_detail'];
		$customer_order=$requestData['customer_order'];

		$length = count($order_detail);//
		$i = 0;
		$response = array();

////		if ($this->order_model->isTodaysOrderExist($customer_order['order_for_client_id'],$customer_order['delivery_date'])){
////			$response['message'] = "Already order taken";
////		}else
//			{
			//make order code for OrderFlex orders
		$client_details=$this->Client_model->getClientDetailsByID($customer_order['order_for_client_id']);
		$client_code=$client_details['client_code'];
//		echo $client_code;
		$code=$client_code."-".$this->getServerDate().'-1';
		$customer_order['trxid']=$customer_order['order_code'];
		$customer_order['order_code']=$code;
		$customer_order['plant_id']=$order_detail[0]['plant'];
		$customer_order['insert_time']=$this->getInsertTime();
		$customer_order['isEditable']=1;
		$customer_order['isSubmitted']=0;
		$order_index=$this->save_order_model->createdNewCustomerOrder($customer_order);
			//echo $order_index;

			$total_amount=0;

			if ($order_index>0 && $order_index!=null) {
				for ($i=0;$i<$length;$i++){
					$price=$this->order_model->getProductRate($order_detail[$i]['product_id']);//amount add
					$ordered_amount=$order_detail[$i]['quantityes']*$price[0]['p_wholesalePrice'];//amount add
					$data = array('txid' => $order_detail[$i]['txid'],
						'product_id' => $order_detail[$i]['product_id'],
						'quantityes' => $order_detail[$i]['quantityes'],
						'client_id' => $order_detail[$i]['client_id'],
						'taker_id' => $order_detail[$i]['taker_id'],
						'delevary_date' => $order_detail[$i]['delevary_date'],
						'plant' => $order_detail[$i]['plant'],
						'taking_date' => $order_detail[$i]['taking_date'],
						'order_type' => $order_detail[$i]['order_type'],
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
			}else{
				$response['message'] = "Failed to save data";
				$this->save_order_model->deletCustomerOrder($order_index);
			}
//		}

		$this->response(json_encode($response), 202);
	}

	private function getServerDate(){
		$getDate= date("Y-m-d H:m:s");
		$getDate = strtotime($getDate);
		$getDate = strtotime("-6 h", $getDate);
		return $getDate=date("dmy", $getDate);
	}

	private function getInsertTime(){
		$getDate= date("Y-m-d H:m:s");
		$getDate = strtotime($getDate);
		$getDate = strtotime("-6 h", $getDate);
		return $getDate=date("Y-m-d H:m:s", $getDate);
	}

}
