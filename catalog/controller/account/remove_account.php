<?php

class ControllerAccountRemoveaccount extends Controller {

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/remove_account.js');

		};
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$data['self'] = $this;
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;


		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/remove_account.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/remove_account.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/remove_account.tpl', $data));
		}
	}

	public function submit(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			
		};
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$this -> load -> model('account/customer');
		if ($this -> customer -> isLogged() && $this -> request -> post['content'] && $this -> request -> post['Password2']) {
			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> post['Password2']);
			if ($variablePasswd['number'] != '0')
			{
				$this -> model_account_customer -> create_reason($this-> session->data['customer_id'],$this -> request -> post['content']);
				$this -> model_account_customer -> up_status_removeaccount($this-> session->data['customer_id'],10);

				$get_pnode = $this -> model_account_customer -> get_ml_customer($this-> session->data['customer_id'])['p_node'];
				
				$get_all_pnode = $this -> model_account_customer -> get_all_pnode($this-> session->data['customer_id']);
				foreach ($get_all_pnode as $value) {
					$this -> model_account_customer -> remove_account($value['customer_id'],$get_pnode);
				}
				$this -> model_account_customer -> remove_account($this-> session->data['customer_id'],0);
				$json['complete'] = 1;
				$this->event->trigger('pre.customer.logout');

				$this->customer->logout();
				$this->cart->clear();

				unset($this->session->data['wishlist']);
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_address']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['comment']);
				unset($this->session->data['order_id']);
				unset($this->session->data['coupon']);
				unset($this->session->data['reward']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);

				$this->event->trigger('post.customer.logout');
				$this -> response -> setOutput(json_encode($json));
			}
			else
			{
				$json['Password2'] = -1;
				$this -> response -> setOutput(json_encode($json));
			}
		}
	}
}