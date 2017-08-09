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
											<i class="icon-plus-sign"></i>
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
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.explode("_", $this->data['method'])[0].DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
											<form class="form form-horizontal add_contact_detail_form" action="javascript:void(0);" method="post">
												
												<div class="form-group">
													<label class="col-md-3 control-label required">Location Name</label>
														<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" name="location_en" placeholder="Contact Location (English)" type="text" data-msg-required="Please enter Contact Location" data-rule-pattern="^([a-zA-Z]([a-zA-Z0-9][ ,]?)*)$" data-msg-pattern="Please enter valid Contact Location">
													</div>
														<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" name="location_fa" placeholder="Contact Location (Farsi)" type="text" data-msg-required="Please enter Contact Location">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Address</label>
													<div class="col-sm-4 controls">
														<textarea tabindex="2" class="form-control" data-rule-required="true" name="address_en" placeholder="Contact Address (English)" data-msg-required="Please enter contact address"></textarea>
													</div>
													<div class="col-sm-4 controls">
														<textarea tabindex="2" class="form-control" data-rule-required="true" name="address_fa" placeholder="Contact Address (Farsi)" data-msg-required="Please enter contact address"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Contact Number</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" data-rule-required="true" name="contact" placeholder="Contact Number" type="text" data-msg-required="Please enter contact number" data-rule-pattern="^(\+?[0-9\-]{10,20}(, ?\+?[0-9\-]{10,20}){0,2})$" data-msg-pattern="Please enter valid Contact number">
														<p class="help-block">
															<small class="text-muted">Use Comma(,) to separate multiple contact numbers.</small><br>
															<small class="text-muted">You can mention maximum of three numbers</small>
														</p>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Email ID</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="4" class="form-control" data-rule-required="true" name="email" placeholder="Contact EmailID" type="text" data-msg-required="Please enter contact email" data-rule-email="true" data-msg-email="Please enter valid emailID">
													</div>
												</div>


												<div class="form-group">
													<label class="control-label col-sm-3 required">Website</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="5" class="form-control" data-rule-required="true" name="website" placeholder="Website URL" type="text" data-msg-required="Please enter website url" data-rule-url="true" data-msg-email="Please enter valid website url">
													</div>
												</div>

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.explode("_", $this->data['method'])[0].DEFAULT_EXT); ?>"><button tabindex="6" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="6" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Add Contact
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
					</div>
					<?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>