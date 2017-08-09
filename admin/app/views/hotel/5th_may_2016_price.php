
<div class="form-group">
  <label class="col-md-2 control-label">Rating</label>  
  <div class="col-sm-4 controls">
    <input type="text"  class='form-control' type="text" name="hotel[rating]"  placeholder="Rating" value="<?php echo $hotel->rating;?>" disabled >
    <!--<input type="hidden"  class='form-control' type="text" name="hotel[hotel_id][]"  placeholder="Rating" value="<?php echo $hotel->hotel_id;?>" > -->
  </div>


  <label class="col-md-2 control-label">Neighbourhood area</label>
  <div class="col-sm-4 controls">
    <input type="text"   class='form-control' type="text" name="hotel[neighbours]"  placeholder="Neighbours" value="<?php echo $hotel->neighbours;?>" disabled >
  </div>
  </div>
</div>
                           
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
    <?php if($price) { foreach ($price as  $row) { ?>
    <tr>

      <th>Retail</th>
            <th>
      <input type="hidden" class="form-control" value="1" name="price[price_type][]" data-rule-required='true' data-rule-money="true" > 
      <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" name="price[hotel_id][]" data-rule-required='true'> 
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->single; ?>" name="price[single][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->single_qty; ?>" name="price[single_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>

      
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->doubles; ?>" name="price[doubles][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->double_qty; ?>" name="price[double_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
       
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->triple; ?>" name="price[triple][]" data-rule-required='true'  data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $row->triple_qty; ?>" name="price[triple_qty][]" data-rule-required='true' data-rule-number="true" data-rule-min="0" data-rule-max="999">
      </th>
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->infants; ?>" name="price[infants][]"  data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[infants_qty][]" >
       </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->twotosix; ?>" name="price[twotosix][]" data-rule-required='true'  data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[twotosix_qty][]"   ></th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->sixtotwelve; ?>" name="price[sixtotwelve][]" data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[sixtotwelve_qty][]" ></th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"  data-rule-required='true' data-rule-money="true"> 
      <input  type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[twelvetosixteenth_qty][]"  >      </th>

      <th><input  type="text" class="form-control" placeholder="Handling Fee" value="" name="price[handle_charge][]"   data-rule-required='true'  data-rule-money="true" ></th>
    </tr>
    <?php  }} ?>

    <tr>
      <th class="required">Cost</th>
      <th>
      <input type="hidden" class="form-control"  name="price[price_type][]" value="2"   data-rule-required='true'> 
      <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Cost" name="price[single][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[single_qty][]"   data-rule-required='true'>
      </th>
       
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[doubles][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[double_qty][]"   data-rule-required='true'>        </th>
      
      
      <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[triple][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[triple_qty][]"   data-rule-required='true'>      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" name="price[infants][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[infants_qty][]"   >      </th>
      
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[twotosix][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[twotosix_qty][]"   >      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[sixtotwelve][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[sixtotwelve_qty][]"   >      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[twelvetosixteenth][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[twelvetosixteenth_qty][]"  >      </th>
      <th><input type="text" class="form-control" placeholder="Handling Fee" name="price[handle_charge][]"   data-rule-required='true' data-rule-money="true">      </th>
    </tr>
    <?php if($hotel->extra_night) { foreach ($extra_price as  $extra_row) { ?>
    <tr >
       <th colspan="8">Extra Night Price</th>
    </tr>
    <tr  class="extra_price_cost">
        <th>Retail</th>
     
      <th>
        <input type="hidden" class="form-control" value="3" name="price[price_type][]"   data-rule-required='true'> 
        <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
        <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->single; ?>" name="price[single][]"   data-rule-required='true'> 
        <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->single_qty; ?>" name="price[single_qty][]"   data-rule-required='true'>
      </th>
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->doubles; ?>" name="price[doubles][]"   data-rule-required='true'> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->double_qty; ?>" name="price[double_qty][]"   data-rule-required='true'>
        </th>
       
      <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->triple; ?>" name="price[triple][]"   data-rule-required='true'> 
      <input  type="text" class="form-control" placeholder="Availability" value="<?php echo $extra_row->triple_qty; ?>" name="price[triple_qty][]"   data-rule-required='true'>
      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->infants; ?>" name="price[infants][]"   data-rule-required='true'> 
      <input  value="-" type="text" class="form-control" placeholder="Availability" disabled value="-" name="price[infants_qty][]"   data-rule-required='true'>
       </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->twotosix; ?>" name="price[twotosix][]"   data-rule-required='true'> 
      <input  value="-" type="text" class="form-control" placeholder="Availability"  disabled value="-" name="price[twotosix_qty][]"   data-rule-required='true'>      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->sixtotwelve; ?>" name="price[sixtotwelve][]"   data-rule-required='true'> 
      <input  value="-" type="text" class="form-control" placeholder="Availability"  disabled value="-" name="price[sixtotwelve_qty][]"   data-rule-required='true'>      </th>
       <th>
      <input  type="text" class="form-control" placeholder="Cost" value="<?php echo $extra_row->twelvetosixteenth; ?>" name="price[twelvetosixteenth][]"   data-rule-required='true'> 
      <input  value="-" type="text" class="form-control" placeholder="Availability"  disabled value="-" name="price[twelvetosixteenth_qty][]"   data-rule-required='true'>      </th>
      <th><input   type="text" class="form-control" placeholder="Handling Fee" value="" name="price[handle_charge][]"   data-rule-required='true'>      </th>
    </tr>

    <tr  class="extra_price_price">
      <th class="required">Cost</th>
       
      <th>
 
        <input type="hidden" class="form-control" value="4"  name="price[price_type][]"   data-rule-required='true'> 
        <input type="hidden" class="form-control" value="<?php echo $hotel->hotel_id;?>" name="price[hotel_id][]"   data-rule-required='true'> 
        <input type="text" class="form-control" placeholder="Cost" name="price[single][]"   data-rule-required='true'> 
        <input type="text" class="form-control" placeholder="Availability" name="price[single_qty][]"   data-rule-required='true'>
      </th>
      <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[doubles][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[double_qty][]"   data-rule-required='true'>
       </th>
      
      <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[triple][]"   data-rule-required='true'> 
      <input type="text" class="form-control" placeholder="Availability" name="price[triple_qty][]"   data-rule-required='true'>
      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[infants][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[infants_qty][]"   data-rule-required='true'>
       </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[twotosix][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[twotosix_qty][]"   data-rule-required='true'>      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[sixtotwelve][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[sixtotwelve_qty][]"   data-rule-required='true'>      </th>
       <th>
      <input type="text" class="form-control" placeholder="Cost" name="price[twelvetosixteenth][]"   data-rule-required='true'> 
      <input disabled value="-" type="text" class="form-control" placeholder="Availability" name="price[twelvetosixteenth_qty][]"   data-rule-required='true'>      </th>
      <th><input type="text" class="form-control" placeholder="Handling Fee" name="price[handle_charge][]"   data-rule-required='true'>      </th>
    </tr>
    <?php }} ?>
    </table>
  </div>
</div>