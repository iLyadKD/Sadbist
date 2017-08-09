<?php 
	/*This file contains all admin shortcut settings*/
?>

<header>
	<nav class="navbar navbar-default navbar-fixed-top">
		<a class="navbar-brand" tabindex="-1" href="<?php echo base_url('home'.DEFAULT_EXT); ?>">
			<img width="" height="30" class="logo" alt="Logo" src="<?php echo asset_url(IMAGE_PATH.'logo.png');?>" />
			<img width="21" height="21" class="logo-xs" alt="Logo" src="<?php echo asset_url(IMAGE_PATH.'logo_xs.png');?>" />
		</a>
		<a class="toggle-nav btn pull-left" tabindex="-1" href="javascript:void(0);">
			<i class="icon-reorder"></i>
		</a>
		
		<ul class="nav">
			<li class="dropdown dark user-menu">
				<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);" tabindex="-1">
					<i class="icon-user"></i>
					<span class="user-name"><?php echo $this->data['admin_email'];?></span>
					<b class="caret"></b>
				</a>

				
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo base_url('home/profile'.DEFAULT_EXT); ?>">
							<i class="icon-user"></i>
							Profile
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="change_my_pwd">
							<i class="icon-lock"></i>
							Change Password
						</a>
					</li>
				<?php if($this->data["admin_type"] === SUPER_ADMIN_USER){  //super-admin profile settings ?>
					<li>
						<a href="<?php echo base_url('home/settings'.DEFAULT_EXT); ?>">
							<i class="icon-cog"></i>
							Settings
						</a>
					</li>
				<?php }?>
					<li class="divider"></li>
					<li>
						<a href="<?php echo base_url('login/logout'.DEFAULT_EXT); ?>">
							<i class="icon-signout"></i>
							Sign out
						</a>
					</li>
				</ul>
			</li>
		</ul>

	</nav>
</header>