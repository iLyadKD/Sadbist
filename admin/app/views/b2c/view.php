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
											<i class="icon-male"></i>
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
												<li>
												 <a href="<?php echo base_url($this->data['controller'].DEFAULT_EXT); ?>"><?php echo $this->data["page_main_title"]; ?></a>
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
								<div class="col-sm-3 col-lg-2">
									<div class="box">
										<div class="box-content">
											<img class="img-responsive" src="<?php $image_path = $user->image_path !== '' ? $user->image_path : B2C_DEFAULT_IMG; echo upload_url($image_path); ?>" />
										</div>
									</div>
								</div>
								
								<div class="col-sm-9 col-lg-10">
									<div class="box">
										<div class="box-content box-double-padding">
											<form class="form">
												<fieldset>
													<div class="col-sm-4">
														<div class="lead">
															<i class="icon-male text-contrast"></i>
															Personal Info
														</div>
													</div>
													<?php
														$fname = '';
														$lname = '';
														$email = '';
														$contct_no = '';
														$country = '';
														$postal_code = '';
														$address = '';
														
														if($user->firstname != '')
															$fname = $user->firstname;
															
														if($user->lastname != '')
															$lname = $user->lastname;	
														if($user->email_id != '')
															$email = $user->email_id;
															
														if($user->contact_no != '')
															$contct_no = $user->contact_no;		
															
														if($user->country_text != '')
															$country = $user->country_text;
															
														if($user->postal_code != '')
															$postal_code = $user->postal_code;		
															
														if($user->address != '')
															$address = $user->address;
															
															
													?>
													<div class="col-sm-7 col-sm-offset-1">
														<?php if($fname != '' || $lname != '') { ?>
															<div class="form-group">
																<label>User Name: </label>
																<?php echo $fname." ".$lname; ?>
															</div>
														<?php } ?>
														<?php if($email != '') { ?>
															<div class="form-group">
																<label>E-mail: </label>
																<?php echo $email; ?>
															</div>
														<?php } ?>
														<hr class="hr-normal">
														<?php if($contct_no != '') { ?>
															<div class="form-group">
																<label>Contact No: </label>
																<?php echo $contct_no; ?>
															</div>
														<?php } ?>
														<?php if($country != '') { ?>
															<div class="form-group">
																<label>Country: </label>
																<?php echo $country; ?>
															</div>
														<?php } ?>
														<?php if($postal_code != 0) { ?>
															<div class="form-group">
																<label>Postal Code: </label>
																<?php echo $postal_code; ?>
															</div>
														<?php } ?>
														
														<?php if($address != '') { ?>
															<div class="form-group">
																<label>Address: </label>
																<?php echo $address; ?>
															</div>
														<?php } ?>
													</div>
												</fieldset>

												<div class="form-actions form-actions-padding">
													<div class="text-right">
														<a href="<?php echo base_url($this->data["controller"].DEFAULT_EXT); ?>"><button class="btn btn-primary" type="button">
																<i class="icon-reply"></i>
																Go Back
															</button></a>
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
