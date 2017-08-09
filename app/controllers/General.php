<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Common_model", "common");
	}

	public function index()
	{
		die;
	}

	public function get_states($country_code)
	{
		$states = $this->common->get_regions($country_code);
		$response = "<option selected value=''>".$this->lang->line("select_region")."</option>";
		if($states !== false)
			foreach($states as $state)
				$response .= "<option value='".$state->country.":::".$state->region."'>".$state->name."</option>";
		echo json_encode(array("result" => $response));exit;	
	}

	public function get_cities($region_codes)
	{
		$region_codes = explode(":::", urldecode($region_codes));
		$cities = $this->common->get_cities($region_codes[1], $region_codes[0]);
		$response = "<option selected value=''>".$this->lang->line("select_city")."</option>";
		if($cities !== false)
			foreach($cities as $city)
				$response .= "<option value='".$city->id."'>".$city->city."</option>";
		echo json_encode(array("result" => $response));exit;	
	}

	public function get_cities_country()
	{	
		$region_codes = $_GET['term'];
		$city_ids = '';
		if(isset($_GET['city_ids'])){
			$city_ids = $_GET['city_ids'];
			$cities = $this->common->get_cities_list($region_codes,$city_ids,$this->data['default_language']);
		}else{
			$cities = $this->common->get_cities_list($region_codes,'',$this->data['default_language']);
		}
		
		$response= array();
		if($cities){
			foreach ($cities as $rows) {
				$response[] = array(
                        'value' => $rows->city.', '.$rows->country,
						'id_value'=>$rows->id
                    );
			}
			echo json_encode($response);
		}	
		exit;	
	}

	public function special_deal_request(){
		error_reporting(E_ALL);
		// echo '<pre>',print_r($this->input->post());exit; 

		/*$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{   
			$post = $this->input->post();
			$response["title"] = "Success";

			$data = array('email' => $post['email'], 'phone' => $post['phone'], 'created_date' => date("Y-m-d H:i:s"));
			$this->load->model("Email_model");
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

			$template = $this->Email_model->get_template("", "simple_email");

			if($template !== false)
			{
				$to_email = $post['email'];
				
				$message = $template->message;

				$text1 = 'Regards from 10020.ir. We will get back to you soon.';
				$text2 = 'New special deal request from 10020.ir.Details </br><b>Details</b><p>Email : '.$post['email'].'</p><p>Phone : '.$post['phone'].'</p>';

				$message1 = str_replace("{%%MESSAGE%%}", $text1, $message);
				$message2 = str_replace("{%%MESSAGE%%}", $text2, $message);

				$subject = '10020.ir special deal request notification';

				$email_from = $template->email_from;
				$email_from_name = $template->email_from_name;
				$to_bcc = explode(",", $template->to_email);
				//pr($to_email);exit;
				$sendmail = $this->send_mail($subject, $message1, $email_from, $email_from_name, $to_email, $to_bcc);

				if($sendmail !== false){
					$save = $this->common->set_special_deal_request($data);

					$this->send_mail($subject, $message2, $email_from, $email_from_name, $to_bcc, '');

					$response = array("status" => "success", "msg_status" => "success", "title" => "Success", "msg" => "Successfully sent request.");
				}
			}

		}
		echo json_encode($response);exit;*/

		error_reporting(E_ALL);
		//echo "came here";exit();
		// echo '<pre>',print_r($this->input->post());exit; 

		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{   
			$post = $this->input->post();
			$response["title"] = "Success";

			$data = array('email' => $post['name'], 'phone' => $post['phone'], 'created_date' => date("Y-m-d H:i:s"));
			/*$this->load->model("Email_model");
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

			$template = $this->Email_model->get_template("", "simple_email");*/

			// if($template !== false)
			// {
				$to_email = $post['name'];
				
				//$message = $template->message;

				$text1 = 'Regards from 10020.ir. We will get back to you soon.';
				$text2 = 'New special deal request from 10020.ir.Details </br><b>Details</b><p>Email : '.$post['name'].'</p><p>Phone : '.$post['phone'].'</p>';

				/*$message1 = str_replace("{%%MESSAGE%%}", $text1, $message);
				$message2 = str_replace("{%%MESSAGE%%}", $text2, $message);

				$subject = '10020.ir special deal request notification';

				$email_from = $template->email_from;
				$email_from_name = $template->email_from_name;
				$to_bcc = explode(",", $template->to_email);*/
				//pr($to_email);exit;
				//$sendmail = $this->send_mail($subject, $message1, $email_from, $email_from_name, $to_email, $to_bcc);

				//if($sendmail !== false){
					$save = $this->common->set_special_deal_request($data);

					 //$this->send_mail($subject, $message2, $email_from, $email_from_name, $to_bcc, '');

				     $othervalue = $this->lang->line("successfully_sent");
					 
					 $response = array("status" => "success", "msg_status" => "success", "title" => "Success", "msg" => $othervalue);
				//}
			//}

		}



		echo json_encode($response);exit;

	}

	private function send_mail($subject, $msg, $from, $from_name, $to, $bcc, $attchments = null)
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
			echo $this->email->print_debugger();exit;
		return false;
	}

        public function latestNews(){

		$id = $_POST['id'];
		
		$response = $this->common->getNewscontent($id);
		//echo "<pre>";print_r($response);exit();
		$lang = $_SESSION['default_language'];

		$conten = 'content_'.$lang;
		
		$con = $response[0]->$conten;
		echo $con;exit();

		

	}


}
