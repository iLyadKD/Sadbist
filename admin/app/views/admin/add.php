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
										</div>

										<div class="box-content">
											<form class="form form-horizontal add_admin_form" action="javascript:void(0);" method="POST">
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_fname">User Type</label>
													<div class="col-sm-4 controls">
														<select tabindex="7" class=" form-control" data-rule-required="true" data-msg-required="Select user type" name="user_type" id="user_type">
															<option value="2">SUB ADMIN</option>
															<option value="6">CALL CENTER STAFF</option>
													 	</select>
													</div>
												</div> 
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_fname">First Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-rule-required="true" data-msg-required="Please enter your first name" data-msg-pattern="Please enter alphabetic first name" id="validation_fname" name="firstname" placeholder="First Name" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_lname">Last Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="2" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-rule-required="true" data-msg-required="Please enter your last name" data-msg-pattern="Please enter alphabetic last name" id="validation_lname" name="lastname" placeholder="Last Name" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_email">E-mail Address</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="3" class="form-control" data-rule-email="true" data-rule-required="true" data-msg-required="Please enter your email address" data-msg-pattern="Please valid email address." id="validation_email" name="emailid" placeholder="E-mail" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_password">Password</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="4" class="form-control" data-rule-minLength="6" data-msg-required="Please enter password" data-msg-minLength="Password length should be minimum of six letters" id="validation_password" name="pass" placeholder="Password" type="password">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_password_confirmation">Confirm Password</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="5" class="form-control" data-rule-equalto="#validation_password" data-rule-required="true" data-msg-equalto="Please re-enter password" id="validation_password_confirmation" name="confirmpass" data-msg-required="Please confirm password" data-msg-minlength="Password mismatch." placeholder="Retype Password" type="password">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Contact Number</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="6" class="form-control flexi_contact"  data-rule-required="true" data-msg-required="Please enter proper contact number" data-rule-min="1" id="validation_contact" name="phone" placeholder="ex : +98 0728 7382782" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for= "country-list">Country</label>
													<div class="col-sm-4 controls">
														<select tabindex="7" class="select2 form-control set_country" data-rule-required="true" data-msg-required="Select country" name="country" id="country">
													 	</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_address">Address</label>
													<div class="col-sm-4 controls">
														<textarea tabindex="10" class="form-control" id="validation_address" placeholder="Address" name="address" rows="3"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="postal_code">Postal Code</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="11" class="form-control" data-rule-number="true" data-rule-required="true" data-msg-required="Enter numberic Pin / Zip code." data-msg-number="Enter numberic Pin / Zip code." name="postal_code" placeholder="Postal Code" type="text">
													</div>
												</div>
												<hr class="hr-normal">
												<div class="form-group">
													<label class="col-sm-3 control-label required">Privileges</label>
													<div class="col-md-8">
														<table class="privileges_list">
															<?php $c=1; 
															foreach($privileges as $priv)
															{
																if($c==1){  echo "<tr>"; }
															?>
																<td><label class="checkbox-inline"><input autocomplete="off" tabindex="12" data-rule-required="true" data-msg-required="" type="checkbox" value="<?=$priv->id;?>" name="privilege[]"><?=$priv->privilege;?></label></td>
															<?php 
																if($c==4){  $c=0; echo "</tr>"; }
																$c++;
															}  
															?>
														</table>
														<br><label class="select_privilege_err">Select at-least one privilege.</label>
													</div>
												</div>
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button tabindex="13" class="btn btn-primary" type="button">
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