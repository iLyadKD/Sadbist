<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 nopadding">
				<div class="col-md-3 col-sm-3 col-xs-12 link_sec">
					<h1><?php echo $this->lang->line("quick_links"); ?></h1>
					<div class="clearfix"></div>
						<div class="links">
							<ul>
								<?php
								$ql_pages = $this->Common_model->get_active_quick_link_pages($this->data["default_language"]);
								if($ql_pages !== false)
									foreach ($ql_pages as $ql_page)
										echo "<li><a href='".base_url($ql_page->slug)."'>".$ql_page->title."</a></li>";
							?>
							</ul>
						</div>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-12 link_sec">
					<h1><?php echo $this->lang->line("traveler_tools"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<ul>
							<?php
								$tt_pages = $this->Common_model->get_active_traveller_tool_pages($this->data["default_language"]);
								if($tt_pages !== false)
									foreach ($tt_pages as $tt_page)
										echo "<li><a href='".base_url($tt_page->slug)."'>".$tt_page->title."</a></li>";
							?>
						</ul>
					</div>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12 nopadding_left link_sec">
					<h1><?php echo $this->lang->line("about_company"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<ul>
							<?php
								$about_ht_pages = $this->Common_model->get_active_about_10020_pages($this->data["default_language"]);
								if($about_ht_pages !== false)
									foreach ($about_ht_pages as $about_ht_page)
										echo "<li><a href='".base_url($about_ht_page->slug)."'>".$about_ht_page->title."</a></li>";
							?>
						</ul>
					</div>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12 nopadding_left link_sec">
					<h1><?php echo $this->lang->line("legal"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<ul>
							<?php
								$legal_pages = $this->Common_model->get_active_legal_pages($this->data["default_language"]);
								if($legal_pages !== false)
									foreach ($legal_pages as $legal_page)
										echo "<li><a href='".base_url($legal_page->slug)."'>".$legal_page->title."</a></li>";
							?>
						</ul>
					</div>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12 nopadding_left link_sec">
					<h1><?php echo $this->lang->line("follow_us"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<!-- <div class="fsocial">
							<?php
								$social_medias = $this->Common_model->get_active_social_media();
								if($social_medias !== false)
									foreach ($social_medias as $social_media)
										echo '<a class="media_icons" href="'.$social_media->url.'" alt="'.$social_media->name.'"><i class="fa fa-'.$social_media->icon.'"></i></a>';
							?>
						</div> -->
						<div class="component1">
							<a target="_blank" href="https://www.facebook.com/sadbist.sadbist" class="icon icon-mono facebook"><img src="<?php echo asset_url('images/facebook.png'); ?>"></a>
							<a target="_blank" href="https://www.linkedin.com/in/travel-sadbist-4b4863138
		" class="icon icon-mono linkedin"><img src="<?php echo asset_url('images/Linkedin.png'); ?>"></a>
							<a target="_blank" href="https://plus.google.com/u/0/
		" class="icon icon-mono googleplus"><img src="<?php echo asset_url('images/google.png'); ?>"></a>
							<a target="_blank" href="https://www.instagram.com/travel10020
		" class="icon icon-mono instagram"><img src="<?php echo asset_url('images/Instagram.png'); ?>"></a>
							<a target="_blank" href="https://telegram.me/travell10020
		" class="icon icon-mono telegram"><img src="<?php echo asset_url('images/Telegram.png'); ?>"></a>
							<a target="_blank" href="https://secure.skype.com/portal/overview
		" class="icon icon-mono skype"><img src="<?php echo asset_url('images/skype.png'); ?>"></a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="clearfix"></div>   
	
	<div class="payment">
		<div class="container">
			<div class="powered">
				<?php echo $this->lang->line("copyright"); ?> &copy; <?php echo DATE("Y"); ?> <span><?php echo PROJECT_NAME ?></span> <?php echo $this->lang->line("all_right_reserved"); ?></div>
			<div class="clearfix"></div>
		</div>
	</div>

	

	<div class="clearfix"></div>         
</div>
<div class="clearfix"></div>
</div>

<script type="text/javascript">
	// All language related variable which are used in javascript mention here
	// use prefix "lang_lib_" to dintinguish between other script variables
	var lang_lib_child_age = "<?php echo $this->lang->line("child_age"); ?>";
	var lang_lib_room = "<?php echo $this->lang->line("room"); ?>";
	var lang_lib_session_expired = "<?php echo $this->lang->line("session_expired"); ?>";
</script>
<?php if(isset($google_analytics) && is_array($google_analytics))
{
	$userId = isset($this->data["user_id"]) ? $this->data["user_type"]."||".$this->data["user_id"] : "Guest";
?>
<!-- Google Analytics -->

<script>

// Instructs analytics.js to use the name `analytics`.
window.GoogleAnalyticsObject = "analytics";

// Creates an initial analytics() function.
// The queued commands will be executed once analytics.js loads.
window.analytics = window.analytics || function() {
  (analytics.q = analytics.q || []).push(arguments)
};

analytics.l = +new Date;
// Creates a default tracker with automatic cookie domain configuration.
<?php 
foreach ($google_analytics as $analytic)
{
	echo 'analytics("create", "'.$analytic->tracker_id.'", "auto", "'.$analytic->tracker_name.'", {
  				userId: "'.$userId.'"
		});';
	echo "// Sends a pageview hit from the tracker just created.";
	echo 'analytics("'.$analytic->tracker_name.'.send", "'.$analytic->tracker_type.'");';
}
?>
</script>
<?php } ?>