<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Bookings extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Sendmail_model");
		$this->data["page_main_title"] = "Bookings Management";
		$this->data["page_title"] = "Bookings Management";
	}

	//Bookings index
	public function index()
	{
		$this->load->view("bookings/b2c/index");
	}

	public function b2c_dom_flights()
	{
		$this->data["page_title"] = "Domestic Flight Bookings";
		$this->load->view("bookings/b2c/index");
	}
}