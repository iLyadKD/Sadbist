<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Common_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	/* Get complete countries list */
	function get_country_list()
	{
		$query = "SELECT `id`, `country` FROM `countries` ORDER BY CASE WHEN `id` = '".OTHER_COUNTRY."' THEN 1 ELSE 0 END, `country`";
		$result = $this->db->query($query);
		if ($result->num_rows() > 0 )     
			return $result->result();
		return false;
	}

	/* Get complete state / region for specific country */
	public function get_regions($country_code)
	{
		$query = "SELECT `country`, `region`, `name` FROM `regions` WHERE `country` = '".$country_code."' ORDER BY CASE WHEN `region` = '".NO_REGION."' THEN 1 ELSE 0 END, `region`";
		$result = $this->db->query($query);
		if ($result->num_rows() > 0 )     
			return $result->result();
		return false;
	}

	/* Get complete city list for particular region and country */
	public function get_cities($region_code, $country_code)
	{
		if($region_code === NO_REGION)
			$query = "SELECT `id`, `city` FROM `cities` WHERE `country` = '".$country_code."' ORDER BY `city`";
		else
			$query = "SELECT `id`, `city` FROM `cities` WHERE `region` = '".$region_code."' AND `country` = '".$country_code."' ORDER BY `city`";
		$result = $this->db->query($query);
		if ($result->num_rows() > 0 )     
			return $result->result();
		return false;
	}

	 public function get_cities_list($name,$get_cities_list=null){   
	    $this->db->select('*');
	    $this->db->from('cities ci');
	    $this->db->like('keyword',$name);
	    $this->db->limit(0,15);
		if($get_cities_list){
			$city_ids = json_decode($get_cities_list);
			$this->db->where_in('id',$city_ids);
		}
	    $query = $this->db->get();
	    if ( $query->num_rows() > 0 ) { 
	        return $query->result();
	    }
	      return false;
    }

	public function get_active_social_media()
	{
		$query = "SELECT `name`, `icon`, `url` FROM `social_network` WHERE `status` = '1' LIMIT 0, 6";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_active_sliders()
	{
		$query = "SELECT `image` FROM `home_sliders` WHERE `status` = '1' LIMIT 0, 5";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_active_quick_link_pages($lang = DEFAULT_LANG)
	{
		$query = "SELECT `title_".$lang."` AS `title`, `content_".$lang."` AS `content`, `slug` FROM `pages` WHERE `status` = '1' AND `link_type` = '".QUICK_LINK_PAGE."' LIMIT 0, 5";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_active_traveller_tool_pages($lang = DEFAULT_LANG)
	{
		$query = "SELECT `title_".$lang."` AS `title`, `content_".$lang."` AS `content`, `slug` FROM `pages` WHERE `status` = '1' AND `link_type` = '".TRAVELLER_TOOL_PAGE."' LIMIT 0, 5";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_active_legal_pages($lang = DEFAULT_LANG)
	{
		$query = "SELECT `title_".$lang."` AS `title`, `content_".$lang."` AS `content`, `slug` FROM `pages` WHERE `status` = '1' AND `link_type` = '".LEGAL_PAGE."' LIMIT 0, 5";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_active_about_10020_pages($lang = DEFAULT_LANG)
	{
		$query = "SELECT `title_".$lang."` AS `title`, `content_".$lang."` AS `content`, `slug` FROM `pages` WHERE `status` = '1' AND `link_type` = '".ABOUT_10020_PAGE."' LIMIT 0, 5";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}
}