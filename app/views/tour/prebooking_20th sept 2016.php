<!DOCTYPE html>
<html class="no-js">
  <?php
    $this->load->view("common/head");
  ?>

<body>
  
  <style>
  label.error {
    color: #ff0000;
    font-variant: super;
    position: relative;
    top: 4px;
    float: left;
    font-size: 12px;
    font-weight: normal;
  }
  .inputs_group{
    
    
  }
  .timeline-panel{
    margin-top: 20px;
  }
  .beds {
    position: relative;
    left: 200px;
    bottom: 28px;
  }
  .beds img {
    position: relative;
    left: 5px;
    bottom: 5px;
  }
  .beds .extra{
    position: relative;
    left: 52px;
    bottom: 26px;
  }
  .beds i {
    position: relative;
    left: 7px;
    bottom: 3px;
    font-size: 12px;
  }
  .beds span {
    position: relative;
    left: 9px;
    bottom: 3px;
    font-size: 10px;
  }
  .timeline > li > .timeline-badge {
    
    width: 180px !important;
  }
  
  </style>

<div id="wrapper">
  <?php
      $include["css"][] = "select2/select2";
      $include["css"][] = "backslider";
      $include["css"][] = "owl.theme";
      $include["css"][] = "owl.carousel";
      $include["css"][] = "jquery.bxslider";
      $this->load->view("common/head", $include);
      $user_exists = isset($user_details) ? true : false;
      
  ?>

 <!-- <span class="cart_icon"><i class="fa fa-cart-plus"></i></span> -->
  <div class="clearfix"></div>
  <section class="mn-reg">
    <div class="container">
      <div class="row">
        <div class="fancy-title">
          <div class="col-md-12 mn_nopadding_left">
            <section>
              <div class="wizard">
                <div class="wizard-inner">
                  <div class="connecting-line"></div>
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"> <a href="#step1" data-value=1 class="tab"  data-toggle="tab" aria-controls="step1" role="tab" title="Traveller Details"> <span class="round-tab"> <i class="fa fa-user"></i> </span> </a> </li>
                    <li role="presentation" class="disabled"> <a href="#step2" data-value=2 class="tab"  data-toggle="tab" aria-controls="step2" role="tab" title="Billing Address"> <span class="round-tab"> <i class="fa fa-list-alt"></i> </span> </a> </li>
                    <li role="presentation" class="disabled"> <a href="#step3" data-value=3 class="tab"  data-toggle="tab" aria-controls="step3" role="tab" title="Payment Details"> <span class="round-tab"> <i class="fa fa-shopping-cart"></i> </span> </a> </li>
                  </ul>
                </div>
               <form name="" id="prebook_submit"   action="javascript:void(0);" method="post" >
                  <div class="tab-content col-md-12">
                    <div class="tab-pane active" role="tabpanel" id="step1">
                     
                      <!-- begin panel group -->
                      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"> 
                        
                        <!-- panel 1 -->
                        <div class="panel panel-default"> 
                          <!--wrap panel heading in span to trigger image change as well as collapse --> 
                          <span class="side-tab" data-target="#tab1" data-toggle="tab" role="tab" aria-expanded="false">
                          <div class="panel-heading" role="tab" id="headingOne"  data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4 class="panel-title"><i class="fa fa-bed"></i> <?php echo $tour_to;?>, &nbsp;&nbsp;&nbsp;<?php echo $info->name;?></h4>
                          </div>
                          </span>
                          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                              <div class="col-md-12 nopadding">
                                <input type="hidden" name="tour_id" value="<?php echo $id_tour;?>">
                                <input type="hidden" name="hotel_id" value="<?php echo $id_hotel;?>">
                                <input type="hidden" name="room_type" value="<?php echo $get_room_types;?>">
                                <input type="hidden" name="count_room_single" value="<?php echo $count_room_single;?>">
                                <input type="hidden" name="count_room_double" value="<?php echo $count_room_double;?>">
                                <input type="hidden" name="count_room_triple" value="<?php echo $count_room_triple;?>">
                                <input type="hidden" name="total_tour_price" value="<?php echo $price_tour;?>">
                                <input type="hidden"   name="departures" id="departures" value='<?php echo $departures;?>'>
                                <input type="hidden" name="tour_from" value="<?php echo $tour_from;?>">
                                <input type="hidden" name="tour_to" value="<?php echo $tour_to;?>">
                                <input type="hidden" name="after_confirm" value='<?php echo $after_confirm;?>'>
                                <input  type="hidden"   name="short_info" value="<?php echo $short_info;?>">
              
              
              <!------------------------Single Rooms----------Start----------------->
                             
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $count_room_single;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line('standard_room'); ?> <?php echo $i.'&nbsp;(Single)';?></div>
                                    <input type="hidden" value="1" name="bed[standard][single][<?php echo $i-1;?>]" >
                                    
                                    
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4><?php echo $this->lang->line("adult_colan");?></h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="adult_salutation[standard][single][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false" id="<?php  echo 'salutation_single'.$i;?>">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="1"><?php echo $this->lang->line("mrs");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("first_name");?></label>
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_fname[standard][single][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'fname_single'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_lname[standard][single][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'lname_single'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_dob[standard][single][]" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'dob_single'.$i;?>" >
                                          </div>
                                        </div>
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[standard][single][]" class="set_country select2" id="<?php echo 'country_single'.$i; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_no[standard][single][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'pn_single'.$i;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_exp[standard][single][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'pe_single'.$i;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                            <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="adult_national_id[standard][single][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'nationality_single'.$i;?>" >
                                          </div>
                                        </div>
                                      </div>
                                      
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                       
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>
                              
                              
      
              <!------------------------Single Rooms----------End----------------->
              
              <!------------------------Double Rooms----------Start----------------->
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $count_room_double;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line('standard_room'); ?> <?php echo $i.'&nbsp;(Double)';?></div>
                                    
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input data-rule-required="false" data-msg-required="Choose bed type" type="checkbox" value="2" title="Double Bed" name="bed[standard][double][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/double_bed.png');?>"></label>
                                      </div>
                                    </div>
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input type="checkbox" value="1" title="Single Bed" name="bed[standard][double][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/single_bed.png');?>"><i class="fa fa-times" aria-hidden="true"></i><span>2</span></label>
                                      </div>
                                    </div>
                                    
                                    <div class="timeline-panel">
                                      <?php for($j=1;$j<=2;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4><?php echo $this->lang->line('adult'); ?>&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                             <select name="adult_salutation[standard][double][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false" id="<?php  echo 'salutation_double'.$i;?>">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="1"><?php echo $this->lang->line("mrs");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_fname[standard][double][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'fname_double'.$i.$j;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_lname[standard][double][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'lname_double'.$i.$j;?>" >
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_dob[standard][double][]" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'dob_double'.$i.$j;?>" >
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[standard][double][]" class="set_country select2" id="<?php echo 'country_double'.$i.$j; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_no[standard][double][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'pn_double'.$i.$j;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_exp[standard][double][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'pe_double'.$i.$j;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                            <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="adult_national_id[standard][double][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'nationality_double'.$i.$j;?>" >
                                          </div>
                                        </div>
                                          
                                          
                                           
                                      </div>
                                      
                                <?php } ?>
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                       
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                    
                                  </li>
                                  
                                  <?php } ?>
                                </ul>
                              
                               
              <!------------------------Double Rooms----------End----------------->
              
              <!------------------------Triple Rooms----------Start----------------->
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $count_room_triple;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line('standard_room'); ?> <?php echo $i.'&nbsp;(Triple)';?></div>
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input data-rule-required="false" data-msg-required="Choose bed type" type="checkbox" value="2" title="Double Bed" name="bed[standard][triple][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/double_bed.png');?>"><i class="fa fa-plus" aria-hidden="true"></i><img class="extra" src="<?php echo base_url('assets/images/single_bed.png');?>"></label>
                                      </div>
                                    </div>
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input type="checkbox" value="1" title="Single Bed" name="bed[standard][triple][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/single_bed.png');?>"><i class="fa fa-times" aria-hidden="true"></i><span>3</span></label>
                                      </div>
                                    </div>
                                    
                                    <div class="timeline-panel">
                                      
                                      
                                     
                                      
                                      <?php for($j=1;$j<=3;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4><?php echo $this->lang->line('adult'); ?>&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="adult_salutation[standard][triple][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false" id="<?php  echo 'salutation_triple'.$i;?>">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="1"><?php echo $this->lang->line("mrs");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_fname[standard][triple][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'fname_triple'.$i.$j;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                           <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_lname[standard][triple][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'lname_triple'.$i.$j;?>" >
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_dob[standard][triple][]" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'dob_triple'.$i.$j;?>" >
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[standard][triple][]" class="set_country select2" id="<?php echo 'country_triple'.$i.$j; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_no[standard][triple][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'pn_triple'.$i.$j;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_exp[standard][triple][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'pe_triple'.$i.$j;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                            <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="adult_national_id[standard][triple][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'nationality_triple'.$i.$j;?>" >
                                          </div>
                                        </div>
                                           
                                      </div>
                                      
                                <?php } ?>
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                       
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                  
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>
                              
              <!------------------------Triple Rooms----------End----------------->
              
              <!-------other room type start----------->
                              <ul class="timeline">
                                <?php  $i=0;foreach($room_types as $room_type) {  $i++; ?>
                                 <?php foreach($post as $key=>$value){
                                  if($key == 'double_'.$room_type){
                                    $label = 'Double';
                                    $entity = 'double';
                                    $access = 1;
                                    $limit = 2;
                                  }
                                  elseif($key == 'single_'.$room_type){
                                    $label = 'Single';
                                    $entity = 'single';
                                    $access = 1;
                                    $limit = 1;
                                  }
                                  elseif($key == 'triple_'.$room_type){
                                    $label = 'Triple';
                                    $entity = 'triple';
                                    $access = 1;
                                    $limit = 3;
                                  }else{
                                    $access = 0;
                                    $label = '';
                                    $limit = 0;
                                  }   
                                  ?>
                                  <?php if($access == 1) { ?>
                                  <?php for($i = 1;$i<= $value;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $room_type.'&nbsp;room '.$i.'&nbsp('.$label.')';?></div>
                                    
                                    <?php if($key == 'double_'.$room_type) { ?>
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input data-rule-required="false" data-msg-required="Choose bed type" type="checkbox" value="2" title="<?php echo $label;?> Bed" name="bed[<?php echo $room_type;?>][<?php echo $entity;?>][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/double_bed.png');?>"></label>
                                      </div>
                                    </div>
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input type="checkbox" value="1" title="Single Bed" name="bed[<?php echo $room_type;?>][double][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/single_bed.png');?>"><i class="fa fa-times" aria-hidden="true"></i><span>2</span></label>
                                      </div>
                                    </div>
                                    <?php }elseif($key == 'triple_'.$room_type)  { ?>
                                    
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input data-rule-required="false" data-msg-required="Choose bed type" type="checkbox" value="2" title="Double Bed" name="bed[<?php echo $room_type;?>][triple][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/double_bed.png');?>"><i class="fa fa-plus" aria-hidden="true"></i><img class="extra" src="<?php echo base_url('assets/images/single_bed.png');?>"></label>
                                      </div>
                                    </div>
                                    <div class="col-md-1 col-sm-2 beds">
                                      <div>
                                        <label><input type="checkbox" value="1" title="Single Bed" name="bed[<?php echo $room_type;?>][triple][<?php echo $i-1;?>]" ><img src="<?php echo base_url('assets/images/single_bed.png');?>"><i class="fa fa-times" aria-hidden="true"></i><span>3</span></label>
                                      </div>
                                    </div>
                                    
                                    <?php }elseif($key == 'single_'.$room_type) {  ?>
                                    <input type="hidden" value="1" name="bed[<?php echo $room_type;?>][single][<?php echo $i-1;?>]" >
                                    <?php } ?>
                                    
                                    
                                    
                                    <div class="timeline-panel">
                                      
                                      
                                     
                                      
                                      <?php for($j=1;$j<=$limit;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4><?php echo $this->lang->line('adult'); ?>&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="adult_salutation[<?php echo $room_type;?>][<?php echo $entity;?>][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false" id="<?php  echo 'salutation_'.$entity.''.$i.$j.$room_type;?>">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="1"><?php echo $this->lang->line("mrs");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_fname[<?php echo $room_type;?>][<?php echo $entity;?>][]
" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'fname_'.$entity.''.$i.$j.$room_type;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_lname[<?php echo $room_type;?>][<?php echo $entity;?>][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'lname_'.$entity.''.$i.$j.$room_type;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_dob[<?php echo $room_type;?>][<?php echo $entity;?>][]" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'dob_'.$entity.''.$i.$j.$room_type;?>" >
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[<?php echo $room_type;?>][<?php echo $entity;?>][]" class="set_country select2" id="<?php echo 'country_'.$entity.''.$i.$j.$room_type; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_no[<?php echo $room_type;?>][<?php echo $entity;?>][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'pn_'.$entity.''.$i.$j.$room_type;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_exp[<?php echo $room_type;?>][<?php echo $entity;?>][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'pe_'.$entity.''.$i.$j.$room_type;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                            <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="adult_national_id[<?php echo $room_type;?>][<?php echo $entity;?>][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'nationality_'.$entity.''.$i.$j.$room_type;?>" >
                                          </div>
                                        </div>
                                           
                                      </div>
                                      
                                <?php } ?>
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                       
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                  
                                      
                                      
                                    </div>
                                  </li>
                                  <?php   } } } } ?>
                                </ul>
                          <!-------other room type end----------->  
              
               <!------------------------<?php echo $this->lang->line("twotosix"); ?>----------Start----------------->
               
                                <ul class="timeline">
                                <?php for($i = 1;$i<= $twotosix;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line("twotosix"); ?></div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="child[twotosix][salutation][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twotosix][fname][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'twotosix_fname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twotosix][lname][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'twotosix_lname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twotosix][dob][]" class="inputs_group till_current_date child" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'twotosix_dob'.$i;?>" >
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twotosix][nationality][]" class="set_country select2" id="<?php echo 'twotosix_country'.$i; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twotosix][passport_no][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'twotosix_pn'.$i;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twotosix][passport_exp][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'twotosix_pe'.$i;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                           <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="child[twotosix][national_id][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'twotosix_nationality'.$i;?>" >
                                          </div>
                                        </div>
                                      </div>                                     
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>                             
                               
                

              
              <!------------------------<?php echo $this->lang->line("twotosix"); ?>----------End----------------->
              
              
              
              <!------------------------<?php echo $this->lang->line("sixtotwelve"); ?>----------Start----------------->
               
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $sixtotwelve;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line("sixtotwelve"); ?></div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="child[sixtotwelve][salutation][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[sixtotwelve][fname][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'sixtotwelve_fname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[sixtotwelve][lname][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'sixtotwelve_lname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[sixtotwelve][dob][]" class="inputs_group till_current_date child" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'sixtotwelve_dob'.$i;?>" >
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[sixtotwelve][nationality][]" class="set_country select2" id="<?php echo 'sixtotwelve_country'.$i; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                          
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[sixtotwelve][passport_no][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'sixtotwelve_pn'.$i;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[sixtotwelve][passport_exp][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'sixtotwelve_pe'.$i;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                          
                                           <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="child[sixtotwelve][national_id][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'sixtotwelve_nationality'.$i;?>" >
                                          </div>
                                        </div>
                                      </div>                                     
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>
              
              <!------------------------<?php echo $this->lang->line("sixtotwelve"); ?>----------End----------------->
              
              <!------------------------<?php echo $this->lang->line("twelvetosixteenth"); ?>----------Start----------------->
               
                               <ul class="timeline">
                                <?php for($i = 1;$i<= $twelvetosixteenth;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line("twelvetosixteenth"); ?></div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="child[twelvetosixteenth][salutation][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twelvetosixteenth][fname][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'twelvetosixteenth_fname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twelvetosixteenth][lname][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'twelvetosixteenth_lname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twelvetosixteenth][dob][]" class="inputs_group till_current_date child" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'twelvetosixteenth_dob'.$i;?>" >
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twelvetosixteenth][nationality][]" class="set_country select2" id="<?php echo 'twelvetosixteenth_country'.$i; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                          
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twelvetosixteenth][passport_no][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'twelvetosixteenth_pn'.$i;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[twelvetosixteenth][passport_exp][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'twelvetosixteenth_pe'.$i;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                          
                                           <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="child[twelvetosixteenth][national_id][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'twelvetosixteenth_nationality'.$i;?>" >
                                          </div>
                                        </div>
                                      </div>                                     
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>

              
              <!------------------------<?php echo $this->lang->line("twelvetosixteenth"); ?>----------End----------------->
              
              <!------------------------Infant----------Start----------------->
               
                                <ul class="timeline">
                                <?php for($i = 1;$i<= $infants;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $this->lang->line("infants"); ?></div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="child[infants][salutation][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('first_name'); ?></label>
                                            
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[infants][fname][]" class="inputs_group first_type" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="<?php  echo 'infants_fname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[infants][lname][]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="<?php  echo 'infants_lname'.$i;?>" >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[infants][dob][]" class="inputs_group till_current_date infant" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="<?php  echo 'infants_dob'.$i;?>" >
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        
                                        <select style="width:100% !important;" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[infants][nationality][]" class="set_country select2" id="<?php echo 'infants_country'.$i; ?>" >
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                          
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[infants][passport_no][]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="<?php  echo 'infants_pn'.$i;?>"  data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            
                                            <input type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child[infants][passport_exp][]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true" id="<?php  echo 'infants_pe'.$i;?>" >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                          
                                           <input disabled type="text" autocomplete="off" data-rule-required="false" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="child[infants][national_id][]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="<?php  echo 'infants_nationality'.$i;?>" >
                                          </div>
                                        </div>
                                      </div>                                     
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>

              
             <!------------------------Infant----------End----------------->
              
                                <ul class="timeline">
                                    <li>&nbsp;
                                      <div class="timeline-badge">Notes </div>
                                      <div class="timeline-panel">
                                        <div class="col-md-12 mn_nopadding traveller_details">
                                          <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group inputtext">
                                             
                                              <textarea  name="notes" class="form-control"></textarea>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </li>
                                </ul>
              
              
                              </div>
                            </div>
                          </div>
                        </div>
                               
                      </div>
                      
                      <ul class="list-inline pull-right">
                        <li>
                          <button type="button" class="searchbtn next-step1">Next</button>
                        </li>
                      </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                      <div class="col-md-8">
                      <h3>Contact Address</h3>
                      <div class="col-md-12 nopadding">
                        <ul class="timeline_1">
                          <li>
                            <div class="col-md-12 nopadding">
                              <div class="traveller_div">
                                <div class="col-md-3 col-sm-3">
                                  <div class="form-group inputtext">
                                  <input type="text" data-rule-required="false" data-msg-required="required" data-rule-email="true" name="email" class="inputs_group" placeholder="<?php echo $this->lang->line('email_address'); ?>" title="<?php echo $this->lang->line('contact_email_address'); ?>" value="<?php echo $user_exists ? $user_details->email_id : ''; ?>">
                                  </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                  <div class="form-group inputtext">
                                  <input type="text" data-rule-required="false" data-msg-required="required" data-rule-flexi_contact="true" data-msg-flexi_contact="+dd dd[dd] dd[dd] dd[ddddddd]" name="contact" class="inputs_group" placeholder="<?php echo $this->lang->line('contact_number'); ?>" title="<?php echo $this->lang->line('contact_number'); ?>" value="<?php echo $user_exists ? $user_details->contact_no : ''; ?>">
                                  <input type="hidden" name="user_type" value="<?php echo $user_exists ? $user_details->user_type : ''; ?>">
                                  <input type="hidden" name="user_id" value="<?php echo $user_exists ? $user_details->user_id : ''; ?>">
                                  </div>
                                </div> 
                              </div>
                              <div class="loader"></div>
                            </div>
                          </li>

                        </ul>
                      </div>
                      <ul class="list-inline pull-right pre_ul">
                        <li>
                          <button type="button" class="searchbtn prev-step">Previous</button>
                        </li>
                        <li>
                          <button type="submit" class="searchbtn next-step2 prebook_btn">Book</button>
                        </li>
                      </ul>
                    </div>
                                <div class="col-md-4 mn_nopadding_right">
            <div class="booking-details hotel_details_1" style="display:inline-block;">
              <div class="cartlistingbuk">
                <div class="cartitembuk">
                  <div class="col-md-3 col-sm-3 col-xs-3 celcart"> <a class="smalbukcrt"><i class="fa fa-hotel fa-4x"></i></a> </div>
                  <div class="col-md-6 col-sm-6 col-xs-6 splcrtpad celcart">
                    <div class="carttitlebuk"><?php echo $info->name;?></div>
                    <!-- <div class="cartstar"><img src="http://travelapt.hs24.info/assets/images/bigrating-4.png" alt="" /></div> -->
                    <div class="cartsec"><strong>Check In:</strong> <?php echo date('dS M Y',strtotime($post['tour_date']));?> <br>
                      <strong>Check Out:</strong> <?php echo date('dS M Y',strtotime($post['tour_date_end']));?></div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 cartfprice celcart">
                    <div class="cartprc">
                      <div class="singecartpricebuk">IRR <?php echo $price_tour;?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="cartlistingbuk">
                
                <?php
                  if(isset($trans_info->tour_flight_id)){
                    $logo = 'plane';
                    $from = $trans_info->departuer_airport;
                    $from_time = $trans_info->departuer_time;
                    $return_from = $trans_info->return_deapartur;
                    $return_from_time = $trans_info->return_departure_time;
                    
                  }
                  if(isset($trans_info->tour_bus_id)){
                    $logo = 'bus';
                    $from = $trans_info->departure_from;
                    $from_time = $trans_info->departure_time;
                    $return_from = $trans_info->return_deaparture;
                    $return_from_time = $trans_info->return_departure_time;
                  }
                  if(isset($trans_info->tour_train_id)){
                    $logo = 'train';
                    $from = $trans_info->departure_from;
                    $from_time = $trans_info->departure_time;
                    $return_from = $trans_info->return_deaparture;
                    $return_from_time = $trans_info->return_departure_time;
                    
                  }
                  if(isset($trans_info->tour_cruise_id)){
                    $logo = 'ship';
                    $from = $trans_info->departure_from;
                    $from_time = $trans_info->departure_time;
                    $return_from = $trans_info->return_deaparture;
                    $return_from_time = $trans_info->return_departure_time;
                    
                  }
                ?>
                <div class="cartitembuk">
                  <div class="col-md-3 col-sm-3 col-xs-3 celcart"> <a class="smalbukcrt"><i class="fa fa-<?php echo $logo;?> fa-5x"></i></a> </div>
                  <div class="col-md-6 col-sm-6 col-xs-6 splcrtpad celcart">
                    <div class="carttitlebuk">
                    </div>
                    <div class="cartsec"><strong>Departure airport and time:</strong> <?php  echo $from.',&nbsp;'.$from_time;?> <br>
                      <strong>Return departure airport and time:</strong><?php  echo $return_from.',&nbsp;'.$return_from_time;?></div>
                  </div>
                </div>
                
              </div>
            </div>
            <div class="hotel_details_1 contact-box" style="display:inline-block; width:100%;">
              <div class="cartlistingbuk">
                <div class="cartitembuk">
                  <div class="col-md-12">
                    <div class="payblnhmxm"><?php echo $this->lang->line("have_promocode"); ?></div>
                  </div>
                </div>
                <div class="clear"></div>
                <div class="cartitembuk prompform">
                 
                    <div class="col-md-8 col-xs-8 mn_nopadding_right">
                      <div class="cartprc">
                        <div class="payblnhm singecartpricebuk ritaln">
                          <input type="text" class="promocode" name="code" placeholder="Enter your code">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-xs-4 mn_nopadding_left payblnhm">
                      <input type="submit" class="promosubmit" name="apply" value="<?php echo $this->lang->line("apply"); ?>">
                    </div>
                  
                </div>
                <div class="clear"></div>
                <div class="savemessage"></div>
              </div>
            </div>
            <div class="hotel_details_1 contact-box" style="display:inline-block; width:100%;">
              <div class="cartlistingbuk">
                <div class="cartitembuk">
                  <div class="col-md-8 col-sm-8 col-xs-8 celcart">
                    <div class="payblnhm"><?php echo $this->lang->line("sub_total"); ?></div>
                  </div>
                  <div class="col-md-4  col-sm-4 col-xs-4 celcart">
                    <div class="cartprc">
                      <div class="ritaln cartcntamnt normalprc">IRR <?php echo $price_tour;?></div>
                    </div>
                  </div>
                </div>
                <div class="cartitembuk">
                  <div class="col-md-8 col-sm-8 col-xs-8 celcart">
                    <div class="payblnhm"><?php echo $this->lang->line("tax"); ?></div>
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4 celcart">
                    <div class="cartprc">
                      <div class="ritaln cartcntamnt normalprc discount">IRR <span class="amount">0.00</span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="cartlistingbuk nomarr">
                <div class="cartitembuk">
                  <div class="col-md-8 col-sm-8 col-xs-8 celcart">
                    <div class="payblnhm">Total Amount</div>
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4 celcart">
                    <div class="cartprc">
                      <div class="ritaln cartcntamnt bigclrfnt finalAmt">IRR <span class="amount"><?php echo $price_tour;?></span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="hotel_details_1 contact-box">
              <div class="travelo-box">
                <h3><i class="soap-icon-phone"></i> <?php echo $this->lang->line("need_assistance"); ?></h3>
                <p><?php echo $this->lang->line("need_assistance_description"); ?></p>
                <address class="contact-details">
                <span class="contact-phone"> 1-800-123-HELLO</span> <br>
                <a class="contact-email" href="#">help@10020.ir</a>
                </address>
              </div>
            </div>
          </div>

                    </div>
                    </form>
                   <div class="clearfix"></div>
                  </div>
              </div>
            </section>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="clearfix"></div>
<?php 
      $this->load->view('common/pop-ups');
      $this->load->view('common/footer');
	  $this->load->view("common/script");
      ?>
      <script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>

</body>
</html>
