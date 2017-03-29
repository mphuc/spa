<?php
class ControllerAccountGd extends Controller {

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));


		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		$data['confirm_tranfer'] = $this -> url -> link ('account/gd/confirm_tranfer');

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$data['language'] = $getLanguage;
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;

		$count_ =  $this ->model_account_customer ->getTotalGD($this -> session -> data['customer_id']);

		$gd_total = $count_['number'];


		$pagination = new Pagination();
		$pagination -> total = $gd_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/gd', 'page={page}', 'SSL');

		$data['GetGDmyaccount'] = $this ->model_account_customer -> getGDById($this -> session -> data['customer_id'],$limit,$start);
		//print_r($data['GetGDmyaccount']);die;
		$data['pagination'] = $pagination -> render();

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd.tpl', $data));
		}
	}
	public function confirm_tranfer(){
		$this -> load -> model('account/customer');
		$id_tranfer =  ($this -> request -> post['id_tranfer']);
		$tranfer= $this -> model_account_customer -> getGDNhan_Trander_list_Image_poup($id_tranfer);
		//print_r($tranfer); die;
        $this -> load -> model('account/customer');

        $show_img_tranfer = $this -> model_account_customer -> show_img_tranfer($id_tranfer);
        //print_r($show_img_tranfer); die;
        echo '<img src="'.$show_img_tranfer['image'].'" style="width:100%; margin-top:20px;" />';
        if ($tranfer['status'] == 1) {

        ?>
        <div class="clearfix" style =" margin-top:20px;"></div>
        <a class="confirm_ btn btn-success btn-lg" href="<?php echo $this -> url -> link ('account/gd/confirm_tran_fer&token='.$id_tranfer.'');?>">Chấp nhận</a>
        <a class="report btn btn-danger btn-lg" href="<?php echo $this -> url -> link ('account/gd/report_tran_fer&token='.$id_tranfer.'');?>">Tranh chấp</a>
        <?php 
    	}
	}

	public function confirm_tran_fer(){
		$id_tranfer =  ($this -> request -> get['token']);
		$this -> load -> model('account/customer');
		$this -> load -> model('account/auto');
		
		$this -> model_account_customer ->updateStatusGD_tranfer($id_tranfer,"1");

		$this -> model_account_customer ->updateStatusPD_tranfer($id_tranfer,"1");

		$show_tranfer= $this -> model_account_customer -> getTransferList($id_tranfer);
		//print_r($id_tranfer);	die;
		$this -> model_account_customer -> updatestatusPD($show_tranfer['pd_id'],2);
	
		$this -> model_account_customer -> updatestatusGDCustom($show_tranfer['gd_id'],2);

		//update time cho Customer PD

		$this -> model_account_auto -> updateCryle($show_tranfer['pd_id_customer'], 3);
		$this -> model_account_customer -> updateDateAuto($show_tranfer['pd_id_customer']);
		$this -> model_account_customer -> updateCheckNEwuser_Custom($show_tranfer['pd_id_customer'] , 2);

		//get id cho
		$customer_pd = $this -> model_account_customer -> getCustomer($show_tranfer['pd_id_customer']);
		/*if(intval($customer_pd['check_Newuser']) === 2){
			// create Pd
			$this -> model_account_customer -> createPDCustom($show_tranfer['pd_id_customer'], 2000000, 3000000);
		}*/

		$this -> response -> redirect($this -> url -> link('account/gd', '', 'SSL'));
	}
	public function report_tran_fer(){
		$id_tranfer =  ($this -> request -> get['token']);
		$this -> load -> model('account/customer');
		$this -> model_account_customer ->updateStatusGD_tranfer($id_tranfer,"2");
		$this -> response -> redirect($this -> url -> link('account/gd', '', 'SSL'));
	}

	public function create() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/gd/create.js');
			$self -> load -> model('account/customer');
		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//get r_wallet AND c_wallet USER
		 $customer = $this->model_account_customer->getCustomer($this->session->data['customer_id']);

        $data['wallet'] = $customer['wallet'] !== '';

		$data['r_wallet'] = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
		$data['r_wallet'] = count($data['r_wallet']) > 0 ? $data['r_wallet']['amount'] : 0.0;

		$data['c_wallet'] = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
		$data['c_wallet'] = count($data['c_wallet']) > 0 ? $data['c_wallet']['amount'] : 0.0;

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd_create.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd_create.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd_create.tpl', $data));
		}
	}

	public function submit() {


		if ($this -> customer -> isLogged() && $this -> request -> get['Password2']) {
			$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
			$this -> load -> model('account/customer');



			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['Password2']);

			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;
			if($json['password'] === -1){
				$json['ok'] = -1;
				$this -> response -> setOutput(json_encode($json));
			}else{
				$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);

				if(intval($this -> request -> get['FromWallet']) === 2){
					$amount_R = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
					$checkConfirmPD = $this->model_account_customer->getConfirmTransaction();

					$json['checkConfirmPD'] = count($checkConfirmPD) == 0 ? -1 : 1;
					intval($amount_R['amount']) === 0 && die();
				}else{
					$amount_C = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
					intval($amount_C['amount']) === 0 && die();
					$json['checkConfirmPD'] = 1;
				}

				$amount = $this -> request -> get['amount'];


				if($amount <= 50000000){
					$amountPin = 1;
				}
				if($amount >= 100000000 && $amount <= 450000000){
					$amountPin = 2;
				}

				if($amount >= 500000000){
					$amountPin = 5;
				}

				if(intval($customer['ping']) <= $amountPin){
					// /$this -> response -> redirect($this -> url -> link('account/token/order', 'token='.$pd['id'].'', 'SSL'));
					$json['pin'] = -1;
				}else{
					$json['pin'] = 1;
				}



				if($json['pin'] === -1 || $json['checkConfirmPD'] == -1){

					$json['ok'] = -1;
					$this -> response -> setOutput(json_encode($json));
				}else{

					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], intval($customer['ping']) - intval($amountPin) );

					//==================
					if(intval($this -> request -> get['FromWallet']) === 2){

					$amontGD = $this -> request -> get['amount'];

				}else{

					//UPdate M-Wallet
					$checkM_Wallet = $this -> model_account_customer -> checkM_Wallet($this -> session -> data['customer_id']);

					if (intval($checkM_Wallet['number']) === 0) {
						if (!$this -> model_account_customer -> insert_M_Wallet($this -> session -> data['customer_id'])) {
							die();
						}
					}
					$getM_GD = $this -> model_account_customer -> get_M_Wallet_GD($this -> session -> data['customer_id']);

					$amontGD = $this -> request -> get['amount'];
					$amount20Percent = ($amontGD*0.2);
					if (intval($getM_GD['number']) === 1) {

						$this -> model_account_customer -> update_M_Wallet($amount20Percent,$this -> session -> data['customer_id'], $date = true);
					}
					else{

						$this -> model_account_customer -> update_M_Wallet($amount20Percent,$this -> session -> data['customer_id']);
					}
					$this -> model_account_customer -> saveTranstionHistory($this -> session -> data['customer_id'], 'M-Wallet', '+ ' . ($amount20Percent/100000000) . ' BTC', "20% withdrew from C - Wallet");
					$amontGD = $amontGD - $amount20Percent;
				}
					//==============


					$returnDate = $this -> model_account_customer -> createGD($amontGD);
					$id_history = $this->model_account_customer->saveHistoryPin(
						$this -> session -> data['customer_id'],
						'-'.intval($amountPin) ,
						'Used pin for GD'.$returnDate['gd_number'],
						'GD',
						'Used pin for GD'.$returnDate['gd_number']
					);
					//update r_wallet or c_wallet
					if($returnDate['query'] === true){
						$returnDate = false;
						if(intval($this -> request -> get['FromWallet']) === 2){
							//get R-wallet
							$returnDate = $this -> model_account_customer -> update_R_Wallet($this -> request -> get['amount'], $this -> session -> data['customer_id']);
							$this->model_account_customer->update_check_withdrawal();
						}

						if(intval($this -> request -> get['FromWallet']) === 1){
							$returnDate = $this -> model_account_customer -> update_C_Wallet($this -> request -> get['amount'], $this -> session -> data['customer_id']);
						}
					}

					$json['ok'] = $returnDate === true && $json['password'] === 1 ? 1 : -1;

					$this -> response -> setOutput(json_encode($json));
				}

			}

		}
	}


	public function transfer(){

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');

		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;


		$getGDCustomer = $this -> model_account_customer -> getGDByCustomerIDAndToken($this -> session -> data['customer_id'], $this -> request -> get['token']);

		intval($getGDCustomer['number']) === 0 && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		$getGDCustomer = null;

		$data['transferList'] = $this -> model_account_customer -> getGdFromTransferList($this -> request -> get['token']);
		// print_r($data['transferList']);
		// die();
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd_transfer.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd_transfer.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd_transfer.tpl', $data));
		}
	}

	public function confirm(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/confirm/confirm.js');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');

		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));


		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		$data['transferConfirm'] = $this -> model_account_customer -> getGDTranferByID($this -> request -> get['token']);

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd_confirm.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd_confirm.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd_confirm.tpl', $data));
		}
	}

	public function getIDCho($transfer_id){
		$this -> load -> model('account/customer');
		$data = $this -> model_account_customer -> getIdCho_Trander_list($transfer_id);
		return $data;
	}

	public function getPDCho($transfer_id){
		$this -> load -> model('account/customer');
		$data = $this -> model_account_customer -> getPDCho_Trander_list($transfer_id);
		return $data;
	}

}
