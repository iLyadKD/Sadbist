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
			custom_fn.load_validate("add_sms_template");


		// Add new staff
		$(document).on("submit", "form.add_sms_template", function(submit_event)
		{
			console.log($(this).find("input[name][type='text']").length);
			console.log($(this).find("input[name][type='text'].valid").length);

			if($('form.add_sms_template').valid())
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
							cur_form[0].reset();
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
		
	
		// Change b2c user account status
		$(document).on("click", "input[type='checkbox'].sms_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			url = base_url+current_controller+"/sms_status"+default_ext;
			$.ajax(
			{
				url: url,
				type: "POST",
				dataType: "JSON",
				data: {'id' : href, 'status' : status},
				beforeSend: function()
				{
					//show popup
					custom_fn.show_loading("Template is being updated....", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(7000);
					if(response.status === "true")
						cur_var.prop("checked", checked);
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
					custom_fn.set_auto_close(5000);
					return false;
				}
			});
		});

		// Change b2c user account status
		$(document).on("click", ".delete_sms_template", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var id = $(this).data("id");
			url = base_url+current_controller+"/sms_delete"+default_ext;
			$.ajax(
			{
				url: url,
				type: "POST",
				dataType: "JSON",
				data: {'sms_id' : id},
				beforeSend: function()
				{
					//show popup
					custom_fn.show_loading("Template is being deleted....", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					setTimeout(function(){location.reload();},500);

				},
				error: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
					custom_fn.set_auto_close(5000);
					return false;
				}
			});
		});

		

	});
});
