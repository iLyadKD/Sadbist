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
											<form class="form form-horizontal update_admin_form" hyperlink="<?php echo $this->input->get('admin')?>" action="javascript:void(0);" method="post"> 
												<div class="form-group">
													<label class="control-label col-sm-3 col-sm-3 required" for="validation_fname">First Name</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" class="form-control" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-rule-required="true" data-msg-required="Please enter your first name" data-msg-pattern="Please enter alphabetic first name" id="validation_fname" name="firstname" placeholder="First Name" type="text" value="<?php echo $admin->firstname; ?>">
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
														<input autocomplete="off" class="form-control has-tooltip" data-placement="top" title="The password has been hidden for security reasons!" id="validation_password" name="pw3" value="Password" type="password" disabled>
														<span class="input-group-addon"><i class="icon-unlock-alt"></i></span>
														<a tabindex="-1" class="change_pwd_admin" href="javascript:void(0);" hyperlink="<?php echo $this->input->get('admin')?>"><button tabindex="-1" class="btn btn-primary" type="button">
																<i class="icon-key"></i>
																Change Password
															</button></a>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Contact Number</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="4" class="form-control flexi_contact"  data-rule-required="true" data-msg-required="Please enter proper contact number" data-rule-min="1" id="validation_contact" name="phone" placeholder="ex : +98 0728 7382782" type="text" value="<?php echo $admin->contact_no; ?>">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="country-list">Country</label>
													<div class="col-sm-4 controls">
														<select tabindex="5" class="select2 form-control set_country" data-rule-required="true" data-msg-required="Select country" name="country" id="country" hyperlink="<?php echo $admin->country; ?>">
													 	</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="validation_address">Address</label>
													<div class="col-sm-4 controls">
														<textarea tabindex="8" class="form-control" id="validation_address" placeholder="Address" name="address" rows="3"><?php echo $admin->address; ?></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="validation_contact">Postal Code</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="9" class="form-control" data-rule-required="true" data-rule-number="true"  name="postal_code" value="<?php echo $admin->postal_code; ?>" placeholder="Postal Code" type="text">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label required">Privileges</label>
													<div class="col-md-8">
														<table class="privileges_list">
															<?php $c=1;
															$granted = array_filter(explode(",", $admin->privileges));
															foreach($privileges as $priv)
															{
																$is_checked = in_array($priv->id, $granted) ? "checked" : "";
																if($c==1){  echo "<tr>"; }
															?>
																<td><label class="checkbox-inline"><input autocomplete="off" tabindex="10" data-rule-required="true" data-msg-required="" type="checkbox" <?php echo $is_checked; ?> value="<?=$priv->id;?>" name="privilege[]"><?=$priv->privilege;?></label></td>
															<?php 
																if($c==4){  $c=0; echo "</tr>"; }
																$c++;
															}  
															?>
														</table>
														<br><label class="select_privilege_err"></label>
													</div>
												</div>
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>">
															<button tabindex="11" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="12" class="btn btn-primary" type="submit">
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