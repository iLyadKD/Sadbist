<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class B2B extends CI_Controller {

	public function __construct()
	{
		// echo base64_decode("SFVLUTVOVENLNVI=");die;
		parent::__construct();
		unset_page_cache();
		$this->data["default_language"] = "en";
		$this->data["page_title"] = "Home";
		$this->data["controller"] = $this->router->fetch_class();
		$this->data["method"] = $this->router->fetch_method();
		$protected = array("register", "login", "two_step_login");
		if(!in_array($this->data["method"], $protected))
			$this->is_active_user();
		$this->load->model("B2b_model");
		$this->data["user_id"] = $this->session->userdata(SESSION_PREPEND."id");
		if(!is_null($this->data["user_id"]))
		{
			$user = $this->B2b_model->is_valid_user("", $this->data["user_id"]);
			$this->data["user_type"] = $user->user_type;
			$this->data["user_type_text"] = "b2b";
			$this->data["user_account"] = $user->acc_type;
			$this->data["user_img"] = $user->image_path;

		}
	}

	public function is_active_user()
	{
		if($this->session->userdata(SESSION_PREPEND."id") === null || $this->session->userdata(SESSION_PREPEND."id") === null)
			redirect("", "refresh");
	}

	public function index()
	{
		$this->load->view("b2b/index");
	}

	public function login()
	{
		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg" => "Please check entered details.", "id" => "");
			$this->form_validation->set_rules("email_id", "Email-Id", "trim|required|valid_email");
			$this->form_validation->set_rules("password", "Password", "trim|required");
			if ($this->form_validation->run() !== false)
			{
				$email_id = $this->input->post("email_id");
				$password = $this->input->post("password");
				$user = $this->B2b_model->is_valid_user($email_id);
				if($user !== false)
				{
					if($user->status === "1")
					{
						if($user->email_verified === "1")
						{
							$status = $this->general->validate_pass($user->password, $user->salt, $password);
							if($status !== false)
							{
								if($user->two_step_verification === "1")
								{
									//generate new verification code and send an email.
									$verification_code = $this->general->generate_random_key(11);
									$this->db->trans_begin();
									$result = $this->B2b_model->update_b2b($user->id, array("verification_code" => base64_encode($verification_code)));
									if($result !== false)
									{
										//send verification email
										//$mail_sent = $this->emails->send_verification_code($id, $verification_code);
										$mail_sent = true;
										if($mail_sent !== false)
										{
											$this->db->trans_commit();
											$response["status"] = "verify";
											$response["id"] = base64_encode($this->encrypt->encode($user->id));
										}
										else
										{
											$thid->db->trans_rollback();
											$response["msg"] = "Sorry, Failed to send verification code to your email. Try again.";
										}
									}
									else
									{
										$thid->db->trans_rollback();
										$response["msg"] = "Sorry, Failed to send verification code to your email. Try again.";
									}
								}
								else
								{
									$response["status"] = "true";
									$this->session->set_userdata(SESSION_PREPEND."id", $user->id);
									$this->session->set_userdata(SESSION_PREPEND."type", $user->user_type);
								}
							}
						}
						else
							$response["msg"] = "Please make email verification before login.<br><a href='".$user->url_string."'>Click here</a> to verify email.";
					}
					else
						$response["msg"] = "Please wait till administration approve your request.";
				}
				else
					$response["msg"] = "Email id and password mismatch.";
			}
			else
				$response["msg"] = "Enter proper Email id doesn't exits.";
			echo json_encode($response);exit;
		}
		redirect("", "refresh");
	}

	public function two_step_login()
	{
		$response = array("status" => "false", "msg" => "Please enter valid details");
		$id = intval($this->encrypt->decode(base64_decode($this->input->post("agent"))));
		if($id !== 0)
		{
			$this->form_validation->set_rules("verification_code", "Verification Code", "trim|required");
			if ($this->form_validation->run() !== false)
			{
				$user = $this->B2b_model->is_valid_user("", $id);
				$code = $this->input->post("verification_code");
				if($user !== false)
				{
					if($code === base64_decode($user->verification_code))
					{
						$response["status"] = "true";
						$this->session->set_userdata(SESSION_PREPEND."id", $id);
						$this->session->set_userdata(SESSION_PREPEND."type", $user->user_type);
					}
					else
						$response["msg"] = "Invalid varification code.";
				}
				else
					$response["msg"] = "Enter proper varification code.";
			}
			else
				$response["msg"] = "Enter proper varification code.";
		}
		echo json_encode($response);exit;
	}

	public function logout()
	{
		$this->session->unset_userdata(SESSION_PREPEND."id");
		$this->session->sess_destroy();
		redirect("", "refresh");
	}

	public function transaction()
	{	
		$data["balance"] = $this->B2b_model->get_my_balance($this->data["user_id"]);
		$this->load->view("b2b/transaction", $data);
	}

	public function get_credit_dtls()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/

		$aColumns = array("", "amount", "deposited", "used", "remaining", "settled");

		$iDisplayStart = $this->input->get("iDisplayStart", true);
		$iDisplayLength = $this->input->get("iDisplayLength", true);
		$iSortCol_0 = $this->input->get("iSortCol_0", true);
		$iSortingCols = $this->input->get("iSortingCols", true);
		$sSearch = $this->input->get("sSearch", true);
		$sEcho = $this->input->get("sEcho", true);

		// Limit
		$sLimit = " LIMIT 0, 25";
		if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] !== "-1")
			$sLimit = " LIMIT ".intval($_GET["iDisplayStart"]).", ".intval($_GET["iDisplayLength"]);

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
		if (isset($_GET["sSearch"]) && $_GET["sSearch"] !== "" && count($aColumns) > 0)
		{
			$sWhere = " WHERE (";
			for ($i = 0; $i < count($aColumns); $i++)
			{
				if ( isset($_GET["bSearchable_".$i]) && $_GET["bSearchable_".$i] === "true")
					if($aColumns[$i] !== "x")
						$sWhere .= $aColumns[$i]." LIKE '%".$_GET["sSearch"]."%' OR ";
			}
			if($sWhere !== " WHERE (")
			{
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ")";
			}
			else
				$sWhere = " ";
		}
		$credit_dtls = $this->B2b_model->get_tranx_details($this->data["user_id"], $sWhere, $sOrder, $sLimit);

		$iTotal = $credit_dtls['count'];
		$rResult = $credit_dtls['result'];

		// Output
		$output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
		);

		$i = isset($_GET["iDisplayStart"]) ? ($_GET["iDisplayStart"] * 1) + 1 : 1;
		$deposit_stats = array("0" => "Pending", "1" => "Accepted", "2" => "Rejected");
		foreach($rResult as $aRow)
		{
			$id = base64_encode($this->encrypt->encode($aRow["id"]));
			$row = array();
			$amount = explode(",", $aRow["amount"]);
			$credit_id = explode(",", $aRow["credit_id"]);
			$status = explode(",", $aRow["credit_status"]);
			$credit_type = explode(",", $aRow["credit_type"]);
			$deposited = explode(",", $aRow["deposited"]);

			$is_settle = false;
			$settle_key = count(array(TRANX_FORCE_SETTLEMENT_B2B, TRANX_REQUEST_FULL_SETTLEMENT_B2B)) > count(array_diff(array(TRANX_FORCE_SETTLEMENT_B2B, TRANX_REQUEST_FULL_SETTLEMENT_B2B), $credit_type)) ? (array_search(TRANX_FORCE_SETTLEMENT_B2B, $credit_type) !== false ? array_search(TRANX_FORCE_SETTLEMENT_B2B, $credit_type) : array_search(TRANX_REQUEST_FULL_SETTLEMENT_B2B, $credit_type)) : false;
			if($aRow["status"] !== "0")
			{
				if($settle_key !== false)
				{
					$is_settle = true;
					$settle_stat = $status[$settle_key];
				}
			}
			$cur_amount = $aRow["credited_amount"];
			$cur_deposit = $aRow["credit_deposited"];
			$cur_used = $aRow["acc_type"] !== B2B_CREDIT_ACC ? "------" : $aRow["used"];
			$cur_remain =  $aRow["acc_type"] !== B2B_CREDIT_ACC ? "------" : $aRow["remain"];
			$cur_settled =  $aRow["acc_type"] !== B2B_CREDIT_ACC ? "------" : $aRow["settled"];
			$cur_pending =  $aRow["acc_type"] !== B2B_CREDIT_ACC ? "------" : $aRow["pending"];
			
			$row[] = $i++;
			$row[] = $cur_amount;
			$row[] = $cur_deposit;
			$row[] = $aRow["tranx_id"];
			$row[] = $cur_used;
			$row[] = $aRow["acc_type"] !== B2B_CREDIT_ACC ? "------" : number_format(($cur_remain * -1), "6", ".", "");
			$row[] = $cur_settled;
			$row[] = $cur_pending;
			//$row[] = $aRow["tranx_id"];
			$row[] = $aRow["acc_type"] === B2B_CREDIT_ACC ? ($cur_pending > 0 ? "Settlement Pending" : "No Settlement Pending") : $deposit_stats[$aRow["status"]];
			$actions = "<div class='pull-left'>";
			if($aRow["acc_type"] === B2B_CREDIT_ACC)
			{
				$actions .= "<a class='btn btn-info btn-xs has-tooltip mrgn_top action_icons view_tranx_details' data-placement='top' title='Transaction Details' href='javascript:void(0);'><i class='fa fa-eye'></i></a>\n";
				if($cur_used > 0)
				{
					if($aRow["settlement"] === "0" && ($is_settle === false || $settle_stat !== "1"))
						$actions .= "<a class='btn btn-pending btn-xs has-tooltip mrgn_top action_icons make_settlement' data-placement='top' title='Settlement Amount (Pending)' href='javascript:void(0);'><i class='fa fa-money'></i></a>\n";
					elseif($aRow["settlement"] === "1")
						$actions .= "<a class='btn btn-success btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Settlement Amount (Accepted)' href='javascript:void(0);'><i class='fa fa-money'></i></a>\n";
					else
						$actions .= "<a class='btn btn-pending btn-xs has-tooltip mrgn_top action_icons make_settlement' data-placement='top' title='Settlement Amount (Failed / Cancelled)' href='javascript:void(0);'><i class='fa fa-money'></i></a>\n";
				}
			
			}
			$actions .= "</div>";
			$row[] = $aRow["acc_type"] === B2B_CREDIT_ACC ? $actions : "------";
			$row[] = $id;
			$row[] = $aRow["acc_type"] === B2B_CREDIT_ACC ? "1" : "0";
			$output["aaData"][] = $row;
		}

		echo json_encode($output);exit;
	}


	public function get_credit_tranx_stats()
	{
		$id = intval($this->encrypt->decode(base64_decode($this->input->get("transaction"))));
		$contents = "<table class='data-table-column-filter table table-bordered table-striped'><tr><th>Amount</th><th>Date/Time</th><th>Actions</th><th>Status</th></tr>";
		$contents .= "</table>";
		$response = array("status" => "0", "contents" => $contents);
		if($id !== 0)
		{
			$result = $this->B2b_model->get_credit_tranx_stats($id);
			if($result !== false)
			{
				$response["status"] = "1";
				$contents = "<table class='data-table-column-filter table table-bordered table-striped'><tr><th>Amount</th><th>Date/Time</th><th>Actions</th><th>Status</th></tr>";
				foreach ($result as $key)
					$contents .= "<tr><td>".$key->amount."</td><td>".$key->deposited."</td><td>".$key->name."</td><td>".($key->status === "0" ? "Pending" : ($key->status === "1" ? "Accepted" : "Cancelled"))."</td></tr>";
				$contents .= "</table>";
				$response["contents"] = $contents;
			}
		}
		echo json_encode($response);exit;
	}

	public function register_view()
	{
		$data['countries'] = $this->common->get_country_list();
		$my_country_code = "IN";
		$data['my_country_code'] = $my_country_code;
		$data['states'] = $this->common->get_regions($my_country_code);
		$this->load->view("b2b/register", $data);
	}

	public function register()
	{
		if(count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
			$this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]');
			$this->form_validation->set_rules('cpassword', 'Password Confirmation', 'trim|required|matches[password]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|required|numeric');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('postal_code', 'Postal / Zip code', 'trim|required|numeric');
			$image_name = "";
			if ($this->form_validation->run() != FALSE)
			{ 
				//check if the user is already registered
				$is_registered = $this->accounts->is_registered_b2b_email($this->input->post('email'));
				if($is_registered)
				{
					$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Your email id already registered. Please try login.");
					$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
					redirect("b2b/register_view");
				}
				else
				{
					$pass = $this->input->post('password');
		            $generate = $this->general->encrypt_pass($pass);

					$user_data = array("email" => $this->input->post("email"),
								'salt' => $generate['salt'],
								'password' => $generate['password'],
								'firstname' => $this->input->post('fname'),
								'lastname' => $this->input->post('lname'),
								"dob" => date("Y-m-d", strtotime($this->input->post("dob"))),
								"address" => $this->input->post("address"),
								"ship_address" => $this->input->post("address"),
								"contact_no" => $this->input->post("contact_no"),
								"city" => $this->input->post("city"),
								"state" => $this->input->post("state"),
								"country" => $this->input->post("country"),
								"postal_code" => $this->input->post("postal_code"),
								"register_date" => date("Y-m-d H:i:s"),
								"status" => 1,
								"updated_on" => date("Y-m-d H:i:s"));
					if(isset($_FILES)&& $_FILES['user_image']['error'] === 0)
					{
						$this->load->library('upload');
						$config['upload_path'] = IMAGE_UPLOAD_PATH.'/b2b/images/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
						$config['overwrite']     = FALSE;
						$config['encrypt_name'] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload('user_image');
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$user_data['profile_photo'] = '/b2b/images/'.$simg['file_name'];
						}
					}
					$this->db->trans_begin();
					$this->accounts->create_b2b($user_data);
					$user_data = $this->accounts->get_b2b_by_email($this->input->post('email'));
					$user_id = $user_data->id;
		            //add an empty verification row in user verifications table.
		            $this->accounts->add_b2b_verification($user_id);
		            $sent_mail = $this->send_verification_mail($user_id, 'Registration', $pass);
		            if($sent_mail === true)
		            {
		            	$this->db->trans_commit();
						$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Thank you for registering with us. Please try login.");
						$this->session->set_flashdata(SESSION_PREPEND."notification_status", "success");
					}
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Registration failed. Please try login.");
						$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
					}
					redirect("user/register_view");
				}
			}
		}
		else
			redirect();
	}

	/* User home page (dashboard) */
	public function dashboard()
	{
		$this->load->view("b2b/index");
	}


	/*b2b user forgot password*/
	public function forgot_password(){
		if($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('email', 'Email-Id', 'trim|required|valid_email');

			if($this->form_validation->run() != FALSE)
			{
				$email = $this->input->post('email');
				$count = $this->accounts->is_registered_b2b_email($email);
				if($count === true)
				{
					$userInfo = $this->accounts->get_b2b_by_email($email);
					if($userInfo->status === "1")
					{
						$status = $this->send_forgot_password_mail($email);
						if($status)
						{
							$response = array(
											'status' => 1,
											'msg' => "A verification link to reset your password has been sent to ".$email."!"
										);
						}
						else
						{
							$response = array(
											'status' => 0,
											'msg' => "Unable to send verification link to ".$email.". Please try again!"
										);
						}

					}
					else
					{
						$response = array(
										'status' => 0,
										'msg' => "This email id has not been activated."
									);
					}

				}
				else
				{
					$response = array(
									'status' => 0,
									'msg' => 'This is not a registered email address.'
								);
				}

			}
			else
			{
				$response = array(
								'status' => 0,
								'msg' => 'Please provide valid input details!'
							);
			}

			echo json_encode($response);
		}
	}

	/*mail verification link for new b2b user*/
	public function send_verification_mail($b2b_id, $email_type, $password) {
		$this->load->library("Send_emails");
		$data['user_data'] = $user_data = $this->accounts->get_user_by_id($b2b_id);
		$data['password'] = $password;        
		$data['email_template'] = $this->email_template->get_email_template($email_type);
		$email = $user_data->email;
		$key = $this->general->generate_random_key();
		$secret = md5($this->encrypt->encode($email));
		$this->accounts->update_b2b_pwd_reset($b2b_id, $key, $secret);
		$data['confirm_link'] = base_url('verification/b2b_email/'.$key.'/'.$secret);
		return $this->send_emails->registered_mail($data);

	}

	/*forget password mail for b2b user*/
	public function get_mail_content_forgotpass($email) {
		$this->load->library("Send_emails");
		$data['user_data'] = $this->accounts->get_b2b_by_email($email);
		$email_type = 'Forgot Password';
		$data['email_template'] = $this->email_template->get_email_template($email_type);

		$key = $this->common->generate_random_key();
		$secret = md5($this->encrypt->encode($email));
		$user_id = $data['user_data']->id;
		$this->accounts->update_b2b_pwd_reset($user_id, $key, $secret);
		$data['reset_link'] = base_url('b2b/set_password/'.$key.'/'.$secret);
		return $this->send_emails->forgot_password_mail($data);
	}

}