<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {


		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this -> document -> addScript('catalog/view/javascript/forgot/forgot.js');
		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');


		$getLanguage = 'english';

		$language = new Language($getLanguage);
		$language -> load('account/forgotten');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;


		if (($this->request->server['REQUEST_METHOD'] === 'POST') && $this->validate()) {

			$this->load->language('mail/forgotten');
			$customer_info = $this->model_account_customer->getCustomerByUsername($this->request->post['email']);


			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 30);

			$this->model_account_customer->editPasswordCustomForEmail($customer_info, $password);

			$subject = sprintf('art-naturalcare.com - Mật khẩu mới', html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = sprintf('Một mật khẩu mới đã được yêu cầu từ art-naturalcare.com', html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= 'Mật khẩu của bạn là:' . "\n\n";
			$message .= $password;



			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			//print_r($customer_info['email']); die;
			$mail->send();
			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log


			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_email'] = $this->language->get('text_your_email');
		$data['text_email'] = $this->language->get('text_email');

		$data['entry_email'] = $this->language->get('entry_email');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('account/forgotten', '', 'SSL');

		$data['back'] = $this->url->link('account/login', '', 'SSL');

		// $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		// $data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/forgotten.tpl', $data));
		}
	}

	protected function validate() {

		
			$getLanguage = 'english';
		

		$language = new Language($getLanguage);
		$language -> load('account/forgotten');
		$lang = $language -> data;
		if ($this->request->post['capcha'] != $_SESSION['cap_code']) {
				$this->error['warning'] = "Cảnh báo: Sai mã bảo vệ";
	    }
	    
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = "Cảnh báo: Tên người dùng không được tìm thấy trong hồ sơ của chúng tôi, vui lòng thử lại!";
		} elseif (!$this->model_account_customer->getCustomerByUsername($this->request->post['email'])) {
			$this->error['warning'] = "Cảnh báo: Tên người dùng không được tìm thấy trong hồ sơ của chúng tôi, vui lòng thử lại!";
		}

		return !$this->error;
	}

	public function resetPasswdTran(){
		if ($this -> customer -> isLogged() && $this -> request -> get['id']) {

			$this->load->model('account/customer');
			$this->load->language('account/forgotten');
			$this->load->language('mail/forgotten');

			$customer_info = $this->model_account_customer->getCustomer($this -> request -> get['id']);

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 30);

			$this->model_account_customer->editPasswordTransactionCustomForEmail($customer_info, $password);

			$subject = sprintf('Iontach - Mật khẩu giao dịch mới', html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = sprintf('Một mật khẩu giao dịch mới đã được yêu cầu từ Iontach', html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= 'Mật khẩu của bạn là:' . "\n\n";
			$message .= $password;

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
			$json['ok'] = 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}
}