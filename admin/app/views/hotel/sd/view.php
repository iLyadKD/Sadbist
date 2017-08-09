<!DOCTYPE html>
<html>
<head>
    <title>Edit Package | <?php echo PROJECT_NAME; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content='text/html;charset=utf-8' http-equiv='content-type'>
    <link href='<?=base_url();?>assets/images/meta_icons/favicon.ico' rel='shortcut icon' type='image/x-icon'>
    <link href="<?=base_url();?>assets/stylesheets/jquery-ui.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/stylesheets/bootstrap/bootstrap.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/stylesheets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/stylesheets/theme-colors.css" media="all" rel="stylesheet" type="text/css" />
  </head>

  <body class='contrast-muted fixed-header'>
    <?php $this->load->view('header');?>
    <div id='wrapper'>
      <div id='main-nav-bg'></div>
      <?php $this->load->view('side-menu');?>

      <section id='content'>
        <div class='container'>
          <div class='row' id='content-wrapper'>
            <div class='col-xs-12'>

              <div class='row'>
                <div class='col-sm-12'>
                  <div class='page-header'>
                    <h1 class='pull-left'>
                      <i class='icon-wrench'></i>
                      <span>Edit Package</span>
                    </h1>
                    <div class='pull-right'>
                      <ul class='breadcrumb'>
                        <li>
                          <a href='<?=base_url();?>'>
                            <i class='icon-bar-chart'></i>
                          </a>
                        </li>
                        <li class='separator'>
                          <i class='icon-angle-right'></i>
                        </li>
                        <li class='active'>Edit Package</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
<?php  $url =  str_replace("admin/","",base_url());?>
                  <div class='row'>
                    <div class='col-sm-12'>
                      <div class='box'>
                        <div class='box-header blue-background'>
                          <div class='title'>Package Info</div>
                        </div>

                        <div class='box-content'>
                        <form action="<?php echo WEB_URL; ?>package/edit/<?php echo $id;?>" method="post"  enctype="multipart/form-data" class='form form-horizontal validate-form'>               
                          <div class='form-group'>
                            <label class='control-label col-sm-3' for='validation_name'>Package type</label>
                            <div class='col-sm-4 controls'>
                              <select class='form-control' data-rule-required='true' name='package[package_type]' id="package_type" >
                                <option value=''> Select Package Type </option> 
                                <?php foreach ($package_type as $type_row) { ?>
                                <option value='<?php echo $type_row->type_id; ?>' <?php if( $type_row->type_id == $package->package_type){ echo "selected";} ?> > <?php echo $type_row->type_name; ?>  </option> 
                                <?php }  ?>
                              </select>
                            </div>
                          </div> 

                        <div class='form-group'>
                          <label class='control-label col-sm-3'  for='validation_current'>Package Name</label>
                          <div class='col-sm-4 controls'>
                            <div class="controls">
                              <input type="text" name="package[package_name]" id="package_name" data-rule-minlength='2' data-rule-required='true' class='form-control' value="<?php echo $package->package_name; ?>">
                            </div>
                          </div>
                        </div>


                        <div class='form-group'>
                          <label class='control-label col-sm-3'  for='validation_current'>Package Name(Persian)</label>
                          <div class='col-sm-4 controls'>
                            <div class="controls">
                              <input type="text" name="package[cpackage_name]" id="cpackage_name" data-rule-minlength='2' data-rule-required='true' class='form-control' value="<?php echo $package->cpackage_name; ?>" >
                            </div>

                          </div>
                        </div>

                        <div class='form-group'>               
                            <label class='control-label col-sm-3'  for='validation_rating' >Package Rating  </label>
                            <div class="col-sm-4 controls" >
                              <select class='form-control' data-rule-required='true' name='package[rating]' id="rating" >
                                <option value=''>0</option>
                                <option value="1" <?php if($package->rating==1){ echo "selected";} ?> >1</option> 
                                <option value="2" <?php if($package->rating==2){ echo "selected";} ?> >2</option>
                                <option value="3" <?php if($package->rating==3){ echo "selected";} ?> >3</option>  
                                <option value="4" <?php if($package->rating==4){ echo "selected";} ?> >4</option>
                                <option value="5" <?php if($package->rating==5){ echo "selected";} ?>>5</option>    
                              </select>
                            </div>  
                          </div>



                          <div class='form-group'>
                            <label class='control-label col-sm-3'  for='validation_current'>Min  allowed sellable packages
                            </label>
                            <div class='col-sm-4 controls'>
                              <div class="controls">
                                <input type="number" name="package[min_allowed]"  data-rule-minlength='1' data-rule-min="1" data-msg-min="Min Person 1" data-rule-number='true' data-rule-required='true' class='form-control' value="<?php echo $package->min_allowed; ?>">
                              </div>

                            </div>
                          </div>

                         <div class='form-group'>
                          <label class='control-label col-sm-3'  for='validation_current'>Max allowed sellable packages </label>
                          <div class='col-sm-4 controls'>        
                            <input type="number" name="package[max_allowed]"  data-rule-minlength='1' data-rule-min="1" data-msg-min="Max Person 1" data-rule-number='true' data-rule-required='true' class='form-control'  value="<?php echo $package->max_allowed; ?>" >
                          </div>
                        </div>

                        <div class='form-group'>
                         <label class='control-label col-sm-3'  for='validation_current'>Package Duration</label>
                          <div class="col-sm-4 controls"> 
                            <input type="number" min="1" max="20" name="package[duration]" data-rule-number='true' data-rule-required='true' class="form-control" id="duration"   size="40" placeholder="Enter Number Between 1-20" value="<?php echo $package->duration; ?>" readonly>                          
                         </div>                     
                        </div>

                        <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_country'>Country</label>
                          <div class='col-sm-4 controls'>
                            <select class='form-control' data-rule-required='true' name='package[package_country]' id="country">
                            <option value="">Select Location</option>
                            <?php foreach ($country as $country_rows) {?>
                            <option value='<?php echo $country_rows->country_code; ?>' <?php if( $country_rows->country_code == $package->package_country){ echo "selected";} ?> ><?php echo $country_rows->name; ?></option>
                            <?php }?>
                            </select>
                          </div>
                        </div>

                          <div class='form-group'>
                            <label class='control-label col-sm-3'  for='validation_current'>City</label>
                              <div class='col-sm-4 controls'>
                                <input type="text" name="package[package_city]" id="package_city" data-rule-required='true' class='form-control'value="<?php echo $package->package_city; ?>" >
                              </div>
                          </div>
                         
                          <div class='form-group'>
                            <label class='control-label col-sm-3'  for='validation_current'>Location</label>
                            <div class='col-sm-4 controls'>
                              <input type="text" name="package[package_location]" id="package_location" data-rule-required='true' class='form-control' value="<?php echo $package->package_location; ?>" >
                            </div>
                          </div>

                          <div class='form-group'>
                            <label class='control-label col-sm-3' for='validation_company'>Package Display Image</label>
                            <div class='col-sm-4 controls'>
                              <img src="<?php echo $url; ?>public/images/package/<?php echo $package->image; ?>" width="75" >
                              <input type="file" title='Image to add' class='form-control'  id='photo' name='package[photo]'>

                              <span id="pacmimg" style="color:#F00; display:none">Please Upload Package Image</span>
                              
                            </div>
                          </div>

                          <div class='form-group'>
                            <label class='control-label col-sm-2' for='validation_name'>Description</label>
                          <div class='col-sm-8 controls'>
                               <textarea name="package[package_description]" data-rule-required='true' class="ckeditor form-control" data-rule-required="true"  cols="70" rows="3" placeholder="Description"><?php echo $package->package_description; ?></textarea>
                           </div> 
                           </div>
                     
                          <div class='form-group'>
                            <label class='control-label col-sm-2' for='validation_name'>Description(Persian)</label>
                            <div class='col-sm-8 controls'>
                              <textarea name="package[cpackage_description]" data-rule-required='true' class="ckeditor form-control" data-rule-required="true"  cols="70" rows="3" placeholder="Description (Persian)"><?php echo $package->cpackage_description; ?></textarea>
                             </div> 
                          </div>

         
                          <fieldset>
                            <legend>Itinerary:</legend>
                              <?php 
                                if($itinerary) {
                                  foreach ($itinerary as  $value) { ?>

                              
                             <div class="duration_info" id="duration_info">

                            <div class='form-group'>
                              <label class='control-label col-sm-3'  for='validation_name'>Days</label>
                                <div class='col-sm-4 controls'>
                                  <input type="hidden" name="itinerary[iti_id][]"  id="iti_id" class='form-control' value="<?php echo $value->iti_id; ?>">
                                 <input type="text" name="itinerary[day][]" readonly="" id="days" data-rule-required='true' class='form-control' value="<?php echo $value->day; ?>">
                                </div>
                            </div>

                          <div class='form-group'>
                            <label class='control-label col-sm-3'  for='validation_place'>Place</label>
                            <div class='col-sm-4 controls'>
                              <input type="text" name="itinerary[place][]" id="place" data-rule-required='true' class='form-control' value="<?php echo $value->place; ?>">
                            </div>
                          </div>

                           <div class='form-group'>
                            <label class='control-label col-sm-3'  for='validation_place'>Place(Persian)</label>
                            <div class='col-sm-4 controls'>
                              <input type="text" name="itinerary[cplace][]" id="place" data-rule-required='true' class='form-control' value="<?php echo $value->cplace; ?>" >
                            </div>
                          </div>


                          <div class='form-group'>
                            <label class='control-label col-sm-3'  for='validation_desc'>Itinerary Description</label>
                            <div class='col-sm-4 controls'>
                              <textarea name="itinerary[i_description][]" class=" ckeditor form-control" data-rule-required="true"  cols="70" rows="3" placeholder="Description"><?php echo $value->i_description; ?></textarea>
                            </div>
                          </div>

                            <div class='form-group'>
                              <label class='control-label col-sm-3'  for='validation_desc'>Itinerary Description(Persian)</label>
                              <div class='col-sm-4 controls'>
                                <textarea name="itinerary[ci_description][]" class=" ckeditor form-control" data-rule-required="true"  cols="70" rows="3" placeholder="Description"><?php echo $value->ci_description; ?></textarea>
                              </div>
                            </div>
                          
                            <div class='form-group'>
                              <label class='control-label col-sm-3' for='validation_company'>Itinerary Image</label>
                              <div class='col-sm-3 controls'>
                                <input type="file" title='Image to add' class='form-control' id='image' name='itinerary[image][]'>
                                <span id="pacmimg" style="color:#F00; display:none">Please Upload Itinerary Image</span>   
                              </div>
                              <input type="hidden" name="itinerary[old_iimage][]" value="<?php echo $value->itinerary_image; ?>" >
                                 <img src="<?php echo $url; ?>public/images/itinerary/<?php echo $value->itinerary_image; ?>" width="200" >

                            </div>
              
                          </div>
                          <?php }     
                                  }
                              ?>
                          <div id="duration_info2"></div>
                          </fieldset>


                      <fieldset>
                      <legend>Package Details:</legend>
                      <div class='form-group'>
                      <label class='control-label col-sm-3' for='validation_company'>&nbsp;</label><div class='col-sm-8 controls'>
                          <?php if($images){

                            foreach ($images as  $images_row) { ?>
                            
                              <img src="../../../public/images/gallery/<?php echo $images_row->gallery_name; ?>" width="125" height="125" >
                         
                            <?php }
                          } ?>

                             </div>
                                </div>
                              <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'>Add Package gallery images</label>
                          <div class='col-sm-8 controls'>
                          
                              <div class="gallery" id="gallery">
                                <input type="file" value="" class="form-control" name="gallery[image][]" /><br>
                              </div>
                             <div class="extra_gallery"></div>
                               <input type="button" class="add_image" value="add more">
                          </div>
                   </div>

                    <?php 
                        if($details){
                          foreach ($details as  $details_rows) {
                        ?>
                    <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'><?php echo $details_rows->title?></label>
                          <div class='col-sm-8 controls'>
                            <ul class="package_details">
                            <li>

                              <input class="form-control b2b-txtbox" id="des_id" name="details[<?php echo $details_rows->title?>][did]"  type="hidden" value="<?php echo $details_rows->id; ?>" >

                                 <textarea name="details[<?php echo $details_rows->title?>][description]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details_rows->description?></textarea>
                              </li>
                            </ul>
                          </div>
                   </div>
                   <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'><?php echo $details_rows->title?>(Persian)</label>
                          <div class='col-sm-8 controls'>
                            <ul class="package_details">
                            <li>
                                 <textarea name="details[<?php echo $details_rows->title?>][cdescription]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details_rows->cdescription?></textarea>
                              </li>
                            </ul>
                          </div>
                   </div>
                   
                    <?php } }?>
                          </fieldset>


                      <fieldset>
                      <legend>Price Details :</legend>
                       <?php  if($duration){ foreach ($duration as $duration_rows) { ?>
                            <input class="form-control" id="dur_id" name="duration[dur_id][]"  type="hidden" value="<?php echo $duration_rows->dur_id; ?>">
                        <div class="price_details"id="price_details">

                        <div class="form-group">
                          <label class="control-label col-sm-1" for="validation_fromdate">From Date</label>

                          <div class="input-group col-sm-2">

                            <input class="fromd datepicker2 b2b-txtbox form-control fromdate" placeholder="MM/DD/YYYY" id="fromdate" name="duration[from_date][]"  type="text" value="<?php echo $duration_rows->from_date; ?>">
                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                          </div>
                          <label class="control-label col-sm-1" for="validation_todate"> To Date</label>
                          <div class="input-group col-sm-2">
                            <input class="form-control b2b-txtbox" placeholder="MM/DD/YYYY" id="to_date" name="duration[to_date][]"  type="text" value="<?php echo $duration_rows->to_date; ?>" >
                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                          </div>

                          <label class="control-label col-sm-1" for="validation_todate"> Adult price</label>
                          <div class="input-group col-sm-2">
                            <input class="form-control b2b-txtbox" placeholder="Adult Price" id="adult_price" name="duration[adult_price][]"  type="text" value="<?php echo $duration_rows->adult_price; ?>">
                            <span class="input-group-addon"><i class="icon-dollar"></i></span>
                          </div>

                          <label class="control-label col-sm-1" for="validation_todate">Child Price</label>
                          <div class="input-group col-sm-2">
                            <input class="form-control b2b-txtbox" placeholder="Child Price" id="child_price" name="duration[child_price][]"  type="text" value="<?php echo $duration_rows->child_price; ?>" >
                            <span class="input-group-addon"><i class="icon-dollar"></i></span>
                          </div>
                        </div>
                        </div>
                        <?php }}?>
                        
                        <div class="extra_price"></div>
                        <!--  <input type="button" name="add_price" class="add_price" value="add">-->

                          <div class='form-actions' style='margin-bottom:0'>
                                        <div class='row'>
                                        <div class='col-sm-9 col-sm-offset-3'>
                                          <a href="<?php echo base_url(); ?>package"><button class='btn btn-primary' type='button'>
                                            <i class='icon-reply'></i>
                                            Go Back  
                                          </button></a>
                                          <button class='btn btn-primary' name="create" type='submit'>
                                            <i class='icon-save'></i>
                                            Update
                                          </button>                                                     
                         
                                        </div>
                                      </div>
                                    </div>


                          </fieldset>



                          
                        </form>
                          <br> <br>
                          </div>

                      </div>
                    </div>
                  </div>

                
             
            </div>
          </div>
         <?php $this->load->view('footer');?>

         <script type="text/javascript">
               function setDatePicker() {
                  $(".fromdate").each(function() {
                       $( this ).datepicker({
                          changeMonth: true,
                          changeYear: true
                       });
                  });
             }

          setDatePicker();

            $("#duration").change(function(){
              $("#duration_info2").empty();
                var duration_info=  $( "#duration_info" );
                var count = this.value -1 ;
                if(count<20){
                for(var i=1;i<=count;i++){

                  var s2= $(duration_info).clone();
                  $(s2).find( "input" ).val("");
                  $(s2).find( "#days" ).val(i+1);
                  $(s2).appendTo("#duration_info2");        
              }
             }
              });


            $(".add_price").click(function(){
            var price =  $("#price_details").clone();
              //  console.log(price);
                 $(price).find( "input" ).val("");
                  $(price).appendTo(".extra_price");       
             setDatePicker();
             });

            $(".add_image").click(function(){
            var gallery =  $("#gallery").clone();
              //  console.log(price);
                 $(gallery).find( "input" ).val("");
                  $(gallery).appendTo(".extra_gallery");       
             });



         


                 

         </script>
        </div>
      </section>
    </div>
    
  </body>
</html>
  <script type="text/javascript">
    function activate(that) { window.location.href = that; }
  </script>