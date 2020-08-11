<?php

class Contact_model extends CI_Model
{

	public function ExportContact($userId){

		$this->db->select('tbl_contact_basic.first_name,
					tbl_contact_basic.last_name,
					tbl_contact_basic.nick_name,
					tbl_contact_basic.gender,
					tbl_physical_contact.holding_no, 
					tbl_physical_contact.road_area, 
					tbl_physical_contact.city, 
					tbl_physical_contact.state, 
					tbl_physical_contact.zip_code, 
					tbl_physical_contact.post_code,
					tbl_virtual_contact.contact_value');
		$this->db->from('tbl_contact_basic');
		$this->db->join('tbl_virtual_contact', 'tbl_virtual_contact.contact_id=tbl_contact_basic.id', 'left');
		$this->db->join('tbl_physical_contact', 'tbl_physical_contact.contact_id=tbl_contact_basic.id', 'left');
		$this->db->where('created_by', $userId);
		$this->db->order_by('first_name', 'asc');
        $rslt = $this->db->get();
		$result = $rslt->result_array();

		if( empty($result) )	
		{
			$result = 'data not found';
		}
		return $result;
	}

    public function getContactList($userId, $parentId){

    	$data = array( 'created_by' => $userId, 'owner_id' => 0 );

    	// $data = "(created_by=".$userId." AND owner_id= 0) OR owner_id=".$parentId;

		$this->db->select('tbl_contact_basic.*, tbl_virtual_contact.contact_value, tbl_virtual_contact.contact_type_id, tbl_virtual_contact.contact_id, tbl_virtual_contact.phone_contact_id');
		$this->db->from('tbl_contact_basic');
		$this->db->where($data);
		if($parentId != 0){
			$this->db->or_where('owner_id', $parentId);	
		}else{
			$this->db->or_where('owner_id', $userId);
		}
		$this->db->join('tbl_virtual_contact', 'tbl_virtual_contact.contact_id=tbl_contact_basic.id', 'left');
		$this->db->order_by('first_name', 'asc');
        $rslt = $this->db->get();
		$result = $rslt->result_array();

		// if( empty($result) )	
		// {
		// 	$result = 'data not found';
		// }
		return $result;
	}


	public function retrieveProfile($userId, $contactId){

		$this->db->select('*');
        $this->db->from('tbl_contact_basic');
		$this->db->where('id', $contactId);
		$this->db->where('owner_id', $userId);

        $rslt = $this->db->get();
		$result = $rslt->result_array();
		
		if( empty($result) )
		{
			$result = 'data not found';
		}
		return $result;
	}

	public function retrieveAddress($contactId){

		$this->db->select('*');
        $this->db->from('tbl_physical_contact');
		$this->db->where('contact_id', $contactId);

        $rslt = $this->db->get();
		$result = $rslt->result_array();

		return $result;
	}

	public function retrieveContact($contactId){

		$this->db->select('*');
        $this->db->from('tbl_virtual_contact');
		$this->db->where('contact_id', $contactId);

        $rslt = $this->db->get();
		$result = $rslt->result_array();

		
		return $result;
	}
	

	public function retrieveRelation($contactId){

		$this->db->select('*');
        $this->db->from('tbl_relation_with_contact');
		$this->db->where('contact_id', $contactId);

        $rslt = $this->db->get();
		$result = $rslt->result_array();

		if( empty($result) )	
		{
			$result = 'data not found';
		}
		return $result;
	}

	public function retrieveProfession($contactId){

		$this->db->select('*');
        $this->db->from('tbl_profession');
		$this->db->where('contact_id', $contactId);

        $rslt = $this->db->get();
		$result = $rslt->result_array();

		if( empty($result) )	
		{
			$result = 'data not found';
		}
		
		return $result;
	}
	public function getBirthday(){
		//it will be modify
		$this->db->select('id, nick_name, created_at');
		$this->db->from('tbl_contact_basic');
		$this->db->where('owner_id', $userId);

        $rslt = $this->db->get();
		$result = $rslt->result_array();
		
		if( empty($result) )	
		{
			$result = 'data not found';
		}
		return $result;
	}
	
	public function getRelationType(){

		$this->db->select('*');
		$this->db->from('tbl_relation_type');

        $rslt = $this->db->get();
		$result = $rslt->result_array();

		return $result;
    }

	public function getVirtualContactType(){

		$this->db->select('*');
		$this->db->from('tbl_virtual_contact_type');

        $rslt = $this->db->get();
		$result = $rslt->result_array();
		
		return $result;
    }

	public function getPhysicalContactType(){

		$this->db->select('*');
		$this->db->from('tbl_physical_contact_types');

        $rslt = $this->db->get();
		$result = $rslt->result_array();
		
		return $result;
	}
	
	public function getContactType(){

		$this->db->select('*');
		$this->db->from('tbl_contact_type');

        $rslt = $this->db->get();
		$result = $rslt->result_array();
		
		return $result;
    }

	public function getCountry(){

		$this->db->select('*');
		$this->db->from('country');
		$this->db->order_by('country_name', 'ASC');

        $rslt = $this->db->get();
		$result = $rslt->result_array();
		
		if( empty($result) )	
		{
			$result = 'data not found';
		}
		return $result;
	}


	public function InsertType($table, $data){
		$this->db->insert($table, $data);
	}

	public function deleteContact($userId, $contactsId){
		$table = 'tbl_contact_basic';
		$i = 0;
		$j = sizeof($contactsId);
		$response = array();
		foreach ($contactsId as $id) {

			if($this->isOwner($table, $userId, $id)){
				$this->db->where('id', $id);
				$this->db->delete($table);
				$i++;
			}

			// else{
			// 	$response['status'] = 4400;
			// 	$response['success'] = $i;
			// 	$response['failed'] = $j - $i;
			// 	$response['message'] = 'you can not delete the contact.';
			// 	return $response;
			// }
		}

		$response['status'] = 4000;
		$response['success'] = $i;
		$response['failed'] = $j - $i;
		$response['message'] = 'you are successfully deleted contact.';

		return $response;
    }

    function isOwner($table,$userId, $id)
	{	
		$data = array('created_by' => $userId, 'id' => $id);
	    $this->db->where($data);
	    $query = $this->db->get($table);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

    public function InsertContact($data){
		$this->db->insert('tbl_contact_basic', $data);
		$Info = $this->db->insert_id();
		return $Info;
	}

	public function InsertContactInfo($table, $data){
		$this->db->insert($table, $data);
	}

	public function InsertVirtualContact($table, $data){
		$this->db->insert_batch($table, $data);
	}

	public function update($id, $data){
		$this->db->where('id', $id);
		if($this->db->update($table, $data)){
		   return true;
		}else{
		   return false;
		}
	}

	function basicInfoParser($data, $userId){
		$profile = $data['profile'];

		$parseData = array(
			'first_name' => (isset($profile['first_name']) ? $profile['first_name'] : NULL),
			'last_name' => (isset($profile['last_name']) ? $profile['last_name'] : NULL),
			'image_path' => (isset($profile['image_path']) ? $profile['image_path'] : NULL),
			'gender' => (isset($profile['gender']) ? $profile['gender'] : NULL),
			'nick_name' => (isset($profile['nick_name']) ? $profile['nick_name'] : NULL),
			'created_at' => (isset($profile['created_at']) ? $profile['created_at'] : NULL),
			'created_by' => $userId,
			'contact_type_id' => (isset($profile['contact_type_id']) ? $profile['contact_type_id'] : NULL),
			'sourceContactRefID' => (isset($profile['sourceContactRefID']) ? $profile['sourceContactRefID'] : NULL),
			'source' => (isset($profile['source']) ? $profile['source'] : NULL),
			'owner_id' => (isset($profile['owner_id']) ? $profile['owner_id'] : $userId),
			'is_private' => (isset($profile['is_private']) ? $profile['is_private'] : NULL),
		);

		return $parseData;
	}

	function physicalAddressParser($data, $contactId){
		$address = $data['address'];
		$parseData = array(
			'holding_no' => $address['holding_no'],
			'road_area' => $address['road_area'],
			'country_id' => $address['country_id'],
			'city' => $address['city'],
			'state' => $address['state'],
			'zip_code' => $address['zip_code'],
			'post_code' => $address['post_code'],
			'address_type_id' => $address['address_type_id'],
			'latitude' => $address['latitude'],
			'longitude' => $address['longitude'],
			'contact_id' => $contactId,
		);

		return $parseData;
	}

	function virtualAddressParser($data, $contactId){
		$contacts = $data['virtualContact'];

		foreach ($contacts as $contact) {
			$contactItem = array(
				'contact_value' => $contact['contact_value'],
				'contact_type_id' => $contact['contact_type_id'],
				'contact_id' => $contactId,
			);
			$parseData[] = $contactItem;
		}

		return $parseData;
	}

	function relationParser($data, $contactId){
		$relation = $data['relation'];

		$parseData = array(
			'contact_id' => $contactId,
			'relation_id' => $relation['relation_id'],
			'relation_remark' => $relation['relation_remark'],
		);

		return $parseData;
	}

	function professionParser($data, $contactId){
		$profession = $data['profession'];

		$parseData = array(
			'designation' => $profession['designation'],
			'department' => $profession['department'],
			'company' => $profession['company'],
			'contact_id' => $contactId,
		);

		return $parseData;
	}

}