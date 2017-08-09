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

	 public function get_cities_list($name,$get_cities_list=null,$lang=DEFAULT_LANG){   
		$this->db->select('ci.*,c.country_'.$lang.' AS country,ci.city_'.$lang.' AS city');
		$this->db->join('countries c','c.id=ci.country','left');
	    $this->db->from('cities ci');
	    $this->db->like('ci.keyword',$name);
	    $this->db->limit(0,15);
		if($get_cities_list){
			$city_ids = json_decode($get_cities_list);
			
			if(!empty($city_ids))
				$this->db->where_in('ci.id',$city_ids);
			else
				return false;
		}
	    $result = $this->db->get();
	    return $result->num_rows() > 0 ? $result->result() : false;
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
		$query = "SELECT `title_".$lang."` AS `title`, `content_".$lang."` AS `content`, `slug` FROM `pages` WHERE `status` = '1' AND `link_type` = '".ABOUT_COMPANY_PAGE."' LIMIT 0, 5";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_promo_by_code($code)
	{
		$query = "SELECT * FROM `promo_code` WHERE `code` = '$code' AND `status` = '1'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_deals($lang = DEFAULT_LANG,$deal_type = "deals")
	{
		$query = "SELECT *, `content_$lang` AS `content` FROM `home_deals` WHERE `status` = '1' AND `display_section` = '$deal_type'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_deals_hotels($lang = DEFAULT_LANG,$deal_type = "deals"){

		$query = "SELECT *, `hotel_name_$lang` AS `content` FROM `home_hotel_deals` WHERE `status` = '1'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_tour_deals($lang = DEFAULT_LANG)
	{
		$query = "SELECT *, `content_$lang` AS `content`, `address_$lang` AS `address`, `hotel_name_$lang` AS `hotel_name` FROM `home_tour_deals` WHERE `status` = '1'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_hotel_deals($lang = DEFAULT_LANG)
	{
		$query = "SELECT *, `address_$lang` AS `address`, `hotel_name_$lang` AS `hotel_name` FROM `home_hotel_deals` WHERE `status` = '1'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_latest_news($lang = DEFAULT_LANG)
	{
		//$query = "SELECT `content_$lang` AS `content` FROM `home_news` WHERE `status` = '1' LIMIT 0, 3";
                $query = "SELECT `content_$lang` AS `content`,id as id FROM `home_news` WHERE `status` = '1' LIMIT 0, 3";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_attractions($lang = DEFAULT_LANG)
	{
		$query = "SELECT *, `tour_$lang` AS `tour`, `address_$lang` AS `address`, `content_$lang` AS `content` FROM `home_attractions` WHERE `status` = '1' LIMIT 0,4";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function set_special_deal_request($data){
		$qry = $this->db->insert('special_deal_request',$data);
		return $this->db->insert_id();
	}


	public function getStaticPageDetails($pId, $lang)
	{
		$query = "SELECT * FROM `pages` WHERE `id` = '".$pId."'";
		$result = $this->db->query($query);
		if ($result->num_rows() > 0 )     

			$res = $result->result();
			
			if($res){

				$name = 'title_'.$lang;
				$con = 'content_'.$lang;

				$titl = $res[0]->$name;
				$con = $res[0]->$con;
				return array($titl,$con);
			}

		return false;
	}


        public function getNewscontent($id){

		$query = "SELECT * FROM `home_news` WHERE `id` = '".$id."'";
		$result = $this->db->query($query);

		if ($result->num_rows() > 0 ) {    

			$res = $result->result();
			
			return $res;

		    }

	   }



       

}
