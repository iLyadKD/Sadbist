<!DOCTYPE html>

<html>
  <?php
    $include["css"][] = "bootstrap/bootstrap";
    $include["css"][] = "light-theme";
    $include["css"][] = "select2/select2";
    $include["css"][] = "jquery-ui/jquery-ui";
    $include["css"][] = "bootstrap-tags/bootstrap-tagsinput";
    $include["css"][] = "bootstrap-datetimepicker/bootstrap-datetimepicker.min";
    $include["css"][] = "bootstrap/bootstrap-switch";
    $this->load->view("common/header", $include);
  ?>
  <style>
  .form-horizontal .control-label {text-align: left;}

  .display_none{display: none;}
  .table tbody > tr > td{border: 0;}
   label.error{color: RED !important;font-weight: normal !important; }
   p{color: #000 !important;}
      .bootstrap-tagsinput {
  width: 100% !important;
}

.form-group .control-label.required:after, th.required:before {
  content:"*";
  color:#cc0000;
  margin-left: 2px;
  font-size: 12px;
}

.glyphicon {
    display: inline-block;
    font-family: "Glyphicons Halflings";
    font-style: normal;
    font-weight: normal;
    line-height: 1;
    position: relative;
    bottom : 25px;
    left: 270px;
    top: auto!important;
}

  .room_types_area{
    font-size:10px;
  }
 



   </style>
  <body class="fixed-header <?php echo get_menu_status() === '1' ? 'main-nav-closed' : 'main-nav-opened'; ?>">
    <?php $this->load->view("header");?>
    <div class="body-wrapper">
      <div class="main-nav-bg"></div>
      <?php $this->load->view("side-menu");?>
      
      <section class="body-content">
        <div class="container">
          <div class="row" class="body-content-wrapper">
            <div class="col-xs-12">
              <div class="row">
                <div class="col-sm-12">
                  <div class="page-header">
                    <h1 class="pull-left">
                      <i class="icon-plus-sign"></i>
                      <span><?php echo $this->data["page_title"]; ?></span>
                    </h1>
                    <div class="pull-right">
                      <ul class="breadcrumb">
                        <li>
                          <a href="<?php echo base_url(); ?>">
                            <i class="icon-bar-chart"></i>
                          </a>
                        </li>
                        <li class="separator">
                          <i class="icon-angle-right"></i>
                        </li>
                        <li>
                         <a href="<?php echo base_url('package'); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
                        </li>
                        <li class="separator">
                          <i class="icon-angle-right"></i>
                        </li>
                        <li class="active"><?php echo $this->data["page_title"]; ?></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div class="box"><div class="notification">
                  
                  <?php 
                     /* if($this->session->flashdata('status')){
                        $status = $this->session->flashdata('status');
                      ?>
                      <div class="alert alert-block alert-success">
                        <a href="#" data-dismiss="alert" class="close">×</a>
                        <h4 class="alert-heading">
                        <?php echo $status; ?>
                        </h4>
                      </div>
                    <?php }*/ ?>
                    
                  </div></div>
                  <div class="box">
                    <div class="box-header blue-background">
                      <div class="title"><?php echo $this->data["page_title"]; ?></div>
                    </div>

                    <div class="box-content">
                       <?php if($this->router->fetch_method() == "duplicate") { $false = ''; $readonly = '';  $url = "create";  $disabled =""; $yes=1;}else { $false = 'onclick="return false"'; $readonly = 'readonly';  $url="edit/".$id;  $disabled ="disabled";$yes=0;}?>
                      <form id="add_package" class="form form-horizontal add_package" action="<?php echo base_url(); ?>package/<?php echo $url; ?>" method="post"  enctype="multipart/form-data" class='cmxform form-horizontal validate-form'>               

                          <div class="form-group">
                            <label class="col-md-2 control-label required" > International / Domestic </label>
                            <div class="col-sm-4 controls">
                            <input type="radio" name="tour[iod]" data-rule-required='true' value='1' <?php if($tour->iod==1){ echo "checked";}?> > Domestic
                              <input type="radio" name="tour[iod]" data-rule-required='true' value='2' <?php if($tour->iod==2){ echo "checked";}?>> International
                              
                              <label for="tour[iod]" class="error"></label>
                            </div>
                              <label class="col-md-2 control-label required"  for='validation_current'>Tour ID</label>
                              <div class="col-sm-4 controls">
                                <input type="text" id="id_tour"  <?php echo $disabled;?>   class='form-control' type="text" name="tour[custom_tour_id]" value="<?php if($yes == 0) echo $tour->custom_tour_id;else echo '';?>"  placeholder="" data-rule-required='true'>  
                              <input type="hidden" id="id_flag">
                              <input type="hidden" id="request_flag" value="<?php echo $yes;?>">
                              <label class="error" id="tour_id_err"></label>
                              </div>
                          </div>

                          <div class="form-group">
                            
                           
                           <!-- <label class="col-md-2 control-label required" for='validation_name'>Tour Type</label>
                            <div class="col-sm-4 controls">
                              <select class='form-control select2' data-rule-required='true' name='tour[tour_type]' id="type"  >
                                <option value=''> Select Tour Type </option> 
                                <?php foreach ($package_type as $type_row) {  ?>
                                <option value='<?php echo $type_row->type_id; ?>' <?php if($type_row->type_id == $tour->tour_type) { echo "selected";}?>> <?php echo $type_row->package_type; ?>  </option> 
                                <?php }  ?>
                              </select>
                            </div>-->
                            <label class="col-md-2 control-label required"  for='validation_current'>Name(English)</label>
                            <div class="col-sm-4 controls">
                              <input type="text"   class='form-control' type="text" name="tour[tour_name_en]"  placeholder="" data-rule-required='true' value="<?php echo $tour->tour_name_en;?>" >  
                            </div>
                            <label class="col-md-2 control-label required"  for='validation_current'>Name(Farsi)</label>
                            <div class="col-sm-4 controls">
                              <input type="text"   class='form-control' type="text" name="tour[tour_name_fa]"  placeholder="" data-rule-required='true' value="<?php echo $tour->tour_name_fa;?>" >  
                            </div>
                            
                   
                          
                          </div>

                          <div class="form-group">
                              
                            <label class="col-md-2 control-label required"  for='validation_rating' >Destination Country</label>
                            <div class="col-sm-4 controls">
                      
                              <select name="tour[d_country]" data-rule-required='true' class="form-control select2 country">
                                <option value="" >Select Country</option>
                                <?php  foreach ($country as $rows) { ?>

                                <option value="<?php echo $rows->country_code;?>" <?php if($tour->d_country ==$rows->country_code )  { echo "selected";}?> ><?php echo $rows->name?></option>
                                <?php }?>
                              </select>
                            </div>
                             <label class="col-md-2 control-label required"  data-rule-required='true' for='validation_rating' >Destination City</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[d_city]" class="form-control select2 city">
                                   <option value="" >Select City</option>
                                  <?php  foreach ($d_city as $d_city_rows) { ?>
                                    <option value="<?php echo $d_city_rows->id;?>" <?php if($d_city_rows->id ==$tour->d_city )  { echo "selected";}?> ><?php echo $d_city_rows->city?></option>
                                 <?php }?>
                              </select>
                            </div>  
                                      
                            
                          </div>

                          <div class="form-group">               
                           
                            <label class="col-md-2 control-label required"  for='validation_rating' >Origination Country</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[o_country]" class="form-control select2 o_country">
                                <option value="" >Select Country</option>
                                <?php  foreach ($country as $rows) { ?>
                                <option value="<?php echo $rows->country_code?>" <?php if($tour->o_country ==$rows->country_code )  { echo "selected";}?> ><?php echo $rows->name?></option>
                                <?php }?>
                            </select>
                            </div>
                            
                            <label class="col-md-2 control-label required"  for='validation_rating' >Origination City</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[o_city]" class="form-control select2  o_city">
                                <option value="" >Select City</option>
                                 <?php  foreach ($o_city as $o_city_rows) { ?>
                                    <option value="<?php echo $o_city_rows->id;?>" <?php if($o_city_rows->id ==$tour->o_city )  { echo "selected";}?> ><?php echo $o_city_rows->city?></option>
                                 <?php }?>
                              </select>
                            </div>
                                
                           
                          </div>


                          <div class="form-group">
                               
                            <label class="col-md-2 control-label required"  for='validation_current'>Tour Start Date</label>
                            <div class="col-sm-4 controls"> 
                               <input <?php echo $disabled;?>  type="text"   class='form-control' type="text" name="tour[from_date]" id="fromdate" placeholder="yy-mm-dd" data-rule-required='true'  value="<?php echo $tour->from_date;?>">
                               <input type="hidden" name="exist_from[from_date]" value="<?php echo $tour->from_date;?>">
                            </div>
                            
                            <label class="col-md-2 control-label required"  for='validation_current'>Tour End Date</label>
                            <div class="col-sm-4 controls">  
                              <input <?php echo $disabled;?> type="text"   class='form-control' type="text" name="tour[to_date]" id="todate" placeholder="yy-mm-dd" data-rule-required='true'  value="<?php echo $tour->to_date;?>" >
                               <input type="hidden" name="exist_to[to_date]" value="<?php echo $tour->to_date;?>">
                            </div>

                     
                          </div>

                          <div class="form-group">
                          
                            <label class="col-md-2 control-label required"  for='validation_current'>No of the Nights</label>
                            <div class="col-sm-4 controls">
                              <input type="number" data-rule-min="0" data-rule-max="999"  min="0"   class='form-control' id="non" type="text" name="tour[no_of_night]"  placeholder="" data-rule-required='true' value="<?php echo $tour->no_of_night;?>" >
                            </div>
                            
                             <label class="col-md-2 control-label required"  for='validation_current'>No of Days</label>
                              <div class="col-sm-4 controls">
                                <input type="number"   class='form-control' id="nod" type="text" name="tour[no_of_day]" value="<?php echo $tour->no_of_day;?>"  placeholder="" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999"  min="0">
                              </div>  
                           
                          </div>
                            <div class="form-group">
                              
                              <input type="hidden" name="ex[overall_tour_price]" value="<?php echo $master_details->overall_tour_price;?>">
                            <label class="col-md-2 control-label required"  for='validation_current'>Base Price</label>
                            <div class="col-sm-4 controls">
                              <input <?php echo $readonly;?> type="text" id="base_price"   class='form-control money'  name="price[overall_tour_price]" data-rule-money="false"  value="<?php echo $master_details->overall_tour_price;?>" placeholder="Base price" data-rule-required='true'  >
                              <?php //if($yes == 1) { ?><input  type="hidden" id="hid_base_price" value="<?php if($master_details->flag_discount == 1) echo $master_details->discount_price; else echo $master_details->overall_tour_price;?>">
                              <input  type="hidden" id="hid_single_price" value="<?php  echo $master_details->single;?>">
                              <input  type="hidden" id="hid_triple_price" value="<?php  echo $master_details->triple;?>">
                              <input  type="hidden" id="hid_infants_price" value="<?php  echo $master_details->infants;?>">
                              <input  type="hidden" id="hid_twotosix_price" value="<?php  echo $master_details->twotosix;?>">
                              <input  type="hidden" id="hid_sixtotwelve_price" value="<?php  echo $master_details->sixtotwelve;?>">
                              <input  type="hidden" id="hid_twelvetosixteenth_price" value="<?php  echo $master_details->twelvetosixteenth;?>">
                              <input type="hidden" id="hid_handle_price" value="<?php  echo $master_details->handle_charge;?>">
                              
                              <input name="price[flag_discount]"  type="hidden"  value="<?php echo $master_details->flag_discount;?>">
                              
                              <?php //} ?>
                            </div>
                            <label class="col-md-2 control-label"  for='validation_current'>Discount Price</label>
                            <div class="col-sm-4 controls">
                              <input <?php echo $readonly;?> type="text" id="discount_price"  class='form-control money' value="<?php echo $master_details->discount_price;?>" data-rule-money="false"  name="price[discount_price]"  placeholder="Discount price"  >  
                            </div>
                            </div>


                          <div class="form-group">
                            <label class="col-md-2 control-label required"  for='validation_current'>Days of the week</label>
                            <div class="col-sm-9 controls">
                            <?php  $tour_weeks = explode(",", $tour->days_week) ;?>
                              <table class="table" >
                                <tr>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" id="checkAll" value="1" name="all_days" <?php if ( "1,2,3,4,5,6,7" == $tour->days_week) { echo "checked ";} ?> > All Days</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="1" name="tour[days_week][]" <?php if (in_array("1", $tour_weeks)) { echo " checked ";} ?> > Mon</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="2" name="tour[days_week][]" <?php if (in_array("2", $tour_weeks)) { echo " checked ";} ?> > Tue</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="3" name="tour[days_week][]" <?php if (in_array("3", $tour_weeks)) { echo " checked ";} ?> > Wed</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="4" name="tour[days_week][]" <?php if (in_array("4", $tour_weeks)) { echo " checked ";} ?> > Thur</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="5" name="tour[days_week][]" <?php if (in_array("5", $tour_weeks)) { echo " checked ";} ?> > Fri</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="6" name="tour[days_week][]" <?php if (in_array("6", $tour_weeks)) { echo " checked ";} ?>> Sat</label></td>
                                <td><label><input <?php echo $disabled;?>  type="checkbox" class="days" value="7" name="tour[days_week][]" <?php if (in_array("7", $tour_weeks)) { echo " checked ";} ?> > Sun</label></td>
                               </tr>
                              </table> 
                            </div>
                            <?php if($yes == 0) { ?><input type="hidden" name="exist_day[days_week][]" value="<?php echo $tour->days_week;?>"><?php } ?>
                          </div>

                          <div class="form-group">
                            <label class="col-md-1 control-label"  for='validation_current'>Insurance </label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4"> 
                                  <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[insurance]" value="1"  <?php if($tour->insurance==1){ echo "checked"; }?>  > 
                            
                              </div>
                              <div class="extra_box_div col-sm-8 <?php if($tour->insurance ==0){ echo "display_none"; }?>">
                                <input type="radio" name="insurance" class="price_include"   data-rule-required='true' value='2' <?php if($tour->insurance_price){ echo "checked"; }?> > Not Included 
                                <input type="radio" name="insurance" class="price_include"  data-rule-required='true' value='1' <?php if(empty($tour->insurance_price)){ echo "checked"; }?> > Included
                                <input type="text"   class='form-control <?php if(empty($tour->insurance_price)){ echo "display_none"; }?> money' type="text" data-rule-money="false" name="tour[insurance_price]" id="price_box" placeholder="Insurance (Price)" data-rule-required='true' value="<?php echo $tour->insurance_price?>">
                              </div>  
                            </div>     
                       
                            <label class="col-md-1 control-label"  for='validation_current'>visa</label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">
                               <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[visa]" value="1"  <?php if($tour->visa==1){ echo "checked"; }?>  >    

                              </div>
                              <div class="extra_box_div col-sm-8 <?php if($tour->visa ==0){ echo "display_none"; }?>">
                                <input type="radio" name="visa" id="visa"  class="price_include" data-rule-required='true' value='2' <?php if($tour->visa_price){ echo "checked"; }?>> Not Included 
                                <input type="radio" name="visa" id="visa" class="price_include" data-rule-required='true' value='1' <?php if(empty($tour->visa_price)){ echo "checked"; }?>> Included
                                <input data-rule-money="false" type="text" class='form-control <?php if(empty($tour->visa_price)){ echo "display_none"; }?> money' type="text" name="tour[visa_price]" id="price_box" placeholder="Visa(price)" data-rule-required='true' value="<?php echo $tour->visa_price?>">
                             </div>  
                            </div>     
                          </div>

                          <div class="form-group">
                            <label class="col-md-1 control-label"  for='validation_current'>Transfer </label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">
                              <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[transfer]" value="1"  <?php if($tour->transfer==1){ echo "checked"; }?>  >    
                               
                              </div>
                              <div class="extra_box_div col-sm-8 <?php if($tour->transfer==0){ echo "display_none"; }?>">
                                <input type="radio" name="transfer" class="price_include" id="price_include" data-rule-required='true' value='2' <?php if($tour->transfer_price){ echo "checked"; }?>> Not Included 
                                <input type="radio" name="transfer" class="price_include" id="price_include"data-rule-required='true' value='1' <?php if(empty($tour->transfer_price)){ echo "checked"; }?>> Included
                                <input type="text" data-rule-money="false"   class='form-control  <?php if(empty($tour->transfer_price)){ echo "display_none"; }?> money' type="text" name="tour[transfer_price]" id="price_box" placeholder="Transfer (Price)" data-rule-required='true' value="<?php echo $tour->transfer_price?>">
                              </div>  
                            </div>     
                          </div>

                          <div class="form-group">
                            <label class="col-md-1 control-label"  for='validation_current'>CIP OUT</label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">
                              <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[cipout]" value="1"  <?php if($tour->cipout==1){ echo "checked"; }?>  >    
                               
                              </div>
                              <div class="extra_box_div col-sm-8 <?php if($tour->cipout ==0){ echo "display_none"; }?>">
                                <input type="radio" name="cout" class="price_include" id="price_include" data-rule-required='true' value='2' <?php if($tour->cipout_price){ echo "checked"; }?>> Not Included 
                                <input type="radio" name="cout" class="price_include" id="price_include"data-rule-required='true' value='1' <?php if(empty($tour->cipout_price)){ echo "checked"; }?>> Included
                                <input type="text"  data-rule-money="false"  class='form-control <?php if(empty($tour->cipout_price)){ echo "display_none"; }?> money' type="text" name="tour[cipout_price]" id="price_box" placeholder="CIP OUT (Price)" data-rule-required='true' value="<?php echo $tour->cipout_price?>">
                               </div>   
                            </div>   

                            <label class="col-md-1 control-label"  for='validation_current'>CIP IN</label>
                              <div class="col-sm-5 controls">

                                <div class=" col-sm-4">

                                  <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[cipin]" value="1"  <?php if($tour->cipin==1){ echo "checked"; }?>  >    
                                </div>
                                <div class="extra_box_div col-sm-8 <?php if($tour->cipin ==0){ echo "display_none"; }?>">
                                  <input type="radio" name="cip_in" class="price_include" id="price_include" data-rule-required='true' value='2' <?php if($tour->cipin_price){ echo "checked"; }?>> Not Included 
                                  <input type="radio" name="cip_in" class="price_include" id="price_include" data-rule-required='true' value='1' <?php if(empty($tour->cipin_price)){ echo "checked"; }?>> Included
                                  <input type="text" data-rule-money="false"  class='form-control <?php if(empty($tour->cipin_price)){ echo "display_none"; }?> money' type="text" name="tour[cipin_price]" id="price_box" placeholder="CIP IN (Price)" data-rule-required='true' value="<?php echo $tour->cipin_price?>">
                                </div>   
                            </div>     
                          </div>

                      
                          <div class="form-group extra_com">
                            <label class="col-md-2 control-label"  for='validation_current'>Net / Commission</label>
                            <?php
                              $counter_bonus = $master_details->counter_bonus;
                              $master_details = $this->Package_model->master_details($id,2);
                            ?>
                            <div class="col-sm-4 controls" >
                              <div class="radio col-sm-4">
                                <label><input  <?php echo $readonly;?> type="radio" class="net_com"  name="price[price_type]"  value="NET" <?php if($master_details->percentage == 0 && $master_details->dollar == 0) { echo "checked";} ?>>Net</label>
                              </div>
                              <div class="radio col-sm-4">
                                <label><input <?php echo $readonly;?> type="radio" class="net_com"  name="price[price_type]" value="COMMISSION"  <?php if($master_details->percentage != 0 || $master_details->dollar != 0) { echo "checked";} ?> >Commission</label>
                              </div>                             
                              
                              
                               <div id="percentage_dollar" class="<?php if($master_details->percentage != 0 || $master_details->dollar != 0) echo ''; else echo 'display_none';?>">
                              <div class="radio col-sm-5">
                                  <input type="text" <?php echo $readonly;?> value="<?php echo $master_details->percentage;?>" id="percentage_box"   class='form-control money'  name="price[percentage]"  placeholder="%" data-rule-required='true' pattern="[0-9]*"  >
                              </div>
                              <div class="radio col-sm-5">
                                  <input type="text"  <?php echo $readonly;?> value="<?php echo $master_details->dollar;?>" id="dollar_box"   class='form-control money' data-rule-money="false"  name="price[dollar]"  placeholder="$" data-rule-required='true'  >
                              </div>
                              </div>
                              
                              
                            </div>  
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label"  for='validation_current'>Counter Bonus </label>
                            <div class="col-sm-4 controls">
                              <input <?php echo $readonly;?> type="text"   class='form-control money' type="text" name="master[counter_bonus]"  placeholder="counter bonus" value="<?php echo $counter_bonus;?>" data-rule-money="false">
                            </div>
                          </div>


                        <fieldset>
                        <legend>Tour Details:</legend>
                          <?php
                            $itinerary_en = '';
                            $itinerary_fa = '';
                            $inclusions_en = '';
                            $inclusions_fa = '';
                            $exclusions_en = '';
                            $exclusions_fa = '';
                            $privacy_policy_en = '';
                            $privacy_policy_fa = '';
                            $terms_conditions_en = '';
                            $terms_conditions_fa = '';
                            $cancellation_policy_en = '';
                            $cancellation_policy_fa = '';
                            
                            if($details->text_contents != ''){
                              //pr($details->text_contents);exit;
                              $text_contents = json_decode($details->text_contents);
                              
                              $itinerary_en = $text_contents->itinerary_en;
                              $itinerary_fa = $text_contents->itinerary_fa;
                              $inclusions_en = $text_contents->inclusions_en;
                              $inclusions_fa = $text_contents->inclusions_fa;
                              $exclusions_en = $text_contents->exclusions_en;
                              $exclusions_fa = $text_contents->exclusions_fa;
                              $privacy_policy_en = $text_contents->privacy_policy_en;
                              $privacy_policy_fa = $text_contents->privacy_policy_fa;
                              $terms_conditions_en = $text_contents->terms_conditions_en;
                              $terms_conditions_fa = $text_contents->terms_conditions_fa;
                              $cancellation_policy_en = $text_contents->cancellation_policy_en;
                              $cancellation_policy_fa = $text_contents->cancellation_policy_fa;
                            }
                          ?>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Itinerary(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                               <textarea id="itinerary_en"  name="details[itinerary_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $itinerary_en; ?></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Itinerary(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                               <textarea id="itinerary_fa" name="details[itinerary_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $itinerary_fa; ?></textarea>
                              </div>
                            </div>
                          </div>
                  
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Inclusions(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="inclusions_en" name="details[inclusions_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $inclusions_en; ?></textarea>        
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Inclusions(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="inclusions_fa" name="details[inclusions_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $inclusions_fa; ?></textarea>        
                              </div>
                            </div>
                          </div>
                      
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Exclusions(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="exclusions_en" name="details[exclusions_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $exclusions_en; ?></textarea>     
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Exclusions(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="exclusions_fa" name="details[exclusions_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $exclusions_fa; ?></textarea>     
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Privacy Policy(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="privacy_en" name="details[privacy_policy_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $privacy_policy_en; ?></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Privacy Policy(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="privacy_fa" name="details[privacy_policy_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $privacy_policy_fa; ?></textarea>
                              </div>
                            </div>
                          </div>
                    
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Terms & Conditions(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="terms_en" name="details[terms_conditions_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $terms_conditions_en; ?></textarea>     
                              </div>       
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Terms & Conditions(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="terms_fa" name="details[terms_conditions_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $terms_conditions_fa; ?></textarea>     
                              </div>       
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Cancellation policy(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="cancellation_en" name="details[cancellation_policy_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $cancellation_policy_en; ?></textarea>     
                              </div>       
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Cancellation policy(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="cancellation_fa" name="details[cancellation_policy_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $cancellation_policy_fa; ?></textarea>     
                              </div>       
                            </div>
                          </div>
                          
                        </fieldset>

                        <fieldset>
                          <legend>Tour Gallery </legend>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Add Tour gallery images</label>
                            <div class='col-md-10 controls'>
                            <?php if($yes == 0) { ?>
                            <div class='row'>
                              <div class='col-md-12'>
                                <?php  if($gallery){ foreach ($gallery as $gallery_row) { ?>
                                    <div class='col-md-3'>
                                      <img src="<?php echo upload_url('tour/'.$gallery_row->gallery_name); ?>" class="img-responsive">
                                    </div>
                                   <?php } }?>
                              </div>
                            </div>
                            <?php } ?>
                                <div class='col-md-4'>
                                    <br>
                                    <input  type="file" value="" class="form-control" name="gallery[image][]" data-rule-accept="image/*"  />
                                </div>
                            </div>
                          </div>

                        </fieldset> <br><br>
                        
                        <fieldset>
                          <legend>Brochure </legend>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Add / update Tour brochure (.pdf only)</label>
                            <div class='col-md-10 controls'>
                            <?php if($yes == 0) { ?>
                            <div class='row'>
                              <div class='col-md-12'>
                                <?php  if($file){ foreach ($file as $gallery_row) { ?>
                                    <div class='col-md-3'>
                                     <a target="_blank" href="<?php echo upload_url('tour/'.$gallery_row->file_name); ?>"><img src="<?php echo base_url("assets/images/icon_pdf.png"); ?>" class="img-responsive"><span style="position: relative;left:20px;bottom:18px;">(Tour brochure)</span></a> 
                                    </div>
                                   <?php } }?>
                              </div>
                            </div>
                            <?php } ?>
                                <div class='col-md-4'>
                                    <br>
                                    <input type="file" value="" class="form-control"  name="file[tour_file][]" data-rule-accept="application/pdf" />
                                </div>
                            </div>
                          </div>

                        </fieldset> <br><br>
                        
                        <fieldset>
                          <legend>Transportation </legend>
                          
                               <div> 
                                   
                             <?php if($flight){ foreach ($flight as $flight_row) { ?>
                              <div id="flight_det" style="background:#f9e0d9;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#EB4614;color:#FFF;padding:10px;" class="flight_number">Flight <a class="pull-right del_transport btn btn-success"  data-id="1" data-tour="<?php echo $tour->tour_id;?>"  data-transport="<?php echo $flight_row->tour_flight_id; ?>"  href="javascript:void(0)">Delete</a></h3>
                                <input <?php if($yes == 1) echo 'disabled'; else echo '';?> class="hd_flight"  type="hidden" name="flight[tour_flight_id][]" value="<?php echo $flight_row->tour_flight_id; ?>">

                                <div class="form-group">
                                   <label class="col-md-2 control-label"  for='validation_current'>Select Airline</label>
                                  <div class="col-sm-4 controls">
                                    <select name="flight[tour_airline_id][]" class="form-control select2">
                                <option value="" >Select Airline</option>
                                <?php  foreach ($airlines as $air) { ?>
                                <option <?php if($air->id == $flight_row->tour_airline_id) echo 'selected';?> value="<?php echo $air->id;?>" ><?php echo $air->airline_en;?>,&nbsp;&nbsp;<?php echo $air->airline_country;?></option>
                                <?php }?>
                              </select>
                                  </div>

                                    <label class="col-md-2 control-label"  for='validation_current'>Flight No</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[airline_no][]"  value="<?php echo $flight_row->airline_no; ?>" placeholder="Flight No" >
                                  </div>       
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[departuer_airport_en][]" value="<?php echo $flight_row->departuer_airport_en; ?>"  placeholder="Departuer Airport" data-rule-required='true'>
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[departuer_airport_fa][]" value="<?php echo $flight_row->departuer_airport_fa; ?>"  placeholder="Departuer Airport" data-rule-required='true'>
                                  </div>
                                </div>
                                
                                
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[arrival_airport_en][]" value="<?php echo $flight_row->arrival_airport_en; ?>"  placeholder="Arrival Airport" data-rule-required='true'>
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[arrival_airport_fa][]" value="<?php echo $flight_row->arrival_airport_fa; ?>"  placeholder="Arrival Airport" data-rule-required='true'>
                                  </div>  
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure time</label>
                                  <div class="col-sm-4 controls">
                                   
                                    <div class="input-group timepicker">
                                        <input name="flight[departuer_time][]"  data-format="hh:mm" type="text" class="form-control"   value="<?php echo $flight_row->departuer_time; ?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival-time</label>
                                  <div class="col-sm-4 controls">
                                   
                                      <div class="input-group timepicker">
                                        <input name="flight[arrival_time][]"  data-format="hh:mm" type="text" class="form-control"   value="<?php echo $flight_row->arrival_time; ?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[return_deapartur_en][]" value="<?php echo $flight_row->return_deapartur_en; ?>"  placeholder="Return Departure Airport" data-rule-required='true'>
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[return_deapartur_fa][]" value="<?php echo $flight_row->return_deapartur_fa; ?>"  placeholder="Return Departure Airport" data-rule-required='true'>
                                  </div>
                                </div>
                                  <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[return_arrival_en][]" value="<?php echo $flight_row->return_arrival_en; ?>"  placeholder="Return-Arrival Airport" data-rule-required='true'>
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[return_arrival_fa][]" value="<?php echo $flight_row->return_arrival_fa; ?>"  placeholder="Return-Arrival Airport" data-rule-required='true'>
                                  </div>   
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  time</label>
                                  <div class="col-sm-4 controls">
                                    
                                     <div class="input-group timepicker">
                                        <input name="flight[return_departure_time][]"  data-format="hh:mm" type="text" class="form-control"   value="<?php echo $flight_row->return_departure_time; ?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="flight[return_arrival_time][]"  data-format="hh:mm" type="text" class="form-control"   value="<?php echo $flight_row->return_arrival_time; ?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                  </div>     
                                </div>
                                
                                  <div class="form-group flight_incl">
                                    <label class="col-md-2 control-label"  for='validation_current'>Flight Price</label>
                                    <div class="col-sm-4 controls">
                                      <select  name="flight[flight_price_type][]" class="flight_inclusion form-control select2" >
                                      <option value="1" <?php if($flight_row->flight_price_type == 1) echo 'selected';?>>Included</option>
                                      <option value="0" <?php if($flight_row->flight_price_type == 0) echo 'selected';?>>Upgraded</option>
                                      </select>
                                    </div>
                                    <div  class="<?php if($flight_row->flight_price == '') echo 'display_none';?> fp">
                                      <div class="col-sm-4  controls">
                                        <input type="text" value="<?php echo $flight_row->flight_price;?>"   class='form-control money' name="flight[flight_price][]"   placeholder="Price" data-rule-money="false">
                                      </div>
                                    </div> <br/><br/>    
                                  </div>
                          
                                </div>
                                <?php } } ?>
                               <div class="display_none">

                               <div id="flight_det" style="background:#f9e0d9;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#EB4614;color:#FFF;padding:10px;" class="flight_number">Flight<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="1" value="Remove"></h3>
                                 <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Select Airline</label>
                                  <div class="col-sm-4 controls">
                                    <select name="flight[tour_airline_id][]" class="form-control select4">
                                <option value="" >Select Airline</option>
                                <?php  foreach ($airlines as $air) { ?>
                                <option value="<?php echo $air->id;?>" ><?php echo $air->airline_en;?>,&nbsp;&nbsp;<?php echo $air->airline_country;?></option>
                                <?php }?>
                              </select>
                                  </div>   

                                   <label class="col-md-2 control-label"  for='validation_current'>Airline No</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[airline_no][]"  placeholder="Airline No" data-rule-alphanumericval="false"  >
                                  </div>   

                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="flight[departuer_airport_en][]"  placeholder="Departuer-Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="flight[departuer_airport_fa][]"  placeholder="Departuer-Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[arrival_airport_en][]"  placeholder="Arrival Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[arrival_airport_fa][]"  placeholder="Arrival Airport" data-rule-alphanumericval="false"  >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input name="flight[departuer_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Departure Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input name="flight[arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Arrival Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[return_deapartur_en][]"  placeholder="Return Departuer Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="flight[return_deapartur_fa][]"  placeholder="Return Departuer Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="flight[return_arrival_en][]"  placeholder="Return Arrival Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="flight[return_arrival_fa][]"  placeholder="Return Arrival Airport" data-rule-alphanumericval="false"  >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="flight[return_departure_time][]"  data-format="hh:mm" type="text" class="form-control" placeholder="Return Departure  Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="flight[return_arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Return Arrival Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                      
                                  </div>     
                                </div>

                                <div class="form-group flight_incl">
                                  <label class="col-md-2 control-label"  for='validation_current'>Flight Price</label>
                                   <div class="col-sm-4 controls">
                                    <select  name="flight[flight_price_type][]" class="flight_inclusion form-control select2" >
                                        <option value="1">Included</option>
                                        <option value="0">Upgraded</option>
                                    </select>
                                  </div>
                                  <div class="display_none fp">
                                   <div class="col-sm-4  controls">
                                     <input type="text"   class='form-control money' name="flight[flight_price][]"   placeholder="Price" data-rule-money="false">
                                  </div>
                                  </div> <br/><br/>    
                                </div>


                                    </div>

                              </div>
                                  
                                        

                               <div class="add_more_transport_flight"></div>

                                <div class="form-group">
                                  <div class="col-sm-12" style="text-align:right">
                                   <input  type="button" class="btn btn-primary add_more_transport" data-id="1" value="Add More Flight">
                                 </div> 
                                 </div> 

                          
                        <!------------------Cruise START----------------------->
                        
                        
                         <?php if($cruise){ foreach ($cruise as $cruise_row) {  ?>
                        <div id="cruise_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                            <h3 style="background:#EB4614;color:#FFF;padding:10px;" class="flight_number">Ship liner<a class="pull-right del_transport btn btn-success"  data-id="2" data-tour="<?php echo $tour->tour_id;?>"  data-transport="<?php echo $cruise_row->tour_cruise_id; ?>"  href="javascript:void(0)">Delete</a></h3>
                            <div class="form-group">
                            <label class="col-md-2 control-label"  for='validation_current'>Cruise Name</label>
                            <div class="col-sm-4 controls">
                              <input <?php if($yes == 1) echo 'disabled'; else echo '';?> type="hidden"   class='form-control transport_ids' type="text" name="cruise[tour_cruise_id][]"  value="<?php echo $cruise_row->tour_cruise_id; ?>"  data-rule-required='true'>
                              <input type="text" class='form-control' type="text" name="cruise[cruise_name][]" value="<?php echo $cruise_row->cruise_name;?>"  placeholder="Cruise Name" data-rule-required='true'>
                            </div>   
                            
                            <label class="col-md-2 control-label">Cruise Number</label>
                            <div class="col-sm-4 controls">
                              <input type="text"   class='form-control' type="text" name="cruise[cruise_number][]" value="<?php echo $cruise_row->cruise_number;?>"  placeholder="Cruise Number" data-rule-required='true'>
                            </div>     
                            </div>

                              <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="cruise[departure_from_en][]"  value="<?php echo $cruise_row->departure_from_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="cruise[departure_from_fa][]"  value="<?php echo $cruise_row->departure_from_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>
                              </div>
                              <div class="form-group">
                          
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[arrival_cruise_en][]"  value="<?php echo $cruise_row->arrival_cruise_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[arrival_cruise_fa][]"  value="<?php echo $cruise_row->arrival_cruise_fa;?>" data-rule-alphanumericval="false"  >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input name="cruise[departure_time][]"  data-format="hh:mm" type="text" class="form-control" value="<?php echo $cruise_row->departure_time;?>">
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input name="cruise[arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  value="<?php echo $cruise_row->arrival_time;?>" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[return_deapartur_en][]"  value="<?php echo $cruise_row->return_deapartur_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[return_deapartur_fa][]"  value="<?php echo $cruise_row->return_deapartur_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return to (English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="cruise[return_arrival_en][]"  value="<?php echo $cruise_row->return_arrival_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="cruise[return_arrival_fa][]"  value="<?php echo $cruise_row->return_arrival_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="cruise[return_departure_time][]"  data-format="hh:mm" type="text" class="form-control" value="<?php echo $cruise_row->return_departure_time;?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="cruise[return_arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  value="<?php echo $cruise_row->return_arrival_time;?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>                                      
                                  </div>     
                                </div>



                                  <div class="form-group cruise_incl">
                                    <label class="col-md-2 control-label"  for='validation_current'>Cruise Price</label>
                                    <div class="col-sm-4 controls">
                                      <select  name="cruise[price_type][]" class="cruise_inclusion form-control" >
                                      <option value="1" <?php if($cruise_row->price_type == 1) echo 'selected';?>>Included</option>
                                      <option value="0" <?php if($cruise_row->price_type == 0) echo 'selected';?>>Upgraded</option>
                                      </select>
                                    </div>
                                    <div  class="<?php if($cruise_row->price == '') echo 'display_none';?> fp">
                                      <div class="col-sm-4  controls">
                                        <input type="text" value="<?php echo $cruise_row->price;?>"   class='form-control money' name="cruise[price][]"   placeholder="Price" data-rule-money="false">
                                      </div>
                                    </div> <br/><br/>    
                                  </div>


                        </div>
                      <?php } } ?>

                        <div class="display_none">
                              <div id="cruise_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#CCC;padding:10px ;" class="flight_number">Cruise<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="2" value="Remove"></h3>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Cruise Name</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text" class='form-control' type="text" name="cruise[cruise_name][]"   placeholder="Cruise Name" >
                                  </div>     

                                <label class="col-md-2 control-label"  for='validation_current'>Cruise Number</label>
                                  <div class="col-sm-4 controls">
                                   <input type="text"   class='form-control' type="text" name="cruise[cruise_number][]"   placeholder="Cruise Number" >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="cruise[departure_from_en][]"  placeholder="departure-cruise" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="cruise[departure_from_fa][]"  placeholder="departure-cruise" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">
                          
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[arrival_cruise_en][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[arrival_cruise_fa][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div>   
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input name="cruise[departure_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Departure Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input name="cruise[arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Arrival Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[return_deapartur_en][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="cruise[return_deapartur_fa][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="cruise[return_arrival_en][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="cruise[return_arrival_fa][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="cruise[return_departure_time][]"  data-format="hh:mm" type="text" class="form-control" placeholder="Return Departure  Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="cruise[return_arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Return Arrival Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                      
                                  </div>     
                                </div>


                                <div class="form-group cruise_incl">
                                  <label class="col-md-2 control-label"  for='validation_current'>Cruise Price</label>
                                   <div class="col-sm-4 controls">
                                    <select  name="cruise[price_type][]" class="cruise_inclusion form-control" >
                                        <option value="1">Included</option>
                                        <option value="0">Upgraded</option>
                                    </select>
                                  </div>
                                  <div class="display_none fp">
                                   <div class="col-sm-4  controls">
                                     <input type="text"   class='form-control money' name="cruise[price][]"   placeholder="Price" data-rule-money="false">
                                  </div>
                                  </div> <br/><br/>    
                                </div>       



                              </div>
                            </div>
              
                           
                              <div class="add_more_transport_cruise"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="2" value="Add More Ship liner">
                                </div> 
                             </div>
                        <!-------------------Cruise END------------------------>

                       <?php if($train){ foreach ($train as $train_row) { ?>
                        <div id="train_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                            <h3 style="background:#EB4614;color:#FFF;padding:10px;" class="flight_number">Train<a class="pull-right del_transport btn btn-success"  data-id="3" data-tour="<?php echo $tour->tour_id;?>"  data-transport="<?php echo $train_row->tour_train_id; ?>"  href="javascript:void(0)">Delete</a></h3>
                            <div class="form-group">
                            <label class="col-md-2 control-label"  for='validation_current'>Train Name</label>
                            <div class="col-sm-4 controls">
                              <input <?php if($yes == 1) echo 'disabled'; else echo '';?> type="hidden"   class='form-control transport_ids' type="text" name="train[tour_train_id][]"  value="<?php echo $train_row->tour_train_id; ?>"  data-rule-required='true'>
                              <input type="text" class='form-control' type="text" name="train[train_name][]" value="<?php echo $train_row->train_name;?>"  placeholder="Train Name" data-rule-required='true'>
                            </div>   
                            
                            <label class="col-md-2 control-label">Train Number</label>
                            <div class="col-sm-4 controls">
                              <input type="text"   class='form-control' type="text" name="train[train_number][]" value="<?php echo $train_row->train_number;?>"  placeholder="Train Number" data-rule-required='true'>
                            </div>     
                            </div>

                              <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="train[departure_from_en][]"  value="<?php echo $train_row->departure_from_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="train[departure_from_fa][]"  value="<?php echo $train_row->departure_from_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>
                              </div>
                              
                              <div class="form-group">
                          
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[arrival_train_en][]"  value="<?php echo $train_row->arrival_train_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[arrival_train_fa][]"  value="<?php echo $train_row->arrival_train_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>  
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input name="train[departure_time][]"  data-format="hh:mm" type="text" class="form-control" value="<?php echo $train_row->departure_time;?>">
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input name="train[arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  value="<?php echo $train_row->arrival_time;?>" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[return_deapartur_en][]"  value="<?php echo $train_row->return_deapartur_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[return_deapartur_fa][]"  value="<?php echo $train_row->return_deapartur_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="train[return_arrival_en][]"  value="<?php echo $train_row->return_arrival_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="train[return_arrival_fa][]"  value="<?php echo $train_row->return_arrival_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>  
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="train[return_departure_time][]"  data-format="hh:mm" type="text" class="form-control" value="<?php echo $train_row->return_departure_time;?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="train[return_arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  value="<?php echo $train_row->return_arrival_time;?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                      
                                  </div>     
                                </div>



                                 <div class="form-group train_incl">
                                    <label class="col-md-2 control-label"  for='validation_current'>Train Price</label>
                                    <div class="col-sm-4 controls">
                                      <select  name="train[price_type][]" class="train_inclusion form-control" >
                                      <option value="1" <?php if($train_row->price_type == 1) echo 'selected';?>>Included</option>
                                      <option value="0" <?php if($train_row->price_type == 0) echo 'selected';?>>Upgraded</option>
                                      </select>
                                    </div>
                                    <div  class="<?php if($train_row->price == '') echo 'display_none';?> fp">
                                      <div class="col-sm-4  controls">
                                        <input type="text" value="<?php echo $train_row->price;?>"   class='form-control money' name="train[price][]"   placeholder="Price" data-rule-money="false">
                                      </div>
                                    </div> <br/><br/>    
                                  </div>


                        </div>
                      <?php } } ?>

                        <div class="display_none">
                              <div id="train_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#CCC;padding:10px ;" class="flight_number">Train<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="3" value="Remove"></h3>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Train Name</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text" class='form-control' type="text" name="train[train_name][]"   placeholder="Train Name" >
                                  </div>     

                                <label class="col-md-2 control-label"  for='validation_current'>Train Number</label>
                                  <div class="col-sm-4 controls">
                                   <input type="text"   class='form-control' type="text" name="train[train_number][]"   placeholder="Train Number" >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="train[departure_from_en][]"  placeholder="departure-train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="train[departure_from_fa][]"  placeholder="departure-train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                
                                <div class="form-group">
                          
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[arrival_train_en][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[arrival_train_fa][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div>    
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input name="train[departure_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Departure Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input name="train[arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Arrival Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[return_deapartur_en][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="train[return_deapartur_fa][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                 <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="train[return_arrival_en][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="train[return_arrival_fa][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="train[return_departure_time][]"  data-format="hh:mm" type="text" class="form-control" placeholder="Return Departure  Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input name="train[return_arrival_time][]"  data-format="hh:mm" type="text" class="form-control"  placeholder="Return Arrival Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                      
                                  </div>     
                                </div>


                               <div class="form-group train_incl">
                                  <label class="col-md-2 control-label"  for='validation_current'>Train Price</label>
                                   <div class="col-sm-4 controls">
                                    <select  name="train[price_type][]" class="train_inclusion form-control" >
                                        <option value="1">Included</option>
                                        <option value="0">Upgraded</option>
                                    </select>
                                  </div>
                                  <div class="display_none fp">
                                   <div class="col-sm-4  controls">
                                     <input type="text"   class='form-control money' name="train[price][]"   placeholder="Price" data-rule-money="false">
                                  </div>
                                  </div> <br/><br/>    
                                </div>   



                              </div>
                            </div>
              
                           
                              <div class="add_more_transport_train"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="3" value="Add More Train">
                                </div> 
                             </div> 
                  
                            

                      
  											 <?php if($bus){ foreach ($bus as $bus_row) { ?>
                          <div id="bus_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                            <h3 style="background:#EB4614;color:#FFF;padding:10px;" class="flight_number">Bus<a class="pull-right del_transport btn btn-success"  data-id="4" data-tour="<?php echo $tour->tour_id;?>"  data-transport="<?php echo $bus_row->tour_bus_id; ?>"  href="javascript:void(0)">Delete</a></h3>

        											<!--<h3 style="background:#CCC;padding:10px ;" class="flight_number">Bus<a href="" class="pull-right btn btn-danger">Remove</a></h3>-->
        											<div class="form-group">
          											<label class="col-md-2 control-label"  for='validation_current'>Bus Name</label>
          											<div class="col-sm-4 controls">
          											   <input <?php if($yes == 1) echo 'disabled'; else echo '';?> type="hidden"   class='form-control transport_ids' type="text" name="bus[tour_bus_id][]"  value="<?php echo $bus_row->tour_bus_id; ?>"  data-rule-required='true'>
          											   <input type="text" class='form-control' type="text" name="bus[bus_name][]" value="<?php echo $bus_row->bus_name;?>"  placeholder="Bus Name" data-rule-required='true'>
          											</div>   
          											
        											 <label class="col-md-2 control-label">Bus Number</label>
      											   <div class="col-sm-4 controls">
      										    	 <input type="text"   class='form-control' type="text" name="bus[bus_number][]" value="<?php echo $bus_row->bus_number;?>"  placeholder="Bus Number" data-rule-required='true'>
      											   </div>     
        											</div>
                               <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="bus[departure_from_en][]" value="<?php echo $bus_row->departure_from_en;?>"  data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="bus[departure_from_fa][]" value="<?php echo $bus_row->departure_from_fa;?>"  data-rule-alphanumericval="false"  >
                                  </div>
                               </div>
                               
                               <div class="form-group">
                          
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[arrival_bus_en][]"  value="<?php echo $bus_row->arrival_bus_en;?>"  data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[arrival_bus_fa][]"  value="<?php echo $bus_row->arrival_bus_fa;?>"  data-rule-alphanumericval="false"  >
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input type="text"  class="form-control" name="bus[departure_time][]"  data-format="hh:mm"  value="<?php echo $bus_row->departure_time;?>" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input   type="text"  class="form-control" name="bus[arrival_time][]"  data-format="hh:mm" value="<?php echo $bus_row->arrival_time;?>" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[return_deaparture_en][]"  value="<?php echo $bus_row->return_deaparture_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return from(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[return_deaparture_fa][]"  value="<?php echo $bus_row->return_deaparture_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="bus[return_arrival_en][]" value="<?php echo $bus_row->return_arrival_en;?>" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return to(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="bus[return_arrival_fa][]" value="<?php echo $bus_row->return_arrival_fa;?>" data-rule-alphanumericval="false"  >
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input  type="text"  class="form-control" name="bus[return_departure_time][]"  data-format="hh:mm" value="<?php echo $bus_row->return_departure_time;?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input  type="text"  class="form-control" name="bus[return_arrival_time][]"  data-format="hh:mm" value="<?php echo $bus_row->return_arrival_time;?>" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                      
                                  </div>     
                                </div>

                                  <div class="form-group bus_incl">
                                    <label class="col-md-2 control-label"  for='validation_current'>Bus Price</label>
                                    <div class="col-sm-4 controls">
                                      <select  name="bus[price_type][]" class="bus_inclusion form-control" >
                                      <option value="1" <?php if($bus_row->price_type == 1) echo 'selected';?>>Included</option>
                                      <option value="0" <?php if($bus_row->price_type == 0) echo 'selected';?>>Upgraded</option>
                                      </select>
                                    </div>
                                    <div  class="<?php if($bus_row->price == '') echo 'display_none';?> fp">
                                      <div class="col-sm-4  controls">
                                        <input type="text" value="<?php echo $bus_row->price;?>"   class='form-control money' name="bus[price][]"   placeholder="Price" data-rule-money="false">
                                      </div>
                                    </div> <br/><br/>    
                                  </div>


                              </div>
  											   <?php } } ?>

                           <div class="display_none">
                              <div id="bus_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                              <h3 style="background:#CCC;padding:10px ;" class="flight_number">Bus<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="4" value="Remove"></h3>
                              <div class="form-group">
                                <label class="col-md-2 control-label"  for='validation_current'>Bus Name</label>
                                <div class="col-sm-4 controls">
                                  <input type="text" class='form-control' type="text" name="bus[bus_name][]"   placeholder="Bus Name" >
                                </div>   

                                <label class="col-md-2 control-label">Bus Number</label>
                                <div class="col-sm-4 controls">
                                  <input type="text"   class='form-control' type="text" name="bus[bus_number][]"   placeholder="Bus Number" >
                                </div>     
                                </div>

                                 <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="bus[departure_from_en][]"  placeholder="Departure City"  data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control ' type="text" name="bus[departure_from_fa][]"  placeholder="Departure City"  data-rule-alphanumericval="false"  >
                                  </div>
                                 </div>
                                 <div class="form-group">
                          
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[arrival_bus_en][]"  placeholder="Arrival City"  data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[arrival_bus_fa][]"  placeholder="Arrival City"  data-rule-alphanumericval="false"  >
                                  </div>   
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input type="text"  class="form-control" name="bus[departure_time][]"  data-format="hh:mm"   placeholder="Departure Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>

                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">

                                    <div class="input-group timepicker">
                                      <input   type="text"  class="form-control" name="bus[arrival_time][]"  data-format="hh:mm" placeholder="Arrival Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[return_deaparture_en][]"  placeholder="Return Departure City" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' type="text" name="bus[return_deaparture_fa][]"  placeholder="Return Departure City" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                 <div class="form-group">

                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="bus[return_arrival_en][]"  placeholder="Return Arrival City" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to(Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' type="text" name="bus[return_arrival_fa][]"  placeholder="Return Arrival City" data-rule-alphanumericval="false"  >
                                  </div>     
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return Departure  Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input  type="text"  class="form-control" name="bus[return_departure_time][]"  data-format="hh:mm" placeholder="Return Departure  Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Return Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                      <div class="input-group timepicker">
                                        <input  type="text"  class="form-control" name="bus[return_arrival_time][]"  data-format="hh:mm" placeholder="Return Arrival Time" >
                                        <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                      </div>

                                      
                                  </div>     
                                </div>

                                <div class="form-group bus_incl">
                                  <label class="col-md-2 control-label"  for='validation_current'>Bus Price</label>
                                   <div class="col-sm-4 controls">
                                    <select  name="bus[price_type][]" class="bus_inclusion form-control" >
                                        <option value="1">Included</option>
                                        <option value="0">Upgraded</option>
                                    </select>
                                  </div>
                                  <div class="display_none fp">
                                   <div class="col-sm-4  controls">
                                     <input type="text"   class='form-control money' name="bus[price][]"   placeholder="Price" data-rule-money="false">
                                  </div>
                                  </div> <br/><br/>    
                                </div>  

                              </div>
                            </div>
                

                        <div class="add_more_transport_bus"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="4" value="Add More Bus">
                                </div> 
                             </div>


										</div>



                             </fieldset>  


                     
                      <fieldset>
                      <legend>Hotel </legend> 
                      <?php
                        $hotel_ids = explode(",",$tour->hotel_id);
                        if($hotel){$j=0; $i=0;foreach ($hotel as $hotelrows) {
                          $i++;
                          $hotel_j = $hotel_ids[$j++];
                          $food_type =$this->Package_model->food_type($tour->tour_id,$hotel_j);
                          if($food_type->food_type == 0) $food_type->food_type = 1;
                            
                      ?>
                      
                
                      <div id="hotel_det" class="hotel_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                        <h3 style="background:#CCC;padding:10px ;" class="hotel_number">Hotel<a href="javascript:void(0)" class="btn btn-primary pull-right delete_hotel"  data-tour="<?php echo $tour->tour_id;?>" data-hotel="<?php echo $hotelrows->hotel_id;?>">Delete</a></h3>
                        <div class="form-group hotel_group">
                         <div class="col-md-12" style="margin-left: 150px;">
                            <label class="col-md-2 control-label required">Hotel Name </label>
                            <div class="col-sm-4 controls hotel_name">
                              <select   name="hotel[hotel_id][]" class="form-control hotel_id select2" readonly  data-rule-required='true' value="1" >
                           
                                <?php if($hotel){ foreach ($hotel_list as $hotel_listrows) {  ?>
                                <option   value="<?php echo $hotel_listrows->hotel_id; ?>" <?php if( $hotel_listrows->hotel_id == $hotel_j) { echo "selected"; } ?> ><?php echo $hotel_listrows->name_en; ?></option>
                                <?php } ?>
                                <?php } ?>
                              </select>
                            </div>                            
                          </div>
               
                        </div>  
                         <div class="hotel_price">
                        <div class="form-group">
                        <label class="col-md-2 control-label">Rating</label>  
                        <div class="col-sm-4 controls">
                        <input type="text"  class='form-control' type="text" name="hotel[rating]"  placeholder="Rating" value="<?php echo $hotelrows->rating;?>" readonly >
                        </div>
                          <label class="col-md-2 control-label">Neighbourhood area</label>
                          <div class="col-sm-4 controls">
                          <input type="text"   class='form-control' type="text" name="hotel[neighbours]"  placeholder="Neighbours" value="<?php echo $hotelrows->neighbours_en;?>" readonly >
                          </div>
                          
                          </div>
                        <div class="form-group">
                        <label class="col-md-2 control-label required"  for='validation_current'>Food Type</label>
                         <div class="col-sm-9 controls">
                           <table class="table" >
                             <tr>
                             
                             <td><label><input <?php echo $disabled;?> type="radio" data-id="<?php echo $hotel_j;?>" <?php if($food_type->food_type == 1) echo 'checked';?> class="radio_food" name="<?php echo $hotel_j;?>" data-rule-required='true' value='1'> BF</label></td>
                             <td><label><input <?php echo $disabled;?> type="radio" data-id="<?php echo $hotel_j;?>" <?php if($food_type->food_type == 4) echo 'checked';?> class="radio_food" name="<?php echo $hotel_j;?>" data-rule-required='true' value='4'> Breakfast and Lunch</label></td>
                             <td><label><input <?php echo $disabled;?> type="radio" data-id="<?php echo $hotel_j;?>" <?php if($food_type->food_type == 5) echo 'checked';?> class="radio_food" name="<?php echo $hotel_j;?>" data-rule-required='true' value='5'> Breakfast and Dinner</label></td>
                             
                           <td><label><input <?php echo $disabled;?> type="radio" data-id="<?php echo $hotel_j;?>" <?php if($food_type->food_type == 2) echo 'checked';?> class="radio_food" name="<?php echo $hotel_j;?>" data-rule-required='true' value='2'> ALL</label></td>
                           <td><label><input <?php echo $disabled;?> type="radio" data-id="<?php echo $hotel_j;?>" <?php if($food_type->food_type == 3) echo 'checked';?> class="radio_food" name="<?php echo $hotel_j;?>" data-rule-required='true' value='3'> UALL</label></td>

                             </tr>
                           </table>
                           <label for="gg" class="error"></label>
                         </div>
                         </div>
                        
                            
                                <div class="form-group">
                                  <div class="col-md-12">                        
                                    <table class="table table-bordered">
                                      <tr>&nbsp;</tr>
                                      <tr><th colspan="9" style="text-align:center;color:#008000;">Standard room price</th></tr>
                                    <tr>
                                      <th>&nbsp;</th>
                                      <th>Single Room</th>
                                      <th>Double Room</th>
                                      <th>Triple Room</th>
                                      <th>Infant</th>
                                      <th>Child (2-6)</th>
                                      <th>Child (6-12)</th>
                                      <th>Child (12-16)</th>
                                      <th>Handling Fee</th>
                                    </tr>
                                    <?php  $rprice =$this->Package_model->get_price($tour->tour_id,$hotel_j,1,0,'Standard'); 
                                          if($rprice) { foreach ($rprice as  $row) {  ?>
                                    <tr>
                                      <th>Retail</th>
                                      <th>
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo 'Standard'; ?>">
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo "استاندارد"; ?>">
                                       <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $row->extra_price_flag; ?>">
                                       <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                       <?php if($yes==0) { ?><input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $row->id; ?>"  data-rule-required='true'> <?php } ?>
                                      <input <?php echo $disabled;?>  type="hidden" class="form-control" value="1" name="price[price_type][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $hotelrows->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>   
                                      
                                      </th>
                                      <th>
                                       <input <?php echo $disabled;?> id="double_value"  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->doubles; ?>" name="price[doubles][]"   data-rule-required='true' data-rule-money="false"> 
                                       <input <?php echo $disabled;?>  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>
                                       </th>
                                       
                                      <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>      </th>
                                         <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                         </th>
                                       <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                    
                                      <th><input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Handling Fee" value="<?php echo $row->handle_charge; ?>" name="price[handle_charge][]"   data-rule-required='true' data-rule-money="false">      </th>
                                    </tr>
             
                                    <?php  }} ?>

                                     <?php   $price = $this->Package_model->get_price($tour->tour_id,$hotel_j,2,0,'Standard');if($price) { foreach ($price as  $row) { ?>
                                    <tr>
                                      <th>Cost</th>
                                        <th>
                                          <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo 'Standard'; ?>">
                                          <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo "استاندارد"; ?>">
                                          
                                      <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $row->extra_price_flag; ?>">
                                      <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                      <?php if($yes==0) { ?><input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $row->id; ?>"  data-rule-required='true'> <?php } ?>
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="2" name="price[price_type][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $hotelrows->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>  
                                        </th>
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->doubles; ?>" name="price[doubles][]" id="double_cost_value"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>
                                      </th>
                                    
                                       
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th><input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Handling Fee" value="<?php echo $row->handle_charge; ?>" name="price[handle_charge][]"   data-rule-required='true'>      </th>
                                      
                                    </tr>
                                        <?php  }} ?>

                                        
                                        
                                    <?php 
                                      $erprice = $this->Package_model->get_price($tour->tour_id,$hotel_j,1,1,'Standard');
                                      if($erprice) { foreach ($erprice as  $extra_row) {
                                        $rm_type_name = $extra_row->room_type;
                                        $id_code = $hotel_j.str_replace( ' ', '', $rm_type_name);
                                      ?>

                                    <tr>
                                    <th colspan="9">Extra Night Price  <?php //if($yes == 1) { ?><span><input data-id="<?php echo $id_code;?>" class="extra_checkbox"  type="checkbox"></span><?php //} ?></th>
                                    </tr>
                                    <tr  class="extra_price_cost display_none" id="retail<?php echo $id_code;?>">
                                        <th>Retail</th>
                                          <th>
                                            <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo 'Standard'; ?>">
                                            <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo "استاندارد"; ?>">
                                      <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $extra_row->extra_price_flag; ?>">
                                      <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                      <?php if($yes==0) { ?>  <input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $extra_row->id; ?>"  data-rule-required='true'> <?php } ?>
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="1" name="price[price_type][]"   data-rule-required='true'> 
                                      <input  <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $extra_row->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?> type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>      </th>
                                        <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->doubles; ?>" name="price[doubles][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?> type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>      </th>
                            
                                       
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?> type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>      </th>
                                               <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                               </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                              
                                      <th><input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Handling Fee" value="<?php echo $extra_row->handle_charge; ?>" name="price[handle_charge][]"   data-rule-required='true' data-rule-money="false">      </th>
                                    </tr>
                                        <?php }}  ?>
                                    <?php 
                                      $eprice = $this->Package_model->get_price($tour->tour_id,$hotel_j,2,1,'Standard');    
                                      if($eprice){
                                      foreach ($eprice as  $extra_row) { ?>
                                    <tr  class="extra_price_price display_none" id="cost<?php echo $id_code;?>">
                                      <th>Cost</th>
                                             <th>
                                              <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo 'Standard'; ?>">
                                              <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo "استاندارد"; ?>">
                                       <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $extra_row->extra_price_flag; ?>">
                                       <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                       <?php if($yes==0) { ?> <input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $extra_row->id; ?>"  data-rule-required='true'>  <?php } ?>
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="2" name="price[price_type][]"  data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $extra_row->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
                                     
                                       <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false">
                                       <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $extra_row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>
                                             </th>
                                      <th>
                                       <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->doubles; ?>" name="price[doubles][]"   data-rule-required='true' data-rule-money="false">
                                       <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $extra_row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>
                                       
                                       </th>
                               
                                      
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $extra_row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>  
                                      </th>
                                         <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input  <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true'> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                         <th><input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Handling Fee"  name="price[handle_charge][]"   data-rule-required='true' value="<?php echo $extra_row->handle_charge; ?>">      </th>
                                    </tr>
                                    <?php }}  ?>
                                    
                                    <!----------Room Type Price START----------->
                                    <tr><th colspan="9" style="text-align: center;">Other Room Types <span><input data-id="<?php echo $hotel_j;?>" class="other_type" type="checkbox"></span></th></tr>
                                    <?php
                                      //$get_room_types = $this->Hotel_model->get_room_types($hotel_j);
                                      //
                                      //if(!empty($get_room_types)){
                                      //  $get_room_types = json_decode($get_room_types->room_types);
                                      //  pr($get_room_types);exit;
                                      //  $datas = [];
                                      //  $en_data = [];
                                      //  foreach($get_room_types as $fn){
                                      //      $fn = explode("en->",$fn);
                                      //      $datas[] = $fn[1];
                                      //  }
                                      //  foreach($datas as $d){
                                      //      $dn = explode("--",$d);
                                      //      $en_data[] = $dn[0];
                                      //  }
                                      //  
                                      //  $get_room_types = $en_data;
                                      //}else{
                                      //   $get_room_types = '';
                                      //}
                                      
                                    $find_room_types = $this->Hotel_model->find_room_types($hotel_j);
                                    if($find_room_types->room_types != ''){
                                    $find_room_types = json_decode($find_room_types->room_types);
                                    $final_datas = [];
                                    
                                    
                                    foreach($find_room_types as $d){
                                    $dn = explode("en->",$d);
                                    $get_str = explode("--fa->",$dn[1]);
                                    $get_str = implode("/",$get_str);
                                    $final_datas[] = $get_str;
                                    }
                                    
                                    
                                    $room_types = $final_datas;
                                    }else{
                                    $room_types = '';
                                    }
                                      
                                    ?>
                                    
                                    <?php
                                      
                                      if($room_types != ''){
                                        for($g=0;$g< count($room_types);$g++){
                                          $value = explode("/",$room_types[$g]);
                                          $value_en = $value[0];
                                          $value_fa = $value[1];
                                    ?>
                                    
                                    
                                    <?php  $rprice =$this->Package_model->get_price($tour->tour_id,$hotel_j,1,0,$value_en);
                                          if($rprice) { foreach ($rprice as  $row) {
                                            $rm_type_name = $row->room_type;
                                            $id_code = $hotel_j.str_replace( ' ', '', $rm_type_name);
                                            ?>
                                    <tr><td>&nbsp;</td></tr>
                                    
         <tr class="other_room_type<?php echo $hotel_j;?> display_none"><th colspan="9" style="text-align:center;color:#4d4dff;"><?php echo $value_en;?> price <span><input data-id="<?php echo $id_code;?>" class="price_checkbox" type="checkbox"></span></th></tr>
                                    <tr  class="display_none" id="genr<?php echo $id_code;?>">
                                      <th>Retail</th>
                                      <th>
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo $value_en; ?>">
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo $value_fa; ?>">
                                       <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $row->extra_price_flag; ?>">
                                       <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                       <?php if($yes==0) { ?><input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $row->id; ?>"  data-rule-required='true'> <?php } ?>
                                      <input <?php echo $disabled;?>  type="hidden" class="form-control" value="1" name="price[price_type][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $hotelrows->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>   
                                      
                                      </th>
                                      <th>
                                       <input <?php echo $disabled;?> id="double_value"  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->doubles; ?>" name="price[doubles][]"   data-rule-required='true' data-rule-money="false"> 
                                       <input <?php echo $disabled;?>  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>
                                       </th>
                                       
                                      <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>      </th>
                                         <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                         </th>
                                       <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                    
                                      <th><input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Handling Fee" value="<?php echo $row->handle_charge; ?>" name="price[handle_charge][]"   data-rule-required='true' data-rule-money="false">      </th>
                                    </tr>
             
                                    <?php  }} ?>

                                     <?php   $price = $this->Package_model->get_price($tour->tour_id,$hotel_j,2,0,$value_en);if($price) { foreach ($price as  $row) { ?>
                                    <tr  class="display_none" id="genc<?php echo $id_code;?>">
                                      <th>Cost</th>
                                        <th>
                                          <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo $value_en; ?>">
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo $value_fa; ?>">
                                      <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $row->extra_price_flag; ?>">
                                      <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                      <?php if($yes==0) { ?><input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $row->id; ?>"  data-rule-required='true'> <?php } ?>
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="2" name="price[price_type][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $hotelrows->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'> 
                                        </th>
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->doubles; ?>" name="price[doubles][]" id="double_cost_value"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>
                                      </th>
                                    
                                       
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false">
                                       <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>
                                      </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th><input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Handling Fee" value="<?php echo $row->handle_charge; ?>" name="price[handle_charge][]"   data-rule-required='true'>      </th>
                                      
                                    </tr>
                                        <?php  }} ?>

                                        
                                        
                                    <?php 
                                      $erprice = $this->Package_model->get_price($tour->tour_id,$hotel_j,1,1,$value_en);
                                      if($erprice) { foreach ($erprice as  $extra_row) {
                                          $rm_type_name = $extra_row->room_type;
                                          $id_code = $hotel_j.str_replace( ' ', '', $rm_type_name); 
                                      ?>

                                     <tr style="display: none;" id="enight<?php echo $id_code;?>">
                                    <th colspan="9">Extra Night Price  <?php //if($yes == 1) { ?><span><input data-id="<?php echo $id_code;?>" class="extra_checkbox"  type="checkbox"></span><?php //} ?></th>
                                    </tr>
                                    <tr  class="extra_price_cost display_none" id="retail<?php echo $id_code;?>">
                                        <th>Retail</th>
                                          <th>
                                            <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo $value_en; ?>">
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo $value_fa; ?>">
                                      <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $extra_row->extra_price_flag; ?>">
                                      <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                      <?php if($yes==0) { ?>  <input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $extra_row->id; ?>"  data-rule-required='true'> <?php } ?>
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="1" name="price[price_type][]"   data-rule-required='true'> 
                                      <input  <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $extra_row->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
                                      <input <?php echo $disabled;?>  type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?> type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>      </th>
                                        <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->doubles; ?>" name="price[doubles][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?> type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>      </th>
                            
                                       
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false"> 
                                      <input <?php echo $disabled;?> type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>      </th>
                                               <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                               </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                              
                                      <th><input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Handling Fee" value="<?php echo $extra_row->handle_charge; ?>" name="price[handle_charge][]"   data-rule-required='true' data-rule-money="false">      </th>
                                    </tr>
                                        <?php }}  ?>
                                    <?php 
                                      $eprice = $this->Package_model->get_price($tour->tour_id,$hotel_j,2,1,$value_en);    
                                      if($eprice){
                                      foreach ($eprice as  $extra_row) { ?>
                                    <tr  class="extra_price_price display_none" id="cost<?php echo $id_code;?>">
                                      <th>Cost</th>
                                             <th>
                                              <input <?php echo $disabled;?> type="hidden" name="price[room_type_en][]" value="<?php echo $value_en; ?>">
                                        <input <?php echo $disabled;?> type="hidden" name="price[room_type_fa][]" value="<?php echo $value_fa; ?>">
                                       <input <?php echo $disabled;?> type="hidden" name="price[extra_price_flag][]" value="<?php echo $extra_row->extra_price_flag; ?>">
                                       <input type="hidden" class="form-control food_type<?php echo $hotel_j;?>"   name="price[food_type][]" value="<?php echo $food_type->food_type?>">
                                       <?php if($yes==0) { ?> <input <?php echo $disabled;?> type="hidden" class="form-control" name="price[id][]"  value="<?php echo $extra_row->id; ?>"  data-rule-required='true'>  <?php } ?>
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="2" name="price[price_type][]"  data-rule-required='true'> 
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" value="<?php echo $extra_row->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
                                     
                                       <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->single; ?>" name="price[single][]"   data-rule-required='true' data-rule-money="false">
                                       <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $extra_row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>
                                             </th>
                                      <th>
                                       <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->doubles; ?>" name="price[doubles][]"   data-rule-required='true' data-rule-money="false">
                                       <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $extra_row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'> 
                                       
                                       </th>
                               
                                      
                                      <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->triple; ?>" name="price[triple][]"   data-rule-required='true' data-rule-money="false">
                                      <input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Availability" value="<?php echo $extra_row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'> 
                                      </th>
                                         <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->infants; ?>" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input  <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th>
                                      <input <?php echo $disabled;?> type="text" class="form-control money" placeholder="Cost" value="<?php echo $extra_row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false"> 
                                       </th>
                                       <th><input <?php echo $disabled;?> type="hidden" class="form-control" placeholder="Handling Fee"  name="price[handle_charge][]"   data-rule-required='true' value="<?php echo $extra_row->handle_charge; ?>">      </th>
                                     
                                    </tr>
                                    <?php }}  ?>
                                    
                                    <?php } } ?>
                                    
                                 <!----------Room Type Price END----------->   
                                    </table>
                                  </div>
                                </div>
                                      </div>
                      </div>
                     

                            <?php } }?>

                          <div class="add_more_hotel_place"></div>



                           


                      <p class="text-right" >
                        <?php if($yes == 0) { ?><a href="<?php echo base_url('package/update_master_price/'.$id.'/'.base64_encode($tour->hotel_id).'/'.base64_encode($tour->default_hotel).'/'.base64_encode($tour->days_week));?>">Update price</a><?php } ?>
                      &nbsp;&nbsp;<input  type="button" class="btn btn-primary  add_more_hotel" value="Add More Hotel"></p>
                    </fieldset>
                         <fieldset>
                        <legend>Vendor Details:</legend>
                        <label class="col-md-2 control-label required" for='validation_name'>Vendor List</label>
                            <div class="col-sm-4 controls">
                              <select class='form-control select2 list_vendor' data-rule-required='true' name='tour[vendor_id]'  >
                                <option value=''> Select vendor</option> 
                                <?php foreach ($vendors as $vendor) {
                                  $v_id = $vendor->id;
                                   $v_name = $vendor->vendor_name_en;
                                   $v_contact_person = $vendor->contact_person_name_en;
                                   $v_web_address = $vendor->web_address;
                                   $v_mobile = $vendor->mobile_no;
                                   $v_login = $vendor->login_id;
                                   $v_pwd = $vendor->password;
                                  
                                  ?>
                                
                               <option data-foo="<?php echo $v_name.'::!!::'.$v_contact_person.'::!!::'.$v_web_address.'::!!::'.$v_mobile.'::!!::'.$v_login.'::!!::'.$v_pwd.'::!!::';?>" <?php if($v_id == $tour->vendor_id) echo 'selected';?> value='<?php echo $v_id; ?>'> <?php echo $v_name; ?>  </option> 
                                <?php }  ?>
                              </select>
                            </div>
                             <div id="vendor_details" class="col-sm-5 controls display_none" style="height:100%;">
                              <div style="background-color:#f0f5f5;padding:10px;text-align:left;font-style:italic;position: relative;height:100%;" class="form-control valid" type="text"  placeholder="address" data-rule-required="true">
                             <div style="margin-bottom: 15px;" id="v_name"></div> 
                             <div style="margin-bottom: 15px;" id="v_contact_person"></div>
                            <div style="margin-bottom: 15px;" id="w_address"></div>
                             <div style="margin-bottom: 15px;" id="log_id"></div>
                            <div style="margin-bottom: 15px;" id="pwd"></div>
                             <div id="v_mobile"></div>
                              </div>
                            </div>    
                      </fieldset>
                      <br /><br />
                      
                      

                      <div class='form-actions' style='margin-bottom:0'>
                        <div class='row'>
                          <div class='col-sm-9 col-sm-offset-3'>
                            <button class='btn btn-primary tour_submit' name="create" type='submit'> <i class='icon-save'></i> <?php if($yes == 0) echo 'Update';else echo 'Submit'; ?> Tour</button>
                             <img style="display: none;" class="tour_loader" src="<?php echo base_url().'assets/images/loading.gif';?>">
                          </div>
                        </div>
                      </div>

                        </form>
                

                 <div  class="display_none">
                            <div id="hotel_det" class="hotel_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                              <h3 style="background:#CCC;padding:10px ;" class="hotel_number">Hotel</h3>
                              <div class="form-group hotel_group">
                                <div class="col-md-12">
                                  <label class="col-md-2 control-label">Hotel Name </label>
                                  <div class="col-sm-4 controls hotel_name">
                                    <select name="hotel[hotel_id][]" class="form-control hotel_id select2"  data-rule-required='true' >
                                      <option value="" >Select Hotel</option>
                                      <?php if($hotel_list){ foreach ($hotel_list as $hotelrows) {?>
                                      <option value="<?php echo $hotelrows->hotel_id; ?>" ><?php echo $hotelrows->name; ?></option>
                                      <?php } } ?>
                                    </select>
                                  </div>
                                  
                                   <label class="col-md-2 control-label required">Room Type</label>
                            <div class="col-sm-4 controls">
                            <select  name="hotel[room_type][]" class="form-control room_type select2" id="room_type" data-rule-required='true'>
                                <?php foreach ($room_type as $type_row) { $type = json_decode($type_row->room_type) ?>
                                <option value='<?php echo $type_row->type_id; ?>'> <?php echo $type->english; ?>  </option> 
                                <?php }  ?>
                            </select>
                            </div>

                                </div>
                              </div>  
                               <div class="hotel_price"></div>

                             </div>
                             </div>
                     
                    </div>
                  </div>
                </div>
              </div>
                  
            </div>
          </div>
          <?php $this->load->view("footer");?>
        </div>
      </section>
    </div>
    <?php $this->load->view("common/scripts");?>

    
  </body>
</html>