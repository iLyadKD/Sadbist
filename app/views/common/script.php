<?php
	$controller = $this->router->fetch_class();
	$action = $this->router->fetch_method();

?>
<!--<script type="text/javascript"> var base_url = "<?php echo base_url();?>";</script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery-1.11.0.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/select2/select2.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery-ui.js');?>"></script> 
<script type="text/javascript" src="<?php echo asset_url('js/validate/jquery.validate.min.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jssor.slider.mini.js');?>"></script>



<?php if(isset($js) && $js) {
	foreach($js as $script) {
		echo '<script type="text/javascript"  src="'.asset_url($script).'"></script>'."\n";
	} 
} 
?>-->
