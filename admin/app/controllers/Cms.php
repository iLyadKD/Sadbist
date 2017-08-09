<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class CMS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Cms_model");
		$this->data["page_main_title"] = "Static Page Management";
		$this->data["page_title"] = "Static Page Management";
	}

	public function index()
	{
		$this->load->view("cms/pages/index");
	}

	// get Page types
	public function get_page_types()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$pages = $this->Cms_model->get_page_types($id, $search, $page);
		echo json_encode($pages);exit;
	}

	public function page_types()
	{
		$this->data["page_title"] = "Static Page Types";
		$this->load->view('cms/pages/types');
	}

	public function page_types_html()
	{
		$page_types = $this->Cms_model->get_all_page_types();
		$html = "";
		if($page_types !== false)
			foreach ($page_types as $page_type)
			{
				$id = base64_encode($this->encrypt->encode($page_type->id));
				$html .= '<li class="item">
							<label class="check pull-left todo">
								<span>'.$page_type->display.'</span>
							</label>
						</li>';
			}
		if($html === "")
			$html = "<li class='item no_spt_data'>No Static Page Types are available. Please add some types.</li>";
		echo $html;exit;
	}

	public function add_sp_type()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("page_type", "Page Type", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Page Type";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$page_type = ucwords($this->input->post("page_type"));
				$slug = $this->general->generate_slug($page_type, false, "_");
				$data = array("name" => $slug, "display" => $page_type);
				$is_exists = $this->Cms_model->is_page_type_exists($data);
				if($is_exists === false)
				{
					$result = $this->Cms_model->add_page_type($data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Page type added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$id = base64_encode($this->encrypt->encode($result));
						$response["data"] = '<li class="item">
												<label class="check pull-left todo">
													<span>'.$page_type.'</span>
												</label>
												<div class="actions pull-right">
													<a class="btn btn-link has-tooltip delete_sp_type" style="color:red;" data-placement="top" hyperlink="'.$id.'" hypername="'.$page_type.'" title="" data-original-title="Remove">
														<i class="icon-remove"></i>
													</a>
												</div>
											</li>';
					}
					else
						$response["msg"] = "Failed to add page type.";
				}
				else
					$response["msg"] = "\"".$page_type."\" already exists.";
			}
		}
		echo json_encode($response);exit;
	}

	//delete subject
	public function delete_page_type()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("sp_type")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Page Type";
				$result = $this->Cms_model->delete_page_type($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Static page type deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function pages()
	{
		$this->load->view("cms/pages/index");
	}

	public function get_pages()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title_en");

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
			$result_list = $this->Cms_model->get_all_pages($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control static_page_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/pages_edit".DEFAULT_EXT."?page=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$url_html = "<a href='".front_url($aRow["slug"])."' target='_blank'>".$aRow["slug"]."</a>";
				$row[] = json_encode(array("sl_no" => $i++, "title" =>$aRow["title_en"], "url_html" => $url_html, "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	// Add Static Page
	public function pages_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("l_type", "Page Type", "trim|required");
			$this->form_validation->set_rules("title_en", "Page English Title", "trim|required");
			$this->form_validation->set_rules("title_fa", "Page Farsi Title", "trim|required");
			$this->form_validation->set_rules("content_en", "English Content", "trim|required");
			$this->form_validation->set_rules("content_fa", "Farsi Content", "trim|required");
			$response["title"] = "Add Static Page";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$slug = $this->general->generate_slug($this->input->post("title_en"));
				while($slug !== STATIC_CONTACT_SLUG && $this->Cms_model->is_slug_exists($slug) !== false)
					$slug = $this->general->generate_slug($slug, true);
				
				$title_en = ucfirst($this->input->post("title_en"));
				$title_fa = ucfirst($this->input->post("title_fa"));
				$content_en = $this->input->post("content_en");
				$content_fa = $this->input->post("content_fa");
				$l_type = is_numeric($this->input->post("l_type")) ? $this->input->post("l_type") : "";

				$page_data = array(
					"title_en" => $title_en,
					"title_fa" => $title_fa,
					"content_en" => $content_en,
					"content_fa" => $content_fa,
					"slug" => $slug,
					"link_type" => $l_type
				);
				$result = $this->Cms_model->new_page($page_data);
				if($result !== false && $result > 0)
				{
					$response["msg"] = "Page added successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
				}
				else
					$response["msg"] = "Sorry, Failed to add static page.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Static Page";
			$this->load->view("cms/pages/add");
		}
	}

	// Update Static Page
	public function pages_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("l_type", "Page Type", "trim|required");
			$this->form_validation->set_rules("static_page", "Page Id", "trim|required");
			$this->form_validation->set_rules("current_slug", "URL", "trim|required");
			$this->form_validation->set_rules("title_en", "Page English Title", "trim|required");
			$this->form_validation->set_rules("title_fa", "Page Farsi Title", "trim|required");
			$this->form_validation->set_rules("content_en", "English Content", "trim|required");
			$this->form_validation->set_rules("content_fa", "Farsi Content", "trim|required");
			$response["title"] = "Update Static Page";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("static_page")));
				if(is_numeric($id) && $id > 0)
				{
					$cur_slug = $this->input->post("current_slug");
					$slug = $this->general->generate_slug($this->input->post("title_en"));
					while($slug !== STATIC_CONTACT_SLUG && $slug !== $cur_slug && $this->Cms_model->is_slug_exists($slug, $cur_slug) !== false)
						$slug = $this->general->generate_slug($slug, true);
					
					$title_en = ucfirst($this->input->post("title_en"));
					$title_fa = $this->input->post("title_fa");
					$content_en = $this->input->post("content_en");
					$content_fa = $this->input->post("content_fa");
					$l_type = is_numeric($this->input->post("l_type")) ? $this->input->post("l_type") : "";

					$page_data = array(
						"title_en" => $title_en,
						"title_fa" => $title_fa,
						"content_en" => $content_en,
						"content_fa" => $content_fa,
						"slug" => $slug,
						"link_type" => $l_type
					);
					$result = $this->Cms_model->update_page($id, $page_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Page updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$response["slug"] = $slug;
					}
					else
					{
						$response["msg"] = "Sorry, No changes.";
						$response["msg_status"] = "info";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("page")));
			if(is_numeric($id) && $id > 0)
			{
				$this->data["page_title"] = "Update Static Page";
				$data["page_detail"] = $this->Cms_model->get_page($id);
				if($data["page_detail"] !== false)
				{
					$data["static_page"] = $this->input->get("page");
					$this->load->view("cms/pages/edit", $data);
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

	/*change static page status*/
	public function static_page_status()
	{
		$id = $this->encrypt->decode(base64_decode($this->input->post("static_page")));
		$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if(is_numeric($id) && $id > 0)
		{
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Static Page Activate" : " - Static Page Deactivate");
			$result = $this->Cms_model->update_page($id, array("status" => $status));
			if($result !== false)
			{
				$response["status"] = "true";
				$response["msg"] = $status === "0" ? "Static page deactivated successfully." : "Static page activated successfully.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
			}
			else
				$response["msg"] = "Failed to update Static page status.";
		}
		echo json_encode($response);exit;
	}

	/*delete static page*/
	public function delete_page()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Static Page";
			$id = $this->encrypt->decode(base64_decode($this->input->post("static_page")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Cms_model->delete_page($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Static page deleted successfully.";
				}
				else
					$response["msg"] = "Sorry Failed to delete static page.";
			}
		}
		echo json_encode($response);exit;
	}

	/* contact page */
	public function contact()
	{
		$this->data["page_title"] = "Contact Details";
		$data["contact_locations"] = $this->Cms_model->get_all_contact_locations()["result"];
		$this->load->view("cms/contact/index", $data);
	}

	public function get_contact_details()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title_en", "address_en", "contact", "email", "website");

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
			$result_list = $this->Cms_model->get_all_contact_details($sWhere, $sOrder, $sLimit);
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
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control contact_detail_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions .= "<a class='btn btn-info btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/contact_edit".DEFAULT_EXT."?cnct_dtl=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "title" => $aRow["title_en"], "address" => $aRow["address_en"], "contact" => $aRow["contact"], "email" => $aRow["email"], "website" => $aRow["website"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	// Add Contact details
	public function contact_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("location_en", "Location Name", "trim|required");
			$this->form_validation->set_rules("location_fa", "Location Name", "trim|required");
			$this->form_validation->set_rules("address_fa", "Address", "trim|required");
			$this->form_validation->set_rules("contact", "Contact Number", "trim|required");
			$this->form_validation->set_rules("email", "EmailID", "trim|required");
			$this->form_validation->set_rules("website", "Website", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Contact Details";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$title_en = ucfirst($this->input->post("location_en"));
				$title_fa = $this->input->post("location_en");
				$address_en = $this->input->post("address_en");
				$address_fa = $this->input->post("address_fa");
				$contact = $this->input->post("contact");
				$email = strtolower($this->input->post("email"));
				$website = strtolower($this->input->post("website"));

				$insert_data = array(
					"title_en" => $title_en,
					"title_fa" => $title_fa,
					"address_en" => $address_en,
					"address_fa" => $address_fa,
					"contact" => $contact,
					"email" => $email,
					"website" => $website
				);
				$result = $this->Cms_model->add_contact_detail($insert_data);
				if($result !== false && $result > 0)
				{
					$response["msg"] = "Contact Details added successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
				}
				else
					$response["msg"] = "Sorry, Failed to add contact details.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_main_title"] = "Contact Us";
			$this->data["page_title"] = "Add Contact Details";
			$this->load->view("cms/contact/add");
		}
	}

	public function contact_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("cnct_dtl", "Contact ID", "trim|required");
			$this->form_validation->set_rules("location_en", "Location Name", "trim|required");
			$this->form_validation->set_rules("location_fa", "Location Name", "trim|required");
			$this->form_validation->set_rules("address_en", "Address", "trim|required");
			$this->form_validation->set_rules("address_fa", "Address", "trim|required");
			$this->form_validation->set_rules("contact", "Contact Number", "trim|required");
			$this->form_validation->set_rules("email", "EmailID", "trim|required");
			$this->form_validation->set_rules("website", "Website", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Contact Details";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("cnct_dtl")));
				if(is_numeric($id) && $id > 0)
				{
					$title_en = ucfirst($this->input->post("location_en"));
					$title_fa = $this->input->post("location_fa");
					$address_en = $this->input->post("address_en");
					$address_fa = $this->input->post("address_fa");
					$contact = $this->input->post("contact");
					$email = strtolower($this->input->post("email"));
					$website = strtolower($this->input->post("website"));

					$upd_data = array(
						"title_en" => $title_en,
						"title_fa" => $title_fa,
						"address_en" => $address_en,
						"address_fa" => $address_fa,
						"contact" => $contact,
						"email" => $email,
						"website" => $website
					);
					$result = $this->Cms_model->update_contact_detail($id, $upd_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Contact Details updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
					}
					else
					{
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("cnct_dtl")));
			if(is_numeric($id) && $id > 0)
			{
				$this->data["page_title"] = "Update Contact Detail";
				$this->data["page_main_title"] = "Contact Us";
				$data["contact"] = $this->Cms_model->get_contact_detail($id);
				$data["contact_id"] = $this->input->get("cnct_dtl");
				$this->load->view('cms/contact/edit', $data);
			}
			else
				redirect($this->data["controller"], "refresh");
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	/*set default contact detail*/
	public function set_default_contact_detail()
	{
		$id = $this->encrypt->decode(base64_decode($this->input->post("cnct_dtl")));
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		$response["title"] = $this->data["page_main_title"]." - Set Default Contact Details";
		if(is_numeric($id) && $id > 0)
		{
			$result = $this->Cms_model->set_default_contact_detail($id);
			if($result !== false)
			{
				$response["status"] = "true";
				$response["msg_status"] = "success";
				$response["msg"] = "Default Contact details changed successfully.";
			}
			else
				$response["msg"] = "Failed to set default Contact details.";
		}
		echo json_encode($response);exit;
	}

	/*change contact detail status*/
	public function contact_detail_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
		$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Contact Details Activate" : " - Contact Details Deactivate");
		$id = $this->encrypt->decode(base64_decode($this->input->post("cnct_dtl")));
		if(is_numeric($id) && $id > 0)
		{
			$result = $this->Cms_model->update_contact_detail($id, array("status" => $status));
			if($result !== false)
			{
				$response["status"] = "true";
				$response["msg"] = $status === "0" ? "Contact details deactivated successfully." : "Contact details activated successfully.";
				$response["msg_status"] = $status === "0" ? "info" : "success";
			}
			else
			{
				$response["msg_status"] = "info";
				$response["msg"] = "Failed to update Contact details status.";
			}
		}
		echo json_encode($response);exit;
	}

	/*delete contact detail*/
	public function delete_contact_detail()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Contact Details";
			$id = $this->encrypt->decode(base64_decode($this->input->post("cnct_dtl")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Cms_model->delete_contact_detail($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Contact detail deleted successfully.";
				}
				else
					$response["msg"] = "Sorry, Failed to delete contact detail.";
			}
		}
		echo json_encode($response);exit;
	}

	public function get_contact_locations()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "title_en");

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
			$result_list = $this->Cms_model->get_all_contact_locations($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control contact_location_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_contact_location' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "title" => $aRow["title_en"], "status_html" => $status_html, "actions" => $actions, "id" => $id, "marker" => $aRow["id"]));
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

	// Add Contact Location
	public function add_contact_location()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("location_name", "Location Name", "trim|required");
			$this->form_validation->set_rules("latitude", "Latitude", "trim|required");
			$this->form_validation->set_rules("longitude", "Longitude", "trim|required");
			if (empty($_FILES['location_image']['name']))
				$this->form_validation->set_rules("location_image", "Location Image", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Location";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$title_en = ucwords($this->input->post("location_name"));
				$image = "";
				$marker_image = null;
				$latitude = (float)$this->input->post("latitude");
				$longitude = (float)$this->input->post("longitude");

				if(isset($_FILES)&& $_FILES["location_image"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."contact_locations/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("location_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$image = "contact_locations/".$simg["file_name"];
						//resize image to fixed width
						$config['image_library'] = 'gd2';
						$config['source_image'] = REL_IMAGE_UPLOAD_PATH.$image;
						$config['overwrite'] = true;
						$config['maintain_ratio'] = true;
						$config['width'] = 100;
						$config['height'] = 100;
						$this->load->library('image_lib',$config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}
					else
					{
						$response["msg"] = "Sorry, Failed to add contact location image.";
						echo json_encode($response);exit;
					}
				}
				else
				{
					$response["msg"] = "Sorry, Failed to add contact location.";
					echo json_encode($response);exit;
				}

				if(isset($_FILES)&& $_FILES["location_icon"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."contact_markers/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("location_icon");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$marker_image = "contact_markers/".$simg["file_name"];
						//resize image to fixed width
						$config['image_library'] = 'gd2';
						$config['source_image'] = REL_IMAGE_UPLOAD_PATH.$marker_image;
						$config['overwrite'] = true;
						$config['maintain_ratio'] = true;
						$config['width'] = 32;
						$config['height'] = 32;
						$this->load->library('image_lib',$config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}
				}

				$insert_data = array(
					"title_en" => $title_en,
					"image" => $image,
					"marker_image" => $marker_image,
					"latitude" => $latitude,
					"longitude" => $longitude
				);
				$result = $this->Cms_model->add_contact_location($insert_data);
				if($result !== false && $result > 0)
				{
					$id = base64_encode($this->encrypt->encode($result));
					$response["msg"] = "Contact location added successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
					$new_data = array();
					$new_data["title"] = $title_en;
					$new_data["marker"] = $result;
					$new_data["id"] = $id;
					$new_data["marker_icon"] = $marker_image;
					$new_data["latitude"] = $latitude;
					$new_data["longitude"] = $longitude;
					$status_html = "<div class='toggle-switch'><input type='checkbox' checked='true' id='$id' class='toggle-control contact_location_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
					$actions = "<div class='pull-left'>";
					$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_contact_location' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
					$actions .= "<br>";
					$actions .= "</div>";
					$new_row = "<tr><td></td><td>".$title_en."</td><td>".$status_html."</td><td>".$actions."</td></tr>";
					$response["new_row"] = $new_row;
					$new_data["display_content"] = "<div class='contact_locations_content'><div class='contact_locations_header'>".$title_en."</div><div class='contact_locations_body'>".$image."</div></div>";
					$response["new_data"] = $new_data;
				}
				else
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
						unlink(REL_IMAGE_UPLOAD_PATH.$image);
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$marker_image) && file_exists(REL_IMAGE_UPLOAD_PATH.$marker_image))
						unlink(REL_IMAGE_UPLOAD_PATH.$marker_image);
					$response["msg"] = "Sorry, Failed to add contact location.";
				}
			}
		}
		echo json_encode($response);exit;
	}

	/*change contact location status*/
	public function contact_location_status()
	{
		$id = $this->encrypt->decode(base64_decode($this->input->post("cnct_loc")));
		$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if(is_numeric($id) && $id > 0)
		{
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Contact Location Activate" : " - Contact Location Deactivate");

			$result = $this->Cms_model->update_contact_location($id, array("status" => $status));
			if($result !== false)
			{
				$response["status"] = "true";
				$response["msg"] = $status === "0" ? "Location deactivated successfully." : "Location activated successfully.";
				$response["msg_status"] = $status === "0" ? "info" : "success";
			}
			else
			{
				$response["msg_status"] = "info";
				$response["msg"] = "Failed to update Location status.";
			}
		}
		echo json_encode($response);exit;
	}

	/*delete contact_location*/
	public function delete_contact_location()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Location";
			$id = $this->encrypt->decode(base64_decode($this->input->post("cnct_loc")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Cms_model->delete_contact_location($id);
				$image = $result->image;
				$marker_image = $result->marker_image;
				if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
					unlink(REL_IMAGE_UPLOAD_PATH.$image);
				if(!is_dir(REL_IMAGE_UPLOAD_PATH.$marker_image) && file_exists(REL_IMAGE_UPLOAD_PATH.$marker_image))
					unlink(REL_IMAGE_UPLOAD_PATH.$marker_image);
				$response["status"] = "true";
				$response["msg_status"] = "success";
				$response["msg"] = "Location deleted successfully.";
			}
		}
		echo json_encode($response);exit;
	}


	public function about_us()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("static_page", "Page Id", "trim|required");
			$this->form_validation->set_rules("content_en", "English Content", "trim|required");
			$this->form_validation->set_rules("content_fa", "Farsi Content", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update About Us";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("static_page")));
				if(is_numeric($id) && $id > 0)
				{
					$content_en = $this->input->post("content_en");
					$content_fa = $this->input->post("content_fa");

					$page_data = array(
						"content_en" => $content_en,
						"content_fa" => $content_fa
					);
					$result = $this->Cms_model->update_page($id, $page_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "About-Us updated successfully.";
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
		else
		{
			$this->data["page_title"] = "About-Us";
			$data["page_detail"] = $this->Cms_model->get_page_by_slug("about-us");
			if(empty($data["page_detail"]) === true)
				redirect($this->data["controller"], "refresh");
			$this->load->view("cms/pages/about_us", $data);
		}
	}

	public function social_network()
	{
		$this->data["page_title"] = "Social Network";
		$this->load->view("cms/social_network/index");
	}

	public function get_social_network()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "name", "icon", "url");

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
			$result_list = $this->Cms_model->get_all_social_network($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control social_media_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_social_network' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_social_network' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$media_icon = "<i class='icon icon-2x icon-".$aRow["icon"]."'></i>";
				$media_url = "<a href='".$aRow["url"]."' target='_blank'>".$aRow["url"]."</a>";
				$row[] = json_encode(array("sl_no" => $i++, "name" => $aRow["name"], "icon" => $aRow["icon"], "url" => $aRow["url"], "media_icon" => $media_icon, "media_url" => $media_url, "status" => $status_html, "actions" => $actions, "id" => $id));
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

	// Add Social Network
	public function add_social_network()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("social_network_name", "Social Network Name", "trim|required");
			$this->form_validation->set_rules("icon", "Social Network Icon", "trim|required");
			$this->form_validation->set_rules("url", "Social Network URL", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Social Network";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$sn_name = ucwords($this->input->post("social_network_name"));
				$icon = strtolower($this->input->post("icon"));
				$url = strtolower($this->input->post("url"));
				$sn_data = array(
					"name" => $sn_name,
					"icon" => $icon,
					"url" => $url
				);
				$result = $this->Cms_model->add_social_network($sn_data);
				if($result !== false && $result > 0)
				{
					$id = base64_encode($this->encrypt->encode($result));
					$response["msg"] = "Social Network added successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
					$new_data = array();
					$new_data["id"] = $id;
					$new_data["icon"] = $icon;
					$new_data["name"] = $sn_name;
					$new_data["url"] = $url;
					$response["new_data"] = $new_data;
					$status_html = "<div class='toggle-switch'><input type='checkbox' checked='true' id='$id' class='toggle-control social_media_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
					$actions = "<div class='pull-left'>";
					$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_social_network' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
					$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_social_network' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
					$actions .= "<br>";
					$actions .= "</div>";
					$icon_html = "<i class='icon icon-2x icon-".$icon."'></i>";
					$url_html = "<a href='".$url."' target='_blank'>".$url."</a>";
					$new_row = "<tr><td></td><td>".$sn_name."</td><td>".$icon_html."</td><td>".$url_html."</td><td>".$status_html."</td><td>".$actions."</td></tr>";
					$response["new_row"] = $new_row;
				}
				else
					$response["msg"] = "Sorry, Failed to add Social Network.";
			}
		}
		echo json_encode($response);exit;
	}

	public function update_social_network()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Update Social Network";
			$this->form_validation->set_rules("social_network_name", "Social Network Name", "trim|required");
			$this->form_validation->set_rules("social_network", "Social Network", "trim|required");
			$this->form_validation->set_rules("icon", "Social Network Icon", "trim|required");
			$this->form_validation->set_rules("url", "Social Network URL", "trim|required");
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("social_network")));
				if(is_numeric($id) && $id > 0)
				{
					$sn_name = ucfirst($this->input->post("social_network_name"));
					$icon = strtolower($this->input->post("icon"));
					$url = strtolower($this->input->post("url"));
					$sn_data = array(
						"name" => $sn_name,
						"icon" => $icon,
						"url" => $url
					);
					$result = $this->Cms_model->update_social_network($id, $sn_data);
					if($result !== false)
					{
						$response["msg"] = "Social Network updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						$new_data["icon"] = $icon;
						$new_data["name"] = $sn_name;
						$new_data["url"] = $url;
						$icon_html = "<i class='icon icon-2x icon-".$icon."'></i>";
						$url_html = "<a href='".$url."' target='_blank'>".$url."</a>";
						$new_data["icon_html"] = $url;
						$new_data["url_html"] = $url;
						$response["new_data"] = $new_data;
					}
					else
						$response["msg"] = "Sorry, Failed to update Social Network.";
				}
			}
		}
		echo json_encode($response);exit;
	}

	public function social_network_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("social_network")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "0" ? " - Deactivate Social Network" : " - Activate Social Network");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Cms_model->update_social_network($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Social Network deactivated." : "Social Network activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete_social_network()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Social Network";
			$id = $this->encrypt->decode(base64_decode($this->input->post("social_network")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Cms_model->delete_social_network($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Social Network deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function clientele()
	{
		$this->data["page_title"] = "Clientele";
		$this->load->view("cms/clientele/index");
	}

	public function get_clientele()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "name");

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
			$result_list = $this->Cms_model->get_all_clientele($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control clientele_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_clientele' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_clientele' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$image_html = "<img width='50px' height='50px' src='".upload_url($aRow["image"])."' alt=''>";
				$row[] = json_encode(array("sl_no" => $i++, "name" =>$aRow["name"], "image" => $aRow["image"], "image_html" => $image_html, "status" => $status_html, "actions" => $actions, "id" => $id));
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

	// Add Clientele
	public function add_clientele()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("clientele_name", "Clientele Name", "trim|required");
			if (empty($_FILES["clientele_image"]["name"]))
				$this->form_validation->set_rules("clientele_image", "Clientele Image", "required");
			$response["title"] = $this->data["page_main_title"]." - Add Clientele";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$image_path = false;
				if(isset($_FILES)&& $_FILES["clientele_image"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."clientele/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("clientele_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$image_path = "clientele/".$simg["file_name"];
						//resize image to fixed width
						$config['image_library'] = 'gd2';
						$config['source_image'] = REL_IMAGE_UPLOAD_PATH.$image_path;
						$config['overwrite'] = true;
						$config['maintain_ratio'] = true;
						$config['width'] = 100;
						$config['height'] = 100;
						$this->load->library('image_lib',$config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}
				}
				if($image_path !== false)
				{
					$ce_name = ucfirst($this->input->post("clientele_name"));
					$ce_data = array(
						"name" => ucfirst($ce_name),
						"image" => $image_path
					);
					$result = $this->Cms_model->add_clientele($ce_data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Clientele added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$id = base64_encode($this->encrypt->encode($result));
						$new_data = array();
						$new_data["id"] = $id;
						$new_data["name"] = $ce_name;
						$new_data["image"] = $image_path;
						$status_html = "<div class='toggle-switch'><input type='checkbox' checked='true' id='$id' class='toggle-control clientele_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
						$actions = "<div class='pull-left'>";
						$actions .= "<a class='btn btn-primary btn-xs has-tooltip edit_clientele' data-placement='top' title='Edit' href='javascript:void(0);'><i class='icon-edit'></i></a>\n";
						$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_clientele' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
						$actions .= "<br>";
						$actions .= "</div>";
						$image_html = "<img width='50px' height='50px' src='".upload_url($image_path)."' alt=''>";
						$new_row = "<tr><td></td><td>".$ce_name."</td><td>".$image_html."</td><td>".$status_html."</td><td>".$actions."</td></tr>";
						$response["new_row"] = $new_row;
						$response["new_data"] = $new_data;
					}
					else
					{
						if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$image_path))
							unlink(REL_IMAGE_UPLOAD_PATH.$image_path);
						$response["msg"] = "Sorry, Failed to add Clientele.";
					}
				}
				else
					$response["msg"] =  "Sorry, Invalid Image or File type not mentioned";
			}
		}
		echo json_encode($response);exit;
	}

	public function update_clientele()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("clientele_name", "Clientele Name", "trim|required");
			$this->form_validation->set_rules("clientele", "Clientele", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Clientele";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("clientele")));
				$ce_img = $this->input->post("image");
				if(is_numeric($id) && $id > 0)
				{
					$image_path = false;
					if(isset($_FILES)&& $_FILES["clientele_image"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."clientele/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("clientele_image");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$image_path = "clientele/".$simg["file_name"];
						}
					}
					$ce_name = ucfirst($this->input->post("clientele_name"));
					$ce_data = array(
						"name" => ucfirst($ce_name)
					);
					if($image_path !== false)
						$ce_data["image"] = $image_path;
					$result = $this->Cms_model->update_clientele($id, $ce_data);
					if($result !== false)
					{
						if($image_path !== false && !is_dir(REL_IMAGE_UPLOAD_PATH.$ce_img) && file_exists(REL_IMAGE_UPLOAD_PATH.$ce_img))
							unlink(REL_IMAGE_UPLOAD_PATH.$ce_img);
						$response["msg"] = "Clientele updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						$new_data["name"] = $ce_name;
						$new_data["image"] = $image_path !== false ? $image_path : $ce_img;
						$new_data["image_html"] = $image_path !== false ? $image_path : $ce_img;
						$image_html = "<img width='50px' height='50px' src='".upload_url($new_data["image_html"])."' alt=''>";
						$new_data["image_html"] = $image_html;
						$response["new_data"] = $new_data;
					}
					else
					{
						if($image_path !== false && !is_dir(REL_IMAGE_UPLOAD_PATH.$image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$image_path))
							unlink(REL_IMAGE_UPLOAD_PATH.$image_path);
						$response["msg"] = "No changes are made.";
						$response["msg_status"] = "info";
					}
				}
			}
		}
		echo json_encode($response);exit;
	}

	public function clientele_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("clientele")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "0" ? " - Deactivate Clientele" : " - Activate Clientele");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Cms_model->update_clientele($id, array("status" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Clientele deactivated." : "Clientele activated.";
					$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function delete_clientele()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Clientele";
			$id = $this->encrypt->decode(base64_decode($this->input->post("clientele")));
			$ce_img = $this->input->post("image");
			if(is_numeric($id) && $id > 0 && $ce_img !== false && $ce_img !== null)
			{
				$result = $this->Cms_model->delete_clientele($id);
				if($result !== false)
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$ce_img) && file_exists(REL_IMAGE_UPLOAD_PATH.$ce_img))
						unlink(REL_IMAGE_UPLOAD_PATH.$ce_img);
					$response["status"] = "true";
					$response["msg"] = "Clientele deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}
}