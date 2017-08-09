require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");


		if($("form.update_profile_form").length > 0)
			custom_fn.load_validate("update_profile_form");
		if($("form.update_email_setting_form").length > 0)
			custom_fn.load_validate("update_email_setting_form");

		if($(".dashboard_menus").length > 0)
		{
			if(typeof(Storage) !== undefined)
			{
				$list_name = $(".dashboard_menus").data("href");
				// Code for localStorage/sessionStorage.
				$top_headers = localStorage.getItem($list_name);
				if($top_headers === null || $top_headers === "")
				{
					$orders = [];
					$lists = $(".dashboard_menus > div");
					$lists.each(function()
					{
						$orders.push($(this).attr("id"));
					});
					localStorage.setItem($list_name, $orders.join(","));
				}
				else
				{
					$orders = $top_headers.split(",");
					$html_content = "";
					for (var i = 0; i < $orders.length; i++)
					{
						var temp_id = $orders[i];
						if(temp.length !== undefined)
						{
							$html_content += $("#"+temp_id).wrap("<p/>").parent().html();
							$("#"+temp_id).unwrap();
						}
					}
					$(".dashboard_menus").html($html_content);
				}
				
				$(".dashboard_menus").removeClass("hide");

				// Sort
				$(".dashboard_menus").sortable({
					revert: true,
					stop: function()
					{
						localStorage.clear();
						$orders = [];
						$lists = $(".dashboard_menus > div");
						$lists.each(function()
						{
							$orders.push($(this).attr("id"));
						});
						localStorage.setItem($list_name, $orders.join(","));
					}
				});
			}
			else
				$(".dashboard_menus").removeClass("hide");
		}


		// Settings and Profile updates

		// Update profile
		$(document).on("submit", "form.update_profile_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("admin", cur_form.data("href"));
				url = base_url+"home/profile"+default_ext;
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
					custom_fn.show_loading("Profile is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					
					$(".wait_loader").hide()
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(7000);
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
					custom_fn.set_auto_close(5000);
				}
			});
			}
		});

		// Update Email Settings
		$(document).on("submit", "form.update_email_setting_form", function(e)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var url = base_url+"home/settings"+default_ext;
				var form_data = new FormData($(this)[0]);
				form_data.append("id", $(this).data("href"));
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
						custom_fn.show_loading("Email settings is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
					},
					error: function(response)
					{
						custom_fn.hide_loading();
						custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
						custom_fn.set_auto_close(5000);
					}
				});
			}
		});

		// End of Settings and Profile updates
	});
});
