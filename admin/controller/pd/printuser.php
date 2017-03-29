<?php
class ControllerPdPrintuser extends Controller {
	private $error = array();

	public function index() {
		$data['self'] = $this ;
		$this -> load -> model('pd/registercustom');
		$id = $_GET['id'];
		$data['getCustomer'] = $this -> model_pd_registercustom -> getCustomer($id);
		//print_r($data['getCustomer']); die;
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/print_user.tpl', $data));
	}
	

	
	
	
}
