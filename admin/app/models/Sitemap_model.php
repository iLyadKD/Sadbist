<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Sitemap_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_all_metatags($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `site_metatags`". $where. $order_by;
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

	public function is_valid_tagname($data)
	{
		$seo_names = $this->general->seo_names();
		if($data["tag_type"] === METATAG_TYPE_NAME)
		{
			$key_list = array_keys($seo_names["name"]);
			return in_array($data["name"], $key_list);
		}
		elseif($data["tag_type"] === METATAG_TYPE_HTTP)
		{
			$key_list = array_keys($seo_names["http-equiv"]);
			return in_array($data["name"], $key_list);
		}
		return false;
	}

	public function is_tag_exists($data, $except = null)
	{
		$except_qry = "";
		if($except !== null)
			$except_qry = " AND (`tag_type` <> '".$except["tag_type"]."' AND `name` <> '".$except["name"]."')";
		$query = "SELECT * FROM `site_metatags` WHERE (`tag_type` = '".$data["tag_type"]."' AND `name` = '".$data["name"]."')".$except_qry;
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? true : false;
	}

	public function add_metatag($data)
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
		$query = "INSERT INTO `site_metatags` ($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function get_metatag($id)
	{
		$query = "SELECT * FROM `site_metatags` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function update_metatag($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `site_metatags` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_metatag($id)
	{
		$query = "DELETE FROM `site_metatags` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_all_analytics($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `site_analytics`". $where. $order_by;
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

	public function is_analytics_exists($data, $except = array())
	{
		$condition = "";
		foreach ($data as $key => $value)
			$condition .= "`$key` = '$value' OR ";
		$condition = substr_replace($condition, "", -4);
		$e_condition = "";
		foreach ($except as $key => $value)
			$e_condition .= "`$key` <> '$value' OR ";
		$e_condition = substr_replace($e_condition, "", -4);
		$query = "SELECT * FROM `site_analytics` WHERE ($condition)";
		if($e_condition !== "")
			$query .= "AND ($e_condition)";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_analytics($id)
	{
		$query = "SELECT * FROM `site_analytics` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_analytics($data)
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
		$query = "INSERT INTO `site_analytics` ($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function update_analytics($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `site_analytics` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_analytics($id)
	{
		$query = "DELETE FROM `site_analytics` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

}