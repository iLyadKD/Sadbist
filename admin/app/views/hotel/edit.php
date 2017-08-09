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
.remove_image {
  position: relative;
  float: right;
  cursor: pointer;
  right: 6px;
  font-size: 9px;
  color: #333333;
  background-color: white;
  padding: 1px 4px 2px 5px;
  border: 1px solid #969696;
  margin-top: 1px;
  margin-right: 1px;
  top: 18px;
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
                     <?php if($this->router->fetch_method() == "duplicate") {  $url = "create"; }else {  $url="edit/".$id;}?>
                      <form id="add_package" class="form form-horizontal add_hotel" action="<?php echo base_url(); ?>hotel/<?php echo $url;?>" method="post"  enctype="multipart/form-data" class='cmxform form-horizontal validate-form'>               

                          <div class="form-group"> 
                            <label class="col-md-2 control-label required" >Name(English)</label>
                            <div class="col-sm-4 controls">
                              <textarea   class='form-control' type="text" name="hotel[name_en]"  data-rule-minlength='2' placeholder="Hotel Name" data-rule-required='true' ><?php echo $hotel->name_en;?></textarea>
                            </div>                       
                           <label class="col-md-2 control-label required" >Name(Farsi)</label>
                            <div class="col-sm-4 controls">
                              <textarea   class='form-control' type="text" name="hotel[name_fa]"  data-rule-minlength='2' placeholder="Hotel Name" data-rule-required='true' ><?php echo $hotel->name_fa;?></textarea>
                            </div>        
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label" >Neighborhood(English)</label>
                            <div class="col-sm-4 controls">
                             <input type="text"   class='form-control' type="text" name="hotel[neighbours_en]" value="<?php echo $hotel->neighbours_en;?>"  data-rule-minlength='2' placeholder="Neighborhood" >
                            </div>
                            <label class="col-md-2 control-label" >Neighborhood(Farsi)</label>
                            <div class="col-sm-4 controls">
                             <input type="text"   class='form-control' type="text" name="hotel[neighbours_fa]" value="<?php echo $hotel->neighbours_fa;?>"  data-rule-minlength='2' placeholder="Neighborhood" >
                            </div>  
                            
                          </div>
                          

                          <div class="form-group">               
                           <label class="col-md-2 control-label required"  >Country</label>
                             <div class="col-sm-4 controls">
                                 <select name="hotel[country]" class="form-control select2 hotel_country" data-rule-required='true'>
                                  <option value="">Select Country</option>
                                  <?php if($country) {foreach ($country as $rows) { ?>
                                  <option value="<?php echo $rows->country_code?>"  <?php if($rows->country_code== $hotel->country){ echo "selected";}?>><?php echo $rows->name?></option>
                                  <?php } }?>
                              </select>
                              </div>
                             
                              <label class="col-md-2 control-label required"  >City </label>
                              <div class="col-sm-4 controls">
                               <input type="hidden"  name="country" class="country_id" value="<?php echo $hotel->country?>">
                              <input type="hidden" name="city" class="city_id" value="<?php echo $hotel->city?>">
                                <select name="hotel[city]" class="form-control hotel_city" data-rule-required='true'>
                                  <option value="">Select City</option>
                                </select>
                              </div>  
                                      
                            
                             
                             
                          </div>

                          <div class="form-group">               
                            <label class="col-md-2 control-label required" >Star Rating</label>
                             <div class="col-sm-4 controls">
                                <select name="hotel[rating]" class="form-control" data-rule-required='true'>
                                  <option value="" >Select Rating</option>
                                  <option value="2" <?php if($hotel->rating==2){ echo "selected";}?> >2</option>
                                  <option value="3" <?php if($hotel->rating==3){ echo "selected";}?> >3</option>
                                  <option value="4" <?php if($hotel->rating==4){ echo "selected";}?> >4</option>
                                  <option value="5" <?php if($hotel->rating==5){ echo "selected";}?> >5</option>
                                  <option value="6" <?php if($hotel->rating==6){ echo "selected";}?> >6</option>
                                </select>
                              </div>
                             
                             <label class="col-md-2 control-label">Add more room types<span class="more_type"><i class="icon-question"></i></span></label>
                            
                            <div class="col-sm-4 controls">
                              <input <?php echo $disabled_type;?> type="text" data-rule-required='false'  class='form-control room_type_list' type="text" name="hotel[room_types]" value="<?php echo $room_types;?>"  placeholder="ex: room view,executive room etc" data-rule-required='true' >
                             <span class="help_info">[ here you can insert the room types in both language (English and Farsi). One by one you can add this, suppose if first one what you typed is english then second one should be farsi. Remember the order should be like this, english1,farsi1,english2,farsi2.
      And you can not edit this if any of these type is being associated with any tour...]</span>
                              <label for="hotel[room_types]" class="error"></label>
                            </div> 
                                
                           
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label required"  >Address(English)</label>
                            <div class="col-sm-4 controls">
                              <textarea   class='form-control' id="address_en" type="text" name="hotel[address_en]" data-rule-minlength='2' placeholder="Address" data-rule-required='true' ><?php echo $hotel->address_en;?></textarea> 
                            </div>
                            <label class="col-md-2 control-label required"  >Address(Farsi)</label>
                            <div class="col-sm-4 controls">
                              <textarea   class='form-control' id="address_fa" type="text" name="hotel[address_fa]"  data-rule-minlength='2' placeholder="Address" data-rule-required='true' ><?php echo $hotel->address_fa;?></textarea> 
                            </div>   
                          </div>

                          
                  
                          <div class="form-group">
                            <label class="col-md-2 control-label required" for='validation_company'>Hotel images</label>
                            <div class='col-sm-10 controls'>
                            <div class='col-sm-12 '>
                              <div class='row image_rows'>

                                <?php  if($gallery) { 
                                 $i=0; foreach ($gallery as $gallery_row) { $i++; ?>
                                <div class='col-sm-2'  id="gallery<?php echo $gallery_row->gallerry_id;?>">
                                <span class="remove_image" data-value="<?php echo $gallery_row->gallerry_id.'/'.$gallery_row->gallery_name;?>"><i class="icon-trash"></i></span>
                                <img class='thumbimage' src="<?php echo upload_url("hotel/gallery/".$gallery_row->gallery_name); ?>">
                                </div>
                                
                                <?php } } ?>
                                </div>
                                 </div>
                                 
                          </div>
                           </div>
                           <div class="form-group">
                            <label class="col-md-2 control-label" for='validation_company'>Add more images</label>
                            <div class='col-sm-6 controls'>
                              <div class="gallery" id="gallery">
                                <input id="imageupload" type="file"  class="form-control" name="gallery[]" multiple data-rule-accept="image/*" accept=".someext,image/*" />
                                <label class="error file_type_err" style="display: none;"></label>
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
                                <input  type="text"  id="latitude" onkeypress="return isNumberKey(event);"  name="hotel[latitude]"  data-rule-required='true' value="<?php echo $hotel->latitude;?>" >
                                <input type="text" id="longitude" onkeypress="return isNumberKey(event);"  name="hotel[longitude]" data-rule-required='true' value="<?php echo $hotel->longitude;?>" >
                                <span id="get_locate" style="cursor: pointer;"><i class="icon-search"></i></span>
                              </div>
                              
                              <div id="hotel_maps" style="height: 300px; width: 100%;"></div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label required"  >Description(English)</label>
                            <div class="col-sm-10 controls">
                              <textarea   class='form-control ckeditor' type="text" name="hotel[descrpition_en]" id="description_en" data-rule-minlength='2' placeholder="" data-rule-required='true' ><?php echo $hotel->descrpition_en;?></textarea> 
                            </div>     
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 control-label required"  >Description(Farsi)</label>
                            <div class="col-sm-10 controls">
                              <textarea   class='form-control ckeditor fa_ckeditor' type="text" name="hotel[descrpition_fa]" id="description_fa" data-rule-minlength='2' placeholder="" data-rule-required='true' ><?php echo $hotel->descrpition_fa;?></textarea> 
                            </div>     
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-2 control-label required" for='validation_logo'>Hotel Logo</label>
                            <div class='col-sm-10 controls'>
                            <?php  if($hotel->logo != '') { ?>
                            <div class='col-sm-12 '>
                              <div class='row'>

                                
                                <div class='col-sm-2'  id="validation_logo">
                                <img width="60px;" height="35px;" src="<?php echo upload_url('hotel/logo/'.$hotel->logo); ?>"    style="border:1px solid #CCC;">
                                </div>
                                
                               
                                </div><br>
                                 </div>
                             <?php }  ?>
                                  <div class='col-sm-5 '>
                              <div class="gallery" id="validation_logo">
                                <input type="file" value="" class="form-control" name="logo"  data-rule-accept="image/*" accept=".someext,image/*" /><br>
                              </div>
                              <div class="logo_preview">
                              <img class="form-group col-sm-3 pull-left preview_img" src="<?php echo asset_url('images/'.'default.png');?>" alt='No Image.' width="60px" height="35px">
                              </div>
                          
                            </div>
                          </div>
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
                          <button class='btn btn-primary'  type='submit'> <i class='icon-save'></i> Update Hotel </button>                                                     
                          </div>
                        </div>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
            
          </div>
        <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">
        Are you really want to delete this pic?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm">Delete</button>
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