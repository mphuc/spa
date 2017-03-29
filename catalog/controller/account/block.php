<?php
class ControllerAccountBlock extends Controller {

	public function index() {
		
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			
			$self -> document -> addScript('catalog/view/javascript/setting/lock.js');
		};
		$block_id = $this -> check_block_id();
		
		if (intval($block_id) === 0) $this->response->redirect(HTTPS_SERVER . 'dashboard.html');

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$data['block_status'] = $this -> check_block_id();
		//language
		
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;


		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//language
		$this -> load -> model('account/customer');
		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/block.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/block.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/block.tpl', $data));
		}
	}

	public function lock_gd(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			
			$self -> document -> addScript('catalog/view/javascript/setting/lock.js');
		};
		$block_id = $this -> check_block_id_gd();
		
		if (intval($block_id) === 0) $this->response->redirect(HTTPS_SERVER . 'dashboard.html');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;


		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//language
		$this -> load -> model('account/customer');
		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/block_gd.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/block_gd.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/block_gd.tpl', $data));
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
	public function unlock(){
		

		if(array_key_exists("unlock",  $this -> request -> get) && $this -> customer -> isLogged()){
            $this -> load -> model('account/block');         
            $unlock = $_GET['unlock'];
            $unlock = intval($unlock);
            switch ($unlock) {
            	case 1:
            		$status_unlock = 1;
            		break;
            	case 2:
            		$status_unlock = 2;
            		break;
            	case 3:
            		$status_unlock = 3;
            		break;
            	
            	default:
            		die();
            		break;
            }
            $wallet = $this -> return_wallet();

            
            $this -> model_account_block -> update_block($this -> customer -> getId());
            $this -> model_account_block -> update_block_status($this -> customer -> getId());
            $getGD = $this -> model_account_block -> get_gd_cwallet_id($this -> customer -> getId());
          
            if (count($getGD) > 0 && doubleval($wallet['c_wallet']) < doubleval($getGD['amount'])) {
            	$this -> model_account_block -> update_GD_amount($wallet['c_wallet'], $this -> customer -> getId(), $getGD['id']);
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'C-wallet', '- ' . number_format($wallet['c_wallet']) . ' VND', "Reason: you did not complete PD", "Lock");
            }else{
            	$this -> model_account_block -> update_C_Wallet($wallet['c_wallet'],$this -> customer -> getId());
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'C-wallet', '- ' . number_format($wallet['c_wallet']) . ' VND', "Reason: you did not complete PD", "Lock");
            }

            $getGD_R = $this -> model_account_block -> get_gd_rwallet_id($this -> customer -> getId());
          
            if (count($getGD_R) > 0 && doubleval($wallet['r_wallet']) < doubleval($getGD_R['amount'])) {
            	$this -> model_account_block -> update_GD_amount($wallet['r_wallet'], $this -> customer -> getId(), $getGD_R['id']);
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'R-wallet', '- ' . number_format($wallet['r_wallet']) . ' VND', "Reason: you did not complete PD", "Lock");
            }else{
            	$this -> model_account_block -> updateRWallet($wallet['r_wallet'],$this -> customer -> getId());
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'R-wallet', '- ' . number_format($wallet['r_wallet']) . ' VND', "Reason: you did not complete PD", "Lock");
            }
            $json['link'] = HTTPS_SERVER.'dashboard.html';
            $json['ok'] = 1;
        }else{
        	$json['ok'] = -1;
        }
        $this->response->setOutput(json_encode($json));
	}
	public function unlock_gd(){
		

		if( $this -> customer -> isLogged()){
            $this -> load -> model('account/block');         
         	$this->load->model('account/customer');
            $wallet = $this -> return_wallet_gd();
          
            $getGD = $this -> model_account_block -> get_gd_cwallet_id($this -> customer -> getId());
          
            if (count($getGD) > 0 && doubleval($wallet['c_wallet']) < doubleval($getGD['amount'])) {
            	$this -> model_account_block -> update_GD_amount($wallet['c_wallet'], $this -> customer -> getId(), $getGD['id']);
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'C-wallet', '- ' . number_format($wallet['c_wallet']) . ' VND', "Reason: you did not complete GD", "Lock");
            }else{
            	$this -> model_account_block -> update_C_Wallet($wallet['c_wallet'],$this -> customer -> getId());
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'C-wallet', '- ' . number_format($wallet['c_wallet']) . ' VND', "Reason: you did not complete GD", "Lock");
            }

            $getGD_R = $this -> model_account_block -> get_gd_rwallet_id($this -> customer -> getId());
          
            if (count($getGD_R) > 0 && doubleval($wallet['r_wallet']) < doubleval($getGD_R['amount'])) {
            	$this -> model_account_block -> update_GD_amount($wallet['r_wallet'], $this -> customer -> getId(), $getGD_R['id']);
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'R-wallet', '- ' . number_format($wallet['r_wallet']) . ' VND', "Reason: you did not complete GD", "Lock");
            }else{
            	$this -> model_account_block -> updateRWallet($wallet['r_wallet'],$this -> customer -> getId());
            	$this -> model_account_customer -> saveTranstionHistory($this -> customer -> getId(), 'R-wallet', '- ' . number_format($wallet['r_wallet']) . ' VND', "Reason: you did not complete GD", "Lock");
            }
            $this -> model_account_block -> update_block_status_gd($this -> customer -> getId());
            $json['link'] = HTTPS_SERVER.'dashboard.html';
            $json['ok'] = 1;
        }else{
        	$json['ok'] = -1;
        }
        $this->response->setOutput(json_encode($json));
	}
	public function return_wallet(){
		if(	$this -> customer -> isLogged()){
            $this -> load -> model('account/block');
         
 
            $block_status = $this -> check_block_id();

            $level = $this -> model_account_block ->getLevel_by_customerid($this -> session -> data['customer_id']);
            $block_id = $this -> model_account_customer -> get_block_id($this -> customer -> getId());
            if (intval($block_status) === 1) {
            	
            	if (intval($block_id['total']) === 0) {
            		switch (intval($level['level'])) {
						case 1:
							$r_wallet = 2000000;
							$c_wallet = 2000000;
							break;
						case 2:
							$r_wallet = 4000000;
							$c_wallet = 4000000;
							break;
						case 3:
							$r_wallet = 7000000;
							$c_wallet = 8000000;
							break;
						case 4:
							$r_wallet = 11000000;
							$c_wallet = 16000000;
							break;
						case 5:
							$r_wallet = 16000000;
							$c_wallet = 32000000;
							break;
						case 6:
							$r_wallet = 22000000;
							$c_wallet = 64000000;
							break;
					}
            	}
            	if (intval($block_id['total']) === 1) {

            		switch (intval($level['level'])) {
						case 1:
							$r_wallet = 4000000;
							$c_wallet = 4000000;
							break;
						case 2:
							$r_wallet = 8000000;
							$c_wallet = 8000000;
							break;
						case 3:
							$r_wallet = 14000000;
							$c_wallet = 16000000;
							break;
						case 4:
							$r_wallet = 22000000;
							$c_wallet = 32000000;
							break;
						case 5:
							$r_wallet = 32000000;
							$c_wallet = 64000000;
							break;
						case 6:
							$r_wallet = 44000000;
							$c_wallet = 128000000;
							break;
					}
            		
            	}
            	
            }
    
            // status = 3
            if (intval($block_status) === 3) {
            	switch (intval($level['level'])) {
					case 1:
						$r_wallet = 700000;
						$c_wallet = 500000;
						break;
					case 2:
						$r_wallet = 1400000;
						$c_wallet = 1000000;
						break;
					case 3:
						$r_wallet = 2800000;
						$c_wallet = 2000000;
						break;
					case 4:
						$r_wallet = 4200000;
						$c_wallet = 4000000;
						break;
					case 5:
						$r_wallet = 7000000;
						$c_wallet = 8000000;
						break;
					case 6:
						$r_wallet = 11200000;
						$c_wallet = 16000000;
						break;
				}
            }
            // end status = 3
            $data['r_wallet'] = $r_wallet;
            $data['c_wallet'] = $c_wallet;
           $data['date'] = $block_id['date'];
           return $data;
        }
	}
	public function return_wallet_gd(){
		if(	$this -> customer -> isLogged()){
            $this -> load -> model('account/block');
         
             $level = $this -> model_account_block ->getLevel_by_customerid($this -> session -> data['customer_id']);

            $block_id = $this -> model_account_customer -> get_all_block_id_gd($this -> customer -> getId());
            $total_block_id_gd = $this -> model_account_customer -> get_block_id_gd_total($this -> customer -> getId());		
            if (intval($total_block_id_gd) === 0) {
            	
            	$r_wallet = 0;
				$c_wallet = 0;
			}    
            // status = 3
            if (intval($total_block_id_gd) >= 1) {
            	switch (intval($level['level'])) {
					case 1:
						$r_wallet = 700000;
						$c_wallet = 500000;
						break;
					case 2:
						$r_wallet = 1400000;
						$c_wallet = 1000000;
						break;
					case 3:
						$r_wallet = 2800000;
						$c_wallet = 2000000;
						break;
					case 4:
						$r_wallet = 4200000;
						$c_wallet = 4000000;
						break;
					case 5:
						$r_wallet = 7000000;
						$c_wallet = 8000000;
						break;
					case 6:
						$r_wallet = 11200000;
						$c_wallet = 16000000;
						break;
				}
            }
            // end status = 3
            $data['r_wallet'] = $r_wallet;
            $data['c_wallet'] = $c_wallet;
           	$data['date'] = $block_id['date'];
           	$data['description'] = $block_id['description'];
           return $data;
        }
	}
	public function sendmail(){
		if ($this->request->post)
		{
			$this -> load -> model('account/customer');
			$getCustomer = $this -> model_account_customer -> getCustomer($this->session->data['customer_id']);
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = 'mmocoimax@gmail.com';
			$mail->smtp_hostname = 'ssl://smtp.gmail.com';
			$mail->smtp_username = 'mmocoimax@gmail.com';
			$mail->smtp_password = 'ibzfqpduhwajikwx';
			$mail->smtp_port = '465';
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			
			$mail -> setTo('trungdoanict@gmail.com');
			$mail -> setFrom('mmocoimax@gmail.com');
			$mail -> setSender(html_entity_decode("Iontach, Inc", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Support Form username ".$getCustomer['username']."!");
			$html_mail = '<div style="background: #f2f2f2; width:100%;">
			   <table align="center" border="0" cellpadding="0" cellspacing="0" style="background:#801818;border-collapse:collapse;line-height:100%!important;margin:0;padding:0;
			    width:700px; margin:0 auto">
			   <tbody>
			      <tr>
			        <td>
			          <div style="text-align:center" class="ajs-header"><img src="'.HTTP_SERVER.'catalog/view/theme/default/images/lo_go.png'.'" alt="logo" style="margin: 20px auto; width:250px;"></div>
			        </td>
			       </tr>
			       <tr>
			       <td style="background:#fff">
			       	<p class="text-center" style="font-size:20px;color: black;text-transform: uppercase; width:100%; float:left;text-align: center;margin: 30px 0px 0 0;">SUPPORT<p>
			       	<p class="text-center" style="color: black; width:100%; float:left;text-align: center;line-height: 15px;margin-bottom:30px;"></p>
		<div style="width:600px; margin:0 auto; font-size=15px">
						<p style="font-size:14px;color: black;margin-left: 70px;">Name: <b>'.$this->request->post['name'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Your Username: <b>'.$getCustomer['username'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Email Address: <b>'.$getCustomer['email'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Phone Number: <b>'.$getCustomer['telephone'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Citizenship Card/Passport No: <b>'.$getCustomer['cmnd'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Content: <b>'.$this->request->post['content'].'</b></p>

				          </div>
			       </td>
			       </tr>
			    </tbody>
			    </table>
			  </div>';
			$mail -> setHtml($html_mail); 
			//print_r($mail); die;
			$mail -> send();
			$this -> response -> redirect("support.html#susscess");
		}
	}

	public function block_history() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};
		$this -> load -> model('account/customer');
		$this -> load -> model('account/block');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//language
		$this -> load -> model('account/transaction');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/transaction');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		
		$data['self'] = $this;
		$data['block'] = $this -> model_account_block -> get_block_id_gd_list($this -> customer -> getId());
		$data['block_pd'] = $this -> model_account_block -> get_block_id_pd_list($this -> customer -> getId());
		


		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/block_history.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/block_history.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/block_history.tpl', $data));
		}
	}
	

}
