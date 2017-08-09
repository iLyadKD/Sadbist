<!DOCTYPE html>
<html>
	<?php
		$include["css"][] = "bootstrap/bootstrap";
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
											<i class="icon-th"></i>
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
													<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.'labels'.DEFAULT_EXT.'?page='.$page); ?>">
														<?php echo $page_name; ?>&nbsp;Page
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
									<div class="box"></div><div class="notification"></div></div>
									<div class="box">
										<div class="box-header blue-background">
											<div class="title"><?php echo $this->data["page_title"]; ?></div>
										</div>

										<div class="box-content">
											<form class="form form-horizontal add_lang_page_label_form" action="javascript:void(0);" hyperlink="<?php echo $page; ?>" method="post">
												<div class="form-group">
													<label class="control-label col-sm-3" for="label">Page Name</label>
													<div class="col-sm-4 controls">
														<label class="form-control"><?php echo $page_name; ?></label>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="label">Label</label>
													<div class="col-sm-4 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" data-msg-required="Please enter label name" data-rule-pattern="^([a-zA-Z]([a-zA-Z0-9]_?)*)$" data-msg-pattern="Please enter valid label" name="label" type="text" placeholder="Label name">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="english">English</label>
													<div class="col-sm-4 controls">
													<textarea tabindex="2" name="english" class="form-control" data-rule-required="true" data-msg-required="Please enter label in english" cols="70" rows="4" placeholder="Label in English"></textarea>
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-sm-3 required" for="farsi">Farsi</label>
													<div class="col-sm-4 controls">
													 <textarea tabindex="3" name="farsi" class="form-control" data-rule-required="true" data-msg-required="Please enter label in farsi" cols="70" rows="4" placeholder="Label in Farsi"></textarea> 
													</div>
												</div>
												
												<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.'labels'.DEFAULT_EXT.'?page='.$page); ?>"><button tabindex="4" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="5" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
																Add Label
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