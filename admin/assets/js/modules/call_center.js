require(["custom_defination"], function(custom_fn)
{


	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+ d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	$(document).ready(function()
	{
		// Global variables
		var base_url    	   = $("head").data("base-url");
		var default_ext        = $("head").data("default-ext");
		var asset_url          = $("head").data("asset-url");
		var current_controller = $("head").data("controller");
		var current_method     = $("head").data("method");
		var user_type		   = $("head").data("cc-user-type"); // GET USER TYPE
		var filtered_data = null;
		var filtered_data_count = null;

		var selected_auto_refresh = getCookie('auto_refresh') ? getCookie('auto_refresh') : 10;
		if (user_type == 1 || user_type ==2) {
			if ($('.item-data-refresh').html()) {
				setInterval( function() {
					if (
					    !$('#assignsmsModal').hasClass('in') &&
					    !$('#cancelsmsModal').hasClass('in') &&
					    !$('#set-auto-refresh').hasClass('in') &&
					    !$('#assignsmsModal').hasClass('filtering') &&
					    $('#items_tab').hasClass('active')
					   ) {
						$('.item-data-refresh').click();
					}
				}, selected_auto_refresh * 1000 ); // if user is an admin, Refresh page every 10 seconds
			}
		}
		
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


		//set auto refresh form
		$('#set-auto-refresh-form').submit(function(e){
			e.preventDefault();
			var auto_refresh_val = $('#set-auto-refresh-form').find('#auto_refresh').val();
			setCookie('auto_refresh', auto_refresh_val , 1000);
			location.reload();
		});

		//send sms form submit
		$('#cancel-sms-form').submit(function(e){
			e.preventDefault();
			var current_flight_ids = $("table.manage_cc_items").DataTable().columns({ filter : 'applied'}).data()[2];

			if (!confirm('Are You Sure You Want To Send Cancel Message To ' + current_flight_ids.length + ' Customers?')) { return; }

			data = $( this ).serializeArray(); 
			data.push({name: "items_val", value: JSON.stringify(current_flight_ids)});
			data.push({name: "cancel", value: 1});
			url1 = base_url+current_controller+"/prepare_sms_to_current_items"+default_ext;
			$.ajax({
				url: url1,
				data: $.param(data),
				type: 'POST',
				dataType: 'json',
				success: function(data){
					if(data.response > 0){
						custom_fn.show_status_msg('success', 'Success', 'Send Messages successfully !');
						custom_fn.set_auto_close(4000);
						// location.reload();
					}
				}
			});

			return false;
		});

		//send sms form submit
		$('#assign-sms-form').submit(function(e){
			e.preventDefault();
			var current_flight_ids = $("table.manage_cc_items").DataTable().columns({ filter : 'applied'}).data()[2];

			if (!confirm('Are You Sure You Want To Send Delay Message To ' + current_flight_ids.length + ' Customers?')) { return; }

			data = $( this ).serializeArray(); 
			data.push({name: "items_val", value: JSON.stringify(current_flight_ids)});
			data.push({name: "new_date", value: $('#delay-new-date').val()});
			data.push({name: "new_time", value: $('#delay-new-time').val()});
			url1 = base_url+current_controller+"/prepare_sms_to_current_items"+default_ext;
			$.ajax({
				url: url1,
				data: $.param(data),
				type: 'POST',
				dataType: 'json',
				success: function(data){
					if(data.response > 0){
						custom_fn.show_status_msg('success', 'Success', 'Send Messages successfully !');
						custom_fn.set_auto_close(4000);
						// location.reload();
					}
				}
			});

			return false;
		});

		function enable_disable_inputs() {
			var status = $('#cc_status').val();
			if (status == 10) {
				$('.book-details').find('input, a, button').prop('disabled', false);
				$('.book-details').addClass('has-success');
			}else{
				$('.book-details').find('input, a, button').prop('disabled', true);
				$('.book-details').removeClass('has-success');
			}
		}
		$('#inboundClicked').click();
		enable_disable_inputs();
		//dynamic inputs according to selected status
		$('#cc_status').change(function(){
			enable_disable_inputs();
			return false;
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

		$('#cc_sms_type').change(function(){
			$(".sms_type[id^='sms_']").each(function( index ) {
			  $(this).css('display','none');
			});
			$('#sms_' + $(this).val() ).css('display','block');
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

	$("table.manage_cc_items").DataTable().search("").draw();
	if($("table.manage_cc_items").length > 0){
		var table = $("table.manage_cc_items").DataTable({
			"dom": "lfrtip",
			"iDisplayLength": 50,
			"bStateSave": true,
			"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
			"ordering": true,
			// "aoColumnDefs": [{"bSearchable": false, "aTargets": [0, 6, 7]}]
		});
		
		
		title = $('table.manage_cc_items tfoot th:nth-child(2)').text();
		$('table.manage_cc_items tfoot th:nth-child(2)').html( '<input type="text" placeholder="Search '+ title +'" />' );

		title = $('table.manage_cc_items tfoot th:nth-child(3)').text();
		$('table.manage_cc_items tfoot th:nth-child(3)').html( '<input type="text" placeholder="Search '+ title +'" />' );
		
		title = $('table.manage_cc_items tfoot th:nth-child(6)').text();
		$('table.manage_cc_items tfoot th:nth-child(6)').html( '<input type="text" placeholder="Search '+ title +'" />' );
		
		var title = $('table.manage_cc_items tfoot th:nth-child(7)').text();
		$('table.manage_cc_items tfoot th:nth-child(7)').html( '<input type="text" placeholder="Search '+ title +'" />' );

		title = $('table.manage_cc_items tfoot th:nth-child(8)').text();
		$('table.manage_cc_items tfoot th:nth-child(8)').html( '<input type="text" placeholder="Search '+ title +'" />' );
		

		table.on('search.dt', function() {
			//number of filtered rows
			filtered_data_count = table.rows( { filter : 'applied'} ).nodes().length;
			//filtered rows data as arrays
			filtered_data = table.rows( { filter : 'applied'} ).data();
		})

		// Apply the search
		table.columns().every( function () {
			var that = this;
			$( 'input', this.footer() ).on( 'keyup change', function () {
				if ( that.search() !== this.value ) {
					if (this.value != '') {
						$('#assignsmsModal').addClass('filtering');
					}else{
						$('#assignsmsModal').removeClass('filtering');
					}
					that.search( this.value ).draw();
				}
			} );
		});
	}





	/*Date Picker Init*/

	var dt = new Date();
	var adult_age = dt.getFullYear() - 12;
	dt.setFullYear(adult_age);
	var dateFormat = "yy/mm/dd";
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
			dateFormat: "yy/mm/dd",
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