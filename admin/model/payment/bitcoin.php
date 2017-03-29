<?php
class ModelPaymentBitcoin extends Model {

	public function get_payment_bitcoin(){
		$sql = "
			SELECT COUNT(*) AS number FROM  `payment_blockchian` 
		";

		$query = $this->db->query($sql);

		return $query -> row['number'];

	}

	// public function set_bitcoin_wallet(){
	// 	$sql = "
	// 		INSERT INTO payment_blockchian (customer_id, wallet , username, c_wallet, r_wallet, total_fee, total_send)
	// 		SELECT u.customer_id , 
	// 				u.wallet,
	// 		       u.username, 
	// 		       c.amount AS c_wallet, 
	// 		       r.amount AS r_wallet , 
	// 		       ((c.amount + r.amount) - ((c.amount + r.amount) * 0.05 ) ) AS total_fee,
	// 		       (((c.amount + r.amount) - ((c.amount + r.amount) * 0.04 ) ) + 20000) AS total_send
	// 		FROM sm_customer u  
	// 		JOIN sm_customer_r_wallet r 
	// 		     ON u.customer_id = r.customer_id 
	// 		JOIN sm_customer_c_wallet c ON c.customer_id = u.customer_id
	// 		WHERE c.amount > 0 OR r.amount > 0 AND (((c.amount + r.amount) + (c.amount + r.amount) * 0.01) + 40000) >= 1250000
	// 		GROUP BY u.customer_id
	// 	";

	// 	$query = $this->db->query($sql);

	// 	return $query;
	// }

	// ================   
// INSERT INTO payment_blockchian (customer_id, wallet , username, r_wallet, total_fee, total_send)
// 			SELECT u.customer_id , 
// 					u.wallet,
// 			       u.username, 
// 			       p.filled  AS r_wallet , 
// 			       ((p.filled * 0.025 ) - ((p.filled * 0.025) * 0.03 ) )  AS total_fee,
// 			       (((p.filled * 0.025) - ((p.filled * 0.025) * 0.02 ) ) + 20000)  AS total_send
// 			FROM sm_customer u  
// 			JOIN sm_customer_r_wallet r 
// 			     ON u.customer_id = r.customer_id 
// 			JOIN sm_customer_provide_donation p ON p.customer_id = u.customer_id
// 			JOIN sm_customer_c_wallet c ON c.customer_id = u.customer_id
// 			WHERE p.status = 1
	// sum(((c.amount + (p.filled * 0.025 )) - ((c.amount + (p.filled * 0.025 )) * 0.03 ) )) AS total_fee,
	// 		       sum((((c.amount + (p.filled * 0.025 ))- ((c.amount + (p.filled * 0.025 )) * 0.02 ) ) + 20000))  AS total_send

// UPDATE `payment_blockchian` 
// SET `total_fee` = ((`c_wallet` + (`r_wallet` * 0.025 )) - ((`c_wallet` + (`r_wallet` * 0.025 )) * 0.03 ) ) , 
// `total_send` = (((`c_wallet` + (`r_wallet` * 0.025 ))- ((`c_wallet` + (`r_wallet` * 0.025 )) * 0.02 ) ) + 20000)

	public function update_bitcoin_wallet(){
		# code...
		$sql = "
			UPDATE `payment_blockchian` 
			SET `total_fee` = ((`c_wallet` + (`r_wallet` * 0.025 )) - ((`c_wallet` + (`r_wallet` * 0.025 )) * 0.03 ) ) , 
			`total_send` = (((`c_wallet` + (`r_wallet` * 0.025 ))- ((`c_wallet` + (`r_wallet` * 0.025 )) * 0.02 ) ) + 20000)
		";
		$query = $this->db->query($sql);

		return $query;
	}
	public function set_bitcoin_wallet(){
		$sql = "
			INSERT INTO payment_blockchian (customer_id, wallet , username, c_wallet, r_wallet)
			SELECT u.customer_id , 
					u.wallet,
			       u.username, 
			       sum(c.amount) / count(u.customer_id) AS c_wallet,
			       sum(p.filled)  AS r_wallet 
			       
			FROM sm_customer u  
			JOIN sm_customer_r_wallet r ON u.customer_id = r.customer_id 
			JOIN sm_customer_provide_donation p ON p.customer_id = u.customer_id
			JOIN sm_customer_c_wallet c ON c.customer_id = u.customer_id
           
			WHERE p.status = 1
            GROUP BY  u.customer_id

			
		";

		$query = $this->db->query($sql);

		return $query;
	}

	// ===============
	public function set_bitcoin_wallet_bk(){
		$sql = "
			INSERT INTO payment_blockchian_backup (customer_id, wallet , username, c_wallet, r_wallet, total_fee, total_send, date_created)

			SELECT u.customer_id , 
					u.wallet,
			       u.username, 
			       c.amount AS c_wallet,
			       p.filled  AS r_wallet , 
			       
			       ((c.amount + (p.filled * 0.025 )) - ((c.amount + (p.filled * 0.025 )) * 0.03 ) )  AS total_fee,
			       (((c.amount + (p.filled * 0.025 ))- ((c.amount + (p.filled * 0.025 )) * 0.02 ) ) + 20000)  AS total_send,
			       NOW() as date_created
			FROM sm_customer u  
			JOIN sm_customer_r_wallet r ON u.customer_id = r.customer_id 
			JOIN sm_customer_provide_donation p ON p.customer_id = u.customer_id
			JOIN sm_customer_c_wallet c ON c.customer_id = u.customer_id
			WHERE p.status = 1
		";

		$query = $this->db->query($sql);

		return $query;
	}


	public function update_table_timer_update_wallet($forwarding_private_key, $input_address, $my_address, $amount){

		$sql = "
			INSERT INTO payment_blockchian_wallet
			SET forwarding_private_key = '".$forwarding_private_key."',
				input_address = '".$input_address."',
				my_address = '".$my_address."',
				amount = '".$amount."'
		";

		$query = $this->db->query($sql);
	}

	public function update_wallet_timer($wallet){
		$sql = "
			UPDATE payment_blockchian_timer
			SET wallet = '".$wallet."'
			WHERE id = 1
		";

		$query = $this->db->query($sql);
	}

	public function get_amount_total(){
		$sql = "
			SELECT sum(total_send) as total
			FROM payment_blockchian
		";
		$query = $this->db->query($sql);

		return $query -> row;
	}

	public function get_bitcoin_wallet($limit, $offset){
		$sql = "
			SELECT *
			FROM payment_blockchian
			LIMIT ".$limit."
			OFFSET ".$offset."
		";

		$query = $this->db->query($sql);

		return $query -> rows;
	}

	public function updateTimer(){
		$query = $this->db->query("
			UPDATE payment_blockchian_timer
			SET timer = DATE_ADD(NOW(),INTERVAL +1 DAY)
			WHERE id = 1
		");
		return $query;
	}

	public function getTimer(){
		$query = $this->db->query("
			SELECT count(*) as number
			FROM payment_blockchian_timer
			WHERE id = 1 AND timer <= NOW()
		");
		return $query -> row['number'];
	}

	public function getTimer_Wallet(){
		$query = $this->db->query("
			SELECT wallet
			FROM payment_blockchian_timer
			WHERE id = 1 
		");
		return $query -> row['wallet'];
	}
}