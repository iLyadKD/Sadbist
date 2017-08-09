<?php
class Package_type_Model extends CI_Model {
	
	function get_package_type(){
		$this->db->select('*')
			       ->from('package_type') ;           
		$query = $this->db->get();
      	if($query->num_rows() > 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	}

  function get_package_type_id($id){
    $this->db->select('*')
             ->from('package_type')
             ->where('type_id',$id);
    $query = $this->db->get();
        if($query->num_rows() > 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }

	function save($data){
		$query = $this->db->insert('package_type', $data); 
      	if($query) { 
        	return $this->db->insert_id();
      	}else {
      		return false;
      	}
	}	

  function update($id,$data){
    $this->db->where('type_id', $id); 
    $query = $this->db->update('package_type', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  } 

  function status($id,$data){
    $this->db->where('type_id', $id);
    $query = $this->db->update('package_type', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function delete($id){
    $query = $this->db->delete('package_type', array('type_id' => $id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }


}