<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#########################################################################
	This file is coded and updated by Muhammed Riyas[riyas.provab@gmail.com].
	#########################################################################
*/

class Special_deal_Model extends CI_Model {
	
	public function getAllItems(){
		$this->db->order_by('id','DESC');
		return $this->db->get('special_deal_request')->result();
	}
	
}