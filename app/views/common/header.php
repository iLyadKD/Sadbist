
<header id="main-nav">
	<div class="top_head">
		<div class="container nopadding">
			<div class="pull-left">
				<div class="component">
					<a target="_blank" href="https://www.facebook.com/sadbist.sadbist" class="icon icon-mono facebook"><img src="<?php echo asset_url('images/facebook.png'); ?>"></a>
					<a target="_blank" href="https://www.linkedin.com/in/travel-sadbist-4b4863138
" class="icon icon-mono linkedin"><img src="<?php echo asset_url('images/Linkedin.png'); ?>"></a>
					<a target="_blank" href="https://plus.google.com/u/0/
" class="icon icon-mono googleplus"><img src="<?php echo asset_url('images/google.png'); ?>"></a>
					<a target="_blank" href="https://www.instagram.com/travel10020
" class="icon icon-mono instagram"><img src="<?php echo asset_url('images/Instagram.png'); ?>"></a>
					<a target="_blank" href="https://telegram.me/travell10020
" class="icon icon-mono telegram"><img src="<?php echo asset_url('images/Telegram.png'); ?>"></a>
					<a target="_blank" href="https://secure.skype.com/portal/overview
" class="icon icon-mono skype"><img src="<?php echo asset_url('images/skype.png'); ?>"></a>
				</div>
			</div>
			<div class="pull-right">
				<ul class="usermenu">
					<li>
						<div class="wrapofa">
							<a href="javascript:void(0);" class="topa dropdown-toggle" data-toggle="dropdown">
								<?php if($_SESSION['default_language']=='en'){ echo $this->data["default_currency"]; } 
							else{ if($_SESSION['default_currency']=="IRR"){ echo IRR_fCURRENCY;} else{ echo USDFAR_CURRENCY; } } ?>
								<b class="caret"></b></a>
							<ul class="dropdown-menu currencychange">
								<li><a href="javascript:void(0)" data-currency="USD" class="default_curr"><?php echo $this->lang->line("usd_caps"); ?></a></li>
								<li><a href="javascript:void(0)" data-currency="IRR" class="default_curr"><?php echo $this->lang->line("irr_caps"); ?></a></li>
							</ul>
						</div>
					</li>

					<li>
						<div class="wrapofa">
							<a href="javascript:void(0);" class="topa dropdown-toggle" data-toggle="dropdown"><img class="lazyload" src="<?php echo asset_url('images/'.$this->data['default_language'].'.png'); ?>" alt="" width="21" height="14" /> <b class="caret"></b></a>
							<ul class="dropdown-menu currencychange">
								<li><a href="javascript:void(0)" data-lang="en" class="default_lang"><img class="lazyload" src="<?php echo asset_url('images/en.png'); ?>" alt="" width="21" height="14" /></a></li>
								<li><a href="javascript:void(0)" data-lang="fa" class="default_lang"><img class="lazyload" src="<?php echo asset_url('images/fa.png'); ?>" alt="" width="21" height="14" /> </a></li>
							</ul>
						</div>
					</li>
					<?php if($this->data["user_id"] === null) { ?>
					<!-- <li><a href="<?php echo base_url('b2c/register'.DEFAULT_EXT); ?>"><i class="fa fa-user"></i> <?php echo $this->lang->line("not_a_member"); ?> <span class="regc"><?php echo $this->lang->line("login"); ?>/<?php echo $this->lang->line("register"); ?></span></a></li>
					<li> -->
					<li><a style="background: #e66035;" href="javascript:void(0);"  data-toggle="modal" data-target=".b2b_login_model"><i class="fa fa-user"></i> <span class="regc"><?php echo $this->lang->line("login"); ?> / <?php echo $this->lang->line("register"); ?></span></a></li>
					<li></li>
					<?php } else { 
								 $this->load->model('B2c_model');
								 $user_data = $this->B2c_model->get_b2c($this->data['user_id'],$this->data['default_language']);
								 $userNAme = ($user_data->firstname !== "") ? $user_data->firstname : $user_data->email_id;
					 ?>
						<li>
							<div class="wrapofa">
								<a href="javascript:void(0);" class="topa dropdown-toggle" data-toggle="dropdown"><!--<img class="lazyload" data-original="<?php $user_img = $this->data['user_img'] === '' ? 'admin2.png' : $this->data['user_img']; echo base_url("assets/images/".$user_img); ?>" alt="" width="21" height="14" />--> <?php echo $userNAme; ?> <b class="caret"></b></a>

								<ul class="dropdown-menu currencychange">
									<li><a href="<?php echo base_url($this->data['user_type_text'].'/edit_profile'.DEFAULT_EXT); ?>"><?php echo $this->lang->line("my_profile"); ?></a></li>
									<!--<li><a href="<?php echo base_url($this->data['user_type_text'].DEFAULT_EXT); ?>"><?php echo $this->lang->line("dashboard"); ?></a></li>
									<li><a href="<?php echo base_url($this->data['user_type_text'].'/settings'.DEFAULT_EXT); ?>"><?php echo $this->lang->line("settings"); ?></a></li>-->
									<li><a href="<?php echo base_url($this->data['user_type_text'].'/logout'.DEFAULT_EXT); ?>"><?php echo $this->lang->line("logout"); ?></a></li>
								</ul>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="nav_bar">
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-3 col-xs-4 nopadding">
					<div class="logo">
						<?php if($this->data["default_language"] === 'fa'):?>
							<a href="<?php echo base_url(); ?>"><img src="<?php echo asset_url('images/logo_farsi.png'); ?>" alt="10020.ir" /></a>
						<?php else: ?>
							<a href="<?php echo base_url(); ?>"><img src="<?php echo asset_url('images/logo.png'); ?>" alt="10020.ir" /></a>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="col-md-7 col-sm-7 col-xs-12 nopadding" style="text-align: center !important;">
			
					<div id="cssmenu" class="col-md-12">
						<ul>
						<li <?php echo (is_null($this->data["default_tab"]) || $this->data["default_tab"] === "") ? "class=\"home_li active\"" : "class=\"home_li\"";?> ><a href="<?php echo base_url(); ?>"><span><?php echo $this->lang->line("home"); ?></span></a></li>
							<li <?php echo ($this->data["default_tab"] === "flights") ? "class=\"flights_li toggle_menu active\"" : "class=\"flights_li toggle_menu\"";?> ><a href="<?php echo $this->data['method'] === 'index' && $this->data['controller'] === 'hotel' ? 'javascript:void(0);' : base_url('flights'); ?>"><span><?php echo $this->lang->line("flights"); ?></span></a></li>

							<li <?php echo ($this->data["default_tab"] === "hotels") ? "class=\"hotels_li toggle_menu active\"" : "class=\"hotels_li toggle_menu\"";?> ><a href="<?php echo $this->data['method'] === 'index' && $this->data['controller'] === 'hotel' ? 'javascript:void(0);' : base_url('hotels'); ?>"><span><?php echo $this->lang->line("hotels"); ?></span></a></li>

							<li <?php echo ($this->data["default_tab"] === "tours") ? "class=\"tours_li toggle_menu active\"" : "class=\"tours_li toggle_menu\"";?> ><a href="<?php echo $this->data['method'] === 'index' && $this->data['controller'] === 'hotel' ? 'javascript:void(0);' : base_url('tours'); ?>"><span><?php echo $this->lang->line("tours"); ?></span></a></li>

							<li><a href="<?php echo base_url('about-us'); ?>"><span><?php echo $this->lang->line("about_us"); ?></span></a></li>
							<li><a href="<?php echo base_url('contact-us'); ?>"><span><?php echo $this->lang->line("draw_rules"); ?></span></a></li>
                            <li><a href="<?php echo base_url('eshop-help'); ?>"><span><?php echo $this->lang->line("eshop_help"); ?></span></a></li>
						</ul>
					</div>
				
				</div>

                <div class="col-md-2 col-sm-2 col-xs-12 nopadding">
                    <ul style="float: left; margin: 10px 0 0 0px;">
                        <li class="cart contact_det">
                            <!-- <a href="#" class="toggle-nav" data-toggle="modal" data-target="#myModal2">
                	<img src="<?php echo base_url('assets/images/call.png');?>">
                </a>  -->
                            <div style="padding: 0;">
                                <span class="pc" style="font-size: 21px;"><i class="fa fa-phone"></i><span class="pc_num">۰۲۱ ۵۴۶۲ ۳۰۰۰</span></span>
                            </div>
                            <div>
                                <span class="pc phone_nu" style="">پشتیبانی ۲۴ ساعته</span>
                            </div>
                        </li>
                    </ul>
                </div>
			</div>
		</div>
	</div>
</header> 
<!--<span class="cart_icon"><i class="fa fa-cart-plus"></i></span>
-->
<div class="clearfix"></div>
