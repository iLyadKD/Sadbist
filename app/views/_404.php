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
			?>
			<div class="container">
				<div class="row mg-tb-20-px">
					<div class="col-md-6 col-md-offset-3 white-text">
						<p class="text-hero">404</p>
						<h1 class="mn-404"><?php echo $this->lang->line("page_not_found"); ?></h1>
						<p class="mg-tb-10-px"><?php echo $this->lang->line("page_not_found_description"); ?></p>
						<p class="mg-tb-20-px"><a href="<?php echo base_url("hotels".DEFAULT_EXT) ?>" class="btn-1"><?php echo $this->lang->line("homepage"); ?> <i class="fa fa-long-arrow-right"></i></a></p>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
				$this->load->view("common/pop-ups");
				$this->load->view("common/footer");
			?>
		</div>
	</body>
<script type="text/javascript" data-main="<?php echo base_url(JS_PATH.'config'); ?>" src="<?php echo base_url(JS_PATH.'require.js'); ?>"></script>
</html>