


<script>
$(document).ready(function(){
	var hotel_id = $("#selected_hotel").val();
	var base_url = $("head").data("base-url");
	var selected_type = '<?php echo $selected_type;?>';
	$.ajax({
		method: "POST",
		url: base_url + "hotel/get_hotel_room_types",
		data: { hotel_id: hotel_id,selected_type:selected_type }
	})
	.done(function( msg ) {
	  $(".select_room_type").html(msg);
		
		
	
	});
	
	$("input.money").each(function(){
		var input = $(this).val();			
		var cnvrt = money(input);
		$(this).val(cnvrt);
	});
	
	
});


function generate_master_price(id,post_id,double_value,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value,key) {
		
		var base_flag = $("#base_flag"+id).val();
		
		var discount_price = $("#discount_price"+id).val();
		
		if (discount_price != '' && discount_price != 0) {
				base_price = discount_price;
		}
		if (key == 'double_value') {
			var base_price = double_value;
        }else{
			var base_price = $("#base_price"+id).val();
		}
		if (key == 'single_value') var base_price = single_value;
		if (key == 'triple_value') var base_price = triple_value;
		if (key == 'infants_value') var base_price = infants_value;
		if (key == 'twotosix_value') var base_price = twotosix_value;
		if (key == 'sixtotwelve_value') var base_price = sixtotwelve_value;
		if (key == 'twelvetosixteenth_value') var base_price = twelvetosixteenth_value;
			
       
		
		
		var percentage_box = $("#percentage_box"+post_id).val();
		var dollar_box = $("#dollar_box"+post_id).val();
		
		if(percentage_box !='' && percentage_box != 0) {
			var val = $("#percentage_box"+post_id).val().replace(/,/g , "");
			var cal = parseInt(base_price.replace(/,/g , ""))*(parseInt(val)/100);
			var final_rate = parseInt(base_price.replace(/,/g , ""))-parseInt(cal);
			
		}
		if (dollar_box != '' && dollar_box != 0) {
			var val = $("#dollar_box"+post_id).val().replace(/,/g , "");
			var final_rate = val;
		}
		if (!isNaN(final_rate)) {
			$("#hid_cost_price"+post_id).val(final_rate);
			
			$("#"+key+post_id).val(money(final_rate));
		}
		
		
		
		/*if (key == 'double_value') {
            $("#hid_base_price"+id).val(base_price);
        }*/
		if (base_flag == 1) {
			$("#base_price"+id).val(base_price);
			$("#hid_total_tour_price"+id).val(base_price);
			
		}else{
			
			$("#discount_price"+id).val(base_price);
			$("#hid_total_tour_price"+id).val(base_price);
			
		}
}

function generate_master_cost_price(id,pre_id,percentage_box,dollar_box) {
		$(".cost_class").each(function() {
			var key = $(this).data('key');
			var field = $("#"+key+pre_id).val();
			if(percentage_box !='' && percentage_box != 0) {
				var val = $("#percentage_box"+id).val().replace(/,/g , "");
				var cal = parseInt(field.replace(/,/g , ""))*(parseInt(val)/100);
				var final_rate = parseInt(field.replace(/,/g , ""))-parseInt(cal);
					
			}
			if (dollar_box != '' && dollar_box != 0) {
				var val = $("#dollar_box"+id).val().replace(/,/g , "");
				var final_rate = val;
			}
			$("#"+key+id).val(money(final_rate));
			
		});
	}



$(document).on( 'change keyup keypress','.double_value,.single_value,.triple_value,.infants_value,.twotosix_value,.sixtotwelve_value,.twelvetosixteenth_value', function () {
		if($(this).hasClass("money")){
                if(event.which >= 37 && event.which <= 40) return;
				$(this).val(function(index, value) {
					return value
					.replace(/\D/g, "")
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					;
				});
            }
			
			
		var key = $(this).data('key'); 
		var id = $(this).data('value');
		var post_id = id+parseInt(1);
		var double_value = $("#double_value"+id).val();
		var single_value = $("#single_value"+id).val();
		var triple_value = $("#triple_value"+id).val();
		var infants_value = $("#infants_value"+id).val();
		var twotosix_value = $("#twotosix_value"+id).val();
		var sixtotwelve_value = $("#sixtotwelve_value"+id).val();
		var twelvetosixteenth_value = $("#twelvetosixteenth_value"+id).val();
		
		generate_master_price(id,post_id,double_value,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value,key);
});

$(document).on('change click','.net_com',function(){
		var post_id = $(this).data('id');
		var id = parseInt(post_id)-1;
		
		$("#percentage_box"+post_id).val('');
		$("#dollar_box"+post_id).val('');
		
		
		if($(this).val() =="NET"){
			$("#percentage_dollar"+post_id).hide().val('');
			$("#hid_total_tour_price"+post_id).val($("#hid_total_tour_price"+id).val());
			
			$(".cost_class").each(function() {
				var key = $(this).data('key');
				$("#"+key+post_id).val($("#"+key+id).val());
			});
			
			
			
			
		}
		else{
			$("#percentage_dollar"+post_id).show();
		}
		
});

$(document).on( 'change keyup keypress','.cb,.dc', function () {
		if($(this).hasClass("money")){
                if(event.which >= 37 && event.which <= 40) return;
				$(this).val(function(index, value) {
					return value
					.replace(/\D/g, "")
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					;
				});
            }	
		var id = $(this).data('id');
		var val = $(this).data('value');
		
		if (val == 'p') {
            $("#dollar_box"+id).val(0);
			var percentage_price = $("#percentage_box"+id).val();
        }else{
			$("#percentage_box"+id).val(0);
			var dollar_price = $("#dollar_box"+id).val();
		}
		var pre_id = parseInt(id)-1;
		var base_price = $("#double_value"+pre_id).val();
		var discount_price = $("#discount_price"+pre_id).val();
		var percentage_box = $("#percentage_box"+id).val();
		var dollar_box = $("#dollar_box"+id).val();		
		
		generate_master_cost_price(id,pre_id,percentage_box,dollar_box);
	});

	

	
	
	function money(val){
		while (/(\d+)(\d{3})/.test(val.toString())){
		  val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
		}
		return val;
	}
	
	$(document).on("keyup", 'input.money', function(event) {
		if(event.which >= 37 && event.which <= 40) return;
		$(this).val(function(index, value) {
			return value
			.replace(/\D/g, "")
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
			;
		});
	});
	
  
</script>
<style>
  .txt_fonts {
	font-size: 10px;
  }
  
  .label_th th { font-size: 12px; font-weight: 500; white-space: nowrap; }
  .label_th th .form-control { font-size: 12px; font-weight: 500; white-space: nowrap; }
</style>



                           
<div class="form-group">
  <div class="col-md-12">                        
    <table class="table table-bordered label_th">
    <tr style="background-color:#eeffe6;">
      <th>&nbsp;</th>
      <th style="width: 150px;">Date</th>
	   <th style="width: 150px;">Change Price</th>
	  <th>Single Room</th>
      <th>Double Room</th>
      <th>Triple Room</th>
      <th>Infant</th>
      <th>Child (2-6)</th>
      <th>Child (6-12)</th>
      <th>Child (12-16)</th>
      <th>Handling Fee</th>
	  
    </tr>
    <?php if($price) {  $i=0; foreach ($price as  $row) {   $i++; if($row->price_type == 1) $type = "Retail";elseif($row->price_type == 2) $type = "Cost";  ?>
    <?php if($i % 2 != 0) { ?>
	<tr>
		<th colspan=11><p>&nbsp;</p></th>
	</tr>
	<?php } ?>
	<tr>
	  <input type="hidden" class="form-control" name="price[id][]" value="<?php echo $row->id;?>">	
      <th>
	  <?php echo $type;?>
	  </th>
      <th>
      <?php if($i % 2 != 0) { ?>
	  <input readonly  type="text"  value="<?php echo $row->tour_date;?>" class="form-control" placeholder="date" value="" name="price[tour_date][]" data-rule-required='true' data-rule-money="false"> 
      <input readonly  type="text" class="form-control" placeholder="day" value="<?php echo $row->tour_day;?>" name="price[tour_day][]" data-rule-required='true' >
	  <?php } else { ?>
	  <input readonly  type="hidden"  value="<?php echo $row->tour_date;?>" class="form-control" placeholder="date" value="" name="price[tour_date][]" data-rule-required='true' data-rule-money="false"> 
      <input readonly  type="hidden" class="form-control" placeholder="day" value="<?php echo $row->tour_day;?>" name="price[tour_day][]" data-rule-required='true' >
	  <?php } 
?>
	  
      </th>
	  <th>
		<input  type="hidden" name="price[total_tour_price][]"  id="hid_total_tour_price<?php echo $row->id;?>" value="<?php echo $row->total_tour_price;?>">
		  
		 
		  <?php
			if($row->discount_price != 0)
				$price_tr = $row->total_tour_price - $row->discount_price;
			else
				$price_tr = $row->total_tour_price - $row->overall_tour_price;
		  ?>
		  <input  type="hidden"  id="tc<?php echo $row->id;?>" value="<?php echo $price_tr;?>">
		
		<?php if($row->price_type == 1) { $visibility = 'text'; $cost_class = '';?>
		
		<input type="hidden" class="form-control" name="price[price_type][]" data-rule-required='true' data-rule-money="false" value="1" >
		<div class="form-group">
		<label class="col-md-12 control-label required txt_fonts"  for='validation_current'>Base Price</label>
		<div class="col-sm-12 controls">
		  <input readonly  type="text" data-value="<?php echo $row->id;?>"  id="base_price<?php echo $row->id;?>"   class='form-control base_price money'    value="<?php echo $row->overall_tour_price;?>" name="price[overall_tour_price][]" placeholder="Base price" data-rule-required='true'  >
		  <input  type="hidden"  id="hid_base_price<?php echo $row->id;?>" value="<?php echo $row->overall_tour_price;?>">
		  
			
		</div>
		<?php
			if($row->discount_price == 0){
				$base_flag = 1;
			}else{
				$base_flag = 0;
			}
		?>
		<input type="hidden" id="base_flag<?php echo $row->id;?>" value="<?php echo $base_flag;?>">
		<label class="col-md-12 control-label txt_fonts"  for='validation_current'>Discount Price</label>
		<div class="col-sm-12 controls">
		  <input readonly  type="text" data-value="<?php echo $row->id;?>" id="discount_price<?php echo $row->id;?>"  class='form-control base_price money' value="<?php  /*if($row->hotel_id != $df) echo 0; else*/ echo $row->discount_price;?>"  name="price[discount_price][]"  placeholder="Discount price"  >
		  <input type="hidden" name="price[percentage][]" value="<?php echo $row->percentage;?>">
		 <input type="hidden" name="price[dollar][]" value="<?php echo $row->dollar;?>"> 
		<input id="tr<?php echo $row->id;?>" type="hidden"  value="<?php echo $row->total_tour_price-$row->doubles;?>">
		</div>
		
		<label class="col-md-12 control-label txt_fonts"  for='validation_current'>Counter Bonus</label>
		<div class="col-sm-12 controls">
		  <input   type="text" data-value="<?php echo $row->id;?>"   class='form-control money' value="<?php  echo $row->counter_bonus;?>"  name="price[counter_bonus][]"  placeholder="Counter Bonus"  >		  
		</div>
		
		
		
		</div>
		<?php }elseif($row->price_type == 2) { $visibility = 'hidden'; $cost_class = 'cost_class';?>
		<input type="hidden" class="form-control" name="price[price_type][]" data-rule-required='true' data-rule-money="false" value="2" >
			<div class="form-group">
				<div class="col-sm-12 controls">
				  <div class="radio col-sm-12">
					<label><input  type="radio" class="net_com" data-id="<?php echo $row->id;?>"  name="<?php echo $row->id;?>"  value="NET" <?php if($row->percentage== 0 && $row->dollar== 0) { echo "checked";} ?>>Net</label>
				  </div>
				  <div class="radio col-sm-12">
					<label><input  type="radio" class="net_com"  data-id="<?php echo $row->id;?>" name="<?php echo $row->id;?>" value="COMMISSION"  <?php if($row->percentage!= 0 || $row->dollar!= 0) { echo "checked";} ?> >Commission</label>
				  </div>
				 
				  
				  
				 
				  
				  
				  
				<div id="percentage_dollar<?php echo $row->id;?>" class="radio col-sm-12 <?php if($row->percentage== 0 && $row->dollar== 0) echo 'display_none'; else echo '';?>" style=" padding:0px;">
					<div class="radio col-sm-6" style=" padding-left:0px;">
						<input type="text" data-id="<?php echo $row->id;?>" data-value="p"  value="<?php echo $row->percentage;?>" id="percentage_box<?php echo $row->id;?>"   class='form-control cb money'  name="price[percentage][]"  placeholder="%" data-rule-required='true'   >
						<input  name="price[overall_tour_price][]" type="hidden"  id="hid_cost_price<?php echo $row->id;?>" value="<?php echo $row->overall_tour_price;?>">
						<input type="hidden" name="price[discount_price][]" value="<?php echo $row->discount_price;?>">
					</div>
					<div class="radio col-sm-6" style=" padding-left:0px;  padding-right:0px;">
						<input type="text" data-id="<?php echo $row->id;?>"   data-value="d" value="<?php echo $row->dollar;?>" id="dollar_box<?php echo $row->id;?>"   class='form-control dc money'  name="price[dollar][]"  placeholder="$" data-rule-required='true'   >
						<input id="tr<?php echo $row->id;?>" type="hidden"  value="<?php echo $row->total_tour_price-$row->doubles;?>">
						<input   type="hidden"    class='form-control money' value="0"  name="price[counter_bonus][]"  >
					</div>
				</div>
				
				  
				  
				  
				  
				  
				  
				  
				  
				</div>  
			</div>
		<?php } ?>
		
	  </th>
	  
      <th>
        
        <input type="hidden" class="form-control" value="<?php echo $row->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
        <input data-key='single_value' data-value="<?php echo $row->id;?>"  type="text" class="form-control single_value money <?php echo $cost_class;?>" id="single_value<?php echo $row->id;?>" placeholder="Cost" value="<?php echo $row->single;?>" name="price[single][]" data-rule-required='true' data-rule-money="false"> 
        <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty;?>" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input data-key='double_value' type="text" data-value="<?php echo $row->id;?>" id="double_value<?php echo $row->id;?>" value="<?php echo $row->doubles;?>" class="form-control double_value money <?php echo $cost_class;?>" placeholder="Cost" value="" name="price[doubles][]" data-rule-required='true' data-rule-money="false" > 
      <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty;?>" name="price[double_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input data-key='triple_value' data-value="<?php echo $row->id;?>"  type="text" class="form-control triple_value money <?php echo $cost_class;?>" id="triple_value<?php echo $row->id;?>" placeholder="Cost" value="<?php echo $row->triple;?>" name="price[triple][]" data-rule-required='true'  data-rule-money="false"> 
      <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty;?>" name="price[triple_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input data-key='infants_value' data-value="<?php echo $row->id;?>"  type="text" class="form-control infants_value money <?php echo $cost_class;?>" id="infants_value<?php echo $row->id;?>" placeholder="Cost" value="<?php echo $row->infants;?>" name="price[infants][]"  data-rule-required='true' data-rule-money="false"> 
       </th>
       <th>
      <input data-key='twotosix_value' data-value="<?php echo $row->id;?>"  type="text" class="form-control twotosix_value money <?php echo $cost_class;?>" id="twotosix_value<?php echo $row->id;?>" placeholder="Cost" value="<?php echo $row->twotosix;?>" name="price[twotosix][]" data-rule-required='true'  data-rule-money="false"> 
      </th>
       <th>
      <input data-key='sixtotwelve_value' data-value="<?php echo $row->id;?>" type="text" class="form-control sixtotwelve_value money <?php echo $cost_class;?>" id="sixtotwelve_value<?php echo $row->id;?>" placeholder="Cost" value="<?php echo $row->sixtotwelve;?>" name="price[sixtotwelve][]" data-rule-required='true' data-rule-money="false"> 
      </th>
       <th>
      <input data-key='twelvetosixteenth_value' data-value="<?php echo $row->id;?>" type="text" class="form-control twelvetosixteenth_value money <?php echo $cost_class;?>" id="twelvetosixteenth_value<?php echo $row->id;?>" placeholder="Cost" value="<?php echo $row->twelvetosixteenth;?>" name="price[twelvetosixteenth][]"  data-rule-required='true' data-rule-money="false"> 
            </th>

      <th><input  type="<?php echo $visibility;?>" class="form-control money" placeholder="Handling Fee" value="<?php echo $row->handle_charge;?>" name="price[handle_charge][]"   data-rule-required='true'  data-rule-money="false" ></th>
    </tr>
	
    <?php  } ?>
	
	<?php } ?>

    </table>
  </div>
</div>
 <div class='form-actions' style='margin-bottom:0'>
                              <div class='row'>
                                <div class='col-sm-9 col-sm-offset-3'>
                                <a href="<?php echo base_url('package/edit/'.$tour_id); ?>"><button class="btn btn-danger" type="button">
                                <i class="icon-reply"></i>
                                Go Back
                              </button></a>
								<button class='btn btn-primary' name="create" type='submit'> <i class='icon-save'></i> Update price</button>
								 
								
                                </div>
                              </div>
                            </div>