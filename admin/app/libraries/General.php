<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class General {

	private $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
		function pr($data){
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}
	}

	public function get_active_countries($id = "", $search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$query = "SELECT `id`, `country_".$lang."` AS `text` FROM `countries` WHERE `status` = '1'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `country_".$lang."` LIKE '$search%'";
		$total_countries = $this->CI->db->query($query)->num_rows();
		$countries = array();
		if($total_countries > 0)
		{
			$query .= " ORDER BY `country_".$lang."`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$countries = $result->result();
		}
		return $total_countries > 0 ? array("total" => $total_countries, "results" => $countries) : array("total" => "0", "results" => array());
	}

	public function get_active_currencies($id = "", $search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$query = "SELECT `id`, CONCAT(`country_".$lang."`, ', ', `currency`, ' (', `code`, ')') AS `label`, CONCAT(`country_".$lang."`, ', ', `currency`) AS `text` FROM `countries` INNER JOIN `currencies` ON `id` = `country` WHERE `currencies`.`status` = '1'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND (`country_".$lang."` LIKE '$search%' OR `code` LIKE  '$search%' OR `currency` LIKE  '$search%')";
		$total_countries = $this->CI->db->query($query)->num_rows();
		$countries = array();
		if($total_countries > 0)
		{
			$query .= " ORDER BY CASE WHEN `id` = '".OTHER_COUNTRY."' THEN 1 ELSE 0 END, `country_".$lang."`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$countries = $result->result();
		}
		return $total_countries > 0 ? array("total" => $total_countries, "results" => $countries) : array("total" => "0", "results" => array());
	}
	
	public function get_unset_currency_countries($id = "", $search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$query = "SELECT `id`, `country_".$lang."` AS `text` FROM `countries` WHERE `id` NOT IN (SELECT 
			`country` FROM `currencies` INNER JOIN `countries` ON `country` = `id`)";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `country_".$lang."` LIKE '$search%'";
		$total_countries = $this->CI->db->query($query)->num_rows();
		$countries = array();
		if($total_countries > 0)
		{
			$query .= " ORDER BY `country_".$lang."`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$countries = $result->result();
		}
		return $total_countries > 0 ? array("total" => $total_countries, "results" => $countries) : array("total" => "0", "results" => array());
	}

	public function get_all_countries($lang = DEFAULT_LANG)
	{
		$query = "SELECT `id`, `country_".$lang."` FROM `countries` ORDER BY  `country_".$lang."`";
		$result = $this->CI->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	/* Get active airlines */
	public function get_airlines($code = "", $search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$query = "SELECT airline_code AS `id`, `airline_".$lang."` AS `text` FROM `flight_airlines` WHERE `status` = '1'";
		if($code !== "")
			$query .= " AND `airline_code` = '$code'";
		if($search !== "")
			$query .= " AND (`airline_code` LIKE '$search%' OR `airline_".$lang."` LIKE '$search%')";
		$total_airlines = $this->CI->db->query($query)->num_rows();
		$airlines = array();
		if($total_airlines > 0)
		{
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$airlines = $result->result();
		}
		return $total_airlines > 0 ? array("total" => $total_airlines, "results" => $airlines) : array("total" => "0", "results" => array());
	}

	/* Get active airports */
	public function get_airports($code = "", $search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$query = "SELECT CONCAT(`airport_".$lang."`, ', ', `city_".$lang."`) AS `text`, `airport_code` as `id`, `city_".$lang."` as `city_text` FROM `flight_airports` WHERE `status` = '1'";
		if($code !== "")
			$query .= " AND `airport_code` = '$code'";
		if($search !== "")
			$query .= " AND (`airport_code` LIKE '$search%' OR `airport_".$lang."` LIKE '$search%' OR `city_".$lang."` LIKE '$search%')";
		$total_airports = $this->CI->db->query($query)->num_rows();
		$airports = array();
		if($total_airports > 0)
		{
			$query .= " ORDER BY FIELD(`city_code`, '$search') DESC, FIELD(`airport_code`, '$search') DESC, FIELD(`city_".$lang."`, '$search'), FIELD(`airport_".$lang."`, '$search')";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$airports = $result->result();
		}
		return $total_airports > 0 ? array("total" => $total_airports, "results" => $airports) : array("total" => "0", "results" => array());
	}

	/* Get active airports */
	public function get_flight_airport($code = "", $lang = DEFAULT_LANG)
	{
		$query = "SELECT `city_".$lang."` AS `text`, `airport_code` as `id`, `city_".$lang."` as `city_text` FROM `flight_airports` WHERE `status` = '1'";
		if($code !== "")
			$query .= " AND `airport_code` = '$code'";
		$total_airports = $this->CI->db->query($query)->num_rows();
		$airports = array();
		if($total_airports > 0)
		{
			$query .= " ORDER BY `city_code` DESC";
			$query .= " LIMIT 1";
			$result = $this->CI->db->query($query);
			$airports = $result->result();
		}
		return $total_airports > 0 ? array("total" => $total_airports, "results" => $airports) : array("total" => "0", "results" => array());
	}

	/* Get active airports */
	public function get_flight_airport_by_name($name = "", $lang = DEFAULT_LANG)
	{
		$query = "SELECT `city_".$lang."` AS `text`, `airport_code` as `id`, `city_".$lang."` as `city_text` FROM `flight_airports` WHERE `status` = '1'";
		if($name !== "")
			$query .= " AND `city_".$lang."` = '$name'";
		$total_airports = $this->CI->db->query($query)->num_rows();
		$airports = array();
		if($total_airports > 0)
		{
			$query .= " ORDER BY city_".$lang." DESC";
			$query .= " LIMIT 1";
			$result = $this->CI->db->query($query);
			$airports = $result->result();
		}
		return $total_airports > 0 ? array("total" => $total_airports, "results" => $airports) : array("total" => "0", "results" => array());
	}

	/* Get complete state / region for specific country */
	public function get_regions($country, $code = "", $search = "", $page = "1")
	{
		$query = "SELECT CONCAT(`country`, \":::\", `region`) AS `id`, `name_en` AS `text` FROM `regions` WHERE `id` <> '0'";
		if($code !== "")
		{
			$code_arr = explode(":::", $code);
			$country = $code_arr[0];
			$region = $code_arr[1];
			if($region !== NO_REGION)
				$query .= " AND CONCAT(`country`, \":::\", `region`) = \"$code\"";
			else
				$query .= " AND `country` = \"$country\" AND `region` IS NULL";
		}
		if($country !== "")
			$query .= " AND`country` = '".$country."'";
		if($search !== "")
			$query .= " AND `name_en` LIKE '$search%'";
		$total_regions = $this->CI->db->query($query)->num_rows();
		$regions = array();
		if($total_regions > 0)
		{
			$query .= " ORDER BY `region`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$regions = $result->result();
		}
		array_push($regions, array("id" => $country.":::".NO_REGION, "text" => "Not Available"));
		return array("total" => $total_regions, "results" => $regions);
	}


	/* Get unassigned state / region codes for specific country */
	public function get_unassigned_regions($country, $region = null, $id = "", $search = "", $page = "1")
	{
		$total_regions = 0;
		if($country !== "")
		{
			$region_query = empty($region) || is_null($region) ? "" : " AND `region` <> '$region'";
			$query = "SELECT `id`, `id` AS `text` FROM `region_codes` WHERE `id` NOT IN (SELECT `region` FROM `regions` WHERE `country` = '$country' ".$region_query.")";
			if($id !== "")
				$query .= " AND `id` = \"$id\"";
			if($search !== "")
				$query .= " AND `id` LIKE '$search%'";
			$total_regions = $this->CI->db->query($query)->num_rows();
			$regions = array();
			if($total_regions > 0)
			{
				$query .= " LIMIT ".(($page - 1) * 10).", 10";
				$result = $this->CI->db->query($query);
				$regions = $result->result();
			}
		}
		return $total_regions > 0 ? array("total" => $total_regions, "results" => $regions) : array("total" => "0", "results" => array());
	}

	/* Get complete city list for particular region and country */
	public function get_cities($country, $region, $id = "", $search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$more_condition = "";
		$region_condition = "";
		if($country !== "" && $region !== "")
		{
			if($region !== NO_REGION)
				$region_condition = "`region` = '".$region."' AND";
			$more_condition = "AND $region_condition `country` = '".$country."'";
		}
		$query = "SELECT `id`, `city_en` AS `text` FROM `cities` WHERE `id` <> '0' $more_condition";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `city_en` LIKE '$search%'";
		$total_cities = $this->CI->db->query($query)->num_rows();
		$cities = array();
		if($total_cities > 0)
		{
			$query .= " ORDER BY `city_en`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$cities = $result->result();
		}
		return $total_cities > 0 ? array("total" => $total_cities, "results" => $cities) : array("total" => "0", "results" => array(), "query" => $this->CI->db->last_query());
	}

	// get all static pages
	public function get_page_types()
	{
		$query = "SELECT * FROM `footer_link_headers` WHERE  `type` = '".STATIC_PAGE_TYPE."'";
		$result = $this->CI->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	// get dashboard menu status
	public function get_menu_status()
	{
		$query = "SELECT * FROM `extra_settings` WHERE `id` = '".MENU_STATUS."'";
		$result = $this->CI->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	// set dashboard menu status
	public function set_menu_status($status)
	{
		$query = "INSERT INTO `extra_settings`(`id`, `settings_name`, `status`) VALUES 
		('".MENU_STATUS."', 'menu_status', '$status') ON DUPLICATE KEY UPDATE `status` = '$status'";
		$this->CI->db->query($query);
		return $this->CI->db->affected_rows() > 0 ? true : false;
	}

	// get all support subjects
	public function get_support_subjects($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `subject` AS `text` FROM `support_ticket_subjects` WHERE `id` <> '0'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `subject` LIKE '$search%'";
		$total_subjects = $this->CI->db->query($query)->num_rows();
		$subjects = array();
		if($total_subjects > 0)
		{
			$query .= " ORDER BY `subject`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$subjects = $result->result();
		}
		return $total_subjects > 0 ? array("total" => $total_subjects, "results" => $subjects) : array("total" => "0", "results" => array());
	}

	// get all static pages
	public function get_users_by_type($type, $admin_id = null, $id = "", $search = "", $page = "1")
	{
		if($type === ADMIN_USER)
			$query = "SELECT `id`, CONCAT(firstname, ' ', lastname, '  &lt;', email_id,'&gt;') AS `label`, CONCAT(firstname, ' ', lastname) AS `text` FROM `admin` WHERE `user_type` = '$type' AND `id` <> '$admin_id' AND `status` = '1'";
		elseif($type === B2B_USER)
			$query = "SELECT SELECT `id`, CONCAT(firstname, ' ', lastname, '  &lt;', email_id,'&gt;') AS `label`, CONCAT(firstname, ' ', lastname) AS `text` FROM `b2b` WHERE `user_type` = '$type' AND `status` = '1'";
		elseif($type === B2C_USER)
			$query = "SELECT `id`, CONCAT(firstname, ' ', lastname, '  &lt;', email_id,'&gt;') AS `label`, CONCAT(firstname, ' ', lastname) AS `text` FROM `b2c` WHERE `user_type` = '$type' AND `status` = '1'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND (`firstname` LIKE '$search%' OR `lastname` LIKE '$search%' OR `email_id` LIKE '$search%')";
		$total_users = $this->CI->db->query($query)->num_rows();
		$users = array();
		if($total_users > 0)
		{
			$query .= " ORDER BY `city_en`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$users = $result->result();
		}
		return $total_users > 0 ? array("total" => $total_users, "results" => $users) : array("total" => "0", "results" => array());
	}

	public function get_active_promocodes()
	{
		$query = "SELECT `promo_code`.*, `name` FROM `promo_code` INNER JOIN `promo_discount_types` ON 
		`promo_discount_types`.`id` = `promo_code`.`type`  WHERE `valid_to` > '".date("Y-m-d")."' 
		AND `status` = '1'";
		$result = $this->CI->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}
	
	/*generate salt and encrypt password*/  
	public function generate_salt_pwd($pass)
	{
		$this->CI->load->library('encrypt');
		$pass = $pass.SALT;
		$salt = hash('sha256', $pass);
		$key = $salt.SALT.$pass; 
		$key = md5($key); 
		$password = SHA1($key);
		$salt = $this->CI->encrypt->encode($salt);
		return array('password'=>$password, 'salt'=>$salt);
	}

	/* check valid password */
	public function validate_pass($enc, $salt, $plain)
	{
		$this->CI->load->library('encrypt');
		$salt = $this->CI->encrypt->decode($salt);
		$password = $plain.SALT;
		$key = $salt.SALT.$password;
		$new_password = SHA1(md5($key));
		return strcmp($enc, $new_password) === 0 ? true : false;
	}

	/*generate random url key*/
	public function generate_random_key($length = 20, $special = false)
	{
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		$spl = array("{", "}", "[", "]", "<", ">", ",", ".", "/", "?", ":", ";", "|", "\\", "+", "=", "_", "-", "(", ")", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*");
		$final_array = array_merge($alphabets,$numbers);
		if($special)
			$final_array = array_merge($final_array,$spl);
		$secret = "";
		while($length--)
			$secret .= $final_array[array_rand($final_array)];
		return $secret;
	}

	public function extract_region($region_country)
	{
		return explode(":::", urldecode($region_country))[1];
	}

	function is_starts_with($haystack, $needle)
	{
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}
	function is_ends_with($haystack, $needle)
	{
		// search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}

	public function generate_slug($string, $append = false, $replace = "-")
	{
		static $append_index = 1;
		if($append_index > 1)
			$string = substr_replace($string, "", -(strlen($append_index)));
		if($append !== false)
			$string .= "-".($append_index++);
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", $replace, $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", $replace, $string);
		return $string;
	}

	public function download_file($file, $file_name)
	{
		$this->CI->load->helper('download');
		$file = base64_decode($file);
		$path = pathinfo($file);
		$ext = $path['extension'];
		$data = file_get_contents(upload_url($file)); // Read the file's contents
		force_download($file_name.$ext, $data); 
	}

	public function set_time_ago($dt, $counter = 3)
	{
		if($counter > 6)
			$counter = 6;
		$sss = date("Y-m-d H:i:s");
		$etime = strtotime($sss) - strtotime($dt);
		if($etime < 1)
			return "less than a second ago";
		$a = array( 12 * 30 * 24 * 60 * 60	=>  "year",
					30 * 24 * 60 * 60		=>  "month",
					24 * 60 * 60			=>  "day",
					60 * 60					=>  "hour",
					60						=>  "minute",
					1						=>  "second"
				);
		$return_val = "";
		foreach( $a as $secs => $str )
		{
			$d = $etime / $secs;
			if($d >= 1)
			{
				$r = floor($d);
				$etime -= ($r * $secs);
				if($counter-- >= 1)
					$return_val .= $r." ".$str.", ";
				else
					break;
			}
		}
		$return_val = substr_replace($return_val, "", -2);
		return $return_val." ago";
	}

	public function countdown($dt, $counter = 2)
	{
		if($counter > 6)
			$counter = 6;
		$sss = date("Y-m-d H:i:s");
		$etime = strtotime($dt) - strtotime($sss);
		if($etime < 1)
			return "less than a second remains";
		$a = array( 12 * 30 * 24 * 60 * 60	=>  "year",
					30 * 24 * 60 * 60		=>  "month",
					24 * 60 * 60			=>  "day",
					60 * 60					=>  "hour",
					60						=>  "minute",
					1						=>  "second"
				);
		$return_val = "";
		foreach( $a as $secs => $str )
		{
			$d = $etime / $secs;
			if($d >= 1)
			{
				$r = floor($d);
				$etime -= ($r * $secs);
				if($counter-- >= 1)
					$return_val .= $r." ".$str.", ";
				else
					break;
			}
		}
		$return_val = substr_replace($return_val, "", -2);
		return $return_val." remains";
	}

	//get seo meta-tags name
	public function seo_names()
	{
		// Dublin Core metatags and miscellaneous metatags are not included
		$response["name"] = array(
							"title" => "Any",
							"description" => "Any",
							"keywords" => "Any",
							"robots" => array("all" => "The spider will not only index the first web-page of your website but also all your other web-pages.", 
												"index, follow" => "The spider will not only index the first web-page of your website but also all your other web-pages.", 
												"index, nofollow" => "The spider will now only look at this page and stops there.", 
												"noindex, follow" => "The spider will not look at this page but will crawl through the rest of the pages on your website.", 
												"noindex, nofollow" => "The spider will not look at this page and will NOT crawl through the rest of your web-pages.",
												"noydir" => "update Yahoo database with new title and descriptions."
												),
							"revisit-after" => array("days", "month"),
							"abstract" => "Any",
							"author" => "Any",
							"contact" => "Email Address",
							"copyright" => "Company Name",
							"distribution" => array("Global" => "Indicates that your web-page is intended for everyone.",
													"Local" => "Intended for local distribution of your document.",
													"IU" => "Internal Use, not intended for public distribution."
												),
							"expires" => "Valid Date",
							"generator" => "Development Platform - Ex: Codeignitor",
							"formatter" => "Development Platform - Ex: Codeignitor",
							"googlebot" => array("noodp" => "Tell the Googlebot NOT to duplicate the ODP description but the description which is located on website.",
												"noarchive" => "Does not allow Google to display cached content.",
												"nosnippet" => "Does not allow Google to display excerpted or cached content.",
												"noindex" => "The spider will not look at this page but will crawl through the rest of the pages on your website.",
												"nofollow" => "Does not allow Google to pass any PageRank or link popularity to the link served."
												),
							"language" => "Valid Language Name.",
							"news_keywords" => "Any",
							"no-email-collection" => "Link or Terms",
							"rating" => array("general" => "Website for everyone.",
											"mature" => "muture",
											"restricted" => "restricted",
											"14 years" => "14 years",
											"safe for kids" => "safe for kids"
											),
							"reply-to" => "Email Address",
							"slurp" => array("noydir" => "update Yahoo database with new title and descriptions."),
							"web_author" => "Any",
							"rel" => "It is not actually a metatag but used for pagerank and values are nofollow and canonical"
						);
		$response["http-equiv"] = array("expires" => "valid date",
										"pragma" => "no-cache",
										"window-target" => "_top",
										"Set-Cookie" => "key=value;key=value_formatted;",
										"pics-label" => "Any",
										"Cache-control" => array("public" => "public",
																"private" => "private",
																"no-cache" => "no-cache",
																"no-store" => "no-store"
																),
										"Content-Type" => array("content" => "text/html",
																"charset" => "utf-8"
																),
										"imagetoolbar" => array("yes" => "display images in explorer",
																"no" => "do not display images in explorer"
																),
										"content-disposition" => array("filename" => "file path",
																		"creation-date" => "created date",
																		"modification-date" => "modified date",
																		"read-date" => "last read date",
																		"size" => "number"
																		),
										"MSThemeCompatible" => array("yes" => "Diplay title bar, select box to xp designing(works only in windows XP.)",
																	"no" => "do not display compatable design of windows xp."
																	),
										"refresh" => "time in seconds, URL to redirect.",
										"Resource-Type" => "type of web-page",
										"Content-Script-Type" => array("text/plain" => "text/plain",
																		"text/html" => "text/html",
																		"application/binary" => "application/binary",
																		"application/postscripts" => "application/postscripts",
																		"image/gif" => "image/gif",
																		"image/xbm" => "image/xbm",
																		"image/jpeg" => "image/jpeg",
																		"audio/basic" => "audio/basic",
																		"video/mpeg" => "video/mpeg",
																		"video/QuickTime" => "video/QuickTime"
																		),
										"Content-Style-Type" => "text/css"
									);
		return $response;
	}

	// get all payment gateways
	public function get_active_payment_gateways($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `title` AS `text`, `pay_mode` FROM `payment_gateways` WHERE  `status` = '1'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `title` LIKE '$search%'";
		$total_pgs = $this->CI->db->query($query)->num_rows();
		$pgs = array();
		if($total_pgs > 0)
		{
			$query .= " ORDER BY `title`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$pgs = $result->result();
		}
		return $total_pgs > 0 ? array("total" => $total_pgs, "results" => $pgs) : array("total" => "0", "results" => array());
	}

	public function get_active_apis($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `api_name` AS `text` FROM `api` WHERE  `status` > '0'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `api_name` LIKE '$search%'";
		$total_apis = $this->CI->db->query($query)->num_rows();
		$apis = array();
		if($total_apis > 0)
		{
			$query .= " ORDER BY `api_name`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$apis = $result->result();
		}
		return $total_apis > 0 ? array("total" => $total_apis, "results" => $apis) : array("total" => "0", "results" => array());
	}

	public function get_api_types($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `api_type_name` AS `text` FROM `api_types` WHERE `id` <> '0'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `api_type_name` LIKE '$search%'";
		$total_api_types = $this->CI->db->query($query)->num_rows();
		$api_types = array();
		if($total_api_types > 0)
		{
			$query .= " ORDER BY `api_type_name`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$api_types = $result->result();
		}
		return $total_api_types > 0 ? array("total" => $total_api_types, "results" => $api_types) : array("total" => "0", "results" => array());
	}

	// get all taxes
	public function get_active_taxes($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `title` AS `text`, `pay_mode` FROM `taxes` WHERE  `status` = '1'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `title` LIKE '$search%'";
		$total_taxes = $this->CI->db->query($query)->num_rows();
		$taxes = array();
		if($total_taxes > 0)
		{
			$query .= " ORDER BY `title`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$taxes = $result->result();
		}
		return $total_taxes > 0 ? array("total" => $total_taxes, "results" => $taxes) : array("total" => "0", "results" => array());
	}

	// get all markup_types
	public function get_markup_types($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `markup_name` AS `text`, `user_type` FROM `markup_types` WHERE  `id` <> '0'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `markup_name` LIKE '$search%'";
		$total_taxes = $this->CI->db->query($query)->num_rows();
		$markup_mts = array();
		if($markup_mts > 0)
		{
			$query .= " ORDER BY `markup_name`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$markup_types = $result->result();
		}
		return $markup_mts > 0 ? array("total" => $markup_mts, "results" => $markup_types) : array("total" => "0", "results" => array());
	}

	// get all b2b users
	public function get_active_b2b_users($id = "", $search = "", $page = "1")
	{

		$query = "SELECT `id`, CONCAT(`firstname`, ' ', `lastname`) AS `text`, CONCAT(`firstname`, ' ', `lastname`, ' &lt;', `email_id`, '&gt;') AS `label` FROM `b2b` WHERE `status` = '1'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND (`firstname` LIKE '$search%' OR `lastname` LIKE '$search%' OR `email_id` LIKE '$search%')";
		$total_b2b_users = $this->CI->db->query($query)->num_rows();
		$result = array();
		if($total_b2b_users > 0)
		{
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
		}
		return $total_b2b_users > 0 ? array("total" => $total_b2b_users, "results" => $result->result()) : array("total" => "0", "results" => array());
	}

	public function get_account_types($id = "", $search = "", $page = "1", $exclude = array("payment_gateway", "credit"))
	{
		$limit_requires = array(B2B_CREDIT_ACC);
		$excluded = "";
		$limit_required = "";
		if(count($exclude) > 0)
			$excluded = implode("','", $exclude);
		if($excluded !== "")
			$excluded = "'".$excluded."'";

		if(count($limit_requires) > 0)
			$limit_required = ", `id` IN (".implode(",", $limit_requires).") AS limit_required";

		$query = "SELECT `id`, `acc_name` AS `text` $limit_required FROM `account_types` WHERE `id` <> '0'";
		if($excluded !== "")
			$query .= " AND `acc_type` NOT IN ($excluded)";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `acc_name` LIKE '$search%'";
		$total_api_types = $this->CI->db->query($query)->num_rows();
		$api_types = array();
		if($total_api_types > 0)
		{
			$query .= " ORDER BY `acc_name`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$api_types = $result->result();
		}
		return $total_api_types > 0 ? array("total" => $total_api_types, "results" => $api_types, "limit" => $limit_required) : array("total" => "0", "results" => array(), "limit" => $limit_required);
	}

	public function currency_json()
	{
		$json = '{"BD": "BDT", "BE": "EUR", "BF": "XOF", "BG": "BGN", "BA": "BAM", "BB": "BBD", "WF": "XPF", "BL": "EUR", "BM": "BMD", "BN": "BND", "BO": "BOB", "BH": "BHD", "BI": "BIF", "BJ": "XOF", "BT": "BTN", "JM": "JMD", "BV": "NOK", "BW": "BWP", "WS": "WST", "BQ": "USD", "BR": "BRL", "BS": "BSD", "JE": "GBP", "BY": "BYR", "BZ": "BZD", "RU": "RUB", "RW": "RWF", "RS": "RSD", "TL": "USD", "RE": "EUR", "TM": "TMT", "TJ": "TJS", "RO": "RON", "TK": "NZD", "GW": "XOF", "GU": "USD", "GT": "GTQ", "GS": "GBP", "GR": "EUR", "GQ": "XAF", "GP": "EUR", "JP": "JPY", "GY": "GYD", "GG": "GBP", "GF": "EUR", "GE": "GEL", "GD": "XCD", "GB": "GBP", "GA": "XAF", "SV": "USD", "GN": "GNF", "GM": "GMD", "GL": "DKK", "GI": "GIP", "GH": "GHS", "OM": "OMR", "TN": "TND", "JO": "JOD", "HR": "HRK", "HT": "HTG", "HU": "HUF", "HK": "HKD", "HN": "HNL", "HM": "AUD", "VE": "VEF", "PR": "USD", "PS": "ILS", "PW": "USD", "PT": "EUR", "SJ": "NOK", "PY": "PYG", "IQ": "IQD", "PA": "PAB", "PF": "XPF", "PG": "PGK", "PE": "PEN", "PK": "PKR", "PH": "PHP", "PN": "NZD", "PL": "PLN", "PM": "EUR", "ZM": "ZMK", "EH": "MAD", "EE": "EUR", "EG": "EGP", "ZA": "ZAR", "EC": "USD", "IT": "EUR", "VN": "VND", "SB": "SBD", "ET": "ETB", "SO": "SOS", "ZW": "ZWL", "SA": "SAR", "ES": "EUR", "ER": "ERN", "ME": "EUR", "MD": "MDL", "MG": "MGA", "MF": "EUR", "MA": "MAD", "MC": "EUR", "UZ": "UZS", "MM": "MMK", "ML": "XOF", "MO": "MOP", "MN": "MNT", "MH": "USD", "MK": "MKD", "MU": "MUR", "MT": "EUR", "MW": "MWK", "MV": "MVR", "MQ": "EUR", "MP": "USD", "MS": "XCD", "MR": "MRO", "IM": "GBP", "UG": "UGX", "TZ": "TZS", "MY": "MYR", "MX": "MXN", "IL": "ILS", "FR": "EUR", "IO": "USD", "SH": "SHP", "FI": "EUR", "FJ": "FJD", "FK": "FKP", "FM": "USD", "FO": "DKK", "NI": "NIO", "NL": "EUR", "NO": "NOK", "NA": "NAD", "VU": "VUV", "NC": "XPF", "NE": "XOF", "NF": "AUD", "NG": "NGN", "NZ": "NZD", "NP": "NPR", "NR": "AUD", "NU": "NZD", "CK": "NZD", "XK": "EUR", "CI": "XOF", "CH": "CHF", "CO": "COP", "CN": "CNY", "CM": "XAF", "CL": "CLP", "CC": "AUD", "CA": "CAD", "CG": "XAF", "CF": "XAF", "CD": "CDF", "CZ": "CZK", "CY": "EUR", "CX": "AUD", "CR": "CRC", "CW": "ANG", "CV": "CVE", "CU": "CUP", "SZ": "SZL", "SY": "SYP", "SX": "ANG", "KG": "KGS", "KE": "KES", "SS": "SSP", "SR": "SRD", "KI": "AUD", "KH": "KHR", "KN": "XCD", "KM": "KMF", "ST": "STD", "SK": "EUR", "KR": "KRW", "SI": "EUR", "KP": "KPW", "KW": "KWD", "SN": "XOF", "SM": "EUR", "SL": "SLL", "SC": "SCR", "KZ": "KZT", "KY": "KYD", "SG": "SGD", "SE": "SEK", "SD": "SDG", "DO": "DOP", "DM": "XCD", "DJ": "DJF", "DK": "DKK", "VG": "USD", "DE": "EUR", "YE": "YER", "DZ": "DZD", "US": "USD", "UY": "UYU", "YT": "EUR", "UM": "USD", "LB": "LBP", "LC": "XCD", "LA": "LAK", "TV": "AUD", "TW": "TWD", "TT": "TTD", "TR": "TRY", "LK": "LKR", "LI": "CHF", "LV": "EUR", "TO": "TOP", "LT": "LTL", "LU": "EUR", "LR": "LRD", "LS": "LSL", "TH": "THB", "TF": "EUR", "TG": "XOF", "TD": "XAF", "TC": "USD", "LY": "LYD", "VA": "EUR", "VC": "XCD", "AE": "AED", "AD": "EUR", "AG": "XCD", "AF": "AFN", "AI": "XCD", "VI": "USD", "IS": "ISK", "IR": "IRR", "AM": "AMD", "AL": "ALL", "AO": "AOA", "AQ": "", "AS": "USD", "AR": "ARS", "AU": "AUD", "AT": "EUR", "AW": "AWG", "IN": "INR", "AX": "EUR", "AZ": "AZN", "IE": "EUR", "ID": "IDR", "UA": "UAH", "QA": "QAR", "MZ": "MZN"}';
		return json_decode($json);
	}

	public function country_list_array()
	{
		$array_list = array(array("id" => "AF", "iso_3" => "AFG", "iso_number" => "004"),
							array("id" => "AX", "iso_3" => "ALA", "iso_number" => "248"),
							array("id" => "AL", "iso_3" => "ALB", "iso_number" => "008"),
							array("id" => "DZ", "iso_3" => "DZA", "iso_number" => "012"),
							array("id" => "AS", "iso_3" => "ASM", "iso_number" => "016"),
							array("id" => "AD", "iso_3" => "AND", "iso_number" => "020"),
							array("id" => "AO", "iso_3" => "AGO", "iso_number" => "024"),
							array("id" => "AI", "iso_3" => "AIA", "iso_number" => "660"),
							array("id" => "AQ", "iso_3" => "ATA", "iso_number" => "010"),
							array("id" => "AG", "iso_3" => "ATG", "iso_number" => "028"),
							array("id" => "AR", "iso_3" => "ARG", "iso_number" => "032"),
							array("id" => "AM", "iso_3" => "ARM", "iso_number" => "051"),
							array("id" => "AW", "iso_3" => "ABW", "iso_number" => "533"),
							array("id" => "AU", "iso_3" => "AUS", "iso_number" => "036"),
							array("id" => "AT", "iso_3" => "AUT", "iso_number" => "040"),
							array("id" => "AZ", "iso_3" => "AZE", "iso_number" => "031"),
							array("id" => "BS", "iso_3" => "BHS", "iso_number" => "044"),
							array("id" => "BH", "iso_3" => "BHR", "iso_number" => "048"),
							array("id" => "BD", "iso_3" => "BGD", "iso_number" => "050"),
							array("id" => "BB", "iso_3" => "BRB", "iso_number" => "052"),
							array("id" => "BY", "iso_3" => "BLR", "iso_number" => "112"),
							array("id" => "BE", "iso_3" => "BEL", "iso_number" => "056"),
							array("id" => "BZ", "iso_3" => "BLZ", "iso_number" => "084"),
							array("id" => "BJ", "iso_3" => "BEN", "iso_number" => "204"),
							array("id" => "BM", "iso_3" => "BMU", "iso_number" => "060"),
							array("id" => "BT", "iso_3" => "BTN", "iso_number" => "064"),
							array("id" => "BO", "iso_3" => "BOL", "iso_number" => "068"),
							array("id" => "BA", "iso_3" => "BIH", "iso_number" => "070"),
							array("id" => "BW", "iso_3" => "BWA", "iso_number" => "072"),
							array("id" => "BV", "iso_3" => "BVT", "iso_number" => "074"),
							array("id" => "BR", "iso_3" => "BRA", "iso_number" => "076"),
							array("id" => "VG", "iso_3" => "VGB", "iso_number" => "092"),
							array("id" => "IO", "iso_3" => "IOT", "iso_number" => "086"),
							array("id" => "BN", "iso_3" => "BRN", "iso_number" => "096"),
							array("id" => "BG", "iso_3" => "BGR", "iso_number" => "100"),
							array("id" => "BF", "iso_3" => "BFA", "iso_number" => "854"),
							array("id" => "BI", "iso_3" => "BDI", "iso_number" => "108"),
							array("id" => "KH", "iso_3" => "KHM", "iso_number" => "116"),
							array("id" => "CM", "iso_3" => "CMR", "iso_number" => "120"),
							array("id" => "CA", "iso_3" => "CAN", "iso_number" => "124"),
							array("id" => "CV", "iso_3" => "CPV", "iso_number" => "132"),
							array("id" => "KY", "iso_3" => "CYM", "iso_number" => "136"),
							array("id" => "CF", "iso_3" => "CAF", "iso_number" => "140"),
							array("id" => "TD", "iso_3" => "TCD", "iso_number" => "148"),
							array("id" => "CL", "iso_3" => "CHL", "iso_number" => "152"),
							array("id" => "CN", "iso_3" => "CHN", "iso_number" => "156"),
							array("id" => "HK", "iso_3" => "HKG", "iso_number" => "344"),
							array("id" => "MO", "iso_3" => "MAC", "iso_number" => "446"),
							array("id" => "CX", "iso_3" => "CXR", "iso_number" => "162"),
							array("id" => "CC", "iso_3" => "CCK", "iso_number" => "166"),
							array("id" => "CO", "iso_3" => "COL", "iso_number" => "170"),
							array("id" => "KM", "iso_3" => "COM", "iso_number" => "174"),
							array("id" => "CG", "iso_3" => "COG", "iso_number" => "178"),
							array("id" => "CD", "iso_3" => "COD", "iso_number" => "180"),
							array("id" => "CK", "iso_3" => "COK", "iso_number" => "184"),
							array("id" => "CR", "iso_3" => "CRI", "iso_number" => "188"),
							array("id" => "CI", "iso_3" => "CIV", "iso_number" => "384"),
							array("id" => "HR", "iso_3" => "HRV", "iso_number" => "191"),
							array("id" => "CU", "iso_3" => "CUB", "iso_number" => "192"),
							array("id" => "CY", "iso_3" => "CYP", "iso_number" => "196"),
							array("id" => "CZ", "iso_3" => "CZE", "iso_number" => "203"),
							array("id" => "DK", "iso_3" => "DNK", "iso_number" => "208"),
							array("id" => "DJ", "iso_3" => "DJI", "iso_number" => "262"),
							array("id" => "DM", "iso_3" => "DMA", "iso_number" => "212"),
							array("id" => "DO", "iso_3" => "DOM", "iso_number" => "214"),
							array("id" => "EC", "iso_3" => "ECU", "iso_number" => "218"),
							array("id" => "EG", "iso_3" => "EGY", "iso_number" => "818"),
							array("id" => "SV", "iso_3" => "SLV", "iso_number" => "222"),
							array("id" => "GQ", "iso_3" => "GNQ", "iso_number" => "226"),
							array("id" => "ER", "iso_3" => "ERI", "iso_number" => "232"),
							array("id" => "EE", "iso_3" => "EST", "iso_number" => "233"),
							array("id" => "ET", "iso_3" => "ETH", "iso_number" => "231"),
							array("id" => "FK", "iso_3" => "FLK", "iso_number" => "238"),
							array("id" => "FO", "iso_3" => "FRO", "iso_number" => "234"),
							array("id" => "FJ", "iso_3" => "FJI", "iso_number" => "242"),
							array("id" => "FI", "iso_3" => "FIN", "iso_number" => "246"),
							array("id" => "FR", "iso_3" => "FRA", "iso_number" => "250"),
							array("id" => "GF", "iso_3" => "GUF", "iso_number" => "254"),
							array("id" => "PF", "iso_3" => "PYF", "iso_number" => "258"),
							array("id" => "TF", "iso_3" => "ATF", "iso_number" => "260"),
							array("id" => "GA", "iso_3" => "GAB", "iso_number" => "266"),
							array("id" => "GM", "iso_3" => "GMB", "iso_number" => "270"),
							array("id" => "GE", "iso_3" => "GEO", "iso_number" => "268"),
							array("id" => "DE", "iso_3" => "DEU", "iso_number" => "276"),
							array("id" => "GH", "iso_3" => "GHA", "iso_number" => "288"),
							array("id" => "GI", "iso_3" => "GIB", "iso_number" => "292"),
							array("id" => "GR", "iso_3" => "GRC", "iso_number" => "300"),
							array("id" => "GL", "iso_3" => "GRL", "iso_number" => "304"),
							array("id" => "GD", "iso_3" => "GRD", "iso_number" => "308"),
							array("id" => "GP", "iso_3" => "GLP", "iso_number" => "312"),
							array("id" => "GU", "iso_3" => "GUM", "iso_number" => "316"),
							array("id" => "GT", "iso_3" => "GTM", "iso_number" => "320"),
							array("id" => "GG", "iso_3" => "GGY", "iso_number" => "831"),
							array("id" => "GN", "iso_3" => "GIN", "iso_number" => "324"),
							array("id" => "GW", "iso_3" => "GNB", "iso_number" => "624"),
							array("id" => "GY", "iso_3" => "GUY", "iso_number" => "328"),
							array("id" => "HT", "iso_3" => "HTI", "iso_number" => "332"),
							array("id" => "HM", "iso_3" => "HMD", "iso_number" => "334"),
							array("id" => "VA", "iso_3" => "VAT", "iso_number" => "336"),
							array("id" => "HN", "iso_3" => "HND", "iso_number" => "340"),
							array("id" => "HU", "iso_3" => "HUN", "iso_number" => "348"),
							array("id" => "IS", "iso_3" => "ISL", "iso_number" => "352"),
							array("id" => "IN", "iso_3" => "IND", "iso_number" => "356"),
							array("id" => "ID", "iso_3" => "IDN", "iso_number" => "360"),
							array("id" => "IR", "iso_3" => "IRN", "iso_number" => "364"),
							array("id" => "IQ", "iso_3" => "IRQ", "iso_number" => "368"),
							array("id" => "IE", "iso_3" => "IRL", "iso_number" => "372"),
							array("id" => "IM", "iso_3" => "IMN", "iso_number" => "833"),
							array("id" => "IL", "iso_3" => "ISR", "iso_number" => "376"),
							array("id" => "IT", "iso_3" => "ITA", "iso_number" => "380"),
							array("id" => "JM", "iso_3" => "JAM", "iso_number" => "388"),
							array("id" => "JP", "iso_3" => "JPN", "iso_number" => "392"),
							array("id" => "JE", "iso_3" => "JEY", "iso_number" => "832"),
							array("id" => "JO", "iso_3" => "JOR", "iso_number" => "400"),
							array("id" => "KZ", "iso_3" => "KAZ", "iso_number" => "398"),
							array("id" => "KE", "iso_3" => "KEN", "iso_number" => "404"),
							array("id" => "KI", "iso_3" => "KIR", "iso_number" => "296"),
							array("id" => "KP", "iso_3" => "PRK", "iso_number" => "408"),
							array("id" => "KR", "iso_3" => "KOR", "iso_number" => "410"),
							array("id" => "KW", "iso_3" => "KWT", "iso_number" => "414"),
							array("id" => "KG", "iso_3" => "KGZ", "iso_number" => "417"),
							array("id" => "LA", "iso_3" => "LAO", "iso_number" => "418"),
							array("id" => "LV", "iso_3" => "LVA", "iso_number" => "428"),
							array("id" => "LB", "iso_3" => "LBN", "iso_number" => "422"),
							array("id" => "LS", "iso_3" => "LSO", "iso_number" => "426"),
							array("id" => "LR", "iso_3" => "LBR", "iso_number" => "430"),
							array("id" => "LY", "iso_3" => "LBY", "iso_number" => "434"),
							array("id" => "LI", "iso_3" => "LIE", "iso_number" => "438"),
							array("id" => "LT", "iso_3" => "LTU", "iso_number" => "440"),
							array("id" => "LU", "iso_3" => "LUX", "iso_number" => "442"),
							array("id" => "MK", "iso_3" => "MKD", "iso_number" => "807"),
							array("id" => "MG", "iso_3" => "MDG", "iso_number" => "450"),
							array("id" => "MW", "iso_3" => "MWI", "iso_number" => "454"),
							array("id" => "MY", "iso_3" => "MYS", "iso_number" => "458"),
							array("id" => "MV", "iso_3" => "MDV", "iso_number" => "462"),
							array("id" => "ML", "iso_3" => "MLI", "iso_number" => "466"),
							array("id" => "MT", "iso_3" => "MLT", "iso_number" => "470"),
							array("id" => "MH", "iso_3" => "MHL", "iso_number" => "584"),
							array("id" => "MQ", "iso_3" => "MTQ", "iso_number" => "474"),
							array("id" => "MR", "iso_3" => "MRT", "iso_number" => "478"),
							array("id" => "MU", "iso_3" => "MUS", "iso_number" => "480"),
							array("id" => "YT", "iso_3" => "MYT", "iso_number" => "175"),
							array("id" => "MX", "iso_3" => "MEX", "iso_number" => "484"),
							array("id" => "FM", "iso_3" => "FSM", "iso_number" => "583"),
							array("id" => "MD", "iso_3" => "MDA", "iso_number" => "498"),
							array("id" => "MC", "iso_3" => "MCO", "iso_number" => "492"),
							array("id" => "MN", "iso_3" => "MNG", "iso_number" => "496"),
							array("id" => "ME", "iso_3" => "MNE", "iso_number" => "499"),
							array("id" => "MS", "iso_3" => "MSR", "iso_number" => "500"),
							array("id" => "MA", "iso_3" => "MAR", "iso_number" => "504"),
							array("id" => "MZ", "iso_3" => "MOZ", "iso_number" => "508"),
							array("id" => "MM", "iso_3" => "MMR", "iso_number" => "104"),
							array("id" => "NA", "iso_3" => "NAM", "iso_number" => "516"),
							array("id" => "NR", "iso_3" => "NRU", "iso_number" => "520"),
							array("id" => "NP", "iso_3" => "NPL", "iso_number" => "524"),
							array("id" => "NL", "iso_3" => "NLD", "iso_number" => "528"),
							array("id" => "AN", "iso_3" => "ANT", "iso_number" => "530"),
							array("id" => "NC", "iso_3" => "NCL", "iso_number" => "540"),
							array("id" => "NZ", "iso_3" => "NZL", "iso_number" => "554"),
							array("id" => "NI", "iso_3" => "NIC", "iso_number" => "558"),
							array("id" => "NE", "iso_3" => "NER", "iso_number" => "562"),
							array("id" => "NG", "iso_3" => "NGA", "iso_number" => "566"),
							array("id" => "NU", "iso_3" => "NIU", "iso_number" => "570"),
							array("id" => "NF", "iso_3" => "NFK", "iso_number" => "574"),
							array("id" => "MP", "iso_3" => "MNP", "iso_number" => "580"),
							array("id" => "NO", "iso_3" => "NOR", "iso_number" => "578"),
							array("id" => "OM", "iso_3" => "OMN", "iso_number" => "512"),
							array("id" => "PK", "iso_3" => "PAK", "iso_number" => "586"),
							array("id" => "PW", "iso_3" => "PLW", "iso_number" => "585"),
							array("id" => "PS", "iso_3" => "PSE", "iso_number" => "275"),
							array("id" => "PA", "iso_3" => "PAN", "iso_number" => "591"),
							array("id" => "PG", "iso_3" => "PNG", "iso_number" => "598"),
							array("id" => "PY", "iso_3" => "PRY", "iso_number" => "600"),
							array("id" => "PE", "iso_3" => "PER", "iso_number" => "604"),
							array("id" => "PH", "iso_3" => "PHL", "iso_number" => "608"),
							array("id" => "PN", "iso_3" => "PCN", "iso_number" => "612"),
							array("id" => "PL", "iso_3" => "POL", "iso_number" => "616"),
							array("id" => "PT", "iso_3" => "PRT", "iso_number" => "620"),
							array("id" => "PR", "iso_3" => "PRI", "iso_number" => "630"),
							array("id" => "QA", "iso_3" => "QAT", "iso_number" => "634"),
							array("id" => "RE", "iso_3" => "REU", "iso_number" => "638"),
							array("id" => "RO", "iso_3" => "ROU", "iso_number" => "642"),
							array("id" => "RU", "iso_3" => "RUS", "iso_number" => "643"),
							array("id" => "RW", "iso_3" => "RWA", "iso_number" => "646"),
							array("id" => "BL", "iso_3" => "BLM", "iso_number" => "652"),
							array("id" => "SH", "iso_3" => "SHN", "iso_number" => "654"),
							array("id" => "KN", "iso_3" => "KNA", "iso_number" => "659"),
							array("id" => "LC", "iso_3" => "LCA", "iso_number" => "662"),
							array("id" => "MF", "iso_3" => "MAF", "iso_number" => "663"),
							array("id" => "PM", "iso_3" => "SPM", "iso_number" => "666"),
							array("id" => "VC", "iso_3" => "VCT", "iso_number" => "670"),
							array("id" => "WS", "iso_3" => "WSM", "iso_number" => "882"),
							array("id" => "SM", "iso_3" => "SMR", "iso_number" => "674"),
							array("id" => "ST", "iso_3" => "STP", "iso_number" => "678"),
							array("id" => "SA", "iso_3" => "SAU", "iso_number" => "682"),
							array("id" => "SN", "iso_3" => "SEN", "iso_number" => "686"),
							array("id" => "RS", "iso_3" => "SRB", "iso_number" => "688"),
							array("id" => "SC", "iso_3" => "SYC", "iso_number" => "690"),
							array("id" => "SL", "iso_3" => "SLE", "iso_number" => "694"),
							array("id" => "SG", "iso_3" => "SGP", "iso_number" => "702"),
							array("id" => "SK", "iso_3" => "SVK", "iso_number" => "703"),
							array("id" => "SI", "iso_3" => "SVN", "iso_number" => "705"),
							array("id" => "SB", "iso_3" => "SLB", "iso_number" => "090"),
							array("id" => "SO", "iso_3" => "SOM", "iso_number" => "706"),
							array("id" => "ZA", "iso_3" => "ZAF", "iso_number" => "710"),
							array("id" => "GS", "iso_3" => "SGS", "iso_number" => "239"),
							array("id" => "SS", "iso_3" => "SSD", "iso_number" => "728"),
							array("id" => "ES", "iso_3" => "ESP", "iso_number" => "724"),
							array("id" => "LK", "iso_3" => "LKA", "iso_number" => "144"),
							array("id" => "SD", "iso_3" => "SDN", "iso_number" => "736"),
							array("id" => "SR", "iso_3" => "SUR", "iso_number" => "740"),
							array("id" => "SJ", "iso_3" => "SJM", "iso_number" => "744"),
							array("id" => "SZ", "iso_3" => "SWZ", "iso_number" => "748"),
							array("id" => "SE", "iso_3" => "SWE", "iso_number" => "752"),
							array("id" => "CH", "iso_3" => "CHE", "iso_number" => "756"),
							array("id" => "SY", "iso_3" => "SYR", "iso_number" => "760"),
							array("id" => "TW", "iso_3" => "TWN", "iso_number" => "158"),
							array("id" => "TJ", "iso_3" => "TJK", "iso_number" => "762"),
							array("id" => "TZ", "iso_3" => "TZA", "iso_number" => "834"),
							array("id" => "TH", "iso_3" => "THA", "iso_number" => "764"),
							array("id" => "TL", "iso_3" => "TLS", "iso_number" => "626"),
							array("id" => "TG", "iso_3" => "TGO", "iso_number" => "768"),
							array("id" => "TK", "iso_3" => "TKL", "iso_number" => "772"),
							array("id" => "TO", "iso_3" => "TON", "iso_number" => "776"),
							array("id" => "TT", "iso_3" => "TTO", "iso_number" => "780"),
							array("id" => "TN", "iso_3" => "TUN", "iso_number" => "788"),
							array("id" => "TR", "iso_3" => "TUR", "iso_number" => "792"),
							array("id" => "TM", "iso_3" => "TKM", "iso_number" => "795"),
							array("id" => "TC", "iso_3" => "TCA", "iso_number" => "796"),
							array("id" => "TV", "iso_3" => "TUV", "iso_number" => "798"),
							array("id" => "UG", "iso_3" => "UGA", "iso_number" => "800"),
							array("id" => "UA", "iso_3" => "UKR", "iso_number" => "804"),
							array("id" => "AE", "iso_3" => "ARE", "iso_number" => "784"),
							array("id" => "GB", "iso_3" => "GBR", "iso_number" => "826"),
							array("id" => "US", "iso_3" => "USA", "iso_number" => "840"),
							array("id" => "UM", "iso_3" => "UMI", "iso_number" => "581"),
							array("id" => "UY", "iso_3" => "URY", "iso_number" => "858"),
							array("id" => "UZ", "iso_3" => "UZB", "iso_number" => "860"),
							array("id" => "VU", "iso_3" => "VUT", "iso_number" => "548"),
							array("id" => "VE", "iso_3" => "VEN", "iso_number" => "862"),
							array("id" => "VN", "iso_3" => "VNM", "iso_number" => "704"),
							array("id" => "VI", "iso_3" => "VIR", "iso_number" => "850"),
							array("id" => "WF", "iso_3" => "WLF", "iso_number" => "876"),
							array("id" => "EH", "iso_3" => "ESH", "iso_number" => "732"),
							array("id" => "YE", "iso_3" => "YEM", "iso_number" => "887"),
							array("id" => "ZM", "iso_3" => "ZMB", "iso_number" => "894"),
							array("id" => "ZW", "iso_3" => "ZWE", "iso_number" => "716"));
	}
	
	public function get_all_cities($id='',$search = "", $page = "1", $lang = DEFAULT_LANG)
	{
		$more_condition = "";
		$region_condition = "";
		
		$query = "SELECT `id`, `city_en` AS `text` FROM `cities` WHERE `id` <> '0' $more_condition";
		if($search !== "")
			$query .= " AND `city_en` LIKE '$search%'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		$total_cities = $this->CI->db->query($query)->num_rows();
		$cities = array();
		if($total_cities > 0)
		{
			$query .= " ORDER BY `city_en`";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->CI->db->query($query);
			$cities = $result->result();
		}
		return $total_cities > 0 ? array("total" => $total_cities, "results" => $cities) : array("total" => "0", "results" => array(), "query" => $this->CI->db->last_query());
	}
	
	

}