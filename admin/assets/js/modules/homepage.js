require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var asset_url = $("head").data("asset-url");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
		var select2_page_count = 10;

		if($("form.add_slider_image_form").length > 0)
			custom_fn.load_validate("add_slider_image_form");
		if($("form.update_slider_image_form").length > 0)
			custom_fn.load_validate("update_slider_image_form");
			
		if($("form.add_deals_homepage").length > 0)
			custom_fn.load_validate("add_deals_homepage");
		if($("form.update_deals_homepage").length > 0)
			custom_fn.load_validate("update_deals_homepage");	

		// Homepage Feature Functionalities Modules

		// Display all Sliders list
		
		$(".from_date").datepicker();
		if($("table.manage_slider_images").length > 0)
			$("table.manage_slider_images").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_slider_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_slider = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
					$("td:eq(1)", nRow).html(jd_m_slider.name);
					$("td:eq(2)", nRow).html(jd_m_slider.image_html);
					$("td:eq(3)", nRow).html(jd_m_slider.status);
					$("td:eq(4)", nRow).html(jd_m_slider.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_image", jd_m_slider.image).data("slider_name", jd_m_slider.name);
				}
			}).fnSetFilteringDelay(2000);
			
			
			
			


		// Add slider image
		$(document).on("submit", "form.add_slider_image_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Slider image is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if($(".preview_img").length > 0)
								$(".preview_img").attr("src", asset_url+"images/default.png");
							cur_form[0].reset();
						}
						cur_form.find("input[name]").focus();
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

		// Update slider image
		$(document).on("submit", "form.update_slider_image_form", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("slider", cur_form.data("href"));
				form_data.append("current_slider_image", cur_form.find("input[type='file']").data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Slider image is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form.find("input[type='file']").data("href", response.new_data["image_path"]);
						cur_form.find(":disabled").val("");
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

		// Change Slider status	
		$(document).on("click", "input[type='checkbox'].slider_image_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("slider", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/slider_image_status"+default_ext;
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
						custom_fn.show_loading("Slider image is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Slider image is being activated..", "it will take a couple of seconds");
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

		//popup window to confirm delete slider image
		$(document).on("click", "a.delete_slider_image", function()
		{
			var slider_name = $(this).data("slider_name");
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_slider_image_template");
			$(".delete_slider_image_template").find("h4.modal-title").text("Home Page Management - Delete Slider Image");
			$(".delete_slider_image_template").find("form").addClass("delete_slider_image_form").data("slider", $(this).data("href")).data("slider_name", $(this).data("slider_name")).data("slider_image", $(this).data("slider_image"));
			var form_data = "<ul>";
			form_data += "<li>Information of '"+slider_name+"' Slider image will be completely lost if you continue.</li>";
			form_data += "<li>'"+slider_name+"' slider image will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete '"+slider_name+"' slider image?";
			$(".delete_slider_image_template").find("div.modal-body").html(form_data);
			$(".delete_slider_image_template").find("button[type='submit']").html("Continue");
			$(".delete_slider_image_template").toggle();
		});

		// delete slider image
		$(document).on("submit", "form.delete_slider_image_form", function()
		{
			alert();
			var url = base_url+current_controller+"/delete_slider_image"+default_ext;
			var slider = $(this).data("slider");
			var slider_image = $(this).data("slider_image");
			var form_data = new FormData();
			form_data.append("slider", slider);
			form_data.append("slider_image", slider_image);
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
					custom_fn.show_loading("Slider image is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_slider_image").filter(function(i, el)
										{
											return $(this).data("href") === slider;
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
		
		//---------------------------------DEALS START-----------------------------------------//
			
			if($("table.manage_deals").length > 0)
			$("table.manage_deals").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_deal_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [1, 2, 3, 4,5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_slider = JSON.parse(aData[0]);

					var str_disp = jd_m_slider.display_section;
					str_disp = str_disp.replace('_'," ");
					str_disp = str_disp.toLowerCase().replace(/\b[a-z]/g, function(letter) {
					    return letter.toUpperCase();
					});

					$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
					$("td:eq(1)", nRow).html(jd_m_slider.category);
					$("td:eq(2)", nRow).html(str_disp);
					$("td:eq(3)", nRow).html(jd_m_slider.content);
					$("td:eq(4)", nRow).html(jd_m_slider.image_html);
					$("td:eq(5)", nRow).html(jd_m_slider.status);
					$("td:eq(6)", nRow).html(jd_m_slider.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_image", jd_m_slider.image).data("slider_name", jd_m_slider.name);
				}
			}).fnSetFilteringDelay(2000);
			
		$(document).on("submit", "form.add_deals_homepage", function(submit_event)
		{
			
			if($(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Deals is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if($(".preview_img").length > 0)
								$(".preview_img").attr("src", asset_url+"images/default.png");
							
							cur_form[0].reset();
							$("#sector_category").trigger('change');
							$("select.tour_list").html("").trigger('change');
							
							
						}
						//cur_form.find("input[name]").focus();
						cur_form.find("#first_input").focus();
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
		
		$(document).on("submit", "form.update_deals_homepage", function(submit_event)
		{
			if($(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("deals_id", cur_form.data("href"));
				form_data.append("current_image", cur_form.find("input[type='file']").data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Deals is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form.find("input[type='file']").data("href", response.new_data["image_path"]);
						cur_form.find(":disabled:not(#sector_category)").val("");
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
		
		
		
		
		
		
		
		
		
		$(document).on("change","#sector_category",function(){
			
			var val = $(this).val();
			$(".sector_tour").hide(); $(".sector_tour").find(":input,select").attr("disabled",true);
			$(".sector_flight").hide(); $(".sector_flight").find(":input,select").attr("disabled",true);
			$(".sector_hotel").hide(); $(".sector_hotel").find(":input,select").attr("disabled",true);
			$(".sector_others").hide(); $(".sector_others").find(":input,select").attr("disabled",true);
			$("select.set_airlines").html("").trigger('change');
			$("select.set_airports").html("").trigger('change');
			$("select.set_search_city").html("").trigger('change');
			
			if (val == 1) {
			   $(".sector_tour").show();
			   $(".sector_tour").find(":input,select").attr("disabled",false);
			   
			   
			   
			   if($("select.tour_list").length > 0)
					{
						$("select.tour_list").each(function()
						{
							var sst = $(this).data("href") !== undefined ? $(this).data("href") : "";
							var url = base_url+"package/tour_links"+default_ext;
							var this_var = "#"+$(this).attr("id");
							
							if(sst !== "")
							{
								$.ajax(
								{
									type: "GET",
									url: url + "?id=" + sst,
									dataType: "json"
								}).then(function (data)
								{
									var option = new Option(data.results[0].text, data.results[0].id, true, true);
									$(this_var).html(option).trigger("change");
									if($("#master_id").length > 0)
										$("#master_id").val(data.results[0].text);
								});
							}
							$(this_var).select2(
							{
								placeholder: {"id": "", "text": "search keywords like hotel name, hotel city"},
								width: "100%",
								minimumInputLength: 1,
								maximumInputLength: 20,
								minimumResultsForSearch: select2_page_count,
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
									return option; // let our custom formatter work
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
							}).on("select2:select", function(option)
							{
								var master_id = option.params.data.id;
								$.post(base_url + "package/retrieve_data",{id:master_id},function(result){
									if($("input[name='master_id']").length > 0 ){
										$("input[name='master_id']").val(master_id).data("href", master_id);
									}
									if($("input[name='tour_link']").length > 0 && result !== ""){
										$("input[name='tour_link']").val(result).data("href", option.params.data.result);
									}
									
								});
								
							}).on("change", function(e)
							{
								
							});
						});
					}
			   
			   
			   
			   
			   
			   
            }else if (val == 2) {
				$(".sector_flight").show();
			    $(".sector_flight").find(":input,select").attr("disabled",false);
				$(".return_date").find(":input").attr("disabled",true);
				
				$('input[type=radio][name=trip_type]').change(function() {
					var rad_val = $(this).val();
					if (rad_val == 'Return') {
						$(".return_date").show();
						$(".return_date").find(":input").attr("disabled",false).val("");
                    }else{
						 $(".return_date").hide();
						$(".return_date").find(":input").attr("disabled",true);
					}
				});
				if($('input[type=radio][name=trip_type]').is(':checked')){
					var rad_vals = $('input[type=radio][name=trip_type]:checked').val();
					if (rad_vals == 'Return') {
                        $(".return_date").find(":input").attr("disabled",false);
                    }
				}
				
				
            }else if (val == 3) {
                $(".sector_hotel").show();
			    $(".sector_hotel").find(":input,select").attr("disabled",false);
            }else if(val == 4){
				$(".sector_others").show();
				$(".sector_others").find(":input,select").attr("disabled",false);
			}else {
                 $(".sector_tour,.sector_flight,.sector_hotel,.sector_others").hide();
				 $(".sector_tour,.sector_flight,.sector_hotel,sector_others").find(":input,select").attr("disabled",true);
            }
		});
		
		if($("#category_field").length > 0){
			$("#sector_category").trigger('change');
		}
		
		$(document).on("click", "input[type='checkbox'].deals_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("id", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/deals_status"+default_ext;
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
						custom_fn.show_loading("Deals is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Deals is being activated..", "it will take a couple of seconds");
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
		
		$(document).on("click", "a.delete_deals", function()
		{
			var slider_name = '';
			
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_slider_image_template");
			$(".delete_slider_image_template").find("h4.modal-title").text("Home Page Management - Delete Deals");
			$(".delete_slider_image_template").find("form").addClass("delete_deals_form").data("slider", $(this).data("href")).data("slider_name", $(this).data("slider_name")).data("slider_image", $(this).data("slider_image"));
			var form_data = "<ul>";
			form_data += "<li>Information of this deals will be completely lost if you continue.</li>";
			form_data += "<li>This deals will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete this deals?";
			$(".delete_slider_image_template").find("div.modal-body").html(form_data);
			$(".delete_slider_image_template").find("button[type='submit']").html("Continue");
			$(".delete_slider_image_template").toggle();
		});
		
		$(document).on("submit", "form.delete_deals_form", function()
		{
			var url = base_url+current_controller+"/delete_deals_image"+default_ext;
			var slider = $(this).data("slider");
			var slider_image = $(this).data("slider_image");
			var form_data = new FormData();
			form_data.append("slider", slider);
			form_data.append("slider_image", slider_image);
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
					custom_fn.show_loading("Deals is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_deals").filter(function(i, el)
										{
											return $(this).data("href") === slider;
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
		
		
		
		
		if($("select.set_search_city").length > 0)
								{
										$("select.set_search_city").each(function()
										{
											var sci = $(this).data("href") !== undefined ? $(this).data("href") : "";
											var url = base_url+"ajax/get_all_cities"+default_ext;
											var this_var = "#"+$(this).attr("id");
											if(sci !== "")
											{
												$.ajax(
												{
													type: "GET",
													url: url + "?id=" + sci,
													dataType: "json"
												}).then(function (data)
												{
													var option = new Option(data.results[0].text, data.results[0].id, true, true);
													$(this_var).html(option).trigger("change");
												});
											}
											$(this_var).select2(
											{
												placeholder: {"id": "", "text": "Select City"},
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
															page: params.page 																													};
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
		
		
		
	
	
	//---------------------------------DEALS END-----------------------------------------//
		
		//---------------------------------TOUR DEALS START-----------------------------------------//
		
		if($("select.tour_list").length > 0)
					{
						$("select.tour_list").each(function()
						{
							var sst = $(this).data("href") !== undefined ? $(this).data("href") : "";
							var url = base_url+"package/tour_deals_data"+default_ext;
							var this_var = "#"+$(this).attr("id");
							
							if(sst !== "")
							{
								$.ajax(
								{
									type: "GET",
									url: url + "?id=" + sst,
									dataType: "json"
								}).then(function (data)
								{
									var option = new Option(data.results[0].text, data.results[0].id, true, true);
									$(this_var).html(option).trigger("change");
									if($("#master_id").length > 0)
										$("#master_id").val(data.results[0].text);
								});
							}
							$(this_var).select2(
							{
								placeholder: {"id": "", "text": "search keywords like hotel name, hotel city"},
								width: "100%",
								minimumInputLength: 1,
								maximumInputLength: 20,
								minimumResultsForSearch: select2_page_count,
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
									return option; // let our custom formatter work
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
							}).on("select2:select", function(option)
							{
								//var info = JSON.stringify(option.params.data.info);
								var str = option.params.data.info.split(',');
								
								var name_en = str[0];
								var name_fa = str[1];
								var rating = str[2];
								var city_en = str[3];
								if (str[4] == '') {
                                    var city_fa = city_en;
                                }else{
									var city_fa = str[4];
								}
								var country_en = str[5];
								if (str[6] == '') {
                                    var country_fa = country_en;
                                }else{
									var country_fa = str[6];
								}
								var price = option.params.data.price;
								var new_str = JSON.stringify(name_en+','+name_fa+','+rating+','+city_en+'-'+country_en+','+city_fa+'-'+'country_fa'+','+price);
								//console.log(new_str);
								
								
								
								if($("input[name='info']").length > 0 ){
									$("input[name='info']").val(new_str).data("href", new_str);
								}
								var master_id = option.params.data.id;
								$.post(base_url + "package/retrieve_data",{id:master_id},function(result){
									if($("input[name='master_id']").length > 0 ){
										$("input[name='master_id']").val(master_id).data("href", master_id);
									}
									if($("input[name='tour_link']").length > 0 && result !== ""){
										$("input[name='tour_link']").val(result).data("href", option.params.data.result);
									}
									
								});
								
							}).on("change", function(e)
							{
								
							});
						});
					}
		
		
		
		
		
		if($("table.manage_tour_deals").length > 0)
			$("table.manage_tour_deals").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_tour_deal_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [3, 4, 5, 6]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_slider = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
					$("td:eq(1)", nRow).html(jd_m_slider.deals_text);
					$("td:eq(2)", nRow).html(jd_m_slider.hotel_name);
					$("td:eq(3)", nRow).html(jd_m_slider.address);
					$("td:eq(4)", nRow).html(jd_m_slider.image_html);
					$("td:eq(5)", nRow).html(jd_m_slider.status);
					$("td:eq(6)", nRow).html(jd_m_slider.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_image", jd_m_slider.image).data("slider_name", jd_m_slider.name);
				}
			}).fnSetFilteringDelay(2000);
		
		
		
		
		
		
		if($("form.add_tour_deals").length > 0){
			custom_fn.load_validate("add_tour_deals");
					
		$(document).on("submit", "form.add_tour_deals", function(submit_event)
		{
			
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Tour Deals is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if($(".preview_img").length > 0)
								$(".preview_img").attr("src", asset_url+"images/default.png");
							
							cur_form[0].reset();
							$("select.tour_list").html("").trigger('change');
							
							
						}
						//cur_form.find("input[name]").focus();
						cur_form.find("#first_input").focus();
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
			
			
		}
		
		
		
		$(document).on("click", "input[type='checkbox'].tour_deals_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("id", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/tour_deals_status"+default_ext;
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
						custom_fn.show_loading("Deals is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Deals is being activated..", "it will take a couple of seconds");
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
		
		$(document).on("click", "a.delete_tour_deals", function()
		{
			var slider_name = '';
			
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_slider_image_template");
			$(".delete_slider_image_template").find("h4.modal-title").text("Home Page Management - Delete Tour Deals");
			$(".delete_slider_image_template").find("form").addClass("delete_tour_deals_form").data("slider", $(this).data("href")).data("slider_name", $(this).data("slider_name")).data("slider_image", $(this).data("slider_image"));
			var form_data = "<ul>";
			form_data += "<li>Information of this deals will be completely lost if you continue.</li>";
			form_data += "<li>This deals will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete this tour deals?";
			$(".delete_slider_image_template").find("div.modal-body").html(form_data);
			$(".delete_slider_image_template").find("button[type='submit']").html("Continue");
			$(".delete_slider_image_template").toggle();
		});
		
		$(document).on("submit", "form.delete_tour_deals_form", function()
		{
			var url = base_url+current_controller+"/delete_tour_deals"+default_ext;
			var slider = $(this).data("slider");
			var slider_image = $(this).data("slider_image");
			var form_data = new FormData();
			form_data.append("slider", slider);
			form_data.append("slider_image", slider_image);
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
					custom_fn.show_loading("Deals is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_tour_deals").filter(function(i, el)
										{
											return $(this).data("href") === slider;
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
		
	if($("form.update_tour_deals").length > 0){
		custom_fn.load_validate("update_tour_deals");
		$(document).on("submit", "form.update_tour_deals", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("tour_deals_id", cur_form.data("href"));
				form_data.append("current_image", cur_form.find("input[type='file']").data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Deals is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form.find("input[type='file']").data("href", response.new_data["image_path"]);
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
		}
			
		 
		 
		//---------------------------------TOUR DEALS END-----------------------------------------//
		
		

		
		//---------------------------------HOTEL DEALS START-----------------------------------------//
		
		if($("select.hotel_list").length > 0)
					{
						$("select.hotel_list").each(function()
						{
							var sst = $(this).data("href") !== undefined ? $(this).data("href") : "";
							var url = base_url+"package/tour_deals_data"+default_ext;
							var this_var = "#"+$(this).attr("id");
							
							if(sst !== "")
							{
								$.ajax(
								{
									type: "GET",
									url: url + "?id=" + sst,
									dataType: "json"
								}).then(function (data)
								{
									var option = new Option(data.results[0].text, data.results[0].id, true, true);
									$(this_var).html(option).trigger("change");
									if($("#master_id").length > 0)
										$("#master_id").val(data.results[0].text);
								});
							}
							$(this_var).select2(
							{
								placeholder: {"id": "", "text": "search keywords like hotel name, hotel city"},
								width: "100%",
								minimumInputLength: 1,
								maximumInputLength: 20,
								minimumResultsForSearch: select2_page_count,
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
									return option; // let our custom formatter work
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
							}).on("select2:select", function(option)
							{
								//var info = JSON.stringify(option.params.data.info);
								var str = option.params.data.info.split(',');
								
								var name_en = str[0];
								var name_fa = str[1];
								var rating = str[2];
								var city_en = str[3];
								if (str[4] == '') {
                                    var city_fa = city_en;
                                }else{
									var city_fa = str[4];
								}
								var country_en = str[5];
								if (str[6] == '') {
                                    var country_fa = country_en;
                                }else{
									var country_fa = str[6];
								}
								var price = option.params.data.price;
								var new_str = JSON.stringify(name_en+','+name_fa+','+rating+','+city_en+'-'+country_en+','+city_fa+'-'+'country_fa'+','+price);
								//console.log(new_str);
								
								
								
								if($("input[name='info']").length > 0 ){
									$("input[name='info']").val(new_str).data("href", new_str);
								}
								var master_id = option.params.data.id;
								$.post(base_url + "package/retrieve_data",{id:master_id},function(result){
									if($("input[name='master_id']").length > 0 ){
										$("input[name='master_id']").val(master_id).data("href", master_id);
									}
									if($("input[name='tour_link']").length > 0 && result !== ""){
										$("input[name='tour_link']").val(result).data("href", option.params.data.result);
									}
									
								});
								
							}).on("change", function(e)
							{
								
							});
						});
					}
		
		
		
		
		
		if($("table.manage_hotel_deals").length > 0)
			$("table.manage_hotel_deals").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_hotel_deal_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [2, 3, 4, 5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_slider = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
					$("td:eq(1)", nRow).html(jd_m_slider.hotel_name);
					$("td:eq(2)", nRow).html(jd_m_slider.address);
					$("td:eq(3)", nRow).html(jd_m_slider.image_html);
					$("td:eq(4)", nRow).html(jd_m_slider.status);
					$("td:eq(5)", nRow).html(jd_m_slider.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_image", jd_m_slider.image).data("slider_name", jd_m_slider.name);
				}
			}).fnSetFilteringDelay(2000);
		
		
		
		
		
		
		if($("form.add_hotel_deals").length > 0){
			custom_fn.load_validate("add_hotel_deals");
					
		$(document).on("submit", "form.add_hotel_deals", function(submit_event)
		{
			
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Hotel Deals is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if($(".preview_img").length > 0)
								$(".preview_img").attr("src", asset_url+"images/default.png");
							
							cur_form[0].reset();
							$("select.hotel_list").html("").trigger('change');
							
							
						}
						//cur_form.find("input[name]").focus();
						cur_form.find("#first_input").focus();
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
			
			
		}
		
		
		
		$(document).on("click", "input[type='checkbox'].hotel_deals_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("id", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/hotel_deals_status"+default_ext;
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
						custom_fn.show_loading("Deals is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Deals is being activated..", "it will take a couple of seconds");
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
		
		$(document).on("click", "a.delete_hotel_deals", function()
		{
			var slider_name = '';
			
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_slider_image_template");
			$(".delete_slider_image_template").find("h4.modal-title").text("Home Page Management - Delete Hotel Deals");
			$(".delete_slider_image_template").find("form").addClass("delete_hotel_deals_form").data("slider", $(this).data("href")).data("slider_name", $(this).data("slider_name")).data("slider_image", $(this).data("slider_image"));
			var form_data = "<ul>";
			form_data += "<li>Information of this deals will be completely lost if you continue.</li>";
			form_data += "<li>This deals will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete this hotel deals?";
			$(".delete_slider_image_template").find("div.modal-body").html(form_data);
			$(".delete_slider_image_template").find("button[type='submit']").html("Continue");
			$(".delete_slider_image_template").toggle();
		});
		
		$(document).on("submit", "form.delete_hotel_deals_form", function()
		{
			var url = base_url+current_controller+"/delete_hotel_deals"+default_ext;
			var slider = $(this).data("slider");
			var slider_image = $(this).data("slider_image");
			var form_data = new FormData();
			form_data.append("slider", slider);
			form_data.append("slider_image", slider_image);
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
					custom_fn.show_loading("Deals is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_hotel_deals").filter(function(i, el)
										{
											return $(this).data("href") === slider;
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
		
	if($("form.update_hotel_deals").length > 0){
		custom_fn.load_validate("update_hotel_deals");
		$(document).on("submit", "form.update_hotel_deals", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("hotel_deals_id", cur_form.data("href"));
				form_data.append("current_image", cur_form.find("input[type='file']").data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Deals is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form.find("input[type='file']").data("href", response.new_data["image_path"]);
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
		}
			
		 
		 
		//---------------------------------HOTEL DEALS END-----------------------------------------//
		
		
		
		
		
		
		//---------------------------------NEWS START-----------------------------------------//
		
		if($("table.manage_news").length > 0)
			$("table.manage_news").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_news_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [1,2, 3]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_slider = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
					$("td:eq(1)", nRow).html(jd_m_slider.news_text);
					$("td:eq(2)", nRow).html(jd_m_slider.status);
					$("td:eq(3)", nRow).html(jd_m_slider.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_name", jd_m_slider.name);
				}
			}).fnSetFilteringDelay(2000);
		
		
		
		
		
		
		if($("form.add_news").length > 0){
			custom_fn.load_validate("add_news");
					
		$(document).on("submit", "form.add_news", function(submit_event)
		{
			
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("News is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if($(".preview_img").length > 0)
								$(".preview_img").attr("src", asset_url+"images/default.png");
							
							cur_form[0].reset();
							
							
						}
						//cur_form.find("input[name]").focus();
						cur_form.find("#first_input").focus();
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
			
			
		}
		
		
		
		$(document).on("click", "input[type='checkbox'].news_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("id", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/news_status"+default_ext;
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
						custom_fn.show_loading("News is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("News is being activated..", "it will take a couple of seconds");
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
		
		$(document).on("click", "a.delete_news", function()
		{
			var slider_name = '';
			
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_slider_image_template");
			$(".delete_slider_image_template").find("h4.modal-title").text("Home Page Management - Delete News");
			$(".delete_slider_image_template").find("form").addClass("delete_news_form").data("slider", $(this).data("href")).data("slider_name", $(this).data("slider_name")).data("slider_image", $(this).data("slider_image"));
			var form_data = "<ul>";
			form_data += "<li>Information of this news will be completely lost if you continue.</li>";
			form_data += "<li>This news will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete this news?";
			$(".delete_slider_image_template").find("div.modal-body").html(form_data);
			$(".delete_slider_image_template").find("button[type='submit']").html("Continue");
			$(".delete_slider_image_template").toggle();
		});
		
		$(document).on("submit", "form.delete_news_form", function()
		{
			var url = base_url+current_controller+"/delete_news"+default_ext;
			var slider = $(this).data("slider");
			var form_data = new FormData();
			form_data.append("slider", slider);
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
					custom_fn.show_loading("News is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_news").filter(function(i, el)
										{
											return $(this).data("href") === slider;
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
		
	if($("form.update_news").length > 0){
		custom_fn.load_validate("update_news");
		$(document).on("submit", "form.update_news", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("news_id", cur_form.data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("News is being updated..", "it will take a couple of seconds");
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
		}
			
		 
		 
		//---------------------------------NEWS END-----------------------------------------//
		
		
		
		
		
		
		//---------------------------------ATTRACTION START-----------------------------------------//
		if($("select.attraction_list").length > 0)
					{
						
						$("select.attraction_list").each(function()
						{
							var sst = $(this).data("href") !== undefined ? $(this).data("href") : "";
							var url = base_url+"package/attraction_data"+default_ext;
							var this_var = "#"+$(this).attr("id");
							
							if(sst !== "")
							{
								$.ajax(
								{
									type: "GET",
									url: url + "?id=" + sst,
									dataType: "json"
								}).then(function (data)
								{
									var option = new Option(data.results[0].text, data.results[0].id, true, true);
									$(this_var).html(option).trigger("change");
									if($("#master_id").length > 0)
										$("#master_id").val(data.results[0].text);
								});
							}
							$(this_var).select2(
							{
								placeholder: {"id": "", "text": "search keywords like hotel name, hotel city"},
								width: "100%",
								minimumInputLength: 1,
								maximumInputLength: 20,
								minimumResultsForSearch: select2_page_count,
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
									return option; // let our custom formatter work
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
							}).on("select2:select", function(option)
							{
								//var info = JSON.stringify(option.params.data.info);
								var str = option.params.data.info.split(',');
								var name_en = str[0];
								var name_fa = str[1];
								var days = str[2];
								var city_en = str[3];
								if (str[4] == '') {
                                    var city_fa = city_en;
                                }else{
									var city_fa = str[4];
								}
								var country_en = str[5];
								if (str[6] == '') {
                                    var country_fa = country_en;
                                }else{
									var country_fa = str[6];
								}
								var price = option.params.data.price;
								var new_str = JSON.stringify(name_en+','+name_fa+','+days+','+city_en+'-'+country_en+','+city_fa+'-'+'country_fa'+','+price);
								console.log(new_str);
								
								
								
								if($("input[name='info']").length > 0 ){
									$("input[name='info']").val(new_str).data("href", new_str);
								}
								var master_id = option.params.data.id;
								$.post(base_url + "package/retrieve_data",{id:master_id},function(result){
									if($("input[name='master_id']").length > 0 ){
										$("input[name='master_id']").val(master_id).data("href", master_id);
									}
									if($("input[name='tour_link']").length > 0 && result !== ""){
										$("input[name='tour_link']").val(result).data("href", option.params.data.result);
									}
									
								});
								
							}).on("change", function(e)
							{
								
							});
						});
					}
		
		
		
		
		
		if($("table.manage_attraction").length > 0)
			$("table.manage_attraction").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/get_attraction_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [2, 3,4, 5 ]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
					var jd_m_slider = JSON.parse(aData[0]);
					$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
					$("td:eq(1)", nRow).html(jd_m_slider.deals_text);
					$("td:eq(2)", nRow).html(jd_m_slider.address);
					$("td:eq(3)", nRow).html(jd_m_slider.image_html);
					$("td:eq(4)", nRow).html(jd_m_slider.status);
					$("td:eq(5)", nRow).html(jd_m_slider.actions);
					$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_image", jd_m_slider.image).data("slider_name", jd_m_slider.name);
				}
			}).fnSetFilteringDelay(2000);
		
		
		
		
		
		
		if($("form.add_attraction").length > 0){
			custom_fn.load_validate("add_attraction");
					
		$(document).on("submit", "form.add_attraction", function(submit_event)
		{
			
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Tour Deals is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if($(".preview_img").length > 0)
								$(".preview_img").attr("src", asset_url+"images/default.png");
							
							cur_form[0].reset();
							$("select.attraction_list").html("").trigger('change');
							
							
						}
						//cur_form.find("input[name]").focus();
						cur_form.find("#first_input").focus();
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
			
			
		}
		
		
		
		$(document).on("click", "input[type='checkbox'].attraction_status", function(click_event)
		{
			click_event.preventDefault();
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = checked ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("id", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/attraction_status"+default_ext;
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
						custom_fn.show_loading("Attraction is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Attraction is being activated..", "it will take a couple of seconds");
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
		
		$(document).on("click", "a.delete_attraction", function()
		{
			var slider_name = '';
			
			$("body").prepend(custom_fn.model_template);
			$(".model_template").addClass("delete_slider_image_template");
			$(".delete_slider_image_template").find("h4.modal-title").text("Home Page Management - Delete attraction");
			$(".delete_slider_image_template").find("form").addClass("delete_attraction_form").data("slider", $(this).data("href")).data("slider_name", $(this).data("slider_name")).data("slider_image", $(this).data("slider_image"));
			var form_data = "<ul>";
			form_data += "<li>Information of this attraction will be completely lost if you continue.</li>";
			form_data += "<li>This attraction will not be displayed.</li>";
			form_data += "</ul>";
			form_data += "<br>Are you sure to delete this attraction?";
			$(".delete_slider_image_template").find("div.modal-body").html(form_data);
			$(".delete_slider_image_template").find("button[type='submit']").html("Continue");
			$(".delete_slider_image_template").toggle();
		});
		
		$(document).on("submit", "form.delete_attraction_form", function()
		{
			var url = base_url+current_controller+"/delete_attraction"+default_ext;
			var slider = $(this).data("slider");
			var slider_image = $(this).data("slider_image");
			var form_data = new FormData();
			form_data.append("slider", slider);
			form_data.append("slider_image", slider_image);
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
					custom_fn.show_loading("attraction is being deleted..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						var cur_row = $("body").find("a.delete_attraction").filter(function(i, el)
										{
											return $(this).data("href") === slider;
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
		
	if($("form.update_attraction").length > 0){
		custom_fn.load_validate("update_attraction");
		$(document).on("submit", "form.update_attraction", function(submit_event)
		{
			if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				form_data.append("attraction_id", cur_form.data("href"));
				form_data.append("current_image", cur_form.find("input[type='file']").data("href"));
				var url = base_url+current_controller+"/"+current_method+default_ext;
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
						custom_fn.show_loading("Attraction is being updated..", "it will take a couple of seconds");
					},
					success: function(response)
					{
						if(response.status === "true")
							cur_form.find("input[type='file']").data("href", response.new_data["image_path"]);
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
		}
			
		 
		 
		//---------------------------------ATTRACTION END-----------------------------------------//
		
		
		
		
		
		
		

	});
});
