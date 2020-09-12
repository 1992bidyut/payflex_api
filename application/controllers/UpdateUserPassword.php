<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class UpdateUserPassword extends REST_Controller{
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
			$res['message']="Username or Password error!";
			$res['code']=202;
			$res['data']=null;
			$this->response($res, 401);
			return false;
		}
		else{
			return true;
		}
	}

	public function index_post()
	{
		if( $this->request->body){
			$requestData = $this->request->body;
		}else{
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'),true);

		$username=$requestData['username'];
		$password=$requestData['password'];
		$newPassword=$requestData['newPassword'];
		$re_newPassword=$requestData['re_newPassword'];

		$isValidUser = $this->login_model->getUser($username, sha1($password));
//		echo print_r($isValidUser);
		$res=array();
		if (empty($isValidUser) || $newPassword!=$re_newPassword){
			$res['message']="Information error!";
			$res['code']=202;
			$res['data']=$requestData;
		}else{
			$updateData=array();
			$updateData['password']=sha1($newPassword);
			if ($this->userModel->updateUserCredential($updateData,$isValidUser[0]['id'])){
				$res['message']="Your password has been changed!";
				$res['code']=202;
				$res['data']=$requestData;
			}else{
				$res['message']="Your password not changed!";
				$res['code']=202;
				$res['data']=$requestData;
			}
		}
		$this->response($res,202);
	}
}
