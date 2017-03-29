<?php
class ModelAccountPd extends Model {
	
	public function getAllInvoiceByCustomer($customer_id, $limit, $offset){
		$query = $this -> db -> query("
			SELECT amount, received, confirmations, date_created, transfer_id, input_address
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."'
			ORDER BY date_created DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}
 
	public function getAllInvoiceByCustomer_notCreateOrder($customer_id){
		$query = $this -> db -> query("
			SELECT amount, received, confirmations, date_created, transfer_id, input_address
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."' AND confirmations = 0
			ORDER BY date_created DESC
		");
		return $query -> rows;
	}
	public function createTransferList10Percent($data){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '+ 72 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;

		$this -> db -> query("
				INSERT INTO ". DB_PREFIX . "customer_transfer_list SET 
				pd_id = '".$data["pd_id"]."',
				gd_id = '".$data["gd_id"]."',
				pd_id_customer = '".$data["pd_id_customer"]."',
				gd_id_customer = '".$data["gd_id_customer"]."',
				
				date_added = NOW(),
				date_finish = ''".$date_finish."'',
				amount = '".$data["amount"]."',
				pd_satatus = 0,
				gd_status = 0
				
			");
		return $this->db->getLastId();
	}

	public function getAllInvoiceByCustomerTotal($customer_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."'
		");
		return $query -> row;
	}
	public function getInvoceFormHash($invoice_id_hash, $customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE invoice_id_hash = '".$invoice_id_hash."' and customer_id = '". $customer_id ."'
		");
		return $query -> row;
	}

	public function countPD($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $id_customer ."' AND confirmations = 0
		");
		return $query -> row;
	}
	public function countTransferID($transfer_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE transfer_id = '". $transfer_id ."'
		");
		return $query -> row;
	}

	public function updateInaddressAndFree($invoice_id, $invoice_id_hash , $input_addr, $fee_percent, $my_addr){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			input_address = '".$input_addr."',
			fee_percent = ".$fee_percent.",
			my_address = '".$my_addr."',
			invoice_id_hash = '".$invoice_id_hash."'
			WHERE invoice_id = ".$invoice_id."");
		return $query;
	}

	public function updateConfirm($invoice_id_hash, $confirmations,$transaction_hash,$input_transaction_hash){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			confirmations = ".$confirmations.",
			transaction_hash = '".$transaction_hash."',
			input_transaction_hash = '".$input_transaction_hash."'
			WHERE invoice_id_hash = ". $invoice_id_hash."");
		return $query;
	}

	public function updateReceived($received, $invoice_id_hash){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			received = received + '" . $received . "'
			WHERE invoice_id_hash = '" . $invoice_id_hash . "'");
		return $query;
	}

	public function getPD($id){
		$query = $this -> db -> query("
			SELECT * 
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = ".$id."
		");
		return $query -> row;
	}

	public function updateDatefinishPD($pd_id,$max_profit){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
				status = '1',
				date_finish = DATE_ADD(NOW(),INTERVAL + 20 DAY),
				date_update_profit = DATE_ADD(NOW(),INTERVAL + 1 DAY),
				max_profit = '".$max_profit."'
				WHERE id = '".$pd_id."'
			");
	}

	public function saveHistoryPin($id_customer, $amount, $user_description, $type , $system_description){
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "ping_history SET
			id_customer = '" . $this -> db -> escape($id_customer) . "',
			amount = '" . $this -> db -> escape( $amount ) . "',
			date_added = NOW(),
			user_description = '" .$this -> db -> escape($user_description). "',
			type = '" .$this -> db -> escape($type). "',
			system_description = '" .$this -> db -> escape($system_description). "'
		");
		return $this -> db -> getLastId();
	}

	public function saveInvoice($customer_id, $secret, $amount, $pd_id){
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."customer_invoice_pd SET
			customer_id = '".$customer_id."',
			secret = '".$secret."',
			amount = ".$amount.",
			transfer_id = '".$pd_id."',
			received = 0,
			date_created = NOW()
		");

		return $query === True ? $this->db->getLastId() : -1;
	}

	public function getInvoiceByIdAndSecret($invoice_id_hash, $secret, $input_address){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE invoice_id_hash = '". $invoice_id_hash ."' AND 
				  secret = '".$secret."' AND
				  confirmations = 0 AND
				  input_address = '".$input_address."'
		");
		return $query -> row;
	}

	public function getPDNow($id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$this->db->escape($id)."'
		");
		return $query -> row;
	}
	public function getGDBefore(){
		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation 
			WHERE status IN (0,1) AND filled < amount AND customer_id <> ".$this -> customer -> getId()."
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}
	// date_added <= DATE_ADD(NOW(), INTERVAL -10 DAY)
	// 			  AND 
	public function getCustomerInventory(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 9 AND customer_id <> '".$this -> customer -> getId()."'
			ORDER BY date_added ASC 
			LIMIT 1
		");
		return $query -> row;
	}
	public function createGDInventory($amount, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET 
			customer_id = '".$customer_id."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW(),INTERVAL +36 HOUR),
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
		if($query){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET 
				date_added = NOW()
				WHERE customer_id = '".$customer_id."'
			");
		}
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		$data['gd_id'] = $gd_id;
		return $data;
	}
	public function createTransferList($data){
		// transfer_code = '".hexdec( crc32($data["gd_id"]) )."',
		$this -> db -> query("
				INSERT INTO ". DB_PREFIX . "customer_transfer_list SET 
				pd_id = '".$data["pd_id"]."',
				gd_id = '".$data["gd_id"]."',
				pd_id_customer = '".$data["pd_id_customer"]."',
				gd_id_customer = '".$data["gd_id_customer"]."',
				date_added = NOW(),
				date_finish = DATE_ADD(NOW() , INTERVAL +1 DAY),
				amount = '".$data["amount"]."',
				pd_satatus = 0,
				gd_status = 0

			");
		return $this->db->getLastId();
	}

	public function updateTransferList($transfer_id){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer_transfer_list SET 
			transfer_code = '".hexdec( crc32($transfer_id) )."'
			WHERE id = ".$transfer_id."
		");

		return $query;
	}
	
	public function updateTotalAmountPD($pd_id , $amount){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
			amount = amount + ".$amount." 
			WHERE id = '".$pd_id."'
		");
	}
	public function updateFilledGD($gd_id , $filled){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET 
			filled = filled + '".$filled."' 
			WHERE id = '".$gd_id."'
		");
	}
	public function updateStatusGD($gd_id , $status){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_get_donation SET 
			status = '".$status."'
			WHERE id = '".$gd_id."'
		");
	}
	public function updateStatusPD($pd_id , $status){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_provide_donation SET 
			status = '".$status."' 
			WHERE id = '".$pd_id."'
		");
	}
	public function CountDayPD($id){
		$query = $this -> db -> query("
			SELECT date_finish
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$this->db->escape($id)."' and date_finish <= NOW()
		");
		return $query -> row;
	}

	
}