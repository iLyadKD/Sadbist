<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/
class Charges extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Charges_model");
		$this->data["page_main_title"] = "Charges Management";
		$this->data["page_title"] = "Charges Management";
	}

	//markup type list	
	public function index()
	{
		$this->data["page_title"] = "Payment Gateways";
		$this->load->view("charges/payment_gateway/index");
	}

	//payment_gateway list view
	public function payment_gateway()
	{
		$this->data["page_title"] = "Payment Gateways";
		$this->load->view("charges/payment_gateway/index");
	}

	//payment_gateway list	
	public function pg_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title");

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
			$result_list = $this->Charges_model->get_all_pg($sWhere, $sOrder, $sLimit);
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
			$pay_mode = array("0" => "Fixed Amount", "1" => "% Amount", "2" => "Fixed and % Amount");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control payment_gateway_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."payment_gateway_edit".DEFAULT_EXT."?payment_gateway=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_payment_gateway' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "title" =>$aRow["title"], "pay_mode" => $pay_mode[$aRow["pay_mode"]], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	//add payment_gateway
	public function payment_gateway_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("pg_name", "payment Gateway Name", "trim|required");
			$this->form_validation->set_rules("pay_mode[]", "Payment Mode", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Paymet Gateway";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$pg_name = ucwords($this->input->post("pg_name"));
				$pay_mode = count($this->input->post("pay_mode")) > 1 ? "2" : $this->input->post("pay_mode")[0];
				$insert_data = array("title" => $pg_name,"pay_mode" => $pay_mode);
				if($this->Charges_model->is_payment_gateway_exists($insert_data) === false)
				{
					$result = $this->Charges_model->add_payment_gateway($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Payment Gateway added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Sorry, Failed to add Payment Gateway.";
				}
				else
					$response["msg"] = "Sorry, \"".$pg_name."\" already exists.";

			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_main_title"] = "Payment Gateways";
			$this->data["page_title"] = "Add Payment Gateway";
			$this->load->view("charges/payment_gateway/add_pg");
		}
	}

	//update payment_gateway status	
	public function payment_gateway_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("payment_gateway")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Payment Gateway Activate" : " - Payment Gateway Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Charges_model->update_payment_gateway($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Payment Gateway deactivated successfully." : "Payment Gateway activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update Payment Gateway status.";
			}
		}
		echo json_encode($response);exit;
	}

	//update payment_gateway
	public function payment_gateway_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("pg_name", "payment Gateway Name", "trim|required");
			$this->form_validation->set_rules("pg_name_old", "payment Gateway Name", "trim|required");
			$this->form_validation->set_rules("pay_mode[]", "Payment Mode", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Paymet Gateway";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("payment_gateway")));
				if(is_numeric($id) && $id > 0)
				{
					$pg_name = ucwords($this->input->post("pg_name"));
					$title_old = ucwords($this->input->post("pg_name_old"));
					$pay_mode = count($this->input->post("pay_mode")) > 1 ? "2" : $this->input->post("pay_mode")[0];
					$insert_data = array("title" => $pg_name,"pay_mode" => $pay_mode);
					if($this->Charges_model->is_payment_gateway_exists($insert_data, $title_old) === false)
					{
						$result = $this->Charges_model->update_payment_gateway($id, $insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Payment Gateway updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$response["payment_gateway"] = $pg_name;
						}
						else
						{
							$response["msg_status"] = "info";
							$response["msg"] = "Sorry, No changes are made.";
						}
					}
					else
						$response["msg"] = "Title alreay exists. Please use different title.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Edit Payment Gateway";
			$data["pg_id"] = $this->input->get("payment_gateway");
			$id = $this->encrypt->decode(base64_decode($this->input->get("payment_gateway")));
			$data["pg_data"] = $this->Charges_model->get_payment_gateway($id);
			if($data["pg_data"] === false)
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
			$this->load->view("charges/payment_gateway/edit_pg", $data);
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
	}


	/*delete payment gateway*/
	public function delete_payment_gateway()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("payment_gateway")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Payment Gateway";
				$result = $this->Charges_model->delete_payment_gateway($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Payment Gateway deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove Payment Gateway.";
			}
		}
		echo json_encode($response);exit;
	}

	//payment_gateway charges list	
	public function pg_charges()
	{
		$this->data["page_title"] = "Payment Gateway Charges";
		$this->load->view("charges/payment_gateway/charges");
	}

	//payment_gateway charges list	
	public function pg_charges_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title");

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
			$result_list = $this->Charges_model->get_all_pg_charges($sWhere, $sOrder, $sLimit);
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
			$pay_mode = array("0" => "Fixed Amount", "1" => "% Amount", "2" => "Fixed and % Amount");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control pg_charges_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."pg_charges_edit".DEFAULT_EXT."?pg_charge=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "title" => $aRow["title"], "api" => (is_null($aRow["api_name"]) ? "---" : $aRow["api_name"]), "pay_mode" => $pay_mode[$aRow["pay_mode"]], "amount" => $aRow["amount"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	//add payment charges	
	public function pg_charges_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("pg_name", "payment Gateway Name", "trim|required");
			$this->form_validation->set_rules("pay_mode", "Payment Mode", "trim|required");
			$this->form_validation->set_rules("pg_amount", "Payment Amount", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Paymet Charges";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$pg_name = is_numeric($this->input->post("pg_name")) ? $this->input->post("pg_name") : "";
				$pay_mode = is_numeric($this->input->post("pay_mode")) ? $this->input->post("pay_mode") : "";
				$api = $this->input->post("api") !== null && $this->input->post("api") !== "" && is_numeric($this->input->post("api")) ? $this->input->post("api") : null;
				$amount = (float)$this->input->post("pg_amount");
				$insert_data = array("pg_id" => $pg_name,"pay_mode" => $pay_mode, "api" => $api, "amount" => $amount);
				$is_pg_charge_exists = $this->Charges_model->is_pg_charge_exists($insert_data);
				if($is_pg_charge_exists === false)
				{
					$result = $this->Charges_model->add_pg_charge($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Payment Gateway Charges added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Sorry, Failed to add Payment Gateway Charges.";
				}
				else
					$response["msg"] = "Sorry, Selected Payment gateway and API combination already exists.";

			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_main_title"] = "Payment Gateways";
			$this->data["page_title"] = "Add Payment Charges";
			$this->load->view("charges/payment_gateway/add_pg_charges");
		}
	}

	//update payment charges status	
	public function pg_charges_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("pg_charge")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Payment Charges Activate" : " - Payment Charges Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Charges_model->update_pg_charge($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Payment Charges deactivated successfully." : "Payment Charges activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update Payment Charges status.";
			}
		}
		echo json_encode($response);exit;
	}

	//update payment charges	
	public function pg_charges_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("pg_charge", "Payment Gateway Charge", "trim|required");
			$this->form_validation->set_rules("pg_amount", "Payment Amount", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Edit Payment Charges";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$amount = (float)$this->input->post("pg_amount");
				$insert_data = array("amount" => $amount);
				$id = $this->encrypt->decode(base64_decode($this->input->post("pg_charge")));
				if(is_numeric($id) && $id > 0)
				{
					$result = $this->Charges_model->update_pg_charge($id, $insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Payment Gateway Charges updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
					{
						$response["msg"] = "No changes are made.";
						$response["msg_status"] = "info";
					}
				}

			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_main_title"] = "Payment Charges";
			$this->data["page_title"] = "Edit Payment Charges";
			$data["pg_charge_id"] = $this->input->get("pg_charge");
			$id = $this->encrypt->decode(base64_decode($this->input->get("pg_charge")));
			$data["pg_charge_data"] = $this->Charges_model->get_pg_charge($id);
			if($data["pg_charge_data"] === false)
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
			$this->load->view("charges/payment_gateway/edit_pg_charges", $data);
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
	}

	/*delete payment charges*/
	public function delete_pg_charge()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("pg_charge")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Payment Charge";
				$result = $this->Charges_model->delete_pg_charge($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Payment Charge deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove Payment Charge.";
			}
		}
		echo json_encode($response);exit;
	}

	//tax list view
	public function taxes()
	{
		$this->data["page_title"] = "Taxes";
		$this->load->view("charges/tax/index");
	}

	//tax list	
	public function taxes_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title");

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
			$result_list = $this->Charges_model->get_all_tax($sWhere, $sOrder, $sLimit);
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
			$pay_mode = array("0" => "Fixed Amount", "1" => "% Amount", "2" => "Fixed and % Amount");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control tax_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."taxes_edit".DEFAULT_EXT."?tax=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_tax' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "title" => $aRow["title"], "pay_mode" => $pay_mode[$aRow["pay_mode"]], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	//add tax
	public function taxes_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tax_name", "Tax Name", "trim|required");
			$this->form_validation->set_rules("pay_mode[]", "Payment Mode", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Tax";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$tax_name = ucwords($this->input->post("tax_name"));
				$pay_mode = count($this->input->post("pay_mode")) > 1 ? "2" : $this->input->post("pay_mode")[0];
				$insert_data = array("title" => $tax_name,"pay_mode" => $pay_mode);
				if($this->Charges_model->is_tax_exists($insert_data) === false)
				{
					$result = $this->Charges_model->add_tax($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Tax added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Sorry, Failed to add Tax.";
				}
				else
					$response["msg"] = "Sorry, \"".$tax_name."\" already exists.";

			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_main_title"] = "Taxes";
			$this->data["page_title"] = "Add Tax";
			$this->load->view("charges/tax/add_tax");
		}
	}

	//update tax status	
	public function taxes_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("tax")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Tax Activate" : " - Tax Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Charges_model->update_tax($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Tax deactivated successfully." : "Tax activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update Tax status.";
			}
		}
		echo json_encode($response);exit;
	}

	//update tax
	public function taxes_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tax", "Tax", "trim|required");
			$this->form_validation->set_rules("tax_name", "Tax Name", "trim|required");
			$this->form_validation->set_rules("tax_name_old", "Tax Name", "trim|required");
			$this->form_validation->set_rules("pay_mode[]", "Payment Mode", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Tax";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("tax")));
				if(is_numeric($id) && $id > 0)
				{
					$tax_name = ucwords($this->input->post("tax_name"));
					$tax_name_old = ucwords($this->input->post("tax_name_old"));
					$pay_mode = count($this->input->post("pay_mode")) > 1 ? "2" : $this->input->post("pay_mode")[0];
					$insert_data = array("title" => $tax_name,"pay_mode" => $pay_mode);
					if($this->Charges_model->is_tax_exists($insert_data, $tax_name_old) === false)
					{
						$result = $this->Charges_model->update_tax($id, $insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Tax updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$response["tax_name"] = $tax_name;
						}
						else
						{
							$response["msg_status"] = "info";
							$response["msg"] = "Sorry, No changes are made.";
						}
					}
					else
						$response["msg"] = "Trying to assign existing another tax name.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Edit Tax";
			$data["tax_id"] = $this->input->get("tax");
			$id = $this->encrypt->decode(base64_decode($this->input->get("tax")));
			$data["tax_data"] = $this->Charges_model->get_tax($id);
			if($data["tax_data"] === false)
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
			$this->load->view("charges/tax/edit_tax", $data);
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
	}


	/*delete payment gateway*/
	public function delete_taxes()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Tax";
			$id = $this->encrypt->decode(base64_decode($this->input->post("tax")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Charges_model->delete_tax($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Tax deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove Tax.";
			}
		}
		echo json_encode($response);exit;
	}

	//tax charges list	
	public function tax_charges()
	{
		$this->data["page_title"] = "Tax Charges";
		$this->load->view("charges/tax/charges");
	}

	//tax charges list	
	public function tax_charges_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title");

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
			$result_list = $this->Charges_model->get_all_tax_charges($sWhere, $sOrder, $sLimit);
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
			$pay_mode = array("0" => "Fixed Amount", "1" => "% Amount", "2" => "Fixed and % Amount");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control tax_charges_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."tax_charges_edit".DEFAULT_EXT."?tax_charge=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_tax_charge' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "title" => $aRow["title"], "api" => (is_null($aRow["api_name"]) ? "---" : $aRow["api_name"]), "pay_mode" => $pay_mode[$aRow["pay_mode"]], "amount" => $aRow["amount"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	//add payment charges	
	public function tax_charges_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tax_name", "Tax Name", "trim|required");
			$this->form_validation->set_rules("pay_mode", "Payment Mode", "trim|required");
			$this->form_validation->set_rules("tax_amount", "Payment Amount", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Paymet Charges";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.".validation_errors();
				$response["msg_status"] = "info";
			}
			else
			{
				$tax_name = is_numeric($this->input->post("tax_name")) ? $this->input->post("tax_name") : "";
				$pay_mode = is_numeric($this->input->post("pay_mode")) ? $this->input->post("pay_mode") : "";
				$api = $this->input->post("api") !== null && $this->input->post("api") !== "" && is_numeric($this->input->post("api")) ? $this->input->post("api") : null;
				$amount = (float)$this->input->post("tax_amount");
				$insert_data = array("tax_id" => $tax_name,"pay_mode" => $pay_mode, "api" => $api, "amount" => $amount);
				$is_tax_charge_exists = $this->Charges_model->is_tax_charge_exists($insert_data);
				if($is_tax_charge_exists === false)
				{
					$result = $this->Charges_model->add_tax_charge($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Tax Charges added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Sorry, Failed to add Tax Charges.";
				}
				else
					$response["msg"] = "Sorry, Selected Tax and API combination already exists.";

			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_main_title"] = "Taxes";
			$this->data["page_title"] = "Add Tax Charges";
			$this->load->view("charges/tax/add_tax_charges");
		}
	}

	//update payment charges status	
	public function tax_charges_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("tax_charge")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Tax Charges Activate" : " - Tax Charges Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Charges_model->update_tax_charge($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Tax Charges deactivated successfully." : "Tax Charges activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update Tax Charges status.";
			}
		}
		echo json_encode($response);exit;
	}

	//update payment charges	
	public function tax_charges_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tax_charge", "Tax Charge", "trim|required");
			$this->form_validation->set_rules("tax_amount", "Payment Amount", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Edit Tax Charges";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$amount = (float)$this->input->post("tax_amount");
				$insert_data = array("amount" => $amount);
				$id = $this->encrypt->decode(base64_decode($this->input->post("tax_charge")));
				if(is_numeric($id) && $id > 0)
				{
					$result = $this->Charges_model->update_tax_charge($id, $insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Tax Charges updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
					{
						$response["msg"] = "No changes are made.";
						$response["msg_status"] = "info";
					}
				}

			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_main_title"] = "Tax Charges";
			$this->data["page_title"] = "Edit Tax Charges";
			$data["tax_charge_id"] = $this->input->get("tax_charge");
			$id = $this->encrypt->decode(base64_decode($this->input->get("tax_charge")));
			$data["tax_charge_data"] = $this->Charges_model->get_tax_charge($id);
			if($data["tax_charge_data"] === false)
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
			$this->load->view("charges/tax/edit_tax_charges", $data);
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
	}

	/*delete payment charges*/
	public function delete_tax_charge()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("tax_charge")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Tax Charge";
				$result = $this->Charges_model->delete_tax_charge($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Tax Charge deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove Tax Charge.";
			}
		}
		echo json_encode($response);exit;
	}
}