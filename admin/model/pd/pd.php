<?php
class ModelPdPd extends Model {

	public function get_all_gd_current_date($status){
		if ($status) {
			switch ($status) {
				case 1:
					$status = 0;
					break;
				case 2:
					$status = 1;
					break;
				default:
					$status = 2;
					break;
			}
			$query = $this->db->query("SELECT c.username, c.account_holder,c.account_number, c.bank_name, c.branch_bank,
				pd.*
			FROM ".DB_PREFIX."customer_provide_donation pd JOIN ".DB_PREFIX."customer  c
			ON pd.customer_id = c.customer_id WHERE date(pd.date_added)=CURRENT_DATE AND pd.status = ".$status."");
		return $query->rows;
		}else{
			$query = $this->db->query("SELECT  c.username, c.account_holder,c.account_number, c.bank_name, c.branch_bank,
				pd.*
			FROM ".DB_PREFIX."customer_provide_donation pd JOIN ".DB_PREFIX."customer  c
			ON pd.customer_id = c.customer_id WHERE date(pd.date_added)=CURRENT_DATE");
		return $query->rows;
		}
		
	}

	public function get_r_wallet() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_r_wallet WHERE amount > 0");
		return $query->rows;
	}
	public function update_r_wallet($customer_id) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "customer_r_wallet SET amount = 0 where customer_id = '".$customer_id."'");
		return $query;
	}
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT username, wallet FROM " . DB_PREFIX . "customer where customer_id = ".$customer_id."");
		return $query->row;
	}

	public function add_data_payment_blockchain($customer_id,$amount,$amount_fee,$wallet){
		$query = $this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_payment_blockhain SET 
			customer_id = '".$customer_id."',
			amount = '".$amount."',
			amount_fee ='".$amount_fee."',
			wallet = '".$wallet."'
			
		");
		return $query;
	}
	public function get_tbl_payment_blockchain() {
		$query = $this->db->query("SELECT c.username, p.* FROM ".DB_PREFIX."customer_payment_blockhain p JOIN " . DB_PREFIX . "customer c
			ON p.customer_id = c.customer_id WHERE amount > 0");
		return $query->rows;
	}



}