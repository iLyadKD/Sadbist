<!DOCTYPE html>
<html class="no-js">
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$this->load->view("common/header", $include);
	?>
	<body class="contrast-red error contrast-background">
		<div class="middle-container">
			<div class="middle-row">
				<div class="middle-wrapper">
					<div class="error-container-header">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<div class="text-center">
										<i class="icon-question-sign"></i>
										404
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="error-container">
						<div class="container">
							<div class="row">
								<div class="col-sm-4 col-sm-offset-4">
									<h4 class="text-center title">Ooops! We can&Prime;t find what you"re looking for.</h4>
									<div class="text-center">
										<a class="btn btn-md btn-ablock" href="javascript:void(0);" onclick="window.history.go(-1); return false;">
											<i class="icon-chevron-left"></i>
											Go back
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="error-container-footer">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<div class="text-center">
										<img width="121" height="31" alt="logo" src="<?php echo asset_url('images/logo_xs.png'); ?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>