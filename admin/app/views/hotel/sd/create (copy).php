<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
		$this->load->view("common/header", $include);

	?>

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
											<form id="add_package" class="form form-horizontal" action="<?php echo base_url(); ?>admin/package/create" method="post"  enctype="multipart/form-data" class='cmxform form-horizontal validate-form'>               
                            
                          <div class="form-group">
                            <label class="col-md-3 control-label" for='validation_name'>Package type</label>
                           <div class="col-sm-5 controls">
                              <select class='form-control' data-rule-required='true' name='package[type]' id="type" >
                                <option value=''> Select Package Type </option> 
                                <?php foreach ($package_type as $type_row) { ?>
                                <option value='<?php echo $type_row->type_id; ?>'> <?php echo $type_row->type_name; ?>  </option> 
                                <?php }  ?>
                              </select>
                            </div>
                          </div> 

                        <div class="form-group">
                          <label class="col-md-3 control-label"  for='package_iod'></label>
                         <div class="col-sm-5 controls">
                         
                              <input type="radio" name="package[iod]" id="package_iod" data-rule-required='true' value='2'> International</input>
                              <input type="radio" name="package[iod]" id="package_iod"data-rule-required='true' vale='1'> Domestic</input>
                               <label for="package[iod]" class="error"></label>
                            </div>
                          </div>
                     <!-- Button trigger modal -->





                        <div class="form-group">
                          <label class="col-md-3 control-label"  for='validation_current'>Package Name</label>
                         <div class="col-sm-5 controls">
                              <input type="text"   class='form-control' type="text" name="details[name]" id="name" data-rule-minlength='2' placeholder="" data-rule-required='true' class='span12'>
                             
                            </div>
                          </div>

                                                
                             
                        

                        <div class="form-group">               
                            <label class="col-md-3 control-label"  for='validation_rating' >Package Rating  </label>
                           <div class="col-sm-5 controls">
                              <select class='form-control' data-rule-required='true' name='package[rating]' id="rating" >
                                <option value=''>Select Rating</option>
                                <option value="1">1</option> 
                                <option value="2">2</option>
                                <option value="3">3</option>  
                                <option value="4">4</option>
                                <option value="5">5</option>    
                              </select>
                            </div>  
                          </div>



                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_current'>Min  allowed sellable packages
                            </label>
                           <div class="col-sm-5 controls">
                            
                                <input type="number" name="package[min_allowed]"  data-rule-minlength='1' data-rule-min="1" data-msg-min="Min Person 1" data-rule-number='true' data-rule-required='true' class='form-control' value="1">
                              </div>

                            </div>
                         

                         <div class="form-group">
                          <label class="col-md-3 control-label"  for='validation_current'>Max allowed sellable packages </label>
                         <div class="col-sm-5 controls">        
                            <input type="number" name="package[max_allowed]"  data-rule-minlength='1' data-rule-min="1" data-msg-min="Max Person 1" data-rule-number='true' data-rule-required='true' class='form-control' >
                          </div>
                        </div>

                        <div class="form-group">
                         <label class="col-md-3 control-label"  for='validation_current'>Package Days</label>
                          <div class="col-sm-5 controls"> 
                            <input type="number" name="package[days]"  min="1" max="20" data-rule-number='true' data-rule-required='true' class="form-control" id="duration"   size="40" placeholder="Enter Package Days Between 1-20">
                         </div>                     
                        </div>

                        <div class="form-group">
                         <label class="col-md-3 control-label"  for='validation_current'>Package Nights</label>
                          <div class="col-sm-5 controls"> 
                            <input type="number" name="package[nights]"  min="1" max="20" data-rule-number='true' data-rule-required='true' class="form-control" id="duration"   size="20" placeholder="Enter Package Nights ">                          
                         </div>                     
                        </div>


                        <div class="form-group">
                          <label class="col-md-3 control-label" for='validation_country'>Country</label>
                         <div class="col-sm-5 controls">
                            <select class='form-control' data-rule-required='true' name='package[country]' id="country">
                            <option value="">Select Country</option>
                            <?php foreach ($country as $country_rows) {?>
                            <option value='<?php echo $country_rows->country_code; ?>'><?php echo $country_rows->name; ?></option>
                            <?php }?>
                            </select>
                          </div>
                        </div>


                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_current'>State</label>
                           <div class="col-sm-5 controls">
                              <input type="text" name="details[state]" id="state" data-rule-required='true' class='form-control'>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_current'>City</label>
                             <div class="col-sm-5 controls">
                                <input type="text" name="details[city]" id="city" data-rule-required='true' class='form-control'>
                              </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label" for='validation_company'>Package Display Image</label>
                           <div class="col-sm-5 controls">
                              <input type="file" title='Image to add' class='form-control' data-rule-required='true' id='photo' name='package[photo]'>
                              <span id="pacmimg" style="color:#F00; display:none">Please Upload Package Image</span>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class='control-label col-sm-2' for='validation_name'>Description</label>
                          <div class='col-sm-8 controls'>
                               <textarea name="details[description]" data-rule-required='true' class="ckeditor form-control" data-rule-required="true"  cols="70" rows="3" placeholder="Description"></textarea>
                           </div> 
                           </div>

                             <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_place'>Include </label>
                           <div class="col-sm-5 controls">
                              <input type="checkbox" name="package[include][]" data-rule-required='true' id="include" value="1" > Hotels
                              <input type="checkbox" name="package[include][]" data-rule-required='true' id="include" value="2" > Sightseeing
                              <input type="checkbox" name="package[include][]" data-rule-required='true' id="include" value="3" > Transfer
                              <input type="checkbox" name="package[include][]" data-rule-required='true' id="include" value="3" > Meals
                              <label for="package[include][]" class="error"></label>
                            </div>

                          </div>

                          <fieldset>
                            <legend>Itinerary:</legend>
                             <div class="duration_info" id="duration_info">

                            <div class="form-group">
                              <label class="col-md-3 control-label"  for='validation_name'>Days</label>
                               <div class="col-sm-5 controls">
                                 <input type="text" name="itinerary[day][]" readonly="" id="days" data-rule-required='true' class='form-control' value="1">
                                </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-3 control-label" for='validation_company'>Itinerary Image</label>
                              <div class='col-sm-3 controls'>
                                <input type="file" title='Image to add' class='form-control' data-rule-required='true' id='image' name='itinerary[image][]'>
                                <span id="pacmimg" style="color:#F00; display:none">Please Upload Itinerary Image</span>   
                              </div>
                            </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_place'>Place</label>
                           <div class="col-sm-5 controls">
                              <input type="text" name="itinerary[place][]" id="place" data-rule-required='true' class='form-control'>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class='control-label col-sm-2'  for='validation_desc'>Itinerary Description</label>
                            <div class='col-sm-8 controls'>
                              <textarea name="itinerary[i_description][]" class=" ckeditor form-control" data-rule-required="true"  cols="70" rows="3" placeholder="Description"></textarea>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_place'>Hotel Name</label>
                           <div class="col-sm-5 controls">
                              <input type="text" name="itinerary[hotel_name][]" id="hotel_name" data-rule-required='true' class='form-control'>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_place'>Hotel Rating</label>
                           <div class="col-sm-5 controls">
                              <input type="text" name="itinerary[hotel_rating][]" id="name" data-rule-required='true' class='form-control'>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label"  for='validation_place'>Include Food</label>
                           <div class="col-sm-5 controls">
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" value="1" > Breakfast
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" value="2" > Lunch
                              <input type="checkbox" name="itinerary[include_food][][]" id="include_food" value="3" > Dinner
                            </div>
                          </div>

                            
              
                          </div>

                          <div id="duration_info2"></div>
                          </fieldset>


                    <fieldset>
                      <legend>Slider Images :</legend>
                          <div class="form-group">
                            <label class="col-md-3 control-label" for='validation_company'>Add Package gallery images</label>
                          <div class='col-sm-8 controls'>
                          
                            <div class="gallery" id="gallery">
                                <input type="file" value="" class="form-control" name="gallery[image][]" /><br>
                            </div>
                              <div class="extra_gallery"></div>
                                <input type="button" class="btn btn-primary btn-xs add_image" value="add more">
                          </div>
                   </div>
                     </fieldset>

                      <fieldset>
                      <legend>Package Details:</legend>
           
                        <div class="form-group">
                              <label class="col-md-3 control-label" for='validation_company'>Overview</label>
                              <div class='col-sm-8 controls'>
                                <textarea name="details[overview]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>
         
                              </div>
                       </div>
                  
                        <div class="form-group">
                          <label class="col-md-3 control-label" for='validation_company'>Inclusions</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[inclusions]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>        
                          </div>
                       </div>
                      
                        <div class="form-group">
                          <label class="col-md-3 control-label" for='validation_company'>Exclusions</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[exclusions]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>     
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-3 control-label" for='validation_company'>Payment Policy</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[payment_policy]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>
                          </div>
                       </div>
                     
                    
                        <div class="form-group">
                          <label class="col-md-3 control-label" for='validation_company'>Terms and conditions</label>
                          <div class='col-sm-8 controls'>
                            <textarea name="details[terms_conditions]" class="form-control ckeditor" data-rule-required="true"  cols="70" rows="3"></textarea>            
                          </div>
                       </div>
                    </fieldset>

                    <fieldset>
                      <legend>Price Details :</legend>
                      <div class="form-vertical">
                        <div class="row-fluid">
                          <div class="span3">
                            <label class="control-label">From Date</label>    
                            <input type="text" class="input-block-level" placeholder="MM/DD/YYYY" id="fromdate" name="duration[from_date][]"   type="text"  data-rule-required='true'>      
                          </div>
                          <div class="span3 ">
                            <label class="control-label">To Date</label>
                            <input type="text" class="input-block-level" placeholder="MM/DD/YYYY" id="todate" name="duration[to_date][]"   type="text"  data-rule-required='true'>      
                          </div>
                          <div class="span3">
                            <label class="control-label">Adult Price</label>
                            <input type="text" class="input-block-level" placeholder="Adult Price" id="adult_price" name="duration[adult_price][]"  type="text" min="1" data-rule-number='true' data-rule-required='true' >
                          </div>
                          <div class="span3">
                            <label class="control-label">Child  Price</label>
                            <input type="text" class="input-block-level" placeholder="Child Price" id="child_price" name="duration[child_price][]"  type="text" min="1" data-rule-number='true' data-rule-required='true'>   
                          </div>
                          </div>
                      </div>
                      

                       
                        <div class="extra_price"></div>
                         <!--<input type="button" name="add_price" class="add_price" value="add">-->

                          <br><br>
                         <!--<input type="button" name="add_price" class="add_price" value="add">-->
                                  </fieldset>

                            <fieldset>
                              <legend>Cancellation:</legend>
                              <div class="form-vertical">

                                <div class="form-group">
                          <label class="col-md-3 control-label" for='validation_company'>Cancellation</label>
                          <div class='col-sm-8 controls'>
                            <select name="cancel_type" id="cancel">
                              <option value="">Select</option>
                              <option value="2">No</option>
                              <option value="1">Yes</option>
                            </select>
                          </div>
                       </div>

                              <div class="cancel_policy_type" style="display:none">
                                <div class="row-fluid " id="cancel_policy" >
                                  <div class="span3">
                                    <label class="control-label">Days</label>    
                                    <input type="number"  placeholder="" id="cancel_days" name="cancel[cancel_days][]"   type="text"  data-rule-required='true'  min="1" max="30">      
                                  </div>
                                  <div class="span3 ">
                                    <label class="control-label">Percentage</label>
                               
                                    <div class="input-prepend input-append">
                                        <input type="number" id="cancel_percentage" name="cancel[cancel_percentage][]" data-rule-required='true'  min="0" max="100" ><span class="add-on">%</span>
                                    </div>
                                    <label for="cancel_percentage" class="error"></label>
                              
                                    </div> 
                                     <div class="span1"> 
                                      <label class="control-label">&nbsp;</label>
                                      <span class="cancel_delete"></span>
                                    </div> 

                                  </div> 
                             

                              
                                <div class="add_more_cancel"></div>


                                <div class="span1">
                                    <label class="control-label">&nbsp;</label>
                                    <input type="button"  class="btn btn-success cancel_btn" id="" name="cancel" value="Add More">      
                                  </div>
                                    </div>
                                      </div>
                     
                                       </fieldset>
                                  
                       
                    

                          <div class='form-actions' style='margin-bottom:0'>
                                        <div class='row'>
                                        <div class='col-sm-9 col-sm-offset-3'>
                                          
                                          <button class='btn btn-primary' name="create" type='submit'>
                                            <i class='icon-save'></i>
                                            Add
                                          </button>                                                     
                         
                                        </div>
                                      </div>
                                    </div>


                          </fieldset>




                          
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