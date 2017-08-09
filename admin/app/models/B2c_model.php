<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class B2c_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_user($id, $email = null)
	{
		$where = "`b2c`.`id` = '$id'";
		if(!is_null($email))
			$where = "`b2c`.`email_id` = '$email'";
		$query = "SELECT `b2c`.*, `countries`.`country_en` AS `country_text` 
		 FROM `b2c` LEFT JOIN 
		`countries` ON `countries`.`id` = `b2c`.`country` WHERE $where";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function users_list($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM `b2c`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response['count'] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
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
			$vals .= is_null($value) ? "NULL, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `b2c`($cols) VALUES ($vals)";
		$this->db->query($query);
		$user = $this->db->insert_id();
		if (!empty($user))
		{
			$data = array(
						"id" => $user, 
						"user_type" => B2C_USER, 
						"email_verified" => "1", 
						"two_step_verification" => "0", 
						"two_step_type" => "0"
					);
			$cols = "";
			$vals = "";
			foreach ($data as $key => $value)
			{
				$cols .= "`".$key."`, ";
				$vals .= is_null($value) ? "NULL, " : "'".$value."', ";
			}
			$cols = substr_replace($cols, "", -2);
			$vals = substr_replace($vals, "", -2);
			$query = "INSERT INTO `b2c_verification`($cols) VALUES ($vals)";
			$this->db->query($query);
			return true;
		}
		return false;
	}

	public function update_profile($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`$key` = NULL, " : "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `b2c` SET ".$set." WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_user($id)
	{
		 $user = $this->get_user($id);
		if($user !== false)
		{
			$query = "DELETE FROM `b2c` WHERE `id` = '$id'";
			$this->db->query($query);
			return $this->db->affected_rows() > 0 ? true : false;
		}
		return false;
	}
}
