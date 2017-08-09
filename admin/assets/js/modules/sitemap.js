require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_seo_form").length > 0)
			custom_fn.load_validate("add_seo_form");
		if($("form.update_seo_form").length > 0)
			custom_fn.load_validate("update_seo_form");

		if($("form.add_analytics_form").length > 0)
			custom_fn.load_validate("add_analytics_form");
		if($("form.update_analytics_form").length > 0)
			custom_fn.load_validate("update_analytics_form");

		// Sitemap Module Functionalities

		// Diplay all metatags
		if($("table.manage_metatags").length > 0)
			$("table.manage_metatags").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/metatags_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 1, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_mtags = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_mtags.sl_no);
					$("td:eq(1)", nRow).html(jd_m_mtags.metatag);
					$("td:eq(2)", nRow).html(jd_m_mtags.description);
					$("td:eq(3)", nRow).html(jd_m_mtags.status);
					$("td:eq(4)", nRow).html(jd_m_mtags.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_mtags.metatag).data("href", jd_m_mtags.id);
				}
			}).fnSetFilteringDelay(2000);

		//submit new Metatag
		$(document).on("submit", "form.add_seo_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
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
						custom_fn.show_loading("New Metatag is being added..", "it will take a couple of seconds");
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

		//submit update Metatag
		$(document).on("submit", "form.update_seo_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var tag_type = cur_form.find("[name='tag_type']");
				var tag_name = cur_form.find("[name='tag_name']");
				var form_data = new FormData(cur_form[0]);
				form_data.append("metatag", cur_form.data("href"))
				form_data.append("tag_type_old", tag_type.data("link"))
				form_data.append("tag_name_old", tag_name.data("href"))
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
						custom_fn.show_loading("Metatag is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							tag_type.data("link", response.new_data["tag_type"]);
							tag_name.data("href", response.new_data["tag_name"]);
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

		// Change Metatags visible status
		$(document).on("click", "input[type='checkbox'].metatag_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("metatag", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/seo_status"+default_ext;
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
						custom_fn.show_loading("Metatag is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Metatag is being activated..", "it will take a couple of seconds");
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

		//popup window to confirm delete Metatag 
		$(document).on("click", "a.delete_metatag", function()
		{
			var metatag_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_metatag_template");
			$(".delete_metatag_template").find("h4.modal-title").text("Sitemap Management - Delete Metatag");
			$(".delete_metatag_template").find("form").addClass("delete_metatag_form").data("metatag", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+metatag_name+"' Metatag will be completely lost if you continue.</li>";
			form_data += "<li>Except \" name => title\", none other has a default value and it will be removed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+metatag_name+"' metatag?";
			$(".delete_metatag_template").find("div.modal-body").html(form_data);
			$(".delete_metatag_template").find("button[type='submit']").html("Continue");
			$(".delete_metatag_template").toggle();
		});

		// delete metatag confirmed
		$(document).on("submit", "form.delete_metatag_form", function()
		{
			var url = base_url+current_controller+"/delete_seo"+default_ext;
			var metatag = $(this).data("metatag");
			var form_data = new FormData();
			form_data.append("metatag", metatag);
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
					custom_fn.show_loading("Metatag is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_metatag").filter(function(i, el)
										{
											return $(this).data("href") === metatag;
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

		// Diplay all analytics
		if($("table.manage_analytics").length > 0)
			$("table.manage_analytics").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/analytics_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4, 5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_gtracker = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_gtracker.sl_no);
					$("td:eq(1)", nRow).html(jd_m_gtracker.track_code);
					$("td:eq(2)", nRow).html(jd_m_gtracker.track_name);
					$("td:eq(3)", nRow).html(jd_m_gtracker.track_type);
					$("td:eq(4)", nRow).html(jd_m_gtracker.status);
					$("td:eq(5)", nRow).html(jd_m_gtracker.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_gtracker.track_name).data("href", jd_m_gtracker.id);
				}
			}).fnSetFilteringDelay(2000);


		//submit new analytic
		$(document).on("submit", "form.add_analytics_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/analytics_add"+default_ext;
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
						custom_fn.show_loading("New Analytics is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
							cur_form[0].reset();
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

		// Change Analytics visible status
		$(document).on("click", "input[type='checkbox'].analytics_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("analytics", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/analytics_status"+default_ext;
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
						custom_fn.show_loading("Analytics is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Analytics is being activated..", "it will take a couple of seconds");
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

		//update analytic
		$(document).on("submit", "form.update_analytics_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var code = cur_form.find("input[name='code']");
				var track_name = cur_form.find("input[name='track_name']");
				var form_data = new FormData(cur_form[0]);
				form_data.append("analytics", cur_form.data("href"));
				form_data.append("code_old", code.data("href"));
				form_data.append("track_name_old", track_name.data("href"));
				url = base_url+current_controller+"/analytics_edit"+default_ext;
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
						custom_fn.show_loading("Analytics is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							code.data("href", response.new_data["code"]);
							track_name.data("href", response.new_data["track_name"]);
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

		//popup window to confirm delete Analytics 
		$(document).on("click", "a.delete_analytics", function()
		{
			var analytic_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_analytics_template");
			$(".delete_analytics_template").find("h4.modal-title").text("Sitemap Management - Delete Analytics");
			$(".delete_analytics_template").find("form").addClass("delete_analytics_form").data("analytics", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+analytic_name+"' Analytics will be completely lost if you continue.</li>";
			form_data += "<li>Analytical record of '"+analytic_name+"' analytics will stop recording.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+analytic_name+"' analytics?";
			$(".delete_analytics_template").find("div.modal-body").html(form_data);
			$(".delete_analytics_template").find("button[type='submit']").html("Continue");
			$(".delete_analytics_template").toggle();
		});

		// delete analytic confirmed
		$(document).on("submit", "form.delete_analytics_form", function()
		{
			var url = base_url+current_controller+"/delete_analytics"+default_ext;
			var analytics = $(this).data("analytics");
			var form_data = new FormData();
			form_data.append("analytics", analytics);
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
					custom_fn.show_loading("Analytics is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_analytics").filter(function(i, el)
										{
											return $(this).data("href") === analytics;
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
		// End of Sitemap Module Functionalities

	});
});
