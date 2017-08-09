<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Location extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Location_model");
		$this->data["page_main_title"] = "Location Management";
		$this->data["page_title"] = "Location Management";
	}

	//view all countries
	public function index()
	{
		//default countries
		$this->data["page_title"] = $this->data["page_main_title"]." - Countries";
		$this->load->view('location/index');
	}

	//view all countries
	public function countries()
	{
		$this->data["page_title"] = $this->data["page_main_title"]." - Countries";
		$this->load->view('location/country/index');
	}

	//get all countries list
	public function countries_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "id", "country_en", "iso_3", "iso_number");

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
			$result_list = $this->Location_model->get_all_countries($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control country_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/countries_edit".DEFAULT_EXT."?country=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "iso_2" =>$aRow["id"], "name" => $aRow["country_en"], "iso_3" => $aRow["iso_3"], "iso_num" => $aRow["iso_number"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	public function countries_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("country_en", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_fa", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_id2", "ISO 2", "trim|required");
			$this->form_validation->set_rules("country_id3", "ISO 3", "trim|required");
			$this->form_validation->set_rules("country_id_num", "ISO Number", "trim|required|numeric");
			$response["title"] = "Add Country";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$country_en = ucfirst($this->input->post("country_en"));
				$country_fa = $this->input->post("country_fa");
				$id_2 = strtoupper($this->input->post("country_id2"));
				$id_3 = strtoupper($this->input->post("country_id3"));
				$id_num = $this->input->post("country_id_num");
				$country_data = array("country_en" => $country_en, "country_fa" => $country_fa, "id" => $id_2, "iso_3" => $id_3, "iso_number" => $id_num);
				$is_exists = $this->Location_model->is_country_exists($country_data);
				if($is_exists === false)
				{
					$result = $this->Location_model->add_country($country_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Country added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Failed to add Country.";
				}
				else
					$response["msg"] = "Few details already assigned to \"".$is_exists->country_en."\"";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Country";
			$this->load->view("location/country/add");
		}
	}

	public function country_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("country")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Country Activate" : " - Country Deactivate");
			if(strlen($id) === 2)
			{
				$result = $this->Location_model->update_country($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Country deactivated." : "Country activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function countries_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("country_en", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_fa", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_id2", "ISO 2", "trim|required");
			$this->form_validation->set_rules("country_id3", "ISO 3", "trim|required");
			$this->form_validation->set_rules("country_id_num", "ISO Number", "trim|required|numeric");
			$this->form_validation->set_rules("country_en_old", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_fa_old", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_id2_old", "ISO 2", "trim|required");
			$this->form_validation->set_rules("country_id3_old", "ISO 3", "trim|required");
			$this->form_validation->set_rules("country_id_num_old", "ISO Number", "trim|required|numeric");
			$response["title"] = $this->data["page_main_title"]." - Update Country";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("country")));
				if(strlen($id) === 2)
				{
					$country_en = ucfirst($this->input->post("country_en"));
					$country_fa = $this->input->post("country_fa");
					$id_2 = strtoupper($this->input->post("country_id2"));
					$id_3 = strtoupper($this->input->post("country_id3"));
					$id_num = intval($this->input->post("country_id_num"));
					$country_en_old = ucfirst($this->input->post("country_en_old"));
					$country_fa_old = $this->input->post("country_fa_old");
					$id_2_old = strtoupper($this->input->post("country_id2_old"));
					$id_3_old = strtoupper($this->input->post("country_id3_old"));
					$id_num_old = intval($this->input->post("country_id_num_old"));
					$datas = array("country_en" => $country_en, "country_fa" => $country_fa, "id" => $id_2, "iso_3" => $id_3, "iso_number" => $id_num);
					$excepts = array("country_en" => $country_en_old, "country_fa" => $country_fa_old, "id" => $id_2_old, "iso_3" => $id_3_old, "iso_number" => $id_num_old);
					$is_exists = $this->Location_model->is_country_exists($datas, $excepts);
					if($is_exists === false)
					{
						$result = $this->Location_model->update_country($id, $datas);
						if($result !== false)
						{
							$response["msg"] = "Country updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$new_data = array();
							$new_data["id"] = base64_encode($this->encrypt->encode($id_2));
							$new_data["country_en"] = $country_en;
							$new_data["country_fa"] = $country_fa;
							$new_data["id_2"] = $id_2;
							$new_data["id_3"] = $id_3;
							$new_data["id_num"] = $id_num;
							$response["new_data"] = $new_data;
						}
						else
						{
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Few details already assigned to \"".$is_exists->country_en."\"";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Country";
			$id = $this->encrypt->decode(base64_decode($this->input->get("country")));
			if(strlen($id) === 2)
			{
				$data["country"] = $this->Location_model->get_country($id);
				if($data["country"] !== false)
				{
					$data["country_id"] = $this->input->get("country");
					$this->load->view("location/country/edit", $data);
				}
				else
					redirect($this->data["controller"]."/countries", "refresh");
			}
			else
				redirect($this->data["controller"]."/countries", "refresh");
		}
		else
			redirect($this->data["controller"]."/countries", "refresh");
	}

	//delete country
	public function delete_country()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("country")));
			if(strlen($id) === 2)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Country";
				$result = $this->Location_model->delete_country($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Country deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	//view all states
	public function regions()
	{
		$this->data["page_title"] = $this->data["page_main_title"]." - States/Regions";
		$this->load->view('location/region/index');
	}


	//get all regions list
	public function regions_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "country_en", "name_en", "name_fa");

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
			$result_list = $this->Location_model->get_all_regions($sWhere, $sOrder, $sLimit);
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
				$id = base64_encode($this->encrypt->encode($aRow["country"].":::".$aRow["region"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control region_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/regions_edit".DEFAULT_EXT."?region=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_region' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "country" =>$aRow["country"], "country_name" => $aRow["country_en"], "region" => $aRow["region"], "region_name" => $aRow["name_en"], "region_name_fa" => $aRow["name_fa"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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


	public function regions_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("country", "Country Name", "trim|required");
			$this->form_validation->set_rules("region", "Region Code", "trim|required");
			$this->form_validation->set_rules("region_name_en", "Region Name", "trim|required");
			$this->form_validation->set_rules("region_name_fa", "Region Name", "trim|required");
			$response["title"] = "Add State/Region";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$country = strtoupper($this->input->post("country"));
				$region_code = strtoupper($this->input->post("region"));
				$region_name_en = ucfirst($this->input->post("region_name_en"));
				$region_name_fa = ucfirst($this->input->post("region_name_fa"));
				$region_data = array("country" => $country, "region" => $region_code, "name_en" => $region_name_en, "name_fa" => $region_name_fa);
				$is_exists = $this->Location_model->is_region_exists($region_data);
				if($is_exists === false)
				{
					$result = $this->Location_model->add_region($region_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "State/Region added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Failed to add State/Region.";
				}
				else
					$response["msg"] = "State/Region name \"".$region_name_en."\" is already exists.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add State/Region";
			$this->data["page_main_title"] = "State/Region Management";
			$this->load->view("location/region/add");
		}
	}

	public function region_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = explode(":::", $this->encrypt->decode(base64_decode($this->input->post("region"))));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Region Activate" : " - Region Deactivate");
			if(count($id) === 2)
			{
				$result = $this->Location_model->update_region($id[0], $id[1], array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Region deactivated." : "Region activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}


	public function regions_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("region_id", "Region/State", "trim|required");
			$this->form_validation->set_rules("country", "Country Name", "trim|required");
			$this->form_validation->set_rules("region", "Region/State", "trim|required");
			$this->form_validation->set_rules("region_name_en", "Region English name", "trim|required");
			$this->form_validation->set_rules("region_name_fa", "Region Farsi name", "trim|required");
			$this->form_validation->set_rules("country_old", "Country Name", "trim|required");
			$this->form_validation->set_rules("region_old", "Region/State", "trim|required");
			$this->form_validation->set_rules("region_name_en_old", "Region name", "trim|required");
			$this->form_validation->set_rules("region_name_fa_old", "Region name", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Region/State";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = explode(":::", $this->encrypt->decode(base64_decode($this->input->post("region_id"))));
				if(count($id) === 2)
				{
					$country = strtoupper($this->input->post("country"));
					$region = strtoupper($this->input->post("region"));
					$region_name_en = ucfirst($this->input->post("region_name_en"));
					$region_name_fa = $this->input->post("region_name_fa");
					$country_old = strtoupper($this->input->post("country_old"));
					$region_old = strtoupper($this->input->post("region_old"));
					$region_name_en_old = ucfirst($this->input->post("region_name_en_old"));
					$region_name_fa_old = $this->input->post("region_name_fa_old");
					$region_data = array("country" => $country, "region" => $region, "name_en" => $region_name_en, "name_fa" => $region_name_fa);
					$excepts = array("country" => $country_old, "region" => $region_old, "name_en" => $region_name_en_old, "name_fa" => $region_name_fa_old);
					$is_exists = $this->Location_model->is_region_exists($region_data, $excepts);
					if($is_exists === false)
					{
						$result = $this->Location_model->update_region($id[0], $id[1], $region_data);
						if($result !== false)
						{
							$response["msg"] = "Region updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$new_data = array();
							$new_data["id"] = base64_encode($this->encrypt->encode(implode(":::", array($country, $region))));
							$new_data["country"] = $country;
							$new_data["region"] = $region;
							$new_data["region_name_en"] = $region_name_en;
							$new_data["region_name_fa"] = $region_name_fa;
							$response["new_data"] = $new_data;
						}
						else
						{
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "\"".$is_exists->name."\" region/state name already assigned to other region/state.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Region/State";
			$id = explode(":::", $this->encrypt->decode(base64_decode($this->input->get("region"))));
			if(count($id) === 2)
			{
				$data["region"] = $this->Location_model->get_region($id[0], $id[1]);
				if($data["region"] !== false)
				{
					$data["region_id"] = $this->input->get("region");
					$this->load->view("location/region/edit", $data);
				}
				else
					redirect($this->data["controller"]."/regions", "refresh");
			}
			else
				redirect($this->data["controller"]."/regions", "refresh");
		}
		else
			redirect($this->data["controller"]."/regions", "refresh");
	}

	//delete State/region
	public function delete_region()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = explode(":::", $this->encrypt->decode(base64_decode($this->input->post("region"))));
			if(count($id) === 2)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete State/Region";
				$result = $this->Location_model->delete_region($id[0], $id[1]);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "State/Region deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	//view all cities
	public function cities()
	{
		$this->data["page_title"] = $this->data["page_main_title"]." - Cities";
		$this->load->view('location/city/index');
	}


	//get all cities list
	public function cities_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "country_en", "name_en", "city_en");

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
			$result_list = $this->Location_model->get_all_cities($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control city_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/cities_edit".DEFAULT_EXT."?city=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_city' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "country_name" =>$aRow["country_en"], "region_name" => $aRow["name_en"], "city_name" => $aRow["city_en"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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


	public function cities_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("country_name", "Country Name", "trim|required");
			$this->form_validation->set_rules("region", "Region/State", "trim|required");
			$this->form_validation->set_rules("state_name", "Region name", "trim");
			$this->form_validation->set_rules("city_en", "City name", "trim|required");
			$this->form_validation->set_rules("city_fa", "City name", "trim|required");
			$this->form_validation->set_rules("latitude", "latitude", "trim");
			$this->form_validation->set_rules("longitude", "longitude", "trim");
			$response["title"] = "Add City";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$country = strtoupper($this->input->post("country"));
				$country_name = ucfirst($this->input->post("country_name"));
				$region = strtoupper($this->general->extract_region($this->input->post("region")));
				$region_name = ucfirst($this->input->post("state_name"));
				if($region === NO_REGION)
				{
					$region = null;
					$region_name = "";
				}
				$city_en = ucfirst($this->input->post("city_en"));
				$city_fa = $this->input->post("city_fa");
				$latitude = floatval($this->input->post("latitude"));
				$longitude = floatval($this->input->post("longitude"));
				$keyword = $city_en.", ".$region_name.", ".$country_name;
				$keyword = str_replace(", ,", ",", $keyword);
				$city_data = array("country" => $country, "region" => $region, "city_en" => $city_en, "city_fa" => $city_fa, "latitude" => $latitude, "longitude" => $longitude, "keyword" => $keyword);
				$is_exists = $this->Location_model->is_city_exists($city_data);
				if($is_exists === false)
				{
					$result = $this->Location_model->add_city($city_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "City added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Failed to add City.";
				}
				else
					$response["msg"] = "City name \"".$city_en."\" is already exists.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add City";
			$this->data["page_main_title"] = "City Management";
			$this->load->view("location/city/add");
		}
	}

	public function city_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("city")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - City Activate" : " - City Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Location_model->update_city($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "City deactivated." : "City activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}


	public function cities_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("city_id", "Region/State", "trim|required");
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("country_old", "Country", "trim|required");
			$this->form_validation->set_rules("country_name", "Country Name", "trim|required");
			$this->form_validation->set_rules("country_name_old", "Country Name", "trim|required");
			$this->form_validation->set_rules("region", "Region/State", "trim|required");
			$this->form_validation->set_rules("region_old", "Region/State", "trim|required");
			$this->form_validation->set_rules("state_name", "Region name", "trim");
			$this->form_validation->set_rules("state_name_old", "Region name", "trim");
			$this->form_validation->set_rules("city_en", "City name", "trim|required");
			$this->form_validation->set_rules("city_fa", "City name", "trim|required");
			$this->form_validation->set_rules("city_en_old", "City name", "trim|required");
			$this->form_validation->set_rules("city_fa_old", "City name", "trim|required");
			$this->form_validation->set_rules("latitude", "latitude", "trim");
			$this->form_validation->set_rules("longitude", "longitude", "trim");
			$response["title"] = $this->data["page_main_title"]." - Update City";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("city_id")));
				if(is_numeric($id) && $id > 0)
				{
					$country = strtoupper($this->input->post("country"));
					$country_old = strtoupper($this->input->post("country_old"));
					$country_name = ucfirst($this->input->post("country_name"));
					$country_name_old = ucfirst($this->input->post("country_name_old"));
					$region1 = strtoupper($this->input->post("region"));
					$region = strtoupper($this->general->extract_region($this->input->post("region")));
					$region_old = strtoupper($this->input->post("region_old"));
					$region_name = ucfirst($this->input->post("state_name"));
					$region_name_old = ucfirst($this->input->post("state_name_old"));
					if($region === NO_REGION)
					{
						$region = null;
						$region_name = "";
					}
					$city_en = ucfirst($this->input->post("city_en"));
					$city_fa = $this->input->post("city_fa");
					$city_en_old = ucfirst($this->input->post("city_en_old"));
					$city_fa_old = $this->input->post("city_fa_old");
					$latitude = floatval($this->input->post("latitude"));
					$logitude = floatval($this->input->post("logitude"));
					$keyword = $city_en.", ".$region_name.", ".$country_name;
					$keyword = str_replace(", ,", ",", $keyword);
					$city_data = array("country" => $country, "region" => $region, "city_en" => $city_en, "city_fa" => $city_fa);
					$excepts = array("country" => $country_old, "region" => $region_old, "city_en" => $city_en_old, "city_fa" => $city_fa_old);
					$is_exists = $this->Location_model->is_city_exists($city_data, $excepts);
					if($is_exists === false)
					{
						$result = $this->Location_model->update_city($id, $city_data);
						if($result !== false)
						{
							$response["msg"] = "City updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$new_data = array();
							$new_data["country"] = $country;
							$new_data["region"] = $region1;
							$new_data["region_name"] = $region_name;
							$new_data["country_name"] = $country_name;
							$response["new_data"] = $new_data;
						}
						else
						{
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "\"".$is_exists->city."\" City name already exists.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update City";
			$id = $this->encrypt->decode(base64_decode($this->input->get("city")));
			if(is_numeric($id) && $id > 0)
			{
				$data["city"] = $this->Location_model->get_city($id);
				if($data["city"] !== false)
				{
					$data["city_id"] = $this->input->get("city");
					$this->load->view("location/city/edit", $data);
				}
				else
					redirect($this->data["controller"]."/cities", "refresh");
			}
			else
				redirect($this->data["controller"]."/cities", "refresh");
		}
		else
			redirect($this->data["controller"]."/cities", "refresh");
	}

	//delete City
	public function delete_city()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("city")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete City";
				$result = $this->Location_model->delete_city($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "City deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	//view all airports
	public function airports()
	{
		$this->data["page_title"] = $this->data["page_main_title"]." - Airports";
		$this->load->view('location/airport/index');
	}


	//get all airports list
	public function airports_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "airport_code", "airport_en", "city_code", "city_en", "country_en");

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
			$result_list = $this->Location_model->get_all_airports($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control airport_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/airports_edit".DEFAULT_EXT."?airport=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "country" =>$aRow["country_en"], "airport_code" => $aRow["airport_code"], "airport" => $aRow["airport_en"], "city_code" => $aRow["city_code"], "city" => $aRow["city_en"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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


	public function airports_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("airport_code", "Airport Code", "trim|required");
			$this->form_validation->set_rules("airport_en", "Airport Name", "trim");
			$this->form_validation->set_rules("airport_fa", "Airport Name", "trim");
			$this->form_validation->set_rules("city_code", "City Code", "trim|required");
			$this->form_validation->set_rules("city_en", "City Name", "trim|required");
			$this->form_validation->set_rules("city_fa", "City Name", "trim|required");
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("latitude", "Latitude", "trim|numeric");
			$this->form_validation->set_rules("longitude", "Logitude", "trim|numeric");
			$this->form_validation->set_rules("city_link", "City Link", "trim");
			$this->form_validation->set_rules("airport_link", "Airport Link", "trim");
			$response["title"] = "Add Airport";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$airport_en = ucwords($this->input->post("airport_en"));
				$airport_fa = $this->input->post("airport_fa");
				$airport_code = strtoupper($this->input->post("airport_code"));
				$city_en = ucwords($this->input->post("city_en"));
				$city_fa = $this->input->post("city_fa");
				$city_code = strtoupper($this->input->post("city_code"));
				$country = strtoupper($this->input->post("country"));
				$latitude = floatval($this->input->post("latitude"));
				$longitude = floatval($this->input->post("longitude"));
				$latitude = $latitude === (float)0 ? null : $latitude;
				$longitude = $longitude === (float)0 ? null : $longitude;
				$airport_link = strtolower($this->input->post("airport_link"));
				$city_link = strtolower($this->input->post("city_link"));
				$insert_data = array("airport_code" => $airport_code, "airport_en" => $airport_en, "airport_fa" => $airport_fa, "city_code" => $city_code, "city_en" => $city_en, "city_fa" => $city_fa, "country" => $country, "latitude" => $latitude, "longitude" => $longitude, "airport_link" => $airport_link, "city_link" => $city_link);
				$is_exists = $this->Location_model->is_airport_exists($insert_data);
				if($is_exists === false)
				{
					$result = $this->Location_model->add_airport($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Airport added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Failed to add Airport.";
				}
				else
					$response["msg"] = "Airport detail is already exists.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Airport";
			$this->data["page_main_title"] = "Airport Management";
			$this->load->view("location/airport/add");
		}
	}

	public function airport_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("airport")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Airport Activate" : " - Airport Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Location_model->update_airport($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Airport deactivated." : "Airport activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}


	public function airports_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("airport_id", "Airport", "trim|required");
			$this->form_validation->set_rules("airport_en", "Airport Name", "trim");
			$this->form_validation->set_rules("airport_fa", "Airport Name", "trim");
			$this->form_validation->set_rules("airport_code", "Airport Code", "trim|required");
			$this->form_validation->set_rules("airport_code_old", "Airport Name", "trim|required");
			$this->form_validation->set_rules("city_code", "City Code", "trim|required");
			$this->form_validation->set_rules("city_code_old", "City Code", "trim|required");
			$this->form_validation->set_rules("city_en", "City Name", "trim|required");
			$this->form_validation->set_rules("city_fa", "City Name", "trim|required");
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("country_old", "Country", "trim|required");
			$this->form_validation->set_rules("latitude", "latitude", "trim|numeric");
			$this->form_validation->set_rules("longitude", "longitude", "trim|numeric");
			$this->form_validation->set_rules("city_link", "City Link", "trim");
			$this->form_validation->set_rules("airport_link", "Airport Link", "trim");
			$response["title"] = $this->data["page_main_title"]." - Update Airport";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("airport_id")));
				if(is_numeric($id) && $id > 0)
				{
					$airport_en = ucwords($this->input->post("airport_en"));
					$airport_fa = $this->input->post("airport_fa");
					$airport_code = strtoupper($this->input->post("airport_code"));
					$airport_code_old = strtoupper($this->input->post("airport_code_old"));
					$city_en = ucwords($this->input->post("city_en"));
					$city_fa = $this->input->post("city_fa");
					$city_code = strtoupper($this->input->post("city_code"));
					$city_code_old = strtoupper($this->input->post("city_code_old"));
					$country = strtoupper($this->input->post("country"));
					$country_old = strtoupper($this->input->post("country_old"));
					$latitude = floatval($this->input->post("latitude"));
					$longitude = floatval($this->input->post("longitude"));
					$latitude = $latitude === (float)0 ? null : $latitude;
					$longitude = $longitude === (float)0 ? null : $longitude;
					$airport_link = strtolower($this->input->post("airport_link"));
					$city_link = strtolower($this->input->post("city_link"));
					$update_data = array("airport_code" => $airport_code, "airport_en" => $airport_en, "airport_fa" => $airport_fa, "city_code" => $city_code, "city_en" => $city_en, "city_fa" => $city_fa, "country" => $country, "latitude" => $latitude, "longitude" => $longitude, "airport_link" => $airport_link, "city_link" => $city_link);
					$excepts = array("airport_code" => $airport_code_old, "city_code" => $city_code_old, "country" => $country_old);
					$is_exists = $this->Location_model->is_airport_exists($update_data, $excepts);
					if($is_exists === false)
					{
						$result = $this->Location_model->update_airport($id, $update_data);
						if($result !== false)
						{
							$response["msg"] = "Airport updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$new_data = array();
							$new_data["country"] = $country;
							$new_data["airport_code"] = $airport_code;
							$new_data["city_code"] = $city_en;
							$response["new_data"] = $new_data;
						}
						else
						{
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Airport details name already exists.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Airport";
			$id = $this->encrypt->decode(base64_decode($this->input->get("airport")));
			if(is_numeric($id) && $id > 0)
			{
				$data["airport"] = $this->Location_model->get_airport($id);
				if($data["airport"] !== false)
				{
					$data["airport_id"] = $this->input->get("airport");
					$this->load->view("location/airport/edit", $data);
				}
				else
					redirect($this->data["controller"]."/airports", "refresh");
			}
			else
				redirect($this->data["controller"]."/airports", "refresh");
		}
		else
			redirect($this->data["controller"]."/airports", "refresh");
	}

	//delete Airport
	public function delete_airport()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("airport")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Airport";
				$result = $this->Location_model->delete_airport($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Airport deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	//view all airlines
	public function airlines()
	{
		$this->data["page_title"] = $this->data["page_main_title"]." - Airlines";
		$this->load->view('location/airline/index');
	}


	//get all airlines list
	public function airlines_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "airline_code", "airline_en");

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
			$result_list = $this->Location_model->get_all_airlines($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control airline_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/airlines_edit".DEFAULT_EXT."?airline=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "airline_code" => $aRow["airline_code"], "airline_name" => $aRow["airline_en"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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


	public function airlines_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("airline_code", "Airline Code", "trim|required");
			$this->form_validation->set_rules("airline_en", "Airline Name", "trim|required");
			$this->form_validation->set_rules("airline_fa", "Airline Name", "trim|required");
			$response["title"] = "Add Airline";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$airline_en = ucwords($this->input->post("airline_en"));
				$airline_fa = $this->input->post("airline_fa");
				$airline_code = strtoupper($this->input->post("airline_code"));
				$insert_data = array("airline_code" => $airline_code, "airline_en" => $airline_en, "airline_en" => $airline_en);
				$is_exists = $this->Location_model->is_airline_exists($insert_data);
				if($is_exists === false)
				{
					$result = $this->Location_model->add_airline($insert_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Airline added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
						$response["msg"] = "Failed to add Airline.";
				}
				else
					$response["msg"] = "Airline detail is already exists.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Airline";
			$this->data["page_main_title"] = "Airline Management";
			$this->load->view("location/airline/add");
		}
	}

	public function airline_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("airline")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Airline Activate" : " - Airline Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Location_model->update_airline($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Airline deactivated." : "Airline activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}


	public function airlines_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("airline_id", "Airline", "trim|required");
			$this->form_validation->set_rules("airline_en", "Airline Name", "trim|required");
			$this->form_validation->set_rules("airline_fa", "Airline Name", "trim|required");
			$this->form_validation->set_rules("airline_code", "Airline Code", "trim|required");
			$this->form_validation->set_rules("airline_code_old", "Airline Name", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Airline";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("airline_id")));
				if(is_numeric($id) && $id > 0)
				{
					$airline_en = ucwords($this->input->post("airline_en"));
					$airline_fa = $this->input->post("airline_fa");
					$airline_code = strtoupper($this->input->post("airline_code"));
					$airline_code_old = strtoupper($this->input->post("airline_code_old"));
					$update_data = array("airline_code" => $airline_code, "airline_en" => $airline_en, "airline_fa" => $airline_fa);
					$excepts = array("airline_code" => $airline_code_old);
					$is_exists = $this->Location_model->is_airline_exists($update_data, $excepts);
					if($is_exists === false)
					{
						$result = $this->Location_model->update_airline($id, $update_data);
						if($result !== false)
						{
							$response["msg"] = "Airline updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
							$new_data = array();
							$new_data["airline"] = $airline_code;
							$response["new_data"] = $new_data;
						}
						else
						{
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Airline details name already exists.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Airline";
			$id = $this->encrypt->decode(base64_decode($this->input->get("airline")));
			if(is_numeric($id) && $id > 0)
			{
				$data["airline"] = $this->Location_model->get_airline($id);
				if($data["airline"] !== false)
				{
					$data["airline_id"] = $this->input->get("airline");
					$this->load->view("location/airline/edit", $data);
				}
				else
					redirect($this->data["controller"]."/airlines", "refresh");
			}
			else
				redirect($this->data["controller"]."/airlines", "refresh");
		}
		else
			redirect($this->data["controller"]."/airlines", "refresh");
	}

	//delete airline
	public function delete_airline()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("airline")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Airline";
				$result = $this->Location_model->delete_airline($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Airline deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}
}