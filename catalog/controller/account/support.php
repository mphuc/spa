<?php
class ControllerAccountSupport extends Controller {

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
		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/support.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/support.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/support.tpl', $data));
		}
	}
	
	public function sendmail(){
		if ($this->request->post && $this -> customer -> isLogged())
		{
			if ($this->request->post['capcha'] != $_SESSION['cap_code']) {
					
					$this -> response -> redirect("support.html#error");
		    }

			$this -> load -> model('account/customer');
			$getCustomer = $this -> model_account_customer -> getCustomer($this->session->data['customer_id']);

			if ($this->request->post)
			{
				
				$this -> model_account_customer -> create_sendmail_account($this->session->data['customer_id'],$this->request->post['name'],$this->request->post['content']);
			}
			
			/*$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = 'mmocoimax@gmail.com';
			$mail->smtp_hostname = 'ssl://smtp.gmail.com';
			$mail->smtp_username = 'mmocoimax@gmail.com';
			$mail->smtp_password = 'ibzfqpduhwajikwx';
			$mail->smtp_port = '465';
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			
			$mail -> setTo('support@iontach.biz');
			$mail -> setFrom('mmocoimax@gmail.com');
			$mail -> setSender(html_entity_decode("Iontach, Inc", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Support Form username ".$getCustomer['username']."!");
			$html_mail = '<div style="background: #f2f2f2; width:100%;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" style="background:#801818;border-collapse:collapse;line-height:100%!important;margin:0;padding:0;
             width:700px; margin:0 auto">
            <tbody>
               <tr>
                 <td>
                   <div style="text-align:center" class="ajs-header"><img src="'.HTTP_SERVER.'catalog/view/theme/default/img/logo.png'.'" alt="logo" style="margin: 20px auto; width:250px;"></div>
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
                     <hr>
                     <p style="font-size:14px;color: black;margin-left: 70px;">Content: <b>'.$this->request->post['content'].'</b></p>

                      </div>
                </td>
                </tr>
             </tbody>
             </table>
           </div>';
			$mail -> setHtml($html_mail); 
			//print_r($mail); die;
			$mail -> send();*/
			$this -> response -> redirect("support.html#success");
		}
	}

}
