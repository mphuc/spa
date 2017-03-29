<?php
class ControllerAccountAccount extends Controller {
	public function index() {
		$this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		if (!$this -> customer -> isLogged()) {
			$this -> session -> data['redirect'] = $this -> url -> link('account/account', '', 'SSL');

			$this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		}

		$this -> load -> language('account/account');

		$this -> document -> setTitle($this -> language -> get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array('text' => $this -> language -> get('text_home'), 'href' => $this -> url -> link('common/home'));

		$data['breadcrumbs'][] = array('text' => $this -> language -> get('text_account'), 'href' => $this -> url -> link('account/account', '', 'SSL'));

		if (isset($this -> session -> data['success'])) {
			$data['success'] = $this -> session -> data['success'];

			unset($this -> session -> data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this -> language -> get('heading_title');

		$data['text_my_account'] = $this -> language -> get('text_my_account');
		$data['text_my_orders'] = $this -> language -> get('text_my_orders');
		$data['text_my_newsletter'] = $this -> language -> get('text_my_newsletter');
		$data['text_edit'] = $this -> language -> get('text_edit');
		$data['text_password'] = $this -> language -> get('text_password');
		$data['text_address'] = $this -> language -> get('text_address');
		$data['text_wishlist'] = $this -> language -> get('text_wishlist');
		$data['text_order'] = $this -> language -> get('text_order');
		$data['text_download'] = $this -> language -> get('text_download');
		$data['text_reward'] = $this -> language -> get('text_reward');
		$data['text_return'] = $this -> language -> get('text_return');
		$data['text_transaction'] = $this -> language -> get('text_transaction');
		$data['text_newsletter'] = $this -> language -> get('text_newsletter');
		$data['text_recurring'] = $this -> language -> get('text_recurring');

		$data['edit'] = $this -> url -> link('account/edit', '', 'SSL');
		$data['password'] = $this -> url -> link('account/password', '', 'SSL');
		$data['address'] = $this -> url -> link('account/address', '', 'SSL');
		$data['wishlist'] = $this -> url -> link('account/wishlist');
		$data['order'] = $this -> url -> link('account/order', '', 'SSL');
		$data['download'] = $this -> url -> link('account/download', '', 'SSL');
		$data['return'] = $this -> url -> link('account/return', '', 'SSL');
		$data['transaction'] = $this -> url -> link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this -> url -> link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this -> url -> link('account/recurring', '', 'SSL');

		if ($this -> config -> get('reward_status')) {
			$data['reward'] = $this -> url -> link('account/reward', '', 'SSL');
		} else {
			$data['reward'] = '';
		}

		$data['column_left'] = $this -> load -> controller('common/column_left');
		$data['column_right'] = $this -> load -> controller('common/column_right');
		$data['content_top'] = $this -> load -> controller('common/content_top');
		$data['content_bottom'] = $this -> load -> controller('common/content_bottom');
		$data['footer'] = $this -> load -> controller('common/footer');
		$data['header'] = $this -> load -> controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/account.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/account.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/account.tpl', $data));
		}
	}

	public function country() {
		$json = array();

		$this -> load -> model('localisation/country');

		$country_info = $this -> model_localisation_country -> getCountry($this -> request -> get['country_id']);

		if ($country_info) {
			$this -> load -> model('localisation/zone');

			$json = array('country_id' => $country_info['country_id'], 'name' => $country_info['name'], 'iso_code_2' => $country_info['iso_code_2'], 'iso_code_3' => $country_info['iso_code_3'], 'address_format' => $country_info['address_format'], 'postcode_required' => $country_info['postcode_required'], 'zone' => $this -> model_localisation_zone -> getZonesByCountryId($this -> request -> get['country_id']), 'status' => $country_info['status']);
		}

		$this -> response -> addHeader('Content-Type: application/json');
		$this -> response -> setOutput(json_encode($json));
	}

	public function autoRunFirstMonth() {
		require ('admin/model/sale/customer.php');
		$adminCustomerModel = new ModelSaleCustomer($this -> registry);
		//H?i phí d? ki?n(ð? tháng 30 ngày) ch?y t?ng ngày(ðóng h?i phí trý?c)
		$results_HPDuKien = $adminCustomerModel -> makeHPDuKien();

	}

	public function autoRunEveryDate() {
		require ('admin/model/sale/customer.php');
		$adminCustomerModel = new ModelSaleCustomer($this -> registry);
		//Off h?i viên vào ngày 10 ch?y t?ng ngày
		$results_checkOffUser = $adminCustomerModel -> checkOffUser();
		// Off HV quá 12 tháng(ð? tháng 30 ngày)ch?y t?ng ngày
		$results_OffUser12Thang = $adminCustomerModel -> OffUser12Thang();

	}

	public function autoPDGD() {
		$this -> load -> model('account/auto');
		$this -> load -> model('customize/register');

		//get first GD
		$loop = true;
		// $count = 0;
		
		$i=1;
		while ($loop) {

			$gdList = $this -> model_account_auto -> getGD7Before();
			
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
					$this -> model_account_auto -> createTransferList($data);
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
					$this -> model_account_auto -> createTransferList($data);
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

					$this -> model_account_auto -> createTransferList($data);

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


	public function updateLevel($customer_id){
	
		$this -> load -> model('account/customer');
		$customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
		
		//level 0 
		if(intval($customer['level']) === 1){
			
			$rows =  $this -> model_account_customer ->getPNode($customer_id);

			if(count($rows) >= 4){
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

			if(count($getLevel) >= 3){
				$this -> model_account_customer ->updateLevel($customer_id, 4);
				
			}
		}
		//level 3
		if(intval($customer['level']) === 4){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 4);
			if(count($getLevel) >= 3){
				$this -> model_account_customer ->updateLevel($customer_id, 5);
				
			}
		}
		//level 4
		if(intval($customer['level']) === 5){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 5);
			if(count($getLevel) >= 3){
				$this -> model_account_customer ->updateLevel($customer_id, 6);
			}
		}
		//level 5
		if(intval($customer['level']) === 6){
			$getLevel = $this -> model_account_customer ->getLevel($customer_id, 6);
			if(count($getLevel) >= 3){
				$this -> model_account_customer ->updateLevel($customer_id, 7);
			}
		}
	}

	public function autoAdd_R_walet() {

		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');

		$allPD = $this -> model_account_auto -> getAllPD();

		//echo "<pre>"; print_r($allPD); echo "</pre>"; die();
		$tmp = null;
		$tmp_count = 1;
		
		foreach ($allPD as $key => $value) {
			
			if ($value['pdstatus'] == 2 && $value['checkRWallet'] == 1) {
			
				//check and update level

				$this->updateLevel($value['pd_id_customer']);
				$customer = $this -> model_account_customer -> getCustomerCustom($value['pd_id_customer']);
			
				if ($tmp != $value['pd_id_customer']) {
					
					$this -> model_account_auto -> update_R_Wallet($value['max']-$value['filled'], $value['pd_id_customer']);
					$this -> model_account_customer -> saveTranstionHistory($value['pd_id_customer'], 'R-wallet', '+ ' . number_format($value['max']-$value['filled']) . ' VND', "Finish PD" . $value['pd_number']);
					$this -> model_account_auto -> updatePDcheck_R_Wallet($value['pd_id']);
					
					//auto Create PD
					$valueCustomer = $this -> model_account_customer -> getCustomer($value['pd_id_customer']);
					
					$getRwallet = $this -> model_account_customer -> getR_Wallet($value['pd_id_customer']);
		
					//Insert C-Wallet

					$checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($value['pd_id_customer']);

					if (intval($checkC_Wallet['number']) === 0) {
						if (!$this -> model_account_customer -> insertC_Wallet($value['pd_id_customer'])) {
							die();
						}
					}

					$getCwallet = $this -> model_account_customer -> getC_Wallet($value['pd_id_customer']);



					if(intval($getRwallet['amount']) > 130000 || intval($getCwallet['amount']) > 130000)
					{ //============stratIf===========
							
						if(intval($valueCustomer['ping']) <= 2){ //start if
						
							if ($getRwallet['amount'] > 130000) {
								//Create PD
								$amountpd	= $value['filled'];
								if ($amountpd == 6000000):
									$max_profit= 8000000;
								elseif ($amountpd == 12000000):
									$max_profit= 16000000;	
								else:
									$max_profit= 32000000;
								endif;	
								$pd_query = $this -> model_account_auto -> createPD($value['pd_id_customer'],$amountpd ,$max_profit);
								// End CreatePd
								$this -> model_account_auto -> update_R_Wallet($getRwallet['amount']-130000, $value['pd_id_customer']);
								$this -> model_account_customer -> saveTranstionHistory($value['pd_id_customer'], 'R-wallet', '- ' . number_format(130000) . ' VND', "Used for Create PD" . $pd_query['pd_number']);
							}elseif ($getCwallet['amount'] > 130000) {

								//Create PD
								$amountpd	= $value['filled'];
								if ($amountpd == 6000000):
									$max_profit= 8000000;		
								elseif ($amountpd == 12000000):
									$max_profit= 16000000;
								else:
									$max_profit= 32000000;
								endif;	
								$pd_query = $this -> model_account_auto -> createPD($value['pd_id_customer'], $amountpd ,$max_profit);
								// End CreatePd
								$this -> model_account_auto -> update_C_Wallet($price, $partent['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($value['pd_id_customer'], 'C-wallet', '- ' . number_format(130000) . ' VND', " Used for Create PD" . $pd_query['pd_number']);
							}else{
								die();
							}
							

						}elseif(intval($valueCustomer['ping']) > 2){ //start if
 	
							$amountpd	= $value['filled'];

							if ($amountpd == 6000000):
								$max_profit= 8000000;
								$subPin = intval($valueCustomer['ping']) - 1;
								$amountPin = 1;
								$this -> model_account_customer ->updatePin($value['pd_id_customer'], $subPin );
							elseif ($amountpd == 12000000):
								$max_profit= 16000000;
								$subPin = intval($valueCustomer['ping']) - 2;
								$amountPin = 2;
								$this -> model_account_customer ->updatePin($value['pd_id_customer'], $subPin );		
							else:
								$max_profit= 32000000;
								$subPin = intval($valueCustomer['ping']) - 4;
								$amountPin = 4;
								$this -> model_account_customer ->updatePin($value['pd_id_customer'], $subPin );	
							endif;	
						 
							$pd_query = $this -> model_account_auto -> createPD($value['pd_id_customer'], $amountpd ,$max_profit);

							$id_history = $this->model_account_customer->saveHistoryPin(
								$value['pd_id_customer'],  
								'-'.$amountPin ,
								'Used pin for PD'.$pd_query['pd_number'],
								'PD',
								'Used pin for PD'.$pd_query['pd_number']
							);
						} // end else;
						else{
							die();
						}



					} //============EndIf===========

					

				}		
				
				$partent = $this -> model_account_customer ->getCustomer($value['p_node']);
				
						
				$checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($partent['customer_id']);

				if (intval($checkC_Wallet['number']) === 0) {
					if (!$this -> model_account_customer -> insertC_Wallet($partent['customer_id'])) {
						die();
					}
				}

				$price = $this -> model_account_auto -> getPDAmount($value['pd_id_customer']);

				$priceCurrent = $price['amount'];
				$price = ($price['amount'] * 10) / 100;

				if ($tmp != $value['pd_id_customer']) {
					$this -> model_account_auto -> update_C_Wallet($price, $partent['customer_id']);
					$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' VND', "Sponsor 10% for finish PD" . $value['pd_number']);
				}

				$pNode_ID = $partent['customer_id'];
			
				$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);
				
				$levelCustomer = intval($customer['level']);
				$levelCustomer1 = intval($customerParent['level']);

				if ($tmp != $value['pd_id_customer']) {
					//tang 1;
					if (intval($customerParent['level']) >= 2) {
	
						switch (intval($customerParent['level'])) {
							// case 1 :	
								
							// 	$percent = 0.5;
							// 	$percentcommission =0.5/100;
							// 	$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
							// 	$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
							
							// 	break;
							case 2 :	
								if($levelCustomer < 2){

									$percent = 2 - $levelCustomer;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								}elseif($levelCustomer == 2){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								} 
								break;
							case 3 :
								if($levelCustomer < 3){
									$percent = 3 - $levelCustomer;
									$percentcommission =$percent/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								}elseif($levelCustomer == 3){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								} 
								break;
							case 4 :
								if($levelCustomer < 4){
									$percent = 4 - $levelCustomer;
									$percentcommission =$percent/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								}elseif($levelCustomer == 4){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								} 
								break;
							case 5 :
								if($levelCustomer < 5){
									$percent = 5 - $levelCustomer;
									$percentcommission =$percent/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								}elseif($levelCustomer == 5){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								} 
								break;
							case 6 :
								if($levelCustomer < 6){
									$percent = 6 - $levelCustomer;
									$percentcommission =$percent/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								}elseif($levelCustomer == 6){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								} 
								break;
							case 7 :
								if($levelCustomer < 7){
									$percent = 7 - $levelCustomer;
									$percentcommission =$percent/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								}elseif($levelCustomer == 7){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								} 
								break;
						}
					}

					//tang 2
					$customerGET = $this -> model_account_customer -> getCustomer($pNode_ID);

					$pNode_ID = $customerGET['p_node'];
					if (intval($pNode_ID) >= 1) {
						$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);

						$levelCustomerOne = intval($customerParent['level']);
						if (intval($customerParent['level']) >= 3) {
							
							switch (intval($customerParent['level'])) {
								// case 2 :	
								// 	if($levelCustomer1 < 2){

								// 		$percent = 2 - $levelCustomer1;
								// 		$percentcommission =$percent/100;

								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	}elseif($levelCustomer1 == 2){
								// 		$percent = 0.5;
								// 		$percentcommission =0.5/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	} 
								// 	break;
								case 3 :
									if($levelCustomer1 < 3){
										$percent = 3 - $levelCustomer1;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomer1 == 3){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 4 :
									if($levelCustomer1 < 4){
										$percent = 4 - $levelCustomer1;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomer1 == 4){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 5 :
									if($levelCustomer1 < 5){
										$percent = 5 - $levelCustomer1;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomer1 == 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 6 :
									if($levelCustomer1 < 6){
										$percent = 6 - $levelCustomer1;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomer1 == 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 7 :
									if($levelCustomer1 < 7){
										$percent = 7 - $levelCustomer1;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomer1 == 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
							}

						}

					}else{
						$tmp = $value['pd_id_customer'];
						continue;

					}

					//tang 2
					$customerGET = $this -> model_account_customer -> getCustomer($pNode_ID);

					$pNode_ID = $customerGET['p_node'];
					if (intval($pNode_ID) >= 1) {
						$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);

						$levelCustomerTwo = intval($customerParent['level']);
						if (intval($customerParent['level']) >= 4) {
							
							switch (intval($customerParent['level'])) {
								// case 3 :
								// 	if($levelCustomerOne < 3){
								// 		$percent = 3 - $levelCustomerOne;
								// 		$percentcommission =$percent/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	}elseif($levelCustomerOne == 3){
								// 		$percent = 0.5;
								// 		$percentcommission =0.5/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	} 
								// 	break;
								case 4 :
									if($levelCustomerOne < 4){
										$percent = 4 - $levelCustomerOne;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerOne == 4){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 5 :
									if($levelCustomerOne < 5){
										$percent = 5 - $levelCustomerOne;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerOne == 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 6 :
									if($levelCustomerOne < 6){
										$percent = 6 - $levelCustomerOne;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerOne == 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 7 :
									if($levelCustomerOne < 7){
										$percent = 7 - $levelCustomerOne;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerOne == 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
							}

						}

					}else{
						$tmp = $value['pd_id_customer'];
						continue;

					}
					//tang 3

					$customerGET = $this -> model_account_customer -> getCustomer($pNode_ID);

					$pNode_ID = $customerGET['p_node'];
					if (intval($pNode_ID) >= 1) {
						$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);
						$levelCustomerThree = intval($customerParent['level']);
						if (intval($customerParent['level']) >= 5) {
							switch (intval($customerParent['level'])) {
								// case 4 :
								// 	if($levelCustomerTwo < 4){
								// 		$percent = 4 - $levelCustomerTwo;
								// 		$percentcommission =$percent/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	}elseif($levelCustomerTwo == 4){
								// 		$percent = 0.5;
								// 		$percentcommission =0.5/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	} 
								// 	break;
								case 5 :
									if($levelCustomerTwo < 5){
										$percent = 5 - $levelCustomerTwo;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerTwo == 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 6 :
									if($levelCustomerTwo < 6){
										$percent = 6 - $levelCustomerTwo;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerTwo == 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 7 :
									if($levelCustomerTwo < 7){
										$percent = 7 - $levelCustomerTwo;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerTwo == 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
							}

						}

					}else{
						$tmp = $value['pd_id_customer'];
						continue;

					}

					//tang 4

					$customerGET = $this -> model_account_customer -> getCustomer($pNode_ID);

					$pNode_ID = $customerGET['p_node'];
					if (intval($pNode_ID) >= 1) {
						$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);
						$levelCustomerFour = intval($customerParent['level']);
						if (intval($customerParent['level']) >= 6) {
							switch (intval($customerParent['level'])) {
								// case 5 :
								// 	if($levelCustomerThree < 5){
								// 		$percent = 5 - $levelCustomerThree;
								// 		$percentcommission =$percent/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	}elseif($levelCustomerThree == 5){
								// 		$percent = 0.5;
								// 		$percentcommission =0.5/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	} 
								// 	break;
								case 6 :
									if($levelCustomerThree < 6){
										$percent = 6 - $levelCustomerThree;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerThree == 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
								case 7 :
									if($levelCustomerThree < 7){
										$percent = 7 - $levelCustomerThree;
										$percentcommission =$percent/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									}elseif($levelCustomerThree == 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerParent['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
									} 
									break;
							}

						}

					}else{
						$tmp = $value['pd_id_customer'];
						continue;

					}
					
					//tang 5
	
					// $customerGET = $this -> model_account_customer -> getCustomer($pNode_ID);

					// $pNode_ID = $customerGET['p_node'];
					// if (intval($pNode_ID) >= 1) {
					// 	$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);
					// 	if (intval($customerParent['level']) === 7) {
					// 		switch (intval($customerParent['level'])) {
								// case 6 :
								// 	if($levelCustomerFour < 6){
								// 		$percent = 5 - $levelCustomerFour;
								// 		$percentcommission =$percent/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	}elseif($levelCustomerFour == 6){
								// 		$percent = 0.5;
								// 		$percentcommission =0.5/100;
								// 		$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
								// 		$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
								// 	} 
								// 	break;
					// 			case 7 :
					// 				if($levelCustomerFour < 6){
					// 					$percent = 5 - $levelCustomerFour;
					// 					$percentcommission =$percent/100;
					// 					$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
					// 					$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
					// 				}elseif($levelCustomerFour == 6){
					// 					$percent = 0.5;
					// 					$percentcommission =0.5/100;
					// 					$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $partent['customer_id']);
					// 					$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$percent." % commission from downline member after PD" . $value['pd_number']);
					// 				} 
					// 				break;
					// 		}

					// 	}
					// }else{
					// 	$tmp = $value['pd_id_customer'];
					// 	continue;
					// }
					
					$customerGET = $this -> model_account_customer -> getCustomer($pNode_ID);
					$pNode_ID = $customerGET['p_node'];
					//tang 6
					if (intval($pNode_ID) >= 1) {
						$customerParent = $this -> model_account_customer -> getCustomerCustom($pNode_ID);
						if (intval($customerParent['level']) === 7) {
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * 0.005, $customerParent['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerParent['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * 0.005) . ' VND', "0.5% commission from downline member after PD" . $value['pd_number']);
						}
					}else{
						$tmp = $value['pd_id_customer'];
						continue;
					}
					
					//ket thuc 6 tang duoc huong hoa hong
				}
				
				$tmp = $value['pd_id_customer'];
				continue;
			}

			if ($value['pdstatus'] == 3) {
				$this -> model_account_auto -> updatePDcheck_R_Wallet($value['pd_id']);
				continue;
			}

			if (intval($value['pd_satatus']) === 0) {
				$this -> model_account_auto -> updateStatusPDTransferList($value['id']);
				$this -> model_account_auto -> updateStatusPD($value['pd_id'], 3);
				$this -> model_account_auto -> updateStatusGD($value['gd_id'], 3);
				$this -> model_account_auto -> updateStatusCustomer($value['pd_id_customer']);
				$partent = $this -> model_account_customer ->getCustomer($value['p_node']);
				$customers = $this -> model_account_customer ->getCustomer($value['pd_id_customer']);
				
				$this -> model_account_auto -> update_C_Wallet(-2000000, $partent['customer_id']);
				$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '- ' . number_format(2000000) . ' VND', "You are fined 2 millions dong because ".$customers['username']." did not transfer money when time is over! " . $value['pd_number']);
				$pdSend = floatval($value['amount']);
				$this -> model_account_auto -> createGDInventory($pdSend, $value['gd_id_customer']);
				continue;
			}

		}
		// echo $tmp_count;
	}

	public function importInventory(){
		$this->load->model('customize/register');
		// die('11');
		$customer = $this->model_customize_register -> getTableCustomerTmp();

		foreach ($customer as $key => $value) {
			$data['p_node'] = -1;
			$data['email'] = 'aiclinkvn@gmail.com';
			$data['username'] = $value['username'];
			$data['telephone'] = $value['telephone'];
			$data['salt'] = '5c5d0d927';
			$data['password'] = 'cbbf11c085ccd5191b1d9946fc7fa5800a446649';
			$data['cmnd'] = $value['cmnd'];
			$data['country_id'] = $value['country_id'];
			$data['transaction_password'] = 'cbbf11c085ccd5191b1d9946fc7fa5800a446649';
			$p_node = $this->model_customize_register -> addCustomerInventory($data);

		}

		die('ok');

	}

	// public function autoNode(){
		
	// 	$this->load->model('account/customer');
	// 	$loop = true;
	// 	// $count = 0;
	// 	$customer = $this-> model_account_customer -> getListCustomerId(2161);	
	// 	$arrId = substr($customer, 1);
	// 	$pdList = $this -> model_account_customer -> getPD10Before($arrId);
	// 	$tmp = 1;
	// 	foreach ($pdList as $key => $value) {		
	// 		$pd_query = $this -> model_account_customer -> createPDCustom(6000000 ,8000000, $value['customer_id']);
	// 		$customers = $this -> model_account_customer ->getCustomer($value['customer_id']);		
	// 		$subPin = intval($customers['ping']) - 1;
	// 		$this -> model_account_customer ->updatePin($value['customer_id'], $subPin );	
	// 		$id_history = $this->model_account_customer->saveHistoryPin(
	// 			$value['customer_id'],  
	// 			$subPin ,
	// 			'Used pin for PD'.$pd_query['pd_number'],
	// 			'PD',
	// 			'Used pin for PD'.$pd_query['pd_number']
	// 		);
	// 	}
		
	// }
	public function autoAddCustomer(){
		
		$this->load->model('customize/register');
		$i=1;
		while ( $i <= 50) {
			$data = array(
		    'username' => 'iops'.$i,
		    'email' => 'iops@gmail.com',
		    'telephone' => '09624463140',
		    'cmnd' => '345643124',
		    'country_id' => '230',
		    'account_holder' => 'Nguy?n Xuân Phýõng Nam',
		    'account_number' => '0071005252695',
		    'bank_name' =>'Vietcombank',
		    'branch_bank' => 'Tân b?nh'

		);
			$this-> model_customize_register -> addCustomerCustom($data, 0);
			$i++;
		}
		die('OK');
	}

}
