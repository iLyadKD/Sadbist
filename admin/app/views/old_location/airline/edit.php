<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
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
											<form class="form form-horizontal update_airline_form" hyperlink="<?php echo $airline_id; ?>" action="javascript:void(0);" method="post">

												<div class="form-group">
													<label class="control-label col-sm-3 required">Airline Code</label>
													<div class="col-sm-4 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="airline_code" placeholder="Airline Code" data-rule-pattern="^([0-9A-Za-z]{2})$" data-msg-pattern="Please enter 2 digit alphanumeric code" value="<?php echo $airline->airline_code; ?>" hyperlink="<?php echo $airline->airline_code; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Airline Name (English)</label>
													<div class="col-sm-4 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="airline_en" placeholder="Airline Name (English)" value="<?php echo $airline->airline_en; ?>" type="text">
													</div>
													<div class="col-sm-4 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="airline_fa" placeholder="Airline Name (Farsi)" value="<?php echo $airline->airline_fa; ?>" type="text">
													</div>
												</div>

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a href="<?php echo base_url($this->data['controller'].'/airlines'.DEFAULT_EXT); ?>"><button class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Update Airline
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