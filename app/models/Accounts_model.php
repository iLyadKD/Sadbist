<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Accounts_Model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	function is_registered_b2c_email($email)
	{
		$result = true;
		$query = "SELECT `id` FROM `b2c` WHERE `email_id` = '$email'";
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
			$result = true;
		else
			$result = false;
		return $result;
	}

	function is_registered_b2b_email($email)
	{
		$result = true;
		$query = "SELECT `id` FROM `b2b` WHERE `email_id` = '$email'";
		$result = $this->db->query($query);
		if($result->num_rows() > 0)
			$result = true;
		else
			$result = false;
		return $result;
	}


	public function create_b2c($userdata)
	{
		 $query = $this->db->insert('b2c',$userdata);


		/*$user_type = $userdata['user_type'];
		$email_id = $userdata['email_id'];
		$salt = $userdata['salt'];
		$password = $userdata['password'];
		$contact_no = $userdata['contact_no'];
		$register_date = $userdata['register_date'];
		$status = $userdata['status'];
		$updated_on = $userdata['updated_on'];
		

		$query = "INSERT INTO b2c (`user_type`, `email_id`, `salt`, `password`, `contact_no`, `register_date`, `status`, `updated_on`) VALUES('$user_type','$email_id','$salt','$password','$contact_no','$register_date','$status','$updated_on')";

		$this->db->query($query);*/

		return $this->db->insert_id();


	}

	public function create_b2b($userdata)
	{
		return $this->db->insert('b2b',$userdata);
	}

	public function get_b2c_by_email($email)
	{
		$this->db->where('email_id', $email);
		$query = $this->db->get('b2c');
		return $query->row();
	}

	public function get_b2b_by_email($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get('b2b');
		return $query->row();
	}

	public function get_user_type_id($user_type)
	{
		$query = "SELECT `user_levels`.`id` FROM `user_levels` INNER JOIN `user_category` ON `user_category`.`id` = `category` AND `name` = '$user_type'";
		$result = $this->db->query($query);
		return $result->row()->id;
	}

	public function add_b2c_verification($user_id)
	{
		$user_type = B2C_USER;
		$update_array = array('user_type'=>$user_type, 'id'=>$user_id, 'email_verified'=>'0', 'two_step_verification'=>'0');
		$this->db->insert('b2c_verification', $update_array);
	}

	public function add_b2b_verification($user_id)
	{
		$user_type = B2C_USER;
		$update_array = array('user_type'=>$user_type, 'id'=>$user_id, 'email'=>'0', 'two_step_verification'=>'0');
		$this->db->insert('b2b_verification', $update_array);
	}

	public function get_b2c_by_id($user_id)
	{
		$this->db->where('id', $user_id);
        $query = $this->db->get('b2c');
		return $query->row();
	}

	public function get_b2b_by_id($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get('b2b');
		return $query->row();
	}

	public function update_b2c_pwd_reset($user_id, $key, $secret)
	{
		$update = array('key' => $key, 'secret' => $secret);
		$this->db->where('id', $user_id);
		$this->db->update('b2c', $update);
	}

	public function update_b2b_pwd_reset($user_id, $key, $secret)
	{
		$update = array('key' => $key, 'secret' => $secret);
		$this->db->where('id', $user_id);
		$this->db->update('b2b', $update);
	}

	public function is_valid_b2c_secret($key, $secret)
	{
		$this->db->where('key', $key);
		$this->db->where('secret', $secret);
		$this->db->where('status', "1");
		return $this->db->get('b2c');
	}

	public function is_valid_b2b_secret($key, $secret)
	{
		$this->db->where('key', $key);
		$this->db->where('secret', $secret);
		$this->db->where('status', "1");
		return $this->db->get('b2b');
	}
}