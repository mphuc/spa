<?php
class ControllerAccountRefferal extends Controller {

	public function index() {	

		function myCheckLoign($self) {
			return $self->customer->isLogged() ? true : false;
		};

		function myConfig($self){

				$self->document->addScript('catalog/view/javascript/refferal/refferal.js');
			$self->document->addScript('catalog/view/javascript/personal/personal.js');
		};
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$data['language']= $getLanguage;
		$language = new Language($getLanguage);
		$language -> load('account/refferal');
		$data['lang'] = $language -> data;
		
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this->response->redirect($this->url->link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));		


		//data render website
		//start load country model
		$this -> load -> model('customize/country');
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;      

		$limit = 10;
		$start = ($page - 1) * 10;
		$refferals_total = $this->model_account_customer->getTotalRefferalByID($this -> customer -> getId());
		$refferals_total = $refferals_total['number'];

		$pagination = new Pagination();
		$pagination->total = $refferals_total;
		$pagination->page = $page;
		$pagination->limit = $limit; 
		$pagination->num_links = 5;
		$pagination->text = 'text';
		$pagination->url = $this->url->link('account/refferal', 'page={page}', 'SSL');

		$data['refferals'] = $this->model_account_customer->getRefferalByID($this -> customer -> getId() , $limit , $start);
		// echo "<pre>"; print_r($data['refferals']); echo "</pre>"; die();
		$data['pagination'] = $pagination->render();

		$data['base'] = $server;
		$data['self'] = $this;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/refferal.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/refferal.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/refferal.tpl', $data));
		}
	}
	public function getCountry($id){
		$this->load->model('account/customer');
		$country = $this->model_account_customer->getCountryByID($id);
		return 'VN';

	}
	
	public function getlevel(){
		if($this->customer->isLogged() && $this -> request -> get['id'] ) {
			$this->load->model('account/customer');
			$json['success'] = intval($this->model_account_customer->getCountLevelCustom($this -> request -> get['id'] , $this -> request -> get['level']));
			$this -> response -> setOutput(json_encode($json));
		}
	}


	public function countFloor($limit, $offset){

		$this -> load -> model('account/customer');
		$floor = $this->model_account_customer->getCountFloor($this -> session -> data['customer_id']);
				//Level 2
		if(!empty($floor1)){
			$data['floor1'] = count($floor1);
			$arrId='';
			foreach ($floor1 as $value) {
				$arrId .= ','.$value['customer_id'];
			}
			$arrId = substr($arrId, 1);
			$json['customerFloor1'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
			$json['arrId1'] = $arrId;
			$floor2 = $this->model_account_customer->getCountFloor($arrId);
			$data['floor2'] = count($floor2);
		}
		$totalFloor = intval($this -> sumFloor());
		
		for ($i=1; $i <= $totalFloor; $i++) { 

			if(!empty($floor)){
				$data['floor'.$i] = count($floor);
				$arrId='';
				foreach ($floor as $value) {
					$arrId .= ','.$value['customer_id'];
				}
				$arrId = substr($arrId, 1);
				$json['customerFloor'.$i] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
				$json['arrId'.$i] = $arrId;
				$floor = $this->model_account_customer->getCountFloor($arrId);
				$data['floor'.$i] = count($floor);
			}
		}
		
		return $json;
	}
	public function getParrent($customer_id){
		$this -> load -> model('account/customer');
		$parrent = $this -> model_account_customer ->getParrent($customer_id);
		return $parrent;
	}
	public function checkPD($customer_id){
		$this->load->model('account/customer');
		$rows = $this -> model_account_customer -> checkPD($customer_id);
		$count = count($rows) > 0 ? 1 : 2;
		return $count;
	}
	public function getPD($customer_id){
		$this->load->model('account/customer');
		$rows = $this -> model_account_customer -> getPDLimit1($customer_id);
		if (!empty($rows)) {
			$PD = $rows['filled']/100000000;
		}else{
			$PD = 0;
		}
		
		return $PD;
	}
	public function sumFloor(){
		$this->load->model('account/customer');
		$floor = $this -> model_account_customer -> getSumFloor($this -> session -> data['customer_id']);
		$floor = intval($floor);
		return $floor;
	}
	public function customerFloor(){
		$this -> load -> language('account/personal');
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/personal');
		$lang = $language -> data;

		$limits = 10;
		
		if (isset($this -> request -> get['prev'])) {
			$limits = intval($this -> request -> get['prev'])-10;
		}
		if (isset($this -> request -> get['next'])) {
			$limits = intval($this -> request -> get['next'])+10;
		}
		if ($limits == 0) {
			$limits = 10;
		}

		$page = intval($limits)/10;
		
		$limit = 10;
		
		$start = ($page - 1) * 10;
		
		$customerFloor = $this -> countFloor($limit,$start);
		if ($this->request->get['floor']) {
			$floor = $this -> request -> get['floor'];
		}else{
			$floor = $floor1;
		}
		
		$totalFloor = intval($this -> sumFloor());
		for ($i=1; $i <= $totalFloor; $i++) { 
			if ($floor == 'floor'.$i) {
				$arrId = $customerFloor['arrId'.$i];
			}
		}

		
		$ts_floor = $this -> model_account_customer -> getTotalCustomerFloor($arrId);
		$ts_floor = $ts_floor['number'];
		
		//Floor 1
		for ($i=1; $i <=	 $totalFloor; $i++) { 
			if ($floor == 'floor'.$i) {
				if (!empty($customerFloor['customerFloor'.$i])) {
					
					$fl = 0;
					
					$customerFloor = $customerFloor['customerFloor'.$i];

					//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
					$fl = $fl.$i;
					$fl = '';
					$fl .=' <h3 class="panel-title" style="
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    color: #760e0f;
">F'.$i.' ('.$ts_floor.' Member)</h3>';
					$fl .= '<table id="datatable" class="table table-striped table-bordered">';
			        $fl .= '   <thead>';
			        $fl .= '      <tr>';
					$fl .= '       	<th class="text-center">No</th>';
					$fl .= '           <th>Username</th>';
					$fl .= '           <th>Name</th>';  
					$fl .= '           <th>Wallet</th>';
					$fl .= '           <th>Phone number</th>';
					$fl .= '           <th>Mail</th>';
					$fl .= '           <th>Parrent</th>';
					$fl .= '           <th>Investment Package</th>';
					$fl .= '           <th>Status</th>';
					$fl .= '           <th>Country</th>';
			        $fl .= '      </tr>';
			        $fl .= '       </thead>';
					$fl .= '<tbody>';
					$count = 1;
					foreach ($customerFloor as $key => $value) {
						$fl .= '<tr>';
						$fl .= '<td data-title="No" align="center">'.$count.'</td>';
						$fl .= '<td data-title="Username">'.$value['name'].'</td>';
						$fl .= '<td data-title="Name">'.$value['firstname'].'</td>';
						$fl .= '<td data-title="Wallet">'.$value['wallet'].'</td>';
						$fl .= '<td data-title="Phone number">'.$value['telephone'].'</td>';
						$fl .= '<td data-title="Mail">'.$value['email'].'</td>';
						$fl .= '<td data-title="Parrent">'.$this -> getParrent($value['p_node']).'</td>';
						$fl .= '<td data-title="Investment">'.$this -> getPD($value['customer_id']).' BTC </td>';
						
						$fl .= '<td data-title="Status">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">Active</span>' : '<span class="text-warning">Waiting</span>').'</td>';
						 // $fl .= '<td data-title="Country">'.$this -> getCountry($value['country_id']).'</td>';
					
						$fl .= '</tr>';
						$count++;
					}
					$fl .= '</tbody>';
					$fl .= '</table>';
					$fl .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
					$fl .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
						<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
					
					$json['fl'.$i] = $fl;
					$fl = null;
				}
			}
		}
	
		$this -> response -> setOutput(json_encode($json));
	}
}