<?php

/**
 * This model created by Shorif, 10/07/2019
 */
class Save_order_model extends CI_Model
{

	public function insertOrderTable($data1){
		$this->db->insert('order_details', $data1);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function insertProductDetails($data2){
		$this->db->insert('product_details', $data2);
		$Info2 = $this->db->insert_id();
		return $Info2;
	}

}

?>