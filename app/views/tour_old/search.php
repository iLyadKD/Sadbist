<!DOCTYPE html>
<html class="no-js">
	<?php
	$include["css"][] = "jquery-ui";
	$include["css"][] = "simplePagination";
	$this->load->view("common/head", $include);

	?>
<style>
.city_title{border-bottom: 1px solid #EEE;}
#listings .description {
	width: 100%;
	background-color: transparent;
}
.sorting1{float: left !important;}
.sorting1 ul li a {
	padding: 0px 8px;
}
.filters h4, .mn_span {
	color:#3d6cc6; font-size: 27px;
}
.hotelfilter_block h4 {
	font-size:16px;
}
#listings h4 {
	color:#ec4614;
	font-size:15px;
	margin:10px 0 0;
}
 #listings .hotel-wrap:nth-of-type(odd) {
 background: #f5f5f5;
}
#listings .mn_price_1 ins {
	font-size: 17px;
	margin-top:5px;
}
.cta-wrap span {
	font-size:16px;
	color:#787777;
	text-decoration:underline
}
.cta-wrap span:hover {
	color:#36C
}
.sorting1 ul li {
	padding:4px 0;
	margin:0
}
.modal-body {
	padding:0px;
}
.table {
	margin-bottom: 0px;
}
#flight_result h2 {
	margin-top: 0px;
}
.check_box {
	margin: 5px 0px;
}
.check_box label {
	position: relative;
}
.h-listing {
	border: 1px solid #ddd;
	padding: 10px 0;
	background: #fff;
	box-shadow: 0px 0px 1px 0px #ccc;
}
#listings .pricing {
	background-color: transparent;
}
.nav-tabs > li > a {
	background: #EC4714;
	color: #fff;
}
.filtericon {
	background: #808080 url("<?php echo base_url('assets/images/filter.png');?>") no-repeat scroll center top 10px;
	border: 1px solid #fff;
	height: 40px;
	overflow: hidden;
	width: 40px;
	z-index: 100;
	margin:18px 13px
}
#listings .mn_price_1 {
	padding:0
}
#listings .cta-wrap a {
	width:auto;
	margin:0
}
.modal-header {
	background-color:#F35D30;
}
.modal-title {
	color: #fff;
}
.filter_block {
	background: #fff;
	box-shadow: 0px 0px 1px 1px #eee;
}
.filters {
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
	background: rgba(0, 0, 0, 0) url("<?php echo base_url('assets/images/sorting_arrow.png');?>") no-repeat scroll center center;
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

.no_result {
    left: 345px;
    position: relative;
    top: 4px;
}
.special_offer {
    font-weight: bold;
    border-right: 0;
    padding: 7px 8px; 
	font-family: 'Roboto', sans-serif;	
    background: #fff; font-size:12px;
    display: block;
    text-align: center;
    color: #2f2f2f;
	border-bottom: 2px solid #eee;
}

.sorting1 ul li div.special_offer {
    background-color: transparent;
    display: inline-block;
    cursor: pointer;
    color: #2BCD1B;
    font-family: 'Roboto', sans-serif;
    font-size: 13px;
    font-weight: normal;
    padding: 5px 14px;
    text-decoration: none;
    text-transform: uppercase;
	position: relative;
	bottom: 20px;
}
.checkbox {
	position: relative;
	top: 10px;
}
</style>
	<body class="contrast-muted login contrast-background">
		<div id="wrapper">
			<?php
				$this->load->view("common/header");
				$this->load->view("common/notification");
				$this->load->view("tour/modify_search");
			?>
    
<div class="clearfix"></div>


<div class="middle_content">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 nopadding">
          <div id="flight_result">
           <div class="col-md-12 nopadding">


  <?php $this->load->view("tour/left_panel");?>


 

<div class="col-md-10 col-sm-10 col-xs-12 nopadding">

			 
			  <div class="hotelfilter_block">
                  	
					<div class="col-md-11 nopadding">
						<div class="sorting1">
						   <ul>
			               	   	<li><?php echo $this->lang->line("tour_search_sortby_colan"); ?></li>
			                    <li><a data-id="1" data-value="0"  class="segmented_btn" ng-click="predicate = 'star'; reverse=!reverse" href="#"><?php echo $this->lang->line("tour_search_star_rating"); ?><span></span></a></li>
			                    <li><a data-id="2" data-value="0" class="segmented_btn" ng-click="predicate = 'hotel_price'; reverse=!reverse" href="#"><?php echo $this->lang->line("tour_search_price"); ?><span></span></a></li>
			                    <li><a data-id="3" data-value="0" class="segmented_btn" ng-click="predicate = 'title'; reverse=!reverse" href="#"><?php echo $this->lang->line("tour_search_hotel_name"); ?><span></span></a></li>
								<li>
									<div class="special_offer">
										<div class="checkbox">
											<label><input class="spcl_o" type="checkbox" value="1" ><?php echo $this->lang->line("special_offers_only"); ?></label>
										</div>
									</div>
								</li>
			                </ul>
						</div>
						</div>
						<div class="col-md-1 nopadding">
							<div class="listins">  <a href="#" id="list" class="listbox nlist fa fa-list"></a> <a id="grid" href="#" class="listbox ngridb fa fa-th"></a></div>
						</div>
					</div>
				 <div class="h-listing list-group" id="products"></div>
				<div class="pagination-holder clearfix">
				   <div id="compact-pagination" style="display: none;margin-top: 20px;"></div>
				</div>

		  
		  
       </div>
  
       </div>       
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>

			<?php 
			$this->load->view('common/pop-ups');
			$this->load->view('common/footer');
			$this->load->view("common/script");
			?>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>


<!--Tour search ModulSTART -->



<!-----------Tour search area---------END------>

</body>
</html>