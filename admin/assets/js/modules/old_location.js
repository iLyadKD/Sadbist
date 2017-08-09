require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_country_form").length > 0)
			custom_fn.load_validate("add_country_form");
		if($("form.update_country_form").length > 0)
			custom_fn.load_validate("update_country_form");

		if($("form.add_region_form").length > 0)
			custom_fn.load_validate("add_region_form");
		if($("form.update_region_form").length > 0)
			custom_fn.load_validate("update_region_form");

		if($("form.add_city_form").length > 0)
			custom_fn.load_validate("add_city_form");
		if($("form.update_city_form").length > 0)
			custom_fn.load_validate("update_city_form");

		if($("form.add_airport_form").length > 0)
			custom_fn.load_validate("add_airport_form");
		if($("form.update_airport_form").length > 0)
			custom_fn.load_validate("update_airport_form");

		if($("form.add_airline_form").length > 0)
			custom_fn.load_validate("add_airline_form");
		if($("form.update_airline_form").length > 0)
			custom_fn.load_validate("update_airline_form");

		// Location Module functionalities

		// Diplay all Countries
		if($("table.manage_countries").length > 0)
			$("table.manage_countries").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/countries_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_cntrys = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_cntrys.sl_no);
					$("td:eq(1)", nRow).html(jd_m_cntrys.iso_2);
					$("td:eq(2)", nRow).html(jd_m_cntrys.name);
					$("td:eq(3)", nRow).html(jd_m_cntrys.iso_3);
					$("td:eq(4)", nRow).html(jd_m_cntrys.iso_num);
					$("td:eq(5)", nRow).html(jd_m_cntrys.status_html);
					$("td:eq(6)", nRow).html(jd_m_cntrys.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_cntrys.id).data("country", jd_m_cntrys.name);
				}
			}).fnSetFilteringDelay(2000);

		//submit new country
		$(document).on("submit", "form.add_country_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/countries_add"+default_ext;
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
						custom_fn.show_loading("Country is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
							cur_form[0].reset();
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

		// Change country visible status
		$(document).on("click", "input[type='checkbox'].country_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("country", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/country_status"+default_ext;
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
						custom_fn.show_loading("Country is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Country is being activated..", "it will take a couple of seconds");
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

		//update country
		$(document).on("submit", "form.update_country_form", function(submit_event)
		{
			if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var country_name_en = cur_form.find("input[name='country_en']");
				var country_name_fa = cur_form.find("input[name='country_fa']");
				var country_id2 = cur_form.find("input[name='country_id2']");
				var country_id3 = cur_form.find("input[name='country_id3']");
				var country_id_num = cur_form.find("input[name='country_id_num']");
				var form_data = new FormData(cur_form[0]);
				form_data.append("country", cur_form.data("href"));
				form_data.append("country_en_old", country_name_en.data("href"));
				form_data.append("country_fa_old", country_name_fa.data("href"));
				form_data.append("country_id2_old", country_id2.data("href"));
				form_data.append("country_id3_old", country_id3.data("href"));
				form_data.append("country_id_num_old", country_id_num.data("href"));
				url = base_url+current_controller+"/countries_edit"+default_ext;
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
						custom_fn.show_loading("Country is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form.data("href", response.new_data["id"]);
							country_name_en.data("href", response.new_data["country_en"]);
							country_name_fa.data("href", response.new_data["country_fa"]);
							country_id2.data("href", response.new_data["id_2"]);
							country_id3.data("href", response.new_data["id_3"]);
							country_id_num.data("href", response.new_data["id_num"]);
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

		//popup window to confirm delete country 
		$(document).on("click", "a.delete_country", function()
		{
			var country_name = $(this).data("country");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_country_template");
			$(".delete_country_template").find("h4.modal-title").text("Location Management - Delete Country");
			$(".delete_country_template").find("form").addClass("delete_country_form").data("country", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+country_name+"' Country will be completely lost if you continue.</li>";
			form_data += "<li>regions/states and cities of '"+country_name+"' country will also be deleted.</li>";
			form_data += "</ul><br>Are you sure to delete '"+country_name+"' country?";
			$(".delete_country_template").find("div.modal-body").html(form_data);
			$(".delete_country_template").find("button[type='submit']").html("Continue");
			$(".delete_country_template").toggle();
		});

		// delete country
		$(document).on("submit", "form.delete_country_form", function()
		{
			var url = base_url+current_controller+"/delete_country"+default_ext;
			var country = $(this).data("country");
			var form_data = new FormData();
			form_data.append("country", country);
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
					custom_fn.show_loading("Country is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_country").filter(function(i, el)
										{
											return $(this).data("href") === country;
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

		
		// Diplay all Regions/States
		if($("table.manage_states").length > 0)
			$("table.manage_states").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/regions_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4, 5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_states = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_states.sl_no);
					$("td:eq(1)", nRow).html(jd_m_states.country_name);
					$("td:eq(2)", nRow).html(jd_m_states.region_name);
					$("td:eq(3)", nRow).html(jd_m_states.region_name_fa);
					$("td:eq(4)", nRow).html(jd_m_states.status_html);
					$("td:eq(5)", nRow).html(jd_m_states.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_states.id).data("country", jd_m_states.country).data("region", jd_m_states.region_name);
				}
			}).fnSetFilteringDelay(2000);

		//submit new region/state
		$(document).on("submit", "form.add_region_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/regions_add"+default_ext;
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
						custom_fn.show_loading("Region/State is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							$("select").html("").trigger("change");
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

		// Change state/region visible status
		$(document).on("click", "input[type='checkbox'].region_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("region", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/region_status"+default_ext;
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
						custom_fn.show_loading("Region/State is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Region/State is being activated..", "it will take a couple of seconds");
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

		//update state/region
		$(document).on("submit", "form.update_region_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var country_select = cur_form.find("select.set_country");
				var region_select = cur_form.find("select.unassigned_regions");
				var country = cur_form.find("select[name='country']");
				var region = cur_form.find("select[name='region']");
				var region_name_en = cur_form.find("input[name='region_name_en']");
				var region_name_fa = cur_form.find("input[name='region_name_fa']");
				form_data.append("region_id", cur_form.data("href"));
				form_data.append("country_old", country.data("href"));
				form_data.append("region_old", region.data("href"));
				form_data.append("region_name_en_old", region_name_en.data("href"));
				form_data.append("region_name_fa_old", region_name_fa.data("href"));
				url = base_url+current_controller+"/regions_edit"+default_ext;
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
						custom_fn.show_loading("Region/State is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form.data("href", response.new_data["id"]);
							country_select.data("href", response.new_data["country"]);
							region_select.data("href", response.new_data["region"]);
							region_name_en.data("href", response.new_data["region_name_en"]);
							region_name_fa.data("href", response.new_data["region_name_fa"]);
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

		//popup window to confirm delete region/state 
		$(document).on("click", "a.delete_region", function()
		{
			var name = $(this).data("region");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_region_template");
			$(".delete_region_template").find("h4.modal-title").text("Location Management - Delete Region/State");
			$(".delete_region_template").find("form").addClass("delete_region_form").data("region", $(this).data("href"));
			$(".delete_region_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' Region/State will be completely lost if you continue.</li><li>Cities of '"+name+"' State/Region will also be deleted.</li></ul><br>Are you sure to delete '"+name+"' state/region?");
			$(".delete_region_template").find("button[type='submit']").html("Continue");
			$(".delete_region_template").toggle();
		});

		// delete region/state
		$(document).on("submit", "form.delete_region_form", function()
		{
			var url = base_url+current_controller+"/delete_region"+default_ext;
			var region = $(this).data("region");
			var form_data = new FormData();
			form_data.append("region", region);
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
					custom_fn.show_loading("Region/State is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_region").filter(function(i, el)
										{
											return $(this).data("href") === region;
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


		// Diplay all Cities
		if($("table.manage_cities").length > 0)
			$("table.manage_cities").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/cities_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4, 5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_cities = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_cities.sl_no);
					$("td:eq(1)", nRow).html(jd_m_cities.country_name);
					$("td:eq(2)", nRow).html(jd_m_cities.region_name);
					$("td:eq(3)", nRow).html(jd_m_cities.city_name);
					$("td:eq(4)", nRow).html(jd_m_cities.status_html);
					$("td:eq(5)", nRow).html(jd_m_cities.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_cities.id).data("city", jd_m_cities.city_name);
				}
			}).fnSetFilteringDelay(2000);

		//submit new city
		$(document).on("submit", "form.add_city_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/cities_add"+default_ext;
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
						custom_fn.show_loading("City is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							$("select").html("").trigger("change");
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

		// Change city visible status	
		$(document).on("click", "input[type='checkbox'].city_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("city", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/city_status"+default_ext;
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
						custom_fn.show_loading("City is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("City is being activated..", "it will take a couple of seconds");
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

		//update city
		$(document).on("submit", "form.update_city_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var country_select = cur_form.find("select.set_country");
				var region_select = cur_form.find("select.set_state");
				var country = cur_form.find("select[name='country']");
				var country_name = cur_form.find("input[name='country_name']");
				var region = cur_form.find("select[name='region']");
				var region_name = cur_form.find("input[name='state_name']");
				var city_en = cur_form.find("input[name='city_en']");
				var city_fa = cur_form.find("input[name='city_fa']");
				form_data.append("city_id", cur_form.data("href"));
				form_data.append("country_old", country.data("href"));
				form_data.append("region_old", region.data("href"));
				form_data.append("city_en_old", city_en.data("href"));
				form_data.append("city_fa_old", city_fa.data("href"));
				form_data.append("state_name_old", region_name.data("href"));
				form_data.append("country_name_old", country_name.data("href"));
				url = base_url+current_controller+"/cities_edit"+default_ext;
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
						custom_fn.show_loading("City is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							country_select.data("href", response.new_data["country"]);
							country_name.data("href", response.new_data["country_name"]);
							region_select.data("href", response.new_data["region"]);
							region_name.data("href", response.new_data["region_name"]);
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

		//popup window to confirm delete City
		$(document).on("click", "a.delete_city", function()
		{
			var name = $(this).data("city");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_city_template");
			$(".delete_city_template").find("h4.modal-title").text("Location Management - Delete City");
			$(".delete_city_template").find("form").addClass("delete_city_form").data("city", $(this).data("href"));
			$(".delete_city_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' City will be completely lost if you continue.</li><li>'"+name+"' City details will not be available.</li></ul><br>Are you sure to delete '"+name+"' city?");
			$(".delete_city_template").find("button[type='submit']").html("Continue");
			$(".delete_city_template").toggle();
		});

		// delete city
		$(document).on("submit", "form.delete_city_form", function()
		{
			var url = base_url+current_controller+"/delete_city"+default_ext;
			var city = $(this).data("city");
			var form_data = new FormData();
			form_data.append("city", city);
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
					custom_fn.show_loading("City is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_city").filter(function(i, el)
										{
											return $(this).data("href") === city;
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


		// Diplay all Airports
		if($("table.manage_airports").length > 0)
			$("table.manage_airports").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/airports_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6, 7]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_airports = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_airports.sl_no);
					$("td:eq(1)", nRow).html(jd_m_airports.airport_code);
					$("td:eq(2)", nRow).html(jd_m_airports.airport);
					$("td:eq(3)", nRow).html(jd_m_airports.city_code);
					$("td:eq(4)", nRow).html(jd_m_airports.city);
					$("td:eq(5)", nRow).html(jd_m_airports.country);
					$("td:eq(6)", nRow).html(jd_m_airports.status_html);
					$("td:eq(7)", nRow).html(jd_m_airports.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_airports.id).data("airport", jd_m_airports.airport);
				}
			}).fnSetFilteringDelay(2000);

		//submit new airport
		$(document).on("submit", "form.add_airport_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/airports_add"+default_ext;
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
						custom_fn.show_loading("Airport is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
							$("select").html("").trigger("change");
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

		// Change airport visible status	
		$(document).on("click", "input[type='checkbox'].airport_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("airport", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/airport_status"+default_ext;
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
						custom_fn.show_loading("Airport is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Airport is being activated..", "it will take a couple of seconds");
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

		//update airport
		$(document).on("submit", "form.update_airport_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var country = cur_form.find("input[name='country_old']");
				var city = cur_form.find("input[name='city_code']");
				var airport = cur_form.find("input[name='airport_code']");
				form_data.append("airport_id", cur_form.data("href"));
				form_data.append("airport_code_old", airport.data("href"));
				form_data.append("city_code_old", city.data("href"));
				form_data.append("country_old", country.data("href"));
				url = base_url+current_controller+"/airports_edit"+default_ext;
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
						custom_fn.show_loading("Airport is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							airport.data("href", response.new_data["airport"]);
							city.data("href", response.new_data["city"]);
							country.data("href", response.new_data["country"]);
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

		//popup window to confirm delete Airport
		$(document).on("click", "a.delete_airport", function()
		{
			var name = $(this).data("airport");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_airport_template");
			$(".delete_airport_template").find("h4.modal-title").text("Location Management - Delete Airport");
			$(".delete_airport_template").find("form").addClass("delete_airport_form").data("airport", $(this).data("href"));
			$(".delete_airport_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' Airport will be completely lost if you continue.</li><li>'"+name+"' Airport details will not be available.</li></ul><br>Are you sure to delete '"+name+"' airport?");
			$(".delete_airport_template").find("button[type='submit']").html("Continue");
			$(".delete_airport_template").toggle();
		});

		// delete city
		$(document).on("submit", "form.delete_airport_form", function()
		{
			var url = base_url+current_controller+"/delete_airport"+default_ext;
			var airport = $(this).data("airport");
			var form_data = new FormData();
			form_data.append("airport", airport);
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
					custom_fn.show_loading("Airport is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_airport").filter(function(i, el)
										{
											return $(this).data("href") === airport;
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



		// Diplay all Airlines
		if($("table.manage_airlines").length > 0)
			$("table.manage_airlines").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/airlines_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_airlines = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_airlines.sl_no);
					$("td:eq(1)", nRow).html(jd_m_airlines.airline_code);
					$("td:eq(2)", nRow).html(jd_m_airlines.airline_name);
					$("td:eq(3)", nRow).html(jd_m_airlines.status_html);
					$("td:eq(4)", nRow).html(jd_m_airlines.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_airlines.id).data("airport", jd_m_airlines.airline_name);
				}
			}).fnSetFilteringDelay(2000);

		//submit new airline
		$(document).on("submit", "form.add_airline_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				url = base_url+current_controller+"/airlines_add"+default_ext;
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
						custom_fn.show_loading("Airline is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
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

		// Change airline visible status	
		$(document).on("click", "input[type='checkbox'].airline_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("airline", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/airline_status"+default_ext;
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
						custom_fn.show_loading("Airline is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Airline is being activated..", "it will take a couple of seconds");
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

		//update airline
		$(document).on("submit", "form.update_airline_form", function(submit_event)
		{
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var airline = cur_form.find("input[name='airline_code']");
				form_data.append("airline_id", cur_form.data("href"));
				form_data.append("airline_code_old", airline.data("href"));
				url = base_url+current_controller+"/airlines_edit"+default_ext;
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
						custom_fn.show_loading("Airline is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						custom_fn.hide_loading();
						if(response.status === "true")
							airline.data("href", response.new_data["airline"]);
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

		//popup window to confirm delete Airline
		$(document).on("click", "a.delete_airline", function()
		{
			var name = $(this).data("airline");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_airline_template");
			$(".delete_airline_template").find("h4.modal-title").text("Location Management - Delete Airline");
			$(".delete_airline_template").find("form").addClass("delete_airline_form").data("airline", $(this).data("href"));
			$(".delete_airline_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' Airline will be completely lost if you continue.</li><li>'"+name+"' Airline details will not be available.</li></ul><br>Are you sure to delete '"+name+"' airline?");
			$(".delete_airline_template").find("button[type='submit']").html("Continue");
			$(".delete_airline_template").toggle();
		});

		// delete city
		$(document).on("submit", "form.delete_airline_form", function()
		{
			var url = base_url+current_controller+"/delete_airline"+default_ext;
			var airline = $(this).data("airline");
			var form_data = new FormData();
			form_data.append("airline", airline);
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
					custom_fn.show_loading("Airline is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_airline").filter(function(i, el)
										{
											return $(this).data("href") === airport;
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
		// End of Location Module Functionalities

	});
});
