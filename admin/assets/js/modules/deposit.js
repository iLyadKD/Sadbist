require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_deposit_form").length > 0)
			custom_fn.load_validate("add_deposit_form");

		// Agent Module Functionalities

		if($(".single_b2b_user_deposits").length > 0)
		{
			user = $(".single_b2b_user_deposits").data("href");
			$(".single_b2b_user_deposits").dataTable(
			{
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/b2b_user_deposits"+default_ext+"?user="+user,
				"iDisplayLength": 10,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [4, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_s_b2b_dep = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_s_b2b_dep.tranx_id);
					$("td:eq(1)", nRow).html(jd_s_b2b_dep.deposited);
					$("td:eq(2)", nRow).html(jd_s_b2b_dep.amount);
					$("td:eq(3)", nRow).html(jd_s_b2b_dep.tranx_category);
					$("td:eq(4)", nRow).html(jd_s_b2b_dep.tranx_slip);
					$("td:eq(5)", nRow).html(jd_s_b2b_dep.remarks);
					$("td:eq(6)", nRow).html(jd_s_b2b_dep.status_html);
					$("td:eq(6) select", nRow).data("name", jd_s_b2b_dep.tranx_id).data("href", jd_s_b2b_dep.id).data("current_status", jd_s_b2b_dep.status).data("b2b_id", jd_s_b2b_dep.b2b_id);
				}
			}).fnSetFilteringDelay(2000);
		}

		if($(".manage_b2b_user_deposit_reqs").length > 0)
			$(".manage_b2b_user_deposit_reqs").dataTable(
			{
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/b2b_user_deposit_requests"+default_ext,
				"iDisplayLength": 10,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [
									{"bSearchable": false, "aTargets": [8]}
								],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_s_b2b_dep = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_s_b2b_dep.id_company);
					$("td:eq(1)", nRow).html(jd_s_b2b_dep.logo_html);
					$("td:eq(2)", nRow).html(jd_s_b2b_dep.name_email);
					$("td:eq(3)", nRow).html(jd_s_b2b_dep.tranx_id);
					$("td:eq(4)", nRow).html(jd_s_b2b_dep.deposited);
					$("td:eq(5)", nRow).html(jd_s_b2b_dep.amount);

					$("td:eq(6)", nRow).html(jd_s_b2b_dep.tranx_slip);
					$("td:eq(7)", nRow).html(jd_s_b2b_dep.remarks);
					$("td:eq(8)", nRow).html(jd_s_b2b_dep.status_html);
					$("td:eq(8) select", nRow).data("name", jd_s_b2b_dep.tranx_id).data("href", jd_s_b2b_dep.id).data("current_status", jd_s_b2b_dep.status).data("b2b_id", jd_s_b2b_dep.b2b_id);

					// $("td:eq(0)", nRow).html(jd_s_b2b_dep.sl_no);
					// $("td:eq(1)", nRow).html(jd_s_b2b_dep.id_company);
					// $("td:eq(2)", nRow).html(jd_s_b2b_dep.logo_html);
					// $("td:eq(3)", nRow).html(jd_s_b2b_dep.name_email);
					// $("td:eq(4)", nRow).html(jd_s_b2b_dep.tranx_id);
					// $("td:eq(5)", nRow).html(jd_s_b2b_dep.deposited);
					// $("td:eq(6)", nRow).html(jd_s_b2b_dep.amount);
					// $("td:eq(7)", nRow).html(jd_s_b2b_dep.tranx_category);
					// $("td:eq(8)", nRow).html(jd_s_b2b_dep.tranx_slip);
					// $("td:eq(9)", nRow).html(jd_s_b2b_dep.remarks);
					// $("td:eq(10)", nRow).html(jd_s_b2b_dep.status_html);
					// $("td:eq(10) select", nRow).data("name", jd_s_b2b_dep.tranx_id).data("href", jd_s_b2b_dep.id).data("current_status", jd_s_b2b_dep.status).data("b2b_id", jd_s_b2b_dep.b2b_id);
				}
			}).fnSetFilteringDelay(2000);

		$(document).on("change", "select.pending_deposit_request", function()
		{
			var name = $(this).data("name");
			var b2b_id = $(this).data("b2b_id");
			var status = $(this).val();
			$(this).data("changed_to", $(this).val());
			$(this).val($(this).data("current_status"));
			var id = $(this).data("href");
			var respond = status === "1" ? "Accept" : "Reject";
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("pending_deposit_request_template");
			$(".pending_deposit_request_template").find("h4.modal-title").text("Deposit Management - "+respond+" the Request");
			var form_data = "<ul>";
			form_data += "<li>You can only respond to request only once and can not be reverted.</li>";
			form_data += "<li>If wrong response is selected, \"Add Deposit\" can add or remove amount with valid remarks.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to process transaction '"+name+"'?";
			$(".pending_deposit_request_template").find("div.modal-body").html(form_data);
			if(status === "1")
				$(".pending_deposit_request_template").find("button[type='submit']").html("Confirm Accept");
			else
				$(".pending_deposit_request_template").find("button[type='submit']").html("Confirm Cancel");
			$(".pending_deposit_request_template").toggle();
			$(".pending_deposit_request_template").find("form").addClass("pending_deposit_request_form").data("tranx", id).data("status", status).data("b2b_id", b2b_id);
			custom_fn.load_validate("pending_deposit_request_form");
		});

		$(document).on("submit", "form.pending_deposit_request_form", function()
		{
			var id = $(this).data("tranx");
			cur_var = $("body").find("select.pending_deposit_request").filter(function(i, el)
						{
							return $(this).data("href") === id;
						});
			var form_data = new FormData();
			form_data.append("tranx", id);
			form_data.append("status", $(this).data("status"));
			form_data.append("b2b_id", $(this).data("b2b_id"));
			var url = base_url+current_controller+"/pending_response";
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
					custom_fn.show_loading("Deposit request is being processed..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						if(cur_var.hasClass("relinquish"))
						{
							cur_row = cur_var.closest("tr");
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
						else
						{
							cur_var.closest("td").html(response.status_html);
							if($(document).find(".b2b_user_balance_deposit").length === 1)
								$(document).find(".b2b_user_balance_deposit").html(response.updated_balance);
						}
					}
					custom_fn.hide_loading();
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(7000);
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.set_auto_close(5000);
				}
			});
		});


		$(document).on("submit", "form.add_deposit_form", function(submit_event)
		{
			if($(this).find("input").length === $(this).find("input.valid").length && $(this).find("textarea").length === $(this).find("textarea.valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("user", $(this).data("href"));
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
						custom_fn.show_loading("Deposit is being processed..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true" || response.status === "exist")
							cur_form[0].reset();
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
		// End of Deposit Module Functionalities

	});
});
