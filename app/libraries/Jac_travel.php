<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab.
	################################################################
*/

class Jac_Travel {

	public $ci;
	protected $key = "";
	protected $url = "";
	function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model("hotel_model");
	}

	function hotels_list($api, $data, $start = "1", $limit = "1000", $currency = "USD", $curr_country = "US")
	{
		$sd = json_decode($data->input_json);
		$rooms = $sd->rooms;
		$city_code = $sd->city_id;
		$nights = $sd->nights;
		$adults = $sd->adult;
		$children = $sd->child;
		$check_in = date("Y-m-d", strtotime($sd->check_in_dt));
		$check_out = date("Y-m-d", strtotime($sd->check_out_dt));
		if($api->status === "2")
		{
			$this->key = $api->live_pwd;
			$this->url = $api->live_url."/ServiceSearch.asp";
		}
		else
		{
			$this->key = $api->test_pwd;
			$this->url = $api->test_url."/ServiceSearch.asp";
		}

		$city_data = $this->ci->hotel_model->api_hotel_code($city_code);
		if($city_data !== false && is_object($city_data))
			$city_name = $city_data->city_name;
		else
			exit();

		$r_types = array();
		for($i = 0; $i < $rooms; $i++)
		{
			$condition = $adults[$i].":".$children[$i];
			$occ = 0;
			switch($condition)
			{
				case "1:0" : $occ = 1; break;
				case "1:1" : $occ = 3; break; // need to remove
				case "1:2" : $occ = 33; break; // need to remove
				case "1:3" : $occ = 0; break;

				case "2:0" : $occ = 2; break; //2/3
				case "2:1" : $occ = 7; break; //33/7
				case "2:2" : $occ = 8; break; 
				case "2:3" : $occ = 0; break;

				case "3:0" : $occ = 4; break;
				case "3:1" : $occ = 0; break;

				case "4:0" : $occ = 5; break;
				default : $occ = 0; break;
			}

			if($occ === 0) exit;
			$r_types[$occ]["qty"] = isset($r_types[$occ]["qty"]) ? ($r_types[$occ]["qty"] + 1) : 1;
			if($children[$i] > 0)
			{
				$r_types[$occ]["child_count"] = isset($r_types[$occ]["child_count"]) ? $r_types[$occ]["child_count"] + $children[$i] : $children[$i];
				$child_age = "childAge_".($i + 1);
				$child_age = $sd->$child_age;
				$r_types[$occ]["child_ages"] = isset($r_types[$occ]["child_ages"]) ? $r_types[$occ]["child_ages"].",".implode(",", $child_age) : implode(",", $child_age);
			}
		}

		$room_str = "";
		foreach ($r_types as $occ => $qty)
		{
			$room_str .="<ROOM>
							<OCCUPANCY>".$occ."</OCCUPANCY>
							<QUANTITY>".$qty["qty"]."</QUANTITY>";
			if(isset($qty["child_count"]))
				$room_str .= "<CHILDREN>
							<CHILD_RATE CHILD_QUANTITY='".$qty["qty"] * $qty["child_count"]."' CHILD_AGE='".$qty["child_ages"]."'/>
						</CHILDREN>";
			$room_str .= "</ROOM>";
		}

		$request = '<SERVICE_SEARCH_REQUEST>
						<VERSION_HISTORY APPLICATION_NAME = "JacTravel" XML_FILE_NAME="Response.XML" LICENCE_KEY="'.$this->key.'" TS_API_VERSION = "v357.0.0">
							<XML_VERSION_NO>3.0</XML_VERSION_NO>
						</VERSION_HISTORY>
						<GEO_LOCATION_NAME>'.$city_name.'</GEO_LOCATION_NAME>
						<START_DATE>'.$check_in.'</START_DATE>
						<NUMBER_OF_NIGHTS>'.$nights.'</NUMBER_OF_NIGHTS>
						<AVAILABLE_ONLY>true</AVAILABLE_ONLY>
						<GET_START_PRICE>true</GET_START_PRICE>
						<ROOM_REPLY>
						<ALL_ROOM/>
						</ROOM_REPLY>
						<ROOMS_REQUIRED>'.$room_str.'
						</ROOMS_REQUIRED>
					</SERVICE_SEARCH_REQUEST>';
		$curl_init = curl_init();
		$httpHeader = array(
					"Content-Type: text/xml; charset=UTF-8",
					"Content-Encoding: UTF-8",
					"Accept-Encoding: gzip,deflate"
				);
		curl_setopt($curl_init, CURLOPT_URL, $this->url);
		curl_setopt($curl_init, CURLOPT_TIMEOUT, 580);
		curl_setopt($curl_init, CURLOPT_HEADER, 0);
		curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_init, CURLOPT_POST, 1);
		curl_setopt($curl_init, CURLOPT_POSTFIELDS, "$request");
		curl_setopt($curl_init, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch2, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl_init, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip,deflate");
		$xmls = curl_exec($curl_init);
		curl_close($curl_init);
		$xmls = str_replace("UTF-16", "UTF-8", $xmls);
		$result = array();
		if($xmls !== "")
		{
			$dom = new DOMDocument();
			$dom->loadXML($xmls);
			$services = $dom->getElementsByTagName("SERVICE");
			foreach($services as $service)
			{
			   $service_id = $service->getAttribute("SERVICE_ID");
			   $is_available = $service->getAttribute("AVAILABLE");
				if($is_available === "Yes")
				{
					$start_price = $service->getAttribute("STARTING_PRICE");
					$api_currency = $service->getAttribute("CURRENCY");
					$result[$service_id] = (object)array("api" => $api->api_id, "price" => $start_price, "api_currency" => $api_currency);
				}
			}
		}
		echo json_encode($result);
	}

	function hotel_details($api, $search_id, $search_data, $hotel_code, $price, $extra)
	{
		$this->ci->hotel_model->clear_hotel_details($api->api_id, $search_id[1]);
		$sd = $search_data;
		$rooms = $sd->rooms;
		$city_code = $sd->city_id;
		$nights = $sd->nights;
		$adults = $sd->adult;
		$children = $sd->child;
		$check_in = date("Y-m-d", strtotime($sd->check_in_dt));
		$check_out = date("Y-m-d", strtotime($sd->check_out_dt));
		if($api->status === "2")
		{
			$this->key = $api->live_pwd;
			$this->url = $api->live_url."/AvailabilityAndPrices.asp";
		}
		else
		{
			$this->key = $api->test_pwd;
			$this->url = $api->test_url."/AvailabilityAndPrices.asp";
		}

		$city_data = $this->ci->hotel_model->api_hotel_code($city_code);
		if($city_data !== false && is_object($city_data))
			$city_name = $city_data->city_name;
		else
			exit();

		$r_types = array();
		for($i = 0; $i < $rooms; $i++)
		{
			$condition = $adults[$i].":".$children[$i];
			$occ = 0;
			switch($condition)
			{
				case "1:0" : $occ = 1; break;
				case "1:1" : $occ = 3; break; // need to remove
				case "1:2" : $occ = 33; break; // need to remove
				case "1:3" : $occ = 0; break;

				case "2:0" : $occ = 2; break; //2/3
				case "2:1" : $occ = 7; break; //33/7
				case "2:2" : $occ = 8; break; 
				case "2:3" : $occ = 0; break;

				case "3:0" : $occ = 4; break;
				case "3:1" : $occ = 0; break;

				case "4:0" : $occ = 5; break;
				default : $occ = 0; break;
			}

			if($occ === 0) exit;
			$r_types[$occ]["qty"] = isset($r_types[$occ]["qty"]) ? ($r_types[$occ]["qty"] + 1) : 1;
			if($children[$i] > 0)
			{
				$r_types[$occ]["child_count"] = isset($r_types[$occ]["child_count"]) ? $r_types[$occ]["child_count"] + $children[$i] : $children[$i];
				$child_age = "childAge_".($i + 1);
				$child_age = $sd->$child_age;
				$r_types[$occ]["child_ages"] = isset($r_types[$occ]["child_ages"]) ? $r_types[$occ]["child_ages"].",".implode(",", $child_age) : implode(",", $child_age);
			}
		}

		$room_str = "";
		foreach ($r_types as $occ => $qty)
		{
			$room_str .="<ROOM>
							<OCCUPANCY>".$occ."</OCCUPANCY>
							<QUANTITY>".$qty["qty"]."</QUANTITY>";
			if(isset($qty["child_count"]))
				$room_str .= "<CHILDREN>
								<CHILD_RATE CHILD_QUANTITY='".$qty["qty"] * $qty["child_count"]."' CHILD_AGE='".$qty["child_ages"]."'/>
							</CHILDREN>";
			$room_str .= "</ROOM>";
		}

		$request = "<HOTEL_AVAILABILITY_AND_PRICE_SEARCH_CRITERIA>
							<VERSION_HISTORY APPLICATION_NAME='JacTravel' XML_FILE_NAME='Response.xml' LICENCE_KEY='".$this->key."' TS_API_VERSION='v357.0.0'>
								<XML_VERSION_NO>3.0</XML_VERSION_NO>
							</VERSION_HISTORY>
							<SERVICE_ID AVAILABLE_ONLY='TRUE'>".$hotel_code."</SERVICE_ID>
							<CLIENT_NAME>API Client</CLIENT_NAME>
							<BOOKING_START_DATE>".$check_in."</BOOKING_START_DATE>
							<BOOKING_END_DATE>".$check_out."</BOOKING_END_DATE>
							<ROOM_REPLY>
								<ALL_ROOM/>
							</ROOM_REPLY>
							<PRICING>
								<VALIDATE_QTY/>
							</PRICING>
							<ROOMS_REQUIRED>".$room_str."</ROOMS_REQUIRED>
						</HOTEL_AVAILABILITY_AND_PRICE_SEARCH_CRITERIA>";
		$curl_init = curl_init();
		$httpHeader = array(
					"Content-Type: text/xml; charset=UTF-8",
					"Content-Encoding: UTF-8",
					"Accept-Encoding: gzip,deflate"
				);
		curl_setopt($curl_init, CURLOPT_URL, $this->url);
		curl_setopt($curl_init, CURLOPT_TIMEOUT, 580);
		curl_setopt($curl_init, CURLOPT_HEADER, 0);
		curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_init, CURLOPT_POST, 1);
		curl_setopt($curl_init, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl_init, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch2, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl_init, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip,deflate");
		$xmls = curl_exec($curl_init);
		curl_close($curl_init);
		$xmls = str_replace("UTF-16", "UTF-8", $xmls);
		$data = array();
		$blank = "";

		if(!empty($xmls))
		{
			$dom = new DOMDocument();
			$dom->loadXML($xmls);
			$options_list = $dom->getElementsByTagName("OPTIONS");
			foreach ($options_list as $options)
			{
				$cancel_policy = "2 day(s) prior to arrival. If cancelled less than 2 day(s) prior then 1 night will be charged. In the event of a no show or early checkout then a charge of up to 100% of the entire cost of the booking may be applied";
		 		$cancel_deadline = "0000-00-00";
		 		$option_list = $options->getElementsByTagName("OPTION");
		 		foreach ($option_list as $option)
		 		{
		 			$cancel_cost = $api_cost = $admin_cost = $agent_cost = $total_cost = 0;
					$token = $option->getElementsByTagName("OPTIONID")->item(0)->nodeValue;
					$room_type = $option->getElementsByTagName("OPTION_NAME")->item(0)->nodeValue;
					$api_currency = "USD";
					$room_code = $occupancy = $option->getElementsByTagName("OCCUPANCY")->item(0)->nodeValue;
					$adult_count = $child_count = $room_count = 0;
					$inclusion = "";
					if(array_key_exists($occupancy,$r_types))
					{
						if($occupancy === "1")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 1;
							$child_count = 0;
						}
						else if($occupancy === "2")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 1;
							$child_count = 0;
						}
						else if($occupancy === "3")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 2;
							$child_count = 0;
						}
						else if($occupancy === "4")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 3;
							$child_count = 0;
						}
						else if($occupancy === "5")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 4;
							$child_count = 0;
						}
						else if($occupancy === "7")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 2;
							$child_count = $r_types[$occupancy]["qty"] * 1;
						}
						else if($occupancy === "8")
						{
							$adult_count = $r_types[$occupancy]["qty"] * 2;
							$child_count = $r_types[$occupancy]["qty"] * 2;
						}
						$room_count = $r_types[$occupancy]["qty"];
					}

					$status = $option->getElementsByTagName("OPTION_STATUS")->item(0)->nodeValue;

					$prices_list = $option->getElementsByTagName("PRICES");
					$room_cost = array();
					foreach ($prices_list as $prices)
					{
						$price_list = $prices->getElementsByTagName("PRICE");
						foreach ($price_list as $key => $price)
						{

							$api_currency = $price->getElementsByTagName("SELL_CURRENCY_CODE")->item(0)->nodeValue;
							$price_date = date("Y-m-d", strtotime($price->getElementsByTagName("SELL_CURRENCY_CODE")->item(0)->nodeValue));
							$cancel_cost = $api_cost = $admin_cost = $agent_cost = $total_cost = $price->getElementsByTagName("SELL_PRICE_AMOUNT")->item(0)->nodeValue;
							$sell_price = $price->getElementsByTagName("SELL_PRICE_ID")->item(0)->nodeValue;
							$inclusion = $price->getElementsByTagName( "MEAL_PLAN_TEXT" )->item(0)->nodeValue;

							$room_cost[] = array("price_date" => $price_date,
													"option_id" => $token,
													"quantity" => $room_count,
													"adult" => $adult_count,
													"child" => $child_count,
													"price_id" => $sell_price,
													"criteria_id" => $search_id[1],
													"occupancyid" => $occupancy
													);  
						}
					}
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
						"board_type" => $this->ci->db->escape_str($blank),
						"board_code" => $this->ci->db->escape_str($blank),
						"board_short_name" => $this->ci->db->escape_str($blank),
						"inclusion" => $this->ci->db->escape_str($inclusion),
						"token" => $this->ci->db->escape_str($token),
						"shuri" => $this->ci->db->escape_str($blank),
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
						"status" => $this->ci->db->escape_str($status)
					);
				}
			}
		}
		if(!empty($data))
			$this->ci->hotel_model->set_hotel_details($api->id, $search_id[1], $data);
	}
}