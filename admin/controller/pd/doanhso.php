<?php
class ControllerPdDoanhso extends Controller {
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
		
		$data['action_upgrade'] = $this->url->link('pd/doanhso/submit', 'token=' . $this->session->data['token'], 'SSL');


		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/doanhso.tpl', $data));
	}

	

	public function submit(){

		$this->load->model('pd/register');
		if ($this->request->server['REQUEST_METHOD'] === 'POST'){
			$this -> load -> model('pd/registercustom');
			//tong doanh so
			$sum_total_pd_all = $this -> model_pd_registercustom -> sum_total_pd_all();
			if ($sum_total_pd_all > 0)
			{	
				$package_90 = $this -> model_pd_registercustom -> get_packege_lager_doanhso_90();
				foreach ($package_90 as $value) {

					$check_packet_active = $this -> model_pd_registercustom -> get_ml_child_active($value['customer_id'],9000000);
					if (intval($check_packet_active) >= 3)
					{
						$amount = $sum_total_pd_all / count($package_90) * 1 /100;

						$amount = round($amount,2);

						$this -> model_pd_registercustom ->update_amount_km_wallet($value['customer_id'],$amount,true);
						$balance_km = $this -> model_pd_registercustom -> Get_amount_KM_Wallet($value['customer_id']);
						$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
			                $value['customer_id'],
			                'Đồng chia tổng doanh số', 
			                '+ ' . ($amount/1000) . ' ĐT',
			                "Nhận 1 % hoa hồng từ tổng doanh số ".($sum_total_pd_all/1000)." ĐT. Với số gói tri ân lớn hơn hoặc bằng 9.000.000 VNĐ là ".count($package_90)." gói",
			                $balance_km
			                );
						$this -> conghuong($value['customer_id'],$amount);
					}
				}


				$package_100 = $this -> model_pd_registercustom -> get_packege_lager_doanhso_100();
				foreach ($package_100 as $value) {
					$amount = $sum_total_pd_all / count($package_100) * 2 /100;
					$amount = round($amount,2);
					$this -> model_pd_registercustom ->update_amount_km_wallet($value['customer_id'],$amount,true);
					$balance_km = $this -> model_pd_registercustom -> Get_amount_KM_Wallet($value['customer_id']);
					$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
		                $value['customer_id'],
		                'Đồng chia tổng doanh số', 
		                '+ ' . ($amount/1000) . ' ĐT',
		                "Nhận 2 % hoa hồng từ tổng doanh số ".(number_format($sum_total_pd_all/1000))." ĐT. Với số gói tri ân lớn hơn hoặc bằng 100.000.000 VNĐ là ".count($package_100)." gói",
		                $balance_km
		                );
					$this -> conghuong($value['customer_id'],$amount);
				}

				$package_200 = $this -> model_pd_registercustom -> get_packege_lager_doanhso_200();
				foreach ($package_200 as $value) {
					$amount = $sum_total_pd_all / count($package_200) * 1 /100;
					$amount = round($amount,2);
					$this -> model_pd_registercustom ->update_amount_km_wallet($value['customer_id'],$amount,true);
					$balance_km = $this -> model_pd_registercustom -> Get_amount_KM_Wallet($value['customer_id']);
					$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
		                $value['customer_id'],
		                'Đồng chia tổng doanh số', 
		                '+ ' . ($amount/1000) . ' ĐT',
		                "Nhận 1 % hoa hồng từ tổng doanh số ".(number_format($sum_total_pd_all/1000))." ĐT. Với số gói tri ân lớn hơn hoặc bằng 200.000.000 VNĐ là ".count($package_200)." gói",
		                $balance_km
		                );
					$this -> conghuong($value['customer_id'],$amount);
				}


				$package_500 = $this -> model_pd_registercustom -> get_packege_lager_doanhso_500();
				foreach ($package_500 as $value) {
					$amount = $sum_total_pd_all / count($package_500) * 1 /100;
					$amount = round($amount,2);
					$this -> model_pd_registercustom ->update_amount_km_wallet($value['customer_id'],$amount,true);
					$balance_km = $this -> model_pd_registercustom -> Get_amount_KM_Wallet($value['customer_id']);
					$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
		                $value['customer_id'],
		                'Đồng chia tổng doanh số', 
		                '+ ' . ($amount/1000) . ' ĐT',
		                "Nhận 1 % hoa hồng từ tổng doanh số ".(number_format($sum_total_pd_all/1000))." ĐT. Với số gói tri ân lớn hơn hoặc bằng 500.000.000 VNĐ là ".count($package_500)." gói",
		                $balance_km
		                );
					$this -> conghuong($value['customer_id'],$amount);
				}

				$package_1450 = $this -> model_pd_registercustom -> get_packege_lager_doanhso_1450();
				foreach ($package_1450 as $value) {
					$amount = $sum_total_pd_all / count($package_1450) * 1 /100;
					$amount = round($amount,2);
					$this -> model_pd_registercustom ->update_amount_km_wallet($value['customer_id'],$amount,true);
					$balance_km = $this -> model_pd_registercustom -> Get_amount_KM_Wallet($value['customer_id']);
					$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
		                $value['customer_id'],
		                'Đồng chia tổng doanh số', 
		                '+ ' . ($amount/1000) . ' ĐT',
		                "Nhận 1 % hoa hồng từ tổng doanh số ".(number_format($sum_total_pd_all/1000))." ĐT. Với số gói tri ân lớn hơn hoặc bằng 1.450.000.000 VNĐ là ".count($package_1450)." gói",
		                $balance_km
		                );
					$this -> conghuong($value['customer_id'],$amount);
				}
			}
	
			$this -> model_pd_registercustom -> upadate_totla_pd_0();
			$this-> session -> data['complaete'] = "complaete";
			$this->response->redirect($this->url->link('pd/doanhso', 'token=' . $this->session->data['token'], 'SSL'));
			
		}
		
	}
	

	public function conghuong($customer_id,$amount)
	{

		$get_customer = $this -> model_pd_registercustom -> getCustomer($customer_id);

		$get_parent = $this -> model_pd_registercustom -> getCustomer_ml($get_customer['p_node']);
		if (count($get_parent) > 0 )
		{
			if ($get_parent['level'] >= 2)
			{
				$get_parent_f = $this -> model_pd_registercustom -> get_goidautu($get_customer['p_node']);
				switch ($get_parent_f['package']) {
					case 3000000:
						$percent = 10;
						break;
					case 6000000:
						$percent = 15;
						break;
					case 9000000:
						$percent = 20;
						break;
					case 100000000:
						$percent = 20;
						break;
					case 200000000:
						$percent = 20;
						break;
					case 500000000:
						$percent = 20;
						break;
					case 1450000000:
						$percent = 20;
						break;
					default:
						$percent = 0;
						break;
				}
				if ($percent > 0)
				{


					$amount_recevie = $amount * $percent/ 100;
					$this -> model_pd_registercustom ->update_amount_ch_wallet($get_parent['customer_id'],$amount_recevie,true);
					
					$balance_ch = $this -> model_pd_registercustom -> Get_amount_CH_Wallet($get_parent['customer_id']);

					$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
		                $get_parent['customer_id'],
		                'Hoa hồng cộng hưởng', 
		                '+ ' . ($amount_recevie/1000) . ' ĐT',
		                "Nhận ".$percent." % hoa hồng từ cộng hưởng từ ".($get_customer['username'])." nhận ".($amount/1000)." ĐT",
		                $balance_ch
		                );
				}
			}
		}
		

	}

}