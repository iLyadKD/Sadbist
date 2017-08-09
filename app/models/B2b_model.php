<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class B2b_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function is_valid_user($email = "", $id = null)
	{
		$query = "SELECT `a`.*, `email_verified`, `two_step_verification`, 
		`two_step_type` FROM `b2b` AS `a` INNER JOIN `b2b_verification` AS 
		`b` ON `a`.`id` = `b`.`id` WHERE `email_id` = '$email'";
		if(!is_null($id))
			$query = "SELECT * FROM `b2b` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function update_b2b($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `b2b` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_tranx_details( $id, $where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM (SELECT `credit_requests`.*, `total_credit`, 
		`credit_tranx`, `credit`, `last_credited`, `credit_used`, `credit_remain`, 
		`credit_settled` FROM (SELECT `b2b`.*, `credit_id`, `credits`.`tranx_id` AS 
		`credits_tranx`, `amount`, `deposited`, `credit_status`, `credit_type` FROM 
		(SELECT `b2b`.*, `tranx_id`, `account_statement`.`status`, `id`, 
		`account_statement`.`deposited` AS `credit_deposited`, `account_statement`.`amount` 
		AS `credited_amount`, `used`, `remain`, `pending`, `settled`, `settlement` 
		FROM (SELECT `agent_id`, `b2b`.`id` AS `b2bid`, `change_request`, `agent_type`, 
		`b2b`.`acc_type`, `email_id`, `company_name`, `contact_no` FROM `b2b` WHERE `b2b`.`id` 
		= '$id' AND `b2b`.`status` = '1') AS `b2b` INNER JOIN `account_statement` ON 
		`user_id` = `b2bid` AND `user_type` = `agent_type` AND  
		`account_statement`.`tranx_type` IN ('".TRANX_ADD_CREDIT_ADMIN."', 
		'".TRANX_REQUEST_CREDIT_B2B."','".TRANX_ADD_DEPOSIT_ADMIN."', 
		'".TRANX_REQUEST_DEPOSIT_B2B."')) AS `b2b` LEFT JOIN (SELECT 
		GROUP_CONCAT(`id`) AS `credit_id`, `tranx_id`,GROUP_CONCAT(`amount`) 
		AS `amount`, GROUP_CONCAT(`deposited`) AS `deposited`, 
		GROUP_CONCAT(`credit_type`) AS `credit_type`, GROUP_CONCAT(`status`) AS 
		`credit_status` FROM `credit_transactions` GROUP BY `tranx_id`) AS `credits` 
		ON `b2b`.`tranx_id` = `credits`.`tranx_id`) AS `credit_requests` INNER JOIN 
		`b2b_acc_info` ON `b2bid` = `b2b_acc_info`.`id` ORDER BY CASE WHEN 
		`settlement` = '1' THEN 1 ELSE 0 END, `credit_deposited` DESC) AS 
		`credit_acc_requests`". $where. $order_by;
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

	public function get_credit_tranx_stats($id)
	{
		$query = "SELECT `name`, `credit_transactions`.`deposited`, 
		`credit_transactions`.`amount`,`credit_transactions`.`status` FROM `account_statement` INNER JOIN 
		(SELECT `name`, `deposited`, `amount`, `tranx_id`, `status` FROM `credit_transactions` 
		INNER JOIN `transaction_types` ON `credit_type` = `transaction_types`.`id`) 
		AS `credit_transactions` ON `credit_transactions`.`tranx_id` = 
		`account_statement`.`tranx_id`  AND `account_statement`.`id` = '$id' ORDER BY `credit_transactions`.`deposited`";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_my_balance($id)
	{
		$query = "SELECT * FROM `b2b_acc_info` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	// public function get_balance($user_id)
	// {
	// 	$query = "SELECT `b2b_acc_info`.*, `account_types`.`acc_type` FROM `b2b_acc_info` INNER JOIN `account_types` ON `b2b_acc_info`.`id` = `account_types`.`id` WHERE `b2b_acc_info`.`id` = '$user_id'";
	// 	$result = $this->db->query($query);
	// 	if ($result->num_rows() > 0 )     
	// 		return $result->row();
	// 	return false;
	// }

	// public function change_account_type($user_id, $acc_type)
	// {
	// 	$query = "UPDATE `b2b_acc_info` SET `acc_type` = '$acc_type' WHERE `id` = '$user_id'";
	// 	$result = $this->db->query($query);
	// 	if ($result->num_rows() > 0 )     
	// 		return $result->row();
	// 	return false;
	// }
}