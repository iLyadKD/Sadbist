<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class Ajax extends CI_Controller {

	/* load default models, libraries */
	public function __construct()
	{
		parent::__construct();
	}

	/*load dashboard view*/
	public function index()
	{
		echo json_encode(array("result" => false));
	}

	public function get_currencies()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$currencies = $this->general->get_active_currencies($id, $search, $page);
		echo json_encode($currencies);exit;	
	}

	public function get_unset_currency_countries()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$countries = $this->general->get_unset_currency_countries($id, $search, $page);
		echo json_encode($countries);exit;
	}

	public function get_countries()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$countries = $this->general->get_active_countries($id, $search, $page);
		echo json_encode($countries);exit;
	}

	public function get_users_by_type()
	{
		$user_type = $this->input->get("user_type");
		$admin_id = $this->data["admin_id"];
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$countries = $this->general->get_users_by_type($user_type, $admin_id, $id, $search, $page);
		echo json_encode($countries);exit;
	}

	public function get_support_subjects()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$subjects = $this->general->get_support_subjects($id, $search, $page);
		echo json_encode($subjects);exit;
	}

	public function get_states()
	{
		$country = $this->input->get("country");
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$regions = $this->general->get_regions($country, $id, $search, $page);
		echo json_encode($regions);exit;
	}

	public function get_unassigned_regions()
	{
		$country = $this->input->get("country");
		$region = $this->input->get("default_region");
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$regions = $this->general->get_unassigned_regions($country, $region, $id, $search, $page);
		echo json_encode($regions);exit;
	}

	public function get_cities()
	{	
		
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$region_codes = array("", "XXX");
		if(empty($id))
		{
			$region_codes = $this->input->get("region");
			$region_codes = explode(":::", urldecode($region_codes));
		}
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$cities = $this->general->get_cities($region_codes[0], $region_codes[1], $id, $search, $page);
		echo json_encode($cities);exit;
	}

	public function active_promocodes_form_html()
	{
		$promocodes = $this->general->get_active_promocodes();
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		$result = "";
		$response["title"] = "Subscriber Management - Send Promocode";
		if($promocodes !== false)
		{
			$result = "<div class='form-group'><label class='control-label'>Choose Any One Promo</label><br>";
			$i = 1;
			foreach ($promocodes as $promocode)
			{
				$result .= "<div class='radio'>
								<label for='promo".$i."'><input type='radio' data-rule-required='true' data-msg-required='' checked='checked' id='promo".$i."' name='promo_code' value='".base64_encode($this->encrypt->encode($promocode->id))."'>
								".$promocode->code." - <em> ".$promocode->discount." (".$promocode->name.") discount on amount ".$promocode->condition." ".DEFAULT_CURRENCY.", valid upto ".date('M j,Y',strtotime($promocode->valid_to))."</em>
								</label>
							</div>";
				$i++;
			}
			$result .= "</div>";
			$response["status"] = "true";
			$response["msg"] = $result;
		}
		if($promocodes === false)
		{
			$response["msg_status"] = "info";
			$response["msg"] = "Valid promocodes are not available to send.";
		}
		echo json_encode($response);exit;	
	}

	public function set_menu_status()
	{
		$status = intval($this->input->get("status"));
		$result = $this->general->set_menu_status($status);
		echo $result === true ? "true" : "false";
	}

	public function get_payment_gateways()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$payment_gateways = $this->general->get_active_payment_gateways($id, $search, $page);
		echo json_encode($payment_gateways);exit;
	}

	public function get_apis()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$apis = $this->general->get_active_apis($id, $search, $page);
		echo json_encode($apis);exit;
	}

	public function get_services()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$services = $this->general->get_services($id, $search, $page);
		echo json_encode($services);exit;
	}

	public function get_taxes()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$payment_gateways = $this->general->get_active_taxes($id, $search, $page);
		echo json_encode($payment_gateways);exit;
	}

	public function get_markup_types()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$markup_types = $this->general->get_markup_types($id, $search, $page);
		echo json_encode($markup_types);exit;
	}

	public function get_airlines()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$airlines = $this->general->get_airlines($id, $search, $page);
		echo json_encode($airlines);exit;
	}

	public function get_airports()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$airports = $this->general->get_airports($id, $search, $page);
		echo json_encode($airports);exit;
	}

	public function get_b2b_users()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$b2b_users = $this->general->get_active_b2b_users($id, $search, $page);
		echo json_encode($b2b_users);exit;
	}

	// b2b user account types
	public function b2b_acc_types()
	{
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$b2b_acc_types = $this->general->get_account_types($id, $search, $page);
		echo json_encode($b2b_acc_types);exit;
	}
	
	public function get_all_cities()
	{	
		$id = is_null($this->input->get("id")) ? "" : $this->input->get("id");
		$search = is_null($this->input->get("search")) ? "" : $this->input->get("search");
		$page = is_null($this->input->get("page")) ? "1" : $this->input->get("page");
		$cities = $this->general->get_all_cities($id,$search, $page);
		echo json_encode($cities);exit;
	}

}