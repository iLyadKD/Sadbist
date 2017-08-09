<!DOCTYPE html>
<html>
  <?php
    $include["css"][] = "bootstrap/bootstrap";
    $include["css"][] = "light-theme";
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

input::-webkit-input-placeholder { font-size: 8pt; }
input::-moz-placeholder { font-size: 8pt; }
input:-ms-input-placeholder { font-size: 8pt; }
input:-moz-placeholder { font-size: 8pt; }


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
                      <span>Create Vendor</span>
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
                         <a href="<?php echo base_url('package/vendors'); ?>"> Vendors</a>
                        </li>
                        <li class="separator">
                          <i class="icon-angle-right"></i>
                        </li>
                        <li class="active">Create Vendor</li>
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
                      <div class="title">Vendor</div>
                    </div>

                    <div class="box-content">

                      <form id="add_vendor" class="form form-horizontal add_vendor" action="<?php echo base_url(); ?>package/create_vendor" method="post">               
                          <div class="form-group">
                           <label class="col-md-2 control-label required"  for='validation_current'>Vendor ID</label>
                          <div class="col-sm-2 controls">
                            <input type="text" data-rule-required="true" id="id_vendor"   class='form-control'  name="vendor[vendor_id]"  placeholder="Vendor ID" data-rule-alphanumericval="false"  >
                          <label class="error" id="vendor_id_err" style="display: none;">Vendor ID should be unique</label>
                          <input type="hidden" id="vid_flag">
                          </div>
                           <label class="col-md-2 control-label required"  for='validation_current'>Name (English)</label>
                          <div class="col-sm-2 controls">
                            <input type="text" data-rule-required="true"   class='form-control'  name="vendor[vendor_name_en]"  placeholder="Vendor name" data-rule-alphanumericval="false"  >
                          </div>
                          <label class="col-md-2 control-label required"  for='validation_current'>Name (Farsi)</label>
                          <div class="col-sm-2 controls">
                            <input type="text" data-rule-required="true"   class='form-control'  name="vendor[vendor_name_fa]"  placeholder="Vendor name" data-rule-alphanumericval="false"  >
                          </div> 
                        </div>
                          <div class="form-group">
                         
                          <label class="col-md-2 control-label required"  for='validation_current'>Contact person (English)</label>
                          <div class="col-sm-2 controls">
                            <input type="text" data-rule-required="true"   class='form-control'  name="vendor[contact_person_name_en]"  placeholder="Company contact person" data-rule-alphanumericval="false"  >
                          </div>
                          <label class="col-md-2 control-label required"  for='validation_current'>Contact person (Farsi)</label>
                          <div class="col-sm-2 controls">
                            <input type="text" data-rule-required="true"   class='form-control'  name="vendor[contact_person_name_fa]"  placeholder="Company contact person" data-rule-alphanumericval="false"  >
                          </div>
                          <label class="col-md-2 control-label required"  for='validation_current'>Website address</label>
                          <div class="col-sm-2 controls">
                            <input type="url" data-rule-required="true"  data-rule-minlength="6" placeholder="http://www.google.com"  name="vendor[web_address]" type="text" class="form-control">
                          </div>
                          
                          </div>
                          
                          
                          <div class="form-group">
                        
                           <label class="col-md-2 control-label required"  for='validation_current'>Login Id</label>
                          <div class="col-sm-2 controls">
                            <input type="text" id="login_id"  data-rule-required="true"    class='form-control'  name="vendor[login_id]"  placeholder="login id"   >
                          <label class="error" id="vendor_log_err" style="display: none;">Login Id should be unique</label>
                          <input type="hidden" id="log_flag">
                          </div>
                          
                          <label class="col-md-2 control-label required"  for='validation_current'>Password</label>
                          <div class="col-sm-2 controls">
                            <input type="text"  data-rule-required="true"    class='form-control'  name="vendor[password]"  placeholder="password"   >
                          </div>
                          
                           </div>
                           <div class="form-group">
                          
                           <label class="col-md-2 control-label required"  for='validation_current'>Office no.</label>
                          <div class="col-sm-4 controls">
                            <input type="text" data-rule-required="true" id="offc_no"   class='form-control'  name="vendor[office_no]"  placeholder="office no." data-rule-alphanumericval="false"  >
                          </div>
                           <label class="col-md-2 control-label"  for='validation_current'>Mobile no.</label>
                          <div class="col-sm-4 controls">
                            <input type="text" data-rule-required="false" id="mobile_no"   class='form-control flexi_contact'  name="vendor[mobile_no]"  placeholder="ex : +98 0728 7382782" data-rule-alphanumericval="false"  >
                          </div>
                           </div>
                        
                        
                        <div class='form-actions' style='margin-bottom:0'>
                        <div class='row'>
                          <div class='col-sm-9 col-sm-offset-3'>
                            <button class='btn btn-primary' name="create" type='submit'> <i class='icon-save'></i> Save vendor</button>                                                     
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