<?php
/**
 * Created by PhpStorm.
 * User: 1992b
 * Date: 7/5/2019
 * Time: 9:45 PM
 */
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Client_orders extends REST_Controller{
	var $data;
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('client_model');
		$this->load->helper('url');
		$this->load->model('login_model');
	}

	protected $rest_format = 'application/json';

	function _perform_library_auth( $email = '', $password = NULL)
	{			
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

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

	public function index_get()
	{
		$username = $this->input->get_request_header('username');
		$password = $this->input->get_request_header('password');
		$employeeId = $this->input->get_request_header('codedEmployeeId');
		$designation = $this->input->get_request_header('designation');
		$date = $this->input->get_request_header('date');

		$userData=$this->login_model->getUser($username,$password);
		$response=array();
		if (empty($userData)){
			$response['message']="Invalid username or password";
			echo json_encode($response);
		}else{
			$response=$this->client_model->getClientOrderDetails($employeeId,$date);
			echo json_encode($response);
		}
	}
}
?>
