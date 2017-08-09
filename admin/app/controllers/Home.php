<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Home extends CI_Controller {

	/* load default models, libraries */
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("Email_model");
		$this->load->model("Sendmail_model");
		$this->data["page_title"] = "Home";
		$this->data["page_main_title"] = "Home";
	}

	/*load dashboard view*/
	public function index()
	{
		$this->load->view("dashboard");
	}

	/*update my password*/
	public function update_pwd()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("cur_pass", "Password", "trim|required");
			$this->form_validation->set_rules("newpass", "Password", "trim|required|matches[confirmpass]");
			$this->form_validation->set_rules("confirmpass", "Password Confirmation", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$response["title"] = "My Profile - Change Password";
				$admin = $this->Admin_model->get_admin_credentials($this->data["admin_email"], $this->data["admin_id"]);
				$password = $this->input->post("cur_pass");
				$newpass = $this->input->post("newpass");
				$valid_admin = $admin !== false ? $this->general->validate_pass($admin->password, $admin->salt, $password) : false;
				if($valid_admin !== false)
				{
					$this->db->trans_begin();
					try
					{
						$new_pass = $this->general->generate_salt_pwd($newpass);
						$result = $this->Admin_model->update_admin($this->data["admin_id"], array("password" => $new_pass["password"], "salt" => $new_pass["salt"]));
						if($result !== false)
						{
							$response["status"] = "true";
							$mail_data = array();
							$mail_data["user"] = $this->Admin_model->get_admin_details("", $this->data["admin_id"]);
							$mail_data["new_pass"] = $newpass;
							$mail_data["modified_by"] = MODIFIER_SELF;
							$mail_data["account"] = ACCOUNT_ADM;
							$mail_sent = $this->Sendmail_model->password_update($mail_data);
							if($mail_sent !== false)
							{
								$this->db->trans_commit();
								$response["status"] = "true";
								$response["msg_status"] = "success";
								$this->clear_login();
								$response["msg"] = "Password updated successfully. You will redirect to login page. Please login with new password.";
							}
							else
							{
								$this->db->trans_rollback();
								$response["msg_status"] = "info";
								$response["msg"] = "Failed to send new password.";
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
		}
		echo json_encode($response);exit;
	}

	protected function clear_login()
	{
		$this->session->unset_userdata("user_info");
		$this->session->sess_destroy();
	}

	/*update profile*/
	public function profile()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("firstname", "First Name", "required");
			$this->form_validation->set_rules("lastname", "Last Name", "required");
			$this->form_validation->set_rules("address", "Address", "required");
			$this->form_validation->set_rules("phone", "Contact No", "required");
			$this->form_validation->set_rules("country", "Country", "required");
			$this->form_validation->set_rules("state", "State", "required");
			$this->form_validation->set_rules("city", "City", "required");
			$this->form_validation->set_rules("postal_code", "Pin / Zip code", "required");

			$response["title"] = "My Profile - Update Profile";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$data["firstname"] = $firstname = ucfirst($this->input->post("firstname"));
				$data["lastname"] = $lastname = ucfirst($this->input->post("lastname"));
				$data["emailid"] = strtolower($this->input->post("emailid"));
				$data["address"] = $address = $this->input->post("address");
				$data["phone"] = $phone = $this->input->post("phone");
				$data["url"] = base_url("login");
				$city = is_numeric($this->input->post("city")) ? $this->input->post("city") : null;
				$state = $this->input->post("state");
				$country = strtoupper($this->input->post("country"));
				$postal_code = $this->input->post("postal_code");
				$region = strtoupper($this->general->extract_region($state));
					if($region === NO_REGION)
						$region = null;
				$admin_data = array(
					"firstname" => $firstname,
					"lastname" => $lastname,
					"address" => $address,
					"contact_no" => $phone,
					"city" => $city,
					"state" => $region,
					"country" => $country,
					"postal_code" => $postal_code,
				);
				$result = $this->Admin_model->update_admin($this->data["admin_id"], $admin_data);
				if($result !== false)
				{
						$response["msg"] = "Profile updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
				}
				else
				{
					$response["msg"] = "No changes are made.";
					$response["msg_status"] = "info";
				}
			}
			echo json_encode($response);exit;
		}
		$this->data["page_title"] = "Update Profile";
		$data["admin"] = $this->Admin_model->get_admin_details(null, $this->data["admin_id"]);
		if($data["admin"] !== false)
			$this->load->view("account/profile",$data);
		else
			redirect($this->data["controller"], "refresh");
	}

	public function settings()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$response["title"] = "Update Email Settings";
			$this->form_validation->set_rules("id", "Email", "required");
			$this->form_validation->set_rules("smtp", "SMTP", "required");
			$this->form_validation->set_rules("host", "Host", "required");
			$this->form_validation->set_rules("port", "Port", "required");
			$this->form_validation->set_rules("username", "Username", "required");
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("id")));
				$id = is_numeric($id) ? $id : "1";
				$smtp = strtolower($this->input->post("smtp"));
				$host = strtolower($this->input->post("host"));
				$port = is_numeric($this->input->post("port")) ? $this->input->post("port") : "465";
				$username = strtolower($this->input->post("username"));
				$password = $this->input->post("password");
				$e_setting_data = array(
					"id" => $id,
					"smtp" => $smtp,
					"host" => $host,
					"port" => $port,
					"username" => $username
				);
				if($password !== false && $password !== null)
					$e_setting_data["password"] = base64_encode($this->encrypt->encode($password));
				$result = $this->Email_model->set_access($e_setting_data);
				if($result !== false)
				{
						$response["msg"] = "Settings updated successfully.";
						$response["msg_status"] = "success";
				}
				else
				{
					$response["msg"] = "No changes are made.";
					$response["msg_status"] = "info";
				}
			}
			echo json_encode($response);exit;
		}
		$this->data["page_title"] = "Update Email Settings";
		$data["email_access"] = $this->Email_model->get_access();
		if($data["email_access"] !== false)
			$this->load->view("account/settings",$data);
		else
			redirect($this->data["controller"], "refresh");
	}
}