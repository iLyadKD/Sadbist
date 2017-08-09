<?php 

/**
*  Class Functions
*/
class Functions 
{	

	var $db;
	function __construct()
	{
		$db=mysqli_connect("localhost","ticketya_10020ir","Admin@10020.ir$$7","ticketya_10020ir");

		$this->db = $db;
	}

	function addPaymentDetails($data,$udata){
		$con = $this->db;
		$order_id = mysqli_real_escape_string($con,$data['orderId']);
		$trans_id = mysqli_real_escape_string($con,$data['orderId']);
		$payer_id = mysqli_real_escape_string($con,$data['payerId']);
		$sale_order_id = mysqli_real_escape_string($con,$data['orderId']);
		$amount = mysqli_real_escape_string($con,$data['amount']);
		$local_date = mysqli_real_escape_string($con,$data['localDate']);
		$local_time = mysqli_real_escape_string($con,$data['localTime']);
		$additional_data = mysqli_real_escape_string($con,$data['additionalData']);
		$call_back = mysqli_real_escape_string($con,$data['callBackUrl']);
		$payment_status = 'FAILED';
		$created_date = date("Y-m-d H:i:s");
		$updated_date = date("Y-m-d H:i:s");
		$ip = $_SERVER['REMOTE_ADDR'];

		$user_name  = mysqli_real_escape_string($con,$udata['name']);
		$user_email = mysqli_real_escape_string($con,$udata['email']);
		$user_phone = mysqli_real_escape_string($con,$udata['phone']);

		$sql = "INSERT INTO payment_trans (user_name,user_email,user_phone,trans_id,order_id,payer_id,sale_order_id,amount,local_date,local_time,additional_data,call_back,payment_status,created_date,updated_date,ip) VALUES('$user_name','$user_email','$user_phone','$trans_id','$order_id','$payer_id','$sale_order_id','$amount','$local_date','$local_time','$additional_data','$call_back','$payment_status','$created_date','$updated_date','$ip')";
		mysqli_query($con,$sql);

		return mysqli_insert_id($con);
	}

	function updatePayRequestAction($data){
		$con = $this->db;

		$status = $data['status'];
		$res_code = $data['res_code'];
		$SaleOrderId = $data['SaleOrderId'];
		$SaleReferenceId = $data['SaleReferenceId'];
		$data_res = $data['data_res'];
		$date = date("Y-m-d H:i:s");

		$sql = "UPDATE payment_trans SET payment_status = '$status', response_code_pay = '$res_code', sale_ref_id = '$SaleReferenceId', pay_response = '$data_res', updated_date = '$date' WHERE order_id = '$SaleOrderId'";

		mysqli_query($con,$sql);

		return 1;
	}

	function verifyUpdateAction($data){
		$con = $this->db;

		$SaleOrderId     = $data['SaleOrderId'];
		$res_code        = $data['res_code'];
		$SaleReferenceId = $data['SaleReferenceId'];
		$status          = $data['status'];
		$date            = date("Y-m-d H:i:s");

		$sql = "UPDATE payment_trans SET payment_status = '$status', response_code_verify = '$res_code' , updated_date = '$date' WHERE sale_ref_id = '$SaleReferenceId' AND order_id = '$SaleOrderId'";

		mysqli_query($con,$sql);

		return $SaleOrderId;
	}

	function settleUpdateAction($data){
		$con = $this->db;

		$SaleOrderId = $data['SaleOrderId'];
		$res_code = $data['res_code'];
		$SaleReferenceId = $data['SaleReferenceId'];
		$status = $data['status'];
		$date = date("Y-m-d H:i:s");

		$sql = "UPDATE payment_trans SET payment_status = '$status', response_code_settle = '$res_code' , updated_date = '$date' WHERE sale_ref_id = '$SaleReferenceId' AND order_id = '$SaleOrderId'";

		mysqli_query($con,$sql);

		return $SaleOrderId;
	}

	function getData($id){
		$con = $this->db;
		
		$sql = "SELECT * FROM payment_trans WHERE order_id = '$id'";

		$res = mysqli_query($con,$sql)or die(mysqli_error($con));

		if(mysqli_num_rows($res) > 0){
			$row = mysqli_fetch_assoc($res);
		}

		return $row;
	}
	
}

?>