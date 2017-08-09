<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class B2c extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Promocode_model");
		$this->load->model("B2c_model");
		$this->load->model("Sendmail_model");
		$this->data["page_main_title"] = "B2C Management";
		$this->data["page_title"] = "B2C Management";
	}

	//agent list
	public function index()
	{
		$this->load->view("b2c/index");
	}

	public function users_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("user_id", "email_id", "firstname", "lastname", "contact_no", "register_date");

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
			$result_list = $this->B2c_model->users_list($sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control b2c_user_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons user_promocode b2c' data-placement='top' title='Send Promocode' href='javascript:void(0);'><i class='icon-barcode'></i></a>\n";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Bookings' href='javascript:void(0);'><i class='icon-eye-open'></i></a>\n";
				$actions .= "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Send Mail' href='".base_url($this->data["controller"]."/mail".DEFAULT_EXT."?user=".$id)."'><i class='icon-envelope'></i></a>\n";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Profile' href='".base_url($this->data["controller"]."/view_profile".DEFAULT_EXT."?user=".$id)."'><i class='icon-search'></i></a>\n";
				$actions .= "<br>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/edit_profile".DEFAULT_EXT."?user=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_b2c_user' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "</div>";
				$image = is_null($aRow["image_path"]) || $aRow["image_path"] === "" ? upload_url(B2C_DEFAULT_IMG) : upload_url($aRow["image_path"]);
				$image_html = "<img width='50px' height='50px' src='".$image."' alt=''>";;
				$row[] = json_encode(array("sl_no" => $i++, "user_id" => $aRow["user_id"], "fname" => $aRow["firstname"], "lname" => $aRow["lastname"], "email" => $aRow["email_id"], "image_html" => $image_html, "contact" => $aRow["contact_no"], "registered" => $aRow["register_date"], "status_html" => $status_html, "actions" => $actions, "id" => $id));
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

	//add new b2c user
	public function add()
	{
		if($this->input->is_ajax_request()  && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Add User";
			$this->form_validation->set_rules("emailid", "Email", "trim|required");
			$this->form_validation->set_rules("pass", "Password", "required|matches[confirmpass]");
			$this->form_validation->set_rules("confirmpass", "Password Confirmation", "required|matches[pass]");
			$this->form_validation->set_rules("phone", "Contact No", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$is_registered = $this->B2c_model->get_user("", $this->input->post("emailid"));
				if($is_registered !== false)
				{
					$response["msg"] = "Enter email ID already registered.";
					$response["msg_status"] = "info";
					$response["status"] = "exist";
				}
				else
				{
					if($this->input->post("national_id") == '')
						$data['national_id'] = 0;
					else
						$data['national_id'] = $this->input->post("national_id");
					
					
					$data["firstname"] = $firstname = ucfirst($this->input->post("firstname"));
					$data["lastname"] = $lastname = ucfirst($this->input->post("lastname"));
					$data["email_id"] = $emailid = strtolower($this->input->post("emailid"));
					$data["address"] = $address = $this->input->post("address");
					$data["phone"] = $phone = $this->input->post("phone");
					$data["password"] = $pass = $this->input->post("pass");
					$data["url"] = front_url();
					$data['dob'] = $this->input->post("dob");
					$data['passport_no'] = $this->input->post("passport_no");
					$data['passport_exp_date'] = $this->input->post("passport_exp_date");	
					$country = strtoupper($this->input->post("country"));
					$postal_code = (int)$this->input->post("postal_code");
					$enc_pass = $this->general->generate_salt_pwd($pass);
					$user_data = array(
								"user_type" => B2C_USER,
								"email_id" => $emailid,
								"salt" => $enc_pass["salt"],
								"password" => $enc_pass["password"],
								"firstname" => $firstname,
								"lastname" => $lastname,
								"dob" => date('Y-m-d',strtotime($data['dob'])),
								"address" => $address,
								"contact_no" => $phone,
								"country" => $country,
								"postal_code" => $postal_code,
								"register_date" => date("Y-m-d H:i:s"),
								"status" => (int)"1",
								"national_id" =>$data['national_id'],
								"passport_no" =>$this->input->post("passport_no"),
								"passport_exp_date" =>date('Y-m-d',strtotime($data['passport_exp_date'])),
								"updated_on" => date("Y-m-d H:i:s"));
					
					$this->db->trans_begin();
					try
					{
						$result = $this->B2c_model->add($user_data);
						
						if($result !== false)
						{
							// $mail_sent = $this->Sendmail_model->b2c_register($data);
							// if($mail_sent === true)
							// {
								$this->db->trans_commit();
								$response["msg"] = "B2C user added successfully.";
								$response["msg_status"] = "success";
								$response["status"] = "true";
							// }
							// else
							// {
							// 	$this->db->trans_rollback();
							// 	$response["msg"] = "Failed to send email.";
							// }
						}
						else
						{
							$this->db->trans_rollback();
							if($user_data["image_path"] !== "" && file_exists(REL_IMAGE_UPLOAD_PATH.$user_data["image_path"]) && !is_dir(REL_IMAGE_UPLOAD_PATH.$user_data["image_path"]))
									unlink(REL_IMAGE_UPLOAD_PATH.$user_data["image_path"]);
								$response["msg"] = "Failed to send email.";
							$response["msg"] = "Failed to add new B2C User.";
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
		}
		else
		{
			$this->data["page_title"] = "Add B2C User";
			$this->load->view("b2c/add");
		}
	}

	/*change user status*/
	public function b2c_user_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("b2c")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Account Activation" : " - Account Deactivation");
			if(is_numeric($id) && $id > 0)
			{
				$this->db->trans_begin();
				try
				{
					$result = $this->B2c_model->update_profile($id, array("status" => $status));
					if($result !== false)
					{
						$response["status"] = "true";
						$mail_data = array();
						$mail_data["status"] = $status === "1" ? "Activated" : "Deactivated";
						$mail_data["user"] = $this->B2c_model->get_user($id);
						$mail_data["account"] = ACCOUNT_B2C;
						$mail_sent = $this->Sendmail_model->account_status($mail_data);
						if($mail_sent !== false)
						{
							$this->db->trans_commit();
							$response["status"] = "true";
							$response["msg"] = $status === "0" ? "B2C User account deactivated successfully." : "B2C User account activated successfully.";
							$response["msg_status"] = $status === "0" ? "info" : "success";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg_status"] = "info";
							$response["msg"] = "Failed to send email to b2c user.";
						}
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg_status"] = "info";
						$response["msg"] = "Failed to update b2c user status.";
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

	// send promocode to single b2c user
	public function promocode()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("user", "User", "trim|required");
			$this->form_validation->set_rules("promo_code", "Promocode", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Send Promocode";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("user")));
				$p_id = $this->encrypt->decode(base64_decode($this->input->post("promo_code")));
				if(is_numeric($id) && $id > 0 && is_numeric($p_id) && $p_id > 0)
				{
					$b2cpc["promocode"] = $this->Promocode_model->get_promocode($p_id);
					$b2c_user = $this->B2c_model->get_user($id);
					if($b2cpc["promocode"] !== false && $b2c_user !== false)
					{
						$b2cpc["to"] = $b2c_user->email_id;
						$b2cpc["salutation"] = $b2c_user->firstname;
						//send promocode mail function call here
						$mail_sent = $this->Sendmail_model->promocode($b2cpc);
						if($mail_sent !== false)
						{
							$response["msg"] = "Promocode is successfully sent to ".$b2cpc["salutation"]." Mail.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$response["msg"] = "Failed to send promocode.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Sorry, Operation failed. Try again.";
				}
			}
		}
		echo json_encode($response);exit;
	}

	// send mail to selected b2c user
	public function mail()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("user", "User", "trim|required");
			$this->form_validation->set_rules("subject", "Subject", "trim|required");
			$this->form_validation->set_rules("message", "Message", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Send Mail";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("user")));
				if(is_numeric($id) && $id > 0)
				{
					$sm["subject"] = ucfirst($this->input->post("subject"));
					$sm["message"] = ucfirst($this->input->post("message"));
					$b2c_user = $this->B2c_model->get_user($id);
					if($b2c_user !== false)
					{
						$sm["to"] = $b2c_user->email_id;
						//send mail function call here
						$mail_sent = $this->Sendmail_model->simple_mail($sm);
						if($mail_sent !== false)
						{
							$response["msg"] = "Mail is successfully sent to B2C User \"".$b2c_user->firstname."\".";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$response["msg"] = "Failed to send mail.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Sorry, Operation failed. Try again.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Send Mail to B2C User";
			$id = $this->encrypt->decode(base64_decode($this->input->get("user")));
			if($id > 0)
			{
				$data["user_details"] = $this->B2c_model->get_user($id);
				if($data["user_details"] !== false)
				{
					$data["user_id"] = $this->input->get("user");
					$this->load->view("b2c/mail", $data);
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

	//view b2c user profile
	public function view_profile()
	{
		$id = $this->encrypt->decode(base64_decode($this->input->get("user")));
		if(is_numeric($id) && $id > 0)
		{
			$this->data["page_title"] = "View Profile";
			$data["user"] = $this->B2c_model->get_user($id);
			$data["id"] = $id;
			$this->load->view("b2c/view",$data);
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	//edit b2c user profile 
	public function edit_profile()
	{
		if($this->input->is_ajax_request()  && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = $this->data["page_main_title"]." - Update User";
			$id = $id = intval($this->encrypt->decode(base64_decode($this->input->post("user"))));
			if(is_numeric($id) && $id > 0)
			{
				$this->form_validation->set_rules("phone", "Contact No", "trim|required");
				
				if($this->form_validation->run() !== false)
				{
					$firstname = $this->input->post("firstname");
					$lastname = $this->input->post("lastname");
					$address = $this->input->post("address");
					$phone = $this->input->post("phone");
					$country = $this->input->post("country");
					$postal_code = $this->input->post("postal_code");
					
					if($this->input->post("national_id") == 0)
						$national_id = 0;
					else
						$national_id = $this->input->post("national_id");
					
					
					
					
					$user_data = array(
								"firstname" => ucfirst($firstname),
								"lastname" => ucfirst($lastname),
								"address" => $address,
								"contact_no" => $phone,
								"country" => strtoupper($country),
								"postal_code" => intval($postal_code),
								"dob" =>date('Y-m-d',strtotime($this->input->post("dob"))),
								"national_id" =>$national_id,
								"passport_no" =>$this->input->post("passport_no"),
								"passport_exp_date" =>date('Y-m-d',strtotime($this->input->post("passport_exp_date"))),
								"updated_on" => date("Y-m-d H:i:s"));
					
					$this->db->trans_begin();
					try
					{
						$result = $this->B2c_model->update_profile($id, $user_data);
						if($result !== false)
						{
							$this->db->trans_commit();
							$response["msg"] = "B2C user updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg_status"] = "info";
							$response["msg"] = "No changes are made.";
						}
					}
					catch(Exception $ex)
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Operation failed.";
					}
				}
			}
			else
				$response["msg"] = "Please check the details you have entered.";
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("user")));
			if(is_numeric($id) && $id > 0)
			{
				$this->data["page_title"] = "Update B2C User";
				$data["id"] = $this->input->get("user");
				$data["user"] = $this->B2c_model->get_user($id);
				$this->load->view("b2c/edit",$data);
			}
			else
				redirect($this->data["controller"], "refresh");
		}
		else
			redirect($this->data["controller"], "refresh");
	}

	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response['title'] = $this->data["page_main_title"]." - Delete User";
			$id = $this->encrypt->decode(base64_decode($this->input->post("user")));
			if(is_numeric($id) && $id > 0)
			{
				$this->db->trans_begin();
				try
				{
					$mail_data = array();
					$mail_data["user"] = $this->B2c_model->get_user($id);
					$result = $this->B2c_model->delete_user($id);
					if($result !== false)
					{
						$mail_data["status"] = "Deleted";
						$mail_data["account"] = ACCOUNT_B2C;
						$mail_sent = $this->Sendmail_model->account_status($mail_data);
						if($mail_sent !== false)
						{
							$this->db->trans_commit();
							$response['status'] = "true";
							$response['msg_status'] = "success";
							$response['msg'] = "B2C user account deleted successfully.";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg_status"] = "info";
							$response["msg"] = "Failed to send email to b2c user.";
						}
					}
					else
					{
						$this->db->trans_rollback();
						$response['msg'] = "Sorry, B2C user account failed to remove. Try again.";
					}
				}
				catch(Exception $ex)
				{
					$this->db->trans_rollback();
					$response["msg"] = "Sorry, Operation failed.";
				}
			}
			else
				$response['msg'] = "Sorry, Operation failed.";
		}
		echo json_encode($response);exit;
	}

	// /*Export Excel File*/
	// public function export_b2c_users() {
	// 	$selected_ids = $this->input->post('cid');
	// 	if (!empty($selected_ids)) {

	// 		$this->load->library("Excel");
	// 		$phpExcel = new PHPExcel();
	// 		$prestasi = $phpExcel->setActiveSheetIndex(0);
	// 		//merger
	// 		$phpExcel->getActiveSheet()->mergeCells('A1:H1');
	// 		//manage row hight
	// 		$phpExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
	// 		//style alignment
	// 		$styleArray = array(
	// 			'alignment' => array(
	// 				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	// 				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	// 			),
	// 		);
	// 		$phpExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	// 		$phpExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
	// 		//border
	// 		$styleArray1 = array(
	// 			'borders' => array(
	// 				'allborders' => array(
	// 					'style' => PHPExcel_Style_Border::BORDER_THIN
	// 				)
	// 			)
	// 		);
	// 		//background
	// 		$styleArray12 = array(
	// 			'fill' => array(
	// 				'type' => PHPExcel_Style_Fill::FILL_SOLID,
	// 				'startcolor' => array(
	// 					'rgb' => '00acec',
	// 				//'rgb' => '009933',
	// 				),
	// 			),
	// 		);
	// 		//freeepane
	// 		$phpExcel->getActiveSheet()->freezePane('A3');
	// 		//coloum width
	// 		$phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4.1);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	// 		$phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
	// 		$prestasi->setCellValue('A1', 'B2C User List');
	// 		$phpExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArray);
	// 		$phpExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArray1);
	// 		$phpExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArray12);
	// 		$prestasi->setCellValue('A2', 'S.No');
	// 		$prestasi->setCellValue('B2', 'Name');
	// 		$prestasi->setCellValue('C2', 'Mobile Number');
	// 		$prestasi->setCellValue('D2', 'Email');
	// 		$prestasi->setCellValue('E2', 'Address');
	// 		$prestasi->setCellValue('F2', 'City');
	// 		$prestasi->setCellValue('G2', 'Counrty');
	// 		$prestasi->setCellValue('H2', 'Postal');

	// 		$user_data = $this->B2c_Model->export_b2c_users($selected_ids)->result();
			
	// 		$no = 0;
	// 		$rowexcel = 2;
	// 		foreach ($user_data as $row) {
	// 			$no++;
	// 			$rowexcel++;
	// 			$phpExcel->getActiveSheet()->getStyle('A' . $rowexcel . ':H' . $rowexcel)->applyFromArray($styleArray);
	// 			$phpExcel->getActiveSheet()->getStyle('A' . $rowexcel . ':H' . $rowexcel)->applyFromArray($styleArray1);
	// 			$prestasi->setCellValue('A' . $rowexcel, $no);
	// 			$prestasi->setCellValue('B' . $rowexcel, $row->firstname . ' ' . $row->lastname);
	// 			$prestasi->setCellValue('C' . $rowexcel, $row->contact_no);
	// 			$prestasi->setCellValue('D' . $rowexcel, $row->email);
	// 			$prestasi->setCellValue('E' . $rowexcel, $row->address);
	// 			$prestasi->setCellValue('F' . $rowexcel, $row->city);
	// 			$prestasi->setCellValue('G' . $rowexcel, $row->country);
	// 			$prestasi->setCellValue('H' . $rowexcel, $row->postal_code);
	// 		}
	// 		$prestasi->setTitle('B2C Report');
	// 		header("Content-Type: application/vnd.ms-excel");
	// 		header("Content-Disposition: attachment; filename=\"b2c_users.xlsx\"");
	// 		header("Cache-Control: max-age=0");
	// 		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
	// 		$objWriter->save("php://output");
	// 	} else {
	// 		redirect('b2c');
	// 	}
	// }

	// // agents list pdf attachment download
	// public function b2c_users_export(){
	// 	$users = $this->B2c_Model->get_user_list(); 

	// 	// create new PDF document
	// 	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// 	// set document information
	// 	$pdf->SetCreator(PDF_CREATOR);
	// 	$pdf->SetAuthor('Skyzpro');
	// 	$pdf->SetTitle('Skyzpro');
	// 	$pdf->SetSubject('Skyzpro');
	// 	$pdf->SetKeywords('Skyzpro, PDF');

	// 	// set default header data
	// 	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);

	// 	// set header and footer fonts
	// 	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	// 	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// 	// set default monospaced font
	// 	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// 	// set margins
	// 	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	// 	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// 	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// 	// set auto page breaks
	// 	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// 	// set image scale factor
	// 	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// 	// set some language-dependent strings (optional)
	// 	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	// 		require_once(dirname(__FILE__).'/lang/eng.php');
	// 		$pdf->setLanguageArray($l);
	// 	}

	// 	// set font
	// 	$pdf->SetFont('times', 'B', 20);

	// 	// add a page
	// 	$pdf->AddPage();

	// 	$pdf->Write(0, 'B2C Users List', '', 0, 'L', true, 0, false, false, 0);

	// 	$pdf->SetFont('helvetica', '', 8);

	// 	$tbl = '<style>
	// 			table {
	// 				border-collapse: collapse;
	// 				border-spacing: 0;
	// 				margin: 0 10px;
	// 				cellspacing: "3"; 
	// 				align: "center";
	// 				border: 1px solid #CCC
	// 			}
	// 			tr {
	// 				padding: 3px 0;
	// 			}
	// 			th {
	// 				background-color: #172841;
	// 				border: 1px solid #CCC;
	// 				color: #FFFFFF;
	// 				font-family: trebuchet MS;
	// 				font-size: 12px;
	// 				padding-bottom: 4px;
	// 				padding-left: 6px;
	// 				padding-top: 5px;
	// 				text-align: left;
	// 			}
	// 			td {
	// 				border: 1px solid #CCC;
	// 				font-size: 10px;
	// 				padding: 3px 7px 2px;
	// 			}
	// 			</style>
	// 			<table>
	// 				<tr>
	// 					<th>Name</th>
	// 					<th>Email ID</th>
	// 					<th>Address</th>
	// 					<th>Contact</th>
	// 					<th>Registered Date</th>
	// 				</tr>';

	// 	foreach($users as $user) {
	// 	  $tbl .= "<tr>
	// 				<td>$user->firstname"." "."$user->lastname</td> 
	// 				<td>$user->email</td>
	// 				<td>$user->address</td>
	// 				<td>$user->contact_no</td>
	// 				<td>$user->register_date</td>
	// 			</tr>";
	// 	 }
	// 	$tbl .= '</table>';

	// 	$pdf->writeHTML($tbl, true, false, false, false, '');

	// 	$pdf->Ln();
	// 	$pdf->SetLineStyle(array('width' => 0.0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(255, 0, 0)));
	// 	$pdf->SetFillColor(255,255,128);
	// 	$pdf->SetTextColor(0,0,128);
	// 	$pdf->Ln();
	
	// 	//Close and output PDF document
	// 	$pdf->Output('B2C_Users.pdf', 'D');
	// }
}