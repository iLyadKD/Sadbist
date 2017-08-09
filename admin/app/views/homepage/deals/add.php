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
											<form class="form form-horizontal add_deals_homepage" action="javascript:void(0);" method="post">
												
												<div class="form-group">
													<label class="control-label col-sm-3 required">Deals Content(En)</label>
													<div class="col-sm-8 controls">
														<textarea id="first_input" autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" name="content_en" placeholder="Deals content" type="text" data-msg-required="Please enter deals content" ></textarea>
													</div>
											
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required">Deals Content(Fa)</label>
													<div class="col-sm-8 controls">
														<textarea autocomplete="off" tabindex="2" autofocus="true" class="form-control" data-rule-required="true" name="content_fa" placeholder="Deals content" type="text" data-msg-required="Please enter deals content" ></textarea>
													</div>
											<img class="form-group col-sm-3 pull-left preview_img" src="<?php echo asset_url(IMAGE_PATH.'default.png');?>" alt='No Image.' width="80" height="80">
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Displayed Under</label>
													<div class="col-sm-5 controls">
														<select tabindex="4"   autofocus="true" class="form-control" data-rule-required="true" name="display_section" id="display_section" data-msg-required="Please select any option">
															<option value="">---select---</option>
															<option value="deals">Deals (image size : 352X60 pixels)</option>
															<option value="tour_deals">Tour Deals (image size : 223x165 pixels)</option>
															<option value="hotel_deals">Hotel Deals (image size : 223x165 pixels)</option>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Image </label>
													<div class="col-sm-5 controls">
														<input autocomplete="off" tabindex="3" class="form-control filestyle" data-classButton="btn btn-primary" data-input="true"  data-buttonText="Select" data-rule-required="true" name="slider_image" type="file" data-msg-required="Please select image" accept="image/*" data-rule-accept="image/*" data-msg-accept="Please select image only.">
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Category</label>
													<div class="col-sm-5 controls">
														<select tabindex="4"   autofocus="true" class="form-control" data-rule-required="true" name="category" id="sector_category" data-msg-required="Please select any category">
															<option value="">select category</option>
															<option value="1">Tour</option>
															<option value="2">Flight</option>
															<option value="3">Hotel</option>
															<option value="4">Others</option>
														</select>
													</div>
												</div>
												
												<div class="sector_tour" style="display: none;">
													<div class="form-group">
														<label class="control-label col-sm-3">make deals on tour</label>
														<div class="col-sm-5 controls">
															<select disabled   autofocus="true" class="select2 form-control deals_list" id="deals_list" data-rule-required="true"  data-msg-required="Please select any tour">
																<input disabled type="hidden" value="" class="valid" name='tour_link' >
																<input disabled type="hidden" value="" class="valid" name='master_id' >
															</select>
														</div>
													</div>
												</div>
												
												<div class="sector_flight" style="display: none;">
													<div class="form-group">
														<label class="control-label col-sm-3 required">Trip type</label>
														<div class="col-sm-5 controls">
														<label class="checkbox-inline"><input tabindex="1" autocomplete="off"  checked="true" data-rule-required="true" data-msg-required="" type="radio" value="OneWay" name="trip_type">ONE WAY</label>
														<label class="checkbox-inline"><input tabindex="1" autocomplete="off"  data-rule-required="true" data-msg-required="" type="radio" value="Return" name="trip_type">ROUND TRIP</label>
														<div class="paymode_error"></div>
														</div>
													</div>
													
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">Airline</label>
														<div class="col-sm-5 controls">
															<select tabindex="2" class="select2 form-control set_airlines valid" data-rule-required="true" name="airline" id="airline">
															</select>
														</div>
													</div>
													
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">From</label>
														<div class="col-sm-5 controls">
															<select tabindex="3" class="select2 form-control set_airports valid" data-rule-required="true" name="o_airport" id="o_airport">
															</select>
														</div>
													</div>
	
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">To</label>
														<div class="col-sm-5 controls">
															<select tabindex="4" class="select2 form-control set_airports valid" data-rule-required="true" name="d_airport" id="d_airport">
															</select>
														</div>
													</div>
													<div class="form-group optional_values common">
														<label class="control-label col-sm-3 required">Departure Date</label>
														<div class="col-sm-5 controls">
															<input tabindex="5" type="text" data-rule-required="true" autocomplete="off"  name="dept_date" class="form-control from_date valid">
															
														</div>
													</div>
												
													<div class="form-group optional_values common return_date" style="display: none;">
														<label class="control-label col-sm-3 required">Return Date</label>
														<div class="col-sm-5 controls">
															<input disabled tabindex="6" type="text" data-rule-required="true" autocomplete="off"  name="return_date" class="form-control from_date valid">
															
														</div>
													</div>
												
												</div>
												
												<div class="sector_hotel" style="display: none;">
													<div class="form-group">
														<label class="control-label col-sm-3 required">Search by city</label>
														<div class="col-sm-5 controls">
															<select tabindex="4" class="select2 form-control set_search_city" data-rule-required="true" name="city" id="search_city">
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3 required">Check In</label>
														<div class="col-sm-5 controls">
															<input tabindex="5" type="text" data-rule-required="true" autocomplete="off"  name="check_in" class="form-control from_date valid">
															</select>
														</div>
													</div>
												
													<div class="form-group">
														<label class="control-label col-sm-3 required">Check Out</label>
														<div class="col-sm-5 controls">
															<input disabled tabindex="6" type="text" data-rule-required="true" autocomplete="off"  name="check_out" class="form-control from_date valid">
															</select>
														</div>
													</div>
												</div>
										
										<div class="sector_others" style="display: none;">
											<div class="form-group">
												<label class="control-label col-sm-3 required">Enter url :</label>
												<div class="col-sm-5 controls">
													<input type="url" data-rule-required="true" placeholder="like https://www.google.com" autocomplete="off"   name="link" class="form-control" >
												</div>
											</div>
										</div>
												
												
												
												
												

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"><button tabindex="4" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="5" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Add Deals
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