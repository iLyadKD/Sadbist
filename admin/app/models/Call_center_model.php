<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	############################################################################
	This file is coded and updated by Muhammed Riyas[muhammed.r@provabmail.com].
	############################################################################
*/

class Call_center_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
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
		$query = "INSERT INTO `cc_staff`($cols) VALUES ($vals)";
		$this->db->query($query);
		$user = $this->db->insert_id();
		if (!empty($user))
		{
			return $user;
		}
		return false;
	}

	public function updateCallCenterData($data){

		$this->db->where('booking_id',$data['booking_id']);
		$res = $this->db->get('cc_items')->num_rows();
	 	
        if($res == 0){
        	$this->db->insert('cc_items',$data);
			return $this->db->insert_id();
        }

        return 0;
		
	}

	public function assignStaff($data){
		$dat = array("assigned_to" => $data['assigned_to'] );
		$this->db->update('cc_items',$dat,array('id' => $data['id']));
		return $this->db->affected_rows();
	}

	public function getItemList($id=""){

		$status      = $this->session->userdata("status");
		$assigned_to = $this->session->userdata("assigned_to");

		if($status == ""){
			$status = 4; //purchased
			$this->session->set_userdata("status",$status);
		}
		if($assigned_to == ""){
			$assigned_to = "";
			$this->session->set_userdata("assigned_to",$assigned_to);
		}

		$response = array("count" => 0, "result" => array());

		$this->db->select('*,cc.id as item_id,sm.id as status_id');
		$this->db->from('cc_items cc');
		$this->db->join('booking_details bd','cc.booking_id = bd.id');
		$this->db->join('bookings bk','bd.id = bk.id');
		$this->db->join('cc_status_master sm','sm.id = cc.status');
		$this->db->where("cc.status",$status);
		$this->db->where("cc.assigned_to",$assigned_to);
		if($id != ""){
			$this->db->where('cc.id',$id);
		}

		$get = $this->db->get();
		
		if($data = $get->result()){

			$response = array("count" => $get->num_rows(), "result" => $data);
		}
		return $response;
	}

	public function getStaffList($id = 6){
		$this->db->where('user_type',$id);
		$this->db->select('id,firstname as fname,lastname as lname, user_type');
		return $this->db->get('admin')->result();
	}

	public function getAllFlightBookings($i_or_d = ""){
		if($i_or_d != false)
		 $this->db->where('i_or_d',$i_or_d);
		 $this->db->join('bookings bk','bd.id = bk.id');
		 // $this->db->order_by('bd.id','DESC');

		return $this->db->get('booking_details bd');
	}

	public function updateItemStatus($data){
		// echo '<pre>',print_r($data);exit; 
		$id        = $data['item_val_status'];
		$add['status'] = $status_id = $data['cc_status'];
		$date = date("Y-m-d H:i:s");
		// $a = 1;
		switch($status_id){

			case 1 : //NEW
				$add['dt_new'] = $date;
				break;

			case 2 : //TIMED OUT
				$add['dt_timeout'] = $date;
				break;

			case 3 : //REJECTED
				$add['dt_reject'] = $date;
				break;

			case 4 : //PURCHASED
				$add['dt_purchase']      = $date;
				$add['purchase_details'] = (isset($data['PURCHASED'])) ? json_encode(@$data['PURCHASED']) : '';
				$a = ($add['purchase_details'] == false) ? 0 : 1;
				break;

			case 5 : //BOOKED
				$add['dt_book']      = $date;
				$add['book_details'] = (isset($data['BOOKED'])) ? json_encode(@$data['BOOKED']) : '';
				$a = ($add['book_details'] == false) ? 0 : 1;
				break;

			case 6 : //ISSUE
				$add['dt_issue']       = $date;
				$add['issue_details']  = (isset($data['ISSUE'])) ? json_encode(@$data['ISSUE']) : '';
				$a = ($add['issue_details'] == false) ? 0 : 1;
				break;

			case 7 : //REFUND REQUESTED
				$add['dt_refund_rq'] = $date;
				$add['refund_rq_details']  = (isset($data['REFUND REQUESTED'])) ? json_encode(@$data['REFUND REQUESTED']) : '';
				$a = ($add['refund_rq_details'] == false) ? 0 : 1;
				break;

			case 8 : //REFUNDED
				$add['dt_refunded'] = $date;
				$add['refunded_details']  = (isset($data['REFUNDED'])) ? json_encode(@$data['REFUNDED']) : '';
				$a = ($add['refunded_details'] == false) ? 0 : 1;
				break;

			default:
			    break;	
		}

		if($status_id > 3 && $a == 0){
			return 0;
		}

		$this->db->where('id',$id);
		$this->db->update('cc_items',$add);

		return $this->db->affected_rows();
	}

	public function updateItemDetails($data){
		// echo '<pre>',print_r($data);exit; 
		$id        = $data['item_id'];
		$add['status']       = $status_id = $data['cc_status'];
		$add['actual_purchase_price'] = $data['actual_price'];
		if(isset($data['assigned_to'])){
			$add['assigned_to']  = $data['assigned_to'];
		}

		$date = date("Y-m-d H:i:s");
		// $a = 1;
		switch($status_id){

			case 1 : //NEW
				$add['dt_new'] = $date;
				break;

			case 2 : //TIMED OUT
				$add['dt_timeout'] = $date;
				break;

			case 3 : //REJECTED
				$add['dt_reject'] = $date;
				break;

			case 4 : //PURCHASED
				$add['dt_purchase']      = $date;
				$add['purchase_details'] = (isset($data['PURCHASED'])) ? json_encode(@$data['PURCHASED']) : '';
				$a = ($add['purchase_details'] == false) ? 0 : 1;
				break;

			case 5 : //BOOKED
				$add['dt_book']      = $date;
				$add['book_details'] = (isset($data['BOOKED'])) ? json_encode(@$data['BOOKED']) : '';
				$a = ($add['book_details'] == false) ? 0 : 1;
				break;

			case 6 : //ISSUE
				$add['dt_issue']       = $date;
				$add['issue_details']  = (isset($data['ISSUE'])) ? json_encode(@$data['ISSUE']) : '';
				$a = ($add['issue_details'] == false) ? 0 : 1;
				break;

			case 7 : //REFUND REQUESTED
				$add['dt_refund_rq'] = $date;
				$add['refund_rq_details']  = (isset($data['REFUND REQUESTED'])) ? json_encode(@$data['REFUND REQUESTED']) : '';
				$a = ($add['refund_rq_details'] == false) ? 0 : 1;
				break;

			case 8 : //REFUNDED
				$add['dt_refunded'] = $date;
				$add['refunded_details']  = (isset($data['REFUNDED'])) ? json_encode(@$data['REFUNDED']) : '';
				$a = ($add['refunded_details'] == false) ? 0 : 1;
				break;

			default:
			    break;	
		}

		if($status_id > 3 && $a == 0){
			return 0;
		}

		$this->db->where('id',$id);
		$this->db->update('cc_items',$add);
		// echo $this->db->last_query();exit;
		return 1;
	}

	public function getStaff($id = ""){
		if($id != ""){
			$this->db->where('id',$id);
		}
		$this->db->select('id,firstname as fname,lastname as lname, user_type');
		$data = $this->db->get('admin')->row();
		return $data;
	}

	public function addCompany($data){
		$this->db->insert('vendor_info',$data);

		return  $this->db->insert_id();
	}

	public function companyList($where = '', $order_by = '', $limit = '')
	{
		$response = array('count' => 0, 'result' => array());
		$query = "SELECT * FROM `vendor_info`". $where. $order_by;
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

	public function getCharterDetails($url = ""){
		if($url != ""){
			$this->db->where('url',$url);
		}

		return $this->db->get('vendor_info')->row();
	}

	public function getStatusList($id = ""){
		if($id != ""){
			$this->db->where('id',$id);
		}
		$this->db->where('id <> 9');
		return $this->db->get('cc_status_master')->result();
	}

	public function getPaymentTrans($book_id = ""){
		if($book_id != ""){
			$this->db->where('book_id',$book_id);
		}

		return $this->db->get('payment_trans');
	}

	public function get_country(){
		$this->db->select('country_en as name,id as country_code')
			       ->from('countries');
		$query = $this->db->get();
      	if($query->num_rows()> 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	}

	public function updateBookingDetails($book_id,$data){ 
		$this->db->where('id',$book_id);
		$this->db->update('booking_details',$data);
		
		return 1;

	}

}