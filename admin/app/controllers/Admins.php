<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Admins extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("Sendmail_model");
		$this->load->model("B2c_model");
		$this->data["page_main_title"] = "Sub-Admin Management";
		$this->data["page_title"] = "Sub-Admin Management";
	}

	/*admin management view*/
	public function index()
	{
		$this->load->view("admin/index");
	}

	public function get_admin_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "firstname", "lastname", "email_id", "contact_no");

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
			$result_list = $this->Admin_model->get_admin_list($this->data["admin_id"], $sWhere, $sOrder, $sLimit);
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
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control admin_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"]."/edit_admin".DEFAULT_EXT."?admin=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-warning btn-xs has-tooltip change_pwd_admin' data-placement='top' title='Change Password' href='javascript:void(0);'><i class='icon-unlock'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_admin' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "fname" => $aRow["firstname"], "lname" => $aRow["lastname"], "email" => $aRow["email_id"], "contact" => $aRow["contact_no"], "status" => $status_html, "actions" => $actions, "id" => $id, "user_type" => $aRow["user_type_name"]));
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

	/*add new admin*/
	public function add_admin()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("firstname", "First Name", "trim|required");
			$this->form_validation->set_rules("lastname", "Last Name", "trim|required");
			$this->form_validation->set_rules("emailid", "Email", "trim|required");
			$this->form_validation->set_rules("address", "Address", "trim|required");
			$this->form_validation->set_rules("pass", "Password", "required|matches[confirmpass]");
			$this->form_validation->set_rules("confirmpass", "Password Confirmation", "required|matches[pass]");
			$this->form_validation->set_rules("phone", "Contact No", "trim|required");
			$this->form_validation->set_rules("country", "Country", "trim|required");
			$this->form_validation->set_rules("postal_code", "Pin / Zip code", "trim|required");

			$response["title"] = $this->data["page_main_title"]."- Add Admin";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$is_registered = $this->Admin_model->is_registered_admin($this->input->post("emailid"));
				if($is_registered !== false)
				{
					$response["msg"] = "Enter email ID already registered.";
					$response["msg_status"] = "info";
					$response["status"] = "exist";
				}
				else
				{
					$data["firstname"] = $firstname = ucfirst($this->input->post("firstname"));
					$data["lastname"] = $lastname = ucfirst($this->input->post("lastname"));
					$data["emailid"] = $emailid = strtolower($this->input->post("emailid"));
					$address = $this->input->post("address");
					$phone = is_numeric($this->input->post("phone")) ? $this->input->post("phone") : "";
					$data["password"] = $pass = $this->input->post("pass");
					$data["url"] = base_url();
					$country = strtoupper($this->input->post("country"));
					$postal_code = is_numeric($this->input->post("postal_code")) ? $this->input->post("postal_code") : "";
					$user_type = $this->input->post("user_type");

					$enc_pass = $this->general->generate_salt_pwd($pass);
					$admin_data = array(
						"firstname" => $firstname,
						"lastname" => $lastname,
						"password" => $enc_pass["password"],
						"salt" => $enc_pass["salt"],
						"email_id" => $emailid,
						"address" => $address,
						"contact_no" => $phone,
						"city" => null,
						"state" => null,
						"country" => $country,
						"postal_code" => $postal_code,
						"user_type" => (int)$user_type,
						"status" => (int)"1",
						"register_date" => date("Y-m-d H:i:s")
					);

					$this->db->trans_begin();
					try
					{
						if($admin = $this->Admin_model->new_admin($admin_data))
						{
							$privilege = $this->input->post("privilege");
							$privileges = "";
							if(!empty($privilege))
							{
								$privileges = ",";
								foreach($privilege as $key => $val)
									$privileges .= $val.",";
							}
							$assigned = $this->Privilege_model->assign_privileges($admin, $privileges);
							if($assigned !== false || $admin !== false)
							{
								// $mail_sent = $this->Sendmail_model->admin_register($data);
								// if($mail_sent === true)
								// {
									$this->db->trans_commit();
									$response["msg"] = "Admin added successfully.";
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
								$response["msg"] = "Sorry, Failed to add new admin.";
								$response["msg_status"] = "info";
								$response["status"] = "false";
							}
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
		else
		{
			$this->data["page_title"] = "Add Admin";
			$data["privileges"] = $this->Privilege_model->get_parent_privileges();
			$this->load->view("admin/add",$data);
		}
	}

	/*change admin status*/
	public function admin_status()
	{
		$id = $this->encrypt->decode(base64_decode($this->input->post("admin")));
		$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if(is_numeric($id) && $id > 0)
		{
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Account Activation" : " - Account Deactivation");

			$this->db->trans_begin();
			try
			{
				$result = $this->Admin_model->update_admin($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$mail_data = array();
					$mail_data["status"] = $status === "1" ? "Activated" : "Deactivated";
					$mail_data["user"] = $this->Admin_model->get_admin_details("", $id);;
					$mail_data["account"] = ACCOUNT_ADM;
					$mail_sent = $this->Sendmail_model->account_status($mail_data);
					if($mail_sent !== false)
					{
						$this->db->trans_commit();
						$response["status"] = "true";
						$response["msg"] = $status === "0" ? "Admin account deactivated successfully." : "Admin account activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg_status"] = "info";
						$response["msg"] = "Failed to send email to admin.";
					}
				}
				else
				{
					$this->db->trans_rollback();
					$response["msg_status"] = "info";
					$response["msg"] = "Failed to update admin status.";
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

	/*edit admin*/
	public function edit_admin()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$id = $this->encrypt->decode(base64_decode($this->input->post("admin")));
			$response["title"] = $this->data["page_main_title"]."- Update Admin";
			if(is_numeric($id) && $id > 0)
			{
				$this->form_validation->set_rules("firstname", "First Name", "required");
				$this->form_validation->set_rules("lastname", "Last Name", "required");
				$this->form_validation->set_rules("address", "Address", "trim");
				$this->form_validation->set_rules("phone", "Contact No", "required|numeric");
				$this->form_validation->set_rules("country", "Country", "required");
				$this->form_validation->set_rules("postal_code", "Pin / Zip code", "required|numeric");

				if($this->form_validation->run() === false)
				{
					$response["msg"] = "Please check the details you have entered.".validation_errors();
					$response["msg_status"] = "info";
					$response["title"] = "Update Admin";
				}
				else
				{
					$firstname = ucfirst($this->input->post("firstname"));
					$lastname = ucfirst($this->input->post("lastname"));
					$address = $this->input->post("address");
					$phone = is_numeric($this->input->post("phone")) ? $this->input->post("phone") : "";
					$data["url"] = base_url("login");
					$country = strtoupper($this->input->post("country"));
					$postal_code = is_numeric($this->input->post("postal_code")) ? $this->input->post("postal_code") : "";
					$admin_data = array(
						"firstname" => $firstname,
						"lastname" => $lastname,
						"address" => $address,
						"contact_no" => $phone,
						"city" => null,
						"state" => null,
						"country" => $country,
						"postal_code" => $postal_code,
					);

					$this->db->trans_begin();
					try
					{
						$result = $this->Admin_model->update_admin($id, $admin_data);
						$privilege = $this->input->post("privilege");
						$privileges = "";
						if(!empty($privilege))
						{
							$privileges = ",";
							foreach($privilege as $key => $val)
								$privileges .= $val.",";
						}
						$assigned = $this->Privilege_model->assign_privileges($id, $privileges);
						if($assigned !== false || $result !== false)
						{
							$this->db->trans_commit();
							$response["msg"] = "Admin updated successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg"] = "No changes are made.";
							$response["msg_status"] = "info";
							$response["status"] = "false";
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
				$response["msg"] = "Sorry, Operation failed.";
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->get("admin")));
			if(is_numeric($id) && $id > 0)
			{
				$data["admin"] = $this->Admin_model->get_admin_details(null, $id);
				if($data["admin"] !== false)
				{
					$this->data["page_title"] = "Update Admin";
					$data["privileges"] = $this->Privilege_model->get_parent_privileges();
		 			$this->load->view("admin/edit",$data);
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

	/*update admin password*/
	public function update_pwd()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("adm_pass", "Password", "trim|required");
			$this->form_validation->set_rules("admin", "Admin", "trim|required");
			$this->form_validation->set_rules("newpass", "Password", "trim|required|matches[confirmpass]");
			$this->form_validation->set_rules("confirmpass", "Password Confirmation", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("admin")));
				if(is_numeric($id) && $id > 0)
				{
					$response["title"] = $this->data["page_main_title"]." - Change Password";
					$admin = $this->Admin_model->get_admin_credentials($this->data["admin_email"], $this->data["admin_id"]);
					$password = $this->input->post("adm_pass");
					$newpass = $this->input->post("newpass");
					$valid_admin = $admin !== false ? $this->general->validate_pass($admin->password, $admin->salt, $password) : false;
					if($valid_admin && $this->data["admin_type"] === SUPER_ADMIN_USER)
					{
						$this->db->trans_begin();
						try
						{
							$new_pass = $this->general->generate_salt_pwd($newpass);
							$result = $this->Admin_model->update_admin($id, array("password" => $new_pass["password"], "salt" => $new_pass["salt"]));
							if($result !== false)
							{
								$response["status"] = "true";
								$mail_data = array();
								$mail_data["user"] = $this->Admin_model->get_admin_details("", $id);
								$mail_data["new_pass"] = $newpass;
								$mail_data["modified_by"] = MODIFIER_ADM;
								$mail_data["account"] = ACCOUNT_ADM;
								$mail_sent = $this->Sendmail_model->password_update($mail_data);
								if($mail_sent !== false)
								{
									$this->db->trans_commit();
									$response["status"] = "true";
									$response["msg_status"] = "success";
									$response["msg"] = "Password updated successfully.";
								}
								else
								{
									$this->db->trans_rollback();
									$response["msg_status"] = "info";
									$response["msg"] = "Failed to send email to admin.";
								}
							}
							else
							{
								$this->db->trans_rollback();
								$response["msg"] = "Sorry, failed to change password.";
							}
						}
						catch(Exception $ex)
						{
							$this->db->trans_rollback();
							$response["msg"] = "Sorry, Operation failed.";
						}
					}
					else
						$response["msg"] = "In-correct password entered.";
				}
				else
					$response["msg"] = "Please enter valid details.";
			}
		}
		echo json_encode($response);exit;
	}

	/*delete admin*/
	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]."- Delete Admin Account";
			$id = $this->encrypt->decode(base64_decode($this->input->post("admin")));
			if(is_numeric($id) && $id > 0)
			{
				$this->db->trans_begin();
				try
				{
					$admin_details = $this->Admin_model->get_admin_details("", $id);
					$result = $this->Admin_model->delete_admin($id);
					if($result !== false)
					{
						$response["status"] = "true";
						$mail_data = array();
						$mail_data["status"] = "Closed";
						$mail_data["account"] = ACCOUNT_ADM;
						$mail_data["user"] = $admin_details;
						$mail_sent = $this->Sendmail_model->account_status($mail_data);
						if($mail_sent !== false)
						{
							$this->db->trans_commit();
							$response["status"] = "true";
							$response["msg_status"] = "success";
							$response["msg"] = "Admin deleted successfully.";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg_status"] = "info";
							$response["msg"] = "Failed to send an email.";
						}
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg"] = "Failed to delete admin account.";
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