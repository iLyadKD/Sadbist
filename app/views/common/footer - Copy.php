<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 nopadding">
				<div class="col-md-3 col-sm-3 col-xs-12 link_sec">
					<h1><?php echo $this->lang->line("quick_links"); ?></h1>
					<div class="clearfix"></div>
						<div class="links">
							<ul>
								<li><a href="javascript:void(0)">Flight</a></li>
								<li><a href="javascript:void(0)">Hotel</a></li>
								<li><a href="javascript:void(0)">Tours</a></li>
								<li><a href="javascript:void(0)">Flight Deals</a></li>
								<li><a href="javascript:void(0)">Hotel Destinations</a></li>
							</ul>
						</div>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-12 link_sec">
					<h1><?php echo $this->lang->line("traveler_tools"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<ul>
							<li><a href="javascript:void(0)">Arrival & Departures</a></li>
							<li><a href="javascript:void(0)">Airline Directory</a></li>
							<li><a href="javascript:void(0)">Flight Tracking</a></li>
							<li><a href="javascript:void(0)">Flight Timetable</a></li>
							<li><a href="javascript:void(0)">Customer Support</a></li>
							<li><a href="javascript:void(0)">Client Testimonial</a></li>
							<li><a href="javascript:void(0)">FAQS</a></li>
						</ul>
					</div>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12 nopadding_left link_sec">
					<h1><?php echo $this->lang->line("about_hundred_twenty"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<ul>
							<li><a href="javascript:void(0)">About Us</a></li>
							<li><a href="javascript:void(0)">Mission & Vision</a></li>
							<li><a href="javascript:void(0)">Management Team</a></li>
							<li><a href="javascript:void(0)">Company Profile</a></li>
							<li><a href="javascript:void(0)">Loyality Program</a></li>
						</ul>
					</div>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12 nopadding_left link_sec">
					<h1><?php echo $this->lang->line("legal"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<ul>
							<li><a href="javascript:void(0)">Privacy Policy</a></li>
							<li><a href="javascript:void(0)">Terms & Conditions</a></li>
							<li><a href="javascript:void(0)">Taxes & Fees</a></li>
							<li><a href="javascript:void(0)">Fare Rules</a></li>
							<li><a href="javascript:void(0)">User Agreement</a></li>
						</ul>
					</div>
				</div>
				
				
				<div class="col-md-2 col-sm-2 col-xs-12 nopadding_left link_sec">
					<h1><?php echo $this->lang->line("follow_us"); ?></h1>
					<div class="clearfix"></div>
					<div class="links">
						<div class="fsocial">
							<a href="javascript:void(0)"><img src="<?php echo asset_url('images/facebook.png'); ?>" alt="" /></a>
							<a href="javascript:void(0)"><img src="<?php echo asset_url('images/twitter.png'); ?>" alt="" /></a>
							<a href="javascript:void(0)"><img src="<?php echo asset_url('images/pinterest.png'); ?>" alt="" /></a>
							<a href="javascript:void(0)"><img src="<?php echo asset_url('images/in.png'); ?>" alt="" /></a>
							<a href="javascript:void(0)"><img src="<?php echo asset_url('images/googleplus.png'); ?>" alt="" /></a>
							<a href="javascript:void(0)"><img src="<?php echo asset_url('images/youtube.png'); ?>" alt="" /></a>
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
				Copyright &copy; <?php echo DATE("Y"); ?> <span><?php echo PROJECT_NAME ?></span> All rights reserved</div>
			<div class="clearfix"></div>
		</div>
	</div>

	

	<div class="clearfix"></div>         
</div>
<div class="clearfix"></div>
</div>
<?php
if(isset($js) && is_array($js))
			foreach ($js as $include)
				echo "<script src='".asset_url("js/".$include)."'></script>\n";
?>


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