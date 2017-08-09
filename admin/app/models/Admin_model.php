<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Admin_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function is_registered_admin($email)
	{
		$query = "SELECT `admin`.`id`, `email_id`, `name` AS `user_type` FROM `admin` 
		INNER JOIN `user_types` ON `admin`.`user_type` = `user_types`.`id` WHERE 
		`email_id` = '$email'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? true : false;
	}

	public function get_admin_credentials($email, $id = "")
	{
		$where = "`email_id` = '$email'";
		if($id !== "")
			$where = "`id` = '$id'";
		$query = "SELECT `salt`, `password` FROM `admin` WHERE $where AND `status` = '1'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_admin_list($except = "", $where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$except_condition = "OR `user_type` = 6";
		if($except = "")
			$except_condition = " AND `id` <> '$except'";
		$query = "SELECT * FROM (SELECT `ad`.`id`, `ad`.`email_id`, `ad`.`firstname`, `ad`.`lastname`,`ad`.`contact_no`, `ad`.`status`, `ad`.`user_type`, `ut`.`name` as `user_type_name` FROM `admin` ad INNER JOIN `user_types` ut ON `ad`.`user_type` = `ut`.`id` WHERE `user_type` = '".ADMIN_USER."'$except_condition) AS `admin`". $where. $order_by;
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

	public function get_admin_details($email, $id = null)
	{
		$where = "`admin`.`email_id` = '$email'";
		if(!is_null($id))
			$where = "`admin`.`id` = '$id'";
		$query = "SELECT `admin`.*, `privileges` FROM `admin` 
		LEFT JOIN `privileges_granted` ON `admin`.`id` = `admin` WHERE $where";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function new_admin($data)
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
		$query = "INSERT INTO `admin`($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_admin($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`$key` = NULL, " : "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `admin` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_admin($id)
	{
		$query = "DELETE FROM `admin` WHERE `id` = '$id'";
		return $this->db->query($query);
	}
}