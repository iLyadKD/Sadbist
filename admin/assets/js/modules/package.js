require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
		
		if (current_controller == "package" && current_method == "edit") {
			var edit_flag = 1;
		}else{
			var edit_flag = 0;
		}
		
		if($("table.manage_packages").length > 0){
			$("table.manage_packages").dataTable({
				"dom": "lfrtip",
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5, 6]}]
			});
			
			$(document).on("click", ".change_tour_status", function(){
				var tht = this;
				var status = $(this).data("status");
				var id = $(this).data("id");
				$.ajax({
					url: base_url + "package/status/" + id + "/" + status,
					data: { hotel_id: hotel_id,tour_id:tour_id }
				})
				.done(function( msg ) {
					if(msg==1){
						if(status==0){
							$(tht).removeClass("label-success").addClass("label-danger").html('<i class="icon-ok"></i>InActive').data('status','1');
						}
						if(status==1){
							$(tht).removeClass("label-danger").addClass("label-success").html('<i class="icon-ok"></i>Active').data('status','0');
						}
					}
				});
			});
		}

		if($("table.manage_vendors").length > 0){
			$("table.manage_vendors").dataTable({
				"dom": "lfrtip",
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5, 6]}]
			});
			$(document).on('click','.delete_vendor',function(){
				if (confirm("Are you sure want to remove this vendor ?")){
					var id = $(this).data('id');
					if (id !== '') {
						location.href= base_url + "package/delete_vendor/"+id;
					}
				}
			});
		}
		

		if($("table.manage_package_airlines").length > 0)
			$("table.manage_package_airlines").dataTable({
				"dom": "lfrtip",
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 3, 4]}]
			});
		
		if($("form#add_tour_airline").length > 0){
			$('form#add_tour_airline').validate();
		}
		if($("form#add_vendor").length > 0){
			$('form#add_vendor').validate();
		}
		
		$(document).on("click", ".change_airline_status", function(){
			var tht = this;
			var status = $(this).data("status");
			var id = $(this).data("id");
			$.ajax({
				url: base_url + "package/airline_status/" + id + "/" + status,
				data: { hotel_id: hotel_id,tour_id:tour_id }
			})
			.done(function( msg ) {
				if(msg==1){
					if(status==0){
						$(tht).removeClass("label-success").addClass("label-danger").html('<i class="icon-ok"></i>InActive').data('status','1');
					}
					if(status==1){
						$(tht).removeClass("label-danger").addClass("label-success").html('<i class="icon-ok"></i>Active').data('status','0');
					}
				}
			});
		});

		if($("form#add_package").length > 0){
			$(".select4").select2();
			$('#non').on('keyup change', function() {
				$("#nod").val(parseInt($(this).val()) + 1);	
			});
			
			$(".extra_checkbox").on('click',function(){
				var id = $(this).data('id');
				if($("#retail"+id).is(":visible")){
					$("#retail"+id).hide();
					$("#cost"+id).hide();
				}else{
					$("#retail"+id).show();
					$("#cost"+id).show();
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
				}else{
					$("#genr"+id).show();
					$("#genc"+id).show();
					$("#enight"+id).show();
					if ($("#"+hotel).val() !== undefined) {
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
				
			$("#percentage_box").on('keyup change keypress',function(){
				if (edit_flag == 1) {
					return false;
				}
				$("#dollar_box").val(0);
			});
			$("#dollar_box").on('keyup change keypress',function(){
				if (edit_flag == 1) {
					return false;
				}
				$("#percentage_box").val(0);
			});
				
				
			$(document).on( 'change keyup keypress click',"#base_price,#discount_price,#percentage_box,#dollar_box,#single_value,#triple_value,#infants_value,#twotosix_value,#sixtotwelve_value,#twelvetosixteenth_value", function () {
				if($(this).hasClass("money")){
					if(event.which >= 37 && event.which <= 40) return;
					$(this).val(function(index, value) {
						return value
						.replace(/\D/g, "")
						.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
						;
					});
				}
				var base_price = $("#base_price").val(); /*if(base_price !='') $("#hid_base_price").val(base_price);*/ 
				var discount_price = $("#discount_price").val();
				var percentage_price = $("#percentage_box").val();
				var dollar_price = $("#dollar_box").val();
				var single_value = $("#single_value").val(); if(single_value !=='') $("#hid_single_price").val(single_value);
				
				var triple_value = $("#triple_value").val(); if(triple_value !=='') $("#hid_triple_price").val(triple_value);
				var infants_value = $("#infants_value").val(); if(infants_value !=='') $("#hid_infants_price").val(infants_value);
				var twotosix_value = $("#twotosix_value").val(); if(twotosix_value !=='') $("#hid_twotosix_price").val(twotosix_value);
				var sixtotwelve_value = $("#sixtotwelve_value").val(); if(sixtotwelve_value !=='') $("#hid_sixtotwelve_price").val(sixtotwelve_value);
				var twelvetosixteenth_value = $("#twelvetosixteenth_value").val(); if(twelvetosixteenth_value !=='') $("#hid_twelvetosixteenth_price").val(twelvetosixteenth_value);
				get_price_room(base_price,discount_price,percentage_price,dollar_price,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value);
				  
			});
				
			if((".list_vendor").length > 0) {
			   $(".list_vendor").on('change',function(){
					val = $(this).val();
					if (val !== '') {
						var selected = $(this).find('option:selected');
						var extra = selected.data('foo').split("::!!::");
						if (extra) {
						   $("#vendor_details").show();
						   $("#v_name").html('<i class="icon-user"></i> Name :  '+extra[0]);
						   $("#v_contact_person").html('<i class="icon-hand-right"></i> Contact person :  '+extra[1]);
						   $("#w_address").html('<i class="icon-building"></i> Web address :  '+extra[2]);
						   $("#v_mobile").html('<i class="icon-mobile-phone"></i> Mobile no : '+extra[3]);
						   $("#log_id").html('<i class="icon-certificate"></i> Login Id : '+extra[4]);
						   $("#pwd").html('<i class="icon-lock"></i> Password : '+extra[5]);
						}
					}else{
						$("#vendor_details").hide();
					}
				});
			   
				var v_selected_option = $( ".list_vendor option:selected" ).val();
				if (v_selected_option !== '') {
				  $(".list_vendor").trigger("change");
				}
			}
					
			$("body").click(function(){
				if ($("#id_tour").val() !== '' && $("#id_flag").val() !== 1 && $("#request_flag").val() == 1) {
					var t_id = $("#id_tour").val();
					$.ajax({
					  url: base_url + "package/checkunique",
					  type: 'POST',
					  async: false,
					  data: {tour_id:t_id},
					  success: function(data) {
						if (data == 0) {
							$("#tour_id_err").html("");
							$("#id_flag").val(1);
							return true;
						}else{
							$("#id_tour").val('');
							$("#tour_id_err").html("Tour ID should be unique");
						}
					  }
					}); 
				}
			});
					
			$("#id_tour").on( 'keyup change keypress', function () {
				$("#id_flag").val(0);
			});
				
			$.validator.addMethod("alphanumericval", function(value, element) {
				return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
			}, "This field only accepts letters and numbers.");
	
			jQuery.validator.addMethod("money",function(value, element) {
				var isValidMoney = /^\d{0,20}(\.\d{0,2})?$/.test(value);
				return this.optional(element) || isValidMoney;
			},
			"Please enter valid price "
			);		
		
			if($(".add_hotel").length > 0){
				$(".add_hotel").validate();
				$(document).on('submit','form.add_hotel',function(e){
					var gal_divs = $(".image_rows > div").length;
					var newly_added_divs = $("#preview-image > div").length;
					if (gal_divs == 0 && newly_added_divs == 0) {
					   $("#imageupload").focus();
					   $(".file_type_err").text('please add atleast one image !!').fadeIn().delay(2000).fadeOut('slow'); 
					   e.preventDefault();	
					}
				});
			}	
		
			if($("form.add_package").length > 0){
				$('form.add_package').validate();
			}
		
			$('#fromdate').datepicker( {
				minDate : 0,
				dateFormat: "yy-mm-dd",
				onClose: function() {
				 var min = $(this).datepicker('getDate'); // Get selected date
			  $("#todate").datepicker('option', 'minDate', min || '0'); // Set other min, default to today
			  }
			});
		
			$('#todate').datepicker({
				minDate : 1,
				dateFormat: "yy-mm-dd"
			});
			$("#checkAll").click(function () {
				$("input.days").prop('checked', $(this).prop("checked"));
			});
		
			if($(".hotel_count").length > 0){ 
				var hotel_count  =$(".hotel_count").length;
				var cars = [];
				for (var hi = 1; hi<= hotel_count; hi++) {
					var hotel_id_get = $( ".hotel_id_get" + hi);
					var hotel_id =$(hotel_id_get).val() ;
					var tour_id =$( ".tour_id_get"+ hi ).val() ;
						$.ajax({
						  method: "POST",
						  url: base_url + "package/get_hotel_json",
						  data: { hotel_id: hotel_id,tour_id:tour_id }
						})
						  .done(function( msg ) {
						   $(hotel_id_get ).parents('.hotel_group').next(".hotel_price").html(msg);					   
						  });
				}
			}
			if($(".country_id").length > 0){ 
				var country_id =$( ".country_id" ).val() ;
				var city_id =$( ".city_id" ).val() ;
				$.ajax({
				  method: "POST",
				  url: base_url + "hotel/city",
				  data: { country_id: country_id,city_id:city_id}
				})
				.done(function( msg ) {
					$(".hotel_city").html(msg);
				});
			}
			
			$(document).on("change", ".country", function(){
				var country_id =$( this ).val() ;
				var name ="d_city";
				$.ajax({
					method: "POST",
					url: base_url + "package/city",
					data: { country_id: country_id ,name: name }
				})
				.done(function( msg ) {
				   $(".city").html(msg);
				 });
			});
		
			$(document).on("change", ".o_country", function(){
				var country_id =$( this ).val() ;
				var name ="o_city";
				$.ajax({
					method: "POST",
					url: base_url + "package/city",
					data: { country_id: country_id,name: name }
				})
				.done(function( msg ) {
					$(".o_city").html(msg);
				 });
			});
		
			$(document).on("change", ".hotel_country", function(){
				var country_id =$( this ).val() ;
				$.ajax({
					method: "POST",
					url: base_url + "hotel/city",
					data: { country_id: country_id}
				})
				.done(function( msg ) {
					$(".hotel_city").html(msg);
				 });
			});
		
		
			$(document).on("change", ".hotel_id", function(){
				var hotel_id =$( this ).val() ;
				var this1 = this;
				
				$.ajax({
					method: "POST",
					url: base_url + "hotel/get_hotel_json",
					data: { hotel_id: hotel_id }
				})
				.done(function( msg ) {
				   $(this1).parents(".hotel_det").find(".hotel_price").html(msg);
				   $("#double_value").val($("#hid_base_price").val());
				   if ($("#hid_cost_price").val() == '') {
					   $("#hid_cost_price").val($("#hid_base_price").val());
				   }
				   var count_hotel = $(".hotel_det").length;
				   if (count_hotel == 1) {
					   $("#double_value").not('.dv,.cv').prop("readonly", true);
				   }

					if ($("#hid_cost_price").val() !== undefined && count_hotel == 1) {
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
					}
				});
			});
					 
					
					 
			$(".add_more_hotel").click(function(){
				var hotel_det =  $("#hotel_det").clone();
				$(hotel_det).find("span.select2").detach();
				$(hotel_det).find( ".select2" ).css("width", "100%").select2();
				$(hotel_det).find( "input, select" ).val("");
				var count = $(".hotel_number").length;
				$(hotel_det).find(".hotel_price").empty();
				$(hotel_det).find(".hotel_id").removeAttr("disabled");
				$(hotel_det).find(".room_type").val('Standard Room');
				for(var i=1;i<=count;i++){
					var ii = i+1;
					$(hotel_det).find(".hotel_number").html("Hotel <a href='javascript:void(0)'  class='btn btn-primary pull-right close_hotel'>Delete</a>" );
				}
				$(hotel_det).appendTo(".add_more_hotel_place");
			});
			
			$(".add_more_flight").click(function(){
				var flight_det =  $("#flight_det").clone();
				$(flight_det).find( "input[type='text']" ).val("");
				var count = $(".flight_number").length;
				   
				for(var i=1;i<=count;i++){
					var ii = i+1;
					$(flight_det).find( ".flight_number" ).html("Flight " + ii + "<a href='javascript:void(0)'  class='btn btn-primary pull-right close_flight'>Remove</a> ");
				  }
				$(flight_det).appendTo(".add_more_flight_place");
			});
					 
			$(document).on("click", ".close_flight", function(){
				$(this).parents("#flight_det").remove();
			});
			
			$(document).on("click", ".close_hotel", function(){
			   $(this).parents("#hotel_det").remove();
			});
		
			$(document).on("click", ".delete_hotel", function(){
				if($(".delete_hotel").length > 1){
					if (current_controller == 'package' && current_method != 'duplicate') {
						var th = this;
						var hotel =$(this).data( "hotel" );
						var tour =$(this).data( "tour" );
						
						$.ajax({
							method: "POST",
							url: base_url + "package/hotel_delete" ,
							data: {hotel_id:hotel,tour_id:tour}
						})
						.done(function( msg ) {
							if(msg==1){ $(th).parents("#hotel_det").remove();}
						});
					}else{
						$(th).parents("#hotel_det").remove();
					}
				}
				else {
					alert("you can't delete this hotel minimum single  hotel requried");
				}
			});
					
			$(".room_type_list").tagsinput();
					
			$('.room_type_list').on('keypress change',function() {
				var standards = ['standard','Standard','STANDARD','STandard','STAndard','STANdard','STANDard','STANDArd','STANDARd','standarD','standaRD','standARD','stanDARD','staNDARD','stANDARD','sTANDARD','$tandard','$TANDARD'];
				var array = $(this).val().split(',');
				if (array !== '') {
					for(var i = 0; i < array.length; i++){
						if($.inArray($.trim(array[i]),standards) != -1){
							array.splice(i, 1);
						}
					}
					for(var i = 0; i < array.length; i++){
						if($.inArray($.trim(array[i]),standards) != -1){
							array.splice(i, 1);
						};
					}
					array.toString();
					$(".room_type_list").val(array);
					$('div.bootstrap-tagsinput span').each(function(){
						var $span = $(this);
						var spanTxt = $span.text();
						if($.inArray($.trim(spanTxt),standards) != -1){
							$($span).remove();
						}
					});
				}
			});
			
			$('.timepicker').datetimepicker({
				pickDate: false,
				pickSeconds: false 
			});
		
			$(".extra_option").bootstrapSwitch({
				onSwitchChange:function(event, state) {
					if($(this).is(':checked')){
						$(this).parents(".col-sm-4").next('.extra_box_div').show();
					}else { 
						$(this).parents(".col-sm-4").next('.extra_box_div').hide();
					}
				}
			});
		
			$(".price_include").click(function(){
				if($(this).val() ==1){
					$(this).parents(".extra_box_div").find("#price_box").hide().val('');
				}
				else{
					$(this).parents(".extra_box_div").find("#price_box").show();
				}
			});
					
					$(".net_com").on('change click',function(){
						if (edit_flag == 1) {
							return false;
						}
						
						if($(this).val() =="NET"){
							$(this).parents(".extra_com").find("#percentage_dollar").addClass('display_none');
							$("#percentage_box").val('');
							$("#dollar_box").val('');
							
							if ($("#double_value").length >0) {
							   $("#hid_base_price,#hid_cost_price,#double_cost_value").val($("#double_value").val());
							   
							}
							
							if ($("#single_value").length >0) {
							   $("#hid_single_price,#hid_single_cprice,#single_cost_value").val($("#single_value").val());
							   
							}
							
							if ($("#triple_value").length >0) {
								$("#hid_triple_price,#hid_triple_cprice,#triple_cost_value").val($("#triple_value").val());
							}
							if ($("#infants_value").length >0) {
								$("#hid_infants_price,#hid_infants_cprice,#infants_cost_value").val($("#infants_value").val());
							}
							if ($("#twotosix_value").length >0) {
								$("#hid_twotosix_price,#hid_twotosix_cprice,#twotosix_cost_value").val($("#twotosix_value").val());
							}
							if ($("#sixtotwelve_value").length >0) {
								$("#hid_sixtotwelve_price,#hid_sixtotwelve_cprice,#sixtotwelve_cost_value").val($("#sixtotwelve_value").val());
							}
							if ($("#twelvetosixteenth_value").length >0) {
								$("#hid_twelvetosixteenth_price,#hid_twelvetosixteenth_cprice,#twelvetosixteenth_cost_value").val($("#twelvetosixteenth_value").val());
							}
						}
						else{
							$(this).parents(".extra_com").find("#percentage_dollar").removeClass('display_none');
						}
					});
					
					
					$(document).on("change", ".flight_inclusion", function(){
		
						if(this.value =="1"){
							
							$(this).parents(".flight_incl").find(".fp").hide().find("input").val('');
						}
						else{
							$(this).parents(".flight_incl").find(".fp").show();
						}
					});
					
					$(document).on("change", ".cruise_inclusion", function(){
		
						if(this.value =="1"){
							
							$(this).parents(".cruise_incl").find(".fp").hide().find("input").val('');
						}
						else{
							$(this).parents(".cruise_incl").find(".fp").show();
						}
					});
					
					$(document).on("change", ".train_inclusion", function(){
		
						if(this.value =="1"){
							
							$(this).parents(".train_incl").find(".fp").hide().find("input").val('');
						}
						else{
							$(this).parents(".train_incl").find(".fp").show();
						}
					});
					
					$(document).on("change", ".bus_inclusion", function(){
		
						if(this.value =="1"){
							$(this).parents(".bus_incl").find(".fp").hide().find("input").val('');
						}
						else{
							$(this).parents(".bus_incl").find(".fp").show();
						}
					});  
		
		
		
					$(".extra_price_option").bootstrapSwitch({
						onSwitchChange:function(event, state) {
							if($(this).is(':checked')){ 
								$(this).parents('tr').next(".extra_price_title").slideDown();
								$(this).parents('tr').next("tr").next(".extra_price_cost").slideDown();
								$(this).parents('tr').next("tr").next("tr").next(".extra_price_price").slideDown();
							}else{
								$(this).parents('tr').next(".extra_price_title").slideUp();
								$(this).parents('tr').next("tr").next(".extra_price_cost").slideUp();
								$(this).parents('tr').next("tr").next("tr").next(".extra_price_price").slideUp();
							}
						}
					});
		
					var append_div;
					$(".add_more_transport").click(function(){
						var s =$(this).data( "id" );
						
						if(s == 1) {
							$("#flight_det").find("select").select2("destroy");
							var details  =  $("#flight_det").clone();  append_div=".add_more_transport_flight";
							$(details).find( "input, select" ).val("");
							$(details).find( ".select4" ).css("width","100%").select2();
						}
						if(s == 2) { var details  =  $("#cruise_det").clone(); append_div=".add_more_transport_cruise"; }
						if(s == 3) { var details  =  $("#train_det").clone(); append_div=".add_more_transport_train"; }
						if(s == 4) { var details  =  $("#bus_det").clone(); append_div=".add_more_transport_bus";  }
						
						$(details).find( ".transport_ids" ).val("");
						$(details).find( ".del_transport" ).removeAttr("data-transport").removeAttr("data-tour").addClass("remove_div").removeClass("del_transport");
						$(details).find( "input[type='text']" ).val("");
						$(details).find( "input[type=hidden]" ).val("");
						$(details).find(".remove_div").show();
						$(details).find(".fp").hide();
						
						 $(details).find('.timepicker').attr("id", "").removeData().off();
						 $(details).find('.timepicker').find('.add-on').removeData().off();
						 $(details).find('.timepicker').find('input').removeData().off();
						 $(details).find('.timepicker').datetimepicker({pickDate: false,pickSeconds: false });
		
		
						$(details).appendTo(append_div); 
					});   
		
					$(document).on("click", ".remove_div", function(){
						var s =$(this).data( "id" )
						if(s == 1) { var  remove_div="#flight_det";}
						if(s == 2) { var  remove_div="#cruise_det"; }
						if(s == 3) { var  remove_div="#train_det"; }
						if(s == 4) { var  remove_div="#bus_det";  }
		
						$(this).parents().remove(remove_div);
					 });
		
					$(document).on("click", ".del_transport", function(){
						var th = this;
						var id =$(this).data( "id" );
						var transport =$(this).data( "transport" );
						var tour =$(this).data( "tour" );
						if(id == 1) { var  url="flight_delete"; remove_div="#flight_det";}
						if(id == 2) { var  url="cruise_delete"; remove_div="#cruise_det"; }
						if(id == 3) { var  url="train_delete"; remove_div="#train_det"; }
						if(id == 4) { var  url="bus_delete";  remove_div="#bus_det";  }
						if (current_controller == 'package' && current_method != 'duplicate') {
							$.ajax({
								method: "POST",
								url: base_url + "package/" + url,
								data: {transport_id:transport,tour_id:tour}
							})
							.done(function( msg ) {
								if(msg==1){$(th).parents().remove(remove_div);}
							});
						}else{
							$(th).parents().find(remove_div).prop("disabled", true);
							$(th).parents().remove(remove_div);
						}
					});
		
		
					//Datepicker
					$('#datepicker1').datepicker();
					$('#datepicker2').datepicker();
		
					$(".show_more").click(function(){
						$(".hide_more").hide();
						$(".show_more").show();
						$(".textarea").hide();
						$(this).next(".textarea").slideDown(1000);
							$(this).hide();
							$(this).prev(".hide_more").show();
					
					});  
		
					$(".hide_more").click(function(){
						$(".textarea").hide();
						$(".hide_more").hide();
						$(".show_more").show();
					});  
		
					$(document).ready(function(){
					  $('body').on('click', 'a.cancel_delete_btn', function() {
					   $(this).closest("#cancel_policy").remove();
					  });
					});
		
					$(document).ready(function(){
					  $('body').on('change', '#cancel', function() {
						if($(this).val()==1){
						$(".cancel_policy_type").show(); }
					
					  else {
						 $(".cancel_policy_type").hide();
					  }
		
						});
					 });
					
					$(".remove_image").on('click',function(){
						var data = $(this).data('value').split('/');
						var id = data[0];
						var name = data[1];
						$("#confirmDelete").modal('show');
						$("#confirm").on('click',function(){
							$.ajax({
								method: "POST",
								url: base_url + "hotel/delete_gallery",
								data: { id: id,name:name }
							})
							.done(function( msg ) {
								$("#gallery"+id).remove();
								$("#confirmDelete").modal('hide');
							});
						});
					});
					
					
					$(document).on('click','.tour_submit',function(){
						var hotel_count = $(".hotel_name").length;
						var extra = hotel_count-12;
						
						if (hotel_count > 12) {
							$("body").prepend(custom_fn.model_template);
							$(".model_template").addClass("hotel_warning");
							$(".hotel_warning").find('.btn-primary').remove();
							$(".hotel_warning").find("h4.modal-title").text("Tour Management - Adding hotels");
							var modal_data = "<ul>";
							modal_data += "<li>Maximum 12s hotels can be add at each Tour</li>";
							modal_data += "<li>Here you have added "+hotel_count+" hotels</li>";
							modal_data += "</ul>";
							modal_data += "<br>So please remove  "+extra+" hotels to complete this process";
							$(".hotel_warning").find("div.modal-body").html(modal_data);
							$(".hotel_warning").toggle();
							return false;
						}
					});
					
					
					$(document).on('submit','form#add_package',function(e){
						$(".tour_submit").hide();
						$(".tour_loader").show();
					});
					
					$(document).on("keyup", 'input.money', function(event) {
						if(event.which >= 37 && event.which <= 40) return;
						$(this).val(function(index, value) {
							return value
							.replace(/\D/g, "")
							.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
							;
						});
						
					});
					if(current_method != 'create'){
						$("input.money").each(function(){
							var input = $(this).val();			
							var cnvrt = money(input);
							$(this).val(cnvrt);
						});
					}	
				}
		
		
		if($("#master_price").length > 0){
			$(document).on("change", ".list_hotels", function(){
				var params = $(this).val().split('-');
				var hotel_id = params[0];
				var tour_id = params[1];
				var df = $("#df").val();
				$("#selected_hotel").val(hotel_id);
				$("#tour_id").val(tour_id);
				var flag = $("#extra_flag").val();
				var room_type = 'Standard';
				var selected_days = $("#selected_days").val().split(',');
				
				if (flag == 0) {
					var place = 'price_sector';
				}else{
					var place = 'price_sector_extra';
				}
				
				$.ajax({
					method: "POST",
					url: base_url + "package/get_hotel_price_master",
					data: { hotel_id: hotel_id,tour_id:tour_id,df:df,flag:flag,room_type:room_type,days:selected_days }
				})
				.done(function( msg ) {
					$(".type_room").removeClass('display_none');
					$(".hotel_extra_price").removeClass('display_none');
					$("#"+place).html(msg);
                    $(".days_search").removeClass('display_none');
				});
			});
            
            $("#checkAll_master").click(function () {
			    $("input.days_master").prop('checked', $(this).prop("checked"));
			});
            
            $("input:checkbox.days_master,input:checkbox#checkAll_master").change(function(){
                var selected_days = [];
                $("input:checkbox[class=days_master]").each(function() {
                    if($(this).is(':checked')){
                        selected_days.push($(this).attr('value'));
                    }
                });
				
				
                $("#selected_days").val(selected_days);
				//if (selected_days.length > 0) {
                    var room_type = $('select[name="change_room_type"]').val();
                    var hotel_id = $("#selected_hotel").val();
					var tour_id = $("#tour_id").val();
					var df = $("#df").val();
					var flag = $("#extra_flag").val();
                    if (flag == 0) {
						var place = 'price_sector';
                    }else{
						var place = 'price_sector_extra';
                    }
					$.ajax({
                        method: "POST",
                        url: base_url + "package/get_hotel_price_master",
                        data: { hotel_id: hotel_id,tour_id:tour_id,df:df,flag:flag,days:selected_days,room_type:room_type }
                    })
                    .done(function( msg ) {
						$(".hotel_extra_price").removeClass('display_none');
                        $("#"+place).html(msg).slideDown();
                        $(".days_search").removeClass('display_none');
                    });
            });
		}
		
		$(".price_extra").bootstrapSwitch({
			onSwitchChange:function(event, state) {
         		if($(this).is(':checked')){ 
                    $("#extra_flag").val(1);
					$('#change_label').bootstrapSwitch('onText', 'Extra night price');
          		}
          		else{
					$('#change_label').bootstrapSwitch('onText', 'Regular night price');
					$("#extra_flag").val(0);
          		}
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
          	}
        });

	});
	
function money(val){
	while (/(\d+)(\d{3})/.test(val.toString())){
	  val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
	}
	return val;
}

function get_price_room(base_price,discount_price,percentage_price,dollar_price,single_value,triple_value,infants_value,twotosix_value,sixtotwelve_value,twelvetosixteenth_value) {
    if (discount_price !== '') {
        if (discount_price !== 0) {
            base_price = discount_price;
        }
	}
	
	$("#hid_base_price").val(base_price);
	if (percentage_price !== 0 && base_price !== '') {
		var cal = parseInt($("#hid_base_price").val().replace(/,/g , ""))*(percentage_price/100);
		var final_rate = parseInt($("#hid_base_price").val().replace(/,/g , ""))-parseInt(cal);
    }
	if (percentage_price !== 0 && single_value !== '') {
		var cal_single = parseInt($("#hid_single_price").val().replace(/,/g , ""))*(parseInt(percentage_price)/100);
		var final_rate_single = parseInt($("#hid_single_price").val().replace(/,/g , ""))-parseInt(cal_single);
    }
    if (percentage_price !== 0 && triple_value !== '') {
		var cal_triple = parseInt($("#hid_triple_price").val().replace(/,/g , ""))*(parseInt(percentage_price)/100);
		var final_rate_triple = parseInt($("#hid_triple_price").val().replace(/,/g , ""))-parseInt(cal_triple);
    }
    if (percentage_price !== 0 && infants_value !== '') {
		var cal_infants = parseInt($("#hid_infants_price").val().replace(/,/g , ""))*(parseInt(percentage_price)/100);
		var final_rate_infants = parseInt($("#hid_infants_price").val().replace(/,/g , ""))-parseInt(cal_infants);
    }
    
    if (percentage_price !== 0 && twotosix_value !== '') {
		var cal_twotosix = parseInt($("#hid_twotosix_price").val().replace(/,/g , ""))*(parseInt(percentage_price)/100);
		var final_rate_twotosix = parseInt($("#hid_twotosix_price").val().replace(/,/g , ""))-parseInt(cal_twotosix);
    }
    
	if (percentage_price !== 0 && sixtotwelve_value !== '') {
		var cal_sixtotwelve = parseInt($("#hid_sixtotwelve_price").val().replace(/,/g , ""))*(parseInt(percentage_price)/100);
		
		var final_rate_sixtotwelve = parseInt($("#hid_sixtotwelve_price").val().replace(/,/g , ""))-parseInt(cal_sixtotwelve);
    }
	if (percentage_price !== 0 && twelvetosixteenth_value !== '') {
		var cal_twelvetosixteenth = parseInt($("#hid_twelvetosixteenth_price").val().replace(/,/g , ""))*(parseInt(percentage_price)/100);
		var final_rate_twelvetosixteenth = parseInt($("#hid_twelvetosixteenth_price").val().replace(/,/g , ""))-parseInt(cal_twelvetosixteenth);
    }
	
	
	if(dollar_price !== 0 && base_price !== ''){
		var final_rate = dollar_price;
	}
	
	if(dollar_price !== 0 && single_value !== ''){
		var final_rate_single = dollar_price;
	}
    if(dollar_price !== 0 && triple_value !== ''){
		var final_rate_triple = dollar_price;
	}
	if(dollar_price !== 0 && infants_value !== ''){
		var final_rate_infants = dollar_price;
	}
	if(dollar_price !== 0 && twotosix_value !== ''){
		var final_rate_twotosix = dollar_price;
	}
	if(dollar_price !== 0 && sixtotwelve_value !== ''){
		var final_rate_sixtotwelve = dollar_price;
	}
	if(dollar_price !== 0 && twelvetosixteenth_value !== ''){
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
			$("#double_cost_value").val(money($("#hid_cost_price").val()));
		}
    }
	if ($("#single_cost_value").length > 0) {
		if ($("#hid_single_cprice").val() == '') {
            $("#single_cost_value").val($("#hid_single_price").val());
        }else {
			$("#single_cost_value").val(money($("#hid_single_cprice").val()));
		}
    }
    
    if ($("#triple_cost_value").length > 0) {
		if ($("#hid_triple_cprice").val() == '') {
            $("#triple_cost_value").val($("#hid_triple_price").val());
        }else {
			$("#triple_cost_value").val(money($("#hid_triple_cprice").val()));
		}
    }
	
    if ($("#infants_cost_value").length > 0) {
		if ($("#hid_infants_cprice").val() == '') {
            $("#infants_cost_value").val($("#hid_infants_price").val());
        }else {
			$("#infants_cost_value").val(money($("#hid_infants_cprice").val()));
		}
    }
	
    if ($("#twotosix_cost_value").length > 0) {
		if ($("#hid_twotosix_cprice").val() == '') {
            $("#twotosix_cost_value").val($("#hid_twotosix_price").val());
        }else {
			$("#twotosix_cost_value").val(money($("#hid_twotosix_cprice").val()));
		}
    }
	
    if ($("#sixtotwelve_cost_value").length > 0) {
		if ($("#hid_sixtotwelve_cprice").val() == '') {
            $("#sixtotwelve_cost_value").val($("#hid_sixtotwelve_price").val());
        }else {
			$("#sixtotwelve_cost_value").val(money($("#hid_sixtotwelve_cprice").val()));
		}
    }
	
    if ($("#twelvetosixteenth_cost_value").length > 0) {
		if ($("#hid_twelvetosixteenth_cprice").val() == '') {
            $("#twelvetosixteenth_cost_value").val($("#hid_twelvetosixteenth_price").val());
        }else {
			$("#twelvetosixteenth_cost_value").val(money($("#hid_twelvetosixteenth_cprice").val()));
		}
    }
}
});
