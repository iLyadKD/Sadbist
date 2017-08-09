<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function addPaymentDetails($data, $udata, $bk_id)
	{
		$order_id = $data['orderId'];
		$trans_id = $data['orderId'];
		$payer_id = $data['payerId'];
		$sale_order_id = $data['orderId'];
		$amount = $data['amount'];
		$local_date = $data['localDate'];
		$local_time = $data['localTime'];
		$additional_data = $data['additionalData'];
		$call_back = $data['callBackUrl'];
		$payment_status = 'FAILED';
		$created_date = date("Y-m-d H:i:s");
		$updated_date = date("Y-m-d H:i:s");
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$user_name  = $udata['name'];
		$user_email = $udata['email'];
		$user_phone = $udata['phone'];
		$bookId = $bk_id;

		$query = "INSERT INTO payment_trans (user_name,user_email,user_phone,trans_id,order_id,payer_id,sale_order_id,amount,local_date,local_time,additional_data,call_back,payment_status,created_date,updated_date,ip,book_id) VALUES('$user_name','$user_email','$user_phone','$trans_id','$order_id','$payer_id','$sale_order_id','$amount','$local_date','$local_time','$additional_data','$call_back','$payment_status','$created_date','$updated_date','$ip','$bookId')";
	
		$this->db->query($query);
		
		return $this->db->insert_id();

	}

	function updatePayRequestAction($data){
		

		$status = $data['status'];
		$res_code = $data['res_code'];
		$SaleOrderId = $data['SaleOrderId'];
		$SaleReferenceId = $data['SaleReferenceId'];
		$data_res = $data['data_res'];
		$date = date("Y-m-d H:i:s");

		$query = "UPDATE payment_trans SET payment_status = '$status', response_code_pay = '$res_code', sale_ref_id = '$SaleReferenceId', pay_response = '$data_res', updated_date = '$date' WHERE order_id = '$SaleOrderId'";

		$this->db->query($query);
		
		return 1;
	}

	function verifyUpdateAction($data){

		

		$SaleOrderId     = $data['SaleOrderId'];
		$res_code        = $data['res_code'];
		$SaleReferenceId = $data['SaleReferenceId'];
		$status          = $data['status'];
		$date            = date("Y-m-d H:i:s");
		// $bookId = $data['bookId'];

		$query = "UPDATE payment_trans SET payment_status = '$status', response_code_verify = '$res_code' , updated_date = '$date' WHERE sale_ref_id = '$SaleReferenceId'";


		$this->db->query($query);
		
		return $SaleOrderId;
	}

	function settleUpdateAction($data){
		

		$SaleOrderId = $data['SaleOrderId'];
		$res_code = $data['res_code'];
		$SaleReferenceId = $data['SaleReferenceId'];
		$status = $data['status'];
		$date = date("Y-m-d H:i:s");
		$bookId = $data['bookId'];

		$query = "UPDATE payment_trans SET payment_status = '$status', response_code_settle = '$res_code' , updated_date = '$date' WHERE sale_ref_id = '$SaleReferenceId' AND book_id = '$bookId'";

		$this->db->query($query);
		
		return $SaleOrderId;
	}

	function UpdatePaymentStatus($data, $pay_status = 1, $book_status = 5){

		$SaleOrderId = $data['SaleOrderId'];
		$res_code = $data['res_code'];
		$SaleReferenceId = $data['SaleReferenceId'];
		//$status = $data['status'];
		$date = date("Y-m-d H:i:s");
		$bookId = $data['bookId'];

		$data = json_encode($data);

		$query = "UPDATE `bookings` AS `bo`, `booking_details` AS `bd` SET `bd`.`payment_status` = '$pay_status', `bd`.`payment_details` = '$data', `bo`.`book_status` = '$book_status' WHERE  `bo`.`id` = '$bookId' AND `bd`.`id` = '$bookId'";

		$this->db->query($query);


		
		return $SaleOrderId;

	}

	function GetPaymentDetails($bid){

		$query = "SELECT * FROM `payment_trans` WHERE `book_id` = '$bid'";
		$result = $this->db->query($query);
		return $result->row();

	}

	function GetPaymentDetailsByOrderId($o_id){

		$query = "SELECT * FROM `payment_trans` WHERE `order_id` = '$o_id'";
		$result = $this->db->query($query);
		return $result->row();

	}
	
}

?>