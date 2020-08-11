<?php
/**
 * Created by PhpStorm.
 * User: 1992b
 * Date: 9/8/2019
 * Time: 12:42 PM
 */
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class ConvertingOrders extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('save_order_model');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('order_model');
	}

	protected $rest_format = 'application/json';

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
		$requestData = json_decode(file_get_contents('php://input'),true);

		$date = $requestData['date'];

		$today= date('mdY', time());

		//$isValidUser = $this->login_model->getUser($username, $password);

			$requestData =$this->order_model->getTodayForecast($date, 1);
			$length = count($requestData);

			for ($i=0;$i<$length;$i++){

				$data = array('txid' => $requestData[$i]['txid'].$date,
					'product_id' => $requestData[$i]['product_id'],
					'quantityes' => $requestData[$i]['quantityes'],
					'client_id' => $requestData[$i]['client_id'],
					'taker_id' => $requestData[$i]['taker_id'],
					'delevary_date' => $requestData[$i]['delevary_date'],
					'plant' => $requestData[$i]['plant'],
					'taking_date' => $requestData[$i]['taking_date'],
					'order_type' => 2,
					'customer_order_id' => $requestData[$i]['customer_order_id'],
					'ordered_amount'=>$requestData[$i]['ordered_amount']//amount add
				);

				$check=$this->order_model->getProductBytxid($requestData[$i]['txid'].$date,2);
				if (empty($check)){
					$res = $this->save_order_model->insertOrderTable($data);
				}else{
					$response['message'] = "already converted";
				}
			}

			if(!empty($res) ){
				$response['message'] = "Successfully convert data";
			} else {
				$response['message'] = "Failed to convert data";
			}
		echo json_encode($response);
	}

}
