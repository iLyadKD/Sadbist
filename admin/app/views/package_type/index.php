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
                        <a class="btn" href="<?php echo base_url($this->data['controller'].'/add'.DEFAULT_EXT); ?>"><i class="icon-plus-sign"></i> Add New Tour Type</a>
                      </div>
                    </div>
                    <div class="box-content box-no-padding">
                      <div class="responsive-table">
                        <div class="scrollable-area">
                          <table id="sample_1" class='table table-bordered table-striped datatable' style='margin-bottom:0;'>
                                    <thead>
                                      <tr>
                                        <th style="width: 10%;">S.No</th>
                                        <th style="width: 40%;">Tour Type</th>
                                        <th width="100">Actions</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php if(!empty($result)){ $c=1;foreach($result as $rows){?>
                                      <tr>
                                        <td><?php echo $c;?></td>
                                        
                                        <td><?php  echo $rows->package_type;?></td>
                                                           
                                        <td>
                                          <div class='text-right'>
                                        <a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='<?php echo base_url(); ?>package_type/edit/<?php echo $rows->type_id;?>'>
                                              <i class='icon-edit'></i> </a>
                                            <a class='btn btn-primary btn-xs has-tooltip' onclick="return confirm('Are you sure to delete')" data-placement='top' title='Delete' onclick="return confirm('Are you sure, do you want to delete this record?');" href='<?php echo base_url(); ?>package_type/delete/<?php echo $rows->type_id;?>'>
                                              <i class='icon-remove'></i>
                                            </a>
                                          
                                          </div>
                                        </td>
                                      </tr>
                                      <?php $c++;}}?>
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