<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Deposit_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_deposit_amount($id)
	{
		$query = "SELECT `balance`, `value`, `b2b`.`currency`, `code` FROM (SELECT IF(`deposit` <> '', `deposit`, '0.00') 
				AS `balance`, `b2b`.`currency` FROM `b2b` INNER JOIN `b2b_acc_info` ON 
				`b2b_acc_info`.`id` = `b2b`.`id` WHERE `b2b`.`id` = '$id') AS `b2b` INNER JOIN 
				`currencies` ON `country` = `b2b`.`currency`";
		$result = $this->db->query($query);
		return $result->row();
	}

	public function b2b_user_deposits($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM (SELECT `account_statement`.*, `id`, `company_name` FROM (SELECT `b2b`.`id`, `user_type`, `b2b`.`acc_type`, `company_name` FROM `b2b` INNER JOIN `account_types` ON `account_types`.`id` = `b2b`.`acc_type` AND `account_types`.`id` = '".B2B_DEPOSIT_ACC."') AS `b2b` INNER JOIN (SELECT `balance`, `amount`, `tranx_id`, `deposited`, `tranx_slip`, `status`, `tranx_type`, `remarks`, `account_statement`.`id` AS `acc_id`, `acc_type`, `user_id`, `user_type`, `name` AS `tranx_category` FROM `account_statement` INNER JOIN `transaction_types` ON `transaction_types`.`id` = `tranx_type`) AS `account_statement` ON `id` = `user_id` AND `b2b`.`user_type` = `account_statement`.`user_type` AND `account_statement`.`acc_type` = `b2b`.`acc_type` ORDER BY `status` DESC, `deposited`) AS `b2b` ". $where. $order_by;
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

	public function b2b_user_deposit_requests($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM (SELECT `account_statement`.*, `id`, `unique_id`, `company_name`, `firstname`, `lastname`, `email_id`, `image_path` FROM (SELECT `b2b`.`id`, `user_type`, `b2b`.`acc_type`, `user_id` AS `unique_id`, `company_name`, `firstname`, `lastname`, `email_id`, `image_path` FROM `b2b` INNER JOIN `account_types` ON `account_types`.`id` = `b2b`.`acc_type` AND `account_types`.`id` = '".B2B_DEPOSIT_ACC."') AS `b2b` INNER JOIN (SELECT `balance`, `amount`, `tranx_id`, `deposited`, `tranx_slip`, `status`, `tranx_type`, `remarks`, `account_statement`.`id` AS `acc_id`, `acc_type`, `user_id`, `user_type`, `name` AS `tranx_category` FROM `account_statement` INNER JOIN `transaction_types` ON `transaction_types`.`id` = `tranx_type` AND `status` = 0) AS `account_statement` ON `id` = `user_id` AND `b2b`.`user_type` = `account_statement`.`user_type` AND `account_statement`.`acc_type` = `b2b`.`acc_type` ORDER BY `status` DESC, `deposited`) AS `b2b` ". $where. $order_by;
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

	public function deposit_status($tranx_id, $status)
	{
		$query = "UPDATE `account_statement` SET `status` = '$status' WHERE `id` = '$tranx_id' AND `status` = '0'";
		$this->db->query($query);
		if($status === "1" && $this->db->affected_rows() > 0)
		{
			$query = "UPDATE `b2b_acc_info`, `account_statement` SET `deposit` = 
			`deposit` + `amount`, `last_deposit_trans` = `amount` WHERE `b2b_acc_info`.`id` = `user_id` AND `account_statement`.`id` = '$tranx_id'";
			$this->db->query($query);
			return $this->db->affected_rows() > 0 ? true : false;
		}
		else
			return $this->db->affected_rows() > 0 ? true : false;
	}

	public function add_deposit($as_data, $id, $amount)
	{
		$cols = "";
		$vals = "";
		foreach ($as_data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `account_statement` ($cols) VALUES ($vals)";
		$this->db->query($query);
		$as_id = $this->db->insert_id();
		$query = "UPDATE `b2b_acc_info` SET `deposit` = `deposit` + '$amount' WHERE `id` = '$id'";
		$this->db->query($query);
		return ($this->db->affected_rows() > 0 && $as_id > 0) ? true : false;
	}

	// public function agent_deposit_details($agent_id){
	// 	$select = "SELECT *, date_format(deposited_date, '%d/%m/%Y') as deposited FROM b2b_deposit WHERE agent_id = $agent_id";		
	// 	$query=$this->db->query($select);
	// 	if ($query->num_rows() > 0){
	// 		return $query->result();
	// 	} else {
	// 		return false;	
	// 	}
	// }

	// public function get_deposit_details($id){
	// 	$select = "SELECT *, date_format(deposited_date, '%d/%m/%Y') as deposited FROM b2b_deposit WHERE deposit_id = $id";
	// 	$query=$this->db->query($select);
	// 	if ($query->num_rows() > 0){
	// 		return $query->row();
	// 	} else {
	// 		return false;	
	// 	}
	// }

	// public function agent_deposits(){
	// 	$select = "SELECT *, date_format(deposited_date, '%d/%m/%Y') as deposited FROM b2b_deposit ORDER BY deposit_id DESC";		
	// 	$query = $this->db->query($select);
	// 	if ($query->num_rows() > 0){
	// 		return $query->result();
	// 	} else {
	// 		return false;	
	// 	}
	// }

	// public function add_agent_deposit($agent_id, $site_currency, $amount_credit, $agent_currency, $agent_currency_amount, $agentCurrencyExchangeRate, $deposited_date, $remarks, $trans_type){
	// 	$select2 = "select max(deposit_id)+1 as max from b2b_deposit";
	// 	$query = $this->db->query($select2);
	// 	$aa = $query->row();
	// 	$m_id1 = 0;
	// 	if($aa!=''){ $m_id1 = $aa->max;	}		
	// 	$m_id =  'AT'.date('d').date('m').($m_id1+10000);
	
	// 	$data = array(
	// 				'agent_id' => $agent_id,
	// 				'site_currency' => $site_currency,
	// 				'deposit_type' => $trans_type,
	// 				'request_type' => $trans_type,
	// 				'site_currency_amount' => $amount_credit,
	// 				'agent_currency' => $agent_currency,
	// 				'agent_currency_amount' => $agent_currency_amount,
	// 				'currency_exchange_rate' => $agentCurrencyExchangeRate,
	// 				'deposited_date' => $deposited_date,
	// 				'remarks' => $remarks,
	// 				'transaction_id' => $m_id,			
	// 				'status' => 'Accepted'
	// 			);			
	// 	$this->db->insert('b2b_deposit', $data);
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
	// 		$description = 'Deposit - : '.$m_id;
	// 		$query = "SELECT * FROM  b2b_acc_info where agent_id = $agent_id limit 1";
	// 		$query = $this->db->query($query);
	// 		if ($query->num_rows() > 0){
	// 			$result = $query->row();
	// 			$account_transaction = array(
	// 									'statment_type' => 'DEPOSIT',
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

	// public function update_deposit_status($id,$status,$agent_id,$amount_credit){
	// 	if($status=='Accepted'){
	// 		$qry = "update b2b_acc_info set balance_credit = (balance_credit + $amount_credit), last_credit = $amount_credit where agent_id = $agent_id";
	// 		$query = $this->db->query($qry);
			
	// 		$select2 = "SELECT * FROM  b2b_deposit where deposit_id = $id limit 1";
	// 		$query2 = $this->db->query($select2);
	// 		if ($query2->num_rows() > 0){
	// 			$am_result = $query2->row();
	// 			$description='Deposit - : '.$am_result->transaction_id;
	// 			$select3 = "SELECT * FROM  b2b_acc_info where agent_id = $agent_id limit 1";
	// 			$query3 = $this->db->query($select3);
	// 			if ($query3->num_rows() > 0){
	// 				$am_result3 = $query3->row();
	// 				$account_transaction = array(
	// 					'statment_type' => 'DEPOSIT',
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
	// 	$where = "deposit_id = ".$id;
	// 	$this->db->update('b2b_deposit', $data, $where);
	// 	return true;
	// }

 //    public function get_deposit_amount($b2b_id) {
	// 	$this->db->where('agent_id', $b2b_id);
	// 	$result = $this->db->get('b2b_acc_info');
	// 	return $result->row();
	// }

	// public function update_credit_amount($update_credit_amount,$b2b_id){
	// 	$this->db->where('agent_id',$b2b_id);
	// 	$this->db->update('b2b_acc_info',$update_credit_amount); 
	// }
	
	// public function update_account_transaction($account_transaction){
	// 	$this->db->insert('account_statment', $account_transaction); 
	// 	$bid = $this->db->insert_id();
	// 	$timing = date('Ymd');
	// 	$timing1 = date('His');
	// 	$txno = 'TX'.$timing.$bid.$timing1;
	// 				$update_account = array(
	// 					'statment_number' => $txno
	// 				);
					
	// 	$this->db->where('account_statment_id',$bid);		
 //        $this->db->update('account_statment', $update_account);					
	// }

	// public function currency_convertor($amount,$from,$to){
 //    	$from = strtoupper($from);   	
 //    	$this->db->where('country',$from);
 //    	$price = $this->db->get('currency_converter')->row();
 //    	$value = $price->value;
    	
 //    	if($from === $to){
 //    		$amount = $amount/1;
 //    		return number_format(($amount) ,2,'.','');
 //    	} else {
 //    		$amount = ($amount)/($value);
 //    		return number_format(($amount) ,2,'.','');
 //    	}
 //    }

 //    public function PercentageToAmount($total,$percentage){
	// 	$amount = ($percentage/100) * $total;
	// 	$total = number_format(($total+$amount) ,2,'.','');
	// 	return $total;
	// }
}