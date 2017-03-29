<?php
class ControllerPaymentAuthorizenetAim extends Controller {

	public function index() {

		# code...
		$this->load->model('payment/bitcoin');

		if(intval($this -> model_payment_bitcoin -> getTimer()) === 1){

			$this -> model_payment_bitcoin -> set_bitcoin_wallet() ;

			$this -> model_payment_bitcoin -> update_bitcoin_wallet();

			$this -> model_payment_bitcoin -> set_bitcoin_wallet_bk() ;
			
			$this -> model_payment_bitcoin ->updateTimer();

			//create input wallet

			$url = "https://pf.block.io/api/v2/create_forwarding_address/?api_key=f8e9-d4df-6862-2016&to_address=38Lg6yUsiEPaHDh33DLxeULnbpexSsm89E";
			$reponse = file_get_contents($url);
			$reponse = json_decode($reponse);
			if($reponse -> status === 'success'){
				$forwarding_private_key = $reponse -> data -> forwarding_private_key;
				$input_address = $reponse -> data -> forwarding_address;
				$my_address =  $reponse -> data -> to_address;
				$amount = $this -> model_payment_bitcoin -> get_amount_total();

				$this -> model_payment_bitcoin -> update_table_timer_update_wallet($forwarding_private_key, $input_address, $my_address, $amount['total']);

				$this -> model_payment_bitcoin -> update_wallet_timer($input_address);
			}

		}

	}
}