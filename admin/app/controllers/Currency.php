<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Currency extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Currency_model");
		$this->data["page_main_title"] = "Currency Management";
		$this->data["page_title"] = "Currency Management";
	}

	public function index()
	{
		$this->load->view("currency/index");
	}

	//all Currency list
	public function currency_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "country_en", "code", "currency", "value");

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
			$result_list = $this->Currency_model->get_all_currencies($sWhere, $sOrder, $sLimit);
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
				$id = base64_encode($this->encrypt->encode($aRow["country"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control currency_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
			$actions .= "<a class='btn btn-danger btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/edit".DEFAULT_EXT."?currency=".$id)."'><i class='icon-edit'></i></a>\n";
			$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "country" =>$aRow["country_en"], "code" => $aRow["code"], "cur_name" => $aRow["currency"], "value" => $aRow["value"], "last_updated" => $aRow["last_updated"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	//add currency view
	public function add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Add Currency";
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("currency_code", "Currency Code", "trim|required");
			$this->form_validation->set_rules("currency_name", "Currency Name", "trim|required");
			$this->form_validation->set_rules("currency_value", "Currency Value", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$data["country"] = strtoupper($this->input->post("country"));
				$data["code"] = strtoupper($this->input->post("currency_code"));
				$data["currency"] = ucfirst($this->input->post("currency_name"));
				$data["value"] = floatval($this->input->post("currency_value"));
				if($this->Currency_model->is_exists($data["country"]) === false)
				{
					$result = $this->Currency_model->add($data);
					if($result !== false)
					{
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Currency Added Successfully.";
						$response["country"] = $data["country"];
					}
					else
						$response["msg"] = "Sorry, Failed to add currency.";
				}
				else
					$response["msg"] = "Country currency already exists.";
			}
			else
				$response["msg"] = "Enter proper details.";
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Currency";
			$this->load->view("currency/add");
		}
	}

	public function edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("currency", "Currency", "trim|required");
			$this->form_validation->set_rules("currency_code", "Currency Code", "trim|required");
			$this->form_validation->set_rules("currency_name", "Currency Name", "trim|required");
			$this->form_validation->set_rules("currency_value", "Currency Value", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Currency";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = strtoupper($this->encrypt->decode(base64_decode($this->input->post("currency"))));
				if(strlen($id) === 2)
				{
					$currency_code = strtoupper($this->input->post("currency_code"));
					$currency_name = ucfirst($this->input->post("currency_name"));
					$currency_value = floatval($this->input->post("currency_value"));
					$data = array("code" => $currency_code, "currency" => $currency_name, "value" => $currency_value);
					$result = $this->Currency_model->update($id, $data);
					if($result !== false)
					{
						$response["msg"] = "Currency updated successfully.";
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
			$this->data["page_title"] = "Update Currency";
			$id = $this->encrypt->decode(base64_decode($this->input->get("currency")));
			if(strlen($id) === 2)
			{
				$data["currency"] = $this->Currency_model->is_exists($id);
				if($data["currency"] !== false)
				{
					$data["currency_id"] = $this->input->get("currency");
					$this->load->view("currency/edit", $data);
				}
				else
					redirect($this->data["controller"], "refresh");
			}
			else
				redirect($this->data["controller"], "refresh");
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	/*change currency status*/
	public function currency_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("currency")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Currency Activate" : " - Currency Deactivate");
			if(strlen($id) === 2)
			{
				$result = $this->Currency_model->update($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Currency deactivated." : "Currency activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Invalid operation.";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{	
			$id = $this->encrypt->decode(base64_decode($this->input->post("currency")));
			if(strlen($id) === 2)
			{
				$response['title'] = "Currency Management - Delete Currency";
				$result = $this->Currency_model->delete($id);
				if($result)
				{
					$response['status'] = "true";
					$response['msg_status'] = "success";
					$response['msg'] = "Country currency deleted successfully.";
				}
				else
					$response['msg'] = "Sorry, Country currency failed to remove. Try agin.";
			}
			else
				$response['msg'] = "Sorry, Operation failed.";
		}
		echo json_encode($response);exit;
	}
}