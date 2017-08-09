<div id="show_search" class="modify_search_details modify_hotel_search_details collapse in">
	<form method="post" id="hotel_search" action="<?php echo base_url('hotel/lists');?>">
		<div class="container nopadding">
			<div class="row">
				<div class="col-xs-12 nopadding">
					<div class="clearfix"></div>
					<div class="col-xs-12 modify_types">
						<div class="row">
							<div class="col-xs-12 mn_nopadding_left city_details">
								<div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
									<label class="mod_label"><?php echo $this->lang->line("search_by_city"); ?><input type="hidden" name="hotel_city" value="<?php echo $city->id; ?>" data-rule-required="true" data-msg-required="*" /></label>
									<input type="text" placeholder="<?php echo $this->lang->line("search_by_city"); ?>" class="mod_inputs_group hotel_city" value="<?php echo $city->keyword; ?>" />
								</div>
								<div class="col-xs-12 col-md-3 col-sm-4 mn_nopadding_right">
									<label class="mod_label"><?php echo $this->lang->line("hotel_search_check_in"); ?></label>
									<input type="text" data-rule-required="true" data-msg-required="*" name="check_in" placeholder="<?php echo $this->lang->line("check_in"); ?>" id="checkin_dt" class="mod_inputs_group cal_back from_date" autocomplete="OFF" value="<?php echo $search_data->check_in; ?>" data-fa-date="<?php echo $search_data->check_in; ?>" />
								</div>
								<div class="col-xs-12 col-md-3 col-sm-4 mn_nopadding_right">
									<label class="mod_label"><?php echo $this->lang->line("hotel_search_check_out"); ?></label>
									<input type="text" data-rule-required="true" data-msg-required="*" name="check_out" placeholder="<?php echo $this->lang->line("check_out"); ?>" id="checkout_dt" data-rule-greater="#checkin_dt" class="mod_inputs_group cal_back to_date" autocomplete="OFF" value="<?php echo $search_data->check_out; ?>" data-fa-date="<?php echo $search_data->check_out; ?>" />
								</div>
								<div class="col-xs-12 col-md-2 col-sm-3 mn_nopadding_right">
									<label class="mod_label"><?php echo $this->lang->line("hotel_search_rooms_colan"); ?></label>
									<select name="rooms" data-last_selected="<?php echo $search_data->rooms; ?>" class="custom-select mod_inputs_group total_rooms">
                                     <?php
                                        for ($i = 1; $i < 4; $i++)
                                           echo "<option ".($i == $search_data->rooms ? "selected" : "")." value='$i'>$i</option>";
                                     ?>
                                  	</select>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="mn-margin"></div>
							<div class="col-md-12 col-sm-12 col-xs-12 mn_nopadding_left">
								<div class="rooms_details">
									<?php for($i = 0; $i < $search_data->rooms; $i++)
										{
									?>
									<div class="room_details">
	                                    <div class="col-md-4 col-sm-4 col-xs-4 person_details nopadding_right">
	                                       <div class="col-md-4 col-sm-4 col-xs-12 nopadding_left inlabel rightpadding mnopadding_right">
	                                          <label class="search_label">&nbsp;</label>
	                                          <div class="clearfix"></div>
	                                          <span>Room <?php echo $i + 1; ?></span>
	                                       </div>
	                                       <div class="col-md-4 col-sm-4 col-xs-12 nopadding inlabel no_left ma_top nopadding_right margintop mnopadding_right">
	                                          <label class="search_label"><?php echo $this->lang->line("adult"); ?></label>
	                                          <div class="clearfix"></div>
	                                          <div class="inputtext">
	                                             <div class="roomblock">
	                                                <div class="roomdetails">
	                                                   <div class="dropdown">
	                                                      <select name="adult[]" id="adult_<?php echo $i + 1; ?>" class="dropdown-select">
	                                                         <?php
	                                                         	$selected = isset($search_data->adult[$i]) ? $search_data->adult[$i] : 1;
	                                                            for ($j = 1; $j < 5; $j++)
	                                                               echo "<option ".($j == $selected ? "selected" : "")." value='$j'>$j</option>";
	                                                         ?>
	                                                      </select>
	                                                   </div>
	                                                </div>
	                                             </div>
	                                          </div>
	                                       </div>
	                                       <div class="col-md-4 col-sm-4 col-xs-12 nopadding_right inlabel no_left ma_top nopadding_right margintop mnopadding_left">
	                                          <label class="search_label"><?php echo $this->lang->line("child"); ?></label>
	                                          <div class="clearfix"></div>
	                                          <div class="inputtext">
	                                             <div class="roomblock">
	                                                <div class="roomdetails">
	                                                   <div class="dropdown">
	                                                      <select name="children[]" id="children_<?php echo $i; ?>" class="dropdown-select room_children"   data-last_selected="<?php echo isset($search_data->children[$i]) ? $search_data->children[$i] : 0; ?>">
	                                                         <option value="0"><?php echo $this->lang->line("select"); ?></option>
	                                                         <?php
	                                                         	$selected = isset($search_data->children[$i]) ? $search_data->children[$i] : 0;
	                                                            for ($j = 1; $j < 4; $j++)
	                                                               echo "<option ".($j == $selected ? "selected" : "")." value='$j'>$j</option>";
	                                                         ?>
	                                                      </select>
	                                                   </div>
	                                                </div>
	                                             </div>
	                                          </div>
	                                       </div>
	                                    </div>
	                                    <div class="col-md-3 col-sm-3 col-xs-3 nopadding children_age">
	                                    	<?php
	                                    		$is_child_exist = isset($search_data->children[$i]) ? $search_data->children[$i] : 0;
	                                    		for($j = 0; $j < $is_child_exist && $j < 4; $j++)
	                                    		{
	                                    			$selected = isset($search_data->child_age[$i][$j]) ? $search_data->child_age[$i][$j] : 1;
		                                    		$child_age_opt = "";
		                                    		if($is_child_exist > 0)
		                                    			for ($k = 1; $k < 13; $k++)
															$child_age_opt .= "<option ".($selected == $k ? "selected" : "")." value='".$k."'>".$k."</option>\n";
	                                    			echo "<div class=\"col-md-4 col-sm-4 col-xs-12 nopadding_right inlabel no_left ma_top nopadding_right margintop mnopadding_left\">\n";
													echo "<label class=\"search_label\">child age ".(($j * 1) + 1)."</label>\n";
													echo "<div class=\"clearfix\"></div>\n";
													echo "<div class=\"inputtext\">\n";
													echo "<div class=\"roomblock\">\n";
													echo "<div class=\"roomdetails\">\n";
													echo "<div class=\"dropdown\">\n";
													echo "<select name=\"child_age[".$i."][]\" class=\"dropdown-select\">\n";
													echo "<option value=\"0\">select</option>\n";
													echo $child_age_opt;
								                    echo "</select>\n</div>\n</div>\n</div>\n</div>\n</div>";
	                                    		}

	                                    	?>
	                                    </div>
	                                    <div class="clearfix"></div>
	                                </div>
	                                <?php
	                                	}
	                                ?>
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 mn_nopadding_right pull-right">
									<label class="mod_label">&nbsp;</label>
									<input type="submit" class="search_btn" value="<?php echo $this->lang->line("hotel_search_search"); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
<div class="clearfix"></div>