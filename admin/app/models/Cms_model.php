<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class CMS_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	// get static pages
	public function get_page_types($id = "", $search = "", $page = "1")
	{
		$query = "SELECT `id`, `display` AS `text` FROM `footer_link_headers` WHERE `type` = '".STATIC_PAGE_TYPE."'";
		if($id !== "")
			$query .= " AND `id` = '$id'";
		if($search !== "")
			$query .= " AND `display` LIKE '$search%'";
		$total_page_types = $this->db->query($query)->num_rows();
		$page_types = array();
		if($total_page_types > 0)
		{
			$query .= "LIMIT ".(($page - 1) * 10).", 10";
			$result = $this->db->query($query);
			$page_types = $result->result();
		}
		return $total_page_types > 0 ? array("total" => $total_page_types, "results" => $page_types) : array("total" => "0", "results" => array());
	}

	// get all static pages
	public function get_all_page_types()
	{
		$query = "SELECT * FROM `footer_link_headers` WHERE `type` = '".STATIC_PAGE_TYPE."'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function is_page_type_exists($data)
	{
		$condition = "";
		foreach ($data as $key => $value)
			$condition .= "`$key` = '$value' OR ";
		$condition = substr_replace($condition, "", -4);
		$query = "SELECT * FROM `footer_link_headers` WHERE $condition";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function add_page_type($data)
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
		$query = "INSERT INTO `footer_link_headers` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function delete_page_type($id)
	{
		$query = "DELETE FROM `footer_link_headers` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_all_pages($where = '', $order_by = '', $limit = '')
	{
		$except = implode(",", array("\"".STATIC_ABOUT_SLUG."\""));
		$response = array("count" => 0, "result" => array());
		//$query = "SELECT * FROM (SELECT * FROM `pages` WHERE `slug` NOT IN($except)) AS `pages`". $where. $order_by;
		$query = "SELECT * FROM `pages` ". $where. $order_by;
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

	public function is_slug_exists($slug, $except = null)
	{
		$query = "SELECT * FROM `pages` WHERE `slug` = '$slug'";
		if($except !== null)
			$query .= " AND `slug` <> '$except'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? true : false;
	}

	public function new_page($data)
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
		$query = "INSERT INTO `pages` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_page($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `pages` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_page($id)
	{
		$query = "SELECT * FROM `pages` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function get_page_by_slug($slug)
	{
		$query = "SELECT * FROM `pages` WHERE `slug` = '$slug'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function delete_page($id)
	{
		$query = "DELETE FROM `pages` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_all_contact_details($where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `contact_details`". $where. $order_by;
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

	public function add_contact_detail($data)
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
		$query = "INSERT INTO `contact_details` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function get_contact_detail($id)
	{
		$query = "SELECT * FROM `contact_details` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function update_contact_detail($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `contact_details` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function set_default_contact_detail($id)
	{
		$query = "UPDATE `contact_details` SET `priority` = if(`id` = '$id', '1', '2')";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_contact_detail($id)
	{
		$query = "DELETE FROM `contact_details` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_all_contact_locations($where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `contact_locations`". $where. $order_by;
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

	public function add_contact_location($data)
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
		$query = "INSERT INTO `contact_locations` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_contact_location($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `contact_locations` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_contact_location($id)
	{
		$query = "SELECT * FROM `contact_locations` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		$query = "DELETE FROM `contact_locations` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? $result->row() : false;	
	}

	public function get_all_social_network($where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `social_network`". $where. $order_by;
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

	public function add_social_network($data)
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
		$query = "INSERT INTO `social_network` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_social_network($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `social_network` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_social_network($id)
	{
		$query = "SELECT * FROM `social_network` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function delete_social_network($id)
	{
		$query = "DELETE FROM `social_network` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	public function get_all_clientele($where = '', $order_by = '', $limit = '')
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `clientele`". $where. $order_by;
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

	public function add_clientele($data)
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
		$query = "INSERT INTO `clientele` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_clientele($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `clientele` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function get_clientele($id)
	{
		$query = "SELECT * FROM `clientele` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function delete_clientele($id)
	{
		$query = "DELETE FROM `clientele` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}

	// public function savecontact($data) {
	// 	$this->db->where('contact_id', 1); //we are hard coding the contact_id as there will be only single row for contact details..
	// 	$this->db->update('contact_details', $data);
	// }

	// public function currentAddress() {
	// 	return $this->db->get('contact_details');
	// }
	// public function getWhySkyzproDetails()
	// {
	// 	$this->db->select('*')->from('cms_whyskyzpro');
	// 	$q = $this->db->get();
	// 	if ($q->num_rows > 0) {
	// 		return $q->result();
	// 	}else
	// 	{
	// 		return false;
	// 	}
	// }
	// public function editWbws($id)
	// {
	// 	$this->db->select('*')->from('cms_whyskyzpro')->where('id',$id);
	// 	$q = $this->db->get();
	// 	if ($q->num_rows > 0) {
	// 		return $q->row();
	// 	}else
	// 	{
	// 		return false;
	// 	}
	// }
	// public function editWbws_save($data,$id)
	// {
	// 	$this->db->where('id',$id);
	// 	$this->db->update('cms_whyskyzpro',$data);
	// 	return true;
	// }
}
