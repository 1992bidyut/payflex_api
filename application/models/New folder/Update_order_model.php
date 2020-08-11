<?php

/**
 * This model created by Shorif, 11/07/2019
 */
class Update_order_model extends CI_Model
{
	public function updateOrderTable($data1, $username, $password){
		$this->db->insert('order_details', $data1);
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function updateProductDetails($data2, $username, $password){
		$this->db->insert('product_details', $data2);
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$Info2 = $this->db->insert_id();
		return $Info2;
	}
}