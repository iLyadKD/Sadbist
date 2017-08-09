require(["bootstrap_switch"], function()
{
	$(document).ready(function()
	{
		$(".toggle_switch").bootstrapSwitch(
		{
			onSwitchChange:function(event, state)
			{
			}
		});
	});
});
