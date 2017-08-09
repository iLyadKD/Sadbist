<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
		$include["css"][] = "jquery-ui/jquery-ui";
		$this->load->view("common/header", $include);

		$CI = & get_instance();
		$CI->load->model('Call_center_model');

		$status_list = $CI->Call_center_model->getStatusList();
		$staffs      = $CI->Call_center_model->getStaffList(); 
		$dataCharter = $CI->Call_center_model->getCharterDetails($departures->book_url); 

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
														<label>Creation Time</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" disabled="" value="<?php echo date("d M Y g:i A",strtotime($item_det->dt_new)); ?>" type="text" name="creation_time" id="creation_time">
													</div>
													<div class="col-sm-4 controls">
														
														<label>Status</label>
														<select  class="form-control"  name="cc_status" id="cc_status">
														<option value="<?php echo $item_det->status_id; ?>"><?php echo $item_det->status_txt; ?></option>
										                    <?php foreach($status_list as $skey => $svalue):
										                    	
										                    if(in_array($svalue->id,$possibleStatus)):
										                    ?>
										                    <option value="<?php echo $svalue->id; ?>" <?php echo ($svalue->status_txt == $item_det->status_txt) ? 'style="display:none"' : ''; ?>><?php echo $svalue->status_txt;?></option>
										                    <?php endif;endforeach; ?>
										                </select>
													</div>
												</div>
												<!-- Dynamic fields according to selected status -->
												<div class="form-group" id="result-inputs"></div>
												<!-- / Dynamic fields according to selected status -->

												<?php 
													$paxTypeGeneral = array('adult','child','infant');

													foreach ($paxTypeGeneral as $pkey => $pvalue) { 
														$fname = @$traveller_det[$pvalue.'_fname'];
														$lname = @$traveller_det[$pvalue.'_lname'];
														$dob = @$traveller_det[$pvalue.'_dob'];
														$nationality = @$traveller_det[$pvalue.'_nationality'];
														$nat_id = @$traveller_det[$pvalue.'_national_id'];
														$passport_num = @$traveller_det[$pvalue.'_passport'];
														$passport_expire = @$traveller_det[$pvalue.'_passport_expire'];
														for($i = 0 ; $i < count($fname); $i++){
												?>
												
												<div class="form-group">
													<div class="col-sm-1 controls">
														<label>Type</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" disabled value="<?php echo ucfirst($pvalue); ?>" type="text">
													</div>
													<div class="col-sm-2 controls">
														<label>First Name</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text"  value="<?php echo $fname[$i]; ?>" name="traveller_det[<?php echo $pvalue;?>_fname][]">
													</div>
													<div class="col-sm-2 controls">
														<label>Last Name</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text"  value="<?php echo $lname[$i]; ?>" name="traveller_det[<?php echo $pvalue;?>_lname][]">
													</div>
													<div class="col-sm-1 controls">
														<label>DOB</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control dt_dob <?php echo $pvalue;?>" type="text"  value="<?php echo $dob[$i]; ?>" name="traveller_det[<?php echo $pvalue;?>_dob][]">
													</div>
													<div class="col-sm-2 controls">
														<label>Nationality</label>
														<select name="traveller_det[<?php echo $pvalue;?>_nationality][]" class="form-control select2 pax_country" data-rule-required="true" data-class="<?php echo $pvalue.$i;?>">
							                                <?php  foreach ($country as $rows) { ?>
							                                <option value="<?php echo $rows->country_code;?>"<?php echo ( $rows->country_code == $nationality[$i]) ? 'selected' : '' ;?> ><?php echo $rows->name?></option>
							                                <?php }?>
							                            </select>
													</div>
													<?php if($nationality[$i] === 'IR'){
														$nat_display = 'block';
														$pass_display = 'none';
													} else{
														$nat_display = 'none';
														$pass_display = 'block';
													}?>
													<div class="col-sm-4 controls <?php echo $pvalue.$i;?> iran_class" style="display:<?php echo $nat_display; ?>">
														<label>National Id</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text"  value="<?php echo $nat_id[$i]; ?>" name="traveller_det[<?php echo $pvalue;?>_national_id][]">
													</div>
													<div class="col-sm-2 controls <?php echo $pvalue.$i;?> non_iran_class" style="display:<?php echo $pass_display; ?>">
														<label>Passport No:</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text"  value="<?php echo $passport_num[$i]; ?>" name="traveller_det[<?php echo $pvalue;?>_passport][]">
													</div>
													
													<div class="col-sm-2 controls <?php echo $pvalue.$i;?> non_iran_class" style="display:<?php echo $pass_display; ?>">
														<label>Expiry Date</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control from_current_date" type="text"  value="<?php echo date('d/m/Y',strtotime(@$passport_expire[$i])); ?>" name="traveller_det[<?php echo $pvalue;?>_passport_expire][]">
													</div>
												</div>
												<?php 
													}
												}
												?>
												<div class="form-group">
													
													<div class="col-sm-4 controls">
														<label>Charter Company</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->company_name; ?>">
													</div>
													<div class="col-sm-4 controls">
														<label>URL</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->url; ?>">
													</div>
													<div class="col-sm-2 controls">
														<label>Login</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->u_name; ?>">
													</div>
													<div class="col-sm-2 controls">
														<label>Password</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->p_word; ?>">
													</div>
													<div class="col-sm-4 controls">
														<label>List Price (<?php echo $item_det->currency;?>)</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo $item_det->api_cost; ?>">
													</div>
													<div class="col-sm-4 controls">
														<label>Sales Price (<?php echo $item_det->currency;?>)</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo $item_det->total_cost; ?>">
													</div>
													<div class="col-sm-4 controls">
														<label>Actual Purchase Price (<?php echo $item_det->currency;?>)</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" value="<?php echo ($item_det->actual_purchase_price != "") ? $item_det->actual_purchase_price : $item_det->api_cost; ?>" name="actual_price">
													</div>
													<div class="col-sm-2 controls">
														<label>Contact Name</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->contact_name; ?>">
													</div>
													<div class="col-sm-2 controls">
														<label>Support Phone</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->support_phone; ?>">
													</div>
													<div class="col-sm-2 controls">
														<label>Other Phone</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->other_phone; ?>">
													</div>
													<div class="col-sm-6 controls">
														<label>Comment Field</label>
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" type="text" disabled value="<?php echo @$dataCharter->comment; ?>">
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

													$tr = '<tr>
																    	<td>Purchase Date</td>
																    	<td>'.date("d M Y g:i A",strtotime($date)).'</td>
																    </tr>';
															foreach ($det_req as $dkey => $dvalue) { 
																$tr .= '<tr>
																    	<td>'.$dvalue[1].'</td>
																    	<td>'.$details[$dvalue[0]].'</td>
																    	</tr>';	    	
																    }	
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
															<a tabindex="14" class="btn btn-primary update-status-btn" href="javascript:void(0);" data-original-title="Save Details" data-toggle="modal" data-target="#save-details-modal" data-item="<?php echo $item_det->item_id;?>" >
																<i class="icon-save"></i>
																Save
															</a>
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
	</body>
</html>