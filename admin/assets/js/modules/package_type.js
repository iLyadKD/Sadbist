require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.package_type").length > 0) {
			custom_fn.load_validate("package_type");
		}
		
		if($("table.datatable").length > 0){
			$(document).ready(function() {
				$('table.datatable thead th').each( function () {
					var title = $(this).text();
					if(title !== "Action")
					$(this).html( '<input type="text" class="form-control" placeholder="'+title+'" />' );
					else {
					$(this).html("");
					}
				});
				var table = $('table.datatable').DataTable( {"order": [[ 0, "asc" ]] } );

				// Apply the search
				table.columns().every( function () {
				var that = this;
					$( 'input', this.header() ).on( 'keyup change', function () {
						if ( that.search() !== this.value ) {
						that
						.search( this.value )
						.draw();
						}
					});
				});
			});

		}
			

	});
});
