<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Ajax extends CI_Controller {

	/* load default models, libraries */
	public function __construct()
	{
		parent::__construct();
		unset_page_cache();
	}

	/*load dashboard view*/
	public function index()
	{
		echo json_encode(array("result" => false));
	}

	public function get_countries()
	{
		$countries = $this->general->get_active_countries();
		$response = "<option selected value=''>".$this->lang->line("select_country")."</option>";
		if($countries !== false)
			foreach($countries as $country)
				$response .= "<option value='".$country->id."'>".$country->country_name."</option>";
		echo json_encode(array("result" => $response));exit;
	}


	public function get_states()
	{
		$country_code = $this->input->get("country");
		$states = $this->general->get_regions($country_code);
		$response = "<option selected value=''>".$this->lang->line("select_region")."</option>";
		if($states !== false)
			foreach($states as $state)
				$response .= "<option value='".$state->country.":::".$state->region."'>".$state->name."</option>";
		$response .= "<option value='".$country_code.":::XXX'>".$this->lang->line("not_available")."</option>";
		echo json_encode(array("result" => $response));exit;	
	}

	public function get_cities()
	{
		$region_codes = $this->input->get("state");
		$region_codes = explode(":::", urldecode($region_codes));
		$cities = $this->general->get_cities($region_codes[1], $region_codes[0]);
		$response = "<option selected value=''>".$this->lang->line("select_city")."</option>";
		if($cities !== false)
			foreach($cities as $city)
				$response .= "<option value='".$city->id."'>".$city->city."</option>";
		echo json_encode(array("result" => $response));exit;	
	}

	public function change_language($lang = DEFAULT_LANG)
	{
		$return = "false";
		if(!empty($lang))
		{
			if($this->session->userdata("default_language") === null || $this->session->userdata("default_language") !== $lang)
			{
				$this->session->set_userdata("default_language",$lang);
				$return = "true";
			}
			
		}
		echo $return;exit;
	}

	public function change_currency($currency = DEFAULT_CURRENCY)
	{
		$return = "false";
		if(!empty($currency))
		{
			if($this->session->userdata("default_currency") === null || $this->session->userdata("default_currency") !== $currency)
			{
				$this->session->set_userdata("default_currency",$currency);
				$return = "true";
			}
			
		}
		echo $return;exit;
	}
}