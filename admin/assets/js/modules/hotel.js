require(["custom_defination", "async!https://maps.googleapis.com/maps/api/js?key=AIzaSyAEjc4IC_8he4zTPM3Gz95Ekm4lOeXXh0w&v=3.exp&sensor=false&libraries=places"], function(custom_fn, gmaps)
{
	$(document).ready(function()
	{
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
		if($("form.add_credit_form").length > 0)
			custom_fn.load_validate("add_credit_form");
			
		if($("table.manage_hotels").length > 0){
			$("table.manage_hotels").dataTable({
				"dom": "lfrtip",
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6, 7]}]
			});
			
			$(document).on('click','.delete_hotel',function(){
				if (confirm("Are you sure want to delete this hotel ?")){
					var id = $(this).data('id');
					var logo = $(this).data('logo');
					if (id !== '' && logo !== '') {
                        location.href= base_url + "hotel/delete/"+id+'/'+logo;
                    }
				}
			});
			
			$(document).on("click", ".change_hotel_status", function(){
				var tht = this;
				var status = $(this).data("status");
				var id = $(this).data("id");
				$.ajax({
					url: base_url + "hotel/status/" + id + "/" + status
				})
				.done(function( msg ) {
					if(msg==1){
						if(status===0){
							$(tht).removeClass("label-success").addClass("label-danger").html('<i class="icon-ok"></i>InActive').data('status','1');
						}
						if(status==1){
							$(tht).removeClass("label-danger").addClass("label-success").html('<i class="icon-ok"></i>Active').data('status','0');
						}
					}
				});
			});
		}
		
		if($("form.add_hotel").length > 0){
			$(".select2").select2();
			$(".add_hotel").validate();
			
			$(document).on("change", ".hotel_country", function(){
				var country_id =$( this ).val() ;
				$.ajax({
				  method: "POST",
				  url: base_url + "hotel/city",
				  data: { country_id: country_id}
				})
				.done(function( msg ) {
				  $(".hotel_city").html(msg);
				  $("select.hotel_city").select2();
				});
			});
			
			if($("#hotel_maps").length > 0){
				$("#get_locate").click(function(){
					if($("#latitude").val() === ''){
						$("#latitude").attr('placeholder','enter latitude here');
					}else{
						var lt = $("#latitude").val();	
					}
					if($("#longitude").val() == ''){
						$("#longitude").attr('placeholder','enter longitude here');
					}else{
						var ln = $("#longitude").val();	
					}
					if (ln == undefined ||  lt == undefined) {
						return false;
					}else{
						initialize(lt,ln);
					}
				});
				initialize();
			}
					
					$(".room_type_list").tagsinput();
					
					$('.room_type_list').on('keypress change',function() {
						var standards = ['standard','Standard','STANDARD','STandard','STAndard','STANdard','STANDard','STANDArd','STANDARd','standarD','standaRD','standARD','stanDARD','staNDARD','stANDARD','sTANDARD','$tandard','$TANDARD'];
						var array = $(this).val().split(',');
						if (array !== '') {
							for(var i = 0; i < array.length; i++){
								if($.inArray($.trim(array[i]),standards) != -1){
									array.splice(i, 1);
								};
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
					
				$(document).on("click", ".remove_preview",function(){
					var rem_arr = [];
					var id = $(this).closest("div").attr("id");
					$("#"+id).remove();
					var c = 0;
					$(".p_images").each(function(){
						c++;
						var gid = $(this).attr('id').split("img");
						rem_arr.push(gid[1]);
						c++;
					});
					$("#file_index").val(rem_arr);
				});

			$("#imageupload").on('change', function () {
				var countFiles = $(this)[0].files.length;
				var imgPath = $(this)[0].value;
				var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
				var image_holder = $("#preview-image");
				image_holder.empty();
			
				if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico")  {
					if (typeof (FileReader) != "undefined") {
							var rem1_arr = [];
						for (var i = 0; i < countFiles; i++) {
							var reader = new FileReader();
							reader.iindex = i;
							rem1_arr.push(i);
							reader.onload = function (e) {
							   $("<div class='p_images' id='img"+e.target.iindex+"'><span class='remove_preview'><i class='icon-trash'></i></span><img src='"+e.target.result+"' class='thumbimage'></div>").appendTo(image_holder);
						   }
						   
							$("#file_index").val(rem1_arr);
							image_holder.show();
							reader.readAsDataURL($(this)[0].files[i]);
						}
			
					} else {
						$(".file_type_err").text('unsupported file type !!').fadeIn().delay(2000).fadeOut('slow'); 
					}
				} else {
				   $(".file_type_err").text('unsupported file type !!').fadeIn().delay(2000).fadeOut('slow'); 
			   }
			});
				
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
			
			$(document).on('click','.more_type',function(){
				$("#modal_concat").modal('show');
			});
		}	
	});
	
	function initialize(l1,l2) {
		var latitude =$("#latitude").val();
		var longitude = $("#longitude").val();
		if (l1) {
			latitude = l1;
		}
		if (l2) {
			longitude = l2;
		}
		var input = document.getElementById('auto_place');
		var autocomplete = new google.maps.places.Autocomplete(input);
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			var lat = place.geometry.location.lat();
			var lon = place.geometry.location.lng();
			$("#latitude").val(lat);
			$("#longitude").val(lon);
			initialize();
		});

		var latlng = new google.maps.LatLng(latitude,longitude);
		var myOptions = {
			scrollwheel: false,								
			zoom: 10,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("hotel_maps"),
		myOptions);

		var marker=new google.maps.Marker({
			position:latlng,
			draggable :true
		});

		marker.setMap(map);
		google.maps.event.addListener(marker, 'dragend', function(){
		// Get the Current position, where the pointer was dropped
		var point = marker.getPosition();
		// Center the map at given point
		map.panTo(point);
		// Update the textbox
		document.getElementById('latitude').value=point.lat();
		document.getElementById('longitude').value=point.lng();
		});
	}

});
