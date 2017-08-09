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
											<i class="icon-ok"></i>
											<span><?php echo $this->data["page_title"]; ?></span>
										</h1>
										<div class="pull-right">
											<ul class="breadcrumb">
												<li>
													<a tabindex="-1" href="<?php echo base_url();?>">
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
											<form class="form form-horizontal update_email_setting_form" hyperlink="<?php echo $email_access->id; ?>" action="javascript:void(0);" method="post"> 
												<div class="form-group">
													<label class="control-label col-sm-3 required"  for="smtp">Protocol</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off"  tabindex="1" autofocus="true" autofocus="true" class="form-control" id="smtp" data-rule-required="true" name="smtp" placeholder="Protocol" type="text" value="<?php echo $email_access->smtp; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required"  for="host">Host Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="2" class="form-control" id="host" data-rule-required="true" name="host" placeholder="Host Name" type="text" value="<?php echo $email_access->host; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required"  for="port">Port</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" id="port"  data-rule-number="true" data-rule-required="true" name="port" placeholder="Port" type="text" value="<?php echo $email_access->port; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required"  for="username">Username</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="4" class="form-control" id="username" data-rule-required="true" data-rule-email="true" name="username" placeholder="Username" type="text" value="<?php echo $email_access->username; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="password">Password</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="5" class="form-control" data-rule-required="true" name="password" placeholder="Password" type="password" value="<?php echo $email_access->password; ?>">
													</div>
												</div>
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button tabindex="6" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="7" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
																Update
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