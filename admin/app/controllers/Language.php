<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Language extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Language_model");
		$this->data["page_main_title"] = "Language Management";
		$this->data["page_title"] = "Language Management";
	}

	//view all pages
	public function index()
	{
		$this->load->view('language/index');
	}

	//load all pages
	public function get_pages()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "page");

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
			$result_list = $this->Language_model->get_pages($sWhere, $sOrder, $sLimit);
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
			$row = array(); 
			$no_page_name = "General (without Page-name)";
			$id = base64_encode($this->encrypt->encode(PAGE_NA));
			$actions = "<div class='pull-left'>";
			$actions .= "<a class='btn btn-warning btn-xs has-tooltip' data-placement='top' title='View Labels' href='".base_url($this->data["controller"]."/labels".DEFAULT_EXT."?page=".$id)."'><i class='icon-eye-open'></i></a>\n";
			$actions .= "<br>";
			$actions .= "</div>";
			$row[] = json_encode(array("sl_no" => "", "name" =>$no_page_name, "actions" => $actions, "id" => $id));
			$row[] = "";
			$row[] = "";
			$output["aaData"][] = $row;
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-warning btn-xs has-tooltip' data-placement='top' title='View Labels' href='".base_url("language/labels".DEFAULT_EXT."?page=".$id)."'><i class='icon-eye-open'></i></a>\n";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_lang_page' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_lang_page' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "name" =>$aRow["page"], "actions" => $actions, "id" => $id));
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

	public function add_page()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Add Page";
			$this->form_validation->set_rules("lang_page", "Page name", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$page = ucwords($this->input->post("lang_page"));
				if($this->Language_model->is_page_exists($page) === false)
				{
					$page_id = $this->Language_model->add_page(array("page" => $page));
					if($page_id > 0)
					{
						$response["status"] = "true";
						$response["msg"] = "Page Added Successfully";
						$response["msg_status"] = "success";
						$new_data = array();
						$id = base64_encode($this->encrypt->encode($page_id));
						$new_data["id"] = $id;
						$new_data["name"] = $page;
						$response["new_data"] = $new_data;
						$actions = "<div class='pull-left'>";
						$actions .= "<a class='btn btn-warning btn-xs has-tooltip' data-placement='top' title='View Labels' href='".base_url("language/labels".DEFAULT_EXT."?page=".$id)."'><i class='icon-eye-open'></i></a>\n";
						$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_lang_page' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
						$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_lang_page' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
						$actions .= "<br>";
						$actions .= "</div>";
						$new_row = "<tr><td></td><td>".$page."</td><td>".$actions."</td></tr>";
						$response["new_row"] = $new_row;
					}
					else
						$response["msg"] = "Unable to add new page.";
				}
				else
				{
					$response["msg"] = "Page name already exists.";
					$response["msg_status"] = "info";
				}
			}
			else
				$response["msg"] = "Please enter valid Page name";
		}
		echo json_encode($response);exit;
	}

	public function update_page()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("page")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Edit Page";
				$this->form_validation->set_rules("lang_page", "Page name", "trim|required");
				if($this->form_validation->run() !== false)
				{
					$page = ucwords($this->input->post("lang_page"));
					if($this->Language_model->is_page_exists($page, $id) === false)
					{
						$result = $this->Language_model->update_page($id, array("page" => $page));
						if($result !== false)
						{
							$response["status"] = "true";
							$response["msg_status"] = "success";
							$response["msg"] = "Page name updated successfully.";
						}
						else
						{
							$response["msg_status"] = "info";
							$response["msg"] = "No changes are made.";
						}
					}
					else
					{
						$response["msg_status"] = "info";
						$response["msg"] = "This Page name already exists. Try different one.";
					}
				}
				else
					$response["msg"] = "Please enter valid details";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete_page()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("page")));
			$all = $this->input->post("without_labels") === "false" ? true : false;
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = "Language Library - Delete Page";
				$result = $this->Language_model->delete_page($id, $all);
				if($result)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "This Page name deleted Successfully.";
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

	//view all labels of page
	public function labels()
	{
		$id = $this->encrypt->decode(base64_decode($this->input->get("page")));
		$id = is_numeric($id) ? $id : PAGE_NA;
		if($id > 0 || $id === PAGE_NA)
		{
			if($id === PAGE_NA)
			{
				$this->data["page_title"] = "General Labels";
				$data["page"] = $this->input->get("page");
				$this->load->view("language/labels", $data);
			}
			else
			{
				$page_details = $this->Language_model->is_page_exists("%", $id);
				if($page_details !== false)
				{
					$this->data["page_title"] = $page_details->page." Labels";
					$data["page"] = $this->input->get("page");
					$this->load->view("language/labels", $data);
				}
				else
					redirect($this->data["controller"], "refresh");
			}
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	//load all pages
	public function get_labels()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "label", "title_en", "title_fa");

		$iDisplayStart = $this->input->get("iDisplayStart", true);
		$iDisplayLength = $this->input->get("iDisplayLength", true);
		$iSortCol_0 = $this->input->get("iSortCol_0", true);
		$iSortingCols = $this->input->get("iSortingCols", true);
		$sSearch = $this->input->get("sSearch", true);
		$sEcho = $this->input->get("sEcho", true);
		if($this->input->is_ajax_request())
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("page")));
			$id  === is_numeric($id) ? $id : PAGE_NA;
			if($id > 0 || $id === PAGE_NA)
			{
				// Limit
				$sLimit = " LIMIT 0, 25";
				if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] !== "-1")
					$sLimit = " LIMIT ".intval($_GET["iDisplayStart"]).", ".intval($_GET["iDisplayLength"]);

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
				if($id === PAGE_NA)
					$sWhere = " WHERE `page` IS NULL ";
				else
					$sWhere = " WHERE `page` = '$id' ";
				if (isset($_GET["sSearch"]) && $_GET["sSearch"] !== "")
				{
					$sWhere .= " AND (";
					for ($i = 0; $i < count($aColumns); $i++)
					{
						if ( isset($_GET["bSearchable_".$i]) && $_GET["bSearchable_".$i] === "true")
							if($aColumns[$i] !== "x")
								$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET["sSearch"]."%' OR ";
					}
					$sWhere = substr_replace($sWhere, "", -3);
					$sWhere .= ")";
				}
				$result_list = $this->Language_model->get_labels($sWhere, $sOrder, $sLimit);
				$iTotal = $result_list["count"];

				$rResult = $result_list["result"];

				// Output
				$output = array(
				"sEcho" => intval($sEcho),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
				);
				$i =  isset($_GET["iDisplayStart"]) ? ($_GET["iDisplayStart"] * 1) + 1 : 1;
				foreach($rResult as $aRow)
				{
					$id = base64_encode($this->encrypt->encode($aRow["id"]));
					$row = array();
					$actions = "<div class='pull-left'>";
						$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url("language/edit_label".DEFAULT_EXT."?label=".$id)."'><i class='icon-edit'></i></a>\n";
						$actions .= "</div>";
					$row[] = json_encode(array("sl_no" => $i++, "label" =>$aRow["label"], "english" => $aRow["title_en"], "farsi" => $aRow["title_fa"], "actions" => $actions, "id" => $id));
					$row[] = "";
					$row[] = "";
					$row[] = "";
					$row[] = "";
					$output["aaData"][] = $row;
				}
			}
			else
			{
				// Output
				$output = array(
				"sEcho" => intval($sEcho),
				"iTotalRecords" => 0,
				"iTotalDisplayRecords" => 0,
				"aaData" => array()
				);
			}
			echo json_encode($output);exit;
		}
		// Output
		$output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array()
		);
		echo json_encode($output);exit;
	}

	public function add_label()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Add Label";
			//add new label
			$id = $this->encrypt->decode(base64_decode($this->input->post("page")));
			if(is_numeric($id) && $id > 0)
			{
				$this->form_validation->set_rules("label", "Label name", "trim|required");
				$this->form_validation->set_rules("english", "English label name", "trim|required");
				$this->form_validation->set_rules("farsi", "Farsi label name", "trim|required");
				if($this->form_validation->run() !== false)
				{
					$data["page"] = $id;
					$data["label"] = strtolower($this->input->post("label"));
					$data["title_en"] = str_replace("'", "&apos;", $this->input->post("english"));
					$data["title_fa"] = str_replace("'", "&apos;", $this->input->post("farsi"));
					if($this->Language_model->is_label_exists($data["label"]) === false)
					{
						$result = $this->Language_model->add_label($data);
						if($result)
						{
							$response["status"] = "true";
							$response["msg_status"] = "success";
							$response["msg"] = "Label added successfully.";
						}
						else
							$response["msg"] = "Failed to add new label.";
					}
					else
					{
						$response["msg_status"] = "info";
						$response["msg"] = "Label name already exists.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			//open view to add new label
			$id = $this->encrypt->decode(base64_decode($this->input->get("page")));
			if(is_numeric($id) && $id > 0)
			{
				$page_details = $this->Language_model->is_page_exists("%", $id);
				if($page_details !== false)
				{
					$this->data["page_title"] = "Add Label";
					$data["page_name"] = $page_details->page;
					$data["page"] = $this->input->get("page");
					$this->load->view("language/add_label", $data);
				}
				else
					redirect($this->data["controller"], "refresh");
			}
			else
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Operation");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
				$this->session->set_flashdata(SESSION_PREPEND."notification_title", "No such page exists. Please check again!!");
				redirect($this->data["controller"], "refresh");
			}
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	public function get_page_list()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$pages = $this->Language_model->get_all_pages($id, $search, $page);
		echo json_encode($pages);exit;
	}

	public function edit_label()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			//add new label
			$id = $this->encrypt->decode(base64_decode($this->input->post("label")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Update Label";
				$this->form_validation->set_rules("english", "English label name", "trim|required");
				$this->form_validation->set_rules("farsi", "Farsi label name", "trim|required");
				if($this->form_validation->run() !== false)
				{
					$data["page"] = is_numeric($this->input->post("lang_page")) ? $this->input->post("lang_page") : null;
					$data["title_en"] = str_replace("'", "&apos;", $this->input->post("english"));
					$data["title_fa"] = str_replace("'", "&apos;", $this->input->post("farsi"));
					$result = $this->Language_model->update_label($id, $data);
					if($result !== false)
					{
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Label updated successfully.";
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
			//open view to add new label
			$id = $this->encrypt->decode(base64_decode($this->input->get("label")));
			if(is_numeric($id) && $id > 0)
			{
				$page_details = $this->Language_model->is_label_exists("%", $id);
				if($page_details !== false)
				{
					$this->data["page_title"] = "Update Label";
					$data["page"] = base64_encode($this->encrypt->encode($page_details->page));
					$data["label"] = $page_details;
					$this->load->view("language/edit_label", $data);
				}
				else
					redirect($this->data["controller"], "refresh");
			}
			else
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Operation");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
				$this->session->set_flashdata(SESSION_PREPEND."notification_title", "No such label exists. Please check again!!");
				redirect($this->data["controller"], "refresh");
			}
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	public function delete_label()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = "Language Library - Delete Label";
			$id = $this->encrypt->decode(base64_decode($this->input->post("page")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Language_model->delete_label($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Label name deleted successfully.";
				}
				else
					$response["msg"] = "Sorry, Failed to delete selected label.";
			}
		}
		echo json_encode($response);exit;
	}
}