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
    <?php if($price) {   $i=0; foreach ($price as  $row) {   $i++; if($row->price_type == 1) { $type = "Retail"; $visibility = 'text';}elseif($row->price_type == 2) { $type = "Cost"; $visibility = 'hidden';}  ?>
    <?php  if($i % 2 != 0) { ?>
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
         
        <input type="hidden" class="form-control" value="<?php echo $row->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
        <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" value="<?php echo $row->single;?>" name="price[single][]" data-rule-required='true' > 
        <input  type="<?php echo $visibility;?>" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty;?>" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input  type="text" data-value="<?php echo $row->id;?>" id="double_value<?php echo $row->id;?>" value="<?php echo $row->doubles;?>" class="form-control double_value money" placeholder="Cost" value="" name="price[doubles][]" data-rule-required='true' data-rule-money="false" > 
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
                                <a href="<?php echo base_url('package/edit/'.$tour_id); ?>"><button class="btn btn-danger" type="button">
                                <i class="icon-reply"></i>
                                Go Back
                              </button></a>
								<button class='btn btn-primary' name="create" type='submit'> <i class='icon-save'></i> Update price</button>
								 
								
                                </div>
                              </div>
                            </div>