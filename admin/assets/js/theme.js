/*
* --------------------------------------------------------------------------------------------------------------------
* main navigation toggling
* --------------------------------------------------------------------------------------------------------------------
*/

	nav_open = function()
	{
		return $("body").hasClass("main-nav-opened") || $(".main-nav-menu").width() > 50;
	};

	/*
	* --------------------------------------------------------------------------------------------------------------------
	* autosize feature for expanding textarea elements
	* --------------------------------------------------------------------------------------------------------------------
	*/

	setAutoSize = function(selector)
	{
		if (selector === null)
			selector = $(".autosize");
		if (jQuery().autosize)
			return selector.autosize();
	};

	/*
	* --------------------------------------------------------------------------------------------------------------------
	* timeago feature converts static time to dynamically refreshed
	* --------------------------------------------------------------------------------------------------------------------
	*/

	setTimeAgo = function(selector)
	{
		if (selector === null)
			selector = $(".timeago");
		if (jQuery().timeago)
		{
			jQuery.timeago.settings.allowFuture = true;
			jQuery.timeago.settings.refreshMillis = 60000;
			selector.timeago();
			return selector.addClass("in");
		}
	};

	/*
	* --------------------------------------------------------------------------------------------------------------------
	* scrollable boxes
	* --------------------------------------------------------------------------------------------------------------------
	*/

	setScrollable = function(selector)
	{
		if (selector === null)
			selector = $(".scrollable");
		if (jQuery().slimScroll)
		{
			return selector.each(function(i, elem)
			{
				return $(elem).slimScroll(
				{
					height: $(elem).data("scrollable-height"),
					start: $(elem).data("scrollable-start") || "top"
				});
			});
		}
	};

(function()
{

	$(document).ready(function()
	{
		var touch;

		var body, click_event, nav, nav_toggler;
		nav_toggler = $("header .toggle-nav");
		nav = $(".main-nav-menu");
		body = $("body");
		click_event = (jQuery.support.touch ? "tap" : "click");
		$(".main-nav-menu .dropdown-collapse").on(click_event, function(e)
		{
			var link, list;
			e.preventDefault();
			link = $(this);
			list = link.parent().find("> ul");
			if (list.is(":visible"))
			{
				if (body.hasClass("main-nav-closed") && link.parents("li").length === 1)
					false;
				else
				{
					link.removeClass("in");
					list.slideUp(300, function()
					{
						return $(this).removeClass("in");
					});
				}
			} 
			else
			{
				if (list.parents("ul.nav.nav-stacked").length === 1)
					$(document).trigger("nav-open");
				link.addClass("in");
				list.slideDown(300, function()
				{
					return $(this).addClass("in");
				});
			}
			return false;
		});
		if (jQuery.support.touch)
		{
			nav.on("swiperight", function(e)
			{
				return $(document).trigger("nav-open");
			});
			nav.on("swipeleft", function(e)
			{
				return $(document).trigger("nav-close");
			});
		}
		nav_toggler.on(click_event, function()
		{
			var status = "0";
			if (nav_open())
				status = "1";
			else
				status = "0";
			var base_url = $("head").attr("base-url") !== undefined ? $("head").attr("base-url") : $("head").data("base-url");
			var default_ext = $("head").attr("default-ext") !== undefined ? $("head").attr("default-ext") : $("head").data("default-ext")
			url = base_url+"ajax/set_menu_status"+default_ext+"?status="+status;
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
												if (status === "1")
													body.removeClass("main-nav-opened").addClass("main-nav-closed");
												else
													body.addClass("main-nav-opened").removeClass("main-nav-closed");
											}
											return false;
										}
		});
		/*
		* --------------------------------------------------------------------------------------------------------------------
		* removes .box after click on .box-remove button
		* --------------------------------------------------------------------------------------------------------------------
		*/

		$(".box .box-remove").on("click", function(e)
		{
			$(this).parents(".box").first().remove();
			e.preventDefault();
			return false;
		});
		/*
		* --------------------------------------------------------------------------------------------------------------------
		* collapse .box after click on .box-collapse button
		* --------------------------------------------------------------------------------------------------------------------
		*/

		$(".box .box-collapse").on("click", function(e) 
		{
			var box;
			box = $(this).parents(".box").first();
			box.toggleClass("box-collapsed");
			e.preventDefault();
			return false;
		});

		/*
		* --------------------------------------------------------------------------------------------------------------------
		* setting up responsive tabs
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (jQuery().tabdrop)
		{
			$(".nav-responsive.nav-pills, .nav-responsive.nav-tabs").tabdrop();
		}

		/*
		* --------------------------------------------------------------------------------------------------------------------
		* setting up basic wysiwyg
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (jQuery().wysihtml5)
		{
			$(".wysihtml5").wysihtml5();
		}
		/*
		* --------------------------------------------------------------------------------------------------------------------
		* setting up sortable list
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (jQuery().nestable)
		{
			$(".dd-nestable").nestable();
		}
		/*
		* --------------------------------------------------------------------------------------------------------------------
		* affixing main navigation
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (!$("body").hasClass("fixed-header"))
		{
			if (jQuery().affix)
			{
				$(".main-nav-menu.main-nav-fixed").affix(
				{
					offset: 40
				});
			}
		}

		/*
		* --------------------------------------------------------------------------------------------------------------------
		* setting up bootstrap tooltips and popovers
		* --------------------------------------------------------------------------------------------------------------------
		*/

		touch = false;
		if (window.Modernizr)
			touch = Modernizr.touch;
		if (!touch)
		{
			$("body").on("mouseenter", ".has-tooltip", function()
			{
				var el;
				el = $(this);
				if (el.data("tooltip") === undefined)
				{
					el.tooltip(
					{
						placement: el.data("placement") || "top",
						container: "body"
					});
				}
				return el.tooltip("show");
			});
			$("body").on("mouseleave", ".has-tooltip", function()
			{
				return $(this).tooltip("hide");
			});


			$("body").on("mouseenter", ".has-popover", function()
			{
				var el;
				el = $(this);
				if (el.data("popover") === undefined)
				{
					el.popover(
					{
						placement: el.data("placement") || "top",
						container: "body"
					});
				}
				return el.popover("show");
			});
			$("body").on("mouseleave", ".has-popover", function()
			{
				return $(this).popover("hide");
			});
		}
		/*
		* --------------------------------------------------------------------------------------------------------------------
		* replacing svg images for png fallback
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (window.Modernizr && Modernizr.svg === false)
		{
			$("img[src*=\"svg\"]").attr("src", function()
			{
				return $(this).attr("src").replace(".svg", ".png");
			
			});
		}

		/*
		* --------------------------------------------------------------------------------------------------------------------
		* setting bootstrap file input
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (jQuery().bootstrapFileInput)
			$("input[type=file]").bootstrapFileInput();
		/*
		* --------------------------------------------------------------------------------------------------------------------
		* modernizr fallbacks
		* --------------------------------------------------------------------------------------------------------------------
		*/

		if (window.Modernizr)
		{
			if (!Modernizr.input.placeholder)
			{
				$("[placeholder]").focus(function()
				{
					var input;
					input = $(this);
					if (input.val() === input.attr("placeholder"))
					{
						input.val("");
						return input.removeClass("placeholder");
					}
				}).blur(function()
				{
					var input;
					input = $(this);
					if (input.val() === "" || input.val() === input.attr("placeholder"))
					{
						input.addClass("placeholder");
						return input.val(input.attr("placeholder"));
					}
				}).blur();
				return $("[placeholder]").parents("form").submit(function()
				{
					return $(this).find("[placeholder]").each(function()
					{
						var input;
						input = $(this);
						if (input.val() === input.attr("placeholder"))
							return input.val("");
					});
				});
			}
		}

		setTimeAgo();
		setScrollable();
		setAutoSize();
	
	});

}).call(this);
