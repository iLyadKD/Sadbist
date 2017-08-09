<div id="show_search" class="modify_search_details modify_flight_search_details collapse in">
	<form method="post" id="domestic_flight_search" action="<?php echo base_url('flight/lists');?>">
		<div class="container nopadding">
			<div class="row">
				<div class="col-xs-12 nopadding">
				<div class="col-xs-12 modify_types">
				<div class="row">
               <div class="col-md-3 col-sm-3 col-xs-12 nopadding">
					<div class="way_type_buttons">
						<ul style="margin-top: 25px; float: left;">
							<li>
								<input type="radio" name="flight_type" data-id="OW" id="oneway" class="css-checkbox flight_type" value="OneWay" <?php echo $search_data->flight_type === ONEWAY ? "checked" : ""; ?>>
								<label for="oneway" class="css-label radGroup1" style="color: #333;"><?php echo $this->lang->line("one_way_caps"); ?></label>
							</li>
							<li>
								<input type="radio" name="flight_type" data-id="RT" id="roundtrip" class="css-checkbox flight_type" value="Return" <?php echo $search_data->flight_type === ROUNDTRIP ? "checked" : ""; ?>>
								<label for="roundtrip" class="css-label radGroup1" style="color: #333;"><?php echo $this->lang->line("round_trip_spl_caps"); ?>ROUNDTRIP</label>
							</li>
						</ul>
					</div>
					</div>
					
							<div class="col-md-5 col-sm-5 col-xs-12 nopadding">
								<div class="normalsearch_div">
									<div class="col-xs-12 nopadding">
										<div class="col-xs-6 col-md-6 col-sm-6 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("from"); ?></label>
											<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="flight_origin" class="dom_flight select2" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" id="dom_from_flight" hyperlink="<?php echo $search_data->flight_origin; ?>">
											</select>
										</div>
										<div class="col-xs-6 col-md-6 col-sm-6 mn_nopadding_right">
											<label class="mod_label required"><?php echo $this->lang->line("to"); ?></label>
											<select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="flight_destination" class="dom_flight select2" placeholder="<?php echo $this->lang->line("enter_city_or_airport"); ?>" id="dom_to_flight" hyperlink="<?php echo  $search_data->flight_destination; ?>">
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 nopadding">
								<div class="col-xs-3 col-md-3 col-sm-3 mn_padding oneway_return_pax_summary">
									<label class="mod_label"><?php echo $this->lang->line("passengers"); ?></label>
									<div class="inputs_group oneway_return_pax"><?php echo $search_data->adult + $search_data->child + $search_data->infant; ?>
									</div>
								</div>

								<div class="hide">
									<div class="oneway_return_pax_details">
										<div class="col-md-12 col-xs-12 nopadding mtop">
											<div class="col-md-4 col-xs-4 mn_nopadding_left">
												<label class="mod_label"><?php echo $this->lang->line("adults"); ?></label>
												<select name="adult" class="inputs_group custom-select">
													<?php $selected = $search_data->adult;
													for ($i=1; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													}
													?>
												</select>
											</div>
											<div class="col-md-4 col-xs-4 nopadding">
												<label class="mod_label"><?php echo $this->lang->line("children"); ?></label>
												<select name="child" class="inputs_group custom-select">
													<?php $selected = $search_data->child;
													for ($i=0; $i < 10; $i++)
													{
														$is_selected = $selected == $i ? "selected" : "";
														echo "<option value='$i' $is_selected >$i</option>";
													} ?>
												</select>
											</div>
											<div class="col-md-4 col-xs-4 mn_nopadding_right">
												<label class="mod_label"><?php echo $this->lang->line("infants"); ?></label>
												<select name="infant" class="inputs_group custom-select">
													<?php $selected = $search_data->infant;
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
								<div class="col-xs-4 col-md-3 col-sm-3 mn_nopadding_right">
									<label class="mod_label required"><?php echo $this->lang->line("departure"); ?></label>
									<input type="text" readonly="readonly" autocomplete="off" name="flight_departure" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="flight_departure" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" class="inputs_group cal_back from_date" value="<?php echo date("d-m-Y", strtotime($search_data->flight_departure)); ?>" data-fa-date="<?php echo $search_data->flight_departure; ?>" />
								</div>

								<div class="col-xs-4 col-md-3 col-sm-3 mn_nopadding_right returning_div" style="display:<?php echo $search_data->flight_type !== ROUNDTRIP ? 'none' : 'block'; ?>">
								<?php
									$secs = isset($search_data->flight_departure) ? strtotime($search_data->flight_departure." +1 days") : strtotime("+1 days");
									$arrv_dt = new DateTime(date("d-m-Y", $secs));
								?>
									<label class="mod_label required"><?php echo $this->lang->line("return"); ?></label>
									<input type="text" readonly="readonly" autocomplete="off" <?php echo $search_data->flight_type === "Return" ? "" : "disabled"; ?> name="flight_arrival" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-greater="#flight_departure" placeholder="<?php echo $this->lang->line("select_date"); ?>" id="to_date" class="inputs_group cal_back to_date" value="<?php echo (isset($search_data->flight_arrival) ? date("d-m-Y", strtotime($search_data->flight_arrival)) : $arrv_dt->format('d-m-Y')); ?>" data-fa-date="<?php echo (isset($search_data->flight_arrival) ? $search_data->flight_arrival : $arrv_dt->format('d-m-Y')); ?>" />
								</div>
								<div class="col-xs-4 col-sm-3 col-md-3 sch pull-right">
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