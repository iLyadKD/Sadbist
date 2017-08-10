<!DOCTYPE html>
<html>
<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
		$include["css"][] = "jquery-ui/jquery-ui";
		$include["css"][] = "custom_css";
		$this->load->view("common/header", $include);

		$CI = & get_instance();
		$CI->load->model('Call_center_model');

		$status_list = $CI->Call_center_model->getStatusList();
		$staffs      = $CI->Call_center_model->getStaffList(); 
		$dataCharter = $CI->Call_center_model->getCharterDetails($departures->book_url); 

		if (strpos($departures->book_url, '/redirection/to?rph=') !== false) {
			$dataCharter = $CI->Call_center_model->getCharterDetails(REDIRECTION_URL); 
			// $dataCharter->url = REDIRECTION_URL . $departures->book_url;
		}

		if (strpos($departures->book_url, '/redirection/to?rph=') !== false) {
		  $first_url = REDIRECTION_URL . $departures->book_url;
		}else{
		  $first_url = $departures->book_url;
		}


		$second_url = '';
		if (isset($arrivals) && isset($arrivals->book_url)) {
			if (strpos($arrivals->book_url, '/redirection/to?rph=') !== false) {
			  $second_url = REDIRECTION_URL . $arrivals->book_url;
			}else{
			  $second_url = $arrivals->book_url;
			}
		}
		$enabled = !$has_permission ? 'disabled' : '';


		$possibleStatus = json_decode($item_det->possible_update_status,true);
		if(!is_array($possibleStatus)){
			$possibleStatus = array();
		}
		$itemKey = base64_encode(json_encode(array($item_det->item_id,$item_det->booking_id)));
	?>
	<style type="text/css">
		.control-label{
			text-align: left !important ;
		}

	</style>
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
											<i class="icon-user"></i>
											<span><?php echo $this->data["page_title"] ?></span>
										</h1>
										<div class="pull-right">
											<ul class="breadcrumb">
												<li>
													<a tabindex="-1" href="<?php echo base_url();?>">
														<i class="icon-bar-chart"></i>
													</a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li>
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"] ?></a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li class="active"><?php echo $this->data["page_title"] ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="box"><div class="notification"></div></div>
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"] ?></div>
										</div>
										<div class="box-content">
											<!-- <div>
											<span>Assigned To : <b><?php echo @$staff->fname." ".@$staff->lname; ?></b></span>|<span>Creation Time : <b><?php echo date("d M Y g:i A",strtotime($item_det->dt_new)); ?></b></span>|<span>Status : <b><?php echo $item_det->status_txt; ?></b></span></div> -->
											<form class="form form-horizontal update-item-details-form" action="javascript:void(0);" method="post" enctype="multipart/form-data"> 
												<input type="hidden" name="item_id" value="<?php echo $itemKey; ?>">
												<div class="form-group">
													<div class="col-sm-2 controls">
														<label>Charter Company</label>
													</div>
													<div class="col-sm-4 controls">
														<label>Website:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" disabled="disabled" value="<?php echo isset($dataCharter->url) ? $dataCharter->url : $departures->book_url; ?>" type="text" name="charter_link" >
														<a class="label label-primary" onclick="window.open('<?php echo isset($dataCharter->url) ? $dataCharter->url : $departures->book_url; ?>', ''); return false;" href="javascript:void(0);">Visit</a>
													</div>

													<div class="col-sm-2 controls">
														<label>Reference:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" <?php echo !$has_permission && $dataCharter == '' ? 'disabled' : '' ?> value="<?php echo 1000 + $item_det->item_id;?>" type="text" name="charter_com_ref_number" >
													</div>
													<?php if ($item_det->flight_type == 'Return') { ?>

													<div class="col-sm-4 controls in-out-btm">
														<a id="outboundClicked" href="#" class="btn">Outbound</a>
														<a id="inboundClicked" href="#" class="btn">Inbound</a>

													</div>
													<?php } ?>
													
												</div>
												<div class="form-group">
													<div class="col-sm-3 controls">
														<label>Company:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->company_name; ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>Support:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->support_phone; ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>Mobile:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->other_phone; ?>">
													</div>

													<div class="col-sm-3 controls">
														<label>Contact:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->contact_name; ?>">
													</div>

													<div class="col-sm-1 controls">
														<label>Login:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->u_name; ?>">
													</div>

													<div class="col-sm-1 controls">
														<label>Password:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->p_word; ?>">
													</div>
												</div>
												<hr>
												<div class="form-group book-details">
													<div class="col-sm-2 controls">
														<label>From:</label>
														<input name="from" autofocus="true" class="form-control" type="text" disabled value="<?php echo $CI->get_actual_text($departures->departure_from); ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>To:</label>
														<input name="to" autofocus="true" class="form-control" type="text" disabled value="<?php echo $CI->get_actual_text($departures->arrival_to); ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>Date:</label>
														<input name="flight_date" autofocus="true" class="form-control" type="text" disabled value="<?php echo date('y/m/d', strtotime($departures->departure_dttm)); ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>Airline:</label>
														<input name="airline_name" autofocus="true" class="form-control" type="text" disabled value="<?php echo $departures->airline_name; ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>Flight NO:</label>
														<input name="flight_no" autofocus="true" class="form-control" type="text" disabled value="<?php echo 1000 + $item_det->item_id; ?>">
													</div>

													<div class="col-sm-2 controls">
														<label>Time:</label>
														<input name="flight_time" autofocus="true" class="form-control" type="text" disabled value="<?php echo date('H:i', strtotime($departures->departure_dttm)); ?>">
													</div>
												</div>
												<div class="abed-container">
												<?php 
												$is_return = $item_det->flight_type == 'Return';
												$count = 1;
												$inbound_json = json_decode($item_det->inbound);
												$outbound_json = json_decode($item_det->outbound);
												if ($is_return) {
													$count = 2;
												 ?>
														<div class="form-group abed-item1 outpound-class">
															<div class="col-sm-2 controls">
																<label>Outbound:</label>
															</div>

															<div class="col-sm-2 controls">
																<label>Sold  (<?php echo $item_det->currency;?>):</label>

																<input disabled autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" name="outbound_total_cost" value="<?php echo isset($outbound_json->total_cost) ? number_format($outbound_json->total_cost) : '' ; ?>">

															</div>

															<div class="col-sm-2 controls">
																<label>Listed (<?php echo $item_det->currency;?>):</label>
																<input disabled autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" name="outbound_api_cost" value="<?php echo isset($outbound_json->api_cost) ? number_format($outbound_json->api_cost): '' ; ?>">

															</div>

															<div class="col-sm-2 controls">
																<label>Actual (<?php echo $item_det->currency;?>):</label>
																<input disabled autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" value="<?php echo (isset($outbound_json->actual_cost) && $outbound_json->actual_cost != "") ? number_format($outbound_json->actual_cost) : ''; ?>" <?php echo !$has_permission ? '' : 'name="outbound_actual_cost"'?>>

															</div>

															<div class="col-sm-2 controls">
																<label>Profit (<?php echo $item_det->currency;?>):</label>
																<input id="profit" autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo isset($outbound_json->total_cost) ? number_format($outbound_json->total_cost - $outbound_json->actual_cost) : ''; ?>">

															</div>

															<div class="col-sm-2 controls">
																<label>PNR Number</label>
																<input disabled <?php echo !$has_permission ? '' : 'name="cc_pnr_number"'?> autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $item_det->pnr_number; ?>">
															</div>
														</div>
													<?php } ?>

													<?php if (true) { ?>
														<div class="form-group abed-item2 inpound-class">
															<div class="col-sm-2 controls">
																<label>Inbound:</label>
															</div>

															<div class="col-sm-2 controls">
																<label>Sold  (<?php echo $item_det->currency;?>):</label>
																<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" name="inbound_total_cost" value="<?php echo isset($inbound_json->total_cost) ? number_format($inbound_json->total_cost): '' ; ?>">

															</div>

															<div class="col-sm-2 controls">
																<label>Listed (<?php echo $item_det->currency;?>):</label>
																<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" name="inbound_api_cost" value="<?php echo isset($inbound_json->api_cost) ? number_format($inbound_json->api_cost) :'' ; ?>">

															</div>

															<div class="col-sm-2 controls">
																<label>Actual (<?php echo $item_det->currency;?>):</label>
																<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo (isset($inbound_json->actual_cost) && $inbound_json->actual_cost != "") ? number_format($inbound_json->actual_cost) : ''; ?>" <?php echo !$has_permission ? '' : 'name="inbound_actual_cost"'?>>

															</div>

															<div class="col-sm-2 controls">
																<label>Profit (<?php echo $item_det->currency;?>):</label>
																<input id="profit" autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo isset($inbound_json->total_cost) ? number_format($inbound_json->total_cost - $inbound_json->actual_cost) : ''; ?>">

															</div>

															<div class="col-sm-2 controls">
																<label>PNR Number</label>
																<input <?php echo !$has_permission ? '' : 'name="inbound_pnr_number"'?> autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $item_det->inbound_pnr_number; ?>">
															</div>
														</div>
													</div>
												<?php } ?>
												
												<hr>

												<div class="form-group">
													<div class="col-sm-2 controls">
														<label>Total:</label>
													</div>

													<div class="col-sm-2 controls">
														<label>Sold  (<?php echo $item_det->currency;?>):</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo number_format(intval(@$outbound_json->total_cost) + intval(($is_return ? @$inbound_json->total_cost : 0))); ?>">

													</div>

													<div class="col-sm-2 controls">
														<label>Listed (<?php echo $item_det->currency;?>):</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo number_format(intval(@$outbound_json->api_cost) + intval(($is_return ? @$inbound_json->api_cost : 0))); ?>">

													</div>

													<div class="col-sm-2 controls">
														<label>Actual (<?php echo $item_det->currency;?>):</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled <?php echo $enabled ?> value="<?php echo (isset($outbound_json->actual_cost) && $outbound_json->actual_cost != "") ? number_format(intval(@$outbound_json->actual_cost) + intval(($is_return ? @$inbound_json->actual_cost : 0))) : ''; ?>">

													</div>

													<div class="col-sm-2 controls">
														<label>Profit (<?php echo $item_det->currency;?>):</label>
														<?php $diff = isset($outbound_json->total_cost) ? intval($outbound_json->total_cost) + intval(($is_return ? @$inbound_json->total_cost : 0)) - (intval($outbound_json->actual_cost) + intval(($is_return ? @$inbound_json->actual_cost : 0))) : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo ($diff != "") ? number_format(intval($diff)) : ''; ?>">

													</div>

												</div>
												
												<hr>

												<div class="form-group">
													<div class="col-sm-2 controls">
														<label>Passenger Records</label>
													</div>
												</div>

												<div class="form-group">
													<div class="col-sm-4 controls">
														<label>Assigned To</label>
														<?php if(in_array(9, $possibleStatus)): ?>
															<select  class="form-control"  name="cc_staffs" >
																<?php 
																foreach($staffs as $skey => $svalue):?>
																	<option value="<?php echo $svalue->id; ?>" <?php echo ($svalue->id == $item_det->assigned_to)?'selected' : '' ?>><?php echo $svalue->fname." ".$svalue->lname;?></option>
																<?php endforeach; ?>

															</select>
														<?php else: ?>
															<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" disabled="" value="<?php echo @$staff->fname." ".@$staff->lname; ?>" type="text" name="cc_staffs" >
														<?php endif;?>	
													</div>


													<div class="col-sm-4 controls">
														<label>Status</label>
														<select <?php echo $enabled ?> class="form-control" <?php echo !$has_permission ? '' : 'name="cc_status"'?> id="cc_status">
														<option value="<?php echo $item_det->status_id; ?>"><?php echo $item_det->status_txt; ?></option>
															<?php foreach($status_list as $skey => $svalue):
																
															if(in_array($svalue->id,$possibleStatus)):
															?>
															<option value="<?php echo $svalue->id; ?>" <?php echo ($svalue->status_txt == $item_det->status_txt) ? 'style="display:none"' : ''; ?>><?php echo $svalue->status_txt;?></option>
															<?php endif;endforeach; ?>
														</select>
													</div>

													<div class="col-sm-2 controls">
                                                                                                                <label class="lable-assign-status">Re Assign</label>
														<a class="btn btn-primary has-tooltip assign-staff" data-placement="top" title="" href="javascript:void(0);" data-original-title="Re-Assign Staff" data-toggle="modal" data-target="#assignstaffModal" <?php echo ($has_admin_permission || in_array(9, $possibleStatus)) ? '' : 'disabled' ?> data-item="<?php echo $item_det->item_id;?>"><i class="icon-user"></i> Re-Assign</a>
												        </div>
                                                                                                        <div class="col-sm-2 controls">
                                                                                                                <label class="lable-assign-status">Change Status</label>

														<a tabindex="14" class="btn btn-primary update-status-btn has-tooltip" href="javascript:void(0);" data-original-title="Change Status" data-toggle="modal" data-target="#update-status" <?php echo $has_admin_permission ? '' : 'disabled' ?> data-item="<?php echo $item_det->item_id;?>" >
															<i class="icon-save"></i>
															Change Status
														</a>
													</div>
												</div>


												<!-- Dynamic fields according to selected status -->
												<div class="form-group" id="result-inputs"></div>
												<!-- / Dynamic fields according to selected status -->

												<div class="form-group">
													<div class="col-sm-4 controls">
														<label>Contact Number</label>
														<input <?php echo !$has_permission ? '' : 'name="contact"'?> autofocus="true" class="form-control" type="text" disabled value="<?php echo $item_det->contact; ?>">
													</div>
													<div class="col-sm-4 controls">
														<label>Email address</label>
														<input <?php echo !$has_permission ? '' : 'name="email"'?> autofocus="true" class="form-control" type="text" disabled value="<?php echo $item_det->email; ?>">
													</div>
												</div>

												<?php 
													$paxTypeGeneral = array('adult','child','infant');
													$index = 0;
													foreach ($paxTypeGeneral as $pkey => $pvalue) {

														$fname = @$traveller_det[$pvalue.'_fname'];
														$lname = @$traveller_det[$pvalue.'_lname'];
														$name_fa = @$traveller_det[$pvalue.'_name_fa'];
														$inbound = @$traveller_det[$pvalue.'_inbound'];
														$outbound = @$traveller_det[$pvalue.'_outbound'];
														$salutation = @$traveller_det[$pvalue.'_salutation'];
														$dob = @$traveller_det[$pvalue.'_dob'];
														$nationality = @$traveller_det[$pvalue.'_nationality'];
														$nat_id = @$traveller_det[$pvalue.'_national_id'];
														$passport_num = @$traveller_det[$pvalue.'_passport'];
														$passport_expire = @$traveller_det[$pvalue.'_passport_expire'];
														for($i = 0 ; $i < count($fname); $i++){
												?>
												
												<div class="form-group">
													<div class="col-sm-1 controls last-row">

														<?php echo $index == 0 ? '<label>Type</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" disabled value="<?php echo ucfirst($pvalue); ?>" type="text">
													</div>

													<div class="col-sm-1 controls last-row">

														<?php echo $index == 0 ? '<label>Title</label>' : '' ?>
														<select <?php echo $enabled ?> style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_salutation][]"' ?> title="<?php echo $this->lang->line("title"); ?>" class="select2" id="adult_salutation" >
															<option <?php echo $salutation[$i] === "0" ? "selected" : ""; ?> value="0"><?php echo $this->lang->line("mr");
															?></option>
															<option <?php echo $salutation[$i] === "1" ? "selected" : ""; ?> value="1"><?php echo $this->lang->line("mrs");
															?></option>
															<option <?php echo $salutation[$i] === "3" ? "selected" : ""; ?> value="3"><?php echo $this->lang->line("miss");
															?></option>
														</select>
													</div>

													<div class="col-sm-1 controls last-row">
														<?php echo $index == 0 ? '<label>First Name</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $fname[$i]; ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_fname][]"' ?> >
													</div>
													<div class="col-sm-1 controls last-row">

														<?php echo $index == 0 ? '<label>Last Name</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $lname[$i]; ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_lname][]"' ?> >
													</div>

													<div class="col-sm-1 controls last-row">

														<?php echo $index == 0 ? '<label>Persian Name</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $name_fa[$i]; ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_name_fa][]"' ?> >
													</div>


													<div class="col-sm-1 controls last-row">
														<?php echo $index == 0 ? '<label>DOB</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control dt_dob <?php echo $pvalue;?>" type="text" <?php echo $enabled ?> value="<?php echo date('Y/m/d', strtotime($dob[$i])); ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_dob][]"' ?> >
													</div>
													<div class="col-sm-2 controls last-row">

														<?php echo $index == 0 ? '<label>Nationality</label>' : '' ?>
														<select <?php echo $enabled ?> <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_nationality][]"' ?> class="form-control select2 pax_country" data-rule-required="true" data-class="<?php echo $pvalue.$i;?>">
															<?php 
															 foreach ($country as $rows) { ?>
															<option value="<?php echo $rows->country_code;?>"<?php echo ( $rows->country_code == $nationality[$i]) ? 'selected' : '' ;?> ><?php echo $rows->name?></option>
															<?php }?>
														</select>
													</div>
													<?php 
														$nat_display = 'none';
														$pass_display = 'block';
													?>
													<div class="col-sm-4 controls last-row <?php echo $pvalue.$i;?> iran_class" style="display:<?php echo $nat_display; ?>">
														<?php echo $index == 0 ? '<label>National Id</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $nat_id[$i]; ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_national_id][]"' ?> >
													</div>
													<div class="col-sm-1 controls last-row <?php echo $pvalue.$i;?> non_iran_class" style="display:<?php echo $pass_display; ?>">

														<?php echo $index == 0 ? '<label>Passport No:</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" <?php echo $enabled ?> value="<?php echo $passport_num[$i]; ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_passport][]"' ?> >
													</div>
													
													<div class="col-sm-1 controls last-row <?php echo $pvalue.$i;?> non_iran_class" style="display:<?php echo $pass_display; ?>">

														<?php echo $index == 0 ? '<label>Expiry Date</label>' : '' ?>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control from_current_date" type="text" <?php echo $enabled ?> value="<?php echo date('Y/m/d',strtotime(str_replace('/', '-', @$passport_expire[$i]))); ?>" <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_passport_expire][]"'?> >
													</div>



													<div class="col-sm-1 controls last-row Last-Row-CheckBox">

														<?php echo $index == 0 ? '<label>IN</label>' : '';
														$status = (isset($inbound[$i]) && $inbound[$i] === "on") ? "checked='checked'" : "";
														 ?>
														<div>
															<input type='checkbox' <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_inbound]['.$i.']"'?> <?php echo $status ?> id='inbound<?php echo $index ?>' class='custom-switch-checkbox'>
														</div>
														
													</div>

													<div class="col-sm-1 controls last-row Last-Row-CheckBox" id="Out-Checkbox">

														<?php echo $index == 0 ? '<label>Out</label>' : '';
														$status = (isset($outbound[$i]) && $outbound[$i] === "on") ? "checked='checked'" : "";
														 ?>
														<div>
															<input type='checkbox' <?php echo !$has_permission ? '' : 'name="traveller_det[' . $pvalue . '_outbound]['.$i.']"'?> <?php echo $status ?> id='outbound<?php echo $index ?>' class='custom-switch-checkbox'>
														</div>
														
													</div>


												</div>
												<?php 
												$index ++;
													}
												}
												?>
												<div class="form-group">
													<label class="col-md-2 control-label">Comments</label>
													<div class="col-md-10 controls">
													<textarea class="form-control" name="item_comments" id="item_comments" rows="5"><?php echo htmlspecialchars($item_det->item_comments) ?></textarea>
													</div>
												</div>

												</form>

												<div class="col-md-12">
												<?php 
												  $status_id  = $item_det->status_id;
												  $status_txt = $item_det->status_txt;
												  $det_req    = json_decode($item_det->details_required,true);
												  if($status_id > 3 &&  $status_id < 9):

													switch($status_id){
														case 4 : //PURCHASED
															$details = json_decode($item_det->purchase_details,true);
															$date    = $item_det->dt_purchase;

															break;

														case 5 : //BOOKED
															$details = json_decode($item_det->book_details,true);
															$date    = $item_det->dt_book;
															break;

														case 6 : //ISSUE
															$details = json_decode($item_det->issue_details,true);
															$date    = $item_det->dt_issue;
															break;

														case 7 : //REFUND REQUESTED
															$details = json_decode($item_det->refund_rq_details,true);
															$date    = $item_det->dt_refund_rq;
															break;

														case 8 : //REFUNDED
															$details = json_decode($item_det->refunded_details,true);
															$date    = $item_det->dt_refunded;
															break;

														default:
															break;	
													}
													$tr='';

													// $tr = '<tr>
													// 					<td>Purchase Date</td>
													// 					<td>'.date("d M Y g:i A",strtotime($date)).'</td>
													// 				</tr>';
															// foreach ($det_req as $dkey => $dvalue) { 
															// 	$tr .= '<tr>
															// 			<td>'.$dvalue[1].'</td>
															// 			<td>'.$details[$dvalue[0]].'</td>
															// 			</tr>';	    	
															// 		}	
												 ?>
												  <table class="table">
													<?php echo $tr;?>
												  </table>
												<?php endif;?>
												</div>
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-12" style="text-align:center">
															<a tabindex="-1" href="<?php echo base_url('call_center'); ?>"><button tabindex="13" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<?php if($has_permission){ ?>
																<a tabindex="14" class="btn btn-primary update-status-btn" href="javascript:void(0);" data-original-title="Save Details" data-toggle="modal" data-target="#save-details-modal" data-item="<?php echo $item_det->item_id;?>" >
																	<i class="icon-save"></i>
																	Save
																</a>
															<?php } ?>
															<!-- <a tabindex="14" class="btn btn-primary update-status-btn" href="javascript:void(0);" data-original-title="Assign Staff" data-toggle="modal" data-target="#update-status" data-item="<?php echo $item_det->item_id;?>" >
																<i class="icon-save"></i>
																Update Status
															</a> -->
															<?php 
															//show reassign button only to purchased or booked or issue statuses
															//if( in_array($item_det->status_id,array(4,5,6))  ):?>
															<!-- <a tabindex="14" class="btn btn-primary assign-staff" href="javascript:void(0);" data-original-title="Assign Staff" data-toggle="modal" data-target="#assignstaffModal" data-item="<?php echo $item_det->item_id;?>">
																<i class="icon-save"></i>
																Assign / Re-assign 
															</a> -->
														<?php //endif;?>
														</div>
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
  <!-- Modal Assign Staff -->
  <div class="modal fade" id="assignstaffModal" role="dialog">
	<div class="modal-dialog modal-sm">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <div class="notification"></div>
		</div>
		<div class="modal-body">
		<form action="POST" id="assign-staff-form">
		  <div class="form-group">
						  <label class="control-label col-sm-3" for="country">Assign staff</label>
						  <div class="col-sm-6 controls">
							<select  class="form-control"  name="cc_staffs" id="cc_staffs">
								 <?php foreach($staffs as $skey => $svalue):?>
								<option value="<?php echo $svalue->id; ?>"><?php echo $svalue->fname." ".$svalue->lname;?></option>
								<?php endforeach; ?>
						   </select>
						   <input type="hidden" name="item_val" id="item_val" value="<?php echo $item_det->item_id;?>">
						  </div>
						</div>
		</div>
		<div class="modal-footer">
		  <button type="submit" class="btn btn-default">Assign</button>
		</div>
		</form>
	  </div>
	</div>
  </div>
  </div>

   <!-- Modal Update Status -->
  <div class="modal fade" id="update-status" role="dialog">
	<div class="modal-dialog modal-sm">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <div class="notification"></div>
		</div>
		<div class="modal-body bd_ovr">
		<form action="POST" id="update-status-form" class="update-status-form">
			<div class="form-group bd_ovr">
				<div class="col-sm-12 controls nopad">
					<label class="control-label c_l nopad" for="country">Change Status</label>
					<select  class="form-control"  name="cc_status" id="cc_status">
						<?php foreach($status_list as $skey => $svalue):?>
						<option value="<?php echo $svalue->id; ?>" <?php echo ($svalue->status_txt == $item_det->status_txt) ? 'selected' : ''; ?>><?php echo $svalue->status_txt;?></option>
						<?php endforeach; ?>
					</select>
					<input type="hidden" name="item_val_status" id="item_val_status" value="<?php echo $item_det->item_id;?>">
				</div>
			</div>
			<div class="controls " id="result-inputs"></div>
		</div>
		<div class="modal-footer">
		  <button class="btn btn-default" id="update-btn-form" type="submit">Update</button>
		</div>
		</form>
	  </div>
	</div>
  </div>
  </div>


<div class="modal fade" id="assignstaffModal" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="notification"></div>
			</div>
			<form action="POST" id="assign-staff-form">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-sm-3" for="country">Assign staff</label>
						<div class="col-sm-6 controls">
							<select  class="form-control"  name="cc_staffs" id="cc_staffs">
								<?php foreach($staffs as $skey => $svalue):?>
									<option value="<?php echo $svalue->id; ?>"><?php echo $svalue->fname." ".$svalue->lname;?></option>
								<?php endforeach; ?>
						 </select>
						 <input type="hidden" name="item_val" id="item_val">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-default">Assign</button>
				</div>
			</form>
		</div>
	</div>
</div>


  <!-- Modal Update Details -->
  <div class="modal fade" id="save-details-modal" role="dialog">
	<div class="modal-dialog modal-sm">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <div class="notification"></div>
		</div>
		<div class="modal-body bd_ovr">
			<h2>Are you sure to proceed ?</h2>
		</div>
		<div class="modal-footer">
		  <button class="btn btn-default" id="proceed-update-details-btn" type="submit">Proceed</button>
		  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
		</div>
	  </div>
	</div>
  </div>
  </div>

<script src="<?php echo base_url('assets/js/jquery/jquery.min.js');?>"></script>
<script>
	$(document).ready(function() {
		$('#outboundClicked').click(function(event) {
			event.preventDefault();


			if ($('.outpound-class').hasClass('abed-item2')) {
				$('.outpound-class').addClass('has-success');
				$('.outpound-class').find('input:not(#profit), a, button').prop('disabled', false);
				$('.outpound-class').removeClass('abed-item2');
				$('.outpound-class').addClass('abed-item1');

				$('.inpound-class').find('input:not(#profit), a, button').prop('disabled', true);
				$('.inpound-class').removeClass('has-success');
				$('.inpound-class').removeClass('abed-item1');
				$('.inpound-class').addClass('abed-item2');
			}
		});
		$('#inboundClicked').click(function(event) {
			event.preventDefault();
			if ($('.inpound-class').hasClass('abed-item2')) {
				$('.inpound-class').addClass('has-success');
				$('.inpound-class').find('input:not(#profit), a, button').prop('disabled', false);
				$('.inpound-class').removeClass('abed-item2');
				$('.inpound-class').addClass('abed-item1');
				

				$('.outpound-class').find('input:not(#profit), a, button').prop('disabled', true);
				$('.outpound-class').removeClass('has-success');
				$('.outpound-class').removeClass('abed-item1');
				$('.outpound-class').addClass('abed-item2');
			}
		});
	});
</script>
	</body>
</html>