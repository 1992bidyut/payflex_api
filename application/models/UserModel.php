<?php


class UserModel extends CI_Model
{
	public function insertUserCredential($data1){
		$this->db->insert('tbl_user', $data1);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function updateUserCredential($data, $id){
		$this->db->where('tbl_user.id', $id);
		if ($this->db->update('tbl_user', $data)) {
			return true;
		}
		else {
			return false;
		}
	}
}
