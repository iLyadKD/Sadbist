<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class _404 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Page Not Found";
	}
	
	public function index()
	{
		$this->load->view('_404');
	}

}