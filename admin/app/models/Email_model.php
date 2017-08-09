<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Email_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_template_list($where, $order_by, $limit)
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `email_template`". $where. $order_by;
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

	public function get_template($id, $email_type = null, $status = false)
	{
		$where = "`id` = '$id'";
		if(!is_null($email_type))
			$where = "`email_type` = '$email_type'";
		if($status === true)
			$where .= " AND `status` = '1'";
		$query = "SELECT * FROM `email_template` WHERE $where";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_template($data)
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
		$query = "INSERT INTO `email_template`($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_template($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `email_template` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_template($id)
	{
		$query = "DELETE FROM `email_template` WHERE `id` = '$id' AND `default` = '0'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_access()
	{
		$query = "SELECT * FROM `email_access`";
		$result = $this->db->query($query);
		$response = json_decode(json_encode(array("id" => "1", "smtp" => "", "host" => "", "port" => "", "username" => "", "password" => "")));
		if($result->num_rows() > 0)
		{
			$response = $result->row();
			$response->id = base64_encode($this->encrypt->encode($response->id));
			$response->password = $this->encrypt->decode(base64_decode($response->password));
		}
		return $response;
	}

	public function set_access($data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);

		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);

		$query = "INSERT INTO `email_access` ($cols) VALUES ($vals) ON 
		DUPLICATE KEY UPDATE $set";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
}