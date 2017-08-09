<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "datatables/jquery.dataTables.min";
		$include["css"][] = "datatables/dataTables.tableTools.min";
		$this->load->view("common/header", $include);
	?>

	<body class="contrast-muted fixed-header">
		<?php $this->load->view("header");?>
		<div id="wrapper">
			<div id="main-nav-bg"></div>
			<?php $this->load->view("side-menu");?>

			<section id="content">
				<div class="container">
					<div class="row" id="content-wrapper">
						<div class="col-xs-12">
							<div class="row">
								<div class="col-sm-12">
									<div class="page-header">
										<h1 class="pull-left">
											<i class="icon-wrench"></i>
											<span><?php echo $this->data["page_title"]; ?></span>
										</h1>
										<div class="pull-right">
											<ul class="breadcrumb">
												<li>
													<a tabindex="-1" href="<?=base_url();?>">
														<i class="icon-bar-chart"></i>
													</a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li class="active"><?php echo $this->data["page_title"]; ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="box"><div class="notification"></div></div>
									<div class="box bordered-box orange-border" style="margin-bottom:0;">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
										</div>
										<div class="box-content box-no-padding">
											<div class="responsive-table">
												<div class="scrollable-area">
													<table class="data-table-column-filter table table-bordered table-striped manage_b2b_flight_bookings">
														<thead>
															<tr>
																<th>Sl No.</th>
																<th>Tax Type</th>
																<th>Tax Rate</th>
																<th width="100">Actions</th>
																<th></th>
															</tr>
														</thead>
														<tfoot>
															<tr>
																<th>Sl No.</th>
																<th>Tax Type</th>
																<th>Tax Rate</th>
																<th width="100">Actions</th>
																<th></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>