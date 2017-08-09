require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		// Subscribers Module Fucnctionalities

		// Diplay all Subscribers
		if($("table.manage_subscribers").length > 0)
			$("table.manage_subscribers").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/subscribers_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_scrbrs = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_scrbrs.sl_no);
					$("td:eq(1)", nRow).html(jd_m_scrbrs.email);
					$("td:eq(2)", nRow).html(jd_m_scrbrs.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_scrbrs.id).data("email", jd_m_scrbrs.email);
					if(jd_m_scrbrs.status === "true")
						$("td a[class*='block_subscriber']", nRow).data("href", jd_m_scrbrs.id+"&0");
					else
						$("td a[class*='block_subscriber']", nRow).data("href", jd_m_scrbrs.id+"&1");
				}
			}).fnSetFilteringDelay(2000);

		// subcriber : send mail form
		$(document).on("submit", "form.subscriber_sendmail_form", function()
		{
			$(this).find("textarea.ckeditor").each(function()
			{
				var id = $(this).attr("id");
				var pc = CKEDITOR.instances[id].getData();
				if(pc === "")
				{
					$("<label class='error'>Please enter details</label>").insertBefore($(this));
					$(this).addClass("error").removeClass("valid");
				}
				else
				{
					if($(this).prev().hasClass("error"))
						$(this).prev().detach();
					$(this).removeClass("error").addClass("valid");
				}
			});
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				var url = base_url+current_controller+"/mail"+default_ext;
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("user", cur_form.data("href"));
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
						custom_fn.show_loading("Mail is being sent to subscriber..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							for(instance in CKEDITOR.instances)
							{
								CKEDITOR.instances[instance].updateElement();
								CKEDITOR.instances[instance].setData('');
							}
							cur_form.find("input[name]").focus();
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

		//popup window to confirm block subscriber
		$(document).on("click", "a.block_subscriber", function()
		{
			var email = $(this).data("email");
			var subscriber = $(this).data("href");
			var temp_var = subscriber.split("&");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("block_subscriber_template");
			$(".block_subscriber_template").find("form").addClass("block_subscriber_form").data("subscriber", $(this).data("href"));
			if(temp_var[1] === "0")
			{
				$(".block_subscriber_template").find("h4.modal-title").text("Subscriber Management - Unblock Subscriber");
				$(".block_subscriber_template").find("div.modal-body").html("<ul><li>'"+email+"' email is being unblocked.</li><li>'"+email+"' email able to receive any future updates.</li></ul><br>Are you sure to unblock '"+email+"' email?");
				$(".block_subscriber_template").find("button[type='submit']").html("Unblock");
			}
			else
			{
				$(".block_subscriber_template").find("h4.modal-title").text("Subscriber Management - Block Subscriber");
				$(".block_subscriber_template").find("div.modal-body").html("<ul><li>'"+email+"' email is being Blocked.</li><li>Blocked emails cannot receive any future updates.</li></ul><br>Are you sure to block '"+email+"' email?");
				$(".block_subscriber_template").find("button[type='submit']").html("Block");
			}
			$(".block_subscriber_template").toggle();
		});

		// block or unblock subscriber
		$(document).on("submit", "form.block_subscriber_form", function()
		{
			var subscriber = $(this).data("subscriber");
			var temp_var = subscriber.split("&");
			var url = base_url+current_controller+"/block"+default_ext;
			var form_data = new FormData();
			form_data.append("subscriber", temp_var[0]);
			form_data.append("status", temp_var[1]);
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
					if(temp_var[1] === "1")
						custom_fn.show_loading("Subscriber is being blocked from receiving updates..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Subscriber is being unblocked to start receiving updates..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_tag = $("body").find("a.block_subscriber").filter(function(i, el)
										{
											return $(this).data("href") === subscriber;
										});
						if(temp_var[1] === "1")
						{
							cur_tag.removeClass("btn-contrast").addClass("btn-warning");
							cur_tag.data("href", temp_var[0]+"&0");
							cur_tag.attr("title", "Unblock");
							cur_tag.tooltip('hide').attr('data-original-title', "Unblock").tooltip('fixTitle');
						}
						else
						{
							cur_tag.removeClass("btn-warning").addClass("btn-contrast");
							cur_tag.data("href", temp_var[0]+"&1");
							cur_tag.attr("title", "Block");
							cur_tag.tooltip('hide').attr('data-original-title', "Block").tooltip('fixTitle');
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

		//popup window to confirm block and remove subscriber
		$(document).on("click", "a.block_remove_subscriber", function()
		{
			var email = $(this).data("email");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("block_remove_subscriber_template");
			$(".block_remove_subscriber_template").find("form").addClass("block_remove_subscriber_form").data("subscriber", $(this).data("href"));
			$(".block_remove_subscriber_template").find("h4.modal-title").text("Subscriber Management - Block and Remove Subscriber");
			var form_data = "<ul>";
			form_data += "<li>'"+email+"' email is being blocked and removed.</li>";
			form_data += "<li>'"+email+"' email will not be able to re-subscribe again nor receive any future updates.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to block and remove '"+email+"' email?";
			form_data += "<br><br><label for='send_mail' class='inline-checkbox'><input type='checkbox' id='send_mail' name='send_mail' value='true' checked='checked'>Send unsubscribed email.</label>";
			$(".block_remove_subscriber_template").find("div.modal-body").html(form_data);
			$(".block_remove_subscriber_template").find("button[type='submit']").html("Block and Remove");
			$(".block_remove_subscriber_template").toggle();
		});

		// block or unblock subscriber
		$(document).on("submit", "form.block_remove_subscriber_form", function()
		{
			var subscriber = $(this).data("subscriber");
			var url = base_url+current_controller+"/remove"+default_ext;
			var form_data = new FormData($(this)[0]);
			form_data.append("subscriber", subscriber);
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
					custom_fn.show_loading("Subscriber is being blocked and removed from receiving any future updates..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.block_remove_subscriber").filter(function(i, el)
										{
											return $(this).data("href") === subscriber;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='3' class='dataTables_empty'>No data available in table</td></tr>");
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

		//popup window to confirm delete subscriber
		$(document).on("click", "a.delete_subscriber", function()
		{
			var email = $(this).data("email");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_subscriber_template");
			$(".delete_subscriber_template").find("form").addClass("delete_subscriber_form").data("subscriber", $(this).data("href"));
			$(".delete_subscriber_template").find("h4.modal-title").text("Subscriber Management - Delete Subscriber");
			var form_data = "<ul>";
			form_data += "<li>'"+email+"' email is being deleted.</li>";
			form_data += "<li>Re-subscribe is possible to '"+email+"' email.</li>";
			form_data += "<li>'"+email+"' email will not be able to receive any future updates.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+email+"' email?";
			form_data += "<br><br><label for='send_mail' class='inline-checkbox'><input type='checkbox' id='send_mail' name='send_mail' value='true' checked='checked'>Send unsubscribed email.</label>";
			$(".delete_subscriber_template").find("div.modal-body").html(form_data);
			$(".delete_subscriber_template").find("button[type='submit']").html("Continue");
			$(".delete_subscriber_template").toggle();
		});

		// block or unblock subscriber
		$(document).on("submit", "form.delete_subscriber_form", function()
		{
			var subscriber = $(this).data("subscriber");
			var url = base_url+current_controller+"/delete"+default_ext;
			var form_data = new FormData($(this)[0]);
			form_data.append("subscriber", subscriber);
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
					custom_fn.show_loading("Subscriber is being deleted from receiving any future updates..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_subscriber").filter(function(i, el)
										{
											return $(this).data("href") === subscriber;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='3' class='dataTables_empty'>No data available in table</td></tr>");
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
		// End of Subscriber Module Functionalities

	});
});
