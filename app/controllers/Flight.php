<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flight extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		unset_page_cache();
		$this->data["page_title"] = "Flights";
		$this->load->library("partocrs");
		$this->load->library("charter_api");
		$this->load->model("Flight_model");
		$this->load->model("Payment_model");
	}

	public function index()
	{
		redirect("", "refresh");
	}

	public function lists($token = "")
	{//echo '<pre>',print_r($this->input->post());exit; 
		if(is_array($this->input->post()) && count($this->input->post()) > 0 && ($this->input->post('flight_departure') != ""  || $this->input->post('mflight_departure')))
		{
			$search_data = $this->input->post();
			$search_data["flight_departure"] = date("Y-m-d", strtotime($search_data["flight_departure"]));
			if(isset($search_data["flight_arrival"]) && !empty($search_data["flight_arrival"]))
				$search_data["flight_arrival"] = date("Y-m-d", strtotime($search_data["flight_arrival"]));
			$hash = preg_replace("`[{}:\[\]\",]`", "", json_encode(array_filter(array_values($search_data))));
			$search_data = json_encode($search_data);
			$s_data = json_decode($search_data);
			if($s_data->flight_type === OpenJaw)
				$loc_list = array_merge((array)$s_data->mflight_origin, (array)$s_data->mflight_destination);
			else
				$loc_list = array($s_data->flight_origin, $s_data->flight_destination);
			$temp_res = $this->Flight_model->get_airports($loc_list, $this->data["default_language"]);
			$countries = array();
			if($temp_res !== false)
				foreach ($temp_res as $obj)
					$countries[] = $obj->country_code;
			$i_or_d = count(array_unique($countries)) === 1 && $countries[0] === IRAN ? IORDD : IORDI;
			if($i_or_d === IORDD && $s_data->flight_type === OpenJaw)
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
				$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Multicity is not available for domestic flights");
				redirect("", "refresh");
			}
			$ip = $_SERVER["REMOTE_ADDR"];
			$service = $i_or_d === IORDD ? DOMESTIC_FLIGHTS : INTERNATIONAL_FLIGHTS;
			$ins_data = array("ip_address" => $ip, "service" => $service, "input_json" => $search_data, "hash" => $hash, "i_or_d" => $i_or_d, "created" => date("Y-m-d H:i:s"));
			$ref_id = $this->Flight_model->new_flight_search($ins_data);
			$hash = base64_encode($this->encrypt->encode($ref_id));
			$this->Flight_model->clear_old_flight_search($service);
			redirect("flight/lists/".$hash, "refresh");
		}
		elseif($token !== "")
		{
			$data["hash"] = $token;
			$search_id = $this->encrypt->decode(base64_decode($token));
			$search_data = $this->Flight_model->current_flight_search($search_id);
			if(empty($search_data))
			{
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
				$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Search details no longer exists. try again.");
				redirect("", "refresh");
			}
			$s_data = $data["search_data"] = json_decode($search_data->input_json);
			$data["i_or_d"] = $search_data->i_or_d;
			$loc_list = array();
			if($s_data->flight_type === MULTICITY)
				$loc_list = array_merge((array)$s_data->mflight_origin, (array)$s_data->mflight_destination);
			else
				$loc_list = array($s_data->flight_origin, $s_data->flight_destination);
				
			$temp_res = $this->Flight_model->get_airports($loc_list, $this->data["default_language"]);
			$locations = array();
			if($temp_res !== false)
				foreach ($temp_res as $obj)
					$locations[$obj->airport_code] = $obj;
			$data["airports"] = $locations;
			if($search_data->i_or_d === IORDI)
				$this->load->view("flight/result",$data);
			else
				$this->load->view("domestic/result",$data);
		}
		else
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "you have performed invalid operation.");
			redirect("", "refresh");
		}
	}

	public function search($token)
	{
		set_time_limit(600);
		$search_id = $this->encrypt->decode(base64_decode($token));
		$data_filter = array("total_count" => "0", "min_price" => "0", "expired" => "false", "airlines" => array(), "airports" => array(),"max_price" => "0", "stops" => array(), "flights" => array(), "rflights" => array());
		$i_or_d = IORDI;
		$search_data = false;
		if(is_numeric($search_id) && $search_id > 0)
			$search_data = $this->Flight_model->current_flight_search($search_id);
		if($search_data !== false)
		{
			$i_or_d = $search_data->i_or_d;
			$is_retreived = $this->Flight_model->is_data_retreived($search_id);
			if($is_retreived == false)
			{
				if($i_or_d === IORDD)
					$current_apis = $this->Flight_model->get_all_flight_apis(DOMESTIC_FLIGHTS);
				else
					$current_apis = $this->Flight_model->get_all_flight_apis(INTERNATIONAL_FLIGHTS);
				// retrive from multiple api
				if($current_apis !== false)
					foreach ($current_apis as $apis)
				{
					$flight_list =array();
					$flights =array();
					for($i = 0; $i < count($current_apis); $i++)
					{
						ob_start();
						$this->api_request($apis->id, $token);
						$results = json_decode(ob_get_contents(), true);
						if(!(is_null($results) || empty($results)))
							if($results["status"] !== false)
								$flight_list[] = $results["result"];
						ob_end_clean();
					}

					// $date = date("Y-m-d H:i:s");
					// $newtimestamp = strtotime($date.' + 15 minute');
					// $valid_status =  date('Y-m-d H:i:s', $newtimestamp);

					// insert into temprorary search table
					foreach ($flight_list as $key => $api_res)
					{
						foreach ($api_res as $res_idx => $fields)
						{	
							$v_clone = array();
							$v_clone["search_id"] = $fields["search_id"];
							$v_clone["api"] = $fields["api"];
							$v_clone["api_name"] = $fields["api_name"];
							$v_clone["flight_type"] = $fields["flight_type"];
							$v_clone["fare_source_code"] = $fields["fare_source_code"];
							$v_clone["airline"] = $fields["airline"];
							$v_clone["airport"] = $fields["airport"];
							$v_clone["api_tax"] = $fields["api_tax"];
							$v_clone["api_cost"] = $fields["api_cost"];
							$v_clone["admin_cost"] = $fields["admin_cost"];
							$v_clone["total_cost"] = $fields["total_cost"];
							$v_clone["currency"] = $fields["currency"];
							$v_clone["fare_type"] = $fields["fare_type"];
							$v_clone["prices"] = json_encode($fields["prices"]);
							$v_clone["stops"] = $fields["stops"];
							$v_clone["results"] = $fields["results"];
							$v_clone["duration"] = $fields["duration"];
							$v_clone["arrival_dttm"] = isset($fields["arrival_dttm"]) ? $fields["arrival_dttm"] : "";
							$v_clone["departure_dttm"] = $fields["departure_dttm"];
							$v_clone["arrival_tm"] = $fields["arrival_tm"];
							$v_clone["departure_tm"] = $fields["departure_tm"];
							$v_clone["origin_destination"] = json_encode($fields["origin_destination"]);
							$v_clone["departures"] = json_encode($fields["departures"]);
							$v_clone["arrivals"] = json_encode($fields["arrivals"]);
							$v_clone["valid_status"] = date("Y-m-d H:i:s");
							$flights[] = $v_clone;
						}
					}
					$this->Flight_model->update_current_search($search_id, array("created" => date("Y-m-d H:i:d"), "results" => "1"));
					if(!empty($flights))
						$this->Flight_model->new_flight_list($flights);

					// end of api data retreive
				}
			}
			elseif($is_retreived === "expired")
				$data_filter["expired"] = "true";
		
			$filter = $_GET;
			$all_result = $this->Flight_model->get_flight_result($search_id, $filter, $i_or_d);
			// echo '<pre>',print_r($all_result);exit;
			if($all_result !== false && $all_result->total > 0)
			{ 
				
				$is_twoway = $i_or_d === IORDD && $all_result->is_twoway === "true" ? "true" : "false";
				$data_filter["total_count"] = $all_result->total;
				$data_filter["min_price"] = $all_result->min;
				$data_filter["max_price"] = $all_result->max;
				$data_filter["is_twoway"] = $is_twoway;
				$airports = array_unique(array_filter(explode(",", $all_result->airports)));
				$data_filter["airports"] = $this->Flight_model->get_airports($airports, $this->data["default_language"]);
				$data_filter["airlines"] = $this->Flight_model->get_all_airlines(array(), $this->data["default_language"]);
				$data_filter["lang"] = $this->data["default_language"];
				$data_filter["currency"] = $this->data["default_currency"];
				if($all_result->currency === IRR)
					$data_filter["currency_val"] = $this->currency_converter->convert($this->data["default_currency"], IRR);
				elseif($all_result->currency === USD)
					$data_filter["currency_val"] = $this->currency_converter->convert(USD, $this->data["default_currency"]);
				else
					$data_filter["currency_val"] = $this->currency_converter->convert($this->data["default_currency"], DEFAULT_CURRENCY);
				$data_filter["price_matrix"] = !empty($all_result->airlines) ? $this->Flight_model->get_airlines($search_id, array_filter(explode(",", $all_result->airlines)), $this->data["default_language"]) : array();
				$astops = array("0" => $this->lang->line("direct"), "1" => $this->lang->line("one_stop"), "2" => $this->lang->line("multi_stop"));
				$tstops = explode(",", $all_result->stops);
				$stops = array();
				foreach ($tstops as $value)
					$stops[] = array("val" => $value, "text" => $astops[$value]);
				$data_filter["stops"] = $stops;
				$data_filter["flights"] = $this->Flight_model->available_iflights($search_id, $filter, $this->data["default_language"], $is_twoway);
				// echo $this->db->last_query();die;
				if($is_twoway === "true" && $data_filter["flights"] !== false)
				{
					$rflights = $this->Flight_model->available_rflights($search_id, $filter, $this->data["default_language"], $is_twoway);
//echo "<pre>";print_r($rflights);exit();
					if($rflights !== false)
						$data_filter["rflights"] = $rflights;
					else
					{
						$data_filter["flights"] = false;
						$data_filter["total_count"] = 0;
					}
				}
			}
		}
		echo json_encode($data_filter);
	}

	public function air_rules($token, $fr_id)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		if($search_data === false)
		{echo json_encode(array("status" => "false", "fare_rules" => $this->lang->line("invalid_fsc")));exit;}
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);
		$api = $flight_row->api;
		$fsc = $flight_row->fare_source_code;
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$result = $this->$api_name->air_rules($api_data, $search_data, $fsc);
		$is_valid = ($result !== false && $result["status"] !== false) ? true : false;
		$content_html = "";
		if($is_valid !== false)
		{
			if($result["type"] === TYPE_JSON)
			{
				$content = json_decode($result["result"]);
				foreach ($content as $main_idx => $main_obj)
				{
					if(!empty($main_obj->Airline))
						$content_html .= "<div class='fare_rule".$main_idx."'><div>\n<label class='pull-left'>".$this->lang->line("airline_colan")." ".$main_obj->Airline."</label>\n<label class='pull-right'>".$this->lang->line("airline_colan")." ".(stripos($main_obj->CityPair, "-") !== false ? $main_obj->CityPair : implode("-", str_split($main_obj->CityPair, 3)))."</label><div class='clearfix'></div>\n</div>\n";
					$content_html .= "<div class='fare_rule_details".$main_idx."'>\n";
					foreach ($main_obj->RuleDetails as $rule_idx => $rule)
					{
						if(!empty($rule->Rules))
						{
							$content_html .= "<div class='fare_desc_div'>\n";
							$content_html .= "<p class='fare_desc_header'>".trim($rule->Category)."</p>\n";
							$content_html .= "<div class='fare_desc_detail' style='display:none;'>";
							$content_html .= "<pre>".$rule->Rules."</pre>";
							$content_html .= "</div>\n";
							$content_html .= "</div>";
						}
					}
					$content_html .= "</div>\n</div><br/>\n";
				}
				echo json_encode(array("status" => "true", "fare_rules" => $content_html));
			}
			else
				echo json_encode(array("status" => "true", "fare_rules" => $result["result"]));
		}
		else
		{
			$this->Flight_model->remove_invalid($fr_id);
			if($result !== false)
				echo json_encode($result);
			else
				echo json_encode(array("status" => "false", "fare_rules" => $this->lang->line("invalid_fsc")));
		}

	}

	public function baggage_rules($token, $fr_id)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		if($search_data === false)
		{echo json_encode(array("status" => "false", "baggage_rules" => "",  "redirect" => "true"));exit;}
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);
		$api = $flight_row->api;
		$fsc = $flight_row->fare_source_code;
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$result = $this->$api_name->air_baggages($api_data, $search_data, $fsc);
		$is_valid = ($result !== false && $result["status"] !== false) ? true : false;
		$content_html = "";
		if($is_valid !== false)
		{
			if($result["type"] === TYPE_JSON)
			{
				$content = json_decode($result["result"]);
				if(count($content) > 0)
				{
					$content_html .= "<table class='table table-bordered baggage_rules'>\n<tr><th>".$this->lang->line("departure")."</th><th>".$this->lang->line("arrival")."</th><th>".$this->lang->line("flight_no")."</th><th>".$this->lang->line("baggage_weight")."</th></tr>";
					foreach ($content as $main_idx => $main_obj)
					{
						$content_html .= "<tr><td>".$main_obj->Departure."</td><td>".$main_obj->Arrival."</td><td>".$main_obj->FlightNo."</td><td>".$main_obj->Baggage."</td></tr>";
					}
					$content_html .= "</table>\n";
				}
				else
					$content_html = "<div><label>".$this->lang->line("no_baggage_details")."</label><br/>
											<label>".$this->lang->line("no_baggage_alternate")."</label></div>";
				echo json_encode(array("status" => "true", "baggage_rules" => $content_html));
			}
			else
				echo json_encode(array("status" => "true", "baggage_rules" => $result["result"]));
		}
		else
		{
			$this->Flight_model->remove_invalid($fr_id);
			if($result !== false)
				echo json_encode($result);
			else
				echo json_encode(array("status" => "false", "baggage_rules" => $this->lang->line("invalid_fsc")));
		}

	}

	public function revalidate($token, $fr_id, $cached = "0")
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		if($search_data === false)
		{echo json_encode(array("status" => "false", "result" => "",  "redirect" => "true"));exit;}
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);
		$api = $flight_row->api;
		$fsc = $flight_row->fare_source_code;
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$revalidate = $this->$api_name->air_revalidate($api_data, $search_data, $flight_row, $cached);
		if($revalidate !== false)
		{
			if($revalidate["redirect"] === "true")
			{
				if($revalidate["status"] === "true")
				{
					if($cached === "0")
					{
						$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $this->lang->line("itinerary_changed"));
						$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
						$this->session->set_flashdata(SESSION_PREPEND."notification_title", $this->lang->line("flight_details_updated"));
						echo json_encode(array("status" => "true", "result" => $this->lang->line("flight_details_updated"), "redirect" => "true"));exit;
					}
					else
					{
						$flight_row = $this->Flight_model->get_selected_flight($fr_id);
						$services_opt = json_decode($flight_row->extra_service_json);
						$baggage_select = null;
						if(count($services_opt) > 0)
						{
							$baggage_select = "<option data-amount='0' value=''>".$this->lang->line("no_baggage")."</option>";
							for ($x = 0; $x < count($services_opt); $x++)
							{
								if($services_opt[$x]->type === BAGGAGE_TYPE)
								$baggage_select .= "<option value='".$services_opt[$x]->id."' data-amount='".$services_opt[$x]->amount."'>".$services_opt[$x]->desc." => ".$services_opt[$x]->currency." ".number_format($services_opt[$x]->amount, 0, "", ",")."</option>";
							}
						}
						$updated_result = array(
												"total_base_cost" => $flight_row->total_cost,
												"baggage_select" => $baggage_select
												);
						echo json_encode(array("status" => "true", "result" => '<div class="alert alert-block alert-info alert-dismissable">
							<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
							<h4 class="alert-heading">'.$this->lang->line("flight_details_updated").'</h4>
								'.$this->lang->line("itinerary_changed").'
						</div>', "redirect" => "false", "updated" => "true", "updates" => $updated_result));exit;
					}
				}
				else
				{
					$this->Flight_model->remove_invalid($fr_id);
					if($cached === "0")
					{
						$this->session->set_flashdata(SESSION_PREPEND."notification_msg", $this->lang->line("itinerary_not_exist"));
						$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
						$this->session->set_flashdata(SESSION_PREPEND."notification_title", $this->lang->line("flight_not_available"));
						echo json_encode(array("status" => "false", "result" => $this->lang->line("flight_not_available"), "redirect" => "true"));exit;
					}
					else
					{
						echo json_encode(array("status" => "false", "result" => '<div class="alert alert-block alert-info alert-dismissable">
							<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
							<h4 class="alert-heading">'.$this->lang->line("flight_not_available").'</h4>
								'.$this->lang->line("itinerary_not_exist").'
						</div>', "redirect" => "true"));exit;
					}
				}
			}
			else
				echo json_encode(array("status" => "true", "result" => $this->lang->line("no_price_changes"), "redirect" => "false"));
		}
		else
		{
			$this->Flight_model->remove_invalid($fr_id);
			echo json_encode(array("status" => "false", "result" => $this->lang->line("invalid_fsc"), "redirect" => "true"));
		}
	}

	public function dom_revalidate($token, $frd_id, $frr_id, $discount = 0.00)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		if($search_data === false)
		{
			echo json_encode(array("status" => "false", "result" => "",  "redirect" => "true"));
			exit;
		}
		$departure_row = $this->Flight_model->get_selected_flight($frd_id);
		$arrival_row = $this->Flight_model->get_selected_flight($frr_id);
		if($departure_row === false || $arrival_row === false)
		{
			echo json_encode(array("status" => "false", "result" => "",  "redirect" => "true"));
			exit;
		}
		$new_fsc = base64_encode($frd_id.":::".$frr_id);
		$is_fsc_avail = $this->Flight_model->get_selected_flight_fsc($new_fsc);
		if($is_fsc_avail === false)
		{	
			$input_json = json_decode($search_data->input_json);

			$pax_count  = $input_json->adult + $input_json->child;
			$discount = $discount*$pax_count;
			
			$flights = array();
			$flights["search_id"] = $departure_row->search_id;
			$flights["api"] = $departure_row->api;
			$flights["api_name"] = $departure_row->api_name;
			$flights["flight_type"] = $departure_row->flight_type;
			$flights["fare_source_code"] = $new_fsc;
			$flights["airline"] = $departure_row->airline;
			$flights["airport"] = $departure_row->airport;
			$flights["api_tax"] = ($departure_row->api_tax * 1) + ($arrival_row->api_tax * 1);
			$flights["api_cost"] = ($departure_row->api_cost * 1) + ($arrival_row->api_cost * 1);
			$flights["admin_cost"] = ($departure_row->admin_cost * 1) + ($arrival_row->admin_cost * 1);
			$flights["total_cost"] = (($departure_row->total_cost * 1) + ($arrival_row->total_cost * 1)) - $discount;
			$flights["currency"] = $departure_row->currency;
			$flights["fare_type"] = $departure_row->api;
			$flights["prices"] = json_encode(array_merge(json_decode($departure_row->prices),json_decode($arrival_row->prices)));
			$flights["stops"] = $departure_row->stops;
			$flights["results"] = "2";
			$flights["duration"] = $departure_row->duration;
			$flights["arrival_dttm"] = $arrival_row->departure_dttm;
			$flights["departure_dttm"] = $departure_row->departure_dttm;
			$flights["arrival_tm"] = $arrival_row->departure_tm;
			$flights["departure_tm"] = $departure_row->departure_tm;
			$flights["origin_destination"] = json_encode(array_merge(json_decode($departure_row->origin_destination),json_decode($arrival_row->origin_destination)));
			$flights["departures"] = $departure_row->departures;
			$flights["arrivals"] = $arrival_row->departures;
			$flights["discount"] = $discount;
			$new_idx = $this->Flight_model->append_to_flight_list($flights);
		}
		else
			$new_idx = $is_fsc_avail->id;
		echo json_encode(array("status" => "true", "result" => $this->lang->line("no_price_changes"), "redirect" => "false", "new_idx" => $new_idx));
	}

	public function prebook($token, $fr_id)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);
		if($search_data === false || $flight_row === false)
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}
		$search_file = FCPATH."app/cache/partocrs/book/".$flight_row->api.$search_id.$flight_row->fare_source_code.".json";
		if(file_exists($search_file))
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Trying to rebook same flight.");
			redirect("", "refresh");
		}
		$data["hash"] = $token;
		$data["fr_id"] = $fr_id;
		$api = $flight_row->api;
		$fsc = $flight_row->fare_source_code;
		$s_data = $data["search_data"] = json_decode($search_data->input_json);
		$data["flight_details"] = $flight_row;
		if(isset($this->data["user_id"]) && !empty($this->data["user_id"]))
			$data["user_details"] = $this->B2c_model->is_valid_user("", $this->data["user_id"]);
			$data["companions"] = $this->B2c_model->get_companions($this->data["user_id"]);
		$loc_list = array();
		if(empty($data["search_data"]))
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}
		$temp_res = $this->Flight_model->get_airports(array(), $this->data["default_language"]);
		$locations = array();
		foreach ($temp_res as $obj)
			$locations[$obj->airport_code] = $obj;
		$temp_res = $this->Flight_model->get_cip_airports();
		$cip_locations = array();
		foreach ($temp_res as $obj)
			$cip_locations[$obj->airport_code] = $obj;
		$data["airports"] = $locations;
		$data["cip_airports"] = $cip_locations;
		$data["prebook_type"] = "flights";
		$data["iord"] = $i_or_d = $search_data->i_or_d;
		$data["currency"] = $this->data["default_currency"];
		if($flight_row->currency === IRR)
			$data["currency_val"] = $this->currency_converter->convert($this->data["default_currency"], IRR);
		elseif($flight_row->currency === USD)
			$data["currency_val"] = $this->currency_converter->convert(USD, $this->data["default_currency"]);
		else
			$data["currency_val"] = $this->currency_converter->convert($this->data["default_currency"], DEFAULT_CURRENCY);
		$total_travellers = 1;
		$data["travel_insurance_amount"] = "3000";
		if($s_data->flight_type === MULTICITY)
			$total_travellers = ($s_data->madult * 1) + ($s_data->mchild * 1) + ($s_data->minfant * 1);
		else
			$total_travellers = ($s_data->adult * 1) + ($s_data->child * 1) + ($s_data->infant * 1);
		$data["total_travellers"] = $total_travellers;
		$data["contact_address"] = $this->general->default_contact_address($this->data["default_language"]);
		
		if($i_or_d === IORDI){
			
			$this->load->view("flight/prebook",$data);
		}else{
			$this->load->view("domestic/prebook",$data);
		}
	}

	public function reserve($token, $fr_id, $bk_id)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);
		$book_details = $this->Flight_model->get_booking($bk_id);
		if($search_data === false || $flight_row === false || $book_details === false)
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}
		$post_data = json_decode($book_details->traveller_json);
		$api = $flight_row->api;
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$reserve = $this->$api_name->air_book($api_data, $search_data, $flight_row, $post_data);
		if($reserve !== false)
		{
			if($reserve["status"] !== false)
			{
				$api_result = json_decode($reserve["result"]);
				$book_data = array(
									"ticket_id" => $api_result->ticket_id,
									"book_status" => "1",
									"api_status" => "11",
									"deadline" => $api_result->deadline
									);
				$cfrm_status = $this->Flight_model->set_booking($bk_id, $book_data);
				if($cfrm_status !== false)
					redirect("flight/voucher/".base64_encode($this->encrypt->encode($bk_id)), "refresh");
			}
		}
		redirect("", "refresh");
	}

	public function confirm($token)
	{
		// echo base64_encode($this->encrypt->encode($token));die;
		$book_id = $this->encrypt->decode(base64_decode($token));
		$book_details = $this->Flight_model->get_booking($book_id);
		if($book_details === false || empty($book_details->ticket_id))
			redirect("", "refresh");
		$api = $book_details->api;
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$ticket_id = $book_details->ticket_id;
		$confirm = $this->$api_name->air_confirm($api_data, $ticket_id);
		if($confirm !== false)
		{
			if($reserve["status"] !== false)
			{
				$book_data = array(
									"api_status" => "11"
									);
				$cfrm_status = $this->Flight_model->set_booking($book_id, $book_data);
				if($cfrm_status !== false)
					redirect("flight/voucher/".base64_encode($this->encrypt->encode($book_id)), "refresh");
			}
		}
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}
	}

	public function book($token, $fr_id, $bk_id)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);
		$book_details = $this->Flight_model->get_booking($bk_id);
		if($search_data === false || $flight_row === false || $book_details === false)
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}
		$post_data = json_decode($book_details->traveller_json);
		$api = $book_details->api;
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$confirm = $this->$api_name->air_book($api_data, $search_data, $flight_row, $post_data, false);
		if($confirm !== false)
		{
			if($confirm["status"] !== false)
			{
				$api_result = json_decode($confirm["result"]);
				$book_data = array(
									"ticket_id" => $api_result->ticket_id,
									"ticketed_date" => date("Y-m-d H:i:s"),
									"book_status" => "1",
									"api_status" => "11"
									);
				$cfrm_status = $this->Flight_model->set_booking($bk_id, $book_data);
				if($cfrm_status !== false)
					redirect("flight/voucher/".base64_encode($this->encrypt->encode($bk_id)), "refresh");
			}
		}
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}
	}

	public function voucher($bk_id)
	{
		$bk_id = $this->encrypt->decode(base64_decode($bk_id)); 
		$book_details = $this->Flight_model->get_booking($bk_id);
		$s_data = json_decode($book_details->input_json);
		if($book_details === false || is_null($book_details->ticket_id))
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid booking details. try again.");
			redirect("", "refresh");
		}
		$data["book_details"] = $book_details;
		
		$temp_res = $this->Flight_model->get_airports(array(), $this->data["default_language"]);

		$payDetails = $this->Payment_model->GetPaymentDetails($bk_id);	

		$locations = array();
		foreach ($temp_res as $obj)
			$locations[$obj->airport_code] = $obj;
		$data["airports"] = $locations;
		$data["payDetails"] = $payDetails;

		if($book_details->i_or_d === IORDI)
			$this->load->view("flight/voucher", $data);
		else
			$this->load->view("domestic/voucher", $data);
	}

	public function session_expired($token)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$this->Flight_model->clear_flight_list($search_id);
		$this->load->view("index");
	}

	public function api_request($api, $token)
	{
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		$api_data = $this->Flight_model->get_flight_api($api);
		$api_name = $api_data->api_code;
		$result = $this->$api_name->low_fare_air_search($api_data, $search_data);
		echo $result;
	}

	public function autocomplete()
	{
		$key = $this->input->get("term");
		$cities = $this->Flight_model->get_cities($key, $this->data["default_language"]);
		echo json_encode($cities);
	}

	public function dom_autocomplete()
	{
		$cities_list = $this->Flight_model->get_dom_cities($this->data["default_language"]);
		$response = "<option selected value=''>".$this->lang->line("enter_city_or_airport")."</option>";
		if($cities_list !== false)
			foreach($cities_list as $cities)
					$response .= "<option value='".$cities->id."'>".$cities->value."</option>";
		echo json_encode(array("result" => $response));exit;
	}

	public function demo_book($token, $fr_id, $bk_id)
	{
		redirect("flight/demo_voucher/".base64_encode($this->encrypt->encode($bk_id)), "refresh");
	}

	public function demo_voucher($bk_id)
	{
		$bk_id = $this->encrypt->decode(base64_decode($bk_id));
		$book_details = $this->Flight_model->get_booking($bk_id);
		$s_data = json_decode($book_details->input_json);
		$data["book_details"] = $book_details;
		$temp_res = $this->Flight_model->get_airports(array(), $this->data["default_language"]);
		$locations = array();
		foreach ($temp_res as $obj)
			$locations[$obj->airport_code] = $obj;
		$data["airports"] = $locations;
		if($book_details->i_or_d === IORDI)
			$this->load->view("flight/voucher", $data);
		else
			$this->load->view("domestic/voucher", $data);
	}

	public function promocode()
	{
		$response = array("status" => "false", "msg" => "Invalid Promocode.", "promocode" => "", "discount" => "0");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("code", "Promocode", "trim|required");
			$this->form_validation->set_rules("amount", "Amount", "trim|required");
			if($this->form_validation->run() !== false)
			{
				$promocode = $this->input->post("code");
				$amount = $this->input->post("amount");
				$promo = $this->Common_model->get_promo_by_code($promocode);
				if($promo !== false)
				{
					$c_dt = new DateTime();
					$iran_time = new DateTimeZone("UTC");
					$c_dt->setTimezone($iran_time);
					$c_dttm = strtotime($c_dt->format("Y-m-d H:i:s"));
					if(strtotime($promo->valid_to) > $c_dttm)
					{
						$currency_val = $this->currency_converter->convert("USD", "IRR");
						if((double)($promo->condition * $currency_val) < (double)$amount)
						{
							$response["promocode"] = $promocode;
							$response["status"] = "true";
							$response["msg"] = "Promocode Applied successfully.";
							if($promo->type === "1")
								$response["discount"] = (($promo->discount / 100) * $amount);
							else
								$response["discount"] = $promo->discount * $currency_val;
						}
						else
							$response["msg"] = "Insufficient purchase amount to apply this promocode.";
					}
					else
						$response["msg"] = "Promocode is expired";
				}
			}
		}
		echo json_encode($response);exit;
	}

	public function get_discount(){

		$data = $this->Flight_model->get_dicount_price();
		echo $data->amount;exit;
	}
}
