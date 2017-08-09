<!DOCTYPE html>
<html>
	<?php
	$services_opt = json_decode($flight_details->extra_service_json);
	$user_exists = isset($user_details) ? true : false;
	$departure_airports = $arrival_airports = $cip_in_airports = $cip_out_airports = array();
	$origin_destinations = json_decode($flight_details->origin_destination);
	$departure_dttm = $arrival_dttm = null;
	foreach ($origin_destinations as $od_idx => $od_obj)
	{
		$departure_airports[$od_obj->departure_loc] = $arrival_airports[$od_obj->arrival_loc] = $od_obj;
		if(is_null($departure_dttm))
				$departure_dttm = new DateTime($od_obj->departure_dttm);
			$arrival_dttm = new DateTime($od_obj->arrival_dttm);
	}
	$travel_duration = 1;
	if(!is_null($departure_dttm) && !is_null($arrival_dttm))
	{
		$temp_dt_res = $departure_dttm->diff($arrival_dttm);
		$travel_duration = $temp_dt_res->format("%a");
	}
	$temp_cip_airports = array_values(array_intersect(array_keys($departure_airports), array_keys($cip_airports)));
	foreach ($temp_cip_airports as $cipout_idx => $cipout_val)
		if(!is_null($cip_airports[$cipout_val]->cip_out))
			$cip_out_airports[$cipout_val] = $cip_airports[$cipout_val];
	$temp_cip_airports = array_values(array_intersect(array_keys($arrival_airports), array_keys($cip_airports)));
	foreach ($temp_cip_airports as $cipin_idx => $cipin_val)
		if(!is_null($cip_airports[$cipin_val]->cip_in))
			$cip_in_airports[$cipin_val] = $cip_airports[$cipin_val];
	$include["css"][] = "select2/select2";
	$this->load->view("common/head", $include);
	?>
<body style="background: #e4e4e4;">
<div id="wrapper">
<?php
	$this->load->view("common/header");
?>

	<div class="clearfix"></div>
	<section class="mn-reg">
		<div class="container">
			<div class="row">
				<div class="fancy-title">
					<div class="col-md-12 notification"></div>
					
					<div class="col-md-12 nopadding">
						<div hyperlink='<?php echo json_encode($flight_details); ?>' class="flight_prebook_block" ng-controller="flight_prebook">
							<div class="flightlist" hyperlink="<?php echo $currency; ?>" dir-paginate="flight_row in flights | itemsPerPage:page_limit" total-items="total_count">
								<div class="flight_result" style="padding: 15px;" hyperlink="<?php echo $currency_val; ?>">
									<div class="col-xs-12 nopadding">
										<div class="col-md-10 col-sm-10 nopadding">
											<div class="flight_middle" ng-repeat="departure_details in flight_row.departures track by $index">
												<div class="col-md-2 col-sm-2 nopadding">
													<div class="flight_left"> <img ng-src="{{departure_details.airline | airline_image}}"/> 
                                                    <div class="clearfix"></div>
													<div class="flight_title flight_nos" ng-bind="departure_details.airline+''+departure_details.flight_no"></div>
														<div class="clearfix"></div>
														<div class="flight_title" ng-bind="(departure_details.airline | airline_name:airlines)"></div>
														
													</div>
												</div>
												<div class="col-md-2 col-sm-2 planecenter">
													<!-- <h1 ng-bind="flight_row.stops_text"></h1><br/> -->
													<div class="dateinfo" ng-bind="departure_details.stop+' <?php echo $this->lang->line("stop_small") ?>'"></div><br/>
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
														<div class="col-xs-12 time nopadding" ng-bind="departure_details.seats+' <?php echo $this->lang->line("seats_small"); ?>'"></div>
													</div>
												</div>
											</div>
											<hr ng-if="flight_row.arrivals.length > 0" />
											<div class="flight_middle" ng-repeat="arrival_details in flight_row.arrivals track by $index">
												<div class="col-md-2 col-sm-2 nopadding">
													<div class="flight_left"> <img ng-src="{{arrival_details.airline | airline_image}}"/> 
                                                    <div class="clearfix"></div>
													<div class="flight_title flight_nos" ng-bind="arrival_details.airline+''+arrival_details.flight_no"></div>
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
														<div class="dateinfo fa_content"><span ng-bind="(arrival_details.departure_dttm | fa_readable_dt)"></span> <span ng-bind="(arrival_details.departure_dttm | fa_readable_tm)"></span> </div>
													</div>
												</div>

												<div class="col-md-2 col-sm-2 nopadding">
													<div class="arrival">
														<div class="col-xs-12 time nopadding" ng-bind="arrival_details.seats+' <?php echo $this->lang->line("seats_small"); ?>'"></div>
														<div ng-if="arrival_details.wait_place.length > 0" class="col-xs-12 time nopadding" ng-bind-html="(arrival_details.wait_tm | mins_to_time)+' stop over at '+(arrival_details.wait_place | airport_city:airports)"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-2 col-sm-2 nopadding_right">
											<div class="flight_right">
												<div class="col-md-12 col-sm-12 nopadding">
													
													<div class="clearfix"></div>
													<label><?php echo $this->lang->line("total_cost_colan"); ?> </label>
													<h2 ng-bind="(flight_row.total_cost | custom_currency:currency:currency_val)"></h2>
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
												</tr>
											</table>
										</div>
								   </div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>

				<form role="form" action="javascript:void(0);" class="prebook_form">
					<div class="col-md-12 nopadding">
						<section>
							<div class="wizard">
							<!-- 	<div class="wizard-inner">
									<ul class="nav nav-tabs" role="tablist">
									</ul>
								</div> -->
								
									<div class="tab-content col-md-12 nopadding">
										<div class="tab-pane active" role="tabpanel" id="step1">
											
											<!-- begin panel group -->
											<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"> 
												<div class="panel panel-default"> 
													<div id="collapseTwo"  class="panel-collapse flight_prebook <?php echo $prebook_type === 'flights' ? ' collapse in' : 'collapse'; ?>" role="tabpanel" aria-labelledby="headingTwo">
														<div class="panel-body">
															<div class="col-md-12 nopadding">
															<h3><?php echo $this->lang->line("traveller_details"); ?></h3>
																<ul class="timeline_1">
																	<li>
																		<div class="timeline-badge none"></div>
																		<div class=" mn_nopadding">
																					<div class="col-md-12 col-sm-12">
																						<div class="form-group">
																							<h4><?php echo $this->lang->line("adult_colan");
																							?></h4>
																						</div>
																					</div>
																				</div>
																		<div class="timeline-panel">
																			<?php
																				$adult = $search_data->adult;
																				for ($i = 0; $i < $adult; $i++)
																				{
																					$salutation = $fname = $lname = $dob = $passport_no = $passport_no_expire = $national_id = "";
																					$nationality = "IR";
																					if($user_exists)
																					if($i === 0)
																					{
																						$salutation = $user_details->general;
																						$fname = $user_details->firstname;
																						$lname = $user_details->lastname;
																						$nationality = $user_details->country;
																						$dob = date("m/d/Y", strtotime($user_details->dob));
																						$passport_no = $user_details->passport_no;
																						$passport_no_expire = date("m/d/Y", strtotime($user_details->passport_exp_date));
																						$national_id = $user_details->national_no;
																					}
																			?>
																			
																		<div class="traveller_div">
																			<div class="col-md-12 mn_nopadding">
																				
																				<div class="col-sm-12 mn_nopadding"> 
																				<div class="col-sm-7 nopadding">
																				<div class="col-md-1 col-sm-1">    
																					<i class="fa fa-list"></i> 
																				</div>
																				<div class="col-md-2 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" id="adult_salutation<?php echo $i; ?>" >
																							<option <?php echo $salutation === "mr" ? "selected" : ""; ?> value="0"><?php echo $this->lang->line("mr");
																							?></option>
																							<option <?php echo $salutation === "mrs" ? "selected" : ""; ?> value="1"><?php echo $this->lang->line("mrs");
																							?></option>
																							<option <?php echo $salutation === "miss" ? "selected" : ""; ?> value="3"><?php echo $this->lang->line("miss");
																							?></option>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-3 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" name="adult_fname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="adult_fname<?php echo $i; ?>" value="<?php echo $fname; ?>">
																					</div>
																				</div>
																				<div class="col-md-3 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" name="adult_lname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="adult_lname<?php echo $i; ?>" value="<?php echo $lname; ?>">
																					</div>
																				</div>
																				<div class="col-md-3 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" class="inputs_group" placeholder="First and last " value="">
																					</div>
																				</div>
																				</div>
																				<div class="col-sm-5 mnopad">
																				<div class="col-md-3 col-sm-2 mnopad">
																						<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" name="adult_nationality[]" class="set_country select2" readonly="true" id="<?php echo 'adult_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
																						</select>
																				</div>
																				
																				<div class="col-md-3 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" name="adult_dob[]" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="adult_dob<?php echo $i; ?>" value="<?php echo $dob; ?>">
																					</div>
																				</div>

																				<div class="col-md-3 col-sm-2 mnopad not_required">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" name="adult_passport[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="adult_passport<?php echo $i; ?>" value="<?php echo $passport_no; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
																					</div>
																				</div>

																				<div class="col-md-3 col-sm-2 mnopad not_required">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" name="adult_passport_expire[]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_current_date" readonly="true" id="adult_passport_expire<?php echo $i; ?>" value="<?php echo $passport_no_expire; ?>">
																					</div>
																				</div>
																			<div class="col-md-3 col-sm-2 mnopad national_id" style="display:none;">
																				<div class="form-group inputtext">
																					<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="adult_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="adult_national_id<?php echo $i; ?>" value="<?php echo $national_id; ?>">
																				</div>
																			</div>
																			</div>
																			</div>
																			<div class="clearfix"></div>
																		</div>
																		</div>
																			<?php
																				}
																				$child = $search_data->child;
																				for ($i = 0; $i < $child; $i++)
																				{ 
																			?>
																			<div class="mn_nopadding">
																					<div class="col-md-12 col-sm-12">
																						<div class="form-group">
																							<h4><?php echo $this->lang->line("child_colan");
																							?></h4>
																						</div>
																					</div>
																				</div>
																		<div class="traveller_div">
																			<div class="col-md-12 mn_nopadding">
																				
																				<div class="col-sm-12 mn_nopadding">
																				<div class="col-sm-5 mnopad">
																				<div class="col-md-1 col-sm-1">    
																					<i class="fa fa-list"></i> 
																				</div>
																				<div class="col-md-2 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" id="child_salutation<?php echo $i; ?>">
																							<option value="4"><?php echo $this->lang->line("mstr");
																							?></option>
																							<option value="3"><?php echo $this->lang->line("miss");
																							?></option>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-5 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_fname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="child_fname<?php echo $i; ?>">
																					</div>
																				</div>
																				<div class="col-md-4 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_lname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="child_lname<?php echo $i; ?>">
																					</div>
																				</div>
																				</div>
																				<div class="col-sm-7 mnopad"> 
																				<div class="col-md-3 col-sm-2 mnopad">
																						<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_nationality[]" class="set_country select2" readonly="true" id="<?php echo 'child_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
																						</select>
																				</div>
																				
																				<div class="col-md-2 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_dob[]" class="inputs_group till_current_date child" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="child_dob<?php echo $i; ?>">
																					</div>
																				</div>

																				<div class="col-md-2 col-sm-2 mnopad not_required">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_passport[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="child_passport<?php echo $i; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
																					</div>
																				</div>

																				<div class="col-md-2 col-sm-2 mnopad not_required">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_passport_expire[]" placeholder=<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_current_date" readonly="true" id="child_passport_expire<?php echo $i; ?>"">
																					</div>
																				</div>
																			<div class="col-md-2 col-sm-2 mnopad national_id" style="display:none;">
																				<div class="form-group inputtext">
																					<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="child_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="child_national_id<?php echo $i; ?>">
																				</div>
																			</div>
																			</div>
																			</div>
																			<div class="clearfix"></div>
																		</div>
																		</div>
																			<?php
																				}
																				$infant = $search_data->infant;
																				for ($i = 0; $i < $infant; $i++)
																				{ 
																			?>
																			<div class="mn_nopadding">
																					<div class="col-md-12 col-sm-12">
																						<div class="form-group">
																							<h4><?php echo $this->lang->line("infant_colan"); ?></h4>
																						</div>
																					</div>
																				</div>
																		<div class="traveller_div">
																			<div class="col-md-12 mn_nopadding">
																				
																				<div class="col-sm-12 mn_nopadding">
																				<div class="col-sm-5 mnopad">
																				<div class="col-md-1 col-sm-1">    
																					<i class="fa fa-list"></i> 
																				</div>
																				<div class="col-md-2 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_salutation[]" title="<?php echo $this->lang->line("title"); ?>" class="select2" id="infant_salutation<?php echo $i; ?>">
																							<option value="4"><?php echo $this->lang->line("mstr");
																							?></option>
																							<option value="3"><?php echo $this->lang->line("miss");
																							?></option>
																						</select>
																					</div>
																				</div>

																				<div class="col-md-5 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_fname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="infant_fname<?php echo $i; ?>">
																					</div>
																				</div>
																				<div class="col-md-4 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_lname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="infant_lname<?php echo $i; ?>">
																					</div>
																				</div>
																				</div>
																				<div class="col-sm-7 mnopad">
																				<div class="col-md-3 col-sm-2 mnopad">
																						<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_nationality[]" class="set_country select2" readonly="true" id="<?php echo 'infant_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
																						</select>
																				</div>
																				
																				<div class="col-md-2 col-sm-2 mnopad">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_dob[]" class="inputs_group till_current_date infant" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="infant_dob<?php echo $i; ?>">
																					</div>
																				</div>

																				<div class="col-md-2 col-sm-2 mnopad not_required">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_passport[]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="infant_passport<?php echo $i; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
																					</div>
																				</div>

																				<div class="col-md-2 col-sm-2 mnopad not_required">
																					<div class="form-group inputtext">
																						<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_passport_expire[]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_current_date" readonly="true" id="infant_passport_expire<?php echo $i; ?>">
																					</div>
																				</div>
																			<div class="col-md-2 col-sm-2 mnopad national_id" style="display:none;">
																				<div class="form-group inputtext">
																					<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="infant_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="infant_national_id<?php echo $i; ?>">
																				</div>
																			</div>
																			</div>
																			</div>
																			<div class="clearfix"></div>
																		</div>
																		</div>
																			<?php
																				}
																			?>
																			
																		</div>
																	</li>
																	</ul>

																<div class="clearfix"></div>
															

																   <div class="col-md-12 nopadding">
																   <h3><?php echo $this->lang->line("contact_details"); ?></h3>
																	<ul class="timeline_1">
																	<li>
																	<div class="col-md-12 nopadding">
                                                                    <div class="traveller_div">
																	<div class="col-md-3 col-sm-3 bok_mrgtop">
																		<div class="form-group inputtext">
																			<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-email="true" name="email" class="inputs_group" placeholder="<?php echo $this->lang->line("email_address"); ?>" title="<?php echo $this->lang->line("email_address"); ?>" value="<?php echo $user_exists ? $user_details->email_id : ''; ?>">
																		</div>
																	</div>
																	<div class="col-md-3 col-sm-3 bok_mrgtop">
																		<div class="form-group inputtext">
																			<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-flexi_contact="true" name="contact" class="inputs_group" placeholder="<?php echo $this->lang->line("contact_number"); ?>" title="<?php echo $this->lang->line("contact_number"); ?>" value="<?php echo $user_exists ? $user_details->contact_no : ''; ?>">
																		</div>
																	</div>
																		<div class="col-sm-6">
																			<div class="cartitembuk prompform">
																				<form class="discounts" action="javascript:void(0);" method="post">
																					<div class="col-md-8 col-xs-8 mn_nopadding_right">
																						<div class="cartprc">
																							<div class="payblnhm singecartpricebuk ritaln">
																								<input type="text" autocomplete="off" class="promocode" data-rule-required="true" name="code" placeholder="<?php echo $this->lang->line("enter_promocode"); ?>">
																							</div>
																						</div>
																					</div>
																					<div class="col-md-4 col-xs-4 mn_nopadding_left payblnhm">
																						<input type="submit" class="promosubmit" data-rule-required="true" name="apply" value="<?php echo $this->lang->line("apply"); ?>">
																					</div>
																				</form>
																			</div>
																		</div>
																	</div>
																</div>
																	</li>
																</ul>
																</div>
																
															</div>
														</div>
													</div>
												</div>
												<!-- / panel 2 --> 
												
											</div>
											<!-- / panel-group -->
											
											
										</div>
										<div class="clearfix"></div>
									</div>
								
							</div>
						</section>
						<div class="clearfix"></div>
					</div>

					

                     <div class="col-md-8 nopadding">
                    	<!-- Aditional Services starts here -->
						<div class="col-md-12 nopadding add_serv">
							<h3><?php echo $this->lang->line("additional_services"); ?></h3>
							<ul class="timeline_1">
								<li>
									<div class="col-md-12 cip-comm">
										<div class="col-sm-9 mn_nopadding">
											<?php
											if(count($cip_out_airports) > 0)
											{
										?>
										<label><span class='fa fa-angle-double-right'> <span><?php echo $this->lang->line("cip_outbound"); ?> - <?php echo $this->lang->line("cost_per_person"); ?></span></span>
											</label> <label><i style='color:#157175' class='fa fa-question-circle-o'></i></label><br/>
										<?php
												foreach($cip_out_airports AS $cip_out_code => $cip_out_obj)
												{
										?>
											<label><?php echo $airports[$cip_out_code]->city." (".$cip_out_code.")"; ?> - <span class="amount"><?php echo $flight_details->currency.' '.$cip_out_obj->cip_out; ?></span>
												<div class="radio_box">
													<input type="radio" data-rule-required="true" class="css-checkbox" value="1" name="cip_out[<?php echo $cip_out_code ?>]" id="cip_out_y_<?php echo $cip_out_code; ?>" data-amount="<?php echo $cip_out_obj->cip_out; ?>">
													<label class="css-label" for="cip_out_y_<?php echo $cip_out_code; ?>"><?php echo $this->lang->line("yes_small"); ?></label> 
													<input type="radio" data-rule-required="true" class="css-checkbox" value="0" checked="checked" name="cip_out[<?php echo $cip_out_code; ?>]" id="cip_out_n_<?php echo $cip_out_code; ?>" data-amount="<?php echo $cip_out_obj->cip_out; ?>">
													<label class="css-label" for="cip_out_n_<?php echo $cip_out_code; ?>"><?php echo $this->lang->line("no_small"); ?></label>
													<div class="clearfix"></div>
												</div>
											</label>
											<div class="clearfix"></div>
											<?php
													}
												}
												else
												{
													echo "<label><span class='fa fa-angle-double-right'> <span>".$this->lang->line("cip_outbound")." - ".$this->lang->line("cost_per_person")."</span></span>
											</label> <label><i style='color:#157175' class='fa fa-question-circle-o'></i></label><br/><label>".$this->lang->line("no_cip_outbound")."
											</label>";
												}
										?>
										</div>
										<div class="col-sm-3 mn_nopadding">
											<span class="pull-right"><?php echo $flight_details->currency; ?>
												<span class="amount cipout_cost">0</span>
											</span>
										</div>
									</div>
									<div class="col-md-12 cip-comm">
										<div class="col-sm-9 mn_nopadding">
										<?php
											if(count($cip_in_airports) > 0)
											{
										?>
										<label><span class='fa fa-angle-double-right'> <span><?php echo $this->lang->line("cip_inbound"); ?> - <?php echo $this->lang->line("cost_per_person"); ?></span></span>
											</label> <label><i style='color:#157175' class='fa fa-question-circle-o'></i></label><br/>
										<?php
												foreach($cip_in_airports AS $cip_in_code => $cip_in_obj)
												{
										?>
											<label><?php echo $airports[$cip_in_code]->city." (".$cip_in_code.")"; ?> - <span class="amount"><?php echo $flight_details->currency.' '.$cip_in_obj->cip_in; ?></span>
												<div class="radio_box">
													<input type="radio" data-rule-required="true" class="css-checkbox" value="1" name="cip_in[<?php echo $cip_in_code; ?>]" id="cip_in_y_<?php echo $cip_in_code; ?>" data-amount="<?php echo $cip_in_obj->cip_in; ?>">
													<label class="css-label" for="cip_in_y_<?php echo $cip_in_code; ?>"><?php echo $this->lang->line("yes_small"); ?></label> 
													<input type="radio" data-rule-required="true" class="css-checkbox" value="0" checked="checked" name="cip_in[<?php echo $cip_in_code; ?>]" id="cip_in_n_<?php echo $cip_in_code; ?>" data-amount="<?php echo $cip_in_obj->cip_in; ?>">
													<label class="css-label" for="cip_in_n_<?php echo $cip_in_code; ?>"><?php echo $this->lang->line("no_small"); ?></label>
													<div class="clearfix"></div>
												</div>
											</label>
											<div class="clearfix"></div>
											<?php
													}
												}
												else
												{
													echo "<label><span class='fa fa-angle-double-right'> <span>".$this->lang->line("cip_inbound")." - ".$this->lang->line("cost_per_person")."</span></span>
											</label> <label><i style='color:#157175' class='fa fa-question-circle-o'></i></label><br/><label>".$this->lang->line("no_cip_inbound")."
											</label>";
												}
										?>
										</div>
										<div class="col-sm-3 mn_nopadding">
											<span class="pull-right"><?php echo $flight_details->currency; ?>
												<span class="amount cipin_cost">0</span>
											</span>
										</div>
									</div>
									<div class="col-md-12 cip-comm">
										<div class="col-sm-9 mn_nopadding">
											<span class="pull-right">
												<?php echo $this->lang->line("total_service_charges"); ?>
											</span>
										</div>
										<div class="col-sm-3 mn_nopadding">
											<span class="pull-right"><?php echo $flight_details->currency; ?>
												<span class="amount total_service_cost">0</span>
											</span>
										</div>
									</div>
								</li>
							</ul>

							
						</div>
						<!-- Aditional Services ends here -->
                     </div>

                     </form>


					<div class="col-md-4 mn_nopadding_right">


						<!-- <div class="hotel_details_1 contact-box">
							<div class="travelo-box">
								<h3><i class="soap-icon-phone"></i><?php echo $this->lang->line("need_assistance"); ?></h3>
								<p><?php echo $this->lang->line("need_assistance_description"); ?></p>
								<address class="contact-details">
								<span class="contact-phone"> <?php echo isset($contact_address) && $contact_address !== false ? $contact_address->address : $this->lang->line("na_caps"); ?>-<?php echo $this->lang->line("hello_caps"); ?></span> <br>
								<a class="contact-email" href="javascript:void(0);"><?php echo isset($contact_address) && $contact_address !== false ? $contact_address->email : $this->lang->line("na_caps"); ?></a>
								</address>
							</div>
						</div> -->
					
						
						<!-- <div class="hotel_details_1 contact-box" style="display:inline-block; width:100%;">
							<div class="cartlistingbuk">
								<div class="cartitembuk">
									<div class="col-md-12">
										<div class="payblnhmxm"><?php echo $this->lang->line("have_promocode"); ?></div>
									</div>
								</div>
								<div class="clear"></div>
								<div class="cartitembuk prompform">
									<form class="discounts" action="javascript:void(0);" method="post">
										<div class="col-md-8 col-xs-8 mn_nopadding_right">
											<div class="cartprc">
												<div class="payblnhm singecartpricebuk ritaln">
													<input type="text" autocomplete="off" class="promocode" data-rule-required="true" name="code" placeholder="<?php echo $this->lang->line("enter_promocode"); ?>">
												</div>
											</div>
										</div>
										<div class="col-md-4 col-xs-4 mn_nopadding_left payblnhm">
											<input type="submit" class="promosubmit" data-rule-required="true" name="apply" value="<?php echo $this->lang->line("apply"); ?>">
										</div>
									</form>
								</div>
								<div class="clear"></div>
								<div class="savemessage"></div>
							</div>
						</div> -->
						<span class="mar_top"></span>
						<div class="hotel_details_1 contact-box" style="display:inline-block; width:100%;">

							<div class="cartlistingbuk">
								<div class="cartitembuk">
									<div class="col-md-6 col-sm-6 col-xs-6 celcart">
										<div class="payblnhm"><?php echo $this->lang->line("sub_total"); ?></div>
									</div>
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="cartprc">
											<div class="ritaln cartcntamnt normalprc"><?php echo $flight_details->currency; ?> <span class="amount total_base_cost" data-amount="<?php echo $flight_details->total_cost; ?>"  data-admin-amount="<?php echo $flight_details->admin_cost; ?>" data-travellers="<?php echo $total_travellers; ?>" data-travel-duration="<?php echo $travel_duration; ?>"><?php echo number_format(($flight_details->total_cost - $flight_details->api_tax), 0, "", ","); ?></span></div>
										</div>
									</div>
								</div>
								<div class="cartitembuk">
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="payblnhm"><?php echo $this->lang->line("tax"); ?></div>
									</div>
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="cartprc">
											<div class="ritaln cartcntamnt normalprc discount"><?php echo $flight_details->currency; ?> <span class="amount flight_total_tax"><?php echo number_format($flight_details->api_tax, 0, "", ","); ?></span></div>
										</div>
									</div>
								</div>

								<div class="cartitembuk">
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="payblnhm"><?php echo $this->lang->line("additional_charges"); ?></div>
									</div>
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="cartprc">
											<div class="ritaln cartcntamnt normalprc discount"><?php echo $flight_details->currency; ?> <span class="amount flight_additional_charges">0</span></div>
										</div>
									</div>
								</div>

								<div class="cartitembuk">
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="payblnhm"><?php echo $this->lang->line("discount"); ?></div>
									</div>
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="cartprc">
											<div class="ritaln cartcntamnt normalprc discount"><?php echo $flight_details->currency; ?> <span class="amount discount_charges">0</span></div>
										</div>
									</div>
								</div>
							</div>
							<div class="cartlistingbuk nomarr">
								<div class="cartitembuk">
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="payblnhm"><?php echo $this->lang->line("total_amount"); ?><span style="color:red;" class="hide"> <?php echo $this->lang->line("price_changed"); ?></span></div>
									</div>
									<div class="col-md-6  col-sm-6 col-xs-6 celcart">
										<div class="cartprc">
											<div class="ritaln cartcntamnt bigclrfnt finalAmt"><?php echo $flight_details->currency ?> <span class="amount total_flight_cost"><?php echo number_format($flight_details->total_cost, 0, "", ","); ?></span></div>
										</div>
									</div>
								</div>
							</div>

						</div>
						<ul class="list-inline">
								<li>
									<a type="button" style="color:white;" href="<?php echo base_url('flight/lists/'.$hash); ?>" class="searchbtn"><i class="fa fa-reply"></i> <?php echo $this->lang->line("back"); ?></a>
								</li>
									<?php
										if($flight_details->fare_type !== "WebFare" && isset($this->data["user_type"]) && $this->data["user_type"] === B2B_USER)
											echo "<li><button type='button' data-fr-id='".$fr_id."' class='searchbtn reserve_flight'>".$this->lang->line("reserve")."</button></li>";
									?>
								<li>
									<input type="hidden" data-rule-required="false" name="promocode" value="" class="flight_promocode valid" />
									<button type="button" data-fr-id="<?php echo $fr_id; ?>" class="searchbtn book_flight"><?php echo $this->lang->line("book"); ?></button>
								</li>
							</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
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
<div id="revalidatemodal" class="modal fade in" role="dialog" style="background-color : rgba(0, 0, 0, 0.7)">
	<div class="loading"></div>
	<div style="color:white;text-align:center;"><?php echo $this->lang->line("verifying_price"); ?></div>
</div>
<div id="bookmodal" class="modal fade in" role="dialog" style="background-color : rgba(0, 0, 0, 0.7)">
	<div class="loading"></div>
	<div style="color:white;text-align:center;"><?php echo $this->lang->line("please_wait_for_ticket_book"); ?></div>
</div>
<script type="text/javascript" data-main="<?php echo asset_url('js/config'); ?>" src="<?php echo asset_url('js/require.js'); ?>"></script>
</body>
</html>