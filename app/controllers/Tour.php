<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tour extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		unset_page_cache();
		$this->data["page_title"] = "Tours";
		$this->load->model("Tour_model");
		$this->load->model("Home_model");
	}

	public function search() {
		$get = $this->input->get();
		$arr_city = $get['arr_city'];
		if(!isset($get['search_type'])){
			if($get['from_hid'] == '' || $get['to_hid'] == ''){
				$this->session->set_flashdata('from_to_msg', $this->lang->line("city_valid_error"));
				redirect(base_url());
			}
			//pr($get);
			$get_city_to = $this->Home_model->getcity_code($get['id_to_city'],$this->data['default_language']);
			$get_city_from = $this->Home_model->getcity_code($get['id_from_city'],$this->data['default_language']);
			$get_data['tocity'] = $get_city_to[0]->id;
			
			$get_data['fromcity'] = $get_city_from[0]->id;
			$get_data['country_code'] = $get_city_to[0]->country;
			$get_data['way'] = $get['pradio'];
			$get_data['checkin'] = $get['checkin'];
			$get_data['nights'] = $get['nights'];
			$get_data['page'] = $get['page'];
			
			$country_by_id_from = $this->Home_model->country_by_id($get_city_from[0]->country,$this->data['default_language']);
			$country_by_id_to = $this->Home_model->country_by_id($get_city_to[0]->country,$this->data['default_language']);
			$get_data['keyword_from'] = $get_city_from[0]->city.', '.$country_by_id_from->country;
			$get_data['keyword_to'] = $get_city_to[0]->city.', '.$country_by_id_to->country;
			
			$this->session->set_userdata($get_data);
			
		}else {
			if($get['from_hid'] == '' && $get['to_hid'] == '' && $get['ready_flag'] == 1){
				
				$this->session->set_flashdata('from_to_msg', $this->lang->line("city_valid_error"));
				redirect(base_url());
			}
			
			if($get['id_to_city'] != '')
				$tocity = $get['id_to_city'];
			else
				$tocity = $get['tocity'];
				
			if($get['id_from_city'] != '')
				$fromcity = $get['id_from_city'];
			else
				$fromcity = $get['fromcity'];	
				
				
			$get_city_to = $this->Home_model->getcity_code($tocity,$this->data['default_language']);
			$get_city_from = $this->Home_model->getcity_code($fromcity,$this->data['default_language']);
			
			$get_data['tocity'] = $get_city_to[0]->id;
			$get_data['fromcity'] = $get_city_from[0]->id;
			$get_data['country_code'] = $get_city_to[0]->country;
			$get_data['nights'] = $get['nights'];
			$get_data['checkin'] = $get['mcheckin'];
			
			$country_by_id_from = $this->Home_model->country_by_id($get_city_from[0]->country,$this->data['default_language']);
			$country_by_id_to = $this->Home_model->country_by_id($get_city_to[0]->country,$this->data['default_language']);
			$get_data['keyword_from'] = $get_city_from[0]->city.', '.$country_by_id_from->country;
			$get_data['keyword_to'] = $get_city_to[0]->city.', '.$country_by_id_to->country;
			
			
			$this->session->set_userdata($get_data);
			
		}
		
		$langauge =$this->session->userdata('default_language');
		$data['title'] ="Tours ";
		$data['neighbours'] = $this->Home_model->get_neighbours($this->data['default_language']);
		$data['arr_city'] = json_encode($arr_city);
		$this->load->view('tour/search',$data);
    }
	
	public function result() {
		$get = $this->input->get();
		$page = $get['page'];
		$langauge =$this->session->userdata('lang');
		$data['tours'] = $this->Tour_model->search($get,$langauge,0,$this->data["default_language"]);
		//pr($data['tours']);exit;
		$data['all_tours'] = $this->Tour_model->search($get,$langauge,1);
		$data['tours_count'] = count($data['all_tours']);
		$this->session->set_userdata('tours_count',ceil($data['tours_count']/50));
		$data['count'] = count($data['tours']);
		
		if($this->data['default_currency'] == "USD")
			$result = ceil($this->currency_converter->currency_val(IRR));
		else
			$result = '1';
		$data['currency'] = $result;
		$price_store = [];
		$all_dates = [];
		if(!empty($data['all_tours'])){
			foreach($data['all_tours'] as $tour){
				$total_tour_price = $tour->FINAL_PRICE;
				$price_store[] = $total_tour_price;
				if(!in_array($tour->tour_date,$all_dates))
					$all_dates[strtotime($tour->tour_date)] = $tour->tour_date;
			}
		}
		
		$data['price_store'] = $price_store;
		$data['min_date'] = '';
		$data['max_date'] = '';
		if(!empty($all_dates)){
			$data['min_date'] = min($all_dates);
			$data['max_date'] = max($all_dates);	
		}
		//pr($data);exit;
		$this->session->set_userdata('tour_count',$data['count']);
		//$data['location_info'] = $get['location_info'];
        $this->load->view('tour/result',$data);
    }

	public function details($master_id){
		if($this->data['default_currency'] == "USD")
			$currency = ceil($this->currency_converter->currency_val(IRR));
		else
			$currency = '1';
		$master_id = $this->encrypt->decode(base64_decode($master_id));
		//echo $master_id;exit;
		$master_details = $this->Tour_model->master_details($master_id,$this->data['default_language']);
		//pr($master_details);exit;
		$tour_date = $master_details->tour_date;
		$data['short_info'] = $tour_date.'/'.$master_details->no_of_day;
		$duration = date('m/d/Y', strtotime($master_details->tour_date)).'-'.date('m/d/Y', strtotime($master_details->tour_date. ' + '.($master_details->no_of_day).' days'));

		$total_tour_price = round($master_details->total_tour_price/$currency,0);
		$base_price = round($master_details->overall_tour_price/$currency,0);
		$hotel_id = $master_details->hotel_id;
		$id = $master_details->tour_id;
		
		if($master_details->tour_flight_id == ''){
			if($master_details->tour_cruise_id == ''){
				if($master_details->tour_train_id == ''){
					if($master_details->tour_bus_id == ''){
						$trans_id = 0;
						$trans_flag = 0;
					}else{
						$trans_id = $master_details->tour_bus_id;
						$trans_flag = 'bus';
					}
				}else{
					$trans_id = $master_details->tour_train_id;
					$trans_flag = 'train';
				}
			}else{
				$trans_id = $master_details->tour_cruise_id;
				$trans_flag = 'cruise';
			}
		}else{
			$trans_id = $master_details->tour_flight_id;
			$trans_flag = 'flight';
		}
		
		$data["contact_address"] = $this->general->default_contact_address($this->data["default_language"]);
		
		if($this->data['default_currency'] == "USD")
			$result = ceil($this->currency_converter->currency_val(IRR));
		else
			$result = '1';
		$data['currency'] = $result;
		
		
		
		
		
		
		$data['tour_id'] = $id;
		$data['gen_info'] = $this->Tour_model->gen_info($id,$this->data['default_language']);
		//pr($data['gen_info']);exit;
		$data['tour_from'] = $this->Tour_model->tour_location($data['gen_info']->o_city,$this->data['default_language']);
		$data['tour_to'] = $this->Tour_model->tour_location($data['gen_info']->d_city,$this->data['default_language']);
		
		$data['hotel_details'] = $this->Tour_model->hotel_details($hotel_id,$this->data['default_language']);
		
		$data['tour_price'] = $this->Tour_model->tour_price($master_id);
		$data['galleries'] = $this->Tour_model->galleries($hotel_id);
		//pr($data['galleries']);exit;
		$data['hotel_price'] = $this->Tour_model->hotel_price($id,$hotel_id,$tour_date);
		$data['flights'] = $this->Tour_model->transportation('flight',$id,$this->data['default_language']);
		$data['cruises'] = $this->Tour_model->transportation('cruise',$id,$this->data['default_language']);
		$data['trains'] = $this->Tour_model->transportation('train',$id,$this->data['default_language']);
		$data['buses'] = $this->Tour_model->transportation('bus',$id,$this->data['default_language']);
		$data['duration'] = $duration;
		
		$data['trans_flag'] = $trans_flag;
		$data['trans_id'] = $trans_id;
		//$data['location_info'] = json_decode(base64_decode($location_info));
		if(!empty($data['gen_info'])){
			$default_h = $data['gen_info']->default_hotel;
			$hotels = explode(",",$data['gen_info']->hotel_id);		
			$standard_room = $this->Tour_model->hotels($master_id,$this->data['default_language']);
			//pr($standard_room);
			$data['std_hotel_name'] = $standard_room[0]['name'];
			$data['star_img'] = base_url('assets/images/star_rating_'.$standard_room[0]['rating'].'.png');
			$data['food_type'] = $standard_room[0]['food_type'];
			
			$check_other_rooms = $this->Tour_model->check_other_rooms($id,$hotel_id,$standard_room[0]['tour_date'],$this->data['default_language']);
			if(!empty($check_other_rooms)){
				$data['hotels'] = array_merge($standard_room,$check_other_rooms);
			}
			else{
				$data['hotels'] = $standard_room;
			}
			
		}
		
		$data['tour_date'] = date('dS M Y',strtotime($tour_date));
		$data['total_tour_price'] = $total_tour_price;
		$data['base_price'] = $base_price;
		$data['selected_hotel'] = $hotel_id;
		$this->load->view("tour/details",$data);
	}
	
	function get_extra_price(){
		if($this->input->is_ajax_request())
		{
			$hotel_id = $this->input->post('hotel_id');
			$field = $this->input->post('type');
			$tour_date = $this->input->post('tour_date');
			$ajax['info'] = $this->Tour_model->extra_night($hotel_id,$field,$tour_date);
			
			if(!empty($ajax['info'])){
				echo json_encode($ajax['info'][0]);exit;
			}else{
				echo 0;exit;
			}
			
		}
	}
	
	
	
	
	
	function prebooking(){
		
		$post = $_POST;
		$id_hotel = $this->input->post('id_hotel');
		$get_room_types = $this->Tour_model->find_room_types($id_hotel);
		$data['after_confirm'] = json_encode($post['after_confirm']);
		
		if($get_room_types->room_types != ''){
			$lang = $this->data['default_language'];
			$get_room_types = json_decode($get_room_types->room_types);
			$datas = [];
			$en_data = [];
			foreach($get_room_types as $fn){
				$fn = explode(''.$lang.'->',$fn);
				$datas[] = $fn[1];
			}
			foreach($datas as $d){
				$dn = explode("--",$d);
				if($dn[0] == '')
					$dn[0] = 'not defined';
				$en_data[] = $dn[0];
			}
			
			$get_room_types = $en_data;
		  }else{
			 $get_room_types = '';
		  }
		
		
		
		         
                                
                                 
		
		$id_tour = $this->input->post('id_tour');
		$count_room_single = $this->input->post('count_room_single');
		$count_room_double = $this->input->post('count_room_double');
		$count_room_triple = $this->input->post('count_room_triple');
		$twotosix = $this->input->post('twotosix');
		$sixtotwelve = $this->input->post('sixtotwelve');
		$twelvetosixteenth = $this->input->post('twelvetosixteenth');
		$infants = $this->input->post('infants');
		
		if($get_room_types != ''){
			foreach($get_room_types as $room_type) {
				foreach($post as $key=>$value){
					if($key == 'twotosix_'.$room_type){
						$twotosix = $twotosix + $post[$key];
					}
					elseif($key == 'sixtotwelve_'.$room_type){
						$sixtotwelve = $sixtotwelve + $post[$key];
					}
					elseif($key == 'twelvetosixteenth_'.$room_type){
						$twelvetosixteenth = $twelvetosixteenth + $post[$key];
					}
					elseif($key == 'infants_'.$room_type){
						$infants = $infants + $post[$key];
					}      
				}
			}
		}
		
		
		
		$price_tour = $this->input->post('price_tour');
		$cost_transportation = $this->input->post('cost_transportation');
		$hotel_cost = $price_tour-$cost_transportation;
		
		
		
		
		$trans_param = explode("_",$this->input->post('trans_param'));
		$tour_date = $this->input->post('tour_date');
		
		
		
		if($trans_param[0] != ''){
			if($trans_param[0] == "flight"){
				$trans_info = $this->Tour_model->transportation_id('flight',$trans_param[1],$this->data['default_language']);
			}elseif($trans_param[0] == "train"){
				$trans_info = $this->Tour_model->transportation_id('train',$trans_param[1],$this->data['default_language']);
			}elseif($trans_param[0] == "bus"){
				$trans_info = $this->Tour_model->transportation_id('bus',$trans_param[1],$this->data['default_language']);
			}elseif($trans_param[0] == "cruise"){
				$trans_info = $this->Tour_model->transportation_id('cruise',$trans_param[1],$this->data['default_language']);
			}
			
		}else{
			$trans_info = 0;
		}
		
		$data['room_types'] = $get_room_types;
		$data['post'] = $post;
		
		$data['trans_info'] = $trans_info[0];
		$this->load->model("Home_model");
		
		$data['countries'] = $this->Home_model->get_countries();
		$data['id_hotel'] = $id_hotel;
		$data['id_tour'] = $id_tour;
		$data['get_room_types'] = implode(",",$get_room_types);
		$data['count_room_single'] = $count_room_single;
		$data['count_room_double'] = $count_room_double;
		$data['count_room_triple'] = $count_room_triple;
		
		$data['twotosix'] = $twotosix;
		$data['sixtotwelve'] = $sixtotwelve;
		$data['twelvetosixteenth'] = $twelvetosixteenth;
		$data['infants'] = $infants;
		$data['price_tour'] = $price_tour;
		$data['cost_transportation'] = $cost_transportation;
		$data['trans_param'] = $trans_param;
		$data['hotel_cost'] = $hotel_cost;
		$data['departures'] = $this->input->post('departures');
		
		$data['tour_from'] = $this->input->post('tour_from');
		$data['tour_to'] = $this->input->post('tour_to');
		$data['short_info'] = $post['short_info'];
		
		$data['info'] = $this->Tour_model->hotel_price($id_tour,$id_hotel,$tour_date);
		if(isset($this->data["user_id"]) && !empty($this->data["user_id"]))
			$data["user_details"] = $this->B2c_model->is_valid_user("", $this->data["user_id"]);
		
		
		
		$this->load->view("tour/prebooking",$data);
	}
	
	function prebook_submit(){
		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0){
			$after_confirm = $this->input->post('after_confirm');
			$departures = $this->input->post('departures');
			$bed = $this->input->post('bed');
			$room_type = $this->input->post('room_type');
			$adult_salutation = $this->input->post('adult_salutation');
			$adult_fname = $this->input->post('adult_fname');
			$adult_lname = $this->input->post('adult_lname');
			$adult_dob = $this->input->post('adult_dob');
			$adult_nationality = $this->input->post('adult_nationality');
			$adult_national_id = $this->input->post('adult_national_id');
			$adult_passport_no = $this->input->post('adult_passport_no');
			$adult_passport_exp = $this->input->post('adult_passport_exp');	
			$single = $this->input->post('count_room_single');
			$double = $this->input->post('count_room_double')*2;
			$triple = $this->input->post('count_room_triple')*3;
			$tourists = [];
			$tourists_spcl = [];
			
			$twotosix = $this->input->post('twotosix');
			$sixtotwelve = $this->input->post('sixtotwelve');
			$twelvetosixteenth = $this->input->post('twelvetosixteenth');
			$infants = $this->input->post('infants');
			$child = $this->input->post('child');
			$final_tourists1 = [];
			$final_tourists2 = [];
			
			if(!empty($child['twotosix']['salutation'])){
				$count = count($child['twotosix']['salutation']);
				$twotosix = [];
				for($i=0;$i<$count;$i++){
					$twotosix[$i]['child_salutation'] = $child['twotosix']['salutation'][$i];
					$twotosix[$i]['child_fname'] = $child['twotosix']['fname'][$i];
					$twotosix[$i]['child_lname'] = $child['twotosix']['lname'][$i];
					$twotosix[$i]['child_dob'] = $child['twotosix']['dob'][$i];
					$twotosix[$i]['child_nationality'] = $child['twotosix']['nationality'][$i];
					$twotosix[$i]['child_passport_no'] = $child['twotosix']['passport_no'][$i];
					$twotosix[$i]['child_passport_exp'] = $child['twotosix']['passport_exp'][$i];
					$twotosix[$i]['group'] = 'twotosix';
					if(isset($child['twotosix']['national_id'][$i]))
						$twotosix[$i]['child_national_id'] = $child['twotosix']['national_id'][$i];
					
				}
				if(!empty($twotosix)){
					$final_tourists1 = array_merge($twotosix,$final_tourists1);
				}
			}
			
			if(!empty($child['sixtotwelve']['salutation'])){
				$count = count($child['sixtotwelve']['salutation']);
				$sixtotwelve = [];
				for($i=0;$i<$count;$i++){
					$sixtotwelve[$i]['child_salutation'] = $child['sixtotwelve']['salutation'][$i];
					$sixtotwelve[$i]['child_fname'] = $child['sixtotwelve']['fname'][$i];
					$sixtotwelve[$i]['child_lname'] = $child['sixtotwelve']['lname'][$i];
					$sixtotwelve[$i]['child_dob'] = $child['sixtotwelve']['dob'][$i];
					$sixtotwelve[$i]['child_nationality'] = $child['sixtotwelve']['nationality'][$i];
					$sixtotwelve[$i]['child_passport_no'] = $child['sixtotwelve']['passport_no'][$i];
					$sixtotwelve[$i]['child_passport_exp'] = $child['sixtotwelve']['passport_exp'][$i];
					$sixtotwelve[$i]['group'] = 'sixtotwelve';
					if(isset($child['sixtotwelve']['national_id'][$i]))
						$sixtotwelve[$i]['child_national_id'] = $child['sixtotwelve']['national_id'][$i];
					
				}
				if(!empty($sixtotwelve)){
					$final_tourists1 = array_merge($sixtotwelve,$final_tourists1);
				}
			}
			
			if(!empty($child['twelvetosixteenth']['salutation'])){
				$count = count($child['twelvetosixteenth']['salutation']);
				$twelvetosixteenth = [];
				for($i=0;$i<$count;$i++){
					$twelvetosixteenth[$i]['child_salutation'] = $child['twelvetosixteenth']['salutation'][$i];
					$twelvetosixteenth[$i]['child_fname'] = $child['twelvetosixteenth']['fname'][$i];
					$twelvetosixteenth[$i]['child_lname'] = $child['twelvetosixteenth']['lname'][$i];
					$twelvetosixteenth[$i]['child_dob'] = $child['twelvetosixteenth']['dob'][$i];
					$twelvetosixteenth[$i]['child_nationality'] = $child['twelvetosixteenth']['nationality'][$i];
					$twelvetosixteenth[$i]['child_passport_no'] = $child['twelvetosixteenth']['passport_no'][$i];
					$twelvetosixteenth[$i]['child_passport_exp'] = $child['twelvetosixteenth']['passport_exp'][$i];
					$twelvetosixteenth[$i]['group'] = 'twelvetosixteenth';
					if(isset($child['twelvetosixteenth']['national_id'][$i]))
						$twelvetosixteenth[$i]['child_national_id'] = $child['twelvetosixteenth']['national_id'][$i];
				}
				if(!empty($twelvetosixteenth)){
					$final_tourists1 = array_merge($twelvetosixteenth,$final_tourists1);
				}
			}
			
			if(!empty($child['infants']['salutation'])){
				$count = count($child['infants']['salutation']);
				$infants = [];
				for($i=0;$i<$count;$i++){
					$infants[$i]['child_salutation'] = $child['infants']['salutation'][$i];
					$infants[$i]['child_fname'] = $child['infants']['fname'][$i];
					$infants[$i]['child_lname'] = $child['infants']['lname'][$i];
					$infants[$i]['child_dob'] = $child['infants']['dob'][$i];
					$infants[$i]['child_nationality'] = $child['infants']['nationality'][$i];
					$infants[$i]['child_passport_no'] = $child['infants']['passport_no'][$i];
					$infants[$i]['child_passport_exp'] = $child['infants']['passport_exp'][$i];
					$infants[$i]['group'] = 'infants';
					if(isset($child['infants']['national_id'][$i]))
						$infants[$i]['child_national_id'] = $child['infants']['national_id'][$i];
					
				}
				
				if(!empty($infants)){
					$final_tourists1 = array_merge($infants,$final_tourists1);
				}
			}
			
			if($room_type != ''){
				$room_type = explode(",",$room_type);
				foreach($room_type as $rm){
				if(!empty($adult_salutation)){
					
					foreach($adult_salutation as $k1=>$sal){
						if($k1 == $rm){
							if(isset($sal['single']) && !empty($sal['single'])){
								$bed_single = [];
								foreach($bed[$rm]['single'] as $a=>$b){
									for($i=0;$i<3;$i++){
										$bed_single[] = $bed[$rm]['single'][$a];
									}
								}
								
								$count = count($adult_salutation[$k1]['single']);
								for($i=0;$i < $count;$i++){
									
									$spcl_single[$i]['adult_salutation'] = $adult_salutation[$k1]['single'][$i];
									$spcl_single[$i]['adult_fname'] = $adult_fname[$k1]['single'][$i];
									$spcl_single[$i]['adult_lname'] = $adult_lname[$k1]['single'][$i];
									$spcl_single[$i]['adult_dob'] = $adult_dob[$k1]['single'][$i];
									$spcl_single[$i]['adult_nationality'] = $adult_nationality[$k1]['single'][$i];
									$spcl_single[$i]['adult_national_id'] = $adult_national_id[$k1]['single'][$i];
									$spcl_single[$i]['adult_passport_no'] = $adult_passport_no[$k1]['single'][$i];
									$spcl_single[$i]['adult_passport_exp'] = $adult_passport_exp[$k1]['single'][$i];
									$spcl_single[$i]['room_category'] = 'single';
									$spcl_single[$i]['room_type'] = $k1;
									$spcl_single[$i]['bed'] = $bed_single[$i];
									
								}
								if(!empty($spcl_single)){
									$tourists_spcl = array_merge($tourists_spcl,$spcl_single);
								}
								
								
							}
							if(isset($sal['double']) && !empty($sal['double'])){
								$bed_double = [];
								foreach($bed[$rm]['double'] as $a=>$b){
									for($i=0;$i<3;$i++){
										$bed_double[] = $bed[$rm]['double'][$a];
									}
								}
								
								$count = count($adult_salutation[$k1]['double']);
								for($i=0;$i < $count;$i++){
									
									$spcl_double[$i]['adult_salutation'] = $adult_salutation[$k1]['double'][$i];
									$spcl_double[$i]['adult_fname'] = $adult_fname[$k1]['double'][$i];
									$spcl_double[$i]['adult_lname'] = $adult_lname[$k1]['double'][$i];
									$spcl_double[$i]['adult_dob'] = $adult_dob[$k1]['double'][$i];
									$spcl_double[$i]['adult_nationality'] = $adult_nationality[$k1]['double'][$i];
									$spcl_double[$i]['adult_national_id'] = $adult_national_id[$k1]['double'][$i];
									$spcl_double[$i]['adult_passport_no'] = $adult_passport_no[$k1]['double'][$i];
									$spcl_double[$i]['adult_passport_exp'] = $adult_passport_exp[$k1]['double'][$i];
									$spcl_double[$i]['room_category'] = 'double';
									$spcl_double[$i]['room_type'] = $k1;
									$spcl_double[$i]['bed'] = $bed_double[$i];
								}
								if(!empty($spcl_double)){
									$tourists_spcl = array_merge($tourists_spcl,$spcl_double);
								}
							}
						
							if(isset($sal['triple']) && !empty($sal['triple'])){
								$bed_triple = [];
								foreach($bed[$rm]['triple'] as $a=>$b){
									for($i=0;$i<3;$i++){
										$bed_triple[] = $bed[$rm]['triple'][$a];
									}
								}
								
								$count = count($adult_salutation[$k1]['triple']);
								for($i=0;$i < $count;$i++){
									
									$spcl_triple[$i]['adult_salutation'] = $adult_salutation[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_fname'] = $adult_fname[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_lname'] = $adult_lname[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_dob'] = $adult_dob[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_nationality'] = $adult_nationality[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_national_id'] = $adult_national_id[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_passport_no'] = $adult_passport_no[$k1]['triple'][$i];
									$spcl_triple[$i]['adult_passport_exp'] = $adult_passport_exp[$k1]['triple'][$i];
									$spcl_triple[$i]['room_category'] = 'triple';
									$spcl_triple[$i]['room_type'] = $k1;
									$spcl_triple[$i]['bed'] = $bed_triple[$i];
									
								}
								if(!empty($spcl_triple)){
									$tourists_spcl = array_merge($tourists_spcl,$spcl_triple);
								}
							}
							
						}
						
						
					}
	
				}
					
				}
				
			}
			
			if($single > 0){
				$bed_single = [];
				foreach($bed['standard']['single'] as $a=>$b){
					for($i=0;$i<3;$i++){
						$bed_single[] = $bed['standard']['single'][$a];
					}
				}
				for($i=0;$i< $single;$i++){
					$tou_single[$i]['adult_salutation'] = $adult_salutation['standard']['single'][$i];
					$tou_single[$i]['adult_fname'] = $adult_fname['standard']['single'][$i];
					$tou_single[$i]['adult_lname'] = $adult_lname['standard']['single'][$i];
					$tou_single[$i]['adult_dob'] = $adult_dob['standard']['single'][$i];
					$tou_single[$i]['adult_nationality'] = $adult_nationality['standard']['single'][$i];
					$tou_single[$i]['adult_national_id'] = $adult_national_id['standard']['single'][$i];
					$tou_single[$i]['adult_passport_no'] = $adult_passport_no['standard']['single'][$i];
					$tou_single[$i]['adult_passport_exp'] = $adult_passport_exp['standard']['single'][$i];
					$tou_single[$i]['room_category'] = 'single';
					$tou_single[$i]['room_type'] = 'standard';
					$tou_single[$i]['bed'] = $bed_single[$i];
				}
				if(!empty($tou_single)){
					$tourists = array_merge($tourists,$tou_single);
				}
				
			}
				
			if($double > 0){
				$bed_double = [];
				foreach($bed['standard']['double'] as $a=>$b){
					for($i=0;$i<2;$i++){
						$bed_double[] = $bed['standard']['double'][$a];
					}
				}
				for($i=0;$i< $double;$i++){
					$tou_double[$i]['adult_salutation'] = $adult_salutation['standard']['double'][$i];
					$tou_double[$i]['adult_fname'] = $adult_fname['standard']['double'][$i];
					$tou_double[$i]['adult_lname'] = $adult_lname['standard']['double'][$i];
					$tou_double[$i]['adult_dob'] = $adult_dob['standard']['double'][$i];
					$tou_double[$i]['adult_nationality'] = $adult_nationality['standard']['double'][$i];
					$tou_double[$i]['adult_national_id'] = $adult_national_id['standard']['double'][$i];
					$tou_double[$i]['adult_passport_no'] = $adult_passport_no['standard']['double'][$i];
					$tou_double[$i]['adult_passport_exp'] = $adult_passport_exp['standard']['double'][$i];
					$tou_double[$i]['room_category'] = 'double';
					$tou_double[$i]['room_type'] = 'standard';
					$tou_double[$i]['bed'] = $bed_double[$i];
				}
				if(!empty($tou_double)){
					$tourists = array_merge($tourists,$tou_double);
				}
			}
			
			if($triple > 0){
				$bed_triple = [];
				foreach($bed['standard']['triple'] as $a=>$b){
					for($i=0;$i<3;$i++){
						$bed_triple[] = $bed['standard']['triple'][$a];
					}
				}
				
				for($i=0;$i< $triple;$i++){
					$tou_triple[$i]['adult_salutation'] = $adult_salutation['standard']['triple'][$i];
					$tou_triple[$i]['adult_fname'] = $adult_fname['standard']['triple'][$i];
					$tou_triple[$i]['adult_lname'] = $adult_lname['standard']['triple'][$i];
					$tou_triple[$i]['adult_dob'] = $adult_dob['standard']['triple'][$i];
					$tou_triple[$i]['adult_nationality'] = $adult_nationality['standard']['triple'][$i];
					$tou_triple[$i]['adult_national_id'] = $adult_national_id['standard']['triple'][$i];
					$tou_triple[$i]['adult_passport_no'] = $adult_passport_no['standard']['triple'][$i];
					$tou_triple[$i]['adult_passport_exp'] = $adult_passport_exp['standard']['triple'][$i];
					$tou_triple[$i]['room_category'] = 'triple';
					$tou_triple[$i]['room_type'] = 'standard';
					$tou_triple[$i]['bed'] = $bed_triple[$i];
				}
				if(!empty($tou_triple)){
					$tourists = array_merge($tourists,$tou_triple);
				}
			}
			$final_tourists2 = array_merge($tourists_spcl,$tourists);
			
			
			$final_tourists = array_merge($final_tourists2,$final_tourists1);
			
			
			
			$bookings['service'] = 4;
			$bookings['book_status'] = 0;
			$bookings['user_type'] = $this->input->post('user_type');
			//$bookings['user_id'] = $this->input->post('user_id');
			
			$input_json['tour_id'] = $this->input->post('tour_id');
			$input_json['hotel_id'] = $this->input->post('hotel_id');
			$input_json['notes'] = $this->input->post('notes');
			$input_json['tour_from'] = $this->input->post('tour_from');
			$input_json['tour_to'] = $this->input->post('tour_to');
			$input_json['short_info'] = $this->input->post('short_info');
			
			
			$save_booking_id = $this->Tour_model->save_booking($bookings);
			if($save_booking_id){
				$booking_details['id'] = $save_booking_id;
				$booking_details['traveller_json'] = json_encode($final_tourists);
				$booking_details['total_cost'] = $this->input->post('total_tour_price');
				$booking_details['departures'] = $departures;
				$booking_details['currency'] = $this->data['default_currency'];
				$booking_details['email'] = $this->input->post('email');
				$booking_details['contact'] = $this->input->post('contact');
				$booking_details['departures'] = $this->input->post('departures');
				$booking_details['input_json'] = json_encode($input_json);
				$booking_details['trip_json'] = $after_confirm;
				
				//pr($final_tourists);exit;
				$save_booking_details = $this->Tour_model->save_booking_details($booking_details);
				if($save_booking_details == 1){
					//echo json_encode($save_booking_id);
					echo base64_encode($this->encrypt->encode($save_booking_id));
				}else{
					echo 0;
				}
			}
			
		}
		exit;
		
	}
	
	
	
	function voucher_generate($id){
		$id = $this->encrypt->decode(base64_decode($id));
		$get = $this->Tour_model->get_booking_details(json_decode($id));
		$input_json = json_decode($get->input_json);
		
		$hotel_id = $input_json->hotel_id;
		$tour_id = $input_json->tour_id;
		$hotel_data = $this->Tour_model->voucher_hotel($hotel_id,$this->data['default_language']);
		$tour_data = $this->Tour_model->voucher_tour($tour_id,$this->data['default_language']);
		
		
		$data['book_id'] = $get->book_id;
		$data['hotel'] = $hotel_data;
		$data['travellers'] = json_decode($get->traveller_json);
		$short_info = explode("/",$input_json->short_info);
		$data['in_date'] = date('m/d/Y',strtotime($short_info[0]));
		$data['out_date'] = date('m/d/Y', strtotime($short_info[0]. ' + '.($short_info[1]).' days'));
		$data['no_of_days'] = $short_info[1];
		$data['transport'] = json_decode($get->departures);
		$data["contact_address"] = $this->general->default_contact_address($this->data["default_language"]);
		$this->load->view('tour/voucher',$data);
	}
	
	public function tour_fromcity($iod=null)
	{
		
		$cities_list = $this->Tour_model->get_from_cities($this->data["default_language"],$iod);
		$response = "<option selected value=''>".$this->lang->line("from_city")."</option>";
		if($cities_list !== false)
			foreach($cities_list as $cities)
					$response .= "<option value='".$cities->city_id."'>".$cities->location."</option>";
		echo json_encode(array("result" => $response));exit;
	}
	
	function get_location_info(){
		if($this->input->is_ajax_request())
		{
			$city_id = $this->input->post('id');
			$datas = $this->Tour_model->city_info($this->data["default_language"],$city_id);
			if(isset($datas->city)){
				echo $datas->city;exit;
			}else{
				echo 0;exit;
			}
		}
	}
	
	function check_tour(){
		if($this->input->is_ajax_request())
		{
			$from_id = $this->input->post('from_id');
			$hid_tocity = $this->input->post('hid_tocity');
			
			$selective_tours = $this->Tour_model->check_tour($this->data["default_language"],$from_id);
			$response = "<option  value=''>".$this->lang->line("to_city")."</option>";
			$city_ids = array();
			if(!empty($selective_tours)){
				foreach($selective_tours as $cities){
					if($hid_tocity == $cities->city_id){
						$selected = 'selected';
					}else{
						$selected = '';
					}
					$response .= "<option $selected  value='".$cities->city_id."'>".$cities->location."</option>";
					//$city_ids[] =  $cities->city_id;
				}
			}
			echo json_encode(array("result" => $response));exit;
		}
	}
	
	
	
	

}
