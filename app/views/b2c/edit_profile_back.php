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
            <form name="b2c_profile" method="post" class="user_edit" id="profile_form_id"  action="<?php echo base_url('b2c/edit_profile')?>" enctype="multipart/form-data">
              <div class="col-sm-3 mnopad">
                <button type="submit" class="btn btn_editpro">Save Profile</button>
              </div>
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro" data-toggle="modal" data-target="#myModal_1">Change Password</button>
              </div>
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro"  data-toggle="modal" data-target="#myModal_2">Delete Account</button>
              </div>
            </div>
            <div class="col-sm-6 nopadding text-right">
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro"><a href="<?php echo base_url('b2c/support_ticket.html')?>">Ticket Support </a></button>
              </div>
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro"> <a href="<?php echo base_url('b2c/booking_report.html')?>">Report</a></button>
              </div>
            </div>
          </div>

          <div class="profile-content b2c_profile_div">
          
            
            <div class="col-xs-12 nopadding">
                <div class="contact_info">
                  <div class="mn-edit-tab">
                    <h2><?php echo $this->lang->line("contact_information"); ?></h2>
                      <h2 class="pull-right"><a href="<?php echo base_url('b2c/edit_profile/1')?>"><?php echo $this->lang->line("edit_profile"); ?></a></h2>
                      <div> 
                      <div class="companion">
                        <i class="fa fa-user fa-2x" aria-hidden="true"></i>
                      </div>   
                  </div>      
                  </div>
                    <div class="col-sm-7 nopadding">
                    <div class="col-md-4 col-sm-6 col-xs-12 mnopad">
                      <div class="reginput">
                        <!-- <?php if($user->country_code == '') $country_code = ''; else $country_code = $user->country_code;?> -->
                        <label><!-- <?php echo $this->lang->line("country_colan");?> -->Email Id</label>                       
                         
                         <!-- <select <?php echo $readonly;?> class="select2 form-control set_country"  name="country" id="country" hyperlink="<?php echo $country_code;?>" data-rule-required="false" >
                         </select>  -->  
                         <input type="text" name="" class="inputs_group" value="" pattern="Enter Your Email Id"> 
                      </div>
                    </div>
                    
                    <input class="form-control valid" type="hidden" value="<?php echo $country_code; ?>" id="code_country">
                    <?php
                     
                      if($country_code != 'IR') $style="display:none;"; else $style = '';
                    ?>
                    
                 <!-- <div class="col-md-4 col-sm-6 col-xs-12 national_id" style="<?php echo $style;?>">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("national_id_colan"); ?></label>
                      <input data-rule-minLength="10" data-msg-minLength="National code length should be minimum of ten letters"  <?php echo $readonly;?> placeholder="<?php echo $this->lang->line("national_id"); ?>" type="text" class="inputs_group" name="national_id" value="<?php echo @$user->national_id; ?>">
                    </div>
                  </div> -->
        
                    

                  <div class="col-md-4 col-sm-6 col-xs-12 mnopad">
                    <div class="reginput">
                      <label><!-- <?php echo $this->lang->line("postal_code_colan"); ?> -->Mobile Number</label>
                      <input  <?php echo $readonly;?> placeholder="Ex : +98-912-588-7875" type="text" class="inputs_group" name="postal_code" value="<?php echo $user->postal_code; ?>">
                    </div>
                  </div>

                  <!--<div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_where_you_live_colan"); ?></label>
                      <input type="text" class="form-control" name="current_location" value="<?php echo $user->current_location; ?>">
                    </div>
                  </div>-->

                  <div class="col-md-4 col-sm-12 col-xs-12 mnopad">
                    <div class="reginput">
                      <label><!-- <?php echo $this->lang->line("address_colan"); ?> -->Landline Number</label>
                      <input  <?php echo $readonly;?> placeholder="Land +98-21-588-7875" type="text" class="inputs_group" name="postal_code" value="<?php echo $user->postal_code; ?>">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12 mnopad">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("address_colan"); ?></label>
                      <textarea <?php echo $readonly;?>  placeholder="<?php echo $this->lang->line("address"); ?>" name="address" class="inputs_group mn-height"><?php echo $user->address; ?></textarea>
                    </div>
                  </div>


              
                 </div>

                 <div class="col-sm-5">
                   <div class="col-md-4 col-sm-6 col-xs-12 mnopad">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("postal_code_colan"); ?></label>
                      <input  <?php echo $readonly;?> placeholder="<?php echo $this->lang->line("postal_code_colan"); ?>" type="text" class="inputs_group" name="postal_code" value="<?php echo $user->postal_code; ?>">
                    </div>
                  </div>
                 </div>


                </div>
              </div>
              <div class="clearfix"></div>

            <div class="personal_info">
                
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
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" id="adult_salutation<?php echo $i; ?>" >
                      <option <?php echo $salutation === "mr" ? "selected" : ""; ?> value="0"><?php echo $this->lang->line("mr");
                      ?></option>
                      <option <?php echo $salutation === "mrs" ? "selected" : ""; ?> value="1"><?php echo $this->lang->line("mrs");
                      ?></option>
                      <option <?php echo $salutation === "miss" ? "selected" : ""; ?> value="3"><?php echo $this->lang->line("miss");
                      ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("first_name_colan"); ?></label> -->
                    <input <?php echo $readonly;?> type="text" class="inputs_group" name="first_name" value="<?php echo $user->firstname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("first_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-5 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("last_name_colan"); ?></label> -->
                    <input  <?php echo $readonly;?> type="text" class="inputs_group" name="last_name"  value="<?php echo $user->lastname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("last_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                </div>


                <div class="col-sm-7 nopadding">
                <div class="col-md-3 col-sm-3 mnopad">
                  <div class="reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[]" class="set_country select2" id="<?php echo 'adult_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("contact_number_colan"); ?></label> -->
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="adult_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="adult_national_id<?php echo $i; ?>" value="<?php echo $national_id; ?>">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("date_of_birth_colan"); ?></label> -->
                    <input  <?php echo $readonly;?> type="text" class="inputs_group till_current_date adult" id="dob" name="dob" value="<?php if($user->dob=="0000-00-00"){ echo ""; } else { echo  date("d-m-Y",strtotime($user->dob)); } ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("email_address_colan"); ?></label> -->
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="adult_passport<?php echo $i; ?>" value="<?php echo $passport_no; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("contact_number_colan"); ?></label> -->
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_expire[]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_current_date" readonly="true" id="adult_passport_expire<?php echo $i; ?>" value="<?php echo $passport_no_expire; ?>">
                  </div>
                </div>

                

                </div>

              </div>
              <div class="clearfix"></div>
        
              
              <div class="personal_info">
                
              <div class="mn-edit-tab">
                <h2>Companions</h2>
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
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" id="adult_salutation<?php echo $i; ?>" >
                      <option <?php echo $salutation === "mr" ? "selected" : ""; ?> value="0"><?php echo $this->lang->line("mr");
                      ?></option>
                      <option <?php echo $salutation === "mrs" ? "selected" : ""; ?> value="1"><?php echo $this->lang->line("mrs");
                      ?></option>
                      <option <?php echo $salutation === "miss" ? "selected" : ""; ?> value="3"><?php echo $this->lang->line("miss");
                      ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("first_name_colan"); ?></label> -->
                    <input <?php echo $readonly;?> type="text" class="inputs_group" name="first_name" value="<?php echo $user->firstname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("first_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                <div class="col-md-5 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("last_name_colan"); ?></label> -->
                    <input  <?php echo $readonly;?> type="text" class="inputs_group" name="last_name"  value="<?php echo $user->lastname; ?>" data-rule-required="false" data-rule-pattern="[A-Za-z]{2,20}" placeholder="<?php echo $this->lang->line("last_name"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>">
                  </div>
                </div>

                </div>


                <div class="col-sm-7 nopadding">
                <div class="col-md-3 col-sm-3 mnopad">
                  <div class="reginput">
                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[]" class="set_country select2" id="<?php echo 'adult_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("date_of_birth_colan"); ?></label> -->
                    <input  <?php echo $readonly;?> type="text" class="inputs_group till_current_date adult" id="dob" name="dob" value="<?php if($user->dob=="0000-00-00"){ echo ""; } else { echo  date("d-m-Y",strtotime($user->dob)); } ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("email_address_colan"); ?></label> -->
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="adult_passport<?php echo $i; ?>" value="<?php echo $passport_no; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("contact_number_colan"); ?></label> -->
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_expire[]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_current_date" readonly="true" id="adult_passport_expire<?php echo $i; ?>" value="<?php echo $passport_no_expire; ?>">
                  </div>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12 mnopad">
                  <div class="reginput">
                    <!-- <label><?php echo $this->lang->line("contact_number_colan"); ?></label> -->
                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="adult_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="adult_national_id<?php echo $i; ?>" value="<?php echo $national_id; ?>">
                  </div>
                </div>

                </div>

              </div>

               <div class="clearfix"></div>




               <div class="col-xs-12 nopadding">
                <div class="contact_info">
                  <!-- <div class="mn-edit-tab">
                    <h2><?php echo $this->lang->line("passport_information"); ?></h2>
                  </div> -->
                  
                  
              <!--    <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("user_profile_nationality_colan"); ?></label>
                      <input type="text" class="form-control" name="national" value="<?php echo $user->country; ?>">
                    </div>
                  </div>-->
                  
                  <!-- <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("passport_number_colan"); ?></label>
                      <input <?php echo $readonly;?> type="text" class="inputs_group" placeholder="<?php echo $this->lang->line("passport_number"); ?>" name="passport_no" value="<?php echo @$user->passport_no; ?>">
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="reginput">
                      <label><?php echo $this->lang->line("passport_expiry_date_colan"); ?></label>
                      <input <?php echo $readonly;?> type="text" class="inputs_group from_date" id="passport_exp_st" name="passport_exp" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" value="<?php if(@$user->passport_exp_date=="0000-00-00"){ echo ""; } else { echo date("d-m-Y",strtotime(@$user->passport_exp_date)); } ?>">
                    </div>
                  </div> -->
                  
              </div>
            </div>

            <?php if($readonly == '') { ?>
            <div class="col-xs-12">
              <div class="reginput">
                <input type="submit" class="btn-2 pull-right" value="<?php echo $this->lang->line("save_information"); ?>">
              </div>
            </div>
            
            <?php } ?>
            </form>
           
          </div>
             
        </div>
          
      </div>
    </div>
  </section>
  <?php
    $this->load->view('common/footer');
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

