<?php
      $include["css"][] = "persian_fonts";
      $include["css"][] = "persian_calendar";
      $this->load->view("common/head", $include);
      ?>
<style>
	.modify_search{
		z-index:99 !important;
	}
</style>

<?php
	$iod = $this->session->userdata('way');
	$tocity = $this->session->userdata('tocity');
    $keyword_to = $this->session->userdata('keyword_to');
    $fromcity = $this->session->userdata('fromcity');
    $keyword_from = $this->session->userdata('keyword_from');
	//$code = $this->session->userdata('code');
	//$package_type = $this->session->userdata('package_type');pr();
	$checkin = $this->session->userdata('checkin');
    $country_code = $departure = $this->session->userdata('country_code');
	$nights = $this->session->userdata('nights');
?>

<div class="modify_search_details result_bg">
    <div class="container nopadding">
      <div class="row">
        <div class="col-xs-12 nopadding">
          <div class="sortblock">
            <div class="col-md-9 nopadding_right city_title">
                    <h4 ><span class="package count" ></span>  <span class="mn_span"><?php echo $keyword_to;?> </span></h4>
                    </div>
            <!-- <div class="col-md-3 col-sm-4 col-xs-12 border-rt">
              <div class="checkin2"> <i class="fa fa-map-marker"></i> <span> <span class="loc1"><?php echo $this->lang->line("tour_search_destination_colan"); ?></span>
                <div class="clearfix"></div>
                </span> 
                <span class="loc1"><h5 class="dep_date"><?php echo $keyword_to?> </h5></span></div>
            </div> -->
            <!-- <div class="col-md-3 col-sm-3 col-xs-12 border-rt">
              <div class="checkin2"> <i class="fa fa-calendar"></i> <span> <span class="loc1"><?php echo $this->lang->line("tour_search_departure_date_colan"); ?></span>
                <div class="clearfix"></div>
                </span> 
<span class="loc1">
                <h5 class="dep_date">24/08/2016  <span>31/08/2016</span></h5>
                </span> 
                </div>
            </div> -->
            <!-- <?php echo date("jS F, Y", strtotime($checkin));?> -->
            <!-- <div class="col-md-3 col-sm-3 col-xs-12 border-rt">
              <div class="checkin2"> <i class="fa fa-moon-o"></i> <span> <span class="loc1"><?php echo $this->lang->line("tour_search_no_of_nights"); ?></span>
                <div class="clearfix"></div>
                </span> 
               <span class="loc1"><h5 class="dep_date"><?php echo $nights;?> </h5></span>
                </div>
            </div> -->
            <div id="click_modify" class="col-md-3 col-sm-3 col-xs-6"> <a class="modify_search_btn" style="margin: 9px 0px;" data-target="#show_search" data-toggle="collapse" href="#"><?php echo $this->lang->line("tour_search_modify_search"); ?></a></div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    
    
<div id="show_search" class="modify_search collapse in" style="height: auto;display: none;">
      <div class="container nopadding">
        <div class="row">
          <div class="col-xs-12 nopadding">
            <div class="clearfix"></div>
            <div class="col-xs-12 modify_types">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 mn_nopadding_left">
                  <form id="modify_package"  method="get" action="<?php echo base_url('tour/search');?>" name="modify_package" >
                  <div class="col-md-3 col-sm-6 col-xs-12 nopadding_right">
                    <label class="search_label"><?php echo $this->lang->line("from_city"); ?></label>
                    <div class="clearfix"></div>
                    <div class="inputtext">
                      <input type="text" class="inputs_group" name="mfromcity"  data-msg-required="<?php echo $this->lang->line("from_city_error"); ?>" data-rule-required="true"  id="fromcity" value="<?php echo $keyword_from;?>">
                      <input type="hidden" id="from_hid" name="from_hid">
					  <input type="hidden" id="to_hid" name="to_hid">
					  <input type="hidden" id="ready_flag" name="ready_flag">
                        <input type="hidden" name="fromcity" id="from" value="<?php echo $fromcity;?>">
                        <input type="hidden" name="tocity" id="to" value="<?php echo $tocity;?>">
                        <input type="hidden" name="checkin" id="departure" value="<?php echo $checkin;?>">
                        <input type="hidden" name="iod" id="iod" value="<?php echo $iod;?>">
                        <input type="hidden" name="country_code" id="country_code" value="<?php echo $country_code;?>">
						<input type="hidden" name="search_type"  value="modify">
						<input type="hidden" name="nights" id="nights" value="<?php echo $nights;?>">
						<input id="page" name="page" type="hidden" value="1" />
						<input type="hidden" name="special_offer" id="sp_ofr" value="0">
						 <input type="hidden" id="id_from_city" name="id_from_city">
                        <input type="hidden" id="id_to_city" name="id_to_city">
                        
                    
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12 nopadding_right">
                    <label class="search_label"><?php echo $this->lang->line("to_city"); ?></label>
                    <div class="clearfix"></div>
                    <div class="inputtext">
                      <input type="text"  id="tocity" data-msg-required="<?php echo $this->lang->line("to_city_error"); ?>" data-rule-required="true" name="mtocity" class="inputs_group" value="<?php echo $keyword_to;?>">
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12 nopadding_right">
                    <label class="search_label"><?php echo $this->lang->line("departure_date"); ?></label>
                    <div class="clearfix"></div>
                    <div class="inputtext">
                      <input type="text" data-msg-required="<?php echo $this->lang->line("check_in_error"); ?>" data-rule-required="true" name="mcheckin"  value="<?php echo $checkin;?>" id="tour_checkin" class="inputs_group1 from_date">
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12 nopadding_right">
                    <label class="search_label"><?php echo $this->lang->line("night_count"); ?></label>
                    <div class="clearfix"></div>
                    <div class="inputtext">
                      <div class="roomblock">
                        <div class="roomdetails">
                          <div class="dropdown">
                            <select class="dropdown-select" name="nights">
                             <option value=""><?php echo $this->lang->line("select"); ?></option>
										<?php  for ($i=1; $i <=12; $i++) { 
											$selected = '';
											if($nights == $i) $selected = 'selected';
											echo '<option value="'.$i.'" '.$selected.'>'.$i.' '.$this->lang->line("no_of_days").'</option>';
										}?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-3 col-md-2 mn_nopadding_right pull-right">
                    <label class="mod_label">&nbsp;</label>
                    <input type="submit" class="search_btn" value="<?php echo $this->lang->line("search_package"); ?>">
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="clearfix"></div>
  </div>