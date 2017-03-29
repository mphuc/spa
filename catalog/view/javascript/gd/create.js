$(function() {

	location.hash === '#success' && $('#sucess-alert').show();

	window.choise = null;
	window.value = null;
	var c_wallet_current = $('#c-wallet').data('value');
	var r_wallet_current = $('#r-wallet').data('value');

	
	/*$('#createGD input[type=radio][name=FromWallet]').change(function(){
		alert(c_wallet_current);
		$('#amount').val('');
		var valRadio = parseInt($(this).val());
		var valBTC = null;
		$('#createGD button').attr('disabled', false);
		valRadio === 1 ? (
			window.choise = 1,
			window.value =  parseFloat($('#c-wallet').data('value')),

			$('#amount').val(parseFloat(window.value) + ' VNĐ')
		) : (
			window.choise = 2,
			window.value =  parseFloat($('#r-wallet').data('value')),
			$('#amount').val(parseFloat(window.value) + ' VNĐ')
		);
	});*/


	$('#createGD').on('submit', function() {
		$(this).ajaxSubmit({
			beforeSubmit : function(arr, $form, options) {

				$('#Password2-error').hide();
				$('#err-c-wallet').hide();
				$('#sucess-alert').hide();
				$('#err-pin').hide();
				$('#err-r-wallet_max').hide();
				$('#err-c-wallet_max').hide();
				$('#createGD button').attr('disabled', true);

				if($('#amount').val() == ""){
					window.funLazyLoad.reset();
					$('#enter_amount').show();
					
					$('#createGD button').attr('disabled', false);
					return false;

				}
				/*if ($("#C_Wallet").is(":checked")){
					if ($('#amount').val() > c_wallet_current){
						$('#err-c-wallet_max').show();
						window.funLazyLoad.reset();
						
						$('#createGD button').attr('disabled', false);
						return false;
					}
				}
				if ($("#R_Wallet").is(":checked")){
					if ($('#amount').val() > r_wallet_current){
						$('#err-r-wallet_max').show();
						window.funLazyLoad.reset();
						
						$('#createGD button').attr('disabled', false);
						return false;
					}
				}*/
				
				/*if(window.choise === 2 && window.value === 0){
					window.funLazyLoad.reset();
					return false;
				}*/
				if(!$('#Password2').val()){
					$('#Password2-error').show();
					return false;
				}
				window.funLazyLoad.start();
				window.funLazyLoad.show();

				$('.alert').hide();
			},
			type : 'GET',
			data : {
				
			},
			success : function(result) {
				$('#createGD button').attr('disabled', false);
				
				result = $.parseJSON(result);

				_.has(result, 'password') && result['password'] === -1 && $('#err-passs').show() && window.funLazyLoad.reset();
				_.has(result, 'checkConfirmPD') && result['checkConfirmPD'] === -1 && $('#err-checkConfirmPD').show() && window.funLazyLoad.reset();
				_.has(result, 'pin') && result['pin'] === -1 && $('#err-pin').show() && window.funLazyLoad.reset();
				_.has(result, 'weekday') && result['weekday'] === -1 && $('#err-weekday').show().html('You may only withdraw maxnimum amount of '+result.max_month_gd+' VND') && window.funLazyLoad.reset();
				if (result.amount_c_min == -1)
				{
					$('#err-c-wallet').html("You must withdraw more "+result.amount_c_min_gd+" VND.").show();
					window.funLazyLoad.reset();
				}
				if (result.amount_c_min_30 == -1)
				{
					$('#err-c-wallet').html("You must withdraw the maxnimum 70% C-Wallet balance").show();
					window.funLazyLoad.reset();
				}
				if (result.amount_r_min == -1)
				{
					$('#err-r-wallet_max').html("You must withdraw more "+result.amount_r_min_gd+" VND.").show();
					window.funLazyLoad.reset();
				}
				if(_.has(result, 'ok') && result['ok'] === 1){
					if(location.hash === ''){
						location.href = location.href+'#success';
					}
					location.reload(true);
				}  
				
			}
		});
		return false;
	});

});
