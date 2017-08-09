<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Api_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_apis($where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `api`.*, `service_name` FROM `api` INNER JOIN `services` WHERE `service` = `services`.`id`) AS `api`". $where. $order_by;
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

	public function get_services()
	{
		$query = "SELECT * FROM `services`";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function is_service_exists($data, $id = null)
	{
		$condition = "";
		if($id !== null)
			$condition = "`id` = '$id'";
		else
		{
			foreach ($data as $key => $value)
				$condition .= "`$key` = '$value' OR ";
			$condition = substr_replace($condition, "", -4);
		}
		$query = "SELECT * FROM `services` WHERE $condition";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_service($data)
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
		$query = "INSERT INTO `services` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function delete_service($id)
	{
		$query = "DELETE FROM `services` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function add($data)
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
		$query = "INSERT INTO `api`($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function get_api($id)
	{
		$query = "SELECT * `api` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function update($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `api` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		$response = $this->db->affected_rows();
		if($response > 0)
		{
			$query = "DELETE FROM `flight_session` WHERE `id` = '$id'";
			$this->db->query($query);
		}
		return $response > 0 ? true : false;
	}

	public function delete($id)
	{
		$query = "DELETE FROM `api` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
}