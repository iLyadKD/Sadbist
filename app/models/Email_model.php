<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_Model extends CI_Model {

	public function get_email_template($email_type)
	{
		$this->db->where('email_type', $email_type);
		$query = $this->db->get('email_template');
		return $query->row();
	}

	public function get_email_acess()
	{
		$query = $this->db->get('email_access');
		return $query->row();
	}
	
	public function get_template($id, $email_type = null, $status = false)
	{
		$where = "`id` = '$id'";
		if(!is_null($email_type))
			$where = "`email_type` = '$email_type'";
		if($status === true)
			$where .= " AND `status` = '1'";
		$query = "SELECT * FROM `email_template` WHERE $where";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	public function get_access()
	{
		$query = "SELECT * FROM `email_access`";
		$result = $this->db->query($query);
		$response = json_decode(json_encode(array("id" => "1", "smtp" => "", "host" => "", "port" => "", "username" => "", "password" => "")));
		if($result->num_rows() > 0)
		{
			$response = $result->row();
			$response->id = base64_encode($this->encrypt->encode($response->id));
			$response->password = $this->encrypt->decode(base64_decode($response->password));
		}
		return $response;
	}
	
	public function forgot_password($data)
	{
		$template = $this->get_template("", "forgot_password");
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