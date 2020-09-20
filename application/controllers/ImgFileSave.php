<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
// require APPPATH.'/libraries/REST_Controller.php';

class ImgFileSave extends CI_Controller
{
	private $email = "";
	
	function __construct() 
	{
		parent::__construct();				
		$this->isSuccessfulLogin = false;		
		$this->load->helper('url');
		$this->load->model('login_model');
		$this->load->model('payment_model');
	}
		
	 protected $rest_format   = 'application/json';
	 
	 //protected $methods              = array('HTTP/1.1');
	
	public function index()
	{
		 if(empty($_POST['username']) || !isset($_POST['password']))
		 {
			$resonseStatus = 4400 ;
			$resonseReason = 'Invalid format' ;
			// die('bhejal');
		 }
		 else
		 {
			 try
			 {

				$imgInfo = array();
				$relation=array();
				$myJSonDatum="";

				$username = $_POST['username'];
				$password = $_POST['password'];
				$trxid = $_POST['trxid'];
				$userID = $_POST['user_id'];
				$extension=$_POST['extension'];
				$file_detail=$_POST['file_detail'];
				$request_time=$_POST['request_time'];
				$fileType = $_POST['image_type'];
				//$order_code=$_POST['order_code'];
				$payment_id=$_POST['payment_id'];
				
				$userData=$this->login_model->getUser($username,sha1($password));
				$user_id=$userData[0]['id'];
				$clientData=$this->login_model->getClientUserInfo($user_id);
			    $client_id=$clientData[0]['client_id'];
			
				$server_url= dirname(__FILE__); 
				// die($server_url);
				//$folder_path = $server_url.'//..//..//..//recorder.onuserver.com';
				// $folder_path = '/home/demoonuserver/public_html/payFlex/asset/images';//demo server
				$folder_path = '/srv/users/serverpilot/apps/payflex/public/total/asset/images';//live sev=rver
				//check if is_dir returns valid path for our folder path so far. 
				
				$file_path = $folder_path .'/'. ((string)$client_id);	

				//create user name filepath and check if already exists, otherwise create

				//Checking if folder already exist----------------------------------------
				if (!file_exists($file_path )) 
				{
					mkdir($file_path , 0777, true);
					
				}

				//create device id file path , check already exists and then create if necessary. 
				$devide = explode('.',$_FILES['uploaded_file']['name']);
				$uploadTo = $file_path .'/'. $trxid . '.'.$devide[1];
					
				$resonseStatus = 4400 ;
				$resonseReason = 'Failed to upload' ;

				$responseContent=array();
				
				
				if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$uploadTo))	
				{	
					$resonseStatus = 202 ;
					$request_file_size = $_FILES['uploaded_file']['size'];
					$file_size = filesize($uploadTo);
					$fileExists = file_exists($uploadTo);
					
					$imgInfo['trxid']=$trxid;
					$imgInfo['image_name']=$trxid.".jpg";
					$imgInfo['user_id']=$userID;
					$imgInfo['request_time']=$request_time;
					$imgInfo['upload_time']=$request_time;
					$imgInfo['image_discription']=$file_detail;				
					$imgInfo['image_type_id']=$fileType;

					$relation['image_id']=$this->payment_model->saveClientImagInfo($imgInfo);
					$relation['user_id']=$userID;
					$relation['payment_id']=$payment_id;

					if ($this->payment_model->isPaymentImagRelationExist($payment_id)) {
						$this->payment_model->updatePaymentImagRelation($relation);
						$resonseReason = 'Image File Updated';
					}else{
						$this->payment_model->savePaymentImagRelation($relation);
						$resonseReason = 'File uploaded';
					}

					$responseContent['status'] = $resonseStatus;
					$responseContent['info'] = $resonseReason;
					$responseContent['file_name'] = $trxid;
					$responseContent['client_id'] = $client_id;
	                $responseContent['request_file_size'] = $request_file_size;
					$responseContent['file_size'] = $file_size;
					$responseContent['file_exists'] = $fileExists;
				}else{
					$responseContent['status'] = 302;
					$responseContent['info'] = "file not uploaded";
					$responseContent['file_name'] = $trxid;
					$responseContent['client_id'] = $client_id;
	                $responseContent['request_file_size'] = 0;
					$responseContent['file_size'] = 0;
					$responseContent['file_exists'] = false;
				}
				
				//array_push($sms_array,$responseContent);
				$myJSonDatum = json_encode($responseContent);
				
			 }
			 catch(Exception $ex)
			 {
			 }	
		 }
		 echo $myJSonDatum;
		 //$this->response($myJSonDatum, 202);
		 //print_r($myJSonDatum); // OK (200) being the HTTP response code
		// die;
	}
	
}