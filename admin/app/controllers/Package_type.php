<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Package_type extends CI_Controller {

	public function __construct(){
		parent::__construct(); 
		$this->data["default_language"] = "en";
		$this->data["page_main_title"] = "Tours";
		$this->data["page_title"] = "Tours Type";
		$this->load->model('Package_type_model');	
    }
	
	public function index(){
		$data['result'] =$this->Package_type_model->get_package_type();
		$this->load->view('package_type/index', $data);

	}

	public function add(){
		if($this->input->server("REQUEST_METHOD")=="POST"){ 
		
			$this->form_validation->set_rules("tour_type", "Tour Type", "trim|required");
	
			if($this->form_validation->run() !== false)
			{
				$data = array(
					'package_type' => $this->input->post('tour_type')
				);
				
				$this->Package_type_model->save($data);
				$this->session->set_flashdata('message', 'Sucessfully Added');
				redirect('package_type', 'refresh');	
			}
			
		} 	
		$data['title']="Add Package Type";
		$this->load->view('package_type/add',$data);
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

	public function status($id,$status){
		$data = array('status'=>$status);
		$this->Package_type_model->update_status($id,$data);	
		$this->session->set_flashdata('message', 'Sucessfully Updated');
		redirect('package_type', 'refresh');
	}

	public function delete($id){
		$this->Package_type_model->delete($id);	
		$this->session->set_flashdata('message', 'Sucessfully Deleted');
		redirect('package_type', 'refresh');
	}

	
}