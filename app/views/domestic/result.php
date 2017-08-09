<!DOCTYPE html>
<html>
	<?php
	$include["css"][] = "select2/select2";
	$this->load->view("common/head", $include);
	$trip_type = array("OneWay" => $this->lang->line("one_way"), "Return" => $this->lang->line("round_trip"));

	$irramount = showCurrency('USD');

	//echo "<pre>".$irramount;exit();

	?>
	<body>

		<div id="wrapper">
		<?php
			$this->load->view("common/header");
		?>
		<!-- <span class="cart_icon"><i class="fa fa-cart-plus"></i></span>  -->
		<div class="clearfix"></div>
		<div class="flight_block_modify">
			<div class="container nopadding">
				<div class="row">
					<!-- <div class="col-xs-12 nopadding">
						<div class="sortblock">
							<div class="col-md-3 col-sm-6 col-xs-6 border-rt">
								<div class="checkin"> <img src="<?php echo base_url('assets/images/take1.png')?>" alt="">
									<span><?php echo $airports[$search_data->flight_origin]->city." (".$search_data->flight_origin.")"; ?>
									</span>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-6 border-rt">
								<div class="checkin"> <img src="<?php echo base_url('assets/images/land1.png')?>" alt="">
									<span><?php echo $airports[$search_data->flight_destination]->city." (".$search_data->flight_destination.")"; ?>
									</span>
								</div>
							</div>

							<div class="col-md-2 col-sm-4 col-xs-6 border-rt">
								<div class="passengers">
								<?php echo $this->lang->line("journey"); ?> <?php echo @$trip_type[$search_data->flight_type]; ?><br/>04-03-2017
								<?php echo $this->lang->line("date"); ?> : <?php echo $search_data->flight_departure; ?>
								</div>
							</div>
							<div class="col-md-2 col-sm-4 col-xs-6 border-rt">
								<div class="passengers"><?php echo $this->lang->line("passengers"); ?> <br/>
									<div class="col-md-4 col-sm-4 col-xs-4 nopadding"> <span class="adultbox"><?php echo $search_data->adult; ?></span> </div>
									<div class="col-md-4 col-sm-4 col-xs-4 nopadding"> <span class="childbox"><?php echo $search_data->child; ?></span> </div>
									<div class="col-md-4 col-sm-4  col-xs-4 nopadding"> <span class="infantbox"><?php echo $search_data->infant; ?></span> </div>
								</div>
							</div>
							<div class="col-md-2 col-sm-4 col-xs-6"> <a href="javascript:void(0);" class="modify_search_btn modify_flight_search"><?php echo $this->lang->line("modify"); ?></a></div>
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
					<div class="domestic_results_block" ng-controller="domestic_controller">
						<div class="hotelfilter_block">
							<div class="col-md-2 col-sm-2 col-xs-2 nopadding">
								<h3 class="filtertitle"><?php echo $this->lang->line("filters"); ?></h3>
							</div>
							<div class="col-md-10 col-sm-10 col-xs-10 width100 nopadding">
								<div class="sorting1">
									<ul>
										<li><?php echo $this->lang->line("sort_by_colan"); ?></li>
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
										<input name="price_filter" type="hidden" ng-value="cmin_price+'-'+cmax_price">
										<strong style="float:left;" ng-bind="(cmin_price | custom_currency:currency:currency_val)"></strong> &nbsp; <strong style="float:right;" ng-bind="(cmax_price | custom_currency:currency:currency_val)"></strong>
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
										<h1><?php echo $this->lang->line("airports"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
									</div>
									<div class="pricesection" ng-clock ng-show="all_total_count > 0">
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
																		<tr style="border:none; height:27px;">
																			<td align="center" height="27" style="border:none;">&nbsp;</td>
																		</tr>
																		<tr style="border:none;">
																			<td align="center" valign="middle" height="28" style="border:none;"><div style="color:#15538a; font-size: 12px;"><?php echo $this->lang->line("all_flights"); ?></div></td>
																		</tr>
																		<tr style="border:none;">
																			<td align="center" style="border:none;">
																				<div ng-repeat = "stop in stops" class=" search_result_border_bottom_part" style="margin-bottom:1px; color:#333; line-height:30px; font-size:11px;padding:0 12px;"><a ng-bind="stop.text"></a></div>
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
																					<td bgcolor="#f5f5f5" align="center" height="20px" style="border-bottom: 1px solid #CCCCCC;"><a  href="javascript:void(0);" ng-click="filter_airline(matrix.airline_code)"><span class="text13 " style="font-size:11px; color:#114879; line-height:15px;" ng-bind="matrix.airline_name"></span></a></td>
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
								<div class="col-md-12 nopadding fixe">
								<div ng-cloak ng-class="total_count > 0 && is_twoway === 'true' ? 'col-md-6' : 'col-md-12'" class="col-md-12 nopadding_left">
								<!-- <h1 class="place_title" ng-cloak> 
								<?php
									echo "<span>".$this->lang->line("special_discount_description")."</span>";
								?>
								</h1> -->
								</div>

								<div ng-cloak ng-show="total_count > 0 && is_twoway === 'true'" class="col-md-6 nopadding">
									<div class="col-md-8 md75 col-xs-3 nopadding_left">
									 	<div class="d_total"> 
											
                                     		<div class="rowone">
                                     		<div class="col-md-5">
									 			<span><?php echo $this->lang->line("total_colan"); ?></span> 
									 			</div>
									 			<div class="col-md-3">
									 			<?php 
		                                     	if($_SESSION['default_language']=='en')
		                                     		{ echo $this->data["default_currency"]; } 
												else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
												?> 
												</div>
												<div class="col-md-4 text-left">
                                     			<!--<span ng-if="selected_flight_price > 0" ng-bind="(selected_flight_price)"></span>-->
                                                                              		<?php
if($_SESSION['default_currency']=="USD"){
?>																
<span ng-if="selected_flight_price > 0" ng-bind="(selected_flight_price)*<?php echo $irramount; ?>  | number:2"></span>
<?php } else { ?>
<span  ng-if="selected_flight_price > 0" ng-bind="((selected_flight_price))"></span>
<?php } ?>


								     			<span ng-if="selected_flight_price === 0" ng-bind="'NA'"></span>
								     			</div>
											</div>


											<div class="rowone">
										 		<div class="col-md-5">
										 		<span><?php echo $this->lang->line("discount_colan"); ?></span>
										 		</div>
										 		<div class="col-md-3">
										 		<?php 

												if($_SESSION['default_language']=='en')
													{ echo $this->data["default_currency"]; } 
													else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
												?> 
												</div>
												<div class="col-md-4 text-left">
	                                     		<!--<span ng-bind="(discount)"></span>-->
	                                     		<?php
if($_SESSION['default_currency']=="USD"){
?>																
<span ng-bind="(discount)*<?php echo $irramount; ?>  | number:2"></span>
<?php } else { ?>
<span ng-bind="((discount))"></span>
<?php } ?>
	                                     		</div>
	                                     	</div>



	                                     	<div class="rowone">
	                                     	<div class="col-md-5">
										 	<span ><?php echo $this->lang->line("fare_price_colan"); ?></span>
										 		</div>
										<div class="col-md-3"> 
										 		<?php 

	                                     	if($_SESSION['default_language']=='en')
	                                     		{ echo $this->data["default_currency"]; } 
											else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
											?> 
											</div>
											<div class="col-md-4 text-left">
	                                     		<!--<span ng-bind="((selected_flight_price-discount))"></span>-->

<?php
if($_SESSION['default_currency']=="USD"){
?>																
<span ng-bind="(selected_flight_price-discount)*<?php echo $irramount; ?> | number:2"></span>
<?php } else { ?>
<span ng-bind="(selected_flight_price-discount)"></span>
<?php } ?>


	                                     		</div>

	                                     	</div>
										</div>
									
									</div>

									<div class="col-md-4 md25 col-xs-4 nopadding rs100">
									 	<button ng-click="dom_flight_validate()" class="select_btn btn-1e"><?php echo $this->lang->line("book_now") ?></button>
									</div>
								</div>
								</div>


								<div class="clearfix"></div>
								<div class="flight_titles" ng-cloak ng-if="is_twoway === 'false'">
									<div class="col-xs-12 nopadding">
										<div class="col-md-10 col-sm-10 nopadding_left">
											<div class="col-md-2 col-sm-2 nopadding_right">
													<div class="arrival"><?php echo $this->lang->line("airline") ?></div>
											</div>

											<div class="col-md-2 col-sm-2"> 
											<div class="arrival"><?php echo $this->lang->line("flight_class") ?></div>
											</div>
												
										
											<div class="col-md-3 col-sm-3 nopadding">
												<div class="destination">
													<?php echo $this->lang->line("departure") ?>
												</div>
											</div>
														
											<div class="col-md-3 col-sm-3 nopadding">
												<div class="arrival">
													<?php echo $this->lang->line("arrival") ?>
												</div>
											</div>

											<div class="col-md-2 col-sm-2 nopadding">
												<div class="arrival">
													<?php echo $this->lang->line("available") ?>
												</div>
											</div>
												
											</div>
											
										</div>

									</div>

								<!-- Domestic oneway starts -->
								<div class="flight_result_block">
									<div ng-show="total_count < 0" class="loading"></div>
									<div ng-cloak ng-if="total_count === '0' && all_total_count > '0'">
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
									
									<div ng-cloak ng-show="total_count > 0 && is_twoway === 'false'" class="flightlist"  dir-paginate="flight_row in flights | itemsPerPage:page_limit" total-items="total_count">
										<div class="flight_result">
											<div class="col-xs-12 nopadding">
											<div class="col-md-10 col-sm-10 nopadding">
											<div class="flight_middle" ng-repeat="departure_details in flight_row.departures track by $index">
												<div class="col-md-2 col-sm-2 nopadding">
													<div class="flight_left"> 
													<img ng-src="{{departure_details.airline | airline_image}}"/> 
													<div class="clearfix"></div>
													<div class="flight_title flight_nos" ng-bind="departure_details.airline+''+departure_details.flight_no">
														

													</div>
														<div class="clearfix"></div>
														<div class="flight_title" ng-bind="(departure_details.airline | airline_name:airlines)"></div>
														
													</div>
												</div>
												<div class="col-md-2 col-sm-2 planecenter">
													<!-- <h1 ng-bind="flight_row.stops_text"></h1><br/> -->
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
														<div ng-if="departure_details.arrival_dttm !== '0000-00-00 00:00:00'">
															<div class="dateinfo" ng-bind="(departure_details.arrival_dttm | readable_dttm)"></div>
															<div class="clearfix"></div>
															<div class="dateinfo fa_content"><span ng-bind="(departure_details.arrival_dttm | fa_readable_dt)"></span> <span ng-bind="(departure_details.arrival_dttm | fa_readable_tm)"></span> </div>
														</div>
														<div ng-if="departure_details.arrival_dttm === '0000-00-00 00:00:00'">
															<div class="dateinfo" ng-bind="'<?php echo $this->lang->line("not_available"); ?>'"></div>
															<div class="clearfix"></div>
														</div>
													</div>
												</div>

												<div class="col-md-2 col-sm-2 nopadding">
													<div class="arrival">
														<div class="col-xs-12 time nopadding" ng-bind="departure_details.seats + ' <?php echo $this->lang->line("seats_small"); ?>'"></div>
													</div>
												</div>
												
												<div class="col-md-12 col-sm-12 nopadding operating_carrier" ng-if="departure_details.airline !== departure_details.operating_airline" ng-bind="' <?php echo $this->lang->line("operating_carrier_changed_to"); ?>'+departure_details.operating_airline_name">
												</div>	
											</div>
										</div>
												<div class="col-md-2 col-sm-2 nopadding_right">
													<div class="flight_right">
														<div class="col-md-12 col-sm-12 nopadding">
															
															<div class="clearfix"></div>
															<label><?php echo $this->lang->line("total_cost_colan"); ?> </label><br/>
															<!-- <h2 ng-bind="(flight_row.total_cost | custom_currency:currency:currency_val)"></h2> -->
															
															<h2 ng-if="flight_row.display_price <= 0" ng-bind="put_comma_among_digit(flight_row.total_cost)" style="font-size: 14px; display: inline-block;"></h2>
															<h2 ng-if="flight_row.display_price > 0" ng-bind="put_comma_among_digit(flight_row.display_price)" style="font-size: 14px; display: inline-block;"></h2>
															<span style="font-size: 14px;">
															<?php
                	                                     	if($_SESSION['default_language']=='en')
                	                                     		{ echo $this->data["default_currency"]; } 
                											else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } }
                											?> 
                											</span>
														</div>
														<div class="col-md-12 col-sm-12 nopadding">
															<div class="flight_select">
																<button ng-click="flight_validate(flight_row.id)" class="select_btn btn-1e"><?php echo $this->lang->line("book_now"); ?></button>
															</div>
															
														</div>
													</div>
													<div class="col-md-12 flinks nopadding">
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
														<th><?php echo $this->lang->line("base_fare"); ?></th>
														<th><?php echo $this->lang->line("tax"); ?></th>
														<th><?php echo $this->lang->line("cost"); ?></th>
														<th><?php echo $this->lang->line("total_cost"); ?></th>
													</tr>
													<tr ng-repeat="fare_breakdown in flight_row.prices">
														<td ng-bind="fare_breakdown.person_type+' X '+fare_breakdown.quantity"></td>
														<td ng-bind="fare_breakdown.base_cost | custom_currency:currency:currency_val"></td>
														<td ng-bind="fare_breakdown.total_tax | custom_currency:currency:currency_val"></td>
														<td ng-bind="fare_breakdown.total_cost | custom_currency:currency:currency_val"></td>
														<td ng-bind="(fare_breakdown.total_cost * fare_breakdown.quantity  | custom_currency:currency:currency_val)"></td>
													</tr>
													<tr>
													<td colspan="3"></td>
													<td><?php echo $this->lang->line("total_cost"); ?></td>
													<td ng-bind="flight_row.total_cost | custom_currency:currency:currency_val"></td>
												</table>
												</div>
										   </div>
										</div>
									</div>
									<div class="clearfix"></div>
									<dir-pagination-controls ng-show="total_count > 0 && is_twoway === 'false'" max-size="7"  default-page="pageno" direction-links="true" boundary-links="true" on-page-change="get_data(newPageNumber)" >
									</dir-pagination-controls>
								</div>
								<!-- Domestic oneway ends -->

								<!-- Domestic round trip start -->
								<div class="dom_flight" ng-cloak ng-show="total_count > 0 && is_twoway === 'true'">

									<!-- Domestic round trip departure start -->

									<div class="col-xs-6 nopad">
										<div class="celsrch1 rnd-trp nomobview addsort">
										<h4 class="text-center in_boun"><?php echo $this->lang->line("outbound"); ?></h4>
											<div class="tabbox nnewbrd">
												
												<div id="onwardshort" class="col-xs-12 nopad shorting">
													<div class="col-xs-2 brdrgt icon1 xthre  searchtab fiftyw ">
														<a id="SortbyAirline" href="javascript:void(0)" rel="data-airline" data-order="asc" class="ascending FlightSorting_round_onward">
														<span class="aftsixf"><?php echo $this->lang->line("airline_caps"); ?></span> 
														</a>
													</div>
													<div class="col-xs-10 nopad fiftyw ">
														<div class="col-xs-6 icon1 xthre  searchtab fiftyw">
															<a id="SortbyDepart" href="javascript:void(0)" rel="data-departuretime" data-order="asc" class="ascending FlightSorting_round_onward">
																<span class="aftsixf"><?php  //$this->lang->line("departure_sf_caps");
																echo $this->lang->line("departure");
																 ?></span> 
															</a>
														</div>
														<div class="col-xs-3 icon1 xthre  searchtab nopad fiftyw">
															<a id="SortbyArrival" href="javascript:void(0)" rel="data-arrivaltime" data-order="asc" class="ascending FlightSorting_round_onward">
																<span class="aftsixf"><?php echo $this->lang->line("info_caps"); ?></span>  
															</a>
														</div>
														<div class="col-xs-3 icon1 xthre searchtab nobrdr fiftyw">
															<a id="SortbyDuration" href="javascript:void(0)" rel="data-duration" data-order="asc" class="ascending FlightSorting_round_onward">
																<span class="aftsixf"><?php echo $this->lang->line("price_caps"); ?></span> 
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>

									 	<div class="tble">
											<div class="col-xs-12 nopad scrolnice scrol_result" tabindex="5001" style="overflow: hidden; outline: none;">
												<div class="col-sm-12 col-md-12 col-xs-12 nopad mn_60">
													<div ng-cloak ng-repeat="oflight in flights" class="repeatflit display_flightresult" ng-init="sel_index = $index">
														<div class="col-sm-12 col-md-12 col-xs-12 nopad mn_60" ng-repeat="departures in oflight.departures">
															<div class="col-xs-12 col-sm-1 nopadding_right mob_radio">
																<input type="radio" ng-click="set_oflight(oflight.id, oflight.total_cost)" id="oflight{{oflight.id}}" name="oflight" class="css-checkbox" ng-value="oflight.id" ng-checked = "sel_index === 0">
																<label for="oflight{{oflight.id}}" class="css-label radGroup1 d_rou"></label>
															</div>
															<div class="col-xs-12 col-sm-3 aftsht nopad">
																<div class="flitimgdspec">
																	<img ng-src="{{departures.airline | airline_image}}" alt="">
																	<!--<span ng-bind="departures.airline+''+departures.flight_no"></span>-->
<span ng-bind="departures.flight_no"></span>
																</div>
															</div>

															<div class="col-xs-12 col-sm-8 nopad aftsht">
																
																<div class="col-xs-12 col-sm-5 mob_re nopad">
																	<!--<div class="dptplace dpttime txt_left" ng-bind-html="(departures.departure_from | airport_city_only:departures.arrival_to:airports)"></div>-->
<?php 

if($_SESSION['default_language']=='en'){ 

?>	

	<div class='dptplace dpttime txt_left' ng-bind-html='(departures.departure_from | airport_city_only:departures.arrival_to:airports)'></div>

<?php  }

else {
?>
          
	<div class='dptplace dpttime txt_left' ng-bind-html='(departures.arrival_to | airport_city_only:departures.departure_from:airports)'></div>

<?php }
 ?>


																	<div class="dpttime txt_left" ng-bind="(departures.departure_dttm | readable_dttm)"></div>
																	<div class="dpttime txt_left fa_content" ng-bind="(departures.departure_dttm | fa_readable_dt)+' '+(departures.departure_dttm | fa_readable_tm)"></div>
																</div>

																<div class="col-xs-12 col-sm-3 nopad">
																	<div class="dptplace" ng-bind="departures.seats+' <?php echo $this->lang->line("seats_small"); ?>'"></div>
																	<!-- <div class="dpttime">Charter</div> -->
																	<div class="dpttime" ng-bind="departures.airline_type"></div>
																</div>
<div class="col-xs-12 col-sm-3 nopadding text-center">
<?php 

	                                     	if($_SESSION['default_language']=='en')
	                                     		{ echo $this->data["default_currency"]; } 
											else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } }
											?> 

											<?php
if($_SESSION['default_currency']=="USD"){
?>																
<div class="durtime" ng-bind="(oflight.display_price)*<?php echo $irramount; ?>  | number:2"></div>
<?php } else { ?>
<div class="durtime" ng-bind="(oflight.display_price)"></div>
<?php } ?>

																
																	<!--<div class="durtime" ng-bind="(oflight.display_price)"></div>-->
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- Domestic round trip departure ends -->

									<!-- Domestic round trip return start -->
									<div class="col-xs-6 nopad">
										<div class="celsrch1 rnd-trp nomobview addsort">
											<div class="tabbox nnewbrd">
												<h4 class="text-center in_boun"><?php echo $this->lang->line("inbound"); ?></h4>
												<div id="onwardshort" class="col-xs-12 nopad shorting">
													<div class="col-xs-2 brdrgt icon1 xthre  searchtab fiftyw ">
														<a id="SortbyAirline" href="javascript:void(0)" rel="data-airline" data-order="asc" class="ascending FlightSorting_round_onward">
															<span class="aftsixf"><?php echo $this->lang->line("airline_caps"); ?></span> 
														</a>
													</div>
													<div class="col-xs-10 nopad fiftyw ">
														<div class="col-xs-6 icon1 xthre  searchtab fiftyw">
															<a id="SortbyDepart" href="javascript:void(0)" rel="data-departuretime" data-order="asc" class="ascending FlightSorting_round_onward">
																<span class="aftsixf"><?php 
																// echo $this->lang->line("departure_sf_caps");
																echo $this->lang->line("return"); ?></span> 
															</a>
														</div>
														<div class="col-xs-3 icon1 xthre  searchtab nopad fiftyw">
															<a id="SortbyArrival" href="javascript:void(0)" rel="data-arrivaltime" data-order="asc" class="ascending FlightSorting_round_onward">
																<span class="aftsixf"><?php echo $this->lang->line("info_caps"); ?></span>  
															</a>
														</div>
														<div class="col-xs-3 icon1 xthre searchtab nobrdr fiftyw">
															<a id="SortbyDuration" href="javascript:void(0)" rel="data-duration" data-order="asc" class="ascending FlightSorting_round_onward">
																<span class="aftsixf"><?php echo $this->lang->line("price_caps"); ?></span> 
															</a>
														</div>
													</div>
												</div>
												 
											</div>
										</div>

										<div class="tble tble1">
											<div class="col-xs-12 nopad scrolnice scrol_result" tabindex="5001" style="overflow: hidden; outline: none;">
												<div class="col-sm-12 col-md-12 col-xs-12 nopad mn_60">
													<div ng-cloak ng-repeat="rflight in rflights" class="repeatflit display_flightresult" ng-init="sel_index = $index">
														<div class="col-sm-12 col-md-12 col-xs-12 nopad mn_60" ng-repeat="departures in rflight.departures">
															<div class="col-xs-12 col-sm-1 nopadding_right mob_radio">
																<input type="radio" ng-click="set_rflight(rflight.id, rflight.total_cost)" id="rflight{{rflight.id}}" name="rflight" class="css-checkbox" ng-value="rflight.id" ng-checked = "sel_index === 0">
																<label for="rflight{{rflight.id}}" class="css-label radGroup1 d_rou"></label>
															</div>
															<div class="col-xs-12 col-sm-3 aftsht nopad">
																<div class="flitimgdspec">
																	<img ng-src="{{departures.airline | airline_image}}" alt="">
																	<!--<span ng-bind="departures.airline+''+departures.flight_no"></span>-->
<span ng-bind="departures.flight_no"></span>
																</div>
															</div>

															<div class="col-xs-12 col-sm-8 nopad aftsht">
																
																<div class="col-xs-12 col-sm-5 nopad">
																	<!--<div class="dptplace dpttime txt_left" ng-bind-html="(departures.departure_from | airport_city_only:departures.arrival_to:airports)"></div>-->

																	<?php 

if($_SESSION['default_language']=='en'){ 

?>	

	<div class='dptplace dpttime txt_left' ng-bind-html='(departures.departure_from | airport_city_only:departures.arrival_to:airports)'></div>

<?php  }

else {
?>
          
	<div class='dptplace dpttime txt_left' ng-bind-html='(departures.arrival_to | airport_city_only:departures.departure_from:airports)'></div>

<?php }
 ?>
																	<div class="dpttime txt_left" ng-bind="(departures.departure_dttm | readable_dttm)"></div>
																	<div class="dpttime txt_left fa_content"><span ng-bind="(departures.departure_dttm | fa_readable_dt)+' '+(departures.departure_dttm | fa_readable_tm)"></span></div>
																</div>

																<div class="col-xs-12 col-sm-3 nopadding">
																	<div class="dptplace" ng-bind="departures.seats+' <?php echo $this->lang->line("seats_small"); ?>'"></div>
																	<!-- <div class="dpttime">Charter</div> -->
																	<div class="dpttime" ng-bind="departures.airline_type"></div>
																</div>
<div class="col-xs-12 col-sm-3 nopadding text-center">
<?php 

     	if($_SESSION['default_language']=='en')
     		{ echo $this->data["default_currency"]; } 
						else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
						?> 


<?php  

	//$value = "{{ rflight.display_price }}";
	//echo $value;exit;
	//$show = showCurrency($this->data["default_currency"],$value);
	        
	//$s = ($value)*3;//echo $s;
?>

<?php
if($_SESSION['default_currency']=="USD"){
?>																
<div class="durtime" ng-bind="(rflight.display_price)*<?php echo $irramount; ?> | number:2"></div>
<?php } else { ?>
<div class="durtime" ng-bind="(rflight.display_price)"></div>
<?php } ?>

</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- Domestic round trip return ends -->
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
	<div id="revalidatemodal" class="modal fade in" role="dialog" style="background-color : rgba(0, 0, 0, 0.7)">
		<div class="loading"></div>
		<div style="color:white;text-align:center;"><?php echo $this->lang->line("checking_additional_details"); ?></div>
	</div>
	<script type="text/javascript" data-main="<?php echo asset_url('js/config'); ?>" src="<?php echo asset_url('js/require.js'); ?>"></script>


<script type="text/javascript">
	if ( _window.width() > 781 ) {
            var mastheadHeight = $( '#masthead' ).height(),
                    toolbarOffset, mastheadOffset;
    
               if ( mastheadHeight > 48 ) {
                   body.removeClass( 'masthead-fixed' );
               }
   
                if ( body.is( '.header-image' ) ) {
                    toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
                    mastheadOffset = $( '#masthead' ).offset().top - toolbarOffset;
    
                   _window.on( 'scroll.twentyfourteen', function() {
                        if ( _window.scrollTop() > mastheadOffset && mastheadHeight < 49 ) {
                            body.addClass( 'masthead-fixed' );
                        } else {
                          body.removeClass( 'masthead-fixed' );
                       }
                   } );
               }
          }



</script>
	
	</body>
</html>
