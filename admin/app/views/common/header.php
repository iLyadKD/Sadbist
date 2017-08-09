<head base-url="<?php echo base_url(); ?>" upload-url="<?php echo upload_url(); ?>" asset-url="<?php echo asset_url(); ?>" front-url="<?php echo substr_replace(base_url(), '', -6); ?>" controller="<?php echo $this->data['controller']; ?>" method="<?php echo $this->data['method']; ?>" default-ext="<?php echo DEFAULT_EXT; ?>" b2b-user-type="<?php echo B2B_USER; ?>">
	<title><?php echo isset($this->data["page_title"]) ? $this->data["page_title"]." - ".(isset($this->data["admin_email"]) ? $this->data["admin_email"]." - ".PROJECT_NAME : PROJECT_NAME) : (isset($this->data["admin_email"]) ? $this->data["admin_email"]." - ".PROJECT_NAME : PROJECT_NAME); ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" >
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="<?php echo base_url('favicon.ico');?>" rel="shortcut icon" type="image/x-icon" >

	<?php
		if(isset($css) && is_array($css))
			foreach ($css as $stylesheet)
				echo "<link href='".asset_url(CSS_PATH.$this->data['default_language'].DIRECTORY_SEPARATOR.$stylesheet.".css")."' media='all' rel='stylesheet' type='text/css'>\n";
	?>
	<?php
		if(isset($ext_css) && is_array($ext_css))
			foreach ($ext_css as $stylesheet)
				echo "<link href='$stylesheet' media='all' rel='stylesheet' type='text/css'>\n";
	?>
</head>