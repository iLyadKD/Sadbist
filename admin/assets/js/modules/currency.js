require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_currency_form").length > 0)
			custom_fn.load_validate("add_currency_form");
		if($("form.update_currency_form").length > 0)
			custom_fn.load_validate("update_currency_form");

		// Currency Management Module Functionlities

		// Diplay all Currencies
		if($("table.manage_currencies").length > 0)
			$("table.manage_currencies").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/currency_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5, 6, 7]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_curr = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_curr.sl_no);
					$("td:eq(1)", nRow).html(jd_m_curr.country);
					$("td:eq(2)", nRow).html(jd_m_curr.code);
					$("td:eq(3)", nRow).html(jd_m_curr.cur_name);
					$("td:eq(4)", nRow).html(jd_m_curr.value);
					$("td:eq(5)", nRow).html(jd_m_curr.last_updated);
					$("td:eq(6)", nRow).html(jd_m_curr.status_html);
					$("td:eq(7)", nRow).html(jd_m_curr.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_curr.id).data("name", jd_m_curr.country);
				}
			}).fnSetFilteringDelay(2000);

		// Change Currency status
		$(document).on("click", "input[type='checkbox'].currency_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("currency", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/currency_status"+default_ext;
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
						custom_fn.show_loading("Currency is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Currency is being activated..", "it will take a couple of seconds");
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

		//submit add currency
		$(document).on("submit", "form.add_currency_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var country_select = cur_form.find("select.unset_currency_country");
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
						custom_fn.show_loading("Country currency is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							country_select.html("").trigger("change");
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

		//submit update currency
		$(document).on("submit", "form.update_currency_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("currency", cur_form.data("href"));
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
						custom_fn.show_loading("Country currency is being updated..", "it will take a couple of seconds");
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

		//popup window to confirm delete currency 
		$(document).on("click", "a.delete_currency", function()
		{
			var country_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_currency_template");
			$(".delete_currency_template").find("h4.modal-title").text("Currency Management - Delete Currency");
			$(".delete_currency_template").find("form").addClass("delete_currency_form").data("currency", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+country_name+"' Country currency will be completely lost if you continue.</li>";
			form_data += "<li>Result will not be able to fetch for '"+country_name+"' Country currency.</li>";
			form_data += "</ul><br>Are you sure to delete '"+country_name+"' Country currency?";
			$(".delete_currency_template").find("div.modal-body").html(form_data);
			$(".delete_currency_template").find("button[type='submit']").html("Continue");
			$(".delete_currency_template").toggle();
		});

		// delete currency
		$(document).on("submit", "form.delete_currency_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var currency = $(this).data("currency");
			var form_data = new FormData();
			form_data.append("currency", currency);
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
					custom_fn.show_loading("Country currency is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_currency").filter(function(i, el)
										{
											return $(this).data("href") === currency;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='8' class='dataTables_empty'>No data available in table</td></tr>");
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
		// End of Currency Management Module Functionlities

	});
});
