<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Flight_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function get_cities($city, $lang = DEFAULT_LANG)
	{
		$cityCode = strtoupper($city);
		/*$this->db->select('CONCAT(city,",",country,",",airport_code) as label, airport_code as id');
		$this->db->from('flight_airports');
		$this->db->like('city', $city);
		$this->db->or_like('airport_code', $cityCode); 
		$this->db->or_like('country', $city);
		$this->db->order_by('airport_code');
		$this->db->limit(10);
		$query = $this->db->get();*/
		$query = "SELECT CONCAT('<span class=\"', IF(`is_city` = '1', 'fa fa-building', 'fa fa-plane'), '\"></span> <span class=\"search_content\">', `city_$lang`, ', ', `airport_$lang`, ', ', `country_$lang`, '</span>', '<span class=\"pull-right airport_code\">', `airport_code`, '</span>') AS label, CONCAT(`city_$lang`, ', ', `airport_$lang`, ', ', `country_$lang`, ', ', `airport_code`) AS value, `airport_code` AS `id` FROM `flight_airports` INNER JOIN `countries` ON `country` = `countries`.`id` WHERE `airport_code` LIKE '$cityCode%' OR `city_code` LIKE '$cityCode%' OR `city_en` LIKE '%$city%' OR `city_fa` LIKE '%$city%' ORDER BY FIELD(`city_code`, '$cityCode') DESC, FIELD(`airport_code`, '$cityCode') DESC, `is_city` DESC, `city_$lang` LIMIT 0, 10";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	public function get_dom_cities($lang = DEFAULT_LANG)
	{
		$query = "SELECT CONCAT('<span class=\"', IF(`is_city` = '1', 'fa fa-building', 'fa fa-plane'), '\"></span> <span class=\"search_content\">', `city_$lang`, ', ', `airport_$lang`, ', ', `country_$lang`, '</span>', '<span class=\"pull-right airport_code\">', `airport_code`, '</span>') AS label, CONCAT(`city_$lang`, ', ', `airport_$lang`, ', ', `country_$lang`, ', ', `airport_code`) AS value, `airport_code` AS `id` FROM `flight_airports` INNER JOIN `countries` ON `country` = `countries`.`id` AND `countries`.`id` = '".IRAN."' ORDER BY `city_code` DESC, `airport_code` DESC, `is_city` DESC";
		$result = $this->db->query($query);
		return $result->result();
	}

	public function get_all_flight_apis($service)
	{
		$query = "SELECT * FROM `api` WHERE `service` = '$service'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_flight_api($id)
	{
		$query = "SELECT * FROM `api` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	public function new_flight_search($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "null, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `search_temp` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function new_flight_book($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "null, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `bookings` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function new_flight_book_details($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "null, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `booking_details` ($cols) VALUES ($vals)";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function new_book_flight_cips($data_batch)
	{
		$cols = "";
		$vals = "";
		foreach ($data_batch as $batch_idx => $batch_val)
		{
			if($batch_idx === 0)
				foreach ($batch_val as $key => $value)
					$cols .= "`".$key."`, ";
			$vals .= "(";
			foreach ($batch_val as $key => $value)
				$vals .= is_null($value) ? "null, " : "'".$value."', ";
			$vals = substr_replace($vals, "", -2);
			$vals .= "), ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `flight_cips` ($cols) VALUES $vals";
		$this->db->query($query);
	}

	function get_selected_flight($id)
	{
		$query = "SELECT * FROM `flight_search_result` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function get_selected_flight_fsc($fsc)
	{
		$query = "SELECT * FROM `flight_search_result` WHERE `fare_source_code` = '$fsc'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function get_admin_b2c_markup($id)
	{
		$query = "SELECT `markups`.* FROM `markups` INNER JOIN `markup_types` ON `markup_types`.`id` = `mt_id` AND `markup_priority` = '0' AND (`markup_types`.`user_type` IS NULL OR `markup_types`.`user_type` = '".B2C_USER."') AND (`api` IS NULL OR `api` = '$id') WHERE `markups`.`status` = '1' ORDER BY `user_type` DESC LIMIT 0,1";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function get_booking($id)
	{
		$query = "SELECT * FROM `bookings` AS `bo`, `booking_details` AS `bd` WHERE `bo`.`id` = `bd`.`id` AND `bo`.`id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function get_booking_by_tickets($ids = array(), $status = null)
	{
		$ids = array_filter($ids);
		if(count($ids) > 0)
		{
			$ids = "'".implode("','", $ids)."'";
			$query = "SELECT * FROM `bookings` AS `bo`, `booking_details` AS `bd` WHERE `bo`.`id` = `bd`.`id` AND `bo`.`ticket_id` IN ($ids)";
			if(!is_null($status))
				$query .= " AND `bo`.`api_status` <> '$status'";
			$result = $this->db->query($query);
			return $result->num_rows() > 0 ? $result->result() : false;
		}
		return false;
	}

	function set_booking($ids, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$ids = is_array($ids) ? "'".implode("','", $ids)."'" : $ids = "'".$ids."'";
		$query = "UPDATE `bookings` AS `bo`, `booking_details` AS `bd` SET $set WHERE `bo`.`id` = `bd`.`id` AND `bo`.`id` IN ($ids)";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function clear_old_flight_search($service = FLIGHT)
	{
		$old_srch_time = date("Y-m-d H:i:s", strtotime("-1 days", strtotime(date("Y-m-d H:i:s"))));
		$query = "DELETE FROM `search_temp` WHERE `service` = '$service' AND `created` < '$old_srch_time'";
		$this->db->query($query);
		$query = "SELECT * FROM `search_temp`";
		$result = $this->db->query($query);
		if($result->num_rows() === 0)
		{
			$query = "SET FOREIGN_KEY_CHECKS = 0";
			$this->db->query($query);
			$query = "TRUNCATE TABLE `search_temp`";
			$this->db->query($query);
			$query = "TRUNCATE TABLE `flight_search_result`";
			$this->db->query($query);
			$query = "TRUNCATE TABLE `hotel_search_result`";
			$this->db->query($query);
			$query = "TRUNCATE TABLE `hotel_search_details`";
			$this->db->query($query);
			$query = "SET FOREIGN_KEY_CHECKS = 1";
			$this->db->query($query);
		}
		return true;
		
	}

	function current_flight_search($id)
	{
		$query = "SELECT * FROM `search_temp` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function update_current_search($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `search_temp` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function set_flight_session($data)
	{
		$cols = "";
		$vals = "";
		foreach ($data as $key => $value)
		{
			$cols .= "`".$key."`, ";
			$vals .= is_null($value) ? "null, " : "'".$value."', ";
		}
		$cols = substr_replace($cols, "", -2);
		$vals = substr_replace($vals, "", -2);
		$query = "INSERT INTO `flight_session` ($cols) VALUES ($vals) ON DUPLICATE KEY UPDATE `session` = '".$data["session"]."', `expires` = '".$data["expires"]."'";
		$this->db->query($query);
		return $this->db->insert_id();
	}

	function get_flight_session($id)
	{
		$query = "SELECT * FROM `flight_session` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function clear_flight_session($id)
	{
		$query = "DELETE FROM `flight_session` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->affected_rows() > 0 ? true : false;
	}

	function is_data_retreived($id)
	{
		$query = "SELECT IF(`created` > '".date("Y-m-d H:i:s", strtotime("-1005 minutes"))."', 'false', 'true') AS `expired` FROM `search_temp` WHERE `results` = '1' AND `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? ($result->row()->expired === "true" ? "expired" : true) : false;
	}

	public function flight_price_updated($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`$key` = '$value', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `flight_search_result` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function remove_invalid($id)
	{
		$query = "DELETE FROM `flight_search_result` WHERE `id` = '$id'";
		$this->db->query($query);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function new_flight_list($data)
	{
		$this->db->insert_batch("flight_search_result", $data);
		return true;
	}

	function append_to_flight_list($data)
	{
		$this->db->insert("flight_search_result", $data);
		return $this->db->insert_id();
	}

	function clear_flight_list($search_id, $api = null)
	{
		$more_condition = "";
		if(!is_null($api))
			$more_condition = " AND `api` = '$api'";
		$query = "DELETE FROM `flight_search_result` WHERE `search_id` = '$search_id'".$more_condition;
		$this->db->query($query);
		return true;
	}

	function get_flight_result($id, $filter, $flight_category = IORDI)
	{	
		$curDate = date("Y-m-d H:i:s", strtotime("-15 minutes"));
		$query = "SELECT count(`id`) AS `total`, max(`total_cost`) AS `max`, min(`total_cost`) AS `min`, GROUP_CONCAT(DISTINCT `airline`) AS `airlines`, GROUP_CONCAT(DISTINCT `airport`) AS `airports`, GROUP_CONCAT(DISTINCT `stops`) AS `stops`, GROUP_CONCAT(DISTINCT `currency`) AS `currency`, IF(GROUP_CONCAT(DISTINCT `flight_type`) = '".ROUNDTRIP."', 'true', 'false') AS `is_twoway`, IF(`valid_status` > '$curDate', 'false', 'true') AS `expired` FROM `flight_search_result` WHERE `search_id` = '$id' AND `results` <> '2'";
		// $query .= " AND `airline` <> 'IR'";
		//price 
		if(isset($filter["price_filter"]) && !empty($filter["price_filter"]))
		{
			$range = explode("-", $filter["price_filter"]);
			if(!empty($range))
			{
				$min = trim($range[0]);
				$max = trim($range[1]);
				$query .= " AND (`total_cost` BETWEEN '$min' AND '$max')";
			}
		}

		// stops 
		if(isset($filter["stops"]))
		{
			$stops = is_array($filter["stops"]) ? implode(",", $filter["stops"]) : $filter["stops"];
			if($stops !== "")
				$query .= " AND `stops` IN ($stops)";
		}

		// departure 
		if(isset($filter["departure_filter"]) && !empty($filter["departure_filter"]))
		{
			$range = explode("-", $filter["departure_filter"]);
			// convert hour integer to time()
			if(!empty($range))
			{
				$min = $this->general->minutes_to_hours(trim($range[0]));
				$max = $this->general->minutes_to_hours(trim($range[1]));
				$query .= " AND (`departure_tm` BETWEEN '$min' AND '$max' AND `results` = '0')";
			}
		}

		if($flight_category === IORDI)
		{
			// arrival 
			if(isset($filter["return_filter"]) && !empty($filter["return_filter"]))
			{
				$range = explode("-", $filter["return_filter"]);
				if(!empty($range))
				{
					$min = $this->general->minutes_to_hours(trim($range[0]));
					$max = $this->general->minutes_to_hours(trim($range[1]));
					$query .= " AND (`arrival_tm` BETWEEN '$min' AND '$max' AND `results` = '0')";
				}
			}
		}

		if($flight_category === IORDD)
		{
			// arrival 
			if(isset($filter["return_filter"]) && !empty($filter["return_filter"]))
			{
				$range = explode("-", $filter["return_filter"]);
				if(!empty($range))
				{
					$min = $this->general->minutes_to_hours(trim($range[0]));
					$max = $this->general->minutes_to_hours(trim($range[1]));
					$query .= " OR (`departure_tm` BETWEEN '$min' AND '$max' AND `results` = '1')";
				}
			}
		}

		// airlines 
		if(isset($filter["airlines"]))
		{
			$airlines = is_array($filter["airlines"]) ? implode("','", $filter["airlines"]) : $filter["airlines"];
			if(!empty($airlines))
				$query .= " AND `airline` IN ('$airlines')";
		}

		// airports 
		if(isset($filter["airports"]))
		{
			$airport_query = "";
			foreach ($filter["airports"] as $key => $value)
				$airport_query .= "`airport` LIKE '%,$value,%' OR ";
			$airport_query = substr_replace($airport_query, "", -4);
			if(!empty($airport_query))
					$query .= "AND ($airport_query)";
		}

		$query .= " ORDER BY `total_cost` ASC";

		$result = $this->db->query($query);
		// echo $this->db->last_query();
		return $result->num_rows() > 0 ? $result->row() : false;
	}

	function available_iflights($id, $filter, $lang = DEFAULT_LANG, $avoid_limit = "false")
	{
		$query = "SELECT `flight_search_result`.*, `airline_$lang` AS `airline_name`, if(`stops` = '0', 'Direct Stop Coach', if(`stops` = '1', 'One Stop Coach', 'Multi Stop Coach')) AS `stops_text` FROM `flight_search_result` INNER JOIN `flight_airlines` ON `airline` = `airline_code` WHERE `search_id` = '$id' AND `results` = '0'";
		//price 
		if(isset($filter["price_filter"]) && !empty($filter["price_filter"]))
		{
			$range = explode("-", $filter["price_filter"]);
			if(!empty($range))
			{
				$min = trim($range[0]);
				$max = trim($range[1]);
				$query .= " AND (`total_cost` BETWEEN '$min' AND '$max')";
			}
		}

		// stops 
		if(isset($filter["stops"]))
		{
			$stops = is_array($filter["stops"]) ? implode(",", $filter["stops"]) : $filter["stops"];
			if($stops !== "")
				$query .= " AND `stops` IN ($stops)";
		}

		// departure 
		if(isset($filter["departure_filter"]) && !empty($filter["departure_filter"]))
		{
			$range = explode("-", $filter["departure_filter"]);
			// convert hour integer to time()
			if(!empty($range))
			{
				$min = $this->general->minutes_to_hours(trim($range[0]));
				$max = $this->general->minutes_to_hours(trim($range[1]));
				$query .= " AND (`departure_tm` BETWEEN '$min' AND '$max' AND `results` = '0')";
			}
		}

		// airlines 
		if(isset($filter["airlines"]))
		{
			$airlines = is_array($filter["airlines"]) ? implode("','", $filter["airlines"]) : $filter["airlines"];
			if(!empty($airlines))
				$query .= " AND `airline` IN ('$airlines')";
		}

		// airports 
		if(isset($filter["airports"]))
		{
			$airport_query = "";
			foreach ($filter["airports"] as $key => $value)
				$airport_query .= "`airport` LIKE '%,$value,%' OR ";
			$airport_query = substr_replace($airport_query, "", -4);
			if(!empty($airport_query))
					$query .= "AND ($airport_query)";
		}

		//order by 
		if(isset($filter["sort_by"]) && $filter["sort_by"] === "desc")
			$order = "DESC";
		else 
			$order = "ASC";

		//order by 
		if(isset($filter["order_by"]))
			$query .= " ORDER BY `".$filter["order_by"]."` ".$order; 

		if($avoid_limit === "false")
		{
			if(isset($filter["page"]) && isset($filter["limit"]))
				$query .= " LIMIT ".((($filter['page'] * 1) - 1) * $filter['limit']).",".$filter['limit'];
			else
				$query .= " LIMIT 0, 10";
		}
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	function available_rflights($id, $filter, $lang = DEFAULT_LANG, $avoid_limit = false)
	{
		$query = "SELECT `flight_search_result`.*, `airline_$lang` AS `airline_name`, if(`stops` = '0', 'Direct Stop Coach', if(`stops` = '1', 'One Stop Coach', 'Multi Stop Coach')) AS `stops_text` FROM `flight_search_result` INNER JOIN `flight_airlines` ON `airline` = `airline_code` WHERE `search_id` = '$id' AND `results` = '1'";
		//price 
		if(isset($filter["price_filter"]) && !empty($filter["price_filter"]))
		{
			$range = explode("-", $filter["price_filter"]);
			if(!empty($range))
			{
				$min = trim($range[0]);
				$max = trim($range[1]);
				//$query .= " AND (`total_cost` BETWEEN '$min' AND '$max')";
				$query .= " AND (`total_cost` >= '$min')";
			}
		}
		/*echo $query;exit();
		$result = $this->db->query($query);
		return $result->result();*/

		// stops 
		if(isset($filter["stops"]))
		{
			$stops = is_array($filter["stops"]) ? implode(",", $filter["stops"]) : $filter["stops"];
			if($stops !== "")
				$query .= " AND `stops` IN ($stops)";
		}

		// arrival 
		if(isset($filter["return_filter"]) && !empty($filter["return_filter"]))
		{
			$range = explode("-", $filter["return_filter"]);
			// convert hour integer to time()
			if(!empty($range))
			{
				$min = $this->general->minutes_to_hours(trim($range[0]));
				$max = $this->general->minutes_to_hours(trim($range[1]));
				$query .= " AND (`departure_tm` BETWEEN '$min' AND '$max' AND `results` = '1')";
			}
		}

		// airlines 
		if(isset($filter["airlines"]))
		{
			$airlines = is_array($filter["airlines"]) ? implode("','", $filter["airlines"]) : $filter["airlines"];
			if(!empty($airlines))
				$query .= " AND `airline` IN ('$airlines')";
		}

		// airports 
		if(isset($filter["airports"]))
		{
			$airport_query = "";
			foreach ($filter["airports"] as $key => $value)
				$airport_query .= "`airport` LIKE '%,$value,%' OR ";
			$airport_query = substr_replace($airport_query, "", -4);
			if(!empty($airport_query))
					$query .= "AND ($airport_query)";
		}

		//order by 
		if(isset($filter["sort_by"]) && $filter["sort_by"] === "desc")
			$order = "DESC";
		else 
			$order = "ASC";

		//order by 
		if(isset($filter["order_by"]))
			$query .= " ORDER BY `".$filter["order_by"]."` ".$order; 
		if(!$avoid_limit)
		{
			if(isset($filter["page"]) && isset($filter["limit"]))
				$query .= " LIMIT ".((($filter['page'] * 1) - 1) * $filter['limit']).",".$filter['limit'];
			else
				$query .= " LIMIT 0, 10";
		}
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_airlines($search_id, $ids, $lang = DEFAULT_LANG)
	{
		$id_list = "";
		foreach ($ids as $id)
			$id_list .= "'$id', ";
		$id_list = substr_replace($id_list, "", -2);
		$query = "SELECT `airline_code`, `airline_$lang` AS `airline_name`, GROUP_CONCAT(CONCAT(`stops`, ':', `total_cost`)) AS `total_price` FROM `flight_airlines`, (SELECT * FROM(SELECT `airline`, `total_cost`, `stops` FROM `flight_search_result` WHERE `search_id` = '$search_id' ORDER BY `total_cost`) AS `a` GROUP BY `airline`, `stops`) AS `flight_search_result` WHERE `airline` = `airline_code` AND `airline_code` IN (".$id_list .") GROUP BY `airline` ORDER BY `total_cost`";
		if(!empty($id_list))
		{
			$result = $this->db->query($query);
			return $result->num_rows() > 0 ? $result->result() : false;
		}
		return false;
	}

	public function get_all_airlines($ids = array(), $lang = DEFAULT_LANG)
	{
		$id_list = "";
		foreach ($ids as $id)
			$id_list .= "'$id', ";
		$id_list = substr_replace($id_list, "", -2);
		$query = "SELECT `airline_code`, `airline_$lang` AS `airline_name` FROM `flight_airlines`";
		if(!empty($id_list))
			$query .= "WHERE `airline_code` IN (".$id_list .")";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_cip_airports()
	{
		$return_array = array(
						"0" => (object)array("airport_code" => "ABD", "cip_in" => null, "cip_out" => "1000"),
						"1" => (object)array("airport_code" => "ACP", "cip_in" => "1500", "cip_out" => null),
						"2" => (object)array("airport_code" => "ACZ", "cip_in" => "1500", "cip_out" => "1000"),
						"3" => (object)array("airport_code" => "ADU", "cip_in" => "3000", "cip_out" => "1000"),
						"4" => (object)array("airport_code" => "AEU", "cip_in" => "2500", "cip_out" => null),
						"5" => (object)array("airport_code" => "AFZ", "cip_in" => "3500", "cip_out" => "1000"),
						"6" => (object)array("airport_code" => "AJK", "cip_in" => "500", "cip_out" => "1000"),
						"7" => (object)array("airport_code" => "AKW", "cip_in" => "6500", "cip_out" => null),
						"8" => (object)array("airport_code" => "AWZ", "cip_in" => "500", "cip_out" => "1000"),
						"9" => (object)array("airport_code" => "BBL", "cip_in" => null, "cip_out" => "4000"),
						"10" => (object)array("airport_code" => "BDH", "cip_in" => "2500", "cip_out" => "1000"),
						"11" => (object)array("airport_code" => "BJB", "cip_in" => null, "cip_out" => "1000"),
						"12" => (object)array("airport_code" => "BND", "cip_in" => "5000", "cip_out" => "2000"),
						"13" => (object)array("airport_code" => "BSM", "cip_in" => null, "cip_out" => "1000"),
						"14" => (object)array("airport_code" => "BUZ", "cip_in" => null, "cip_out" => "1000"),
						"15" => (object)array("airport_code" => "BXR", "cip_in" => "5000", "cip_out" => null),
						"16" => (object)array("airport_code" => "CKT", "cip_in" => null, "cip_out" => "3000"),
						"17" => (object)array("airport_code" => "CQD", "cip_in" => null, "cip_out" => "3000"),
						"18" => (object)array("airport_code" => "DEF", "cip_in" => "3500", "cip_out" => "1000"),
						"19" => (object)array("airport_code" => "FAZ", "cip_in" => null, "cip_out" => "1000"),
						"20" => (object)array("airport_code" => "GBT", "cip_in" => "2500", "cip_out" => null),
						"21" => (object)array("airport_code" => "GCH", "cip_in" => null, "cip_out" => "1000"),
						"22" => (object)array("airport_code" => "GSM", "cip_in" => "5500", "cip_out" => "4000"),
						"23" => (object)array("airport_code" => "GZW", "cip_in" => null, "cip_out" => "1000"),
						"24" => (object)array("airport_code" => "HDM", "cip_in" => null, "cip_out" => "1000"),
						"25" => (object)array("airport_code" => "HDR", "cip_in" => "1500", "cip_out" => null),
						"26" => (object)array("airport_code" => "IAQ", "cip_in" => "1500", "cip_out" => "1000"),
						"27" => (object)array("airport_code" => "IFN", "cip_in" => "3000", "cip_out" => "1000"),
						"28" => (object)array("airport_code" => "IHR", "cip_in" => "2500", "cip_out" => null),
						"29" => (object)array("airport_code" => "IIL", "cip_in" => "3500", "cip_out" => "1000"),
						"30" => (object)array("airport_code" => "IKA", "cip_in" => "5500", "cip_out" => "1000"),
						"31" => (object)array("airport_code" => "JWN", "cip_in" => "6500", "cip_out" => null),
						"32" => (object)array("airport_code" => "JYR", "cip_in" => "500", "cip_out" => "1000"),
						"33" => (object)array("airport_code" => "KER", "cip_in" => null, "cip_out" => "4000"),
						"34" => (object)array("airport_code" => "KHA", "cip_in" => "2500", "cip_out" => "1000"),
						"35" => (object)array("airport_code" => "KHD", "cip_in" => null, "cip_out" => "1000"),
						"36" => (object)array("airport_code" => "KHK", "cip_in" => "5000", "cip_out" => "2000"),
						"37" => (object)array("airport_code" => "KHY", "cip_in" => null, "cip_out" => "1000"),
						"38" => (object)array("airport_code" => "KIH", "cip_in" => null, "cip_out" => "1000"),
						"39" => (object)array("airport_code" => "KLM", "cip_in" => "5000", "cip_out" => null),
						"40" => (object)array("airport_code" => "KNR", "cip_in" => null, "cip_out" => "3000"),
						"41" => (object)array("airport_code" => "KSH", "cip_in" => null, "cip_out" => "3000"),
						"42" => (object)array("airport_code" => "LFM", "cip_in" => "3500", "cip_out" => "1000"),
						"43" => (object)array("airport_code" => "LRR", "cip_in" => null, "cip_out" => "1000"),
						"44" => (object)array("airport_code" => "LVP", "cip_in" => "2500", "cip_out" => null),
						"45" => (object)array("airport_code" => "MHD", "cip_in" => "4000", "cip_out" => "1000"),
						"46" => (object)array("airport_code" => "MRX", "cip_in" => "5500", "cip_out" => "4000"),
						"47" => (object)array("airport_code" => "NSH", "cip_in" => null, "cip_out" => "1000"),
						"48" => (object)array("airport_code" => "NUJ", "cip_in" => "1500", "cip_out" => null),
						"49" => (object)array("airport_code" => "OMH", "cip_in" => "1500", "cip_out" => "1000"),
						"50" => (object)array("airport_code" => "OMI", "cip_in" => "3000", "cip_out" => "1000"),
						"51" => (object)array("airport_code" => "PGU", "cip_in" => "2500", "cip_out" => null),
						"52" => (object)array("airport_code" => "PYK", "cip_in" => "3500", "cip_out" => "1000"),
						"53" => (object)array("airport_code" => "RAS", "cip_in" => "500", "cip_out" => "1000"),
						"54" => (object)array("airport_code" => "RJN", "cip_in" => "6500", "cip_out" => null),
						"55" => (object)array("airport_code" => "RUD", "cip_in" => "500", "cip_out" => "1000"),
						"56" => (object)array("airport_code" => "RZR", "cip_in" => null, "cip_out" => "4000"),
						"57" => (object)array("airport_code" => "SDG", "cip_in" => "2500", "cip_out" => "1000"),
						"58" => (object)array("airport_code" => "SRY", "cip_in" => null, "cip_out" => "1000"),
						"59" => (object)array("airport_code" => "SXI", "cip_in" => "5000", "cip_out" => "2000"),
						"60" => (object)array("airport_code" => "SYJ", "cip_in" => null, "cip_out" => "1000"),
						"61" => (object)array("airport_code" => "SYZ", "cip_in" => null, "cip_out" => "1000"),
						"62" => (object)array("airport_code" => "TBZ", "cip_in" => "5000", "cip_out" => null),
						"63" => (object)array("airport_code" => "TCX", "cip_in" => null, "cip_out" => "3000"),
						"64" => (object)array("airport_code" => "TEW", "cip_in" => null, "cip_out" => "3000"),
						"65" => (object)array("airport_code" => "THR", "cip_in" => "3500", "cip_out" => "1000"),
						"66" => (object)array("airport_code" => "XBJ", "cip_in" => null, "cip_out" => "1000"),
						"67" => (object)array("airport_code" => "YEH", "cip_in" => "2500", "cip_out" => null),
						"68" => (object)array("airport_code" => "YES", "cip_in" => null, "cip_out" => "1000"),
						"68" => (object)array("airport_code" => "ZAH", "cip_in" => null, "cip_out" => "1000"),
						"69" => (object)array("airport_code" => "ZBR", "cip_in" => "5500", "cip_out" => "4000"));
		return $return_array;
	}

	public function get_airports($ids = array(), $lang = DEFAULT_LANG)
	{
		$id_list = "";
		foreach ($ids as $id)
			$id_list .= "'$id', ";
		$id_list = substr_replace($id_list, "", -2);
		$where = "WHERE `flight_airports`.`country` = `countries`.`id` ";
		if($id_list !== "")
			$where .= "AND `airport_code` IN (".$id_list .")";
		$query = "SELECT `airport_code`, `airport_$lang` AS `airport`, `city_code`, `city_$lang` AS `city`, `country_$lang` AS `country`, `country` AS `country_code`, `city_link`, `airport_link` FROM `flight_airports`, `countries` ".$where." ORDER BY `city_code`, `airport_code`";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->result() : false;
	}

	public function get_dicount_price($id = ""){
		if($id != ""){
			$this->db->where("id",$id);
		}

		return $this->db->get('discounts')->row();
	}

}
