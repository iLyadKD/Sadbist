<!--
	##################################################################
	This file contains shortcut menu items displayed in the dashboard.
	################################################################## 
-->

<div class="row box box-transparent dashboard_menus hide" hyperlink="<?php echo $this->encrypt->encode($this->data['admin_id']); ?>">
	<?php
		$icon_label = "";
		$privilege_label = "";
		$url_label = "";
		$order_by = "";
		foreach ($this->data["privileges"] as $privilege)
		{
			if($privilege->order_by === "1" || ($order_by === "" && $privilege->parent !== "0"))
				continue;
			if(($this->data["admin_type"] === SUPER_ADMIN_USER || ($privilege->parent === "1" && $order_by !== "" && $order_by === $privilege->order_by) || ($this->data["my_privileges"] !== false && $this->data["my_privileges"] !== null && in_array($privilege->id, $this->data["my_privileges"]))))
			{
				$icon_label = $icon_label === "" ? $privilege->icon : $icon_label;
				$privilege_label = $privilege_label === "" ? $privilege->privilege : $privilege_label;
				if(($order_by === "" && $privilege->url !== "") || ($order_by !== "" && $privilege->parent === "1"))
					$url_label = $privilege->url !== "" ? $privilege->url.DEFAULT_EXT : "";
				if($privilege->url === "" && $privilege->is_parent === "1")
					$order_by = $privilege->order_by;
				if ($icon_label !== "" && $privilege_label !== "" &&	$url_label !== "")
				{ ?>
				<div class="col-xs-4 col-sm-2" id="top_header_<?php echo $privilege->main_order; ?>">
					<div class="box-quick-link blue-background">
						<a href="<?php echo base_url($url_label); ?>">
							<div class="header">
								<div class="icon-<?php echo $icon_label; ?>"></div>
							</div>
							<div class="content"><?php echo $privilege_label; ?></div>
						</a>
					</div>
				</div>
		<?php
					$icon_label = "";
					$privilege_label = "";
					$url_label = "";
					$order_by = "";
				}
			}
		} ?>
</div>
