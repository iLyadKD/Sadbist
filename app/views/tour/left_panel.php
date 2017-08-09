  <div class="col-md-2 col-sm-2 col-xs-12 filters nopadding_left">
                <!--<h4>Filters</h4>-->
                <div class="filter_block">
                 <!-- <div class="price_filter">
                    <div data-toggle="collapse" data-target="#collapse2" class="filterbg pricebg collapsebtn">
                      <h1>Price <span class="accordprefix"> <img alt="" src="<?php echo base_url('assets/images/minus.png');?>"> </span> </h1>
                    </div>
                    <div id="collapse2" class="pricesection in">
                        <form name="fliter" id="fliter" method="post">
                            <div class="infiltrbox htlrub price_slider1">
                <div class="price_slider1">
                      <input type="text" id="amount" class="level" name="amount" value="" readonly>
					  <input type="hidden" id="std_range" class="level" name="std_range" value="" readonly>
					  <input type="hidden" id="range" class="level" name="range" value="">
					  <input type="hidden" id="slider_flag" value="0">
                    <div id="slider-range"></div>
                    </div>
              </div>
                    </div>
                  </div>-->
				 <form name="fliter" id="fliter" method="post">
                  
                  <div class="Fare_type">
                    <div data-toggle="collapse" data-target="#collapse1" class="filterbg farebg collapsebtn">
                      <h1><?php echo $this->lang->line("star_rating"); ?> <span class="accordprefix"> <img alt="" src="<?php echo base_url('assets/images/minus.png');?>"> </span> </h1>
                    </div>
                    <div style="padding-bottom:15px;" id="collapse1" class="pricesection in">
                       <div class="check_box">
                        <input class="rating_variables" type="checkbox" id="fare6" name="rating[]" value="6">
                        <label for="fare6"> <span><img alt="" src="<?php echo base_url('assets/images/star_rating_6.png');?>"></span> </label>
                        <div class="clearfix"></div>
                      </div>
					  <div class="check_box">
                        <input class="rating_variables" type="checkbox" id="fare5" name="rating[]" value="5">
                        <label for="fare5"> <span><img alt="" src="<?php echo base_url('assets/images/star_rating_5.png');?>"></span> </label>
                        <div class="clearfix"></div>
                      </div>
                      <div class="check_box">
                        <input class="rating_variables" type="checkbox" id="fare4" name="rating[]" value="4">
                        <label for="fare4"> <span><img alt="" src="<?php echo base_url('assets/images/star_rating_4.png');?>"></span> </label>
                        <div class="clearfix"></div>
                      </div>
                      <div class="check_box">
                        <input class="rating_variables" type="checkbox" id="fare3" name="rating[]" value="3">
                        <label for="fare3"> <span><img alt="" src="<?php echo base_url('assets/images/star_rating_3.png');?>"></span> </label>
                        <div class="clearfix"></div>
                      </div>
                      <div class="check_box">
                        <input class="rating_variables" type="checkbox" id="fare2" name="rating[]" value="2">
                        <label for="fare2"> <span><img alt="" src="<?php echo base_url('assets/images/star_rating_2.png');?>"></span> </label>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
	<div class="air_lines">
		<div data-toggle="collapse" data-target="#collapse3" class="filterbg pricebg collapsebtn">
			<h1><?php echo $this->lang->line("transportation"); ?> <span class="accordprefix"> <img alt="" src="<?php echo base_url('assets/images/minus.png');?>"> </span> </h1>
		</div>
		<div style="padding-bottom:15px;" id="collapse3" class="pricesection in">
			<div class="check_box">
				<input type="checkbox" id="fare4f" name="transportation[]" value="1">
				<label for="fare4f"> <span><?php echo $this->lang->line("flight"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			<div class="check_box">
				<input type="checkbox" id="fareb" name="transportation[]" value="4">
				<label for="fareb"> <span><?php echo $this->lang->line("bus"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			<div class="check_box">
				<input type="checkbox" id="faret" name="transportation[]" value="3">
				<label for="faret"> <span><?php echo $this->lang->line("train"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
				<div class="check_box">
				<input type="checkbox" id="farec" name="transportation[]" value="2">
				<label for="farec"> <span><?php echo $this->lang->line("cruise"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
	<div class="air_lines">
		<div data-toggle="collapse" data-target="#collapse3" class="filterbg pricebg collapsebtn">
			<h1><?php echo $this->lang->line("food_service"); ?> <span class="accordprefix"> <img alt="" src="<?php echo base_url('assets/images/minus.png');?>"> </span> </h1>
		</div>
		<div style="padding-bottom:15px;" id="collapse3" class="pricesection in">
			<div class="check_box">
				<input type="checkbox" id="food1" name="food[]" value="1">
				<label for="food1"> <span><?php echo $this->lang->line("food_type_breakfast_only"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			<div class="check_box">
				<input type="checkbox" id="food4" name="food[]" value="4">
				<label for="food4"> <span><?php echo $this->lang->line("food_type_breakfast_lunch"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			<div class="check_box">
				<input type="checkbox" id="food5" name="food[]" value="5">
				<label for="food5"> <span><?php echo $this->lang->line("food_type_breakfast_dinner"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			<div class="check_box">
				<input type="checkbox" id="food2" name="food[]" value="2">
				<label for="food2"> <span><?php echo $this->lang->line("food_type_all_caps"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			<div class="check_box">
				<input type="checkbox" id="food3" name="food[]" value="3">
				<label for="food3"> <span><?php echo $this->lang->line("food_type_uall_caps"); ?></span> </label>
				<div class="clearfix"></div>
			</div>
			
			
		</div>
	</div>
                  
                  <?php if($neighbours[0]->neighbours != '') { ?>
                  <!--<div class="air_lines">
                    <div data-toggle="collapse" data-target="#collapse5" class="filterbg pricebg collapsebtn">
                      <h1><?php echo $this->lang->line("neighbourhood"); ?><span class="accordprefix"> <img alt="" src="<?php echo base_url('assets/images/minus.png');?>"> </span> </h1>
                    </div>
                    <div style="padding-bottom:15px;overflow-x: hidden;height:100px;" id="collapse5" class="pricesection in">
                    <?php $i=0; foreach($neighbours as $n){ $i++;?> 
                      <div class="check_box">
                        <input type="checkbox" id="<?php echo $n->hotel_id;?>" name="neighbours[]" value="<?php echo $n->neighbours;?>">
                        <label for="<?php echo $n->hotel_id;?>"> <span><?php echo $n->neighbours;?></span> </label>
                        <div class="clearfix"></div>
                      </div>
                      <?php } ?>
                 
                    </div>
                  </div>-->
                  <?php } ?>
                </div>
				  
	<input type="hidden" name="order_by" id="order_by" value="2"> 
	<input type="hidden" name="order_by_value" id="order_by_value" value="5"> 
	</form>
              </div>
  
