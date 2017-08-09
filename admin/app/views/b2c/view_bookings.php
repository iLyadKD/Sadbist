<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?> | <?php echo PROJECT_NAME; ?> </title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta content='text/html;charset=utf-8' http-equiv='content-type'>       
        <link href='<?= base_url(); ?>assets/images/meta_icons/favicon.ico' rel='shortcut icon' type='image/x-icon'>
        <link href="<?= base_url(); ?>assets/stylesheets/plugins/datatables/bootstrap-datatable.css" media="all" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/stylesheets/bootstrap/bootstrap.css" media="all" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/stylesheets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css" />
        <link href="<?= base_url(); ?>assets/stylesheets/theme-colors.css" media="all" rel="stylesheet" type="text/css" />
    </head>

    <body class='contrast-muted fixed-header'>
        <?php $this->load->view('header'); ?>
        <div id='wrapper'>
            <div id='main-nav-bg'></div>
            <?php $this->load->view('side-menu'); ?>

            <section id='content'>
                <div class='container'>
                    <div class='row' id='content-wrapper'>
                        <div class='col-xs-12'>
                            <div class='row'>
                                <div class='col-sm-12'>
                                    <div class='page-header'>
                                        <h1 class='pull-left'>
                                            <i class='icon-user'></i>
                                            <span><?php echo $title; ?></span>
                                        </h1>
                                        <div class='pull-right'>
                                            <ul class='breadcrumb'>
                                                <li>
                                                    <a href='<?= base_url(); ?>'>
                                                        <i class='icon-bar-chart'></i>
                                                    </a>
                                                </li>
                                                <li class='separator'>
                                                  <i class='icon-angle-right'></i>
                                                </li>
                                                <li>
                                                  <a href="<?php echo WEB_URL; ?>b2c">B2C Users</a>
                                                </li>
                                                <li class='separator'>
                                                  <i class='icon-angle-right'></i>
                                                </li>
                                                <li class='active'><?php echo $title; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='row'>
                                <div class='col-sm-12'>
                                    <div class='box bordered-box orange-border' style='margin-bottom:0;'>
                                        <div class='box-header blue-background'>
                                            <div class='title'><?php echo $title; ?></div>
                                        </div>
                    					<div class="tabbable" style="margin-top: 20px">
                    					<ul class="nav nav-responsive nav-tabs"><li class="dropdown pull-right tabdrop hide"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify"></i> <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>
                    						<li class="active"><a data-toggle="tab" href="#retab1"><i class="icon-build"></i> Flights</a></li>
                    					 </ul>
                    					<div class="tab-content">

                    					<div id="retab1" class="tab-pane active">
                                        <div class='box-content box-no-padding'>
                                            <div class='responsive-table'>
                                                <div class='scrollable-area'>

                                                    <form method="post" id="myform" action="<?php echo base_url(); ?>b2c/export_b2c_users"  onsubmit="return checkForm(this);">
                                                        <table class='data-table-column-filter table table-bordered table-striped' style='margin-bottom:0;'>
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Booking ID</th>
                                                                    <th>B2C User ID</th>
                                                                    <th>Arrival</th>
                                                                    <th>Departure</th>
                                                                    <th>Deadline-Date</th>
                                                                    <th>Selling Amount</th>
                                                                    <th>Booking Number</th>
                                                                    <th>API Provider Name</th>
                                                                    <th>Created Date</th>
                                                                    <th>Voucher ID</th>
                                                                    <th>Booked Currency</th>
                                                                    <th>Payment Status</th>
                                                                    <th>Currency</th>
                                                                    <th>Selling Price</th>
                                                                    <th>Profit</th>
                                                                    <th>Email</th>
                                                                    <th>Voucher</th>
                                                                    <th>Invoice</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Booking ID</th>
                                                                    <th>B2C User ID</th>
                                                                    <th>Arrival</th>
                                                                    <th>Departure</th>
                                                                    <th>Deadline-Date</th>
                                                                    <th>Selling Amount</th>
                                                                    <th>Booking Number</th>
                                                                    <th>API Provider Name</th>
                                                                    <th>Created Date</th>
                                                                    <th>Voucher ID</th>
                                                                    <th>Booked Currency</th>
                                                                    <th>Payment Status</th>
                                                                    <th>Currency</th>
                                                                    <th>Selling Price</th>
                                                                    <th>Profit</th>
                                                                    <th>Email</th>
                                                                    <th>Voucher</th>
                                                                    <th>Invoice</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                					</div>

                					</div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
				<?php $this->load->view('footer'); ?>
                </div>
            </section>
        </div>
    </body>
</html>