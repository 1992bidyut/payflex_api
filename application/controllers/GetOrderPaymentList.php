<?php
/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class GetOrderPaymentList extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('payment_model');
		$this->load->helper('url');
		$this->load->model('login_model');
	}

	protected $rest_format   = 'application/json';
	var $userName;

	function _perform_library_auth( $email = '', $password = NULL)
	{			
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');
        
        $this->userPass=$password;
		$password = sha1($password);
		$this->userName=$email;

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
		$client_id=$requestData['client_id'];
		$order_code=$requestData['order_code'];
		
		$userData=$this->login_model->getUser($this->userName,sha1($this->userPass));
		$user_id=$userData[0]['id'];
		$clientData=$this->login_model->getClientUserInfo($user_id);
		$client_id=$clientData[0]['client_id'];

		$payment_list=$this->payment_model->getPaymentList($order_code);
		
		$length = count($payment_list);
		for ($i=0;$i<$length;$i++){
			 $payment_list[$i]['image_url']='http://demo.onuserver.com/payFlex/asset/images/'.((string)$client_id).'/'.$payment_list[$i]['image_name'];//demo server
//			$payment_list[$i]['image_url']='https://payflex.onukit.com/total/asset/images/'.((string)$client_id).'/'.$payment_list[$i]['image_name'];//live server
		}

		$response['code']=202;
		$response['message']='successful';
		$response['payment_list']=$payment_list;
		$this->response(json_encode($response), 202);
	}
}

?>
