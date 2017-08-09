// require(["custom_defination", "async!http://maps.google.com/maps/api/js?key=AIzaSyAEjc4IC_8he4zTPM3Gz95Ekm4lOeXXh0w"], function(custom_fn, gmaps)
require(["custom_defination"], function(custom_fn)
{
$(document).ready(function()
{
	//remove attributes on head tag
	$(document).find("head").each(function()
	{
		var attributess = [];
		$.each(this.attributes, function(i, attrs)
		{
			var name = attrs.name;
			$("head").data(name, attrs.value);
			attributess.push(name);
		});
		$(attributess).each(function()
		{
			$("head").removeAttr(attributess.pop());
		});
	});


	// Global variables
	var base_url = $("head").data("base-url");
	var front_url = $("head").data("front-url");
	var asset_url = $("head").data("asset-url");
	var upload_url = $("head").data("upload-url");
	var default_ext = $("head").data("default-ext");
	var current_controller = $("head").data("controller");
	var current_method = $("head").data("method");
	var b2b_user_type = parseInt($("head").data("b2b-user-type"));
	var current_sort_index = 0;
	var current_sort_value = 2;
	var current_sort_length = 1;
	var current_sort_main = 2;
	var contact_markers = [];
	var select2_page_count = 10;
	var map = null;
	var create_contact_marker = null;

	// Global Functions
	$("body").prepend("<div class='wait_loader'></div>");

	$(document).on("click", ".model_template .close_popup", function()
	{
		$("body").find(".model_template").detach();
	});

	$(":file").filestyle();

	$(".navigation_main_menu li.active").parents("ul").addClass("in");
	$(".navigation_main_menu li.active").parents("li").addClass("active");

	// Remove any id stored by PHP from attribute list
	$("[hyperlink]").each(function()
	{
		var href = $(this).attr("hyperlink");
		$(this).data("href", href);
		$(this).removeAttr("hyperlink");
	});

	// Remove any id stored by PHP from attribute list
	$("[data-link]").each(function()
	{
		var link = $(this).attr("data-link");
		$(this).data("link", link);
		$(this).removeAttr("data-link");
	});

	// Remove any togglehref with href
	$("[togglehref]").each(function()
	{
		var href = $(this).attr("togglehref");
		$(this).attr("href", href);
		$(this).removeAttr("togglehref");	
	});

	// Remove any order-by tag and add data
	$("[order-by]").each(function()
	{
		var order_by = $(this).attr("order-by");
		var main_order = $(this).attr("main-order");
		$(this).data("order-by", order_by).data("main-order", main_order);
		$(this).removeAttr("order-by").removeAttr("main-order");	
	});

	//Display datepicker when date icon is clicked
	$(document).on("click", "input + span.input-group-addon", function()
	{
		if($(this).prev().hasClass("today_onwards_limited") || $(this).prev().hasClass("before_date_recent_limited"))
		{
			var visible = $(this).prev().datepicker("widget").is(":visible");
			$(this).prev().datepicker(visible ? "hide" : "show");
		}
	});
	if($(".login_pattern").length > 0)
	{
		new PatternLock(".login_pattern",
		{
			mapper: function(idx)
			{
				$(".patt-holder").css("background", "#0aa89e");
				return (idx%9);
			},
			onDraw:function(pattern)
			{
				$("[name='login_pattern']").val(pattern);
			}
		});
	}

	if(typeof(Storage) !== undefined)
	{
		$list_name = $(".dashboard_menus").data("href");
		// Code for localStorage/sessionStorage.
		$top_headers = localStorage.getItem($list_name);
		if($top_headers === null || $top_headers === "")
		{
			$orders = [];
			$lists = $(".dashboard_menus > div");
			$lists.each(function()
			{
				$orders.push($(this).attr("id"));
			});
			localStorage.setItem($list_name, $orders.join(","));
		}
		else
		{
			$orders = $top_headers.split(",");
			$html_content = "";
			for (var i = 0; i < $orders.length; i++)
			{
				var temp_id = $orders[i];
				if(temp.length !== undefined)
				{
					$html_content += $("#"+temp_id).wrap("<p/>").parent().html();
					$("#"+temp_id).unwrap();
				}
			}
			$(".dashboard_menus").html($html_content);
		}
		
		$(".dashboard_menus").removeClass("hide");

		// Sort
		$(".dashboard_menus").sortable({
			revert: true,
			stop: function()
			{
				localStorage.clear();
				$orders = [];
				$lists = $(".dashboard_menus > div");
				$lists.each(function()
				{
					$orders.push($(this).attr("id"));
				});
				localStorage.setItem($list_name, $orders.join(","));
			}
		});
	}
	else
		$(".dashboard_menus").removeClass("hide");

	//drag nad drop side-menu
	$(".navigation_main_menu").sortable(
	{
		placeholder: "slide_placeholder",
		axis: "y",
		revert: 150,
		start: function(e, ui)
		{
			current_sort_index = ui.item.index();
			current_sort_value = ui.item.data("order-by");
			current_sort_main = ui.item.data("main-order");
			current_sort_length = $(".navigation_main_menu").find("li").filter(function(i, el)
									{
										return $(this).data("order-by") === current_sort_value;
									}).length;
			e.stopPropagation();
			placeholderHeight = ui.item.outerHeight();
			ui.placeholder.height(placeholderHeight);
			$('<div class="slide_placeholder_animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
		},
		change: function(event, ui)
		{
			ui.placeholder.stop().height(0).animate(
			{
				height: ui.item.outerHeight()
			}, 300);
			placeholderAnimatorHeight = parseInt($(".slide_placeholder_animator").attr("data-height"));
			$(".slide_placeholder_animator").stop().height(placeholderAnimatorHeight + 15).animate(
			{
				height: 0
			}, 300, function()
			{
				$(this).remove();
				placeholderHeight = ui.item.outerHeight();
				$('<div class="slide_placeholder_animator" data-height="' + placeholderHeight + '"></div>').insertAfter(ui.placeholder);
			});

		},
		stop: function(e, ui)
		{
			var cur_ui = $(this);
			$(".slide_placeholder_animator").remove();
			if(ui.item.index() === 0 || current_sort_index === 0 || ui.item.index() === current_sort_index)
				cur_ui.sortable('cancel');
			else
			{
				var form_data = new FormData();
				form_data.append("o_from", current_sort_value);
				form_data.append("o_order_length", current_sort_length);
				form_data.append("o_main_from", current_sort_main);
				var o_to = ui.item.prev().data("order-by");
				var o_main = ui.item.prev().data("main-order");
				if(ui.item.index() < current_sort_index)
				{
					o_to = ui.item.next().data("order-by");
					o_main = ui.item.next().data("main-order");
				}
				to_length = $(".navigation_main_menu").find("li").filter(function(i, el)
									{
										return $(this).data("order-by") === o_to;
									}).length;
				form_data.append("o_to", o_to);
				form_data.append("o_main_to", o_main);
				form_data.append("o_to_length", to_length);
				$.ajax(
				{
					url: url,
					method: "POST",
					dataType: "JSON",
					data: form_data,
					processData: false,
					contentType:false,
					beforeSend: function(event)
					{
						$(".navigation_main_menu").sortable("disable");
					},
					success: function(response)
					{
						if(response.status === "true")
						{
							if(ui.item.index() > current_sort_index)
							{
								ui.item.data("order-by", ((ui.item.prev().data("order-by") * 1)));
								if(ui.item.find("li").length > 0)
									ui.item.find("li").data("order-by", ui.item.data("order-by"));
								//ui.item.data("main-order", ((ui.item.prev().data("main-order") * 1)));
								var prev_li = ui.item.prev();
								while(prev_li.index() !== -1 && prev_li.index() >= current_sort_index)
								{
									prev_li.data("order-by", ((prev_li.data("order-by") * 1) - 1));
									if(prev_li.find("li").length > 0)
										prev_li.find("li").data("order-by", prev_li.data("order-by"));
									//prev_li.data("main-order", ((prev_li.data("main-order") * 1) - 1));
									prev_li = prev_li.prev();
								}
							}
							else
							{
								ui.item.data("order-by", ((ui.item.next().data("order-by") * 1)));
								if(ui.item.find("li").length > 0)
									ui.item.find("li").data("order-by", ui.item.data("order-by"));
								//ui.item.data("main-order", ((ui.item.next().data("main-order") * 1)));
								var next_li = ui.item.next();
								while(next_li.index() !== -1 && next_li.index() <= current_sort_index)
								{
									next_li.data("order-by", ((next_li.data("order-by") * 1) + 1));
									if(next_li.find("li").length > 0)
										next_li.find("li").data("order-by", next_li.data("order-by"));
									//next_li.data("main-order", ((next_li.data("main-order") * 1) + 1));
									next_li = next_li.next();
								}
							}
						}
						else
							cur_ui.sortable('cancel');
					},
					error: function(response)
					{
						cur_ui.sortable('cancel');
					}
				}).complete(function()
				{
					$(".navigation_main_menu").sortable("enable");
				});
			}
		}
	});

	$("img.lazyload").lazyload({
			event : "scroll click",
			skip_invisible : true,
			failure_limit : 20,
			effect : "fadeIn"
		});
	$(".input_tags").tagsinput();
	$(".select2").select2();
	$(".toggle_switch").bootstrapSwitch(
	{
		onSwitchChange:function(event, state)
		{
		}
	});
	$(".timepicker").datetimepicker(
	{
		pickDate: false,
		pickSeconds: false 
	});

	$(".before_date_recent").datepicker(
	{
		maxDate : 0
	});

	$(".before_date_recent_limited").datepicker(
	{
		maxDate : 0,
		minDate : -30
	});

	$(".before_date_old").datepicker(
	{
		maxDate : 0,
		changeYear : true,
		changeMonth : true,
		yearRange : "-55 : -10"
	});

	$(".today_onwards_limited").datepicker(
	{
		minDate : 0,
		maxDate : 90
	});

	$(".today_onwards").datepicker(
	{
		minDate : 0
	});


	if($("select.set_metatag_types").length > 0)
	{
		var smt = $("select.set_metatag_types").data("href") !== undefined ? $("select.set_metatag_types").data("href") : "";
		var result = "<option value=''>Select Metatag Type</option>";
		result += "<option value='name'>NAME</option>";
		result += "<option value='http-equiv'>HTTP-EQUIV</option>";
		$("select.set_metatag_types").html(result);
		if(smt !== "")
		{
			$("select.set_metatag_types").val(smt);
			$("select.set_metatag_types").data("href", "");
			$("select.set_metatag_types").change();
		}
		$("select.set_metatag_types").select2();
	}

	if($("select.set_markup_type_user").length > 0)
	{
		var smtu = $("select.set_markup_type_user").data("href") !== undefined ? $("select.set_markup_type_user").data("href") : "";
		var result = "";
		result += "<option value=''>General(All)</option>";
		result += "<option value='3'>B2B</option>";
		result += "<option value='4'>B2C</option>";
		$("select.set_markup_type_user").html(result);
		if(smtu !== "")
		{
			$("select.set_markup_type_user").val(smtu);
			$("select.set_markup_type_user").data("href", "");
			$("select.set_markup_type_user").change();
		}
		$("select.set_markup_type_user").select2();
	}

	/*//non-ajax call to get support subjects list
	if($("select.set_support_subjects").length > 0)
	{
		var sss = $("select.set_support_subjects").data("href") !== undefined ? $("select.set_support_subjects").data("href") : "";
		var url = base_url+"ajax/get_support_subjects"+default_ext;
		var xmlhttp;
		if (window.XMLHttpRequest)// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		else // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		xmlhttp.open("GET",url,true);
		xmlhttp.send();
		xmlhttp.onreadystatechange = function()
									{
										if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
										{
											var response = JSON.parse(xmlhttp.responseText);
											$("select.set_support_subjects").html(response.result);
											if(sss !== "")
											{
												$("select.set_support_subjects").val(sss);
												$("select.set_support_subjects").data("href", "");
												$("select.set_support_subjects").change();
											}
											$("select.set_support_subjects").select2();
										}
										else
										{
											$("select.set_support_subjects").html("");
											$("select.set_support_subjects").select2();
										}
									}
	}*/

	if($("select.set_support_user_type").length > 0)
	{
		var ssut = $("select.set_support_user_type").data("href") !== undefined ? $("select.set_support_user_type").data("href") : "";
		var result = "<option value=''>Select User Type</option>";
		result += "<option value='2'>Admin</option>";
		result += "<option value='3'>B2B</option>";
		result += "<option value='4'>B2C</option>";
		$("select.set_support_user_type").html(result);
		if(ssut !== "")
		{
			$("select.set_support_user_type").val(ssut);
			$("select.set_support_user_type").data("href", "");
			$("select.set_support_user_type").change();
		}
		$("select.set_support_user_type").select2().on("change", function(e)
		{
			if($("select.set_support_users").length > 0)
			{
				var user_type = $(this).val();
				$("select.set_support_users").each(function()
				{
					var ssu = $(this).data("href") !== undefined ? $(this).data("href") : "";
					var url = base_url+"ajax/get_users_by_type"+default_ext;
					var this_var = "#"+$(this).attr("id");
					if(ssu !== "")
					{
						$.ajax(
						{
							type: "GET",
							url: url + "?id=" + ssu,
							dataType: "json"
						}).then(function (data)
						{
							var option = new Option(data.results[0].text, data.results[0].id, true, true);
							$(this_var).html(option).trigger("change");
						});
					}
					$(this_var).select2(
					{
						placeholder: {"id": "", "text": "Select User"},
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
									page: params.page,
									user_type: user_type
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
		});
	}

	if($("select.set_support_subjects").length > 0)
	{
		$("select.set_support_subjects").each(function()
		{
			var sss = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_support_subjects"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(sss !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + sss,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Subject"},
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
			})
		});
	}

	if($("select.set_acc_type").length > 0)
	{
		$("select.set_acc_type").each(function()
		{
			var sact = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/b2b_acc_types"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(sact !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + sact,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
					if($(".credit_limit_div").length > 0)
					{
						if(data.results[0].limit_required === "1")
						{
							$(".credit_limit_div").removeClass("hide");
							$(".credit_limit_div").find("input[type='text']").removeAttr("disabled");
						}
						else
						{
							$(".credit_limit_div").addClass("hide");
							$(".credit_limit_div").find("input[type='text']").attr("disabled", "true");
						}
					}
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Account Type"},
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
			}).on("select2:select", function(option)
			{
				if($(".credit_limit_div").length > 0)
				{
					if(option.params.data.limit_required === "1")
					{
						$(".credit_limit_div").removeClass("hide");
						$(".credit_limit_div").find("input[type='text']").removeAttr("disabled");
					}
					else
					{
						$(".credit_limit_div").addClass("hide");
						$(".credit_limit_div").find("input[type='text']").attr("disabled", "true");
					}
				}
			});
		});
	}

	if($("select.static_page_types").length > 0)
	{
		$("select.static_page_types").each(function()
		{
			var spt = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+current_controller+"/get_page_types"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(spt !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + spt,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Page Type"},
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

	if($("select.set_airlines").length > 0)
	{
		$("select.set_airlines").each(function()
		{
			var sal = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_airlines"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(sal !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + sal,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Airline"},
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

	if($("select.set_airports").length > 0)
	{
		$("select.set_airports").each(function()
		{
			var sap = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_airports"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(sap !== "")
			{
				$.ajax(
				{ // make the request for the selected data object
					type: "GET",
					url: url + "?id=" + sap,
					dataType: "json"
				}).then(function (data)
				{
					// Here we should have the data object
					var option = new Option(data.results[0].text, data.results[0].id, true, true); // update the text that is displayed (and maybe even the value)
					$(this_var).html(option).trigger("change"); // notify JavaScript components of possible changes
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Airport"},
				// allowClear: true,
				// tags: "true",
				// selectOnClose: true, // Select highlighted option on close
				// dropdownParent: $("#parent_modal"), // Parent where list need to display
				// dir: "rtl", // direction
				// data: array_data, // Load from array
				// maximumSelectionLength: 2, // Limit for multiple select
				// minimumResultsForSearch: Infinity, // Hide search box permanently
				// tokenSeparators: [","], // consider seperate values on multiple tags
				// language: "en",
				// theme: "classic",
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
							search: params.term,	// search term
							page: params.page		// page number
						};
					},
					processResults: function(data, params)
					{
						// parse the results into the format expected by Select2
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data, except to indicate that infinite
						// scrolling can be used
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
			});
		});
	}

	if($("select.set_currency").length > 0)
	{
		$("select.set_currency").each(function()
		{
			var scu = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_currencies"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(scu !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + scu,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Currency"},
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
			})
		});
	}

	if($("select.set_lang_page").length > 0)
	{
		$("select.set_lang_page").each(function()
		{
			var slp = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+current_controller+"/get_page_list"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(slp !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + slp,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Page"},
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

	if($("select.unset_currency_country").length > 0)
	{
		$("select.unset_currency_country").each(function()
		{
			var ucc = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_unset_currency_countries"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(ucc !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + ucc,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Country"},
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

	if($("select.set_country").length > 0)
	{
		$("select.set_country").each(function()
		{
			var sco = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_countries"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(sco !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + sco,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
					if($("input[name='country_name']").length > 0)
						$("input[name='country_name']").val(data.results[0].text);
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Country"},
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
			}).on("select2:select", function(option)
			{
				if($("input[name='country_name']").length > 0 && option.params.data.id !== "")
					$("input[name='country_name']").val(option.params.data.text).data("href", option.params.data.text);
			}).on("change", function(option)
			{
				var country = $(this).val();
				if(country !== "")
				{
					if($("select.set_city").length > 0)
						$("select.set_city").val("").trigger("change");

					if($("select.set_state").length > 0)
					{
						$("select.set_state").each(function()
						{
							var sst = $(this).data("href") !== undefined ? $(this).data("href") : "";
							var url = base_url+"ajax/get_states"+default_ext;
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
									if($("input[name='state_name']").length > 0)
										$("input[name='state_name']").val(data.results[0].text);
								});
							}
							$(this_var).select2(
							{
								placeholder: {"id": "", "text": "Select State / Region"},
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
											page: params.page,
											country: country
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
								if($("input[name='state_name']").length > 0 && option.params.data.id !== "")
									$("input[name='state_name']").val(option.params.data.region_name).data("href", option.params.data.region_name);
							}).on("change", function(e)
							{
								if($("select.set_city").length > 0)
								{
									var region = $(this).val();
									if(region !== "")
									{
										$("select.set_city").each(function()
										{
											var sci = $(this).data("href") !== undefined ? $(this).data("href") : "";
											var url = base_url+"ajax/get_cities"+default_ext;
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
															page: params.page,
															region: region
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
							});
						});
					}
					else if($("select.unassigned_regions").length > 0)
					{
						$("select.unassigned_regions").each(function()
						{
							var uar = $(this).data("href") !== undefined ? $(this).data("href") : "";
							var url = base_url+"ajax/get_unassigned_regions"+default_ext;
							var this_var = "#"+$(this).attr("id");
							if(uar !== "")
							{
								var option = new Option(uar, uar, true, true);
								$(this_var).html(option).trigger("change");
							}
							$(this_var).select2(
							{
								placeholder: {"id": "", "text": "Select State / Region Code"},
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
											page: params.page,
											country: country,
											default_region : uar
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
							});
						});
					}
				}
			});
		});
	}

	if($("select.set_agents").length > 0)
	{
		$("select.set_agents").each(function()
		{
			var s_a = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_b2b_users"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(s_a !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + s_a,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Search B2B Agent"},
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

	if($("select.set_markup_types").length > 0)
	{
		$("select.set_markup_types").each(function()
		{
			var smut = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_markup_types"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(smut !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + smut,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
					var type = data.results[0].user_type;
					var cur_form = $(this).closest("form");
					cur_form.data("user_type", type);
					cur_form.find(".optional_values").addClass("hide").find("input, select").attr("disabled", "true");
					if(type !== "")
					{
						cur_form.find(".optional_values.common").removeClass("hide").find("input, select").removeAttr("disabled");
						if(parseInt(type) === b2b_user_type)
							cur_form.find(".optional_values.b2b").removeClass("hide").find("input, select").removeAttr("disabled");
					}
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Markup Type"},
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
			}).on("select2:select", function(option)
			{
				var type = option.params.data.user_type;
				var cur_form = $(this).closest("form");
				cur_form.data("user_type", type);
				cur_form.find(".optional_values").addClass("hide").find("input, select").attr("disabled", "true");
				if(type !== "")
				{
					cur_form.find(".optional_values.common").removeClass("hide").find("input, select").removeAttr("disabled");
					if(parseInt(type) === b2b_user_type)
						cur_form.find(".optional_values.b2b").removeClass("hide").find("input, select").removeAttr("disabled");
				}
			});
		});
	}

	if($("select.set_payment_gateway").length > 0)
	{
		$("select.set_payment_gateway").each(function()
		{
			var dpgc = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_payment_gateways"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(dpgc !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + dpgc,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
					var next_div = $(this).closest(".form-group").next();
					if(data.results[0].pay_mode === "2")
						next_div.removeClass("hide");
					else
					{
						next_div.addClass("hide");
						next_div.find("[value='"+data.results[0].pay_mode+"']").click();
					}
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Payment Gateway"},
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
			}).on("select2:select", function(option)
			{
				var next_div = $(this).closest(".form-group").next();
				if(option.params.data.pay_mode === "2")
					next_div.removeClass("hide");
				else
				{
					next_div.addClass("hide");
					next_div.find("[value='"+option.params.data.pay_mode+"']").click();
				}
			});
		});
	}

	if($("select.set_taxes").length > 0)
	{
		$("select.set_taxes").each(function()
		{
			var stx = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_taxes"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(stx !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + stx,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
					var next_div = $(this).closest(".form-group").next();
					if(data.results[0].pay_mode === "2")
						next_div.removeClass("hide");
					else
					{
						next_div.addClass("hide");
						next_div.find("[value='"+data.results[0].pay_mode+"']").click();
					}
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select Taxes"},
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
			}).on("select2:select", function(option)
			{
				var next_div = $(this).closest(".form-group").next();
				if(option.params.data.pay_mode === "2")
					next_div.removeClass("hide");
				else
				{
					next_div.addClass("hide");
					next_div.find("[value='"+option.params.data.pay_mode+"']").click();
				}
			});
		});
	}

	if($("select.set_apis").length > 0)
	{
		$("select.set_apis").each(function()
		{
			var sapi = $(this).data("href") !== undefined ? $(this).data("href") : "";
			var url = base_url+"ajax/get_apis"+default_ext;
			var this_var = "#"+$(this).attr("id");
			if(sapi !== "")
			{
				$.ajax(
				{
					type: "GET",
					url: url + "?id=" + sapi,
					dataType: "json"
				}).then(function (data)
				{
					var option = new Option(data.results[0].text, data.results[0].id, true, true);
					$(this_var).html(option).trigger("change");
				});
			}
			$(this_var).select2(
			{
				placeholder: {"id": "", "text": "Select API"},
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
	
	function set_api_type()
	{
		if($("select.set_api_types").length > 0)
		{
			$("select.set_api_types").each(function()
			{
				var sat = $(this).data("href") !== undefined ? $(this).data("href") : "";
				var url = base_url+"ajax/get_api_types"+default_ext;
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
					placeholder: {"id": "", "text": "Select API Type"},
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

	if($("form.login_admin").length > 0)
		custom_fn.load_validate("login_admin");
	if($("form.forgot_pass").length > 0)
		custom_fn.load_validate("forgot_pass");

	if($("form.update_profile_form").length > 0)
		custom_fn.load_validate("update_profile_form");
	if($("form.update_email_setting_form").length > 0)
		custom_fn.load_validate("update_email_setting_form");

	if($("form.add_admin_form").length > 0)
		custom_fn.load_validate("add_admin_form");
	if($("form.update_admin_form").length > 0)
		custom_fn.load_validate("update_admin_form");

	if($("form.add_b2c_user_form").length > 0)
		custom_fn.load_validate("add_b2c_user_form");
	if($("form.update_b2c_user_form").length > 0)
		custom_fn.load_validate("update_b2c_user_form");

	if($("form.add_b2b_user_form").length > 0)
		custom_fn.load_validate("add_b2b_user_form");
	if($("form.update_b2b_user_form").length > 0)
		custom_fn.load_validate("update_b2b_user_form");


	if($("form.add_markup_type_form").length > 0)
		custom_fn.load_validate("add_markup_type_form");
	if($("form.update_markup_type_form").length > 0)
		custom_fn.load_validate("update_markup_type_form");

	if($("form.add_markup_form").length > 0)
		custom_fn.load_validate("add_markup_form");
	if($("form.update_markup_form").length > 0)
		custom_fn.load_validate("update_markup_form");

	if($("form.add_privilege_form").length > 0)
		custom_fn.load_validate("add_privilege_form");
	if($("form.update_privilege_form").length > 0)
		custom_fn.load_validate("update_privilege_form");

	if($("form.add_percent_promo_form").length > 0)
		custom_fn.load_validate("add_percent_promo_form");
	if($("form.add_cash_promo_form").length > 0)
		custom_fn.load_validate("add_cash_promo_form");
	if($("form.send_promocode_form").length > 0)
		custom_fn.load_validate("send_promocode_form");

	if($("form.add_payment_gateway_form").length > 0)
		custom_fn.load_validate("add_payment_gateway_form");
	if($("form.update_payment_gateway_form").length > 0)
		custom_fn.load_validate("update_payment_gateway_form");

	if($("form.add_pg_charges_form").length > 0)
		custom_fn.load_validate("add_pg_charges_form");
	if($("form.update_pg_charges_form").length > 0)
		custom_fn.load_validate("update_pg_charges_form");

	if($("form.add_taxes_form").length > 0)
		custom_fn.load_validate("add_taxes_form");
	if($("form.update_taxes_form").length > 0)
		custom_fn.load_validate("update_taxes_form");

	if($("form.add_tax_charges_form").length > 0)
		custom_fn.load_validate("add_tax_charges_form");
	if($("form.update_tax_charges_form").length > 0)
		custom_fn.load_validate("update_tax_charges_form");

	if($("form.add_api_type_form").length > 0)
		custom_fn.load_validate("add_api_type_form");

	if($("form.add_currency_form").length > 0)
		custom_fn.load_validate("add_currency_form");
	if($("form.update_currency_form").length > 0)
		custom_fn.load_validate("update_currency_form");

	if($("form.user_sendmail_form").length > 0)
		custom_fn.load_validate("user_sendmail_form");

	if($("form.add_country_form").length > 0)
		custom_fn.load_validate("add_country_form");
	if($("form.update_country_form").length > 0)
		custom_fn.load_validate("update_country_form");

	if($("form.add_seo_form").length > 0)
		custom_fn.load_validate("add_seo_form");
	if($("form.update_seo_form").length > 0)
		custom_fn.load_validate("update_seo_form");

	if($("form.add_analytics_form").length > 0)
		custom_fn.load_validate("add_analytics_form");
	if($("form.update_analytics_form").length > 0)
		custom_fn.load_validate("update_analytics_form");

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

	if($("form.add_st_subject_form").length > 0)
		custom_fn.load_validate("add_st_subject_form");

	if($("form.add_support_ticket_form").length > 0)
		custom_fn.load_validate("add_support_ticket_form");

	if($("form.add_email_template_form").length > 0)
		custom_fn.load_validate("add_email_template_form");
	if($("form.update_email_template_form").length > 0)
		custom_fn.load_validate("update_email_template_form");

	if($("form.add_lang_page_label_form").length > 0)
		custom_fn.load_validate("add_lang_page_label_form");
	if($("form.update_lang_page_label_form").length > 0)
		custom_fn.load_validate("update_lang_page_label_form");

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


	if($("form.add_slider_image_form").length > 0)
		custom_fn.load_validate("add_slider_image_form");
	if($("form.update_slider_image_form").length > 0)
		custom_fn.load_validate("update_slider_image_form");

	if($("form.add_deposit_form").length > 0)
		custom_fn.load_validate("add_deposit_form");
	if($("form.add_credit_form").length > 0)
		custom_fn.load_validate("add_credit_form");

	$.validator.addMethod("prince", (function(value, element)
	{
		return value === "prince";
	}), "Please enter \"prince\"!");
	$.validator.addMethod("landline", function(value, element)
	{
		return this.optional(element) || /^[0-9\-]+$/.test(value);
	}, "Please enter a valid landline number.");
	
	$.validator.methods.equal = function(value, element, param) {
		return value === param;
	};

	$(document).on("change", "input[type='file']", function()
	{
		if($(".preview_img").length > 0)
			custom_fn.preview_img(this);
	});


	//Common functionalities on multiple users



	//popup window with active promocodes
	$(document).on("click", "a.user_promocode", function()
	{
		var current_user = $(this).hasClass("b2b") ? "b2b" : ($(this).hasClass("b2c") ? "b2c" : "subscriber");
		var email = $(this).data("email");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("send_promocode_template");
		$(".send_promocode_template").find("form").addClass("user_promocode_form").data("user", $(this).data("href")).data("user_type", current_user);
		if(current_user === "b2b")
			$(".b2c_promocode_template").find("h4.modal-title").text("B2B Management - Send Promocode");
		else if(current_user === "b2c")
			$(".b2c_promocode_template").find("h4.modal-title").text("B2C Management - Send Promocode");
		else
			$(".send_promocode_template").find("h4.modal-title").text("Subscriber Management - Send Promocode");
		$(".send_promocode_template").find("button[type='submit']").html("Send");
		var url = base_url+"ajax/active_promocodes_form_html"+default_ext;
		$.ajax(
		{
			url: url,
			method: "GET",
			dataType: "JSON",
			processData: false,
			contentType:false,
			beforeSend: function()
			{
				//show popup
				custom_fn.show_loading("Promocodes are being loaded..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "true")
				{
					$(".send_promocode_template").find("div.modal-body").css({"width" : "100%", "overflow" : "auto", "height" : "200px"}).html(response.msg);
					$(".send_promocode_template").toggle();
					custom_fn.load_validate("user_promocode_form");
				}
				else
				{
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(5000);
				}
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
				custom_fn.set_auto_close(5000);
			}
		});
	});


	// send promocode form
	$(document).on("submit", "form.user_promocode_form", function()
	{
		if($(this).find("[type='radio'].valid").length  > 0)
		{
			var url = base_url+current_controller+"/promocode"+default_ext;
			var cur_form = $(this);
			var user_type = cur_form.data("user_type");
			var form_data = new FormData(cur_form[0]);
			form_data.append("user", cur_form.data("user"));
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
					if(user_type === "b2b")
						custom_fn.show_loading("Promocode is being sent to b2b user..", "it will take a couple of seconds");
					else if(user_type === "b2c")
						custom_fn.show_loading("Promocode is being sent to b2c user..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Promocode is being sent to subscriber..", "it will take a couple of seconds");
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

	//send mail form
	$(document).on("submit", "form.user_sendmail_form", function()
	{
		var user_type = $(this).hasClass("b2b") ? "b2b" : ($(this).hasClass("b2c") ? "b2c" : "subscriber");
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
					if(user_type === "b2b")
						custom_fn.show_loading("Mail is being sent to b2b user..", "it will take a couple of seconds");
					else if(user_type === "b2c")
						custom_fn.show_loading("Mail is being sent to b2c user..", "it will take a couple of seconds");
					else
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




	// Login Page

	//Forgot Password
	$(document).on("click", ".forgot_password", function(e)
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("forgot_password_template");
		$(".forgot_password_template").find("h4.modal-title").text("Forgot Password");
		var formdata = "<div class='form-group'>";
		formdata += "<label class='control-label col-sm-5' for='forgot_email'>Enter your email address</label>";
		formdata += "<div class='col-sm-7 controls'>";
		formdata += "<input class='form-control' data-rule-required='true' id='forgot_email' name='forgot_email' placeholder='Email Address' type='text' data-rule-email='true'>";
		formdata += "</div>";
		formdata += "</div>";
		$(".forgot_password_template").find("div.modal-body").html(formdata);
		$(".forgot_password_template").find("button[type='submit']").html("Send Email");
		$(".forgot_password_template").toggle();
		$(".forgot_password_template").find("form").addClass("send_forgot_pass_mail_form");
		custom_fn.load_validate("send_forgot_pass_mail_form");
	});

	// enter email address (forgot password)
	$(document).on("submit", "form.send_forgot_pass_mail_form", function(e)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			var url = base_url+current_controller+"/forgot_password"+default_ext;
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
					//show popup
					custom_fn.show_loading("Verification code is being sent to mentioned email..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form.children("div:eq(0)").slideUp("slow", function()
						{
							var formdata = "<div class='form-group'>";
							formdata += "<label class='control-label col-sm-5' for='verification_code'>Enter Verification Code</label>";
							formdata += "<div class='col-sm-7 controls'>";
							formdata += "<input class='form-control' data-rule-minlength='6' data-rule-required='true' id='verification_code' name='verify' placeholder='Verification Code' type='text'>";
							formdata += "</div>";
							formdata += "</div>";
							formdata += "<div class='form-group'>";
							formdata += "<label class='control-label col-sm-5' for='validation_password'>New Password</label>";
							formdata += "<div class='col-sm-7 controls'>";
							formdata += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'>";
							formdata += "</div>";
							formdata += "</div>";
							formdata += "<div class='form-group'>";
							formdata += "<label class='control-label col-sm-5' for='validation_password_confirmation'>Password confirmation</label>";
							formdata += "<div class='col-sm-7 controls'>";
							formdata += "<input class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'>";
							formdata += "</div>";
							formdata += "</div>";
							cur_form.children("div:eq(0)").html(formdata);
							cur_form.children("div:eq(0)").slideDown("slow");
							cur_form.removeClass("send_forgot_pass_mail_form").data("admin", response.admin).addClass("reset_password_form").data("admin", response.id);
							$("form.reset_password_form").validate();
						});
					}
					else
					{
						var formdata = "<div class='alert alert-block alert-dismissable alert-"+response.msg_status+" forgot_password_msg'>";
						formdata += "<a href='javascript:void(0)' data-dismiss='alert' class='notify_close close'>×</a>";
						formdata += "<h4 class='alert-heading'>"+response.title+"</h4>"+response.msg+"</div>";
						cur_form.find("div:eq(0)").find(".forgot_password_msg").detach();
						cur_form.find("div:eq(0)").append(formdata)
						setTimeout(function(){cur_form.find(".forgot_password_msg .notify_close").click()}, 4000);
					}
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

	// verify code and new password
	$(document).on("submit", "form.reset_password_form", function(e)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			var url = base_url+current_controller+"/reset_password"+default_ext;
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("admin", cur_form.data("admin"));
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
					custom_fn.show_loading("verifying and updating password..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form.children("div:eq(0)").slideUp("slow", function()
						{
							var formdata = "<div class='alert alert-block alert-dismissable alert-"+response.msg_status+" forgot_password_msg'>";
							formdata += "<h4 class='alert-heading'>"+response.title+"</h4>"+response.msg+"</div>";
							cur_form.find("div:eq(0)").find(".forgot_password_msg").detach();
							cur_form.find("button[type='submit']").detach();
							cur_form.children("div:eq(0)").html(formdata);
							cur_form.children("div:eq(0)").slideDown("slow");
						});
					}
					else
					{
						var formdata = "<div class='alert alert-block alert-dismissable alert-"+response.msg_status+" forgot_password_msg'>";
						formdata += "<a href='javascript:void(0)' data-dismiss='alert' class='notify_close close'>×</a>";
						formdata += "<h4 class='alert-heading'>"+response.title+"</h4>"+response.msg+"</div>";
						cur_form.find("div:eq(0)").find(".forgot_password_msg").detach();
						cur_form.find("div:eq(0)").append(formdata)
						setTimeout(function(){cur_form.find(".forgot_password_msg .notify_close").click()}, 4000);
					}
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

	// End of Login Module Functionalities



	// Settings and Profile updates

	// Change current password popup
	$(document).on("click", "a.change_my_pwd", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("change_my_pwd_template");
		$(".change_my_pwd_template").find("h4.modal-title").text("My Profile - Change Password");
		var formdata = "<div class='form-group'>";
		formdata += "<label class='control-label col-sm-5' for='my_password'>Enter Current Password</label>";
		formdata += "<div class='col-sm-7 controls'>";
		formdata += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='my_password' name='cur_pass' placeholder='Current Password' type='password'>";
		formdata += "</div>";
		formdata += "</div>";
		formdata += "<div class='form-group'>";
		formdata += "<label class='control-label col-sm-5' for='validation_password'>New Password</label>";
		formdata += "<div class='col-sm-7 controls'>";
		formdata += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'>";
		formdata += "</div>";
		formdata += "</div>";
		formdata += "<div class='form-group'>";
		formdata += "<label class='control-label col-sm-5' for='validation_password_confirmation'>Password confirmation</label>";
		formdata += "<div class='col-sm-7 controls'>";
		formdata += "<input class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'>";
		formdata += "</div>";
		formdata += "</div>";
		$(".change_my_pwd_template").find("div.modal-body").html(formdata);
		$(".change_my_pwd_template").find("button[type='submit']").html("Change");
		$(".change_my_pwd_template").toggle();
		$(".change_my_pwd_template").find("form").addClass("change_my_pwd_form");
		custom_fn.load_validate("change_my_pwd_form");
	});

	
	// confirm update password
	$(document).on("submit", "form.change_my_pwd_form", function(e)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			var url = base_url+"home/update_pwd"+default_ext;
			var form_data = new FormData($(this)[0]);
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
					custom_fn.show_loading("Your password is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(7000);
					if(response.status === "true")
					setTimeout(function(){window.location.reload();}, 5000);
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

	// Update profile
	$(document).on("submit", "form.update_profile_form", function(submit_event)
	{
		if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("admin", cur_form.data("href"));
			url = base_url+"home/profile"+default_ext;
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
				custom_fn.show_loading("Profile is being updated..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				
				$(".wait_loader").hide()
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

	// Update Email Settings
	$(document).on("submit", "form.update_email_setting_form", function(e)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			var url = base_url+"home/settings"+default_ext;
			var form_data = new FormData($(this)[0]);
			form_data.append("id", $(this).data("href"));
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
					custom_fn.show_loading("Email settings is being updated..", "it will take a couple of seconds");
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




	// End of Settings and Profile updates


	// Admin Management module functionalities

	// Display all admin list
	if($("table.manage_admins").length > 0)
		$("table.manage_admins").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/get_admin_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5]}
							// 	{"bVisible": false, "aTargets": [2]},
							// 	{ // merge email and company into this column
							// 		"mRender": function(data, type, column) {
							// 			return data +" "+ column[2];
							// 		},
							// 		"aTargets": [ 1 ]
							// }
							],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_adm = JSON.parse(aData[0]);
				var adm_name = jd_m_adm.fname+" "+jd_m_adm.lname;
				$("td:eq(0)", nRow).html(jd_m_adm.sl_no);
				$("td:eq(1)", nRow).html(adm_name);
				$("td:eq(2)", nRow).html(jd_m_adm.email);
				$("td:eq(3)", nRow).html(jd_m_adm.contact);
				$("td:eq(4)", nRow).html(jd_m_adm.status);
				$("td:eq(5)", nRow).html(jd_m_adm.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_adm.id).data("name", adm_name).data("email", jd_m_adm.email);
			}
		}).fnSetFilteringDelay(2000);

	//display required message for privileges
	$(document).on("click", "input[name='privilege[]']", function()
	{
		if($("input[name='privilege[]']:checked").length > 0)
			$(".select_privilege_err").html("");
		else
			$(".select_privilege_err").html("Select at-least one privilege.");
	});

	// Add new Admin
	$(document).on("submit", "form.add_admin_form", function(submit_event)
	{
		if(($(this).find("input[name][type='text']").length + $(this).find("input[name][type='password']").length) === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='password']").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("input[type='checkbox']:checked").length > 0)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			url = base_url+current_controller+"/add_admin"+default_ext;
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
					custom_fn.show_loading("Admin account is being created..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form[0].reset();
						$("select").html("").trigger("change");
					}
					else if(response.status === "exist")
					{
						cur_form.find("input[name='emailid']").val("").focus();
						cur_form.find("input[type='password']").val("");
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

	// Activate or De-activate admin account
	$(document).on("click", "input[type='checkbox'].admin_status", function(click_event)
	{
		click_event.preventDefault();
		click_event.stopPropagation();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("admin", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/admin_status"+default_ext;
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
					custom_fn.show_loading("Admin account is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Admin account is being activated..", "it will take a couple of seconds");
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

	// Edit Admin Details
	$(document).on("submit", "form.update_admin_form", function(submit_event)
	{
		if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("input[type='checkbox']:checked").length > 0)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("admin", cur_form.data("href"));
			url = base_url+current_controller+"/edit_admin"+default_ext;
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
				custom_fn.show_loading("Admin account is being updated..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				
				$(".wait_loader").hide()
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

	// Change other admins password popup
	$(document).on("click", "a.change_pwd_admin", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("admin_change_pwd_template");
		$(".admin_change_pwd_template").find("h4.modal-title").text("Admin Management - Change Password");
		$(".admin_change_pwd_template").find("div.modal-body").html("<div class='form-group'><label class='control-label col-sm-5' for='my_password'>Enter Your Password</label><div class='col-sm-7 controls'><input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='my_password' name='adm_pass' placeholder='Your Password' type='password'></div></div><div class='form-group'><label class='control-label col-sm-5' for='validation_password'>New Password</label><div class='col-sm-7 controls'><input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'></div></div><div class='form-group'><label class='control-label col-sm-5' for='validation_password_confirmation'>Password confirmation</label><div class='col-sm-7 controls'><input class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'></div></div><br>");
		$(".admin_change_pwd_template").find("button[type='submit']").html("Change");
		$(".admin_change_pwd_template").toggle();
		$(".admin_change_pwd_template").find("form").addClass("admin_change_pwd_form").data("admin", $(this).data("href"));
		custom_fn.load_validate("admin_change_pwd_form");
	});

	// confirm change password of other admins
	$(document).on("submit", "form.admin_change_pwd_form", function(e)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			var url = base_url+current_controller+"/update_pwd"+default_ext;
			var form_data = new FormData($(this)[0]);
			form_data.append("admin", $(this).data("admin"));
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
					custom_fn.show_loading("Admin password is being updated..", "it will take a couple of seconds");
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

	//pop-up window to confirm message to delete admin
	$(document).on("click", "a.delete_admin", function()
	{
		var name = $(this).data("name")+" ["+$(this).data("email")+"]";
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("admin_delete_template");
		$(".admin_delete_template").find("h4.modal-title").text("Admin Management - Delete Admin");
		$(".admin_delete_template").find("button[type='submit']").data("admin", $(this).data("href"));
		$(".admin_delete_template").find("form").addClass("admin_delete_form").data("admin", $(this).data("href"));
		$(".admin_delete_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' will be completely lost if you continue.</li><li>You can deactivate '"+name+"' account if you want to keep details.</li></ul><br>Are you sure to completely delete '"+name+"' account?");
		$(".admin_delete_template").find("button[type='submit']").html("Continue");
		$(".admin_delete_template").toggle();
	});

	// delete admin-confirmed
	$(document).on("submit", "form.admin_delete_form", function()
	{
		var url = base_url+current_controller+"/delete"+default_ext;
		var admin = $(this).data("admin");
		var form_data = new FormData();
		form_data.append("admin", admin);
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
				custom_fn.show_loading("Admin account is being removed..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{
					var cur_row = $("body").find("a.delete_admin").filter(function(i, el)
									{
										return $(this).data("href") === admin;
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
	
	// End of Admin management

	// Privileges Module Functionalities

	//Display all privileges
	if($("table.manage_privileges").length > 0)
		$("table.manage_privileges").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/privilege_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 5, 6, 7, 8, 9, 10]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_privlg = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_privlg.sl_no);
				$("td:eq(1)", nRow).html(jd_m_privlg.controller);
				$("td:eq(2)", nRow).html(jd_m_privlg.icon_html);
				$("td:eq(3)", nRow).html(jd_m_privlg.name);
				$("td:eq(4)", nRow).html(jd_m_privlg.url);
				$("td:eq(5)", nRow).html(jd_m_privlg.menu_type);
				$("td:eq(6)", nRow).html(jd_m_privlg.order_by);
				$("td:eq(7)", nRow).html(jd_m_privlg.sub_menu_order);
				$("td:eq(8)", nRow).html(jd_m_privlg.is_avail_to_admin);
				$("td:eq(9)", nRow).html(jd_m_privlg.status_html);
				$("td:eq(10)", nRow).html(jd_m_privlg.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_privlg.id).data("name", jd_m_privlg.name);
			}
		}).fnSetFilteringDelay(2000);

	// Privilege available to admin or change status of privilege or delete privilege
	$(document).on("click", "input[type='checkbox'].privilege_visible_to_admin, input[type='checkbox'].privilege_status", function(click_event)
	{
		click_event.preventDefault();
		click_event.stopPropagation();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var op_type = "available";
		if(cur_var.hasClass("privilege_visible_to_admin"))
			op_type = "available";
		else if(cur_var.hasClass("privilege_status"))
			op_type = "status";
		var form_data = new FormData();
		form_data.append("type", op_type);
		form_data.append("privilege", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/privilege_status"+default_ext;
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
				custom_fn.show_loading("Privilege is being updated..", "it will take a couple of seconds");
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

	// show preview of icon selected for privileges
	$(document).on("blur", "input[name].prev_privilege_icon", function()
	{
		var class_icon = "icon-"+$(this).val();
		$(this).parent().next().find("i").removeAttr("class").addClass(class_icon);
	});

	//toggle submenu order by depending on type of menu
	$(document).on("click", ".privilege_menu_types", function()
	{
		if($(this).val() === "0")
			$("input[name].submenu_order_by").attr("disabled", "disabled")
		else
			$("input[name].submenu_order_by").removeAttr("disabled")
	});

	//add new privilege
	$(document).on("submit", "form.add_privilege_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === ($(this).find("input[type='text'].valid").length + $(this).find("input[type='text']:disabled").length))
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			url = base_url+current_controller+"/add"+default_ext;
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
					custom_fn.show_loading("Privilege is being created..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form[0].reset();
						cur_form.find(".submenu_order_by").attr("disabled", "disabled");
						cur_form.find("input[name].prev_privilege_icon").parent().next().find("i").removeAttr("class").removeAttr("class")
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

	//update privilege (un-finished)
	$(document).on("submit", "form.update_privilege_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === ($(this).find("input[type='text'].valid").length + $(this).find("input[type='text']:disabled").length))
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("privilege", cur_form.data("href"));
			url = base_url+current_controller+"/edit"+default_ext;
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
					custom_fn.show_loading("Privilege is being updated..", "it will take a couple of seconds");
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

	//popup window to confirm delete privilege 
	$(document).on("click", "a.delete_privilege", function()
	{
		var name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_privilege_template");
		$(".delete_privilege_template").find("h4.modal-title").text("Manage Privileges - Delete Privilege");
		$(".delete_privilege_template").find("form").addClass("delete_privilege_form").data("privilege", $(this).data("href"));
		$(".delete_privilege_template").find("div.modal-body").html("<ul><li>If you are deleting main privilege, subprivileges under that privilege will also be deleted.</li><li>Information of '"+name+"' privilege will be completely lost if you continue.</li><li>You cannot access '"+name+"' privilege again.</li></ul><br>Are you sure to delete '"+name+"' privilege?");
		$(".delete_privilege_template").find("button[type='submit']").html("Continue");
		$(".delete_privilege_template").toggle();
	});

	// delete privilege
	$(document).on("submit", "form.delete_privilege_form", function()
	{
		var url = base_url+current_controller+"/delete"+default_ext;
		var privilege = $(this).data("privilege");
		var form_data = new FormData();
		form_data.append("privilege", privilege);
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
				custom_fn.show_loading("Privilege is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
					setTimeout(function(){window.location.reload();}, 5000);
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
	

	//End of Privilege Module Functionalities


	// B2C Module Functionalities

	
	if($("table.manage_b2c_users").length > 0)
		$("table.manage_b2c_users").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/users_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [6, 7]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_b2c = JSON.parse(aData[0]);
				var b2c_name = jd_m_b2c.fname+" "+jd_m_b2c.lname;
				$("td:eq(0)", nRow).html(jd_m_b2c.user_id);
				$("td:eq(1)", nRow).html(jd_m_b2c.email);
				$("td:eq(2)", nRow).html(jd_m_b2c.image_html);
				$("td:eq(3)", nRow).html(b2c_name);
				$("td:eq(4)", nRow).html(jd_m_b2c.contact);
				$("td:eq(5)", nRow).html(jd_m_b2c.registered);
				$("td:eq(6)", nRow).html(jd_m_b2c.status_html);
				$("td:eq(7)", nRow).html(jd_m_b2c.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_b2c.id).data("name", b2c_name);
			}
		}).fnSetFilteringDelay(2000);


	// Add new b2c user
	$(document).on("submit", "form.add_b2c_user_form", function(submit_event)
	{
		if(($(this).find("input[name][type='text']").length + $(this).find("input[name][type='password']").length) === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='password']").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			url = base_url+current_controller+"/add"+default_ext;
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
					custom_fn.show_loading("B2C user account is being created..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form[0].reset();
						cur_form.find("img").attr("src", cur_form.find("input[type='file']").data("href"));
						$("select").html("").trigger("change");
					}
					else if(response.status === "exist")
					{
						cur_form.find("input[name='emailid']").val("").focus();
						cur_form.find("input[type='password']").val("");
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

	// Change b2c user account status
	$(document).on("click", "input[type='checkbox'].b2c_user_status", function(click_event)
	{
		click_event.preventDefault();
		click_event.stopPropagation();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("b2c", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/b2c_user_status"+default_ext;
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
					custom_fn.show_loading("B2C user account is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("B2C user account is being activated..", "it will take a couple of seconds");
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

	// update b2c user
	$(document).on("submit", "form.update_b2c_user_form", function(submit_event)
	{
		if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("current_image_path", cur_form.find("input[type='file']").data("href"));
			form_data.append("user", cur_form.data("href"));
			url = base_url+current_controller+"/edit_profile"+default_ext;
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
					custom_fn.show_loading("B2C user account is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true" && response.image_path !== undefined)
						cur_form.find("input[type='file']").data("href", response.image_path);
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

	//popup window to confirm delete b2c user 
	$(document).on("click", "a.delete_b2c_user", function()
	{
		var b2c_user_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_b2c_user_template");
		$(".delete_b2c_user_template").find("h4.modal-title").text("B2C Management - Delete User");
		$(".delete_b2c_user_template").find("form").addClass("delete_b2c_user_form").data("user", $(this).data("href"));
		var form_data = "<ul>";
		form_data += "<li>Information of B2C user '"+b2c_user_name+"' will be completely lost if you continue.</li>";
		form_data += "<li>You can deactivate '"+b2c_user_name+"' account if you want to keep details.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to completely delete '"+b2c_user_name+"' account?";
		$(".delete_b2c_user_template").find("div.modal-body").html(form_data);
		$(".delete_b2c_user_template").find("button[type='submit']").html("Continue");
		$(".delete_b2c_user_template").toggle();
	});

	// confirmed to delete b2c user
	$(document).on("submit", "form.delete_b2c_user_form", function()
	{
		var url = base_url+current_controller+"/delete"+default_ext;
		var user = $(this).data("user");
		var form_data = new FormData();
		form_data.append("user", user);
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
				custom_fn.show_loading("B2c User account is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_b2c_user").filter(function(i, el)
									{
										return $(this).data("href") === user;
									}).closest("tr");
					var cur_table = cur_row.closest("table");
					cur_row.detach();
					if(cur_table.find("tbody tr").length === 0)
						cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='9' class='dataTables_empty'>No data available in table</td></tr>");
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


	//End of B2C Module Functionalities


	// Agent Module Functionalities

	// Display all B2B Users
	if($(".manage_b2b_users").length > 0)
		$(".manage_b2b_users").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/users_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [2, 7, 8]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_b2b = JSON.parse(aData[0]);
				var b2b_name = jd_m_b2b.fname+" "+jd_m_b2b.lname;
				$("td:eq(0)", nRow).html(jd_m_b2b.user_id);
				$("td:eq(1)", nRow).html(jd_m_b2b.company);
				$("td:eq(2)", nRow).html(jd_m_b2b.image_html);
				$("td:eq(3)", nRow).html(b2b_name);
				$("td:eq(4)", nRow).html(jd_m_b2b.email);
				$("td:eq(5)", nRow).html(jd_m_b2b.contact);
				$("td:eq(6)", nRow).html(jd_m_b2b.account_type);
				$("td:eq(7)", nRow).html(jd_m_b2b.status_html);
				$("td:eq(8)", nRow).html(jd_m_b2b.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_b2b.id).data("name", b2b_name);
			}
		}).fnSetFilteringDelay(2000);


	// Add new b2b user
	$(document).on("submit", "form.add_b2b_user_form", function(submit_event)
	{
		if($(this).find("input[name]").length === ($(this).find("input[name].valid").length + $(this).find("input[name]:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
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
					custom_fn.show_loading("B2B User account is being created..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form[0].reset();
						cur_form.find("img").attr("src", asset_url+"images/default.png");
						$("select").html("").trigger("change");
					}
					else if(response.status === "exist")
					{
						cur_form.find("input[name='emailid']").val("").focus();
						cur_form.find("input[type='password']").val("");
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


	// update b2b user
	$(document).on("submit", "form.update_b2b_user_form", function(submit_event)
	{
		if($(this).find("input[name][type='text']").length === ($(this).find("input[name][type='text'].valid").length + $(this).find("input[name][type='text']:disabled").length) && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("current_image_path", cur_form.find("input[type='file']").data("href"));
			form_data.append("user", cur_form.data("href"));
			url = base_url+current_controller+"/edit_profile"+default_ext;
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
					custom_fn.show_loading("B2B user account is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true" && response.image_path !== undefined)
						cur_form.find("input[type='file']").data("href", response.image_path);
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

	// Change b2b user account status	
	$(document).on("click", "input[type='checkbox'].b2b_user_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var cur_row = cur_var.closest("tr");
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("b2b", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/b2b_user_status"+default_ext;
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
					custom_fn.show_loading("B2B user account is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("B2B user account is being activated..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
				custom_fn.set_auto_close(7000);
				if(response.status === "true")
					cur_var.prop("checked", checked);
				if(cur_row !== undefined)
				{
					if(checked)
						cur_row.find(".activation_required").removeClass("hide");
					else
						cur_row.find(".activation_required").addClass("hide");
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

	$(document).on("click", "a.b2b_user_password", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("b2b_user_password_template");
		$(".b2b_user_password_template").find("h4.modal-title").text("B2B Management - Change Password");
		var form_data = "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-5' for='my_password'>Enter Your Password</label>";
		form_data += "<div class='col-sm-7 controls'>";
		form_data += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='my_password' name='adm_pass' placeholder='Your Password' type='password'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-5' for='validation_password'>New Password</label>";
		form_data += "<div class='col-sm-7 controls'>";
		form_data += "<input class='form-control' data-rule-minlength='6' data-rule-password='true' data-rule-required='true' id='validation_password' name='newpass' placeholder='New Password' type='password'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-5' for='validation_password_confirmation'>Password confirmation</label>";
		form_data += "<div class='col-sm-7 controls'>";
		form_data += "<input class='form-control' data-rule-equalto='#validation_password' data-msg-equalto='Please re-enter password' data-rule-required='true' id='validation_password_confirmation' name='confirmpass' placeholder='Password confirmation' type='password'>";
		form_data += "</div>";
		form_data += "</div>";
		$(".b2b_user_password_template").find("div.modal-body").html(form_data);
		$(".b2b_user_password_template").find("button[type='submit']").html("Change");
		$(".b2b_user_password_template").toggle();
		$(".b2b_user_password_template").find("form").addClass("b2b_user_password_form").data("user", $(this).data("href"))
		custom_fn.load_validate("b2b_user_password_form");
	});

	$(document).on("submit", "form.b2b_user_password_form", function(e)
	{
		if($(this).find("input").length === $(this).find("input.valid").length)
		{
			var url = base_url+"b2b/change_password"+default_ext;
			var form_data = new FormData($(this)[0]);
			form_data.append("user", $(this).data("user"));
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
					custom_fn.show_loading("B2B User password is being updated..", "it will take a couple of seconds");
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

	if($(".manage_b2b_user_credit_reqs").length > 0)
		$(".manage_b2b_user_credit_reqs").dataTable(
		{
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/b2b_user_credit_requests"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_s_b2b_cdt = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_s_b2b_cdt.id_company);
				$("td:eq(1)", nRow).html(jd_s_b2b_cdt.logo_html);
				$("td:eq(2)", nRow).html(jd_s_b2b_cdt.name_email);
				$("td:eq(3)", nRow).html(jd_s_b2b_cdt.tranx_id);
				$("td:eq(4)", nRow).html(jd_s_b2b_cdt.tranx_category);
				$("td:eq(5)", nRow).html(jd_s_b2b_cdt.amount);
				$("td:eq(6)", nRow).html(jd_s_b2b_cdt.deposited);
				$("td:eq(7)", nRow).html(jd_s_b2b_cdt.used_credit);
				$("td:eq(8)", nRow).html(jd_s_b2b_cdt.balance_credit);
				$("td:eq(9)", nRow).html(jd_s_b2b_cdt.settlement_credit);
				$("td:eq(10)", nRow).html(jd_s_b2b_cdt.status_html);
				$("td:eq(10) select", nRow).data("name", jd_s_b2b_cdt.tranx_id).data("href", jd_s_b2b_cdt.id).data("current_status", jd_s_b2b_cdt.status).data("b2b_id", jd_s_b2b_cdt.b2b_id);
			}
		}).fnSetFilteringDelay(2000);

	// End of Agent Module Functionalities


	// Markup Module Functionalities

	// Display markup types
	if($("table.manage_markup_types").length > 0)
		$("table.manage_markup_types").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/types_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_mrkup_typ = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_mrkup_typ.sl_no);
				$("td:eq(1)", nRow).html(jd_m_mrkup_typ.name);
				$("td:eq(2)", nRow).html(jd_m_mrkup_typ.priority);
				$("td:eq(3)", nRow).html(jd_m_mrkup_typ.category);
				$("td:eq(4)", nRow).html(jd_m_mrkup_typ.actions);
				nRow.className = jd_m_mrkup_typ.category;
				$("td:eq(4) a[href='javascript:void(0);']", nRow).data("name", jd_m_mrkup_typ.name).data("priority", jd_m_mrkup_typ.priority).data("category", jd_m_mrkup_typ.category).data("mu_category", jd_m_mrkup_typ.user_type).data("href", jd_m_mrkup_typ.id);
			}
		}).fnSetFilteringDelay(2000);


	//submit new markup type
	$(document).on("submit", "form.add_markup_type_form", function(submit_event)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			url = base_url+current_controller+"/types_add"+default_ext;
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
					custom_fn.show_loading("Markup Type is being added..", "it will take a couple of seconds");
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

	//submit update markup type
	$(document).on("submit", "form.update_markup_type_form", function(submit_event)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var mu_priority = cur_form.find("[name='markup_priority']");
			var mu_type = cur_form.find("[name='user_type']");
			var form_data = new FormData(cur_form[0]);
			form_data.append("mu_type", $(this).data("href"));
			form_data.append("mu_user_old", mu_type.data("link"));
			form_data.append("mu_priority_old", mu_priority.data("href"));
			url = base_url+current_controller+"/types_edit"+default_ext;
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
					custom_fn.show_loading("Markup Type is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					mu_type.data("link", mu_type.val());
					mu_priority.data("href", mu_priority.val());
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

	//popup window to confirm set default of same type 
	$(document).on("click", "a.set_default_markup_type", function()
	{
		var mu_type_name = $(this).data("name");
		var category = $(this).data("category");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("set_default_markup_type_template");
		$(".set_default_markup_type_template").find("h4.modal-title").text("Markup Management - Set Default Markup Type");
		$(".set_default_markup_type_template").find("form").addClass("set_default_markup_type_form").data("type", $(this).data("href")).data("priority", $(this).data("priority")).data("category", $(this).data("mu_category"));
		var form_data = "<ul>";
		form_data += "<li>'"+mu_type_name+"' markup type will be set default in '"+category+"' category.</li>";
		form_data += "<li>'"+mu_type_name+"' will be given highest priority over other markup types.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to set '"+mu_type_name+"' as default markup type?";
		$(".set_default_markup_type_template").find("div.modal-body").html(form_data);
		$(".set_default_markup_type_template").find("button[type='submit']").html("Continue");
		$(".set_default_markup_type_template").toggle();
	});

	// delete privilege
	$(document).on("submit", "form.set_default_markup_type_form", function()
	{
		var url = base_url+current_controller+"/set_default_type"+default_ext;
		var priority = $(this).data("priority");
		var type = $(this).data("type");
		var category = $(this).data("category");
		var form_data = new FormData();
		form_data.append("mu_type", type);
		form_data.append("mu_priority_old", priority);
		form_data.append("markup_priority", "0");
		form_data.append("user_type", category);
		form_data.append("mu_user_old", category);
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
				custom_fn.show_loading("Markup type is being set as Default..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.set_default_markup_type").filter(function(i, el)
									{
										return $(this).data("href") === type;
									}).closest("tr");
					$("body").find("tr."+cur_row.attr("class")).each(function(index)
					{
						var data_priority = $(this).find("a[href='javascript:void(0);']").data("priority");
						if(parseInt(data_priority) <= priority)
						{
							$(this).find("td:eq(2)").html((data_priority * 1) + 1);
							$(this).find("a[href='javascript:void(0);']").data("priority", (data_priority * 1) + 1);
						}
					});
					$("body").find("tr."+cur_row.attr("class")+" a.default_markup_type").addClass("set_default_markup_type btn-danger").removeClass("default_markup_type btn-info").attr("title", "Set Default").tooltip('hide').attr('data-original-title', "Set Default").tooltip('fixTitle');
					cur_row.find("a.set_default_markup_type").addClass("default_markup_type btn-info").removeClass("set_default_markup_type btn-danger").attr("title", "Currently Default").tooltip('hide').attr('data-original-title', "Currently Default").tooltip('fixTitle');
					cur_row.find("td:eq(2)").html("0");
					cur_row.find("a[href='javascript:void(0);']").data("priority", "0");
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

	//popup window to confirm markup type
	$(document).on("click", "a.delete_markup_type", function()
	{
		var mu_type_name = $(this).data("name");
		var category = $(this).data("category");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_markup_type_template");
		$(".delete_markup_type_template").find("h4.modal-title").text("Markup Management - Delete Markup Type");
		$(".delete_markup_type_template").find("form").addClass("delete_markup_type_form").data("type", $(this).data("href")).data("priority", $(this).data("priority")).data("category", $(this).data("mu_category"));
		var form_data = "<ul>";
		form_data += "<li>'"+mu_type_name+"' markup type will be deleted from '"+category+"' category.</li>";
		form_data += "<li>'"+mu_type_name+"' priority will transfer to next other markup types.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+mu_type_name+"' markup type?";
		$(".delete_markup_type_template").find("div.modal-body").html(form_data);
		$(".delete_markup_type_template").find("button[type='submit']").html("Continue");
		$(".delete_markup_type_template").toggle();
	});

	// delete privilege
	$(document).on("submit", "form.delete_markup_type_form", function()
	{
		var url = base_url+current_controller+"/delete_type"+default_ext;
		var priority = $(this).data("priority");
		var type = $(this).data("type");
		var category = $(this).data("category");
		var form_data = new FormData();
		form_data.append("mu_type", type);
		form_data.append("markup_priority", priority);
		form_data.append("user_type", category);
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
				custom_fn.show_loading("Markup Type is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_markup_type").filter(function(i, el)
									{
										return $(this).data("href") === type;
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

	//submit new markup
	$(document).on("submit", "form.add_markup_form", function(submit_event)
	{
		if($(this).find("input[name]").length === ($(this).find("input[name].valid").length + $(this).find("input[name]:disabled").length) && $(this).find("select[name].error").length === 0)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var user_type = cur_form.data("user_type") !== undefined ? cur_form.data("user_type") : "";
			var form_data = new FormData(cur_form[0]);
			form_data.append("user_type", user_type);
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
					custom_fn.show_loading("Markup is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form[0].reset();
						cur_form.find("select").html("").trigger("change");
						cur_form.find(".optional_values").addClass("hide");
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

	//update markup
	$(document).on("submit", "form.update_markup_form", function(submit_event)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var markup = cur_form.data("href");
			var form_data = new FormData(cur_form[0]);
			form_data.append("markup", markup);
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
					custom_fn.show_loading("Markup is being updated..", "it will take a couple of seconds");
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


	// Display b2c markups
	if($("table.manage_b2c_markups").length > 0)
	$("table.manage_b2c_markups").dataTable({
		"dom": "lfrtip",
		"bProcessing": false,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": base_url+current_controller+"/b2c_markup_list"+default_ext,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
		"ordering": false,
		"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 7, 8, 9, 10]}],
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
		{
			var jd_m_b2c_mrkup = JSON.parse(aData[0]);
			$("td:eq(0)", nRow).html(jd_m_b2c_mrkup.sl_no);
			$("td:eq(1)", nRow).html(jd_m_b2c_mrkup.name);
			$("td:eq(2)", nRow).html(jd_m_b2c_mrkup.country_name);
			$("td:eq(3)", nRow).html(jd_m_b2c_mrkup.api);
			$("td:eq(4)", nRow).html(jd_m_b2c_mrkup.airline);
			$("td:eq(5)", nRow).html(jd_m_b2c_mrkup.o_airport);
			$("td:eq(6)", nRow).html(jd_m_b2c_mrkup.d_airport);
			$("td:eq(7)", nRow).html(jd_m_b2c_mrkup.category);
			$("td:eq(8)", nRow).html(jd_m_b2c_mrkup.amount);
			$("td:eq(9)", nRow).html(jd_m_b2c_mrkup.status_html);
			$("td:eq(10)", nRow).html(jd_m_b2c_mrkup.actions);
			$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_b2c_mrkup.name).data("href", jd_m_b2c_mrkup.id).data("general", jd_m_b2c_mrkup.is_general);
		}
	}).fnSetFilteringDelay(2000);

	// Display b2b markups
	if($("table.manage_b2b_markups").length > 0)
	$("table.manage_b2b_markups").dataTable({
		"dom": "lfrtip",
		"bProcessing": false,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": base_url+current_controller+"/b2b_markup_list"+default_ext,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
		"ordering": false,
		"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 7, 8, 9, 10, 11]}],
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
		{
			var jd_m_b2b_mrkup = JSON.parse(aData[0]);
			$("td:eq(0)", nRow).html(jd_m_b2b_mrkup.sl_no);
			$("td:eq(1)", nRow).html(jd_m_b2b_mrkup.name);
			$("td:eq(2)", nRow).html(jd_m_b2b_mrkup.country_name);
			$("td:eq(3)", nRow).html(jd_m_b2b_mrkup.api);
			$("td:eq(4)", nRow).html(jd_m_b2c_mrkup.airline);
			$("td:eq(5)", nRow).html(jd_m_b2c_mrkup.o_airport);
			$("td:eq(6)", nRow).html(jd_m_b2c_mrkup.d_airport);
			$("td:eq(7)", nRow).html(jd_m_b2b_mrkup.agent);
			$("td:eq(8)", nRow).html(jd_m_b2b_mrkup.category);
			$("td:eq(9)", nRow).html(jd_m_b2b_mrkup.amount);
			$("td:eq(10)", nRow).html(jd_m_b2b_mrkup.status_html);
			$("td:eq(11)", nRow).html(jd_m_b2b_mrkup.actions);
			$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_b2b_mrkup.name).data("href", jd_m_b2b_mrkup.id).data("general", jd_m_b2b_mrkup.is_general);
		}
	}).fnSetFilteringDelay(2000);

	// Change markup status	
	$(document).on("click", "input[type='checkbox'].markup_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("markup", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/markup_status"+default_ext;
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
					custom_fn.show_loading("Markup is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Markup is being activated..", "it will take a couple of seconds");
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

	//popup window to confirm markup
	$(document).on("click", "a.delete_markup", function()
	{
		var markup_name = $(this).data("name");
		var markup = $(this).data("href");
		var is_general = $(this).data("general");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_markup_template");
		$(".delete_markup_template").find("h4.modal-title").text("Markup Management - Delete Markup");
		$(".delete_markup_template").find("form").addClass("delete_markup_form").data("markup", markup);
		var form_data = "<ul>";
		form_data += "<li>'markup will be deleted from '"+markup_name+"' category.</li>";
		if(is_general === "")
			form_data += "<li>'"+markup_name+"' category is common to B2C and B2B Users. If continued, It will be removed for both Users.</li>";
		form_data += "</ul><br>Are you sure to delete markup from '"+markup_name+"' markup type?";
		$(".delete_markup_template").find("div.modal-body").html(form_data);
		$(".delete_markup_template").find("button[type='submit']").html("Continue");
		$(".delete_markup_template").toggle();
	});

	// delete privilege
	$(document).on("submit", "form.delete_markup_form", function()
	{
		var url = base_url+current_controller+"/delete_markup"+default_ext;
		var markup = $(this).data("markup");
		var form_data = new FormData();
		form_data.append("markup", markup);
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
				custom_fn.show_loading("Markup is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_markup").filter(function(i, el)
									{
										return $(this).data("href") === markup;
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
					var colspan_length = cur_row.children().length;
					if(cur_table.find("tbody tr").length === 0)
						cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='"+colspan_length+"' class='dataTables_empty'>No data available in table</td></tr>");
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

	// End of Markup Module Functionalities


	// Promocode Module Functionalities
	
	// Load All Promocode list
	if($("table.manage_promocodes").length > 0)
		$("table.manage_promocodes").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/promo_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 6, 7, 8]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_promo = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_promo.sl_no);
				$("td:eq(1)", nRow).html(jd_m_promo.code);
				$("td:eq(2)", nRow).html(jd_m_promo.promo_type);
				$("td:eq(3)", nRow).html(jd_m_promo.range);
				$("td:eq(4)", nRow).html(jd_m_promo.discount_html);
				$("td:eq(5)", nRow).html(jd_m_promo.valid_from);
				$("td:eq(6)", nRow).html(jd_m_promo.valid_to_html);
				$("td:eq(7)", nRow).html(jd_m_promo.status_html);
				$("td:eq(8)", nRow).html(jd_m_promo.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_promo.id).data("name", jd_m_promo.code);
			}
		}).fnSetFilteringDelay(2000);

	// toggle between auto promocode and manual
	$(document).on("click", "div.auto_promo_toggle a", function()
	{
		var sel = $(this).data("href");
		var this_parent = $(this).closest("form");
		var promocode = this_parent.find(".set_readonly_promo").data("href");
		this_parent.find('.auto_promo_toggle a').each(function(index)
		{
			if($(this).data("href") === sel)
				$(this).removeClass("notActive").addClass("active");
			else
				$(this).removeClass("active").addClass("notActive");
		});
		if((sel * 1) === 1)
			this_parent.find(".set_readonly_promo").val(promocode).attr("readonly", "readonly");
		else
		this_parent.find(".set_readonly_promo").removeAttr("readonly value").focus();
	});

	//Add new promocode
	$(document).on("submit", "form.add_percent_promo_form, form.add_cash_promo_form", function(e)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			var cur_form = $(this)[0];
			var form_data = new FormData(cur_form);
			if($(this).hasClass("add_percent_promo_form"))
				form_data.append("promo_type", "percentage");
			else
				form_data.append("promo_type", "amount");
			var url = base_url+current_controller+"/add_promo"+default_ext;

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
					custom_fn.show_loading("Promocode being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					cur_form.reset();
					if(response.status === "true")
						$(".set_readonly_promo").data("href", response.new_code);
					$(".set_readonly_promo").val($(".set_readonly_promo").data("href"));
					custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
					custom_fn.set_auto_close(5000);
				},
				error: function(response)
				{
					cur_form.reset();
					$(".set_readonly_promo").val($(".set_readonly_promo").data("href"));
					custom_fn.hide_loading();
					custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
					custom_fn.set_auto_close(5000);
				}
			});
		}
	});

	$(".promocode_status").bootstrapSwitch(
	{
		onSwitchChange:function(event, state)
		{
			var cur_var = $(this);
			var checked = cur_var.is(":checked");
			var status = state ? "1" : "0";
			var href = $(this).data("href");
			var form_data = new FormData();
			form_data.append("promocode", href);
			form_data.append("status", status);
			url = base_url+current_controller+"/promocode_status"+default_ext;
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
						custom_fn.show_loading("Promocode is being deactivated..", "it will take a couple of seconds");
					else
						custom_fn.show_loading("Promocode is being activated..", "it will take a couple of seconds");
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
		}
	});

	// Change promocode status
	$(document).on("click", "input[type='checkbox'].promocode_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("promocode", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/promocode_status"+default_ext;
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
					custom_fn.show_loading("Promocode is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Promocode is being activated..", "it will take a couple of seconds");
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


	// submit send promocode
	$(document).on("submit", "form.send_promocode_form", function()
	{
		if($(this).find("input[name]").length > 0)
		{
			var url = base_url+current_controller+"/"+current_method+default_ext;
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("promocode", cur_form.data("href"));
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
					custom_fn.show_loading("Promocode is being sent to emails..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						cur_form[0].reset();
						cur_form.find(".input_tags").tagsinput("removeAll");
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

	// popup message to edit promocode (un-finished)
	$(document).on("click", "a.edit_promocode", function()
	{
		// $("body").prepend(custom_fn.model_template);
		// var promocode = $(this).closest("tr").find("td:eq(1)").html();
		// var amount_range = $(this).closest("tr").find("td:eq(3)").html().split(" ");
		// var discount = $(this).closest("tr").find("td:eq(4)").html().split(" ");
		// var expiry_date = $(this).closest("tr").find("td:eq(6):contains('Expired')").length > 0 ? new Date() : $(this).closest("tr").find("td:eq(6)").html();
		// $(".model_template").addClass("edit_promocode_template");
		// $(".edit_promocode_template").find("h4.modal-title").text("Manage Promocode - Edit Promocode");
		// var form_body = "<div class='form-group'>";
		// form_body += "<label class='control-label col-sm-5'>Promocode</label>";
		// form_body += "<div class='col-sm-7 controls'>";
		// form_body += "<input autocomplete='off' class='form-control' value='"+promocode+"' disabled='disabled'>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "<div class='form-group'>";
		// form_body += "<label class='control-label col-sm-5' for='discount'>Enter amount range</label>";
		// form_body += "<div class='col-sm-7 controls'>";
		// form_body += "<input autocomplete='off' class='form-control' value='"+amount_range[0]+"' data-rule-number='true' data-rule-required='true' data-rule-min='0' name='discount' type='text' placeholder='Discount'>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "<div class='form-group'>";
		// form_body += "<label class='control-label col-sm-5' for='promo_amount'>Enter discount in "+discount[1]+"</label>";
		// form_body += "<div class='col-sm-7 controls'>";
		// form_body += "<input autocomplete='off' class='form-control' value='"+discount[0]+"' data-rule-number='true' data-rule-required='true' data-rule-min='0' name='promo_amount' type='text' placeholder='Amount Range'>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "<div class='form-group'>";
		// form_body += "<label class='control-label col-sm-5' for='expiry_date'>Expiry Date</label>";
		// form_body += "<div class='input-group col-sm-7'>";
		// form_body += "<input autocomplete='off' class='form-control today_onwards_limited' value='"+expiry_date+"' data-format='MM/DD/YYYY' data-rule-required ='true' data-msg-required='Please select date.' placeholder='MM/DD/YYYY' name='expiry_date'  type='text' readonly=''>";
		// form_body += "<span class='input-group-addon'>";
		// form_body += "<span class='icon-calendar' data-time-icon='icon-time'></span>";
		// form_body += "</span>";
		// form_body += "</div>";
		// form_body += "</div>";
		// form_body += "</div>";
		// $(".edit_promocode_template").find("div.modal-body").html(form_body);
		// $(".edit_promocode_template").find("button[type='submit']").html("Update");
		// $(".edit_promocode_template").find("form").addClass("edit_promocode_form").data("promo", $(this).data("href"));
		// $(".edit_promocode_template").toggle();
		// custom_fn.load_validate("edit_promocode_form");
	});

	//update promocode
	$(document).on("submit", "form.edit_promocode_form", function()
	{
		// if($(this).find("input").length === $(this).find("input.valid").length)
		// {
		// 	var cur_form = $(this)[0];
		// 	var updated_name = $(this).find("input[name='lang_page']").val();
		// 	var page = $(this).data("page");
		// 	var url = base_url+current_controller+"/update_page"+default_ext;
		// 	var form_data = new FormData(cur_form);
		// 	form_data.append("page", page);
		// 	$.ajax(
		// 	{
		// 		url: url,
		// 		method: "POST",
		// 		dataType: "JSON",
		// 		data: form_data,
		// 		processData: false,
		// 		contentType:false,
		// 		beforeSend: function()
		// 		{
		// 			// remove popup window
		// 			$("body").find(".model_template").detach();
		// 			//show popup
		// 			custom_fn.show_loading("Promocode is being updated..", "it will take a couple of seconds");
		// 		},
		// 		success: function(response)
		// 		{
		// 			if(response.status === "true")
		// 			{
		// 				$("body").find("a.edit_promocode").filter(function(i, el)
		// 				{
		// 					return $(this).data("href") === page;
		// 				}).closest("tr").find("td:eq(1)").html(updated_name);
		// 			}
		// 			custom_fn.hide_loading();
		// 			custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
		// 			custom_fn.set_auto_close(7000);
		// 		},
		// 		error: function(response)
		// 		{
		// 			custom_fn.hide_loading();
		// 			custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
		// 			custom_fn.set_auto_close(5000);
		// 		}
		// 	});
		// }
	});

	//popup window to confirm delete promocode 
	$(document).on("click", "a.delete_promocode", function()
	{
		var promo_code = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_promocode_template");
		$(".delete_promocode_template").find("h4.modal-title").text("Manage Promocode - Delete Promocode");
		$(".delete_promocode_template").find("form").addClass("delete_promocode_form").data("promocode", $(this).data("href"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+promo_code+"' promocode will be completely lost if you continue.</li>";
		form_data += "<li>'"+promo_code+"' promocode will be treated as invalid if any user try to apply at booking time.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+promo_code+"' promocode?";
		$(".delete_promocode_template").find("div.modal-body").html(form_data);
		$(".delete_promocode_template").find("button[type='submit']").html("Continue");
		$(".delete_promocode_template").toggle();
	});

	// delete privilege
	$(document).on("submit", "form.delete_promocode_form", function()
	{
		var url = base_url+current_controller+"/delete"+default_ext;
		var promocode = $(this).data("promocode");
		var form_data = new FormData();
		form_data.append("promocode", promocode);
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
				custom_fn.show_loading("Promocode is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_promocode").filter(function(i, el)
									{
										return $(this).data("href") === promocode;
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
						cur_table.find("tbody").append("<tr class='odd'><td valign='top' colspan='9' class='dataTables_empty'>No data available in table</td></tr>");
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




	// End of Promocode Module Functionalities


	// Charges Module Functionalities

	// Manage Payment Gateways
	if($("table.manage_payment_gateways").length > 0)
		$("table.manage_payment_gateways").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/pg_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_pay_gw = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_pay_gw.sl_no);
				$("td:eq(1)", nRow).html(jd_m_pay_gw.title);
				$("td:eq(2)", nRow).html(jd_m_pay_gw.pay_mode);
				$("td:eq(3)", nRow).html(jd_m_pay_gw.status_html);
				$("td:eq(4)", nRow).html(jd_m_pay_gw.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_pay_gw.id).data("name", jd_m_pay_gw.title);
			}
		}).fnSetFilteringDelay(2000);


	//display required message for payment mode
	$(document).on("click", "input[name='pay_mode[]']", function()
	{
		if($("input[name='pay_mode[]']:checked").length > 0)
			$(".paymode_error").html("");
		else
			$(".paymode_error").html("<label class='mrgn_top_more'>Please select at-least one payment mode.</label>");
	});

	// Add payment gateway
	$(document).on("submit", "form.add_payment_gateway_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
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
					custom_fn.show_loading("Payment Gateway is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
						cur_form[0].reset();
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

	// Change Payment Gateway status	
	$(document).on("click", "input[type='checkbox'].payment_gateway_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("payment_gateway", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/payment_gateway_status"+default_ext;
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
					custom_fn.show_loading("Payment Gateway is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Payment Gateway is being activated..", "it will take a couple of seconds");
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

	// Update payment gateway
	$(document).on("submit", "form.update_payment_gateway_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var pg_name = cur_form.find("input[name='pg_name']");
			var form_data = new FormData(cur_form[0]);
			form_data.append("payment_gateway", cur_form.data("href"));
			form_data.append("pg_name_old", pg_name.data("href"));
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
					custom_fn.show_loading("Payment Gateway is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					pg_name.data("href", response.payment_gateway);
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

	//popup window to confirm delete payment gateway
	$(document).on("click", "a.delete_payment_gateway", function()
	{
		var payment_gateway_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_payment_gateway_template");
		$(".delete_payment_gateway_template").find("h4.modal-title").text("Charges Management - Delete Payment Gateway");
		$(".delete_payment_gateway_template").find("form").addClass("delete_payment_gateway_form").data("payment_gateway", $(this).data("href")).data("name", $(this).data("name"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+payment_gateway_name+"' Payment Gateway will be completely lost if you continue.</li>";
		form_data += "<li>'"+payment_gateway_name+"' payment gateway charges will also be deleted.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+payment_gateway_name+"' payment gateway?";
		$(".delete_payment_gateway_template").find("div.modal-body").html(form_data);
		$(".delete_payment_gateway_template").find("button[type='submit']").html("Continue");
		$(".delete_payment_gateway_template").toggle();
	});

	// delete payment gateway
	$(document).on("submit", "form.delete_payment_gateway_form", function()
	{
		var url = base_url+current_controller+"/delete_payment_gateway"+default_ext;
		var payment_gateway = $(this).data("payment_gateway");
		var form_data = new FormData();
		form_data.append("payment_gateway", payment_gateway);
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
				custom_fn.show_loading("Payment Gateway is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{
					var cur_row = $("body").find("a.delete_payment_gateway").filter(function(i, el)
									{
										return $(this).data("href") === payment_gateway;
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

	// Manage Payment Gateway Charges
	if($("table.manage_pg_charges").length > 0)
		$("table.manage_pg_charges").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/pg_charges_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4, 5, 6]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_pay_gw_chrg = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_pay_gw_chrg.sl_no);
				$("td:eq(1)", nRow).html(jd_m_pay_gw_chrg.title);
				$("td:eq(2)", nRow).html(jd_m_pay_gw_chrg.api);
				$("td:eq(3)", nRow).html(jd_m_pay_gw_chrg.pay_mode);
				$("td:eq(4)", nRow).html(jd_m_pay_gw_chrg.amount);
				$("td:eq(5)", nRow).html(jd_m_pay_gw_chrg.status_html);
				$("td:eq(6)", nRow).html(jd_m_pay_gw_chrg.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_pay_gw_chrg.id).data("name", jd_m_pay_gw_chrg.title);
			}
		}).fnSetFilteringDelay(2000);

	$(document).on("click", "form.add_pg_charges_form input[name='pay_mode']", function()
	{
		var current_form = $(this).closest("form.add_pg_charges_form");
		var amount_div = current_form.find(".payable_amount_div");
		if(current_form !== undefined && current_form.length > 0)
		{
			var pay_mode = $(this).val();
			amount_div.find(">label").html($(this).parent().text());
			if(parseInt(pay_mode) === 1)
				amount_div.find("input").attr({"placeholder" : "Percentage"});
			else
				amount_div.find("input").attr({"placeholder" : "Amount"});
			amount_div.find("input").next().detach()
			custom_fn.load_validate('add_pg_charges_form');
		}
	});


	// Add payment charges
	$(document).on("submit", "form.add_pg_charges_form", function(submit_event)
	{
		if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
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
					custom_fn.show_loading("Payment Gateway Charges is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						cur_form[0].reset();
						$("select.set_payment_gateway").closest(".form-group").next().addClass("hide");
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


	// Change Payment Charges status	
	$(document).on("click", "input[type='checkbox'].pg_charges_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("pg_charge", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/pg_charges_status"+default_ext;
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
					custom_fn.show_loading("Payment Charges is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Payment Charges is being activated..", "it will take a couple of seconds");
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

	// Update payment charges
	$(document).on("submit", "form.update_pg_charges_form", function(submit_event)
	{
		if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("pg_charge", cur_form.data("href"));
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
					custom_fn.show_loading("Payment Gateway Charge is being updated..", "it will take a couple of seconds");
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

	//popup window to confirm delete payment charges
	$(document).on("click", "a.delete_pg_charge", function()
	{
		var pg_charge_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_pg_charge_template");
		$(".delete_pg_charge_template").find("h4.modal-title").text("Charges Management - Delete Payment Charges");
		$(".delete_pg_charge_template").find("form").addClass("delete_pg_charge_form").data("pg_charge", $(this).data("href")).data("name", $(this).data("name"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+pg_charge_name+"' Payment Charges will be completely lost if you continue.</li>";
		form_data += "<li>'"+pg_charge_name+"' payment charges will not be applicable to any bookings.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+pg_charge_name+"' payment charge?";
		$(".delete_pg_charge_template").find("div.modal-body").html(form_data);
		$(".delete_pg_charge_template").find("button[type='submit']").html("Continue");
		$(".delete_pg_charge_template").toggle();
	});

	// delete payment charges
	$(document).on("submit", "form.delete_pg_charge_form", function()
	{
		var url = base_url+current_controller+"/delete_pg_charge"+default_ext;
		var pg_charge = $(this).data("pg_charge");
		var form_data = new FormData();
		form_data.append("pg_charge", pg_charge);
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
				custom_fn.show_loading("Payment Charges is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{
					var cur_row = $("body").find("a.delete_pg_charge").filter(function(i, el)
									{
										return $(this).data("href") === pg_charge;
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


	// Manage Taxes
	if($("table.manage_taxes").length > 0)
		$("table.manage_taxes").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/taxes_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_tax = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_tax.sl_no);
				$("td:eq(1)", nRow).html(jd_m_tax.title);
				$("td:eq(2)", nRow).html(jd_m_tax.pay_mode);
				$("td:eq(3)", nRow).html(jd_m_tax.status_html);
				$("td:eq(4)", nRow).html(jd_m_tax.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_tax.id).data("name", jd_m_tax.title);
			}
		}).fnSetFilteringDelay(2000);

	// Add Tax
	$(document).on("submit", "form.add_taxes_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
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
					custom_fn.show_loading("Tax is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
						cur_form[0].reset();
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


	// Change Tax status	
	$(document).on("click", "input[type='checkbox'].tax_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("tax", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/taxes_status"+default_ext;
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
					custom_fn.show_loading("Tax is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Tax is being activated..", "it will take a couple of seconds");
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

	// Update Tax
	$(document).on("submit", "form.update_taxes_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var tax_name = cur_form.find("input[name='tax_name']");
			var form_data = new FormData(cur_form[0]);
			form_data.append("tax", cur_form.data("href"));
			form_data.append("tax_name_old", tax_name.data("href"));
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
					custom_fn.show_loading("Tax is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					tax_name.data("href", response.tax_name);
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

	//popup window to confirm delete Tax
	$(document).on("click", "a.delete_tax", function()
	{
		var tax_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_tax_template");
		$(".delete_tax_template").find("h4.modal-title").text("Charges Management - Delete Tax");
		$(".delete_tax_template").find("form").addClass("delete_tax_form").data("tax", $(this).data("href")).data("name", $(this).data("name"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+tax_name+"' Tax will be completely lost if you continue.</li>";
		form_data += "<li>'"+tax_name+"' Tax charges will also be deleted.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+tax_name+"' Tax?";
		$(".delete_tax_template").find("div.modal-body").html(form_data);
		$(".delete_tax_template").find("button[type='submit']").html("Continue");
		$(".delete_tax_template").toggle();
	});

	// delete Tax
	$(document).on("submit", "form.delete_tax_form", function()
	{
		var url = base_url+current_controller+"/delete_taxes"+default_ext;
		var tax = $(this).data("tax");
		var form_data = new FormData();
		form_data.append("tax", tax);
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
				custom_fn.show_loading("Tax is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{
					var cur_row = $("body").find("a.delete_tax").filter(function(i, el)
									{
										return $(this).data("href") === tax;
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

	// Manage Tax Charges
	if($("table.manage_tax_charges").length > 0)
		$("table.manage_tax_charges").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/tax_charges_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 3, 4, 5, 6]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_tax_chrg = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_tax_chrg.sl_no);
				$("td:eq(1)", nRow).html(jd_m_tax_chrg.title);
				$("td:eq(2)", nRow).html(jd_m_tax_chrg.api);
				$("td:eq(3)", nRow).html(jd_m_tax_chrg.pay_mode);
				$("td:eq(4)", nRow).html(jd_m_tax_chrg.amount);
				$("td:eq(5)", nRow).html(jd_m_tax_chrg.status_html);
				$("td:eq(6)", nRow).html(jd_m_tax_chrg.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_tax_chrg.id).data("name", jd_m_tax_chrg.title);
			}
		}).fnSetFilteringDelay(2000);

	$(document).on("click", "form.add_tax_charges_form input[name='pay_mode']", function()
	{
		var current_form = $(this).closest("form.add_tax_charges_form");
		var amount_div = current_form.find(".payable_amount_div");
		if(current_form !== undefined && current_form.length > 0)
		{
			var pay_mode = $(this).val();
			amount_div.find(">label").html($(this).parent().text());
			if(parseInt(pay_mode) === 1)
				amount_div.find("input").attr({"placeholder" : "Percentage"});
			else
				amount_div.find("input").attr({"placeholder" : "Amount"});
			amount_div.find("input").next().detach()
			custom_fn.load_validate('add_tax_charges_form');
		}
	});


	// Add tax charges
	$(document).on("submit", "form.add_tax_charges_form", function(submit_event)
	{
		if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
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
					custom_fn.show_loading("Tax Charges is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					if(response.status === "true")
					{
						cur_form[0].reset();
						$("select").html("").trigger("change");
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


	// Change Tax Charges status	
	$(document).on("click", "input[type='checkbox'].tax_charges_status", function(click_event)
	{
		click_event.preventDefault();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("tax_charge", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/tax_charges_status"+default_ext;
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
					custom_fn.show_loading("Tax Charges is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Tax Charges is being activated..", "it will take a couple of seconds");
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

	// Update tax charges
	$(document).on("submit", "form.update_tax_charges_form", function(submit_event)
	{
		if($(this).find("input[type='text'][name]").length === $(this).find("input[type='text'][name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("tax_charge", cur_form.data("href"));
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
					custom_fn.show_loading("Tax Charge is being updated..", "it will take a couple of seconds");
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

	//popup window to confirm delete tax charges
	$(document).on("click", "a.delete_tax_charge", function()
	{
		var tax_charge_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_tax_charge_template");
		$(".delete_tax_charge_template").find("h4.modal-title").text("Charges Management - Delete Tax Charges");
		$(".delete_tax_charge_template").find("form").addClass("delete_tax_charge_form").data("tax_charge", $(this).data("href")).data("name", $(this).data("name"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+tax_charge_name+"' Tax Charges will be completely lost if you continue.</li>";
		form_data += "<li>'"+tax_charge_name+"' tax charges will not be applicable to any bookings.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+tax_charge_name+"' payment charge?";
		$(".delete_tax_charge_template").find("div.modal-body").html(form_data);
		$(".delete_tax_charge_template").find("button[type='submit']").html("Continue");
		$(".delete_tax_charge_template").toggle();
	});

	// delete tax charges
	$(document).on("submit", "form.delete_tax_charge_form", function()
	{
		var url = base_url+current_controller+"/delete_tax_charge"+default_ext;
		var tax_charge = $(this).data("tax_charge");
		var form_data = new FormData();
		form_data.append("tax_charge", tax_charge);
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
				custom_fn.show_loading("Tax Charges is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{
					var cur_row = $("body").find("a.delete_tax_charge").filter(function(i, el)
									{
										return $(this).data("href") === tax_charge;
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
	// End of Charges Module Functionalities




	// Subcribers Module Fucnctionalities

	// Diplay all Subscribers
	if($("table.manage_subscribers").length > 0)
		$("table.manage_subscribers").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/subscribers_list"+default_ext,
			"iDisplayLength": 10,
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
			var country_name = cur_form.find("input[name='country_en']");
			var country_id2 = cur_form.find("input[name='country_id2']");
			var country_id3 = cur_form.find("input[name='country_id3']");
			var country_id_num = cur_form.find("input[name='country_id_num']");
			var form_data = new FormData(cur_form[0]);
			form_data.append("country", cur_form.data("href"));
			form_data.append("country_en_old", country_name.data("href"));
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
						country_name.data("href", response.new_data["country_en"]);
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
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4, 5]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_states = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_states.sl_no);
				$("td:eq(1)", nRow).html(jd_m_states.country_name);
				$("td:eq(2)", nRow).html(jd_m_states.region);
				$("td:eq(3)", nRow).html(jd_m_states.region_name);
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
			var region_name = cur_form.find("input[name='region_name']");
			form_data.append("region_id", cur_form.data("href"));
			form_data.append("country_old", country.data("href"));
			form_data.append("region_old", region.data("href"));
			form_data.append("region_name_old", region_name.data("href"));
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
						region_name.data("href", response.new_data["region_name"]);
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
			var city = cur_form.find("input[name='city']");
			form_data.append("city_id", cur_form.data("href"));
			form_data.append("country_old", country.data("href"));
			form_data.append("region_old", region.data("href"));
			form_data.append("city_old", city.data("href"));
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



	// API Module functionalities
	
	// Load API Types
	if($("ul.manage_api_types").length > 0)
	{
		url = base_url+current_controller+"/types_html"+default_ext;
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
				custom_fn.show_loading("API Types are being loaded..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				$("ul.manage_api_types").html(response);
				// Remove any id stored by PHP from attribute list
				$("ul.manage_api_types [hyperlink]").each(function()
				{
					var href = $(this).attr("hyperlink");
					$(this).data("href", href);
					$(this).removeAttr("hyperlink");
				});

				// Remove any id stored by PHP from attribute list
				$("ul.manage_api_types [hypername]").each(function()
				{
					var href = $(this).attr("hypername");
					$(this).data("api_type", href);
					$(this).removeAttr("hypername");
				});
			},
			error: function(response)
			{
				custom_fn.hide_loading();
			}
		});
	}

	//add API Type
	$(document).on("submit", "form.add_api_type_form", function(submit_event)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			var url = base_url+current_controller+"/add_api_type"+default_ext;
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
					custom_fn.show_loading("API Type is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					cur_form[0].reset();
					if(response.status === "true")
					{
						if($("ul.manage_api_types li:eq(0)").hasClass("no_api_types_data"))
							$("ul.manage_api_types li:eq(0)").detach();
						$("ul.manage_api_types").append(response.data);
						
						// Remove any id stored by PHP from attribute list
						$("ul.manage_api_types [hyperlink]").each(function()
						{
							var href = $(this).attr("hyperlink");
							$(this).data("href", href);
							$(this).removeAttr("hyperlink");
						});
						// Remove any id stored by PHP from attribute list
						$("ul.manage_api_types [hypername]").each(function()
						{
							var href = $(this).attr("hypername");
							$(this).data("api_type", href);
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


	//popup window to confirm delete api type
	$(document).on("click", "a.delete_api_type", function()
	{
		var name = $(this).data("api_type");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_api_type_template");
		$(".delete_api_type_template").find("h4.modal-title").text("API Management - Delete API Type");
		$(".delete_api_type_template").find("form").addClass("delete_api_type_form").data("api_type", $(this).data("href"));
		$(".delete_api_type_template").find("div.modal-body").html("<ul><li>Information of '"+name+"' API Type will be completely lost if you continue.</li><li>API details associated with '"+name+"' API Type will also be deleted.</li></ul><br>Are you sure to delete '"+name+"' API Type?");
		$(".delete_api_type_template").find("button[type='submit']").html("Continue");
		$(".delete_api_type_template").toggle();
	});

	// delete support page subject
	$(document).on("submit", "form.delete_api_type_form", function()
	{
		var url = base_url+current_controller+"/delete_api_type"+default_ext;
		var api_type = $(this).data("api_type");
		var form_data = new FormData();
		form_data.append("api_type", api_type);
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
				custom_fn.show_loading("API Type is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{
					$("ul.manage_api_types").find("a.delete_api_type").filter(function(i, el)
					{
						return $(this).data("href") === api_type;
					}).closest("li").detach();
					if($("ul.manage_api_types li").length === 0)
						$("ul.manage_api_types").append("<li class='item no_api_types_data'>No API Types are available. Please add some API Types.</li>");
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
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 3, 4, 5, 6]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_api = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_api.sl_no);
				$("td:eq(1)", nRow).html(jd_m_api.api_type);
				$("td:eq(2)", nRow).html(jd_m_api.api_name);
				$("td:eq(3)", nRow).html(jd_m_api.test_credentials);
				$("td:eq(4)", nRow).html(jd_m_api.live_credentials);
				$("td:eq(5)", nRow).html(jd_m_api.status_html);
				$("td:eq(6)", nRow).html(jd_m_api.actions);
				$("td a[href='javascript:void(0);']", nRow).data("href", jd_m_api.id).data("api_name", jd_m_api.api_name).data("category", jd_m_api.category).data("api_type", jd_m_api.api_type).data("test_user", jd_m_api.test_user).data("live_user", jd_m_api.live_user).data("test_pwd", jd_m_api.test_pwd).data("live_pwd", jd_m_api.live_pwd).data("test_url", jd_m_api.test_url).data("live_url", jd_m_api.live_url).data("test_extra", jd_m_api.test_extra).data("live_extra", jd_m_api.live_extra);
			}
		}).fnSetFilteringDelay(2000);


	// Add API popup
	$(document).on("click", "button.add_api", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("add_api_template");
		$(".add_api_template").find("h4.modal-title").text("API Management - Add API");
		var form_data = "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-3'>API Type</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<select class='select2 form-control set_api_types' name='api_type' id='api_type' data-rule-required='true' data-msg-required='Select API Type'>";
		form_data += "</select>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-3'>Credentials Type</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<select class='select2 form-control api_category_types' style='width:100%;' name='category' id='category' data-rule-required='true'>";
		form_data += "<option value = '1'>Test Only</option>";
		form_data += "<option value = '2'>Live Only</option>";
		form_data += "<option value = '3'>Test and Live</option>";
		form_data += "</select>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-3'>API Name</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='api_name' placeholder='API Name' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group test_credentials_required'>";
		form_data += "<label class='control-label col-sm-3'>Test Credentials</label>";
		form_data += "<div class='col-sm-4 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='test_user' placeholder='Test Username' type='text'>";
		form_data += "</div>";
		form_data += "<div class='col-sm-5 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='test_pass' placeholder='Test Password' type='password'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group test_credentials_required'>";
		form_data += "<label class='control-label col-sm-3'>Test Extra Credentials</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control valid' autocomplete='off' data-rule-required='false' name='test_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group live_credentials_required hide'>";
		form_data += "<label class='control-label col-sm-3'>Live Credentials</label>";
		form_data += "<div class='col-sm-4 controls'>";
		form_data += "<input class='form-control' autocomplete='off' disabled='true' data-rule-required='true' name='live_user' placeholder='Live Username' type='text'>";
		form_data += "</div>";
		form_data += "<div class='col-sm-5 controls'>";
		form_data += "<input class='form-control' autocomplete='off' disabled='true' data-rule-required='true' name='live_pass' placeholder='Live Password' type='password'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group live_credentials_required hide'>";
		form_data += "<label class='control-label col-sm-3'>Live Extra Credentials</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control valid' autocomplete='off' disabled='true' data-rule-required='false' name='live_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-3'>URL</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='api_url' placeholder='URL' type='text'>";
		form_data += "<p class='help-block'><small class='text-muted'>Note: If Live and Test Url are different, Then format is <br/>&Prime;Live_URL (Space) Test_URL&Prime;<br/>Example : https://liveurl.com/api https://testurl.com/api</small></p>";
		form_data += "</div>";
		form_data += "</div>";

		$(".add_api_template").find("div.modal-body").html(form_data);
		$(".add_api_template").find("button[type='submit']").html("Add");
		$(".add_api_template").find("form").addClass("add_api_form");
		$("select.api_category_types").select2();
		custom_fn.load_validate("add_api_form");
		set_api_type();
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
						$("table.manage_apis tbody tr:eq("+index+") a[class*='edit_api']").data("category", response.new_data["category"]).data("api_type", response.new_data["api_type"]).data("test_user", response.new_data["test_user"]).data("live_user", response.new_data["live_user"]).data("test_pwd", response.new_data["test_pwd"]).data("live_pwd", response.new_data["live_pwd"]).data("test_url", response.new_data["test_url"]).data("live_url", response.new_data["live_url"]).data("test_extra", response.new_data["test_extra"]).data("live_extra", response.new_data["live_extra"]);
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
		var api_type = $(this).data("api_type");
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
		form_data += "<label class='control-label col-sm-3'>API Type</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<span class='form-control'>"+api_type+"</span>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-3'>Credentials Type</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<select style='width:100%;' class='select2 form-control api_category_types' name='category' data-rule-required='true'>";
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
		form_data += "<label class='control-label col-sm-3'>API Name</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control' autocomplete='off' value='"+api_name+"' data-rule-required='true' name='api_name' placeholder='API Name' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		var tcr_hide = category === "2" ? "hide" : "";
		var tcr_disable = category === "2" ? "disabled='true'" : "";
		form_data += "<div class='form-group test_credentials_required "+tcr_hide+"'>";
		form_data += "<label class='control-label col-sm-3'>Test Credentials</label>";
		form_data += "<div class='col-sm-4 controls'>";
		form_data += "<input class='form-control' autocomplete='off' value='"+test_user+"' "+tcr_disable+" data-rule-required='true' name='test_user' placeholder='Test Username' type='text'>";
		form_data += "</div>";
		form_data += "<div class='col-sm-5 controls'>";
		form_data += "<input class='form-control' autocomplete='off' value='"+test_pwd+"' "+tcr_disable+" data-rule-required='true' name='test_pass' placeholder='Test Password' type='password'>";
		form_data += "</div>";
		form_data += "</div>";

		form_data += "<div class='form-group test_credentials_required "+tcr_hide+"'>";
		form_data += "<label class='control-label col-sm-3'>Test Extra Credentials</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control valid' autocomplete='off' value='"+test_extra+"' "+tcr_disable+" data-rule-required='false' name='test_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
		form_data += "</div>";
		form_data += "</div>";

		var lcr_hide = category === "1" ? "hide" : "";
		var lcr_disable = category === "1" ? "disabled='true'" : "";
		form_data += "<div class='form-group live_credentials_required "+lcr_hide+"'>";
		form_data += "<label class='control-label col-sm-3'>Live Credentials</label>";
		form_data += "<div class='col-sm-4 controls'>";
		form_data += "<input class='form-control' autocomplete='off' value='"+live_user+"' "+lcr_disable+" data-rule-required='true' name='live_user' placeholder='Live Username' type='text'>";
		form_data += "</div>";
		form_data += "<div class='col-sm-5 controls'>";
		form_data += "<input class='form-control' autocomplete='off' value='"+live_pwd+"' "+lcr_disable+" data-rule-required='true' name='live_pass' placeholder='Live Password' type='password'>";
		form_data += "</div>";
		form_data += "</div>";


		form_data += "<div class='form-group live_credentials_required "+lcr_hide+"'>";
		form_data += "<label class='control-label col-sm-3'>Live Extra Credentials</label>";
		form_data += "<div class='col-sm-9 controls'>";
		form_data += "<input class='form-control valid' autocomplete='off' value='"+live_extra+"' "+lcr_disable+" data-rule-required='false' name='live_extra' placeholder='Merchant ID / Office ID etc' type='text'>";
		form_data += "</div>";
		form_data += "</div>";

		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-3'>URL</label>";
		form_data += "<div class='col-sm-9 controls'>";
		api_url = test_url !== live_url ? (live_url+" "+test_url) : live_url;
		form_data += "<input class='form-control' autocomplete='off' value='"+api_url+"' data-rule-required='true' name='api_url' placeholder='URL' type='text'>";
		form_data += "<p class='help-block'><small class='text-muted'>Note: If Live and Test Url are different, Then format is <br/>&Prime;Live_URL (Space) Test_URL&Prime;<br/>Example : https://liveurl.com/api https://testurl.com/api</small></p>";
		form_data += "</div>";
		form_data += "</div>";

		$(".edit_api_template").find("div.modal-body").html(form_data);
		$(".edit_api_template").find("button[type='submit']").html("Update");
		$("select.api_category_types").select2();
		set_api_type();
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


	// Currency Management Module Functionlities

	// Diplay all Currencies
	if($("table.manage_currencies").length > 0)
		$("table.manage_currencies").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/currency_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5, 6, 7]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_curr = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_curr.sl_no);
				$("td:eq(1)", nRow).html(jd_m_curr.country);
				$("td:eq(2)", nRow).html(jd_m_curr.code);
				$("td:eq(3)", nRow).html(jd_m_curr.cur_name);
				$("td:eq(4)", nRow).html(jd_m_curr.value);
				$("td:eq(5)", nRow).html(jd_m_curr.last_updated);
				$("td:eq(6)", nRow).html(jd_m_curr.status_html);
				$("td:eq(7)", nRow).html(jd_m_curr.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_curr.id).data("name", jd_m_curr.country);
			}
		}).fnSetFilteringDelay(2000);

	// Change Currency status
	$(document).on("click", "input[type='checkbox'].currency_status", function(click_event)
	{
		click_event.preventDefault();
		click_event.stopPropagation();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("currency", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/currency_status"+default_ext;
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
					custom_fn.show_loading("Currency is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Currency is being activated..", "it will take a couple of seconds");
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

	//submit add currency
	$(document).on("submit", "form.add_currency_form", function(submit_event)
	{
		if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var country_select = cur_form.find("select.unset_currency_country");
			var form_data = new FormData(cur_form[0]);
			url = base_url+current_controller+"/add"+default_ext;
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
					custom_fn.show_loading("Country currency is being added..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						cur_form[0].reset();
						country_select.html("").trigger("change");
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

	//submit update currency
	$(document).on("submit", "form.update_currency_form", function(submit_event)
	{
		if($(this).find("input[name][type='text']").length === $(this).find("input[name][type='text'].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			form_data.append("currency", cur_form.data("href"));
			url = base_url+current_controller+"/edit"+default_ext;
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
					custom_fn.show_loading("Country currency is being updated..", "it will take a couple of seconds");
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

	//popup window to confirm delete currency 
	$(document).on("click", "a.delete_currency", function()
	{
		var country_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_currency_template");
		$(".delete_currency_template").find("h4.modal-title").text("Currency Management - Delete Currency");
		$(".delete_currency_template").find("form").addClass("delete_currency_form").data("currency", $(this).data("href"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+country_name+"' Country currency will be completely lost if you continue.</li>";
		form_data += "<li>Result will not be able to fetch for '"+country_name+"' Country currency.</li>";
		form_data += "</ul><br>Are you sure to delete '"+country_name+"' Country currency?";
		$(".delete_currency_template").find("div.modal-body").html(form_data);
		$(".delete_currency_template").find("button[type='submit']").html("Continue");
		$(".delete_currency_template").toggle();
	});

	// delete currency
	$(document).on("submit", "form.delete_currency_form", function()
	{
		var url = base_url+current_controller+"/delete"+default_ext;
		var currency = $(this).data("currency");
		var form_data = new FormData();
		form_data.append("currency", currency);
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
				custom_fn.show_loading("Country currency is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_currency").filter(function(i, el)
									{
										return $(this).data("href") === currency;
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


	// End of Currency Management Module Functionlities

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
		form_data += "<label class='control-label col-sm-5' for='lang_page'>Enter Page name</label>";
		form_data += "<div class='col-sm-7 controls'>";
		form_data += "<input autocomplete='off' class='form-control' data-rule-required='true' title='Please Enter valid page name' data-rule-pattern='^([a-zA-Z]([a-zA-Z_])*)$' id='lang_page' name='lang_page' placeholder='Page name' type='text'>";
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
		form_data += "<label class='control-label col-sm-5' for='lang_page'>Enter Page name</label>";
		form_data += "<div class='col-sm-7 controls'>";
		form_data += "<input autocomplete='off' value='"+page_name+"' class='form-control' data-rule-required='true' title='Please Enter valid page name' data-rule-pattern='^([a-zA-Z]([a-zA-Z_])*)$' id='lang_page' name='lang_page' placeholder='Page name' type='text'>";
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
		$(".delete_lang_page_template").find("button[type='submit']").html("Delete with labels").addClass("confirm_delete_lang_page_labels").clone().insertBefore($(".delete_lang_page_template").find("button[type='submit']")).html("Delete only page").removeClass("confirm_delete_lang_page_labels").addClass("confirm_delete_only_lang_page");
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



	// Sitemap Module Functionalities

	// Diplay all metatags
	if($("table.manage_metatags").length > 0)
		$("table.manage_metatags").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/metatags_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 1, 3, 4]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_mtags = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_mtags.sl_no);
				$("td:eq(1)", nRow).html(jd_m_mtags.metatag);
				$("td:eq(2)", nRow).html(jd_m_mtags.description);
				$("td:eq(3)", nRow).html(jd_m_mtags.status);
				$("td:eq(4)", nRow).html(jd_m_mtags.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_mtags.metatag).data("href", jd_m_mtags.id);
			}
		}).fnSetFilteringDelay(2000);

	//submit new Metatag
	$(document).on("submit", "form.add_seo_form", function(submit_event)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
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
					custom_fn.show_loading("New Metatag is being added..", "it will take a couple of seconds");
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

	//submit update Metatag
	$(document).on("submit", "form.update_seo_form", function(submit_event)
	{
		if($(this).find("input[name]").length === $(this).find("input[name].valid").length && $(this).find("select[name]").length === $(this).find("select[name].valid").length && $(this).find("textarea[name]").length === $(this).find("textarea[name].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var tag_type = cur_form.find("[name='tag_type']");
			var tag_name = cur_form.find("[name='tag_name']");
			var form_data = new FormData(cur_form[0]);
			form_data.append("metatag", cur_form.data("href"))
			form_data.append("tag_type_old", tag_type.data("link"))
			form_data.append("tag_name_old", tag_name.data("href"))
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
					custom_fn.show_loading("Metatag is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						tag_type.data("link", response.new_data["tag_type"]);
						tag_name.data("href", response.new_data["tag_name"]);
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

	// Change Metatags visible status
	$(document).on("click", "input[type='checkbox'].metatag_status", function(click_event)
	{
		click_event.preventDefault();
		click_event.stopPropagation();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("metatag", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/seo_status"+default_ext;
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
					custom_fn.show_loading("Metatag is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Metatag is being activated..", "it will take a couple of seconds");
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

	//popup window to confirm delete Metatag 
	$(document).on("click", "a.delete_metatag", function()
	{
		var metatag_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_metatag_template");
		$(".delete_metatag_template").find("h4.modal-title").text("Sitemap Management - Delete Metatag");
		$(".delete_metatag_template").find("form").addClass("delete_metatag_form").data("metatag", $(this).data("href"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+metatag_name+"' Metatag will be completely lost if you continue.</li>";
		form_data += "<li>Except \" name => title\", none other has a default value and it will be removed.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+metatag_name+"' metatag?";
		$(".delete_metatag_template").find("div.modal-body").html(form_data);
		$(".delete_metatag_template").find("button[type='submit']").html("Continue");
		$(".delete_metatag_template").toggle();
	});

	// delete country
	$(document).on("submit", "form.delete_metatag_form", function()
	{
		var url = base_url+current_controller+"/delete_seo"+default_ext;
		var metatag = $(this).data("metatag");
		var form_data = new FormData();
		form_data.append("metatag", metatag);
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
				custom_fn.show_loading("Metatag is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_metatag").filter(function(i, el)
									{
										return $(this).data("href") === metatag;
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

	// Diplay all analytics
	if($("table.manage_analytics").length > 0)
		$("table.manage_analytics").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/analytics_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 4, 5]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_gtracker = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_gtracker.sl_no);
				$("td:eq(1)", nRow).html(jd_m_gtracker.track_code);
				$("td:eq(2)", nRow).html(jd_m_gtracker.track_name);
				$("td:eq(3)", nRow).html(jd_m_gtracker.track_type);
				$("td:eq(4)", nRow).html(jd_m_gtracker.status);
				$("td:eq(5)", nRow).html(jd_m_gtracker.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("name", jd_m_gtracker.track_name).data("href", jd_m_gtracker.id);
			}
		}).fnSetFilteringDelay(2000);


	//submit new analytic
	$(document).on("submit", "form.add_analytics_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var form_data = new FormData(cur_form[0]);
			url = base_url+current_controller+"/analytics_add"+default_ext;
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
					custom_fn.show_loading("New Analytics is being added..", "it will take a couple of seconds");
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

	// Change Analytics visible status
	$(document).on("click", "input[type='checkbox'].analytics_status", function(click_event)
	{
		click_event.preventDefault();
		click_event.stopPropagation();
		var cur_var = $(this);
		var checked = cur_var.is(":checked");
		var status = checked ? "1" : "0";
		var href = $(this).data("href");
		var form_data = new FormData();
		form_data.append("analytics", href);
		form_data.append("status", status);
		url = base_url+current_controller+"/analytics_status"+default_ext;
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
					custom_fn.show_loading("Analytics is being deactivated..", "it will take a couple of seconds");
				else
					custom_fn.show_loading("Analytics is being activated..", "it will take a couple of seconds");
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

	//update analytic
	$(document).on("submit", "form.update_analytics_form", function(submit_event)
	{
		if($(this).find("input[type='text']").length === $(this).find("input[type='text'].valid").length)
		{
			submit_event.stopPropagation();
			submit_event.preventDefault();
			var cur_form = $(this);
			var code = cur_form.find("input[name='code']");
			var track_name = cur_form.find("input[name='track_name']");
			var form_data = new FormData(cur_form[0]);
			form_data.append("analytics", cur_form.data("href"));
			form_data.append("code_old", code.data("href"));
			form_data.append("track_name_old", track_name.data("href"));
			url = base_url+current_controller+"/analytics_edit"+default_ext;
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
					custom_fn.show_loading("Analytics is being updated..", "it will take a couple of seconds");
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "true")
					{
						code.data("href", response.new_data["code"]);
						track_name.data("href", response.new_data["track_name"]);
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

	//popup window to confirm delete Analytics 
	$(document).on("click", "a.delete_analytics", function()
	{
		var analytic_name = $(this).data("name");
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("delete_analytics_template");
		$(".delete_analytics_template").find("h4.modal-title").text("Sitemap Management - Delete Analytics");
		$(".delete_analytics_template").find("form").addClass("delete_analytics_form").data("analytics", $(this).data("href"));
		var form_data = "<ul>";
		form_data += "<li>Information of '"+analytic_name+"' Analytics will be completely lost if you continue.</li>";
		form_data += "<li>Analytical record of '"+analytic_name+"' analytics will stop recording.</li>";
		form_data += "</ul>";
		form_data += "<br>Are you sure to delete '"+analytic_name+"' analytics?";
		$(".delete_analytics_template").find("div.modal-body").html(form_data);
		$(".delete_analytics_template").find("button[type='submit']").html("Continue");
		$(".delete_analytics_template").toggle();
	});

	// delete country
	$(document).on("submit", "form.delete_analytics_form", function()
	{
		var url = base_url+current_controller+"/delete_analytics"+default_ext;
		var analytics = $(this).data("analytics");
		var form_data = new FormData();
		form_data.append("analytics", analytics);
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
				custom_fn.show_loading("Analytics is being deleted..", "it will take a couple of seconds");
			},
			success: function(response)
			{
				if(response.status === "true")
				{

					var cur_row = $("body").find("a.delete_analytics").filter(function(i, el)
									{
										return $(this).data("href") === analytics;
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

	// End of Sitemap Module Functionalities



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
			form_data += "<label class='control-label col-sm-4'>Location Name</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='location_name' placeholder='Location Name' type='text'>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4'>Location Image</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='location_image' placeholder='Location Image' type='file' data-rule-accept='image/*'>";
			form_data += "<p class='help-block'><small class='text-muted'>Note: Any image file and Image will be resized.</small></p>";
			form_data += "</div>";
			form_data += "</div>";
			form_data += "<div class='form-group'>";
			form_data += "<label class='control-label col-sm-4'>Location Icon</label>";
			form_data += "<div class='col-sm-8 controls'>";
			form_data += "<input class='form-control' autocomplete='off' name='location_icon' placeholder='Location Icon (Optional)' type='file' data-rule-accept='image/png'>";
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
		form_data += "<label class='control-label col-sm-4'>Social Network Name</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='social_network_name' placeholder='Social Network Name' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'>Social Network Icon</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='icon' placeholder='Social Network Icon' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'>Social Network URL</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='url' placeholder='Social Network URL' type='text'>";
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
		form_data += "<label class='control-label col-sm-4'>Social Network Name</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' value='"+media_name+"' name='social_network_name' placeholder='Social Network Name' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'>Social Network Icon</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' value='"+media_icon+"' name='icon' placeholder='Social Network Icon' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'>Social Network URL</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='url' value='"+media_url+"' placeholder='Social Network URL' type='text'>";
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
		form_data += "<label class='control-label col-sm-4'>Clientele Title</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='clientele_name' placeholder='Clientele Name' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'>Clientele Image</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' data-rule-required='true' name='clientele_image' placeholder='Clientele Icon' type='file' data-msg-accept='Please select only PNG Files' accept='image/png'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'></label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<img class='col-sm-4 pull-left preview_img' src='"+asset_url+"images/default.png' alt='No Image.' height='60'>";
		form_data += "</div>";
		form_data += "</div>";

		$(".add_clientele_template").find("div.modal-body").html(form_data);
		$(".add_clientele_template").find("button[type='submit']").html("Add");
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
		form_data += "<label class='control-label col-sm-4'>Clientele Title</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' value='"+ce_name+"' data-rule-required='true' name='clientele_name' placeholder='Clientele Name' type='text'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'>Clientele Image</label>";
		form_data += "<div class='col-sm-8 controls'>";
		form_data += "<input class='form-control' autocomplete='off' name='clientele_image' placeholder='Clientele Icon' type='file' data-msg-accept='Please select only PNG Files' accept='image/png'>";
		form_data += "</div>";
		form_data += "</div>";
		form_data += "<div class='form-group'>";
		form_data += "<label class='control-label col-sm-4'></label>";
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



	// Homepage Feature Functionalities Modules

	// Display all Sliders list	
	if($("table.manage_slider_images").length > 0)
		$("table.manage_slider_images").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+current_controller+"/get_slider_list"+default_ext,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 2, 4, 5]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				var jd_m_slider = JSON.parse(aData[0]);
				$("td:eq(0)", nRow).html(jd_m_slider.sl_no);
				$("td:eq(1)", nRow).html(jd_m_slider.name);
				$("td:eq(2)", nRow).html(jd_m_slider.mage_html);
				$("td:eq(3)", nRow).html(jd_m_slider.content);
				$("td:eq(4)", nRow).html(jd_m_slider.status);
				$("td:eq(5)", nRow).html(jd_m_slider.actions);
				$("td a[href='javascript:void(0);'], td input[class*='toggle-control']", nRow).data("href", jd_m_slider.id).data("slider_image", jd_m_slider.image).data("slider_name", jd_m_slider.name);
			}
		}).fnSetFilteringDelay(2000);


	// Add slider image
	$(document).on("submit", "form.add_slider_image_form", function(submit_event)
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
					for(instance in CKEDITOR.instances)
					{
						CKEDITOR.instances[instance].updateElement();
						CKEDITOR.instances[instance].setData('');
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

	// delete Clientele
	$(document).on("submit", "form.delete_slider_image_form", function()
	{
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


	// End of Homepage Feature Functionalities Modules



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

	$(document).on("submit", "form.agent-credit-form", function(e)
	{
		if($(this).find("input").length === $(this).find("input.valid").length && $(this).find("textarea").length === $(this).find("textarea.valid").length)
		{
			var url = base_url+"credit/add_credit";
			var cur_form = $(this)[0];
			var form_data = new FormData(cur_form);
			form_data.append("agent_details", $(this).data("agent"));
			$(".wait_loader").show();
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
					$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>Credit being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
					$(".wait_loader").show();
				},
				success: function(response)
				{
					if(response.status === "0")
						$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Add Credit</h4>"+response.msg+"</div>");
					else
						$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Add Credit</h4>"+response.msg+"</div>");
					custom_fn.hide_loading();
					cur_form.reset();
					custom_fn.set_auto_close(5000);
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Add Credit</h4>Sorry, Operation Failed.</div>");
					custom_fn.set_auto_close(5000);
				}
			});
		}
	});

	$(document).on("submit", "form[name='activate_form']", function(e)
	{
		if($(this).find("input").length === ($(this).find("input.valid").length + $(this).find("input:disabled").length) && $(this).find("select").length === $(this).find("select.valid").length)
		{
			e.preventDefault();
			var agent = $(this).data("agent");
			var acc_type = $(this).find(".account_type").val();
			var limit_required = $(this).find(".account_type option:selected").data("limit-required");
			var limit = $(this).find("[name='credit_limit']").val();
			var set_form = $("form[name='activate_form']")[0];
			var url = base_url+"b2b/activate"

			$.ajax(
			{
				url: url,
				method: "GET",
				dataType: "JSON",
				data: {"agent" : agent, "acc_type" : acc_type, "limit" : limit},
				beforeSend: function()
				{
					$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>Agent account is being activated..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
					$(".wait_loader").show();
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "1")
					{
						$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Activation</h4>Agent account activated successfully.</div>");
						var this_var = $("select[currently-selected='true']");
							var sign = this_var.prev();
							sign.addClass("btn-success");
							sign.removeClass("btn-danger");
							sign.attr("title", "Active");
							sign.tooltip('hide').attr('data-original-title', "Active").tooltip('fixTitle');
							sign.html("<i class='icon-ok'></i>");
							this_var.data("selected", 1);
							this_var.val(this_var.data("selected"));
							if(limit_required === 1)
								this_var.parent().parent().find("td:eq(5)").html("Credit");
							else
								this_var.parent().parent().find("td:eq(5)").html("Deposit");
							this_var.removeAttr("currently-selected");
							var act_fields = this_var.closest("td").next().find(".activation_required");
							var act_fields = this_var.closest("td").next().find(".activation_required");
						act_fields.removeClass("need_activation").each(function(index)
						{
							$(this).attr("title", $(this).data("original-title").replace(" (Deacivated)", ""));
							$(this).tooltip('hide').attr('data-original-title', $(this).data("original-title").replace(" (Deacivated)", "")).tooltip('fixTitle');
						});	
					
					}
					else if(response.status === "2")
						$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Activation</h4>Sorry, Invalid operation.</div>");
					else
						$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Activation</h4>Sorry, Operation failed.</div>");
					set_form.reset();
					custom_fn.set_auto_close(5000);
					$(".mn_close").click();
				},
				error: function(response)
				{
					set_form.reset();
					custom_fn.hide_loading();
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Activation</h4>Sorry, Operation failed.</div>");
					custom_fn.set_auto_close(5000);
					$(".mn_close").click();
				}
			});
		}
	});

	$(document).on("change", ".activation_switch", function()
	{
		var selVal = $(this).val();
		var this_var = $(this);
		var agent_id = $(this).find(":selected").data("agent-id");
		var url = base_url+"b2b/update_status?agent="+agent_id+"&status="+selVal;
		
		if(selVal === "1")
		{
			$("[name='account_type_popup']").popup("show");
			$("form[name='activate_form']").data("agent", agent_id);
			$(this).attr("currently-selected", "true");
			this_var.val(this_var.data("selected"));
			return false;			
		}
		else if(selVal === "0")
		{
			$.ajax(
			{
				url: url,
				method: "GET",
				dataType: "JSON",
				beforeSend: function()
				{
					$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>Agent account is being deactivated..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
					$(".wait_loader").show();
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					if(response.status === "1")
					{
						$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Deactivation</h4>Agent deactivated successfully.</div>");
						var sign = this_var.prev();
						sign.removeClass("btn-success");
						sign.addClass("btn-danger");
						sign.attr("title", "In-active");
						sign.tooltip('hide').attr('data-original-title', "In-active").tooltip('fixTitle');
						sign.html("<i class='icon-minus'></i>");
						this_var.data("selected", selVal);
						var act_fields = this_var.closest("td").next().find(".activation_required");
						act_fields.addClass("need_activation").each(function(index)
						{
							$(this).attr("title", $(this).data("original-title")+" (Deacivated)");
							$(this).tooltip('hide').attr('data-original-title', $(this).data("original-title")+" (Deacivated)").tooltip('fixTitle');
						});	
					}
					else if(response.status === "2")
						$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Deactivation</h4>Sorry, Invalid operation.</div>");
					else
						$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Deactivation</h4>Sorry, Operation failed.</div>");
					custom_fn.set_auto_close(5000);
					this_var.val(this_var.data("selected"));
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					this_var.val(this_var.data("selected"));
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Deactivation</h4>Sorry, Operation failed.</div>");
					custom_fn.set_auto_close(5000);
					return false;
				}
			});
		}
	});

	$(document).on("change", ".pending-credit-req", function()
	{
		var tranx = $(this).data("tranx");
		var status = $(this).val();
		var prev_sib = $(this).prev();
		var url = base_url+"credit/pending_response";
		$.ajax(
		{
			url: url,
			method: "GET",
			dataType: "JSON",
			data: {"transaction" : tranx, "status" : status},
			beforeSend: function()
			{
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>Credit request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(status === "2")
				{
					prev_sib.addClass("alert-danger").removeClass("btn-info");
					prev_sib.find("i").addClass("icon-minus").removeClass("icon-info");
					prev_sib.attr("title", "Cancelled");
					prev_sib.next().detach();
					prev_sib.parent().append("<span>Cancelled</span>");
					prev_sib.tooltip('hide').attr('data-original-title', "Cancelled").tooltip('fixTitle');
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending</h4>"+response.msg+"</div>");
				}
				else if(status === "1")
				{
					if($(".agent_balance_credit").length > 0)
						window.location.reload();
					prev_sib.addClass("alert-success").removeClass("btn-info");
					prev_sib.find("i").addClass("icon-ok").removeClass("icon-info");
					prev_sib.attr("title", "Accepted");
					prev_sib.next().detach();
					prev_sib.parent().append("<span>Accepted</span>")
					prev_sib.tooltip('hide').attr('data-original-title', "Accepted").tooltip('fixTitle');
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending</h4>"+response.msg+"</div>");
				}
				else
					window.location.reload();
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".check-credit-status", function()
	{
		var tranx = $(this).data("tranx");
		var url = base_url+"credit/get_credit_status";
		$.ajax(
		{
			url: url,
			method: "GET",
			dataType: "JSON",
			data: {"transaction" : tranx},
			beforeSend: function()
			{
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				$("body").prepend(custom_fn.model_template);
				$(".model_template").addClass("check-credit-status-template");
				$(".check-credit-status-template").find("h4.modal-title").text("Credit - Status");
				$(".check-credit-status-template").find("button[type='submit']").detach();
				$(".check-credit-status-template").find("button[type='button']:eq(1)").html("okay");
				$(".check-credit-status-template").find("div.modal-body").html(response.request+"<br><br>"+response.msg);
				$(".check-credit-status-template").toggle();
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".full-settle-status", function()
	{
		var tranx = $(this).data("tranx");
		var url = base_url+"credit/get_full_settle_status";
		$.ajax(
		{
			url: url,
			method: "GET",
			dataType: "JSON",
			data: {"transaction" : tranx},
			beforeSend: function()
			{
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				$("body").prepend(custom_fn.model_template);
				$(".model_template").addClass("full-settle-status-template");
				$(".full-settle-status-template").find("h4.modal-title").text("Credit - Settlement status");
				$(".full-settle-status-template").find("button[type='submit']").detach();
				$(".full-settle-status-template").find("button[type='button']:eq(1)").html("okay");
				$(".full-settle-status-template").find("div.modal-body").css({"max-height": "400px", "overflow" : "auto"}).html(response.contents);
				$(".full-settle-status-template").toggle();
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".change-account-type", function()
	{
		if(!$(this).hasClass("need_activation"))
		{
			$("body").prepend(custom_fn.model_template);
			var acc_type = $(this).data("acc-type");
			var agent = $(this).data("href");
			var requested = $(this).data("agent-request");
			$(".model_template").addClass("b2b-change-account-type");
			$(".b2b-change-account-type").find("div.modal-body").html("<div class='row'></div>");
			var acc_type_select =$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find(">div").eq(0).find("select");
			$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find(">div").eq(1).css("display", "none");
			$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find(">div").eq(2).detach();
			$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find("select").attr("multiple", true);
			var url = base_url+"b2b/get_deposit_credit_details";
			$.ajax(
			{
				url: url,
				method: "GET",
				dataType: "JSON",
				data: {"agent" : agent, "acc_type" : acc_type},
				beforeSend: function()
				{
					$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>Credit details are being retrived..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
					$(".wait_loader").show();
				},
				success: function(response)
				{
					custom_fn.hide_loading();
					$(".b2b-change-account-type").find("h4.modal-title").text("Agency Type - "+response.account_name);

					if(response.account_name === "Credit")
					{
						$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'>"+response.account_types+"</div>");
						$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-4'>Settled Credit</label><div class='col-sm-8 controls'>"+response.settled+"</div></div>");
						$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-4'>Credit Used</label><div class='col-sm-8 controls'>"+response.used+"</div></div>");
						$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-4'>Credit Available</label><div class='col-sm-8 controls'>"+response.balance+"</div></div>");
						if(response.settled < response.used)
							$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-12 text-danger'>Note: Please make Settlement before changing agent account.</label></div>");
					}
					else
					{
						$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'>"+response.account_types+"</div>");
						$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-4'>Current Balance</label><div class='col-sm-8 controls'>"+response.balance+"</div></div>");
						if(response.balance > 0)
							$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-12 text-danger'>Note: Agency has some amount. Please make Settlement before changing agent account.</label></div>");
					}
				},
				error: function(response)
				{
					custom_fn.hide_loading();
				}
			});
$(".b2b-change-account-type").find("button[type='submit']").html("Change");
			//$(".b2b-change-account-type").find("button[type='submit']").html("Change").addClass("confirm-change-account-type").clone().insertBefore($(".b2b-change-account-type").find("button[type='submit']")).html("Reject").removeClass("confirm-change-account-type").addClass("reject-change-account-type");
			$(".b2b-change-account-type").toggle();
			$(".b2b-change-account-type").find("form").addClass("b2b-change-account-form").data("agent", $(this).data("href")).data("acc_type", acc_type);
			custom_fn.load_validate("b2b-change-account-form");
		}
	});

	/*$(document).on("click", ".change-account-type", function()
	{
		$("body").prepend(custom_fn.model_template);
		var acc_type = $(this).data("acc-type");		
		$(".model_template").addClass("b2b-change-account-type");
		$(".b2b-change-account-type").find("h4.modal-title").text("Agents - Change Account");
		$(".b2b-change-account-type").find("div.modal-body").html("<div class='row'>"+$("form.set-account-form").html()+"</div>");
		var acc_type_option =$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find(">div").eq(0).find("select option[value='"+acc_type+"']");
		var acc_type_text = acc_type_option.text();
		acc_type_option.detach();
		$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find(">div").eq(1).css("display", "none");
		$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).find(">div").eq(2).detach();
		$(".b2b-change-account-type").find("div.modal-body").find("div").eq(0).prepend("<div class='form-group clearfix'><label class='control-label col-sm-4'>Currenct Agent Type</label><div class='col-sm-8 controls'>"+acc_type_text+"</div></div>");
		$(".b2b-change-account-type").find("button[type='submit']").html("Change");
		$(".b2b-change-account-type").toggle();
		$(".b2b-change-account-type").find("form").addClass("b2b-change-account-form").data("agent", $(this).data("href")).data("acc_type", acc_type);
		custom_fn.load_validate("b2b-change-account-form");
	});*/

	$(document).on("submit", "form.b2b-change-account-form", function()
	{
		if($(this).find("input[checked").length === ($(this).find("input.valid").length + $(this).find("input:disabled").length) && $(this).find("select").length === ($(this).find("select.valid").length))
		{
			var url = base_url+"b2b/change_account";
			var form_data = new FormData($(this)[0]);
			form_data.append("agent", $(this).data("agent"));
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
					$("body").find(".model_template").detach();
					$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>Agent account is being updated..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
					$(".wait_loader").show();
				},
				success: function(response)
				{
					if(response.status === "1")
						$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Management - Change Account</h4>"+response.msg+"</div>");
					else
						$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Management - Change Account</h4>"+response.msg+"</div>");
					custom_fn.hide_loading();
					setTimeout(function(){if(response.status === "1") window.location.reload();else $("body").find(".notification .alert-close").click();}, 3000);
				},
				error: function(response)
				{
					custom_fn.hide_loading();
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Agent Management - Change Account</h4>Sorry, Failed to update password. Try agin.</div>");
					custom_fn.set_auto_close(5000);
				}
			});
		}
	});

	$(document).on("click", ".pending-credit-request", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("pending-credit-request-template");
		$(".pending-credit-request-template").find("h4.modal-title").text("Agent Credit - Pending Request");
		$(".pending-credit-request-template").find("div.modal-body").html("Do you want to accept pending credit request?");
		$(".pending-credit-request-template").find("button[type='submit']").html("Accept").addClass("confirm-pending-credit-request").data("tranx", $(this).data("href")).data("amount", $(this).data("amount")).clone().insertBefore($(".pending-credit-request-template").find("button[type='submit']")).html("Reject").removeClass("confirm-pending-credit-request").addClass("reject-pending-credit-request").data("tranx", $(this).data("href"));
		$(".pending-credit-request-template").toggle();
	});

	$(document).on("click", ".confirm-pending-credit-request", function()
	{
		var tranx = $(this).data("tranx");
		var credit = $(this).data("amount");
		var url = base_url+"credit/update_credit_status";
		var form_data = new FormData();
		form_data.append("tranx", tranx);
		form_data.append("status", "1");
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
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
				$(".pending-credit-request-template").detach();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "1")
				{
					var pcr = $(".pending-credit-request").filter(function(i, el){
									return $(this).data("href") === tranx;
								});
					pcr.attr("title", pcr.data("original-title").replace(" (Pending)", " (Accepted)"));
					pcr.tooltip('hide').attr('data-original-title', pcr.data("original-title").replace(" (Pending)", " (Accepted)")).tooltip('fixTitle');
					var cur_remain = pcr.closest("tr").find("td:eq(7)").html();
					cur_remain = parseFloat(((cur_remain * 1) - (credit * 1)));
					pcr.closest("tr").find("td:eq(7)").html(cur_remain.toFixed(6));
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Request</h4>"+response.msg+"</div>");
					pcr.removeClass("btn-pending").addClass("btn-success").removeClass("pending-credit-request");
				}
				else
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Request</h4>"+response.msg+"</div>");
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});
	$(document).on("click", ".reject-pending-credit-request", function()
	{
		var tranx = $(this).data("tranx");
		var url = base_url+"credit/update_credit_status";
		var form_data = new FormData();
		form_data.append("tranx", tranx);
		form_data.append("status", "2");
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
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
				$(".pending-credit-request-template").detach();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "1")
				{
					var pcr = $(".pending-credit-request").filter(function(i, el){
									return $(this).data("href") === tranx;
								});
					pcr.attr("title", pcr.data("original-title").replace(" (Pending)", " (Rejected)"));
					pcr.tooltip('hide').attr('data-original-title', pcr.data("original-title").replace(" (Pending)", " (Rejected)")).tooltip('fixTitle');
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Request</h4>"+response.msg+"</div>");
					pcr.removeClass("btn-pending").addClass("btn-danger").removeClass("pending-credit-request");
				}
				else
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Request</h4>"+response.msg+"</div>");
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".pending-partial-settlement", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("pending-partial-settlement-template");
		$(".pending-partial-settlement-template").find("h4.modal-title").text("Agent Credit - Pending Partial Settlement");
		$(".pending-partial-settlement-template").find("div.modal-body").html("Agent has requested for partial payment of amount <strong>\""+$(this).data("amount")+"\"</strong> on <strong>\""+$(this).data("deposited")+"\"</strong><br>Are you sure to confirm this partial payment?");
		$(".pending-partial-settlement-template").find("button[type='submit']").html("Accept").addClass("confirm-pending-partial-settlement").data("tranx", $(this).data("href")).data("amount", $(this).data("amount")).clone().insertBefore($(".pending-partial-settlement-template").find("button[type='submit']")).html("Reject").removeClass("confirm-pending-partial-settlement").addClass("reject-pending-partial-settlement").data("tranx", $(this).data("href"));
		$(".pending-partial-settlement-template").toggle();
	});

	$(document).on("click", ".confirm-pending-partial-settlement", function()
	{
		var tranx = $(this).data("tranx");
		var credit = $(this).data("amount");
		var url = base_url+"credit/make_partial_payment";
		var form_data = new FormData();
		form_data.append("tranx", tranx);
		form_data.append("status", "1");
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
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
				$(".pending-partial-settlement-template").detach();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "1")
				{
					var pps = $(".pending-partial-settlement").filter(function(i, el){
									return $(this).data("href") === tranx;
								});
					pps.attr("title", pps.data("original-title").replace(" (Pending)", " (Accepted)"));
					pps.tooltip('hide').attr('data-original-title', pps.data("original-title").replace(" (Pending)", " (Accepted)")).tooltip('fixTitle');
					var cur_settle = pps.closest("tr").find("td:eq(8) span").html();
					cur_settle = parseFloat(((cur_settle * 1) + (credit * 1)));
					pps.closest("tr").find("td:eq(8) span").html(cur_settle.toFixed(6));
					var cur_pending = pps.closest("tr").find("td:eq(9)").html();
					cur_pending = parseFloat(((cur_pending * 1) - (credit * 1)));
					pps.closest("tr").find("td:eq(9)").html(cur_pending.toFixed(6));
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Partial Settlement</h4>"+response.msg+"</div>");
					pps.removeClass("btn-pending").addClass("btn-success").removeClass("pending-partial-settlement");
				}
				else
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Partial Settlement</h4>"+response.msg+"</div>");
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".reject-pending-partial-settlement", function()
	{
		var tranx = $(this).data("tranx");
		var url = base_url+"credit/make_partial_payment";
		var form_data = new FormData();
		form_data.append("tranx", tranx);
		form_data.append("status", "2");
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
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
				$(".pending-partial-settlement-template").detach();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "1")
				{
					var pps = $(".pending-partial-settlement").filter(function(i, el){
									return $(this).data("href") === tranx;
								});
					pps.attr("title", pps.data("original-title").replace(" (Pending)", " (Rejected)"));
					pps.tooltip('hide').attr('data-original-title', pps.data("original-title").replace(" (Pending)", " (Rejected)")).tooltip('fixTitle');
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Partial Settlement</h4>"+response.msg+"</div>");
					pps.removeClass("btn-pending").addClass("btn-danger").removeClass("pending-partial-settlement");
				}
				else
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Partial Settlement</h4>"+response.msg+"</div>");
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".pending-full-settlement", function()
	{
		$("body").prepend(custom_fn.model_template);
		$(".model_template").addClass("pending-full-settlement-template");
		$(".pending-full-settlement-template").find("h4.modal-title").text("Agent Credit - Pending Partial Settlement");
		$(".pending-full-settlement-template").find("div.modal-body").html("Agent has requested for full settlement of amount <strong>\""+$(this).data("amount")+"\"</strong> on <strong>\""+$(this).data("deposited")+"\"</strong><br>Are you sure to confirm this full settlement?");
		$(".pending-full-settlement-template").find("button[type='submit']").html("Accept").addClass("confirm-pending-full-settlement").data("tranx", $(this).data("href")).data("amount", $(this).data("amount")).clone().insertBefore($(".pending-full-settlement-template").find("button[type='submit']")).html("Reject").removeClass("confirm-pending-full-settlement").addClass("reject-pending-full-settlement").data("tranx", $(this).data("href"));
		$(".pending-full-settlement-template").toggle();
	});

	$(document).on("click", ".confirm-pending-full-settlement", function()
	{
		var tranx = $(this).data("tranx");
		var credit = $(this).data("amount");
		var url = base_url+"credit/make_full_settlement";
		var form_data = new FormData();
		form_data.append("tranx", tranx);
		form_data.append("status", "1");
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
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
				$(".pending-full-settlement-template").detach();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "1")
				{
					var pfs = $(".pending-full-settlement").filter(function(i, el){
									return $(this).data("href") === tranx;
								});
					pfs.attr("title", pfs.data("original-title").replace(" (Pending)", " (Accepted)"));
					pfs.tooltip('hide').attr('data-original-title', pfs.data("original-title").replace(" (Pending)", " (Accepted)")).tooltip('fixTitle');
					var cur_settle = pfs.closest("tr").find("td:eq(8) span").html();
					cur_settle = parseFloat(((cur_settle * 1) + (credit * 1)));
					pfs.closest("tr").find("td:eq(4)").html(pfs.closest("tr").find("td:eq(6)").html());
					pfs.closest("tr").find("td:eq(7)").html("0.000000");
					pfs.closest("tr").find("td:eq(8) span").html(cur_settle.toFixed(6));
					pfs.closest("tr").find("td:eq(9)").html("0.000000");
					pfs.closest("tr").find("td:eq(10)").html("No Settlement Pending.");
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Full Settlement</h4>"+response.msg+"</div>");
					pfs.removeClass("btn-pending").addClass("btn-success").removeClass("pending-full-settlement");
				}
				else
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Full Settlement</h4>"+response.msg+"</div>");
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".reject-pending-full-settlement", function()
	{
		var tranx = $(this).data("tranx");
		var url = base_url+"credit/make_full_settlement";
		var form_data = new FormData();
		form_data.append("tranx", tranx);
		form_data.append("status", "2");
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
				$(".wait_loader").html("<div class='full-center'><div class='spinner-clock'><div class='spinner-clock-hour'></div><div class='spinner-clock-minute'></div></div><h2 class='mb5'>request being processed..</h2><p class='text-bigger'>it will take a couple of seconds</p></div>");
				$(".wait_loader").show();
				$(".pending-full-settlement-template").detach();
			},
			success: function(response)
			{
				custom_fn.hide_loading();
				if(response.status === "1")
				{
					var pfs = $(".pending-full-settlement").filter(function(i, el){
									return $(this).data("href") === tranx;
								});
					pfs.attr("title", pfs.data("original-title").replace(" (Pending)", " (Rejected)"));
					pfs.tooltip('hide').attr('data-original-title', pfs.data("original-title").replace(" (Pending)", " (Rejected)")).tooltip('fixTitle');
					$("body").find(".notification").html("<div class='alert alert-block alert-success alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Full Settlement</h4>"+response.msg+"</div>");
					pfs.removeClass("btn-pending").addClass("btn-danger").removeClass("pending-full-settlement");
				}
				else
					$("body").find(".notification").html("<div class='alert alert-block alert-danger alert-dismissable'><a href='javascript:void(0)' data-dismiss='alert' class='alert-close close'>×</a><h4 class='alert-heading'>Credit - Pending Full Settlement</h4>"+response.msg+"</div>");
				custom_fn.set_auto_close(5000);
			},
			error: function(response)
			{
				custom_fn.hide_loading();
				custom_fn.set_auto_close(5000);
			}
		});
	});

	$(document).on("click", ".trans_slip", function(e)
	{
		e.preventDefault();
		var trans_slip = $(this).data("href");
		$("#transSlipCont").attr("src", "");
		$("#transSlipCont").attr("src", trans_slip);
		$("#transSlipPop").popup("show");
	});

	if($(".deposit_agents").length > 0)
		$(".deposit_agents").dataTable({
			"dom": "<'clearfix'T>lfrtip",
			"oTableTools":
			{
				"aButtons":
				[
					{
						"sExtends": "csv",
						"sFieldBoundary": '"',
						//"sFieldSeperator": "-",
						// "bSelectedOnly": true,
						// "sCharSet": "utf8",
						"sFileName": "Deposit Agents" + ".csv"
					},
					{
						"sExtends": "pdf",
						"sPdfSize": "tabloid",
						"sPdfOrientation": "landscape",
						"sPdfMessage": "Deposit Agents List",
						"sFileName": "Deposit Agents" + ".pdf"
					}
				],
				"sSwfPath": base_url+"10020_assets/swf/copy_csv_xls_pdf.swf",
			},
			"oLanguage":
			{
				"sSearch": "Search:"
			},
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+"deposit/get_agent_list",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [2, 5, 6, 7, 8, 9]},
								{"bVisible": false, "aTargets": [1, 7, 8, 9]},
								{ // merge email and company into this column
									"mRender": function(data, type, column) {
										return oObj.aData[0] +"<br>["+ oObj.aData[1]+"]";
									},
									"aTargets": [ 0 ]
								}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				$("td:eq(1)", nRow).html("<a data-lightbox='flatty' href='"+aData[2]+"'> <img width='50' height='50' title='"+aData[2]+"' alt='' src='"+aData[2]+"'></a>");
				if(aData[5] === "1")
					$("td:eq(4)", nRow).html("<a class='btn btn-success btn-xs has-tooltip mrgn_tr' data-placement='top' title='Active'> <i class='icon-ok'></i> </a><select data-selected='1' data-acc-type='"+aData[8]+"' class='activation_switch'><option data-agent-id='"+aData[7]+"' value='1' selected>Activate</option><option data-agent-id='"+aData[7]+"' value='0'>De-activate</option></select>");
				else if(aData[5] === "0")
					$("td:eq(4)", nRow).html("<a class='btn btn-danger btn-xs has-tooltip mrgn_tr' data-placement='top' title='In-active'> <i class='icon-minus'></i> </a><select data-selected='0' data-acc-type='"+aData[8]+"' class='activation_switch'><option data-agent-id='"+aData[7]+"' value='1'>Activate</option><option data-agent-id='"+aData[7]+"' value='0' selected >De-activate</option></select>");
				else
					$("td:eq(4)", nRow).html("<a class='btn btn-warning btn-xs has-tooltip mrgn_tr' data-placement='top' title='Send Verfication Details' href='"+base_url+"b2b/send_Verfication_detatils/"+aData[7]+"'> <i class='icon-envelope'></i></a> <a class='btn btn-danger  btn-xs has-tooltip mrgn_tr' data-placement='top' title='Check the user Log' target='_blank' href='"+base_url+"b2b/check_user_log_detatils/"+aData[7]+"'> <i class='icon-info'></i></a><br>Not Verified");
				var actions = "<div class='pull-left'>";
				//actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' target='_blank' data-placement='top' title='Connect to Agent' href='"+front_url+"agent/connect/"+aData[10]+"'><i class='icon-external-link'></i></a>\n";
				if(aData[9] === "0")
					actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Change Account Type' href='javascript:void(0);'><i class='icon-exchange'></i></a>\n";
				else
					actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons bounce change-account-type' data-placement='top' title='Agent requested' href='javascript:void(0);' data-href='"+aData[7]+"' data-acc-type='"+aData[8]+"'><i class='icon-exchange'></i></a>\n";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Bookings' href='javascript:void(0);' data-href='"+aData[7]+"'><i class='icon-eye-open'></i></a>\n";
				actions += "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Send Mail' href='javascript:void(0);' data-href='"+aData[7]+"'><i class='icon-envelope'></i></a>\n";
				actions += "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Deposit Details' href='"+base_url+"deposit/agent_deposit.html?agent="+aData[7]+"'><i class='icon-usd'></i></a>\n";
				actions += "<br>";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Profile' href='"+base_url+"b2b/view_profile.html?agent="+aData[7]+"'><i class='icon-search'></i></a>\n";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Edit User' href='"+base_url+"b2b/edit_profile.html?agent="+aData[7]+"'><i class='icon-edit'></i></a>\n";
				actions += "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons change-pwd-b2b' data-placement='top' title='Change Password' href='javascript:void(0);' data-href='"+aData[7]+"'><i class='icon-lock'></i></a>\n";
				//actions += "<a class='btn btn-danger btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Agent cannot be deleted. you can de-activate agent account.' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				actions += "</div>";
				$("td:eq(5)", nRow).html(actions);
			}
		}).fnSetFilteringDelay(2000);

	if($(".deposit_requests").length > 0)
		$(".deposit_requests").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+"deposit/get_all_deposit_reqs",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [11]},
								{"bVisible": false, "aTargets": [11]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				if(aData[10] === "2")
					$("td:eq(10)", nRow).html("<a class='btn btn-danger btn-xs has-tooltip' data-placement='top' title='Cancelled'><i class='icon-minus'></i></a><span>Cancelled</span>");
				else if(aData[10] === "1")
					$("td:eq(10)", nRow).html("<a class='btn btn-accept btn-xs has-tooltip' data-placement='top' title='Accepted'><i class='icon-ok'></i></a><span>Accepted</span>");
				else
					$("td:eq(10)", nRow).html("<a class='btn btn-primary btn-xs has-tooltip action_icons' data-placement='top' title='Cancelled'><i class='icon-info'></i></a><select data-tranx='"+aData[11]+"' class='pending-deposit-req'><option value='0' selected>Pending</option><option value='1'>Accept</option><option value='2'>Cancel</option></select>");
			}
		}).fnSetFilteringDelay(2000);

	if($(".credit_requests").length > 0)
		$(".credit_requests").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+"credit/get_all_credit_reqs",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]},
								{"bVisible": false, "aTargets": [13, 14, 15, 16, 17]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				$("td:eq(6) span", nRow).data("tranx", aData[13]);
				$("td:eq(9) span", nRow).data("tranx", aData[13]);
				$("td:eq(12) a.pending-credit-request", nRow).data("href", aData[13]).data("amount", aData[15]);
				$("td:eq(12) a.pending-partial-settlement", nRow).data("href", aData[14]).data("amount", aData[15]).data("deposited", aData[16]);
				$("td:eq(12) a.pending-full-settlement", nRow).data("href", aData[17]).data("amount", aData[15]).data("deposited", aData[16]);
			}
		}).fnSetFilteringDelay(2000);

	if($(".credit_agents").length > 0)
		$(".credit_agents").dataTable({
			"sDom": "<'clearfix'T>lfrtip",
			"oTableTools":
			{
				"aButtons":
				[
					{
						"sExtends": "csv",
						"sFieldBoundary": '"',
						//"sFieldSeperator": "-",
						// "bSelectedOnly": true,
						// "sCharSet": "utf8",
						"sFileName": "Credit Agents" + ".csv"
					},
					{
						"sExtends": "pdf",
						"sPdfSize": "tabloid",
						"sPdfOrientation": "landscape",
						"sPdfMessage": "Credit Agents List",
						"sFileName": "Credit Agents" + ".pdf"
					}
				],
				"sSwfPath": base_url+"10020_assets/swf/copy_csv_xls_pdf.swf",
			},
			"oLanguage":
			{
				"sSearch": "Search:"
			},
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+"credit/get_agent_list",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [2, 8, 9, 10, 11, 12]},
								{"bVisible": false, "aTargets": [1, 10, 11, 12]},
								{ // merge email and company into this column
									"mRender": function(data, type, column) {
										return oObj.aData[0] +"<br>["+ oObj.aData[1]+"]";
									},
									"aTargets": [ 0 ]
							}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				$("td:eq(1)", nRow).html("<a data-lightbox='flatty' href='"+aData[2]+"'> <img width='50' height='50' title='"+aData[2]+"' alt='' src='"+aData[2]+"'></a>");
				if(aData[8] === "1")
					$("td:eq(7)", nRow).html("<a class='btn btn-success btn-xs has-tooltip mrgn_tr' data-placement='top' title='Active'> <i class='icon-ok'></i> </a><select data-selected='1' data-acc-type='"+aData[11]+"' class='activation_switch'><option data-agent-id='"+aData[10]+"' value='1' selected>Activate</option><option data-agent-id='"+aData[10]+"' value='0'>De-activate</option></select>");
				else if(aData[8] === "0")
					$("td:eq(7)", nRow).html("<a class='btn btn-danger btn-xs has-tooltip mrgn_tr' data-placement='top' title='In-active'> <i class='icon-minus'></i> </a><select data-selected='0' data-acc-type='"+aData[11]+"' class='activation_switch'><option data-agent-id='"+aData[10]+"' value='1'>Activate</option><option data-agent-id='"+aData[10]+"' value='0' selected >De-activate</option></select>");
				else
					$("td:eq(7)", nRow).html("<a class='btn btn-warning btn-xs has-tooltip mrgn_tr' data-placement='top' title='Send Verfication Details' href='"+base_url+"b2b/send_Verfication_detatils/"+aData[10]+"'> <i class='icon-envelope'></i></a> <a class='btn btn-danger  btn-xs has-tooltip mrgn_tr' data-placement='top' title='Check the user Log' target='_blank' href='"+base_url+"b2b/check_user_log_detatils/"+aData[10]+"'> <i class='icon-info'></i></a><br>Not Verified");
				var actions = "<div class='pull-left'>";
				//actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' target='_blank' data-placement='top' title='Connect to Agent' href='"+front_url+"agent/connect/"+aData[10]+"'><i class='icon-external-link'></i></a>\n";
				if(aData[12] === "0")
					actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Change Account Type' href='javascript:void(0);'><i class='icon-exchange'></i></a>\n";
				else
					actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons bounce change-account-type' data-placement='top' title='Agent requested' href='javascript:void(0);' data-href='"+aData[10]+"' data-acc-type='"+aData[11]+"'><i class='icon-exchange'></i></a>\n";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Bookings' href='javascript:void(0);' data-href='"+aData[10]+"'><i class='icon-eye-open'></i></a>\n";
				actions += "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Send Mail' href='javascript:void(0);' data-href='"+aData[10]+"'><i class='icon-envelope'></i></a>\n";
				actions += "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Credit Details' href='"+base_url+"credit/agent_credit.html?agent="+aData[10]+"'><i class='icon-usd'></i></a>\n";
				actions += "<br>";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Partial Settlement : No request has made.' href='javascript:void(0);' data-href='"+aData[10]+"'><i class='icon-money'></i></a>\n";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Full Settlement : No request has made.' href='javascript:void(0);' data-href='"+aData[10]+"'><i class='icon-money'></i></a>\n";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='View Profile' href='"+base_url+"b2b/view_profile.html?agent="+aData[10]+"'><i class='icon-search'></i></a>\n";
				actions += "<a class='btn btn-primary btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Edit User' href='"+base_url+"b2b/edit_profile.html?agent="+aData[10]+"'><i class='icon-edit'></i></a>\n";
				actions += "<a class='btn btn-warning btn-xs has-tooltip mrgn_top action_icons change-pwd-b2b' data-placement='top' title='Change Password' href='javascript:void(0);' data-href='"+aData[10]+"'><i class='icon-lock'></i></a>\n";
				//actions += "<a class='btn btn-danger btn-xs has-tooltip mrgn_top action_icons' data-placement='top' title='Agent cannot be deleted. you can de-activate agent account.' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				actions += "</div>";
				$("td:eq(8)", nRow).html(actions);
			}
		}).fnSetFilteringDelay(2000);

	if($(".single-agent-credits").length > 0)
	{
		agent = $(".single-agent-credits").data("agent");
		$(".single-agent-credits").dataTable({
			"dom": "lfrtip",
			"bProcessing": false,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": base_url+"credit/get_agent_credit_reqs.html?agent="+agent,
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": false,
			"aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 5, 7, 8]},
								{"bVisible": false, "aTargets": [8]}],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
			{
				if(aData[7] === "2")
					$("td:eq(7)", nRow).html("<a class='btn btn-danger btn-xs has-tooltip' data-placement='top' title='Cancelled'><i class='icon-minus'></i></a><span>Cancelled</span>");
				else if(aData[7] === "1")
					$("td:eq(7)", nRow).html("<a class='btn btn-accept btn-xs has-tooltip' data-placement='top' title='Accepted'><i class='icon-ok'></i></a><span>Accepted</span>");
				else
					$("td:eq(7)", nRow).html("<a class='btn btn-primary btn-xs has-tooltip action_icons' data-placement='top' title='Cancelled'><i class='icon-info'></i></a><select data-tranx='"+aData[8]+"' class='pending-credit-req'><option value='0' selected>Pending</option><option value='1'>Accept</option><option value='2'>Cancel</option></select>");
			}
		}).fnSetFilteringDelay(2000);
	}
});
});
