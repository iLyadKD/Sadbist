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
											<form class="form form-horizontal update_airport_form" hyperlink="<?php echo $airport_id; ?>" action="javascript:void(0);" method="post">

												<div class="form-group">
													<label class="control-label col-sm-3 required">Airport Code (English)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="airport_code" placeholder="Airport Code" data-rule-pattern="^([0-9A-Za-z]{3})$" data-msg-pattern="Please enter 3 digit alphanumeric code" value="<?php echo $airport->airport_code; ?>" hyperlink="<?php echo $airport->airport_code; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Airport Name (English)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="airport_en" placeholder="Airport Name" value="<?php echo $airport->airport_en; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Airport Name (Farsi)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="airport_fa" placeholder="Airport Name" value="<?php echo $airport->airport_fa; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">City Code (English)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="city_code" placeholder="City Code" data-rule-pattern="^([0-9A-Za-z]{3})$" data-msg-pattern="Please enter 3 digit alphanumeric code" value="<?php echo $airport->city_code; ?>" hyperlink="<?php echo $airport->city_code; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">City Name (English)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="city_en" placeholder="City Name" value="<?php echo $airport->city_en; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">City Name (Farsi)</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-required="true" name="city_fa" placeholder="City Name" value="<?php echo $airport->city_fa; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Country</label>
													<div class="col-sm-5 controls">
														<select class="select2 form-control set_country" data-rule-required="true" data-msg-required="Select country" name="country" hyperlink="<?php echo $airport->country; ?>" id="country-list">
															<option value="">Select Country</option>
													 </select>
													 <input name="country_old" value="<?php echo $airport->country; ?>" hyperlink="<?php echo $airport->country; ?>" type="hidden">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3">Latitude</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-number="true" name="latitude" placeholder="Latitude" value="<?php echo $airport->latitude; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3">Longitude</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off" data-rule-number="true" name="longitude" placeholder="Longitude" value="<?php echo $airport->longitude; ?>" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3">Airport Link</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off"  value="<?php echo $airport->airport_link; ?>" data-rule-url="true" name="airport_link" placeholder="Airport Link" type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3">City Link</label>
													<div class="col-sm-5 controls">
														<input class="form-control" autocomplete="off"  value="<?php echo $airport->city_link; ?>" data-rule-url="true" name="city_link" placeholder="City Link" type="text">
													</div>
												</div>

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a href="<?php echo base_url($this->data['controller'].'/airports'.DEFAULT_EXT); ?>"><button class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Update Airport
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