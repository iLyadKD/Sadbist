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
	}

	public function initialize()
	{
		$this->ci->data["default_language"] = "eng";
		$this->ci->data["controller"] = $this->ci->router->fetch_class();
		$this->ci->data["method"] = $this->ci->router->fetch_method();
		$valid_excludes = array("login");
		$privilege_excludes = array("ajax", "privileges");
		if(!in_array($this->ci->data["controller"], $valid_excludes))
		{
			is_valid_admin_access();
		
			if($this->ci->session->userdata(SESSION_PREPEND."id") !== null && $this->ci->session->userdata(SESSION_PREPEND."id") !== false)
			{
				$this->ci->data["admin_id"] = $this->ci->session->userdata(SESSION_PREPEND."id");
				$this->ci->data["admin_email"] = $this->ci->session->userdata(SESSION_PREPEND."email");
				$this->ci->data["admin_type"] = $this->ci->session->userdata(SESSION_PREPEND."user_type");
				$this->ci->data["privileges"] = $this->ci->Privilege_model->get_active_privileges($this->ci->data["admin_id"]);
				
				$my_privileges = $this->ci->Privilege_model->get_admin_privileges($this->ci->data["admin_id"]);
				$this->ci->data["my_privileges"] = $my_privileges !== false ? array_filter(explode(",", $my_privileges)) : false;
				if(!in_array($this->ci->data["controller"], $privilege_excludes))
					is_admin_accessable($this->ci->data["admin_type"], $this->ci->data["controller"], $this->ci->data["my_privileges"]);
			}
		}
	}
}