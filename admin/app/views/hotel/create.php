<!DOCTYPE html>
<html>
  <?php
    $include["css"][] = "bootstrap/bootstrap";
    $include["css"][] = "light-theme";    
    $include["css"][] = "select2/select2";
    $include["css"][] = "jquery-ui/jquery-ui";
    $include["css"][] = "bootstrap-tags/bootstrap-tagsinput";
    $include["css"][] = "bootstrap-switch/bootstrap-switch";
    $include["css"][] = "fileinput";
    $this->load->view("common/header", $include);
  ?>
  <style>
    .form-horizontal .control-label { text-align: left;}
    .toggle-switch {margin-top: 8px;}
    .extra_box_div{display: none;}
    .display_none{display: none;}
     label.error{color: RED !important;font-weight: normal !important; }
        .bootstrap-tagsinput {
  width: 100% !important;
}

.form-group .control-label.required:after, th.required:before {
  content:"*";
  color:#cc0000;
  margin-left: 2px;
  font-size: 12px;
}

.thumbimage {
    float:left;
    width:100px;
    height:55px;
    padding:5px;
    border:1px solid #CCC;
    margin-bottom: 5px;
}
.remove_preview {
    position: relative;
    float: left;
    cursor: pointer;
    right: 6px;
    font-size: 9px;
    color: #333333;
    background-color: white;
    padding: 1px 4px 2px 5px;
    border: 1px solid #969696;
    top: 5px;
    left: 95px;
}
.logo_preview{
  position: relative;
  left: 325px;
  bottom: 52px;
}

.more_type{
    position: relative;
    left: 100px;
    cursor: pointer;
    bottom: 15px;
}

.help_info {
    font-size: 10px;
    font-style: italic;
    font-weight: lighter;
    background-color: #EEF7EA;
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
                         <a href="<?php echo base_url($this->data['controller']); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
                     <?php echo form_open_multipart(base_url("hotel/create"),array('class' => 'form form-horizontal add_hotel cmxform form-horizontal validate-form','id'=>'add_package','method'=>'post'));?>
                      <?php /* <form id="add_package" class="form form-horizontal add_hotel" action="<?php echo base_url(); ?>hotel/create" method="post"  enctype="multipart/form-data" class='cmxform form-horizontal validate-form'>               */ ?>

                          <div class="form-group"> 
                            <label class="col-md-2 control-label required" >Name(English)</label>
                              <div class="col-sm-4 controls">
                                <textarea   class='form-control' type="text" name="hotel[name_en]"  data-rule-minlength='2' placeholder="Hotel Name" data-rule-required='true' ></textarea>
                              </div>
                               <label class="col-md-2 control-label required" >Name(Farsi)</label>
                              <div class="col-sm-4 controls">
                                <textarea   class='form-control' type="text" name="hotel[name_fa]"  data-rule-minlength='2' placeholder="Hotel Name" data-rule-required='true' ></textarea>
                              </div>
                            
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" >Neighbourhood(English)</label>
                            <div class="col-sm-4 controls">
                             <input type="text"   class='form-control' type="text" name="hotel[neighbours_en]"  placeholder="Neighbourhood" >
                            </div>
                            <label class="col-md-2 control-label" >Neighbourhood(Farsi)</label>
                            <div class="col-sm-4 controls">
                             <input type="text"   class='form-control' type="text" name="hotel[neighbours_fa]"  placeholder="Neighbourhood" >
                            </div>
                          </div>

                          <div class="form-group">               
                           <label class="col-md-2 control-label required"  >Country</label>
                             <div class="col-sm-4 controls">
                                <select name="hotel[country]" data-rule-required='true'  class="form-control select2 hotel_country">
                                  <option value="">Select Country</option>
                                  <?php if($country) {foreach ($country as $rows) { ?>
                                  <option value="<?php echo $rows->country_code?>" ><?php echo $rows->name?></option>
                                  <?php } }?>
                              </select>
                              </div>
                             
                              <label class="col-md-2 control-label required"  >City </label>
                              <div class="col-sm-4 controls">
                                <select name="hotel[city]" class="form-control hotel_city" data-rule-required='true'>
                                  <option value="">Select City</option>
                                </select>
                              </div>  
                                      
                           
                          </div>

                          <div class="form-group">               
                             <label class="col-md-2 control-label required" >Star Rating</label>
                             <div class="col-sm-4 controls">
                                <select name="hotel[rating]" class="form-control" data-rule-required='true' >
                                  <option value="" >Select Rating</option>
                                  <option value="2" >2</option>
                                  <option value="3" >3</option>
                                  <option value="4" >4</option>
                                  <option value="5" >5</option>
                                  <option value="6" >6</option>
                                </select>
                              </div>
                             
                            
                            <label class="col-md-2 control-label">Add more room types<span class="more_type"><i class="icon-question"></i></span> </label>
                            
                            <div class="col-sm-4 controls">
                              <input type="text" data-rule-required='false'  class='form-control room_type_list' type="text" name="hotel[room_types]"   placeholder="ex: room view,executive room etc" data-rule-required='true' >
                             <span class="help_info">[ here you can insert the room types in both language (English and Farsi). One by one you can add this, suppose if first one what you typed is english then second one should be farsi. Remember the order should be like this, english1,farsi1,english2,farsi2.
      And you can not edit this if any of these type is being associated with any tour...]</span>
                              <label for="hotel[room_types]" class="error"></label>
                            </div>     
                                
                           
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label required"  >Address(English)</label>
                            <div class="col-sm-4 controls">
                              <textarea   class='form-control' type="text" name="hotel[address_en]" id="address_en" data-rule-minlength='2' placeholder="Address" data-rule-required='true' ></textarea> 
                            </div>
                            <label class="col-md-2 control-label required"  >Address(Farsi)</label>
                            <div class="col-sm-4 controls">
                              <textarea   class='form-control' type="text" name="hotel[address_fa]" id="address_fa" data-rule-minlength='2' placeholder="Address" data-rule-required='true' ></textarea> 
                            </div>     
                          </div>
                  
                          <div class="form-group">
                            <label class="col-md-2 control-label required" for='validation_company'>Add Hotel images</label>
                            <div class='col-sm-6 controls'>
                              <div class="gallery" id="gallery">
                                <input id="imageupload" type="file"  class="form-control" name="gallery[]" multiple data-rule-accept="image/*" data-rule-required='true' accept=".someext,image/*"  />
                                <label class="error file_type_err" style="display: none;">unsupported file type</label>
                                <input name="file_index" type="hidden" id="file_index">
                                <br />
                                <div id="preview-image"></div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group" style="height:250px;">
                            <label class="col-md-2 control-label"  >Hotel Location</label>
                            <div class="col-sm-10 controls">
                              <input type="text" id="auto_place" style="width:400px;" placeholder="Search the location here">
                              <span style="color: #eb4614">OR</span>
                              <div style="margin:8px; ">
                                <input  type="text"  id="latitude" onkeypress="return isNumberKey(event);"  name="hotel[latitude]"  data-rule-required='true' value="33.41624954855018" >
                                <input type="text" id="longitude" onkeypress="return isNumberKey(event);"  name="hotel[longitude]" data-rule-required='true' value=" 44.299090332031255" >
                                <span id="get_locate" style="cursor: pointer;"><i class="icon-search"></i></span>
                              </div>
                              
                              <div id="hotel_maps" style="height: 280px; width: 100%;"></div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label required"  >Description(English)</label>
                            <div class="col-sm-10 controls">
                              <textarea   class='form-control ckeditor' type="text" name="hotel[descrpition_en]" id="description_en" placeholder="" data-rule-required='true' data-rule-minlength="100"  ></textarea> 
                            </div>     
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label required"  >Description(Farsi)</label>
                            <div class="col-sm-10 controls">
                              <textarea   class='form-control ckeditor fa_ckeditor' type="text" name="hotel[descrpition_fa]" id="description_fa" placeholder="" data-rule-required='true' data-rule-minlength="100"  ></textarea> 
                            </div>     
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label required" for='validation_logo'>Hotel Logo</label>
                            <div class='col-sm-4 controls'>
                              <div class="gallery" id="validation_logo">
                                <input type="file"  class="form-control" name="logo"  data-rule-accept="image/*" data-rule-required='true' accept=".someext,image/*" /><br>
                              </div>
                              <div class="logo_preview">
                              <img class="form-group col-sm-3 pull-left preview_img" src="<?php echo asset_url('images/'.'default.png');?>" alt='No Image.' width="60px" height="35px">
                              </div>
                            </div>
                          </div>

                      <div class='form-actions' style='margin-bottom:0'>
                        <div class='row'>
                          <div class='col-sm-9 col-sm-offset-4'>
                          <a href="<?php echo base_url('hotel'); ?>"><button class="btn btn-danger" type="button">
                                <i class="icon-reply"></i>
                                Go Back
                              </button></a>
                          <button class='btn btn-primary' name="create" type='submit'> <i class='icon-save'></i> Submit Hotel </button>                                                     
                          </div>
                        </div>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
            
          </div>
          
          <div class="modal fade" id="modal_concat" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Adding room type</h4>
                </div>
                <div class="modal-body">
                 here you can insert the room types in both language (English and Farsi). One by one you can add this, suppose if first one what you typed is english then second one should be farsi. Remember the order should be like this, english1,farsi1,english2,farsi2.
                And you can not edit this if any of these type is being associated with any tour...
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default"  data-dismiss="modal">Got it</button>
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