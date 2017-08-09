require(["bootstrap"], function()
{
	$(document).ready(function()
	{
		// $(".timepicker").datetimepicker(
		// {
		// 	pickDate: false,
		// 	pickSeconds: false 
		// });

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


		//Display datepicker when date icon is clicked
		$(document).on("click", "input + span.input-group-addon", function()
		{
			if($(this).prev().hasClass("today_onwards_limited") || $(this).prev().hasClass("before_date_recent_limited"))
			{
				var visible = $(this).prev().datepicker("widget").is(":visible");
				$(this).prev().datepicker(visible ? "hide" : "show");
			}
		});

	});
});
