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
			right: 80px;
			top: 166px;
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
											<form class="form form-horizontal update_tour_deals" action="javascript:void(0);" method="post" hyperlink="<?php echo $tour_deals_id;?>">
												<!--<input type="hidden" name="tour_deals_id" value="<?php echo $tour_deals_id;?>">-->
												<div class="form-group">
													<label class="control-label col-sm-3 required">Deals text(En)</label>
													<div class="col-sm-8 controls">
														<input  autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" value="<?php echo $tour_deals->content_en;?>" name="content_en" placeholder="ex:-Last minute deals" type="text" data-msg-required="Please enter deals content" >
													</div>
											
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required">Deals text(Fa)</label>
													<div class="col-sm-8 controls">
														<input autocomplete="off" tabindex="2" autofocus="true" class="form-control" data-rule-required="true" value="<?php echo $tour_deals->content_fa;?>" name="content_fa" placeholder="ex:-Last minute deals" type="text" data-msg-required="Please enter deals content" >
													</div>
											<img class="form-group col-sm-3 pull-left preview_img" src="<?php echo $tour_deals->image === '' ? asset_url(IMAGE_PATH.'default.png') : upload_url($tour_deals->image);?>" alt='No Image.' width="80" height="80">
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Select Image</label>
													<div class="col-sm-5 controls">
														<input hyperlink = "<?php echo $tour_deals->image;?>" autocomplete="off" tabindex="3" class="form-control filestyle" data-classButton="btn btn-primary" data-input="true"  data-buttonText="Select" data-rule-required="false" name="slider_image" type="file" data-msg-required="Please select image" accept="image/*" data-rule-accept="image/*" data-msg-accept="Please select image only.">
													</div>
												</div>
												
												
												
												<div class="sector_tour" >
													<div class="form-group">
														<label class="control-label col-sm-3">Select hotel</label>
														<div class="col-sm-5 controls">
															<select hyperlink = "<?php echo $master_id;?>" data-rule-required="true" data-msg-required="Please select any hotel"    autofocus="true" class="select2 form-control tour_list valid" id="tour_list" data-rule-required="true"  data-msg-required="Please select any tour">
																<input  type="hidden" value="<?php echo $tour_deals->tour_link;?>" class="valid" name='tour_link' >
																<input  type="hidden" value="<?php echo $tour_deals->master_id;?>" class="valid" name='master_id' >
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
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].'/'.'tour_deals'); ?>"><button tabindex="4" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="5" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Update Deals
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