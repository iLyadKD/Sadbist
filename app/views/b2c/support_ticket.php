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
 <!--  <?php $this->load->view("b2c/menu"); ?> -->
    <div class="clearfix"></div>
      <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="profile-content">
            <div class="col-md-12 nopadding">
              <div class="mn-edit">
                <div class="col-lg-12">
                  <section id="section-3">
                    <ul class="tabs1">
                      <li><a href='#tab1'><i class="fa fa-inbox"></i> <?php echo $this->lang->line("inbox_support_ticket"); ?></a></li>
                      <li><a href='#tab2'><i class="fa fa-arrow-right"></i> <?php echo $this->lang->line("sent_support_ticket"); ?></a></li>
                      <li><a href='#tab3'><i class="fa fa-close"></i> <?php echo $this->lang->line("closed_support_ticket"); ?></a></li>
                      <li><a href="#tab4"><i class="fa fa-check"></i> <?php echo $this->lang->line("new_support_ticket"); ?></a></li>
                    </ul>
                    <div id='tab1'>
                      <div class="booking_deposit">
                        <div class="tab-pane active" id="basic">
                          <table class="table table-striped dataTable table-bordered">
                            <thead>
                              <tr style="background:#6A9E20; color:#fff">
                                <th><?php echo $this->lang->line("support_ticket_id"); ?></th>
                                <th><?php echo $this->lang->line("date"); ?></th>
                                <th><?php echo $this->lang->line("subject"); ?></th>
                                <th><?php echo $this->lang->line("replied_by"); ?></th>
                                <th><?php echo $this->lang->line("action"); ?></th>
                              </tr>
                            </thead>
                            <tbody>
                             <?php if (!empty($inbox)) {
                                
                                foreach ($inbox as $inbox_rows) { ?>
                              <tr>
                             
                                <td><?php echo $inbox_rows->support_id; ?></td>
                                <td><?php echo $inbox_rows->created; ?></td>
                                <td><?php echo $inbox_rows->subject; ?></td>
                                <td><?php echo $inbox_rows->last_reply; ?></td>
                                <td>
                                <div class="btn-group"> 
                                  <a class="btn btn-mini tip" href="<?php echo base_url('b2c/view_support_ticket/'.$inbox_rows->id);?>" data-original-title="<?php echo $this->lang->line("view"); ?>"><img alt="" src="<?php echo base_url('assets/images/mail-open.png');?>"></a>
                                  <a class="btn btn-mini tip" href="<?php echo base_url('b2c/delete_support_ticket'); ?>" data-original-title="<?php echo $this->lang->line("close"); ?>"><img alt="" src="<?php echo base_url('assets/images/cross.png');?>"></a>
                                </div>
                                 </td>
                                
                              </tr>
                               <?php }} else {
                                echo "<tr><td colspan='6'>".$this->lang->line("no_record_found_caps")."</td></tr>";
                                } ?>

                            </tbody>
                          </table>
                        </div>
                        
                      </div>
                    </div>
                    <div id='tab2'>
                      <div class="booking_deposit"> 
                      	<div class="tab-pane" id="basic">
                          <table class="table table-striped dataTable table-bordered">
                            <thead>
                              <tr style="background:#6A9E20; color:#fff">
                                <th><?php echo $this->lang->line("support_ticket_id"); ?></th>
                                <th><?php echo $this->lang->line("date"); ?></th>
                                <th><?php echo $this->lang->line("subject"); ?></th>
                                <th><?php echo $this->lang->line("replied_by"); ?></th>
                                <th><?php echo $this->lang->line("action"); ?></th>
                              </tr>
                            </thead>
                            <tbody>
                                 <?php if (!empty($send_item)) {
                                
                                foreach ($send_item as $send_item_rows) { ?>
                              <tr>
                              
                                <td><?php echo $send_item_rows->support_id; ?></td>
                                <td><?php echo $send_item_rows->created; ?></td>
                                <td><?php echo $send_item_rows->subject; ?></td>
                                <td><?php echo $send_item_rows->last_reply; ?></td>
                                <td>
                                  <div class="btn-group">
                                  <a class="btn btn-mini tip" href="<?php echo base_url('b2c/view_support_ticket/'.$send_item_rows->id);?>" data-original-title="View Ticketes"><img alt="" src="<?php echo base_url('assets/images/mail-open.png');?>"></a>
                                  <a class="btn btn-mini tip" href="#" data-original-title="<?php echo $this->lang->line("close"); ?>"><img alt="" src="<?php echo base_url('assets/images/cross.png');?>"></a>
                                  </div>
                                </td>
                              
                              </tr>

                                
                              <?php }} else {
                                echo "<tr><td colspan='6'>".$this->lang->line("no_record_found_caps")."</td></tr>";
                                } ?>

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div id='tab3'>
                      <div class="booking_deposit"> 
                      	<div class="tab-pane" id="basic">
                          <table class="table table-striped dataTable table-bordered">
                            <thead>
                              <tr style="background:#6A9E20; color:#fff">
                                <th><?php echo $this->lang->line("support_ticket_id"); ?></th>
                                <th><?php echo $this->lang->line("date"); ?></th>
                                <th><?php echo $this->lang->line("subject"); ?></th>
                                <th><?php echo $this->lang->line("replied_by"); ?></th>
                                <th><?php echo $this->lang->line("action"); ?></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($closed_ticket)) {
                                
                                foreach ($closed_ticket as $closed_ticket_rows) { ?>
                              <tr>
                              
                                <td><?php echo $closed_ticket_rows->support_id; ?></td>
                                <td><?php echo $closed_ticket_rows->created; ?></td>
                                <td><?php echo $closed_ticket_rows->subject; ?></td>
                                <td><?php echo $closed_ticket_rows->last_reply; ?></td>
                                <td>
                                 <div class="btn-group">
                                  <a class="btn btn-mini tip" href="<?php echo base_url('b2c/view_support_ticket/'.$closed_ticket_rows->id);?>" data-original-title="<?php echo $this->lang->line("view"); ?>"><img alt="" src="<?php echo base_url('assets/images/mail-open.png');?>"></a>
                                  <a class="btn btn-mini tip" href="#" data-original-title="<?php echo $this->lang->line("delete"); ?>"><img alt="" src="<?php echo base_url('assets/images/cross.png');?>"></a>
                                  </div>
                                </td>
                              
                              </tr>

                                  <?php }} else {
                                echo "<tr><td colspan='6'>".$this->lang->line("no_record_found_caps")."</td></tr>";
                                } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div id='tab4'>
                      <div class="booking_deposit"> 
                      	<div class="col-md-12 nopadding">
                                <div class="withedrow">
                                <div class="rowit chngecolr">
                                    <form enctype="multipart/form-data" method="POST" action="">
                                      <div class="likrticktsec">
                                        <div class="cobldo"><?php echo $this->lang->line("subject"); ?></div>
                                        <div class="coltcnt">
                                          <select class="input-1" name="subject">
                                          <option value=""><?php echo $this->lang->line("select_subject"); ?></option>
                                          <?php if($support_subject){
                                            foreach ($support_subject as $rows) { ?>
                                            <option value="<?php echo $rows->id?>"><?php echo $rows->subject?></option>
                                            <?php  } } ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="likrticktsec">
                                        <div class="cobldo"><?php echo $this->lang->line("attachment"); ?></div>
                                        <div class="coltcnt">
                                          <input type="file" id="" name="file_name" class="input-1">
                                        </div>
                                      </div>
                                      <div class="likrticktsec">
                                        <div class="cobldo"><?php echo $this->lang->line("message"); ?></div>
                                        <div class="coltcnt">
                                          <textarea class="tikttext input-1" name="message"></textarea>
                                        </div>
                                      </div>
                                      <div class="likrticktsec">
                                        <div class="cobldo">&nbsp;</div>
                                        <div class="coltcnt">
                                          <input type="submit" value="<?php echo $this->lang->line("add_support_ticket"); ?>" class="btn-2 pull-right">
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      </div>
                    </div>
                  </section>
                </div>
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
?>
</body>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
</html>