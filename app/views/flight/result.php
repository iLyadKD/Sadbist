<!DOCTYPE html>
<html>
	<?php
	$this->load->view("common/head");
	$cabin_type_text = array("Y" => $this->lang->line("economy_class"), "S" => $this->lang->line("premium_economy_class"), "C" => $this->lang->line("business_class"), "J" => $this->lang->line("premium_business_class"), "F" => $this->lang->line("first_class"), "P" => $this->lang->line("premium_first_class"));
	$trip_type = array("OneWay" => $this->lang->line("one_way"), "Return" => $this->lang->line("round_trip"), "OpenJaw" => $this->lang->line("multi_city"));

$irramount = showCurrency('USD');
	
	?>
<body>
<div id="wrapper">
<?php
	$this->load->view("common/header");
?>
	<!-- <span class="cart_icon"><i class="fa fa-cart-plus"></i></span>  -->
	<div class="clearfix"></div>
	<div class="flight_block_modify">
		<div class="container nopadding pd15">
			<div class="row">
				<!-- <div class="col-xs-12 nopadding">
					<div class="sortblock">
						<div class="col-md-2 col-sm-6 col-xs-12 border-rt">
							<div class="checkin"> <img src="<?php echo base_url('assets/images/take1.png')?>" alt="">
								<span><?php echo $search_data->flight_type === MULTICITY ? $airports[$search_data->mflight_origin[0]]->city." (".$search_data->mflight_origin[0].")" : $airports[$search_data->flight_origin]->city." (".$search_data->flight_origin.")"; ?>
								</span>
							</div>
						</div>
						<div class="col-md-2 col-sm-6 col-xs-12 border-rt">
							<div class="checkin"> <img src="<?php echo base_url('assets/images/land1.png')?>" alt="">
								<span><?php echo $search_data->flight_type === MULTICITY ? $airports[$search_data->mflight_destination[count($search_data->mflight_destination) - 1]]->city." (".$search_data->mflight_destination[count($search_data->mflight_destination) - 1].")" : $airports[$search_data->flight_destination]->city." (".$search_data->flight_destination.")"; ?>
								</span>
							</div>
						</div>

						<div class="col-md-2 col-sm-6 col-xs-12 border-rt">
							<div class="passengers">
							<?php echo $this->lang->line("journey"); ?> <?php echo @$trip_type[$search_data->flight_type]; ?><br/>
							<?php echo $this->lang->line("date"); ?> : <?php echo $search_data->flight_type === MULTICITY ? $search_data->mflight_departure[0] : $search_data->flight_departure; ?>
							</div>
						</div>
						<div class="col-md-2 col-sm-4 col-xs-12 border-rt">
							<div class="passengers">
								<?php echo $this->lang->line("class"); ?>: <?php echo $search_data->class === "" ? $this->lang->line("any_class") : @$cabin_type_text[$search_data->class]; ?></div>
						</div>
						<div class="col-md-2 col-sm-4 col-xs-12 border-rt">
							<div class="passengers"><?php echo $this->lang->line("passengers"); ?> <br/>
								<div class="col-md-4 col-sm-4 col-xs-4 nopadding"> <span class="adultbox"><?php echo $search_data->flight_type === MULTICITY ? $search_data->madult : $search_data->adult; ?></span> </div>
								<div class="col-md-4 col-sm-4 col-xs-4 nopadding"> <span class="childbox"><?php echo $search_data->flight_type === MULTICITY ? $search_data->mchild : $search_data->child; ?></span> </div>
								<div class="col-md-4 col-sm-4  col-xs-4 nopadding"> <span class="infantbox"><?php echo $search_data->flight_type === MULTICITY ? $search_data->minfant : $search_data->infant; ?></span> </div>
							</div>
						</div>
						<div class="col-md-2 col-sm-4 col-xs-12"> <a href="javascript:void(0);" class="modify_search_btn modify_flight_search"><?php echo $this->lang->line("modify"); ?></a></div>
					</div>
				</div> -->
			</div>
		</div>
		<?php $this->load->view("flight/modify_search", $search_data); ?>
	</div>
	<div class="clearfix"></div>
	<div class="middle_content">
		<div class="container">
			<div class="col-xs-12">
				<div class="col-xs-12 nopadding pd15">
					<div class="flight_results_block" ng-controller="flight_controller">
						<div class="hotelfilter_block">
							<div class="col-md-6 col-sm-6  col-xs-12 nopadding">
								<!-- <h3 class="filtertitle"><?php echo $this->lang->line("filters"); ?></h3> -->

								<!-- <h1 class="place_title" style="background: #fff; text-align: left; padding-left: 10px;" ng-cloak> 
								<?php
								if(in_array($search_data->flight_type, array("OneWay", "Return")))
									echo "<span>".$airports[$search_data->flight_origin]->city." (".$search_data->flight_origin.")</span> to <span>".$airports[$search_data->flight_destination]->city." (".$search_data->flight_destination.")</span> - ".$search_data->flight_departure;
								if($search_data->flight_type === "Return")
									echo ", <span>".$airports[$search_data->flight_destination]->city." (".$search_data->flight_destination.")</span> to <span>".$airports[$search_data->flight_origin]->city." (".$search_data->flight_origin.")</span> - ".$search_data->flight_arrival;
								if($search_data->flight_type === MULTICITY)
								{
									$length_idx = count($search_data->mflight_origin);
									for ($i = 0; $i < $length_idx; $i++)
									{
										echo "<span>".$search_data->mflight_origin[$i]."</span> to <span>".$search_data->mflight_destination[$i]."</span> - ".$search_data->mflight_departure[$i];
										if($i < ($length_idx -1))
											echo ", ";
									}
								}
								?>
								</h1> -->
							</div>
							<div class="col-md-6 col-sm-6  col-xs-12 nopadding">
								<div class="sorting1">
									<ul>
										<li><?php echo $this->lang->line("sort_by_coaln"); ?></li>
										<li><a href="javascript:void(0);" ng-click="sort_me('departure')"><?php echo $this->lang->line("departure"); ?><i class="fa fa-sort" ng-class="order_by !== 'departure' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc')"></i></a></li>
										<li><a href="javascript:void(0);" ng-click="sort_me('arrival')"><?php echo $this->lang->line("arrival"); ?><i class="fa fa-sort" ng-class="order_by !== 'arrival' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc')"></i></a></li>
										<li><a href="javascript:void(0);" ng-click="sort_me('duration')"><?php echo $this->lang->line("duration"); ?><i class="fa fa-sort" ng-class="order_by !== 'duration' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc')"></i></a></li>
										<li><a href="javascript:void(0);" ng-click="sort_me('price')"><?php echo $this->lang->line("price"); ?><i class="fa fa-sort" ng-class="order_by !== 'price' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-amount-asc' : 'fa-sort-amount-desc')"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-2 nopadding">
						<form action="javascript:void(0);" method="post" id="filter_form">
							<div class="filter_block">
								<div class="price_filter">
									<div class="filterbg pricebg" id="price_Sec">
										<h1><?php echo $this->lang->line("price"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricerange pricesection" id="show_price" ng-clock ng-show="all_total_count > 0">
										<div class="price-slider"></div>
										<input name="price_filter" type="hidden" ng-value="cmin_price+'-'+cmax_price"><?php 

				                                     	if($_SESSION['default_language']=='en')
				                                     		{ echo $this->data["default_currency"]; } 
														else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
														?>
										<strong style="float:left;" ng-bind="(cmin_price)"></strong> &nbsp; <strong style="float:right;" ng-bind="(cmax_price)"></strong>
									</div>
								</div>
								<div class="Fare_type">
									<div class="filterbg farebg" id="Fare_type">
										<h1><?php echo $this->lang->line("stop_over"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricesection" id="show_Fare" style="padding-bottom:15px;" ng-clock ng-show="all_total_count > 0">
										<div class="check_box" ng-cloak ng-repeat="stop in stops">
											<input type="checkbox" id="stop_{{stop.val}}" ng-click="filter_data()" checked="checked" ng-value="stop.val" name="stops[]" />
											<label for="stop_{{stop.val}}"> <span ng-bind="stop.text"></span> </label>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="price_filter">
									<div class="filterbg pricebg" id="price_Sec">
										<h1><?php echo $this->lang->line("departure_time"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricesection" id="show_price" ng-clock ng-show="all_total_count > 0">
										<div class="departure-slider"></div>
										<input name="departure_filter" type="hidden" ng-value="dmin_time+'-'+dmax_time">
										<strong style="float:left;" ng-bind="dmin_time"></strong> &nbsp; <strong style="float:right;" ng-bind="dmax_time"></strong>
									</div>
								</div>
								<?php if($search_data->flight_type === ROUNDTRIP)
								{
								?>

								<div class="price_filter">
									<div class="filterbg pricebg" id="price_Sec">
										<h1><?php echo $this->lang->line("return_time"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricesection" id="show_price" ng-clock ng-show="all_total_count > 0">
										<div class="return-slider"></div>
										<input name="return_filter" type="hidden" ng-value="rmin_time+'-'+rmax_time">
										<strong style="float:left;" ng-bind="rmin_time"></strong> &nbsp; <strong style="float:right;" ng-bind="rmax_time"></strong>
									</div>
								</div>
								<?php
								}
								?>
								<div class="air_lines">
									<div class="filterbg pricebg" id="Air_line">
										<h1><?php echo $this->lang->line("airlines"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricesection" ng-clock ng-show="all_total_count > 0">
										<!-- <div class="check_box mn_margin" ng-cloak>
											<input type="checkbox" id="airline_NaN" class="toggle_airlines" ng-checked="filter_modified"/>
											<label for="airline_NaN"> <span><?php echo $this->lang->line("all_airlines"); ?></span> </label>
											<div class="airline_logo"></div>
											<div class="clearfix"></div>
										</div> -->
										<div class="check_box mn_margin" ng-cloak ng-repeat="airline in price_matrix | orderBy:[airline.airline_name]">
											<input type="checkbox" id="airline_{{$index}}" ng-click="filter_data()" name="airlines[]" ng-value="airline.airline_code" />
											<label for="airline_{{$index}}"> <span ng-bind="airline.airline_name"></span> </label>
											<div class="airline_logo"><img ng-src="{{airline.airline_code | airline_image}}" alt=""/></div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								<div class="air_ports">
									<div class="filterbg pricebg" id="Air_port">
										<h1><?php echo $this->lang->line("airports"); ?><span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricesection" ng-clock ng-show="all_total_count > 0">
										<!-- <div class="check_box mn_margin" ng-cloak>
											<input type="checkbox" id="airport_NaN" class="toggle_airports" ng-checked="filter_modified"/>
											<label for="airport_NaN"> <span><?php echo $this->lang->line("all_airports"); ?></span> </label>
											<div class="airline_logo"></div>
											<div class="clearfix"></div>
										</div> -->
										<div class="check_box mn_margin" ng-cloak ng-repeat="airport in airports | orderBy:[airports.airport_code]">
											<input type="checkbox" id="airport_{{$index}}" ng-click="filter_data()" name="airports[]" ng-value="airport.airport_code" />
											<label for="airport_{{$index}}"> <span ng-bind="airport.city"></span> </label>
											<div class="airline_logo" ng-bind="airport.airport_code"></div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
							</form>
						</div>
						<div class="col-md-10 nopadding_right">
							<?php $this->load->view("common/notification"); ?>
							<div class="col-md-12 col-xs-12 nopadding">
								<ul ng-show="all_total_count > 0" ng-cloak class="cd-tabs-content" style="height: auto; overflow:auto;">
									<!-- Inbox content-->
									<li class="selected" data-content="inbox">
										<div id="tab1">
											<div class="booking_deposit1">
												<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border:none;">
													<tbody>
														<tr>
															<td align="left" width="140" valign="top" style="border:none;"><table cellspacing="0" cellpadding="0" border="0" bgcolor="#e7f5fd" align="left" width="140" class="matrix_fixed_part_main">
																	<tbody>
																		<tr style="border:none;">
																			<td align="center" height="30" style="border:none;">&nbsp;</td>
																		</tr>
																		<tr style="border:none;">
																			<td align="center" bgcolor="#f9f9f9" valign="middle" height="33" style="border:none;"><div style="color:#fff;"><!-- <?php echo $this->lang->line("all_flights"); ?> --></div></td>
																		</tr>
																		<tr style="border:none;">
																			<td align="center" style="border:none;">
																				<div ng-repeat = "stop in stops" class="text13 search_result_border_bottom_part" style="margin-bottom:1px; color:#333; line-height:30px; font-size:11px;padding:0 12px; border-right: 1px solid #ccc;"><a ng-bind="stop.text"></a></div>
																			</td>
																		</tr>
																	</tbody>
																</table></td>
															<td align="left" valign="top" style="border:none;">
															<div id="matrix_container">
																	<div class="content_matrix" ng-repeat="matrix in price_matrix">
																		<table ng-init="prices_list = (matrix.total_price | split_price:',')" cellspacing="0" cellpadding="0" border="0" align="center" width="100" class="matrix_tab airline_nm" name="airline_filter">
																			<tbody>
																				<tr>
																					<td bgcolor="#FFFFFF" align="center" height="32"><a  href="javascript:void(0);" ng-click="filter_airline(matrix.airline_code)"><img border="0" ng-src="{{matrix.airline_code | airline_image}}"></a></td>
																				</tr>
																				<tr>
																					<td bgcolor="#f5f5f5" align="center" height="30px" style="border-bottom: 1px solid #CCCCCC;"><a  href="javascript:void(0);" ng-click="filter_airline(matrix.airline_code)"><span class="text13 " style="font-size:11px; color:#114879; line-height:15px;" ng-bind="matrix.airline_name"></span></a></td>
																				</tr>
																				<tr ng-repeat="matrix_stop in stops">
																					<td align="center" ng-init="price_exists = stop_exists(prices_list, matrix_stop.val)" class="matri">
																					<a style="cursor: pointer;" ng-if="price_exists > 0" ng-click="filter_airline_price_stop(matrix.airline_code, price_exists, matrix_stop.val)" ng-bind="(price_exists | custom_currency:currency:currency_val)"></a>
																					<a ng-if="price_exists === 0" >---</a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																	<!-- end ngRepeat: (a,m) in matrixs -->
																</div></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</li>
								</ul>
								<div class="clearfix"></div>
		
								<!--  <div class="ad" style="margin:20px 0px;"><img src="images/miniad.jpg" alt="" /></div>-->
								
                                <div class="flight_titles">
											<div class="col-xs-12 nopadding">
											<div class="col-md-10 col-sm-10 nopadding_left">
												<div class="col-md-2 col-sm-2 nopadding_right">
														<div class="arrival"><?php echo $this->lang->line("airline"); ?></div>
												</div>

												<div class="col-md-2 col-sm-2"> 
												<div class="arrival"><?php echo $this->lang->line("stops"); ?></div>
												</div>
													
											
												<div class="col-md-3 col-sm-3 nopadding">
													<div class="destination">
														<?php echo $this->lang->line("departure"); ?>
													</div>
												</div>
															
												<div class="col-md-3 col-sm-3 nopadding">
													<div class="arrival">
														<?php echo $this->lang->line("arrival"); ?>
													</div>
												</div>

												<div class="col-md-2 col-sm-2 nopadding">
													<div class="arrival">
														<?php echo $this->lang->line("duration"); ?>
													</div>
												</div>
													
												</div>
												
											</div>

										</div>

								<div class="flight_result_block">
									<div ng-show="total_count < 0" class="loading"></div>
									<div ng-cloak ng-show="total_count === '0' && all_total_count > '0'">
										<h4 class="noresults">
											<img src="<?php echo base_url('assets/images/no_results.png')?>">
										<?php echo $this->lang->line("no_filter_result"); ?>
										</h4>
									</div>
									<div ng-cloak ng-if="total_count === '0' && all_total_count === '0'">
										<h4 class="noresults">
											<img src="<?php echo base_url('assets/images/no_results.png')?>">
										<?php echo $this->lang->line("no_search_result"); ?>
										</h4>
									</div>
									<div class="clearfix"></div>
									
									<div ng-cloak ng-show="total_count > 0" class="flightlist"  dir-paginate="flight_row in flights | itemsPerPage:page_limit" total-items="total_count">
										<div class="flight_result">
											<div class="col-xs-12 nopadding">
											<div class="col-md-10 col-sm-10 nopadding">
											<div class="flight_middle" ng-repeat="departure_details in flight_row.departures track by $index">
												<div class="col-md-2 col-sm-2 nopadding">
													<div class="flight_left"> 
													<img ng-src="{{departure_details.airline | airline_image}}"/> 
													<div class="clearfix"></div>
													<!--<div class="flight_title flight_nos" ng-bind="departure_details.airline+''+departure_details.flight_no">
														

													</div>-->
<div class="flight_title flight_nos" ng-bind="departure_details.flight_no">
														

													</div>

														<div class="clearfix"></div>
														<div class="flight_title" ng-bind="(departure_details.airline | airline_name:airlines)"></div>
														
													</div>
												</div>
												<div class="col-md-2 col-sm-2 planecenter">
													<div class="dateinfo" ng-bind="departure_details.stop+' <?php echo $this->lang->line("stop_small"); ?>'"></div><br/>
													<div class="dateinfo" ng-bind="departure_details.cabin_type"></div>
												</div>
													
														
												<div class="col-md-3 col-sm-3 nopadding">
													<div class="destination">
														<div class="from_place" ng-bind-html="(departure_details.departure_from | airport_city:airports)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo" ng-bind="(departure_details.departure_dttm | readable_dttm)"></div><div class="clearfix"></div>
														<div class="dateinfo fa_content"><span ng-bind="(departure_details.departure_dttm | fa_readable_dt)"></span> <span ng-bind="(departure_details.departure_dttm | fa_readable_tm)"></span> </div>
													</div>
												</div>
												
												<div class="col-md-3 col-sm-3 nopadding">
													<div class="arrival">
														<div class="from_place" ng-bind-html="(departure_details.arrival_to | airport_city:airports)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo" ng-bind="(departure_details.arrival_dttm | readable_dttm)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo fa_content"><span ng-bind="(departure_details.arrival_dttm | fa_readable_dt)"></span> <span ng-bind="(departure_details.arrival_dttm | fa_readable_tm)"></span> </div>
													</div>
												</div>

												<div class="col-md-2 col-sm-2 nopadding">
													<div class="arrival">
														<div class="col-xs-12 time nopadding" ng-bind="departure_details.duration + ' <?php echo $this->lang->line("short_hours"); ?>'"></div>
														<div ng-if="departure_details.wait_place.length > 0" class="col-xs-12 time nopadding" ng-bind-html="(departure_details.wait_tm | mins_to_time)+' <?php echo $this->lang->line("stop_over_at_small"); ?> '+(departure_details.wait_place | airport_city:airports)"></div>
													</div>
												</div>
												
												<div class="col-md-12 col-sm-12 nopadding operating_carrier ddd" ng-if="departure_details.airline !== departure_details.operating_airline" ng-bind="'<?php echo $this->lang->line("operating_carrier_changed_to"); ?> '+departure_details.operating_airline_name">
												</div>	
											</div>
											<hr ng-if="flight_row.arrivals.length > 0">
											<div class="flight_middle" ng-repeat="arrival_details in flight_row.arrivals track by $index">
												<div class="col-md-2 col-sm-2 nopadding">
													<div class="flight_left"> <img ng-src="{{arrival_details.airline | airline_image}}"/> 
                                                     <div class="clearfix"></div>
													<!--<div class="flight_title flight_nos" ng-bind="arrival_details.airline+''+arrival_details.flight_no"></div>-->

<div class="flight_title flight_nos" ng-bind="arrival_details.flight_no"></div>
														<div class="clearfix"></div>
														<div class="flight_title" ng-bind="(arrival_details.airline | airline_name:airlines)"></div>
														
													</div>
												</div>
												<div class="col-md-2 col-sm-2 planecenter">
													<!-- <h1 ng-bind="flight_row.stops_text"></h1><br/> -->
													<div class="dateinfo" ng-bind="arrival_details.stop+' <?php echo $this->lang->line("stop_small"); ?>'"></div><br/>
													<div class="dateinfo" ng-bind="arrival_details.cabin_type"></div>
												</div>
												<div class="col-md-3 col-sm-3 nopadding">
													<div class="destination">
														<div class="from_place" ng-bind-html="(arrival_details.departure_from | airport_city:airports)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo" ng-bind="(arrival_details.departure_dttm | readable_dttm)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo fa_content"><span ng-bind="(arrival_details.departure_dttm | fa_readable_dt)"></span> <span ng-bind="(arrival_details.departure_dttm | fa_readable_tm)"></span> </div>
													</div>
												</div>
												
												<div class="col-md-3 col-sm-3 nopadding">
													<div class="arrival">
														<div class="from_place" ng-bind-html="(arrival_details.arrival_to | airport_city:airports)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo" ng-bind="(arrival_details.arrival_dttm | readable_dttm)"></div>
														<div class="clearfix"></div>
														<div class="dateinfo fa_content"><span ng-bind="(arrival_details.arrival_dttm | fa_readable_dt)"></span> <span ng-bind="(arrival_details.arrival_dttm | fa_readable_tm)"></span> </div>
													</div>
												</div>

												<div class="col-md-2 col-sm-2 nopadding">
													<div class="arrival">
														<div class="col-xs-12 time nopadding" ng-bind="arrival_details.duration + ' <?php echo $this->lang->line("short_hours"); ?>'"></div>
														<div ng-if="arrival_details.wait_place.length > 0" class="col-xs-12 time nopadding" ng-bind-html="(arrival_details.wait_tm | mins_to_time)+' <?php echo $this->lang->line("stop_over_at_small"); ?> '+(arrival_details.wait_place | airport_city:airports)"></div>
													</div>
												</div>
											</div>

											<div class="col-md-12 col-sm-12 nopadding operating_carrier" ng-if="arrival_details.airline !== arrival_details.operating_airline" ng-bind="'<?php echo $this->lang->line("operating_carrier_changed_to"); ?> '+arrival_details.operating_airline_name">
											</div>
										</div>
												<div class="col-md-2 col-sm-2 nopadding_right">
													<div class="flight_right">
														<div class="col-md-12 col-sm-12 nopadding text-center">
															
															<div class="clearfix"></div>
															<label><?php echo $this->lang->line("total_cost"); ?> </label><br/>

<?php 

	                                     	if($_SESSION['default_language']=='en')
	                                     		{ echo $this->data["default_currency"]; } 
											else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
											?>
															<!--<h2 ng-if="flight_row.display_price <= 0" ng-bind="(flight_row.total_cost)"></h2>
															<h2 ng-if="flight_row.display_price > 0" ng-bind="(flight_row.display_price)"></h2>-->

<?php
if($_SESSION['default_currency']=="USD"){
?>																
<h2 ng-if="flight_row.display_price <= 0" ng-bind="(flight_row.total_cost)*<?php echo $irramount; ?> | number:2"></h2>
<h2  ng-if="flight_row.display_price > 0" ng-bind="(flight_row.total_cost)*<?php echo $irramount; ?>| number:2"></h2>
<?php } else { ?>
<h2 ng-if="flight_row.display_price <= 0" ng-bind="(flight_row.total_cost)"></h2>
<h2 ng-if="flight_row.display_price > 0" ng-bind="(flight_row.display_price)"></h2>
<?php } ?>




														</div>
														<div class="col-md-12 col-sm-12 nopadding">
															<div class="flight_select">
																<button ng-click="flight_validate(flight_row.id)" class="select_btn btn-1e stbtn"><?php echo $this->lang->line("book_now"); ?></button>
															</div>
															
														</div>
													</div>
													<div class="col-md-12 flinks nopadding">
                                                    <a class="col-sm-6 text-center" href="javascript:void(0);" ng-click="fare_details(flight_row.id)"><?php echo $this->lang->line("fare_rules"); ?></a>
                                                    <a class="col-sm-5 text-center" href="javascript:void(0);" ng-click="baggage_details(flight_row.id)"><?php echo $this->lang->line("baggages"); ?></a>
                                                    <a class="col-sm-12 text-center fare_breakdown" href="javascript:void(0);"><?php echo $this->lang->line("fare_breakdown"); ?></a>
													</div>
												</div>
											</div>
                                           <div class="clearfix"></div>

                                           <div class="fare_breakdown_details" style="display:none;">
												<div class="col-md-12">
												<table class="table table-bordered">
													<tr>
														<th><?php echo $this->lang->line("passenger_type"); ?></th>
														<th><?php echo $this->lang->line("base_fare"); ?>(<?php 

				                                     	if($_SESSION['default_language']=='en')
				                                     		{ echo $this->data["default_currency"]; } 
														else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
														?>)</th>
														<th><?php echo $this->lang->line("tax"); ?>(<?php 

				                                     	if($_SESSION['default_language']=='en')
				                                     		{ echo $this->data["default_currency"]; } 
														else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
														?>)</th>
														<th><?php echo $this->lang->line("cost"); ?>(<?php 

				                                     	if($_SESSION['default_language']=='en')
				                                     		{ echo $this->data["default_currency"]; } 
														else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
														?>)</th>
														<th><?php echo $this->lang->line("total_cost"); ?>(<?php 

				                                     	if($_SESSION['default_language']=='en')
				                                     		{ echo $this->data["default_currency"]; } 
														else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
														?>)</th>
													</tr>
													<tr ng-repeat="fare_breakdown in flight_row.prices">
														<td ng-bind="fare_breakdown.person_type+' X '+fare_breakdown.quantity"></td>
														<td ng-bind="fare_breakdown.base_cost"></td>
														<td ng-bind="fare_breakdown.total_tax"></td>
														<td ng-bind="fare_breakdown.total_cost"></td>
														<td ng-bind="(fare_breakdown.total_cost * fare_breakdown.quantity)"></td>
													</tr>
													<tr>
													<td colspan="3"></td>
													<td><?php echo $this->lang->line("total_cost_colan"); ?>	<?php 

	                                     	if($_SESSION['default_language']=='en')
	                                     		{ echo $this->data["default_currency"]; } 
											else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
											?></td>
											
													<td ng-bind="flight_row.total_cost"></td>
												</table>
												</div>
                                           </div>
										</div>
									</div>
									<div class="clearfix"></div>
									<dir-pagination-controls ng-show="total_count > 0" max-size="7"  default-page="pageno" direction-links="true" boundary-links="true" on-page-change="get_data(newPageNumber)" >
									</dir-pagination-controls>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>  
<?php 
	$this->load->view('common/pop-ups');
	$this->load->view('common/footer');
?>

	<div id="faremodal" class="modal fade" role="dialog">
		<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content modal-content1">
		<div class="modal-header modal-header1">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php echo $this->lang->line("fare_rules"); ?></h4>
		</div>

		<div class="modal-body scroll_body">
			<div class="col-xs-12 nopadding">
				<div class="col-md-12 airrules_more_details">
					<div class="loading"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		</div>
		</div>
	</div>

	<div id="baggagemodal" class="modal fade" role="dialog">
		<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content modal-content1">
		<div class="modal-header modal-header1">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php echo $this->lang->line("baggage_rules"); ?></h4>
		</div>

		<div class="modal-body scroll_body">
			<div class="col-xs-12 nopadding">
				<div class="col-md-12 baggage_details">
					<div class="loading"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		</div>
		</div>
	</div>
	<div id="revalidatemodal" class="modal fade in" role="dialog" style="background-color : rgba(0, 0, 0, 0.7)">
		<div class="loading"></div>
		<div style="color:white;text-align:center;"><?php echo $this->lang->line("checking_additional_details"); ?></div>
	</div>
	<div class="hide">
		<a href="#" data-toggle="modal" class="hide" data-target="#faremodal"><a/>
		<a href="#" data-toggle="modal" class="hide" data-target="#baggagemodal"><a/>
	</div>
<script type="text/javascript" data-main="<?php echo asset_url('js/config'); ?>" src="<?php echo asset_url('js/require.js'); ?>"></script>
</body>
</html>
