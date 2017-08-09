<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Privilege_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_privileges($where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM(SELECT * FROM `privileges` WHERE `valid` = '1' ORDER BY 
		`order_by`, `child_order`) AS `privileges`". $where. $order_by;
		$result = $this->db->query($query);
		$response["count"] = $result->num_rows();
		if($result->num_rows() > 0)
		{
			$response['count'] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response['result'] = $result->result_array();
		}
		return $response;
	}

	public function get_privilege($id)
	{
		$query = "SELECT * FROM `privileges` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_active_privileges($admin)
	{
		$query = "SELECT * FROM (SELECT `privileges`.`id`, `controller`, `icon`, `privilege`, `url`, `status`, `level`, `privilege_avail`, `is_parent`, `parent`, `privileges_custom`.`order_by`, `privileges_custom`.`main_order`, `parent_order`, `child_order`, `valid`, `default` FROM `privileges` INNER JOIN `privileges_custom` ON `privileges_custom`.`privilege_id` = `privileges`.`id` AND `admin_id` = '$admin') AS `privileges` WHERE `status` = '1' AND 
		`valid` = '1' ORDER BY `order_by`, `main_order`";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_parent_privileges()
	{
		$query = "SELECT * FROM `privileges` WHERE `status` = '1' AND 
		`valid` = '1' AND `parent` = '0' AND `default` <> '1' AND 
		`privilege_avail` = '1' ORDER BY `main_order`";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_admin_privileges($id)
	{
		$query = "SELECT `privileges` FROM `privileges_granted` WHERE `admin` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row()->privileges : false;
	}

	public function assign_privileges($admin, $privileges)
	{
		$query = "INSERT INTO `privileges_granted`(`admin`, `privileges`) VALUES 
		('$admin', '$privileges') ON DUPLICATE KEY UPDATE `privileges` = '$privileges'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function update($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `privileges` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function is_parent_exists($order_by, $level, $child_order)
	{
		$query = "SELECT * FROM `privileges` WHERE `order_by` = '$order_by' AND `level` = '$level' AND `child_order` = '$child_order'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_sibling_privilege($order_by, $level, $parent_order, $child_order)
	{
		$query = "SELECT * from (SELECT *, `main_order` AS `set_main_order` FROM `privileges` WHERE 
		`order_by` = '$order_by' AND `level` = '$level' AND `parent_order` = '$parent_order' AND 
		`child_order` = '$child_order') AS `a` UNION SELECT * FROM (SELECT *, `main_order` + 1 AS 
		`set_main_order` FROM `privileges` WHERE `order_by` = '$order_by' AND `level` >= '$level' 
		AND `child_order` <= '$child_order' ORDER BY `main_order` DESC) AS `b` LIMIT 0, 1";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_privilege($data)
	{
		$query = "SELECT `main_order` FROM `privileges` WHERE `order_by` = '".$data["order_by"]."' ORDER BY `main_order`";
		$result = $this->db->query($query);
		$data["main_order"] = $result->num_rows() > 0 ? $result->row()->main_order : "0";
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		if($data["main_order"] !== "0")
		{
			$query = "UPDATE `privileges` SET `main_order` = `main_order` + 1 WHERE `main_order` >= '".$data["main_order"]."'";
			$this->db->query($query);
		}
		$query = "UPDATE `privileges` SET `order_by` = `order_by` + 1 WHERE `order_by` >= '".$data["order_by"]."'";
		$this->db->query($query);
		$query = "INSERT INTO `privileges`($cols) VALUES ($vals)";
		$this->db->query($query);
		$ref_id = $this->db->insert_id();
		$adm_id = $this->data["admin_id"];
		$query = "INSERT INTO `privileges_custom` (`admin_id`, `privilege_id`, `order_by`, `main_order`) SELECT '$adm_id', `id`, `order_by`, `main_order` FROM `privileges` WHERE `id` = '$ref_id'";
		$this->db->query($query);
		return $ref_id;
	}

	public function add_subprivilege($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$exed = "";
		$query = "UPDATE `privileges` SET `main_order` = `main_order` + 1 WHERE `main_order` >= '".$data["main_order"]."'";
		$this->db->query($query);
		$query = "UPDATE `privileges` SET `is_parent` = '1', `url` = '' WHERE `order_by` = '".$data["order_by"]."' AND `level` = '".($data["level"] - 1)."' AND `child_order` = '".$data["parent_order"]."' LIMIT 1";
		$this->db->query($query);
		$query = "UPDATE `privileges` SET `child_order` = `child_order` + 1 WHERE `order_by` = '".$data["order_by"]."' AND `level` = '".$data["level"]."' AND `child_order` >= '".$data["child_order"]."' AND `parent_order` = '".$data["parent_order"]."' AND `parent` = '1'";
		$this->db->query($query);

		$query = "UPDATE `privileges` SET `parent_order` = `parent_order` + 1 WHERE `order_by` = '".$data["order_by"]."' AND `level` > '".$data["level"]."' AND `parent_order` > '".$data["parent_order"]."' AND `parent` = '1'";
		$this->db->query($query);
		$query = "INSERT INTO `privileges`($cols) VALUES ($vals)";
		$this->db->query($query);
		$ref_id = $this->db->insert_id();
		$adm_id = $this->data["admin_id"];
		$query = "INSERT INTO `privileges_custom` (`admin_id`, `privilege_id`, `order_by`, `main_order`) SELECT '$adm_id', `id`, `order_by`, `main_order` FROM `privileges` WHERE `id` = '$ref_id'";
		$this->db->query($query);
		return $ref_id;
	}

	public function update_privilege($id, $data)
	{
		
	}

	public function update_subprivilege($id, $data)
	{
		
	}

	//disable privilege
	// public function delete_privilege($id)
	// {
	// 	$query = "UPDATE `privileges` AS `p1`, `privileges` AS `p2` SET `p1`.`valid` = '0' 
	// 	WHERE `p1`.`order_by` = `p2`.`order_by` AND `p2`.`id` = '$id'";
	// 	$this->db->query($query);
	// 	return $this->db->affected_rows() > 0 ? true : false;
	// }

	//delete privilege permanently 
	public function delete_privilege($id)
	{
		$query = "SELECT * FROM `privileges` WHERE `id` = '$id'";
		$row = $this->db->query($query)->row();
		$order_by = $row->order_by;
		$level = $row->level;
		$child_order = $row->child_order;
		$parent_order = $row->parent_order;
		$main_order = $row->main_order;
		$result = false;
		if($row->is_parent === "1" && $level === "0")
		{
			$query = "SELECT count(*) AS `counts` FROM `privileges` WHERE `order_by` = '$order_by'";
			$counts = $this->db->query($query)->row()->counts;
			$query = "DELETE FROM `privileges` WHERE `order_by` = '$order_by'";
			$result = $this->db->query($query);
			$query = "UPDATE `privileges` SET `order_by` = `order_by` - 1, `main_order` = `main_order` - '$counts' WHERE `order_by` > '$order_by'";
			$this->db->query($query);
		}
		elseif($row->is_parent === "1")
		{
			$result = false;
		}
		else
		{
			$query = "DELETE FROM `privileges` WHERE `id` = '$id'";
			$result = $this->db->query($query);
			// $query = "UPDATE `privileges` SET `parent_order` = `parent_order` - 1 WHERE `order_by` = '$order_by' AND `level` > '$level' AND `parent_order` > '$child_order'";
			// $this->db->query($query);
			// $query = "UPDATE `privileges` SET `child_order` = `child_order` - 1 WHERE `order_by` = '$order_by' AND `level` = '$level' AND `parent_order` = '$parent_order' AND `child_order` >= '$child_order'";
			// $this->db->query($query);
			$query = "UPDATE `privileges` SET `main_order` = `main_order` - 1 WHERE `main_order` >= '$main_order'";
			$this->db->query($query);
		}
		return $result;
	}

	public function reorder_privileges($from, $to, $length, $main_from, $main_to, $admin)
	{
		$query = "UPDATE `privileges_custom` SET `order_by` = '7777777' WHERE `order_by` = '$from' AND `admin_id` = '$admin'";
		$this->db->query($query);
		if($this->db->affected_rows() > 0)
		{
			if($from > $to)
			{
				$query = "UPDATE `privileges_custom` SET `order_by` = `order_by` + '1' WHERE `order_by` BETWEEN '$to' AND '$from' AND `admin_id` = '$admin'";
				$this->db->query($query);
				if($this->db->affected_rows() > 0)
				{
					$query = "UPDATE `privileges_custom` SET `order_by` = '$to' WHERE `order_by` = '7777777' AND `admin_id` = '$admin'";
					$this->db->query($query);
				}
			}
			else
			{
				$query = "UPDATE `privileges_custom` SET `order_by` = `order_by` - '1' WHERE `order_by` BETWEEN '$from' AND '$to' AND `admin_id` = '$admin'";
				$this->db->query($query);
				if($this->db->affected_rows() > 0)
				{
					//, `main_order` = ($main_to + (`main_order` - $main_from))
					$query = "UPDATE `privileges_custom` SET `order_by` = '$to' WHERE `order_by` = '7777777' AND `admin_id` = '$admin'";
					$this->db->query($query);
				}
			}
		}
		return $this->db->affected_rows() > 0 ? true : false;
	}
}