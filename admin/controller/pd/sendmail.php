<?php
class Controllerpdsendmail extends Controller {
	public function index() {

		$this->document->setTitle('Send mail');
		$this->load->model('sale/customer');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/sendmail.tpl', $data));
	}

	public function submit(){
		if ($this-> request -> post){
			print_r($_POST);
			
			$mail = new Mail();
			$mail -> protocol = $this -> config -> get('config_mail_protocol');
			$mail -> parameter = $this -> config -> get('config_mail_parameter');
			$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
			$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
			$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
			$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

			$mail->setTo($_POST['email']);
			$mail -> setFrom($this -> config -> get('config_email'));
			$mail -> setSender(html_entity_decode("Iontach Community", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Administrator Support");
			$mail -> setHtml($_POST['content']);
			$mail -> send();
			$this -> response -> redirect($this -> url -> link('pd/sendmail&token='.$_GET['token'].'#suscces'));
		}
	}

}