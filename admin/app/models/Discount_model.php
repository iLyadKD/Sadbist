<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#########################################################################
	This file is coded and updated by Muhammed Riyas[riyas.provab@gmail.com].
	#########################################################################
*/

class Discount_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function getData($id = ""){
		if($id != ""){
			$this->db->where("id",$id);
		}

		return $this->db->get('discounts')->row();
	}

	public function addDiscount($data,$id = 1){
		$this->db->update('discounts',$data);
		$this->db->where('id',$id);

		return 1;
	}
}