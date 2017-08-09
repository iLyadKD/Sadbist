<?php
/*
	##########################################
	This file contains admin panel side menu.
	##########################################
*/
$controller = $this->data["controller"]; 
$method = $this->data["method"]; 
?>

<nav class="main-nav-menu">
	<div class="navigation">
		<ul class="nav navigation_main_menu">
			<?php
				$last_level = 0;
				$default_level = 0;
				$current_order = "";
				foreach ($this->data["privileges"] as $privilege)
				{
					if (($this->data["admin_type"] === SUPER_ADMIN_USER) || ($privilege->order_by === "1") || ($current_order === $privilege->order_by) || ($privilege->privilege_avail === "1" && $this->data["my_privileges"] !== false && $this->data["my_privileges"] !== null && in_array($privilege->id, $this->data["my_privileges"])))
					{
						if((int)$privilege->level >= (int)$last_level)
						{
							if($privilege->is_parent === "1")
							{
			?>
								<li order-by="<?php echo $privilege->order_by; ?>"  main-order="<?php echo $privilege->main_order; ?>">
									<a tabindex="-1" href="javascript:void(0)" class="dropdown-collapse">
										<i class="icon-<?php echo $privilege->icon; ?>"></i>
										<span><?php echo $privilege->privilege; ?></span>
										<i class="icon-angle-down angle-down"></i>
									</a>
									<ul class="nav">
			<?php
							}
							else
							{
								$rel_url = $privilege->url !== "" ? $privilege->url.DEFAULT_EXT : "";
			?>
								<li order-by="<?php echo $privilege->order_by; ?>" main-order="<?php echo $privilege->main_order; ?>" class="<?php if(strpos($privilege->controller, ','.$controller.',') !== false && $this->general->is_starts_with($controller.'/'.$method, $privilege->url)){echo 'active';} ?>">
									<a tabindex="-1" href="<?php echo base_url($rel_url); ?>">
										<i class="icon-<?php echo $privilege->icon; ?>"></i>
										<span><?php echo $privilege->privilege; ?></span>
									</a>
								</li>
			<?php
							}
						}
						else
						{
							$temp_level = $last_level;
							$last_level = $privilege->level;
							while((int)$temp_level > (int)$last_level)
							{
								echo "</li></ul>";
								$temp_level--;
							}
							if($privilege->is_parent === "1")
							{
			?>
								<li order-by="<?php echo $privilege->order_by; ?>" main-order="<?php echo $privilege->main_order; ?>">
									<a tabindex="-1" href="javascript:void(0)" class="dropdown-collapse">
										<i class="icon-<?php echo $privilege->icon; ?>"></i>
										<span><?php echo $privilege->privilege; ?></span>
										<i class="icon-angle-down angle-down"></i>
									</a>
									<ul class="nav">
			<?php
							}
							else
							{
								$rel_url = $privilege->url !== "" ? $privilege->url.DEFAULT_EXT : "";
			?>
								<li order-by="<?php echo $privilege->order_by; ?>" main-order="<?php echo $privilege->main_order; ?>" class="<?php if(strpos($privilege->controller, ','.$controller.',') !== false && $this->general->is_starts_with($controller.'/'.$method, $privilege->url)){echo 'active';} ?>">
									<a tabindex="-1" href="<?php echo base_url($rel_url); ?>">
										<i class="icon-<?php echo $privilege->icon; ?>"></i>
										<span><?php echo $privilege->privilege; ?></span>
									</a>
								</li>
			<?php
							}
						}
						$current_order = $privilege->order_by;
						$last_level = $privilege->level;
					}
				}
				while((int)$last_level > (int)$default_level)
				{
					echo "</li></ul>";
					$last_level--;
				}
			?>
		</ul>
	</div>
</nav>