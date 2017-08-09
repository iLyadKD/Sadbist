require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
		var upload_url = $("head").data("upload-url");

		// XML Logs Module Functionalities

		// Display all XML Logs
		if($("table.manage_xml_logs").length > 0)
			$("table.manage_xml_logs").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_logs_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 4, 5, 6, 7]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_x_l = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_x_l.sl_no);
					$("td:eq(1)", nRow).html(jd_m_x_l.tranx_session);
					$("td:eq(2)", nRow).html(jd_m_x_l.api_name);
					$("td:eq(3)", nRow).html(jd_m_x_l.ip_addr);
					$("td:eq(4)", nRow).html(jd_m_x_l.xml_type);
					$("td:eq(5)", nRow).html(jd_m_x_l.response_status);
					$("td:eq(6)", nRow).html(jd_m_x_l.logged_time);
					$("td:eq(7)", nRow).html(jd_m_x_l.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_x_l.id).data("request", jd_m_x_l.request).data("response", jd_m_x_l.response).data("log_name", jd_m_x_l.tranx_session+" ("+jd_m_x_l.xml_type+")");
				}
			}).fnSetFilteringDelay(2000);

		//popup window to confirm delete xml log
		$(document).on("click", "a.delete_log", function()
		{
			var log_name = $(this).data("log_name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_log_template");
			$(".delete_log_template").find("h4.modal-title").text("Logs Management - Delete Log");
			$(".delete_log_template").find("form").addClass("delete_log_form").data("log", $(this).data("href")).data("request", $(this).data("request")).data("response", $(this).data("response"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+log_name+"' Log will be completely lost if you continue.</li>";
			form_data += "<li>'"+log_name+"' request and response will also be removed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+log_name+"' log details?";
			$(".delete_log_template").find("div.modal-body").html(form_data);
			var btn_val = $(".delete_log_template").find("button[type='submit']");
			btn_val.html("Delete Log only");
			btn_val.clone().insertAfter(btn_val).html("Delete with Response").addClass("with_files");
			$(".delete_log_template").toggle();
		});

		$(document).on("click", "button.with_files", function()
		{
			$(this).parents("form").addClass("with_files");
		});

		// delete xml log
		$(document).on("submit", "form.delete_log_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var log = $(this).data("log");
			var with_files = false;
			if($(this).hasClass("with_files"))
				with_files = true;
			var request_xml = $(this).data("request");
			var response_xml = $(this).data("response");
			var form_data = new FormData();
			form_data.append("log", log);
			form_data.append("with_files", with_files);
			form_data.append("request_xml", request_xml);
			form_data.append("response_xml", response_xml);
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
					custom_fn.show_loading("XML Log is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_log").filter(function(i, el)
										{
											return $(this).data("href") === log;
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

		$(document).on("click", "a.view_request_log, a.view_response_log", function()
		{
			var file = $(this).data("request");
			if($(this).hasClass("view_response_log"))
				file = $(this).data("response");
			window.open(upload_url+file, "_blank");
		});
		// End of XML Logs Module Functionalities

	});
});
