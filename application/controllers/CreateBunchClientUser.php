<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class CreateBunchClientUser extends REST_Controller{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('client_model');
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('userModel');
	}

	protected $rest_format = 'application/json';

	function _perform_library_auth( $email = '', $password = NULL)
	{
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$this->username=$email;
		$this->pass=$password;

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

	public function index_post()
	{
		$clientList=$this->client_model->getClientsList();

		foreach ($clientList as $client){
//			$userdata=array();
//			$getDate= date("Y-m-d");
//			$userdata['username']=$client['client_code']."@total";
//			$userdata['password']=sha1("abcdtotal");
//			$userdata['created_time']=$getDate;
//			$userdata['user_type']=3;
//			$insertIndex=$this->userModel->insertUserCredential($userdata);
			$client['virtual_account_no']="77072".$client['client_code'];
			$this->client_model->updateClintInfo($client,$client['id']);
		}
		echo json_encode($clientList);
	}
}
