<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab.
	################################################################
*/

class Hotel_Beds {

	public $ci;
	protected $username = "";
	protected $password = "";
	protected $url = "";
	function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model("Hotel_model");
	}

	function hotels_list($api, $search_data, $start = "1", $limit = "999")
	{
		$sd = json_decode($search_data->input_json);
		$rooms = $sd->rooms;
		$city_id = $sd->hotel_city;
		$nights = $sd->nights;
		$adults = $sd->adult;
		$children = $sd->children;
		$check_in = date("Ymd", strtotime($sd->check_in));
		$check_out = date("Ymd", strtotime($sd->check_out));
		$token = strtotime($search_data->created);
		$city_details = $this->ci->Hotel_model->city_by_id($city_id);
		if($city_details !== false)
			$city_code = $city_details->hotel_beds;
		else
			exit;
		if($api->status === "2")
		{
			$this->username = $api->live_user;
			$this->password = $api->live_pwd;
			$this->url = $api->live_url;
		}
		else
		{
			$this->username = $api->test_user;
			$this->password = $api->test_pwd;
			$this->url = $api->test_url;
		}

		$hotel_occupancy = "";
		$r_types = array();
		for($i = 0; $i < $rooms; $i++)
		{
			$condition = $adults[$i].":".$children[$i];
			$r_types[$condition]["qty"] = isset($r_types[$condition]["qty"]) ? ($r_types[$condition]["qty"] + 1) : 1;
			$r_types[$condition]["adult_count"] = $adults[$i];
			$r_types[$condition]["child_count"] = $children[$i];
			if($children[$i] > 0)
			{
				$cld_age = "";
				$child_age = $sd->child_age[$i];
				foreach ($child_age as $value)
				{
					$cld_age .= "<Customer type = 'CH'>
											<Age>" . $value . "</Age>
										</Customer>
								";
				}
				$r_types[$condition]["child_ages"] = isset($r_types[$condition]["child_ages"]) ? $r_types[$condition]["child_ages"].",".$cld_age : $cld_age;
			}
		}
		foreach ($r_types as $room)
		{
			$hotel_occupancy .="<HotelOccupancy>
								<RoomCount>" . $room["qty"]. "</RoomCount>
									<Occupancy>
										<AdultCount>".$room["adult_count"]."</AdultCount>
										<ChildCount>".$room["child_count"]."</ChildCount>
										";

					if(isset($room["child_ages"]))
						$hotel_occupancy .= "<GuestList>".$room["child_ages"]."</GuestList>";
						$hotel_occupancy .= "</Occupancy>
								</HotelOccupancy>
								";
		}

		$request = "<HotelValuedAvailRQ echoToken='DummyEchoToken' sessionId='".$token."' xmlns='http://www.hotelbeds.com/schemas/2005/06/messages' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.hotelbeds.com/schemas/2005/06/messages HotelValuedAvailRQ.xsd' showDiscountsList='Y' version='2011/01'>
						<Language>ENG</Language>
						<Credentials>
							<User>".$this->username."</User>
							<Password>".$this->password."</Password>
						</Credentials>
						<PaginationData itemsPerPage='".$limit."' pageNumber='".$start."'/>
						<CheckInDate date='".$check_in."'/>
						<CheckOutDate date='".$check_out."'/>
						<Destination code='".$city_code."' type='SIMPLE'/>
						<OccupancyList>
								".$hotel_occupancy." 
						</OccupancyList>
					</HotelValuedAvailRQ>";
		file_put_contents(FCPATH."app/cache/hotel_beds/request/hotels_".$search_data->id.".xml", $request);

		$response = $this->curl($this->url, $request);
		file_put_contents(FCPATH."app/cache/hotel_beds/response/hotels_".$search_data->id.".xml", $response);

		$dom = new DOMDocument();
		$dom->loadXML($response);
		$sh = $dom->getElementsByTagName("ServiceHotel");
		$result = array();
		foreach($sh as $val)
		{
			$currency_node = $val->getElementsByTagName("Currency");
			$currency = $currency_node->item(0)->getAttribute("code");

			$package_rate_node = $val->getElementsByTagName("PackageRate");
			$package_rate = $package_rate_node->item(0)->nodeValue;

			if($package_rate === "N")
			{
				$hotel_infos = $val->getElementsByTagName("HotelInfo");
				foreach($hotel_infos as $hotel_info)
				{
					$code_node = $hotel_info->getElementsByTagName("Code");
					$hotel_code = $code_node->item(0)->nodeValue;

					$zone_node = $hotel_info->getElementsByTagName("Zone");
					$area = $zone_node->item(0)->nodeValue;
					break;
				}

				$hotel_rooms = $val->getElementsByTagName("AvailableRoom");
				foreach($hotel_rooms as $hotel_room)
				{
					$amount = $hotel_room->getElementsByTagName("Amount");
					$board = $hotel_room->getElementsByTagName("Board")->item(0)->getAttribute("shortname"); // RO,EB,CB,BB
					if(!($board =="RO" || $board =="HB" || $board =="FB" || $board !="BB" || $board =="EB" || $board =="CB" || $board =="SC")) $board = "OO";
						$api_cost = $amount->item(0)->nodeValue;
						$admin_cost = $total_cost = $api_cost;
						if(!isset($result[$hotel_code]) || (isset($result[$hotel_code]) && $result[$hotel_code]->total_cost > $total_cost))
						$result[$hotel_code] = (object)array("search_id" => $search_data->id, "hotel_code" => $hotel_code, "api" => $api->id, "api_name" => $api->api_code, "api_cost" => $api_cost, "admin_cost" => $admin_cost, "total_cost" => $total_cost, "currency" => $currency, "extra" => array("board" => $board, "area" => $area));
				}
			}
		}
		return json_encode($result);
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
		$check_in = date("Ymd", strtotime($sd->check_in_dt));
		$check_out = date("Ymd", strtotime($sd->check_out_dt));
		$city_code = $sd->hotel_beds;
		if($api->status === "2")
		{
			$this->username = $api->live_user;
			$this->password = $api->live_pwd;
			$this->url = $api->live_url;
		}
		else
		{
			$this->username = $api->test_user;
			$this->password = $api->test_pwd;
			$this->url = $api->test_url;
		}

		$hotel_occupancy = "";
		$r_types = array();
		for($i = 0; $i < $rooms; $i++)
		{
			$condition = $adults[$i].":".$children[$i];
			$r_types[$condition]["qty"] = isset($r_types[$condition]["qty"]) ? ($r_types[$condition]["qty"] + 1) : 1;
			$r_types[$condition]["adult_count"] = $adults[$i];
			$r_types[$condition]["child_count"] = $children[$i];
			if($children[$i] > 0)
			{
				$cld_age = "";
				$child_age = "childAge_".($i + 1);
				$child_age = $sd->$child_age;
				foreach ($child_age as $value)
				{
					$cld_age .= "<Customer type = 'CH'>
											<Age>" . $value . "</Age>
										</Customer>
								";
				}
				$r_types[$condition]["child_ages"] = isset($r_types[$condition]["child_ages"]) ? $r_types[$condition]["child_ages"].$cld_age : $cld_age;
			}
		}
		foreach ($r_types as $room)
		{
			$hotel_occupancy .="<HotelOccupancy>
								<RoomCount>" . $room["qty"]. "</RoomCount>
									<Occupancy>
										<AdultCount>".$room["adult_count"]."</AdultCount>
										<ChildCount>".$room["child_count"]."</ChildCount>
										";

					if(isset($room["child_ages"]))
						$hotel_occupancy .= "<GuestList>".$room["child_ages"]."</GuestList>";
						$hotel_occupancy .= "</Occupancy>
								</HotelOccupancy>
								";
		}

		$request = "<HotelValuedAvailRQ xmlns='http://www.hotelbeds.com/schemas/2013/04/messages' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' echoToken='DummyEchoToken' sessionId='" .$search_id[0] . "' xsi:schemaLocation='http://www.hotelbeds.com/schemas/2013/04/messages HotelValuedAvailRQ.xsd' showDiscountsList='Y' version='2013/12'>
						<Language>ENG</Language>
						<Credentials>
							<User>".$this->username."</User>
							<Password>".$this->password."</Password>
						</Credentials>
						<PaginationData itemsPerPage='1000' pageNumber='1'/>
						<CheckInDate date='".$check_in."'/>
						<CheckOutDate date='".$check_out."'/>
						<Destination code='".$city_code."' type='SIMPLE'/>
						<OccupancyList>
							   ".$hotel_occupancy." 
						</OccupancyList>
						<HotelCodeList withinResults='Y'>
							<ProductCode>".$hotel_code."</ProductCode>
						</HotelCodeList>
						<ExtraParamList>
							<ExtendedData type='EXT_ADDITIONAL_PARAM'>
								<Name>PARAM_KEY_PRICE_BREAKDOWN</Name>
								<Value>Y</Value>
							</ExtendedData>
							<ExtendedData type='EXT_DISPLAYER'>
								<Name>DISPLAYER_DEFAULT</Name>
								<Value>PROMOTION:Y</Value>
							</ExtendedData>
							<ExtendedData type='EXT_ADDITIONAL_PARAM'>
								<Name>PARAM_SHOW_OPAQUE_CONTRACT</Name>
								<Value>Y</Value>
							</ExtendedData>
						</ExtraParamList>
					<ShowCancellationPolicies>Y</ShowCancellationPolicies>
				</HotelValuedAvailRQ>";
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
		curl_setopt($curl_init, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip,deflate");
		$xmls = curl_exec($curl_init);
		curl_close($curl_init);
		$data = array();
		$blank = "";
		$dom = new DOMDocument();
		$dom->loadXML($xmls);

		$hotel_service = $dom->getElementsByTagName("ServiceHotel");

		foreach ($hotel_service as $hotel)
		{
			$hotel_info = $hotel->getElementsByTagName("HotelInfo");
			$api_hotel_code = null;
			if($hotel_info->item(0))
				$api_hotel_code = $hotel_info->item(0)->getElementsByTagName("Code")->item(0)->nodeValue;
			else  
				exit;
			if($hotel_code === $api_hotel_code)
			{
				$token = $hotel->getAttribute("availToken");
				$incoming_office = $hotel->getElementsByTagName("IncomingOffice")->item(0)->getAttribute("code");
				$dest_code = $hotel->getElementsByTagName("Destination")->item(0)->getAttribute("code");
				$api_currency = $hotel->getElementsByTagName("Currency")->item(0)->getAttribute("code");
				$package_rate = $hotel->getElementsByTagName("PackageRate")->item(0)->nodeValue;
				if($package_rate === "N")
				{
					$observations = $hotel->getElementsByTagName("Observations");
					$promotions_text = array();
					foreach ($observations as $observation)
						$promotions_text[] = $observation->nodeValue;
					$promotions_text = !empty($promotions_text) ? implode(",", $promotions_text) : "";
					$remarks = $hotel->getElementsByTagName("Remark");
					$remarks_text = array();
					foreach ($remarks as $remark)
						$remarks_text[] = $remark->nodeValue;
					$remarks_text = !empty($remarks_text) ? implode(",", $remarks_text) : "";

					$contracts = $hotel->getElementsByTagName("Contract");
					$contract_name = "";
					foreach ($contracts as $contract)
					{
						$contract_name_node = $contract->getElementsByTagName("Name");
						$contract_name = $contract_name_node->item(0)->nodeValue;
						break;
					}

					$hotel_rooms = $hotel->getElementsByTagName("AvailableRoom");
					foreach ($hotel_rooms as $hotel_room)
					{
						$room_count = $hotel_room->getElementsByTagName("RoomCount")->item(0)->nodeValue;

						$adult_count = $hotel_room->getElementsByTagName("AdultCount")->item(0)->nodeValue;

						$child_count = $hotel_room->getElementsByTagName("ChildCount")->item(0)->nodeValue;

						$cancel_cost = $api_cost = $admin_cost = $agent_cost = $total_cost = $hotel_room->getElementsByTagName("Amount")->item(0)->nodeValue;

						$cancel_policy = "For Cancellation Policy continue with booking.";
						$cancel_deadline = "0000-00-00";
						if($hotel_room->getElementsByTagName("CancellationPolicies")->item(0))
						{
							$cp = $hotel_room->getElementsByTagName("CancellationPolicies")->item(0)->getElementsByTagName("CancellationPolicy")->item(0);
							$cancel_cost = $cp->getAttribute("amount");
							$cancel_deadline = date("Y-m-d", strtotime($cp->getAttribute("dateFrom")));
							$cancel_policy = "\$$amountvc will be charged after Cancel DeadLine $cancel_till_date";
						}

						$shrui = $hotel_room->getElementsByTagName("HotelRoom")->item(0)->getAttribute("SHRUI");

						$board_node = $hotel_room->getElementsByTagName("Board");
						$board_type = $board_node->item(0)->getAttribute("type");
						$board_code = $board_node->item(0)->getAttribute("code");
						$board_short_name = $board_node->item(0)->getAttribute("shortname");
						$inclusion = $board_node->item(0)->nodeValue;

						$room_type_node = $hotel_room->getElementsByTagName("RoomType");
						$room_type = $room_type_node->item(0)->nodeValue;
						$room_code = $room_type_node->item(0)->getAttribute("code");
						$room_classification = $room_type_node->item(0)->getAttribute("type");
						$room_characteristic = $room_type_node->item(0)->getAttribute("characteristic");

						$room_details = json_encode(array($adult_count.":".$child_count => array("adult" => $adult_count, "child" => $child_count, "rooms" => $room_count)));
						$costs = json_encode(array(array("user" => "api", "markup" => "0", "markup_type" => "$", "amount" => $api_cost, "currency" => $api_currency, "currency_val" => "1.00"),array("user" => "admin", "markup" => "0", "markup_type" => "$", "amount" => $admin_cost, "currency" => "USD", "currency_val" => "1.00")));

						$charges = json_encode(array(array("charge" => "payment_gateway", "markup" => "0", "markup_type" => "$", "amount" => "0", "currency" => "USD", "currency_val" => "1.00")));

						$data[] = array(
							"search_id" => $this->ci->db->escape_str($search_id[1]),
							"api" => $this->ci->db->escape_str($api->api_id),
							"hotel_code" => $this->ci->db->escape_str($hotel_code),
							"dest_code" => $this->ci->db->escape_str($dest_code),
							"room_code" => $this->ci->db->escape_str($room_code),
							"room_type" => $this->ci->db->escape_str($room_type),
							"room_characteristic" => $this->ci->db->escape_str($room_characteristic),
							"room_classification" => $this->ci->db->escape_str($room_classification),
							"room_details" => $this->ci->db->escape_str($room_details),
							"board_type" => $this->ci->db->escape_str($board_type),
							"board_code" => $this->ci->db->escape_str($board_code),
							"board_short_name" => $this->ci->db->escape_str($board_short_name),
							"inclusion" => $this->ci->db->escape_str($inclusion),
							"token" => $this->ci->db->escape_str($token),
							"shuri" => $this->ci->db->escape_str($shrui),
							"contract_name" => $this->ci->db->escape_str($contract_name),
							"api_currency" => $this->ci->db->escape_str($api_currency),
							"costs" => $this->ci->db->escape_str($costs),
							"charges" => $this->ci->db->escape_str($charges),
							"city" => $this->ci->db->escape_str($blank),
							"room_cost" => $this->ci->db->escape_str($blank),
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
							"incoming_office" => $this->ci->db->escape_str($incoming_office),
							"promotions" => $this->ci->db->escape_str($promotions_text),
							"offers" => $this->ci->db->escape_str($offers),
							"remarks" => $this->ci->db->escape_str($remarks_text),
							"status" => $this->ci->db->escape_str("Available")
						);
					}
				}
			}
		}
		if(!empty($data))
			$this->ci->hotel_model->set_hotel_details($api->id, $search_id[1], $data);
	}

	public function prebooking_request($search_id, $api, $search_data, $hotel_code, $room_list, $extra)
	{
		$sd = json_decode($search_data);
		$rooms = $sd->rooms;
		$city_code = $sd->city_id;
		$nights = $sd->nights;
		$adults = $sd->adult;
		$children = $sd->child;
		$check_in = date("Ymd", strtotime($sd->check_in_dt));
		$check_out = date("Ymd", strtotime($sd->check_out_dt));
		$token = $room_list[0]->token;
		$contract_name = $room_list[0]->contract_name;
		$incoming_office = $room_list[0]->incoming_office;
		$hotel_code = $room_list[0]->hotel_code;
		$city_id = $room_list[0]->dest_code;
		$city_code = $sd->hotels_bed;
		if($api->status === "2")
		{
			$this->username = $api->live_user;
			$this->password = $api->live_pwd;
			$this->url = $api->live_url;
		}
		else
		{
			$this->username = $api->test_user;
			$this->password = $api->test_pwd;
			$this->url = $api->test_url;
		}

		$r_types = array();
		for($i = 0; $i < $rooms; $i++)
		{
			$condition = $adults[$i].":".$children[$i];
			$r_types[$condition]["qty"] = isset($r_types[$condition]["qty"]) ? ($r_types[$condition]["qty"] + 1) : 1;
			$r_types[$condition]["adult_count"] = $adults[$i];
			$r_types[$condition]["child_count"] = $children[$i];
			if($children[$i] > 0)
			{
				$cld_age = "";
				$child_age = "childAge_".($i + 1);
				$child_age = $sd->$child_age;
				foreach ($child_age as $value)
				{
					$cld_age .= "<Customer type = 'CH'>
											<Age>" . $value . "</Age>
										</Customer>
								";
				}
				$r_types[$condition]["child_ages"] = isset($r_types[$condition]["child_ages"]) ? $r_types[$condition]["child_ages"].$cld_age : $cld_age;
			}
		}
		$avail_room = "";
		foreach($room_list as $v)
		{
			$avail_room .= "<AvailableRoom>
								<HotelOccupancy>
									<RoomCount>".$v->room_count."</RoomCount>
									<Occupancy>
										<AdultCount>".$v->adult_count."</AdultCount>
										<ChildCount>".$v->child_count."</ChildCount>
										<GuestList>
											<Customer type='AD'><Age>45</Age></Customer>";
						if($v->child_count > 0)
						$avail_room .= $r_types[$v->adult_count.":".$v->child_count]["child_ages"];
						$avail_room .= '</GuestList>
									</Occupancy>
								</HotelOccupancy>
								<HotelRoom SHRUI="'.$v->shuri.'">
									<Board type="'.$v->board_type.'" code="'.$v->board_code.'">'.$v->inclusion.'</Board>
									<RoomType type="'.$v->room_classification.'" code="'.$v->room_code.'" characteristic="'.$v->room_characteristic.'">'.$v->room_type.'</RoomType>
								</HotelRoom>
							</AvailableRoom>';
		}
		$request = "<?xml version='1.0' encoding='UTF-8'?>
						<ServiceAddRQ echoToken='DummyEchoToken' xmlns='http://www.hotelbeds.com/schemas/2005/06/messages' xsi:schemaLocation='http://www.hotelbeds.com/schemas/2005/06/messages ../xsd/ServiceAddRQ.xsd' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' version='2013/12'>
							<Language>ENG</Language>
							<Credentials>
							<User>".$this->username."</User>
							<Password>".$this->password."</Password>
							</Credentials>
							<Service availToken='".$token."' xsi:type='ServiceHotel'>
								<ContractList>
									<Contract>
										<Name>".$contract_name."</Name>   
										<IncomingOffice code='".$incoming_office."'/>
									</Contract>
								</ContractList>
								<DateFrom date='".$check_in."'/>
								<DateTo date='".$check_out."'/>
								<HotelInfo xsi:type='ProductHotel'>
									<Code>".$hotel_code."</Code>
									<Destination type='SIMPLE' code='".$city_id."'/>
								</HotelInfo>
								".$avail_room."                             
							</Service>
						</ServiceAddRQ>";
		$curl_init = curl_init();
		$httpHeader = array(
					"Content-Type: text/xml; charset=UTF-8",
					"Content-Encoding: UTF-8",
					"Accept-Encoding: gzip,deflate"
				);
		curl_setopt($curl_init, CURLOPT_URL, $this->url);
		curl_setopt($curl_init, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_init, CURLOPT_TIMEOUT, 580);
		curl_setopt($curl_init, CURLOPT_HEADER, 0);
		curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_init, CURLOPT_POST, 1);
		curl_setopt($curl_init, CURLOPT_POSTFIELDS, "$request");
		curl_setopt($curl_init, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl_init, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip,deflate");
		$xmls = curl_exec($curl_init);
		$res_status = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
		curl_close($curl_init);
		$dom = new DOMDocument();
		$dom->loadXML($xmls);
		$data = array("error" => true, "response_status" => false);
		$is_error = $dom->getElementsByTagName("Message")->item(0)->NodeValue;

		if(empty($is_error))
		{
			$data["error"] = false;
			if($res_status === 200)
			{
				$data["response_status"] = true;
				$purchase_node = $dom->getElementsByTagName("Purchase");
				$data["purchase_token"] = $purchase_node->item(0)->getAttribute("purchaseToken");
				$data["total_price"] = $data["api_total_price"] = $purchase_node->item(0)->getElementsByTagName("TotalPrice")->item(0)->nodeValue;
				$data["currency"] = $purchase_node->item(0)->getElementsByTagName("Currency")->item(0)->getAttribute("code");

				$service_node = $dom->getElementsByTagName("Service");
				$data["spui"] = $service_node->item(0)->getAttribute("SPUI");
				$c_in = $dom->getElementsByTagName("DateFrom")->item(0)->getAttribute("date");
				$c_yr = substr($c_in, 0, 4);
				$c_mn = substr($c_in, 4, 2);
				$c_dt = substr($c_in, 6, 2);
				$c_in = $c_yr."-".$c_mn."-".$c_dt;
				$data["check_in"] = $c_in;
				$c_out = $dom->getElementsByTagName("DateTo")->item(0)->getAttribute("date");
				$c_yr = substr($c_out, 0, 4);
				$c_mn = substr($c_out, 4, 2);
				$c_dt = substr($c_out, 6, 2);
				$c_out = $c_yr."-".$c_mn."-".$c_dt;
				$data["check_out"] = $c_out;

				$supplier_node = $dom->getElementsByTagName("Supplier");
				$data["supplier_vat"] = $supplier_node->item(0)->getAttribute("vatNumber");
				$data["supplier_name"] = $supplier_node->item(0)->getAttribute("name");

				$data["comments"] = array();
				$contracts = $dom->getElementsByTagName("Contract");
				foreach($contracts as $contract_node)
				{
					$comments_list = $contract_node->getElementsByTagName("CommentList");
					foreach($comments_list as $comments)
					{
						$comment = $comments->getElementsByTagName("Comment");
						array_push($data["comments"], $comment->item(0)->nodeValue);
					}
				}

				$room_details = $cancellation_policies = $current_policy = $current_room = array();
				$available_rooms = $dom->getElementsByTagName("AvailableRoom");
				foreach($available_rooms as $room_key => $available_room)
				{
					$policy_list = $available_room->getElementsByTagName("CancellationPolicy");
					$current_policy = array();
					foreach($policy_list as $policies)
					{
						$cancel_from = $policies->getAttribute("dateFrom");
						$c_yr = substr($cancel_from, 0, 4);
						$c_mn = substr($cancel_from, 4, 2);
						$c_dt = substr($cancel_from, 6, 2);
						$cancel_from = $c_yr."-".$c_mn."-".$c_dt;
						$current_policy[] = array("cancel_from" => $cancel_from, "cancel_till" => $policies->getAttribute("time"), "cancel_amount" => $policies->getAttribute("amount"));
					}
					$cancellation_policies[$room_key] = $current_policy;

					$occupancy_list = $available_room->getElementsByTagName("HotelOccupancy");
					foreach($occupancy_list as $ho)
					{
						$current_room = array();
						$current_room["room_count"] = $ho->getElementsByTagName("RoomCount")->item(0)->nodeValue;
						$occupancies = $ho->getElementsByTagName("Occupancy");
						foreach($occupancies as $occ)
						{
							$current_room["adult_count"] = $occ->getElementsByTagName("AdultCount")->item(0)->nodeValue;
							$current_room["child_count"] = $occ->getElementsByTagName("ChildCount")->item(0)->nodeValue;

							$guest_list = $occ->getElementsByTagName("GuestList");
							foreach($guest_list as $gl)
							{
								$customer = $gl->getElementsByTagName("Customer");
								foreach($customer as $cus)
								{
									$current_room["customer_type"][] = $cus->getAttribute("type");
									$current_room["customer_id"][] = $cus->getElementsByTagName("CustomerId")->item(0)->nodeValue;
									$current_room["customer_age"][] = $cus->getElementsByTagName("Age")->item(0)->nodeValue;
								}
							}
						}
					}
					$room_details[$room_key] = $current_room;
				}
				$data["cancel_policies"] = $cancellation_policies;
				$data["rooms"] = $room_details;
				$data["hash_file"] = $hash_file = base_encode($search_id)."_".time();
				if(@$data["cancel_policies"])
					file_put_contents("cart/$hash_file.txt", json_encode($data));
			}
		}
		return $data;
	}

	public function curl($url, $request)
	{
		$curl_init = curl_init();
		$httpHeader = array(
							//"SOAPAction: {$action}", 
							"Content-Type: application/xml; charset=UTF-8",
							"Content-Encoding: UTF-8",
							"Accept-Encoding: gzip,deflate"
					);
		curl_setopt($curl_init, CURLOPT_URL, $url);
		curl_setopt($curl_init, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl_init, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_init, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl_init, CURLOPT_HEADER, 0);
		curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_init, CURLOPT_POST, 1);
		curl_setopt($curl_init, CURLOPT_POSTFIELDS, "$request");
		curl_setopt($curl_init, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl_init, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip,deflate");
		$response = curl_exec($curl_init);
		$status = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
		curl_close($curl_init);
		return $status === 200 ? $response :false;
	}

}