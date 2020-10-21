<?php
/**
 * Created by PhpStorm.
 * User: 1992b
 * Date: 7/15/2019
 * Time: 11:43 AM
 */
class Product_model extends CI_Model {

public function getProductDetailsList(){
		$this->db->select('product_details.*,
			product_type.type,
			tbl_product_price.p_retailPrice,
			tbl_product_price.p_wholesalePrice,
			tbl_product_price.p_specialPrice');
		$this->db->from('product_details');
		$this->db->JOIN('product_type','product_details.p_type=product_type.id','left');
		$this->db->JOIN('tbl_product_price','product_details.id=tbl_product_price.product_id','left');
		$this->db->where('tbl_product_price.is_active','1');
		$this->db->order_by('product_details.id');
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}
	
}

?>