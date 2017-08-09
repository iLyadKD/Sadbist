<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Booking_Model extends CI_Model {

	public function getAllFlightBookings($i_or_d = ""){
		if($i_or_d != false)
		 $this->db->where('i_or_d',$i_or_d);

		return $this->db->get('booking_details');
	}

	// public function getHotelBookings($user_id, $user_type){      
	// 	$this->db->where('booking_global.user_type', $user_type);
	// 	$this->db->where('booking_global.user_id', $user_id);
	// 	$this->db->where('booking_global.module', 'HOTEL');
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function getFlightBookings($user_id, $user_type){        
	// 	$this->db->where('booking_global.user_type', $user_type);
	// 	$this->db->where('booking_global.user_id', $user_id);
	// 	$this->db->where('booking_global.module', 'FLIGHT');
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function getAllBookings(){
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function b2b_getAllBookings(){
	// 	$this->db->where('user_type', 2);
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function b2c_getAllBookings(){
	// 	$this->db->where('user_type', 3);
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function guest_getAllBookings(){
	// 	$this->db->where('user_type', 4);
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function getBookingbyPnr($pnr_no,$module){
	// 	if($module == 'FLIGHT'){
	// 		$this->db->join('booking_flight','booking_global.ref_id = booking_flight.ID');
	// 	}else if($module == 'HOTEL'){
	// 		$this->db->join('booking_hotel','booking_global.ref_id = booking_hotel.ID');
	// 	}
	// 	$this->db->where('pnr_no',$pnr_no);
	// 	return $this->db->get('booking_global');
	// }

	// public function get_airport_name($code){
	// 	$this->db->select('airport_name as airport');
	// 	$this->db->where('airport_code', $code);
	// 	$data = $this->db->get('flight_airport_list')->row();
	// 	return $data->airport;
	// }

	// public function get_airline_name($code){
	// 	$this->db->select('airline_name as airline');
	// 	$this->db->where('airline_code', $code);
	// 	$data = $this->db->get('airlines_list')->row();
	// 	return $data->airline;
	// }

	// public function get_api_credentials($api){
	// 	$this->db->where('api_name', $api);
	// 	$this->db->where('status', 1);
	// 	return $this->db->get('api');
	// }

	// public function Update_Booking_Global($pnr_no, $update_booking, $module){
	// 	$this->db->where('pnr_no',$pnr_no);
	// 	$this->db->where('module',$module);
	// 	$this->db->update('booking_global', $update_booking);
	// }
	
	// public function check_promocode($promo_code){
	// 	$this->db->where('promo_code',$promo_code);
	// 	$this->db->where('status',1);
	// 	return $this->db->get('promo');
	// }
	
	// public function getBookingsByUser($user_id, $user_type){
	// 	$this->db->where('booking_global.user_type', $user_type);
	// 	$this->db->where('booking_global.user_id', $user_id);
	// 	return $this->db->get('booking_global');
	// }

	// public function getBookingPnr($pnr_no){
	// 	$this->db->where('pnr_no',$pnr_no);
	// 	return $this->db->get('booking_global');
	// }

	// public function getBookingFlightData($ref_id) {
	// 	$this->db->where('id', $ref_id);
	// 	return $this->db->get('booking_flight');
	// }

	// public function getBookingHotelData($ref_id) {
	// 	$this->db->where('id', $ref_id);
	// 	return $this->db->get('booking_hotel');
	// }

	// public function get_vendor_details($code){
	// 	$this->db->where('alternate_vendor_code', $code);
	// 	$data = $this->db->get('car_vendors')->row();
	// 	return $data->short_name;
	// }

	// public function getRefineSearchResult($post, $user_type){
	// 	if($user_type != "") {
	// 		$this->db->where('user_type', $user_type);
	// 	} else {
	// 		return false;
	// 	}

	// 	if($post['datetype']!='') {
	// 		if($post['datetype']=='vdate'){
	// 			$this->db->where('booking_global.voucher_date >=', $post['from']);
	// 			$this->db->where('booking_global.voucher_date <=', $post['to']);
	// 		}
	// 		if($post['datetype']=='tdate'){
	// 			$this->db->where('booking_global.travel_date >', $post['from']);
	// 			$this->db->where('booking_global.travel_date <', $post['to']);
	// 		}
	// 	}
		
	// 	if($post['module'] !='') {
	// 		$this->db->where('booking_global.module', $post['module']);
	// 	}
	// 	if($post['apistatus'] !='') {
	// 		$this->db->where('booking_global.api_status', $post['apistatus']);
	// 	}
	// 	if($post['bookingstatus'] !=''){
	// 		$this->db->where('booking_global.booking_status', $post['bookingstatus']);
	// 	}
	// 	$this->db->order_by('booking_global.id','desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function GetUserData($user_type, $user_id){
	// 	if($user_type == '3'){
	// 		$this->db->where('user_id', $user_id);
	// 		return $this->db->get('b2c');
	// 	}else if($user_type == '2'){
	// 		$this->db->where('agent_id', $user_id);
	// 		return $this->db->get('b2b');
	// 	}
	// }

	// public function getMarkupData($module_type, $ref_id) {
	// 	switch($module_type) {
	// 		case "flight":
	// 			$this->db->where('ID', $ref_id);
	// 			return $this->db->get('booking_flights');
	// 			break;
	// 		case "hotel":
	// 			$this->db->where('ID', $ref_id);
	// 			return $this->db->get('booking_hotel');
	// 			break;
	// 		default:
	// 			return false;
	// 			break;
	// 	}
	// }

	// /*Returns admin details.*/
	// public function getAdminDetails($admin_id) {
	// 	$this->db->where('agent_id', $admin_id);
	// 	return $this->db->get('b2b');
	// }

	// public function b2b_refundBookingData() {
	// 	$this->db->where('booking_status', 'CANCELLED');
	// 	$this->db->where('user_type', 2);
	// 	$this->db->order_by('id', 'desc');
	// 	return $this->db->get('booking_global');
	// }

	// public function getAdminAccInfo($admin_id) {
	// 	$this->db->where('agent_id', $admin_id);
	// 	return $this->db->get('b2b_acc_info');
	// }

	// public function updateAccInfo($updatedAmt, $admin_id) {
	// 	$this->db->where('agent_id', $admin_id);
	// 	$updateArr = array('balance_credit'=>$updatedAmt);
	// 	$this->db->update('b2b_acc_info', $updateArr);
	// 	return true;
	// }

	// public function updateAccStmt($account_stmt_arr) {
	// 	$this->db->insert('account_statment', $account_stmt_arr);
	// 	return true;
	// }

	// public function updateBookingGlobal($pnr_no, $booking_no, $bookingGlobalArr) {
	// 	$this->db->where('booking_no', $pnr_no);
	// 	$this->db->where('pnr_no', $booking_no);
	// 	$this->db->update('booking_global', $bookingGlobalArr);
	// 	return true;
	// }

	// public function getClientSettings($api){
	// 	$this->db->select('*');
	// 	$this->db->from('api');
	// 	$this->db->where('api_name', $api);
	// 	$this->db->where('status', 1);
	// 	$query = $this->db->get();
	// 	return $query->row();
	// }

	// public function get_curr_val($curr){
	// 	$curr = strtoupper($curr);
	// 	$this->db->where('country',$curr);
	// 	$price = $this->db->get('currency_converter')->row();
	// 	$price->value;
	// 	return $value = $price->value;
	// }

}