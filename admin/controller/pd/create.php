<?php
class ControllerPdCreate extends Controller {
	public function index() {
		$this->document->setTitle('Create New');
		$this->load->model('pd/pd');

	$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
		$this -> document -> addStyle('../catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/create', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		

		
		$data['action_upgrade'] = $this->url->link('pd/create/submit', 'token=' . $this->session->data['token'], 'SSL');

		$data['check_p_binary'] = $this->url->link('pd/register/get_position', 'token=' . $this->session->data['token'], 'SSL');


		$data['getaccount'] = $this->url->link('pd/upgrade/getaccount&token='.$this->session->data['token']);

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/create.tpl', $data));
	}

	public function get_position($p_binary){
		$this -> load -> model('pd/register');
		$p_binary = $this -> request -> get['p_binary'];
		$check_pbinary = $this -> model_pd_register ->get_customer_Id_by_username($p_binary);

		$check_p_binary = $this -> model_pd_register -> count_p_binary($check_pbinary['customer_id']);
		if (!empty($check_p_binary)) {
			$html ='';
			if (intval($check_p_binary['left']) === 0 && intval($check_p_binary['right']) === 0 ) {
				$html .= '<option value="">Chọn vị trí</option>';
	            $html .= '<option value="left">Trái</option>';
	            $html .= '<option value="right">Phải</option>';
			} elseif (intval($check_p_binary['left']) === 0 && intval($check_p_binary['right']) !== 0 ) {
				$html .= '<option value="">Chọn vị trí</option>';
	            $html .= '<option value="left">Trái</option>';
	            
			} elseif (intval($check_p_binary['left']) !== 0 && intval($check_p_binary['right']) === 0 ) {
				$html .= '<option value="">Chọn vị trí</option>';
	            $html .= '<option value="right">Phải</option>';
			}
			$json['html'] = $html;

			
		} else{
			$json['html'] = null;
		}
		$this -> response -> setOutput(json_encode($json));
		
	}
	
	public function getaccount() {
		if ($this -> request -> post['keyword']) {
			$this -> load -> model('pd/register');
			$tree = $this -> model_pd_register -> getCustomLike($this -> request -> post['keyword']);
			
			if (count($tree) > 0) {
				foreach ($tree as $value) {
					 echo '<li class="list-group-item"  onClick="selectU(' . "'" . $value['name'] . "'" . ','.$value['customer_id'].');">' . $value['name'] . '</li>';
				}
			}
		}
	}

	public function submit(){
		$this->load->model('pd/register');
		if ($this->request->server['REQUEST_METHOD'] === 'POST'){
			$username = $this->request->post['username'];
			$amount = $this->request->post['investment'];
			
			$tmp = $this ->model_pd_register -> add_code($this->request->post);
			
			$this -> session -> data['success'] = $this -> language -> get('Upgradesuccess!');
			// $this->update_C_wallet($customer_id, $amount);
			// $this->response->redirect($this->url->link('pd/upgrade', 'token=' . $this->session->data['token'], 'SSL'));
			$code =  $tmp['code'];
			$hash = "#".$this->chuyenChuoi($this->request->post['username'])."-".number_format($this->request->post['investment'])."-".$code."-".$this->request->post['telephone']."-".$this->chuyenChuoi($this->request->post['address'])."";
			//$this -> response -> redirect($this -> url -> link('pd/create', 'token=' . $this->session->data['token'].$hash, 'SSL'));
			$data['code'] = $code;
			$data['username'] = $this->request->post['username'];
			$data['investment'] = $this->request->post['investment'];
		
			$data['telephone'] = $this->request->post['telephone'];
			$data['address'] = $this->request->post['address'];
			require_once dirname(__FILE__) . '/SpeedSMSAPI.php';
			$sms = new SpeedSMSAPI();
			//$this ->sendSMS([''.$data['telephone'].''], ''.$data['code'].'');
			
			$this->response->setOutput($this->load->view('pd/printcode.tpl', $data));
			/*$data['token'] = $this->session->data['token'];
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('pd/upgrade.tpl', $data));*/
		}
		
	}

	

	public function getUserInfo() {
	    $sms = new SpeedSMSAPI();
	    $userInfo = $sms->getUserInfo();
	    var_dump($userInfo);
	}

	public function sendSMS($phones, $content) {
	    $sms = new SpeedSMSAPI();
	    $return = $sms->sendSMS($phones, $content, SpeedSMSAPI::SMS_TYPE_CSKH, "", 1);
	    var_dump($return);
	}

	

	public function chuyenChuoi($str) {
// In thường
     $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
     $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
     $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
     $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
     $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
     $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
     $str = preg_replace("/(đ)/", 'd', $str);    
// In đậm
     $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
     $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
     $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
     $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
     $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
     $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
     $str = preg_replace("/(Đ)/", 'D', $str);
     return $str; // Trả về chuỗi đã chuyển
}  
	public function update_C_wallet($customer_id, $amount){
		$this->load->model('pd/register');
		$customer = $this -> model_pd_register -> getCustomerCustom($customer_id);

        $partent = $this -> model_pd_register -> getCustomerCustom($customer['p_node']);
        $investment_parrent = $this -> model_pd_register -> get_filled_by_id($partent['customer_id']);
         $investment_customer = $this -> model_pd_register -> get_filled_by_id($customer_id);

    	if (intval($investment_parrent['sum_filled']) <= intval($customer['package'])) {
    		switch (intval($investment_parrent['sum_filled'])) {
	    		case 5000000:
	    			$per = 10;
	    			break;
	    		case 20000000:
	    			$per = 15;
	    			break;
	    		case 50000000:
	    			$per = 18;
	    			break;
	    		case 100000000:
	    			$per = 20;
	    			break;
	    		case 500000000:
	    			$per = 25;
	    			break;
	    		case 1000000000:
	    			$per = 32;
	    			break;
    		}
    	
    		$price = (intval($amount) * $per) / 100;
    	} else{
    		switch (intval($investment_customer['sum_filled'])) {
	    		case 5000000:
	    			$per = 10;
	    			break;
	    		case 20000000:
	    			$per = 15;
	    			break;
	    		case 50000000:
	    			$per = 18;
	    			break;
	    		case 100000000:
	    			$per = 20;
	    			break;
	    		case 500000000:
	    			$per = 25;
	    			break;
	    		case 1000000000:
	    			$per = 32;
	    			break;
    		}
    		$price = (intval($amount) * $per) / 100;
    	}
    	
		$double = intval($investment_parrent['sum_filled'])*2;

		if ($price > $double) {
			$per_comission = $double;
		}else {
			$per_comission = $price;
		}
		

		$this -> model_pd_register -> update_C_Wallet($per_comission, $partent['customer_id']);
		$this -> model_pd_register -> saveTranstionHistory($partent['customer_id'], 'Ví Hoa hồng', '+ ' . number_format($per_comission) . ' VND', "Thưởng trực tiếp ".$per." % từ thành viên ".$customer['username']." đầu tư gói  (".number_format($amount)." VND)");
		$this -> update_vnd_binary($customer_id, $amount);
	}
	public function update_vnd_binary($customer_id, $amount){
		$customer_ml = $this -> model_pd_register -> getTableCustomerMLByUsername($customer_id);
		$customer = $this -> model_pd_register -> getCustomerCustom($customer_id);	
			$customer_first = true ;
			if(intval($customer_ml['p_binary']) !== 0){
				while (true) {
					//lay thang cha trong ban Ml
					$customer_ml_p_binary = $this -> model_pd_register -> getTableCustomerMLByUsername($customer_ml['p_binary']);

					if($customer_first){
						//kiem tra la customer dau tien vi day la gia tri callback mac dinh
						if(intval($customer_ml_p_binary['left']) === intval($customer_id))  {
							//nhanh trai
							$this -> model_pd_register -> update_pd_binary(true, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh trái', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']." đầu tư gói (".number_format($amount)." VNĐ)");
						}else{
							//nhanh phai
							$this -> model_pd_register -> update_pd_binary(false, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh phải', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']." đầu tư gói (".number_format($amount)." VNĐ)");
						}
						$customer_first = false;
					}else{
			
						if(intval($customer_ml_p_binary['left']) === intval($customer_ml['customer_id']) ) {
							//nhanh trai
							$this -> model_pd_register -> update_pd_binary(true, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh trái', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']." đầu tư gói (".number_format($amount)." VNĐ)");
							
						}else{
							//nhanh phai
							$this -> model_pd_register -> update_pd_binary(false, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh phải', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']." đầu tư gói (".number_format($amount)." VNĐ)");
						}
					}
					
					

					if(intval($customer_ml_p_binary['customer_id']) === 1){
						break;
					}
					//lay tiep customer de chay len tren lay thang cha
					$customer_ml = $this -> model_pd_register -> getTableCustomerMLByUsername($customer_ml_p_binary['customer_id']);

				} 
			}
	}
	public function send_sms($phone_send,$content){
		$APIKey="70152DEE3829626055A11C11E1F766";
		$SecretKey="86CF68BD01032D2E48DD90FDE471D8";
		$YourPhone = $phone_send;
		$content = $content;
        $ch = curl_init();
		$SampleXml = "<RQST>"
           . "<APIKEY>". $APIKey ."</APIKEY>"
           . "<SECRETKEY>". $SecretKey ."</SECRETKEY>"                                    
           . "<ISFLASH>0</ISFLASH>"
   			."<SMSTYPE>4</SMSTYPE>"
           . "<CONTENT>". ''.$content.'' ."</CONTENT>"
           . "<CONTACTS>"
           . "<CUSTOMER>"
           . "<PHONE>". $YourPhone ."</PHONE>"
           . "</CUSTOMER>"                               
           . "</CONTACTS>"
		   . "</RQST>";
							   		
							   
		curl_setopt($ch, CURLOPT_URL,            "http://api.esms.vn/MainService.svc/xml/SendMultipleMessage_V2/" );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_POST,           1 );
		curl_setopt($ch, CURLOPT_POSTFIELDS,     $SampleXml ); 
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

		$result=curl_exec ($ch);		
		$xml = simplexml_load_string($result);

		if ($xml === false) {
			die('Error parsing XML');   
		}
		print "$xml->CodeResult";   		
		
	}

	public function createInvestment($customer_id, $amount){
		$this->load->model('pd/register');
		$pd_query = $this -> model_pd_register -> createPD($customer_id, $amount);
	}
	public function print_code() {
		$this->document->setTitle('Print code');
		$data = array();
		$this->response->setOutput($this->load->view('pd/printcode.tpl', $data));
	}
	
}