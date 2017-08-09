<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Sms_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function save($data){
		$query = $this->db->insert('sms_templates', $data); 
      	if($query) { 
        	return $this->db->insert_id();
      	}else {
      		return false;
      	}
	}

	public function getAllData($id = ""){

		if($id != false){
			$this->db->where('sms_id',$id);
		}
		
		return $this->db->get('sms_templates')->result();
	}

	public function updateStatus($id,$status){
		$this->db->where('sms_id',$id);
		$this->db->update('sms_templates',$status);

		return $this->db->affected_rows();
	}

	public function delete($id){
		$this->db->delete('sms_templates',$id);

		return $this->db->affected_rows();
	}
}