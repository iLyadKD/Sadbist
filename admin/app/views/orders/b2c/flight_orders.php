<!DOCTYPE html>
<html>
<head>
    <title>B2C Flight Booking Reports | <?php echo PROJECT_NAME; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta content='text/html;charset=utf-8' http-equiv='content-type'>
    <link href='<?= base_url(); ?>assets/images/meta_icons/favicon.ico' rel='shortcut icon' type='image/x-icon'>
    <link href="<?= base_url(); ?>assets/stylesheets/bootstrap/bootstrap.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/stylesheets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/stylesheets/theme-colors.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/stylesheets/jquery-ui.css" media="all" rel="stylesheet" type="text/css" />
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
                                        <i class='icon-plane'></i>
                                        <span>B2C Flight Booking Reports</span>
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
                                            <li class='active'>B2C Flight Booking Reports</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='box bordered-box orange-border' style='margin-bottom:0;'>
                                    <div class='box-header blue-background'>
                                        <div class='title'>B2C Flight Booking Reports</div>
                                        <div class="actions">
                                            <a class='btn' href="<?php echo WEB_URL; ?>b2c_orders/flight_bookings_export"><i class='icon-download'></i> PDF</a>
                                            <a class='btn' href="<?php echo WEB_URL; ?>b2c_orders/export_flight_bookings"><i class='icon-download'></i> Excel</a> 
                                        </div>
                                    </div>
                                <div> 
                            </div>
                        </div>                   
                    
<div style="overflow: hidden">
    <form name="resetpwd" id="refineSearch" method="post" action="">
        <div class="col-md-4 padding10">
            <div class="ritpul"> 
                <div class="rowput">
                    Date
                    <select  class="form-control logpadding" name="datetype">
                        <option value="">Select Date Type </option>
                        <option value="vdate">Voucher/Booking Date</option>
                        <option value="tdate">Travel Date</option>
                    </select> 
                </div>
            </div>
        </div>

        <div class="col-md-4 padding10">
            <div class="ritpul"> 
                <div class="rowput">
                    From
                    <input class="form-control logpadding dateFrom" id="fromDate" type="text" name="from" placeholder="Select from date"/>
                </div>
            </div>
        </div>

        <div class="col-md-4 padding10">
            <div class="ritpul"> 
                <div class="rowput">
                    To
                    <input class="form-control logpadding dateTo" id="toDate" type="text" name="to" placeholder="Select to date"/>
                </div>
            </div>
        </div>

        <div class="clear"></div>
        <div class="col-md-4 padding10">
            <div class="ritpul"> 
                <div class="rowput">
                    Api Status
                    <input type="hidden" id="fltrType" name="module" value="HOTEL">
                    <input type="hidden" name="user_type" value="3">
                    <select  class="form-control logpadding" name="apistatus">
                        <option value="">Select Date Type </option>
                        <option value="CONFIRMED">Confirmed</option>
                        <option value="PENDING">Pending</option> 
                        <option value="CANCELLED">Cancelled</option> 
                        <option value="FAILED">Failed</option>
                    </select> 
                </div>
            </div>
        </div>
        <div class="col-md-4 padding10">
            <div class="ritpul"> 
                <div class="rowput">
                    Booking Status
                    <select  class="form-control logpadding" name="bookingstatus">
                        <option value="">Select Date Type </option>
                        <option value="CONFIRMED">Confirmed</option>
                        <option value="PENDING">Pending</option> 
                        <option value="CANCELLED">Cancelled</option> 
                        <option value="FAILED">Failed</option>
                    </select> 
                </div>
            </div>
        </div>

        <div class="col-md-4 padding10">
            <div class="ritpul xlbtn"> 
            <div class="btndrt">
                <button class="submitlogin btn btn-primary">Search</button>
            </div>
            </div>
        </div>
    </form>
</div>
    
    <div class="tabbable" style="margin-top: 20px; position: relative">
    <div class='box-content box-no-padding'>
        <div class='responsive-table'>
        <div class='scrollable-area'>
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

<script src="<?=base_url();?>assets/javascripts/orders.js" type="text/javascript"></script>
</script>

</body>
</html>