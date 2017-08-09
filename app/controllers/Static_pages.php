<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Static_Pages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		redirect("static_pages/load/contact-us");
	}

	public function load($page = "contact-us")
	{
		$data = array();
		$page_name = str_replace("-", "_", $page);
		if($page === STATIC_CONTACT_SLUG)
		{
			$data["contact_address"] = $this->general->default_contact_address($this->data["default_language"]);
			$data["contact_location"] = $this->general->contact_locations($this->data["default_language"]);
		}
		$this->load->view("static/".$page_name, $data);
	}

	public function load_page($page = "")
	{     
		if($page === "")
			redirect();
		else
		{
			$data["page"] = $this->general->static_page_by_id($page, $this->data["default_language"]);

			if(empty($data["page"]) === true)
				redirect("", "refresh");
			else
				$this->load->view("static/page", $data);
		}
	}
	
	public function contact_us()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("name", "Name", "trim|required");
			$this->form_validation->set_rules("email", "Email", "trim|required");
			$this->form_validation->set_rules("message", "Message", "trim|required");
			$response["title"] = "Contact Us";
			if($this->form_validation->run() !== false)
			{
				pr($this->input->post());exit;
				$response["status"] = "true";
				$response["msg_status"] = "success";
				$response["msg"] = "Password reset successful. Please login with new password.";
			}
			else
				$response["msg"] = "Please enter valid verification code.";
		}
		echo json_encode($response);exit;
	}
	
	
}
