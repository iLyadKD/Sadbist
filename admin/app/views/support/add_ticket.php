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
											<form class="form form-horizontal add_support_ticket_form" action="javascript:void(0);" method="post">
												<div class="form-body">
													<?php if($this->data["admin_type"] === SUPER_ADMIN_USER)
															{
													?>
														<div class="form-group">
															<label class="col-md-3 control-label required" for="subject">User Type</label>
																<div class="col-md-4 controls">
																	<select tabindex="1" autofocus="true" class="select2 form-control set_support_user_type" data-rule-required="true" data-msg-required="Select user type" name="user_type" id="user_type">
													 				</select>
																</div>
														</div>

														<div class="form-group">
															<label class="col-md-3 control-label required" for="subject">Select User</label>
																<div class="col-md-4 controls">
																	<select tabindex="2" class="select2 form-control set_support_users" data-rule-required="true" data-msg-required="Select user" name="support_user" id="support_user">
													 				</select>
																</div>
														</div>
													<?php
															}
													?>

														<div class="form-group">
															<label class="col-md-3 control-label required" for="subject">Select Subject</label>
																<div class="col-md-4 controls">
																	<select tabindex="3" class="select2 form-control set_support_subjects" data-rule-required="true" data-msg-required="Select subject" name="support_subject" id="support_subject">
													 				</select>
																</div>
														</div>

														<div class="form-group">
															<label class="control-label col-sm-3" for="validation_image">Attachment File</label>
															<div class="col-sm-4 controls">
																<input tabindex="4" type="file" data-msg-accept="Select valid file" class="form-control" id="validation_image" name="file_name" data-rule-accept="image/*,.pdf,.txt,.doc,.docx,.rtf,.wpd,.zip,.rar,.xls,.csv,.xlsx">
														 		<p class="help-block">
																	<small class="text-muted">If more then one file, make it ZIP and attach.</small>
																</p>
															</div>
														</div>
													<div class="form-group">
														<label class="control-label col-sm-3 required" for="sub">Message</label>
														<div class="col-sm-4 controls">
															<textarea tabindex="5" class="char-max-length form-control" maxlength="150" id="textcounter" placeholder="Some text here..." data-rule-required="true" data-msg-required="Enter description" rows="3" name="message"></textarea>
															 <p class="help-block">
																<small class="text-muted">Maximum Allowed Characters 150</small>
															</p>
														</div>
													</div>
												</div>

												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>">
																<button tabindex="6" class="btn btn-primary" type="button">
																	<i class="icon-reply"></i>
																		Go Back
																</button>
															</a>
																<button tabindex="7" class="btn btn-primary" type="submit">
																	<i class="icon-save"></i>
																	Submit
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