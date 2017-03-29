<?php
class ModelAccountAuto extends Model {


	public function getAllCustomer_Binary(){
		$query = $this -> db -> query("
			SELECT c.customer_id AS customer_id, c.total_pd_left , c.total_pd_right FROM ". DB_PREFIX . "customer c
			JOIN ". DB_PREFIX ."customer_ml AS c_ml
				ON c.customer_id = c_ml.customer_id
			WHERE c_ml.level = 2 AND c.total_pd_left > 0 AND c.total_pd_right > 0
		");
		return $query -> rows;
	}

	public function update_Left_Count($customer_id){

		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			SET total_pd_left = total_pd_left - total_pd_right,
				total_pd_right = total_pd_right - total_pd_right
			WHERE customer_id = ".$customer_id."
		");
		return $query;
	}

	public function update_Right_Count($customer_id){

		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			SET total_pd_right = total_pd_right - total_pd_left,
				total_pd_left = total_pd_left - total_pd_left
			WHERE customer_id = ".$customer_id."
		");
		return $query;
	}

	public function getMaxPd($customer_id){
		$query = $this -> db -> query("
			SELECT MAX(filled) AS pd_max FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = ".$customer_id."
		");
		return $query -> row;
	}
	public function getMaxLevel($customer_id){
		$query = $this -> db -> query("
			SELECT MAX(level) AS max_level FROM ". DB_PREFIX . "customer_ml
			WHERE customer_id IN (".$customer_id.")
		");
		return $query -> row['max_level'];
	}
	public function updateTransferList($transfer_id){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer_transfer_list SET 
			transfer_code = '".hexdec( crc32($transfer_id) )."'
			WHERE id = ".$transfer_id."
		");

		return $query;
	}
	public function createGDInventory($amount, $customer_id){

		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = '".$customer_id."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW(),INTERVAL - 10 DAY),
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
		return $data;
	}

	public function createPDInventory($filled, $customer_id){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '- 40 day' , strtotime ($date_added));
		$date= date('Y-m-d H:i:s',$date_finish) ;
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET
			customer_id = '".$customer_id."',
			date_added = '".$date_finish."',
			date_finish = '".$date."',
			filled = '".$filled."',
			amount = 0,
			status = 0
		");
		$amount	= 7700000;

		$gd_id = $this->db->getLastId();

		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				pd_number = '".$gd_number."',
				max_profit = '".$amount."'
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
		return $data;
	}
	public function createPD($amount, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET
			customer_id = '".$customer_id."',
			date_added = NOW(),
			filled = '".$amount."',
			date_finish = DATE_ADD(NOW(),INTERVAL +1 DAY),
			date_finish_forAdmin = DATE_ADD(NOW(),INTERVAL +1 DAY),
			status = 0,
			check_R_Wallet =1
		");
		//update max_profit and pd_number
		$pd_id = $this->db->getLastId();

		//$max_profit = (float)($amount * $this->config->get('config_pd_profit')) / 100;
		$max_profit = 3000000;
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



	public function getGD7Before(){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE date_finish <= '".$date_added."' AND customer_id NOT IN (SELECT customer_id FROM ". DB_PREFIX . "customer WHERE status = 8)
			AND status = 0 ORDER BY date_added ASC LIMIT 1
		");
		return $query -> row;
	}

	public function getPDAmount($iod_customer){
		$query = $this -> db -> query("
			SELECT amount
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."'
		");
		return $query -> row;
	}
	public function getGD_for_admin(){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '-36 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;

		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE date_finish <= '".$date_added."'  AND customer_id NOT IN (SELECT customer_id FROM ". DB_PREFIX . "customer WHERE status = 8 OR quy_bao_tro = 1)
				  AND status = 0
			ORDER BY date_added ASC
			
		");
		return $query -> rows;
	}
	public function getPD7Before(){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '- 30 day' , strtotime ($date_added));
		$date_finish = date('Y-m-d H:i:s',$date_finish) ;

		$query = $this -> db -> query("
			SELECT id , customer_id , amount , filled
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_finish <= '".$date_added."' AND customer_id NOT IN (SELECT customer_id FROM ". DB_PREFIX . "customer WHERE status = 8)
			AND STATUS =0
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}

	public function getCustomerInventory(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE quy_bao_tro = 1
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}
	public function get_customer_earn_insurance_fund(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 9
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}

	public function getCustomerALLInventory(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 9
		");
		return $query -> rows;
	}
	public function getUser(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_tmp
		");
		return $query -> rows;
	}

	public function updateStatusPD($pd_id , $status){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '+72 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_provide_donation SET
			status = '".$status."',date_finish = '".$date_finish."'
			WHERE id = '".$pd_id."'
		");
	}
	public function update_date_finish_rp($pd_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_provide_donation SET
			date_finish = NOW()
			WHERE id = '".$pd_id."'
		");
	}
	public function updateCycleAdd($pd_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_provide_donation SET
			cycle = cycle + 1
			WHERE id = '".$pd_id."'
		");
	}
	public function updateCycleAddCustomer($pd_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			cycle = cycle + 1
			WHERE customer_id = '".$pd_id."'
		");
	}
	public function ResetCycleAddCustomer($pd_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			cycle = 1
			WHERE customer_id = '".$pd_id."'
		");
	}

	public function updateFilledPD($pd_id , $amount, $profit){

		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
			filled = '".$amount."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW(),INTERVAL +20 DAY),
			date_finish_forAdmin = DATE_ADD(NOW(),INTERVAL +1 DAY),
			status = 1,
			max_profit = '".$profit."',
			date_update_profit = DATE_ADD(NOW(),INTERVAL + 1 DAY)
			WHERE id = '".$pd_id."'
		");
	}

	public function updateAmountPD($pd_id , $amount){
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
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '+72 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_get_donation SET
			status = '".$status."',date_finish = '".$date_finish."'
			WHERE id = '".$gd_id."'
		");
		
	}


	public function createTransferList($data){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '+72 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_transfer_list SET
			pd_id = '".$data["pd_id"]."',
			gd_id = '".$data["gd_id"]."',
			pd_id_customer = '".$data["pd_id_customer"]."',
			gd_id_customer = '".$data["gd_id_customer"]."',
			transfer_code = '".hexdec( crc32($data["gd_id"]) )."',
			date_added = '".$date_added."',
			date_finish = '".$date_finish."',
			amount = '".$data["amount"]."',
			pd_satatus = 0,
			gd_status = 0
		");
		return $this->db->getLastId();
	}
	public function getAllPD(){
		$query = $this -> db -> query("
			SELECT ctl.* , c.p_node, pd.check_R_Wallet as checkRWallet, pd.filled, pd.max_profit as max, pd.status as pdstatus, pd.pd_number as pd_number
			FROM ". DB_PREFIX . "customer_provide_donation AS pd
			JOIN ". DB_PREFIX ."customer_transfer_list AS ctl
				ON ctl.pd_id = pd.id
			JOIN ". DB_PREFIX ."customer AS c
				ON c.customer_id = pd.customer_id
			WHERE ctl.date_finish <= NOW() AND pd.status <> 3
		");

		return $query -> rows;
	}
	public function getCusIdByPdID($pd_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transfer_list
			WHERE pd_id = '".$this -> db -> escape($pd_id)."'
		");
		return $query -> row;
	}

	public function updatePDcheck_R_Wallet($pd_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_provide_donation SET
			check_R_Wallet = 0
			WHERE id = '".$pd_id."'
		");
		return $query === true ? true : false;
	}
	public function update_R_Wallet($amount , $customer_id){

		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_r_wallet SET
			amount = amount + ".intval($amount)."
			WHERE customer_id = '".$customer_id."'
		");

		return $query === true ? true : false;
	}
	public function update_C_Wallet($amount , $customer_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_c_wallet SET
			amount = amount + ".intval($amount)."
			WHERE customer_id = '".$customer_id."'
		");
	}

	public function updateStatusCustomer($customer_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			status = 8 WHERE customer_id = '".$customer_id."'
		");
	}
	public function updateStatusPDTransferList($transferID){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 2,
				gd_status = 2
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	public function updateConfirmation($transferID){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
				confirmations = 4
				WHERE transfer_id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}


	public function getPD20Before(){

		$query = $this -> db -> query("
			SELECT id , customer_id , amount , filled,pd_number, cycle
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_finish >=  NOW() AND date_update_profit <= NOW()
			AND STATUS = 1	and check_withdrawal = 0
		");
		return $query -> rows;
	}
	public function updateMaxProfitPD($pd_id , $amount){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
			max_profit = max_profit + ".$amount.",
			date_update_profit = DATE_ADD(NOW(),INTERVAL + 1 DAY)
			WHERE id = '".$pd_id."'
		");
	}
	public function ResetMaxProfitPD($pd_id){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
			max_profit = 0
			WHERE id = '".$pd_id."'
		");
	}

	public function getDayFnPD(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_finish <=  NOW() AND status = 2 AND check_return_profit = 0
		");

		return $query -> rows;
	}
	public function getDayCreatePD(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_finish <= DATE_ADD(NOW(),INTERVAL - 1 DAY) AND status = 2 AND check_withdrawal = 0
		");

		return $query -> rows;
	}
	public function getcheckWithdrawalRwallet($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_r_wallet
			WHERE customer_id = '".$customer_id."'
		");
		return $query -> row;
	}
	public function Reset_R_Wallet($customer_id,$amount){

		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_r_wallet SET
			amount = amount - ".intval($amount)."
			WHERE customer_id = '".$customer_id."'
		");

		return $query === true ? true : false;
	}
	public function auto_find_pd_update_status_report(){
		
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer_provide_donation SET
			status = 3,
			date_added = DATE_ADD(NOW(),INTERVAL -15 DAY),
			date_finish = NOW()
			WHERE date_finish <= NOW()
				  AND STATUS =1
		");
		return $query === true ? true : false;
	}
	public function get_rp_pd(){
		$query_row = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_finish <= NOW()
				  AND STATUS =1
		");
		return $query_row -> rows;
	}
	public function get_gd_notcf(){
		$query_row = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE date_finish <= NOW()
				  AND STATUS =1
		");
		return $query_row -> rows;
	}
	public function getM_Wallet(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_m_wallet
			WHERE date <= NOW() and amount > 0
		");
		return $query -> rows;
	}
	public function update_M_Wallet($amount , $customer_id, $add = false){
		if(!$add){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_m_wallet SET
				amount = amount - ".intval($amount)."
				WHERE customer_id = '".$customer_id."'
			");
			return $query === true ? true : false;
		}

	}
/*-------------------------------------------------------------------------------------------------*/
	public function check_user_PD(){
		$customer = $this -> db -> query("
			SELECT customer_id
			FROM ". DB_PREFIX . "customer AS A
			WHERE check_Newuser = 1 
				AND date_auto <= NOW() 
				AND A.status <> 8 
				AND A.account_number <> 0 
				AND A.customer_id <> 1
				AND A.quy_bao_tro = 0
				AND customer_id NOT IN (SELECT customer_id FROM ". DB_PREFIX . "customer_provide_donation AS B WHERE A.customer_id = B.customer_id AND B.status <> 2)
		");
		return $customer -> rows;
	}

	public function create_PD($amount, $customer_id,$max_profit){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET
			customer_id = ".intval($customer_id).",
			date_added = NOW(),
			amount = '".$amount."',
			date_finish = DATE_ADD(NOW(),INTERVAL +5 DAY),
			date_finish_forAdmin = DATE_ADD(NOW(),INTERVAL + 72 HOUR),
			status = 1,
			max_profit = '".$max_profit."'
		");

		return $this -> db -> getLastId();
	}

	public function update_pd_number($pd_id, $pd_number){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
			pd_number = '".$pd_number."'
			WHERE id = '".$pd_id."'
		");
		return $query;
	}

	public function update_pin($customer_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer SET
			ping = ping - 1
			WHERE customer_id = '".$customer_id."'
		");
		return $query;
	}

	public function get_gd_quy_bo_tro(){
		$query = $this -> db -> query("SELECT * FROM ". DB_PREFIX . "customer WHERE quy_bao_tro = 1 ORDER BY date_cycle ASC LIMIT 1");
		return $query -> row;
	}
	public function update_date_last($customer_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer SET
			date_cycle = NOW()
			WHERE customer_id = '".$customer_id."'
		");
		return $query;
	}
	public function create_GD($customer_id,$amount,$filled){
		$query = $this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = ".intval($customer_id).",
			amount = '".$amount."',
			date_added = NOW(),
			status = 1,
			filled = '".$filled."'
		");
		$gd_id = $this->db->getLastId();
		$gd_number = hexdec(crc32($gd_id));
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				gd_number = '".$gd_number."'
				WHERE id = '".$gd_id."'
			");
		return $gd_id;
	}

	public function create_tranfer_list($pd_id,$gd_id,$pd_id_customer,$gd_id_customer,$amount,$pd_satatus,$gd_status){
		$query = $this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_transfer_list SET
			pd_id = ".intval($pd_id).",
			gd_id = ".intval($gd_id).",
			pd_id_customer = ".intval($pd_id_customer).",
			gd_id_customer = ".intval($gd_id_customer).",
			date_added = DATE_ADD(NOW(),INTERVAL + 13 hour),
			date_finish = DATE_ADD(NOW(),INTERVAL + 133 hour),
			amount = '".$amount."',
			pd_satatus = '".$pd_satatus."',
			gd_status = '".$gd_status."'

		");
		$id = $this->db->getLastId();
		$transfer_code = hexdec(crc32($id));
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				transfer_code = '".$transfer_code."'
				WHERE id = '".$id."'
			");
	}

	public function getPD_all($id_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = ".$id_customer."
		");
		return $query -> row;
	}
	public function getGD_all($id_gd){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE id = ".$id_gd."
		");
		return $query -> row;
	}



	public function new_user_pd(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE check_Newuser = 1 AND cycle = 1 AND quy_bao_tro = 0 AND customer_id <> 1 AND date_auto <= NOW()
		");
		return $query -> rows;
	}

	public function updateCryle($customer_id){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			SET cycle = cycle + 1
			WHERE customer_id = ".$customer_id."
		");
		return $query;
	}

	public function getPD_all24h(){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_finish <= NOW()
		");
		return $query -> rows;
	}

	public function getGD_all24h(){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE status = '0'
		");
		return $query -> rows;
	}

	public function get_admin_PD(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer WHERE status = '8' ORDER BY date_cycle ASC LIMIT 1 
		");
		return $query -> row;
	}

	public function update_date_cycle($customer_id){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			SET date_cycle = NOW()
			WHERE customer_id = ".$customer_id."
		");
		return $query;
	}
	public function createTransferList_new($data){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_transfer_list SET
			pd_id = '".$data["pd_id"]."',
			gd_id = '".$data["gd_id"]."',
			pd_id_customer = '".$data["pd_id_customer"]."',
			gd_id_customer = '".$data["gd_id_customer"]."',
			transfer_code = '".hexdec( crc32($data["gd_id"]) )."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW() , INTERVAL +1 DAY),
			amount = '".$data["amount"]."',
			pd_satatus = 0,
			gd_status = 0
		");
	}

	public function get_GD_auto(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transfer_list
			WHERE date_finish <= NOW() AND gd_status = 2 AND pd_satatus = 1 AND input_transaction_hash <> '1'
		");
		return $query -> rows;
	}
	public function createGD($customer_id,$amount){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = '".$customer_id."',
			date_added = NOW(),
			amount = '".$amount."',
			status = 0,
			date_finish = DATE_ADD(NOW() , INTERVAL +1 DAY)
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
		return $data;
	}
	public function update_status_gdpd($id_tranfer)
	{
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				input_transaction_hash = '1'
				WHERE id = '".$id_tranfer."'
			");
		return $query;
	}

	public function getPD_GD(){
		$query = $this -> db -> query("
			SELECT A.*,B.quy_bao_tro
			FROM  ".DB_PREFIX."customer_get_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE A.status = 0 AND A.check_gd <> 1 AND B.quy_bao_tro <> 1
		");
		return $query -> rows;
	}

	public function update_check_gd($id){
		
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				check_gd = '1'
				WHERE id = '".$id."'
			");
		return $query;
	}
	public function get_PD_finish(){
		$date_finish= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation 
			WHERE status = 2 AND date_finish <= '".$date_finish."' AND check_R_Wallet = 1
		");
		return $query -> rows;
	}
	public function get_PD_finish_thuong(){
		$date_added = date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			SELECT A.*,B.username	
			FROM  ".DB_PREFIX."customer_provide_donation A INNER JOIN ".DB_PREFIX."customer B ON A.customer_id = B.customer_id
			WHERE A.status = 2 AND A.date_finish <= '".$date_added."' AND A.check_return_profit = 0
		");
		return $query -> rows;
	}
	public function update_PD_finish_thuong($id){
		
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				check_return_profit = '1'
				WHERE id = '".$id."'
			");
		return $query;
	}
	public function getusername($customer_id){
		$query = $this -> db -> query("
			SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '".$customer_id."'");
		return $query -> row;
	}
	public function re_pd(){
		$date_added= date('Y-m-d H:i:s');
		$date_finish = strtotime ( '-48 hour' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_get_donation 
			WHERE status = 2 AND date_finish <= '".$date_finish."' AND check_gd = 0  
			
		");
		return $query -> rows;
	}

	public function getGD_repd($customer_id){
		$date_added= date('Y-m-d H:i:s');
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = ".$customer_id." AND status <> 2 AND date_finish >= '".$date_added."'  order by id desc limit 1
		");
		return $query -> row;
	}

	public function update_status_customer($customer_id){
		
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
				status = '8'
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}
	public function update_lock_customer($customer_id){
		
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id SET
				status = 1
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}
	public function update_lock2_customer($customer_id){
		
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_block_id SET
				status = 2
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}
	
	public function getCustomOfNode($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.p_node AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.customer_id = ". $id_user."");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['code'];
			$listId .= $this -> getCustomOfNode($item['code']);
		}
		return $listId;
	}
	public function get_customer_update_level($customer_id){
		
		$query = $this -> db -> query("
			SELECT customer_id
			FROM ". DB_PREFIX . "customer
			WHERE customer_id IN (".$customer_id.")
		");
		return $query -> rows;
	}
}