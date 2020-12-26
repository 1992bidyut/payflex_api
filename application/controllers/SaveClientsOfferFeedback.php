<?php
/**
 * This controller created by Bidyut, 04/04/2020
 */

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class SaveClientsOfferFeedback extends REST_Controller
{
	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('ApplicationModel');
		$this->load->helper('url');
		$this->load->model('offer_model');
	}

	protected $rest_format = 'application/json';
	var $userName;

	function _perform_library_auth($email = '', $password = NULL)
	{
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$this->userPass = $password;
		$password = sha1($password);
		$this->userName = $email;

		$isValidUser = $this->login_model->getUser($email, $password);

		if (empty($isValidUser)) {
			$response = array();

			$response['code'] = 202;
			$response['message'] = 'Username or Password error!';
			$response['data'] = null;
			$this->response(json_encode($response), 401);
			return false;
		} else {
			return true;
		}
	}

	function index_post()
	{

		if ($this->request->body) {
			$requestData = $this->request->body;
		} else {
			$requestData = $this->input->post();
		}
		$requestData = json_decode(file_get_contents('php://input'), true);
		$insertIndex=$this->offer_model->insertClientOffer($requestData);
		if (isset($insertIndex)){
			$response['code'] = 202;
			$response['message'] = 'Data successfully submitted!';
			$response['data'] = null;
			$this->response(json_encode($response), 202);
		}else{
			$response['code'] = 202;
			$response['message'] = 'Not submitted!';
			$response['data'] = null;
			$this->response(json_encode($response), 202);
		}
	}
}
