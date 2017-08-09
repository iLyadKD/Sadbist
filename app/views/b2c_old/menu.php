<div class="dash">
        <div class="col-md-12">
          <div class="fancy-title text-center uppercase">
            <h6 class="text-alpha text-air"><?php echo $this->lang->line("user_profile_my_profile"); ?></h6>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="container">
        <div class="col-md-12 nopadding">
          <div class="col-md-8">
            <div class="col-xs-12 col-sm-6 mn_nopadding_left">
              <ul class="event-list">
                <li> <img alt="My Profile" src="<?php echo base_url('assets/images/profile.jpg'); ?>" />
                  <div class="info">
                    <h2 class="title"><a href="<?php echo base_url('b2c'); ?>"><?php echo $this->lang->line("user_profile_my_profile"); ?></a></h2>
                  </div>
                </li>
                <li> <img alt="Booking Report" src="<?php echo base_url('assets/images/booking.jpg');?>" />
                  <div class="info">
                    <h2 class="title"><a href="<?php echo base_url('b2c/booking_report'); ?>"><?php echo $this->lang->line("user_profile_booking_report"); ?></a></h2>
                  </div>
                </li>
              </ul>
            </div>
            <div class="col-xs-12 col-sm-6 mn_nopadding_right">
              <ul class="event-list">
                <li> <img alt="Settings" src="<?php echo base_url('assets/images/setting.jpg');?>" />
                  <div class="info">
                    <h2 class="title"><a href="<?php echo base_url('b2c/settings'); ?>"><?php echo $this->lang->line("user_profile_settings"); ?></a></h2>
                  </div>
                </li>
                <li> <img alt="Support Ticket" src="<?php echo base_url('assets/images/support_ticket.jpg');?>" />
                  <div class="info">
                    <h2 class="title"><a href="<?php echo base_url('b2c/support_ticket'); ?>"><?php echo $this->lang->line("user_profile_support_ticket"); ?></a></h2>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md-4 user-details">
            <a href=""><div class="user-image">

            <?php  $img_url =  "admin2.png"; ?>
            <img src="<?php echo base_url('assets/images/'.$img_url);?>" alt="<?php echo $user->firstname; ?>" title="<?php echo $user->firstname; ?>" class="img-circle">

             </div>
            <div class="user-info-block">
              <div class="user-heading">
                <h3><?php echo $user->firstname; ?> <?php echo $user->lastname; ?> </h3>
                  <h3><?php echo $user->email_id; ?></h3>
                <span class="help-block"> <?php echo $user->country; ?></span> </div>
            </div></a>
          </div>
        </div>
        </div>
   </div> 