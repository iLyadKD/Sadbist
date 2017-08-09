<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Email extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Email_model');
		$this->data["page_title"] = "Email Template Management";
		$this->data["page_main_title"] = "Email Template Management";
	}

	public function index()
	{
		$this->load->view('email/index');
	}

	public function email_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "email_type", "subject", "email_from_name", "email_from");

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
			$result_list = $this->Email_model->get_template_list($sWhere, $sOrder, $sLimit);
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
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_right action_icons view_email_template' data-placement='top' title='View Template' href='javascript:void(0);'><i class='icon-eye-open'></i></a>\n";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_right action_icons' data-placement='top'  title='Edit' href='".base_url($this->data["controller"]."/edit".DEFAULT_EXT."?email=".$id)."'><i class='icon-edit'></i></a>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "name" =>$aRow["email_name"], "subject" => $aRow["subject"], "from" => $aRow["email_from_name"]." &lt;".$aRow["email_from"]."&gt;", "template" => $aRow["message"], "actions" => $actions, "id" => $id));
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

	public function add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("email_type", "Email Type", "trim|required");
			$this->form_validation->set_rules("subject", "Subject", "trim|required");
			$this->form_validation->set_rules("email_from", "Email From", "trim|required");
			$this->form_validation->set_rules("email_from_name", "Emailer Name", "trim|required");
			$this->form_validation->set_rules("to_email", "BCC Email", "trim|required");
			$this->form_validation->set_rules("message", "Message", "required]");

			$response["title"] = $this->data["page_main_title"]." - Add Template";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check entered details.";
				$response["msg_status"] = "info";
			}
			else
			{
				$data = array( "email_name" => $this->input->post("email_type"),
								"subject" => ucfirst($this->input->post("subject")),
								"message" => $this->input->post("email_body"),
								"email_from" => strtolower($this->input->post("email_from")),
								"email_from_name" => ucwords($this->input->post("email_from_name")),
								"to_email" => $this->input->post("to_email"),
								"default" => "0"
						);
				$result = $this->Email_model->add_template($data);
				if($result !== false)
				{
					$response["msg"] = "Email template added successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
				}
				else
					$response["msg"] = "Sorry, Failed to add new email template.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Email Template";
			$this->load->view("email/add");
		}
	}

	public function edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("template", "Template ID", "trim|required");
			$this->form_validation->set_rules("email_type", "Email Type", "trim|required");
			$this->form_validation->set_rules("subject", "Subject", "trim|required");
			$this->form_validation->set_rules("email_from", "Email From", "trim|required");
			$this->form_validation->set_rules("email_from_name", "Emailer Name", "trim|required");
			$this->form_validation->set_rules("to_email", "BCC Email", "trim|required");
			$this->form_validation->set_rules("message", "Message", "required]");

			$response["title"] = $this->data["page_main_title"]." - Edit Template";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check entered details.";
				$response["msg_status"] = "info";
			}
			else
			{
				$data = array( "email_name" => $this->input->post("email_type"),
								"subject" => ucfirst($this->input->post("subject")),
								"message" => $this->input->post("email_body"),
								"email_from" => strtolower($this->input->post("email_from")),
								"email_from_name" => ucwords($this->input->post("email_from_name")),
								"to_email" => $this->input->post("to_email")
						);
				$id = $this->encrypt->decode(base64_decode($this->input->post("template")));
				if(is_numeric($id) && $id > 0)
				{
					$result = $this->Email_model->update_template($id, $data);
					if($result !== false)
					{
						$response["msg"] = "Email template updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
					{
						$response["msg"] = "No changes are made.";
						$response["msg_status"] = "info";
					}
				}
				else
					$response["msg"] = "Please check the details you have entered.";
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("email")));
			if(is_numeric($id) && $id !== 0)
			{
				$this->data["page_title"] = "Update Email Template";
				$data["email"] = $this->Email_model->get_template($id);
				$this->load->view('email/edit',$data);
			}
			else
				redirect($this->data["controller"], "refresh");
		}
	}

	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Template";
			$id = $this->encrypt->decode(base64_decode($this->input->post("template")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Email_model->delete_template($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Email template deleted Successfully.";
				}
				else
					$response["msg"] = "Failed to delete email template.";
			}
		}
		echo json_encode($response);exit;
	}

	public function edit_access($id)
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("access", "Access ID", "trim|required");
			$this->form_validation->set_rules("smtp", "SMTP server", "trim|required");
			$this->form_validation->set_rules("host", "Host", "trim|required");
			$this->form_validation->set_rules("port", "Port Number", "trim|required");
			$this->form_validation->set_rules("username", "Username", "trim|required");
			$this->form_validation->set_rules("password", "Password", "trim|required");

			$response["title"] = $this->data["page_main_title"]." - Edit Access";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check entered details.";
				$response["msg_status"] = "info";
			}
			else
			{
				$data["id"] = is_numeric($this->input->post("access")) ? $this->input->post("access") :1;
				$data["smtp"] = $this->input->post("smtp");
				$data["host"] = $this->input->post("host");
				$data["port"] = $this->input->post("port");
				$data["username"] = $this->input->post("username");
				$data["password"] = $this->input->post("password");
				$result = $this->Admin_Model->set_access($data);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Email access details updated Successfully.";
				}
				else
					$response["msg"] = "Failed to update email access details.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$data["access"] = $this->Email_model->get_access();
			$this->load->view("email/access");
		}
	}
}