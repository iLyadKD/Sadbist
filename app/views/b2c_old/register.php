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
				<div class="col-md-4  col-md-offset-4 col-sm-12" id="toBottom">
					<div class="sign_up-1"> 
						<!-- start form -->
						
						<form action="<?php echo base_url('b2c/register') ?>" class="sign user_register" method="post" enctype="multipart/form-data">
							<div class="formtitle"><?php echo $this->lang->line("register_to_hundred"); ?></div>
							<!-- start top_section -->
							<div class="top_section">
								<div class="section">

								<div class="col-md-12 col-sm-12">
							
										  <!--<div class="input">
										    <label><?php echo $this->lang->line("user_reg_first_name"); ?></label>
										    <input type="text" name="fname" data-rule-required="true" data-rule-pattern="[A-Za-z]{2,20}" data-msg-required="Please enter firstname." data-msg-pattern="Please enter alphabetical name." placeholder="<?php echo $this->lang->line("user_reg_first_name"); ?>" class="input-1" value="<?php echo set_value('fname'); ?>">
										    <label for="fname" class="error"><?php echo form_error('fname'); ?></label>
										  </div>
										 <div class="input">
											<label><?php echo $this->lang->line("user_reg_last_name"); ?></label>
											<input type="text" name="lname" data-rule-required="true" data-rule-pattern="(([a-zA-Z] ?){2,30})" data-msg-required="Please enter lastname." data-msg-pattern="Please enter alphabetical name." placeholder="<?php echo $this->lang->line("user_reg_last_name"); ?>" class="input-1" value="<?php echo set_value('lname'); ?>">
											<label for="lname" class="error"><?php echo form_error('lname'); ?></label>
										</div>-->
										 
										  <div class="input">
											<label class="required"><?php echo $this->lang->line("user_reg_email"); ?></label>
											<input type="email" name= "email" data-rule-required="true" data-rule-email="true" data-msg-email="Please enter valid Email address" data-msg-required="Please enter Email address"  placeholder="Ex: sakib.606@gmail.com" class="input-1" value="<?php echo set_value('email'); ?>" >
											<label for="email" class="error"><?php echo form_error('email'); ?></label>
										</div>
										  
										  <div class="input">
											<label class="required"><?php echo $this->lang->line("user_reg_contact_number"); ?></label>
											<input type="text" name="contact_no" data-rule-required="true"  id="validation_contact_b2c" data-msg-pattern="Please enter valid phone number." placeholder="ex : +98  0728 738-2782" class="input-1" value="<?php echo set_value('contact_no'); ?>">
											<label for="contact_no"><?php echo form_error('contact_no'); ?></label>
										</div>
										
										<div class="input">
											<label class="required"><?php echo $this->lang->line("user_reg_password"); ?></label>
											<input type="password" id="password" name="password" data-rule-minlength="6" data-rule-minlength="Please enter minimum 6 characters." data-rule-required="true" placeholder="<?php echo $this->lang->line("user_reg_password"); ?>" class="input-1" value="<?php echo set_value('password'); ?>" >
											<label for="password" class="error"><?php echo form_error('password'); ?></label>
										</div>
						
										<div class="input">
											<label class="required"><?php echo $this->lang->line("user_reg_confirm_password"); ?></label>
											<input type="password" name="cpassword" data-rule-equalTo="#password" data-msg-equalTo="Re-enter password" data-rule-required="true" placeholder="<?php echo $this->lang->line("user_reg_confirm_password"); ?>" class="input-1" value="<?php echo set_value('cpassword'); ?>" >
											<label for="cpassword" class="error"><?php echo form_error('cpassword'); ?></label>
										</div>
										

										

										<div class="input">
											<div class="submit">
												<input type="submit" value="<?php echo $this->lang->line("user_reg_register_here"); ?>" class="btn-1">
											</div>
										</div>

										<div class="input">
											<div class="alreadyaccount"><?php echo $this->lang->line("user_reg_already_have_account"); ?> <a href="javascript:void(0)" data-target="#myModal" data-toggle="modal" class="sign_color">Sign In</a></div>
										</div>
							
								
									<div class="clearfix"></div>							
								</div>

									<div class="col-md-6 col-sm-12 col-md-offset-1" style="display:none">
										
				                     	<p class="lead">Register now for <span class="text-success">FREE</label></p>
					                    <ul>
											<li><i class="fa fa-check text-success"></i> Print tickets and invoices</li>
											<li><span class="fa fa-check text-success"></label> Access booking history with upcoming trips</li>
											<li><span class="fa fa-check text-success"></label> Save your favorites</li>
											<li><span class="fa fa-check text-success"></label>  Enter your contact details only once</li>
											<li><span class="fa fa-check text-success"></label> Get a gift <small>(only new customers)</small></li>
											<li><span class="fa fa-check text-success"></label>Holiday discounts up to 30% off</li>
					                    </ul>	
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