<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "datatables/jquery.dataTables.min";
		$include["css"][] = "datatables/dataTables.tableTools.min";
		$this->load->view("common/header", $include);
		
	?>
		<style type="text/css">$("tfoot input") {
    display: table-header-group;
}</style>
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
											<span>Vendor List</span>
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
												<li class="active">Vendors</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<?php 
									if($this->session->flashdata('message')){
										$message = $this->session->flashdata('message');
									?>
									<div class="alert alert-block alert-success">
									<a href="#" data-dismiss="alert" class="close">×</a>
									<h4 class="alert-heading">
									<?php echo $message; ?>
									</h4>
									</div>
									<?php } ?>
									<div class="box"><div class="notification"></div></div>
									<div class="box bordered-box orange-border" style="margin-bottom:0;">
										<div class="box-header blue-background">
											<div class="title">Vendors</div>
											<div class="actions">
												<a href="<?php echo base_url($this->data['controller'].'/create_vendor'); ?>"> <button class="btn" style="margin-bottom:5px"  ><i class="icon-plus-sign"></i> Add New Vendor </button></a>
											</div>                     
										</div>
										<div class="box-content box-no-padding">
											<div class="responsive-table">
												<div class="scrollable-area">
													<div class="actions" align="">
											</div>
													<table class="table table-bordered table-striped manage_vendors" style="margin-bottom:0;">
														<thead>
                                                            <tr>
																<th>Vendor ID</th>
																<th>Vendor name</th>
																<th>Contact person name</th>
																<th>Mobile no.</th>
																<th>Website Address</th>
																<!--<th>Status</th>-->
																<th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if($vendors){ $i=0; foreach ($vendors as  $rows) { $i++; ?>                                                 
															<tr>
																<td><?php echo $rows->vendor_id?></td>
																<td><?php echo $rows->vendor_name_en?></td>
																<td><?php echo $rows->contact_person_name_en?></td>
																<td><?php echo $rows->mobile_no?></td>
																<td><?php echo $rows->web_address?></td>
																<!--<td><?php if($rows->status==1){  ?>
																	<a class="change_tour_status label label-success" href="javascript:void(0);" data-status="0" data-id="<?php echo $rows->id; ?>" ><i class="icon-ok"></i>Active</a>
																	<?php } else {  ?>
																	<a class="change_tour_status label label-danger" href="javascript:void(0);" data-status="1"  data-id="<?php echo $rows->id; ?>" ><i class="icon-ok"></i>InActive</a>
																	<?php } ?>
																</td>-->
																<td>
																	<a href="<?php echo base_url('package/edit_vendor/'.base64_encode($rows->id));?>" class="btn btn-primary btn-xs has-tooltip " data-original-title="Edit"><i class="icon-edit"></i></a>
																	<a href="#" class="btn btn-primary btn-xs has-tooltip delete_vendor" data-id="<?php echo base64_encode($rows->id);?>" data-original-title="Delete"><i class="icon-remove"></i></a>
																</td>
															</tr>
							                              <?php } } ?>
							                            </tbody>

														
														</table>
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
	</body>
</html>