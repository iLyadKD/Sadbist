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

function get_price_room_master(base_price,discount_price,commission_price,id) {
	if (discount_price != '') {
        if (discount_price != 0) {
            base_price = discount_price;
        }
    }
	if (commission_price != '' && base_price != '') {
        var val = $("#commission_box"+id).val();
		var cal = parseInt($("#hid_base_price"+id).val())*(parseInt(val)/100);
		var final_rate = parseInt($("#hid_base_price"+id).val())-parseInt(cal);
		
    }
	
	$("#hid_cost_price"+id).val(final_rate);
	
	$("#hid_base_price"+id).val(base_price);
	
	if ($("#double_value"+id).length > 0) {
		$("#double_value"+id).val($("#hid_base_price"+id).val());
		
		var tc = $("#tc"+id).val();
		var get_change = parseInt($("#double_value"+id).val())+parseInt(tc);
		$("#hid_total_tour_price"+id).val(get_change);
		
		
    }
	if ($("#double_cost_value"+id).length > 0) {
		$("#double_cost_value"+id).val($("#hid_cost_price"+id).val());
    }
	
	
}	
	
if($(".base_price").length > 0){
	$(".base_price").on( 'keyup change keypress', function () {
		if($(this).hasClass("money")){
                if(event.which >= 37 && event.which <= 40) return;
				$(this).val(function(index, value) {
					return value
					.replace(/\D/g, "")
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					;
				});
            }	
		var id = $(this).data('value');
		
		var base_price = $("#base_price"+id).val();
		var discount_price = $("#discount_price"+id).val();
		var commission_price = $("#commission_box"+id).val();
		get_price_room_master(base_price,discount_price,commission_price,id);
	});
}

	$('.net_com').on('change click', function() {
		var id = $(this).data('id');
		var pre_id = parseInt(id)-1;
		
		$("#percentage_box"+id).val('');
		$("#dollar_box"+id).val('');
		
		
		if($(this).val() =="NET"){
			$("#percentage_dollar"+id).hide().val('');
			$("#double_value"+id).val($("#double_value"+pre_id).val());
			$("#hid_total_tour_price"+id).val($("#hid_total_tour_price"+pre_id).val());
		}
		else{
			$("#percentage_dollar"+id).show();
		}
	});
	
	$(".cb,.dc").on('keyup change keypress', function () {
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
		var discount_price = '';
		
		
		
		if (val == 'p') {
			var cal = parseInt(base_price)*(parseInt(percentage_price)/100);
			var final_rate = parseInt(base_price)-parseInt(cal);
        }else{
			var cal = dollar_price;
			var final_rate = cal;
		}
		
		
		if(!isNaN(final_rate)) {
		   $("#double_value"+id).val(final_rate);
		   $("#hid_total_tour_price"+id).val(parseInt(final_rate)+parseInt($("#tr"+id).val()));
        }
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
  
</style>



                           
<div class="form-group">
  <div class="col-md-12">                        
    <table class="table table-bordered">
    <tr style="background-color:#eeffe6;">
      <th>&nbsp;</th>
      <th style="width: 200px;">Date</th>
	   <th style="width: 500px;">Change Price</th>
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
      <th><?php echo $type;?></th>
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
		
		<?php if($row->price_type == 1) { $visibility = 'text'; ?>
		
		<input type="hidden" class="form-control" name="price[price_type][]" data-rule-required='true' data-rule-money="false" value="1" >
		<div class="form-group">
		<label class="col-md-2 control-label required txt_fonts"  for='validation_current'>Base Price</label>
		<div class="col-sm-4 controls">
		  <input  type="text" data-value="<?php echo $row->id;?>"  id="base_price<?php echo $row->id;?>"   class='form-control base_price money'    value="<?php echo $row->overall_tour_price;?>" name="price[overall_tour_price][]" placeholder="Base price" data-rule-required='true'  >
		  <input  type="hidden"  id="hid_base_price<?php echo $row->id;?>" value="<?php echo $row->overall_tour_price;?>">
		  
			
		</div>
		<label class="col-md-2 control-label txt_fonts"  for='validation_current'>Discount Price</label>
		<div class="col-sm-4 controls">
		  <input  type="text" data-value="<?php echo $row->id;?>" id="discount_price<?php echo $row->id;?>"  class='form-control base_price money' value="<?php  /*if($row->hotel_id != $df) echo 0; else*/ echo $row->discount_price;?>"  name="price[discount_price][]"  placeholder="Discount price"  >
		  <input type="hidden" name="price[percentage][]" value="<?php echo $row->percentage;?>">
		 <input type="hidden" name="price[dollar][]" value="<?php echo $row->dollar;?>"> 
		<input id="tr<?php echo $row->id;?>" type="hidden"  value="<?php echo $row->total_tour_price-$row->doubles;?>">
		  
		</div>
		</div>
		<?php }elseif($row->price_type == 2) { $visibility = 'hidden';?>
		<input type="hidden" class="form-control" name="price[price_type][]" data-rule-required='true' data-rule-money="false" value="2" >
			<div class="form-group">
				<div class="col-sm-12 controls">
				  <div class="radio col-sm-3">
					<label><input type="radio" class="net_com" data-id="<?php echo $row->id;?>"  name="<?php echo $row->id;?>"  value="NET" <?php if($row->percentage== 0 && $row->dollar== 0) { echo "checked";} ?>>Net</label>
				  </div>
				  <div class="radio col-sm-3">
					<label><input type="radio" class="net_com"  data-id="<?php echo $row->id;?>" name="<?php echo $row->id;?>" value="COMMISSION"  <?php if($row->percentage!= 0 || $row->dollar!= 0) { echo "checked";} ?> >Commission</label>
				  </div>
				 
				  
				  
				 
				  
				  
				  
				<div id="percentage_dollar<?php echo $row->id;?>" class="radio col-sm-10 <?php if($row->percentage== 0 && $row->dollar== 0) echo 'display_none'; else echo '';?>">
					<div class="radio col-sm-6">
						<input type="text" data-id="<?php echo $row->id;?>" data-value="p"  value="<?php echo $row->percentage;?>" id="percentage_box<?php echo $row->id;?>"   class='form-control cb money'  name="price[percentage][]"  placeholder="%" data-rule-required='true' pattern="[0-9]*"  >
						<input  name="price[overall_tour_price][]" type="hidden"  id="hid_cost_price<?php echo $row->id;?>" value="<?php echo $row->overall_tour_price;?>">
						<input type="hidden" name="price[discount_price][]" value="<?php echo $row->discount_price;?>">
					</div>
					<div class="radio col-sm-6">
						<input type="text" data-id="<?php echo $row->id;?>"   data-value="d" value="<?php echo $row->dollar;?>" id="dollar_box<?php echo $row->id;?>"   class='form-control dc money'  name="price[dollar][]"  placeholder="$" data-rule-required='true' pattern="[0-9]*"  >
						<input id="tr<?php echo $row->id;?>" type="hidden"  value="<?php echo $row->total_tour_price-$row->doubles;?>">
					</div>
				</div>
				<!--<br /><br /><br /><br />
				<label class="col-md-4 control-label txt_fonts"  for='validation_current'>Counter Bonus</label>
				<div class="col-sm-6">
				 <input type="text"   class='form-control money' type="text" name="tour[counter_bonus][]"  placeholder="counter bonus" value="<?php echo $tour->counter_bonus;?>" data-rule-money="false">
				</div>-->
				  
				  
				  
				  
				  
				  
				  
				  
				</div>  
			</div>
		<?php } ?>
		
	  </th>
      <th>
        
        <input type="hidden" class="form-control" value="<?php echo $row->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
        <input  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->single;?>" name="price[single][]" data-rule-required='true' data-rule-money="false"> 
        <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty;?>" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input  type="text" data-value="<?php echo $row->id;?>" id="double_value<?php echo $row->id;?>" value="<?php echo $row->doubles;?>" class="form-control double_value money" placeholder="Cost" value="" name="price[doubles][]" data-rule-required='true' data-rule-money="false" readonly> 
      <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty;?>" name="price[double_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->triple;?>" name="price[triple][]" data-rule-required='true'  data-rule-money="false"> 
      <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty;?>" name="price[triple_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->infants;?>" name="price[infants][]"  data-rule-required='true' data-rule-money="false"> 
       </th>
       <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twotosix;?>" name="price[twotosix][]" data-rule-required='true'  data-rule-money="false"> 
      </th>
       <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->sixtotwelve;?>" name="price[sixtotwelve][]" data-rule-required='true' data-rule-money="false"> 
      </th>
       <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="<?php echo $row->twelvetosixteenth;?>" name="price[twelvetosixteenth][]"  data-rule-required='true' data-rule-money="false"> 
            </th>

      <th><input  type="text" class="form-control money" placeholder="Handling Fee" value="<?php echo $row->handle_charge;?>" name="price[handle_charge][]"   data-rule-required='true'  data-rule-money="false" ></th>
    </tr>
	
    <?php  } ?>
	
	<?php } ?>

    </table>
  </div>
</div>
 <div class='form-actions' style='margin-bottom:0'>
                              <div class='row'>
                                <div class='col-sm-9 col-sm-offset-3'>
                                <button class='btn btn-primary' name="create" type='submit'> <i class='icon-save'></i> Update price</button>                                                    
                                </div>
                              </div>
                            </div>