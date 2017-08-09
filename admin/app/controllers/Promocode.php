<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Promocode extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Promocode_model");
		$this->load->model("Sendmail_model");
		$this->data["page_main_title"] = "Promocode Management";
		$this->data["page_title"] = "Promocode Management";
	}
	
	//promo view list	
	public function index()
	{ 
		$this->load->view("promocode/index");
	}

	//display all promocode
	public function promo_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "code", "", "condition", "discount", "valid_from");

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
							$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET["sSearch"]."%' OR ";
				}
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ")";
			}
			$result_list = $this->Promocode_model->get_all_promocodes($sWhere, $sOrder, $sLimit);
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
			$promo_types = array("1" => "Discount by %", "2" => "Discount by $");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$is_valid = (strtotime(date("Y-m-d")) < strtotime($aRow["valid_to"]));
				$row = array();
				$valid_to_html = $is_valid ? (date("dS M Y",strtotime($aRow["valid_to"]))." <br>(".($this->general->countdown($aRow["valid_to"])).")") : "<span class='text-danger'><i class='icon-warning'></i>&nbsp; Expired</span>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = $is_valid ? "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control promocode_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>" : "<span class='text-primary'><i class='icon-remove-circle'></i>&nbsp; Not Applicable</span>";
				$actions = "<div class='pull-left'>";
				if($aRow["status"] === "1" && $is_valid)
					$actions .= "<a class='btn btn-warning btn-xs has-tooltip action_icons mrgn_right send_promocode_mail' data-placement='top' title='Send Promo' href='".base_url($this->data["controller"]."/send".DEFAULT_EXT."?promocode=".$id)."'><i class='icon-envelope'></i></a>";
				#$actions .= "<a class='btn btn-danger btn-xs has-tooltip edit_promocode' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/edit".DEFAULT_EXT."?promocode=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_promocode' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "code" =>$aRow["code"], "promo_type" => $promo_types[$aRow["type"]], "range" => ($aRow["condition"]." $"), "discount_html" => ($aRow["discount"].($aRow["type"] === "1" ? " %" : " $")), "valid_from" => date("dS M Y",strtotime($aRow["valid_from"])), "valid_to_html" => $valid_to_html, "status_html" => $status_html, "actions" => $actions, "id" => $id));
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
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

	//add new promo view
	public function add_promo()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Add Promocode";
			$this->form_validation->set_rules("promo_type", "Promo Type", "trim|required");
			$this->form_validation->set_rules("promo_code", "Promo Code", "trim|required");
			$this->form_validation->set_rules("discount", "Disocunt", "trim|required");
			$this->form_validation->set_rules("expiry_date", "Expiry Date", "trim|required");
			$this->form_validation->set_rules("promo_amount", "Condition Amount", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$data["type"] = $this->input->post("promo_type");
				$data["code"] = $this->input->post("promo_code");
				$data["discount"] = (float)$this->input->post("discount");
				$data["condition"] = (float)$this->input->post("promo_amount");
				$data["valid_to"] = date("Y-m-d", strtotime($this->input->post("expiry_date")));
				if($this->Promocode_model->is_promo_exists($data["code"]) === false)
				{
					$result = $this->Promocode_model->add_promo($data);
					if($result !== false && $result > 0)
					{
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Promocode Added Successfully.";
						$response["new_code"] = $this->general->generate_random_key(10);
					}
					else
						$response["msg"] = "Sorry, Failed to add promocode.";
				}
				else
				{
					$response["status"] = "exist";
					$response["new_code"] = $this->general->generate_random_key(10);
					$response["msg"] = "Promocode already exists.";
				}
			}
			else
				$response["msg"] = "Enter proper details.";
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Promocode";
			$data["promo_code"] = $this->general->generate_random_key(10);
			$this->load->view("promocode/add",$data);
		}
	}

	/*change promocode status*/
	public function promocode_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("promocode")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Activate" : " - Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Promocode_model->update_promocode($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Promocode deactivated successfully." : "Promocode activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update Promocode status.";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{	
			$id = $this->encrypt->decode(base64_decode($this->input->post("promocode")));
			if(is_numeric($id) && $id > 0)
			{
				$response['title'] = $this->data["page_main_title"]." - Delete Promocode";
				$result = $this->Promocode_model->delete_promocode($id);
				if($result)
				{
					$response['status'] = "true";
					$response['msg_status'] = "success";
					$response['msg'] = "Promocode deleted successfully.";
				}
				else
					$response['msg'] = "Sorry, Promocode failed to remove. Try agin.";
			}
			else
				$response['msg'] = "Sorry, Operation failed.";
		}
		echo json_encode($response);exit;
	}

	public function send()
	{

		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Send Promocode";
			$id = $this->encrypt->decode(base64_decode($this->input->post("promocode")));
			if(is_numeric($id) && $id > 0)
			{
				$additional_emails = array_filter(explode(",", $this->input->post("additional_emails")));
				$selected = array();
				if($this->input->post("b2b") !== null)
					array_push($selected, $this->input->post("b2b"));
				if($this->input->post("b2c") !== null)
					array_push($selected, $this->input->post("b2c"));
				if($this->input->post("subscribers") !== null)
					array_push($selected, $this->input->post("subscribers"));
				if(count($additional_emails) !== 0 || count($selected) !== 0)
				{
					$users_list = $this->Promocode_model->get_promocode_emails($selected);
					$spc["promocode"] = $this->Promocode_model->get_promocode($id);
					$users_list = $users_list !== false ? explode(",", $users_list) : array();
					$users_list = array_merge($users_list, $additional_emails);
					if(count($users_list) !== 0 && $spc["promocode"] !== false)
					{
						$spc["to"] = $users_list;
						$spc["salutation"] = "Subscriber";
						$mail_sent = $this->Sendmail_model->promocode($spc);
						if($mail_sent !== false)
						{	
							$response["msg"] = "Promocode sent Successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$response["msg"] = "Failed to send promocode.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "User category you have selected does not contain any valid emails to send.";
				}
				else
					$response["msg"] = "Please enter Email Ids or select one or more user category.";
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Send Promocode";
			$id = $this->encrypt->decode(base64_decode($this->input->get("promocode")));
			if(is_numeric($id) && $id > 0)
			{
				$data["promocode_id"] = $this->input->get("promocode");
				$this->load->view("promocode/send", $data);
			}
			else
				redirect($this->data["controller"], "refresh");
		}
		else
			redirect($this->data["controller"], "refresh");
	}
}