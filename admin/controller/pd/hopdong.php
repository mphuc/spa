<?php
class ControllerPdHopdong extends Controller {
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

		$data['getaccount'] = $this->url->link('pd/hopdong/getaccount&token='.$this->session->data['token']);
		$data['getaccount_username'] = $this->url->link('pd/hopdong/getaccount_username&token='.$this->session->data['token']);
		$data['link_search'] = $this -> url -> link('pd/hopdong/search_name&token='.$this->session->data['token'].'', '', 'SSL');
		$data['link_search_username'] = $this -> url -> link('pd/hopdong/link_search_username&token='.$this->session->data['token'].'', '', 'SSL');
		$data['query_child'] = $this -> url -> link('pd/hopdong/query_child&token='.$this->session->data['token'].'', '', 'SSL');
		$data['load_date'] = $this -> url -> link('pd/hopdong/load_date&token='.$this->session->data['token'].'', '', 'SSL');
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/hopdong.tpl', $data));
	}
	
	public function search_name(){
		$name = $this -> request ->post['name'];
		$this -> load -> model('pd/registercustom');
		$get_name_customer = $this -> model_pd_registercustom -> get_name_customer($name);
		$i = 1;
		foreach ($get_name_customer as $value) {
			$get_filled_by_id = $this -> model_pd_registercustom -> get_filled_by_id($value['customer_id']);
		?>
			<tr>
				<td><?php echo $i++;?></td>
				<td><?php echo $value['username'];?></td>
				<td><?php echo $value['firstname'];?></td>
				<td><?php echo number_format($get_filled_by_id['sum_filled']);?></td>
				<td class="text-center"><a target="_blank" href="<?php echo $this->url->link('pd/printuser&id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
			</tr>
		<?php
		}

	}


	public function load_date(){
		$date = $this -> request ->post['date'];
		$this -> load -> model('pd/registercustom');

		$get_name_customer = $this -> model_pd_registercustom -> load_date($date);
		//print_r($get_name_customer); die;
		$i = 1;
		foreach ($get_name_customer as $value) {
			$get_filled_by_id = $this -> model_pd_registercustom -> get_filled_by_id($value['customer_id']);
		?>
			<tr>
				<tr>
				<td><?php echo $i++;?></td>
				<td><?php echo $value['username'];?></td>
				<td><?php echo $value['firstname'];?></td>
				<td><?php echo number_format($get_filled_by_id['sum_filled']);?></td>
				<td class="text-center"><a target="_blank" href="<?php echo $this->url->link('pd/printuser&id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
			</tr>
			
		<?php
		}

	}

	public function getCustomer($customer_id){
		$this -> load -> model('pd/registercustom');
		$getCustomer = $this -> model_pd_registercustom -> getCustomer($customer_id);
		return $getCustomer['firstname'];
	}
	
	public function getaccount() {
		if ($this -> request -> post['keyword']) {
			$this -> load -> model('pd/registercustom');
			$tree = $this -> model_pd_registercustom -> getCustomLike_name($this -> request -> post['keyword']);
			//print_r($tree); die;
			if (count($tree) > 0) {
				foreach ($tree as $value) {
					 echo '<li class="list-group-item" onClick="selectU(' . "'" . $value['firstname'] . "'" . ');">' . $value['firstname'] . '</li>';
				}
			}
		}
	}
	public function getaccount_username(){
		if ($this -> request -> post['keyword']) {
			$this -> load -> model('pd/register');
			$tree = $this -> model_pd_register -> getCustomLike($this -> request -> post['keyword']);
			
			if (count($tree) > 0) {
				foreach ($tree as $value) {
					 echo '<li class="list-group-item" onClick="selectU_username(' . "'" . $value['name'] . "'" . ');">' . $value['name'] . '</li>';
				}
			}
		}
	}
	public function link_search_username(){
		$name = $this -> request ->post['name'];
		$this -> load -> model('pd/registercustom');
		$get_name_customer = $this -> model_pd_registercustom -> get_name_customer_username($name);
		
		$i = 1;
		//print_r($get_name_customer); die;
		foreach ($get_name_customer as $value) {
			$get_filled_by_id = $this -> model_pd_registercustom -> get_filled_by_id($value['customer_id']);
		?>
			<tr>
				<tr>
				<td><?php echo $i++;?></td>
				<td><?php echo $value['username'];?></td>
				<td><?php echo $value['firstname'];?></td>
				<td><?php echo number_format($get_filled_by_id['sum_filled']);?></td>
				<td class="text-center"><a target="_blank" href="<?php echo $this->url->link('pd/printuser&id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
			</tr>
		<?php
		}
	}
	public function get_childrend($customer_id){
		$this -> load -> model('pd/registercustom');
		$get_childrend = $this -> model_pd_registercustom -> get_childrend($customer_id);
		return substr($get_childrend, 1);
	}
	public function view_hopdong(){
		$customer_id  = $this ->request -> get['customer_id'];
		$this -> load -> model('pd/registercustom');
		$data['customer'] = $this -> model_pd_registercustom ->get_username_id($customer_id);
		$data['hopdong'] = $this -> model_pd_registercustom ->get_hopdong_buyid($customer_id);
		$data['baotro'] = $this -> model_pd_registercustom -> get_baotro($customer_id);
		$data['get_name_customer'] = $this -> model_pd_registercustom -> get_name_customer($customer_id);
		
		$data['seft'] = $this;

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('pd/view_hopdong.tpl', $data));
	}
	public function query_child(){
		$customer_id = $this -> request-> get['id'];
		echo $customer_id;
		$get_childrend = $this -> get_childrend($customer_id);
		//print_r($get_childrend);die;
	}
	
	public function get_pakege_cha($customer_id){
		$this -> load -> model('pd/registercustom');
		$customer = $this -> model_pd_registercustom ->get_username_id($customer_id);
		return $customer['package'];
	}
	public function get_goidautu($customer_id){
		$this -> load -> model('pd/registercustom');
		$customer = $this -> model_pd_registercustom ->get_goidautu($customer_id);
		return $customer['package'];
	}
	public function get_hhtructiep($goicha,$goicon){
		if (intval($goicha) <= intval($goicon)) {
    		switch (intval($goicha)) {
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
    	
    		$price = (intval($goicon) * $per) / 100;
    	} else{
    		switch (intval($goicon)) {
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
    		$price = (intval($goicon) * $per) / 100;
    	}
    	
		$double = intval($goicha)*2;

		if ($price > $double) {
			$per_comission = $double;
		}else {
			$per_comission = $price;
		}
		return $per_comission;
	}
	public function edit_user(){
		$customer_id  = $this ->request -> get['customer_id'];
		$this -> load -> model('pd/registercustom');
		$data['customer'] = $this -> model_pd_registercustom ->get_username_id($customer_id);
		$data['seft'] = $this;
		$data['action_update'] = $this->url->link('pd/hopdong/submit_update&customer_id='.$customer_id, 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('pd/edit_user.tpl', $data));
	}

	public function submit_update(){
		$this -> load -> model('pd/registercustom');
		$customer_id  = $this ->request -> get['customer_id'];
		$newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$_POST['date_cmnd']);
		$date_cmnd = date('Y-m-d',strtotime($newDate));
		//print_r($date_cmnd); die;
		/*print_r($date_cmnd); die;*/
		if ($_POST['password'] == "")
		{
			$this -> model_pd_registercustom ->update_user($_POST['firstname'],$_POST['email'],$_POST['telephone'],$_POST['cmnd'],$_POST['account_holder'],$_POST['account_number'],$_POST['bank_name'],$_POST['branch_bank'],$_POST['address_cmnd'],$date_cmnd,$_POST['address_cus'],$customer_id,$password = false);
		}
		else
		{
			$this -> model_pd_registercustom ->update_user($_POST['firstname'],$_POST['email'],$_POST['telephone'],$_POST['cmnd'],$_POST['account_holder'],$_POST['account_number'],$_POST['bank_name'],$_POST['branch_bank'],$_POST['address_cmnd'],$date_cmnd,$_POST['address_cus'],$customer_id,$_POST['password']);
		}
		$data['customer'] = $this -> model_pd_registercustom ->get_username_id($customer_id);
		$data['action_update'] = $this->url->link('pd/hopdong/submit_update&customer_id='.$customer_id, 'token=' . $this->session->data['token'], 'SSL');
		$data['seft'] = $this;
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this-> session -> data['complate'] = "complate";
		$this->response->setOutput($this->load->view('pd/edit_user.tpl', $data));
	}
}