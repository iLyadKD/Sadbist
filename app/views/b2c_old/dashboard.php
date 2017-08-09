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
  <section class="mn-reg">
    <div class="dash">
      <div class="col-md-12">
        <div class="col-sm-4 col-md-4 user-details col-md-offset-4 col-sm-offset-4  col-xs-offset-0">
          <div class="mn_user_de user-info-block ">
            <div class="user-heading">
              <h3><?php echo $user->firstname; ?> <?php echo $user->lastname; ?></h3>
              <h3><?php echo $user->email_id; ?></h3>
              <span class="help-block"><?php if($user->city != '') echo $user->city.','; ?> <?php echo $user->country; ?></span> </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12 nopadding">
          <div class="col-md-6">
            <div class="mn-profile">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile"></div>
                  <h3><?php echo $this->lang->line("user_dashboard_my_account"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6"> <a href="<?php echo base_url('b2c/edit_profile/0');?>">
                  <h6><i class="fa fa-user"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_my_profile"); ?></h4>
                  </h6>
                  </a> </div>
                <div class="col-md-6"> <a href="<?php echo base_url('b2c/edit_profile/1');?>">
                  <h6><i class="fa fa-edit"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_edit_profile"); ?></h4>
                  </h6>
                  </a> </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mn-settings">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile b2c_setting"></div>
                  <h3><?php echo $this->lang->line("user_dashboard_settings"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6"> <a href="b2c/settings.html">
                  <h6><i class="fa fa-lock"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_change_password"); ?> </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-6"> <a href="b2c/settings.html">
                  <h6><i class="fa fa-times"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_cancel_account"); ?> </h4>
                  </h6>
                  </a> </div>
                

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mn-booking">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile b2c_booking"></div>
                  <h3><?php echo $this->lang->line("user_dashboard_booking_report"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-4"> <a href="b2c/booking_report.html">
                  <h6><i class="fa fa-plane"></i> <br/>
                    <h4> <?php echo $this->lang->line("user_dashboard_flighs"); ?></h4>
                  </h6>
                  </a> </div>
                <div class="col-md-4"> <a href="b2c/booking_report.html">
                  <h6><i class="fa fa-hotel"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_hotels"); ?>  </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-4"> <a href="b2c/booking_report.html">
                  <h6><i class="fa fa-map-marker"></i> <br/>
                    <h4> <?php echo $this->lang->line("user_dashboard_tours"); ?></h4>
                  </h6>
                  </a> </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mn-support">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile b2c_ticket"></div>
                  <h3><?php echo $this->lang->line("user_dashboard_support_ticket"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-3 nopad_5"> <a href="<?php echo base_url('b2c/support_ticket.html')?>">
                  <h6><i class="fa fa-inbox"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_inbox"); ?> </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-3 nopad_5"> <a href="b2c/support_ticket.html">
                  <h6><i class="fa fa-arrow-right"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_sent_items"); ?>  </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-3 nopad_5"> <a href="b2c/support_ticket.html">
                  <h6><i class="fa fa-close"></i> <br/>
                    <h4> <?php echo $this->lang->line("user_dashboard_closed_tickets"); ?></h4>
                  </h6>
                  </a> </div>
                <div class="col-md-3 nopad_5"> <a href="b2c/support_ticket.html">
                  <h6><i class="fa fa-check"></i> <br/>
                    <h4><?php echo $this->lang->line("user_dashboard_new_tickets"); ?></h4>
                  </h6>
                  </a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="clearfix"></div>
  <?php   $this->load->view('common/footer');?>
</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>