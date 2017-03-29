<?php
class ControllerPdRegistercustom extends Controller {
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
		

		
		$data['action_dangky'] = $this->url->link('pd/register/submit', 'token=' . $this->session->data['token'], 'SSL');

		$data['check_p_binary'] = $this->url->link('pd/register/get_position', 'token=' . $this->session->data['token'], 'SSL');


		$data['getaccount'] = $this->url->link('pd/register/getaccount&token='.$this->session->data['token']);

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/register.tpl', $data));
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
					 echo '<li class="list-group-item" onClick="selectU(' . "'" . $value['name'] . "'" . ');">' . $value['name'] . '</li>';
				}
			}
		}
	}

	public function submit(){

		$this->load->model('pd/register');

		if ($this->request->server['REQUEST_METHOD'] === 'POST'){

			$check_pnode = $this -> model_pd_register ->get_customer_Id_by_username($this->request->post['p_node']);
			$check_pbinary = $this -> model_pd_register ->get_customer_Id_by_username($this->request->post['p_binary']);
			$check_p_binary = $this -> model_pd_register -> check_p_binary($this->request->post['p_binary']);
			
			if (intval(count($check_pnode)) === 1 && intval(count($check_pbinary)) === 1 && intval($check_p_binary['number']) != 2) {

				$tmp = $this -> model_pd_register -> addCustomer_custom($this->request->post);

				$cus_id= (int)$tmp['customer_id'];
				$pass = $tmp['pass'];
				$username = substr(hexdec(crc32(md5($cus_id))),0, 7);
				$this -> model_pd_register -> update_username_customer($cus_id, $username);

				$amount = 0;
				
				$checkC_Wallet = $this -> model_pd_register -> checkC_Wallet($cus_id);
				if(intval($checkC_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertC_Wallet($cus_id)){
						die();
					}
				}
				
				$checkR_Wallet = $this -> model_pd_register -> checkR_Wallet($cus_id);
				if(intval($checkC_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertR_Wallet($cus_id)){
						die();
					}
				}
				$checkM_Wallet = $this -> model_pd_register -> checkM_Wallet($cus_id);
				if(intval($checkM_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertM_Wallet($cus_id)){
						die();
					}
				}
				$checkCN_Wallet = $this -> model_pd_register -> checkCN_Wallet($cus_id);
				if(intval($checkCN_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertCN_Wallet($cus_id)){
						die();
					}
				}
				$checkCH_Wallet = $this -> model_pd_register -> checkCH_Wallet($cus_id);
				if(intval($checkCH_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertCH_Wallet($cus_id)){
						die();
					}
				}
				$checkTT_Wallet = $this -> model_pd_register -> checkTT_Wallet($cus_id);
				if(intval($checkTT_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertTT_Wallet($cus_id)){
						die();
					}
				}

				// lãi trực tiếp
				/*$check_show_pnode = $this -> model_pd_register ->check_show_pnode($cus_id);
				// lấy số tiền đầu tư
				$show_pd_customer = $this -> model_pd_register -> show_pd_customer($check_show_pnode['p_node']);
				$show_pd_customer['filled'];*/


				
				
				//Update thuong quan ly
				$this -> createInvestment($cus_id, $this->request->post['investment']);

				$data['has_register'] = true;
				// $mail = new Mail();
				// $mail -> protocol = $this -> config -> get('config_mail_protocol');
				// $mail -> parameter = $this -> config -> get('config_mail_parameter');
				// $mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
				// $mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
				// $mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				// $mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
				// $mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

				// $mail -> setTo($this -> request -> post['email']);
				// $mail -> setFrom($this -> config -> get('config_email'));
				// $mail -> setSender(html_entity_decode("Công Ty TNHH SX TM VẬN ẢI BIÊỂN HƯNG THỊNH PHÁT", ENT_QUOTES, 'UTF-8'));
				// $mail -> setSubject("Công Ty TNHH SX TM VẬN ẢI BIÊỂN HƯNG THỊNH PHÁT - TÀI KHOẢN CỦA BẠN ĐÃ TẠO THÀNH CÔNG!");
				// $mail -> setHtml('
				// 	<h1><span style="font-size:12px">CHÚC MỪNG TÀI KHOẢNC CỦA BẠN ĐÃ TẠO THÀNH CÔNG!</span></h1>
				// 	<p><span style="font-size:12px"><strong>What is Next?</strong></span></p>
				// 	<p><span style="font-size:12px">Bây giời bạn có thể &nbsp;<a href="' . $this -> url -> link("account/login", "", "SSL") . '" style="color:rgb(0,72,153);background:transparent" target="_blank">Đăng nhập</a> Sử dụng tên đăng nhập&nbsp;<strong> và&nbsp;</strong><strong>mật khẩu</strong>, và  bắt đầu ử dụng website.</span></p>
				// 	<p><span style="font-size:12px">&nbsp;<a href="' . HTTPS_SERVER . '" target="_blank">http://hungthinhphatcorp.com/</a></span></p>
				// 	<p><span style="font-size:12px">-  Họ Tên : ' . $this -> request -> post["firstname"] . '</span></p>
				// 	<p><span style="font-size:12px">-  Địa chỉ : ' . $this -> request -> post["address"] . '</span></p>
				// 	<p><span style="font-size:12px">-  Mã đăng nhập : ' . $username . '</span></p>
				// 	<p><span style="font-size:12px">- Mật khẩu : ' . $this -> request -> post["password"] . '</span></p>
					
				// 	<p><span style="font-size:12px"><span style="font-family:arial,helvetica,sans-serif">If you have any questions, feel free to contact us by using our support center in the adress belov</span></span></p>
				// 	<p><strong><span style="font-size:12px">HTP support team!</span></strong></p>
				// ');
				
				// $mail -> send();
				$phone = $this -> request -> post["telephone"];
				$password = "admin123";
				$content = "CÔNG TY TNHH TM SX VẬN TẢI BIỂN HƯNG THỊNH PHÁT
				 	Username : ".$username."
				 	Password : ".$pass."
				 ";
				//$this -> sms($phone,$content);
				
				$this -> session -> data['success'] = $this -> language -> get('Create account success!');
				

				//$this->response->redirect($this->url->link('pd/register', 'token=' . $this->session->data['token'], 'SSL'));
				if (($this->request->post['check_user']) == 'enable') {
					
					$this->update_C_wallet($cus_id);
					$p_binary = $this -> model_pd_register -> get_customer_Id_by_username($this->request->post['p_binary']);
					$p_binary= $p_binary['customer_id'];
					$this -> get_customer_by_binary($cus_id, $p_binary);
				}else{
					$this -> model_pd_register -> update_status_r_wallet($cus_id);
				}
				

				$this->response->redirect($this->url->link('pd/register/prints', 'token=' . $this->session->data['token'].'&id_customer='.$cus_id.'&pass='.$pass, 'SSL'));

			} else{
				die('Không tồn tại nhánh hoặc người bảo trợ!');
			}

			
		}
		
	}
	public function sms($phone,$content){

	$this->sendSMS([''.$phone.''], ''.$content.'');
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
	//=== Tai==
	public function get_customer_by_binary($customer_id, $p_binary){
		$this->load->model('pd/register');

		// $p_binary = $this -> model_pd_register -> get_p_binary_by_customer_id($customer_id);
		$customer = $this -> model_pd_register -> getCustomerCustom($customer_id);
		
		$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($p_binary);

		$per_comission = 100000;
		//Tang 1
		if (intval(count($customer_p_binary)) > 0) {

			$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
			$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");

			//Tang 2
			$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
				
			if (intval(count($customer_p_binary)) > 0) {
				$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
				$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
				
				//Tang 3
				$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
				if (intval(count($customer_p_binary)) > 0) {
					$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
					$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
					
					//Tang 4
					$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
					if (intval(count($customer_p_binary)) > 0) {
						$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
						$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
						
						//Tang 5
						$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
						if (intval(count($customer_p_binary)) > 0) {
							$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
							$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
							
							//Tang 6
							$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
							if (intval(count($customer_p_binary)) > 0 && intval($customer['package']) > 5000000) {
								$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
								$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
								
								//Tang 7
								$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
								if (intval(count($customer_p_binary)) > 0) {
									$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
									$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
									
									//Tang 8
									$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
									if (intval(count($customer_p_binary)) > 0 && intval($customer['package']) >= 20000000) {
										$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
										$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
										
										//Tang 9
										$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
										if (intval(count($customer_p_binary)) > 0 && intval($customer['package']) >= 50000000) {
											$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
											$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
											
											//Tang 10
											$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
											if (intval(count($customer_p_binary)) > 0 && intval($customer['package']) >= 100000000) {
												$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
												$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
												
												//Tang 11
												$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
												if (intval(count($customer_p_binary)) > 0 && intval($customer['package']) >= 500000000) {
													$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
													$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
													
													//Tang 12
													$customer_p_binary = $this -> model_pd_register -> get_customer_by_binary($customer_p_binary['p_binary']);
													if (intval(count($customer_p_binary)) > 0 && intval($customer['package']) >= 1000000000) {
														$this -> model_pd_register -> update_CH_Wallet($per_comission, $customer_p_binary['customer_id']);
														$this -> model_pd_register -> saveTranstionHistory($customer_p_binary['customer_id'], 'Cộng hưởng', '+ 100,000 VND', "Thưởng Quản Lý từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
	}
	//= End
	public function createInvestment($customer_id, $amount){
		$this->load->model('pd/register');
		$pd_query = $this -> model_pd_register -> createPD($customer_id, $amount);
	}

	public function update_C_wallet($customer_id){
		$this->load->model('pd/register');
		$customer = $this -> model_pd_register -> getCustomerCustom($customer_id);

        $partent = $this -> model_pd_register -> getCustomerCustom($customer['p_node']);
        $investment_parrent = $this -> model_pd_register -> get_filled_by_id($partent['customer_id']);

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
    	
    		$price = (intval($customer['package']) * $per) / 100;
    	} else{
    		switch (intval($customer['package'])) {
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
    		$price = (intval($customer['package']) * $per) / 100;
    	}
    	
		$double = intval($investment_parrent['sum_filled'])*2;

		if ($price > $double) {
			$per_comission = $double;
		}else {
			$per_comission = $price;
		}
		

		$this -> model_pd_register -> update_C_Wallet($per_comission, $partent['customer_id']);
		$this -> model_pd_register -> saveTranstionHistory($partent['customer_id'], 'Ví Hoa hồng', '+ ' . number_format($per_comission) . ' VND', "Thưởng trực tiếp ".$per." % từ thành viên ".$customer['username']."-".$customer['firstname']." đầu tư gói  (".number_format($customer['package'])." VND)");
		$this -> update_vnd_binary($customer_id, $customer['package']);
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
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh trái', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']."-".$customer['firstname']." đầu tư gói (".number_format($amount)." VNĐ)");
						}else{
							//nhanh phai
							$this -> model_pd_register -> update_pd_binary(false, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh phải', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']."-".$customer['firstname']." đầu tư gói (".number_format($amount)." VNĐ)");
						}
						$customer_first = false;
					}else{
			
						if(intval($customer_ml_p_binary['left']) === intval($customer_ml['customer_id']) ) {
							//nhanh trai
							$this -> model_pd_register -> update_pd_binary(true, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh trái', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']."-".$customer['firstname']." đầu tư gói (".number_format($amount)." VNĐ)");
							
						}else{
							//nhanh phai
							$this -> model_pd_register -> update_pd_binary(false, $customer_ml_p_binary['customer_id'], $amount );
							$this -> model_pd_register -> saveTranstionHistory($customer_ml_p_binary['customer_id'], 'Doanh thu nhánh phải', '+ ' . number_format($amount) . ' VNĐ', "từ thành viên tuyến dưới ".$customer['username']."-".$customer['firstname']." đầu tư gói (".number_format($amount)." VNĐ)");
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
	public function prints(){
		$this -> load -> model('pd/registercustom');
		$data['pass'] = $this->request->get['pass'];
		if (isset($this->request->get['username'])){
			$id_user = $this -> model_pd_registercustom ->get_id_buy_username($this->request->get['username']);
			if (count($id_user) == 0) die("username không tồn tại");
			$cus_id = $id_user['customer_id'];
		}
		else
		{
			$cus_id = $this->request->get['id_customer'];
		}
		if (isset($this->request->get['update']))
		{
			if ($this->request->get['update'] == 1){
				$salt = substr(md5(uniqid(rand(), true)), 0, 9);
				$password = mt_rand(9,999999);
				$pass = sha1($salt . sha1($salt . sha1($password)));echo "<br/>";
				$this -> model_pd_registercustom -> update_pass($cus_id,$pass,$salt);
				$data['pass'] = $password;
			?>
				<script>
					jquery('.pass').html("111111");
				</script>
			<?php
			}
		}
		
		$this->load->model('pd/register');
		$data['info_customer'] = $this -> model_pd_register -> get_customer_print($cus_id);
		$data['self'] = $this;
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/print.tpl', $data));
	}
	public function get_username($customer_id){
		$this->load->model('pd/register');
		$username = $this -> model_pd_register -> get_username_by_id($customer_id);
		
		return $username['username'];

	}
}