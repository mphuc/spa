<?php
class ControllerPdKetoan extends Controller {
	public function index() {
		$this->document->setTitle('Provide Donation');
		$this->load->model('pd/pd');

	$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		

	

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/upgrade.tpl', $data));
	}

	public function ketoan_r() {
		$this->document->setTitle('Hoa hồng trực tiếp');
		$this->load->model('pd/pd');

		$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this -> load -> model('pd/registercustom');


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 20;
		$start = ($page - 1) * 20;

		$ts_history = $this -> model_pd_registercustom -> get_count_r_wallet_bk();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/ketoan/ketoan_r', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['customer'] =  $this-> model_pd_registercustom->get_all_customer_r_wallet_bk($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/ketoan_r.tpl', $data));
	}

	public function up_r()
	{
		$this -> load -> model('pd/registercustom');
		$this -> model_pd_registercustom->update_status_r_bk($_GET['status'],$_GET['id']);
		$this->response->redirect($this->url->link('pd/ketoan/ketoan_r', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
	}
	
	public function ketoan_ch() {
		$this->document->setTitle('Hoa hồng trực tiếp');
		$this->load->model('pd/pd');

		$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this -> load -> model('pd/registercustom');


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 20;
		$start = ($page - 1) * 20;

		$ts_history = $this -> model_pd_registercustom -> get_count_ch_wallet_bk();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/ketoan/ketoan_ch', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['customer'] =  $this-> model_pd_registercustom->get_all_customer_ch_wallet_bk($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/ketoan_ch.tpl', $data));
	}

	public function up_ch()
	{
		$this -> load -> model('pd/registercustom');
		$this -> model_pd_registercustom->update_status_ch_bk($_GET['status'],$_GET['id']);
		$this->response->redirect($this->url->link('pd/ketoan/ketoan_ch', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
	}

	public function ketoan_hh() {
		$this->document->setTitle('Hoa hồng trực tiếp');
		$this->load->model('pd/pd');

		$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this -> load -> model('pd/registercustom');


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 20;
		$start = ($page - 1) * 20;

		$ts_history = $this -> model_pd_registercustom -> get_count_ch_wallet_bk();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/ketoan/ketoan_hh', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['customer'] =  $this-> model_pd_registercustom->get_all_customer_hh_wallet_bk($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/ketoan_hh.tpl', $data));
	}

	public function up_hh()
	{
		$this -> load -> model('pd/registercustom');
		$this -> model_pd_registercustom->update_status_hh_bk($_GET['status'],$_GET['id']);
		$this->response->redirect($this->url->link('pd/ketoan/ketoan_hh', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
	}

	public function ketoan_km() {
		$this->document->setTitle('Hoa hồng trực tiếp');
		$this->load->model('pd/pd');

		$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this -> load -> model('pd/registercustom');


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 20;
		$start = ($page - 1) * 20;

		$ts_history = $this -> model_pd_registercustom -> get_count_km_wallet_bk();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/ketoan/ketoan_km', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['customer'] =  $this-> model_pd_registercustom->get_all_customer_km_wallet_bk($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/ketoan_km.tpl', $data));
	}

	public function up_km()
	{
		$this -> load -> model('pd/registercustom');
		$this -> model_pd_registercustom->update_status_km_bk($_GET['status'],$_GET['id']);
		$this->response->redirect($this->url->link('pd/ketoan/ketoan_km', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
	}
	public function ketoan_ln() {
		$this->document->setTitle('Hoa hồng trực tiếp');
		$this->load->model('pd/pd');

		$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this -> load -> model('pd/registercustom');


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 20;
		$start = ($page - 1) * 20;

		$ts_history = $this -> model_pd_registercustom -> get_count_ln_wallet_bk();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/ketoan/ketoan_km', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['customer'] =  $this-> model_pd_registercustom->get_all_customer_ln_wallet_bk($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/ketoan_ln.tpl', $data));
	}

	public function up_ln()
	{
		$this -> load -> model('pd/registercustom');
		$this -> model_pd_registercustom->update_status_ln_bk($_GET['status'],$_GET['id']);
		$this->response->redirect($this->url->link('pd/ketoan/ketoan_ln', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
	}
}