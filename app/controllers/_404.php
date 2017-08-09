<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class _404 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	
		$this->data["default_language"] = "en";
		$this->data["page_title"] = "Page not found";
		$this->data["controller"] = $this->router->fetch_class();
		$this->data["method"] = $this->router->fetch_method();
		$this->load->model("B2b_model");
		$this->load->model("B2c_model");
		$this->data["user_id"] = $this->session->userdata(SESSION_PREPEND."id");
		if(!is_null($this->data["user_id"]) && !is_null($this->session->userdata(SESSION_PREPEND."type")))
		{
				$user = $this->B2c_model->is_valid_user("", $this->data["user_id"]);
				$this->data["user_type"] = $user->user_type;
				$this->data["user_type_text"] = "b2c";
				$this->data["user_img"] = $user->image_path;

		}
	}

	public function index()
	{	
		$this->load->view("_404");
	}
}
