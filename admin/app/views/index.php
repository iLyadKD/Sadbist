<!DOCTYPE html>
<html class="no-js">
	<?php

	$include["css"][] = "bootstrap/bootstrap";
	$include["css"][] = "light-theme";
	$include["css"][] = "pattern-lock";
	$this->load->view("common/header", $include);

	?>
	<body class="login contrast-background">
		<div class="middle-container">
			<div class="middle-row">
				<div class="middle-wrapper">
					<div class="login-container-header">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<div class="text-center">
										<img src="<?php echo asset_url(IMAGE_PATH.'logo.png')?>" />
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="login-container">
						<div class="container">
							<?php $this->load->view('common/notification'); ?>

							<div class="row login_div">
								<div class="col-sm-4 col-sm-offset-4">
									<h1 class="text-center title">Sign in</h1>
									<form action="<?php echo base_url("login".DEFAULT_EXT); ?>" class="login_admin" method="post">
										<div class="form-group">
											<div class="controls with-icon-over-input">
												<input placeholder="E-mail" class="form-control" data-rule-email="true" data-rule-required="true" name="email" type="text" />
												<i class="icon-user text-muted"></i>
											</div>
										</div>
										<div class="form-group">
											<div class="controls with-icon-over-input">
												<input placeholder="Password" class="form-control" data-rule-password="true" data-rule-required="true" name="password" type="password" />
												<i class="icon-lock text-muted"></i>
											</div>
										</div><!-- 
										<div class="form-group">
											<div class="login_pattern"></div>
											<input type="hidden" name="login_pattern" data-rule-required="true" data-rule-pattern="/[0-9]{1,9}/" value="">
										</div> -->
										<button class="btn btn-primary btn-block">Sign in</button>
									</form>
									
									<div class="text-center">
										<hr class="hr-normal">
										<a href="javascript:void(0);" class="forgot_password">Forgot your password?</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="login-container-footer">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<?php $this->load->view("common/scripts");?>
</html>