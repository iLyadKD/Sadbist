require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_privilege_form").length > 0)
			custom_fn.load_validate("add_privilege_form");
		if($("form.update_privilege_form").length > 0)
			custom_fn.load_validate("update_privilege_form");


		// Privileges Module Functionalities

		//Display all privileges
		if($("table.manage_privileges").length > 0)
			$("table.manage_privileges").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/privilege_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 5, 6, 7, 8, 9, 10]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_privlg = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_privlg.sl_no);
					$("td:eq(1)", nRow).html(jd_m_privlg.controller);
					$("td:eq(2)", nRow).html(jd_m_privlg.icon_html);
					$("td:eq(3)", nRow).html(jd_m_privlg.name);
					$("td:eq(4)", nRow).html(jd_m_privlg.url);
					$("td:eq(5)", nRow).html(jd_m_privlg.menu_type);
					$("td:eq(6)", nRow).html(jd_m_privlg.order_by);
					$("td:eq(7)", nRow).html(jd_m_privlg.sub_menu_order);
					$("td:eq(8)", nRow).html(jd_m_privlg.is_avail_to_admin);
					$("td:eq(9)", nRow).html(jd_m_privlg.status_html);
					$("td:eq(10)", nRow).html(jd_m_privlg.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_privlg.id).data("name", jd_m_privlg.name);
				}
			}).fnSetFilteringDelay(2000);

		// Privilege available to admin or change status of privilege or delete privilege
		$(document).on("click", "input[type='checkbox'].privilege_visible_to_admin, input[type='checkbox'].privilege_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var op_type = "available";
			if(cur_var.hasClass("privilege_visible_to_admin"))
				op_type = "available";
			else if(cur_var.hasClass("privilege_status"))
				op_type = "status";
			var form_data = new FormData();
			form_data.append("type", op_type);
			form_data.append("privilege", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/privilege_status"+default_ext;
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
					custom_fn.show_loading("Privilege is being updated..", "it will take a couple of seconds");
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

		// show preview of icon selected for privileges
		$(document).on("blur", "input[name].prev_privilege_icon", function()
		{
			var class_icon = "icon-"+$(this).val();
			$(this).parent().next().find("i").removeAttr("class").addClass(class_icon);
		});

		//toggle submenu order by depending on type of menu
		$(document).on("click", ".privilege_menu_types", function()
		{
			if($(this).val() === "0")
				$("input[name].submenu_order_by").attr("disabled", "disabled").parent().siblings("label").removeClass("required");
			else
				$("input[name].submenu_order_by").removeAttr("disabled").parent().siblings("label").addClass("required");
		});

		//add new privilege
		$(document).on("submit", "form.add_privilege_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === ($(this).find("input[type='text'].valid").length + $(this).find("input[type='text']:disabled").length))
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
						custom_fn.show_loading("Privilege is being created..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							cur_form.find(".submenu_order_by").attr("disabled", "disabled");
							cur_form.find("input[name].prev_privilege_icon").parent().next().find("i").removeAttr("class").removeAttr("class")
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

		//update privilege (un-finished)
		$(document).on("submit", "form.update_privilege_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === ($(this).find("input[type='text'].valid").length + $(this).find("input[type='text']:disabled").length))
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("privilege", cur_form.data("href"));
				url = base_url+current_controller+"/edit"+default_ext;
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
						custom_fn.show_loading("Privilege is being updated..", "it will take a couple of seconds");
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

		//popup window to confirm delete privilege 
		$(document).on("click", "a.delete_privilege", function()
		{
			var name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_privilege_template");
			$(".delete_privilege_template").find("h4.modal-title").text("Manage Privileges - Delete Privilege");
			$(".delete_privilege_template").find("form").addClass("delete_privilege_form").data("privilege", $(this).data("href"));
			$(".delete_privilege_template").find("div.modal-body").html("<ul><li>If you are deleting main privilege, subprivileges under that privilege will also be deleted.</li><li>Information of '"+name+"' privilege will be completely lost if you continue.</li><li>You cannot access '"+name+"' privilege again.</li></ul><br>Are you sure to delete '"+name+"' privilege?");
			$(".delete_privilege_template").find("button[type='submit']").html("Continue");
			$(".delete_privilege_template").toggle();
		});

		// delete privilege
		$(document).on("submit", "form.delete_privilege_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var privilege = $(this).data("privilege");
			var form_data = new FormData();
			form_data.append("privilege", privilege);
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
					custom_fn.show_loading("Privilege is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
						setTimeout(function(){window.location.reload();}, 5000);
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
		//End of Privilege Module Functionalities

	});
});
