<head data-id="<?php if(isset($this->session->userdata['10020.ir_front_id'])) echo $this->session->userdata['10020.ir_front_id'];?>" data-base-url="<?php echo base_url(); ?>" data-language="<?php echo $this->data['default_language']; ?>" asset-url="<?php echo asset_url(); ?>" front-url="<?php echo substr_replace(base_url(), '', -6); ?>" controller="<?php echo $this->data['controller']; ?>" method="<?php echo $this->data['method']; ?>" data-file-ext="<?php echo DEFAULT_EXT; ?>" data-controller="<?php echo $this->data['controller']; ?>" data-method="<?php echo $this->data['method']; ?>" data-hash="<?php echo isset($hash) ? $hash : ''; ?>">
	<title><?php echo isset($this->data["page_title"]) ? $this->data["page_title"]." - ".(isset($this->data["user_email"]) ? $this->data["user_email"]." - ".PROJECT_NAME : PROJECT_NAME) : (isset($this->data["user_email"]) ? $this->data["user_email"]." - ".PROJECT_NAME : PROJECT_NAME); ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" >
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="<?php echo base_url('favicon.ico');?>" rel="shortcut icon" type="image/x-icon" >
	<?php

		$default_css[] = "style_".$this->data["default_language"];
		$default_css[] = "bootstrap_".$this->data["default_language"];
		$default_css[] = "bootstrap_rtl_".$this->data["default_language"];
		$default_css[] = "dashboard_".$this->data["default_language"];
		$default_css[] = "custom_".$this->data["default_language"];
		$default_css[] = "jquery-ui_".$this->data["default_language"];
		$default_css[] = "font-awesome";
		$default_css[] = "persian_fonts";
		$default_css[] = "persian_calendar";
		if(isset($default_css) && is_array($default_css))
			foreach ($default_css as $stylesheet)
				echo "<link href='".asset_url("css/".$stylesheet.".css")."' media='all' rel='stylesheet' type='text/css'>\n";
		if(isset($css) && is_array($css))
			foreach ($css as $stylesheet)
				echo "<link href='".asset_url("css/".$stylesheet.".css")."' media='all' rel='stylesheet' type='text/css'>\n";
		if(isset($ext_css) && is_array($ext_css))
			foreach ($ext_css as $stylesheet)
				echo "<link href='$stylesheet' media='all' rel='stylesheet' type='text/css'>\n";
	?>

	        <?php if($_SESSION['default_language']=='en'){ ?>

			 <input type="hidden" value="1" id='lang_id'/>

			<?php } else { ?>

			
			 <input type="hidden" value="2" id='lang_id'/>

			<?php } ?>
</head>