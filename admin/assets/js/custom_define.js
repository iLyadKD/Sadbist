define(["jquery", "additional_methods"], function($)
{

	return {
		model_template : '<div class="modal fade in model_template" tabindex="-1">\n<div class="modal-dialog">\n<div class="modal-content">\n<div class="modal-header">\n<button aria-hidden="true" class="close close_popup" data-dismiss="modal_template" type="button">×</button>\n<h4 class="modal-title"></h4>\n</div>\n<form class="form form-horizontal" method="post" action="javascript:void(0);">\n<div class="modal-body" style="max-height:400px;overflow-y:auto;overflow-x:hidden;">\n</div>\n<div class="modal-footer">\n<button class="btn btn-default close_popup" data-dismiss="modal" type="button">Close</button>\n<button class="btn btn-primary" type="submit">Send</button>\n</div>\n</form>\n</div>\n</div>\n</div>',
		multi_preview_img : function(input, container, multiple)
		{
			if(!multiple)
			{
				if (input.files && input.files[0])
				{
					var reader = new FileReader();
					reader.onload = function (e)
					{
						if($(container).length > 0)
							$(container).attr("src", e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
				}
			}
			else
			{
				$(container).html("");
				var total_files = input.files.length;
				initial = 0;
				for (var i = 0; i < total_files; i++)
				{
					var extn = input.files[i].name.substring(input.files[i].name.lastIndexOf('.') + 1).toLowerCase();
					if(extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg")
					{
						if(typeof(FileReader) !== undefined)
						{
							var reader = new FileReader();
							reader.onload = function (e)
							{
								$(container).append($("<div class='preview_img_container'></div>").data("selected_file", initial++).html($("<span class='fa icon-times-circle icon-lg remove_preview_file' title='remove'></span>").data("input_tag", $(input).attr("id"))).prepend($("<img />",
								{
									"src": e.target.result,
									"class": "thumbimage"
								})));
							}
							reader.readAsDataURL(input.files[i]);
						}
					}
					else
						delete input.files[i];
				}
			}
		},
		preview_img : function(input)
		{
			if (input.files && input.files[0])
			{
				var reader = new FileReader();
				reader.onload = function (e)
				{
					if($(".preview_img").length > 0)
						$(".preview_img").attr("src", e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		},
		load_validate : function(class_name)
		{
			$("."+class_name).validate();
		},
		show_loading : function(title, subtitle)
		{
			$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>"+title+"</h2><p class='text-bigger'>"+subtitle+"</p></div>");
			$(".wait_loader").show();
		},
		hide_loading : function()
		{
			$(".wait_loader").html("");
			$(".wait_loader").hide();
		},
		show_status_msg : function(status, title, msg)
		{
			$("body").find(".notification").html("<div class='alert alert-block alert-"+status+" alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='notify_close close'>×</a><h4 class='alert-heading'>"+title+"</h4>"+msg+"</div>");
		},
		set_auto_close : function(timeout)
		{
			if($("html").data("is-auto-set") !== undefined || $("html").data("is-auto-set") !== null)
				clearTimeout($("html").data("is-auto-set"));
			var auto_set = setTimeout(function(){$("body").find(".notification .notify_close").click(); $("html").data("is-auto-set", null);}, timeout);
			$("html").data("is-auto-set", auto_set);
		},
		trim_char : function(string, char_to_remove)
		{
			while(string.charAt(0) === char_to_remove)
				string = string.substring(1);

			while(string.charAt(string.length - 1) == char_to_remove)
				string = string.substring(0,string.length-1);
			return string;
		},
		marker_icon : function(status)
		{
			return (parseInt(status) === 0) ? "http://maps.google.com/mapfiles/kml/pal3/icon48.png" : "http://maps.google.com/mapfiles/kml/pal3/icon56.png";
		}
	}
});