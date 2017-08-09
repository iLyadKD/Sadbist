<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotel extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		unset_page_cache();
		$this->data["default_language"] = !empty($this->session->userdata('default_language')) ? $this->session->userdata('default_language') : "en";
		$this->data["page_title"] = "Home";
		$this->data["controller"] = $this->router->fetch_class();
		$this->data["method"] = $this->router->fetch_method();
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
	{ 	error_reporting(E_ALL);
		$this->session->set_userdata(array('lang'=>$this->data["default_language"]));
		$data["deals"] = $this->Common_model->get_deals($this->data["default_language"]);
		$data["tour_deals"] = $this->Common_model->get_deals($this->data["default_language"],'tour_deals');
		$data["hotel_deals"] = $this->Common_model->get_deals_hotels($this->data["default_language"],'hotel_deals');
		$data["latest_news"] = $this->Common_model->get_latest_news($this->data["default_language"]);
		$data["national_attractions"] = $this->Common_model->get_attractions($this->data["default_language"]);

		$data["currency_val"] = $this->currency_converter->convert($this->data["default_currency"], "IRR");

		$this->load->model("Flight_model");
		$temp_res = $this->Flight_model->get_airports(array(DEFAULT_FLIGHT_ORIGIN,DEFAULT_FLIGHT_DESTINATION), $this->data["default_language"]);
		$locations = array();
		if($temp_res !== false)
			foreach ($temp_res as $obj)
				$locations[$obj->airport_code] = $obj;
		$data["airports"] = $locations; 
		$this->load->view("index", $data);
	}
	
	public function dynamic()
	{
		$this->load->view("hotel/dynamic");
	}

 	public function language() {
		$return=0;
		$post=$this->input->post('lang');
		if($post==''){
		}else{
		    $this->session->set_userdata('default_language',$post);
		     $return=1;
		}
		echo $return;exit;
  	}

  	public function currency() {
		$return=0;
		$post=$this->input->post('currency');
		if($post==''){
		}else{
		    $this->session->set_userdata('default_language',$post);
		     $return=1;
		}
		echo $return;exit;
  	}


  public function result()
	{
		$this->load->view("hotel/result");
	}
	public function details()
	{
		$this->load->view("hotel/details");
	}
	public function prebook()
	{
		$this->load->view("prebook");
	}
}
