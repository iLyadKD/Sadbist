<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   
//error_reporting('E_ALL ^ E_NOTICE'); 
class Hotel extends CI_Controller {
    
    public function  __construct(){      
        parent::__construct(); 
      	unset_page_cache();

		$this->data["default_language"] = "en";
		$this->data["page_main_title"] = "Hotel";
		$this->data["page_title"] = "Hotel";
        $this->load->model("Hotel_model");         
    }

    public function index(){
        $data['hotel'] =$this->Hotel_model->get_hotel($lang = DEFAULT_LANG); 
        $data['title'] ="View Hotel"; 
        $this->load->view('hotel/index', $data);
    }

	
	
    public function create(){
         if($this->input->server("REQUEST_METHOD")=="POST"){ 
            $hotel = $this->input->post('hotel');
			
			if(!empty($hotel['room_types'])){
				$en = [];
				$fa = [];
				$base = [];
				$room_types = explode(",",$hotel['room_types']);
				for($i=0;$i <count($room_types);$i++){
					if($i%2 == 0){
						array_push($en,$room_types[$i]);
					}else{
						array_push($fa,$room_types[$i]);
					}
				}
				
				if(count($en)>0){
					for($c=0;$c<count($en);$c++){
						if(!isset($fa[$c]))
							$fa[$c] = '';
						$base[] = 'en->'.$en[$c].'--'.'fa->'.$fa[$c];
						
					}
				}
				
				$hotel['room_types'] = json_encode($base);
				
			}
           //pr($hotel);exit;
		    $hotel_id = $this->Hotel_model->save_hotel($hotel);
			
			/********************Logo START*****************************/
			if(isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != ''){
				$logo_name = pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME);
				$logo_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
				$logo_size = $_FILES['logo']['size'];
				$logo_name = "logo-".$logo_size.rand(0,5000000).".".$logo_ext;
				$config['upload_path'] = REL_IMAGE_UPLOAD_PATH.'/hotel/logo/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = $logo_name;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('logo')){
					$error = array('error' => $this->upload->display_errors());
				}else{
					$this->load->library('image_lib');
					$image_name = $logo_name;
					$data = $this->upload->data();
					$file_name = $data['file_name'];
					$config_res['source_image'] =REL_IMAGE_UPLOAD_PATH.'/hotel/logo/'.$image_name;
					$config_res['maintain_ratio'] = TRUE;
					$config_res['width'] = 800;
					$config_res['height'] = 400;
					$this->image_lib->initialize($config_res);
					$this->image_lib->resize();
					$this->image_lib->clear();
				}
				
				$hotel_logo = array(
						'hotel_id' => $hotel_id,
						"logo" => $file_name
				);
				$this->Hotel_model->save_logo($hotel_id,$hotel_logo);
			}
			
			


			
			/********************Logo END****************************/
			
			
			
			
			
			
			
			
			$file_index = explode(",",$this->input->post('file_index'));
			$max_count = max($file_index);
			$first_key = key($_FILES['gallery']['name']);
			$gal_count = count($_FILES['gallery']['name']);
			$gall = [];
			for($i=$first_key;$i<=$max_count;$i++){
				if($file_index[$first_key] != ''){
					if(in_array($i,$file_index)){
						$gall['gallery'][$i]['name'] = $_FILES['gallery']['name'][$i];
						$gall['gallery'][$i]['type'] = $_FILES['gallery']['type'][$i];
						$gall['gallery'][$i]['tmp_name'] = $_FILES['gallery']['tmp_name'][$i];
						$gall['gallery'][$i]['error'] = $_FILES['gallery']['error'][$i];
						$gall['gallery'][$i]['size'] = $_FILES['gallery']['size'][$i];
					}
				}
			}
			
			$first_key = key($gall['gallery']);
			if($gall['gallery'][$first_key]['name'] != ''){
				$gimage_count = count($gall['gallery']);
				$this->load->library('image_lib');
				for ($g=$first_key;$g<=$max_count;$g++) { 
						if(in_array($g,$file_index)){
							$gname = $gall['gallery'][$g]['name'];
							$gsize = $gall['gallery'][$g]['size'];
							$gtype = $gall['gallery'][$g]['type'];
							$gtemp = $gall['gallery'][$g]['tmp_name'];
							$ext = explode(".", $gname);
							$gimage_name = "gallery-".$gsize.rand(0,5000000).".".$ext[1];
						   
							$config['upload_path'] = REL_IMAGE_UPLOAD_PATH."/hotel/gallery/";
							$config['allowed_types'] = 'gif|jpg|jpeg|png';
							$config['file_name'] = $gimage_name;
							$config['overwrite'] = FALSE;
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							$_FILES = $gall['gallery'];
							if (!$this->upload->do_upload($g)){
								 $error = array('error' => $this->upload->display_errors());
							}else{
								$data = $this->upload->data();
								$file_name = $data['file_name'];
								$config_res['source_image'] = REL_IMAGE_UPLOAD_PATH.'/hotel/gallery/'.$file_name;
								$config_res['maintain_ratio'] = TRUE;
								$config_res['width'] = 800;
								$config_res['height'] = 400;
								$this->image_lib->initialize($config_res);
								$this->image_lib->resize();
								$this->image_lib->clear();
							}
							$gallery_img = array(
												'hotel_id' => $hotel_id,
												"gallery_name" => $file_name
												);
						   
						  
						   $this->Hotel_model->save_images($gallery_img);
							
						}
                }
            }
            
            redirect('hotel', 'refresh');            
        }
        $this->load->model("Package_model");
        $data['country'] = $this->Package_model->get_country();
        $this->load->view('hotel/create', $data);
    }

    
    public function edit($id){
        if($this->input->server("REQUEST_METHOD")=="POST"){  
            
            $hotel = $this->input->post('hotel');
			if(!empty($hotel['room_types'])){
				$en = [];
				$fa = [];
				$base = [];
				$room_types = explode(",",$hotel['room_types']);
				for($i=0;$i <count($room_types);$i++){
					if($i%2 == 0){
						array_push($en,$room_types[$i]);
					}else{
						array_push($fa,$room_types[$i]);
					}
				}
				if(count($en)>0){
					for($c=0;$c<count($en);$c++){
						if(!isset($fa[$c]))
							$fa[$c] = '';
						$base[] = 'en->'.$en[$c].'--'.'fa->'.$fa[$c];
						
					}
				}
				$hotel['room_types'] = json_encode($base);
				
			}
			
			
			
			
			
            $extra_night = @$hotel['extra_night'];
            if(empty($extra_night)){ $hotel['extra_night']="0";}else {
                $hotel['extra_night']= $extra_night;
          }
       
			$this->Hotel_model->update_hotel($id,$hotel);
        
		
		
		if(isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != ''){
				$logo_name = pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME);
				$logo_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
				$logo_size = $_FILES['logo']['size'];
				$logo_name = "logo-".$logo_size.rand(0,5000000).".".$logo_ext;
				$config['upload_path'] = REL_IMAGE_UPLOAD_PATH.'/hotel/logo/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = $logo_name;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('logo')){
					$error = array('error' => $this->upload->display_errors());
				}else{
					$this->load->library('image_lib');
					$image_name = $logo_name;
					$data = $this->upload->data();
					$file_name = $data['file_name'];
					$config_res['quality'] = '100';
					$config_res['source_image'] = REL_IMAGE_UPLOAD_PATH.'/hotel/logo/'.$file_name;
					$config_res['maintain_ratio'] = FALSE;
					$config_res['width'] = 800;
					$config_res['height'] = 400;
					
					//$config_res['new_image'] = 'assets/tour/hotel/logo/new/'.$file_name;
					$this->image_lib->initialize($config_res);
					$this->image_lib->resize();
					$this->image_lib->clear();
				}
				
				$hotel_logo = array(
						'hotel_id' => $id,
						"logo" => $file_name
				);
				$this->Hotel_model->save_logo($id,$hotel_logo);
			}
		

			if($this->input->post('file_index') != ''){
				$file_index = explode(",",$this->input->post('file_index'));
				$max_count = max($file_index);
				$first_key = key($_FILES['gallery']['name']);
				$gal_count = count($_FILES['gallery']['name']);
				$gall = [];
				for($i=$first_key;$i<=$max_count;$i++){
					if($file_index[$first_key] != ''){
						if(in_array($i,$file_index)){
							$gall['gallery'][$i]['name'] = $_FILES['gallery']['name'][$i];
							$gall['gallery'][$i]['type'] = $_FILES['gallery']['type'][$i];
							$gall['gallery'][$i]['tmp_name'] = $_FILES['gallery']['tmp_name'][$i];
							$gall['gallery'][$i]['error'] = $_FILES['gallery']['error'][$i];
							$gall['gallery'][$i]['size'] = $_FILES['gallery']['size'][$i];
						}
					}
				}
				
				$first_key = key($gall['gallery']);
				if($gall['gallery'][$first_key]['name'] != ''){
					$gimage_count = count($gall['gallery']);
					$this->load->library('image_lib');
					for ($g=$first_key;$g<=$max_count;$g++) { 
							if(in_array($g,$file_index)){
								$gname = $gall['gallery'][$g]['name'];
								$gsize = $gall['gallery'][$g]['size'];
								$gtype = $gall['gallery'][$g]['type'];
								$gtemp = $gall['gallery'][$g]['tmp_name'];
								$ext = explode(".", $gname);
								$gimage_name = "gallery-".$gsize.rand(0,5000000).".".$ext[1];
							   
								$config['upload_path'] = REL_IMAGE_UPLOAD_PATH."/hotel/gallery/";
								$config['allowed_types'] = 'gif|jpg|jpeg|png|ico';
								$config['file_name'] = $gimage_name;
								$config['overwrite'] = FALSE;
								$this->load->library('upload', $config);
								$this->upload->initialize($config);
								$_FILES = $gall['gallery'];
								if (!$this->upload->do_upload($g)){
									 $error = array('error' => $this->upload->display_errors());
								}else{
									$data = $this->upload->data();
									$file_name = $data['file_name'];
									$config_res['source_image'] = REL_IMAGE_UPLOAD_PATH.'/hotel/gallery/'.$file_name;
									$config_res['maintain_ratio'] = TRUE;
									$config_res['width'] = 800;
									$config_res['height'] = 400;
									$this->image_lib->initialize($config_res);
									$this->image_lib->resize();
									$this->image_lib->clear();
								}
								if(!isset($error)){
									$gallery_img = array(
													'hotel_id' => $id,
													"gallery_name" => $file_name
													);
							   
							  
									$this->Hotel_model->save_images($gallery_img);
									
								}
								
								
							}
					}
				}
			}
			
			
			
			
			redirect('hotel', 'refresh');           
        }    

        $this->load->model("Package_model");
        $data['country'] = $this->Package_model->get_country();
        $data['hotel'] = $this->Hotel_model->get_hotel_id($id);
		$data['disabled_type'] = '';
		if($data['hotel']->room_types != ''){
			$rm_types = json_decode($data['hotel']->room_types);
			$big_arr = [];
			
			if(!empty($rm_types)){
			foreach($rm_types as $rm){
				$a = explode("--",$rm);
				$aa = [];
				foreach($a as $a1){
					$a1 = explode("->",$a1);
					$aa[] = $a1[1];
				}
				$b = implode(",",$aa);
				
				
				$big_arr[] = $b;
			}
			$data['room_types'] = implode(",",$big_arr);
			}else{
				$data['room_types'] = '';
			}
			
		}else{
			$data['room_types'] = '';
		}
		if($data['room_types'] != ''){
			$check_room_type = $this->check_room_type($id,explode(",",$data['room_types']));
			if($check_room_type == 1)
				$data['disabled_type'] = 'disabled';
			else
				$data['disabled_type'] = '';
		}
        $data['gallery'] = $this->Hotel_model->get_gallery($id);
        $data['id'] = $id;
		$this->load->view('hotel/edit', $data);
    }
	
	function check_room_type($id,$room_type){
		if(!empty($room_type)){
			foreach($room_type as $room){
				$involved_price = $this->Hotel_model->involved_price($id,$room);
				
				if(!empty($involved_price)){
					return 1;
				}else{
					return 0;
				}
			}
		}
	}

     public function duplicate($id){
        $this->load->model("Package_model");
        $data['country'] = $this->Package_model->get_country();
        $data['hotel'] = $this->Hotel_model->get_hotel_id($id);
        $data['price'] = $this->Hotel_model->get_price($id,1);
        $data['extra_price'] = $this->Hotel_model->get_price($id,2);
        $data['gallery'] = $this->Hotel_model->get_gallery($id);
        $data['id'] = $id;
        $this->load->view('hotel/edit', $data);
    }

    public function get_hotel_json(){
        $id = $this->input->post('hotel_id');
        if($id){
            $data['hotel'] = $this->Hotel_model->get_hotel_id($id);
           
			
			$find_room_types = $this->Hotel_model->find_room_types($id);
			if($find_room_types->room_types != ''){
				$find_room_types = json_decode($find_room_types->room_types);
				$final_datas = [];
				
				
				foreach($find_room_types as $d){
					$dn = explode("en->",$d);
					$get_str = explode("--fa->",$dn[1]);
					$get_str = implode("/",$get_str);
					$final_datas[] = $get_str;
				}
				
				
				$data['room_types'] = $final_datas;
			}else{
				$data['room_types'] = '';
			}
			
			
            $this->load->view('hotel/price', $data);
        }
    }
	public function get_hotel_type_json(){
		$hotel_id = $this->input->post('hotel_id');
		$room_type = $this->input->post('room_type');
		
			$data['hotel_id'] = $hotel_id;
			$data['room_type'] = $room_type;
            //pr($data);exit;
			$this->load->view('hotel/price_type', $data);
    }
	

     public function city(){

         $code = $this->input->post('country_id');
         $city = $this->input->post('city_id');
         $selected="";
        if($code){
            $this->load->model("Package_model");
            $city_results = $this->Package_model->get_city($code);
             echo "<select name='hotel[city]' class='form-control select2' data-rule-required='true' >";
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
        if($this->Hotel_model->update_status($id,$data)){
            echo "1";
        } 
        else {
            echo "0";
        }
        
    }

    public function delete($id,$logo){
		$count_tours = $this->check_before_delete($id);
		
		if($count_tours >0){
			$this->session->set_flashdata('message', 'You can not delete this hotel which is associated with tours ');
			redirect('hotel', 'refresh');
		}
		
		$logo = base64_decode($logo);
		$get_images = $this->Hotel_model->get_gallery($id);
		$delete = $this->Hotel_model->delete($id);
		
		if(!empty($get_images)){
			foreach($get_images as $img){
				$img_arr[] = $img->gallery_name;
				@unlink(REL_IMAGE_UPLOAD_PATH."/hotel/gallery/".$img->gallery_name);
			}
		}
        if($delete){
			@unlink(REL_IMAGE_UPLOAD_PATH."/hotel/logo/".$logo);
			$this->session->set_flashdata('message', 'Sucessfully Deleted');
			redirect('hotel', 'refresh');
		}
		
    }

    public function delete_image($id){
       $name=  $this->input->get('name');
       $result = $this->Hotel_model->delete_image($id);  
       if($result){
        unlink("cdn/hotels/gallery/".$name);
        echo "1";
       }
    }
	
	
	public function get_hotel_room_types(){
        $id = $this->input->post('hotel_id');
		$selected_type = $this->input->post('selected_type');
        if($id){
			$data['room_types'] = $find_room_types = $this->Hotel_model->get_room_types($id);
			
			if($find_room_types->room_types != ''){
				$find_room_types = json_decode($find_room_types->room_types);
				$datas = [];
				$en_data = [];
				foreach($find_room_types as $fn){
					$fn = explode("en->",$fn);
					$datas[] = $fn[1];
				}
				foreach($datas as $d){
					$dn = explode("--",$d);
					$en_data[] = $dn[0];
				}
				
				$data['room_types'] = $en_data;
			}else{
				$data['room_types'] = '';
			}
			
			$data['selected_type'] = $selected_type;
            $this->load->view('package/room_types', $data);
        }
    }
	
	public function delete_gallery(){
       $id=  $this->input->post('id');
	   $name = $this->input->post('name');
       $result = $this->Hotel_model->delete_gallery($id);  
	   if($result){
        unlink(REL_IMAGE_UPLOAD_PATH."/hotel/gallery/".$name);
        echo "1";
       }
    }
	
	function check_before_delete($id){
		$count_tours = $this->Hotel_model->count_tours($id);
		if(!empty($count_tours))
			return count($count_tours);
		else
			return 0;
	}

    
}