require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_percent_promo_form").length > 0)
			custom_fn.load_validate("add_percent_promo_form");
		if($("form.add_cash_promo_form").length > 0)
			custom_fn.load_validate("add_cash_promo_form");
		if($("form.send_promocode_form").length > 0)
			custom_fn.load_validate("send_promocode_form");

		// Promocode Module Functionalities
		
		// Load All Promocode list
		if($("table.manage_promocodes").length > 0)
			$("table.manage_promocodes").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/promo_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 6, 7, 8]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_promo = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_promo.sl_no);
					$("td:eq(1)", nRow).html(jd_m_promo.code);
					$("td:eq(2)", nRow).html(jd_m_promo.promo_type);
					$("td:eq(3)", nRow).html(jd_m_promo.range);
					$("td:eq(4)", nRow).html(jd_m_promo.discount_html);
					$("td:eq(5)", nRow).html(jd_m_promo.valid_from);
					$("td:eq(6)", nRow).html(jd_m_promo.valid_to_html);
					$("td:eq(7)", nRow).html(jd_m_promo.status_html);
					$("td:eq(8)", nRow).html(jd_m_promo.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_promo.id).data("name", jd_m_promo.code);
				}
			}).fnSetFilteringDelay(2000);

		// toggle between auto promocode and manual
		$(document).on("click", "div.auto_promo_toggle a", function()
		{
			var sel = $(this).data("href");
			var this_parent = $(this).closest("form");
			var promocode = this_parent.find(".set_readonly_promo").data("href");
			this_parent.find('.auto_promo_toggle a').each(function(index)
			{
				if($(this).data("href") === sel)
					$(this).removeClass("notActive").addClass("active");
				else
					$(this).removeClass("active").addClass("notActive");
			});
			if((sel * 1) === 1)
				this_parent.find(".set_readonly_promo").val(promocode).attr("readonly", "readonly");
			else
			this_parent.find(".set_readonly_promo").removeAttr("readonly value").focus();
		});

		//Add new promocode
		$(document).on("submit", "form.add_percent_promo_form, form.add_cash_promo_form", function(e)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var cur_form = $(this)[0];
				var form_data = new FormData(cur_form);
				if($(this).hasClass("add_percent_promo_form"))
					form_data.append("promo_type", "percentage");
				else
					form_data.append("promo_type", "amount");
				var url = base_url+current_controller+"/add_promo"+default_ext;

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
						custom_fn.show_loading("Promocode being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						cur_form.reset();
						if(response.status === "true")
							$(".set_readonly_promo").data("href", response.new_code);
						$(".set_readonly_promo").val($(".set_readonly_promo").data("href"));
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(5000);
					},
					error: function(response)
					{
						cur_form.reset();
						$(".set_readonly_promo").val($(".set_readonly_promo").data("href"));
						custom_fn.hide_loading();
						custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
						custom_fn.set_auto_close(5000);
					}
				});
			}
		});

		$(".promocode_status").bootstrapSwitch(
		{
			onSwitchChange:function(event, state)
			{
				var cur_var = $(this);
				var checked = cur_var.is(":checked");
				var status = state ? "1" : "0";
				var href = $(this).data("href");
				var form_data = new FormData();
				form_data.append("promocode", href);
				form_data.append("status", status);
				url = base_url+current_controller+"/promocode_status"+default_ext;
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
							custom_fn.show_loading("Promocode is being deactivated..", "it will take a couple of seconds");
						else
							custom_fn.show_loading("Promocode is being activated..", "it will take a couple of seconds");
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
			}
		});

		// Change promocode status
		$(document).on("click", "input[type='checkbox'].promocode_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("promocode", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/promocode_status"+default_ext;
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
						custom_fn.show_loading("Promocode is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Promocode is being activated..", "it will take a couple of seconds");
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


		// submit send promocode
		$(document).on("submit", "form.send_promocode_form", function()
		{
			if($(this).find("input[name]").length > 0)
			{
				var url = base_url+current_controller+"/"+current_method+default_ext;
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("promocode", cur_form.data("href"));
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
						custom_fn.show_loading("Promocode is being sent to emails..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							cur_form[0].reset();
							cur_form.find(".input_tags").tagsinput("removeAll");
						}
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

		// popup message to edit promocode (un-finished)
		$(document).on("click", "a.edit_promocode", function()
		{
			// $("body").prepend(custom_fn.model_template);
			// var promocode = $(this).closest("tr").find("td:eq(1)").html();
			// var amount_range = $(this).closest("tr").find("td:eq(3)").html().split(" ");
			// var discount = $(this).closest("tr").find("td:eq(4)").html().split(" ");
			// var expiry_date = $(this).closest("tr").find("td:eq(6):contains('Expired')").length > 0 ? new Date() : $(this).closest("tr").find("td:eq(6)").html();
			// $(".model_template").addClass("edit_promocode_template");
			// $(".edit_promocode_template").find("h4.modal-title").text("Manage Promocode - Edit Promocode");
			// var form_body = "<div class='form-group'>";
			// form_body += "<label class='control-label col-sm-5'>Promocode</label>";
			// form_body += "<div class='col-sm-7 controls'>";
			// form_body += "<input autocomplete='off' class='form-control' value='"+promocode+"' disabled='disabled'>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "<div class='form-group'>";
			// form_body += "<label class='control-label col-sm-5' for='discount'>Enter amount range</label>";
			// form_body += "<div class='col-sm-7 controls'>";
			// form_body += "<input autocomplete='off' class='form-control' value='"+amount_range[0]+"' data-rule-number='true' data-rule-required='true' data-rule-min='0' name='discount' type='text' placeholder='Discount'>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "<div class='form-group'>";
			// form_body += "<label class='control-label col-sm-5' for='promo_amount'>Enter discount in "+discount[1]+"</label>";
			// form_body += "<div class='col-sm-7 controls'>";
			// form_body += "<input autocomplete='off' class='form-control' value='"+discount[0]+"' data-rule-number='true' data-rule-required='true' data-rule-min='0' name='promo_amount' type='text' placeholder='Amount Range'>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "<div class='form-group'>";
			// form_body += "<label class='control-label col-sm-5' for='expiry_date'>Expiry Date</label>";
			// form_body += "<div class='input-group col-sm-7'>";
			// form_body += "<input autocomplete='off' class='form-control today_onwards_limited' value='"+expiry_date+"' data-format='MM/DD/YYYY' data-rule-required ='true' data-msg-required='Please select date.' placeholder='MM/DD/YYYY' name='expiry_date'  type='text' readonly=''>";
			// form_body += "<span class='input-group-addon'>";
			// form_body += "<span class='icon-calendar' data-time-icon='icon-time'></span>";
			// form_body += "</span>";
			// form_body += "</div>";
			// form_body += "</div>";
			// form_body += "</div>";
			// $(".edit_promocode_template").find("div.modal-body").html(form_body);
			// $(".edit_promocode_template").find("button[type='submit']").html("Update");
			// $(".edit_promocode_template").find("form").addClass("edit_promocode_form").data("promo", $(this).data("href"));
			// $(".edit_promocode_template").toggle();
			// custom_fn.load_validate("edit_promocode_form");
		});

		//update promocode
		$(document).on("submit", "form.edit_promocode_form", function()
		{
			// if($(this).find("input").length === $(this).find("input.valid").length)
			// {
			// 	var cur_form = $(this)[0];
			// 	var updated_name = $(this).find("input[name='lang_page']").val();
			// 	var page = $(this).data("page");
			// 	var url = base_url+current_controller+"/update_page"+default_ext;
			// 	var form_data = new FormData(cur_form);
			// 	form_data.append("page", page);
			// 	$.ajax(
			// 	{
			// 		url: url,
			// 		method: "POST",
			// 		dataType: "JSON",
			// 		data: form_data,
			// 		processData: false,
			// 		contentType:false,
			// 		beforeSend: function()
			// 		{
			// 			// remove popup window
			// 			$("body").find(".model_template").detach();
			// 			//show popup
			// 			custom_fn.show_loading("Promocode is being updated..", "it will take a couple of seconds");
			// 		},
			// 		success: function(response)
			// 		{
			// 			if(response.status === "true")
			// 			{
			// 				$("body").find("a.edit_promocode").filter(function(i, el)
			// 				{
			// 					return $(this).data("href") === page;
			// 				}).closest("tr").find("td:eq(1)").html(updated_name);
			// 			}
			// 			custom_fn.hide_loading();
			// 			custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
			// 			custom_fn.set_auto_close(7000);
			// 		},
			// 		error: function(response)
			// 		{
			// 			custom_fn.hide_loading();
			// 			custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
			// 			custom_fn.set_auto_close(5000);
			// 		}
			// 	});
			// }
		});

		//popup window to confirm delete promocode 
		$(document).on("click", "a.delete_promocode", function()
		{
			var promo_code = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_promocode_template");
			$(".delete_promocode_template").find("h4.modal-title").text("Manage Promocode - Delete Promocode");
			$(".delete_promocode_template").find("form").addClass("delete_promocode_form").data("promocode", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+promo_code+"' promocode will be completely lost if you continue.</li>";
			form_data += "<li>'"+promo_code+"' promocode will be treated as invalid if any user try to apply at booking time.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+promo_code+"' promocode?";
			$(".delete_promocode_template").find("div.modal-body").html(form_data);
			$(".delete_promocode_template").find("button[type='submit']").html("Continue");
			$(".delete_promocode_template").toggle();
		});

		// delete privilege
		$(document).on("submit", "form.delete_promocode_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var promocode = $(this).data("promocode");
			var form_data = new FormData();
			form_data.append("promocode", promocode);
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
					custom_fn.show_loading("Promocode is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_promocode").filter(function(i, el)
										{
											return $(this).data("href") === promocode;
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
	// End of Promocode Module Functionalities

	});
});
