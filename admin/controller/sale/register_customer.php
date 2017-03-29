<?php
class ControllerSaleRegisterCustomer extends Controller {
	private $error = array();

	public function index() {
		$data = "";
		$data['url_register'] = $this->url->link('sale/register_customer/register', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('sale/register_customer.tpl', $data));
	}
	public function register(){
		$this -> load -> model('sale/register');
		if ($_POST)
		{
			$username = substr(hash_hmac('ripemd160', hexdec(crc32(md5(microtime()))), 'secret'), 0, 16);
			print_r($_POST); die;
		}
	}
	

}
