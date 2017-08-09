<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#########################################################################
	This file is coded and updated by Muhammed Riyas[riyas.provab@gmail.com].
	#########################################################################
*/

class Discount extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Discount_model");
		$this->data["page_main_title"] = "Discount Management";
		$this->data["page_title"] = "Discount Management";
	}

	
	public function index()
	{	
		$data['discount'] = $this->Discount_model->getData();

		$this->load->view('discount/add',$data);


	}

	public function add(){
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
// echo '<pre>',print_r($this->input->post());exit; 
		if($this->input->is_ajax_request())
		{
			$data = array('amount' => $this->input->post('discount_price'));

			$update = $this->Discount_model->addDiscount($data);

			$response["status"] = "true";
			$response["msg"] = "Discount price updated successfully";
			$response["msg_status"] ="success";
			$response["title"] ="Success";
		}
		
		echo json_encode($response);exit;	
	}

}	