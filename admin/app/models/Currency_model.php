<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Currency_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_currencies($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `currencies`.*, `country_en` FROM `currencies` INNER JOIN 
			`countries` ON `id` = `country`) AS `currencies`". $where. $order_by;
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
		$query = "INSERT INTO `currencies`($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function is_exists($country)
	{
		$query = "SELECT * FROM (SELECT `currencies`.*, `country_en` FROM `currencies` INNER JOIN 
			`countries` ON `id` = `country`) AS `currencies` WHERE `country` = '$country'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function update($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `currencies` SET ".$set." WHERE `country` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete($id)
	{
			$query = "DELETE FROM `currencies` WHERE `country` = '$id'";
			$this->db->query($query);
			return $this->db->affected_rows() > 0 ? true : false;
	}

}