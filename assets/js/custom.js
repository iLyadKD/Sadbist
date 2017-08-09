require(["bootstrap","lazy","additional_methods","dt_extn","popupoverlay","select2","dt_tabletools","backslider","jq_bxslider","sticky","owl_carousel"],function(){$(document).ready(function()
{jQuery.validator.addMethod("greater",function(value,element,params)
{val=value.split("-");val=val.reverse();val=val.join("-");chk_val=$(params).val().split("-");chk_val=chk_val.reverse();chk_val=chk_val.join("-");if(!/Invalid|NaN/.test(new Date(val)))
return new Date(val)>=new Date(chk_val);return isNaN(val)&&isNaN(chk_val)||(Number(val)>=Number(chk_val));},"Invalid date.");$('.from_date.adult').datepicker();jQuery.validator.addMethod("dateformat",function(value,element){return value.match(/^\d\d?\-\d\d?\-\d\d\d\d$/);},"Please enter a date in the format dd/mm/yyyy.");jQuery.validator.addMethod("flexi_contact",function(value,element){return value.match(/^(\+[0-9]{2}[ \-]?[0-9]{2}[0-9]?[0-9]?[ \-]?[0-9]{2}[0-9]?[0-9]?[ \-]?[0-9]{2,9})$/);},"+dd dd[dd] dd[dd] dd[ddddddd]");jQuery.validator.addMethod("notEqualTo",function(value,element,param){return this.optional(element)||value!=$(param).val();},"Please specify a different to city");$("[hyperlink]").each(function()
{var href=$(this).attr("hyperlink");$(this).data("href",href);$(this).removeAttr("hyperlink");});if($(".flight-list-v2").length>0)
$(".spcl").attr("disabled","disabled");if($(".flight_deals").length>0)
$(".flight_deals").tinycarousel({interval:true,axis:"y"}).data("plugin_tinycarousel").start();$(".b2b_login_model").on("hidden.bs.modal",function(e)
{$("#booknow").val(0);$("form.user_login").find("input[type=text], textarea").val("");$("form.user_login .login_error").empty();});var touch=false;var base_url=$("head").data("base-url");var controller=$("head").data("controller");var method=$("head").data("method");var logged_in=$("head").data("id");var file_ext=$("head").data("file-ext");var language=$("head").data("language");var model_template='<div class="modal fade in model_template" tabindex="-1">\n<div class="modal-dialog">\n<div class="modal-content">\n<div class="modal-header">\n<button aria-hidden="true" class="close close-all" type="button">×</button>\n<h4 class="modal-title"></h4>\n</div>\n<form class="form" style="margin-bottom: 0;" method="post" action="javascript:void(0);">\n<div class="modal-body">\n</div>\n<div class="modal-footer">\n<button class="btn btn-default close-all" data-dismiss="modal" type="button">Close</button>\n<button class="btn btn-primary" type="submit">Send</button>\n</div>\n</form>\n</div>\n</div>\n</div>';if($("form.b2b_login_form").length>0)
var b2b_lf_cnt=$("form.b2b_login_form").html();$("body").prepend("<div class='wait_loader'></div>");$(document).on("click","[data-toggle='dropdown'], .bx-controls-direction a",function(event)
{$("html").trigger("scroll");});$(window).scroll(function()
{var wScroll=$(this).scrollTop();if(wScroll>20)
{$("#main-nav").addClass("active");$("#slide_out_menu").addClass("scrolled");}
else
{$("#main-nav").removeClass("active");$("#slide_out_menu").removeClass("scrolled");};if(wScroll>100)
{$(".fixe").addClass("active");$("#slide_out_menu").addClass("scrolled");}
else
{$(".fixe").removeClass("active");$("#slide_out_menu").removeClass("scrolled");};if(wScroll>120)
{$(".addsort").addClass("active");$("#slide_out_menu").addClass("scrolled");}
else
{$(".addsort").removeClass("active");$("#slide_out_menu").removeClass("scrolled");};});$(document).on("click",".default_lang",function()
{var lang=$(this).data("lang");$.ajax({url:base_url+"ajax/change_language/"+lang,type:"post",success:function(response)
{if(response==="true")
location.reload();}});});$(document).on("click",".default_curr",function()
{var currency=$(this).data("currency");$.ajax({url:base_url+"ajax/change_currency/"+currency,type:"post",success:function(response)
{if(response==="true")
location.reload();}});});if(window.Modernizr)
touch=Modernizr.touch;if(!touch)
{$("body").on("mouseenter",".has-tooltip",function()
{var el;el=$(this);if(el.data("tooltip")===undefined)
{el.tooltip({placement:el.data("placement")||"top",container:"body"});}
return el.tooltip("show");});$("body").on("mouseleave",".has-tooltip",function()
{return $(this).tooltip("hide");});}
$(document).on("click",".close-all",function()
{$(this).closest(".model_template").detach();});$(document).on("click","span.forgot-password a",function()
{$(this).closest("#myModal").find("button.close").click();});$(document).on("click",".oneway_return_pax",function(){$(".oneway_return_pax").popModal({html:$(".oneway_return_pax_details"),placement:"bottomLeft",showCloseBut:true,onDocumentClickClose:true,onDocumentClickClosePrevent:"",overflowContent:false,inline:true,beforeLoadingContent:"Please, waiting...",onOkBut:function(){},onCancelBut:function(){},onLoad:function(){},onClose:function(){$(".oneway_return_pax").html(parseInt($("[name='adult']").val())+parseInt($("[name='child']").val())+parseInt($("[name='infant']").val()))}});});$(document).on("click",".multi_city_pax",function(){$(".multi_city_pax").popModal({html:$(".multi_city_pax_details"),placement:"bottomLeft",showCloseBut:true,onDocumentClickClose:true,onDocumentClickClosePrevent:"",overflowContent:false,inline:true,beforeLoadingContent:"Please, waiting...",onOkBut:function(){},onCancelBut:function(){},onLoad:function(){},onClose:function(){$(".multi_city_pax").html(parseInt($("[name='madult']").val())+parseInt($("[name='mchild']").val())+parseInt($("[name='minfant']").val()));}});});$(document).find("[data-toggle='modal']").attr("href","#");$(document).on("click",".b2b_login_close_btn, .b2b_login_model",function(event)
{var target=$(event.target);if(target.hasClass("b2b_login_close_btn")||target.hasClass("b2b_login_model"))
{$("form.b2b_secure_login_form").removeClass("b2b_secure_login_form").addClass("b2b_login_form");$("form.b2b_login_form").html(b2b_lf_cnt);}
event.stopPropagation();});$(document).on("change","input[type='file'][accept='image/*']",function()
{if($(".preview_img").length>0)
{if(this.files&&this.files[0])
{var reader=new FileReader();reader.onload=function(e)
{$(".preview_img").attr("src",e.target.result);}
reader.readAsDataURL(this.files[0]);}}});if($("img.lazyload").length>0)
{$("img.lazyload").lazyload({event:"scroll click",skip_invisible:true,failure_limit:20,effect:"fadeIn"});}
$(document).on("click","a.read_full_latest_news",function()
{var full_content=$(this).data("full-content");});if($(".till_current_date.adult").length>0)
{}
calendar=$.calendars.instance(language);global_date_format=(calendar.name==="Persian")?'yy/mm/dd':'dd-mm-yy';if($(".dob_prof").length>0)
{var dt=new Date();var dob_prof_max=dt.getFullYear();dt.setFullYear(dob_prof_max);$(".dob_prof").datepicker({dateFormat:global_date_format,maxDate:0,changeMonth:true,changeYear:true,showButtonPanel:true,yearRange:"1920:"+dob_prof_max,defaultDate:dt});}
if($(".from_current_date").length>0)
{var dt=new Date();var dob_prof_max=dt.getFullYear();dt.setFullYear(dob_prof_max);$(".from_current_date").datepicker({dateFormat:global_date_format,maxDate:0,changeMonth:true,changeYear:true,showButtonPanel:true,yearRange:"1920:"+dob_prof_max,defaultDate:dt});}
$("select.select2").select2();if($("select.set_country").length>0)
{var url=base_url+"ajax/get_countries";$.ajax({url:url,method:"GET",dataType:"JSON",success:function(response)
{$("select.set_country").each(function(k,e){var sco=$(e).data("href")!==undefined?$(e).data("href"):"";$(e).html(response.result);if(sco!=="")
{$(e).val(sco);$(e).data("href","");$(e).change();}
$(e).select2("destroy");$(e).select2();});},error:function(response)
{$("select.set_country").html("");$("select.set_country").select2("destroy");$("select.set_country").select2();}});}
if($("form.prebook_form").length>0)
var prebook_form=$("form.prebook_form").validate({ignore:'input[type="button"],input[type="submit"]'});if($(".nav-tabs").length>0)
$(".nav-tabs > li a[title]").tooltip();$('a[data-toggle="tab"]').on("show.bs.tab",function(e)
{var target_tab=$(e.target);if(target_tab.parent().hasClass("disabled"))
return false;});if($("form.b2b_login_form").length>0)
$("form.b2b_login_form").validate();if($("form.user_edit").length>0){$("form.user_edit").validate();$(document).on("change","select.set_country",function()
{var cur_parent_id=$(this).closest(".user_info_div").prop('id');var cur_parent="#"+cur_parent_id;if($(cur_parent).find(".national_id").length>0&&$(this).val()==="IR")
{if($(cur_parent).find(".not_required").length>0)
{}}
else
{if($(cur_parent).find(".not_required").length>0)
{}}});}
if($("form#tour_search").length>0){$("form#tour_search").validate();}
if($("form#modify_package").length>0){$("form#modify_package").validate();$("#fromcity,#tocity").keyup(function(){$("#ready_flag").val(1);});}
if($(".hotel_list_view").length>0){$('input[name="hotels"]:checked').each(function(){$("#id_hotel").val(this.value);});$('input[name="trans"]:checked').each(function(){$("#trans_param").val(this.value);});}
$(document).on("submit","form.b2b_login_form",function()
{if($(this).find("input").length===$(this).find("input.valid").length)
{var cur_form=$(this);var username=$(this).find("input[name='email_id']").val();var form_data=new FormData(cur_form[0]);var url="b2b/login"+file_ext;$.ajax({url:url,method:"POST",dataType:"JSON",data:form_data,processData:false,contentType:false,beforeSend:function()
{cur_form.find(".login_error").html("");$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>user credentails being verified..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");$(".wait_loader").show();},success:function(response)
{if(response.status==="true")
window.location.href=base_url+"b2b"+file_ext;else if(response.status==="verify")
{$(".wait_loader").html("");$(".wait_loader").hide();cur_form.children("div:eq(0)").slideUp("slow",function()
{cur_form.html('<div class="col-md-12" style="display:none;"><div class="login_error"></div><div class="mn-login form-group"><label class="control-label">Verification Code</label><div class="controls"><input autocomplete="off" class="form-control valid" name="verification_code" type="text" placeholder="Verification Code" data-rule-required="true" data-msg-required="Please enter verification code received by email."></div></div><div class="mn-login"><button class="btn-1" type="submit">Verify</button></div></div>');cur_form.children("div:eq(0)").slideDown("slow");cur_form.removeClass("b2b_login_form").addClass("b2b_secure_login_form").data("user",response.id);$("form.b2b_secure_login_form").validate();});}
else
{$(".wait_loader").html("");$(".wait_loader").hide();cur_form[0].reset();cur_form.find(".login_error").html(response.msg);cur_form.find("input[name='email_id']").val(username).select().focus();}},error:function(response)
{$(".wait_loader").html("");$(".wait_loader").hide();cur_form[0].reset();cur_form.closest(".model").find(".close").trigger("click");$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>B2B Login</h4>Sorry, Failed to login. Try agin.</div>");setTimeout(function(){$("body").find(".notification .alert-close").click();},3000);}});}});$(document).on("submit","form.b2b_secure_login_form",function()
{if($(this).find("input").length===$(this).find("input.valid").length)
{var cur_form=$(this);var form_data=new FormData(cur_form[0]);form_data.append("agent",cur_form.data("user"));var url="b2b/two_step_login"+file_ext;$.ajax({url:url,method:"POST",dataType:"JSON",data:form_data,processData:false,contentType:false,beforeSend:function()
{cur_form.find(".login_error").html("");$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>verification code is being verified..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");$(".wait_loader").show();},success:function(response)
{if(response.status==="true")
window.location.href=base_url+"b2b"+file_ext;else
{$(".wait_loader").html("");$(".wait_loader").hide();cur_form[0].reset();cur_form.find(".login_error").html(response.msg);cur_form.find("input[name='verification_code']").focus();}},error:function(response)
{$(".wait_loader").html("");$(".wait_loader").hide();cur_form[0].reset();cur_form.closest(".model").find(".close").trigger("click");$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>B2B Login</h4>Sorry, Failed to login. Try agin.</div>");setTimeout(function(){$("body").find(".notification .alert-close").click();},3000);}});}});if($(".best-hotel-deals-slider").length>0)
{$(".best-hotel-deals-slider").bxSlider({slideWidth:223,minSlides:1,maxSlides:5,slideMargin:10});}
if($(".best-tour-deals-slider").length>0)
{$(".best-tour-deals-slider").bxSlider({slideWidth:223,minSlides:1,maxSlides:5,slideMargin:10});}
if($(".slider_images_available p").length>0)
{var slider=[];$(".slider_images_available").children().each(function()
{slider.push({image:$(this).html()});});$.supersized({slide_interval:2000,transition:1,transition_speed:1000,slide_links:"blank",slides:slider});}
$(document).on("click","ul.home_deals >li,.bookbut",function()
{var category=$(this).data("category");if(category==="tour")
{var tour_json=$(this).data("tour");window.location.href=tour_json.tour_link;}
else if(category==="flight")
{var flight_json=$(this).data("flight");var flight_form=$('<form action="'+base_url+'flight/lists'+'" method="post"></form>');$("<input type='hidden' name='flight_type' value='"+flight_json.trip_type+"'>").appendTo(flight_form);$("<input type='hidden' name='flight_origin' value='"+flight_json.o_airport+"'>").appendTo(flight_form);$("<input type='hidden' name='flight_destination' value='"+flight_json.d_airport+"'>").appendTo(flight_form);$("<input type='hidden' name='flight_departure' value='"+flight_json.dept_date+"'>").appendTo(flight_form);$("<input type='hidden' name='class' value='Y'>").appendTo(flight_form);$("<input type='hidden' name='adult' value='1'>").appendTo(flight_form);$("<input type='hidden' name='child' value='0'>").appendTo(flight_form);$("<input type='hidden' name='infant' value='0'>").appendTo(flight_form);if(flight_json.return_date!==undefined&&flight_json.return_date!=="")
$("<input type='hidden' name='flight_arrival' value='"+flight_json.return_date+"'>").appendTo(flight_form);if(flight_json.airline!==undefined&&flight_json.airline!=="")
$("<input type='hidden' name='preference_airport' value='"+flight_json.airline+"'>").appendTo(flight_form);flight_form.appendTo('body').submit();}
else if(category==="hotel")
{var hotel_json=$(this).data("hotel");console.log("need to process and send request to API");}
else if(category==="others")
{var other_json=$(this).data("other");window.location.href=other_json.link;}});$(document).on("click","div.bhoechie-tab-menu>div.list-group>a",function(e)
{e.preventDefault();var class_name=$(this).attr("class").split(" ")[0].replace("_ic","_li");$("."+class_name).addClass("active").siblings().removeClass("active");$(this).siblings("a.active").removeClass("active");$(this).addClass("active");var index=$(this).index();$("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");generate_dp("form#"+$(".bhoechie-tab-content.active>form").attr("id"),language);});if($(".bhoechie-tab-content.active>form").length>0)
generate_dp("form#"+$(".bhoechie-tab-content.active>form").attr("id"),language);else if($(".modify_search_details form").length>0)
generate_dp("form#"+$(".modify_search_details form").attr("id"),language);else if($(".prebook_div form").length>0)
generate_dp("form#"+$(".prebook_div form").attr("id"),language);else if($(".flight_prebook_div form").length>0)
generate_dp("form#"+$(".flight_prebook_div form").attr("id"),language);else if($(".b2c_profile_div form").length>0)
generate_dp("form#"+$(".b2c_profile_div form").attr("id"),language);$(document).on("click",".toggle_menu",function()
{var class_name=$(this).attr("class").split(" ")[0].replace("_li","");window.history.pushState(class_name,class_name,base_url+class_name);class_name+="_ic";$("."+class_name).trigger("click");});if($("#cssmenu").length>0)
{$("#cssmenu").prepend("<div id='menu-button'></div>");$("#cssmenu #menu-button").on("click",function()
{var menu=$(this).next("ul");if(menu.hasClass("open"))
menu.removeClass("open");else
menu.addClass("open");});}
if($(".credit_details").length>0)
$(".credit_details").dataTable({"dom":"lfrtip","bProcessing":false,"bServerSide":true,"sServerMethod":"GET","sAjaxSource":base_url+"b2b/get_credit_dtls","iDisplayLength":10,"aLengthMenu":[[10,25,50,75,-1],[10,25,50,75,"All"]],"aaSorting":[[0,"asc"]],"aoColumnDefs":[{"bSortable":false,"aTargets":[0,7,8,9,10]},{"bSearchable":false,"aTargets":[0,7,8,9,10]},{"bVisible":false,"aTargets":[10]}],"fnRowCallback":function(nRow,aData,iDisplayIndex,iDisplayIndexFull)
{if(aData[11]==="1")
{$("td:eq(9) .view_tranx_details",nRow).data("tranx",aData[10]);$("td:eq(9) .make_settlement",nRow).data("tranx",aData[10]);}
else
$("td:eq(9)").html("");}}).fnSetFilteringDelay(2000);$(document).on("click",".view_tranx_details",function()
{var tranx=$(this).data("tranx");var url=base_url+"b2b/get_credit_tranx_stats";$.ajax({url:url,method:"GET",dataType:"JSON",data:{"transaction":tranx},beforeSend:function()
{$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");$(".wait_loader").show();},success:function(response)
{$(".wait_loader").html("");$(".wait_loader").hide();$("body").prepend(model_template);$(".model_template").addClass("credit-tranx-stats-template");$(".credit-tranx-stats-template").find("h4.modal-title").text("Credit - Transaction status");$(".credit-tranx-stats-template").find("button[type='submit']").detach();$(".credit-tranx-stats-template").find("button[type='button']:eq(1)").html("okay");$(".credit-tranx-stats-template").find("div.modal-body").css({"max-height":"400px","overflow":"auto"}).html(response.contents);$(".credit-tranx-stats-template").toggle();},error:function(response)
{$(".wait_loader").html("");$(".wait_loader").hide();setTimeout(function(){$("body").find(".notification .alert-close").click();},3000);}});});$(document).on('click','.companion',function(event,data){$("#b2c_companion").modal('show');if($("#b2c_companion").length>0){if(data==undefined){count_companion();}}
$("#list_companion").modal('hide');if(data!=undefined){var id=data.id;var action=data.edit;var url=base_url+"b2c/companion_by_id";$.ajax({method:'post',url:url,data:{id:id},success:function(data){var obj=JSON.parse(data);console.log(obj);form_setup(1,1);$("#salutation").val(obj.salutation);$("#fname").val(obj.fname);$("#lname").val(obj.lname);$("#dob").val(obj.dob);$("#passport_no").val(obj.passport_no);$("#passport_exp").val(obj.passport_exp);$("#companion_id").val(obj.id);$("#edit_comp_national_id").val(obj.national_id);$("#edit_comp_nationality").val(obj.nationality);$("#edit_comp_name_fa").val(obj.name_fa);$("#edit_comp_nationality").change();$("#b2c_companion").find(".btn-default").show().val('Update');$("#b2c_companion").find(".modal-title").text('Update companion');}})}else{form_setup(1,0);$("#b2c_companion").find(".modal-title").text('Add companion');}
if($("#b2c_companion").length>0){$(".add_companion").validate();}});$(document).on("submit","form.add_companion",function(e)
{if($(this).find("input[lname]").length===$(this).find("input[lname].valid").length)
{var form_data=new FormData($(this)[0]);var url=base_url+"b2c/add_companion";$.ajax({url:url,method:"POST",dataType:"JSON",data:form_data,processData:false,contentType:false,beforeSend:function()
{var loading_img="<p class='text-center pre_loader'><img src='"+base_url+"assets/images/loader/loading.gif' alt'Loading'><br><h3 class='text-center' ></h3>";$(".loaderdiv").html(loading_img);$(".btndiv").hide();},success:function(response)
{if(response==1){setTimeout(function(){$(".loaderdiv").html('');$(".btndiv").show();$('.add_companion').find('input:text').val('');$('.add_companion').find('select').val(0);var num=parseInt($("#count_companion").val())+1;$("#count_companion").val(num);form_setup(2,0);},2000);}
if(response==2){setTimeout(function(){$(".loaderdiv").html('');$(".btndiv").show();},2000);}},error:function(response)
{}});}});$(document).on('click','.modal_change, .compList',function(){var id=$(this).data('id');if(id=='list'){$("#b2c_companion").modal('hide');$("#list_companion").modal('show');var loading_img="<p class='text-center pre_loader'><img src='"+base_url+"assets/images/loader/loading.gif' alt'Loading'><br><h3 class='text-center' ></h3>";$(".list_body").html(loading_img);setTimeout(function(){if($(".list_body").length>0){get_companion_list();}},2000);}else if(id=="add"){form_setup(1,0);$("#b2c_companion").modal('show');$("#list_companion").modal('hide');}});$(document).on('click','.action_companion',function(){var action=$(this).data('action');var id=$(this).data('id');if(action=='edit'){$('.companion').trigger('click',[{id:id}]);}else if(action=='delete'){var item='#row_'+id;var url=base_url+"b2c/delete_companion";$.ajax({method:'post',url:url,data:{id:id},success:function(msg){if(msg==1){$(".com_table").find(item).remove();var num=parseInt($("#count_companion").val())-1;$("#count_companion").val(num);}}})}});$("#b2c_companion").on('hidden.bs.modal',function(){$('.add_companion').find('input:text').val('');$('.add_companion').find('select').val(0);});if($('.add_new_companion').length>0){$('.add_new_companion').click(function(){var newCount=$(".user_info_div_comp").length;var parentDiv=$(this).closest(".user_info_div_comp");var newDiv=$('#companion_new_dummy').clone();newID='companion_new_'+(newCount+1);newDiv.prop('id',newID);nat_HTML=' <select style="width:100% !important;" data-rule-required="true" data-msg-required="Required *" name="comp_nationality[]" class="set_country_comp"  data-href="IR" ></select>';newDiv.find('.nationality > .reginput').html(nat_HTML);var url=base_url+"ajax/get_countries";$.ajax({url:url,method:"GET",dataType:"JSON",success:function(response)
{$("select.set_country_comp").each(function(k,e){var sco=$(e).data("href")!==undefined?$(e).data("href"):"";$(e).html(response.result);if(sco!=="")
{$(e).val(sco);$(e).data("href","");$(e).change();}});},error:function(response)
{$("select.set_country_comp").html("");}});newDiv.find('.mefullwd').html('');$('.companions-container').append(newDiv);newDiv.find('select').select2();newDiv.css('display','block');newDiv.find('.from_current_date').prop('id','');newDiv.find('.from_current_date').removeClass('hasDatepicker');newDiv.find('.from_current_date').datepicker({minDate:1,changeMonth:true,showButtonPanel:true,changeYear:true});newDiv.find(".dob_prof").prop('id','');newDiv.find(".dob_prof").removeClass('hasDatepicker');newDiv.find(".dob_prof").datepicker({dateFormat:dateFormat,maxDate:0,changeMonth:true,changeYear:true,showButtonPanel:true,yearRange:"1920:"+dob_prof_max,defaultDate:dt});});}
$('#save_prof').click(function(){$('#profile_form_id').validate().cancelSubmit=true;$('#profile_form_id').submit();});$('.remove_new_companion').click(function(){var newCount=$(".user_info_div_comp").length;if(newCount>2){$('.companions-container').find('.user_info_div_comp').last().remove();}});$('.delete_companion').click(function(){var id=$(this).data('id');var sel=$(this);var url=base_url+"b2c/delete_companion";$.ajax({method:'post',url:url,data:{id:id},success:function(msg){if(msg=='1'){console.log(sel.closest(".user_info_div"));sel.closest(".user_info_div").remove()}}});});});function get_companion_list(){var url=base_url+"b2c/companion_list";var page=1;$.ajax({method:'post',url:url,data:{page:page},success:function(data){$(".list_body").html(data);}})}
function count_companion(){var url=base_url+"b2c/count_companion";var page=1;$.ajax({method:'post',url:url,data:{page:page},success:function(data){$("#count_companion").val(data);}})}
function form_setup(arg1,arg2){var str1='Sorry ! You cannot add more, already exceeded maximum number of companions';var str2='Done ! Now you cannot add more, exceeded maximum number of companions';var count_comp=$("#count_companion").val();if(count_comp>=10&&arg2!=1){$("#b2c_companion").find('#first_div :input').hide().attr("disabled",true);if(arg1==1){$("#b2c_companion").find('#second_div').show().html(str1);}else if(arg1==2){$("#b2c_companion").find('#second_div').show().html(str2);}
$("#b2c_companion").find(".btn-default").hide().val('Save').attr("disabled",true);}else{$("#b2c_companion").find('#first_div :input').show().attr("disabled",false);$("#b2c_companion").find('#second_div').hide();$("#b2c_companion").find(".btn-default").show().val('Save').attr("disabled",false);}}});