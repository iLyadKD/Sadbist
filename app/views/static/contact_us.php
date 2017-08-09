<!DOCTYPE html>
<html class="no-js">
	<?php
		$this->load->view("common/head");
	?>
	<body>
		<div id="wrapper">
		<?php
			$this->load->view("common/header");
			$this->load->view("common/notification");
		?>

			<section class="mn-abtus">
			<div class="container">
			<div class="row">
			
				<div class="mn-contact">
					<!--<div class="col-md-6 col-xs-12 nopadding">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3307.000309708779!2d-118.50285509999999!3d34.018203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2a4c41fb9b5fb%3A0xf414ba23e770309e!2s25+California+Ave%2C+Santa+Monica%2C+CA+90403%2C+USA!5e0!3m2!1sen!2sin!4v1443096635473" width="100%" height="550" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
					<div class="col-md-6 col-xs-12 nopadding">
						<div class="layout-left">
							<h2><?php echo $this->lang->line("contact_us"); ?></h2>
							<div class="description">
							</div>
							<div class="mn-main">
							<form action = "javascript:void(0);" class="contact_us" method="post">
								<div class="col-md-12">
								<div class="mn-contact_1">
										<label><?php echo $this->lang->line("name"); ?></label>
										<input name="name" type="text" data-rule-required="true" placeholder="<?php echo $this->lang->line("name"); ?>" class="input-1">
									</div>
									<div class="mn-contact_1">
										<label><?php echo $this->lang->line("email_address"); ?></label>
										<input name="email" class="input-1" type="email"  data-rule-required="true" data-rule-email="true" placeholder="<?php echo $this->lang->line("email_address"); ?>" data-msg-required="<?php echo $this->lang->line("email_address_required"); ?>" data-msg-email="<?php echo $this->lang->line("email_address_valid_error"); ?>">
									</div>
									<div class="mn-contact_1">
										<label><?php echo $this->lang->line("message"); ?></label>
										<textarea  data-rule-required="true" name="message" placeholder="<?php echo $this->lang->line("message"); ?>" class="input-1 mn-height"></textarea>
									</div>
									<div class="mn-contact_1">
										<input type="submit" value="<?php echo $this->lang->line("submit"); ?>" class="btn-2 pull-right mn-tp">
									</div>
								</div>
							</form>
								
							</div>
							
							</div>
							
							<div class="address-wrap clearfix">
								<div class="address-info">
									<ul>
										<li><i class="fa fa-map-marker"></i><?php echo $contact_address !== false ? $contact_address->address : $this->lang->line("na_caps"); ?></li>
										<li><i class="fa fa-phone"></i><?php echo $contact_address !== false ? $contact_address->contact : $this->lang->line("na_caps"); ?></li>
										<li><i class="fa fa-envelope-o"></i><?php echo $contact_address !== false ? $contact_address->email : $this->lang->line("na_caps"); ?></li>
									</ul>
								</div>
								
							</div>
							
						</div>-->

	<?php 

		$this->load->helper('utility_helper');
					
		$details = getStaticPageDetails(28,$this->data["default_language"]);
		

	?>
	<div class="col-md-12 col-xs-12 nopadding">
	<div class="layout-left">
	<h2><?php echo $details[0]; ?></h2>
	<div class="description">
	
	<?php echo $details[1];  ?>
	
	</div></div></div>


					</div>
					</div>
					</div>
			</section>
			<div class="clearfix"></div>
			<?php
				$this->load->view("common/pop-ups");
				$this->load->view("common/footer");
			?>
		</div>
	</body>
	<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>
