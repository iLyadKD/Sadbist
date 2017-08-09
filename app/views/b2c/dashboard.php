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
  <style>
    label.error {
          color: RED;
    font-variant: normal;
    font-size: 9px;
    font-weight: 200;
    }
    .inputs_group{
      font-size: 9px !important;
    }
  </style>
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
    <div class="container dashboard_b2c">
      <input type="hidden" id="count_companion" value="<?php echo $companions;?>">
      <div class="row">
        <div class="col-md-12 nopadding">
          <div class="col-md-6">
            <div class="mn-profile">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile"></div>
                  <h3><?php echo $this->lang->line("my_account"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-4"> <a href="<?php echo base_url('b2c/edit_profile/0');?>">
                  <h6><i class="fa fa-user"></i> <br/>
                    <h4><?php echo $this->lang->line("my_profile"); ?></h4>
                  </h6>
                  </a> </div>
                <div class="col-md-4"> <a href="<?php echo base_url('b2c/edit_profile/1');?>">
                  <h6><i class="fa fa-edit"></i> <br/>
                    <h4><?php echo $this->lang->line("edit_profile"); ?></h4>
                  </h6>
                  </a>
                </div>
                <div class="col-md-4 companion"> <a href="javascript:void(0)">
                  <h6><i class="fa fa-user-plus"></i> <br/>
                    <h4>Add companion</h4>
                  </h6>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mn-settings">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile b2c_setting"></div>
                  <h3><?php echo $this->lang->line("settings"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6"> <a href="b2c/settings.html">
                  <h6><i class="fa fa-lock"></i> <br/>
                    <h4><?php echo $this->lang->line("change_password"); ?> </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-6"> <a href="b2c/settings.html">
                  <h6><i class="fa fa-times"></i> <br/>
                    <h4><?php echo $this->lang->line("cancel_account"); ?> </h4>
                  </h6>
                  </a> </div>

                 <!-- <div class="col-md-4"> <a href="b2c/settings.html">
                  <h6><i class="fa fa-check-square"></i> <br/>
                    <h4><?php echo $this->lang->line("two_step_verification"); ?></h4>
                  </h6>
                  </a> </div>
 -->
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mn-booking">
              <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <div class="triangle-right">
                  <div class="b2c_profile b2c_booking"></div>
                  <h3><?php echo $this->lang->line("booking_reports"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-4"> <a href="b2c/booking_report.html">
                  <h6><i class="fa fa-plane"></i> <br/>
                    <h4> <?php echo $this->lang->line("flights"); ?></h4>
                  </h6>
                  </a> </div>
                <div class="col-md-4"> <a href="b2c/booking_report.html">
                  <h6><i class="fa fa-hotel"></i> <br/>
                    <h4><?php echo $this->lang->line("hotels"); ?>  </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-4"> <a href="b2c/booking_report.html">
                  <h6><i class="fa fa-map-marker"></i> <br/>
                    <h4> <?php echo $this->lang->line("tours"); ?></h4>
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
                  <h3><?php echo $this->lang->line("support_ticket"); ?></h3>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-3 nopad_5"> <a href="<?php echo base_url('b2c/support_ticket.html')?>">
                  <h6><i class="fa fa-inbox"></i> <br/>
                    <h4><?php echo $this->lang->line("inbox_support_ticket"); ?> </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-3 nopad_5"> <a href="b2c/support_ticket.html">
                  <h6><i class="fa fa-arrow-right"></i> <br/>
                    <h4><?php echo $this->lang->line("sent_support_ticket"); ?>  </h4>
                  </h6>
                  </a> </div>
                <div class="col-md-3 nopad_5"> <a href="b2c/support_ticket.html">
                  <h6><i class="fa fa-close"></i> <br/>
                    <h4> <?php echo $this->lang->line("closed_support_ticket"); ?></h4>
                  </h6>
                  </a> </div>
                <div class="col-md-3 nopad_5"> <a href="b2c/support_ticket.html">
                  <h6><i class="fa fa-check"></i> <br/>
                    <h4><?php echo $this->lang->line("new_support_ticket"); ?></h4>
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
  <?php
  $this->load->view('common/footer');
  $this->load->view('common/pop-ups');
  
  ?>
</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>