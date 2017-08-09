<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Support extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Support_model");
		$this->data["page_main_title"] = "Support Ticket Management";
		$this->data["page_title"] = "Support Ticket Management";
	}

	public function index()
	{
		$this->load->view('support/index');
	}

	public function subjects()
	{
		$this->data["page_title"] = "Support Ticket Subjects";
		$this->load->view('support/subjects');
	}

	public function subjects_html()
	{
		$subjects = $this->Support_model->subjects_list();
		$html = "";
		if($subjects !== false)
			foreach ($subjects as $subject)
			{
				$id = base64_encode($this->encrypt->encode($subject->id));
				$html .= '<li class="item">
							<label class="check pull-left todo">
								<span>'.$subject->subject.'</span>
							</label>
							<div class="actions pull-right">
								<a class="btn btn-link has-tooltip delete_st_subject" style="color:red;" data-placement="top" hyperlink="'.$id.'" hypername="'.$subject->subject.'" title="" data-original-title="Remove">
									<i class="icon-remove"></i>
								</a>
							</div>
						</li>';
			}
		if($html === "")
			$html = "<li class='item no_sts_data'>No support ticket subjects are available. Please add some subjects.</li>";
		echo $html;exit;
	}

	public function add_subject()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("subject", "Subject", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Subject";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$subject = ucfirst($this->input->post("subject"));
				$data = array("subject" => $subject);
				$is_exists = $this->Support_model->is_subject_exists($data);
				if($is_exists === false)
				{
					$result = $this->Support_model->add_subject($data);
					if($result !== false && $result > 0)
					{
						$response["msg"] = "Subject added successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$id = base64_encode($this->encrypt->encode($result));
						$response["data"] = '<li class="item">
												<label class="check pull-left todo">
													<span>'.$subject.'</span>
												</label>
												<div class="actions pull-right">
													<a class="btn btn-link has-tooltip delete_st_subject" style="color:red;" data-placement="top" hyperlink="'.$id.'" hypername="'.$subject.'" title="" data-original-title="Remove">
														<i class="icon-remove"></i>
													</a>
												</div>
											</li>';
					}
					else
						$response["msg"] = "Failed to add subject.";
				}
				else
					$response["msg"] = "\"".$subject."\" already exists.";
			}
		}
		echo json_encode($response);exit;
	}

	//delete subject
	public function delete_subject()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("st_subject")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Delete Subject";
				$result = $this->Support_model->delete_subject($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Subject deleted successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	public function tickets()
	{
		$this->load->view('support/index');
	}

	public function tickets_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$usertypes = array(SUPER_ADMIN_USER => "Administrator", ADMIN_USER => "Admin", B2B_USER => "B2B User", B2C_USER => "B2C User");
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Add Support Ticket";
			$this->form_validation->set_rules("user_type", "User Type", "trim|required");
			$this->form_validation->set_rules("support_user", "Support User", "trim|required");
			$this->form_validation->set_rules("support_subject", "Support Subject", "trim|required");
			$this->form_validation->set_rules("message", "Message", "trim|required");
			if($this->form_validation->run() !== false)
			{
				if($this->data["admin_type"] === SUPER_ADMIN_USER)
				{
					$st_data["user_type_from"] = $sth_data["user_type_from"] = $this->input->post("user_type");
					$st_data["user_id_from"] = $sth_data["user_id_from"] = $this->input->post("support_user");
					$st_data["user_type_to"] = $sth_data["user_type_to"] = $this->data["admin_type"];
					$st_data["user_id_to"] = $sth_data["user_id_to"] = $this->data["admin_id"];
				}
				else
				{
					$st_data["user_type_to"] = $sth_data["user_type_to"] = SUPER_ADMIN_USER;
					$st_data["user_id_to"] = $sth_data["user_id_to"] = SUPER_ADMIN_USER_ID;
					$st_data["user_type_from"] = $sth_data["user_type_from"] = $this->data["admin_type"];
					$st_data["user_id_from"] = $sth_data["user_id_from"] = $this->data["admin_id"];
				}
				$st_data["subject"] = $this->input->post("support_subject");
				$sth_data["message"] = $this->input->post("message");
				$st_data["last_reply"] = $sth_data["replied_by"] = $usertypes[$st_data["user_type_from"]];
				$sth_data["attachment"] = "";
				if(isset($_FILES)&& $_FILES["file_name"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."support_ticket/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG|doc|docx|rtf|txt|wpd|xls|xlsx|pdf|zip|rar";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("file_name");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$sth_data["attachment"] = "support_ticket/".$simg["file_name"];
					}
					else
					{
						$response["msg"] = "Please select valid attachment file.";
						echo json_encode($response);exit;
					}
				}
				$this->db->trans_begin();
				try
				{
					$result = $this->Support_model->add_support_ticket($st_data, $sth_data);
					if($result !== false)
					{
						$this->db->trans_commit();
						$response["status"] = "true";
						$response["msg_status"] = "success";
						$response["msg"] = "Support Ticket has been created Successfully.";
					}
					else
					{
						if($sth_data["attachment"] !== "" && file_exists(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]) && !is_dir(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]))
							unlink(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]);
						$this->db->trans_rollback();
						$response["msg"] = "Failed to create Support Ticket.";
					}
				}
				catch(Exception $ex)
				{
					if($sth_data["attachment"] !== "" && file_exists(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]) && !is_dir(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]))
							unlink(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]);
					$this->db->trans_rollback();
					$response["msg"] = "Sorry, Operation failed.";
				}
			}
			else
				$response["msg"] = "Enter proper details.";
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Support Ticket";
			$this->load->view("support/add_ticket");
		}
	}


	//get all inbox tickets list
	public function inbox_tickets_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "support_id", "last_updated", "email_id", "st_subject", "last_reply");

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
			$condition = array("user_id" => $this->data["admin_id"], "user_type" => $this->data["admin_type"]);
			$result_list = $this->Support_model->get_inbox_tickets($condition, $sWhere, $sOrder, $sLimit);
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
				$ticket_html = "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='' href='".base_url($this->data["controller"]."/tickets_view".DEFAULT_EXT."?ticket=".$id)."' data-original-title='View Ticket'><i class='icon-ticket'></i>".$aRow["support_id"]."</a>\n";
				$email_html = "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='".$aRow["last_reply"]."'><i class='icon-user'></i></a><span class='has-popover' data-original-title='' title='".$aRow["firstname"]." ".$aRow["lastname"]."'>".$aRow["email_id"]."</span>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='' href='".base_url($this->data["controller"]."/tickets_view".DEFAULT_EXT."?ticket=".$id)."' data-original-title='View Ticket'><i class='icon-ticket'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip close_support_ticket' data-placement='top' title='Close' href='javascript:void(0);'><i class='icon-off'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "ticket" => $aRow["support_id"], "ticket_html" => $ticket_html, "last_updated" => $aRow["last_updated"], "email" => $email_html, "subject" => $aRow["st_subject"], "last_reply" => $aRow["last_reply"], "actions" => $actions, "id" => $id));
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


	//get all sent tickets list
	public function sent_tickets_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "support_id", "last_updated", "email_id", "st_subject", "last_reply");

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
			$condition = array("user_id" => $this->data["admin_id"], "user_type" => $this->data["admin_type"]);
			$result_list = $this->Support_model->get_sent_tickets($condition, $sWhere, $sOrder, $sLimit);
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
				$ticket_html = "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='' href='".base_url($this->data["controller"]."/tickets_view".DEFAULT_EXT."?ticket=".$id)."' data-original-title='View Ticket'><i class='icon-ticket'></i>".$aRow["support_id"]."</a>\n";
				$email_html = "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='".$aRow["last_reply"]."'><i class='icon-user'></i></a><span class='has-popover' data-original-title='' title='".$aRow["firstname"]." ".$aRow["lastname"]."'>".$aRow["email_id"]."</span>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='' href='".base_url($this->data["controller"]."/tickets_view".DEFAULT_EXT."?ticket=".$id)."' data-original-title='View Ticket'><i class='icon-ticket'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip close_support_ticket' data-placement='top' title='Close' href='javascript:void(0);'><i class='icon-off'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "ticket" => $aRow["support_id"], "ticket_html" => $ticket_html, "last_updated" => $aRow["last_updated"], "email" => $email_html, "subject" => $aRow["st_subject"], "last_reply" => $aRow["last_reply"], "actions" => $actions, "id" => $id));
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


	//get all closed tickets list
	public function closed_tickets_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "support_id", "last_updated", "email_id", "st_subject", "last_reply");

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
			$condition = array("user_id" => $this->data["admin_id"], "user_type" => $this->data["admin_type"]);
			$result_list = $this->Support_model->get_closed_tickets($condition, $sWhere, $sOrder, $sLimit);
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
				$email_html = "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='".$aRow["last_reply"]."'><i class='icon-user'></i></a><span class='has-popover' data-original-title='' title='".$aRow["firstname"]." ".$aRow["lastname"]."'>".$aRow["email_id"]."</span>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='' href='".base_url($this->data["controller"]."/tickets_view".DEFAULT_EXT."?ticket=".$id)."' data-original-title='View Ticket'><i class='icon-ticket'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_support_ticket' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "ticket" => $aRow["support_id"], "last_updated" => $aRow["last_updated"], "email" => $email_html, "subject" => $aRow["st_subject"], "last_reply" => $aRow["last_reply"], "actions" => $actions, "id" => $id));
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

	public function tickets_view()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.", "result" => "");
			$usertypes = array(SUPER_ADMIN_USER => "Administrator", ADMIN_USER => "Admin", B2B_USER => "B2B User", B2C_USER => "B2C User");
			$response["title"] = $this->data["page_main_title"]." - Reply Support Ticket";
			$this->form_validation->set_rules("ticket_dtls", "Ticket Details", "trim|required");
			$this->form_validation->set_rules("message", "Message", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$ticket_dtls = explode(":::", $this->input->post("ticket_dtls"));
				$id = $this->encrypt->decode(base64_decode($ticket_dtls[0]));
				if(count($ticket_dtls) === 3)
				{
					$st_data["user_type_from"] = $sth_data["user_type_from"] = $this->data["admin_type"];
					$st_data["user_id_from"] = $sth_data["user_id_from"] = $this->data["admin_id"];
					$st_data["user_type_to"] = $sth_data["user_type_to"] = $ticket_dtls[1];
					$st_data["user_id_to"] = $sth_data["user_id_to"] = $ticket_dtls[2];
					$sth_data["message"] = $this->input->post("message");
					$st_data["last_reply"] = $sth_data["replied_by"] = $usertypes[$st_data["user_type_from"]];
					$sth_data["attachment"] = "";
					if(isset($_FILES)&& $_FILES["file_name"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."support_ticket/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG|doc|docx|rtf|txt|wpd|xls|xlsx|pdf|zip|rar";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("file_name");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$sth_data["attachment"] = "support_ticket/".$simg["file_name"];
						}
						else
						{
							$response["msg"] = "Please select valid attachment file.";
							echo json_encode($response);exit;
						}
					}
					$this->db->trans_begin();
					try
					{
						$result = $this->Support_model->reply_support_ticket($id, $st_data, $sth_data);
						if($result !== false)
						{
							$this->db->trans_commit();
							$response["status"] = "true";
							$response["msg_status"] = "success";
							$attachment = !is_null($sth_data["attachment"]) && $sth_data["attachment"] !== "" ? "<a class='attachment' href='".upload_url($sth_data["attachment"])."' download><i class='icon-paper-clip'></i></a>" : "";
							$response["msg"] = "Support Ticket has been replied Successfully.";
							$response["result"] .= '<div class="row msg_container base_sent">
												<div class="col-md-11 col-xs-10">
													<div class="messages msg_sent">
													<h5>You <span> Says:</span></h5>
														<p>'.$sth_data["message"].'</p>
														<time>'.$this->general->set_time_ago(date("Y-m-d H:i:s")).'</time>
														'.$attachment.'
													</div>
												</div>
												<div class="col-md-1 col-xs-2 avatar">
													<img src="'.upload_url("common/images/default.png").'" class=" img-responsive ">
												</div>
											</div>';
						}
						else
						{
							if($sth_data["attachment"] !== "" && file_exists(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]) && !is_dir(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]);
							$this->db->trans_rollback();
							$response["msg"] = "Failed to reply Support Ticket.";
						}
					}
					catch(Exception $ex)
					{
						if($sth_data["attachment"] !== "" && file_exists(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]) && !is_dir(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$sth_data["attachment"]);
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
					}
				}
				else
					$response["msg"] = "Some data is missing.";
			}
			else
				$response["msg"] = "Enter proper details.";
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("ticket")));
			if(is_numeric($id) && $id > 0)
			{
				$this->data["page_title"] = "View Support Ticket History";
				$data["support_ticket"] = $this->input->get("ticket");
				$this->load->view("support/view_ticket", $data);
			}
			else
				redirect("support", "refresh");
		}
		else
			redirect("support", "refresh");
	}

	public function support_ticket_history()
	{
		$return_limit = 5;
		$response = array("result" => "No history availale.", "page" => 0);
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("ticket")));
			$page = is_numeric($this->input->post("ticket_page")) ? $this->input->post("ticket_page") : "0";
			$ticket_added = is_numeric($this->input->post("ticket_inserted")) ? $this->input->post("ticket_page") : "0";
			if(is_numeric($id) && $id > 0)
			{
				$limit = "limit ".(($page * $return_limit) + $ticket_added).", ".$return_limit;
				$result = $this->Support_model->get_st_history($id, $limit);
				if($result["count"] !== 0)
				{
					$response["page"] = $page + 1;
					$response["count"] = $result["count"];
					$response["result"] = "";
					$no_more_data = true;
					$counts = ceil($result["count"]/$return_limit);
					if($counts > $response["page"])
						$no_more_data = false;
					if($page === "0")
						$response["result"] = '<div class="panel-body msg_container_base">';
					if(!$no_more_data)
						$response["result"] .= "<a href='javascript:void(0);' class='load_more_st_history' hyperlink='".($page + 1)."'> Load older history</a>";
					$result["result"] = array_reverse($result["result"]);
					$user_type_to = "";
					$user_id_to = "";
					$status = false;
					foreach ($result["result"] as $key)
					{
						$status = $key["status"] === "1" ? true : false;
						$user_type_to = $key["user_type_from"] === $this->data["admin_type"] ? $key["user_type_to"] : $key["user_type_from"];
						$user_id_to = $key["user_id_from"] === $this->data["admin_id"] ? $key["user_id_to"] : $key["user_id_from"];
						$image = !is_null($key["image"]) && $key["image"] !== "" ? upload_url($key["image"]) : upload_url("common/images/default.png");
						$attachment = !is_null($key["attachment"]) && $key["attachment"] !== "" ? "<a class='attachment' href='".upload_url($key["attachment"])."' download><i class='icon-paper-clip'></i></a>" : "";
						if($key["user_type_from"] === $this->data["admin_type"])
						{
							$response["result"] .= '<div class="row msg_container base_sent">
											<div class="col-md-11 col-xs-10">
												<div class="messages msg_sent">
												<h5>You <span> Says:</span></h5>
													<p>'.$key["message"].'</p>
													<time>'.$this->general->set_time_ago($key["replied_on"]).'</time>
													'.$attachment.'
												</div>
											</div>
											<div class="col-md-1 col-xs-2 avatar">
												<img src="'.$image.'" class=" img-responsive ">
											</div>
										</div>';
						}
						else
						{
							$response["result"] .= '<div class="row msg_container base_receive">
											<div class="col-md-1 col-xs-2 avatar">
												<img src="'.$image.'" class=" img-responsive ">
											</div>
											<div class="col-md-11 col-xs-10">
												<div class="messages msg_receive">
													<h5>'.$key["firstname"]." ".$key["lastname"].'<span> Says:</span></h5>
													<p>'.$key["message"].'</p>
													<time>'.$this->general->set_time_ago($key["replied_on"]).'</time>
													'.$attachment.'
												</div>
											</div>
										</div>';
						}
					}
					if($page === "0")
					{
						$response["result"] .= '</div>';
						if($status !== false)
						{
							$response["result"] .= '<form action="javascript:void(0);" class="reply_support_ticket" hyperlink="'.$this->input->post("ticket").":::".$user_type_to.":::".$user_id_to.'" method="post">
														<div class="panel-footer col-md-5">
															<input type="file" class="form-control input-sm chat_input" name="file_name" data-rule-accept="image/*,.pdf,.txt,.doc,.docx,.rtf,.wpd,.zip,.rar,.xls,.csv,.xlsx" placeholder="Select Attachment" />
														</div>
														<div class="panel-footer col-md-6">
															<div class="input-group">
																<input id="btn-input" autocomplete="off" name="message" data-rule-required="true" data-msg-required="Please enter description." type="text" class="form-control input-sm chat_input" placeholder="Write your message here..." />
																<span class="input-group-btn">
																	<button type="submit" class="btn btn-primary btn-sm">Send</button>
																</span>
															</div>
														</div>
														<div class="panel-footer col-md-1">
															<a class="btn btn-primary btn-sm" href="'.base_url($this->data['controller'].DEFAULT_EXT).'" >Go Back</a>
														</div>
													<form>';
						}
						else
						{
							$response["result"] .= '<div class="panel-footer col-md-5">
													</div>
													<div class="panel-footer col-md-6">
													</div>
													<div class="panel-footer col-md-1">
														<a class="btn btn-primary btn-sm" href="'.base_url($this->data['controller'].DEFAULT_EXT).'" >Go Back</a>
													</div>';
						}
					}
				}
			}
		}
		echo json_encode($response);exit;
	}

	//close support ticket
	public function close_ticket()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("ticket")));
			if(is_numeric($id) && $id > 0)
			{
				$response["title"] = $this->data["page_main_title"]." - Close Ticket";
				$result = $this->Support_model->close_ticket($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = "Ticket closed successfully.";
					$response["msg_status"] = "success";
				}
				else
					$response["msg"] = "Sorry, Operation failed.";
			}
		}
		echo json_encode($response);exit;
	}

	//delete support ticket
	public function delete_ticket()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Ticket";
			$id = $this->encrypt->decode(base64_decode($this->input->post("ticket")));
			if(is_numeric($id) && $id > 0)
			{
				$this->db->trans_begin();
				try
				{
					$result = $this->Support_model->remove_ticket($id, $this->data["admin_type"]);
					if($result !== false)
					{
						if(is_array($result) && count($result) > 0)
						{
							foreach ($result as $key)
							{
								if($key["attachment"] !== "" && file_exists(REL_IMAGE_UPLOAD_PATH.$key["attachment"]) && !is_dir(REL_IMAGE_UPLOAD_PATH.$key["attachment"]))
									unlink(REL_IMAGE_UPLOAD_PATH.$key["attachment"]);
							}
						}
						$this->db->trans_commit();
						$response["status"] = "true";
						$response["msg"] = "Ticket deleted successfully.";
						$response["msg_status"] = "success";
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
						$response["attachment"] = "content";
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
}