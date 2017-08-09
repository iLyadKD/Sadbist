<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Payment extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		unset_page_cache();
		$this->data["page_title"] = "Payment";
		$this->load->model("Flight_model");
		$this->load->model("Payment_model");

		
	}

	public function index()
	{

	}

	public function book($token, $fr_id)
	{
		// echo '<pre>',print_r($this->input->post());exit; 
		// get all results
		$search_id = $this->encrypt->decode(base64_decode($token));
		$search_data = $this->Flight_model->current_flight_search($search_id);
		$flight_row = $this->Flight_model->get_selected_flight($fr_id);

		// on empty redirect
		if($search_data === false || $flight_row === false)
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Search");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "Invalid search or flight details. try again.");
			redirect("", "refresh");
		}

		//cip airport lists
		$temp_res = $this->Flight_model->get_cip_airports();
		$cip_airports = array();
		foreach ($temp_res as $obj)
			$cip_airports[$obj->airport_code] = $obj;

		//search data
		$post_data_json = json_encode($this->input->post(),JSON_UNESCAPED_UNICODE);
		$post_data = json_decode($post_data_json);
		$s_data = json_decode($search_data->input_json);
		$origin_destinations = json_decode($flight_row->origin_destination);
		$temp_baggage_service = json_decode($flight_row->extra_service_json);

		// baggage options
		$baggage_service = array();
		$baggage_cost = 0;
		if(count($temp_baggage_service) > 0)
		{
			for ($x = 0; $x < count($temp_baggage_service); $x++)
				if($temp_baggage_service[$x]->type === BAGGAGE_TYPE)
					$baggage_service[$temp_baggage_service[$x]->id] = $temp_baggage_service[$x];
			$adult_baggage = $post_data->adult_baggage;
			$child_baggage = isset($post_data->child_baggage) ? $post_data->child_baggage : array();
			$infant_baggage = isset($post_data->infant_baggage) ? $post_data->infant_baggage : array();
			for ($i = 0; $i < count($adult_baggage); $i++)
				if($adult_baggage[$i] !== "")
					$baggage_cost += $baggage_service[$adult_baggage[$i]]->amount * 1;
			for ($i = 0; $i < count($child_baggage); $i++)
				if($child_baggage[$i] !== "")
					$baggage_cost += $baggage_service[$child_baggage[$i]]->amount * 1;
			for ($i = 0; $i < count($infant_baggage); $i++)
				if($infant_baggage[$i] !== "")
					$baggage_cost += $baggage_service[$infant_baggage[$i]]->amount * 1;
		}
		$baggage_cost = number_format($baggage_cost, 0, "", "");

		$wheel_chair_inc = 0;
		if(isset($post_data->adult_wheel_chair))
			foreach ($post_data->adult_wheel_chair as $awc_idx => $awc_val)
				if($awc_val === "1")
				{
					$wheel_chair_inc = 1;
					break;
				}
		if(isset($post_data->child_wheel_chair))
			foreach ($post_data->child_wheel_chair as $cwc_idx => $cwc_val)
				if($cwc_val === "1")
				{
					$wheel_chair_inc = 1;
					break;
				}
		if(isset($post_data->infant_wheel_chair))
			foreach ($post_data->infant_wheel_chair as $iwc_idx => $iwc_val)
				if($iwc_val === "1")
				{
					$wheel_chair_inc = 1;
					break;
				}

		// original travel origin and destinations
		$departure_airports = $arrival_airports = array();
		$departure_dttm = $arrival_dttm = null;
		foreach ($origin_destinations as $od_idx => $od_obj)
		{
			$departure_airports[$od_obj->departure_loc] = $arrival_airports[$od_obj->arrival_loc] = $od_obj;
			if(is_null($departure_dttm))
				$departure_dttm = new DateTime($od_obj->departure_dttm);
			$arrival_dttm = new DateTime($od_obj->arrival_dttm);
		}

		// total travel duration
		$travel_duration = 1;
		if(!is_null($departure_dttm) && !is_null($arrival_dttm))
		{
			$temp_dt_res = $departure_dttm->diff($arrival_dttm);
			$travel_duration = $temp_dt_res->format("%a");
		}

		//contact details
		$contact = $post_data->contact;
		$email = $post_data->email;

		// travellers count
		$total_travellers = 1;
		$travel_insurance_amount = "3000";
		if($s_data->flight_type === MULTICITY)
			$total_travellers = ($s_data->madult * 1) + ($s_data->mchild * 1) + ($s_data->minfant * 1);
		else
			$total_travellers = ($s_data->adult * 1) + ($s_data->child * 1) + ($s_data->infant * 1);

		// travel insurance
		$travel_insurance = 0;
		if($s_data->flight_type !== ONEWAY && $search_data->i_or_d === IORDI)
		{
			if($post_data->travel_insurance === "1")
				$travel_insurance = number_format($total_travellers * $travel_insurance_amount * $travel_duration, 0, "","");
			unset($post_data->travel_insurance);
		}
		//cip details to insert
		$book_cip = array();
		$cip_cost = 0;

		//cip out details
		$cip_out = $cip_out_data = array();
		if(isset($post_data->cip_out))
		{
			$cip_out = $post_data->cip_out;
			unset($post_data->cip_out);
		}

		foreach ($cip_out as $cip_out_idx => $cip_out_include)
			if($cip_out_include === "1")
				$cip_out_data[] = $cip_out_idx;

		$cip_out_inc = count($cip_out_data) > 0 ? "1" : "0";

		if($cip_out_inc === "1")
		{
			foreach ($cip_out_data as $cip_out_data_val)
			{
				$cip_price = $cip_airports[$cip_out_data_val]->cip_out;
				if(!is_null($cip_price))
				{
					$cip_cost += $cip_price * $total_travellers;
					$book_cip[] = array(
										"cip_type" => CIP_OUT,
										"departure_loc" => $departure_airports[$cip_out_data_val]->departure_loc,
										"arrival_loc" => $departure_airports[$cip_out_data_val]->arrival_loc,
										"airline" => $departure_airports[$cip_out_data_val]->airline,
										"flight_no" => $departure_airports[$cip_out_data_val]->flight_no,
										"departure_dttm" => $departure_airports[$cip_out_data_val]->departure_dttm,
										"arrival_dttm" => $departure_airports[$cip_out_data_val]->arrival_dttm,
										"traveller_count" => $total_travellers,
										"cost" => $cip_price,
										"email" => $email,
										"contact" => $contact
										);
				}
			}
		}

		// cip in details
		$cip_in = $cip_in_data = array();
		if(isset($post_data->cip_in))
		{
			$cip_in = $post_data->cip_in;
			unset($post_data->cip_in);
		}

		foreach ($cip_in as $cip_in_idx => $cip_in_include)
			if($cip_in_include === "1")
				$cip_in_data[] = $cip_in_idx;

		$cip_in_inc = count($cip_in_data) > 0 ? "1" : "0";

		if($cip_in_inc === "1")
		{
			foreach ($cip_in_data as $cip_in_data_val)
			{
				$cip_price = $cip_airports[$cip_in_data_val]->cip_in;
				if(!is_null($cip_price))
				{
					$cip_cost += $cip_price * $total_travellers;
					$book_cip[] = array(
										"cip_type" => CIP_IN,
										"departure_loc" => $arrival_airports[$cip_out_data_val]->departure_loc,
										"arrival_loc" => $arrival_airports[$cip_out_data_val]->arrival_loc,
										"airline" => $arrival_airports[$cip_out_data_val]->airline,
										"flight_no" => $arrival_airports[$cip_out_data_val]->flight_no,
										"departure_dttm" => $arrival_airports[$cip_out_data_val]->departure_dttm,
										"arrival_dttm" => $arrival_airports[$cip_out_data_val]->arrival_dttm,
										"traveller_count" => $total_travellers,
										"cost" => $cip_price,
										"email" => $email,
										"contact" => $contact
										);
				}
			}
		}
		$cip_cost = number_format($cip_cost, 0, "", "");

		$discount_cost = 0;
		if(isset($post_data->promocode))
		{
			$promocode = $post_data->promocode;
			unset($post_data->promocode);
			$a_cost = $flight_row->admin_cost;
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
					if((double)($promo->condition * $currency_val) < (double)$a_cost)
					{
						if($promo->type === "1")
							$discount_cost = (($promo->discount / 100) * $a_cost);
						else
							$discount_cost = $promo->discount * $currency_val;
					}
				}
			}
		}
		$book_type = $post_data->book_type; // reserve or book
		unset($post_data->book_type);
		$post_data_json = json_encode($post_data,JSON_UNESCAPED_UNICODE);
		$user_id = $user_type = null;
		if(!is_null($this->data["user_id"]) && !is_null($this->data["user_type"]))
		{
			$user_id = $this->data["user_id"];
			$user_type = $this->data["user_type"];
		}
		$payment_status = "0";
		$api_data = $this->Flight_model->get_flight_api($flight_row->api);;
		$book_data = array(
					"service" => $api_data->service,
					"api" => $api_data->id,
					"user_type" => $user_type,
					"user_id" => $user_id,
					"book_status" => "0",
					"api_status" => "11"
					);
		$this->db->trans_begin();
		try
		{
			$total_cost = number_format(((($flight_row->total_cost * 1) + ($baggage_cost * 1) + ($travel_insurance * 1) + ($cip_cost * 1)) - $discount_cost), 0, "", "");
			$ref_id = $this->Flight_model->new_flight_book($book_data);
			$book_details = array(
							"id" => $ref_id,
							"i_or_d" => $search_data->i_or_d,
							"book_type" => $book_type,
							"input_json" => $search_data->input_json,
							"traveller_json" => $post_data_json,
							"baggage_json" => $flight_row->extra_service_json,
							"email" => $email,
							"contact" => $contact,
							"travel_insurance" => $travel_insurance,
							"cip_in" => $cip_in_inc,
							"cip_out" => $cip_out_inc,
							"wheel_chair" => $wheel_chair_inc,
							"flight_type" => $flight_row->flight_type,
							"api_tax" => $flight_row->api_tax,
							"api_cost" => $flight_row->api_cost,
							"admin_cost" => $flight_row->admin_cost,
							"base_cost" => $flight_row->total_cost,
							"total_cost" => $total_cost,
							"discount" => $discount_cost,
							"currency" => $flight_row->currency,
							"departures" => $flight_row->departures,
							"arrivals" => $flight_row->arrivals,
							"booked_date" => date("Y-m-d H:i:s")
							);


			$this->Flight_model->new_flight_book_details($book_details);
			if(count($book_cip) > 0)
			{
				$final_book_cip_batch = array();
				foreach ($book_cip as $book_cip_idx => $book_cip_val)
				{
					$book_cip_val["book_id"] = $ref_id;
					$final_book_cip_batch[$book_cip_idx] = $book_cip_val;
				}
				$this->Flight_model->new_book_flight_cips($final_book_cip_batch);
			}
			$this->db->trans_commit();
			echo json_encode(array("url" =>"payment/pay_process/".$token."/".$fr_id."/".$ref_id));exit;
		}
		catch(Exception $ex)
		{
			$this->db->trans_rollback();
			echo json_encode(array("url" =>"payment/failure/".$ref_id));exit;
		}
	}




	/*public function pay_process($token, $fr_id, $bk_id)
	{
		//redirect("payment/success/".$token."/".$fr_id."/".$bk_id);
		// $pg_id = "1";
		

		//$pg_details = $this->Common_model->get_pg_details($pg_id);
		$pg_details = (object)array("api_id" => "3", "api_name" => "irankish", "status" => "2", "test_extra" => "", "live_extra" => "AF73", "test_user" => "", "test_pwd" => "", "test_url" => "", "live_user" => "", "live_pwd" => "22338240992352910814917221751200141041845518824222260", "live_url" => "https://ikc.shaparak.ir/TPayment/Payment/index");
		$book_details = $this->Flight_model->get_booking($bk_id);

		$merchant_id = $pg_details->live_extra;
		$data["url"] = $pg_details->live_url;
		$t_data = array("return_url" => base_url("payment/verify_process/".$token."/".$fr_id."/".$bk_id),
						"amount" => 1206,
						"merchant_id" => $merchant_id,
						"book_id" => $book_details->book_id
		);
				;
		$token = $this->get_irankish_token($t_data);
		
		if(!empty($token))
		{
			$upd_data = array(
						"payment_details" => json_encode(array("token" => $token))
						);
			$this->Flight_model->set_booking($bk_id, $upd_data);
			$data["data"] = array(
								"token" => $token,
								"merchantId" => $merchant_id
								);
			$this->load->view("payment/index", $data);
		}
		else
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Payment");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "unable to redirect to payment gateway.");
			redirect("", "refresh");
		}
	}
*/
	public function verify_process($token, $fr_id, $bk_id)
	{
		$pg_details = (object)array("api_id" => "3", "api_name" => "irankish", "status" => "2", "test_extra" => "", "live_extra" => "AF73", "test_user" => "", "test_pwd" => "", "test_url" => "", "live_user" => "", "live_pwd" => "22338240992352910814917221751200141041845518824222260", "live_url" => "https://ikc.shaparak.ir/TPayment/Payment/index");
		$post_data = json_encode($this->input->post());
		$pg_status = array(IRANKISH_INVALID_CHARS => "Unauthorized characters are available in the request.",
							IRANKISH_TRANS_REFLUXED => "Transaction has already been refluxed.",
							IRANKISH_ILLEGAL_QUERY => "The query string is illegal.",
							IRANKISH_ERR_REQ => "Error in the request.",
							IRANKISH_TRANS_NOT_FOUND => "The transaction could not be found.",
							IRANKISH_ERR_INTERNAL_BANK => "Bank internal error.",
							IRANKISH_TRANS_VERIFIED => "Transaction has already been verified.",
							IRANKISH_OK => "true",
							IRANKISH_USER_CANCELLED => "Payment cancelled by user.",
							IRANKISH_LOW_BALANCE => "Insufficient funds.",
							IRANKISH_ERR_CARD_INFO => "Card information is wrong.",
							IRANKISH_ERR_CARD_PASSWORD => "Card password is wrong.",
							IRANKISH_ERR_CVV_EXP_DATE => "Card CVV2 or Expire date is wrong.",
							IRANKISH_ERR_CARD_BLOCKED => "Card is blocked.",
							IRANKISH_ERR_CARD_EXPIRED => "Card is expired.",
							IRANKISH_ERR_TIMEOUT => "Payment gateway time expired.",
							IRANKISH_ERR_BANK_INTERNAL => "Bank internal error.",
							IRANKISH_TRANS_PERMISSION_DENIED => "Bank not issued transaction licences.",
							IRANKISH_AMOUNT_LIMIT => "Amount limit exceeded.",
							IRANKISH_DAY_LIMIT => "Amount limit exceed for today.",
							IRANKISH_MONTH_LIMIT => "Amount limit exceeded for this month."
							);
		$post_data = json_decode($post_data);
		if($post_data->resultCode == IRANKISH_OK)
		{
			echo "payment success\n\n\n\n";
			var_dump($post_data);die;
			$book_details = $this->Flight_model->get_booking($bk_id);
			$payment_details = json_decode($book_details->payment_details);
			$reference_id = !empty($post_data->referenceId) ? $post_data->referenceId : 0;
			$post_data->token = $payment_details->token;
			$data["token"] = $payment_details->token;
			$data["merchant_id"] = $pg_details->live_extra;
			$data["reference_no"] = $reference_id;
			$data["secret_key"] = $pg_details->live_pwd;
			$data["amount"] = $pg_details->total_cost;
			$verify_payment = $this->irankish_verify_payment($data);
			$upd_data = array("payment_details" => json_encode($post_data));
			if($verify_payment === true)
			{
				$upd_data["payment_status"] = "2";
				$this->Flight_model->set_booking($bk_id, $upd_data);
				// redirect("payment/success/".$token."/".$fr_id."/".$bk_id);
			}
			else
			{
				$upd_data["payment_status"] = "1";
				$this->Flight_model->set_booking($bk_id, $upd_data);
				$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Payment Failed");
				$this->session->set_flashdata(SESSION_PREPEND."notification_status", "info");
				$this->session->set_flashdata(SESSION_PREPEND."notification_title", "payment process failed.");
				redirect("", "refresh");
			}
		}
		else
		{
			$upd_data = array("payment_details" => json_encode($post_data));
			$upd_data["payment_status"] = "1";
				$this->Flight_model->set_booking($bk_id, $upd_data);
			$error_msg =  isset($pg_status[$post_data->resultCode]) ? $pg_status[$post_data->resultCode] : "Error code :".$post_data->resultCode;
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Payment Failure");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", $error_msg);
			redirect("", "refresh");
		}
	}

	public function success($token, $fr_id, $bk_id)
	{
		$book_details = $this->Flight_model->get_booking($bk_id);
		if($book_details->service === INTERNATIONAL_FLIGHTS)
		{
			if($book_details->book_type === BOOK_TYPE_RESERVE)
				redirect("flight/reserve/".$token."/".$fr_id."/".$bk_id);
			else
				redirect("flight/book/".$token."/".$fr_id."/".$bk_id);
		}
		elseif($book_details->service === DOMESTIC_FLIGHTS)
		{
			redirect("flight/demo_book/".$token."/".$fr_id."/".$bk_id);
		}
		else
		{
			$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Invalid Operation");
			$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
			$this->session->set_flashdata(SESSION_PREPEND."notification_title", "you have performed invalid operation. try again.");
			redirect("", "refresh");
		}
	}

	public function demo_success($token, $fr_id, $bk_id)
	{
		redirect("flight/demo_book/".$token."/".$fr_id."/".$bk_id);
	}

	public function failure($bk_id)
	{
		$data = array(
					"payment_status" => "1",
					"book_status" => "5"
					);
		$this->Flight_model->set_booking($bk_id, $data);
		$this->session->set_flashdata(SESSION_PREPEND."notification_msg", "Payment Failure");
		$this->session->set_flashdata(SESSION_PREPEND."notification_status", "danger");
		$this->session->set_flashdata(SESSION_PREPEND."notification_title", "unable Payment process cancelled. Try again.");
		redirect("", "refresh");
	}

	protected function get_irankish_token($data)
	{
		// Sample response
		/*object(stdClass)#412 (1) { ["MakeTokenResult"]=> object(stdClass)#411 (3) { ["message"]=> string(7) "Success" ["result"]=> bool(true) ["token"]=> string(20) "93580208036537014819" } }*/
		$client = new SoapClient("https://ikc.shaparak.ir/XToken/Tokens.xml", array("soap_version"   => SOAP_1_1));

		$params["amount"] =  round($data["amount"]);
		$params["merchantId"] = $data["merchant_id"];
		$params["invoiceNo"] = $data["book_id"];
		$params["paymentId"] = $data["book_id"];
		$params["revertURL"] = $data["return_url"];
		$params["description"] = "";
		$result = $client->__soapCall("MakeToken", array($params));
		return $result->MakeTokenResult->token;
	}


	/* New feb-9 Rahul*/

	public function pay_process($token, $fr_id, $bk_id)
	{
		// error_reporting(E_ALL);

		$terminalId = '2108481';
		$userName = 'ir100';
		$userPassword = '29961660';
        $callBackUrl = base_url('payment/paymentDetails');//'http://192.168.0.39/10020ir/payment/paymentDetails';
        $orderId = time();
        $payerId = 0;

		$this->load->library('mellatPayment/Nusoap_base');

		$client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');

    	$namespace= 'http://interfaces.core.sw.bps.com/';
		

		$book_details = $this->Flight_model->get_booking($bk_id);

		$load = '<div>Please Wait, Your request is processing...</div>';

		echo $load;

		$totalCost = $book_details->total_cost;
		
		$merchantId = $pg_details->live_extra;

		$bookId = $book_details->book_id;
		
		$data["url"] = $pg_details->live_url;

		$amount = round($totalCost, 2);
        $localDate = date("Ymd");
        $localTime = date("His");
        $additionalData = "Please clear payment";
		
		$err = $client->getError();
		if ($err) {
			redirect("payment/payment_error","refresh");
		}

		$u_id=$this->session->userdata(SESSION_PREPEND."id");
		if($u_id != false){
			$data_user = $this->B2c_model->get_b2c($u_id);
			$u_name  = $data_user->firstname." ".$data_user->lastname;
			$u_email = $data_user->email_id;
			$u_phone = $data_user->contact_no;
		}
		 
        $userData = array(
                        'name' => @$u_name,
                        'email' => @$u_email,
                        'phone' => @$u_phone
                    );
	  
		$parameters = array(
			'terminalId' => $terminalId,
			'userName' => $userName,
			'userPassword' => $userPassword,
			'orderId' => $orderId,
			'amount' => $amount,
			'localDate' => $localDate,
			'localTime' => $localTime,
			'additionalData' => $additionalData,
			'callBackUrl' => $callBackUrl,
			'payerId' => $payerId);

        /** Insert Payment transaction table **/
        
        $pay_trans_id = $this->Payment_model->addPaymentDetails($parameters, $userData, $bk_id);

        $parameters1 = array(
			'terminalId' => $terminalId,
			'userName' => $userName,
			'userPassword' => $userPassword,
			'orderId' => $orderId,
			'amount' => $amount,
			'localDate' => $localDate,
			'localTime' => $localTime,
			'additionalData' => $additionalData,
			'callBackUrl' => $callBackUrl,
			'payerId' => $payerId);
        
		// Call the SOAP method
		$result = $client->call('bpPayRequest', $parameters1, $namespace);
		// Check for a fault
		
		if ($client->fault) {
		// echo '<pre>112',print_r($result);exit; 
			redirect("payment/payment_error","refresh");
		}else {

			// Check for errors
			
			$resultStr  = $result;

			$err = $client->getError();
			if ($err) {
				// echo '<pre>111',print_r($err);exit; 
				redirect("payment/payment_error","refresh");
				// Display the error
				// echo '<h2>Error</h2><pre>' . $err . '</pre>';
				// die();
			} 
			else {
				// Display the result
				$res = explode (',',$resultStr);

				// echo "<script>alert('Pay Response is : " . $resultStr . "');</script>";
				// echo "Pay Response is : " . $resultStr;

				$ResCode = $res[0];

				$RefId = $res[1];

				if ($ResCode == "0") {
					
					/* From For submitting to mellat */

					echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script><form style="display:none" method="post" action="https://bpm.shaparak.ir/pgwchannel/startpay.mellat" id=form1 name=form1>';


	                echo '<input type="hidden" name="uname" id="uname" value="'.$userData["name"].'"/>';
	                echo '<input type="hidden" name="uemail" id="uemail" value="'.$userData["email"].'"/>';
	                echo '<input type="hidden" name="uphone" id="uphone" value="'.$userData["phone"].'"/>';

	                echo '<input type="hidden" name="PayAmount" id="PayAmount" value="'.$parameters["amount"].'"/>';

	                echo '<textarea name="PayAdditionalData" id="PayAdditionalData">'.$parameters["additionalData"].'</textarea>';

	                echo '<input type="text" name="RefId" id="RefId" value="'.$RefId.'"/>';

	                echo '</form>';

	                echo '<script type="text/javascript">';

	                echo "$('#form1').submit();";

	                echo '</script>';

	                exit;

				} 
				else {
					redirect("payment/payment_error","refresh");
					// log error in app
					// Update table, log the error
					// Show proper message to user
				}
			}// end Display the result
		}// end Check for errors 
		redirect("payment/payment_error","refresh");
		exit();
		
		
	}

	/** Payment Transaction Details and status **/

	public function paymentDetails(){


		$terminalId = '2108481';
		$userName = 'ir100';
		$userPassword = '29961660';
        $orderId = time();
        $payerId = 0;

		$this->load->library('mellatPayment/Nusoap_base');

		$client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');

    	$namespace= 'http://interfaces.core.sw.bps.com/';

    	$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "ResponseCode: ".$_REQUEST['ResCode'].PHP_EOL.
            "Data: ".json_encode($_REQUEST).PHP_EOL.
            "-------------------------".PHP_EOL;

		file_put_contents('paylogs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

		$status = $_REQUEST['ResCode'] == 0 ? 'PENDING' : 'FAILED';

		$bookId = $_REQUEST['SaleOrderId'];

		
		$data = array(
				'status' => 'PENDING',
				'res_code' => $_REQUEST['ResCode'],
				'SaleOrderId' => $_REQUEST['SaleOrderId'],
				'SaleReferenceId' => $_REQUEST['SaleReferenceId'],
				'data_res' => json_encode($_REQUEST)
		);

		$orderId = $_REQUEST['SaleOrderId'];

		$verifySaleOrderId = $_REQUEST['SaleOrderId'];
		$verifySaleReferenceId = $_REQUEST['SaleReferenceId'];

		$key = base64_encode($_REQUEST['SaleOrderId']);

		$parameters = array(
			'terminalId'      => $terminalId,
			'userName' 	      => $userName,
			'userPassword'    => $userPassword,
			'orderId'         => $orderId,
			'saleOrderId'     => $verifySaleOrderId,
			'saleReferenceId' => $verifySaleReferenceId);

		$orderDetails = $this->Payment_model->GetPaymentDetailsByOrderId($orderId);
		//save data
		$bk_id = @$orderDetails->book_id;

		$saveAction = $this->Payment_model->updatePayRequestAction($data);

		if ($_REQUEST['ResCode'] == 0){ 

	
			// Call the SOAP method
			$result = $client->call('bpVerifyRequest', $parameters, $namespace);

			$log_verify  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
		            "Data: ".json_encode($result).PHP_EOL.
		            "DataPGRes: ".json_encode($_REQUEST).PHP_EOL.
		            "-------------------------".PHP_EOL;

			file_put_contents('paylogs/verifylog_'.date("j.n.Y").'.txt', $log_verify, FILE_APPEND);

			// Check for a fault
			if ($client->fault) {
				// header("location:feedback.php?success='.$key.'");
			} 
			else {

				$resultStr = $result;
				
				$err = $client->getError();
				if ($err) {
					// header("location:feedback.php?success='.$key.'");
				} 
				else {

					$status = $resultStr == 0 ? 'VERIFIED' : $status;
					$data = array(
									'status' => $status,
									'res_code' => $resultStr,
									'SaleOrderId' => $_REQUEST['SaleOrderId'],
									'SaleReferenceId' => $_REQUEST['SaleReferenceId']
								);

					// Update Table, Save Verify Status 
					$verifyAction = $this->Payment_model->verifyUpdateAction($data);

					// Note: Successful Verify means complete successful sale was done.
					// echo "<script>alert('Verify Response is : " . $resultStr . "');</script>";
					// echo "Verify Response is : " . $resultStr;

					$orderId = $orderId;
					$settleSaleOrderId = $orderId;
					$settleSaleReferenceId = $verifySaleReferenceId;

					// Check for an error
					$err = $client->getError();
					if ($err) {
						// header("location:feedback.php?success='.$key.'");
					}
				  	  
					$parameters = array(
						'terminalId' => $terminalId,
						'userName' => $userName,
						'userPassword' => $userPassword,
						'orderId' => $orderId,
						'saleOrderId' => $settleSaleOrderId,
						'saleReferenceId' => $settleSaleReferenceId);

					// Call the SOAP method
					$result = $client->call('bpSettleRequest', $parameters, $namespace);

					$log_settle  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
			            "Data: ".json_encode($result).PHP_EOL.
			            "DataPGRes: ".json_encode($_REQUEST).PHP_EOL.
			            "-------------------------".PHP_EOL;

					file_put_contents('paylogs/settlelog_'.date("j.n.Y").'.txt', $log_settle, FILE_APPEND);

					// Check for a fault
					if ($client->fault) {
						// header("location:feedback.php?success='.$key.'");
					} 
					else {
						$resultStr1 = $result;
						
						$err = $client->getError();
						if ($err) {

							// header("location:feedback.php?success='.$key.'");
						} 
						else {

							$status = $resultStr1 == 0 ? 'SETTLED' : $status;
							$data = array(
											'status' => $status,
											'res_code' => $resultStr,
											'SaleOrderId' => $_REQUEST['SaleOrderId'],
											'SaleReferenceId' => $_REQUEST['SaleReferenceId']
										);

							// Update Table, Save Settle Status 
							
							$settleAction = $this->Payment_model->settleUpdateAction($data);

							$pay_status   = $resultStr1 == 0 ? 2 : 1;//success or failed
							$book_status  = $pay_status == 2 ? 1 : 4;//success or failed

							$verifyAction = $this->Payment_model->UpdatePaymentStatus($data,$pay_status,$book_status);

							redirect("flight/voucher/".base64_encode($this->encrypt->encode($bk_id)), "refresh");

							// Note: Successful Settle means that sale is settled.
							
						}// end Display the result
					}// end Check for errors

					}// end Display the result
				}// end Check for errors
		}
		else{

			$verifyAction = $this->Payment_model->verifyUpdateAction($data);

			$verifyAction = $this->Payment_model->UpdatePaymentStatus($data);
			redirect("flight/voucher/".base64_encode($this->encrypt->encode($bk_id)));


		}

	}

	public function payment_error($id = ""){
		$this->load->view('payment/error-display.php');
	}


}