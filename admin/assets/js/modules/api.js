require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		var select2_page_count = 10;

		if($("form.add_service_form").length > 0)
			custom_fn.load_validate("add_service_form");
		
		function set_service()
		{
			if($("select.set_services").length > 0)
			{
				$("select.set_services").each(function()
				{
					var sat = $(this).data("href") !== undefined ? $(this).data("href") : "";
					var url = base_url+"ajax/get_services"+default_ext;
					var this_var = "#"+$(this).attr("id");
					if(sat !== "")
					{
						$.ajax(
						{
							type: "GET",
							url: url + "?id=" + sat,
							dataType: "json"
						}).then(function (data)
						{
							var option = new Option(data.results[0].text, data.results[0].id, true, true);
							$(this_var).html(option).trigger("change");
						});
					}
					$(this_var).select2(
					{
						placeholder: {"id": "", "text": "Select Service"},
						width: "100%",
						minimumInputLength: 1,
						ajax:
						{
							url: url,
							dataType: "json",
							delay: 500,
							data: function(params)
							{
								return {
									search: params.term,
									page: params.page
								};
							},
							processResults: function(data, params)
							{
								params.page = params.page || 1;
								return {
									results: data.results,
									pagination:
									{
										more: (params.page * select2_page_count) < data.total
									}
								};
							},
							cache: true
						},
						escapeMarkup: function(option)
						{
							return option; 
						},
						templateResult: function(option)
						{
							if(option.loading)
								return "<li class=\"select2-results__option\" role=\"treeitem\" aria-disabled=\"true\">Searching…<i class=\"pull-right icon icon-spinner icon-pulse icon-fw\"></i></li>";
							else
								return option.label || option.text;
						},
						templateSelection: function(option)
						{
							return option.text || option.id;
						}
					});
				});
			}
		}

		// API Module functionalities
		
		// Load Services
		if($("ul.manage_services").length > 0)
		{
			url = base_url+current_controller+"/services_html"+default_ext;
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
					custom_fn.show_loading("Services are being loaded..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					$("ul.manage_services").html(response);
					// Remove any id stored by PHP from attribute list
					$("ul.manage_services [hyperlink]").each(function()
					{
						var href = $(this).attr("hyperlink");
						$(this).data("href", href);
						$(this).removeAttr("hyperlink");
					});

					// Remove any id stored by PHP from attribute list
					$("ul.manage_services [hypername]").each(function()
					{
						var href = $(this).attr("hypername");
						$(this).data("service", href);
						$(this).removeAttr("hypername");
					});
				},
				error: function(response)
				{
					custom_fn.hide_loading();
				}
			});
		}

		//add Service
		$(document).on("submit", "form.add_service_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/add_service"+default_ext;
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
						custom_fn.show_loading("Service is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						cur_form[0].reset();
						if(response.status === "true")
						{
							if($("ul.manage_services li:eq(0)").hasClass("no_services_data"))
								$("ul.manage_services li:eq(0)").detach();
							$("ul.manage_services").append(response.data);
							
							// Remove any id stored by PHP from attribute list
							$("ul.manage_services [hyperlink]").each(function()
							{
								var href = $(this).attr("hyperlink");
								$(this).data("href", href);
								$(this).removeAttr("hyperlink");
							});
							// Remove any id stored by PHP from attribute list
							$("ul.manage_services [hypername]").each(function()
							{
								var href = $(this).attr("hypername");
								$(this).data("service", href);
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


		//popup window to confirm delete Service
		$(document).on("click", "a.delete_service", function()
		{
			var name = $(this).data("service");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_service_template");
			$(".delete_service_template").find("h4.modal-title").text("API Management - Delete Service");
			$(".delete_service_template").find("form").addClass("delete_service_form").data("service", $(this).data("href"));
			$(".delete_service_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' Service will be completely lost if you continue.</li><li>API details associated with '"+name+"' Service will also be deleted.</li></ul><br>Are you sure to delete '"+name+"' Service?");
			$(".delete_service_template").find("button[type='submit']").html("Continue");
			$(".delete_service_template").toggle();
		});

		// delete support page subject
		$(document).on("submit", "form.delete_service_form", function()
		{
			var url = base_url+current_controller+"/delete_service"+default_ext;
			var service = $(this).data("service");
			var form_data = new FormData();
			form_data.append("service", service);
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
					custom_fn.show_loading("Service is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						$("ul.manage_services").find("a.delete_service").filter(function(i, el)
						{
							return $(this).data("href") === service;
						}).closest("li").detach();
						if($("ul.manage_services li").length === 0)
							$("ul.manage_services").append("<li class='item no_services_data'>No Services are available. Please add some Services.</li>");
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

		// Diplay all APIs
		if($("table.manage_apis").length > 0)
			$("table.manage_apis").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/api_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 3, 4, 5, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_api = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_api.sl_no);
					$("td:eq(1)", nRow).html(jd_m_api.service);
					$("td:eq(2)", nRow).html(jd_m_api.api_name);
					$("td:eq(3)", nRow).html(jd_m_api.test_credentials);
					$("td:eq(4)", nRow).html(jd_m_api.live_credentials);
					$("td:eq(5)", nRow).html(jd_m_api.status_html);
					$("td:eq(6)", nRow).html(jd_m_api.actions);
					$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_api.id).data("api_name", jd_m_api.api_name).data("category", jd_m_api.category).data("service", jd_m_api.service).data("test_user", jd_m_api.test_user).data("live_user", jd_m_api.live_user).data("test_pwd", jd_m_api.test_pwd).data("live_pwd", jd_m_api.live_pwd).data("test_url", jd_m_api.test_url).data("live_url", jd_m_api.live_url).data("test_extra", jd_m_api.test_extra).data("live_extra", jd_m_api.live_extra);
				}
			}).fnSetFilteringDelay(2000);


		// Add API popup
		$(document).on("click", "button.add_api", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("add_api_template");
			$(".add_api_template").find("h4.modal-title").text("API Management - Add API");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>Service</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<select tabindex='1' autofocus='true' class='select2 form-control set_services' name='service' id='service' data-rule-required='true' data-msg-required='Select Service'>";
			form_data += "</select>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>Credentials Type</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<select tabindex='2' class='select2 form-control api_category_types' style='width:100%;' name='category' id='category' data-rule-required='true'>";
			form_data += "<option value = '1'>Test Only</option>";
			form_data += "<option value = '2'>Live Only</option>";
			form_data += "<option value = '3'>Test and Live</option>";
			form_data += "</select>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>API Name</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='3' class='form-control' autocomplete='off' data-rule-required='true' name='api_name' placeholder='API Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group test_credentials_required'>";
			form_data += "<label class='control-label col-sm-3 required'>Test Credentials</label>";
			form_data += "<div class='col-sm-4 controls'>";
			form_data += "<input autocomplete='off' tabindex='4' class='form-control' autocomplete='off' data-rule-required='true' name='test_user' placeholder='Test Username' type='text'>";
			form_data += "</div>";
			form_data += "<div class='col-sm-5 controls'>";
			form_data += "<input autocomplete='off' tabindex='5' class='form-control' autocomplete='off' data-rule-required='true' name='test_pass' placeholder='Test Password' type='password'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group test_credentials_required'>";
			form_data += "<label class='control-label col-sm-3'>Test Extra Credentials</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='6' class='form-control valid' autocomplete='off' data-rule-required='false' name='test_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group live_credentials_required hide'>";
			form_data += "<label class='control-label col-sm-3 required'>Live Credentials</label>";
			form_data += "<div class='col-sm-4 controls'>";
			form_data += "<input autocomplete='off' tabindex='7' class='form-control' disabled='true' data-rule-required='true' name='live_user' placeholder='Live Username' type='text'>";
			form_data += "</div>";
			form_data += "<div class='col-sm-5 controls'>";
			form_data += "<input autocomplete='off' tabindex='8' class='form-control' disabled='true' data-rule-required='true' name='live_pass' placeholder='Live Password' type='password'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group live_credentials_required hide'>";
			form_data += "<label class='control-label col-sm-3'>Live Extra Credentials</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='9' class='form-control valid' disabled='true' data-rule-required='false' name='live_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>URL</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='10' class='form-control' data-rule-required='true' name='api_url' placeholder='URL' type='text'>";
			form_data += "<p class='help-block'><small class='text-muted'>Note: If Live and Test Url are different, Then format is <br/>&Prime;Live_URL (Space) Test_URL&Prime;<br/>Example : https://liveurl.com/api https://testurl.com/api</small></p>";
			form_data += "</div>";
			form_data += "</div>";

			$(".add_api_template").find("div.modal-body").html(form_data);
			$(".add_api_template").find("button[type='submit']").html("Add");
			$(".add_api_template").find("form").addClass("add_api_form");
			$("select.api_category_types").select2();
			custom_fn.load_validate("add_api_form");
			set_service();
			$(".add_api_template").toggle();
		});

		// select api category to add / update
		$(document).on("change", ".api_category_types", function()
		{
			var selected = $(this).val();
			$(".test_credentials_required").removeClass("hide").find("input").removeAttr("disabled");
			$(".live_credentials_required").removeClass("hide").find("input").removeAttr("disabled");
			if(parseInt(selected) === 2)
				$(".test_credentials_required").addClass("hide").find("input").prop("disabled", "true");
			else if(parseInt(selected) === 1)
				$(".live_credentials_required").addClass("hide").find("input").prop("disabled", "true");
		});

		// add new api
		$(document).on("submit", "form.add_api_form", function(submit_event)
		{
			if($(this).find("input[name]").length <= ($(this).find("input[name].valid").length + $(this).find("input[name]:disabled").length))
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/add"+default_ext;
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
						custom_fn.show_loading("API is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						index = $("table.manage_apis tbody").find("tr").length;
						sl_no = index + 1;
						if(response.status === "true")
						{
							if(index === 1 && $("table.manage_apis tbody").find(".dataTables_empty").length > 0)
							{
								index = 0;
								sl_no = 1;
								$("table.manage_apis tbody").find("tr").detach();
							}
							$("table.manage_apis tbody").append(response.new_data["new_row"]);
							$("table.manage_apis tbody tr:eq("+index+")").find("td:eq(0)").html(sl_no);
							$("table.manage_apis tbody tr:eq("+index+") a[href='javascript:void(0);']").data("href", response.new_data["api_id"]).data("api_name", response.new_data["api_name"]);
							$("table.manage_apis tbody tr:eq("+index+") a[class*='edit_api']").data("category", response.new_data["category"]).data("service", response.new_data["service"]).data("test_user", response.new_data["test_user"]).data("live_user", response.new_data["live_user"]).data("test_pwd", response.new_data["test_pwd"]).data("live_pwd", response.new_data["live_pwd"]).data("test_url", response.new_data["test_url"]).data("live_url", response.new_data["live_url"]).data("test_extra", response.new_data["test_extra"]).data("live_extra", response.new_data["live_extra"]);
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

		// Change API status	
		$(document).on("change", "select.api_status", function()
		{
			var cur_var = $(this);
			var api = $(this).data("api");
			var status = cur_var.val();
			if(cur_var.data("status") !== status)
			{
				var form_data = new FormData();
				form_data.append("api", api);
				form_data.append("status", status);
				url = base_url+current_controller+"/update_status"+default_ext;
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
							custom_fn.show_loading("API is being deactivated..", "it will take a couple of seconds");
						else
							custom_fn.show_loading("API is being activated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							if(response.msg_status !== "info")
								cur_var.prev().addClass("btn-success").removeClass("btn-danger").attr("title", "Active").attr('data-original-title', "Active").tooltip('fixTitle').find("i").attr("class", "icon-ok");
							else
								cur_var.prev().addClass("btn-danger").removeClass("btn-success").attr("title", "De-active").attr('data-original-title', "De-active").tooltip('fixTitle').find("i").attr("class", "icon-minus");
							cur_var.data("status", status);
						}
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
					},
					error: function(response)
					{
						cur_var.find("option [value='"+cur_var.data("status")+"']").prop("selected", "true");
						custom_fn.hide_loading();
						custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
						custom_fn.set_auto_close(5000);
					}
				});
			}
			else
				return false;
		});



		// Update API popup
		$(document).on("click", "a.edit_api", function()
		{
			var api = $(this).data("href");
			var category = $(this).data("category");
			var service = $(this).data("service");
			var api_name = $(this).data("api_name");
			var test_user = $(this).data("test_user");
			var test_pwd = $(this).data("test_pwd");
			var test_extra = $(this).data("test_extra");
			var test_url = $(this).data("test_url");
			var live_user = $(this).data("live_user");
			var live_pwd = $(this).data("live_pwd");
			var live_extra = $(this).data("live_extra");
			var live_url = $(this).data("live_url");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("edit_api_template");
			$(".edit_api_template").find("h4.modal-title").text("API Management - Update API");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>Service</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<span class='form-control'>"+service+"</span>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>Credentials Type</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<select tabindex='1' autofocus='true' style='width:100%;' class='select2 form-control api_category_types' name='category' data-rule-required='true'>";
			if(category === "1")
				form_data += "<option selected value = '1'>Test Only</option>";
			else
				form_data += "<option value = '1'>Test Only</option>";
			if(category === "2")
				form_data += "<option selected value = '2'>Live Only</option>";
			else
				form_data += "<option value = '2'>Live Only</option>";
			if(category === "3")
				form_data += "<option selected value = '3'>Test and Live</option>";
			else
				form_data += "<option value = '3'>Test and Live</option>";
			form_data += "</select>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>API Name</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='2' class='form-control' value='"+api_name+"' data-rule-required='true' name='api_name' placeholder='API Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			var tcr_hide = category === "2" ? "hide" : "";
			var tcr_disable = category === "2" ? "disabled='true'" : "";
			form_data += "<div class='form-group test_credentials_required "+tcr_hide+"'>";
			form_data += "<label class='control-label col-sm-3 required'>Test Credentials</label>";
			form_data += "<div class='col-sm-4 controls'>";
			form_data += "<input autocomplete='off' tabindex='3' class='form-control' value='"+test_user+"' "+tcr_disable+" data-rule-required='true' name='test_user' placeholder='Test Username' type='text'>";
			form_data += "</div>";
			form_data += "<div class='col-sm-5 controls'>";
			form_data += "<input autocomplete='off' tabindex='4' class='form-control' value='"+test_pwd+"' "+tcr_disable+" data-rule-required='true' name='test_pass' placeholder='Test Password' type='password'>";
			form_data += "</div>";
			form_data += "</div>";

			form_data += "<div class='form-group test_credentials_required "+tcr_hide+"'>";
			form_data += "<label class='control-label col-sm-3'>Test Extra Credentials</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='5' class='form-control valid' value='"+test_extra+"' "+tcr_disable+" data-rule-required='false' name='test_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
			form_data += "</div>";
			form_data += "</div>";

			var lcr_hide = category === "1" ? "hide" : "";
			var lcr_disable = category === "1" ? "disabled='true'" : "";
			form_data += "<div class='form-group live_credentials_required "+lcr_hide+"'>";
			form_data += "<label class='control-label col-sm-3 required'>Live Credentials</label>";
			form_data += "<div class='col-sm-4 controls'>";
			form_data += "<input autocomplete='off' tabindex='6' class='form-control' value='"+live_user+"' "+lcr_disable+" data-rule-required='true' name='live_user' placeholder='Live Username' type='text'>";
			form_data += "</div>";
			form_data += "<div class='col-sm-5 controls'>";
			form_data += "<input autocomplete='off' tabindex='7' class='form-control' value='"+live_pwd+"' "+lcr_disable+" data-rule-required='true' name='live_pass' placeholder='Live Password' type='password'>";
			form_data += "</div>";
			form_data += "</div>";


			form_data += "<div class='form-group live_credentials_required "+lcr_hide+"'>";
			form_data += "<label class='control-label col-sm-3'>Live Extra Credentials</label>";
			form_data += "<div class='col-sm-9 controls'>";
			form_data += "<input autocomplete='off' tabindex='8' class='form-control valid' value='"+live_extra+"' "+lcr_disable+" data-rule-required='false' name='live_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
			form_data += "</div>";
			form_data += "</div>";

			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-3 required'>URL</label>";
			form_data += "<div class='col-sm-9 controls'>";
			api_url = test_url !== live_url ? (live_url+" "+test_url) : live_url;
			form_data += "<input autocomplete='off' tabindex='9' class='form-control' value='"+api_url+"' data-rule-required='true' name='api_url' placeholder='URL' type='text'>";
			form_data += "<p class='help-block'><small class='text-muted'>Note: If Live and Test Url are different, Then format is <br/>&Prime;Live_URL (Space) Test_URL&Prime;<br/>Example : https://liveurl.com/api https://testurl.com/api</small></p>";
			form_data += "</div>";
			form_data += "</div>";

			$(".edit_api_template").find("div.modal-body").html(form_data);
			$(".edit_api_template").find("button[type='submit']").html("Update");
			$("select.api_category_types").select2();
			set_service();
			$(".edit_api_template").find("form").addClass("edit_api_form").data("api", api);
			custom_fn.load_validate("edit_api_form");
			$(".edit_api_template").toggle();
		});

		// submit update api
		$(document).on("submit", "form.edit_api_form", function(submit_event)
		{
			if($(this).find("input[name]").length <= ($(this).find("input[name].valid").length + $(this).find("input[name]:disabled").length))
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var api = $(this).data("api");
				var form_data = new FormData($(this)[0]);
				form_data.append("api", api);
				var url = base_url+current_controller+"/update"+default_ext;
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
						custom_fn.show_loading("API is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							var row =  $("body").find("a.edit_api").filter(function(i, el)
										{
											return $(this).data("href") === api;
										}).closest("tr");
							row.find("td:eq(2)").html(response.new_data["api_name"]);
							row.find("td:eq(3)").html(response.new_data["test_details"]);
							row.find("td:eq(4)").html(response.new_data["live_details"]);
							row.find("td a[href='javascript:void(0);']").data("api_name", response.new_data["api_name"]).data("category", response.new_data["category"]).data("test_user", response.new_data["test_user"]).data("live_user", response.new_data["live_user"]).data("test_pwd", response.new_data["test_pwd"]).data("live_pwd", response.new_data["live_pwd"]).data("test_url", response.new_data["test_url"]).data("live_url", response.new_data["live_url"]).data("test_url", response.new_data["test_url"]).data("live_url", response.new_data["live_url"]).data("test_extra", response.new_data["test_extra"]).data("live_extra", response.new_data["live_extra"]);
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

		//popup window to confirm delete api 
		$(document).on("click", "a.delete_api", function()
		{
			var api_name = $(this).data("api_name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_api_template");
			$(".delete_api_template").find("h4.modal-title").text("API Management - Delete API");
			$(".delete_api_template").find("form").addClass("delete_api_form").data("api", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+api_name+"' API will be completely lost if you continue.</li>";
			form_data += "<li>Result will not be able to fetch from '"+api_name+"' API.</li>";
			form_data += "</ul><br>Are you sure to delete '"+api_name+"' API?";
			$(".delete_api_template").find("div.modal-body").html(form_data);
			$(".delete_api_template").find("button[type='submit']").html("Continue");
			$(".delete_api_template").toggle();
		});

		// delete API
		$(document).on("submit", "form.delete_api_form", function()
		{
			var url = base_url+current_controller+"/delete"+default_ext;
			var api = $(this).data("api");
			var form_data = new FormData();
			form_data.append("api", api);
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
					custom_fn.show_loading("API is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_api").filter(function(i, el)
										{
											return $(this).data("href") === api;
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
		// End of API Module functionalities

	});
});
