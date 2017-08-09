<!DOCTYPE html>
<html>
	<?php
	$include["css"][] = "persian_fonts";
	$include["css"][] = "persian_calendar";
	$this->load->view("common/head", $include);
	?>
	<?php
	$this->load->view("common/header");
	$this->load->view("common/notification");
?>
	<body>
		<div id="wrapper">
			<div class="flight_block_modify result_bg">
				<div class="container nopadding">
					<div class="row">
						<div class="col-xs-12 nopadding">
							<div class="sortblock">
								<div class="col-md-2 col-sm-4 col-xs-12 border-rt">
									<div class="checkin2"> <i class="fa fa-map-marker"></i> <span> <span class="loc1"><?php echo $this->lang->line("hotel_search_location_colan"); ?></span>
										<div class="clearfix"></div>
										<?php echo $city->city; ?> </span> </div>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12 border-rt">
									<div class="checkin2"> <i class="fa fa-calendar"></i> <span> <span class="loc1"><?php echo $this->lang->line("hotel_search_check_in_colan"); ?></span>
										<div class="clearfix"></div>
										<?php echo date("d M Y", strtotime($search_data->check_in)); ?> </span> </div>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12 border-rt">
									<div class="checkin2"> <i class="fa fa-calendar"></i> <span> <span class="loc1"><?php echo $this->lang->line("hotel_search_check_out_colan"); ?></span>
										<div class="clearfix"></div>
										<?php echo date("d M Y", strtotime($search_data->check_out)); ?> </span> </div>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12 border-rt">
									<div class="checkin2"> <i class="fa fa-hotel"></i> <span> <span class="loc1"><?php echo $this->lang->line("hotel_search_rooms_colan"); ?></span>
										<div class="clearfix"></div>
										<?php echo $search_data->rooms; ?> </span> </div>
								</div>
								<div class="col-md-2 col-sm-4 col-xs-12 border-rt">
									<div class="passengers1"><?php echo $this->lang->line("hotel_search_travellers"); ?><br>
										<div class="col-md-4 col-sm-4 col-xs-6 nopadding"> <span class="adultbox1"><?php echo array_sum($search_data->adult); ?></span> </div>
										<div class="col-md-4 col-sm-4 col-xs-6 nopadding"> <span class="childbox1"><?php echo array_sum($search_data->children); ?></span> </div>
									</div>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-6 pull-right"> <a href="javascript:void(0);" class="modify_search_btn modify_hotel_search"><?php echo $this->lang->line("hotel_search_modify_search"); ?></a></div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php $this->load->view("hotel/modify_search"); ?>
				<div class="middle_content">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 nopadding">
								<div class="hotel_controller" ng-controller="hotel_controller">
									<div class="hotelfilter_block">
										<div class="col-md-2 nopadding">
											<h3 class="filtertitle"><?php echo $this->lang->line("hotel_search_filter"); ?></h3>
										</div>
										<div class="col-md-10 nopadding">
											<div class="sorting1">
												<ul>
													<li><?php echo $this->lang->line("hotel_search_sort_by_colan"); ?></li>
													<li><a href="javascript:void(0);" ng-click="sort_me('star')"><?php echo $this->lang->line("hotel_search_star_rating"); ?><i class="fa fa-sort" ng-class="order_by !== 'star' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc')"></i></a></li>
													<li><a href="javascript:void(0);" ng-click="sort_me('user_rating')"><?php echo $this->lang->line("hotel_search_guest_rating"); ?><i class="fa fa-sort" ng-class="order_by !== 'user_rating' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc')"></i></a></li>
													<li><a href="javascript:void(0);" ng-click="sort_me('hotel_name')"><?php echo $this->lang->line("hotel_search_hotel_name"); ?><i class="fa fa-sort" ng-class="order_by !== 'hotel_name' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-alpha-asc' : 'fa-sort-alpha-desc')"></i></a></li>
													<li><a href="javascript:void(0);" ng-click="sort_me('price')"><?php echo $this->lang->line("hotel_search_price"); ?><i class="fa fa-sort" ng-class="order_by !== 'price' ? 'fa-sort' : (sort_by === 'asc' ? 'fa-sort-amount-asc' : 'fa-sort-amount-desc')"></i></a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-2 nopadding">
										<form action="javascript:void(0);" id="filter_form">
											<div class="filter_block">
												<div class="price_filter">
													<div class="filterbg pricebg">
														<h1><?php echo $this->lang->line("hotel_search_price"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
													</div>
													<div class="pricerange pricesection" ng-clock ng-show="all_total_count > 0">
														<div class="price-slider"></div>
														<input name="price_filter" type="hidden" ng-value="cmin_price+'-'+cmax_price">
														<strong style="float:left;" ng-bind="(cmin_price | custom_currency:currency:currency_val)"></strong> &nbsp; <strong style="float:right;" ng-bind="(cmax_price | custom_currency:currency:currency_val)"></strong>
													</div>
												</div>
												<div class="Fare_type">
													<div class="filterbg farebg" id="Fare_type">
														<h1><?php echo $this->lang->line("hotel_search_star_rating"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
													</div>
													<div class="pricesection" id="show_Fare" style="padding-bottom:15px;" ng-clock ng-show="all_total_count > 0">
														<div class="check_box" ng-cloak ng-repeat="star in stars | orderBy : star">
															<input type="checkbox" id="fare_{{$index}}" ng-click="filter_data()" checked="checked" value="{{$index}}" name="stars[]" />
															<label for="star_{{$index}}"> <span for="star_{{$index}}"><img ng-src="{{star | star_image}}"/></span> </label>
															<div class="clearfix"></div>
														</div>
													</div>
												</div>
												<div class="price_filter">
													<div class="filterbg pricebg" id="userrating">
														<h1><?php echo $this->lang->line("hotel_search_search_by_hotel"); ?> <span class="accordprefix"> <img src="<?php echo base_url('assets/images/minus.png')?>" alt="" /> </span> </h1>
													</div>
													<div class="pricesection" id="show_userrating">
														<input type="text" name="hotel_name" ng-model="hotel_name" class="inputtext_2" ng-change="filter_data()">
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-10 nopadding">
										<div class="col-md-12 col-xs-12 nopadding">
											<div ng-show="total_count < 0" class="loading"></div>
											<div ng-cloak ng-show="total_count === '0' && all_total_count > '0'">No result found in search category</div>
											<div ng-cloak ng-if="total_count === '0' && all_total_count === '0'">No hotel result available</div>
											<div class="clearfix"></div>
											<div ng-clock class="flight_result_block">
												<div ng-cloak ng-show="total_count > 0" class="flightlist"  dir-paginate="hotel_row in hotels | itemsPerPage:page_limit" total-items="total_count">
													<div class="flight_result">
														<div class="col-xs-12 nopadding">
															<div class="col-md-10 col-sm-10 nopadding">
																<div class="hotel_gallery">
																	<div class="col-md-3 col-sm-3 col-xs-12 nopadding_left">
																		<div class="hotel_img zoomin"> <img ng-src="{{hotel_row.image | hotel_image}}"/> </div>
																	</div>
																	<div class="col-md-9 col-sm-9 col-xs-12 nopadding">
																		<div class="park1 car_list">
																			<h1 class="york1" ng-bind="hotel_row.hotel_name"></h1>
																			<div class="clearfix"></div>
																		</div>
																		<div class="col-md-6 nopadding">
																			<div class="hotel_det">
																				<div class="star_rating"><span class="location" ng-bind="hotel_row.extra.area"></span> </div>
																			</div>
																		</div>
																		<div class="col-md-6 nopadding">
																			<div class="hotel_det">
																				<div class="star_rating"><span><?php echo $this->lang->line("hotel_search_star_rating_colan"); ?></span> <img ng-src="{{hotel_row.star | star_image}}"/> </div>
																			</div>
																		</div>
																		<div class="car_advantages">
																			<p ng-bind="hotel_row.description | truncate:300"></p>
																		</div>
																		<div class="clearfix"></div>
																	</div>
																</div>
															</div>
															<div class="col-md-2 col-sm-2 nopadding hotelprice_sec">
																<div class="car_right">
																	<div class="col-md-12 nopadding">
																		<h2><span ng-bind="hotel_row.total_cost | custom_currency:currency:currency_val"></span>
																			<div class="clearfix"></div>
																			<span><?php echo $this->lang->line("hotel_search_avg_night"); ?></span> </h2>
																	</div>
																	<div class="col-md-12 nopadding">
																		<div class="hotel_select">
																			<button ng-click="hotel_details(hotel_row.id)" class="select_btn btn-1e"><?php echo $this->lang->line("hotel_search_details"); ?></button>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="clearfix"></div>
													</div>
												</div>
												<div class="clearfix"></div>
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
			</div>
			<?php
				$this->load->view("common/pop-ups");
				$this->load->view("common/footer");
			?>
		</div>
	</body>
	<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>
