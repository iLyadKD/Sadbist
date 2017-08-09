<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   
//error_reporting('E_ALL ^ E_NOTICE'); 
class Package extends CI_Controller {
    
    public function  __construct(){      
        parent::__construct(); 
      	unset_page_cache();

		$this->data["default_language"] = "en";
		$this->data["page_main_title"] = "Tours";
		$this->data["page_title"] = "Tours";
        $this->load->model("Package_model");      
         $this->load->model("Hotel_model");     
    }

    public function index(){
        $data['tour'] =$this->Package_model->get_tour(); 
        $data['title'] ="View Package"; 
        $this->load->view('package/index', $data);
    }

    public function create(){ 

		
		if($this->input->server("REQUEST_METHOD")=="POST"){ 
			//pr($_POST);exit;
			$date1 = $this->input->post('tour')['to_date'];
			$date2 = $this->input->post('tour')['from_date'];
			$days_week = $this->input->post('tour')['days_week'];
			$get_all_dates = $this->create_price_master($date2,$date1,$days_week);	//pr($get_all_dates);exit;
		   
		    /* Tour Details*/
			$base_flight = [];
			$base_cruise = [];
			$base_train = [];
			$base_bus = [];
			$tr_cost = '';
			
            $tour    = $this->input->post('tour');
			$master = $this->input->post('master');
			$price = $this->input->post('price');
			
			
			//pr($price);exit;
			if($price['discount_price'] == '')
				$price['discount_price'] = 0;
			
			
			
			$discount_price = str_replace(",","",$price['discount_price']);
			
			if($discount_price == 0){
				$overall_tour_price = str_replace(",","",$price['overall_tour_price']);
				$price['flag_discount'] = 0;
			}
			else{
				$overall_tour_price = $discount_price;
				$price['flag_discount'] = 1;
			}
			
			
            if(@$this->input->post('tour')['days_week']){
            $tour['days_week'] = implode(",",$this->input->post('tour')['days_week']);
            }
            if(empty($tour['insurance'])){ $tour['insurance_price'] =0;} else { $tour['insurance_price'] =str_replace(",","",$tour['insurance_price']);} 
            if(empty($tour['visa'])){ $tour['visa_price'] =0;} else { $tour['visa_price'] =str_replace(",","",$tour['visa_price']);}
            if(empty($tour['transfer'])){ $tour['transfer_price'] =0;} else { $tour['transfer_price'] =str_replace(",","",$tour['transfer_price']);}
            if(empty($tour['cipout'])){ $tour['cipout_price'] =0;} else { $tour['cipout_price'] =str_replace(",","",$tour['cipout_price']);}
            if(empty($tour['cipin'])){ $tour['cipin_price'] =0;} else { $tour['cipin_price'] =str_replace(",","",$tour['cipin_price']);}
            if(empty($tour['iod'])){ $tour['iod'] =0;}

            $tour['hotel_id'] = implode(',',$this->input->post('hotel')['hotel_id']);
			
			$tour['default_hotel'] = $this->input->post('hotel')['hotel_id'][0];
			$price_key['percentage'] = str_replace(",","",$price['percentage']);
			$price_key['dollar'] = str_replace(",","",$price['dollar']);
			$tour_id = $this->Package_model->save_tour($tour);
            
            /* Tour Details*/       
            $details = $this->input->post('details');
			
			$text['itinerary_en'] = preg_replace("/[&'#39;]/","",$details['itinerary_en']);
			$text['inclusions_en'] = preg_replace("/[&'#39;]/","",$details['inclusions_en']);
			$text['exclusions_en'] = preg_replace("/[&'#39;]/","",$details['exclusions_en']);
			$text['privacy_policy_en'] = preg_replace("/[&'#39;]/","",$details['privacy_policy_en']);
			$text['terms_conditions_en'] = preg_replace("/[&'#39;]/","",$details['terms_conditions_en']);
			$text['cancellation_policy_en'] = preg_replace("/[&'#39;]/","",$details['cancellation_policy_en']);
			$text['itinerary_fa'] = preg_replace("/[&'#39;]/","",$details['itinerary_fa']);
			$text['inclusions_fa'] = preg_replace("/[&'#39;]/","",$details['inclusions_fa']);
			$text['exclusions_fa'] = preg_replace("/[&'#39;]/","",$details['exclusions_fa']);
			$text['privacy_policy_fa'] = preg_replace("/[&'#39;]/","",$details['privacy_policy_fa']);
			$text['terms_conditions_fa'] = preg_replace("/[&'#39;]/","",$details['terms_conditions_fa']);
			$text['cancellation_policy_fa'] = preg_replace("/[&'#39;]/","",$details['cancellation_policy_fa']);
			
			$json_details = json_encode($text);
			
			$details_data = array('text_contents'=>$json_details,'tour_id'=>$tour_id);
			$this->Package_model->save_details($details_data);

            /* Gallery*/
            if(isset($_FILES['gallery']['name']['image'])){
                $gimage_count = count($_FILES['gallery']['name']['image']);
                for ($g=0; $g < $gimage_count; $g++) { 
                    if($_FILES['gallery']['name']['image'][$g] !=""){
                        $gname = $_FILES['gallery']['name']['image'][$g];
                        $gsize = $_FILES['gallery']['size']['image'][$g];
                        $gtype = $_FILES['gallery']['type']['image'][$g];
                        $gtemp = $_FILES['gallery']['tmp_name']['image'][$g];
                        $ext = explode(".", $gname);
                        $gimage_name = "gallery-".$gsize.rand(0,5000000).".".$ext[1];
                        move_uploaded_file($gtemp,REL_IMAGE_UPLOAD_PATH."/tour/".$gimage_name);
                        $gallery_img = array(
                                            "gallery_name" => $gimage_name
                                            );
                       $this->Package_model->save_images($gallery_img,$tour_id);
                    }
                }
            }
			
			/* Pdf file upload*/
            if(isset($_FILES['file']['name']['tour_file'])){
                $gfile_count = count($_FILES['file']['name']['tour_file']);
                for ($g=0; $g < $gfile_count; $g++) { 
                    if($_FILES['file']['name']['tour_file'][$g] !=""){
                        $gname = $_FILES['file']['name']['tour_file'][$g];
                        $gsize = $_FILES['file']['size']['tour_file'][$g];
                        $gtype = $_FILES['file']['type']['tour_file'][$g];
                        $gtemp = $_FILES['file']['tmp_name']['tour_file'][$g];
                        $ext = explode(".", $gname);
                        $gimage_name = "file-".$gsize.rand(0,5000000).".".$ext[1];
                        move_uploaded_file($gtemp,REL_IMAGE_UPLOAD_PATH."/tour/".$gimage_name);
                        $gallery_img = array(
                                            "file_name" => $gimage_name
                                            );
                       $this->Package_model->save_file($gallery_img,$tour_id);
                    }
                }
            }  

            /* FLight Details*/       
            $flight = $this->input->post('flight');
            $flight_count = count($this->input->post('flight')['tour_airline_id']);
            $flight_final =array();       
            for ($f=0; $f <$flight_count ; $f++) {
                if($flight['tour_airline_id'][$f]){
                    foreach ($flight as $key => $value) {
                        $flight_key['tour_id'] = $tour_id;  
                        if($key == 'flight_price'){
							$flight_key[$key] = str_replace(",","",$flight[$key][$f]);
						}else{
							$flight_key[$key] = $flight[$key][$f];
						}
						  
                    }
                    $flight_final [] = $flight_key;    
                }
            }       
           
		    if($flight_final){
				$this->Package_model->save_flight($flight_final);
				foreach($flight_final as $bp){
						$base_flight[] = $bp['flight_price'];
				}
			}
			
			if(!empty($base_flight)){
				$base_flight = MIN($base_flight);
			}
			//pr($base_flight);exit;
			/* Bus Details*/       
            $bus = $this->input->post('bus');
            $bus_count = count($this->input->post('bus')['bus_name']);
            $bus_final =array();       
            for ($e=0; $e <$bus_count ; $e++) {
                if($bus['bus_name'][$e]){
                    foreach ($bus as $key => $value) {
                        $bus_key['tour_id'] = $tour_id;  
                        if($key == 'price'){
							$bus_key[$key] = str_replace(",","",$bus[$key][$e]);
						}else{
							$bus_key[$key] = $bus[$key][$e];
						}
						  
                    }
                    $bus_final [] = $bus_key;    
                }
            }       
            
            if($bus_final){
				$this->Package_model->save_bus($bus_final);
				foreach($bus_final as $bp){
						$base_bus[] = $bp['price'];
				}
			}
			
			if(!empty($base_bus)){
				$base_bus = MIN($base_bus);
			}
			
			/* Train Details*/       
            $train = $this->input->post('train');
            $train_count = count($this->input->post('train')['train_name']);
            $train_final =array();       
            for ($f=0; $f <$train_count ; $f++) {
                if($train['train_name'][$f]){
                    foreach ($train as $key => $value) {
                        $train_key['tour_id'] = $tour_id;  
                        if($key == 'price'){
							$train_key[$key] = str_replace(",","",$train[$key][$f]);
						}else{
							$train_key[$key] = $train[$key][$f];
						}
						
						  
                    }
                    $train_final [] = $train_key;    
                }
            }       
            
            if($train_final){
				$this->Package_model->save_train($train_final);
				foreach($train_final as $bp){
						$base_train[] = $bp['price'];
				}
			}
			
			if(!empty($base_train)){
				$base_train = MIN($base_train);
			}
			
				/* Cruise Details*/       
            $cruise = $this->input->post('cruise');
            $cruise_count = count($this->input->post('cruise')['cruise_name']);
            $cruise_final =array();       
            for ($g=0; $g <$cruise_count ; $g++) {
                if($cruise['cruise_name'][$g]){
                    foreach ($cruise as $key => $value) {
                        $cruise_key['tour_id'] = $tour_id;  
                        if($key == 'price'){
							$cruise_key[$key] = str_replace(",","",$cruise[$key][$g]);
						}else{
							$cruise_key[$key] = $cruise[$key][$g];
						}
						  
                    }
                   $cruise_final [] = $cruise_key;    
                }
            }       
            
            if($cruise_final){
				$this->Package_model->save_cruise($cruise_final);
				foreach($cruise_final as $bp){
						$base_cruise[] = $bp['price'];
				}
			}
			if(!empty($base_cruise)){
				$base_cruise = MIN($base_cruise);
			}
			
			
			if(empty($base_flight)){
				if(empty($base_cruise)){
					if(empty($base_train)){
						if(empty($base_bus)){
							$total_tour_price = 0;
						}else{
							$tr_cost = $base_bus;
						}
					}else{
						$tr_cost = $base_train;
					}	
				}else{
					$tr_cost = $base_cruise;
				}
			}else{
				$tr_cost = $base_flight;
			}
		
		$hotel_id  = $this->input->post('hotel')['hotel_id'];
		
		
		$price_count = count($this->input->post('price')['doubles']);
		$final_rows =array();       
		
		
		$price_key = [];
		foreach($get_all_dates as $gt){
			$gt = explode("/",$gt);
			$g_date = $gt[0];
			$g_day = $gt[1];
			for ($d=0; $d <$price_count ; $d++) {
				foreach ($price as $key => $value) {
					
					$price_key['tour_id'] = $tour_id;
					$price_key['d_hotel'] = $this->get_default_hotel($tour_id);
					if($key != 'overall_tour_price' && $key != 'discount_price' && $key != 'percentage' && $key != 'dollar' && $key != 'flag_discount'){
						
						
						$price_key[$key] = $price[$key][$d];
						if($key == 'doubles'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'single'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'triple'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'infants'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'twotosix'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'sixtotwelve'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'twelvetosixteenth'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						if($key == 'handle_price'){
							$price_key[$key] = str_replace(",","",$price[$key][$d]);
						}
						
					}
					
					$price_key['tour_date'] = $g_date;
					$price_key['tour_day'] = $g_day;
					
					
					
					if(isset($price_key['price_type']) && $price_key['price_type'] == 1){
						//$price['food_type'] == $tour['food_type'];
						$price_key['counter_bonus'] = str_replace(",","",$master['counter_bonus']);
						if($price['hotel_id'][$d] == $tour['default_hotel']){
							$price_key['overall_tour_price'] = str_replace(",","",$price['overall_tour_price']);
							$price_key['discount_price'] = str_replace(",","",$price['discount_price']);
							$price_key['percentage'] = 0;
							$price_key['dollar'] = 0;
							$price_key['flag_discount'] = $price['flag_discount'];
							$price_key['total_tour_price']   = $tr_cost + $overall_tour_price;
							
							
						}else{
							
							$price_key['overall_tour_price'] = str_replace(",","",$price['doubles'][$d]);
							$price_key['discount_price'] = 0;
							$price_key['percentage'] = 0;
							$price_key['dollar'] = 0;
							$price_key['flag_discount'] = 0;
							$price_key['total_tour_price']   = $tr_cost + str_replace(",","",$price['doubles'][$d]);
							
						}
					}elseif(isset($price_key['price_type']) && $price_key['price_type'] == 2){
						$price_key['counter_bonus'] = 0;
						if($price['hotel_id'][$d] == $tour['default_hotel']){
							$price_key['discount_price'] = 0;
							$price_key['percentage'] = str_replace(",","",$price['percentage']);
							$price_key['dollar'] = str_replace(",","",$price['dollar']);
							$price_key['flag_discount'] = 0;
							
							if($price_key['percentage'] != 0){
								$price_key['overall_tour_price'] = $overall_tour_price-($overall_tour_price*(str_replace(",","",$price['percentage'])/100));
							}else{
								$price_key['overall_tour_price'] = str_replace(",","",$price_key['dollar']);
							}
							$price_key['total_tour_price']   = $tr_cost + str_replace(",","",$price_key['overall_tour_price']);
							
						}else{
							$price_key['discount_price'] = 0;
							$price_key['percentage'] = 0;
							$price_key['dollar'] = 0;
							$price_key['flag_discount'] = 0;
							$price_key['overall_tour_price'] = str_replace(",","",$price['doubles'][$d]);
							$price_key['total_tour_price']   = $tr_cost + str_replace(",","",$price['doubles'][$d]);
							
						}
						
					}
					
				}
					
					$final_rows [] = $price_key;
			} 
		}
		
		
			if($final_rows){
				$this->Package_model->save_price_master($final_rows);
			}
			
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Successfully Added");
			redirect('package', 'refresh');            
        }
        //$data['package_type'] = $this->Package_model->get_package_type();
        $data['country'] = $this->Package_model->get_country();
		$data['vendors'] = $this->Package_model->get_vendor();
		$data['airlines'] = $this->Package_model->get_airlines();
		

        $data['hotel'] = $this->Hotel_model->get_hotel();
        $data['title'] ="Add Package"; 
        $this->load->view('package/create', $data);
    }


    public function edit($id){
        if($this->input->server("REQUEST_METHOD")=="POST"){
               /* Tour Details*/
            
			
			//pr($_POST);exit;
			$base_flight = [];
			$base_cruise = [];
			$base_train = [];
			$base_bus = [];
			
			$exist_day = $this->input->post('exist_day');
			$exist_from = $this->input->post('exist_from')['from_date'];
			$exist_to = $this->input->post('exist_to')['to_date'];
			
			$date1 = $exist_to;
			$date2 = $exist_from;
			$days_week = explode(",",$exist_day['days_week'][0]);
			//pr($days_week);exit;
			$get_all_dates = $this->create_price_master($date2,$date1,$days_week);
			
			
			$tour = $this->input->post('tour');
			$master = $this->input->post('master');
			
			$price = $this->input->post('price');
			
		
			$discount_price = $price['discount_price'];
			
			if($discount_price == 0){
				$overall_tour_price = $price['overall_tour_price'];
				$price['flag_discount'] = 0;
			}
			else{
				$overall_tour_price = $discount_price;
				$price['flag_discount'] = 1;
			}
			
			$price_key['discount_price'] = $price['discount_price'];
			$price_key['overall_tour_price'] = $overall_tour_price;
			
			
			
            //if(@$this->input->post('tour')['days_week']){$tour['days_week'] = implode(",",$this->input->post('tour')['days_week']);}
           if(empty($tour['insurance'])){ $tour['insurance_price'] =0;} else { $tour['insurance_price'] =str_replace(",","",$tour['insurance_price']);} 
            if(empty($tour['visa'])){ $tour['visa_price'] =0;} else { $tour['visa_price'] =str_replace(",","",$tour['visa_price']);}
            if(empty($tour['transfer'])){ $tour['transfer_price'] =0;} else { $tour['transfer_price'] =str_replace(",","",$tour['transfer_price']);}
            if(empty($tour['cipout'])){ $tour['cipout_price'] =0;} else { $tour['cipout_price'] =str_replace(",","",$tour['cipout_price']);}
            if(empty($tour['cipin'])){ $tour['cipin_price'] =0;} else { $tour['cipin_price'] =str_replace(",","",$tour['cipin_price']);}

    
         if($this->input->post('hotel')['hotel_id']){
			 $tour['hotel_id'] = implode(',',$this->input->post('hotel')['hotel_id']);
		 }
		 //$tour['room_type'] = implode(',',$this->input->post('hotel')['room_type']);
		 $tour['default_hotel'] = $this->input->post('hotel')['hotel_id'][0];
		
			$this->Package_model->update_tour($id,$tour);
              /* Tour Details*/       
            $details = $this->input->post('details');
			
			$text['itinerary_en'] = preg_replace("/[&'#39;]/","",$details['itinerary_en']);
			$text['inclusions_en'] = preg_replace("/[&'#39;]/","",$details['inclusions_en']);
			$text['exclusions_en'] = preg_replace("/[&'#39;]/","",$details['exclusions_en']);
			$text['privacy_policy_en'] = preg_replace("/[&'#39;]/","",$details['privacy_policy_en']);
			$text['terms_conditions_en'] = preg_replace("/[&'#39;]/","",$details['terms_conditions_en']);
			$text['cancellation_policy_en'] = preg_replace("/[&'#39;]/","",$details['cancellation_policy_en']);
			$text['itinerary_fa'] = preg_replace("/[&'#39;]/","",$details['itinerary_fa']);
			$text['inclusions_fa'] = preg_replace("/[&'#39;]/","",$details['inclusions_fa']);
			$text['exclusions_fa'] = preg_replace("/[&'#39;]/","",$details['exclusions_fa']);
			$text['privacy_policy_fa'] = preg_replace("/[&'#39;]/","",$details['privacy_policy_fa']);
			$text['terms_conditions_fa'] = preg_replace("/[&'#39;]/","",$details['terms_conditions_fa']);
			$text['cancellation_policy_fa'] = preg_replace("/[&'#39;]/","",$details['cancellation_policy_fa']);
			
			$json_details = json_encode($text);
			$details_data = array('text_contents'=>$json_details);
            $this->Package_model->update_details($id,$details_data);

			/* FLight Details*/       
           
			if($this->input->post('flight')['tour_airline_id'] != ''){
				$flight = $this->input->post('flight');
				
				$flight_count = count($this->input->post('flight')['tour_airline_id']);
				$flight_final =array();  
                $flight_key_new=array();
                $flight_final_new =array();     
				for ($f=0; $f <$flight_count ; $f++) {
					 $flight['tour_airline_id'][$f];
					if($flight['tour_airline_id'][$f]){
                         $tour_flight_id = @$flight['tour_flight_id'][$f];
						 if(!empty($tour_flight_id)){

							foreach ($flight as $key => $value) {
								$flight_key['tour_id'] = $id;  
								if($key == 'flight_price'){
									@$flight_key[$key] = str_replace(",","",$flight[$key][$f]);
								}else{
									@$flight_key[$key] = $flight[$key][$f];
								}
								//@$flight_key[$key] = $flight[$key][$f];  
							  }
								$flight_final [] = $flight_key;   
                        }
						else{
							
							foreach ($flight as $key => $value) {
								$flight_key_new['tour_id'] = $id;  
								if($key == 'flight_price'){
									@$flight_key_new[$key] = str_replace(",","",$flight[$key][$f]);
								}else{
									@$flight_key_new[$key] = $flight[$key][$f];
								}
								//@$flight_key_new[$key] = $flight[$key][$f];  
	
							}
							$flight_final_new [] = $flight_key_new;   
						}
					}        
				}  
				
				if($flight_final){
					$this->Package_model->update_flight($flight_final);
					foreach($flight_final as $bp){
						if($bp['flight_price'] == '')
							$bp['flight_price'] = 0;
						$base_flight[] = $bp['flight_price'];
					}
				}
                if($flight_final_new){
					$this->Package_model->save_flight($flight_final_new);
					foreach($flight_final_new as $bp){
						if($bp['flight_price'] == '')
							$bp['flight_price'] = 0;
						$base_flight[] = $bp['flight_price'];
					}
				}
				
				if(!empty($base_flight)){
					$base_flight = MIN($base_flight);
				}

			}
			

			/* Bus Details*/       
            if($this->input->post('bus')['bus_name'] != ''){
				$bus = $this->input->post('bus');
				$bus_count = count($this->input->post('bus')['bus_name']);
				$bus_final =array();       
                $bus_final_new =array();  
                $bus_key_new=array();     
				for ($f=0; $f <$bus_count ; $f++) {
					if($bus['bus_name'][$f]){
                        $tour_bus_id = @$bus['tour_bus_id'][$f];
                        if(!empty($tour_bus_id)){
						foreach ($bus as $key => $value) {
							$bus_key['tour_id'] = $id;  
							if($key == 'price'){
								@$bus_key[$key] = str_replace(",","",@$bus[$key][$f]);
							}else{
								@$bus_key[$key] = @$bus[$key][$f];
							}
							//$bus_key[$key] = @$bus[$key][$f];  
						}
                         
						$bus_final [] = $bus_key;  
                        }  
						else {
							foreach ($bus as $key => $value) {
								$bus_key_new['tour_id'] = $id;  
								//$bus_key_new[$key] =@$bus[$key][$f];
								if($key == 'price'){
									@$bus_key_new[$key] = str_replace(",","",@$bus[$key][$f]);
								}else{
									@$bus_key_new[$key] = @$bus[$key][$f];
								}
	
							}
							$bus_final_new [] = $bus_key_new;  
						}

					}

				}
				
				
          
				if($bus_final){
					$this->Package_model->update_bus($bus_final);
					foreach($bus_final as $bp){
						if($bp['price'] == '')
							$bp['price'] = 0;
						$base_bus[] = $bp['price'];
					}
				}
				if($bus_final_new){
					$this->Package_model->save_bus($bus_final_new);
					foreach($bus_final_new as $bp){
						if($bp['price'] == '')
							$bp['price'] = 0;
						$base_bus[] = $bp['price'];
					}
				}
				
				if(!empty($base_bus)){
					$base_bus = MIN($base_bus);
				}
            
			}
			
			
			/* Train Details*/       
            
			if($this->input->post('train')['train_name'] != ''){
				$train = $this->input->post('train');
				$train_count = count($this->input->post('train')['train_name']);
				$train_final =array();       
                $train_final_new =array();      
				for ($f=0; $f <$train_count ; $f++) {
					if($train['train_name'][$f]){

                         $tour_train_id = @$train['tour_train_id'][$f];
                        if(!empty($tour_train_id)){
    						foreach ($train as $key => $value) {
    							$train_key['tour_id'] = $id;  
    							//$train_key[$key] = $train[$key][$f];
								if($key == 'price'){
									$train_key[$key] = str_replace(",","",$train[$key][$f]);
								}else{
									$train_key[$key] = $train[$key][$f];
								}
    						}
    						$train_final [] = $train_key; 
                        }
                        else {    
                            foreach ($train as $key => $value) {
                                $train_key_new['tour_id'] = $id;  
                                //$train_key_new[$key] = @$train[$key][$f];
								if($key == 'price'){
									$train_key_new[$key] = str_replace(",","",@$train[$key][$f]);
								}else{
									$train_key_new[$key] = @$train[$key][$f];
								}
                            }
                            $train_final_new [] = $train_key_new;  
                        } 
					}
				}       
         
				if($train_final){
					$this->Package_model->update_train($train_final);
					foreach($train_final as $bp){
						if($bp['price'] == '')
							$bp['price'] = 0;
						$base_train[] = $bp['price'];
					}
					
				}
                if($train_final_new){
					$this->Package_model->save_train($train_final_new);
					foreach($train_final as $bp){
						if($bp['price'] == '')
							$bp['price'] = 0;
						$base_train[] = $bp['price'];
					}
				}
				
				if(!empty($base_train)){
					$base_train = MIN($base_train);
				}
				
			}
			
			if($this->input->post('cruise')['cruise_name'] != ''){
				$cruise = $this->input->post('cruise');
				$cruise_count = count($this->input->post('cruise')['cruise_name']);
				$cruise_final =array();       
                $cruise_final_new =array();      
				for ($g=0; $g <$cruise_count ; $g++) {
					if($cruise['cruise_name'][$g]){

                         $tour_cruise_id = @$cruise['tour_cruise_id'][$g];
                        if(!empty($tour_cruise_id)){
    						foreach ($cruise as $key => $value) {
    							$cruise_key['tour_id'] = $id;  
    							//$cruise_key[$key] = $cruise[$key][$g];
								if($key == 'price'){
									$cruise_key[$key] = str_replace(",","",@$cruise[$key][$f]);
								}else{
									$cruise_key[$key] = @$cruise[$key][$f];
								}
    						}
    						$cruise_final [] = $cruise_key; 
                        }
                        else {    
                            foreach ($cruise as $key => $value) {
                                $cruise_key_new['tour_id'] = $id;  
                                //$cruise_key_new[$key] = @$cruise[$key][$g];
								if($key == 'price'){
									$cruise_key_new[$key] = str_replace(",","",@$cruise[$key][$f]);
								}else{
									$cruise_key_new[$key] = @$cruise[$key][$f];
								}
                            }
                           $cruise_final_new [] = $cruise_key_new;  
                        } 
					}
				}       
         
				if($cruise_final){
					$this->Package_model->update_cruise($cruise_final);
					foreach($cruise_final as $bp){
						if($bp['price'] == '')
							$bp['price'] = 0;
						$base_cruise[] = $bp['price'];
					}
				}
                if($cruise_final_new){
					$this->Package_model->save_cruise($cruise_final_new);
					foreach($cruise_final as $bp){
						if($bp['price'] == '')
							$bp['price'] = 0;
							$base_cruise[] = $bp['price'];
					}
				}
				
				if(!empty($base_cruise)){
					$base_cruise = MIN($base_cruise);
				}
				
			}

			
			//pr($base_flight);pr($base_cruise);pr($base_train);pr($base_bus);exit;
			$tr_cost = 0;
			if(empty($base_flight) && $base_flight != 0){
				if(empty($base_cruise) && $base_cruise != 0){
					if(empty($base_train) && $base_train != 0){
						if(empty($base_bus) && $base_bus != 0){
							$tr_cost = 0;
						}else{
							$tr_cost = $base_bus;
						}
					}else{
						$tr_cost = $base_train;
					}	
				}else{
					$tr_cost = $base_cruise;
				}
			}else{
				$tr_cost = $base_flight;
			}
			
				$this->Package_model->master_price_updation($id,$tr_cost);
			
			if(isset($price['doubles'])) {
						
						$price_count = count($this->input->post('price')['doubles']);
						$final_rows =array();    
						$hotels_rows =array();          
						foreach($get_all_dates as $gt){
									$gt = explode("/",$gt);
									$g_date = $gt[0];
									$g_day = $gt[1];
									
									for ($d=0; $d <$price_count ; $d++) {
										foreach ($price as $key => $value) {
											
											
											$price_key['tour_id'] = $id;
											$price_key['d_hotel'] = $this->get_default_hotel($id);
											if($key != 'overall_tour_price' && $key != 'discount_price' && $key != 'percentage' && $key != 'dollar' && $key != 'flag_discount'){
												$price_key[$key] = $price[$key][$d];
												if($key == 'doubles'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'single'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'triple'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'infants'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'twotosix'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'sixtotwelve'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'twelvetosixteenth'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												if($key == 'handle_price'){
													$price_key[$key] = str_replace(",","",$price[$key][$d]);
												}
												
											}
											
											$price_key['tour_date'] = $g_date;
											$price_key['tour_day'] = $g_day;
											$price_key['overall_tour_price'] = str_replace(",","",$price['overall_tour_price']);
											if(isset($price_key['price_type']) && $price_key['price_type'] == 1){
												$price_key['counter_bonus'] = str_replace(",","",$master['counter_bonus']);
												$price_key['discount_price'] = str_replace(",","",$price['discount_price']);
												$price_key['percentage'] = 0;
												$price_key['dollar'] = 0;
												$price_key['overall_tour_price'] = str_replace(",","",$price['overall_tour_price']);
												$price_key['total_tour_price'] = $tr_cost + $overall_tour_price;
												$price_key['flag_discount'] = $price['flag_discount'];
											}elseif(isset($price_key['price_type']) && $price_key['price_type'] == 2){
												$price_key['counter_bonus'] = 0;
												$price_key['discount_price'] = 0;
												$price_key['percentage'] = $price['percentage'];
												$price_key['dollar'] = str_replace(",","",$price['dollar']);
												$price_key['flag_discount'] = 0;
												$price_key['overall_tour_price'] = $price['doubles'][0];
												$price_key['total_tour_price']   = $tr_cost + str_replace(",","",$price['doubles'][0]);
												
											}
										}
											/*if($price_key['price_type'] == 2){
												pr($price_key);exit;
											}*/
											
											$final_rows [] = $price_key;       
									} 
						}
						
						
						
						
						if($final_rows){
							$this->Package_model->save_price_master($final_rows);			
						}
			}

			


            
             
			
             /* Gallery*/    
            if(isset($_FILES['gallery']['name']['image'])){
                $gimage_count = count($_FILES['gallery']['name']['image']);
                for ($g=0; $g < $gimage_count; $g++) { 
                    if($_FILES['gallery']['name']['image'][$g] !=""){
                        $gname = $_FILES['gallery']['name']['image'][$g];
                        $gsize = $_FILES['gallery']['size']['image'][$g];
                        $gtype = $_FILES['gallery']['type']['image'][$g];
                        $gtemp = $_FILES['gallery']['tmp_name']['image'][$g];
                        $ext = explode(".", $gname);
                        $gimage_name = "gallery-".$gsize.rand(0,5000000).".".$ext[1];
                        move_uploaded_file($gtemp,REL_IMAGE_UPLOAD_PATH."/tour/".$gimage_name);
                        $gallery_img = array(
                                            "gallery_name" => $gimage_name
                                            );
                       $this->Package_model->save_images($gallery_img,$id);
                    }
                }
            }
			
			/* Pdf file upload*/
            if(isset($_FILES['file']['name']['tour_file'])){
                $gfile_count = count($_FILES['file']['name']['tour_file']);
                for ($g=0; $g < $gfile_count; $g++) { 
                    if($_FILES['file']['name']['tour_file'][$g] !=""){
                        $gname = $_FILES['file']['name']['tour_file'][$g];
                        $gsize = $_FILES['file']['size']['tour_file'][$g];
                        $gtype = $_FILES['file']['type']['tour_file'][$g];
                        $gtemp = $_FILES['file']['tmp_name']['tour_file'][$g];
                        $ext = explode(".", $gname);
                        $gimage_name = "file-".$gsize.rand(0,5000000).".".$ext[1];
                        move_uploaded_file($gtemp,REL_IMAGE_UPLOAD_PATH."/tour/".$gimage_name);
                        $gallery_img = array(
                                            "file_name" => $gimage_name
                                            );
                       $this->Package_model->save_file($gallery_img,$id);
                    }
                }
            }  
			
			
			if(isset($this->input->post('type_price')['name']) && $this->input->post('type_price')['name'] != ''){
				
				$type_price = $this->input->post('type_price');
				$type_price_count = count($this->input->post('type_price')['name']);
				$type_price_final =array();       
                $type_price_final_new =array();  
                $type_price_key_new=array();     
				for ($x=0; $x <$type_price_count ; $x++) {
					if($type_price['name'][$x]){
                        $tour_type_price_id = @$type_price['tour_id'][$x];
                        $price_arr = [];
						foreach($type_price['price'] as $p){
							if($p != '')
								$price_arr[] = $p;
						}
						
						$type_price['price'] = $price_arr;
						if(!empty($tour_type_price_id)){
						
						foreach ($type_price as $key => $value) {
							$type_price_key['tour_id'] = $id;  
							$type_price_key[$key] = @$type_price[$key][$x];
							
						}
                        
						
						
						//pr($type_price);exit;
						$type_price_final [] = $type_price_key;  
                        }  
						else {
							foreach ($type_price as $key => $value) {
								$type_price_key_new['tour_id'] = $id;  
								if($key != 'tour_id')
									$type_price_key_new[$key] =$type_price[$key][$x];  
	
							}
							$type_price_final_new [] = $type_price_key_new;  
						}

					}

				}
				
				
			//pr($type_price_final);exit;
				if($type_price_final){
					$this->Package_model->update_type_price($type_price_final,$id);
					
				}
				if($type_price_final_new){
					$this->Package_model->save_type_price($type_price_final_new);
				}
            
			}

			
			

            //$this->update_master_price($id);
			redirect('package', 'refresh'); 

        }
		$data['vendors'] = $this->Package_model->get_vendor();
        //$data['package_type'] = $this->Package_model->get_package_type();
		$data['airlines'] = $this->Package_model->get_airlines();
        $data['country'] = $this->Package_model->get_country();
        $data['tour'] = $this->Package_model->get_tour_id($id);
		$data['master_details'] = $this->Package_model->master_details($id,1);
		
        $data['hotel_list'] = $this->Hotel_model->get_hotel();
        $data['hotel'] = $this->Package_model->get_hotel_id(explode(",",$data['tour']->hotel_id));
		//pr($data['hotel']);exit;
		$data['bus'] = $this->Package_model->get_bus($id);
		$data['train'] = $this->Package_model->get_train($id);
		$data['cruise'] = $this->Package_model->get_cruise($id);
        $data['flight'] = $this->Package_model->get_flight($id);
        $data['details'] = $this->Package_model->get_details_id($id);
        $data['gallery'] = $this->Package_model->get_images($id);
		$data['file'] = $this->Package_model->get_file($id);
        $data['d_city'] = $this->Package_model->get_city($data['tour']->d_country);
        $data['o_city'] = $this->Package_model->get_city($data['tour']->o_country);
    
        $data['id']  =$id;
        $data['title'] ="Edit package";
        $this->load->view('package/edit', $data);
    }
	
	public function copyandinsert($data,$id,$added_days,$exist_day){
		$action = $this->Package_model->copyandinsert($id,$added_days,$data,$exist_day);
		
	}
	
	 public function duplicate($id){
       $data['vendors'] = $this->Package_model->get_vendor();
        $data['package_type'] = $this->Package_model->get_package_type();
		$data['airlines'] = $this->Package_model->get_airlines();
        $data['country'] = $this->Package_model->get_country();
        $data['tour'] = $this->Package_model->get_tour_id($id);
        $data['hotel_list'] = $this->Hotel_model->get_hotel();
        $data['hotel'] = $this->Package_model->get_hotel_id(explode(",",$data['tour']->hotel_id));
		$data['bus'] = $this->Package_model->get_bus($id);
		$data['train'] = $this->Package_model->get_train($id);
		$data['cruise'] = $this->Package_model->get_cruise($id);
        $data['flight'] = $this->Package_model->get_flight($id);
        $data['details'] = $this->Package_model->get_details_id($id);
        $data['gallery'] = $this->Package_model->get_images($id);
		$data['file'] = $this->Package_model->get_file($id);
        $data['d_city'] = $this->Package_model->get_city($data['tour']->d_country);
        $data['o_city'] = $this->Package_model->get_city($data['tour']->o_country);
		$data['master_details'] = $this->Package_model->master_details($id,1);
    
        $data['id']  =$id;
        $data['title'] ="Edit package";
        $this->load->view('package/edit', $data);
    }

    public function view($id){
        $data['package'] = $this->Package_model->get_package_id($id);
        $data['itinerary'] = $this->Package_model->get_itinerary($id);
        $data['details'] = $this->Package_model->get_details($id);
        $data['duration'] = $this->Package_model->get_duration($id);
        $data['images'] = $this->Package_model->get_images($id);
        $data['package_type'] = $this->Package_model->get_package_type();
        $data['country'] = $this->Package_model->get_country();
        $data['id']  =$id;
        $this->load->view('package/view', $data);
    }

   

    public function get_hotel_json(){

       echo $hotel_id = $this->input->post('hotel_id');
       echo $tour_id = $this->input->post('tour_id');
        if($hotel_id && $tour_id){
            $data['hotel'] = $this->Hotel_model->get_hotel_id($hotel_id);
            $data['rprice'] = $this->Package_model->get_price($tour_id,$hotel_id,1); 
            $data['price'] = $this->Package_model->get_price($tour_id,$hotel_id,2);
            $data['erprice'] = $this->Package_model->get_price($tour_id,$hotel_id,3); 
            $data['eprice'] = $this->Package_model->get_price($tour_id,$hotel_id,4);  
            $this->load->view('package/price', $data);
        }
    }

    public function city(){

         $code = $this->input->post('country_id');
         $city = $this->input->post('city');
         $name = $this->input->post('name');
         $selected="";
        if($code){
            $city_results = $this->Package_model->get_city($code);
             echo "<select name='tour[".$name."]' class='form-control select2' data-rule-required='true'>";
            if($city_results){
                echo "<option value=''>Select City</option>";
                foreach ($city_results as  $row) {
                 if($city == $row->id) {  $selected="selected"; } else { $selected="";}
                echo "<option value=".$row->id." ".$selected.">".$row->city."</option>";
                }
            }else {
                 echo "<option value=''>No City Found</option>";
            }
            echo "</select>";
        }
     

    }

    public function status($id,$status){
        $data = array('status'=>$status);
        $update =$this->Package_model->update_status($id,$data); 
      if($update){
        echo "1";
      }
    }

    public function delete($id){
        $this->Package_model->delete($id);  
        $this->session->set_flashdata('message', 'Sucessfully Deleted');
        redirect('package', 'refresh');
    }

    public function delete_image($id){
       $name=  $this->input->get('name');
       $result = $this->Package_model->delete_image($id);  
       if($result){
        unlink("cdn/packages/gallery/".$name);
        echo "1";
       }
    }

    public function hotel_delete(){
        if($this->input->server("REQUEST_METHOD")=="POST"){
            $hotel_id=  $this->input->post('hotel_id');
            $tour_id=  $this->input->post('tour_id');
            $tour = $this->Package_model->get_tour_id($tour_id);
            $tour_hotel_id = explode(",", $tour->hotel_id);
            $tour_hotel_id = array_diff($tour_hotel_id, array($hotel_id));
            $tour_hotel_id = implode(",", $tour_hotel_id);

            $this->Package_model->update_tour($tour_id,array("hotel_id"=>$tour_hotel_id));   
            $result = $this->Package_model->delete_hotel_price($hotel_id,$tour_id);  

            if( $result){ echo "1";}
        }    
    }


    public function flight_delete(){
        $tour_flight_id=  $this->input->post('transport_id');
        $tour_id=  $this->input->post('tour_id');
        $result = $this->Package_model->delete_flight($tour_flight_id,$tour_id);  
        if( $result){ echo "1";}
    }

    public function bus_delete(){
        $tour_bus_id=  $this->input->post('transport_id');
        $tour_id=  $this->input->post('tour_id');
        $result = $this->Package_model->delete_bus($tour_bus_id,$tour_id);  
        if( $result){ echo "1";}
    }

    public function train_delete(){
        $tour_train_id=  $this->input->post('transport_id');
        $tour_id=  $this->input->post('tour_id');        
        $result = $this->Package_model->delete_train($tour_train_id,$tour_id);  
        if( $result){ echo "1";}
    }
	
	 public function cruise_delete(){
        $tour_cruise_id=  $this->input->post('transport_id');
        $tour_id=  $this->input->post('tour_id');        
        $result = $this->Package_model->delete_cruise($tour_cruise_id,$tour_id);  
        if( $result){ echo "1";}
    }
	
	public function create_vendor(){
         if($this->input->server("REQUEST_METHOD")=="POST"){ 
			$vendor = $this->input->post('vendor');
			
			if($vendor){
				$this->Package_model->save_vendor($vendor);	
			}
            $this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Successfully Added");
            redirect('package/vendors', 'refresh');            
        }
      
        $this->load->view('package/create_vendor');
    }
	
	public function vendors(){
        $data['vendors'] =$this->Package_model->get_vendor(); 
        $data['title'] ="Vendor list"; 
        $this->load->view('package/vendors', $data);
    }
	
	public function edit_vendor($id){
			if($id){
				$id = base64_decode($id);
				if($this->input->server("REQUEST_METHOD")=="POST"){
					$vendor = $this->input->post('vendor');
					if($vendor){
						 $this->Package_model->update_vendor($id,$vendor);
						 redirect('package/vendors', 'refresh'); 
					}
					
				}else{
					$data['vendor'] =$this->Package_model->get_vendor_id($id);
					$this->load->view('package/edit_vendor', $data);
					
				}
				
			}else{
				redirect('package/vendors', 'refresh');
			}
		
		
    }
	
	public function checkunique(){
		if($this->input->is_ajax_request()){
			$tour_id = $this->input->post('tour_id');
			echo $chk_tourid = $this->Package_model->check_tour_id($tour_id);exit;
			
		}
	}
	public function checkunique_vendor(){
		if($this->input->is_ajax_request()){
			$vendor_id = $this->input->post('vendor_id');
			echo $chk_vid = $this->Package_model->check_vendor_id($vendor_id);exit;
			
		}
	}
	
	public function checkunique_vendor_login(){
		if($this->input->is_ajax_request()){
			$vendor_id = $this->input->post('log_id');
			echo $chk_vid = $this->Package_model->check_vendor_log_id($vendor_id);exit;
			
		}
	}
	
	
function create_price_master($strDateFrom,$strDateTo,$days_week) {
	$alldays = array();
	
	foreach($days_week as $wd){
		if($wd == 1){
			array_push($alldays,'Mon');
		}
		if($wd == 2){
			array_push($alldays,'Tue');
		}
		if($wd == 3){
			array_push($alldays,'Wed');
		}
		if($wd == 4){
			array_push($alldays,'Thu');
		}
		if($wd == 5){
			array_push($alldays,'Fri');
		}
		if($wd == 6){
			array_push($alldays,'Sat');
		}
		if($wd == 7){
			array_push($alldays,'Sun');
		}
	}
	
    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        $date1 = date('Y-m-d',$iDateFrom);
		$day1 = date('D',strtotime($date1));
		$com1 = $date1.'/'.$day1;
		 // first entry
		if(in_array($day1,$alldays)){
			array_push($aryRange,$com1);
		}
		
		
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
			$date2 = date('Y-m-d',$iDateFrom);
			$day2 = date('D',strtotime($date2));
            $com2 = $date2.'/'.$day2;
			if(in_array($day2,$alldays)){
				array_push($aryRange,$com2);
			}
        }
    }
    return $aryRange;
}

	public function update_master_price($id,$hotels,$df,$days){
		$hotel_ids = explode(",",base64_decode($hotels));
		$df = base64_decode($df);
		$hotels = [];
		$days = explode(",",base64_decode($days));
		$data['days'] = $days;
		foreach($hotel_ids as $ids){
			$detail_hotels = $this->Package_model->hotel_detail($ids);
			$hotels[] = $detail_hotels;
		}
		
		$data['hotels'] = $hotels; 
		$data['tour_id'] = $id;
		$data['default_hotel'] = $df;		
		
		$this->load->view('package/master_price', $data);
		
	}
	
	public function get_hotel_price_master(){
		if($this->input->post('days')){
			$days = $this->input->post('days');
		}else{
			$days = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		}
		
		
		$room_type = $this->input->post('room_type');
		$hotel_id = $this->input->post('hotel_id');
		$tour_id = $this->input->post('tour_id');
		$df = $this->input->post('df');
		$flag = $this->input->post('flag');
		if($flag == 0 && $room_type == 'Standard') $view = 'master_price_sector';else $view = 'master_price_sector_extra';
        
		
		if($hotel_id && $tour_id){
            $data['price'] = $this->Package_model->get_price_master($hotel_id,$tour_id,$flag,$days,$room_type);
			$data['tour'] = $this->Package_model->get_tour_id($tour_id);
			$data['df'] = $df;
			$data['tour_id'] = $tour_id;
			$data['selected_type'] = $room_type;
			$data['room_types'] = $this->Hotel_model->get_room_types($hotel_id);
			$this->load->view('package/'.$view, $data);
        }
    }
	
	public function master_update(){
		$tour = $this->input->post('tour');
		$price = $this->input->post('price');
		//pr($price);exit;
		$price_count = count($this->input->post('price')['doubles']);
		$final_rows =array();
		$params = explode("-",$this->input->post('master')['hotel_id']);
		$hotel_id = $params[0];
		$tour_id = $params[1];
		//pr($price);exit;
		//pr(count($price['counter_bonus']));exit;
		for ($d=0; $d <$price_count ; $d++) {
			$price_id = @$price['id'][$d];
			 if(!empty($price_id)){
				 foreach ($price as $key => $value) {
					 $price_key['tour_id'] = $tour_id;  
					 $price_key[$key] = str_replace(",","",$price[$key][$d]);  
				 }
				 $final_rows [] = $price_key;
			 }
		}
		
		//pr($final_rows);exit;
        if($final_rows){
			$this->Package_model->update_price_master($final_rows);
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Successfully Updated the price");
            redirect('package/edit/'.$tour_id, 'refresh');
		}
	}
	
	function get_default_hotel($tour_id){
		
		if($tour_id){
			$d_hotel = $this->Package_model->get_default_hotel($tour_id);
			return $d_hotel;
		}
	}
	
	public function delete_vendor($id){
		$id = base64_decode($id);
		
		$count_vendors = $this->check_before_delete($id);
		
		if($count_vendors >0){
			$this->session->set_flashdata('message', 'You can not delete this vendor who is already associated with tours ');
			redirect('package/vendors', 'refresh');
		}
		
		$this->Package_model->delete_vendor($id);	
		$this->session->set_flashdata('message', 'Sucessfully Deleted');
		redirect('package/vendors', 'refresh');
	}
	
	
	/**********Airlines START*************/
	


	public function airlines_add(){
		$this->data["page_title"] = "Airlines";
		if($this->input->server("REQUEST_METHOD")=="POST"){
			$data['airline_en'] = $this->input->post('airline_en');
			$data['airline_fa'] = $this->input->post('airline_fa'); 
            $data['airline_country'] = $this->input->post('airline_country');
			$airline_id = $this->Package_model->save_airlines($data);
			//pr($_FILES);exit;
			/********************Logo START*****************************/
			if(isset($_FILES['airline_logo']['name']) && $_FILES['airline_logo']['name'] != ''){
				$airline_logo_name = pathinfo($_FILES['airline_logo']['name'], PATHINFO_FILENAME);
				$airline_logo_ext = pathinfo($_FILES['airline_logo']['name'], PATHINFO_EXTENSION);
				$airline_logo_size = $_FILES['airline_logo']['size'];
				$airline_logo_name = "airline_logo-".$airline_logo_size.rand(0,5000000).".".$airline_logo_ext;
				$config['upload_path'] = REL_IMAGE_UPLOAD_PATH.'/tour/airline_logo/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = $airline_logo_name;
				//pr($config);exit;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('airline_logo')){
					$error = array('error' => $this->upload->display_errors());
				}else{
					$this->load->library('image_lib');
					$image_name = $airline_logo_name;
					$data = $this->upload->data();
					$file_name = $data['file_name'];
					$config_res['source_image'] = REL_IMAGE_UPLOAD_PATH.'/tour/airline_logo/'.$image_name;
					$config_res['maintain_ratio'] = TRUE;
					$config_res['width'] = 800;
					$config_res['height'] = 400;
					$this->image_lib->initialize($config_res);
					$this->image_lib->resize();
					$this->image_lib->clear();
				}
				
				$tour_airline_logo = array(
						"airline_logo" => $file_name
				);
				$this->Package_model->save_airlines_logo($airline_id,$tour_airline_logo);
			}
			
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Successfully Added");
            redirect('package/view_airlines', 'refresh');
		
		}
		
		$this->load->model("Package_model");
        $data['country'] = $this->Package_model->get_country();
        $this->load->view('package/airline/add', $data);
	}
	
	public function view_airlines(){
        $data['airlines'] =$this->Package_model->get_airlines();
		
        $this->data["page_title"] = "Airlines";
        $this->load->view('package/airline/index', $data);
    }
	
	public function edit_airlines($id){
        $this->data["page_title"] = "Airlines";
		if($this->input->server("REQUEST_METHOD")=="POST"){  
            $data['airline_en'] = $this->input->post('airline_en');
			$data['airline_fa'] = $this->input->post('airline_fa');
            $data['airline_country'] = $this->input->post('airline_country');
       
			$this->Package_model->update_airlines($id,$data);
        
			
		
		if(isset($_FILES['airline_logo']['name']) && $_FILES['airline_logo']['name'] != ''){
				$airline_logo_name = pathinfo($_FILES['airline_logo']['name'], PATHINFO_FILENAME);
				$airline_logo_ext = pathinfo($_FILES['airline_logo']['name'], PATHINFO_EXTENSION);
				$airline_logo_size = $_FILES['airline_logo']['size'];
				$airline_logo_name = "airline_logo-".$airline_logo_size.rand(0,5000000).".".$airline_logo_ext;
				$config['upload_path'] = REL_IMAGE_UPLOAD_PATH.'/tour/airline_logo/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = $airline_logo_name;
				//pr($config);exit;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('airline_logo')){
					$error = array('error' => $this->upload->display_errors());
				}else{
					$this->load->library('image_lib');
					$image_name = $airline_logo_name;
					$data = $this->upload->data();
					$file_name = $data['file_name'];
					$config_res['source_image'] = REL_IMAGE_UPLOAD_PATH.'/tour/airline_logo/'.$image_name;
					$config_res['maintain_ratio'] = TRUE;
					$config_res['width'] = 800;
					$config_res['height'] = 400;
					$this->image_lib->initialize($config_res);
					$this->image_lib->resize();
					$this->image_lib->clear();
				}
				
				$tour_airline_logo = array(
						"airline_logo" => $file_name
				);
				$this->Package_model->save_airlines_logo($id,$tour_airline_logo);
		}
			
			redirect('package/view_airlines', 'refresh');           
        }    

        $this->load->model("Package_model");
        $data['country'] = $this->Package_model->get_country();
        $data['airlines'] = $this->Package_model->get_airlines_id($id);
        $data['id'] = $id;
		$this->data["page_title"] = "Airlines";
		$this->load->view('package/airline/edit', $data);
    }
	
	public function delete_airlines($id,$name){
		$delete = $this->Package_model->delete_airlines($id);
		
		if($delete){
			unlink(REL_IMAGE_UPLOAD_PATH.'upload_files/tour/airline_logo/'.$name);
		}
        $this->session->set_flashdata('message', 'Sucessfully Deleted');
        redirect('package/view_airlines', 'refresh');
    }
	
	 public function airline_status($id,$status){
        $data = array('status'=>$status);
        $update =$this->Package_model->update_airline_status($id,$data); 
      if($update){
        echo "1";
      }
    }
	/**********Airlines STOP**************/
	
	
	function retrieve_data(){
		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0){
			$master_id =  $this->input->post('id');
			$front_address = explode('admin',base_url());
			$front_address = $front_address[0];
			echo $link = $front_address.'tour/details/'.base64_encode($this->encrypt->encode($master_id));exit;
		}
		
	}
	
	function get_trans_flag($id){
		$get_trans_flag = $this->Package_model->get_trans_flag($id);
		return $get_trans_flag;
	}
	
	public function tour_links()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$datas = $this->Package_model->tour_links($id, $search, $page);
		echo json_encode($datas);exit;
	}

	
	public function tour_deals_data()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$datas = $this->Package_model->tour_deals_data($id, $search, $page);
		echo json_encode($datas);exit;
	}
	
	public function attraction_data()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$datas = $this->Package_model->attraction_data($id, $search, $page);
		echo json_encode($datas);exit;
	}
	
	function check_before_delete($id){
		$count_vendors = $this->Package_model->count_vendors($id);
		if(!empty($count_vendors))
			return count($count_vendors);
		else
			return 0;
	}
	
	
	
	



    
}