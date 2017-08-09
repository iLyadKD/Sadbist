<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab.
	################################################################
*/

class Hotels_Pro {

	public $ci;
	protected $key = "";
	protected $url = "";
	function __construct()
	{
		$this->ci = &get_instance();
	}

	function hotels_list($api, $data, $start = "1", $limit = "1000", $currency = "USD", $curr_country = "US")
	{
		$sd = json_decode($data->input_json);
		$room_count = $sd->rooms;
		$city_code = $sd->city_id;
		$nights = $sd->nights;
		$adults = $sd->adult;
		$children = $sd->child;
		$city_code = $sd->hotels_pro;
		$check_in = date("Y-m-d", strtotime($sd->check_in_dt));
		$check_out = date("Y-m-d", strtotime($sd->check_out_dt));
		if($api->status === "2")
		{
			$this->key = $api->live_pwd;
			$this->url = $api->live_url;
		}
		else
		{
			$this->key = $api->test_pwd;
			$this->url = $api->test_url;
		}

		$d_rooms = array();
		for($i = 0; $i < $room_count; $i++)
		{
			$rooms = array();
			for($j = 0; $j < $adults[$i]; $j++) { $rooms[] = array("paxType" => "Adult"); }
			$child_age = "childAge_".($i + 1);
			$child_age = $sd->$child_age;
			for($j = 0; $j < $children[$i]; $j++) { $rooms[] = array("paxType" => "Child","age"=>$child_age[$j]); }
			$d_rooms[] = $rooms;
		}
		$client = new SoapClient($this->url, array('trace' => 1, 'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
		$checkAvailability = array();
		try
		{
			$filters = array();
			//$filters[] = array("filterType" => "resultLimit", "filterValue" => "1000"); 
			$paginationRequest = array();
			$paginationRequest = array("searchid" => "","rangeFrom" => $start, "rangeTo" => $limit);
			$checkAvailability = $client->getAvailableHotel($this->key, $city_code, $check_in, $check_out, $currency, $curr_country, "false", $d_rooms, $filters, $paginationRequest);
		}
		catch (SoapFault $exception)
		{
			$exception->getMessage();
		}
		if(!isset($checkAvailability) && empty($checkAvailability)) exit;
		$result = array();
		if(!empty($checkAvailability))
		{
			$hotelprossearchid = $checkAvailability->searchId;
			if (is_object($checkAvailability->availableHotels))
				$hotelResponse[] = $checkAvailability->availableHotels;
			else
				$hotelResponse = $checkAvailability->availableHotels;

			foreach ((array) $hotelResponse as $hnum => $hotel) {

				$processId = $hotel->processId;
				$hotel_code = $hotel->hotelCode;
				$price = $hotel->totalPrice;
				$brd =  $hotel->boardType;
				$api_currency = $hotel->currency;

				if($brd == "Half Board") $board = "HB";
				else if($brd == "Room Only") $board = "RO";
				else if($brd == "English Breakfast") $board = "EB";
				else if($brd == "Bed and Breakfast" || $brd == "Bed and Full Breakfast") $board = "BB";
				else if($brd == "Full Breakfast") $board = "FB";
				else if($brd == "English Breakfast") $board = "EB";
				else if($brd == "Continental breakfast") $board = "CB";
				else $board = "OO";
				
				if(!isset($result[$hotel_code]) || (isset($result[$hotel_code]) && $result[$hotel_code]->price > $price))
				$result[$hotel_code] = (object)array("api" => $api->api_id, "price" => $price, "key1" => $board, "key2_1" => $hotelprossearchid, "key2_2" => $hotel->processId, "api_currency" => $api_currency);
			}
		}
		return json_encode($result);
	}

	function hotel_details($api, $search_id, $search_data, $hotel_code, $price, $extra)
	{
		$this->ci->hotel_model->clear_hotel_details($api->api_id, $search_id[1]);
		if($api->status === "2")
		{
			$this->key = $api->live_pwd;
			$this->url = $api->live_url;
		}
		else
		{
			$this->key = $api->test_pwd;
			$this->url = $api->test_url;
		}

		if($hotel_code && $extra)
		{
			$client = new SoapClient($this->url, array('trace' => 1, 'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
			$checkAvailability = array();
			try
			{
				//echo $this->key." ".$extra." ".$hotel_code;die;
				$checkAvailability = $client->allocateHotelCode($this->key, $extra, $hotel_code);
			}
			catch (SoapFault $exception)
			{
				$exception->getMessage();
			}
		}

		if(!@$checkAvailability) exit;
		if (is_object($checkAvailability->availableHotels))
			$hotelResponse[] = $checkAvailability->availableHotels;
		else
			$hotelResponse = $checkAvailability->availableHotels;

		$cancel_policy = "For Cancellation Policy continue with booking.";
		$cancel_deadline = "0000-00-00";
		foreach ((array) $hotelResponse as $hnum => $hotel)
		{
			$api_hotel_code = $hotel->hotelCode;     

			if($api_hotel_code === $hotel_code)
			{
				$room_code = $hotel->processId;
				$room_status = (($hotel->availabilityStatus === "InstantConfirmation") ? "Available" : $hotel->availabilityStatus);
				$api_currency = isset($hotel->currency) ? $hotel->currency: "USD";
				$inclusion = $hotel->boardType;

				$cancel_cost = $api_cost = $admin_cost = $agent_cost = $total_cost = $hotel->totalPrice;

				if(is_object($hotel->rooms))
					$room_details[] = $hotel;
				else
					$room_details = $hotel->rooms;

				$room_type = array();
				$room_cost = array();
				$ppx = array();
				$avg_cost = array();
				$adult_count = $child_count = 0;
				$room_count = count($room_details);
				foreach ((array) $room_details as $rnum => $room)
				{
					$room_type[] = $room->roomCategory;
					$room_cost[$rnum]["total"] = $room->totalRoomRate;
					$rooms_info = "";
					if (is_object($room->paxes))
						$rooms_info[] = $room->paxes;
					else
						$rooms_info = $room->paxes;

					$aadult = 0; $cchild = 0;
					foreach ((array) $rooms_info as $pnum => $pax)
					{
						$pax_type = $pax->paxType;
						if($pax_type === "Adult") { ++$aadult; ++$adult_count;}
						if($pax_type === "Child") { ++$cchild; ++$child_count;}
					}
					$rooms = 1;
					if(array_key_exists($aadult.":".$cchild, $ppx))
						$rooms = ($ppx[$aadult.":".$cchild]["rooms"] * 1) + 1;
					$ppx[$aadult.":".$cchild] = array("adult" => $aadult, "child" => $cchild, "rooms" => $rooms);

					if (is_object($room->ratesPerNight))
						$rates_per_night[] = $room->ratesPerNight;
					else
						$rates_per_night = $room->ratesPerNight;

					foreach ((array) $rates_per_night as $rpnum => $price)
						$room_cost[$rnum][$price->date] = $price->amount;
				}
				$adultchild = json_encode($ppx);

				$room_type = implode("-<br>", $room_type);
				$room_cost = json_encode($room_cost);
				$costs = json_encode(array(array("user" => "api", "markup" => "0", "markup_type" => "$", "amount" => $api_cost, "currency" => $api_currency, "currency_val" => "1.00"),array("user" => "admin", "markup" => "0", "markup_type" => "$", "amount" => $admin_cost, "currency" => "USD", "currency_val" => "1.00")));

				$charges = json_encode(array(array("charge" => "payment_gateway", "markup" => "0", "markup_type" => "$", "amount" => "0", "currency" => "USD", "currency_val" => "1.00")));

				$data[] = array(
					"search_id" => $this->ci->db->escape_str($search_id[1]),
					"api" => $this->ci->db->escape_str($api->api_id),
					"hotel_code" => $this->ci->db->escape_str($hotel_code),
					"dest_code" => $this->ci->db->escape_str($blank),
					"room_code" => $this->ci->db->escape_str($room_code),
					"room_type" => $this->ci->db->escape_str($room_type),
					"room_characteristic" => $this->ci->db->escape_str($blank),
					"room_classification" => $this->ci->db->escape_str($blank),
					"room_details" => $this->ci->db->escape_str($adultchild),
					"board_type" => $this->ci->db->escape_str($blank),
					"board_code" => $this->ci->db->escape_str($blank),
					"board_short_name" => $this->ci->db->escape_str($blank),
					"inclusion" => $this->ci->db->escape_str($inclusion),
					"token" => $this->ci->db->escape_str($extra),
					"shuri" => $this->ci->db->escape_str($hnum),
					"contract_name" => $this->ci->db->escape_str($blank),
					"api_currency" => $this->ci->db->escape_str($api_currency),
					"costs" => $this->ci->db->escape_str($costs),
					"charges" => $this->ci->db->escape_str($charges),
					"city" => $this->ci->db->escape_str($blank),
					"room_cost" => $this->ci->db->escape_str($room_cost),
					"api_cost" => $this->ci->db->escape_str($api_cost),
					"admin_cost" => $this->ci->db->escape_str($admin_cost),
					"agent_cost" => $this->ci->db->escape_str($agent_cost),
					"total_cost" => $this->ci->db->escape_str($total_cost),
					"cancel_cost" => $this->ci->db->escape_str($cancel_cost),
					"room_count" => $this->ci->db->escape_str($room_count),
					"adult_count" => $this->ci->db->escape_str($adult_count),
					"child_count" => $this->ci->db->escape_str($child_count),
					"cancel_policy" => $this->ci->db->escape_str($cancel_policy),
					"cancel_deadline" => $this->ci->db->escape_str($cancel_deadline),
					"incoming_office" => $this->ci->db->escape_str($blank),
					"promotions" => $this->ci->db->escape_str($blank),
					"offers" => $this->ci->db->escape_str($blank),
					"remarks" => $this->ci->db->escape_str($blank),
					"status" => $this->ci->db->escape_str($room_status)
				);
			}
		}

		if(!empty($data))
			$this->ci->hotel_model->set_hotel_details($api->id, $search_id[1], $data);
	}

	public function prebooking_request($search_id, $api, $search_data, $hotel_code, $room_list, $extra)
	{
		$sd = json_decode($search_data);
		$room_count = $sd->rooms;
		$city_code = $sd->city_id;
		$nights = $sd->nights;
		$adults = $sd->adult;
		$children = $sd->child;
		$city_code = $sd->hotels_pro;
		$check_in = date("Y-m-d", strtotime($sd->check_in_dt));
		$check_out = date("Y-m-d", strtotime($sd->check_out_dt));
		if($api->status === "2")
		{
			$this->key = $api->live_pwd;
			$this->url = $api->live_url;
		}
		else
		{
			$this->key = $api->test_pwd;
			$this->url = $api->test_url;
		}

		$client = new SoapClient($this->url, array("trace" => 1));
		$data = array("error" => true, "response_status" => true);
		try
		{
			$jd_decode = explode("||", $extra);
			$process_id = isset($jd_decode[1]) ? $jd_decode[1] : "";
			$data["error"] = false;
			$policy_details = $client->getHotelCancellationPolicy($this->key, $process_id, $hotel_code);
			if(!empty($policy_details))
			{
				$cancellation_policies = $room_details = array();
				$room_details["track_id"] = $policy_details->trackingId;
				$policy_list = is_array($policy_details->cancellationPolicy) ? $policy_details->cancellationPolicy : array($policy_details->cancellationPolicy);
				foreach ($policy_list as $policies)
				{
					$current_policy = array();
					$current_policy["cancel_from"] = $policies->cancellationDay;
					$current_policy["cancel_till"] = $policies->cancellationDay;
					$current_policy["cancel_amount_type"] = $policies->feeType;
					$current_policy["cancel_amount"] = $policies->feeAmount;
					$current_policy["cancel_details"] = $policies->remarks;
					$cancellation_policies[] = $current_policy;
				}
				$data["cancel_policies"] = $cancellation_policies;
				$data["rooms"] = $room_details;
				$data["hash_file"] = $hash_file = base_encode($search_id)."_".time();
				if(@$data["cancel_policies"])
					file_put_contents("cart/$hash_file.txt", json_encode($data));

			}
			var_dump($policy_list);die;
		}
		catch (SoapFault $exception)
		{
			$data["response_status"] = $exception->getMessage(); //exit;
		}
		return $data;
	}
}