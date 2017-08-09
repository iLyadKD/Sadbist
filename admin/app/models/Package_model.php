<?php
class Package_Model extends CI_Model {
	
  function get_package_type(){
    $this->db->select('*')
             ->from('package_type');
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  
  

  function get_tour($lang=DEFAULT_LANG){
    $this->db->select('th.*,c.country_'.$lang.' as d_country,cy.city_'.$lang.' as d_city,pt.package_type as tour_type')
             ->from('tour th')
             ->join('countries c','c.id=th.d_country','left')
             ->join('cities cy','cy.id=th.d_city','left')
             ->join('package_type pt','pt.type_id=th.tour_type','left')
             ->order_by('th.tour_id','desc');
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  function get_tour_id($id){
    $this->db->select('*')
             ->from('tour')
             ->where('tour_id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }
  function master_details($id,$type){
    $this->db->select('mp.*')
             ->from('master_price mp')
			  ->where('mp.price_type',$type)
			 ->order_by('mp.id','asc')
			 ->limit(1)
             ->where('tour_id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }

  function get_hotel_id($id){
    $this->db->select('*')
             ->from('tour_hotel')
             ->where_in('hotel_id',$id)
			 ->order_by('hotel_id','desc');
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  
  


	function get_country(){
		$this->db->select('country_en as name,id as country_code')
			       ->from('countries');
		$query = $this->db->get();
      	if($query->num_rows()> 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	}

  function get_langauge(){
    $this->db->select('*')
             ->from('langauge');
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }

  function get_details_id($tour_id){
    $this->db->select('*')
              ->from('tour_details')
              ->where('tour_id',$tour_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }

  function get_images($tour_id){
    $this->db->select('*')
              ->from('tour_gallery')
              ->where('tour_id',$tour_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  function get_file($tour_id){
    $this->db->select('*')
              ->from('tour_file')
              ->where('tour_id',$tour_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }

  function get_flight($tour_id){
    $this->db->select('*')
              ->from('tour_flight')
              ->where('tour_id',$tour_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }

  function get_price($tour_id,$hotel_id,$price_type,$flag,$room_type){
    $this->db->select('*,room_type_en as room_type')
              ->from('master_price')
              ->where('tour_id',$tour_id)
              ->where('hotel_id',$hotel_id)
              ->where('price_type',$price_type)
			  ->where('extra_price_flag',$flag)
			  ->where('room_type_en',$room_type)
			  ->limit(1);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  function food_type($tour_id,$hotel_id){
    $this->db->select('m.food_type')
              ->from('master_price m')
              ->where('tour_id',$tour_id)
              ->where('hotel_id',$hotel_id)
			  ->order_by('m.id','ASC')
			  ->limit(1);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result()[0];
        }else {
          return false;
        }
  }

  function get_city($city,$lang = DEFAULT_LANG){
    $this->db->select('*,`cities`.`city_'.$lang.'` AS city ')
              ->from('cities')
              ->where('country',$city);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }


  function save_tour($data){
    $query = $this->db->insert('tour', $data); 
        if($query) { 
          return $this->db->insert_id();
        }else {
          return false;
        }
  }

	function save_itinerary($data){
		$query = $this->db->insert_batch('tour_itinerary', $data); 
      	if($query) { 
        	return true;
      	}else {
      		return false;
      	}
	}

	function save_images($data,$id){
		$this->db->where('tour_id', $id);
		$q = $this->db->get('tour_gallery');
		if($q->num_rows() > 0 )  {
		  $this->db->where('tour_id',$id);
		  $query = $this->db->update('tour_gallery', $data);
		}else{
		  $this->db->set('tour_id', $id);
		  $query = $this->db->insert('tour_gallery', $data); 
		}
		
		if($query) { 
			  return true;
		}else {
			  return false;
		}
	}
	
	function save_file($data,$id){
		$this->db->where('tour_id', $id);
		$q = $this->db->get('tour_file');
		if($q->num_rows() > 0 )  {
		  $this->db->where('tour_id',$id);
		  $query = $this->db->update('tour_file', $data);
		}else{
		  $this->db->set('tour_id', $id);
		  $query = $this->db->insert('tour_file', $data); 
		}
		
		if($query) { 
			  return true;
		}else {
			  return false;
		}
	}

	function save_details($data){
		//pr($data);exit;
		$query = $this->db->insert('tour_details', $data); 
      	if($query) { 
			return true;
      	}else {
      		return false;
      	}
	}

	function save_flight($data){
		$query = $this->db->insert_batch('tour_flight', $data); 
      	if($query) { 
        	return true;
      	}else {
      		return false;
      	}
	}


  function save_price($data){
	$query = $this->db->insert_batch('tour_price', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function save_price_master($data){
	//pr($data);exit;
	$query = $this->db->insert_batch('master_price', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function save_bus($data){
		$query = $this->db->insert_batch('tour_bus', $data); 
      	if($query) { 
        	return true;
      	}else {
      		return false;
      	}
	}
	
	function save_train($data){
		$query = $this->db->insert_batch('tour_train', $data);
      	if($query) { 
        	return true;
      	}else {
      		return false;
      	}
	}
	
	function save_cruise($data){
		$query = $this->db->insert_batch('tour_cruise', $data); 
      	if($query) { 
        	return true;
      	}else {
      		return false;
      	}
	}
	
	function save_type_price($data){
		//pr($data);exit;
		$query = $this->db->insert_batch('tour_price_room_type', $data); 
      	if($query) { 
        	return true;
      	}else {
      		return false;
      	}
	}
	
  function get_bus($package_id){
    $this->db->select('*')
              ->from('tour_bus')
              ->where('tour_id',$package_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  
  function get_train($package_id){
    $this->db->select('*')
              ->from('tour_train')
              ->where('tour_id',$package_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  
  function get_cruise($package_id){
    $this->db->select('*')
              ->from('tour_cruise')
              ->where('tour_id',$package_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
	
  function update_bus($data){
	$query = $this->db->update_batch('tour_bus', $data,'tour_bus_id'); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  function update_type_price($data,$tour_id){
	//pr($data);exit;
	$this->db->where_in('tour_id', $tour_id);
	$dq = $this->db->delete('tour_price_room_type');
	if($dq){
	  //pr($data);exit;
	  $query = $this->db->insert_batch('tour_price_room_type', $data); 
	  if($query) { 
		return true;
	  }else {
		return false;
	  }
	}
	
  }
  
  function update_train($data){
	$query = $this->db->update_batch('tour_train', $data,'tour_train_id'); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function update_cruise($data){
	$query = $this->db->update_batch('tour_cruise', $data,'tour_cruise_id'); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  
  function update_tour($id,$data){
   // pr($data);exit;
	$this->db->where('tour_id', $id); 
    $query = $this->db->update('tour', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }
  
  function update_tour_master_price($id,$data){
    $this->db->where('tour_id', $id); 
    $query = $this->db->update('master_price', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }



  function update_images($data){
    $query = $this->db->update_batch('tour_gallery', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function update_details($id,$data){ 
    $this->db->where('tour_id', $id); 
    $query = $this->db->update('tour_details', $data);
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

function update_price($data){
  $query = $this->db->update_batch('tour_price', $data,'id'); 
	  if($query) { 
		return true;
	  }else {
		return false;
	  }
}



function master_price_updation($id,$tr_cost){
	$get_old_datas = $this->get_old_datas($id);
	
	if(!empty($get_old_datas)){
	  $d_arr = [];
	  foreach($get_old_datas as $get){
		$get->total_tour_price = $get->doubles+$tr_cost;
		$d_arr[] = $get;
		
	  }
	  
	  if(!empty($d_arr)){
		$query = $this->db->update_batch('master_price', $d_arr,'id'); 
		if($query) { 
		return true;
		}else {
		return false;
		}
	  }
	}
	
  }
  
  function get_old_datas($id){
	$this->db->select('mp.id,mp.doubles')
              ->from('master_price mp')
              ->where('tour_id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
	
  }

  function update_flight($data){
	$query = $this->db->update_batch('tour_flight', $data,'tour_flight_id'); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function update_duration($data){
    $query = $this->db->update_batch('tour_duration', $data,'dur_id'); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }


  function update_cancel($data){
    $query = $this->db->update_batch('tour_cancellation', $data,'can_id'); 
    
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function update_status($id,$data){
    $this->db->where('tour_id', $id);
    $query = $this->db->update('tour', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete($id){
    $this->db->delete('tour', array('tour_id' => $id)); 
    $this->db->delete('tour_details', array('tour_id' => $id)); 
    $this->db->delete('tour_flight', array('tour_id' => $id));
	$this->db->delete('tour_train', array('tour_id' => $id));
	$this->db->delete('tour_bus', array('tour_id' => $id)); 
    $this->db->delete('tour_cruise', array('tour_id' => $id)); 
    $query =  $this->db->delete('master_price', array('tour_id' => $id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete_image($id){
    $query = $this->db->delete('tour_gallery', array('id' => $id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete_hotel_price($hotel_id,$tour_id){
    $query = $this->db->delete('master_price', array('hotel_id' => $hotel_id,'tour_id' => $tour_id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete_flight($id,$tour_id){
    $query = $this->db->delete('tour_flight', array('tour_flight_id' => $id,'tour_id' => $tour_id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete_bus($id,$tour_id){
    $query = $this->db->delete('tour_bus', array('tour_bus_id' => $id,'tour_id' => $tour_id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete_train($id,$tour_id){
    $query = $this->db->delete('tour_train', array('tour_train_id' => $id,'tour_id' => $tour_id));
    //echo $this->db->last_query(); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function delete_cruise($id,$tour_id){
    $query = $this->db->delete('tour_cruise', array('tour_cruise_id' => $id,'tour_id' => $tour_id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function save_vendor($data){
    $query = $this->db->insert('vendor', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
    function get_vendor(){
	  $this->db->select('v.*')
			   ->from('vendor v')             
			   ->order_by('v.id','desc');
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->result();
	  }else {
		return false;
	  }
	}
	
  function get_vendor_id($id){
	  $this->db->select('*')
			   ->from('vendor')
			   ->where('id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->row();
	  }else {
		return false;
	  }
  }
  
  function update_vendor($id,$data){
    $this->db->where('id', $id); 
    $query = $this->db->update('vendor', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }
  
  function check_tour_id($id){
	  $this->db->select('t.custom_tour_id')
			   ->from('tour t')
			   ->where('t.custom_tour_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return 1;
	  }else {
		return 0;
	  }
  }
  
  function check_vendor_id($id){
	  $this->db->select('v.id')
			   ->from('vendor v')
			   ->where('v.vendor_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return 1;
	  }else {
		return 0;
	  }
  }
  
  function check_vendor_log_id($id){
	  $this->db->select('v.id')
			   ->from('vendor v')
			   ->where('v.login_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return 1;
	  }else {
		return 0;
	  }
  }
  
  function hotel_detail($id){
	  $this->db->select('th.name_en AS name,th.hotel_id')
			   ->from('tour_hotel th')
			   ->where('hotel_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->row();
	  }else {
		return false;
	  }
  }
  
  function get_price_master($hotel_id,$tour_id,$flag,$days,$room_type){
	$this->db->select('*')
             ->from('master_price')
             ->where('hotel_id',$hotel_id)
			 ->where('tour_id',$tour_id)
			 ->where('extra_price_flag',$flag)
			 ->where('room_type_en',$room_type)
			  ->where_in('tour_day',$days)
			 ->order_by('id','ASC');
      // ->order_by('name','asc');
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  
  function copyandinsert($id,$day,$datas,$exist_day){
	if($exist_day == 1) $exist_day = "Mon";
	if($exist_day == 2) $exist_day = "Tue";
	if($exist_day == 3) $exist_day = "Wed";
	if($exist_day == 4) $exist_day = "Thu";
	if($exist_day == 5) $exist_day = "Fri";
	if($exist_day == 6) $exist_day = "Sat";
	if($exist_day == 7) $exist_day = "Sun";
	
	$days = [];
	foreach($day as $d){
	  if($d == 1) $days[] = 'Mon';
	  if($d == 2) $days[] = 'Tue';
	  if($d == 3) $days[] = 'Wed';
	  if($d == 4) $days[] = 'Thu';
	  if($d == 5) $days[] = 'Fri';
	  if($d == 6) $days[] = 'Sat';
	  if($d == 7) $days[] = 'Sun';
	}
	
	
	
	 $this->db->select('tour_id,hotel_id,d_hotel,price_type,overall_tour_price,total_tour_price,flag_discount,discount_price,tour_date,tour_day,single,single_qty,doubles,double_qty,triple,triple_qty,twotosix,twotosix_qty,sixtotwelve,sixtotwelve_qty,twelvetosixteenth,twelvetosixteenth_qty,infants,infants_qty,handle_charge')
			   ->from('master_price mp')             
			   ->order_by('mp.id','ASC')
			   ->where('mp.tour_day',$exist_day)
			   ->where('mp.tour_id',$id);
	  $query = $this->db->get();
	 $insert = [];
	 $all_arr = [];
	 
	 
	  foreach($datas as $data){
		foreach ($query->result() as $row) {
		  //pr($query->result());exit;
		  $d = explode("/",$data);
		  $day = $d[1];
		  $date = $d[0];
		 
		  $row->tour_date = $date;
		  $row->tour_day = $day;
		 
		  if(!in_array($row->price_type.'-'.$row->tour_date.'-'.$row->hotel_id,$all_arr)){
			$insert[] = $row;
		   
			$all_arr[] = $row->price_type.'-'.$row->tour_date.'-'.$row->hotel_id;
		  }
		}
		//pr($insert);exit;
		
	  }
	  
	  
	  $copy_data = [];
	  foreach($insert as $ex){
		  foreach($ex as $n=>$vals){
			  $copy_data['extra_price_flag'] = 1;
			  
				  $copy_data[$n] = 0;
				  if($n == 'tour_id') $copy_data['tour_id'] = $vals;
				  if($n == 'd_hotel') $copy_data['d_hotel'] = $vals;
				  if($n == 'hotel_id') $copy_data['hotel_id'] = $vals;
				  if($n == 'tour_date') $copy_data['tour_date'] = $vals;
				  if($n == 'tour_day') $copy_data['tour_day'] = $vals;
				  if($n == 'price_type') $copy_data['price_type'] = $vals;
		  }
		  $assemble[] = $copy_data;
	  }
	  
	 
	 
	 if($this->db->insert_batch('master_price',$insert)){
	  
	  if($this->db->insert_batch('master_price',$assemble)){
		return true;
	  }else{
		return false;
	  }
	  
	 }else{
	  return false;
	 }

		  
		  
  }
  
  function update_price_master($data){
	$query = $this->db->update_batch('master_price', $data,'id'); 
		if($query) { 
		  return true;
		}else {
		  return false;
		}
  }
  
  function get_default_hotel($id){
	  $this->db->select('t.default_hotel')
			   ->from('tour t')
			   ->where('tour_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->row()->default_hotel;
	  }else {
		return false;
	  }
  }
  
  function get_room_types_only($id){
	  $this->db->select('t.room_type')
			   ->from('tour t')
			   ->where('tour_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->result()[0]->room_type;
	  }else {
		return false;
	  }
  }
  
  
  
  function price_types($tour_id,$hotel_id){
    $this->db->select('*')
              ->from('tour_price_room_type')
              ->where('tour_id',$tour_id)
			  ->where('hotel_id',$hotel_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
		  return $query->result();
        }else {
          return false;
        }
  }
  
  function delete_vendor($id){
    $query = $this->db->delete('vendor', array('id' => $id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function save_airlines($data){
    $query = $this->db->insert('tour_airlines', $data); 
        if($query) { 
          return $this->db->insert_id();
        }else {
          return false;
        }
  }
  function save_airlines_logo($id,$data){
    $this->db->where('id', $id); 
	$query = $this->db->update('tour_airlines', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }
  
  function get_airlines(){
    $this->db->select('*')
             ->from('tour_airlines')
			 ->order_by('id','DESC');
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result();
        }else {
          return false;
        }
  }
  
  function update_airlines($id,$data){
    $this->db->where('id', $id); 
    $query = $this->db->update('tour_airlines', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }
  
  function get_airlines_id($id){
    $this->db->select('*')
             ->from('tour_airlines')
             ->where('id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->result()[0];
        }else {
          return false;
        }
  }
  
  function delete_airlines($id){
    $query = $this->db->delete('tour_airlines', array('id' => $id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function update_airline_status($id,$data){
    $this->db->where('id', $id);
    $query = $this->db->update('tour_airlines', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }
  
  function retrieve_data($id){
    $this->db->select('mp.*,t.no_of_night,t.o_city,d_city,o_country,d_country')
              ->from('master_price mp')
			  ->join('tour t','t.tour_id=mp.tour_id','left')
              ->where('id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }
  
  function get_trans_flag($id){
	$query1 = 'SELECT tour_flight_id FROM `tour_flight` WHERE `tour_id`='.$id.' ORDER BY flight_price ASC';
	$result1 = $this->db->query($query1);
	
	$query2 = 'SELECT tour_bus_id FROM `tour_bus` WHERE `tour_id`='.$id.' ORDER BY price ASC';
	$result2 = $this->db->query($query2);
	
	$query3 = 'SELECT tour_cruise_id FROM `tour_cruise` WHERE `tour_id`='.$id.' ORDER BY price ASC';
	$result3 = $this->db->query($query3);
	
	$query4 = 'SELECT tour_train_id FROM `tour_train` WHERE `tour_id`='.$id.' ORDER BY price ASC';
	$result4 = $this->db->query($query4);
	
	if($result1->num_rows() > 0){
	  return 'flight-'.$result1->row()->tour_flight_id.'';
	}elseif($result2->num_rows() > 0){
	  return 'bus-'.$result2->row()->tour_bus_id.'';
	}elseif($result3->num_rows() > 0){
	 return 'cruise-'.$result3->row()->tour_cruise_id.'';
	}elseif($result4->num_rows() > 0){
	  return 'train-'.$result4->row()->tour_train_id.'';
	}else{
	  return 0;
	}
	
  }
  
  function get_location($city_id){
	 $this->db->select('city.city_en AS city,country.country_en AS country')
              ->from('cities city')
			  ->join('countries country','country.id=city.country','left')
              ->where('city.id',$city_id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }
  
  public function tour_links($code = "", $search = "", $page = "1")
	{
		$query = "SELECT `mp`.`id` AS `id`, CONCAT(`t`.`tour_name_en`,', ',`th`.`name_en`,', ',DATE_FORMAT(`mp`.`tour_date`,'%m/%d/%Y')) AS `text` FROM `master_price` AS `mp` LEFT JOIN `tour_hotel` AS `th` ON `th`.`hotel_id` = `mp`.`hotel_id` LEFT JOIN `tour` AS `t` ON `t`.`tour_id`=`mp`.`tour_id` LEFT JOIN `cities` as `city` ON `city`.`id`=`t`.`d_city` WHERE `mp`.`room_type_en`='Standard' AND `mp`.`price_type`=1 AND `mp`.`extra_price_flag`=0  ";
		
 		if($search !== "")
			$query .= " AND (`th`.`name_en` LIKE '$search%' OR `city_en` LIKE '$search%')";
			
		if($code !== "")
			$query .= " AND `mp`.`id` = '$code'";
		$total_links = $this->db->query($query)->num_rows();
		
		$links = array();
		if($total_links > 0)
		{
			$query .= " ORDER BY `mp`.`tour_date` DESC";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->db->query($query);
			$links = $result->result();
		}
		return array("total" => $total_links, "results" => $links);
	}
	
	public function tour_deals_data($code = "", $search = "", $page = "1")
	{
		$query = "SELECT `mp`.`id` AS `id`,`mp`.`total_tour_price` AS `price`, CONCAT(`th`.`name_en`,', ',`th`.`name_fa`,', ',`th`.`rating`,', ',`city`.`city_en`,', ','city_fa',', ',`country`.`country_en`,', ',`country`.`country_fa`) AS `info`,CONCAT(`t`.`tour_name_en`,', ',`th`.`name_en`,', ',DATE_FORMAT(`mp`.`tour_date`,'%m/%d/%Y')) AS `text` FROM `master_price` AS `mp` LEFT JOIN `tour_hotel` AS `th` ON `th`.`hotel_id` = `mp`.`hotel_id` LEFT JOIN `tour` AS `t` ON `t`.`tour_id`=`mp`.`tour_id` LEFT JOIN `cities` as `city` ON `city`.`id`=`th`.`city` LEFT JOIN `countries` as `country` ON `country`.`id`=`th`.`country` WHERE `mp`.`room_type_en`='Standard' AND `mp`.`price_type`=1 AND `mp`.`extra_price_flag`=0  ";
		
 		if($search !== "")
			$query .= " AND (`th`.`name_en` LIKE '$search%' OR `city`.`city_en` LIKE '$search%')";
		if($code !== "")
			$query .= " AND `mp`.`id` = '$code'";
		$total_links = $this->db->query($query)->num_rows();
		$links = array();
		if($total_links > 0)
		{
			$query .= " ORDER BY `mp`.`tour_date` DESC";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->db->query($query);
			$links = $result->result();
		}
		return array("total" => $total_links, "results" => $links);
	}
	
	public function attraction_data($code = "", $search = "", $page = "1")
	{
		$query = "SELECT `mp`.`id` AS `id`,`mp`.`total_tour_price` AS `price`, CONCAT(`t`.`tour_name_en`,', ',`t`.`tour_name_fa`,', ',`t`.`no_of_day`,', ',`city`.`city_en`,', ','city_fa',', ',`country`.`country_en`,', ',`country`.`country_fa`) AS `info`,CONCAT(`t`.`tour_name_en`,', ',`th`.`name_en`,', ',DATE_FORMAT(`mp`.`tour_date`,'%m/%d/%Y')) AS `text` FROM `master_price` AS `mp` LEFT JOIN `tour_hotel` AS `th` ON `th`.`hotel_id` = `mp`.`hotel_id` LEFT JOIN `tour` AS `t` ON `t`.`tour_id`=`mp`.`tour_id` LEFT JOIN `cities` as `city` ON `city`.`id`=`th`.`city` LEFT JOIN `countries` as `country` ON `country`.`id`=`th`.`country` WHERE `mp`.`room_type_en`='Standard' AND `mp`.`price_type`=1 AND `mp`.`extra_price_flag`=0  ";
		
 		if($search !== "")
			$query .= " AND (`th`.`name_en` LIKE '$search%' OR `city`.`city_en` LIKE '$search%')";
		if($code !== "")
			$query .= " AND `mp`.`id` = '$code'";
		$total_links = $this->db->query($query)->num_rows();
		
		$links = array();
		if($total_links > 0)
		{
			$query .= " ORDER BY `mp`.`tour_date` DESC";
			$query .= " LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->db->query($query);
			$links = $result->result();
		}
		return array("total" => $total_links, "results" => $links);
	}
	
	function count_vendors($id){
	 $sql = "SELECT tour.tour_id FROM tour WHERE tour.vendor_id = $id";
	 $result = $this->db->query($sql);
	return $result->num_rows() > 0 ? $result->result_array() : false;
	 
}
  

}