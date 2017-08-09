<?php
class Hotel_Model extends CI_Model {
	
	function get_hotel($lang = DEFAULT_LANG){
		$this->db->select('th.*,c.country_en as country,`cy`.`city_'.$lang.'` as city')
			    ->from('tour_hotel th')
				->join('countries c','c.id=th.country','left')
				->join('cities cy','cy.id=th.city','left')
				->where('th.status',1)
			    ->order_by('th.hotel_id','desc');
		$query = $this->db->get();
      	if($query->num_rows()> 0 ) { 
        	return $query->result();
      	}else {
      		return false;
      	}
	}

  function get_hotel_id($id,$lang = DEFAULT_LANG){
    $this->db->select('*')
             ->from('tour_hotel')
            ->where('hotel_id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }
  
  function get_room_types($id){
    $this->db->select('room_types')
             ->from('tour_hotel')
            ->where('hotel_id',$id);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
  }


  function get_gallery($id){
    $this->db->select('*')
             ->from('tour_hotel_gallery')
             ->where('hotel_id',$id);
    $query = $this->db->get();
	if($query->num_rows()> 0 ) { 
	  return $query->result();
	}else {
	  return false;
	}
  }

  function save_hotel($data){
    $query = $this->db->insert('tour_hotel', $data); 
	if($query) { 
	  return $this->db->insert_id();
	}else {
	  return false;
	}
  }

  function save_price($data){
    $query = $this->db->insert_batch('tour_hotel_price', $data); 
        if($query) { 
          return $this->db->insert_id();
        }else {
          return false;
        }
  }

  function save_images($data){
    $query = $this->db->insert('tour_hotel_gallery', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }


   function update_hotel($id,$data){
    $this->db->where('hotel_id', $id); 
    $query = $this->db->update('tour_hotel', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }
  
  function save_logo($id,$data){
    $this->db->where('hotel_id', $id); 
	$query = $this->db->update('tour_hotel', $data); 
        if($query) { 
          return true;
        }else {
          
          return false;
        }
  }

  function update_price($data){
    $query = $this->db->update_batch('tour_hotel_price', $data,'id'); 
    // echo $this->db->last_query();
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

  function update_status($id,$data){
    $this->db->where('hotel_id', $id); 
    $query = $this->db->update('tour_hotel', $data); 
        if($query) { 
          return true;
        }else {
          return false;
        }
  }

function delete($id){
    
	$query = $this->db->delete('tour_hotel', array('hotel_id' => $id)); 
        if($query) { 
			$query1 = $this->db->delete('tour_hotel_gallery', array('hotel_id' => $id));
			if($query1){
				return true;
			}else {
				return false;
			}
		}	
		  
}
  
  function find_room_types($id){
	  $this->db->select('th.room_types')
			   ->from('tour_hotel th')
			   ->where('hotel_id',$id);
	  $query = $this->db->get();
	  if($query->num_rows()> 0 ) { 
		return $query->row();
	  }else {
		return false;
	  }
  }
  
function delete_gallery($id){
	$query = $this->db->delete('tour_hotel_gallery', array('gallerry_id' => $id)); 
        if($query) { 
          return true;
        }else {
          return false;
        }
}

function count_tours($id){
	 $sql = "SELECT tour.tour_id FROM tour WHERE FIND_IN_SET($id,tour.hotel_id)";
	 $result = $this->db->query($sql);
	return $result->num_rows() > 0 ? $result->result_array() : false;
	 
}

function involved_price($id,$type){
	 $this->db->select('mp.id')
             ->from('master_price mp')
            ->where('hotel_id',$id)
			->where('room_type_en',$type);
    $query = $this->db->get();
        if($query->num_rows()> 0 ) { 
          return $query->row();
        }else {
          return false;
        }
}


}