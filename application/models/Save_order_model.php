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

	public function insertConvertData($data3){
		$this->db->insert('order_details', $data3);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function createdNewCustomerOrder($data4){
		$this->db->insert('tbl_customer_order', $data4);
		$Info = $this->db->insert_id();
		return $Info;
	}
	public function deletCustomerOrder($id){
		$this->db->where('id', $id);
		$Info =  $this->db->delete('tbl_customer_order');
		//echo "Deleted:".$Info;
		return $Info;
	}


}
?>