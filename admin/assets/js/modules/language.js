require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_lang_page_label_form").length > 0)
			custom_fn.load_validate("add_lang_page_label_form");
		if($("form.update_lang_page_label_form").length > 0)
			custom_fn.load_validate("update_lang_page_label_form");

		// Language Module Functionalities

		// Display all Page list	
		if($("table.manage_lang_pages").length > 0)
			$("table.manage_lang_pages").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_pages"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_lng_page = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_lng_page.sl_no);
					$("td:eq(1)", nRow).html(jd_m_lng_page.name);
					$("td:eq(2)", nRow).html(jd_m_lng_page.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_lng_page.id).data("name", jd_m_lng_page.name);
				}
			}).fnSetFilteringDelay(2000);

		// Popup window for adding language page
		$(document).on("click", ".add_lang_page", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("add_lang_page_template");
			$(".add_lang_page_template").find("h4.modal-title").text("Language Library - Add Page");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-5 required' for='lang_page'>Enter Page name</label>";
			form_data += "<div class='col-sm-7 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control' data-rule-required='true' title='Please Enter valid page name' data-rule-pattern='^([a-zA-Z]([a-zA-Z_])*)$' id='lang_page' name='lang_page' placeholder='Page name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			$(".add_lang_page_template").find("div.modal-body").html(form_data);
			$(".add_lang_page_template").find("button[type='submit']").html("Add");
			$(".add_lang_page_template").find("form").addClass("add_lang_page_form");
			$(".add_lang_page_template").toggle();
			custom_fn.load_validate("add_lang_page_form");
		});

		// add new language page
		$(document).on("submit", "form.add_lang_page_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/add_page"+default_ext;
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
						custom_fn.show_loading("Language Page is being created..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							index = $("table.manage_lang_pages tbody").find("tr").length;
							sl_no = index + 1;
							if(index === 1 && $("table.manage_lang_pages tbody").find(".dataTables_empty").length > 0)
							{
								index = 0;
								sl_no = 1;
								$("table.manage_lang_pages tbody").find("tr:eq(0)").detach();
							}
							$("table.manage_lang_pages tbody").append(response.new_row);
							$("td:eq(0)", "table.manage_lang_pages tbody tr:eq("+index+")").html(sl_no);
							$("td a[href='javascript:void(0);']", "table.manage_lang_pages tbody tr:eq("+index+")").data("href", response.new_data["id"]).data("name", response.new_data["name"]);
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

		// popup message to edit language page
		$(document).on("click", "a.edit_lang_page", function()
		{
			$("body").prepend(custom_fn.model_template);
			var page_name = $(this).data("name");
			$(".model_template").addClass("edit_lang_page_template");
			$(".edit_lang_page_template").find("h4.modal-title").text("Language Library - Edit Page");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-5 required' for='lang_page'>Enter Page name</label>";
			form_data += "<div class='col-sm-7 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' autofocus='true' value='"+page_name+"' class='form-control' data-rule-required='true' title='Please Enter valid page name' data-rule-pattern='^([a-zA-Z]([a-zA-Z_])*)$' id='lang_page' name='lang_page' placeholder='Page name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			$(".edit_lang_page_template").find("div.modal-body").html(form_data);
			$(".edit_lang_page_template").find("button[type='submit']").html("Update");
			$(".edit_lang_page_template").find("form").addClass("edit_lang_page_form").data("page", $(this).data("href"));
			$(".edit_lang_page_template").toggle();
			custom_fn.load_validate("edit_lang_page_form");
		});

		//update language page name
		$(document).on("submit", "form.edit_lang_page_form", function()
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				var cur_form = $(this)[0];
				var updated_name = $(this).find("input[name='lang_page']").val();
				var page = $(this).data("page");
				var url = base_url+current_controller+"/update_page"+default_ext;
				var form_data = new FormData(cur_form);
				form_data.append("page", page);
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
						custom_fn.show_loading("Language Page is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							var row = $("body").find("a.edit_lang_page").filter(function(i, el)
							{
								return $(this).data("href") === page;
							}).closest("tr");
							row.find("td:eq(1)").html(updated_name);
							row.find("td a[href='javascript:void(0);']").data("name", updated_name);
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

		// popup to confirm delete language page
		$(document).on("click", "a.delete_lang_page", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_lang_page_template");
			$(".delete_lang_page_template").find("h4.modal-title").text("Language Library - Delete Page");
			var form_data = "<ul>";
			form_data += "<li>\"Delete with labels\" delete page including labels under selected page</li>";
			form_data += "<li>\"Delete selected page\" only delete page and keep labels under it without label</li>";
			form_data += "</ul>";
			form_data += " Do you want to proceed?";
			$(".delete_lang_page_template").find("div.modal-body").html(form_data);
			$(".delete_lang_page_template").find("button[type='submit']").html("Delete Page").addClass("confirm_delete_only_lang_page");
			$(".delete_lang_page_template").find("form").addClass("delete_lang_page_form").data("page", $(this).data("href")).data("without_labels", true);
			$(".delete_lang_page_template").toggle();
		});

		//set to delete labels with page
		$(document).on("click", ".confirm_delete_lang_page_labels", function()
		{
			$(this).closest("form").data("without_labels", false);
		});

		//set to delete only page without labels
		$(document).on("click", ".confirm_delete_only_lang_page", function()
		{
			$(this).closest("form").data("without_labels", true);
		});

		$(document).on("submit", "form.delete_lang_page_form", function(event)
		{
			var cur_form = $(this);
			var without_labels = cur_form.data("without_labels");
			var page = $(this).data("page");
			var url = base_url+current_controller+"/delete_page"+default_ext;
			var form_data = new FormData();
			form_data.append("page", page);
			form_data.append("without_labels", without_labels);
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
					custom_fn.show_loading("Language Page is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_lang_page").filter(function(i, el)
							{
								return $(this).data("href") === page;
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

		// View all labels under selected page
		if($("table.manage_lang_page_labels").length > 0)
		{
			var page = $("table.manage_lang_page_labels").data("href");
			$("table.manage_lang_page_labels").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_labels"+default_ext+"?page="+page,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_lng_page_lbl = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_lng_page_lbl.sl_no);
					$("td:eq(1)", nRow).html(jd_m_lng_page_lbl.label);
					$("td:eq(2)", nRow).html(jd_m_lng_page_lbl.english);
					$("td:eq(3)", nRow).html(jd_m_lng_page_lbl.farsi);
					$("td:eq(4)", nRow).html(jd_m_lng_page_lbl.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_lng_page_lbl.id).data("name", jd_m_lng_page_lbl.label);
				}
			}).fnSetFilteringDelay(2000);
		}

		//add new label under selected page
		$(document).on("submit", "form.add_lang_page_label_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				var page = $(this).data("href");
				var cur_form = $(this);
				var url = base_url+current_controller+"/add_label"+default_ext;
				var form_data = new FormData(cur_form[0]);
				form_data.append("page", page);
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
						custom_fn.show_loading("Language Label is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form[0].reset();
						custom_fn.hide_loading();
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						cur_form.find("input").val("").select().focus();
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

		//edit label of selected page
		$(document).on("submit", "form.update_lang_page_label_form", function(event)
		{
			if($(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				var label = $(this).data("href");
				var cur_form = $(this);
				var url = base_url+current_controller+"/edit_label"+default_ext;
				var form_data = new FormData(cur_form[0]);
				form_data.append("label", label);
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
						custom_fn.show_loading("Language Label is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						cur_form.find("input").select().focus();
						custom_fn.hide_loading();
						custom_fn.set_auto_close(5000);
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

		// popup window for confirm delete on Language Page
		$(document).on("click", "a.delete_lang_page_label", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_lang_page_label_template");
			$(".delete_lang_page_label_template").find("h4.modal-title").text("Language Library - Delete Label");
			var form_data = "<ul>";
			form_data += "<li>Deleting label will not show any content in the frontend</li>";
			form_data += "<li>Make sure, you are not using this label anywhere</li>";
			form_data += "<li>Continue on your own risk.</li>";
			form_data += "</ul>";
			form_data += " Do you want to proceed?";
			$(".delete_lang_page_label_template").find("div.modal-body").html(form_data);
			$(".delete_lang_page_label_template").find("button[type='submit']").html("Delete");
			$(".delete_lang_page_label_template").find("form").addClass("delete_lang_page_label_form").data("page", $(this).data("href"));
			$(".delete_lang_page_label_template").toggle();
		});

		//confirm delete language label
		$(document).on("submit", "form.delete_lang_page_label_form", function(event)
		{
			var page = $(this).data("page");
			var url = base_url+current_controller+"/delete_label"+default_ext;
			var form_data = new FormData();
			form_data.append("page", page);
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
					custom_fn.show_loading("Language Label is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_lang_page_label").filter(function(i, el)
							{
								return $(this).data("href") === page;
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
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.hide_loading();
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
		// End of Language Library functionalities

	});
});
