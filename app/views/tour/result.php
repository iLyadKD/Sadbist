<style>
	.flight_logo{
		float: left;
		margin-left:5px; 
	}
	.flight_logo img {
		width: 25px;
		border-radius:20% 
	}
	.other_trans {
		text-shadow: 1px 1px 1px #ccc;
		font-size:10px;
		background: #b8340a !important;
	}
</style>

<?php
	$include["css"][] = "simplePagination";
	if($this->data['default_language'] == 'fa') $fa_content = 'fa_content'; else $fa_content = '';

?>
<?php if(!empty($tours)) { $hotel_base_price_store = array(); $i = 0;?>
<?php foreach($tours as $tour) { 
					   $i++;
					   $flight_logos = explode(",",$tour->flight_count);
					   $total_tour_price = round($tour->FINAL_PRICE/$currency,0);
					   $base_price = round($tour->overall_tour_price/$currency,0);
                        $trans_logo = "class='fa fa-question'";
						if($tour->tour_flight_id == ''){
                           if($tour->tour_cruise_id == ''){
                              if($tour->tour_train_id == ''){
                                 if($tour->tour_bus_id == ''){
                                 }else{
									$trans_logo = "class='fa fa-bus'";
                                 }
                              }else{
								  $trans_logo = "class='fa fa-train'";
                              }
                           }else{
							   $trans_logo = "class='fa fa-ship'";
                           }
                        }else{
						  $trans_logo = "class='fa fa-plane'";
                        }
						$hotel_base_price = round($tour->doubles/$currency,2);
                        
                      
                       
                    $hotel_base_price_store[] = $hotel_base_price;
                      $session_data = explode(",",$this->session->userdata['keyword_to']);
					  
					  $code = $tour->tour_id;
                    $hotel_id = $tour->hotel_id;
					if($currency == 1) $price_currency= $this->lang->line("irr_caps"); else $price_currency = $this->lang->line("usd_caps");
					
					if($tour->flag_discount == 1) $spcl_offer = 'sprice'; else $spcl_offer = '';
					
					if($tour->food_type == 1){
						$food_type = $this->lang->line("food_type_breakfast_only");
					}elseif($tour->food_type == 2){
						$food_type = $this->lang->line("food_type_all_caps");
					}elseif($tour->food_type == 3){
						$food_type = $this->lang->line("food_type_uall_caps");
					}elseif($tour->food_type == 4){
						$food_type = $this->lang->line("food_type_breakfast_lunch");
					}elseif($tour->food_type == 5){
						$food_type = $this->lang->line("food_type_breakfast_dinner");
					}else{
						$food_type = "";
					}
					
					$hotel_name_length = strlen($tour->name);
					if($hotel_name_length > 35)
						$hotel_name = substr($tour->name,0,35).' ...';
					else
						$hotel_name = $tour->name;
					
					$availability = 1;	
					if($tour->single_qty == 0 && $tour->double_qty == 0 && $tour->triple_qty == 0){
						$availability = 0;
					}
					
					$valid_trans = 1;	
					if($tour->flight_count == '' && $tour->cruise_count == 0 && $tour->train_count == 0 && $tour->bus_count == 0){
						$valid_trans = 0;
					}
					
					
                ?>

<div class="hotellist hotel-wrap col-xs-12 col-sm-4 col-lg-3 nopadding item list-group-item">
  <div class="col-md-12 col-sm-12 col-xs-12 nopadding full_width">
    <div class="description h-card resp-module">
      <div class="image-and-details thumbnail">
        
		 <div class="col-md-4 col-sm-4  col-xs-12 nopadding_right grid_desc grid-box2">
		
		<div class="col-md-2 col-sm-2 col-xs-4 nopadding grid_image grid-box">
          <div class="image carimage"> <span><a  <?php if($tour->status == 1 && $availability == 1 && $valid_trans == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($this->encrypt->encode($tour->master_id)));?>" <?php } ?>><i <?php echo $trans_logo;?> aria-hidden="true"></i></a>
            </span> </div>
        </div>
		

<div class="col-md-3 col-sm-4 col-xs-4  nopadding grid_image grid-box">
    <a class="starrate"  <?php if($tour->status == 1 && $availability == 1 && $valid_trans == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($this->encrypt->encode($tour->master_id)));?>" <?php } ?>>
		<div class="col-md-4 nopadding">
			<div class="mn_rating <?php echo $fa_content;?>">
				<?php $final_rating = $tour->hotel_rating;?>
				<?php echo $final_rating;?><img alt="" src="<?php echo base_url('assets/images/star.png')?>">
			</div>
		</div>
    </a> 
</div>
		
		
		
<div class="col-md-7 col-sm-6  col-xs-4  nopad grid_desc grid-box">
	<div class="col-md-12 nopadding">
		<div class="col-md-12 col-sm-3 nopadding grid_desc">
			<div class="mn_resultc">
				<a data-target-blank="" <?php if($tour->status == 1 && $availability == 1 && $valid_trans == 1) { ?> href="<?php echo base_url('tour/details/'.base64_encode($this->encrypt->encode($tour->master_id)));?>" <?php } ?>>
				<h4><img height="30" width="65" alt="" src="<?php echo upload_url('/hotel/logo/'.$tour->logo);?>" class="u-photo list-group-image"><?php echo $hotel_name;?> </h4>
				</a>
			</div>
		</div>
	</div>
</div>
		
		
		</div>
		
		
		
		
        <div class="col-md-8 col-sm-8 col-xs-12  nopadding_right grid_desc grid-box2">
          <div class="col-md-12 nopadding"> 
            <div class="col-md-3 col-sm-3 col-xs-3 nopadding girdinfo ">
			<div class="tools">
				<a style="color: #333 !important;"   <?php if($tour->status == 1 && $availability == 1 && $valid_trans == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($this->encrypt->encode($tour->master_id)));?>" <?php } ?>  class="tooltips" >
					<img width="25" src="<?php echo base_url('assets/images/dinner.png');?>">
					<span><?php echo $food_type;?></span>
				</a>
			</div>
            </div>
			<a style="color: #333 !important;"   <?php if($tour->status == 1 && $availability == 1 && $valid_trans == 1) { ?>href="<?php echo base_url('tour/details/'.base64_encode($this->encrypt->encode($tour->master_id)));?>" <?php } ?>>
            <div class="col-md-2 col-sm-2 col-xs-2 nopadding mn_ro girdinfo">
              <div class="mn_neigh"> <span class="<?php echo $fa_content;?>">  <?php echo ($tour->no_of_night+1).' '.$this->lang->line("no_of_days");?>  </span> </div>
            </div>
            <div class="col-md-2 col-sm-2  col-xs-3 nopadding mn_ro girdinfo griddate">
              <div class="mn_neigh"> <span style="font-size: 17px;" class="from_date" data-fa-date="<?php echo $tour->tour_date; ?>"><?php echo $tour->tour_date;?></span> </div>
            </div>
            <div class="col-md-2 col-sm-2  col-xs-1 nopadding mn_ro text-center sout iconv">
				<?php if($tour->status == 0 || $availability == 0) { ?>
				<img class="soutimg" width="70" src="<?php echo base_url('assets/images/sout.png');?>">
				<?php } ?> 
		<?php //if($tour->)?>
		<?php
			if($flight_logos[0] != ''){
				foreach($flight_logos as $fl){ 
		?>
		<!--<i class="fa fa-plane"></i>-->
		<div class="flight_logo">
		<img  src="<?php echo upload_url('/tour/airline_logo/'.$fl);?>">
		</div>
		<?php } }elseif($tour->bus_count > 0){ for($i=0;$i<$tour->bus_count;$i++){ ?>
			<i  class="fa fa-bus other_trans" aria-hidden="true"></i>
		<?php } }elseif($tour->train_count > 0){ for($i=0;$i<$tour->train_count;$i++){ ?>
			<i  class="fa fa-train other_trans" aria-hidden="true"></i>
		<?php } }elseif($tour->cruise_count > 0){ for($i=0;$i<$tour->cruise_count;$i++){ ?>
			<i  class="fa fa-ship other_trans" aria-hidden="true"></i>
		<?php } } ?>
		
        
			</div>
            <div class="col-md-3 col-sm-3 col-xs-3 nopadding price_det">
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

<?php
  $max_range = max($price_store);
  $min_range = min($price_store);
  

}else {
  
  ?>
<span class="no_result"> <?php echo $this->lang->line("no_search_result"); ?></span>
<?php } ?>
<input id="all_count"  name="all_count" type="hidden" value="<?php if($tours){ echo str_replace("{{count}}", count($tours), $this->lang->line("results_found"));}else { echo $this->lang->line("no_search_result");}?>">
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


 