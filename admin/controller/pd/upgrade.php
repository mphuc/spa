<?php
class ControllerPdUpgrade extends Controller {
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
		

		
		$data['action_upgrade'] = $this->url->link('pd/upgrade/submit', 'token=' . $this->session->data['token'], 'SSL');


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/upgrade.tpl', $data));
	}

	

	public function submit(){

		$this->load->model('pd/register');
		if ($this->request->server['REQUEST_METHOD'] === 'POST'){
			$loinhuan = $this -> request -> post['loinhuan'];
			$this -> load -> model('pd/registercustom');
			$get_packege_lager = $this -> model_pd_registercustom -> get_packege_lager();
			foreach ($get_packege_lager as $value) {
				print_r($value);
				switch ($value['filled']) {
					case 100000000:
						$percent = 5;
						break;
					case 200000000:
						$percent = 6;
						break;
					case 500000000:
						$percent = 7;
						break;
					case 1450000000:
						$percent = 10;
						break;	
					default:
						$percent = 0;
						break;
				}

				$amount = doubleval($loinhuan) * $percent / 100;

				$this -> model_pd_registercustom ->update_amount_ln_wallet($value['customer_id'],$amount,true);

				$balanece_ln = $this -> model_pd_registercustom -> Get_amount_LN_Wallet($value['customer_id']);

			 	$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
	                $value['customer_id'],
	                'Hoa hồng chia lợi nhuận chuỗi spa', 
	                '+ ' . (number_format($amount)) . ' VNĐ',
	                "Nhận ".$percent."% lợi nhuận từ spa khi tri ân gói ".(number_format($value['filled']))." VNĐ.",
	                $balanece_ln
	                );
			}

			$this-> session -> data['complaete'] = "complaete";
			$this->response->redirect($this->url->link('pd/upgrade', 'token=' . $this->session->data['token'], 'SSL'));
			
		}
		
	}
	
}