<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Admin_model");
		$this->load->model("Sendmail_model");
		$this->data["default_language"] = "en";
		$this->data["page_title"] = "Login";
		$this->data["page_main_title"] = "Login";
	}

	/*Admin sign-in functionality*/
	public function index()
	{
		// $this->general->hotel_beds_city();die;
		is_admin_logged_in();
		if($this->input->post("email") && $this->input->post("password"))
		{
			$this->form_validation->set_rules("email", "Email-Id", "trim|required|valid_email");
			$this->form_validation->set_rules("password", "Password", "trim|required");

			if ($this->form_validation->run() !== false)
			{
				$email = $this->input->post("email");
				$password = $this->input->post("password");

				if ($this->check_login($email, $password))
				{
					$admin_details = $this->Admin_model->get_admin_details($email);
					$user_info = array( 
						SESSION_PREPEND."id"		=> $admin_details->id,
						SESSION_PREPEND."email"		=> $admin_details->email_id,
						SESSION_PREPEND."user_type" => $admin_details->user_type
					);
					$this->session->set_userdata($user_info);
					redirect("home", "refresh");
				}
				else
				{
					$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Please enter valid Username/Password combination!");
					$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
					$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Login Failed!!");
					redirect();
				}
			}
			else
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Please enter valid Username/Password combination!");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
				$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Login Failed!!");
				redirect();
			}
		}
		$this->load->view("index");	
	}
	
	/*Re-generate and confirm password*/ 
	private function check_login($email, $password)
	{
		$admin = $this->Admin_model->get_admin_credentials($email);
		return $admin !== false ? $this->general->validate_pass($admin->password, $admin->salt, $password) : false;
	}

	/*Admin sign-out functionality*/
	public function logout()
	{
		$this->session->unset_userdata("user_info");
		$this->session->sess_destroy();
		redirect("login", "refresh"); 
	}

	/*forget password code mail to admin/sub-admin*/
	public function forgot_password()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) === 1)
		{
			$response["title"] = "Forgot Password - Validate Email Address";
			$this->form_validation->set_rules("forgot_email", "Email address", "trim|required|valid_email");
			if($this->form_validation->run() !== false)
			{
				$email = $this->input->post("forgot_email");
				$user_info = $this->Admin_model->is_registered_admin($email);
				if($user_info !== false)
				{
					$user_info = $this->Admin_model->get_admin_details($email);
					if($user_info->status === "1")
					{
						$this->db->trans_begin();
						try
						{
							$verify_code = $this->general->generate_random_key(mt_rand(7, 15));
							$upd_status = $this->Admin_model->update_admin($user_info->id, array("key" => base64_encode($this->encrypt->encode($verify_code))));
							if($upd_status !== false)
							{
								$data["verify_code"] = $verify_code;
								$data["admin"] = $user_info;
								$msg_sent = $this->Sendmail_model->forgot_password($data);
								if($msg_sent === true)
								{
									$this->db->trans_commit();
									$response["status"] = "true";
									$response["admin"] = base64_encode($this->encrypt->encode($user_info->id));
								}
								elseif($msg_sent === "TNF")
								{
									$this->db->trans_rollback();
									$response["msg"] = "Sorry, Email template is not found.";
								}
								else
								{
									$this->db->trans_rollback();
									$response["msg"] = "Sorry, Failed to send verification email.";
								}
							}
							else
							{
								$this->db->trans_rollback();
								$response["msg"] = "Sorry, Failed to generate verification code.";
							}
						}
						catch(Exception $ex)
						{
							$this->db->trans_rollback();
							$response["msg"] = "Sorry, Operation failed.";
						}
					}
					else
					{
						$response["msg"] = "This email address is inactive. Please contact administration.";
						$response["msg_status"] = "info";
					}
				}
				else
					$response["msg"] = "This is not valid admin email address.";
			}
			else
				$response["msg"] = "Please check the details you have entered.";
		}
		echo json_encode($response);exit;
	}

	/*reset password for admin*/
	public function reset_password()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("admin", "Admin", "trim|required");
			$this->form_validation->set_rules("verify", "Code", "trim|required");
			$this->form_validation->set_rules("newpass", "Password", "trim|required|matches[confirmpass]");
			$this->form_validation->set_rules("confirmpass", "Password Confirmation", "trim|required");
			$response["title"] = "Forgot Password - Reset Password";
			if($this->form_validation->run() !== false)
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("admin")));
				if(is_numeric($id) && $id > 0)
				{
					$admin_info = $this->Admin_model->get_admin_details("", $id);
					$verify_code = $this->encrypt->decode(base64_decode($admin_info->key));
					$code = $this->input->post("verify");
					$newpass = $this->input->post("newpass");
					if(strcmp($code, $verify_code) === 0)
					{
						$new_pass = $this->general->generate_salt_pwd($newpass);
						$result = $this->Admin_model->update_admin($id, array("password" => $new_pass["password"], "salt" => $new_pass["salt"], "key" => ""));
						if($result !== false)
						{
							$response["status"] = "true";
							$response["msg_status"] = "success";
							$response["msg"] = "Password reset successful. Please login with new password.";
						}
						else
							$response["msg"] = "Password reset unsuccessful. Please try again.";
					}
					else
					{
							$response["msg_status"] = "info";
							$response["msg"] = "Please enter valid verification code.";
					}
				}
				else
					$response["msg"] = "Please enter valid verification code.";
			}
			else
				$response["msg"] = "Please enter valid verification code.";
		}
		echo json_encode($response);exit;
	}

}