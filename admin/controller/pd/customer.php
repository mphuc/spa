<?php
class ControllerPdCustomer extends Controller {
	public function index() {
		$this->document->setTitle('Customer ');
		$this->load->model('pd/pd');
	
		
		if (isset($this->request->get['filter_status'])) {
				$status = $this->request->get['filter_status'];
				$data['filter_status'] = $this->request->get['filter_status'];
			
		} else{
			$status = null;
			$data['filter_status'] = null;
		}
		// echo "<pre>"; print_r($status); echo "</pre>"; die();
		$data['self'] = $this;
		
		$str = HTTPS_SERVER;

		$data['getaccount'] = $this->url->link('pd/history/getaccount&token='.$this->session->data['token']);
		$data['getaccount_username'] = $this->url->link('pd/history/getaccount_username&token='.$this->session->data['token']);
		$data['link_search'] = $this -> url -> link('pd/history/search_name&token='.$this->session->data['token'].'', '', 'SSL');
		$data['link_search_username'] = $this -> url -> link('pd/history/link_search_username&token='.$this->session->data['token'].'', '', 'SSL');
		$data['query_child'] = $this -> url -> link('pd/history/query_child&token='.$this->session->data['token'].'', '', 'SSL');
		$data['load_date'] = $this -> url -> link('pd/history/load_date&token='.$this->session->data['token'].'', '', 'SSL');
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this -> load -> model('pd/registercustom');


		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 40;
		$start = ($page - 1) * 40;

		$ts_history = $this -> model_pd_registercustom -> get_count_customer();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/customer', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		
		$data['customer'] =  $this-> model_pd_registercustom->get_all_customer($limit, $start);
		
		$data['pagination'] = $pagination -> render();


		$this->response->setOutput($this->load->view('pd/customer.tpl', $data));
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
				
				<td><?php echo $value['date_register_tree'];?></td>
				
				<td><?php echo $this->getCustomer($value['p_node']);?></td>
				<td class="text-center"><a href="<?php echo $this->url->link('pd/history/view_history&customer_id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><button class="btn btn-success"><i class="fa fa-external-link" aria-hidden="true"></i></button></a></td>
				<td class="text-center"><a href="<?php echo $this->url->link('pd/history/edit_user&customer_id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><button class="btn btn-primary	"><i class="fa fa-eyedropper" aria-hidden="true"></i></button></a></td>	
			
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
				<td><?php echo $i++;?></td>
				<td><?php echo $value['username'];?></td>
				<td><?php echo $value['firstname'];?></td>
				<td><?php echo number_format($get_filled_by_id['sum_filled']);?></td>
				<td class="text-center"><i class="fa fa-circle  <?php echo ($value['status_r_wallet'] == 1) ? "text-danger" : "text-success" ?>" aria-hidden="true"></i></td>
				<td><?php echo $value['date_register_tree'];?></td>
				<td><?php echo number_format($value['total_pd_left'])."/".number_format($value['total_pd_right']);?></td>
				<td><?php echo $this->getCustomer($value['p_node']);?></td>
				<td class="text-center"><a href="<?php echo $this->url->link('pd/history/view_history&customer_id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><button class="btn btn-success"><i class="fa fa-external-link" aria-hidden="true"></i></button></a></td>
				<td class="text-center"><a href="<?php echo $this->url->link('pd/history/edit_user&customer_id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><button class="btn btn-primary	"><i class="fa fa-eyedropper" aria-hidden="true"></i></button></a></td>	
			
			</tr>
		<?php
		}

	}

	public function getCustomer($customer_id){
		$this -> load -> model('pd/registercustom');
		$getCustomer = $this -> model_pd_registercustom -> getCustomer($customer_id);
		if (count($getCustomer) > 0 )
			return $getCustomer['username'];
		else
			return 0;
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
			//print_r($value); die;
		?>
			<tr>
				<td><?php echo $i++;?></td>
				<td><?php echo $value['username'];?></td>
				<td><?php echo $value['firstname'];?></td>
				<td><?php echo number_format($get_filled_by_id['sum_filled']);?></td>
				
				<td><?php echo $value['date_register_tree'];?></td>
				<td class="text-center">
	              <?php if ($value['level'] >= 2) { ?>
	                <i class="fa fa-circle" style="color: #4caf50" aria-hidden="true"></i>
	              <?php } else { ?>
	                  <i class="fa fa-circle" style="color: red" aria-hidden="true"></i>
	              <?php } ?>
	            </td>
				<td><?php echo $this->getCustomer($value['p_node']);?></td>
				<td class="text-center"><a href="<?php echo $this->url->link('pd/history/view_history&customer_id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><button class="btn btn-success"><i class="fa fa-external-link" aria-hidden="true"></i></button></a></td>
				<td class="text-center"><a href="<?php echo $this->url->link('pd/history/edit_user&customer_id='.$value['customer_id'].'&token='.$this->session->data['token']);?>"><button class="btn btn-primary	"><i class="fa fa-eyedropper" aria-hidden="true"></i></button></a></td>		
			</tr>
		<?php
		}
	}
	public function get_childrend($customer_id){
		$this -> load -> model('pd/registercustom');
		$get_childrend = $this -> model_pd_registercustom -> get_childrend($customer_id);
		return substr($get_childrend, 1);
	}
	public function view_history(){
		$customer_id  = $this ->request -> get['customer_id'];
		$this -> load -> model('pd/registercustom');
		$data['customer'] = $this -> model_pd_registercustom ->get_username_id($customer_id);
		$data['history'] = $this -> model_pd_registercustom ->get_history_buyid($customer_id);
		$data['baotro'] = $this -> model_pd_registercustom -> get_baotro($customer_id);
		$data['get_name_customer'] = $this -> model_pd_registercustom -> get_name_customer($customer_id);
		
		$data['seft'] = $this;

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('pd/view_history.tpl', $data));
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
		$data['action_update'] = $this->url->link('pd/history/submit_update&customer_id='.$customer_id, 'token=' . $this->session->data['token'], 'SSL');
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
		$data['action_update'] = $this->url->link('pd/history/submit_update&customer_id='.$customer_id, 'token=' . $this->session->data['token'], 'SSL');
		$data['seft'] = $this;
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this-> session -> data['complate'] = "complate";
		$this->response->setOutput($this->load->view('pd/edit_user.tpl', $data));
	}


	public function dautu_user(){
		$customer_id  = $this ->request -> get['customer_id'];
		$this -> load -> model('pd/registercustom');
		$data['customer'] = $this -> model_pd_registercustom ->get_username_id($customer_id);

		$data['show_pd_customer'] = $this -> model_pd_registercustom ->show_pd_customer($customer_id);
		//createPD
		
		$data['seft'] = $this;

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('pd/dautu_user.tpl', $data));
	}

	public function invesment()
	{

		$customer_id  = $this ->request -> post['customer_id'];
		$package  = $this ->request -> post['package'];
		$this -> load -> model('pd/registercustom');

		$getCustomer = $this -> model_pd_registercustom -> getCustomer($customer_id);

		$check_pd_customer = $this -> model_pd_registercustom -> check_pd_customer($customer_id,$package);
		$check_pd_customer > 0 && die("error");
		// tao pd
		switch ($package) {
			case 3000000:
				$doanhso_100 = 0;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				break;
			case 6000000:
				$doanhso_100 = 0;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				break;
			case 9000000:
				$doanhso_100 = 9;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				break;
			case 100000000:
				$doanhso_100 = 12;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 12;
				break;
			case 200000000:
				$doanhso_100 = 12;
				$doanhso_200 = 15;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 15;
				break;
			case 500000000:
				$doanhso_100 = 12;
				$doanhso_200 = 15;
				$doanhso_500 = 18;
				$doanhso_1450 = 0;
				$loinhuan = 18;
				break;
			case 1450000000:
				$doanhso_100 = 12;
				$doanhso_200 = 15;
				$doanhso_500 = 18;
				$doanhso_1450 = 24;
				$loinhuan = 24;
				break;
			default:
				$doanhso_100 = 0;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				$percent = 0;
				break;
		}
		$this -> model_pd_registercustom -> createPD($customer_id, $package,$doanhso_100,$doanhso_200,$doanhso_500,$doanhso_1450,$loinhuan);

		// update ML
		$this -> model_pd_registercustom -> update_customer_binary($customer_id, $getCustomer['p_node']);
		
		// update level
		$this -> model_pd_registercustom -> update_level_ml($customer_id, 2);

		
		// cap nhap total pd 
		$this -> model_pd_registercustom -> upadate_totla_pd($customer_id, $package,true);


		
		$get_parent = $this -> model_pd_registercustom -> getCustomer_ml($getCustomer['p_node']);
		if (count($get_parent) > 0)
		{
			//hoa hong truc tiep
			$amount = $this -> refferal_commision($customer_id,$package);
			
			// hoa hong cua hoa hong
			if (doubleval($amount) > 0)
			{
				$this -> hoahongf1($getCustomer['p_node'],$amount);
			}
			
		}
	
		$this-> session -> data['complate'] = "complate";
		$this->response->redirect($this->url->link('pd/customer/dautu_user', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
	}

	public function upgray_invesment()
	{
		$customer_id  = $this ->request -> post['customer_id'];
		$package  = $this ->request -> post['package'];
		$this -> load -> model('pd/registercustom');

		$getCustomer = $this -> model_pd_registercustom -> getCustomer($customer_id);

		$check_pd_customer = $this -> model_pd_registercustom -> check_pd_customer($customer_id,$package);
		$check_pd_customer > 0 && die("error");
		// tao pd
		switch ($package) {
			case 3000000:
				$doanhso_100 = 0;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				break;
			case 6000000:
				$doanhso_100 = 0;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				break;
			case 9000000:
				$doanhso_100 = 9;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				break;
			case 100000000:
				$doanhso_100 = 12;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 12;
				break;
			case 200000000:
				$doanhso_100 = 12;
				$doanhso_200 = 15;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 15;
				break;
			case 500000000:
				$doanhso_100 = 12;
				$doanhso_200 = 15;
				$doanhso_500 = 18;
				$doanhso_1450 = 0;
				$loinhuan = 18;
				break;
			case 1450000000:
				$doanhso_100 = 12;
				$doanhso_200 = 15;
				$doanhso_500 = 18;
				$doanhso_1450 = 24;
				$loinhuan = 24;
				break;
			default:
				$doanhso_100 = 0;
				$doanhso_200 = 0;
				$doanhso_500 = 0;
				$doanhso_1450 = 0;
				$loinhuan = 0;
				$percent = 0;
				break;
		}
		$this -> model_pd_registercustom -> createPD($customer_id, $package,$doanhso_100,$doanhso_200,$doanhso_500,$doanhso_1450,$loinhuan);
		
		// cap nhap total pd 
		$this -> model_pd_registercustom -> upadate_totla_pd($customer_id, $package,true);

		$get_parent = $this -> model_pd_registercustom -> getCustomer_ml($getCustomer['p_node']);
		if (count($get_parent) > 0)
		{
			//hoa hong truc tiep
			$amount = $this -> refferal_commision($customer_id,$package);
			
			// hoa hong cua hoa hong
			if (doubleval($amount) > 0)
			{
				$this -> hoahongf1($getCustomer['p_node'],$amount);
			}
			
		}
	
		$this-> session -> data['complate'] = "complate";
		$this->response->redirect($this->url->link('pd/customer/dautu_user', 'token=' . $this->session->data['token'] .'&customer_id='.$customer_id, 'SSL'));
		
	}

	public function hoahongf1($parent_id,$amount)
	{	
		$customer_id_f = $parent_id;
		while (true) {
			
			$get_customer_id_f = $this -> model_pd_registercustom -> getCustomer($customer_id_f);
			
			$get_parent_f = $this -> model_pd_registercustom -> getCustomer_ml_donation($get_customer_id_f['p_node']);
			
			if (count($get_parent_f) > 0)
			{
				$get_child_active = $this -> model_pd_registercustom -> get_customer_ml_pnode($get_parent_f['customer_id']);
				// level cha va co 3 con active
				if (intval($get_parent_f['level']) >= 2 && intval($get_child_active) >= 3)
				{	
					switch ($get_parent_f['filled']) {
						case 3000000:
							$percent = 30;
							break;
						case 6000000:
							$percent = 40;
							break;
						case 9000000:
							$percent = 50;
							break;
						case 100000000:
							$percent = 50;
							break;
						case 200000000:
							$percent = 50;
							break;
						case 500000000:
							$percent = 50;
							break;
						case 1450000000:
							$percent = 50;
							break;
						default:
							$percent = 0;
							break;
					}
					if ($percent > 0)
					{
						$check_packet_active = $this -> model_pd_registercustom -> get_ml_child_active($get_parent_f['customer_id'],$get_parent_f['filled']);

						if (intval($check_packet_active) >= 3)
						{
							$per_cent =  $percent;
						}
						else
						{
							$per_cent =  $percent-10;
						}
						$amount_receve = $amount * $per_cent /100;
						// cong diem
						$this -> model_pd_registercustom ->update_amount_hh_wallet($get_parent_f['customer_id'],$amount_receve,true);
						// balance
						$balance_hh_parent = $this -> model_pd_registercustom -> Get_amount_HH_Wallet($get_parent_f['customer_id']);
						// luu lich su

						$id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
		                $get_parent_f['customer_id'],
		                'Hoa hồng trên thu nhập trực tiếp F1', 
		                '+ ' . (number_format($amount_receve)) . ' VNĐ',
		                "Nhận ".$per_cent."% hoa hồng từ tài khoản ".$get_customer_id_f['username']." nhận ".(number_format($amount))." VNĐ",
		                $balance_hh_parent
		                );
						
					}
					
				}
				else
				{
					break;
				}	
			}
			else
			{
				break;
			}
			$customer_id_f = $get_parent_f['customer_id'];
		}
	}

	public function refferal_commision($customer_id,$package)
	{
		$getCustomer = $this -> model_pd_registercustom -> getCustomer($customer_id);

		$amount = 0;
		$get_parent = $this -> model_pd_registercustom -> getCustomer_ml($getCustomer['p_node']);
		if ($get_parent['level'] == 2)
		{
			$get_goidautu = $this -> model_pd_registercustom -> get_goidautu($getCustomer['p_node']);
			switch ($get_goidautu['package']) {
				case 3000000:
					$percent = 6;
					$max_profit = 30000000;
					break;
				case 6000000:
					$percent = 8;
					$max_profit = 40000000;
					break;
				case 9000000:
					$percent = 10;
					$max_profit = 50000000;
					break;
				case 100000000:
					$percent = 10;
					$max_profit = 50000000;
					break;
				case 200000000:
					$percent = 10;
					$max_profit = 50000000;
					break;
				case 500000000:
					$percent = 10;
					$max_profit = 50000000;
					break;
				case 1450000000:
					$percent = 10;
					$max_profit = 50000000;
					break;
				default:
					$percent = 0;
					$max_profit = 0;
					break;
			}
			if ($percent > 0)
			{
				$amount = doubleval($package) * $percent/100;

				if ($amount > $max_profit)
				{
					$amount = $max_profit;
				}
				$this -> model_pd_registercustom ->update_amount_r_wallet($get_parent['customer_id'],$amount,true);
				$balance_r_parent = $this -> model_pd_registercustom -> Get_amount_R_Wallet($get_parent['customer_id']);
				 $id_history = $this -> model_pd_registercustom -> saveTranstionHistory(
	                $get_parent['customer_id'],
	                'Hoa hồng trực tiếp', 
	                '+ ' . (number_format($amount)) . ' VNĐ',
	                "Nhận ".$percent."% hoa hồng trực tiếp từ tài khoản ".$getCustomer['username']." khi tri ân gói ".(number_format($package))." VNĐ.",
	                $balance_r_parent
	                ); 
			}
			
		}
		return $amount;
	}

	
}