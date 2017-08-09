<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "select2/select2";
		$include["css"][] = "light-theme";
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
											<form class="form form-horizontal add_static_page_form" action="javascript:void(0);" method="post"> 
												<div class="form-group">
													<label class="control-label col-sm-3 required">Page Type</label>
													<div class="col-sm-8 controls">
														<select tabindex="1" autofocus="true" data-rule-required="true" data-msg-required="Please select page type" class="select2 static_page_types form-control" name="l_type" id="l_type">
														</select>
													</div>	   
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required">Page Title</label>
													<div class="col-sm-4 controls">
														<input autocomplete="OFF" tabindex="2" class="form-control" data-rule-required="true" name="title_en" placeholder="Page Title (English)" type="text">
													</div>	  
													<div class="col-sm-4 controls">
														<input autocomplete="OFF" tabindex="2" class="form-control" data-rule-required="true" name="title_fa" placeholder="Page Title (Farsi)" type="text">
													</div>	   
												</div>
			
											<div class="row">
												<label class="control-label col-sm-3 required">Content (English)</label>
													<div class="col-sm-8">
														<div class="box">
															<div class="box-content">
																<textarea tabindex="3" name="content_en" id="content_en" class="form-control ckeditor page-content" rows="10" data-rule-required="true"> 
																 </textarea>
															</div>
														</div>
													</div>
											</div>

											<div class="row">
												<label class="control-label col-sm-3 required">Content (Farsi)</label>
													<div class="col-sm-8">
														<div class="box">
															<div class="box-content">
																<textarea tabindex="3" name="content_fa" id="content_fa" class="form-control ckeditor fa_ckeditor page-content" rows="10" data-rule-required="true"> 
																 </textarea>
															</div>
														</div>
													</div>
											</div>

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.explode("_", $this->data['method'])[0].DEFAULT_EXT); ?>"><button tabindex="4" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="5" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Add Page
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
					</div>
					<?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>