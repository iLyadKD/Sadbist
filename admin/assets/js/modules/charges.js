require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_taxes_form").length > 0)
			custom_fn.load_validate("add_taxes_form");
		if($("form.update_taxes_form").length > 0)
			custom_fn.load_validate("update_taxes_form");

		if($("form.add_tax_charges_form").length > 0)
			custom_fn.load_validate("add_tax_charges_form");
		if($("form.update_tax_charges_form").length > 0)
			custom_fn.load_validate("update_tax_charges_form");

		if($("form.add_payment_gateway_form").length > 0)
			custom_fn.load_validate("add_payment_gateway_form");
		if($("form.update_payment_gateway_form").length > 0)
			custom_fn.load_validate("update_payment_gateway_form");

		if($("form.add_pg_charges_form").length > 0)
			custom_fn.load_validate("add_pg_charges_form");
		if($("form.update_pg_charges_form").length > 0)
			custom_fn.load_validate("update_pg_charges_form");

		// Charges Module Functionalities

		// Manage Payment Gateways
		if($("table.manage_payment_gateways").length > 0)
			$("table.manage_payment_gateways").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/pg_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_pay_gw = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_pay_gw.sl_no);
					$("td:eq(1)", nRow).html(jd_m_pay_gw.title);
					$("td:eq(2)", nRow).html(jd_m_pay_gw.pay_mode);
					$("td:eq(3)", nRow).html(jd_m_pay_gw.status_html);
					$("td:eq(4)", nRow).html(jd_m_pay_gw.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_pay_gw.id).data("name", jd_m_pay_gw.title);
				}
			}).fnSetFilteringDelay(2000);


		//display required message for payment mode
		$(document).on("click", "input[name='pay_mode[]']", function()
		{
			if($("input[name='pay_mode[]']:checked").length > 0)
				$(".paymode_error").html("");
			else
				$(".paymode_error").html("<label class='mrgn_top_more'>Please select at-least one payment mode.</label>");
		});

		// Add payment gateway
		$(document).on("submit", "form.add_payment_gateway_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Payment Gateway is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form[0].reset();
						cur_form.find("input[name]").focus();
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

		// Change Payment Gateway status	
		$(document).on("click", "input[type='checkbox'].payment_gateway_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("payment_gateway", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/payment_gateway_status"+default_ext;
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
						custom_fn.show_loading("Payment Gateway is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Payment Gateway is being activated..", "it will take a couple of seconds");
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

		// Update payment gateway
		$(document).on("submit", "form.update_payment_gateway_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var pg_name = cur_form.find("input[name='pg_name']");
				var form_data = new FormData(cur_form[0]);
				form_data.append("payment_gateway", cur_form.data("href"));
				form_data.append("pg_name_old", pg_name.data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Payment Gateway is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						pg_name.data("href", response.payment_gateway);
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

		//popup window to confirm delete payment gateway
		$(document).on("click", "a.delete_payment_gateway", function()
		{
			var payment_gateway_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_payment_gateway_template");
			$(".delete_payment_gateway_template").find("h4.modal-title").text("Charges Management - Delete Payment Gateway");
			$(".delete_payment_gateway_template").find("form").addClass("delete_payment_gateway_form").data("payment_gateway", $(this).data("href")).data("name", $(this).data("name"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+payment_gateway_name+"' Payment Gateway will be completely lost if you continue.</li>";
			form_data += "<li>'"+payment_gateway_name+"' payment gateway charges will also be deleted.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+payment_gateway_name+"' payment gateway?";
			$(".delete_payment_gateway_template").find("div.modal-body").html(form_data);
			$(".delete_payment_gateway_template").find("button[type='submit']").html("Continue");
			$(".delete_payment_gateway_template").toggle();
		});

		// delete payment gateway
		$(document).on("submit", "form.delete_payment_gateway_form", function()
		{
			var url = base_url+current_controller+"/delete_payment_gateway"+default_ext;
			var payment_gateway = $(this).data("payment_gateway");
			var form_data = new FormData();
			form_data.append("payment_gateway", payment_gateway);
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
					custom_fn.show_loading("Payment Gateway is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_payment_gateway").filter(function(i, el)
										{
											return $(this).data("href") === payment_gateway;
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

		// Manage Payment Gateway Charges
		if($("table.manage_pg_charges").length > 0)
			$("table.manage_pg_charges").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/pg_charges_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4, 5, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_pay_gw_chrg = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_pay_gw_chrg.sl_no);
					$("td:eq(1)", nRow).html(jd_m_pay_gw_chrg.title);
					$("td:eq(2)", nRow).html(jd_m_pay_gw_chrg.api);
					$("td:eq(3)", nRow).html(jd_m_pay_gw_chrg.pay_mode);
					$("td:eq(4)", nRow).html(jd_m_pay_gw_chrg.amount);
					$("td:eq(5)", nRow).html(jd_m_pay_gw_chrg.status_html);
					$("td:eq(6)", nRow).html(jd_m_pay_gw_chrg.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_pay_gw_chrg.id).data("name", jd_m_pay_gw_chrg.title);
				}
			}).fnSetFilteringDelay(2000);

		$(document).on("click", "form.add_pg_charges_form input[name='pay_mode']", function()
		{
			var current_form = $(this).closest("form.add_pg_charges_form");
			var amount_div = current_form.find(".payable_amount_div");
			if(current_form !== undefined && current_form.length > 0)
			{
				var pay_mode = $(this).val();
				amount_div.find(">label").html($(this).parent().text());
				if(parseInt(pay_mode) === 1)
					amount_div.find("input").attr({"placeholder" : "Percentage"});
				else
					amount_div.find("input").attr({"placeholder" : "Amount"});
				amount_div.find("input").next().detach()
				custom_fn.load_validate('add_pg_charges_form');
			}
		});


		// Add payment charges
		$(document).on("submit", "form.add_pg_charges_form", function(submit_event)
		{
			if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Payment Gateway Charges is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							cur_form[0].reset();
							$("select.set_payment_gateway").closest(".form-group").next().addClass("hide");
						}
						cur_form.find("input[name]").focus();
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


		// Change Payment Charges status	
		$(document).on("click", "input[type='checkbox'].pg_charges_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("pg_charge", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/pg_charges_status"+default_ext;
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
						custom_fn.show_loading("Payment Charges is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Payment Charges is being activated..", "it will take a couple of seconds");
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

		// Update payment charges
		$(document).on("submit", "form.update_pg_charges_form", function(submit_event)
		{
			if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("pg_charge", cur_form.data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Payment Gateway Charge is being updated..", "it will take a couple of seconds");
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

		//popup window to confirm delete payment charges
		$(document).on("click", "a.delete_pg_charge", function()
		{
			var pg_charge_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_pg_charge_template");
			$(".delete_pg_charge_template").find("h4.modal-title").text("Charges Management - Delete Payment Charges");
			$(".delete_pg_charge_template").find("form").addClass("delete_pg_charge_form").data("pg_charge", $(this).data("href")).data("name", $(this).data("name"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+pg_charge_name+"' Payment Charges will be completely lost if you continue.</li>";
			form_data += "<li>'"+pg_charge_name+"' payment charges will not be applicable to any bookings.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+pg_charge_name+"' payment charge?";
			$(".delete_pg_charge_template").find("div.modal-body").html(form_data);
			$(".delete_pg_charge_template").find("button[type='submit']").html("Continue");
			$(".delete_pg_charge_template").toggle();
		});

		// delete payment charges
		$(document).on("submit", "form.delete_pg_charge_form", function()
		{
			var url = base_url+current_controller+"/delete_pg_charge"+default_ext;
			var pg_charge = $(this).data("pg_charge");
			var form_data = new FormData();
			form_data.append("pg_charge", pg_charge);
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
					custom_fn.show_loading("Payment Charges is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_pg_charge").filter(function(i, el)
										{
											return $(this).data("href") === pg_charge;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='7' class='dataTables_empty'>No data available in table</td></tr>");
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


		// Manage Taxes
		if($("table.manage_taxes").length > 0)
			$("table.manage_taxes").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/taxes_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_tax = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_tax.sl_no);
					$("td:eq(1)", nRow).html(jd_m_tax.title);
					$("td:eq(2)", nRow).html(jd_m_tax.pay_mode);
					$("td:eq(3)", nRow).html(jd_m_tax.status_html);
					$("td:eq(4)", nRow).html(jd_m_tax.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_tax.id).data("name", jd_m_tax.title);
				}
			}).fnSetFilteringDelay(2000);

		// Add Tax
		$(document).on("submit", "form.add_taxes_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Tax is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form[0].reset();
						cur_form.find("input[name]").focus();
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


		// Change Tax status	
		$(document).on("click", "input[type='checkbox'].tax_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("tax", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/taxes_status"+default_ext;
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
						custom_fn.show_loading("Tax is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Tax is being activated..", "it will take a couple of seconds");
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

		// Update Tax
		$(document).on("submit", "form.update_taxes_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var tax_name = cur_form.find("input[name='tax_name']");
				var form_data = new FormData(cur_form[0]);
				form_data.append("tax", cur_form.data("href"));
				form_data.append("tax_name_old", tax_name.data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Tax is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						tax_name.data("href", response.tax_name);
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

		//popup window to confirm delete Tax
		$(document).on("click", "a.delete_tax", function()
		{
			var tax_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_tax_template");
			$(".delete_tax_template").find("h4.modal-title").text("Charges Management - Delete Tax");
			$(".delete_tax_template").find("form").addClass("delete_tax_form").data("tax", $(this).data("href")).data("name", $(this).data("name"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+tax_name+"' Tax will be completely lost if you continue.</li>";
			form_data += "<li>'"+tax_name+"' Tax charges will also be deleted.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+tax_name+"' Tax?";
			$(".delete_tax_template").find("div.modal-body").html(form_data);
			$(".delete_tax_template").find("button[type='submit']").html("Continue");
			$(".delete_tax_template").toggle();
		});

		// delete Tax
		$(document).on("submit", "form.delete_tax_form", function()
		{
			var url = base_url+current_controller+"/delete_taxes"+default_ext;
			var tax = $(this).data("tax");
			var form_data = new FormData();
			form_data.append("tax", tax);
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
					custom_fn.show_loading("Tax is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_tax").filter(function(i, el)
										{
											return $(this).data("href") === tax;
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

		// Manage Tax Charges
		if($("table.manage_tax_charges").length > 0)
			$("table.manage_tax_charges").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/tax_charges_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4, 5, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_tax_chrg = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_tax_chrg.sl_no);
					$("td:eq(1)", nRow).html(jd_m_tax_chrg.title);
					$("td:eq(2)", nRow).html(jd_m_tax_chrg.api);
					$("td:eq(3)", nRow).html(jd_m_tax_chrg.pay_mode);
					$("td:eq(4)", nRow).html(jd_m_tax_chrg.amount);
					$("td:eq(5)", nRow).html(jd_m_tax_chrg.status_html);
					$("td:eq(6)", nRow).html(jd_m_tax_chrg.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_tax_chrg.id).data("name", jd_m_tax_chrg.title);
				}
			}).fnSetFilteringDelay(2000);

		$(document).on("click", "form.add_tax_charges_form input[name='pay_mode']", function()
		{
			var current_form = $(this).closest("form.add_tax_charges_form");
			var amount_div = current_form.find(".payable_amount_div");
			if(current_form !== undefined && current_form.length > 0)
			{
				var pay_mode = $(this).val();
				amount_div.find(">label").html($(this).parent().text());
				if(parseInt(pay_mode) === 1)
					amount_div.find("input").attr({"placeholder" : "Percentage"});
				else
					amount_div.find("input").attr({"placeholder" : "Amount"});
				amount_div.find("input").next().detach()
				custom_fn.load_validate('add_tax_charges_form');
			}
		});


		// Add tax charges
		$(document).on("submit", "form.add_tax_charges_form", function(submit_event)
		{
			if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Tax Charges is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							cur_form[0].reset();
							$("select").html("").trigger("change");
						}
						cur_form.find("input[name]").focus();
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


		// Change Tax Charges status	
		$(document).on("click", "input[type='checkbox'].tax_charges_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("tax_charge", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/tax_charges_status"+default_ext;
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
						custom_fn.show_loading("Tax Charges is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Tax Charges is being activated..", "it will take a couple of seconds");
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

		// Update tax charges
		$(document).on("submit", "form.update_tax_charges_form", function(submit_event)
		{
			if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("tax_charge", cur_form.data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Tax Charge is being updated..", "it will take a couple of seconds");
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

		//popup window to confirm delete tax charges
		$(document).on("click", "a.delete_tax_charge", function()
		{
			var tax_charge_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_tax_charge_template");
			$(".delete_tax_charge_template").find("h4.modal-title").text("Charges Management - Delete Tax Charges");
			$(".delete_tax_charge_template").find("form").addClass("delete_tax_charge_form").data("tax_charge", $(this).data("href")).data("name", $(this).data("name"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+tax_charge_name+"' Tax Charges will be completely lost if you continue.</li>";
			form_data += "<li>'"+tax_charge_name+"' tax charges will not be applicable to any bookings.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+tax_charge_name+"' payment charge?";
			$(".delete_tax_charge_template").find("div.modal-body").html(form_data);
			$(".delete_tax_charge_template").find("button[type='submit']").html("Continue");
			$(".delete_tax_charge_template").toggle();
		});

		// delete tax charges
		$(document).on("submit", "form.delete_tax_charge_form", function()
		{
			var url = base_url+current_controller+"/delete_tax_charge"+default_ext;
			var tax_charge = $(this).data("tax_charge");
			var form_data = new FormData();
			form_data.append("tax_charge", tax_charge);
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
					custom_fn.show_loading("Tax Charges is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_tax_charge").filter(function(i, el)
										{
											return $(this).data("href") === tax_charge;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='7' class='dataTables_empty'>No data available in table</td></tr>");
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
		// End of Charges Module Functionalities

	});
});
