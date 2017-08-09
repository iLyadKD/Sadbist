<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Support_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function subjects_list()
	{
		$query = "SELECT * FROM `support_ticket_subjects`";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function is_subject_exists($data)
	{
		$condition = "";
		foreach ($data as $key => $value)
			$condition .= "`$key` = '$value' OR ";
		$condition = substr_replace($condition, "", -4);
		$query = "SELECT * FROM `support_ticket_subjects` WHERE $condition";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function add_subject($data)
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
		$query = "INSERT INTO `support_ticket_subjects` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function delete_subject($id)
	{
		$query = "DELETE FROM `support_ticket_subjects` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function add_support_ticket($st_data, $sth_data)
	{
		$cols = "";
		$vals = "";
		foreach ($st_data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `support_ticket` ($cols) VALUES ($vals)";
		$this->db->query($query);
		$sth_data["id"] = $this->db->insert_id();
		$cols = "";
		$vals = "";
		foreach ($sth_data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `support_ticket_history` ($cols) VALUES ($vals)";
		$result = $this->db->query($query);
		return $result !== 0 ? $sth_data["id"] : false;
	}

	public function get_inbox_tickets($condition, $where, $order_by, $limit)
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `support_ticket`.*, `support_ticket_subjects`.`subject` 
			AS `st_subject`, `admin`.`email_id`, `admin`.`firstname`, `admin`.`lastname` FROM 
			`support_ticket` INNER JOIN `support_ticket_subjects` ON `support_ticket`.`subject` = 
			`support_ticket_subjects`.`id` INNER JOIN `admin` ON `support_ticket`.`user_id_from` = 
			`admin`.`id` AND `user_type_from` = '".ADMIN_USER."' WHERE `support_ticket`.`status` = '1' 
			AND `user_type_to` = '".$condition["user_type"]."' AND `user_id_to` = 
			'".$condition["user_id"]."' UNION SELECT `support_ticket`.*, 
			`support_ticket_subjects`.`subject` AS `st_subject`, `b2c`.`email_id`, `b2c`.`firstname`, 
			`b2c`.`lastname` FROM `support_ticket` INNER JOIN 
			`support_ticket_subjects` ON `support_ticket`.`subject` = `support_ticket_subjects`.`id` 
			INNER JOIN `b2c` ON `support_ticket`.`user_id_from` = `b2c`.`id` AND 
			`user_type_from` = '".B2C_USER."' WHERE `support_ticket`.`status` = '1' AND `user_type_to` = 
			'".$condition["user_type"]."' AND `user_id_to` = '".$condition["user_id"]."') AS 
			`support_tickets`". $where. $order_by;
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

	public function get_sent_tickets($condition, $where, $order_by, $limit)
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT `support_ticket`.*, `support_ticket_subjects`.`subject` 
			AS `st_subject`, `admin`.`email_id`, `admin`.`firstname`, `admin`.`lastname` FROM 
			`support_ticket` INNER JOIN `support_ticket_subjects` ON `support_ticket`.`subject` = 
			`support_ticket_subjects`.`id` INNER JOIN `admin` ON `support_ticket`.`user_id_to` = 
			`admin`.`id` AND `user_type_to` = '".ADMIN_USER."' WHERE `support_ticket`.`status` = '1' 
			AND `user_type_from` = '".$condition["user_type"]."' AND `user_id_from` = 
			'".$condition["user_id"]."' UNION SELECT `support_ticket`.*, 
			`support_ticket_subjects`.`subject` AS `st_subject`, `b2c`.`email_id`, `b2c`.`firstname`, 
			`b2c`.`lastname` FROM `support_ticket` INNER JOIN 
			`support_ticket_subjects` ON `support_ticket`.`subject` = `support_ticket_subjects`.`id` 
			INNER JOIN `b2c` ON `support_ticket`.`user_id_to` = `b2c`.`id` AND 
			`user_type_to` = '".B2C_USER."' WHERE `support_ticket`.`status` = '1' AND `user_type_from` =
			'".$condition["user_type"]."' AND `user_id_from` = '".$condition["user_id"]."') AS 
			`support_tickets`". $where. $order_by;
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

	public function get_closed_tickets($condition, $where, $order_by, $limit)
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT * FROM (SELECT * FROM (SELECT `support_ticket`.*, `support_ticket_subjects`.`subject` AS 
			`st_subject`, `email_id`, `firstname`, `lastname` FROM `support_ticket` INNER JOIN 
			`support_ticket_subjects` ON `support_ticket`.`subject` = `support_ticket_subjects`.`id` 
			INNER JOIN `admin` ON IF(`support_ticket`.`user_type_from` = '".SUPER_ADMIN_USER."', 
			`admin`.`id` = `support_ticket`.`user_id_to` AND `support_ticket`.`user_type_to` = 
			'".ADMIN_USER."', `admin`.`id` = `support_ticket`.`user_id_from` AND 
			`support_ticket`.`user_type_from` = '".ADMIN_USER."') WHERE `support_ticket`.`status` = '0' 
			AND IF('".SUPER_ADMIN_USER."' IN (`support_ticket`.`user_type_from`, 
			`support_ticket`.`user_type_to`), `su_op` = '0', `ou_op` = '0') 
			AND((`support_ticket`.`user_id_to` = '".$condition["user_id"]."' AND 
			`support_ticket`.`user_type_to` = '".$condition["user_type"]."') OR 
			(`support_ticket`.`user_id_from` = '".$condition["user_id"]."' AND 
			`support_ticket`.`user_type_from` = '".$condition["user_type"]."')) UNION
			SELECT `support_ticket`.*, `support_ticket_subjects`.`subject` AS 
			`st_subject`, `email_id`, `firstname`, `lastname` FROM `support_ticket` INNER JOIN 
			`support_ticket_subjects` ON `support_ticket`.`subject` = `support_ticket_subjects`.`id` 
			INNER JOIN `b2c` ON IF(`support_ticket`.`user_type_from` = '".SUPER_ADMIN_USER."', 
			`b2c`.`id` = `support_ticket`.`user_id_to` AND `support_ticket`.`user_type_to` = 
			'".B2C_USER."', `b2c`.`id` = `support_ticket`.`user_id_from` AND 
			`support_ticket`.`user_type_from` = '".B2C_USER."') WHERE `support_ticket`.`status` = '0' 
			AND IF('".SUPER_ADMIN_USER."' IN (`support_ticket`.`user_type_from`, 
			`support_ticket`.`user_type_to`), `su_op` = '0', `ou_op` = '0') 
			AND((`support_ticket`.`user_id_to` = '".$condition["user_id"]."' AND 
			`support_ticket`.`user_type_to` = '".$condition["user_type"]."') OR 
			(`support_ticket`.`user_id_from` = '".$condition["user_id"]."' AND 
			`support_ticket`.`user_type_from` = '".$condition["user_type"]."'))) AS `support_tickets` 
			ORDER BY `last_reply`) AS `support_tickets`". $where. $order_by;
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

	public function get_st_history($id, $limit = "LIMIT 0, 2")
	{
		$response = array("count" => 0, "result" => array());
		$query = "SELECT `support_ticket`.`id`, `status`, `support_ticket`.`status`, `image`, `firstname`, `lastname`, `support_ticket_history`.`user_type_from`, 
		`support_ticket_history`.`user_id_from`, `support_ticket_history`.`user_type_to`, 
		`support_ticket_history`.`user_id_to`, `message`, `attachment`, 
		`replied_on` FROM( SELECT `support_ticket`.`id`, `support_ticket`.`status`, `email_id`, `firstname`, 
		`lastname`, '' AS `image` FROM `support_ticket` INNER JOIN `admin` ON (`user_id_from` = 
		`admin`.`id` AND `user_type_from` IN ('".SUPER_ADMIN_USER."', '".ADMIN_USER."')) AND 
		`support_ticket`.`id` = '$id' UNION SELECT `support_ticket`.`id`, `support_ticket`.`status`, 
		`email_id`, `firstname`, `lastname`, `image_path` AS `image` FROM `support_ticket` 
		INNER JOIN `b2c` ON (`user_id_from` = `b2c`.`id` AND `user_type_from` = '".B2C_USER."') 
		AND `support_ticket`.`id` = '$id') AS `support_ticket` INNER JOIN `support_ticket_history` ON 
		`support_ticket_history`.`id` = `support_ticket`.`id` ORDER BY `replied_on` DESC ";
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

	public function reply_support_ticket($id, $st_data, $sth_data)
	{
		$set = "";
		foreach ($st_data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `support_ticket` SET ".$set." WHERE `id` = '$id'";
		$this->db->query($query);
		$sth_data["id"] = $id;
		$cols = "";
		$vals = "";
		foreach ($sth_data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `support_ticket_history` ($cols) VALUES ($vals)";
		return $this->db->query($query);
	}

	public function close_ticket($id)
	{
		$query = "UPDATE `support_ticket` SET `status` = '0' WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function remove_ticket($id, $user_type)
	{
		$query = "SELECT * FROM `support_ticket` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		$result = $result->num_rows() > 0 ? $result->row() : false;
		if($result !== false)
		{
			if($result->ou_op === "0" && $result->su_op === "0")
			{
				if($user_type === SUPER_ADMIN_USER)
				{
					$query = "UPDATE `support_ticket` SET `su_op` = '1' WHERE `id` = '$id'";
					return $this->db->query($query);
				}
				if($user_type === ADMIN_USER)
				{
					$query = "UPDATE `support_ticket` SET `ou_op` = '1' WHERE `id` = '$id'";
					return $this->db->query($query);
				}
			}
			else
			{
				if(($result->su_op === "0" && $user_type === SUPER_ADMIN_USER) || ($result->ou_op === "0" && $user_type === ADMIN_USER))
				{
					$query = "SELECT `attachment` FROM `support_ticket_history` WHERE `id` = '$id'";
					$result = $this->db->query($query);
					$attachments = $result->num_rows() > 0 ? array_filter($result->result_array()) : array();
					$query = "DELETE FROM `support_ticket` WHERE `id` = '$id'";
					$this->db->query($query);
					return $this->db->affected_rows() > 0 ? (count($attachments) > 0 ? $attachments : true) : false;
				}
			}
		}
		return false;
	}
}