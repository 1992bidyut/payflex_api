<?php

class Payment_model extends CI_Model

{
	public function getBankList(){
		$this->db->select('*');
		$this->db->from('tbl_financial_institution_list');
		$this->db->order_by('bank_name');
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	public function getPaymentMode(){
		$this->db->select('*');
		$this->db->from('tbl_payment_mode');
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	

	public function savePayment($data){
		$this->db->insert('tbl_payment', $data);
		$Info = $this->db->insert_id();
		return $Info;
	}
	
	public function updatePayment($data){
		$this->db->where('tbl_payment.trxid', $data['trxid']);
		if ($this->db->update('tbl_payment', $data)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isReferenceExist($refNo){
		$this->db->select('*');
		$this->db->from('tbl_payment');
		$this->db->where('tbl_payment.reference_no', $refNo);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$size=count($result);
		if ($size>0){
			return true;
		}else{
			return false;
		}
	}

	public function getPaymentStatus($data){
		$this->db->select('*');
		$this->db->from('tbl_payment');
		$this->db->where('tbl_payment.trxid', $data['trxid']);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result[0]['action_flag'];
	}

	public function getPaymentList($order_code){
		$this->db->select('tbl_payment.*,
			tbl_financial_institution_list.bank_name,
			tbl_payment_mode.methode_name,tbl_payment_mode.custom_methode,
			tbl_image.trxid as img_trxid,tbl_image.image_name');
		$this->db->from("tbl_payment");
		$this->db->join('tbl_financial_institution_list','tbl_payment.financial_institution_id=tbl_financial_institution_list.id', 'left');
		$this->db->join('tbl_payment_mode','tbl_payment.payment_mode_id=tbl_payment_mode.id', 'left');
		$this->db->join('tbl_payment_image_relation','tbl_payment.id=tbl_payment_image_relation.payment_id', 'left');
		$this->db->join('tbl_image','tbl_image.id=tbl_payment_image_relation.image_id', 'left');
		$this->db->where('tbl_payment.order_code',$order_code);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		//echo print_r($result);
		//echo json_encode($result);
		return $result;
	}
	public function saveClientImagInfo($data){
		$this->db->insert('tbl_image', $data);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function savePaymentImagRelation($data2){
		$this->db->insert('tbl_payment_image_relation', $data2);
		$Info = $this->db->insert_id();
		return $Info;
	}
	
	public function updatePaymentImagRelation($data3){
		$this->db->where('tbl_payment_image_relation.payment_id', $data3['payment_id']);
		if ($this->db->update('tbl_payment_image_relation', $data3)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isPaymentImagRelationExist($pay_id){
		$this->db->select('*');
		$this->db->from('tbl_payment_image_relation');
		$this->db->where('tbl_payment_image_relation.payment_id', $pay_id);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		if (empty($result)) {
			return false;
		}else{
			return true;
		}
	}

// 	SELECT tbl_payment.*,
// tbl_financial_institution_list.bank_name,
// tbl_payment_mode.methode_name,tbl_payment_mode.custom_methode
// FROM tbl_payment
// LEFT JOIN tbl_financial_institution_list ON tbl_payment.financial_institution_id=tbl_financial_institution_list.id
// LEFT JOIN tbl_payment_mode ON tbl_payment.payment_mode_id=tbl_payment_mode.id
// WHERE order_code='15763202870030'

// SELECT 
// tbl_customer_order.*,
// order_details.txid,order_details.id as product_order_id, order_details.order_type, order_details.plant,order_details.quantityes,
// product_details.id as product_id,product_details.p_name,product_details.p_type,product_details.p_retailPrice,product_details.p_wholesalePrice,product_details.p_specialPrice
// FROM tbl_customer_order 
// LEFT JOIN order_details ON tbl_customer_order.id=order_details.customer_order_id
// LEFT JOIN product_details ON order_details.product_id=product_details.id
// where delivery_date='2019-10-17' 
// AND order_for_client_id='572'
// AND order_type='2'
}
?>
