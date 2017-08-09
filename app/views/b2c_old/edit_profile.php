<!DOCTYPE html>
<html class="no-js">
<?php 
  $include['css'][] = "select2/select2";
  $this->load->view("common/head",$include);
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
        <?php if( $this->session->flashdata('success')) {?>
        <div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-top:10px;">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">×</span></button> 

        <?php echo $this->session->flashdata('success');?>
        </div>
        <?php  } ?>

          <div class="profile-content">
            <div class="personal_info">
              <div class="mn-edit-tab">
                <h2><?php echo $this->lang->line("user_profile_personal_information"); ?></h2>
                
              </div>

              <form method="post" class="user_edit"  action="<?php echo base_url('b2c/edit_profile')?>" enctype="multipart/form-data">
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_first_name_colan"); ?></label>
                    <input <?php echo $readonly;?> type="text" class="form-control" name="first_name" value="<?php echo $user->firstname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" data-msg-required="Please enter firstname." data-msg-pattern="Please enter alphabetical name.">
                  </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_last_name_colan"); ?></label>
                    <input  <?php echo $readonly;?> type="text" class="form-control"name="last_name"  value="<?php echo $user->lastname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" data-msg-required="Please enter lastname." data-msg-pattern="Please enter alphabetical name.">
                  </div>
                </div>

                

                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_date_of_birth_colan"); ?></label>
                    <input  <?php echo $readonly;?> type="date" class="form-control" id="dob" name="dob" value="<?php if($user->dob=="0000-00-00"){ echo ""; } else {echo  $user->dob; } ?>">
                  </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_email_colan"); ?></label>
                    <input type="text" class="form-control" value="<?php echo $user->email_id; ?>" readonly>
                  </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("user_profile_phone_number_colan"); ?></label>
                    <input  <?php echo $readonly;?> id="validation_contact_b2c" data-rule-required="true" type="text" class="form-control" name="mobile" value="<?php echo $user->contact_no; ?>">
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
                        <?php if($user->country_code == '') $country_code = ''; else $country_code = $user->country_code;?>
                        <label><?php echo $this->lang->line("user_profile_country_colan");?></label>                       
                         
                         <select <?php  if ($readonly != '') echo 'disabled';?> class="select2 form-control set_country"  name="country" id="country" hyperlink="<?php echo $country_code;?>" data-rule-required="false" >
                         </select>    
                      </div>
                    </div>
                    
                    <input class="form-control valid" type="hidden" value="<?php echo $country_code; ?>" id="code_country">
                    <?php
                     
                      if($country_code != 'IR') $style="display:none;"; else $style = '';
                    ?>
                    
                 <div class="col-md-4 col-sm-6 col-xs-12 national_id" style="<?php echo $style;?>">
                    <div class="reginput">
                      <label>National Code</label>
                      <input  <?php echo $readonly;?> type="text" class="form-control" name="national_id" value="<?php echo $user->national_id; ?>">
                    </div>
                  </div>
        
                    

                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label>Postal Code</label>
                      <input  <?php echo $readonly;?> type="text" class="form-control" name="postal_code" value="<?php echo $user->postal_code; ?>">
                    </div>
                  </div>

                  <!--<div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_where_you_live_colan"); ?></label>
                      <input type="text" class="form-control" name="current_location" value="<?php echo $user->current_location; ?>">
                    </div>
                  </div>-->

                  <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_address_colan"); ?></label>
                      <textarea <?php echo $readonly;?>  placeholder="" name="address" class="form-control mn-height"><?php echo $user->address; ?></textarea>
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
                  
                  
              <!--    <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_nationality_colan"); ?></label>
                      <input type="text" class="form-control" name="national" value="<?php echo $user->country; ?>">
                    </div>
                  </div>-->
                  
                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_passport_number_colan"); ?></label>
                      <input <?php echo $readonly;?> type="text" class="form-control" name="passport_no" value="<?php echo $user->passport_no; ?>">
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_passport_exp_date"); ?></label>
                      <input <?php echo $readonly;?> type="date" class="form-control" id="passport_exp" name="passport_exp" value="<?php if($user->passport_exp_date=="0000-00-00"){ echo ""; } else {echo  $user->passport_exp_date; } ?>">
                    </div>
                  </div>

                  <!--<div class="col-md-4 col-sm-6 col-xs-12"> 
                      <?php  if($user->passport_image) {  $passport = base_url('assets/user_profile/'.$user->passport_image) ; } else{ $passport = ""; }?> 
                        <?php if($passport){?>
                      <div class="col-md-4 col-xs-4 nopadding">          
                        <div class="reginput">
                          <img width="80" height="70" src="<?php echo $passport;?>" alt="Passport Image">            
                        </div>
                      </div>
                       <?php } ?>
                      <div class="col-md-8 col-xs-4 nopadding"><br><br>
                        <input type="file" name="passport_image" class="form-control logpadding mn-padding">
                        <input type="hidden" name="old_passport_image" value="<?php echo $user->passport_image;?>">
                      </div>
                  </div>          -->
              </div>
            </div>

            <?php if($readonly == '') { ?>
            <div class="col-xs-12">
              <div class="reginput">
                <input type="submit" class="btn-2 pull-right" value="<?php echo $this->lang->line("user_profile_save_information"); ?>">
              </div>
            </div>
            <?php } ?>
            
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php  $this->load->view('common/footer');?>
  <div class="clearfix"></div>
</div>
</div>
</div>
</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>