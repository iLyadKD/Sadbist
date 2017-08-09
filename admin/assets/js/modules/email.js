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

		if($("form.add_email_template_form").length > 0)
			custom_fn.load_validate("add_email_template_form");
		if($("form.update_email_template_form").length > 0)
			custom_fn.load_validate("update_email_template_form");

		// Email Module Functionalities

		// Diplay all emails
		var manage_emails = null;
		if($("table.manage_emails").length > 0)
			manage_emails = $("table.manage_emails").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/email_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_eml_tmpl = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_eml_tmpl.sl_no);
					$("td:eq(1)", nRow).html(jd_m_eml_tmpl.name);
					$("td:eq(2)", nRow).html(jd_m_eml_tmpl.subject);
					$("td:eq(3)", nRow).html(jd_m_eml_tmpl.from);
					$("td:eq(4)", nRow).html(jd_m_eml_tmpl.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_eml_tmpl.id).data("name", jd_m_eml_tmpl.name).data("etemplate", jd_m_eml_tmpl.template);
				}
			}).fnSetFilteringDelay(2000);
		// if(manage_emails !== null)
		// 	setInterval(function()
		// 	{
		// 		manage_emails.fnDraw(false);
		// 	}, 30000);

		// Display content of selected email template
		$(document).on("click", "a.view_email_template", function()
		{
			var data = $(this).data("etemplate");  /*Get the id of the clicked row using data attribute.*/
			data = data.replace(/datasrc=/g, "src=");
			data = data.replace(/{%%BASE_URL%%}/g, base_url);
			data = data.replace(/{%%UPLOAD_URL%%}/g, upload_url);
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("view_email_template_template");
			$(".view_email_template_template").find("div.modal-dialog").css({"width" : "800px"});
			$(".view_email_template_template").find("div.modal-footer").detach();
			$(".view_email_template_template").find("div.modal-body").css({"width" : "100%", "overflow" : "auto", "height" : "500px"}).html(data);
			$(".view_email_template_template").toggle();  
		});

		// add email template
		$(document).on("submit", "form.add_email_template_form", function()
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
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				var url = base_url+current_controller+"/add"+default_ext;
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
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
						custom_fn.show_loading("Email template details being added..", "it will take a couple of seconds");
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

		//update email template
		$(document).on("submit", "form.update_email_template_form", function()
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
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				var url = base_url+current_controller+"/edit"+default_ext;
				var template = $(this).data("href");
				var form_data = new FormData($(this)[0]);
				form_data.append("template", template);
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
						custom_fn.show_loading("Email template details being updated..", "it will take a couple of seconds");
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

		//pop-up window to confirm message to delete email template
		$(document).on("click", "a.delete_email_template", function()
		{
			var template_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_email_template_template");
			$(".delete_email_template_template").find("h4.modal-title").text("Email Template - Delete Template");
			$(".delete_email_template_template").find("form").addClass("delete_email_template_form").data("template", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+template_name+"' email templete will be completely lost if you continue.</li>";
			form_data += "<li>You may face issues if '"+template_name+"' email template is used to send mails by other functions.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to completely delete '"+template_name+"' email template?";
			$(".delete_email_template_template").find("div.modal-body").html(form_data);
			$(".delete_email_template_template").find("button[type='submit']").html("Continue");
			$(".delete_email_template_template").toggle();
		});

		//confirmed delete email template
		$(document).on("submit", "form.delete_email_template_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var template = $(this).data("template");
			var form_data = new FormData();
			form_data.append("template", template);
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
					custom_fn.show_loading("Email template details being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_email_template").filter(function(i, el)
										{
											return $(this).data("href") === template;
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
		});
		// End of Email Module functionalities
	});
});
