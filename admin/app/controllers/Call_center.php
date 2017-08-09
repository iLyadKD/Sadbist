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
			$updateData['created_date'] = $datetime;
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
	* Get Item Details
	*/
	public function item_details($id){

		$id      = base64_decode($id);
		$details = $this->Call_center_model->getItemList($id);

		$data['item_det']      = $details['result'][0];
		$data['traveller_det'] = json_decode($data['item_det']->traveller_json,true);
		$data['departures']    = json_decode($data['item_det']->departures)[0];
		$data['country']       = $this->Call_center_model->get_country();
		$data['staff']         = array();
		if($data['item_det']->assigned_to != ""){
			$data['staff']    = $this->Call_center_model->getStaff($data['item_det']->assigned_to);
		}

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
		$post = $this->input->post();
		// echo '<pre>',print_r($post);exit; 
		//response msg
		$response = array("status" => "Failed", "msg_status" => "danger",  "msg" => "Not updated");


		if(!empty($post) && $post['item_id'] != ""){

		$itemKey = json_decode(base64_decode($post['item_id']));

		$item_id = $itemKey[0];
		$book_id = $itemKey[1];

		$paxData['traveller_json'] = json_encode($post['traveller_det']);

		$itemData['item_id']   = $item_id;
		$itemData['cc_status'] = $post['cc_status'];
		$itemData['actual_price']   = $post['actual_price'];
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

}