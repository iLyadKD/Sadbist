<!DOCTYPE html>
<html class="no-js">   
<?php
  $include["css"][] = "backslider";
  $include["css"][] = "owl.theme";
  $include["css"][] = "owl.carousel";
  $include["css"][] = "jquery.bxslider";
  $include["css"][] = "ion.rangeSlider";
  $include["css"][] = "social_media_share";
  $this->load->view("common/head", $include);
  if($this->data['default_language'] == 'fa') {
	$fa_content = 'fa_content';
	$standard = 'استاندارد';
  }else {
	$fa_content = '';
	$standard = 'Standard';
  }
  ?>

<body>
<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>-->
<style>
.mn_star {
	margin:13px 0 4px;
	text-align:center
}
.nav > li > a:hover, .nav > li > a:focus{
	color:#555;
}
.table-bordered1 > thead > tr > th, .table-bordered1 > tbody > tr > th, .table-bordered1 > tfoot > tr > th, .table-bordered1 > thead > tr > td, .table-bordered1 > tbody > tr > td, .table-bordered1 > tfoot > tr > td {
	border:none
}
.mn_night {
	color: #ec4614 !important;
	display: inline-block !important;
	font-size: 11px !important;
}
.mn_night_spcl {
    color: #ec4614 !important;
    display: inline-block !important;
    font-size: 11px !important;
    position: relative;
    bottom: 19px;
    left: 91px;
}

.col-xs-12.nopadding.flight_check {
	margin: 2px 0 0;
}
.hotel_rate {
	color:#333;
}
.cta, a.cta, .cta:link, .cta:visited {
	    width: 50%;
    margin: 0 5px 15px 5px;
    box-shadow: inset 0px 0px 10px 4px #4bad4e;
    background: rgb(21, 144, 26);
    padding: 5px 0px;
    line-height: normal;
    border-radius: 5px;
}

.cta1, a.cta1, .cta1:link, .cta1:visited {
      width: 100%;
    margin: 10px 0px;
    box-shadow: inset 0px 0px 10px 4px #027707;
    background: rgb(21, 144, 26); border:none; color:#fff;
    padding: 7px 0px; font-size: 12px;
    line-height: normal;
    border-radius: 5px;
}
.mediashare{
  padding: 7px 37px;
 
}

#listings .description {
	width: 100%;
	background-color: transparent;
}
.border {
	border: 1px solid #ddd;
	float: left;
	margin: 0;
	padding: 10px;
}
.mn_flight_section {
	border: 1px solid #ddd;
	float: left;
	margin: 10px 0;
	padding: 0 0px;
	width: 100%;
}
.mn_iternary h3 {
	float:none !important;
	padding:0 !important
}
.flight-list-v2 h5 {
	color:#3d6cc6;
	font-size:18px;
}
.table {
	margin-bottom: 0px;
}
.mn_top {
	margin:5px 0 0 0;
}
.mn_tabel {
	width:100%;
	float:left;
}
#flight_result h2 small {
	color:#ec4614
}
#flight_result h3 {
	margin-top: 8px;
	padding:0 0 0 0px;
	font-size:16px;
	margin:0
}
.mn_border_top_none {
	border:solid 1px #ddd;
}
.check_box {
	float:left;
	width:auto;
	margin: 0px 10px 0 0;
}
.check_box label {
	position: relative;
}
.h-listing {
	border: 1px solid #ddd;
	padding: 10px;
	background: #fff;
	box-shadow: 0px 0px 1px 0px #ccc;
}
#listings .pricing {
	background-color: transparent;
}
.nav-tabs > li > a, .nav-tabs > li > a:hover {
	 background: #f5f5f5;
    color: #757373;     border-radius: 4px 24px 0 0; padding: 6px 20px;
    font-size: 12px; border:1px solid #ababab; font-weight: 500;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    color: #fff; font-weight: 500;
    cursor: default;
    background-color: #9eb188;
    border: 1px solid #6a886b;
    border-bottom-color: transparent;
}

.filtericon {
	background: #808080 url("images/filter.png") no-repeat scroll center top 10px;
	border: 1px solid #fff;
	height: 40px;
	overflow: hidden;
	width: 40px;
	z-index: 100;
	margin:18px 13px
}
.modal-header {
	background-color:#F35D30;
}
.modal-title {
	color: #fff;
}
.filter_block {
	background: #fff;
}
.filters {
	position: absolute;
	z-index: 9;
}
.sorting2 {
	float: left;
	padding: 0px 0 0;
	width: 100%;
}
.sorting2 ul {
	margin: 0;
}
.sorting2 ul li {
	color: #333;
	float: left;
	font-family: opensansregular;
	font-size: 10px;
	margin: 0 5px 0 0;
	background: #696969;
	padding: 5px 5px;
	border-radius:5px;
}
.sorting2 ul li a {
	background-color: transparent;
	color: #fff;
	cursor: pointer;
	display: inline-block;
	font-family: "Roboto", sans-serif;
	font-size: 12px;
	font-weight: normal;
	padding: 0px 0px;
	text-decoration: none;
	text-transform: uppercase;
}
.sorting2 ul li a span {
	background: rgba(0, 0, 0, 0) url("images/sorting_arrow.png") no-repeat scroll center center;
	display: inline-block;
	height: 15px;
	margin-right: 7px;
	margin-left:0;
	position: relative;
	width: 17px;
}
.modify_search_btn {
	margin: 18px 0;
}
.border-rt {
	padding:0 15px 0px 15px;
}
.checkin2 {
	float:none
}
.sorting2 ul li {
	width:31%;
	text-align:center
}
.flight-list-v2 {
	background: #f5f5f5;
	border: medium none;
	box-shadow: none;
	margin:0px 0px 0;
	padding:0px 0 0;
}
.modify_search_btn {
	margin:5px 0;
	width:auto;
}

.extra_msg {
	color: #ff0000 !important;
    font-size: 11px !important;
    margin-top: 11px
}

.mn_facilities .fa {
    display: inline;margin-right:5px;}
	
  .radio_span {
	position:relative;top:3px;left:4px;
  }
  
  .small_fnt {
	font-size: 13px;
  }
  
  .bosx_info{
	position: fixed;
	z-index: 9;
	background: #ddd none repeat scroll 0 0;
  }
  
  .checkin2 a {
    color: #777;
    float: left;
    font-family: "Roboto",sans-serif;
    font-size: 15px;
    line-height: 20px;
    margin-left: 10px;
}
#box_info_1 {
  background:  #f5f5f5;
  z-index: 9 !important;
    }
	
	 body {
  min-height: 1100px;
}

.stylish_radio[type=radio] {
    display:none;
}
 
.stylish_radio[type=radio] + label {
    display:inline-block;
    padding: 4px 12px;
   
    font-size: 10px;
    line-height: 6px;
    color: #333;
    text-align: center;
    text-shadow: 0 1px 1px rgba(255,255,255,0.75);
    vertical-align: middle;
    cursor: pointer;
    background-color: #f5f5f5;
    background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
    background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
    background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
    background-image: -o-linear-gradient(top,#fff,#e6e6e6);
    background-image: linear-gradient(to bottom,#fff,#e6e6e6);
    background-repeat: repeat-x;
    border: 1px solid #ccc;
    border-color: #e6e6e6 #e6e6e6 #bfbfbf;
    border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
    border-bottom-color: #b3b3b3;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
}
 
.stylish_radio[type=radio]:checked + label {
       background-image: none;
    outline: 0;
    -webkit-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
    -moz-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
        background-color:#e0e0e0;
}

.morectnt span {
  display: none;
}
.check_box label:after {
	opacity: 0;
    content: '';
    position: absolute;
    width: 9px;
    height: 5px;
    background: transparent;
    top: 4px;
    left: 3px;
    border: 3px solid #222;
    border-top: none;
    border-right: none;
    transform: rotate(-45deg);
  
}

.flight_row {
  padding-right: 10px;
  padding-left: 10px; width: 100%; display: block; background: #f39072;
    padding: 2px 10px; color: #000;
}
.flight_row input {
  cursor: pointer;
}

.trans_nfo {
  position: relative; padding:10px;
}

.title_trans h4 {
  font-size: 14px;
  position: relative; color: #000;
}

.ex-night {
    
    padding-top: 1px !important;
	width: 30% !important;
	float: left !important;
	cursor: pointer;
}
.pdflink {
    background-image: url('<?php echo base_url('assets/images/icon_pdf.png');?>');
	background-position: 100% 1px;
    background-repeat: no-repeat;
    padding-right: 20px;
    position: relative;
    top: 9px;
    left: 60px;
}

.div_gallery {
  cursor: move; position: absolute; left: 0px; top: 0px; width: 720px; height: 460px;
  overflow: hidden;
}

.mn-name h2{
  margin: 5px 0 0 28px !important;
}


.amount_div {
  border: 1px solid #ddd;
  padding: 10px;
  float: right;
  font-weight: bold;
}

.img-circle {
    position: relative;
    bottom: 2px !important;
}


@media (max-width: 1200px) {

    .mediashare{
 padding: 7px 28px;
 width: 100%;
    }

}

@media (max-width: 991px) {
  
  .mediashare{
 padding: 7px 77px;
 width: 100%;
    }
}

@media (max-width: 768px) {
  .mediashare{
 padding: 7px 67px;
 width: 100%;
    }
}
  
  @media (max-width: 767px) {
  .mediashare{
 padding: 7px 61px;
 width: 100%;
    }
}
 @media (max-width: 650px) {
  .mediashare{
 padding: 7px 48px;
 width: 100%;
    }
}
@media (max-width: 550px) {
  .mediashare{
 padding: 7px 41px;
 width: 100%;
    }
}

</style>
<div id="wrapper">
  <?php
      $this->load->view("common/header");
      $this->load->view("common/notification");
  ?>
  <div class="flight_block_modify result_bg">
    <div class="container nopadding">
      <div class="row">
        <div class="col-xs-12 nopadding">
          <div class="sortblock">
            <div class="col-md-3 col-sm-6 col-xs-6  border-rt">
              
                <?php
				$curr = $this->data['default_currency'];
				if($curr == 'IRR') $curr= $this->lang->line("irr_caps"); else $curr = $this->lang->line("usd_caps");
				
				$lang = DEFAULT_LANG;
				if($gen_info->no_of_night > 1)
				  $n_str = $this->lang->line("nights");
				else
				  $n_str = $this->lang->line("night");
				  
				if($gen_info->no_of_day > 1)
				  $d_str = $this->lang->line("days");
				else
				  $d_str = $this->lang->line("day");
			  ?>

        <?php
        if($hotel_price->rating == 6)
          $star = 'star_rating_6.png';
        if($hotel_price->rating == 2)
          $star = 'star_rating_2.png';
        if($hotel_price->rating == 3)
          $star = 'star_rating_3.png';
        if($hotel_price->rating == 4)
          $star = 'star_rating_4.png';
        if($hotel_price->rating == 5)
          $star = 'star_rating_5.png';
        ?>
                <!-- <?php echo $gen_info->tour_name;?>, <?php echo $gen_info->no_of_night.'&nbsp;Night'.$n_str;?> <?php echo $gen_info->no_of_day.'&nbsp;Day'.$d_str;?> :-->
                <div class="mn-name">
                              <h2><?php echo $gen_info->tour_name;?> </h2>
                              <h3><img src="<?php echo base_url('assets/images/'.$star.'');?>" alt="" style="padding-left: 28px;"/></h3>
                </div>
            </div>

            <div class="col-md-3 col-sm-6  col-xs-6  border-rt">
              <div class="checkin3"> 
              <span class="<?php echo $fa_content;?> nightsdays"><small class="nights"><?php echo $gen_info->no_of_night.'&nbsp;'.$n_str;?></small> <small class="days"><?php echo $gen_info->no_of_day.'&nbsp;'.$d_str;?></small> 
              </span> 
              <?php
				$tour_duration = explode("-",$duration);
				$start = $tour_duration[0];
				$end = $tour_duration[1];
			  ?>
			  <div class="clearfix"></div>
              <div class="col-md-5 col-sm-5 col-xs-5 nopadding floatright"><h3><?php echo $start;?></h3> </div>
              <div class="col-md-2 col-sm-2 col-xs-2 nopadding"><i class="fa fa-long-arrow-right hiddenlang"></i> <i class="fa fa-long-arrow-left hiddeneng"></i></div>
              <div class="col-md-5 col-sm-5 col-xs-5 nopadding floatleft"><h4><?php echo $end;?></h4></div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-6 border-rt">
              
              <div class="mn_loc"><h5><?php echo $tour_to->text;?></h5></div>
            </div>

            

            <div class="col-md-3 col-sm-6 col-xs-6"> 
             <div class="mod_price"><?php echo $this->lang->line("base"); ?> <span class="u_price"><span class="money"><?php echo round($base_price/$currency,2);?></span> <?php echo $curr;?></span></div>
             <div class="mod_price"><?php echo $this->lang->line("discount"); ?> <span class="n_price"><span class="money"><?php echo round($total_tour_price/$currency,2);?></span> <?php echo $curr;?></span></div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
  <div class="middle_content">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 nopadding">
          <div id="flight_result">
            <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
              <div class="col-md-12 nopadding" id="photos">
                <div class="col-md-7 col-sm-12 col-xs-12 nopadding floatright">
                  <div class="col-md-12 nopadding">
                    <div id="slider1_container" style="position: relative; width: 720px;
        height: 460px; overflow: hidden;">
                      <div u="slides" class="div_gallery">
                        <?php if(!empty($galleries)) { ?>
                        <?php foreach($galleries as $gallery){ ?>
                        <div> <img u="image" src="<?php echo upload_url('hotel/gallery/'.$gallery['gallery_name']);?>" /> <img u="thumb" src="<?php echo upload_url('hotel/gallery/'.$gallery['gallery_name']);?>" /> </div>
                        <?php } ?>
                        <?php } ?>
                      </div>
                      
                      <!-- thumbnail navigator container -->
                      <div u="thumbnavigator" class="jssort07" style="width: 720px; height: 75px; left: 0px; bottom: 0px;"> 
                        <!-- Thumbnail Item Skin Begin -->
                        <div u="slides" style="cursor: default;">
                          <div u="prototype" class="p">
                            <div u="thumbnailtemplate" class="i"></div>
                            <div class="o"></div>
                          </div>
                        </div>
                        <span u="arrowleft" class="jssora11l" style="top: 123px; left: 8px;"> </span> 
                        <!-- Arrow Right --> 
                        <span u="arrowright" class="jssora11r" style="top: 123px; right: 8px;"> </span> 
                        <!--#endregion Arrow Navigator Skin End --> 
                      </div>
                      <!-- Trigger --> 
                    </div>
                  </div>
                </div>
                <div class="col-md-5 col-sm-12 col-xs-12 nopadding floatleft">
                  <div class="flight-list-v2">
                    
                    <div class="clearfix"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 nopadding">

                      <input type="hidden" id="latitude"  value="<?php echo $hotel_price->latitude?>" >
                      <input type="hidden" id="longitude"  value=" <?php echo $hotel_price->longitude?>" >
                      <div style="width:450px;height: 343px;margin-left: 20px;" id="hotel_map">
                        
                      </div>
                      <div class="hotel-list-view1">
                      <div class="box_info" id="box_info_1">
                        <div class="col-md-12 nopadding">
                          <div class="col-md-12 mn_top_packge">
                            <h2 class="tour_rate" id="hotel_rate">
                              <input type="hidden" id="fixed_price" value="<?php echo round($total_tour_price/$currency,2);?>">
                              <input type="hidden" id="total_nights" value="<?php echo $gen_info->no_of_night;?>">
                              <input type="hidden" id="transportation_cost" value="0">
                              <input type="hidden" id="handle_charge" value="<?php echo round($hotel_price->handle_charge/$currency,2);?>">
                              <input type="hidden" id="overall_tour_price" value="<?php echo round($tour_price->total_tour_price/$currency,2);?>">
                              <input type="hidden" id="extra_cost" value="0">
                              <input type="hidden" id="currency" value="<?php echo $currency;?>">
                              <input type="hidden" id="transport_flag" value=0>
                              <?php echo $this->lang->line('total'); ?> :<span class="money" id="changeval">0</span><?php echo ' '.$curr;?></h2>
                          </div>
                        </div>
                        <div class="col-md-12 col-sm-6 pull-right mn_top_packge">
                          <form name="book_now" id="book_now" method="post" action="<?php echo base_url('tour/prebooking')?>">
                            <div class="col-md-12 prebook_data"> 
                              <!--<a href="#" class="cta">BOOK NOW</a>-->
                              
                              <input  type="hidden"  name="id_hotel" id="id_hotel" value="<?php echo $selected_hotel;?>">
                              <input  type="hidden" value='0'  name="count_room_single" id="count_room_single">
                              <input  type="hidden" value='0'  name="count_room_double" id="count_room_double">
                              <input  type="hidden" value='0'  name="count_room_triple" id="count_room_triple">
                              <input  type="hidden" value='0'  name="infants" id="infants">
                              <input  type="hidden" value='0'  name="twotosix" id="twotosix">
                              <input  type="hidden" value='0'  name="sixtotwelve" id="sixtotwelve">
                              <input  type="hidden" value='0'  name="twelvetosixteenth" id="twelvetosixteenth">
                              <input  type="hidden" value='<?php echo $tour_id;?>'  name="id_tour" id="id_tour">
                              <input  type="hidden" value="<?php echo round($total_tour_price/$currency,2);?>"  name="price_tour" id="price_tour">
                              <input  type="hidden" value="0"  name="cost_transportation" id="cost_transportation">
                              <input  type="hidden"   name="tour_date" value="<?php echo date('Y-m-d',strtotime($tour_date));?>">
                              <input  type="hidden"   name="tour_date_end" value="<?php echo date('Y-m-d', strtotime($tour_date. ' + '.($gen_info->no_of_night+1).' days'));?>">
							  <input  type="hidden"   name="trans_param" id="trans_param">
							  <input  type="hidden"   name="departures" id="departures">
							  <input  type="hidden"   name="short_info" value="<?php echo $short_info;?>">
							  <input  type="hidden"   name="tour_from" value="<?php echo $tour_from->text;?>">
							  <input  type="hidden"   name="tour_to" value="<?php echo $tour_to->text;?>">
                              <input style="display: none;"  type="submit" class="cta" value="<?php echo $this->lang->line('book_now'); ?>">
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                  <?php
					if($gen_info->tour_file != '')
					  $brochure_file = $gen_info->tour_file;
					else
					  $brochure_file = '';
				  ?>
				  <div class="col-md-3 col-sm-3 col-xs-3 nopadding_right">
					<?php if($brochure_file != '') { ?>
					<input  type="button" onClick="window.open('<?php echo upload_url('tour/'.$brochure_file);?>');"class="cta1" value="<?php echo $this->lang->line('brochure'); ?>" style="margin-top: 4px;">
					<?php } ?>
					<span class="cta1 mediashare"><?php echo $this->lang->line('share'); ?></span>
					<!--<input  type="button" class="cta1" value="<?php echo $this->lang->line('city_info'); ?>">-->
                  </div>

                  <div class="col-md-9 col-sm-9 col-xs-9 nopadding_right">
                    <div class="need_assist">
                    <?php echo $this->lang->line('need_assistance'); ?>
                    <br><?php echo $this->lang->line('call_us'); ?> <span><?php echo $contact_address !== false ? $contact_address->contact : $this->lang->line("na_caps"); ?></span>
                    </div>
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8 nopadding">
                  </div>
                </div>

              </div>
              <div class="clearfix"></div>
            </div>
            <div class="fix"> 
              <div class="panel with-nav-tabs mn_top_input">
                <div class="mn_background">
                  <?php
				  $itinerary = '';
				  $inclusions = '';
				  $exclusions = '';
				  $terms_conditions = '';
				  $cancellation_policy = '';
				  $privacy_policy = '';
				  if($gen_info->text_contents != ''){
					$text_contents = json_decode($gen_info->text_contents);
					if($this->data['default_language'] == 'en'){
					  $itinerary = $text_contents->itinerary_en;
					  $inclusions = $text_contents->inclusions_en;
					  $exclusions = $text_contents->exclusions_en;
					  $terms_conditions = $text_contents->terms_conditions_en;
					  $cancellation_policy = $text_contents->cancellation_policy_en;
					  $privacy_policy = $text_contents->privacy_policy_en;
					}else{
					  $itinerary = $text_contents->itinerary_fa;
					  $inclusions = $text_contents->inclusions_fa;
					  $exclusions = $text_contents->exclusions_fa;
					  $terms_conditions = $text_contents->terms_conditions_fa;
					  $cancellation_policy = $text_contents->cancellation_policy_fa;
					  $privacy_policy = $text_contents->privacy_policy_fa;
					}
					
				  }
				?>
	  <ul class="nav nav-tabs">
	  <?php if($hotel_details['description'] != '') { ?>
		<li class="active"><a data-toggle="tab" href="#tab1primary"><?php echo $this->lang->line('description'); ?></a></li>
	  <?php } ?>
	  <?php if($itinerary != '') { ?>
		<li><a data-toggle="tab" href="#tabdec"><?php echo $this->lang->line('itinerary'); ?></a></li>
	  <?php } ?>
	  <?php if($inclusions != '') { ?>
		<li><a data-toggle="tab" href="#incl"><?php echo $this->lang->line('inclusions'); ?></a></li>
	  <?php } ?>
	  <?php if($exclusions != '') { ?>
		<li><a data-toggle="tab" href="#excl"><?php echo $this->lang->line('exclusions'); ?></a></li>
	  <?php } ?>
	  <?php if($terms_conditions != '') { ?>
		<li><a data-toggle="tab" href="#tab3"><?php echo $this->lang->line('terms_and_conditions'); ?></a></li>
	  <?php } ?>
	  <?php if($cancellation_policy != '') { ?>
		<li><a data-toggle="tab" href="#tab2primary"><?php echo $this->lang->line('cancellation_policy'); ?></a></li>
	  <?php } ?>
	  <?php if($privacy_policy != '') { ?>
		<li><a data-toggle="tab" href="#tab4"><?php echo $this->lang->line('privacy_policy'); ?></a></li>
	  <?php } ?>
	  </ul>
    </div>
                
				
<!--				<div class="panel-body mn_border mn_border_top_none" style="background:none;">
                  <div class="tab-content mn_border_none">
                   
                    <?php if($hotel_details['description'] != '') { ?>
					  <div id="tab1primary" class="tab-pane fade in active">
						<div class="col-md-12 nopadding">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary more"> <?php echo $hotel_details['description'];?> </div>
						</div>
					  </div>
					<?php } ?>
					
					<?php if($itinerary != '') { ?>
					  <div id="tabdec" class="tab-pane fade">
						<div class="col-md-12 nopadding">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary"> <?php echo $itinerary;?> </div>
						</div>
					  </div>
					<?php } ?>
					<?php if($itinerary != '') { ?>
					  <div id="incl" class="tab-pane fade">
						<div class="col-md-12 nopadding">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary"> <?php echo $inclusions;?> </div>
						</div>
					  </div>
					<?php } ?>
                    <?php if($itinerary != '') { ?>
					  <div id="excl" class="tab-pane fade">
						<div class="col-md-12 nopadding">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary"> <?php echo $exclusions;?> </div>
						</div>
					  </div>
					<?php } ?>
					<?php if($itinerary != '') { ?>
					  <div id="tab3" class="tab-pane fade">
						<div class="col-md-12">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary"> <?php echo $terms_conditions;?> </div>
						</div>
					  </div>
					<?php } ?>
					<?php if($itinerary != '') { ?>
					  <div id="tab2primary" class="tab-pane fade">
						<div class="col-md-12">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary"> <?php echo $cancellation_policy;?> </div>
						</div>
					  </div>                    
					<?php } ?>
                    <?php if($itinerary != '') { ?>
					  <div id="tab4" class="tab-pane fade">
						<div class="col-md-12 nopadding">
						  <div class="mn_iternary"> </div>
						  <div class="mn_iternary"> <?php echo $privacy_policy;?> </div>
						</div>
					  </div>
					<?php } ?>
                  </div>
                </div>
-->

				<div class="panel-body mn_border mn_border_top_none" style="background:none;">
                  <div class="tab-content mn_border_none">
                   
                    <div id="tab1primary" class="tab-pane fade in active">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary more"> <?php echo $hotel_details['description'];?> </div>
                      </div>
                    </div>
					<div id="tabdec" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $itinerary;?> </div>
                      </div>
                    </div>
					
					<div id="incl" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $inclusions;?> </div>
                      </div>
                    </div>
					
                    <div id="excl" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $exclusions;?> </div>
                      </div>
                    </div>
                    <div id="tab3" class="tab-pane fade">
                      <div class="col-md-12">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $terms_conditions;?> </div>
                      </div>
                    </div>
					<div id="tab2primary" class="tab-pane fade">
                      <div class="col-md-12">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $cancellation_policy;?> </div>
                      </div>
                    </div>                    
                    
                    <div id="tab4" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $privacy_policy;?> </div>
                      </div>
                    </div>
                  </div>
                </div>

				
				
				
              </div>
            </div>
			
			<!--------------------Dynamic Variables--------START---------------->
					  <?php
							if($food_type == 1){
								$food_type = $this->lang->line('food_type_breakfast_only');
							  }elseif($food_type == 2){
								$food_type = $this->lang->line('food_type_all_caps');
							  }elseif($food_type == 3){
								$food_type = $this->lang->line('tour_food_type_uall');
							  }elseif($food_type == 4){
								$food_type = $this->lang->line('food_type_breakfast_lunch');
							  }elseif($food_type == 5){
								$food_type = $this->lang->line('food_type_breakfast_dinner');
							  }else{
								$food_type = "";
							}
							
							$hotel_name_ln = strlen($std_hotel_name);
							if($hotel_name_ln > 20)
							  $width = "style = width:300px";
							else
							  $width = "style = width:200px";
							  
							if($trans_flag == "flight"){
							  $logo_class = 'fa fa-plane';
							  $strng = $this->lang->line('flight_information');
							}elseif($trans_flag == "cruise"){
							  $logo_class = 'fa fa-ship';
							  $strng = $this->lang->line('ship_information');
							}elseif($trans_flag == "train"){
							  $logo_class = 'fa fa-train';
							  $strng = $this->lang->line('train_information');
							}else{
							  $logo_class = 'fa fa-bus';
							  $strng = $this->lang->line('bus_information');
							}
					  
					  
					  ?>
					  
					  <!--------------------Dynamic Variables--------END---------------->
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			            <div class="col-xs-12 nopadding">
            <div class="hotel_list_view roomtools">
                <div class="col-md-12 title_trans">
                  <h4><i class="fa fa-hotel" style="display: inline;"></i> <?php echo $this->lang->line("hotel_information");?></h4>
                </div>
                  <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                    <div class="mn_ta hinfo">
                      <img width="25" src="<?php echo base_url('assets/images/dinner.png');?>"><img src="<?php echo $star_img;?>" alt=""/> <span class="text-center tools"> <a href="#"  class="tooltips" > <?php echo $food_type;?></span> </a>
            <table class="table table-bordered tabledir" >
                        <tr style=" background: #f5f5f5; ">
                          <th style="width: 120px;"> <span><i class="fa fa-h-square" style="display: inline;color: #EC4614;"></i> <?php echo $std_hotel_name;?><br /></span></th>
                          <th> <span><?php echo $this->lang->line("single_room"); ?></span></th>
                          <th> <span><?php echo $this->lang->line("double_room"); ?></span></th>
                          <th> <span><?php echo $this->lang->line("triple_room"); ?></span></th>
                          <th> <span><?php echo $this->lang->line("infant"); ?></span> </th>
                          <th> <span><?php echo $this->lang->line("child_1"); ?></span> </th>
                          <th> <span><?php echo $this->lang->line("child_2"); ?></span> </th>
                          <th> <span><?php echo $this->lang->line("child_3"); ?></span> </th>
                        </tr>
                        <?php if(!empty($hotels)) { foreach($hotels as $hotel) { 
						$single_qty = $hotel['single_qty'];
						$double_qty = $hotel['double_qty'];
						$triple_qty = $hotel['triple_qty'];
			  
						$id_code = $hotel['room_type'];
						$s_id = $hotel['hotel_id'];
						$default_hotel = $hotel['d_hotel'];
						
						if($hotel['hotel_id'] == $selected_hotel && $hotel['room_type'] == $standard){
						  $spcl = "";
						  $people_count = 2;
						  $name = '';
						  $rm_txt = $this->lang->line("standard_room");
						  $flag = '';
						}
						else{
						  $spcl = "spcl";
						  $people_count = '';
						  if($hotel['room_type'] == '') $hotel['room_type'] = $hotel['room_type_en'];
						  $name = $hotel['room_type'];
						  $rm_txt = '';
						  $flag = $hotel['room_type'];
						}
					   
						?>
                        <tr class="table_tr main_tr" data-id="tr<?php echo $hotel['id'];?>">
                          <input type="hidden"  id="master_id" value="<?php echo $hotel['id'];?>">
                          <input type="hidden" id="hotel_id" value='<?php echo $s_id;?>'>
                          <td <?php echo $width;?>><div class="check_box" >
                              <input value="<?php echo $s_id;?>" class="hotel_variables" type="checkbox" data-id="<?php echo $hotel['id'];?>" data-value="<?php echo trim($hotel['latitude']).'-'.trim($hotel['longitude']).'-'.$hotel['name'].'-'.round($hotel['handle_charge']/$currency,2);?>" name="hotels" id="<?php echo 'hotel_'.$hotel['id'];?>" />
                         <span><?php echo $name.$rm_txt;?></span> 
                              
                            </div>
                            
                          
                            <input type="hidden" id="extra_total<?php echo $hotel['id'];?>">
                            <?php
                $check_extra = $this->Tour_model->check_extra($hotel['tour_id'],$hotel['hotel_id'],$hotel['tour_date'],$hotel['room_type'],$this->data['default_language']);
                if(!empty($check_extra)){
              ?>
                            <span class="mn_night ex-night"  data-id="<?php echo $hotel['id'];?>" data-value="single/<?php echo $tour_date;?>" id="single<?php echo $hotel['id'];?>" data-target="#myModal_night1" data-toggle="modal"><?php echo $this->lang->line("extra_night"); ?></span>
                            <?php } else {  ?>
              <span class="mn_night ex-night">&nbsp;</span>
              <?php } ?>
      
                          </td>
                          <?php $tour_date = date('Y-m-d',strtotime($tour_date));?>
                          <input type="hidden" name="hotel_price" id="hotel_price<?php echo $hotel['id'];?>" value="<?php echo round($hotel['single']/$currency,2);?>" >
                          <td style="width: 139px;font-size: 9px;text-align: center;"><div style="width:100%;"> <span class="money" id="display_single<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"> <?php echo round($hotel['single']/$currency,2).'&nbsp;'.$curr;?></span>
                              <input type="hidden" id="single_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['single']/$currency,2);?>">
                              <input type="hidden" id="hid_single<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips" >
                              <span class="<?php echo $fa_content;?>"><input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count single_room"  data-master="<?php echo $hotel['id'];?>" data-value="single" id="count_single<?php echo $hotel['id'];?>"  type="number" min="0" max="<?php echo $single_qty;?>"  style="text-align:center;width: 50px;" value="" onkeypress="return isNumberKey(event,this.value);" >
							  </span>
                              <span><?php echo $this->lang->line("no_of_persons"); ?> </span> </a> </div></td>
                          <td style=" width:139px; font-size: 9px;text-align: center;" class="hotel_list"><div style="width:100%;"> <span class="money" id="display_double<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"> <?php echo round($hotel['doubles']/$currency,2).'&nbsp;'.$curr;?></span>
                              <input type="hidden" id="double_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['doubles']/$currency,2);?>">
                              <input type="hidden" id="hid_double<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <span class="<?php echo $fa_content;?>"><input  data-flag="<?php echo $flag;?>"   class="txt_count double_room"  data-master="<?php echo $hotel['id'];?>" data-value="double" id="count_double<?php echo $hotel['id'];?>"   onkeypress="return isNumberKey(event,this.value);"   type="number" min="0" max="<?php echo $double_qty;?>" step="2" style="text-align:center;width: 50px;" value="" ></span>
                              <span><?php echo $this->lang->line("no_of_persons"); ?></span> </a> </div></td>
                          <td style="width: 139px;font-size: 9px;text-align: center;"><div style="width:100%;"> <span class="money" id="display_triple<?php echo $hotel['id'];?>" style="height:19px; padding:0px;"> <?php echo round($hotel['triple']/$currency,2).'&nbsp;'.$curr;?></span>
                              <input type="hidden" id="triple_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['triple']/$currency,2);?>">
                              <input type="hidden" id="hid_triple<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <span class="<?php echo $fa_content;?>"><input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count triple_room"  data-master="<?php echo $hotel['id'];?>" data-value="triple" id="count_triple<?php echo $hotel['id'];?>"    type="number" min="0" max="<?php echo $triple_qty;?>" step="3" style="text-align:center;width: 50px;" value=""  onkeypress="return isNumberKey(event,this.value);" ></span>
                              <span><?php echo $this->lang->line("no_of_persons"); ?></span> </a> </div></td>
                          <td style="width: 139px;font-size: 9px;text-align: center;" class="hotel_list"><div style="width:100%;"> <span class="money" id="display_infants<?php echo $hotel['id'];?>" style="height:19px; padding:0px;"><?php echo round($hotel['infants']/$currency,2).'&nbsp;'.$curr;?>
                              <input type="hidden" id="hid_infants">
                              </span>
                              <input type="hidden" id="infants_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['infants']/$currency,2);?>">
                              <input type="hidden" id="hid_infants<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <span class="<?php echo $fa_content;?>"><input readonly  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count "  data-master="<?php echo $hotel['id'];?>" data-value="infants" id="count_infants<?php echo $hotel['id'];?>"    type="number" min="0" max="5" style="text-align:center;width: 50px;" value=""  onkeypress="return isNumberKey(event,this.value);"></span>
                              <span><?php echo $this->lang->line("no_of_infants"); ?> </span> </a> </div></td>
                          <td style="font-size: 9px;text-align: center;" class="hotel_list"><div class="pull-left" style="width:90px;"> <span class="money" id="display_twotosix<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"> <?php echo round($hotel['twotosix']/$currency,2).'&nbsp;'.$curr;?>
                              <input type="hidden" id="hid_twotosix">
                              </span>
                              <input type="hidden" id="twotosix_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['twotosix']/$currency,2);?>">
                              <input type="hidden" id="hid_twotosix<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <span class="<?php echo $fa_content;?>"><input readonly  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count "  data-master="<?php echo $hotel['id'];?>" data-value="twotosix" id="count_twotosix<?php echo $hotel['id'];?>"    type="number" min="0" max="5" style="text-align:center;width: 50px;" value=""  onkeypress="return isNumberKey(event,this.value);"></span>
                              <span> <?php echo $this->lang->line("no_of_childs_1"); ?> </span> </a> </div></td>
                          <td style="font-size: 9px;text-align: center;" class="hotel_list"><div class="pull-left" style="width:90px;"> <span class="money" id="display_sixtotwelve<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"><?php echo round($hotel['sixtotwelve']/$currency,2).'&nbsp;'.$curr;?>
                              <input type="hidden" id="hid_sixtotwelve">
                              </span>
                              <input type="hidden" id="sixtotwelve_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['sixtotwelve']/$currency,2);?>">
                              <input type="hidden" id="hid_sixtotwelve<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <span class="<?php echo $fa_content;?>"><input readonly  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count "  data-master="<?php echo $hotel['id'];?>" data-value="sixtotwelve" id="count_sixtotwelve<?php echo $hotel['id'];?>"    type="number" min="0" max="5" style="text-align:center;width: 50px;" value=""  onkeypress="return isNumberKey(event,this.value);"></span>
                              <span> <?php echo $this->lang->line("no_of_childs_2"); ?> </span> </a> </div></td>
                          <td style="font-size: 9px;text-align: center;" class="hotel_list"><div class="pull-left" style="width:90px;"> <span class="money" id="display_twelvetosixteenth<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"><?php echo round($hotel['twelvetosixteenth']/$currency,2).'&nbsp;'.$curr;?>
                              <input type="hidden" id="hid_twelvetosixteenth">
                              </span>
                              <input type="hidden" id="twelvetosixteenth_val<?php echo $hotel['id'];?>" value="<?php echo round($hotel['twelvetosixteenth']/$currency,2);?>">
                              <input type="hidden" id="hid_twelvetosixteenth<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <span class="<?php echo $fa_content;?>"><input readonly  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count "  data-master="<?php echo $hotel['id'];?>" data-value="twelvetosixteenth" id="count_twelvetosixteenth<?php echo $hotel['id'];?>"    type="number" min="0" max="5" style="text-align:center;width: 50px;" value=""  onkeypress="return isNumberKey(event,this.value);"></span>
							  
                              <span> <?php echo $this->lang->line("no_of_childs_3"); ?> </span> </a> </div></td>
                        </tr>
                        <?php } }?>
                       <!--<tr class="table_tr" style="background-color:#eee">
                          <td align="center" colspan="3"><h5><?php echo $this->lang->line('total_amount');?>:&nbsp;&nbsp; <span class="money" id="changeval1">0</span><?php echo ' '.$curr;?> </h5></td>
						  <td colspan="4"></td>
						  <td colspan="2"></td>
                        </tr>-->
                      </table>
                    </div>
                  </div>
                </div> 

			
			
			
			
			
			
            <div class="fix">
			  
			  
              <div class="col-md-12 nopadding">
				<div class="col-md-12 title_trans">
                  <h4><i class="<?php echo $logo_class;?>" style="display: inline;"></i> <?php echo $strng;?></h4>
                </div>
                <div class="col-xs-12 col-md-12 nopadding flight_check">
                  <div class="col-xs-12 nopadding flight_check small_fnt">
                    <?php 
					  $f_price = '';
					  $c_price = '';
					  $t_price = '';
					  $b_price = '';
					  if(!empty($flights) && $trans_flag == "flight"){ $f=0; foreach($flights as $flight) {
						
						if($this->data['default_language'] == 'en')
						  $airline_name = $flight->airline_en;
						else
						  $airline_name = $flight->airline_fa;
						
						if($airline_name == '')
						  $airline_name = $flight->airline_en;
						  
						  
						$infos = json_encode($flight);
						if($trans_flag == "flight"){
						  if($f == 0){
							$checked = 'checked';
							
						  }
						  else{
							$checked = '';
							$f_price = " (".round($flight->flight_price/$currency,2)." ".$curr." ".$this->lang->line("extra_small").")";
						  }
						}else{
							$checked = '';							
						}
					  ?>
                    
					<?php if($checked == 'checked') { ?>
					<input type="hidden" id="pre_departures" data-pre_infos='<?php echo $infos;?>'>
					<?php } ?>
					  <div class="mn_ta trans_nfo col-md-4">
						<div class="table_d">
						  <span class="flight_row">
					  <input data-infos='<?php echo $infos;?>' type='radio' name='trans' <?php echo $checked;?> value="flight_<?php echo $flight->tour_flight_id;?>" data-id="<?php echo $flight->airline_en.'--'.$flight->airline_no.'--'.$flight->departuer_airport.'--'.$flight->arrival_airport.'--'.$flight->departuer_time.'--'.$flight->arrival_time.'--'.$flight->return_deapartur.'--'.$flight->return_arrival.'--'.$flight->return_departure_time.'--'.$flight->return_arrival_time;?>" data-value="<?php echo round($flight->flight_price/$currency,2);?>">
					  <label><span class="trans_circle" data-value="flight_<?php echo $flight->tour_flight_id;?>" data-id="<?php echo $flight->airline_en.'--'.$flight->airline_no.'--'.$flight->departuer_airport.'--'.$flight->arrival_airport.'--'.$flight->departuer_time.'--'.$flight->arrival_time.'--'.$flight->return_deapartur.'--'.$flight->return_arrival.'--'.$flight->return_departure_time.'--'.$flight->return_arrival_time;?>" ><img data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('click_to_see_more_details'); ?>" class="img-circle" height="20px" width="20px"  src="<?php echo upload_url('tour/airline_logo/'.$flight->airline_logo);?>"/>&nbsp;<?php echo $airline_name;?></span><span class="money"><?php echo $f_price;?></span><br/>
					  </label>
					  </span>
						  
						<div class="col-xs-12 nopadding">
						  <div class="col-xs-12 nopadding bus_no">
						  <?php echo $flight->airline_no;?>
						  </div>
						  <div class="flight_middle">
							<div class="departure">
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="destination">
								<div class="from_place"><?php echo $flight->departuer_airport;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $flight->departuer_time;?></div>
								</div>
							  </div>
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="arrival">
								<div class="from_place"><?php echo $flight->arrival_airport;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $flight->arrival_time;?></div>
								</div>
							  </div>
							</div>
						  </div>
						  <div class="flight_middle">
							<div class="departure">
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="destination">
								<div class="from_place"><?php echo $flight->return_deapartur;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $flight->return_departure_time;?></div>
								</div>
							  </div>
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="arrival">
								<div class="from_place"><?php echo $flight->return_arrival;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $flight->return_arrival_time;?></div>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					</div>
						</div>

                    <?php $f++; } ?>
					
					<?php } ?>
			
                    <?php if(!empty($cruises) && $trans_flag == "cruise"){ $c = 0; foreach($cruises as $cruise) {
						$infos = json_encode($cruise);
						if($trans_flag == "cruise"){
						  if($c == 0){
							$checked = 'checked';
						  }
						  else {
							$checked = '';
							$c_price = " (".round($cruise->price/$currency,2)." ".$curr." ".$this->lang->line("extra_small").")";
						  }
						}else{
							$checked = '';
						}
					  ?>
					  <?php if($checked == 'checked') { ?>
					<input type="hidden" id="pre_departures" data-pre_infos='<?php echo $infos;?>'>
					<?php } ?>
					  
					  <div class="mn_ta trans_nfo col-md-4">
						<div class="table_d">
						<span class="flight_row">
                    <input data-infos='<?php echo $infos;?>' data-id="<?php echo $cruise->cruise_name.'--'.$cruise->departure_from.'--'.$cruise->arrival_cruise.'--'.$cruise->departure_time.'--'.$cruise->arrival_time.'--'.$cruise->return_deapartur.'--'.$cruise->return_arrival.'--'.$cruise->return_departure_time.'--'.$cruise->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> value="cruise_<?php echo $cruise->tour_cruise_id;?>" data-value="<?php echo round($cruise->price/$currency,2);?>">
                    <label><span class="trans_circle" data-id="<?php echo $cruise->cruise_name.'--'.$cruise->departure_from.'--'.$cruise->arrival_cruise.'--'.$cruise->departure_time.'--'.$cruise->arrival_time.'--'.$cruise->return_deapartur.'--'.$cruise->return_arrival.'--'.$cruise->return_departure_time.'--'.$cruise->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> data-value="cruise_<?php echo $cruise->tour_cruise_id;?>" ><i data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('click_to_see_more_details'); ?>"  class="fa fa-ship" aria-hidden="true"></i></span>&nbsp;&nbsp;<?php echo $cruise->cruise_name;?><span class="money"><?php echo $c_price;?></span><br/>
                    </label>
                    </span>
						
						<div class="col-xs-12 nopadding">
						  <div class="col-xs-12 nopadding bus_no">
						  <?php echo $cruise->cruise_number;?>
						  </div>
						  <div class="flight_middle">
							<div class="departure">
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="destination">
								<div class="from_place"><?php echo $cruise->departure_from;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $cruise->departure_time;?></div>
								</div>
							  </div>
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="arrival">
								<div class="from_place"><?php echo $cruise->arrival_cruise;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $cruise->arrival_time;?></div>
								</div>
							  </div>
							</div>
						  </div>
						  <div class="flight_middle">
							<div class="departure">
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="destination">
								<div class="from_place"><?php echo $cruise->return_deapartur;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $cruise->return_departure_time;?></div>
								</div>
							  </div>
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="arrival">
								<div class="from_place"><?php echo $cruise->return_arrival;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $cruise->return_arrival_time;?></div>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					</div>
						</div>
						
					  
					  
					  
                    
                    <?php $c++; }  ?>
					
					
					
					
					
					<?php } ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(!empty($trains) && $trans_flag == "train"){ $t=0; foreach($trains as $train) {
						$infos = json_encode($train);
						if($trans_flag == "train"){
						  if($t == 0){
							$checked = 'checked';
						  }
						  else {
							$checked = '';
							$t_price = " (".round($train->price/$currency,2)." ".$curr." ".$this->lang->line("extra_small").")";
						  }
						}else{
							$checked = '';
						}
					  
					  ?>
                    <?php if($checked == 'checked') { ?>
					  <input type="hidden" id="pre_departures" data-pre_infos='<?php echo $infos;?>'>
					<?php } ?>
					
					<div class="mn_ta trans_nfo col-md-4">
					  <div class="table_d">
					  <span class="flight_row">
                    <input data-infos='<?php echo $infos;?>' data-id="<?php echo $train->train_name.'--'.$train->departure_from.'--'.$train->arrival_train.'--'.$train->departure_time.'--'.$train->arrival_time.'--'.$train->return_deapartur.'--'.$train->return_arrival.'--'.$train->return_departure_time.'--'.$train->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> value="train_<?php echo $train->tour_train_id;?>" data-value="<?php echo round($train->price/$currency,2);?>">
                    <label> <span class="trans_circle" data-id="<?php echo $train->train_name.'--'.$train->departure_from.'--'.$train->arrival_train.'--'.$train->departure_time.'--'.$train->arrival_time.'--'.$train->return_deapartur.'--'.$train->return_arrival.'--'.$train->return_departure_time.'--'.$train->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> data-value="train_<?php echo $train->tour_train_id;?>" ><i data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('click_to_see_more_details'); ?>" class="fa fa-train" aria-hidden="true"></i></span>&nbsp;&nbsp; <?php echo $train->train_name;?><span class="money"><?php echo $t_price;?></span><br/>
                    </label>
                    </span>
					  
					  <div class="col-xs-12 nopadding">
						  <div class="col-xs-12 nopadding bus_no">
						  <?php echo $train->train_number;?>
						  </div>
						  <div class="flight_middle">
							<div class="departure">
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="destination">
								<div class="from_place"><?php echo $train->departure_from;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $train->departure_time;?></div>
								</div>
							  </div>
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="arrival">
								<div class="from_place"><?php echo $train->arrival_train;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $train->arrival_time;?></div>
								</div>
							  </div>
							</div>
						  </div>
						  <div class="flight_middle">
							<div class="departure">
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="destination">
								<div class="from_place"><?php echo $train->return_deapartur;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $train->return_departure_time;?></div>
								</div>
							  </div>
							  <div class="col-md-6 col-sm-6 nopadding">
								<div class="arrival">
								<div class="from_place"><?php echo $train->return_arrival;?></div>
								<div class="clearfix"></div>
								<div class="dateinfo <?php echo $fa_content;?>"><?php echo $train->return_arrival_time;?></div>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					</div>
						</div>
                    <?php $t++; } ?>
					
					
					
					
					
					
					
					
					
					<?php } ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(!empty($buses) && $trans_flag == "bus"){ $b = 0; foreach($buses as $bus) {
					$infos = json_encode($bus);
					  if($trans_flag == "bus"){
						  if($b == 0){
							$checked = 'checked';
						  }
						  else {
							$checked = '';
							$b_price = " (".round($bus->price/$currency,2)." ".$curr." ".$this->lang->line("extra_small").")";
						  }
						}else{
							$checked = '';
						}
					  
					  ?>
                    
					
					
			<?php if($checked == 'checked') { ?>
				  <input type="hidden" id="pre_departures" data-pre_infos='<?php echo $infos;?>'>
			<?php } ?>		
<div class="mn_ta trans_nfo col-md-4">
    <div class="table_d">
					  <span class="flight_row">
                    <input data-infos='<?php echo $infos;?>' data-id="<?php echo $bus->bus_name.'--'.$bus->departure_from.'--'.$bus->arrival_bus.'--'.$bus->departure_time.'--'.$bus->arrival_time.'--'.$bus->return_deaparture.'--'.$bus->return_arrival.'--'.$bus->return_departure_time.'--'.$bus->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> value="bus_<?php echo $bus->tour_bus_id;?>" data-value="<?php echo round($bus->price/$currency,2);?>">
                    <label> <span class="trans_circle" data-id="<?php echo $bus->bus_name.'--'.$bus->departure_from.'--'.$bus->arrival_bus.'--'.$bus->departure_time.'--'.$bus->arrival_time.'--'.$bus->return_deaparture.'--'.$bus->return_arrival.'--'.$bus->return_departure_time.'--'.$bus->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> data-value="bus_<?php echo $bus->tour_bus_id;?>" ><i data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('click_to_see_more_details'); ?>" class="fa fa-bus" aria-hidden="true"></i></span>&nbsp;&nbsp; <?php echo $bus->bus_name;?><span class="money"><?php echo $b_price;?></span><br/>
                    </label>
                    </span>
					  

<div class="col-xs-12 nopadding">
  <div class="col-xs-12 nopadding bus_no">
  <?php echo $bus->bus_number;?>
  </div>
  <div class="flight_middle">
	<div class="departure">
	  <div class="col-md-6 col-sm-6 nopadding">
		<div class="destination">
		<div class="from_place"><?php echo $bus->departure_from;?></div>
		<div class="clearfix"></div>
		<div class="dateinfo <?php echo $fa_content;?>"><?php echo $bus->departure_time;?></div>
		</div>
	  </div>
	  <div class="col-md-6 col-sm-6 nopadding">
		<div class="arrival">
		<div class="from_place"><?php echo $bus->arrival_bus;?></div>
		<div class="clearfix"></div>
		<div class="dateinfo <?php echo $fa_content;?>"><?php echo $bus->arrival_time;?></div>
		</div>
	  </div>
	</div>
  </div>
  <div class="flight_middle">
	<div class="departure">
	  <div class="col-md-6 col-sm-6 nopadding">
		<div class="destination">
		<div class="from_place"><?php echo $bus->return_deaparture;?></div>
		<div class="clearfix"></div>
		<div class="dateinfo <?php echo $fa_content;?>"><?php echo $bus->return_departure_time;?></div>
		</div>
	  </div>
	  <div class="col-md-6 col-sm-6 nopadding">
		<div class="arrival">
		<div class="from_place"><?php echo $bus->return_arrival;?></div>
		<div class="clearfix"></div>
		<div class="dateinfo <?php echo $fa_content;?>"><?php echo $bus->return_arrival_time;?></div>
		</div>
	  </div>
	</div>
  </div>
</div>

</div>
</div>
					
                    
					
					
					
					<?php $b++; }?>
					
					
					<?php } ?>
					
					 
                  </div>
				  <!--<div class="amount_div"><?php echo $this->lang->line('total_amount');?>:&nbsp;&nbsp; <span class="money" id="changeval1">0</span><?php echo ' '.$curr;?> </div>-->
                </div>
                
              </div>




              <div class="clearfix"></div>
            </div>
			
			
			
			
            </div>

            <div class="col-md-12 nopadding">
              <?php if($gen_info->visa != 0 || $gen_info->insurance != 0 || $gen_info->transfer != 0 || $gen_info->cipout != 0 || $gen_info->cipin != 0) { ?>
              <div class="mn_facilities mn_extra_ser">
                <h2><?php echo $this->lang->line('extra_services');?></h2>
                <ul>
				<?php
				  if($gen_info->visa == 0)
					$display = '';
				  else
					$display = 1;
				  
				  if($gen_info->visa == 1 && round($gen_info->visa_price/$currency,2) == 0){
					$disabled = 'disabled';
					$price = '';
					$checked = 'checked';
					$included = $this->lang->line("included");
					$class = '';
				  }
				  elseif(round($gen_info->visa_price/$currency,2) != 0){
					$disabled = '';
					$price = round($gen_info->visa_price/$currency,2);
					$checked = '';
					$included = '';
					$class = 'mn_checked';
				}
				?>
				<?php if($display == 1) { ?>
				  <li>
                    <div class="check_box <?php echo $class;?>">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="visa" id="visa" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="visa"></label>
                    </div>
                    <span class="mn_image mn_7"></span><?php echo $this->lang->line('visa');?>
                    <?php if($price != '') { ?>
                    - <span class="mn_red money"><?php echo $price;?> <?php echo $curr;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                  </li>
				<?php } ?>
                  
				  
				<?php
				  if($gen_info->insurance == 0)
					$display = '';
				  else
					$display = 1;
				  
				  if($gen_info->insurance == 1 && round($gen_info->insurance_price/$currency,2) == 0){
					$disabled = 'disabled';
					$price = '';
					$checked = 'checked';
					$included = $this->lang->line("included");
					$class = '';
				  }
				  elseif(round($gen_info->insurance_price/$currency,2) != 0){
					$disabled = '';
					$price = round($gen_info->insurance_price/$currency,2);
					$checked = '';
					$included = '';
					$class = 'mn_checked';
				  }
				?>
				<?php if($display == 1) { ?>
				  <li>
                    <div class="check_box <?php echo $class;?>">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="insurance" id="insurance" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="insurance"></label>
                    </div>
                    <span class="mn_image mn_8"></span><?php echo $this->lang->line('insurance');?>
                    <?php if($price != '') { ?>
                    - <span class="mn_red money"><?php echo $price;?> <?php echo $curr;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
				  </li>
				<?php } ?>
                   
				<?php
				  if($gen_info->transfer == 0)
					$display = '';
				  else
					$display = 1;
				  
				  if($gen_info->transfer == 1 && round($gen_info->transfer_price/$currency,2) == 0){
					$disabled = 'disabled';
					$price = '';
					$checked = 'checked';
					$included = $this->lang->line("included");
					$class = '';
				  }
				  elseif(round($gen_info->transfer_price/$currency,2) != 0){
					$disabled = '';
					$price = round($gen_info->transfer_price/$currency,2);
					$checked = '';
					$included = '';
					$class = 'mn_checked';
				  }
				?>
				<?php if($display == 1) { ?>
				  <li>
                    <div class="check_box <?php echo $class;?>">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="transfer" id="transfer" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="transfer"></label>
                    </div>
                    <span class="mn_image mn_9"></span><?php echo $this->lang->line('transfer');?>
                    <?php if($price != '') { ?>
                    - <span class="mn_red money"><?php echo $price;?> <?php echo $curr;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
				  </li>
				<?php } ?>
                  
				<?php
				  if($gen_info->cipout == 0)
					$display = '';
				  else
					$display = 1;
				  
				  if($gen_info->cipout == 1 && round($gen_info->cipout_price/$currency,2) == 0){
					$disabled = 'disabled';
					$price = '';
					$checked = 'checked';
					$included = $this->lang->line("included");
					$class = '';
				  }
				  elseif(round($gen_info->cipout_price/$currency,2) != 0){
					$disabled = '';
					$price = round($gen_info->cipout_price/$currency,2);
					$checked = '';
					$included = '';
					$class = 'mn_checked';
				  }
				?>
				<?php if($display == 1) { ?>
				  <li>
                    <div class="check_box <?php echo $class;?>">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="cipout" id="cipout" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="cipout"></label>
                    </div>
                    <span class="mn_image mn_10"></span><?php echo $this->lang->line('cip_outbound');?>
                    <?php if($price != '') { ?>
                    - <span class="mn_red money"><?php echo $price;?> <?php echo $curr;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
				  </li>
				<?php } ?>
                  
				<?php
				  if($gen_info->cipin == 0)
					$display = '';
				  else
					$display = 1;
				  
				  if($gen_info->cipin == 1 && round($gen_info->cipin_price/$currency,2) == 0){
					$disabled = 'disabled';
					$price = '';
					$checked = 'checked';
					$included = $this->lang->line("included");
					$class = '';
				  }
				  elseif(round($gen_info->cipin_price/$currency,2) != 0){
					$disabled = '';
					$price = round($gen_info->cipin_price/$currency,2);
					$checked = '';
					$included = '';
					$class = 'mn_checked';
				}
				?>
				<?php if($display == 1) { ?>
				  <li>
                    <div class="check_box <?php echo $class;?>">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="cipin" id="cipin" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="cipin"></label>
                    </div>
                    <span class="mn_image mn_11"></span><?php echo $this->lang->line('cip_inbound');?>
                    <?php if($price != '') { ?>
                    - <span class="mn_red money"><?php echo $price;?> <?php echo $curr;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                    <?php } ?>
				  </li>
				   <?php } ?>
                </ul>
              </div>
             
            </div>
            <div class="col-md-12 nopadding">
              <div class="col-md-6 nopadding_left">
                <div class="mn_facilities">
                  <h4><?php echo $this->lang->line('tour_facilities');?></h4>
                  <ul>
                    <?php
					  $gen_info->facilities = 'Guiding,medicine,Sports';
					  $facilities = explode(",",$gen_info->facilities);
					  foreach($facilities as $facilities_rows){ 
					?>
                    <li><span class="fa fa-check"></span><?php echo $facilities_rows ?></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
              <div class="col-md-6 nopadding_right">
                <div class="mn_facilities ">
                  <h4><?php echo $this->lang->line('hotel_facilities');?></h4>
                  <ul id="amenities_hotel">
                    <?php
					  $hotel_price->amenities = 'Complimentary Breakfast,Restaurant,Internet / Free Wi-Fi,Parking';
					  $amenities = explode(",",$hotel_price->amenities);
					  foreach($amenities as $am){ 
					?>
                    <li><span class="fa fa-check"></span><?php echo $am; ?></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
            <!-- <div class="col-md-12 nopadding">
            </div>--> 
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
  //if ( _window.width() > 781 ) {
  //          var mastheadHeight = $( '#masthead' ).height(),
  //                  toolbarOffset, mastheadOffset;
  //  
  //             if ( mastheadHeight > 48 ) {
  //                 body.removeClass( 'masthead-fixed' );
  //             }
  // 
  //              if ( body.is( '.header-image' ) ) {
  //                  toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
  //                  mastheadOffset = $( '#masthead' ).offset().top - toolbarOffset;
  //  
  //                 _window.on( 'scroll.twentyfourteen', function() {
  //                      if ( _window.scrollTop() > mastheadOffset && mastheadHeight < 49 ) {
  //                          body.addClass( 'masthead-fixed' );
  //                      } else {
  //                        body.removeClass( 'masthead-fixed' );
  //                     }
  //                 } );
  //             }
  //        }



</script>

<!------Hidden value for Map START------->
<input type="hidden" id="h_name" value="<?php echo $hotel_price->name?>">

<!------Hidden value for Map END-------> 



<?php 
	$this->load->view('common/pop-ups');
	$this->load->view('common/footer');
	$this->load->view("common/script");
?>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script> 

<script>
  
function isNumberKey(evt,input) { 
		
		return false;
}
</script>

<div class="modal fade" id="myModal_night1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo $this->lang->line("extra_night"); ?></h4>
      </div>
      <div class="modal-body"><?php echo $this->lang->line("extra_night_info"); ?></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line("close_small"); ?></button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
</body>
</html>

