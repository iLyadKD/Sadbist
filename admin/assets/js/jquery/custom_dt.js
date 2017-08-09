$.datepicker.regional["fa"] =
{
	closeText: "بستن",
	prevText: "&#x3c;قبلي",
	nextText: "بعدي&#x3e;",
	currentText: "امروز",
	monthNames: ["فروردين","ارديبهشت","خرداد","تير","مرداد","شهريور","مهر","آبان","آذر","دي","بهمن","اسفند"],
	monthNamesShort: ["١","٢","٣","٤","٥","٦","٧","٨","٩","١٠","١١","١٢"],
	dayNames: ["يکشنبه","دوشنبه","سه‌شنبه","چهارشنبه","پنجشنبه","جمعه","شنبه"],
	dayNamesShort: ["ي","د","س","چ","پ","ج", "ش"],
	dayNamesMin: ["ي","د","س","چ","پ","ج", "ش"],
	showAnim: "slide",
	numberOfMonths: 1,
	weekHeader: "هف",
	dateFormat: "dd-mm-yy",
	firstDay: 6,
	isRTL: true,
	showMonthAfterYear: false,
	// prevJumpText: "&#x3c;&#x3c;",
	// prevJumpStatus: "",
	// nextJumpText: "&#x3e;&#x3e;",
	// nextJumpStatus: "",
	// todayText: "امروز",
	// todayStatus: "نمايش ماه جاري",
	// clearText: "حذف تاريخ",
	// clearStatus: "پاک کردن تاريخ جاري",
	// currentStatus: "نمايش ماه جاري",
	// nextStatus: "نمايش ماه بعد",
	// prevStatus: "نمايش ماه قبل",
	// closeStatus: "بستن بدون اعمال تغييرات",
	// yearStatus: "نمايش سال متفاوت",
	// monthStatus: "نمايش ماه متفاوت",
	// weekText: "هف",
	// weekStatus: "هفتهِ سال",
	// dayStatus: "انتخاب D, M d",
	// defaultStatus: "انتخاب تاريخ",
	// altField: "#alternate",
	// altFormat: "DD, d MM, yy",
	// showWeek: true,
	// showButtonPanel: true,
	// showOtherMonths: true,
	// selectOtherMonths: true,
	//numberOfMonths: 2,
	// beforeShowDay: function(input)
	// {

	// },
	// onSelect: function(input)
	// {

	// },
	beforeShow: function(input)
	{
		setTimeout(function()
		{
			var btn_tbody = $(input)
			.datepicker("widget")
			.find(".ui-datepicker-calendar tbody");
			digits = {"0" :"٠", "1" : "١", "2" : "٢", "3" : "٣", "4" : "٤", "5" : "٥", "6" : "٦", "7" : "٧", "8" : "٨", "9" : "٩"};
			// change to other language
			btn_tbody.find("a.ui-state-default, span.ui-state-default").each(function()
			{
				var temp_clone =$(this).clone();
				$(this).addClass("hide");
				temp_clone.removeClass("ui-state-default").insertAfter($(this));
				var val = $(temp_clone).html().multi_replace(digits);
				$(temp_clone).html(val);
			});
			// change year to other language
			$(input).datepicker("widget").find("select.ui-datepicker-year option, span.ui-datepicker-year").each(function()
			{
				$(this).html($(this).html().multi_replace(digits));
			});
			// var btn_panel = $(input)
			// .datepicker("widget")
			// .find(".ui-datepicker-buttonpane");
			// var btn = $('<button type="button" class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all">Change</button>');
			// btn.unbind("click").bind("click", function ()
			// {
			// 	//
			// });
			// btn_panel.find("button").detach();
			// btn.appendTo(btn_panel);
		}, 1);
	},
	onChangeMonthYear: function(input)
	{
		setTimeout(function()
		{
			var btn_tbody = $(input)
			.datepicker("widget")
			.find(".ui-datepicker-calendar tbody");
			digits = {"0" :"٠", "1" : "١", "2" : "٢", "3" : "٣", "4" : "٤", "5" : "٥", "6" : "٦", "7" : "٧", "8" : "٨", "9" : "٩"};
			// change dates to other language
			btn_tbody.find("a.ui-state-default, span.ui-state-default").each(function()
			{
				var temp_clone =$(this).clone();
				$(this).addClass("hide");
				temp_clone.removeClass("ui-state-default").insertAfter($(this));
				var val = $(temp_clone).html().multi_replace(digits);
				$(temp_clone).html(val);
			});
			// change year to other language
			$(input).datepicker("widget").find("select.ui-datepicker-year option, span.ui-datepicker-year").each(function()
			{
				$(this).html($(this).html().multi_replace(digits));
			});
		}, 1);
	},
	yearSuffix: ""
};

$.datepicker.regional["en"] =
{
	closeText: "Done",
	prevText: "Prev",
	nextText: "Next",
	currentText: "Today",
	monthNames: ["January","February","March","April","May","June","July","August","September","October","November","December"],
	monthNamesShort: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
	dayNames: ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
	dayNamesShort: ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
	dayNamesMin: ["Su","Mo","Tu","We","Th","Fr","Sa"],
	showAnim: "slide",
	numberOfMonths: 1,
	weekHeader: "Wk",
	dateFormat: "dd-mm-yy",
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ""
};