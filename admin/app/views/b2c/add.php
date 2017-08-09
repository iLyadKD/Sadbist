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
											<form class="form form-horizontal add_b2c_user_form" action="javascript:void(0);" method="post" enctype="multipart/form-data"> 
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3" for="validation_name">Name</label>
													<div class="col-sm-3 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control valid" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$"   data-msg-pattern="Please enter alphabetic first name" id="validation_fname" name="firstname" placeholder="First Name" type="text">
													</div>
													<div class="col-sm-3 controls">
														<input autocomplete="off" tabindex="2" class="form-control valid" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$"  data-msg-pattern="Please enter alphabetic last name" id="validation_lname" name="lastname" placeholder="Last Name" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_email">E-mail</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" data-rule-email="true" data-rule-required="true" data-msg-required="Please enter your email address" data-msg-pattern="Please valid email address." id="validation_email" name="emailid" placeholder="Ex: sakib.606@gmail.com" type="text">
													</div>
													<label class="control-label col-sm-2 col-sm-2" for="validation_name"> Date of Birth:</label>
													<div class="col-sm-2 controls">
														<input type="text" autocomplete="off" tabindex="4"  name="dob" class="form-control till_current_date adult valid">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_password">Password</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="5" class="form-control" data-rule-minLength="6" data-msg-required="Please enter password" data-msg-minLength="Password length should be minimum of six letters" id="validation_password" data-rule-required="true" name="pass" placeholder="Password" type="password">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_password_confirmation">Confirm Password</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="6" class="form-control" data-rule-equalto="#validation_password" data-rule-required="true" data-msg-equalto="Please re-enter password" id="validation_password_confirmation" name="confirmpass" data-msg-required="Please confirm password" data-msg-minlength="Password mismatch." placeholder="Retype Password" type="password">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Contact No.</label>
													<div class="col-sm-4 controls">
														<input tabindex="7" autocomplete="off" class="form-control flexi_contact"  data-rule-required="true"  id="validation_contact_b2c" name="phone" placeholder="ex : +98 0728 7382782" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="country">Nationality</label>
													<div class="col-sm-4 controls">
														<select tabindex="8" class="select2 form-control set_country valid"  name="country" id="country">
													 </select>
													</div>
												</div>
												<div class="form-group national_id" style="display:none;">
													<label class="control-label col-sm-3">National Code</label>
													<div class="col-sm-4 controls">
														<input data-rule-minLength="10" data-msg-minLength="National code length should be minimum of ten letters" type="text" class="form-control valid" name="national_id">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-2" for="validation_name">Passport number:</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" class="form-control valid"  name="passport_no" placeholder="Passport Number" type="text" tabindex="9" data-rule-maxLength="12"  data-msg-maxLength="Passport no length should not be maximum of 12 letters">
													</div>
													<label class="control-label col-sm-2 col-sm-2" for="validation_name">Passport expire date:</label>
													<div class="col-sm-2 controls">
														<input tabindex="10" type="text" autocomplete="off"  name="passport_exp_date" class="form-control from_date valid">
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-sm-3" for="postal_code">Postal Code</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="11" class="form-control valid" data-rule-number="true"  name="postal_code" placeholder="Postal Code" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="validation_address">Address</label>
													<div class="col-sm-4 controls">
														<textarea tabindex="12" class="form-control valid" id="validation_address" placeholder="Address" name="address" rows="3"></textarea>
													</div>
												</div>
												
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url('b2c.html'); ?>"><button tabindex="13" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="14" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
																Add User
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