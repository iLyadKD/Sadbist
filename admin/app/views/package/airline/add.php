<!DOCTYPE html>
<html>
  <?php
    $include["css"][] = "bootstrap/bootstrap";
    $include["css"][] = "light-theme";
    $include["css"][] = "select2/select2";
    $include["css"][] = "jquery-ui/jquery-ui";
    $include["css"][] = "bootstrap-tags/bootstrap-tagsinput";
    $include["css"][] = "bootstrap-switch/bootstrap-switch";
    $this->load->view("common/header", $include);
  ?>
  <style>

.logo_preview{
  position: relative;
  left: 325px;
  bottom: 52px;
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
                         <a href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.explode("_", $this->data['method'])[0].DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
                    <!-- Form-->
                     <?php echo form_open_multipart(base_url("package/airlines_add"),array('class' => 'form form-horizontal','id'=>'add_tour_airline','method'=>'post'));?>
                     
                          <div class="form-group"> 
                            <label class="col-md-2 control-label required" >Airline Name (English)</label>
                            <div class="col-sm-4 controls">
                              <input type="text" class='form-control' type="text" name="airline_en"  placeholder="Airline Name" data-rule-required='true'  >
                            </div>
                          </div>
                         <div class="form-group"> 
                            <label class="col-md-2 control-label required" >Airline Name (Farsi)</label>
                            <div class="col-sm-4 controls">
                              <input type="text" class='form-control' type="text" name="airline_fa"  placeholder="Airline Name" data-rule-required='true'  >
                            </div>
                          </div>

                          <div class="form-group">               
                           <label class="col-md-2 control-label required"  >Country</label>
                             <div class="col-sm-4 controls">
                                <select name="airline_country" data-rule-required='true'  class="form-control select2">
                                  <option value="">Select Country</option>
                                  <?php if($country) {foreach ($country as $rows) { ?>
                                  <option value="<?php echo $rows->name?>" ><?php echo $rows->name?></option>
                                  <?php } }?>
                              </select>
                              </div>                          
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label required" for='validation_logo'>Airline Logo</label>
                            <div class='col-sm-4 controls'>
                              <div class="gallery" id="validation_logo">
                                <input type="file" data-rule-accept="image/*" data-rule-required='true' accept=".someext,image/*" class="form-control" name="airline_logo"  data-rule-accept="image/*" /><br>
                              </div>
                              <div class="logo_preview">
                              <img class="form-group col-sm-3 pull-left preview_img" src="<?php echo asset_url('images/'.'default.png');?>" alt='No Image.' width="60px" height="35px">
                              </div>
                             <!-- <div class="extra_gallery"></div>-->
                                
                            </div>
                          </div>

                      <div class='form-actions' style='margin-bottom:0'>
                        <div class='row'>
                          <div class='col-sm-9 col-sm-offset-2'>
                          <a href="<?php echo base_url('package/view_airlines'); ?>"><button class="btn btn-danger" type="button">
                                <i class="icon-reply"></i>
                                Go Back
                              </button></a>
                          <button class='btn btn-primary' name="create" type='submit'> <i class='icon-save'></i> Submit Airline </button>                                                     
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
  </body>
</html>