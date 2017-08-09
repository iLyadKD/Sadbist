<!DOCTYPE html>
<html class="no-js">
	<?php
		$include["css"][] = "bootstrap/bootstrap";
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
										<div class="tabbable">
											<ul class="nav nav-responsive nav-tabs">
												<li class="active"><a data-toggle="tab" href=".pcptab">Promo code by %</a></li>
												<li class=""><a data-toggle="tab" href=".pcctab">Promo code by amount</a></li>
											</ul>
											<div class="tab-content">
													<div class="tab-pane active pcptab">
														<form class="form form-horizontal add_percent_promo_form" action="javascript:void(0);" method="post">
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="promo_code">Promo Code</label>
																<div class="col-sm-4 controls">
																	<input autocomplete="off" tabindex="1" autofocus="true" class="form-control set_readonly_promo" hyperlink="<?php echo $promo_code; ?>" data-rule-required="true" name="promo_code" type="text" value="<?php echo $promo_code; ?>" readonly />
																</div>                              
																<div class="form-group">
																		<div class="btn-group auto_promo_toggle">
																			<a class="btn btn-primary btn-sm active" hyperlink="1">Auto</a>
																			<a class="btn btn-primary btn-sm notActive" hyperlink="0">Manual</a>
																		</div>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="discount">Discount in %</label>
																<div class="col-sm-4 controls">
																	<input autocomplete="off" tabindex="2" class="form-control" max="100" data-rule-number="true" data-rule-required="true" data-rule-min="0" data-rule-max="100" id="discount" name="discount" type="text" placeholder="Discount">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="promo_amount">Amount Range </label>
																<div class="col-sm-4 controls">
																	<input autocomplete="off" tabindex="3" class="form-control"  data-rule-number="true" data-rule-required="true" data-rule-min="0" id="promo_amount" name="promo_amount" type="text" placeholder="Amount Range">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-sm-3 required">Expiry Date</label>
																<div class="input-group col-sm-4">
																	<input autocomplete="off" tabindex="4" class="form-control today_onwards_limited" value="<?php echo date("m/d/Y"); ?>" data-format="MM/DD/YYYY" data-rule-required ="true" data-msg-required="Please select date." placeholder="MM/DD/YYYY" name="expiry_date"  type="text" readonly="">
																	<span class="input-group-addon">
																		<span class="icon-calendar" data-time-icon="icon-time"></span>
																	</span>
																</div>
															</div>

															<div class="form-actions">
																<div class="row">
																	<div class="col-sm-9 col-sm-offset-3">
																		<a tabindex="-1" href="<?php echo base_url('promocode'.DEFAULT_EXT); ?>"><button tabindex="5" class="btn btn-primary" type="button">
																			<i class="icon-reply"></i>
																			Go Back
																		</button></a>
																		<button tabindex="6" class="btn btn-primary" type="submit">
																			<i class="icon-plus"></i>
																			Add Promo
																		</button>
																	</div>
																</div>
															</div>
														</form>
													</div>

													<!-- tab 2 -->
													<div class="tab-pane pcctab">
														<form class="form form-horizontal add_cash_promo_form" action="javascript:void(0);" method="post"> 
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="promo_code">Promo Code</label>
																<div class="col-sm-4 controls">
																	<input autocomplete="off" tabindex="1" autofocus="true" class="form-control set_readonly_promo" hyperlink="<?php echo $promo_code; ?>" data-rule-required="true" name="promo_code" type="text" value="<?php echo $promo_code; ?>" readonly />
																</div>                              
																<div class="form-group">
																		<div class="btn-group auto_promo_toggle">
																			<a class="btn btn-primary btn-sm active" hyperlink="1">Auto</a>
																			<a class="btn btn-primary btn-sm notActive" hyperlink="0">Manual</a>
																		</div>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="discount">Discount in USD</label>
																<div class="col-sm-4 controls">
																	<input autocomplete="off" tabindex="2" class="form-control" data-rule-number="true" data-rule-required="true" data-rule-min="0" id="discount" name="discount" type="text" placeholder="Discount">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="promo_amount">Amount Range </label>
																<div class="col-sm-4 controls">
																	<input autocomplete="off" tabindex="3" class="form-control"  data-rule-number="true" data-rule-required="true"  data-rule-min="0" id="promo_amount" name="promo_amount" type="text" placeholder="Amount Range">
																</div>
															</div>
															<div class="form-group">
																<label class="control-label col-sm-3 required" for="datepicker">Expiry Date</label>
																<div class="input-group col-sm-4">
																	<input autocomplete="off" tabindex="4" class="form-control today_onwards_limited" value="<?php echo date("m/d/Y"); ?>" data-format="MM/DD/YYYY" data-rule-required ="true" data-msg-required="Please select date." placeholder="MM/DD/YYYY" name="expiry_date"  type="text" readonly="">
																	<span class="input-group-addon">
																		<span class="icon-calendar" data-time-icon="icon-time"></span>
																	</span>
																</div>
															</div>

															<div class="form-actions">
																<div class="row">
																	<div class="col-sm-9 col-sm-offset-3">
																		<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button tabindex="5" class="btn btn-primary" type="button">
																			<i class="icon-reply"></i>
																			Go Back
																		</button></a>
																		<button tabindex="6" class="btn btn-primary" type="submit">
																			<i class="icon-plus"></i>
																			Add Promo
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
					 
						</div>
					</div>
					<?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>