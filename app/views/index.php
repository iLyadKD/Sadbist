<!DOCTYPE html>
<html class="no-js">
	<?php
		$include["css"][] = "backslider";
		$include["css"][] = "owl.theme";
		$include["css"][] = "owl.carousel";
		$include["css"][] = "jquery.bxslider";
		$include["css"][] = "select2/select2";
		$this->load->view("common/head", $include);
		?>
	<body class="contrast-muted login contrast-background">
    <div id="main-loader" style="position: fixed; width: 100%; height: 100%; background: #FFF; z-index: 1000;">
        <img src="assets/images/loader.gif" alt="loading..." width="50" style="right: 50%; top: 50%; margin: -25px -25px 0 0; position: absolute;">
    </div>
	<style>
		.install_div {
			cursor: pointer;
		}
	</style>
		
		<div id="wrapper">
			<?php
				$this->load->view("common/header");
				$this->load->view("common/notification");
				?>
			<div id="search_form">
				<div class="container cform nopadding">
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 bhoechie-tab-container">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab-menu">
							<div class="list-group">
								<!-- <a id="tab_flight" href="javascript:void(0);" class="domestic_ic tablist text-center <?php echo is_null($this->data['default_tab']) || !in_array($this->data['default_tab'], array('flights', 'hotels', 'tours')) ? 'active' : ''; ?>">
									<span class="flight_icon"></span><span><?php echo $this->lang->line("domestic_flights"); ?></span>
								</a> -->
								<a id="tab_flight" href="javascript:void(0);" class="flights_ic tablist text-center <?php echo is_null($this->data['default_tab']) || !in_array($this->data['default_tab'], array('flights', 'hotels', 'tours')) || $this->data['default_tab'] === 'flights'? 'active' : ''; ?>">
								<span class="flight_icon"></span><span><?php echo $this->lang->line("flights"); ?></span>
								</a>
								<a id="tab_hotel" href="javascript:void(0);" class="hotels_ic tablist text-center <?php echo $this->data['default_tab'] === 'hotels' ? 'active' : ''; ?>">
								<span class="hotel_icon"></span><span><?php echo $this->lang->line("hotels"); ?></span>
								</a>
								<a id="tab_tour" href="javascript:void(0);" class="tours_ic tablist text-center <?php echo $this->data['default_tab'] === 'tours' ? 'active' : ''; ?>">
								<span class="tour_icon"></span><span><?php echo $this->lang->line("tours"); ?></span>
								</a>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab nopadding">
							<!-- domestic flight section -->
							<!--<div class="bhoechie-tab-content ">
							  <form method="post" id="domestic_flight_search" action="<?php echo base_url('flight/lists');?>">
								<div class="col-xs-12 flight_module nopadding">
									<div class="col-xs-6 col-md-12 nopadding flight_check" style="margin-bottom:1%;">
										<div class="col-xs-12 nopadding flight_check" style="margin-bottom:1%;">
											<input type="radio" data-rule-required="true" name="flight_type" data-id="OW" id="dom_oneway" class="css-checkbox flight_type" value="OneWay">
											<label for="dom_oneway" class="css-label"><?php echo $this->lang->line("one_way_caps"); ?></label>
											<input type="radio" data-rule-required="true" name="flight_type" data-id="RT" id="dom_roundtrip" checked="checked" class="css-checkbox flight_type" value="Return"/>
											<label for="dom_roundtrip" class="css-label"><?php echo $this->lang->line("round_trip_spl_caps"); ?>ROUNDTRIP</label>
										</div>
									</div>
									<div class = "normalsearch_div">
										<div class="col-xs-12 mbottom">
											<div class="row">
												<div class="col-md-5 col-sm-5 col-xs-5 nopadding inlabel margintop mnopadding_right">
													<label class="search_label required"><?php echo $this->lang->line("from"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
														<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="flight_origin" class="dom_flight select2" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" id="dom_flight">
														</select>
													</div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-2 rarrow">
													<label class="search_label">&nbsp;</label>
													<img class="lazyload" data-original="<?php echo asset_url('images/roundarrow.png'); ?>" alt="" width="29" height="29" />
												</div>
												<div class="col-md-2 col-sm-2 col-xs-6 oarrow" style="display:none;">
													<label class="search_label">&nbsp;</label>
													<img class="lazyload" data-original="<?php echo asset_url('images/onewayarrow.png'); ?>" alt="" width="29" height="29" />
												</div>
												<div class="col-md-5 col-sm-5 col-xs-5 nopadding inlabel margintop mnopadding_right mleft">
													<label class="search_label required"><?php echo $this->lang->line("to"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
													<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="flight_destination" class="dom_flight select2" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" id="dom_flight">
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 mbottom">
											<div class="row">
												<div class="col-xs-12 col-sm-5 col-md-5 
												nopadding ">
													<div id="departing" class="padfive col-md-6 nopadding col-sm-6 col-xs-6  inlabel mright margintop">
														<label class="search_label required"><?php echo $this->lang->line("departure"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="flight_departure" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="dom_from_date" class="inputs_group1 from_date" />
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-6 ma_top nopadding_right margintop no_left mright inlabel returning_div padfive">
														<label class="search_label required"><?php echo $this->lang->line("return"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-greater="#dom_from_date" name="flight_arrival" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="dom_to_date" class="inputs_group1 disable to_date" />
														</div>
													</div>
												</div>
											  <!--  <div class="col-md-2 col-sm-2 col-xs-12">
													<label class="search_label">&nbsp;</label>
												</div> -->
												<!--<div class="col-xs-12 col-sm-7 col-md-7 nopadding">

													<div class="col-md-3 col-sm-3 col-xs-12 ma_top nopadding_right margintop no_left inlabel">
														<div class="clearfix"></div>
													</div>

													<div class="col-md-3 col-sm-3 col-xs-4 nopadding_right mright inlabel margintop no_left inlabel padfive">
														<label class="search_label"><?php echo $this->lang->line("adults"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select data-rule-required="true" name="adult" class="dropdown-select">
																		<?php
																			for ($i=1; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-4 ma_top nopadding_right mright margintop no_left inlabel padfive">
														<label class="search_label"><?php echo $this->lang->line("children"); ?><span class="agelimit"><?php echo $this->lang->line("child_age_limit"); ?></span></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="child" class="dropdown-select">
																		<?php
																			for ($i=0; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																	
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-4 ma_top nopadding_right margintop no_left inlabel padfive">
														<label class="search_label"><?php echo $this->lang->line("infants"); ?><span class="agelimit"><?php echo $this->lang->line("infant_age_limit"); ?></span></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="infant" class="dropdown-select">
																		<?php
																			for ($i=0; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 nopadding">
										<div class="searchbutton">
											<input type="submit" value="<?php echo $this->lang->line("search_flights"); ?>" class="searchbtn" />
										</div>
									</div>
								</div>
								</form> 
							</div>-->
							<!-- international flight section -->
							<div class="bhoechie-tab-content <?php echo is_null($this->data['default_tab']) || !in_array($this->data['default_tab'], array('flights', 'hotels', 'tours')) || $this->data['default_tab'] === 'flights' ? 'active' : ''; ?>">
							 <form method="post" id="flight_search" action="<?php echo base_url('flight/lists');?>">
								<div class="col-xs-12 flight_module nopadding">
									<div class="col-xs-12 col-md-12 nopadding flight_check" style="margin-bottom:1%;">
										<div class="col-xs-12 nopadding flight_check" style="margin-bottom:1%;">
											<input type="radio" data-rule-required="true" name="flight_type" data-id="OW" id="oneway" class="css-checkbox flight_type" value="OneWay">
											<label for="oneway" class="css-label"><?php echo $this->lang->line("one_way_caps"); ?></label>
											<input type="radio" data-rule-required="true" name="flight_type" data-id="RT" id="roundtrip" checked="checked" class="css-checkbox flight_type" value="Return"/>
											<label for="roundtrip" class="css-label"><?php echo $this->lang->line("round_trip_caps"); ?></label>
											<input type="radio" data-rule-required="true" name="flight_type" data-id="MC" id="multicity" class="css-checkbox flight_type" value="OpenJaw" />
											<label for="multicity" class="css-label"><?php echo $this->lang->line("multi_city_caps"); ?></label>
										</div>
									</div>
									<div class = "normalsearch_div">
										<div class="col-xs-12 mbottom">
											<div class="row">
												<div class="col-md-5 col-sm-5 col-xs-12 nopadding inlabel margintop mnopadding_right">
													<label class="search_label required"><?php echo $this->lang->line("from"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
														<?php 
														
																$def_flight_from_city_text = $airports[DEFAULT_FLIGHT_ORIGIN]->city.', '.$airports[DEFAULT_FLIGHT_ORIGIN]->airport.', '.$airports[DEFAULT_FLIGHT_ORIGIN]->country. ' ('.DEFAULT_FLIGHT_ORIGIN.')';
																$def_flight_from_city_code = DEFAULT_FLIGHT_ORIGIN;
																$def_flight_to_city_text   = $airports[DEFAULT_FLIGHT_DESTINATION]->city.', '.$airports[DEFAULT_FLIGHT_DESTINATION]->airport.', '.$airports[DEFAULT_FLIGHT_DESTINATION]->country. ' ('.DEFAULT_FLIGHT_DESTINATION.')';
																$def_flight_to_city_code   = DEFAULT_FLIGHT_DESTINATION;
														

														 ?>
														<input type="text" autocomplete="off" autofocus="true" id="flight_originx" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group from_flight" value="<?php echo $def_flight_from_city_text; ?>"/>
														<input type="hidden" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="flight_origin" value="<?php echo $def_flight_from_city_code; ?>">

														<input type="hidden" data-rule-required="true" id="flight_originx_temp" name="flight_originx_temp" value="<?php echo $def_flight_from_city_text; ?>">
														<!-- <select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="flight_origin" class="dom_flight select2" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" id="dom_flight">
														</select> -->
													</div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-12 rarrow">
													<label class="search_label">&nbsp;</label>
													<img class="lazyload" data-original="<?php echo asset_url('images/roundarrow.png'); ?>" alt="" width="29" height="29" />
												</div>
												<div class="col-md-2 col-sm-2 col-xs-12 oarrow" style="display:none;">
													<label class="search_label">&nbsp;</label>
													<img class="lazyload" data-original="<?php echo asset_url('images/onewayarrow.png'); ?>" alt="" width="29" height="29" />
												</div>
												<div class="col-md-5 col-sm-5 col-xs-12 nopadding inlabel margintop mnopadding_right mleft">
													<label class="search_label required"><?php echo $this->lang->line("to"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
													<input type="text" autocomplete="off" id="flight_destinationx" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group to_flight" value="<?php echo $def_flight_to_city_text; ?>"/>
															<input type="hidden" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="flight_destination" value="<?php echo $def_flight_to_city_code; ?>">
															<input type="hidden" data-rule-required="true" id="flight_destination_temp" name="flight_destination_temp" value="<?php echo $def_flight_to_city_text; ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 mbottom">
											<div class="row">
												<div class="col-xs-12 col-sm-5 col-md-5 nopadding">
													<div id="departing" class="col-md-6 col-sm-6 col-xs-12 nopadding inlabel mright margintop">
														<label class="search_label required"><?php echo $this->lang->line("departure"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<input type="text" autocomplete="off" readonly="readonly"  data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="flight_departure" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="from_date" class="inputs_group1 from_date" />
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-12 ma_top nopadding_right margintop no_left mright inlabel returning_div">
														<label class="search_label required"><?php echo $this->lang->line("return"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<input type="text" autocomplete="off"  readonly="readonly" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-greater="#from_date" name="flight_arrival" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="to_date" class="inputs_group1 disable to_date" />
														</div>
													</div>
												</div>
											  <!--  <div class="col-md-2 col-sm-2 col-xs-12">
													<label class="search_label">&nbsp;</label>
												</div> -->
												<div class="col-xs-12 col-sm-7 col-md-7 nopadding">
													<div class="col-md-3 col-sm-3 col-xs-12 nopadding_right mright inlabel margintop">
														<label class="search_label"><?php echo $this->lang->line("adults"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select data-rule-required="true" name="adult" class="dropdown-select" id="adult">
																		<?php
																			for ($i=0; $i < 10; $i++){
																				$selected = ($i == 1)?'selected':'';
																				echo "<option ".$selected." value='$i'>$i</option>";
																			}
																		?>
																	</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-12 ma_top nopadding_right mright margintop no_left inlabel">
														<label class="search_label"><?php echo $this->lang->line("children"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="child" class="dropdown-select" id="child">
																		<?php
																			for ($i=0; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																	<span><?php echo $this->lang->line("child_age_limit"); ?></span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-12 ma_top nopadding_right margintop no_left inlabel">
														<label class="search_label"><?php echo $this->lang->line("infants"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="infant" class="dropdown-select" id="infant">
																		<?php
																			for ($i=0; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																	<span><?php echo $this->lang->line("infant_age_limit"); ?></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-md-3 col-sm-3 col-xs-12 ma_top nopadding_right margintop no_left inlabel">
														<label class="search_label"><?php echo $this->lang->line("class"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="class" class="dropdown-select">
																			<option value="Y"><?php echo $this->lang->line("economy_class"); ?></option>
																			<option value="S"><?php echo $this->lang->line("premium_economy_class"); ?></option>
																			<option value="C"><?php echo $this->lang->line("business_class"); ?></option>
																			<option value="J"><?php echo $this->lang->line("premium_business_class"); ?></option>
																			<option value="F"><?php echo $this->lang->line("first_class"); ?></option>
																			<option value="P"><?php echo $this->lang->line("premium_first_class"); ?></option>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
									<div class="multisearch_div" style="display:none">
										<div class="col-xs-12 mbottom">
											<div class="row">
												
													<div class="mtop">
														<div class="col-md-4 col-sm-4 col-xs-12 nopadding_left inlabel margintop mnopadding_right">
															<label class="search_label required"><?php echo $this->lang->line("from"); ?></label>
															<div class="clearfix"></div>
															<div class="inputtext">
																<input type="text" autocomplete="off" autofocus="true" disabled="disabled" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_origin" 
																id="mflight_origin1test"/>
																<input type="hidden" disabled="disabled" id="mflight_origin0"  data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="mflight_origin[]" value="">
															</div>
														</div>

														<div class="col-md-4 col-sm-4 col-xs-12 nopadding_left inlabel margintop mnopadding_right">
															<label class="search_label required"><?php echo $this->lang->line("to"); ?></label>
															<div class="clearfix"></div>
															<div class="inputtext">
																<input type="text" autocomplete="off" disabled="disabled" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_destination" 
																id="mflight_destination1test"/>
																<input type="hidden" disabled="disabled" id="mflight_destination0" data-rule-required="true"  data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="mflight_destination[]" value="">
															</div>
														</div>

														<div id="departing" class="col-md-2 col-sm-2 col-xs-12 nopadding_left mright mleft inlabel margintop">
															<label class="search_label required"><?php echo $this->lang->line("departure"); ?></label>
															<div class="clearfix"></div>
															<div class="inputtext">
																<input type="text" autocomplete="off" disabled="disabled" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="mflight_departure0" disabled="disabled" name="mflight_departure[]" class="inputs_group1 from_date" />
															</div>
														</div>

													</div>

												<div class="multi_flight">
													<div id="multi_flight" class="mtop" style="margin-top: 10px;">
														<div class="col-md-4 col-sm-4 col-xs-12 nopadding_left inlabel margintop mnopadding_right">
															<!-- <label class="search_label required"><?php echo $this->lang->line("from"); ?></label> -->
															<div class="clearfix"></div>
															<div class="inputtext">
																<input type="text" autocomplete="off" autofocus="true" disabled="disabled" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_origin" />
																<input type="hidden" disabled="disabled" id="mflight_origin1" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="mflight_origin[]" value="">
															</div>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12 nopadding_left inlabel margintop mnopadding_right">
															<!-- <label class="search_label required"><?php echo $this->lang->line("to"); ?></label> -->
															<div class="clearfix"></div>
															<div class="inputtext">
																<input type="text" autocomplete="off" disabled="disabled" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_destination" />
																<input type="hidden" disabled="disabled" id="mflight_destination1" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="mflight_destination[]" value="">
															</div>
														</div>
														<div id="departing" class="col-md-2 col-sm-2 col-xs-12 nopadding_left mright mleft inlabel margintop">
															<!-- <label class="search_label required"><?php echo $this->lang->line("departure"); ?></label> -->
															<div class="clearfix"></div>
															<div class="inputtext">
																<input type="text" autocomplete="off" disabled="disabled" data-rule-required="true" placeholder="<?php echo $this->lang->line("select_date"); ?>" name="mflight_departure[]" id="mflight_departure1" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-greater="#mflight_destination0" class="inputs_group1 from_date" />
															</div>
														</div>
														<div class="col-md-2 col-xs-2 mefullwd nopadadd mleft">
															<!-- <label class="search_label">&nbsp;</label> -->
															<div class="addflight add_stop_points"><span class="fa">+</span></div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 mbottom">
											<div class="row">
												<div class="col-xs-12 col-sm-12 col-md-12 nopadding">
													<div id="departing" class="col-md-3 col-sm-3 col-xs-12 nopadding_left mright inlabel margintop">
														<label class="search_label"><?php echo $this->lang->line("adults"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select data-rule-required="true" disabled="disabled" name="madult" class="dropdown-select">
																		<?php
																			for ($i=1; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div id="returning" class="col-md-3 col-sm-3 col-xs-12 ma_top nopadding_left mright margintop no_left inlabel">
														<label class="search_label"><?php echo $this->lang->line("children"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="mchild" disabled="disabled" class="dropdown-select">
																		<?php
																			for ($i = 0; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																	<span><?php $this->lang->line("child_age_limit"); ?></span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-12 ma_top nopadding_left margintop no_left inlabel nopadding_right">
														<label class="search_label"><?php echo $this->lang->line("infants"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="minfant" disabled="disabled" class="dropdown-select">
																		<?php
																			for ($i = 0; $i < 10; $i++)
																				echo "<option value='$i'>$i</option>";
																		?>
																	</select>
																	</div>
																	<span><?php $this->lang->line("infant_age_limit"); ?></span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-12 ma_top margintop no_left inlabel nopadding_right">
														<label class="search_label"><?php echo $this->lang->line("class"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="class" disabled="disabled" class="dropdown-select">
																			<option value="Y"><?php echo $this->lang->line("economy_class"); ?></option>
																			<option value="S"><?php echo $this->lang->line("premium_economy_class"); ?></option>
																			<option value="C"><?php echo $this->lang->line("business_class"); ?></option>
																			<option value="J"><?php echo $this->lang->line("premium_business_class"); ?></option>
																			<option value="F"><?php echo $this->lang->line("first_class"); ?></option>
																			<option value="P"><?php echo $this->lang->line("premium_first_class"); ?></option>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 nopadding">
										<div class="searchbutton">
											<input type="submit" value="<?php echo $this->lang->line("search_flights"); ?>" class="searchbtn" />
										</div>
									</div>
								</div>
								</form>
							</div>
							<!-- Hotel section -->
							<div class="bhoechie-tab-content tabc2 <?php echo $this->data['default_tab'] === 'hotels' ? 'active' : ''; ?>">
								<!--<form method="post" id="hotel_search" action="<?php echo base_url('hotel/lists');?>">
									<div class="col-xs-12 hotel_module nopadding">
										<div class="col-xs-12 mbottom nopadding city_details">
											<div>
												<div class="col-md-6 col-sm-6 col-xs-12 nopadding inlabel margintop rightpadding">
													<label class="search_label required"><?php echo $this->lang->line("search_by_city"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
														<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("search_by_city"); ?>" class="inputs_group hotel_city" />
														<input type="hidden" name="hotel_city" value="" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" />
													</div>
												</div>
												<div class="col-md-3 col-sm-3 col-xs-12 nopadding_right inlabel margintop rightpadding mnopadding_left">
													<label class="search_label required"><?php echo $this->lang->line("check_in"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
														<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="check_in" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="checkin_dt" class="inputs_group1 from_date" />
													</div>
												</div>
												<div class="col-md-3 col-sm-3 col-xs-12 nopadding_right inlabel no_left ma_top margintop mnopadding_left mright">
													<label class="search_label required"><?php echo $this->lang->line("check_out"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
														<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-greater="#checkin_dt" name="check_out" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="checkout_dt" class="inputs_group1 to_date" />
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 margintop nopadding rooms_details">
											<div class="col-md-12 col-sm-12 col-xs-12 nopadding room_details">
												<div class="col-md-6 col-sm-6 col-xs-12 nopadding person_details">
													<div class="col-md-4 col-sm-4 col-xs-12 nopadding_left inlabel rightpadding mnopadding_right mpad">
														<label class="search_label"><?php echo $this->lang->line("rooms"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="rooms" class="dropdown-select total_rooms">
																			<?php
																				for ($i = 1; $i < 4; $i++)
																					echo "<option value='$i'>$i</option>";
																			?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-4 col-sm-4 col-xs-12 nopadding inlabel no_left ma_top nopadding_right margintop mnopadding_right">
														<label class="search_label"><?php echo $this->lang->line("adults"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="adult[]" id="adult_0" class="dropdown-select">
																			<?php
																				for ($i = 1; $i < 5; $i++)
																					echo "<option value='$i'>$i</option>";
																			?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-4 col-sm-4 col-xs-12 nopadding_right inlabel no_left ma_top nopadding_right margintop mnopadding_left">
														<label class="search_label"><?php echo $this->lang->line("children"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<div class="roomblock">
																<div class="roomdetails">
																	<div class="dropdown">
																		<select name="children[]" id="children_0" class="dropdown-select room_children">
																			<?php
																				for ($i = 0; $i < 4; $i++)
																					echo "<option value='$i'>$i</option>";
																			?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6 nopadding children_age"></div>
											</div>
										</div>
										<div class="col-xs-12 nopadding_right">
											<div class="searchbutton">
												<input type="submit" value="<?php echo $this->lang->line("search_hotels"); ?>" class="searchbtn" />
											</div>
										</div>
									</div>
								</form> -->

                                <img src="comming/pic/coming_soon.png" width="450" alt="Comming Soon" style="opacity: 0.7; margin-top: 20px;">
							</div>
							<!-- tour section -->
							<div class="bhoechie-tab-content tabc3 <?php echo $this->data['default_tab'] === 'tours' ? 'active' : ''; ?>">
								<!-- <form name="tour_search" id="tour_search" method="get" action="<?php echo base_url('tour/search')?>">
									<div style="margin-bottom:1%;" class="col-xs-12 nopadding flight_check">
										 <input type="radio" checked="checked" class="css-checkbox1" id="pdomestic" value="1" name="pradio">
										<label class="css-label1 radGroup1" for="pdomestic"><?php echo $this->lang->line("domestic"); ?></label>
										<input type="radio"  class="css-checkbox1"  value="2" id="pinternational" name="pradio">
										<label class="css-label1 radGroup1" for="pinternational"><?php echo $this->lang->line("international"); ?></label>
									  
									</div>
									<div class="col-xs-12 hotel_module">
										<div class="tabspl">
											<div class="tabrow">
												<div class="col-xs-12 mbottom nopadding">
													<?php 
														if($this->session->flashdata('from_to_msg')){
														$status = $this->session->flashdata('from_to_msg');
														?>
													<div class="alert alert-block alert-success">
														<?php echo $status;?>
													</div>
													<?php } ?>
													<div class="col-md-6 col-sm-6 col-xs-12 nopadding_left mpad">
														<label class="search_label"><?php echo $this->lang->line("from_city"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="fromcity" class="tour_fromcity select2" placeholder="<?php echo $this->lang->line("from_city"); ?>" id="fromcity">
														</select>
															<input type="hidden" data-rule-required="true" id="from_hid" name="from_hid">
														</div>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-12 nopadding_right mright">
														<label class="search_label"><?php echo $this->lang->line("to_city"); ?></label>
														<div class="clearfix"></div>
														<div class="inputtext">
															<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="tocity" class="tour_tocity select2" placeholder="<?php echo $this->lang->line("to_city"); ?>" id="tocity">
														</select>
															
															<input type="hidden" data-rule-required="true" id="to_hid" name="to_hid">
															<input type="hidden" data-rule-required="true" id="arr_city" name="arr_city">
															<input id="page" name="page" type="hidden" value="0" />
														</div>
													</div>
												</div>
												<div class="col-md-4 col-sm-6 col-xs-12 nopadding_left mpad">
													<label class="search_label"><?php echo $this->lang->line("departure_date"); ?></label>
													<div class="clearfix"></div>
													<div class="inputtext">
														<input type="text" autocomplete="off" class="inputs_group1 from_date" name="checkin" id="tour_checkin" placeholder="<?php echo $this->lang->line("departure_date"); ?>" data-msg-required="<?php echo $this->lang->line("check_in_required"); ?>" data-rule-required="true" />
													</div>
												</div>
												<div class="col-md-4 col-sm-6 col-xs-12 nopadding_left mpad">
													<label class="search_label"><?php echo $this->lang->line("night_count"); ?></label>
													<div class="clearfix"></div>
													<div class="dropdown">
														<select class="dropdown-select" name="nights">
															<?php  for ($i=1; $i <=12; $i++) { 
																$selected = '';
																if($i == 1) $selected = 'selected';
																echo '<option value="'.$i.'" '.$selected.'>'.$i.' '.$this->lang->line("no_of_days").'</option>';
																}?>
														</select>
													</div>
												</div>
												<div class="col-xs-12 nopadding">
													<div class="add_tour_passenger"></div>
												</div>
												<div class="col-xs-12 nopadding">
													
													<div class="searchbutton">
														<input type="hidden" id="id_from_city" name="id_from_city">
														<input type="hidden" id="id_to_city" name="id_to_city">
														<input id="tour_search_btn"  type="submit" class="searchbtn" value="<?php echo $this->lang->line("search_package"); ?>">
													</div>
												</div>
												<div class="col-xs-1 nopadding install_div" data-toggle="modal" data-target="#payment_installation">
													<div class="installment"><?php echo $this->lang->line("installment"); ?></div>
													<div class="quesstion_mark">
														<img src="<?php echo asset_url('images/help_info.png'); ?>" alt=""  />
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</form> -->

                                <img src="comming/pic/coming_soon.png" width="450" alt="Comming Soon" style="opacity: 0.7; margin-top: 20px;">
							</div>
						</div>

                         
                        <div>
                       
                            <div class="img_center">
                              <img src="<?php echo asset_url('images/enamad-logo.png'); ?>" alt="" id="xlapwmcssguilbrhgwmd" style="cursor:pointer" onclick="window.open(&quot;https://trustseal.enamad.ir/Verify.aspx?id=38079&amp;p=fuixaqgwdrfsqgwljzpg&quot;, &quot;Popup&quot;,&quot;toolbar=no, location=no, statusbar=no, menubar=no, scrollbars=1, resizable=0, width=580, height=600, top=30&quot;)" />
                            </div>

                            <div class="img_center">
                              <!-- <img src="<?php echo asset_url('images/samandehi.png'); ?>" alt="" />
    https://logo.samandehi.ir/logo.aspx?id=68677&p=wlbqaqgwwlbqyndtyndt
                              -->

                              <img id='fukzoeukfukzjzpejzpe' style='cursor:pointer' onclick='window.open("https://logo.samandehi.ir/Verify.aspx?id=68677&p=gvkamcsigvkajyoejyoe", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt='logo-samandehi' src='<?php echo asset_url('images/logo_samandehi.png')?>'/>
                            </div>
                       
                        </div>


					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 nopadding_right">
						<div class="deals">
							<h3><?php echo $this->lang->line("deals"); ?></h3>

								<div class="flight_deals">
								<a class="buttons prev" href="#"><i class="fa fa-arrow-up"></i></a> 
								<div class="viewport">
									<ul class="overview home_deals">
									<?php
										if($deals !== false)
											foreach ($deals as $deal)
											{
												if($deal->category === "1")
												{
													echo 	"<li style='cursor:pointer;' data-category='tour' data-tour='".$deal->inputs."'>
																<div class='flightdeals'>
																<div class='flightimg'><img class='lazyload' data-original='".upload_url($deal->image)."' alt='' width='84' height='25' /></div>
																
																</div>
															</li>";
												}
												elseif($deal->category === "2")
												{
													echo 	"<li style='cursor:pointer;' data-category='flight' data-flight='".$deal->inputs."'>
																<div class='flightdeals'>
																<div class='flightimg'><img class='lazyload' data-original='".upload_url($deal->image)."' alt='' width='84' height='25' /></div>
																
																</div>
															</li>";
												}
												elseif($deal->category === "3")
												{
													echo 	"<li style='cursor:pointer;' data-category='hotel' data-hotel='".$deal->inputs."'>
																<div class='flightdeals'>
																<div class='flightimg'><img class='lazyload' data-original='".upload_url($deal->image)."' alt='' width='84' height='25' /></div>
																
																</div>
															</li>";
												}
											}
									?>
									</ul>
								</div>
								<a class="buttons next" href="#"><i class="fa fa-arrow-down"></i></a>
								</div>

					
						</div>

						<div class="deals1">
							<div class="col-md-12">
								<h3><?php echo $this->lang->line("special_deal_request"); ?></h3>
								<div class="insigndiv" style="padding: 0 0 10px 0;">
									<label class="error spcl_req_label" style="color:green"></label>
									<form class="special_deal_request_form" method="post">
										<div class="ritpul">
											<div class="rowput">
												<span class="icon glyphicon-envelope"></span>
												<!--<input class="form-control logpadding inp" type="email" name="email" placeholder="<?php echo $this->lang->line("email_address"); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("email_address_required"); ?>" aria-required="true">-->

<input class="form-control logpadding inp" type="text" name="name" placeholder="<?php echo $this->lang->line("enter_name"); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("name_requried"); ?>" aria-required="true">
											</div>
											<div class="rowput col-md-8 nopadding">
												<span class="fa fa-mobile"></span>
												<input class="form-control logpadding inp" type="text" name="phone" placeholder="09121234567" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("phone_number_required"); ?>" aria-required="true" id="phone1">
											</div>
											<div class="rowput col-md-4 nopadding_right">
												<input type="submit" class="submitlogin" value="<?php echo $this->lang->line("submit"); ?>"/>
											</div>

                                            <button type="button" class="btn btn-success" style="width: 100%;" data-toggle="modal" data-target="#lottery-modal"><?php echo $this->lang->line("lottery_tracking"); ?></button>
											<div class="clearfix"></div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="tour_deals">
				<div class="container nopadding">
					<div class="col-xs-12 nopadding">
						<div class="ourservices1">
							<h1 class="border-line"><?php echo $this->lang->line("tour_deals"); ?></h1>
							<div class="clearfix"></div>
							<?php  
								if(!empty($tour_deals))
								{
							?>
							<div class="best-tour-deals-slider">
							<?php
									foreach ($tour_deals as $tour_deal)
									{
										if($tour_deal->category == 1){$data_cat = 'tour';}elseif($tour_deal->category == 2){$data_cat = 'flight';}else{$data_cat = 'hotel';}
							?>
										<div class="slide" title="<?php echo $tour_deal->content; ?>">
											<img class="lazyload" data-original="<?php echo upload_url($tour_deal->image); ?>" alt="" width="170" />
											<div class="servicesblock1">
												<div class="hotelname"><?php echo $tour_deal->content; ?></div>
												<!--<div class="hotelname"><?php echo $tour_deal->hotel_name." ".$tour_deal->address; ?></div>
												<div class="starratingblock">
													<div class="starratings"><img class="lazyload" data-original="<?php echo asset_url('images/stars/star_'.$tour_deal->rating.'.png'); ?>" alt="" width="78" height="24" /></div>
												</div>
												<div class="price-rate"> <?php echo $this->data["default_currency"]." ".number_format(($tour_deal->price / $currency_val), "0", "", ","); ?> </div>-->
												<div class="clearfix"></div>
												<a data-category="<?php echo $data_cat;?>" data-<?php echo $data_cat;?>='<?php echo $tour_deal->inputs;?>'' href="javascript:void(0);" class="bookbut centerbook"><?php echo $this->lang->line("details"); ?></a>
											</div>
										</div>
							<?php
									}
							?>
							</div>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="bestdeals">
				<div class="container nopadding">
					<div class="ourservices1">
						
						<?php  
								if(!empty($hotel_deals))
								{
							?>
<h1 class="border-line"><?php echo $this->lang->line("best_hotel_deals"); ?></h1>
						<div class="clearfix"></div>
							<div class="best-hotel-deals-slider">
							<?php
									foreach ($hotel_deals as $hotel_deal)
									{
										if($hotel_deal->category == 1){$data_cat = 'tour';}elseif($hotel_deal->category == 2){$data_cat = 'flight';}else{$data_cat = 'hotel';}
							?>
										<div class="slide" title="<?php echo $hotel_deal->content; ?>">
											<img class="lazyload" data-original="<?php echo upload_url($hotel_deal->image); ?>" alt="" width="223" height="165" />
											<div class="servicesblock1">
												<div class="hotelname"><?php echo $hotel_deal->content; ?></div>
												<!--<div class="hotelname"><?php echo $hotel_deal->hotel_name." ".$hotel_deal->address; ?></div>
												<div class="starratingblock">
													<div class="starratings"><img class="lazyload" data-original="<?php echo asset_url('images/stars/star_'.$hotel_deal->rating.'.png'); ?>" alt="" width="78" height="24" /></div>
												</div>
												<div class="price-rate"> <?php echo $this->data["default_currency"]." ".number_format(($hotel_deal->price / $currency_val), "0", "", ","); ?> </div>-->
												<div class="clearfix"></div>
												<a data-category="<?php echo $data_cat;?>" data-<?php echo $data_cat;?>='<?php echo $hotel_deal->inputs;?>'' href="javascript:void(0);" class="bookbut centerbook"><?php echo $this->lang->line("details"); ?></a>
											</div>
										</div>
							<?php
									}
							?>
							</div>
							<?php
								}
							?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="tour_deals">
				<div class="container nopadding nac">
					<div class="col-xs-12 nopadding">
						<div class="col-md-8 col-sm-8 col-xs-12 nopadding_left attraction">
							<h3><?php echo $this->lang->line("national_attractions"); ?></h3>
							<?php if(!empty($national_attractions))
									{
										$idx = 0;
										foreach ($national_attractions as $attraction)
										{
											if($idx % 2 === 0)
												echo '<div class="col-md-6" style="padding:0px;">';
							?>		
								<div class="tourdeals">
									<div class="tourimg">
										<img class="lazyload" data-original="<?php echo upload_url($attraction->image); ?>" alt="" width="110" height="90">
									</div>
									<div class="tourdetails">
										<h1><?php echo $attraction->tour; ?></h1>
										<span class="loc"><?php echo $attraction->address; ?></span>
										<span><?php echo $attraction->content; ?></span>
										<div class="tourdetailsrate1 mar0"><span><?php echo $this->lang->line("starting_at"); ?></span> <?php echo $this->data["default_currency"]." ".number_format(($attraction->price / $currency_val), "0", "", ","); ?></div>
									</div>
									<div class="tourdealsrate">
										<div class="rateper">7
											<span><?php echo $this->lang->line("day"); ?></span>
										</div>
										<div class="clearfix"></div>
                                                                                 <?php  

                                         $url= $this->encrypt->decode(base64_decode($attraction->tour_link));

                                         $parts = explode("/",$url);

                                         if($parts[3]=="10020ir")
                                         {
                                           unset($parts[3]);
                                           $parts = array_values($parts);
                                         }

                                       	 $url= implode("/",$parts);

										 ?>    


										<a href="<?php echo $url; ?>" class="bookbut"><?php echo $this->lang->line("details"); ?></a>
									</div>
								</div>
							<?php
											if($idx !== 0 && $idx % 2 !== 0)
												echo "</div>";
											$idx++;
										}
										if($idx % 2 !== 0)
											echo "</div>";
									}
							?>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12 nopadding_right attraction">
							<h3><?php echo $this->lang->line("latest_news"); ?></h3>
							<div class="clearfix"></div>
							<div class="latest_news">
								<ul class="newslist">
								<?php
									if($latest_news !== false)
									{   //echo "<pre>";print_r($latest_news);exit(0);
										foreach ($latest_news as $news)
										{      $id=$news->id;
                                                                                        
											$is_more = strlen($news->content) > 150 ? true : false;
											$brief_desc = $news->content;
											if($is_more)
											{
												$brief_desc = explode(" ", substr($news->content, 0 , 150));
												array_pop($brief_desc);
												$brief_desc = implode(" ", $brief_desc);
											}



											echo "<li>".$brief_desc.($is_more ? "... <a data-full-content='".str_replace("'", "&prime;", $news->content)."' class='read_full_latest_news' href='javascript:void(0);' onClick='callajax($id);'>".$this->lang->line("read_more")."</a>" : "")."
											</li>";
										}
									}
								?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
				$this->load->view("common/pop-ups");
				$this->load->view("common/footer");
				?>
		</div>
		<div class="hidden slider_images_available">
		<?php
			$bg_sliders = $this->Common_model->get_active_sliders();
			if($bg_sliders !== false)
				foreach ($bg_sliders as $bg_slider)
					echo "<p>".upload_url($bg_slider->image)."</p>";
		?>
		</div>
	</body>
    <script type="text/javascript" src="assets/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>

<script>
    jQuery("#lottery-status-check-btn").click(function(){
        var LotteryPhoneNo = jQuery("#lottery-phone").val();
        if (LotteryPhoneNo === "" || LotteryPhoneNo.length != 11) {
            alert("شماره تلفن خود را صحیح وارد نمایید.");
            return false;
        }
        jQuery.ajax({
            'url': 'action.php?act=checkLottery',
            'data': {
                'phone': LotteryPhoneNo
            },
            'dataType': 'text',
            'type': 'POST',
            'success': function(res) {
                if (res < 1) {
                    jQuery("#lottery-status-message").css({
                        'margin-top': '10px',
                        'color': 'red'
                    }).text('متاسفانه شما به عنوان برنده قرعه کشی انتخاب نشده اید.').fadeIn();
                } else {
                    jQuery("#lottery-status-message").css({
                        'margin-top': '10px',
                        'color': 'green'
                    }).text('شما برنده ی قرعه کشی شده اید.').fadeIn();
                }
            }
        });
    });

    $.fn.preload = function() {
        this.each(function(){
            $('<img/>')[0].src = this;
        });
    }

    // Usage:

    $(['upload_files/slider_images/4eb38996c584295e9172f9b6cbbe795c.jpg',
        'upload_files/slider_images/6fbb0b3b1064035d6617e15262651504.jpg',
        'upload_files/slider_images/49d0ca216ab06e1b92ae3c9233db54ca.jpg']).preload();

    jQuery(document).ready(function(){
        
    });
    
    jQuery(window).load(function () {
        jQuery("#main-loader").delay(2000).fadeOut(1000);
    
    });
</script>

</html>
