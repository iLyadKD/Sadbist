<!DOCTYPE html>
<html class="no-js">
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
											<i class="icon-barcode"></i>
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
									<div class="row todo-list">
										<div class="col-sm-12">
											<div class="box"><div class="notification"></div></div>
											<div class="box bordered-box orange-border">
												<div class="box-header blue-background">
													<div class="title"><?php echo $this->data["page_title"]; ?></div>
												</div>
												<div class="box-content box-no-padding">
													<div class="responsive-table">
														<div class="sortable-container">
															<form class="new-todo add_sp_type_form" method="post" action="javascript:void(0);" accept-charset="UTF-8">
																<input autocomplete="off" tabindex="1" class="form-control" id="todo_name" data-rule-required="true" name="page_type" data-msg-required="Please enter Static page type." placeholder="Type your new page type here...Ex : Quick Links / Legal / About Us" type="text">
																<button tabindex="2" class="btn btn-success" type="submit">
																	<i class="icon-plus"></i>
																</button>
															</form>
														</div>
													</div>
												</div>
												<div class="box-content box-no-padding">
													<div class="responsive-table">
														<ul class="list-unstyled manage_sp_types" data-sortable-axis="y" data-sortable-connect=".sortable">
															
														</ul>
													</div>
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