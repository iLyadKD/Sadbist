<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Location_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_countries($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT * FROM `countries` ORDER BY `country_en`) AS `countries`". $where. $order_by;
		$result = $this->db->query($query);
		$response["count"] = $result->num_rows();
		if($result->num_rows() > 0)
		{
			$response['count'] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
		}
		return $response;
	}

	public function is_country_exists($data, $except = array())
	{
		$condition = "";
		foreach ($data as $key => $value)
			$condition .= "`$key` = '$value' OR ";
		$condition = substr_replace($condition, "", -4);
		$e_condition = "";
		foreach ($except as $key => $value)
			$e_condition .= "`$key` <> '$value' OR ";
		$e_condition = substr_replace($e_condition, "", -4);
		$query = "SELECT * FROM `countries` WHERE ($condition)";
		if($e_condition !== "")
			$query .= "AND ($e_condition)";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_country($id)
	{
		$query = "SELECT * FROM `countries` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_country($data)
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
		$query = "INSERT INTO `countries`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_country($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `countries` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_country($id)
	{
		$query = "DELETE FROM `countries` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_all_regions($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `regions`.*, `country_en` FROM `regions` INNER JOIN `countries` WHERE `country` = `countries`.`id`) AS `regions`". $where. $order_by;
		$result = $this->db->query($query);
		$response["count"] = $result->num_rows();
		if($result->num_rows() > 0)
		{
			$response['count'] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
		}
		return $response;
	}

	public function is_region_exists($data, $except = array())
	{
		$condition = "(`region` = '".$data['region']."' AND `country` = '".$data['country']."') OR `name_en` = '".$data['name_en']."' ";
		$e_condition = "";
		$query = "SELECT * FROM (SELECT * FROM `regions` WHERE $condition) AS `regions`";
		if(count($except) !== 0)
		{
			$e_condition = "(`name_en` <> '".$except['name_en']."' AND `region` <> '".$except['region']."' AND `country` <> '".$except['country']."') AND (`name_en` <> '".$except['name_en']."' AND `region` IS NULL AND `country` <> '".$except['country']."')";
			$query .= " WHERE $e_condition";
		}
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_region($country, $region)
	{
		$query = "SELECT * FROM `regions` WHERE `region` = '$region' AND `country` = '$country'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_region($data)
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
		$query = "INSERT INTO `regions`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_region($country, $region, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `regions` SET $set WHERE `region` = '$region' AND `country` = '$country'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_region($country, $region)
	{
		$query = "DELETE FROM `regions` WHERE `region` = '$region' AND `country` = '$country'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_all_cities($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `cities`.*, `country_en`, `name_en` FROM `cities` 
			INNER JOIN `countries` ON `cities`.`country` = `countries`.`id` LEFT JOIN `regions` 
			ON `cities`.`region` IS NOT NULL AND `cities`.`region` = `regions`.`region` AND `cities`.`country` = `regions`.`country` ORDER BY `country_en`, `name_en`, `city_en`) AS `cities`". $where. $order_by;
		$result = $this->db->query($query);
		$response["count"] = $result->num_rows();
		if($response["count"] > 0)
		{
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
		}
		return $response;
	}

	public function is_city_exists($data, $except = array())
	{
		$region_codition = "`region` = '".$data['region']."'";
		if(is_null($data['region']))
			$region_codition = "`region` IS NULL";
		$condition = "$region_codition AND `country` = '".$data['country']."' AND `city_en` = '".$data['city_en']."' ";
		$e_condition = "";
		$query = "SELECT * FROM (SELECT * FROM `cities` WHERE $condition) AS `cities`";
		if(!empty($except))
		{
			$e_region_codition = "`region` <> '".$except['region']."'";
			if(is_null($except['region']))
				$e_region_codition = "`region` IS NOT NULL";
			$e_condition = "`city_en` <> '".$except['city_en']."' AND $e_region_codition AND `country` <> '".$except['country']."'";
			$query .= " WHERE $e_condition";
		}
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_city($id)
	{
		$query = "SELECT * FROM `cities` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_city($data)
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
		$query = "INSERT INTO `cities`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_city($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`$key` = NULL, " : "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `cities` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_city($id)
	{
		$query = "DELETE FROM `cities` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_all_airports($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `flight_airports`.*, `country_en` FROM `flight_airports` 
			INNER JOIN `countries` ON `flight_airports`.`country` = `countries`.`id` ORDER BY `airport_code`) AS `flight_airports`". $where. $order_by;
		$result = $this->db->query($query);
		$response["count"] = $result->num_rows();
		if($response["count"] > 0)
		{
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
		}
		return $response;
	}

	public function is_airport_exists($data, $except = array())
	{
		$condition = "`country` = '".$data['country']."' AND `city_code` = '".$data['city_code']."'  AND `airport_code` = '".$data['airport_code']."'";
		$query = "SELECT * FROM (SELECT * FROM `flight_airports` WHERE $condition) AS `flight_airports`";
		if(!empty($except))
		{
			$e_condition = "`city_code` <> '".$except['city_code']."' AND `airport_code` <> '".$except['airport_code']."' AND `country` <> '".$except['country']."'";
			$query .= " WHERE $e_condition";
		}
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_airport($id)
	{
		$query = "SELECT * FROM `flight_airports` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_airport($data)
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
		$query = "INSERT INTO `flight_airports`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_airport($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`$key` = NULL, " : "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `flight_airports` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_airport($id)
	{
		$query = "DELETE FROM `flight_airports` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_all_airlines($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM(SELECT * FROM `flight_airlines` ORDER BY `airline_code`) AS `flight_airlines` ". $where. $order_by;
		$result = $this->db->query($query);
		$response["count"] = $result->num_rows();
		if($response["count"] > 0)
		{
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
		}
		return $response;
	}

	public function is_airline_exists($data, $except = array())
	{
		$condition = "`airline_code` = '".$data['airline_code']."'";
		$query = "SELECT * FROM (SELECT * FROM `flight_airlines` WHERE $condition) AS `flight_airlines`";
		if(!empty($except))
		{
			$e_condition = "`airline_code` <> '".$except['airline_code']."'";
			$query .= " WHERE $e_condition";
		}
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_airline($id)
	{
		$query = "SELECT * FROM `flight_airlines` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_airline($data)
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
		$query = "INSERT INTO `flight_airlines`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_airline($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`$key` = NULL, " : "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `flight_airlines` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_airline($id)
	{
		$query = "DELETE FROM `flight_airlines` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

}