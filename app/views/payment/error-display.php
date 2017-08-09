<!DOCTYPE html>
<html>
	<?php
	$this->load->view("common/head");
	$cabin_type_text = array("Y" => $this->lang->line("economy_class"), "S" => $this->lang->line("premium_economy_class"), "C" => $this->lang->line("business_class"), "J" => $this->lang->line("premium_business_class"), "F" => $this->lang->line("first_class"), "P" => $this->lang->line("premium_first_class"));
	$trip_type = array("OneWay" => $this->lang->line("one_way"), "Return" => $this->lang->line("round_trip"), "OpenJaw" => $this->lang->line("multi_city"));
	?>
	<head>
		<style>
/*Confirmation*/
.confirm-msg {
	background:#fff;
	width:80%;
	margin:25px auto;
	padding:25px;
	border:1px solid #dedede;
}
.confirm-msg h2 {
	color:#0C0;
	font-size:25px;
}
.confirm-msg h4 {
	font-size:18px;
	margin:5px 0px;
}
.confirm-msg div > strong {
	font-size:16px;
}
.confirm-msg table {
	border:1px solid #dedede;
}
.confirm-msg .unsecc {
	color:#ff0000 !important;
}
.confirm-msg div > i {
	margin-top:15px;
}
.confirm-msg .text-success {
	background:#0C0;
	color:#fff;
	padding:2px 0px;
	width:30%;
	margin-top:5px;
	text-transform:uppercase;
}
.confirm-msg .text-unsuccess {
	background:#ff0000;
	color:#fff;
	padding:2px 0px;
	width:45%;
	margin-top:5px;
	text-transform:uppercase;
}
.confirm-msg img {
    width: 50px;
}
</style>
	</head>
<body>
<div id="wrapper">
<?php
	$this->load->view("common/header");
?>
	


<section class="contentwrap1">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="confirm-msg text-center"> <img src="<?php echo base_url();?>assets/images/unsuccess.png" width="190">
          <h2 class="unsecc">Transaction not Successful</h2>
          <div>
            <h4>Something went wrong and we were not able to complete your booking. Please try again.</h4>
          </div>
          <div class="clearfix"></div>
          <br>
          <br>
          <!-- <table class="table table-striped text-left">
            <tr>
              <th colspan="3"><h4>Bangalore - New Delhi</h4></th>
              <th class="text-right"><h4>Aug 11, 2016</h4></th>
            </tr>
            <tr>
              <td><div><strong>Mr. Passenger name here</strong></div>
                <div class="text-unsuccess text-center">unsuccessful</div></td>
              <td><div><strong>Jet Airways</strong></div>
                <div>9W 745 Economy</div></td>
              <td><div><strong>Bangalore</strong> <i class="fa fa-long-arrow-right pull-right" ></i></div>
                <div>08:55</div></td>
              <td><div><strong>New Delhi</strong></div>
                <div>11:00</div></td>
            </tr>
          </table> -->
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</section>




	<?php 
	$this->load->view('common/pop-ups');
	$this->load->view('common/footer');
?>
	</body>
</html>