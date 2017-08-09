<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verification extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Accounts_model', 'accounts');
		$this->load->model('Verify_model', 'verify_model');
	}

	/* make sure to not display error message if link is altered */
	public function index()
	{
		$this->load->view("index");
	}

	/*verification from mail for b2c user*/
	public function b2c_email($key, $secret)
	{
		$notification_msg = 'The link you followed is no longer valid. You can attempt sending another verification email from your dashboard.';
		$notification_status = "danger";
		if($key == '' || $secret == '')
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $notification_msg);
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", $notification_status);
			if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
				redirect();
			else
				redirect("b2c/dashboard");
		}
		else
		{
			$key_details = $this->accounts->is_valid_b2c_secret($key, $secret);
			if($key_details->num_rows() === 1)
			{
				$user_data = $key_details->row();
				$b2c_id = $user_data->id;
				$verify_details = $this->verify_model->check_b2c_verfication($b2c_id);
				if(($this->session->userdata(SESSION_PREPEND."id") !== false && $this->session->userdata(SESSION_PREPEND."id") !== null && $this->session->userdata(SESSION_PREPEND."id") === $b2c_id) || ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null))
				{
					if($verify_details->num_rows() === 1)
					{
						$verify_row = $verify_details->row();
						if($verify_row->email === "1")
						{
							$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Your email has been already confirmed.");
							$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
							redirect();
						}
						else
						{
							$update = array('email' => '1');
							$this->verify_model->update_b2c_verfication($b2c_id,$update);
							$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Your email has been verified successfully.");
							$this->session->set_flashdata(SESSION_PREPEND."notification_status", "success");
							if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
							{
								$this->session->set_userdata(SESSION_PREPEND."id", $user_data->id);
								$this->session->set_userdata(SESSION_PREPEND."email", $user_data->email);
								$this->session->set_userdata(SESSION_PREPEND."type", "B2C");
							}
						}
						redirect("b2c/dashboard");
					}

					
				}
				else
				{
					$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Someone is already logged in. Please logout and try again. Or this link is not provided to you.");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", $notification_status);
				if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
					redirect();
				else
					redirect("b2c/dashboard");
				}			
			}
			else
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $notification_msg);
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", $notification_status);
				if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
					redirect();
				else
					redirect("b2c/dashboard");
			}
		}
	}
	/*verification from mail for b2b user*/
	public function b2b_email($key, $secret)
	{
		$notification_msg = 'The link you followed is no longer valid. You can attempt sending another verification email from your dashboard.';
		$notification_status = "danger";
		if($key == '' || $secret == '')
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $notification_msg);
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", $notification_status);
			if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
				redirect();
			else
				redirect("b2b/dashboard");
		}
		else
		{
			$key_details = $this->accounts->is_valid_b2b_secret($key, $secret);
			if($key_details->num_rows() === 1)
			{
				$user_data = $key_details->row();
				$b2b_id = $user_data->id;
				$verify_details = $this->verify_model->check_b2b_verfication($b2b_id);
				if(($this->session->userdata(SESSION_PREPEND."id") !== false && $this->session->userdata(SESSION_PREPEND."id") !== null && $this->session->userdata(SESSION_PREPEND."id") === $b2b_id) || ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null))
				{
					if($verify_details->num_rows() === 1)
					{
						$verify_row = $verify_details->row();
						if($verify_row->email === "1")
						{
							$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Your email has been already confirmed.");
							$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
							redirect();
						}
						else
						{
							$update = array('email' => '1');
							$this->verify_model->update_b2b_verfication($b2b_id,$update);
							$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Your email has been verified successfully.");
							$this->session->set_flashdata(SESSION_PREPEND."notification_status", "success");
							if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
							{
								$this->session->set_userdata(SESSION_PREPEND."id", $user_data->id);
								$this->session->set_userdata(SESSION_PREPEND."email", $user_data->email);
								$this->session->set_userdata(SESSION_PREPEND."type", "B2B");
							}
						}
						redirect("b2b/dashboard");
					}

					
				}
				else
				{
					$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Someone is already logged in. Please logout and try again. Or this link is not provided to you.");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", $notification_status);
				if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
					redirect();
				else
					redirect("b2b/dashboard");
				}			
			}
			else
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $notification_msg);
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", $notification_status);
				if ($this->session->userdata(SESSION_PREPEND."id") === false || $this->session->userdata(SESSION_PREPEND."id") === null)
					redirect();
				else
					redirect("b2b/dashboard");
			}
		}
	}
}