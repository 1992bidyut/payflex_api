<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class GetOffers extends REST_Controller
{
	var $username;
	var $pass;
	var $userId;

	function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('login_model');
		$this->load->helper('url');
		$this->load->model('offer_model');
	}
	protected $rest_format = 'application/json';

	function _perform_library_auth($email = '', $password = NULL)
	{
		$CI = get_instance();
		$CI->load->library('encrypt');
		$CI->load->model('login_model');

		$this->username = $email;
		$this->pass = $password;

		$password = sha1($password);

		$isValidUser = $this->login_model->getUser($email, $password);
		$this->userId=$isValidUser[0]['id'];

		if(empty($isValidUser)){
			$response=array();
			$response['code']=401;
			$response['message']='Username or Password error!';
			$response['data']=null;
			$this->response(json_encode($response), 401);
			return false;
		}
		else{
			return true;
		}
	}

	public function index_get(){
		$clientDetail=$this->login_model->getClientUserInfo($this->userId);
		$clientId=$clientDetail[0]['client_id'];
		$response=array();
		$response['code']=202;
		$response['message']='Successful!';
		$offers=$this->offer_model->offerList();
		$offerList=array();
		$counter=0;
		if (!empty($offers)){
			for ($i=0;$i<count($offers);$i++){
//				$offers[$i]['image_url']='http://demo.onuserver.com/payFlex/asset/images/offers/'.$offers[$i]['image_name'];//demo server
				$offers[$i]['image_url']='https://payflex.onukit.com/total/asset/images/offers/'.$offers[$i]['image_name'];//live server
				if (!$this->offer_model->checkClientOfferRelation($offers[$i]['id'],$clientId)){
					$offerList[$counter]=$offers[$i];
					$counter++;
				}
			}
		}
		$response['data']=$offerList;
		$this->response(json_encode($response), 202);
	}
}
