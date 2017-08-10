<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#########################################################################
	This file is coded and updated by Muhammed Riyas[riyas.provab@gmail.com].
	#########################################################################
*/
class Call_center extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Call_center_model");
		$this->data["page_main_title"] = "Call Center Management";
		$this->data["page_title"] = "Call Center Management";
	}

	/*
	* Call center dashboard 
	*/	
	public function index()
	{	

		$data = $this->Call_center_model->getItemList(); 

		$data['staffs'] = $this->Call_center_model->getStaffList();
		$data['status_list'] = $this->Call_center_model->getStatusList();
		$data['status_sess']      = $this->session->userdata("status");
		$data['assigned_to_sess'] = $this->session->userdata("assigned_to");
		$data['user_type'] = $this->session->userdata(SESSION_PREPEND . 'user_type');

		$this->load->model('sms_model');
		$data['sms_types_list'] = $this->sms_model->getAllData();
		

		$data1 = array();
		$myCallback = function($key) use(&$data1){
			$data1[$key->id] = $key->fname." ".$key->lname;
		};
		array_walk($data['staffs'], $myCallback);

		$data['get_staff'] = $data1;

		$this->load->view("call_center/dashboard",$data);
	}


	/*
	* Add Call center staffs
	*/
	// public function add_staff(){

	// 	if($this->input->is_ajax_request()  && count($this->input->post()) > 0)
	// 	{
	// 		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
	// 		$response["title"] = $this->data["page_main_title"]." - Add New Staff";
	// 		$this->form_validation->set_rules("fname", "Email", "trim|required");
	// 		$this->form_validation->set_rules("lname", "Email", "trim|required");
	// 		$this->form_validation->set_rules("phone1", "Contact No", "trim|required");

	// 		if($this->form_validation->run() !== false)
	// 		{
	// 			$data["fname"]        = $firstname = ucfirst($this->input->post("fname"));
	// 			$data["lname"]        = $lastname = ucfirst($this->input->post("lname"));
	// 			$data["phone1"]       = $phone1 = strtolower($this->input->post("phone1"));
	// 			$data["phone2"]       = $phone2 = $this->input->post("phone2");
	// 			$data["created_date"] = date("Y-m-d H:i:s");

	// 			$user_data = array(
	// 							'fname'  => $firstname,
	// 							'lname'  => $lastname,
	// 							'phone1' => $phone1,
	// 							'phone2' => $phone2
	// 						 );
	// 			try
	// 			{
	// 				$result = $this->Call_center_model->add($user_data);
	// 				if($result !== false)
	// 				{
	// 						$response["msg"] = "Staff added successfully.";
	// 						$response["msg_status"] = "success";
	// 						$response["status"] = "true";
						
	// 				}
	// 				else
	// 				{
	// 					$response["msg"] = "Failed to add new Staff.";
	// 				}
	// 			}
	// 			catch(Exception $ex)
	// 			{
	// 				$response["msg"] = "Sorry, Operation failed.";
	// 			}
	// 			echo json_encode($response);exit;
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$this->data["page_title"] = "Add New Staff";
	// 		$this->load->view("call_center/add-staff");
	// 	}
	// }


	/*
	* Update Call Center Data Items from Booking Table
	*/
	public function update_items(){
		$this->load->model("Booking_model");

		//get Domestic data {D}
		$dataBookings = $this->Call_center_model->getAllFlightBookings('D');

		foreach ($dataBookings->result() as $key => $value) {
			$book_id    = $value->id;
			$book_status = $value->book_status;
			$datetime   = date("Y-m-d H:i:s");

			//response msg
			$response = array("status" => "false", "msg_status" => "danger",  "msg" => "Not updated ".$book_id.".");
			$updateData = array();
			switch($book_status){
				// case 0 : //pending
				// 	$updateData['status'] = 1; //mark as new in callcenter
				// 	$updateData['dt_new'] = date("Y-m-d H:i:s");
				// 	break;
				case 1 : //reserved or payment success bookings 	

					$input_json    = json_decode($value->input_json);
					$traveller_det = json_decode($value->traveller_json,true);
					$departure    = json_decode($value->departures)[0];
					$people_count = $input_json->adult + $input_json->child + $input_json->infant;

					$data = array();
					$data['flight_number'] = $value->pnr_number;
					$data['from'] = $this->get_actual_text($departure->departure_from);
					$data['to'] = $this->get_actual_text($departure->arrival_to);
					$data['purchased_date'] = $value->booked_date;
					$data['purchased_time'] = date('h:i', strtotime($departure->departure_dttm));
					$data['people_count'] = $people_count + ' نفر';
					$data['booking_ref'] = (1000 + $value->id);
					
					$data['contact'] = $value->contact;
					
					$this->send_sms($data);

					$paymentDetail = $this->Call_center_model->getPaymentTrans($value->id);
					$payData = $paymentDetail->row();
					$updateData['status']  = 4; // mark as purchased in call center
					$updateData['dt_purchase'] = $payData->updated_date;
					$updateData['dt_new'] = date("Y-m-d H:i:s");
					break;
				// case 3 : //cancelled 	
				// 	$updateData['status']  = 8; // mark as refunded in call center
				// 	$updateData['dt_refunded'] = date("Y-m-d H:i:s");
				// 	$updateData['dt_new'] = date("Y-m-d H:i:s");
				// 	break;
				case 4 : //failed 	
					$paymentDetail = $this->Call_center_model->getPaymentTrans($value->id);
					$payData = $paymentDetail->row();
					$updateData['status']  = 3; // mark as rejected out in call center
					$updateData['dt_reject'] = $payData->updated_date;
					$updateData['dt_new'] = date("Y-m-d H:i:s");
					break;	

				default  : //timeout or unattended 	
					$updateData['status']  = 2; // mark as timed out in call center
					$updateData['dt_timeout'] = $value->booked_date;
					$updateData['dt_new'] = date("Y-m-d H:i:s");
					break;	
			}		
			


			$updateData['booking_id'] = $book_id;
			// $updateData['created_date'] = $datetime;
			$updateData['updated_date'] = $datetime;

			if($book_id != false){
				$updateQry = $this->Call_center_model->updateCallCenterData($updateData);
				if($updateQry){
					$response["msg"] = "Updated successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
				}
			}

			echo json_encode($response);
		}
	}

	/*
	* Assign item to a staff
	*/
	public function assign_staff(){
		$item = $this->input->post();

		$data['id']          = $item['item_val'];
		$data['assigned_to'] = $item['cc_staffs'];
		if($data['id'] != "" && $data['assigned_to'] != ""){
			$updateAction = $this->Call_center_model->assignStaff($data);
			if($updateAction != false){
				echo  $updateAction;exit;
			}
		}

		echo 0;exit;
	}

	/*
	* Prepare SMS To Current Items
	*/
	public function prepare_sms_to_current_items(){
		$this->load->model('sms_model');
		$this->load->helper('persian_date');
		
		$item = $this->input->post();
		$items_val          = json_decode($item['items_val']);


		$is_cancel = isset($item['cancel']) ? 1 : 0;
		$new_time = isset($item['new_time']) ? $item['new_time'] : 0;
		$new_date = isset($item['new_date']) ? $item['new_date'] : 0;

		if($items_val != ""){
			
			$sent_msgs = array();
			foreach ($items_val as $key => $flight_ref_no) {
				$flight_item_id = $flight_ref_no - 1000;
				$item_info = $this->Call_center_model->getItemList($flight_item_id)['result'][0];
				if ($item_info) {
					
					$departures    = json_decode($item_info->departures)[0];
					$from = $this->get_actual_text($departures->departure_from);
					$to = $this->get_actual_text($departures->arrival_to);
					$airline_name = $departures->airline_name;
					$date = to_date(date('Y/m/d', strtotime($departures->departure_dttm)), 'Y/m/d');

					if ($departures && $is_cancel) {
						$message = "مسافرين محترم پرواز شماره ".$item_info->pnr_number." مسير ".$from." - ".$to." عباس  مورخ ".$date." ضمن عرض پوزش به اطلاع ميرساند پرواز شما به علت محدوديت عملياتي باطل اعلام ميگردد. با تشكر \nهواپيمائي ".$airline_name." \nجهت کسب اطلاعات بیشتر با بخش پشتیبانی تماس حاصل فرمایید. \n02154623000";
					}else{
						$message = "مسافرين محترم پرواز شماره  ".$item_info->pnr_number." مسير ".$from." - ".$to." مورخ  ".$date." ضمن عرض پوزش به اطلاع ميرساند پرواز شما به علت تأخير در ورود از مسير قبل با تأخير در ساعت  ".$new_time." مورخ ".$new_date." انجام  ميگردد. با تشكر \nهواپيمائي نفت ايران";
					}
					$this->send_sms_msg($message, $item_info->contact);
					// $sent_msgs[] = $message;
				}
			}

			$sent_msgs_count = count($sent_msgs);
			if(count($sent_msgs) > 0){
				echo json_encode(array('response' => $sent_msgs_count, 'sent' => $sent_msgs));exit;
			}else{
				echo json_encode(array('response' => 0, 'sent' => array()));exit;
			}
		}

		echo 0;exit;
	}

	/*
	* Get Item Details
	*/
	public function item_details($id){

		$id      = base64_decode($id);
		$details = $this->Call_center_model->getItemList($id);

		$data['item_det']      = $details['result'][0];
		$data['traveller_det'] = json_decode($data['item_det']->traveller_json,true);
		$data['departures']    = json_decode($data['item_det']->departures)[0];
		$arrivals    = json_decode($data['item_det']->arrivals);
		if (count($arrivals)) {
			$data['arrivals']    = $arrivals[0];
		}
		$data['country']       = $this->Call_center_model->get_country();
		$data['staff']         = array();
		if($data['item_det']->assigned_to != ""){
			$data['staff']    = $this->Call_center_model->getStaff($data['item_det']->assigned_to);
		}
		$user_type = $this->session->userdata(SESSION_PREPEND . "user_type");
		$current_user_id = $this->session->userdata(SESSION_PREPEND . "id");

		$data['has_permission'] = ($data['item_det']->assigned_to == $current_user_id || $user_type == SUPER_ADMIN_USER || $user_type == ADMIN_USER);
		$data['has_admin_permission'] = $user_type == SUPER_ADMIN_USER || $user_type == ADMIN_USER;

		if($details){
			$this->load->view('call_center/details',$data);
		}
		else{
			$this->load->library('user_agent');
			redirect($this->agent->referrer(), 'refresh');
		}
	}

	
	/*
	* Update Status
	*/
	public function update_item_status(){
		$post = $this->input->post();
		if(!empty($post) && $post['item_val_status'] != ""){
			$updateAction = $this->Call_center_model->updateItemStatus($post);	
			echo $updateAction;exit;
		}

	}

	/*
	* Update Item Details
	*/
	public function update_item_details(){
		$this->load->helper('persian_date');
		$post = $this->input->post();
		// echo '<pre>',print_r($post);exit; 
		//response msg
		$response = array("status" => "Failed", "msg_status" => "danger",  "msg" => "Not updated");

		if(!empty($post) && $post['item_id'] != ""){

		$itemKey = json_decode(base64_decode($post['item_id']));

		$item_id = $itemKey[0];
		$book_id = $itemKey[1];


		$paxData['traveller_json'] = json_encode($post['traveller_det']);
		
		$inout_bound_data = array();

		if (isset($post['inbound_total_cost'])) {
			$paxData['inbound_pnr_number'] = $post['inbound_pnr_number'];
			$inbound_total_cost = str_replace(',', '', $post['inbound_total_cost']);
			$inbound_api_cost = str_replace(',', '', $post['inbound_api_cost']);
			$inbound_actual_cost = str_replace(',', '', $post['inbound_actual_cost']);
			$inbound = array(
			                                     "total_cost" => $inbound_total_cost,
			                                     "api_cost" => $inbound_api_cost,
			                                     "actual_cost" => $inbound_actual_cost
			                                     );
			$paxData['inbound'] = json_encode($inbound);
		}
		if (isset($post['outbound_total_cost'])) {
			$paxData['pnr_number'] = $post['cc_pnr_number'];
			$outbound_total_cost = str_replace(',', '', $post['outbound_total_cost']);
			$outbound_api_cost = str_replace(',', '', $post['outbound_api_cost']);
			$outbound_actual_cost   = str_replace(',', '', $post['outbound_actual_cost']);
			$outbound = array(
			                                     "total_cost" => $outbound_total_cost,
			                                     "api_cost" => $outbound_api_cost,
			                                     "actual_cost" => $outbound_actual_cost
			                                     );
			$paxData['outbound'] = json_encode($outbound);
		}
		
		$itemData['item_id']   = $item_id;
		$itemData['cc_status'] = $post['cc_status'];
		
		$itemData['item_comments'] = $post['item_comments'];
		
		if (isset($post['from'])) {
			$item_response = $this->Call_center_model->getItemList($item_id);
			$item_info = null;
			if ($item_response['count'] > 0) {
				$item_info = $item_response['result'][0];
			}
			if ($item_info) {
				$departure    = json_decode($item_info->departures)[0];
				$departure->departure_from = $this->get_english_code_text($post['from']);
				$departure->arrival_to = $this->get_english_code_text($post['to']);
				$departure->airline_name = $post['airline_name'];
				$departure->departure_dttm = date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $post['flight_date'] .' '. $post['flight_time'])));
				$departures[0] = $departure;
				$paxData['departures'] = json_encode($departures);
			}
		}
		if (isset($post['outbound_actual_cost'])) {
			$itemData['actual_price']   = str_replace(',', '', $post['outbound_actual_cost']);
		}
		if(isset($post['cc_staffs']) && ($post['cc_staffs'] != "")){
			$itemData['assigned_to'] = $post['cc_staffs'];
		} 

		// echo '<pre>',print_r(array($paxData,$itemData));exit; 
			// $this->db->trans_begin();
			$updateItems = $this->Call_center_model->updateItemDetails($itemData);

			if($updateItems){
				$updateBooking = $this->Call_center_model->updateBookingDetails($book_id,$paxData);
				if($updateBooking){
					$response["msg"] = "Details updated successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "Success";
				}
				// else{
				// 	$this->db->trans_rollback();
				// }
			}
			// else{
			// 	$this->db->trans_rollback();
			// }
			
			if ($post['cc_status'] == 5) {
				$this->generate_tickets($item_id);
			}
		}
		echo json_encode($response);exit;

	}

	/*
	* Add new charter company to vendor_info
	*/
	public function add_company(){
		if($this->input->is_ajax_request()  && count($this->input->post()) > 0)
		{ 

			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");

			$response["title"] = $this->data["page_main_title"]." - Add New Company";
			$this->form_validation->set_rules("cname", "Company Name", "trim|required");
			$this->form_validation->set_rules("url", "URL", "trim|required");
			$this->form_validation->set_rules("uname", "User Name", "trim|required");
			$this->form_validation->set_rules("pswd", "Password", "trim|required");
			$this->form_validation->set_rules("contact_name", "Contact Name", "trim|required");
			$this->form_validation->set_rules("phone1", "Support Phone", "trim|required");
			$this->form_validation->set_rules("phone2", "Other Phone", "trim|required");

			if($this->form_validation->run() !== false)
			{
				$data["company_name"]  = ucfirst($this->input->post("cname"));
				$data["url"]           = $this->input->post("url");
				$data["u_name"]        = $this->input->post("uname");
				$data["p_word"]        = $this->input->post("pswd");
				$data["contact_name"]  = ucfirst($this->input->post("contact_name"));
				$data["support_phone"] = $this->input->post("phone1");
				$data["other_phone"]   = $this->input->post("phone2");
				$data["comment"]       = $this->input->post("comments");
				$data["created_date"]  = date("Y-m-d H:i:s");

				
				try
				{
					$result = $this->Call_center_model->addCompany($data);
					if($result !== false)
					{
							$response["msg"] = "Company added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						
					}
					else
					{
						$response["msg"] = "Failed to add new Company.";
					}
				}
				catch(Exception $ex)
				{
					$response["msg"] = "Sorry, Operation failed.";
				}
				echo json_encode($response);exit;
			}
		}
		else
		{
			$this->data["page_title"] = "Add New Company";
			$this->load->view("call_center/add-company");
		}
	}

	/*
	* List of charter companies
	*/
	public function companies_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("vendor_id", "company_name", "url", "u_name", "p_word", "contact_name","support_phone","other_phone","comment","created_date");

		$iDisplayStart = $this->input->get("iDisplayStart", true);
		$iDisplayLength = $this->input->get("iDisplayLength", true);
		$iSortCol_0 = $this->input->get("iSortCol_0", true);
		$iSortingCols = $this->input->get("iSortingCols", true);
		$sSearch = $this->input->get("sSearch", true);
		$sEcho = $this->input->get("sEcho", true);

		if($this->input->is_ajax_request())
		{
			// Limit
			$sLimit = " LIMIT 0, 25";
			if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] !== "-1" && is_numeric($_GET["iDisplayStart"]) && is_numeric($_GET["iDisplayLength"]))
				$sLimit = " LIMIT ".$_GET["iDisplayStart"].", ".$_GET["iDisplayLength"];

			// Ordering
			$sOrder = "";
			if (isset($_GET["iSortCol_0"]))
			{
				$sOrder = " ORDER BY ";
				for ($i = 0; $i < intval($_GET["iSortingCols"]); $i++)
				{
					if ($_GET["bSortable_".intval($_GET["iSortCol_".$i])] === "1")
						if($aColumns[intval($_GET["iSortCol_".$i])] !== "x")
							$sOrder .= $aColumns[intval($_GET["iSortCol_".$i])]." ".($_GET["sSortDir_".$i] === "asc" ? "asc" : "desc") .", ";
				}
				if ($sOrder === " ORDER BY ")
					$sOrder = "";
				if($sOrder !== "")
					$sOrder = substr_replace($sOrder, "", -2);
			}

			// Filtering
			$sWhere = " ";
			if (isset($_GET["sSearch"]) && $_GET["sSearch"] !== "")
			{
				$sWhere = " WHERE (";
				for ($i = 0; $i < count($aColumns); $i++)
				{
					if ( isset($_GET["bSearchable_".$i]) && $_GET["bSearchable_".$i] === "true")
						if($aColumns[$i] !== "x")
							$sWhere .= $aColumns[$i]." LIKE '%".$_GET["sSearch"]."%' OR ";
				}
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ")";
			}
			$result_list = $this->Call_center_model->companyList($sWhere, $sOrder, $sLimit);
			// echo '<pre>',print_r($result_list);exit; 
			$iTotal = $result_list["count"];

			$rResult = $result_list["result"];

			// Output
			$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iTotal,
			"aaData" => array()
			);
			$i = isset($_GET["iDisplayStart"]) ? ($_GET["iDisplayStart"] * 1) + 1 : 1;
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["vendor_id"]));
				$row = array();
				// $status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				// $status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control b2c_user_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				// $actions = "<div class='pull-left'>";
				// $actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons user_promocode b2c' data-placement='top' title='Send Promocode' href='javascript:void(0);'><i class='icon-barcode'></i></a>\n";
				// $actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Bookings' href='javascript:void(0);'><i class='icon-eye-open'></i></a>\n";
				// $actions .= "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Send Mail' href='".base_url($this->data["controller"]."/mail".DEFAULT_EXT."?user=".$id)."'><i class='icon-envelope'></i></a>\n";
				// $actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Profile' href='".base_url($this->data["controller"]."/view_profile".DEFAULT_EXT."?user=".$id)."'><i class='icon-search'></i></a>\n";
				// $actions .= "<br>";
				// $actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/edit_profile".DEFAULT_EXT."?user=".$id)."'><i class='icon-edit'></i></a>\n";
				// $actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_b2c_user' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				// $actions .= "</div>";
				// $image = is_null($aRow["image_path"]) || $aRow["image_path"] === "" ? upload_url(B2C_DEFAULT_IMG) : upload_url($aRow["image_path"]);
				// $image_html = "<img width='50px' height='50px' src='".$image."' alt=''>";
				$row[] = json_encode(array("sl_no" => $i++, "company_name" => $aRow["company_name"], "url" => $aRow["url"], "u_name" => $aRow["u_name"], "p_word" => $aRow["p_word"], "contact_name" => $aRow['contact_name'], "support_phone" => $aRow["support_phone"], "other_phone" => $aRow["other_phone"], "comment" => $aRow['comment']));
				
				$output["aaData"][] = $row;
			}
		}
		else
		{
			$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => "0",
			"iTotalDisplayRecords" => "0",
			"aaData" => array()
			);
		}
		echo json_encode($output);exit;
	}

	public function getInputElements(){
		return; // stopped

		$status = $this->input->post('status');
		$html   = '';

		$dataStatus = $this->Call_center_model->getStatusList($status);
		if(!empty($dataStatus)){

			$dataStatus = $dataStatus[0];

			if($dataStatus->details_required != ""){
				$req_fields = json_decode($dataStatus->details_required,true);


				foreach ($req_fields as $rkey => $rvalue) {
					$html .= '<div class="col-md-2">
            			<label class="control-label '.$rvalue[2].'">'.$rvalue[1].'</label>
            			<input type="text" name="'.$dataStatus->status_txt.'['.$rvalue[0].']" class="form-control valid"  id="'.$rvalue[0].'"  type="text" data-rule-required="'.$rvalue[3].'">
            		</div>';
				}

			}
		}

		echo $html; exit;
	}

	public function setSortFilter(){
		$data = $this->input->post();

		 $status      = $data['status'];
		 $assigned_to = $data['assigned_to'];

		 $this->session->set_userdata("status",$status);
		 $this->session->set_userdata("assigned_to",$assigned_to);
		 return true;
	}

	public function generate_tickets($item_id)
	{
		$this->load->helper('persian_date');
		$item_response = $this->Call_center_model->getItemList($item_id);
		$item_info = null;
		if ($item_response['count'] > 0) {
			$item_info = $item_response['result'][0];
		}
		if(!$item_info){ return; }


		$traveller_det = json_decode($item_info->traveller_json);
		$departures    = json_decode($item_info->departures)[0];
		$arrivals    = json_decode($item_info->arrivals)[0];
		$input_json    = json_decode($item_info->input_json);
		$people_count = count($traveller_det);

		$data = array();

		$data['from'] = $this->get_actual_text($departures->departure_from);
		$data['to'] = $this->get_actual_text($departures->arrival_to);
		$data['airline_name'] = $departures->airline_name;
		$data['flight_date'] = date('y/m/d', strtotime($departures->departure_dttm));
		$data['flight_time'] = date('h:i', strtotime($departures->departure_dttm));
		$data['voucher'] = '-';

		$data['contact'] = $item_info->contact;
		$this->send_sms($data, true);


		if ($item_info->flight_type == 'Return') {
			$tickets_types = [$departures, $arrivals];
		}else{
			$tickets_types = [$departures];
		}
		$tickets = array();
		for ($person_key=0; $person_key < count($traveller_det); $person_key++) { 
		
			if (is_array($traveller_det)) {
				$traveller_det_copy = $traveller_det[$person_key];
				$adult_fname = @$traveller_det_copy->adult_fname;
				$adult_lname = @$traveller_det_copy->adult_lname;
				$adult_name_fa = @$traveller_det_copy->adult_name_fa;
			
			}elseif (is_array(@$traveller_det->adult_fname)) {
				$adult_fname = @$traveller_det->adult_fname[0];
				$adult_lname = @$traveller_det->adult_lname[0];
				$adult_name_fa = @$traveller_det->adult_name_fa[0];
			}else{
				$adult_fname = @$traveller_det->adult_fname;
				$adult_lname = @$traveller_det->adult_lname;
				$adult_name_fa = @$traveller_det->adult_name_fa;
			}

			foreach ($tickets_types as $key => $departure) {
				$tickets[$person_key][] = (object) array(
					'passenger_name' => $adult_fname . ' ' . $adult_lname ,
					'passenger_name_fa' => $adult_name_fa ,
					'type' => $key == 0 ? 1 : 2 , // 1 one way, 2 two ways
					'booking_ref' => (1000 + $item_info->id) ,
					'from' => $this->get_actual_text($departure->departure_from) ,
					'to' => $this->get_actual_text($departure->arrival_to) ,
					'fare' => '' ,
					'payment' => $item_info->payment_details ,
					'number' => $item_info->pnr_number , //$departure->flight_no ,
					'date' => date('Y/m/d', strtotime($departure->departure_dttm)) ,
					'date_fa' => to_date(date('Y/m/d', strtotime($departure->departure_dttm)), 'Y/m/d') ,
					'carrier' => $departure->airline_name,
					'flight' => $departure->airline_type ,
					'issued_date' => date('Y/m/d', strtotime($item_info->booked_date)) ,
					'issued_date_fa' => to_date(date('Y/m/d', strtotime($item_info->booked_date)), 'Y/m/d') ,
					'remark' => '' ,
					'class' => $departure->cabin_type ,
					'time' => date('h:i', strtotime($departure->departure_dttm)) ,
					'allow' => '20Kg' ,
					'status' => 'OK' ,
					'price' => '', //$item_info->total_cost ,
					// 'barcode' => $this->create_barcode($item_info->book_id),
				);	
			}
		}

		$this->load->library('Pdf');
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('10020.ir');
		$pdf->SetTitle('10020.ir');
		$pdf->SetSubject('10020.ir');
		$pdf->SetKeywords('10020.ir, PDF');

		$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'rtl';
		$lg['a_meta_language'] = 'fa';
		// set some language-dependent strings (optional)
		$pdf->setLanguageArray($lg);
		// 
		// set default header data
		// پشتیبانی 56 ساعته
		// 021 5462 3000
		$pdf->setHeaderData('header.png');
		// footer.png
		// $pdf->setFooterData();
	

		$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'ltr';
		$lg['a_meta_language'] = 'en';
		// set some language-dependent strings (optional)
		$pdf->setLanguageArray($lg);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set font
		$pdf->SetFont('dejavusans', '', 20);

		for ($person_key=0; $person_key < $people_count; $person_key++) { 
			// add a page
			$pdf->AddPage();


			$data = array();
			$data['tickets'] = $tickets[$person_key];
			
			$tbl = $this->load->view('call_center/ticket_template', $data, true);
			$pdf->writeHTML($tbl, true, false, false, false, '');
			
			

			$tbl = $this->load->view('call_center/help_texts', $data, true);
			// $pdf->WriteHTML('<center><h6 class="red_text">رعایت این نکات در کلیه پروازها الزامی است</h6></center>', true, 0, true, 0, 'C');
			$pdf->writeHTML($tbl, true, false, false, false, 'C');

			$pdf->Ln();
			$pdf->SetLineStyle(array('width' => 0.0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
			$pdf->SetFillColor(255,255,128);
			$pdf->SetTextColor(0,0,128);
			$pdf->Ln();
		
			// START FOOTER
			$footer_image_file = base_url() . 'assets/images/footer.png';
			$footer_logo_html = '<img src="' . $footer_image_file . '" />';
			$pdf->writeHTMLCell(690, 44, 8, 228, $footer_logo_html);
			// END FOOTER
		}
		//Close and output PDF document
		$id = 192831;
		$data = $pdf->Output(APPPATH . '/ticket_'.$item_info->booking_id.'.pdf', 'F');
		
		// // Send SMS
		// $phone = $item_info->contact;

		// // Send Email
		$this->load->model("Sendmail_model");
		$email = $item_info->email;
		$message = '<h3>سفر خوشي را براي شما آرزومنديم و از اینکه 10020  را برای سفر انتخاب نموده اید متشکریم.</h3>
<p>بلیط شما ضمیمه این ایمیل گردیده است. پیشنهاد می کنیم همیشه چند ساعت قبل از پرواز، آخرین وضعیت پرواز شامل تاخیرهای احتمالی و شرایط آب و هوا را با شماره تلفن 199 اطلاعات فرودگاه بررسی نمایید.</p>
<strong>رفرنس رزرو شما '.$item_info->booking_id.' می باشد.</strong>';
		$access = $this->Email_model->get_access();
		$subject = '10020.ir';
		$email_from = $access->username;
		unset($access);
		$email_from_name = '10020.ir';
		$sent = $this->Sendmail_model->send_mail($subject, $message, $email_from, $email_from_name, $email, null, [APPPATH . '/ticket_'.$item_info->booking_id.'.pdf']);
		if ($sent) {
			unlink(APPPATH . '/ticket_'.$item_info->booking_id.'.pdf');
		}

	}

	function send_sms($data=array(), $booked = false)
	{
		if ($booked) {
			$message = "فرخوشی برایتان آرزو میکنیم\n
". $data['from'] ." - ". $data['to'] ."\n
".  $data['flight_date']  ."\n
".  $data['flight_time']  ."\n
هواپیمایی: ".  $data['airline_name']  ."\n
شماره واچر:".  $data['voucher']  ."\n
پشتیبانی:02154623000";
		}else{
		$message = "پرواز مورد نظر با شماره پرواز(". $data['flight_number'] .") \n
از: ". $data['from'] ." \n
به: ". $data['to'] ." \n
در تاریخ: ". $data['purchased_date'] ." \n
ساعت: ". $data['purchased_time'] ." \n
برای: ". $data['people_count'] ." \n
با رفرنس: ". $data['booking_ref'] ." \n
با موفقیت خریداری شد.\n
در صورت داشتن سئوال با شماره 02154623000 تماس بگیرید.\n
10020.ir" ;
		}

		$this->send_sms_msg($message, $data['contact']);
	}

	function send_sms_msg($msg='', $to='')
	{
		// return;
		ini_set("soap.wsdl_cache_enabled", "0");
		$client = new SoapClient( 'http://payamak-service.ir/SendService.svc?wsdl', array('encoding'=>'UTF-8'));
		 //'http://payamak-service.ir/SendService.svc?singleWsdl' );
		$result = $client->SendSMS( array( 'userName'       => 'jfathi',
		                                   'password'       => '1002010020',
		                                   'fromNumber'     => '2186030931', // '2186030931',// '50005346264',
		                                   'toNumbers'      => [$to], // [/*'+972592105628'*/], '+989125047869',
		                                   'messageContent' => $msg,
		                                   'isflash' => false,
		                                ) );
		return isset($result->recId->long) ? 1 : 0;
	}
	function get_actual_english_text($code='THR')
	{
		$airports = $this->general->get_flight_airport($code, 'en');
		$first_found = $airports['results'] ? ($airports['results'][0] ? $airports['results'][0] : null) : null;
		return $first_found->text;
	}

	function get_english_code_text($name='تهران')
	{
		$airports = $this->general->get_flight_airport_by_name($name, 'en');
		$first_found = $airports['results'] ? ($airports['results'][0] ? $airports['results'][0] : null) : null;
		return $first_found->id;
	}

	function get_actual_text($code='THR', $lang="fa")
	{
		$airports = $this->general->get_airports($code,'', '1', $lang);
		$first_found = $airports['results'] ? ($airports['results'][0] ? $airports['results'][0] : null) : null;
		if ($first_found) {
			$texts_found = preg_split( "/(,|،)/", $first_found->city_text );
			
			if (count($texts_found) > 0) {
				$found_fa = false;
				foreach ($texts_found as $key => $value) {
					if (!preg_match('/[^A-Za-z0-9]/', $value)) {
						continue;
					}
					$found_fa = true;
					$city_name = $texts_found[$key];
				}
			}
			if (!$found_fa) {
				$city_name = $texts_found[0];
			}

			if (isset($city_name)) {
				if (!preg_match('/[^A-Za-z0-9]/', $city_name)) {
					return $this->get_actual_text($code, 'en');
				}
				return $city_name;
			}
		}
		return $code;
	}


	function test2()
	{
		$this->load->helper('persian_date');
		echo "<pre>";
		print_r(to_date('2017/07/19', 'Y/m/d'));
		// 1396/04/28
		echo "</pre>";
		die();
		die;
		$flight_number = '960';
		$from = $this->get_actual_text('THR');
		$to = $this->get_actual_text('MHD');
		$purchased_date = '17/07/12';
		$purchased_time = '03:30';
		$people_count = '2 نفر';
		$booking_ref = '1003';


		$message = "پرواز مورد نظر با شماره پرواز(". $flight_number .") \n
از: ". $from ." \n
به: ". $to ." \n
در تاریخ: ". $purchased_date ." \n
ساعت: ". $purchased_time ." \n
برای: ". $people_count ." \n
با رفرنس: ". $booking_ref ." \n
با موفقیت خریداری شد.\n
در صورت داشتن سئوال با شماره 02154623000 تماس بگیرید.\n
10020.ir" ;


// 		$message = "پرواز مورد نظر با شماره پرواز(". $flight_number .") </br>
// از: ". $from ." </br>
// به: ". $to ." </br>
// در تاریخ: ". $purchased_date ." </br>
// ساعت: ". $purchased_time ." </br>
// برای: ". $people_count ." </br>
// با رفرنس: ". $booking_ref ." </br>
// با موفقیت خریداری شد.</br>
// در صورت داشتن سئوال با شماره 02154623000 تماس بگیرید.</br>
// 10020.ir" ;

		// $this->load->model("Sendmail_model");
		// $email = 'abed.saidy@gmail.com';

		// $access = $this->Email_model->get_access();
		// $subject = '10020.ir';
		// $email_from = $access->username;
		// unset($access);
		// $email_from_name = '10020.ir';
		// $sent = $this->Sendmail_model->send_mail($subject, $message, $email_from, $email_from_name, $email, null);
		// if ($sent) {
		// 	echo "sent";
		// 	// unlink(APPPATH . '/ticket_'.$item_info->booking_id.'.pdf');
		// }else{
		// 	echo "not sent";
		// }
		// die();
		
		
		// $this->send_sms_msg($message, '+989212143313');
		// die('done');
		
		
		// ini_set("soap.wsdl_cache_enabled", "0");
		// try {
		// 	$client = new SoapClient( 'http://payamak-service.ir/SendService.svc?wsdl', array('encoding'=>'UTF-8'));
		// 	// $credit = $client->GetCredit( array( 'userName' => 'jfathi', 'password' => '1002010020' ))->GetCreditResult;

		// 	// $credit = $client->GetSenderNumbers( array( 'userName' => 'jfathi', 'password' => '1002010020' ));
		// 	$credit = $client->GetMessages( array( 'userName' => 'jfathi', 'password' => '1002010020', 'messageType'=> 2, 'fromNumbers'=> ['2186030931', '50005346264'], 'index' => 0, 'count' => 100 ));


		// } catch ( SoapFault $ex ) {
		// 	// $credit = $ex->faultstring;
		// }
		// echo "<pre>";
		// print_r($credit);
		// echo "</pre>";
		// die('done');
	}
}