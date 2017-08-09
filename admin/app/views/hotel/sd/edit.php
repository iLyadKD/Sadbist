<?php echo $this->load->view('header')?>
<link href="<?php echo base_url('cdn/admin/assets/jquery-ui/jquery-ui-1.10.1.custom.min.css');?>" rel="stylesheet" id="style_color" />
<style>
.error{color: #B94A48;}
input.error{border:1px solid  #B94A48;}
</style>
      <!-- BEGIN EDITABLE TABLE widget-->
             <div class="row-fluid">
                 <div class="span12">
                     <!-- BEGIN EXAMPLE TABLE widget-->
                     <div class="widget green">
                         <div class="widget-title">
                             <h4> <?php echo $title;?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                         </div>
                         <div class="widget-body">
                             <div>
                                 <div class="clearfix">
                                

            
                    <div class='span12'>
                        <?php  if($details) {
                           $formname ="edit";
                        } else{ $formname ="update_language"; }?>
                        <?php echo $formname?>
                        <form id="add_package" action="<?php echo base_url(); ?>admin/package/<?php echo $formname?>/<?php echo $language ?>/<?php echo $package->package_id ?>/" method="post"  enctype="multipart/form-data" class='cmxform form-horizontal validate-form'>               
                            
                          <div class='control-group'>
                            <label class='control-label' for='validation_name'>Package type</label>
                            <div class='controls'>
                              <select class='span6' data-rule-required='true' name='package[type]' id="type" >
                                <option value=''> Select Package Type </option> 
                                <?php foreach ($package_type as $type_row) { ?>
                                <option value='<?php echo $type_row->type_id; ?>' <?php if( $type_row->type_id == $package->type){ echo "selected";} ?> > <?php echo $type_row->type_name; ?>  </option> 
                                <?php }  ?>
                              </select>
                              </select>
                            </div>
                          </div> 

                        <div class='control-group'>
                          <label class='control-label'  for='validation_current'></label>
                          <div class='controls'>
                         
                              <input type="radio" name="package[iod]" id="package_iod" data-rule-required='true' value='2' <?php echo ($package->iod === '2')?'checked=checked':''; ?>> International</input>
                              <input type="radio" name="package[iod]" id="package_iod2"data-rule-required='true' value='1' <?php echo ($package->iod === '1')?'checked=checked':''; ?>> Domestic</input>
                               <label for="package[package_iod]" class="error"></label>
                            </div>
                          </div>
                     <!-- Button trigger modal -->

                        <div class='control-group'>
                          <label class='control-label'  for='validation_current'>Package Name</label>
                          <div class='controls'>
                              <input type="text" class='span6' type="text" name="details[name]" value="<?php echo $details->name ?>" id="name"  value="" data-rule-minlength='2' placeholder="" data-rule-required='true' class='span12'>
                             
                            </div>
                          </div>

                        

                        <div class='control-group'>               
                            <label class='control-label'  for='validation_rating' >Package Rating  </label>
                            <div class="controls" >
                              <select class='span6' data-rule-required='true' name='package[rating]' id="rating" >
                                <option value=''>Select Rating</option>
                               <option value="1" <?php if($package->rating==1){ echo "selected";} ?> >1</option> 
                                <option value="2" <?php if($package->rating==2){ echo "selected";} ?> >2</option>
                                <option value="3" <?php if($package->rating==3){ echo "selected";} ?> >3</option>  
                                <option value="4" <?php if($package->rating==4){ echo "selected";} ?> >4</option>
                                <option value="5" <?php if($package->rating==5){ echo "selected";} ?>>5</option>   
                              </select>
                            </div>  
                          </div>



                          <div class='control-group'>
                            <label class='control-label'  for='validation_current'>Min  allowed sellable packages
                            </label>
                            <div class='controls'>
                            
                                <input type="number" name="package[min_allowed]" value="<?php echo $package->min_allowed; ?>"  data-rule-minlength='1' data-rule-min="1" data-msg-min="Min Person 1" data-rule-number='true' data-rule-required='true' class='span6' value="1">
                              </div>

                            </div>
                         

                         <div class='control-group'>
                          <label class='control-label'  for='validation_current'>Max allowed sellable packages </label>
                          <div class='controls'>        
                            <input type="number" name="package[max_allowed]"  value="<?php echo $package->max_allowed; ?>"  data-rule-minlength='1' data-rule-min="1" data-msg-min="Max Person 1" data-rule-number='true' data-rule-required='true' class='span6' >
                          </div>
                        </div>

                        <div class='control-group'>
                         <label class='control-label'  for='validation_current'>Package Days</label>
                          <div class="controls"> 
                            <input type="number" name="package[days]" value="<?php echo $package->days; ?>" min="1" max="20" data-rule-number='true' data-rule-required='true' class="span6" id="duration"   size="40" placeholder="Enter Number Between 1-20">
                         </div>                     
                        </div>

                        <div class='control-group'>
                         <label class='control-label'  for='validation_current'>Package Nights</label>
                          <div class="controls"> 
                            <input type="number" name="package[nights]"  value="<?php echo $package->nights; ?>"  min="1" max="20" data-rule-number='true' data-rule-required='true' class="span6" id="duration"   size="40" placeholder="">                          
                         </div>                     
                        </div>


                        <div class='control-group'>
                          <label class='control-label' for='validation_country'>Country</label>
                          <div class='controls'>
                            <select   class='span6' data-rule-required='true' name='package[country]' id="country">
                            <option value="">Select Country</option>
                           <?php foreach ($country as $country_rows) {?>
                            <option value='<?php echo $country_rows->country_code; ?>' <?php if( $country_rows->country_code == $package->country){ echo "selected";} ?> ><?php echo $country_rows->name; ?></option>
                            <?php }?>
                            </select>
                          </div>
                        </div>


                          <div class='control-group'>
                            <label class='control-label'  for='validation_current'>State</label>
                            <div class='controls'>
                              <input type="text" name="details[state]"   value="<?php echo $details->state ?>" id="state" data-rule-required='true' class='span6'>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_current'>City</label>
                              <div class='controls'>
                                <input type="text" name="details[city]"  value="<?php echo $details->city ?>" id="city" data-rule-required='true' class='span6'>
                              </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label' for='validation_company'>Package Display Image</label>
                            <div class='controls'>
                              <img src="<?php echo base_url(); ?>cdn/packages/<?php echo $package->image; ?>" width="75" >
                              <input type="file" title='Image to add' class='span6' name='package[photo]'>
                              <span id="pacmimg" style="color:#F00; display:none">Please Upload Package Image</span>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label col-sm-2' for='validation_name'>Description</label>
                          <div class='col-sm-8 controls'>
                               <textarea name="details[description]"  data-rule-required='true' class="ckeditor span6" data-rule-required="true"  cols="70" rows="3" placeholder="Description"><?php echo $details->description
                                ?></textarea>
                           </div> 
                           </div>

                             <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Include </label>
                            <div class='controls'>
                               <?php $include_food =explode(",",$package->include);?>
                              <input type="checkbox" name="package[include][]" id="include" value="1" <?php if (in_array("1", $include_food)) { echo "checked";}?> > Hotels
                              <input type="checkbox" name="package[include][]" id="include" value="2" <?php if (in_array("2", $include_food)) { echo "checked";}?> > Sightseeing
                              <input type="checkbox" name="package[include][]" id="include" value="3" <?php if (in_array("3", $include_food)) { echo "checked";}?> > Transfer
                              <input type="checkbox" name="package[include][]" id="include" value="3"<?php if (in_array("4", $include_food)) { echo "checked";}?>  > Meals
                            </div>
                          </div>

                          <fieldset>
                            <legend>Itinerary:</legend>
                            <?php 
                                if($itinerary) {
                                  foreach ($itinerary as  $value) { ?>
                             <div class="duration_info" id="duration_info">

                            <div class='control-group'>
                              <label class='control-label'  for='validation_name'>Days</label>
                                <div class='controls'>
                                  <input type="hidden" name="itinerary[iti_id][]"  value="<?php echo $value->iti_id?>">
                                 <input type="text" name="itinerary[day][]" readonly="" id="days" data-rule-required='true' class='span6' value="<?php echo $value->day?>">
                                </div>
                            </div>

                            <div class='control-group'>
                              <label class='control-label' for='validation_company'>Itinerary Image</label>
                              <div class='col-sm-3 controls'>
                                <input type="file" title='Image to add' class='form-control' id='image' name='itinerary[image][]'>
                                <span id="pacmimg" style="color:#F00; display:none">Please Upload Itinerary Image</span>   
                          
                                 <input type="hidden" name="itinerary[old_iimage][]" value="<?php echo $value->itinerary_image; ?>" >
                                 <img src="<?php echo base_url(); ?>cdn/packages/itinerary/<?php echo $value->itinerary_image; ?>" width="100" >
                               </div>
                            </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Place</label>
                            <div class='controls'>
                              <input type="text" name="itinerary[place][]" value="<?php echo $value->place ?>" id="place" data-rule-required='true' class='span6'>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label col-sm-2'  for='validation_desc'>Itinerary Description</label>
                            <div class='col-sm-8 controls'>
                              <textarea name="itinerary[i_description][]"  class=" ckeditor span6" data-rule-required="true"  cols="70" rows="3"><?php echo $value->i_description ?></textarea>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Hotel Name</label>
                            <div class='controls'>
                              <input type="text" name="itinerary[hotel_name][]" value="<?php echo $value->hotel_name ?>"   id="hotel_name" data-rule-required='true' class='span6'>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Hotel Rating</label>
                            <div class='controls'>
                              <input type="text" name="itinerary[hotel_rating][]" value="<?php echo $value->hotel_rating ?>"  value="<?php echo $value->hotel_rating;?>" id="name" data-rule-required='true' class='span6'>
                            </div>
                          </div>
                          <?php $include_food =explode(",",$value->include_food);?>
                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Include Food</label>
                            <div class='controls'>
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" <?php if (in_array("1", $include_food)) { echo "checked";}?> value="1" > Breakfast
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" <?php if (in_array("2", $include_food)) { echo "checked";}?> value="2" > Lunch
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" <?php if (in_array("3", $include_food)) { echo "checked";}?> value="3" > Dinner
                            </div>
                          </div>

                            
              
                          </div>

                          <?php } } else {    for ($i=1; $i <= $package->days; $i++) {  ?>
                             <div class="duration_info" id="duration_info">

                            <div class='control-group'>
                              <label class='control-label'  for='validation_name'>Days</label>
                                <div class='controls'>
                                 <input type="text" name="itinerary[day][]" readonly="" id="days" data-rule-required='true' class='span6' value="<?php echo $i; ?>">
                                </div>
                            </div>

                            <div class='control-group'>
                              <label class='control-label' for='validation_company'>Itinerary Image</label>
                              <div class='col-sm-3 controls'>
                                <input type="file" title='Image to add' class='span6' data-rule-required='true' id='image' name='itinerary[image][]'>
                                <span id="pacmimg" style="color:#F00; display:none">Please Upload Itinerary Image</span>   
                              </div>
                            </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Place</label>
                            <div class='controls'>
                              <input type="text" name="itinerary[place][]" id="place" data-rule-required='true' class='span6'>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label col-sm-2'  for='validation_desc'>Itinerary Description</label>
                            <div class='col-sm-8 controls'>
                              <textarea name="itinerary[i_description][]" class=" ckeditor span6" data-rule-required="true"  cols="70" rows="3" placeholder="Description"></textarea>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Hotel Name</label>
                            <div class='controls'>
                              <input type="text" name="itinerary[hotel_name][]" id="hotel_name" data-rule-required='true' class='span6'>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Hotel Rating</label>
                            <div class='controls'>
                              <input type="text" name="itinerary[hotel_rating][]" id="name" data-rule-required='true' class='span6'>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'  for='validation_place'>Include Food</label>
                            <div class='controls'>
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" value="1" > Breakfast
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" value="2" > Lunch
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" value="3" > Dinner
                            </div>
                          </div>
                          </div>
                          <?php } } ?>

                          <div id="duration_info2"></div>
                          </fieldset>



                    <fieldset>
                      <legend>Slider Images :</legend>
                          <div class='control-group'>
                            <label class='control-label' for='validation_company'>Add Package gallery images</label>

                          <div class='col-sm-8 controls'>
                            <?php if($images){

                            foreach ($images as  $images_row) { ?>
                               <div class='col-md-5 images_rows'>
                               <img src="<?php echo base_url()?>cdn/packages/gallery/<?php echo $images_row->gallery_name; ?>" width="125" height="125" >
                                <a class="delete_image" data-image="<?php echo $images_row->gallery_name; ?>" data-url="<?php echo base_url(); ?>admin/package/delete_image/<?php echo $images_row->id;?>" >X</a>
                                </div>
                         
                            <?php }
                          } ?>

                            <div class="gallery" id="gallery">
                                <input type="file" value="" class="span6" name="gallery[image][]" /><br>
                            </div>
                              <div class="extra_gallery"></div>
                                <input type="button" class="btn btn-primary btn-xs add_image" value="add more">
                          </div>
                   </div>
                     </fieldset>

                      <fieldset>
                      <legend>Package Details:</legend>
                      
                        <div class='control-group'>
                              <label class='control-label' for='validation_company'>Overview</label>
                              <div class='col-sm-8 controls'>
                                <textarea name="details[overview]" class="span6 ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details->overview;?></textarea>
         
                              </div>
                       </div>
                  
                        <div class='control-group'>
                          <label class='control-label' for='validation_company'>Inclusions</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[inclusions]" class="span6 ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details->inclusions;?></textarea>        
                          </div>
                       </div>
                      
                        <div class='control-group'>
                          <label class='control-label' for='validation_company'>Exclusions</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[exclusions]" class="span6 ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details->exclusions;?></textarea>     
                          </div>
                        </div>

                        <div class='control-group'>
                          <label class='control-label' for='validation_company'>Payment Policy</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[payment_policy]" class="span6 ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details->payment_policy;?></textarea>
                          </div>
                       </div>
                     
                        
                    
                        <div class='control-group'>
                          <label class='control-label' for='validation_company'>Terms and conditions</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[terms_conditions]" class="span6 ckeditor" data-rule-required="true"  cols="70" rows="3"><?php echo $details->terms_conditions;?></textarea>            
                          </div>
                       </div>
                    </fieldset>

                    <fieldset>
                      <legend>Price Details :</legend>
                               <?php  if($duration){ foreach ($duration as $duration_rows) { ?>
                      <div class="form-vertical">
                        <div class="row-fluid">
                          <div class="span3">
                            <label class="control-label">From Date</label>    
                            <input type="text" class="input-block-level" placeholder="MM/DD/YYYY" id="fromdate" name="duration[from_date][]"  value="<?php echo $duration_rows->from_date; ?>"  data-rule-required='true'>      
                            <input type="hidden" name="duration[dur_id][]"  value="<?php echo $duration_rows->dur_id; ?>">      
                          </div>
                          <div class="span3 ">
                            <label class="control-label">To Date</label>
                            <input type="text" class="input-block-level" placeholder="MM/DD/YYYY" id="todate" name="duration[to_date][]" value="<?php echo $duration_rows->to_date; ?>"   type="text"  data-rule-required='true'>      
                          </div>
                          <div class="span3">
                            <label class="control-label">Price Per/Adult</label>
                            <input type="text" class="input-block-level" placeholder="Adult Price" id="adult_price" name="duration[adult_price][]" value="<?php echo $duration_rows->adult_price; ?>"  type="text" min="1" data-rule-number='true' data-rule-required='true' >
                          </div>
                          <div class="span3">
                            <label class="control-label">Child  Per/Child</label>
                            <input type="text" class="input-block-level" placeholder="Child Price" id="child_price" name="duration[child_price][]"  value="<?php echo $duration_rows->child_price; ?>" type="text" min="1" data-rule-number='true' data-rule-required='true'>   
                          </div>
                          </div>
                      </div>
                      <?php } } ?>
                      

                       
                        <div class="extra_price"></div>
                         <!--<input type="button" name="add_price" class="add_price" value="add">-->


                            <fieldset>
                              <legend>Cancellation Policy:</legend>
                              <div class="form-vertical">

                               <div class='control-group'>
                                  <label class='control-label' for='validation_company'>Cancellation</label>
                                  <div class='col-sm-8 controls'>
                                    <select name="cancel_type" id="cancel" disabled >
                                      <option value="">Select</option>
                                      <option value="2" <?php if($cancel ==""){  echo "selected"; }?>>No</option>
                                      <option value="1" <?php if($cancel){  echo "selected"; }?>>Yes</option>
                                    </select>
                                  </div>
                               </div>


                                <?php  if($cancel){  $i=1;foreach ($cancel as  $cancel_rows) { $i++;?>
                                
                                <div class="row-fluid " id="cancel_policy">
                                  <div class="span3">
                                    <label class="control-label">Days</label>    
                                    <input type="number"  placeholder="" id="cancel_days" name="cancel[cancel_days][]"   type="text"  data-rule-required='true'  min="1" max="30" value="<?php echo  $cancel_rows->cancel_days?>" >      
                                    <input type="hidden" name="cancel[can_id][]"  value="<?php echo $cancel_rows->can_id; ?>">
                                  </div>
                                  <div class="span3 ">
                                    <label class="control-label">Percentage</label>
                               
                                    <div class="input-prepend input-append">
                                        <input type="number" id="cancel_percentage" name="cancel[cancel_percentage][]" data-rule-required='true'  min="0" max="100" value="<?php echo  $cancel_rows->cancel_percentage; ?>" ><span class="add-on">%</span>
                                    </div>
                                
                                  </div>
                                   <div class="span1"> 
                                      <label class="control-label">&nbsp;</label>
                                      <span class="cancel_delete">
                                  <?php if($i>2){?>
                     

                                 <a href="javascript:void(0)" class="btn btn-primary cancel_delete_btn" >X</a></span>
                                   

                                  <?php } ?>
                                   </div> 

                              </div> 

                        
                                

                                       <?php } ?>
                                         <div class="add_more_cancel"></div>

                                   <div class="span1 ">
                                    <label class="control-label">&nbsp;</label>
                                    <input type="button"  class="btn btn-success cancel_btn" id="" name="cancel" value="Add More">      
                                  </div> 
                                  <?php  } ?>
                            </div>

                              
                               
                                       </fieldset>


                          <div class='form-actions' style='margin-bottom:0'>
                                        <div class='row'>
                                        <div class='col-sm-9 col-sm-offset-3'>
                                          
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

         <script type="text/javascript" src="<?php echo base_url('cdn/admin/js/jquery.validate.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('cdn/admin/assets/jquery-ui/jquery-ui-1.10.1.custom.min.js');?>"></script>
          <script type="text/javascript">
          $('#add_package').validate();
       $(document).ready(function(){
            $('#fromdate').datepicker( {
              minDate : 0,
              onClose: function() {
                var minDate = $(this).datepicker('getDate');
                minDate.setDate(minDate.getDate() + 1);
                date_selected = (minDate.getMonth() + 1) + '/' + (minDate.getDate()) + '/' + minDate.getFullYear();
                $('#todate').datepicker("option", "minDate", date_selected);
              }
            });
            $('#todate').datepicker({
             minDate : 1
            });
          }); 



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

                $(".cancel_btn").click(function(){
            var cancel =  $("#cancel_policy").clone();
            //  console.log(price);
            $(cancel).find( "input[type='number']" ).val("");
            $(cancel).appendTo(".add_more_cancel"); 
            $(cancel).find( ".cancel_delete" ).html('<a href="javascript:void(0)" class="btn btn-primary cancel_delete_btn" >X</a>');    
          });  

                 $(document).ready(function(){
              $('body').on('click', 'a.cancel_delete_btn', function() {
               $(this).closest("#cancel_policy").remove();
              });
             });

                 $(".delete_image").click(function(){
                    var url = $(this).attr('data-url');
                    var image_name = $(this).attr('data-image');
                    var sthis  = this;
                    $.ajax({
                            method: "GET",
                            url: url,
                            data: { name : image_name }
                          })
                            .done(function( msg ) {
                             if(msg ==1 ){
                              $(sthis).closest(".images_rows").remove();
                             }
                            });
            });
       

          </script>
      
         <?php $this->load->view('footer');?>