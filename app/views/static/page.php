<!DOCTYPE html>
<html class="no-js">
	<?php
		$this->load->view("common/head");
	?>
	<body class="contrast-muted login contrast-background">
		<div id="wrapper">
			<?php
				$this->load->view("common/header");
				$this->load->view("common/notification");

            $this->load->helper('utility_helper');

            $details = getStaticPageDetails(6,$this->data["default_language"]);
			?>
			<section class="mn-abtus">
				<div class="container">
					<div class="row">
						<div class="mn-abt">
							<div class="col-md-12 nopadding">
							
								<h2><?php echo $details[0]; ?></h2>
                                

                                    <?php echo $details[1];  ?>

                                
                                                                



							</div>
						
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
