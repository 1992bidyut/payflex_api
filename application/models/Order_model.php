<?php
/**
 * Created by PhpStorm.
 * User: 1992b
 * Date: 7/15/2019
 * Time: 11:43 AM
 */
class Order_model extends CI_Model {
	public function getTotalForecast($employeeId,$start_date,$end_date){
		$m_response=array();
		$clientList=$this->getClientsWithOfficers($employeeId);
		$count=0;
		foreach ($clientList as $item){
			$m_response[$count]=$item;
			$m_response[$count]['forecast']=$this->getClientOrderSum($item['clients_id'],1,$start_date,$end_date);
			//$m_response[$count]['schedule']=$this->getClientOrderSum($item['id'],2,$date);
			$count++;
		}
		//echo json_encode($m_response);
		//return $clientList;
		return $m_response;
	}
	public function getTotalSchedule($employeeId,$start_date,$end_date){
		$m_response=array();
		$clientList=$this->getClientsWithOfficers($employeeId);
		$count=0;
		foreach ($clientList as $item){
			$m_response[$count]=$item;
			$m_response[$count]['schedule']=$this->getClientOrderSum($item['clients_id'],2,$start_date,$end_date);
			//$m_response[$count]['schedule']=$this->getClientOrderSum($item['id'],2,$date);
			$count++;
		}
		//echo json_encode($m_response);
		//return $clientList;
		return $m_response;
	}

	public function getClientsWithOfficers($employeeId){
		$this->db->select('label1.id AS label1_id,
		label1_info.name as label1_name,
		label2.id as label2_id,
		label2_info.name as label2_name,
		label3.id AS label3_id,
		label3_info.name as label3_name,
		label4.id AS label4_id,
		label4_info.name as label4_name,
		clients.id as clients_id,
		clients.client_code,
		clients.name');

		$this->db->from('`employees` as label2');
		$this->db->join('employee_info as label2_info','label2_info.id=label2.info_id', 'left');

		$this->db->join('employees as label1','label1.id = label2.parent_id', 'left');
		$this->db->join('employee_info as label1_info','label1_info.id=label1.info_id', 'left');

		$this->db->join('employees as label3','label3.parent_id = label2.id', 'left');
		$this->db->join('employee_info as label3_info','label3_info.id=label3.info_id', 'left');

		$this->db->join('employees as label4','label4.parent_id = label3.id', 'left');
		$this->db->join('employee_info as label4_info','label4_info.id=label4.info_id', 'left');

		$this->db->join('client_info AS clients','clients.handler_id=label4.id', 'left');

		$this->db->where('label2.parent_id', $employeeId);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		//echo print_r($result);
		//echo json_encode($result);
		return $result;
	}

	private function getClientOrderSum($id,$orderType,$start_date,$end_date){
		$productList=$this->getProductList();
		$forecastOrder=array();
		$counter=0;
		foreach ($productList as $proItem){
				$productArray=array(
					"product_name"=>$proItem['p_name'],
					"product_type"=>$proItem['p_type'],
					"product_id"=>$proItem['id'],
					"order_id"=>"",
					"txid"=>"",
					"client_id"=>$id,
					"taker_id"=>"",
					"delevary_date"=>"",
					"plant"=>"",
					"taking_date"=>"",
					"order_type"=>"",
					"quantityes"=>0
				);
			$forecastOrder[$counter]=$productArray;
			$counter++;
			}

		$clientOrderList = $this->getClientOrders($id,$orderType,$start_date,$end_date);
		foreach ($clientOrderList as $orderItem){
			$productId=$orderItem['product_id'];
			$quan=$orderItem['quantityes'];
			$counter=0;
			foreach ($forecastOrder as $forOrderItem)
			{
				if ($forecastOrder[$counter]['product_id']==$productId){
					if (!empty($quan)){
						$forecastOrder[$counter]['quantityes']+=$quan;
						$forecastOrder[$counter]['txid']=$orderItem['txid'];
						$forecastOrder[$counter]['client_id']=$orderItem['client_id'];
						$forecastOrder[$counter]['taker_id']=$orderItem['taker_id'];
						$forecastOrder[$counter]['delevary_date']=$orderItem['delevary_date'];
						$forecastOrder[$counter]['plant']=$orderItem['plant'];
						$forecastOrder[$counter]['taking_date']=$orderItem['taking_date'];
						$forecastOrder[$counter]['order_id']=$orderItem['id'];
						$forecastOrder[$counter]['order_type']=$orderItem['order_type'];
					}
				}
				$counter++;
			}
		}
		return $forecastOrder;
	}

	public function getPlants(){
		$this->db->select('*');
		$this->db->from('plant_detail');
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		return $result;
	}

	public function getClientOrders($id,$orderType,$start_date,$end_date){
		$this->db->select('*');
		$this->db->from('order_details');
		$this->db->where('order_details.client_id', $id);
		$this->db->where('order_details.order_type',$orderType);
		
// 		$this->db->where('taking_date >=', $start_date);
// 		$this->db->where('taking_date <=', $end_date);

//        if ($orderType==1){
//        			$this->db->where('taking_date >=', $start_date);
//        			$this->db->where('taking_date <=', $end_date);
//        		}
//        if ($orderType==2){
        		$this->db->where('delevary_date >=', $start_date);
        		$this->db->where('delevary_date <=', $end_date);
//        	}
        		
		//$this->db->join('plant_detail','plant_detail.id=order_details.plant', 'left');
		
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		return $result;
	}

	private function getProductList(){
		$this->db->select('*');
		$this->db->from('product_details');
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		return $result;
	}


	public  function getTodayForecast($date, $orderType){
		//echo $date.";".$orderType;
		$this->db->select('*');
		$this->db->from('order_details');
		$this->db->where('order_details.order_type',$orderType);
		$this->db->where('order_details.delevary_date', $date);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		return $result;
	}

	public function getProductBytxid($txid,$orderType){
		$this->db->select('*');
		$this->db->from('order_details');
		$this->db->where('order_details.order_type',$orderType);
		$this->db->where('order_details.txid', $txid);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		return $result;
	}

	public function getProductRate($product_id){//amount add
		$this->db->select('*');
		$this->db->from('tbl_product_price');
		$this->db->where('tbl_product_price.product_id',$product_id);
		$this->db->where('tbl_product_price.is_active', '1');
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		return $result;

	}

	public function getOrderDetailsByOrderCode($client_id,$order_code){
		$this->db->select('tbl_customer_order.*,
			order_details.txid,
			order_details.id as product_order_id, 
			order_details.order_type, 
			order_details.plant,
			plant_detail.plant as plantName,
			order_details.quantityes,
			product_details.id as product_id,
			product_details.p_name,
			product_details.p_type,
			tbl_product_price.p_retailPrice,
			tbl_product_price.p_wholesalePrice,
			tbl_product_price.p_specialPrice,
			product_details.p_discription');
		$this->db->from('`tbl_customer_order`');
		$this->db->join('order_details','tbl_customer_order.id=order_details.customer_order_id', 'left');
		$this->db->join('product_details','order_details.product_id=product_details.id', 'left');
		$this->db->join('tbl_product_price','tbl_product_price.product_id=product_details.id', 'left');
		$this->db->join('plant_detail','order_details.plant=plant_detail.id', 'left');
		$this->db->where('order_for_client_id', $client_id);
		$this->db->where('order_code', $order_code);
		$this->db->where('order_type', 2);
		$this->db->where('tbl_product_price.is_active', 1);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		//echo print_r($result);
		//echo json_encode($result);
		return $result;
	}

	public function getClientOrderDetails($client_id,$delivery_date){
		$this->db->select('tbl_customer_order.*,
			order_details.txid,
			order_details.id as product_order_id, 
			order_details.order_type, 
			order_details.plant,
			order_details.quantityes,
			product_details.id as product_id,
			product_details.p_name,
			product_details.p_type,
			tbl_product_price.p_retailPrice,
			tbl_product_price.p_wholesalePrice,
			tbl_product_price.p_specialPrice,
			product_details.p_discription');
		$this->db->from('`tbl_customer_order`');
		$this->db->join('order_details','tbl_customer_order.id=order_details.customer_order_id', 'left');
		$this->db->join('product_details','order_details.product_id=product_details.id', 'left');
		$this->db->join('tbl_product_price','tbl_product_price.product_id=product_details.id', 'left');
		$this->db->where('order_for_client_id', $client_id);
		$this->db->where('delivery_date', $delivery_date);
		$this->db->where('order_type', 2);
		$this->db->where('tbl_product_price.is_active', 1);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		//echo print_r($result);
		//echo json_encode($result);
		return $result;
	}

	public function getOnlyOrderList($client_id,$start_date,$end_date){
		$query="SELECT * 
		FROM tbl_customer_order 
		WHERE order_for_client_id= '".$client_id."' 
		and delivery_date>= '".$start_date."'
		and delivery_date <= '".$end_date."'";
		$resource = $this->db->query($query);
		// echo $this->db->last_query();
		// die();
		return $resource->result_array();
	}
	public function checkDuplicateOrder($id){
		$this->db->select('*');
		$this->db->from('tbl_customer_order');
		$this->db->where('tbl_customer_order.id',$id);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		return $result;
	}
	public function isTodaysOrderExist($client_id,$date){
		$this->db->select('*');
		$this->db->from('tbl_customer_order');
		$this->db->where('tbl_customer_order.order_for_client_id',$client_id);
		$this->db->where('tbl_customer_order.delivery_date',$date);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		$que=$this->db->last_query();
		if (empty($result)){
			return false;
		}else{
			return true;
		}
	}

	public function isEditAllowed($id){
		$this->db->select('*');
		$this->db->from('tbl_customer_order');
		$this->db->where('tbl_customer_order.id', $id);
		$this->db->where('tbl_customer_order.isEditable', 1);
		$rslt = $this->db->get();
		$result = $rslt->result_array();
		if (!empty($result)) {
			return true;
		}else{
			return false;
		}
	}

	public function updateCustomerSubmit($orderCode){
		$data=array();
		$data['isSubmitted']=1;
		$data['isEditable']=0;
		$this->db->where('tbl_customer_order.order_code', $orderCode);
		if ($this->db->update('tbl_customer_order', $data)) {
			return true;
		}
		else {
			return false;
		}
	}

}
/*SELECT
label1.id AS label1_id,
label1_info.name as label1_name,

label2.id as label2_id,
label2_info.name as label2_name,

label3.id AS label3_id,
label3_info.name as label3_name,

label4.id AS label4_id,
label4_info.name as label4_name,

clients.id as clients_id,
clients.client_code,
clients.name

FROM `employees` as label2
LEFT JOIN employee_info as label2_info ON label2_info.id=label2.info_id

LEFT JOIN employees as label1 ON label1.id = label2.parent_id
LEFT JOIN employee_info as label1_info ON label1_info.id=label1.info_id

LEFT JOIN employees as label3 ON label3.parent_id = label2.id
LEFT JOIN employee_info as label3_info ON label3_info.id=label3.info_id

LEFT JOIN employees as label4 ON label4.parent_id = label3.id
LEFT JOIN employee_info as label4_info ON label4_info.id=label4.info_id

LEFT JOIN client_info AS clients ON clients.handler_id=label4.id

WHERE label2.parent_id=16*/

//
//SELECT table1.*
//FROM order_details as table1
//LEFT JOIN order_details as table2 ON table2.client_id=table1.client_id
//WHERE table2.product_id != table1.product_id

/*SELECT table1.*, product_details.p_name, product_details.p_type
FROM order_details as table1
LEFT JOIN order_details as table2 ON table2.product_id != table1.product_id
JOIN product_details ON product_details.id = table1.product_id
WHERE table1.product_id !=0
GROUP BY table1.client_id
ORDER BY product_id*/
?>
