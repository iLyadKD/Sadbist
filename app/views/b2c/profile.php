<!DOCTYPE html>
<html class="no-js">
  <?php
  $this->load->view("common/head");
  ?>
<body>
<div id="wrapper">
<?php
  $this->load->view("common/header");
  $this->load->view("common/notification");
?> 
<section class="mn-reg">
    
      <?php $this->load->view("b2c/menu"); ?>
        	
        <div class="clearfix"></div>
      <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="profile-content">
            <div class="personal_info">
              <div class="mn-edit-tab">
                <h2><?php echo $this->lang->line("my_profile_caps"); ?></h2>
                <p><a href="<?php echo base_url('b2c/edit_profile') ?>"><i class="fa fa-edit"></i></a></p>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="reginput">
                  <label><?php echo $this->lang->line("user_profile_first_name_colan"); ?></label>
                  <input type="text" class="input-1" readonly="" value="<?php echo $user->firstname; ?>">
                </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="reginput">
                  <label><?php echo $this->lang->line("user_profile_last_name_colan"); ?></label>
             
                  <input type="text" class="input-1" readonly="" value="<?php echo $user->lastname; ?>">
                </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="reginput">
                  <label><?php echo $this->lang->line("user_profile_i_am_colan"); ?></label>
                      <?php  if($user->general=="1"){
                              $gender ="Male";
                            } elseif($user->general=="2"){
                            $gender = "Female";
                      } ?>
                  <input type="text" class="input-1" readonly="" value="<?php echo $gender; ?>">
                </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="reginput">
                  <label><?php echo $this->lang->line("user_profile_date_of_birth_colan"); ?></label>
                  <input type="text" class="input-1" readonly="" value="<?php echo $user->dob; ?>">
                </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="reginput">
                  <label><?php echo $this->lang->line("user_profile_email_colan"); ?></label>
                  <input type="text" class="input-1" readonly="" value="<?php echo $user->email_id; ?>">
                </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="reginput">
                  <label><?php echo $this->lang->line("user_profile_phone_number_colan"); ?></label>
                  <input type="text" class="input-1" readonly="" value="<?php echo $user->contact_no; ?>">
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12 nopadding">
              <div class="contact_info">
                <div class="mn-edit-tab">
                  <h2><?php echo $this->lang->line("user_profile_contact_information"); ?></h2>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_where_you_live_colan"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo $user->address; ?>">
                  </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_city_colan"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo $user->city; ?>">
                  </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_state_colan"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo $user->state; ?>">
                  </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_country_colan"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo $user->country; ?>">
                  </div>
                </div>
            

                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="col-md-5 col-xs-4 nopadding">
                      <?php  if($user->image_path) {$image = base_url('assets/user_profile/'.$user->image_path) ; } else{ $image = base_url('assets/user_profile/2_3-Dining-Header.jpg'); }?> 
                      <div class="rowput"> <img width="80" height="70" src="<?php echo $image;?>" alt="Profile Image"> </div>
                    </div>
                  </div>

                          

                <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_address_colan"); ?></label>
                    <textarea readonly="" placeholder="" value="<?php echo $user->address; ?>" name="address" class="input-1 mn-height"></textarea>
                  </div>
                </div>
           

              </div>
            </div>


             <div class="clearfix"></div>
            <div class="col-xs-12 nopadding">
              <div class="contact_info">
                <div class="mn-edit-tab">
                  <h2><?php echo $this->lang->line("user_profile_passport_information"); ?></h2>
                </div>
                
                
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_nationality_colan"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo $user->nationality; ?>">
                  </div>
                </div>
                
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_passport_number_colan"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo @$user->passport_no; ?>">
                  </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_passport_exp_date"); ?></label>
                    <input type="text" class="input-1" readonly="" value="<?php echo $user->passport_exp_date; ?>">
                  </div>
                </div>

                 <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="col-md-5 col-xs-4 nopadding">
                      <?php  if($user->passport_image) {$image = base_url('assets/user_profile/'.$user->passport_image) ; } else{ $image = base_url('assets/user_profile/2_3-Dining-Header.jpg'); }?> 
                      <div class="rowput"> <img width="80" height="70" src="<?php echo $image;?>" alt="Profile Image1"> </div>
                    </div>
                  </div>
                
              
            </div>
           
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php   $this->load->view('common/footer');?>
</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>