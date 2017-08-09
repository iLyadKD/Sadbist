<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Privileges extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data["page_main_title"] = "Privileges Management";
		$this->data["page_title"] = "Privileges Management";
	}

	public function index()
	{
		$this->load->view("privileges/index");
	}

	public function privilege_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "controller", "", "privilege", "url");

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
			$result_list = $this->Privilege_model->get_all_privileges($sWhere, $sOrder, $sLimit);
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
				$icon_html = "<i class='icon-".$aRow["icon"]."'></i>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control privilege_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$privilege_visible = ($aRow["privilege_avail"] === "1") ? "checked='checked'" : "";
				$privilege_visible_html = "<div class='toggle-switch'><input type='checkbox' $privilege_visible id='$id' class='toggle-control privilege_visible_to_admin'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$is_avail_to_admin = $aRow["default"] === "1" ? ($aRow["privilege_avail"] === "0" ? "<span class='text-danger'><i class='icon-warning'></i>&nbsp; Access Denied</span>" : "<span class='text-success'><i class='icon-info'></i>&nbsp; Access Granted</span>") : $privilege_visible_html;
				$is_status_accessable = $aRow["default"] === "1" ? ($aRow["status"] === "0" ? "<span class='text-danger'><i class='icon-warning'></i>&nbsp; Inactive</span>" : "<span class='text-success'><i class='icon-info'></i>&nbsp; Active</span>") : $status_html;
				$actions = "<div class='pull-left'>";
				if($aRow["default"] !== "1" && $aRow["level"] === "0")
				{
					#$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/edit".DEFAULT_EXT."?privilege=".$id)."'><i class='icon-edit'></i></a>\n";
					$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_privilege' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				}
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "controller" => $aRow["controller"], "icon_html" => $icon_html, "name" => $aRow["privilege"], "url" => $aRow["url"], "menu_type" => ($aRow["parent"] === "0" ? "Main Menu" : "Sub Menu"), "order_by" => $aRow["order_by"], "sub_menu_order" => ($aRow["child_order"] === "0" ? "-------" : $aRow["child_order"]), "is_avail_to_admin" => $is_avail_to_admin, "status_html" => $is_status_accessable, "actions" => $actions, "id" => $id));
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

	public function add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Add Privilege";
			$this->form_validation->set_rules("controller_name", "Controller", "trim|required");
			$this->form_validation->set_rules("icon_name", "Icon Class", "trim|required");
			$this->form_validation->set_rules("privilege_name", "Privilege Name", "trim|required");
			$this->form_validation->set_rules("rel_url", "URL", "trim|required");
			$this->form_validation->set_rules("privilege_type", "Menu Type", "required");
			$this->form_validation->set_rules("menu_order", "Menu Order", "required");
			if($this->input->post("privilege_type") !== null && $this->input->post("privilege_type") !== false && $this->input->post("privilege_type") === "1")
			{
				$this->form_validation->set_rules("level", "Menu Level", "required");
				$this->form_validation->set_rules("submenu_order", "Submenu Order", "required");
				$this->form_validation->set_rules("parent_order", "Parent Order", "required");
			}
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$this->db->trans_begin();
				try
				{
					$privilege_type = is_numeric($this->input->post("privilege_type")) ? $this->input->post("privilege_type") : "0";
					$menu_order = is_numeric($this->input->post("menu_order")) ? $this->input->post("menu_order") : "1";
					$data = array("controller" => ",".strtolower($this->input->post("controller_name")).",",
								"icon" => $this->input->post("icon_name"),
								"privilege" => ucwords($this->input->post("privilege_name")),
								"url" => $this->input->post("rel_url"),
								"order_by" => $menu_order);
					if($privilege_type > 0)
					{
						$level = is_numeric($this->input->post("level")) ? $this->input->post("level") : "0";
						$child_order = is_numeric($this->input->post("child_order")) ? $this->input->post("child_order") : "1";
						$parent_order = is_numeric($this->input->post("parent_order")) ? $this->input->post("parent_order") : "0";
						$data["parent"] = "1";
						$data["level"] = $level;
						$data["child_order"] = $child_order;
						$data["parent_order"] = $parent_order;
						$result = "order_nonexist";
						$main_privilege = $this->Privilege_model->is_parent_exists($data["order_by"], ($data["level"] - 1), $data["parent_order"]);
						if($main_privilege !== false)
						{
							if($main_privilege->is_parent === "1")
							{
								$sibling = $this->Privilege_model->get_sibling_privilege($data["order_by"], $data["level"], $data["parent_order"], $data["child_order"]);
								$data["main_order"] = (int)$sibling->set_main_order;
							}
							else
								$data["main_order"] = (int)$main_privilege->main_order + 1;
							$result = $this->Privilege_model->add_subprivilege($data);
						}
						else
						{

							$this->db->trans_rollback();
							$response["msg"] = "Order by value is not exist to add submenu.";
							$response["msg_status"] = "info";
						}
					}
					else
					{
						$data["parent"] = "0";
						$data["child_order"] = "0";
						$data["parent_order"] = "0";
						$data["level"] = "0";
						$result = $this->Privilege_model->add_privilege($data);
					}
					if($result !== "order_nonexist" && $result !== false)
					{
						$this->db->trans_commit();
						$response["msg"] = "Privilege added successfully. Please refresh to see the changes.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					elseif($result === false)
					{
						$this->db->trans_rollback();
						$response["msg"] = "Failed to add Privilege.";
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
			$this->data["page_title"] = "Add Privilege";
			$this->load->view("privileges/add");
		}
	}

	public function privilege_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("privilege")));
			$op_type = $this->input->post("type");
			$status = $this->input->post("status");
			$type_arr = array("available", "status");
			if(is_numeric($id) && $id > 0 && in_array($op_type, $type_arr))
			{
				$column = $op_type === "available" ? "privilege_avail" : "status";
				$response["title"] = $this->data["page_main_title"].($op_type === "available" ? " - Update Access" : " - Update Status");
				$data = array($column => $status);
				$result = true;
				$result = $this->Privilege_model->update($id, $data);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = $status === "0" ? "info" : "success";
					$response["msg"] = $op_type === "available" ? "Privilege Status successfully updated." : "Status successfully updated. Please refresh to see the changes.";
				}
				else
					$response["msg"] = "Failed to update status.";
			}
		}
		echo json_encode($response);exit;
	}

	public function reorder()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Reorder Privileges";
			$from = (int)$this->input->post("o_from");
			$to = (int)$this->input->post("o_to");
			$o_length = (int)$this->input->post("o_order_length");
			$main_to = (int)$this->input->post("o_main_to");
			$main_from = (int)$this->input->post("o_main_from");
			if($from > 1 && $to > 1 && $main_from > 1 && $main_to > 1 && $o_length > 0)
			{
				$this->db->trans_begin();
				$result = $this->Privilege_model->reorder_privileges($from, $to, $o_length, $main_from, $main_to, $this->data["admin_id"]);
				if($result !== false)
				{
					$this->db->trans_commit();
					$response["status"] = "true";
				}
				else
					$this->db->trans_rollback();
			}
		}
		echo json_encode($response);exit;
	}

	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Privilege";
			$id = $this->encrypt->decode(base64_decode($this->input->post("privilege")));
			if(is_numeric($id) && $id > 0)
			{
				try
				{
					$this->db->trans_begin();
					$result = $this->Privilege_model->delete_privilege($id);
					if($result !== false)
					{
						$this->db->trans_commit();
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Privilege is deleted. Page will reload to see the changes.";
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg"] = "Failed to delete privilege.";
					}
				}
				catch(Exception $ex)
				{
					$this->db->trans_rollback();
					$response["msg"] = "Failed to delete privilege.";
				}
			}
		}
		echo json_encode($response);exit;
	}
}