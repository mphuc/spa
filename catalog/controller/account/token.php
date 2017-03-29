<?php
class ControllerAccountToken extends Controller {
	private $error = array();

	public function index() {

		$this -> response -> redirect($this -> url -> link('account/token/transfer', '', 'SSL'));

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');

		};
		$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
$block_id_gd = $this -> check_block_id_gd();

		if (intval($block_id_gd) !== 0) $this->response->redirect(HTTPS_SERVER . 'lockgd.html');
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//data render website
		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}

		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;
		$history_total = $this -> model_account_customer -> getTotalTokenHistory($this -> customer -> getId());
		$history_total = $history_total['number'];

		$pagination = new Pagination();
		$pagination -> total = $history_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/token', 'page={page}', 'SSL');

		$data['history'] = $this -> model_account_customer -> getTokenHistoryById($this -> customer -> getId(), $limit, $start);

		$data['pagination'] = $pagination -> render();

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$data['language'] = $getLanguage;

		$data['base'] = $server;
		$data['self'] = $this;
		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/token.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/token.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/token.tpl', $data));
		}
	}
	public function check_block_id_gd(){
		$this->load->model('account/customer');
		$block_id = $this -> model_account_customer -> get_block_id_gd($this -> customer -> getId());
		
		return intval($block_id);

	}
	public function check_block_id(){
		$this->load->model('account/customer');
		$block_id = $this -> model_account_customer -> get_block_id($this -> customer -> getId());
		
		return intval($block_id['status']);

	}

	public function callback() {
		/*$invoice_id_hash = array_key_exists('invoice_id', $this -> request -> get) ? $this -> request -> get['invoice_id'] : "Error";
		$secret = array_key_exists('secret', $this -> request -> get) ? $this -> request -> get['secret'] : "Error";

		$value_in_satoshi = array_key_exists('value', $this -> request -> get) ? $this -> request -> get['value'] : "Error";

		$confirmations = array_key_exists('confirmations', $this -> request -> get) ? $this -> request -> get['confirmations'] : "Error";
		$input_address = array_key_exists('input_address', $this -> request -> get) ? $this -> request -> get['input_address'] : "Error";

		//check invoice

		$invoice = $this -> model_account_token -> getInvoiceByIdAndSecret($invoice_id_hash, $secret);

		count($invoice) === 0 && die();

		$received = intval($invoice['received']) + intval($value_in_satoshi);
		$this -> model_account_token -> updateReceived($value_in_satoshi, $invoice_id_hash);
		$invoice = $this -> model_account_token -> getInvoiceByIdAndSecret($invoice_id_hash, $secret);
		$received = intval($invoice['received']);*/
		$this -> load -> model('account/token');
		$invoice_id = array_key_exists('invoice', $this -> request -> post) ? $this -> request -> post['invoice'] : "Error";
        $payment_code = array_key_exists('code', $this -> request -> post) ? $this -> request -> post['code'] : "Error";
        $tx_hash = array_key_exists('tx_hash', $this -> request -> post) ? $this -> request -> post['tx_hash'] : "Error";
        $payout_tx_hash = array_key_exists('payout_tx_hash', $this -> request -> post) ? $this -> request -> post['payout_tx_hash'] : "Error";
        $payout_service_fee = array_key_exists('payout_service_fee', $this -> request -> post) ? $this -> request -> post['payout_service_fee'] : "Error";
        $payout_miner_fee = array_key_exists('payout_miner_fee', $this -> request -> post) ? $this -> request -> post['payout_miner_fee'] : "Error";
        $this -> load -> model('account/pd');
        $this -> load -> model('account/auto');
        $this -> load -> model('account/customer');
        
        $invoice = $this -> model_account_token -> getInvoiceByIdAndSecret_pin($invoice_id, $payment_code);
        
        (count($invoice) === 0) && die;

        intval($invoice['confirmations']) >= 3 && die();

        $data = file_get_contents("https://bitaps.com/api/address/". $invoice['input_address']);
        
        $respond = json_decode($data); // Response array
        $received = doubleval($respond->received);
     
        $this -> model_account_token -> updateReceived_pin($received, $payment_code);
       
        $invoice = $this -> model_account_token -> getInvoiceByIdAndSecret_pin($invoice_id, $payment_code);

        $received = intval($invoice['received']);
		if ($received >= intval($invoice['amount'])) {
			
			//update Pin User
			$this -> model_account_token -> updatePin($invoice['customer_id'], $invoice['pin']);
			$this -> model_account_token -> saveHistoryPin($invoice['customer_id'], "+ " . $invoice['pin'], $_SERVER['SERVER_NAME'], 'Buy', $_SERVER['SERVER_NAME']);

			//update confirm
			$this -> model_account_token -> updateConfirm_pin($payment_code, 3,$tx_hash,$payout_tx_hash,$payout_service_fee,$payout_miner_fee);
			$this -> model_account_token -> update_customer_insurance_fund(2);

			

		}
	}
	public function show_invoice_pending() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> load -> model('account/token');
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$data['notCreate'] = true;
		$data['invoice'] = $this -> model_account_token -> getAllInvoiceByCustomer_notCreateOrder($this -> customer -> getId());
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;
		$data['self'] = $this;
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/checkBTC.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/checkBTC.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/checkBTC.tpl', $data));
		}
	}

	public function show_invoice() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> load -> model('account/token');
			$self -> document -> addScript('catalog/view/javascript/token/payment.js');
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		!array_key_exists('invoice_hash', $this -> request -> get) && die();
		$invoice_hash = $this -> request -> get['invoice_hash'];

		$invoice = $this -> model_account_token -> getInvoceFormHash($invoice_hash, $this -> customer -> getId());
		!$invoice && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		$count_invoice = $this -> model_account_token -> countToken($this -> customer -> getId());
		$count_invoice = $count_invoice['number'];
		$data['notCreate'] = false;
		if ($count_invoice > 6) {
			$data['notCreate'] = true;
			$data['invoice'] = $this -> model_account_token -> getAllInvoiceByCustomer_notCreateOrder($this -> customer -> getId());
		} else {
			$data['bitcoin'] = $invoice['amount'];
			$data['wallet'] = $invoice['input_address'];
			$data['received'] = $invoice['received'];
		}
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;
		$data['self'] = $this;
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/checkBTC.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/checkBTC.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/checkBTC.tpl', $data));
		}
	}

	public function buySubmit() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> load -> model('account/token');
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		!array_key_exists('pin_price', $this -> request -> get) && die();

		//check new user

		$customer_get = $this -> model_account_customer -> getCustomer($this -> session -> data['customer_id']);
		//language
		
		if(intval($this -> request -> get['pin_price'] < 5)){
			
			$json['error'] = true;
			$json['text'] = 'Please buy more than 5 pin for new user';
			$this -> response -> setOutput(json_encode($json));
			
		}else{
			//check count customer
			$count_invoice = $this -> model_account_token -> countToken($this -> session -> data['customer_id']);
			$count_invoice = $count_invoice['number'];
			$data['notCreate'] = false;
			if ($count_invoice >= 6)
				$data['notCreate'] = true;
			//save invoice

			if (!$data['notCreate']) {
				$secret = substr(hash_hmac('ripemd160', hexdec(crc32(md5(microtime()))), 'secret'), 0, 16);
				$pin =$this -> request -> get['pin_price'];
				$pin_tmp = $pin * 7;
				$url = "https://blockchain.info/tobtc?currency=USD&value=" . (string)$pin_tmp;
				$amount = file_get_contents($url);

				$amount = $amount * 100000000;
				

				//$invoice_id = $this -> model_account_token -> saveInvoice($this -> session -> data['customer_id'], $secret, $amount, $pin);


				/*$invoice_id === -1 && die('Server error , Please try again !!!!');
				$invoice_id_hash = hexdec(crc32(md5($invoice_id)));
				//create API Blockchainapi.org
				// $my_address = "18vVv1bbGU9MyN35xvbykABreegnS9iHe7";
				// $my_address = "1CUJCPdkeHadLjWonKh44uswW1A3KvuvU5";
				 $my_address = '1FepTnoMH7q8LdzEmULrfwECx9tUkJN2rG';

				$my_callback_url = HTTPS_SERVER . 'index.php?route=account/token/callback&invoice_id=' . $invoice_id_hash . '&secret=' . $secret;

				$api_base = 'https://blockchainapi.org/api/receive';
				$response = file_get_contents($api_base . '?method=create&address=' . $my_address . '&callback=' . urlencode($my_callback_url));
			
				$object = json_decode($response);
				//update input address and fee_percent
				$mycallback = HTTPS_SERVER . 'index.php?route=account/token/callback&invoice_id=' . $invoice_id_hash . '&secret=' . $secret."&value=".$amount."&confirmations=3";
				!$this -> model_account_token -> updateInaddressAndFree($invoice_id, $invoice_id_hash , $object -> input_address, $object -> fee_percent, $object -> destination,$mycallback) && die('Server Error !!!!');*/
				/*$confirmations = 0;
	            $callback = urlencode("https://sotuchon.org/callback.html");
	            $fee = "low";
	            $rules = [
	              array('address'=>'1EtyfHf6jLwH9exV97qPoMR7PMxZsttECV', 'amount'=> 20000),
	              array('address'=>'1FepTnoMH7q8LdzEmULrfwECx9tUkJN2rG', 'quota'=> 100)
	            ];
	            
	            $postfields = json_encode(array('type'=>"payment_list", 'payment_list'=> $rules ));
	            $data = $this->post_api("https://bitaps.com/api/create/payment/smartcontract/". $callback . "?confirmations=" . $confirmations . "&fee=" . $fee, $postfields);*/

				
				$payout_address = "1FepTnoMH7q8LdzEmULrfwECx9tUkJN2rG";
	            $confirmations = 0;
	            $fee_level = "low";
	            $callback = urlencode("https://iontach.biz/index.php?route=account/token/callback");
	            $data = file_get_contents("https://bitaps.com/api/create/payment/". $payout_address. "/" . $callback . "?confirmations=" . $confirmations. "&fee_level=" . $fee_level);
	            if (!$data) { die("Sever error !"); };
	            $respond = json_decode($data,true);
	            $my_wallet = $respond["address"]; 
	            $secret = $respond["payment_code"]; 
	            $invoice_id_hash = $respond["invoice"];

	            $invoice_id = $this -> model_account_token -> saveInvoice_pin($this -> session -> data['customer_id'], $secret,$invoice_id_hash, $amount, $pin,$my_wallet,$callback);

				

				$json['error'] = false;
				
				$json['text'] = HTTPS_SERVER . 'confirm&invoice_hash=' . $invoice_id_hash;
				$this -> response -> setOutput(json_encode($json));
			} 
			else {
				$json['error'] = false;
				$json['text'] = HTTPS_SERVER . 'index.php?route=account/token/show_invoice_pending';
				$this -> response -> setOutput(json_encode($json));
			}
		}
	}

	public function transfersubmit() {
		//die;
		$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
		if ($json['login'] === 1) {
			$this -> load -> model('customize/register');
			$this -> load -> model('account/customer');
			//check customer
			$customer = $this -> request -> get['customer'];
			$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
			$Tree = $this -> model_account_customer -> checkUserName($this -> customer -> getId());

			$UTree = explode(',', $Tree);
			unset($UTree[0]);

			//get customer partent
			// $customerParent = $this -> model_account_customer -> getCustomer($this -> customer -> getId());
			// $customerParent = $customerParent['p_node'];
			// if (intval($customerParent) !== 0) {
			// 	$customerParent = $this -> model_account_customer -> getCustomer($customerParent);
			// 	array_push($UTree, $customerParent['username']);
			// }

			$customers = in_array($customer, $UTree) ? 1 : 0;

			$json['customer'] = intval($customers) === 0 ? -1 : 1;

			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['TransferPassword']);
			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;

			$customerSend = $this -> model_account_customer -> getCustomer($this -> customer -> getId());
			$pin = $customerSend['ping'];

			$num = $this -> request -> get['pin'];
			
			$amount = $num;
			if (is_numeric($num) && intval($num) !== 0 && ($pin - $amount) >= 5 && $amount >= 5) {
				$json['pin'] = 1;
			} else {
				$json['pin'] = -1;
			}
			

			if ($json['pin'] === 1 && $json['customer'] === 1 && $json['password'] === 1) {

				//up pin
				$pin_update = ($pin - $this -> request -> get['pin']);
				$this -> model_account_customer -> updatePin($this -> customer -> getId(), $pin_update);

				//down pin
				$customerReceived = $this -> model_account_customer -> getCustomerByUsername($this -> request -> get['customer']);
				$ping = intval($customerReceived['ping']);

				if ($ping === 0) {
					$this -> model_account_customer -> updatePin($customerReceived['customer_id'], $this -> request -> get['pin']);
				} else {
					$ping = $ping + intval($this -> request -> get['pin']);
					$this -> model_account_customer -> updatePin($customerReceived['customer_id'], $ping);
				}
				$customerReceived = $this -> model_account_customer -> getCustomerByUsername($this -> request -> get['customer']);
				//check new user
				/*if ($customerReceived['check_Newuser'] == 0 && intval($customerReceived['cycle']) === 0){

					if (intval($customerReceived['ping']) >= 3){
						$this -> model_account_customer -> updateNew_user($customerReceived['customer_id']);
						$this -> model_account_customer -> updatePinCustom($customerReceived['customer_id'] , 3);
						$id_history = $this -> model_account_customer -> saveHistoryPin($customerReceived['customer_id'], '- 3' , 'Sử dụng pin cho vòng quy mới', 'Cho - PH', 'Sử dụng pin cho vòng quy mới');

					}
				}
*/

				//save history cho user chuyen di

				$id_history = $this -> model_account_customer -> saveHistoryPin($this -> customer -> getId(), '- ' . $this -> request -> get['pin'], $this -> request -> get['description'], 'Transfer Pin', 'ID Receive '. $customerReceived['username']);

				//save history cho user nhan token
				$id_history = $this -> model_account_customer -> saveHistoryPin($customerReceived['customer_id'], '+ ' . $this -> request -> get['pin'], $this -> request -> get['description'], 'Transfer Pin', 'ID Give '. $customerSend['username']);
				//update 3840000 VND
				//get r-wallet of user received
				$checkR_Wallet = $this -> model_account_customer -> checkR_Wallet($customerReceived['customer_id']);
				if (intval($checkR_Wallet['number']) === 0) {
					if (!$this -> model_account_customer -> insertR_Wallet($customerReceived['customer_id'])) {
						die();
					}
				}
				//get r-wallet of user received
				$getRwallet = $this -> model_account_customer -> getR_Wallet($customerReceived['customer_id']);
				$getGDRecived = $this -> model_account_customer -> getTotalGD($customerReceived['customer_id']);

				// if(intval($getGDRecived['number']) === 0 && intval($getRwallet['amount']) === 0 && intval($customerReceived['ping']) === 0){
				// 	$this -> model_account_customer -> updateR_Wallet($customerReceived['customer_id'] , 3840000);
				// 	$this -> model_account_customer -> updateCheckNEwuser($customerReceived['customer_id']);
				// }
				$json['ok'] = $id_history ? 1 : -1;
			} else {
				$json['ok'] = -1;
			}
		}

		$this -> response -> setOutput(json_encode($json));
	}

	public function getaccount() {
		if (empty($_POST['keyword'])) die();
		if ($this -> customer -> isLogged() && $this -> request -> post['keyword']) {
			$this -> load -> model('account/customer');

			$tree = $this -> model_account_customer -> getCustomLike($this -> request -> post['keyword'], $this -> customer -> getId());

			foreach ($tree as $key => $value) {
				echo $value ? '<li class="list-group-item" onClick="selectU(' . "'" . $value['name'] . "'" . ');"><div>Username: ' . $value['name'] . '</div><div>' . $value['account_holder'] . '</div></li>' : '';
			}
		}
	}

	public function transfer() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
			$self -> document -> addScript('catalog/view/javascript/transfer/transfer.js');
			$self -> document -> addStyle('catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');
		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}

		$data['base'] = $server;
		$data['self'] = $this;

		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;
		$history_total = $this -> model_account_customer -> getTotalTokenHistory($this -> customer -> getId());
		$history_total = $history_total['number'];

		$pagination = new Pagination();
		$pagination -> total = $history_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/token/transfer', 'page={page}', 'SSL');

		$data['history'] = $this -> model_account_customer -> getTokenHistoryById($this -> customer -> getId(), $limit, $start);

		$data['pagination'] = $pagination -> render();
		$data['getCustomer'] = $this -> model_account_customer -> getCustomer($this -> customer -> getId());
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/token_transfer.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/token_transfer.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/token_transfer.tpl', $data));
		}

	}

	public function buypin() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/token');
		$self -> load -> model('account/customer');
			// $self -> document -> addScript('catalog/view/javascript/transfer/transfer.js');
			$self -> document -> addScript('catalog/view/javascript/token/token.js');
		};

		//language
		
		$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}
		$data['wallet'] = $this -> config -> get('config_wallet');
		$data['priceBTC'] = $this->config->get('config_price_btc_now');
		$data['base'] = $server;
		$data['self'] = $this;

		$customer_id_buy_pin = array(1,2,3,4,5,6,7,8);
	
		$check_buy_pin = in_array($this -> session -> data['customer_id'], $customer_id_buy_pin) ? 1 : 0;
		
		if (intval($check_buy_pin) === 0) $this->response->redirect(HTTPS_SERVER . 'login.html');

		//get all invoice

		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 6;
		$start = ($page - 1) * 6;
		$invoice_total = $this -> model_account_token -> getAllInvoiceByCustomerTotal($this -> session -> data['customer_id']);

		$invoice_total = $invoice_total['number'];
		$pagination = new Pagination();
		$pagination -> total = $invoice_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/token/buypin', 'page={page}#order-pin-history', 'SSL');

		$data['pagination'] = $pagination -> render();

		$data['invoice'] = $this -> model_account_token -> getAllInvoiceByCustomer($this -> session -> data['customer_id'], $limit, $start);
//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/buypin.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/buypin.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/buypin.tpl', $data));
		}

	}
	public function payment() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		!$this -> request -> get['value'] && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		if (!is_numeric($this -> request -> get['value'])) {
			$this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		}
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$data['wallet'] = $this -> config -> get('config_wallet');
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//get pd form transfer list

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/payment_pin.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/payment_pin.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/payment_pin.tpl', $data));
		}
	}
	public function check_payment(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/token');
		};
		$block_id = $this -> check_block_id();
		$this -> load -> model('account/token');
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		$invoice_hash = $this -> request -> post['invoice_hash'];

		$invoice = $this -> model_account_token -> getInvoceFormHash($invoice_hash, $this -> customer -> getId());
		if (count($invoice) > 0) {
			$json1['confirmations'] = $invoice['confirmations'];
			$json1['received'] = $invoice['received'];
			$this -> response -> setOutput(json_encode($json1));
			
		}
	}

}
