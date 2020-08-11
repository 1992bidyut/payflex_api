<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Client_login extends REST_Controller
{
	var $username;
	var $pass;

	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('login_model');
		$this->load->helper('url');
		$this->load->model('client_model');
	}

	protected $rest_format   = 'application/json';

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

	public function index_post(){
		if( $this->request->body){
			$requestData = $this->request->body;
		}else{
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'),true);
		
		$userData=$this->login_model->getUser($this->username,sha1($this->pass));
		$user_id=$userData[0]['id'];
		$clientData=$this->login_model->getClientUserInfo($user_id);
		$contact=$this->client_model->getClientContact($clientData[0]['client_id']);
		$clientData[0]['contacts']=$contact;
		$this->response(json_encode($clientData[0]),202);
	}
}
