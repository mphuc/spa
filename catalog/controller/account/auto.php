<?php

class ControllerAccountAuto extends Controller {

	public function auto_create_pd_new_user(){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$users = $this -> model_account_auto -> new_user_pd();

		foreach ($users as $key => $value) {
			$amount = 2000000;
			$max_profit = 3000000;
			$customer_id = $value['customer_id'];

			$pd_ID = $this -> model_account_auto-> create_PD($amount, $value['customer_id'] , $max_profit);
		
			$pd_number = hexdec( crc32($pd_ID) );

			$this -> model_account_auto-> update_pd_number($pd_ID, $pd_number);

			$quy_bo_tro = $this -> model_account_auto ->get_gd_quy_bo_tro();

			//update date quy_bao_tro theo vong

			$this -> model_account_auto -> update_date_last(intval($quy_bo_tro['customer_id']));

			$id_gd = $this -> model_account_auto -> create_GD($quy_bo_tro['customer_id'], $amount, $amount);

			$getPD = $this -> model_account_auto -> getPD_all($customer_id);


			$getGD = $this -> model_account_auto -> getGD_all($id_gd);

			$this -> model_account_auto -> create_tranfer_list(
				$getPD['id'],$getGD['id'],
				$getPD['customer_id'],
				$getGD['customer_id'],
				$getPD['amount'],
				$getPD['status'],
				$getGD['status']
			);

			$this -> model_account_auto -> updateCryle($customer_id, 2);

			$title = "PD - Cho Leader";
			$sub = $value['username'] ." PD - Cho " .$quy_bo_tro['username'];

			$mess = "ID [".$value['username'] ."] đã khớp lệnh với [". $quy_bo_tro['username']."] mời vào website để xem hóa đơn của người PH - Cho";

			$this -> emailQuyBaoTro($title  , $sub , $mess);

		}
		
	}


	function emailQuyBaoTro($title ,$sub, $mess){
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo('phucnguyen@icsc.vn');
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($sub);
		$mail->setText($mess);
		$mail->send();
	}


	public function autoPDGD() {
		$this -> load -> model('account/auto');
		$this -> load -> model('customize/register');
		$this -> load -> model('account/pd');
		$this -> load -> model('account/customer');

		$loop = true;
		
		// $count = 0;
		$i=1;
		while ($loop) {

			$gdList = $this -> model_account_auto -> getGD7Before();
			// echo "<pre>"; print_r($gdList); echo "</pre>"; die();
			$pdList = $this -> model_account_auto -> getPD7Before();
		
			if(count($gdList) === 0 && count($pdList) > 0){

				//get customer in inventory
				$inventory = $this -> model_account_auto ->getCustomerInventory();
				
				
				$pdSend = floatval($pdList['filled'] - $pdList['amount']);

				$inventoryID = $inventory['customer_id'];

				//create GD cho inventory
				$this -> model_account_auto -> createGDInventory($pdSend, $inventoryID);
				// continue;
				
			}

			if(count($pdList) === 0 && count($gdList) > 0){

				$gdResiver = floatval($gdList['amount'] - $gdList['filled']);

				$inventory = $this -> model_account_auto ->getCustomerInventory();

				$inventoryID = $inventory['customer_id'];

				$this -> model_account_auto -> createPDInventory($gdResiver, $inventoryID);
				// continue;
				// die('2');
			}
			
			if (count($pdList) === 0 && count($gdList) === 0) {
				
				$loop = false;
				break;
			}

			if ($pdList && $gdList) {

				$pdSend = intval($pdList['filled'] - $pdList['amount']);
				$gdResiver = intval($gdList['amount'] - $gdList['filled']);

				if ($pdSend === $gdResiver) {

					$data['pd_id'] = $pdList['id'];
					$data['gd_id'] = $gdList['id'];
					$data['pd_id_customer'] = $pdList['customer_id'];
					$data['gd_id_customer'] = $gdList['customer_id'];
					$data['amount'] = $pdSend;
					$id_transfer = $this -> model_account_auto -> createTransferList($data);
					$this -> model_account_auto -> updateTransferList($id_transfer);
					
					$this -> model_account_auto -> updateStatusPD($pdList['id'], 1);
					$this -> model_account_auto -> updateStatusGD($gdList['id'], 1);
					$this -> model_account_auto -> updateAmountPD($pdList['id'], $pdSend);
					$this -> model_account_auto -> updateFilledGD($gdList['id'], $pdSend);
					continue;
				}

				if ($pdSend < $gdResiver) {
					$data['pd_id'] = $pdList['id'];
					$data['gd_id'] = $gdList['id'];
					$data['pd_id_customer'] = $pdList['customer_id'];
					$data['gd_id_customer'] = $gdList['customer_id'];
					$data['amount'] = $pdSend;
					$id_transfer = $this -> model_account_auto -> createTransferList($data);
					$this -> model_account_auto -> updateTransferList($id_transfer);
					$this -> model_account_auto -> updateStatusPD($pdList['id'], 1);
					$this -> model_account_auto -> updateAmountPD($pdList['id'], $pdSend);
					$this -> model_account_auto -> updateFilledGD($gdList['id'], $pdSend);
					continue;

				}

				if ($pdSend > $gdResiver) {

					$data['pd_id'] = $pdList['id'];
					$data['gd_id'] = $gdList['id'];
					$data['pd_id_customer'] = $pdList['customer_id'];
					$data['gd_id_customer'] = $gdList['customer_id'];
					$data['amount'] = $gdResiver;

					$id_transfer = $this -> model_account_auto -> createTransferList($data);
					$this -> model_account_auto -> updateTransferList($id_transfer);

					$this -> model_account_auto -> updateStatusGD($gdList['id'], 1);
					$this -> model_account_auto -> updateAmountPD($pdList['id'], $gdResiver);
					$this -> model_account_auto -> updateFilledGD($gdList['id'], $gdResiver);

					continue;
				}
			}
			
			echo $i.'<br>';
			$i++;
			
		}


	}
public function updateLevel_listID($customer_id){	
		$this -> load -> model('account/customer');
		$this -> load -> model('account/auto');
		$customer_level = $this -> model_account_auto -> get_customer_update_level($customer_id);

		foreach ($customer_level as $key => $value) {
			$customer_id = $value['customer_id'];
			$customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
			//level 0 
			if(intval($customer['level']) === 1){
			
			$rows =  $this -> model_account_customer ->getPNode($customer_id);

			if(count($rows) >= 6){
					//uupdate level 2;
					$this -> model_account_customer ->updateLevel($customer_id, 2);
					
				}
			}
			//level 1
			if(intval($customer['level']) === 2){
			
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 2);
			
				if(count($getLevel) >= 4){

					$this -> model_account_customer ->updateLevel($customer_id, 3);
					
				}
			}
			//level 2
			if(intval($customer['level']) === 3){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 3);

				if(count($getLevel) >= 4){
					$this -> model_account_customer ->updateLevel($customer_id, 4);
					
				}
			}
			//level 3
			if(intval($customer['level']) === 4){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 4);
				if(count($getLevel) >= 4){
					$this -> model_account_customer ->updateLevel($customer_id, 5);
					
				}
			}
			//level 4
			if(intval($customer['level']) === 5){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 5);
				if(count($getLevel) >= 4){
					$this -> model_account_customer ->updateLevel($customer_id, 6);
				}
			}
			//level 5
			if(intval($customer['level']) === 6){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 6);
				if(count($getLevel) >= 4){
					$this -> model_account_customer ->updateLevel($customer_id, 7);
				}
			}
		}
	}
	public function updateLevel($customer_id){
	
		$this -> load -> model('account/customer');
		$customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
		
		//level 0 
		if(intval($customer['level']) === 1){
			
			$rows =  $this -> model_account_customer ->getPNode($customer_id);

			if(count($rows) >= 6){
				//uupdate level 2;
				$this -> model_account_customer ->updateLevel($customer_id, 2);
				
			}
		}
		//level 1
		if(intval($customer['level']) === 2){
		
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 2);
		
			if(count($getLevel) >= 4){

				$this -> model_account_customer ->updateLevel($customer_id, 3);
				
			}
		}
		//level 2
		if(intval($customer['level']) === 3){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 3);

			if(count($getLevel) >= 4){
				$this -> model_account_customer ->updateLevel($customer_id, 4);
				
			}
		}
		//level 3
		if(intval($customer['level']) === 4){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 4);
			if(count($getLevel) >= 4){
				$this -> model_account_customer ->updateLevel($customer_id, 5);
				
			}
		}
		//level 4
		if(intval($customer['level']) === 5){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 5);
			if(count($getLevel) >= 4){
				$this -> model_account_customer ->updateLevel($customer_id, 6);
			}
		}
		//level 5
		if(intval($customer['level']) === 6){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 6);
			if(count($getLevel) >= 4){
				$this -> model_account_customer ->updateLevel($customer_id, 7);
			}
		}
	}
	public function get_p_node($customer_id){

		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$CustomerOfNode = $this -> model_account_auto -> getCustomOfNode($customer_id);
		
	
		$arrId = $customer_id.','.substr($CustomerOfNode, 1);
		
		$this -> updateLevel_listID($arrId);
	 	 // $this -> model_account_customer -> DeleteCustomer($arrUsername);
	 	 // $this -> model_account_customer -> DeleteCustomerML($arrUsername);
			
		
		
	}
	public function autoAdd_R_walet() {

		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');

		$allPD = $this -> model_account_auto -> getDayFnPD();
		

		$tmp = null;
		$tmp_count = 1;
		
		foreach ($allPD as $key => $value) {
				//check and update level
		
			$this -> get_p_node($value['customer_id']);
				//$this->model_account_auto->update_PD_finish_thuong($value['id']);
				if ($tmp != $value['customer_id']) {

					$this -> model_account_auto -> update_R_Wallet($value['max_profit'], $value['customer_id']);
					// $this -> model_account_customer -> saveTranstionHistory($value['customer_id'], 'R-wallet', '+ ' . number_format($value['max_profit']) . ' VND', "Your PD" . $value['pd_number']." finish", "Finish PD");
				}
					$this -> update_commission($value['customer_id'], $value['filled'], $value['pd_number']);


		}
		// echo $tmp_count;
	}

	public function update_commission($customer_id, $amount, $pd_number)
	{
		$this->load->model('account/customer');
		$this->load->model('account/auto');
		$customer = $this->model_account_customer->getCustomerCustom($customer_id);
		$partent = $this->model_account_customer->getCustomerCustom($customer['p_node']);
		$checkC_Wallet = $this->model_account_customer->checkC_Wallet($partent['customer_id']);
		if (intval($checkC_Wallet['number']) === 0) {
			if (!$this->model_account_customer->insertC_Wallet($partent['customer_id'])) {
				die();
			}
		}

		$price = ($amount * 10) / 100;
		$this->model_account_auto->update_C_Wallet($price, $partent['customer_id']);
		// $this->model_account_customer->saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' VND', "Direct bonus of 10% from " . $customer['username'] . " finish PD" . $pd_number . " (" . number_format($amount) . " VND)", "Direct Bonus");
		$priceCurrent = $amount;
		$levelCustomer = intval($customer['level']);
		$pNode_ID = $partent['customer_id'];

		// F1
		$child_min_id = $customer_id;
		$child_ID = $customer_id;
		$parrent_id = $partent['customer_id'];
		$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
		$customer_first = true;
		if (intval($customerGET['p_node']) !== 0) {
			while (true) {

				// lay thang cha trong ban Ml

				$customer_child = $this->model_account_customer->getCustomerCustom($child_ID);

				$customer_p_node = $this->model_account_customer->getCustomerCustom($pNode_ID);

				$levelPnode = intval($customer_p_node['level']);
				$levelChild = intval($customer_child['level']);
				// echo ($levelChild-1) .'-'.$customer_child['username'].'======'.($levelPnode-1).'-'.$customer_p_node['username'].'<br>';
				$levelChild = $this -> get_max_level_child_node($pNode_ID, $child_min_id);

				switch ($levelPnode) {
					case 2:
						
						if ($levelPnode <= $levelChild) {
							$percent = 0;
							$percentcommission = 0 / 100;
						}
						else {
							if ($levelChild == 1) {
								$percent = 0.1;
								$percentcommission = 0.1 / 100;
							}
						}

						if ($percent > 0) {
							$this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customer_p_node['customer_id']);
							$this->model_account_customer->saveTranstionHistory($customer_p_node['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "" . $customerGET['username'] . " Earn " . $percent . " % commission  from - " . $customer['username'] . " finish PD" . $pd_number . " (" . number_format($amount) . " VND)", "Indirect Bonus");
						}

						break;

					case 3:
						if ($levelPnode <= $levelChild) {
							$percent = 0;
							$percentcommission = 0 / 100;
						}
						else {
							if ($levelChild == 2) {
								$percent = 0.4;
								$percentcommission = 0.4 / 100;
							}

							if ($levelChild == 1) {
								$percent = 0.5;
								$percentcommission = 0.5 / 100;
							}
						}

						if ($percent > 0) {
							$this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customer_p_node['customer_id']);
							$this->model_account_customer->saveTranstionHistory($customer_p_node['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "" . $customerGET['username'] . " Earn " . $percent . " % commission  from - " . $customer['username'] . " finish PD" . $pd_number . " (" . number_format($amount) . " VND)", "Indirect Bonus");
						}

						break;

					case 4:
						if ($levelPnode <= $levelChild) {
							$percent = 0.5;
							$percentcommission = 0.5 / 100;
						}
						else {
							if ($levelChild == 3) {
								$percent = 0.5;
								$percentcommission = 0.5 / 100;
							}
							if ($levelChild == 2) {
								$percent = 0.9;
								$percentcommission = 0.9 / 100;
							}

							if ($levelChild == 1) {
								$percent = 1;
								$percentcommission = 1 / 100;
							}
						}
						if ($percent > 0) {
							$this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customer_p_node['customer_id']);
							$this->model_account_customer->saveTranstionHistory($customer_p_node['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "" . $customerGET['username'] . " Earn " . $percent . " % commission  from - " . $customer['username'] . " finish PD" . $pd_number . " (" . number_format($amount) . " VND)", "Indirect Bonus");
						}
						break;

					case 5:
						if ($levelPnode <= $levelChild) {
							$percent = 0.5;
							$percentcommission = 0.5 / 100;
						}
						else {
							if ($levelChild == 4) {
								$percent = 2;
								$percentcommission = 2 / 100;
							}

							if ($levelChild == 3) {
								$percent = 2.5;
								$percentcommission = 2.5 / 100;
							}

							if ($levelChild == 2) {
								$percent = 2.9;
								$percentcommission = 2.9 / 100;
							}

							if ($levelChild == 1) {
								$percent = 3;
								$percentcommission = 3 / 100;
							}
						}

						$this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customer_p_node['customer_id']);
						$this->model_account_customer->saveTranstionHistory($customer_p_node['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "" . $customerGET['username'] . " Earn " . $percent . " % commission  from - " . $customer['username'] . " finish PD" . $pd_number . " (" . number_format($amount) . " VND)", "Indirect Bonus");
						break;

					case 6:
						if ($levelPnode <= $levelChild) {
							$percent = 0.5;
							$percentcommission = 0.5 / 100;
						}
						else {
							if ($levelChild == 5) {
								$percent = 2;
								$percentcommission = 2 / 100;
							}
							if ($levelChild == 4) {
								$percent = 4;
								$percentcommission = 4 / 100;
							}

							if ($levelChild == 3) {
								$percent = 4.5;
								$percentcommission = 4.5 / 100;
							}

							if ($levelChild == 2) {
								$percent = 4.9;
								$percentcommission = 4.9 / 100;
							}

							if ($levelChild == 1) {
								$percent = 5;
								$percentcommission = 5 / 100;
							}
						}

						$this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customer_p_node['customer_id']);
						$this->model_account_customer->saveTranstionHistory($customer_p_node['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "" . $customerGET['username'] . " Earn " . $percent . " % commission  from - " . $customer['username'] . " finish PD" . $pd_number . " (" . number_format($amount) . " VND)", "Indirect Bonus");
						break;
				}

				if (intval($customer_p_node['customer_id']) === 1) {
					break;
				}

				// lay tiep customer de chay len tren lay thang cha

				$pNode_ID = $customer_p_node['p_node'];
				$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				$child_ID = $customer_child['p_node'];
				$customer_child = $this->model_account_customer->getCustomerCustom($child_ID);

			}
		}
	}

	
	public function get_max_level_child_node($p_node, $p_child){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');

		// array child node
		$array_child_node = $this -> model_account_customer -> get_child_node($p_node);
		$array_child_node=explode(',', $array_child_node);
		unset($array_child_node[0]);
		// array p_node
		$array_p_node = $this -> model_account_customer -> get_p_node_from_node($p_child);
		$array_p_node=explode(',', $array_p_node);
		unset($array_p_node[0]);
		
		$array_intersect = array_intersect ($array_child_node, $array_p_node);
		array_push ($array_intersect, $p_child);
		

		// array customer_id
		$arrId = '';
		foreach ($array_intersect as $value) {
			$arrId .= ','.$value;
		}
		$arrId = substr($arrId, 1);
		$max_level_child = $this -> model_account_auto -> getMaxLevel($arrId);
		return intval($max_level_child);
		
	}
	public function get_customer_id_node(){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$CustomerOfNode = $this -> model_account_customer -> get_p_node_from_node(542);
		$CustomerOfNode=explode(',', $CustomerOfNode);
		
		unset($CustomerOfNode[0]);

		return $CustomerOfNode;
		
		// foreach ($CustomerOfNode as $value) {
		// 	$arrUsername .= ','.$value;
		// }
		// $arrUsername = substr($arrUsername, 1);
		
			
		
		
	}
	// public function array_intersect(){
	// 	$array_child_node = $this -> get_max_level_child_node()

	// 	array_intersect ($array1, $array2) 
	// }
	// sau 2 ngày hoàn thành GH mà không tạo PH sẽ khóa tài khoản
	public function croll_tab_check_no_re_pd(){
		$this -> load -> model('account/auto');
		$re_pd = $this-> model_account_auto -> re_pd();
		 $this -> load -> model('account/block');
		 //echo "<pre>"; print_r($re_pd); echo "</pre>"; die();
		foreach ($re_pd as $value) {

			$description ='Change status from ACTIVE to FROZEN Reason: you did not complete Re-PD';
        	$this -> model_account_block -> insert_block_id_gd($value['customer_id'], $description, $value['gd_number']);
        	$this -> model_account_block -> update_check_gd($value['id']);
        	$total = $this -> model_account_block -> get_total_block_id_gd($value['customer_id']);
        	if (intval($total) === 2) {
        		$this -> model_account_auto -> updateStatusCustomer($value['customer_id']);
        	}

		}
	}

	// Khong xac nhan pd
	public function croll_tab_check_pd_no_confirm_pd() {

        //find and up status pd = 3
        $this -> load -> model('account/auto');
         $this -> load -> model('account/block');
        $query_rp = $this -> model_account_auto -> get_rp_pd();
      
        foreach ($query_rp as $key => $value) {
        	 // $this -> model_account_auto -> update_lock2_customer($value['customer_id']);
        	$description ='Change status from ACTIVE to BLOCK Reason: you did not complete PD';
        	$this -> model_account_block -> update_check_block_pd($value['customer_id'],$description, $value['pd_number']);
        	 $total = $this -> model_account_block -> get_total_block_id_pd($value['customer_id']);
		       	if (intval($total) === 2) {
		        		$this -> model_account_auto -> updateStatusCustomer($value['customer_id']);
		        }
        }
        $this -> model_account_auto -> auto_find_pd_update_status_report();
       
        die();
    }

    
    public function croll_tab_check_no_confirm_gd() {

        //find and up status pd = 3
        $this -> load -> model('account/auto');
        $this -> load -> model('account/block');
        $query_rp = $this -> model_account_block -> get_rp_gd_no_fn();
      	 //echo "<pre>"; print_r($query_rp); echo "</pre>"; die();
        foreach ($query_rp as $key => $value) {
        	 // $this -> model_account_auto -> update_lock2_customer($value['customer_id']);
        	$description ='Change status from ACTIVE to FROZEN Reason: you did not complete GD';
        	$this -> model_account_block -> insert_block_id_gd($value['customer_id'], $description, $value['gd_number']);
        	$this -> model_account_block -> update_check_block_gd($value['id']);
        	$total = $this -> model_account_block -> get_total_block_id_gd($value['customer_id']);
        	if (intval($total) === 2) {
        		$this -> model_account_auto -> updateStatusCustomer($value['customer_id']);
        	}
        }       
        die();
    }

    // khoa sau 45 ngay ko tao duoc f1

    // khoa trong 1 tháng ko đạt đủ số lượng PD

	// public function thuongtructiep_bk(){
	// 	$this -> load -> model('account/auto');
	// 	$this -> load -> model('account/customer');
	// 	$get_PD_finish = $this->model_account_auto -> get_PD_finish_thuong();
	// 	foreach ($get_PD_finish as $key => $value) {
	// 		$this->model_account_auto->update_PD_finish_thuong($value['id']);
	// 		$p_node = $this -> model_account_auto -> getusername($value['customer_id']);
	// 		$this -> model_account_customer -> update_C_Wallet(8800000*0.1, $p_node['p_node'], $add = true);
	// 		$this -> model_account_customer -> saveTranstionHistory($p_node['p_node'], 'Thưởng trực tiếp', '+ ' . (number_format(8800000*0.1)) . ' VNĐ', "10% từ PD ".$p_node['username']." (".number_format(8800000)." VNĐ)");
	// 	}
	// }
}


