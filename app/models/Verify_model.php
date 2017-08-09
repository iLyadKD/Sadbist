<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Verify_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function check_b2c_verfication($b2c_id) {
		$this->db->where('id', $b2c_id);
		return $this->db->get('b2c_verification');
	}

	public function update_b2c_verfication($b2c_id,$update){
		$this->db->where('id', $b2c_id);
		return $this->db->update('b2c_verification',$update);
	}

	public function check_b2b_verfication($b2b_id) {
		$this->db->where('id', $b2b_id);
		return $this->db->get('b2b_verification');
	}

	public function update_b2b_verfication($b2b_id,$update){
		$this->db->where('id', $b2b_id);
		return $this->db->update('b2b_verification',$update);
	}
}