<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/
class Markup extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Markup_model");
		$this->data["page_main_title"] = "Markup Management";
		$this->data["page_title"] = "Markup Management";
	}

	//markup type list	
	public function index()
	{
		$this->load->view("markup/index");
	}

	//markup types view	
	public function types()
	{
		$this->data["page_title"] = "Markup Types";
		$this->load->view("markup/index");
	}


	//display all markup types
	public function types_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "markup_name", "markup_priority", "name");

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
			$result_list = $this->Markup_model->get_markup_types($sWhere, $sOrder, $sLimit);
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
				if($aRow["markup_priority"] === "0")
					$actions .= "<a class='btn btn-info btn-xs has-tooltip default_markup_type' data-placement='top' title='Currently Default' href='javascript:void(0);'><i class='icon-exchange'></i></a>\n";
				else
					$actions .= "<a class='btn btn-danger btn-xs has-tooltip set_default_markup_type' data-placement='top' title='Set Default' href='javascript:void(0);'><i class='icon-exchange'></i></a>\n";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/types_edit".DEFAULT_EXT."?type=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_markup_type' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "name" => $aRow["markup_name"], "priority" => $aRow["markup_priority"], "category" => $aRow["name"], "user_type" => $aRow["user_type"], "actions" => $actions, "id" => $id));
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

	public function types_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("markup_name", "Markup Name", "trim|required");
			$this->form_validation->set_rules("markup_priority", "Priority", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Markup Type";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$markup_name = ucfirst($this->input->post("markup_name"));
				$markup_priority = is_numeric($this->input->post("markup_priority")) ? $this->input->post("markup_priority") : "1";
				$user_type = $this->input->post("user_type") !== null && $this->input->post("user_type") !== "" && $this->input->post("user_type") !== "null" && is_numeric($this->input->post("user_type")) ? $this->input->post("user_type") : NULL;
				$insert_data = array("markup_name" => $markup_name, "markup_priority" => $markup_priority, "user_type" => $user_type);
				
				$this->db->trans_begin();
				try
				{
					$result = $this->Markup_model->add_type($insert_data);
					if($result !== false && $result > 0)
					{
						$this->db->trans_commit();
						$response["msg"] = "Markup Type added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg"] = "Failed to add Markup Type.";
					}
				}
				catch(Exception $ex)
				{
					$this->db->trans_rollback();
					$response["msg"] = "Sorry, Operation failed.";
				}
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Markup Type";
			$this->load->view("markup/add_type");
		}
	}

	public function types_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("mu_type", "Markup ID", "trim|required");
			$this->form_validation->set_rules("markup_name", "Markup Name", "trim|required");
			$this->form_validation->set_rules("markup_priority", "Priority", "trim|required");
			$this->form_validation->set_rules("mu_priority_old", "Priority", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Markup Type";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("mu_type")));
				if(is_numeric($id) && $id > 0)
				{
					$markup_name = ucfirst($this->input->post("markup_name"));
					$markup_priority = is_numeric($this->input->post("markup_priority")) ? $this->input->post("markup_priority") : "1";
					$user_type = $this->input->post("user_type") !== null && $this->input->post("user_type") !== "" && $this->input->post("user_type") !== "null" && is_numeric($this->input->post("user_type")) ? $this->input->post("user_type") : NULL;
					$type = $this->input->post("mu_user_old") !== null && $this->input->post("mu_user_old") !== "" && is_numeric($this->input->post("mu_user_old")) ? $this->input->post("mu_user_old") : NULL;
					$priority = is_numeric($this->input->post("mu_priority_old")) ? $this->input->post("mu_priority_old") : "1";
					$update_data = array("markup_name" => $markup_name, "markup_priority" => $markup_priority, "user_type" => $user_type);
					$conditions = array("id" => $id, "priority" => $priority, "type" => $type);
					$this->db->trans_begin();
					try
					{
						$result = $this->Markup_model->update_type($conditions, $update_data);
						if($result !== false)
						{
							$this->db->trans_commit();
							$response["msg"] = "Markup Type updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg"] = "Failed to update Markup Type.";
						}
					}
					catch(Exception $ex)
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("type")));
			if(is_numeric($id) && $id > 0)
			{
				$this->data["page_title"] = "Edit Markup Type";
				$data["mu_type_id"] = $this->input->get("type");
				$data["mu_type"] = $this->Markup_model->get_markup_type($id);
				if($data["mu_type"] !== false)
					$this->load->view("markup/edit_type", $data);
				else
					redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
			}
			else
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)), "refresh");
	}

	public function set_default_type()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("mu_type", "Markup ID", "trim|required");
			$this->form_validation->set_rules("markup_priority", "Priority", "trim|required");
			$this->form_validation->set_rules("mu_priority_old", "Priority", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Set Default Markup Type";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("mu_type")));
				if($id > 0)
				{
					$markup_priority = is_numeric($this->input->post("markup_priority")) ? $this->input->post("markup_priority") : "1";
					$user_type = $this->input->post("user_type") !== null && $this->input->post("user_type") !== "" && $this->input->post("user_type") !== "null" && is_numeric($this->input->post("user_type")) ? $this->input->post("user_type") : NULL;
					$type = $this->input->post("mu_user_old") !== null && $this->input->post("mu_user_old") !== "" && is_numeric($this->input->post("mu_user_old")) ? $this->input->post("mu_user_old") : NULL;
					$priority = is_numeric($this->input->post("mu_priority_old")) ? $this->input->post("mu_priority_old") : "1";
					$update_data = array("markup_priority" => $markup_priority, "user_type" => $user_type);
					$conditions = array("id" => $id, "priority" => $priority, "type" => $type);
					$this->db->trans_begin();
					try
					{
						$result = $this->Markup_model->update_type($conditions, $update_data);
						if($result !== false)
						{
							$this->db->trans_commit();
							$response["msg"] = "Markup Type updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg"] = "Failed to update Markup Type.";
						}
					}
					catch(Exception $ex)
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
					}
				}
			}
		}
		echo json_encode($response);exit;
	}

	public function delete_type()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("mu_type", "Markup ID", "trim|required");
			$this->form_validation->set_rules("markup_priority", "Priority", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Delete Markup Type";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("mu_type")));
				if(is_numeric($id) && $id > 0)
				{
					$markup_priority = is_numeric($this->input->post("markup_priority")) ? $this->input->post("markup_priority") : "1";
					$user_type = $this->input->post("user_type") !== null && $this->input->post("user_type") !== "" && $this->input->post("user_type") !== "null" && is_numeric($this->input->post("user_type")) ? $this->input->post("user_type") : NULL;
					$conditions = array("id" => $id, "priority" => $markup_priority, "type" => $user_type);
					$this->db->trans_begin();
					try
					{
						$result = $this->Markup_model->delete_type($conditions);
						if($result !== false)
						{
							$this->db->trans_commit();
							$response["msg"] = "Markup Type deleted successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg"] = "Failed to delete Markup Type.";
						}
					}
					catch(Exception $ex)
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
					}
				}
			}
		}
		echo json_encode($response);exit;
	}

	//add markup
	public function markup_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Add Markup";
			$this->form_validation->set_rules("mu_type", "Markup Type", "trim|required");
			$user_type = $this->input->post("user_type") !== false ? $this->input->post("user_type") : "";
			if($user_type === B2B_USER)
				$this->form_validation->set_rules("mu_agent", "Agent", "trim|required");
			$this->form_validation->set_rules("mu_pattern", "Markup Pattern", "trim|required");
			$this->form_validation->set_rules("mu_amount", "Amount", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$validate["mt_id"] = $data["mt_id"] = is_numeric($this->input->post("mu_type")) && $this->input->post("mu_type") > 0 ? $this->input->post("mu_type") : 0;
				if($user_type === B2B_USER)
					$validate["b2b"] = $data["b2b"] = is_numeric($this->input->post("mu_agent")) && $this->input->post("mu_agent") > 0 ? $this->input->post("mu_agent") : null;
				$validate["api"] = $data["api"] = is_numeric($this->input->post("api")) && $this->input->post("api") > 0 ? $data["api"] : null;
				$data["country"] = $this->input->post("country");
				$validate["country"] = $data["country"] = $data["country"] !== false && $data["country"] !== "" ? $data["country"] : null;
				$data["airline"] = $this->input->post("airline");
				$validate["airline"] = $data["airline"] = $data["airline"] !== false && $data["airline"] !== "" ? $data["airline"] : null;
				$data["o_airport"] = $this->input->post("o_airport");
				$validate["o_airport"] = $data["o_airport"] = $data["o_airport"] !== false && $data["o_airport"] !== "" ? $data["o_airport"] : null;
				$data["d_airport"] = $this->input->post("d_airport");
				$validate["d_airport"] = $data["d_airport"] = $data["d_airport"] !== false && $data["d_airport"] !== "" ? $data["d_airport"] : null;
				$data["mt_type"] = is_numeric($this->input->post("mu_pattern")) ? $this->input->post("mu_pattern") : DEFAULT_DISCOUNT;
				$data["mt_amount"] = (float)$this->input->post("mu_amount");
				if($this->Markup_model->is_markup_exists($validate) === false)
				{
					$result = $this->Markup_model->add_markup($data);
					if($result > 0)
					{
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Markup Added Successfully.";
					}
					else
						$response["msg"] = "Sorry, Failed to add markup.";
				}
				else
					$response["msg"] = "Given markup details already exists";
			}
			else
				$response["msg"] = "Enter proper details.";
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Markup";
			$this->load->view("markup/add_markup");
		}
	}

	//update markup
	public function markup_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Edit Markup";
			$this->form_validation->set_rules("markup", "Markup", "trim|required");
			$this->form_validation->set_rules("mu_pattern", "Markup Pattern", "trim|required");
			$this->form_validation->set_rules("mu_amount", "Amount", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("markup")));
				if(is_numeric($id) && $id > 0)
				{
					$data["mt_type"] = is_numeric($this->input->post("mu_pattern")) ? $this->input->post("mu_pattern") : DEFAULT_DISCOUNT;
					$data["mt_amount"] = (float)$this->input->post("mu_amount");
					$result = $this->Markup_model->update_markup($id, $data);
					if($result !== false)
					{
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Markup updated Successfully.";
					}
					else
					{
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			else
				$response["msg"] = "Enter proper details.";
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Edit Markup";
			$id = $this->encrypt->decode(base64_decode($this->input->get("markup")));
			if(is_numeric($id) && $id > 0)
			{
				$data["markup_id"] = $this->input->get("markup");
				$data["markup"] = $this->Markup_model->get_markup($id);
				$this->load->view("markup/edit_markup", $data);
			}
			else
				redirect($this->data["controller"].DIRECTORY_SEPARATOR."types", "refresh");
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR."types", "refresh");
	}

	//update markup status	
	public function markup_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("markup")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Markup Activate" : " - Markup Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Markup_model->update_markup($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Markup deactivated successfully." : "Markup activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update Markup status.";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete_markup()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("markup", "Markup ID", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Delete Markup";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("markup")));
				if(is_numeric($id) && $id > 0)
				{
					$this->db->trans_begin();
					try
					{
						$result = $this->Markup_model->delete_markup($id);
						if($result !== false)
						{
							$this->db->trans_commit();
							$response["msg"] = "Markup deleted successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg"] = "Failed to delete Markup.";
						}
					}
					catch(Exception $ex)
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
					}
				}
			}
		}
		echo json_encode($response);exit;
	}

	//b2c markup list	
	public function b2c()
	{
		$this->data["page_title"] = "B2C User Markup";
		$this->load->view("markup/b2c/index");
	}

	//display all b2c markup
	public function b2c_markup_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "markup_name", "country_en", "api_name", "airline_en", "o_airport", "d_airport");

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
			$result_list = $this->Markup_model->get_b2c_markups($sWhere, $sOrder, $sLimit);
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
			$markup_patterns = array("1" => "Fixed ($)", "2" => "Percentage (%)");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$user_type = is_null($aRow["user_type"]) ? "" : $aRow["user_type"];
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control markup_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/markup_edit".DEFAULT_EXT."?markup=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_markup' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "name" => $aRow["markup_name"], "country_name" => (is_null($aRow["country_en"]) ? "All Countries" : $aRow["country_en"]), "api" => (is_null($aRow["api_name"]) ? "All" : $aRow["api_name"]), "airline" => (is_null($aRow["airline_en"]) ? "All" : $aRow["airline_en"]), "o_airport" => (is_null($aRow["o_airport"]) ? "All" : $aRow["o_airport"]), "d_airport" => (is_null($aRow["d_airport"]) ? "All" : $aRow["d_airport"]), "category" => $markup_patterns[$aRow["mt_type"]], "amount" => $aRow["mt_amount"], "status_html" => $status_html, "actions" => $actions, "is_general" => $user_type, "id" => $id));
				$row[] = "";
				$row[] = "";
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

	//b2b markup list	
	public function b2b()
	{
		$this->data["page_title"] = "B2B User Markup";
		$this->load->view("markup/b2b/index");
	}

	//display all b2b markup
	public function b2b_markup_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "markup_name", "country_en", "api_name", "airline_en", "o_airport", "d_airport");

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
			$result_list = $this->Markup_model->get_b2b_markups($sWhere, $sOrder, $sLimit);
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
			$markup_patterns = array("1" => "Fixed ($)", "2" => "Percentage (%)");
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$user_type = is_null($aRow["user_type"]) ? "" : $aRow["user_type"];
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control markup_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/markup_edit".DEFAULT_EXT."?markup=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_markup' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "name" => $aRow["markup_name"], "country_name" => (is_null($aRow["country_en"]) ? "All Countries" : $aRow["country_en"]), "api" => (is_null($aRow["api_name"]) ? "All" : $aRow["api_name"]), "airline" => (is_null($aRow["airline_en"]) ? "All" : $aRow["airline_en"]), "o_airport" => (is_null($aRow["o_airport"]) ? "All" : $aRow["o_airport"]), "d_airport" => (is_null($aRow["d_airport"]) ? "All" : $aRow["d_airport"]), "agent" => (is_null($aRow["b2b"]) ? "All Agents" : $aRow["firstname"]." ".$aRow["lastname"]." (".$aRow["user_id"].")"), "category" => $markup_patterns[$aRow["mt_type"]], "amount" => $aRow["mt_amount"], "status_html" => $status_html, "actions" => $actions, "is_general" => $user_type, "id" => $id));
				$row[] = "";
				$row[] = "";
				$row[] = "";
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
}