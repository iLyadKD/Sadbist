<!DOCTYPE html>
<html class="no-js">
	<?php

	$include["css"][] = "bootstrap/bootstrap";
	$include["css"][] = "light-theme";
	$this->load->view("common/header", $include);

	?>

	<body class="fixed-header <?php echo get_menu_status() === '1' ? 'main-nav-closed' : 'main-nav-opened'; ?>">
		<?php $this->load->view("header");?>
		<div class="body-wrapper">
			<div class="main-nav-bg"></div>
			<?php $this->load->view("side-menu");?>
			<section class="body-content">
				<div class="container">
					<div class="row" class="body-content-wrapper">
						<div class="col-xs-12">
							<div class="page-header page-header-with-buttons">
								<h1 class="pull-left"> <i class="icon-dashboard"></i> <span>Dashboard</span> </h1>
							</div>
							<?php $this->load->view("top-menu"); ?>
							<?php if($this->data["admin_type"] === SUPER_ADMIN_USER) { ?>
							<!--<div class="chartdiv" style="width: 50%; float: left">
								<div class="box_2">
									<div class="box-header">
										<h5>Booking Engine</h5>
									</div>
									<div class="box-content">
										<div id="chartContainer" style="height: 260px; width: 100%;"></div>
									</div>
								</div>
							</div>
							<div class="chartdiv">
								<div class="box_2">
									<div class="box-header">
										<h5>User Registration Status</h5>
									</div>
									<div class="box-content">
										<div id="chartContainer1" style="height: 260px; width: 100%;"></div>
									</div>
								</div>
							</div>-->
							<?php } ?>
						</div>
					</div>
					<?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
	</body>
	<?php
	$this->load->view("common/scripts");
	if($this->data["admin_type"] === SUPER_ADMIN_USER) { ?>
	<script src="<?php echo asset_url(JS_PATH.'jquery/google_script_1.js');?>" type="text/javascript"></script> 

<!--	<script type="text/javascript">
	window.onload = function () {
		var chart1 = new CanvasJS.Chart("chartContainer",
		{
			title:{
				verticalAlign: 'top',
				horizontalAlign: 'left'
			},
									animationEnabled: true,
			data: [
			{        
				type: "doughnut",
				startAngle:20,
				toolTipContent: "{label}: {y} - <strong>#percent%</strong>",
				indexLabel: "{label} #percent%",
				dataPoints: [
					{  y: 30, label: "Confirmed" },
					{  y: 30, label: "Cancelled" },
					{  y: 20, label: "Failed" },
					{  y: 20,  label: "Pending"}
				]
			}
			]
		});
		chart1.render();
		
		var chart = new CanvasJS.Chart("chartContainer1",
		{
			animationEnabled: true,
			title:{
				text: ""
			},
			data: [
			{
				type: "column", //change type to bar, line, area, pie, etc
				dataPoints: [
					{ label: "B2B Agents", y: 60 },
					{ label: "B2B Users", y: 55 }
				]
			}
			]
			});

		chart.render();
	}
	</script>-->
	<?php } ?>
</html>