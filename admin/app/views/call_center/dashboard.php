<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "datatables/jquery.dataTables.min";
		$include["css"][] = "datatables/dataTables.tableTools.min";
		$this->load->view("common/header", $include);
	?>
		

<style type="text/css">
  
  .box .box-header .actions1 {float: left  !important;display: table  !important;margin:0 auto !important;    margin-top: -3px;}
.actions1 .form-group{  display: inline-block;
    padding: 0 10px;}
  
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
											<i class="icon-eye-open"></i>
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
                        <li class="active"><?php echo $this->data["page_title"] ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							
							
          <div class="main-chart dash">
            <div class="tabbable customtab emptabs">
               <ul class="nav nav-tabs emp" role="tablist" id="myTab">
                  <li role="presentation"  class="active" ><a href="#createemp" aria-controls="emp" role="tab" data-toggle="tab">Items</a></li>
                  <!-- <li role="presentation">
                     <a href="#emplist" aria-controls="profile" role="tab" data-toggle="tab">Staff List</a>
                  </li> -->
                  <li role="presentation">
                     <a href="#charterlist" aria-controls="profile" role="tab" data-toggle="tab">Charter Companies</a>
                  </li>
               </ul>
                        
               <div class="tab-content ">
                  <div role="tabpanel" class="tab-pane active" id="createemp">
                   <div class="intabs">
               
                       <div class="rowit">
                           <div class="box bordered-box orange-border" style="margin-bottom:0;">
                              <div class="box-header blue-background">
                                <div class="col-md-3"> <div class="title">Items</div></div>
                                <div class="col-md-6">

                                <div class="actions1">

                                          <div class="form-group">

                                            
                                              <select class="form-control" id="sort_status">
                                                  <option value="">sort by status</option>
                                                  <?php foreach($status_list as $stkey => $stvalue):?>
                                                      <option value="<?php echo $stvalue->id; ?>" <?php echo ($stvalue->id == $status_sess)?'selected' : ''; ?>><?php echo $stvalue->status_txt;?></option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </div>
                                          <div class="form-group">
                                           
                                              <select class="form-control" id="sort_assigned">
                                                  <option value="">un assigned</option>
                                                  <?php foreach($staffs as $skey => $svalue):?>
                                                    <option value="<?php echo $svalue->id; ?>" <?php echo ($svalue->id == $assigned_to_sess)?'selected' : ''; ?>><?php echo $svalue->fname." ".$svalue->lname;?></option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </div>


                                           <a href="javascript:void(0)" class="view_by"><button class="btn" ><i class="icon-refresh"></i> View By</button></a>
                                      </div>
                                </div>
                            
                            <div class="col-md-3">
                                 <div class="actions">
                                           
                                            <a href="javascript:void(0)" class="item-data-refresh"><button class="btn" style="margin-bottom:5px"  ><i class="icon-refresh"></i> Refresh</button></a>
                                       </div> 
                                       </div>                 
                              </div>
                              <div class="box-content box-no-padding">
                                 <div class="responsive-table">
                                    <div class="scrollable-area">
                                       
                                       <table class="table table-bordered table-striped manage_cc_items" style="margin-bottom:0;">

                                          <thead>
                                            <tr> 
                                                <th>No:</th>
                                                <th>Created</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Airline & Flight</th>
                                                <!-- <th>Provider</th> -->
                                                <th>Purchased Price</th>
                                                <th>Sales Price</th>
                                                <th>Status</th>
                                                <th>Assigned To</th>
                                                <th>Action</th>
                                            </tr>
                                          </thead>
                                        
                                          <tbody>
                                               <?php foreach($result as $rkey => $rvalue) :
                                                $flightData = json_decode($rvalue->input_json);
                                                $departures = json_decode($rvalue->departures);
                                                // echo '<pre>',print_r($rvalue->input_json);exit; 
                                               ?> 
                                               <tr>
                                               <td><?php echo ($rkey+1);?></td>
                                               <td><?php echo $rvalue->created_date;?></td>
                                               <td><?php echo $flightData->flight_origin;?></td>
                                               <td><?php echo $flightData->flight_destination;?></td>
                                               <td><?php 
                                                    foreach ($departures as $dkey => $dvalue) {
                                                       echo "<span>".$dvalue->flight_no." - ".$dvalue->airline_name." - ".$dvalue->operating_airline_name."</span>";
                                                    }
                                                   ?>
                                               </td>
                                               <td><?php echo $rvalue->total_cost." ".$rvalue->currency;?></td>
                                               <td><?php echo $rvalue->api_cost." ".$rvalue->currency;?></td>
                                               <td>

                                               <a class="change_hotel_status label label-success assign-staff" href="javascript:void(0);" data-status="0"  data-id=""><?php echo $rvalue->status_txt; ?></a>
                                              
                                               <!--  <a class="change_hotel_status label label-danger" href="javascript:void(0);" data-status="1" data-id="" ><i class="icon-ok"></i>InActive</a> -->
                                                </td>
                                                <td>
                                                  <?php
                                                  $possibleStatus = json_decode($rvalue->possible_update_status,true);

                                                    if(!is_array($possibleStatus)){
                                                      $possibleStatus = array();
                                                    } 

                                                    if($rvalue->assigned_to == "" && in_array(9, $possibleStatus)):               
                                                    ?>
                                                    <a class="btn btn-primary btn-xs has-tooltip mrgn_top action_icons assign-staff" data-placement="top" title="" href="javascript:void(0);" data-original-title="Assign Staff" data-toggle="modal" data-target="#assignstaffModal" data-item="<?php echo $rvalue->item_id;?>"><i class="icon-user"></i></a>
                                                  <?php else :?>
                                                    <a class="change_hotel_status label label-success assign-staff" href="javascript:void(0);" data-status="0"  data-id=""><?php echo @$get_staff[$rvalue->assigned_to]; ?></a>
                                                  <?php endif; ?>  
                                                </td>
                                                <td>
                                                  <a class="btn btn-primary btn-xs has-tooltip mrgn_top action_icons assign-staff" data-placement="top" title="" href="<?php echo base_url($this->data['controller'].'/item_details/'.base64_encode($rvalue->item_id)); ?>" data-original-title="View Details"><i class="icon-eye"></i></a>
                                                </td>
                                            </tr>   
                                            <?php endforeach;?> 
                                         </tbody>
                                      </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                              </div>
                              </div>
                              </div>


                             <!-- <div role="tabpanel" class="tab-pane" id="emplist">
                                <div class="intabs">
                                 <div class="rowit">
                                   <div class="box bordered-box orange-border" style="margin-bottom:0;">
                                      <div class="box-header blue-background">
                                         <div class="title">Staff List</div>
                                         <div class="actions">
                                            <a href="<?php echo base_url($this->data['controller'].'/add_staff'.DEFAULT_EXT); ?>"> <button class="btn" style="margin-bottom:5px"  ><i class="icon-plus-sign"></i> Add New Staff </button></a>
                                         </div>                     
                                      </div>
                                      <div class="box-content box-no-padding">
                                         <div class="responsive-table">
                                            <div class="scrollable-area">
                                               <div class="actions" align="">
                                         </div>
                                               <table class="table table-bordered table-striped manage_cc_items" style="margin-bottom:0;">

                                                  <thead>
                                                    <tr> 
                                                        <th>No:</th>
                                                        <th>Name</th>
                                                        <th>Phone 1</th>
                                                        <th>Phone 2</th>
                                                    </tr>
                                                  </thead>
                                                
                                                  <tbody>
                                                    <?php foreach ($staffs as $sskey => $ssvalue): ?>
                                                      <tr>
                                                        <td><?php echo ($sskey+1); ?></td>
                                                        <td><?php echo $ssvalue->fname." ".$ssvalue->lname; ?></td>
                                                        <td><?php echo $ssvalue->phone1; ?></td>
                                                        <td><?php echo $ssvalue->phone2; ?></td>
                                                      </tr>
                                                    <?php endforeach; ?>
                                                 </tbody>
                                              </table>
                                            </div>
                                         </div>
                                      </div>
                                   </div>
                                  </div>
                                </div>
                             </div>
 -->
                             <div role="tabpanel" class="tab-pane" id="charterlist">
                                <div class="intabs">
                                 <div class="rowit">
                                   <div class="box bordered-box orange-border" style="margin-bottom:0;">
                                      <div class="box-header blue-background">
                                         <div class="title">Charter Companies List</div>
                                         <div class="actions">
                                            <a href="<?php echo base_url($this->data['controller'].'/add_company'.DEFAULT_EXT); ?>"> <button class="btn" style="margin-bottom:5px"  ><i class="icon-plus-sign"></i> Add New Company </button></a>
                                         </div>                     
                                      </div>
                                      <div class="box-content box-no-padding">
                                         <div class="responsive-table">
                                            <div class="scrollable-area">
                                               <div class="actions" align="">
                                         </div>
                                                <table class="table table-bordered table-striped manage_company_list" style="margin-bottom:0;" id="manage_company_list">

                                                  <thead>
                                                    <tr>
                                                      <th>No:</th>
                                                      <th>Company</th>
                                                      <th>Url</th>
                                                      <th>Username</th>
                                                      <th>Password</th>
                                                      <th>Contact Name</th>
                                                      <th>Support Phone</th>
                                                      <th>Other Phone</th>
                                                      <th>Comment</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody><tr class="odd text-center"><td valign="top"  colspan="9" class="dataTables_empty"><i class="bar_loading"></i></td></tr></tbody>
                                                </table>    
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
					</div>
				 <?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
    <script>
//    jQuery(function () {
//     jQuery('#myTab a:last').tab('show')
// })
</script>
		<?php $this->load->view("common/scripts");?>
    
<!-- Modal -->
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
  </div>
	</body>
</html>