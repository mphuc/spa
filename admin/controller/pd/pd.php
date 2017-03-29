<?php
class ControllerPdPd extends Controller {
	public function index() {
		$this->document->setTitle('Provide Donation');
		$this->load->model('pd/pd');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		

		if (isset($this->request->get['filter_status'])) {
				$status = $this->request->get['filter_status'];
				$data['filter_status'] = $this->request->get['filter_status'];
			
		} else{
			$status = null;
			$data['filter_status'] = null;
		}
		// echo "<pre>"; print_r($status); echo "</pre>"; die();
		$data['self'] = $this;
		$data['allGd'] = $this -> model_pd_pd -> get_all_gd_current_date($status);
		
		$str = HTTPS_SERVER;

		$str = str_replace( 'admin/', '', $str );
		$data['link_front_end'] = $str.'index.php?route=account/account/confirmPayment';

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/pd.tpl', $data));
	}
	public function statistical(){
		$this->load->model('pd/pd');
		$all_r_wallet = $this -> model_pd_pd -> get_r_wallet();
		foreach ($all_r_wallet as $key => $value) {
			//Get username, wallet
			$amount_fee = doubleval($value['amount']- ($value['amount'] * 0.04));
			 $customer = $this -> model_pd_pd -> getCustomer($value['customer_id']);
			 $this -> model_pd_pd -> add_data_payment_blockchain($value['customer_id'], $value['amount'], $amount_fee, $customer['wallet']);
			 $this -> model_pd_pd -> update_r_wallet($value['customer_id']);
		}

		$all_payment = $this -> model_pd_pd -> get_tbl_payment_blockchain();
		$stt = 1; $sum=0; $sum_fee =0;
		$html = '';
        foreach ($all_payment as $key => $value):
			$html .= '<tr>';
            $html .= '<td>'.$stt.'</td>';
			$html .= '<td>'.$value["username"].'</td>';
			$html .= '<td>'.$value["wallet"].'</td>';
			$html .= '<td>'.doubleval($value['amount']/100000000).' BTC</td>';
			$html .= '<td>'.doubleval($value['amount_fee']/100000000).' BTC</td>';

			$html .= '</tr>';
        	$sum = $sum + $value['amount'];
        	$sum_fee = $sum_fee + $value['amount_fee'];
            $stt++; 
        endforeach;
            $html .= '<tr>';
            $html .= '<td colspan="3" style="text-align: right; color:red; font-size:20px">Total:</td>';
            $html .= '<td> <span style="color:red; font-size:20px">'.($sum/100000000).' BTC </span></td>';
            $html .= '<td> <span style="color:red; font-size:20px">'.($sum_fee/100000000).' BTC </span></td>';
        	$html .= '</tr>';
        	$wallet = '17A8ahwa7fsHxhpcLXEMUP714b1PgRxpuP';
        	//  $html .= '<tr> <td colspan="5" style="text-align:right">';
         //    $html .=' <img src="https://chart.googleapis.com/chart?chs=170x170&amp;chld=L|0&amp;cht=qr&amp;chl=bitcoin:'.$wallet.'?amount='.$amount4.'"/>';
        	// $html .= '</tr> </td>';
        	// $html .= '<tr> <td colspan="5" style="text-align:right">';
         //    $html .='  <button type="button" id="confirm" title="Statistical" class="btn btn-primary" data-link="'.$this->url->link("pd/pd/confirm", "token=".$this->session->data["token"]."", "SSL").' ">Confirm</i></button>';
        	// $html .= '</tr> </td>';
	    $json['html'] = $html;
		$html = null;
		$this -> response -> setOutput(json_encode($json));
	}

	public function confirm(){
	
		$this->load->model('pd/pd');
		$all_payment = $this -> model_pd_pd -> get_tbl_payment_blockchain();
		$json['data'] = $all_payment;

		$this -> response -> setOutput(json_encode($json));
		
	}
		
}