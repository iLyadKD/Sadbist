<?php if(!defined('BASEPATH')) exit('No direct script access allowed');   
class Home_Model extends CI_Model {

	function __construct(){
			parent::__construct();
		 }
     
     function get_package_types(){
		$this->db->select('*')
			       ->from('package_type') ;           
		$query = $this->db->get();
      	if($query->num_rows() > 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	 }
	 
	 function get_neighbours($lang = DEFAULT_LANG){
		$this->db->select('tour_hotel.neighbours_'.$lang.' AS neighbours,tour_hotel.hotel_id,tour_hotel.rating')
			       ->group_by(`tour_hotel`.`hotel_id`)
				   ->from('tour_hotel')->where('neighbours_'.$lang.' !=','') ;           
		$query = $this->db->get();
      	if($query->num_rows() > 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	 }
	 
	 
	 
	 function get_hotels()
     {
     	$query = $this->db->query("select * from `home_packages` WHERE `type` = 'hotel' AND `available` = 1");
		return $query->num_rows() > 0 ? $query->result() : false;
     } 
     
     function get_cars()
     {
     	$query = $this->db->query("select * from `home_packages` WHERE `type` = 'car' AND `available` = 1");
		return $query->num_rows() > 0 ? $query->result() : false;
     } 
     
     function get_packages()
     {
     	$query = $this->db->query("select * from `home_packages` WHERE `type` = 'package' AND `available` = 1");
		return $query->num_rows() > 0 ? $query->result() : false;
     } 
     
     function get_cruises()
     {
     	$query = $this->db->query("select * from `home_packages` WHERE `type` = 'cruise' AND `available` = 1");
		return $query->num_rows() > 0 ? $query->result() : false;
     }  

     function get_sliders()
     {
     	$query = $this->db->query("select * from `home_packages` WHERE `type` = 'slider' AND `available` = 1");
		return $query->num_rows() > 0 ? $query->result() : false;
     }

     function get_explores()
     {
        $query = $this->db->query("select * from `home_explore` WHERE `available` = 1");
        return $query->num_rows() > 0 ? $query->result() : false;
     }

     function get_latest_news()
     {
          $query = $this->db->query("select * from `latest_news`");
        return $query->num_rows() > 0 ? $query->result() : false;
     }

     function get_best_price()
     {
          $query = $this->db->query("select * from `home_best_price` WHERE `status` = '1'");
        return $query->num_rows() > 0 ? $query->row()->image : false;
     }

     function get_contact()
     {
      $query = $this->db->query("select * from `contact` WHERE `id` = 1");
    return $query->num_rows() > 0 ? $query->row() : false;
     } 
	
	function country_by_id($id,$lang = DEFAULT_LANG)
     {
      $query = $this->db->query("select country_".$lang." AS country from `countries` WHERE `id` = '$id'");
    return $query->num_rows() > 0 ? $query->row() : false;
     } 

     function get_packages_city($country)
     {    
          $this->db->select('country_en,id');
          $this->db->from('countries');
          $this->db->like('country_en', $country);
          //$this->db->limit(10, 12);
          $result = $this->db->get();
         // echo $this->db->last_query();
          return $result->result();
     } 
     
      function get_package_type($lang)
     {
          $query = $this->db->query("select type_id,$lang as type_name from `package_type` ");
          return $query->num_rows() > 0 ? $query->result() : false;
     }

       function get_media_list()
     {
          $query = $this->db->query("select * from `home_media` ");
          return $query->num_rows() > 0 ? $query->result() : false;
     } 

       function get_cms_list()
     {
          $query = $this->db->query("select * from `cms` WHERE `status` = '1' ");
          return $query->num_rows() > 0 ? $query->result() : false;
     }

     function get_page_details($id)
     {
      $query = $this->db->query("select * from `cms` WHERE `id` = '$id' AND `status` = '1' ");
          return $query->num_rows() > 0 ? $query->row() : false;
     }
	 
	 function getcity_code($city_id, $lang = DEFAULT_LANG){
		$query = $this->db->query("select `id`,`keyword`,`country`,`city_".$lang."` AS `city` from `cities` WHERE `id` = '$city_id' ");
		//echo $this->db->last_query();exit;
		return $query->num_rows() > 0 ? $query->result() : false;
		
	 }
	 
	 function get_countries(){
		$this->db->select('country_en')->order_by('country_en','ASC')
			       ->from('countries') ;           
		$query = $this->db->get();
      	if($query->num_rows() > 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	 }


}