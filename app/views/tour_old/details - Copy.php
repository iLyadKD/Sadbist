<!DOCTYPE html>
<html class="no-js">
<?php
  $include["css"][] = "backslider";
  $include["css"][] = "owl.theme";
  $include["css"][] = "owl.carousel";
  $include["css"][] = "jquery.bxslider";
  $include["css"][] = "ion.rangeSlider";
  $this->load->view("common/head", $include);
  ?>

<body>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
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
	width:80%;
	margin:0 95px 10px 0
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
    color: #000; font-weight: 500;
    cursor: default;
    background-color: #ecffdb;
    border: 1px solid #308233;
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
	padding:0 15px 10px 15px;
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
  padding-left: 10px;
}
.flight_row input {
  cursor: pointer;
}

.trans_nfo {
  position: relative;
  right: 115px;
}

.title_trans h4 {
  font-size: 14px;
  position: relative;
  top: 35px;
  right: 5px;
}

.ex-night {
    
    padding-top: 1px !important;
	width: 30% !important;
	float: left !important;
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




	


</style>
<div id="wrapper">
  <?php
      $this->load->view("common/header");
      $this->load->view("common/notification");
	  if($currency == 1) $price_currency= 'USD'; else $price_currency = 'IRR';
  ?>
  <div class="flight_block_modify result_bg">
    <div class="container nopadding">
      <div class="row">
        <div class="col-xs-12 nopadding">
          <div class="sortblock">
            <div class="col-md-3 col-sm-4 col-xs-12 border-rt">
              
                <?php
				if($gen_info->no_of_night > 1)
				  $n_str ='s';
				else
				  $n_str ='';
				  
				if($gen_info->no_of_day > 1)
				  $d_str ='s';
				else
				  $d_str ='';
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
                              <img class="hotel_lo" src="<?php echo base_url('assets/images/facebook.png');?>" alt=""><h2><?php echo $gen_info->tour_name;?> </h2>
                              <h3><img src="<?php echo base_url('assets/images/'.$star.'');?>" alt=""/></h3>
                              <small><?php echo $gen_info->no_of_night.'&nbsp;Night'.$n_str;?> <?php echo $gen_info->no_of_day.'&nbsp;Day'.$d_str;?></small>
                              
                              <h5><?php echo $from_city.'&nbsp;to&nbsp;'.$to_city;?></h5>
                            </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 border-rt">
              <div class="checkin2"> <span> <?php echo $tour_date;?> </span> </div>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6 border-rt">
              
              <div class="mn_star"><img src="<?php echo base_url('assets/images/'.$star.'');?>" alt=""/></div>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12 border-rt">
              <div class="checkin2"><a href="javascript:window.history.go(-1);"> <?php echo $tour_count;?> Packages Available </a href="javascript:window.history.go(-1);"> </div>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6"> <a class="modify_search_btn" href="javascript:window.history.go(-1);">GO BACK</a></div>
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
                <div class="col-md-7 nopadding">
                  <div class="col-md-12 nopadding">
                    <div id="slider1_container" style="position: relative; width: 720px;
        height: 460px; overflow: hidden;">
                      <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 720px; height: 460px;
            overflow: hidden;">
                        <?php if(!empty($galleries)) { ?>
                        <?php foreach($galleries as $gallery){ ?>
                        <div> <img u="image" src="<?php echo ABS_BASE_PATH.'assets/tour/hotel/'.$gallery['gallery_name'];?>" /> <img u="thumb" src="<?php echo ABS_BASE_PATH.'assets/tour/hotel/'.$gallery['gallery_name'];?>" /> </div>
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
                <div class="col-md-5 nopadding">
                  <div class="flight-list-v2">
                    <div class="hotel-list-view1">
                      <div class="box_info" id="box_info_1">
                        <div class="col-md-12 nopadding">
                          <div class="col-md-6 mn_top_packge">
                            <div class="mn-name">
                              <img class="hotel_lo" src="<?php echo base_url('assets/images/facebook.png');?>" alt=""><h2><?php echo $gen_info->tour_name;?> </h2>
                              <h3><img src="<?php echo base_url('assets/images/'.$star.'');?>" alt=""/></h3>
                              <small><?php echo $gen_info->no_of_night.'&nbsp;Night'.$n_str;?> <?php echo $gen_info->no_of_day.'&nbsp;Day'.$d_str;?></small>
                              
                              <h5><?php echo $from_city.'&nbsp;to&nbsp;'.$to_city;?></h5>
                            </div>
                          </div>
                          <div class="col-md-6 mn_top_packge">
                            <h2 class="hotel_rate" id="hotel_rate">
                              <input type="hidden" id="fixed_price" value="<?php echo $total_tour_price*$currency;?>">
                              <input type="hidden" id="total_nights" value="<?php echo $gen_info->no_of_night;?>">
                              <input type="hidden" id="transportation_cost" value="0">
                              <input type="hidden" id="handle_charge" value="<?php echo $hotel_price->handle_charge*$currency;?>">
                              <input type="hidden" id="overall_tour_price" value="<?php echo $tour_price->total_tour_price*$currency;?>">
                              <input type="hidden" id="extra_cost" value="0">
                              <input type="hidden" id="currency" value="<?php echo $currency;?>">
                              <input type="hidden" id="transport_flag" value=0>
                              <span id="changeval"><?php echo $total_tour_price*2;?></span><?php echo ' '.$price_currency;?></h2>
                          </div>
                        </div>
                        <div class="col-md-12 col-sm-6 pull-right mn_top_packge">
                          <form name="book_now" id="book_now" method="post" action="<?php echo base_url('tour/prebooking')?>">
                            <div class="col-md-12 prebook_data"> 
                              <!--<a href="#" class="cta">BOOK NOW</a>-->
                              
                              <input type="hidden"  name="id_hotel" id="id_hotel" value="<?php echo $selected_hotel;?>">
                              <input type="hidden" value='0'  name="count_room_single" id="count_room_single">
                              <input type="hidden" value='1'  name="count_room_double" id="count_room_double">
                              <input type="hidden" value='0'  name="count_room_triple" id="count_room_triple">
                              <input type="hidden" value='0'  name="infants" id="infants">
                              <input type="hidden" value='0'  name="twotosix" id="twotosix">
                              <input type="hidden" value='0'  name="sixtotwelve" id="sixtotwelve">
                              <input type="hidden" value='0'  name="twelvetosixteenth" id="twelvetosixteenth">
                              <input type="hidden" value='<?php echo $tour_id;?>'  name="id_tour" id="id_tour">
                              <input type="hidden" value="<?php echo $total_tour_price*2*$currency;?>"  name="price_tour" id="price_tour">
                              <input type="hidden" value="<?php echo $transportation_cost*$currency;?>"  name="cost_transportation" id="cost_transportation">
                              <input type="hidden"   name="tour_date" value="<?php echo date('Y-m-d',strtotime($tour_date));?>">
                              <input type="hidden"   name="trans_param" id="trans_param">
                              <input  type="submit" class="cta" value="BOOK NOW">
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                      <input type="hidden" id="latitude"  value="<?php echo $hotel_price->latitude?>" >
                      <input type="hidden" id="longitude"  value=" <?php echo $hotel_price->longitude?>" >
                      <div style="width:450px;height: 265px;margin-left: 20px;" id="hotel_map"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="fix"> 
              <div class="panel with-nav-tabs mn_top_input">
                <div class="mn_background">
                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab1primary">Description</a></li>
                    <li><a data-toggle="tab" href="#tabdec">Itinerary</a></li>
                    <li><a data-toggle="tab" href="#incl">Inclusions</a></li>
                    <li><a data-toggle="tab" href="#excl">Exclusions</a></li>
                    <li><a data-toggle="tab" href="#tab3">Terms & Conditions</a></li>
                    <li><a data-toggle="tab" href="#tab2primary">Cancellation Policy</a></li>
                    <li><a data-toggle="tab" href="#tab4">Privacy Policy</a></li>
                  </ul>
                </div>
                <div class="panel-body mn_border mn_border_top_none" style="background:none;">
                  <div class="tab-content mn_border_none">
                    <div id="tab1primary" class="tab-pane fade in active">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary more"> <?php echo $gen_info->overview;?> </div>
                      </div>
                    </div>
                    <div id="incl" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $gen_info->inclusions;?> </div>
                      </div>
                    </div>
                    <div id="excl" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $gen_info->exclusions;?> </div>
                      </div>
                    </div>
                    <div id="tabdec" class="tab-pane fade">
                      <div class="col-md-12">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $gen_info->itinerary;?> </div>
                      </div>
                    </div>
                    <div id="tab2primary" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $gen_info->cancellation_policy;?> </div>
                      </div>
                    </div>
                    <div id="tab3" class="tab-pane fade">
                      <div class="col-md-12">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $gen_info->terms_conditions;?> </div>
                      </div>
                    </div>
                    <div id="tab4" class="tab-pane fade">
                      <div class="col-md-12 nopadding">
                        <div class="mn_iternary"> </div>
                        <div class="mn_iternary"> <?php echo $gen_info->privacy_policy;?> </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="fix">
			  
			  <!--------------------Dynamic Variables--------START---------------->
					  <?php
							if($food_type == 1){
								$food_type = "Breakfast only";
							  }elseif($food_type == 2){
								$food_type = "ALL";
							  }elseif($food_type == 3){
								$food_type = "UALL";
							  }elseif($food_type == 4){
								$food_type = "Breakfast and Lunch";
							  }elseif($food_type == 5){
								$food_type = "Breakfast and Dinner";
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
							  $strng = 'Flight Information';
							}elseif($trans_flag == "cruise"){
							  $logo_class = 'fa fa-ship';
							  $strng = 'Ship liner Info';
							}elseif($trans_flag == "train"){
							  $logo_class = 'fa fa-train';
							  $strng = 'Train Information';
							}else{
							  $logo_class = 'fa fa-bus';
							  $strng = 'Bus Information';
							}
					  
					  
					  ?>
					  
					  <!--------------------Dynamic Variables--------END---------------->
              <div class="col-md-12 nopadding">
				<div class="col-md-3 title_trans">
                  <h4><i class="<?php echo $logo_class;?>" style="display: inline;"></i> <?php echo $strng;?></h4>
                </div>
                <div class="col-xs-12 col-md-9 pull-right nopadding flight_check">
                  <div class="col-xs-12 nopadding flight_check small_fnt">
                    <?php 
					  $f_price = '';
					  $c_price = '';
					  $t_price = '';
					  $b_price = '';
					  if(!empty($flights) && $trans_flag == "flight"){ $f=0; foreach($flights as $flight) {
						if($trans_flag == "flight"){
						  if($f == 0){
							$airline_en = $flight->airline_en;
							$airline_no = $flight->airline_no;
							$departuer_airport = $flight->departuer_airport;
							$arrival_airport = $flight->arrival_airport;
							$departuer_time = $flight->departuer_time;
							$arrival_time = $flight->arrival_time;
							$return_deapartur = $flight->return_deapartur;
							$return_arrival = $flight->return_arrival;
							$return_departure_time = $flight->return_departure_time;
							$return_arrival_time = $flight->return_arrival_time;
							$checked = 'checked';
						  }
						  else{
							$checked = '';
							$f_price = " (".$flight->flight_price*$currency." ".$price_currency." extra)";
						  }
						}else{
							$checked = '';							
						}
					  ?>
                    <span class="flight_row">
                    <input type='radio' name='trans' <?php echo $checked;?> value="flight_<?php echo $flight->tour_flight_id;?>" data-id="<?php echo $flight->airline_en.'--'.$flight->airline_no.'--'.$flight->departuer_airport.'--'.$flight->arrival_airport.'--'.$flight->departuer_time.'--'.$flight->arrival_time.'--'.$flight->return_deapartur.'--'.$flight->return_arrival.'--'.$flight->return_departure_time.'--'.$flight->return_arrival_time;?>" data-value="<?php echo $flight->flight_price*$currency;?>">
                    <label><span class="trans_circle" data-value="flight_<?php echo $flight->tour_flight_id;?>" data-id="<?php echo $flight->airline_en.'--'.$flight->airline_no.'--'.$flight->departuer_airport.'--'.$flight->arrival_airport.'--'.$flight->departuer_time.'--'.$flight->arrival_time.'--'.$flight->return_deapartur.'--'.$flight->return_arrival.'--'.$flight->return_departure_time.'--'.$flight->return_arrival_time;?>" ><img data-placement="bottom" data-toggle="tooltip" title="click here to see details" class="img-circle" height="20px" width="20px"  src="<?php echo ABS_BASE_PATH.'assets/tour/airline_logos/'.$flight->airline_logo;?>"/></span><?php echo $f_price;?><br/>
                    </label>
                    </span>
                    <?php $f++; } ?>
					<?php if($trans_flag == "flight"){ ?>
					<div class="mn_ta trans_nfo">
					  <table class="table table-bordered">
                        <tr style=" background: #FFFFFF; ">
                          <th> <span> Airline Name</span> </th>
                          <th> <span>Airline No</span></th>
                          <th> <span>Dep Airport</span></th>
                          <th> <span>ETD</span></th>
                          <th> <span>Arrival Airport</span> </th>
                          <th> <span>ETA</span> </th>
                          <th> <span>Return Dep Airport</span> </th>
                          <th> <span>Return ETD</span> </th>
						  <th> <span>Return Arrival Airport</span> </th>
						  <th> <span>Return ETA</span> </th>
                        </tr>
						
						<tr class="air" style=" background: #f5f5f5; ">
						  <td><span class="air_name"><?php echo $airline_en;?></span></td>
						  <td><span class="air_no"><?php echo $airline_no;?></span></td>
						  <td><span class="dept_air"><?php echo $departuer_airport;?></span></td>
						  <td><span class="dept_time"><?php echo $arrival_airport;?></span></td>
						  <td><span class="arr_air"><?php echo $departuer_time;?></span></td>
						  <td><span class="arr_time"><?php echo $arrival_time;?></span></td>
						  <td><span class="re_dept_air"><?php echo $return_deapartur;?></span></td>
						  <td><span class="re_dept_time"><?php echo $return_arrival;?></span></td>
						  <td><span class="re_arr_air"><?php echo $return_departure_time;?></span></td>
						  <td><span class="re_arr_time"><?php echo $return_arrival_time;?></span></td>
						</tr>
					</table>
					</div>
					<?php } ?>
					
					<?php } ?>
			
                    <?php if(!empty($cruises) && $trans_flag == "cruise"){ $c = 0; foreach($cruises as $cruise) {
						if($trans_flag == "cruise"){
						  if($c == 0){
							$airline_en = $cruise->cruise_name;
							$airline_no = $cruise->cruise_number;
							$departuer_airport = $cruise->departure_from;
							$arrival_airport = $cruise->arrival_cruise;
							$departuer_time = $cruise->departure_time;
							$arrival_time = $cruise->arrival_time;
							$return_deapartur = $cruise->return_deapartur;
							$return_arrival = $cruise->return_arrival;
							$return_departure_time = $cruise->return_departure_time;
							$return_arrival_time = $cruise->return_arrival_time;
							$checked = 'checked';
						  }
						  else {
							$checked = '';
							$c_price = " (".$cruise->price*$currency." ".$price_currency." extra)";
						  }
						}else{
							$checked = '';
						}
					  ?>
                    <span class="flight_row">
                    <input data-id="<?php echo $cruise->cruise_name.'--'.$cruise->departure_from.'--'.$cruise->arrival_cruise.'--'.$cruise->departure_time.'--'.$cruise->arrival_time.'--'.$cruise->return_deapartur.'--'.$cruise->return_arrival.'--'.$cruise->return_departure_time.'--'.$cruise->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> value="cruise_<?php echo $cruise->tour_cruise_id;?>" data-value="<?php echo $cruise->price*$currency;?>">
                    <label><span class="trans_circle" data-id="<?php echo $cruise->cruise_name.'--'.$cruise->departure_from.'--'.$cruise->arrival_cruise.'--'.$cruise->departure_time.'--'.$cruise->arrival_time.'--'.$cruise->return_deapartur.'--'.$cruise->return_arrival.'--'.$cruise->return_departure_time.'--'.$cruise->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> data-value="cruise_<?php echo $cruise->tour_cruise_id;?>" ><i data-placement="bottom" data-toggle="tooltip" title="click here to see details"  class="fa fa-ship" aria-hidden="true"></i></span>&nbsp;&nbsp;<?php echo $cruise->cruise_name;?><?php echo $c_price;?><br/>
                    </label>
                    </span>
                    <?php $c++; }  ?>
					<?php if($trans_flag == "cruise"){ ?>
					<div class="mn_ta trans_nfo">
					  <table class="table table-bordered">
                        <tr style=" background: #FFFFFF; ">
                          <th> <span> Ship liner Name</span> </th>
                          <th> <span>Ship liner No</span></th>
                          <th> <span>Dep Port</span></th>
                          <th> <span>ETD</span></th>
                          <th> <span>Arrival Port</span> </th>
                          <th> <span>ETA</span> </th>
                          <th> <span>Return Dep Port</span> </th>
                          <th> <span>Return ETD</span> </th>
						  <th> <span>Return Arrival Port</span> </th>
						  <th> <span>Return ETA</span> </th>
                        </tr>
						
						<tr class="cruise" style=" background: #f5f5f5; ">
						  <td><span class="air_name"><?php echo $airline_en;?></span></td>
						  <td><span class="air_no"><?php echo $airline_no;?></span></td>
						  <td><span class="dept_air"><?php echo $departuer_airport;?></span></td>
						  <td><span class="dept_time"><?php echo $arrival_airport;?></span></td>
						  <td><span class="arr_air"><?php echo $departuer_time;?></span></td>
						  <td><span class="arr_time"><?php echo $arrival_time;?></span></td>
						  <td><span class="re_dept_air"><?php echo $return_deapartur;?></span></td>
						  <td><span class="re_dept_time"><?php echo $return_arrival;?></span></td>
						  <td><span class="re_arr_air"><?php echo $return_departure_time;?></span></td>
						  <td><span class="re_arr_time"><?php echo $return_arrival_time;?></span></td>
						</tr>
					</table>
					</div>
					<?php } ?>
					
					
					
					
					<?php } ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(!empty($trains) && $trans_flag == "train"){ $t=0; foreach($trains as $train) {
						if($trans_flag == "train"){
						  if($t == 0){
							$airline_en = $train->train_name;
							$airline_no = $train->train_number;
							$departuer_airport = $train->departure_from;
							$arrival_airport = $train->arrival_train;
							$departuer_time = $train->departure_time;
							$arrival_time = $train->arrival_time;
							$return_deapartur = $train->return_deapartur;
							$return_arrival = $train->return_arrival;
							$return_departure_time = $train->return_departure_time;
							$return_arrival_time = $train->return_arrival_time;
							$checked = 'checked';
						  }
						  else {
							$checked = '';
							$t_price = " (".$train->price*$currency." ".$price_currency." extra)";
						  }
						}else{
							$checked = '';
						}
					  
					  ?>
                    <span class="flight_row">
                    <input data-id="<?php echo $train->train_name.'--'.$train->departure_from.'--'.$train->arrival_train.'--'.$train->departure_time.'--'.$train->arrival_time.'--'.$train->return_deapartur.'--'.$train->return_arrival.'--'.$train->return_departure_time.'--'.$train->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> value="train_<?php echo $train->tour_train_id;?>" data-value="<?php echo $train->price*$currency;?>">
                    <label> <span class="trans_circle" data-id="<?php echo $train->train_name.'--'.$train->departure_from.'--'.$train->arrival_train.'--'.$train->departure_time.'--'.$train->arrival_time.'--'.$train->return_deapartur.'--'.$train->return_arrival.'--'.$train->return_departure_time.'--'.$train->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> data-value="train_<?php echo $train->tour_train_id;?>" ><i data-placement="bottom" data-toggle="tooltip" title="click here to see details" class="fa fa-train" aria-hidden="true"></i></span>&nbsp;&nbsp; <?php echo $train->train_name;?><?php echo $t_price;?><br/>
                    </label>
                    </span>
                    <?php $t++; } ?>
					<?php if($trans_flag == "train"){ ?>
					<div class="mn_ta trans_nfo">
					  <table class="table table-bordered">
                        <tr style=" background: #FFFFFF; ">
                          <th> <span> Train Name</span> </th>
                          <th> <span>Train No</span></th>
                          <th> <span>Dep Station</span></th>
                          <th> <span>ETD</span></th>
                          <th> <span>Arrival Station</span> </th>
                          <th> <span>ETA</span> </th>
                          <th> <span>Return Dep Station</span> </th>
                          <th> <span>Return ETD</span> </th>
						  <th> <span>Return Arrival Station</span> </th>
						  <th> <span>Return ETA</span> </th>
                        </tr>
						
						<tr class="train" style=" background: #f5f5f5; ">
						  <td><span class="air_name"><?php echo $airline_en;?></span></td>
						  <td><span class="air_no"><?php echo $airline_no;?></span></td>
						  <td><span class="dept_air"><?php echo $departuer_airport;?></span></td>
						  <td><span class="dept_time"><?php echo $arrival_airport;?></span></td>
						  <td><span class="arr_air"><?php echo $departuer_time;?></span></td>
						  <td><span class="arr_time"><?php echo $arrival_time;?></span></td>
						  <td><span class="re_dept_air"><?php echo $return_deapartur;?></span></td>
						  <td><span class="re_dept_time"><?php echo $return_arrival;?></span></td>
						  <td><span class="re_arr_air"><?php echo $return_departure_time;?></span></td>
						  <td><span class="re_arr_time"><?php echo $return_arrival_time;?></span></td>
						</tr>
					</table>
					</div>
					<?php } ?>
					
					
					
					
					
					
					
					<?php } ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if(!empty($buses) && $trans_flag == "bus"){ $b = 0; foreach($buses as $bus) {
					  if($trans_flag == "bus"){
						  if($b == 0){
							$airline_en = $bus->bus_name;
							$airline_no = $bus->bus_number;
							$departuer_airport = $bus->departure_from;
							$arrival_airport = $bus->arrival_bus;
							$departuer_time = $bus->departure_time;
							$arrival_time = $bus->arrival_time;
							$return_deapartur = $bus->return_deaparture;
							$return_arrival = $bus->return_arrival;
							$return_departure_time = $bus->return_departure_time;
							$return_arrival_time = $bus->return_arrival_time;
							$checked = 'checked';
						  }
						  else {
							$checked = '';
							$b_price = " (".$bus->price*$currency." ".$price_currency." extra)";
						  }
						}else{
							$checked = '';
						}
					  
					  ?>
                    <span class="flight_row">
                    <input data-id="<?php echo $bus->bus_name.'--'.$bus->departure_from.'--'.$bus->arrival_bus.'--'.$bus->departure_time.'--'.$bus->arrival_time.'--'.$bus->return_deaparture.'--'.$bus->return_arrival.'--'.$bus->return_departure_time.'--'.$bus->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> value="bus_<?php echo $bus->tour_bus_id;?>" data-value="<?php echo $bus->price*$currency;?>">
                    <label> <span class="trans_circle" data-id="<?php echo $bus->bus_name.'--'.$bus->departure_from.'--'.$bus->arrival_bus.'--'.$bus->departure_time.'--'.$bus->arrival_time.'--'.$bus->return_deaparture.'--'.$bus->return_arrival.'--'.$bus->return_departure_time.'--'.$bus->return_arrival_time;?>" type="radio" name="trans" <?php echo $checked;?> data-value="bus_<?php echo $bus->tour_bus_id;?>" ><i data-placement="bottom" data-toggle="tooltip" title="click here to see details" class="fa fa-bus" aria-hidden="true"></i></span>&nbsp;&nbsp; <?php echo $bus->bus_name;?><?php echo $b_price;?><br/>
                    </label>
                    </span>
                    <?php $b++; }?>
					
					<?php if($trans_flag == "bus"){ ?>
					<div class="mn_ta trans_nfo">
					  <table class="table table-bordered">
                        <tr style=" background: #FFFFFF; ">
                          <th> <span> Bus Name</span> </th>
                          <th> <span>Bus No</span></th>
                          <th> <span>Dep Station</span></th>
                          <th> <span>ETD</span></th>
                          <th> <span>Arrival Station</span> </th>
                          <th> <span>ETA</span> </th>
                          <th> <span>Return Dep Station</span> </th>
                          <th> <span>Return ETD</span> </th>
						  <th> <span>Return Arrival Station</span> </th>
						  <th> <span>Return ETA</span> </th>
                        </tr>
						
						<tr class="bus" style=" background: #f5f5f5; ">
						  <td><span class="air_name"><?php echo $airline_en;?></span></td>
						  <td><span class="air_no"><?php echo $airline_no;?></span></td>
						  <td><span class="dept_air"><?php echo $departuer_airport;?></span></td>
						  <td><span class="dept_time"><?php echo $arrival_airport;?></span></td>
						  <td><span class="arr_air"><?php echo $departuer_time;?></span></td>
						  <td><span class="arr_time"><?php echo $arrival_time;?></span></td>
						  <td><span class="re_dept_air"><?php echo $return_deapartur;?></span></td>
						  <td><span class="re_dept_time"><?php echo $return_arrival;?></span></td>
						  <td><span class="re_arr_air"><?php echo $return_departure_time;?></span></td>
						  <td><span class="re_arr_time"><?php echo $return_arrival_time;?></span></td>
						</tr>
					</table>
					</div>
					<?php } ?>
					<?php } ?>
                  </div>
                </div>
                <div class="hotel-list-view1 hotel_list_view roomtools">
                  <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                    <div class="mn_ta">
                      
					  <table class="table table-bordered">
                        <tr style=" background: #f5f5f5; ">
                          <th style="width: 120px;"> <span><i class="fa fa-h-square" style="display: inline;color: #EC4614;"></i> <?php echo $std_hotel_name;?><br /><img src="<?php echo $star_img;?>" alt=""/></span> &nbsp;<span class="text-center tools"> <a href="#"  class="tooltips" > <img width="25" src="<?php echo base_url('assets/images/dinner.png');?>"> <span><?php echo $food_type;?></span> </a> </span></th>
                          <th> <span>Single Room</span></th>
                          <th> <span>Double Room</span></th>
                          <th> <span>Triple Room</span></th>
                          <th> <span>Infant</span> </th>
                          <th> <span>Child (2-6)</span> </th>
                          <th> <span>Child (6-12)</span> </th>
                          <th> <span>Child (12-16)</span> </th>
                        </tr>
                        <?php if(!empty($hotels)) { foreach($hotels as $hotel) { 
							$id_code = str_replace( ' ', '', $hotel['room_type']);
							$s_id = $hotel['hotel_id'];
							$default_hotel = $hotel['d_hotel'];
							if($hotel['hotel_id'] == $selected_hotel && $hotel['room_type'] == 'Standard'){
							  $spcl = "";
							  $people_count = 2;
							  $name = '';
							  $rm_txt = 'Standard Room';
							  $flag = '';
							}
							else{
							  $spcl = "spcl";
							  $people_count = '';
							  $name = $hotel['room_type'];
							  $rm_txt = '';
							  $flag = $hotel['room_type'];
							}
						  ?>
                        <tr class="table_tr main_tr" data-id="tr<?php echo $hotel['id'];?>">
                          <input type="hidden"  id="master_id" value="<?php echo $hotel['id'];?>">
                          <input type="hidden" id="hotel_id" value='<?php echo $s_id;?>'>
                          <td <?php echo $width;?>><div class="check_box" >
                              <input value="<?php echo $s_id;?>" class="hotel_variables" type="checkbox" data-id="<?php echo $hotel['id'];?>" data-value="<?php echo trim($hotel['latitude']).'-'.trim($hotel['longitude']).'-'.$hotel['name'].'-'.$hotel['handle_charge']*$currency;?>" name="hotels" id="<?php echo 'hotel_'.$hotel['id'];?>" />
                         <span><?php echo $name.$rm_txt;?></span> 
                              
                            </div>
                            
                          
                            <input type="hidden" id="extra_total<?php echo $hotel['id'];?>">
                            <?php
							  $check_extra = $this->Tour_model->check_extra($hotel['tour_id'],$hotel['hotel_id'],$hotel['tour_date'],$hotel['room_type']);
							  if(!empty($check_extra)){
							?>
                            <span class="mn_night ex-night"  data-id="<?php echo $hotel['id'];?>" data-value="single/<?php echo $tour_date;?>" id="single<?php echo $hotel['id'];?>" data-target="#myModal_night1" data-toggle="modal">Extra Night</span>
                            <?php } else {  ?>
							<span class="mn_night ex-night">&nbsp;</span>
							<?php } ?>
			
                          </td>
                          <?php $tour_date = date('Y-m-d',strtotime($tour_date));?>
                          <input type="hidden" name="hotel_price" id="hotel_price<?php echo $hotel['id'];?>" value="<?php echo $hotel['single']*$currency;?>" >
                          <td style="width: 139px;font-size: 9px;text-align: center;"><div style="width:100%;"> <span id="display_single<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"> <?php echo $hotel['single']*$currency;?></span>
                              <input type="hidden" id="single_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['single']*$currency;?>">
                              <input type="hidden" id="hid_single<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips" >
                              <input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count" data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="single" id="count_single<?php echo $hotel['id'];?>" onkeypress="return isNumberKey(event);" type="number" min="0" max="10"  style="text-align:center;width: 50px;" value="" >
                              <span>No of persons </span> </a> </div></td>
                          <td style=" width:139px; font-size: 9px;text-align: center;" class="hotel_list"><div style="width:100%;"> <span id="display_double<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"> <?php echo $hotel['doubles']*$currency;?></span>
                              <input type="hidden" id="double_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['doubles']*$currency;?>">
                              <input type="hidden" id="hid_double<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <input  data-flag="<?php echo $flag;?>"   class="txt_count " data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="double" id="count_double<?php echo $hotel['id'];?>"   onkeypress="return isNumberKey(event,this.value);"   type="number" min="0" max="10" step="2" style="text-align:center;width: 50px;" value="<?php echo $people_count;?>" >
                              <span>No of persons</span> </a> </div></td>
                          <td style="width: 139px;font-size: 9px;text-align: center;"><div style="width:100%;"> <span id="display_triple<?php echo $hotel['id'];?>" style="height:19px; padding:0px;"> <?php echo $hotel['triple']*$currency;?></span>
                              <input type="hidden" id="triple_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['triple']*$currency;?>">
                              <input type="hidden" id="hid_triple<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count" data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="triple" id="count_triple<?php echo $hotel['id'];?>" onkeypress="return isNumberKey(event);"   type="number" min="0" max="12" step="3" style="text-align:center;width: 50px;" value="" >
                              <span>No of persons</span> </a> </div></td>
                          <td style="width: 139px;font-size: 9px;text-align: center;" class="hotel_list"><div style="width:100%;"> <span id="display_infants<?php echo $hotel['id'];?>" style="height:19px; padding:0px;"><?php echo $hotel['infants']*$currency;?>
                              <input type="hidden" id="hid_infants">
                              </span>
                              <input type="hidden" id="infants_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['infants']*$currency;?>">
                              <input type="hidden" id="hid_infants<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count " data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="infants" id="count_infants<?php echo $hotel['id'];?>"  onkeypress="return isNumberKey(event);"  type="number" min="0" max="10" style="text-align:center;width: 50px;" value="" >
                              <span>No of infants </span> </a> </div></td>
                          <td style="font-size: 9px;text-align: center;" class="hotel_list"><div class="pull-left" style="width:90px;"> <span id="display_twotosix<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"> <?php echo $hotel['twotosix']*$currency;?>
                              <input type="hidden" id="hid_twotosix">
                              </span>
                              <input type="hidden" id="twotosix_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['twotosix']*$currency;?>">
                              <input type="hidden" id="hid_twotosix<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count " data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="twotosix" id="count_twotosix<?php echo $hotel['id'];?>"  onkeypress="return isNumberKey(event);"  type="number" min="0" max="10" style="text-align:center;width: 50px;" value="" >
                              <span> No of childs (2-6) </span> </a> </div></td>
                          <td style="font-size: 9px;text-align: center;" class="hotel_list"><div class="pull-left" style="width:90px;"> <span id="display_sixtotwelve<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"><?php echo $hotel['sixtotwelve']*$currency;?>
                              <input type="hidden" id="hid_sixtotwelve">
                              </span>
                              <input type="hidden" id="sixtotwelve_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['sixtotwelve']*$currency;?>">
                              <input type="hidden" id="hid_sixtotwelve<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count " data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="sixtotwelve" id="count_sixtotwelve<?php echo $hotel['id'];?>"  onkeypress="return isNumberKey(event);"  type="number" min="0" max="10" style="text-align:center;width: 50px;" value="" >
                              <span> No of childs(6-12) </span> </a> </div></td>
                          <td style="font-size: 9px;text-align: center;" class="hotel_list"><div class="pull-left" style="width:90px;"> <span id="display_twelvetosixteenth<?php echo $hotel['id'];?>"  style="height:19px; padding:0px;"><?php echo $hotel['twelvetosixteenth']*$currency;?>
                              <input type="hidden" id="hid_twelvetosixteenth">
                              </span>
                              <input type="hidden" id="twelvetosixteenth_val<?php echo $hotel['id'];?>" value="<?php echo $hotel['twelvetosixteenth']*$currency;?>">
                              <input type="hidden" id="hid_twelvetosixteenth<?php echo $hotel['id'];?>">
                            </div>
                            <div class="tools"> <a class="tooltips">
                              <input  data-toggle="tooltip" data-flag="<?php echo $flag;?>"  class="txt_count " data-id="<?php echo 'hotel_'.$hotel['id'];?>" data-master="<?php echo $hotel['id'];?>" data-value="twelvetosixteenth" id="count_twelvetosixteenth<?php echo $hotel['id'];?>"   onkeypress="return isNumberKey(event);" type="number" min="0" max="10" style="text-align:center;width: 50px;" value="" >
                              <span> No of childs(12-16) </span> </a> </div></td>
                        </tr>
                        <?php } }?>
                        <tr class="table_tr" style="background-color:#eee" data-id="tr1353">
                          <input type="hidden" id="master_id" value="1353">
                          <input type="hidden" id="hotel_id" value="15">
                          <td align="center" colspan="3"><h5>Total Amount:&nbsp;&nbsp; <span id="changeval1"><?php echo $total_tour_price*2;?></span><?php echo ' '.$price_currency;?> </h5></td>
                          <?php if($gen_info->tour_file != '') { ?>
						  <td colspan="2"></td>
						  <td colspan="2">
						  <a class="pdflink" href="<?php echo ABS_BASE_PATH.'assets/tour/'.$gen_info->tour_file;?>" target="_blank">Download Brochure</a>
						  
						  </td>
						  <?php } else { ?>
						  <td colspan="4"></td>
						  <?php } ?>
                          <td colspan="2"><input type="submit" class="cta" value="BOOK NOW" style="margin:0px; width:100%;"></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="col-md-12 nopadding">
              <?php if($gen_info->visa != 0 || $gen_info->insurance != 0 || $gen_info->transfer != 0 || $gen_info->cipout != 0 || $gen_info->cipin != 0) { ?>
              <div class="mn_facilities mn_extra_ser">
                <h2>Extra Services</h2>
                <ul>
                  <li>
                    <?php
						  if($gen_info->visa == 0)
							$display = '';
						  else
							$display = 1;
						  
						  if($gen_info->visa == 1 && $gen_info->visa_price*$currency == 0){
							$disabled = 'disabled';
							$price = '';
							$checked = 'checked';
							$included = 'Included';
						  }
						  elseif($gen_info->visa_price*$currency != 0){
							$disabled = '';
							$price = $gen_info->visa_price*$currency;
							$checked = '';
							$included = '';
						  }
						  
							
						  
						?>
                    <?php if($display == 1) { ?>
                    <div class="check_box">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="visa" id="visa" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="visa"></label>
                    </div>
                    <span class="mn_image mn_7"></span>Visa
                    <?php if($price != '') { ?>
                    - <span class="mn_red"><?php echo $price;?> <?php echo $price_currency;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                    <?php } ?>
                  </li>
                  <li>
                    <?php
						  if($gen_info->insurance == 0)
							$display = '';
						  else
							$display = 1;
						  
						  if($gen_info->insurance == 1 && $gen_info->insurance_price*$currency == 0){
							$disabled = 'disabled';
							$price = '';
							$checked = 'checked';
							$included = 'Included';
						  }
						  elseif($gen_info->insurance_price*$currency != 0){
							$disabled = '';
							$price = $gen_info->insurance_price*$currency;
							$checked = '';
							$included = '';
						  }
						  
							
						  
						?>
                    <?php if($display == 1) { ?>
                    <div class="check_box">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="insurance" id="insurance" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="insurance"></label>
                    </div>
                    <span class="mn_image mn_8"></span>Insurance
                    <?php if($price != '') { ?>
                    - <span class="mn_red"><?php echo $price;?> <?php echo $price_currency;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                    <?php } ?>
                  <li>
                    <?php
						  if($gen_info->transfer == 0)
							$display = '';
						  else
							$display = 1;
						  
						  if($gen_info->transfer == 1 && $gen_info->transfer_price*$currency == 0){
							$disabled = 'disabled';
							$price = '';
							$checked = 'checked';
							$included = 'Included';
						  }
						  elseif($gen_info->transfer_price*$currency != 0){
							$disabled = '';
							$price = $gen_info->transfer_price*$currency;
							$checked = '';
							$included = '';
						  }
						  
							
						  
						?>
                    <?php if($display == 1) { ?>
                    <div class="check_box">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="transfer" id="transfer" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="transfer"></label>
                    </div>
                    <span class="mn_image mn_9"></span>Transfer
                    <?php if($price != '') { ?>
                    - <span class="mn_red"><?php echo $price;?> <?php echo $price_currency;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                    <?php } ?>
                  <li>
                    <?php
						  if($gen_info->cipout == 0)
							$display = '';
						  else
							$display = 1;
						  
						  if($gen_info->cipout == 1 && $gen_info->cipout_price*$currency == 0){
							$disabled = 'disabled';
							$price = '';
							$checked = 'checked';
							$included = 'Included';
						  }
						  elseif($gen_info->cipout_price*$currency != 0){
							$disabled = '';
							$price = $gen_info->cipout_price*$currency;
							$checked = '';
							$included = '';
						  }
						  
							
						  
						?>
                    <?php if($display == 1) { ?>
                    <div class="check_box">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="cipout" id="cipout" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="cipout"></label>
                    </div>
                    <span class="mn_image mn_10"></span>CIP OUT
                    <?php if($price != '') { ?>
                    - <span class="mn_red"><?php echo $price;?> <?php echo $price_currency;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                    <?php } ?>
                  <li>
                    <?php
						  if($gen_info->cipin == 0)
							$display = '';
						  else
							$display = 1;
						  
						  if($gen_info->cipin == 1 && $gen_info->cipin_price*$currency == 0){
							$disabled = 'disabled';
							$price = '';
							$checked = 'checked';
							$included = 'Included';
						  }
						  elseif($gen_info->cipin_price*$currency != 0){
							$disabled = '';
							$price = $gen_info->cipin_price*$currency;
							$checked = '';
							$included = '';
						  }
						  
							
						  
						?>
                    <?php if($display == 1) { ?>
                    <div class="check_box">
                      <input <?php if($price != '') { echo 'value='.$price.''; } ?> type="checkbox" data-id="cipin" id="cipin" <?php echo $disabled;?> <?php echo $checked;?>>
                      <label for="cipin"></label>
                    </div>
                    <span class="mn_image mn_11"></span>CIP IN
                    <?php if($price != '') { ?>
                    - <span class="mn_red"><?php echo $price;?> <?php echo $price_currency;?></span>
                    <?php }else echo '- <span class="mn_red">'.$included.'</span>'; ?>
                    <?php } ?>
                </ul>
              </div>
              <?php } ?>
            </div>
            <div class="col-md-12 nopadding">
              <div class="col-md-6 nopadding_left">
                <div class="mn_facilities">
                  <h2>Tour Facilities</h2>
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
                  <h2>Hotel Facilities</h2>
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

<!------Hidden value for Map START------->
<input type="hidden" id="h_name" value="<?php echo $hotel_price->name?>">

<!------Hidden value for Map END-------> 

<!--Extra Night Modal -->
<div class="modal fade" id="myModal_night1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Extra Night</h4>
      </div>
      <div class="modal-body"> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like). </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!--Airline Modal -->
<div class="modal fade" id="modal_transport_details" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="trans_name"></span>&nbsp;<i id ="transclass" style="display: inline;"></i></h4>
      </div>
      <div class="fix">
        <div class="col-md-12 nopadding">
          <div class="hotel-list-view1" style="margin:0px;">
            <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
              <div>
                <table class="table table-bordered">
                  <tr style=" background: #f5f5f5; ">
                    <th> <span id="1st">Airline No</span> </th>
                    <th> <span id="2nd">Departure Airport</span> </th>
                    <th> <span id="3rd">Departure Time</span> </th>
                    <th> <span id="4th">Arrival Airport</span> </th>
                    <th> <span id="5th">Arrival Time</span> </th>
                    <th> <span id="6th">Return Departure Airport</span> </th>
                    <th> <span id="7th">Return Departure Time</span> </th>
                    <th> <span id="8th">Return Arrival Airport</span> </th>
                    <th> <span id="9th">Return Arrival Time</span> </th>
                  </tr>
                  <tr>
                    <td><span id="air_no"></span></td>
                    <td><span id="dept_air"></span></td>
                    <td><span id="dept_time"></span></td>
                    <td><span id="arr_air"></span></td>
                    <td><span id="arr_time"></span></td>
                    <td><span id="re_dept_air"></span></td>
                    <td><span id="re_dept_time"></span></td>
                    <td><span id="re_arr_air"></span></td>
                    <td><span id="re_arr_time"></span></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>

<!----End Popup------->
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

function value_check(value,max,hotel_id,key){
	var get_total_price = $("#changeval").html();
	if (value > max) {
		if (max == 1) var room = "room"; else var room = "rooms"; 
		$("#extra_"+key+hotel_id).css('display','inline-block').text(max +' '+ room +' '+ "left");
		setTimeout(function(){
			$("#display_"+key+hotel_id).html($("#"+key+"_val"+hotel_id).val());
			$("#extra_"+key+hotel_id).fadeOut("slow");
			$("#count_"+key+hotel_id).val(1);
			$("#changeval").html(get_total_price);
			$("#price_tour").val($("#changeval").html());
		}, 2000);
    }
}

    
  </script>
<?php //echo $this->data['default_currency'].'/'.$curr;?>
</body>
</html>
</body>
</html>
