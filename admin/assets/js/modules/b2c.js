require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var asset_url = $("head").data("asset-url");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
	
		$(".from_date").datepicker();
		
		if($("form.add_b2c_user_form,form.update_b2c_user_form").length > 0){
			custom_fn.load_validate("add_b2c_user_form");
			$(document).on("change", "select.set_country", function()
		{
			var cur_parent = $(this).closest(".add_b2c_user_form");
			if($(cur_parent).find(".national_id").length > 0 && $(this).val() === "IR")
			{
				$(cur_parent).find(".national_id").show();
				$(cur_parent).find(".national_id").find(":input").removeAttr("disabled");
			}
			else
			{
				$(cur_parent).find(".national_id").hide();
				$(cur_parent).find(".national_id").find(":input").prop("disabled", true);
			}
		});
			
		
		if($(".set_country").val() === "IR"){
			
			var cur_parent = $(".set_country").closest(".add_b2c_user_form");
			$(cur_parent).find(".national_id").show();
			$(cur_parent).find(".national_id").find(":input").removeAttr("disabled");
		}else{
			$(cur_parent).find(".national_id").hide();
			$(cur_parent).find(".national_id").find(":input").prop("disabled", true).val("");
		}	
			
			
			
		}
		if($("form.update_b2c_user_form").length > 0){
			custom_fn.load_validate("update_b2c_user_form");

			$(document).on("change", "select.set_country", function()
		{
			var cur_parent = $(this).closest(".update_b2c_user_form");
			if($(cur_parent).find(".national_id").length > 0 && $(this).val() === "IR")
			{
				$(cur_parent).find(".national_id").show();
				$(cur_parent).find(".national_id").find(":input").removeAttr("disabled");
			}
			else
			{
				$(cur_parent).find(".national_id").hide();
				$(cur_parent).find(".national_id").find(":input").prop("disabled", true).val("");
			}
		});
			
		
		if($(".set_country").length > 0 && $("#code_country").val() === "IR"){
			var cur_parent = $(".set_country").closest(".update_b2c_user_form");
			$(cur_parent).find(".national_id").show();
			$(cur_parent).find(".national_id").find(":input").removeAttr("disabled");
		}else{
			$(cur_parent).find(".national_id").hide();
			$(cur_parent).find(".national_id").find(":input").prop("disabled", true).val("");
		}	


		}


		// B2C Module Functionalities
		if($("table.manage_b2c_users").length > 0)
			$("table.manage_b2c_users").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/users_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [5, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_b2c = JSON.parse(aData[0]);
					var b2c_name = jd_m_b2c.fname+" "+jd_m_b2c.lname;
					$("td:eq(0)", nRow).html(jd_m_b2c.user_id);
					$("td:eq(1)", nRow).html(jd_m_b2c.email);
					$("td:eq(2)", nRow).html(b2c_name);
					$("td:eq(3)", nRow).html(jd_m_b2c.contact);
					$("td:eq(4)", nRow).html(jd_m_b2c.registered);
					$("td:eq(5)", nRow).html(jd_m_b2c.status_html);
					$("td:eq(6)", nRow).html(jd_m_b2c.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_b2c.id).data("name", b2c_name);
				}
			}).fnSetFilteringDelay(2000);


		// Add new b2c user
		$(document).on("submit", "form.add_b2c_user_form", function(submit_event)
		{
			
			if(($(this).find("input[name][type='text']").length + $(this).find("input[name][type='password']").length) === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='password']").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
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
						custom_fn.show_loading("B2C user account is being created..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							cur_form.find("img").attr("src", cur_form.find("input[type='file']").data("href"));
							// cur_form.find("img").attr("src", asset_url+"images/default.png");
							$("select").html("").trigger("change");
						}
						else if(response.status === "exist")
						{
							cur_form.find("input[name='emailid']").val("").focus();
							cur_form.find("input[type='password']").val("");
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

		// Change b2c user account status
		$(document).on("click", "input[type='checkbox'].b2c_user_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("b2c", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/b2c_user_status"+default_ext;
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
					if(status === "0")
						custom_fn.show_loading("B2C user account is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("B2C user account is being activated..", "it will take a couple of seconds");
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

		// update b2c user
		$(document).on("submit", "form.update_b2c_user_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("current_image_path", cur_form.find("input[type='file']").data("href"));
				form_data.append("user", cur_form.data("href"));
				url = base_url+current_controller+"/edit_profile"+default_ext;
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
						custom_fn.show_loading("B2C user account is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true" && response.image_path !== undefined)
							cur_form.find("input[type='file']").data("href", response.image_path);
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

		//popup window to confirm delete b2c user 
		$(document).on("click", "a.delete_b2c_user", function()
		{
			var b2c_user_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_b2c_user_template");
			$(".delete_b2c_user_template").find("h4.modal-title").text("B2C Management - Delete User");
			$(".delete_b2c_user_template").find("form").addClass("delete_b2c_user_form").data("user", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of B2C user '"+b2c_user_name+"' will be completely lost if you continue.</li>";
			form_data += "<li>You can deactivate '"+b2c_user_name+"' account if you want to keep details.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to completely delete '"+b2c_user_name+"' account?";
			$(".delete_b2c_user_template").find("div.modal-body").html(form_data);
			$(".delete_b2c_user_template").find("button[type='submit']").html("Continue");
			$(".delete_b2c_user_template").toggle();
		});

		// confirmed to delete b2c user
		$(document).on("submit", "form.delete_b2c_user_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var user = $(this).data("user");
			var form_data = new FormData();
			form_data.append("user", user);
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
					custom_fn.show_loading("B2c User account is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_b2c_user").filter(function(i, el)
										{
											return $(this).data("href") === user;
										}).closest("tr");
						var cur_table = cur_row.closest("table");
						cur_row.detach();
						if(cur_table.find("tbody tr").length === 0)
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='9' class='dataTables_empty'>No data available in table</td></tr>");
					}
					custom_fn.hide_loading();
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(5000);
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
					custom_fn.set_auto_close(5000);
				}
			});
		});
		//End of B2C Module Functionalities

	});
});
