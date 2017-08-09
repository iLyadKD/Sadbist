<style>
	.flight_logo{
		float: left;
		margin-left:5px; 
	}
	.flight_logo img {
		width: 25px;
		border-radius:20% 
	}
</style>

<?php
	$include["css"][] = "simplePagination";

?>
<?php if(!empty($tours)) { $hotel_base_price_store = array(); $i = 0;?>
<?php foreach($tours as $tour) { 
					   $i++;
					   $flight_logos = explode(",",$tour->flight_count);
					   
                       $total_tour_price = round($tour->FINAL_PRICE/$currency,2);
					   $base_price = $tour->overall_tour_price;
                        if($tour->tour_flight_id == ''){
                           if($tour->tour_cruise_id == ''){
                              if($tour->tour_train_id == ''){
                                 if($tour->tour_bus_id == ''){
                                   $transportation_cost = 0;
                                   $trans_id = 0;
                                   $trans_flag = 0;
                                 }else{
                                   $transportation_cost = round($tour->bus_price/$currency,2);
                                   $trans_id = $tour->tour_bus_id;
                                   $trans_flag = 'bus';
									$trans_logo = "class='fa fa-bus'";
                                 }
                              }else{
                                 $transportation_cost = round($tour->train_price/$currency,2);
                                 $trans_id = $tour->tour_train_id;
                                 $trans_flag = 'train';
								  $trans_logo = "class='fa fa-train'";
                              }
                           }else{
                              $transportation_cost = round($tour->cruise_price/$currency,2);
                              $trans_id = $tour->tour_cruise_id;
                              $trans_flag = 'cruise';
							   $trans_logo = "class='fa fa-ship'";
                           }
                        }else{
                          
                          $transportation_cost = round($tour->flight_price/$currency,2);
                          $trans_id = $tour->tour_flight_id;
                          $trans_flag = 'flight';
						  $trans_logo = "class='fa fa-plane'";
                        }
                        $hotel_base_price = round($tour->doubles/$currency,2);
                        
                        if($transportation_cost == '')
                           $transportation_cost = 0;
                       
                        //$price_store[] = $total_tour_price;
                    $hotel_base_price_store[] = $hotel_base_price;
                      $session_data = explode(",",$this->session->userdata['keyword_to']);
					  
					  $code = $tour->tour_id;
                    $hotel_id = $tour->hotel_id;
					if($currency == 1) $price_currency= 'IRR'; else $price_currency = 'USD';
					
					if($tour->flag_discount == 1) $spcl_offer = 'sprice'; else $spcl_offer = '';
					
					if($tour->food_type == 1){
						$food_type = "Breakfast only";
					}elseif($tour->food_type == 2){
						$food_type = "ALL";
					}elseif($tour->food_type == 3){
						$food_type = "UALL";
					}elseif($tour->food_type == 4){
						$food_type = "Breakfast and Lunch";
					}elseif($tour->food_type == 5){
						$food_type = "Breakfast and Dinner";
					}else{
						$food_type = "";
					}
					
					$tour_name_length = strlen($tour->name);
					if($tour_name_length > 35)
						$tour_name = substr($tour->name,0,35).' ...';
					else
						$tour_name = $tour->name;
					
                ?>

<div class="hotellist hotel-wrap col-xs-12 col-sm-4 col-lg-3 nopadding item list-group-item">
  <div class="col-md-12 col-sm-12 col-xs-12 nopadding full_width">
    <div class="description h-card resp-module">
      <div class="image-and-details thumbnail">
        
		
		
		<div class="col-md-1 col-sm-1 nopadding grid_image grid-box">
          <div class="image carimage"> <span><a  <?php if($tour->status == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($code).'/'.base64_encode($hotel_id).'/'.base64_encode($total_tour_price).'/'.base64_encode($transportation_cost).'/'.base64_encode($trans_flag.'-'.$trans_id).'/'.base64_encode($tour->tour_date).'/'.base64_encode($tour->master_id).'/'.$this->data['default_currency'].'/'.$tour->hotel_rating.'/'.base64_encode($base_price));?>" <?php } ?>><i <?php echo $trans_logo;?> aria-hidden="true"></i></a>
            <?php //echo $tour->master_id;?>
            </span> </div>
        </div>
		

<div class="col-md-1 col-sm-1 nopadding grid_image grid-box">
    <a class="starrate"  <?php if($tour->status == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($code).'/'.base64_encode($hotel_id).'/'.base64_encode($total_tour_price).'/'.base64_encode($transportation_cost).'/'.base64_encode($trans_flag.'-'.$trans_id).'/'.base64_encode($tour->tour_date).'/'.base64_encode($tour->master_id).'/'.$this->data['default_currency'].'/'.$tour->hotel_rating.'/'.base64_encode($base_price));?>" <?php } ?>>
            <div class="col-md-12 nopadding">
              <div class="mn_rating">
                <?php $final_rating = $tour->hotel_rating;?>
                <?php echo $final_rating;?><img alt="" src="<?php echo base_url('assets/images/star.png')?>"> </div>
            </div>
            </a> 
		</div>
		
		
		
        <div class="col-md-2 col-sm-6 nopad grid_desc grid-box">
          <div class="col-md-12 nopadding">
            <div class="col-md-12 col-sm-3 nopadding grid_desc">
              <div class="mn_resultc">
                <a data-target-blank="" <?php if($tour->status == 1) { ?> href="<?php echo base_url('tour/details/'.base64_encode($code).'/'.base64_encode($hotel_id).'/'.base64_encode($total_tour_price).'/'.base64_encode($transportation_cost).'/'.base64_encode($trans_flag.'-'.$trans_id).'/'.base64_encode($tour->tour_date).'/'.base64_encode($tour->master_id).'/'.$this->data['default_currency'].'/'.$tour->hotel_rating.'/'.base64_encode($base_price));?>" <?php } ?>>
                <h4><img height="30" alt="" src="<?php echo upload_url('/hotel/logo/'.$tour->logo);?>" class="u-photo list-group-image"><?php echo $tour_name;?> </h4>
                </a> </div>
            </div>
            </div>
        </div>
		
		
		
		
		
		
		
        <div class="col-md-8 col-sm-5 nopadding_right grid_desc grid-box2">
          <div class="col-md-12 nopadding"> 
            <div class="col-md-3 nopadding girdinfo ">
			<div class="tools">
				<a href="#"  class="tooltips" >
					<img width="25" src="<?php echo base_url('assets/images/dinner.png');?>">
					<span><?php echo $food_type;?></span>
				</a>
			</div>
            </div>
			<a style="color: #333 !important;"   <?php if($tour->status == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($code).'/'.base64_encode($hotel_id).'/'.base64_encode($total_tour_price).'/'.base64_encode($transportation_cost).'/'.base64_encode($trans_flag.'-'.$trans_id).'/'.base64_encode($tour->tour_date).'/'.base64_encode($tour->master_id).'/'.$this->data['default_currency'].'/'.$tour->hotel_rating.'/'.base64_encode($base_price));?>" <?php } ?>>
            <div class="col-md-2 nopadding mn_ro girdinfo">
              <div class="mn_neigh"> <span> <strong> <?php echo ($tour->no_of_night+1).' days';?> </strong> </span> </div>
            </div>
            <div class="col-md-2 nopadding mn_ro girdinfo griddate">
              <div class="mn_neigh"> <span><strong><?php echo $tour->tour_date;?></strong></span> </div>
            </div>
            <div class="col-md-2 nopadding mn_ro text-center sout iconv">
				<?php if($tour->status == 0) { ?>
				<img class="soutimg" width="70" src="<?php echo base_url('assets/images/sout.png');?>">
				<?php } ?> 
		<?php //if($tour->)?>
		<?php
			if(count($flight_logos)>0){
				foreach($flight_logos as $fl){ 
		?>
		<!--<i class="fa fa-plane"></i>-->
		<div class="flight_logo">
		<img  src="<?php echo upload_url('/tour/airline_logo/'.$fl);?>">
		</div>
		<?php } } ?>
        
			</div>
            <div class="col-md-3 col-sm-3 col-xs-12 nopadding price_det">
              <div class="mn_neigh">
				<div class="mn_price_1">
					<span class="old-price-cont">
						<span class="money"><?php echo $total_tour_price;?></span>
						<?php echo $price_currency;?>
					</span>
				</div>
              </div>
              <div class="<?php echo $spcl_offer;?>">
              </div>
            </div>
		  </a>
		  
		  
          </div>
        </div>
		
		
		
		
		
		
		
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
<?php } ?>

<!--<div id="load_more" style="text-align: center;cursor: pointer;">Load More</div>
<input type="hidden" id="click_count" value="0">-->

<?php
  $max_range = max($price_store);
  $min_range = min($price_store);
  

}else {
  
  ?>
<span class="no_result"> No Search Result Found</span>
<?php } ?>
<input id="all_count"  name="all_count" type="hidden" value="<?php if($tours){ echo count($tours).'&nbsp;result&nbsp;';}else { echo 'No&nbsp;result&nbsp;';}?>found">
<input id="chk_count" type="hidden" value="<?php if($tours) echo count($tours); else echo 0;?>" />
<input id="max_range" type="hidden" value="<?php if(isset($max_range)) echo $max_range;?>" />
<input id="min_range" type="hidden" value="<?php if(isset($min_range)) echo $min_range;?>" />
<input type="hidden" id="std_currency" value="<?php echo $currency;?>">
<input type="hidden" id="tours_count" value="<?php echo $this->session->userdata('tours_count');?>">
<input type="hidden" id="min_date" value="<?php if($max_date != '') echo date("d/m/Y", strtotime($min_date));else echo 0;?>">
<input type="hidden" id="max_date" value="<?php if($max_date != '') echo date("d/m/Y", strtotime($max_date));else echo 0;?>">
<script>
   
   
$(".money").each(function(){
	var input = $(this).html();
	var cnvrt = money(input);
	$(this).html(cnvrt);
});
   
   function ajaxsearch() {
		var loading_img ="<p class='text-center'><img src='"+ base_url +"assets/images/loader/loading.gif' alt'Loading'><br><h3 class='text-center' ></h3>";
		var min_value = $("body").find("#min_range").val();
		var max_value = $("body").find("#max_range").val();
		//$("html, body").animate({ scrollTop: 100 }, "slow");
		$('#products').html(loading_img);
        $("#compact-pagination").hide();
		
		setTimeout(function(){
			var postData=$( "#modify_package" ).serialize() + '&' +  $( "#fliter" ).serialize();
			$.ajax({
				method: "get",
				url: base_url+'tour/result',
				data: postData
			})
			.done(function( msg ) {
				$('#products').html(msg);
                $("#compact-pagination").show();
				$(".total-count").html($('#all_count').val());
				
				
			});
		}, 1000);
	
	}
	
	function money(val){
		while (/(\d+)(\d{3})/.test(val.toString())){
		  val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
		}
		return val;
	}
</script> 


 