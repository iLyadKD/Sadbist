<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab.
	################################################################
*/

class Partocrs {

	private $ci;
	private $office_id;
	private $username;
	private $password;
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model("Flight_model");
		$this->office_id = "CRS001151";
		$this->username = "SEFID_XML";
		$this->password = "xml123";
	}

	public function authenticate($api_data)
	{
		$office_id = $api_data->extra;
		if($api_data->status === "2")
		{
			$username = $api_data->live_user;
			$password = $api_data->live_pwd;
			$url = $api_data->live_url;
		}
		elseif($api_data->status === "1")
		{
			$username = $api_data->test_user;
			$password = $api_data->test_pwd;
			$url = $api_data->test_url;
		}
		else
			return $result = array("status" => false, "result" => "Invalid API Credentials.");

		// reuse existing session
		$valid_session = $this->ci->Flight_model->get_flight_session($api_data->id);
		if($valid_session !== false && strtotime($valid_session->expires) > strtotime("+0 minutes", time()))
			return array("status" => true, "result" => $valid_session->session);

		$xml = "<CreateSession xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
					<OfficeId>".$office_id."</OfficeId>
					<Password>".$password."</Password>
					<UserName>".$username."</UserName>
				</CreateSession>";
		$response = $this->curl($url."Authenticate/CreateSession", $xml);
		$result = array("status" => false, "result" => "Invalid XML Response.");
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
			{
				$session_data = array("id" => $api_data->id, "session" => $response->SessionId, "expires" => date("Y-m-d H:i:s", strtotime("+14 minutes", time())));
				$this->ci->Flight_model->set_flight_session($session_data);
				$result = array("status" => true, "result" => $response->SessionId);
			}
			else
				$result = array("status" => false, "result" => $response->Error->Message);

		}
		return $result;

	}

	public function low_fare_air_search($api_data, $search_data)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$trip_type = $srch_dtls->flight_type;
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/partocrs/search/".$api_data->id.$hash.".json";
		$response = false;
		if(file_exists($search_file))
			if(filemtime($search_file) > strtotime("-2 minutes", time()))
				$response = file_get_contents($search_file);
		if($response === false)
		{
			if($api_data->status === "2")
				$url = $api_data->live_url;
			elseif($api_data->status === "1")
				$url = $api_data->test_url;
			else
				exit;

			$valid_session = $this->authenticate($api_data);
			if($valid_session !== false && $valid_session["status"] !== false)
				$session = $valid_session["result"]; // new or existing session
			else
				return $valid_session["result"];

			$cabin_type = $srch_dtls->class;
			$stops = "All";
			
			if($srch_dtls->flight_type === MULTICITY)
			{
				$adult_count = $srch_dtls->madult;
				$child_count = $srch_dtls->mchild;
				$infant_count = $srch_dtls->minfant;
				$destination_details = "";
				$length = count($srch_dtls->mflight_origin);
				for ($i = 0; $i < $length; $i++)
				{ 
					$destination_details .= "<d2p1:OriginDestinationInformation>
												<d2p1:DepartureDateTime>".date("Y-m-d\TH:i:s", strtotime($srch_dtls->mflight_departure[$i]))."</d2p1:DepartureDateTime>
												<d2p1:DestinationLocationCode>".$srch_dtls->mflight_destination[$i]."</d2p1:DestinationLocationCode>
												<d2p1:OriginLocationCode>".$srch_dtls->mflight_origin[$i]."</d2p1:OriginLocationCode>
											</d2p1:OriginDestinationInformation>";
				}
			}
			else
			{
				$adult_count = $srch_dtls->adult;
				$child_count = $srch_dtls->child;
				$infant_count = $srch_dtls->infant;
				if($srch_dtls->flight_type === ONEWAY)
				{// time format 2016-06-04T16:51:44.0170242+04:30
					$destination_details = "<d2p1:OriginDestinationInformation>
												<d2p1:DepartureDateTime>".date("Y-m-d\TH:i:s", strtotime($srch_dtls->flight_departure))."</d2p1:DepartureDateTime>
												<d2p1:DestinationLocationCode>".$srch_dtls->flight_destination."</d2p1:DestinationLocationCode>
												<d2p1:OriginLocationCode>".$srch_dtls->flight_origin."</d2p1:OriginLocationCode>
											</d2p1:OriginDestinationInformation>";
				}
				else
				{
					$destination_details = "<d2p1:OriginDestinationInformation>
												<d2p1:DepartureDateTime>".date("Y-m-d\TH:i:s", strtotime($srch_dtls->flight_departure))."</d2p1:DepartureDateTime>
												<d2p1:DestinationLocationCode>".$srch_dtls->flight_destination."</d2p1:DestinationLocationCode>
												<d2p1:OriginLocationCode>".$srch_dtls->flight_origin."</d2p1:OriginLocationCode>
											</d2p1:OriginDestinationInformation>
											<d2p1:OriginDestinationInformation>
												<d2p1:DepartureDateTime>".date("Y-m-d\TH:i:s", strtotime($srch_dtls->flight_arrival))."</d2p1:DepartureDateTime>
												<d2p1:DestinationLocationCode>".$srch_dtls->flight_origin."</d2p1:DestinationLocationCode>
												<d2p1:OriginLocationCode>".$srch_dtls->flight_destination."</d2p1:OriginLocationCode>
											</d2p1:OriginDestinationInformation>";
				}
			}
			$xml = "<AirLowFareSearch xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<AdultCount>".$adult_count."</AdultCount>
						<ChildCount>".$child_count."</ChildCount>
						<InfantCount>".$infant_count."</InfantCount>
						<NearByAirports>true</NearByAirports>
						<OriginDestinationInformations xmlns:d2p1='http://schemas.datacontract.org/2004/07/ServiceModel.Entities'>
							".$destination_details."
						</OriginDestinationInformations>
						<PricingSourceType>All</PricingSourceType>
						<RequestOption>Fifty</RequestOption>
						<SessionId>".$session."</SessionId>
						<TravelPreference xmlns:d2p1='http://schemas.datacontract.org/2004/07/ServiceModel.Entities'>
							<d2p1:AirTripType>".$trip_type."</d2p1:AirTripType>
							<d2p1:MaxStopsQuantity>".$stops."</d2p1:MaxStopsQuantity>
							".($cabin_type === "" ? "" : "<d2p1:CabinType>".$cabin_type."</d2p1:CabinType>")."
						</TravelPreference>
					</AirLowFareSearch>";
			$response = $this->curl($url."Air/AirLowFareSearch", $xml);
			$temp_res = json_decode($response);
			if($temp_res->Success === true)
				file_put_contents($search_file, $response);
		}
		
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
			{
				$this->ci->Flight_model->clear_flight_list($search_data->id, $api_data->id);
				//echo "<pre>";print_r($response);echo "</pre>";die;
				$res_arr = $this->parse_flights($api_data, $search_data, $response->PricedItineraries);
				$result = json_encode(array("status" => true, "result" => $res_arr));
			}
			else
				$result = json_encode(array("status" => false, "result" => $response->Error->Message));
		}
		print_r($result);
	}

	public function air_rules($api_data, $search_data, $fare_source_code)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/partocrs/rules/".$api_data->id.$hash.$fare_source_code.".json";
		$response = false;
		if(file_exists($search_file))
			if(filemtime($search_file) > strtotime("-2 minutes", time()))
				$response = file_get_contents($search_file);
		if($response === false)
		{
			if($api_data->status === "2")
				$url = $api_data->live_url;
			elseif($api_data->status === "1")
				$url = $api_data->test_url;
			else
				exit;

			$valid_session = $this->authenticate($api_data);
			if($valid_session !== false && $valid_session["status"] !== false)
				$session = $valid_session["result"]; // new or existing session
			else
				return $valid_session["result"];

			$xml = "<AirRules xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<FareSourceCode>".$fare_source_code."</FareSourceCode>
						<SessionId>".$session."</SessionId>
					</AirRules>";
			$response = $this->curl($url."Air/AirRules", $xml);
			$temp_res = json_decode($response);
			if($temp_res->Success === true)
				file_put_contents($search_file, $response);
		}
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
				$result = array("status" => true, "result" => json_encode($response->FareRules));
			else
			{
				$search_file = FCPATH."app/cache/partocrs/search/".$api_data->id.$hash.".json";
				if(file_exists($search_file))
					unlink($search_file);
				$result = array("status" => false, "result" => $response->Error->Message);
			}
		}
		return $result;
	}

	public function air_revalidate($api_data, $search_data, $flight_details, $cached = "0")
	{
		$srch_dtls = json_decode($search_data->input_json);
		$fare_source_code = $flight_details->fare_source_code;
		$hash = $search_data->hash;
		$redirect = false;
		$search_file = FCPATH."app/cache/partocrs/revalidate/".$api_data->id.$hash.$fare_source_code.".json";
		$response = false;
		if($cached === "0")
			if(file_exists($search_file))
				if(filemtime($search_file) > strtotime("-2 minutes", time()))
					$response = file_get_contents($search_file);
		if($response === false)
		{
			if($api_data->status === "2")
				$url = $api_data->live_url;
			elseif($api_data->status === "1")
				$url = $api_data->test_url;
			else
				exit;

			$valid_session = $this->authenticate($api_data);
			if($valid_session !== false && $valid_session["status"] !== false)
				$session = $valid_session["result"]; // new or existing session
			else
				return $valid_session["result"];

			$xml = "<AirRevalidate xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<FareSourceCode>".$fare_source_code."</FareSourceCode>
						<SessionId>".$session."</SessionId>
					</AirRevalidate>";
			$response = $this->curl($url."Air/AirRevalidate", $xml);
			$temp_res = json_decode($response);
			if(isset($temp_res->Success) && $temp_res->Success === true)
			{
				file_put_contents($search_file, $response);
				$markups = $this->ci->Flight_model->get_admin_b2c_markup($api_data->id);
				$curr_value = $this->ci->currency_converter->currency_val("IRR");
				$new_res = array();
				if($temp_res->PricedItinerary->FareSourceCode !== $fare_source_code)
				{
					$itinerary = array("0" => $temp_res->PricedItinerary);
					$new_res_arr = $this->parse_flights($api_data, $search_data, $itinerary);
					$new_res = (array)$new_res_arr[0];
					$this->modify_search_cache($api_data, $search_data, $fare_source_code, $temp_res);
					$search_file_new = FCPATH."app/cache/partocrs/revalidate/".$api_data->id.$hash.$temp_res->PricedItinerary->FareSourceCode.".json";
					file_put_contents($search_file_new, $response);
					$redirect = true;
				}
				$services = array();
				foreach ($temp_res->Services as $srvc_idx => $srvc_obj)
				{
					$temp = array();
					$temp["desc"] = $srvc_obj->Description;
					$temp["id"] = $srvc_obj->ServiceId;
					$temp["type"] = $srvc_obj->Type;
					$temp["api_amount"] = $srvc_obj->ServiceCost->Amount;
					$admin_cost = $srvc_obj->ServiceCost->Amount;
					// if($markups !== false)
					// {
					// 	if($markups->mt_type === "2")
					// 		$admin_cost = $srvc_obj->ServiceCost->Amount + floor(($markups->mt_amount / 100) * $srvc_obj->ServiceCost->Amount);
					// 	else
					// 		$admin_cost = $srvc_obj->ServiceCost->Amount + floor($curr_value * $markups->mt_amount);
					// }
					$temp["amount"] = $admin_cost;
					$temp["currency"] = $srvc_obj->ServiceCost->Currency;
					$services[] = $temp;
				}
				$new_res["extra_service_json"] = json_encode($services);
				$this->ci->Flight_model->flight_price_updated($flight_details->id, $new_res);
			}
			else
				$redirect = true;
		}
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
				$result = array("status" => "true", "result" => "Success", "redirect" => "$redirect");
			else
			{
				$search_file = FCPATH."app/cache/partocrs/search/".$api_data->id.$hash.".json";
				if(file_exists($search_file))
					unlink($search_file);
				$error_msg = "No Error Message to display";
				if(!is_null($response->Error))
					$error_msg = $response->Error->Message;
				$result = array("status" => "false", "result" => $error_msg, "redirect" => "true");
			}
		}
		return $result;
	}

	public function air_baggages($api_data, $search_data, $fare_source_code)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/partocrs/baggages/".$api_data->id.$hash.$fare_source_code.".json";
		$response = false;
		if(file_exists($search_file))
			if(filemtime($search_file) > strtotime("-2 minutes", time()))
				$response = file_get_contents($search_file);
		if($response === false)
		{
			if($api_data->status === "2")
				$url = $api_data->live_url;
			elseif($api_data->status === "1")
				$url = $api_data->test_url;
			else
				exit;

			$valid_session = $this->authenticate($api_data);
			if($valid_session !== false && $valid_session["status"] !== false)
				$session = $valid_session["result"]; // new or existing session
			else
				return $valid_session["result"];

			$xml = "<AirBaggages xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<FareSourceCode>".$fare_source_code."</FareSourceCode>
						<SessionId>".$session."</SessionId>
					</AirBaggages>";
			$response = $this->curl($url."Air/AirBaggages", $xml);
			$temp_res = json_decode($response);
			if($temp_res->Success === true)
				file_put_contents($search_file, $response);
		}
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
				$result = array("status" => true, "result" => json_encode($response->BaggageInfoes));
			else
			{
				$search_file = FCPATH."app/cache/partocrs/search/".$api_data->id.$hash.".json";
				if(file_exists($search_file))
					unlink($search_file);
				$error_msg = "No Error Message to display";
				if(!is_null($response->Error))
					$error_msg = $response->Error->Message;
				$result = array("status" => false, "result" => $error_msg);
			}
		}
		return $result;
	}

	public function air_book($api_data, $search_data, $flight_details, $details, $reserve = true)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/partocrs/book/".$api_data->id.$search_data->id.$flight_details->fare_source_code.".json";
		$response = false;
		if(file_exists($search_file))
			return array("status" => false, "result" => "You are resubmitting same request.");
		if($reserve === true and $flight_details->fare_type === "WebFare")
			return array("status" => false, "result" => "This flight cannot be reserved.");
		if($response === false)
		{
			if($api_data->status === "2")
				$url = $api_data->live_url;
			elseif($api_data->status === "1")
				$url = $api_data->test_url;
			else
				exit;

			$valid_session = $this->authenticate($api_data);
			if($valid_session !== false && $valid_session["status"] !== false)
				$session = $valid_session["result"]; // new or existing session
			else
				return $valid_session["result"];

			$adult_cnt = isset($details->adult_salutation) ? count($details->adult_salutation) : 0;
			$child_cnt = isset($details->child_salutation) ? count($details->child_salutation) : 0;
			$infant_cnt = isset($details->infant_salutation) ? count($details->infant_salutation) : 0;
			$travellers_details = "";
			$gender_list = array("0" => "Male", "1" => "Female", "2" => "Female", "3" => "Female", "4" => "Male");
			$salutations = array("0" => "Mr", "1" => "Mrs", "2" => "Ms", "3" => "Miss", "4" => "Mstr");

			// if(isset($details->baggage_opt) && !empty($details->baggage_opt))
			// 	$travellers_details .= "<d2p1:ExtraServiceId>".$details->baggage_opt."</d2p1:ExtraServiceId>";
			for($i = 0; $i < $adult_cnt; $i++)
			{
				$national_id = isset($details->adult_national_id[$i]) && !empty($details->adult_national_id[$i]) ? "<d2p1:NationalId>".$details->adult_national_id[$i]."</d2p1:NationalId>" : "";
				$extra_service = isset($details->adult_baggage[$i]) && !empty($details->adult_baggage[$i]) ? "<d2p1:ExtraServiceId>".$details->adult_baggage[$i]."</d2p1:ExtraServiceId>" : "";
				$travellers_details .= "<d2p1:AirTraveler>".$national_id."".$extra_service."
											<d2p1:DateOfBirth>".date("Y-m-d\TH:i:s", strtotime($details->adult_dob[$i]))."</d2p1:DateOfBirth>
											<d2p1:Gender>".$gender_list[$details->adult_salutation[$i]]."</d2p1:Gender>
											<d2p1:PassengerName>
												<d2p1:PassengerFirstName>".$details->adult_fname[$i]."</d2p1:PassengerFirstName>
												<d2p1:PassengerLastName>".$details->adult_lname[$i]."</d2p1:PassengerLastName>
												<d2p1:PassengerTitle>".$salutations[$details->adult_salutation[$i]]."</d2p1:PassengerTitle>
											</d2p1:PassengerName>
											<d2p1:PassengerType>Adt</d2p1:PassengerType>
											<d2p1:Passport>
												<d2p1:Country>".$details->adult_nationality[$i]."</d2p1:Country>
												<d2p1:ExpiryDate>".date("Y-m-d\TH:i:s", strtotime($details->adult_passport_expire[$i]))."</d2p1:ExpiryDate>
												<d2p1:PassportNumber>".$details->adult_passport[$i]."</d2p1:PassportNumber>
											</d2p1:Passport>
										</d2p1:AirTraveler>
									</d2p1:AirTravelers>";
			}
			for($i = 0; $i < $child_cnt; $i++)
			{
				$national_id = isset($details->child_national_id[$i]) && !empty($details->child_national_id[$i]) ? "<d2p1:NationalId>".$details->child_national_id[$i]."</d2p1:NationalId>" : "";
				$extra_service = isset($details->child_baggage[$i]) && !empty($details->child_baggage[$i]) ? "<d2p1:ExtraServiceId>".$details->child_baggage[$i]."</d2p1:ExtraServiceId>" : "";
				$travellers_details .= "<d2p1:AirTraveler>".$national_id."".$extra_service."
											<d2p1:DateOfBirth>".date("Y-m-d\TH:i:s", strtotime($details->child_dob[$i]))."</d2p1:DateOfBirth>
											<d2p1:Gender>".$gender_list[$details->child_salutation[$i]]."</d2p1:Gender>
											<d2p1:PassengerName>
												<d2p1:PassengerFirstName>".$details->child_fname[$i]."</d2p1:PassengerFirstName>
												<d2p1:PassengerLastName>".$details->child_lname[$i]."</d2p1:PassengerLastName>
												<d2p1:PassengerTitle>".$salutations[$details->child_salutation[$i]]."</d2p1:PassengerTitle>
											</d2p1:PassengerName>
											<d2p1:PassengerType>Chd</d2p1:PassengerType>
											<d2p1:Passport>
												<d2p1:Country>".$details->child_nationality[$i]."</d2p1:Country>
												<d2p1:ExpiryDate>".date("Y-m-d\TH:i:s", strtotime($details->child_passport_expire[$i]))."</d2p1:ExpiryDate>
												<d2p1:PassportNumber>".$details->child_passport[$i]."</d2p1:PassportNumber>
											</d2p1:Passport>
										</d2p1:AirTraveler>
									</d2p1:AirTravelers>";
			}
			for($i = 0; $i < $infant_cnt; $i++)
			{
				$national_id = isset($details->infant_national_id[$i]) && !empty($details->infant_national_id[$i]) ? "<d2p1:NationalId>".$details->infant_national_id[$i]."</d2p1:NationalId>" : "";
				$extra_service = isset($details->infant_baggage[$i]) && !empty($details->infant_baggage[$i]) ? "<d2p1:ExtraServiceId>".$details->infant_baggage[$i]."</d2p1:ExtraServiceId>" : "";
				$travellers_details .= "<d2p1:AirTraveler>".$national_id."".$extra_service."
											<d2p1:DateOfBirth>".date("Y-m-d\TH:i:s", strtotime($details->infant_dob[$i]))."</d2p1:DateOfBirth>
											<d2p1:Gender>".$gender_list[$details->infant_salutation[$i]]."</d2p1:Gender>
											<d2p1:PassengerName>
												<d2p1:PassengerFirstName>".$details->infant_fname[$i]."</d2p1:PassengerFirstName>
												<d2p1:PassengerLastName>".$details->infant_lname[$i]."</d2p1:PassengerLastName>
												<d2p1:PassengerTitle>".$salutations[$details->infant_salutation[$i]]."</d2p1:PassengerTitle>
											</d2p1:PassengerName>
											<d2p1:PassengerType>Inf</d2p1:PassengerType>
											<d2p1:Passport>
												<d2p1:Country>".$details->infant_nationality[$i]."</d2p1:Country>
												<d2p1:ExpiryDate>".date("Y-m-d\TH:i:s", strtotime($details->infant_passport_expire[$i]))."</d2p1:ExpiryDate>
												<d2p1:PassportNumber>".$details->infant_passport[$i]."</d2p1:PassportNumber>
											</d2p1:Passport>
										</d2p1:AirTraveler>
									</d2p1:AirTravelers>";
			}
			$travellers_details .= "<d2p1:AreaCode>21</d2p1:AreaCode>
									<d2p1:CountryCode>0098</d2p1:CountryCode>
									<d2p1:Email>".$details->email."</d2p1:Email>
									<d2p1:PhoneNumber>".$details->contact."</d2p1:PhoneNumber>";
			header("Content-Type: text/xml");
			echo $xml = "<AirBook xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<FareSourceCode>".$flight_details->fare_source_code."</FareSourceCode>
						<SessionId>".$session."</SessionId>
						<TravelerInfo xmlns:d2p1='http://schemas.datacontract.org/2004/07/ServiceModel.Entities'>
							<d2p1:AirTravelers>
								".$travellers_details."
						</TravelerInfo>
					</AirBook>";die;
			$req_file = FCPATH."app/cache/partocrs/book/request/".$api_data->id.$search_data->id.$flight_details->fare_source_code.".json";
			$err_file = FCPATH."app/cache/partocrs/book/error/".$api_data->id.$search_data->id.$flight_details->fare_source_code.".json";
				file_put_contents($req_file, $xml);
			$response = $this->curl($url."Air/AirBook", $xml);
			$temp_res = json_decode($response);
			if($temp_res->Success === true)
				file_put_contents($search_file, $response);
			else
				file_put_contents($err_file, $response);
		}
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
			{
				$comfirm_stat = false;
				if($flight_details->fare_type === "WebFare")
					$confirm_stat = true;
				$result = array("status" => true, "result" => json_encode(array("ticket_id" => $response->UniqueId, "deadline" => $response->TktTimeLimit, "reserve" => true, "confirm" => $comfirm_stat)));
				if(!$reserve && $confirm_stat === false)
				{
					$crfm_res = $this->air_confirm($api_data, $response->UniqueId);
					if($crfm_res !== false)
					{
						$crfm_res = json_decode($crfm_res);
						if($crfm_res->Success === false)
						{
							if(file_exists($search_file))
								unlink($search_file);
							$result = array("status" => false, "result" => $crfm_res->result);
						}
						else
							$result["confirm"] = true;
					}
				}
			}
			else
			{
				$error_msg = "No Error Message to display";
				if(!is_null($response->Error))
					$error_msg = $response->Error->Message;
				$result = array("status" => false, "result" => $error_msg);
			}
		}
		return $result;
	}

	public function air_cancel($api_data, $ticket_id)
	{
		$search_file = FCPATH."app/cache/partocrs/cancel/".$api_data->id.$ticket_id.".json";
		$response = false;
		if(file_exists($search_file))
			return json_encode(array("status" => false, "result" => "Already ticket has been cancelled."));
		if($api_data->status === "2")
			$url = $api_data->live_url;
		elseif($api_data->status === "1")
			$url = $api_data->test_url;
		else
			exit;

		$valid_session = $this->authenticate($api_data);
		if($valid_session !== false && $valid_session["status"] !== false)
			$session = $valid_session["result"]; // new or existing session
		else
			return $valid_session["result"];

		$xml = "<AirCancel xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
					<SessionId>".$session."</SessionId>
					<UniqueId>".$ticket_id."</UniqueId>
				</AirCancel>";
		$response = $this->curl($url."Air/AirCancel", $xml);
		$temp_res = json_decode($response);
		if($temp_res->Success === true)
			file_put_contents($search_file, $response);
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
				$result = json_encode(array("status" => true, "result" => "Success"));
			else
			{
				$error_msg = "No Error Message to display";
				if(!is_null($response->Error))
					$error_msg = $response->Error->Message;
				$result = array("status" => false, "result" => $error_msg);
			}
		}
		return $result;
	}

	public function air_confirm($api_data, $ticket_id)
	{
		$search_file = FCPATH."app/cache/partocrs/confirm/".$api_data->id.$ticket_id.".json";
		$response = false;
		if(file_exists($search_file))
			return json_encode(array("status" => false, "result" => "Already ticket ID is already booked"));
		if($api_data->status === "2")
			$url = $api_data->live_url;
		elseif($api_data->status === "1")
			$url = $api_data->test_url;
		else
			exit;

		$valid_session = $this->authenticate($api_data);
		if($valid_session !== false && $valid_session["status"] !== false)
			$session = $valid_session["result"]; // new or existing session
		else
			return $valid_session["result"];

		$xml = "<AirOrderTicket xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns=http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
					<SessionId>".$session."</SessionId>
					<UniqueId>".$ticket_id."</UniqueId>
				</AirOrderTicket>";
		$response = $this->curl($url."Air/AirOrderTicket", $xml);
		$temp_res = json_decode($response);
		if($temp_res->Success === true)
			file_put_contents($search_file, $response);
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
				$result = json_encode(array("status" => true, "result" => "Success"));
			else
			{
				$error_msg = "No Error Message to display";
				if(!is_null($response->Error))
					$error_msg = $response->Error->Message;
				$result = array("status" => false, "result" => $error_msg);
			}
		}
		return $result;
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
		$srch_dtls = json_decode($search_data->input_json);
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/partocrs/book_data/".$api_data->id.$ticket_id.".json";
		$response = false;
		if(file_exists($search_file))
			if(filemtime($search_file) > strtotime("-2 minutes", time()))
				$response = file_get_contents($search_file);
		if($response === false)
		{
			if($api_data->status === "2")
				$url = $api_data->live_url;
			elseif($api_data->status === "1")
				$url = $api_data->test_url;
			else
				exit;

			$valid_session = $this->authenticate($api_data);
			if($valid_session !== false && $valid_session["status"] !== false)
				$session = $valid_session["result"]; // new or existing session
			else
				return $valid_session["result"];

			$xml = "<AirBookingData xmlns:i=http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<SessionId>".$session."</SessionId>
						<UniqueId>".$ticket_id."</UniqueId>
					</AirBookingData>";
			$response = $this->curl($url."Air/AirBookingData", $xml);
			$temp_res = json_decode($response);
			if($temp_res->Success === true)
				file_put_contents($search_file, $response);
		}
		$result = false;
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
				$result = array("status" => true, "result" => json_encode($response));
			else
			{
				$error_msg = "No Error Message to display";
				if(!is_null($response->Error))
					$error_msg = $response->Error->Message;
				$result = array("status" => false, "result" => $error_msg);
			}
		}
		return $result;
	}

	public function close_connection($api_data)
	{
		if($api_data->status === "2")
			$url = $api_data->live_url;
		elseif($api_data->status === "1")
			$url = $api_data->test_url;
		else
			return $result = array("status" => false, "result" => "Invalid API Credentials.");

		// reuse existing session
		$valid_session = $this->ci->Flight_model->get_flight_session($api_data->id);
		if($valid_session !== false && strtotime($valid_session->expires) > strtotime("+0 minutes", time()))
			$session = $valid_session->session;
		$result = true;
		if(isset($session))
		{
			$xml = "<EndSession xmlns:i='http://www.w3.org/2001/XMLSchema-instance' xmlns='http://schemas.datacontract.org/2004/07/ServiceModel.Request'>
						<SessionId>".$session."</SessionId>
					</EndSession>";
			$response = $this->curl($this->url."Authenticate/EndSession", $xml);
			$result = false;
			if($response !== false)
			{
				$response = json_decode($response);
				$status = $response->Success;
				if($status === true)
				{
					$this->ci->Flight_model->clear_flight_session($api_data->id);
					$result = array("status" => true, "result" => "Success");
				}
				else
				{
					$error_msg = "No Error Message to display";
					if(!is_null($response->Error))
						$error_msg = $response->Error->Message;
					$result = array("status" => false, "result" => $error_msg);
				}

			}
		}
		return $result;
	}

	public function curl($url, $request)
	{
		// echo $request;die;
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
		curl_setopt($curl_init, CURLOPT_ENCODING, "gzip, deflate");
		$response = curl_exec($curl_init);
		$status = curl_getinfo($curl_init, CURLINFO_HTTP_CODE);
		curl_close($curl_init);
		return $status === 200 ? $response :false;
	}

	public function parse_flights($api_data, $search_data, $itineraries)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$trip_type = $srch_dtls->flight_type;
		$airlines = array();
		$temp_res = $this->ci->Flight_model->get_all_airlines();
		foreach ($temp_res as $obj)
			$airlines[$obj->airline_code] = $obj;

		$person_types = array("1" => "Adult", "2" => "Child", "3" => "Infant");
		$flight_types = array("1" => "OneWay", "2" => "Return", "3" => "Circle", "4" => "OpenJaw");
		$fare_types = array("1" => "Default", "2" => "Public", "3" => "Private", "4" => "WebFare");
		$cabin_types = array("1" => "Y", "2" => "S", "3" => "C", "4" => "J", "5" => "F", "6" => "P", "100" => "Default");
		$cabin_type_text = array("1" => "Economy", "2" => "Premium Economy", "3" => "Business", "4" => "Premium Business", "5" => "First", "6" => "Premium First", "100" => "Default");

		$markups = $this->ci->Flight_model->get_admin_b2c_markup($api_data->id);
		$curr_value = $this->ci->currency_converter->currency_val("IRR");
		$res_arr = array();
		foreach ($itineraries as $flight_idx => $flight_obj)
		{
			if($trip_type !== $flight_types[$flight_obj->DirectionInd])
				continue;
			$temp_res = array();
			$temp_res["search_id"] = $search_data->id;
			$temp_res["api"] = $api_data->id;
			$temp_res["api_name"] = $api_data->api_code;
			$temp_res["flight_type"] = $flight_type = $flight_types[$flight_obj->DirectionInd];
			$temp_res["fare_source_code"] = $flight_obj->FareSourceCode;
			$temp_res["airline"] = $flight_obj->ValidatingAirlineCode;
			$prices = $flight_obj->AirItineraryPricingInfo;
			$temp_res["duration"] = 0;
			$temp_res["api_tax"] = $prices->ItinTotalFare->TotalTax;
			$temp_res["api_cost"] = $prices->ItinTotalFare->TotalFare;

			$admin_cost = $prices->ItinTotalFare->TotalFare;
			// if($markups !== false)
			// {
			// 	if($markups->mt_type === "2")
			// 		$admin_cost = $prices->ItinTotalFare->TotalFare + floor(($markups->mt_amount / 100) * $prices->ItinTotalFare->TotalFare);
			// 	else
			// 		$admin_cost = $prices->ItinTotalFare->TotalFare + floor($curr_value * $markups->mt_amount);
			// }

			$temp_res["admin_cost"] = $admin_cost;
			$temp_res["total_cost"] = $admin_cost;
			$temp_res["currency"] = $prices->ItinTotalFare->Currency;
			$temp_res["fare_type"] = $fare_types[$prices->FareType];
			$price_break_down = $prices->PtcFareBreakdown;
			foreach ($price_break_down as $pbd_idx => $pbd_obj)
			{
				$temp_price_breakdown = array();
				$temp_price_breakdown["person_type"] = $person_types[$pbd_obj->PassengerTypeQuantity->PassengerType];
				$temp_price_breakdown["quantity"] = $pbd_obj->PassengerTypeQuantity->Quantity;
				$temp_price_breakdown["api_base_cost"] = $pbd_obj->PassengerFare->BaseFare;
				$temp_price_breakdown["api_total_cost"] = $pbd_obj->PassengerFare->TotalFare;

				$admin_base_cost = $pbd_obj->PassengerFare->BaseFare;
				$admin_total_cost = $pbd_obj->PassengerFare->TotalFare;
				// if($markups !== false)
				// {
				// 	if($markups->mt_type === "2")
				// 	{
				// 		$admin_base_cost = $pbd_obj->PassengerFare->BaseFare + floor(($markups->mt_amount / 100) * $pbd_obj->PassengerFare->BaseFare);
				// 		$admin_total_cost = $pbd_obj->PassengerFare->TotalFare + floor(($markups->mt_amount / 100) * $pbd_obj->PassengerFare->TotalFare);
				// 	}
				// 	else
				// 	{
				// 		$admin_base_cost = $pbd_obj->PassengerFare->BaseFare + floor($curr_value * $markups->mt_amount);
				// 		$admin_total_cost = $pbd_obj->PassengerFare->TotalFare + floor($curr_value * $markups->mt_amount);
				// 	}
				// }
				
				$temp_price_breakdown["admin_base_cost"] = $admin_base_cost;
				$temp_price_breakdown["admin_total_cost"] = $admin_total_cost;
				$temp_price_breakdown["base_cost"] = $admin_base_cost;
				$temp_price_breakdown["total_cost"] = $admin_total_cost;
				$temp_price_breakdown["currency"] = $pbd_obj->PassengerFare->Currency;
				$tax_amount = 0;
				foreach($pbd_obj->PassengerFare->Taxes as $tx_amt)
					$tax_amount += $tx_amt->Amount;
				$temp_price_breakdown["api_tax"] = $tax_amount;
				$temp_price_breakdown["total_tax"] = $admin_total_cost - $admin_base_cost;
				$temp_price_breakdown["tax_details"] = $pbd_obj->PassengerFare->Taxes;
				$temp_res["prices"][] = $temp_price_breakdown;
			}

			$route_departure = $flight_obj->OriginDestinationOptions[0]->FlightSegments;
			$route_return = isset($flight_obj->OriginDestinationOptions[1]) ? $flight_obj->OriginDestinationOptions[1]->FlightSegments : null;
			$oneway_openjaw = array();
			if(in_array($flight_type, array(ONEWAY, ROUNDTRIP)))
			{
				$stop_cnt = count($route_departure) - 1;
				$temp_res["stop_out"] = $stop_cnt > 2 ? 2 : $stop_cnt;
				$temp_res["departure_dttm"] = str_replace("T", " ", $route_departure[0]->DepartureDateTime);
				$temp_res["origin_destination"][] = array(
													"departure_dttm" => str_replace("T", " ", $route_departure[0]->DepartureDateTime),
													"arrival_dttm" => str_replace("T", " ", $route_departure[count($route_departure) - 1]->ArrivalDateTime),
													"departure_loc" => $route_departure[0]->DepartureAirportLocationCode,
													"arrival_loc" => $route_departure[count($route_departure) - 1]->ArrivalAirportLocationCode,
													"airline" => $route_departure[0]->MarketingAirlineCode,
													"flight_no" => $route_departure[0]->FlightNumber
													);
				if($flight_type === "Return")
				{
					if($temp_res["stop_out"] < (count($route_return) - 1))
						$temp_res["stop_out"] = count($route_return) - 1;
					if(strtotime($temp_res["departure_dttm"]) > strtotime($route_return[0]->DepartureDateTime))
					$temp_res["departure_dttm"] = str_replace("T", " ", $route_return[0]->DepartureDateTime);
					$temp_res["arrival_dttm"] = str_replace("T", " ", $route_return[count($route_return) - 1]->ArrivalDateTime);
					$temp_res["origin_destination"][] = array(
													"departure_dttm" => str_replace("T", " ", $route_return[0]->DepartureDateTime),
													"arrival_dttm" => str_replace("T", " ", $route_return[count($route_return) - 1]->ArrivalDateTime),
													"departure_loc" => $route_return[0]->DepartureAirportLocationCode,
													"arrival_loc" => $route_return[count($route_return) - 1]->ArrivalAirportLocationCode,
													"airline" => $route_return[0]->MarketingAirlineCode,
													"flight_no" => $route_return[0]->FlightNumber
													);
				}
			}
			$openjaw_stops = 0;
			$dpt_idx = 0;
			$idx_temp = false;
			$src_loc = "";
			$src_dttm = "";
			$src_airline = "";
			$src_flight_no = "";
			foreach ($route_departure as $dept_idx => $dept_obj)
			{
				$temp_dept = array();
				$temp_dept["departure_dttm"] = str_replace("T", " ", $dept_obj->DepartureDateTime);
				$temp_dept["arrival_dttm"] = str_replace("T", " ", $dept_obj->ArrivalDateTime);
				$temp_dept["stop"] = $dept_obj->StopQuantity;
				$temp_dept["flight_no"] = $dept_obj->FlightNumber;
				$temp_dept["airline"] = $temp_res["airline"] = $dept_obj->MarketingAirlineCode;
				$temp_dept["operating_airline"] = $dept_obj->OperatingAirline->Code;
				$temp_dept["airline_name"] = $airlines[$dept_obj->MarketingAirlineCode]->airline_name;
				$temp_dept["operating_airline_name"] = $airlines[$dept_obj->OperatingAirline->Code]->airline_name;
				$temp_res["airport"][] = $temp_dept["departure_from"] = $dept_obj->DepartureAirportLocationCode;
				$temp_res["airport"][] = $temp_dept["arrival_to"] = $dept_obj->ArrivalAirportLocationCode;
				$temp_dept["duration"] = $dept_obj->JourneyDuration;
				$temp_res["duration"] += $dept_obj->JourneyDuration;
				$temp_dept["cabin_type"] = $cabin_type_text[$dept_obj->CabinClassCode]." / ".$dept_obj->ResBookDesigCode;
				if(!empty($route_departure[$dept_idx + 1]->DepartureDateTime))
				{
					$trvl_dpt = $temp_dept["arrival_dttm"];
					$trvl_arv = str_replace("T", " ", $route_departure[$dept_idx + 1]->DepartureDateTime);
					$temp_dept["wait_tm"] = floor((strtotime($trvl_arv) - strtotime($trvl_dpt))/60);
					$temp_dept["wait_place"] = $route_departure[$dept_idx + 1]->DepartureAirportLocationCode;

				}
				$oneway_openjaw[] = $temp_dept;
				if($flight_type === MULTICITY)
				{
					if($idx_temp === false)
					{
						$src_loc = $dept_obj->DepartureAirportLocationCode;
						$src_dttm = str_replace("T", " ", $dept_obj->DepartureDateTime);
						$src_airline = $dept_obj->MarketingAirlineCode;
						$src_flight_no = $dept_obj->FlightNumber;
						$idx_temp = true;
					}
					if(!isset($temp_res["departure_dttm"]) || (strtotime($temp_res["departure_dttm"]) > strtotime($route_departure[$dept_idx]->DepartureDateTime)))
						$temp_res["departure_dttm"] = str_replace("T", " ", $route_departure[$dept_idx]->DepartureDateTime);
					if($srch_dtls->mflight_destination[$dpt_idx] === $dept_obj->ArrivalAirportLocationCode)
					{
						if(!isset($temp_res["stop_out"]) || $temp_res["stop_out"] < $openjaw_stops)
							$temp_res["stop_out"] = $openjaw_stops;
						$openjaw_stops = 0;
						$dpt_idx++;
						$temp_res["origin_destination"][] = array(
													"departure_dttm" => $src_dttm,
													"arrival_dttm" => str_replace("T", " ", $dept_obj->ArrivalDateTime),
													"departure_loc" => $src_loc,
													"arrival_loc" => $dept_obj->ArrivalAirportLocationCode,
													"airline" => $src_airline,
													"flight_no" => $src_flight_no
													);
						$idx_temp = false;
					}
					else
						$openjaw_stops++;

				}
			}
			$return_res = array();
			if(!is_null($route_return))
			foreach ($route_return as $arrv_idx => $arrv_obj)
			{
				$temp_arrv = array();
				$temp_arrv["departure_dttm"] = str_replace("T", " ", $arrv_obj->DepartureDateTime);
				$temp_arrv["arrival_dttm"] = str_replace("T", " ", $arrv_obj->ArrivalDateTime);
				$temp_arrv["stop"] = $arrv_obj->StopQuantity;
				$temp_arrv["flight_no"] = $arrv_obj->FlightNumber;
				$temp_arrv["airline"] = $arrv_obj->MarketingAirlineCode;
				$temp_arrv["operating_airline"] = $arrv_obj->OperatingAirline->Code;
				$temp_arrv["airline_name"] = $airlines[$arrv_obj->MarketingAirlineCode]->airline_name;
				$temp_arrv["operating_airline_name"] = $airlines[$arrv_obj->OperatingAirline->Code]->airline_name;
				$temp_res["airport"][] = $temp_arrv["departure_from"] = $arrv_obj->DepartureAirportLocationCode;
				$temp_res["airport"][] = $temp_arrv["arrival_to"] = $arrv_obj->ArrivalAirportLocationCode;
				$temp_arrv["duration"] = $arrv_obj->JourneyDuration;
				$temp_res["duration"] += $arrv_obj->JourneyDuration;
				$temp_arrv["cabin_type"] = $cabin_type_text[$arrv_obj->CabinClassCode]." / ".$arrv_obj->ResBookDesigCode;
				if(!empty($route_return[$arrv_idx + 1]->DepartureDateTime))
				{
					$trvl_dpt = $temp_arrv["arrival_dttm"];
					$trvl_arv = str_replace("T", " ", $route_return[$arrv_idx + 1]->DepartureDateTime);
					$temp_arrv["wait_tm"] = floor((strtotime($trvl_arv) - strtotime($trvl_dpt))/60);
					$temp_arrv["wait_place"] = $route_return[$arrv_idx + 1]->DepartureAirportLocationCode;

				}
				$return_res[] = $temp_arrv;
			}
			$temp_res["departures"] = $oneway_openjaw;
			$temp_res["arrivals"] = $return_res;
			$temp_res["departure_tm"] = date("H:i:s", strtotime($temp_res["departure_dttm"]));
			$temp_res["arrival_tm"] = isset($temp_res["arrival_dttm"]) ? date("H:i:s", strtotime($temp_res["arrival_dttm"])) : "00:00";
			$temp = array_unique($temp_res["airport"]);
			$temp_res["airport"] = str_replace(",,", ",", ",".implode(",", $temp).",");
			$res_arr[] = $temp_res;
		}
		return $res_arr;
	}

	public function modify_search_cache($api_data, $search_data, $fsc, $new_result = null)
	{
		$srch_dtls = json_decode($search_data->input_json);
		$hash = $search_data->hash;
		$search_file = FCPATH."app/cache/partocrs/search/".$api_data->id.$hash.".json";
		$response = false;
		if(file_exists($search_file))
			if(filemtime($search_file) > strtotime("-15 minutes", time()))
				$response = file_get_contents($search_file);
		$res_arr = array();
		if($response !== false)
		{
			$response = json_decode($response);
			$status = $response->Success;
			if($status === true)
			{
				foreach ($response->PricedItineraries as $flight_idx => $flight_obj)
				{
					if($fsc !== $flight_obj->FareSourceCode)
						$res_arr[$flight_idx] = $flight_obj;
					elseif(!is_null($new_result))
						$res_arr[$flight_idx] = $new_result;
				}
				$response->PricedItineraries = $res_arr;
				file_put_contents($search_file, json_encode($response));
			}
		}
	}

}