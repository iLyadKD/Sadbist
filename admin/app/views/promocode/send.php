<!DOCTYPE html>
<html class="no-js">
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "bootstrap/bootstrap-tagsinput";
		$include["css"][] = "bootstrap/bootstrap-switch";
		$include["css"][] = "light-theme";
		$include["css"][] = "jquery-ui/jquery-ui";
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
												<li>
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><?php echo $this->data["page_main_title"]; ?></a>
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
							<div class="row notification"></div>
							<div class="row">
								<div class="col-sm-12 box">
									<div class="box-header blue-background">
										<div class="title"><?php echo $this->data["page_title"]; ?></div>
									</div>
									<div class="box-content">
										<form class="form form-horizontal send_promocode_form" hyperlink="<?php echo $promocode_id ?>" action="javascript:void(0);" method="post">
											
											<div class="form-group">
												<label class="control-label col-sm-3" for="promo_code">Email Ids: </label>
												<div class="col-sm-5 controls">
													<input autocomplete="off" tabindex="1" autofocus="true" class="form-control input_tags valid" name="additional_emails" rows="4" data-rule-pattern="^(([a-z][a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4})(, ?([a-z][a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4})){0,19})$" data-msg-pattern="Please enter upto 20 valid list of email(s).">
												</div>
												<p class="help-block">
													<small class="text-muted">Use Comma(,) to separate multiple email ids.</small><br/>
													<small class="text-muted">You can specify maximum of 20 email ids.</small>
												</p>
											</div>

											<div class="form-group">
												<label class="control-label col-sm-3" for="promo_code">Select User Category: </label>
												<div class="col-sm-8 controls">
													<div class="col-sm-2">
														<label>B2B Users: </label>
													</div>
													<div class="col-sm-2">
															<input tabindex="2" type="checkbox" data-on-text="Yes" data-off-text="No" name="b2b" value="3" class="toggle_switch hide">
													</div>
													<div class="col-sm-2">
														<label>B2C Users: </label>
													</div>
													<div class="col-sm-2">
															<input tabindex="2" type="checkbox" data-on-text="Yes" data-off-text="No" name="b2c" value="4" class="toggle_switch hide">
													</div>
													<div class="col-sm-2">
														<label>Subscribers: </label>
													</div>
													<div class="col-sm-2">
															<input tabindex="2" type="checkbox" value="5" class="toggle_switch hide" data-on-text="Yes" data-off-text="No" name="subscribers">
													</div>
													<div class="col-sm-10">
														<p class="help-block">
															<small class="text-muted">* You can not select particular user in this categories. but can be mentioned in the above.</small><br/>
															<small class="text-muted">* If mentioned email is repeated, Promocode will be sent only once.</small>
														</p>
													</div>
												</div>
											</div>
											
											<div class="form-actions">
												<div class="row">
													<div class="col-sm-9 col-sm-offset-3">
														<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button tabindex="3" class="btn btn-primary" type="button">
															<i class="icon-reply"></i>
															Go Back
														</button></a>
														<button tabindex="4" class="btn btn-primary" type="submit">
															<i class="icon-mail-forward"></i>
															Send
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