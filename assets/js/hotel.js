var base_url=$("head").data("base-url");var file_ext=$("head").data("file-ext");var hash=$("head").data("hash");var language=$("head").data("language");require(["angular_app"],function(angular_app)
{var processing=null;var filter_types=[];filter_types["price"]="total_cost";filter_types["star"]="star";filter_types["user_rating"]="star";filter_types["hotel_name"]="hotel_name";angular_app.controller("hotel_controller",["$scope","$http","$timeout","$filter",function($scope,$http,$timeout,$filter)
{var hac=$scope;hac.min_price=hac.cmin_price=0;hac.max_price=hac.cmax_price=0;hac.total_count=hac.all_total_count=-2;hac.hotels=[];hac.pageno=1;hac.page_limit=10;hac.currency="IRR";hac.currency_val=1;hac.stars=[];hac.order_by="price";hac.load=true;hac.filter_modified=true;hac.sort_by="asc";hac.sort_me=function(sort)
{if(hac.order_by===sort)
hac.sort_by=hac.sort_by==="asc"?"desc":"asc";else
hac.sort_by="asc";hac.order_by=sort;hac.filter_data();}
hac.get_data=function(pageno)
{hac.hotels=[];hac.pageno=pageno;if(hac.load===true)
hac.total_count=-2;else
hac.total_count=-1;if(hac.load===true)
filter_form="";var filter_form=$("form#filter_form").serialize();var oby=filter_types[hac.order_by];$http.get(base_url+"hotel/search/"+hash+"?page="+hac.pageno+"&limit="+hac.page_limit+"&fresh="+hac.load+"&sort_by="+hac.sort_by+"&order_by="+oby+"&"+filter_form).success(function(response)
{if(response.expired==="true")
window.location.href=base_url+"hotel/session_expired/"+hash
if(hac.load===true)
{hac.load=false;hac.total_count=hac.all_total_count=response.total_count;hac.min_price=hac.cmin_price=response.min_price;hac.max_price=hac.cmax_price=response.max_price;hac.stars=response.stars;hac.locations=response.locations;hac.currency=response.currency;hac.currency_val=response.currency_val;if($("div.price-slider").length>0)
$("div.price-slider").slider({range:true,min:parseInt(hac.min_price),max:parseInt(hac.max_price),values:[hac.cmin_price,hac.cmax_price],slide:function(event,ui)
{hac.cmin_price=ui.values[0];hac.cmax_price=ui.values[1];hac.$apply();hac.filter_data();}});}
angular.forEach(response.hotels,function(value,key)
{value.extra=angular.fromJson(value.extra);this.push(value);},hac.hotels);hac.total_count=response.total_count;});};hac.filter_data=function()
{hac.total_count=-1;if(processing===null&&hac.all_total_count>0)
processing=$timeout(function()
{hac.hotels=[];hac.total_count=-1;hac.pageno=1;var filter_form=$("form#filter_form").serialize();var oby=filter_types[hac.order_by];$http.get(base_url+"hotel/search/"+hash+"?page="+hac.pageno+"&limit="+hac.page_limit+"&fresh="+hac.load+"&sort_by="+hac.sort_by+"&order_by="+oby+"&"+filter_form).success(function(response)
{if(response.expired==="true")
window.location.href=base_url+"hotel/session_expired/"+hash
angular.forEach(response.hotels,function(value,key)
{this.push(value);},hac.hotels);hac.total_count=response.total_count;processing=null;});},2000,true).then(function(){});};hac.get_data(1);}]);var ele=$(document).find(".hotel_controller");var is_init=ele.injector();if(is_init===undefined)
angular.bootstrap(ele,["app_custom"]);});$(document).ready(function()
{var touch=false;var hs="form#hotel_search";if($(hs).length>0)
{$(hs).validate({ignore:".ignore"});if($(hs+" .hotel_city").length>0)
$(hs+" .hotel_city").autocomplete({source:base_url+"hotel/autocomplete",minLength:3,response:function(event,ui)
{if(ui.content.length===0)
{$(this).addClass("error");$(this).val("");}
else
$(this).removeClass("error");},select:function(event,ui)
{$("[name='hotel_city']").val(ui.item.id);}});$(document).on("change",".total_rooms",function()
{if($(this).data("last_selected")===undefined)
$(this).data("last_selected",1);var last_selected=$(this).data("last_selected");var room_cc=$(this).val()>0?$(this).val():1;if(last_selected>room_cc)
$(document).find(".rooms_details").children(":gt("+(room_cc-1)+")").detach();else
{for(var i=last_selected;i<room_cc;i++)
{var extra_room=$(".rooms_details .room_details:eq(0)").clone();extra_room.find(".children_age").html("");extra_room.find("select[name='adult[]']").attr("id","adult_"+i).val("1");extra_room.find("select[name='children[]']").attr("id","children_"+i).val("0").data("last_selected",0);extra_room.find(".person_details>div:eq(0)").html("<label class=\"search_label\">&nbsp;</label><span>Room "+((i*1)+1)+"</span>");$(".rooms_details").append(extra_room);}}
$(this).data("last_selected",room_cc);});$(document).on("change",".room_children",function()
{if($(this).data("last_selected")===undefined)
$(this).data("last_selected",0);var last_selected=$(this).data("last_selected");var child_cc=$(this).val()>0?$(this).val():0;var child_idx=$(this).attr("id").replace("children_","");if(last_selected>child_cc)
$(this).closest(".room_details").find(".children_age").children(":gt("+child_cc+"), :eq("+child_cc+")").detach();else
{var child_age_opt="";for(var j=1;j<13;j++)
child_age_opt+="<option value='"+j+"'>"+j+"</option>\n";for(var i=((last_selected*1)+1);i<=child_cc;i++)
{var child_age_div="<div class=\"col-md-4 col-sm-4 col-xs-12 nopadding_right inlabel no_left ma_top nopadding_right margintop mnopadding_left\">\n";child_age_div+="<label class=\"search_label\">child age "+i+"</label>\n";child_age_div+="<div class=\"clearfix\"></div>\n";child_age_div+="<div class=\"inputtext\">\n";child_age_div+="<div class=\"roomblock\">\n";child_age_div+="<div class=\"roomdetails\">\n";child_age_div+="<div class=\"dropdown\">\n";child_age_div+="<select name=\"child_age["+child_idx+"][]\" class=\"dropdown-select\">\n";child_age_div+="<option value=\"0\">select</option>\n";child_age_div+=child_age_opt;child_age_div+="</select>\n</div>\n</div>\n</div>\n</div>\n</div>";$(this).closest(".room_details").find(".children_age").append(child_age_div);}}
$(this).data("last_selected",child_cc);});}
$(document).on("click",".modify_hotel_search",function()
{if($(".modify_hotel_search_details").length>0)
$(".modify_hotel_search_details").slideToggle(500);});});