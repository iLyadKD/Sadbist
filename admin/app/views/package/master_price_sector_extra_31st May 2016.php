<!--<script>
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



  
</script>-->
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
	 
      <th>Single Room</th>
      <th>Double Room</th>
      <th>Triple Room</th>
      <th>Infant</th>
      <th>Child (2-6)</th>
      <th>Child (6-12)</th>
      <th>Child (12-16)</th>
      <th>Handling Fee</th>
	  
    </tr>
    <?php if($price) { $i=0; foreach ($price as  $row) {  $i++; if($row->price_type == 1) $type = "Retail";elseif($row->price_type == 2) $type = "Cost";   ?>
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
      <input readonly  type="text"  value="<?php echo $row->tour_date;?>" class="form-control" placeholder="date" value="" name="price[tour_date][]" data-rule-required='true' data-rule-money="true"> 
      <input readonly  type="text" class="form-control" placeholder="day" value="<?php echo $row->tour_day;?>" name="price[tour_day][]" data-rule-required='true' >
       <?php } else { ?>
	  <input readonly  type="hidden"  value="<?php echo $row->tour_date;?>" class="form-control" placeholder="date" value="" name="price[tour_date][]" data-rule-required='true' data-rule-money="true"> 
      <input readonly  type="hidden" class="form-control" placeholder="day" value="<?php echo $row->tour_day;?>" name="price[tour_day][]" data-rule-required='true' >
	  <?php } ?>
      <th>
         
        <input type="hidden" class="form-control" value="<?php echo $row->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
        <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->single;?>" name="price[single][]" data-rule-required='true' data-rule-money="true"> 
        <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty;?>" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input  type="text" data-value="<?php echo $row->id;?>" id="double_value<?php echo $row->id;?>" value="<?php echo $row->doubles;?>" class="form-control double_value" placeholder="Cost" value="" name="price[doubles][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty;?>" name="price[double_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->triple;?>" name="price[triple][]" data-rule-required='true'  data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty;?>" name="price[triple_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->infants;?>" name="price[infants][]"  data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[infants_qty][]" >
       </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->twotosix;?>" name="price[twotosix][]" data-rule-required='true'  data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[twotosix_qty][]"   ></th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->sixtotwelve;?>" name="price[sixtotwelve][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[sixtotwelve_qty][]" ></th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->twelvetosixteenth;?>" name="price[twelvetosixteenth][]"  data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[twelvetosixteenth_qty][]"  >      </th>

      <th><input  type="text" class="form-control" placeholder="Handling Fee" value="<?php echo $row->handle_charge;?>" name="price[handle_charge][]"   data-rule-required='true'  data-rule-money="true" ></th>
    </tr>
    <?php  }} ?>

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