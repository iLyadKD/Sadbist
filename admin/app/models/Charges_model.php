<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Charges_Model extends CI_Model {
	
	public function __construct(){
			 parent::__construct();
	}

	public function get_all_pg($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `payment_gateways`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response["count"] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response["result"] = $result->result_array();
		}
		return $response;
	}

	public function is_payment_gateway_exists($data, $except = null)
	{
		$excepts = "";
		if(!is_null($except))
			$excepts = " AND `title` <> '$except'";
		$query = "SELECT * FROM `payment_gateways` WHERE `title` = '".$data["title"]."'".$excepts;
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_payment_gateway($id)
	{
		$query = "SELECT * FROM `payment_gateways` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_payment_gateway($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `payment_gateways` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_payment_gateway($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `payment_gateways` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_payment_gateway($id)
	{
		$query = "DELETE FROM `payment_gateways` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_all_pg_charges($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `payment_charges`.*, `title`, `api_name`, `payment_gateways`.`pay_mode` AS `pg_pay_mode` FROM `payment_charges` INNER JOIN `payment_gateways` ON `payment_gateways`.`id` = `payment_charges`.`pg_id` LEFT JOIN `api` ON `payment_charges`.`api` = `api`.`id`) AS `payment_charges`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response["count"] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response["result"] = $result->result_array();
		}
		return $response;
	}

	public function is_pg_charge_exists($data)
	{
		$api_cdtn = !is_null($data["api"]) ? " AND `api` = '".$data["api"]."'" : " AND `api` IS NULL";
		$query = "SELECT * FROM `payment_charges` WHERE `pg_id` = '".$data["pg_id"]."' $api_cdtn";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_pg_charge($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "NULL, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `payment_charges` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_pg_charge($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `payment_charges` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_pg_charge($id)
	{
		$query = "DELETE FROM `payment_charges` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_pg_charge($id)
	{
		$query = "SELECT * FROM (SELECT `payment_charges`.*, `title`, `api_name`, `payment_gateways`.`pay_mode` AS `pg_pay_mode` FROM `payment_charges` INNER JOIN `payment_gateways` ON `payment_gateways`.`id` = `payment_charges`.`pg_id` LEFT JOIN `api` ON `payment_charges`.`api` = `api`.`id`) AS `payment_charges` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_all_tax($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `taxes`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response["count"] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response["result"] = $result->result_array();
		}
		return $response;
	}

	public function is_tax_exists($data, $except = null)
	{
		$excepts = "";
		if(!is_null($except))
			$excepts = " AND `title` <> '$except'";
		$query = "SELECT * FROM `taxes` WHERE `title` = '".$data["title"]."'".$excepts;
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_tax($id)
	{
		$query = "SELECT * FROM `taxes` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_tax($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `taxes` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_tax($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `taxes` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_tax($id)
	{
		$query = "DELETE FROM `taxes` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_all_tax_charges($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `tax_charges`.*, `title`, `api_name`, `taxes`.`pay_mode` AS `tax_pay_mode` FROM `tax_charges` INNER JOIN `taxes` ON `taxes`.`id` = `tax_charges`.`tax_id` LEFT JOIN `api` ON `tax_charges`.`api` = `api`.`id`) AS `tax_charges`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response["count"] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response["result"] = $result->result_array();
		}
		return $response;
	}

	public function is_tax_charge_exists($data)
	{
		$api_cdtn = !is_null($data["api"]) ? " AND `api` = '".$data["api"]."'" : " AND `api` IS NULL";
		$query = "SELECT * FROM `tax_charges` WHERE `tax_id` = '".$data["tax_id"]."' $api_cdtn";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_tax_charge($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "NULL, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `tax_charges` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_tax_charge($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `tax_charges` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_tax_charge($id)
	{
		$query = "DELETE FROM `tax_charges` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_tax_charge($id)
	{
		$query = "SELECT * FROM (SELECT `tax_charges`.*, `title`, `api_name`, `taxes`.`pay_mode` AS `tax_pay_mode` FROM `tax_charges` INNER JOIN `taxes` ON `taxes`.`id` = `tax_charges`.`tax_id` LEFT JOIN `api` ON `tax_charges`.`api` = `api`.`id`) AS `tax_charges` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
}