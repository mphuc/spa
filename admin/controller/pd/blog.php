<?php
class ControllerpdBlog extends Controller {
	public function index() {

		$this->document->setTitle('Blog');
		$this->load->model('sale/customer');
		


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;
		$pd_total = $this -> model_sale_customer -> getTotalblog();

		$pd_total = $pd_total['number'];

		$pagination = new Pagination();
		$pagination -> total = $pd_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('index.php?route=pd/blog&token='.$_GET['token'].'', 'page={page}', 'SSL');

		$data['pin'] = $this -> model_sale_customer -> getBlogById($limit, $start);
		$data['pagination'] = $pagination -> render();

		$data['getBlogById_admin'] = $this -> model_sale_customer -> getBlogById_admin();


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/blog.tpl', $data));
	}

	public function update_status(){
		$this->load->model('sale/customer');
		$getblog_id = $this -> model_sale_customer -> getblog_id(intval($this-> request -> get['id']));
		if ($getblog_id['status'] == 0){
			$status = 1;
		}
		else
		{
			$status = 0;
		}
		$this -> model_sale_customer -> update_status_block_account(intval($this-> request -> get['id']),$status);
		$this->response->redirect("index.php?route=pd/blog&token=".$_GET['token']);
	}

	public function create(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/theme/default/ckeditor/ckeditor.js');
			$self -> document -> addScript('catalog/view/theme/default/ckeditor/samples/js/sample.js');
			$self -> document -> addScript('catalog/view/javascript/blog.js');

		};
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$data['self'] = $this;
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;


		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/blog_create.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/blog_create.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/blog_create.tpl', $data));
		}

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