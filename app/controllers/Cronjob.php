<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {
	private $api_data = false;
	public function __construct()
	{
		parent::__construct();
		$this->load->library("Partocrs");
		$this->api_data = $this->Flight_model->get_flight_api(PARTOCRS);
	}

	public function index()
	{
		die;
	}

	public function book_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_BOOK_STATUS);
		var_dump($response);die;
	}

	public function pendind_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_PENDING_STATUS);
		var_dump($response);die;
	}

	public function wait_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_WAIT_STATUS);
		var_dump($response);die;
	}

	public function tkt_in_process_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_TKT_PROCESS_STATUS);
		var_dump($response);die;
	}

	public function ticketed_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_TICKETED_STATUS);
		var_dump($response);die;
	}

	public function tkt_change_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_TKTD_CHANGED_STATUS);
		var_dump($response);die;
	}

	public function tkt_schedule_change_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_TKTD_SCHEDULE_CHANGED_STATUS);
		var_dump($response);die;
	}

	public function cancel_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_CANCEL_STATUS);
		var_dump($response);die;
	}

	public function urgent_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_URGENT_STATUS);
		var_dump($response);die;
	}

	public function api_urgent_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_API_URGENT_STATUS);
		var_dump($response);die;
	}

	public function time_limit_change_status()
	{
		$response = $this->partocrs->air_message_queue($this->api_data, PARTO_TIME_LIMIT_CHANGE_STATUS);
		var_dump($response);die;
	}

	public function update_trip_details($ids, $data = array())
	{
		foreach ($ids as $ticket_id)
		{
			$response = $this->partocrs->air_trip_details($this->api_data, $ticket_id);
			$response = json_decode($response["result"];
			$data["trip_json"] = json_encode($result->TravelItinerary);
			$this->Flight_model->set_booking($ticket_id, $data);
		}
		return true;
	}

	public function message_queue_status($status)
	{
		$response = $this->partocrs->air_message_queue($this->api_data, $status);
		$this->update_message_queue($response, $status);
	}

	protected function update_message_queue($msg_list, $status)
	{
		$msg_list = json_decode($msg_list);
		$unique_ids = array();
		if(!is_null($msg_list) && !empty($msg_list))
			if($msg_list->status === true)
				foreach ($msg_list->result as $msg)
					$unique_ids[] = $msg->UniqueId;
			else
				exit;
		else
			exit;
		$result = $this->Flight_model->get_booking_by_tickets($unique_ids, $status);
		$exist_unique_ids = array();
		if($result !== false)
			foreach ($result as $row)
				$exist_unique_ids[] = $row->ticket_id;
		else
			exit;
		if(count($exist_unique_ids) > 0)
		{
			$response = $this->partocrs->air_remove_message_queue_item($this->api_data, $status, $exist_unique_ids);
			$response = json_decode($response);
			if(!is_null($response) && !empty($response))
				if($msg_list->status === true)
				{
					$book_status = "0";
					if(in_array($status, array(PARTO_BOOK_STATUS, PARTO_TKT_PROCESS_STATUS, PARTO_TIME_LIMIT_CHANGE_STATUS)))
						$book_status = "1";
					elseif(in_array($status, array(PARTO_TICKETED_STATUS, PARTO_TKTD_CHANGED_STATUS, PARTO_TKTD_SCHEDULE_CHANGED_STATUS)))
						$book_status = "2";
					elseif(in_array($status, array(PARTO_CANCEL_STATUS)))
						$book_status = "3";
					elseif(!in_array($status, array(PARTO_PENDING_STATUS, PARTO_WAIT_STATUS, PARTO_URGENT_STATUS, PARTO_API_URGENT_STATUS)))
						$book_status = "4";
					$data = array(
								"book_status" => $book_status,
								"api_status" => $status
								);
					if(in_array($status, array(PARTO_TKT_PROCESS_STATUS, PARTO_BOOK_STATUS, PARTO_TIME_LIMIT_CHANGE_STATUS, PARTO_TICKETED_STATUS, PARTO_TKTD_CHANGED_STATUS, PARTO_TKTD_SCHEDULE_CHANGED_STATUS)))
						$this->update_trip_details($exist_unique_ids, $data);
					else
						$this->Flight_model->set_booking($exist_unique_ids, $data);
				}
				else
					exit;
			else
				exit;
		}
		else
			exit;
	}
}