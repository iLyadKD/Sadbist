require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url    	   = $("head").data("base-url");
		var default_ext        = $("head").data("default-ext");
		var asset_url          = $("head").data("asset-url");
		var current_controller = $("head").data("controller");
		var current_method     = $("head").data("method");
	
		
		if($("form.add_callcenter_staff_form").length > 0)
			custom_fn.load_validate("add_callcenter_staff_form");


		// Add new staff
		$(document).on("submit", "form.add_callcenter_staff_form", function(submit_event)
		{
			console.log($(this).find("input[name][type='text']").length);
			console.log($(this).find("input[name][type='text'].valid").length);
			
			if(($(this).find("input[name][type='text']").length) === ($(this).find("input[name][type='text'].valid").length))
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				// url = base_url+current_controller+"/add_staff"+default_ext;
				url = "add_staff";
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
						custom_fn.show_loading("New staff is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{ 
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
						}
						
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
					},
					error: function(response)
					{
						custom_fn.hide_loading();
						custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
						custom_fn.set_auto_close(function(){},5000);
					}
				});
			}
		});
		
		$('.assign-staff').click(function(){
			$('#item_val').val($(this).data('item'));
		});

		//assign staff form submit
		$('#assign-staff-form').submit(function(){
			
			data = $( this ).serialize(); 
			url1 = base_url+current_controller+"/assign_staff"+default_ext;
			$.ajax({
				url: url1,
				data: data,
				type: 'POST',
				success: function(response){
					if(response > 0){

						custom_fn.show_status_msg('success', 'Success', 'Assigned to staff successfully !');
						custom_fn.set_auto_close(4000);
						location.reload();
					}
				}
			});

			return false;
		});

		// $('.update-status-btn').click(function(){
		// 	$('#item_val_status').val($(this).data('item'));
		// });

		//dynamic inputs according to selected status
		$('#cc_status').change(function(){
			var status = $(this).val();
			if(status != "" || status != 0){
				$.ajax({
					url: base_url+current_controller+"/getInputElements"+default_ext,
					data: {'status' : status },
					type: 'POST',
					success: function(response){
						// console.log(response);return false;
						$('#result-inputs').html('');
						$('#result-inputs').html(response);
						custom_fn.load_validate("update-status-form");
						return false;
					}
				})
			}
			return false;
		});

		//update-status-form
		$(document).on("click", "#update-btn-form", function(submit_event){
			// submit_event.stopPropagation();
			// submit_event.preventDefault();
			if($('#update-status-form').valid()){
				data = $('#update-status-form').serialize(); 
				url1 = base_url+current_controller+"/update_item_status"+default_ext;
				$.ajax({
					url: url1,
					data: data,
					type: 'POST',
					success: function(response){
// console.log(response);return false;
						if(response > 0){

							custom_fn.show_status_msg('success', 'Success', 'Status updated successfully !');
							custom_fn.set_auto_close(4000);
							location.reload();
						}
						else{

							custom_fn.show_status_msg('danger', 'Failed', 'Operation failed !');
							custom_fn.set_auto_close(4000);
							location.reload();
						}
					}
				});
			}
			

			return false;
		});

		//update-details-form
		$(document).on("click", "#proceed-update-details-btn", function(submit_event){
			// submit_event.stopPropagation();
			// submit_event.preventDefault();
			var form = $('.update-item-details-form');
			if(form.valid()){
				data = form.serialize(); 
				url1 = base_url+current_controller+"/update_item_details"+default_ext;
				$.ajax({
					url: url1,
					data: data,
					type: 'POST',
					dataType: 'json',
					beforeSend: function()
					{
						//show popup
						custom_fn.show_loading("Details are being updated", "it will take a couple of seconds");
					},
					success: function(response){
						// console.log(response);return false;
						// if(response > 0){

							custom_fn.show_status_msg(response.msg_status, response.status, response.msg);
							custom_fn.set_auto_close(2000);
							location.reload();
						// }
						// else{

						// 	custom_fn.show_status_msg('danger', 'Failed', 'Operation failed !');
						// 	custom_fn.set_auto_close(4000);
						// 	location.reload();
						// }
					}
				});
			}
			

			return false;
		});



		if($("form.add_company_form").length > 0)
			custom_fn.load_validate("add_company_form");

		// Add new company
		$(document).on("submit", "form.add_company_form", function(submit_event)
		{
			console.log($(this).find("input[name][type='text']").length);
			console.log($(this).find("input[name][type='text'].valid").length);
			
			if(($(this).find("input[name][type='text']").length) === ($(this).find("input[name][type='text'].valid").length))
			{
				submit_event.stopPropagation();
				submit_event.preventDefault();
				var cur_form = $(this);
				var form_data = new FormData(cur_form[0]);
				// url = base_url+current_controller+"/add_staff"+default_ext;
				url = "add_company";
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
						custom_fn.show_loading("New company is being added..", "it will take a couple of seconds");
					},
					success: function(response)
					{ 
						custom_fn.hide_loading();
						if(response.status === "true")
						{
							cur_form[0].reset();
						}
						
						custom_fn.show_status_msg(response.msg_status, response.title, response.msg);
						custom_fn.set_auto_close(7000);
					},
					error: function(response)
					{
						custom_fn.hide_loading();
						custom_fn.show_status_msg("danger", "Status", "Sorry, Operation failed.");
						custom_fn.set_auto_close(function(){},5000);
					}
				});
			}
		});


		//Refresh Item Details Table
		$('.item-data-refresh').click(function(){
			url = base_url+current_controller+"/update_items"+default_ext;
			$.ajax({
				url: url,
				type: 'POST',
				beforeSend: function()
				{
					//show popup
					custom_fn.show_loading("Details are being updated", "it will take a couple of seconds");
				},
				success: function(response){
						location.reload();
					
				}
			});
		});



		// Company List Table Functionalities
		if($("table#manage_company_list").length > 0)
			$("table#manage_company_list").dataTable({
				"dom": "lfrtip",
				"bProcessing": false,
				"bServerSide": true,
				"sServerMethod": "GET",
				"sAjaxSource": base_url+current_controller+"/companies_list"+default_ext,
				"iDisplayLength": 10,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": false,
				"aoColumnDefs": [{"bSearchable": false, "aTargets": [1,2,3,5]}],
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{ 
					var jd_m_b2c = JSON.parse(aData);

					$("td:eq(0)", nRow).html(jd_m_b2c.sl_no);
					$("td:eq(1)", nRow).html(jd_m_b2c.company_name);
					$("td:eq(2)", nRow).html(jd_m_b2c.url);
					$("td:eq(3)", nRow).html(jd_m_b2c.u_name);
					$("td:eq(4)", nRow).html(jd_m_b2c.p_word);
					$("td:eq(5)", nRow).html(jd_m_b2c.contact_name);
					$("td:eq(6)", nRow).html(jd_m_b2c.support_phone);
					$("td:eq(7)", nRow).html(jd_m_b2c.other_phone);
					$("td:eq(8)", nRow).html(jd_m_b2c.comment);
					
				}
			}).fnSetFilteringDelay(1000);


		$('.view_by').click(function(){
			var status = $('#sort_status').val();
			var assigned_to = $('#sort_assigned').val();
			if(status == "" && assigned_to == ""){
				return false;
			}

			url = base_url+current_controller+"/setSortFilter"+default_ext;
			$.ajax({
				url: url,
				type: 'POST',
				data: {'status' : status, 'assigned_to' : assigned_to},
				beforeSend: function()
				{
					//show popup
					custom_fn.show_loading("Details are being filtered", "it will take a couple of seconds");
				},
				success: function(response){
					// console.log(response);return false;
						location.reload();
					
				}
			});
		});

	});

	if($("table.manage_cc_items").length > 0){
			$("table.manage_cc_items").dataTable({
				"dom": "lfrtip",
				"iDisplayLength": 50,
				"bStateSave": true,
				"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
				"ordering": true,
				// "aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6, 7]}]
			});
	}





	/*Date Picker Init*/

	var dt = new Date();
	var adult_age = dt.getFullYear() - 12;
	dt.setFullYear(adult_age);
	var dateFormat = "dd-mm-yy";
	$(".dt_dob.adult").datepicker(
	{	
		dateFormat: dateFormat,
		maxDate: "-12Y",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		yearRange: "1920:"+adult_age,
		defaultDate: dt
	});
	dt = new Date();
	var child_age_min = dt.getFullYear() - 2;
	var child_age_max = dt.getFullYear() - 12;
	dt.setFullYear(child_age_min);
	$(".dt_dob.child").datepicker(
	{
		dateFormat: dateFormat,
		maxDate: "-2Y",
		minDate: "-12Y",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		yearRange: child_age_max+":"+child_age_min,
		defaultDate: dt
	});
	dt = new Date();
	var infant_age_min = dt.getFullYear();
	var infant_age_max = dt.getFullYear() - 2;
	dt.setFullYear(infant_age_min);
	$(".dt_dob.infant").datepicker(
	{
		dateFormat: dateFormat,
		maxDate: 0,
		minDate: "-2Y",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		yearRange: infant_age_max+":"+infant_age_min,
		defaultDate: dt
	});


	if($(".from_current_date").length > 0)
		$(".from_current_date").datepicker(
		{	
			dateFormat: "dd/mm/yy",
			minDate: 1,
			changeMonth: true,
			showButtonPanel: true,
			changeYear: true
		});

	$(".pax_country").change(function(){
		var val = $(this).val();
		var dataClass = $(this).data('class');
		console.log(val,dataClass);
		if(val != 'IR'){
			$("."+dataClass+'.non_iran_class').css('display','block');
			$("."+dataClass+'.iran_class').css('display','none');
			return false;
		}

		$("."+dataClass+'.non_iran_class').css('display','none');
		$("."+dataClass+'.iran_class').css('display','block');
		return false;



	})

	


});
