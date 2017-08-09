<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Language_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_pages($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `lang_pages`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response["count"] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response["result"] = $result->result_array();
		}
		return $response;
	}

	public function get_all_pages($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `page` AS `text` FROM `lang_pages` WHERE `id` <> '0'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `page` LIKE '$search%'";
		$total_pages = $this->db->query($query)->num_rows();
		$pages = array();
		if($total_pages > 0)
		{
			$query .= "LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->db->query($query);
			$pages = $result->result();
		}
		return $total_pages > 0 ? array("total" => $total_pages, "results" => $pages) : array("total" => "0", "results" => array());
	}

	public function is_page_exists($page, $id = null)
	{
		if(!is_null($id))
			$query = "SELECT * FROM `lang_pages` WHERE `page` LIKE '$page' AND `id` = '$id'";
		else
			$query = "SELECT * FROM `lang_pages` WHERE `page` = '$page'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_page($data)
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
		$query = "INSERT INTO `lang_pages`($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_page($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `lang_pages` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_page($id, $all = true)
	{
		if(!$all)
		{
			$query = "UPDATE `lang_library` SET `page` = null WHERE `page` = '$id'";
			$this->db->query($query);
		}
		$query = "DELETE FROM `lang_pages` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_labels($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT `id`, `title_en`, `title_fa`, `label`, IFNULL(`page`, 'NA') AS 
		`page` FROM `lang_library`". $where. $order_by;
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
		{
			$response["count"] = $result->num_rows();
			$query .= $limit;
			$result = $this->db->query($query);
			$response["result"] = $result->result_array();
		}
		return $response;
	}

	public function is_label_exists($label, $id = null)
	{
		if(!is_null($id))
			$query = "SELECT * FROM (SELECT `lang_library`.`id`, `title_en`, `title_fa`, 
				`label`, `lang_library`.`page`, `lang_pages`.`page` AS `page_name` FROM 
				`lang_library` LEFT JOIN `lang_pages` ON `lang_library`.`page` = 
				`lang_pages`.`id`) AS `lang_library` WHERE `label` LIKE '$label' AND 
				`id` = '$id'";
		else
		$query = "SELECT * FROM (SELECT `lang_library`.`id`, `title_en`, `title_fa`, 
				`label`, `lang_library`.`page`, `lang_pages`.`page` AS `page_name` FROM 
				`lang_library` LEFT JOIN `lang_pages` ON `lang_library`.`page` = 
				`lang_pages`.`id`) AS `lang_library` WHERE `label` = '$label'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_label($data)
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
		$query = "INSERT INTO `lang_library`($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_label($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = ".(empty($value) ? "null" : "'".$value."'").", ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `lang_library` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_label($id)
	{
		$query = "DELETE FROM `lang_library` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
}