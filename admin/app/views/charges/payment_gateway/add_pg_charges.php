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
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
											<form class="form form-horizontal add_pg_charges_form" action="javascript:void(0);" method="post">
												
												<div class="form-group">
													<label class="control-label col-sm-3 required">Payment Gateway</label>
													<div class="col-sm-5 controls">
														<select tabindex="1" autofocus="true" class="select2 form-control set_payment_gateway" data-rule-required="true" name="pg_name" id="pg_name" data-msg-required="Please select Payment Gateway Name">
														</select>
													</div>
												</div>
												<div class="form-group hide">
													<label class="control-label col-sm-3 required">Payment Mode</label>
													<div class="col-sm-5 controls">
													<label class="checkbox-inline"><input autocomplete="off" tabindex="2" checked="true" data-rule-required="true" data-msg-required="" type="radio" value="0" name="pay_mode">Fixed Amount($)</label>
													<label class="checkbox-inline"><input autocomplete="off" tabindex="2" data-rule-required="true" data-msg-required="" type="radio" value="1" name="pay_mode">Percentage Amount(%)</label>
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">API</label>
													<div class="col-sm-5 controls">
														<select tabindex="3" class="select2 form-control set_apis valid" name="api" id="api">
														</select>
													</div>
												</div>
												<div class="form-group payable_amount_div">
													<label class="control-label col-sm-3 required">Amount</label>
													<div class="col-sm-5 controls">
														<input tabindex="4" class="form-control" autocomplete="off" data-rule-required="true" name="pg_amount" placeholder="Amount" type="text" data-msg-required="Please enter valid value" data-rule-min= "0">
													</div>
												</div>

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"><button tabindex="5" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="6" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Add Payment Charges
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