<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Subscriber_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_subscribers($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT * FROM `subscribers` WHERE `block` < '2') AS `subscribers`". $where. $order_by;
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

	public function is_subscriber_exist($id)
	{
		$query = "SELECT * FROM `subscribers` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function update($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `subscribers` SET ".$set." WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete($id)
	{
			$query = "DELETE FROM `subscribers` WHERE `id` = '$id'";
			$this->db->query($query);
			return $this->db->affected_rows() > 0 ? true : false;
	}

}