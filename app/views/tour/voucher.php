

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->lang->line("tour_vouchar"); ?></title>

<link href="<?php echo asset_url('css/font-awesome.css');?>" media='all' rel='stylesheet' type='text/css' >

<body>	
<table width="100%"  cellpadding="0" cellspacing="0" border="0" style=" font-family:Trebuchet MS, Arial,sans-serif; font-size:13px;line-height:24px;">
	<tbody>
		<tr>
			<td><table width="820" cellpadding="10" cellspacing="0" border="0" bgcolor="#f8f8f8" align="center">
					<tbody>
						<tr>
							<td><table width="100%" bgcolor="#ec4614" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:center;"><img src="<?php echo asset_url('images/logo.png');?>"  alt=""/></td>
									</tr>
								</table>
								<table width="100%" bgcolor="#fff" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:left;">
											  <span style="color:#37c622"><?php echo $book_id;?></span><br/>
											 <?php echo $this->lang->line("book_ref_no_colan"); ?><span style="color:#ec4614"></span>
										</td>
										<td style="text-align:right"> 
											  <span>NA</span><br/>
											 <span style="color:#ec4614"><?php echo $this->lang->line("pending"); ?></span>
										</td>
									</tr>
								</table>
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
            	<tr>
                	<td style="text-align:center; font-size:20px; color:#37c622">
                    	<?php echo $this->lang->line("hotel_details"); ?>                       
                    </td>
                </tr>
            </table>
								
            <!----------------Hotel details------------(START)-------------->
			<table width="100%" bgcolor="#e5e5e5" border="0" cellpadding="10" cellspacing="0">
            	<tr>
                	<th style="background:#f5f5f5;border:solid 1px #ccc"><?php echo $this->lang->line("hotel_name"); ?></th>
                    <th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("address_colan_off"); ?></th>
                    <th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("neighbourhood"); ?></th>
                    <th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("star_rating"); ?></th>
                </tr>
                <tr>
                	<td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center"><?php echo $hotel['name'];?></td>
                    <td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $hotel['address'];?></td>
                    <td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $hotel['neighbours'];?></td>
                    <td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $hotel['rating'];?></td>
                </tr>
            </table>
			<!----------------Hotel details------------(END)-------------->
								
								
								
								
								<!----------------Booking details------------(START)-------------->
								
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:center; font-size:20px; color:#37c622"><?php echo $this->lang->line("booking_details"); ?></td>
									</tr>
								</table>
								<table width="100%" bgcolor="#e5e5e5" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<th style="background:#f5f5f5;border:solid 1px #ccc"><?php echo $this->lang->line("name"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("room_type"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("check_in"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("check_out"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("no_of_days"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("passenger_type"); ?></th>
										
									</tr>
									<?php foreach($travellers as $travller){
										if(array_key_exists('adult_salutation',$travller)){
											$type = 'Adult';
											$type_str =  $this->lang->line("adult"); 
										}elseif($travller->group != 'infants'){
											$type = 'Child';
											$type_str =  $this->lang->line("child");
										}else{
											$type = 'Infants';
											$type_str =  $this->lang->line("infants");
										}
										
										if($type == 'Adult'){
											$sal = $travller->adult_salutation;
											$name = $travller->adult_fname.'&nbsp;'.$travller->adult_lname;
											$room_type = $travller->room_type;
										}else{
											$sal = $travller->child_salutation;
											$name = $travller->child_fname.'&nbsp;'.$travller->child_lname;											   $room_type = '';
										}
										if($sal == 0) $sal = 'Mr';elseif($sal == 1) $sal = 'Miss'; else $sal = 'Mrs';
										
									?>
									
									<tr>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center"><?php echo $sal.'.'.$name;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $room_type;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $in_date;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $out_date;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $no_of_days;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $type_str;?></td>
									</tr>
									
									
									<?php } ?>
									
								</table>
								
								<!----------------Booking details------------(END)-------------->

								
								
								
								<!----------------Transportation------------(START)-------------->
								<?php
									//pr($transport);exit;
									if(array_key_exists('tour_bus_id',$transport)){
										$logo = 'bus.png';
										$fa = 'fa-bus';
										$name = $transport->bus_name;
										$number = $transport->bus_number;
										$from = $transport->departure_from;
										$to = $transport->arrival_bus;
										$dept_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->departure_time));
										$arr_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->arrival_time));
										
										$return_from = $transport->return_deaparture;
										$return_to = $transport->return_arrival;
										$return_dept_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_departure_time));
										$return_arr_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_arrival_time));
										
									}elseif(array_key_exists('tour_flight_id',$transport)){
										$logo = 'flight.png';
										$fa = 'fa-plane';
										$name = $transport->airline_name;
										$number = $transport->airline_no;
										$from = $transport->departuer_airport;
										$to = $transport->arrival_airport;
										$dept_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->departuer_time));
										$arr_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->arrival_time));
										
										$return_from = $transport->return_deapartur;
										$return_to = $transport->return_arrival;
										$return_dept_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_departure_time));
										$return_arr_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_arrival_time));
									}elseif(array_key_exists('tour_cruise_id',$transport)){
										$logo = 'cruise.png';
										$fa = 'fa-ship';
										$name = $transport->cruise_name;
										$number = $transport->cruise_number;
										$from = $transport->departure_from;
										$to = $transport->arrival_bus;
										$dept_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->departure_time));
										$arr_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->arrival_time));
										
										$return_from = $transport->return_deaparture;
										$return_to = $transport->return_arrival;
										$return_dept_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_departure_time));
										$return_arr_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_arrival_time));
									}else{
										$logo = 'train.png';
										$fa = 'fa-train';
										$name = $transport->train_name;
										$number = $transport->train_number;
										$from = $transport->departure_from;
										$to = $transport->arrival_bus;
										$dept_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->departure_time));
										$arr_time = date("D jS  M Y",strtotime($in_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->arrival_time));
										
										$return_from = $transport->return_deaparture;
										$return_to = $transport->return_arrival;
										$return_dept_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_departure_time));
										$return_arr_time = date("D jS  M Y",strtotime($out_date)).',&nbsp;'.date("h:i:s A",strtotime($transport->return_arrival_time));
										
									}
								?>
								
								
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:center; font-size:20px; color:#37c622"><?php echo $this->lang->line("transportation"); ?></td>
									</tr>
								</table>								
								
								<table cellspacing="0" cellpadding="0" bgcolor="#fff" style="width:100%;border:solid 1px #ddd; text-align:center">
									<tr>
										<td width="18%" style="padding:5px; font-size:12px; border-right:solid 1px #ddd"><img width="120px" height="70px" align="" src="<?php echo asset_url('images/vouchar/'.$logo);?>"></td>
										<td width="15%" style="padding:0px;font-size:13px; border-right:solid 1px #ddd"><strong style="color:#00496f;"><?php echo $name;?><br>
											<?php echo $number;?></strong></td>
										<td style="padding:5px 10px; font-size:12px;text-align:left"><strong style="font-size:18px"><?php echo $from;?></strong><br>
											<?php echo $dept_time;?></td>
										<td style="padding:5px 0px; font-size:13px;">
											<i class="fa <?php echo $fa;?> fa-2x" aria-hidden="true" style="color:#ec4614"></i>
										</td>
										<td style="text-align:right; padding:5px 10px; font-size:13px;"><strong style="font-size:18px"><?php echo $to;?></strong><br>
											<?php echo $arr_time;?></td>
									</tr>
								</table>
								
								<div style="text-align: center;"><img width="80px" height="40px" align="" src="<?php echo asset_url('images/return_journey.png');?>"></div>
								
								<table cellspacing="0" cellpadding="0" bgcolor="#fff" style="width:100%;border:solid 1px #ddd; text-align:center">
									<tr>
										<td width="18%" style="padding:5px; font-size:12px; border-right:solid 1px #ddd"><img width="120px" height="70px" align="" src="<?php echo asset_url('images/vouchar/'.$logo);?>"></td>
										<td width="15%" style="padding:0px;font-size:13px; border-right:solid 1px #ddd"><strong style="color:#00496f;"><?php echo $name;?><br>
											<?php echo $number;?></strong></td>
										<td style="padding:5px 10px; font-size:12px;text-align:left"><strong style="font-size:18px"><?php echo $return_from;?></strong><br>
											<?php echo $return_dept_time;?></td>
										<td style="padding:5px 0px; font-size:13px;">
											<i class="fa <?php echo $fa;?> fa-2x" aria-hidden="true" style="color:#ec4614"></i>
										</td>
										<td style="text-align:right; padding:5px 10px; font-size:13px;"><strong style="font-size:18px"><?php echo $return_to;?></strong><br>
											<?php echo $return_arr_time;?></td>
									</tr>
									</table>
								
								<!----------------Transportation------------(END)-------------->
								
								
								
								<!----------------Passenger details------------(START)-------------->
								
								
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:center; font-size:20px; color:#37c622"><?php echo $this->lang->line("passenger_details"); ?></td>
									</tr>
								</table>
								<table width="100%" bgcolor="#e5e5e5" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("name"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("nationality"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("date_of_birth"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("passport_number"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("passport_expiry_date"); ?></th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("passenger_type"); ?></th>
										
									</tr>
									<?php foreach($travellers as $travller){
										if(array_key_exists('adult_salutation',$travller)){
											$type = 'Adult';
											$type_str =  $this->lang->line("adult"); 
										}elseif($travller->group != 'infants'){
											$type = 'Child';
											$type_str =  $this->lang->line("child");
										}else{
											$type = 'Infants';
											$type_str =  $this->lang->line("infants");
										}
										if($type == 'Adult'){
											$sal = $travller->adult_salutation;
											$name = $travller->adult_fname.'&nbsp;'.$travller->adult_lname;
											$nationality = $travller->adult_nationality;
											$dob = $travller->adult_dob;
											$pn = $travller->adult_passport_no;
											$pe = $travller->adult_passport_exp;
										}else{
											$sal = $travller->child_salutation;
											$name = $travller->child_fname.'&nbsp;'.$travller->child_lname;
											$nationality = $travller->child_nationality;
											$dob = $travller->child_dob;
											$pn = $travller->child_passport_no;
											$pe = $travller->child_passport_exp;
										}
										if($sal == 0) $sal = 'Mr';elseif($sal == 1) $sal = 'Miss'; else $sal = 'Mrs';
										
									?>
									
									<tr>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center"><?php echo $sal.'.'.$name;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $nationality;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $dob;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $pn;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $pe;?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $type;?></td>
									</tr>
									
									
									<?php } ?>
									
								</table>
								
								
								
								<!----------------Passenger details------------(END)-------------->
								
								
								
								
								
								<table width="100%" cellspacing="0" cellpadding="10" border="0" bgcolor="#fff">
									<tbody>
										<tr>
											<td align="center"><?php echo $contact_address->address;?><br>
												<?php echo $this->lang->line("contact_us"); ?>: <?php echo $contact_address->contact;?>,<br>
												<?php echo $this->lang->line("email_address"); ?> : <?php echo $contact_address->email;?></td>
										</tr>
									</tbody>
								</table></td>
						</tr>
					</tbody>
				</table></td>
		</tr>
	</tbody>
</table>
</body>
</html>
