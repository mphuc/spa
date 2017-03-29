<?php
class ModelSaleRegister extends Model {
	public function create_user($username,$full_name,$bank_name_account,$bank_name,$telephone,$email,$password,$address){
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET username = '" .$username. "',account_holder = '".$full_name."', account_bank = '".$bank_name_account."',bank_name = '".$bank_name."',telephone = '".$telephone."',email = '".$email."',password = '".MD5($password)."',address = '".$address."',p_node = ''");
	}
	public function addCustomer_custom($data){
		
		
		$data['p_node'] = $this->getId_by_username($data['node']);

		//$data['p_node'] = $this -> session -> data['customer_id'];

		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($data['p_node']) . "',
			customer_code = '".hexdec(crc32(md5($data['username'])))."',
			email = '" . $this -> db -> escape($data['email']) . "', 
			firstname = '" . $this -> db -> escape($data['firstname']) . "', 
			address_cmnd = '" . $this -> db -> escape($data['address']) . "', 
			
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '1', 
			cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
			country_id = '". $this -> db -> escape($data['country_id']) ."',
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['transaction_password'])))) . "',
			date_added = NOW(),
			date_register_tree = DATE_ADD(NOW(),INTERVAL + 45 MINUTE),
			check_Newuser = 1,
			language = 'vietnamese'
		");

		$customer_id = $this -> db -> getLastId();

		// p_binary = '" . $data['p_node'] . "',

		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET 
			customer_id = '" . (int)$customer_id . "',
			customer_code = '".hexdec(crc32(md5($data['email'])))."',
			level = '1', 
			p_binary = '" . $data['p_binary'] . "', 
			p_node = '" . $data['p_node'] . "',
			date_added = NOW()");

		//update p_binary

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET `username` = '" .hexdec(crc32(md5($customer_id))). "' WHERE customer_id = '" .$customer_id. "'");
		if($data['postion'] === 'right'){
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `right` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_binary'] . "'");
		}else{
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `left` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_binary'] . "'");
		}
		return $customer_id;
	}
	public function getId_by_username($username) {
		$query = $this -> db -> query("
			SELECT customer_id FROM " . DB_PREFIX . "customer WHERE customer_code = '" . $this -> db -> escape($username) . "'
			");

		return $query -> row['customer_id'];
	}
}