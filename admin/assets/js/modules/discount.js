require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url    	   = $("head").data("base-url");
		var default_ext        = $("head").data("default-ext");
		var asset_url          = $("head").data("asset-url");
		var current_controller = $("head").data("controller");
		var current_method     = $("head").data("method");
	
		
		if($("form.add_sms_template").length > 0)
			custom_fn.load_validate("update_discount");


		// Update Discount
		$(document).on("submit", "form.update_discount", function(submit_event)
		{
			console.log($(this).find("input[name][type='text']").length);
			console.log($(this).find("input[name][type='text'].valid").length);

			if($('form.update_discount').valid())
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/add"+default_ext;
				$.ajax(
				{
					url: url,
					method: "POST",
					dataType: "JSON",
					data: form_data,
					processData: false,
					contentType:false,
					beforeSend: function()
					{
						//show popup
						custom_fn.show_loading("New Template is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{ 
						
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							// cur_form[0].reset();
						}
						
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
					},
					error: function(response)
					{
						custom_fn.hide_loading();
						custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
						custom_fn.set_auto_close(function(){},5000);
					}
				});
			}
		});
		
	
	});
});
