<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Promocode_Model extends CI_Model {
	
	public function __construct()
	{
	    parent::__construct();
    }

	public function get_active_promo_list()
	{
		$query = "SELECT * FROM `promo_code` WHERE `valid_to` > '".date("Y-m-d")."' AND `status` = '1'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_all_promocodes($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `promo_code`". $where. $order_by;
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

	public function is_promo_exists($code)
	{
		$query = "SELECT * FROM `promo_code` WHERE `code` = '$code'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_promocode($id)
	{
			$query = "SELECT * FROM `promo_code` WHERE `id` = '$id'";
			$result = $this->db->query($query);
			return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	public function add_promo($data)
	{
		$data["valid_from"] = date("Y-m-d H:i:s");
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `promo_code`(`code`, `type`, `valid_from`, `valid_to`, 
			`discount`, `condition`) SELECT '".$data["code"]."', `id`, '".$data["valid_from"]."', 
			'".$data["valid_to"]."', '".$data["discount"]."', '".$data["condition"]."' FROM 
			`promo_discount_types` WHERE `type` = '".$data["type"]."'";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_promocode($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `promo_code` SET ".$set." WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_promocode($id)
	{
		$query = "DELETE FROM `promo_code` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_promocode_emails($tables)
	{
		if(count($tables) === 1)
		{
			$query = "SELECT GROUP_CONCAT(`email_id`) AS `email_id` FROM (SELECT `email_id`, 'Subscriber' AS `firstname`, '1' AS `status` FROM `subscribers` WHERE `block` = '0') AS `subscribers` GROUP BY `status`";
			$table_name = ($tables[0] === B2B_USER) ? "b2b" : (($tables[0] === B2C_USER) ? "b2c" : "");
			if($table_name !== "")
				$query = "SELECT GROUP_CONCAT(`email_id`) AS `email_id` FROM (SELECT `email_id`, `firstname`,`status` FROM `".$table_name."` WHERE `status` = '1') AS `subscribers` GROUP BY `status`";
			$result = $this->db->query($query);
			return $result->num_rows() > 0 ? $result->row()->email_id : false;
		}
		else
		{
			$query = "";
			foreach ($tables as $value)
			{
				if($value === B2B_USER)
					$query = ($query === "") ? "SELECT `email_id`, `firstname`, `status` FROM `b2b` WHERE `status` = '1'" : $query." UNION SELECT `email_id`, `firstname`, `status` FROM `b2b` WHERE `status` = '1'";
				if($value === B2C_USER)
					$query = ($query === "") ? "SELECT `email_id`, `firstname`, `status` FROM `b2c` WHERE `status` = '1'" : $query." UNION SELECT `email_id`, `firstname`, `status` FROM `b2c` WHERE `status` = '1'";
				if($value === SUBSCRIBERS)
					$query = ($query === "") ? "SELECT `email_id`, 'Subscriber' AS `firstname`, '1' AS `status` FROM `subscribers` WHERE `block` = '0'" : $query." UNION SELECT `email_id`, 'Subscriber' AS `firstname`, '1' AS `status` FROM `subscribers` WHERE `block` = '0'";
			}
			if($query !== "")
			{
				$query = "SELECT GROUP_CONCAT(`email_id`) AS `email_id` FROM ($query) AS `subscribers` GROUP BY `status`";
				$result = $this->db->query($query);
				return $result->num_rows() > 0 ? $result->row()->email_id : false;
			}
			return false;
		}
	}
}