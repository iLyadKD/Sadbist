<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
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
											<i class="icon-user"></i>
											<span><?php echo $this->data["page_title"] ?></span>
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
												<li>
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"] ?></a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li class="active"><?php echo $this->data["page_title"] ?></li>
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
											<div class="title"><?php echo $this->data["page_title"] ?></div>
										</div>
										<div class="box-content">
											<form class="form form-horizontal add_company_form" action="javascript:void(0);" method="post" enctype="multipart/form-data"> 
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_cname">Company Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control valid"  id="validation_cname" name="cname" placeholder="Company Name" type="text" data-rule-required="true">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_url">URL</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control valid" data-rule-pattern="https?://.+"   data-msg-pattern="Please enter valid url" id="validation_url" name="url" placeholder="URL" type="text" data-rule-required="true">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_uname">User Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control valid"  id="validation_uname" name="uname" placeholder="User Name" type="text" data-rule-required="true">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_pswd">Password</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control valid" id="validation_pswd" name="pswd" placeholder="Password" type="password" data-rule-required="true">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_contact_name">Contact Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control valid"  id="validation_contact_name" name="contact_name" placeholder="Contact Name" type="text" data-rule-required="true">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="phone1">Support Phone</label>
													<div class="col-sm-4 controls">
														<input tabindex="7" autocomplete="off" class="form-control flexi_contact"  data-rule-required="true"  id="phone1" name="phone1" placeholder="ex : +98 0728 7382782" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="phone2">Other Phone</label>
													<div class="col-sm-4 controls">
														<input tabindex="7" autocomplete="off" class="form-control flexi_contact valid"  data-rule-required="false"  id="phone2" name="phone2" placeholder="ex : +98 0728 7382782" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3" for="comments">Comments</label>
													<div class="col-sm-4 controls">
														<textarea autocomplete="off" tabindex="1" autofocus="true" class="form-control valid" name="comments" placeholder="Comments" ></textarea>
													</div>
												</div>
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url('call_center'); ?>"><button tabindex="13" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="14" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
																Save
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