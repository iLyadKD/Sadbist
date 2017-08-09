require([], function()
{
	
	//global variables
	var touch = false;
	var base_url = $("head").data("base-url");
	var controller = $("head").data("controller");
	var method = $("head").data("method");
	var logged_in = $("head").data("id");
	var file_ext = $("head").data("file-ext");	
	
	
	$(document).ready(function() {
			/*****Tour Code*****/
			

		
		if($("#tour_checkin").length > 0){

			// $("#tour_checkin").datepicker({
			// 	minDate: 0,
			// 	onSelect: function(selected) {
			// 		$("#tour_checkout").datepicker("option","minDate", selected);
			// 	}
			// });

			// $("#tour_checkout").datepicker({ 
			// 	minDate: 0,
			// 	onSelect: function(selected) {
			// 		$("#tour_checkin").datepicker("option","maxDate", selected);
			// 	}
			// });  
		}

		if($("#tour_room").length > 0){
			$('#tour_room').on('change', function() {
				var s ;
				var room_count = this.value;
				var passenger = $("#tour_passenger").clone();
				$(".add_tour_passenger").empty();
				if(room_count >1 ){	
					for (var i = 1 ; i < room_count; i++) {		
					  	s = i+ 1;
						$(passenger).find( "#room_count" ).html("<br><h3>Rooms " + s + "</h3>" );
					    passenger.clone().appendTo(".add_tour_passenger");
		         	}
		     	}
			});
		}	

		if($("#fromcity").length > 0){
			if (controller != "tour" && method != 'search' ) {
               //$("#tocity").prop('disabled', true);
           }
			
			
			$(document).ready(function(){
				$("#fromcity").autocomplete({
					source: base_url+"general/get_cities_country",
					minLength: 1,
					autoFocus: true,
					select: function(event, ui) {
						$(this).val(ui.item.value);
						get_tour(ui.item.id_value);
						$("#from_hid").val(ui.item.value);
						return false;
					}
				});

				
				$("#tocity").autocomplete({
					 source: function (request, response) {
						$.ajax({
							url: base_url+"general/get_cities_country",
							data: { term: request.term,city_ids:$("#arr_city").val() },
							dataType: "json",
							success: response,
							error: function () {
								response([]);
							}
						});
					},
					minLength: 1,
					autoFocus: true,
					select: function(event, ui) {
						$(this).val(ui.item.value);
						$("#to_hid").val(ui.item.value);
						return false;
					}
				});
				

			});
			
			
			
			$('#fromcity,#tocity').on('input', function() {
				var from = hasValue("#fromcity");
				var to = hasValue("#tocity");
				if (from == true && to == true) {
					$("#tour_search_btn").prop('disabled',false);
                }else{
					 $("#tour_search_btn").prop('disabled',true);
				}
			});
		}
		
		if($(".mn_facilities").length > 0){
			$(".mn_facilities :checkbox").change(function(){
				
				var id = $(this).data('id');
				var value = $("#"+id).val();
				var checked = $("#"+id).is(':checked');
				if (checked == true) {
					var exist_val = $("#changeval").html();
					var final_price = parseInt(exist_val)+parseInt(value);
					$("#changeval").html(final_price);
					var current_val = $("#extra_cost").val();
					$("#extra_cost").val(parseInt(current_val)+parseInt(value));
                }else{
					var exist_val = $("#changeval").html();
					var final_price = parseInt(exist_val)-parseInt(value);
					$("#changeval").html(final_price);
					var current_val = $("#extra_cost").val();
					$("#extra_cost").val(parseInt(current_val)-parseInt(value));
				}
				
				
				
				$("#price_tour").val($("#changeval").html());
			});
		}
		
		
		
		/************POPUP (Hotel price calculation) START***************/


$(document).on("click", ".mn_night", function () {
	 var id_hotel = $("#id_hotel").val();
	 var hotel_id = $(this).data('id');
	 var value = $(this).data('value').split('/');
	 var type = value[0];
	 var tour_date = value[1];
	 
	 $("#type").val(type);
	if (id_hotel != hotel_id) {
		return false;
    }
	
	
	$.ajax({
			data: {hotel_id: hotel_id,type:type,tour_date:tour_date},
			method:'post',
			url: base_url + "tour/get_extra_price",
			success: function(data){    
				if (data != 0) {
					var obj = $.parseJSON(data);
                        if (type == 'single') {
                           var data_value =  obj.single;
						   $("#pop_number").attr('max',obj.single_qty);
                        }
						if (type == 'double') {
                           var data_value =  obj.doubles;
						   $("#pop_number").attr('max',obj.double_qty);
                        }
						if (type == 'triple') {
                           var data_value =  obj.triple;
						   $("#pop_number").attr('max',obj.triple_qty);
                        }
						if (type == 'infants') {
                           var data_value =  obj.infants;
						   $("#pop_number").attr('max',obj.infants_qty);
                        }
						if (type == 'twotosix') {
                           var data_value =  obj.twotosix;
						   $("#pop_number").attr('max',obj.twotosix_qty);
                        }
						if (type == 'sixtotwelve') {
                           var data_value =  obj.sixtotwelve;
						   $("#pop_number").attr('max',obj.sixtotwelve_qty);
                        }
						if (type == 'twelvetosixteenth') {
                           var data_value =  obj.twelvetosixteenth;
						   $("#pop_number").attr('max',obj.twelvetosixteenth_qty);
                        }
						
						
						
						if ($("#price_"+type+hotel_id).val() != undefined && $("#price_"+type+hotel_id).val() == 0) {
                            $("#price_"+type+hotel_id).val(data_value*$("#currency").val());
                        }
						if ($("#price_"+type+hotel_id).val() != undefined)  {
							$("#pop_price").html($("#price_"+type+hotel_id).val()*$("#currency").val());
						}else{
							$("#price_"+type+hotel_id).remove();
							$("#append").prepend("<input type='hidden' id='price_"+type+hotel_id+"' value='"+data_value+"'>");
							$("#pop_price").html($("#price_"+type+hotel_id).val()*$("#currency").val());
						}
						
						if ($("#number_"+type+hotel_id).val() != undefined) {
							var pop_num = $("#number_"+type+hotel_id).val();
							if (pop_num == '') {
								pop_num = 0;
                            }
							$("#pop_number").val(pop_num);
						}else{
							$("#number_"+type+hotel_id).remove();
							$("#append").prepend("<input type='hidden' id='number_"+type+hotel_id+"'>");
							$("#pop_number").val(0);
						}
					$("#hid_"+type+hotel_id).val(data_value*$("#currency").val());
					
                }
			}
		});
});

$("#pop_number").change(function(){
	
	var hotel_id = $("#hotel_id").val();
	var number = $(this).val();
	
	if (number) {
        var type = $("#type").val();
		if ($("#count_"+type+hotel_id).val() == 0 || $("#count_"+type+hotel_id).val() == '') {
            return false;
        }
		$("#number_"+type+hotel_id).val(number);
		change_price(number,type,hotel_id);
    }
});




		
	if($("#products").length > 0) 	{
		var loading_img ="<p id='img_loading' class='text-center'><img src='"+ base_url +"assets/images/loader/loading.gif' alt'Loading'><br><h3 class='text-center' ></h3>";
		$('#products').html(loading_img);
		$("#compact-pagination").css('display','none');
		
		setTimeout(function(){
			var search=$( "#modify_package" ).serialize();
			$.ajax({
				method: "get",
				url: base_url +"tour/result",
				data: search
			})
			.done(function( msg ) {
				$('#products').html(msg);
				$('#compact-pagination').show();
				
				
				$('#compact-pagination').pagination({
					pages: 7,
					cssStyle: 'compact-theme',
					displayedPages: 7
				});
				
				var min_value = $("body").find("#min_range").val();
				var max_value = $("body").find("#max_range").val();
				if (min_value == max_value) {
					min_value = 0;
				}
				if (min_value == '') {
					min_value = 1;
				}
				if (max_value == '') {
					max_value = 20000;
				}
				change_slider(min_value,max_value);
				if ($("#t_date").val() != 0) {
                   $("#tour_date").html($("#t_date").val());
                }
			});
		}, 1000);
		
		
		
		
		
		if($("form#fliter").length > 0){
		$( "input[type='checkbox']" ).change(function(){
			ajaxsearch();
		});
		}
		
		
		$("#click_modify").click(function(){
		$("#show_search").slideToggle();
		});
		
		$(".segmented_btn").click(function(){
		$(".segmented_btn").removeClass('active');
		var order_by = $(this).attr("data-id");
		var order_by_value = $(this).attr("data-value");
		$("#order_by").val(order_by);
		$("#order_by_value").val(order_by_value);
		if(order_by_value==1){
		$(this).attr("data-value",0);
		}else {
		$(this).attr("data-value",1);
		}
		$(this).addClass("active");
		ajaxsearch();
		}); 
		
		
		
		
		$('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
		$('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');
		$('#products .item').addClass('grid-group-item');});
		
		
		
		
		/*$('.segmented_btn').on('click',function(){
			$("#page").val(1);	
		});*/
		

		

}
		
		
    
   
	
		/************POPUP (Hotel price calculation) END***************/
	





 

 

$('.roomtype:checkbox').on('change',function(){
	var type_store = [];
	var hotel_id = $("#hotel_id").val();
	var type = $(this).data('value');
	$("#count_"+type+hotel_id).val(1);
	$("#count_"+type+hotel_id).prop('disabled',false);
	if(!this.checked) {
		$("#count_"+type+hotel_id).val(0);
		$("#count_"+type+hotel_id).prop('disabled',true);
    }
	
	$('input:checkbox.roomtype').each(function () {
	   if (this.checked) {
        type_store.push($(this).data('value'));
       }
	});
	
	if(type_store.length > 0) {
		var type_price = [];
		$.each(type_store,function(index,value){
            if (value != 'single') {
                type_price.push($("#display_"+value+hotel_id).html());
            }
			
		});

		var total = 0;
		$.each(type_price,function() {
			total += parseInt(this);
		});
		var tour_price = parseInt($("#overall_tour_price").val())+parseInt(total);
		$("#changeval").html(tour_price);
		
    }
	
});
 
 

 
	
$('.hotel_variables:checkbox').on('change',function(){
	
	var chk = $(this).data('id');
	$("#id_hotel").val(chk);
	
	if (chk) {
		$(".txt_count").val('');
		$("#count_double"+chk).val(2);
		$("#display_single"+chk).html($("#single_val"+chk).val());
		$("#display_double"+chk).html($("#double_val"+chk).val());
		$("#display_triple"+chk).html($("#triple_val"+chk).val());
		$("#display_infants"+chk).html($("#infants_val"+chk).val());
		$("#display_twotosix"+chk).html($("#twotosix_val"+chk).val());
		$("#display_sixtotwelve"+chk).html($("#sixtotwelve_val"+chk).val());
		$("#display_twelvetosixteenth"+chk).html($("#twelvetosixteenth_val"+chk).val());
	}
	
	
	var param = $(this).data('value').split("-");
	var lat = param[0];
	var lon = param[1];
	var hotel_name = param[2];
	
	var hotel_id = chk;
	var hotel_price = $("#display_double"+hotel_id).html();
	$("#overall_tour_price").val(hotel_price);
	
	var overall_tour_price = $("#overall_tour_price").val();
	var trans_cost = $("#transportation_cost").val()*total_people();
	var tour_price = overall_tour_price*2+parseInt(trans_cost)+parseInt($("#extra_cost").val());
	$("#changeval").html(tour_price);
	$("#price_tour").val($("#changeval").html());
	
	
	$("#latitude").val(lat);
	$("#longitude").val(lon);
	$("#h_name").val(hotel_name);
	initialize(lat,lon,hotel_name);
	
	
	
	
	
	$("#pop_number").val(0);
	$("#pop_price").html('');
	 var th = $(this);
	 name = th.prop('name'); 
	 if(th.is(':checked')){
		 $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);   
	  }
		var hotel_id = $(this).data('id');
		$("#hotel_id").val(hotel_id);
		var price = $(this).data('value')*$("#total_nights").val();
		
		if (price) {
			$("#changeval").html(price+parseInt(transportation_cost)+parseInt($("#extra_cost").val()));
			$("#price_tour").val($("#changeval").html());
		}
		
		$("input[name=radio_hotel_type][value=" + 1 + "]").prop('checked', true);
		
		$(".txt_count").prop('disabled',true);
		$(".hotel_list").find('input').attr("disabled", "disabled");
		$(".table_tr").find('span').attr('data-target','');
		$(".table_tr").css('background-color', '#e6e6e6');
		
		$("#count_triple"+hotel_id).prop('disabled', false);
		$("#count_double"+hotel_id).prop('disabled', false);
		$("#count_single"+hotel_id).prop('disabled', false);
		
		$("#count_infants"+hotel_id).prop('disabled', false);
		$("#count_twotosix"+hotel_id).prop('disabled', false);
		$("#count_sixtotwelve"+hotel_id).prop('disabled', false);
		$("#count_twelvetosixteenth"+hotel_id).prop('disabled', false);
		$("#count_single"+hotel_id).closest('tr').css('background-color', '');
		
		$("#pop_price").html('');
		$("#pop_number").val(0);
	 if ($.inArray(chk, store) == -1) {
		store.push(chk);
	 }
	 
		$.each(store, function(k,v) {
					if (chk != v) {
						$("#display_single"+v).html($("#single_val"+v).val());
						$("#display_infants"+v).html($("#infants_val"+v).val());
						$("#display_twotosix"+v).html($("#twotosix_val"+v).val());
						$("#display_sixtotwelve"+v).html($("#sixtotwelve_val"+v).val());
						$("#display_twelvetosixteenth"+v).html($("#twelvetosixteenth_val"+v).val());
					}
					
		});
		
		
		$('#single'+hotel_id).attr('data-target','#myModal_night1');
		$('#double'+hotel_id).attr('data-target','#myModal_night1');
		$('#triple'+hotel_id).attr('data-target','#myModal_night1');
		$('#infants'+hotel_id).attr('data-target','#myModal_night1');
		$('#twotosix'+hotel_id).attr('data-target','#myModal_night1');
		$('#sixtotwelve'+hotel_id).attr('data-target','#myModal_night1');
		$('#twelvetosixteenth'+hotel_id).attr('data-target','#myModal_night1');
		
		
		
	});

	$('input[type=radio][name=radios]').change(function() {
		var data = $(this).data('value').split(',');
		var hotel_id = data[0];
		var rm_type = data[1];
		
	});

	 $(".txt_count").change(function(){
		var hotel_id = $("#hotel_id").val();
		var key_val = $(this).val();
		if (key_val != '') {
            var chk = $(this).data('id');
			var type = $(this).data('value');
			
			if (type == 'single') {
                var dt = $("#count_single"+hotel_id).val();
				$("#single_room").val(dt);
            }
			if (type == 'double') {
                var dt = $("#count_double"+hotel_id).val()/2;
				$("#double_room").val(dt);
            }
			if (type == 'triple') {
                var dt = $("#count_triple"+hotel_id).val()/3;
				$("#triple_room").val(dt);
            }
			if (type == 'infants') {
                var dt = $("#count_infants"+hotel_id).val();
				$("#infants").val(dt);
            }
			if (type == 'twotosix') {
                var dt = $("#count_twotosix"+hotel_id).val();
				$("#twotosix").val(dt);
            }
			if (type == 'sixtotwelve') {
                var dt = $("#count_sixtotwelve"+hotel_id).val();
				$("#sixtotwelve").val(dt);
            }
			if (type == 'twelvetosixteenth') {
                var dt = $("#count_twelvetosixteenth"+hotel_id).val();
				$("#twelvetosixteenth").val(dt);
            }
			
			var trans_cost = $("#transportation_cost").val()*total_people();	
			var get_total = total_room_cost(hotel_id)+parseInt(trans_cost)+parseInt($("#extra_cost").val())+parseInt(get_extra_night_price());
			$("#changeval").html(get_total);
			$("#price_tour").val($("#changeval").html());
			
			var count = $("#count_"+type+hotel_id).val();
			if (count > 0) {
               $("#"+type+hotel_id).show();
            }else{
				$("#"+type+hotel_id).css('cssText','display:none !important;');
			}
			
        }
		
		if ($("#count_single"+hotel_id).val() == 0 && $("#count_double"+hotel_id).val() == 0 && $("#count_triple"+hotel_id).val() == 0) {
           $(".cta").prop('disabled',true).css('opacity','0.4'); 
        }else{
			$(".cta").prop('disabled',false).css('opacity','1'); 
		}
		
		
});
	 
	  $('input[type=radio][name=trans]').change(function() {
		var price = $(this).data('value');
		if (price == '') {
            price = 0;
        }
		var param = this.value;
		$("#trans_param").val(param);
		$("#transportation_cost").val(price);
		
		var trans_cost = $("#transportation_cost").val()*total_people();
		
		var chk_trans = param.split("_");
		if (chk_trans[0] == "flight") var main_trans = 'plane';
		if (chk_trans[0] == "train") var main_trans = 'train';
		if (chk_trans[0] == "bus") var main_trans = 'bus';
		
		var trans_param = $(this).data('id').split('--');
		
		var name = trans_param[0];
		var departuer_place = trans_param[1];
		var arrival_place = trans_param[2];
		var departuer_time = trans_param[3];
		var arrival_time = trans_param[4];
		var return_deapartur = trans_param[5];
		var return_arrival = trans_param[6];
		var return_departure_time = trans_param[7];
		var return_arrival_time = trans_param[8];
		
		$('#modal_transport_details').modal('show');
		
		$("#trans_name").html(name);
		$("#transclass").attr('class',"fa fa-"+main_trans);
		$("#trans_dept").html(departuer_place+'&nbsp;,&nbsp;'+departuer_time);
		$("#trans_arr").html(arrival_place+'&nbsp;,&nbsp;'+arrival_time);
		$("#trans_ret_dep").html(return_deapartur+'&nbsp;,&nbsp;'+return_departure_time);
		$("#trans_ret_arr").html(return_arrival+'&nbsp;,&nbsp;'+return_arrival_time); 
		
		var hotel_id = get_hotel_id();
		var get_cost_total = total_room_cost(hotel_id);
		$("#changeval").html(get_cost_total+parseInt(trans_cost)+parseInt($("#extra_cost").val()));
		$("#price_tour").val($("#changeval").html());
	});	 
	
	
	
	
 
	var store = [];
	if($("#hotel_map").length > 0){
		 $("#box_info_1").sticky({ topSpacing: 0 });
		initialize($("#latitude").val(),$("#longitude").val(),$("#h_name").val());
		store.push($("#hotel_id").val());
		
		var showChar = 200;  
    var ellipsestext = "...";

    var lesstext = "Show more >";
    var moretext = "Show less";
    

    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
            $(this).html(html);
        }
    });
 
    
	$(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
	
	

	if (controller == "tour" && method == "details") {
		var options = {
		$AutoPlay: true,                                    
		$AutoPlayInterval: 4000,                            
		$SlideDuration: 500,                                
		$DragOrientation: 3,                                
		$UISearchMode: 0,                                   

		$ThumbnailNavigatorOptions: {
			$Class: $JssorThumbnailNavigator$,              
			$ChanceToShow: 2,                               

			$Loop: 1,                                       
			$SpacingX: 3,                                  
			$SpacingY: 3,                                   
			$DisplayPieces: 6,                              
			$ParkingPosition: 253,                          

			$ArrowNavigatorOptions: {
				$Class: $JssorArrowNavigator$,              
				$ChanceToShow: 2,                               
				$AutoCenter: 2,                                 
				$Steps: 6                                       
			}
		}
	};

	var jssor_slider1 = new $JssorSlider$("slider1_container", options);
	function ScaleSlider() {
		var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
		if (parentWidth)
			jssor_slider1.$ScaleWidth(Math.min(parentWidth, 800));
		else
			window.setTimeout(ScaleSlider, 30);
	}
	ScaleSlider();

	$(window).bind("load", ScaleSlider);
	$(window).bind("resize", ScaleSlider);
	$(window).bind("orientationchange", ScaleSlider);
	
		$(window).scroll(function(){
			if ($(window).scrollTop() > 0) {
				$(".box_info").css('opacity','0.7');
			}else{
				$(".box_info").css('opacity','1');
			}
		});

    }
	
	
	
	
	
	
	
	}
		


 
    /******Prebook page******************************START******/
	
	
	$('.nav-tabs > li a[title]').tooltip();
    
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

	
	
	
	$(".next-step1").click(function (e) {
        if($("#prebook_submit").valid()){
			var $active = $('.wizard .nav-tabs li.active');
			$active.next().removeClass('disabled');
			nextTab($active);	
		}
    });
	$(".next-step2").click(function (e) {
        if($("#prebook_submit").valid()){
			var $active = $('.wizard .nav-tabs li.active');
			$active.next().removeClass('disabled');
			nextTab($active);	
		}
    });
	
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });

	
 
    /*******Prebook page******************************END******/
	
    

	});
	
	
	
 
 
 function final_price(txtval,type,extra_total){	
	var transportation_cost = parseInt($("#transportation_cost").val());
	var hotel_id = $("#hotel_id").val();
	var exist_val = $("#fixed_price").val();
	var hotel_price = $("#hotel_price"+hotel_id).val();
	var overall_tour_price = $("#overall_tour_price").val();
	var total_nights = $("#total_nights").val();
	var single_room_price = $("#single_val"+hotel_id).val();
	
	
	if (txtval == 0) {
		$("#extra_"+type+hotel_id).css('display','inline-block').text("invalid entry");
		setTimeout(function(){
			$("#display_"+type+hotel_id).html($("#"+type+"_val"+hotel_id).val());
			$("#extra_"+type+hotel_id).fadeOut("slow");
			$("#count_"+type+hotel_id).val(1);
		}, 1000);
    }
	
	
	var single_val = 0; var double_val = 0; var triple_val = 0; var infants_val = 0; var twotosix_val = 0;  var sixtotwelve_val = 0; var twelvetosixteenth_val = 0;
	
	if ($("#price_single"+hotel_id).length > 0) {
        var single_val = $("#price_single"+hotel_id).val();
    }
	if ($("#price_double"+hotel_id).length > 0) {
        var double_val = $("#price_double"+hotel_id).val();
    }
	if ($("#price_triple"+hotel_id).length > 0) {
        var triple_val = $("#price_triple"+hotel_id).val();
    }
	if ($("#price_infants"+hotel_id).length > 0) {
        var infants_val = $("#price_infants"+hotel_id).val();
    }
	if ($("#price_twotosix"+hotel_id).length > 0) {
        var twotosix_val = $("#price_twotosix"+hotel_id).val();
    }
	if ($("#price_sixtotwelve"+hotel_id).length > 0) {
        var sixtotwelve_val = $("#price_sixtotwelve"+hotel_id).val();
    }
	if ($("#price_twelvetosixteenth"+hotel_id).length > 0) {
        var twelvetosixteenth_val = $("#price_twelvetosixteenth"+hotel_id).val();
    }
	
	
	var extra_night_price = parseInt(single_val)+parseInt(double_val)+parseInt(triple_val)+parseInt(infants_val)+parseInt(twotosix_val)+parseInt(sixtotwelve_val)+parseInt(twelvetosixteenth_val);
	

	if (transportation_cost != 0) {
        var tour_ds = (overall_tour_price/total_nights)/single_room_price;
		var tour_price = (hotel_price*tour_ds*total_nights)+transportation_cost;
		
    }else{
		var tour_ds = (overall_tour_price/total_nights)/single_room_price;
		var tour_price = hotel_price*tour_ds*total_nights;
	}
	
	
	var infants_price = parseInt($("#infants_val"+hotel_id).val())*parseInt($("#count_infants"+hotel_id).val());
	var twotosix_price = parseInt($("#twotosix_val"+hotel_id).val())*parseInt($("#count_twotosix"+hotel_id).val());
	var sixtotwelve_price = parseInt($("#sixtotwelve_val"+hotel_id).val())*parseInt($("#count_sixtotwelve"+hotel_id).val());
	var twelvetosixteenth_price = parseInt($("#twelvetosixteenth_val"+hotel_id).val())*parseInt($("#count_twelvetosixteenth"+hotel_id).val());
	if (extra_total != 0) {
		var current_total = parseInt(tour_price)+(parseInt(infants_price) + parseInt(twotosix_price) + parseInt(sixtotwelve_price) + parseInt(twelvetosixteenth_price))*($("#total_nights").val())+parseInt(extra_total);
		$("#extra_total"+hotel_id).val(extra_total);
	}else {
		var ex = 0;
	$("#extra_total"+hotel_id).val(ex);
		var current_total = parseInt(tour_price)+parseInt(parseInt(infants_price) + parseInt(twotosix_price) + parseInt(sixtotwelve_price) + parseInt(twelvetosixteenth_price))*($("#total_nights").val())+parseInt(ex)+parseInt(extra_night_price);
	}
	
	$("#changeval").html(current_total);
	$("#price_tour").val($("#changeval").html());
	
 }
	
	
	
	function nextTab(elem) {
		$(elem).next().find('a[data-toggle="tab"]').click();
	}
	function prevTab(elem) {
		$(elem).prev().find('a[data-toggle="tab"]').click();
	}
		
	function change_price(count,type,hotel_id){
		if (count) {
			var change_price = count*$("#hid_"+type+hotel_id).val();
			$("#pop_price").html(change_price);
			var exist_val = $("#changeval").html();
			$("#price_"+type+hotel_id).val(change_price);
			/*var extra_total = parseInt($("#price_"+type+hotel_id).val())+parseInt($("#price_infants").val())+parseInt($("#price_twotosix").val())+parseInt($("#price_sixtotwelve").val())+parseInt($("#price_twelvetosixteenth").val());
			final_price(1,type,extra_total);*/
			//console.log(get_extra_night_price());
			var extra_total = total_room_cost(hotel_id)+get_extra_night_price();
			$("#changeval").html(extra_total);
			$("#price_tour").val($("#changeval").html());
		}
	}
	
	
	
	function total_price(price){
		var exist_val = $("#fixed_price").val();
		$("#changeval").html(parseInt(exist_val)+parseInt(price));
		$("#price_tour").val($("#changeval").html());
		
	}
	function get_tour(from_id){
		var base_url = $("head").data("base-url");
		if (from_id) {
            $.ajax({
				data: {from_id: from_id},
				method:'post',
				url: base_url + "tour/check_tour",
				success: function(data){    
					if (data) {
                        //var jsndata = $.parseJSON(data);console.log(jsndata);
						//var make_arr = jsndata.split(',');
						$("#arr_city").val(data);
                    }
				}
			});
        }
	}
	
	function initialize(lat,lon,hotel_name) {
		var base_url = $("head").data("base-url");						
		var latitude =lat;
		var longitude = lon;
		var latlng = new google.maps.LatLng(latitude,longitude);
		var myOptions = {
		  zoom: 12,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("hotel_map"),
			myOptions);
		var pinImage = new google.maps.MarkerImage(base_url+"assets/images/hotel_icon.png");
		var marker=new google.maps.Marker({
						position:latlng,
						draggable :false,
						icon: pinImage,
						animation: google.maps.Animation.DROP
					  });
		
		var contentString = "<div>"+hotel_name+"</div>";
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});

		marker.addListener('mouseover', function() {
			infowindow.open(map, marker);
		});


		marker.setMap(map);

		google.maps.event.addListener(marker, 'dragend', function(){
			var point = marker.getPosition();
			map.panTo(point);
			document.getElementById('latitude').value=point.lat();
			document.getElementById('longitude').value=point.lng();
		});


   	}
	
	function hasValue(elem) {
		return $(elem).filter(function() { return $(this).val(); }).length > 0;
	}
	
	function ajaxsearch() {
		var loading_img ="<p class='text-center'><img src='"+ base_url +"assets/images/loader/loading.gif' alt'Loading'><br><h3 class='text-center' ></h3>";
		var min_value = $("body").find("#min_range").val();
		var max_value = $("body").find("#max_range").val();
		$("html, body").animate({ scrollTop: 100 }, "slow");
		$('#products').html(loading_img);
		$("#compact-pagination").css('display','none');
		
		setTimeout(function(){
			var postData=$( "#modify_package" ).serialize() + '&' +  $( "#fliter" ).serialize();
			$.ajax({
				method: "get",
				url: base_url+'tour/result',
				data: postData
			})
			.done(function( msg ) {
				//$('#products').html(msg);
				$('#products').html(msg);
				$('#compact-pagination').show();
				$(".total-count").html($('#all_count').val());
				
				
			});
		}, 1000);
	
	}
	
	
	
	
	function change_slider(min,max){
		$( "#slider-range" ).slider({
			range: true,
			min: parseInt(min),
			max:  parseInt(max),
			values: [ min, max ],
			slide: function( event, ui ) {
				$( "#amount" ).val(ui.values[ 0 ] + "IRR" + "-" + ui.values[ 1 ] + "IRR" );
			},
			change: function( event, ui ) {
				$("#range").val(ui.values[0] + '-'+ ui.values[1]);
				ajaxsearch();
			}
		});
	  
		$("#amount").val( $("#slider-range" ).slider("values", 0 )  + "IRR" + "-" + $("#slider-range" ).slider("values", 1 ) + "IRR");
		$("#range").val($("#min_range").val() + '-'+ $("#max_range").val());
		var std_min_range = $("#min_range").val()/$("#std_currency").val();
		var std_max_range = $("#max_range").val()/$("#std_currency").val();
		
		$("#std_range").val(std_min_range + '-'+ std_max_range);
	}
	
	function ScaleSlider() {
		var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
		if (parentWidth)
			jssor_slider1.$ScaleWidth(Math.min(parentWidth, 800));
		else
			window.setTimeout(ScaleSlider, 30);
	}
	
	function total_room_cost(hotel_id){
			var single_count = $("#count_single"+hotel_id).val();
			if (single_count == '') single_count = 0;
			var single_price = single_count*parseInt($("#display_single"+hotel_id).html());
			
			var double_count = $("#count_double"+hotel_id).val();
			if (double_count == '') double_count = 0;
			var double_price = double_count*parseInt($("#display_double"+hotel_id).html());
			
			var triple_count = $("#count_triple"+hotel_id).val();
			if (triple_count == '') triple_count = 0;
			var triple_price = parseInt(triple_count)*parseInt($("#display_triple"+hotel_id).html());
			
			var infants_count = $("#count_infants"+hotel_id).val();
			if (infants_count == '') infants_count = 0;
			var infants_price = infants_count*parseInt($("#display_infants"+hotel_id).html());
			
			var twotosix_count = $("#count_twotosix"+hotel_id).val();
			if (twotosix_count == '') twotosix_count = 0;
			var twotosix_price = twotosix_count*parseInt($("#display_twotosix"+hotel_id).html());
			
			var sixtotwelve_count = $("#count_sixtotwelve"+hotel_id).val();
			if (sixtotwelve_count == '') sixtotwelve_count = 0;
			var sixtotwelve_price = sixtotwelve_count*parseInt($("#display_sixtotwelve"+hotel_id).html());
			
			var twelvetosixteenth_count = $("#count_twelvetosixteenth"+hotel_id).val();
			if (twelvetosixteenth_count == '') twelvetosixteenth_count = 0;
			var twelvetosixteenth_price = twelvetosixteenth_count*parseInt($("#display_twelvetosixteenth"+hotel_id).html());
			
			var c_total_price = parseInt(single_price)+parseInt(double_price)+parseInt(triple_price)+parseInt(infants_price)+parseInt(twotosix_price)+parseInt(sixtotwelve_price)+parseInt(twelvetosixteenth_price);
			return c_total_price;
		
	}
	
	function get_hotel_id(){
		var selected_hotel = [];
		$("input:checkbox[class=hotel_variables]").each(function() {
			if($(this).is(':checked')){
				selected_hotel.push($(this).attr('value'));
			}
			
		});
		return selected_hotel[0];
	}
	
	function get_extra_night_price(){
		var hotel_id = $("#id_hotel").val();
		var single = $("#price_single"+hotel_id).val();
		if (isNaN(single)) {
           single = 0;
        }
		var doubles = $("#price_double"+hotel_id).val()*parseInt($("#count_double"+hotel_id).val());
		
		if (isNaN(doubles)) {
		   doubles = 0;
        }
		var triple = $("#price_triple"+hotel_id).val();
		if (isNaN(triple)) {
           triple = 0;
        }
		var infants = $("#price_infants"+hotel_id).val();
		if (isNaN(infants)){
           infants = 0;
        }
		var twotosix = $("#price_twotosix"+hotel_id).val();
		if (isNaN(twotosix)) {
           twotosix = 0;
        }
		var sixtotwelve = $("#price_sixtotwelve"+hotel_id).val();
		if (isNaN(sixtotwelve)) {
           sixtotwelve = 0;
        }
		var twelvetosixteenth = $("#price_twelvetosixteenth"+hotel_id).val();
		if (isNaN(twelvetosixteenth)) {
           twelvetosixteenth = 0;
        }
		
		var total = parseInt(single)+parseInt(doubles)+parseInt(triple)+parseInt(infants)+parseInt(twotosix)+parseInt(sixtotwelve)+parseInt(twelvetosixteenth);
		return total;
	}
	
	function total_people(){
		var hotel_id = $("#id_hotel").val();
		
		var single_count = $("#count_single"+hotel_id).val();
			if (single_count == '') single_count = 0;
		var double_count = $("#count_double"+hotel_id).val();
			if (double_count == '') double_count = 0;
		var triple_count = $("#count_triple"+hotel_id).val();
			if (triple_count == '') triple_count = 0;
		
		return parseInt(single_count)+parseInt(double_count)+parseInt(triple_count);
	}
	
	
	
	
	

});