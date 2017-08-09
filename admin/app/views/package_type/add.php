<!DOCTYPE html>
<html>
  <?php
    $include["css"][] = "bootstrap/bootstrap";
    $include["css"][] = "light-theme";
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
                        <li>
                          <a href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><?php echo $this->data["page_main_title"]; ?>
                          </a>
                        </li>
                        <li class="active"><?php echo $this->data["page_title"]; ?></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="box"></div><div class="notification"></div></div>
                  <div class="box">
                    <div class="box-header blue-background">
                      <div class="title"><?php echo $this->data["page_title"]; ?></div>
                    </div>

                    <div class="box-content">
                      <form class="form form-horizontal package_type" style="margin-bottom: 0;" action="" method="post">
               
                              
                           <div class="form-group">
                            <label class="control-label col-sm-3" for="label">Tour Type</label>
                            <div class="col-sm-4 controls">
                               <input  autocomplete="off" class="form-control" autocomplete="off" data-rule-required="true" data-msg-required="Please enter tour type"   type="text" placeholder="Tour Type" name="tour_type"  >
                            </div>                     
                              
                           </div>

                           <div class="form-actions" style="margin-bottom:0">
                            <div class="row">
                              <div class="col-sm-9 col-sm-offset-3">
                                <a href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><button class="btn btn-primary" type="button">
                                  <i class="icon-reply"></i>
                                  Go Back
                                </button></a>
                                <button class="btn btn-primary" type="submit">
                                  <i class="icon-save"></i>
                                  Add Tour Type
                                </button>
                              </div>
                            </div>
                           </div>
                        
                      </form>

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