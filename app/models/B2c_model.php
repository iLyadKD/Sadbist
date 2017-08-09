<?php defined("BASEPATH") OR exit("No direct access allowed");
/*
	#################################################################
	This file is coded and updated by MayurSingh@Provab[Akansha-Team].
	################################################################
*/

class B2c_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function is_valid_user($email = "", $id = null)
	{
		$query = "SELECT `a`.* FROM `b2c` AS `a`  WHERE `email_id` = '$email'";
		if(!is_null($id))
			$query = "SELECT * FROM `b2c` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	/*public function is_valid_user($email = "", $id = null)
	{
		$query = "SELECT `a`.*, `email_verified`, `two_step_verification`, 
		`two_step_type` FROM `b2c` AS `a` INNER JOIN `b2c_verification` AS 
		`b` ON `a`.`id` = `b`.`id` WHERE `email_id` = '$email'";
		if(!is_null($id))
			$query = "SELECT * FROM `b2c` WHERE `id` = '$id'";
		$result = $this->db->query($query);
		return $result->num_rows() > 0 ? $result->row() : false;
	}*/
    
    public function get_b2c($id,$lang=DEFAULT_LANG){   
	    $this->db->select('*,c.country_'.$lang.' as country,b.country as country_code');
	    $this->db->from('b2c b');
	    $this->db->join('countries c','c.id=b.country','left');
	    $this->db->where('b.id',$id);
	    $query = $this->db->get();
	   
	    if ( $query->num_rows() > 0 ) { 
	        return $query->row();
	    }
	      return false;
    }

    function  check_user($data) {
        $this->db->select('email_id,firstname,password,id');
        $this->db->from('b2c');
        $this->db->where($data);
        $query = $this->db->get();
        
        if($query->num_rows()>0){ return $query->row(); } else { return FALSE; }
    }

	public function update_b2c($id, $data)
	{
		$set = "";
		foreach ($data as $key => $value)
			$set .= "`".$key."` = '".$value."', ";
		$set = substr_replace($set, "", -2);
		$query = "UPDATE `b2c` SET $set WHERE `id` = '$id'";
		$this->db->query($query);
		
		return $this->db->affected_rows() > 0 ? true : false;
	}

    function  update($id,$data) {
        $this->db->where('id',$id);
        $query =$this->db->update('b2c', $data);
       
        if($query){ return true; } else { return false; }
    }

	function  get_city($id) {
        $this->db->select('*');
        $this->db->from('cities');
        $this->db->where('region',$id);
        $query = $this->db->get();
       
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }

    function  get_state($id) {
        $this->db->select('*');
        $this->db->from('regions');
        $this->db->where('country',$id);
        $query = $this->db->get();
        
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }
    function  get_country($lang = DEFAULT_LANG) {
        $this->db->select('*,country_'.$lang.' AS country');
        $this->db->from('countries');
        $query = $this->db->get();
        if($query->num_rows()>0){ return $query->result(); } else { return FALSE; }
    }

    function  get_support_subject() {
        $this->db->select('*');
	    $this->db->from('support_ticket_subjects ');
	    $query = $this->db->get();
	    if ( $query->num_rows() > 0 ) { 
	        return $query->result();
	    }
	      return false;
    }


    function  get_support_ticket($id,$type) {
        $this->db->select('b.*,c.subject as subject');
	    $this->db->from('support_ticket b');
	    $this->db->join('support_ticket_subjects c','c.id=b.subject','left');
	    $this->db->where("b.user_id_to",$id);
	    $this->db->where("b.user_type_to",$type);
	    $query = $this->db->get();
	   
	    if ( $query->num_rows() > 0 ) { 
	        return $query->result();
	    }
	      return false;
    }

    function  get_send_support_ticket($id,$type) {
        $this->db->select('b.*,c.subject as subject');
	    $this->db->from('support_ticket b');
	    $this->db->join('support_ticket_subjects c','c.id=b.subject','left');
	    $this->db->where("b.user_id_from",$id);
	    $this->db->where("b.user_type_from",$type);
	    $query = $this->db->get();
	   
	    if ( $query->num_rows() > 0 ) { 
	        return $query->result();
	    }
	      return false;
    }

    function  get_closed_support_ticket($id,$type) {
        $this->db->select('b.*,c.subject as subject');
	    $this->db->from('support_ticket b');
	    $this->db->join('support_ticket_subjects c','c.id=b.subject','left');
	    $this->db->where("b.user_id_from",$id);
	    $this->db->or_where('user_id_to', $id); 
	    $this->db->where("b.user_type_from",$type);
	    $this->db->or_where('user_type_to', $id); 
	    $this->db->where("b.status",0);
	   // echo $this->db->last_query();
	    $query = $this->db->get();
	    if ( $query->num_rows() > 0 ) { 
	    	
	        //echo "<pre>";print_r($query->result()); die;
	        return $query->result();
	    }
	      return false;
    }

    function  get_b2c_history($id) {
        $this->db->select('*');
	    $this->db->from('support_ticket_history');
	    $this->db->where("id",$id);
	    $query = $this->db->get();
	    
	    if ( $query->num_rows() > 0 ) { 
	    	return $query->result();
	        //echo "<pre>";print_r($query->result()); die;
	    }
	      return false;
    }


    function  insert_ticket($data) {
        $query =$this->db->insert('support_ticket', $data);
       	$id = $this->db->insert_id();
        if($id){ return $id; } else { return false; }
    }

     function  insert_ticket_history($data) {
        $query =$this->db->insert('support_ticket_history', $data);
       
        if($query){ return true; } else { return false; }
    }

    function  update_ticket($id,$data) {
    	$this->db->where('id',$id);
        $query =$this->db->update('support_ticket', $data);

        if($query){ return true; } else { return false; }
    }



    function delete_support_ticket($id){
    $where = "id = $id";

    if ($this->db->delete('support_ticket', $where)) {
    	//echo $this->db->last_query();
      return true;
    } else {
      return false;
    }
  }
function  save_companion($data) {
	$query = $this->db->insert('b2c_companions', $data);
	if($query){ return 1 ;} else { return 0; }
}
 function  update_companion($id,$data) {
        $this->db->where('id',$id);
        $query =$this->db->update('b2c_companions', $data);
       
        if($query){ return 2; } else { return 0; }
    }

function  companion_list($id) {
	$this->db->select('*');
	$this->db->from('b2c_companions');
	$this->db->where("user_id",$id);
	$this->db->order_by("id",'DESC');
	$query = $this->db->get();
	if ( $query->num_rows() > 0 ) { 
		return $query->result();
	}
	return false;
}

function  companion_by_term($id,$term) {
	$this->db->select('*');
	$this->db->from('b2c_companions');
	$this->db->where("user_id",$id);
	$this->db->like("fname",$term);
	$this->db->order_by("id",'DESC');
	$query = $this->db->get();
	if ( $query->num_rows() > 0 ) { 
		return $query->result();
	}
	return false;
}

function  companion_by_id($id) {
	$this->db->select('*');
	$this->db->from('b2c_companions');
	$this->db->where("id",$id);
	$query = $this->db->get();
	if ( $query->num_rows() > 0 ) { 
		return $query->result()[0];
	}
	return false;
}

function delete_companion($id){
	$query = $this->db->delete('b2c_companions', array('id' => $id)); 
        if($query) { 
          return 1;
        }else {
          return 0;
        }
}

function  count_companion($id) {
	$this->db->select('id');
	$this->db->from('b2c_companions');
	$this->db->where("user_id",$id);
	$query = $this->db->get();
	if ( $query->num_rows() > 0 ) { 
		return $query->num_rows();
	}else{
		return 0;
	}
	
}

function get_b2c_details($email, $id = null){
	$where = "`b2c`.`email_id` = '$email'";
	if(!is_null($id))
		$where = "`b2c`.`id` = '$id'";
	$query = "SELECT `b2c`.* FROM `b2c` WHERE $where";
	$result = $this->db->query($query);
	return $result->num_rows() > 0 ? $result->row() : false;
	
}

function get_companions($id = null){
	$this->db->where('user_id',$id);
	return $this->db->get('b2c_companions')->result();
}
  
}