<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Sendmail_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Email_model");
		// $access = new stdClass();
		// $access->smtp = "smtp";
		// $access->host = "ssl://smtp.gmail.com";
		// $access->port = "465";
		// $access->username = "mayur2074.provab@gmail.com";
		// $access->password = "provab@2074";
		$access = $this->Email_model->get_access();
		$config['protocol'] = $access->smtp;
		$config['smtp_host'] = $access->host;
		$config['smtp_port'] = $access->port;
		$config['smtp_user'] = $access->username;
		$config['smtp_pass'] = $access->password;
		$config['wordwrap']     = FALSE;
		$config['mailtype']     = "html";
		$config['charset']      = "UTF-8";
		$config['crlf']         = "\r\n";
		$config['newline']      = "\r\n";
		$this->load->library("email", $config);
	}


	function test_mail()
	{
		$email = "mayur2074.provab@gmail.com";
		$new_pass = "New Password";
		$this->email->from('admin@provab.com', 'Provab');
		$to=trim($email);
		$this->email->to($to); 
		$this->email->subject("Forgot Password request");
		$message1 = "<html><body><h1>Your paswword is ".$new_pass."</h1></body></html>";
		$this->email->message($message1);           
		if($this->email->send())
			echo "sent";
		else
			echo $this->email->print_debugger();
	}


	public function forgot_password($data)
	{
		$template = $this->Email_model->get_template("", "forgot_password");
		if($template !== false)
		{
			$admin = $data["admin"];
			$message = $template->message;
			$message = str_replace("{%%SALUTATION%%}", $admin->firstname, $message);
			$message = str_replace("{%%CODE%%}", $data["verify_code"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);

			$to_email = $admin->email_id;

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	public function admin_register($data)
	{
		$template = $this->Email_model->get_template("", "admin_registration");
		if($template !== false)
		{
			$to_email = $data["emailid"];
			
			$message = $template->message;
			$message = str_replace("{%%TITLE%%}", PROJECT_NAME, $message);
			$message = str_replace("{%%SALUTATION%%}", $data["firstname"], $message);
			$message = str_replace("{%%USERNAME%%}", $data["emailid"], $message);
			$message = str_replace("{%%PASSWORD%%}", $data["password"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("{%%LOGIN_URL%%}", $data["url"], $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	public function b2c_register($data)
	{
		$template = $this->Email_model->get_template("", "b2c_registration_confirmed");
		
		if($template !== false)
		{
			$to_email = $data["email_id"];
			
			$message = $template->message;
			$message = str_replace("{%%TITLE%%}", PROJECT_NAME, $message);
			$message = str_replace("{%%SALUTATION%%}", $data["email_id"], $message);
			$message = str_replace("{%%USERNAME%%}", $to_email, $message);
			$message = str_replace("{%%PASSWORD%%}", $data["password"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("{%%LOGIN_URL%%}", $data["url"], $message);
			$message = str_replace("datasrc=", "src=", $message);
			//pr($message);exit;
			$subject = str_replace("{%%TITLE%%}", PROJECT_NAME, $template->subject);
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);
			//pr($to_email);exit;
			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	public function b2b_register($data)
	{
		$template = $this->Email_model->get_template("", "b2b_registration");
		if($template !== false)
		{
			$to_email = $data["email_id"];
			
			$message = $template->message;
			$message = str_replace("{%%TITLE%%}", PROJECT_NAME, $message);
			$message = str_replace("{%%SALUTATION%%}", $data["firstname"], $message);
			$message = str_replace("{%%USERNAME%%}", $to_email, $message);
			$message = str_replace("{%%PASSWORD%%}", $data["password"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("{%%LOGIN_URL%%}", $data["login_url"], $message);
			$message = str_replace("{%%CONFIRMLINK%%}", $data["confirm_url"], $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	public function account_status($data)
	{
		$template = $this->Email_model->get_template("", "account_status");
		if($template !== false)
		{
			$user = $data["user"];
			$to_email = $user->email_id;

			$message = $template->message;
			$message = str_replace("{%%SALUTATION%%}", $user->firstname, $message);
			$message = str_replace("{%%STATUS%%}", $data["status"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);
			$message = str_replace("{%%ACCOUNT%%}", $data["account"], $message);

			$subject = str_replace("{%%STATUS%%}", $data["status"], $template->subject);
			$subject = str_replace("{%%ACCOUNT%%}", $data["account"], $subject);

			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);			
		}
		else
			return "TNF";
	}

	function simple_mail($data)
	{
		$template = $this->Email_model->get_template("", "simple_email");
		if($template !== false)
		{
			$message = $data["message"];
			$to = $data["to"];
			$subject = $data["subject"];
			$attchments = isset($data["attchments"]) ? $data["attchments"] : null;
			$to_email = is_array($to) ? $to : explode(",", trim($to));
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);
			$message = str_replace("{%%MESSAGE%%}", $message, $template->message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = !empty($subject) ? $subject : $template->subject;
			
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc, $attchments);
		}
		else
			return "TNF";
	}

	function promocode($data)
	{
		$template = $this->Email_model->get_template("", "promocode");
		if($template !== false)
		{
			$promo_types = array("1" => "Discount by %", "2" => "Discount by $");
			$message = $template->message;
			$to = $data["to"];
			$salutation = $data["salutation"];
			$promocode = $data["promocode"];
			$to_email = is_array($to) ? $to : explode(",", trim($to));
			$message = str_replace("{%%PROMOCODE%%}", $promocode->code, $template->message);
			$message = str_replace("{%%DISCOUNT%%}", $promocode->discount, $message);
			$message = str_replace("{%%SALUTATION%%}", $salutation, $message);
			$message = str_replace("{%%DISCOUNT_TYPE%%}", $promo_types[$promocode->type], $message);
			$message = str_replace("{%%CONDITION_AMOUNT%%}", $promocode->condition, $message);
			$message = str_replace("{%%CURRENCY%%}", "$", $message);
			$message = str_replace("{%%EXPIRY_DATE%%}", date("dS M Y",strtotime($promocode->valid_to)), $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = !empty($subject) ? $subject : $template->subject;
			
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	public function password_update($data)
	{
		$template = $this->Email_model->get_template("", "password_update");
		if($template !== false)
		{
			$user = $data["user"];
			$to_email = $user->email_id;

			$message = $template->message;
			$message = str_replace("{%%SALUTATION%%}", $user->firstname, $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("{%%ACCOUNT%%}", $data["account"], $message);
			$message = str_replace("datasrc=", "src=", $message);
			$message = str_replace("{%%NEW_PASSWORD%%}", $data["new_pass"], $message);
			$message = str_replace("{%%MODIFIED_BY%%}", $data["modified_by"], $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	function subscribe($data)
	{
		$template = $this->Email_model->get_template("", "subscribe");
		if($template !== false)
		{
			$to = $data["to"];
			$to_email = is_array($to) ? $to : explode(",", trim($to));
			$message = str_replace("{%%UNSUBSCRIBE%%}", $data["unsubscribe_link"], $template->message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%TITLE%%}", PROJECT_NAME, $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	function unsubscribe($data)
	{
		$template = $this->Email_model->get_template("", "unsubscribe");
		if($template !== false)
		{
			$to = $data["to"];
			$to_email = is_array($to) ? $to : explode(",", trim($to));
			$message = str_replace("{%%UNSUBSCRIBED%%}", $data["unsubscribed"], $template->message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%TITLE%%}", PROJECT_NAME, $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}

	public function admin_deposit($data)
	{
		$template = $this->Email_model->get_template("", "admin_deposit");
		if($template !== false)
		{
			$user = $data["user"];
			$to_email = $user->email_id;

			$message = $template->message;
			$message = str_replace("{%%SALUTATION%%}", $user->firstname, $message);
			$message = str_replace("{%%STATUS%%}", $data["status"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);
			$message = str_replace("{%%AMOUNT%%}", $data["amount"], $message);
			$message = str_replace("{%%CURRENCY%%}", $data["currency"], $message);
			$message = str_replace("{%%REASON%%}", $data["reason"], $message);

			$subject = str_replace("{%%STATUS%%}", $data["status"], $template->subject);

			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);			
		}
		else
			return "TNF";
	}

	public function deposit_request($data)
	{
		$template = $this->Email_model->get_template("", "deposit_request");
		if($template !== false)
		{
			$user = $data["user"];
			$to_email = $user->email_id;

			$message = $template->message;
			$message = str_replace("{%%SALUTATION%%}", $user->firstname, $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);			
		}
		else
			return "TNF";
	}

	public function deposit_response($data)
	{
		$template = $this->Email_model->get_template("", "deposit_response");
		if($template !== false)
		{
			$user = $data["user"];
			$to_email = $user->email_id;

			$message = $template->message;
			$message = str_replace("{%%SALUTATION%%}", $user->firstname, $message);
			$message = str_replace("{%%STATUS%%}", $data["status"], $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("datasrc=", "src=", $message);
			$message = str_replace("{%%ACCEPT_MORE%%}", $data["accept_more"], $message);

			$subject = $template->subject;
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);

			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);			
		}
		else
			return "TNF";
	}

	function send_mail($subject, $msg, $from, $from_name, $to, $bcc, $attchments = null)
	{
		$this->email->from($from, $from_name);
		$this->email->to($to);
		if(!empty($bcc))
			$this->email->bcc($bcc);
		$this->email->subject($subject);
		$this->email->message($msg);
		if(!is_null($attchments))
			foreach ($attchments as $attchment)
				$this->email->attach($attchment);
		if($this->email->send())
			return true;
		else
			//echo $this->email->print_debugger();exit;
		return false;
	}
}