require(["custom_defination", "async!http://maps.google.com/maps/api/js?key=AIzaSyAEjc4IC_8he4zTPM3Gz95Ekm4lOeXXh0w"], function(custom_fn, gmaps)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var asset_url = $("head").data("asset-url");
		var upload_url = $("head").data("upload-url");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
		var asset_url = $("head").data("asset-url");

		var contact_markers = [];
		var map = null;
		var create_contact_marker = null;

		if($("form.add_sp_type_form").length > 0)
			custom_fn.load_validate("add_sp_type_form");

		if($("form.add_static_page_form").length > 0)
			custom_fn.load_validate("add_static_page_form");
		if($("form.update_static_page_form").length > 0)
			custom_fn.load_validate("update_static_page_form");

		if($("form.add_contact_detail_form").length > 0)
			custom_fn.load_validate("add_contact_detail_form");
		if($("form.update_contact_detail_form").length > 0)
			custom_fn.load_validate("update_contact_detail_form");

		if($("form.update_about_us_form").length > 0)
			custom_fn.load_validate("update_about_us_form");

		// StaticPages Module Functionalities
		// Load static page types
		if($("ul.manage_sp_types").length > 0)
		{
			url = base_url+current_controller+"/page_types_html"+default_ext;
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
					custom_fn.show_loading("Page types are being loaded..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					$("ul.manage_sp_types").html(response);
					// Remove any id stored by PHP from attribute list
					$("ul.manage_sp_types [hyperlink]").each(function()
					{
						var href = $(this).attr("hyperlink");
						$(this).data("href", href);
						$(this).removeAttr("hyperlink");
					});

					// Remove any id stored by PHP from attribute list
					$("ul.manage_sp_types [hypername]").each(function()
					{
						var href = $(this).attr("hypername");
						$(this).data("sp_type", href);
						$(this).removeAttr("hypername");
					});
				},
				error: function(response)
				{
					custom_fn.hide_loading();
				}
			});
		}

		//add static page type
		$(document).on("submit", "form.add_sp_type_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/add_sp_type"+default_ext;
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
						custom_fn.show_loading("Static page type is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						cur_form[0].reset();
						if(response.status === "true")
						{
							if($("ul.manage_sp_types li:eq(0)").hasClass("no_spt_data"))
								$("ul.manage_sp_types li:eq(0)").detach();
							$("ul.manage_sp_types").append(response.data);
							
							// Remove any id stored by PHP from attribute list
							$("ul.manage_sp_types [hyperlink]").each(function()
							{
								var href = $(this).attr("hyperlink");
								$(this).data("href", href);
								$(this).removeAttr("hyperlink");
							});
							// Remove any id stored by PHP from attribute list
							$("ul.manage_sp_types [hypername]").each(function()
							{
								var href = $(this).attr("hypername");
								$(this).data("sp_type", href);
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


		//popup window to confirm delete static page type
		$(document).on("click", "a.delete_sp_type", function()
		{
			var sp_type = $(this).data("sp_type");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_sp_type_template");
			$(".delete_sp_type_template").find("h4.modal-title").text("Static Page Management - Delete Type");
			$(".delete_sp_type_template").find("form").addClass("delete_sp_type_form").data("sp_type", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+sp_type+"' Static page type  will be completely lost if you continue.</li>";
			form_data += "<li>Static pages under '"+sp_type+"' type name will also be deleted.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+sp_type+"' Static page type?";
			$(".delete_sp_type_template").find("div.modal-body").html(form_data);
			$(".delete_sp_type_template").find("button[type='submit']").html("Continue");
			$(".delete_sp_type_template").toggle();
		});

		// delete Static page type
		$(document).on("submit", "form.delete_sp_type_form", function()
		{
			var url = base_url+current_controller+"/delete_page_type"+default_ext;
			var sp_type = $(this).data("sp_type");
			var form_data = new FormData();
			form_data.append("sp_type", sp_type);
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
					custom_fn.show_loading("Static page type is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						$("ul.manage_sp_types").find("a.delete_sp_type").filter(function(i, el)
						{
							return $(this).data("href") === sp_type;
						}).closest("li").detach();
						if($("ul.manage_sp_types li").length === 0)
							$("ul.manage_sp_types").append("<li class='item no_spt_data'>No Static Page Types are available. Please add some types.</li>");
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

		// Display all Static Page list	
		if($("table.manage_static_pages").length > 0)
			$("table.manage_static_pages").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_pages"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_stc_pgs = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_stc_pgs.sl_no);
					$("td:eq(1)", nRow).html(jd_m_stc_pgs.title);
					$("td:eq(2)", nRow).html(jd_m_stc_pgs.url_html);
					$("td:eq(3)", nRow).html(jd_m_stc_pgs.status_html);
					$("td:eq(4)", nRow).html(jd_m_stc_pgs.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_stc_pgs.title).data("href", jd_m_stc_pgs.id);
				}
			}).fnSetFilteringDelay(2000);

		// Change status of Statis Pages
		$(document).on("click", "input[type='checkbox'].static_page_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("static_page", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/static_page_status"+default_ext;
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
						custom_fn.show_loading("Static Page is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Static Page is being activated..", "it will take a couple of seconds");
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

		// Add static page
		$(document).on("submit", "form.add_static_page_form", function(submit_event)
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
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
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
						custom_fn.show_loading("Static is being created..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							cur_form[0].reset();
							for(instance in CKEDITOR.instances)
							{
								CKEDITOR.instances[instance].updateElement();
								CKEDITOR.instances[instance].setData('');
							}
							$(".static_page_types").html("").trigger("change");
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


		// Update static page
		$(document).on("submit", "form.update_static_page_form", function(submit_event)
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
			if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("static_page", $(this).data("href"));
				form_data.append("current_slug", $(this).find(".current_slug").data("href"));
				url = base_url+current_controller+"/pages_edit"+default_ext;
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
						custom_fn.show_loading("Static is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form.find(".current_slug").data("href", response.slug)
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


		//pop-up window to confirm message to delete static page
		$(document).on("click", "a.delete_static_page", function()
		{
			var page_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_static_page_template");
			$(".delete_static_page_template").find("h4.modal-title").text("Static Page Management - Delete Static Page");
			$(".delete_static_page_template").find("button[type='submit']");
			$(".delete_static_page_template").find("form").addClass("delete_static_page_form").data("static_page", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+page_name+"' static page will be completely lost if you continue.</li>";
			form_data += "<li>You can deactivate '"+page_name+"' static page if you want to keep details.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to completely delete '"+page_name+"' static page?";
			$(".delete_static_page_template").find("div.modal-body").html(form_data);
			$(".delete_static_page_template").find("button[type='submit']").html("Continue");
			$(".delete_static_page_template").toggle();
		});

		// confirm deletion of static page
		$(document).on("submit", "form.delete_static_page_form", function()
		{
			var url = base_url+current_controller+"/delete_page"+default_ext;
			var static_page = $(this).data("static_page");
			var form_data = new FormData();
			form_data.append("static_page", static_page);
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
					custom_fn.show_loading("Static page is being removed..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_static_page").filter(function(i, el)
										{
											return $(this).data("href") === static_page;
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

		// Display contact locations markers
		if($(".contact_locations_map").length > 0)
		{
			var locations = $(".contact_locations_list").length > 0 ? $(".contact_locations_list").data("href") : "";
			temp_arr = locations.split(":::");
			var new_locations = [];
			for (var i = 0; i < temp_arr.length; i++)
			{
				var new_temp = [];
				new_temp = temp_arr[i].split(", ");
				if(new_temp.length <2)
					break;
				for (var j = 0; j < new_temp.length; j++)
					new_temp[j] = custom_fn.trim_char(new_temp[j], "'");
				new_locations.push(new_temp);
			}
			locations = new_locations;
			center_lat = 21;
			center_lng = 78;
			if (navigator.geolocation)
			{
				navigator.geolocation.getCurrentPosition(function(position)
					{
						center_lat = parseFloat(position.coords.latitude);
						center_lng = parseFloat(position.coords.longitude);
					}, function(error)
					{
						center_lat = 21;
						center_lng = 78;
					});
			}
			if(locations.length > 0)
			{
				center_lat = locations[0][1];
				center_lng = locations[0][2];
			}
			map = new google.maps.Map(document.getElementsByClassName("contact_locations_map")[0], {
							zoom: 5,
							maxZoom: 17,
							minZoom: 3,
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							center: new google.maps.LatLng(center_lat, center_lng),
							disableDefaultUI: true
						});
			var infowindow = new google.maps.InfoWindow();
			for (i = 0; i < locations.length; i++)
			{
				if(locations[i][3] !== "")
				{
					marker_icon = {
										url: upload_url+locations[i][3],
										scaledSize: new google.maps.Size(30, 30), // scaled size
										origin: new google.maps.Point(0,0), // origin
										anchor: new google.maps.Point(0, 0) // anchor
									};
					contact_markers[locations[i][0]] = new google.maps.Marker({
						position: new google.maps.LatLng(locations[i][1], locations[i][2]),
						map: map,
						icon: marker_icon,
						marker_name: locations[i][0],
						custom_icon: locations[i][5]
					});
				}
				else
				{
					marker_icon = custom_fn.marker_icon(locations[i][6]);
					contact_markers[locations[i][0]] = new google.maps.Marker({
						position: new google.maps.LatLng(parseFloat(locations[i][1]), parseFloat(locations[i][2])),
						map: map,
						icon: marker_icon,
						marker_name: locations[i][0],
						custom_icon: locations[i][5]
					});
				}
				google.maps.event.addListener(contact_markers[locations[i][0]], "click", (function(marker, i)
				{
					return function()
					{
						infowindow.setContent(locations[i][4]);
						infowindow.open(map, marker);
					}
				})(contact_markers[locations[i][0]], i));
			}
		}

		// Display all Contact details
		if($("table.manage_contact_details").length > 0)
			$("table.manage_contact_details").dataTable({
				"dom": "frtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_contact_details"+default_ext,
				"iDisplayLength": 5,
				"aLengthMenu": [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6, 7]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_cnct_dtls = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_cnct_dtls.sl_no);
					$("td:eq(1)", nRow).html(jd_m_cnct_dtls.title);
					$("td:eq(2)", nRow).html(jd_m_cnct_dtls.address);
					$("td:eq(3)", nRow).html(jd_m_cnct_dtls.contact);
					$("td:eq(4)", nRow).html(jd_m_cnct_dtls.email);
					$("td:eq(5)", nRow).html(jd_m_cnct_dtls.website);
					$("td:eq(6)", nRow).html(jd_m_cnct_dtls.status_html);
					$("td:eq(7)", nRow).html(jd_m_cnct_dtls.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_cnct_dtls.title).data("href", jd_m_cnct_dtls.id);
				}
			}).fnSetFilteringDelay(2000);

		// Add contact details
		$(document).on("submit", "form.add_contact_detail_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
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
						custom_fn.show_loading("Contact detail is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form[0].reset();
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

		// Update contact details
		$(document).on("submit", "form.update_contact_detail_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("cnct_dtl", cur_form.data("href"));
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
						custom_fn.show_loading("Contact detail is being updated..", "it will take a couple of seconds");
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

		// Change contact detail status
		$(document).on("click", "input[type='checkbox'].contact_detail_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("cnct_dtl", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/contact_detail_status"+default_ext;
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
						custom_fn.show_loading("Contact Detail is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Contact Detail is being activated..", "it will take a couple of seconds");
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

		// set default contact detail	
		$(document).on("click", "a.set_default_contact_detail", function()
		{
			var cnct_dtl = $(this).data("href");
			var cur_var = $(this);
			var form_data = new FormData();
			form_data.append("cnct_dtl", cnct_dtl);
			url = base_url+current_controller+"/set_default_contact_detail"+default_ext;
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
					custom_fn.show_loading("Default Contact details being changed..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						if(cur_var.closest("table").find("a.default_contact_detail").length > 0)
							cur_var.closest("table").find("a.default_contact_detail").attr("title", "Set Default").addClass("btn-primary set_default_contact_detail").removeClass("btn-contrast default_contact_detail").attr('data-original-title', "Set Default").tooltip('fixTitle');
						cur_var.attr("title", "Currently Default").addClass("btn-contrast default_contact_detail").removeClass("btn-primary set_default_contact_detail").tooltip('hide').attr('data-original-title', "Currently Default").tooltip('fixTitle');
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


		//popup window to confirm delete contact location
		$(document).on("click", "a.delete_contact_detail", function()
		{
			var cnct_dtl_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_contact_detail_template");
			$(".delete_contact_detail_template").find("h4.modal-title").text("Contact Management - Delete Detail");
			$(".delete_contact_detail_template").find("form").addClass("delete_contact_detail_form").data("cnct_dtl", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+cnct_dtl_name+"' Contact details will be completely lost if you continue.</li>";
			form_data += "<li>'"+cnct_dtl_name+"' contact details will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+cnct_dtl_name+"' Contact detail?";
			$(".delete_contact_detail_template").find("div.modal-body").html(form_data);
			$(".delete_contact_detail_template").find("button[type='submit']").html("Continue");
			$(".delete_contact_detail_template").toggle();
		});

		// confirm delete contact location
		$(document).on("submit", "form.delete_contact_detail_form", function()
		{
			var url = base_url+current_controller+"/delete_contact_detail"+default_ext;
			var contact_id = $(this).data("cnct_dtl");
			var form_data = new FormData();
			form_data.append("cnct_dtl", contact_id);
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
					custom_fn.show_loading("Contact detail is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_contact_detail").filter(function(i, el)
										{
											return $(this).data("href") === contact_id;
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

		// Display all Contact locations
		if($("table.manage_contact_markers").length > 0)
			$("table.manage_contact_markers").dataTable({
				"dom": "rtp",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_contact_locations"+default_ext,
				"iDisplayLength": 5,
				"aLengthMenu": [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_cnct_locs = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_cnct_locs.sl_no);
					$("td:eq(1)", nRow).html(jd_m_cnct_locs.title);
					$("td:eq(2)", nRow).html(jd_m_cnct_locs.status_html);
					$("td:eq(3)", nRow).html(jd_m_cnct_locs.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_cnct_locs.title).data("marker", jd_m_cnct_locs.marker).data("href", jd_m_cnct_locs.id);
				}
			}).fnSetFilteringDelay(2000);

		// Add Contact location popup
		$(document).on("click", "a.add_contact_location", function()
		{
			var cur_var = $(this);
			if($(this).data("location_selected") === undefined || $(this).data("location_selected") === "")
			{
				for (var temp_marker in contact_markers)
					contact_markers[temp_marker].setMap(null);
				$("<div class='map_form_right'><a href='javascript:void(0);' class='cancel_contact_location'> <i title='Cancel' class='icon-3x icon-remove pull-right'></i></a></div>").insertAfter(cur_var.parent());
				cur_var.find("i").removeClass("icon-spin icon-crosshairs").addClass("icon-save");
				center_lat = 21;
				center_lng = 78;
				if (navigator.geolocation)
				{
					navigator.geolocation.getCurrentPosition(function(position)
						{
							center_lat = parseFloat(position.coords.latitude);
							center_lng = parseFloat(position.coords.longitude);
						}, function(error)
						{
							center_lat = 21;
							center_lng = 78;
						});
				}
				if(contact_markers.length > 0)
					for(var temp_marker in contact_markers)
					{
						var temp_position = contact_markers[temp_marker].getPosition();
						center_lat = temp_position.lat();
						center_lng = temp_position.lng();
						break;
					}
				create_contact_marker = new google.maps.Marker({
						position: new google.maps.LatLng(center_lat, center_lng),
						map: map,
						icon: "http://maps.google.com/mapfiles/kml/pal5/icon6.png",
						draggable: true
					});
				var latlng = new google.maps.LatLng(center_lat, center_lng);
				map.setCenter(latlng);

				cur_var.data("location_selected", "true");
				cur_var.data("latitude", center_lat);
				cur_var.data("longitude", center_lng);
				// Register Custom "dragend" Event
				google.maps.event.addListener(create_contact_marker, 'dragend', function()
				{
					// Get the Current position, where the pointer was dropped
					var point = create_contact_marker.getPosition();
					// Center the map at given point
					map.panTo(point);
					// Update the textbox
					cur_var.data("latitude", point.lat());
					cur_var.data("longitude", point.lng());
				});
			}
			else
			{
				$("body").prepend(custom_fn.model_template);
				$(".model_template").addClass("add_contact_location_template");
				$(".add_contact_location_template").find("h4.modal-title").text("Contact Management - Add Location");
				var form_data = "<div class='form-group'>";
				form_data += "<label class='control-label col-sm-4 required'>Location Name</label>";
				form_data += "<div class='col-sm-8 controls'>";
				form_data += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control'  data-rule-required='true' name='location_name' placeholder='Location Name' type='text'>";
				form_data += "</div>";
				form_data += "</div>";
				form_data += "<div class='form-group'>";
				form_data += "<label class='control-label col-sm-4 required'>Location Image</label>";
				form_data += "<div class='col-sm-8 controls'>";
				form_data += "<input autocomplete='off' tabindex='2' class='form-control' data-rule-required='true' name='location_image' placeholder='Location Image' type='file' data-rule-accept='image/*'>";
				form_data += "<p class='help-block'><small class='text-muted'>Note: Any image file and Image will be resized.</small></p>";
				form_data += "</div>";
				form_data += "</div>";
				form_data += "<div class='form-group'>";
				form_data += "<label class='control-label col-sm-4 required'>Location Icon</label>";
				form_data += "<div class='col-sm-8 controls'>";
				form_data += "<input autocomplete='off' tabindex='3' class='form-control' name='location_icon' placeholder='Location Icon (Optional)' type='file' data-rule-accept='image/png'>";
				form_data += "<p class='help-block'><small class='text-muted'>Note: Small size \"PNG\" image only and Image will be resized (Optional).</small></p>";
				form_data += "</div>";
				form_data += "</div>";

				$(".add_contact_location_template").find("div.modal-body").html(form_data);
				$(".add_contact_location_template").find("button[type='submit']").html("Add Location");
				$(".add_contact_location_template").toggle();
				$(".add_contact_location_template").find("form").addClass("add_contact_location_form").data("latitude", cur_var.data("latitude")).data("longitude", cur_var.data("longitude"));
				custom_fn.load_validate("add_contact_location_form");
			}
		});

		// add new contact location
		$(document).on("submit", "form.add_contact_location_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("latitude", cur_form.data("latitude"));
				form_data.append("longitude", cur_form.data("longitude"));
				var url = base_url+current_controller+"/add_contact_location"+default_ext;
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
						custom_fn.show_loading("Contact location is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						index = $("table.manage_contact_markers tbody").find("tr").length;
						sl_no = index + 1;
						if(response.status === "true")
						{
							// update contact location table
							if(index === 1 && $("table.manage_contact_markers tbody").find(".dataTables_empty").length > 0)
							{
								index = 0;
								sl_no = 1;
								$("table.manage_contact_markers tbody").find("tr").detach();
							}
							$("table.manage_contact_markers tbody").append(response.new_row);
							$("table.manage_contact_markers tbody tr:eq("+index+")").find("td:eq(0)").html(sl_no);
							$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", "table.manage_contact_markers tbody tr:eq("+index+")").data("name", response.new_data["title"]).data("marker", response.new_data["marker"]).data("href", response.new_data["id"]);

							// end of updating contact location table

							// google maps update
							if(response.new_data["marker_icon"] !== "" && response.new_data["marker_icon"] !== null)
							{
								marker_icon = {
													url: upload_url+response.new_data["marker_icon"],
													scaledSize: new google.maps.Size(30, 30), // scaled size
													origin: new google.maps.Point(0,0), // origin
													anchor: new google.maps.Point(0, 0) // anchor
												};
								contact_markers[response.new_data["marker"]] = new google.maps.Marker({
									position: new google.maps.LatLng(response.new_data["latitude"], response.new_data["longitude"]),
									map: map,
									icon: marker_icon,
									marker_name: response.new_data["marker"],
									custom_icon: "true"
								});
							}
							else
							{
								marker_icon = custom_fn.marker_icon("1");
								contact_markers[response.new_data["marker"]] = new google.maps.Marker({
									position: new google.maps.LatLng(response.new_data["latitude"], response.new_data["longitude"]),
									icon: marker_icon,
									marker_name: response.new_data["marker"],
									custom_icon: "false"
								});
							}

							google.maps.event.addListener(contact_markers[response.new_data["marker"]], "click", (function(marker, i)
							{
								return function()
								{
									infowindow.setContent(response.new_data["display_content"]);
									infowindow.open(map, marker);
								}
							})(contact_markers[response.new_data["marker"]], i));
							// end of google maps update

							//remove add location view and display locations
							$("a.cancel_contact_location").trigger("click");
							// end

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

		// Cancel Contact location
		$(document).on("click", "a.cancel_contact_location", function()
		{
			$(this).parent().prev().find(">a").data("location_selected", "").find("i").addClass("icon-spin icon-crosshairs").removeClass("icon-save");
			$(this).parent().detach();
			if(create_contact_marker !== null)
			{
				create_contact_marker.setMap(null);
				create_contact_marker = null;
			}
			for(var temp_marker in contact_markers)
				contact_markers[temp_marker].setMap(map);
		});

		// Change contact location status
		$(document).on("click", "input[type='checkbox'].contact_location_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var marker_id = $(this).data("marker");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("cnct_loc", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/contact_location_status"+default_ext;
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
						custom_fn.show_loading("Contact Location is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Contact Location is being activated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(7000);
					if(response.status === "true")
					{
						cur_var.prop("checked", checked);

						if(contact_markers[marker_id] !== undefined)
						{
							if(contact_markers[marker_id].custom_icon === "false")
							{
								current_stat = status === "0" ? "0" : "1";
								marker_icon = custom_fn.marker_icon(current_stat);
								contact_markers[marker_id].setIcon(marker_icon);	
							}
						}
					}
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


		//popup window to confirm delete contact location
		$(document).on("click", "a.delete_contact_location", function()
		{
			var location_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_contact_location_template");
			$(".delete_contact_location_template").find("h4.modal-title").text("Contact Management - Delete Location");
			$(".delete_contact_location_template").find("form").addClass("delete_contact_location_form").data("cnct_loc", $(this).data("href")).data("marker", $(this).data("marker"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+location_name+"' Location will be completely lost if you continue.</li>";
			form_data += "<li>'"+location_name+"' Location will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+location_name+"' Location?";
			$(".delete_contact_location_template").find("div.modal-body").html(form_data);
			$(".delete_contact_location_template").find("button[type='submit']").html("Continue");
			$(".delete_contact_location_template").toggle();
		});

		// confirm delete contact location
		$(document).on("submit", "form.delete_contact_location_form", function()
		{
			var url = base_url+current_controller+"/delete_contact_location"+default_ext;
			var contact_location = $(this).data("cnct_loc");
			var marker_id = $(this).data("marker");
			var form_data = new FormData();
			form_data.append("cnct_loc", contact_location);
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
					custom_fn.show_loading("Contact Location is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_contact_location").filter(function(i, el)
										{
											return $(this).data("href") === contact_location;
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
							cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='4' class='dataTables_empty'>No data available in table</td></tr>");
						if(contact_markers[marker_id] !== undefined)
						{
							(contact_markers[marker_id].marker_name === "false")
							{
								contact_markers[marker_id].setMap(null);
								contact_markers.splice(marker_id, 1);
							}
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

		// Update about us page
		$(document).on("submit", "form.update_about_us_form", function(submit_event)
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
			if($(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("static_page", $(this).data("href"));
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
						custom_fn.show_loading("\"About Us\" is being updated..", "it will take a couple of seconds");
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

		// Display all Social media list	
		if($("table.manage_social_network").length > 0)
			$("table.manage_social_network").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_social_network"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4, 5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_sc_nw = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_sc_nw.sl_no);
					$("td:eq(1)", nRow).html(jd_m_sc_nw.name);
					$("td:eq(2)", nRow).html(jd_m_sc_nw.media_icon);
					$("td:eq(3)", nRow).html(jd_m_sc_nw.media_url);
					$("td:eq(4)", nRow).html(jd_m_sc_nw.status);
					$("td:eq(5)", nRow).html(jd_m_sc_nw.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_sc_nw.name).data("href", jd_m_sc_nw.id).data("url", jd_m_sc_nw.url).data("icon", jd_m_sc_nw.icon);
				}
			}).fnSetFilteringDelay(2000);


		// Add Social network popup
		$(document).on("click", "a.add_social_network", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("add_social_network_template");
			$(".add_social_network_template").find("h4.modal-title").text("Static Page Management - Add Social Network");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Social Network Name</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' class='form-control' data-rule-required='true' name='social_network_name' placeholder='Social Network Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Social Network Icon</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='2' class='form-control' data-rule-required='true' name='icon' placeholder='Social Network Icon' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Social Network URL</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='3' class='form-control' data-rule-required='true' name='url' placeholder='Social Network URL' type='text'>";
			form_data += "</div>";
			form_data += "</div>";

			$(".add_social_network_template").find("div.modal-body").html(form_data);
			$(".add_social_network_template").find("button[type='submit']").html("Add");
			$(".add_social_network_template").toggle();
			$(".add_social_network_template").find("form").addClass("add_social_network_form");
			custom_fn.load_validate("add_social_network_form");
		});

		// add new Social network
		$(document).on("submit", "form.add_social_network_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/add_social_network"+default_ext;
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
						custom_fn.show_loading("Social network is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						index = $("table.manage_social_network tbody").find("tr").length;
						sl_no = index + 1;
						if(response.status === "true")
						{
							if(index === 1 && $("table.manage_social_network tbody").find(".dataTables_empty").length > 0)
							{
								index = 0;
								sl_no = 1;
								$("table.manage_social_network tbody").find("tr").detach();
							}
							$("table.manage_social_network tbody").append(response.new_row);
							$("table.manage_social_network tbody tr:eq("+index+")").find("td:eq(0)").html(sl_no);
							$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", "table.manage_social_network tbody tr:eq("+index+")").data("name", response.new_data["name"]).data("href", response.new_data["id"]).data("url", response.new_data["url"]).data("icon", response.new_data["icon"]);

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

		// Change Social network status
		$(document).on("click", "input[type='checkbox'].social_media_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("social_network", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/social_network_status"+default_ext;
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
						custom_fn.show_loading("Social Network is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Social Network is being activated..", "it will take a couple of seconds");
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


		// Update Social network popup
		$(document).on("click", "a.edit_social_network", function()
		{
			var media = $(this).data("href");
			var media_name = $(this).data("name");
			var media_icon = $(this).data("icon");
			var media_url = $(this).data("url");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("edit_social_network_template");
			$(".edit_social_network_template").find("h4.modal-title").text("Static Page Management - Update Social Network");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Social Network Name</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' class='form-control' data-rule-required='true' value='"+media_name+"' name='social_network_name' placeholder='Social Network Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Social Network Icon</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='2' class='form-control' data-rule-required='true' value='"+media_icon+"' name='icon' placeholder='Social Network Icon' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Social Network URL</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='3' class='form-control' data-rule-required='true' name='url' value='"+media_url+"' placeholder='Social Network URL' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			$(".edit_social_network_template").find("div.modal-body").html(form_data);
			$(".edit_social_network_template").find("button[type='submit']").html("Update");
			$(".edit_social_network_template").toggle();
			$(".edit_social_network_template").find("form").addClass("edit_social_network_form").data("social_network", $(this).data("href"));
			custom_fn.load_validate("edit_social_network_form");
		});

		// submit update Social network
		$(document).on("submit", "form.edit_social_network_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var social_network = $(this).data("social_network");
				var form_data = new FormData($(this)[0]);
				form_data.append("social_network", social_network);
				var url = base_url+current_controller+"/update_social_network"+default_ext;
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
						custom_fn.show_loading("Social Network is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							var row =  $("body").find("a.edit_social_network").filter(function(i, el)
										{
											return $(this).data("href") === social_network;
										}).closest("tr");
							row.find("td:eq(1)").html(response.new_data["name"]);
							row.find("td:eq(2)").html(response.new_data["icon_html"]);
							row.find("td:eq(3)").html(response.new_data["url_html"]);
							$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", row).data("name", response.new_data["name"]).data("url", response.new_data["url"]).data("icon", response.new_data["icon"]);
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

		//popup window to confirm delete Social network 
		$(document).on("click", "a.delete_social_network", function()
		{
			var media_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_social_network_template");
			$(".delete_social_network_template").find("h4.modal-title").text("Static Page Management - Delete Social Network");
			$(".delete_social_network_template").find("form").addClass("delete_social_network_form").data("social_network", $(this).data("href"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+media_name+"' Social Network will be completely lost if you continue.</li>";
			form_data += "<li>'"+media_name+"' Social Network will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+media_name+"' Social Network?";
			$(".delete_social_network_template").find("div.modal-body").html(form_data);
			$(".delete_social_network_template").find("button[type='submit']").html("Continue");
			$(".delete_social_network_template").toggle();
		});

		// delete Social network
		$(document).on("submit", "form.delete_social_network_form", function()
		{
			var url = base_url+current_controller+"/delete_social_network"+default_ext;
			var social_network = $(this).data("social_network");
			var form_data = new FormData();
			form_data.append("social_network", social_network);
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
					custom_fn.show_loading("Social Network is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_social_network").filter(function(i, el)
										{
											return $(this).data("href") === social_network;
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

		// Display all Clientele list	
		if($("table.manage_clientele").length > 0)
			$("table.manage_clientele").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_clientele"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_cele = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_cele.sl_no);
					$("td:eq(1)", nRow).html(jd_m_cele.name);
					$("td:eq(2)", nRow).html(jd_m_cele.image_html);
					$("td:eq(3)", nRow).html(jd_m_cele.status);
					$("td:eq(4)", nRow).html(jd_m_cele.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_cele.name).data("href", jd_m_cele.id).data("image", jd_m_cele.image);
				}
			}).fnSetFilteringDelay(2000);


		// Add Clientele popup
		$(document).on("click", "a.add_clientele", function()
		{
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("add_clientele_template");
			$(".add_clientele_template").find("h4.modal-title").text("Static Page Management - Add Clientele");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Clientele Title</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control' data-rule-required='true' name='clientele_name' placeholder='Clientele Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Clientele Image</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='2' class='form-control' data-rule-required='true' name='clientele_image' placeholder='Clientele Icon' type='file' data-msg-accept='Please select only PNG Files' accept='image/png'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'></label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<img class='col-sm-4 pull-left preview_img' src='"+asset_url+"images/default.png' alt='No Image.' height='60'>";
			form_data += "</div>";
			form_data += "</div>";

			$(".add_clientele_template").find("div.modal-body").html(form_data);
			$(".add_clientele_template").find("button[type='submit']").html("Add");
			$(".add_clientele_template").find(":file").filestyle();
			$(".add_clientele_template").toggle();
			$(".add_clientele_template").find("form").addClass("add_clientele_form").attr("enctype", "multipart/form-data");
			custom_fn.load_validate("add_clientele_form");
		});

		// add new Clientele
		$(document).on("submit", "form.add_clientele_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/add_clientele"+default_ext;
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
						custom_fn.show_loading("Clientele is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						index = $("table.manage_clientele tbody").find("tr").length;
						sl_no = index + 1;
						if(response.status === "true")
						{
							if(index === 1 && $("table.manage_clientele tbody").find(".dataTables_empty").length > 0)
							{
								index = 0;
								sl_no = 1;
								$("table.manage_clientele tbody").find("tr").detach();
							}
							$("table.manage_clientele tbody").append(response.new_row);
							$("table.manage_clientele tbody tr:eq("+index+")").find("td:eq(0)").html(sl_no);
							$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", "table.manage_clientele tbody tr:eq("+index+")").data("name", response.new_data["name"]).data("href", response.new_data["id"]).data("image", response.new_data["image"]);
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

		// Change Clientele status	
		$(document).on("click", "input[type='checkbox'].clientele_status", function(click_event)
		{
			click_event.preventDefault();
			click_event.stopPropagation();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("clientele", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/clientele_status"+default_ext;
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
						custom_fn.show_loading("Clientele is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Clientele is being activated..", "it will take a couple of seconds");
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

		// Update Clientele popup
		$(document).on("click", "a.edit_clientele", function()
		{
			var clientele = $(this).data("href");
			var ce_name = $(this).data("name");
			var ce_image = $(this).data("image");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("edit_clientele_template");
			$(".edit_clientele_template").find("h4.modal-title").text("Static Page Management - Update Clientele");
			var form_data = "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Clientele Title</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='1' autofocus='true' class='form-control' value='"+ce_name+"' data-rule-required='true' name='clientele_name' placeholder='Clientele Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'>Clientele Image</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input autocomplete='off' tabindex='2' class='form-control' name='clientele_image' placeholder='Clientele Icon' type='file' data-msg-accept='Please select only PNG Files' accept='image/png'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4 required'></label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<img class='col-sm-4 pull-left preview_img' src='"+upload_url+ce_image+"' alt='No Image.' height='60'>";
			form_data += "</div>";
			form_data += "</div>";
			$(".edit_clientele_template").find("div.modal-body").html(form_data);
			$(".edit_clientele_template").find("button[type='submit']").html("Update");
			$(".edit_clientele_template").toggle();
			$(".edit_clientele_template").find("form").addClass("edit_clientele_form").data("clientele", $(this).data("href")).data("image", ce_image);
			custom_fn.load_validate("edit_clientele_form");
		});

		// submit update Clientele
		$(document).on("submit", "form.edit_clientele_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var clientele = $(this).data("clientele");
				var ce_img = $(this).data("image");
				var form_data = new FormData($(this)[0]);
				form_data.append("clientele", clientele);
				form_data.append("image", ce_img);
				var url = base_url+current_controller+"/update_clientele"+default_ext;
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
						custom_fn.show_loading("Clientele is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							var row =  $("body").find("a.edit_clientele").filter(function(i, el)
										{
											return $(this).data("href") === clientele;
										}).closest("tr");
							row.find("td:eq(1)").html(response.new_data["name"]);
							row.find("td:eq(2)").html(response.new_data["image_html"]);
							$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", row).data("name", response.new_data["name"]).data("image", response.new_data["image"]);
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

		//popup window to confirm delete Clientele
		$(document).on("click", "a.delete_clientele", function()
		{
			var ce_name = $(this).data("name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_clientele_template");
			$(".delete_clientele_template").find("h4.modal-title").text("Static Page Management - Delete Clientele");
			$(".delete_clientele_template").find("form").addClass("delete_clientele_form").data("clientele", $(this).data("href")).data("image", $(this).data("image"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+ce_name+"' Clientele will be completely lost if you continue.</li>";
			form_data += "<li>'"+ce_name+"' Clientele will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+ce_name+"' Clientele?";
			$(".delete_clientele_template").find("div.modal-body").html(form_data);
			$(".delete_clientele_template").find("button[type='submit']").html("Continue");
			$(".delete_clientele_template").toggle();
		});

		// delete Clientele
		$(document).on("submit", "form.delete_clientele_form", function()
		{
			var url = base_url+current_controller+"/delete_clientele"+default_ext;
			var clientele = $(this).data("clientele");
			var form_data = new FormData();
			form_data.append("clientele", clientele);
			form_data.append("image", $(this).data("image"));
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
					custom_fn.show_loading("Clientele is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{

						var cur_row = $("body").find("a.delete_clientele").filter(function(i, el)
										{
											return $(this).data("href") === clientele;
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
		// End of StaticPages Module Functionalities

	});
});
