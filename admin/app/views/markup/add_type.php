<!DOCTYPE html>
<html class="no-js">
	<?php
		$include["css"][] = "bootstrap/bootstrap";
		$include["css"][] = "light-theme";
		$include["css"][] = "select2/select2";
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
											<i class="icon-plus"></i>
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
												<li class="separator">
													<a tabindex="-1" href="<?php echo base_url($this->data['controller'].DIRECTORY_SEPARATOR.implode("_",array_slice(explode("_", $this->data['method']), 0, -1)).DEFAULT_EXT); ?>">
													<?php echo $this->data["page_main_title"]; ?>
														<i class="icon-angle-right"></i>
													</a>
												</li>
												<li class="active"><?php echo $this->data["page_title"]; ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="row todo-list">
										<div class="col-sm-12">
											<div class="box"><div class="notification"></div></div>
											<div class="box bordered-box orange-border">
												<div class="box-header blue-background">
													<div class="title"><?php echo $this->data["page_title"]; ?></div>
												</div>
												<div class="box-content">
											<form class="form form-horizontal add_markup_type_form" action="javascript:void(0);" method="post">
												<div class="form-group">
													<label class="control-label col-sm-3 required">Markup Name</label>
													<div class="col-sm-5 controls">
														<input autocomplete="off" tabindex="1" autofocus="true" class="form-control" data-rule-required="true" name="markup_name" placeholder="Markup Type Name"data-rule-pattern="^([a-zA-Z][a-zA-Z0-9 ]*)$" data-msg-pattern="Enter valid markup type name." type="text">
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-sm-3 required">Markup Priority</label>
													<div class="col-sm-5 controls">
														<input autocomplete="off" tabindex="2" class="form-control" data-rule-required="true" name="markup_priority" placeholder="Lowest value is high priority" type="text" data-rule-pattern="^([0-9]+)$" data-msg-pattern="Priority value should be greater than or equal to Zero">
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-3 control-label required" for="subject">Markup Category</label>
														<div class="col-md-5 controls">
															<select tabindex="3" class="select2 form-control set_markup_type_user" name="user_type" id="user_type">
											 				</select>
														</div>
												</div>

											<div class="form-actions">
													<div class="row">
														<div class="col-sm-9 col-sm-offset-3">
															<a tabindex="-1" href="<?php echo base_url($this->data['controller'].'/types'.DEFAULT_EXT); ?>"><button tabindex="4" class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
															<button tabindex="5" class="btn btn-primary" type="submit">
																<i class="icon-save"></i>
															 Add Markup Type
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
						</div>
					</div>
					<?php $this->load->view("footer");?>
				</div>
			</section>
		</div>
		<?php $this->load->view("common/scripts");?>
	</body>
</html>