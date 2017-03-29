<?php
class ControllerpdManagersendmail extends Controller {
	public function index() {

		$this->document->setTitle('Send mail');
		$this->load->model('sale/customer');
		


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 30;
		$start = ($page - 1) * 30;

		$ts_history = $this -> model_sale_customer -> get_count_mail();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/manager_sendmail', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['pin'] =  $this-> model_sale_customer->get_all_mail($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/manager_sendmail.tpl', $data));
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