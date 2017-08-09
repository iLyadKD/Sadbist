

<style>
  .room_types_area{
    font-size:10px;
  }
</style>
  <?php $id_code = $hotel->hotel_id.'standard';?>
<div class="form-group">
  <label class="col-md-2 control-label">Rating</label>  
  <div class="col-sm-4 controls">
    <input type="text"  class='form-control' type="text" name="hotel[rating]"  placeholder="Rating" value="<?php echo $hotel->rating;?>" disabled >

  </div>


  <label class="col-md-2 control-label">Neighbourhood area</label>
  <div class="col-sm-4 controls">
    <input type="text"   class='form-control' type="text" name="hotel[neighbours]"  placeholder="Neighbours" value="<?php echo $hotel->neighbours_en;?>" disabled >
  </div>
      
  
  </div>

<div class="form-group">
   <label class="col-md-2 control-label required"  for='validation_current'>Food Type</label>
    
  <div class="col-sm-9 controls">
    <table class="table" >
      <tr>
      <td>
      <label><input type="radio" data-id="<?php echo $hotel->hotel_id;?>" class="radio_food" name="<?php echo $hotel->hotel_id;?>" data-rule-required='true' value='1'> BF</label></td>
      <td><label><input type="radio" data-id="<?php echo $hotel->hotel_id;?>" class="radio_food" name="<?php echo $hotel->hotel_id;?>" data-rule-required='true' value='4'> Breakfast and Lunch</label></td>
      <td><label><input type="radio" data-id="<?php echo $hotel->hotel_id;?>" class="radio_food" name="<?php echo $hotel->hotel_id;?>" data-rule-required='true' value='5'> Breakfast and Dinner</label></td>
    <td><label><input type="radio" data-id="<?php echo $hotel->hotel_id;?>" class="radio_food" name="<?php echo $hotel->hotel_id;?>" data-rule-required='true' value='2'> ALL</label></td>
    <td><label><input type="radio" data-id="<?php echo $hotel->hotel_id;?>" class="radio_food" name="<?php echo $hotel->hotel_id;?>" data-rule-required='true' value='3'> UALL</label></td>
   </tr>
     </table>
    <label for="gg" class="error"></label>
      
      
    
  </div>
</div>





</div>
                           
<div class="form-group added_hotel" id="h_<?php echo $hotel->hotel_id;?>" >
  <div class="col-md-12">                        
    <table class="table table-bordered">
      <tr>&nbsp;</tr>
       <tr><th colspan="9" style="text-align:center;color:#008000;">Standard room price</th></tr>
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
   
    <tr>

      <th class="required">Retail</th>
            <th>
      <input type="hidden" class="form-control" value="Standard" name="price[room_type_en][]" >
      <input type="hidden" class="form-control" value="استاندارد" name="price[room_type_fa][]" >
      <input type="hidden" class="form-control" value="1" name="price[price_type][]"   > 
      <input id="id_hotel" type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" name="price[hotel_id][]" >
        <input type="hidden" class="form-control" value="0" name="price[extra_price_flag][]"   >
        <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>"   name="price[food_type][]">

      <input  type="text" id="single_value" class="form-control money" placeholder="Cost" value="" name="price[single][]" data-rule-required='true' data-rule-money="false"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input   type="text" id="double_value" class="form-control money" placeholder="Cost" value="" name="price[doubles][]" data-rule-required='true' data-rule-money="false"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" name="price[double_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input  type="text" id="triple_value" class="form-control money" placeholder="Cost" value="" name="price[triple][]" data-rule-required='true'  data-rule-money="false"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" name="price[triple_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input  type="text" id="infants_value" class="form-control money" placeholder="Cost" value="" name="price[infants][]"  data-rule-required='true' data-rule-money="false"> 
       </th>
       <th>
      <input  type="text" id="twotosix_value" class="form-control money" placeholder="Cost" value="" name="price[twotosix][]" data-rule-required='true'  data-rule-money="false"> 
      </th>
       <th>
      <input  type="text" id="sixtotwelve_value" class="form-control money" placeholder="Cost" value="" name="price[sixtotwelve][]" data-rule-required='true' data-rule-money="false"> 
      </th>
       <th>
      <input  type="text" id="twelvetosixteenth_value" class="form-control money" placeholder="Cost" value="" name="price[twelvetosixteenth][]"  data-rule-required='true' data-rule-money="false"> 
      </th>

      <th><input  type="text" class="form-control money" placeholder="Handling Fee" value="" name="price[handle_charge][]"   data-rule-required='true'  data-rule-money="false" ></th>
    </tr>
    <?php // }} ?>

    <tr>
      <th class="required">Cost</th>
      <th>
      <input type="hidden" class="form-control" value="Standard" name="price[room_type_en][]" >
      <input type="hidden" class="form-control" value="استاندارد" name="price[room_type_fa][]" >
      <input type="hidden" class="form-control"  name="price[price_type][]" value="2"   > 
      <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" name="price[hotel_id][]">
        <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>"   name="price[food_type][]">
        <input type="hidden" class="form-control" value="0" name="price[extra_price_flag][]"   >
      <input type="text" id="single_cost_value" class="form-control money" placeholder="Cost" name="price[single][]"   data-rule-required='true' data-rule-money="false">
      <input type="hidden" class="form-control" placeholder="Availability" name="price[single_qty][]"   data-rule-required='false'>
      </th>
       
       <th>
      <input type="text" class="form-control money" id="double_cost_value" placeholder="Cost" name="price[doubles][]"   data-rule-required='true' data-rule-money="false">
      <input type="hidden" class="form-control" placeholder="Availability" name="price[double_qty][]"   data-rule-required='false'>
      
      
      <th>
      <input type="text" id="triple_cost_value" class="form-control money" placeholder="Cost" name="price[triple][]"   data-rule-required='true' data-rule-money="false">
      <input type="hidden" class="form-control" placeholder="Availability" name="price[triple_qty][]"   data-rule-required='false'>
       <th>
      <input  type="text" id="infants_cost_value" class="form-control money" placeholder="Cost" name="price[infants][]"   data-rule-required='true' data-rule-money="false"> 
          </th>
      
       <th>
      <input type="text" id="twotosix_cost_value" class="form-control money" placeholder="Cost" name="price[twotosix][]"   data-rule-required='true' data-rule-money="false"> 
            </th>
       <th>
      <input type="text" id="sixtotwelve_cost_value" class="form-control money" placeholder="Cost" name="price[sixtotwelve][]"   data-rule-required='true' data-rule-money="false"> 
           </th>
       <th>
      <input type="text" id="twelvetosixteenth_cost_value" class="form-control money" placeholder="Cost" name="price[twelvetosixteenth][]"   data-rule-required='true' data-rule-money="false">
      <input type="hidden" class="form-control" placeholder="Handling Fee" name="price[handle_charge][]"   data-rule-required='false' data-rule-money="true">
           </th>
    </tr>

    <tr >
       <th colspan="9">Extra Night Price <span><input data-id="<?php echo $id_code;?>" data-hotel="<?php echo $hotel->hotel_id;?>" class="extra_checkbox" type="checkbox"></span></th>
    </tr>
   
    <tr  class="extra_price_cost display_none" id="retail<?php echo $id_code;?>">
        <th>Retail</th>
     
      <th>
      <input type="hidden" class="form-control" value="Standard" disabled name="price[room_type_en][]" >
      <input type="hidden" class="form-control" value="استاندارد"  disabled name="price[room_type_fa][]" >
        <input  type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" disabled name="price[hotel_id][]"   > 
        <input  type="hidden" class="form-control" value="1" disabled name="price[price_type][]"   >
        <input  type="hidden" class="form-control" value="1" disabled name="price[extra_price_flag][]">
        <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>" disabled  name="price[food_type][]">

        <input  type="text" class="form-control money" placeholder="Cost"  disabled name="price[single][]"   data-rule-required='false' data-rule-money="false"> 
        <input   type="text" class="form-control" placeholder="Availability"  disabled name="price[single_qty][]"   data-rule-required='false'>
      </th>
      <th>
      <input   type="text" class="form-control money" placeholder="Cost"  disabled name="price[doubles][]"   data-rule-required='false' data-rule-money="false"> 
      <input   type="text" class="form-control" placeholder="Availability"  disabled name="price[double_qty][]"   data-rule-required='false'>
        </th>
       
      <th>
      <input   type="text" class="form-control money" placeholder="Cost"  disabled name="price[triple][]"   data-rule-required='false' data-rule-money="false"> 
      <input   type="text" class="form-control" placeholder="Availability"  disabled name="price[triple_qty][]"   data-rule-required='false'>
      </th>
       <th>
      <input   type="text" class="form-control money" placeholder="Cost"  disabled name="price[infants][]"   data-rule-required='false' data-rule-money="false"> 
       </th>
       <th>
      <input   type="text" class="form-control money" placeholder="Cost"  disabled name="price[twotosix][]"   data-rule-required='false' data-rule-money="false"> 
       <th>
      <input   type="text" class="form-control money" placeholder="Cost"  disabled name="price[sixtotwelve][]"   data-rule-required='false'' data-rule-money="false"> 
       <th>
      <input   type="text" class="form-control money" placeholder="Cost"  disabled name="price[twelvetosixteenth][]"   data-rule-required='false' data-rule-money="false"> 
            </th>
      <th><input    type="text" class="form-control money" placeholder="Handling Fee" value="" disabled name="price[handle_charge][]"   data-rule-required='false' data-rule-money="false">      </th>
    </tr>

    <tr  class="extra_price_price display_none" id="cost<?php echo $id_code;?>">
      <th class="required">Cost</th>
       
      <th>
      <input type="hidden" class="form-control" value="Standard" disabled name="price[room_type_en][]" >
      <input type="hidden" class="form-control" value="استاندارد" disabled name="price[room_type_fa][]" >
        <input  type="hidden" class="form-control" value="2"  disabled name="price[price_type][]"   >
        <input  type="hidden" class="form-control" value="1" disabled name="price[extra_price_flag][]"   >
        <input  type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>"  disabled name="price[hotel_id][]">
         <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>" disabled   name="price[food_type][]">
        <input  type="text" class="form-control money" placeholder="Cost" disabled name="price[single][]"' data-rule-money="false"   data-rule-required='false'>
        <input  type="hidden" class="form-control" placeholder="Availability" disabled name="price[single_qty][]"   data-rule-required='false'>
      </th>
      <th>
      <input  type="text" class="form-control money" placeholder="Cost" disabled name="price[doubles][]"   data-rule-required='false' data-rule-money="false">
      <input  type="hidden" class="form-control" placeholder="Availability" disabled name="price[double_qty][]"   data-rule-required='false'>
       </th>
      
      <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[triple][]"   data-rule-required='false'>
       <input  type="hidden" class="form-control" placeholder="Availability" disabled name="price[triple_qty][]"   data-rule-required='false'>
      </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[infants][]"   data-rule-required='false'> 
       </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[twotosix][]"   data-rule-required='false'> 
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[sixtotwelve][]"   data-rule-required='false'> 
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[twelvetosixteenth][]"   data-rule-required='false'>
      <input  type="hidden" class="form-control" placeholder="Handling Fee" disabled name="price[handle_charge][]"   data-rule-required='false'>
    </tr>
    
   
    
    
    
    
                      <!----------------------------Room categories START---------------------->  
    
    
    <tr><th colspan="9" style="text-align: center;">Other Room Types <span><input data-id="<?php echo $hotel->hotel_id;?>" class="other_type" type="checkbox"></span></th></tr>
    
    <?php if($room_types != ''){ ?>
      <?php for($r=0;$r<count($room_types);$r++){
          $value = explode("/",$room_types[$r]);
          //pr($value);exit;
          $value_en = $value[0];
          $value_fa = $value[1];
          
          
          $id_code = $hotel->hotel_id.str_replace( ' ', '', $value_en);
        ?>
        <tr><td>&nbsp;</td></tr>
         <tr class="other_room_type<?php echo $hotel->hotel_id;?> display_none"><th colspan="9" style="text-align:center;color:#4d4dff;"><?php echo $value_en;?> price <span><input data-id="<?php echo $id_code;?>" data-hotel="<?php echo $hotel->hotel_id;?>" class="price_checkbox" type="checkbox"></span></th></tr>
      <tr  class="display_none" id="genr<?php echo $id_code;?>">

      <th>Retail</th>
            <th>
      <input type="hidden" class="form-control" value="<?php echo $value_en;?>" disabled name="price[room_type_en][]" >
      <input type="hidden" class="form-control" value="<?php echo $value_fa;?>" disabled name="price[room_type_fa][]" >
      <input type="hidden" class="form-control" value="1" disabled name="price[price_type][]" > 
      <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" disabled name="price[hotel_id][]" data-rule-required='false'>
        <input type="hidden" class="form-control" value="0" disabled name="price[extra_price_flag][]"  >
         <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>" disabled  name="price[food_type][]">

      <input  type="text" class="form-control money" placeholder="Cost" value="" disabled name="price[single][]" data-rule-required='false' data-rule-money="false"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" disabled name="price[single_qty][]" data-rule-required='false' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input   type="text" id="double_value" class="form-control dv money" placeholder="Cost" value="" disabled name="price[doubles][]" data-rule-required='false' data-rule-money="false"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" disabled name="price[double_qty][]" data-rule-required='false' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="" disabled name="price[triple][]" data-rule-required='false'  data-rule-money="false"> 
      <input  type="text" class="form-control" placeholder="Availability" value="" disabled name="price[triple_qty][]" data-rule-required='false' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="" disabled name="price[infants][]"  data-rule-required='false' data-rule-money="false"> 
      
       </th>
       <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="" disabled name="price[twotosix][]" data-rule-required='false'  data-rule-money="false"> 
      </th>
       <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="" disabled name="price[sixtotwelve][]" data-rule-required='false' data-rule-money="false"> 
      </th>
       <th>
      <input  type="text" class="form-control money" placeholder="Cost" value="" disabled name="price[twelvetosixteenth][]"  data-rule-required='false' data-rule-money="false"> 
            </th>

      <th><input  type="text" class="form-control money" placeholder="Handling Fee" value="" disabled name="price[handle_charge][]"   data-rule-required='false'  data-rule-money="false" ></th>
    </tr>

    <tr  class="display_none" id="genc<?php echo $id_code;?>">
      <th >Cost </th>
      <th>
      <input type="hidden" class="form-control" value="<?php echo $value_en;?>" disabled name="price[room_type_en][]" >
      <input type="hidden" class="form-control" value="<?php echo $value_fa;?>" disabled name="price[room_type_fa][]" >
      <input type="hidden" class="form-control"  disabled name="price[price_type][]" value="2"   > 
      <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" disabled name="price[hotel_id][]"   > 
      
        <input type="hidden" class="form-control" value="0" disabled name="price[extra_price_flag][]">
         <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>" disabled  name="price[food_type][]">
      <input type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[single][]"   data-rule-required='false'>
      <input type="hidden" class="form-control" placeholder="Availability" disabled name="price[single_qty][]"   data-rule-required='false'>
      </th>
       
       <th>
      <input type="text" class="form-control cv money" data-rule-money="false" id="double_cost_value" placeholder="Cost" disabled name="price[doubles][]"   data-rule-required='false'>
      <input type="hidden" class="form-control" placeholder="Availability" disabled name="price[double_qty][]"   data-rule-required='false'> 
       </th>
      
      
      <th>
      <input type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[triple][]"   data-rule-required='false'>
      <input type="hidden" class="form-control" placeholder="Availability" disabled name="price[triple_qty][]"   data-rule-required='false'>
      </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[infants][]"   data-rule-required='false'> 
           </th>
      
       <th>
      <input type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[twotosix][]"   data-rule-required='false'> 
            </th>
       <th>
      <input type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[sixtotwelve][]"   data-rule-required='false'> 
           </th>
       <th>
      <input type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[twelvetosixteenth][]"   data-rule-required='false'>
      <input type="hidden" class="form-control" placeholder="Handling Fee" disabled name="price[handle_charge][]"   data-rule-required='false' data-rule-money="true">
          </th>
    </tr>

    <tr style="display: none;" id="enight<?php echo $id_code;?>">
       <th colspan="9">Extra Night Price <span><input data-id="<?php echo $id_code;?>" class="extra_checkbox" type="checkbox"></span></th>
    </tr>
    <tr  class="extra_price_cost display_none" id="retail<?php echo $id_code;?>">
        <th>Retail</th>
     
      <th>
      <input type="hidden" class="form-control" value="<?php echo $value_en;?>" disabled name="price[room_type_en][]" data-rule-required='false'>
      <input type="hidden" class="form-control" value="<?php echo $value_fa;?>" disabled name="price[room_type_fa][]" >
        <input  type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" disabled name="price[hotel_id][]"> 
        <input  type="hidden" class="form-control" value="1" disabled name="price[price_type][]">
        <input  type="hidden" class="form-control" value="1" disabled name="price[extra_price_flag][]">
         <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>" disabled   name="price[food_type][]">
        <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[single][]"   data-rule-required='false'> 
        <input   type="text" class="form-control" placeholder="Availability"  disabled name="price[single_qty][]"   data-rule-required='false'>
      </th>
      <th>
      <input   type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[doubles][]"   data-rule-required='false'> 
      <input   type="text" class="form-control" placeholder="Availability"  disabled name="price[double_qty][]"   data-rule-required='false'>
        </th>
       
      <th>
      <input   type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[triple][]"   data-rule-required='false'> 
      <input   type="text" class="form-control" placeholder="Availability"  disabled name="price[triple_qty][]"   data-rule-required='false'>
      </th>
       <th>
      <input   type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[infants][]"   data-rule-required='false'> 
      
       </th>
       <th>
      <input   type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[twotosix][]"   data-rule-required='false'> 
            </th>
       <th>
      <input   type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[sixtotwelve][]"   data-rule-required='false'> 
           </th>
       <th>
      <input   type="text" class="form-control money" data-rule-money="false" placeholder="Cost"  disabled name="price[twelvetosixteenth][]"   data-rule-required='false'> 
           </th>
      <th><input    type="text" class="form-control money" data-rule-money="false" placeholder="Handling Fee" value="" disabled name="price[handle_charge][]"   data-rule-required='false'>      </th>
    </tr>

    <tr  class="extra_price_price display_none" id="cost<?php echo $id_code;?>">
      <th class="required">Cost</th>
       
      <th>
      <input type="hidden" class="form-control" value="<?php echo $value_en;?>" disabled name="price[room_type_en][]">
      <input type="hidden" class="form-control" value="<?php echo $value_fa;?>" disabled name="price[room_type_fa][]" >
        <input  type="hidden" class="form-control" value="2"  disabled name="price[price_type][]">
        <input  type="hidden" class="form-control" value="1" disabled name="price[extra_price_flag][]">
        <input  type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>"  disabled name="price[hotel_id][]">
         <input type="hidden" class="form-control food_type<?php echo $hotel->hotel_id;?>" disabled   name="price[food_type][]">
        <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[single][]"   data-rule-required='false'>
        <input  type="hidden" class="form-control" placeholder="Availability" disabled name="price[single_qty][]"   data-rule-required='false'>
      </th>
      <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[doubles][]"   data-rule-required='false'>
      <input  type="hidden" class="form-control" placeholder="Availability" disabled name="price[double_qty][]"   data-rule-required='false'>
       </th>
      
      <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[triple][]"   data-rule-required='false'> 
      </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[infants][]"   data-rule-required='false'>
      <input  type="hidden" class="form-control" placeholder="Availability" disabled name="price[triple_qty][]"   data-rule-required='false'>
      
       </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[twotosix][]"   data-rule-required='false'> 
            </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[sixtotwelve][]"   data-rule-required='false'> 
            </th>
       <th>
      <input  type="text" class="form-control money" data-rule-money="false" placeholder="Cost" disabled name="price[twelvetosixteenth][]"   data-rule-required='false'>
      <input  type="hidden" class="form-control" placeholder="Handling Fee" disabled name="price[handle_charge][]"   data-rule-required='false'>
        </th>
    </tr>
      
      <?php } ?>
    <?php } ?>
    
                    <!--------------------------------Room categories END----------------------->  
   
    </table>
  <div class="appends" style="display: none;"></div>
  </div>
</div>


<script>
  
  $(".extra_checkbox").on('click',function(){
		var id = $(this).data('id');
		var hotel = $(this).data('hotel');
		if($("#retail"+id).is(":visible")){
			$("#retail"+id).hide();
			$("#cost"+id).hide();
            $('#retail'+id).find(':input').prop('disabled', true);
            $('#cost'+id).find(':input').prop('disabled', true);
		}else{
			$("#retail"+id).show();
			$("#cost"+id).show();
             $('#retail'+id).find(':input').prop('disabled', false);
            $('#cost'+id).find(':input').prop('disabled', false);
            
           if ($("#"+hotel).val() != undefined) {
              $(".food_type"+hotel).val($("#"+hotel).val());
           }
		}
	});
  
  $(".other_type").on('click',function(){
		var id = $(this).data('id');
		if($(".other_room_type"+id).is(":visible")){
			$(".other_room_type"+id).hide();
		}else{
			$(".other_room_type"+id).show();
		}
	});
  
  $(".price_checkbox").on('click',function(){
		var id = $(this).data('id');
		var hotel = $(this).data('hotel');
		if($("#genr"+id).is(":visible")){
			$("#genr"+id).hide();
			$("#genc"+id).hide();
            $("#enight"+id).hide();
            $('#genr'+id).find(':input').prop('disabled', true);
            $('#genc'+id).find(':input').prop('disabled', true);
		}else{
			$("#genr"+id).show();
			$("#genc"+id).show();
            $("#enight"+id).show();
            $('#genr'+id).find(':input').prop('disabled', false);
            $('#genc'+id).find(':input').prop('disabled', false);
            if ($("#"+hotel).val() != undefined) {
              $(".food_type"+hotel).val($("#"+hotel).val());
           }
		}
	});
  
  $(".radio_food").on('change',function(){
    var id = $(this).data('id');
    var val = $(this).val();
    if ($("#"+id).length > 0) {
        $("#"+id).val(val);
    }else{
        $(".appends").append("<input type='hidden' id='"+id+"' value="+val+">");
    }
    $(".food_type"+id).val(val);
    
  });
  
  		/*$("#base_price,#discount_price,#percentage_box,#dollar_box,#single_value,#triple_value,#infants_value,#twotosix_value,#sixtotwelve_value,#twelvetosixteenth_value").on( 'keyup change keypress keydown mousewheel', function () {
			var base_price = $("#base_price").val();
			var discount_price = $("#discount_price").val();
			var percentage_price = $("#percentage_box").val();
			var dollar_price = $("#dollar_box").val();
			
			var single_value = $("#single_value").val();
			var triple_value = $("#triple_value").val();
			var infants_value = $("#infants_value").val();
			var twotosix_value = $("#twotosix_value").val();
			var sixtotwelve_value = $("#sixtotwelve_value").val();
			var twelvetosixteenth_value = $("#twelvetosixteenth_value").val();
			get_price_room(base_price,discount_price,percentage_price,dollar_price,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value);
			  
		});*/
        
    /*function get_price_room(base_price,discount_price,percentage_price,dollar_price,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value) {
    if (discount_price != '') {
        if (discount_price != 0) {
            base_price = discount_price;
        }
    }
	if (percentage_price != 0 && base_price != '') {
		var cal = parseInt($("#hid_base_price").val())*(parseInt(percentage_price)/100);
		var final_rate = parseInt($("#hid_base_price").val())-parseInt(cal);
    }
	
	if (percentage_price != 0 && single_value != '') {
		var cal_single = parseInt($("#hid_single_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_single = parseInt($("#hid_single_price").val())-parseInt(cal_single);
    }
    if (percentage_price != 0 && triple_value != '') {
		var cal_triple = parseInt($("#hid_triple_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_triple = parseInt($("#hid_triple_price").val())-parseInt(cal_triple);
    }
    if (percentage_price != 0 && infants_value != '') {
		var cal_infants = parseInt($("#hid_infants_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_infants = parseInt($("#hid_infants_price").val())-parseInt(cal_infants);
    }
    
    if (percentage_price != 0 && twotosix_value != '') {
		var cal_twotosix = parseInt($("#hid_twotosix_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_twotosix = parseInt($("#hid_twotosix_price").val())-parseInt(cal_twotosix);
    }
    
	if (percentage_price != 0 && sixtotwelve_value != '') {
		var cal_sixtotwelve = parseInt($("#hid_sixtotwelve_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_sixtotwelve = parseInt($("#hid_sixtotwelve_price").val())-parseInt(cal_sixtotwelve);
    }
	if (percentage_price != 0 && twelvetosixteenth_value != '') {
		var cal_twelvetosixteenth = parseInt($("#hid_twelvetosixteenth_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_twelvetosixteenth = parseInt($("#hid_twelvetosixteenth_price").val())-parseInt(cal_twelvetosixteenth);
    }
	
	
	if(dollar_price != 0 && base_price != ''){
		var final_rate = dollar_price;
	}
	
	if(dollar_price != 0 && single_value != ''){
		var final_rate_single = dollar_price;
	}
    if(dollar_price != 0 && triple_value != ''){
		var final_rate_triple = dollar_price;
	}
	if(dollar_price != 0 && infants_value != ''){
		var final_rate_infants = dollar_price;
	}
	if(dollar_price != 0 && twotosix_value != ''){
		var final_rate_twotosix = dollar_price;
	}
	if(dollar_price != 0 && sixtotwelve_value != ''){
		var final_rate_sixtotwelve = dollar_price;
	}
	if(dollar_price != 0 && twelvetosixteenth_value != ''){
		var final_rate_twelvetosixteenth = dollar_price;
	}
	
	
	
	
	
	
	$("#hid_cost_price").val(final_rate);
	$("#hid_single_cprice").val(final_rate_single);
	$("#hid_triple_cprice").val(final_rate_triple);
	$("#hid_infants_cprice").val(final_rate_infants);
	$("#hid_twotosix_cprice").val(final_rate_twotosix);
	$("#hid_sixtotwelve_cprice").val(final_rate_sixtotwelve);
	$("#hid_twelvetosixteenth_cprice").val(final_rate_twelvetosixteenth);
	
	
	
	
	
	$("#hid_base_price").val(base_price);
	$("#hid_single_price").val(single_value);
	$("#hid_triple_price").val(triple_value);
	$("#hid_infants_price").val(infants_value);
	$("#hid_twotosix_price").val(twotosix_value);
	$("#hid_sixtotwelve_price").val(sixtotwelve_value);
	$("#hid_twelvetosixteenth_price").val(twelvetosixteenth_value);
	
	
	if ($("#double_value").length > 0) {
		$("#double_value").val($("#hid_base_price").val());
    }
	if ($("#single_value").length > 0) {
		$("#single_value").val($("#hid_single_price").val());
    }
	if ($("#triple_value").length > 0) {
		$("#triple_value").val($("#hid_triple_price").val());
    }
	if ($("#infants_value").length > 0) {
		$("#infants_value").val($("#hid_infants_price").val());
    }
	if ($("#twotosix_value").length > 0) {
		$("#twotosix_value").val($("#hid_twotosix_price").val());
    }
	if ($("#sixtotwelve_value").length > 0) {
		$("#sixtotwelve_value").val($("#hid_sixtotwelve_price").val());
    }
	if ($("#twelvetosixteenth_value").length > 0) {
		$("#twelvetosixteenth_value").val($("#hid_twelvetosixteenth_price").val());
    }
	
	
	if ($("#double_cost_value").length > 0) {
		if ($("#hid_cost_price").val() == '') {
            $("#double_cost_value").val($("#hid_base_price").val());
        }else {
			$("#double_cost_value").val($("#hid_cost_price").val());
		}
		
    }
	if ($("#single_cost_value").length > 0) {
		if ($("#hid_single_cprice").val() == '') {
            $("#single_cost_value").val($("#hid_single_price").val());
        }else {
			$("#single_cost_value").val($("#hid_single_cprice").val());
		}
		
    }
    
    if ($("#triple_cost_value").length > 0) {
		if ($("#hid_triple_cprice").val() == '') {
            $("#triple_cost_value").val($("#hid_triple_price").val());
        }else {
			$("#triple_cost_value").val($("#hid_triple_cprice").val());
		}
		
    }
    if ($("#infants_cost_value").length > 0) {
		if ($("#hid_infants_cprice").val() == '') {
            $("#infants_cost_value").val($("#hid_infants_price").val());
        }else {
			$("#infants_cost_value").val($("#hid_infants_cprice").val());
		}
		
    }
    if ($("#twotosix_cost_value").length > 0) {
		if ($("#hid_twotosix_cprice").val() == '') {
            $("#twotosix_cost_value").val($("#hid_twotosix_price").val());
        }else {
			$("#twotosix_cost_value").val($("#hid_twotosix_cprice").val());
		}
		
    }
    if ($("#sixtotwelve_cost_value").length > 0) {
		if ($("#hid_sixtotwelve_cprice").val() == '') {
            $("#sixtotwelve_cost_value").val($("#hid_sixtotwelve_price").val());
        }else {
			$("#sixtotwelve_cost_value").val($("#hid_sixtotwelve_cprice").val());
		}
		
    }
    if ($("#twelvetosixteenth_cost_value").length > 0) {
		if ($("#hid_twelvetosixteenth_cprice").val() == '') {
            $("#twelvetosixteenth_cost_value").val($("#hid_twelvetosixteenth_price").val());
        }else {
			$("#twelvetosixteenth_cost_value").val($("#hid_twelvetosixteenth_cprice").val());
		}
		
    }
}*/

/*$(document).on("keyup", 'input.money', function(event) {
    if(event.which >= 37 && event.which <= 40) return;
    $(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
    });
});*/

		/*$(document).on( 'keyup change keypress',"#base_price,#discount_price,#percentage_box,#dollar_box,#single_value,#triple_value,#infants_value,#twotosix_value,#sixtotwelve_value,#twelvetosixteenth_value", function () {
			var base_price = $("#base_price").val();
			var discount_price = $("#discount_price").val();
			var percentage_price = $("#percentage_box").val();
			var dollar_price = $("#dollar_box").val();
			
			var single_value = $("#single_value").val();
			
			var triple_value = $("#triple_value").val();
			var infants_value = $("#infants_value").val();
			var twotosix_value = $("#twotosix_value").val();
			var sixtotwelve_value = $("#sixtotwelve_value").val();
			var twelvetosixteenth_value = $("#twelvetosixteenth_value").val();
			
			get_price_room(base_price,discount_price,percentage_price,dollar_price,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value);
			  
		});
        function get_price_room(base_price,discount_price,percentage_price,dollar_price,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value) {
    if (discount_price != '') {
        if (discount_price != 0) {
            base_price = discount_price;
        }
    }
	
	if (percentage_price != 0 && base_price != '') {
		var cal = parseInt($("#hid_base_price").val().replace(",",""))*(parseInt(percentage_price)/100);
		var final_rate = parseInt($("#hid_base_price").val().replace(",",""))-parseInt(cal);
		
    }
	
	if (percentage_price != 0 && single_value != '') {
		var cal_single = parseInt($("#hid_single_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_single = parseInt($("#hid_single_price").val())-parseInt(cal_single);
    }
    if (percentage_price != 0 && triple_value != '') {
		var cal_triple = parseInt($("#hid_triple_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_triple = parseInt($("#hid_triple_price").val())-parseInt(cal_triple);
    }
    if (percentage_price != 0 && infants_value != '') {
		var cal_infants = parseInt($("#hid_infants_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_infants = parseInt($("#hid_infants_price").val())-parseInt(cal_infants);
    }
    
    if (percentage_price != 0 && twotosix_value != '') {
		var cal_twotosix = parseInt($("#hid_twotosix_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_twotosix = parseInt($("#hid_twotosix_price").val())-parseInt(cal_twotosix);
    }
    
	if (percentage_price != 0 && sixtotwelve_value != '') {
		var cal_sixtotwelve = parseInt($("#hid_sixtotwelve_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_sixtotwelve = parseInt($("#hid_sixtotwelve_price").val())-parseInt(cal_sixtotwelve);
    }
	if (percentage_price != 0 && twelvetosixteenth_value != '') {
		var cal_twelvetosixteenth = parseInt($("#hid_twelvetosixteenth_price").val())*(parseInt(percentage_price)/100);
		
		var final_rate_twelvetosixteenth = parseInt($("#hid_twelvetosixteenth_price").val())-parseInt(cal_twelvetosixteenth);
    }
	
	
	if(dollar_price != 0 && base_price != ''){
		var final_rate = dollar_price;
	}
	
	if(dollar_price != 0 && single_value != ''){
		var final_rate_single = dollar_price;
	}
    if(dollar_price != 0 && triple_value != ''){
		var final_rate_triple = dollar_price;
	}
	if(dollar_price != 0 && infants_value != ''){
		var final_rate_infants = dollar_price;
	}
	if(dollar_price != 0 && twotosix_value != ''){
		var final_rate_twotosix = dollar_price;
	}
	if(dollar_price != 0 && sixtotwelve_value != ''){
		var final_rate_sixtotwelve = dollar_price;
	}
	if(dollar_price != 0 && twelvetosixteenth_value != ''){
		var final_rate_twelvetosixteenth = dollar_price;
	}
	
	
	
	
	//.replace(",","")
	
	$("#hid_cost_price").val(final_rate);
	$("#hid_single_cprice").val(final_rate_single);
	$("#hid_triple_cprice").val(final_rate_triple);
	$("#hid_infants_cprice").val(final_rate_infants);
	$("#hid_twotosix_cprice").val(final_rate_twotosix);
	$("#hid_sixtotwelve_cprice").val(final_rate_sixtotwelve);
	$("#hid_twelvetosixteenth_cprice").val(final_rate_twelvetosixteenth);
	
	
	
	
	
	$("#hid_base_price").val(base_price);
	$("#hid_single_price").val(single_value);
	$("#hid_triple_price").val(triple_value);
	$("#hid_infants_price").val(infants_value);
	$("#hid_twotosix_price").val(twotosix_value);
	$("#hid_sixtotwelve_price").val(sixtotwelve_value);
	$("#hid_twelvetosixteenth_price").val(twelvetosixteenth_value);
	
	
	if ($("#double_value").length > 0) {
		$("#double_value").val($("#hid_base_price").val());
    }
	if ($("#single_value").length > 0) {
		$("#single_value").val($("#hid_single_price").val());
    }
	if ($("#triple_value").length > 0) {
		$("#triple_value").val($("#hid_triple_price").val());
    }
	if ($("#infants_value").length > 0) {
		$("#infants_value").val($("#hid_infants_price").val());
    }
	if ($("#twotosix_value").length > 0) {
		$("#twotosix_value").val($("#hid_twotosix_price").val());
    }
	if ($("#sixtotwelve_value").length > 0) {
		$("#sixtotwelve_value").val($("#hid_sixtotwelve_price").val());
    }
	if ($("#twelvetosixteenth_value").length > 0) {
		$("#twelvetosixteenth_value").val($("#hid_twelvetosixteenth_price").val());
    }
	
	if ($("#double_cost_value").length > 0) {
		if ($("#hid_cost_price").val() == '') {
            $("#double_cost_value").val($("#hid_base_price").val());
        }else {
			$("#double_cost_value").val($("#hid_cost_price").val());
		}
		
		$("#double_cost_value").val('ss');
    }
	if ($("#single_cost_value").length > 0) {
		if ($("#hid_single_cprice").val() == '') {
            $("#single_cost_value").val($("#hid_single_price").val());
        }else {
			$("#single_cost_value").val($("#hid_single_cprice").val());
		}
		
    }
    
    if ($("#triple_cost_value").length > 0) {
		if ($("#hid_triple_cprice").val() == '') {
            $("#triple_cost_value").val($("#hid_triple_price").val());
        }else {
			$("#triple_cost_value").val($("#hid_triple_cprice").val());
		}
		
    }
    if ($("#infants_cost_value").length > 0) {
		if ($("#hid_infants_cprice").val() == '') {
            $("#infants_cost_value").val($("#hid_infants_price").val());
        }else {
			$("#infants_cost_value").val($("#hid_infants_cprice").val());
		}
		
    }
    if ($("#twotosix_cost_value").length > 0) {
		if ($("#hid_twotosix_cprice").val() == '') {
            $("#twotosix_cost_value").val($("#hid_twotosix_price").val());
        }else {
			$("#twotosix_cost_value").val($("#hid_twotosix_cprice").val());
		}
		
    }
    if ($("#sixtotwelve_cost_value").length > 0) {
		if ($("#hid_sixtotwelve_cprice").val() == '') {
            $("#sixtotwelve_cost_value").val($("#hid_sixtotwelve_price").val());
        }else {
			$("#sixtotwelve_cost_value").val($("#hid_sixtotwelve_cprice").val());
		}
		
    }
    if ($("#twelvetosixteenth_cost_value").length > 0) {
		if ($("#hid_twelvetosixteenth_cprice").val() == '') {
            $("#twelvetosixteenth_cost_value").val($("#hid_twelvetosixteenth_price").val());
        }else {
			$("#twelvetosixteenth_cost_value").val($("#hid_twelvetosixteenth_cprice").val());
		}
		
    }
}*/
  
  
</script>