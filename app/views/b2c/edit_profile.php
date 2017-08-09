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

  //variables used in custom.js
  // $required_star = $this->lang->line("required_star");
  // $js_title      = $this->lang->line("title");
 
?>

<section class="mn-reg">
    
    <?//php $this->load->view("b2c/menu"); ?>
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
          <div class="edit_pro_btns">
            <div class="col-sm-6 nopadding">
           
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro" id="save_prof"><?php echo $this->lang->line("save_profile"); ?></button>
              </div>
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro" data-toggle="modal" data-target="#myModal_1"><?php echo $this->lang->line("change_password"); ?></button>
              </div>
              <!--<div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro"  data-toggle="modal" data-target="#myModal_2">Delete Account</button>
              </div>-->
            </div>
            <div class="col-sm-6 nopadding text-right">
             <!--  <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro compList" data-id="list"><a href="javascript:void(0)">Companions </a></button>
              </div> -->
              <!-- <div class="col-sm-3 mnopad">
                <a class="btn btn_editpro" href="<?php echo base_url('b2c/booking_report.html')?>">Report</a>
              </div> -->
            </div>
          </div>

          <div class="profile-content b2c_profile_div">
          
             <form formnovalidate name="b2c_profile" method="post" class="user_edit" id="profile_form_id"  action="<?php echo base_url('b2c/edit_profile')?>" enctype="multipart/form-data">
            <div class="col-xs-12 nopadding">
                <div class="contact_info">
                  <div class="mn-edit-tab">
                    <h2><?php echo $this->lang->line("contact_information"); ?></h2>
                      <!-- <h2 class="pull-right"><a href="<?php echo base_url('b2c/edit_profile/1')?>"><?php echo $this->lang->line("edit_profile"); ?></a></h2> -->
                      <div> 
                      <div class="companion">
                        <i class="fa fa-user fa-2x" aria-hidden="true"></i>
                      </div>   
                  </div>      
                  </div>
                    <div class="col-sm-7 nopadding">
                    <div class="col-md-4 col-sm-6 col-xs-12 mnopad">
                      <div class="reginput">
                        <label class="required"><?php echo $this->lang->line('email_address'); ?></label>                       
                         <input type="text" name="email_addr" class="inputs_group"  pattern="Enter Your Email Id" placeholder="<?php echo $this->lang->line('email_address'); ?>" value="<?php echo $user->email_id; ?>" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" data-rule-email="true"> 
                      </div>
                    </div>
               
                  <div class="col-md-4 col-sm-6 col-xs-12 mnopad">
                    <div class="reginput">
                      <label class="required"><?php echo $this->lang->line('contact_number'); ?></label>
                      <input  <?php echo $readonly;?> placeholder="Ex : +98-912-588-7875" type="text" class="inputs_group" name="phone_number" value="<?php echo $user->contact_no; ?>">
                    </div>
                  </div>


                  <div class="col-md-4 col-sm-12 col-xs-12 mnopad">
                    <div class="reginput">
                      <label><?php echo $this->lang->line('landline_number'); ?></label>
                      <input  <?php echo $readonly;?> placeholder="Land +98-21-588-7875" type="text" class="inputs_group" name="land_number" value="<?php echo $user->land_num; ?>">
                    </div>
                  </div>

                  
                 </div>

                


                </div>
              </div>
              <div class="col-md-7 col-sm-12 col-xs-12 mnopad">
                <div class="reginput">
                  <label><?php echo $this->lang->line("address_colan_off"); ?></label>
                  <textarea <?php echo $readonly;?>  placeholder="<?php echo $this->lang->line('address_colan_off'); ?>" name="address" class="inputs_group mn-height" style="resize:none !important;height:auto;" rows="2"><?php echo $user->address; ?></textarea>
                </div>
              </div>
              <div class="col-sm-5">
                 <div class="col-md-4 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <label><?php echo $this->lang->line("postal_code"); ?></label>
                    <input  <?php echo $readonly;?> placeholder="<?php echo $this->lang->line("postal_code"); ?>" type="text" class="inputs_group" name="postal_code" value="<?php echo $user->postal_code; ?>">
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>

            <div class="personal_info user_info_div" id="personal_info">
                
              <div class="mn-edit-tab">
                <h2><?php echo $this->lang->line("personal_information"); ?></h2>
                <?php if($readonly === "readonly")
                {
                  $readonly = "disabled";
                ?>
                  
                <?php
                }
                ?>
              </div>
 
              <div class="col-sm-5 nopadding">

                <div class="col-md-2 col-sm-2 mnopad">
                  <div class="form-group inputtext reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="user_salutation" title="<?php echo $this->lang->line("title"); ?>" class="select2" id="user_salutation" >
                      <option <?php echo $salutation === "mr" ? "selected" : ""; ?> value="0"><?php echo $this->lang->line("mr");
                      ?></option>
                      <option <?php echo $salutation === "mrs" ? "selected" : ""; ?> value="1"><?php echo $this->lang->line("mrs");
                      ?></option>
                      <option <?php echo $salutation === "miss" ? "selected" : ""; ?> value="3"><?php echo $this->lang->line("miss");
                      ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input <?php echo $readonly;?> type="text" class="inputs_group" name="user_first_name" value="<?php echo $user->firstname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("first_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input  <?php echo $readonly;?> type="text" class="inputs_group" name="user_last_name"  value="<?php echo $user->lastname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("last_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-4 col-sm-4 mnopad">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="user_name_fa" class="inputs_group par_rtl" placeholder="<?php echo $this->lang->line("user_name"); ?>" title="<?php echo $this->lang->line("user_name"); ?>" id="user_name_fa" value="<?php  if($_SESSION['default_language']=='en'){echo $user->name; } else{echo $user->name_fa;} ?>">
                  </div>
                </div>

                </div>

                <div class="col-sm-7 nopadding">
                <div class="col-md-3 col-sm-3 mnopad">
                  <div class="reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="user_nationality" class="set_country select2" id="user_nationality" data-href="<?php echo ($user->country_code != "")?$user->country_code:'IR'; ?>" >
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad national_id">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="user_national_id" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="user_national_id" value="<?php echo $user->national_id; ?>">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input   type="text" class="inputs_group dob_prof" id="user_dob" name="user_dob" value="<?php if($user->dob=="0000-00-00"){ echo ""; } else { echo  date("d-m-Y",strtotime($user->dob)); } ?>" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_num">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="user_passport_number" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="user_passport_number"  data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>" value="<?php echo $user->passport_no; ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_exp">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="user_passport_expire" placeholder="<?php echo $this->lang->line('passport_expiry_date'); ?>" title="<?php echo $this->lang->line('passport_expiry_date'); ?>" class="inputs_group from_current_date" id="user_passport_expire" value="<?php echo date("d-m-Y",strtotime($user->passport_exp_date)); ?>">
                  </div>
                </div>

                

                </div>

              </div>
              <div class="clearfix"></div>
        
              
              
              <div class="companions-container">  
              <div class="mn-edit-tab">
                <h2><?php echo $this->lang->line("companion"); ?></h2>
                <?php if($readonly === "readonly")
                {
                  $readonly = "disabled";
                ?>
                  
                <?php
                }
                ?>
              </div>

              <?php foreach ($companion_lists as $ckey => $cvalue): ?>

              <div class="personal_info user_info_div" id="companion_<?php echo $ckey; ?>">
               <div class="col-sm-5 nopadding">

                <div class="col-md-2 col-sm-2 mnopad">
                  <div class="form-group inputtext reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" >
                      <option <?php echo ($cvalue->salutation == 0)? 'selected' : ''; ?> value="0"><?php echo $this->lang->line("mr");
                      ?></option>
                      <option <?php echo ($cvalue->salutation == 1)? 'selected' : ''; ?>  value="1"><?php echo $this->lang->line("mrs");
                      ?></option>
                      <option <?php echo ($cvalue->salutation == 3)? 'selected' : ''; ?>  value="3"><?php echo $this->lang->line("miss");
                      ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input type="text" class="inputs_group" name="comp_first_name[]" value="<?php echo $cvalue->fname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("first_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input   type="text" class="inputs_group" name="comp_last_name[]"  value="<?php echo $cvalue->lname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("last_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-4 col-sm-4 mnopad">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_name_fa[]" class="inputs_group par_rtl" placeholder="<?php echo $this->lang->line("company_name"); ?>" title="<?php echo $this->lang->line("company_name"); ?>" value="<?php if($_SESSION['default_language']=='en'){echo $cvalue->name;}else{echo $cvalue->name_fa;} ?>">   
                  </div>
                </div>

                </div>

                <div class="col-sm-7 nopadding">
                <div class="col-md-2 col-sm-3 mnopad">
                  <div class="reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_nationality[]" class="set_country select2"  data-href="<?php echo $cvalue->nationality; ?>" >
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad national_id">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="comp_national_id[]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>"  value="<?php echo $cvalue->national_id; ?>">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input   type="text" class="inputs_group dob_prof"  name="dob[]" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" value="<?php echo date('d-m-Y',strtotime($cvalue->dob)); ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_num">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_passport_number[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group"   data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>" value="<?php echo $cvalue->passport_no; ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_exp">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_passport_expire[]" placeholder="<?php echo $this->lang->line('passport_expiry_date'); ?>" title="<?php echo $this->lang->line('passport_expiry_date'); ?>" class="inputs_group from_current_date" value="<?php echo ($cvalue->passport_exp == '' || $cvalue->passport_exp == '0000-00-00')? '' :date('d-m-Y',strtotime($cvalue->passport_exp)); ?>">
                  </div>
                </div>
                <div>
                  <input type="hidden" name="insert_update_id[]" value="<?php echo $cvalue->id; ?>"/>
                </div>
                <div class="col-md-2 col-xs-1 mefullwd nopadadd mleft text-right">
                  <div class="addflight delete_companion" data-id="<?php echo $cvalue->id; ?>" ><span class="fa fa-minus"></span></div>
                  <!-- <div class="addflight add_new_companion"><span class="fa fa-plus"></span></div> -->
                </div>

                

                </div>
                </div>
              <?php endforeach;?>

              <div class="personal_info user_info_div_comp" id="companion_new_1">
               <div class="col-sm-5 nopadding">

                <div class="col-md-2 col-sm-2 mnopad salutation">
                  <div class="form-group inputtext reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" >
                      <option  value="0"><?php echo $this->lang->line("mr");
                      ?></option>
                      <option  value="1"><?php echo $this->lang->line("mrs");
                      ?></option>
                      <option  value="3"><?php echo $this->lang->line("miss");
                      ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mnopad fname">
                  <div class="reginput">
                    <input type="text" class="inputs_group" name="comp_first_name[]" value="" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("first_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12 mnopad lname">
                  <div class="reginput">
                    <input   type="text" class="inputs_group" name="comp_last_name[]"  value="" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("last_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-4 col-sm-4 mnopad fa_name">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_name_fa[]" class="inputs_group par_rtl" placeholder="<?php echo $this->lang->line("company_name"); ?>" title="<?php echo $this->lang->line("company_name"); ?>" >
                  </div>
                </div>

                </div>

                <div class="col-sm-7 nopadding">
                <div class="col-md-2 col-sm-3 mnopad nationality">
                  <div class="reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_nationality[]" class="set_country select2"  data-href="IR" >
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad national_id">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="comp_national_id[]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>"  >
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input   type="text" class="inputs_group dob_prof"  name="dob[]" value="" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_num">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_passport_number[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group"   data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>" >
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_exp">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_passport_expire[]" placeholder="<?php echo $this->lang->line('passport_expiry_date'); ?>" title="<?php echo $this->lang->line('passport_expiry_date'); ?>" class="inputs_group from_current_date" id="user_passport_expire" value="<?php echo date("d-m-Y",strtotime($user->passport_expiry_date)); ?>">
                  </div>
                </div>
                <div>
                  <input type="hidden" name="insert_update_id[]" value=""/>
                </div>
                <div class="col-md-2 col-xs-1 mefullwd nopadadd mleft text-right">
                  <div class="addflight remove_new_companion"><span class="fa fa-minus"></span></div>
                  <div class="addflight add_new_companion"><span class="fa fa-plus"></span></div>
                </div>

                

                </div>
                </div>

                </div>
                <!-- /companions container -->

               <div class="clearfix"></div>
               <div class="col-xs-12 nopadding"></div>
     
            </form>
           
          </div>

          <!-- Dummy DIV -->
            <div class="personal_info user_info_div_comp" id="companion_new_dummy" style="display:none">
               <div class="col-sm-5 nopadding">

                <div class="col-md-2 col-sm-2 mnopad salutation">
                  <div class="form-group inputtext reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="" >
                      <option  value="0"><?php echo $this->lang->line("mr");
                      ?></option>
                      <option  value="1"><?php echo $this->lang->line("mrs");
                      ?></option>
                      <option  value="3"><?php echo $this->lang->line("miss");
                      ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 mnopad fname">
                  <div class="reginput">
                    <input type="text" class="inputs_group" name="comp_first_name[]" value="" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("first_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12 mnopad lname">
                  <div class="reginput">
                    <input   type="text" class="inputs_group" name="comp_last_name[]"  value="" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("last_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-4 col-sm-4 mnopad fa_name">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_name_fa[]" class="inputs_group par_rtl" placeholder="<?php echo $this->lang->line("company_name"); ?>" title="<?php echo $this->lang->line("company_name"); ?>" >
                  </div>
                </div>

                </div>

                <div class="col-sm-7 nopadding">
                <div class="col-md-2 col-sm-3 mnopad nationality">
                  <div class="reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_nationality[]" class="set_country select2"  data-href="IR" >
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad national_id">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="comp_national_id[]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>"  >
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <input   type="text" class="inputs_group dob_prof"  name="dob[]" value="" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_num">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_passport_number[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group"   data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>" >
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad passport_exp">
                  <div class="reginput">
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="comp_passport_expire[]" placeholder="<?php echo $this->lang->line('passport_expiry_date'); ?>" title="<?php echo $this->lang->line('passport_expiry_date'); ?>" class="inputs_group from_current_date">
                  </div>
                </div>
                <div>
                  <input type="hidden" name="insert_update_id[]" value=""/>
                </div>
                <div class="col-md-2 col-xs-1 mefullwd nopadadd mleft text-right">
                  <div class="addflight add_new_companion"><span class="fa fa-minus"></span></div>
                  <div class="addflight add_new_companion"><span class="fa fa-plus"></span></div>
                </div>

                

                </div>
                </div>
          <!-- /Dummy DIV -->
             
        </div>
          
      </div>
    </div>
  </section>
  <?php
    $this->load->view('common/footer');
    // $this->load->view('common/pop-ups');
  ?>
  <div class="clearfix"></div>
</div>
</div>
</div>



<!-- change password modal -->
<div class="modal fade" id="myModal_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 id="myModalLabel" class="modal-title"><?php echo $this->lang->line("change_password"); ?></h4>
      </div>
      <!-- /.modal-header -->
      <div id="message"></div>
      <form role="form" method="post" name="change_password"  action="<?php echo base_url('b2c/update_password')?>" id="change_password">  
            <div class="modal-body">
        
          <div class="form-group">
            <div class="reginput">
              <input type="password" name="current_password" placeholder="<?php echo $this->lang->line("current_password"); ?>" class="input-1" >
            </div>
          </div>
          <!-- /.form-group -->
          
          <div class="form-group">
            <div class="reginput">
              <input type="password" name="new_password" id="new_password" placeholder="<?php echo $this->lang->line("new_password"); ?>" class="input-1">
            </div>
            <!-- /.input-group --> 
          </div>
          <!-- /.form-group -->
          
          <div class="form-group">
            <div class="reginput">
              <input type="password" name="confirm_password" placeholder="<?php echo $this->lang->line("confirm_password"); ?>" class="input-1">
            </div>
            <!-- /.input-group --> 
          </div>
          <!-- /.form-group -->
          <!-- /.checkbox -->
      </div>
      <!-- /.modal-body -->
      
      <div class="modal-footer">
        <button class="btn-1 mn-tp" type="submit"><?php echo $this->lang->line("update_password"); ?></button>
       
      </div>
      <!-- /.modal-footer --> 
        </form>
    </div>
  </div>
</div>

<!-- cancel account modal -->
<div class="modal fade" id="myModal_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 id="myModalLabel" class="modal-title"><?php echo $this->lang->line("cancel_account"); ?></h4>
      </div>
      <form name="cancel_account" id="cancel_account" method="post" action="<?php echo base_url('user/cancel_account');?>" >
      <div class="modal-body">
        <div id="confirm_message"></div>
         <div class="form-group">
            <div class="reginput">
              <input type="password" placeholder="<?php echo $this->lang->line("current_password");?>" name="acc_password" class="input-1" value="">
            </div>
          </div>
         <p> <?php echo $this->lang->line("cancel_account_description1");?></p>
      </div>
      <!-- /.modal-body -->
      
      <div class="modal-footer">
        <input type="submit" class="searchbtn-1 pull" id="confirm" value="<?php echo $this->lang->line("confirm");?>"/>
        <button class="searchbtn-2 pull-right "  data-dismiss="modal"  ><?php echo $this->lang->line("cancel");?></button>
      </div>
      <!-- /.modal-footer --> 
      </form>
      <!-- /.modal-footer --> 
      
    </div>
  </div>
</div>


</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>

