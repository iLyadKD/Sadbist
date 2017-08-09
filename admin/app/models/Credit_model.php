<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Credit_Model extends CI_Model {
	
	public function __construct()
	{
      parent::__construct();
    }

	public function b2b_user_credit_requests($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM (SELECT `account_statement`.*, `id`, `unique_id`, `company_name`, `firstname`, `lastname`, `email_id`, `image_path`, `used_credit`, `balance_credit`, `settlement_credit` FROM (SELECT `b2b`.`id`, `user_type`, `b2b`.`acc_type`, `user_id` AS `unique_id`, `company_name`, `firstname`, `lastname`, `email_id`, `image_path`, `total_credit_used` AS `used_credit`, `total_credit_remain` AS `balance_credit`, (`total_credit` - `total_credit_used`) AS `settlement_credit` FROM `b2b` INNER JOIN `account_types` ON `account_types`.`id` = `b2b`.`acc_type` AND `account_types`.`id` = '".B2B_CREDIT_ACC."' INNER JOIN `b2b_acc_info` ON `b2b`.`id` = `b2b_acc_info`.`id`) AS `b2b` INNER JOIN (SELECT `balance`, `amount`, `tranx_id`, `deposited`, `tranx_slip`, `status`, `tranx_type`, `remarks`, `account_statement`.`id` AS `acc_id`, `acc_type`, `user_id`, `user_type`, `name` AS `tranx_category` FROM `account_statement` INNER JOIN `transaction_types` ON `transaction_types`.`id` = `tranx_type` AND `status` = '0' AND `tranx_type` IN ('".TRANX_REQUEST_CREDIT_B2B."')) AS `account_statement` ON `id` = `user_id` AND `b2b`.`user_type` = `account_statement`.`user_type` AND `account_statement`.`acc_type` = `b2b`.`acc_type` ORDER BY `status` DESC, `deposited`) AS `b2b` ". $where. $order_by;
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

	public function get_agent_list($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT `b2b`.`id`, `agent_type`, `b2b`.`acc_type`, `change_request`, `email_id`, `company_name`, `contact_no`, 
			`status`, `image_path`, `credit_limit`, `credit_used`, `credit_remain`, 
			`credit_settled` FROM (SELECT `b2b`.`id`, `change_request`, `agent_type`, `credit_limit`, `b2b`.`acc_type`, `email_id`, 
			`company_name`, `contact_no`, `status`, `image_path` FROM `b2b` INNER JOIN `account_types`ON 
			`account_types`.`id` = `b2b`.`acc_type` AND `account_types`.`acc_type` = '".B2B_CREDIT_ACC."') 
			AS `b2b` INNER JOIN `b2b_acc_info` ON `b2b`.`id` = `b2b_acc_info`.`id`". $where. $order_by;
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

	public function get_credit_status($id)
	{
		$query = "SELECT `status`, `name`, `deposited` FROM `account_statement` INNER JOIN 
		`transaction_types` ON `tranx_type` = `transaction_types`.`id` AND 
		`account_statement`.`id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_settlement_status($id)
	{
		$query = "SELECT `name`, `credit_transactions`.`deposited`, 
		`credit_transactions`.`amount`,`credit_transactions`.`status` FROM `account_statement` INNER JOIN 
		(SELECT `name`, `deposited`, `amount`, `tranx_id`, `status` FROM `credit_transactions` 
		INNER JOIN `transaction_types` ON `credit_type` = `transaction_types`.`id`) 
		AS `credit_transactions` ON `credit_transactions`.`tranx_id` = 
		`account_statement`.`tranx_id`  AND `account_statement`.`id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_credit_requests($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM (SELECT `credit_requests`.*, `total_credit`, 
		`credit_tranx`, `credit`, `last_credited`, `credit_used`, `credit_remain`, 
		`credit_settled` FROM (SELECT `b2b`.*, `credit_id`, `credits`.`tranx_id` AS 
		`credits_tranx`, `amount`, `deposited`, `credit_status`, `credit_type` FROM 
		(SELECT `b2b`.*, `tranx_id`, `account_statement`.`status`, `id`, 
		`account_statement`.`deposited` AS `credit_deposited`, `used`, 
		`remain`, `pending`, `settled`, `settlement` FROM (SELECT `agent_id`, 
		`b2b`.`id` AS `b2bid`, `change_request`, `agent_type`, `b2b`.`acc_type`, 
		`email_id`, `company_name`, `contact_no` FROM `b2b` INNER JOIN 
		`account_types` ON `account_types`.`id` = `b2b`.`acc_type` AND 
		`account_types`.`acc_type` = '".B2B_CREDIT_ACC."' AND `b2b`.`status` = '1') AS 
		`b2b` INNER JOIN `account_statement` ON `user_id` = `b2bid` AND `user_type` 
		= `agent_type` AND `b2b`.`acc_type` = `account_statement`.`acc_type` AND 
		`account_statement`.`tranx_type` IN ('".TRANX_ADD_CREDIT_ADMIN."', 
		'".TRANX_REQUEST_CREDIT_B2B."')) AS `b2b` INNER JOIN (SELECT 
		GROUP_CONCAT(`id`) AS `credit_id`, `tranx_id`,GROUP_CONCAT(`amount`) 
		AS `amount`, GROUP_CONCAT(`deposited`) AS `deposited`, 
		GROUP_CONCAT(`credit_type`) AS `credit_type`, GROUP_CONCAT(`status`) AS 
		`credit_status` FROM `credit_transactions` GROUP BY `tranx_id`) AS `credits` 
		ON `b2b`.`tranx_id` = `credits`.`tranx_id`) AS `credit_requests` INNER JOIN 
		`b2b_acc_info` ON `b2bid` = `b2b_acc_info`.`id` ORDER BY CASE WHEN 
		`settlement` = '1' THEN 1 ELSE 0 END, `credit_deposited`) AS 
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

	public function get_credit_amount($id)
	{
		$query = "SELECT `balance`, `value`, `currency_type`, `credit_used`, `credit_settled`, `credit_limit` FROM (SELECT 
				IF(`credit_remain` <> '', `credit_remain`, '0.00') AS `balance`, `credit_used`, 
				`currency_type`, `credit_settled`, `credit_limit` FROM `b2b` INNER JOIN `b2b_acc_info` ON `b2b_acc_info`.`id` = 
				`b2b`.`id` WHERE `b2b`.`id` = '$id') AS `b2b` INNER JOIN `currencies` ON 
				`country` = `currency_type`";
		$result = $this->db->query($query);
		return $result->row();
	}

	public function is_credit_in_limit($id, $amount)
	{
		$query = "SELECT IF(`result` <> '', 'true', 'false') AS `response` FROM (SELECT IF(`credit_limit` >= 
				((`credit_remain` - '$amount') * -1), 'Avail', '') AS `result` FROM `b2b` INNER JOIN 
				`b2b_acc_info` ON `b2b`.`id` = `b2b_acc_info`.`id` AND `b2b`.`id` = '$id') AS 
				`b2b`";
		$result = $this->db->query($query);
		return $result->row()->response === "true" ? true : false;
	}

	public function is_settled($id)
	{
		$query = "SELECT IF(`result` = '0.00', 'true', 'false') AS `response` FROM (SELECT 
				`credit` AS `result` FROM `b2b` INNER JOIN `b2b_acc_info` ON `b2b`.`id` = 
				`b2b_acc_info`.`id` AND `b2b`.`id` = '$id') AS `b2b`";
		$result = $this->db->query($query);
		return $result->row()->response === "true" ? true : false;
	}

	public function add_credit($id, $agent_type, $acc_type, $amount, $deposited_date, $remarks)
	{
		$balance_credit = $this->get_credit_amount($id);
		$tranx_id = "CD".date("YmdHis");
		$query = "INSERT INTO `account_statement`(`user_id`, `user_type`, `b2b`.`acc_type`, 
				`tranx_type`, `amount`, `original_amt`, `balance`, `tranx_id`, `deposited`, 
				`status`, `remarks`, `exchange_rate`) VALUES('$id', '$agent_type', '$acc_type', 'Credit - Admin Added', 
				'$amount', '".($amount*$balance_credit->value)."', '".$balance_credit->balance."', '$tranx_id',
				'$deposited_date', '1', '$remarks', '".$balance_credit->currency_type.':::'.$balance_credit->value."')";
		$this->db->query($query);
		$query = "UPDATE `b2b_acc_info` SET `credit` = '$amount', `last_credited` = '$deposited_date', 
		`credit_remain` = `credit_remain` - '$amount' WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function pending_response($tranx_id, $status)
	{
		$query = "UPDATE `account_statement` SET `status` = '$status' WHERE `id` = '$tranx_id' AND `status` = '0'";
		$this->db->query($query);
		if($status === 1 && $this->db->affected_rows() > 0)
		{
			 $query = "UPDATE `b2b_acc_info`, `account_statement` SET `credit` = `amount`, 
			`credit_remain` = `credit_remain` - `amount`, `last_credited` = `deposited` 
			WHERE `b2b_acc_info`.`id` = `user_id` AND `account_statement`.`id` = '$tranx_id'";
			$this->db->query($query);
			return $this->db->affected_rows() > 0 ? true : false;
		}
		else
			return $this->db->affected_rows() > 0 ? true : false;
	}

	public function update_credit_req($id, $status)
	{
		$query = "UPDATE `credit_transactions`, `account_statement` SET 
		`credit_transactions`.`status` = '$status', `account_statement`.`status` = 
		'$status' WHERE `credit_transactions`.`tranx_id` = `account_statement`.`tranx_id` 
		AND `account_statement`.`id` = '$id'";
		if($status === 1)
			$query = "UPDATE `credit_transactions`, `account_statement`, 
			`b2b_acc_info` SET `credit_transactions`.`status` = '$status', 
			`remain` = `credit_transactions`.`amount`, `account_statement`.`status` 
			= '$status', `total_credit` = `total_credit` + 
			`credit_transactions`.`amount`, `total_credit_remain` = 
			`total_credit_remain` + `credit_transactions`.`amount`, `credit` = 
			IF(`credit_tranx` IS NULL, `credit_transactions`.`amount`, `credit`), 
			`last_credited` = IF(`credit_tranx` IS NULL, 
			`credit_transactions`.`deposited`, `last_credited`), `credit_remain` = 
			IF(`credit_tranx` IS NULL, `account_statement`.`amount`, `credit_remain`), 
			`credit_tranx` = IF(`credit_tranx` IS NULL, `credit_transactions`.`tranx_id`, 
			`credit_tranx`) WHERE `account_statement`.`id` = '$id' AND `credit_type` 
			IN ('".TRANX_ADD_CREDIT_ADMIN."', '".TRANX_REQUEST_CREDIT_B2B."') AND 
			`credit_transactions`.`tranx_id` = `account_statement`.`tranx_id` 
			AND `b2b_acc_info`.`id` = `user_id`";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function make_partial_payment($id, $status)
	{
		$query = "UPDATE `credit_transactions` SET `status` = '$status' WHERE `id` 
		= '$id' AND `credit_type` = '".TRANX_PARTIAL_SETTLEMENT."'";
		if($status === 1)
			$query = "UPDATE `credit_transactions`, `account_statement`, 
			`b2b_acc_info` SET `credit_transactions`.`status` = '$status', 
			`settled` = `settled` + `credit_transactions`.`amount`, `pending` = 
			`pending` - `credit_transactions`.`amount`, `credit_settled` = 
			`credit_settled` + `credit_transactions`.`amount` WHERE 
			`credit_transactions`.`id` = '$id' AND `credit_type` 
			IN ('".TRANX_PARTIAL_SETTLEMENT."') AND `credit_transactions`.`tranx_id` 
			= `account_statement`.`tranx_id` AND `b2b_acc_info`.`id` = `user_id`";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function make_full_payment($id, $status)
	{
		$query = "UPDATE `credit_transactions` SET `status` = '$status' WHERE `id` 
		= '$id' AND `credit_type` IN ('".TRANX_FORCE_SETTLEMENT_B2B."', 
			'".TRANX_REQUEST_FULL_SETTLEMENT_B2B."')";
		$rtn_val = 0;
		if($status === 1)
		{
			$query = "SELECT `user_id`, `user_type`, `b2b`.`acc_type`, `tranx_type`, 
			`a`.`amount`, `a`.`tranx_id`, `a`.`deposited`, `used`, `remain`, 
			`settled`, `pending`, `settlement` FROM `account_statement` AS 
			`a` INNER JOIN `credit_transactions` AS `b` ON `a`.`tranx_id` = 
			`b`.`tranx_id` AND `credit_type` IN ('".TRANX_ADD_CREDIT_ADMIN."', 
			'".TRANX_REQUEST_CREDIT_B2B."') AND `settlement` <> '1' AND 
			`b`.`tranx_id` NOT IN (SELECT `tranx_id` FROM `credit_transactions` 
			WHERE `id` = '$id') ORDER BY `a`.`deposited`";
			$result = $this->db->query($query);
			$credits = $result->num_rows() > 0 ? $result->row() : false;
			$status_update = "UPDATE `credit_transactions` SET `status`= '$status' 
			WHERE `id` = '$id'";
			$settlement = "UPDATE `account_statement` AS `a`, `credit_transactions` 
			AS `b` SET `settlement` = '$status', `a`.`amount` = `used`, `remain` = 
			'0.000000', `settled` = `settled` + `pending`, `pending` = '0.000000' 
			WHERE `a`.`tranx_id` = `b`.`tranx_id` 
			AND `b`.`id` = '$id' AND `credit_type` IN ('".TRANX_FORCE_SETTLEMENT_B2B."', 
			'".TRANX_REQUEST_FULL_SETTLEMENT_B2B."')";
			$this->db->query($status_update);
			if($this->db->affected_rows() > 0)
			{
				$acc_info = "UPDATE `b2b_acc_info` AS `a`, `account_statement` AS `b`, 
				`credit_transactions` AS `c` SET `total_credit` = '0.000000', 
				`total_credit_used` = '0.000000', `total_credit_remain` = '0.000000', 
				`credit_tranx` = null, `credit` = '0.000000', `last_credited` = 
				'0.000000', `credit_used` = '0.000000', `credit_remain` = '0.000000', 
				`credit_settled` = '0.000000' WHERE `c`.`id` = '$id' AND `credit_type` 
				IN ('".TRANX_FORCE_SETTLEMENT_B2B."', '".TRANX_REQUEST_FULL_SETTLEMENT_B2B."') 
				AND `c`.`tranx_id` = `b`.`tranx_id` AND `a`.`id` = `user_id`";

				if($credits !== false)
				{
					$acc_info = "UPDATE `b2b_acc_info` AS `a`, `account_statement` AS `b`, 
					`credit_transactions` AS `c` SET `total_credit` = `total_credit` - `b`.`amount`, 
					`total_credit_used` = `total_credit_used` - (`b`.`amount` - `b`.`used`), 
					`total_credit_remain` = `total_credit_remain` - (`b`.`amount` - `b`.`used`), 
					`credit_tranx` = '".$credits->tranx_id."', `credit` = '".$credits->amount."', 
					`last_credited` = '".$credits->deposited."', `credit_used` = '".$credits->used."', 
					`credit_remain` = '".$credits->remain."', `credit_settled` = '".$credits->settled."' 
					WHERE `c`.`id` = '$id' AND `credit_type` 
					IN ('".TRANX_FORCE_SETTLEMENT_B2B."', '".TRANX_REQUEST_FULL_SETTLEMENT_B2B."') 
					AND `c`.`tranx_id` = `b`.`tranx_id` AND `a`.`id` = `user_id`";
				}
				$this->db->query($acc_info);
				if($this->db->affected_rows() > 0)
				{
					$this->db->query($settlement);
					return $this->db->affected_rows() > 0 ? true : false;
				}
			}
			return false;
		}
		else
		{
			$this->db->query($query);
			return $this->db->affected_rows() > 0 ? true : false;
		}
	}

	// public function get_credit_details($id)
	// {
	// 	$select = "SELECT *, date_format(credited_date, '%d/%m/%Y') as credited FROM b2b_credit WHERE credit_id = $id";
	// 	$query=$this->db->query($select);
	// 	if ($query->num_rows() > 0){
	// 		return $query->row();
	// 	} else {
	// 		return false;	
	// 	}
	// }

	// public function all_credits(){
	// 	$select = "SELECT *, date_format(credited_date, '%d/%m/%Y') as credited FROM b2b_credit ORDER BY credit_id DESC";		
	// 	$query = $this->db->query($select);
	// 	if ($query->num_rows() > 0){
	// 		return $query->result();
	// 	} else {
	// 		return false;	
	// 	}
	// }

	// public function add_agent_credit($agent_id, $site_currency, $amount_credit, $agent_currency, $agent_currency_amount, $agentCurrencyExchangeRate, $credited_date, $remarks, $trans_type){
	// 	$select2 = "select max(credit_id)+1 as max from b2b_credit";
	// 	$query = $this->db->query($select2);
	// 	$aa = $query->row();
	// 	$m_id1 = 0;
	// 	if($aa!=''){ $m_id1 = $aa->max;	}		
	// 	$m_id =  'AT'.date('d').date('m').($m_id1+10000);
	
	// 	$data = array(
	// 				'agent_id' => $agent_id,
	// 				'site_currency' => $site_currency,
	// 				'credit_type' => $trans_type,
	// 				'request_type' => $trans_type,
	// 				'site_currency_amount' => $amount_credit,
	// 				'agent_currency' => $agent_currency,
	// 				'agent_currency_amount' => $agent_currency_amount,
	// 				'currency_exchange_rate' => $agentCurrencyExchangeRate,
	// 				'credited_date' => $credited_date,
	// 				'remarks' => $remarks,
	// 				'transaction_id' => $m_id,			
	// 				'status' => 'Accepted'
	// 			);			
	// 	$this->db->insert('b2b_credit', $data);
	// 	$id = $this->db->insert_id();
		
	// 	if (!empty($id)) {	//update agent balance
	// 		$select = "SELECT agent_acc_info_id FROM  b2b_acc_info where agent_id = $agent_id limit 1";
	// 		$query = $this->db->query($select);
	// 		if ($query->num_rows() > 0){
	// 			$qry = "update b2b_acc_info set balance_credit = (balance_credit + $amount_credit), last_credit = $amount_credit where agent_id = $agent_id";
	// 		} else {
	// 			$qry = "insert into  b2b_acc_info set agent_id = $agent_id, balance_credit = $amount_credit, last_credit = $amount_credit";
	// 		}
	// 		$this->db->query($qry);	

	// 		//update account statement
	// 		$description = 'credit - : '.$m_id;
	// 		$query = "SELECT * FROM  b2b_acc_info where agent_id = $agent_id limit 1";
	// 		$query = $this->db->query($query);
	// 		if ($query->num_rows() > 0){
	// 			$result = $query->row();
	// 			$account_transaction = array(
	// 									'statment_type' => 'credit',
	// 									'tranx_number' => $m_id,
	// 									'user_type' => '2',
	// 									'user_id' => $agent_id,
	// 									'amount' => $amount_credit,
	// 									'balance_amount' => $result->balance_credit,
	// 									'description' => $description
	// 								);
	// 			$this->db->insert('account_statment',$account_transaction); 
	// 			$bid = $this->db->insert_id();
	// 			$timing = date('Ymd');
	// 			$timing1 = date('His');
	// 			$txno = 'TX'.$timing.$bid.$timing1;
	// 			$update_account = array( 'statment_number' => $txno );
	// 			$this->db->where('account_statment_id',$bid);
	// 			$this->db->update('account_statment', $update_account);
	// 		}

	// 		return $m_id;
	// 	} else {
	// 		return false;
	// 	}
	// }

	// public function update_credit_status($id,$status,$agent_id,$amount_credit){
	// 	if($status=='Accepted'){
	// 		$qry = "update b2b_acc_info set balance_credit = (balance_credit + $amount_credit), last_credit = $amount_credit where agent_id = $agent_id";
	// 		$query = $this->db->query($qry);
			
	// 		$select2 = "SELECT * FROM  b2b_credit where credit_id = $id limit 1";
	// 		$query2 = $this->db->query($select2);
	// 		if ($query2->num_rows() > 0){
	// 			$am_result = $query2->row();
	// 			$description='credit - : '.$am_result->transaction_id;
	// 			$select3 = "SELECT * FROM  b2b_acc_info where agent_id = $agent_id limit 1";
	// 			$query3 = $this->db->query($select3);
	// 			if ($query3->num_rows() > 0){
	// 				$am_result3 = $query3->row();
	// 				$account_transaction = array(
	// 					'statment_type' => 'credit',
	// 					'tranx_number' => $am_result->transaction_id,
	// 					'user_type' => '2',
	// 					'user_id' => $agent_id,
	// 					'amount' => $amount_credit,
	// 					'balance_amount' => $am_result3->balance_credit,
	// 					'description' => $description
	// 				);
	// 				$this->db->insert('account_statment',$account_transaction); 
	// 				$bid = $this->db->insert_id();
	// 				$timing = date('Ymd');
	// 				$timing1 = date('His');
	// 				$txno = 'TX'.$timing.$bid.$timing1;
	// 						$update_account = array(
	// 								'statment_number' => $txno
	// 								);
	// 				$this->db->where('account_statment_id',$bid);
	// 				$this->db->update('account_statment', $update_account);
	// 			}
	// 		}
	// 	}

	// 	$data = array('status' => $status);
	// 	$where = "credit_id = ".$id;
	// 	$this->db->update('b2b_credit', $data, $where);
	// 	return true;
	// }
}