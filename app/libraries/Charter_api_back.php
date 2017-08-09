<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab.
	################################################################
*/

class Charter_API {

	private $ci;
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model("Flight_model");
	}

	public function low_fare_air_search($api_data, $search_data)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$trip_type = $srch_dtls->flight_type;
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/charter_api/search/".$api_data->id.$hash.".json";
		$rsearch_file = FCPATH."app/cache/charter_api/search/r".$api_data->id.$hash.".json";
		$response = $r_response = false;
		if($response === false)
		{
			if($api_data->status === "2")
				$main_url = $api_data->live_url;
			elseif($api_data->status === "1")
				$main_url = $api_data->test_url;
			else
				exit;
			$url = $main_url;
			$from_place = $srch_dtls->flight_origin;
			$to_place = $srch_dtls->flight_destination;
			$from_date_arr = explode("-", $srch_dtls->flight_departure);
			$from_date = to_julian_date($from_date_arr[0], $from_date_arr[1], $from_date_arr[2]);
			$intr_day = 0;
			$url .= "From=".$from_place."&To=".$to_place."&StartDate=".$from_date."&IntervalDay=".$intr_day."&FlightNumber=&RecordId=1&PageSize=1000&SortOrder=1";
			$response = $this->curl($url); 
			file_put_contents($search_file, $response);
			if($trip_type === ROUNDTRIP)
			{
				$to_date_arr = explode("-", $srch_dtls->flight_arrival);
				$to_date = to_julian_date($to_date_arr[0], $to_date_arr[1], $to_date_arr[2]);
				$url = $main_url;
				$url .= "From=".$to_place."&To=".$from_place."&StartDate=".$to_date."&IntervalDay=".$intr_day."&FlightNumber=&RecordId=1&PageSize=1000&SortOrder=1";
				$r_response = $this->curl($url);
				file_put_contents($rsearch_file, $r_response);
			}
		}
		
		$result = false;
		if($response !== false)
		{
			$this->ci->Flight_model->clear_flight_list($search_data->id, $api_data->id);
			$res_arr = $this->parse_flights($api_data, $search_data, $response, $r_response, $trip_type);
			$result = json_encode(array("status" => true, "result" => $res_arr));
		}
		print_r($result);
	}

	public function air_rules($api_data, $search_data, $fare_source_code)
	{
		$result = array("status" => true, "result" => "Please Contact airport for details.", "type" => TYPE_STRING);
		return $result;
	}

	public function air_revalidate($api_data, $search_data, $flight_details, $cached = "0")
	{
		$services = array();
		$new_res["extra_service_json"] = json_encode($services);
		$this->ci->Flight_model->flight_price_updated($flight_details->id, $new_res);
		$result = array("status" => "true", "result" => "Success", "redirect" => "false", "type" => TYPE_STRING);
	}

	public function air_baggages($api_data, $search_data, $fare_source_code)
	{
		$result = array("status" => true, "result" => "Please Contact airport for details.", "type" => TYPE_STRING);
		return $result;
	}

	public function air_book($api_data, $search_data, $flight_details, $details, $reserve = true)
	{

	}

	public function air_cancel($api_data, $ticket_id)
	{

	}

	public function air_confirm($api_data, $ticket_id)
	{

	}

	public function air_message_queue($session, $status)
	{

	}

	public function air_multi_revalidate($api_data, $search_data, $fare_source_codes)
	{

	}

	public function air_book_notes($session, $book_code, $notes)
	{

	}

	public function air_trip_details($session, $book_code)
	{

	}

	public function air_book_details($api_data, $ticket_id)
	{

	}

	public function close_connection($api_data)
	{

	}

	public function parse_flights($api_data, $search_data, $d_itineraries, $a_itineraries, $flight_type = ONEWAY)
	{
		$d_itineraries = json_decode($d_itineraries);
		if($a_itineraries !== false)
			$a_itineraries = json_decode($a_itineraries);
		$srch_dtls = json_decode($search_data->input_json);
		$airlines = array();
		$temp_res = $this->ci->Flight_model->get_all_airlines();
		foreach ($temp_res as $obj)
			$airlines[$obj->airline_code] = $obj;

		$person_types = array("1" => "Adult", "2" => "Child", "3" => "Infant");
		$fare_types = array("1" => "Default", "2" => "Public", "3" => "Private", "4" => "WebFare");
		$skip_results = array("سیستمی");
		$adult_count = $srch_dtls->adult;
		$child_count = $srch_dtls->child;
		$infant_count = $srch_dtls->infant;
		$total_passengers = $adult_count + $child_count + $infant_count;
		$markups = $this->ci->Flight_model->get_admin_b2c_markup($api_data->id);
		$curr_value = $this->ci->currency_converter->currency_val(IRR);
		$res_arr = array();
		if(isset($d_itineraries->FlightList))
		foreach ($d_itineraries->FlightList as $flight_obj)
		{
			if($flight_obj->IsAds === true)
				continue;
			if($flight_obj->Quantity[0]->Quantity < ($total_passengers + 3))
				continue;
			foreach ($skip_results as $skip_value)
				if(!empty($flight_obj->ToolTip) && strripos($flight_obj->ToolTip, $skip_value) !== false)
					continue;
			$temp_res = array();
			$temp_res["search_id"] = $search_data->id;
			$temp_res["api"] = $api_data->id;
			$temp_res["api_name"] = $api_data->api_code;
			$temp_res["flight_type"] = $flight_type;
			$temp_res["results"] = "0";
			$temp_res["fare_source_code"] = $flight_obj->Id;
			$temp_res["airline"] = $flight_obj->AirLineIataCode;
			$prices = $flight_obj->PriceList[0];
			$temp_res["duration"] = 0;
			$temp_res["api_tax"] = 0;
			$total_trip_price = ((($adult_count + $child_count) * ($prices->Amount * TOMAN)) + ($infant_count * DOMESTIC_INFANT_PRICE));
			$temp_res["api_cost"] = $total_trip_price;

			$admin_cost = $total_trip_price;
			// if($markups !== false)
			// {
			// 	if($markups->mt_type === "2")
			// 		$admin_cost = ((($adult_count + $child_count) * $prices->Amount) + ($infant_count * DOMESTIC_INFANT_PRICE)) + floor(($markups->mt_amount / 100) * ((($adult_count + $child_count) * $prices->Amount) + ($infant_count * DOMESTIC_INFANT_PRICE)));
			// 	else
			// 		$admin_cost = ((($adult_count + $child_count) * $prices->Amount) + ($infant_count * DOMESTIC_INFANT_PRICE)) + floor($curr_value * $markups->mt_amount);
			// }

			$temp_res["admin_cost"] = $admin_cost;
			$temp_res["total_cost"] = $admin_cost;
			$temp_res["currency"] = IRR;
			$temp_res["fare_type"] = $fare_types[2];
			if($adult_count > 0)
			{
				$temp_price_breakdown = array();
				$temp_price_breakdown["person_type"] = $person_types[1];
				$temp_price_breakdown["quantity"] = $adult_count;
				$temp_price_breakdown["api_base_cost"] = ($prices->Amount * TOMAN);
				$temp_price_breakdown["api_total_cost"] = ($prices->Amount * TOMAN);

				$admin_base_cost = ($prices->Amount * TOMAN);
				$admin_total_cost = ($prices->Amount * TOMAN);
				// if($markups !== false)
				// {
				// 	if($markups->mt_type === "2")
				// 	{
				// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
				// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
				// 	}
				// 	else
				// 	{
				// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
				// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
				// 	}
				// }
				
				$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
				$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
				$temp_price_breakdown["base_cost"] = $admin_base_cost;
				$temp_price_breakdown["total_cost"] = $admin_total_cost;
				$temp_price_breakdown["currency"] = IRR;
				$tax_amount = 0;
				$temp_price_breakdown["api_tax"] = $tax_amount;
				$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
				$temp_price_breakdown["tax_details"] = array();
				$temp_res["prices"][] = $temp_price_breakdown;
			}
			if($child_count > 0)
			{
				$temp_price_breakdown = array();
				$temp_price_breakdown["person_type"] = $person_types[2];
				$temp_price_breakdown["quantity"] = $child_count;
				$temp_price_breakdown["api_base_cost"] = ($prices->Amount * TOMAN);
				$temp_price_breakdown["api_total_cost"] = ($prices->Amount * TOMAN);

				$admin_base_cost = ($prices->Amount * TOMAN);
				$admin_total_cost = ($prices->Amount * TOMAN);
				// if($markups !== false)
				// {
				// 	if($markups->mt_type === "2")
				// 	{
				// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
				// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
				// 	}
				// 	else
				// 	{
				// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
				// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
				// 	}
				// }
				
				$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
				$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
				$temp_price_breakdown["base_cost"] = $admin_base_cost;
				$temp_price_breakdown["total_cost"] = $admin_total_cost;
				$temp_price_breakdown["currency"] = IRR;
				$tax_amount = 0;
				$temp_price_breakdown["api_tax"] = $tax_amount;
				$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
				$temp_price_breakdown["tax_details"] = array();
				$temp_res["prices"][] = $temp_price_breakdown;
			}
			if($infant_count > 0)
			{
				$temp_price_breakdown = array();
				$temp_price_breakdown["person_type"] = $person_types[3];
				$temp_price_breakdown["quantity"] = $infant_count;
				$temp_price_breakdown["api_base_cost"] = DOMESTIC_INFANT_PRICE;
				$temp_price_breakdown["api_total_cost"] = DOMESTIC_INFANT_PRICE;

				$admin_base_cost = DOMESTIC_INFANT_PRICE;
				$admin_total_cost = DOMESTIC_INFANT_PRICE;
				// if($markups !== false)
				// {
				// 	if($markups->mt_type === "2")
				// 	{
				// 		$admin_base_cost = DOMESTIC_INFANT_PRICE + floor(($markups->mt_amount / 100) * DOMESTIC_INFANT_PRICE);
				// 		$admin_total_cost = DOMESTIC_INFANT_PRICE + floor(($markups->mt_amount / 100) * DOMESTIC_INFANT_PRICE);
				// 	}
				// 	else
				// 	{
				// 		$admin_base_cost = DOMESTIC_INFANT_PRICE + floor($curr_value * $markups->mt_amount);
				// 		$admin_total_cost = DOMESTIC_INFANT_PRICE + floor($curr_value * $markups->mt_amount);
				// 	}
				// }
				
				$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
				$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
				$temp_price_breakdown["base_cost"] = $admin_base_cost;
				$temp_price_breakdown["total_cost"] = $admin_total_cost;
				$temp_price_breakdown["currency"] = IRR;
				$tax_amount = 0;
				$temp_price_breakdown["api_tax"] = $tax_amount;
				$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
				$temp_price_breakdown["tax_details"] = array();
				$temp_res["prices"][] = $temp_price_breakdown;
			}

			$oneway = array();
			$start_time = $flight_obj->DepartureTime;
			$end_time = $flight_obj->ArrivalTime;

			$dept_dttm = substring_index(substring_index($flight_obj->DepartureDateTime, "(", "-1"), ")", "1") / EPOCH_BY;
			$start_datetime = date("Y-m-d H:i:s", $dept_dttm);
			$dept_dt = substring_index($start_datetime, " ", 1);

			$stop_cnt = 0;
			$temp_res["stops"] = $stop_cnt > 2 ? 2 : $stop_cnt;
			$temp_res["departure_dttm"] = $start_datetime;
			if($end_time === "00:00")
				$end_datetime = "0000-00-00 00:00:00";
			elseif(strtotime($start_time) > strtotime($end_time))
				$end_datetime = date("Y-m-d H:i:s", strtotime("+1 days", strtotime($dept_dt." ".$end_time)));
			else
				$end_datetime = date("Y-m-d H:i:s", strtotime($dept_dt." ".$end_time));
			$temp_res["origin_destination"][] = array(
												"departure_dttm" => $start_datetime,
												"arrival_dttm" => $end_datetime,
												"departure_loc" => $flight_obj->SourceLocationCode,
												"arrival_loc" => $flight_obj->DestinationLocationCode,
												"airline" => $flight_obj->AirLineIataCode,
												"flight_no" => $flight_obj->FlightNumber
												);
			$temp_dept = array();
			$temp_dept["departure_dttm"] = $start_datetime;
			$temp_dept["arrival_dttm"] = $end_datetime;
			$temp_dept["stop"] = $stop_cnt;
			$temp_dept["seats"] = $flight_obj->Quantity[0]->Quantity;
			$temp_dept["book_url"] = $flight_obj->AgencyAddress;
			$temp_dept["flight_no"] = $flight_obj->FlightNumber;
			$temp_dept["airline"] = $temp_res["airline"] = $flight_obj->AirLineIataCode;
			$temp_dept["airline_type"] = $flight_obj->AirPlane;
			$temp_dept["operating_airline"] = $flight_obj->AirLineIataCode;
			$temp_dept["airline_name"] = $airlines[$flight_obj->AirLineIataCode]->airline_name;
			$temp_dept["operating_airline_name"] = $airlines[$flight_obj->AirLineIataCode]->airline_name;
			$temp_res["airport"][] = $temp_dept["departure_from"] = $flight_obj->SourceLocationCode;
			$temp_res["airport"][] = $temp_dept["arrival_to"] = $flight_obj->DestinationLocationCode;
			$temp_dept["duration"] = 0;
			$temp_res["duration"] += 0;
			$temp_dept["cabin_type"] = $prices->PriceByFareClass->FareClassTitle;
			$oneway[] = $temp_dept;

			$return_res = array();
			$temp_res["departures"] = $oneway;
			$temp_res["arrivals"] = $return_res;
			$temp_res["departure_tm"] = $start_time;
			$temp_res["arrival_tm"] = "00:00";
			$temp = array_unique($temp_res["airport"]);
			$temp_res["airport"] = str_replace(",,", ",", ",".implode(",", $temp).",");
			$res_arr[] = $temp_res;
		}
		if($a_itineraries !== false && !is_null($a_itineraries))
		{
			foreach ($a_itineraries->FlightList as $flight_obj)
			{
				if($flight_obj->IsAds === true)
					continue;
				if($flight_obj->Quantity[0]->Quantity < ($total_passengers + 3))
					continue;
				foreach ($skip_results as $skip_value)
					if(!empty($flight_obj->ToolTip) && strripos($flight_obj->ToolTip, $skip_value) !== false)
						continue;
				$temp_res = array();
				$temp_res["search_id"] = $search_data->id;
				$temp_res["api"] = $api_data->id;
				$temp_res["api_name"] = $api_data->api_code;
				$temp_res["flight_type"] = $flight_type;
				$temp_res["results"] = "1";
				$temp_res["fare_source_code"] = $flight_obj->Id;
				$temp_res["airline"] = $flight_obj->AirLineIataCode;
				$prices = $flight_obj->PriceList[0];
				$temp_res["duration"] = 0;
				$temp_res["api_tax"] = 0;
				$total_trip_price = ((($adult_count + $child_count) * ($prices->Amount * TOMAN)) + ($infant_count * DOMESTIC_INFANT_PRICE));
				$temp_res["api_cost"] = $total_trip_price;

				$admin_cost = $total_trip_price;
				// if($markups !== false)
				// {
				// 	if($markups->mt_type === "2")
				// 		$admin_cost = ((($adult_count + $child_count) * $prices->Amount) + ($infant_count * DOMESTIC_INFANT_PRICE)) + floor(($markups->mt_amount / 100) * ((($adult_count + $child_count) * $prices->Amount) + ($infant_count * DOMESTIC_INFANT_PRICE)));
				// 	else
				// 		$admin_cost = ((($adult_count + $child_count) * $prices->Amount) + ($infant_count * DOMESTIC_INFANT_PRICE)) + floor($curr_value * $markups->mt_amount);
				// }

				$temp_res["admin_cost"] = $admin_cost;
				$temp_res["total_cost"] = $admin_cost;
				$temp_res["currency"] = IRR;
				$temp_res["fare_type"] = $fare_types[2];
				if($adult_count > 0)
				{
					$temp_price_breakdown = array();
					$temp_price_breakdown["person_type"] = $person_types[1];
					$temp_price_breakdown["quantity"] = $adult_count;
					$temp_price_breakdown["api_base_cost"] = ($prices->Amount * TOMAN);
					$temp_price_breakdown["api_total_cost"] = ($prices->Amount * TOMAN);

					$admin_base_cost = ($prices->Amount * TOMAN);
					$admin_total_cost = ($prices->Amount * TOMAN);
					// if($markups !== false)
					// {
					// 	if($markups->mt_type === "2")
					// 	{
					// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
					// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
					// 	}
					// 	else
					// 	{
					// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
					// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
					// 	}
					// }
					
					$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
					$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
					$temp_price_breakdown["base_cost"] = $admin_base_cost;
					$temp_price_breakdown["total_cost"] = $admin_total_cost;
					$temp_price_breakdown["currency"] = IRR;
					$tax_amount = 0;
					$temp_price_breakdown["api_tax"] = $tax_amount;
					$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
					$temp_price_breakdown["tax_details"] = array();
					$temp_res["prices"][] = $temp_price_breakdown;
				}
				if($child_count > 0)
				{
					$temp_price_breakdown = array();
					$temp_price_breakdown["person_type"] = $person_types[2];
					$temp_price_breakdown["quantity"] = $child_count;
					$temp_price_breakdown["api_base_cost"] = ($prices->Amount * TOMAN);
					$temp_price_breakdown["api_total_cost"] = ($prices->Amount * TOMAN);

					$admin_base_cost = ($prices->Amount * TOMAN);
					$admin_total_cost = ($prices->Amount * TOMAN);
					// if($markups !== false)
					// {
					// 	if($markups->mt_type === "2")
					// 	{
					// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
					// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor(($markups->mt_amount / 100) * ($prices->Amount * TOMAN));
					// 	}
					// 	else
					// 	{
					// 		$admin_base_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
					// 		$admin_total_cost = ($prices->Amount * TOMAN) + floor($curr_value * $markups->mt_amount);
					// 	}
					// }
					
					$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
					$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
					$temp_price_breakdown["base_cost"] = $admin_base_cost;
					$temp_price_breakdown["total_cost"] = $admin_total_cost;
					$temp_price_breakdown["currency"] = IRR;
					$tax_amount = 0;
					$temp_price_breakdown["api_tax"] = $tax_amount;
					$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
					$temp_price_breakdown["tax_details"] = array();
					$temp_res["prices"][] = $temp_price_breakdown;
				}
				if($infant_count > 0)
				{
					$temp_price_breakdown = array();
					$temp_price_breakdown["person_type"] = $person_types[3];
					$temp_price_breakdown["quantity"] = $infant_count;
					$temp_price_breakdown["api_base_cost"] = DOMESTIC_INFANT_PRICE;
					$temp_price_breakdown["api_total_cost"] = DOMESTIC_INFANT_PRICE;

					$admin_base_cost = DOMESTIC_INFANT_PRICE;
					$admin_total_cost = DOMESTIC_INFANT_PRICE;
					// if($markups !== false)
					// {
					// 	if($markups->mt_type === "2")
					// 	{
					// 		$admin_base_cost = DOMESTIC_INFANT_PRICE + floor(($markups->mt_amount / 100) * DOMESTIC_INFANT_PRICE);
					// 		$admin_total_cost = DOMESTIC_INFANT_PRICE + floor(($markups->mt_amount / 100) * DOMESTIC_INFANT_PRICE);
					// 	}
					// 	else
					// 	{
					// 		$admin_base_cost = DOMESTIC_INFANT_PRICE + floor($curr_value * $markups->mt_amount);
					// 		$admin_total_cost = DOMESTIC_INFANT_PRICE + floor($curr_value * $markups->mt_amount);
					// 	}
					// }
					
					$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
					$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
					$temp_price_breakdown["base_cost"] = $admin_base_cost;
					$temp_price_breakdown["total_cost"] = $admin_total_cost;
					$temp_price_breakdown["currency"] = IRR;
					$tax_amount = 0;
					$temp_price_breakdown["api_tax"] = $tax_amount;
					$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
					$temp_price_breakdown["tax_details"] = array();
					$temp_res["prices"][] = $temp_price_breakdown;
				}

				$oneway = array();
				$start_time = $flight_obj->DepartureTime;
				$end_time = $flight_obj->ArrivalTime;
				$arrv_dttm = substring_index(substring_index($flight_obj->DepartureDateTime, "(", "-1"), ")", "1") / EPOCH_BY;
				$start_datetime = date("Y-m-d H:i:s", $arrv_dttm);
				$arrv_dt = substring_index($start_datetime, " ", 1);

				$stop_cnt = 0;
				$temp_res["stops"] = $stop_cnt > 2 ? 2 : $stop_cnt;
				$temp_res["departure_dttm"] = $start_datetime;
				if($end_time === "00:00")
					$end_datetime = "0000-00-00 00:00:00";
				elseif(strtotime($start_time) > strtotime($end_time))
					$end_datetime = date("Y-m-d H:i:s", strtotime("+1 days", strtotime($arrv_dt." ".$end_time)));
				else
					$end_datetime = date("Y-m-d H:i:s", strtotime($arrv_dt." ".$end_time));
				$temp_res["origin_destination"][] = array(
													"departure_dttm" => $start_datetime,
													"arrival_dttm" => $end_datetime,
													"departure_loc" => $flight_obj->SourceLocationCode,
													"arrival_loc" => $flight_obj->DestinationLocationCode,
													"airline" => $flight_obj->AirLineIataCode,
													"flight_no" => $flight_obj->FlightNumber
													);
				$temp_dept = array();
				$temp_dept["departure_dttm"] = $start_datetime;
				$temp_dept["arrival_dttm"] = $end_datetime;
				$temp_dept["stop"] = $stop_cnt;
				$temp_dept["seats"] = $flight_obj->Quantity[0]->Quantity;
				$temp_dept["book_url"] = $flight_obj->AgencyAddress;
				$temp_dept["flight_no"] = $flight_obj->FlightNumber;
				$temp_dept["airline"] = $temp_res["airline"] = $flight_obj->AirLineIataCode;
				$temp_dept["airline_type"] = $flight_obj->AirPlane;
				$temp_dept["operating_airline"] = $flight_obj->AirLineIataCode;
				$temp_dept["airline_name"] = $airlines[$flight_obj->AirLineIataCode]->airline_name;
				$temp_dept["operating_airline_name"] = $airlines[$flight_obj->AirLineIataCode]->airline_name;
				$temp_res["airport"][] = $temp_dept["departure_from"] = $flight_obj->SourceLocationCode;
				$temp_res["airport"][] = $temp_dept["arrival_to"] = $flight_obj->DestinationLocationCode;
				$temp_dept["duration"] = 0;
				$temp_res["duration"] += 0;
				$temp_dept["cabin_type"] = $prices->PriceByFareClass->FareClassTitle;
				$oneway[] = $temp_dept;

				$return_res = array();
				$temp_res["departures"] = $oneway;
				$temp_res["arrivals"] = $return_res;
				$temp_res["departure_tm"] = $start_time;
				$temp_res["arrival_tm"] = "00:00";
				$temp = array_unique($temp_res["airport"]);
				$temp_res["airport"] = str_replace(",,", ",", ",".implode(",", $temp).",");
				$res_arr[] = $temp_res;
			}
		}
		return $res_arr;
	}

	public function curl($url)
	{
		$curl_init = curl_init();
		$httpHeader = array(
							//"SOAPAction: {$action}", 
							"Content-Type: application/json; charset=UTF-8",
							"Content-Encoding: UTF-8",
							"Accept-Encoding: gzip,deflate"
					);
		curl_setopt($curl_init, CURLOPT_URL, $url);
		curl_setopt($curl_init, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_init, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl_init, CURLOPT_HEADER, false);
		curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_init, CURLOPT_POST, false);
		curl_setopt($curl_init, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl_init, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip, deflate");
		$response = curl_exec($curl_init);
		$status = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
		curl_close($curl_init);
		return $status === 200 ? $response :false;
	}

}