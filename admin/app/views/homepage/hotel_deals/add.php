<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
		$include["css"][] = "jquery-ui/jquery-ui";
		$this->load->view("common/header", $include);

	?>
	<style>
		.preview_img {
			right: 60px;
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
											<i class="icon-plus-sign"></i>
											<span><?php echo $this->data["page_title"]; ?></span>
										</h1>
										<div class="pull-right">
											<ul class="breadcrumb">
												<li>
													<a tabindex="-1" href="<?php echo base_url(); ?>">
														<i class="icon-bar-chart"></i>
													</a>
												</li>
												<li class="separator">
													<i class="icon-angle-right"></i>
												</li>
												<li>
												 <a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>"> <?php echo $this->data["page_main_title"]; ?></a>
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
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
										</div>

										<div class="box-content">
											<form class="form form-horizontal add_hotel_deals" action="javascript:void(0);" method="post">

												<div class="form-group">
											<img class="form-group col-sm-3 pull-left preview_img" src="<?php echo asset_url(IMAGE_PATH.'default.png');?>" alt='No Image.' width="80" height="80">
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Image</label>
													<div class="col-sm-5 controls">
														<input autocomplete="off" tabindex="3" class="form-control filestyle" data-classButton="btn btn-primary" data-input="true"  data-buttonText="Select" data-rule-required="true" name="slider_image" type="file" data-msg-required="Please select image" accept="image/*" data-rule-accept="image/*" data-msg-accept="Please select image only.">
													</div>
												</div>
												
												
												
												<div class="sector_tour" >
													<div class="form-group">
														<label class="control-label col-sm-3">Select hotel</label>
														<div class="col-sm-5 controls">
															<select data-rule-required="true" data-msg-required="Please select any hotel"    autofocus="true" class="select2 form-control hotel_list" id="hotel_list" data-rule-required="true"  data-msg-required="Please select any tour">
																<input  type="hidden" value="" class="valid" name='tour_link' >
																<input  type="hidden" value="" class="valid" name='master_id' >
																<input  type="hidden" value="" class="valid" name='info' >
															</select>
														</div>
													</div>
												</div>										
												
												
													</div>
												</div>
												
												
												
												
												

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].'/'.'hotel_deals'); ?>"><button tabindex="4" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="5" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Add Deals
															</button>
														</div>
													</div>
												</div>
											</form>

										</div>
									</div>
								</div>
							</div>
							
						</div>
				<?php $this->load->view("footer");?>
					</div>
					
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>