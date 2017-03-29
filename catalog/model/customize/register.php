<?php
class ModelCustomizeRegister extends Model {

	public function get_vcb($number){
		$query = $this -> db -> query("
			SELECT * FROM acount_vietcombank where account_id = '".$number."'
			");
		$num =  $query -> row;
		return ($num);
	}
	
	public function insert_vcb($number,$account_name,$bank_name){
		$query = $this -> db -> query("
			INSERT INTO `acount_vietcombank`(`account_id`, `account_name`, `bank_name`) VALUES ('".$number."','".$account_name."','".$bank_name."')
			");
	}


	public function checkExitUserName($username) {
		$query = $this -> db -> query("
			SELECT EXISTS(SELECT 1 FROM " . DB_PREFIX . "customer WHERE username = '" . $username . "')  AS 'exit'
			");

		return $query -> row['exit'];
	}

	public function checkExitUserNameForToken($username, $idUserNameLogin) {
		$query = $this -> db -> query("
			SELECT EXISTS(SELECT 1 FROM " . DB_PREFIX . "customer WHERE customer_id <> '". $idUserNameLogin ."' AND  username = '" . $username . "')  AS 'exit'
			");

		return $query -> row['exit'];
	}

	public function checkExitEmail($email) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE email = '" . $this -> db -> escape($email) . "'
			");

		return $query -> row['number'];
	}

	public function checkExitPhone($telephone) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE telephone = '" . $this -> db -> escape($telephone) . "'
			");

		return $query -> row['number'];
	}

	public function checkExitCMND($cmnd) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE account_number = '" . $this -> db -> escape($cmnd) . "'
			");

		return $query -> row['number'];
	}
	public function checkExitCMNDS($cmnd) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE cmnd = '" . $this -> db -> escape($cmnd) . "'
			");

		return $query -> row['number'];
	}

	public function getId_by_username($username) {
		$query = $this -> db -> query("
			SELECT customer_id FROM " . DB_PREFIX . "customer WHERE customer_code = '" . $this -> db -> escape($username) . "'
			");

		return $query -> row['customer_id'];
	}

	public function addCustomer_custom($data){

		$data['p_node'] = $this->getId_by_username($data['node']);

		//$data['p_node'] = $this -> customer -> getId();

		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($data['p_node']) . "',
			customer_code = '".hexdec(crc32(md5($data['username'])))."',
			email = '" . $this -> db -> escape($data['email']) . "', 
			username = '" . $this -> db -> escape($data['username']) . "', 
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '1', 
			
			cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
			country_id = '". $this -> db -> escape($data['country_id']) ."',
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['transaction_password'])))) . "',
			date_added = NOW(),
			check_Newuser = 1,
			language = 'english'
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

		if($data['postion'] === 'right'){
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `right` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_binary'] . "'");
		}else{
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `left` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_binary'] . "'");
		}
		return $customer_id;
	}

	public function update_customer_code($customer_id){
		# code...
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer 
			SET customer_code = '".hexdec( crc32($customer_id) )."'
			WHERE customer_id = ".$customer_id."
			
		");
	}

	public function addCustomer($data) {
		

		$data['p_node'] = $this -> customer -> getId();
		$email = $data['email'];
		$email_full = explode("@", $email);
		$email = str_replace(".","",$email_full[0]);
		$email_finish = $email."@".$email_full[1];

		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($data['p_node']) . "', 
			email = '" . $this -> db -> escape($email_finish) . "', 
			username = '" . $this -> db -> escape($data['username']) . "', 
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '1',
			bank_name = '".$this -> db -> escape($data['bank_name']) ."', 
			cmnd = '" . $this -> db -> escape($data['cmnds']) . "', 
			account_number = '" . $this -> db -> escape($data['account_number']) . "', 
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password2'])))) . "',
			date_added = NOW(),
			check_Newuser = 0,
			language = 'vietnamese',
			account_holder = '".$this -> db -> escape($data['account_holder'])."'
		");

		$customer_id = $this -> db -> getLastId();



		// p_binary = '" . $data['p_node'] . "',
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET 
			customer_id = '" . (int)$customer_id . "',
			level = '1', 
			p_binary = '" . $data['p_node'] . "', 
			p_node = '" . $data['p_node'] . "', 
			date_added = NOW()");

		return $customer_id;

	}

	public function getCustomer_ml($customer_id) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer_ml  WHERE customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function getTotalChild($customer_id) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ml WHERE p_binary = '" . (int)$customer_id . "' AND status <> 0");
		return intval($query -> row['total']);
	}

	function reduce_p_binary($p_node, $customer_id) {

		$query = $this -> db -> query("SELECT customer_id FROM " . DB_PREFIX . "customer_ml 
			WHERE p_node = '" . (int)$p_node . " ' 
			AND customer_id <> '" . $customer_id . "'
			AND status <> 0
			OR p_binary =  '" . $p_node . "'
			ORDER BY id");
		$rows = $query -> rows;

		foreach ($rows as $key => $value) {

			if ($this -> getTotalChild($value['customer_id']) < 2) {
				$query = null;
				$rows = null;
				return $value['customer_id'];
				break;
			}
			// else{
			// 	$this -> reduce_p_binary($value['customer_id'], $customer_id);
			// }
		}
	}
	public function addCustomerByToken($data) {
		$date_added = date('Y-m-d H:i:s');
		$data['p_node'] = $this->getId_by_username($data['node']);
		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($data['p_node']) . "', 
			email = '" . $this -> db -> escape($data['email']) . "', 
			username = '" . $this -> db -> escape($data['username']) . "', 
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '1',
			cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
			account_number = '" . $this -> db -> escape($data['account_number']) . "', 
			branch_bank = '" . $this -> db -> escape($data['branch_bank']) . "', 
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password2'])))) . "',
			date_added = '".$date_added."',
			check_Newuser = 0,
			language = 'vietnamese',
			account_holder = '".$this -> db -> escape($data['account_holder'])."',
			bank_name = '".$this -> db -> escape($data['bank_name'])."'
		");
		$customer_id = $this -> db -> getLastId();
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET 
			customer_id = '" . (int)$customer_id . "',
			level = '1', 
			p_binary = 0, 
			p_node = 0, 
			date_added = '".$date_added."'");

		return $customer_id;
	}
}
