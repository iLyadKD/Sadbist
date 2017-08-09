require(["custom_defination"], function(custom_fn)
{
	$(document).ready(function()
	{
		// Global variables
		var base_url = $("head").data("base-url");
		var default_ext = $("head").data("default-ext");
		var current_controller = $("head").data("controller");
		var current_method = $("head").data("method");

		if($("form.add_credit_form").length > 0)
			custom_fn.load_validate("add_credit_form");

		// Credit Module Functionalities

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
		// End of Credit Module Functionalities

	});
});
