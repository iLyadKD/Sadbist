require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		$(":file").filestyle();
		
		
		$(document).on("change", "input[type='file']", function()
		{
			if($(".multi_preview_img").length > 0)
				custom_fn.multi_preview_img(this, ".multi_preview_img", false);
			if($(this).attr("multiple") === true || $(this).attr("multiple") === "true" || $(this).attr("multiple") === "multiple")
				custom_fn.multi_preview_img(this, ".multi_preview_img", true);
		});

		$(document).on("click", ".remove_preview_file",function()
		{
			var parent = $(this).closest("div.preview_img_container");
			var selected_index = parent.data("selected_file");
			var input_attached = $(this).data("input_tag");
			
			/*if(input_attached !== undefined && input_attached !== "")
				$("#" + input_attached).filestyle("clear");*/
			parent.detach();
		});
	});
});
