<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/
class Subscriber extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Subscriber_model");
		$this->load->model("Promocode_model");
		$this->load->model("Sendmail_model");
		$this->data["page_main_title"] = "Subscribers Management";
		$this->data["page_title"] = "Subscribers Management";
	}

	//subscriber view	
	public function index()
	{
		$this->load->view("subscriber/index");
	}

	//subscribers list
	public function subscribers_list()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "email_id");

		$iDisplayStart = $this->input->get("iDisplayStart", true);
		$iDisplayLength = $this->input->get("iDisplayLength", true);
		$iSortCol_0 = $this->input->get("iSortCol_0", true);
		$iSortingCols = $this->input->get("iSortingCols", true);
		$sSearch = $this->input->get("sSearch", true);
		$sEcho = $this->input->get("sEcho", true);

		if($this->input->is_ajax_request())
		{
			// Limit
			$sLimit = " LIMIT 0, 25";
			if (isset($_GET["iDisplayStart"]) && $_GET["iDisplayLength"] !== "-1" && is_numeric($_GET["iDisplayStart"]) && is_numeric($_GET["iDisplayLength"]))
				$sLimit = " LIMIT ".$_GET["iDisplayStart"].", ".$_GET["iDisplayLength"];

			// Ordering
			$sOrder = "";
			if (isset($_GET["iSortCol_0"]))
			{
				$sOrder = " ORDER BY ";
				for ($i = 0; $i < intval($_GET["iSortingCols"]); $i++)
				{
					if ($_GET["bSortable_".intval($_GET["iSortCol_".$i])] === "1")
						if($aColumns[intval($_GET["iSortCol_".$i])] !== "x")
							$sOrder .= $aColumns[intval($_GET["iSortCol_".$i])]." ".($_GET["sSortDir_".$i] === "asc" ? "asc" : "desc") .", ";
				}
				if ($sOrder === " ORDER BY ")
					$sOrder = "";
				if($sOrder !== "")
					$sOrder = substr_replace($sOrder, "", -2);
			}

			// Filtering
			$sWhere = " ";
			if (isset($_GET["sSearch"]) && $_GET["sSearch"] !== "")
			{
				$sWhere = " WHERE (";
				for ($i = 0; $i < count($aColumns); $i++)
				{
					if ( isset($_GET["bSearchable_".$i]) && $_GET["bSearchable_".$i] === "true")
						if($aColumns[$i] !== "x")
							$sWhere .= $aColumns[$i]." LIKE '%".$_GET["sSearch"]."%' OR ";
				}
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ")";
			}
			$result_list = $this->Subscriber_model->get_all_subscribers($sWhere, $sOrder, $sLimit);
			$iTotal = $result_list["count"];

			$rResult = $result_list["result"];

			// Output
			$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iTotal,
			"aaData" => array()
			);
			$i = isset($_GET["iDisplayStart"]) ? ($_GET["iDisplayStart"] * 1) + 1 : 1;
			foreach($rResult as $aRow)
			{
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$status = ($aRow["block"] === "1") ? "true'" : "false";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-info btn-xs has-tooltip user_promocode subscriber' data-placement='top' title='Send Promocode' href='javascript:void(0);'><i class='icon-barcode'></i></a>\n";
				$actions .= "<a class='btn btn-success btn-xs has-tooltip' data-placement='top' title='Send Mail' href='".base_url($this->data["controller"]."/mail".DEFAULT_EXT."?user=".$id)."'><i class='icon-envelope'></i></a>\n";
				if($aRow["block"] === "1")
					$actions .= "<a class='btn btn-warning btn-xs has-tooltip block_subscriber' data-placement='top' title='Unblock' href='javascript:void(0);'><i class='icon-pause'></i></a>\n";
				else
					$actions .= "<a class='btn btn-contrast btn-xs has-tooltip block_subscriber' data-placement='top' title='Block' href='javascript:void(0);'><i class='icon-pause'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip block_remove_subscriber' data-placement='top' title='Block and Delete' href='javascript:void(0);'><i class='icon-off'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_subscriber' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "email" => $aRow["email_id"], "status" => $status, "actions" => $actions, "id" => $id));
				$row[] = "";
				$row[] = "";
				$output["aaData"][] = $row;
			}
		}
		else
		{
			$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => "0",
			"iTotalDisplayRecords" => "0",
			"aaData" => array()
			);
		}
		echo json_encode($output);exit;
	}

	// send promocode to single subscriber
	public function promocode()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$this->form_validation->set_rules("user", "User", "trim|required");
			$this->form_validation->set_rules("promo_code", "Promocode", "trim|required");
			$response["title"] = "Subscriber Management - Send Promocode";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("user")));
				$p_id = $this->encrypt->decode(base64_decode($this->input->post("promo_code")));
				if(is_numeric($id) && $id > 0 && is_numeric($p_id) && $p_id > 0)
				{
					$spc["promocode"] = $this->Promocode_model->get_promocode($p_id);
					$subscriber = $this->Subscriber_model->is_subscriber_exist($id);
					if($spc["promocode"] !== false && $subscriber !== false)
					{
						$spc["to"] = $subscriber->email_id;
						$spc["salutation"] = "Subscriber";
						//send promocode mail function call here
						$mail_sent = $this->Sendmail_model->promocode($spc);
						if($mail_sent !== false)
						{
							$response["msg"] = "Promocode is successfully sent to subscriber Mail.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$response["msg"] = "Failed to send promocode.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Sorry, Operation failed. Try again.";
				}
			}
		}
		echo json_encode($response);exit;
	}

	// send mail to selected subscriber
	public function mail()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("user", "User", "trim|required");
			$this->form_validation->set_rules("subject", "Subject", "trim|required");
			$this->form_validation->set_rules("message", "Message", "trim|required");
			$response["title"] = "Subscriber Management - Send Mail";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("user")));
				if(is_numeric($id) && $id > 0)
				{
					$sm["subject"] = ucfirst($this->input->post("subject"));
					$sm["message"] = ucfirst($this->input->post("message"));
					$subscriber = $this->Subscriber_model->is_subscriber_exist($id);
					if($subscriber !== false)
					{
						$sm["to"] = $subscriber->email_id;
						//send mail function call here
						$mail_sent = $this->Sendmail_model->simple_mail($sm);
						if($mail_sent !== false)
						{
							$response["msg"] = "Mail is successfully sent to subscriber.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							$response["msg"] = "Failed to send mail.";
							$response["msg_status"] = "info";
						}
					}
					else
						$response["msg"] = "Sorry, Operation failed. Try again.";
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Send Mail to Subscriber";
			$id = $this->encrypt->decode(base64_decode($this->input->get("user")));
			if(is_numeric($id) && $id > 0)
			{
				$data["subscribers"] = $this->Subscriber_model->is_subscriber_exist($id);
				if($data["subscribers"] !== false)
				{
					$data["user_id"] = $this->input->get("user");
					$this->load->view("subscriber/mail", $data);
				}
				else
					redirect("subscriber", "refresh");
			}
			else
				redirect("subscriber", "refresh");
		}
		else
			redirect("subscriber", "refresh");
	}

	/* block subscriber from getting updates */
	public function block()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("subscriber")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "1";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Block Subscriber" : " - Unblock Subscriber");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Subscriber_model->update($id, array("block" =>$status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "1" ? "Subscriber blocked from receiving further updates." : "Subscriber unblocked to start receiving further updates.";
					$response["msg_status"] = $status === "1" ? "info" : "success";
				}
				else
					$response["msg"] = "Sorry, Invalid operation.";
			}
		}
		echo json_encode($response);exit;
	}

	/* block and remove subscriber from getting updates */
	public function remove()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("subscriber")));
			$send_mail = $this->input->post("send_mail");
			$response["title"] = $this->data["page_main_title"]." - Block and Remove Subscriber";
			if(is_numeric($id) && $id > 0)
			{
				$this->db->trans_begin();
				try
				{
					$subscriber = $this->Subscriber_model->is_subscriber_exist($id);
					$result = $this->Subscriber_model->update($id, array("block" => "2"));
					if($result !== false)
					{
						$mail_sent = true;
						if($send_mail === "true")
						{
							$brs["to"] = $subscriber->email_id;
							$brs["unsubscribed"] = "Administrator";
							$mail_sent = $this->Sendmail_model->unsubscribe($brs);
						}
						if($mail_sent === true)
						{
							$this->db->trans_commit();
							$response["status"] = "true";
							$response["msg"] = "Subscriber blocked and removed from receiving further updates.";
							$response["msg_status"] = "success";
						}
						else
						{
							$this->db->trans_rollback();
							$response["msg"] = "Failed to send mail.";
						}
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg"] = "Sorry, Invalid operation.";
					}
				}
				catch(Exception $ex)
				{
					$this->db->trans_rollback();
					$response["msg"] = "Sorry, Operation failed.";
				}
			}
		}
		echo json_encode($response);exit;
	}

	/* delete subscriber from getting updates */
	public function delete()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("subscriber")));
			$send_mail = $this->input->post("send_mail");
			$response["title"] = "Subscriber Management - Delete Subscriber";
			if(is_numeric($id) && $id > 0)
			{
				$this->db->trans_begin();
				$subscriber = $this->Subscriber_model->is_subscriber_exist($id);
				$result = $this->Subscriber_model->delete($id);
				if($result !== false)
				{
					$mail_sent = true;
					if($send_mail === "true")
					{
						$rs["to"] = $subscriber->email_id;
						$rs["unsubscribed"] = "Administrator";
						$mail_sent = $this->Sendmail_model->unsubscribe($rs);
					}
					if($mail_sent === true)
					{
						$this->db->trans_commit();
						$response["status"] = "true";
						$response["msg"] = "Subscriber removed from receiving further updates. This subscriber can re-subscribe again.";
						$response["msg_status"] = "success";
					}
					else
					{
						$this->db->trans_rollback();
						$response["msg"] = "Failed to send mail.";
					}
				}
				else
				{
					$this->db->trans_rollback();
					$response["msg"] = "Sorry, Invalid operation.";
				}
			}
		}
		echo json_encode($response);exit;
	}

}