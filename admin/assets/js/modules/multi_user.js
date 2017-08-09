require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.user_sendmail_form").length > 0)
			custom_fn.load_validate("user_sendmail_form");

		//Common functionalities on multiple users

		//popup window with active promocodes
		$(document).on("click", "a.user_promocode", function()
		{
			var current_user = $(this).hasClass("b2b") ? "b2b" : ($(this).hasClass("b2c") ? "b2c" : "subscriber");
			var email = $(this).data("email");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("send_promocode_template");
			$(".send_promocode_template").find("form").addClass("user_promocode_form").data("user", $(this).data("href")).data("user_type", current_user);
			if(current_user === "b2b")
				$(".b2c_promocode_template").find("h4.modal-title").text("B2B Management - Send Promocode");
			else if(current_user === "b2c")
				$(".b2c_promocode_template").find("h4.modal-title").text("B2C Management - Send Promocode");
			else
				$(".send_promocode_template").find("h4.modal-title").text("Subscriber Management - Send Promocode");
			$(".send_promocode_template").find("button[type='submit']").html("Send");
			var url = base_url+"ajax/active_promocodes_form_html"+default_ext;
			$.ajax(
			{
				url: url,
				method: "GET",
				dataType: "JSON",
				processData: false,
				contentType:false,
				beforeSend: function()
				{
					//show popup
					custom_fn.show_loading("Promocodes are being loaded..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						$(".send_promocode_template").find("div.modal-body").css({"width" : "100%", "overflow" : "auto", "height" : "200px"}).html(response.msg);
						$(".send_promocode_template").toggle();
						custom_fn.load_validate("user_promocode_form");
					}
					else
					{
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(5000);
					}
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
					custom_fn.set_auto_close(5000);
				}
			});
		});


		// send promocode form
		$(document).on("submit", "form.user_promocode_form", function()
		{
			if($(this).find("[type='radio'].valid").length  > 0)
			{
				var url = base_url+current_controller+"/promocode"+default_ext;
				var cur_form = $(this);
				var user_type = cur_form.data("user_type");
				var form_data = new FormData(cur_form[0]);
				form_data.append("user", cur_form.data("user"));
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
						// remove popup window
						$("body").find(".model_template").detach();
						//show popup
						if(user_type === "b2b")
							custom_fn.show_loading("Promocode is being sent to b2b user..", "it will take a couple of seconds");
						else if(user_type === "b2c")
							custom_fn.show_loading("Promocode is being sent to b2c user..", "it will take a couple of seconds");
						else
							custom_fn.show_loading("Promocode is being sent to subscriber..", "it will take a couple of seconds");
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

		//send mail form
		$(document).on("submit", "form.user_sendmail_form", function()
		{
			var user_type = $(this).hasClass("b2b") ? "b2b" : ($(this).hasClass("b2c") ? "b2c" : "subscriber");
			$(this).find("textarea.ckeditor").each(function()
			{
				var id = $(this).attr("id");
				var pc = CKEDITOR.instances[id].getData();
				if(pc === "")
				{
					$("<label class='error'>Please enter details</label>").insertBefore($(this));
					$(this).addClass("error").removeClass("valid");
				}
				else
				{
					if($(this).prev().hasClass("error"))
						$(this).prev().detach();
					$(this).removeClass("error").addClass("valid");
				}
			});
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				var url = base_url+current_controller+"/mail"+default_ext;
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("user", cur_form.data("href"));
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
						// remove popup window
						$("body").find(".model_template").detach();
						//show popup
						if(user_type === "b2b")
							custom_fn.show_loading("Mail is being sent to b2b user..", "it will take a couple of seconds");
						else if(user_type === "b2c")
							custom_fn.show_loading("Mail is being sent to b2c user..", "it will take a couple of seconds");
						else
							custom_fn.show_loading("Mail is being sent to subscriber..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							for(instance in CKEDITOR.instances)
							{
								CKEDITOR.instances[instance].updateElement();
								CKEDITOR.instances[instance].setData('');
							}
							cur_form.find("input[name]").focus();
						}
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
		//End of Multi User Module Functionalities

	});
});
