<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "bootstrap/bootstrap-tagsinput";
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
							
							<div class="row">
								<div class="col-sm-12">
								<div class="box"><div class="notification"></div></div>
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
											<div class="actions">
												
											</div>
										</div>
										<div class="box-content">
											<form class="form form-horizontal update_email_template_form" action="javascript:void(0);" hyperlink="<?php echo $this->input->get('email'); ?>" method="post">
												<div class="form-body">
														<div class="form-group">
															<label class="col-md-3 control-label required" for="subject">Email Type</label>
																<div class="col-md-4 controls">
																	<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-minlength="2" data-rule-required="true" name="email_type" placeholder="Enter Type" type="text" value="<?php echo $email->email_name; ?>" />
																</div>
														</div>
														<div class="form-group">
															<label class="col-md-3 control-label required" for="subject">Subject</label>
																<div class="col-md-4 controls">
																	<input autocomplete="off" tabindex="2" class="form-control" data-rule-minlength="2" data-rule-required="true" id="subject" name="subject" placeholder="Enter Subject" type="text" value="<?php echo $email->subject ?>" />
																</div>
														</div>
														<div class="form-group">
																<label class="col-md-3 control-label required">Email From</label>
																<div class="col-md-4 controls">
																		<input autocomplete="off" tabindex="3" class="form-control placeholder-no-fix helperText" data-rule-minlength="2" data-rule-required="true" data-rule-email="true" type="text" id="email_from" placeholder="Enter sender email id" name="email_from" value="<?php echo $email->email_from ?>" />
																</div>
														</div>
														<div class="form-group">
																<label class="col-md-3 control-label required">Email From Name</label>
																<div class="col-md-4 controls">
																		<input autocomplete="off" tabindex="4" class="form-control helperText" data-rule-minlength="2" data-rule-required="true" type="text" id="email_from_name" placeholder="Enter sender name" name="email_from_name" id="email_from_name" value="<?php echo $email->email_from_name ?>"/>
																</div>
														</div>
														<div class="form-group">
																<label class="col-md-3 control-label required">Email Bcc</label>
																<div class="col-md-4 controls">
																		<input autocomplete="off" tabindex="5" class="form-control helperText input_tags valid" id="to_email" type="text" name="to_email" value="<?php echo $email->to_email; ?>"/>
																</div>
														</div>
														<div class="form-group">
																<label class="col-md-3 control-label required">Edit Template</label>
																<div class="col-md-8 controls">
																	 <textarea tabindex="6" class="ckeditor form-control" data-rule-minlength="2" data-rule-required="true" name="email_body" id="email_body" rows="30" cols="200">
																				<?php echo $email->message; ?>
																	 </textarea>
																</div>
														</div>
												</div>

												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>">
																<button tabindex="7" class="btn btn-primary" type="button">
																	<i class="icon-reply"></i>
																		Go Back
																</button>
															</a>
																<button tabindex="8" class="btn btn-primary" type="submit">
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