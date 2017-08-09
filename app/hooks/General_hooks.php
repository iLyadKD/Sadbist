<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class General_hooks {
	private $ci;
	function __construct()
	{
		$this->ci = &get_instance();
		unset_page_cache();
		$this->ci->load->model("B2c_model");
	}

	public function initialize()
	{
		// load default language
		if($this->ci->session->userdata("default_language") === null)
			$this->ci->session->set_userdata("default_language", DEFAULT_LANG);
		$site_lang = $this->ci->session->userdata("default_language");
		$this->ci->lang->load("PrInCe", $site_lang);
		$this->ci->config->set_item('language', $site_lang);
		$this->ci->data["default_language"] = $site_lang;

		//default tab
		$this->ci->data["default_tab"] = $this->ci->uri->segment(1);

		// load default currency
		if($this->ci->session->userdata("default_currency") === null)
			$this->ci->session->set_userdata("default_currency", DEFAULT_CURRENCY);
		$site_curr = $this->ci->session->userdata("default_currency");
		$this->ci->data["default_currency"] = $site_curr;

		// function details
		$this->ci->data["controller"] = $this->ci->router->fetch_class();
		$this->ci->data["method"] = $this->ci->router->fetch_method();

		//user details
		$this->ci->data["user_id"] = $this->ci->session->userdata(SESSION_PREPEND."id");
		if(!is_null($this->ci->data["user_id"]) && !is_null($this->ci->session->userdata(SESSION_PREPEND."type")))
		{
			$user = $this->ci->B2c_model->is_valid_user("", $this->ci->data["user_id"]);
			$this->ci->data["user_type"] = $user->user_type;
			$this->ci->data["user_type_text"] = "b2c";
			$this->ci->data["user_img"] = $user->image_path;
		}
	}
}