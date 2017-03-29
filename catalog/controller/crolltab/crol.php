<?php
class ControllerCrolltabCrol extends Controller {

	function auto_get_PD(){
		// check PD >24h
		$this -> load -> model('account/auto');
		// PD user
		$getallPD = $this -> model_account_auto -> getPD_all24h();

		
		$getallGD = $this -> model_account_auto -> getGD_all24h();
			
		if (count($getallPD) > 0 && count($getallGD) == 0)
		{
			// get user admin
			$get_admin_PD = $this -> model_account_auto -> get_admin_PD();
			// create tranferlist

			$id_gd = $this -> model_account_auto -> create_GD($quy_bo_tro['customer_id'], $amount, $amount);

			// update date cycle
			$this -> model_account_auto -> update_date_cycle($customer_id);
			print_r($get_admin_PD); die;
		}
		

	}
}