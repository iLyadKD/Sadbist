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
  <?php $this->load->view("b2c/menu"); ?>
    <div class="clearfix"></div>
      <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="profile-content">
          <div class="col-lg-8 ">
                <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> <?php echo $this->lang->line("conversation"); ?> 
                   
                </div>
                <div class="panel-body">
                   <?php if($ticket) { 
                          foreach ($ticket as $rows) { 
                            if($rows->user_type_from==4){ $type="left";   $type1=""; $op="right";}else {
                               $type="right";   $op=""; $type1="right";
                            }
                      ?>
                <div class="col-md-12" style="margin-bottom:10px">
                  <div class="col-md-2" style="float:<?php echo $type; ?>;">
                  <img src="<?php echo upload_url("/common/images/default.png"); ?>" alt="<?php echo $this->lang->line("user_avatar"); ?>"  width="120" class="img-circle" style="border:1px solid #CCC;padding:10px;" />
                  <p class="text-center"><?php if($rows->user_type_from==4){ echo $this->lang->line("you");} else {  echo $rows->replied_by; }?></p>
                  </div>
                  <div class="col-md-10" style="border:1px solid #CCC;min-height:100px;background:#FFF;padding:10px;">
                  <?php  echo $rows->message;?>
                  <?php if($rows->attachment){ ?>
                  <p><a href="<?php  echo base_url('admin/upload_files/'.$rows->attachment);?>"> <?php echo $this->lang->line("attachment"); ?></a></p>
                  <?php } ?>
                  <br>
                  <small style="color:#CCC"><span class="glyphicon glyphicon-time"></span><?php   echo $rows->replied_on;?></small>
                  </div>
                </div>
                 <?php } }?>
                  <div class="clearfix"></div>
                   
                </div>
                <div class="panel-footer">
                    <form action="<?php echo  base_url('b2c/view_support_ticket/'.$tid);?>" method="post" enctype="multipart/form-data">
                    <div class="col-md-3 ">
                      <input type="file" class="form-control" name="file_name" >
                    </div>
                     <div class="col-md-12 ">
                      <div class="input-group">
                          <input id="btn-input" type="text" name="message" class="form-control" placeholder="<?php echo $this->lang->line("type_msg_here"); ?>" />
                          <span class="input-group-btn">
                              <button class="btn btn-success" id="btn-chat"><?php echo $this->lang->line("send"); ?></button>
                          </span>
                      </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<style>
.panel-primary > .panel-heading {
    color: #fff;
    background-color: #ec4614;
    border-color: #ec4614;
}
.panel-primary {
    border-color: #ec4614;
}

.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}
.panel{
 padding:0px; 

}
.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}


::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

</style>
          </div>
        </div>
      </div>
    </div>
  </section>

<div class="clearfix"></div>
<?php 
  $this->load->view('common/footer');
?>
</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>