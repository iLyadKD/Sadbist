<?php
      $include["css"][] = "persian_fonts";
      $include["css"][] = "persian_calendar";
	   $include["css"][] = "select2/select2";
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
            <div class="col-md-9 col-sm-9 col-xs-9 nopadding_right city_title">
				  <h4 ><span class="package count" ></span>  <span class="mn_span"><?php echo $keyword_to;?> </span></h4>
            </div>
            
            <div id="click_modify"  class="col-md-3 col-sm-3 col-xs-3"> <a class="modify_search_btn" style="margin: 9px 0px;" data-target="#show_search" data-toggle="collapse"  href="#"><?php echo $this->lang->line("modify"); ?></a></div>
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
                      
					 <!-- <input type="text" class="inputs_group" name="mfromcity"  data-msg-required="<?php echo $this->lang->line("from_city_required"); ?>" data-rule-required="true"  id="fromcity" value="<?php echo $keyword_from;?>">-->
					  <select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="mfromcity" class="tour_fromcity select2" placeholder="<?php echo $this->lang->line("from_city"); ?>" id="fromcity" hyperlink="<?php echo $fromcity;?>">
					  
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
						<!--<input type="hidden" name='location_info' value='<?php echo $location_info;?>'>-->
						<input type="hidden" name="arr_city" data-rule-required="true" id="arr_city" value='<?php echo json_decode($arr_city);?>'>
                        
                    
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12 nopadding_right">
                    <label class="search_label"><?php echo $this->lang->line("to_city"); ?></label>
                    <div class="clearfix"></div>
                    <div class="inputtext">
                     
					  <select  style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star");	?>" name="mtocity" class="tour_tocity select2" placeholder="<?php echo $this->lang->line("to_city"); ?>" id="tocity" hyperlink="<?php echo $tocity;?>">
					  <input type="hidden" id="hid_tocity" value="<?php echo $tocity;?>">
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12 nopadding_right">
                    <label class="search_label"><?php echo $this->lang->line("departure_date"); ?></label>
                    <div class="clearfix"></div>
                    <div class="inputtext">
                      <input type="text" data-msg-required="<?php echo $this->lang->line("check_in_required"); ?>" data-rule-required="true" name="mcheckin"  value="<?php echo $checkin;?>" id="tour_checkin" class="inputs_group1 from_date">
					  
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
                <!--     <label class="mod_label">&nbsp;</label> -->
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