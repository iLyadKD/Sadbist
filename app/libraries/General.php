<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class General {

	private $ci;
	public function __construct()
	{
		$this->ci =& get_instance();
		function pr($data){ echo "<pre>"; print_r($data);echo "</pre>"; }
			
		
	}

	public function get_active_countries($lang = DEFAULT_LANG)
	{
		$curLan = $_SESSION['default_language'];
		
		$query = "SELECT `id`, `country_".$curLan."` AS `country_name` FROM `countries` WHERE `status` = '1' ORDER BY CASE WHEN `id` = '".OTHER_COUNTRY."' THEN 1 ELSE 0 END, `country_".$curLan."`";

		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function static_page_by_id($id, $lang = DEFAULT_LANG)
	{
	$query = "SELECT `title_".$lang."` AS `title`,`content_".$lang."` AS `content` FROM `pages` WHERE `status` = '1' and `id`='$id'";

		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function default_contact_address($lang = DEFAULT_LANG)
	{
		$query = "SELECT `title_".$lang."` AS `title`, `address_".$lang."` AS `address`, `contact`, `email`, `website` FROM `contact_details` ORDER BY `priority` LIMIT 0, 1";
		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function contact_locations($lang = DEFAULT_LANG)
	{
		$query = "SELECT `title_".$lang."` AS `title`, `latitude`, `longitude`, `image`, `marker_image` FROM `contact_locations` WHERE `status` = '1'";
		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_active_currencies($lang = DEFAULT_LANG)
	{
		$query = "SELECT `id`, `country_".$lang."` AS `country_name`, `code`, `currency` FROM `countries` INNER JOIN `currencies` ON `id` = `country` WHERE `countries`.`status` = '1' ORDER BY CASE WHEN `id` = '".OTHER_COUNTRY."' THEN 1 ELSE 0 END, `country_".$lang."`";
		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_all_countries($lang = DEFAULT_LANG)
	{
		$query = "SELECT `id`, `country_".$lang."` AS `country_name` FROM `countries` ORDER BY CASE WHEN `id` = '".OTHER_COUNTRY."' THEN 1 ELSE 0 END, `country_".$lang."`";
		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}


	/* Get complete state / region for specific country */
	public function get_regions($country_code)
	{
		$query = "SELECT `country`, `region`, `name` FROM `regions` WHERE `country` = '".$country_code."' ORDER BY CASE WHEN `region` = '".NO_REGION."' THEN 1 ELSE 0 END, `region`";
		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	/* Get complete city list for particular region and country */
	public function get_cities($region_code, $country_code)
	{
		if($region_code === NO_REGION)
			$query = "SELECT `id`, `city` FROM `cities` WHERE `country` = '".$country_code."' ORDER BY `city`";
		else
			$query = "SELECT `id`, `city` FROM `cities` WHERE `region` = '".$region_code."' AND `country` = '".$country_code."' ORDER BY `city`";
		$result = $this->ci->db->query($query);
		if ($result->num_rows() > 0 )     
			return $result->result();
		return false;
	}

	/*generate salt and encrypt password*/  
	public function generate_salt_pwd($pass)
	{
		$this->ci->load->library('encrypt');
		$pass = $pass.SALT;
		$salt = hash('sha256', $pass);
		$key = $salt.SALT.$pass; 
		$key = md5($key); 
		$password = SHA1($key);
		$salt = $this->ci->encrypt->encode($salt);
		return array('password'=>$password, 'salt'=>$salt);
	}

	/* check valid password */
	public function validate_pass($enc, $salt, $plain)
	{
		$this->ci->load->library('encrypt');
		$salt = $this->ci->encrypt->decode($salt);
		$password = $plain.SALT;
		$key = $salt.SALT.$password;
		$new_password = SHA1(md5($key));
		return strcmp($enc, $new_password) === 0 ? true : false;
	}

	/*generate random url key*/
	public function generate_random_key($length = 20)
	{
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		$final_array = array_merge($alphabets,$numbers);            
		$secret = '';

		while($length--) {
			$key = array_rand($final_array);
			$secret .= $final_array[$key];
		}
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

	function minutes_to_hours($min)
	{
		return str_pad($min, 2, "0", STR_PAD_LEFT).":00:00";
	}
}
