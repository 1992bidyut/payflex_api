<?php
/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class GetProductDetails extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('payment_model');
		$this->load->helper('url');
		$this->load->model('product_model');
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

	function index_get(){
		$response['code']=202;
		$response['message']='successful';
		$response['product_list']=$this->product_model->getProductDetailsList();
		$this->response(json_encode($response), 202);
	}
}

?>