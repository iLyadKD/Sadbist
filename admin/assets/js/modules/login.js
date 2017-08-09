require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.login_admin").length > 0)
			custom_fn.load_validate("login_admin");
		if($("form.forgot_pass").length > 0)
			custom_fn.load_validate("forgot_pass");

		if($(".login_pattern").length > 0)
		{
			new PatternLock(".login_pattern",
			{
				mapper: function(idx)
				{
					$(".patt-holder").css("background", "#0aa89e");
					return (idx%9);
				},
				onDraw:function(pattern)
				{
					$("[name='login_pattern']").val(pattern);
				}
			});
		}


		// Login Page

		//Forgot Password
		$(document).on("click", ".forgot_password", function(e)
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("forgot_password_template");
			$(".forgot_password_template").find("h4.modal-title").text("Forgot Password");
			var formdata = "<div class='form-group'>";
			formdata += "<label class='control-label col-sm-5 required' for='forgot_email'>Enter your email address</label>";
			formdata += "<div class='col-sm-7 controls'>";
			formdata += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control' data-rule-required='true' id='forgot_email' name='forgot_email' placeholder='Email Address' type='text' data-rule-email='true'>";
			formdata += "</div>";
			formdata += "</div>";
			$(".forgot_password_template").find("div.modal-body").html(formdata);
			$(".forgot_password_template").find("button[type='submit']").html("Send Email");
			$(".forgot_password_template").toggle();
			$(".forgot_password_template").find("form").addClass("send_forgot_pass_mail_form");
			custom_fn.load_validate("send_forgot_pass_mail_form");
		});

		// enter email address (forgot password)
		$(document).on("submit", "form.send_forgot_pass_mail_form", function(e)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var url = base_url+current_controller+"/forgot_password"+default_ext;
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
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
						custom_fn.show_loading("Verification code is being sent to mentioned email..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form.children("div:eq(0)").slideUp("slow", function()
							{
								var formdata = "<div class='form-group'>";
								formdata += "<label class='control-label col-sm-5 required' for='verification_code'>Enter Verification Code</label>";
								formdata += "<div class='col-sm-7 controls'>";
								formdata += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control' data-rule-minlength='6' data-rule-required='true' id='verification_code' name='verify' placeholder='Verification Code' type='text'>";
								formdata += "</div>";
								formdata += "</div>";
								formdata += "<div class='form-group'>";
								formdata += "<label class='control-label col-sm-5 required' for='validation_password'>New Password</label>";
								formdata += "<div class='col-sm-7 controls'>";
								formdata += "<input autocomplete='off' tabindex='2' class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'>";
								formdata += "</div>";
								formdata += "</div>";
								formdata += "<div class='form-group'>";
								formdata += "<label class='control-label col-sm-5 required' for='validation_password_confirmation'>Password confirmation</label>";
								formdata += "<div class='col-sm-7 controls'>";
								formdata += "<input autocomplete='off' tabindex='3' class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'>";
								formdata += "</div>";
								formdata += "</div>";
								cur_form.children("div:eq(0)").html(formdata);
								cur_form.children("div:eq(0)").slideDown("slow");
								cur_form.removeClass("send_forgot_pass_mail_form").data("admin", response.admin).addClass("reset_password_form").data("admin", response.id);
								$("form.reset_password_form").validate();
							});
						}
						else
						{
							var formdata = "<div class='alert alert-block alert-dismissable alert-"+response.msg_status+" forgot_password_msg'>";
							formdata += "<a href='javascript:void(0)' data-dismiss='alert' class='notify_close close'>×</a>";
							formdata += "<h4 class='alert-heading'>"+response.title+"</h4>"+response.msg+"</div>";
							cur_form.find("div:eq(0)").find(".forgot_password_msg").detach();
							cur_form.find("div:eq(0)").append(formdata)
							setTimeout(function(){cur_form.find(".forgot_password_msg .notify_close").click()}, 4000);
						}
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

		// verify code and new password
		$(document).on("submit", "form.reset_password_form", function(e)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var url = base_url+current_controller+"/reset_password"+default_ext;
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("admin", cur_form.data("admin"));
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
						custom_fn.show_loading("verifying and updating password..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form.children("div:eq(0)").slideUp("slow", function()
							{
								var formdata = "<div class='alert alert-block alert-dismissable alert-"+response.msg_status+" forgot_password_msg'>";
								formdata += "<h4 class='alert-heading'>"+response.title+"</h4>"+response.msg+"</div>";
								cur_form.find("div:eq(0)").find(".forgot_password_msg").detach();
								cur_form.find("button[type='submit']").detach();
								cur_form.children("div:eq(0)").html(formdata);
								cur_form.children("div:eq(0)").slideDown("slow");
							});
						}
						else
						{
							var formdata = "<div class='alert alert-block alert-dismissable alert-"+response.msg_status+" forgot_password_msg'>";
							formdata += "<a href='javascript:void(0)' data-dismiss='alert' class='notify_close close'>×</a>";
							formdata += "<h4 class='alert-heading'>"+response.title+"</h4>"+response.msg+"</div>";
							cur_form.find("div:eq(0)").find(".forgot_password_msg").detach();
							cur_form.find("div:eq(0)").append(formdata)
							setTimeout(function(){cur_form.find(".forgot_password_msg .notify_close").click()}, 4000);
						}
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
		// End of Login Module Functionalities

	});
});
