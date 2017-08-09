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
													<a href="<?php echo base_url(); ?>">
														<i class="icon-bar-chart"></i>
													</a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li>
												 <a href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
											<form class="form form-horizontal update_country_form" style="margin-bottom: 0;" action="javascript:void(0);" hyperlink="<?php echo $country_id; ?>" method="post">
												<div class="form-group">
													<label class="control-label col-sm-3 required">Country Name (English)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" hyperlink="<?php echo $country->country_en; ?>" value="<?php echo $country->country_en; ?>" name="country_en" placeholder="Country Name (English)" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Country Name (Farsi)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" hyperlink="<?php echo $country->country_fa; ?>" value="<?php echo $country->country_fa; ?>" name="country_fa" placeholder="Country Name (Farsi)" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Country Code(ISO 2)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" hyperlink="<?php echo $country->id; ?>" value="<?php echo $country->id; ?>" name="country_id2" placeholder="Country ID" type="text" data-rule-pattern="^([a-zA-Z][a-zA-Z])$" data-msg-pattern="Please enter two letter ID">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Country Code(ISO 3)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" hyperlink="<?php echo $country->iso_3; ?>" value="<?php echo $country->iso_3; ?>" name="country_id3" placeholder="Country ID" type="text" data-rule-pattern="^([a-zA-Z][a-zA-Z][a-zA-Z])$" data-msg-pattern="Please enter three letter ID">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Country Code(ISO Number)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" hyperlink="<?php echo $country->iso_number; ?>" value="<?php echo $country->iso_number; ?>" name="country_id_num" placeholder="Country ID" type="text" data-rule-pattern="^([0-9]{1,3})$" data-msg-pattern="Please enter numeric ID">
													</div>
												</div>

											<div class="form-actions" style="margin-bottom:0">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Update Country
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