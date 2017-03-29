<?php
class ModelAccountBlock extends Model {

	public function get_block_id($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_block_id
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function get_gd_cwallet_id($id_customer){
		$query = $this -> db -> query("
			SELECT amount, id
			FROM  ".DB_PREFIX."customer_get_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND status = 0 AND type = 0
		");
		return $query -> row;
	}
	public function get_gd_rwallet_id($id_customer){
		$query = $this -> db -> query("
			SELECT amount, id
			FROM  ".DB_PREFIX."customer_get_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND status = 0 AND type = 1
		");
		return $query -> row;
	}
	public function getLevel_by_customerid($customer_id){
		$query =  $this -> db -> query("
			SELECT level
			FROM " . DB_PREFIX . "customer_ml
			WHERE customer_id = '".$customer_id."'");
		return $query -> row;
	}
	public function update_block($customer_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id SET
			total = total + 1
			WHERE customer_id = '".(int)$customer_id."'");
		return $query;
	}
	public function update_block_status($customer_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id SET
			status = 0
			WHERE customer_id = '".(int)$customer_id."'");
		return $query;
	}
	public function update_C_Wallet($amount , $customer_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_c_wallet SET
			amount = amount - ".floatval($amount)."
			WHERE customer_id = '".$customer_id."'
		");
		return $query === true ? true : false;
	}
	public function update_GD_amount($amount , $customer_id, $id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_get_donation SET
			amount = amount - ".floatval($amount)."
			WHERE customer_id = '".$customer_id."' AND id= '".$id."'
		");
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

	// Block ID GD
	public function get_block_id_gd($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_block_id_gd
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}
	public function get_block_id_gd_list($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_block_id_gd
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> rows;
	}
	public function get_block_id_pd_list($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_block_id
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> rows;
	}
	public function get_total_block_id_gd($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) as total
			FROM  ".DB_PREFIX."customer_block_id_gd
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' AND status = 0
		");
		return $query -> row['total'];
	}
	public function get_total_block_id_pd($id_customer){
		$query = $this -> db -> query("
			SELECT total
			FROM  ".DB_PREFIX."customer_block_id
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row['total'];
	}
	public function update_block_gd($customer_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id_gd SET
			total = total + 1
			WHERE customer_id = '".(int)$customer_id."'");
		return $query;
	}
	public function update_block_id_gd($customer_id,$gd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id_gd SET
			status = 0
			WHERE customer_id = '".(int)$customer_id."' LIMIT 1");
		return $query;
	}
	public function update_block_status_gd($customer_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id_gd SET
			status = 0
			WHERE customer_id = '".(int)$customer_id."' AND status = 1  LIMIT 1");
		return $query;
	}
	public function update_check_block_gd($id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
			check_request_block = 1
			WHERE id = '".(int)$id."'");
		return $query;
	}
	public function update_check_gd($id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
			check_gd = 1
			WHERE id = '".(int)$id."'");
		return $query;
	}

	public function get_rp_gd_no_fn(){
		$date_now= date('Y-m-d H:i:s');
		$query_row = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE DATE_ADD(date_finish,INTERVAL 12 HOUR) <= '".$date_now."'
				  AND STATUS = 1 AND check_request_block = 0
		");
		return $query_row -> rows;
	}
	public function insert_block_id_gd($id_customer,$description,$id_gd){
		$date_now= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_block_id_gd SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			status = 1,
			description ='".$this -> db -> escape($description)."',
			date = '".$date_now."',
			id_gd ='" .$this -> db -> escape($id_gd). "'
		");
		return $query;
	}
	public function update_check_block_pd($id_customer,$description,$pd_id){
		$date_now= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id SET
			status = 1,
			description ='".$this -> db -> escape($description)."',
			date = '".$date_now."',
			pd_id ='" .$this -> db -> escape($pd_id). "'
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'");
		return $query;
	}

}