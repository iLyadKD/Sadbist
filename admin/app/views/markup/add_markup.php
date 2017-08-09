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
													<a tabindex="-1" href="<?php echo base_url(); ?>">
														<i class="icon-bar-chart"></i>
													</a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li>
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
											<form class="form form-horizontal add_markup_form" action="javascript:void(0);" method="post">
												
												<div class="form-group">
													<label class="control-label col-sm-3 required">Markup Type</label>
													<div class="col-sm-5 controls">
														<select tabindex="1" autofocus="true" class="select2 form-control set_markup_types" data-rule-required="true" data-msg-required="Please Select Markup Type" name="mu_type" id="mu_type">
														</select>
													</div>
												</div>

												<div class="form-group optional_values b2b hide">
													<label class="control-label col-sm-3 required">B2B User</label>
													<div class="col-sm-5 controls">
														<select tabindex="2" class="select2 form-control set_agents" data-rule-required="true" data-msg-required="Please Select Agent" name="mu_agent" id="mu_agent">
														</select>
													</div>
												</div>

												<div class="form-group optional_values common hide">
													<label class="control-label col-sm-3 required">Select Country</label>
													<div class="col-sm-5 controls">
														<select tabindex="3" class="select2 form-control set_country valid" data-rule-required="false" name="country" id="country">
														</select>
													</div>
												</div>

												<div class="form-group optional_values common hide">
													<label class="control-label col-sm-3 required">API</label>
													<div class="col-sm-5 controls">
														<select tabindex="4" class="select2 form-control set_apis valid" data-rule-required="false" name="api" id="api">
														</select>
													</div>
												</div>

												<div class="form-group optional_values common hide">
													<label class="control-label col-sm-3 required">Airline</label>
													<div class="col-sm-5 controls">
														<select tabindex="5" class="select2 form-control set_airlines valid" data-rule-required="false" name="airline" id="airline">
														</select>
													</div>
												</div>

												<div class="form-group optional_values common hide">
													<label class="control-label col-sm-3 required">Origin Airport</label>
													<div class="col-sm-5 controls">
														<select tabindex="6" class="select2 form-control set_airports valid" data-rule-required="false" name="o_airport" id="o_airport">
														</select>
													</div>
												</div>

												<div class="form-group optional_values common hide">
													<label class="control-label col-sm-3 required">Destination Airport</label>
													<div class="col-sm-5 controls">
														<select tabindex="7" class="select2 form-control set_airports valid" data-rule-required="false" name="d_airport" id="d_airport">
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Markup Pattern</label>
													<div class="col-sm-5 controls">
													<label class="checkbox-inline"><input autocomplete="off" tabindex="8" checked="true" data-rule-required="true" data-msg-required="" type="radio" value="1" name="mu_pattern">Fixed Amount($)</label>
													<label class="checkbox-inline"><input autocomplete="off" tabindex="8" data-rule-required="true" data-msg-required="" type="radio" value="2" name="mu_pattern">Percentage Amount(%)</label>
													<div class="paymode_error"></div>
													</div>
												</div>

												<div class="form-group payable_amount_div">
													<label class="control-label col-sm-3 required">Percentage / Amount</label>
													<div class="col-sm-5 controls">
														<input autocomplete="off" tabindex="9" class="form-control" data-rule-required="true" name="mu_amount" placeholder="Percentage / Amount" type="text" data-msg-required="Please enter valid value" data-rule-min= "0">
													</div>
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
															 Add Markup
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