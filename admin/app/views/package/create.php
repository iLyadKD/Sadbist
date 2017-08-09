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
  

  <body class="fixed-header <?php echo get_menu_status() === '1' ? 'main-nav-closed' : 'main-nav-opened'; ?>">
  <script src="<?php echo base_url('assets/js/jquery/jquery.min.js');?>"></script>
  
    <style>
  .form-horizontal .control-label {text-align: left;}
  .toggle-switch {margin-top: 8px;}
  .extra_box_div{display: none;}
  .display_none{display: none;}
  .table tbody > tr > td{border: 0;}
   label.error{color: RED !important;font-weight: normal !important; }
   .field-error{ border: 1px solid #FFF;}
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
table.days_price_table {background-color:transparent;border-collapse:collapse;width:100%;}
table.days_price_table th, table.days_price_table td {text-align:center;border:1px solid black;padding:5px;}
table.days_price_table th {background-color:AntiqueWhite;}

.div_price {
  padding: 10px;
  margin: 15px;
  display: none;
}



  </style>
  
    <?php $this->load->view("header");?>
    <div class="body-wrapper">
      <div class="main-nav-bg"></div>
      <?php $this->load->view("side-menu");?>
      
      <section class="body-content">
        <div class="container">
          <div class="row">
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
                  <div class="box"><div class="notification"></div></div>
                  <div class="box">
                    <div class="box-header blue-background">
                      <div class="title"><?php echo $this->data["page_title"]; ?></div>
                    </div>

                    <div class="box-content">

                      <form id="add_package" class="form form-horizontal add_package" action="<?php echo base_url(); ?>package/create" method="post"  enctype="multipart/form-data" >               
                          <div class="form-group">
                            <label class="col-md-2 control-label required" > International / Domestic </label>
                            <div class="col-sm-4 controls">
                             <input type="radio" name="tour[iod]" data-rule-required='true' value='1'> Domestic
                              <input type="radio" name="tour[iod]" data-rule-required='true' value='2'> International
                              
                              <label for="tour[iod]" class="error"></label>
                            </div>
                               <label class="col-md-2 control-label required"  for='validation_current'>Tour ID</label>
                            <div class="col-sm-4 controls">
                              <input type="text" id="id_tour"   class='form-control'  name="tour[custom_tour_id]"  placeholder="" data-rule-required='true'>
                              <input type="hidden" id="id_flag">
                              <input type="hidden" id="request_flag" value="1">
                              <label class="error" id="tour_id_err"></label>
                            </div>
                          </div>
                          <div class="form-group">
                       
                             <label class="col-md-2 control-label required"  for='validation_current'>Name(English)</label>
                            <div class="col-sm-4 controls">
                              <input type="text"   class='form-control'  name="tour[tour_name_en]"  placeholder="" data-rule-required='true'  data-rule-alphanumericval="false" >  
                            </div>
                             <label class="col-md-2 control-label required"  for='validation_current'>Name(Farsi)</label>
                            <div class="col-sm-4 controls">
                              <input type="text"   class='form-control'  name="tour[tour_name_fa]"  placeholder="" data-rule-required='true'  data-rule-alphanumericval="false" >  
                            </div>
                            
                          </div>

                          <div class="form-group">               
                            <label class="col-md-2 control-label required"  for='validation_rating' >Destination Country</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[d_country]" class="form-control select2 country" data-rule-required='true'>
                                <option value="" >Select Country</option>
                                <?php  foreach ($country as $rows) { ?>
                                <option value="<?php echo $rows->country_code?>" ><?php echo $rows->name?></option>
                                <?php }?>
                              </select>
                            </div>
                             <label class="col-md-2 control-label required"  for='validation_rating' >Destination City</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[d_city]" class="form-control select2 city" data-rule-required='true'>
                                    <option value=""> Select City</option>                         
                              </select>
                            </div> 
                          </div>
                          <div class="form-group">               
                            <label class="col-md-2 control-label required"  for='validation_rating' >Origination Country</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[o_country]" class="form-control select2 o_country" data-rule-required='true'>
                                <option value="" >Select Country</option>
                                <?php  foreach ($country as $rows) { ?>
                                <option value="<?php echo $rows->country_code?>" ><?php echo $rows->name?></option>
                                <?php }?>
                            </select>
                            </div>
                             <label class="col-md-2 control-label required"  for='validation_rating' >Origination City</label>
                            <div class="col-sm-4 controls">
                              <select name="tour[o_city]" class="form-control select2 o_city" data-rule-required='true'>
                                <option value=""> Select City</option>
                              </select>
                            </div>  
                                
                           
                          </div>


                          <div class="form-group">
                            
                            
                            <label class="col-md-2 control-label required"  for='validation_current'>Tour Start Date</label>
                            <div class="col-sm-4 controls"> 
                               <input type="text"   class='form-control'  name="tour[from_date]" id="fromdate" placeholder="yy-mm-dd" data-rule-required='true'>
                            </div>
                             <label class="col-md-2 control-label required"  for='validation_current'>Tour End Date</label>
                            <div class="col-sm-4 controls">  
                              <input type="text"   class='form-control'  name="tour[to_date]" id="todate" placeholder="yy-mm-dd" data-rule-required='true' >
                            </div>

                    
                          </div>

                          <div class="form-group">
                             <label class="col-md-2 control-label required"  for='validation_current'>No of Nights</label>
                              <div class="col-sm-4 controls">
                                <input type="number" id="non"   class='form-control'  type="text" name="tour[no_of_night]"  placeholder="" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999"  min="0">
                              </div>
                              
                               <label class="col-md-2 control-label required"  for='validation_current'>No of Days</label>
                              <div class="col-sm-4 controls">
                                <input type="number" id="nod"  class='form-control'  name="tour[no_of_day]"  placeholder="" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999"  min="0" >
                              </div>  
                           
                          </div>
                            <div class="form-group">
                            <label class="col-md-2 control-label required"  for='validation_current'>Base Price</label>
                            <div class="col-sm-4 controls">
                              <input type="text" id="base_price"   class='form-control money'  name="price[overall_tour_price]"  placeholder="Base price" data-rule-required='true' data-rule-money="false"  >  
                              <input type="hidden" id="hid_base_price">
                              <input type="hidden" id="hid_cost_price">
                              
                              <input type="hidden" id="hid_single_price">
                              <input type="hidden" id="hid_single_cprice">
                              
                              <input type="hidden" id="hid_triple_price">
                              <input type="hidden" id="hid_triple_cprice">
                              
                              <input type="hidden" id="hid_infants_price">
                              <input type="hidden" id="hid_infants_cprice">
                              
                              <input type="hidden" id="hid_twotosix_price">
                              <input type="hidden" id="hid_twotosix_cprice">
                              
                              <input type="hidden" id="hid_sixtotwelve_price">
                              <input type="hidden" id="hid_sixtotwelve_cprice">
                              
                              <input type="hidden" id="hid_twelvetosixteenth_price">
                              <input type="hidden" id="hid_twelvetosixteenth_cprice">
                              
                              <input type="hidden" id="hid_handle_price">
                            </div>
                             <label class="col-md-2 control-label"  for='validation_current'>Discount Price</label>
                            <div class="col-sm-4 controls">
                              <input type="text" id="discount_price" data-rule-money="false"   class='form-control money'  name="price[discount_price]"  placeholder="Discount price"  >  
                            </div>
                            </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label required"  for='validation_current'>Days of the week</label>
                            <div class="col-sm-9 controls">
                              <table class="table" >
                                <tr>
                                <td><label><input type="checkbox" id="checkAll" value="1" name="all_days"  > All Days</label></td>
                                <td><label><input type="checkbox" class="days" value="1" name="tour[days_week][]" data-rule-required='true' > Mon</label></td>
                                <td><label><input type="checkbox" class="days" value="2" name="tour[days_week][]" data-rule-required='true'> Tue</label></td>
                                <td><label><input type="checkbox" class="days" value="3" name="tour[days_week][]" data-rule-required='true' > Wed</label></td>
                                <td><label><input type="checkbox" class="days" value="4" name="tour[days_week][]" > Thur</label></td>
                                <td><label><input type="checkbox" class="days" value="5" name="tour[days_week][]" > Fri</label></td>
                                <td><label><input type="checkbox" class="days" value="6" name="tour[days_week][]" > Sat</label></td>
                                <td><label><input type="checkbox" class="days" value="7" name="tour[days_week][]" > Sun</label></td>
                               </tr>
                              </table> 
                              <label for="tour[days_week][]" class="error"></label>
                            </div>      
                          </div>
                          
                       
                          <div class="form-group">
                            <label class="col-md-1 control-label"  for='validation_current'>Insurance </label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">   
                              <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[insurance]" value="1"    >    
                                
                              </div>
                              <div class="extra_box_div col-sm-8">
                                <input type="radio" name="insurance" class="price_include"   data-rule-required='true' value='2'> Not Included 
                                <input type="radio" name="insurance" class="price_include"  data-rule-required='true' value='1'> Included
                                <input type="text"   class='form-control display_none  money'  name="tour[insurance_price]" id="price_box" placeholder="Insurance (Price)" data-rule-required='true' data-rule-money="false">
                              </div>  
                            </div>     
                       
                            <label class="col-md-1 control-label"  for='validation_current'>visa</label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">
                              <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[visa]" value="1"    >    
                               
                              </div>
                              <div class="extra_box_div col-sm-8">
                                <input type="radio" name="visa" id="visa"  class="price_include" data-rule-required='true' value='2'> Not Included 
                                <input type="radio" name="visa" id="visa" class="price_include" data-rule-required='true' value='1'> Included
                                <input type="text" class='form-control display_none  money'  name="tour[visa_price]" id="price_box" placeholder="Visa(price)" data-rule-required='true' data-rule-money="false">
                             </div>  
                            </div>     
                          </div>

                          <div class="form-group">
                            <label class="col-md-1 control-label"  for='validation_current'>Transfer </label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">
                              <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[transfer]" value="1"    >    
                                
                              </div>
                              <div class="extra_box_div col-sm-8">
                                <input type="radio" name="transfer" class="price_include" id="price_include" data-rule-required='true' value='2'> Not Included 
                                <input type="radio" name="transfer" class="price_include" id="price_include" data-rule-required='true' value='1'> Included
                                <input type="text"   class='form-control display_none  money'  name="tour[transfer_price]" id="price_box" placeholder="Transfer (Price)" data-rule-required='true' data-rule-money="false">
                              </div>  
                            </div>     
                          </div>

                          <div class="form-group">
                            <label class="col-md-1 control-label"  for='validation_current'>CIP OUT</label>
                            <div class="col-sm-5 controls">
                              <div class="col-sm-4">
                               <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[cipout]" value="1"    >    
                                
                              </div>
                              <div class="extra_box_div col-sm-8">
                                <input type="radio" name="cout" class="price_include" id="price_include" data-rule-required='true' value='2'> Not Included 
                                <input type="radio" name="cout" class="price_include" id="price_include" data-rule-required='true' value='1'> Included
                                <input type="text"   class='form-control display_none  money'  name="tour[cipout_price]" id="price_box" placeholder="CIP OUT (Price)" data-rule-required='true' data-rule-money="false" >
                               </div>   
                            </div>   

                            <label class="col-md-1 control-label"  for='validation_current'>CIP IN</label>
                              <div class="col-sm-5 controls">
                                <div class=" col-sm-4">
                                <input type='checkbox' data-off-color="danger" data-on-text="YES" data-off-text="NO" class='extra_option' name="tour[cipin]" value="1"    >    
                                  
                                </div>
                                <div class="extra_box_div col-sm-8">
                                  <input type="radio" name="cip_in" class="price_include" id="price_include" data-rule-required='true' value='2'> Not Included 
                                  <input type="radio" name="cip_in" class="price_include" id="price_include" data-rule-required='true' value='1'> Included
                                  <input type="text"   class='form-control display_none  money'  name="tour[cipin_price]" id="price_box" placeholder="CIP IN (Price)" data-rule-required='true' data-rule-money="false" >
                                </div>   
                            </div>     
                          </div>

                          <div class="form-group extra_com">
                            <label class="col-md-2 control-label"  for='validation_current'>Net / Comission</label>
                            <div class="col-sm-4 controls">
                              <div class="radio col-sm-4">
                                <label><input type="radio" class="net_com"  name="price[price_type]"  value="NET" data-rule-required='true'>Net</label>
                              </div>
                              <div class="radio col-sm-4">
                                <label><input type="radio" class="net_com"  name="price[price_type]" value="COMMISSION"  data-rule-required='true' >Commission</label>
                              </div>
                              <div id="percentage_dollar" class="display_none">
                              <div class="radio col-sm-5">
                                  <input type="text" id="percentage_box"   data-rule-money="false"   class='form-control money'  name="price[percentage]"  placeholder="%" data-rule-required='true'>
                              </div>
                              <div class="radio col-sm-5">
                                  <input type="text" id="dollar_box"   data-rule-money="false"   class='form-control money'  name="price[dollar]"  placeholder="$" data-rule-required='true'  >
                              </div>
                              </div>
                              
                            </div>  
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label"  for='validation_current'>Counter Bonus </label>
                            <div class="col-sm-4 controls">
                              <input type="text"  data-rule-money="false"  class='form-control money'  name="master[counter_bonus]"  placeholder="counter bonus">
                            </div>
                          </div>

          

                        <fieldset>
                        <legend>Tour Details:</legend>                          
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Itinerary(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="itinerary_en" name="details[itinerary_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>        
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Itinerary(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="itinerary_fa" name="details[itinerary_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>        
                              </div>
                            </div>
                          </div>
                  
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Inclusions(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="inclusions_en" name="details[inclusions_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>        
                              </div>
                            </div>
                            </div>
                          <div class="form-group">
                             <label class="col-md-2 control-label" for='validation_company'>Inclusions(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="inclusions_fa" name="details[inclusions_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>        
                              </div>
                            </div>
                          </div>
                      
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Exclusions(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="exclusions_en" name="details[exclusions_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>     
                              </div>
                            </div>
                            </div>
                          <div class="form-group">
                             <label class="col-md-2 control-label" for='validation_company'>Exclusions(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="exclusions_fa" name="details[exclusions_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>     
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Privacy Policy(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="privacy_en" name="details[privacy_policy_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>
                              </div>
                            </div>
                            </div>
                          <div class="form-group">
                             <label class="col-md-2 control-label" for='validation_company'>Privacy Policy(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="privacy_fa" name="details[privacy_policy_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>
                              </div>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Terms & Conditions(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="terms_en" name="details[terms_conditions_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>
                              </div>
                            </div>
                            </div>
                          <div class="form-group">
                              <label class="col-md-2 control-label" for='validation_company'>Terms & Conditions(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="terms_fa" name="details[terms_conditions_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>
                              </div>
                            </div>
                          </div>
                    
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Cancellation policy(English)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="cancellation_en" name="details[cancellation_policy_en]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>     
                              </div>       
                            </div>
                            </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Cancellation policy(Farsi)</label>
                            <div class='col-sm-8 controls'>
                              <a href="javascript:void(0)" class="hide_more" style="display:none">Hide the box</a>
                              <a href="javascript:void(0)" class="show_more">Show the box</a>
                              <div style="display:none" class="textarea">
                                <textarea id="cancellation_fa" name="details[cancellation_policy_fa]" class="form-control ckeditor fa_ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>     
                              </div>       
                            </div>
                          </div>
                        </fieldset>

                        <fieldset>
                          <legend>Tour Gallery </legend>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Add Tour gallery images</label>
                            <div class='col-sm-4 controls'>
                              <div class="gallery" id="gallery">
                                <input type="file" value="" class="form-control"  name="gallery[image][]" data-rule-accept="image/*" /><br>
                              </div>
                            </div>
                          </div>

                        </fieldset> <br><br>
                        
                        <fieldset>
                          <legend>Brochure </legend>
                          <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Add / update Brochure (.pdf only)</label>
                            <div class='col-sm-4 controls'>
                              <div class="tour_file" id="tour_file">
                                <input type="file" value="" class="form-control"  name="file[tour_file][]" data-rule-accept="application/pdf" /><br>
                              </div>
                            </div>
                          </div>

                        </fieldset> <br><br>

                        <fieldset>
                          <legend>Transportation </legend>
                            <div >     
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

                                   <label class="col-md-2 control-label"  for='validation_current'>Flight No</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="flight[airline_no][]"  placeholder="Flight No" data-rule-alphanumericval="false"  >
                                  </div>   

                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="flight[departuer_airport_en][]"  placeholder="Departuer-Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="flight[departuer_airport_fa][]"  placeholder="Departuer-Airport" data-rule-alphanumericval="false"  >
                                  </div>   
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="flight[arrival_airport_en][]"  placeholder="Arrival Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="flight[arrival_airport_fa][]"  placeholder="Arrival Airport" data-rule-alphanumericval="false"  >
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
                                    <input type="text"   class='form-control'  name="flight[return_deapartur_en][]"  placeholder="Return Departuer Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="flight[return_deapartur_fa][]"  placeholder="Return Departuer Airport" data-rule-alphanumericval="false"  >
                                  </div>    
                                </div>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to (English)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' name="flight[return_arrival_en][]"  placeholder="Return Arrival Airport" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return to (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    
                                   <input type="text"   class='form-control' name="flight[return_arrival_fa][]"  placeholder="Return Arrival Airport" data-rule-alphanumericval="false"  >
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
                                     <input type="text"  data-rule-money="false"   class='form-control money' name="flight[flight_price][]"   placeholder="Price" >
                                  </div>
                                  </div> <br/><br/>    
                                </div>

                              </div>


                              <div class="add_more_transport_flight"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="1" value="Add More Flight">
                                </div> 
                             </div> 
                   <!-- Cruise START-->
                    <div id="cruise_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#CCC;padding:10px ;" class="flight_number">Ship liner<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="2" value="Remove"></h3>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Cruise Name</label>
                                  <div class="col-sm-4 controls">
                                  <input type="text" class='form-control'  name="cruise[cruise_name][]"   placeholder="Cruise Name" >
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Cruise Number</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'   name="cruise[cruise_number][]"   placeholder="Cruise Number" >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="cruise[departure_from_en][]"  placeholder="departure-train" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="cruise[departure_from_fa][]"  placeholder="departure-train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                               <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="cruise[arrival_cruise_en][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="cruise[arrival_cruise_fa][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Departure Time</label>
                                  <div class="col-sm-4 controls">
                                
                                  <div class="input-group timepicker">
                                      <input name="cruise[departure_time][]"   data-format="hh:mm" type="text" class="form-control"  placeholder="Departure Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Arrival Time</label>
                                  <div class="col-sm-4 controls">
                                    <div class="input-group timepicker">
                                      <input name="cruise[arrival_time][]"   data-format="hh:mm" type="text" class="form-control"  placeholder="Arrival Time" >
                                      <span class="input-group-addon add-on"><i class="icon-clock-o"></i></span>
                                    </div>
                                  </div>     
                                </div>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="cruise[return_deapartur_en][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="cruise[return_deapartur_fa][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                              <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to (English)</label>
                                  <div class="col-sm-4 controls">                                    
                                   <input type="text"   class='form-control'  name="cruise[return_arrival_en][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return to (Farsi)</label>
                                  <div class="col-sm-4 controls">                                    
                                   <input type="text"   class='form-control'  name="cruise[return_arrival_fa][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
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
                                     <input type="text"    data-rule-money="false"   class='form-control money' name="cruise[price][]"   placeholder="Price" >
                                  </div>
                                  </div> <br/><br/>    
                                </div>         
                                

                              </div>


                              <div class="add_more_transport_cruise"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="2" value="Add More Ship liner">
                                </div> 
                             </div> 
                    
                             <!-- Cruise END-->

                              <div id="train_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#CCC;padding:10px ;" class="flight_number">Train<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="3" value="Remove"></h3>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Train Name</label>
                                  <div class="col-sm-4 controls">
                                  <input type="text" class='form-control'  name="train[train_name][]"   placeholder="Train Name" >
                                  </div>     

                                  <label class="col-md-2 control-label"  for='validation_current'>Train Number</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' name="train[train_number][]"   placeholder="Train Number" >
                                  </div> 
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="train[departure_from_en][]"  placeholder="departure-train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="train[departure_from_fa][]"  placeholder="departure-train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="train[arrival_train_en][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="train[arrival_train_fa][]"  placeholder="Arrival train" data-rule-alphanumericval="false"  >
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
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="train[return_deapartur_en][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="train[return_deapartur_fa][]"  placeholder="Return departure train" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to (English)</label>
                                  <div class="col-sm-4 controls">                                    
                                   <input type="text"   class='form-control'  name="train[return_arrival_en][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return to (Farsi)</label>
                                  <div class="col-sm-4 controls">                                    
                                   <input type="text"   class='form-control'  name="train[return_arrival_fa][]"  placeholder="Return Arrival train" data-rule-alphanumericval="false"  >
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
                                        <input name="train[return_arrival_time][]"  data-format="hh:mm"  class="form-control"  placeholder="Return Arrival Time" >
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
                                     <input type="text"   data-rule-money="false"   class='form-control money' name="train[price][]"   placeholder="Price">
                                  </div>
                                  </div> <br/><br/>    
                                </div>              
                                

                              </div>


                              <div class="add_more_transport_train"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="3" value="Add More Train">
                                </div> 
                             </div> 


                              <div id="bus_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                                <h3 style="background:#CCC;padding:10px ;" class="flight_number">Bus<input  type="button"  class="pull-right btn btn-success btn-sm remove_div" style="display:none;" data-id="4" value="Remove"></h3>
                                <div class="form-group">
                                <label class="col-md-2 control-label"  for='validation_current'>Bus Name</label>
                                <div class="col-sm-4 controls">
                                    <input type="text" class='form-control'  name="bus[bus_name][]"   placeholder="Bus Name" >
                                </div>   

                                <label class="col-md-2 control-label">Bus Number</label>
                                  <div class="col-sm-4 controls">
											             <input type="text"   class='form-control'  name="bus[bus_number][]"   placeholder="Bus Number" >
                                  </div>     
                                </div>

                                 <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>From (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="bus[departure_from_en][]"  placeholder="Departure City"  data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>From (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control '  name="bus[departure_from_fa][]"  placeholder="Departure City"  data-rule-alphanumericval="false"  >
                                  </div>
                                 </div>
                                <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>To (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="bus[arrival_bus_en][]"  placeholder="Arrival City"  data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>To (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control'  name="bus[arrival_bus_fa][]"  placeholder="Arrival City"  data-rule-alphanumericval="false"  >
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
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (English)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' name="bus[return_deaparture_en][]"  placeholder="Return Departure City" data-rule-alphanumericval="false"  >
                                  </div>
                                  <label class="col-md-2 control-label"  for='validation_current'>Return from (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                    <input type="text"   class='form-control' name="bus[return_deaparture_fa][]"  placeholder="Return Departure City" data-rule-alphanumericval="false"  >
                                  </div>
                                </div>
                               <div class="form-group">
                                  <label class="col-md-2 control-label"  for='validation_current'>Return to (English)</label>
                                  <div class="col-sm-4 controls">
                                   <input type="text"   class='form-control'  name="bus[return_arrival_en][]"  placeholder="Return Arrival City" data-rule-alphanumericval="false"  >
                                  </div>
                                   <label class="col-md-2 control-label"  for='validation_current'>Return to (Farsi)</label>
                                  <div class="col-sm-4 controls">
                                   <input type="text"   class='form-control'  name="bus[return_arrival_fa][]"  placeholder="Return Arrival City" data-rule-alphanumericval="false"  >
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
                                     <input type="text"   data-rule-money="false"   class='form-control money' name="bus[price][]"   placeholder="Price">
                                  </div>
                                  </div> <br/><br/>    
                                </div>  

                                </div>

                
                                <div class="add_more_transport_bus"></div>

                              <div class="form-group">
                                <div class="col-sm-12" style="text-align:right">
                                 <input  type="button" class="btn btn-primary add_more_transport" data-id="4" value="Add More BUs">
                                </div> 
                             </div> 

                            </div>
                           </fieldset>


                     <br>      <br>
                      <fieldset>
                      <legend>Hotel </legend> 
 
                      <div id="hotel_det" class="hotel_det" style="background:#f8f8f8;padding:0 10px;margin-bottom:10px;">
                        <h3 style="background:#CCC;padding:10px ;" class="hotel_number">Hotel</h3>
                        <div class="form-group hotel_group" id="first_hotel">
                          <div class="col-md-12" style="margin-left: 150px;">
                            <label class="col-md-2 control-label required">Hotel Name </label>
                            <div class="col-sm-4 controls hotel_name">
                              <select   id="list_hotel"    name="hotel[hotel_id][]" class="form-control hotel_id select2"  data-rule-required='true' >
                                <option value="" >Select Hotel</option>
                                <?php if($hotel){ foreach ($hotel as $hotelrows) {?>
                                <option  value="<?php echo $hotelrows->hotel_id; ?>" ><?php echo $hotelrows->name_en; ?></option>
                                <?php } } ?>
                              </select>
                            </div>
                             
                          </div>
                          
                        </div>
                        <input type="hidden" id="hotel_store" value="">
                         <div class="hotel_price"></div>
                         

                      </div>
                     
                      <div class="add_more_hotel_place"></div>
                      <p class="text-right" ><input  type="button" class="btn btn-primary  add_more_hotel" value="Add More Hotel">
                      </p>
                    </fieldset>
                      
                      <fieldset>
                        <legend>Vendor Details:</legend>
                        <label class="col-md-2 control-label required" for='validation_name'>Vendor List</label>
                            <div class="col-sm-4 controls">
                              <select class='form-control select2 list_vendor'  name='tour[vendor_id]'  data-rule-required='true' >
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
                                <option data-foo="<?php echo $v_name.'::!!::'.$v_contact_person.'::!!::'.$v_web_address.'::!!::'.$v_mobile.'::!!::'.$v_login.'::!!::'.$v_pwd.'::!!::';?>" value='<?php echo $v_id; ?>'> <?php echo $v_name; ?>  </option> 
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
                            <button class='btn btn-primary tour_submit' name="create" type='submit'> <i class='icon-save'></i> Submit Tour</button>                          
                            
                          <img style="display: none;" class="tour_loader" src="<?php echo base_url().'assets/images/loading.gif';?>">
                          </div>
                        </div>
                      </div>

                     
                   </form>
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
    <style type="text/css">
    label.error{color: RED;}
    </style>

    
  </body>
</html>