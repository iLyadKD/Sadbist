<?php if(!defined('BASEPATH')) exit('No direct script access allowed');   
class Tour_Model extends CI_Model {
       
    function  gen_info($id,$lang=DEFAULT_LANG) {
		$this->db->select('t.*,t.tour_name_'.$lang.' AS tour_name,td.*,tf.file_name tour_file');
        $this->db->from('tour t');
        $this->db->join('tour_details td','td.tour_id = t.tour_id', 'left');
		$this->db->join('tour_file tf','tf.tour_id = t.tour_id', 'left');
        $this->db->where('t.tour_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }
	
	function tour_location($id,$lang=DEFAULT_LANG){
		$this->db->select('CONCAT(city.city_'.$lang.'," ,",country.country_'.$lang.') AS text');
		$this->db->from('cities city');
		$this->db->join('countries country','country.id = city.country', 'left');
		$this->db->where('city.id',$id);
		$query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
	}
    
    function  galleries($id) {
        $this->db->select('tg.*');
        $this->db->from('tour_hotel_gallery tg');
        $this->db->join('tour_hotel th','tg.hotel_id = th.hotel_id', 'left');
        $this->db->where_in('tg.hotel_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result_array(); } else { return FALSE; }
    }
    
    function  hotel_price($id,$hotel_id,$date,$lang = DEFAULT_LANG) {
		$this->db->select('mp.doubles AS hotel_price,mp.handle_charge,mp.hotel_id,th.rating,th.latitude,th.longitude,th.name_'.$lang.' AS name');
        $this->db->from('master_price mp');
        $this->db->join('tour_hotel th','mp.hotel_id = th.hotel_id', 'left');
        $this->db->where('mp.tour_id',$id);
		$this->db->where('mp.hotel_id',$hotel_id);
        $this->db->where('mp.price_type',1);
		$this->db->where('mp.tour_date',$date);
		$this->db->limit(0,1);
		
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }
    
    function  hotels($id,$lang = DEFAULT_LANG) {
		$this->db->select('th.name_'.$lang.' AS name,th.rating,th.latitude,th.longitude,mp.*,mp.room_type_'.$lang.' AS room_type');
        $this->db->from('master_price mp');
        $this->db->join('tour_hotel th','mp.hotel_id = th.hotel_id', 'left');
        $this->db->where('mp.id',$id);
		
		$result = $this->db->get();
        if($result->num_rows()>0){ return $result->result_array(); } else { return FALSE; }
    }
	
	function  hotel_details($id,$lang = DEFAULT_LANG) {
		$this->db->select('th.descrpition_'.$lang.' AS description');
        $this->db->from('tour_hotel th');
        $this->db->where('th.hotel_id',$id);
		$result = $this->db->get();
        if($result->num_rows()>0){ return $result->result_array()[0]; } else { return FALSE; }
    }
	

    function  get_package_type($langauge) {
        $this->db->select("*");
        $this->db->from('package_type');
        $query = $this->db->get();
        $this->db->last_query();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }
	
	function  tour_price($id) {
        $this->db->select("mp.*");
        $this->db->from('master_price mp');
		$this->db->where('mp.id',$id);
        $query = $this->db->get();
        $this->db->last_query();
        if($query->num_rows()>0){ return $query->result()[0]; } else { return FALSE; }
    }

    function  get_package_type_id($langauge,$id) {
        $this->db->select("$langauge as type_name,type_id");
        $this->db->from('package_type');
        $this->db->where('type_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }

    function  search($data,$langauge,$count_flag, $lang = DEFAULT_LANG) {
		if($langauge == "en")
            $langauge = "english";
        $country_code =  $data['country_code'];
        $tocity =  $data['tocity'];
		$fromcity =  $data['fromcity'];
		$nights = $data['nights'];
		$checkin = $data['checkin'];
		$iod = $data['iod'];
		
        $where="";
		$tour_where="";
		$hotel_wise="";
		$current_date = date('Y-m-d');
		if($country_code) {   $where .= " AND t.d_country = '$country_code'";}
		if($tocity) {   $where .= " AND t.d_city = '$tocity'";}
		if($fromcity) {   $where .= " AND t.o_city = '$fromcity'";}
		if($iod) {   $where .= " AND t.iod = '$iod'";}
		if($checkin) {
			$checkin = date("Y-m-d",strtotime($checkin));
			$tour_where .= " AND ((`tour_date` BETWEEN ('".$checkin."' - INTERVAL 6 DAY) AND '".$checkin."') or (`tour_date` BETWEEN   '".$checkin."' AND ('".$checkin."' + INTERVAL 6 DAY))) AND `tour_date` >= '".$current_date."'";
		}
			
        $rating = @$data['rating'];
		
		if($rating) {
			foreach($rating as $r){
				$r_arr[] = $r;
			}
			$rate = implode(",",$r_arr);
			$tour_where .= " AND th.rating IN ($rate)";
			$default_hotel = "`mp`.`hotel_id` = `th`.`hotel_id`"; 
			$hotel_wise= ",`hotels`.`hotel_id`";
		}elseif(isset($data['std_range'])){
			$default_hotel = "`mp`.`hotel_id` = `th`.`hotel_id`";
		}
		else{
			 $default_hotel = "`mp`.`hotel_id` = `th`.`hotel_id`";
		}
		$page = $data['page'];
		$items_per_page = 50;
		$offset = ($page - 1) * $items_per_page;
		
		if(isset($data['neighbours']) && !empty($data['neighbours'])){
            foreach($data['neighbours'] as $n){
                $n_arr[] = "'".$n."'";
            }
			$neighbours = implode(",",$n_arr);
			$tour_where .= " AND `th`.`neighbours_".$lang."` IN ($neighbours)";
			$default_hotel = "`mp`.`hotel_id` = `th`.`hotel_id`"; 
			$hotel_wise= ",`hotels`.`hotel_id`";
			
        }
		if(isset($data['special_offer']) && $data['special_offer'] == 1){
			$tour_where .= " AND `flag_discount`=1";
		}
		if(isset($data['food']) && !empty($data['food'])){
            foreach($data['food'] as $food){
                $food_arr[] = "'".$food."'";
            }
			$foods = implode(",",$food_arr);
			$tour_where .= " AND `mp`.`food_type` IN ($foods)";
        }
		
		$order = '';
		if(isset($data['order_by_value']) && isset($data['order_by']) ){
			//$offset = $this->session->userdata('offset');
			if($data['order_by']==1 && $data['order_by_value'] == 0){
                $order = ' order by `hotels`.`hotel_rating` ASC ';
            }elseif($data['order_by']==1 && $data['order_by_value'] == 1){
               $order = ' order by `hotels`.`hotel_rating` DESC ';
            }
            if($data['order_by']==2 && $data['order_by_value'] == 0){
				  $order = ' order by `hotels`.`doubles` ASC ';
            }elseif($data['order_by']==2 && $data['order_by_value'] == 1){
				 $order = ' order by `hotels`.`doubles` DESC ';
            }
            
            if($data['order_by']==3 && $data['order_by_value'] == 0){
				 $order = ' order by `hotels`.`name` ASC ';
            }elseif($data['order_by']==3 && $data['order_by_value'] == 1){
				$order = ' order by `hotels`.`name` DESC ';
            }
        }else{
			 $order = ' order by `hotels`.`tour_date` ASC ';
		}
		$trans="";
		if(isset($data['transportation']) && !empty($data['transportation'])){
             
			foreach($data['transportation'] as $tr){
                $tr_arr[] = $tr;
            }
            if(in_array(1,$tr_arr)){
				$trans[] = ' flight_count != "" ';
            }
			 if(in_array(2,$tr_arr)){
                $trans[] = ' cruise_count != ""';
            }
             if(in_array(3,$tr_arr)){
				$trans[] = ' train_count != ""';
            }
			 if(in_array(4,$tr_arr)){
                $trans[] = ' bus_count != ""';
            }
           
			$trans =   " Having ".implode(" || ",$trans);
        }
		
		if($count_flag == 0)
			$limit = "LIMIT " . $offset . "," . $items_per_page." ";
		else
			$limit = '';
		
		$query = "
		SELECT flights.*,trains.*,buses.*,cruises.*,`hotels`.*,`t`.tour_name_".$lang.",`t`.status,t.no_of_night,t.visa,t.visa_price,t.transfer,
		t.transfer_price,t.insurance,t.insurance_price
		,(SELECT GROUP_CONCAT(ta.airline_logo) FROM tour_flight tf INNER JOIN tour_airlines ta ON ta.id = tf.tour_airline_id WHERE t.tour_id = tf.tour_id)  as flight_count
		,(SELECT COUNT(*) FROM tour_cruise  tc WHERE t.tour_id = tc.tour_id)  as cruise_count
		,(SELECT COUNT(*) FROM tour_train  tt WHERE t.tour_id = tt.tour_id)  as train_count
		,(SELECT COUNT(*) FROM tour_bus  tb WHERE t.tour_id = tb.tour_id)  as bus_count
		FROM `tour`  as t 

		INNER JOIN (select * from(
			SELECT `mp`.`doubles`,`mp`.`double_qty`,`mp`.`single_qty`,`mp`.`triple_qty`,mp.`tour_date`,mp.`id` master_id,`mp`.`tour_id`,`mp`.`overall_tour_price`,`mp`.`food_type`,`mp`.flag_discount,`mp`.total_tour_price AS FINAL_PRICE, `th`.`hotel_id`, `th`.`name_".$lang."` AS name,`th`.`logo`, `cities`.`city_".$lang."` AS `city`,th.neighbours_".$lang." AS neighbours,th.rating hotel_rating 
		FROM `master_price` as `mp`  
		INNER JOIN `tour_hotel` as `th` ON ".$default_hotel."
		INNER JOIN `cities` ON `th`.`city` = `cities`.`id` 
		WHERE `price_type` = '1' AND `room_type_en` = 'Standard' AND `extra_price_flag` = 0  ".$tour_where." 
		ORDER BY (mp.`id`) ASC  
		) AS `hotels`) as `hotels`  
		
		LEFT JOIN (
			SELECT  `tff`.`flight_price`,`tff`.`tour_flight_id`,`tff`.`tour_id`
		FROM `tour_flight` tff  order by `tff`.`flight_price` ASC 
		) AS `flights` ON  `t`.`tour_id` = `flights`.`tour_id`
		
		
		
		LEFT JOIN (
			SELECT  `tcc`.`price` AS cruise_price,`tcc`.`tour_cruise_id`,`tcc`.`tour_id`
		FROM `tour_cruise` tcc    order by `tcc`.`price` ASC
		) AS `cruises` ON  `t`.`tour_id` = `cruises`.`tour_id`
		
		LEFT JOIN (
			SELECT  `ttt`.`price` AS train_price,`ttt`.`tour_train_id`,`ttt`.`tour_id`
		FROM `tour_train` ttt    order by `ttt`.`price` ASC
		) AS `trains` ON  `t`.`tour_id` = `trains`.`tour_id`
		
		LEFT JOIN (
			SELECT  `tbb`.`price` AS bus_price,`tbb`.`tour_bus_id`,`tbb`.`tour_id`
		FROM `tour_bus` tbb    order by `tbb`.`price` ASC
		) AS `buses` ON  `t`.`tour_id` = `buses`.`tour_id`
		
		WHERE `t`.`tour_id` = `hotels`.`tour_id` ".$where."   group by `hotels`.`master_id`  ".$hotel_wise."  ".$trans."
		".$order."  ".$limit." ";
				
        $result = $this->db->query($query); 
		if($result->num_rows()>0){ return $result->result(); } else { return FALSE; }
    }
    
    function get_price($t_id){
        $this->db->select('tour_price.single,tour_price.tour_id,tour_price.hotel_id,th.name,th.rating');
        $this->db->from('tour_price');
		$this->db->join('tour_hotel th','th.hotel_id = tour_price.hotel_id', 'left');
        $this->db->where('tour_price.tour_id',$t_id);
        $this->db->where('tour_price.price_type',2);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }
	function transportation($type,$t_id,$lang = DEFAULT_LANG){
		if($type == "flight"){
			$this->db->select('tf.*,ta.*,tf.departuer_airport_'.$lang.' AS departuer_airport,tf.arrival_airport_'.$lang.' AS arrival_airport,tf.return_deapartur_'.$lang.' AS return_deapartur,tf.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_flight tf');
			$this->db->join('tour_airlines ta','ta.id = tf.tour_airline_id', 'left');
			$this->db->where('tf.tour_id',$t_id);
			$this->db->order_by('tf.flight_price','ASC');
			$query = $this->db->get();
		}
		if($type == "train"){
			$this->db->select('tt.*,tt.departure_from_'.$lang.' AS departure_from,tt.arrival_train_'.$lang.' AS arrival_train,tt.return_deapartur_'.$lang.' AS return_deapartur,tt.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_train tt');
			$this->db->where('tt.tour_id',$t_id);
			$this->db->order_by('tt.price','ASC');
			$query = $this->db->get();
		}
		if($type == "cruise"){
			$this->db->select('tc.*,tc.departure_from_'.$lang.' AS departure_from,tc.arrival_cruise_'.$lang.' AS arrival_cruise,tc.return_deapartur_'.$lang.' AS return_deapartur,tc.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_cruise tc');
			$this->db->where('tc.tour_id',$t_id);
			$this->db->order_by('tc.price','ASC');
			$query = $this->db->get();
		}
		if($type == "bus"){
			$this->db->select('tb.*,tb.departure_from_'.$lang.' AS departure_from,tb.arrival_bus_'.$lang.' AS arrival_bus,tb.return_deaparture_'.$lang.' AS return_deaparture,tb.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_bus tb');
			$this->db->where('tb.tour_id',$t_id);
			$this->db->order_by('tb.price','ASC');
			$query = $this->db->get();
		}
		if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
	}
	
	function transportation_id($type,$t_id,$lang = DEFAULT_LANG){
		if($type == "flight"){
			$this->db->select('tf.*,tf.departuer_airport_'.$lang.' AS departuer_airport,tf.arrival_airport_'.$lang.' AS arrival_airport,tf.return_deapartur_'.$lang.' AS return_deapartur,tf.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_flight tf');
			$this->db->where('tf.tour_flight_id',$t_id);
			$query = $this->db->get();
		}
		if($type == "train"){
			$this->db->select('tt.*,tt.departure_from_'.$lang.' AS departure_from,tt.arrival_train_'.$lang.' AS arrival_train,tt.return_deaparture_'.$lang.' AS return_deaparture,tt.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_train tt');
			$this->db->where('tt.tour_train_id',$t_id);
			$query = $this->db->get();
		}
		if($type == "bus"){
			$this->db->select('tb.*,tb.departure_from_'.$lang.' AS departure_from,tb.arrival_bus_'.$lang.' AS arrival_bus,tb.return_deaparture_'.$lang.' AS return_deaparture,tb.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_bus tb');
			$this->db->where('tb.tour_bus_id',$t_id);
			$query = $this->db->get();
		}
		if($type == "cruise"){
			$this->db->select('tc.*,tc.departure_from_'.$lang.' AS departure_from,tc.arrival_cruise_'.$lang.' AS arrival_cruise	,tc.return_deaparture_'.$lang.' AS return_deaparture,tc.return_arrival_'.$lang.' AS return_arrival');
			$this->db->from('tour_cruise tc');
			$this->db->where('tc.tour_cruise_id',$t_id);
			$query = $this->db->get();
		}
		if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
	}
	
    function  get_package_id($id,$langauge) {
        if($langauge == "en")
            $langauge = "english";
        $this->db->select('*,p.package_id as package_id');
        $this->db->from('package p');
        $this->db->join('package_duration d','d.package_id = p.package_id', 'left');
        $this->db->join('package_details de','de.package_id = p.package_id', 'left');
        $this->db->where('p.package_id',$id);
        $this->db->where('de.language',$langauge);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }

    function  get_itinerary_id($id,$langauge) {
        if($langauge == "en")
            $langauge = "english";
        $this->db->select('*');
        $this->db->from('package_itinerary');
        $this->db->where('package_id',$id);
        $this->db->where('language',$langauge);
        $this->db->order_by('ABS(day)','asc');
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }
    
    function  get_hotel_id($id,$langauge) {
        if($langauge == "en")
            $langauge = "english";
        $this->db->select('*');
        $this->db->from('package_hotel');
        $this->db->where('package_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }
    
    function  get_flight_id($id,$langauge) {
        if($langauge == "en")
            $langauge = "english";
        $this->db->select('*');
        $this->db->from('package_flight');
        $this->db->where('package_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }

    function  get_details_id($id,$langauge) {
        $this->db->select('*');
        $this->db->from('package_details');
        $this->db->where('package_id',$id);
        $this->db->where('language',$langauge);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }

    function  get_gallery_id($id) {
        $this->db->select('*');
        $this->db->from('package_gallery');
        $this->db->where('package_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }

    function  get_cancel($package_id) {
        $this->db->select("*");
        $this->db->from('package_cancellation');
        $this->db->where('package_id',$package_id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }


    function  get_booking($booking_id) {
        $this->db->select("*");
        $this->db->from('package_booking');
        $this->db->where('id',$booking_id);
        $this->db->or_where('md5(booking_number)', $booking_id); 
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }

    function  get_booking_count() {
        $this->db->select("id");
        $this->db->from('package_booking');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function  save_booking($data) {
		$query = $this->db->insert('bookings', $data);
        if($query){ return $this->db->insert_id() ;} else { return false; }
    }
	
	function  save_booking_details($data) {
		$query = $this->db->insert('booking_details', $data);
        if($query){ return 1 ;} else { return 0; }
    }
	
    
     function extra_night($id,$field,$date) {
		if($field == 'single')
			$this->db->select('single,single_qty');
		if($field == 'doubles')
			$this->db->select('doubles,double_qty');
		if($field == 'triple')
			$this->db->select('triple,triple_qty');
		if($field == 'infants')
			$this->db->select('infants');
		if($field == 'twotosix')
			$this->db->select('twotosix');
		if($field == 'sixtotwelve')
			$this->db->select('sixtotwelve');
		if($field == 'twelvetosixteenth')
			$this->db->select('twelvetosixteenth');
			
        $this->db->from('master_price');
        $this->db->where('hotel_id',$id);
        $this->db->where('price_type',2);
		$this->db->where('extra_price_flag',1);
		$this->db->where('room_type','Standard');
		$this->db->where('tour_date',$date);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }
	
	function find_room_types($id){
	  $this->db->select('th.room_types')
			   ->from('tour_hotel th')
			   ->where('hotel_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->row();
	  }else {
		return false;
	  }
	}
	
	function  get_currency($currency) {
        $this->db->select('c.value');
        $this->db->from('currencies c');
        $this->db->where('code',$currency);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result_array(); } else { return FALSE; }
    }
	
	function  check_other_rooms($id,$hotel_id,$tour_date,$lang = DEFAULT_LANG) {
        $this->db->select('th.name_'.$lang.' AS name,th.rating,th.latitude,th.longitude,mp.*,mp.room_type_'.$lang.' AS room_type');
		$this->db->join('tour_hotel th','mp.hotel_id = th.hotel_id', 'left');
        $this->db->from('master_price mp');
        $this->db->where('mp.hotel_id',$hotel_id);
		$this->db->where('mp.tour_id',$id);
		$this->db->where('mp.tour_date',$tour_date);
		$this->db->where('mp.room_type_en !=','Standard');
		$this->db->where('mp.price_type',1);
		$this->db->where('mp.extra_price_flag',0);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result_array(); } else { return FALSE; }
    }
	
	function  check_extra($tour_id,$hotel_id,$tour_date,$room_type,$lang = DEFAULT_LANG) {
		$this->db->select('mp.*,mp.room_type_'.$lang.' AS room_type');
		$this->db->from('master_price mp');
		$this->db->where('tour_id',$tour_id);
		$this->db->where('hotel_id',$hotel_id);
		$this->db->where('tour_date',$tour_date);
		$this->db->where('room_type_'.$lang.'',$room_type);
		$this->db->where('extra_price_flag',1);
		   
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result_array()[0]; } else { return FALSE; }
    }
	
	function  get_booking_details($id) {
		$this->db->select('bd.*,b.book_id');
		$this->db->join('bookings b','bd.id = b.id', 'left');
        $this->db->from('booking_details bd');
        $this->db->where('bd.id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }
	
	function  voucher_hotel($id,$lang = DEFAULT_LANG) {
		$this->db->select('th.name_'.$lang.' AS name,th.address_'.$lang.' AS address,th.neighbours_'.$lang.' AS neighbours,th.rating');
        $this->db->from('tour_hotel th');
        $this->db->where('th.hotel_id',$id);
		$result = $this->db->get();
        if($result->num_rows()>0){ return $result->result_array()[0]; } else { return FALSE; }
    }
	
	function  voucher_tour($id,$lang = DEFAULT_LANG) {
		$this->db->select('th.name_'.$lang.' AS name,th.address_'.$lang.' AS address,th.neighbours_'.$lang.' AS neighbours,th.rating');
        $this->db->from('tour_hotel th');
        $this->db->where('th.hotel_id',$id);
		$result = $this->db->get();
        if($result->num_rows()>0){ return $result->result_array()[0]; } else { return FALSE; }
    }
	
	function master_details($id,$lang=DEFAULT_LANG){
		$this->db->select('mp.*,t.no_of_day,tf.tour_flight_id,tb.tour_bus_id,tc.tour_cruise_id,tt.tour_train_id');
		$this->db->from('master_price mp');
		$this->db->join('tour t','t.tour_id = mp.tour_id', 'left');
		$this->db->join('tour_flight tf','tf.tour_id = t.tour_id', 'left');
		$this->db->join('tour_bus tb','tb.tour_id = t.tour_id', 'left');
		$this->db->join('tour_cruise tc','tc.tour_id = t.tour_id', 'left');
		$this->db->join('tour_train tt','tt.tour_id = t.tour_id', 'left');
		$this->db->where('mp.id',$id);
		$query = $this->db->get();
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
	}
	
	function get_from_cities($lang=DEFAULT_LANG,$iod){
		$this->db->select('CONCAT(city.city_'.$lang.'," ,",country.country_'.$lang.') AS location,city.id city_id');
		$this->db->from('tour t');
		$this->db->join('countries country','country.id = t.o_country', 'left');
		$this->db->join('cities city','city.id = t.o_city', 'left');
		$this->db->group_by('city.id');
		$this->db->where('t.iod',$iod);
		$query = $this->db->get();
		if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
	}
	
	function city_info($lang=DEFAULT_LANG,$id){
		$this->db->select('CONCAT(city.city_'.$lang.'," ,",country.country_'.$lang.') AS city');
		$this->db->join('countries country','country.id = city.country', 'left');
        $this->db->from('cities city');
        $this->db->where('city.id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result()[0]; } else { return FALSE; }
	}
	
	function  check_tour($lang=DEFAULT_LANG,$id) {
		$current_date = date('Y-m-d');
		$this->db->select('CONCAT(city.city_'.$lang.'," ,",country.country_'.$lang.') AS location,city.id city_id');
		$this->db->from('tour t');
		$this->db->join('countries country','country.id = t.d_country', 'left');
		$this->db->join('cities city','city.id = t.d_city', 'left');
		$this->db->group_by('city.id');
		$this->db->where('t.o_city',$id);
		$this->db->where('t.status',1);
		$this->db->where('t.d_city != ',$id,FALSE);
		$this->db->where('t.to_date >= ',$current_date);
		$query = $this->db->get();
		if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }

}