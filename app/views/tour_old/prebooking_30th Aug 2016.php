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
               <form name="" id="prebook_submit"   action="<?php echo base_url('tour/prebook_submit')?>" method="post"  enctype="multipart/form-data">
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
                                            <select name='adult_salutation[standard][double][]'  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j;?>" name="adult_fname[standard][double][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j;?>" name="adult_lname[standard][double][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[standard][double][]" class="set_country select2" id="<?php echo 'country'.$i.$j; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j;?>" name="adult_passport_no[standard][double][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i.$j;?>" name="adult_passport_exp[standard][double][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j;?>" name="adult_national_id[standard][double][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                <?php for($i = 1;$i<= $count_room_double;$i++) { ?>
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
                                            <select name="adult_salutation[standard][<?php echo $room_type;?>][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j.$room_type;?>" name="adult_fname[<?php echo $room_type;?>][double][]
" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j.$room_type;?>" name="adult_lname[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[<?php echo $room_type;?>][double][]" class="set_country select2" id="<?php echo 'country'.$i.$j.$room_type; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j.$room_type;?>" name="adult_passport_no[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i.$j.$room_type;?>" name="adult_passport_exp[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j.$room_type;?>" name="adult_national_id[<?php echo $room_type;?>][double][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                  <?php } } } } ?>
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
                                            <select name="adult_salutation[standard][single][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="adult_fname[standard][single][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="adult_lname[standard][single][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[standard][single][]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="adult_passport_no[standard][single][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="adult_passport_exp[standard][single][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="adult_national_id[standard][single][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                            <select name="adult_salutation[<?php echo $room_type;?>][single][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$room_type;?>" name="adult_fname[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$room_type;?>" name="adult_lname[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[<?php echo $room_type;?>][single][]" class="set_country select2" id="<?php echo 'country'.$i.$room_type; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$room_type;?>" name="adult_passport_no[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i.$room_type;?>" name="adult_passport_exp[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$room_type;?>" name="adult_national_id[<?php echo $room_type;?>][single][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                            <select name="adult_salutation[standard][triple][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j;?>" name="adult_fname[standard][triple][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j;?>" name="adult_lname[standard][triple][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[standard][triple][]" class="set_country select2" id="<?php echo 'country'.$i.$j; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j;?>" name="adult_passport_no[standard][triple][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i.$j;?>" name="adult_passport_exp[standard][triple][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j;?>" name="adult_national_id[standard][triple][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                <?php for($i = 1;$i<= $count_room_triple;$i++) { ?>
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
                                            <select name="adult_salutation[<?php echo $room_type;?>][triple][]"  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i.$j.$room_type;?>" name="adult_fname[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i.$j.$room_type;?>" name="adult_lname[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                        
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[<?php echo $room_type;?>][triple][]" class="set_country select2" id="<?php echo 'country'.$i.$j.$room_type; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i.$j.$room_type;?>" name="adult_passport_no[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i.$j.$room_type;?>" name="adult_passport_exp[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i.$j.$room_type;?>" name="adult_national_id[<?php echo $room_type;?>][triple][]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                  <?php } } } } ?>
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
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="adult_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="adult_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="adult_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="adult_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="adult_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="adult_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="twotosix_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="twotosix_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="twotosix_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="twotosix_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="twotosix_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="twotosix_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="sixtotwelve_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="sixtotwelve_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="sixtotwelve_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="sixtotwelve_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="sixtotwelve_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="sixtotwelve_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                                            <select  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="Mr">Mr</option>
                                              <option value="Mrs">Mrs</option>
                                              <option value="Miss">Miss</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">First Name</label>
                                            <input type="text" id="<?php  echo 'fname'.$i;?>" name="twelvetosixteenth_fname[]" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1">Last Name</label>
                                            <input type="text" id="<?php  echo 'lname'.$i;?>" name="twelvetosixteenth_lname[]" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                          </div>
                                        </div>
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1">Nationality</label>
                                        <select  style="width:100% !important;" data-rule-required="true" data-msg-required="required" name="twelvetosixteenth_nationality[]" class="set_country select2" id="<?php echo 'country'.$i; ?>"><option value="">Select Country</option>
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport No</label>
                                            <input type="text" id="<?php  echo 'pn'.$i;?>" name="twelvetosixteenth_passport_no[]" class="inputs_group" placeholder="Passport No" data-msg-required="Enter the passport no" data-rule-required="true">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">Passport Expired</label>
                                            <input type="text" id="<?php  echo 'pe'.$i;?>" name="twelvetosixteenth_passport_exp[]" class="inputs_group" placeholder="Passport Expired Date" data-msg-required="Enter the passport expiary date" data-rule-required="true">
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1">National ID</label>
                                            <input disabled type="text" id="<?php  echo 'nationality'.$i;?>" name="twelvetosixteenth_nationality[]" class="inputs_group" placeholder="Nationality" data-msg-required="Enter your nationality" data-rule-required="true">
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
                      <div class="col-md-8">
                      <h3>Billing Address</h3>
                      <div class="col-md-12 nopadding">
                        <ul class="timeline_1">
                          <li>
                            <div class="timeline-panel">
                              <div class="col-md-12 nopadding">
                                <div class="col-md-6 col-sm-6 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">First Name</label>
                                    <input type="text" name="bfirstname" class="inputs_group" placeholder="First Name" data-msg-required="Enter the first name" data-rule-required="true">
                                  </div>
                                </div>
                                <div class="col-md-6 col-sm-6 mn_nopadding_right">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Last Name</label>
                                    <input type="text" name="blastname" class="inputs_group" placeholder="Last Name" data-msg-required="Enter the last name" data-rule-required="true">
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-12 nopadding">
                                <div class="col-md-6  col-sm-6 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Street Address</label>
                                    <input type="text" name="address1" class="inputs_group" placeholder="Address1" data-msg-required="This field is required" data-rule-required="true">
                                  </div>
                                </div>
                                <div class="col-md-6 col-sm-6 mn_nopadding_right">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Address 2</label>
                                    <input type="text" name="address2" class="inputs_group" placeholder="Address2">
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-12 nopadding">
                                <div class="col-md-4 col-sm-4 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Email</label>
                                    <input type="text" name="email" class="inputs_group" placeholder="Email Id" data-msg-required="This field is required" data-rule-required="true">
                                  </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Mobile Number 1</label>
                                    <input type="text" name="mobile1" class="inputs_group" placeholder="Mobile Number 1" data-msg-required="This field is required" data-rule-required="true">
                                  </div>
                                </div>
                                    <div class="col-md-4 col-sm-4 mn_nopadding_right">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Mobile Number 2</label>
                                    <input type="text" name="mobile2" class="inputs_group" placeholder="Mobile Number 2" data-msg-required="This field is required" data-rule-required="true">
                                  </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Country</label>
                                    <select class="inputs_group" name="country" data-msg-required="This field is required" data-rule-required="true">
                                      <option  value="">Please select country</option>
                                      <?php if(!empty($countries)){ foreach($countries as $country) { ?>
                                      <option  value="<?php echo $country->country_en;?>"><?php echo $country->country_en;?></option>
                                      <?php } } ?>
                                     
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">State</label>
                                    <input type="text" name="state" class="inputs_group" placeholder="State" data-msg-required="This field is required" data-rule-required="true">
                                  </div>
                                </div>
                                 <div class="col-md-4 col-sm-4 mn_nopadding_right">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">City</label>
                                    <input type="text" name="city" class="inputs_group" placeholder="City" data-msg-required="This field is required" data-rule-required="true">
                                  </div>
                                </div>
                                <div class="col-md-4 col-sm-4 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Postal / Zip code</label>
                                    <input type="text" name="postal_code" class="inputs_group" placeholder="Postel Code" data-msg-required="This field is required" data-rule-required="true">
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
                          <button type="button" class="searchbtn next-step2">Next</button>
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
                    <!--</form>-->
                    <div class="tab-pane" role="tabpanel" id="step3">
                      <h3>Payment Details</h3>
                      <div class="col-md-12 nopadding">
                        <ul class="timeline_1">
                          <li>
                            <div class="timeline-panel">
                              <div class="col-xs-12 nopadding">
                                <input type="radio" class="css-checkbox" id="credit_card" name="radiog_lite">
                                <label class="css-label radGroup1" for="credit_card">Credit Card</label>
                                <input type="radio" class="css-checkbox" checked="checked" id="debit_card" name="radiog_lite">
                                <label class="css-label radGroup1" for="debit_card">Debit Card</label>
                                <input type="radio" class="css-checkbox" id="paypal" name="radiog_lite">
                                <label class="css-label radGroup1" for="paypal">Paypal</label>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-md-12 nopadding">
                                <div class="col-md-6 col-sm-6 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Name On Card</label>
                                    <input type="text" name="name_on_card" class="inputs_group" placeholder="Name On Card">
                                  </div>
                                </div>
                                <div class="col-md-6 col-sm-6 mn_nopadding_right">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Card Number</label>
                                    <input type="text" name="card_number" class="inputs_group" placeholder="Card Number">
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-12 nopadding">
                                <div class="col-md-3 col-sm-3 mn_nopadding_left">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Expiration Date</label>
                                    <input type="text" name="expiration_date" class="inputs_group" placeholder="DD">
                                  </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Expiration Year</label>
                                    <input type="text" name="year" class="inputs_group" placeholder="YYYY">
                                  </div>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                  <div class="form-group inputtext">
                                    <label class="search_label-1">Security Code</label>
                                    <input type="text" name="security_code" placeholder="CVV" class="inputs_group">
                                  </div>
                                </div>
                                <div class="col-md-3 col-sm-3 mn_nopadding_right">
                                  <div class="form-group inputtext"> <img style="margin:25px 0 0 0" width="50" height="29" alt="ccv" src="images/icon_ccv.gif"> <small>Last 3 digits</small> </div>
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
                          <button type="submit" class="searchbtn">Finish</button>
                        </li>
                      </ul>
                 
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </form>
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
