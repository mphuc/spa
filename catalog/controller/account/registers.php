<?php
class ControllerAccountRegisters extends Controller {
	private $error = array();

	public function index() {
		die();
		!array_key_exists('ref', $this -> request -> get) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));




		$this -> document -> addScript('catalog/view/javascript/register/register.js');
		$this -> load -> language('account/register');

		   $this -> document -> setTitle('Đăng ký thành viên');

		$this -> load -> model('account/customer');
		$this -> load -> model('customize/country');

		$customer_get = $this -> model_account_customer -> getCustomerbyCode($this -> request -> get['ref']);

		count($customer_get) === 0 && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		$data['self'] = $this;

		$data['customer_id'] = $customer_get['customer_id'];


		$data['country'] = $this -> model_customize_country -> getCountry();
		$data['action'] = $this -> url -> link('account/registers/confirmSubmit', 'ref=' . $this -> request -> get['ref'], 'SSL');
		$data['actionCheckUser'] = $this -> url -> link('account/registers/checkuser', '', 'SSL');
		$data['actionCheckEmail'] = $this -> url -> link('account/registers/checkemail', '', 'SSL');
		$data['actionCheckPhone'] = $this -> url -> link('account/registers/checkphone', '', 'SSL');
		$data['actionCheckCmnd'] = $this -> url -> link('account/registers/checkcmnd', '', 'SSL');
		// $data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this -> load -> controller('common/footer');
		$data['header'] = $this -> load -> controller('common/header');
		$this -> load -> model('account/customer');
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/registers.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/registers.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/registers.tpl', $data));
		}
	}

	public function confirmSubmit() {
		if ($this->request->server['REQUEST_METHOD'] === 'POST'){

			$this -> load -> model('customize/register');
			$this -> load -> model('account/auto');
			$this -> load -> model('account/customer');
			

			$tmp = $this -> model_customize_register -> addCustomerByToken($this->request->post);

			$this -> model_customize_register -> update_customer_code($tmp);

			$data['has_register'] = true;
			$cus_id= $tmp;
			$amount = 0;
	
			$checkC_Wallet = $this -> model_account_customer -> checkR_Wallet($cus_id);
			if(intval($checkC_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertR_WalletR($cus_id,$amount)){
					die();
				}
			}

			$checkHH_Wallet = $this -> model_account_customer -> checkHH_Wallet($cus_id);
			if(intval($checkHH_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertHH_Wallet($amount, $cus_id)){
					die();
				}
			}

			$checkKM_Wallet = $this -> model_account_customer -> checkKM_Wallet($cus_id);
			if(intval($checkKM_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertKM_Wallet($amount, $cus_id)){
					die();
				}
			}

			$checkLN_Wallet = $this -> model_account_customer -> checkLN_Wallet($cus_id);
			if(intval($checkLN_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertLN_Wallet($amount, $cus_id)){
					die();
				}
			}

			$checkCH_Wallet = $this -> model_account_customer -> checkCH_Wallet($cus_id);
			if(intval($checkCH_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertCH_Wallet($amount, $cus_id)){
					die();
				}
			}

			/*$checkDT_Wallet = $this -> model_account_customer -> checkDT_Wallet($cus_id);
			if(intval($checkDT_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertDT_Wallet($amount, $cus_id)){
					die();
				}
			}*/

			$mail = new Mail();
			$mail -> protocol = $this -> config -> get('config_mail_protocol');
			$mail -> parameter = $this -> config -> get('config_mail_parameter');
			$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
			$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
			$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
			$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

			//$mail -> setTo($this -> config -> get('config_email'));
			$mail->setTo($_POST['email']);
			$mail -> setFrom($this -> config -> get('config_email'));
			$mail -> setSender(html_entity_decode("Iontach", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Chúc mừng bạn đã đăng ký thành công!");
			$mail -> setHtml('
				
		   <table align="center" bgcolor="#eeeeee" border="0" cellpadding="0" cellspacing="0" style="background:#eeeeee;border-collapse:collapse;line-height:100%!important;margin:0;padding:0;width:100%!important">
		   <tbody>
		      <tr>
		         <td>
		            <table style="border-collapse:collapse;margin:auto;max-width:635px;min-width:320px;width:100%">
		   <tbody>
		      <tr>
		         <td>
		            <table style="border-collapse:collapse;color:#c0c0c0;font-family:"Helvetica Neue",Arial,sans-serif;font-size:13px;line-height:26px;margin:0 auto 26px;width:100%">
		               <tbody>
		                  <tr>
		                     <td></td>
		                  </tr>
		               </tbody>
		            </table>
		         </td>
		      </tr>
		      <tr>
		         <td>
		            <table style="width:600px;" align="center" border="0" cellspacing="0" style="border-collapse:collapse;border-radius:3px;color:#545454;font-family:"Helvetica Neue",Arial,sans-serif;font-size:13px;line-height:20px;margin:0 auto;width:100%">
		   <tbody>
		      <tr>
		         <td>
		            <table border="0" cellpadding="0" cellspacing="0" style="border:none;border-collapse:separate;font-size:1px;height:2px;line-height:3px;width:100%">
		               <tbody>
		                  <tr>
		                     <td bgcolor="#9B59B6" valign="top"> </td>
		                  </tr>
		               </tbody>
		            </table>
		            <table style="width:600px; border="0" cellpadding="0" cellspacing="0" height="100%" style="border-collapse:collapse;border-color:#dddddd;border-radius:0 0 3px 3px;border-style:solid;border-width:1px;width:100%" width="100%">
		   <tbody>
		      <tr>
		         <td align="center" valign="top">
		            <table border="0" cellpadding="0" cellspacing="0" width="100%">
		               <tbody>
		                  <tr>
		                     <td align="center" style="background:#ffffff">
		                        <a href="https://happymoney.us" target="_blank" data-saferedirecturl="happymoney.us">
		                           <h1 style="margin-top:30px; font-weight:bold;">HAPPY MONEY</h1>
		                        </a>
		                     </td>
		                  </tr>
		               </tbody>
		            </table>
		         </td>
		      </tr>
		      <table style="background:#FFF; padding:25px;width:600px">
		      	<tbody>
		      		<tr>
		      			<td style="padding:30px;background:white;color:#525252;font-family:"Helvetica Neue",Arial,sans-serif;font-size:15px;line-height:22px;overflow:hidden;">
		            <p><span>Xin chào <b>'.$_POST['username'].'</b>,</span></p>
		            <p><span>Chúc mừng bạn đã đăng ký tài khoản thành công!</span></p>
		            <p><strong>Tên tài khoản ngân hàng: <span style="color:#5cb85c">'.$_POST['account_holder'].'</span></strong></p>
		            <p><strong>Số tài khoản ngân hàng: <span style="color:#5cb85c">'.$_POST['account_number'].'</span></strong></p>
		            <p><strong>Email: <span style="color:#5cb85c">'.$_POST['email'].'</span></strong></p>
		              <p><strong>Số điện thoại: <span style="color:#5cb85c">'.$_POST['telephone'].'</span></strong></p>
		            <p><strong>Tên tài khoản: <span style="color:#5cb85c">'.$_POST['username'].'</span></strong></p>
		            <p><strong>Mật khẩu đăng nhập: <span style="color:#5cb85c">'.$_POST['password'].'</span></strong></p>
					<p><strong>Mật khẩu 2: <span style="color:#5cb85c">'.$_POST['password2'].'</span></strong></p>
		            <p><strong>Vào ngày<strong>: '.date('d/m/Y H:i:s').'</p></td></p>
		             
		      		</tr>
		      	</tbody>
		      </table>
		       <hr>

			');
			//$mail -> send();
			unset($this->session->data['customer_id']);
			$this->response->redirect(HTTPS_SERVER . 'login.html#success');
			
		}
	}

	public function checkuser() {
		if ($this -> request -> get['username']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitUserName($this -> request -> get['username'])) === 1 ? 1 : 0;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkemail() {
		if ($this -> request -> get['email']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitEmail($this -> request -> get['email'])) < 100 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}
	public function checkphone() {
		if ($this -> request -> get['phone']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitPhone($this -> request -> get['phone'])) < 100 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkcmnd() {
		if ($this -> request -> get['cmnd']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitCMND($this -> request -> get['cmnd'])) < 100 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}

}
