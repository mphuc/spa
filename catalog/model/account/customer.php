<?php
class ModelAccountCustomer extends Model {

	public function update_login($customer_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
				date_login_update = NOW()
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}

	public function addCustomer($data) {
		$this -> event -> trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this -> config -> get('config_customer_group_display')) && in_array($data['customer_group_id'], $this -> config -> get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this -> config -> get('config_customer_group_id');
		}

		$this -> load -> model('account/customer_group');

		$customer_group_info = $this -> model_account_customer_group -> getCustomerGroup($customer_group_id);

		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this -> config -> get('config_store_id') . "', firstname = '" . $this -> db -> escape($data['firstname']) . "', lastname = '" . $this -> db -> escape($data['lastname']) . "', email = '" . $this -> db -> escape($data['email']) . "', telephone = REPLACE('" . $this -> db -> escape($data['telephone']) . "', ' ', ''), cmnd = '" . $this -> db -> escape($data['cmnd']) . "', account_bank = '" . $this -> db -> escape($data['account_bank']) . "', address_bank = '" . $this -> db -> escape($data['address_bank']) . "', p_node = '" . $this -> db -> escape($data['p_node']) . "', custom_field = '" . $this -> db -> escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this -> db -> escape($this -> request -> server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this -> db -> getLastId();


		$this -> event -> trigger('post.customer.add', $customer_id);

		return $customer_id;
	}
	public function getPDActive($customer_id){
		$query = $this -> db -> query("
			SELECT * 
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE status = 1 OR status = 2 AND customer_id = ".$customer_id."
		");
		return $query -> row;
	}
	public function getTotalCustomerFloor($arrId){
		$query = $this -> db -> query("
			SELECT count(*) AS number 
			FROM " . DB_PREFIX . "customer c JOIN " . DB_PREFIX . "customer_ml ml
			ON c.customer_id = ml.customer_id
			WHERE c.customer_id IN (".$arrId.")
		");

		return $query -> row;
	}
	public function getParrent($customer_id){
		$query = $this->db->query("SELECT username
			FROM " . DB_PREFIX . "customer WHERE customer_id = ".$customer_id."");
		
		return $query->row['username'];
	}
	public function getPD_active($iod_customer){
		
		$query = $this -> db -> query("
			SELECT max(filled) as filled
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."' AND status = 1
		");
		return $query -> row['filled'];
	}
	public function checkPD($customer_id){
		$query = $this->db->query("SELECT *
			FROM " . DB_PREFIX . "customer_provide_donation WHERE status IN (1,2) AND customer_id = ".$customer_id."");
		return $query -> rows;
	}
public function getCustomerFloor($arrId, $limit, $offset){
		$query = $this->db->query("SELECT c.customer_id, c.username AS name,c.firstname,c.account_holder,c.account_number,c.ping, c.wallet, c.telephone,c.email,c.country_id, c.account_holder as fullname, c.email, 
			c.telephone, ml.level, c.p_node
			FROM " . DB_PREFIX . "customer c JOIN " . DB_PREFIX . "customer_ml ml
			ON c.customer_id = ml.customer_id
			WHERE c.customer_id IN (".$arrId.") LIMIT ".$limit." OFFSET ".$offset."");
		return $query -> rows;
	}

	public function update_avatar($customer_id, $image){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
				img_profile = '".$image."'
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}
	

	public function getInfoUsers_binary($id_id){

		$query = $this->db->query("select u.*,ml.level, l.name_vn as level_member from ". DB_PREFIX . "customer_ml as ml Left Join " . DB_PREFIX . "customer as u ON ml.customer_id = u.customer_id Left Join " . DB_PREFIX . "member_level as l ON l.id = ml.level Where ml.customer_id = " . $id_id);
		$return  = $query->row;
		return $return;
	}

	public function saveTranstionHistory($customer_id, $wallet, $text_amount, $system_decsription,$type){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."customer_transaction_history SET
			customer_id = '".$customer_id."',
			wallet = '".$wallet."',
			text_amount = '".$text_amount."',
			system_decsription = '".$system_decsription."',
			date_added = '".$date_added."',
			type = '".$type."'
		");
		return $query;
	}

	public function getGdFromTransferList($gd_id){
		$query = $this -> db -> query("
			SELECT ctl.* , c.username
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.gd_id_customer = c.customer_id
			WHERE ctl.gd_id = '".$this->db->escape($gd_id)."'
		");
		return $query -> rows;
	}
	public function updateCheck_R_WalletPD($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				check_R_Wallet = 1
				WHERE id = '".$pd_id."'
			");
		return $query;
	}

	public function getGDTranferByID($transacion_id){

		$query = $this -> db -> query("
			SELECT c.*, ctl.*
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.pd_id_customer = c.customer_id
			WHERE ctl.id = '".$this->db->escape($transacion_id)."' AND gd_id_customer = ".$this -> session -> data['customer_id']."
		");
		return $query -> row;
	}

	public function getPNode($customer_id){
		$query = $this -> db -> query("
			SELECT * FROM sm_customer_provide_donation pd JOIN sm_customer_get_donation gd on pd.customer_id = gd.customer_id WHERE pd.customer_id in
			(SELECT customer_id FROM sm_customer WHERE p_node = ".$customer_id.") AND pd.status = 2 AND gd.status = 2 GROUP BY pd.customer_id");
		return $query -> rows;
	}

	public function getPDByTranferID($id){
		$query = $this -> db -> query("
			SELECT pd_id, gd_id
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE id = '".$id."'
		");
		return $query -> row;
	}
	public function countStatusPDTransferList($pd_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_transfer_list
			WHERE pd_id = '". $pd_id ."' AND pd_satatus = 0
			");
		return $query -> row;
	}
	public function countStatusGDTransferList($pd_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_transfer_list
			WHERE gd_id = '". $pd_id ."' AND pd_satatus = 0
			");
		return $query -> row;
	}
	public function updateStusPD($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = 2
				WHERE id = '".$pd_id."'
			");
		return $query;
	}
	public function updateStusPDActive($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = 1
				WHERE id = '".$pd_id."'
			");
		return $query;
	}
	public function updateStusGDActive($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				status = 1
				WHERE id = '".$pd_id."'
			");
		return $query;
	}

	public function updateStusGD($gd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				status = 1
				WHERE id = '".$gd_id."'
			");
		return $query;
	}
	public function getTransferList_All($transfer_code){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE id = '".$transfer_code."'
		");
		return $query -> row;
	}
	public function updateStatusPDTransferList($transferID, $img){

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 1,
				
				image ='".$img."'
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}

	public function getPDTranferByID($transacion_id){
		$query = $this -> db -> query("
			SELECT c.*, ctl.*
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.gd_id_customer = c.customer_id
			
			WHERE ctl.id = '".$this->db->escape($transacion_id)."'
		");
		return $query -> row;
	}
	public function getCountryByID($id){
		$query = $this -> db -> query("
			SELECT name
			FROM ". DB_PREFIX ."country
			WHERE country_id = '".$this->db->escape($id)."'
		");
		return $query -> row;
	}

	public function getPhone($customer_id){
		$query = $this->db->query("SELECT telephone
			FROM " . DB_PREFIX . "customer WHERE customer_id = ".$customer_id."");
		return $query -> row['telephone'];
	}
	public function getAccount_holder($customer_id){
		
		$query = $this->db->query("SELECT account_holder
			FROM " . DB_PREFIX . "customer WHERE customer_id = ".$customer_id."");
		return $query -> row['account_holder'];
	}
	public function getPdFromTransferList($pd_id){

		$query = $this -> db -> query("
			SELECT ctl.* , c.username, c.wallet
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.gd_id_customer = c.customer_id
			WHERE ctl.pd_id = '".$this->db->escape($pd_id)."'
		");
		return $query -> rows;
	}

	public function getGDByCustomerIDAndToken($customer_id, $token){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_get_donation
			WHERE customer_id = '". $customer_id ."' AND id = '".$token."'
			");
		return $query -> row;
	}
	public function getPD($iod_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."'
		");
		return $query -> rows;
	}
	public function get_PD_by_customer_id($id_customer){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation 
			WHERE customer_id = '".$id_customer."' AND status = 1 ORDER BY date_added DESC
			
		");

		return $query -> row;
	}
	public function getallcommision($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT pd.*, c.username
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			JOIN ". DB_PREFIX ."customer AS c
			ON pd.customer_id = c.customer_id
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND pd.wallet = 'R-wallet'
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getallcommision_system(){

		$query = $this -> db -> query("
			SELECT pd.*, c.username
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			JOIN ". DB_PREFIX ."customer AS c
			ON pd.customer_id = c.customer_id
			ORDER BY pd.date_added DESC
			LIMIT 20
		");

		return $query -> rows;
	}
	public function getallcommision_history($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT pd.*, c.username
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			JOIN ". DB_PREFIX ."customer AS c
			ON pd.customer_id = c.customer_id
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND pd.wallet = 'C-wallet'
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}
	public function getPDByCustomerIDAndToken($customer_id, $token){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_provide_donation
			WHERE customer_id = '". $customer_id ."' AND id = '".$token."'
			");
		return $query -> row;
	}
	public function getPDConfirm($id){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$this->db->escape($id)."'
		");
		return $query -> row;
	}
	public function createPD($amount, $max_profit){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '+ 30 day' , strtotime ($date_added));
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET
			customer_id = '".$this -> session -> data['customer_id']."',
			date_added = '".$date_added."',
			filled = '".$amount."',
			date_finish = '".$date_finish."',
			date_finish_forAdmin = '".$date_finish."',
			status = 0,
			check_R_Wallet = 1
		");
		//update max_profit and pd_number
		$pd_id = $this->db->getLastId();

		//$max_profit = (float)($amount * $this->config->get('config_pd_profit')) / 100;

		$pd_number = hexdec( crc32($pd_id) );
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				max_profit = '".$max_profit."',
				pd_number = '".$pd_number."'
				WHERE id = '".$pd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['pd_number'] = $pd_number;
		$data['pd_id'] = $pd_id;
		return $data;
	}

	public function createPDCustom($customer_id,$amount, $max_profit){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET
			customer_id = '".$customer_id."',
			date_added = NOW(),
			filled = '".$amount."',
			date_finish ='0000-00-00 00:00:00',
			date_finish_forAdmin = DATE_ADD(NOW(),INTERVAL +10 DAY),
			status = 0
		");
		//update max_profit and pd_number
		$pd_id = $this->db->getLastId();

		//$max_profit = (float)($amount * $this->config->get('config_pd_profit')) / 100;

		$pd_number = hexdec( crc32($pd_id) );
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				max_profit = '".$max_profit."',
				pd_number = '".$pd_number."'
				WHERE id = '".$pd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['pd_number'] = $pd_number;
		$data['pd_id'] = $pd_id;
		return $data;
	}


	public function insertR_Wallet($id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_r_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = '0'
		");
		return $query;
	}
	public function insertR_WalletR($id_customer,$amount){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_r_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}
	public function insert_block_id($id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_block_id SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			date = NOW()
		");
		return $query;
	}

	public function insertC_Wallet($id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_c_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = '0'
		");
		return $query;
	}

	public function checkR_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_r_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function checkHH_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_hh_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function insertHH_Wallet($amount, $id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_hh_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}

	public function checkKM_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_km_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function insertKM_Wallet($amount, $id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_km_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}

	public function checkLN_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_ln_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function insertLN_Wallet($amount, $id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_ln_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}

	public function checkCH_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_ch_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function insertCH_Wallet($amount, $id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_ch_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}

	public function checkDT_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_dt_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function insertDT_Wallet($amount, $id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_dt_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}



	public function get_block_id($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_block_id
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function get_all_block_id_gd($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_block_id_gd
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function get_block_id_gd($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) as total
			FROM  ".DB_PREFIX."customer_block_id_gd
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND status = 1
		");
		return $query -> row['total'];
	}
	public function get_block_id_gd_total($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) as total
			FROM  ".DB_PREFIX."customer_block_id_gd
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND status = 0
		");
		return $query -> row['total'];
	}

	public function updateR_Wallet($id_customer, $amount){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_r_wallet SET
			amount = '" . $this -> db -> escape((float)$amount) . "'
			WHERE customer_id = '" . (int)$id_customer . "'");

		return $query;
	}

	public function checkC_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_c_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getTotalPD($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}
	public function getTotalcommission($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND wallet LIKE 'R-wallet'
		");

		return $query -> row;
	}
	
	public function getTotalcommission_history($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND wallet LIKE 'C-wallet'
		");

		return $query -> row;
	}

	public function getTableCustomerMLByUsername($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_ml
			WHERE customer_id = '".$customer_id."'
		");

		return $query -> row;
	}


	public function update_pd_binary($left = true, $customer_id, $total_pd){
		if($left){
			$query = $this -> db -> query("
				UPDATE ".DB_PREFIX."customer
				SET total_pd_left = total_pd_left + ".$total_pd."
				WHERE customer_id = '".$customer_id."'
			");
		}else{
			$query = $this -> db -> query("
				UPDATE ".DB_PREFIX."customer
				SET total_pd_right = total_pd_right + ".$total_pd."
				WHERE customer_id = '".$customer_id."'
			");
		}
		return $query;
	}

	public function getR_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT amount
			FROM  ".DB_PREFIX."customer_r_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getC_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_c_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getLikeMember($name = '', $idUserLogin){
		if($name === ''){
			$customer_query = $this->db->query("
				SELECT username AS name , customer_id AS code FROM " . DB_PREFIX . "customer WHERE customer_id <> ". $this->db->escape($idUserLogin) ."
				LIMIT 8");
			return $customer_query -> rows;
		}
		if($name !== ''){
			$customer_query = $this->db->query("
				SELECT username AS name , customer_id AS code FROM " . DB_PREFIX . "customer
				WHERE customer_id <> ". $idUserLogin ." AND username Like '%".$this->db->escape($name)."%'
				LIMIT 8");
			return $customer_query -> rows;
		}
	}

	public function getPasswdTransaction($password=''){
		if($password !== ''){
			$customer_query = $this->db->query("
				SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer
				WHERE customer_id = '". $this -> session -> data['customer_id'] ."' AND transaction_password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "')))))");
			return $customer_query -> row;
		}
	}

	public function countGdOfDay($month, $year, $day){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE customer_id = '".$this -> session -> data['customer_id']."'
				  AND MONTH(date_added) = '".$month."'
				  AND YEAR(date_added) = '".$year."'
				  AND DAY(date_added) = '".$day."'
		");

		return $query -> row;
	}

	public function update_C_Wallet($amount , $customer_id, $add = false){
		if(!$add){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_c_wallet SET
				amount = amount - ".floatval($amount).",
				date_add_rut = NOW(),
				count_rut = count_rut + 1
				WHERE customer_id = '".$customer_id."'
			");

		}else{
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_c_wallet SET
				amount = amount + ".floatval($amount)."
				WHERE customer_id = '".$customer_id."'
			");
		}

		return $query === true ? true : false;
	}

	public function update_R_Wallet($amount , $customer_id, $add = false){
		if(!$add){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_r_wallet SET
				amount = amount - ".floatval($amount)."
				WHERE customer_id = '".$customer_id."'
			");
		}
		return $query === true ? true : false;
	}
	public function updateRWallet($amount , $customer_id){
			
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_r_wallet SET
				amount = amount - ".floatval($amount)."
				WHERE customer_id = '".$customer_id."'
			");
			
		return $query === true ? true : false;
	}
	public function createGD($amount){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '+168 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;	

		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = '".$this -> session -> data['customer_id']."',
			date_added = '".$date_added."',
			date_finish = '".$date_finish."',
			amount = '".$amount."',
			status = 0
		");

		$gd_id = $this->db->getLastId();

		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				gd_number = '".$gd_number."'
				WHERE id = '".$gd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		$data['gd_id'] = $gd_id;
		return $data;
	}

	public function editPasswordCustomForEmail($data, $password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $data['customer_id'];
		$salt = $data['salt'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}
	public function getCustomLike($name, $customer_id) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT username AS name, account_holder FROM ". DB_PREFIX ."customer
			WHERE username Like '%".$this->db->escape($name)."%' AND customer_id <> ".$customer_id." 
			LIMIT 12
		") ;
		$array_id = $query -> rows;

		return $array_id;
	}
	public function checkUserName($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.customer_id AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node = ". $id_user ."");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['name'];
			$listId .= $this -> checkUserName($item['code']);
		}
		return $listId;
	}


	public function getTotalGD($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_get_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}
	public function countPDINProvide(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = '".$this->session->data['customer_id']."'
		");
		return $query -> rows;
	}
	public function checkChuky($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND cycle = 0 AND check_Newuser = 2
		");

		return $query -> row;
	}

	public function getGDById($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT A.*, B.username
			FROM  ".DB_PREFIX."customer_get_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id 
			WHERE A.customer_id = '".$this -> db -> escape($id_customer)."'
			ORDER BY A.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function checkpasswd($password=''){
		if($password !== ''){
			$customer_query = $this->db->query("
				SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer
				WHERE customer_id = '". $this -> session -> data['customer_id'] ."' AND password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) AND status <> 0 ");
			return $customer_query -> row;
		}
	}

	public function updatePin($id_customer, $pin){

		$this -> event -> trigger('pre.customer.edit', $data);
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = " . $this -> db -> escape((int)$pin) . "
			WHERE customer_id = '" . (int)$id_customer . "'");

		$this -> event -> trigger('post.customer.edit', $id_customer);

	}
	public function updatePin_sub($id_customer, $pin){

		$this -> event -> trigger('pre.customer.edit', $data);
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = ping - " . $this -> db -> escape((int)$pin) . "
			WHERE customer_id = '" . (int)$id_customer . "'");

		$this -> event -> trigger('post.customer.edit', $id_customer);

	}

	public function updatePin_rutping($id_customer, $pin){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = ping - " . $this -> db -> escape((int)$pin) . "

			WHERE customer_id = '" . (int)$id_customer . "'");

	}

	public function updatePinCustom($id_customer, $pin){

		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = ping - " . $pin. "
			WHERE customer_id = '" . (int)$id_customer . "'");


	}

	public function getDateAuto($customer_id){
		$query = $this -> db -> query("
			SELECT  DATE_ADD(date_auto,INTERVAL +11 HOUR) AS date_auto
			FROM " . DB_PREFIX . "customer
			WHERE customer_id = '". $customer_id."'
		");
		return $query -> row;
	}

	public function updateNew_user($id_customer){

		$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				check_Newuser = 1, 
				cycle = 1,
				date_auto = DATE_ADD(NOW(),INTERVAL 5 DAY)
				WHERE customer_id = '" . (int)$id_customer. "'");
		return $query;
	}

	public function updateDateAuto($id_customer){

		$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				date_auto = DATE_ADD(NOW(),INTERVAL 5 DAY)
				WHERE customer_id = '" . (int)$id_customer. "'");
		return $query;
	}



	public function updateStatus($id_customer,  $status){
		if($id_customer && $status){
			$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				status = '" . $this -> db -> escape((int)$status) . "'
				WHERE customer_id = '" . (int)$id_customer. "'");
			if($query){
				$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer_ml SET
				status = '" . $this -> db -> escape((int)$status) . "'
				WHERE customer_id = '" . (int)$id_customer. "'");
			}else{
				$query = false;
			}

			return $query;
		}
	}

	public function getLevel($customer_id, $level){
		$query =  $this -> db -> query("
			SELECT *
					FROM " . DB_PREFIX . "customer_ml
					WHERE customer_id
					IN ( SELECT customer_id FROM " . DB_PREFIX . "customer WHERE p_node = ".$customer_id." )
					AND level = ".$level."
					GROUP BY customer_id");
		return $query -> rows;
	}
	public function getLevel_by_customerid($customer_id){
		$query =  $this -> db -> query("
			SELECT level
			FROM " . DB_PREFIX . "customer_ml
			WHERE customer_id = '".$customer_id."'");
		return $query -> row;
	}

	public function updateLevel($customer_id, $level){
		$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer_ml SET
				level = ".$level."
				WHERE customer_id = '" . (int)$customer_id. "'");
		return $query;
	}

	public function updateCheckNEwuser($id_customer){
		if($id_customer){
			$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				check_Newuser = 0
				WHERE customer_id = '" . (int)$id_customer. "'");
			return $query;
		}
	}

	public function updateCheckNEwuser_Custom($id_customer, $check_Newuser){
		if($id_customer){
			$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				check_Newuser = ".$check_Newuser."
				WHERE customer_id = '" . (int)$id_customer. "'");
			return $query;
		}
	}



	public function saveHistoryPin($id_customer, $amount, $user_description, $type , $system_description){
		$date_added= date('Y-m-d H:i:s');
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "ping_history SET
			id_customer = '" . $this -> db -> escape($id_customer) . "',
			amount = '" . $this -> db -> escape( $amount ) . "',
			date_added = '".$date_added."',
			user_description = '" .$this -> db -> escape($user_description). "',
			type = '" .$this -> db -> escape($type). "',
			system_description = '" .$this -> db -> escape($system_description). "'
		");
		return $this -> db -> getLastId();
	}

	public function getTotalRefferalByID($id_customer){

		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM ".DB_PREFIX."customer_ml
			WHERE p_node =  '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}

	public function getRefferalByID($id_customer ,$limit, $offset){
		$query = $this -> db -> query("
			SELECT c.email , c.username,c.telephone,c.cmnd,c.wallet,c.country_id, c.customer_id, ml.level, c.date_added
			FROM ".DB_PREFIX."customer_ml AS ml
			JOIN ". DB_PREFIX ."customer AS c
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node =  '".$this -> db -> escape($id_customer)."'
			ORDER BY ml.level DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotalTokenHistory($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."ping_history
			WHERE id_customer = ".$this -> db -> escape($id_customer)." AND amount <> '- 0' AND amount <> '+ 0'
		");

		return $query -> row;
	}

	public function getTokenHistoryById($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."ping_history
			WHERE id_customer = ".$this -> db -> escape($id_customer)." AND amount <> '- 0' AND amount <> '+ 0'
			ORDER BY date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."

		");

		return $query -> rows;
	}

	public function editCustomerWallet($wallet) {

		$data['wallet'] = $wallet;
		$this -> event -> trigger('pre.customer.edit', $data);
		$customer_id = $this -> session -> data['customer_id'];
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET wallet = '". $wallet ."' WHERE customer_id = '" . (int)$customer_id . "'");
		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editCustomerBanks($data) {

		$data_arr = $data;
		$this -> event -> trigger('pre.customer.edit', $data_arr);
		$customer_id = $this -> session -> data['customer_id'];
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET account_holder = '". $data_arr['account_holder'] ."',bank_name = '". $data_arr['bank_name'] ."',account_number = '". $data_arr['account_number'] ."',branch_bank = '". $data_arr['branch_bank'] ."' WHERE customer_id = '" . (int)$customer_id . "'");
		$this -> event -> trigger('post.customer.edit', $customer_id);
	}
	public function editCustomerProfile($data) {

		$data_arr = $data;
		$this -> event -> trigger('pre.customer.edit', $data_arr);
		$customer_id = $this -> session -> data['customer_id'];
		// $this -> db -> query("UPDATE " . DB_PREFIX . "customer SET username = '". $data_arr['username'] ."',email = '". $data_arr['email'] ."',
		// 	telephone = '". $data_arr['telephone'] ."' WHERE customer_id = '" . (int)$customer_id . "'");
		
		if (isset($data['address_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$this -> db -> escape($data['address_id']) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}
		if (isset($data['country_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET country_id = '" . (int)$this -> db -> escape($data['country_id']) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$this -> event -> trigger('post.customer.edit', $customer_id);
	}
	// public function editCustomerProfile($data) {

	// 	$data_arr = $data;
	// 	$this -> event -> trigger('pre.customer.edit', $data_arr);
	// 	$customer_id = $this -> session -> data['customer_id'];
	// 	$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET address_id = '". $data_arr['address_id'] ."',country_id = '". $data_arr['country_id'] ."', username = '". $data_arr['username'] ."',email = '". $data_arr['email'] ."',telephone = '". $data_arr['telephone'] ."' WHERE customer_id = '" . (int)$customer_id . "'");
	// 	$this -> event -> trigger('post.customer.edit', $customer_id);
	// }

	public function editCustomer($data) {

		$this -> event -> trigger('pre.customer.edit', $data);

		$customer_id = $this -> session -> data['customer_id'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this -> db -> escape($data['firstname']) . "', lastname = '" . $this -> db -> escape($data['lastname']) . "', email = '" . $this -> db -> escape($data['email']) . "', telephone = '" . $this -> db -> escape($data['telephone']) . "', account_bank = '" . $this -> db -> escape($data['account_bank']) . "', address_bank = '" . $this -> db -> escape($data['address_bank']) . "', custom_field = '" . $this -> db -> escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editCustomerCusotm($data) {


		$this -> event -> trigger('pre.customer.edit', $data);

		$customer_id = $this -> session -> data['customer_id'];
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			email = '" . $this -> db -> escape($data['email']) . "',
			telephone = '" . $this -> db -> escape($data['telephone']) . "'
			WHERE customer_id = '" . (int)$customer_id . "'");

		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editPasswordCustom($password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $this -> session -> data['customer_id'];

		$salt = $this -> getCustomer($customer_id);
		$salt = $salt['salt'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function editPasswordTransactionCustom($password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $this -> session -> data['customer_id'];

		$salt = $this -> getCustomer($customer_id);
		$salt = $salt['salt'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function editPassword($email, $password) {
		$this -> event -> trigger('pre.customer.edit.password');

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this -> event -> trigger('pre.customer.edit.newsletter');

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this -> session -> data['customer_id'] . "'");

		$this -> event -> trigger('post.customer.edit.newsletter');
	}

	public function getCustomer($customer_id) {
		$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}
	
	public function getCustomerPDForPD($p_node) {
		$query = $this -> db -> query("
			SELECT c.customer_id
			FROM " . DB_PREFIX . "customer c
			JOIN sm
			WHERE c.p_node = '" . (int)$p_node . "'"
		);
		return $query -> row;
	}

	public function getTotalHistory($customer_id){
		$query = $this -> db -> query("
			SELECT count(*) AS number
			FROM ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".intval($customer_id)."'
		");

		return $query -> row;
	}

	public function getTransctionHistory($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
			ORDER BY date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getCustomerCustom($customer_id) {
		$query = $this -> db -> query("SELECT c.customer_id, c.username, c.telephone, c.customer_id , ml.level, c.p_node, c.cycle, c.status
			FROM ". DB_PREFIX ."customer AS c
				JOIN ". DB_PREFIX ."customer_ml AS ml
				ON ml.customer_id = c.customer_id
				WHERE c.customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function getCustomerBank($customer_id) {
		$query = $this -> db -> query("SELECT account_holder, bank_name, account_number,branch_bank   FROM ". DB_PREFIX ."customer WHERE customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function editPasswordTransactionCustomForEmail($data, $password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $data['customer_id'];
		$salt = $data['salt'];
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function getCustomerCustomFormSetting($customer_id) {
		$query = $this -> db -> query("SELECT c.username, c.telephone , c.email , c.wallet , ml.level FROM ". DB_PREFIX ."customer AS c
				JOIN ". DB_PREFIX ."customer_ml AS ml
				ON ml.customer_id = c.customer_id
				WHERE c.customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function getUserOff($listIdChild) {
		if ($listIdChild != '') {
			$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 0");
			return $query -> rows;
		}
		return array();
	}

	public function getUserNotHP($listIdChild) {
		if ($listIdChild != '') {
			$date = strtotime(date('Y-m-d'));
			$month = date('m', $date);
			$year = date('Y', $date);
			$arrNotHP = array();
			$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 1");
			$arrUser = $query -> rows;
			foreach ($arrUser as $user) {
				$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "profit WHERE  user_id = " . $user['customer_id'] . " and type_profit = 1 and year = '" . $year . "' AND month = '" . $month . "'");
				if (!$query -> row) {
					array_push($arrNotHP, $user);
				}
			}
			return $arrNotHP;
		} else {
			return array();
		}
	}

	public function getListChild($id_package) {
		$query = $this -> db -> query("SELECT cm.*,c.username,c.telephone,c.status AS status_cus,c.firstname,c.cmnd,CONCAT(c.firstname, ' ', c.lastname) as name_customer,ml.name_vn as package_vn FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id)  WHERE cm.p_node = '" . (int)$id_package . "'");

		return $query -> rows;
	}

	public function getListChildCustom($id_package) {
		$query = $this -> db -> query("
				SELECT cm.level, c.username, c.telephone , c.customer_id
				FROM ". DB_PREFIX ."customer_ml cm LEFT JOIN ". DB_PREFIX ."customer c ON (c.customer_id = cm.customer_id)
				WHERE cm.p_node = '2'
			");

		return $query -> rows;
	}

	public function getListChildNotPackage($id_user) {
		$id_user = $id_user * (-1);
		$query = $this -> db -> query("SELECT cm.*,c.username,c.firstname,c.cmnd,CONCAT(c.firstname, ' ', c.lastname) as name_customer,ml.name_vn as package_vn FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id)  WHERE cm.p_node = '" . $id_user . "'");

		return $query -> rows;
	}


	public function getCustomerByEmail($email) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		return $query -> row;
	}

	public function getCustomerByUsername($username) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(username) = '" . $this -> db -> escape(utf8_strtolower($username)) . "'");

		return $query -> row;
	}

	public function getCustomerByToken($token) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this -> db -> escape($token) . "' AND token != ''");

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query -> row;
	}

	public function getTotalCustomersById($customer_id) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query -> row['total'];
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		return $query -> row['total'];
	}

	public function getTotalCustomersByTelephone($telephone) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(telephone) = REPLACE('" . $this -> db -> escape(utf8_strtolower($telephone)) . "'" . ", ' ', '')");

		return $query -> row['total'];
	}

	public function getIps($customer_id) {
		$query = $this -> db -> query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query -> rows;
	}

	public function isBanIp($ip) {
		$query = $this -> db -> query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this -> db -> escape($ip) . "'");

		return $query -> num_rows;
	}

	public function addLoginAttempt($email) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this -> db -> escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this -> db -> escape($this -> request -> server['REMOTE_ADDR']) . "'");

		if (!$query -> num_rows) {
			$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this -> db -> escape(utf8_strtolower((string)$email)) . "', ip = '" . $this -> db -> escape($this -> request -> server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this -> db -> escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this -> db -> escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this -> db -> escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query -> row['customer_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this -> db -> query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		return $query -> row;
	}

	public function deleteLoginAttempts($email) {
		$this -> db -> query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");
	}

	public function getPackages($customer_id) {
		$query = $this -> db -> query("SELECT cm.*,ml.name_vn AS package_vn FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id) WHERE cm.customer_id = '" . (int)$customer_id . "' ORDER BY cm.date_added");

		return $query -> rows;
	}

	public function getInfoPackages($id_package) {
		$query = $this -> db -> query("SELECT cm.*,ml.name_vn AS package_vn,c.username,c.firstname FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id) WHERE cm.id_package = '" . (int)$id_package . "'");

		return $query -> row;
	}

	public function getNameParent($customer_id) {
		$query = $this -> db -> query("SELECT c.firstname AS name_parent FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) WHERE cm.customer_id = '" . (int)$customer_id . "'");
		if (isset($query -> row['name_parent'])) {
			return $query -> row['name_parent'];
		} else
			return "";
	}

	public function getMonthRegister($customer_id) {
		$date = strtotime(date('Y-m-d'));
		$yearNow = date('Y', $date);
		$monthNow = date('m', $date);
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$rowCus = $query -> row;
		$dateRegis = strtotime($rowCus['date_added']);
		$yearRegis = date('Y', $dateRegis);
		$monthRegis = date('m', $dateRegis);
		$numYear = $yearNow - $yearRegis;
		if ($numYear > 0) {
			$monthNow = $monthNow + (12 * $numYear);
		}
		return $monthNow - $monthRegis;
	}

	public function getAllProfitByType($user_id, $type) {
		$query = $this -> db -> query("SELECT SUM(receive) AS total FROM " . DB_PREFIX . "profit WHERE user_id = '" . (int)$user_id . "' and type_profit in (" . $type . ")");
		return $query -> row['total'];
	}

	public function countProfitByType($user_id, $type) {
		$query = $this -> db -> query("SELECT count(*) AS total FROM " . DB_PREFIX . "profit WHERE user_id = '" . (int)$user_id . "' and type_profit in (" . $type . ")");
		return $query -> row['total'];
	}

	function getBParent($id) {
		$query = $this -> db -> query("select p_binary from " . DB_PREFIX . "customer as u1 INNER join " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id where u1.customer_id = " . (int)$id);
		return $query -> row['p_binary'];
	}

	function getInfoUsers($id_ids) {
		if (!is_array($id_ids))
			$ids = array($id_ids);
		else
			$ids = $id_ids;
		$array_id = "( " . implode(',', $ids) . " )";
		$query = $this -> db -> query("select u.*,mlm.level, l.name_vn as level_member from " . DB_PREFIX . "customer as u Left Join " . DB_PREFIX . "customer_ml as mlm ON mlm.customer_id = u.customer_id  Left Join " . DB_PREFIX . "member_level as l ON l.id = mlm.level  Where u.customer_id IN " . $array_id);
		if (!is_array($id_ids)) {
			$return = $query -> row;
		} else {
			$return = $query -> rows;
		}
		return $return;
	}

	//	lay tong so thanh vien
	function getSumNumberMember($node) {
		$result = 0;
		return $result;
	}

	function getLeftO($id) {
		$query = $this -> db -> query('select u2.email, u2.telephone, u2.date_added, mlm.customer_id as id, mlm.level,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as text, CONCAT( "level1"," left") as iconCls,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as name,l.name_vn as level_user,u2.username,u2.status,u2.date_added  from ' . DB_PREFIX . 'customer AS u2 LEFT join ' . DB_PREFIX . 'customer_ml AS mlm ON u2.customer_id = mlm.customer_id INNER join ' . DB_PREFIX . 'customer_ml AS u1 ON u1.left = mlm.customer_id left Join ' . DB_PREFIX . 'member_level as l ON l.id = mlm.level where mlm.p_binary = ' . (int)$id);
		//	return json_decode(json_encode($query->row), false);
		return $query -> row;
	}

	function getRightO($id) {
		$query = $this -> db -> query('select u2.email, u2.telephone,u2.date_added, mlm.customer_id as id, mlm.level,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as text, CONCAT( "level1"," right") as iconCls,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as name,l.name_vn as level_user,u2.username,u2.status,u2.date_added from ' . DB_PREFIX . 'customer AS u2 LEFT join ' . DB_PREFIX . 'customer_ml AS mlm ON u2.customer_id = mlm.customer_id INNER join ' . DB_PREFIX . 'customer_ml AS u1 ON u1.right = mlm.customer_id left Join ' . DB_PREFIX . 'member_level as l ON l.id = mlm.level where mlm.p_binary = ' . (int)$id);
		//return json_decode(json_encode($query->row), false);
		return $query -> row;
	}

	function getLeft($id) {
		$query = $this -> db -> query("select u2.left from " . DB_PREFIX . "customer as u1
			INNER JOIN " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id
			where u1.customer_id = " . (int)$id);
		return null;
	}

	function getRight($id) {
		$query = $this -> db -> query("select u2.right from " . DB_PREFIX . "customer as u1 INNER JOIN " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id where u1.customer_id = " . (int)$id);
		return null;
	}

	function getSumLeft($id) {
		$result = 0;
		$left = $this -> getLeft($id);
		if ($left) {
			$result += 1;
			$result += $this -> getSumMember($left);
		}
		return $result;
	}

	//Get sum right node binarytree
	function getSumRight($id) {
		$result = 0;
		$right = $this -> getRight($id);
		if ($right) {
			$result += 1;
			$result += $this -> getSumMember($right);
		}
		return $result;
	}

	//Get sum left node and right node for any node bynary
	function getSumMember($id) {

		$result = 0;
		$left = $this -> getLeft($id);
		$right = $this -> getRight($id);
		if ($left) {
			$result += 1;
			$result += $this -> getSumMember($left);
		}
		if ($right) {
			$result += 1;
			$result += $this -> getSumMember($right);
		}

		//print_r($result);
		return $result;
	}

	function getSumFloor($arrId) {
		$floor = 0;
		$query = $this -> db -> query("select mlm.customer_id from " . DB_PREFIX . "customer as u Left Join " . DB_PREFIX . "customer_ml as mlm ON mlm.customer_id = u.customer_id  Where mlm.p_binary IN (" . $arrId . ")");
		$arrChild = $query -> rows;

		if (!empty($arrChild)) {
			$floor += 1;
			$arrId = '';
			foreach ($arrChild as $child) {
				$arrId .= ',' . $child['customer_id'];
			}
			$arrId = substr($arrId, 1);
			$floor += $this -> getSumFloor($arrId);
		}
		return $floor;
	}



	function checkActiveUser($id_user = 0) {
		$query = $this -> db -> query("select u1.status from " . DB_PREFIX . "customer as u1 where u1.customer_id = " . (int)$id_user);
		return $query -> row['status'];
	}

	function getCountTreeCustom($id_user) {

		$listId = 0;
		$query = $this -> db -> query("select customer_id from " . DB_PREFIX . "customer_ml where p_node = " . (int)$id_user);
		$array_id = $query -> rows;

		foreach ($array_id as $item) {
			$listId ++;
			$listId = $listId + $this -> getCountTreeCustom($item['customer_id']);
		}
		return $listId;
	}

	function getCountBinaryTreeCustom($id_user) {
		$listId =0 ;
		$query = $this -> db -> query("select customer_id from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user);
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId ++;
			$listId = $listId + $this -> getCountBinaryTreeCustom($item['customer_id']);
		}
		return $listId;
	}


	function getCount_ID_BinaryTreeCustom($id_user) {
		$listId = '';
		$query = $this -> db -> query("select customer_id from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user);
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ','.$item['customer_id'];
			$listId .= $this -> getCount_ID_BinaryTreeCustom($item['customer_id']);
		}
		return $listId;
	}



	function getCountLevelCustom($id_user, $level) {
		$listId = 0;

		$query = $this -> db -> query("select customer_id , level from " . DB_PREFIX . "customer_ml where p_node = " . (int)$id_user);
		$array_id = $query -> rows;

		foreach ($array_id as $item) {
			intval($item['level']) === intval($level) && $listId ++;
			$listId = $listId + $this -> getCountLevelCustom($item['customer_id'], $level);
		}
		return $listId;
	}

	function getListIdChild($id_user) {
		$listId = '';
		$query = $this -> db -> query("select customer_id from " . DB_PREFIX . "customer_ml
			where p_binary = " . (int)$id_user);
		$array_id = $query -> rows;

		foreach ($array_id as $item) {
			$listId .= ',' . $item['customer_id'];
			$listId .= $this -> getListIdChild($item['customer_id']);
		}
		return $listId;
	}

	function getListCTP($id_user) {
		$dateEnd = date("Y-m-d H:i:s");
		$monthEnd = date('m', strtotime($dateEnd));
		$yearEnd = date('Y', strtotime($dateEnd));
		$arrCTP = array();
		$query = $this -> db -> query("select * from " . DB_PREFIX . "customer where customer_id = " . (int)$id_user);
		$infoUser = $query -> row;
		$dateStar = $infoUser['date_added'];

		$monthRegister = $this -> getMonthRegister($id_user);
		$numHP = $this -> countProfitByType($id_user, 1);
		$config_congtacphi = $this -> config -> get('config_congtacphi');
		for ($n = 1; $n <= 12; $n++) {
			$monthStar = date('m', strtotime($dateStar));
			$yearStar = date('Y', strtotime($dateStar));
			if ($monthStar == "12") {
				$monthNext = 1;
				$yearNext = $yearStar + 1;
			} else {
				$monthNext = $monthStar + 1;
				$yearNext = $yearStar;
			}
			$dateNext = date("Y-m-d", strtotime("01-" . $monthNext . "-" . $yearNext));
			if (strtotime($dateNext) <= strtotime($dateEnd)) {
				$node = new stdClass();
				$queryHVTT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user . " AND date_added >= '" . $dateStar . "' AND date_added < '" . $dateNext . "'");
				$numHVTT = $queryHVTT -> row['total'];
				$CTP_HVTT = $numHVTT * $config_congtacphi;
				$node -> numHVTT = $numHVTT;
				$node -> CTP_HVTT = $CTP_HVTT;
				$queryHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND receive > 0 AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateNext) . "'");
				$numHVGT = $queryHVGT -> row['total'] - $numHVTT;
				$CTP_HVGT = $numHVGT * $config_congtacphi;
				$queryTotalHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateNext) . "'");
				$numTotalHVGT = $queryTotalHVGT -> row['total'] - $numHVTT;
				$node -> numHVGT = $numHVGT;
				$node -> numTotalHVGT = $numTotalHVGT;
				$node -> CTP_HVGT = $CTP_HVGT;
				$node -> CTP_DuKien = $CTP_HVTT + $CTP_HVGT;
				$queryHPFromCTP = $this -> db -> query("select SUM(receive) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 1 AND hp_from_ctp = 1 AND date_hpdk >= '" . strtotime($dateStar) . "' AND date_hpdk < '" . strtotime($dateNext) . "'");
				$numHPFromCTP = $queryHPFromCTP -> row['total'];

				$numUserOff = 0;
				$listIdChild = $this -> getListIdChild($id_user);
				$listIdChild = substr($listIdChild, 1);

				if ($listIdChild != '') {
					$queryUserOff = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 0 AND MONTH(c.date_off ) = '" . $monthStar . "' AND YEAR(c.date_off ) = '" . $yearStar . "' AND c.num_off = 1 and c.type_off = 1");
					$numUserOff = count($queryUserOff -> rows);
				}

				if (($monthRegister >= $n && $numHP > $n) || ($monthRegister == 11 && $n == 12 && $numHP == 12)) {
					$node -> CTP_Thuc = $node -> CTP_DuKien - $numHPFromCTP - ($numUserOff * $config_congtacphi);
				} else {
					$node -> CTP_Thuc = 0;
				}
				$dateStar = $dateNext;
				array_push($arrCTP, $node);
			} else {
				$node = new stdClass();
				$queryHVTT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user . " AND date_added >= '" . $dateStar . "' AND date_added < '" . $dateEnd . "'");
				$numHVTT = $queryHVTT -> row['total'];
				$CTP_HVTT = $numHVTT * $config_congtacphi;
				$node -> numHVTT = $numHVTT;
				$node -> CTP_HVTT = $CTP_HVTT;
				$queryHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . "  AND receive > 0 AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateEnd) . "'");
				$numHVGT = $queryHVGT -> row['total'] - $numHVTT;
				$CTP_HVGT = $numHVGT * $config_congtacphi;
				$queryTotalHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateEnd) . "'");
				$numTotalHVGT = $queryTotalHVGT -> row['total'] - $numHVTT;
				$node -> numHVGT = $numHVGT;
				$node -> numTotalHVGT = $numTotalHVGT;
				$node -> CTP_HVGT = $CTP_HVGT;
				$node -> CTP_DuKien = $CTP_HVTT + $CTP_HVGT;
				$queryHPFromCTP = $this -> db -> query("select SUM(receive) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 1 AND hp_from_ctp = 1 AND date_hpdk >= '" . strtotime($dateStar) . "' AND date_hpdk < '" . strtotime($dateNext) . "'");
				$numHPFromCTP = $queryHPFromCTP -> row['total'] + 0;
				$numUserOff = 0;
				$listIdChild = $this -> getListIdChild($id_user);
				$listIdChild = substr($listIdChild, 1);

				if ($listIdChild != '') {
					$queryUserOff = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 0 AND MONTH(c.date_off) = '" . $monthStar . "' AND YEAR(c.date_off ) = '" . $yearStar . "' AND c.num_off = 1 and c.type_off = 1");
					$numUserOff = count($queryUserOff -> rows);
				}
				if ($monthRegister >= $n && $numHP > $n || ($monthRegister == 11 && $n == 12 && $numHP == 12)) {
					$node -> CTP_Thuc = $node -> CTP_DuKien - $numHPFromCTP - ($numUserOff * $config_congtacphi);
				} else {
					$node -> CTP_Thuc = 0;
				}

				array_push($arrCTP, $node);
				break;
			}
		}

		if ($n < 12) {
			for ($n; $n <= 12; $n++) {
				$node = new stdClass();
				$node -> numHVTT = 0;
				$node -> CTP_HVTT = 0;
				$node -> numHVGT = 0;
				$node -> numTotalHVGT = 0;
				$node -> CTP_HVGT = 0;
				$node -> CTP_DuKien = 0;
				$node -> CTP_Thuc = 0;
				array_push($arrCTP, $node);
			}
		}

		return $arrCTP;
	}
	public function getParentByIdCustomer($customer_id){
		$query = $this->db->query("
			SELECT username AS name FROM " . DB_PREFIX . "customer WHERE p_node = '".$customer_id."'");
		return $query -> rows;
	}
	public function getCountFloor($id_user) {
		$query = $this -> db -> query("SELECT customer_id
			FROM " . DB_PREFIX . "customer_ml
			WHERE p_binary IN (". $id_user.")");
		return $query -> rows;

	}
	public function getCheckPD($id_customer){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE customer_id = '".$this->db->escape($id_customer)."'
		");
		return $query -> row;
	}
	public function UpdateCheckPD($id_customer){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			 SET check_Pd = check_Pd + 1 WHERE customer_id = '".$this->db->escape($id_customer)."'
		");
	}
	public function UpdateResetPD($id_customer){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			 SET check_Pd = '0' WHERE customer_id = '".$this->db->escape($id_customer)."'
		");
	}
	public function CountGDDay($number_pd_day,$number_pd_month){
		$date = date('Y-m-d');
		$date_month = date('Y-m'); 
		
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id= '".$this -> session -> data['customer_id']."'
			AND (SELECT COUNT(*) FROM ". DB_PREFIX . "customer_provide_donation
				WHERE customer_id= '".$this -> session -> data['customer_id']."' AND date_added >= '".$date." 00:00:00' AND date_added <= '".$date." 23:59:59') < ".$number_pd_day." AND (SELECT COUNT(*) FROM ". DB_PREFIX . "customer_provide_donation
				WHERE customer_id= '".$this -> session -> data['customer_id']."' AND date_added >= '".$date_month."-01 00:00:00' AND date_added <= '".$date_month."-30 23:59:59') < ".$number_pd_month."
			
		");

		return $query->row;
	}
	public function getStatusPD(){
		$query = $this -> db -> query("
			SELECT COUNT(*) as pdtotal
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE status = '0' AND customer_id = '".$this -> session -> data['customer_id']."'
		");
		return $query -> row;
	}
	public function getStatusGD(){
		$query = $this -> db -> query("
			SELECT COUNT(*) as gdtotal
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE status = '0' AND customer_id = '".$this -> session -> data['customer_id']."'
		");
		return $query -> row;
	}


	public function getLanguage($customer_id){
		$query = $this -> db -> query("
			SELECT language
			FROM ". DB_PREFIX . "customer
			WHERE customer_id = ".$customer_id."
		");
		return $query -> row['language'];
	}

	public function updateLanguage($customer_id, $language){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer SET
			language = '".$language."'
			WHERE customer_id = ".$customer_id."
		");
		return $query;
	}
	public function updateDatefinishPD($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = '1',
				date_finish = DATE_ADD(NOW(),INTERVAL + 20 DAY)
				WHERE id = '".$pd_id."'
			");
	}
	public function getAllPDByTranferID($pd_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$this->db->escape($pd_id)."'
		");
		return $query -> row;
	}
	public function getAllGDByTranferID($gd_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE id = '".$this->db->escape($gd_id)."'
		");
		return $query -> row;
	}

	public function getPDMarch($iod_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."' and status = 1
		");
		return $query -> row;
	}
	public function getCustomOfNode($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.customer_id AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node = ". $id_user."");
		$array_id = $query -> rows;

		foreach ($array_id as $item) {
			$listId .= ',' . $item['code'];
			$listId .= $this -> getCustomOfNode($item['code']);
		}
		return $listId;
	}
	public function get_child_node($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.customer_id AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node = ". $id_user."");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['code'];
			$listId .= $this -> get_child_node($item['code']);
		}
		return $listId;
	}
	public function get_p_node_from_node($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.customer_id, c.p_node AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.customer_id = ". $id_user."");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['code'];
			$listId .= $this -> get_p_node_from_node($item['code']);
		}
		return $listId;
	}
	public function countLeft($arrId){
		if($arrId != ''){
			$query = $this -> db -> query("
			SELECT `left` FROM ". DB_PREFIX ."customer_ml WHERE `left` IN (".$arrId.")
		");
		return $query -> rows;
		}

	}
	public function getLeft_Right($Binary){
			$query = $this -> db -> query("
				SELECT * FROM ". DB_PREFIX ."customer_ml WHERE p_binary = '".$Binary."'
		");
		return $query -> row;
	}
	public function countall($customer_id){
		$query = $this -> db -> query("SELECT * FROM sm_customer_ml WHERE customer_id = ".$customer_id."");
	   	return $query -> rows;
	}


	public function getCustomer_ML($customer_id){

		$query = $this -> db -> query("SELECT ml.left,ml.right FROM sm_customer_ml as ml WHERE customer_id = ".$customer_id."");
		return $query -> row;
	}


	public function leftcount($customer_id)
	{
	    $query = $this -> db -> query("SELECT * FROM sm_customer_ml WHERE customer_id = ".$customer_id."");
	   	$array_left = $query -> row;
	    $count = 0;
	    if(!empty($array_left['left']))
	    {
	        $count += $this->allcount($array_left['left']) +1;
	    }
	    return $count;
	}
	public function rightcount($customer_id)
	{
	    $query = $this -> db -> query("SELECT * FROM sm_customer_ml WHERE customer_id = ".$customer_id."");
		$array_right = $query -> row;
	    $count = 0;
	    if(!empty($array_right['right']))
	    {
	        $count += $this->allcount($array_right['right']) +1;
	    }
	    return $count;
	}
	public function allcount($customer_id)
	{
	    $query = $this -> db -> query("SELECT * FROM sm_customer_ml WHERE customer_id = ".$customer_id."");
		$array = $query -> row;
	    $count = 0;
	    if(!empty($array['left']))
	    {
	        $count += $this->allcount($array['left']) +1;
	    }
	    if(!empty($array['right']))
	    {
	        $count += $this->allcount($array['right']) +1;
	    }
	    return $count;
	}

	public function countRight($arrId){
			if($arrId != ''){
		$query = $this -> db -> query("
			SELECT `right` FROM ". DB_PREFIX ."customer_ml WHERE `right` IN (".$arrId.")
		");
		return $query -> rows;
		}
	}
	public function getAllTotalGD(){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_get_donation
		");

		return $query -> row;
	}
	public function getAllTotalGD_Status($customer_id){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_get_donation WHERE status = 1 AND customer_id =  ".$customer_id."
		");

		return $query -> row;
	}
	public function getAllTotalPD(){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_provide_donation
		");

		return $query -> row;
	}
	public function getAllTotalPD_Status($customer_id){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_provide_donation WHERE status = 1 AND customer_id =  ".$customer_id."");

		return $query -> row;
	}
	public function getAllGD($limit, $offset,$status){
		$query = $this -> db -> query("
			SELECT c.username, gd.amount, gd.date_added
			FROM  ".DB_PREFIX."customer_get_donation gd LEFT JOIN sm_customer c on gd.customer_id = c.customer_id where gd.status ='".$status."'
			GROUP BY gd.customer_id ORDER BY gd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}
	public function getAllPD($limit, $offset, $status){
		$query = $this -> db -> query("
			SELECT c.username, pd.filled, pd.date_added
			FROM  ".DB_PREFIX."customer_provide_donation pd LEFT JOIN sm_customer c on pd.customer_id = c.customer_id WHERE pd.status = '".$status."'
			 GROUP BY c.username ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}
	function get_p_binary($arrid) {
		$query = $this -> db -> query("select p_binary from " . DB_PREFIX . "customer as u1 INNER join " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id where u1.customer_id IN (".$arrid.")");
		return $query -> rows;
	}

	public function countPDLeft_Right($arrId){
		if($arrId != ''){
		$query = $this -> db -> query("
			SELECT SUM(total_pd) as total FROM ". DB_PREFIX ."customer WHERE customer_id IN (".$arrId.")
		");
		return $query -> row;
		}
	}
	public function checkBinaryLeft($id){
		$query = $this -> db -> query("
			SELECT `left` FROM ". DB_PREFIX ."customer_ml WHERE `p_binary` ='".$id."'
		");
		return $query -> row;
	}
	public function checkBinaryRight($id){
		$query = $this -> db -> query("
			SELECT `right` FROM ". DB_PREFIX ."customer_ml WHERE `p_binary` ='".$id."'
		");
		return $query -> row;
	}
	public function checkBinary($id){
		$query = $this -> db -> query("
			SELECT * FROM ". DB_PREFIX ."customer_ml WHERE `p_binary` ='".$id."'
		");
		return $query -> row;
	}
	public function ResetCycleAddCustomer($pd_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			cycle = 0
			WHERE customer_id = '".$pd_id."'
		");
	}
	public function getConfirmTransaction(){
		$query = $this -> db -> query("
			SELECT * FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = ".$this -> session -> data['customer_id']."
			AND status = 1 ORDER BY date_added DESC LIMIT 1
		");
		return $query -> rows;
	}
	public function update_check_withdrawal(){
		$query = $this->db->query('UPDATE '.DB_PREFIX.'customer_provide_donation SET check_withdrawal = 1
			WHERE customer_id = '.$this -> session -> data['customer_id'].' AND status = 2');
		return $query === true ? true : false;
	}
	public function checkM_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_m_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function get_M_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_m_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function get_M_Wallet_GD($id_customer){

		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_m_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND date <= NOW()
		");
		return $query -> row;
	}
	public function update_M_Wallet($amount , $customer_id, $date = false){
		if (!$date) {
			$query = $this -> db -> query("	UPDATE " . DB_PREFIX . "customer_m_wallet SET
			amount = amount + ".intval($amount)."
			WHERE customer_id = '".$customer_id."'
		");

		}else{
			$query = $this -> db -> query("	UPDATE " . DB_PREFIX . "customer_m_wallet SET
			amount = amount + ".intval($amount).",
			date = DATE_ADD(NOW(),INTERVAL + 90 DAY)
			WHERE customer_id = '".$customer_id."'
		");

		}
		return $query === true ? true : false;
	}
	public function insert_M_Wallet($id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_m_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = '0',
			date = DATE_ADD(NOW(),INTERVAL - 90 DAY)
		");
		return $query;
	}
	public function update_total_pd($customer_id, $amount){
		$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				total_pd = total_pd + ".$amount."
				WHERE customer_id = '" . (int)$customer_id. "'");
		return $query;
	}
	public function getAmountPD($id_customer){

		$query = $this -> db -> query("
			SELECT filled
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND status <> 0 ORDER BY filled DESC LIMIT 1
		");
		return $query -> row['filled'];
	}

	public function GetPDmyaccount($customer_id,$limit,$offset){

		$query = $this -> db -> query("
			SELECT A.amount AS amount,
				   A.image AS image,
				   A.id AS id,
				   A.transfer_code AS transfer_id
			FROM ". DB_PREFIX . "customer_transfer_list A 
			WHERE A.pd_id_customer = '".$customer_id."' 
			GROUP BY A.id
			ORDER BY A.id DESC
			
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}
	public function updateStatusPD_TransferList($id_tranfer,$image){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 1,
				image = '".$image."'
				WHERE transfer_code = '".intval($id_tranfer)."'
		");
		return $query;
	}
	public function show_img_tranfer($gd_id){
		$query = $this -> db -> query("
			SELECT image
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE id = '".$this->db->escape($gd_id)."'
		");
		return $query -> row;
	}
	public function GetGDmyaccount($customer_id,$limit,$offset){
	
		$query = $this -> db -> query("
			SELECT A.amount AS amount,
				   A.image AS image,
				   A.id AS id,
				   A.transfer_code AS transfer_id
			FROM ". DB_PREFIX . "customer_transfer_list A 
			WHERE gd_id_customer = ".$customer_id."
			GROUP BY A.id
			ORDER BY A.id DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}

	public function updateStatusGD_tranfer($id_tranfer,$status){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				gd_status = '".$status."'
				WHERE transfer_code = '".$id_tranfer."'
		");
		return $query;
	}

	public function updateStatusPD_tranfer($id_tranfer,$status){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = '".$status."'
				WHERE transfer_code = '".$id_tranfer."'
		");
		return $query;
	}

	public function updateStatusGD($gd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				status = 2
				WHERE id = '".$id_tranfer."'
		");
		return $query;
	}

	public function countGDTransferList($customer_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_transfer_list
			WHERE gd_id_customer = '". $customer_id ."'
			");
		return $query -> row;
	}
	public function countPDTransferList($customer_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_transfer_list
			WHERE pd_id_customer = '". $customer_id ."'
			");
		return $query -> row;
	}
	public function countPD($customer_id){
		$query = $this -> db -> query("
			SELECT * FROM sm_customer_provide_donation where customer_id in 
			(SELECT customer_id FROM sm_customer WHERE p_node = ".$customer_id.") AND status = 0 GROUP BY customer_id");
		return $query -> rows;
	}
	public function updatestatusPD($pd_id,$status){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = '".$status."'
				WHERE id = '".$pd_id."'
			");
		return $query;
	}

	public function updatestatusGDCustom($gd_id,$status){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				status = '".$status."'
				WHERE id = '".$gd_id."'
			");
		return $query;
	}
	public function show_tranfer($gd_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE id = '".$this->db->escape($gd_id)."'
		");
		return $query -> row;
	}

	public function getTransferList($transfer_code){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE transfer_code = '".$transfer_code."'
		");
		return $query -> row;
	}

	public function get_PD_f1($id_customer,$limit,$offset){
		$query = $this -> db -> query("
			SELECT A.customer_id, B.*,C.*,(SELECT username FROM ". DB_PREFIX . "customer as K WHERE K.customer_id = B.gd_id_customer) as gd_username, (SELECT account_number FROM ". DB_PREFIX . "customer as K WHERE K.customer_id = B.gd_id_customer) as gd_account_number,(SELECT telephone FROM ". DB_PREFIX . "customer as K WHERE K.customer_id = B.gd_id_customer) as gd_telephone,(SELECT account_holder FROM ". DB_PREFIX . "customer as K WHERE K.customer_id = B.gd_id_customer) as gd_account_holder
			FROM ". DB_PREFIX . "customer_ml A INNER JOIN ". DB_PREFIX . "customer_transfer_list B ON A.customer_id = B.pd_id_customer INNER JOIN ". DB_PREFIX . "customer C ON B.pd_id_customer = C.customer_id
			WHERE A.p_node = '".$this->db->escape($id_customer)."' LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}
	public function count_PD_f1($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX . "customer_ml A INNER JOIN ". DB_PREFIX . "customer_provide_donation B ON A.customer_id = B.customer_id
			WHERE p_node = '".$this->db->escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getIdCho_Trander_list($transfer_id){
		$query = $this -> db -> query("
			SELECT C.username, C.	account_holder, C.telephone
			FROM ". DB_PREFIX . "customer_transfer_list T
			INNER JOIN ". DB_PREFIX . "customer C
				ON T.pd_id_customer = C.customer_id
			WHERE T.id = ".$transfer_id."
		");
		return $query -> row;
	}

	public function getIdNhan_Trander_list($transfer_id){
		$query = $this -> db -> query("
			SELECT C.username, C.account_holder, C.telephone, C.account_number 
			FROM ". DB_PREFIX . "customer_transfer_list T
			INNER JOIN ". DB_PREFIX . "customer C
				ON T.gd_id_customer = C.customer_id
			WHERE T.id = ".$transfer_id."
		");
		return $query -> row;
	}

	public function getPDCho_Trander_list($transfer_id){

		$query = $this -> db -> query("
			SELECT P.*
			FROM ". DB_PREFIX . "customer_transfer_list T
			INNER JOIN ". DB_PREFIX . "customer_provide_donation P
				ON T.pd_id = P.id
			WHERE T.id = ".$transfer_id."
		");
		return $query -> row;
	}

	public function getGDNhan_Trander_list($transfer_id){

		$query = $this -> db -> query("
			SELECT G.*
			FROM ". DB_PREFIX . "customer_transfer_list T
			INNER JOIN ". DB_PREFIX . "customer_get_donation G
				ON T.gd_id = G.id
			WHERE T.id = ".$transfer_id."
		");
		return $query -> row;
	}

	public function getGDNhan_Trander_list_Image_poup($transfer_id){

		$query = $this -> db -> query("
			SELECT G.*
			FROM ". DB_PREFIX . "customer_transfer_list T
			INNER JOIN ". DB_PREFIX . "customer_get_donation G
				ON T.gd_id = G.id
			WHERE T.id = ".$transfer_id."
		");
		return $query -> row;
	}

	public function count_provide_donation($customer_id){
		$query = $this -> db -> query("
			SELECT count(*) as numb
			FROM ". DB_PREFIX . "customer_provide_donation T
			WHERE customer_id = ".$customer_id."
		");
		return $query -> row;
	}
	public function Get_provide_donation($customer_id,$limit,$offset){

		$query = $this -> db -> query("
			SELECT A.*,B.username,A.id as id_tranfer,C.date_finish as date_finish_tranfer
			FROM ". DB_PREFIX . "customer_provide_donation A INNER JOIN ". DB_PREFIX . "customer B ON A.customer_id = B.customer_id LEFT JOIN ". DB_PREFIX . "customer_transfer_list C ON A.id = C.pd_id
			WHERE A.customer_id = '".$customer_id."' 
			ORDER BY A.id DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}
	public function get_provide_donation_new($customer_id,$limit,$offset){

		$query = $this -> db -> query("
			SELECT A.*,B.username
			FROM ". DB_PREFIX . "customer_provide_donation A INNER JOIN ". DB_PREFIX . "customer B ON A.customer_id = B.customer_id
			WHERE A.customer_id = '".$customer_id."' 
			ORDER BY A.id DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}
	public function saveMessage($customer_id, $transfer_id, $message){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."customer_message_transfer SET
			customer_id = '".$customer_id."',
			transfer_id = '".$transfer_id."',
			message = '".$message."',
			
			date_added = '".$date_added."'
		");
		return $query;
	}
	public function getMessage($transfer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_message_transfer
			WHERE transfer_id = ".$transfer_id."
		");
		return $query -> rows;
	}

	public function get_customer_by_binary($p_binary) {
		$query = $this -> db -> query("SELECT c.username, c.telephone, c.customer_id , ml.level, ml.p_binary,ml.p_node FROM ". DB_PREFIX ."customer AS c
				JOIN ". DB_PREFIX ."customer_ml AS ml
				ON ml.customer_id = c.customer_id
				WHERE ml.customer_id = '" . (int)$p_binary . "'");
		return $query -> row;
	}
	public function get_C_wallet_level0($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_c_wallet
			WHERE customer_id = ".$customer_id." AND count_rut = 0  
		");
		return $query -> rows;
	}
	public function onlineToday(){
		$date = date('Y-m-d');
		$total = 0;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `key` = 'login' and `date_added` >= '".$date." 00:00:00' and `date_added` <='".$date." 23:59:59' GROUP BY customer_id");
		if (isset($query->rows)) {
			$total = count($query->rows);
		}
		return $total;
	}
	public function getTransferList_byId($id){
		$query = $this -> db -> query("
			SELECT COUNT(*) as number
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE id = '".$id."'
		");
		return $query -> row;
	}
	public function updateStatusGDTransferList($transferID){

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				
				gd_status = 1
				
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	public function updateStatusGDTransferList_report($transferID){

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				
				gd_status = 2
				
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	public function tatol_GD_child($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_get_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE B.p_node = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1) GROUP BY A.id
		");

		return $query -> row;
	}
	public function get_GD_child($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_get_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id LEFT JOIN ".DB_PREFIX."customer_transfer_list C ON A.customer_id = C.gd_id_customer
			WHERE B.p_node = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1) GROUP BY A.id
		"); 

		return $query -> rows;
	}
	public function tatol_PD_child($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_provide_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE B.p_node = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1) GROUP BY A.id
		");

		return $query -> row;
	}
	public function get_PD_child($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id WHERE B.p_node = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1) GROUP BY A.id
		");

		return $query -> rows;
	}

	public function tatol_GD_customer($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_get_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE B.customer_code = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1)
			GROUP BY A.id
		");

		return $query -> row;
	}
	public function get_GD_customer($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_get_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id LEFT JOIN ".DB_PREFIX."customer_transfer_list C ON A.customer_id = C.gd_id_customer
			WHERE B.customer_code = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1)
			GROUP BY A.id
		");

		return $query -> rows;
	}

	public function tatol_PD_customer($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_provide_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE B.customer_code = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1) GROUP BY A.id
		");

		return $query -> row;
	}
	public function get_PD_customer($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id WHERE B.customer_code = '".$this -> db -> escape($id_customer)."' AND (A.status = 0 or A.status = 1) GROUP BY A.id
		");

		return $query -> rows;
	}


	public function getPDfinish_child($id_customer){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			SELECT A.*,B.username,B.account_number
			FROM  ".DB_PREFIX."customer_transfer_list A INNER JOIN ".DB_PREFIX."customer B ON A.pd_id_customer = B.customer_id
			WHERE B.p_node = '".$this -> db -> escape($id_customer)."' AND A.pd_satatus = 0 AND A.date_finish <= '".$date_added."' AND A.status_pnode_pd = 0
		");

		return $query -> rows;
	}
	public function createPD_pnode($amount, $max_profit){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '+ 1 day' , strtotime ($date_added));
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET
			customer_id = '".$this -> session -> data['customer_id']."',
			date_added = '".$date_added."',
			filled = '".$amount."',
			date_finish = '".$date_finish."',
			date_finish_forAdmin = '".$date_finish."',
			status = 1,
			check_R_Wallet = 1
		");
		//update max_profit and pd_number
		$pd_id = $this->db->getLastId();

		//$max_profit = (float)($amount * $this->config->get('config_pd_profit')) / 100;

		$pd_number = hexdec( crc32($pd_id) );
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				max_profit = '".$max_profit."',
				pd_number = '".$pd_number."'
				WHERE id = '".$pd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['pd_number'] = $pd_number;
		$data['pd_id'] = $pd_id;
		return $data;
	}
	public function createTransferList($pd_id,$gd_id,$pd_id_customer,$gd_id_customer,$amount){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_transfer_list SET
			pd_id = '".$pd_id."',
			gd_id = '".$gd_id."',
			pd_id_customer = '".$pd_id_customer."',
			gd_id_customer = '".$gd_id_customer."',
			transfer_code = '".hexdec( crc32($gd_id) ).rand(10,100)."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW() , INTERVAL + 24 HOUR),
			amount = '".$amount."',
			pd_satatus = 0,
			gd_status = 0
		");
	}

	public function update_status_pnode_pd($transferID){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				
				status_pnode_pd = 1
				
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	public function updateStatustranfer($transferID){

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 2,
				gd_status = 2
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	public function getGDweekday($customer_id){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("SELECT sum(amount) as sum
		FROM " . DB_PREFIX . "customer_get_donation
		WHERE YEARWEEK(date_added)=YEARWEEK('".$date_added."')
		");
		return $query->row['sum'];
	}

	public function getGD_user($customer_id){
		$query = $this -> db -> query("
			SELECT count(*) as num
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE customer_id = ".$customer_id."
		");
		return $query -> row['num'];
	}

	public function repd($customer_id){
		$date_added= date('Y-m-d H:i:s');
	
		$query = $this -> db -> query("
			SELECT count(*) as num
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE status = 2 AND date_finish <= '".$date_added."' AND check_gd = 0 AND customer_id = ".$customer_id."
		");
		return $query -> row['num'];
	}

	public function pd_user($customer_id){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			SELECT count(*) as num
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE status = 0 OR status = 1 OR status = 1 AND customer_id = ".$customer_id." AND date_finish <= '".$date_added."'
		");
		return $query -> row['num'];
	}
	public function update_check_gd($id){
		
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				check_gd = '1'
				WHERE id = '".$id."'
			");
		return $query;
	}
	public function check_GD_customer($customer_id){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '-48 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_get_donation 
			WHERE customer_id = '".$customer_id."' AND status = 2 AND date_finish <= '".$date_finish."' AND check_gd = 0 ORDER BY date_finish ASC LIMIT 1
			
		");
		return $query -> row;
	}
	public function check_pin($customer_id){
		$query = $this->db->query("SELECT ping
			FROM " . DB_PREFIX . "customer WHERE customer_id = ".$customer_id."");
		
		return $query->row['ping'];
	}
	public function get_insurance_fund(){
		$query = $this->db->query("SELECT amount
			FROM " . DB_PREFIX . "customer_insurance_fund");
		
		return $query->row['amount'];
	}

	public function count_1date($pd_id){
		$query = $this->db->query("SELECT * 
			FROM sm_customer_provide_donation WHERE status = 1 AND DATE_ADD(date_finish,INTERVAL - 2 DAY) < NOW() AND id = '".$pd_id."'");
		
		return $query->row;
	}
	public function count_2date($pd_id){
		$query = $this->db->query("SELECT *
			FROM sm_customer_provide_donation WHERE status = 1 AND DATE_ADD(date_finish,INTERVAL - 1 DAY) < NOW() AND id = '".$pd_id."'");
		
		return $query->row;
	}
	public function update_max_profit($pd_id,$max_profit){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				max_profit = '".$max_profit."'
				WHERE id = '".$pd_id."'
			");
		$data['query'] = $query ? true : false;
	}
	public function get_childrend($id_customer){
		
		$mang = "";
		$query = $this -> db -> query("
			SELECT customer_id
			FROM  ".DB_PREFIX."customer_ml
			WHERE p_node IN (".$id_customer.")
		");
		$count = $query->rows;
		foreach ($query->rows as $value) {
			$mang .= ",".$value['customer_id'];
		}
		$mang = substr($mang,1);
		if (strlen($mang) > 0)
		{
			$querys = $this -> db -> query("
				SELECT *
				FROM  ".DB_PREFIX."customer
				WHERE customer_id IN (".$mang.")
			");
			return $querys->rows; 
		}
		return array();
	}
	public function get_customer_by_code($code){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer
			WHERE customer_code = '".$this -> db -> escape($code)."'
		");
		return $query -> row;
	}
	public function get_customer_by_id($code){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer
			WHERE customer_id = '".$this -> db -> escape($code)."'
		");
		return $query -> row;
	}
	public function get_childrend_all($arrId) {
		$floor = 0;
		$query = $this -> db -> query("select mlm.customer_id from " . DB_PREFIX . "customer as u Left Join " . DB_PREFIX . "customer_ml as mlm ON mlm.customer_id = u.customer_id  Where mlm.p_node IN (" . $arrId . ")");
		$arrChild = $query -> rows;

		if (!empty($arrChild)) {
			$floor += 1;
			$arrId = '';
			foreach ($arrChild as $child) {
				$arrId .= ',' . $child['customer_id'];
			}
			$arrId = substr($arrId, 1);
			$floor += $this -> getSumFloor($arrId);
		}
		return $arrId;
	}
	public function get_customer_by_id_in($id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer
			WHERE customer_id IN (".$this -> db -> escape($id).") ORDER BY customer_id DESC
		");
		return $query -> rows;
	}
	public function get_childrend_customer($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_ml
			WHERE p_node = (".$id_customer.")
		");
		$count = $query -> rows;

		if (count($count) > 0)
		{
			$querys = $this -> db -> query("
				SELECT max(date_added) as max_date
				FROM  ".DB_PREFIX."customer_ml
				WHERE p_node = (".$id_customer.")
			");
			return $querys -> row['max_date'];
		}
		else
		{
			$queryss = $this -> db -> query("
				SELECT date_added 
				FROM  ".DB_PREFIX."customer_ml
				WHERE customer_id = (".$id_customer.")
			");
			return $queryss -> row['date_added'];
		}
	}
	public function date_pd($id_customer){
		$queryss = $this -> db -> query("
			SELECT date_added,level
			FROM  ".DB_PREFIX."customer_ml
			WHERE customer_id = (".$id_customer.")
		");
		$data_added = $queryss -> row['date_added'];
		$query = $this -> db -> query("
			SELECT * 
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = ".$id_customer." AND date_added >= '".date('Y')."-".date('m')."-".date('d H:i:s',strtotime($data_added))."'
		");
		$count = $query -> rows;
		$json['date_pd'] = date('Y')."-".date('m')."-".date('d H:i:s',strtotime($data_added));
		$json['count_pd'] = count($count);
		$json['level'] = $queryss -> row['level'];
		return $json;
	}

	public function create_reason($customer_id, $reason){

		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."account_remove SET
			customer_id = '".$customer_id."',
			reason = '".$reason."',
			date_added = '".$date_added."'
		");
		return $query;
	}
	public function get_all_pnode($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_ml
			WHERE p_node = '".$customer_id."'
		");
		return $query -> rows;
	}

	public function get_ml_customer($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_ml
			WHERE customer_id = '".$customer_id."'
		");
		return $query -> row;
	}

	public function remove_account($customer_id, $p_node){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
				p_node = '".$p_node."'
				WHERE customer_id = '".$customer_id."'
			");
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_ml SET
				p_node = '".$p_node."',
				p_binary = '".$p_node."'
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}

	public function up_status_removeaccount($customer_id, $status){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
				status = '".$status."'
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}
	public function create_sendmail_account($customer_id, $title,$description){

		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."account_sendmail SET
			customer_id = '".$customer_id."',
			title = '".$title."',
			description = '".$description."', 
			date_added = '".$date_added."'
		");
		return $query;
	}
	
	public function create_block_account($customer_id, $title,$content){
		$type = ($this -> session ->data['customer_id'] == 1) ? 1 : 0;
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."blog_customer SET
			customer_id = '".$customer_id."',
			title = '".$this -> db -> escape($title)."',
			description = '".$this -> db -> escape(htmlspecialchars_decode($content))."',
			date_added = NOW(),
			type = '".$type."'
		");
		
	}

	public function edit_block_account($customer_id, $title,$content,$id){
		
		$query = $this -> db -> query("
			UPDATE ".DB_PREFIX."blog_customer SET
			title = '".$this -> db -> escape($title)."',
			description = '".$this -> db -> escape(htmlspecialchars_decode($content))."',
			date_added = NOW()
			WHERE customer_id = '".$customer_id."' AND id = '".$id."'
		");
		
	}

	public function getTotalblog(){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."blog_customer
			WHERE type = 0 AND status = 0
		");

		return $query -> row;
	}
	public function getBlogById($limit, $offset){

		$query = $this -> db -> query("
			SELECT pd.*, c.username
			FROM  ".DB_PREFIX."blog_customer AS pd
			JOIN ". DB_PREFIX ."customer AS c
			ON pd.customer_id = c.customer_id
			WHERE pd.type = 0 AND pd.status = 0
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}
	public function getBlogById_admin(){

		$query = $this -> db -> query("
			SELECT pd.*
			FROM  ".DB_PREFIX."blog_customer AS pd
			WHERE type = 1 AND pd.status = 0
			ORDER BY pd.date_added DESC
		");

		return $query -> rows;
	}
	public function getblog_id($id){
		$query = $this -> db -> query("
			SELECT A.*,B.username
			FROM  ".DB_PREFIX."blog_customer A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE A.id = '".$id."' AND A.status = 0
		");

		return $query -> row;
	}
	public function getCustomerbyCode($customer_id) {
		
		$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_code = '" . $customer_id . "'");
		return $query -> row;
	}

	public function  get_goidautu($customer_id){
		$query = $this -> db -> query("
			SELECT MAX(filled) as package
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = '".$this -> db -> escape($customer_id)."'
		");
		return $query -> row;
	}

	public function get_r_bk($customer_id)
	{
		$query = $this -> db -> query("
			SELECT sum(amount) as number FROM ".DB_PREFIX."customer_r_wallet_bk
			WHERE customer_id = '".$customer_id."'
		");
		return $query -> row;
	}

	public function get_ch_bk($customer_id)
	{
		$query = $this -> db -> query("
			SELECT sum(amount) as number FROM ".DB_PREFIX."customer_ch_wallet_bk
			WHERE customer_id = '".$customer_id."'
		");
		return $query -> row;
	}

	public function get_km_bk($customer_id)
	{
		$query = $this -> db -> query("
			SELECT sum(amount) as number FROM ".DB_PREFIX."customer_km_wallet_bk
			WHERE customer_id = '".$customer_id."'
		");
		return $query -> row;
	}

	public function get_ln_bk($customer_id)
	{
		$query = $this -> db -> query("
			SELECT sum(amount) as number FROM ".DB_PREFIX."customer_ln_wallet_bk
			WHERE customer_id = '".$customer_id."'
		");
		return $query -> row;
	}
	public function sum_total_pd_all()
	{
		$query = $this -> db -> query("
			SELECT SUM(total_pd) as total_pd
			FROM  ".DB_PREFIX."customer
		");
		return $query -> row['total_pd'];
	}
	public function getTotalcommission_refferal($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND ( wallet LIKE 'Hoa hồng trực tiếp' OR wallet LIKE 'Trả hoa hồng trực tiếp')
		");

		return $query -> row;
	}

	public function getallcommision_refferal($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND ( pd.wallet LIKE 'Hoa hồng trực tiếp' OR pd.wallet LIKE 'Trả hoa hồng trực tiếp' )
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotalcommission_conghuong($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND ( wallet LIKE 'Hoa hồng cộng hưởng' OR wallet LIKE 'Trả hoa hồng cộng hưởng')
		");

		return $query -> row;
	}

	public function getallcommision_conghuong($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Hoa hồng cộng hưởng' OR wallet LIKE 'Trả hoa hồng cộng hưởng')
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotalcommission_hoahong($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND ( wallet LIKE 'Hoa hồng trên thu nhập trực tiếp F1' OR wallet LIKE 'Trả hoa hồng trên thu nhập trực tiếp của F1' ) AND type = 0
		");

		return $query -> row;
	}

	public function getallcommision_hoahong($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Hoa hồng trên thu nhập trực tiếp F1' OR wallet LIKE 'Trả hoa hồng trên thu nhập trực tiếp của F1') AND type = 0
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotalcommission_doanhso($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Đồng chia tổng doanh số' OR wallet LIKE 'Trả đồng chia tổng doanh sô')
		");

		return $query -> row;
	}

	public function getallcommision_doanhso($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Đồng chia tổng doanh số' OR wallet LIKE 'Trả đồng chia tổng doanh sô')
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotalcommission_loinhuan($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Hoa hồng chia lợi nhuận chuỗi spa' OR wallet LIKE 'Trả hoa hồng chia lợi nhuận chuỗi spa')
		");

		return $query -> row;
	}

	public function getTotalcommission_dt($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Nhận ĐT từ sản phẩm của Spa' OR wallet LIKE 'Rút ĐT')
		");

		return $query -> row;
	}
	public function getallcommision_dt($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Nhận ĐT từ sản phẩm của Spa' OR wallet LIKE 'Rút ĐT')
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}
	public function getallcommision_loinhuan($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history AS pd
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' AND (wallet LIKE 'Hoa hồng chia lợi nhuận chuỗi spa' OR wallet LIKE 'Trả hoa hồng chia lợi nhuận chuỗi spa')
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotal_pnode($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_ml
			WHERE p_node = '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}

	public function getall_pnode($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_ml A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
				WHERE A.p_node = '".$this -> db -> escape($id_customer)."'
				ORDER BY B.date_added DESC
				LIMIT ".$limit."
				OFFSET ".$offset."
		");

		return $query -> rows;
	}
	public function get_dt_customer($customer_id)
	{
		$query = $this -> db -> query("
			SELECT amount 
			FROM  ".DB_PREFIX."customer_dt_wallet
			WHERE customer_id = '".$this -> db -> escape($customer_id)."'
		");

		return $query -> row['amount'];
	}
}
