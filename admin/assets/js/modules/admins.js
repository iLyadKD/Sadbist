require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_admin_form").length > 0)
			custom_fn.load_validate("add_admin_form");
		if($("form.update_admin_form").length > 0)
			custom_fn.load_validate("update_admin_form");

		// Admin Management module functionalities

		// Display all admin list
		if($("table.manage_admins").length > 0)
			$("table.manage_admins").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_admin_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5]}
								// 	{"bVisible": false, "aTargets": [2]},
								// 	{ // merge email and company into this column
								// 		"mRender": function(data, type, column) {
								// 			return data +" "+ column[2];
								// 		},
								// 		"aTargets": [ 1 ]
								// }
								],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_adm = JSON.parse(aData[0]);
					var adm_name = jd_m_adm.fname+" "+jd_m_adm.lname;
					$("td:eq(0)", nRow).html(jd_m_adm.sl_no);
					$("td:eq(1)", nRow).html(adm_name);
					$("td:eq(2)", nRow).html(jd_m_adm.user_type);
					$("td:eq(3)", nRow).html(jd_m_adm.email);
					$("td:eq(4)", nRow).html(jd_m_adm.contact);
					$("td:eq(5)", nRow).html(jd_m_adm.status);
					$("td:eq(6)", nRow).html(jd_m_adm.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_adm.id).data("name", adm_name).data("email", jd_m_adm.email);
				}
			}).fnSetFilteringDelay(2000);

		//display required message for privileges
		$(document).on("click", "input[name='privilege[]']", function()
		{
			if($("input[name='privilege[]']:checked").length > 0)
				$(".select_privilege_err").html("");
			else
				$(".select_privilege_err").html("Select at-least one privilege.");
		});

		// Add new Admin
		$(document).on("submit", "form.add_admin_form", function(submit_event)
		{
			if(($(this).find("input[name][type='text']").length + $(this).find("input[name][type='password']").length) === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='password']").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("input[type='checkbox']:checked").length > 0)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/add_admin"+default_ext;
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
						custom_fn.show_loading("Admin account is being created..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
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

		// Activate or De-activate admin account
		$(document).on("click", "input[type='checkbox'].admin_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("admin", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/admin_status"+default_ext;
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
						custom_fn.show_loading("Admin account is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Admin account is being activated..", "it will take a couple of seconds");
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

		// Edit Admin Details
		$(document).on("submit", "form.update_admin_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("input[type='checkbox']:checked").length > 0)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("admin", cur_form.data("href"));
				url = base_url+current_controller+"/edit_admin"+default_ext;
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
					custom_fn.show_loading("Admin account is being updated..", "it will take a couple of seconds");
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

		// Change other admins password popup
		$(document).on("click", "a.change_pwd_admin", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("admin_change_pwd_template");
			$(".admin_change_pwd_template").find("h4.modal-title").text("Admin Management - Change Password");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-5 required' for='my_password'>Enter Your Password</label>";
			form_data += "<div class='col-sm-7 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='my_password' name='adm_pass' placeholder='Your Password' type='password'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-5 required' for='validation_password'>New Password</label>";
			form_data += "<div class='col-sm-7 controls'>";
			form_data += "<input autocomplete='off' tabindex='2' class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-5 required' for='validation_password_confirmation'>Password confirmation</label>";
			form_data += "<div class='col-sm-7 controls'>";
			form_data += "<input autocomplete='off' tabindex='3' class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'>";
			form_data += "</div>";
			form_data += "</div>";
			$(".admin_change_pwd_template").find("div.modal-body").html(form_data);
			$(".admin_change_pwd_template").find("button[type='submit']").html("Change");
			$(".admin_change_pwd_template").toggle();
			$(".admin_change_pwd_template").find("form").addClass("admin_change_pwd_form").data("admin", $(this).data("href"));
			custom_fn.load_validate("admin_change_pwd_form");
		});

		// confirm change password of other admins
		$(document).on("submit", "form.admin_change_pwd_form", function(e)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var url = base_url+current_controller+"/update_pwd"+default_ext;
				var form_data = new FormData($(this)[0]);
				form_data.append("admin", $(this).data("admin"));
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
						custom_fn.show_loading("Admin password is being updated..", "it will take a couple of seconds");
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

		//pop-up window to confirm message to delete admin
		$(document).on("click", "a.delete_admin", function()
		{
			var name = $(this).data("name")+" ["+$(this).data("email")+"]";
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("admin_delete_template");
			$(".admin_delete_template").find("h4.modal-title").text("Admin Management - Delete Admin");
			$(".admin_delete_template").find("button[type='submit']").data("admin", $(this).data("href"));
			$(".admin_delete_template").find("form").addClass("admin_delete_form").data("admin", $(this).data("href"));
			$(".admin_delete_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' will be completely lost if you continue.</li><li>You can deactivate '"+name+"' account if you want to keep details.</li></ul><br>Are you sure to completely delete '"+name+"' account?");
			$(".admin_delete_template").find("button[type='submit']").html("Continue");
			$(".admin_delete_template").toggle();
		});

		// delete admin-confirmed
		$(document).on("submit", "form.admin_delete_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var admin = $(this).data("admin");
			var form_data = new FormData();
			form_data.append("admin", admin);
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
					custom_fn.show_loading("Admin account is being removed..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_admin").filter(function(i, el)
										{
											return $(this).data("href") === admin;
										}).closest("tr");
						var cur_table = cur_row.closest("table");
						var next_row = cur_row.next().length > 0 ? cur_row.next() : "";
						var sl_no = cur_row.find("td:eq(0)").html();
						cur_row.detach();
						while(next_row !== "")
						{
							next_row.find("td:eq(0)").html(sl_no);
							var next_row = next_row.next().length > 0 ? next_row.next() : "";
							sl_no = (sl_no * 1) + 1;
						}
						if(cur_table.find("tbody tr").length === 0)
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='6' class='dataTables_empty'>No data available in table</td></tr>");
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
		// End of Admin management

	});
});