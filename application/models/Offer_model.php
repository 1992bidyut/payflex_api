<?php


class Offer_model extends CI_Model{

	public function offerList(){
		$this->db->select('tbl_offer.*,tbl_image.image_name');
		$this->db->from('tbl_offer');
		$this->db->join('tbl_image', 'tbl_image.id=tbl_offer.offer_image_id', 'left');
		$this->db->where('tbl_offer.is_active', 1);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	public function insertClientOffer($data1){
		$this->db->insert('tbl_offer_client_relation', $data1);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function checkClientOfferRelation($offerId,$clientId){
		$this->db->select();
		$this->db->from('tbl_offer_client_relation');
		$this->db->where('tbl_offer_client_relation.offer_id', $offerId);
		$this->db->where('tbl_offer_client_relation.client_id', $clientId);
		$rslt = $this->db->get();
		$result = $rslt->row();
		if (isset($result)){
			return true;
		}else{
			return false;
		}
	}
}
