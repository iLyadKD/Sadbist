<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#########################################################################
	This file is coded and updated by Muhammed Riyas[riyas.provab@gmail.com].
	#########################################################################
*/
class Special_deals extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Special_deal_model");
		$this->data["page_main_title"] = "Special Deal Requests";
		$this->data["page_title"] = "Special Deal Requests";
	}

	/*
	* Call center dashboard 
	*/	
	public function index()
	{	
		$data['result'] = $this->Special_deal_model->getAllItems(); 
// echo '<pre>',print_r($data);exit; 
		$this->load->view("special_deal/index",$data);
	}

}