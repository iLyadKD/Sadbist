<!DOCTYPE html>
<html class="no-js">
	<?php

	$include["css"][] = "backslider";
	$include["css"][] = "owl.theme";
	$include["css"][] = "owl.carousel";
	$include["css"][] = "jquery.bxslider";
	$this->load->view("common/head", $include);

	?>
<?php
if($this->session->userdata("10020_default_language") !== false && !is_null($this->session->userdata("10020_default_language")))
{
	#$include['css'][] = ASSET_FOLDER.'/css/'.$this->session->userdata("10020_default_language").'/select.css';
}
else
{
	#$include['css'][] = ASSET_FOLDER.'/css/english/select.css';
}
$include['link_css'][] = 'https://fonts.googleapis.com/css?family=Roboto';
$this->load->view('common/header',$include); 
//$this->load->view('common/notifications');
?>

<section class="mn-reg">
	<div class="container">
		<div class="row">
			<div class="col-md-12 nopadding">
				<div class="col-md-12 col-sm-12" id="toBottom">
					<div class="sign_up-1"> 
						<!-- start form -->
						<?php echo form_open_multipart("b2c/register", array("class" => "sign user_register", "method" => "post")); ?>
							<div class="formtitle"><?php echo $this->lang->line("register_to_hundred"); ?></div>
							<!-- start top_section -->
							<div class="top_section">
								<div class="section">
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_first_name"); ?></label>
											<input type="text" name="fname" data-rule-required="true" data-rule-pattern="[A-Za-z]{2,20}" data-msg-required="Please enter firstname." data-msg-pattern="Please enter alphabetical name." placeholder="<?php echo $this->lang->line("user_reg_first_name"); ?>" class="input-1">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_last_name"); ?></label>
											<input type="text" name="lname" data-rule-required="true" data-rule-pattern="(([a-zA-Z] ?){2,30})" data-msg-required="Please enter lastname." data-msg-pattern="Please enter alphabetical name." placeholder="<?php echo $this->lang->line("user_reg_last_name"); ?>" class="input-1">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_date_of_birth"); ?></label>
											<input type="text" name="dob" data-rule-required="true" value="<?php echo date('m/d/Y', strtotime('-10 year')); ?>" placeholder="<?php echo $this->lang->line("user_reg_date_of_birth"); ?>" class="input-1 dob">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_password"); ?></label>
											<input type="password" id="password" name="password" data-rule-minlength="6" data-rule-minlength="Please enter minimum 6 characters." data-rule-required="true" placeholder="<?php echo $this->lang->line("user_reg_password"); ?>" class="input-1">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_confirm_password"); ?></label>
											<input type="password" name="cpassword" data-rule-equalTo="#password" data-msg-equalTo="Re-enter password" data-rule-required="true" placeholder="<?php echo $this->lang->line("user_reg_confirm_password"); ?>" class="input-1">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_email"); ?></label>
											<input type="email" name= "email" data-rule-required="true" data-rule-email="true" data-msg-email="Please enter valid Email address" data-msg-required="Please enter Email address"  placeholder="<?php echo $this->lang->line("user_reg_email"); ?>" class="input-1">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_contact_number"); ?></label>
											<input type="" name="contact_no" data-rule-required="true" data-rule-pattern="((\+91)?[789][0-9]{9,14})" data-msg-required="Please enter phone number." data-msg-pattern="Please enter valid phone number." placeholder="<?php echo $this->lang->line("user_reg_contact_number"); ?>" class="input-1">
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_country"); ?></label>
											<select class="frm-field section-country input-1 region_country" name="country" data-rule-required="true" data-msg-required="Select your country">
												<option selected value=""><?php echo $this->lang->line("user_reg_select_country"); ?></option>
												<?php
													if($countries !== false)
													foreach($countries as $country)
													{
														$sel_country = $country->id === $my_country_code ? "selected" : "";
														echo "<option $sel_country value='".$country->id."'>".$country->country."</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_state_region"); ?></label>
											<select class="frm-field section-country input-1 region_state" name="state" data-rule-required="true" data-msg-required="Select your state / region">
												<option selected value=""><?php echo $this->lang->line("user_reg_select_state_region"); ?></option>
												<?php
													if($states !== false)
													foreach($states as $state)
														echo "<option value='".$state->country.":::".$state->region."'>".$state->name."</option>";
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_city"); ?></label>
											<select class="frm-field section-country input-1 region_city" name="city" data-rule-required="true" data-msg-required="Select your city">
												<option selected value=""><?php echo $this->lang->line("user_reg_select_city"); ?></option>
											</select>
										</div>
									</div>

									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_address"); ?></label>
											<textarea name="address" placeholder="<?php echo $this->lang->line("user_reg_address"); ?>" class="input-1" style="height:30px;"></textarea>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_postal_zipcode"); ?></label>
											<input type="" name="postal_code" data-rule-required="true" data-rule-pattern="([1-9][0-9]{4,7})" data-msg-required="Please enter postal / zip code." data-msg-pattern="Please enter valid postal / zip code." placeholder="<?php echo $this->lang->line("user_reg_postal_zipcode"); ?>" class="input-1">
										</div>
									</div>

									<div class="col-md-4 col-sm-6">
										<div class="input">
											<label><?php echo $this->lang->line("user_reg_profile_pic"); ?></label>
											<input type="file" name="user_image" data-rule-accept="image/*" data-msg-accept="Please select only image file." placeholder="Images files only" class="input-1">
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="section">
									<div class="col-md-6 col-sm-12 pull-left">
										<div class="alreadyaccount"><?php echo $this->lang->line("user_reg_already_have_account"); ?> <a href="javascript:void(0)" data-target="#myModal" data-toggle="modal" class="sign_color">Sign In</a></div>
									</div>
									<div class="col-md-6 pull-right">
										<div class="submit">
											<input type="submit" value="<?php echo $this->lang->line("user_reg_register_here"); ?>" class="btn-2">
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<!-- end bottom-section -->
						<?php echo form_close(); ?>
						<!-- end form --> 
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php 
$this->load->view('common/pop-ups');
$include = array();
#$include['js'][] = ASSET_FOLDER.'/js/select.js';
$this->load->view('common/footer',$include);
?>
<script type="text/javascript">
	set_base_url("<?php echo base_url(); ?>", "<?php echo asset_url(ASSET_FOLDER); ?>");
</script>
</body>
</html>