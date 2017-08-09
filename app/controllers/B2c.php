<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class B2C extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Common_model", "common");
		$this->load->model("Email_model");
		$access = $this->Email_model->get_access();
		$config['protocol'] = $access->smtp;
		$config['smtp_host'] = $access->host;
		$config['smtp_port'] = $access->port;
		$config['smtp_user'] = $access->username;
		$config['smtp_pass'] = $access->password;
		$config['wordwrap']     = FALSE;
		$config['mailtype']     = "html";
		$config['charset']      = "UTF-8";
		$config['crlf']         = "\r\n";
		$config['newline']      = "\r\n";
		$this->load->library("email", $config);
		$this->load->model("Accounts_model", "accounts");
		$this->load->library("General", "general");
		unset_page_cache();
		$this->data["default_language"] = !empty($this->session->userdata('default_language')) ? $this->session->userdata('default_language') : "en";
		$this->data["page_title"] = "Home";
		$this->data["controller"] = $this->router->fetch_class();
		$this->data["method"] = $this->router->fetch_method();
		$this->load->model("B2c_model");
		$this->data["user_id"] = $this->session->userdata(SESSION_PREPEND."id");
		if(!is_null($this->data["user_id"]) && !is_null($this->session->userdata(SESSION_PREPEND."type")))
		{
				$user = $this->B2c_model->is_valid_user("", $this->data["user_id"]);
				$this->data["user_type"] = $user->user_type;
				$this->data["user_type_text"] = "b2c";
				$this->data["user_img"] = $user->image_path;

		}

		$this->session->keep_flashdata('message');
	}

	public function login()
	{

		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg" => $this->lang->line("please_check_entered_details"), "id" => "");
			$this->form_validation->set_rules("email_id", $this->lang->line("email_address"), "trim|required|valid_email");
			$this->form_validation->set_rules("password", $this->lang->line("password"), "trim|required");
			if ($this->form_validation->run() !== false)
			{
				$email_id = $this->input->post("email_id");
				$password = $this->input->post("password");
		      
				$user = $this->B2c_model->is_valid_user($email_id);
				
				if($user !== false)
				{
					$generate = $this->general->validate_pass($user->password,$user->salt,$password);
// echo '<pre>',print_r($generate);exit; 
					if($generate)
					{
						if($user->status === "1" )
						{
							$response["status"] = "true";
							$this->session->set_userdata(SESSION_PREPEND."id", $user->id);
							$this->session->set_userdata(SESSION_PREPEND."type", $user->user_type);
							$this->session->set_userdata(SESSION_PREPEND."email", $user->email_id);
						}
						else
							$response["msg"] = $this->lang->line("account_inactive");
					}
					else
						$response["msg"] = $this->lang->line("mismatch_email_password");
				}
				else
					$response["msg"] = $this->lang->line("email_not_registered");
			}
			echo json_encode($response);exit;
		}
		redirect("", "refresh");
	}

	public function register(){	 
		if($this->input->server("REQUEST_METHOD")=="POST"	){ 
			$this->load->helper('security');
			$this->form_validation->set_rules('password', $this->lang->line("password"), 'trim|required|matches[cpassword]|xss_clean');
			$this->form_validation->set_rules('cpassword', $this->lang->line("confirm_password"), 'trim|required|matches[password]|xss_clean');
			$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'trim|required|valid_email|is_unique[b2c.email_id]|xss_clean');
			$this->form_validation->set_rules('contact_no', $this->lang->line("contact_number"), 'trim|required|xss_clean');
	
			$image_name = "";

			if ($this->form_validation->run() != FALSE)
			{
				//check if the user is already registered
				$is_registered = $this->accounts->is_registered_b2c_email($this->input->post('email'));
				
				if($is_registered)
				{
					$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $this->lang->line("email_already_registered"));
					
					$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
					redirect("b2c/register_view");
				}
				else
				{
					$pass = $this->input->post('password');
		            $generate = $this->general->generate_salt_pwd($pass);

					$user_data = array(
								"user_type" 	=> 4,
								"email_id" 		=> $this->input->post('email'),
								"salt" 			=> $generate['salt'],
								"password"		=> $generate['password'],
								"contact_no"	=> $this->input->post('contact_no'),					
								"register_date" => date('Y-m-d H:i:s'),
								"status"		=> 1,
								"updated_on"	=> date('Y-m-d H:i:s')								
					);
					
					$url = base_url();
					
					$this->db->trans_begin();
					$this->accounts->create_b2c($user_data);
					$user_data = $this->accounts->get_b2c_by_email($this->input->post('email'));
					$user_id = $user_data->id;

		            $sent_mail = $this->b2c_register($user_data,$pass,$url);
					
					$this->session->set_flashdata(SESSION_PREPEND."notification_title", $this->lang->line("b2c_registration"));

					$sent_mail = true;

		            if($sent_mail === true)
		            {
		            	$this->db->trans_commit();

						$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $this->lang->line("register_success"));
						$this->session->set_flashdata(SESSION_PREPEND."notification_status", "success");
					}
					else
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $this->lang->line("register_failed"));
						$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
					}
					
					redirect("b2c/register");
				} 
			}else{

				$this->db->trans_rollback();
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $this->lang->line("register_failed"));
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");

			}
		}
		$data = "";

		$this->load->view("b2c/register", $data);
	}
	

	public function index(){

		$id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}
		$data['user']=$this->B2c_model->get_b2c($id,$this->data['default_language']);
		$data['companions'] = $this->count_companion();
		$this->load->view("b2c/dashboard",$data);
	}

	public function profile(){
		$id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}
		$data['user']=$this->B2c_model->get_b2c($id);
		$this->load->view("b2c/profile",$data);
	}
	
	public function edit_profile($str = 0){
		$id=$this->session->userdata(SESSION_PREPEND."id");
		if(!$id){ redirect();}
        //$id= $this->session_check();
        if($this->input->server("REQUEST_METHOD")=="POST"){ 
        	// echo '<pre>',print_r($this->input->post());exit; 

            $data = array(
                        'firstname'       	=> $this->input->post('user_first_name') , 
                        'lastname'          => $this->input->post('user_last_name') ,
                        'dob'               => date('Y-m-d',strtotime($this->input->post('user_dob'))) , 
                        'contact_no'        => $this->input->post('phone_number') ,
                        'land_num'          => $this->input->post('land_number') ,
                        'address'           => $this->input->post('address') , 
                        'postal_code'       => $this->input->post('postal_code') , 
                        'name_fa'           => $this->input->post('user_name_fa') , 
                        // 'national_id'       => $this->input->post('user_national_id'),
                        // 'passport_no'       => $this->input->post('user_passport_number'),
                        // 'passport_exp_date' => date('Y-m-d',strtotime($this->input->post('user_passport_expire')))
                        );
			$country = $this->input->post('user_nationality') ;  
			if($country == 'IR'){
				$data['national_id']       = $this->input->post('user_national_id');
				$data['passport_no']       = '';
				$data['passport_exp_date'] = '';
			}
			else{
				$data['national_id']       = '';
				$data['passport_no']       = $this->input->post('user_passport_number');
				$data['passport_exp_date'] = date('Y-m-d',strtotime($this->input->post('user_passport_expire')));
			}
          
          	if($country){
            	$data['country'] = $country; 
            }

            /*echo "<pre>";print_r($data);exit();*/

            $update =$this->B2c_model->update_b2c($id,$data); 


            //add new companion
            if(!empty($this->input->post('comp_first_name'))){
            	$comp_first_name = $this->input->post('comp_first_name');
            	$comp_salutation = $this->input->post('comp_salutation');
            	$comp_last_name = $this->input->post('comp_last_name');
            	$comp_name_fa = $this->input->post('comp_name_fa');
            	$comp_nationality = $this->input->post('comp_nationality');
            	$dob = $this->input->post('dob');
            	$comp_id = $this->input->post('insert_update_id');

            	foreach ($comp_first_name as $ckey => $cvalue) {

		            $companion_country = $comp_nationality[$ckey] ;  
		            $d_o_b = ($dob[$ckey] == '' || $dob[$ckey] == '0000-00-00' || $dob[$ckey] == '01-01-1970' || $dob[$ckey] == '1970-01-01')? '' : date("Y-m-d",strtotime($dob[$ckey]));

            		$companionData = array(
            						'salutation' => $comp_salutation[$ckey] ,
            						'fname' => $cvalue,
            						'lname' => $comp_last_name[$ckey],
            						'name_fa' => $comp_name_fa[$ckey],
            						'dob' => $d_o_b,
            						'user_id' => $id,
            						'nationality' => $companion_country
            					 );
            		$companionId = $comp_id[$ckey];

					if($companion_country == 'IR'){
						$companionData['national_id']       = $this->input->post('comp_national_id')[$ckey];
						$companionData['passport_no']       = '';
						$companionData['passport_exp'] 		= '';
					}
					else{
						$companionData['national_id']       = '';
						$companionData['passport_no']       = $this->input->post('comp_passport_number')[$ckey];
						$companionData['passport_exp'] = date('Y-m-d',strtotime($this->input->post('comp_passport_expire')[$ckey]));
					}
					if($cvalue != "" && $companion_country != "" && $companionId==""){
						$updateCompanion = $this->B2c_model->save_companion($companionData);
					}
					elseif($cvalue != "" && $companion_country != "" && $companionId != ""){
						$updateCompanion = $this->B2c_model->update_companion($companionId,$companionData);
						// echo $updateCompanion;exit;
					}
					else{
						// echo '<pre>'.$id;print_r($companionData);exit; 
					}
            	}
            }

            $this->session->set_flashdata('success', $this->lang->line("profile_update_success"));
            //redirect("b2c/edit_profile");
        }
       
		// if($str != 1) $readonly = 'readonly'; else $readonly = '';
		$readonly = '';
		$data['user']    =$this->B2c_model->get_b2c($id);
		$data['country'] =$this->B2c_model->get_country($this->data['default_language']);
		$data['companion_lists'] = $this->B2c_model->companion_list($id);
		$data['readonly'] = $readonly;
		$this->load->view("b2c/edit_profile",$data);
	}
    

   function settings(){
        $id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}
        $data['user'] =$this->B2c_model->get_b2c($id);   
		$this->load->view("b2c/settings",$data);
	}

	function booking_report(){
		$id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}
        $data['user'] =$this->B2c_model->get_b2c($id);   
		$this->load->view("b2c/booking_report",$data);
	}

	function support_ticket(){
		$id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}

        if($this->input->server("REQUEST_METHOD")=="POST"){ 
        	$attachments="";
      
        	if($_FILES['file_name']['name']){
               $config['upload_path'] = 'assets/support_ticket/';
                $config['allowed_types'] = '*';
                $config['max_size'] = '100000';
                $config['max_width']  = '100000';
                $config['max_height']  = '100000';
                $config['overwrite'] = false;
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('file_name')){
                    $s = array('error' => $this->upload->display_errors());
                }
                else {
                    $this->upload->data();

                         $s = array('upload_data' => $this->upload->data());
                  $attachments = base_url().'support_ticket/'.$this->upload->data()['file_name'] ; 
                 
                }
            }
           
        		$data = array(
							'user_id_from'            => $id ,
							'user_type_from'          => 4,
                            'user_type_to'        	  => SUPER_ADMIN_USER ,
							'user_id_to'          	  => SUPER_ADMIN_USER_ID,
                            'subject'             	  => $this->input->post('subject'),
                            'last_reply'              => 'B2C User'
					);

        		$ticket_id = $this->B2c_model->insert_ticket($data); 

        		$data2 = array(
                            'id'                      => $ticket_id,
							'user_id_from'            => $id ,
							'user_type_from'          => 4,
                            'user_type_to'        	  => SUPER_ADMIN_USER ,
							'user_id_to'          	  => SUPER_ADMIN_USER_ID,
							'replied_by'              => 'B2C User', 
                            'message'       		  => $this->input->post('message'),
                            'attachment'        	  => $attachments
					);
    
         $insert =$this->B2c_model->insert_ticket_history($data2); 
        }
        $data['user'] =$this->B2c_model->get_b2c($id);   
		//$data['support']=$this->B2c_model->get_support_ticket($id,4);
		$data['inbox']=$this->B2c_model->get_support_ticket($id,4);
		$data['send_item']=$this->B2c_model->get_send_support_ticket($id,4);
		$data['support_subject']=$this->B2c_model->get_support_subject();
		$data['closed_ticket']=$this->B2c_model->get_closed_support_ticket($id,4);
		$this->load->view("b2c/support_ticket",$data);

	}

	function view_support_ticket($tid){
		$id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}
        if($this->input->server("REQUEST_METHOD")=="POST"){ 

        	$data = array(
							'user_id_from'            => $id ,
							'user_type_from'          => 4,
                            'user_type_to'        	  => SUPER_ADMIN_USER ,
							'user_id_to'          	  => SUPER_ADMIN_USER_ID,
                            'last_reply'              => 'B2C User'
					);

        	 $update_ticket =$this->B2c_model->update_ticket($tid,$data); 
        	 $attachments="";
        	if($_FILES['file_name']['name']){
               $config['upload_path'] = 'admin/upload_files/support_ticket/';
                $config['allowed_types'] = '*';
                $config['max_size'] = '100000';
                $config['max_width']  = '100000';
                $config['max_height']  = '100000';
                $config['overwrite'] = false;
                $config["encrypt_name"] = TRUE;
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('file_name')){
                    $s = array('error' => $this->upload->display_errors());
                }
                else {
                    $this->upload->data();

                         $s = array('upload_data' => $this->upload->data());
                  $attachments = 'support_ticket/'.$this->upload->data()['file_name'] ; 
                 
                }
            }

        	$data2 = array(
                            'id'                      => $tid,
							'user_id_from'            => $id ,
							'user_type_from'          => 4,
                            'user_type_to'        	  => SUPER_ADMIN_USER ,
							'user_id_to'          	  => SUPER_ADMIN_USER_ID,
							'replied_by'              => 'B2C User', 
                            'message'       		  => $this->input->post('message'),
                            'attachment'        	  => $attachments
                      
					);

        	//echo "<pre>";print_r($data2);die;
        	 $insert =$this->B2c_model->insert_ticket_history($data2); 
        	 	if($insert){
        	 		redirect('b2c/view_support_ticket/'.$tid);
        	 	}

        }
        $data['user'] =$this->B2c_model->get_b2c($id);

        $data['ticket'] =$this->B2c_model->get_b2c_history($tid);
        //echo "<prE>";print_r( $data['ticket']); echo "</prE>";
        // /die;
        $data['tid'] = $tid;
        $this->load->view("b2c/view_support_ticket",$data);
    }    

	 function logout(){
		unset($_SESSION["10020_front_id"]);
		unset($_SESSION["10020_front_email"]);
		unset($_SESSION["10020_front_type"]);
		//$this->session->sess_destroy();
		redirect();
	}


     function update_password() {
             $id=$this->session->userdata(SESSION_PREPEND."id");
        	if(!$id){ redirect();}
            if($this->input->server("REQUEST_METHOD")=="POST"){ 
            $message = array();

            $this->form_validation->set_rules('current_password', $this->lang->line("current_password"), 'required|trim|min_length[6]');  
            $this->form_validation->set_rules('new_password', $this->lang->line("new_password"), 'required|trim|min_length[6]');                       
            $this->form_validation->set_rules('confirm_password', $this->lang->line("confirm_password"), 'required|trim|min_length[6]');                       

            if ($this->form_validation->run() == TRUE){  

        		$current_password = $this->input->post('current_password');
            	$user = $this->B2c_model->get_b2c($this->session->userdata(SESSION_PREPEND."id"));

               $user_avli = $this->general->validate_pass($user->password, $user->salt, $current_password);
                
           
                if($user_avli)
                {
                	$pass = $this->input->post('new_password');
                	$generate = $this->general->generate_salt_pwd($pass);
                    $data = array(
                            "salt" 			=> $generate['salt'],
							"password"		=> $generate['password']
                            );

                    
                    $update =$this->B2c_model->update($id,$data); 
                    $message['message'] = $this->lang->line("update_success");
                    $message['result']  = 1;
          
                    
                }
                else {
                    $message['message'] = $this->lang->line("invalid_password");
                     $message['result']  = 0;

                }

            } 
            else {
                    $message['message'] = $this->lang->line("please_check_entered_details");
                    $message['result']  = 0;
            }  
            echo json_encode($message);
        }
    }

    function two_step_verification($status){
            $id=$this->session->userdata(SESSION_PREPEND."id"); 
            $message = array();
             if($status !==""){
            $data = array(
                            'two_step'       => $status
                 );

            $update =$this->B2c_model->update($id,$data);
            $message['message'] = $this->lang->line("update_success");
            $message['result'] = "1";
            echo json_encode($message);
        }
        else {
            $message['message'] = $this->lang->line("cannot_update");
            $message['result'] = "0";
            echo json_encode($message);
        }
    }

     function cancel_account(){
       	$id=$this->session->userdata(SESSION_PREPEND."id");
        if(!$id){ redirect();}
        if($this->input->server("REQUEST_METHOD")=="POST"){ 
            $message = array();
            $this->form_validation->set_rules('acc_password', $this->lang->line("current_password"), 'required|trim');  

            if ($this->form_validation->run() == TRUE){    
                $email_id=$this->session->userdata(SESSION_PREPEND."email");
				$user = $this->B2c_model->is_valid_user($email_id);
				$generate = $this->general->validate_pass($user->password,$user->salt,$this->input->post('acc_password'));
				
				/*$data = array(
                            'id'            => $id , 
                            'password'      => md5($this->input->post('acc_password')) 
                            );
               
                $user_avli =$this->B2c_model->check_user($data); 
				echo '<pre>';
				print_r($this->session->userdata);exit;*/
                if($generate)
                {
                    $data = array(
                            'status'       => 4 
                            );
                    $update =$this->B2c_model->update($id,$data);
                    $message['message'] = $this->lang->line("account_deactivated");
                    $message['result']  = 1;      
                }
                else {
                    $message['message'] = $this->lang->line("invalid_password");
                    $message['result']  = 0;
                }

            } 
            else {
                    $message['message'] = $this->lang->line("please_check_entered_details");
                    $message['result']  = 0;
            }  
            echo json_encode($message);
        }
    }
	/*b2c user forgot password*/
	 function forgot_password(){
		if($this->input->is_ajax_request())
		{
			
			$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'trim|required|valid_email');

			if($this->form_validation->run() != FALSE)
			{
				$email = $this->input->post('email');
				$count = $this->accounts->is_registered_b2c_email($email);
				if($count === true)
				{
					$userInfo = $this->accounts->get_b2c_by_email($email);
					//$data["admin"] = $userInfo;
					if($userInfo->status === "1")
					{
						$this->db->trans_begin();
						try
						{
							
							$verify_code = $this->general->generate_random_key(mt_rand(7, 15));
							$upd_status = $this->B2c_model->update_b2c($userInfo->id, array("key" => base64_encode($this->encrypt->encode($verify_code))));
							if($upd_status !== false)
							{
								
								$data["verify_code"] = $verify_code;
								$data["admin"] = $userInfo;
								
								$msg_sent = $this->Email_model->forgot_password($data);
								//$msg_sent = true;
								if($msg_sent=== true)
								{
									$this->db->trans_commit();
									$response = array(
										'status' => "true",
										'msg' => str_replace("{{email}}", $email, $this->lang->line("reset_password_link_success")),
										'admin' => base64_encode($this->encrypt->encode($userInfo->id))
									);
								}
								else
								{
									$this->db->trans_rollback();
									$response = array(
										'status' => 0,
										'msg' => str_replace("{{email}}", $email, $this->lang->line("reset_password_link_fail"))
									);
								}
							}
							
							
						}
						catch(Exception $ex)
						{
							$this->db->trans_rollback();
							$response["msg"] = "Sorry, Operation failed.";
						}

					}
					else
					{
						$response = array(
										'status' => 0,
										'msg' => $this->lang->line("account_inactive")
									);
					}

				}
				else
				{
					$response = array(
									'status' => 0,
									'msg' => $this->lang->line("email_not_registered")
								);
				}

			}
			else
			{
				$response = array(
								'status' => 0,
								'msg' => $this->lang->line("please_check_entered_details")
							);
			}

			echo json_encode($response);
		}
	}
	
	public function reset_password()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("admin", "Admin", "trim|required");
			$this->form_validation->set_rules("verify", "Code", "trim|required");
			$this->form_validation->set_rules("newpass", "Password", "trim|required|matches[confirmpass]");
			$this->form_validation->set_rules("confirmpass", "Password Confirmation", "trim|required");
			$response["title"] = "Forgot Password - Reset Password";
			if($this->form_validation->run() !== false)
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("admin")));
				if(is_numeric($id) && $id > 0)
				{
					$admin_info = $this->B2c_model->get_b2c_details("", $id);
					$verify_code = $this->encrypt->decode(base64_decode($admin_info->key));
					$code = $this->input->post("verify");
					$newpass = $this->input->post("newpass");
					if(strcmp($code, $verify_code) === 0)
					{
						$new_pass = $this->general->generate_salt_pwd($newpass);
						$result = $this->B2c_model->update_b2c($id, array("password" => $new_pass["password"], "salt" => $new_pass["salt"], "key" => ""));
						if($result !== false)
						{
							$response["status"] = "true";
							$response["msg_status"] = "success";
							$response["msg"] = "Password reset successful. Please login with new password.";
						}
						else
							$response["msg"] = "Password reset unsuccessful. Please try again.";
					}
					else
					{
							$response["msg_status"] = "info";
							$response["msg"] = "Please enter valid verification code.";
					}
				}
				else
					$response["msg"] = "Please enter valid verification code.";
			}
			else
				$response["msg"] = "Please enter valid verification code.";
		}
		echo json_encode($response);exit;
	}

	/*mail verification link for new b2c user*/
	 function send_verification_mail($b2c_id, $email_type, $password) {
		$this->load->library("Send_emails");
		$data['user_data'] = $user_data = $this->accounts->get_b2c_by_id($b2c_id);
		$data['password'] = $password;        
		$data['email_template'] = $this->email_template->get_email_template($email_type);
		$email = $user_data->email;
		$key = $this->general->generate_random_key();
		$secret = md5($this->encrypt->encode($email));
		$this->db->trans_begin();
		$this->accounts->update_b2c_pwd_reset($b2c_id, $key, $secret);
		$data['confirm_link'] = base_url('verification/b2c_email/'.$key.'/'.$secret);
		$response = $this->send_emails->registered_mail($data);
		if($response === true)
		{
			$this->db->trans_commit();
			return $response;
		}
		else
		{
			$this->db->trans_rollback();
			return $response;
		}

	}
	
	public function b2c_register($data,$pwd,$url)
	{
		$data = (array)$data;
		//pr($data);exit;
		
		$template = $this->Email_model->get_template("", "b2c_registration_confirmed");

		if($template !== false)
		{
			$to_email = $data["email_id"];
			
			$message = $template->message;
			$message = str_replace("{%%TITLE%%}", PROJECT_NAME, $message);
			$message = str_replace("{%%SALUTATION%%}", $data["email_id"], $message);
			$message = str_replace("{%%USERNAME%%}", $to_email, $message);
			$message = str_replace("{%%PASSWORD%%}", $pwd, $message);
			$message = str_replace("{%%BASE_URL%%}", base_url(), $message);
			$message = str_replace("{%%UPLOAD_URL%%}", upload_url(), $message);
			$message = str_replace("{%%LOGIN_URL%%}", $url, $message);
			$message = str_replace("datasrc=", "src=", $message);
			//pr($message);exit;
			$subject = str_replace("{%%TITLE%%}", PROJECT_NAME, $template->subject);
			$email_from = $template->email_from;
			$email_from_name = $template->email_from_name;
			$to_bcc = explode(",", $template->to_email);
			//pr($to_email);exit;
			return $this->send_mail($subject, $message, $email_from, $email_from_name, $to_email, $to_bcc);
		}
		else
			return "TNF";
	}
	
	function send_mail($subject, $msg, $from, $from_name, $to, $bcc, $attchments = null)
	{
		$this->email->from($from, $from_name);
		$this->email->to($to);
		if(!empty($bcc))
			$this->email->bcc($bcc);
		$this->email->subject($subject);
		$this->email->message($msg);
		if(!is_null($attchments))
			foreach ($attchments as $attchment)
				$this->email->attach($attchment);
		if($this->email->send())
			return true;
		else
			//echo $this->email->print_debugger();exit;
		return false;
	}

	/*forget password mail for b2c user*/
	 function get_mail_content_forgotpass($email) {
		$this->load->library("Send_emails");
		$data['user_data'] = $this->accounts->get_b2c_by_email($email);
		$email_type = 'Forgot Password';
		$data['email_template'] = $this->email_template->get_email_template($email_type);

		$key = $this->common->generate_random_key();
		$secret = md5($this->encrypt->encode($email));
		$user_id = $data['user_data']->id;
		$this->db->trans_begin();
		$this->accounts->update_b2c_pwd_reset($user_id, $key, $secret);
		$data['reset_link'] = base_url('b2c/set_password/'.$key.'/'.$secret);
		$response = $this->send_emails->forgot_password_mail($data);
		if($response === true)
		{
			$this->db->trans_commit();
			return $response;
		}
		else
		{
			$this->db->trans_rollback();
			return $response;
		}
	}
	
	function add_companion(){
		$com = $this->input->post("companion");
		// echo '<pre>',print_r($com);exit; 
		if(!empty($com)){
			$data['salutation'] = $com['salutation'];
			$data['fname'] = $com['fname'];
			$data['lname'] = $com['lname'];
			$data['dob'] = date('Y-m-d',strtotime($com['dob']));
			$data['user_id'] = $com['user_id'];
			$data['name_fa'] = $com['name_fa'];
			$data['nationality'] = $com['nationality'];

			if($data['nationality'] == 'IR'){
				$data['national_id']       = $com['national_id'];
				$data['passport_no']       = '';
				$data['passport_exp'] = '';
			}
			else{
				$data['national_id']       = '';
				$data['passport_no']       = $com['passport_no'];
				$data['passport_exp'] = date('Y-m-d',strtotime($com['passport_exp']));
			}

			if($com['id'] == ''){
				echo $save_companion = $this->B2c_model->save_companion($data);
			}else{
				echo $update =$this->B2c_model->update_companion($com['id'],$data); 
			}
			exit;
			
		}
		
	}
	
	function companion_list(){
		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0){
			$user_id = $this->data['user_id'];
			$companion_lists = $this->B2c_model->companion_list($user_id);
			if(!empty($companion_lists)){
				echo '<table class="table table-bordered table-striped com_table">
				<thead>
				<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>National ID</th>
				<th>Passport No</th>
				<th>Passport Expire</th>
				<th>#</th>
				</tr>
				</thead>
				<tbody>';
				foreach($companion_lists as $com){
					$nat_id       = ($com->national_id == "")? 'NA' : $com->national_id;
					$passport_no  = ($com->passport_no == "")? 'NA' : $com->passport_no;
					$passport_exp = ($com->passport_exp == "" || $com->passport_exp == "0000-00-00")? 'NA' : $com->passport_exp;
					echo '<tr id=row_'.$com->id.'>';
					echo '<td>'.$com->fname.'</td>';
					echo '<td>'.$com->lname.'</td>';
					echo '<td>'.$nat_id.'</td>';
					echo '<td>'.$passport_no.'</td>';
					echo '<td>'.$passport_exp.'</td>';
					echo '<td><i data-action="edit" data-id='.$com->id.' class="fa fa-pencil-square-o action_companion" aria-hidden="true"></i> &nbsp;&nbsp;<i data-action="delete" data-id='.$com->id.' class="fa fa-trash-o action_companion" aria-hidden="true"></i></td>';
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
			}
		}
		
	}
	
	function companion_by_term(){
		$term = $_GET['term'];
		if($this->data['user_id'] != '')
			$user_id = $this->data['user_id'];
		else
			return false;
		$companion_lists = $this->B2c_model->companion_by_term($user_id,$term);
		
		
		if($companion_lists){
			$response= [];
			foreach ($companion_lists as $rows) {
				
				$response[] = array(
                        'value' => $rows->fname,
						'salutation' => $rows->salutation,
						'lname' => $rows->lname,
						'dob' => $rows->dob,
						'passport_no' => $rows->passport_no,
						'passport_exp' => $rows->passport_exp
						
                    );
			}
			echo json_encode($response);
		}	
		exit;	
		
	}
	
	function companion_by_id(){
		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0){
			$id = $this->input->post("id");
			$companion = $this->B2c_model->companion_by_id($id);
			echo json_encode($companion);exit;
			
		}
		
	}
	
	public function delete_companion(){
		if($this->input->is_ajax_request() === true && count($this->input->post()) > 0){
		   $id=  $this->input->post('id');
		   $result = $this->B2c_model->delete_companion($id);  
		   if($result){
			echo $result;
		   }
		}
    }
	
	function count_companion(){
		
			$user_id = $this->data['user_id'];
			$count_companion = $this->B2c_model->count_companion($user_id);
			if($this->input->is_ajax_request() === true && count($this->input->post()) > 0){
				echo $count_companion;
			}else{
				return $count_companion;
			}
				
			exit;
			
	}

	public function get_salutation_html(){
		$required_star = $this->lang->line("required_star");
		$title = $this->lang->line("title");
		$mr = $this->lang->line("mr");
		$mrs = $this->lang->line("mrs");
		$miss = $this->lang->line("miss");

		$html = '<select style="width:100% !important;" data-rule-required="true" data-msg-required="'.$required_star.'" name="comp_salutation[]" title="'.$title.'" class="select2" >
                      <option value="0">'.$mr.'</option>
                      <option value="1">'.$mrs.'</option>
                      <option value="3">'.$miss.'</option>
                    </select>';

        return $html;
	}



}
