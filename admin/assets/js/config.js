

var header = document.getElementsByTagName("head")[0];
var asset_url = header.getAttribute("asset-url");
var controller_name = header.getAttribute("controller");
require.config(
{	
	urlArgs: "bust="+new Date().getTime(),//remove this in production
	baseUrl : asset_url+"js",
	paths : {
			"jquery": "jquery/jquery.min",
			"bootstrap": "bootstrap/bootstrap",
			"bootstrap_file_style": "bootstrap/bootstrap-filestyle.min",
			"bootstrap_tags": "bootstrap/bootstrap-tagsinput",
			"bootstrap_dttm": "bootstrap/bootstrap-datetimepicker.min",
			"bootstrap_switch": "bootstrap/bootstrap-switch",
			"mobile_custom": "jquery/jquery.mobile.custom.min",
			"lazy": "jquery.lazyload",
			"pattern_lock": "pattern-lock",
			"jquery_ui": "jquery/jquery-ui.min",
			"validation": "validate/jquery.validate.min",
			"additional_methods": "validate/additional-methods",
			"datatables": "datatables/jquery.dataTables.min",
			"dt_tabletools": "datatables/dataTables.tableTools.min",
			"dt_extn": "datatables/dataTables.extension",
			"popupoverlay": "jquery.popupoverlay",
			"select2": "select2/select2",
			"ckeditor": "ckeditor/ckeditor",
			"theme": "theme",
			"custom_defination": "custom_define",
			"custom": "custom",
			"custom_select": "custom/select",
			"custom_files": "custom/files",
			"custom_tags": "custom/tags",
			"custom_switch": "custom/switch",
			"custom_datepicker": "custom/datepicker",
			"login": "modules/login",
			"home": "modules/home",
			"admins": "modules/admins",
			"privileges": "modules/privileges",
			"b2c": "modules/b2c",
			"b2b": "modules/b2b",
			"deposit": "modules/deposit",
			"credit": "modules/credit",
			"multi_user": "modules/multi_user",
			"markup": "modules/markup",
			"promocode": "modules/promocode",
			"charges": "modules/charges",
			"api": "modules/api",
			"currency": "modules/currency",
			"subscriber": "modules/subscriber",
			"location": "modules/location",
			"support": "modules/support",
			"email": "modules/email",
			"language": "modules/language",
			"cms": "modules/cms",
			"homepage": "modules/homepage",
			"sitemap": "modules/sitemap",
			"logs": "modules/logs",
			"package": "modules/package",
			"hotel": "modules/hotel",
			"package_type": "modules/package_type",
			"call_center": "modules/call_center",
			"sms": "modules/sms",
			"discount": "modules/discount"
	},
	shim : {"jquery" :
			{
				exports : "JQuery"
			},
			"jquery_ui" :
			{
				deps : ["jquery"],
				exports : "jquery_ui"
			},
			"bootstrap" :
			{
				deps : ["jquery_ui"],
				exports : "bootstrap"
			},
			"custom_datepicker" :
			{
				deps : ["bootstrap"],
				exports : "custom_datepicker"
			},
			"bootstrap_file_style" :
			{
				deps : ["bootstrap"],
				exports : "bootstrap_file_style"
			},
			"bootstrap_switch" :
			{
				deps : ["bootstrap"],
				exports : "bootstrap_switch"
			},
			"bootstrap_dttm" :
			{
				deps : ["bootstrap"],
				exports : "bootstrap_dttm"
			},
			"bootstrap_tags" :
			{
				deps : ["bootstrap"],
				exports : "bootstrap_tags"
			},
			"lazy" :
			{
				deps : ["jquery"],
				exports : "lazy"
			},
			"pattern_lock" :
			{
				deps : ["jquery"],
				exports : "pattern_lock"
			},
			"validation" :
			{
				deps : ["jquery"],
				exports : "validation"
			},
			"additional_methods" :
			{
				deps : ["validation"],
				exports : "additional_methods"
			},
			"datatables" :
			{
				deps : ["jquery"],
				exports : "datatables"
			},
			"dt_extn" :
			{
				deps : ["datatables"],
				exports : "dt_extn"
			},
			"popupoverlay" :
			{
				deps : ["bootstrap"],
				exports : "popupoverlay"
			},
			"select2" :
			{
				deps : ["jquery"],
				exports : "select2"
			},
			"ckeditor" :
			{
				deps : ["jquery"],
				exports : "ckeditor"
			},
			"dt_tabletools" :
			{
				deps : ["datatables"],
				exports : "dt_tabletools"
			},
			"theme" :
			{
				deps : ["jquery"],
				exports : "theme"
			},
			"custom_defination" :
			{
				deps : ["additional_methods"],
				exports : "custom_defination"
			},
			"custom" : 
			{
				deps : ["bootstrap", "lazy", "theme", "custom_defination"],
				exports : "custom"
			},
			"custom_select" :
			{
				deps : ["select2", "custom"],
				exports : "custom_select"
			},
			"custom_tags" :
			{
				deps : ["bootstrap_tags", "custom"],
				exports : "custom_tags"
			},
			"custom_files" :
			{
				deps : ["bootstrap_file_style", "custom"],
				exports : "custom_files"
			},
			"custom_datepicker" :
			{
				deps : ["custom"],
				exports : "custom_datepicker"
			},
			"custom_switch" :
			{
				deps : ["bootstrap_switch"],
				exports : "custom_switch"
			},
			"login" :
			{
				deps : ["custom", "pattern_lock"],
				exports : "login"
			},
			"home" :
			{
				deps : ["custom", "custom_select"],
				exports : "home"
			},
			"admins" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "admins"
			},
			"privileges" :
			{
				deps : ["custom", "dt_extn", "custom_switch"],
				exports : "privileges"
			},
			"multi_user" :
			{
				deps : ["custom", "custom_select", "ckeditor", "dt_extn", "custom_switch", "custom_files"],
				exports : "multi_user"
			},
			"b2c" :
			{
				deps : ["multi_user","custom"],
				exports : "b2c"
			},
			"b2b" :
			{
				deps : ["multi_user"],
				exports : "b2b"
			},
			"deposit" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_datepicker"],
				exports : "deposit"
			},
			"credit" :
			{
				deps : ["custom", "dt_extn", "custom_switch"],
				exports : "credit"
			},
			"markup" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "markup"
			},
			"promocode" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_datepicker", "custom_tags"],
				exports : "promocode"
			},
			"charges" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "charges"
			},
			"api" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "api"
			},
			"currency" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "currency"
			},
			"subscriber" :
			{
				deps : ["multi_user"],
				exports : "subscriber"
			},
			"location" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "location"
			},
			"support" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select", "custom_files"],
				exports : "support"
			},
			"email" :
			{
				deps : ["custom", "ckeditor", "dt_extn", "custom_switch"],
				exports : "email"
			},
			"language" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "language"
			},
			"cms" :
			{
				deps : ["custom", "ckeditor", "dt_extn", "custom_switch", "custom_select", "bootstrap_file_style"],
				exports : "cms"
			},
			"homepage" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "homepage"
			},
			"sitemap" :
			{
				deps : ["custom", "dt_extn", "custom_switch", "custom_select"],
				exports : "sitemap"
			},
			"logs" :
			{
				deps : ["custom", "dt_extn", "custom_switch"],
				exports : "logs"
			},
			"package" :
			{
				deps : ["multi_user","bootstrap_tags","select2","bootstrap_dttm","bootstrap_switch"],
				exports : "package"
			},
			"hotel" :
			{
				deps : ["multi_user","select2","bootstrap_tags","jquery_ui"],
				exports : "hotel"
			},
			"package_type" :
			{
				deps : ["custom", "multi_user","bootstrap_switch"],
				exports : "package_type"
			},
			"call_center" :
			{
				deps : ["multi_user","custom","bootstrap_dttm"],
				exports : "call_center"
			},
			"sms" :
			{
				deps : ["multi_user","custom"],
				exports : "sms"
			},
			"discount" :
			{
				deps : ["multi_user","custom"],
				exports : "discount"
			}
	}
});

require([controller_name], function(){});
if(controller_name === "cms" || controller_name === "hotel")
	require(
	{
		waitSeconds : 60,
		paths :
		{
			async : asset_url+"js/rjd/async" //alias to plugin
		}
	});
