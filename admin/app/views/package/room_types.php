 <select class='form-control select2' data-rule-required='true' id="change_room_type"  name="change_room_type">
      <option  value='Standard'> Standard </option> 
      <?php foreach ($room_types as $type) {   ?>
      <option <?php if($selected_type == $type) echo 'selected';?>  value='<?php echo $type; ?>'> <?php echo $type; ?>  </option> 
      <?php }  ?>
</select>
 
 <script>
  	var base_url = $("head").data("base-url");
    $("#change_room_type").on('change',function(){
		var room_type = $('select[name="change_room_type"]').val();
				var hotel_id = $("#selected_hotel").val();
					var tour_id = $("#tour_id").val();
					var df = $("#df").val();
					var flag = $("#extra_flag").val();
					
                    var place = '#price_sector';
                    var place1 = '#price_sector_extra';
					var selected_days = $("#selected_days").val().split(',');
					
					
                    $.ajax({
						method: "POST",
						url: base_url + "package/get_hotel_price_master",
						data: { hotel_id: hotel_id,tour_id:tour_id,df:df,flag:flag,days: selected_days,room_type:room_type}
					})
					.done(function( msg ) {
						$(".hotel_extra_price").removeClass('display_none');
						if (flag == 0) {
							$(place1).slideUp();
							$(place).html(msg).slideDown();
							$(place).find(":input").removeAttr("disabled");
							$(place1).find(":input").prop("disabled", true);
						}else{
							$(place1).html(msg).slideDown();
							$(place).slideUp();
							$(place1).find(":input").removeAttr("disabled");					
							$(place).find(":input").prop("disabled", true);
						 }
					});
		
	});
  
 </script>