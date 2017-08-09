<!DOCTYPE html>
<html>
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
							<div class="row">
								<div class="col-sm-12">
									<div class="page-header">
										<h1 class="pull-left">
											<i class="icon-home"></i>
											<span><?php echo $this->data["page_title"]; ?></span>
										</h1>
										<div class="pull-right">
											<ul class="breadcrumb">
												<li>
													<a tabindex="-1" href="<?php echo base_url(); ?>">
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
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
										</div>
										<div class="box-content">
											<div class="col-md-8 cl_map_box">
													<div class="height300 contact_locations_map">
													
													</div>
													<div class="map_form">
														<a a href="javascript:void(0);" class="add_contact_location"> <i title="Add new Location" class="icon-3x icon-spin icon-crosshairs"></i></a>
													</div>
												</div>
											<div class="col-md-4">
												<div class="scrollable-area">
													<table class="data-table-column-filter table table-bordered table-striped manage_contact_markers">
														<thead>
															<tr role="row" class="heading">
																<th>Sl No.</th>
																<th>Title</th>
																<th>Status</th>
																<th>Actions</th>
															</tr>
														</thead>
														<tbody><tr class="odd text-center"><td valign="top" colspan="4" class="dataTables_empty"><i class="bar_loading"></i></td></tr></tbody>
													</table>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12">
											&nbsp;
											</div>
											<div class="col-md-12">
												<div class="scrollable-area">
													<table class="data-table-column-filter table table-bordered table-striped manage_contact_details">
														<thead>
																<tr role="row" class="heading">
																	<th>Sl No.</th>
																	<th>Location</th>
																	<th>Address</th>
																	<th>Contact Number</th>
																	<th>Email ID</th>
																	<th>Website</th>
																	<th>Status</th>
																	<th>Actions</th>
																</tr>
															</thead>
															<tbody><tr class="odd text-center"><td valign="top" colspan="8" class="dataTables_empty">Loading..</td></tr></tbody>
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
		<?php
			if(isset($contact_locations) && is_array($contact_locations))
			{
				$cl_markers = [];
				foreach ($contact_locations as $location)
				{
					$display_content = "<div class='contact_locations_content'><div class='contact_locations_header'>".$location["title_en"]."</div><div class='contact_locations_body'>".$location["image"]."</div></div>";
					$cl_markers[]= "'".$location["id"]."', '".$location["latitude"]."', '".$location["longitude"]."', '".$location["marker_image"]."', '".$display_content."', '".($location["marker_image"] === "" || is_null($location["marker_image"]) ? "false" : "true")."', '".$location["status"]."'";
				}
				$cl_markers = implode(":::", $cl_markers);
				echo '<div class="contact_locations_list" data-href="'.$cl_markers.'">';
			}
		?>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>