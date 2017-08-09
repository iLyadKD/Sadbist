<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class B2B_Model extends CI_Model {

	public function __construct()
	{

		parent::__construct();
	}

	public function users_list($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM (SELECT `b2b`.*, `acc_name` FROM `b2b` INNER JOIN `account_types` ON 
		`account_types`.`id` = `b2b`.`acc_type`) AS `b2b`". $where. $order_by;
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

	public function get_user($id, $email = null)
	{
		$where = "`b2b`.`id` = '$id'";
		if(!is_null($email))
			$where = "`b2b`.`email_id` = '$email'";
		$query = "SELECT `b2b`.*, `acc_name`, `account_types`.`acc_type` AS `acc_type_text`, `countries`.`country_en` AS `country_text`, 
		`regions`.`name` AS `state_text`, `cities`.`city` AS `city_text` FROM `b2b` 
		INNER JOIN `account_types` ON `account_types`.`id` = `b2b`.`acc_type` INNER JOIN 
		`countries` ON `countries`.`id` = `b2b`.`country` LEFT JOIN `regions` ON 
		`regions`.`region` = `b2b`.`state` AND `regions`.`country` = `b2b`.`country` 
		LEFT JOIN `cities` ON `cities`.`id` = `b2b`.`city` WHERE $where";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_agent($data)
	{
		$this->db->insert("b2b", $data);
		$agent = $this->db->insert_id();
		if (!empty($agent))
		{
			$data = array(
						"id" => $agent,
						"deposit" => "0.00",
						"last_deposit_trans" => "0.00",
						"total_credit" => "0.00",
						"total_credit_used" => "0.00",
						"total_credit_remain" => "0.00",
						"credit_tranx" => "",
						"last_credit_trans" => "0.00",
						"credit" => "0.00",
						"last_credited" => date("Y-m-d H:i:s", strtotime("1970-01-01")),
						"credit_used" => "0.00",
						"credit_remain" => "0.00",
						"credit_settled" => "0.00",
						"last_credit_trans" => "0.00"
					);
			$this->db->insert("b2b_acc_info", $data);
			$data = array(
						"id" => $agent, 
						"user_type" => B2B_USER, 
						"email_verified" => "0", 
						"two_step_verification" => "0", 
						"two_step_type" => "0"
					);
			$this->db->insert("b2b_verification",$data);
			return $agent;
		}
		return false;
	}

	public function update_profile($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= is_null($value) ? "`$key` = NULL, " : "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `b2b` SET ".$set." WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function is_any_pending_reqs($id, $user_type)
	{
		$query = "SELECT IF(COUNT(`account_statement`.`id`) <> '0', 'true', 'false') AS 
		`response` FROM `account_statement` WHERE `user_id` = '$id' AND `user_type` = 
		'$user_type' AND `status` = '0'";
		$result = $this->db->query($query);
		return $result->row()->response === "true" ? true : false;
	}

	public function cancel_deposit_credit_reqs($id)
	{
		$query = "UPDATE `account_statement` SET `status` = '2' WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

}