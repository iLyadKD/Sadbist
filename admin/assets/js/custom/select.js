require(["select2"], function()
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");
		var select2_page_count = 10;


		$(".select2").select2();
		

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

		// non-ajax call to get support subjects list
		// if($("select.set_support_subjects1").length > 0)
		// {
		// 	var sss = $("select.set_support_subjects").data("href") !== undefined ? $("select.set_support_subjects").data("href") : "";
		// 	var url = base_url+"ajax/get_support_subjects"+default_ext;
		// 	var xmlhttp;
		// 	if (window.XMLHttpRequest)// code for IE7+, Firefox, Chrome, Opera, Safari
		// 		xmlhttp = new XMLHttpRequest();
		// 	else // code for IE6, IE5
		// 		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		// 	xmlhttp.open("GET",url,true);
		// 	xmlhttp.send();
		// 	xmlhttp.onreadystatechange = function()
		// 								{
		// 									if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		// 									{
		// 										var response = JSON.parse(xmlhttp.responseText);
		// 										$("select.set_support_subjects").html(response.result);
		// 										if(sss !== "")
		// 										{
		// 											$("select.set_support_subjects").val(sss);
		// 											$("select.set_support_subjects").data("href", "");
		// 											$("select.set_support_subjects").change();
		// 										}
		// 										$("select.set_support_subjects").select2();
		// 									}
		// 									else
		// 									{
		// 										$("select.set_support_subjects").html("");
		// 										$("select.set_support_subjects").select2();
		// 									}
		// 								}
		// }

		if($("select.set_support_user_type").length > 0)
		{
			var ssut = $("select.set_support_user_type").data("href") !== undefined ? $("select.set_support_user_type").data("href") : "";
			var result = "<option value=''>Select User Type</option>";
			result += "<option value='2'>Admin</option>";
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
								$(this_var).closest("form").data("is-updated", true);
								$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
										$(this_var).closest("form").data("is-updated", true);
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
										$("input[name='state_name']").val(option.params.data.text).data("href", option.params.data.text);
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
														$(this_var).closest("form").data("is-updated", true);
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
						if(type !== "" && type !== null)
							cur_form.find(".optional_values.common").removeClass("hide").find("input, select").removeAttr("disabled");
						$(this_var).closest("form").data("is-updated", true);
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
					if(type !== "" && type !== null)
					{
						cur_form.find(".optional_values.common").removeClass("hide").find("input, select").removeAttr("disabled");
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
						$(this_var).closest("form").data("is-updated", true);
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
	});
});
