
<style>
  .room_types_area{
    font-size:10px;
  }
 
  
</style>

                           
<div class="form-group">
  <div class="col-md-12">                        
    <table class="table table-bordered">
    <tr>
      <th>&nbsp;</th>
      <th>Single Room</th>
      <th>Double Room</th>
      <th>Triple Room</th>
      <th>Infant</th>
      <th>Child (2-6)</th>
      <th>Child (6-12)</th>
      <th>Child (12-16)</th>
      <th>Handling Fee</th>
    </tr>
    <?php //if($price) { foreach ($price as  $row) { ?>
    <tr>
      <?php
          $cleanStr = str_replace( ' ', '', $room_type );  
      ?>
      <th class="title_retail<?php echo $cleanStr;?>">Retail</th>
      <th>
      <input type="hidden" class="form-control" value="<?php echo $room_type;?>" name="price[room_type][]" data-rule-required='true'>
      <input type="hidden" class="form-control" value="1" name="price[price_type][]" data-rule-required='true' data-rule-money="true" > 
      <input type="hidden" class="form-control" value="<?php echo $hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'>
        <input type="hidden" class="form-control" value="0" name="price[extra_price_flag][]"   data-rule-required='true'>

      <input  type="text" class="form-control" placeholder="Cost" value="" name="price[single][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input   type="text" id="double_value" class="form-control" placeholder="Cost" value="" name="price[doubles][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" name="price[double_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="" name="price[triple][]" data-rule-required='true'  data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" name="price[triple_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="" name="price[infants][]"  data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-"  >
       </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="" name="price[twotosix][]" data-rule-required='true'  data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-"    ></th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="" name="price[sixtotwelve][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-"  ></th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="" name="price[twelvetosixteenth][]"  data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-"   >      </th>

      <th><input  type="text" class="form-control" placeholder="Handling Fee" value="" name="price[handle_charge][]"   data-rule-required='true'  data-rule-money="true" ></th>
    </tr>
    <?php // }} ?>

    <tr>
      <th class="required">Cost</th>
      <th>
      <input type="hidden" class="form-control" value="<?php echo $room_type;?>" name="price[room_type][]" data-rule-required='true'>
      <input type="hidden" class="form-control"  name="price[price_type][]" value="2"   data-rule-required='true'> 
      <input type="hidden" class="form-control" value="<?php echo $hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
      
        <input type="hidden" class="form-control" value="0" name="price[extra_price_flag][]"   data-rule-required='true'>
      <input type="text" class="form-control" placeholder="Cost" name="price[single][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[single_qty][]"   data-rule-required='true'>
      </th>
       
       <th>
      <input type="text" class="form-control" id="double_cost_value" placeholder="Cost" name="price[doubles][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[double_qty][]"   data-rule-required='true'>        </th>
      
      
      <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[triple][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[triple_qty][]"   data-rule-required='true'>      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[infants][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability"   >      </th>
      
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[twotosix][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability"    >      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[sixtotwelve][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability"    >      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[twelvetosixteenth][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability"   >      </th>
      <th><input type="text" class="form-control" placeholder="Handling Fee" name="price[handle_charge][]"   data-rule-required='true' data-rule-money="true">      </th>
    </tr>

    <tr >
       <th colspan="9">Extra Night Price <span><input data-id="<?php echo $hotel_id.'/'.$cleanStr;?>" class="extra_checkbox_type" type="checkbox"></span></th>
    </tr>
    <tr  class="extra_price_cost_type display_none" id="retail_type<?php echo $hotel_id.$cleanStr;?>">
        <th>Retail</th>
     
      <th>
      <input type="hidden" class="form-control" value="<?php echo $room_type;?>" name="price[room_type][]" data-rule-required='true'>
        <input  type="hidden" class="form-control" value="<?php echo $hotel_id;?>" name="price[hotel_id][]"   data-rule-required='false'> 
        <input  type="hidden" class="form-control" value="1" name="price[price_type][]"   data-rule-required='false'>
        <input  type="hidden" class="form-control" value="1" name="price[extra_price_flag][]"   data-rule-required='false'>
        <input  type="text" class="form-control" placeholder="Cost"  name="price[single][]"   data-rule-required='false'> 
        <input   type="text" class="form-control" placeholder="Availability"  name="price[single_qty][]"   data-rule-required='false'>
      </th>
      <th>
      <input   type="text" class="form-control" placeholder="Cost"  name="price[doubles][]"   data-rule-required='false'> 
      <input   type="text" class="form-control" placeholder="Availability"  name="price[double_qty][]"   data-rule-required='false'>
        </th>
       
      <th>
      <input   type="text" class="form-control" placeholder="Cost"  name="price[triple][]"   data-rule-required='false'> 
      <input   type="text" class="form-control" placeholder="Availability"  name="price[triple_qty][]"   data-rule-required='false'>
      </th>
       <th>
      <input   type="text" class="form-control" placeholder="Cost"  name="price[infants][]"   data-rule-required='false'> 
      <input   value="-" type="text" class="form-control" placeholder="Availability" readonly    data-rule-required='false'>
       </th>
       <th>
      <input   type="text" class="form-control" placeholder="Cost"  name="price[twotosix][]"   data-rule-required='false'> 
      <input   type="text" class="form-control" placeholder="Availability"  readonly value="-"   data-rule-required='false'>      </th>
       <th>
      <input   type="text" class="form-control" placeholder="Cost"  name="price[sixtotwelve][]"   data-rule-required='false'> 
      <input  value="-" type="text" class="form-control" placeholder="Availability"  readonly    data-rule-required='false'>      </th>
       <th>
      <input   type="text" class="form-control" placeholder="Cost"  name="price[twelvetosixteenth][]"   data-rule-required='false'> 
      <input  readonly type="text" class="form-control" placeholder="Availability"   value="-"    data-rule-required='false'>      </th>
      <th><input    type="text" class="form-control" placeholder="Handling Fee" value="" name="price[handle_charge][]"   data-rule-required='false'>      </th>
    </tr>

    <tr  class="extra_price_price_type display_none" id="cost_type<?php echo $hotel_id.$cleanStr;?>">
      <th class="required">Cost</th>
       
      <th>
      <input type="hidden" class="form-control" value="<?php echo $room_type;?>" name="price[room_type][]" data-rule-required='true'>
        <input  type="hidden" class="form-control" value="2"  name="price[price_type][]"   data-rule-required='false'>
        <input  type="hidden" class="form-control" value="1" name="price[extra_price_flag][]"   data-rule-required='false'>
        <input  type="hidden" class="form-control" value="<?php echo $hotel_id;?>"  name="price[hotel_id][]"   data-rule-required='false'> 
        <input  type="text" class="form-control" placeholder="Cost" name="price[single][]"   data-rule-required='false'> 
        <input  type="text" class="form-control" placeholder="Availability" name="price[single_qty][]"   data-rule-required='false'>
      </th>
      <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[doubles][]"   data-rule-required='false'> 
      <input  type="text" class="form-control" placeholder="Availability" name="price[double_qty][]"   data-rule-required='false'>
       </th>
      
      <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[triple][]"   data-rule-required='false'> 
      <input  type="text" class="form-control" placeholder="Availability" name="price[triple_qty][]"   data-rule-required='false'>
      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[infants][]"   data-rule-required='false'> 
      <input readonly value="-" type="text" class="form-control" placeholder="Availability"    data-rule-required='false'>
       </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[twotosix][]"   data-rule-required='false'> 
      <input readonly value="-" type="text" class="form-control" placeholder="Availability"    data-rule-required='false'>      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[sixtotwelve][]"   data-rule-required='false'> 
      <input readonly value="-" type="text" class="form-control" placeholder="Availability"    data-rule-required='false'>      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[twelvetosixteenth][]"   data-rule-required='false'> 
      <input readonly value="-" type="text" class="form-control" placeholder="Availability"    data-rule-required='false'>      </th>
      <th><input  type="text" class="form-control" placeholder="Handling Fee" name="price[handle_charge][]"   data-rule-required='false'>      </th>
    </tr>
    
    <?php
            $find_room_types = $this->Hotel_model->find_room_types($hotel_id);
			$room_types = explode(",",$find_room_types['room_types']);
    ?>
    <!--<tr class="more_rooms">
       <th colspan="4">Add more room categories<span>
        <select    class="form-control room_categories" data-value="<?php echo $hotel_id;?>"  data-rule-required='true' >
          <option value="" >Select Room Type</option>
          
          <?php if($room_types){ foreach ($room_types as $rt) {?>
          <option  value="<?php echo $rt; ?>" ><?php echo $rt; ?></option>
          <?php } } ?>
        </select>
        
       </span></th>
       <th colspan=5></th>
    </tr>
       <tr>
      <th colspan="9">
        <div class="hotel_room_price"></div>
        
      </th>
    </tr>-->
    
    
    
    
   
    </table>
  </div>
</div>

<script>
  
  $(document).ready(function(){
    $(".extra_price_cost_type").find(":input").prop("disabled",true);
    //$(".title_retail"+'<?php echo $room_type;?>').html('<?php echo $room_type;?>');
    var class_code = '<?php echo $room_type;?>'.replace(/ /g,'');
    $(".title_retail"+class_code).html('<?php echo $room_type;?>');
  });
  
  $(".extra_checkbox_type").on('click',function(){
   var id = $(this).data('id').split('/');
      id = id[0]+id[1];
   
   if($("#retail_type"+id).is(":visible")){
      $("#retail_type"+id).hide();
      $("#cost_type"+id).hide();
      $("#retail_type"+id).find(":input").prop("disabled", true);
      $("#cost_type"+id).find(":input").prop("disabled", true);
    }else{
       
       $("#retail_type"+id).show();
       $("#cost_type"+id).show();
       $("#retail_type"+id).find(":input").prop("disabled", false);
       $("#cost_type"+id).find(":input").prop("disabled", false);
    }
  });
</script>