<?php
class ControllerPdStatistical extends Controller {
	public function index() {
		$this->document->setTitle('Provide Donation');
		$this->load->model('pd/pd');

	$this -> document -> addScript('view/javascript/register/register.js');

		$this -> document -> addScript('../catalog/view/javascript/countdown/jquery.countdown.min.js');
		$this -> document -> addScript('../catalog/view/javascript/pd/countdown.js');
		
		$this ->load->model('sale/customer');
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/statistical.tpl', $data));
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

				$cus_id= $tmp;
				$username = hexdec(crc32(md5($cus_id)));
				$this -> model_pd_register -> update_username_customer($tmp, $username);

				$amount = 0;
				$checkC_Wallet = $this -> model_pd_register -> checkC_Wallet($cus_id);
				if(intval($checkC_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertC_Wallet($amount, $cus_id)){
						die();
					}
				}
				$checkR_Wallet = $this -> model_pd_register -> checkR_Wallet($cus_id);
				if(intval($checkC_Wallet['number'])  === 0){
					if(!$this -> model_pd_register -> insertR_Wallet($amount, $cus_id)){
						die();
					}
				}
				$this -> createInvestment($cus_id, $this->request->post['investment']);
				$this->update_C_wallet($cus_id);
				

				$data['has_register'] = true;
				$mail = new Mail();
				$mail -> protocol = $this -> config -> get('config_mail_protocol');
				$mail -> parameter = $this -> config -> get('config_mail_parameter');
				$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
				$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
				$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
				$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

				$mail -> setTo($this -> request -> post['email']);
				$mail -> setFrom($this -> config -> get('config_email'));
				$mail -> setSender(html_entity_decode("Công Ty TNHH SX TM VẬN ẢI BIÊỂN HƯNG THỊNH PHÁT", ENT_QUOTES, 'UTF-8'));
				$mail -> setSubject("Công Ty TNHH SX TM VẬN ẢI BIÊỂN HƯNG THỊNH PHÁT - TÀI KHOẢN CỦA BẠN ĐÃ TẠO THÀNH CÔNG!");
				$mail -> setHtml('
					<h1><span style="font-size:12px">CHÚC MỪNG TÀI KHOẢNC CỦA BẠN ĐÃ TẠO THÀNH CÔNG!</span></h1>
					<p><span style="font-size:12px"><strong>What is Next?</strong></span></p>
					<p><span style="font-size:12px">Bây giời bạn có thể &nbsp;<a href="' . $this -> url -> link("account/login", "", "SSL") . '" style="color:rgb(0,72,153);background:transparent" target="_blank">Đăng nhập</a> Sử dụng tên đăng nhập&nbsp;<strong> và&nbsp;</strong><strong>mật khẩu</strong>, và  bắt đầu ử dụng website.</span></p>
					<p><span style="font-size:12px">&nbsp;<a href="' . HTTPS_SERVER . '" target="_blank">http://hungthinhphatcorp.com/</a></span></p>
					<p><span style="font-size:12px">-  Họ Tên : ' . $this -> request -> post["firstname"] . '</span></p>
					<p><span style="font-size:12px">-  Địa chỉ : ' . $this -> request -> post["address"] . '</span></p>
					<p><span style="font-size:12px">-  Mã đăng nhập : ' . $username . '</span></p>
					<p><span style="font-size:12px">- Mật khẩu : ' . $this -> request -> post["password"] . '</span></p>
					
					<p><span style="font-size:12px"><span style="font-family:arial,helvetica,sans-serif">If you have any questions, feel free to contact us by using our support center in the adress belov</span></span></p>
					<p><strong><span style="font-size:12px">HTP support team!</span></strong></p>
				');
				
				$mail -> send();
				$phone = $this -> request -> post["telephone"];
				$password = "admin123";
				$content = "hung thinh phat
				 	Username : ".$username."
				 	Password : ".$password."
				 ";
				$this -> send_sms($phone,$content);
				
				$this -> session -> data['success'] = $this -> language -> get('Create account success!');
				
				$this->response->redirect($this->url->link('pd/register', 'token=' . $this->session->data['token'], 'SSL'));
				
			} else{
				die('Không tồn tại nhánh hoặc người bảo trợ!');
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

	public function update_C_wallet($customer_id){
		$this->load->model('pd/register');
		$customer = $this -> model_pd_register -> getCustomerCustom($customer_id);

        $partent = $this -> model_pd_register -> getCustomerCustom($customer['p_node']);

    	switch (intval($partent['package'])) {
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

		$double = intval($partent['package'])*2;

		if ($price > $double) {
			$per_comission = $double;
		} else{
			$per_comission = $price;
		}
		

		$this -> model_pd_register -> update_C_Wallet($per_comission, $partent['customer_id']);
		$this -> model_pd_register -> saveTranstionHistory($partent['customer_id'], 'Ví Hoa hồng', '+ ' . number_format($per_comission) . ' VND', "Thưởng trực tiếp ".$per." % từ thành viên ".$customer['username']." đầu tư gói  (".number_format($customer['package'])." VND)");
	}
}