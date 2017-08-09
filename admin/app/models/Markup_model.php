<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Markup_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_markup_types($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `markup_types`.*, IFNULL(`name`, 'General') AS `name` FROM `markup_types` LEFT JOIN 
			`user_types` ON `user_type` = `user_types`.`id`) AS `markup_types`". $where. $order_by;
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

	public function get_markup_type($id)
	{
		$query = "SELECT * FROM `markup_types` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_type($data)
	{
		$markup_type_condition = is_null($data["user_type"]) ? "`user_type` IS NULL" : "`user_type` = 
		'".$data["user_type"]."'";
		$query = "UPDATE `markup_types` SET `markup_priority` = `markup_priority` + 1 WHERE 
		`markup_priority` >= '".$data["markup_priority"]."' AND $markup_type_condition";
		$this->db->query($query);
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "NULL, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `markup_types`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_type($condition, $data)
	{
		if($condition["type"] !== $data["user_type"])
		{
			$markup_type_condition = is_null($condition["type"]) ? "`user_type` IS NULL" : "`user_type` = 
			'".$condition["type"]."'";
			$query = "UPDATE `markup_types` SET `markup_priority` = `markup_priority` - 1 WHERE 
			`markup_priority` > '".$condition["priority"]."' AND $markup_type_condition";
			$this->db->query($query);

			$markup_type_condition = is_null($data["user_type"]) ? "`user_type` IS NULL" : "`user_type` = 
			'".$data["user_type"]."'";
			$query = "UPDATE `markup_types` SET `markup_priority` = `markup_priority` + 1 WHERE 
			`markup_priority` >= '".$data["markup_priority"]."' AND $markup_type_condition";
			$this->db->query($query);
		}
		else
		{

			$inc_dec = intval($data["markup_priority"]) > intval($condition["priority"]) ? "-" : "+";
			$min_value = intval($data["markup_priority"]) > intval($condition["priority"]) ? $condition["priority"] : $data["markup_priority"];
			$max_value = intval($data["markup_priority"]) < intval($condition["priority"]) ? $condition["priority"] : $data["markup_priority"];

			$markup_type_condition = is_null($data["user_type"]) ? "`user_type` IS NULL" : "`user_type` = 
			'".$data["user_type"]."'";
			$query = "UPDATE `markup_types` SET `markup_priority` = `markup_priority` $inc_dec '1' WHERE 
			`markup_priority` >= '".$min_value."' AND `markup_priority` <= '".$max_value."' AND $markup_type_condition";
			$this->db->query($query);
		}
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = ".(is_null($value) ? "NULL" : "'".$value."'").", ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `markup_types` SET $set WHERE `id` = '".$condition["id"]."'";
		return $this->db->query($query);
	}

	public function delete_type($condition)
	{
		$markup_type_condition = is_null($condition["type"]) ? "`user_type` IS NULL" : "`user_type` = 
		'".$condition["type"]."'";
		$query = "UPDATE `markup_types` SET `markup_priority` = `markup_priority` - '1' WHERE 
		`markup_priority` > '".$condition["priority"]."' AND $markup_type_condition";
		$this->db->query($query);
		$query = "DELETE FROM `markup_types` WHERE `id` = '".$condition["id"]."'";
		return $this->db->query($query);
	}

	public function is_markup_exists($data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`".$key."` IS NULL AND " : "`".$key."` = '".$value."' AND ";
		$set = substr_replace($set, "", -5);
		$query = "SELECT * FROM `markups` WHERE $set";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function add_markup($data)
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
		$query = "INSERT INTO `markups`($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_markup($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `markups` SET ".$set." WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_markup($id)
	{
		$query = "DELETE FROM `markups` WHERE `id` = '".$id."'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	public function get_b2c_markups($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `markups`.*, `markup_types`.`user_type`, `markup_name`, `api_name`, `country_en`, `airline_en` FROM `markups` INNER JOIN `markup_types` ON `markup_types`.`id` = `mt_id` AND (`markup_types`.`user_type` IS NULL OR `markup_types`.`user_type` = '".B2C_USER."') LEFT JOIN `api` ON `api` = `api`.`id` LEFT JOIN `countries` ON `country` = `countries`.`id` LEFT JOIN `flight_airlines` ON `airline` = `airline_code` ORDER BY `markup_types`.`user_type`) AS `b2c_markups`". $where. $order_by;
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

	public function get_b2b_markups($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `markups`.*, `markup_types`.`user_type`, `markup_name`, `api_name`, `country_en`, `user_id`, `firstname`, `lastname` FROM `markups` INNER JOIN `markup_types` ON `markup_types`.`id` = `mt_id` AND (`markup_types`.`user_type` IS NULL OR `markup_types`.`user_type` = '".B2B_USER."') LEFT JOIN `api` ON `api` = `api`.`id` LEFT JOIN `countries` ON `country` = `countries`.`id` LEFT JOIN `b2b` ON `b2b`.`id` = `b2b` ORDER BY `markup_types`.`user_type`) AS `b2c_markups`". $where. $order_by;
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

	public function get_markup($id)
	{
		$query = "SELECT * FROM (SELECT `markups`.*, `markup_types`.`user_type`, `markup_name`, `api_name`, `country_en`, `airline_en` FROM `markups` INNER JOIN `markup_types` ON `markup_types`.`id` = `mt_id` LEFT JOIN `api` ON `api` = `api`.`id` LEFT JOIN `countries` ON `country` = `countries`.`id` LEFT JOIN `flight_airlines` ON `airline` = `airline_code` ORDER BY `markup_types`.`user_type`) AS `markups` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

}