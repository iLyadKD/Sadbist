require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_markup_type_form").length > 0)
			custom_fn.load_validate("add_markup_type_form");
		if($("form.update_markup_type_form").length > 0)
			custom_fn.load_validate("update_markup_type_form");

		if($("form.add_markup_form").length > 0)
			custom_fn.load_validate("add_markup_form");
		if($("form.update_markup_form").length > 0)
			custom_fn.load_validate("update_markup_form");

		// Markup Module Functionalities

		// Display markup types
		if($("table.manage_markup_types").length > 0)
			$("table.manage_markup_types").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/types_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_mrkup_typ = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_mrkup_typ.sl_no);
					$("td:eq(1)", nRow).html(jd_m_mrkup_typ.name);
					$("td:eq(2)", nRow).html(jd_m_mrkup_typ.priority);
					$("td:eq(3)", nRow).html(jd_m_mrkup_typ.category);
					$("td:eq(4)", nRow).html(jd_m_mrkup_typ.actions);
					nRow.className = jd_m_mrkup_typ.category;
					$("td:eq(4) a[href='javascript:void(0);']", nRow).data("name", jd_m_mrkup_typ.name).data("priority", jd_m_mrkup_typ.priority).data("category", jd_m_mrkup_typ.category).data("mu_category", jd_m_mrkup_typ.user_type).data("href", jd_m_mrkup_typ.id);
				}
			}).fnSetFilteringDelay(2000);


		//submit new markup type
		$(document).on("submit", "form.add_markup_type_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/types_add"+default_ext;
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
						custom_fn.show_loading("Markup Type is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							$("select").html("").trigger("change");
						}
						cur_form.find("input:eq(0)").focus();
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

		//submit update markup type
		$(document).on("submit", "form.update_markup_type_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var mu_priority = cur_form.find("[name='markup_priority']");
				var mu_type = cur_form.find("[name='user_type']");
				var form_data = new FormData(cur_form[0]);
				form_data.append("mu_type", $(this).data("href"));
				form_data.append("mu_user_old", mu_type.data("link"));
				form_data.append("mu_priority_old", mu_priority.data("href"));
				url = base_url+current_controller+"/types_edit"+default_ext;
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
						custom_fn.show_loading("Markup Type is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						mu_type.data("link", mu_type.val());
						mu_priority.data("href", mu_priority.val());
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

		//popup window to confirm set default of same type 
		$(document).on("click", "a.set_default_markup_type", function()
		{
			var mu_type_name = $(this).data("name");
			var category = $(this).data("category");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("set_default_markup_type_template");
			$(".set_default_markup_type_template").find("h4.modal-title").text("Markup Management - Set Default Markup Type");
			$(".set_default_markup_type_template").find("form").addClass("set_default_markup_type_form").data("type", $(this).data("href")).data("priority", $(this).data("priority")).data("category", $(this).data("mu_category"));
			var form_data = "<ul>";
			form_data += "<li>'"+mu_type_name+"' markup type will be set default in '"+category+"' category.</li>";
			form_data += "<li>'"+mu_type_name+"' will be given highest priority over other markup types.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to set '"+mu_type_name+"' as default markup type?";
			$(".set_default_markup_type_template").find("div.modal-body").html(form_data);
			$(".set_default_markup_type_template").find("button[type='submit']").html("Continue");
			$(".set_default_markup_type_template").toggle();
		});

		// delete privilege
		$(document).on("submit", "form.set_default_markup_type_form", function()
		{
			var url = base_url+current_controller+"/set_default_type"+default_ext;
			var priority = $(this).data("priority");
			var type = $(this).data("type");
			var category = $(this).data("category");
			var form_data = new FormData();
			form_data.append("mu_type", type);
			form_data.append("mu_priority_old", priority);
			form_data.append("markup_priority", "0");
			form_data.append("user_type", category);
			form_data.append("mu_user_old", category);
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
					custom_fn.show_loading("Markup type is being set as Default..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.set_default_markup_type").filter(function(i, el)
										{
											return $(this).data("href") === type;
										}).closest("tr");
						$("body").find("tr."+cur_row.attr("class")).each(function(index)
						{
							var data_priority = $(this).find("a[href='javascript:void(0);']").data("priority");
							if(parseInt(data_priority) <= priority)
							{
								$(this).find("td:eq(2)").html((data_priority * 1) + 1);
								$(this).find("a[href='javascript:void(0);']").data("priority", (data_priority * 1) + 1);
							}
						});
						$("body").find("tr."+cur_row.attr("class")+" a.default_markup_type").addClass("set_default_markup_type btn-danger").removeClass("default_markup_type btn-info").attr("title", "Set Default").tooltip('hide').attr('data-original-title', "Set Default").tooltip('fixTitle');
						cur_row.find("a.set_default_markup_type").addClass("default_markup_type btn-info").removeClass("set_default_markup_type btn-danger").attr("title", "Currently Default").tooltip('hide').attr('data-original-title', "Currently Default").tooltip('fixTitle');
						cur_row.find("td:eq(2)").html("0");
						cur_row.find("a[href='javascript:void(0);']").data("priority", "0");
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

		//popup window to confirm markup type
		$(document).on("click", "a.delete_markup_type", function()
		{
			var mu_type_name = $(this).data("name");
			var category = $(this).data("category");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_markup_type_template");
			$(".delete_markup_type_template").find("h4.modal-title").text("Markup Management - Delete Markup Type");
			$(".delete_markup_type_template").find("form").addClass("delete_markup_type_form").data("type", $(this).data("href")).data("priority", $(this).data("priority")).data("category", $(this).data("mu_category"));
			var form_data = "<ul>";
			form_data += "<li>'"+mu_type_name+"' markup type will be deleted from '"+category+"' category.</li>";
			form_data += "<li>'"+mu_type_name+"' priority will transfer to next other markup types.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+mu_type_name+"' markup type?";
			$(".delete_markup_type_template").find("div.modal-body").html(form_data);
			$(".delete_markup_type_template").find("button[type='submit']").html("Continue");
			$(".delete_markup_type_template").toggle();
		});

		// delete privilege
		$(document).on("submit", "form.delete_markup_type_form", function()
		{
			var url = base_url+current_controller+"/delete_type"+default_ext;
			var priority = $(this).data("priority");
			var type = $(this).data("type");
			var category = $(this).data("category");
			var form_data = new FormData();
			form_data.append("mu_type", type);
			form_data.append("markup_priority", priority);
			form_data.append("user_type", category);
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
					custom_fn.show_loading("Markup Type is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_markup_type").filter(function(i, el)
										{
											return $(this).data("href") === type;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='5' class='dataTables_empty'>No data available in table</td></tr>");
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

		//submit new markup
		$(document).on("submit", "form.add_markup_form", function(submit_event)
		{
			if($(this).find("input[name]").length === ($(this).find("input[name].valid").length + $(this).find("input[name]:disabled").length) && $(this).find("select[name].error").length === 0)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var user_type = cur_form.data("user_type") !== undefined ? cur_form.data("user_type") : "";
				var form_data = new FormData(cur_form[0]);
				form_data.append("user_type", user_type);
				url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Markup is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							cur_form.find("select").html("").trigger("change");
							cur_form.find(".optional_values").addClass("hide");
						}
						cur_form.find("input:eq(0)").focus();
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

		//update markup
		$(document).on("submit", "form.update_markup_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var markup = cur_form.data("href");
				var form_data = new FormData(cur_form[0]);
				form_data.append("markup", markup);
				url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Markup is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						cur_form.find("input:eq(0)").focus();
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


		// Display b2c markups
		if($("table.manage_b2c_markups").length > 0)
		$("table.manage_b2c_markups").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/b2c_markup_list"+default_ext,
			"iDisplayLength": 10,
			"bStateSave": true,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 7, 8, 9, 10]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_b2c_mrkup = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_b2c_mrkup.sl_no);
				$("td:eq(1)", nRow).html(jd_m_b2c_mrkup.name);
				$("td:eq(2)", nRow).html(jd_m_b2c_mrkup.country_name);
				$("td:eq(3)", nRow).html(jd_m_b2c_mrkup.api);
				$("td:eq(4)", nRow).html(jd_m_b2c_mrkup.airline);
				$("td:eq(5)", nRow).html(jd_m_b2c_mrkup.o_airport);
				$("td:eq(6)", nRow).html(jd_m_b2c_mrkup.d_airport);
				$("td:eq(7)", nRow).html(jd_m_b2c_mrkup.category);
				$("td:eq(8)", nRow).html(jd_m_b2c_mrkup.amount);
				$("td:eq(9)", nRow).html(jd_m_b2c_mrkup.status_html);
				$("td:eq(10)", nRow).html(jd_m_b2c_mrkup.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_b2c_mrkup.name).data("href", jd_m_b2c_mrkup.id).data("general", jd_m_b2c_mrkup.is_general);
			}
		}).fnSetFilteringDelay(2000);

		// Display b2b markups
		if($("table.manage_b2b_markups").length > 0)
		$("table.manage_b2b_markups").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/b2b_markup_list"+default_ext,
			"iDisplayLength": 10,
			"bStateSave": true,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 7, 8, 9, 10, 11]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_b2b_mrkup = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_b2b_mrkup.sl_no);
				$("td:eq(1)", nRow).html(jd_m_b2b_mrkup.name);
				$("td:eq(2)", nRow).html(jd_m_b2b_mrkup.country_name);
				$("td:eq(3)", nRow).html(jd_m_b2b_mrkup.api);
				$("td:eq(4)", nRow).html(jd_m_b2c_mrkup.airline);
				$("td:eq(5)", nRow).html(jd_m_b2c_mrkup.o_airport);
				$("td:eq(6)", nRow).html(jd_m_b2c_mrkup.d_airport);
				$("td:eq(7)", nRow).html(jd_m_b2b_mrkup.agent);
				$("td:eq(8)", nRow).html(jd_m_b2b_mrkup.category);
				$("td:eq(9)", nRow).html(jd_m_b2b_mrkup.amount);
				$("td:eq(10)", nRow).html(jd_m_b2b_mrkup.status_html);
				$("td:eq(11)", nRow).html(jd_m_b2b_mrkup.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_b2b_mrkup.name).data("href", jd_m_b2b_mrkup.id).data("general", jd_m_b2b_mrkup.is_general);
			}
		}).fnSetFilteringDelay(2000);

		// Change markup status	
		$(document).on("click", "input[type='checkbox'].markup_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("markup", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/markup_status"+default_ext;
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
						custom_fn.show_loading("Markup is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Markup is being activated..", "it will take a couple of seconds");
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

		//popup window to confirm markup
		$(document).on("click", "a.delete_markup", function()
		{
			var markup_name = $(this).data("name");
			var markup = $(this).data("href");
			var is_general = $(this).data("general");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_markup_template");
			$(".delete_markup_template").find("h4.modal-title").text("Markup Management - Delete Markup");
			$(".delete_markup_template").find("form").addClass("delete_markup_form").data("markup", markup);
			var form_data = "<ul>";
			form_data += "<li>'markup will be deleted from '"+markup_name+"' category.</li>";
			if(is_general === "")
				form_data += "<li>'"+markup_name+"' category is common to B2C and B2B Users. If continued, It will be removed for both Users.</li>";
			form_data += "</ul><br>Are you sure to delete markup from '"+markup_name+"' markup type?";
			$(".delete_markup_template").find("div.modal-body").html(form_data);
			$(".delete_markup_template").find("button[type='submit']").html("Continue");
			$(".delete_markup_template").toggle();
		});

		// delete privilege
		$(document).on("submit", "form.delete_markup_form", function()
		{
			var url = base_url+current_controller+"/delete_markup"+default_ext;
			var markup = $(this).data("markup");
			var form_data = new FormData();
			form_data.append("markup", markup);
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
					custom_fn.show_loading("Markup is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_markup").filter(function(i, el)
										{
											return $(this).data("href") === markup;
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
						var colspan_length = cur_row.children().length;
						if(cur_table.find("tbody tr").length === 0)
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='"+colspan_length+"' class='dataTables_empty'>No data available in table</td></tr>");
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
		// End of Markup Module Functionalities

	});
});
