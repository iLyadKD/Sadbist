<div id="show_search" class="modify_search_details modify_flight_search_details collapse in">
	<form method="post" id="flight_search" action="<?php echo base_url('flight/lists');?>">
		<div class="container nopadding pd15">
			<div class="row">
				<div class="col-xs-12 nopadding pd15">
					<div class="way_type_buttons">
						<ul>
							<li>
								<input type="radio" name="flight_type" data-id="OW" id="oneway" class="css-checkbox flight_type" value="OneWay" <?php echo $search_data->flight_type === ONEWAY ? "checked" : ""; ?>>
								<label for="oneway" class="css-label radGroup1" style="color:#333;"><?php echo $this->lang->line("one_way_caps"); ?></label>
							</li>
							<li>
								<input type="radio" name="flight_type" data-id="RT" id="roundtrip" class="css-checkbox flight_type" value="Return" <?php echo $search_data->flight_type === ROUNDTRIP ? "checked" : ""; ?>>
								<label for="roundtrip" class="css-label radGroup1" style="color:#333;"><?php echo $this->lang->line("round_trip_caps"); ?></label>
							</li>
							<?php if($i_or_d === 'I') : ?>
							<li>
								<input type="radio" name="flight_type" data-id="MC" id="multicity" class="css-checkbox flight_type" value="OpenJaw" <?php echo $search_data->flight_type === MULTICITY ? "checked" : ""; ?>>
								<label for="multicity" class="css-label radGroup1" style="color:#333;"><?php echo $this->lang->line("multi_city_caps"); ?></label>
							</li>
						<?php endif; ?>
						</ul>
					</div>
					<div class="clearfix"></div>
					<div class="col-xs-12 modify_types">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-xs-12 nopadding">
								<div class="normalsearch_div" style="display:<?php echo $search_data->flight_type === MULTICITY ? 'none' : 'block'; ?>">
									<div class="col-xs-12 nopadding">
										<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("from"); ?></label>
											<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group from_flight"  value="<?php echo $search_data->flight_type === MULTICITY ? '' : $airports[$search_data->flight_origin]->city.', '.$airports[$search_data->flight_origin]->airport.', '.$airports[$search_data->flight_origin]->country. ' ('.$search_data->flight_origin.')'; ?>" />
											<input type="hidden" name="flight_origin" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" value="<?php echo $search_data->flight_type === MULTICITY ? '' : $search_data->flight_origin; ?>">
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("to"); ?></label>
											<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group to_flight" value="<?php echo $search_data->flight_type === MULTICITY ? '' : $airports[$search_data->flight_destination]->city.', '.$airports[$search_data->flight_destination]->airport.', '.$airports[$search_data->flight_destination]->country. ' ('.$search_data->flight_destination.')'; ?>" />
											<input type="hidden" name="flight_destination" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" value="<?php echo $search_data->flight_type === MULTICITY ? '' : $search_data->flight_destination; ?>">
										</div>
										<div class="col-xs-12 col-md-2 col-sm-2 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("departure"); ?></label>
											<input type="text" autocomplete="off" name="flight_departure" readonly="readonly" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="flight_departure" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" class="inputs_group cal_back from_date" value="<?php if($search_data->flight_type === MULTICITY){echo "";}else{if ($this->data['default_language'] == "fa") {echo fa_to_en_digits(gregorian_to_jalali(date('d-m-Y',strtotime($search_data->flight_departure)), true));}else{echo date('d-m-Y',strtotime($search_data->flight_departure));}} ?>" data-fa-date="<?php echo $search_data->flight_type === MULTICITY ? '' : date('d-m-Y',strtotime($search_data->flight_departure)); ?>" />
										</div>

										<div class="col-xs-12 col-md-2 col-sm-2 mn_nopadding_right returning_div" style="display:<?php echo $search_data->flight_type !== 'Return' ? 'none' : 'block'; ?>">
										<?php
											$secs = isset($search_data->flight_departure) ? strtotime($search_data->flight_departure." +1 days") : strtotime("+1 days");
											$arrv_dt = new DateTime(date("Y-m-d", $secs));
										?>
											<label class="mod_label required"><?php echo $this->lang->line("return"); ?></label>
											<input type="text" readonly="readonly" autocomplete="off" <?php echo $search_data->flight_type === "Return" ? "" : "disabled"; ?> name="flight_arrival" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-greater="#flight_departure" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="to_date" class="inputs_group cal_back to_date" value="<?php if($search_data->flight_type === MULTICITY){echo "";}else{if(isset($search_data->flight_arrival)){if($this->data['default_language'] == "fa"){echo fa_to_en_digits(gregorian_to_jalali(date('d-m-Y',strtotime($search_data->flight_arrival)), true));}else{echo date('d-m-Y',strtotime($search_data->flight_arrival));}}else{echo $arrv_dt->format('d-m-Y');}} ?>" data-fa-date="<?php echo $search_data->flight_type === MULTICITY ? '' : (isset($search_data->flight_arrival) ?  date('d-m-Y',strtotime($search_data->flight_arrival)) : $arrv_dt->format('d-m-Y')); ?>" />
										</div>
									</div>
								</div>
								<div  class="multisearch_div" style="display:<?php echo $search_data->flight_type !== 'OpenJaw' ? 'none' : 'block'; ?>">
									<div class="col-xs-12 nopadding">

										<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("from"); ?></label>
											<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_origin"  value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $airports[$search_data->mflight_origin[0]]->city.', '.$airports[$search_data->mflight_origin[0]]->airport.', '.$airports[$search_data->mflight_origin[0]]->country. ' ('.$search_data->mflight_origin[0].')'; ?>" />
											<input type="hidden" name="mflight_origin[]" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_origin[0]; ?>">
										</div>

										<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("to"); ?></label>
											<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_destination" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $airports[$search_data->mflight_destination[0]]->city.', '.$airports[$search_data->mflight_destination[0]]->airport.', '.$airports[$search_data->mflight_destination[0]]->country. ' ('.$search_data->mflight_destination[0].')'; ?>" />
											<input type="hidden" name="mflight_destination[]" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_destination[0]; ?>">
										</div>

										<div class="col-xs-12 col-md-2 col-sm-2 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("departure"); ?></label>
											<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="mflight_departure0" name="mflight_departure[]" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" class="inputs_group1 from_date" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_departure[0]; ?>" data-fa-date="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' :  date('d-m-Y',strtotime($search_data->mflight_departure[0])); ?>" />
										</div>

										<div class="clearfix"></div>
									</div>
									<div class="multi_flight">
										<div id="multi_flight" class="col-xs-12 nopadding" style="margin-top: 10px;">
											<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
												<!-- <label class="mod_label required"><?php echo $this->lang->line("from"); ?></label> -->
												<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_origin"  value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $airports[$search_data->mflight_origin[1]]->city.', '.$airports[$search_data->mflight_origin[1]]->airport.', '.$airports[$search_data->mflight_origin[1]]->country. ' ('.$search_data->mflight_origin[1].')'; ?>" />
												<input type="hidden" name="mflight_origin[]" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_origin[1]; ?>"/>
											</div>

											<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
												<!-- <label class="mod_label required"><?php echo $this->lang->line("to"); ?></label> -->
												<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" class="inputs_group mflight_destination" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $airports[$search_data->mflight_destination[1]]->city.', '.$airports[$search_data->mflight_destination[1]]->airport.', '.$airports[$search_data->mflight_destination[1]]->country. ' ('.$search_data->mflight_destination[1].')'; ?>" />
												<input type="hidden" name="mflight_destination[]" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_destination[1]; ?>">
											</div>

											<div class="col-xs-12 col-md-2 col-sm-2 mn_nopadding_right">
												<!-- <label class="mod_label required"><?php echo $this->lang->line("departure"); ?></label> -->
												<input type="text" autocomplete="off" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="mflight_departure1" name="mflight_departure[]" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" class="inputs_group1 from_date" value="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_departure[1]; ?>" data-fa-date="<?php echo $search_data->flight_type !== 'OpenJaw' ? '' :  date('d-m-Y',strtotime($search_data->mflight_departure[1])); ?>" />
											</div>

											<div class="col-md-2 col-xs-2 mefullwd nopadadd mleft">
												<!-- <label class="search_label">&nbsp;</label> -->
												<div class="addflight add_stop_points">
													<span class="fa">+</span>
												</div>
											</div>

										</div>
										<?php
											$length_idx = isset($search_data->mflight_origin) ? count($search_data->mflight_origin) : 0;
											for ($i = 2; $i < $length_idx; $i++)
											{ 
										?>
												<div class="more_stop_points" class="col-xs-12 nopadding">
													<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
														<!-- <label class="mod_label"><?php echo $this->lang->line("from"); ?></label> -->
														<?php
														echo "<input type='text' autocomplete='off' placeholder='".$this->lang->line('enter_city_or_airport')."' class='inputs_group mflight_origin'  value='".($search_data->flight_type !== 'OpenJaw' ? '' : $airports[$search_data->mflight_origin[$i]]->city.', '.$airports[$search_data->mflight_origin[$i]]->airport.', '.$airports[$search_data->mflight_origin[$i]]->country. ' ('.$search_data->mflight_origin[$i]).")' />";
														echo "<input type='hidden' name='mflight_origin[]' data-rule-required='true' data-msg-required='".$this->lang->line("required_star")."' value='".($search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_origin[$i])."' />";
														?>
													</div>
													<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
														<!-- <label class="mod_label"><?php echo $this->lang->line("to"); ?></label> -->
														<?php
														echo "<input type='text' autocomplete='off' placeholder='".$this->lang->line('enter_city_or_airport')."' class='inputs_group mflight_destination'  value='".($search_data->flight_type !== 'OpenJaw' ? '' : $airports[$search_data->mflight_destination[$i]]->city.', '.$airports[$search_data->mflight_destination[$i]]->airport.', '.$airports[$search_data->mflight_destination[$i]]->country. ' ('.$search_data->mflight_destination[$i]).")' />";
														echo "<input type='hidden' name='mflight_destination[]' name='mflight_origin[]' data-rule-required='true' data-msg-required='".$this->lang->line("required_star")."' value='".($search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_destination[$i])."' />";
														?>
													</div>
													<div class="col-xs-12 col-md-2 col-sm-2 mn_nopadding_right">
														<!-- <label class="mod_label"><?php echo $this->lang->line("departure"); ?></label> -->
														<?php
														echo "<input type='text' autocomplete='off' name='mflight_origin[]' data-rule-required='true' data-msg-required='".$this->lang->line("required_star")."' placeholder='".$this->lang->line("select_date")."' id='mflight_departure".$i."' name='mflight_departure[]' class='inputs_group1 from_date' autocomplete='OFF' value='".($search_data->flight_type !== 'OpenJaw' ? '' : $search_data->mflight_departure[$i])."' data-fa-date='".($search_data->flight_type !== 'OpenJaw' ? '' :  date('d-m-Y',strtotime($search_data->mflight_departure[$i])))."' />";
														?>
													</div>
													<div class="col-md-2 col-xs-2 mefullwd nopadadd mleft">
														<!-- <label class="search_label">&nbsp;</label> -->
														<div id="remScnt" class="drop_stop_points">
															<span class="fa">-</span>
														</div>
													</div>
													<div class="clearfix"></div>
												</div>
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 nopadding">
								<div class="col-xs-12 col-md-4 col-sm-4 mn_padding oneway_return_pax_summary" style="display:<?php echo $search_data->flight_type === MULTICITY ? 'none' : 'block'; ?>">
									<label class="mod_label"><?php echo $this->lang->line("passengers"); ?></label>
									<div class="inputs_group oneway_return_pax"><?php echo $search_data->flight_type === MULTICITY ? 1 : ($search_data->adult + $search_data->child + $search_data->infant); ?>
									</div>
								</div>

								<div class="col-xs-12 col-md-4 col-sm-4 mn_padding multi_city_pax_summary" style="display:<?php echo $search_data->flight_type !== 'OpenJaw' ? 'none' : 'block'; ?>">
									<label class="mod_label"><?php echo $this->lang->line("passengers"); ?></label>
									<div class="inputs_group multi_city_pax"><?php echo $search_data->flight_type === MULTICITY ? ($search_data->madult + $search_data->mchild + $search_data->minfant) : 1; ?>
									</div>
								</div>

								<div class="hide">
									<div class="oneway_return_pax_details">
										<div class="col-md-12 col-xs-12 nopadding mtop">
											<div class="col-md-4 col-xs-12 mn_nopadding_left">
												<label class="mod_label"><?php echo $this->lang->line("adults"); ?></label>
												<select name="adult" <?php echo $search_data->flight_type === MULTICITY ? "disabled" : ""; ?> class="inputs_group custom-select">
													<?php $selected = $search_data->flight_type === MULTICITY ? "1" : $search_data->adult;
													for ($i=1; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													}
													?>
												</select>
											</div>
											<div class="col-md-4 col-xs-12 nopadding">
												<label class="mod_label"><?php echo $this->lang->line("children"); ?></label>
												<select name="child" <?php echo $search_data->flight_type === MULTICITY ? "disabled" : ""; ?> class="inputs_group custom-select">
													<?php $selected = $search_data->flight_type === MULTICITY ? "0" : $search_data->child;
													for ($i=0; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													} ?>
												</select>
											</div>
											<div class="col-md-4 col-xs-12 mn_nopadding_right">
												<label class="mod_label"><?php echo $this->lang->line("infants"); ?></label>
												<select name="infant" <?php echo $search_data->flight_type === MULTICITY ? "disabled" : ""; ?> class="inputs_group custom-select">
													<?php $selected = $search_data->flight_type === MULTICITY ? "0" : $search_data->infant;
													for ($i=0; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													} ?>
												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="hide">
									<div class="multi_city_pax_details">
										<div class="col-md-12 col-xs-12 nopadding mtop">
											<div class="col-md-4 col-xs-12 mn_nopadding_left">
												<label class="mod_label"><?php echo $this->lang->line("adults"); ?></label>
												<select name="madult" <?php echo $search_data->flight_type !== MULTICITY ? "disabled" : ""; ?> class="inputs_group custom-select">
													<?php $selected = $search_data->flight_type !== "OpenJaw" ? "1" : $search_data->madult;
													for ($i=1; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													}
													?>
												</select>
											</div>

											<div class="col-md-4 col-xs-12 nopadding">
												<label class="mod_label"><?php echo $this->lang->line("children"); ?></label>
												<select name="mchild" <?php echo $search_data->flight_type !== MULTICITY ? "disabled" : ""; ?> class="inputs_group custom-select">
													<?php $selected = $search_data->flight_type !== "OpenJaw" ? "0" : $search_data->mchild;
													for ($i=0; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													} ?>
												</select>
											</div>

											<div class="col-md-4 col-xs-12 mn_nopadding_right">
												<label class="mod_label"><?php echo $this->lang->line("infants"); ?></label>
												<select name="minfant" <?php echo $search_data->flight_type !== MULTICITY ? "disabled" : ""; ?> class="inputs_group custom-select">
													<?php $selected = $search_data->flight_type !== "OpenJaw" ? "0" : $search_data->minfant;
													for ($i=0; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													} ?>
												</select>
											</div>
											
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding p_d15">
									<label class="mod_label"><?php echo $this->lang->line("class"); ?></label>
									<select name="class" class="custom-select inputs_group">
										<option <?php echo $search_data->class === "Y" ? "selected" : ""; ?> value="Y"><?php echo $this->lang->line("economy_class"); ?></option>
										<option <?php echo $search_data->class === "S" ? "selected" : ""; ?> value="S"><?php echo $this->lang->line("premium_economy_class"); ?></option>
										<option <?php echo $search_data->class === "C" ? "selected" : ""; ?> value="C"><?php echo $this->lang->line("business_class"); ?></option>
										<option <?php echo $search_data->class === "J" ? "selected" : ""; ?> value="J"><?php echo $this->lang->line("premium_business_class"); ?></option>
										<option <?php echo $search_data->class === "F" ? "selected" : ""; ?> value="F"><?php echo $this->lang->line("first_class"); ?></option>
										<option <?php echo $search_data->class === "P" ? "selected" : ""; ?> value="P"><?php echo $this->lang->line("premium_first_class"); ?></option>
									</select>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4 pull-right">
									<label class="mod_label">&nbsp;</label>
									<input type="submit" class="search_btn" value="<?php echo $this->lang->line("search"); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<?php

function fa_to_en_digits ($digit) {
    $en = Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $fa = Array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
    
    return str_replace($en, $fa, $digit);
}

function div($a,$b) { 
    return (int) ($a / $b); 
} 
 
function gregorian_to_jalali ($date, $str) 
{ 
    $date = explode("-",$date);
    $g_d = $date[0];
    $g_m = $date[1];
    $g_y = $date[2];
    
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29); 
 
  
   $gy = $g_y-1600; 
   $gm = $g_m-1; 
   $gd = $g_d-1; 
 
   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400); 
 
   for ($i=0; $i < $gm; ++$i) 
      $g_day_no += $g_days_in_month[$i]; 
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) 
      /* leap and after Feb */ 
      $g_day_no++; 
   $g_day_no += $gd; 
 
   $j_day_no = $g_day_no-79; 
 
   $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */ 
   $j_day_no = $j_day_no % 12053; 
 
   $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */ 
 
   $j_day_no %= 1461; 
 
   if ($j_day_no >= 366) { 
      $jy += div($j_day_no-1, 365); 
      $j_day_no = ($j_day_no-1)%365; 
   } 
 
   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) 
      $j_day_no -= $j_days_in_month[$i]; 
   $jm = $i+1; 
   $jd = $j_day_no+1; 
 if($str) return $jy.'/'.$jm.'/'.$jd ;
   return array($jy, $jm, $jd); 
}

?>