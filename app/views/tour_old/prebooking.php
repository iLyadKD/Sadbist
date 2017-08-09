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
  </style>

<div id="wrapper">
  <?php
      $include["css"][] = "select2/select2";
      $this->load->view("common/head", $include);
      $this->load->view("common/header");
      $this->load->view("common/notification");
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
                            <h4 class="panel-title"><i class="fa fa-bed"></i> Dubai, United Arab Emirates, &nbsp;&nbsp;&nbsp;<?php echo $info->name;?>&nbsp;&nbsp;&nbsp;12/08/2016</h4>
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
                    <!------------------------Double Rooms----------Start----------------->
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $count_room_double;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Standard room <?php echo $i.'&nbsp;(Double)';?></div>
                                    <div class="timeline-panel">
                                      
                                      
                                     
                                      
                                      <?php for($j=1;$j<=2;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select name='adult_salutation[standard][double][]'  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j;?>" name="adult_fname[standard][double][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j;?>" name="adult_lname[standard][double][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Date of Birth</label>
                                            <input type="date" id="<?php  echo 'dob'.$i.$j;?>" name="adult_dob[standard][double][]" class="inputs_group" placeholder="Date of Birth" data-msg-required="Enter the date of birth" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[standard][double][]" class="set_country select2" id="<?php echo 'country'.$i.$j; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j;?>" name="adult_passport_no[standard][double][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="date" id="<?php  echo 'pe'.$i.$j;?>" name="adult_passport_exp[standard][double][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j;?>" name="adult_national_id[standard][double][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>
                              
                              <!-------other room type start----------->
                              
                              <ul class="timeline">
                                <?php $i=0;foreach($room_types as $room_type) {  $i++; ?>
                                 <?php foreach($post as $key=>$value){
                                   if($key == 'double_'.$room_type){                                   
                                     
                                  ?>
                                   <?php for($i = 1;$i<= $value;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $room_type.'&nbsp;room '.$i.'&nbsp(Double)';?></div>
                                    <div class="timeline-panel">
                                      
                                      
                                     
                                      
                                      <?php for($j=1;$j<=2;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select name="adult_salutation[<?php echo $room_type;?>][double][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j.$room_type;?>" name="adult_fname[<?php echo $room_type;?>][double][]
" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j.$room_type;?>" name="adult_lname[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Date of Birth</label>
                                            <input type="date" id="<?php  echo 'dob'.$i.$j.$room_type;?>" name="adult_dob[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Date of Birth" data-msg-required="Enter the date of birth" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[<?php echo $room_type;?>][double][]" class="set_country select2" id="<?php echo 'country'.$i.$j.$room_type; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j.$room_type;?>" name="adult_passport_no[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="date" id="<?php  echo 'pe'.$i.$j.$room_type;?>" name="adult_passport_exp[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j.$room_type;?>" name="adult_national_id[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php  } } } } ?>
                                </ul>
                          <!-------other room type end----------->   
              <!------------------------Double Rooms----------End----------------->
              
              
              <!------------------------Single Rooms----------Start----------------->
                             
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $count_room_single;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Standard room <?php echo $i.'&nbsp;(Single)';?></div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select name="adult_salutation[standard][single][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="adult_fname[standard][single][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="adult_lname[standard][single][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Date of Birth</label>
                                            <input type="date" id="<?php  echo 'dob'.$i;?>" name="adult_dob[standard][single][]" class="inputs_group" placeholder="Date of Birth" data-msg-required="Enter the date of birth" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[standard][single][]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="adult_passport_no[standard][single][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="date" id="<?php  echo 'pe'.$i;?>" name="adult_passport_exp[standard][single][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="adult_national_id[standard][single][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>
                              
                              <!-------other room type start----------->
                              
                                <ul class="timeline">
                                <?php $i=0;foreach($room_types as $room_type) {  $i++; ?>
                                 <?php foreach($post as $key=>$value){
                                   if($key == 'single_'.$room_type){                                   
                                
                                  ?>
                                  <?php for($i = 1;$i<= $value;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $room_type.'&nbsp;room '.$i.'&nbsp(Single)';?> </div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select name="adult_salutation[<?php echo $room_type;?>][single][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$room_type;?>" name="adult_fname[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$room_type;?>" name="adult_lname[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Date of Birth</label>
                                            <input type="date" id="<?php  echo 'dob'.$i.$room_type;?>" name="adult_dob[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Date of Birth" data-msg-required="Enter the date of birth" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[<?php echo $room_type;?>][single][]" class="set_country select2" id="<?php echo 'country'.$i.$room_type; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$room_type;?>" name="adult_passport_no[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="date" id="<?php  echo 'pe'.$i.$room_type;?>" name="adult_passport_exp[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$room_type;?>" name="adult_national_id[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } }  } }?>
                                </ul>

                              <!-------other room type end----------->
      
              <!------------------------Single Rooms----------End----------------->
              
              <!------------------------Triple Rooms----------Start----------------->
                              <ul class="timeline">
                                <?php for($i = 1;$i<= $count_room_triple;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Standard room <?php echo $i.'&nbsp;(Triple)';?></div>
                                    <div class="timeline-panel">
                                      
                                      
                                     
                                      
                                      <?php for($j=1;$j<=3;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select name="adult_salutation[standard][triple][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j;?>" name="adult_fname[standard][triple][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j;?>" name="adult_lname[standard][triple][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Date of Birth</label>
                                            <input type="date" id="<?php  echo 'dob'.$i.$j;?>" name="adult_dob[standard][triple][]" class="inputs_group" placeholder="Date of Birth" data-msg-required="Enter the date of birth" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[standard][triple][]" class="set_country select2" id="<?php echo 'country'.$i.$j; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j;?>" name="adult_passport_no[standard][triple][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="date" id="<?php  echo 'pe'.$i.$j;?>" name="adult_passport_exp[standard][triple][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j;?>" name="adult_national_id[standard][triple][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>
                              <!-------other room type start----------->
                              <ul class="timeline">
                                <?php $i=0;foreach($room_types as $room_type) {  $i++; ?>
                                 <?php foreach($post as $key=>$value){
                                   if($key == 'triple_'.$room_type){                                   
                                
                                  ?>
                                   <?php for($i = 1;$i<= $value;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge"><?php echo $room_type.'&nbsp;room '.$i.'&nbsp(Triple)';?></div>
                                    <div class="timeline-panel">
                                      
                                      
                                     
                                      
                                      <?php for($j=1;$j<=3;$j++){ ?>
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult&nbsp;<?php  echo $j;?>:</h4>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select name="adult_salutation[<?php echo $room_type;?>][triple][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j.$room_type;?>" name="adult_fname[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j.$room_type;?>" name="adult_lname[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Date of Birth</label>
                                            <input type="date" id="<?php  echo 'dob'.$i.$j.$room_type;?>" name="adult_dob[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Date of Birth" data-msg-required="Enter the date of birth" data-rule-required="false">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[<?php echo $room_type;?>][triple][]" class="set_country select2" id="<?php echo 'country'.$i.$j.$room_type; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j.$room_type;?>" name="adult_passport_no[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="date" id="<?php  echo 'pe'.$i.$j.$room_type;?>" name="adult_passport_exp[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j.$room_type;?>" name="adult_national_id[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
                                          </div>
                                        </div>
                                           
                                      </div>
                                      
                                <?php }  ?>
                                
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
                                  <?php  } } } } ?>
                                </ul>
                               <!-------other room type end----------->
              <!------------------------Triple Rooms----------End----------------->
              
               <!------------------------Children(2-6)----------Start----------------->
               
                                <ul class="timeline">
                                <?php for($i = 1;$i<= $twotosix;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Children(2-6)</div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <!--<div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult:</h4>
                                          </div>
                                        </div>-->
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="adult_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="adult_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="adult_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="adult_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="adult_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="adult_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>

               

              
              <!------------------------Children(2-6)----------End----------------->
              
              
              
              <!------------------------Children(6-12)----------Start----------------->
               
                                <ul class="timeline">
                                <?php for($i = 1;$i<= $sixtotwelve;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Children(6-12) </div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <!--<div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult:</h4>
                                          </div>
                                        </div>-->
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="twotosix_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="twotosix_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="twotosix_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="twotosix_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="twotosix_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="twotosix_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>

              
              <!------------------------Children(6-12)----------End----------------->
              
              <!------------------------Children(12-16)----------Start----------------->
               
                                <ul class="timeline">
                                <?php for($i = 1;$i<= $twelvetosixteenth;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Children(12-16) </div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <!--<div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult:</h4>
                                          </div>
                                        </div>-->
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="sixtotwelve_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="sixtotwelve_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="sixtotwelve_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="sixtotwelve_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="sixtotwelve_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="sixtotwelve_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>

              
              <!------------------------Children(12-16)----------End----------------->
              
              <!------------------------Infant----------Start----------------->
               
                                <ul class="timeline">
                                <?php for($i = 1;$i<= $infants;$i++) { ?>
                                  <li>&nbsp;
                                    <div class="timeline-badge">Infant </div>
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        <!--<div class="col-md-12 col-sm-12 nopadding">
                                          <div class="form-group">
                                            <h4>Adult:</h4>
                                          </div>
                                        </div>-->
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Title</label>
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="false">
                                              <option value="0">Mr</option>
                                              <option value="1">Mrs</option>
                                              <option value="2">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="twelvetosixteenth_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="false">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="twelvetosixteenth_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="false">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="false" data-msg-required="required" name="twelvetosixteenth_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="twelvetosixteenth_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="false">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="twelvetosixteenth_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="false">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="twelvetosixteenth_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="false">
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
                  
                                      
                                      
                                      <!--<h5 data-toggle="modal" data-target="#myModal_cancallation">Cancellation Policy</h5>-->
                                    </div>
                                  </li>
                                  <?php } ?>
                                </ul>

              
              <!------------------------Infant----------End----------------->
              
              
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
                      <?php echo $user_exists = '';?>
                      <div class="col-md-8">
                      <h3>Contact Address</h3>
                      <div class="col-md-12 nopadding">
                        <ul class="timeline_1">
                          <li>
                            <div class="col-md-12 nopadding">
                              <div class="traveller_div">
                                <div class="col-md-3 col-sm-3">
                                  <div class="form-group inputtext">
                                  <input type="text" data-rule-required="false" data-msg-required="required" data-rule-email="true" name="email" class="inputs_group" placeholder="<?php echo $this->lang->line('contact_email_address'); ?>" title="<?php echo $this->lang->line('contact_email_address'); ?>" value="<?php echo $user_exists ? $user_details->email_id : ''; ?>">
                                  </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                  <div class="form-group inputtext">
                                  <input type="text" data-rule-required="false" data-msg-required="required" data-rule-number="true" name="contact" class="inputs_group" placeholder="<?php echo $this->lang->line('contact_number'); ?>" title="<?php echo $this->lang->line('contact_number'); ?>" value="<?php echo $user_exists ? $user_details->contact_no : ''; ?>">
                                  </div>
                                </div> 
                              </div>
                            </div>
                          </li>

                        </ul>
                      </div>
                      <ul class="list-inline pull-right">
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
                    <div class="cartsec"><strong>Check In:</strong> 23 Apr, 2015 <br>
                      <strong>Check Out:</strong> 24 Apr, 2015</div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 cartfprice celcart">
                    <div class="cartprc">
                      <div class="singecartpricebuk">IRR <?php echo $price_tour;?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="cartlistingbuk">
                <div class="cartitembuk">
                  <div class="col-md-3 col-sm-3 col-xs-3 celcart"> <a class="smalbukcrt"><i class="fa fa-plane fa-5x"></i></a> </div>
                  <div class="col-md-6 col-sm-6 col-xs-6 splcrtpad celcart">
                    <div class="carttitlebuk">
                    <?php //pr($trans_info);exit; ?>
                    <?php if(isset($trans_info->airline_name)) echo $trans_info->airline_name;?></div>
                    <!-- <div class="cartstar"><img src="http://travelapt.hs24.info/assets/images/bigrating-4.png" alt="" /></div> -->
                    <div class="cartsec"><strong>Departure airport and time:</strong> <?php if(isset($trans_info->departuer_airport) && isset($trans_info->departuer_time)) echo $trans_info->departuer_airport.','.$trans_info->departuer_time;?> <br>
                      <strong>Return departure airport and time:</strong><?php if(isset($trans_info->return_deapartur) && isset($trans_info->return_departure_time)) echo $trans_info->return_deapartur.','.$trans_info->return_departure_time;?></div>
                  </div>
                 <!-- <div class="col-md-3 col-sm-3 col-xs-3 cartfprice celcart">
                    <div class="cartprc">
                      <div class="singecartpricebuk">IRR <?php echo $cost_transportation;?></div>
                    </div>
                  </div>-->
                </div>
              </div>
            </div>
            <div class="hotel_details_1 contact-box" style="display:inline-block; width:100%;">
              <div class="cartlistingbuk">
                <div class="cartitembuk">
                  <div class="col-md-12">
                    <div class="payblnhmxm">I have a promotional code</div>
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
                      <input type="submit" class="promosubmit" name="apply" value="Apply">
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
                    <div class="payblnhm">Sub Total</div>
                  </div>
                  <div class="col-md-4  col-sm-4 col-xs-4 celcart">
                    <div class="cartprc">
                      <div class="ritaln cartcntamnt normalprc">IRR <?php echo $price_tour;?></div>
                    </div>
                  </div>
                </div>
                <div class="cartitembuk">
                  <div class="col-md-8 col-sm-8 col-xs-8 celcart">
                    <div class="payblnhm">Tax</div>
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
                <h3><i class="soap-icon-phone"></i> Need Assistance?</h3>
                <p>We would be more than happy to help you. Our team advisor are 24/7 at your service to help you.</p>
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
                <!--</form>-->
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
      //$this->load->view('common/pop-ups');
      $this->load->view('common/footer');
	  $this->load->view("common/script");
      ?>
      <script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>

</body>
</html>
