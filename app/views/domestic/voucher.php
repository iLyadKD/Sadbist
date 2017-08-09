<?php
$booking_status = array("0" => "Pending", "1" => "Reserved", "2" => "Confirmed", "3" => "Cancelled", "4" => "Failed");
$flight_type_text = array("OneWay" => "One Way Trip", "Return" => "Round Trip", "OpenJaw" => "MultiCity Trip");
$search_data = json_decode($book_details->input_json);
$traveller_data = json_decode($book_details->traveller_json);
$departures = json_decode($book_details->departures);
$arrivals = json_decode($book_details->arrivals);
$salutations = array("0" => "Mr", "1" => "Mrs", "2" => "Ms", "3" => "Miss", "4" => "Mstr");
$adult_cnt = count($traveller_data->adult_salutation);
$child_cnt = isset($traveller_data->child_salutation) ? count($traveller_data->child_salutation) : 0;
$infant_cnt = isset($traveller_data->infant_salutation) ? count($traveller_data->infant_salutation) : 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flight Voucher</title>
</head>

<body>
<table width="100%"  cellpadding="0" cellspacing="0" border="0" style=" font-family:Trebuchet MS, Arial,sans-serif; font-size:13px;line-height:24px;">
	<tbody>
		<tr>
			<td><table width="820" cellpadding="10" cellspacing="0" border="0" bgcolor="#f8f8f8" align="center">
					<tbody>
						<tr>
							<td><table width="100%" bgcolor="#ec4614" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:center;"><img src="<?php echo asset_url('images/logo.png'); ?>" alt=""/></td>
									</tr>
								</table>
								<table width="100%" bgcolor="#fff" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:left;">
											Booking reference no : <span style="color:#37c622"><?php echo $book_details->book_id; ?></span><br/>
											XML reference no : <span style="color:#ec4614"><?php echo $book_details->ticket_id; ?></span><br/>
											Amount : <span><?php echo $payDetails->amount; ?></span>
										</td>
										<td style="text-align:right"> 
											 Booking Date : <span><?php echo empty($book_details->ticketed_date) ? "NA" : $book_details->ticketed_date; ?></span><br/>
											Booking Status : <span style="color:#ec4614"><?php echo $booking_status[$book_details->book_status]; ?></span><br/>

											Payment Status : 
											<span style="color:#ec4614">
											
											<?php if($book_details->payment_status==1)
											{ echo "Failed"; } ?>
											<?php if($book_details->payment_status==0)
											{ echo "Pending"; } ?>
											<?php if($book_details->payment_status==2)
											{ echo "Success"; } ?>
												
											</span>

										</td>
									</tr>
								</table>
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:center; font-size:16px; color:#37c622">
											<?php
											if(in_array($search_data->flight_type, array("OneWay", "Return")))
											{
												echo $airports[$search_data->flight_origin]->city." (".$search_data->flight_origin.") <img width='15' src='".asset_url('images/take1.png')."' alt=''/> ".$airports[$search_data->flight_destination]->city." (".$search_data->flight_destination.")";
												if($search_data->flight_type === "Return")
													echo ", ".$airports[$search_data->flight_destination]->city." (".$search_data->flight_destination.") <img width='15' src='".asset_url('images/land1.png')."' alt=''/> ".$airports[$search_data->flight_origin]->city." (".$search_data->flight_origin.")";
												echo " - ".$flight_type_text[$search_data->flight_type];
											}
											if($search_data->flight_type === "OpenJaw")
											{
												$length_idx = count($search_data->mflight_origin);
												for ($i = 0; $i < $length_idx; $i++)
												{
													echo $search_data->mflight_origin[$i]." <img width='15' src='".asset_url('images/take1.png')."' alt=''/> ".$search_data->mflight_destination[$i];
													if($i < ($length_idx -1))
														echo ", ";
												}
												echo " - ".$flight_type_text[$search_data->flight_type];
											}
											?>
										</td>
									</tr>
								</table>
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:left; font-size:16px; color:#37c622;padding:10px 0">Passenger Details</td>
									</tr>
								</table>
								<table width="100%" bgcolor="#e5e5e5" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<th style="background:#f5f5f5;border:solid 1px #ccc">Passenger Name</th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none">Nationality</th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none">DOB</th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none">Passport No</th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none">Passport Expires On</th>
										<th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none">Passenger Type</th>
									</tr>
								<?php
								for($i = 0; $i < $adult_cnt; $i++)
								{
								?>
									<tr>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center"><?php echo $salutations[$traveller_data->adult_salutation[$i]].". ".$traveller_data->adult_fname[$i]." ".$traveller_data->adult_lname[$i]; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->adult_nationality[$i]; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->adult_dob[$i]; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->adult_passport[$i]; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->adult_passport_expire[$i]; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center">Adult</td>
									</tr>
								<?php
								}
								for($i = 0; $i < $child_cnt; $i++)
								{
								?>
									<tr>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center"><?php echo $traveller_data->firstname." ".$traveller_data->lastname; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->firstname." ".$traveller_data->lastname; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->contact; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->country."/".$traveller_data->city; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->country."/".$traveller_data->city; ?></td>
									</tr>
								<?php
								}
								for($i = 0; $i < $infant_cnt; $i++)
								{
								?>
									<tr>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center"><?php echo $traveller_data->firstname." ".$traveller_data->lastname; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->firstname." ".$traveller_data->lastname; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->contact; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->country."/".$traveller_data->city; ?></td>
										<td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"><?php echo $traveller_data->country."/".$traveller_data->city; ?></td>
									</tr>
								<?php
								}
								?>
								</table>
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:left; font-size:16px; color:#37c622;padding:10px 0"><img width="17" src="<?php echo asset_url('images/take1.png'); ?>" alt=""/> Departure</span></td>
									</tr>
								</table>
							<?php 
							for($i = 0; $i < count($departures); $i++)
							{
							?>
								<table cellspacing="0" cellpadding="0" bgcolor="#fff" style="width:100%;border:solid 1px #ddd; text-align:center">
									<tr>
										<td width="18%" style="padding:5px; font-size:12px; border-right:solid 1px #ddd"><img width="120px" height="60px" align="" src="<?php echo asset_url('images/airline_logos/'.$departures[$i]->airline.'.gif'); ?>"></td>
										<td width="15%" style="padding:0px;font-size:13px; border-right:solid 1px #ddd"><strong style="color:#00496f;"><?php echo $departures[$i]->airline_name; ?><br>
											<?php echo $departures[$i]->flight_no; ?></strong></td>
										<td style="padding:5px 10px; font-size:12px;text-align:left"><strong style="font-size:18px"><?php echo $departures[$i]->departure_from; ?></strong><br>
											<span style="font-size:14px;"><?php echo $airports[$departures[$i]->departure_from]->city; ?><br>
											<?php echo date("D, d M y, H:i", strtotime($departures[$i]->departure_dttm))." hrs"; ?></span></td>
										<td style="padding:5px 0px; font-size:13px;"><img width="25px" height="25px" alt="" src="<?php echo asset_url('images/AWT-Plane.png'); ?>"></td>
										<td style="text-align:right; padding:5px 10px; font-size:13px;"><strong style="font-size:18px"><?php echo $departures[$i]->arrival_to; ?></strong><br>
											<span style="font-size:14px;"><?php echo $airports[$departures[$i]->arrival_to]->city; ?><br>
											<?php echo date("D, d M y, H:i", strtotime($departures[$i]->arrival_dttm))." hrs"; ?></span></td>
									</tr>
								</table>
							<?php
							}
							if(!empty($arrivals))
							{
							?>
								<table width="100%" border="0" cellpadding="10" cellspacing="0">
									<tr>
										<td style="text-align:left; font-size:16px; color:#37c622;padding:10px 0"><img width="17" src="<?php echo asset_url('images/land1.png'); ?>" alt=""/> Arrival</span></td>
									</tr>
								</table>
							<?php 
							for($i = 0; $i < count($arrivals); $i++)
							{
							?>
								<table cellspacing="0" cellpadding="0" bgcolor="#fff" style="width:100%;border:solid 1px #ddd; text-align:center">
									<tr>
										<td width="18%" style="padding:5px; font-size:12px; border-right:solid 1px #ddd"><img width="120px" height="60px" align="" src="<?php echo asset_url('images/airline_logos/'.$arrivals[$i]->airline.'.gif'); ?>"></td>
										<td width="15%" style="padding:0px;font-size:13px; border-right:solid 1px #ddd"><strong style="color:#00496f;"><?php echo $arrivals[$i]->airline_name; ?><br>
											<?php echo $arrivals[$i]->flight_no; ?></strong></td>
										<td style="padding:5px 10px; font-size:12px;text-align:left"><strong style="font-size:18px"><?php echo $arrivals[$i]->departure_from; ?></strong><br>
											<span style="font-size:14px;"><?php echo $airports[$arrivals[$i]->departure_from]->city; ?><br>
											<?php echo date("D, d M y, H:i", strtotime($arrivals[$i]->departure_dttm))." hrs"; ?></span></td>
										<td style="padding:5px 0px; font-size:13px;"><img width="25px" height="25px" alt="" src="<?php echo asset_url('images/AWT-Plane.png'); ?>"></td>
										<td style="text-align:right; padding:5px 10px; font-size:13px;"><strong style="font-size:18px"><?php echo $arrivals[$i]->arrival_to; ?></strong><br>
											<span style="font-size:14px;"><?php echo $airports[$arrivals[$i]->arrival_to]->city; ?><br>
											<?php echo date("D, d M y, H:i", strtotime($arrivals[$i]->arrival_dttm))." hrs"; ?></span></td>
									</tr>
								</table>
							<?php
							}
							}
							?>
								<table width="100%" cellspacing="0" cellpadding="10" border="0" bgcolor="#fff">
									<tbody>
										<tr>
											<td align="center">10020.ir, Washington DC, USA<br>
												Tel: +90 999 999 9999 Fax: +9999 999999,<br>
												E-mail : support@10020.ir</td>
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
