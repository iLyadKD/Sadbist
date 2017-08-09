<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Homepage_Model extends CI_Model {

	public function __construct()
	{	
		parent::__construct();
	}

	public function get_all_sliders($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `home_sliders`". $where. $order_by;
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

	public function get_slider_image($id)
	{
		$query = "SELECT * FROM `home_sliders` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function add_slider_image($data)
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
		$query = "INSERT INTO `home_sliders` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function add_home_deals($data)
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
		$query = "INSERT INTO `home_deals` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function update_slider_image($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `home_sliders` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function delete_slider_image($id)
	{
		$query = "DELETE FROM `home_sliders` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
	
	public function get_all_deals($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `home_deals`". $where. $order_by;
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
	
	public function get_deals_by_id($id)
	{
		$query = "SELECT * FROM `home_deals` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	public function update_deals($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `home_deals` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
	public function delete_deals($id)
	{
		$query = "DELETE FROM `home_deals` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
	
	public function add_tour_deals($data)
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
		$query = "INSERT INTO `home_tour_deals` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function get_all_tour_deals($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `home_tour_deals`". $where. $order_by;
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
	public function update_tour_deals($id, $data)
	{
		//pr($data);exit;
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `home_tour_deals` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
	public function delete_tour_deals($id)
	{
		$query = "DELETE FROM `home_tour_deals` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
	
	public function get_tour_deals_by_id($id)
	{
		$query = "SELECT * FROM `home_tour_deals` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	
	
	public function add_hotel_deals($data)
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
		$query = "INSERT INTO `home_hotel_deals` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function get_all_hotel_deals($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `home_hotel_deals`". $where. $order_by;
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
	public function update_hotel_deals($id, $data)
	{
		//pr($data);exit;
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `home_hotel_deals` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
	public function delete_hotel_deals($id)
	{
		$query = "DELETE FROM `home_hotel_deals` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
	
	public function get_hotel_deals_by_id($id)
	{
		$query = "SELECT * FROM `home_hotel_deals` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	
	
	
	
	public function add_news($data)
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
		$query = "INSERT INTO `home_news` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function get_all_news($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `home_news`". $where. $order_by;
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
	public function update_news($id, $data)
	{
		//pr($data);exit;
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `home_news` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
	public function delete_news($id)
	{
		$query = "DELETE FROM `home_news` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
	
	public function get_news_by_id($id)
	{
		$query = "SELECT * FROM `home_news` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	
	
	
	
	
	
	
	public function add_attraction($data)
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
		$query = "INSERT INTO `home_attractions` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function get_attraction_list($where = "", $order_by = "", $limit = "")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM `home_attractions`". $where. $order_by;
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
	public function update_attraction($id, $data)
	{
		//pr($data);exit;
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `home_attractions` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}
	
	public function delete_attraction($id)
	{
		$query = "DELETE FROM `home_attractions` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;	
	}
	
	public function get_attraction_by_id($id)
	{
		$query = "SELECT * FROM `home_attractions` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	
	
	
}