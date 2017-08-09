<?php if ( ! defined('BASEPATH')) exit('No getAllDatadirect script access allowed');

class Sms extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Sms_model');
		$this->data["page_title"] = "SMS Template Management";
		$this->data["page_main_title"] = "SMS Template Management";	
    }
	
	public function index(){
		$data['result'] =$this->Sms_model->getAllData();
		$this->load->view('sms/index', $data);

	}

	public function add(){
		if($this->input->server("REQUEST_METHOD")=="POST"){ 
			$data = $this->input->post();
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");

			$this->form_validation->set_rules("sms_type", "Type", "trim|required|is_unique[sms_templates.sms_type]",array('is_unique' => 'This SMS Type already exists'));
			$this->form_validation->set_rules("sms_content", "Content", "trim|required");
			$this->form_validation->set_error_delimiters('', '');
	
			if($this->form_validation->run() !== false)
			{
				
				try
				{
					$result = $this->Sms_model->save($data);
					if($result !== false)
					{		
							$response["title"] = "success";
							$response["msg"] = "Template added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						
					}
					else
					{
						$response["msg"] = "Failed to add new template.";
					}
				}
				catch(Exception $ex)
				{
					$response["msg"] = "Sorry, Operation failed.";
				}
			}
			else{
				$response["msg"] = validation_errors();
			}
			echo json_encode($response);exit;
			
		} 	
		$this->data['page_title']="Add SMS Template";
		$this->load->view('sms/add',array());
	}

	public function edit($id){
		if($this->input->server("REQUEST_METHOD")=="POST"){ 
			$this->form_validation->set_rules("tour_type", "Tour Type", "trim|required");
	
			if($this->form_validation->run() !== false)
			{
				$data = array(
					'package_type' => $this->input->post('tour_type')
				);
				
				$this->Package_type_model->update($id,$data);
				$this->session->set_flashdata('message', 'Sucessfully Updated');
				redirect('package_type', 'refresh');
			}
		}
		
		$data['title']="Edit Package Type";
		$data['row'] = $this->Package_type_model->get_package_type_id($id);
		$data['id']  =$id;
		$this->load->view('package_type/edit', $data);
	}

	public function view($id){
		$data['row'] = $this->Package_type_model->get_package_id($id);
		$data['id']  =$id;
		$this->load->view('package_type/view', $data);
	}

	public function sms_status(){
		$post = $this->input->post();
		$id     = $post['id'];
		$status = $post['status'];

		$data = array('sms_status'=>$status);

		$response = array("status" => "false", "msg_status" => "danger", "title" => "Failed", "msg" => "Invalid Operation.");

		$update = $this->Sms_model->updateStatus($id,$data);
		if($update > 0 ){

			$response["status"] = "true";
			$response["title"]  = "Success";
			$response["msg"] = $status === "0" ? "template deactivated successfully." : "template activated successfully.";
			$response["msg_status"] = $status === "0" ? "info" : "success";
		}	
		echo json_encode($response);exit;
	}

	public function sms_delete(){
		$post = $this->input->post();
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Failed", "msg" => "Invalid Operation.");
		$delete = $this->Sms_model->delete($post);	
		if($delete > 0){
			$response["status"] 	= "true";
			$response["title"]  	= "Success";
			$response["msg"]    	= "template deleted.";
			$response["msg_status"] = "success";
		}
		echo json_encode($response);exit;
	}

	
}