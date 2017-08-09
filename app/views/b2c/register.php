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
$include['link_css'][] = 'https://fonts.googleapis.com/css?family=Roboto';
$this->load->view('common/header',$include); 
//$this->load->view('common/notifications');
?>

<section class="mn-reg">
	<div class="container">
		<div class="row">
			<div class="col-md-12 nopadding">
				<div class="col-md-4  col-md-offset-4 col-sm-12" id="toBottom">
					<div class="sign_up-1"> 
						<!-- start form -->

						<?php 

						$ses = SESSION_PREPEND;

						if($this->session->flashdata($ses.'notification_msg')){
						$status = $this->session->flashdata($ses.'notification_status');
						$mes = $this->session->flashdata($ses.'notification_msg');
						?>
						<div class="alert alert-block alert-<?php echo $status; ?>">
							<?php echo $mes;?>
						</div>
						<?php } ?>
						
						<form action="<?php echo base_url('b2c/register') ?>" class="sign user_register" method="post" enctype="multipart/form-data">
							<div class="formtitle"><?php echo $this->lang->line("register_with_us_caps"); ?></div>
							<!-- start top_section -->
							<div class="top_section">



								<div class="section">

								<div class="col-md-12 col-sm-12">
							
										  <!--<div class="input">
										    <label><?php echo $this->lang->line("first_name"); ?></label>
										    <input type="text" name="fname" data-rule-required="true" data-rule-pattern="[A-Za-z]{2,20}" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alphabetics_error"); ?>" placeholder="<?php echo $this->lang->line("first_name"); ?>" class="input-1">
										  </div>
										 <div class="input">
											<label><?php echo $this->lang->line("last_name"); ?></label>
											<input type="text" name="lname" data-rule-required="true" data-rule-pattern="(([a-zA-Z] ?){2,30})" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-msg-pattern="<?php echo $this->lang->line("only_alplabetics_error"); ?>" placeholder="<?php echo $this->lang->line("last_name"); ?>" class="input-1">
										</div>-->
										 
										  <div class="input">
											<label class="required"><?php echo $this->lang->line("email_address"); ?></label>
											<input type="email" name= "email" data-rule-required="true" data-rule-email="true" data-msg-email="<?php echo $this->lang->line("email_address_valid_error"); ?>" data-msg-required="<?php echo $this->lang->line("email_address_required"); ?>" placeholder="<?php echo $this->lang->line("example_email_address"); ?>" class="input-1">
										</div>
										  
										  <div class="input">
											<label class="required"><?php echo $this->lang->line("contact_number"); ?></label>
											<input type="text" name="contact_no" data-rule-required="true"  id="validation_contact_b2c"  placeholder="<?php echo $this->lang->line("example_contact_number"); ?>"  data-msg-required="<?php echo $this->lang->line("contact_number_valid_error"); ?>" class="input-1 flexi_contact" onblur="checkPhone();">
											<span class="spcl_req_label"></span>
										</div>
										
										<div class="input">
											<label class="required"><?php echo $this->lang->line("password"); ?></label>
											<input type="password" id="password" name="password" data-rule-minlength="6" data-rule-minlength="<?php echo $this->lang->line("password_length_error"); ?>" data-rule-required="true" placeholder="<?php echo $this->lang->line("password"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" class="input-1">
										</div>
						
										<div class="input">
											<label class="required"><?php echo $this->lang->line("confirm_password"); ?></label>
											<input type="password" name="cpassword" data-rule-equalTo="#password" data-msg-equalTo="<?php echo $this->lang->line("enter_confirm_password"); ?>" data-rule-required="true" placeholder="<?php echo $this->lang->line("confirm_password"); ?>" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" class="input-1" >
										</div>
										

										

										<div class="input">
											<div class="submit">
												<input type="submit" value="<?php echo $this->lang->line("register_here"); ?>" class="btn-1" id="btn-1">
											</div>
										</div>

										<div class="input">
											<div class="alreadyaccount"><?php echo $this->lang->line("already_have_account"); ?>
											<a href="#" data-toggle="modal" data-target=".b2b_login_model" class="sign_color"><?php echo $this->lang->line("sign_in_here"); ?></a>
											</div>
										</div>
							
								
									<div class="clearfix"></div>							
								</div>
							</div>
							<!-- end bottom-section -->
						</form>
						<!-- end form --> 
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php 
$this->load->view('common/pop-ups');
$this->load->view('common/notification');
$include = array();
#$include['js'][] = ASSET_FOLDER.'/js/select.js';
$this->load->view('common/footer',$include);
?>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</body>
</html>