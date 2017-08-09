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
												<li>
													<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><?php echo $this->data["page_main_title"]; ?>
													</a>
												</li>
												<li class="active"><?php echo $this->data["page_title"]; ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="box"></div><div class="notification"></div></div>
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
										</div>

										<div class="box-content">
											<form class="form form-horizontal add_privilege_form" action="javascript:void(0);" method="post">
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Controller</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" data-msg-required="Please enter controller name" data-rule-pattern="^([a-zA-Z]([a-zA-Z0-9]_?)*)$" data-msg-pattern="Please enter valid controller" name="controller_name" type="text" placeholder="Controller name">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Icon Class</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="2" class="form-control prev_privilege_icon" data-rule-required="true" data-msg-required="Please enter icon class" data-rule-pattern="^([a-zA-Z]([a-zA-Z]-?)*)$" data-msg-pattern="Please enter valid icon class" name="icon_name" type="text" placeholder="Icon Class">
													</div>
													<label class="control-label col-sm-1" for="label"><i class=""></i></label>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Privilege Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" data-rule-required="true" data-msg-required="Please enter privilege name" data-rule-pattern="^([a-zA-Z]([a-zA-Z] ?)*)$" data-msg-pattern="Please enter valid name" name="privilege_name" type="text" placeholder="Privilege name">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Relative URL</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="4" class="form-control" data-rule-required="true" data-msg-required="Please enter url" data-rule-pattern="^([a-zA-Z]([_/]?[a-zA-Z0-9])*)$" data-msg-pattern="Please enter valid url" name="rel_url" type="text" placeholder="controller/method">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Order By</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="5" class="form-control" data-rule-required="true" data-msg-required="Please enter menu order" data-rule-min="2" data-msg-min="Please enter valid number great than 1" data-msg-number="Please enter valid number" name="menu_order" type="text" placeholder="Ex : 2">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Privilege Type</label>
													<div class="col-sm-4 controls">
														<label class="radio-inline"><input autocomplete="off" tabindex="6" class="privilege_menu_types" data-rule-required="true" data-msg-required="Please select privilege type" name="privilege_type" checked="checked" type="radio" value="0"> Main Privilege</label>
														<label class="radio-inline"><input autocomplete="off" tabindex="6" class="privilege_menu_types" autocomplete="off" data-rule-required="true" data-msg-required="Please select privilege type" name="privilege_type" type="radio" value="1"> Sub-Privilege</label>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3" for="label">Parent Order</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="7" class="form-control submenu_order_by" data-rule-required="true" data-msg-required="Please enter parent level" data-rule-min="0" data-msg-min="Please enter valid positive number" data-rule-number="true" data-msg-number="Please enter valid number" name="parent_order" type="text" placeholder="Ex : 0" disabled="disabled">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3" for="label">Sub-Menu level</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="8" class="form-control submenu_order_by" data-rule-required="true" data-msg-required="Please enter submenu level" data-rule-min="1" data-msg-min="Please enter valid positive number great than 0" data-rule-number="true" data-msg-number="Please enter valid number" name="level" type="text" placeholder="Ex : 1" disabled="disabled">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3" for="label">Sub-Menu Order</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="9" class="form-control submenu_order_by" data-rule-required="true" data-msg-required="Please enter current order" data-rule-min="1" data-msg-min="Please enter valid positive number great than 0" data-rule-number="true" data-msg-number="Please enter valid number" name="submenu_order" type="text" placeholder="Ex : 1" disabled="disabled">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label text-danger  col-sm-6">Note : While adding submenu, Menu order should be exists.</label>
												</div>

												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button tabindex="10" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="11" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
																Add Privilege
															</button>
														</div>
													</div>
												</div>
											</form>

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