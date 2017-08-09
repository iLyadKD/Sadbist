<!DOCTYPE html>
<html class="no-js">
<?php 
  $include['css'][] = "jquery.onoff";
  $this->load->view("common/head",$include);
?>

<style>
.modal-dialog {
    width:350px;
    margin: 30px auto;
}
.modal-content { background: #fff; }
</style>

<body>
<div id="wrapper">
<?php
  $this->load->view("common/header");
  $this->load->view("common/notification");
?> 
<section class="mn-reg">
  <?php $this->load->view("b2c/menu"); ?>
    <div class="clearfix"></div>
      <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="profile-content">
        <div class="col-md-6 col-sm-4"  style="margin-top: 20px;">
        <div class="panel">
		<div class="col-md-12 bg_blur_1 ">
		</div>
        <div class="col-md-12 col-xs-12">
           <img src="<?php echo base_url('assets/images/change.png');?>" class="img-thumbnail picture hidden-xs" />
           <img src="<?php echo base_url('assets/images/change.png');?>" class="img-thumbnail visible-xs picture_mob" />
           <div class="setting_texture">
                <h1><?php echo $this->lang->line("user_settings_change_password"); ?> </h1>
                <h4>You can change your password</h4>
                <span>Update your current 10020 Group Password</span>
                <p><a href=""  data-toggle="modal" data-target="#myModal_1"  class="btn-1 mn-tp"><?php echo $this->lang->line("user_settings_change_password"); ?></a></p>
           </div>
        </div>
    </div>   
    	</div>
	 <div class="col-md-6 col-sm-4" style="margin-top: 20px;">
        <div class="panel">
		<div class="col-md-12 bg_blur ">
		</div>
        <div class="col-md-12 col-xs-12">
           <img src="<?php echo base_url('assets/images/cancel.png');?>" class="img-thumbnail picture hidden-xs" />
           <img src="<?php echo base_url('assets/images/cancel.png');?>" class="img-thumbnail visible-xs picture_mob" />
           <div class="setting_texture">
                <h1><?php echo $this->lang->line("user_settings_cancel_account"); ?> </h1>
                <h4>You can cancel account here</h4>
                <span>Cancel your 10020 Group account.</span>
                <p><a href=""  data-toggle="modal" data-target="#myModal_2"  class="btn-1 mn-tp"><?php echo $this->lang->line("user_settings_cancel_account"); ?></a></p>
           </div>
        </div>
    </div>   
    	</div>
          

          <!--<div class="col-md-4 col-sm-4" style="margin-top: 20px;">
        <div class="panel">
    <div class="col-md-12 bg_step ">
    </div>
        <div class="col-md-12 col-xs-12">
           <img src="<?php echo base_url('assets/images/cancel.png');?>" class="img-thumbnail picture hidden-xs" />
           <img src="<?php echo base_url('assets/images/cancel.png');?>" class="img-thumbnail visible-xs picture_mob" />
           <div class="setting_texture">
                <h1><?php echo $this->lang->line("user_settings_two_step_verification"); ?></h1>
                <h4>You can Verify account here</h4>
                <span>Verify your 10020 Group account.</span>
                <p><a href=""  data-toggle="modal" data-target="#myModal_3"  class="btn-1 mn-tp"><?php echo $this->lang->line("user_settings_settings"); ?></a></p>
           </div>
        </div>
    </div>   
      </div>-->
          
      </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="myModal_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 id="myModalLabel" class="modal-title"><?php echo $this->lang->line("user_settings_change_password"); ?></h4>
      </div>
      <!-- /.modal-header -->
      
            <div class="modal-body">
              <form role="form" method="post" name="change_password"  action="<?php echo base_url('b2c/update_password')?>" id="change_password">  
        
          <div class="form-group">
            <div class="reginput">
              <input type="password" name="current_password" placeholder="<?php echo $this->lang->line("user_settings_current_password"); ?>" class="input-1" >
            </div>
          </div>
          <!-- /.form-group -->
          
          <div class="form-group">
            <div class="reginput">
              <input type="password" name="new_password" id="new_password" placeholder="<?php echo $this->lang->line("user_settings_new_password"); ?>" class="input-1">
            </div>
            <!-- /.input-group --> 
          </div>
          <!-- /.form-group -->
          
          <div class="form-group">
            <div class="reginput">
              <input type="password" name="confirm_password" placeholder="<?php echo $this->lang->line("user_settings_confirm_password"); ?>" class="input-1">
            </div>
            <!-- /.input-group --> 
          </div>
          <!-- /.form-group -->
          <!-- /.checkbox -->
        
      </div>
      <!-- /.modal-body -->
      
      <div class="modal-footer">
        <button class="btn-1 mn-tp" type="submit"><?php echo $this->lang->line("user_settings_update_password"); ?></button>
       
      </div>
      <!-- /.modal-footer --> 
        </form>
    </div>
  </div>
</div>


<div class="modal fade" id="myModal_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <form name="cancel_account" id="cancel_account" method="post" action="<?php echo base_url('user/cancel_account');?>" > 
      <div class="modal-body">
        <div id="confirm_message"></div>
         <div class="form-group">
            <div class="reginput">
              <input type="password" placeholder="<?php echo $this->lang->line("Password");?>" name="acc_password" class="input-1" value="">
            </div>
          </div>
         <p> <?php echo $this->lang->line("cancel_account_description");?></p>
      </div>
      <!-- /.modal-body -->
      
      <div class="modal-footer">
        <input type="submit" class="searchbtn-1 pull" id="confirm" value="<?php echo $this->lang->line("confirm");?>"/>
        <button class="searchbtn-2 pull-right "  data-dismiss="modal"  ><?php echo $this->lang->line("cancel");?></button>
      </div>
      <!-- /.modal-footer --> 
      </form>
      <!-- /.modal-footer --> 
      
    </div>
  </div>
</div>
    <div class="modal fade" id="myModal_3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 id="myModalLabel" class="modal-title"><?php echo $this->lang->line("user_settings_two_step_verification"); ?></h4>
      </div>
      <!-- /.modal-header -->
      
      <div class="modal-body">
        <p> Verify your 10020 Group account.</p>
        <input type="checkbox" checked />
      </div>
      <!-- /.modal-body -->
      
      <div class="modal-footer">
        <button class="searchbtn-1 pull-left btn-1"><?php echo $this->lang->line("user_settings_confirm"); ?></button>
      </div>
      <!-- /.modal-footer --> 
      
    </div>
  </div>
</div>
  </section>
<?php 
  $this->load->view('common/footer');
?>
</div>



</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>