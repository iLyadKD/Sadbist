<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Logs_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_logs($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `xml_logs`.*, `api_name` FROM `xml_logs` INNER JOIN `api` ON `api`.`id` = `api` ORDER BY `logged_time` DESC) AS `xml_logs`". $where. $order_by;
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

	public function delete($id)
	{
		$query = "DELETE FROM `xml_logs` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
}