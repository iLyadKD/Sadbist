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

                      <form id="master_price" class="form form-horizontal" action="<?php echo base_url(); ?>package/master_update" method="post" >               
                           
                           
                           <div class="form-group">
                       
                            <label class="col-md-2 control-label" for='validation_name'>Hotels</label>
                            <div class="col-sm-4 controls">
                              <input type="hidden" id="df" value="<?php echo $default_hotel;?>">
                              <select class='form-control list_hotels select2' data-rule-required='true' name='master[hotel_id]' >
                                <option value=''> Select Hotel </option> 
                                <?php foreach ($hotels as $hotel) {   ?>
                                <option  value='<?php echo $hotel->hotel_id.'-'.$tour_id; ?>'> <?php echo $hotel->name; ?>  </option> 
                                <?php }  ?>
                              </select>
                            </div>
                            
                            
                            <div class="col-sm-4 hotel_extra_price display_none">
                                      <input id="change_label"  type='checkbox' data-off-color="success" data-on-color="success" data-on-text="Extra night price"  data-off-text="Regular night price" class='price_extra'  value="1" >    
                                        
                            </div>
                            
                          </div>
                           <div class="form-group type_room display_none">
                            <label class="col-md-2 control-label" for='validation_name'>Room Type</label>
                            <div class="col-sm-4 controls select_room_type">                             
                              
                            </div>
                            </div>
                           
                           <div class="form-group days_search display_none">
                            <label class="col-md-2 control-label"  for='validation_current'>Search by days</label>
                            <div class="col-sm-9 controls">
                              <table class="table" >
                                <tr>
                                <td><label><input type="checkbox" id="checkAll_master" value="1"   > All Days</label></td>
                                <td><label><input <?php if (in_array("1", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Mon"  > Mon</label></td>
                                <td><label><input <?php if (in_array("2", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Tue"  > Tue</label></td>
                                <td><label><input <?php if (in_array("3", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Wed"  > Wed</label></td>
                                <td><label><input <?php if (in_array("4", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Thu"  > Thur</label></td>
                                <td><label><input <?php if (in_array("5", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Fri"  > Fri</label></td>
                                <td><label><input <?php if (in_array("6", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Sat"  > Sat</label></td>
                                <td><label><input <?php if (in_array("7", $days)) { echo " checked ";} ?> type="checkbox" class="days_master" value="Sun"  > Sun</label></td>
                               </tr>
                              </table> 
                             
                            </div>      
                          </div>
                          
                          <input type="hidden" id="extra_flag" value="0"> 
                           <input type="hidden" id="selected_hotel">
                           <input type="hidden" id="tour_id">
                           <div id="price_sector"></div>
                           <div id="price_sector_extra"></div>
                           <input type="hidden" id="selected_days" value="Mon,Tue,Wed,Thu,Fri,Sat,Sun">
                           
                           
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