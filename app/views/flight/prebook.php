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
$irramount = showCurrency('USD');
?>

<style>
.qus { background: #ddd; border-radius:50%; }
.add_serv{margin: 10px 0;}
.add_serv h3{margin: 10px 0;font-size: 18px;color: #f39072;}
.cip-comm{margin: 10px 0;}
.add_serv label span{font-size: 14px;}
.add_serv label span span {     font-family: 'Roboto', sans-serif;}
.tt{cursor: pointer;}
.inputs_group { height: 26px; }
.select2-container .select2-choice { height: 26px;}
.select2-chosen, .select2-choice > span:first-child, .select2-container .select2-choices .select2-search-field input { 
                           padding: 5px 6px; margin-right: 16px !important; }
.cip-comm { background: #fff; padding: 10px 10px; margin: 2px 0px; }	
.passport_num,.passport_exp{ display: none; }                                   
</style>


<!-- <span class="cart_icon"><i class="fa fa-cart-plus"></i></span>  -->
<div class="clearfix"></div>
<section class="mn-reg">
<div class="container">
	<div class="row">
		<div class="fancy-title flight_prebook_div">
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
											<!--<div class="flight_title flight_nos" ng-bind="departure_details.airline+''+departure_details.flight_no"></div>-->
<div class="flight_title flight_nos" ng-bind="departure_details.flight_no"></div>
												<div class="clearfix"></div>
												<!--<div class="flight_title" ng-bind="(departure_details.airline | airline_name:airlines)"></div>-->
												
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
												<div class="dateinfo" ng-bind="(departure_details.arrival_dttm | readable_dttm)"></div>
												<div class="clearfix"></div>
												<div class="dateinfo fa_content"><span ng-bind="(departure_details.arrival_dttm | fa_readable_dt)"></span> <span ng-bind="(departure_details.arrival_dttm | fa_readable_tm)"></span> </div>
											</div>
										</div>

										<div class="col-md-2 col-sm-2 nopadding">
											<div class="arrival">
												<div class="col-xs-12 time nopadding" ng-bind="departure_details.duration+' <?php echo $this->lang->line("short_hours"); ?>'"></div>
												<div ng-if="departure_details.wait_place.length > 0" class="col-xs-12 time nopadding" ng-bind-html="(departure_details.wait_tm | mins_to_time)+' <?php echo $this->lang->line("stop_over_at"); ?> '+(departure_details.wait_place | airport_city:airports)"></div>
											</div>
										</div>
							
										<div class="col-md-12 col-sm-12 nopadding operating_carrier" ng-if="departure_details.airline !== departure_details.operating_airline" ng-bind="' <?php echo $this->lang->line("operating_carrier_changed_to"); ?>'+departure_details.operating_airline_name">
										</div>	
									</div>
									<hr ng-if="flight_row.arrivals.length > 0" />
									<div class="flight_middle" ng-repeat="arrival_details in flight_row.arrivals track by $index">
										<div class="col-md-2 col-sm-2 nopadding">
											<div class="flight_left"> <img ng-src="{{arrival_details.airline | airline_image}}"/> 
                                            <div class="clearfix"></div>
											<!--<div class="flight_title flight_nos" ng-bind="arrival_details.airline+''+arrival_details.flight_no"></div>-->
<div class="flight_title flight_nos" ng-bind="arrival_details.flight_no"></div>

												<div class="clearfix"></div>
												<!--<div class="flight_title" ng-bind="(arrival_details.airline | airline_name:airlines)"></div>-->
												
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
												<div class="dateinfo" ng-bind="arrival_details.departure_dttm+' <?php echo $this->lang->line("short_hours"); ?>'"></div>
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
												<div class="col-xs-12 time nopadding" ng-bind="arrival_details.duration+ ' <?php echo $this->lang->line("short_hours"); ?>'"></div>
												<div ng-if="arrival_details.wait_place.length > 0" class="col-xs-12 time nopadding" ng-bind-html="(arrival_details.wait_tm | mins_to_time)+' <?php echo $this->lang->line("stop_over_at"); ?> '+(arrival_details.wait_place | airport_city:airports)"></div>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 nopadding operating_carrier" ng-if="arrival_details.airline !== arrival_details.operating_airline" ng-bind="' <?php echo $this->lang->line("operating_carrier_changed_to"); ?>'+arrival_details.operating_airline_name">
									</div>
								</div>
								<div class="col-md-2 col-sm-2 nopadding_right">
									<div class="flight_right">
										<div class="col-md-12 col-sm-12 nopadding">
											
											<div class="clearfix"></div>
											<label><?php echo $this->lang->line("total_cost_colan"); ?> </label>
											<!--<h2 ng-bind="(flight_row.total_cost | custom_currency:currency:currency_val)"></h2>-->

<?php
if($_SESSION['default_currency']=="USD"){
?>
<h2  ng-bind="('<?php if($_SESSION['default_language']=='en')
	                 { echo $this->data["default_currency"]; } 
				 else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; }  } 
			  ?>'+' '+((flight_row.total_cost)*<?php echo $irramount; ?>  | number:2))"></h2>


<?php } else { ?>											
<h2 ng-bind="('<?php if($_SESSION['default_language']=='en')
	                 { echo $this->data["default_currency"]; } 
				 else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } 
			  ?>'+' '+flight_row.total_cost)"></h2>
<?php } ?>
										</div>
									</div>
									<div class="col-md-12 flinks nopadding">
									<a class="col-sm-12 text-center" href="javascript:void(0);" ng-click="fare_details(flight_row.id)"><?php echo $this->lang->line("fare_rules"); ?></a>
									<a class="col-sm-12 text-center" href="javascript:void(0);" ng-click="baggage_details(flight_row.id)"><?php echo $this->lang->line("baggages"); ?></a>
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

			<form role="form" action="javascript:void(0);" class="prebook_form" name="flight_prebook_form" id="flight_prebook_form">


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
													<div class="col-md-12">
													<h3><?php echo $this->lang->line("traveller_details"); ?></h3>
														<ul class="timeline_1">
															<li> 
																<div class="timeline-badge none"></div>
																<div class="col-sm-1 mn_nopadding">
																			<div class="col-md-12 col-sm-12">
																				<div class="form-group">
																					<h4><?php echo $this->lang->line("adult_colan");
																					?></h4>
																				</div>
																			</div>
																		</div>
															<div class="clearfix"></div>
																<div class="timeline-panel">
																<div class="bg_adult">
																	<?php
																		$adult = $search_data->flight_type === MULTICITY ? $search_data->madult : $search_data->adult;
																		for ($i = 0; $i < $adult; $i++)
																		{
																			$salutation = $fname = $lname = $name_fa = $dob = $passport_no = $passport_no_expire = $national_id = "";
																			$nationality = "IR";
																			if($user_exists)
																			if($i === 0)
																			{	
																				
																				$salutation = $user_details->general;
																				$fname = $user_details->firstname;
																				$lname = $user_details->lastname;
																				$name_fa = $user_details->name_fa;
																				$nationality = $user_details->country;
																				$dob = date("m/d/Y", strtotime($user_details->dob));
																				$passport_no = $user_details->passport_no;
																				$passport_no_expire = date("m/d/Y", strtotime($user_details->passport_exp_date));
																				$national_id = $user_details->national_no;
																			}
																	?>

																<div class="traveller_div" id="adult_traveller<?php echo $i; ?>">
																
																	<div class="col-md-12 mn_nopadding">
																		
																		<div class="col-sm-12 mn_nopadding"> 
																		<div class="col-md-5 nopadding">
																		<div class="col-md-1 col-sm-1">    
																		<i class="fa fa-list listpopbtn"></i> 


																		</div>	

                                                                   <div class="col-md-2 col-sm-2 nopadding salutation">
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
																		<div class="col-md-3 col-sm-5 mnopad fname">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_fname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="adult_fname<?php echo $i; ?>" value="<?php echo $fname; ?>">
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-4 mnopad lname">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_lname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="adult_lname<?php echo $i; ?>" value="<?php echo $lname; ?>">
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-4 mnopad fa_name">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_name_fa[]" class="inputs_group par_rtl" placeholder="<?php echo $this->lang->line('full_name'); ?>" title="<?php echo $this->lang->line('full_name'); ?>" id="adult_name_fa<?php echo $i; ?>" value="<?php echo $name_fa; ?>">
																			</div>
																		</div>
                                                                       </div>

                                                                        <div class="col-md-7 nopadding"> 
																		<div class="col-md-3 col-sm-3 mnopad nationality">
																				<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_nationality[]" class="set_country select2" id="<?php echo 'adult_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
																				</select>
																		</div>
																		<div class="col-md-2 col-sm-2 mnopad national_id" style="display:block;">
																		<div class="form-group inputtext">
																			<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="adult_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="adult_national_id<?php echo $i; ?>" value="<?php echo $national_id; ?>">
																		</div>
																		</div>
																		
																		<div class="col-md-2 col-sm-2 mnopad d_o_b">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_dob[]" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="adult_dob<?php echo $i; ?>" value="<?php echo $dob; ?>">
																			</div>
																		</div>

																		<div class="col-md-2 col-sm-2 mnopad passport_num">
																			<div class="form-group inputtext">
																				<input type="text" disabled autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport[<?php echo $i; ?>]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="adult_passport<?php echo $i; ?>" value="<?php echo $passport_no; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
																			</div>
																		</div>

																		<div class="col-md-2 col-sm-2 mnopad passport_exp">
																			<div class="form-group inputtext">
																				<input type="text" disabled autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="adult_passport_expire[<?php echo $i; ?>]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_current_date" readonly="true" id="adult_passport_expire<?php echo $i; ?>" value="<?php echo $passport_no_expire; ?>">
																			</div>
																		</div>

																		

																		<?php
																			if($iord === IORDI)
																			{
																		?>
																		<div class="col-md-1 col-sm-1 mnopad">
																			<div class="form-group inputtext">
																				<label style="margin-left: 12px;"><input type="checkbox"  data-rule-required="false" class="" value="1" title="<?php echo $this->lang->line('wheel_chair'); ?>" name="adult_wheel_chair[<?php echo $i; ?>]" id="adult_wheel_chair<?php echo $i; ?>"><i class="fa fa-wheelchair-alt"></i></label>
																			</div>
																		</div>
																		<?php
																		}
																		?>
																		<?php
																		// echo '<pre>',print_r($services_opt);exit; 
																		if(is_array($services_opt) && count($services_opt) > 0)
																		{
																		?>
																		<div class="col-md-6 col-sm-6 mnopad">
																			<div class="form-group inputtext">
																				<select style="width:100% !important;" name="adult_baggage[]" class="baggage_select select2" id="adult_baggage<?php echo $i; ?>" >
																					<option data-amount="0" value=""><?php echo $this->lang->line("no_baggage");
																					?></option>
																				<?php
																					for ($x = 0; $x < count($services_opt); $x++)
																					{
																						if($services_opt[$x]->type === BAGGAGE_TYPE)
																						echo "<option value='".$services_opt[$x]->id."' data-amount='".$services_opt[$x]->amount."'>".$services_opt[$x]->desc." => ".$services_opt[$x]->currency." ".number_format($services_opt[$x]->amount, 0, "", ",")."</option>";
																					}
																				?>
																				</select>
																			</div>
																	</div>
																<?php
																	}
																?>
																	</div>

																	</div>
																
																	<div class="clearfix"></div>
																</div>
																</div>
															
																	<?php
																		} ?> 

</div>



																	<?php
																		
																		$child = $search_data->flight_type === MULTICITY ? $search_data->mchild : $search_data->child;
																		if($child){
																		?>
																		<div class="col-sm-1 mn_nopadding">
																			<div class="col-md-12 col-sm-12">
																				<div class="form-group">
																					<h4><?php echo $this->lang->line("child_colan");
																					?></h4>
																				</div>
																			</div>
																		</div>
																		 <div class="clearfix"></div>
																	 <div class="bg_adult">

																		<?php
																		for ($i = 0; $i < $child; $i++)
																		{ 
																	?>
																	
																	
																<div class="traveller_div" id="child_traveller<?php echo $i; ?>">
																	<div class="col-md-12 mn_nopadding">
																		
																		<div class="col-sm-12 mn_nopadding">
																		<div class="col-sm-5 nopadding">
																		<div class="col-md-1 col-sm-1">    
																		<i class="fa fa-list listpopbtn"></i> 

																		</div>	

																		<div class="col-md-2 col-sm-2 nopadding salutation">
																			<div class="form-group inputtext">
																				<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" name="child_salutation[]" title="<?php echo $this->lang->line("title");
																					?>" class="select2" id="child_salutation<?php echo $i; ?>">
																					<option value="0"><?php echo $this->lang->line("mr");
																					?></option>
																					<option value="3"><?php echo $this->lang->line("miss");
																					?></option>
																				</select>
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-2 mnopad fname">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="child_fname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="child_fname<?php echo $i; ?>">
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-2 mnopad lname">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="child_lname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="child_lname<?php echo $i; ?>">
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-4 mnopad fa_name">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="child_name_fa[]" class="inputs_group par_rtl" placeholder="نام و نام خانوادگی" title="<?php echo $this->lang->line('last_name'); ?>" id="child_name_fa<?php echo $i; ?>" value="<?php echo $lname; ?>">
																			</div>
																		</div>
																		</div>
																		<div class="col-sm-7 nopadding">
																		<div class="col-md-3 col-sm-3 mnopad nationality">
																				<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="child_nationality[]" class="set_country select2" id="<?php echo 'child_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
																				</select>
																		</div>
																		<div class="col-md-2 col-sm-2 mnopad national_id" >
																		<div class="form-group inputtext">
																			<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="child_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="child_national_id<?php echo $i; ?>">
																		</div>
																	</div>
																		<div class="col-md-2 col-sm-2 mnopad d_o_b">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="child_dob[]" class="inputs_group till_current_date child" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="child_dob<?php echo $i; ?>">
																			</div>
																		</div>
																		<div class="col-md-2 col-sm-2 mnopad passport_num">
																			<div class="form-group inputtext">
																				<input type="text" disabled autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="child_passport[<?php echo $i; ?>]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="child_passport<?php echo $i; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
																			</div>
																		</div>

																	<div class="col-md-2 col-sm-2 mnopad passport_exp">
																			<div class="form-group inputtext">
																				<input type="text" disabled autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="child_passport_expire[<?php echo $i; ?>]" placeholder="<?php echo $this->lang->line("passport_expiry_date");
																					?>" title="<?php echo $this->lang->line("passport_expiry_date");
																					?>" class="inputs_group from_current_date" readonly="true" id="child_passport_expire<?php echo $i; ?>">
																			</div>
																		</div>

																	</div>
																	</div>
																	<div class="clearfix"></div>
																</div>
																</div>

																<?php
																		}}
																		?>
																		</div>
																	<?php
																		
																		$infant = $search_data->flight_type === MULTICITY ? $search_data->minfant : $search_data->infant;

																		if($infant){
																			?>
																		<div class="col-sm-1 mn_nopadding">
																			<div class="col-md-12 col-sm-12">
																				<div class="form-group">
																					<h4><?php echo $this->lang->line("infant_colan");
																					?></h4>
																				</div>
																			</div>
																		</div>
																		<div class="clearfix"></div>
																 <div class="bg_adult">
																		<?php
																		for ($i = 0; $i < $infant; $i++)
																		{ 
																	?>
																	
																
																<div class="traveller_div" id="infant_traveller<?php echo $i; ?>">
																	<div class="col-md-12 mn_nopadding">
																		
																		<div class="col-sm-12 mn_nopadding">
																		<div class="col-sm-5 nopadding">
																		<div class="col-md-1 col-sm-1">    
																		<i class="fa fa-list listpopbtn"></i> 
																		</div>	
																		<div class="col-md-2 col-sm-2 nopadding salutation">
																			<div class="form-group inputtext">
																				<select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="infant_salutation[]" title="Title" class="select2" id="infant_salutation<?php echo $i; ?>">
																					<option value="0"><?php echo $this->lang->line("mr");
																					?></option>
																					<option value="3"><?php echo $this->lang->line("miss");
																					?></option>
																				</select>
																			</div>
																		</div>

																		<div class="col-md-3 col-sm-2 mnopad fname">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="infant_fname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" id="infant_fname<?php echo $i; ?>">
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-2 mnopad lname">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="infant_lname[]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>" id="infant_lname<?php echo $i; ?>">
																			</div>
																		</div>
																		<div class="col-md-3 col-sm-4 mnopad fa_name">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="infant_name_fa[]" class="inputs_group par_rtl" placeholder="نام و نام خانوادگی" title="<?php echo $this->lang->line('last_name'); ?>" id="infant_name_fa<?php echo $i; ?>" value="<?php echo $lname; ?>">
																			</div>
																		</div>
																		</div>
																		<div class="col-sm-7 nopadding">
																		<div class="col-md-3 col-sm-2 mnopad nationality">
																				<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="infant_nationality[]" class="set_country select2" id="<?php echo 'infant_nationality'.$i; ?>" data-href="<?php echo $nationality; ?>">
																				</select>
																		</div>

																		<div class="col-md-2 col-sm-2 mnopad national_id">
																		<div class="form-group inputtext">
																			<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="infant_national_id[<?php echo $i; ?>]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="infant_national_id<?php echo $i; ?>">
																		</div>
																	</div>
																		
																		<div class="col-md-2 col-sm-2 mnopad d_o_b">
																			<div class="form-group inputtext">
																				<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");
																					?>" name="infant_dob[]" class="inputs_group till_current_date infant" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>" id="infant_dob<?php echo $i; ?>">
																			</div>
																		</div>

																	<div class="col-md-2 col-sm-2 mnopad passport_num">
																			<div class="form-group inputtext">
																				<input type="text" disabled autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" name="infant_passport[<?php echo $i; ?>]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group" id="infant_passport<?php echo $i; ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
																			</div>
																		</div>

																	<div class="col-md-2 col-sm-2 mnopad passport_exp">
																			<div class="form-group inputtext">
																				<input type="text" disabled autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" name="infant_passport_expire[<?php echo $i; ?>]" placeholder="<?php echo $this->lang->line("passport_expiry_date") ?>" title="<?php echo $this->lang->line("passport_expiry_date") ?>" class="inputs_group from_current_date" readonly="true" id="infant_passport_expire<?php echo $i; ?>">
																			</div>
																		</div>

																	<?php
																	if($iord === IORDI)
																	{
																	?>
																	<div class="col-md-2 col-sm-2 mnopad">
																		<div class="form-group inputtext">
																			<label><input type="checkbox"  data-rule-required="false" title="<?php echo $this->lang->line('wheel_chair'); ?>" class="" value="1" name="adult_wheel_chair[<?php echo $i; ?>]" id="adult_wheel_chair<?php echo $i; ?>"><i class="fa fa-wheelchair-alt"></i></label>
																		</div>
																	</div>
																	<?php
																	}
																	?>
																
																	
																	</div>
																	</div>
																	<div class="clearfix"></div>
																</div>
																</div>
																	<?php
																		}}
																	?>
																	
																</div>
																</div>
															</li>
															</ul>

														<div class="clearfix"></div>
													

														   <div class="col-md-12">
														   <h3><?php echo $this->lang->line("contact_details"); ?></h3>
															<ul class="timeline_1">
															<li>
															<div class="col-md-12 nopadding">
                                                            <div class="traveller_div">
															<div class="col-md-3 col-sm-3 bok_mrgtop">
																<div class="form-group inputtext">
																	<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" data-rule-email="true" name="email" class="inputs_group" placeholder="<?php echo $this->lang->line('email_address'); ?>" title="<?php echo $this->lang->line('email_address'); ?>" value="<?php echo $user_exists ? $user_details->email_id : ''; ?>">
																</div>
															</div>
															<div class="col-md-3 col-sm-3 bok_mrgtop">
																<div class="form-group inputtext">
																	<input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");?>" data-rule-flexi_contact="true" name="contact" class="inputs_group" placeholder="<?php echo ' +98-912-123-4455';//$this->lang->line('contact_number'); ?>" title="<?php echo $this->lang->line('contact_number'); ?>" value="<?php echo $user_exists ? $user_details->contact_no : ''; ?>">
																</div>
															</div>
															<div class="col-md-6 col-sm-3">
															<div class="cartitembuk prompform">
																<!--  <form class="discounts" action="javascript:void(0);" method="post"> -->
																	<!-- <div class="col-md-8 col-xs-8 mn_nopadding_right">
																		<div class="cartprc">
																			<div class="payblnhm singecartpricebuk ritaln">
																				<input type="text" autocomplete="off" class="inputs_group" data-rule-required="false" name="code" placeholder="<?php echo $this->lang->line("enter_promocode"); ?>">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-4 col-xs-4 mn_nopadding_left payblnhm">
																		<input type="submit" class="promosubmit" data-rule-required="true" name="apply" value="<?php echo $this->lang->line("apply"); ?>">
																	</div> -->
																<!-- </form>  -->
																<ul class="list-inline">
																	<li>
																		<a type="button" style="color:white;" href="<?php echo base_url('flight/lists/'.$hash); ?>" class="searchbtn"><!-- <i class="fa fa-reply"></i> --> <?php echo $this->lang->line("back"); ?></a>
																	</li>
																		<?php
																			if($flight_details->fare_type !== "WebFare" && isset($this->data["user_type"]) && $this->data["user_type"] === B2B_USER)
																				echo "<li><button type='button' data-fr-id='".$fr_id."' class='searchbtn reserve_flight'>".$this->lang->line("reserve")."</button></li>";
																		?>
																	<li>
																		
																		<!-- <input type="hidden" data-rule-required="false" name="promocode" value="" class="apply_promocode valid" /> -->

																		<button type="button" data-fr-id="<?php echo $fr_id; ?>" class="searchbtn book_flight"><?php echo $this->lang->line("book"); ?></button>

																		
																	</li>
																</ul>
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
										
										<!--  panel 3 --> 
										<!--<div class="panel panel-default">  
													<span class="side-tab" data-target="#tab3" data-toggle="tab" role="tab" aria-expanded="false">
													<div class="panel-heading" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapsethree" aria-expanded="false" aria-controls="collapsethree">
														<h4 class="panel-title collapsed">Tours Booking</h4>
													</div>
													</span>
													<div id="collapsethree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
														<div class="panel-body"> 
															tab 3 content </div>
													</div>
												</div>--> 
									</div>
									<!-- / panel-group -->
									
									
								</div>
								<div class="clearfix"></div>
							</div>
						
					</div>
				</section>
				<div class="clearfix"></div>
			</div>

			
			<!-- Aditional Services starts here -->
            <!-- <div class="col-md-8 nopadding">
            	
				<div class="col-md-12 nopadding add_serv">
					<h3><?php echo $this->lang->line("additional_services"); ?></h3>
					<ul class="timeline_1">
						<li>
							<div class="col-md-12 cip-comm">
								<div class="col-md-9 mn_nopadding">
									<label> 
										<span class="fa fa-angle-double-right"> <span><?php echo $this->lang->line("baggage_cost"); ?>:</span> </span>
									</label>
								</div>
								<div class="col-md-3 mn_nopadding">
									<span class="pull-right"><?php echo $flight_details->currency; ?>
										<span class="amount baggage_cost">0</span>
									</span>
								</div>
							</div>
							<div class="col-md-12 cip-comm <?php echo $search_data->flight_type === ONEWAY || $iord !== IORDI ? 'hide' : ''; ?>">
								<div class="col-sm-9 mn_nopadding">
									<label> 
										<span class="fa fa-angle-double-right"> <?php echo $this->lang->line("travel_insurance"); ?> - <?php echo $flight_details->currency." ".$travel_insurance_amount; ?><?php echo $this->lang->line("per_person_per_day"); ?> (<?php echo $this->lang->line("recommanded"); ?>)</span>
									</label> <label><i style="color:#157175" class="fa fa-question-circle-o tt"></i></label>
									<div class="radio_box radio_floatleft">
										<input type="radio" data-rule-required="true" name="travel_insurance" id="travel_insurance_y" class="css-checkbox" value="1" data-amount="<?php echo $travel_insurance_amount; ?>">
										<label for="travel_insurance_y" class="css-label"><?php echo $this->lang->line("yes_small"); ?></label>   
										<input type="radio" data-rule-required="true" name="travel_insurance" id="travel_insurance_n" class="css-checkbox" value="0" checked="checked" data-amount="<?php echo $travel_insurance_amount; ?>">
										<label for="travel_insurance_n" class="css-label"><?php echo $this->lang->line("no_small"); ?></label>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="col-sm-3 mn_nopadding">
									<span class="pull-right"><?php echo $flight_details->currency; ?>
										<span class="amount travel_insurance_cost">0</span>
									</span>
								</div>

							</div>
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
								<label><span class='fa fa-angle-double-right'> <span><?php echo $this->lang->line("	cip_inbound"); ?> - <?php echo $this->lang->line("cost_per_person"); ?></span></span>
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
				
             </div> -->
			<!-- Aditional Services ends here -->
            




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
				<!-- <div class="hotel_details_1 contact-box" style="display:inline-block; width:100%;">
					<div class="cartlistingbuk">
						<div class="cartitembuk">
							<div class="col-md-6 col-sm-6 col-xs-6 celcart">
								<div class="payblnhm"><?php echo $this->lang->line("sub_total"); ?></div>
							</div>
							<div class="col-md-6  col-sm-6 col-xs-6 celcart">
								<div class="cartprc">
									<div class="ritaln cartcntamnt normalprc"><?php echo $flight_details->currency; ?> <span class="amount total_base_cost" data-amount="<?php echo $flight_details->total_cost; ?>" data-travellers="<?php echo $total_travellers; ?>" data-travel-duration="<?php echo $travel_duration; ?>"><?php echo number_format(($flight_details->total_cost - $flight_details->api_tax), 0, "", ","); ?></span></div>
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
				</div> -->
					


             </form>
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

<!-- Companion list modal -->
<div class="modal fade listpop " id="companion_modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Choose Companion</h4>
			</div>
			<div class="modal-body">
			<!-- 	<select data-val="" class="companion_select select2">
					<option>select</option>
					<?php if($user_exists) :?>
					<option value='<?php echo $data_sel_comp_json; ?>'><?php echo @$data_sel_comp['fname']." ".@$data_sel_comp['lname']; ?></option>
					
				<?php endif;?>
				</select> 

 -->
				<div class="">
					<table class="table">
						<thead>
							<th><?php echo $this->lang->line('first_name'); ?></th>
							<th><?php echo $this->lang->line('last_name'); ?></th> 
							<th><?php echo $this->lang->line('full_name'); ?></th>
							<th></th>
						</thead>
						<tbody>
							<tr>
								<td><?php echo @$data_sel_comp['fname']; ?></td>
								<td><?php echo @$data_sel_comp['lname']; ?></td>
								<td><?php echo @$data_sel_comp['name_fa']; ?></td>
								<td><a href="javascript:void(0)" class="companion_select" data-val="" data-json='<?php echo $data_sel_comp_json; ?>'>Select</a></td>

							</tr>
							<?php   foreach ($companions as $key => $value):?>
							<tr>
								<td><?php echo $value->fname?></td>
								<td><?php echo $value->lname; ?></td>
								<td><?php echo $value->name_fa; ?></td>
								<td><a href="javascript:void(0)" class="companion_select" data-val="" data-json='<?php echo json_encode($value); ?>'>Select</a></td>

							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			</div>

		</div>
	</div>
</div>	
<!-- /Companion list modal -->

<script type="text/javascript" data-main="<?php echo asset_url('js/config'); ?>" src="<?php echo asset_url('js/require.js'); ?>"></script>
</body>
</html>
