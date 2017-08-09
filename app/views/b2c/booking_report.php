<!DOCTYPE html>
<html class="no-js">
  <?php
  $include["css"][] = "jquery.dataTables";
  $include["css"][] = "dataTables.tableTools";
  $this->load->view("common/head", $include);
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
        <div class="edit_pro_btns">
            <div class="col-sm-6 nopadding">
           
              <div class="col-sm-3 mnopad">
                <a type="button" href="<?php echo base_url('b2c/edit_profile.html')?>" class="btn btn_editpro" id="save_prof">My Profile</a>
              </div>
              
            </div>
            <!-- <div class="col-sm-6 nopadding text-right">
              <div class="col-sm-3 mnopad">
                <button type="button" class="btn btn_editpro compList" data-id="list"><a href="javascript:void(0)">Companions </a></button>
              </div>
              <div class="col-sm-3 mnopad">
               <a href="<?php echo base_url('b2c/booking_report.html')?>">Report</a>
              </div>
            </div> -->
        </div>
        <div class="profile-content">
        <div class="col-md-12 nopadding">
          <div class="mn-string">
            <div class="mn-hundred">
              <div id="myStat" data-dimension="200" data-text="100%" data-info="<?php echo $this->lang->line("total_bookings"); ?>" data-width="4" data-fontsize="38" data-percent="100" data-fgcolor="#61a9dc" data-bgcolor="#f5f5f5" data-fill="#fff"></div>
              <div id="myStat1" data-dimension="200" data-text="75%" data-info="<?php echo $this->lang->line("confirm_bookings"); ?>" data-width="4" data-fontsize="38" data-percent="75" data-fgcolor="#34a017" data-bgcolor="#f5f5f5" data-fill="#fff"></div>
              <div id="myStat2"  data-dimension="200" data-text="15%" data-info="<?php echo $this->lang->line("cancel_bookings"); ?>" data-width="4" data-fontsize="38" data-percent="15" data-fgcolor="#d51717" data-bgcolor="#f5f5f5" data-fill="#fff"></div>
              <div id="myStat3"  data-dimension="200" data-text="10%" data-info="<?php echo $this->lang->line("pending_bookings"); ?>" data-width="4" data-fontsize="38" data-percent="10" data-fgcolor="#ff5c30" data-bgcolor="#f5f5f5" data-fill="#fff"></div>
            </div>
          </div>
        </div>
        <div class="col-md-12 nopadding">
                    <div class="mn-edit">
                      <div class="col-lg-12">
                            <section id="section-3">
                              <ul class="tabs1">
                                <li><a href='#tab1'><i class="fa fa-plane"></i> <?php echo $this->lang->line("flights"); ?> </a></li>
                                <li><a href='#tab2'><i class="fa fa-hotel"></i> <?php echo $this->lang->line("hotels"); ?></a></li>                                
                                <li><a href='#tab3'><i class="fa fa-map-marker"></i> <?php echo $this->lang->line("tours"); ?> </a></li>
                              </ul>
                              <div id='tab1'>
                                <div class="booking_deposit1">
                                  <table id="example1" class="display" cellspacing="0" width="100%">
                                    <thead>
                                      <tr style="background-color:#6A9E20;">
                                        <th><?php echo $this->lang->line("user_report_no"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_flight_name"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_destination"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_departure"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_class"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_adults"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_children"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_amount"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_status"); ?></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>1</td>
                                        <td>Air Arabia </td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>Economy</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>240$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                      <tr>
                                        <td>1</td>
                                        <td>Air Arabia </td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>Economy</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>340$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div id='tab2'>
                                <div class="booking_deposit1">
                                  <table id="example2" class="display" cellspacing="0" width="100%">
                                    <thead>
                                      <tr style="background-color:#6A9E20;">
                                        <th><?php echo $this->lang->line("user_report_no"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_hotel_name"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_checkin"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_checkout"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_rooms"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_adults"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_country"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_city"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_children"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_amount"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_status"); ?></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>1</td>
                                        <td>Mabely Grand Hotel</td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>2</td>
                                        <td>1</td>
                                        <td>India</td>
                                        <td>Bangalore</td>
                                        <td>2</td>
                                        <td>200$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                      <tr>
                                      <td>1</td>
                                        <td>Mabely Grand Hotel</td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>2</td>
                                        <td>1</td>
                                        <td>India</td>
                                        <td>Bangalore</td>
                                        <td>2</td>
                                        <td>200$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div id='tab3'>
                                <div class="booking_deposit1">
                                  <table id="example1" class="display" cellspacing="0" width="100%">
                                    <thead>
                                      <tr style="background-color:#6A9E20;">
                                        <th><?php echo $this->lang->line("user_report_no"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_location"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_departure_date"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_arrival_date"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_adults"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_children"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_amount"); ?></th>
                                        <th><?php echo $this->lang->line("user_report_status"); ?></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>1</td>
                                        <td>United States</td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>240$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                      <tr>
                                        <td>2</td>
                                        <td>San Francisco</td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>240$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                      <tr>
                                        <td>3</td>
                                        <td>Japan</td>
                                        <td>07-05-2015</td>
                                        <td>08-05-2015</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>240$</td>
                                        <td><a title="Cancel Booking" onclick="hotel_cancel_booking(this)" data-pnrid="1" data-bstatus="CONFIRMED" data-pnr="VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" data-placement="top" class="btn btn-danger btn-xs has-tooltip" data-original-title="Cancel Booking"><span class="loadr"></span><i class="fa fa-times"></i></a> <a title="Mail voucher" onclick="hotel_mail_voucher(this)" data-pnr="SLC150515092311851" data-placement="top" class="btn btn-success btn-xs has-tooltip" data-original-title="Edit Voucher"><span class="loadr"></span><i class="fa fa-envelope-o"></i></a> <a href="/hotel/voucher/VTB4RE1UVXdOVEUxTURreU16RXhPRFV4" target="_blank" title="View voucher" data-placement="top" class="btn btn-primary btn-xs has-tooltip" data-original-title="View Voucher"><i class="fa fa-eye"></i>
</a><a href="#" class="btn btn-warning btn-xs has-tooltip"><i class="fa fa-file-text"></i></a></td>
                                      </tr>
                                    </tbody>
                                  </table>
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
<?php 
  // $include = array();
  // $include['js'][] = "jquery-1.7.min.js";
  // $include['js'][] = "jquery-ui.js";
  // $include['js'][] = "bootstrap.js";
  // $include['js'][] = "menu.js";
  // $include['js'][] = "jquery.circliful.min.js"; 
  // $include['js'][] = "jquery.dataTables.js";
  // $include['js'][] = "dataTables.tableTools.js";
  // $this->load->view('common/footer',$include);
?>
<script type="text/javascript" data-main="<?php echo asset_url(JS_PATH.'config'); ?>" src="<?php echo asset_url(JS_PATH.'require.js'); ?>"></script>
<script>
$( document ).ready(function() {
	$('#myStat').circliful();
	$('#myStat1').circliful();
	$('#myStat2').circliful();
$('#myStat3').circliful();
});
</script>
<script type="text/javascript" language="javascript" class="init">
$(document).ready( function () {
    $('#example, #example1, #example2, #example3').dataTable({
        "dom": 'T<"clear">lfrtip'
    });
});
</script>
<script>
			// Wait until the DOM has loaded before querying the document
			$(document).ready(function(){
				$('ul.tabs1').each(function(){
					// For each set of tabs, we want to keep track of
					// which tab is active and it's associated content
					var $active, $content, $links = $(this).find('a');

					// If the location.hash matches one of the links, use that as the active tab.
					// If no match is found, use the first link as the initial active tab.
					$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
					$active.addClass('active');

					$content = $($active[0].hash);

					// Hide the remaining content
					$links.not($active).each(function () {
						$(this.hash).hide();
					});

					// Bind the click event handler
					$(this).on('click', 'a', function(e){
						// Make the old tab inactive.
						$active.removeClass('active');
						$content.hide();

						// Update the variables with the new link and content
						$active = $(this);
						$content = $(this.hash);

						// Make the tab active.
						$active.addClass('active');
						$content.show();

						// Prevent the anchor's default click action
						e.preventDefault();
					});
				});
			});
			
		</script>
</body>
</html>
