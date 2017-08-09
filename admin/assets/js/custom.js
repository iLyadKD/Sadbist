require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		
		$(document).on("change", "input[type='file']", function()
	{
		
		var id = $(this).attr('id'); 
		if($(".preview_img").length > 0 && id != 'imageupload')
			custom_fn.preview_img(this);
	});
		
		
		
		//remove attributes on head tag
		$(document).find("head").each(function()
		{
			var attributess = [];
			$.each(this.attributes, function(i, attrs)
			{
				var name = attrs.name;
				$("head").data(name, attrs.value);
				attributess.push(name);
			});
			$(attributess).each(function()
			{
				$("head").removeAttr(attributess.pop());
			});
		});


		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");

		// Sidemenu sort variables
		var current_sort_index = 0;
		var current_sort_value = 2;
		var current_sort_length = 1;
		var current_sort_main = 2;

		// Global Functions
		$("body").prepend("<div class='wait_loader'></div>");

		$(document).on("click", ".model_template .close_popup", function()
		{
			$("body").find(".model_template").detach();
		});

		// Remove any id stored by PHP from attribute list
		$("[hyperlink]").each(function()
		{
			var href = $(this).attr("hyperlink");
			$(this).data("href", href);
			$(this).removeAttr("hyperlink");
		});

		// Remove any id stored by PHP from attribute list
		$("[data-link]").each(function()
		{
			var link = $(this).attr("data-link");
			$(this).data("link", link);
			$(this).removeAttr("data-link");
		});

		// Remove any togglehref with href
		$("[togglehref]").each(function()
		{
			var href = $(this).attr("togglehref");
			$(this).attr("href", href);
			$(this).removeAttr("togglehref");	
		});

		// Remove any order-by tag and add data
		$("[order-by]").each(function()
		{
			var order_by = $(this).attr("order-by");
			var main_order = $(this).attr("main-order");
			$(this).data("order-by", order_by).data("main-order", main_order);
			$(this).removeAttr("order-by").removeAttr("main-order");	
		});

		$.validator.addMethod("prince", (function(value, element)
		{
			return value === "prince";
		}), "Please enter \"prince\"!");
		$.validator.addMethod("landline", function(value, element)
		{
			return this.optional(element) || /^[0-9\-]+$/.test(value);
		}, "Please enter a valid landline number.");
		
		$.validator.methods.equal = function(value, element, param) {
			return value === param;
		};


		$(document).find("textarea.fa_ckeditor").each(function()
		{
			var id = $(this).attr("id");
			if(CKEDITOR.instances[id])
				CKEDITOR.instances[id].destroy(true);
			CKEDITOR.replace(id,
			{
				contentsLangDirection: "rtl"
			});
		});
		
if($(".till_current_date.adult").length > 0)
	{
		var dt = new Date();
		var adult_age = dt.getFullYear() - 12;
		dt.setFullYear(adult_age);
		$(".till_current_date.adult").datepicker(
		{
			maxDate: "-12Y",
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			yearRange: "1920:"+adult_age,
			defaultDate: dt
		});
		dt = new Date();
		var child_age_min = dt.getFullYear() - 2;
		var child_age_max = dt.getFullYear() - 12;
		dt.setFullYear(child_age_min);
		
	}
		
	jQuery.validator.addMethod("flexi_contact",function(value, element) {
		return value.match(/^(\+[0-9]{2}[ \-]?[0-9]{2}[0-9]?[0-9]?[ \-]?[0-9]{2}[0-9]?[0-9]?[ \-]?[0-9]{2,9})$/);
	},"+dd dd[dd] dd[dd] dd[ddddddd]");


		$("img.lazyload").lazyload({
			event : "scroll click",
			skip_invisible : true,
			failure_limit : 20,
			effect : "fadeIn"
		});

		$(".navigation_main_menu li.active").parents("ul").addClass("in");
		$(".navigation_main_menu li.active").parents("li").addClass("active");

		$(document).on("click", "a[href^='http']", function(e)
		{
			var action = $(this).attr("href");
			var target = $(this).attr("target");
			e.preventDefault();
			if(target !== "_blank")
			{
				if($(document).find("section.body-content .box-content form").length > 0)
				{
					if($(document).find("section.body-content .box-content form").data("is-dirty") === true, $(document).find("section.body-content .box-content form").data("is-updated") === false)
					{
						$("body").prepend(custom_fn.model_template);
						$(".model_template").addClass("confirm_redirect");
						$(".confirm_redirect").find("h4.modal-title").text("Quit Operation!!");
						$(".confirm_redirect").find("button[type='submit']");
						$(".confirm_redirect").find("form").addClass("confirm_redirect_form").data("action", action);
						$(".confirm_redirect").find("div.modal-body").html("<ul><li>Your unsaved operations will be lost</li></ul><br>Are you sure you want to continue?");
						$(".confirm_redirect").find("button[type='submit']").html("Continue");
						$(".confirm_redirect").toggle();
					}
					else
						window.location.replace(action);
				}
				else
					window.location.replace(action);
			}
		});

		$(document).on("change", "section.body-content .box-content form :input", function(e)
		{
			$(document).find("section.body-content .box-content form").data("is-dirty", true);
			$(document).find("section.body-content .box-content form").data("is-updated", false);
		});


		$(document).on("submit", "form.confirm_redirect_form", function(e)
		{
			e.preventDefault();
			if($(this).data("action") !== undefined)
				window.location.replace($(this).data("action"));
		})

		// Update My Password

		// Change current password popup
		$(document).on("click", "a.change_my_pwd", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("change_my_pwd_template");
			$(".change_my_pwd_template").find("h4.modal-title").text("My Profile - Change Password");
			var formdata = "<div class='form-group'>";
			formdata += "<label class='control-label col-sm-5' for='my_password'>Enter Current Password</label>";
			formdata += "<div class='col-sm-7 controls'>";
			formdata += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='my_password' name='cur_pass' placeholder='Current Password' type='password'>";
			formdata += "</div>";
			formdata += "</div>";
			formdata += "<div class='form-group'>";
			formdata += "<label class='control-label col-sm-5' for='validation_password'>New Password</label>";
			formdata += "<div class='col-sm-7 controls'>";
			formdata += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'>";
			formdata += "</div>";
			formdata += "</div>";
			formdata += "<div class='form-group'>";
			formdata += "<label class='control-label col-sm-5' for='validation_password_confirmation'>Password confirmation</label>";
			formdata += "<div class='col-sm-7 controls'>";
			formdata += "<input class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'>";
			formdata += "</div>";
			formdata += "</div>";
			$(".change_my_pwd_template").find("div.modal-body").html(formdata);
			$(".change_my_pwd_template").find("button[type='submit']").html("Change");
			$(".change_my_pwd_template").toggle();
			$(".change_my_pwd_template").find("form").addClass("change_my_pwd_form");
			custom_fn.load_validate("change_my_pwd_form");
		});

		
		// confirm update password
		$(document).on("submit", "form.change_my_pwd_form", function(e)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var url = base_url+"home/update_pwd"+default_ext;
				var form_data = new FormData($(this)[0]);
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
						custom_fn.show_loading("Your password is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
						if(response.status === "true")
						setTimeout(function(){window.location.reload();}, 5000);
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
	});
});