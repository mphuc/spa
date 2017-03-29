<?php
class ControllerAccountRegister extends Controller {
	private $error = array();

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		//method to call function
		//!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		
		$this -> load-> model('account/customer');

		$customer_get = $this -> model_account_customer -> getCustomerbyCode($_GET['ref']);
		//print_r($customer_get); die;
		count($customer_get) === 0 && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		$data['self'] = $this;

		$data['customer_id'] = $customer_get['customer_id'];
		$data['actionWallet'] = $this -> url -> link('account/personal/checkwallet', '', 'SSL');

		
		$data['action'] = $this -> url -> link('account/registers/confirmSubmit', 'ref=' . $_GET['ref'], 'SSL');
		$data['actionCheckUser'] = $this -> url -> link('account/registers/checkuser', '', 'SSL');
		$data['actionCheckEmail'] = $this -> url -> link('account/registers/checkemail', '', 'SSL');
		$data['actionCheckPhone'] = $this -> url -> link('account/registers/checkphone', '', 'SSL');
		$data['actionCheckCmnd'] = $this -> url -> link('account/registers/checkcmnd', '', 'SSL');
		
		$data['footer'] = $this -> load -> controller('common/footer');
		$data['header'] = $this -> load -> controller('common/header');
		$this -> load -> model('account/customer');
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/register.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/register.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/register.tpl', $data));
		}


	

	}
	public function confirmSubmit() {
		
		$filter_wave2 = Array('"', "'");
		foreach($_POST as $key => $value)
    	$_POST[$key] = $this -> replace_injection($_POST[$key], $filter_wave2);
		foreach($_GET as $key => $value)
    	$_GET[$key] = $this -> replace_injection($_GET[$key], $filter_wave2);
       
		if ($this->request->server['REQUEST_METHOD'] === 'POST'){

			$this -> load -> model('customize/register');
			$this -> load -> model('account/auto');
			$this -> load -> model('account/customer');
			
			$checkUser = intval($this -> model_customize_register -> checkExitUserName($_POST['username'])) === 1 ? 1 : -1;
			
			if ($checkUser == 1) {
				die('Error');
			}
			
			$tmp = $this -> model_customize_register -> addCustomerByToken($this->request->post);

			$cus_id= $tmp;
				

			$code_active = sha1(md5(md5($cus_id)));
			$this -> model_customize_register -> insert_code_active($cus_id, $code_active);

			$amount = 0;
			$checkR_Wallet = $this -> model_account_customer -> checkR_Wallet($cus_id);
			if(intval($checkR_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertR_WalletR($cus_id,$amount)){
					die();
				}
			}

			$checkHH_Wallet = $this -> model_account_customer -> checkHH_Wallet($cus_id);
			if(intval($checkHH_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertHH_WalletR($cus_id,$amount)){
					die();
				}
			}
			
			$data['has_register'] = true;
			$mail = new Mail();
			$mail -> protocol = $this -> config -> get('config_mail_protocol');
			$mail -> parameter = $this -> config -> get('config_mail_parameter');
			$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
			$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
			$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
			$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

			$mail -> setTo($_POST['email']);
			$mail -> setFrom($this -> config -> get('config_email'));
			$mail -> setSender(html_entity_decode("Diamonds freefall", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Congratulations Your Registration is Confirmed!");
			$html_mail = '

			<div style="background:#2C276C; width:100%;">
			   <table align="center" border="0" cellpadding="0" cellspacing="0" style="background:#26105C;border-collapse:collapse;line-height:100%!important;margin:0;padding:0;
			    width:700px; margin:0 auto">
			   <tbody>
			      <tr>
			        <td>
			           <div style="text-align:center" class="ajs-header"><img  src="'.HTTPS_SERVER.'catalog/view/theme/default/images/logo.png" alt="logo" style="margin: 20px auto; width:350px;"></div>
			        </td>
			       </tr>
			       <tr>
			       <td style="background:#fff">
			       	<p class="text-center" style="font-size:20px;color: black;text-transform: uppercase; width:100%; float:left;text-align: center;margin: 30px 0px 0 0;">congratulations !<p>
			       	<p class="text-center" style="color: black; width:100%; float:left;text-align: center;line-height: 15px;margin-bottom:30px;">You have successfully registered account</p>
   	<div style="width:600px; margin:0 auto; font-size=15px">

				       	<p style="font-size:14px;color: black;margin-left: 70px;">Your Username: <b>'.$this-> request ->post['username'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Email Address: <b>'.$this-> request ->post['email'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Phone Number: <b>'.$this-> request ->post['telephone'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Citizenship Card/Passport No: <b>'.$this-> request ->post['cmnd'].'</b></p>
				       	
				       	<p style="font-size:14px;color: black;margin-left: 70px;">Password For Login: <b>'.$this-> request ->post['password'].'</b></p>    	
				       	<p style="text-align:center;">
				       		<img style="margin:0 auto" src="https://chart.googleapis.com/chart?chs=150x150&chld=L|1&cht=qr&chl=bitcoin:'.$this-> request ->post['wallet'].'"/>
				       	</p>
				       	<p style="font-size:14px;color: black; text-align:center"><b>'.$this-> request ->post['wallet'].'</b></p>
				       	<p style="font-size:14px;color: black;margin-left: 70px; line-height: 20px; margin-right: 70px; ">You\'ll receive 2,000 coins after the account active and we are responsible for mining within 6 months to pay enough coin for you, you will be selling 100% or purchasing goods or international airfares</p>
				       	<p style="font-size:14px;color: black;margin-left: 70px;line-height: 20px; margin-right: 70px;"><b>*Note:</b>
All of your registration information can not be modified. If you want to modify information please contact administrator@aircoin.org</p>
				       	<p style="font-size:14px;color: black;text-align:center; margin-bottom:60px; margin-top:30px;"><a href="'.HTTPS_SERVER.'active.html&token='.$code_active.'" style="margin: 0 auto;width: 200px;background: #d14836; text-transform: uppercase; border-radius: 5px; font-weight: bold;text-decoration:none;color:#f8f9fb;display:block;padding:12px 10px 10px">Active Account</a>
				       	</p
				          </div>
			       </td>
			       </tr>
			       <tr>
		       <td style="width:100%; height: 35px; background: #26105C; color: #fff; line-height:35px; text-align: center">© 2017 Diamonds freefall - All Rights Reserved<td>
		       </tr>
		       </tr>
			    </tbody>
			    </table>
			  </div>';
			
			$this-> model_customize_register -> update_template_mail($code_active, $html_mail);
			$mail -> setHtml($html_mail);	
			//$mail -> send();

			$this->session->data['register_mail'] = $this-> request ->post['email'];
			unset($this->session->data['customer_id']);
			$this -> response -> redirect(HTTPS_SERVER . 'login.html#success');
			
		}
	}
	public function avatar($file,$cus_id){
	$this->load->model('account/customer');
	
		$filename = html_entity_decode($this->request->files['avatar']['name'], ENT_QUOTES, 'UTF-8');
		
		$filename = str_replace(' ', '_', $filename);
		if(!$filename || !$this->request->files){
			die();
		}

		$file = $filename . '.' . md5(mt_rand()) ;

		
		move_uploaded_file($this->request->files['avatar']['tmp_name'], DIR_UPLOAD_CUSTOM . $file);


		//save image profile
		$server = $this -> request -> server['HTTPS'] ? $this -> config -> get('config_ssl') :  $this -> config -> get('config_url');
		
		$linkImage = $server . 'system/card/'.$file;
	
		$this -> model_account_customer -> update_avatar($cus_id,$linkImage);

		
	}
		public function country() {
		$json = array();

		$this->load->model('customize/country');

		$country_info = $this->model_customize_country->getCountrys($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_customize_country->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function check_block_id(){
		$this->load->model('account/customer');
		$block_id = $this -> model_account_customer -> get_block_id($this -> customer -> getId());
		
		return intval($block_id['status']);

	}
	public function check_pin(){
		$this -> load -> model('account/customer');
		$pin = $this -> model_account_customer -> check_pin($this->session->data['customer_id']);

		return $pin;
	}
	public function checkuser() {
		if (empty($_GET['username'])) die();
		if ($this -> request -> get['username']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitUserName($this -> request -> get['username'])) === 1 ? 1 : 0;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkemail() {
		if (empty($_GET['email'])) die();
		$email = $this -> request -> get['email'];
		$email_full = explode("@", $email);
		$email = str_replace(".","",$email_full[0]);
		$email_finish = $email."@".$email_full[1];
		
		if ($this -> request -> get['email']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitEmail($email_finish)) < 1 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}
	public function checkphone() {
		if (empty($_GET['phone'])) die();
		if ($this -> request -> get['phone']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitPhone($this -> request -> get['phone'])) < 1 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkcmnd() {
		if (empty($_GET['cmnd'])) die();
		if ($this -> request -> get['cmnd']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitCMND($this -> request -> get['cmnd'])) < 1 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function getjson(){
		if (empty($_POST['number_vcb'])) die();
		$account = $_POST['number_vcb'];
		$this -> load -> model('customize/register');
		$number = $this->model_customize_register->get_vcb($account);
		if (count($number) < 1)
		{
			$json = implode('', file('https://santienao.com/api/v1/bank_accounts/'.$account.''));
			echo  $json;
			$json_decode = json_decode($json);
			if ($json_decode->state == "fetched"){
				$this->model_customize_register->insert_vcb($json_decode->account_id,$json_decode->account_name,$json_decode->bank_name);
				die;
			}
		}
		else{
			$number = $this->model_customize_register->get_vcb($account);
			echo json_encode($number);
		}

	}



}
