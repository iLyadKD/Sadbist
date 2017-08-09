<?php

	/**
	 * frontend URL
	 *
	 * Returns front_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	function front_url($uri = "")
	{
		$front_url = strrev(strstr(strrev(trim(base_url(), "/")), "/"));
		return $front_url.ltrim($uri, "/");
	}


	/**
	 * Assets URL
	 *
	 * Returns asset_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	function asset_url($uri = "")
	{
		$asset_url = base_url()."assets".DIRECTORY_SEPARATOR;
		return $asset_url.ltrim($uri, "/");
	}

	/**
	 * file upload URL
	 *
	 * Returns upload_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	function upload_url($uri = "")
	{
		$url = strrev(strstr(strrev(trim(base_url(), "/")), "/"));
		$upload_url = $url."upload_files".DIRECTORY_SEPARATOR;
		return $upload_url.ltrim($uri, "/");
	}


	/* Check already logged in */
	function is_admin_logged_in()
	{
		$ci_ins =& get_instance();
		if($ci_ins->session->userdata(SESSION_PREPEND."user_type") === SUPER_ADMIN_USER || $ci_ins->session->userdata(SESSION_PREPEND."user_type") === ADMIN_USER)
			redirect("home");
	}

	/* Check valid user logged in */
	function is_valid_admin_access()
	{
		$ci_ins =& get_instance();
		if(!($ci_ins->session->userdata(SESSION_PREPEND."user_type") === SUPER_ADMIN_USER))
			if(!($ci_ins->session->userdata(SESSION_PREPEND."user_type") === ADMIN_USER) && !($ci_ins->session->userdata(SESSION_PREPEND."user_type") === CALL_CENTER_STAFF))
				redirect("login");
				
	}

	/*check access privelage for a particular page for sub-admin*/
	function is_admin_accessable($admin_type, $controller, $my_privileges)
	{
		$ci_ins =& get_instance();
		$privileges = $ci_ins->Privilege_model->get_parent_privileges();
		if($admin_type !== SUPER_ADMIN_USER)
		{
			$privileges = array_map("extract_privileges", $privileges);
			$all_previleges = array();
			foreach($privileges as $k => $v)
				foreach($v as $l => $m)
					$all_previleges[$m] = $l;
			$assigned_privileges = array_intersect($all_previleges, $my_privileges);
			$keys = array_filter(explode(",", implode(",", array_keys($assigned_privileges))));
			array_push($keys, "home");
			if(!in_array($controller, $keys))
				redirect("home");
		}
	}

	function extract_privileges($privilege)
	{
		return array($privilege->id => $privilege->controller);
	}

	/* unset page chache */
	function unset_page_cache()
	{
		$ci_ins =& get_instance();
		$ci_ins->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$ci_ins->output->set_header("Pragma: no-cache");
	}

	/* set page chache */
	function set_page_cache()
	{
		$ci_ins =& get_instance();
		$ci_ins->output->cache(2);
	}

	function get_menu_status()
	{
		$ci_ins =& get_instance();
		$status = $ci_ins->general->get_menu_status();
		return $status !== false ? $status->status : "0";
	}