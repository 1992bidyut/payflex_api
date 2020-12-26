<?php
/**
 * Created by PhpStorm.
 * User: 1992b
 * Date: 7/2/2019
 * Time: 12:08 PM
 */
class Login_model extends CI_Model{
	public function getUser($username,$password){

		$this->db->select('*');
		$this->db->from('tbl_user');
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	public function getUserInfo($employeeID){
		$this->db->select('*');
		$this->db->from('employees');
		$this->db->join('employee_info', 'employee_info.id=employees.info_id', 'right');
		$this->db->where('employees.id', $employeeID);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	public function getClientUserInfo($user_id){
		$this->db->select('tbl_user.*,
		client_info.id as client_id,
		client_info.catagory_id,
		client_info.client_code,
		client_info.virtual_account_no,
		client_info.name,
		client_info.representative_name,
		client_info.office_id,
		client_info.client_parent_id,
		client_info.created_date_time,
		client_info.latitude,
		client_info.longitude,
		client_info.is_active,
		tbl_client_employee_relation.*');
		$this->db->from('tbl_user');
		$this->db->join('client_info', 'client_info.user_id=tbl_user.id', 'left');
		$this->db->join('tbl_client_employee_relation','client_info.id=tbl_client_employee_relation.client_id','feft');
		$this->db->where('tbl_user.id', $user_id);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	public function insertDeviceMap($data){
		$this->db->insert('tbl_client_device_map', $data);
		$Info = $this->db->insert_id();
		return $Info;
	}
	public function updateDeviceMap($id,$updateDate){
		$this->db->where('tbl_client_device_map.id', $id);
		if ($this->db->update('tbl_client_device_map', $updateDate)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getAllDeviceMap($clientId){
		$this->db->select('*');
		$this->db->from('tbl_client_device_map');
		$this->db->where('tbl_client_device_map.client_id', $clientId);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}

	public function isDeviceMapExist($client_id,$android_id){
		$this->db->select();
		$this->db->from('tbl_client_device_map');
		$this->db->where('tbl_client_device_map.client_id', $client_id);
		$this->db->where('tbl_client_device_map.android_id', $android_id);
		$rslt = $this->db->get();
		$result = $rslt->row();
		if (isset($result)){
			return true;
		}else{
			return false;
		}
	}

	public function getOPPValidation($client_id,$android_id,$otp){
		$this->db->select();
		$this->db->from('tbl_client_device_map');
		$this->db->where('tbl_client_device_map.client_id', $client_id);
		$this->db->where('tbl_client_device_map.android_id', $android_id);
		$this->db->where('tbl_client_device_map.last_otp', $otp);
		$rslt = $this->db->get();
		$result = $rslt->row();
		if (isset($result)){
			return true;
		}else{
			return false;
		}
	}
}
