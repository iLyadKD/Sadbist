<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Api_model");
		$this->data["page_main_title"] = "API Management";
		$this->data["page_title"] = "API Management";
	}

	public function index()
	{
		$this->load->view("api/index");
	}


	//load all pages
	public function api_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "service", "api_name");

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
			$result_list = $this->Api_model->get_apis($sWhere, $sOrder, $sLimit);
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
				$test_details = !empty($aRow["test_user"]) ? "Username : ".$aRow["test_user"] : "Username : ---";
				$test_details .= !empty($aRow["test_pwd"]) ? "<br/>Password : ".$this->encrypt->decode(base64_decode($aRow["test_pwd"])) : "<br/>Password : ---";
				$test_details .= !empty($aRow["test_extra"]) ? "<br/>Extra : ".$aRow["test_extra"] : "<br/>Extra : ---";
				$test_details .= "<br/>URL : ".$aRow["test_url"];
				$live_details = !empty($aRow["live_user"]) ? "Username : ".$aRow["live_user"] : "Username : ---";
				$live_details .= !empty($aRow["live_pwd"]) ? "<br/>Password : ".$this->encrypt->decode(base64_decode($aRow["live_pwd"])) : "<br/>Password : ---";
				$live_details .= !empty($aRow["live_extra"]) ? "<br/>Extra : ".$aRow["live_extra"] : "<br/>Extra : ---";
				$live_details .= "<br/>URL : ".$aRow["live_url"];
				$status_html = "";
				if ($aRow["status"] !== "0")
				{
					$status_html = "<a class='btn btn-success btn-xs has-tooltip' data-placement='top' title='Active'><i class='icon-ok'></i></a>";
				}
				else
				{
					$status_html = "<a class='btn btn-danger btn-xs has-tooltip' data-placement='top' title='De-active'><i class='icon-minus'></i></a>";
				}
				$status_html .= "<select data-api='".$id."' data-status='".$aRow["status"]."' class='api_status'>\n\t<option ".($aRow["status"] === "0" ? "selected" : "")." value='0'> Deactive </option>\n\t<option ".($aRow["status"] === "1" ? "selected" : "")." value='1'> Test </option>\n\t<option ".($aRow["status"] === "2" ? "selected" : "")." value='2'> Live </option>\n\t</select>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_api' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$new_data = array();
				$new_data["id"]  = $id;
				$new_data["sl_no"]  = $i++;
				$new_data["test_credentials"]  = $test_details;
				$new_data["live_credentials"]  = $live_details;
				$new_data["status_html"]  = $status_html;
				$new_data["actions"]  = $actions;
				$new_data["api_name"]  = $aRow["api_name"];
				$new_data["service"]  = $aRow["service_name"];
				$new_data["category"]  = isset($aRow["test_user"]) && isset($aRow["live_user"]) ? "3" : (isset($aRow["live_user"]) ? "2" : "1");
				$new_data["test_user"]  = isset($aRow["test_user"]) ? $aRow["test_user"] : "";
				$new_data["live_user"]  = isset($aRow["live_user"]) ? $aRow["live_user"] : "";
				$new_data["test_pwd"]  = !empty($aRow["test_pwd"]) ? $this->encrypt->decode(base64_decode($aRow["test_pwd"])) : "";
				$new_data["live_pwd"]  = !empty($aRow["live_pwd"]) ? $this->encrypt->decode(base64_decode($aRow["live_pwd"])) : "";
				$new_data["test_extra"]  = isset($aRow["test_extra"]) ? $aRow["test_extra"] : "";
				$new_data["live_extra"]  = isset($aRow["live_extra"]) ? $aRow["live_extra"] : "";
				$new_data["test_url"]  = $aRow["test_url"];
				$new_data["live_url"]  = $aRow["live_url"];
				$row[] = json_encode($new_data);
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

	public function add()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("service", "Service", "trim|required");
			$this->form_validation->set_rules("category", "Category", "trim|required");
			$this->form_validation->set_rules("api_name", "API Name", "trim|required");
			if($this->input->post("category") !== "2")
			{
				$this->form_validation->set_rules("test_user", "Test Username", "trim|required");
				$this->form_validation->set_rules("test_pass", "Test Password", "trim|required");
			}
			if($this->input->post("category") !== "1")
			{
				$this->form_validation->set_rules("live_user", "Live Username", "trim|required");
				$this->form_validation->set_rules("live_pass", "Live Password", "trim|required");
			}
			$this->form_validation->set_rules("api_url", "API URL", "trim|required");
			$response["title"] = "API Management - Add API";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$service = $this->input->post("service");
				$api_name = ucwords($this->input->post("api_name"));
				$api_code = $this->general->generate_slug($this->input->post("api_name"), false, "_");
				$category = $this->input->post("category");
				$api_url = explode(" ",$this->input->post("api_url"));
				$live_url = $api_url[0];
				$test_url = isset($api_url[1]) ? $api_url[1] : $live_url;
				$api_data = array(
					"service" => $service,
					"api_code" => $api_code,
					"api_name" => $api_name,
					"test_url" => $test_url,
					"live_url" => $live_url
				);
				if($category !== "2")
				{
					$test_user = $this->input->post("test_user");
					$test_pwd = $this->input->post("test_pass");
					$test_extra = $this->input->post("test_extra");
					$api_data["test_user"] = $test_user;
					$api_data["test_pwd"] = base64_encode($this->encrypt->encode($test_pwd));
					$api_data["test_extra"] = $test_extra;
					$api_data["status"] = "1";
				}
				if($category !== "1")
				{
					$live_user = $this->input->post("live_user");
					$live_pwd = $this->input->post("live_pass");
					$live_extra = $this->input->post("live_extra");
					$api_data["live_user"] = $live_user;
					$api_data["live_pwd"] = base64_encode($this->encrypt->encode($live_pwd));
					$api_data["live_extra"] = $live_extra;
					$api_data["status"] = "2";
				}
				$is_exists_type = $this->Api_model->is_service_exists("", $api_data["service"]);
				if($is_exists_type !== false)
				{
					$result = $this->Api_model->add($api_data);
					if($result !== false && $result > 0)
					{
						$id = base64_encode($this->encrypt->encode($result));
						$response["msg"] = "API added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						$test_details = !empty($api_data["test_user"]) ? "Username : ".$api_data["test_user"] : "Username : ---";
						$test_details .= !empty($api_data["test_pwd"]) ? "<br/>Password : ".$this->encrypt->decode(base64_decode($api_data["test_pwd"])) : "<br/>Password : ---";
						$test_details .= !empty($api_data["test_extra"]) ? "<br/>Extra : ".$api_data["test_extra"] : "<br/>Extra : ---";
						$test_details .= "<br/>URL : ".$api_data["test_url"];
						$live_details = !empty($api_data["live_user"]) ? "Username : ".$api_data["live_user"] : "Username : ---";
						$live_details .= !empty($api_data["live_pwd"]) ? "<br/>Password : ".$this->encrypt->decode(base64_decode($api_data["live_pwd"])) : "<br/>Password : ---";
						$live_details .= !empty($api_data["live_extra"]) ? "<br/>Extra : ".$api_data["live_extra"] : "<br/>Extra : ---";
						$live_details .= "<br/>URL : ".$api_data["live_url"];
						$status = "<a class='btn btn-success btn-xs has-tooltip' data-placement='top' title='Active'><i class='icon-ok'></i></a>";
						$status .= "<select data-api='".$id."' data-status='".$api_data["status"]."' class='api_status'>\n\t<option ".($api_data["status"] === "0" ? "selected" : "")." value='0'> Deactive </option>\n\t<option ".($api_data["status"] === "1" ? "selected" : "")." value='1'> Test </option>\n\t<option ".($api_data["status"] === "2" ? "selected" : "")." value='2'> Live </option>\n\t</select>";
						$actions = "<div class='pull-left'>";
						$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_api' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
						$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_api' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
						$actions .= "<br>";
						$actions .= "</div>";
						$new_data["new_row"] = "<tr><td></td><td>".$is_exists_type->service_name."</td><td>".$api_name."</td><td>".$test_details."</td><td>".$live_details."</td><td>".$status."</td><td>".$actions."</td></tr>";
						$new_data["api_id"]  = $id;
						$new_data["api_name"]  = $api_name;
						$new_data["service"]  = $is_exists_type->service_name;
						$new_data["category"]  = $category;
						$new_data["test_user"]  = !empty($api_data["test_user"]) ? $api_data["test_user"] : "";
						$new_data["live_user"]  = !empty($api_data["live_user"]) ? $api_data["live_user"] : "";;
						$new_data["test_pwd"]  = isset($api_data["test_pwd"]) ? $this->encrypt->decode(base64_decode($api_data["test_pwd"])) : "";;
						$new_data["live_pwd"]  = isset($api_data["live_pwd"]) ? $this->encrypt->decode(base64_decode($api_data["live_pwd"])) : "";;
						$new_data["test_url"]  = $api_data["test_url"];
						$new_data["live_url"]  = $api_data["live_url"];
						$new_data["test_extra"]  = isset($api_data["test_extra"]) ? $api_data["test_extra"] : "";
						$new_data["live_extra"]  = isset($api_data["live_extra"]) ? $api_data["live_extra"] : "";
						$response["new_data"] = $new_data;
					}
					else
						$response["msg"] = "Sorry, Failed to add API.";
				}
				else
					$response["msg"] = "Sorry, Invalid Service.";
			}
		}
		echo json_encode($response);exit;
	}

	public function update()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("api", "API", "trim|required");
			$this->form_validation->set_rules("category", "Category", "trim|required");
			$this->form_validation->set_rules("api_name", "API Name", "trim|required");
			if($this->input->post("category") !== "2")
			{
				$this->form_validation->set_rules("test_user", "Test Username", "trim|required");
				$this->form_validation->set_rules("test_pass", "Test Password", "trim|required");
			}
			if($this->input->post("category") !== "1")
			{
				$this->form_validation->set_rules("live_user", "Live Username", "trim|required");
				$this->form_validation->set_rules("live_pass", "Live Password", "trim|required");
			}
			$this->form_validation->set_rules("api_url", "API URL", "trim|required");
			$response["title"] = "API Management - Update API";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("api")));
				if(is_numeric($id) && $id > 0)
				{
					$api_name = ucwords($this->input->post("api_name"));
					$category = $this->input->post("category");
					$api_url = explode(" ",$this->input->post("api_url"));
					$live_url = $api_url[0];
					$test_url = isset($api_url[1]) ? $api_url[1] : $live_url;
					$api_data = array(
						"api_name" => $api_name,
						"test_url" => $test_url,
						"live_url" => $live_url
					);
					if($category !== "2")
					{
						$test_user = $this->input->post("test_user");
						$test_pwd = $this->input->post("test_pass");
						$test_extra = $this->input->post("test_extra");
						$api_data["test_user"] = $test_user;
						$api_data["test_pwd"] = base64_encode($this->encrypt->encode($test_pwd));
						$api_data["test_extra"] = $test_extra;
						$api_data["status"] = "1";
					}
					if($category !== "1")
					{
						$live_user = $this->input->post("live_user");
						$live_pwd = $this->input->post("live_pass");
						$live_extra = $this->input->post("live_extra");
						$api_data["live_user"] = $live_user;
						$api_data["live_pwd"] = base64_encode($this->encrypt->encode($live_pwd));
						$api_data["live_extra"] = $live_extra;
						$api_data["status"] = "2";
					}
					$result = $this->Api_model->update($id, $api_data);
					if($result !== false)
					{
						$response["msg"] = "API updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						$test_details = !empty($api_data["test_user"]) ? "Username : ".$api_data["test_user"] : "Username : ---";
						$test_details .= !empty($api_data["test_pwd"]) ? "<br/>Password : ".$this->encrypt->decode(base64_decode($api_data["test_pwd"])) : "<br/>Password : ---";
						$test_details .= !empty($api_data["test_extra"]) ? "<br/>Extra : ".$api_data["test_extra"] : "<br/>Extra : ---";
						$test_details .= "<br/>URL : ".$api_data["test_url"];
						$live_details = !empty($api_data["live_user"]) ? "Username : ".$api_data["live_user"] : "Username : ---";
						$live_details .= !empty($api_data["live_pwd"]) ? "<br/>Password : ".$this->encrypt->decode(base64_decode($api_data["live_pwd"])) : "<br/>Password : ---";
						$live_details .= !empty($api_data["live_extra"]) ? "<br/>Extra : ".$api_data["live_extra"] : "<br/>Extra : ---";
						$live_details .= "<br/>URL : ".$api_data["live_url"];
						$new_data["test_details"] = $test_details;
						$new_data["live_details"] = $live_details;
						$new_data["api_name"]  = $api_name;
						$new_data["category"]  = $category;
						$new_data["test_user"]  = isset($api_data["test_user"]) ? $api_data["test_user"] : "";
						$new_data["live_user"]  = isset($api_data["live_user"]) ? $api_data["live_user"] : "";;
						$new_data["test_pwd"]  = !empty($api_data["test_pwd"]) ? $this->encrypt->decode(base64_decode($api_data["test_pwd"])) : "";
						$new_data["live_pwd"]  = !empty($api_data["live_pwd"]) ? $this->encrypt->decode(base64_decode($api_data["live_pwd"])) : "";
						$new_data["test_extra"]  = isset($api_data["test_extra"]) ? $api_data["test_extra"] : "";
						$new_data["live_extra"]  = isset($api_data["live_extra"]) ? $api_data["live_extra"] : "";
						$new_data["test_url"]  = $api_data["test_url"];
						$new_data["live_url"]  = $api_data["live_url"];
						$response["new_data"] = $new_data;
					}
					else
						$response["msg"] = "Sorry, Failed to update API.";
				}
			}
		}
		echo json_encode($response);exit;
	}

	public function update_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("api")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - API Activate" : " - API Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Api_model->update($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "API deactivated successfully." : "API activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update API status.";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("api")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete API";
				$result = $this->Api_model->delete($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "API deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}


	public function services()
	{
		$this->data["page_title"] = "Services";
		$this->load->view("api/service");
	}

	public function services_html()
	{
		$services = $this->Api_model->get_services();
		$html = "";
		if($services !== false)
			foreach ($services as $service)
			{
				$id = base64_encode($this->encrypt->encode($service->id));
				$html .= '<li class="item">
							<label class="check pull-left todo">
								<span>'.$service->service_name.'</span>
							</label>
						</li>';
			}
		if($html === "")
			$html = "<li class='item no_services_data'>No Services are available. Please add some Services.</li>";
		echo $html;exit;
	}

	public function add_service()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("service", "Service", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Service";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$service = ucwords($this->input->post("service"));
				$data = array("service_name" => $service);
				$is_exists = $this->Api_model->is_service_exists($data);
				if($is_exists === false)
				{
					$result = $this->Api_model->add_service($data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Service added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$id = base64_encode($this->encrypt->encode($result));
						$response["data"] = '<li class="item">
												<label class="check pull-left todo">
													<span>'.$service.'</span>
												</label>
											</li>';
					}
					else
						$response["msg"] = "Failed to add Service.";
				}
				else
					$response["msg"] = "\"".$service."\" already exists.";
			}
		}
		echo json_encode($response);exit;
	}

	//delete Service
	public function delete_service()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("service")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Service";
				$result = $this->Api_model->delete_service($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Service deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}
}