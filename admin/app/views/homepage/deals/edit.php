<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
		$include["css"][] = "jquery-ui/jquery-ui";
		$this->load->view("common/header", $include);
		

	?>
	<style>
		.preview_img {
			right: 80px;
			top: 166px;
		}
	</style>
	
	<?php
		$category = $deals->category;
		$display = $deals->display_section;
		$inputs = json_decode($deals->inputs);
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
													<a tabindex="-1" href="<?php echo base_url(); ?>">
														<i class="icon-bar-chart"></i>
													</a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li>
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
											<form hyperlink="<?php echo $deals_id;?>" class="form form-horizontal update_deals_homepage" action="javascript:void(0);" method="post">
												
												<div class="form-group">
													<label class="control-label col-sm-3 required">Deals Content(En)</label>
													<div class="col-sm-8 controls">
														<textarea id="first_input" autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" name="content_en" placeholder="Deals content" type="text" data-msg-required="Please enter deals content" ><?php echo $deals->content_en;?></textarea>
													</div>
											
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required">Deals Content(Fa)</label>
													<div class="col-sm-8 controls">
														<textarea autocomplete="off" tabindex="2" autofocus="true" class="form-control" data-rule-required="true" name="content_fa" placeholder="Deals content" type="text" data-msg-required="Please enter deals content" ><?php echo $deals->content_fa;?></textarea>
													</div>
											<img class="form-group col-sm-3 pull-left preview_img" src="<?php echo $deals->image === '' ? asset_url(IMAGE_PATH.'default.png') : upload_url($deals->image);?>" alt='No Image.' width="80" height="80">
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Displayed Under</label>
													<div class="col-sm-5 controls">
														<select tabindex="4"   autofocus="true" class="form-control" data-rule-required="true" name="display_section" id="display_section" data-msg-required="Please select any option">
															<option value="">---select---</option>
															<option <?php if($display == 'deals') echo 'selected';?> value="deals">Deals (image size : 352X60 pixels)</option>
															<option <?php if($display == 'tour_deals') echo 'selected';?> value="tour_deals">Tour Deals (image size : 223x165 pixels)</option>
															<option <?php if($display == 'hotel_deals') echo 'selected';?> value="hotel_deals">Hotel Deals (image size : 223x165 pixels)</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Image</label>
													<div class="col-sm-5 controls">
														<input  autocomplete="off" tabindex="3" class="form-control filestyle valid" data-classButton="btn btn-primary" data-input="true"  data-buttonText="Select" data-rule-required="false" name="slider_image" type="file" data-msg-required="Please select image" accept="image/*" data-rule-accept="image/*" hyperlink = '<?php echo $deals->image;?>' data-msg-accept="Please select image only.">
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Category</label>
													<div class="col-sm-5 controls">
														<select disabled tabindex="4"    autofocus="true" class="form-control" data-rule-required="true" value="<?php echo $deals->category;?>"  id="sector_category" data-msg-required="Please select any category">
														
															<option value="">select category</option>
															<option <?php if($category == 1) echo 'selected';?> value="1">Tour</option>
															<option <?php if($category == 2) echo 'selected';?> value="2">Flight</option>
															<option <?php if($category == 3) echo 'selected';?> value="3">Hotel</option>
															<option <?php if($category == 4) echo 'selected';?> value="4">Others</option>
														</select>
													</div>
													<input type="hidden" id="category_field" name="category"  value="<?php echo $deals->category;?>">
												</div>
												
												<?php
													if($category == 1){
														$disabled = '';
														$style = "";
														$master_id = $inputs->master_id;
														$link = $inputs->tour_link;
													}else{
														$disabled = 'disabled';
														$style = "display:none;";
														$master_id = '';
														$link = '';
													}
												?>
												<div class="sector_tour" style="<?php echo $style;?>">
													<div class="form-group">
														<label class="control-label col-sm-3">make deals on tour</label>
														<div class="col-sm-5 controls">
															
															<select tabindex="5" hyperlink = '<?php echo $master_id;?>' <?php echo $disabled;?>   autofocus="true" class="select2 form-control deals_list" id="deals_list" data-rule-required="true"   data-msg-required="Please select any tour">
																<input <?php echo $disabled;?> type="hidden" value="" class="valid" name='tour_link' >
																<input value="<?php echo $master_id;?>" <?php echo $disabled;?> type="hidden"  class="valid" name='master_id' >
																<input value="<?php echo $link;?>" <?php echo $disabled;?> type="hidden"  class="valid" name='tour_link' >
															</select>
														</div>
													</div>
												</div>
												
												<?php
													if($category == 2){
														$airline = $inputs->airline;
														$origin = $inputs->o_airport;
														$dest = $inputs->d_airport;
														$dept_date = $inputs->dept_date;
														
														$disabled = '';
														$style = "";
														$trip_type = $inputs->trip_type;
														
														if($trip_type == 'Return'){
															$inner_disabled = '';
															$inner_style = '';
															$return = $inputs->return_date;
														}else{
															$return = '';
															$inner_disabled = 'disabled';
															$inner_style = 'display:none;';
														}
														
													}else{
														$dept_date = '';
														$disabled = 'disabled';
														$style = "display:none;";
														$trip_type = '';
														$inner_disabled = 'disabled';
														$inner_style = 'display:none;';
														
														$airline = '';
														$origin = '';
														$dest = '';
														$return = '';
													}
												?>
												<div class="sector_flight" style="<?php echo $style;?>">
													<div class="form-group">
														<label class="control-label col-sm-3 required">Trip type</label>
														<div class="col-sm-5 controls">
														<label class="checkbox-inline"><input tabindex="6" <?php echo $disabled;?>  autocomplete="off"   data-rule-required="true" data-msg-required="" type="radio"  value="OneWay" <?php if($trip_type == 'OneWay') echo 'checked';?>  name="trip_type">ONE WAY</label>
														<label class="checkbox-inline"><input tabindex="7" <?php echo $disabled;?>  autocomplete="off"  data-rule-required="true" data-msg-required="" type="radio"   value="Return" <?php if($trip_type == 'Return') echo 'checked';?>  name="trip_type">ROUND TRIP</label>
														
														<div class="paymode_error"></div>
														</div>
													</div>
													
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">Airline</label>
														<div class="col-sm-5 controls">
															<select <?php echo $disabled;?> tabindex="8" class="select2 form-control set_airlines valid" data-rule-required="true" name="airline" id="airline" hyperlink = '<?php echo $airline;?>'>
															</select>
														</div>
													</div>
													
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">From</label>
														<div class="col-sm-5 controls">
															<select <?php echo $disabled;?> tabindex="9" class="select2 form-control set_airports valid" data-rule-required="true" name="o_airport" id="o_airport" hyperlink = '<?php echo $origin;?>'>
															</select>
														</div>
													</div>
	
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">To</label>
														<div class="col-sm-5 controls">
															<select <?php echo $disabled;?> tabindex="10" class="select2 form-control set_airports valid" data-rule-required="true" name="d_airport" id="d_airport" hyperlink = '<?php echo $dest;?>'>
															</select>
														</div>
													</div>
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">Departure Date</label>
														<div class="col-sm-5 controls">
															<input <?php echo $disabled;?> tabindex="11" type="text" data-rule-required="true" autocomplete="off"  name="dept_date" class="form-control from_date valid" value="<?php echo $dept_date;?>">
															
														</div>
													</div>
													<div class="form-group optional_values common return_date" style="<?php echo $inner_style;?>">
														<label class="control-label col-sm-3 required">Return Date</label>
														<div class="col-sm-5 controls">
															<input <?php echo $inner_disabled;?> value="<?php echo $return;?>" tabindex="6" type="text" data-rule-required="true" autocomplete="off"  name="return_date" class="form-control from_date valid" tabindex="12">
															
														</div>
													</div>
												
												</div>
												
												<?php
													if($category == 3){
														$search_city = $inputs->city;
														$disabled = '';
														$style = "";
														$check_in = $inputs->check_in;
														$check_out = $inputs->check_out;
													}else{
														$disabled = 'disabled';
														$style = "display:none;";
														$search_city = '';
														$check_in = '';
														$check_out = '';
													}
												?>
												
												
												<div class="sector_hotel" style="<?php echo $style;?>">
													<div class="form-group">
														<label class="control-label col-sm-3 required">Search by city</label>
														<div class="col-sm-5 controls">
															<select hyperlink = "<?php echo $search_city;?>" <?php echo $disabled;?> tabindex="13" class="select2 form-control set_search_city" data-rule-required="true" name="city" id="search_city">
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3 required">Check In</label>
														<div class="col-sm-5 controls">
															<input <?php echo $disabled;?> tabindex="14" type="text" data-rule-required="true" autocomplete="off"  name="check_in" class="form-control from_date valid" value="<?php echo $check_in;?>">
															</select>
														</div>
													</div>
												
													<div class="form-group">
														<label class="control-label col-sm-3 required">Check Out</label>
														<div class="col-sm-5 controls">
															<input <?php echo $disabled;?> tabindex="15" type="text" data-rule-required="true" autocomplete="off"  name="check_out" class="form-control from_date valid" value="<?php echo $check_out;?>">
															</select>
														</div>
													</div>
												</div>
										
										
										
												<?php
													if($category == 4){
														$style = "";
														$link = $inputs->link;
														$disabled = '';
													}else{
														$style = "display:none;";
														$link = '';
														$disabled = 'disabled';
													}
												?>
													
												<div class="sector_others" >
													<div class="form-group">
														<label class="control-label col-sm-3 required">Enter url :</label>
														<div class="col-sm-5 controls">
															<input <?php echo $disabled;?> type="url" data-rule-required="true" placeholder="like https://www.google.com" autocomplete="off"   name="link" class="form-control" value="<?php echo $link;?>" >
														</div>
													</div>
												</div>
												
												
												
												
												

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"><button tabindex="16" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="17" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															Update Deals
															</button>
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