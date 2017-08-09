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
													<a tabindex="-1" href="<?php echo base_url();?>">
														<i class="icon-bar-chart"></i>
													</a>
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
											<form class="form form-horizontal update_profile_form" hyperlink="<?php echo $this->input->get('admin')?>" action="javascript:void(0);" method="post"> 
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_fname">First Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" autofocus="true" tabindex="1" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-rule-required="true" data-msg-required="Please enter your first name" data-msg-pattern="Please enter alphabetic first name" id="validation_fname" name="firstname" placeholder="First Name" type="text" value="<?php echo $admin->firstname; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_lname">Last Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="2" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-rule-required="true" data-msg-required="Please enter your last name" data-msg-pattern="Please enter alphabetic last name" id="validation_lname" name="lastname" placeholder="Last Name" type="text" value="<?php echo $admin->lastname; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_email">E-mail</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" data-rule-email="true" data-rule-required="true" id="validation_email" name="emailid" placeholder="E-mail" type="text" value="<?php echo $admin->email_id; ?>" disabled>
													</div>               
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="validation_password">Password</label>
													<div class="col-sm-4 controls input-group">
														<input autocomplete="off" tabindex="-1" class="form-control has-tooltip" data-placement="top" title="The password has been hidden for security reasons!" id="validation_password" name="pw3" value="Password" type="password" disabled>
														<span class="input-group-addon"><i class="icon-unlock-alt"></i></span>
														<a class="change_my_pwd" tabindex="-1" href="javascript:void(0);" hyperlink="<?php echo $this->input->get('admin')?>"><button tabindex="-1" class="btn btn-primary" type="button">
																<i class="icon-key"></i>
																Change Password
															</button></a>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Contact No</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="4" class="form-control" data-rule-number="true" data-rule-minlength="6" data-msg-minLength="Please enter proper contact number" data-msg-min="Please enter proper contact number" data-rule-required="true" data-msg-required="Please enter proper contact number" data-rule-min="1" id="validation_contact" name="phone" placeholder="Contact No" type="text" value="<?php echo $admin->contact_no; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="country">Country</label>
													<div class="col-sm-4 controls">
														<select tabindex="5" class="select2 form-control set_country" data-rule-required="true" data-msg-required="Select country" name="country" id="country" hyperlink="<?php echo $admin->country; ?>">
													 	</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="state">State</label>
													<div class="col-sm-4 controls">
														<select tabindex="6" class="select2 form-control set_state" data-rule-required="true" data-msg-required="Select region / state" name="state" id="state" hyperlink="<?php echo $admin->country.":::".(is_null($admin->state) ? NO_REGION : $admin->state); ?>">
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="city">City</label>
													<div class="col-sm-4 controls">
														<select tabindex="7" class="select2 form-control set_city" data-rule-required="true" data-msg-required="Select city" name="city" id="city" hyperlink="<?php echo $admin->city; ?>">
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_address">Address</label>
													<div class="col-sm-4 controls">
														<textarea tabindex="8" class="form-control" id="validation_address" data-rule-required="true" data-msg-required="enter your address." placeholder="Address" name="address" rows="3"><?php echo $admin->address; ?></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Postal Code</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="9" class="form-control" data-rule-number="true"  name="postal_code" value="<?php echo $admin->postal_code; ?>" placeholder="Postal Code" type="text">
													</div>
												</div>
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>">
															<button class="btn btn-primary" tabindex="10" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button class="btn btn-primary" tabindex="11" type="submit">
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