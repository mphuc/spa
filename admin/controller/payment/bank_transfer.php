<?php
class ControllerPaymentBankTransfer extends Controller {
	private $error = array();

	public function index() {

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		//get all payment blockchain
		$this->load->model('payment/bitcoin');
		$data['users_payment'] = array();
		$data['payment_bitcoin'] = $this -> model_payment_bitcoin -> get_payment_bitcoin(); 



		$data['timer'] = 0;
		$data['wallet'] = $this -> model_payment_bitcoin -> getTimer_Wallet();
		if(intval($data['payment_bitcoin']) === 0 ){
			//get timer
			$data['timer'] = $this -> model_payment_bitcoin -> getTimer();
			$data['link'] = $this -> url -> link('payment/authorizenet_aim','', 'SSL');
			$data['token'] = $this -> request -> get['token'];

		}else{
			$data['payment_bitcoin_send'] = $this -> model_payment_bitcoin -> get_amount_total();

			$data['payment_bitcoin_send'] = $data['payment_bitcoin_send']['total'];

			$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

			$limit = 10;
			$start = ($page - 1) * 10;


			$pagination = new Pagination();
			$pagination -> total = $data['payment_bitcoin'];
			$pagination -> page = $page;
			$pagination -> limit = $limit;
			$pagination -> num_links = 5;
			$pagination -> text = 'text';
			$pagination -> url = $this -> url -> link('payment/bank_transfer', 'token='.$this -> request -> get['token'].'&page={page}', 'SSL');

			$data['pagination'] = $pagination -> render();

			$data['users_payment'] = $this -> model_payment_bitcoin -> get_bitcoin_wallet($limit, $start);
		}
		$this->response->setOutput($this->load->view('payment/bank_transfer.tpl', $data));
	}

}