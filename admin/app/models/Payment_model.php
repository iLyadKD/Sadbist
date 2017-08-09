<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Payment_Model extends CI_Model {
	
	public function __construct(){
			 parent::__construct();
	}

	// public function fetch_charges(){
	// 	$this->db->join('api','api.api_id = payment_gateway_charges.api_id', 'left');
	// 	$this->db->join('gateway','gateway.gateway_id = payment_gateway_charges.gateway_id', 'left');
	// 	return $this->db->get('payment_gateway_charges');
	// }

	// public function delete_charges($id){
	// 	$this->db->delete('payment_gateway_charges', array('markup_id' => $id)); 
	// }
	
	// public function get_api_filtered(){
	// 		$apis = array('');
	// 		$data = $this->db->get('payment_gateway_charges')->result();
	// 		foreach ($data as $value) {
	// 			$apis[] = $value->api_id;
	// 		}
	// 		$this->db->where_not_in('api_id', $apis);
	// 		return $this->db->get('api')->result();
	// }

	// public function add_charges($data){
	// 	$this->db->insert('payment_gateway_charges', $data); 
	// }

	// public function add_charges_geo(){
	// 	$country = $this->input->post('country');
	// 	$markup = $this->input->post('markup');
		
	// 	$data= array(
	// 			'markup'=>$markup,
	// 			'country_id'=>$country,
	// 			'api_id'=>0
	// 	);
	// 	$this->db->insert('payment_gateway_charges',$data);
	// }

	// public function get_countries_filtered(){
	// 	$countries = array();
	// 	$data = $this->db->get('payment_gateway_charges')->result();
	// 	foreach ($data as $value) {
	// 		$countries[] = $value->country_id;
	// 	}
	// 	$this->db->where_not_in('country_id', $countries);
	// 	return $this->db->get('country')->result();
	// }

	// public function get_charges_by_id($id){
	// 		$this->db->select('gateway.name, payment_gateway_charges.markup, payment_gateway_charges.markup_id, api.api_name');
	// 		$this->db->where('payment_gateway_charges.markup_id', $id);
	// 		$this->db->join('gateway','gateway.gateway_id = payment_gateway_charges.gateway_id', 'left');
	// 		$this->db->join('api','api.api_id = payment_gateway_charges.api_id', 'left');
	// 		return $this->db->get('payment_gateway_charges');
	// }
		
	// public function update_charges($id,$markup){
	// 		$data = array(
	// 			'markup' => $markup
	// 		);
	// 		$this->db->where('markup_id',$id);
	// 		$this->db->update('payment_gateway_charges', $data); 
	// }
	
	
	// public function save_service_charges($data){ 
	// 		$this->db->set('created_on', 'NOW()', FALSE);
	// 		$this->db->insert('service_charges', $data);        
	// 	}
		
	// public function get_service_charges(){
	// 	$sql="select s.*, a.* from service_charges as s INNER JOIN api as a on s.api_id=a.api_id";
	// 	$qur=$this->db->query($sql);  
	// 	return $qur->result();
	// }
	
	// public function delete_service_charge($id){
	// 	$this->db->delete('service_charges', array('charge_id' => $id)); 
	// }
	
	// public function get_service_charges_by_id($eid){ 
	// 	$sql="select s.*, a.* from service_charges as s INNER JOIN api as a on s.api_id=a.api_id where charge_id='$eid'";
	// 	$qur=$this->db->query($sql);  
	// 	return $qur->row();
	// }
	
	// public function update_charge($data,$id){
	// 	$this->db->where('charge_id',$id);
	// 	$this->db->update('service_charges', $data);  
	// }
	
	// public function get_api_filtered_service(){
	// 		$apis = array('');
	// 		$data = $this->db->get('service_charges')->result();
	// 		foreach ($data as $value) {
	// 			$apis[] = $value->api_id;
	// 		}
	// 		$this->db->where_not_in('api_id', $apis);
	// 		return $this->db->get('api')->result();
	// }
	
	// public function get_apis(){
	// 		$this->db->select("*");
	// 		$this->db->from('api');
	// 		return $this->db->get()->result();
	// }

	// public function get_gateways(){
	// 		$this->db->select("*");
	// 		$this->db->from('gateway');
	// 		return $this->db->get()->result();
	// }

	// public function isServiceExists($id){
	// 	$this->db->select("*");
	// 	$this->db->where('api_id',$id);
	// 	$qur=$this->db->get('service_charges');
	// 	if($qur->num_rows()>0){
	// 		return $qur->row();
	// 	}
	// 	return array();	
	// }

	// public function isPaymentProductExists($gateway='', $api=''){
	// 	$this->db->where('gateway_id', $gateway);
	// 	$this->db->where('api_id',$api);
	// 	$query = $this->db->get('payment_gateway_charges');
	// 	if($query->num_rows() > 0){
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

	// public function gateway_status($id, $data){
	// 	$where = "markup_id = ".$id;
	// 	$this->db->update('payment_gateway_charges', $data, $where);
	// }

	// public function tax_status($id, $data){
	// 	$where = "charge_id = ".$id;
	// 	$this->db->update('service_charges', $data, $where);
	// }

}