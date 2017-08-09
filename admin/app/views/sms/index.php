<!DOCTYPE html>
<html>
  <?php
    $include["css"][] = "bootstrap/bootstrap";
    $include["css"][] = "light-theme";
    $include["css"][] = "datatables/jquery.dataTables.min";
    $include["css"][] = "datatables/dataTables.tableTools.min";
    $this->load->view("common/header", $include);
  ?>

  <body class="fixed-header <?php echo get_menu_status() === '1' ? 'main-nav-closed' : 'main-nav-opened'; ?>">
    <?php $this->load->view("header");?>
    <div class="body-wrapper">
      <div class="main-nav-bg"></div>
      <?php $this->load->view("side-menu");?>
      
      <section class="body-content">
        <div class="container">
          <div class="row" class="body-content-wrapper">
            <div class="col-xs-12">
              <div class="row">
                <div class="col-sm-12">
                  <div class="page-header">
                    <h1 class="pull-left">
                      <i class="icon-th"></i>
                      <span><?php echo $this->data["page_title"]; ?></span>
                    </h1>
                    <div class="pull-right">
                      <ul class="breadcrumb">
                        <li>
                          <a href="<?php echo base_url(); ?>">
                            <i class="icon-bar-chart"></i>
                          </a>
                        </li>
                        <li class="separator">
                          <i class="icon-angle-right"></i>
                        </li>
                        <li class="active"><?php echo $this->data["page_title"]; ?></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-sm-12">
                  <div class="box"><div class="notification"><?php $this->load->view("common/notification"); ?></div></div>
                  <div class="box bordered-box orange-border" style="margin-bottom:0;">
                    <div class="box-header blue-background">
                      <div class="title"><?php echo $this->data["page_title"]; ?></div>
                      <div class="actions">
                        <a class="btn" href="<?php echo base_url($this->data['controller'].'/add'.DEFAULT_EXT); ?>"><i class="icon-plus-sign"></i> Add New Template</a>
                      </div>
                    </div>
                    <div class="box-content box-no-padding">
                      <div class="responsive-table">
                        <div class="scrollable-area">
                          <table id="sample_1" class='table table-bordered table-striped datatable' style='margin-bottom:0;'>
                                    <thead>
                                      <tr>
                                        <th style="width: 10%;">S.No</th>
                                        <th style="width: 40%;">SMS Type</th>
                                        <th width="100">Content</th>
                                        <th width="100">Status</th>
                                        <th width="100">Actions</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php if(!empty($result)): $c=1;foreach($result as $key=>$row):?>
                                        <tr>
                                          <td><?php echo ($key+1);?></td>
                                          <td><?php echo $row->sms_type; ?></td>
                                          <td><?php echo $row->sms_content; ?></td>
                                          <td>
                                            <div class="toggle-switch"><input type="checkbox" <?php echo ($row->sms_status == 1) ? 'checked' : '' ;?> class="toggle-control sms_status" data-href="<?php echo $row->sms_id; ?>"><label for="" class="toggle-checkbox toggle-check-cross"></label></div>
                                          </td>
                                          <td>
                                            <a class="btn btn-danger btn-xs has-tooltip delete_sms_template" data-placement="top" data-id="<?php echo $row->sms_id; ?>" href="javascript:void(0);" data-original-title="Delete"><i class="icon-remove"></i></a>
                                          </td>
                                        </tr>
                                      <?php endforeach;endif;?>
                                      </tbody>
                                    </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php $this->load->view("footer");?>
        </div>
      </section>
    </div>
    <?php $this->load->view("common/scripts");?>
  </body>
</html>