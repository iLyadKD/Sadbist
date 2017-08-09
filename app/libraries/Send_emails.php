<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Send_Emails {

	private $CI;
    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->model('Email_model');
        $email_access = $this->CI->Email_model->get_email_acess();
        $config = Array(
                        'protocol' => $email_access->smtp,
                        'smtp_host' => $email_access->host,
                        'smtp_port' => $email_access->port,
                        'smtp_user' => $email_access->username,
                        'smtp_pass' => $email_access->password,
                        'mailtype' => 'html',
                        'charset' => 'iso-8859-1',
                        'wordwrap' => TRUE
                    );
        $this->CI->load->library('email', $config);
    }

	public function registered_mail($data)
	{
		$message = $data['email_template']->message;
		$delimiters = $data['email_template']->to_email;
		$email_to = explode(";", $delimiters);
		$email_to_1 = $email_to[0];
		if($data['user_data']->profile_photo === '')
			$profile_photo = base_url(IMAGE_UPLOAD_PATH_ABS.'/b2c/images/default.png');
		else
			$profile_photo = base_url(IMAGE_UPLOAD_PATH_ABS.$data['user_data']->profile_photo);
		$message = str_replace("{%%FIRSTNAME%%}", $data['user_data']->firstname, $message);
		$message = str_replace("{%%USERNAME%%}", $data['user_data']->email, $message);
		$message = str_replace("{%%Email%%}", $data['user_data']->email, $message);
		$message = str_replace("{%%WEB_URL%%}", base_url(), $message);
		$message = str_replace("{%%TITLE%%}", TITLE_10020, $message);
		$message = str_replace("{%%PASSWORD%%}", $data['password'], $message);
		$message = str_replace("{%%USERIMAGE%%}", $profile_photo, $message);
		$message = str_replace("{%%CONFIRMLINK%%}", $data['confirm_link'], $message);

		$this->CI->email->set_newline("\r\n");
		$this->CI->email->from($data['email_template']->email_from, $data['email_template']->email_from_name);
		$this->CI->email->to($data['user_data']->email, $email_to_1);
		$this->CI->email->subject($data['email_template']->subject);
		$this->CI->email->message($message);
		if($this->CI->email->send())
			return true;
		return false;
	}

}