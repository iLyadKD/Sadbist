<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/
class Homepage extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Homepage_model");
		$this->data["page_main_title"] = "Homepage Management";
		$this->data["page_title"] = "Homepage Management";
	}

	//homepage list	
	public function index()
	{
		$this->data["page_title"] = "Sliders";
		$this->load->view("homepage/slider/index");
	}

	//slider images
	public function sliders()
	{
		$this->data["page_title"] = "Sliders";
		$this->load->view("homepage/slider/index");
	}

	public function get_slider_list()
	{
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("", "name", "", "content_en");

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
			$result_list = $this->Homepage_model->get_all_sliders($sWhere, $sOrder, $sLimit);
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
				$image_html = "<img width='75px' height='50px' src='".upload_url($aRow["image"])."' alt=''>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control slider_image_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."sliders_edit".DEFAULT_EXT."?slider=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a class='btn btn-danger btn-xs has-tooltip delete_slider_image' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "name" =>$aRow["name"], "image_html" => $image_html, "image" => $aRow["image"], "status" => $status_html, "actions" => $actions, "id" => $id));
				$row[] = "";
				$row[] = "";
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

	public function sliders_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("slider_name", "Slider Name", "trim|required");
			if (empty($_FILES['slider_image']['name']))
				$this->form_validation->set_rules("slider_image", "Slider Image", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add Slider Image";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$slider_name = ucfirst($this->input->post("slider_name"));
				// $content_en = strip_tags($this->input->post("content_en"));
				$insert_data = array("name" => $slider_name);
				if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."slider_images/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("slider_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$insert_data["image"] = "slider_images/".$simg["file_name"];
						/*
						//resize image to fixed width
						$config['image_library'] = 'gd2';
						$config['source_image'] = REL_IMAGE_UPLOAD_PATH.$image;
						$config['overwrite'] = true;
						$config['maintain_ratio'] = true;
						$config['width'] = 100;
						$config['height'] = 100;
						$this->load->library('image_lib',$config);
						$this->image_lib->resize();
						$this->image_lib->clear()*/
						$result = $this->Homepage_model->add_slider_image($insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Slider Image added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
							$response["msg"] = "Sorry, Failed to add slider image.";
						}
					}
					else
						$response["msg"] = "Sorry, Failed to add slider image.";
				}
				else
					$response["msg"] = "Please select valid image file.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Slider";
			$this->load->view("homepage/slider/add");
		}
	}

	/*change slider image status*/
	public function slider_image_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Slider Image Activate" : " - Slider Image Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->update_slider_image($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Slider image deactivated successfully." : "Slider image activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update slider image status.";
			}
		}
		echo json_encode($response);exit;
	}

	public function sliders_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("slider", "Slider", "trim|required");
			$this->form_validation->set_rules("current_slider_image", "Slider Image", "trim");
			$this->form_validation->set_rules("slider_name", "Slider Name", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Slider Image";
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
				if(is_numeric($id) && $id > 0)
				{
					$current_image_path = $this->input->post("current_slider_image");
					$slider_name = ucfirst($this->input->post("slider_name"));
					$insert_data = array("name" => $slider_name);
					if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."slider_images/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("slider_image");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$insert_data["image"] = "slider_images/".$simg["file_name"];
						}
						else
						{
							$response["msg"] = "Sorry, Failed to upload new slider image.";
							echo json_encode($response);exit;
						}
					}
					$result = $this->Homepage_model->update_slider_image($id, $insert_data);
					if($result !== false)
					{
						$response["msg"] = "Slider Image updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						if(isset($insert_data["image"]))
						{
							$new_data["image_path"] = $insert_data["image"];
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$current_image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$current_image_path))
								unlink(REL_IMAGE_UPLOAD_PATH.$current_image_path);
						}
						else
							$new_data["image_path"] = $current_image_path;
						$response["new_data"] = $new_data;
					}
					else
					{
						if(isset($insert_data["image"]))
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Edit Slider";
			$data["slider_id"] = $this->input->get("slider");
			$id = $this->encrypt->decode(base64_decode($this->input->get("slider")));
			$data["slider"] = $this->Homepage_model->get_slider_image($id);
			if($data["slider"] === false)
				redirect("homepage/sliders", "refresh");
			$this->load->view("homepage/slider/edit", $data);
		}
		else
			redirect("homepage/sliders", "refresh");
	}


	/*delete slider image*/
	public function delete_slider_image()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete Slider Image";
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			$image = $this->input->post("slider_image");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->delete_slider_image($id);
				if($result !== false)
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
								unlink(REL_IMAGE_UPLOAD_PATH.$image);
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Slider Image deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove Slider Image.";
			}
		}
		echo json_encode($response);exit;
	}

	/**********************************Deals Start********************************************/
	
	public function deals_add()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			$this->form_validation->set_rules("category", "Category Name", "trim|required");
			
			
			if (empty($_FILES['slider_image']['name']))
				$this->form_validation->set_rules("slider_image", "Slider Image", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add deals";
			if($this->form_validation->run() === false)
			{
				
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$content_en = str_replace("'", "", $this->input->post("content_en"));
				$content_fa = str_replace("'", "", $this->input->post("content_fa"));
				$category = $this->input->post("category");
				$display_section = $this->input->post("display_section");
				
				
				if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/deals/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("slider_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$image = "deals/".$simg["file_name"];
						
						unset($_POST['content_en'],$_POST['content_fa'],$_POST['category']);
						$json_input = json_encode($this->input->post());
						$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa,"category" => $category,"image" => $image,"inputs" => $json_input,"display_section" => $display_section);
						
						//pr($insert_data);exit;
						$result = $this->Homepage_model->add_home_deals($insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Deals added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
							$response["msg"] = "Sorry, Failed to add deals.";
						}
					}
					else
						$response["msg"] = "Sorry, Failed to add deals.";
				}
				else
					$response["msg"] = "Please select valid image file.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Deals";
			$this->load->view("homepage/deals/add");
		}
	}
	
	public function deals()
	{
		$this->data["page_title"] = "Deals";
		$this->load->view("homepage/deals/deals");
	}
	
	public function get_deal_list()
	{
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("content_en");

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
			$sWhere = "";
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
			$result_list = $this->Homepage_model->get_all_deals($sWhere, $sOrder, $sLimit);
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
				
				if($aRow["category"] == 1)
					$category = 'Tour';
				elseif($aRow["category"] == 2)
					$category = 'Flight';
				elseif($aRow["category"] == 3)
					$category = 'Hotel';
				else
					$category = 'Others';
					
				$display_section = $aRow["display_section"];
				$content = (strlen($aRow["content_en"]) > 40) ? substr($aRow["content_en"],0,40).'...' : $aRow["content_en"];
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$image_html = "<img width='75px' height='50px' src='".upload_url($aRow["image"])."' alt=''>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control deals_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."deals_edit".DEFAULT_EXT."?deals_id=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a  class='btn btn-danger btn-xs has-tooltip delete_deals' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "content" =>$content, "image_html" => $image_html, "image" => $aRow["image"], "status" => $status_html, "actions" => $actions, "id" => $id,'category' =>$category, 'display_section' => $display_section));
				$row[] = "";
				$row[] = "";
				$row[] = "";
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
	
	public function deals_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			$this->form_validation->set_rules("category", "Category Name", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Update Tour Deals";
			
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->encrypt->decode(base64_decode($this->input->post("deals_id")));
				if(is_numeric($id) && $id > 0)
				{
					$current_image_path = $this->input->post("current_image");
					
					$content_en = str_replace("'", "", $this->input->post("content_en"));
					$content_fa = str_replace("'", "", $this->input->post("content_fa"));
					$category = $this->input->post("category");
					$display_section = $this->input->post("display_section");

					unset($_POST['content_en'],$_POST['content_fa'],$_POST['category'],$_POST["deals_id"],$_POST["current_image"]);
					$json_input = json_encode($this->input->post());
						$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa,"category" => $category,"inputs" => $json_input,"display_section" => $display_section);
						
					if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/deals/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("slider_image");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$insert_data["image"] = "deals/".$simg["file_name"];
						}
						else
						{
							$response["msg"] = "Sorry, Failed to upload new slider image.";
							echo json_encode($response);exit;
						}
					}
					
					
					$result = $this->Homepage_model->update_deals($id, $insert_data);
					if($result !== false)
					{
						$response["msg"] = "Deals updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						if(isset($insert_data["image"]))
						{
							$new_data["image_path"] = $insert_data["image"];
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$current_image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$current_image_path))
								unlink(REL_IMAGE_UPLOAD_PATH.$current_image_path);
						}
						else
							$new_data["image_path"] = $current_image_path;
						$response["new_data"] = $new_data;
					}
					else
					{
						if(isset($insert_data["image"]))
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Deals";
			$data["deals_id"] = $this->input->get("deals_id");
			$id = $this->encrypt->decode(base64_decode($this->input->get("deals_id")));
			$data["deals"] = $this->Homepage_model->get_deals_by_id($id);
			if($data["deals"] === false)
				redirect("homepage/deals", "refresh");
			
			//pr($data);exit;
			$this->load->view("homepage/deals/edit", $data);
		}
		else
			redirect("homepage/deals", "refresh");
	}
	
	public function deals_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("id")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Deals Activate" : " - Deals Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->update_deals($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Deals deactivated successfully." : "Deals activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update deals status.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function delete_deals()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete deals Image";
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			$image = $this->input->post("slider_image");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->delete_deals($id);
				if($result !== false)
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
								unlink(REL_IMAGE_UPLOAD_PATH.$image);
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Deals deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove deals.";
			}
		}
		echo json_encode($response);exit;
	}
	
		/**********************************Deals End********************************************/
		
		
		
		/**********************************Tour Deals Start********************************************/
		
		
		public function tour_deals()
	{
		$this->data["page_title"] = "Tour Deals";
		$this->load->view("homepage/tour_deals/deals");
	}
	
	public function deals_add_tour()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			
			
			if (empty($_FILES['slider_image']['name']))
				$this->form_validation->set_rules("slider_image", "Slider Image", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add tour deals";
			if($this->form_validation->run() === false)
			{
				
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$content_en = str_replace("'", "", $this->input->post("content_en"));
				$content_fa = str_replace("'", "", $this->input->post("content_fa"));
				$tour_link  = base64_encode($this->encrypt->encode($this->input->post("tour_link")));
				$master_id = base64_encode($this->encrypt->encode($this->input->post("master_id")));
				
				if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/tour_deals/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("slider_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$image = "tour_deals/".$simg["file_name"];
						
						
						$info = explode(",",json_decode($this->input->post("info")));
						$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa,"image" => $image,"tour_link"=>$tour_link,"master_id"=>$master_id,'hotel_name_en'=>$info[0],'hotel_name_fa'=>$info[1],'rating'=>$info[2],'address_en'=>$info[3],'address_fa'=>$info[4],'price'=>$info[5]);
						//pr($insert_data);exit;
						$result = $this->Homepage_model->add_tour_deals($insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Tour deals added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
							$response["msg"] = "Sorry, Failed to add deals.";
						}
					}
					else
						$response["msg"] = "Sorry, Failed to add deals.";
				}
				else
					$response["msg"] = "Please select valid image file.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Tour Deals";
			$this->load->view("homepage/tour_deals/add");
		}
	}
	
	
	
	public function get_tour_deal_list()
	{
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("content_en","hotel_name_en","address_en");

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
			$sWhere = "";
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
			$result_list = $this->Homepage_model->get_all_tour_deals($sWhere, $sOrder, $sLimit);
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
				$address = (strlen($aRow["address_en"]) > 50) ? substr($aRow["address_en"],0,50).'...' : $aRow["address_en"];
				$contents = (strlen($aRow["content_en"]) > 50) ? substr($aRow["content_en"],0,50).'...' : $aRow["content_en"];
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$image_html = "<img width='75px' height='50px' src='".upload_url($aRow["image"])."' alt=''>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control tour_deals_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."tour_deals_edit".DEFAULT_EXT."?tour_deals_id=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a  class='btn btn-danger btn-xs has-tooltip delete_tour_deals' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "hotel_name" =>$aRow["hotel_name_en"], "image_html" => $image_html, "image" => $aRow["image"], "status" => $status_html, "actions" => $actions, "id" => $id,'deals_text' =>$contents,'address' =>$address));
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
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
	
	public function tour_deals_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("id")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Tour deals Activate" : " - Tour deals Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->update_tour_deals($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Tour deals deactivated successfully." : "Tour deals activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update tour deals status.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function delete_tour_deals()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete deals";
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			$image = $this->input->post("slider_image");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->delete_tour_deals($id);
				if($result !== false)
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
								unlink(REL_IMAGE_UPLOAD_PATH.$image);
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Deals deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove deals.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function tour_deals_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			
			$response["title"] = $this->data["page_main_title"]." - Update Tour Deals";
			$info = $this->input->post("info");
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->input->post("tour_deals_id");
				if(is_numeric($id) && $id > 0)
				{
					$current_image_path = $this->input->post("current_image");
					
					//pr($_POST);exit;
					
					$content_en = str_replace("'", "", $this->input->post("content_en"));
					$content_fa = str_replace("'", "", $this->input->post("content_fa"));
					
					
					
					
					
						if($info != ''){
							$tour_link  = base64_encode($this->encrypt->encode($this->input->post("tour_link")));
							$master_id = base64_encode($this->encrypt->encode($this->input->post("master_id")));
							$info = explode(",",json_decode($this->input->post("info")));
							$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa,"tour_link"=>$tour_link,"master_id"=>$master_id,'hotel_name_en'=>$info[0],'hotel_name_fa'=>$info[1],'rating'=>$info[2],'address_en'=>$info[3],'address_fa'=>$info[4],'price'=>$info[5]);	
						}else{
							$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa);	
						}
						
						
					//pr($insert_data);exit;
					if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/tour_deals/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("slider_image");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$insert_data["image"] = "tour_deals/".$simg["file_name"];
						}
						else
						{
							$response["msg"] = "Sorry, Failed to upload new deals image.";
							echo json_encode($response);exit;
						}
					}
					
					//pr($insert_data);exit;
					$result = $this->Homepage_model->update_tour_deals($id, $insert_data);
					if($result !== false)
					{
						$response["msg"] = "Tour Deals updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						if(isset($insert_data["image"]))
						{
							$new_data["image_path"] = $insert_data["image"];
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$current_image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$current_image_path))
								unlink(REL_IMAGE_UPLOAD_PATH.$current_image_path);
						}
						else
							$new_data["image_path"] = $current_image_path;
						$response["new_data"] = $new_data;
					}
					else
					{
						if(isset($insert_data["image"]))
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Tour Deals";
			$id = $this->encrypt->decode(base64_decode($this->input->get("tour_deals_id")));
			$data["tour_deals_id"] = $id;
			$data["tour_deals"] = $this->Homepage_model->get_tour_deals_by_id($id);
			$data['master_id'] = $this->encrypt->decode(base64_decode($data["tour_deals"]->master_id));
			if($data["tour_deals"] === false)
				redirect("homepage/tour_deals", "refresh");
			
			//pr($data);exit;
			$this->load->view("homepage/tour_deals/edit", $data);
		}
		else
			redirect("homepage/tour_deals", "refresh");
	}

		
		/**********************************Tour Deals End********************************************/
		
		
		
		/**********************************Hotel Deals Start********************************************/
		
		
		public function hotel_deals()
	{
		$this->data["page_title"] = "Hotel Deals";
		$this->load->view("homepage/hotel_deals/deals");
	}
	
	public function deals_add_hotel()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tour_link", "Tour Link", "trim|required");
			
			if (empty($_FILES['slider_image']['name']))
				$this->form_validation->set_rules("slider_image", "Slider Image", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add hotel deals";
			if($this->form_validation->run() === false)
			{
				
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$tour_link  = base64_encode($this->encrypt->encode($this->input->post("tour_link")));
				$master_id = base64_encode($this->encrypt->encode($this->input->post("master_id")));
				
				if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
				{
$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/hotel_deals/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("slider_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$image = "hotel_deals/".$simg["file_name"];
						
						
						$info = explode(",",json_decode($this->input->post("info")));
						$insert_data = array("image" => $image,"tour_link"=>$tour_link,"master_id"=>$master_id,'hotel_name_en'=>$info[0],'hotel_name_fa'=>$info[1],'rating'=>$info[2],'address_en'=>$info[3],'address_fa'=>$info[4],'price'=>$info[5]);
						//pr($insert_data);exit;
						$result = $this->Homepage_model->add_hotel_deals($insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Hotel deals added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
							$response["msg"] = "Sorry, Failed to add deals.";
						}
					}
					else
						$response["msg"] = "Sorry, Failed to add deals.";
				}
				else
					$response["msg"] = "Please select valid image file.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Hotel Deals";
			$this->load->view("homepage/hotel_deals/add");
		}
	}
	
	
	
	public function get_hotel_deal_list()
	{
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("hotel_name_en","address_en");

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
			$sWhere = "";
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
			$result_list = $this->Homepage_model->get_all_hotel_deals($sWhere, $sOrder, $sLimit);
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
				$address = (strlen($aRow["address_en"]) > 50) ? substr($aRow["address_en"],0,50).'...' : $aRow["address_en"];
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$image_html = "<img width='75px' height='50px' src='".upload_url($aRow["image"])."' alt=''>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control hotel_deals_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."hotel_deals_edit".DEFAULT_EXT."?hotel_deals_id=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a  class='btn btn-danger btn-xs has-tooltip delete_hotel_deals' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "hotel_name" =>$aRow["hotel_name_en"], "image_html" => $image_html, "image" => $aRow["image"], "status" => $status_html, "actions" => $actions, "id" => $id,'address' =>$address));
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
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
	
	public function hotel_deals_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("id")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Hotel deals Activate" : " - Hotel deals Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->update_hotel_deals($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Hotel deals deactivated successfully." : "Hotel deals activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update hotel deals status.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function delete_hotel_deals()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response["title"] = $this->data["page_main_title"]." - Delete deals";
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			$image = $this->input->post("slider_image");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->delete_hotel_deals($id);
				if($result !== false)
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
								unlink(REL_IMAGE_UPLOAD_PATH.$image);
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Deals deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove deals.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function hotel_deals_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("tour_link", "Tour Link", "trim|required");

			$response["title"] = $this->data["page_main_title"]." - Update Hotel Deals";
			$info = $this->input->post("info");
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->input->post("hotel_deals_id");
				if(is_numeric($id) && $id > 0)
				{
					$current_image_path = $this->input->post("current_image");
						if($info != ''){
							$tour_link  = base64_encode($this->encrypt->encode($this->input->post("tour_link")));
							$master_id = base64_encode($this->encrypt->encode($this->input->post("master_id")));
							$info = explode(",",json_decode($this->input->post("info")));
							$insert_data = array("tour_link"=>$tour_link,"master_id"=>$master_id,'hotel_name_en'=>$info[0],'hotel_name_fa'=>$info[1],'rating'=>$info[2],'address_en'=>$info[3],'address_fa'=>$info[4],'price'=>$info[5]);	
						}else{
						
						}
						
						
					//pr($insert_data);exit;
					if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/hotel_deals/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("slider_image");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$insert_data["image"] = "hotel_deals/".$simg["file_name"];
						}
						else
						{
							$response["msg"] = "Sorry, Failed to upload new deals image.";
							echo json_encode($response);exit;
						}
					}
					
					//pr($insert_data);exit;
					$result = $this->Homepage_model->update_hotel_deals($id, $insert_data);
					if($result !== false)
					{
						$response["msg"] = "Hotel Deals updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						if(isset($insert_data["image"]))
						{
							$new_data["image_path"] = $insert_data["image"];
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$current_image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$current_image_path))
								unlink(REL_IMAGE_UPLOAD_PATH.$current_image_path);
						}
						else
							$new_data["image_path"] = $current_image_path;
						$response["new_data"] = $new_data;
					}
					else
					{
						if(isset($insert_data["image"]))
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Tour Deals";
			$id = $this->encrypt->decode(base64_decode($this->input->get("hotel_deals_id")));
			$data["hotel_deals_id"] = $id;
			$data["hotel_deals"] = $this->Homepage_model->get_hotel_deals_by_id($id);
			$data['master_id'] = $this->encrypt->decode(base64_decode($data["hotel_deals"]->master_id));
			if($data["hotel_deals"] === false)
				redirect("homepage/hotel_deals", "refresh");
			
			//pr($data);exit;
			$this->load->view("homepage/hotel_deals/edit", $data);
		}
		else
			redirect("homepage/hotel_deals", "refresh");
	}
		
		/**********************************Hotel Deals End********************************************/
		
		
		
		/**********************************News Start********************************************/
		
		
		public function news()
	{
		$this->data["page_title"] = "News";
		$this->load->view("homepage/news/news");
	}
	
	public function add_news()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			
			
			$response["title"] = $this->data["page_main_title"]." - Add News";
			if($this->form_validation->run() === false)
			{
				
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$content_en = str_replace("'", "", $this->input->post("content_en"));
				$content_fa = str_replace("'", "", $this->input->post("content_fa"));
				
				$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa);
				$result = $this->Homepage_model->add_news($insert_data);
				if($result !== false && $result > 0)
				{
					$response["msg"] = "News added successfully.";
					$response["msg_status"] = "success";
					$response["status"] = "true";
				}
				else
				{
					$response["msg"] = "Sorry, Failed to add news.";
				}
					
				
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add News";
			$this->load->view("homepage/news/add");
		}
	}
	
	
	
	public function get_news_list()
	{
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("content_en");

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
			$sWhere = "";
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
			$result_list = $this->Homepage_model->get_all_news($sWhere, $sOrder, $sLimit);
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
				$contents = (strlen($aRow["content_en"]) > 50) ? substr($aRow["content_en"],0,50).'...' : $aRow["content_en"];
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control news_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."news_edit".DEFAULT_EXT."?news_id=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a  class='btn btn-danger btn-xs has-tooltip delete_news' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++,  "status" => $status_html, "actions" => $actions, "id" => $id,'news_text' =>$contents));
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
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
	
	public function news_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("id")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - News Activate" : " - News Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->update_news($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "News  deactivated successfully." : "News activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update news status.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function delete_news()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete deals";
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->delete_news($id);
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "News deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove news.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function news_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			
			$response["title"] = $this->data["page_main_title"]." - Update News";
			$info = $this->input->post("info");
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->input->post("news_id");
				if(is_numeric($id) && $id > 0)
				{
					
					$content_en = str_replace("'", "", $this->input->post("content_en"));
					$content_fa = str_replace("'", "", $this->input->post("content_fa"));
					
					$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa);
					
						
						
					//pr($insert_data);exit;
					
					$result = $this->Homepage_model->update_news($id, $insert_data);
					if($result !== false)
					{
						$response["msg"] = "News updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						
					}
					else
					{
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update News";
			$id = $this->encrypt->decode(base64_decode($this->input->get("news_id")));
			$data["news_id"] = $id;
			$data["news"] = $this->Homepage_model->get_news_by_id($id);
			if($data["news"] === false)
				redirect("homepage/news", "refresh");
			$this->load->view("homepage/news/edit", $data);
		}
		else
			redirect("homepage/news", "refresh");
	}

		
		/**********************************News End********************************************/
		
		
		
		/**********************************Attraction Start********************************************/
		
		
		public function attractions()
	{
		$this->data["page_title"] = "National Attractions";
		$this->load->view("homepage/attraction/attraction");
	}
	
	public function add_attraction()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			
			
			if (empty($_FILES['slider_image']['name']))
				$this->form_validation->set_rules("slider_image", "Slider Image", "trim|required");
			$response["title"] = $this->data["page_main_title"]." - Add attraction";
			if($this->form_validation->run() === false)
			{
				
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$content_en = str_replace("'", "", $this->input->post("content_en"));
				$content_fa = str_replace("'", "", $this->input->post("content_fa"));
				$tour_link  = base64_encode($this->encrypt->encode($this->input->post("tour_link")));
				$master_id = base64_encode($this->encrypt->encode($this->input->post("master_id")));
				
				if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
				{
					$this->load->library("upload");
					$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/attractions/";
					$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
					$config["overwrite"]     = FALSE;
					$config["encrypt_name"] = TRUE;
					$this->upload->initialize($config);
					$img_upd_status = $this->upload->do_upload("slider_image");
					if($img_upd_status !== false && $img_upd_status !== null)
					{
						$simg = $this->upload->data();
						$image = "attractions/".$simg["file_name"];
						
						
						$info = explode(",",json_decode($this->input->post("info")));
						$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa,"image" => $image,"tour_link"=>$tour_link,"master_id"=>$master_id,'tour_en'=>$info[0],'tour_fa'=>$info[1],'day'=>$info[2],'address_en'=>$info[3],'address_fa'=>$info[4],'price'=>$info[5]);
						$result = $this->Homepage_model->add_attraction($insert_data);
						if($result !== false && $result > 0)
						{
							$response["msg"] = "Attraction added successfully.";
							$response["msg_status"] = "success";
							$response["status"] = "true";
						}
						else
						{
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
							$response["msg"] = "Sorry, Failed to attraction.";
						}
					}
					else
						$response["msg"] = "Sorry, Failed to add attraction.";
				}
				else
					$response["msg"] = "Please select valid image file.";
			}
			echo json_encode($response);exit;
		}
		else
		{
			$this->data["page_title"] = "Add Attraction";
			$this->load->view("homepage/attraction/add");
		}
	}
	
	
	
	public function get_attraction_list()
	{
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
		* you want to insert a non-database field (for example a counter or static image)
		*/
		$aColumns = array("content_en","address_en");

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
			$sWhere = "";
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
			$result_list = $this->Homepage_model->get_attraction_list($sWhere, $sOrder, $sLimit);
			
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
				$address = (strlen($aRow["address_en"]) > 50) ? substr($aRow["address_en"],0,50).'...' : $aRow["address_en"];
				$contents = (strlen($aRow["content_en"]) > 50) ? substr($aRow["content_en"],0,50).'...' : $aRow["content_en"];
				$id = base64_encode($this->encrypt->encode($aRow["id"]));
				$row = array();
				$image_html = "<img width='75px' height='50px' src='".upload_url($aRow["image"])."' alt=''>";
				$status = ($aRow["status"] === "1") ? "checked='checked'" : "";
				$status_html = "<div class='toggle-switch'><input type='checkbox' $status id='$id' class='toggle-control attraction_status'><label for='$id' class='toggle-checkbox toggle-check-cross'></label></div>";
				$actions = "<div class='pull-left'>";
				$actions .= "<a class='btn btn-primary btn-xs has-tooltip' data-placement='top' title='Edit' href='".base_url($this->data["controller"].DIRECTORY_SEPARATOR."attraction_edit".DEFAULT_EXT."?attraction_id=".$id)."'><i class='icon-edit'></i></a>\n";
				$actions .= "<a  class='btn btn-danger btn-xs has-tooltip delete_attraction' data-placement='top' title='Delete' href='javascript:void(0);'><i class='icon-remove'></i></a>\n";
				$actions .= "<br>";
				$actions .= "</div>";
				$row[] = json_encode(array("sl_no" => $i++, "hotel_name" =>$aRow["tour_en"], "image_html" => $image_html, "image" => $aRow["image"], "status" => $status_html, "actions" => $actions, "id" => $id,'deals_text' =>$contents,'address' =>$address));
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
				$row[] = "";
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
	
	public function attraction_status()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$id = $this->encrypt->decode(base64_decode($this->input->post("id")));
			$status = is_numeric($this->input->post("status")) ? $this->input->post("status") : "0";
			$response["title"] = $this->data["page_main_title"].($status === "1" ? " - Attraction Activate" : " - Attraction Deactivate");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->update_attraction($id, array("status" => $status));
				if($result !== false)
				{
					$response["status"] = "true";
					$response["msg"] = $status === "0" ? "Attraction deactivated successfully." : "Attraction activated successfully.";
						$response["msg_status"] = $status === "0" ? "info" : "success";
				}
				else
					$response["msg"] = "Failed to update attraction status.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function delete_attraction()
	{
		$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			$response["title"] = $this->data["page_main_title"]." - Delete attraction";
			$id = $this->encrypt->decode(base64_decode($this->input->post("slider")));
			$image = $this->input->post("slider_image");
			if(is_numeric($id) && $id > 0)
			{
				$result = $this->Homepage_model->delete_attraction($id);
				if($result !== false)
				{
					if(!is_dir(REL_IMAGE_UPLOAD_PATH.$image) && file_exists(REL_IMAGE_UPLOAD_PATH.$image))
								unlink(REL_IMAGE_UPLOAD_PATH.$image);
					$response["status"] = "true";
					$response["msg_status"] = "success";
					$response["msg"] = "Attraction deleted successfully.";
				}
				else
					$response["msg"] = "Failed to remove attraction.";
			}
		}
		echo json_encode($response);exit;
	}
	
	public function attraction_edit()
	{
		if($this->input->is_ajax_request() && count($this->input->post()) > 0)
		{
			
			$response = array("status" => "false", "msg_status" => "danger", "title" => "Invalid", "msg" => "Invalid Operation.");
			$this->form_validation->set_rules("content_en", "Content english", "trim|required");
			$this->form_validation->set_rules("content_fa", "Content farsi", "trim|required");
			
			$response["title"] = $this->data["page_main_title"]." - Update Attraction";
			$info = $this->input->post("info");
			if($this->form_validation->run() === false)
			{
				$response["msg"] = "Please check the details you have entered.";
				$response["msg_status"] = "info";
			}
			else
			{
				$id = $this->input->post("attraction_id");
				if(is_numeric($id) && $id > 0)
				{
					$current_image_path = $this->input->post("current_image");
					
					//pr($_POST);exit;
					
					$content_en = str_replace("'", "", $this->input->post("content_en"));
					$content_fa = str_replace("'", "", $this->input->post("content_fa"));
					
					
					
					
					
						if($info != ''){
							$tour_link  = base64_encode($this->encrypt->encode($this->input->post("tour_link")));
							$master_id = base64_encode($this->encrypt->encode($this->input->post("master_id")));
							$info = explode(",",json_decode($this->input->post("info")));
							$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa,"tour_link"=>$tour_link,"master_id"=>$master_id,'tour_en'=>$info[0],'tour_fa'=>$info[1],'day'=>$info[2],'address_en'=>$info[3],'address_fa'=>$info[4],'price'=>$info[5]);	
						}else{
							$insert_data = array("content_en" => $content_en,"content_fa" => $content_fa);	
						}
						
						
					//pr($insert_data);exit;
					if(isset($_FILES)&& $_FILES["slider_image"]["error"] === 0)
					{
						$this->load->library("upload");
						$config["upload_path"] = REL_IMAGE_UPLOAD_PATH."/attractions/";
						$config["allowed_types"] = "gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG";
						$config["overwrite"]     = FALSE;
						$config["encrypt_name"] = TRUE;
						$this->upload->initialize($config);
						$img_upd_status = $this->upload->do_upload("slider_image");
						if($img_upd_status !== false && $img_upd_status !== null)
						{
							$simg = $this->upload->data();
							$insert_data["image"] = "attractions/".$simg["file_name"];
						}
						else
						{
							$response["msg"] = "Sorry, Failed to upload new attraction image.";
							echo json_encode($response);exit;
						}
					}
					
					//pr($insert_data);exit;
					$result = $this->Homepage_model->update_attraction($id, $insert_data);
					if($result !== false)
					{
						$response["msg"] = "Attraction updated successfully.";
						$response["msg_status"] = "success";
						$response["status"] = "true";
						$new_data = array();
						if(isset($insert_data["image"]))
						{
							$new_data["image_path"] = $insert_data["image"];
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$current_image_path) && file_exists(REL_IMAGE_UPLOAD_PATH.$current_image_path))
								unlink(REL_IMAGE_UPLOAD_PATH.$current_image_path);
						}
						else
							$new_data["image_path"] = $current_image_path;
						$response["new_data"] = $new_data;
					}
					else
					{
						if(isset($insert_data["image"]))
							if(!is_dir(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]) && file_exists(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]))
								unlink(REL_IMAGE_UPLOAD_PATH.$insert_data["image"]);
						$response["msg_status"] = "info";
						$response["msg"] = "No changes are made.";
					}
				}
			}
			echo json_encode($response);exit;
		}
		elseif(count($this->input->get()) > 0)
		{
			$this->data["page_title"] = "Update Attraction";
			$id = $this->encrypt->decode(base64_decode($this->input->get("attraction_id")));
			$data["attraction_id"] = $id;
			$data["attraction"] = $this->Homepage_model->get_attraction_by_id($id);
			$data['master_id'] = $this->encrypt->decode(base64_decode($data["attraction"]->master_id));
			if($data["attraction"] === false)
				redirect("homepage/attractions", "refresh");
			
			//pr($data);exit;
			$this->load->view("homepage/attraction/edit", $data);
		}
		else
			redirect("homepage/attractions", "refresh");
	}

		
		/**********************************Attraction End********************************************/


	
	
}