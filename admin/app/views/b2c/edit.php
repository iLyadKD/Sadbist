<!DOCTYPE html>
<html class="no-js">
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
											<i class="icon-male"></i>
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
								<div class="notification"></div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
											<div class="actions">
												<a tabindex="-1" class="btn btn-warning btn-xs has-tooltip" data-placement="top" title="View Profile" href="<?php echo base_url($this->data['controller'].'/view_profile'.DEFAULT_EXT.'?user='.$id); ?>">
													<i class="icon-search"></i>
												</a>
												<a tabindex="-1" class="btn btn-warning btn-xs has-tooltip" data-placement="top" title="View Bookings" href="javascript:void(0);">
													<i class="icon-eye-close"></i>
												</a>
											</div>
										</div>

										<div class="box-content">
											<form class="form form-horizontal update_b2c_user_form" action="javascript:void(0);" method="post" hyperlink="<?php echo $id; ?>" enctype="multipart/form-data"> 
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3" for="validation_fname">User Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$"   data-msg-pattern="Please enter alphabetic first name" id="validation_fname" name="firstname" placeholder="First Name" type="text" value="<?php echo $user->firstname; ?>">
													</div>
													<div class="col-sm-3 controls">
														<input autocomplete="off" tabindex="2" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$"  data-msg-pattern="Please enter alphabetic last name" id="validation_lname" name="lastname" placeholder="Last Name" type="text" value="<?php echo $user->lastname; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_email">E-mail</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" data-rule-email="true" data-rule-required="true" data-msg-required="you have altered email address." data-msg-pattern="you have altered email address." id="validation_email" name="emailid" placeholder="Ex: sakib.606@gmail.com" type="text" disabled="" value="<?php echo $user->email_id; ?>">
													</div>
													<label class="control-label col-sm-2 col-sm-2" for="validation_name"> Date of Birth:</label>
													<div class="col-sm-2 controls">
														<input type="text" autocomplete="off" tabindex="4"  name="dob" class="form-control till_current_date adult valid" value="<?php echo date('m/d/Y',strtotime($user->dob)); ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Contact No</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="5" class="form-control valid flexi_contact"  data-rule-required="true"  id="validation_contact_b2c" name="phone" placeholder="ex : +98 0728 7382782" type="text" value="<?php echo $user->contact_no; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="country">Nationality</label>
													<div class="col-sm-4 controls">
														<select tabindex="6" class="select2 form-control set_country valid"  name="country" id="country" hyperlink="<?php echo $user->country; ?>">
													 </select>
													 <input class="form-control valid" type="hidden" value="<?php echo $user->country; ?>" id="code_country">
													</div>
												</div>
												
												<?php
													if($user->national_id == 0) $style="display:none;"; else $style = '';
												?>
												<div class="form-group national_id" style="<?php echo $style;?>">
													<label class="control-label col-sm-3">National Code</label>
													<div class="col-sm-4 controls">
														<input data-rule-minLength="10" data-msg-minLength="National code length should be minimum of ten letters" tabindex="7" class="form-control" value="<?php echo $user->national_id;?>" type="text"  name="national_id">
													</div>
												</div>
										<div class="form-group">
													<label class="control-label col-sm-3 col-sm-2" for="validation_name">Passport number:</label>
													<div class="col-sm-4 controls">
														<input tabindex="8" autocomplete="off" class="form-control valid"  name="passport_no" placeholder="Passport Number" type="text" data-rule-maxLength="12"  data-msg-maxLength="Passport no length should not be maximum of 12 letters" value="<?php echo $user->passport_no;?>">
													</div>
													<label class="control-label col-sm-2 col-sm-2" for="validation_name">Passport expire date:</label>
													<div class="col-sm-2 controls">
														<input tabindex="9" class="form-control from_date valid" value="<?php echo date('m/d/Y',strtotime($user->passport_exp_date));?>" type="text" autocomplete="off"  name="passport_exp_date">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="postal_code">Postal Code</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="11" class="form-control valid" data-rule-number="true"  name="postal_code" placeholder="Postal Code" type="text" value="<?php if($user->postal_code != 0) echo $user->postal_code; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="validation_address">Address</label>
													<div class="col-sm-4 controls">
														<textarea tabindex="11" class="form-control valid" id="validation_address"  placeholder="Address" name="address" rows="3"><?php echo $user->address; ?></textarea>
													</div>													
												</div>
												
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button tabindex="11" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="12" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
																Update User
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