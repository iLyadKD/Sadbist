<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "datatables/jquery.dataTables.min";
		$include["css"][] = "datatables/dataTables.tableTools.min";
		$this->load->view("common/header", $include);
	?>

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
												<li class="active"><?php echo $this->data["page_title"]; ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<div class="box"><div class="notification"></div></div>
									<div class="box bordered-box orange-border" style="margin-bottom:0;">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
											<div class="actions">
												<a href="<?php echo base_url($this->data['controller'].'/create'); ?>"> <button class="btn" style="margin-bottom:5px"  ><i class="icon-plus-sign"></i> Add Package </button></a>
											</div>                     
										</div>
										<div class="box-content box-no-padding">
											<div class="responsive-table">
												<div class="scrollable-area">
													<div class="actions" align="">
											</div>
													<table class="data-table-column-filter table table-bordered table-striped " style="margin-bottom:0;">
														<thead>
                                                            <tr>
                                                              <th>S.No</th>
                                                              <th>Image</th>
                                                              <th>Package Name</th>
                                                              <th>Country</th>
                                                              <th>City</th>
                                                              <th>Status</th>
                                                              <th width="100">Langauge</th>
                                                              <th width="50">Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                              <?php if(!empty($package)){ $i=1;foreach($package as $rows){
                                $id =$rows->package_id;
                                ?>
                              <tr>
                                <?php  $url =  str_replace("admin/","",base_url());?>
                                <td> <img src="<?php  echo base_url(); ?>cdn/packages/<?php echo $rows->image;?>" width="50" height="50" ></td>
                                <td><?php echo $rows->name;?></td>
                                <td><?php echo $rows->cname;?></td>
                                <td><?php echo $rows->city;?></td>
                                <td>                                    <?php if ($rows->status == '1') { ?>
                                              <a class='btn btn-success btn-xs has-tooltip' data-placement='top' title='Active'>
                                                  <i class='icon-ok'></i>
                                              </a>
                                      <?php } else { ?>
                                              <a class='btn btn-danger btn-xs has-tooltip' data-placement='top' title='In-Active'>
                                                  <i class='icon-minus'></i>
                                              </a>
                                              <?php } ?>
                                          <select onchange="activate(this.value);">
                                  
                                          <option value="<?php echo base_url(); ?>admin/package/status/<?php echo $rows->package_id; ?>/1"      <?php if ($rows->status == '1') {  echo "selected"; }?> >Active</option>
                                          <option value="<?php echo base_url(); ?>admin/package/status/<?php echo $rows->package_id; ?>/0"<?php if ($rows->status == '0') {  echo "selected";} ?> >In-Active</option>
                                        </select>
                                     </td>
                                     <td>  <?php if($langauge) { foreach ($langauge as $rows) {  ?>
                                        <a href="<?php echo base_url().'admin/package/edit/'.strtolower($rows->name).'/'.$id;?>" data-original-title="Update English">
                                        <img src='<?php echo base_url();?>cdn/fn/images/lang/<?php echo $rows->image; ?>' width='16' height='16' style='border:1px solid #CCC'> <?php
                                } }?></td>
                                <td class="center ">         
                               <!--   <a class="btn btn-primary btn-xs has-tooltip" data-placement="top" title="" href="<?php echo base_url().'package/view/'.$id;?>" data-original-title="View Package">
                                  <i class="icon-search"></i>
                                  </a>
                                  <a class="btn btn-primary btn-sm has-tooltip" data-placement="top" title="" href="<?php echo base_url().'admin/package/edit/'.$id;?>" data-original-title="Edit Package">
                                  <i class="icon-edit"></i>  </a>  -->

                                  <a href="<?php echo base_url().'admin/package/delete/'.$id;?>" data-original-title="Delete Package" onclick="return confirm('Do you want delete this record');" class="btn btn-primary btn-xs has-tooltip"> 
                                  <i class="icon-remove"></i>
                                  </a>
                                </td>
                              </tr>
                              <?php $i++;}}?>
                              </tbody>
														<tfoot>
															<tr>
																<th>S.No</th>
                                                              <th>Image</th>
                                                              <th>Package Name</th>
                                                              <th>Country</th>
                                                              <th>City</th>
                                                              <th>Status</th>
                                                              <th width="100">Langauge</th>
                                                              <th width="50">Delete</th>
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
				 <?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>