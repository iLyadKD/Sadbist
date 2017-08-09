<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/
class Sitemap extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Sitemap_model");
		$this->data["page_main_title"] = "Sitemap Management";
		$this->data["page_title"] = "Sitemap Management";
	}

	//default seo	
	public function index()
	{
		$this->load->view("sitemap/index");
	}

	//seo	
	public function seo()
	{
		$this->data["page_title"] = "SEO Optimization";
		$this->load->view("sitemap/metatags/index");
	}

	//get all seo list
	public function metatags_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "name", "description");

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
			$result_list = $this->Sitemap_model->get_all_metatags($sWhere, $sOrder, $sLimit);
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
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control metatag_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."seo_edit".DEFAULT_EXT."?metatag=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_metatag' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "metatag" =>($aRow["tag_type"] ." => ". $aRow["name"]), "description" => $aRow["description"], "status" => $status_html, "actions" => $actions, "id" => $id));
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

	public function seo_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tag_type", "Tag Type", "trim|required");
			$this->form_validation->set_rules("tag_name", "Tagname", "trim|required");
			$this->form_validation->set_rules("tag_value", "Tagvalue", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Metatags";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$tag_type = strtolower($this->input->post("tag_type"));
				$tag_name = strtolower($this->input->post("tag_name"));
				$tag_value = strip_tags($this->input->post("tag_value"));
				$insert_data = array("tag_type" => $tag_type, "name" => $tag_name, "description" => $tag_value);
				$is_valid_tagname = $this->Sitemap_model->is_valid_tagname($insert_data);
				if($is_valid_tagname !== false)
				{
					$is_exists = $this->Sitemap_model->is_tag_exists($insert_data);
					if($is_exists === false)
					{
						$result = $this->Sitemap_model->add_metatag($insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Metatags added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
							$response["msg"] = "Failed to add Metatags.";
					}
					else
						$response["msg"] = "\"".$tag_type."\" => \"".$tag_name."\" is already exists.";
				}
				else
					$response["msg"] = "\"".$tag_name."\" is invalid tagname.";

			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "New SEO Metatags";
			$this->load->view("sitemap/metatags/add");
		}
	}

	public function seo_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("metatag")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Metatag Activate" : " - Metatag Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Sitemap_model->update_metatag($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Metatag deactivated." : "Metatag activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function seo_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("metatag", "Metatag ID", "trim|required");
			$this->form_validation->set_rules("tag_type", "Tag Type", "trim|required");
			$this->form_validation->set_rules("tag_type_old", "Tag Type", "trim|required");
			$this->form_validation->set_rules("tag_name", "Tagname", "trim|required");
			$this->form_validation->set_rules("tag_name_old", "Tagname", "trim|required");
			$this->form_validation->set_rules("tag_value", "Tagvalue", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Metatag";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("metatag")));
				if(is_numeric($id) && $id > 0)
				{
					$tag_type = strtolower($this->input->post("tag_type"));
					$tag_type_old = strtolower($this->input->post("tag_type_old"));
					$tag_name = strtolower($this->input->post("tag_name"));
					$tag_name_old = strtolower($this->input->post("tag_name_old"));
					$tag_value = strip_tags($this->input->post("tag_value"));
					$datas = array("tag_type" => $tag_type, "name" => $tag_name, "description" => $tag_value);
					$is_valid_tagname = $this->Sitemap_model->is_valid_tagname($datas);
					if($is_valid_tagname !== false)
					{
						$excepts = array("tag_type" => $tag_type_old, "name" => $tag_name_old);
						$is_exists = $this->Sitemap_model->is_tag_exists($datas, $excepts);
						if($is_exists === false)
						{
							$result = $this->Sitemap_model->update_metatag($id, $datas);
							if($result !== false)
							{
								$response["msg"] = "Metatag updated successfully.";
								$response["msg_status"] = "success";
								$response["status"] = "true";
								$new_data = array();
								$new_data["tag_type"] = $tag_type;
								$new_data["tag_name"] = $tag_name;
								$response["new_data"] = $new_data;
							}
							else
							{
								$response["msg_status"] = "info";
								$response["msg"] = "No changes are made.";
							}
						}
						else
							$response["msg"] = "\"".$tag_type."\" => \"".$tag_name."\" is already exists.";
					}
					else
						$response["msg"] = "\"".$tag_name."\" is invalid tagname.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Metatag";
			$id = $this->encrypt->decode(base64_decode($this->input->get("metatag")));
			if(is_numeric($id) && $id > 0)
			{
				$data["metatag"] = $this->Sitemap_model->get_metatag($id);
				if($data["metatag"] !== false)
				{
					$data["metatag_id"] = $this->input->get("metatag");
					$this->load->view("sitemap/metatags/edit", $data);
				}
				else
					redirect($this->data["controller"].DIRECTORY_SEPARATOR.explode("_", $this->data["method"])[0], "refresh");
			}
			else
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.explode("_", $this->data["method"])[0], "refresh");
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.explode("_", $this->data["method"])[0], "refresh");
	}

	//delete seo
	public function delete_seo()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("metatag")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Metatag";
				$result = $this->Sitemap_model->delete_metatag($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Metatag deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	//google analytics	
	public function analytics()
	{
		$this->data["page_title"] = "Google Analytics";
		$this->load->view("sitemap/analytics/index");
	}

	//get all analytic list
	public function analytics_list()
	{
		
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "tracker_code", "track_name", "tracker_type");

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
			$result_list = $this->Sitemap_model->get_all_analytics($sWhere, $sOrder, $sLimit);
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
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control analytics_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/analytics_edit".DEFAULT_EXT."?track=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_analytics' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "track_code" => $aRow["track_code"], "track_name" => $aRow["track_name"], "track_type" => $aRow["track_type"], "status" => $status_html, "actions" => $actions, "id" => $id));
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

	public function analytics_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("code", "Tracker Code", "trim|required");
			$this->form_validation->set_rules("track_name", "Tracker Name", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Analytics";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$track_name = ucfirst($this->input->post("track_name"));
				$code = strtoupper($this->input->post("code"));
				$insert_data = array("track_code" => $code, "track_name" => $track_name);
				$is_exists = $this->Sitemap_model->is_analytics_exists($insert_data);
				if($is_exists === false)
				{
					$result = $this->Sitemap_model->add_analytics($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Analytics added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Failed to add Analytics.";
				}
				else
					$response["msg"] = "\"".$is_exists->track_name."\" or \"".$is_exists->track_code."\" already exists.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "New Analytics";
			$this->load->view("sitemap/analytics/add");
		}
	}

	public function analytics_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("analytics")));
			$status = $this->input->post("status") === "0" ? 0 : intval($this->input->post("status"));
			$response["title"] = $this->data["page_main_title"].($status === "0" ? " - Deactivate Analytics" : " - Activate Analytics");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Sitemap_model->update_analytics($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Analytics deactivated." : "Analytics activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function analytics_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("analytics", "Analytics ID", "trim|required");
			$this->form_validation->set_rules("code", "Code", "trim|required");
			$this->form_validation->set_rules("track_name", "Track Name", "trim|required");
			$this->form_validation->set_rules("code_old", "Code", "trim|required");
			$this->form_validation->set_rules("track_name_old", "Track Name", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Analytics";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("analytics")));
				if(is_numeric($id) && $id > 0)
				{
					$code = strtoupper($this->input->post("code"));
					$track_name = ucfirst($this->input->post("track_name"));
					$code_old = strtoupper($this->input->post("code_old"));
					$track_name_old = ucfirst($this->input->post("track_name_old"));
					$datas = array("track_code" => $code, "track_name" => $track_name);
					$excepts = array("track_code" => $code_old, "track_name" => $track_name_old);
					$is_exists = $this->Sitemap_model->is_analytics_exists($datas, $excepts);
					if($is_exists === false)
					{
						$result = $this->Sitemap_model->update_analytics($id, $datas);
						if($result !== false)
						{
							$response["msg"] = "Analytics updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$new_data = array();
							$new_data["code"] = $code;
							$new_data["track_name"] = $track_name;
							$response["new_data"] = $new_data;
						}
						else
						{
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "\"".$is_exists->track_name."\" or \"".$is_exists->track_code."\" already exists.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Analytics";
			$id = $this->encrypt->decode(base64_decode($this->input->get("track")));
			if(is_numeric($id) && $id > 0)
			{
				$data["track"] = $this->Sitemap_model->get_analytics($id);
				if($data["track"] !== false)
				{
					$data["track_id"] = $this->input->get("track");
					$this->load->view("sitemap/analytics/edit", $data);
				}
				else
					redirect($this->data["controller"].DIRECTORY_SEPARATOR.explode("_", $this->data["method"])[0], "refresh");
			}
			else
				redirect($this->data["controller"].DIRECTORY_SEPARATOR.explode("_", $this->data["method"])[0], "refresh");
		}
		else
			redirect($this->data["controller"].DIRECTORY_SEPARATOR.explode("_", $this->data["method"])[0], "refresh");
	}

	//delete analytics
	public function delete_analytics()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("analytics")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Analytics";
				$result = $this->Sitemap_model->delete_analytics($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Analytics deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

}