<?php
class ControllerAccountPd extends Controller {

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
			$self -> document -> addScript('catalog/view/javascript/pd/confirm.js');
			$self -> document -> addScript('catalog/view/javascript/bootstraptable/bootstrap-table-expandable.js');
			$self -> document -> addStyle('catalog/view/javascript/bootstraptable/bootstrap-table-expandable.css');
		};
		
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);
			
			
		$rows =  $this -> model_account_customer ->countPDINProvide();
		
		$data['countPd'] = count($rows);

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		
		$data['pds'] = $this -> model_account_customer -> get_PD_by_customer_id($this -> session -> data['customer_id']);
		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd.tpl', $data));
		}
	}
	//00 days 00 hours 00 minutes 00 senconds
	public function getAccountHolder($customer_id){
		$this -> load -> model('account/customer');
		$parrent = $this -> model_account_customer ->getAccount_holder($customer_id);
		return $parrent;
	}
	public function getParrent($customer_id){
		$this -> load -> model('account/customer');
		$parrent = $this -> model_account_customer ->getParrent($customer_id);
		return $parrent;
	}
	public function getPhone($customer_id){
		$this -> load -> model('account/customer');
		$phone = $this -> model_account_customer ->getPhone($customer_id);
		return $phone;
	}
	public function getMessages($transfer_id){
		$this->load->model('account/customer');
		$message = $this-> model_account_customer -> getMessage($transfer_id);
		$html ='';
		foreach ($message as $value) {
			$html .= '<h5 class="text-warning">
'.$this->getParrent($value['customer_id']).'
<span class="text-muted pull-right" style="font-size:12px;"><i class="fa fa-calendar-o"> </i> '.$value['date_added'].'</span>
</h5>';
			
			$html.= '<p>';
			$html .= $value['message'];
			$html .= '</p>';
		}
		return $html;
	}
	public function show_confirm($id_transfer){
		$transfer_confirm = $this -> model_account_customer -> getPDTranferByID($id_transfer);

		$html = '';
		$html .= '<table class="table table-bordered table-condensed table-hover ">
   <thead>
      <tr>
         <th colspan="2" class="fade in"> Information Transfer : <br>Information Bank</th>
      </tr>
   </thead>
   <tbody>
   	  <tr>
         <td>Amount</td>
         <td>'.number_format($transfer_confirm['amount']).' VNĐ</td>
      </tr>
      <tr>
         <td>Bank</td>
         <td>Vietcombank</td>
      </tr>
     
      <tr>
         <td>Account Holder</td>
         <td>'.$transfer_confirm['account_holder'].'</td>
      </tr>
      <tr>
         <td>Account Number</td>
         <td>'.$transfer_confirm['account_number'].'</td>
      </tr>
      <tr>
         <td>Phone Number</td>
         <td>'.$transfer_confirm['telephone'].'</td>
      </tr>
      <tr>
         <th colspan="2" class="fade in"><strong>Contact Info </strong></th>
      </tr>
      <tr>
         <td>Information ID Receive </td>
         <td>Name: <strong>'.$transfer_confirm['account_holder'].' ('.$transfer_confirm['username'].')</strong><br>Phone:<strong> '.$transfer_confirm['telephone'].'</strong></td>
      </tr>
      <tr>
         <td>Information Sponsor ID Receive </td>
         <td>Name: <strong>'.$this->getAccountHolder($transfer_confirm['p_node']).' ('.$this->getParrent($transfer_confirm['p_node']).')</strong><br>Phone:<strong> '.$this->getPhone($transfer_confirm['p_node']).'</strong></td>
      </tr>
   </tbody>
</table>';


		return $html;
	}
	public function show_transfer($pd_id){

			/*$this -> load -> model('account/customer');
			$this -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$this -> document -> addScript('catalog/view/javascript/pd/countdown.js');
			$this -> document -> addScript('catalog/view/javascript/pd/confirm.js');
			$this -> document -> addScript('catalog/view/javascript/bootstraptable/bootstrap-table-expandable.js');
			$this -> document -> addStyle('catalog/view/javascript/bootstraptable/bootstrap-table-expandable.css');*/
		
		$transferList = $this -> model_account_customer -> getPdFromTransferList($pd_id);
		
		$html ='';
		foreach ($transferList as $key => $value) {
			
			if (intval($value['pd_satatus']) === 0){
			$status = '<span class="label label-default">Waitting</span>';
		}
		if (intval($value['pd_satatus']) === 1){
			$status = '<span class="label label-success">Finish</span>';
		}
		if (intval($value['pd_satatus']) === 2){
			$status = '<span class="label label-danger">Report</span>';
		}
		if (intval($value['gd_status']) === 0){
			$status_gd = '<span class="label label-warning">Waitting</span>';
		}
		if (intval($value['gd_status']) === 1){
			$status_gd = '<span class="label label-success">Finish</span>';
		}
		if (intval($value['gd_status']) === 2){
			$status_gd = '<span class="label label-danger">Report</span>';
		}

			if(!$value['image']){
				$image = ' <div class="fileUpload btn btn-primary">
	    <span>Upload Bill</span>
	   	
	   	<input type="file" class="upload" name="avatar" id="file" accept="image/jpg,image/png,image/jpeg,image/gif"/>
	</div>    
		<div class="clearfix"></div>         
   		<img id="blah" src="#" style="display:none; max-width:90%; margin:0 auto" alt="your image" />

                          ';

			}
			if($value['image']){ 
               $image = '<a href="'.$value['image'].'" target="_blank"><img class="text-center" style="max-width:35%" src="'.$value['image'].'" style="display:block ; margin-top:20px;" /></a>';
            } 
			$html .= '<div class="row">
   
   <div class="col-lg-12 col-sm-12 col-xs-12 height">
      <i class="fa fa-code-fork" aria-hidden="true"></i> Trade Code:
      <strong class=" text-danger">'.$value["transfer_code"].'</strong>
   </div>
   <div class="col-lg-3 col-sm-6 col-xs-12">
      <i class="fa fa-calendar"> </i> Date Created:
      <strong class=" text-primary">'.date("d/m/Y", strtotime($value['date_added'])).'</strong>
   </div>
   
   <div class="col-lg-3 col-sm-6 col-xs-12">
      <i class="fa fa-cloud-upload"> UserID PD :</i> 
      <strong class="text-primary"> Bạn ('.$this->getParrent($value['pd_id_customer']).')</strong>
   </div>
   <div class="col-lg-3 col-sm-6 col-xs-12">
      <i class="fa fa-money"> Số Tiền :</i> 
      <strong class=" text-primary">'.(number_format($value['amount'])).' VNĐ</strong>
   </div>
   <div class="col-lg-3 col-sm-6 col-xs-12 ">
      <i class="fa fa-cloud-download"> UserID GD : </i> 
      <strong class=" text-primary">'.$value['username'].'</strong>
   </div>
   <div class="col-lg-4  col-sm-6 col-xs-12 height">
      <i class="fa fa-check-circle-o text-success">Status PD: </i>
      <span class="text-success">'.$status .'</span>
   </div>
   <div class="col-lg-4  col-sm-6 col-xs-12 height">
      <i class="fa fa-check-circle-o text-success"> Status GD:  </i>
      <span class="text-success">'.$status_gd.'</span>
   </div>
   <div class="col-lg-4 col-sm-6 col-xs-12 height">
      <span class="pull-left">
      <a class="btn btn-xs btn-primary" data-toggle="modal" href="#modal-id-'.$value['transfer_code'].'">Confirm Bill</a>
      <a class="btn btn-xs btn-info showdetails" data-toggle="modal" href="#modal-'.$value['transfer_code'].'">Detail </a>
      </span>
   </div>
   <div class="modal fade" id="modal-'.$value['transfer_code'].'">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">Confirm PD for '.$value['username'].'</h4>
            </div>
            <div class="modal-body">
               '.$this->show_confirm($value['id']).'
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <a class="btn btn-primary" data-toggle="modal" href="#modal-id-'.$value['transfer_code'].'">Confirm Transfer</a>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="modal-id-'.$value['transfer_code'].'">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">Message</h4>
            </div>
            <form class="comfim-pd" action="'.$this -> url -> link('account/pd/confirmSubmit', '', 'SSL').'" method="POST" enctype="multipart/form-data" style="
    text-align: left;
">
               <input type="hidden" value="'.$value['id'].'" name="token" />
               <div class="modal-body">
                  <div class="form-group">
                 
                     <br>
                     '.$image.'
                     <div class="error-file alert alert-dismissable alert-danger" style="display:none; margin:20px 0px;">
                        <i class="fa fa-fw fa-times"></i>Please chosen image with : "jpeg", "jpg", "png", "gif", "bmp"
                     </div>
                  </div>
                  <div class="form-group">
                     <textarea autofocus="" placeholder="Message" name="message" id="textmessages" class="form-control" style="width:100%" rows="2"></textarea>
                  </div>
                  <div class="form-group">
                  	'.$this->getMessages($value['id']).'
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Confirm</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
	function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#blah").attr("src", e.target.result);
            $("#blah").show();
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#file").change(function(){
    readURL(this);
});
</script>
';
		}
		
		return $html;
	}

	
	public function create(){
		
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/pd/create.js');
			$self -> load -> model('account/customer');
			
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		$block_id_gd = $this -> check_block_id_gd();

		if (intval($block_id_gd) !== 0) $this->response->redirect(HTTPS_SERVER . 'lockgd.html');
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		
		$CheckPDUpdate = $this -> model_account_customer -> getCheckPD($this -> session -> data['customer_id']);			
		
		$data['count'] = $CheckPDUpdate['check_PD'];
	//$rows =  $this -> model_account_customer ->countPD($this -> session -> data['customer_id']);
		//count($rows) >= 1 && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;
		$data['level'] = $this -> model_account_customer ->getLevel_by_customerid($this -> session -> data['customer_id']);

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd_create.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd_create.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd_create.tpl', $data));
		}
	}
	public function submit() {
		
		$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
		
		if ($this -> customer -> isLogged() && $this -> request -> get['amount'] && $this -> request -> get['Password2']) {
			$this -> load -> model('account/customer');
			$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		$block_id_gd = $this -> check_block_id_gd();

		if (intval($block_id_gd) !== 0) $this->response->redirect(HTTPS_SERVER . 'lockgd.html');
			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['Password2']);
			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;
			$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);
			$checkAccount_holder = $customer['account_holder'];
			$amount	= $this -> request -> get['amount'];
			$level = $this -> model_account_customer -> getTableCustomerMLByUsername($this -> session -> data['customer_id']);
			switch ($level['level']) {
                  case 1:
                    $ping = 1;
                    break;
                  case 2:
                    $ping = 2;
                    break;
                  case 3:
                    $ping = 3;
                    break;
                  case 4:
                    $ping = 4;
                    break;
                  case 5:
                    $ping = 5;
                    break;
                  case 6:
                    $ping = 6;
                    break;
                }
			if(intval($customer['ping']) < $ping){
				// /$this -> response -> redirect($this -> url -> link('account/token/order', 'token='.$pd['id'].'', 'SSL'));
				$json['pin'] = -1;
			}else{
				$json['pin'] = 1;
			}
			
			switch (intval($level['level'])) {
				case 1:
					$number_pd_day = 2;
					$number_pd_month = 4;
					break;
				case 2:
					$number_pd_day = 2;
					$number_pd_month = 8;
					break;
				case 3:
					$number_pd_day = 3;
					$number_pd_month = 10;
					break;
				case 4:
					$number_pd_day = 4;
					$number_pd_month = 15;
					break;
				case 5:
					$number_pd_day = 4;
					$number_pd_month = 20;
					break;
				default:
					$number_pd_day = 5;
					$number_pd_month = 25;
					break;
			}
			$CountDay = $this -> model_account_customer ->CountGDDay($number_pd_day,$number_pd_month);
			$json['checkCountDay']= (count($CountDay) != 0) ? 1 : -1;
			
			$GDTMP = $this -> model_account_customer -> getPDById($this -> session -> data['customer_id'], 1, 0);

			if (count($GDTMP) === 0) {
				$json['checkCountDay'] = 1;
			}
			//pdwaiting
			$json['account_number']= 1;
			$json['checkWaiting'] = 1;
			//$json['checkCountDay'] = 1;
			//$json['checkCountDay'] = 1;
			if ($json['password'] === 1 && $json['pin'] === 1 && $json['checkCountDay'] === 1 && $json['checkWaiting'] === 1 && $json['account_number']=== 1 ) {
				$check_GD_customer = $this -> model_account_customer -> check_GD_customer($this->session->data['customer_id']);
				
				if (count($check_GD_customer) > 0)
				{
					$this -> model_account_customer -> update_check_gd($check_GD_customer['id']);
					
				}
				
				$amount	= $this -> request -> get['amount'];



				$this -> model_account_customer ->updatePin_sub($this -> session -> data['customer_id'], $ping );
				switch ($amount) {
					case 7700000:
						$max_profit = 10010000;
						break;
					case 15400000:
						$max_profit = 20020000;
						break;
					case 23100000:
						$max_profit = 30030000;
						break;
					case 30800000:
						$max_profit = 40040000;
						break;
					case 38500000:
						$max_profit = 50050000;
					case 46200000:
						$max_profit = 50050000;
						break;
					default:
						die();
						break;
				}
				$pd_query = $this -> model_account_customer -> createPD($amount ,$max_profit);							
				$id_history = $this->model_account_customer->saveHistoryPin(
					$this -> session -> data['customer_id'],  
					'- 1',
					'Use Pin for PD'.$pd_query['pd_number'],
					'PD',
					'Use Pin for PD'.$pd_query['pd_number']
				);

				$json['data_link']= $this->url->link('account/pd/');
				// $json['data_link']= $this->url->link('account/pd/confirm&token='.$transfer_id.'');
				$json['ok'] = 1;
			}else{
				$json['ok'] = -1;
			}
			$getCustomer = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
			$mail = new Mail();
			$mail -> protocol = $this -> config -> get('config_mail_protocol');
			$mail -> parameter = $this -> config -> get('config_mail_parameter');
			$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
			$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
			$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
			$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

			//$mail -> setTo($this -> config -> get('config_email'));
			$mail -> setTo($getCustomer['email']);
			$mail -> setFrom($this -> config -> get('config_email'));
			$mail -> setSender(html_entity_decode("Golobal", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Congratulations Your Registration is Confirmed!");
			$mail -> setHtml('
				
		   <table align="center" bgcolor="#eeeeee" border="0" cellpadding="0" cellspacing="0" style="background:#eeeeee;border-collapse:collapse;line-height:100%!important;margin:0;padding:0;width:100%!important">
		   <tbody>
		      <tr>
		         <td>
		            <table style="border-collapse:collapse;margin:auto;max-width:635px;min-width:320px;width:100%">
		   <tbody>
		      <tr>
		         <td>
		            <table style="border-collapse:collapse;color:#c0c0c0;font-family:"Helvetica Neue",Arial,sans-serif;font-size:13px;line-height:26px;margin:0 auto 26px;width:100%">
		               <tbody>
		                  <tr>
		                     <td></td>
		                  </tr>
		               </tbody>
		            </table>
		         </td>
		      </tr>
		      <tr>
		         <td>
		            <table align="center" border="0" cellspacing="0" style="border-collapse:collapse;border-radius:3px;color:#545454;font-family:"Helvetica Neue",Arial,sans-serif;font-size:13px;line-height:20px;margin:0 auto;width:100%">
		   <tbody>
		      <tr>
		         <td>
		            <table border="0" cellpadding="0" cellspacing="0" style="border:none;border-collapse:separate;font-size:1px;height:2px;line-height:3px;width:100%">
		               <tbody>
		                  <tr>
		                     <td bgcolor="#9B59B6" valign="top"> </td>
		                  </tr>
		               </tbody>
		            </table>
		            <table border="0" cellpadding="0" cellspacing="0" height="100%" style="border-collapse:collapse;border-color:#dddddd;border-radius:0 0 3px 3px;border-style:solid;border-width:1px;width:100%" width="100%">
		   <tbody>
		      <tr>
		         <td align="center" valign="top">
		            <table border="0" cellpadding="0" cellspacing="0" width="100%">
		               <tbody>
		                  <tr>
		                     <td align="center" style="background:#ffffff">
		                        <a href="https://Iontach.us" target="_blank" data-saferedirecturl="happymoney.us">
		                           <h1 style="margin-top:30px; font-weight:bold;">Iontach</h1>
		                        </a>
		                     </td>
		                  </tr>
		               </tbody>
		            </table>
		         </td>
		      </tr>
		      <table style="background:#FFF; padding:25px;">
		      	<tbody>
		      		<tr>
		      			<td style="padding:30px;background:white;color:#525252;font-family:"Helvetica Neue",Arial,sans-serif;font-size:15px;line-height:22px;overflow:hidden;">
		            <p><span>Xin chào <b>'.$getCustomer['username'].'</b>,</span></p>
		            <p><span>Bạn vừa tạo PD với số tiền: </span> <strong><span style="color:#5cb85c">2,000,000 VND</span><span style="color:#f7931a"> </span></strong> Vào ngày '.date('d/m/Y H:i:s').'</p></td>
		      		</tr>
		      	</tbody>
		      </table>
		       <hr>

			');
			//$mail -> send();
			$this -> response -> setOutput(json_encode($json));
		}
	}
	
	public function transfer() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		$block_id_gd = $this -> check_block_id_gd();

		if (intval($block_id_gd) !== 0) $this->response->redirect(HTTPS_SERVER . 'lockgd.html');
		!$this -> request -> get['token'] && $this->response->redirect(HTTPS_SERVER . 'dashboard.html');

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		
		$getPDCustomer = $this -> model_account_customer -> getPDByCustomerIDAndToken($this -> session -> data['customer_id'], $this -> request -> get['token']);

		$getPDCustomer['number'] === 0 && $this->response->redirect(HTTPS_SERVER . 'dashboard.html');
		$getPDCustomer = null;


		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//get pd form transfer list
		$PdUser = $this -> model_account_customer -> getPD($this -> session -> data['customer_id']);

		$checkPdOfUser = null;
		foreach ($PdUser as $key => $value) {
			if($value['id'] === $this -> request -> get['token']){
				$checkPdOfUser = true;
				break;
			}
		}

		!$checkPdOfUser && $this->response->redirect(HTTPS_SERVER . 'dashboard.html');

		$data['transferList'] = $this -> model_account_customer -> getPdFromTransferList($this -> request -> get['token']);

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd_transfer.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd_transfer.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd_transfer.tpl', $data));
		}
	}
	public function confirm() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
			$self -> document -> addScript('catalog/view/javascript/pd/confirm.js');
		};
$block_id = $this -> check_block_id();
		
		if (intval($block_id) !== 0) $this->response->redirect(HTTPS_SERVER . 'lock.html');
		//method to call function
		$block_id_gd = $this -> check_block_id_gd();

		if (intval($block_id_gd) !== 0) $this->response->redirect(HTTPS_SERVER . 'lockgd.html');
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		//get image


		!$this -> request -> get['token']  && $this->response->redirect(HTTPS_SERVER . 'dashboard.html');

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		$data['transferConfirm'] = $this -> model_account_customer -> getPDTranferByID($this -> request -> get['token']);

		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd_confirm.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd_confirm.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd_confirm.tpl', $data));
		}
	}

	public function confirmSubmit() {
		$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
		//$json['ok'] = -1;
		
		if ($this -> customer -> isLogged() && $this -> request -> post['token'] && isset($this->request->files['avatar']['name'])) {
			$this -> load -> model('account/customer');
	
			$filename = html_entity_decode($this->request->files['avatar']['name'], ENT_QUOTES, 'UTF-8');

			$filename = str_replace(' ', '_', $filename);
			if(!$filename || !$this->request->files){
				die();
			}

			$file = $this -> request -> post['token'].'_'.$filename . '.' . md5(mt_rand()) ;

	
			move_uploaded_file($this->request->files['avatar']['tmp_name'], DIR_UPLOAD . $file);

		if (!empty($this->request->post['message'])) {
			$this->model_account_customer->saveMessage($this->session->data['customer_id'], $this->request->post['token'],$this->request->post['message']);
		}
			//save image profile
			$server = $this -> request -> server['HTTPS'] ? $this -> config -> get('config_ssl') :  $this -> config -> get('config_url');
			$linkImage = $server . 'system/upload/'.$file;
		
			$this -> model_account_customer -> updateStatusPDTransferList($this -> request -> post['token'],$linkImage);
			// die('999');
			//get PDID
			$Customer_Tranferlist = $this -> model_account_customer -> getPDByTranferID($this -> request -> post['token']);

			$PDCustomer = $Customer_Tranferlist['pd_id'];
			//count PD status tu transfer list check xem con dong du lieu nao chua finish
			//neu chua finish thi chua cho finish
			$GDCustomer = $Customer_Tranferlist['gd_id'];

			

			//count status
			$countNotPDFinsh = $this -> model_account_customer -> countStatusPDTransferList($PDCustomer);
			
			$countNotGDFinish = $this -> model_account_customer -> countStatusGDTransferList($GDCustomer);

			if(count($countNotPDFinsh) > 0 && intval($countNotPDFinsh['number']) === 0){
				// $this -> model_account_customer -> updateStusPDActive($PDCustomer, 1);

				$total = $this -> model_account_customer -> count_1date($PDCustomer);
				if (count($total) > 0) {
					$this -> model_account_customer -> update_max_profit($PDCustomer, floatval($total['filled'])*1.25);
					
				}
				$total2day = $this -> model_account_customer -> count_2date($PDCustomer);
				if (count($total2day) > 0) {
					$this -> model_account_customer -> update_max_profit($PDCustomer, floatval($total['filled'])*1.19);
				}

				$this -> model_account_customer -> updateStusPD($PDCustomer);
				$this -> model_account_customer -> updateCheck_R_WalletPD($PDCustomer);
				
			}
			if(count($countNotGDFinish) > 0 && intval($countNotGDFinish['number']) === 0){
				$this -> model_account_customer -> updateStusGD($GDCustomer);
			}
			$json['ok'] = 1;
			//$this->response->redirect(HTTPS_SERVER . 'provide-donation.html');
		} else{
			$this -> load -> model('account/customer');
			if (!empty($this->request->post['message'])) {

				$this-> model_account_customer-> saveMessage($this->session->data['customer_id'], $this->request->post['token'],$this->request->post['message']);
			}
			//$this->response->redirect(HTTPS_SERVER . 'provide-donation.html');
		}

		$this -> response -> setOutput(json_encode($json));
	}
	public function updatePercent($customer_id, $amount, $pd_number)
    {
        $this->load->model('account/customer');
   
        $customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
        $partent = $this -> model_account_customer -> getCustomerCustom($customer['p_node']);
        
        $priceCurrent = $amount; //(*100 000 000)
        // $price        = ($amount * 0.05);
        
        // $this->model_account_auto->update_C_Wallet($price, $partent['customer_id']);
      
        // $this->model_account_customer->saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' VND', "Sponsor 5% for ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
        $levelCustomer = intval($customer['level']);
        $pNode_ID = $partent['customer_id'];
        //F1
        $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
        if (intval(count($customerGET)) > 0) {
        	$levelCustomer1 = intval($customerGET['level']);
        	// ==========================
        	if (intval($customerGET['level']) >= 2) {
							
				switch (intval($customerGET['level'])) {
					case 2 :
						if($levelCustomer < 2){
							$percent = 2 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 2){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 3 :	
						if($levelCustomer < 3){
							$percent = 3 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 3){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 4 :	
						if($levelCustomer < 4){
							$percent = 4 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 4){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 5 :	
						if($levelCustomer < 5){
							$percent = 5 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 5){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 6 :	
						if($levelCustomer < 6){
							$percent = 6 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 6){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 7 :	
						if($levelCustomer < 7){
							$percent = 7 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 7){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
				}

			}
        	// =========================
        	// $percent           = 3;
         //    $percentcommission = $percent / 100;
         //    $this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
         //    $this->model_account_customer->saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent."% for ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");


            $pNode_ID = $customerGET['p_node'];
            //F2
            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);

	        if (intval(count($customerGET)) > 0) {
	        	$levelCustomer2 = intval($customerGET['level']);
	        	// ==========================
	        	if (intval($customerGET['level']) >= 2) {
								
					switch (intval($customerGET['level'])) {
						case 2 :
							if($levelCustomer1 < 2){
								$percent = 2 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 2){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']."  Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 3 :	
							if($levelCustomer1 < 3){
								$percent = 3 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 3){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 4 :	
							if($levelCustomer1 < 4){
								$percent = 4 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 4){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 5 :	
							if($levelCustomer1 < 5){
								$percent = 5 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 5){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 6 :	
							if($levelCustomer1 < 6){
								$percent = 6 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 6){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 7 :	
							if($levelCustomer1 < 7){
								$percent = 7 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 7){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
					}

				}
	        	// =========================
	            $pNode_ID = $customerGET['p_node'];
	            //F3
	            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
	            if (intval(count($customerGET)) > 0) {
	            	$levelCustomer3 = intval($customerGET['level']);
		        	// ==========================
		        	if (intval($customerGET['level']) >= 2) {
									
						switch (intval($customerGET['level'])) {
							case 2 :
								if($levelCustomer2 < 2){
									$percent = 2 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 2){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 3 :	
								if($levelCustomer2 < 3){
									$percent = 3 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 3){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 4 :	
								if($levelCustomer2 < 4){
									$percent = 4 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 4){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 5 :	
								if($levelCustomer2 < 5){
									$percent = 5 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 5){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 6 :	
								if($levelCustomer2 < 6){
									$percent = 6 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 6){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 7 :	
								if($levelCustomer2 < 7){
									$percent = 7 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 7){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
						}

					}
		        	// =========================
		            $pNode_ID = $customerGET['p_node'];
		            //F4
		            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
		             if (intval(count($customerGET)) > 0) {
		             	$levelCustomer4 = intval($customerGET['level']);
			        	// ==========================
			        	if (intval($customerGET['level']) >= 2) {
										
							switch (intval($customerGET['level'])) {
								case 2 :
									if($levelCustomer3 < 2){
										$percent = 2 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 2){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 3 :	
									if($levelCustomer3 < 3){
										$percent = 3 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 3){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 4 :	
									if($levelCustomer3 < 4){
										$percent = 4 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 4){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 5 :	
									if($levelCustomer3 < 5){
										$percent = 5 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 6 :	
									if($levelCustomer3 < 6){
										$percent = 6 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 7 :	
									if($levelCustomer3 < 7){
										$percent = 7 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
							}

						}
			        	// =========================
			            $pNode_ID = $customerGET['p_node'];
			            //F5
			            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
			            if (intval(count($customerGET)) > 0) {
			            	$levelCustomer5 = intval($customerGET['level']);
				        	// ==========================
				        	if (intval($customerGET['level']) >= 2) {
											
								switch (intval($customerGET['level'])) {
									case 2 :
										if($levelCustomer4 < 2){
											$percent = 2 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 2){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 3 :	
										if($levelCustomer4 < 3){
											$percent = 3 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 3){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 4 :	
										if($levelCustomer4 < 4){
											$percent = 4 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 4){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 5 :	
										if($levelCustomer4 < 5){
											$percent = 5 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 5){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 6 :	
										if($levelCustomer4 < 6){
											$percent = 6 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 6){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 7 :	
										if($levelCustomer4 < 7){
											$percent = 7 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 7){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
								}

							}
				        	// =========================
				        	// =========================
		            $pNode_ID = $customerGET['p_node'];
		            //F6
		            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
		             if (intval(count($customerGET)) > 0) {
		             	$levelCustomer6 = intval($customerGET['level']);
			        	// ==========================
			        	if (intval($customerGET['level']) >= 2) {
										
							switch (intval($customerGET['level'])) {
								case 2 :
									if($levelCustomer5 < 2){
										$percent = 2 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 2){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 3 :	
									if($levelCustomer5 < 3){
										$percent = 3 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 3){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 4 :	
									if($levelCustomer5 < 4){
										$percent = 4 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 4){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 5 :	
									if($levelCustomer5 < 5){
										$percent = 5 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 6 :	
									if($levelCustomer5 < 6){
										$percent = 6 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 7 :	
									if($levelCustomer5 < 7){
										$percent = 7 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
							}

						}
			        	// =========================
				            $pNode_ID = $customerGET['p_node'];
				            //F7
				            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        	if (intval($customerGET['level']) >= 4) {
												
									$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']."  Sponsor ".$percent." % for (F7) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");

							}
					        	// =========================
				            $pNode_ID = $customerGET['p_node'];
				            //F8
				            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        	if (intval($customerGET['level']) >= 4) {
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F8) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}
					        	// =========================
					           $pNode_ID = $customerGET['p_node'];
				            //F9
				            	$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				           		 if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        		if (intval($customerGET['level']) >= 4) {
					
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F9) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

									}
					        	// =========================
					             	$pNode_ID = $customerGET['p_node'];
				            //F10
				           			 $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            		if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        			if (intval($customerGET['level']) >= 4) {
					
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F10) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									

										}
					        	// =========================
					            
					        			$pNode_ID = $customerGET['p_node'];
				            //F11
							            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
							            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        				if (intval($customerGET['level']) >= 4) {
					
												$percent = 0.5;
												$percentcommission =0.5/100;
												$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
												$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F11) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

											}
					        	// =========================
					             			$pNode_ID = $customerGET['p_node'];
				            //F12
								            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
								            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        					if (intval($customerGET['level']) >= 4) {
					
													$percent = 0.5;
													$percentcommission =0.5/100;
													$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
													$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F12) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

												}
					        	// =========================
					             				$pNode_ID = $customerGET['p_node'];
				            //F13
				            					$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            					if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        						if (intval($customerGET['level']) >= 4) {
					
														$percent = 0.5;
														$percentcommission =0.5/100;
														$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
														$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F13) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

													}
					        	// =========================
					             					$pNode_ID = $customerGET['p_node'];
				            //F14
				            						$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            						if (intval(count($customerGET)) > 0) {
					        	// ==========================
											        	if (intval($customerGET['level']) >= 4) {
											
															$percent = 0.5;
															$percentcommission =0.5/100;
															$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
															$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F14) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
														

														}
											        	// =========================
											             $pNode_ID = $customerGET['p_node'];
				            //F15
											            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
											            if (intval(count($customerGET)) > 0) {
												        	// ==========================
												        	if (intval($customerGET['level']) >= 4) {
												
																	$percent = 0.5;
																	$percentcommission =0.5/100;
																	$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																	$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F15) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
															

															}
												        	// =========================
												             $pNode_ID = $customerGET['p_node'];
				            //F16
													            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
													            if (intval(count($customerGET)) > 0) {
														        	// ==========================
														        	if (intval($customerGET['level']) >= 4) {
														
																			$percent = 0.5;
																			$percentcommission =0.5/100;
																			$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																			$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F16) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																	

																	}
														        	// =========================
														            
														        	 $pNode_ID = $customerGET['p_node'];
				            //F17
														            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
														            if (intval(count($customerGET)) > 0) {
															        	// ==========================
															        	if (intval($customerGET['level']) >= 4) {
															
																				$percent = 0.5;
																				$percentcommission =0.5/100;
																				$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																				$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F17) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																		

																		}
															        	// =========================
															             $pNode_ID = $customerGET['p_node'];
				            //F18
															            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
															            if (intval(count($customerGET)) > 0) {
																        	// ==========================
																        	if (intval($customerGET['level']) >= 4) {
																
																					$percent = 0.5;
																					$percentcommission =0.5/100;
																					$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																					$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F18) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																			

																			}
																        	// =========================
																            
																        	$pNode_ID = $customerGET['p_node'];
				            //F19
																            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
																            if (intval(count($customerGET)) > 0) {
																	        	// ==========================
																	        	if (intval($customerGET['level']) >= 4) {
																	
																						$percent = 0.5;
																						$percentcommission =0.5/100;
																						$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																						$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F19) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																				

																				}
																	        	// =========================
																	             $pNode_ID = $customerGET['p_node'];
				            //F20
																	            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
																	            if (intval(count($customerGET)) > 0) {
																		        	// ==========================
																		        	if (intval($customerGET['level']) >= 4) {
																		
																							$percent = 0.5;
																							$percentcommission =0.5/100;
																							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F20) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																					

																					}
					        	// =========================        
					        	
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
						    	}
					        }
				        }
			        }
		        }
	        }	        
        }      
    }
    public function createpd_child(){
    	$transfer_code = $this -> request ->get['token'];
    	$this ->load->model('account/customer');
    	$select_tranfer = $this -> model_account_customer ->getTransferList($transfer_code);
    	//print_r($select_tranfer); die;
    	$pd_query = $this -> model_account_customer -> createPD_pnode($select_tranfer['amount'] ,$select_tranfer['amount']*1.15);							
		// $id_history = $this->model_account_customer->saveHistoryPin(
		// 	$this -> session -> data['customer_id'],  
		// 	'- 1',
		// 	'Use Pin for PD'.$pd_query['pd_number'],
		// 	'PD',
		// 	'Use Pin for PD'.$pd_query['pd_number']
		// );

		$this->model_account_customer->createTransferList($pd_query['pd_id'],$select_tranfer['gd_id'],$this->session->data['customer_id'],$select_tranfer['gd_id_customer'],$select_tranfer['amount']);
		$this -> model_account_customer ->update_status_pnode_pd($select_tranfer['id']);
		$this -> model_account_customer -> updateStatustranfer($select_tranfer['id']);
    	$this -> response -> redirect($this -> url -> link('account/dashboard#createPD', '', 'SSL'));
    }
    public function check_block_id(){
		$this->load->model('account/customer');
		$block_id = $this -> model_account_customer -> get_block_id($this -> customer -> getId());
		
		return intval($block_id['status']);

	}
	public function check_block_id_gd(){
		$this->load->model('account/customer');
		$block_id = $this -> model_account_customer -> get_block_id_gd($this -> customer -> getId());
		
		return intval($block_id);

	}
}
