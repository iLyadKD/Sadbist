require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_st_subject_form").length > 0)
			custom_fn.load_validate("add_st_subject_form");

		if($("form.add_support_ticket_form").length > 0)
			custom_fn.load_validate("add_support_ticket_form");

		// Support Ticket Module Functionalities

		// Load support ticket subjects
		if($("ul.manage_st_subjects").length > 0)
		{
			url = base_url+current_controller+"/subjects_html"+default_ext;
			$.ajax(
			{
				url: url,
				method: "GET",
				dataType: "HTML",
				processData: false,
				contentType:false,
				beforeSend: function()
				{
					//show popup
					custom_fn.show_loading("Subjects are being loaded..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					$("ul.manage_st_subjects").html(response);
					// Remove any id stored by PHP from attribute list
					$("ul.manage_st_subjects [hyperlink]").each(function()
					{
						var href = $(this).attr("hyperlink");
						$(this).data("href", href);
						$(this).removeAttr("hyperlink");
					});

					// Remove any id stored by PHP from attribute list
					$("ul.manage_st_subjects [hypername]").each(function()
					{
						var href = $(this).attr("hypername");
						$(this).data("subject", href);
						$(this).removeAttr("hypername");
					});
				},
				error: function(response)
				{
					custom_fn.hide_loading();
				}
			});
		}

		//add support ticket subject
		$(document).on("submit", "form.add_st_subject_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/add_subject"+default_ext;
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
						custom_fn.show_loading("Support ticket subject is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						cur_form[0].reset();
						if(response.status === "true")
						{
							if($("ul.manage_st_subjects li:eq(0)").hasClass("no_sts_data"))
								$("ul.manage_st_subjects li:eq(0)").detach();
							$("ul.manage_st_subjects").append(response.data);
							
							// Remove any id stored by PHP from attribute list
							$("ul.manage_st_subjects [hyperlink]").each(function()
							{
								var href = $(this).attr("hyperlink");
								$(this).data("href", href);
								$(this).removeAttr("hyperlink");
							});
							// Remove any id stored by PHP from attribute list
							$("ul.manage_st_subjects [hypername]").each(function()
							{
								var href = $(this).attr("hypername");
								$(this).data("subject", href);
								$(this).removeAttr("hypername");
							});
						}
						custom_fn.hide_loading();
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
						cur_form.find("input[name]").focus();
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


		//popup window to confirm delete support ticket subject 
		$(document).on("click", "a.delete_st_subject", function()
		{
			var name = $(this).data("subject");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_st_subject_template");
			$(".delete_st_subject_template").find("h4.modal-title").text("Support Management - Delete Subject");
			$(".delete_st_subject_template").find("form").addClass("delete_st_subject_form").data("subject", $(this).data("href"));
			$(".delete_st_subject_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' Support Ticket will be completely lost if you continue.</li><li>Support History with '"+name+"' Subject name will also be deleted.</li></ul><br>Are you sure to delete '"+name+"' Support Ticket Subject?");
			$(".delete_st_subject_template").find("button[type='submit']").html("Continue");
			$(".delete_st_subject_template").toggle();
		});

		// delete support page subject
		$(document).on("submit", "form.delete_st_subject_form", function()
		{
			var url = base_url+current_controller+"/delete_subject"+default_ext;
			var subject = $(this).data("subject");
			var form_data = new FormData();
			form_data.append("st_subject", subject);
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
					custom_fn.show_loading("Support ticket subject is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						$("ul.manage_st_subjects").find("a.delete_st_subject").filter(function(i, el)
						{
							return $(this).data("href") === subject;
						}).closest("li").detach();
						if($("ul.manage_st_subjects li").length === 0)
							$("ul.manage_st_subjects").append("<li class='item no_sts_data'>No support ticket subjects are available. Please add some subjects.</li>");
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

		//add support ticket
		$(document).on("submit", "form.add_support_ticket_form", function(submit_event)
		{
			if($(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/tickets_add"+default_ext;
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
						custom_fn.show_loading("Support ticket is being created..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							cur_form[0].reset();
							cur_form.find("select").html("").trigger("change");

						}
						custom_fn.hide_loading();
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
						cur_form.find("input[name]").focus();
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

		// Diplay all inbox support tickets
		if($("table.manage_inbox_tickets").length > 0)
			$("table.manage_inbox_tickets").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/inbox_tickets_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_in_tkt = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_in_tkt.sl_no);
					$("td:eq(1)", nRow).html(jd_m_in_tkt.ticket_html);
					$("td:eq(2)", nRow).html(jd_m_in_tkt.last_updated);
					$("td:eq(3)", nRow).html(jd_m_in_tkt.email);
					$("td:eq(4)", nRow).html(jd_m_in_tkt.subject);
					$("td:eq(5)", nRow).html(jd_m_in_tkt.last_reply);
					$("td:eq(6)", nRow).html(jd_m_in_tkt.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_in_tkt.id).data("ticket", jd_m_in_tkt.ticket);
				}
			}).fnSetFilteringDelay(2000);



		// Diplay all sent support tickets
		if($("table.manage_sent_tickets").length > 0)
			$("table.manage_sent_tickets").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/sent_tickets_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_snt_tkt = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_snt_tkt.sl_no);
					$("td:eq(1)", nRow).html(jd_m_snt_tkt.ticket_html);
					$("td:eq(2)", nRow).html(jd_m_snt_tkt.last_updated);
					$("td:eq(3)", nRow).html(jd_m_snt_tkt.email);
					$("td:eq(4)", nRow).html(jd_m_snt_tkt.subject);
					$("td:eq(5)", nRow).html(jd_m_snt_tkt.last_reply);
					$("td:eq(6)", nRow).html(jd_m_snt_tkt.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_snt_tkt.id).data("ticket", jd_m_snt_tkt.ticket);
				}
			}).fnSetFilteringDelay(2000);


		// Diplay all closed support tickets
		if($("table.manage_closed_tickets").length > 0)
			$("table.manage_closed_tickets").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/closed_tickets_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_cls_tkt = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_cls_tkt.sl_no);
					$("td:eq(1)", nRow).html(jd_m_cls_tkt.ticket);
					$("td:eq(2)", nRow).html(jd_m_cls_tkt.last_updated);
					$("td:eq(3)", nRow).html(jd_m_cls_tkt.email);
					$("td:eq(4)", nRow).html(jd_m_cls_tkt.subject);
					$("td:eq(5)", nRow).html(jd_m_cls_tkt.last_reply);
					$("td:eq(6)", nRow).html(jd_m_cls_tkt.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_cls_tkt.id).data("ticket", jd_m_cls_tkt.ticket);
				}
			}).fnSetFilteringDelay(2000);

		//load ticket history
		if($(".chat_window").length > 0)
		{
			var ticket_id = $(".chat_window").data("href");
			var form_data = new FormData();
			form_data.append("ticket", ticket_id);
			var url = base_url+current_controller+"/support_ticket_history"+default_ext;
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
					custom_fn.show_loading("Support ticket details is being loaded..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					$(".chat_window .panel").append(response.result);
					$(".chat_window").data("page", response.page);
					// Remove any id stored by PHP from attribute list
					$(document).find("form.reply_support_ticket[hyperlink]").each(function()
					{
						var href = $(this).attr("hyperlink");
						$(this).data("href", href);
						$(this).removeAttr("hyperlink");
						custom_fn.load_validate("reply_support_ticket");
					});
					$(".chat_window .panel-body").stop().animate({scrollTop: $(document).find("div.panel-body.msg_container_base")[0].scrollHeight}, 800);
					custom_fn.hide_loading();
				},
				error: function(response)
				{
					custom_fn.hide_loading();
				}
			});

		}

		//load older ticket history
		$(document).on("click", ".chat_window .load_more_st_history", function(e)
		{
			var ticket_id = $(".chat_window").data("href");
			var dynamic_add = $(".chat_window").data("new_ticket") !== undefined ? $(".chat_window").data("new_ticket") : 0;
			var load_page = $(".chat_window").data("page") !== undefined ? $(".chat_window").data("page") : 0;
			var form_data = new FormData();
			form_data.append("ticket", ticket_id);
			form_data.append("ticket_inserted", dynamic_add);
			form_data.append("ticket_page", load_page);
			var url = base_url+current_controller+"/support_ticket_history"+default_ext;
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
					custom_fn.show_loading("Support ticket details is being loaded..", "it will take a couple of seconds");
					$(".chat_window .panel-body > a.load_more_st_history").detach();
				},
				success: function(response)
				{
					$(".chat_window .panel-body").prepend(response.result);
					$(".chat_window").data("page", response.page);
					custom_fn.hide_loading();
				},
				error: function(response)
				{
					custom_fn.hide_loading();
				}
			});

		});


		//reply support ticket
		$(document).on("submit", "form.reply_support_ticket", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("ticket_dtls", $(this).data("href"));
				var url = base_url+current_controller+"/tickets_view"+default_ext;
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
						custom_fn.show_loading("Support ticket reply is being sent..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							cur_form[0].reset();
							$(".chat_window .panel-body").append(response.result);
							$(".chat_window .panel-body").stop().animate({scrollTop: $(document).find("div.panel-body.msg_container_base")[0].scrollHeight}, 800);
							var dynamic_add = $(".chat_window").data("new_ticket") !== undefined ? $(".chat_window").data("new_ticket") : 0;
							$(".chat_window").data("new_ticket", (dynamic_add + 1));

						}
						custom_fn.hide_loading();
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
						cur_form.find("input[name]").focus();
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

		//popup window to confirm close support ticket 
		$(document).on("click", "a.close_support_ticket", function()
		{
			var ticket = $(this).data("ticket");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("close_ticket_template");
			$(".close_ticket_template").find("h4.modal-title").text("Support Management - Close Ticket");
			$(".close_ticket_template").find("form").addClass("close_support_ticket_form").data("ticket", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Once it is been closed, Cannot submitend anymore replies to '"+ticket+"' Support ticket.</li>";
			form_data += "<li>'"+ticket+"' Support ticket cannot be reopened again.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to close '"+ticket+"' Support ticket?";
			$(".close_ticket_template").find("div.modal-body").html(form_data);
			$(".close_ticket_template").find("button[type='submit']").html("Continue");
			$(".close_ticket_template").toggle();
		});

		// confirm close support ticket
		$(document).on("submit", "form.close_support_ticket_form", function()
		{
			var url = base_url+current_controller+"/close_ticket"+default_ext;
			var ticket = $(this).data("ticket");
			var form_data = new FormData();
			form_data.append("ticket", ticket);
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
					custom_fn.show_loading("Support Ticket is being closed..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.close_support_ticket").filter(function(i, el)
										{
											return $(this).data("href") === ticket;
										}).closest("tr");
						var cur_row_html = cur_row.html();
						var cur_row_ticket = cur_row.find("a.close_support_ticket").data("ticket");
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
						if($("body").find("table.manage_closed_tickets").length > 0)
						{
							index = $("table.manage_closed_tickets tbody").find("tr").length;
							sl_no = index + 1;
							if(index === 1 && $("table.manage_closed_tickets tbody").find(".dataTables_empty").length > 0)
							{
								index = 0;
								sl_no = 1;
								$("table.manage_closed_tickets tbody").find("tr").detach();
							}
							$("table.manage_closed_tickets tbody").append("<tr>"+cur_row_html+"</tr>");
							$("td:eq(0)", "table.manage_closed_tickets tbody tr:eq("+index+")").html(sl_no);
							$("table.manage_closed_tickets tbody tr:eq("+index+") a[href='javascript:void(0);']").data("href", ticket).data("ticket", cur_row_ticket);
							var new_row = $("table.manage_closed_tickets tbody tr:eq("+index+")");
							new_row.find("td:eq(1)").html(cur_row_ticket);
							new_row.find("td:eq(6) a.close_support_ticket").addClass("delete_support_ticket").removeClass("close_support_ticket").find("i").addClass("icon-remove").removeClass("icon-off");
						}
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

		//popup window to confirm delete support ticket 
		$(document).on("click", "a.delete_support_ticket", function()
		{
			var name = $(this).data("ticket");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_ticket_template");
			$(".delete_ticket_template").find("h4.modal-title").text("Support Management - Delete Ticket");
			$(".delete_ticket_template").find("form").addClass("delete_support_ticket_form").data("ticket", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>'"+name+"' Support ticket details will no longer available if you continue.</li>";
			form_data += "<li>'"+name+"' Support ticket cannot be accessed again.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+name+"' Support ticket?";
			$(".delete_ticket_template").find("div.modal-body").html(form_data);
			$(".delete_ticket_template").find("button[type='submit']").html("Continue");
			$(".delete_ticket_template").toggle();
		});

		// confirm delete support ticket
		$(document).on("submit", "form.delete_support_ticket_form", function()
		{
			var url = base_url+current_controller+"/delete_ticket"+default_ext;
			var ticket = $(this).data("ticket");
			var form_data = new FormData();
			form_data.append("ticket", ticket);
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
					custom_fn.show_loading("Support Ticket is being removed..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_support_ticket").filter(function(i, el)
										{
											return $(this).data("href") === ticket;
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
		// End of Support Ticket Module Functionalities

	});
});
