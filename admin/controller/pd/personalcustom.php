<?php
class ControllerPdPersonalcustom extends Controller {
	private $error = array();

	public function index() {
		

		$this->document->addScript('view/javascript/personal/tree.min.js');

		$data['self'] = $this ;
	

		//=============================Refferal=======================



		$data['self'] = $this;
	

		//$data['customerChild'] = $this -> model_pd_register-> getParentByIdCustomer($this -> session -> data['customer_id']);
		
		//$total = $this -> model_account_customer-> getCountTreeCustom($this -> session -> data['customer_id']);
		$this -> load -> model('pd/registercustom');
		$id = 1;
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		if (isset($_GET['username']))
		{
			$username = $_GET['username'];
			$id_user = $this -> model_pd_registercustom ->get_id_buy_username($username);
			if (count($id_user) == 0) die("username không tồn tại");
			$id = $id_user['customer_id'];
		}
		if (isset($_GET['name']))
		{
			$name = $_GET['name'];
			$id_user = $this -> model_pd_registercustom ->get_id_buy_name($name);
			if (count($id_user) == 0) die("Tên không tồn tại");
			$id = $id_user['customer_id'];
		}
$data['trees'] =  HTTPS_SERVER . 'index.php?route=pd/personalcustom/get_BinaryTree&id='.$id.'&token=' . $this->session->data['token'];
		
		//==============================================================
$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	$this->response->setOutput($this->load->view('pd/personalcustom.tpl', $data));
	}
	

	
	
	public function get_BinaryTree(){

		$this -> load -> model('pd/registercustom');
		
		// $id = $this->request->get['id_user'];
		$id = $_GET['id'];

		$user = $this -> model_pd_registercustom -> getInfoUsers_binary($id);



		$node = new stdClass();


		$node->id = $id;
		
		// $node->text = $user['username'] ;

		$node->username = $user['username'] ;
		$node ->account_holder = $user['account_holder'];
		// $node -> telephone = $user['telephone'];
		// $node -> date_added = $user['date_added'];
		$node -> level = $user['level'];
		// $node-> level_user = $user["level_member"];
		switch (intval($user['level'])) {
			case '1':
				$type = 'darkturquoise';
				break;
			
			case '2':
				$type = 'red';
				break;
			default:
				$type = 'blue';
				break;
			
		}
		$node-> type = $type;
		// $node -> status_ml = $user['status_ml'];
		

		$date = strtotime(date('Y-m-d'));
		$monthNow = date('m',$date);
		$yearNow = date('Y',$date);
		$date_added = strtotime($user['date_added']);
		$month = date('m',$date_added);
		$year = date('Y',$date_added);
		
		// if($user['status'] == 0){
		// 	$node->iconCls = "level4";
		// }else if($monthNow == $month && $yearNow == $year){
		// 	$node->iconCls = "level2";
		// }else{
		// 	$node->iconCls = "level3";
		// }

		$node->fl = 1;

		$this->get_BinaryChildTree($node);

		$node = array($node);

	//	ob_clean();
		echo json_encode($node[0]);

		exit();

	}
	public function get_BinaryChildTree(&$node){

		$date = strtotime(date('Y-m-d'));
		$monthNow = date('m',$date);
		$yearNow = date('Y',$date);
		
		$this -> load -> model('pd/register');
		$left_row = $this -> model_pd_registercustom ->getLeftO($node->id);
		
		// print_r($left_row);
		// die();
			$left = new stdClass();
		
		    foreach ($left_row as $key => $value) {
		        $left->$key = $value;
		    } 
			
			$node->children = array();

			if(isset($left_row["id"])){

				$left->fl = $node->fl +1;
				$left -> position ='left';
				$lv = $node->level;
// switch (intval($lv)) {
// 			case '1':
// 				$type = 'darkturquoise';
// 				break;
			
// 			case '2':
// 				$type = 'red';
// 				break;
// 			default:
// 				$type = 'blue';
// 				break;
			
// 		}
		$left-> type = 'blue';
				$left -> empty = false;
				
					$this->get_BinaryChildTree($left);
				
				
				array_push($node->children , $left);			

			}
		

		$right_row = $this -> model_pd_registercustom ->getRightO($node->id);
		$right = new stdClass();
	    foreach ($right_row as $key => $value) {
	        $right->$key = $value;
	    } 
		
		if(isset($right_row["id"])){

			$right->fl = $node->fl +1;
			$right -> position ='right';
$lv = $node->level;
// switch (intval($lv)) {
// 			case '1':
// 				$type = 'darkturquoise';
// 				break;
			
// 			case '2':
// 				$type = 'red';
// 				break;
// 			default:
// 				$type = 'blue';
// 				break;
			
// 		}
		$right-> type = 'darkturquoise';

			$right -> empty = false;
			
				$this->get_BinaryChildTree($right);

			

			array_push($node->children , $right);
		}
		

		if(count($node->children) ==0) $node->children = 0;

		return;

	}

	public function getcurent(){
		$this -> load -> model('pd/registercustom');
		$node = new stdClass();


		$node->id = '80';
		$node->fl = 1;
		$children = $this -> show_child_customer($node);
		

		//$this->get_BinaryChildTree($node);

		$node = array($node);

	//	ob_clean();
		print_r($node[0]);
		
		//$this->get_array($node[0]->children);
		exit();
	}
	/*public function get_array($array){
		$array_id = array();
		if (count($array) > 0)
		{
			foreach ($array as $key => $value) {
				//print_r($value);
				array_push($array_id , $value->customer_id);
				foreach ($value->children as $key1 => $value1) {
					array_push($array_id , $value1->customer_id);
					//$this ->get_array($value1->children);
				}
			}
		}
		
		print_r($array_id);
	}*/


	public function show_child_customer($node){
		$array = array();
		$this -> load -> model('pd/registercustom');
		$left_row = $this -> model_pd_registercustom ->getLeftO_new($node->id);
			$left = new stdClass();
		    foreach ($left_row as $key => $value) {
		        $left->$key = $value;
		    } 
			$node->children = array();

			if(isset($left_row["id"])){
				$left->fl = $node->fl +1;
				//$left -> position ='left';
				//$left -> empty = false;
				$this->show_child_customer($left);
				array_push($node->children , $left);

			}
		$right_row = $this -> model_pd_registercustom ->getRightO_new($node->id);
		$right = new stdClass();
	    foreach ($right_row as $key => $value) {
	        $right->$key = $value;
	    } 
		if(isset($right_row["id"])){

			$right->fl = $node->fl +1;
			//$right -> position ='right';
			//$right -> empty = false;
			$this->show_child_customer($right);
			array_push($node->children , $right);
		}
		if(count($node->children) ==0) $node->children = 0;

		return;

	}

	public function get_childrend(){
		$customer_id = 80;
		
		$this -> load -> model('pd/registercustom');
		$a = $this -> model_pd_registercustom -> get_childrend($customer_id);
		print_r($a);
	}
	public function getInfoCustomer() {
		$id_user = $this -> request -> get['id'];

		$this -> load -> model('pd/registercustom');

		$user = $this -> model_pd_registercustom -> getCustomer($id_user);
		$json = array();
		$json['nameCustomer'] = $user['firstname'];
		$json['telephone'] = $user['telephone'];
		$json['total_left'] = $this -> model_pd_registercustom -> getSumLeft($id_user);
		$json['total_right'] = $this -> model_pd_registercustom -> getSumRight($id_user);
		$json['floor'] = $this -> model_pd_registercustom -> getSumFloor($id_user);
		$json['total'] = $json['total_left'] + $json['total_right'];
		$this -> response -> addHeader('Content-Type: application/json');
		$this -> response -> setOutput(json_encode($json));
		
	}
}
