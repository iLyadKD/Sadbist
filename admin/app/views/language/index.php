<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "datatables/jquery.dataTables.min";
		$include["css"][] = "datatables/dataTables.tableTools.min";
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
											<i class="icon-th"></i>
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
									<div class="box"><div class="notification"><?php $this->load->view("common/notification"); ?></div></div>
									<div class="box bordered-box orange-border">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
											<div class="actions">
												<a tabindex="1" class="btn add_lang_page" href="javascript:void(0);"><i class="icon-plus-sign"></i> Add New Page</a>
											</div>
										</div>
										<div class="box-content box-no-padding">
											<div class="responsive-table">
												<div class="scrollable-area">
													<table class="data-table-column-filter table table-bordered table-striped manage_lang_pages">
														<thead>
															<tr>
																<th>Sl No.</th>
																<th>Page</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody><tr class="odd text-center"><td valign="top" colspan="3" class="dataTables_empty"><i class="bar_loading"></i></td></tr></tbody>
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